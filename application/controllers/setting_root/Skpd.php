<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Skpd extends public_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('setting_root/m_skpd');
		$this->load->model('setting/m_log');
		$this->load->model('telaah/m_relasi_kelurahan');
		$this->load->model('setting_admin/m_jenis_skpd');

		$this->wajib_admin(); // pembatasan per method ada di bawah

		// Daftar/tambah/hapus SKPD hanya untuk super admin.
		// Admin OPD hanya boleh membuka dan mengubah SKPD miliknya sendiri.
		$method = strtolower((string) $this->router->fetch_method());
		if (!in_array($method, array('update_view', 'update'), TRUE)) {
			$this->wajib_super_admin();
		}
	}

	// Super admin boleh semua SKPD; admin OPD hanya SKPD miliknya
	private function boleh_ubah_skpd($skpd_id)
	{
		$skpd_id = (int) $skpd_id;
		if ($skpd_id <= 0) {
			return FALSE;
		}
		return $this->is_super_admin() || $skpd_id === $this->skpd_id_login();
	}

	// Nama file kop surat: jangan pakai input mentah (dulu memakai skpd_nama apa adanya).
	// Tanpa titik agar ekstensi diambil dari file upload yang sudah divalidasi CI.
	private function nama_file_kop($nama)
	{
		$nama = trim(preg_replace('/[^A-Za-z0-9]+/', '_', (string) $nama), '_');
		$nama = substr($nama, 0, 60);
		return ($nama !== '' ? $nama : 'kop_surat') . '_' . bin2hex(random_bytes(4));
	}

	private function hapus_file_kop($nama_file)
	{
		$nama_file = basename((string) $nama_file);
		$path = './upload/kop_surat/' . $nama_file;
		if ($nama_file !== '' && is_file($path)) {
			unlink($path);
		}
	}

	//View All Data
	public function index()
	{
		$config = array();
		$config["base_url"] = base_url() . "setting_root/skpd/index";
		$config["total_rows"] = $this->m_skpd->record_count();
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
		$this->data['skpd'] = $this->m_skpd->data($config["per_page"], $page);
		$this->render('setting_root/skpd/content');
	}

	//View Data Search
	public function search()
	{
		if ($this->input->post('submit')) {
			$column = "skpd_nama";
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

		$config = array();
		$config["base_url"] = base_url() . "setting_root/skpd/search/" . $query . "/" . $column;
		$config["total_rows"] = $this->m_skpd->record_count_search($column, $query);
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
			$data['number'] = 0;
		} else {
			$data['number'] = $this->uri->segment(6);
		}

		$this->pagination->initialize($config);
		$page = ($this->uri->segment(6)) ? $this->uri->segment(6) : 0;

		$this->data['links'] = $this->pagination->create_links();
		$this->data['skpd'] = $this->m_skpd->data_search($column, $query, $config["per_page"], $page);
		$this->render('setting_root/skpd/content');
	}

	//View Create Data
	public function create_view()
	{
		$this->data['skpd'] = $this->m_skpd->get_kecamatan();
		$this->data['jenis_skpd'] = $this->m_jenis_skpd->get_all();
		$this->render('setting_root/skpd/insert');
	}

	//Create Data
	public function create()
	{
		//$this->form_validation->set_rules('skpd_kode', 'Kode SKPD', 'required');
		$this->form_validation->set_rules('skpd_nama', 'Nama SKPD', 'required');

		if ($this->form_validation->run() == FALSE) {
			$this->data['jenis_skpd'] = $this->m_jenis_skpd->get_all();
			$this->render('setting_root/skpd/insert');
		} else {
			$userfile_name = isset($_FILES['userfile']['name']) ? $_FILES['userfile']['name'] : '';
			if ($userfile_name == '') {

				if ($this->input->post('jenis_skpd') == 5) {

					$data['skpd_kode'] = $this->input->post('skpd_kode');
					$data['skpd_nama'] = $this->input->post('skpd_nama');
					$data['kepala'] = $this->input->post('kepala');
					$data['nip'] = $this->input->post('nip');
					$data['jenis_skpd'] = $this->input->post('jenis_skpd');
					$data['status'] = 1;
					$this->m_skpd->create($data);

					$last = $this->m_skpd->getLast();
					foreach ($last as $l) {
						$last_id = $l->skpd_id;
					}

					$data2['id_kecamatan'] = $this->input->post('id_kecamatan');
					$data2['id_kelurahan'] = $last_id;
					$this->m_relasi_kelurahan->create($data2);
				} else {

					$data['skpd_kode'] = $this->input->post('skpd_kode');
					$data['skpd_nama'] = $this->input->post('skpd_nama');
					$data['kepala'] = $this->input->post('kepala');
					$data['nip'] = $this->input->post('nip');
					$data['jenis_skpd'] = $this->input->post('jenis_skpd');
					$data['status'] = 1;
					$this->m_skpd->create($data);
				}

				$log['kode_log_action'] = "53";
				$log['action'] = "Insert";
				$log['kode_log_action_table'] = "14";
				$log['action_table'] = "Table Skpd";
				$this->m_log->create($log);

				$this->session->set_flashdata('notif', 'Data SKPD Di SImpan !');
				redirect('setting_root/skpd');
			} else {

				$filename = $this->nama_file_kop($this->input->post('skpd_nama'));
				$config['upload_path'] = './upload/kop_surat/';
				$config['allowed_types'] = "gif|jpg|jpeg|png|webp";
				$config['overwrite'] = FALSE;
				$config['max_size'] = MAKS_UPLOAD_GAMBAR_KB;
				$config['max_width'] = "10000";
				$config['max_height'] = "10000";
				$config['file_name'] = $filename; // sudah acak
				$this->upload->initialize($config);

				if (!$this->upload->do_upload('userfile')) {
					$this->session->set_flashdata('error', 'Gagal upload KOP Surat: ' . $this->upload->display_errors('', '') . ' (format JPG, PNG, GIF, WEBP, maks. 2 MB)');
					redirect('setting_root/skpd/create_view');
				} else {
					$userfile = $this->upload->data();

					if ($this->input->post('jenis_skpd') == 5) {

						$data['skpd_kode'] = $this->input->post('skpd_kode');
						$data['skpd_nama'] = $this->input->post('skpd_nama');
						$data['kepala'] = $this->input->post('kepala');
						$data['nip'] = $this->input->post('nip');
						$data['kop_surat'] = $userfile['file_name'];
						$data['jenis_skpd'] = $this->input->post('jenis_skpd');
						$data['status'] = 1;
						$this->m_skpd->create($data);

						$last = $this->m_skpd->getLast();
						foreach ($last as $l) {
							$last_id = $l->skpd_id;
						}

						$data2['id_kecamatan'] = $this->input->post('id_kecamatan');
						$data2['id_kelurahan'] = $last_id;
						$this->m_relasi_kelurahan->create($data2);
					} else if ($this->input->post('jenis_skpd') == 2) {

						$data['skpd_kode'] = $this->input->post('skpd_kode');
						$data['skpd_nama'] = $this->input->post('skpd_nama');
						$data['kepala'] = $this->input->post('kepala');
						$data['nip'] = $this->input->post('nip');
						$data['kop_surat'] = $userfile['file_name'];
						$userfile2_name = isset($_FILES['userfile2']['name']) ? $_FILES['userfile2']['name'] : '';
						if ($userfile2_name != '' && $this->upload->do_upload('userfile2')) {
							$userfile2 = $this->upload->data();
							$data['kop_surat2'] = $userfile2['file_name'];
						}
						$data['jenis_skpd'] = $this->input->post('jenis_skpd');
						$data['status'] = 1;
						$this->m_skpd->create($data);
					} else {

						$data['skpd_kode'] = $this->input->post('skpd_kode');
						$data['skpd_nama'] = $this->input->post('skpd_nama');
						$data['kepala'] = $this->input->post('kepala');
						$data['nip'] = $this->input->post('nip');
						$data['kop_surat'] = $userfile['file_name'];
						$data['jenis_skpd'] = $this->input->post('jenis_skpd');
						$data['status'] = 1;
						$this->m_skpd->create($data);
					}

					$log['kode_log_action'] = "53";
					$log['action'] = "INSERT";
					$log['kode_log_action_table'] = "14";
					$log['action_table'] = "TABLE SKPD";
					$this->m_log->create($log);

					$this->session->set_flashdata('notif', 'Data SKPD Di Simpan !');
					redirect('setting_root/skpd');
				}
			}
		}
	}

	//View Update Data
	public function update_view()
	{
		$skpd_id = $this->encrypt->decode(base64_decode($this->input->get('skpd_id')), $this->session->userdata('encrypt_key'));
		if (!$this->boleh_ubah_skpd($skpd_id)) {
			redirect('beranda');
		}

		$this->data['skpd'] = $this->m_skpd->get_kecamatan();
		$this->data['jenis_skpd'] = $this->m_jenis_skpd->get_all();
		$this->data['entry'] =  $this->m_skpd->get($skpd_id);
		if (!isset($this->data['entry'][0]) || $this->data['entry'][0] == "") {
			redirect('setting_root/skpd');
		} else {
			$this->render('setting_root/skpd/update');
		}
	}

	//Update Data
	public function update()
	{
		if (!$this->boleh_ubah_skpd($this->input->post('skpd_id'))) {
			redirect('beranda');
		}

		//$this->form_validation->set_rules('skpd_kode', 'Kode SKPD', 'required');
		$this->form_validation->set_rules('skpd_nama', 'Nama SKPD', 'required');

		if ($this->form_validation->run() == FALSE) {
			$this->data['skpd'] = $this->m_skpd->get_kecamatan();
			$this->data['jenis_skpd'] = $this->m_jenis_skpd->get_all();
			$this->data['entry'] =  $this->m_skpd->get($this->input->post('skpd_id'));
			if (!isset($this->data['entry'][0]) || $this->data['entry'][0] == "") {
				redirect('setting_root/skpd');
			} else {
				$this->render('setting_root/skpd/update');
			}
		} else {
			$userfile_name = isset($_FILES['userfile']['name']) ? $_FILES['userfile']['name'] : '';
			$userfile2_name = isset($_FILES['userfile2']['name']) ? $_FILES['userfile2']['name'] : '';
			if ($userfile_name == '' && $userfile2_name == '') {

				if ($this->input->post('jenis_skpd') == 5) {

					$data['skpd_id'] = $this->input->post('skpd_id');
					$data['skpd_kode'] = $this->input->post('skpd_kode');
					$data['skpd_nama'] = $this->input->post('skpd_nama');
					$data['kepala'] = $this->input->post('kepala');
					$data['nip'] = $this->input->post('nip');
					$data['jenis_skpd'] = $this->input->post('jenis_skpd');
					$data['status'] = 1;

					$this->m_skpd->update($data);

					$data2['id_kecamatan'] = $this->input->post('id_kecamatan');
					$data2['id_kelurahan'] = $this->input->post('skpd_id');
					$this->m_relasi_kelurahan->update($data2);
				} else {

					$data['skpd_id'] = $this->input->post('skpd_id');
					$data['skpd_kode'] = $this->input->post('skpd_kode');
					$data['skpd_nama'] = $this->input->post('skpd_nama');
					$data['kepala'] = $this->input->post('kepala');
					$data['nip'] = $this->input->post('nip');
					$data['jenis_skpd'] = $this->input->post('jenis_skpd');
					$data['status'] = 1;

					$this->m_skpd->update($data);
				}

				$log['kode_log_action'] = "54";
				$log['action'] = "Update dengan skpd_id = " . $this->input->post('skpd_id');
				$log['kode_log_action_table'] = "14";
				$log['action_table'] = "Table Skpd";
				$this->m_log->create($log);

				$this->session->set_flashdata('notif', 'Data SKPD Di Ubah !');
				if ($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->skpd_id == "") {
					redirect('setting_root/skpd');
				} else {
					$skpd_id = base64_encode($this->encrypt->encode($this->input->post('skpd_id'), $this->session->userdata('encrypt_key')));
					redirect('setting_root/skpd/update_view?skpd_id=' . $skpd_id);
				}
			} else {

				$path_file = './upload/kop_surat/';

				// Ambil nama file lama dari database
				$image = $this->m_skpd->link_gambar($this->input->post('skpd_id'));
				$old_kop_surat = '';
				$old_kop_surat2 = '';
				if ($image && $image->num_rows() > 0) {
					$row = $image->row();
					$old_kop_surat = $row->kop_surat;
					$old_kop_surat2 = isset($row->kop_surat2) ? $row->kop_surat2 : '';
				}

				// Upload userfile (kop_surat)
				if ($userfile_name != '') {
					$config_upload = array();
					$config_upload['upload_path'] = $path_file;
					$config_upload['allowed_types'] = "gif|jpg|jpeg|png|webp";
					$config_upload['overwrite'] = FALSE;
					$config_upload['max_size'] = MAKS_UPLOAD_GAMBAR_KB;
					$config_upload['max_width'] = 10000;
					$config_upload['max_height'] = 10000;
					$config_upload['file_name'] = $this->nama_file_kop($this->input->post('skpd_nama'));
					$this->upload->initialize($config_upload);

					if ($this->upload->do_upload('userfile')) {
						$userfile = $this->upload->data();
						// Hapus file lama hanya jika nama berbeda dengan file baru
						if ($old_kop_surat && $old_kop_surat != $userfile['file_name']) {
							$this->hapus_file_kop($old_kop_surat);
						}
					} else {
						$this->session->set_flashdata('error', 'Gagal upload KOP Surat: ' . $this->upload->display_errors('', '') . ' (format JPG, PNG, GIF, WEBP, maks. 2 MB)');
						$skpd_id = base64_encode($this->encrypt->encode($this->input->post('skpd_id'), $this->session->userdata('encrypt_key')));
						redirect('setting_root/skpd/update_view?skpd_id=' . $skpd_id);
					}
				}

				// Upload userfile2 (kop_surat2)
				if ($userfile2_name != '') {
					$config_upload2 = array();
					$config_upload2['upload_path'] = $path_file;
					$config_upload2['allowed_types'] = "gif|jpg|jpeg|png|webp";
					$config_upload2['overwrite'] = FALSE;
					$config_upload2['max_size'] = MAKS_UPLOAD_GAMBAR_KB;
					$config_upload2['max_width'] = 10000;
					$config_upload2['max_height'] = 10000;
					$config_upload2['file_name'] = $this->nama_file_kop($this->input->post('skpd_nama'));
					$this->upload->initialize($config_upload2);

					if ($this->upload->do_upload('userfile2')) {
						$userfile2 = $this->upload->data();
						// Hapus file lama hanya jika nama berbeda dengan file baru
						if ($old_kop_surat2 && $old_kop_surat2 != $userfile2['file_name']) {
							$this->hapus_file_kop($old_kop_surat2);
						}
					} else {
						$this->session->set_flashdata('error', 'Gagal upload KOP Surat Anggota DPRD: ' . $this->upload->display_errors('', '') . ' (format JPG, PNG, GIF, WEBP, maks. 2 MB)');
						$skpd_id = base64_encode($this->encrypt->encode($this->input->post('skpd_id'), $this->session->userdata('encrypt_key')));
						redirect('setting_root/skpd/update_view?skpd_id=' . $skpd_id);
					}
				}

				// Simpan data ke database
				$data['skpd_id'] = $this->input->post('skpd_id');
				$data['skpd_kode'] = $this->input->post('skpd_kode');
				$data['skpd_nama'] = $this->input->post('skpd_nama');
				$data['kepala'] = $this->input->post('kepala');
				$data['nip'] = $this->input->post('nip');
				$data['jenis_skpd'] = $this->input->post('jenis_skpd');
				$data['status'] = 1;

				if (isset($userfile)) {
					$data['kop_surat'] = $userfile['file_name'];
				}
				if (isset($userfile2)) {
					$data['kop_surat2'] = $userfile2['file_name'];
				}

				$this->m_skpd->update($data);

				if ($this->input->post('jenis_skpd') == 5) {
					$data2['id_kecamatan'] = $this->input->post('id_kecamatan');
					$data2['id_kelurahan'] = $this->input->post('skpd_id');
					$this->m_relasi_kelurahan->update($data2);
				}

				$log['kode_log_action'] = "54";
				$log['action'] = "UPDATE skpd_id = " . $this->input->post('skpd_id');
				$log['kode_log_action_table'] = "14";
				$log['action_table'] = "TABLE SKPD";
				$this->m_log->create($log);

				$this->session->set_flashdata('notif', 'Data SKPD Di Ubah !');
				if ($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->skpd_id == "") {
					redirect('setting_root/skpd');
				} else {
					$skpd_id = base64_encode($this->encrypt->encode($this->input->post('skpd_id'), $this->session->userdata('encrypt_key')));
					redirect('setting_root/skpd/update_view?skpd_id=' . $skpd_id);
				}
			}
		}
	}

	//Delete Data
	public function delete()
	{
		$skpd_id = $this->encrypt->decode(base64_decode($this->input->get('skpd_id')), $this->session->userdata('encrypt_key'));

		if ((int) $skpd_id <= 0) {
			redirect('setting_root/skpd');
		}

		$image = $this->m_skpd->link_gambar($skpd_id);
		if ($image && $image->num_rows() > 0) {
			$row = $image->row();
			$this->hapus_file_kop($row->kop_surat);
		}

		$this->m_skpd->delete($skpd_id);
		$this->m_relasi_kelurahan->delete($skpd_id);

		$log['kode_log_action'] = "56";
		$log['action'] = "DELETE skpd_id = " . $skpd_id;
		$log['kode_log_action_table'] = "14";
		$log['action_table'] = "TABLE SKPD";
		$this->m_log->create($log);

		$this->session->set_flashdata('notif', 'Data SKPD Di Hapus !');
		redirect('setting_root/skpd');
	}
}
