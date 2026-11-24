<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pegawai extends public_Controller
{
	function dd($data)
	{
		echo '<pre>';
		print_r($data);
		exit;
	}

	function __construct()
	{
		parent::__construct();
		error_reporting(0);
		$this->load->model('setting_admin/m_pegawai');
		$this->load->model('telaah/M_relasi_sekda', 'm_relasi_sekda');
		$this->load->model('setting_admin/m_anggota');
		$this->load->model('setting/m_log');

		// get_pelaksana/get_pengikut dipakai form telaah (semua user login);
		// selebihnya (CRUD/import pegawai) hanya untuk admin
		$method = strtolower((string) $this->router->fetch_method());
		if (!in_array($method, array('get_pelaksana', 'get_pengikut'), TRUE)) {
			$this->wajib_admin();
		}

		// Admin OPD hanya boleh membuka/mengubah/menghapus pegawai di SKPD-nya sendiri
		// (dulu pegawai SKPD lain bisa diubah lewat pegawai_id, termasuk NIK & tanda tangan pejabat)
		if (in_array($method, array('update_view', 'delete'), TRUE)) {
			$this->wajib_milik_skpd('table_pegawai', 'pegawai_id', $this->id_dari_get('pegawai_id'), 'setting_admin/pegawai');
		} elseif ($method === 'update') {
			$this->wajib_milik_skpd('table_pegawai', 'pegawai_id', $this->input->post('pegawai_id'), 'setting_admin/pegawai');
		}
	}
	//View All Data
	public function index()
	{
		$staff_sekda = false;
		if ($this->ion_auth->user()->row()->jenis_skpd == 3) {
			$staff_sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
		}
		$config = array();
		$config["base_url"] = base_url() . "setting_admin/pegawai/index";
		if ($staff_sekda) {
			$config["total_rows"] = $this->m_pegawai->data_setda('', '', $this->ion_auth->user()->row()->skpd_id, $staff_sekda[0]['bagian_id']);
		} else {
			$config["total_rows"] = $this->m_pegawai->data('', '', $this->ion_auth->user()->row()->skpd_id);
		}
		$config["per_page"] = 25;
		$config["uri_segment"] = 4;
		$choice = $config["total_rows"] / $config["per_page"];
		$config["num_links"] = 5;

		$config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = 'Previous';
		$config['prev_tag_open'] = '<li class="prev">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = 'Next';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		if ($this->uri->segment(4) == "") {
			$this->data['number'] = 0;
		} else {
			$this->data['number'] = $this->uri->segment(4);
		}

		$this->pagination->initialize($config);
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

		$this->data['links'] = $this->pagination->create_links();
		// $this->data['pegawai'] = $this->m_pegawai->data($config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
		if ($staff_sekda) {
			$this->data['pegawai'] = $this->m_pegawai->data_setda($config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id, $staff_sekda[0]['bagian_id']);
		} else {
			$this->data['pegawai'] = $this->m_pegawai->data($config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
		}
		$this->render('setting_admin/pegawai/content');
	}

	//View Data Search
	public function search()
	{
		if ($this->input->post('submit')) {
			$column = kolom_aman($this->input->post('column'));
			$query = $this->input->post('data');

			$option = array(
				'user_column' => $column,
				'user_data' => $query
			);
			$this->session->set_userdata($option);
		} else {
			$query = $this->uri->segment(4);
			$column = kolom_aman($this->uri->segment(5));
		}
		$staff_sekda = false;
		if ($this->ion_auth->user()->row()->jenis_skpd == 3) {
			$staff_sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
		}
		$config = array();
		$config["base_url"] = base_url() . "setting_admin/pegawai/search/" . $query . "/" . $column;
		if ($staff_sekda) {
			$config["total_rows"] = $this->m_pegawai->data_search_setda($column, $query, '', '', $this->ion_auth->user()->row()->skpd_id, $staff_sekda[0]['bagian_id']);
		} else {
			$config["total_rows"] = $this->m_pegawai->data_search($column, $query, '', '', $this->ion_auth->user()->row()->skpd_id);
		}
		$config["per_page"] = 25;
		$config["uri_segment"] = 6;
		$choice = $config["total_rows"] / $config["per_page"];
		$config["num_links"] = 5;

		$config['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
		$config['full_tag_close'] = '</ul>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = 'Previous';
		$config['prev_tag_open'] = '<li class="prev">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = 'Next';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		if ($this->uri->segment(6) == "") {
			$this->data['number'] = 0;
		} else {
			$this->data['number'] = $this->uri->segment(6);
		}

		$this->pagination->initialize($config);
		$page = ($this->uri->segment(6)) ? $this->uri->segment(6) : 0;

		$this->data['links'] = $this->pagination->create_links();
		if ($staff_sekda) {
			$this->data['pegawai'] = $this->m_pegawai->data_search_setda($column, $query, $config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id, $staff_sekda[0]['bagian_id']);
		} else {
			$this->data['pegawai'] = $this->m_pegawai->data_search($column, $query, $config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
		}
		$this->render('setting_admin/pegawai/content');
	}

	//View Create Data
	public function create_view()
	{
		if ($this->ion_auth->user()->row()->jenis_skpd == 3) {
			$staff_sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
			$this->data['staff_sekda'] = $staff_sekda;
			if ($staff_sekda) {
				$this->data['bagian'] = $this->m_relasi_sekda->bagian($staff_sekda[0]['bagian_id']);
			} else {
				$this->data['bagian'] = $this->m_relasi_sekda->bagian_all();
			}
		}

		$this->data['skpd'] = $this->m_pegawai->skpd();
		$this->data['golongan'] = $this->m_pegawai->golongan();
		$this->data['esselon'] = $this->m_pegawai->esselon();
		$this->data['jabatan'] = $this->m_pegawai->jabatan();
		$this->render('setting_admin/pegawai/insert');
	}

	//Create Data
	public function create()
	{
		$this->form_validation->set_rules('pegawai_nip', 'NIP', 'required|integer');
		$this->form_validation->set_rules('pegawai_nik', 'NIK', 'trim|integer'); // opsional, tapi bila diisi harus angka
		$this->form_validation->set_rules('pegawai_nama', 'Nama Pegawai', 'required|max_length[255]');

		if ($this->form_validation->run() == FALSE) {
			if ($this->ion_auth->user()->row()->jenis_skpd == 3) {
				$staff_sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
				$this->data['staff_sekda'] = $staff_sekda;
				if ($staff_sekda) {
					$this->data['bagian'] = $this->m_relasi_sekda->bagian($staff_sekda[0]['bagian_id']);
				} else {
					$this->data['bagian'] = $this->m_relasi_sekda->bagian_all();
				}
			}
			$this->data['golongan'] = $this->m_pegawai->golongan();
			$this->data['esselon'] = $this->m_pegawai->esselon();
			$this->data['jabatan'] = $this->m_pegawai->jabatan();
			$this->render('setting_admin/pegawai/insert');
		} else {
			$filename = $this->input->post('pegawai_nip');
			$config['upload_path'] = './upload/tanda_tangan/';
			$config['allowed_types'] = "png";
			$config['overwrite'] = FALSE;
			$config['max_size'] = MAKS_UPLOAD_GAMBAR_KB;
			$config['max_width'] = "10000";
			$config['max_height'] = "10000";
			$config['file_name'] = nama_file_unggah($filename);
			$this->upload->initialize($config);
			$ttd_terunggah = $this->upload->do_upload();
			if (!$ttd_terunggah && !empty($_FILES['userfile']['name'])) {
				// file dipilih tapi ditolak: data pegawai tetap disimpan, user diberi tahu tanda tangannya gagal
				$this->session->set_flashdata('error', 'Tanda tangan tidak diunggah: ' . $this->upload->display_errors('', '') . ' Tanda tangan harus berupa file PNG, maks. 2 MB. Data pegawai lainnya tetap disimpan.');
			}
			if (!$ttd_terunggah) {

				$data['pegawai_nip'] = $this->input->post('pegawai_nip');
				$data['pegawai_nik'] = $this->input->post('pegawai_nik');
				$data['pegawai_nama'] = $this->input->post('pegawai_nama');
				$data['pegawai_jabatan'] = $this->input->post('pegawai_jabatan');
				$data['pegawai_namajabatan'] = $this->input->post('pegawai_namajabatan');
				//$data['pegawai_tanggalmenjabat'] = $this->input->post('pegawai_tanggalmenjabat');
				$data['pegawai_golongan'] = $this->input->post('pegawai_golongan');
				$data['tanggal'] = date("Y-m-d");
				$data['waktu'] = date("h:i:s");
				//$data['status'] = $this->input->post('status');
				$data['status_tandatangan'] = $this->input->post('status_tandatangan');
				if ($this->ion_auth->user()->row()->jenis_skpd == 3) {
					$data['bagian_id'] = $this->input->post('bagian_id');
				}
				$data['skpd_id'] = $this->ion_auth->user()->row()->skpd_id;

				$this->m_pegawai->create($data);

				$log['kode_log_action'] = "53";
				$log['action'] = "INSERT";
				$log['kode_log_action_table'] = "8";
				$log['action_table'] = "TABLE PEGAWAI";
				$this->m_log->create($log);

				$this->session->set_flashdata('notif', 'Data Pegawai Di Simpan !');
				redirect('setting_admin/pegawai');
			} else {

				$dat = $this->upload->data();

				$data['pegawai_nip'] = $this->input->post('pegawai_nip');
				$data['pegawai_nik'] = $this->input->post('pegawai_nik');
				$data['pegawai_nama'] = $this->input->post('pegawai_nama');
				$data['pegawai_jabatan'] = $this->input->post('pegawai_jabatan');
				$data['pegawai_namajabatan'] = $this->input->post('pegawai_namajabatan');
				//$data['pegawai_tanggalmenjabat'] = $this->input->post('pegawai_tanggalmenjabat');
				$data['pegawai_golongan'] = $this->input->post('pegawai_golongan');
				$data['tanggal'] = date("Y-m-d");
				$data['waktu'] = date("h:i:s");
				//$data['status'] = $this->input->post('status');
				$data['status_tandatangan'] = $this->input->post('status_tandatangan');
				if ($this->ion_auth->user()->row()->jenis_skpd == 3) {
					$data['bagian_id'] = $this->input->post('bagian_id');
				}
				$data['skpd_id'] = $this->ion_auth->user()->row()->skpd_id;
				$data['pegawai_tandatangan'] = $dat['file_name'];

				$this->m_pegawai->create($data);

				$log['kode_log_action'] = "53";
				$log['action'] = "INSERT";
				$log['kode_log_action_table'] = "8";
				$log['action_table'] = "TABLE PEGAWAI";
				$this->m_log->create($log);

				$this->session->set_flashdata('notif', 'Data Pegawai Di Simpan !');
				redirect('setting_admin/pegawai');
			}
		}
	}

	//View Update Data
	public function update_view()
	{
		$pegawai_id = $this->encrypt->decode(base64_decode($this->input->get('pegawai_id')), $this->session->userdata('encrypt_key'));

		$this->data['entry'] =  $this->m_pegawai->get($pegawai_id);
		if (!isset($this->data['entry'][0]) || $this->data['entry'][0] == "") {
			redirect('setting_admin/pegawai');
		} else {

			if ($this->ion_auth->user()->row()->jenis_skpd == 3) {
				$staff_sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
				$this->data['staff_sekda'] = $staff_sekda;
				if ($staff_sekda) {
					$this->data['bagian'] = $this->m_relasi_sekda->bagian($staff_sekda[0]['bagian_id']);
				} else {
					$this->data['bagian'] = $this->m_relasi_sekda->bagian_all();
				}
			}

			$this->data['skpd'] = $this->m_pegawai->skpd();
			$this->data['golongan'] = $this->m_pegawai->golongan();
			$this->data['esselon'] = $this->m_pegawai->esselon();
			$this->data['jabatan'] = $this->m_pegawai->jabatan();
			$this->render('setting_admin/pegawai/update');
		}
	}
	//Update Data
	public function update()
	{
		$this->form_validation->set_rules('pegawai_nip', 'NIP', 'required|integer');
		$this->form_validation->set_rules('pegawai_nik', 'NIK', 'trim|integer'); // opsional, tapi bila diisi harus angka
		$this->form_validation->set_rules('pegawai_nama', 'Nama Pegawai', 'required|max_length[255]');

		if ($this->form_validation->run() == FALSE) {
			$this->data['entry'] =  $this->m_pegawai->get($this->input->post('pegawai_id'));
			if (!isset($this->data['entry'][0]) || $this->data['entry'][0] == "") {
				redirect('setting_admin/pegawai');
			} else {
				if ($this->ion_auth->user()->row()->jenis_skpd == 3) {
					$staff_sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
					$this->data['staff_sekda'] = $staff_sekda;
					if ($staff_sekda) {
						$this->data['bagian'] = $this->m_relasi_sekda->bagian($staff_sekda[0]['bagian_id']);
					} else {
						$this->data['bagian'] = $this->m_relasi_sekda->bagian_all();
					}
				}
				$this->data['skpd'] = $this->m_pegawai->skpd();
				$this->data['golongan'] = $this->m_pegawai->golongan();
				$this->data['esselon'] = $this->m_pegawai->esselon();
				$this->data['jabatan'] = $this->m_pegawai->jabatan();
				$this->render('setting_admin/pegawai/update');
			}
		} else {
			$pegawai_id = base64_encode($this->encrypt->encode($this->input->post('pegawai_id'), $this->session->userdata('encrypt_key')));
			$filename = $this->input->post('pegawai_nip');
			$config['upload_path'] = './upload/tanda_tangan/';
			$config['allowed_types'] = "png";
			$config['overwrite'] = FALSE;
			$config['max_size'] = MAKS_UPLOAD_GAMBAR_KB;
			$config['max_width'] = "10000";
			$config['max_height'] = "10000";
			$config['file_name'] = nama_file_unggah($filename);
			$this->upload->initialize($config);
			$ttd_terunggah = $this->upload->do_upload();
			if (!$ttd_terunggah && !empty($_FILES['userfile']['name'])) {
				// file dipilih tapi ditolak: data pegawai tetap disimpan, user diberi tahu tanda tangannya gagal
				$this->session->set_flashdata('error', 'Tanda tangan tidak diunggah: ' . $this->upload->display_errors('', '') . ' Tanda tangan harus berupa file PNG, maks. 2 MB. Data pegawai lainnya tetap disimpan.');
			}
			if (!$ttd_terunggah) {

				if ($this->input->post('pegawai_nip') == $this->input->post('pegawai_nip2')) {

					$data['pegawai_id'] = $this->input->post('pegawai_id');
					$data['pegawai_nip'] = $this->input->post('pegawai_nip');
					$data['pegawai_nik'] = $this->input->post('pegawai_nik');
					$data['pegawai_nama'] = $this->input->post('pegawai_nama');
					$data['pegawai_jabatan'] = $this->input->post('pegawai_jabatan');
					$data['pegawai_namajabatan'] = $this->input->post('pegawai_namajabatan');
					//$data['pegawai_tanggalmenjabat'] = $this->input->post('pegawai_tanggalmenjabat');
					$data['pegawai_golongan'] = $this->input->post('pegawai_golongan');
					$data['status'] = $this->input->post('status');
					$data['status_tandatangan'] = $this->input->post('status_tandatangan');
					if ($this->ion_auth->user()->row()->jenis_skpd == 3) {
						$data['bagian_id'] = $this->input->post('bagian_id');
					}
					$data['skpd_id'] = $this->ion_auth->user()->row()->skpd_id;

					$this->m_pegawai->update($data);

					$log['kode_log_action'] = "54";
					$log['action'] = "UPDATE pegawai_id = " . $this->input->post('pegawai_id');
					$log['kode_log_action_table'] = "8";
					$log['action_table'] = "TABLE PEGAWAI";
					$this->m_log->create($log);

					$this->session->set_flashdata('notif', 'Data Pegawai Di Ubah !');
					redirect('setting_admin/pegawai/update_view?pegawai_id=' . $pegawai_id);
				} else {

					$data2['pegawai_id'] = $this->input->post('pegawai_id');
					$data2['status_delete'] = 1;

					$this->m_pegawai->update($data2);

					$data['pegawai_nip'] = $this->input->post('pegawai_nip');
					$data['pegawai_nik'] = $this->input->post('pegawai_nik');
					$data['pegawai_nama'] = $this->input->post('pegawai_nama');
					$data['pegawai_jabatan'] = $this->input->post('pegawai_jabatan');
					$data['pegawai_namajabatan'] = $this->input->post('pegawai_namajabatan');
					//$data['pegawai_tanggalmenjabat'] = $this->input->post('pegawai_tanggalmenjabat');
					$data['pegawai_golongan'] = $this->input->post('pegawai_golongan');
					$data['tanggal'] = date("Y-m-d");
					$data['waktu'] = date("h:i:s");
					//$data['status'] = $this->input->post('status');
					$data['status_tandatangan'] = $this->input->post('status_tandatangan');
					if ($this->ion_auth->user()->row()->jenis_skpd == 3) {
						$data['bagian_id'] = $this->input->post('bagian_id');
					}
					$data['skpd_id'] = $this->ion_auth->user()->row()->skpd_id;

					$this->m_pegawai->create($data);

					$log['kode_log_action'] = "53";
					$log['action'] = "INSERT";
					$log['kode_log_action_table'] = "8";
					$log['action_table'] = "TABLE PEGAWAI";
					$this->m_log->create($log);

					$this->session->set_flashdata('notif', 'Data Pegawai Di Simpan !');
					redirect('setting_admin/pegawai');
				}
			} else {

				$dat = $this->upload->data();

				if ($this->input->post('pegawai_nip') == $this->input->post('pegawai_nip2')) {

					$image = $this->m_pegawai->link_gambar($this->input->post('pegawai_id'));
					if ($image->num_rows() > 0) {
						$row = $image->row();
						$file_gambar = $row->pegawai_tandatangan;
						$path_file = './upload/tanda_tangan/';
						hapus_file_unggah($path_file, $file_gambar);
					}

					$dat = $this->upload->data();

					$data['pegawai_id'] = $this->input->post('pegawai_id');
					$data['pegawai_nip'] = $this->input->post('pegawai_nip');
					$data['pegawai_nik'] = $this->input->post('pegawai_nik');
					$data['pegawai_nama'] = $this->input->post('pegawai_nama');
					$data['pegawai_jabatan'] = $this->input->post('pegawai_jabatan');
					$data['pegawai_namajabatan'] = $this->input->post('pegawai_namajabatan');
					//$data['pegawai_tanggalmenjabat'] = $this->input->post('pegawai_tanggalmenjabat');
					$data['pegawai_golongan'] = $this->input->post('pegawai_golongan');
					$data['status'] = $this->input->post('status');
					$data['status_tandatangan'] = $this->input->post('status_tandatangan');
					if ($this->ion_auth->user()->row()->jenis_skpd == 3) {
						$data['bagian_id'] = $this->input->post('bagian_id');
					}
					$data['skpd_id'] = $this->ion_auth->user()->row()->skpd_id;
					$data['pegawai_tandatangan'] = $dat['file_name'];

					$this->m_pegawai->update($data);

					$log['kode_log_action'] = "54";
					$log['action'] = "UPDATE pegawai_id = " . $this->input->post('pegawai_id');
					$log['kode_log_action_table'] = "8";
					$log['action_table'] = "TABLE PEGAWAI";
					$this->m_log->create($log);

					$this->session->set_flashdata('notif', 'Data Pegawai Di Ubah !');
					redirect('setting_admin/pegawai/update_view?pegawai_id=' . $pegawai_id);
				} else {

					$data2['pegawai_id'] = $this->input->post('pegawai_id');
					$data2['status_delete'] = 1;

					$this->m_pegawai->update($data2);

					$data['pegawai_nip'] = $this->input->post('pegawai_nip');
					$data['pegawai_nik'] = $this->input->post('pegawai_nik');
					$data['pegawai_nama'] = $this->input->post('pegawai_nama');
					$data['pegawai_jabatan'] = $this->input->post('pegawai_jabatan');
					$data['pegawai_namajabatan'] = $this->input->post('pegawai_namajabatan');
					$data['pegawai_golongan'] = $this->input->post('pegawai_golongan');
					$data['tanggal'] = date("Y-m-d");
					$data['waktu'] = date("h:i:s");
					$data['status_tandatangan'] = $this->input->post('status_tandatangan');
					if ($this->ion_auth->user()->row()->jenis_skpd == 3) {
						$data['bagian_id'] = $this->input->post('bagian_id');
					}
					$data['skpd_id'] = $this->ion_auth->user()->row()->skpd_id;
					$data['pegawai_tandatangan'] = $dat['file_name'];

					$this->m_pegawai->create($data);

					$log['kode_log_action'] = "53";
					$log['action'] = "INSERT";
					$log['kode_log_action_table'] = "8";
					$log['action_table'] = "TABLE PEGAWAI";
					$this->m_log->create($log);

					$this->session->set_flashdata('notif', 'Data Pegawai Di Simpan !');
					redirect('setting_admin/pegawai');
				}
			}
		}
	}

	//Delete Data
	public function delete()
	{
		$pegawai_id = $this->encrypt->decode(base64_decode($this->input->get('pegawai_id')), $this->session->userdata('encrypt_key'));

		$data['pegawai_id'] = $pegawai_id;
		$data['status_delete'] = 1;

		$this->m_pegawai->update($data);

		$log['kode_log_action'] = "56";
		$log['action'] = "DELETE pegawai_id = " . $pegawai_id;
		$log['kode_log_action_table'] = "8";
		$log['action_table'] = "TABLE PEGAWAI";
		$this->m_log->create($log);

		$this->session->set_flashdata('notif', 'Data pegawai Di Hapus !');
		redirect('setting_admin/pegawai');
	}

	public function import_view()
	{
		$this->render('setting_admin/pegawai/import');
	}

	public function import()
	{
		// Dulu isi CSV disisipkan langsung ke query SQL (SQL injection). Sekarang lewat query builder
		// (nilai di-escape), dan pegawai selalu masuk ke SKPD milik admin yang mengimpor.
		$f = isset($_FILES['filename']) ? $_FILES['filename'] : NULL;
		if (!$f || !is_string($f['name']) || $f['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($f['tmp_name'])) {
			$this->session->set_flashdata('notif', 'Pilih satu berkas CSV yang akan diimpor.');
			redirect('setting_admin/pegawai/import_view', 'refresh');
		}
		if (strtolower(pathinfo($f['name'], PATHINFO_EXTENSION)) !== 'csv' || $f['size'] > 2 * 1024 * 1024) {
			$this->session->set_flashdata('notif', 'Berkas harus berformat .csv dan maksimal 2 MB.');
			redirect('setting_admin/pegawai/import_view', 'refresh');
		}
		$skpd_id = $this->skpd_id_login();
		if ($skpd_id <= 0) {
			$this->session->set_flashdata('notif', 'Akun Anda tidak terhubung ke SKPD, import dibatalkan.');
			redirect('setting_admin/pegawai', 'refresh');
		}

		$teks = function ($v, $maks) {
			$v = trim(preg_replace('/[\x00-\x1F\x7F]/u', '', (string) $v));
			return mb_substr($v, 0, $maks, 'UTF-8');
		};
		$masuk = 0;
		$lewat = 0;
		$handle = fopen($f['tmp_name'], 'r');
		$this->db->trans_start();
		while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
			$nip  = $teks(isset($data[0]) ? $data[0] : '', 30);
			$nama = $teks(isset($data[1]) ? $data[1] : '', 255);
			// baris kosong / baris judul kolom dilewati
			if ($nama === '' || strtolower($nip) === 'nip' || strtolower($nama) === 'nama') {
				$lewat++;
				continue;
			}
			$this->db->insert('table_pegawai', array(
				'pegawai_nip'         => $nip,
				'pegawai_nama'        => $nama,
				'pegawai_golongan'    => $teks(isset($data[2]) ? $data[2] : '', 50),
				'pegawai_jabatan'     => $teks(isset($data[3]) ? $data[3] : '', 50),
				'pegawai_namajabatan' => $teks(isset($data[4]) ? $data[4] : '', 255),
				'skpd_id'             => $skpd_id,
			));
			$masuk++;
		}
		fclose($handle);
		$this->db->trans_complete();

		$log['kode_log_action'] = "53";
		$log['action'] = "IMPORT CSV " . $masuk . " pegawai";
		$log['kode_log_action_table'] = "8";
		$log['action_table'] = "TABLE PEGAWAI";
		$this->m_log->create($log);

		$this->session->set_flashdata('notif', $masuk . ' data pegawai berhasil diimpor' . ($lewat ? ' (' . $lewat . ' baris dilewati)' : '') . '.');
		redirect('setting_admin/pegawai', 'refresh');
	}

	public function get_pelaksana()
	{
		if ($this->uri->segment(4) == "walikota") {
			$data = $this->m_pegawai->get_walikota($this->uri->segment(5));
		} else if ($this->uri->segment(4) == "dprd") {
			$data = $this->m_anggota->get($this->uri->segment(5));
		} else {
			$data = $this->m_pegawai->get($this->uri->segment(5));
		}

		if ($this->uri->segment(4) == "dprd") {
			echo "<p style='text-align:left;'>: " . $data[0]['anggotadprd_name'] . " <span style='float:right;'>";
		} else {
			echo "<p style='text-align:left;'>: " . $data[0]['pegawai_nama'] . " <span style='float:right;'>";
		}

		if ($data[0]['status'] == 1) {
			echo "<a class='btn btn-xs btn-danger'>Sedang Melakukan Perjalanan</a>";
		} else {
			echo "<a class='btn btn-xs btn-success'>Tidak Sedang Melakukan Perjalanan</a>";
		};
		echo "</span></p>";

		if ($this->ion_auth->user()->row()->skpd_id == 182) {
			echo '<select class="form-control" form="form-telaah" name="telaah_jabatan_pelaksana" required>
				<option value="" >- Pilih Jabatan Dalam Perjalanan -</option>
				<option value="1">Penanggung Jawab</option>
				<option value="2">Pembantu Penanggung Jawab</option>
				<option value="3">Pengendali Teknis</option>
				<option value="4">Ketua Tim</option>
				<option value="5">Anggota</option>
				<option value="6">Admin Tim</option>
			</select>	';
		}
	}

	public function get_pengikut()
	{
		$data = $this->uri->segment(5);
		$pieces = explode("-", $data);
		foreach ($pieces as $i => $key) {
			if ($this->uri->segment(4) == "dprd") {
				$datax = $this->m_anggota->get($key);
			} else {
				$datax = $this->m_pegawai->get($key);
			}

			// id kosong / tidak ditemukan (mis. semua pengikut dihapus dari pilihan) tidak ditampilkan
			if ($key === '' || empty($datax)) {
				continue;
			}

			if ($this->uri->segment(4) == "dprd") {
				echo "<p style='text-align:left;'>: " . $datax[0]['anggotadprd_name'] . " <span style='float:right;'>";
			} else {
				echo "<p style='text-align:left;'>: " . $datax[0]['pegawai_nama'] . " <span style='float:right;'>";
			}

			if ($datax[0]['status'] == 1) {
				echo "<a class='btn btn-xs btn-danger'>Sedang Melakukan Perjalanan</a>";
			} else {
				echo "<a class='btn btn-xs btn-success'>Tidak Sedang Melakukan Perjalanan</a>";
			};
			echo "</span></p>";

			if ($this->ion_auth->user()->row()->skpd_id == 182) {
				echo '<select class="form-control" form="form-telaah" name="telaah_jabatan_pengikut[]" required>
						<option value="" >- Pilih Jabatan Dalam Perjalanan -</option>
						<option value="1">Penanggung Jawab</option>
						<option value="2">Pembantu Penanggung Jawab</option>
						<option value="3">Pengendali Teknis</option>
						<option value="4">Ketua Tim</option>
						<option value="5">Anggota</option>
						<option value="6">Admin Tim</option>
					</select><br>	';
			}
		}
	}
}
