<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Anggaran extends public_Controller
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
		$this->load->model('setting_admin/m_anggaran');
		$this->load->model('telaah/M_relasi_sekda', 'm_relasi_sekda');
		$this->load->model('laporan/m_rincian');
		$this->load->model('laporan/m_pengeluaran_rill');
		$this->load->model('setting/m_log');
		if (!($this->ion_auth->user()->row()->id)) {
			redirect('login');
		}
	}
	//View All Data
	public function index()
	{
		$staff_sekda = false;
		if ($this->ion_auth->user()->row()->jenis_skpd == 3) {
			$staff_sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
		}
		$config             = array();
		$config["base_url"] = base_url() . "setting_admin/anggaran/index";
		if ($staff_sekda) {
			$config["total_rows"] = $this->m_anggaran->record_count_setda($this->ion_auth->user()->row()->skpd_id, $staff_sekda[0]['bagian_id']);
		} else {
			$config["total_rows"] = $this->m_anggaran->record_count($this->ion_auth->user()->row()->skpd_id);
		}
		$config["per_page"]    = 25;
		$config["uri_segment"] = 4;
		$choice                = $config["total_rows"] / $config["per_page"];
		$config["num_links"]   = 5;

		$config['full_tag_open']   = '<ul class="pagination pagination-sm no-margin pull-right">';
		$config['full_tag_close']  = '</ul>';
		$config['first_link']      = 'First';
		$config['last_link']       = 'Last';
		$config['first_tag_open']  = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['prev_link']       = 'Previous';
		$config['prev_tag_open']   = '<li class="prev">';
		$config['prev_tag_close']  = '</li>';
		$config['next_link']       = 'Next';
		$config['next_tag_open']   = '<li>';
		$config['next_tag_close']  = '</li>';
		$config['last_tag_open']   = '<li>';
		$config['last_tag_close']  = '</li>';
		$config['cur_tag_open']    = '<li class="active"><a href="#">';
		$config['cur_tag_close']   = '</a></li>';
		$config['num_tag_open']    = '<li>';
		$config['num_tag_close']   = '</li>';

		if ($this->uri->segment(4) == "") {
			$this->data['number'] = 0;
		} else {
			$this->data['number'] = $this->uri->segment(4);
		}

		$this->pagination->initialize($config);
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

		$this->data['links']    = $this->pagination->create_links();
		if ($staff_sekda) {
			$this->data['anggaran'] = $this->m_anggaran->data_setda($config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id, $staff_sekda[0]['bagian_id']);
		} else {
			$this->data['anggaran'] = $this->m_anggaran->data($config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
		}
		$this->data['tahun']            = date('Y');
		$this->data['setting_anggaran'] = $this->m_anggaran->setting_anggaran();

		$this->render('setting_admin/anggaran/content');
	}

	//View Data Search
	public function search()
	{

		if ($this->input->post('submit')) {
			$tahun = $this->input->post('tahun');
			$column = $this->input->post('column');
			$query = $this->input->post('data');

			$option = array(
				'user_column' => $column,
				'user_data' => $query,
				'tahun' => $tahun
			);
			$this->session->set_userdata($option);
		} else {
			$tahun = $this->uri->segment(4);
			$query = $this->uri->segment(5);
			$column = $this->uri->segment(6);
		}

		$staff_sekda = false;
		if ($this->ion_auth->user()->row()->jenis_skpd == 3) {
			$staff_sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
		}
		$config = array();
		if ($query) {
			$config["base_url"] = base_url() . "setting_admin/anggaran/search/" . $tahun . "/" . $query . "/" . $column;
		} else {
			$config["base_url"] = base_url() . "setting_admin/anggaran/search/" . $tahun . "/" . $tahun . "/" . $tahun;
		}

		if ($query) {
			if ($staff_sekda) {
				$config["total_rows"] = $this->m_anggaran->record_count_search_setda($column, $query, $this->ion_auth->user()->row()->skpd_id, $staff_sekda[0]['bagian_id'], $tahun);
			} else {
				$config["total_rows"] = $this->m_anggaran->record_count_search($column, $query, $this->ion_auth->user()->row()->skpd_id, $tahun);
			}
		} else {
			if ($staff_sekda) {
				$config["total_rows"] = $this->m_anggaran->record_count_search_setda('', '', $this->ion_auth->user()->row()->skpd_id, $staff_sekda[0]['bagian_id'], $tahun);
			} else {
				$config["total_rows"] = $this->m_anggaran->record_count_search('', '', $this->ion_auth->user()->row()->skpd_id, $tahun);
			}
		}


		$config["per_page"] = 25;
		if ($query) {
			$config["uri_segment"] = 7;
		} else {
			$config["uri_segment"] = 5;
		}
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

		if ($query) {
			if ($this->uri->segment(7) == "") {
				$data['number'] = 0;
			} else {
				$data['number'] = $this->uri->segment(7);
			}

			$this->pagination->initialize($config);
			$page = ($this->uri->segment(7)) ? $this->uri->segment(7) : 0;
		} else {
			if ($this->uri->segment(5) == "") {
				$data['number'] = 0;
			} else {
				$data['number'] = $this->uri->segment(5);
			}

			$this->pagination->initialize($config);
			$page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
		}


		$this->data['links'] = $this->pagination->create_links();

		if ($query) {
			if ($staff_sekda) {
				$this->data['anggaran'] = $this->m_anggaran->data_search_setda($column, $query, $config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id, $staff_sekda[0]['bagian_id'], $tahun);
			} else {
				$this->data['anggaran'] = $this->m_anggaran->data_search($column, $query, $config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id, $tahun);
			}
		} else {
			if ($staff_sekda) {
				$this->data['anggaran'] = $this->m_anggaran->data_search_setda('', '', $config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id, $staff_sekda[0]['bagian_id'], $tahun);
			} else {
				$this->data['anggaran'] = $this->m_anggaran->data_search('', '', $config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id, $tahun);
			}
		}


		$this->data['tahun'] =  $tahun;
		$this->data['setting_anggaran'] = $this->m_anggaran->setting_anggaran();
		$this->render('setting_admin/anggaran/content');
	}

	//View Create Data
	public function create_view()
	{
		$this->data['skpd'] = $this->m_anggaran->skpd();
		$this->render('setting_admin/anggaran/insert');
	}
	//Create Data
	public function create()
	{
		$this->form_validation->set_rules('tahun', 'Tahun', 'required');
		$this->form_validation->set_rules('jenis_anggaran', 'Jenis Anggaran', 'required');
		$this->form_validation->set_rules('nama_program', 'Nama Program', 'required|max_length[255]');
		$this->form_validation->set_rules('nama_kegiatan', 'Nama Kegiatan', 'required|max_length[255]');
		$this->form_validation->set_rules('uraian', 'Uraian', 'required');
		$this->form_validation->set_rules('pagu', 'Satuan Harga', 'required');
		$this->form_validation->set_rules('no_rekening', 'Kode Rekening', 'required');
		$this->form_validation->set_rules('mata_anggaran', 'Mata Anggaran', 'required');

		if ($this->form_validation->run() == FALSE) {
			$this->data['skpd'] = $this->m_anggaran->skpd();
			$this->render('setting_admin/anggaran/insert');
		} else {
			$data['tahun'] = $this->input->post('tahun');
			$data['jenis_anggaran'] = $this->input->post('jenis_anggaran');
			$data['nama_program'] = $this->input->post('nama_program');
			$data['nama_kegiatan'] = $this->input->post('nama_kegiatan');
			$data['uraian'] = $this->input->post('uraian');
			$data['pagu'] = str_replace(".", "", $this->input->post('pagu'));
			$data['no_rekening'] = $this->input->post('no_rekening');
			$data['mata_anggaran'] = $this->input->post('mata_anggaran');
			$data['sisa_pagu'] = str_replace(".", "", $this->input->post('pagu'));
			$data['skpd_id'] = $this->ion_auth->user()->row()->skpd_id;
			$staff_sekda = false;
			if ($this->ion_auth->user()->row()->jenis_skpd == 3) {
				$staff_sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
			}
			if ($staff_sekda) {
				$data['bagian_id'] = $staff_sekda[0]['bagian_id'];
			}

			$this->m_anggaran->create($data);

			$log['kode_log_action'] = "53";
			$log['action'] = "INSERT";
			$log['kode_log_action_table'] = "1";
			$log['action_table'] = "TABLE ANGGARAN";
			$this->m_log->create($log);

			$this->session->set_flashdata('notif', 'Data Anggaran Di Simpan !');
			redirect('setting_admin/anggaran');
		}
	}
	//View Update Data
	public function update_view()
	{
		$id_anggaran = $this->encrypt->decode(base64_decode($this->input->get('id_anggaran')), $this->session->userdata('encrypt_key'));

		$this->data['entry'] =  $this->m_anggaran->get($id_anggaran);
		if (!isset($this->data['entry'][0]) || $this->data['entry'][0] == "") {
			redirect('setting_admin/anggaran');
		} else {
			$this->data['skpd'] = $this->m_anggaran->skpd();
			$this->render('setting_admin/anggaran/update');
		}
	}
	//Update Data
	public function update()
	{
		$this->form_validation->set_rules('tahun', 'Tahun', 'required');
		$this->form_validation->set_rules('jenis_anggaran', 'Jenis Anggaran', 'required');
		$this->form_validation->set_rules('nama_program', 'Program', 'required|max_length[255]');
		$this->form_validation->set_rules('nama_kegiatan', 'Kegiatan', 'required|max_length[255]');
		$this->form_validation->set_rules('uraian', 'Uraian', 'required');
		$this->form_validation->set_rules('pagu', 'Satuan Harga', 'required');
		$this->form_validation->set_rules('no_rekening', 'Kode Rekening', 'required');
		$this->form_validation->set_rules('mata_anggaran', 'Mata Anggaran', 'required');

		if ($this->form_validation->run() == FALSE) {
			$this->data['entry'] =  $this->m_anggaran->get($this->input->post('id_anggaran'));
			if (!isset($this->data['entry'][0]) || $this->data['entry'][0] == "") {
				redirect('setting_admin/anggaran');
			} else {
				$this->data['skpd'] = $this->m_anggaran->skpd();
				$this->render('setting_admin/anggaran/update');
			}
		} else {
			$data['tahun'] = $this->input->post('tahun');
			$data['id_anggaran'] = $this->input->post('id_anggaran');
			$data['jenis_anggaran'] = $this->input->post('jenis_anggaran');
			$data['nama_program'] = $this->input->post('nama_program');
			$data['nama_kegiatan'] = $this->input->post('nama_kegiatan');
			$data['uraian'] = $this->input->post('uraian');
			$data['pagu'] = str_replace(".", "", $this->input->post('pagu'));
			$data['no_rekening'] = $this->input->post('no_rekening');
			$data['mata_anggaran'] = $this->input->post('mata_anggaran');
			$data['sisa_pagu'] = str_replace(".", "", $this->input->post('pagu'));
			$data['skpd_id'] = $this->ion_auth->user()->row()->skpd_id;
			$staff_sekda = false;
			if ($this->ion_auth->user()->row()->jenis_skpd == 3) {
				$staff_sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
			}
			if ($staff_sekda) {
				$data['bagian_id'] = $staff_sekda[0]['bagian_id'];
			}

			$this->m_anggaran->update($data);

			$log['kode_log_action'] = "54";
			$log['action'] = "UPDATE id_anggaran = " . $this->input->post('id_anggaran');
			$log['kode_log_action_table'] = "1";
			$log['action_table'] = "TABLE ANGGARAN";
			$this->m_log->create($log);

			$this->session->set_flashdata('notif', 'Data Anggaran Di Ubah !');
			redirect('setting_admin/anggaran');
		}
	}

	public function detail_anggaran()
	{
		$id_anggaran = $this->encrypt->decode(base64_decode($this->uri->segment(4)), $this->session->userdata('encrypt_key'));
		$config = array();
		// $config['page_query_string'] = TRUE;
		$config["base_url"] = base_url() . "setting_admin/anggaran/detail_anggaran/" . $this->uri->segment(4);
		$config["total_rows"] = count($this->m_anggaran->count_telaah_anggaran($id_anggaran));
		$config["per_page"] = 100;
		$config["uri_segment"] = 5;
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

		if ($this->uri->segment(5) == "") {
			$this->data['number'] = 0;
		} else {
			$this->data['number'] = $this->uri->segment(5);
		}

		$this->pagination->initialize($config);
		$page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;

		$this->data['telaah'] = $this->m_anggaran->get_telaah_anggaran($id_anggaran, $config["per_page"], $page);
		$this->data['links'] = $this->pagination->create_links();
		$this->render('setting_admin/anggaran/detail_anggaran');
	}

	//Delete Data
	public function delete()
	{
		$id_anggaran = $this->encrypt->decode(base64_decode($this->input->get('id_anggaran')), $this->session->userdata('encrypt_key'));

		$this->m_anggaran->delete($id_anggaran);

		$log['kode_log_action'] = "56";
		$log['action'] = "DELETE id_anggaran = " . $id_anggaran;
		$log['kode_log_action_table'] = "1";
		$log['action_table'] = "TABLE ANGGARAN";
		$this->m_log->create($log);

		$this->session->set_flashdata('notif', 'Data Anggaran Di Hapus !');
		redirect('setting_admin/anggaran');
	}
}
