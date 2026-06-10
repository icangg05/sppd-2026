<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends admin_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('setting_root/m_user');
		$this->load->model('setting_root/m_log');
		$this->load->model('setting_admin/m_pegawai');

	}
	//View All Data
	public function index()
	{
		if(($this->ion_auth->user()->row()->id==1) ||($this->ion_auth->get_users_groups()->row()->id == 9) || ($this->ion_auth->get_users_groups()->row()->id == 100)){
		
			$config = array ();
			$config ["base_url"] = base_url () . "setting_root/user/index";
			if (($this->ion_auth->user()->row()->id==1) ||($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->id=="") 
				||  $this->ion_auth->get_users_groups()->row()->id == 100){ 
				$config ["total_rows"] = $this->m_user->record_count();
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
				$this->data['user'] = $this->m_user->data($config ["per_page"], $page);
			} else {
				$this->data['user'] = $this->m_user->data2($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
			}
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
				$column = $this->uri->segment ( 5 );
			}
			
			$config = array ();
			$config ["base_url"] = base_url () . "setting_root/user/search/".$query."/".$column;
			if (($this->ion_auth->user()->row()->id==1) ||($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->id=="") 
				||  $this->ion_auth->get_users_groups()->row()->id == 100){ 
				$config ["total_rows"] = $this->m_user->record_count_search($column,$query);
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
				$this->data['user'] = $this->m_user->data_search($column,$query,$config ["per_page"], $page);
			} else {
				$this->data['user'] = $this->m_user->data_search2($column,$query,$config ["per_page"], $page,$this->ion_auth->user()->row()->skpd_id);
			}
			$this->render('setting_root/user/content');
		} else {
			redirect('beranda');
		}
	}
	//View Create Data
	public function create_view()
	{
		if(($this->ion_auth->user()->row()->id==1) ||($this->ion_auth->get_users_groups()->row()->id == 9) || ($this->ion_auth->get_users_groups()->row()->id == 100)){
			$this->data['groups'] = $this->m_user->groups();
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
		$this->form_validation->set_rules('groups[]','Groups','required');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->data['groups'] = $this->m_user->groups();
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
			$group_ids = $this->input->post('groups');
			$skpd_id = $this->input->post('skpd_id');
			$additional_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'company'    => 'PEMERINTAH KOTA KENDARI',
				'phone'      => $this->input->post('phone'),
				'skpd_id'      => $this->input->post('skpd_id')
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
				
				$this->data['groups'] = $this->m_user->groups();
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
			
			$this->data['groups'] = $this->m_user->groups();
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
		
		$this->form_validation->set_rules('first_name','First name','trim');
		$this->form_validation->set_rules('last_name','Last name','trim');
		$this->form_validation->set_rules('phone','Phone','trim');
		$this->form_validation->set_rules('username','Username','trim|required');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email');
		$this->form_validation->set_rules('password','Password','required|min_length[6]');
		$this->form_validation->set_rules('password_confirm','Password confirmation','required|matches[password]');
		$this->form_validation->set_rules('groups[]','Groups','required');
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
				$this->data['groups'] = $this->ion_auth->groups()->result();
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
				'skpd_id'      => $this->input->post('skpd_id')
				);
			if(strlen($this->input->post('password'))>=6) $new_data['password'] = $this->input->post('password');
			$this->ion_auth->update($user_id, $new_data);
            //Update the groups user belongs to
			$groups = $this->input->post('groups');
			if (isset($groups) && !empty($groups))
			{
				$this->ion_auth->remove_from_group('', $user_id);
				foreach ($groups as $group)
				{
					$this->ion_auth->add_to_group($group, $user_id);
				}
			}
			if($this->input->post('subbagian')){
				$this->m_user->delete1($this->uri->segment(4));
				$this->m_user->delete2($this->uri->segment(4));
				$this->m_user->delete3($this->uri->segment(4));
				$data['user_id'] = $this->input->post('user_id');
				$data['subbagian_id'] = $this->input->post('subbagian');
				$this->m_user->create1($data);
			} else if($this->input->post('bagian')){
				$this->m_user->delete1($this->uri->segment(4));
				$this->m_user->delete2($this->uri->segment(4));
				$this->m_user->delete3($this->uri->segment(4));
				$data2['user_id'] = $this->input->post('user_id');
				$data2['bagian_id'] = $this->input->post('bagian');
				$this->m_user->create2($data2);
			} else if($this->input->post('asisten')){
				$this->m_user->delete1($this->uri->segment(4));
				$this->m_user->delete2($this->uri->segment(4));
				$this->m_user->delete3($this->uri->segment(4));
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
		
		$this->form_validation->set_rules('first_name','First name','trim');
		$this->form_validation->set_rules('last_name','Last name','trim');
		$this->form_validation->set_rules('phone','Phone','trim');
		$this->form_validation->set_rules('username','Username','trim|required');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email');
		$this->form_validation->set_rules('password','Password','required|min_length[6]');
		$this->form_validation->set_rules('password_confirm','Password confirmation','required|matches[password]');
		$this->form_validation->set_rules('groups[]','Groups','required');
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
				$this->data['groups'] = $this->ion_auth->groups()->result();
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
				'skpd_id'      => $this->input->post('skpd_id')
				);
			if(strlen($this->input->post('password'))>=6) $new_data['password'] = $this->input->post('password');
			$this->ion_auth->update($user_id, $new_data);
            //Update the groups user belongs to
			$groups = $this->input->post('groups');
			if (isset($groups) && !empty($groups))
			{
				$this->ion_auth->remove_from_group('', $user_id);
				foreach ($groups as $group)
				{
					$this->ion_auth->add_to_group($group, $user_id);
				}
			}
			if($this->input->post('subbagian')){
				$this->m_user->delete1($this->uri->segment(4));
				$this->m_user->delete2($this->uri->segment(4));
				$this->m_user->delete3($this->uri->segment(4));
				$data['user_id'] = $this->input->post('user_id');
				$data['subbagian_id'] = $this->input->post('subbagian');
				$this->m_user->create1($data);
			} else if($this->input->post('bagian')){
				$this->m_user->delete1($this->uri->segment(4));
				$this->m_user->delete2($this->uri->segment(4));
				$this->m_user->delete3($this->uri->segment(4));
				$data2['user_id'] = $this->input->post('user_id');
				$data2['bagian_id'] = $this->input->post('bagian');
				$this->m_user->create2($data2);
			} else if($this->input->post('asisten')){
				$this->m_user->delete1($this->uri->segment(4));
				$this->m_user->delete2($this->uri->segment(4));
				$this->m_user->delete3($this->uri->segment(4));
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
		if($_FILES['userfile']['name']==''){
			
		} else {
			
			$filename = $this->input->post('id');
			$config['upload_path'] = './upload/profil/';
			$config['allowed_types'] = "gif|jpg|jpeg|png";
			$config['overwrite']="true";
			$config['max_size']="20000000";
			$config['max_width']="10000";
			$config['max_height']="10000";
			$config['file_name'] = ''.$filename;
			$this->upload->initialize($config);
			if(!$this->upload->do_upload()){
				echo  $this->upload->display_errors();
			}else {
				
				$image = $this->m_user->link_gambar($this->input->post('id'));
				if ($image->num_rows() > 0)
				{
					$row = $image->row();			
					$file_gambar = $row->photo;
					$path_file = './upload/profil/';
					unlink($path_file.$file_gambar);
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
	
	//Delete Data
	public function delete() 
	{
		$this->m_user->delete($this->uri->segment(4));
		
		$log['kode_log_action'] = "56";
		$log['action'] = "DELETE id_user = ".$this->uri->segment(4);
		$log['kode_log_action_table'] = "23";
		$log['action_table'] = "TABLE USER";
		$this->m_log->create($log);	
		
		$this->session->set_flashdata('notif','Data User Di Hapus !');
		redirect('setting_root/user');	
	}
}