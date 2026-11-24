<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Daftar username yang diblokir/dikunci karena percobaan login gagal.
// Hanya super admin yang boleh membuka blokir.
class Login_blokir extends public_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('setting/m_log');
		$this->load->model('setting/m_login_lockout');

		$this->wajib_super_admin();
	}

	public function index()
	{
		$this->data['daftar'] = $this->m_login_lockout->daftar();
		$this->render('setting_root/login_blokir/content');
	}

	public function buka()
	{
		if ($this->input->method() !== 'post') {
			redirect('setting_root/login_blokir');
		}
		$login = $this->m_login_lockout->kunci($this->input->post('login'));
		if ($login !== '') {
			$this->m_login_lockout->reset($login);

			$log['kode_log_action'] = "54";
			$log['action'] = "BUKA BLOKIR LOGIN " . substr($login, 0, 80);
			$log['kode_log_action_table'] = "23";
			$log['action_table'] = "TABLE USER";
			$this->m_log->create($log);

			$this->session->set_flashdata('notif', 'Blokir login untuk "' . html_escape($login) . '" sudah dibuka.');
		}
		redirect('setting_root/login_blokir');
	}
}
