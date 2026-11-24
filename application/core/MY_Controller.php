<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	protected $data = array();
	function __construct()
	{
		parent::__construct();
		$this->data['page_title'] = 'Ping CMS';
		$this->data['before_head'] = '';
		$this->data['before_body'] = '';
	}

	// ---------------------------------------------------------------
	// Hak akses terpusat
	//   super admin : grup 100 (SUPER ADMIN) atau user id 1
	//   admin       : super admin atau grup 9 (ADMIN OPD)
	// ---------------------------------------------------------------
	protected function id_grup_login()
	{
		$row = $this->ion_auth->get_users_groups()->row();
		return $row ? (int) $row->id : 0;
	}

	protected function is_super_admin()
	{
		return $this->ion_auth->logged_in()
			&& ((int) $this->session->userdata('user_id') === 1 || $this->id_grup_login() === 100);
	}

	protected function is_admin()
	{
		return $this->is_super_admin() || ($this->ion_auth->logged_in() && $this->id_grup_login() === 9);
	}

	// skpd_id milik user login, diambil langsung dari tabel users
	// (user() melakukan LEFT JOIN table_skpd sehingga skpd_id bisa tertimpa NULL)
	protected function skpd_id_login()
	{
		$row = $this->db->select('skpd_id')->where('id', (int) $this->session->userdata('user_id'))->get('users')->row();
		return $row ? (int) $row->skpd_id : 0;
	}

	// Id terenkripsi dari query string (pola ?xxx_id=... di halaman setting)
	protected function id_dari_get($nama)
	{
		return (int) $this->encrypt->decode(base64_decode((string) $this->input->get($nama)), $this->session->userdata('encrypt_key'));
	}

	// Baris $tabel dengan $kolom_id = $id wajib milik SKPD user login (super admin bebas)
	protected function wajib_milik_skpd($tabel, $kolom_id, $id, $kembali)
	{
		if ($this->is_super_admin()) {
			return;
		}
		$row = $this->db->select('skpd_id')->where($kolom_id, (int) $id)->get($tabel)->row();
		if (!$row || (int) $row->skpd_id !== $this->skpd_id_login()) {
			$this->session->set_flashdata('notif', 'Anda hanya dapat mengelola data milik SKPD Anda sendiri.');
			redirect($kembali, 'refresh');
		}
	}

	// Akun ini punya grup tingkat kota / super admin (GRUP_KHUSUS_SUPER_ADMIN, config/constants.php)?
	protected function punya_grup_khusus($user_id)
	{
		return $this->db->where('user_id', (int) $user_id)
			->where_in('group_id', GRUP_KHUSUS_SUPER_ADMIN)
			->count_all_results('users_groups') > 0;
	}

	// Super admin: semua user. Admin OPD: hanya user di SKPD-nya sendiri, bukan user id 1,
	// dan bukan akun bergrup khusus (Asisten, Sekda, Pimpinan DPRD, Walikota, Inspektorat, Super Admin).
	// Dipakai manajemen user (ubah, hapus, reset) dan log aktivitas user.
	protected function boleh_kelola_user($target_id)
	{
		$target_id = (int) $target_id;
		if ($target_id <= 0) {
			return FALSE;
		}
		if ($this->is_super_admin()) {
			return TRUE;
		}
		if (!$this->is_admin() || $target_id === 1) {
			return FALSE;
		}
		$target = $this->db->select('skpd_id')->where('id', $target_id)->get('users')->row();
		if (!$target) {
			return FALSE;
		}
		$skpd_saya = $this->skpd_id_login();
		return !$this->punya_grup_khusus($target_id) && $skpd_saya > 0 && (int) $target->skpd_id === $skpd_saya;
	}

	protected function wajib_login()
	{
		if (!$this->ion_auth->logged_in()) {
			redirect('login', 'refresh');
		}
		$this->wajib_akun_aktif();
	}

	// Akun yang dinonaktifkan / dihapus saat masih login langsung dikeluarkan
	// (session lama tetap berisi identity). Dicek sekali per request.
	private $akun_aktif_dicek = FALSE;
	private function wajib_akun_aktif()
	{
		if ($this->akun_aktif_dicek) {
			return;
		}
		$row = $this->db->select('active')->where('id', (int) $this->session->userdata('user_id'))->get('users')->row();
		if (!$row || (int) $row->active !== 1) {
			$this->ion_auth->logout(); // session dibuat ulang, flashdata di bawah tetap terbawa
			$this->session->set_flashdata('notif', 'Akun Anda sudah dinonaktifkan. Hubungi administrator.');
			redirect('login', 'refresh');
		}
		$this->akun_aktif_dicek = TRUE;
	}

	protected function wajib_admin()
	{
		$this->wajib_login();
		if (!$this->is_admin()) {
			redirect('beranda', 'refresh');
		}
	}

	protected function wajib_super_admin()
	{
		$this->wajib_login();
		if (!$this->is_super_admin()) {
			redirect('beranda', 'refresh');
		}
	}

	protected function render($the_view = NULL, $template = 'master')
	{
		if($template == 'json' || $this->input->is_ajax_request())
		{
			header('Content-Type: application/json');
			echo json_encode($this->data);
		}
		elseif(is_null($template))
		{
			$this->load->view($the_view,$this->data);
		}
		else
		{
			$this->data['the_view_content'] = (is_null($the_view)) ? '' : $this->load->view($the_view, $this->data, TRUE);
			$this->load->view('templates/' . $template . '_view', $this->data);
		}
	}
}

class Admin_Controller extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->wajib_login(); // belum login / akun nonaktif -> halaman login
		$this->data['current_user'] = $this->ion_auth->user()->row();
		$this->data['current_user_menu'] = '';
		if($this->ion_auth->in_group('admin'))
		{
			$this->data['current_user_menu'] = $this->load->view('templates/_parts/user_menu_admin_view.php', NULL, TRUE);
		}

		$this->data['page_title'] = 'CI CMS - Dashboard';
	}
	protected function render($the_view = NULL, $template = 'admin_master')
	{
		parent::render($the_view, $template);
	}
}

class Public_Controller extends MY_Controller
{

	// Walau namanya "public", semua turunan kelas ini adalah halaman internal
	// (telaah, laporan, setting). Halaman yang memang publik memakai
	// Qr_Controller, CI_Controller (api) atau MY_Controller (login).
	function __construct()
	{
		parent::__construct();
		$this->wajib_login();
	}
	
	protected function render($the_view = NULL, $template = 'admin_master')
	{
		parent::render($the_view, $template);
	}
}


class Qr_Controller extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
	}
	
	
}