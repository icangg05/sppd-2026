<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Menyajikan gambar tanda tangan untuk pratinjau di halaman admin.
// Folder upload/tanda_tangan/ ditutup dari akses web langsung (.htaccess) karena gambar tanda tangan
// pejabat sensitif (bisa dipakai memalsukan dokumen). PDF tetap membacanya langsung dari disk.
class Berkas_ttd extends public_Controller {
	const FOLDER = './upload/tanda_tangan/';

	function __construct()
	{
		parent::__construct();
		$this->wajib_admin();
	}

	public function lihat($nama = '')
	{
		$nama = basename(rawurldecode((string) $nama));
		$path = self::FOLDER . $nama;
		if ($nama === '' || !preg_match('/\.(png|jpe?g|gif)$/i', $nama) || !is_file($path) || !$this->boleh_lihat($nama)) {
			show_404();
		}

		$jenis = array('png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'gif' => 'image/gif');
		$ext = strtolower(pathinfo($nama, PATHINFO_EXTENSION));
		$this->output
			->set_content_type($jenis[$ext])
			->set_header('Cache-Control: private, max-age=300')
			->set_header('X-Content-Type-Options: nosniff')
			->set_output(file_get_contents($path));
	}

	// Super admin: semua. Admin OPD: hanya tanda tangan pegawai / pejabat di SKPD-nya sendiri.
	private function boleh_lihat($nama)
	{
		if ($this->is_super_admin()) {
			return TRUE;
		}
		$skpd = $this->skpd_id_login();
		if ($skpd <= 0) {
			return FALSE;
		}
		$pegawai = $this->db->where(array('pegawai_tandatangan' => $nama, 'skpd_id' => $skpd))->count_all_results('table_pegawai');
		$pejabat = $this->db->where(array('tanda_tangan' => $nama, 'skpd_id' => $skpd))->count_all_results('table_tanda_tangan');
		return ($pegawai + $pejabat) > 0;
	}
}
