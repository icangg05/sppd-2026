<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Cek keaslian dokumen PDF ber-TTE ke BSrE (acuan: VerifyDocument di sppd-update)
class Dokumen extends public_Controller {
	const MAKS_KB = MAKS_UPLOAD_DOKUMEN_KB; // 10 MB, dibatasi lagi oleh upload_max_filesize PHP

	function __construct()
	{
		parent::__construct();
		$this->wajib_admin();
		$this->load->library('tte_verifikasi');
		$this->load->model('setting/m_log');
	}

	public function index()
	{
		$this->data['hasil'] = NULL;
		$this->data['batas_ukuran'] = $this->batas_ukuran_teks();
		$this->render('setting_admin/dokumen/content');
	}

	public function cek()
	{
		if ($this->input->method() !== 'post') {
			redirect('setting_admin/dokumen');
		}
		$hasil = $this->periksa_unggahan();

		// Dari halaman (AJAX): kirim potongan hasil saja agar pratinjau di kanan tetap tampil
		if ($this->input->is_ajax_request()) {
			$this->load->view('setting_admin/dokumen/hasil', array('hasil' => $hasil));
			return;
		}

		$this->data['hasil'] = $hasil;
		$this->data['batas_ukuran'] = $this->batas_ukuran_teks();
		$this->render('setting_admin/dokumen/content');
	}

	private function periksa_unggahan()
	{
		$f = isset($_FILES['userfile']) ? $_FILES['userfile'] : NULL;
		if (!$f || $f['error'] === UPLOAD_ERR_NO_FILE || $f['name'] === '') {
			return $this->tte_verifikasi->gagal('Pilih berkas PDF yang akan diverifikasi.');
		}
		if (in_array($f['error'], array(UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE), TRUE)) {
			return $this->tte_verifikasi->gagal('Ukuran berkas melebihi batas ' . $this->batas_ukuran_teks() . '.');
		}

		// Simpan sementara di luar folder web; dihapus setelah dikirim ke BSrE
		$folder = rtrim(sys_get_temp_dir(), '/') . '/sppd_cek_dokumen/';
		if (!is_dir($folder)) {
			mkdir($folder, 0700, TRUE);
		}
		$this->upload->initialize(array(
			'upload_path'   => $folder,
			'allowed_types' => 'pdf',
			'max_size'      => self::MAKS_KB,
			'file_name'     => bin2hex(random_bytes(8)), // nama dari klien tidak dipakai di disk
			'overwrite'     => FALSE,
		));
		if (!$this->upload->do_upload('userfile')) {
			return $this->tte_verifikasi->gagal(trim(strip_tags($this->upload->display_errors('', ''))) ?: 'Berkas gagal diunggah.');
		}

		$up = $this->upload->data();
		try {
			$fh = fopen($up['full_path'], 'rb');
			$awal = $fh ? fread($fh, 5) : '';
			if ($fh) {
				fclose($fh);
			}
			if (strncmp($awal, '%PDF-', 5) !== 0) {
				return $this->tte_verifikasi->gagal('Berkas bukan dokumen PDF yang valid.');
			}
			$hasil = $this->tte_verifikasi->verifikasi($up['full_path'], $up['client_name']);
		} finally {
			if (is_file($up['full_path'])) {
				unlink($up['full_path']);
			}
		}

		$log['kode_log_action'] = "58";
		$log['action'] = "CEK DOKUMEN TTE: " . ($hasil['ok'] ? substr((string) $hasil['summary'], 0, 40) : 'GAGAL');
		$log['kode_log_action_table'] = "0";
		$log['action_table'] = "DOKUMEN";
		$this->m_log->create($log);

		return $hasil;
	}

	private function batas_ukuran_teks()
	{
		$ini = $this->ke_byte(ini_get('upload_max_filesize'));
		$batas = min(self::MAKS_KB * 1024, $ini > 0 ? $ini : PHP_INT_MAX);
		return ($batas >= 1048576 ? round($batas / 1048576, 1) . ' MB' : round($batas / 1024) . ' KB');
	}

	private function ke_byte($nilai)
	{
		$nilai = trim((string) $nilai);
		$angka = (float) $nilai;
		switch (strtolower(substr($nilai, -1))) {
			case 'g': $angka *= 1024;
			case 'm': $angka *= 1024;
			case 'k': $angka *= 1024;
		}
		return (int) $angka;
	}
}
