<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pengaturan penandatangan "Setuju Bayar / Mengetahui" KHUSUS Sekretariat Daerah (SETDA).
 * Nilai disimpan pada file upload/json/data.json dan dipakai dinamis oleh cetak
 * Kuitansi Rampung, Rincian Biaya Perjalanan Dinas (RBPD), dan Daftar Pengeluaran Rill
 * untuk telaah berkategori SETDA (menggantikan data dari database).
 *
 * Halaman ini hanya boleh diakses oleh user SKPD Sekretariat Daerah (skpd_id == 3).
 */
class Setuju_bayar extends public_Controller {

	private $json_path;

	function __construct()
	{
		parent::__construct();
		$this->load->model('setting/m_log');
		$this->load->model('setting_admin/m_setuju_bayar');
		$this->json_path = FCPATH . 'upload/json/data.json';

		// Harus login
		if (!$this->ion_auth->logged_in()) {
			redirect('login', 'refresh');
		}

		// Khusus Sekretariat Daerah (SETDA)
		if ($this->ion_auth->user()->row()->skpd_id != 3) {
			redirect('beranda', 'refresh');
		}
	}

	/**
	 * Baca isi data.json (mengembalikan nilai default bila belum ada / tidak valid).
	 */
	private function read_json()
	{
		$default = array(
			'label'      => 'PENGGUNA ANGGARAN',
			'pegawai_id' => '',
			'nama'       => '',
			'asal_opd'   => '',
			'nip'        => '',
		);

		if (!is_file($this->json_path)) {
			return $default;
		}

		$json = json_decode(file_get_contents($this->json_path), true);
		if (!is_array($json)) {
			return $default;
		}

		return array_merge($default, $json);
	}

	//View Form
	public function index()
	{
		$this->data['setuju_bayar'] = $this->read_json();
		$this->data['pegawai'] = $this->m_setuju_bayar->pegawai($this->ion_auth->user()->row()->skpd_id);
		$this->render('setting_admin/setuju_bayar/content');
	}

	//Simpan Data ke JSON
	public function update()
	{
		$this->form_validation->set_rules('label', 'Label', 'required');
		$this->form_validation->set_rules('pegawai_id', 'Nama Penandatangan', 'required');

		if ($this->form_validation->run() == FALSE) {
			$this->data['setuju_bayar'] = $this->read_json();
			$this->data['pegawai'] = $this->m_setuju_bayar->pegawai($this->ion_auth->user()->row()->skpd_id);
			$this->render('setting_admin/setuju_bayar/content');
			return;
		}

		// Nama, asal OPD, dan NIP diisi OTOMATIS dari data pegawai terpilih
		$pegawai = $this->m_setuju_bayar->get_pegawai($this->input->post('pegawai_id'));
		if (empty($pegawai)) {
			$this->session->set_flashdata('error', '<div class="alert alert-danger text-center"><b>Pegawai tidak ditemukan.</b></div>');
			redirect('setting_admin/setuju_bayar');
			return;
		}

		$data = array(
			'label'      => trim($this->input->post('label')),
			'pegawai_id' => $pegawai['pegawai_id'],
			'nama'       => $pegawai['pegawai_nama'],
			'asal_opd'   => $pegawai['skpd_nama'],
			'nip'        => $pegawai['pegawai_nip'],
		);

		// Pastikan folder tersedia
		$dir = dirname($this->json_path);
		if (!is_dir($dir)) {
			@mkdir($dir, 0777, TRUE);
		}

		$written = @file_put_contents($this->json_path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

		if ($written === FALSE) {
			$this->session->set_flashdata('error', '<div class="alert alert-danger text-center"><b>Gagal menyimpan data. Periksa izin tulis folder upload/json.</b></div>');
			redirect('setting_admin/setuju_bayar');
			return;
		}

		$log['kode_log_action'] = "";
		$log['action'] = "UPDATE Setuju Bayar SETDA (data.json)";
		$log['kode_log_action_table'] = "";
		$log['action_table'] = "FILE upload/json/data.json";
		$this->m_log->create($log);

		$this->session->set_flashdata('notif', 'Data Setuju Bayar (SETDA) berhasil disimpan !');
		redirect('setting_admin/setuju_bayar');
	}
}
