<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends admin_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('setting_root/m_user');
		$this->load->model('setting_root/m_log');
		$this->load->model('setting_admin/m_pegawai');

		// Halaman profil (update_view2/update2/update3) dicek per method:
		// boleh untuk akun sendiri atau admin yang berhak atas user tersebut.
		// Selain itu manajemen user hanya untuk admin.
		$method = strtolower((string) $this->router->fetch_method());
		if (!in_array($method, array('update_view2', 'update2', 'update3'), TRUE)) {
			$this->wajib_admin();
		}
	}

	// boleh_kelola_user() ada di MY_Controller (dipakai juga controller Log)

	private function wajib_boleh_kelola_user($target_id)
	{
		if (!$this->boleh_kelola_user($target_id)) {
			$this->session->set_flashdata('message', 'Anda tidak berhak mengelola user ini.');
			redirect('beranda', 'refresh');
		}
	}

	// Akun sendiri, atau admin yang berhak atas user tersebut
	private function wajib_diri_sendiri_atau_berhak($target_id)
	{
		if ((int) $target_id !== (int) $this->session->userdata('user_id')) {
			$this->wajib_boleh_kelola_user($target_id);
		}
	}

	// Grup yang boleh diberikan: non super admin tidak boleh memberi grup khusus
	// (Asisten, Sekda, Pimpinan DPRD, Walikota, Inspektorat, Super Admin).
	// Grup SUPER ADMIN (100) tidak bisa diberikan lewat form, hanya dipertahankan
	// untuk akun yang memang sudah super admin ($user_id).
	private function saring_grup($groups, $user_id = 0)
	{
		$super_lama = in_array(100, $this->grup_user($user_id), TRUE);
		$hasil = array();
		foreach ((array) $groups as $g) {
			$g = (int) $g;
			if ($g <= 0 || (!$this->is_super_admin() && in_array($g, GRUP_KHUSUS_SUPER_ADMIN, TRUE))) {
				continue;
			}
			if ($g === 100 && !$super_lama) {
				continue;
			}
			$hasil[] = $g;
		}
		return $hasil;
	}

	// skpd_id dari form bila SKPD-nya ada; NULL bila kosong / tidak valid (SKPD lama dipertahankan)
	private function skpd_dari_post()
	{
		$id = (int) $this->input->post('skpd_id');
		if ($id <= 0) {
			return NULL;
		}
		return $this->db->where('skpd_id', $id)->count_all_results('table_skpd') > 0 ? $id : NULL;
	}

	// Pilihan grup di form user: admin OPD tidak melihat grup khusus.
	// Grup berkuota yang sudah penuh diberi keterangan ->penuh (ditampilkan nonaktif di form),
	// kecuali grup yang sudah dipegang user yang sedang diubah ($user_id).
	private function grup_boleh_dipilih($user_id = 0)
	{
		$grup = $this->m_user->groups();
		if (!$this->is_super_admin()) {
			$grup = array_values(array_filter($grup, function ($g) {
				return !in_array((int) $g->id, GRUP_KHUSUS_SUPER_ADMIN, TRUE);
			}));
		}
		$lama = $this->grup_user($user_id);
		// akun super admin: tampilkan grup SUPER ADMIN agar tetap terpilih
		// (m_user->groups() menyembunyikannya karena tidak boleh diberikan ke akun lain)
		if ($this->is_super_admin() && in_array(100, $lama, TRUE)) {
			$super = $this->db->where('id', 100)->get('groups')->row();
			if ($super) {
				$grup[] = $super;
			}
		}
		foreach ($grup as $g) {
			$id = (int) $g->id;
			$g->penuh = '';
			if (isset(KUOTA_GRUP_KHUSUS[$id]) && !in_array($id, $lama, TRUE)) {
				$terisi = count($this->pemegang_aktif_grup($id, $user_id));
				if ($terisi >= KUOTA_GRUP_KHUSUS[$id]) {
					$g->penuh = $terisi . '/' . KUOTA_GRUP_KHUSUS[$id];
				}
			}
		}
		return $grup;
	}

	// Id grup yang dimiliki user
	private function grup_user($user_id)
	{
		if ((int) $user_id <= 0) {
			return array();
		}
		$baris = $this->db->select('group_id')->where('user_id', (int) $user_id)->get('users_groups')->result();
		return array_map(function ($b) { return (int) $b->group_id; }, $baris);
	}

	// Akun AKTIF pemegang grup (tanpa $kecuali_user_id)
	private function pemegang_aktif_grup($group_id, $kecuali_user_id = 0)
	{
		return $this->db->select('users.id, users.username')
			->from('users_groups')
			->join('users', 'users.id = users_groups.user_id')
			->where('users_groups.group_id', (int) $group_id)
			->where('users.active', 1)
			->where('users.id <>', (int) $kecuali_user_id)
			->get()->result();
	}

	// '' bila aman; pesan error bila ada grup BARU (belum dipegang user ini) yang kuotanya sudah penuh.
	// $termasuk_lama TRUE (mengaktifkan kembali akun): grup yang sudah dipegang ikut dicek.
	private function cek_kuota_grup($groups, $user_id = 0, $termasuk_lama = FALSE)
	{
		$lama = $termasuk_lama ? array() : $this->grup_user($user_id);
		foreach ((array) $groups as $g) {
			$g = (int) $g;
			if (!isset(KUOTA_GRUP_KHUSUS[$g]) || in_array($g, $lama, TRUE)) {
				continue;
			}
			$pemegang = $this->pemegang_aktif_grup($g, $user_id);
			if (count($pemegang) >= KUOTA_GRUP_KHUSUS[$g]) {
				$nama = $this->db->select('name')->where('id', $g)->get('groups')->row('name');
				return 'Role ' . $nama . ' sudah dipegang ' . count($pemegang) . ' akun aktif (maksimal ' . KUOTA_GRUP_KHUSUS[$g] . '): '
					. implode(', ', array_map(function ($u) { return $u->username; }, $pemegang))
					. '. Nonaktifkan atau ubah role akun tersebut terlebih dahulu.';
			}
		}
		return '';
	}

	// Callback validasi field groups[] (diawali "_" agar tidak bisa dibuka lewat URL)
	private $id_user_kuota = 0;
	public function _kuota_grup($grup)
	{
		$pesan = $this->cek_kuota_grup(array($grup), $this->id_user_kuota);
		if ($pesan !== '') {
			$this->form_validation->set_message('_kuota_grup', $pesan);
			return FALSE;
		}
		return TRUE;
	}
	// Filter role di daftar user (khusus super admin): ?grup=<id grup>
	private function filter_grup()
	{
		return $this->is_super_admin() ? max(0, (int) $this->input->get('grup')) : 0;
	}

	private function data_filter_grup($grup)
	{
		$this->data['grup_dipilih'] = $grup;
		$this->data['daftar_grup'] = $this->is_super_admin() ? $this->m_user->rekap_grup() : array();
	}

	//View All Data
	public function index()
	{
		if(($this->ion_auth->user()->row()->id==1) ||($this->ion_auth->get_users_groups()->row()->id == 9) || ($this->ion_auth->get_users_groups()->row()->id == 100)){
		
			$config = array ();
			$grup = $this->filter_grup();
			$config ["base_url"] = base_url () . "setting_root/user/index";
			$config ['reuse_query_string'] = TRUE; // filter role (?grup=) ikut di link halaman
			if (($this->ion_auth->user()->row()->id==1) ||($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->id=="") 
				||  $this->ion_auth->get_users_groups()->row()->id == 100){ 
				$config ["total_rows"] = $this->m_user->record_count($grup);
			} else {
				$config ["total_rows"] = $this->m_user->record_count2($this->ion_auth->user()->row()->skpd_id);
			}
			$config ["per_page"] = 25;
			$config ["uri_segment"] = 4;
			$choice = $config ["total_rows"] / $config ["per_page"];
			$config ["num_links"] = 5;
			
			$config ['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
			$config ['full_tag_close'] = '</ul>';
			$config ['first_link'] = 'First';
			$config ['last_link'] = 'Last';
			$config ['first_tag_open'] = '<li>';
			$config ['first_tag_close'] = '</li>';
			$config ['prev_link'] = 'Previous';
			$config ['prev_tag_open'] = '<li class="prev">';
			$config ['prev_tag_close'] = '</li>';
			$config ['next_link'] = 'Next';
			$config ['next_tag_open'] = '<li>';
			$config ['next_tag_close'] = '</li>';
			$config ['last_tag_open'] = '<li>';
			$config ['last_tag_close'] = '</li>';
			$config ['cur_tag_open'] = '<li class="active"><a href="#">';
			$config ['cur_tag_close'] = '</a></li>';
			$config ['num_tag_open'] = '<li>';
			$config ['num_tag_close'] = '</li>';
			
			if ($this->uri->segment ( 4 ) == "") {
				$this->data ['number'] = 0;
			} else {
				$this->data ['number'] = $this->uri->segment ( 4 );
			}
			
			$this->pagination->initialize ( $config );
			$page = ($this->uri->segment ( 4 )) ? $this->uri->segment ( 4 ) : 0;
			
			$this->data['links'] = $this->pagination->create_links ();
			if (($this->ion_auth->user()->row()->id==1) ||($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->id=="") 
				||  $this->ion_auth->get_users_groups()->row()->id == 100){ 
				$this->data['user'] = $this->m_user->data($config ["per_page"], $page, $grup);
			} else {
				$this->data['user'] = $this->m_user->data2($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
			}
			$this->data['boleh_reset_password'] = $this->is_super_admin();
			$this->data['token_reset_password'] = $this->is_super_admin() ? $this->token_reset_password() : '';
			$this->data_filter_grup($grup);
			$this->render('setting_root/user/content');
		} else {
			redirect('beranda');
			
		}
	}
	
	//View Data Search
	public function search()
	{
		if(($this->ion_auth->user()->row()->id==1) ||($this->ion_auth->get_users_groups()->row()->id == 9) || ($this->ion_auth->get_users_groups()->row()->id == 100)){
			
			if($this->input->post('submit')){
				$column = "username";
				$query = $this->input->post('data');
				
				$option = array(
					'user_column'=>$column,
					'user_data'=>$query
					);
				$this->session->set_userdata($option);
			}else{
				$query = $this->uri->segment ( 4 );
				$column = kolom_aman($this->uri->segment ( 5 ));
			}
			
			$config = array ();
			$grup = $this->filter_grup();
			$config ["base_url"] = base_url () . "setting_root/user/search/".$query."/".$column;
			$config ['reuse_query_string'] = TRUE; // filter role (?grup=) ikut di link halaman
			if (($this->ion_auth->user()->row()->id==1) ||($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->id=="") 
				||  $this->ion_auth->get_users_groups()->row()->id == 100){ 
				$config ["total_rows"] = $this->m_user->record_count_search($column,$query,$grup);
			} else {
				$config ["total_rows"] = $this->m_user->record_count_search2($column,$query,$this->ion_auth->user()->row()->skpd_id);
			}
			$config ["per_page"] = 25;
			$config ["uri_segment"] = 6;
			$choice = $config ["total_rows"] / $config ["per_page"];
			$config ["num_links"] = 5;
			
			$config ['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
			$config ['full_tag_close'] = '</ul>';
			$config ['first_link'] = 'First';
			$config ['last_link'] = 'Last';
			$config ['first_tag_open'] = '<li>';
			$config ['first_tag_close'] = '</li>';
			$config ['prev_link'] = 'Previous';
			$config ['prev_tag_open'] = '<li class="prev">';
			$config ['prev_tag_close'] = '</li>';
			$config ['next_link'] = 'Next';
			$config ['next_tag_open'] = '<li>';
			$config ['next_tag_close'] = '</li>';
			$config ['last_tag_open'] = '<li>';
			$config ['last_tag_close'] = '</li>';
			$config ['cur_tag_open'] = '<li class="active"><a href="#">';
			$config ['cur_tag_close'] = '</a></li>';
			$config ['num_tag_open'] = '<li>';
			$config ['num_tag_close'] = '</li>';
			
			if ($this->uri->segment ( 6 ) == "") {
				$data ['number'] = 0;
			} else {
				$data ['number'] = $this->uri->segment ( 6 );
			}
			
			$this->pagination->initialize ( $config );
			$page = ($this->uri->segment ( 6 )) ? $this->uri->segment ( 6 ) : 0;
			
			$this->data['links'] = $this->pagination->create_links ();
			if (($this->ion_auth->user()->row()->id==1) ||($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->id=="") 
				||  $this->ion_auth->get_users_groups()->row()->id == 100){ 
				$this->data['user'] = $this->m_user->data_search($column,$query,$config ["per_page"], $page, $grup);
			} else {
				$this->data['user'] = $this->m_user->data_search2($column,$query,$config ["per_page"], $page,$this->ion_auth->user()->row()->skpd_id);
			}
			$this->data['boleh_reset_password'] = $this->is_super_admin();
			$this->data['token_reset_password'] = $this->is_super_admin() ? $this->token_reset_password() : '';
			$this->data_filter_grup($grup);
			$this->render('setting_root/user/content');
		} else {
			redirect('beranda');
		}
	}
	//View Create Data
	public function create_view()
	{
		if(($this->ion_auth->user()->row()->id==1) ||($this->ion_auth->get_users_groups()->row()->id == 9) || ($this->ion_auth->get_users_groups()->row()->id == 100)){
			$this->data['groups'] = $this->grup_boleh_dipilih();
			if (($this->ion_auth->user()->row()->id==1) ||($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->id=="") 
				||  $this->ion_auth->get_users_groups()->row()->id == 100){ 
				$this->data['skpd']= $this->m_pegawai->skpd();
			} else {
				$this->data['skpd']= $this->m_pegawai->skpd2($this->ion_auth->user()->row()->skpd_id);
			}
			$this->data['subbagian']= $this->m_pegawai->subbagian();
			$this->data['bagian']= $this->m_pegawai->bagian();
			$this->data['asisten']= $this->m_pegawai->asisten();
			$this->render('setting_root/user/insert');
		} else {
			redirect('beranda');
		}
	}
	
	//Create Data
	public function create()
	{
		$this->form_validation->set_rules('first_name','First name','trim');
		$this->form_validation->set_rules('last_name','Last name','trim');
		$this->form_validation->set_rules('phone','Phone','trim');
		$this->form_validation->set_rules('username','Username','trim|required|is_unique[users.username]');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password','Password','required|min_length[6]');
		$this->form_validation->set_rules('password_confirm','Password confirmation','required|matches[password]');
		$this->id_user_kuota = 0;
		$this->form_validation->set_rules('groups[]','Groups','required|callback__kuota_grup');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->data['groups'] = $this->grup_boleh_dipilih();
			if (($this->ion_auth->user()->row()->id==1) ||($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->id=="") 
				||  $this->ion_auth->get_users_groups()->row()->id == 100){ 
				$this->data['skpd']= $this->m_pegawai->skpd();
			} else {
				$this->data['skpd']= $this->m_pegawai->skpd2($this->ion_auth->user()->row()->skpd_id);
			}
			$this->render('setting_root/user/insert');
		} 
		else 
		{	
			
			$username = $this->input->post('username');
			$email = $this->input->post('email');
			$password = $this->input->post('password');
			$group_ids = $this->saring_grup($this->input->post('groups'));
			if (empty($group_ids)) {
				$this->session->set_flashdata('message', 'Grup user tidak valid.');
				redirect('setting_root/user/create_view', 'refresh');
			}
			// admin OPD hanya boleh membuat user untuk SKPD-nya sendiri
			$skpd_id = $this->is_super_admin() ? (int) $this->skpd_dari_post() : $this->skpd_id_login(); // tidak dipilih / tidak valid = 0
			$additional_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'company'    => 'PEMERINTAH KOTA KENDARI',
				'phone'      => $this->input->post('phone'),
				'skpd_id'      => $skpd_id
				);
			$this->ion_auth->register($username, $password, $email, $additional_data, $group_ids);
			
			$last = $this->m_user->getLast();
			foreach ($last as $l) {
				$last_id = $l->id;
			}
			
			if($this->input->post('subbagian')){
				$data['user_id'] = $last_id;
				$data['subbagian_id'] = $this->input->post('subbagian');
				$this->m_user->create1($data);
			} else if($this->input->post('bagian')){
				$data2['user_id'] = $last_id;
				$data2['bagian_id'] = $this->input->post('bagian');
				$this->m_user->create2($data2);
			} else if($this->input->post('asisten')){
				$data3['user_id'] = $last_id;
				$data3['asisten_id'] = $this->input->post('asisten');
				$this->m_user->create3($data3);
			} 			
			
			
			$log['kode_log_action'] = "53";
			$log['action'] = "INSERT";
			$log['kode_log_action_table'] = "23";
			$log['action_table'] = "TABLE USER";
			$this->m_log->create($log);
			
			$this->session->set_flashdata('notif','Data User Di Simpan !');
			redirect('setting_root/user','refresh');
			
		}
	}
	
	//View Update Data
	public function update_view()
	{
		if(($this->ion_auth->user()->row()->id==1) ||($this->ion_auth->get_users_groups()->row()->id == 9) || ($this->ion_auth->get_users_groups()->row()->id == 100)){
			$this->wajib_boleh_kelola_user($this->uri->segment(4));

			$this->data['entry'] =  $this->m_user->get($this->uri->segment(4));
			if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
				redirect('setting_root/user');
			} else {
				if($user = $this->ion_auth->user((int) $this->uri->segment(4))->row())
				{
					$this->data['user'] = $user;
				}
				else
				{
					$this->session->set_flashdata('message', 'The user doesn\'t exist.');
					redirect('setting_root/user', 'refresh');
				}
				$this->data['usergroups'] = array();
				if($usergroups = $this->ion_auth->get_users_groups($user->id)->result())
				{
					foreach($usergroups as $group)
					{
						$this->data['usergroups'][] = $group->id;
					}
				}
				
				$this->data['groups'] = $this->grup_boleh_dipilih((int) $this->uri->segment(4));
				if (($this->ion_auth->user()->row()->id==1) ||($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->id=="") 
				||  $this->ion_auth->get_users_groups()->row()->id == 100){ 
					$this->data['skpd']= $this->m_pegawai->skpd();
				} else {
					$this->data['skpd']= $this->m_pegawai->skpd2($this->ion_auth->user()->row()->skpd_id);
				}
				$this->data['subbagian']= $this->m_pegawai->subbagian();
				$this->data['bagian']= $this->m_pegawai->bagian();
				$this->data['asisten']= $this->m_pegawai->asisten();
				$this->render('setting_root/user/update');
			}
		} else {
			redirect('beranda');
		}
	}
//View Update Data
	public function update_view2()
	{
		$this->wajib_diri_sendiri_atau_berhak($this->uri->segment(4));
		$this->data['entry'] =  $this->m_user->get($this->uri->segment(4));
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('setting_root/user');
		} else {
			if($user = $this->ion_auth->user((int) $this->uri->segment(4))->row())
			{
				$this->data['user'] = $user;
			}
			else
			{
				$this->session->set_flashdata('message', 'The user doesn\'t exist.');
				redirect('setting_root/user', 'refresh');
			}
			$this->data['usergroups'] = array();
			if($usergroups = $this->ion_auth->get_users_groups($user->id)->result())
			{
				foreach($usergroups as $group)
				{
					$this->data['usergroups'][] = $group->id;
				}
			}
			
			$this->data['groups'] = $this->grup_boleh_dipilih((int) $this->uri->segment(4));
			if (($this->ion_auth->user()->row()->id==1) ||($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->id=="") 
				||  $this->ion_auth->get_users_groups()->row()->id == 100){ 
				$this->data['skpd']= $this->m_pegawai->skpd();
			} else {
				$this->data['skpd']= $this->m_pegawai->skpd2($this->ion_auth->user()->row()->skpd_id);
			}
			$this->data['subbagian']= $this->m_pegawai->subbagian();
			$this->data['bagian']= $this->m_pegawai->bagian();
			$this->data['asisten']= $this->m_pegawai->asisten();
			$this->render('setting_root/user/update2');
		}
	}
	//Update Data
	public function update($user_id = NULL)
	{
		$user_id = $this->input->post('user_id') ? $this->input->post('user_id') : $user_id;
		if($this->data['current_user']->id == $user_id)
		{
			$this->session->set_flashdata('message', 'Use the profile page to change your own credentials.');
			redirect('setting_root/user', 'refresh');
		}
		$this->wajib_boleh_kelola_user($user_id);
		
		$this->form_validation->set_rules('first_name','First name','trim');
		$this->form_validation->set_rules('last_name','Last name','trim');
		$this->form_validation->set_rules('phone','Phone','trim');
		$this->form_validation->set_rules('username','Username','trim|required');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email');
		// password opsional: kosongkan bila tidak ingin mengganti (sama seperti halaman profil)
		if ($this->input->post('password') != '' || $this->input->post('password_confirm') != '') {
			$this->form_validation->set_rules('password','Password','required|min_length[6]');
			$this->form_validation->set_rules('password_confirm','Ulangi Password','required|matches[password]');
		}
		$this->id_user_kuota = (int) $user_id;
		$this->form_validation->set_rules('groups[]','Groups','required|callback__kuota_grup');
		if($this->form_validation->run() === FALSE)
		{
			$this->data['entry'] =  $this->m_user->get($this->input->post('user_id'));
			if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
				redirect('setting_root/user');
			} else {
				if($user = $this->ion_auth->user((int) $this->input->post('user_id'))->row())
				{
					$this->data['user'] = $user;
				}
				else
				{
					$this->session->set_flashdata('message', 'The user doesn\'t exist.');
					redirect('setting_root/user', 'refresh');
				}
				$this->data['groups'] = $this->grup_boleh_dipilih((int) $user_id); // tersaring + info kuota
				$this->data['usergroups'] = array();
				if($usergroups = $this->ion_auth->get_users_groups($user->id)->result())
				{
					foreach($usergroups as $group)
					{
						$this->data['usergroups'][] = $group->id;
					}
				}
				if (($this->ion_auth->user()->row()->id==1) ||($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->id=="") 
				||  $this->ion_auth->get_users_groups()->row()->id == 100){ 
					$this->data['skpd']= $this->m_pegawai->skpd();
				} else {
					$this->data['skpd']= $this->m_pegawai->skpd2($this->ion_auth->user()->row()->skpd_id);
				}
				$this->data['subbagian']= $this->m_pegawai->subbagian();
				$this->data['bagian']= $this->m_pegawai->bagian();
				$this->data['asisten']= $this->m_pegawai->asisten();
				$this->render('setting_root/user/update');
			}
		}
		else
		{
			$user_id = $this->input->post('user_id');
			$new_data = array(
				'username' => $this->input->post('username'),
				'email' => $this->input->post('email'),
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),                
				'company'    => 'PEMERINTAH KOTA KENDARI',
				'phone'      => $this->input->post('phone'),
				'skpd_id'      => $this->is_super_admin() ? $this->skpd_dari_post() : $this->skpd_id_login()
				);
			if ($new_data['skpd_id'] === NULL) unset($new_data['skpd_id']); // SKPD tidak dipilih: tetap
			if(strlen((string) $this->input->post('password'))>=6) $new_data['password'] = $this->input->post('password');
			$this->ion_auth->update($user_id, $new_data);
            //Update the groups user belongs to
			$groups = $this->saring_grup($this->input->post('groups'), (int) $user_id);
			if (isset($groups) && !empty($groups))
			{
				$this->ion_auth->remove_from_group('', $user_id);
				foreach ($groups as $group)
				{
					$this->ion_auth->add_to_group($group, $user_id);
				}
			}
			// relasi lama dihapus per user_id (dulu memakai uri segment 4 yang selalu kosong di URL POST,
			// sehingga relasi lama menumpuk dan perubahan sub bagian / bagian tidak berlaku)
			if($this->input->post('subbagian')){
				$this->m_user->delete1((int) $user_id);
				$this->m_user->delete2((int) $user_id);
				$this->m_user->delete3((int) $user_id);
				$data['user_id'] = $this->input->post('user_id');
				$data['subbagian_id'] = $this->input->post('subbagian');
				$this->m_user->create1($data);
			} else if($this->input->post('bagian')){
				$this->m_user->delete1((int) $user_id);
				$this->m_user->delete2((int) $user_id);
				$this->m_user->delete3((int) $user_id);
				$data2['user_id'] = $this->input->post('user_id');
				$data2['bagian_id'] = $this->input->post('bagian');
				$this->m_user->create2($data2);
			} else if($this->input->post('asisten')){
				$this->m_user->delete1((int) $user_id);
				$this->m_user->delete2((int) $user_id);
				$this->m_user->delete3((int) $user_id);
				$data3['user_id'] = $this->input->post('user_id');
				$data3['asisten_id'] = $this->input->post('asisten');
				$this->m_user->create3($data3);
			} 			
			
			$this->session->set_flashdata('notif','Data User Di Ubah !');
			redirect('setting_root/user','refresh');
		}
	}
	
	//Update Data
	public function update2($user_id = NULL)
	{
		$user_id = $this->input->post('user_id') ? $this->input->post('user_id') : $user_id;
		$this->wajib_diri_sendiri_atau_berhak($user_id);
		$boleh_ubah_grup = $this->is_super_admin() && (int) $user_id !== (int) $this->session->userdata('user_id');
		
		$this->form_validation->set_rules('first_name','First name','trim');
		$this->form_validation->set_rules('last_name','Last name','trim');
		$this->form_validation->set_rules('phone','Phone','trim');
		$this->form_validation->set_rules('username','Username','trim|required');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email');
		// Password opsional di halaman profil: kosongkan jika tidak ingin mengganti
		if ($this->input->post('password') != '' || $this->input->post('password_confirm') != '') {
			$this->form_validation->set_rules('password','Password','min_length[6]');
			$this->form_validation->set_rules('password_confirm','Ulangi Password','required|matches[password]');
		}
		// grup hanya divalidasi bila memang boleh diubah (super admin untuk user lain)
		if ($boleh_ubah_grup) $this->form_validation->set_rules('groups[]','Groups','required');
		if($this->form_validation->run() === FALSE)
		{
			$this->data['entry'] =  $this->m_user->get($this->input->post('user_id'));
			if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
				redirect('setting_root/user');
			} else {
				if($user = $this->ion_auth->user((int) $this->input->post('user_id'))->row())
				{
					$this->data['user'] = $user;
				}
				else
				{
					$this->session->set_flashdata('message', 'The user doesn\'t exist.');
					redirect('setting_root/user', 'refresh');
				}
				if (($this->ion_auth->user()->row()->id==1) ||($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->id=="") 
				||  $this->ion_auth->get_users_groups()->row()->id == 100){ 
					$this->data['skpd']= $this->m_pegawai->skpd();
				} else {
					$this->data['skpd']= $this->m_pegawai->skpd2($this->ion_auth->user()->row()->skpd_id);
				}
				$this->data['groups'] = $this->grup_boleh_dipilih((int) $user_id);
				$this->data['usergroups'] = array();
				if($usergroups = $this->ion_auth->get_users_groups($user->id)->result())
				{
					foreach($usergroups as $group)
					{
						$this->data['usergroups'][] = $group->id;
					}
				}
				$this->render('setting_root/user/update2');
			}
		}
		else
		{
			$user_id = $this->input->post('user_id');
			$new_data = array(
				'username' => $this->input->post('username'),
				'email' => $this->input->post('email'),
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),                
				'company'    => 'PEMERINTAH KOTA KENDARI',
				'phone'      => $this->input->post('phone'),
				'skpd_id'      => $this->skpd_dari_post()
				);
			// SKPD hanya bisa diubah super admin (termasuk untuk akunnya sendiri);
			// user lain tidak boleh memindahkan dirinya ke SKPD lain
			if (!$this->is_super_admin() || $new_data['skpd_id'] === NULL) unset($new_data['skpd_id']);
			if(strlen((string) $this->input->post('password'))>=6) $new_data['password'] = $this->input->post('password');
			$this->ion_auth->update($user_id, $new_data);
            //Update the groups user belongs to
			$groups = $boleh_ubah_grup ? $this->saring_grup($this->input->post('groups'), (int) $user_id) : array();
			$pesan_kuota = $this->cek_kuota_grup($groups, (int) $user_id);
			if ($pesan_kuota !== '') {
				$this->session->set_flashdata('notif', $pesan_kuota);
				redirect('setting_root/user/update_view2/' . (int) $user_id);
			}
			if (isset($groups) && !empty($groups))
			{
				$this->ion_auth->remove_from_group('', $user_id);
				foreach ($groups as $group)
				{
					$this->ion_auth->add_to_group($group, $user_id);
				}
			}
			// relasi lama dihapus per user_id (dulu memakai uri segment 4 yang selalu kosong di URL POST,
			// sehingga relasi lama menumpuk dan perubahan sub bagian / bagian tidak berlaku)
			if($this->input->post('subbagian')){
				$this->m_user->delete1((int) $user_id);
				$this->m_user->delete2((int) $user_id);
				$this->m_user->delete3((int) $user_id);
				$data['user_id'] = $this->input->post('user_id');
				$data['subbagian_id'] = $this->input->post('subbagian');
				$this->m_user->create1($data);
			} else if($this->input->post('bagian')){
				$this->m_user->delete1((int) $user_id);
				$this->m_user->delete2((int) $user_id);
				$this->m_user->delete3((int) $user_id);
				$data2['user_id'] = $this->input->post('user_id');
				$data2['bagian_id'] = $this->input->post('bagian');
				$this->m_user->create2($data2);
			} else if($this->input->post('asisten')){
				$this->m_user->delete1((int) $user_id);
				$this->m_user->delete2((int) $user_id);
				$this->m_user->delete3((int) $user_id);
				$data3['user_id'] = $this->input->post('user_id');
				$data3['asisten_id'] = $this->input->post('asisten');
				$this->m_user->create3($data3);
			} 			
			
			$this->session->set_flashdata('notif','Data Di Ubah !');
			redirect('setting_root/user/update_view2/'.$this->input->post('user_id'));
		}
	}
	
	//Update Data
	public function update3()
	{	
		$this->wajib_diri_sendiri_atau_berhak($this->input->post('id'));
		$kembali = 'setting_root/user/update_view2/' . (int) $this->input->post('id');
		if(empty($_FILES['userfile']['name'])){
			$this->session->set_flashdata('error', 'Pilih file foto terlebih dahulu.');
			redirect($kembali);
		} else {
			
			$filename = 'user_' . (int) $this->input->post('id') . '_' . bin2hex(random_bytes(4));
			$config['upload_path'] = './upload/profil/';
			$config['allowed_types'] = "gif|jpg|jpeg|png";
			$config['overwrite']=FALSE;
			$config['max_size']=MAKS_UPLOAD_GAMBAR_KB;
			$config['max_width']="10000";
			$config['max_height']="10000";
			$config['file_name'] = $filename; // sudah acak
			$this->upload->initialize($config);
			if(!$this->upload->do_upload()){
				$this->session->set_flashdata('error', 'Foto gagal diunggah: ' . $this->upload->display_errors('', '') . ' (format JPG, PNG, GIF, maks. 2 MB)');
				redirect($kembali);
			}else {
				
				$image = $this->m_user->link_gambar($this->input->post('id'));
				if ($image->num_rows() > 0)
				{
					$row = $image->row();			
					$file_gambar = basename((string) $row->photo);
					$path_file = './upload/profil/';
					if ($file_gambar !== '' && is_file($path_file.$file_gambar)) hapus_file_unggah($path_file, $file_gambar);
				}					
				
				$dat = $this->upload->data();
				
				$data['id'] = $this->input->post('id');
				$data['photo'] = $dat['file_name'];
				
				$this->m_user->update($data);		
				
				
				$log['kode_log_action'] = "54";
				$log['action'] = "UPDATE Foto User".$this->input->post('id');
				$log['kode_log_action_table'] = "23";
				$log['action_table'] = "TABLE USER";
				$this->m_log->create($log);
				
				$this->session->set_flashdata('notif','Foto Di Simpan !');
				redirect('setting_root/user/update_view2/'.$this->input->post('id'));
			}
		}
	}
	
	const PASSWORD_RESET = 'pass1234';

	// Token per sesi untuk form aksi di halaman user: reset password & aktif/nonaktif (CSRF global aplikasi nonaktif)
	private function token_reset_password()
	{
		$token = (string) $this->session->userdata('token_reset_password');
		if ($token === '') {
			$token = bin2hex(random_bytes(16));
			$this->session->set_userdata('token_reset_password', $token);
		}
		return $token;
	}

	// Kembali ke halaman daftar user asal (halaman, filter role & pencarian tetap)
	private function halaman_kembali_user()
	{
		$referer = (string) $this->input->server('HTTP_REFERER');
		return strpos($referer, base_url('setting_root/user')) === 0 ? substr($referer, strlen(base_url())) : 'setting_root/user';
	}

	// Aksi POST dari halaman user: wajib POST dengan token yang sah
	private function wajib_post_bertoken($kembali)
	{
		if ($this->input->method() !== 'post') {
			redirect($kembali);
		}
		$token = (string) $this->input->post('token');
		if ($token === '' || !hash_equals($this->token_reset_password(), $token)) {
			$this->session->set_flashdata('notif', 'Permintaan tidak sah, silakan ulangi dari halaman user.');
			redirect($kembali);
		}
	}

	// Aktifkan / nonaktifkan user — khusus super admin, lewat POST dari halaman user.
	// Akun nonaktif tidak bisa login, dan bila sedang login langsung dikeluarkan (MY_Controller::wajib_login).
	public function ubah_status()
	{
		$this->wajib_super_admin();
		$kembali = $this->halaman_kembali_user();
		$this->wajib_post_bertoken($kembali);

		$target_id = (int) $this->input->post('user_id');
		if ($target_id <= 0 || $target_id === 1 || $target_id === (int) $this->session->userdata('user_id')) {
			$this->session->set_flashdata('notif', 'Status akun ini tidak dapat diubah.');
			redirect($kembali);
		}
		$target = $this->db->select('id, username, active')->where('id', $target_id)->get('users')->row();
		if (!$target) {
			$this->session->set_flashdata('notif', 'User tidak ditemukan.');
			redirect($kembali);
		}

		$aktifkan = (int) $target->active !== 1;
		if ($aktifkan) {
			// role tingkat kota (Walikota, Sekda, ...) tidak boleh melebihi kuota akun aktif
			$pesan = $this->cek_kuota_grup($this->grup_user($target_id), $target_id, TRUE);
			if ($pesan !== '') {
				$this->session->set_flashdata('notif', 'User ' . html_escape($target->username) . ' tidak dapat diaktifkan. ' . html_escape($pesan));
				redirect($kembali);
			}
			$data = array('active' => 1, 'activation_code' => NULL);
		} else {
			// remember_code dikosongkan agar cookie "ingat saya" tidak bisa dipakai lagi
			$data = array('active' => 0, 'activation_code' => NULL, 'remember_code' => NULL);
		}
		$this->db->where('id', $target_id)->update('users', $data);

		$log['kode_log_action'] = "54";
		$log['action'] = ($aktifkan ? "AKTIFKAN" : "NONAKTIFKAN") . " id_user = " . $target_id;
		$log['kode_log_action_table'] = "23";
		$log['action_table'] = "TABLE USER";
		$this->m_log->create($log);

		$this->session->set_flashdata('notif', 'User ' . html_escape($target->username) . ($aktifkan ? ' telah diaktifkan.' : ' telah dinonaktifkan dan tidak bisa login.'));
		redirect($kembali);
	}

	// Reset password user ke "pass1234" — khusus super admin, lewat POST dari halaman user
	public function reset_password()
	{
		$this->wajib_super_admin();
		$kembali = $this->halaman_kembali_user();
		$this->wajib_post_bertoken($kembali);

		$target_id = (int) $this->input->post('user_id');
		// akun utama & akun sendiri tidak di-reset ke password default (ubah lewat halaman profil)
		if ($target_id <= 0 || $target_id === 1 || $target_id === (int) $this->session->userdata('user_id')) {
			$this->session->set_flashdata('notif', 'Password akun ini tidak dapat di-reset dari sini. Ubah lewat halaman profil.');
			redirect($kembali);
		}
		$target = $this->db->select('id, username')->where('id', $target_id)->get('users')->row();
		if (!$target) {
			$this->session->set_flashdata('notif', 'User tidak ditemukan.');
			redirect($kembali);
		}
		// password super admin tidak di-reset ke password default; pemiliknya mengganti lewat halaman profil
		if (in_array(100, $this->grup_user($target_id), TRUE)) {
			$this->session->set_flashdata('notif', 'Password akun super admin tidak dapat di-reset. Pemilik akun menggantinya sendiri lewat halaman profil.');
			redirect($kembali);
		}

		// reset_password() meng-hash ulang dan mengosongkan remember_code / kode lupa password
		if (!$this->ion_auth->reset_password($target->username, self::PASSWORD_RESET)) {
			$this->session->set_flashdata('notif', 'Gagal me-reset password user ' . html_escape($target->username) . '.');
			redirect($kembali);
		}
		// sekaligus buka kunci / blokir login user tersebut
		$this->load->model('setting/m_login_lockout');
		$this->m_login_lockout->reset($target->username);

		$log['kode_log_action'] = "54";
		$log['action'] = "RESET PASSWORD id_user = " . $target_id;
		$log['kode_log_action_table'] = "23";
		$log['action_table'] = "TABLE USER";
		$this->m_log->create($log);

		$this->session->set_flashdata('notif', 'Password user ' . html_escape($target->username) . ' telah di-reset menjadi ' . self::PASSWORD_RESET . '.');
		redirect($kembali);
	}

	//Delete Data
	public function delete()
	{
		$target_id = (int) $this->uri->segment(4);
		if ($target_id === 1 || $target_id === (int) $this->session->userdata('user_id')) {
			redirect('setting_root/user');
		}
		$this->wajib_boleh_kelola_user($target_id);
		$this->m_user->delete($target_id);
		
		$log['kode_log_action'] = "56";
		$log['action'] = "DELETE id_user = ".$target_id;
		$log['kode_log_action_table'] = "23";
		$log['action_table'] = "TABLE USER";
		$this->m_log->create($log);	
		
		$this->session->set_flashdata('notif','Data User Di Hapus !');
		redirect('setting_root/user');	
	}
}