<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Utilitas extends public_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('setting/m_log');

		$this->wajib_super_admin();
	}

	// View Content Back Up
	public function index(){
		$this->render('setting_root/utilitas/backup');
	}

	// Back Up DB (hanya super admin, langsung diunduh tanpa disimpan di server)
	public function backupdb(){
		$this->load->dbutil();

		$aturan = array(
			'format'      => 'zip',
			'filename'    => 'db_sppd.sql'
			);

		$backup = $this->dbutil->backup($aturan);

		$log['kode_log_action'] = "57";
		$log['action'] = "BACKUP DATABASE";
		$log['kode_log_action_table'] = "0";
		$log['action_table'] = "DATABASE";
		$this->m_log->create($log);

		$this->load->helper('download');
		force_download('DB_SPPD ('. date("Y-m-d-H-i-s") .').zip', $backup);
	}

	// Fitur restore() dan update() (import SQL/CSV lewat web) DIHAPUS.
	// restore() menjalankan file_get_contents() atas input POST lalu mengeksekusi
	// isinya sebagai SQL (bisa dipakai menjalankan SQL apa saja, mis. lewat data://),
	// dan update() menyusun query dari CSV tanpa escaping (SQL injection).
	// Restore database lakukan langsung di server (mysql CLI / phpMyAdmin internal).
}
