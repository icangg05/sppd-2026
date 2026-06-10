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
			$column = $this->input->post('column');
			$query = $this->input->post('data');

			$option = array(
				'user_column' => $column,
				'user_data' => $query
			);
			$this->session->set_userdata($option);
		} else {
			$query = $this->uri->segment(4);
			$column = $this->uri->segment(5);
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
		$this->form_validation->set_rules('pegawai_nik', 'NIK', 'required|integer');
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
			$config['overwrite'] = "true";
			$config['max_size'] = "20000000";
			$config['max_width'] = "10000";
			$config['max_height'] = "10000";
			$config['file_name'] = '' . $filename;
			$this->upload->initialize($config);
			if (!$this->upload->do_upload()) {

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
		$this->form_validation->set_rules('pegawai_nik', 'NIK', 'required|integer');
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
			$filename = $this->input->post('pegawai_nip');
			$config['upload_path'] = './upload/tanda_tangan/';
			$config['allowed_types'] = "png";
			$config['overwrite'] = "true";
			$config['max_size'] = "20000000";
			$config['max_width'] = "10000";
			$config['max_height'] = "10000";
			$config['file_name'] = '' . $filename;
			$this->upload->initialize($config);
			if (!$this->upload->do_upload()) {

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
						unlink($path_file . $file_gambar);
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
		//if($this->input->post('submit')){

		$seq = 1;
		$skpd_id = $this->ion_auth->user()->row()->skpd_id;;
		$handle = fopen($_FILES['filename']['tmp_name'], "r"); //Membuka file dan membacanya
		while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

			$import = "
					INSERT INTO table_pegawai (pegawai_nip, pegawai_nama, pegawai_golongan, pegawai_jabatan, pegawai_namajabatan, skpd_id) 
					VALUES ('$data[0]','$data[1]','$data[2]', '$data[3]', '$data[4]', $skpd_id) 
				";

			//data array sesuaikan dengan jumlah kolom pada CSV anda mulai dari “0” bukan “1”
			$this->db->query($import);


			$seq++;
		}
		fclose($handle); //Menutup CSV file

		$this->session->set_flashdata('notif', 'Data CSV Berhasil Disimpan !');

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
			echo '<select class="form-control" name="telaah_jabatan_pelaksana" required>
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
				echo "<p style='text-align:left;'>: " . $datax[0]['anggotadprd_name'] . " <span style='float:right;'>";
			} else {
				$datax = $this->m_pegawai->get($key);
				echo "<p style='text-align:left;'>: " . $datax[0]['pegawai_nama'] . " <span style='float:right;'>";
			}

			if ($datax[0]['status'] == 1) {
				echo "<a class='btn btn-xs btn-danger'>Sedang Melakukan Perjalanan</a>";
			} else {
				echo "<a class='btn btn-xs btn-success'>Tidak Sedang Melakukan Perjalanan</a>";
			};
			echo "</span></p>";

			if ($this->ion_auth->user()->row()->skpd_id == 182) {
				echo '<select class="form-control" name="telaah_jabatan_pengikut[]" required>
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
