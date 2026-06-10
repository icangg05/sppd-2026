<?php
defined('BASEPATH') or exit('No direct script access allowed');
class List_telaah extends public_Controller
{
	function dd($data)
	{
		echo "<pre>";
		print_r($data);
		echo "</pre>";
		exit;
	}

	function __construct()
	{
		parent::__construct();
		// error_reporting(0);
		// Increase memory limit for this controller due to large dataset loading
		ini_set('memory_limit', '2048M');
		$this->load->model('setting_root/m_admin');
		$this->load->model('setting_root/m_provinsi');
		$this->load->model('setting_root/m_kabupaten');
		$this->load->model('setting_root/m_skpd');
		$this->load->model('setting_admin/m_anggota');
		$this->load->model('setting_admin/m_pegawai');
		$this->load->model('setting_admin/m_anggaran');
		$this->load->model('setting_admin/m_rekening');
		$this->load->model('telaah/m_kadis');
		$this->load->model('telaah/m_esselon');
		$this->load->model('telaah/m_dprd');
		$this->load->model('telaah/m_sekda');
		$this->load->model('telaah/m_staff_dprd');
		$this->load->model('telaah/m_sekwan');
		$this->load->model('telaah/m_camat');
		$this->load->model('telaah/m_lurah');
		$this->load->model('telaah/m_staff_camat');
		$this->load->model('telaah/m_staff_lurah');
		$this->load->model('telaah/m_kapus');
		$this->load->model('telaah/m_sekwan');
		$this->load->model('telaah/m_pengikut');
		$this->load->model('telaah/m_lokasi_tujuan');
		$this->load->model('telaah/m_timeline');
		$this->load->model('telaah/m_telaah');
		$this->load->model('telaah/m_relasi_sekda');
		$this->load->model('laporan/m_spd');
		$this->load->model('laporan/m_laporan');
		$this->load->model('setting/m_log');
		$this->load->model('m_widget');

		//cek login
		if (!($this->ion_auth->user()->row()->id)) {
			redirect('login');
		}
	}

	### Tampilan Semua Data Permohonan SPPD Administrator
	public function index_admin()
	{

		$config = array();
		$config["base_url"] = base_url() . "telaah/list_telaah/index_admin/" . $this->uri->segment(4);

		switch ($this->uri->segment(4)) {
			case "esselon":
				$config["total_rows"] = $this->m_admin->record_count_esselon();
				break;
			case "kadis":
				$config["total_rows"] = $this->m_admin->record_count_kadis();
				break;
			case "dprd":
				$config["total_rows"] = $this->m_admin->record_count_dprd();
				break;
			case "sekda":
				$config["total_rows"] = $this->m_admin->record_count_sekda();
				break;
			case "camat":
				$config["total_rows"] = $this->m_admin->record_count_camat();
				break;
			case "lurah":
				$config["total_rows"] = $this->m_admin->record_count_lurah();
				break;
			case "staff_dprd":
				$config["total_rows"] = $this->m_admin->record_count_staffdprd();
				break;
			case "staff_camat":
				$config["total_rows"] = $this->m_admin->record_count_staffcamat();
				break;
			case "staff_lurah":
				$config["total_rows"] = $this->m_admin->record_count_stafflurah();
				break;
			case "walikota":
				$config["total_rows"] = $this->m_admin->record_countwalikota();
				break;
			case "staff_setda":
				$config["total_rows"] = $this->m_admin->record_count_staffsekda();
				break;
			case "sekwan":
				$config["total_rows"] = $this->m_admin->record_count_sekwan();
				break;
			case "kapus":
				$config["total_rows"] = $this->m_admin->record_count_kapus();
				break;
		}

		$config["per_page"] = 25;
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
		$this->data['links'] = $this->pagination->create_links();

		switch ($this->uri->segment(4)) {
			case "esselon":
				$this->data['data'] = $this->m_admin->data_esselon($config["per_page"], $page);
				break;
			case "kadis":
				$this->data['data'] = $this->m_admin->data_kadis($config["per_page"], $page);
				break;
			case "dprd":
				$this->data['data'] = $this->m_admin->data_dprd($config["per_page"], $page);
				break;
			case "sekda":
				$this->data['data'] = $this->m_admin->data_sekda($config["per_page"], $page);
				break;
			case "camat":
				$this->data['data'] = $this->m_admin->data_camat($config["per_page"], $page);
				break;
			case "lurah":
				$this->data['data'] = $this->m_admin->data_lurah($config["per_page"], $page);
				break;
			case "staff_dprd":
				$this->data['data'] = $this->m_admin->data_staffdprd($config["per_page"], $page);
				break;
			case "staff_camat":
				$this->data['data'] = $this->m_admin->data_staffcamat($config["per_page"], $page);
				break;
			case "staff_lurah":
				$this->data['data'] = $this->m_admin->data_stafflurah($config["per_page"], $page);
				break;
			case "walikota":
				$this->data['data'] = $this->m_admin->datawalikota2($config["per_page"], $page);
				break;
			case "staff_setda":
				$this->data['data'] = $this->m_admin->data_staffsekda($config["per_page"], $page);
				break;
			case "sekwan":
				$this->data['data'] = $this->m_admin->data_sekwan($config["per_page"], $page);
				break;
			case "kapus":
				$this->data['data'] = $this->m_admin->data_kapus($config["per_page"], $page);
				break;
		}
		$this->render('telaah/content');
	}

	### Tampilan Semua Data Search Permohonan SPPD Administrator
	public function search_admin()
	{
		if ($this->input->post('submit')) {
			$column = 'pegawai_nama';
			$query = $this->input->post('data');
			$option = array(
				'user_column' => $column,
				'user_data' => $query
			);
			$this->session->set_userdata($option);
		} else {
			$query = $this->uri->segment(5);
			$column = $this->uri->segment(6);
		}

		$config = array();
		$config["base_url"] = base_url() . "telaah/list_telaah/search_admin/" . $this->uri->segment(4) . "/" . $query . "/" . $column;

		switch ($this->uri->segment(4)) {
			case "esselon":
				$config["total_rows"] = $this->m_admin->record_count_search_esselon($column, $query);
				break;
			case "kadis":
				$config["total_rows"] = $this->m_admin->record_count_search_kadis($column, $query);
				break;
			case "dprd":
				$config["total_rows"] = $this->m_admin->record_count_search_dprd($column, $query);
				break;
			case "sekda":
				$config["total_rows"] = $this->m_admin->record_count_search_sekda($column, $query);
				break;
			case "camat":
				$config["total_rows"] = $this->m_admin->record_count_search_camat($column, $query);
				break;
			case "lurah":
				$config["total_rows"] = $this->m_admin->record_count_search_lurah($column, $query);
				break;
			case "staff_dprd":
				$config["total_rows"] = $this->m_admin->record_count_search_staffdprd($column, $query);
				break;
			case "staff_camat":
				$config["total_rows"] = $this->m_admin->record_count_search_staffcamat($column, $query);
				break;
			case "staff_lurah":
				$config["total_rows"] = $this->m_admin->record_count_search_stafflurah($column, $query);
				break;
			case "walikota":
				$config["total_rows"] = $this->m_admin->record_count_search_walikota($column, $query);
				break;
			case "staff_setda":
				$config["total_rows"] = $this->m_admin->record_count_search_staffsekda($column, $query);
				break;
			case "sekwan":
				$config["total_rows"] = $this->m_admin->record_count_search_sekwan($column, $query);
				break;
			case "kapus":
				$config["total_rows"] = $this->m_admin->record_count_search_kapus($column, $query);
				break;
		}

		$config["per_page"] = 25;
		$config["uri_segment"] = 7;
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

		if ($this->uri->segment(7) == "") {
			$this->data['number'] = 0;
		} else {
			$this->data['number'] = $this->uri->segment(7);
		}

		$this->pagination->initialize($config);
		$page = ($this->uri->segment(7)) ? $this->uri->segment(7) : 0;

		$this->data['links'] = $this->pagination->create_links();

		switch ($this->uri->segment(4)) {
			case "esselon":
				$this->data['data'] = $this->m_admin->data_search_esselon($column, $query, $config["per_page"], $page);
				break;
			case "kadis":
				$this->data['data'] = $this->m_admin->data_search_kadis($column, $query, $config["per_page"], $page);
				break;
			case "dprd":
				$this->data['data'] = $this->m_admin->data_search_dprd($column, $query, $config["per_page"], $page);
				break;
			case "sekda":
				$this->data['data'] = $this->m_admin->data_search_sekda($column, $query, $config["per_page"], $page);
				break;
			case "camat":
				$this->data['data'] = $this->m_admin->data_search_camat($column, $query, $config["per_page"], $page);
				break;
			case "lurah":
				$this->data['data'] = $this->m_admin->data_search_lurah($column, $query, $config["per_page"], $page);
				break;
			case "staff_dprd":
				$this->data['data'] = $this->m_admin->data_search_staffdprd($column, $query, $config["per_page"], $page);
				break;
			case "staff_camat":
				$this->data['data'] = $this->m_admin->data_search_staffcamat($column, $query, $config["per_page"], $page);
				break;
			case "staff_lurah":
				$this->data['data'] = $this->m_admin->data_search_stafflurah($column, $query, $config["per_page"], $page);
				$break;
			case "walikota":
				$this->data['data'] = $this->m_admin->data_search_walikota($column, $query, $config["per_page"], $page);
				break;
			case "staff_setda":
				$this->data['data'] = $this->m_admin->data_search_staffsekda($column, $query, $config["per_page"], $page);
				break;
			case "sekwan":
				$this->data['data'] = $this->m_admin->data_search_sekwan($column, $query, $config["per_page"], $page);
				break;
			case "kapus":
				$this->data['data'] = $this->m_admin->data_search_kapus($column, $query, $config["per_page"], $page);
				break;
		}
		$this->render('telaah/content');
	}

	### Tampilan Semua Data Result Permohonan SPPD Full Administrator
	public function result_admin()
	{
		$column = 'table_telaah.telaah_status';
		$query = $this->uri->segment(5);

		$config = array();
		$config["base_url"] = base_url() . "telaah/list_telaah/result_admin/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "";

		switch ($this->uri->segment(4)) {
			case "esselon":
				$config["total_rows"] = $this->m_admin->record_count_search_esselon($column, $query);
				break;
			case "kadis":
				$config["total_rows"] = $this->m_admin->record_count_search_kadis($column, $query);
				break;
			case "dprd":
				$config["total_rows"] = $this->m_admin->record_count_search_dprd($column, $query);
				break;
			case "sekda":
				$config["total_rows"] = $this->m_admin->record_count_search_sekda($column, $query);
				break;
			case "camat":
				$config["total_rows"] = $this->m_admin->record_count_search_camat($column, $query);
				break;
			case "lurah":
				$config["total_rows"] = $this->m_admin->record_count_search_lurah($column, $query);
				break;
			case "staff_dprd":
				$config["total_rows"] = $this->m_admin->record_count_search_staffdprd($column, $query);
				break;
			case "staff_camat":
				$config["total_rows"] = $this->m_admin->record_count_search_staffcamat($column, $query);
				break;
			case "staff_lurah":
				$config["total_rows"] = $this->m_admin->record_count_search_stafflurah($column, $query);
				break;
			case "walikota":
				$config["total_rows"] = $this->m_admin->record_count_search_walikota($column, $query);
				break;
			case "staff_setda":
				$config["total_rows"] = $this->m_admin->record_count_search_staffsekda($column, $query);
				break;
			case "sekwan":
				$config["total_rows"] = $this->m_admin->record_count_search_sekwan($column, $query);
				break;
			case "kapus":
				$config["total_rows"] = $this->m_admin->record_count_search_kapus($column, $query);
				break;
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

		switch ($this->uri->segment(4)) {
			case "esselon":
				$this->data['data'] = $this->m_admin->data_search_esselon($column, $query, $config["per_page"], $page);
				break;
			case "kadis":
				$this->data['data'] = $this->m_admin->data_search_kadis($column, $query, $config["per_page"], $page);
				break;
			case "dprd":
				$this->data['data'] = $this->m_admin->data_search_dprd($column, $query, $config["per_page"], $page);
				break;
			case "sekda":
				$this->data['data'] = $this->m_admin->data_search_sekda($column, $query, $config["per_page"], $page);
				break;
			case "camat":
				$this->data['data'] = $this->m_admin->data_search_camat($column, $query, $config["per_page"], $page);
				break;
			case "lurah":
				$this->data['data'] = $this->m_admin->data_search_lurah($column, $query, $config["per_page"], $page);
				break;
			case "staff_dprd":
				$this->data['data'] = $this->m_admin->data_search_staffdprd($column, $query, $config["per_page"], $page);
				break;
			case "staff_camat":
				$this->data['data'] = $this->m_admin->data_search_staffcamat($column, $query, $config["per_page"], $page);
				break;
			case "staff_lurah":
				$this->data['data'] = $this->m_admin->data_search_stafflurah($column, $query, $config["per_page"], $page);
				break;
			case "walikota":
				$this->data['data'] = $this->m_admin->data_search_walikota($column, $query, $config["per_page"], $page);
				break;
			case "staff_setda":
				$this->data['data'] = $this->m_admin->data_search_staffsekda($column, $query, $config["per_page"], $page);
				break;
			case "sekwan":
				$this->data['data'] = $this->m_admin->data_search_sekwan($column, $query, $config["per_page"], $page);
				break;
			case "kapus":
				$this->data['data'] = $this->m_admin->data_search_kapus($column, $query, $config["per_page"], $page);
				break;
		}
		$this->render('telaah/content');
	}

	############################################################################################################

	//Tampilan All Data Permohonan SPPD
	public function index()
	{
		$config = array();
		$config["base_url"] = base_url() . "telaah/list_telaah/index/" . $this->uri->segment(4);

		switch ($this->uri->segment(4)) {
			case "esselon":
				$config["total_rows"] = $this->m_esselon->record_count($this->ion_auth->user()->row()->skpd_id);
				break;
			case "puskesmas":
				$config["total_rows"] = $this->m_esselon->data3('', '');
				break;
			case "kadis":
				$config["total_rows"] = $this->m_kadis->record_count($this->ion_auth->user()->row()->skpd_id);
				break;
			case "dprd":
				$config["total_rows"] = $this->m_dprd->record_count();
				break;
			case "sekda":
				$config["total_rows"] = $this->m_sekda->record_count();
				break;
			case "camat":
				$config["total_rows"] = $this->m_camat->record_count($this->ion_auth->user()->row()->skpd_id);
				break;
			case "lurah":
				$config["total_rows"] = $this->m_lurah->record_count($this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_dprd":
				$config["total_rows"] = $this->m_staff_dprd->record_count();
				break;
			case "staff_camat":
				$config["total_rows"] = $this->m_staff_camat->record_count($this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_lurah":
				$config["total_rows"] = $this->m_staff_lurah->record_count($this->ion_auth->user()->row()->skpd_id);
				break;
			case "walikota":
				$config["total_rows"] = $this->m_sekda->record_countwalikota();
				break;
			case "staff_setda":
				$staff_sekda = $this->m_relasi_sekda->getsubbagian($this->ion_auth->user()->row()->id);
				$config["total_rows"] = $this->m_sekda->record_count_staffsekda_bag($staff_sekda[0]['bagian_id']);
				break;
			case "sekwan":
				$config["total_rows"] = $this->m_sekwan->record_count($this->ion_auth->user()->row()->skpd_id);
				break;
			case "kapus":
				$config["total_rows"] = $this->m_kapus->record_count($this->ion_auth->user()->row()->skpd_id);
				break;
		}

		$config["per_page"] = 25;
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
		$this->data['links'] = $this->pagination->create_links();

		switch ($this->uri->segment(4)) {
			case "esselon":
				$this->data['data'] = $this->m_esselon->data($config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "puskesmas":
				$this->data['data'] = $this->m_esselon->data3($config["per_page"], $page);
				break;
			case "kadis":
				$this->data['data'] = $this->m_kadis->data($config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "dprd":
				$this->data['data'] = $this->m_dprd->data($config["per_page"], $page);
				break;
			case "sekda":
				$this->data['data'] = $this->m_sekda->data($config["per_page"], $page);
				break;
			case "camat":
				$this->data['data'] = $this->m_camat->data($config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "lurah":
				$this->data['data'] = $this->m_lurah->data($config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_dprd":
				$this->data['data'] = $this->m_staff_dprd->data($config["per_page"], $page);
				break;
			case "staff_camat":
				$this->data['data'] = $this->m_staff_camat->data($config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_lurah":
				$this->data['data'] = $this->m_staff_lurah->data($config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "walikota":
				$this->data['data'] = $this->m_sekda->datawalikota($config["per_page"], $page);
				break;
			case "staff_setda":
				if ($this->ion_auth->get_users_groups()->row()->id == 9) {
					$staff_sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
					if ($staff_sekda) {
						$this->data['data'] = $this->m_sekda->data_staffsekda_bag($config["per_page"], $page, $staff_sekda[0]['bagian_id']);
					} else {
						$this->data['data'] = $this->m_sekda->data_staffsekda($config["per_page"], $page);
					}
				} else {
					$staff_sekda = $this->m_relasi_sekda->getsubbagian($this->ion_auth->user()->row()->id);
					$this->data['data'] = $this->m_sekda->datakasubagstaf($config["per_page"], $page, $staff_sekda[0]['subbagian_id']);
				}

				break;
			case "sekwan":
				$this->data['data'] = $this->m_sekwan->data($config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "kapus":
				$this->data['data'] = $this->m_kapus->data($config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;
		}

		$this->render('telaah/list_telaah/content');
	}

	//View Data Search
	public function search()
	{
		if ($this->input->post('submit')) {
			if ($this->uri->segment(4) == 'dprd') {
				$column = 'anggotadprd_name';
			} else {
				$column = 'pegawai_nama';
			}
			$query = $this->input->post('data');
			$option = array(
				'user_column' => $column,
				'user_data' => $query
			);
			$this->session->set_userdata($option);
		} else {
			$query = str_replace("%20", " ", $this->uri->segment(5));
			$column = $this->uri->segment(6);
		}

		$config = array();
		$config["base_url"] = base_url() . "telaah/list_telaah/search/" . $this->uri->segment(4) . "/" . $query . "/" . $column;

		switch ($this->uri->segment(4)) {
			case "esselon":
				$config["total_rows"] = $this->m_esselon->record_count_search($column, $query, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "puskesmas":
				$config["total_rows"] = $this->m_esselon->data_search3($column, $query, '', '');
				break;
			case "kadis":
				$config["total_rows"] = $this->m_kadis->record_count_search($column, $query, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "dprd":
				$config["total_rows"] = $this->m_dprd->record_count_search($column, $query);
				break;
			case "sekda":
				$config["total_rows"] = $this->m_sekda->record_count_search($column, $query);
				break;
			case "camat":
				$config["total_rows"] = $this->m_camat->record_count_search($column, $query, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "lurah":
				$config["total_rows"] = $this->m_lurah->record_count_search($column, $query, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_dprd":
				$config["total_rows"] = $this->m_staff_dprd->record_count_search($column, $query);
				break;
			case "staff_camat":
				$config["total_rows"] = $this->m_staff_camat->record_count_search($column, $query, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_lurah":
				$config["total_rows"] = $this->m_staff_lurah->record_count_search($column, $query, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "walikota":
				$config["total_rows"] = $this->m_sekda->record_count_search_walikota($column, $query);
				break;
			case "staff_setda":
				$staff_sekda = $this->m_relasi_sekda->getsubbagian($this->ion_auth->user()->row()->id);
				if ($staff_sekda) {
					$config["total_rows"] = $this->m_sekda->record_count_search_staffsekda_bag($column, $query, $staff_sekda[0]['bagian_id']);
				} else {
					$config["total_rows"] = $this->m_sekda->record_count_search_staffsekda($column, $query);
				}
				break;
			case "sekwan":
				$config["total_rows"] = $this->m_sekwan->record_count_search($column, $query, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "kapus":
				$config["total_rows"] = $this->m_kapus->record_count_search($column, $query, $this->ion_auth->user()->row()->skpd_id);
				break;
		}

		$config["per_page"] = 25;
		$config["uri_segment"] = 7;
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

		if ($this->uri->segment(7) == "") {
			$this->data['number'] = 0;
		} else {
			$this->data['number'] = $this->uri->segment(7);
		}

		$this->pagination->initialize($config);
		$page = ($this->uri->segment(7)) ? $this->uri->segment(7) : 0;

		$this->data['links'] = $this->pagination->create_links();

		switch ($this->uri->segment(4)) {
			case "esselon":
				$this->data['data'] = $this->m_esselon->data_search($column, $query, $config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "puskesmas":
				$this->data['data'] = $this->m_esselon->data_search3($column, $query, $config["per_page"], $page);
				break;
			case "kadis":
				$this->data['data'] = $this->m_kadis->data_search($column, $query, $config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "dprd":
				$this->data['data'] = $this->m_dprd->data_search($column, $query, $config["per_page"], $page);
				break;
			case "sekda":
				$this->data['data'] = $this->m_sekda->data_search($column, $query, $config["per_page"], $page);
				break;
			case "camat":
				$this->data['data'] = $this->m_camat->data_search($column, $query, $config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "lurah":
				$this->data['data'] = $this->m_lurah->data_search($column, $query, $config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_dprd":
				$this->data['data'] = $this->m_staff_dprd->data_search($column, $query, $config["per_page"], $page);
				break;
			case "staff_camat":
				$this->data['data'] = $this->m_staff_camat->data_search($column, $query, $config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_lurah":
				$this->data['data'] = $this->m_staff_lurah->data_search($column, $query, $config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "walikota":
				$this->data['data'] = $this->m_sekda->data_search_walikota($column, $query, $config["per_page"], $page);
				break;
			case "staff_setda":
				$staff_sekda = $this->m_relasi_sekda->getsubbagian($this->ion_auth->user()->row()->id);
				if ($staff_sekda) {
					$this->data['data'] = $this->m_sekda->data_search_staffsekda_bag($column, $query, $config["per_page"], $page, $staff_sekda[0]['bagian_id']);
				} else {
					$this->data['data'] = $this->m_sekda->data_search_staffsekda($column, $query, $config["per_page"], $page);
				}
				break;
			case "sekwan":
				$this->data['data'] = $this->m_sekwan->data_search($column, $query, $config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "kapus":
				$this->data['data'] = $this->m_kapus->data_search($column, $query, $config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;
		}

		$this->render('telaah/list_telaah/content');
	}

	//View Data Result
	public function result()
	{

		$column = 'table_telaah.telaah_status';
		$query = $this->uri->segment(5);

		$config = array();
		$config["base_url"] = base_url() . "telaah/list_telaah/result/" . $this->uri->segment(4) . "/" . $this->uri->segment(5) . "";

		switch ($this->uri->segment(4)) {
			case "esselon":
				$config["total_rows"] = $this->m_esselon->record_count_search($column, $query, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "puskesmas":
				$config["total_rows"] = $this->m_esselon->data_search3($column, $query, '', '');
				break;
			case "kadis":
				$config["total_rows"] = $this->m_kadis->record_count_search($column, $query, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "dprd":
				$config["total_rows"] = $this->m_dprd->record_count_search($column, $query);
				break;
			case "sekda":
				$config["total_rows"] = $this->m_sekda->record_count_search($column, $query);
				break;
			case "camat":
				$config["total_rows"] = $this->m_camat->record_count_search($column, $query, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "lurah":
				$config["total_rows"] = $this->m_lurah->record_count_search($column, $query, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_dprd":
				$config["total_rows"] = $this->m_staff_dprd->record_count_search($column, $query);
				break;
			case "staff_camat":
				$config["total_rows"] = $this->m_staff_camat->record_count_search($column, $query, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_lurah":
				$config["total_rows"] = $this->m_staff_lurah->record_count_search($column, $query, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "walikota":
				$config["total_rows"] = $this->m_sekda->record_count_search_walikota($column, $query);
				break;
			case "staff_setda":
				$staff_sekda = $this->m_relasi_sekda->getsubbagian($this->ion_auth->user()->row()->id);
				if ($staff_sekda) {
					$config["total_rows"] = $this->m_sekda->record_count_search_staffsekda_bag($column, $query, $staff_sekda[0]['bagian_id']);
				} else {
					$config["total_rows"] = $this->m_sekda->record_count_search_staffsekda($column, $query);
				}
				break;
			case "sekwan":
				$config["total_rows"] = $this->m_sekwan->record_count_search($column, $query, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "kapus":
				$config["total_rows"] = $this->m_kapus->record_count_search($column, $query, $this->ion_auth->user()->row()->skpd_id);
				break;
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

		switch ($this->uri->segment(4)) {
			case "esselon":
				$this->data['data'] = $this->m_esselon->data_search($column, $query, $config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "puskesmas":
				$this->data['data'] = $this->m_esselon->data_search3($column, $query, $config["per_page"], $page);
				break;
			case "kadis":
				$this->data['data'] = $this->m_kadis->data_search($column, $query, $config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "dprd":
				$this->data['data'] = $this->m_dprd->data_search($column, $query, $config["per_page"], $page);
				break;

			case "sekda":
				$this->data['data'] = $this->m_sekda->data_search($column, $query, $config["per_page"], $page);
				break;

			case "camat":
				$this->data['data'] = $this->m_camat->data_search($column, $query, $config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "lurah":
				$this->data['data'] = $this->m_lurah->data_search($column, $query, $config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "staff_dprd":
				$this->data['data'] = $this->m_staff_dprd->data_search($column, $query, $config["per_page"], $page);
				break;

			case "staff_camat":
				$this->data['data'] = $this->m_staff_camat->data_search($column, $query, $config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_lurah":
				$this->data['data'] = $this->m_staff_lurah->data_search($column, $query, $config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "walikota":
				$this->data['data'] = $this->m_sekda->data_search_walikota($column, $query, $config["per_page"], $page);
				break;
			case "staff_setda":
				$staff_sekda = $this->m_relasi_sekda->getsubbagian($this->ion_auth->user()->row()->id);
				if ($staff_sekda) {
					$this->data['data'] = $this->m_sekda->data_search_staffsekda_bag($column, $query, $config["per_page"], $page, $staff_sekda[0]['bagian_id']);
				} else {
					$this->data['data'] = $this->m_sekda->data_search_staffsekda($column, $query, $config["per_page"], $page);
				}
				break;
			case "sekwan":
				$this->data['data'] = $this->m_sekwan->data_search($column, $query, $config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "kapus":
				$this->data['data'] = $this->m_kapus->data_search($column, $query, $config["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
				break;
		}

		$this->render('telaah/list_telaah/content');
	}

	public function data_perihal_dprd()
	{
		$this->render('telaah/cari_perihal_dprd');
	}

	public function search_perihal_dprd()
	{
		if ($this->input->post('submit')) {
			$column = 'telaah_perihal';
			$query = $this->input->post('data');
			$this->data['keyword'] = $this->input->post('data');
			$option = array(
				'user_column' => $column,
				'user_data' => $query
			);
			$this->session->set_userdata($option);
		} else {
			$query = str_ireplace('%20', ' ', $this->uri->segment(4));
			$column = $this->uri->segment(5);
			$this->data['keyword'] = str_ireplace('%20', ' ', $this->uri->segment(4));
		}

		$config = array();
		$config["base_url"] = base_url() . "telaah/list_telaah/search_perihal_dprd/" . $query . "/" . $column;
		$config["total_rows"] = $this->m_dprd->data_search2($column, $query, '', '');
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
		$this->data['dprd'] = $this->m_dprd->data_search2($column, $query, $config["per_page"], $page);
		$this->render('telaah/cari_perihal_dprd');
	}

	public function data_perihal_staff_dprd()
	{
		$this->render('telaah/cari_perihal_staff_dprd');
	}

	public function search_perihal_staff_dprd()
	{
		if ($this->input->post('submit')) {
			$column = 'telaah_perihal';
			$query = $this->input->post('data');
			$this->data['keyword'] = $this->input->post('data');
			$option = array(
				'user_column' => $column,
				'user_data' => $query
			);
			$this->session->set_userdata($option);
		} else {
			$query = str_ireplace('%20', ' ', $this->uri->segment(4));
			$column = $this->uri->segment(5);
			$this->data['keyword'] = str_ireplace('%20', ' ', $this->uri->segment(4));
		}

		$config = array();
		$config["base_url"] = base_url() . "telaah/list_telaah/search_perihal_staff_dprd/" . $query . "/" . $column;
		$config["total_rows"] = $this->m_staff_dprd->data_search2($column, $query, '', '');
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
		$this->data['staff_dprd'] = $this->m_staff_dprd->data_search2($column, $query, $config["per_page"], $page);
		$this->render('telaah/cari_perihal_staff_dprd');
	}

	//View Data Result
	public function sudah_upload_laporan()
	{
		$config = array();
		$config["base_url"] = base_url() . "telaah/list_telaah/sudah_upload_laporan/" . $this->uri->segment(4);

		switch ($this->uri->segment(4)) {
			case "esselon":
				$config["total_rows"] = $this->m_esselon->sudah_upload_laporan('', '', 1, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "puskesmas":
				$config["total_rows"] = $this->m_esselon->sudah_upload_laporan('', '', 1, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "kadis":
				$config["total_rows"] = $this->m_esselon->sudah_upload_laporan('', '', 2, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "dprd":
				$config["total_rows"] = $this->m_esselon->sudah_upload_laporan('', '', 3, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "sekda":
				$config["total_rows"] = $this->m_esselon->sudah_upload_laporan('', '', 4, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "camat":
				$config["total_rows"] = $this->m_esselon->sudah_upload_laporan('', '', 5, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "lurah":
				$config["total_rows"] = $this->m_esselon->sudah_upload_laporan('', '', 5, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_dprd":
				$config["total_rows"] = $this->m_esselon->sudah_upload_laporan('', '', 6, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_camat":
				$config["total_rows"] = $this->m_esselon->sudah_upload_laporan('', '', 7, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_lurah":
				$config["total_rows"] = $this->m_esselon->sudah_upload_laporan('', '', 7, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "walikota":
				$config["total_rows"] = $this->m_esselon->sudah_upload_laporan('', '', 8, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_setda":
				$staff_sekda = $this->m_relasi_sekda->getsubbagian($this->ion_auth->user()->row()->id);
				if ($staff_sekda) {
					$config["total_rows"] = $this->m_esselon->sudah_upload_laporan_staffsekda_bag('', '', 9, $staff_sekda[0]['bagian_id']);
				} else {
					$config["total_rows"] = $this->m_esselon->sudah_upload_laporan_staffsekda('', '', 9);
				}
				break;
			case "sekwan":
				$config["total_rows"] = $this->m_esselon->sudah_upload_laporan('', '', 10, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "kapus":
				$config["total_rows"] = $this->m_esselon->sudah_upload_laporan('', '', 11, $this->ion_auth->user()->row()->skpd_id);
				break;
		}

		$config["per_page"] = 25;
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

		$this->data['links'] = $this->pagination->create_links();

		switch ($this->uri->segment(4)) {
			case "esselon":
				$this->data['data'] = $this->m_esselon->sudah_upload_laporan($config["per_page"], $page, 1, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "puskesmas":
				$this->data['data'] = $this->m_esselon->sudah_upload_laporan($config["per_page"], $page, 1, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "kadis":
				$this->data['data'] = $this->m_esselon->sudah_upload_laporan($config["per_page"], $page, 2, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "dprd":
				$this->data['data'] = $this->m_esselon->sudah_upload_laporan($config["per_page"], $page, 3, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "sekda":
				$this->data['data'] = $this->m_esselon->sudah_upload_laporan($config["per_page"], $page, 4, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "camat":
				$this->data['data'] = $this->m_esselon->sudah_upload_laporan($config["per_page"], $page, 5, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "lurah":
				$this->data['data'] = $this->m_esselon->sudah_upload_laporan($config["per_page"], $page, 5, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "staff_dprd":
				$this->data['data'] = $this->m_esselon->sudah_upload_laporan($config["per_page"], $page, 6, $this->ion_auth->user()->row()->skpd_id);
				break;
				break;

			case "staff_camat":
				$this->data['data'] = $this->m_esselon->sudah_upload_laporan($config["per_page"], $page, 7, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_lurah":
				$this->data['data'] = $this->m_esselon->sudah_upload_laporan($config["per_page"], $page, 7, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "walikota":
				$this->data['data'] = $this->m_esselon->sudah_upload_laporan($config["per_page"], $page, 8, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_setda":
				$staff_sekda = $this->m_relasi_sekda->getsubbagian($this->ion_auth->user()->row()->id);
				if ($staff_sekda) {
					$this->data['data'] = $this->m_esselon->sudah_upload_laporan_staffsekda_bag($config["per_page"], $page, 9, $staff_sekda[0]['bagian_id']);
				} else {
					$this->data['data'] = $this->m_esselon->sudah_upload_laporan_staffsekda($config["per_page"], $page, 9);
				}
				break;
			case "sekwan":
				$this->data['data'] = $this->m_esselon->sudah_upload_laporan($config["per_page"], $page, 10, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "kapus":
				$this->data['data'] = $this->m_esselon->sudah_upload_laporan($config["per_page"], $page, 11, $this->ion_auth->user()->row()->skpd_id);
				break;
		}

		$this->render('telaah/list_telaah/content');
	}

	//View Data Result
	public function belum_upload_laporan()
	{
		$config = array();
		$config["base_url"] = base_url() . "telaah/list_telaah/belum_upload_laporan/" . $this->uri->segment(4);

		switch ($this->uri->segment(4)) {
			case "esselon":
				$config["total_rows"] = $this->m_esselon->belum_upload_laporan('', '', 1, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "puskesmas":
				$config["total_rows"] = $this->m_esselon->belum_upload_laporan('', '', 1, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "kadis":
				$config["total_rows"] = $this->m_esselon->belum_upload_laporan('', '', 2, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "dprd":
				$config["total_rows"] = $this->m_esselon->belum_upload_laporan('', '', 3, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "sekda":
				$config["total_rows"] = $this->m_esselon->belum_upload_laporan('', '', 4, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "camat":
				$config["total_rows"] = $this->m_esselon->belum_upload_laporan('', '', 5, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "lurah":
				$config["total_rows"] = $this->m_esselon->belum_upload_laporan('', '', 5, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_dprd":
				$config["total_rows"] = $this->m_esselon->belum_upload_laporan('', '', 6, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_camat":
				$config["total_rows"] = $this->m_esselon->belum_upload_laporan('', '', 7, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_lurah":
				$config["total_rows"] = $this->m_esselon->belum_upload_laporan('', '', 7, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "walikota":
				$config["total_rows"] = $this->m_esselon->belum_upload_laporan('', '', 8, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_setda":
				$staff_sekda = $this->m_relasi_sekda->getsubbagian($this->ion_auth->user()->row()->id);
				if ($staff_sekda) {
					$config["total_rows"] = $this->m_esselon->belum_upload_laporan_staffsekda_bag('', '', 9, $staff_sekda[0]['bagian_id']);
				} else {
					$config["total_rows"] = $this->m_esselon->belum_upload_laporan_staffsekda('', '', 9);
				}
				break;
			case "sekwan":
				$config["total_rows"] = $this->m_esselon->belum_upload_laporan('', '', 10, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "kapus":
				$config["total_rows"] = $this->m_esselon->belum_upload_laporan('', '', 11, $this->ion_auth->user()->row()->skpd_id);
				break;
		}

		$config["per_page"] = 25;
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

		$this->data['links'] = $this->pagination->create_links();

		switch ($this->uri->segment(4)) {
			case "esselon":
				$this->data['data'] = $this->m_esselon->belum_upload_laporan($config["per_page"], $page, 1, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "puskesmas":
				$this->data['data'] = $this->m_esselon->belum_upload_laporan($config["per_page"], $page, 1, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "kadis":
				$this->data['data'] = $this->m_esselon->belum_upload_laporan($config["per_page"], $page, 2, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "dprd":
				$this->data['data'] = $this->m_esselon->belum_upload_laporan($config["per_page"], $page, 3, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "sekda":
				$this->data['data'] = $this->m_esselon->belum_upload_laporan($config["per_page"], $page, 4, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "camat":
				$this->data['data'] = $this->m_esselon->belum_upload_laporan($config["per_page"], $page, 5, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "lurah":
				$this->data['data'] = $this->m_esselon->belum_upload_laporan($config["per_page"], $page, 5, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "staff_dprd":
				$this->data['data'] = $this->m_esselon->belum_upload_laporan($config["per_page"], $page, 6, $this->ion_auth->user()->row()->skpd_id);
				break;
				break;

			case "staff_camat":
				$this->data['data'] = $this->m_esselon->belum_upload_laporan($config["per_page"], $page, 7, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_lurah":
				$this->data['data'] = $this->m_esselon->belum_upload_laporan($config["per_page"], $page, 7, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "walikota":
				$this->data['data'] = $this->m_esselon->belum_upload_laporan($config["per_page"], $page, 8, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_setda":
				$staff_sekda = $this->m_relasi_sekda->getsubbagian($this->ion_auth->user()->row()->id);
				if ($staff_sekda) {
					$this->data['data'] = $this->m_esselon->belum_upload_laporan_staffsekda_bag($config["per_page"], $page, 9, $staff_sekda[0]['bagian_id']);
				} else {
					$this->data['data'] = $this->m_esselon->belum_upload_laporan_staffsekda($config["per_page"], $page, 9);
				}
				break;
			case "sekwan":
				$this->data['data'] = $this->m_esselon->belum_upload_laporan($config["per_page"], $page, 10, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "kapus":
				$this->data['data'] = $this->m_esselon->belum_upload_laporan($config["per_page"], $page, 11, $this->ion_auth->user()->row()->skpd_id);
				break;
		}

		$this->render('telaah/list_telaah/content');
	}

	public function cetak_sudah_upload_laporan()
	{
		$this->load->library('excel');

		//load PHPExcel library
		$this->excel->setActiveSheetIndex(0);
		//name the worksheet
		$this->excel->getActiveSheet()->setTitle('Daftar Pegawai.xls');


		//STYLING
		$styleArray2 = array(
			'borders' => array(
				'allborders' =>
				array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' =>
					array('argb' => '0000'),
				),
			),
		);

		$skpd = $this->m_skpd->get($this->ion_auth->user()->row()->skpd_id);

		//SET DIMENSI TABEL
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(100);

		switch ($this->uri->segment(4)) {
			case "esselon":
				$data = $this->m_esselon->cetak_sudah_upload_laporan(1, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "puskesmas":
				$data = $this->m_esselon->cetak_sudah_upload_laporan(1, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "kadis":
				$data = $this->m_esselon->cetak_sudah_upload_laporan(2, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "dprd":
				$data = $this->m_esselon->cetak_sudah_upload_laporan(3, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "sekda":
				$data = $this->m_esselon->cetak_sudah_upload_laporan(4, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "camat":
				$data = $this->m_esselon->cetak_sudah_upload_laporan(5, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "lurah":
				$data = $this->m_esselon->cetak_sudah_upload_laporan(5, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "staff_dprd":
				$data = $this->m_esselon->cetak_sudah_upload_laporan(6, $this->ion_auth->user()->row()->skpd_id);
				break;
				break;
			case "staff_camat":
				$data = $this->m_esselon->cetak_sudah_upload_laporan(7, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_lurah":
				$data = $this->m_esselon->cetak_sudah_upload_laporan(7, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "walikota":
				$data = $this->m_esselon->cetak_sudah_upload_laporan(8, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_setda":
				$staff_sekda = $this->m_relasi_sekda->getsubbagian($this->ion_auth->user()->row()->id);
				if ($staff_sekda) {
					$data = $this->m_esselon->cetak_sudah_upload_laporan_staffsekda_bag(9, $staff_sekda[0]['bagian_id']);
				} else {
					$data = $this->m_esselon->cetak_sudah_upload_laporan_staffsekda(9);
				}
				break;
			case "sekwan":
				$data = $this->m_esselon->cetak_sudah_upload_laporan(10, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "kapus":
				$data = $this->m_esselon->cetak_sudah_upload_laporan(11, $this->ion_auth->user()->row()->skpd_id);
				break;
		}

		$this->excel->getActiveSheet()->mergeCells('A1:D1');
		$this->excel->getActiveSheet()->setCellValue('A1', 'Daftar Pegawai Yang Sudah Upload Laporan');
		$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setName('Calibri');

		$this->excel->getActiveSheet()->mergeCells('A2:D2');
		$this->excel->getActiveSheet()->setCellValue('A2', $skpd[0]['skpd_nama']);
		$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setName('Calibri');


		$this->excel->getActiveSheet()->setCellValue('A4', 'NO');
		$this->excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('A4')->getFont()->setName('Calibri');
		$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$this->excel->getActiveSheet()->setCellValue('B4', 'Tanggal Pengajuan');
		$this->excel->getActiveSheet()->getStyle('B4')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('B4')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('B4')->getFont()->setName('Calibri');
		$this->excel->getActiveSheet()->getStyle('B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$this->excel->getActiveSheet()->setCellValue('C4', 'Pelaksana');
		$this->excel->getActiveSheet()->getStyle('C4')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('C4')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('C4')->getFont()->setName('Calibri');
		$this->excel->getActiveSheet()->getStyle('C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$this->excel->getActiveSheet()->setCellValue('D4', 'Perihal');
		$this->excel->getActiveSheet()->getStyle('D4')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('D4')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('D4')->getFont()->setName('Calibri');
		$this->excel->getActiveSheet()->getStyle('D4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('D4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$no = 5;
		$x = 1;

		foreach ($data as $v) {
			$this->excel->getActiveSheet()->setCellValue('A' . $no, $x++);
			$this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$date = substr($v->telaah_waktuinput, 0, 10);
			$time = substr($v->telaah_waktuinput, 11, 19);
			$telaah_waktuinput =  $this->waktu->date_indo($date);

			$this->excel->getActiveSheet()->setCellValue('B' . $no, $telaah_waktuinput . ' ' . $time);
			$this->excel->getActiveSheet()->getStyle('B' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('B' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			if ($this->uri->segment(4) == "dprd") {
				$this->excel->getActiveSheet()->setCellValue('C' . $no, $v->anggotadprd_name);
			} else {
				$this->excel->getActiveSheet()->setCellValue('C' . $no, $v->pegawai_nama);
			}

			$this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$this->excel->getActiveSheet()->setCellValue('D' . $no, $v->telaah_perihal);
			$this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('F' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$no++;
		}

		$this->excel->getActiveSheet()->getStyle('A4:D' . ($no))->applyFromArray($styleArray2);

		if (ob_get_length()) ob_end_clean();
		$filename = 'Daftar Pegawai Yang Sudah Upload Laporan.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache

		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

		$objWriter->save('php://output');

		//redirect('report/report_all','refresh');	
	}

	public function cetak_belum_upload_laporan()
	{
		$this->load->library('excel');

		//load PHPExcel library
		$this->excel->setActiveSheetIndex(0);
		//name the worksheet
		$this->excel->getActiveSheet()->setTitle('Daftar Pegawai.xls');


		//STYLING
		$styleArray2 = array(
			'borders' => array(
				'allborders' =>
				array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' =>
					array('argb' => '0000'),
				),
			),
		);

		$skpd = $this->m_skpd->get($this->ion_auth->user()->row()->skpd_id);

		//SET DIMENSI TABEL
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(100);

		switch ($this->uri->segment(4)) {
			case "esselon":
				$data = $this->m_esselon->cetak_belum_upload_laporan(1, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "puskesmas":
				$data = $this->m_esselon->cetak_belum_upload_laporan(1, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "kadis":
				$data = $this->m_esselon->cetak_belum_upload_laporan(2, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "dprd":
				$data = $this->m_esselon->cetak_belum_upload_laporan(3, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "sekda":
				$data = $this->m_esselon->cetak_belum_upload_laporan(4, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "camat":
				$data = $this->m_esselon->cetak_belum_upload_laporan(5, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "lurah":
				$data = $this->m_esselon->cetak_belum_upload_laporan(5, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "staff_dprd":
				$data = $this->m_esselon->cetak_belum_upload_laporan(6, $this->ion_auth->user()->row()->skpd_id);
				break;
				break;
			case "staff_camat":
				$data = $this->m_esselon->cetak_belum_upload_laporan(7, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_lurah":
				$data = $this->m_esselon->cetak_belum_upload_laporan(7, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "walikota":
				$data = $this->m_esselon->cetak_belum_upload_laporan(8, $this->ion_auth->user()->row()->skpd_id);
				break;
			case "staff_setda":
				$staff_sekda = $this->m_relasi_sekda->getsubbagian($this->ion_auth->user()->row()->id);
				if ($staff_sekda) {
					$data = $this->m_esselon->cetak_belum_upload_laporan_staffsekda_bag(9, $staff_sekda[0]['bagian_id']);
				} else {
					$data = $this->m_esselon->cetak_belum_upload_laporan_staffsekda(9);
				}
				break;
			case "sekwan":
				$data = $this->m_esselon->cetak_belum_upload_laporan(10, $this->ion_auth->user()->row()->skpd_id);
				break;

			case "kapus":
				$data = $this->m_esselon->cetak_belum_upload_laporan(11, $this->ion_auth->user()->row()->skpd_id);
				break;
		}

		$this->excel->getActiveSheet()->mergeCells('A1:D1');
		$this->excel->getActiveSheet()->setCellValue('A1', 'Daftar Pegawai Yang Belum Upload Laporan');
		$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setName('Calibri');

		$this->excel->getActiveSheet()->mergeCells('A2:D2');
		$this->excel->getActiveSheet()->setCellValue('A2', $skpd[0]['skpd_nama']);
		$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setName('Calibri');


		$this->excel->getActiveSheet()->setCellValue('A4', 'NO');
		$this->excel->getActiveSheet()->getStyle('A4')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('A4')->getFont()->setName('Calibri');
		$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$this->excel->getActiveSheet()->setCellValue('B4', 'Tanggal Pengajuan');
		$this->excel->getActiveSheet()->getStyle('B4')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('B4')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('B4')->getFont()->setName('Calibri');
		$this->excel->getActiveSheet()->getStyle('B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('B4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$this->excel->getActiveSheet()->setCellValue('C4', 'Pelaksana');
		$this->excel->getActiveSheet()->getStyle('C4')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('C4')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('C4')->getFont()->setName('Calibri');
		$this->excel->getActiveSheet()->getStyle('C4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('C4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$this->excel->getActiveSheet()->setCellValue('D4', 'Perihal');
		$this->excel->getActiveSheet()->getStyle('D4')->getFont()->setSize(11);
		$this->excel->getActiveSheet()->getStyle('D4')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('D4')->getFont()->setName('Calibri');
		$this->excel->getActiveSheet()->getStyle('D4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('D4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$no = 5;
		$x = 1;

		foreach ($data as $v) {
			$this->excel->getActiveSheet()->setCellValue('A' . $no, $x++);
			$this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$date = substr($v->telaah_waktuinput, 0, 10);
			$time = substr($v->telaah_waktuinput, 11, 19);
			$telaah_waktuinput =  $this->waktu->date_indo($date);

			$this->excel->getActiveSheet()->setCellValue('B' . $no, $telaah_waktuinput . ' ' . $time);
			$this->excel->getActiveSheet()->getStyle('B' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('B' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			if ($this->uri->segment(4) == "dprd") {
				$this->excel->getActiveSheet()->setCellValue('C' . $no, $v->anggotadprd_name);
			} else {
				$this->excel->getActiveSheet()->setCellValue('C' . $no, $v->pegawai_nama);
			}

			$this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$this->excel->getActiveSheet()->setCellValue('D' . $no, $v->telaah_perihal);
			$this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('F' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$no++;
		}

		$this->excel->getActiveSheet()->getStyle('A3:D' . ($no))->applyFromArray($styleArray2);

		if (ob_get_length()) ob_end_clean();
		$filename = 'Daftar Pegawai Yang Belum Upload Laporan.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache

		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');

		$objWriter->save('php://output');

		//redirect('report/report_all','refresh');	
	}

	//View Create Data
	public function create_view()
	{
		$this->data['perjalanan'] = 0;
		$this->data['kabupaten2'] = array();
		$this->data['pengikut_on'] = 0;

		switch ($this->uri->segment(4)) {

			case "esselon":
				$this->data['pelaksana'] = $this->m_pegawai->get_pegawai_skpd($this->ion_auth->user()->row()->skpd_id);
				$this->data['pengikut'] = $this->m_pegawai->get_pegawai_skpd($this->ion_auth->user()->row()->skpd_id);
				if ($this->ion_auth->user()->row()->jenis_skpd == 7) {
					$this->data['anggaran'] = $this->m_anggaran->anggaran_opd(36);
				} else {
					$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->ion_auth->user()->row()->skpd_id);
				}
				$this->data['rekening'] = $this->m_rekening->rekening_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
				$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
				$this->data['posisi_kaopd'] = $this->m_esselon->posisi_kaopd($this->ion_auth->user()->row()->skpd_id);

				## PENANDA TANGAN
				if ($this->ion_auth->user()->row()->jenis_skpd == 7) {
					$this->data['kepala_opd'] = $this->m_spd->kepala_opd(36);
					$this->data['sekretaris_opd'] = $this->m_spd->sekretaris_opd(36);
					$this->data['kabid'] = $this->m_spd->kabid_dinkes();
				} else {
					$this->data['kepala_opd'] = $this->m_spd->kepala_opd($this->ion_auth->user()->row()->skpd_id);
					$this->data['sekretaris_opd'] = $this->m_spd->sekretaris_opd($this->ion_auth->user()->row()->skpd_id);
					$this->data['kabid'] = $this->m_spd->kabid_opd($this->ion_auth->user()->row()->skpd_id);
				}

				break;

			case "kadis":
				$this->data['pelaksana'] = $this->m_kadis->kepala_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['pengikut'] = $this->m_pegawai->get_pegawai_skpd($this->ion_auth->user()->row()->skpd_id);
				$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['rekening'] = $this->m_rekening->rekening_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
				$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
				$this->data['posisi_walikota'] = $this->m_kadis->posisi_walikota();

				## PENANDA TANGAN
				if ($this->ion_auth->user()->row()->jenis_skpd == 7) {
					$this->data['kepala_opd'] = $this->m_spd->kepala_opd(36);
					$this->data['sekretaris_opd'] = $this->m_spd->sekretaris_opd(36);
					$this->data['kabid'] = $this->m_spd->kabid_dinkes();
				} else {
					$this->data['kepala_opd'] = $this->m_spd->kepala_opd($this->ion_auth->user()->row()->skpd_id);
					$this->data['sekretaris_opd'] = $this->m_spd->sekretaris_opd($this->ion_auth->user()->row()->skpd_id);
					$this->data['kabid'] = $this->m_spd->kabid_opd($this->ion_auth->user()->row()->skpd_id);
				}

				$this->data['walikota'] = $this->m_spd->walikota();
				$this->data['wakil_walikota'] = $this->m_spd->wakil_walikota();
				$this->data['sekda'] = $this->m_spd->sekda();
				break;

			case "dprd":
				$this->data['pelaksana'] = $this->m_dprd->anggota();
				$this->data['pengikut'] = $this->m_dprd->anggota();
				$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['rekening'] = $this->m_rekening->rekening_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
				$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
				$this->data['posisi_kadprd'] = $this->m_dprd->posisi_kadprd();

				## PENANDA TANGAN
				$this->data['sekwan'] = $this->m_spd->sekwan();
				$this->data['kabid'] = $this->m_spd->kabid_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['ketua_dprd'] = $this->m_spd->ketua_dprd();
				$this->data['wakil_ketua_dprd'] = $this->m_spd->wakil_ketua_dprd();
				break;

			case "sekda":
				$this->data['pelaksana'] = $this->m_pegawai->get_pegawai_skpd($this->ion_auth->user()->row()->skpd_id);
				$this->data['pengikut'] = $this->m_pegawai->get_pegawai_skpd($this->ion_auth->user()->row()->skpd_id);
				$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['rekening'] = $this->m_rekening->rekening_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
				$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
				$this->data['posisi_walikota'] = $this->m_sekda->posisi_walikota();

				## PENANDA TANGAN
				$this->data['sekda'] = $this->m_spd->sekda();
				$this->data['asisten1'] = $this->m_spd->asisten1();
				$this->data['asisten2'] = $this->m_spd->asisten2();
				$this->data['asisten3'] = $this->m_spd->asisten3();
				$this->data['walikota'] = $this->m_spd->walikota();
				$this->data['wakil_walikota'] = $this->m_spd->wakil_walikota();
				break;

			case "camat":
				$this->data['pelaksana'] = $this->m_pegawai->get_pegawai_skpd($this->ion_auth->user()->row()->skpd_id);
				$this->data['pengikut'] = $this->m_pegawai->get_pegawai_skpd($this->ion_auth->user()->row()->skpd_id);
				$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['rekening'] = $this->m_rekening->rekening_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
				$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
				$this->data['posisi_walikota'] = $this->m_camat->posisi_walikota($this->ion_auth->user()->row()->skpd_id);

				## PENANDA TANGAN
				$this->data['camat'] = $this->m_spd->camat($this->ion_auth->user()->row()->skpd_id);
				$this->data['sekcam'] = $this->m_spd->sekcam($this->ion_auth->user()->row()->skpd_id);
				$this->data['walikota'] = $this->m_spd->walikota();
				break;

			case "lurah":
				$this->data['pelaksana'] = $this->m_pegawai->get_pegawai_skpd($this->ion_auth->user()->row()->skpd_id);
				$this->data['pengikut'] = $this->m_pegawai->get_pegawai_skpd($this->ion_auth->user()->row()->skpd_id);
				$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['rekening'] = $this->m_rekening->rekening_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
				$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
				$this->data['posisi_walikota'] = $this->m_lurah->posisi_walikota($this->ion_auth->user()->row()->skpd_id);

				## PENANDA TANGAN
				$this->data['lurah'] = $this->m_spd->lurah($this->ion_auth->user()->row()->skpd_id);
				$this->data['walikota'] = $this->m_spd->walikota();
				break;

			case "staff_dprd":
				$this->data['pelaksana'] = $this->m_pegawai->get_pegawai_skpd($this->ion_auth->user()->row()->skpd_id);
				$this->data['pengikut'] = $this->m_pegawai->get_pegawai_skpd($this->ion_auth->user()->row()->skpd_id);
				$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['rekening'] = $this->m_rekening->rekening_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
				$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
				$this->data['posisi_kadprd'] = $this->m_staff_dprd->posisi_kadprd();

				## PENANDA TANGAN
				$this->data['sekwan'] = $this->m_spd->sekwan();
				$this->data['kabid'] = $this->m_spd->kabid_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['sekwan'] = $this->m_spd->sekwan($this->ion_auth->user()->row()->skpd_id);
				$this->data['kabid'] = $this->m_spd->kabid_opd($this->ion_auth->user()->row()->skpd_id);
				break;

			case "staff_camat":
				$this->data['pelaksana'] = $this->m_pegawai->get_pegawai_skpd($this->ion_auth->user()->row()->skpd_id);
				$this->data['pengikut'] = $this->m_pegawai->get_pegawai_skpd($this->ion_auth->user()->row()->skpd_id);
				$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['rekening'] = $this->m_rekening->rekening_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
				$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
				$this->data['posisi_kaopd'] = $this->m_staff_camat->posisi_kaopd($this->ion_auth->user()->row()->skpd_id);


				## PENANDA TANGAN
				$this->data['camat'] = $this->m_spd->camat($this->ion_auth->user()->row()->skpd_id);
				$this->data['sekcam'] = $this->m_spd->sekcam($this->ion_auth->user()->row()->skpd_id);
				break;

			case "staff_lurah":
				$this->data['pelaksana'] = $this->m_pegawai->get_pegawai_skpd($this->ion_auth->user()->row()->skpd_id);
				$this->data['pengikut'] = $this->m_pegawai->get_pegawai_skpd($this->ion_auth->user()->row()->skpd_id);
				$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['rekening'] = $this->m_rekening->rekening_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
				$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
				$this->data['posisi_kaopd'] = $this->m_staff_lurah->posisi_kaopd($this->ion_auth->user()->row()->skpd_id);

				## PENANDA TANGAN
				$this->data['lurah'] = $this->m_spd->lurah($this->ion_auth->user()->row()->skpd_id);
				break;

			case "walikota":
				$this->data['pelaksana'] = $this->m_sekda->pimpinan();
				// Use AJAX for pengikut to avoid memory exhaustion
				$this->data['pengikut'] = array();
				// $this->data['pengikut'] = $this->m_sekda->pegawaiall_optimized();
				$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['rekening'] = $this->m_rekening->rekening_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
				$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
				$this->data['posisi_walikota'] = $this->m_sekda->posisi_walikota();

				## PENANDA TANGAN
				$this->data['sekda'] = $this->m_spd->sekda();
				$this->data['asisten1'] = $this->m_spd->asisten1();
				$this->data['asisten2'] = $this->m_spd->asisten2();
				$this->data['asisten3'] = $this->m_spd->asisten3();

				$this->data['walikota'] = $this->m_spd->walikota();
				$this->data['wakil_walikota'] = $this->m_spd->wakil_walikota();
				break;

			case "staff_setda":
				$staff_sekda = $this->m_relasi_sekda->getsubbagian($this->ion_auth->user()->row()->id);
				if ($staff_sekda) {
					$this->data['pelaksana'] = $this->m_sekda->pegawai_setda($this->ion_auth->user()->row()->skpd_id, $staff_sekda[0]['bagian_id']);
					$this->data['pengikut'] = $this->m_sekda->pegawai_setda($this->ion_auth->user()->row()->skpd_id, $staff_sekda[0]['bagian_id']);
					$this->data['anggaran'] = $this->m_sekda->anggaran_setda($this->ion_auth->user()->row()->skpd_id, $staff_sekda[0]['bagian_id']);
				} else {
					$this->data['pelaksana'] = $this->m_sekda->pegawai($this->ion_auth->user()->row()->skpd_id);
					$this->data['pengikut'] = $this->m_sekda->pegawai($this->ion_auth->user()->row()->skpd_id);
					$this->data['anggaran'] = $this->m_sekda->anggaran($this->ion_auth->user()->row()->skpd_id);
				}

				$this->data['rekening'] = $this->m_sekda->rekening($this->ion_auth->user()->row()->skpd_id);
				$this->data['provinsi'] = $this->m_sekda->get_provinsi();
				$this->data['kabupaten'] = $this->m_sekda->get_kabupaten();
				$this->data['posisi_walikota'] = $this->m_sekda->posisi_walikota();

				## PENANDA TANGAN
				$this->data['sekda'] = $this->m_spd->sekda();
				$this->data['asisten1'] = $this->m_spd->asisten1();
				$this->data['asisten2'] = $this->m_spd->asisten2();
				$this->data['asisten3'] = $this->m_spd->asisten3();

				break;

			case "sekwan":
				$this->data['pelaksana'] = $this->m_sekwan->sekwan($this->ion_auth->user()->row()->skpd_id);
				$this->data['pengikut'] = $this->m_pegawai->get_pegawai_skpd($this->ion_auth->user()->row()->skpd_id);
				$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['rekening'] = $this->m_rekening->rekening_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
				$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
				$this->data['posisi_walikota'] = $this->m_sekwan->posisi_walikota();

				## PENANDA TANGAN
				$this->data['sekwan'] = $this->m_spd->sekwan();
				$this->data['kabid'] = $this->m_spd->kabid_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['walikota'] = $this->m_spd->walikota();
				$this->data['wakil_walikota'] = $this->m_spd->wakil_walikota();
				$this->data['sekda'] = $this->m_spd->sekda();
				break;

			case "kapus":
				$this->data['pelaksana'] = $this->m_kapus->kepala_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['pegawai'] = $this->m_kapus->pegawai($this->ion_auth->user()->row()->skpd_id);
				$this->data['pengikut'] = $this->m_pegawai->get_pegawai_skpd($this->ion_auth->user()->row()->skpd_id);
				$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['rekening'] = $this->m_rekening->rekening_opd($this->ion_auth->user()->row()->skpd_id);
				$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
				$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
				$this->data['posisi_walikota'] = $this->m_kapus->posisi_walikota();

				## PENANDA TANGAN
				$this->data['kapus'] = $this->m_spd->kapus($this->ion_auth->user()->row()->skpd_id);
				break;
		}

		$this->render('telaah/list_telaah/insert');
	}

	//Call Back Validation Kegiatan
	public function kegiatan_check($str)
	{
		$pagu = $this->m_anggaran->get($str);
		$rincian_biaya =  $this->m_anggaran->cek_sisa_anggaran_skpd($str);
		$pengeluaran_rill =  $this->m_anggaran->cek_pengeluaran_rill_skpd($str);
		$rincian = $rincian_biaya[0]->jumlah + $pengeluaran_rill[0]->jumlah;
		$total = round(($rincian / $pagu[0]['pagu']) * 100, 2);
		if ($total >= 100) {
			$this->form_validation->set_message('kegiatan_check', 'Anggaran Perjalanan Yang Akan Digunakan Tidak Tersedia');
			return FALSE;
		}
		if ($pagu[0]['pagu'] == 0 || $pagu[0]['pagu'] == "") {
			$this->form_validation->set_message('kegiatan_check', 'Anggaran Perjalanan Yang Akan Digunakan Tidak Tersedia');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	//Call Back Validation pelaksana
	public function pelaksana_check($str)
	{
		$status_laporan = $this->m_telaah->count_laporan_perjalanan_pegawai($str);
		if ($status_laporan > 0) {
			$this->form_validation->set_message('pelaksana_check', 'Laporan Perjalanan Pelaksana Belum Di Verifikasi');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	//Create Data
	public function create()
	{
		$this->form_validation->set_rules('telaah_perihal', 'Perihal', 'required');
		$this->form_validation->set_rules('telaah_persoalan', 'Persoalan', 'required');
		$this->form_validation->set_rules('telaah_fakta', 'Fakta', 'required');
		$this->form_validation->set_rules('telaah_analisis', 'Analisis', 'required');
		$this->form_validation->set_rules('telaah_jenisangkutan', 'Jenis Angkutan', 'required');
		$this->form_validation->set_rules('telaah_angkutan', 'Angkutan', 'required');
		$this->form_validation->set_rules('telaah_tanggalberangkat', 'Tanggal Berangkat', 'required');
		$this->form_validation->set_rules('telaah_tanggalkembali', 'Tanggal Kembali', 'required');
		$this->form_validation->set_rules('telaah_hari', 'Lama Perjalanan (Hari)', 'required');
		$this->form_validation->set_rules('telaah_tempatberangkat', 'Tempat Berangkat', 'required');
		$this->form_validation->set_rules('telaah_domainperjalanan', 'Domain Perjalanan', 'required');
		if ($this->input->post('telaah_domainperjalanan') == 1) {
			$this->data['perjalanan'] = 1;
			$this->data['kabupaten2'] = $this->m_kabupaten->get_kabupaten2($this->input->post('telaah_provinsitujuan'));
			$this->form_validation->set_rules('telaah_provinsitujuan', 'Provinsi', 'required');
			$this->form_validation->set_rules('telaah_kotatujuan', 'Kota Tujuan', 'required');
		} else if ($this->input->post('telaah_domainperjalanan') == 2) {
			$this->data['perjalanan'] = 2;
			$data['telaah_provinsitujuan'] = 74;
			$this->form_validation->set_rules('telaah_kotatujuan2', 'Kota Tujuan', 'required');
		}
		$this->form_validation->set_rules('telaah_kantortujuan', 'Kantor Tujuan', 'required');
		$this->form_validation->set_rules('telaah_kegiatan', 'Kegiatan', 'required|callback_kegiatan_check');
		$this->form_validation->set_rules('telaah_kategoriperjalanan', 'Kategori Perjalanan', 'required');
		if ($this->input->post('telaah_kategoriperjalanan') == 1) {
			if (empty($_FILES['userfile']['name'])) {
				$this->form_validation->set_rules('userfile', 'Dokumen Pendukung', 'required');
			}
		}
		$this->form_validation->set_rules('telaah_kecepatan', 'Telaah Kecepatan', 'required');
		$this->form_validation->set_rules('telaah_tanggalspd', 'Tanggal SPPD', 'required');
		$this->form_validation->set_rules('telaah_tanggalspt', 'Tanggal SPT', 'required');

		if ($this->ion_auth->user()->row()->jenis_skpd == 7 || $this->ion_auth->user()->row()->id == 947 || $this->ion_auth->user()->row()->id == 948) {
			## PELAKSANA
			if ($this->input->post('sppd_lanjutan') == "") {
				$this->form_validation->set_rules('telaah_pelaksana', 'Pelaksana', 'required');
			}
		} else {
			## PELAKSANA
			if ($this->input->post('sppd_lanjutan') == "") {
				$this->form_validation->set_rules('telaah_pelaksana', 'Pelaksana', 'required');
			}

			## PENGIKUT
			$telaah_pengikut = $this->input->post('telaah_pengikut');
			$pengikut = is_array($telaah_pengikut) ? count($telaah_pengikut) : 0;
			$n = 0;
			for ($i = 0; $i < $pengikut; $i++) {
				if ($this->uri->segment(4) == "dprd") {
					$pegawai_id = $this->m_anggota->get_status($this->input->post('telaah_pengikut')[$i], 1);
				} else {
					$pegawai_id = $this->m_pegawai->get_status($this->input->post('telaah_pengikut')[$i], 1);
				}
				$n = $n + count($pegawai_id);

				if (count($pegawai_id) > 0) {
					$this->form_validation->set_rules('telaah_pengikut' . $n, $pegawai_id[0]['pegawai_nama'], 'required', array('required' => '%s (Belum Mengisi laporan Perjalanannya).'));
				}
			}
			$this->data['pengikut_on'] = $n;
		}

		$cek_kop_surat = $this->m_skpd->get($this->ion_auth->user()->row()->skpd_id);
		$path_file = './upload/kop_surat/' . $cek_kop_surat[0]['kop_surat'];

		if (file_exists($path_file)) {
		} else {
			$this->form_validation->set_rules('kop_surat', 'Kop Surat', 'required', array('required' => '%s Belum Di Upload.'));
		}

		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');

		if ($this->form_validation->run() == FALSE) {
			$this->create_view();
		} else {
			$filename = $this->input->post('userfile');
			$config['upload_path'] = './upload/telaah/';
			$config['allowed_types'] = "gif|jpg|jpeg|png|pdf|doc|docx|xls|xlsx|ppt|pptx";
			$config['overwrite'] = "true";
			$config['max_size'] = "20000000";
			$config['max_width'] = "10000";
			$config['max_height'] = "10000";
			$config['file_name'] = '' . $filename;
			$this->upload->initialize($config);
			if (!$this->upload->do_upload()) {

				$data['telaah_perihal'] = $this->input->post('telaah_perihal');
				$data['telaah_persoalan'] = $this->input->post('telaah_persoalan');
				$data['telaah_fakta'] = $this->input->post('telaah_fakta');
				$data['telaah_analisis'] = $this->input->post('telaah_analisis');
				$data['telaah_jenisangkutan'] = $this->input->post('telaah_jenisangkutan');
				$data['telaah_angkutan'] = $this->input->post('telaah_angkutan');
				$data['telaah_tanggalberangkat'] = date("Y-m-d", strtotime($this->input->post('telaah_tanggalberangkat')));
				$data['telaah_tanggalkembali'] = date("Y-m-d", strtotime($this->input->post('telaah_tanggalkembali')));
				$data['telaah_hari'] = str_replace(".", "", $this->input->post('telaah_hari'));
				$data['telaah_tempatberangkat'] = $this->input->post('telaah_tempatberangkat');
				$data['telaah_domainperjalanan'] = $this->input->post('telaah_domainperjalanan');
				if ($data['telaah_domainperjalanan'] == 1) {
					$data['telaah_provinsitujuan'] = $this->input->post('telaah_provinsitujuan');
					$data['telaah_kotatujuan'] = $this->input->post('telaah_kotatujuan');
				} else if ($data['telaah_domainperjalanan'] == 2) {
					$data['telaah_provinsitujuan'] = 74;
					$data['telaah_kotatujuan'] = $this->input->post('telaah_kotatujuan2');
				} else if ($data['telaah_domainperjalanan'] == 3) {
					$data['telaah_provinsitujuan'] = 74;
					$data['telaah_kotatujuan'] = 7471;
				}

				$data['telaah_kantortujuan'] = $this->input->post('telaah_kantortujuan');
				$data['telaah_kegiatan'] = $this->input->post('telaah_kegiatan');
				$data['telaah_kategoriperjalanan'] = $this->input->post('telaah_kategoriperjalanan');
				$data['telaah_kecepatan'] = $this->input->post('telaah_kecepatan');
				$data['telaah_pelaksana'] = $this->input->post('telaah_pelaksana');
				if ($this->ion_auth->user()->row()->skpd_id == 182) {
					$data['telaah_jabatan_pelaksana'] = $this->input->post('telaah_jabatan_pelaksana');
					$data['telaah_no_surat_tugas'] = $this->input->post('telaah_no_surat_tugas');
				}
				if ($this->input->post('telaah_sekretariat') == 1) {
					$data['telaah_sekretariat'] = $this->input->post('telaah_sekretariat');
				} else {
					$data['telaah_sekretariat'] = 0;
				}
				$data['telaah_tanggalspd'] = date("Y-m-d", strtotime($this->input->post('telaah_tanggalspd')));
				$data['telaah_ttdspd'] = $this->input->post('telaah_ttdspd');

				$ttd = explode(",", $this->input->post('telaah_ttdspt'));

				// Default values if explode fails
				$ttd_code = isset($ttd[0]) ? $ttd[0] : 0;
				$ttd_desc = isset($ttd[1]) ? $ttd[1] : '';

				// echo $ttd[0]; //Code 
				// echo $ttd[1]; //Description  

				## WALIKOTA, WAKIL WALIKOTA
				if ($ttd_code == 1) {
					$data['telaah_tanggalspt'] = date("Y-m-d", strtotime($this->input->post('telaah_tanggalspt')));
					$data['telaah_ttdspt'] = $ttd_desc;
					$data['telaah_ttdsptw'] = 1;
				} else {
					$data['telaah_tanggalspt'] = date("Y-m-d", strtotime($this->input->post('telaah_tanggalspt')));
					$data['telaah_ttdspt'] = $ttd_desc;
					$data['telaah_ttdsptw'] = 0;
				}

				$data['user_id'] = $this->ion_auth->user()->row()->user_id;

				// Kategori telaah
				switch ($this->uri->segment(4)) {
					case "esselon":
						$data['telaah_kategori'] = 1;
						break;
					case "kadis":
						$data['telaah_kategori'] = 2;
						break;
					case "dprd":
						$data['telaah_kategori'] = 3;
						break;
					case "sekda":
						$data['telaah_kategori'] = 4;
						break;
					case "camat":
						$data['telaah_kategori'] = 5;
						break;
					case "lurah":
						$data['telaah_kategori'] = 5;
						break;
					case "staff_dprd":
						$data['telaah_kategori'] = 6;
						break;
					case "staff_camat":
						$data['telaah_kategori'] = 7;
						break;
					case "staff_lurah":
						$data['telaah_kategori'] = 7;
						break;
					case "walikota":
						$data['telaah_kategori'] = 8;
						break;
					case "staff_setda":
						$data['telaah_kategori'] = 9;
						break;
					case "sekwan":
						$data['telaah_kategori'] = 10;
						break;
					case "kapus":
						$data['telaah_kategori'] = 11;
						break;
				}

				$data['telaah_waktuinput'] = date("Y-m-d H:i:s");
				$data['telaah_skpd_id'] = $this->ion_auth->user()->row()->skpd_id;
				$data['telaah_jenis_skpd'] = $this->ion_auth->user()->row()->jenis_skpd;

				// Create Telaah
				$this->m_telaah->create($data);

				$data2['pegawai_id'] = $this->input->post('telaah_pelaksana');
				$data2['status'] = 1;
				$this->m_pegawai->update($data2);

				// Get Telaah
				$last = $this->m_telaah->getLast();
				foreach ($last as $l) {
					$last_id = $l->telaah_id;
				}
				$datas['telaah_id'] = $last_id;

				// Create TimeLine
				switch ($this->uri->segment(4)) {
					case "esselon":
						if ($this->input->post('telaah_sekretariat') == 1) {
							$datas['telaah_id'] = $last_id;
							$datas['timeline_kabid_id'] = 1;
							$datas['timeline_kabid_name'] = "";
							$datas['timeline_kabid_date'] = date("Y-m-d H:i:s");
						} else {
							$datas['telaah_id'] = $last_id;
						}
						$this->m_timeline->create1($datas);
						break;
					case "kadis":
						$this->m_timeline->create2($datas);
						break;
					case "dprd":
						$this->m_timeline->create3($datas);
						break;
					case "sekda":
						$this->m_timeline->create4($datas);
						break;
					case "camat":
						$this->m_timeline->create5($datas);
						break;
					case "lurah":
						$this->m_timeline->create5($datas);
						break;
					case "staff_dprd":
						$this->m_timeline->create6($datas);
						break;
					case "staff_camat":
						$this->m_timeline->create7($datas);
						break;
					case "staff_lurah":
						$this->m_timeline->create7($datas);
						break;
					case "walikota":
						$this->m_timeline->create8($datas);
						break;
					case "staff_setda":
						$this->m_timeline->create9($datas);
						break;
					case "sekwan":
						$this->m_timeline->create10($datas);
						break;
					case "kapus":
						$this->m_timeline->create11($datas);
						break;
				}

				switch ($this->uri->segment(4)) {
					case "staff_setda":
						$sekda = $this->m_relasi_sekda->getsubbagian($this->ion_auth->user()->row()->id);
						$data3['telaah_id'] = $last_id;
						$data3['subbagian_id'] = $sekda[0]['subbagian_id'];
						$this->m_relasi_sekda->create($data3);
						break;
					case "sekda":
						$sekda = $this->m_relasi_sekda->getsubbagian($this->ion_auth->user()->row()->id);
						$data3['telaah_id'] = $last_id;
						$data3['subbagian_id'] = $sekda[0]['subbagian_id'];
						$this->m_relasi_sekda->create($data3);
						break;
				}


				$telaah_pengikut = $this->input->post('telaah_pengikut');
				$jumlah = is_array($telaah_pengikut) ? count($telaah_pengikut) : 0;

				for ($i = 0; $i < $jumlah; $i++) {
					$data4['telaah_id'] = $last_id;
					$data4['pegawai_id'] = $this->input->post('telaah_pengikut')[$i];
					if ($this->ion_auth->user()->row()->skpd_id == 182) {
						$data4['telaah_jabatan_pengikut'] = $this->input->post('telaah_jabatan_pengikut')[$i];
					}
					$this->m_pengikut->create($data4);

					## Create SPPD dan SPT Pengikut
					switch ($this->uri->segment(4)) {
						case "esselon":
							$this->cetak_spd($last_id, 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						// $this->cetak_spt($last_id,$this->uri->segment(4),2,$this->input->post('telaah_pengikut')[$i]); break;
						case "kadis":
							$this->cetak_spd($last_id, 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						// $this->cetak_spt($last_id,$this->uri->segment(4),2,$this->input->post('telaah_pengikut')[$i]); break;
						case "dprd":
							$this->cetak_spd($last_id, 'dprd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "sekda":
							$this->cetak_spd($last_id, 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "camat":
							$this->cetak_spd($last_id, 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "lurah":
							$this->cetak_spd($last_id, 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "staff_dprd":
							$this->cetak_spd($last_id, 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "staff_camat":
							$this->cetak_spd($last_id, 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "staff_lurah":
							$this->cetak_spd($last_id, 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "walikota":
							$this->cetak_spd($last_id, 'walikota', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "staff_setda":
							$this->cetak_spd($last_id, 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "sekwan":
							$this->cetak_spd($last_id, 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "kapus":
							$this->cetak_spd($last_id, 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
					}
				}

				$prov_tujuan2 = $this->input->post('telaah_provinsitujuan2');
				$jml_lokasi_tujuan = is_array($prov_tujuan2) ? count($prov_tujuan2) : 0;
				for ($i = 0; $i < $jml_lokasi_tujuan; $i++) {
					$data5['telaah_id'] = $last_id;
					$data5['provinsi_id'] = $this->input->post('telaah_provinsitujuan2')[$i];
					$data5['kabkot_id'] = $this->input->post('telaah_kotatujuan3')[$i];
					$this->m_lokasi_tujuan->create($data5);
				}

				## Create SPPD dan SPT Pelaksana
				switch ($this->uri->segment(4)) {
					case "esselon":
						$this->cetak_spd($last_id, 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "kadis":
						$this->cetak_spd($last_id, 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "dprd":
						$this->cetak_spd($last_id, 'dprd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt_dprd($last_id, $this->input->post('telaah_pelaksana'));
						break;
					case "sekda":
						$this->cetak_spd($last_id, 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 2, $this->input->post('telaah_pelaksana'));
						break;
					case "camat":
						$this->cetak_spd($last_id, 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "lurah":
						$this->cetak_spd($last_id, 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "staff_dprd":
						$this->cetak_spd($last_id, 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "staff_camat":
						$this->cetak_spd($last_id, 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 2, $this->input->post('telaah_pelaksana'));
						break;
					case "staff_lurah":
						$this->cetak_spd($last_id, 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 2, $this->input->post('telaah_pelaksana'));
						break;
					case "walikota":
						$this->cetak_spd($last_id, 'walikota', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "staff_setda":
						$this->cetak_spd($last_id, 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 2, $this->input->post('telaah_pelaksana'));
						break;
					case "sekwan":
						$this->cetak_spd($last_id, 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "kapus":
						$this->cetak_spd($last_id, 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
				}

				$log['kode_log_action'] = "53";
				$log['action'] = "INSERT";
				$log['kode_log_action_table'] = "15";
				$log['action_table'] = "TELAAH STAFF";
				$this->m_log->create($log);

				$this->session->set_flashdata('notif', 'Data Telaah Staf Di Simpan !');

				redirect('telaah/list_telaah/index/' . $this->uri->segment(4));
			} else {

				$dat = $this->upload->data();

				$data['telaah_perihal'] = $this->input->post('telaah_perihal');
				$data['telaah_persoalan'] = $this->input->post('telaah_persoalan');
				$data['telaah_fakta'] = $this->input->post('telaah_fakta');
				$data['telaah_analisis'] = $this->input->post('telaah_analisis');
				$data['telaah_jenisangkutan'] = $this->input->post('telaah_jenisangkutan');
				$data['telaah_angkutan'] = $this->input->post('telaah_angkutan');
				$data['telaah_tanggalberangkat'] = date("Y-m-d", strtotime($this->input->post('telaah_tanggalberangkat')));
				$data['telaah_tanggalkembali'] = date("Y-m-d", strtotime($this->input->post('telaah_tanggalkembali')));
				$data['telaah_hari'] = str_replace(".", "", $this->input->post('telaah_hari'));
				$data['telaah_tempatberangkat'] = $this->input->post('telaah_tempatberangkat');
				$data['telaah_domainperjalanan'] = $this->input->post('telaah_domainperjalanan');
				if ($data['telaah_domainperjalanan'] == 1) {
					$data['telaah_provinsitujuan'] = $this->input->post('telaah_provinsitujuan');
					$data['telaah_kotatujuan'] = $this->input->post('telaah_kotatujuan');
				} else if ($data['telaah_domainperjalanan'] == 2) {
					$data['telaah_provinsitujuan'] = 74;
					$data['telaah_kotatujuan'] = $this->input->post('telaah_kotatujuan2');
				} else if ($data['telaah_domainperjalanan'] == 3) {
					$data['telaah_provinsitujuan'] = 74;
					$data['telaah_kotatujuan'] = 7471;
				}

				$data['telaah_kantortujuan'] = $this->input->post('telaah_kantortujuan');
				$data['telaah_kegiatan'] = $this->input->post('telaah_kegiatan');
				$data['telaah_kategoriperjalanan'] = $this->input->post('telaah_kategoriperjalanan');
				$data['telaah_kecepatan'] = $this->input->post('telaah_kecepatan');
				$data['telaah_pelaksana'] = $this->input->post('telaah_pelaksana');
				if ($this->ion_auth->user()->row()->skpd_id == 182) {
					$data['telaah_jabatan_pelaksana'] = $this->input->post('telaah_jabatan_pelaksana');
					$data['telaah_no_surat_tugas'] = $this->input->post('telaah_no_surat_tugas');
				}
				if ($this->input->post('telaah_sekretariat') == 1) {
					$data['telaah_sekretariat'] = $this->input->post('telaah_sekretariat');
				} else {
					$data['telaah_sekretariat'] = 0;
				}

				$data['telaah_tanggalspd'] = date("Y-m-d", strtotime($this->input->post('telaah_tanggalspd')));
				$data['telaah_ttdspd'] = $this->input->post('telaah_ttdspd');

				$ttd = explode(",", $this->input->post('telaah_ttdspt'));

				// Default values if explode fails
				$ttd_code = isset($ttd[0]) ? $ttd[0] : 0;
				$ttd_desc = isset($ttd[1]) ? $ttd[1] : '';

				// echo $ttd[0]; //Code 
				// echo $ttd[1]; //Description  

				## WALIKOTA, WAKIL WALIKOTA
				if ($ttd_code == 1) {
					$data['telaah_tanggalspt'] = date("Y-m-d", strtotime($this->input->post('telaah_tanggalspt')));
					$data['telaah_ttdspt'] = $ttd_desc;
					$data['telaah_ttdsptw'] = 1;
				} else {
					$data['telaah_tanggalspt'] = date("Y-m-d", strtotime($this->input->post('telaah_tanggalspt')));
					$data['telaah_ttdspt'] = $ttd_desc;
					$data['telaah_ttdsptw'] = 0;
				}

				$data['user_id'] = $this->ion_auth->user()->row()->user_id;

				// Kategori telaah
				switch ($this->uri->segment(4)) {
					case "esselon":
						$data['telaah_kategori'] = 1;
						break;
					case "kadis":
						$data['telaah_kategori'] = 2;
						break;
					case "dprd":
						$data['telaah_kategori'] = 3;
						break;
					case "sekda":
						$data['telaah_kategori'] = 4;
						break;
					case "camat":
						$data['telaah_kategori'] = 5;
						break;
					case "lurah":
						$data['telaah_kategori'] = 5;
						break;
					case "staff_dprd":
						$data['telaah_kategori'] = 6;
						break;
					case "staff_camat":
						$data['telaah_kategori'] = 7;
						break;
					case "staff_lurah":
						$data['telaah_kategori'] = 7;
						break;
					case "walikota":
						$data['telaah_kategori'] = 8;
						break;
					case "staff_setda":
						$data['telaah_kategori'] = 9;
						break;
					case "sekwan":
						$data['telaah_kategori'] = 10;
						break;
					case "kapus":
						$data['telaah_kategori'] = 11;
						break;
				}

				$data['telaah_waktuinput'] = date("Y-m-d H:i:s");
				$data['telaah_skpd_id'] = $this->ion_auth->user()->row()->skpd_id;
				$data['telaah_jenis_skpd'] = $this->ion_auth->user()->row()->jenis_skpd;
				$data['telaah_dokumenpendukung'] = $dat['file_name'];

				// Create Telaah
				$this->m_telaah->create($data);

				// Get Telaah
				$last = $this->m_telaah->getLast();
				foreach ($last as $l) {
					$last_id = $l->telaah_id;
				}
				$datas['telaah_id'] = $last_id;

				// Create TimeLine
				switch ($this->uri->segment(4)) {
					case "esselon":
						if ($this->input->post('telaah_sekretariat') == 1) {
							$datas['telaah_id'] = $last_id;
							$datas['timeline_kabid_id'] = 1;
							$datas['timeline_kabid_name'] = "";
							$datas['timeline_kabid_date'] = date("Y-m-d H:i:s");
						} else {
							$datas['telaah_id'] = $last_id;
						}
						$this->m_timeline->create1($datas);
						break;
					case "kadis":
						$this->m_timeline->create2($datas);
						break;
					case "dprd":
						$this->m_timeline->create3($datas);
						break;
					case "sekda":
						$this->m_timeline->create4($datas);
						break;
					case "camat":
						$this->m_timeline->create5($datas);
						break;
					case "lurah":
						$this->m_timeline->create5($datas);
						break;
					case "staff_dprd":
						$this->m_timeline->create6($datas);
						break;
					case "staff_camat":
						$this->m_timeline->create7($datas);
						break;
					case "staff_lurah":
						$this->m_timeline->create7($datas);
						break;
					case "walikota":
						$this->m_timeline->create8($datas);
						break;
					case "staff_setda":
						$this->m_timeline->create9($datas);
						break;
					case "sekwan":
						$this->m_timeline->create10($datas);
						break;
					case "kapus":
						$this->m_timeline->create11($datas);
						break;
				}

				switch ($this->uri->segment(4)) {
					case "staff_setda":
						$sekda = $this->m_relasi_sekda->getsubbagian($this->ion_auth->user()->row()->id);
						$data3['telaah_id'] = $last_id;
						$data3['subbagian_id'] = $sekda[0]['subbagian_id'];
						$this->m_relasi_sekda->create($data3);
						break;
					case "sekda":
						$sekda = $this->m_relasi_sekda->getsubbagian($this->ion_auth->user()->row()->id);
						$data3['telaah_id'] = $last_id;
						$data3['subbagian_id'] = $sekda[0]['subbagian_id'];
						$this->m_relasi_sekda->create($data3);
						break;
				}

				$telaah_pengikut = $this->input->post('telaah_pengikut');
				$jumlah = is_array($telaah_pengikut) ? count($telaah_pengikut) : 0;

				for ($i = 0; $i < $jumlah; $i++) {
					$data2['telaah_id']  = $last_id;
					$data2['pegawai_id'] = $this->input->post('telaah_pengikut')[$i];
					if ($this->ion_auth->user()->row()->skpd_id == 182) {
						$data2['telaah_jabatan_pengikut'] = $this->input->post('telaah_jabatan_pengikut')[$i];
					}
					$this->m_pengikut->create($data2);

					## Create SPPD dan SPT Pengikut
					switch ($this->uri->segment(4)) {
						case "esselon":
							$this->cetak_spd($last_id, 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						// $this->cetak_spt($last_id,$this->uri->segment(4),2,$this->input->post('telaah_pengikut')[$i]); break;
						case "kadis":
							$this->cetak_spd($last_id, 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						// $this->cetak_spt($last_id,$this->uri->segment(4),2,$this->input->post('telaah_pengikut')[$i]); break;
						case "dprd":
							$this->cetak_spd($last_id, 'dprd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "sekda":
							$this->cetak_spd($last_id, 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "camat":
							$this->cetak_spd($last_id, 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "lurah":
							$this->cetak_spd($last_id, 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "staff_dprd":
							$this->cetak_spd($last_id, 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "staff_camat":
							$this->cetak_spd($last_id, 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "staff_lurah":
							$this->cetak_spd($last_id, 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "walikota":
							$this->cetak_spd($last_id, 'walikota', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "staff_setda":
							$this->cetak_spd($last_id, 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "sekwan":
							$this->cetak_spd($last_id, 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "kapus":
							$this->cetak_spd($last_id, 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
					}
				}

				$prov_tujuan2 = $this->input->post('telaah_provinsitujuan2');
				$jml_lokasi_tujuan = is_array($prov_tujuan2) ? count($prov_tujuan2) : 0;
				for ($i = 0; $i < $jml_lokasi_tujuan; $i++) {
					$data3['telaah_id'] = $last_id;
					$data3['provinsi_id'] = $this->input->post('telaah_provinsitujuan2')[$i];
					$data3['kabkot_id'] = $this->input->post('telaah_kotatujuan3')[$i];
					$this->m_lokasi_tujuan->create($data3);
				}

				## Create SPPD dan SPT Pelaksana
				switch ($this->uri->segment(4)) {
					case "esselon":
						$this->cetak_spd($last_id, 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "kadis":
						$this->cetak_spd($last_id, 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "dprd":
						$this->cetak_spd($last_id, 'dprd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt_dprd($last_id, $this->input->post('telaah_pelaksana'));
						break;
					case "sekda":
						$this->cetak_spd($last_id, 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 2, $this->input->post('telaah_pelaksana'));
						break;
					case "camat":
						$this->cetak_spd($last_id, 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "lurah":
						$this->cetak_spd($last_id, 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "staff_dprd":
						$this->cetak_spd($last_id, 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "staff_camat":
						$this->cetak_spd($last_id, 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 2, $this->input->post('telaah_pelaksana'));
						break;
					case "staff_lurah":
						$this->cetak_spd($last_id, 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 2, $this->input->post('telaah_pelaksana'));
						break;
					case "walikota":
						$this->cetak_spd($last_id, 'walikota', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "staff_setda":
						$this->cetak_spd($last_id, 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 2, $this->input->post('telaah_pelaksana'));
						break;
					case "sekwan":
						$this->cetak_spd($last_id, 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "kapus":
						$this->cetak_spd($last_id, 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($last_id, $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
				}

				$log['kode_log_action'] = "53";
				$log['action'] = "INSERT";
				$log['kode_log_action_table'] = "15";
				$log['action_table'] = "TELAAH STAFF";
				$this->m_log->create($log);

				$this->session->set_flashdata('notif', 'Data Telaah Staf Di Simpan !');

				//Redirect
				redirect('telaah/list_telaah/index/' . $this->uri->segment(4));
			}
		}
	}

	//View Update Data
	public function update_view()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));

		$this->data['entry'] =  $this->m_telaah->get($telaah_id);
		if (!isset($this->data['entry'][0]) || $this->data['entry'][0] == "") {
			redirect('kabid/list_telaah');
		} else {
			switch ($this->uri->segment(4)) {

				case "esselon":
					$this->data['pelaksana'] = $this->m_pegawai->get_pegawai_skpd($this->data['entry'][0]['skpd']);
					$this->data['pengikut'] = $this->m_pegawai->get_pegawai_skpd($this->data['entry'][0]['skpd']);
					if ($this->ion_auth->user()->row()->jenis_skpd == 7) {
						$this->data['anggaran'] = $this->m_anggaran->anggaran_opd(36);
					} else {
						$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->data['entry'][0]['skpd']);
					}
					$this->data['rekening'] = $this->m_rekening->rekening_opd($this->data['entry'][0]['skpd']);
					$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
					$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
					$this->data['posisi_kaopd'] = $this->m_esselon->posisi_kaopd($this->data['entry'][0]['skpd']);
					break;

				case "kadis":
					$this->data['pelaksana'] = $this->m_kadis->kepala_opd($this->data['entry'][0]['skpd']);
					$this->data['pengikut'] = $this->m_pegawai->get_pegawai_skpd($this->data['entry'][0]['skpd']);
					$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->data['entry'][0]['skpd']);
					$this->data['rekening'] = $this->m_rekening->rekening_opd($this->data['entry'][0]['skpd']);
					$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
					$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
					$this->data['posisi_walikota'] = $this->m_kadis->posisi_walikota();
					break;

				case "dprd":
					$this->data['pelaksana'] = $this->m_dprd->anggota();
					$this->data['pengikut'] = $this->m_dprd->anggota();
					$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->data['entry'][0]['skpd_id']);
					$this->data['rekening'] = $this->m_rekening->rekening_opd($this->data['entry'][0]['skpd']);
					$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
					$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
					$this->data['posisi_kadprd'] = $this->m_dprd->posisi_kadprd();
					break;

				case "sekda":
					$this->data['pelaksana'] = $this->m_pegawai->get_pegawai_skpd($this->data['entry'][0]['skpd']);
					$this->data['pengikut'] = $this->m_pegawai->get_pegawai_skpd($this->data['entry'][0]['skpd']);
					$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->data['entry'][0]['skpd']);
					$this->data['rekening'] = $this->m_rekening->rekening_opd($this->data['entry'][0]['skpd']);
					$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
					$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
					$this->data['posisi_walikota'] = $this->m_sekda->posisi_walikota();
					break;

				case "camat":
					$this->data['pelaksana'] = $this->m_pegawai->get_pegawai_skpd($this->data['entry'][0]['skpd']);
					$this->data['pengikut'] = $this->m_pegawai->get_pegawai_skpd($this->data['entry'][0]['skpd']);
					$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->data['entry'][0]['skpd']);
					$this->data['rekening'] = $this->m_rekening->rekening_opd($this->data['entry'][0]['skpd']);
					$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
					$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
					$this->data['posisi_walikota'] = $this->m_camat->posisi_walikota($this->data['entry'][0]['skpd']);
					break;

				case "lurah":
					$this->data['pelaksana'] = $this->m_pegawai->get_pegawai_skpd($this->data['entry'][0]['skpd']);
					$this->data['pengikut'] = $this->m_pegawai->get_pegawai_skpd($this->data['entry'][0]['skpd']);
					$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->data['entry'][0]['skpd']);
					$this->data['rekening'] = $this->m_rekening->rekening_opd($this->data['entry'][0]['skpd']);
					$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
					$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
					$this->data['posisi_walikota'] = $this->m_lurah->posisi_walikota($this->data['entry'][0]['skpd']);
					break;

				case "staff_dprd":
					$this->data['pelaksana'] = $this->m_pegawai->get_pegawai_skpd($this->data['entry'][0]['skpd']);
					$this->data['pengikut'] = $this->m_pegawai->get_pegawai_skpd($this->data['entry'][0]['skpd']);
					$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->data['entry'][0]['skpd']);
					$this->data['rekening'] = $this->m_rekening->rekening_opd($this->data['entry'][0]['skpd']);
					$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
					$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
					$this->data['posisi_kadprd'] = $this->m_staff_dprd->posisi_kadprd();
					break;

				case "staff_camat":
					$this->data['pelaksana'] = $this->m_pegawai->get_pegawai_skpd($this->data['entry'][0]['skpd']);
					$this->data['pengikut'] = $this->m_pegawai->get_pegawai_skpd($this->data['entry'][0]['skpd']);
					$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->data['entry'][0]['skpd']);
					$this->data['rekening'] = $this->m_rekening->rekening_opd($this->data['entry'][0]['skpd']);
					$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
					$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
					$this->data['posisi_kaopd'] = $this->m_staff_camat->posisi_kaopd($this->data['entry'][0]['skpd']);
					break;

				case "staff_lurah":
					$this->data['pelaksana'] = $this->m_pegawai->get_pegawai_skpd($this->data['entry'][0]['skpd']);
					$this->data['pengikut'] = $this->m_pegawai->get_pegawai_skpd($this->data['entry'][0]['skpd']);
					$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->data['entry'][0]['skpd']);
					$this->data['rekening'] = $this->m_rekening->rekening_opd($this->data['entry'][0]['skpd']);
					$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
					$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
					$this->data['posisi_kaopd'] = $this->m_staff_lurah->posisi_kaopd($this->data['entry'][0]['skpd']);
					break;

				case "walikota":
					$this->data['pelaksana'] = $this->m_sekda->pimpinan();
					$this->data['pengikut'] = $this->m_sekda->pegawaiall();
					$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->data['entry'][0]['skpd']);
					$this->data['rekening'] = $this->m_rekening->rekening_opd($this->data['entry'][0]['skpd']);
					$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
					$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
					$this->data['posisi_walikota'] = $this->m_sekda->posisi_walikota();
					break;

				case "staff_setda":
					$staff_sekda = $this->m_relasi_sekda->getsubbagian($this->ion_auth->user()->row()->id);
					if ($staff_sekda) {
						$this->data['pelaksana'] = $this->m_sekda->pegawai_setda($this->data['entry'][0]['skpd'], $staff_sekda[0]['bagian_id']);
						$this->data['pengikut'] = $this->m_sekda->pegawai_setda($this->data['entry'][0]['skpd'], $staff_sekda[0]['bagian_id']);
						$this->data['anggaran'] = $this->m_sekda->anggaran_setda($this->data['entry'][0]['skpd'], $staff_sekda[0]['bagian_id']);
					} else {
						$this->data['pelaksana'] = $this->m_sekda->pegawai($this->data['entry'][0]['skpd']);
						$this->data['pengikut'] = $this->m_sekda->pegawai($this->data['entry'][0]['skpd']);
						$this->data['anggaran'] = $this->m_sekda->anggaran($this->data['entry'][0]['skpd']);
					}

					$this->data['rekening'] = $this->m_sekda->rekening($this->data['entry'][0]['skpd']);
					$this->data['provinsi'] = $this->m_sekda->get_provinsi();
					$this->data['kabupaten'] = $this->m_sekda->get_kabupaten();
					$this->data['posisi_walikota'] = $this->m_sekda->posisi_walikota();
					break;

				case "sekwan":
					$this->data['pelaksana'] = $this->m_sekwan->sekwan($this->data['entry'][0]['skpd']);
					$this->data['pengikut'] = $this->m_pegawai->get_pegawai_skpd($this->data['entry'][0]['skpd']);
					$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->data['entry'][0]['skpd']);
					$this->data['rekening'] = $this->m_rekening->rekening_opd($this->data['entry'][0]['skpd']);
					$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
					$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
					$this->data['posisi_walikota'] = $this->m_sekwan->posisi_walikota();
					break;

				case "kapus":
					$this->data['pelaksana'] = $this->m_kapus->kepala_opd($this->data['entry'][0]['skpd']);
					$this->data['pengikut'] = $this->m_pegawai->get_pegawai_skpd($this->data['entry'][0]['skpd']);
					$this->data['anggaran'] = $this->m_anggaran->anggaran_opd($this->data['entry'][0]['skpd']);
					$this->data['rekening'] = $this->m_rekening->rekening_opd($this->data['entry'][0]['skpd']);
					$this->data['provinsi'] = $this->m_provinsi->get_provinsi();
					$this->data['kabupaten'] = $this->m_kabupaten->get_kabupaten();
					$this->data['posisi_walikota'] = $this->m_kapus->posisi_walikota();
					break;
			}
			$this->render('telaah/list_telaah/update');
		}
	}

	//Update Data
	public function update()
	{
		$this->form_validation->set_rules('telaah_perihal', 'Perihal', 'required');
		$this->form_validation->set_rules('telaah_persoalan', 'Persoalan', 'required');
		$this->form_validation->set_rules('telaah_fakta', 'Fakta', 'required');
		$this->form_validation->set_rules('telaah_analisis', 'Analisis', 'required');
		$this->form_validation->set_rules('telaah_jenisangkutan', 'Jenis Angkutan', 'required');
		$this->form_validation->set_rules('telaah_angkutan', 'Angkutan', 'required');
		$this->form_validation->set_rules('telaah_tanggalberangkat', 'Tanggal Berangkat', 'required');
		$this->form_validation->set_rules('telaah_tanggalkembali', 'Tanggal Kembali', 'required');
		$this->form_validation->set_rules('telaah_hari', 'Lama Perjalanan (Hari)', 'required');
		$this->form_validation->set_rules('telaah_tempatberangkat', 'Tempat Berangkat', 'required');
		$this->form_validation->set_rules('telaah_domainperjalanan', 'Domain Perjalanan', 'required');
		$this->form_validation->set_rules('telaah_kantortujuan', 'Kantor Tujuan', 'required');
		$this->form_validation->set_rules('telaah_kegiatan', 'Kegiatan', 'required');
		$this->form_validation->set_rules('telaah_kategoriperjalanan', 'Kategori Perjalanan', 'required');
		$this->form_validation->set_rules('telaah_kecepatan', 'Telaah Kecepatan', 'required');
		$this->form_validation->set_rules('telaah_pelaksana', 'Pelaksana', 'required');
		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');

		$telaah_id = base64_encode($this->encrypt->encode($this->input->post('telaah_id'), $this->session->userdata('encrypt_key')));

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('notif2', 'Data Telaah Staf Gagal di Simpan !');
			redirect('telaah/list_telaah/update_view/' . $this->uri->segment(4) . '?telaah_id=' . $telaah_id);
		} else {
			$filename = $this->input->post('telaah_id');
			$config['upload_path'] = './upload/telaah/';
			$config['allowed_types'] = "gif|jpg|jpeg|png|pdf|doc|docx|xls|xlsx|ppt|pptx";
			$config['overwrite'] = "true";
			$config['max_size'] = "20000000";
			$config['max_width'] = "10000";
			$config['max_height'] = "10000";
			$config['file_name'] = '' . $filename;
			$this->upload->initialize($config);
			if (!$this->upload->do_upload()) {

				$data['telaah_id'] = $this->input->post('telaah_id');
				$data['telaah_perihal'] = $this->input->post('telaah_perihal');
				$data['telaah_persoalan'] = $this->input->post('telaah_persoalan');
				$data['telaah_fakta'] = $this->input->post('telaah_fakta');
				$data['telaah_analisis'] = $this->input->post('telaah_analisis');
				$data['telaah_jenisangkutan'] = $this->input->post('telaah_jenisangkutan');
				$data['telaah_angkutan'] = $this->input->post('telaah_angkutan');
				$data['telaah_tanggalberangkat'] = $this->input->post('telaah_tanggalberangkat');
				$data['telaah_tanggalkembali'] = $this->input->post('telaah_tanggalkembali');
				$data['telaah_hari'] = str_replace(".", "", $this->input->post('telaah_hari'));
				$data['telaah_tempatberangkat'] = $this->input->post('telaah_tempatberangkat');
				$data['telaah_domainperjalanan'] = $this->input->post('telaah_domainperjalanan');
				if ($data['telaah_domainperjalanan'] == 1) {
					$data['telaah_provinsitujuan'] = $this->input->post('telaah_provinsitujuan');
					$data['telaah_kotatujuan'] = $this->input->post('telaah_kotatujuan');
				} else if ($data['telaah_domainperjalanan'] == 2) {
					$data['telaah_provinsitujuan'] = 74;
					$data['telaah_kotatujuan'] = $this->input->post('telaah_kotatujuan2');
				} else if ($data['telaah_domainperjalanan'] == 3) {
					$data['telaah_provinsitujuan'] = 74;
					$data['telaah_kotatujuan'] = 7471;
				}

				$data['telaah_kantortujuan'] = $this->input->post('telaah_kantortujuan');
				$data['telaah_kegiatan'] = $this->input->post('telaah_kegiatan');
				$data['telaah_kategoriperjalanan'] = $this->input->post('telaah_kategoriperjalanan');
				$data['telaah_kecepatan'] = $this->input->post('telaah_kecepatan');
				$data['telaah_pelaksana'] = $this->input->post('telaah_pelaksana');
				if ($this->input->post('telaah_sekretariat') == 1) {
					$data['telaah_sekretariat'] = $this->input->post('telaah_sekretariat');
				} else {
					$data['telaah_sekretariat'] = 0;
				}
				$data['user_id'] = $this->ion_auth->user()->row()->user_id;

				$this->m_telaah->update($data);

				$this->m_pengikut->delete($this->input->post('telaah_id'));
				$telaah_pengikut = $this->input->post('telaah_pengikut');
				$jumlah = is_array($telaah_pengikut) ? count($telaah_pengikut) : 0;

				for ($i = 0; $i < $jumlah; $i++) {
					$data2['telaah_id'] = $this->input->post('telaah_id');
					$data2['pegawai_id'] = $this->input->post('telaah_pengikut')[$i];
					$this->m_pengikut->create($data2);

					## Create SPPD dan SPT Pengikut
					switch ($this->uri->segment(4)) {
						case "esselon":
							$this->cetak_spd($this->input->post('telaah_id'), 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						// $this->cetak_spt($last_id,$this->uri->segment(4),2,$this->input->post('telaah_pengikut')[$i]); break;
						case "kadis":
							$this->cetak_spd($this->input->post('telaah_id'), 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						// $this->cetak_spt($last_id,$this->uri->segment(4),2,$this->input->post('telaah_pengikut')[$i]); break;
						case "dprd":
							$this->cetak_spd($this->input->post('telaah_id'), 'dprd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "sekda":
							$this->cetak_spd($this->input->post('telaah_id'), 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "camat":
							$this->cetak_spd($this->input->post('telaah_id'), 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "lurah":
							$this->cetak_spd($this->input->post('telaah_id'), 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "staff_dprd":
							$this->cetak_spd($this->input->post('telaah_id'), 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "staff_camat":
							$this->cetak_spd($this->input->post('telaah_id'), 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "staff_lurah":
							$this->cetak_spd($this->input->post('telaah_id'), 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "walikota":
							$this->cetak_spd($this->input->post('telaah_id'), 'walikota', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "staff_setda":
							$this->cetak_spd($this->input->post('telaah_id'), 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "sekwan":
							$this->cetak_spd($this->input->post('telaah_id'), 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "kapus":
							$this->cetak_spd($this->input->post('telaah_id'), 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
					}
				}

				$prov_tujuan2 = $this->input->post('telaah_provinsitujuan2');
				$jml_lokasi_tujuan = is_array($prov_tujuan2) ? count($prov_tujuan2) : 0;
				for ($i = 0; $i < $jml_lokasi_tujuan; $i++) {
					$data3['telaah_id'] = $this->input->post('telaah_id');
					$data3['provinsi_id'] = $this->input->post('telaah_provinsitujuan2')[$i];
					$data3['kabkot_id'] = $this->input->post('telaah_kotatujuan3')[$i];
					$this->m_lokasi_tujuan->create($data3);
				}

				## Create SPPD dan SPT Pelaksana
				switch ($this->uri->segment(4)) {
					case "esselon":
						$this->cetak_spd($this->input->post('telaah_id'), 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "kadis":
						$this->cetak_spd($this->input->post('telaah_id'), 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "dprd":
						$this->cetak_spd($this->input->post('telaah_id'), 'dprd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt_dprd($this->input->post('telaah_id'), $this->input->post('telaah_pelaksana'));
						break;
					case "sekda":
						$this->cetak_spd($this->input->post('telaah_id'), 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 2, $this->input->post('telaah_pelaksana'));
						break;
					case "camat":
						$this->cetak_spd($this->input->post('telaah_id'), 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "lurah":
						$this->cetak_spd($this->input->post('telaah_id'), 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "staff_dprd":
						$this->cetak_spd($this->input->post('telaah_id'), 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "staff_camat":
						$this->cetak_spd($this->input->post('telaah_id'), 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 2, $this->input->post('telaah_pelaksana'));
						break;
					case "staff_lurah":
						$this->cetak_spd($this->input->post('telaah_id'), 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 2, $this->input->post('telaah_pelaksana'));
						break;
					case "walikota":
						$this->cetak_spd($this->input->post('telaah_id'), 'walikota', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "staff_setda":
						$this->cetak_spd($this->input->post('telaah_id'), 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 2, $this->input->post('telaah_pelaksana'));
						break;
					case "sekwan":
						$this->cetak_spd($this->input->post('telaah_id'), 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "kapus":
						$this->cetak_spd($this->input->post('telaah_id'), 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
				}
				// perbaikan

				if ($this->input->post('telaah_status') == 5) {
					switch ($this->input->post('telaah_kategori')) {
						case "1":
							$table = 'table_timeline1';
							$disposisi = array('timeline_kabid_id', 'timeline_sekdis_id', 'timeline_kadis_id');
							break;
						case "2":
							$table = 'table_timeline2';
							$disposisi = array('timeline_sekdis_id', 'timeline_kadis_id', 'timeline_sekda_id', 'timeline_walikota_id');
							break;
						case "3":
							$table = 'table_timeline3';
							$disposisi = array('timeline_kasubid_id', 'timeline_sekwan_id', 'timeline_kadprd_id');
							break;
						case "4":
							$table = 'table_timeline4';
							$disposisi = array('timeline_kabag_id', 'timeline_asisten_id', 'timeline_sekda_id', 'timeline_walikota_id');
							break;
						case "5":
							$table = 'table_timeline5';
							$disposisi = array('timeline_sekcam_id', 'timeline_camat_id', 'timeline_sekda_id', 'timeline_walikota_id');
							break;
						case "6":
							$table = 'table_timeline6';
							$disposisi = array('timeline_kabag_id', 'timeline_sekwan_id');
							break;
						case "7":
							$table = 'table_timeline7';
							$disposisi = array('timeline_lurah_id', 'timeline_sekcam_id', 'timeline_camat_id');
							break;
						case "8":
							$table = 'table_timeline8';
							$disposisi = array('timeline_kabag_id', 'timeline_sekda_id', 'timeline_walikota_id');
							break;
						case "9":
							$table = 'table_timeline9';
							$disposisi = array('timeline_kabag_id', 'timeline_asisten_id', 'timeline_sekda_id');
							break;
						case "10":
							$table = 'table_timeline10';
							$disposisi = array('timeline_kabag_id', 'timeline_sekwan_id', 'timeline_sekda_id', 'timeline_walikota_id');
							break;
						case "11":
							$table = 'table_timeline11';
							$disposisi = array('timeline_kapus_id');
							break;
					}

					for ($i = 0; $i < count($disposisi); $i++) {
						$text = str_replace("_id", "_disposisi", $disposisi[$i]);
						$this->m_telaah->update_perbaikan($table, $this->input->post('telaah_id'), $disposisi[$i], $text);
					}

					$data4['telaah_id'] = $this->input->post('telaah_id');
					$data4['telaah_perbaikan'] = 1;
					$data4['telaah_status'] = 1;
					$this->m_telaah->update($data4);
				}


				$log['kode_log_action'] = "53";
				$log['action'] = "INSERT";
				$log['kode_log_action_table'] = "15";
				$log['action_table'] = "TELAAH STAFF";
				$this->m_log->create($log);

				$this->session->set_flashdata('notif', 'Data Telaah Staf Di Simpan !');

				//Redirect
				redirect('telaah/list_telaah/update_view/' . $this->uri->segment(4) . '?telaah_id=' . $telaah_id);
			} else {

				$dat = $this->upload->data();

				$data['telaah_id'] = $this->input->post('telaah_id');
				$data['telaah_perihal'] = $this->input->post('telaah_perihal');
				$data['telaah_persoalan'] = $this->input->post('telaah_persoalan');
				$data['telaah_fakta'] = $this->input->post('telaah_fakta');
				$data['telaah_analisis'] = $this->input->post('telaah_analisis');
				$data['telaah_jenisangkutan'] = $this->input->post('telaah_jenisangkutan');
				$data['telaah_angkutan'] = $this->input->post('telaah_angkutan');
				$data['telaah_tanggalberangkat'] = $this->input->post('telaah_tanggalberangkat');
				$data['telaah_tanggalkembali'] = $this->input->post('telaah_tanggalkembali');
				$data['telaah_hari'] = str_replace(".", "", $this->input->post('telaah_hari'));
				$data['telaah_tempatberangkat'] = $this->input->post('telaah_tempatberangkat');
				$data['telaah_domainperjalanan'] = $this->input->post('telaah_domainperjalanan');
				if ($data['telaah_domainperjalanan'] == 1) {
					$data['telaah_provinsitujuan'] = $this->input->post('telaah_provinsitujuan');
					$data['telaah_kotatujuan'] = $this->input->post('telaah_kotatujuan');
				} else if ($data['telaah_domainperjalanan'] == 2) {
					$data['telaah_provinsitujuan'] = 74;
					$data['telaah_kotatujuan'] = $this->input->post('telaah_kotatujuan2');
				} else if ($data['telaah_domainperjalanan'] == 3) {
					$data['telaah_provinsitujuan'] = 74;
					$data['telaah_kotatujuan'] = 7471;
				}

				$data['telaah_kantortujuan'] = $this->input->post('telaah_kantortujuan');
				$data['telaah_kegiatan'] = $this->input->post('telaah_kegiatan');
				$data['telaah_kategoriperjalanan'] = $this->input->post('telaah_kategoriperjalanan');
				$data['telaah_kecepatan'] = $this->input->post('telaah_kecepatan');
				$data['telaah_pelaksana'] = $this->input->post('telaah_pelaksana');
				$data['user_id'] = $this->ion_auth->user()->row()->user_id;
				$data['telaah_dokumenpendukung'] = $dat['file_name'];

				$this->m_telaah->update($data);

				$this->m_pengikut->delete($this->input->post('telaah_id'));
				$telaah_pengikut = $this->input->post('telaah_pengikut');
				$jumlah = is_array($telaah_pengikut) ? count($telaah_pengikut) : 0;

				for ($i = 0; $i < $jumlah; $i++) {
					$data2['telaah_id'] = $this->input->post('telaah_id');
					$data2['pegawai_id'] = $this->input->post('telaah_pengikut')[$i];
					$this->m_pengikut->create($data2);

					## Create SPPD dan SPT Pengikut
					switch ($this->uri->segment(4)) {
						case "esselon":
							$this->cetak_spd($this->input->post('telaah_id'), 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						// $this->cetak_spt($last_id,$this->uri->segment(4),2,$this->input->post('telaah_pengikut')[$i]); break;
						case "kadis":
							$this->cetak_spd($this->input->post('telaah_id'), 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						// $this->cetak_spt($last_id,$this->uri->segment(4),2,$this->input->post('telaah_pengikut')[$i]); break;
						case "dprd":
							$this->cetak_spd($this->input->post('telaah_id'), 'dprd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "sekda":
							$this->cetak_spd($this->input->post('telaah_id'), 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "camat":
							$this->cetak_spd($this->input->post('telaah_id'), 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "lurah":
							$this->cetak_spd($this->input->post('telaah_id'), 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "staff_dprd":
							$this->cetak_spd($this->input->post('telaah_id'), 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "staff_camat":
							$this->cetak_spd($this->input->post('telaah_id'), 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "staff_lurah":
							$this->cetak_spd($this->input->post('telaah_id'), 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "walikota":
							$this->cetak_spd($this->input->post('telaah_id'), 'walikota', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "staff_setda":
							$this->cetak_spd($this->input->post('telaah_id'), 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "sekwan":
							$this->cetak_spd($this->input->post('telaah_id'), 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
						case "kapus":
							$this->cetak_spd($this->input->post('telaah_id'), 'opd', 2, $this->input->post('telaah_pengikut')[$i]);
							break;
					}
				}

				$prov_tujuan2 = $this->input->post('telaah_provinsitujuan2');
				$jml_lokasi_tujuan = is_array($prov_tujuan2) ? count($prov_tujuan2) : 0;
				for ($i = 0; $i < $jml_lokasi_tujuan; $i++) {
					$data3['telaah_id'] = $this->input->post('telaah_id');
					$data3['provinsi_id'] = $this->input->post('telaah_provinsitujuan2')[$i];
					$data3['kabkot_id'] = $this->input->post('telaah_kotatujuan3')[$i];
					$this->m_lokasi_tujuan->create($data3);
				}

				## Create SPPD dan SPT Pelaksana
				switch ($this->uri->segment(4)) {
					case "esselon":
						$this->cetak_spd($this->input->post('telaah_id'), 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "kadis":
						$this->cetak_spd($this->input->post('telaah_id'), 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "dprd":
						$this->cetak_spd($this->input->post('telaah_id'), 'dprd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt_dprd($this->input->post('telaah_id'), $this->input->post('telaah_pelaksana'));
						break;
					case "sekda":
						$this->cetak_spd($this->input->post('telaah_id'), 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 2, $this->input->post('telaah_pelaksana'));
						break;
					case "camat":
						$this->cetak_spd($this->input->post('telaah_id'), 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "lurah":
						$this->cetak_spd($this->input->post('telaah_id'), 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "staff_dprd":
						$this->cetak_spd($this->input->post('telaah_id'), 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "staff_camat":
						$this->cetak_spd($this->input->post('telaah_id'), 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 2, $this->input->post('telaah_pelaksana'));
						break;
					case "staff_lurah":
						$this->cetak_spd($this->input->post('telaah_id'), 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 2, $this->input->post('telaah_pelaksana'));
						break;
					case "walikota":
						$this->cetak_spd($this->input->post('telaah_id'), 'walikota', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "staff_setda":
						$this->cetak_spd($this->input->post('telaah_id'), 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 2, $this->input->post('telaah_pelaksana'));
						break;
					case "sekwan":
						$this->cetak_spd($this->input->post('telaah_id'), 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
					case "kapus":
						$this->cetak_spd($this->input->post('telaah_id'), 'opd', 1, $this->input->post('telaah_pelaksana'));
						$this->cetak_spt($this->input->post('telaah_id'), $this->uri->segment(4), 1, $this->input->post('telaah_pelaksana'));
						break;
				}
				// perbaikan

				if ($this->input->post('telaah_status') == 5) {
					switch ($this->input->post('telaah_kategori')) {
						case "1":
							$table = 'table_timeline1';
							$disposisi = array('timeline_kabid_id', 'timeline_sekdis_id', 'timeline_kadis_id');
							break;
						case "2":
							$table = 'table_timeline2';
							$disposisi = array('timeline_sekdis_id', 'timeline_kadis_id', 'timeline_sekda_id', 'timeline_walikota_id');
							break;
						case "3":
							$table = 'table_timeline3';
							$disposisi = array('timeline_kasubid_id', 'timeline_sekwan_id', 'timeline_kadprd_id');
							break;
						case "4":
							$table = 'table_timeline4';
							$disposisi = array('timeline_kabag_id', 'timeline_asisten_id', 'timeline_sekda_id', 'timeline_walikota_id');
							break;
						case "5":
							$table = 'table_timeline5';
							$disposisi = array('timeline_sekcam_id', 'timeline_camat_id', 'timeline_sekda_id', 'timeline_walikota_id');
							break;
						case "6":
							$table = 'table_timeline6';
							$disposisi = array('timeline_kabag_id', 'timeline_sekwan_id');
							break;
						case "7":
							$table = 'table_timeline7';
							$disposisi = array('timeline_lurah_id', 'timeline_sekcam_id', 'timeline_camat_id');
							break;
						case "8":
							$table = 'table_timeline8';
							$disposisi = array('timeline_kabag_id', 'timeline_sekda_id', 'timeline_walikota_id');
							break;
						case "9":
							$table = 'table_timeline9';
							$disposisi = array('timeline_kabag_id', 'timeline_asisten_id', 'timeline_sekda_id');
							break;
						case "10":
							$table = 'table_timeline10';
							$disposisi = array('timeline_kabag_id', 'timeline_sekwan_id', 'timeline_sekda_id', 'timeline_walikota_id');
							break;
						case "11":
							$table = 'table_timeline11';
							$disposisi = array('timeline_kapus_id');
							break;
					}

					for ($i = 0; $i < count($disposisi); $i++) {
						$text = str_replace("_id", "_disposisi", $disposisi[$i]);
						$this->m_telaah->update_perbaikan($table, $this->input->post('telaah_id'), $disposisi[$i], $text);
					}

					$data4['telaah_id'] = $this->input->post('telaah_id');
					$data4['telaah_perbaikan'] = 1;
					$data4['telaah_status'] = 1;
					$this->m_telaah->update($data4);
				}

				$log['kode_log_action'] = "53";
				$log['action'] = "INSERT";
				$log['kode_log_action_table'] = "15";
				$log['action_table'] = "TELAAH STAFF";
				$this->m_log->create($log);

				$this->session->set_flashdata('notif', 'Data Telaah Staf Di Simpan !');

				//Redirect
				redirect('telaah/list_telaah/update_view/' . $this->uri->segment(4) . '?telaah_id=' . $telaah_id);
			}
		}
	}

	//Delete Lokasi Perjalanan Jika Lokasi Lebih Dari 1
	public function delete_location()
	{
		$lokasi_tujuan_id = $this->encrypt->decode(base64_decode($this->input->get('lokasi_tujuan_id')), $this->session->userdata('encrypt_key'));

		$this->m_lokasi_tujuan->delete($lokasi_tujuan_id);

		$this->session->set_flashdata('notif', 'Data Lokasi Tujuan Di Hapus !');
		redirect('telaah/list_telaah/update_view/' . $this->uri->segment(4) . '?telaah_id=' . $this->input->get('telaah_id'));
	}

	//View Detail Data
	public function detail()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));

		//Get data
		switch ($this->uri->segment(4)) {
			case "esselon":
				$this->data['entry'] =  $this->m_esselon->get($telaah_id);
				break;
			case "puskesmas":
				$this->data['entry'] =  $this->m_esselon->get($telaah_id);
				break;
			case "kadis":
				$this->data['entry'] =  $this->m_kadis->get($telaah_id);
				break;
			case "dprd":
				$this->data['entry'] =  $this->m_dprd->get($telaah_id);
				break;
			case "sekda":
				$this->data['entry'] =  $this->m_sekda->get($telaah_id);
				break;
			case "camat":
				$this->data['entry'] =  $this->m_camat->get($telaah_id);
				break;
			case "lurah":
				$this->data['entry'] =  $this->m_lurah->get($telaah_id);
				break;
			case "staff_dprd":
				$this->data['entry'] =  $this->m_staff_dprd->get($telaah_id);
				break;
			case "staff_camat":
				$this->data['entry'] =  $this->m_staff_camat->get($telaah_id);
				break;
			case "staff_lurah":
				$this->data['entry'] =  $this->m_staff_lurah->get($telaah_id);
				break;
			case "walikota":
				$this->data['entry'] =  $this->m_sekda->getWalikota($telaah_id);
				break;
			case "staff_setda":
				$this->data['entry'] =  $this->m_sekda->get($telaah_id);
				break;
			case "sekwan":
				$this->data['entry'] =  $this->m_sekwan->get($telaah_id);
				break;
			case "kapus":
				$this->data['entry'] =  $this->m_kapus->get($telaah_id);
				break;
		}

		if (!isset($this->data['entry'][0]) || $this->data['entry'][0] == "") {

			//Redirect
			switch ($this->uri->segment(4)) {
				case "esselon":
					redirect('telaah/list_telaah/index/esselon');
					break;
				case "puskesmas":
					redirect('telaah/list_telaah/index/puskesmas');
					break;
				case "kadis":
					redirect('telaah/list_telaah/index/kadis');
					break;
				case "dprd":
					redirect('telaah/list_telaah/index/dprd');
					break;
				case "sekda":
					redirect('telaah/list_telaah/index/sekda');
					break;
				case "camat":
					redirect('telaah/list_telaah/index/camat');
					break;
				case "lurah":
					redirect('telaah/list_telaah/index/lurah');
					break;
				case "staff_dprd":
					redirect('telaah/list_telaah/index/staff_dprd');
					break;
				case "staff_camat":
					redirect('telaah/list_telaah/index/staff_camat');
					break;
				case "staff_lurah":
					redirect('telaah/list_telaah/index/staff_lurah');
					break;
				case "walikota":
					redirect('telaah/list_telaah/index/walikota');
					break;
				case "staff_setda":
					redirect('telaah/list_telaah/index/staff_setda');
					break;
				case "sekwan":
					redirect('telaah/list_telaah/index/sekwan');
					break;
				case "kapus":
					redirect('telaah/list_telaah/index/kapus');
					break;
			}
		} else {

			// Get pengikut 
			if ($this->uri->segment(4) == "dprd") {
				$this->data['pengikut'] =  $this->m_pengikut->data_dprd($telaah_id);
			} else {
				$this->data['pengikut'] =  $this->m_pengikut->data($telaah_id);
			}

			// Get TimeLine
			switch ($this->uri->segment(4)) {
				case "esselon":
					$timeline =  $this->m_esselon->getTimeline1($telaah_id);
					if ($this->data['entry'][0]['telaah_sekretariat'] == 1) {
						$this->data['disposisi1'] = $timeline[0]['timeline_sekdis_id'];
						$this->data['disposisi2'] = $timeline[0]['timeline_kadis_id'];
						$this->data['disposisi3'] = "";
						$this->data['disposisi4'] = "";

						$this->data['nama_disposisi1'] = "SEKRETARIS OPD";
						$this->data['nama_disposisi2'] = "KEPALA OPD";
					} else {
						$this->data['disposisi1'] = $timeline[0]['timeline_kabid_id'];
						$this->data['disposisi2'] = $timeline[0]['timeline_sekdis_id'];
						$this->data['disposisi3'] = $timeline[0]['timeline_kadis_id'];
						$this->data['disposisi4'] = "";

						$this->data['nama_disposisi1'] = "KABID / IRBAN / KABAG";
						$this->data['nama_disposisi2'] = "SEKRETARIS OPD";
						$this->data['nama_disposisi3'] = "KEPALA OPD";
					}
					break;
				case "puskesmas":
					$timeline =  $this->m_esselon->getTimeline1($telaah_id);
					if ($this->data['entry'][0]['telaah_sekretariat'] == 1) {
						$this->data['disposisi1'] = $timeline[0]['timeline_sekdis_id'];
						$this->data['disposisi2'] = $timeline[0]['timeline_kadis_id'];
						$this->data['disposisi3'] = "";
						$this->data['disposisi4'] = "";

						$this->data['nama_disposisi1'] = "SEKRETARIS OPD";
						$this->data['nama_disposisi2'] = "KEPALA OPD";
					} else {
						$this->data['disposisi1'] = $timeline[0]['timeline_kabid_id'];
						$this->data['disposisi2'] = $timeline[0]['timeline_sekdis_id'];
						$this->data['disposisi3'] = $timeline[0]['timeline_kadis_id'];
						$this->data['disposisi4'] = "";

						$this->data['nama_disposisi1'] = "KABID / IRBAN / KABAG";
						$this->data['nama_disposisi2'] = "SEKRETARIS OPD";
						$this->data['nama_disposisi3'] = "KEPALA OPD";
					}
					break;
				case "kadis":
					$timeline =  $this->m_kadis->getTimeline2($telaah_id);
					if ($this->data['entry'][0]['telaah_domainperjalanan'] == 3 || $this->data['entry'][0]['telaah_domainperjalanan'] == 4) {
						$this->data['disposisi1'] = $timeline[0]['timeline_sekdis_id'];
						$this->data['disposisi2'] = $timeline[0]['timeline_kadis_id'];
						$this->data['disposisi3'] = "";
						$this->data['disposisi4'] = "";

						$this->data['nama_disposisi1'] = "SEKRETARIS OPD";
						$this->data['nama_disposisi2'] = "KEPALA OPD";
					} else {
						$this->data['disposisi1'] = $timeline[0]['timeline_sekdis_id'];
						$this->data['disposisi2'] = $timeline[0]['timeline_kadis_id'];
						$this->data['disposisi3'] = $timeline[0]['timeline_sekda_id'];
						$this->data['disposisi4'] = $timeline[0]['timeline_walikota_id'];

						$this->data['nama_disposisi1'] = "SEKRETARIS OPD";
						$this->data['nama_disposisi2'] = "KEPALA OPD";
						$this->data['nama_disposisi3'] = "SEKDA";
						$this->data['nama_disposisi4'] = "WALIKOTA";
					}
					break;
				case "dprd":
					$timeline =  $this->m_dprd->getTimeline($telaah_id);
					$this->data['disposisi1'] = $timeline[0]['timeline_kasubid_id'];
					$this->data['disposisi2'] = $timeline[0]['timeline_sekwan_id'];
					$this->data['disposisi3'] = $timeline[0]['timeline_kadprd_id'];
					$this->data['disposisi4'] = "";

					$this->data['nama_disposisi1'] = "KABAG";
					$this->data['nama_disposisi2'] = "SEKRETARIS DEWAN";
					$this->data['nama_disposisi3'] = "PIMPINAN DPRD";
					break;
				case "sekda":
					$timeline =  $this->m_sekda->getTimeline($telaah_id);
					$this->data['disposisi1'] = $timeline[0]['timeline_kabag_id'];
					$this->data['disposisi2'] = $timeline[0]['timeline_asisten_id'];
					$this->data['disposisi3'] = $timeline[0]['timeline_sekda_id'];
					$this->data['disposisi4'] = $timeline[0]['timeline_walikota_id'];

					$this->data['nama_disposisi1'] = "KABAG";
					$this->data['nama_disposisi2'] = "ASISTEN/KEPALA OPD";
					$this->data['nama_disposisi3'] = "SEKDA";
					$this->data['nama_disposisi4'] = "WALIKOTA";
					break;
				case "camat":
					$timeline =  $this->m_camat->getTimeline($telaah_id);
					$this->data['disposisi1'] = $timeline[0]['timeline_sekcam_id'];
					$this->data['disposisi2'] = $timeline[0]['timeline_camat_id'];
					$this->data['disposisi3'] = $timeline[0]['timeline_sekda_id'];
					$this->data['disposisi4'] = $timeline[0]['timeline_walikota_id'];

					$this->data['nama_disposisi1'] = "SEKCAM";
					$this->data['nama_disposisi2'] = "CAMAT";
					$this->data['nama_disposisi3'] = "SEKDA";
					$this->data['nama_disposisi4'] = "WALIKOTA";
					break;
				case "lurah":
					$timeline =  $this->m_lurah->getTimeline($telaah_id);
					$this->data['disposisi1'] = $timeline[0]['timeline_sekcam_id'];
					$this->data['disposisi2'] = $timeline[0]['timeline_camat_id'];
					$this->data['disposisi3'] = $timeline[0]['timeline_sekda_id'];
					$this->data['disposisi4'] = "";

					$this->data['nama_disposisi1'] = "SEKCAM";
					$this->data['nama_disposisi2'] = "CAMAT";
					$this->data['nama_disposisi3'] = "SEKDA";
					$this->data['nama_disposisi4'] = "";
					break;
				case "staff_dprd":
					$timeline =  $this->m_staff_dprd->getTimeline($telaah_id);
					$this->data['disposisi1'] = $timeline[0]['timeline_kabag_id'];
					$this->data['disposisi2'] = $timeline[0]['timeline_sekwan_id'];
					$this->data['disposisi3'] = "";
					$this->data['disposisi4'] = "";

					$this->data['nama_disposisi1'] = "KABAG";
					$this->data['nama_disposisi2'] = "SEKRETARIS DEWAN";
					break;
				case "staff_camat":
					$timeline =  $this->m_staff_camat->getTimeline($telaah_id);
					$this->data['disposisi1'] = $timeline[0]['timeline_lurah_id'];
					$this->data['disposisi2'] = $timeline[0]['timeline_sekcam_id'];
					$this->data['disposisi3'] = $timeline[0]['timeline_camat_id'];
					$this->data['disposisi4'] = "";

					$this->data['nama_disposisi1'] = "KASUBAG";
					$this->data['nama_disposisi2'] = "SEKCAM";
					$this->data['nama_disposisi3'] = "CAMAT";
					break;
				case "staff_lurah":
					$timeline =  $this->m_staff_lurah->getTimeline($telaah_id);
					$this->data['disposisi1'] = $timeline[0]['timeline_lurah_id'];
					$this->data['disposisi2'] = $timeline[0]['timeline_sekcam_id'];
					$this->data['disposisi3'] = $timeline[0]['timeline_camat_id'];
					$this->data['disposisi4'] = "";

					$this->data['nama_disposisi1'] = "LURAH";
					$this->data['nama_disposisi2'] = "SEKCAM";
					$this->data['nama_disposisi3'] = "CAMAT";
					break;
				case "walikota":
					$timeline =  $this->m_sekda->getTimeline8($telaah_id);
					$this->data['disposisi1'] = $timeline[0]['timeline_kabag_id'];
					$this->data['disposisi2'] = $timeline[0]['timeline_sekda_id'];
					$this->data['disposisi3'] = $timeline[0]['timeline_walikota_id'];
					$this->data['disposisi4'] = "";

					$this->data['nama_disposisi1'] = "KABAG";
					$this->data['nama_disposisi2'] = "SEKDA";
					$this->data['nama_disposisi3'] = "WALIKOTA";
					break;
				case "staff_setda":
					$timeline =  $this->m_sekda->getTimeline9($telaah_id);
					$this->data['disposisi1'] = $timeline[0]['timeline_kabag_id'];
					$this->data['disposisi2'] = $timeline[0]['timeline_asisten_id'];
					$this->data['disposisi3'] = $timeline[0]['timeline_sekda_id'];
					$this->data['disposisi4'] = "";

					$this->data['nama_disposisi1'] = "KABAG";
					$this->data['nama_disposisi2'] = "ASISTEN/KEPALA OPD";
					$this->data['nama_disposisi3'] = "SEKDA";
					break;
				case "sekwan":
					$timeline =  $this->m_sekwan->getTimeline10($telaah_id);
					$this->data['disposisi1'] = $timeline[0]['timeline_kabag_id'];
					$this->data['disposisi2'] = $timeline[0]['timeline_sekwan_id'];
					$this->data['disposisi3'] = $timeline[0]['timeline_sekda_id'];
					$this->data['disposisi4'] = $timeline[0]['timeline_walikota_id'];

					$this->data['nama_disposisi1'] = "KABAG";
					$this->data['nama_disposisi2'] = "SEKWAN";
					$this->data['nama_disposisi3'] = "SEKDA";
					$this->data['nama_disposisi4'] = "WALIKOTA";
					break;
				case "kapus":
					$timeline =  $this->m_kapus->getTimeline2($telaah_id);
					$this->data['disposisi1'] = $timeline[0]['timeline_kapus_id'];
					$this->data['disposisi2'] = "";
					$this->data['disposisi3'] = "";
					$this->data['disposisi4'] = "";

					$this->data['nama_disposisi1'] = "KEPALA PUSKESMAS";
					break;
			}
			$this->render('telaah/detail');
		}
	}

	//View Laporan
	public function laporan()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$this->data['telaah_id'] =  $telaah_id;
		$this->data['data'] =  $this->m_telaah->get($telaah_id);
		$this->render('telaah/list_telaah/laporan');
	}

	//Cek Timeline yang memiliki telaah kecepatan 1
	public function cek_timeline()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));

		switch ($this->uri->segment(4)) {
			case "esselon":
				$timeline						= $this->m_esselon->getTimeline1($telaah_id);
				$this->data['data_telaah']	= $this->m_esselon->get($telaah_id);

				$this->data['label_nama1'] 	= "Nama Kabid :";
				$this->data['label_nama2'] 	= "Nama Sekdis :";
				$this->data['label_nama3'] 	= "Nama Kadis :";

				$this->data['nama1'] 			= $timeline[0]['timeline_kabid_name'];
				$this->data['nama2'] 			= $timeline[0]['timeline_sekdis_name'];
				$this->data['nama3'] 			= $timeline[0]['timeline_kadis_name'];
				$this->data['nama4'] 			= "";

				$this->data['tanggal1'] 		= $timeline[0]['timeline_kabid_date'];
				$this->data['tanggal2'] 		= $timeline[0]['timeline_sekdis_date'];
				$this->data['tanggal3'] 		= $timeline[0]['timeline_kadis_date'];
				$this->data['tanggal4'] 		= "";

				$this->data['input_nama1'] 	= "timeline_kabid_disposisi";
				$this->data['input_nama2'] 	= "timeline_sekdis_disposisi";
				$this->data['input_nama3'] 	= "timeline_kadis_disposisi";

				$this->data['timeline1'] 		= $timeline[0]['timeline_kabid_disposisi'];
				$this->data['timeline2'] 		= $timeline[0]['timeline_sekdis_disposisi'];
				$this->data['timeline3'] 		= $timeline[0]['timeline_kadis_disposisi'];
				$this->data['timeline4'] 		= "";

				break;

			case "kadis":
				$timeline 					= $this->m_kadis->getTimeline2($telaah_id);
				$this->data['data_telaah']	= $this->m_kadis->get($telaah_id);

				$this->data['label_nama1'] 	= "Nama Sekdis :";
				$this->data['label_nama2'] 	= "Nama Kadis :";
				$this->data['label_nama3'] 	= "Nama Sekda :";

				$this->data['nama1'] 			= $timeline[0]['timeline_sekdis_name'];
				$this->data['nama2'] 			= $timeline[0]['timeline_kadis_name'];
				$this->data['nama3'] 			= $timeline[0]['timeline_sekda_name'];
				$this->data['nama4'] 			= "";

				$this->data['tanggal1'] 		= $timeline[0]['timeline_sekdis_date'];
				$this->data['tanggal2'] 		= $timeline[0]['timeline_kadis_date'];
				$this->data['tanggal3'] 		= $timeline[0]['timeline_sekda_date'];
				$this->data['tanggal4'] 		= "";

				$this->data['input_nama1'] 	= "timeline_sekdis_disposisi";
				$this->data['input_nama2'] 	= "timeline_kadis_disposisi";
				$this->data['input_nama3'] 	= "timeline_sekda_disposisi";

				$this->data['timeline1'] 		= $timeline[0]['timeline_sekdis_disposisi'];
				$this->data['timeline2'] 		= $timeline[0]['timeline_kadis_disposisi'];
				$this->data['timeline3'] 		= $timeline[0]['timeline_sekda_disposisi'];
				$this->data['timeline4'] 		= "";

				break;
			case "dprd":
				$timeline 	 				= $this->m_dprd->getTimeline($telaah_id);
				$this->data['data_telaah'] 	= $this->m_dprd->get($telaah_id);

				$this->data['label_nama1'] 	= "Nama Kasubid :";
				$this->data['label_nama2'] 	= "Nama Sekwan :";
				$this->data['label_nama3'] 	= "Nama Pimpinan DPRD :";

				$this->data['nama1'] 			= $timeline[0]['timeline_kasubid_name'];
				$this->data['nama2'] 			= $timeline[0]['timeline_sekwan_name'];
				$this->data['nama3'] 			= $timeline[0]['timeline_kadprd_name'];
				$this->data['nama4'] 			= "";

				$this->data['tanggal1'] 		= $timeline[0]['timeline_kasubid_date'];
				$this->data['tanggal2'] 		= $timeline[0]['timeline_sekwan_date'];
				$this->data['tanggal3'] 		= $timeline[0]['timeline_kadprd_date'];
				$this->data['tanggal4'] 		= "";

				$this->data['input_nama1'] 	= "timeline_kasubid_disposisi";
				$this->data['input_nama2'] 	= "timeline_sekwan_disposisi";
				$this->data['input_nama3'] 	= "timeline_kadprd_disposisi";

				$this->data['timeline1'] 		= $timeline[0]['timeline_kasubid_disposisi'];
				$this->data['timeline2'] 		= $timeline[0]['timeline_sekwan_disposisi'];
				$this->data['timeline3'] 		= $timeline[0]['timeline_kadprd_disposisi'];
				$this->data['timeline4'] 		= "";

				break;
			case "sekda":
				$timeline 					= $this->m_sekda->getTimeline($telaah_id);
				$this->data['data_telaah'] 	= $this->m_sekda->get($telaah_id);

				$this->data['label_nama1'] 	= "Nama Kabag :";
				$this->data['label_nama2'] 	= "Nama Asisten/Kepala OPD :";
				$this->data['label_nama3'] 	= "Nama Sekda :";

				$this->data['nama1'] 			= $timeline[0]['timeline_kabag_name'];
				$this->data['nama2'] 			= $timeline[0]['timeline_asisten_name'];
				$this->data['nama3'] 			= $timeline[0]['timeline_sekda_name'];
				$this->data['nama4'] 			= "";

				$this->data['tanggal1'] 		= $timeline[0]['timeline_kabag_date'];
				$this->data['tanggal2'] 		= $timeline[0]['timeline_asisten_date'];
				$this->data['tanggal3'] 		= $timeline[0]['timeline_sekda_date'];
				$this->data['tanggal4'] 		= "";

				$this->data['input_nama1'] 	= "timeline_kabag_disposisi";
				$this->data['input_nama2'] 	= "timeline_asisten_disposisi";
				$this->data['input_nama3'] 	= "timeline_sekda_disposisi";

				$this->data['timeline1'] 		= $timeline[0]['timeline_kabag_disposisi'];
				$this->data['timeline2'] 		= $timeline[0]['timeline_asisten_disposisi'];
				$this->data['timeline3'] 		= $timeline[0]['timeline_sekda_disposisi'];
				$this->data['timeline4'] 		= "";

				break;
			case "camat":
				$timeline 					= $this->m_camat->getTimeline($telaah_id);
				$this->data['data_telaah']	= $this->m_camat->get($telaah_id);

				$this->data['label_nama1'] 	= "Nama Sekcam :";
				$this->data['label_nama2'] 	= "Nama Camat :";
				$this->data['label_nama3'] 	= "Nama Sekda :";

				$this->data['nama1'] 			= $timeline[0]['timeline_sekcam_name'];
				$this->data['nama2'] 			= $timeline[0]['timeline_camat_name'];
				$this->data['nama3'] 			= $timeline[0]['timeline_sekda_name'];
				$this->data['nama4'] 			= "";

				$this->data['tanggal1'] 		= $timeline[0]['timeline_sekcam_date'];
				$this->data['tanggal2'] 		= $timeline[0]['timeline_camat_date'];
				$this->data['tanggal3'] 		= $timeline[0]['timeline_sekda_date'];
				$this->data['tanggal4'] 		= "";

				$this->data['input_nama1'] 	= "timeline_sekcam_disposisi";
				$this->data['input_nama2'] 	= "timeline_camat_disposisi";
				$this->data['input_nama3'] 	= "timeline_sekda_disposisi";

				$this->data['timeline1'] 		= $timeline[0]['timeline_sekcam_disposisi'];
				$this->data['timeline2'] 		= $timeline[0]['timeline_camat_disposisi'];
				$this->data['timeline3'] 		= $timeline[0]['timeline_sekda_disposisi'];
				$this->data['timeline4'] 		= "";

				break;
			case "lurah":
				$timeline					 	= $this->m_lurah->getTimeline($telaah_id);
				$this->data['data_telaah']	= $this->m_camat->get($telaah_id);

				$this->data['label_nama1'] 	= "Nama Sekcam :";
				$this->data['label_nama2'] 	= "Nama Camat :";
				$this->data['label_nama3'] 	= "Nama Sekda :";

				$this->data['nama1'] 			= $timeline[0]['timeline_sekcam_name'];
				$this->data['nama2'] 			= $timeline[0]['timeline_camat_name'];
				$this->data['nama3'] 			= $timeline[0]['timeline_sekda_name'];
				$this->data['nama4'] 			= "";

				$this->data['tanggal1'] 		= $timeline[0]['timeline_sekcam_date'];
				$this->data['tanggal2'] 		= $timeline[0]['timeline_camat_date'];
				$this->data['tanggal3'] 		= $timeline[0]['timeline_sekda_date'];
				$this->data['tanggal4'] 		= "";

				$this->data['input_nama1'] 	= "timeline_sekcam_disposisi";
				$this->data['input_nama2'] 	= "timeline_camat_disposisi";
				$this->data['input_nama3'] 	= "timeline_sekda_disposisi";

				$this->data['timeline1'] 		= $timeline[0]['timeline_sekcam_disposisi'];
				$this->data['timeline2'] 		= $timeline[0]['timeline_camat_disposisi'];
				$this->data['timeline3'] 		= $timeline[0]['timeline_sekda_disposisi'];
				$this->data['timeline4'] 		= "";

				break;
			case "staff_dprd":
				$timeline						= $this->m_staff_dprd->getTimeline($telaah_id);
				$this->data['data_telaah']	= $this->m_staff_dprd->get($telaah_id);

				$this->data['label_nama1'] 	= "Nama Kabag :";
				$this->data['label_nama2'] 	= "Nama Sekwan :";
				$this->data['label_nama3'] 	= "";

				$this->data['nama1'] 			= $timeline[0]['timeline_kabag_name'];
				$this->data['nama2'] 			= $timeline[0]['timeline_sekwan_name'];
				$this->data['nama3'] 			= "";
				$this->data['nama4'] 			= "";

				$this->data['tanggal1'] 		= $timeline[0]['timeline_kabag_date'];
				$this->data['tanggal2'] 		= $timeline[0]['timeline_sekwan_date'];
				$this->data['tanggal3'] 		= "";
				$this->data['tanggal4'] 		= "";

				$this->data['input_nama1'] 	= "timeline_kabag_disposisi";
				$this->data['input_nama2'] 	= "timeline_sekwan_disposisi";

				$this->data['timeline1'] 		= $timeline[0]['timeline_kabag_disposisi'];
				$this->data['timeline2'] 		= $timeline[0]['timeline_sekwan_disposisi'];
				$this->data['timeline3'] 		= "";
				$this->data['timeline4'] 		= "";

				break;
			case "staff_camat":
				$timeline 					= $this->m_staff_camat->getTimeline($telaah_id);
				$this->data['data_telaah']	= $this->m_staff_lurah->get($telaah_id);

				$this->data['label_nama1'] 	= "Nama Kasubag :";
				$this->data['label_nama2'] 	= "Nama Sekcam :";
				$this->data['label_nama3'] 	= "Nama Camat";

				$this->data['nama1'] 			= $timeline[0]['timeline_lurah_name'];
				$this->data['nama2'] 			= $timeline[0]['timeline_sekcam_name'];
				$this->data['nama3'] 			= $timeline[0]['timeline_camat_name'];
				$this->data['nama4'] 			= "";

				$this->data['tanggal1'] 		= $timeline[0]['timeline_lurah_date'];
				$this->data['tanggal2'] 		= $timeline[0]['timeline_sekcam_date'];
				$this->data['tanggal3'] 		= $timeline[0]['timeline_camat_date'];
				$this->data['tanggal4'] 		= "";

				$this->data['input_nama1'] 	= "timeline_lurah_disposisi";
				$this->data['input_nama2'] 	= "timeline_sekcam_disposisi";
				$this->data['input_nama3'] 	= "timeline_camat_disposisi";

				$this->data['timeline1'] 		= $timeline[0]['timeline_lurah_disposisi'];
				$this->data['timeline2'] 		= $timeline[0]['timeline_sekcam_disposisi'];
				$this->data['timeline3'] 		= $timeline[0]['timeline_camat_disposisi'];
				$this->data['timeline4'] 		= "";

				break;
			case "staff_lurah":
				$timeline 					= $this->m_staff_lurah->getTimeline($telaah_id);
				$this->data['data_telaah']	= $this->m_staff_lurah->get($telaah_id);

				$this->data['label_nama1'] 	= "Nama Kabag :";
				$this->data['label_nama2'] 	= "Nama Sekcam :";
				$this->data['label_nama3'] 	= "Nama Camat";

				$this->data['nama1'] 			= $timeline[0]['timeline_lurah_name'];
				$this->data['nama2'] 			= $timeline[0]['timeline_sekcam_name'];
				$this->data['nama3'] 			= $timeline[0]['timeline_camat_name'];
				$this->data['nama4'] 			= "";

				$this->data['tanggal1'] 		= $timeline[0]['timeline_lurah_date'];
				$this->data['tanggal2'] 		= $timeline[0]['timeline_sekcam_date'];
				$this->data['tanggal3'] 		= $timeline[0]['timeline_camat_date'];
				$this->data['tanggal4'] 		= "";

				$this->data['input_nama1'] 	= "timeline_lurah_disposisi";
				$this->data['input_nama2'] 	= "timeline_sekcam_disposisi";
				$this->data['input_nama3'] 	= "timeline_camat_disposisi";

				$this->data['timeline1'] 		= $timeline[0]['timeline_lurah_disposisi'];
				$this->data['timeline2'] 		= $timeline[0]['timeline_sekcam_disposisi'];
				$this->data['timeline3'] 		= $timeline[0]['timeline_camat_disposisi'];
				$this->data['timeline4'] 		= "";

				break;
			case "walikota":
				$timeline 					= $this->m_sekda->getTimeline8($telaah_id);
				$this->data['data_telaah']	= $this->m_sekda->getWalikota($telaah_id);

				$this->data['label_nama1'] 	= "Nama Kabag :";
				$this->data['label_nama2'] 	= "Nama Sekda :";
				$this->data['label_nama3'] 	= "Nama Walikota :";

				$this->data['nama1'] 			= $timeline[0]['timeline_kabag_name'];
				$this->data['nama2'] 			= $timeline[0]['timeline_sekda_name'];
				$this->data['nama3'] 			= $timeline[0]['timeline_walikota_name'];
				$this->data['nama4'] 			= "";

				$this->data['tanggal1'] 		= $timeline[0]['timeline_kabag_date'];
				$this->data['tanggal2'] 		= $timeline[0]['timeline_sekda_date'];
				$this->data['tanggal3'] 		= $timeline[0]['timeline_walikota_date'];
				$this->data['tanggal4'] 		= "";

				$this->data['input_nama1'] 	= "timeline_kabag_disposisi";
				$this->data['input_nama2'] 	= "timeline_sekda_disposisi";
				$this->data['input_nama3'] 	= "timeline_walikota_disposisi";

				$this->data['timeline1'] 		= $timeline[0]['timeline_kabag_disposisi'];
				$this->data['timeline2'] 		= $timeline[0]['timeline_sekda_disposisi'];
				$this->data['timeline3'] 		= $timeline[0]['timeline_walikota_disposisi'];
				$this->data['timeline4'] 		= "";

				break;
			case "staff_setda":
				$timeline 					= $this->m_sekda->getTimeline9($telaah_id);
				$this->data['data_telaah'] 	= $this->m_sekda->get($telaah_id);

				$this->data['label_nama1'] 	= "Nama Kabag :";
				$this->data['label_nama2'] 	= "Nama Asisten/Kepala OPD :";
				$this->data['label_nama3'] 	= "Nama Sekda :";

				$this->data['nama1'] 			= $timeline[0]['timeline_kabag_name'];
				$this->data['nama2'] 			= $timeline[0]['timeline_asisten_name'];
				$this->data['nama3'] 			= $timeline[0]['timeline_sekda_name'];
				$this->data['nama4'] 			= "";

				$this->data['tanggal1'] 		= $timeline[0]['timeline_kabag_date'];
				$this->data['tanggal2'] 		= $timeline[0]['timeline_asisten_date'];
				$this->data['tanggal3'] 		= $timeline[0]['timeline_sekda_date'];
				$this->data['tanggal4'] 		= "";

				$this->data['input_nama1'] 	= "timeline_kabag_disposisi";
				$this->data['input_nama2'] 	= "timeline_asisten_disposisi";
				$this->data['input_nama3'] 	= "timeline_sekda_disposisi";

				$this->data['timeline1'] 		= $timeline[0]['timeline_kabag_disposisi'];
				$this->data['timeline2'] 		= $timeline[0]['timeline_asisten_disposisi'];
				$this->data['timeline3'] 		= $timeline[0]['timeline_sekda_disposisi'];
				$this->data['timeline4'] 		= "";

				break;
			case "sekwan":
				$timeline						= $this->m_sekwan->getTimeline10($telaah_id);
				$this->data['data_telaah']	= $this->m_sekwan->get($telaah_id);

				$this->data['label_nama1'] 	= "Nama Kabag :";
				$this->data['label_nama2'] 	= "Nama Sekwan :";
				$this->data['label_nama3'] 	= "Nama Sekda :";

				$this->data['nama1'] 			= $timeline[0]['timeline_kabag_name'];
				$this->data['nama2'] 			= $timeline[0]['timeline_sekwan_name'];
				$this->data['nama3'] 			= $timeline[0]['timeline_sekda_name'];
				$this->data['nama4'] 			= "";

				$this->data['tanggal1'] 		= $timeline[0]['timeline_kabag_date'];
				$this->data['tanggal2'] 		= $timeline[0]['timeline_sekwan_date'];
				$this->data['tanggal3'] 		= $timeline[0]['timeline_sekda_date'];
				$this->data['tanggal4'] 		= "";

				$this->data['input_nama1'] 	= "timeline_kabag_disposisi";
				$this->data['input_nama2'] 	= "timeline_sekwan_disposisi";
				$this->data['input_nama3'] 	= "timeline_sekda_disposisi";

				$this->data['timeline1'] 		= $timeline[0]['timeline_kabag_disposisi'];
				$this->data['timeline2'] 		= $timeline[0]['timeline_sekwan_disposisi'];
				$this->data['timeline3'] 		= $timeline[0]['timeline_sekda_disposisi'];
				$this->data['timeline4'] 		= "";

				break;
			case "kapus":
				$timeline						= $this->m_kapus->getTimeline2($telaah_id);
				$this->data['data_telaah']	= $this->m_kapus->get($telaah_id);

				$this->data['label_nama1'] 	= "Nama Kepala Puskesmas :";
				$this->data['label_nama2'] 	= "";
				$this->data['label_nama3'] 	= "";

				$this->data['nama1'] 			= $timeline[0]['timeline_kapus_name'];
				$this->data['nama2'] 			= "";
				$this->data['nama3'] 			= "";
				$this->data['nama4'] 			= "";

				$this->data['tanggal1'] 		= $timeline[0]['timeline_kapus_date'];
				$this->data['tanggal2'] 		= "";
				$this->data['tanggal3'] 		= "";
				$this->data['tanggal4'] 		= "";

				$this->data['input_nama1'] 	= "timeline_kapus_disposisi";

				$this->data['timeline1'] 		= $timeline[0]['timeline_kapus_disposisi'];
				$this->data['timeline2'] 		= "";
				$this->data['timeline3'] 		= "";
				$this->data['timeline4'] 		= "";
				break;
		}

		$this->render('telaah/list_telaah/cek_timeline');
	}

	//Update Timeline untuk Admin Jika 15 menit belum di periksa/disposisi
	public function update_timeline()
	{

		switch ($this->uri->segment(4)) {
			case "esselon":
				if ($this->input->post('job') == 'sekdis') {
					$data['timeline_sekdis_disposisi'] 	= $this->input->post('timeline_sekdis_disposisi');
					$data['telaah_id'] 					= $this->input->post('telaah_id');
				} elseif ($this->input->post('job') == 'kadis') {
					$data['timeline_kadis_disposisi'] 	= $this->input->post('timeline_kadis_disposisi');
					$data['telaah_id'] 					= $this->input->post('telaah_id');
				} elseif ($this->input->post('job') == 'kabid') {
					$data['timeline_kabid_disposisi'] 	= $this->input->post('timeline_kabid_disposisi');
					$data['telaah_id'] 					= $this->input->post('telaah_id');
				}
				$this->m_esselon->update_timeline($data);

				$this->session->set_flashdata('notif', 'Data Timeline Berhasil Di Update !');
				$telaah_id = base64_encode($this->encrypt->encode($data['telaah_id'], $this->session->userdata('encrypt_key')));
				redirect('list_telaah/esselon/cek_timeline?telaah_id=' . $telaah_id);
				break;
			case "kadis":
				if ($this->input->post('job') == 'sekdis') {
					$data['timeline_sekdis_disposisi'] 	= $this->input->post('timeline_sekdis_disposisi');
					$data['telaah_id']					= $this->input->post('telaah_id');
				} elseif ($this->input->post('job') == 'kadis') {
					$data['timeline_kadis_disposisi'] 	= $this->input->post('timeline_kadis_disposisi');
					$data['telaah_id'] 					= $this->input->post('telaah_id');
				} elseif ($this->input->post('job') == 'sekda') {
					$data['timeline_sekda_disposisi'] 	= $this->input->post('timeline_sekda_disposisi');
					$data['telaah_id'] 					= $this->input->post('telaah_id');
				}
				$this->m_kadis->update_timeline($data);

				$this->session->set_flashdata('notif', 'Data Timeline Berhasil Di Update !');
				$telaah_id = base64_encode($this->encrypt->encode($data['telaah_id'], $this->session->userdata('encrypt_key')));
				redirect('list_telaah/kadis/cek_timeline?telaah_id=' . $telaah_id);
				break;

			case "dprd":
				if ($this->input->post('job') == 'kasubid') {
					$data['timeline_kasubid_disposisi'] = $this->input->post('timeline_kasubid_disposisi');
					$data['telaah_id'] 					= $this->input->post('telaah_id');
				} elseif ($this->input->post('job') == 'sekwan') {
					$data['timeline_sekwan_disposisi'] 	= $this->input->post('timeline_sekwan_disposisi');
					$data['telaah_id'] 					= $this->input->post('telaah_id');
				} elseif ($this->input->post('job') == 'kadprd') {
					$data['timeline_kadprd_disposisi'] 	= $this->input->post('timeline_kadprd_disposisi');
					$data['telaah_id'] 					= $this->input->post('telaah_id');
				}
				$this->m_dprd->update_timeline($data);

				$this->session->set_flashdata('notif', 'Data Timeline Berhasil Di Update !');
				$telaah_id = base64_encode($this->encrypt->encode($data['telaah_id'], $this->session->userdata('encrypt_key')));
				redirect('list_telaah/dprd/cek_timeline?telaah_id=' . $telaah_id);
				break;

			//case "sekda" 			: $this->data['timeline'] =  $this->m_sekda->getTimeline($telaah_id); 
			//						  $this->render('list_telaah/sekda/detail');break;
			//case "camat" 			: $this->data['timeline'] =  $this->m_camat->getTimeline($telaah_id); 
			//						  $this->render('list_telaah/camat/detail'); break;
			//case "lurah" 			: $this->data['timeline'] =  $this->m_lurah->getTimeline($telaah_id); 
			//						  $this->render('list_telaah/lurah/detail');break;
			case "staff_dprd":
				if ($this->input->post('job') == 'kabag') {
					$data['timeline_kabag_disposisi'] = $this->input->post('timeline_kabag_disposisi');
					$data['telaah_id'] 								= $this->input->post('telaah_id');
				} elseif ($this->input->post('job') == 'sekwan') {
					$data['timeline_sekwan_disposisi'] = $this->input->post('timeline_sekwan_disposisi');
					$data['telaah_id'] 								= $this->input->post('telaah_id');
				}
				$this->m_staff_dprd->update_timeline($data);

				$this->session->set_flashdata('notif', 'Data Timeline Berhasil Di Update !');
				$telaah_id = base64_encode($this->encrypt->encode($data['telaah_id'], $this->session->userdata('encrypt_key')));
				redirect('list_telaah/staff_dprd/cek_timeline?telaah_id=' . $telaah_id);
				break;

			//case "staff_camat" 		: $this->data['timeline'] =  $this->m_staff_camat->getTimeline($telaah_id); 
			//						  $this->render('list_telaah/staff_camat/detail');break;
			//case "staff_lurah" 		: $this->data['timeline'] =  $this->m_staff_lurah->getTimeline($telaah_id); 
			//						  $this->render('list_telaah/staff_lurah/detail');break;
			//case "walikota" 		: $this->data['timeline'] =  $this->m_sekda->getTimeline8($telaah_id); 
			//						  $this->render('list_telaah/sekda/detailwalikota');break;
			//case "staff_setda" 		: $this->data['timeline'] =  $this->m_sekda->getTimeline9($telaah_id); 
			//						  $this->render('list_telaah/sekda/detailkasubag');break;
			case "sekwan":
				if ($this->input->post('job') == 'sekdis') {
					$data['timeline_sekdis_disposisi'] 	= $this->input->post('timeline_sekdis_disposisi');
					$data['telaah_id'] 					= $this->input->post('telaah_id');
				} elseif ($this->input->post('job') == 'sekwan') {
					$data['timeline_kadis_disposisi'] 	= $this->input->post('timeline_kadis_disposisi');
					$data['telaah_id'] 					= $this->input->post('telaah_id');
				} elseif ($this->input->post('job') == 'sekda') {
					$data['timeline_sekda_disposisi'] 	= $this->input->post('timeline_sekda_disposisi');
					$data['telaah_id'] 					= $this->input->post('telaah_id');
				}
				$this->m_sekwan->update_timeline($data);

				$this->session->set_flashdata('notif', 'Data Timeline Berhasil Di Update !');
				$telaah_id = base64_encode($this->encrypt->encode($data['telaah_id'], $this->session->userdata('encrypt_key')));
				redirect('list_telaah/sekwan/cek_timeline?telaah_id=' . $telaah_id);
				break;

			case "kapus":
				if ($this->input->post('job') == 'sekdis') {
					$data['timeline_sekdis_disposisi'] = $this->input->post('timeline_sekdis_disposisi');
					$data['telaah_id'] 								= $this->input->post('telaah_id');
				} elseif ($this->input->post('job') == 'kapus') {
					$data['timeline_kadis_disposisi'] = $this->input->post('timeline_kadis_disposisi');
					$data['telaah_id'] 								= $this->input->post('telaah_id');
				} elseif ($this->input->post('job') == 'sekda') {
					$data['timeline_sekda_disposisi'] = $this->input->post('timeline_sekda_disposisi');
					$data['telaah_id'] 								= $this->input->post('telaah_id');
				}
				$this->m_kapus->update_timeline($data);

				$this->session->set_flashdata('notif', 'Data Timeline Berhasil Di Update !');
				$telaah_id = base64_encode($this->encrypt->encode($data['telaah_id'], $this->session->userdata('encrypt_key')));
				redirect('list_telaah/kapus/cek_timeline?telaah_id=' . $telaah_id);
				break;
		}
	}

	// Delete Telaah
	public function delete_telaah()
	{

		$arrayTable = array('table_telaah', 'table_timeline1', 'table_timeline2', 'table_timeline3', 'table_timeline4', 'table_timeline5', 'table_timeline6', 'table_timeline7', 'table_timeline8', 'table_timeline9', 'table_timeline10', 'table_timeline11', 'table_pengikut', 'table_pengeluaran_rill', 'table_kuitansi_panjar', 'table_laporanperjalanan', 'table_lokasi_tujuan', 'table_rincian_biaya', 'table_tanggal_perjalanan', 'table_relasi_sekda');

		for ($i = 0; $i < count($arrayTable); $i++) {
			$this->m_kadis->delete_telaah($this->input->post('telaah_id'), $arrayTable[$i]);
		}

		$this->session->set_flashdata('notif', 'Data Telaah Staf Di Hapus !');
		redirect('telaah/list_telaah/index/' . $this->input->post('url'));
	}

	public function rekap_data()
	{
		$this->data['rekap'] 	=  $this->m_widget->getRekap();
		$this->data['skpd'] 	=  $this->m_widget->getSKPD();
		$this->render('widget');
	}

	public function detail_rekap_data()
	{
		$this->data['rekap'] = $this->m_widget->getDetailRekap($this->uri->segment(4));
		$this->render('getDetailRekap');
	}


	public function getDataBySKPD()
	{
		$namaSKPD = explode('-', $this->input->post('skpd'));
		$this->data['nama_skpd']	= $namaSKPD[1];
		$this->data['rekap'] 			= $this->m_widget->getDetailSKPD($namaSKPD[0]);
		$this->render('getRekapSkpd');
	}

	function tgl_indo($tanggal)
	{
		$bulan = array(
			1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		$pecahkan = explode('-', $tanggal);

		// variabel pecahkan 0 = tanggal
		// variabel pecahkan 1 = bulan
		// variabel pecahkan 2 = tahun

		return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
	}

	## Create SPPD
	function cetak_spd($telaah_id, $jenis_skpd, $kategori_pelaksana, $pegawai_id)
	{
		$telaah = $this->m_telaah->get($telaah_id);

		## Hapus PDF
		$filename  = 'SPPD - ' . $telaah[0]['pegawai_nama'] . ' - ' . date("d-m-Y", strtotime($telaah[0]['telaah_tanggalspd'])) . '.pdf';
		$path_file = './upload/doc_dummy/';
		if (file_exists($path_file . $filename)) {
			unlink($path_file . $filename);
		}

		switch ($jenis_skpd) {
			case "opd":
				if ($kategori_pelaksana == 1) {
					$data = $this->m_laporan->get_pelaksana_opd($telaah_id);
				} else if ($kategori_pelaksana == 2) {
					$data = $this->m_laporan->get_pengikut_opd($telaah_id, $pegawai_id);
				}
				break;
			case "dprd":
				if ($kategori_pelaksana == 1) {
					$data = $this->m_laporan->get_pelaksana_dprd($telaah_id);
				} else if ($kategori_pelaksana == 2) {
					$data = $this->m_laporan->get_pengikut_dprd($telaah_id, $pegawai_id);
				}
				break;
			case "walikota":
				if ($kategori_pelaksana == 1) {
					$data = $this->m_laporan->get_pelaksana_walikota($telaah_id);
				} else if ($kategori_pelaksana == 2) {
					$data = $this->m_laporan->get_pengikut_opd($telaah_id, $pegawai_id);
				}
				break;
		}

		$lokasi_tujuan = $this->m_lokasi_tujuan->get2($telaah_id);

		$pdf = new PDF_MC_Table('L', 'mm', 'legal');
		$pdf->SetMargins(10, 3.175, 25);

		$pdf->SetAutoPageBreak(false);
		// membuat halaman baru
		$pdf->AddPage();

		//$pdf->Cell(10,12,'',0,1);
		$pdf->SetFont('Arial', 'B', 20);

		if ($data[0]['jenis_skpd'] == 7 && $data[0]['telaah_kategori'] != 11) {
			$dinas_kesehatan = $this->m_laporan->get_dinas_kesehatan();
			$pdf->Image('./upload/kop_surat/' . $dinas_kesehatan[0]['kop_surat'], 10, 5, 160, 30);
		} else {
			if ($jenis_skpd == "walikota") {
				$walikota = $this->m_laporan->get_pelaksana_walikota($telaah_id);
				$pdf->Image('./upload/kop_surat/' . $walikota[0]['kop_surat'], 10, 5, 160, 30);
			} else {
				$pdf->Image('./upload/kop_surat/' . $data[0]['kop_surat'], 10, 5, 160, 30);
			}
		}

		$pdf->Cell(10, 25, '', 0, 1);

		$pdf->Cell(10, 7, '', 0, 1);
		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(110, 3, '', 0, 0);
		$pdf->Cell(20, 3, 'Lampiran', 0, 0);
		$pdf->Cell(30, 3, ':', 0, 1);

		$pdf->Cell(110, 3, '', 0, 0);
		$pdf->Cell(20, 3, 'Lembar Ke', 0, 0);
		$pdf->Cell(30, 3, ': I,II,III,IV', 0, 1);

		$pdf->Cell(110, 3, '', 0, 0);
		$pdf->Cell(20, 3, 'Kode No.', 0, 0);
		$pdf->Cell(30, 3, ':', 0, 1);

		$pdf->Cell(110, 3, '', 0, 0);
		$pdf->Cell(20, 3, 'Nomor', 0, 0);
		$pdf->Cell(30, 3, ':', 0, 1);

		$pdf->Cell(10, 3, '', 0, 1);
		$pdf->SetFont('Arial', 'B', 9);
		$pdf->Cell(160, 4, 'SURAT PERINTAH PERJALANAN DINAS (SPPD)', 0, 1, 'C');

		$pdf->Cell(10, 3, '', 0, 1);
		$pdf->SetFont('Arial', '', 7);

		$skpdnama = strtolower($data[0]['skpd_nama']);
		$skpdnama2 = ucwords($skpdnama);

		## 1
		$pdf->SetWidths(array(5, 75, 80));
		$border = array('LT', 'LT', 'LTR');
		$align = array('', '', 'J');
		$style = array('', '', '');
		if ($data[0]['jenis_skpd'] == 2) {
			$caption = array("1.", "Pejabat berwenang yang memberi perintah", "Sekretaris DPRD Kota Kendari");
		} else if ($data[0]['jenis_skpd'] == 3) {
			$caption = array("1.", "Pejabat berwenang yang memberi perintah", "Sekretaris Daerah");
		} else if ($data[0]['jenis_skpd'] == 7 && $data[0]['telaah_kategori'] != 11) {
			$caption = array("1.", "Pejabat berwenang yang memberi perintah", "Kepala Dinas Kesehatan Kota Kendari");
		} else {
			if ($data[0]['telaah_skpd_id'] == 182) {
				$caption = array("1.", "Pejabat berwenang yang memberi perintah", 'Inspektur');
			} else {
				$caption = array("1.", "Pejabat berwenang yang memberi perintah", 'Kepala ' . $skpdnama2);
			}
		}
		$pdf->Row($caption, $border, $align);


		## 2
		$pdf->Cell(5, 4, '2.', 'LTR', 0);
		$pdf->Cell(75, 4, 'Nama Pegawai yang diperintahkan', 'TR', 0);
		$pdf->Cell(80, 4, $data[0]['pegawai_nama'], 'TR', 1);


		## 3.a
		$pangkatGol = isset($data[0]['pangkat']) && isset($data[0]['pegawai_golongan']) ?
			$data[0]['pangkat'] . " - " . $data[0]['pegawai_golongan'] : '-';

		$pdf->Cell(5, 4, '3.', 'LTR', 0, 'T');
		$pdf->Cell(5, 4, 'a.', 'T', 0, 'T');
		$pdf->Cell(70, 4, 'Pangkat dan Golongan ruang gaji', 'TR', 0);
		$pdf->Cell(80, 4, $pangkatGol, 'TR', 1);

		$pdf->Cell(5, 4, '', 'LR', 0, 'T');
		$pdf->Cell(5, 4, '', '', 0, 'T');
		$pdf->Cell(70, 4, 'menurut PP No.30 Tahun 2015', 'R', 0);
		$pdf->Cell(80, 4, '', 'R', 1);


		## 3.b
		$pdf->SetWidths(array(5, 5, 70, 80));
		$border = array('LR', 'L', 'R', 'LR');
		$align  = array('', '', '', 'J');

		if ($data[0]['telaah_skpd_id'] == 182) {
			if ($kategori_pelaksana == 1) {
				if ($data[0]['telaah_jabatan_pelaksana'] == 1) {
					$caption = array("", "b.", "Jabatan / Instansi", "Penanggung Jawab");
				} else if ($data[0]['telaah_jabatan_pelaksana'] == 2) {
					$caption = array("", "b.", "Jabatan / Instansi", "Pembantu Penanggung Jawab");
				} else if ($data[0]['telaah_jabatan_pelaksana'] == 3) {
					$caption = array("", "b.", "Jabatan / Instansi", "Pengendali Teknis");
				} else if ($data[0]['telaah_jabatan_pelaksana'] == 4) {
					$caption = array("", "b.", "Jabatan / Instansi", "Ketua Tim");
				} else if ($data[0]['telaah_jabatan_pelaksana'] == 5) {
					$caption = array("", "b.", "Jabatan / Instansi", "Anggota");
				} else if ($data[0]['telaah_jabatan_pelaksana'] == 6) {
					$caption = array("", "b.", "Jabatan / Instansi", "Admin Tim");
				} else { // TAMBAHAN KODE
					$caption = array("", "b.", "Jabatan / Instansi", $data[0]['pegawai_namajabatan']);
				}
			} else {
				if ($data[0]['telaah_jabatan_pengikut'] == 1) {
					$caption = array("", "b.", "Jabatan / Instansi", "Penanggung Jawab");
				} else if ($data[0]['telaah_jabatan_pengikut'] == 2) {
					$caption = array("", "b.", "Jabatan / Instansi", "Pembantu Penanggung Jawab");
				} else if ($data[0]['telaah_jabatan_pengikut'] == 3) {
					$caption = array("", "b.", "Jabatan / Instansi", "Pengendali Teknis");
				} else if ($data[0]['telaah_jabatan_pengikut'] == 4) {
					$caption = array("", "b.", "Jabatan / Instansi", "Ketua Tim");
				} else if ($data[0]['telaah_jabatan_pengikut'] == 5) {
					$caption = array("", "b.", "Jabatan / Instansi", "Anggota");
				} else if ($data[0]['telaah_jabatan_pengikut'] == 6) {
					$caption = array("", "b.", "Jabatan / Instansi", "Admin Tim");
				} else { // TAMBAHAN KODE
					$caption = array("", "b.", "Jabatan / Instansi", $data[0]['pegawai_namajabatan']);
				}
			}
		} else {
			$caption = array("", "b.", "Jabatan / Instansi", $data[0]['pegawai_namajabatan']);
		}

		$pdf->Row($caption, $border, $align);

		## 3.c
		$pdf->SetWidths(array(5, 5, 70, 80));
		$border = array('LR', 'L', 'R', 'LR');
		$caption = array("", "c.", "Tingkat biaya perjalanan dinas", "");
		$pdf->Row($caption, $border);

		## 4
		$pdf->SetWidths(array(5, 75, 80));
		$border = array(1, 1, 1);
		$align = array('', '', 'J');
		$caption = array("4.", "Maksud Perjalanan Dinas", $data[0]['telaah_perihal']);
		$pdf->Row($caption, $border, $align);

		## 5
		$pdf->Cell(5, 4, '5.', 1, 0);
		$pdf->Cell(75, 4, 'Alat angkutan yang dipergunakan', 1, 0);
		$pdf->Cell(80, 4, $data[0]['telaah_angkutan'], 1, 1);

		## 6.a
		$pdf->SetWidths(array(5, 5, 70, 80));
		$border = array('L', 'L', '', 'LR');
		$align = array('', '', '', 'J');
		$caption = array("6.", "a.", "Tempat berangkat", $data[0]['telaah_tempatberangkat']);
		$pdf->Row($caption, $border, $align);

		## 6.b
		$pdf->SetWidths(array(5, 5, 70, 80));
		$border = array('L', 'L', '', 'LR');
		$align = array('', '', '', 'J');
		if ($data[0]['telaah_domainperjalanan'] == 3 || $data[0]['telaah_domainperjalanan'] == 4) {
			$caption = array("", "b.", "Tempat tujuan", $data[0]['telaah_kantortujuan']);
		} else {
			if (count($lokasi_tujuan) == 1) {
				$caption = array("", "b.", "Tempat tujuan", $data[0]['kabupaten_kota'] . " DAN " . $lokasi_tujuan[0]['kabupaten_kota']);
			} else if (count($lokasi_tujuan) == 2) {
				$caption = array("", "b.", "Tempat tujuan", $data[0]['kabupaten_kota'] . ", " . $lokasi_tujuan[0]['kabupaten_kota'] . " DAN " . $lokasi_tujuan[1]['kabupaten_kota']);
			} else {
				$caption = array("", "b.", "Tempat tujuan", $data[0]['kabupaten_kota']);
			}
		}
		$pdf->Row($caption, $border, $align);

		## 7
		$start_date = new DateTime($data[0]['telaah_tanggalberangkat']);
		$end_date = new DateTime($data[0]['telaah_tanggalkembali']);
		$interval = $start_date->diff($end_date);

		$pdf->Cell(5, 4, '7.', 'LTR', 0);
		$pdf->Cell(5, 4, 'a.', 'LT', 0);
		$pdf->Cell(70, 4, 'Lamanya Perjalanan dinas', 'TR', 0);
		if ($data[0]['telaah_hari'] == 0) {
			$pdf->Cell(80, 4, ($interval->days + 1) . ' Hari', 'LTR', 1);
		} else {
			$pdf->Cell(80, 4, $data[0]['telaah_hari'] . ' Hari', 'LTR', 1);
		}
		$pdf->Cell(5, 4, '', 'LR', 0);
		$pdf->Cell(5, 4, 'b.', 'L', 0);
		$pdf->Cell(70, 4, 'Tanggal berangkat', 'R', 0);
		$pdf->Cell(80, 4, date("d-m-Y", strtotime($data[0]['telaah_tanggalberangkat'])), 'LR', 1);

		$pdf->Cell(5, 4, '', 'LR', 0);
		$pdf->Cell(5, 4, 'c.', 'L', 0);
		$pdf->Cell(70, 4, 'Tanggal harus kembali', 'R', 0);
		$pdf->Cell(80, 4, date("d-m-Y", strtotime($data[0]['telaah_tanggalkembali'])), 'LR', 1);

		## 8
		$pdf->Cell(5, 4, '8.', 1, 0);
		$pdf->Cell(75, 4, 'Pengikut', 1, 0);
		$pdf->Cell(80, 4, 'Keterangan', 1, 1);

		if ($this->uri->segment(6) == 1 && $this->uri->segment(5) != "dprd") {
			$pengikut = $this->m_laporan->get_pengikut($telaah_id);
			$jml_pengikut = count($pengikut);
			if (!isset($pengikut[0]) || $pengikut[0] == "") {
			} else {
				for ($i = 0; $i < $jml_pengikut; $i++) {
					$pdf->Cell(5, 4, '', 'LR', 0, 'T');
					$pdf->Cell(5, 4, ($i + 1) . '.', 'L', 0, 'T');
					$pdf->Cell(70, 4, $pengikut[$i]['pegawai_nama'], 'R', 0);
					$pdf->Cell(80, 4, '', 'LR', 1);
				}
			}
		}

		## 9
		$pdf->Cell(5, 4, '9.', 1, 0);
		$pdf->Cell(75, 4, 'Pembebanan Anggaran', 1, 0);
		$pdf->Cell(80, 4, '', 1, 1);

		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);

		## 9.a
		$pdf->SetWidths(array(5, 5, 70, 80));
		$border = array('L', 'L', '', 'LR');
		$align = array('', '', '', 'J');
		$caption = array("", "a.", "Instansi", $skpd_nama2);
		$pdf->Row($caption, $border, $align);

		## 9.b
		$pdf->SetWidths(array(5, 5, 70, 80));
		$border = array('L', 'L', '', 'LR');
		$align = array('', '', '', 'J');
		$caption = array("", "b.", "Mata Anggaran", $data[0]['no_rekening']);
		$pdf->Row($caption, $border, $align);

		$pdf->SetWidths(array(5, 5, 70, 80));
		$border = array('L', 'L', '', 'LR');
		$align = array('', '', '', 'J');
		$caption = array("", "", "", $data[0]['mata_anggaran']);
		$pdf->Row($caption, $border, $align);

		## 10
		$pdf->Cell(5, 4, '10.', 1, 0);
		$pdf->Cell(75, 4, 'keterangan lain-lain', 1, 0);
		$pdf->Cell(80, 4, '', 1, 1);

		## TANDA TANGAN
		$pdf->Cell(10, 5, '', 0, 1);

		$pdf->Cell(80, 4, '', 0, 0);
		$pdf->Cell(35, 4, 'Dikeluarkan di', 0, 0);
		$pdf->Cell(45, 4, ': Kendari', 0, 1);

		$pdf->Cell(80, 4, '', 0, 0);
		$pdf->Cell(35, 4, 'Tanggal', 0, 0);
		$pdf->Cell(45, 4, ': ' . $this->tgl_indo($data[0]['telaah_tanggalspd']), 0, 1);

		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);

		$pdf->Cell(80, 4, '', 0, 0);
		$pdf->MultiCell(80, 4, '', 0, 1);

		$pdf->Cell(10, 20, '', 0, 1);
		$pdf->Cell(80, 4, '', 0, 0);
		$pdf->Cell(80, 4, '_________________________________', 0, 1);

		$pdf->Cell(80, 4, '', 0, 0);
		$pdf->Cell(80, 4, '', 0, 1);

		//atur posisi 1.5 cm dari bawah
		$pdf->SetY(-15);
		//buat garis horizontal
		$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
		//Arial italic 9
		$pdf->SetFont('Arial', 'I', 9);
		//nomor halaman
		$pdf->Cell(305, 10, 'Dokumen ini ditandatangani secara elektronik menggunakan Layanan BSrE', 0, 0, 'R');


		/**
		 * tambah tulisan gratifikasi di bagian bawah
		 */
		$pdf->SetY(-15);
		//buat garis horizontal
		$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
		//Arial italic 9
		$pdf->SetFont('Arial', 'I', 9);
		//nomor halaman
		$pdf->Cell(305, 10, 'Tidak Menerima Gratifikasi Dalam Bentuk Apapun Selama Pelaksanaan Tugas', 0, 0, 'L');
		/**
		 * End
		 */


		// membuat halaman baru
		//$pdf->AddPage();

		$pdf->SetFont('Arial', '', 7);
		$pdf->Cell(10, 16, '.', 0, 1);
		$pdf->Cell(70, 3, '', 'LTR', 0);
		$pdf->Cell(5, 3, 'I', 'LT', 0);
		$pdf->Cell(25, 3, 'Berangkat dari', 'T', 0);
		$pdf->Cell(40, 3, ': Kendari', 'TR', 1);

		$pdf->Cell(70, 3, '', 'LR', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, '(Tempat Kedudukan)', 0, 0);
		$pdf->Cell(40, 3, ':', 'R', 1);

		$pdf->Cell(70, 3, '', 'LR', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, 'Pada Tanggal', 0, 0);
		$pdf->Cell(40, 3, ': ' . date("d-m-Y", strtotime($data[0]['telaah_tanggalberangkat'])), 'R', 1);

		$pdf->Cell(70, 3, '', 'LR', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, '', 'R', 1);


		$start_x = $pdf->GetX(); //initial x (start of column position)

		$current_y = $pdf->GetY();
		$current_x = $pdf->GetX();
		$cell_width = 5;
		$cell_height = 3;
		$text = str_repeat(' ', 400);
		$pdf->MultiCell(70, $cell_height, "" . $text, 'LR');

		$current_x += 75;
		$pdf->SetXY($current_x, $current_y);
		$pdf->MultiCell(60, $cell_height, '', '');

		$current_x += 60;
		$pdf->SetXY($current_x, $current_y);
		$pdf->MultiCell(5, $cell_height, "", 'R');

		$pdf->Cell(70, 3, '', 'LR', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, '', 0, 0);
		$pdf->Cell(40, 3, '', 'R', 1);

		$pdf->Cell(70, 3, '', 'LR', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, '', 0, 0);
		$pdf->Cell(40, 3, '', 'R', 1);

		$pdf->Cell(70, 3, '', 'LR', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, '_________________________________', 'R', 1, 'C');

		$pdf->Cell(70, 3, '', 'LR', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, '', 'BR', 1, 'C');


		//baris 2
		if (($data[0]['telaah_kategori'] == 1 && $data[0]['jenis_skpd'] != 7) || ($data[0]['telaah_kategori'] == 2) || ($data[0]['jenis_skpd'] == 3)) {
			if ($data[0]['telaah_domainperjalanan'] == 1 || $data[0]['telaah_domainperjalanan'] == 2) {
				$pdf->SetWidths(array(5, 25, 40, 5, 25, 40));
				$border = array('LT', 'T', 'T', 'LT', 'T', 'TR');
				$align = array('', '', '', '', '', '');
				$caption = array('I.', 'Tiba Di', ': ' . $data[0]['kabupaten_kota'], '', 'Berangkat dari', ': ' . $data[0]['kabupaten_kota']);
				$pdf->Row($caption, $border, $align);
			} else {
				$pdf->Cell(5, 3, 'II.', 'LT', 0);
				$pdf->Cell(25, 3, 'Tiba Di', 'T', 0);
				$pdf->Cell(40, 3, ':', 'T', 0);
				$pdf->Cell(5, 3, '', 'LT', 0);
				$pdf->Cell(25, 3, 'Berangkat dari', 'T', 0);
				$pdf->Cell(40, 3, ':', 'TR', 1);
			}
		} else {
			$pdf->Cell(5, 3, 'II.', 'LT', 0);
			$pdf->Cell(25, 3, 'Tiba Di', 'T', 0);
			$pdf->Cell(40, 3, ':', 'T', 0);
			$pdf->Cell(5, 3, '', 'LT', 0);
			$pdf->Cell(25, 3, 'Berangkat dari', 'T', 0);
			$pdf->Cell(40, 3, ':', 'TR', 1);
		}

		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, 'Pada Tanggal', 0, 0);
		$pdf->Cell(40, 3, ': ' . date("d-m-Y", strtotime($data[0]['telaah_tanggalberangkat'])), 'R', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, 'Ke', 0, 0);
		$pdf->Cell(40, 3, ':', 'R', 1);

		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, 'Jabatan', 0, 0);
		$pdf->Cell(40, 3, ':', 'R', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, 'Pada Tanggal', 0, 0);
		$pdf->Cell(40, 3, ':', 'R', 1);

		$pdf->Cell(70, 3, '', 'LR', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, 'Jabatan', 0, 0);
		$pdf->Cell(40, 3, ':', 'R', 1);

		$pdf->Cell(70, 3, '', 'LR', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, '', 0, 0);
		$pdf->Cell(40, 3, '', 'R', 1);

		$pdf->Cell(70, 3, '', 'LR', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, '', 0, 0);
		$pdf->Cell(40, 3, '', 'R', 1);

		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, '_______________________________', 'R', 0, 'C');
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, '_______________________________', 'R', 1, 'C');
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, '', 'R', 0, 'C');
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, '', 'R', 1, 'C');

		//baris 3
		$lokasi_tujuan = $this->m_lokasi_tujuan->get2($telaah_id);

		$pdf->Cell(5, 3, 'III.', 'LT', 0);
		$pdf->Cell(25, 3, 'Tiba Di', 'T', 0);
		$pdf->Cell(40, 3, ':', 'T', 0);
		$pdf->Cell(5, 3, '', 'LT', 0);
		$pdf->Cell(25, 3, 'Berangkat dari', 'T', 0);
		$pdf->Cell(40, 3, ':', 'TR', 1);

		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, 'Pada Tanggal', 0, 0);
		$pdf->Cell(40, 3, ':', 'R', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, 'Ke', 0, 0);
		$pdf->Cell(40, 3, ':', 'R', 1);

		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, 'Jabatan', 0, 0);
		$pdf->Cell(40, 3, ':', 'R', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, 'Pada Tanggal', 0, 0);
		$pdf->Cell(40, 3, ':', 'R', 1);

		$pdf->Cell(70, 3, '', 'LR', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, 'Jabatan', 0, 0);
		$pdf->Cell(40, 3, ':', 'R', 1);

		$pdf->Cell(70, 3, '', 'LR', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, '', 0, 0);
		$pdf->Cell(40, 3, '', 'R', 1);

		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, '_______________________________', 'R', 0, 'C');
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, '_______________________________', 'R', 1, 'C');
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, '', 'R', 0, 'C');
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, '', 'R', 1, 'C');

		//baris 4
		$lokasi_tujuan = $this->m_lokasi_tujuan->get2($telaah_id);

		$pdf->Cell(5, 3, 'IV.', 'LT', 0);
		$pdf->Cell(25, 3, 'Tiba Di', 'T', 0);
		$pdf->Cell(40, 3, ':', 'T', 0);
		$pdf->Cell(5, 3, '', 'LT', 0);
		$pdf->Cell(25, 3, 'Berangkat dari', 'T', 0);
		$pdf->Cell(40, 3, ':', 'TR', 1);

		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, 'Pada Tanggal', 0, 0);
		$pdf->Cell(40, 3, ':', 'R', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, 'Ke', 0, 0);
		$pdf->Cell(40, 3, ':', 'R', 1);

		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, 'Jabatan', 0, 0);
		$pdf->Cell(40, 3, ':', 'R', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, 'Pada Tanggal', 0, 0);
		$pdf->Cell(40, 3, ':', 'R', 1);

		$pdf->Cell(70, 3, '', 'LR', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, 'Jabatan', 0, 0);
		$pdf->Cell(40, 3, ':', 'R', 1);

		$pdf->Cell(70, 3, '', 'LR', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, '', 0, 0);
		$pdf->Cell(40, 3, '', 'R', 1);

		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, '_______________________________', 'R', 0, 'C');
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, '_______________________________', 'R', 1, 'C');
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, '', 'R', 0, 'C');
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, '', 'R', 1, 'C');

		//baris 5

		/*if($data[0]['telaah_domainperjalanan']==1 || $data[0]['telaah_domainperjalanan']==2 ){
			$pdf->SetWidths(array(5,25,40,5,25,40));
			$border = array('LT','T','T','LT','T','TR');
			$align = array('','','','','','');
			$caption = array('III','Tiba Di',': '.$lokasi_tujuan[1]['kabupaten_kota'],'','Berangkat dari',': '.$lokasi_tujuan[1]['kabupaten_kota']);
			$pdf->Row($caption, $border, $align);
		} else {*/
		$pdf->Cell(5, 3, 'V', 'LT', 0);
		$pdf->Cell(25, 3, 'Tiba Di', 'T', 0);
		$pdf->Cell(40, 3, ':', 'T', 0);
		$pdf->Cell(5, 3, '', 'LT', 0);
		$pdf->Cell(25, 3, 'Berangkat dari', 'T', 0);
		$pdf->Cell(40, 3, ':', 'TR', 1);
		//}

		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, 'Pada Tanggal', 0, 0);
		$pdf->Cell(40, 3, ':', 'R', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, 'Ke', 0, 0);
		$pdf->Cell(40, 3, ':', 'R', 1);

		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, 'Jabatan', 0, 0);
		$pdf->Cell(40, 3, ':', 'R', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, 'Pada Tanggal', 0, 0);
		$pdf->Cell(40, 3, ':', 'R', 1);

		$pdf->Cell(70, 3, '', 'LR', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, 'Jabatan', 0, 0);
		$pdf->Cell(40, 3, ':', 'R', 1);

		$pdf->Cell(70, 3, '', 'LR', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, '', 0, 0);
		$pdf->Cell(40, 3, '', 'R', 1);

		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, '_______________________________', 'R', 0, 'C');
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, '_______________________________', 'R', 1, 'C');
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, '', 'R', 0, 'C');
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, '', 'R', 1, 'C');

		//baris 5
		if ($data[0]['skpd_id'] == 38) {
			/*if($data[0]['telaah_domainperjalanan']==1 || $data[0]['telaah_domainperjalanan']==2 ){
			$pdf->SetWidths(array(5,25,40,5,25,40));
			$border = array('LT','T','T','LT','T','TR');
			$align = array('','','','','','');
			$caption = array('III','Tiba Di',': '.$lokasi_tujuan[1]['kabupaten_kota'],'','Berangkat dari',': '.$lokasi_tujuan[1]['kabupaten_kota']);
			$pdf->Row($caption, $border, $align);
		} else {*/
			$pdf->Cell(5, 3, 'VI', 'LT', 0);
			$pdf->Cell(25, 3, 'Tiba Di', 'T', 0);
			$pdf->Cell(40, 3, ':', 'T', 0);
			$pdf->Cell(5, 3, '', 'LT', 0);
			$pdf->Cell(25, 3, 'Berangkat dari', 'T', 0);
			$pdf->Cell(40, 3, ':', 'TR', 1);
			//}

			$pdf->Cell(5, 3, '', 'L', 0);
			$pdf->Cell(25, 3, 'Pada Tanggal', 0, 0);
			$pdf->Cell(40, 3, ':', 'R', 0);
			$pdf->Cell(5, 3, '', 'L', 0);
			$pdf->Cell(25, 3, 'Ke', 0, 0);
			$pdf->Cell(40, 3, ':', 'R', 1);

			$pdf->Cell(5, 3, '', 'L', 0);
			$pdf->Cell(25, 3, 'Jabatan', 0, 0);
			$pdf->Cell(40, 3, ':', 'R', 0);
			$pdf->Cell(5, 3, '', 'L', 0);
			$pdf->Cell(25, 3, 'Pada Tanggal', 0, 0);
			$pdf->Cell(40, 3, ':', 'R', 1);

			$pdf->Cell(70, 3, '', 'LR', 0);
			$pdf->Cell(5, 3, '', 'L', 0);
			$pdf->Cell(25, 3, 'Jabatan', 0, 0);
			$pdf->Cell(40, 3, ':', 'R', 1);

			$pdf->Cell(70, 3, '', 'LR', 0);
			$pdf->Cell(5, 3, '', 'L', 0);
			$pdf->Cell(25, 3, '', 0, 0);
			$pdf->Cell(40, 3, '', 'R', 1);

			$pdf->Cell(5, 3, '', 'L', 0);
			//$pdf->Cell(65,4,$data[0]['pegawai_nama'],'R',0,'C');
			$pdf->Cell(65, 3, '_______________________________', 'R', 0, 'C');
			$pdf->Cell(5, 4, '', 'L', 0);
			//$pdf->Cell(65,4,$data[0]['pegawai_nama'],'R',1,'C');
			$pdf->Cell(65, 3, '_______________________________', 'R', 1, 'C');
			$pdf->Cell(5, 3, '', 'L', 0);
			//$pdf->Cell(65,4,'NIP '.$data[0]['pegawai_nip'],'R',0,'C');
			$pdf->Cell(65, 3, '', 'R', 0, 'C');
			$pdf->Cell(5, 3, '', 'L', 0);
			//$pdf->Cell(65,4,'NIP '.$data[0]['pegawai_nip'],'R',1,'C');
			$pdf->Cell(65, 3, '', 'R', 1, 'C');
		}

		//baris 6

		if (($data[0]['telaah_kategori'] == 1 && $data[0]['jenis_skpd'] != 7) || ($data[0]['telaah_kategori'] == 2)) {
			if ($data[0]['telaah_domainperjalanan'] == 1 || $data[0]['telaah_domainperjalanan'] == 2) {
				$pdf->SetWidths(array(5, 25, 40, 70));
				$border = array('LT', 'T', 'RT', 'LRT');
				$align = array('', '', '', '');
				if ($data[0]['skpd_id'] == 38) {
					$caption = array('VII.', 'Tiba Di', ': Kendari', 'Telah diperiksa dengan keterangan bahwa');
				} else {
					$caption = array('VI', 'Tiba Di', ': Kendari', 'Telah diperiksa dengan keterangan bahwa');
				}
				$pdf->Row($caption, $border, $align);
			} else {
				if ($data[0]['skpd_id'] == 38) {
					$pdf->Cell(5, 3, 'VII.', 'LT', 0);
				} else {
					$pdf->Cell(5, 3, 'VI.', 'LT', 0);
				}
				$pdf->Cell(25, 3, 'Tiba Di', 'T', 0);
				$pdf->Cell(40, 3, ':', 'RT', 0);
				$pdf->Cell(70, 3, 'Telah diperiksa dengan keterangan bahwa', 'lRT', 1);
			}
		} else {
			if ($data[0]['skpd_id'] == 38) {
				$pdf->Cell(5, 3, 'VII.', 'LT', 0);
			} else {
				$pdf->Cell(5, 3, 'VI.', 'LT', 0);
			}
			$pdf->Cell(25, 3, 'Tiba Di', 'T', 0);
			$pdf->Cell(40, 3, ':', 'RT', 0);
			$pdf->Cell(70, 3, 'Telah diperiksa dengan keterangan bahwa', 'lRT', 1);
		}

		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, '(Tempat Kedudukan)', 0, 0);
		$pdf->Cell(40, 3, ':', 'R', 0);
		$pdf->Cell(70, 3, 'perjalanan tersebut diatas telah benar dilakukan', 'LR', 1);

		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, 'Pada Tanggal', 0, 0);
		$pdf->Cell(40, 3, ': ' . date("d-m-Y", strtotime($data[0]['telaah_tanggalkembali'])), 'R', 0);
		$pdf->Cell(70, 3, 'atas perintahnya semata-mata untuk kepentingan', 'LR', 1);

		$pdf->Cell(70, 3, '', 'L', 0);
		$pdf->Cell(70, 3, 'jabatan dalam waktu yang sesingkat-singkatnya.', 'LR', 1);

		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, 'Pejabat yang memberi perintah', 'R', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, 'Pejabat yang memberi perintah', 'R', 1);

		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, '', 'R', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, '', 'R', 1);

		$pdf->SetWidths(array(5, 65, 5, 65));
		$border = array('L', 'R', 'L', 'R');
		$align = array('', '', '', '');
		$caption = array("", '', "", '');
		$pdf->Row($caption, $border, $align);

		$pdf->Cell(70, 3, '', 'LR', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, '', 0, 0);
		$pdf->Cell(40, 3, '', 'R', 1);

		$pdf->Cell(70, 3, '', 'LR', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, '', 0, 0);
		$pdf->Cell(40, 3, '', 'R', 1);

		$pdf->Cell(70, 3, '', 'LR', 0);
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(25, 3, '', 0, 0);
		$pdf->Cell(40, 3, '', 'R', 1);

		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, "_______________________________", 'R', 0, 'C');
		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, "_______________________________", 'R', 1, 'C');

		$pdf->Cell(5, 3, '', 'L', 0);
		$pdf->Cell(65, 3, '', 'R', 0, 'C');
		$pdf->Cell(5, 3, '', 'L', 0);

		$pdf->Cell(65, 3, '', 'R', 1, 'C');

		if ($data[0]['skpd_id'] == 38) {
			$pdf->Cell(5, 3, 'VIII.', 'LTB', 0);
		} else {
			$pdf->Cell(5, 3, 'VII.', 'LTB', 0);
		}
		$pdf->Cell(135, 3, 'Keterangan Lain-lain', 'RTB', 1);

		if ($data[0]['skpd_id'] == 38) {
			$pdf->Cell(5, 3, 'IX.', 'LT', 0);
		} else {
			$pdf->Cell(5, 3, 'VIII.', 'LT', 0);
		}

		$pdf->Cell(135, 3, 'PERHATIAN', 'RT', 1);

		$pdf->MultiCell(140, 3, 'Pejabat yang berwenang memberi SPPD pegawai yang melakukan Perjalanan Dinas, para pejabat yang mengesahkan tanggal berangkat/tiba, serta bendaharawan bertanggung jawab berdasarkan peraturan - peraturan Keuangan Negara, apabila Negara menderita rugi akibat kesalahan, kelalaian dan kealpaan (Lampiran SK. Menteri Keuangan tanggal 25-4-1974 Nomor B-296/MK/I/1974).', 1, 'J');

		//atur posisi 1.5 cm dari bawah
		$pdf->SetY(-15);
		//buat garis horizontal
		$pdf->Line(10, $pdf->GetY(), 315, $pdf->GetY());
		//Arial italic 9
		$pdf->SetFont('Arial', 'I', 9);
		//nomor halaman
		//$pdf->Cell(0,10,'Tanda Tangan Digital pada surat ini menggunakan Layanan BSRE',0,0,'R');

		$filename = 'SPPD-' . $telaah_id . '-' . $pegawai_id . '.pdf';
		$path = "./upload/doc_perjalanan/$filename";
		$pdf->Output($path, 'F');
	}

	## Create SPT
	function cetak_spt($telaah_id, $posisi, $kategori_pelaksana, $pegawai_id)
	{
		switch ($posisi) {
			case "esselon":
			case "camat":
			case "lurah":
			case "staff_setda":
			case "staff_dprd":
			case "staff_lurah":
			case "kapus":
			case "sekwan":
			case "staff_camat":
				$data  = $this->m_laporan->get_pelaksana_opd($telaah_id);
				$data2 = $this->m_laporan->get_pengikut2($telaah_id);
				break;
			case "kadis":
				if ($kategori_pelaksana == 1) {
					## Pelaksana
					$data = $this->m_laporan->get_pelaksana_opd($telaah_id);
				} else if ($kategori_pelaksana == 2) {
					## Pengikut
					$data = $this->m_laporan->get_pengikut_opd($telaah_id, $pegawai_id);
				}
				break;
			case "dprd":
				if ($kategori_pelaksana == 1) {
					$data = $this->m_laporan->get_pelaksana_dprd($telaah_id);
				} else if ($kategori_pelaksana == 2) {
					$data = $this->m_laporan->get_pengikut_dprd($telaah_id, $pegawai_id);
				}
				break;
			case "sekda":
				if ($kategori_pelaksana == 1) {
					## Pengikut
					$data  = $this->m_laporan->get_pelaksana_opd($telaah_id, $pegawai_id);
					$data2 = $this->m_laporan->get_pengikut2($telaah_id);
				} else if ($kategori_pelaksana == 2) {
					## Pelaksana
					$data  = $this->m_laporan->get_pelaksana_opd($telaah_id);
					$data2 = $this->m_laporan->get_pengikut2($telaah_id);
				}
				break;
			case "walikota":
				$data  = $this->m_laporan->get_pelaksana_walikota($telaah_id);
				$data2 = $this->m_laporan->get_pengikut2($telaah_id);
				break;
		}
		$lokasi_tujuan = $this->m_lokasi_tujuan->get2($telaah_id);

		$pdf = new PDF_MC_Table('P', 'mm', 'legal');
		$pdf->SetMargins(25, 3.175, 25);
		$pdf->SetAutoPageBreak(false);
		// membuat halaman baru
		$pdf->AddPage();
		$pdf->SetTopMargin(25);
		if ($data[0]['jenis_skpd'] == 7 && $data[0]['telaah_kategori'] != 11) {
			$dinas_kesehatan = $this->m_laporan->get_dinas_kesehatan();
			$pdf->Image('./upload/kop_surat/' . $dinas_kesehatan[0]['kop_surat'], 20, 16, 170, 30);
		} else if ($data[0]['telaah_kategori'] == 2) {
			if ($data[0]['telaah_domainperjalanan'] == 3) {
				$pdf->Image('./upload/kop_surat/' . $data[0]['kop_surat'], 20, 16, 170, 30);
			} else {
				$pdf->Image('./assets2/dist/img/garuda.png', 90, 10, 30, 30);
			}
		} else if ($posisi == "camat" || $posisi == "sekwan" || $posisi == "walikota" || ($posisi == "sekda" && $kategori_pelaksana == 2)) {
			$pdf->Image('./assets2/dist/img/garuda.png', 90, 10, 30, 30);
		} else {
			$pdf->Image('./upload/kop_surat/' . $data[0]['kop_surat'], 20, 16, 170, 30);
		}

		if (($posisi == "kadis" && $kategori_pelaksana == 1 && $data[0]['telaah_domainperjalanan'] == 1)
			|| ($posisi == "kadis" && $kategori_pelaksana == 1 && $data[0]['telaah_domainperjalanan'] == 2)
			|| $posisi == "camat" || $posisi == "sekwan" || $posisi == "walikota" || ($posisi == "sekda" && $kategori_pelaksana == 2)
		) {
			$pdf->SetFont('Times', 'B', 26);
			$pdf->Cell(10, 50, '', 0, 1);
			$pdf->Cell(160, 7, 'WALIKOTA KENDARI', 0, 1, 'C');

			$pdf->SetFont('Times', 'BU', 16);
			$pdf->Cell(10, 10, '', 0, 1);
		} else {
			$pdf->SetFont('Times', 'BU', 16);
			$pdf->Cell(10, 45, '', 0, 1);
		}

		$pdf->Cell(160, 7, 'SURAT PERINTAH TUGAS', 0, 1, 'C');
		// Memberikan space kebawah agar tidak terlalu rapat

		$pdf->SetFont('Times', '', 10);
		if ($data[0]['telaah_no_surat_tugas']) {
			$pdf->Cell(160, 6, 'No. ' . $data[0]['telaah_no_surat_tugas'], 0, 1, 'C');
		} else {
			$pdf->Cell(45, 6, '', 0, 0);
			$pdf->Cell(115, 6, 'No.', 0, 1);
		}

		$pdf->Cell(10, 4, '', 0, 1);

		$pdf->SetFont('Times', '', 10);
		$pdf->Cell(20, 6, 'Dari', 0, 0);

		if (($posisi == "kadis" && $kategori_pelaksana == 1 && $data[0]['telaah_domainperjalanan'] == 1)
			|| ($posisi == "kadis" && $kategori_pelaksana == 1 && $data[0]['telaah_domainperjalanan'] == 2)
			|| $posisi == "camat" || $posisi == "sekwan" || $posisi == "walikota" || ($posisi == "sekda" && $kategori_pelaksana == 2)
		) {
			$pdf->Cell(140, 6, ': Walikota Kendari', 0, 1);
		} else {
			$skpd_nama = strtolower($data[0]['skpd_nama']);
			$skpd_nama2 = ucwords($skpd_nama);
			if ($data[0]['jenis_skpd'] == 2 && $data[0]['telaah_kategori'] != 10) {
				$pdf->Cell(140, 6, ': SEKRETARIS DPRD KOTA KENDARI', 0, 1);
			} else if ($data[0]['jenis_skpd'] == 2 && $data[0]['telaah_kategori'] == 10) {
				$pdf->Cell(140, 6, ': Walikota Kendari', 0, 1);
			} else if ($data[0]['jenis_skpd'] == 7 && $data[0]['telaah_kategori'] == 1) {
				$pdf->Cell(140, 6, ': Kepala Dinas Kesehatan Kota Kendari', 0, 1);
			} else {
				if ($data[0]['telaah_skpd_id'] == 182) {
					$pdf->Cell(140, 6, ': Inspektur', 0, 1);
				} else {
					$pdf->Cell(140, 6, ': Kepala ' . $skpd_nama2, 0, 1);
				}
			}
		}


		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10, 7, '', 0, 1);

		$pdf->SetFont('Times', 'B', 14);
		$pdf->Cell(160, 7, 'MEMERINTAHKAN', 0, 1, 'C');

		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10, 4, '', 0, 1);

		$pdf->SetFont('Times', '', 10);
		$pdf->Cell(20, 6, 'Kepada', 0, 0);
		$pdf->Cell(3, 6, ':', 0, 0);
		$pdf->Cell(137, 6, '', 0, 1);


		// Inspektorat
		if ($data[0]['telaah_skpd_id'] == 182) {
			$data2 = array_map(function ($item) {
				return (array) $item;
			}, $data2);

			if (!empty($data2)) {
				$dataAnggota = array_merge($data, $data2);
			} else {
				$dataAnggota = $data;
			}

			// Buat attribute baru 'jabatan_order' untuk mengurutkan data berdasarkan jabatan
			foreach ($dataAnggota as &$item) {
				if (isset($item['telaah_jabatan_pengikut'])) {
					if ($item['telaah_jabatan_pengikut'] == 1) {
						$item['jabatan_order'] = 1;
					} else if ($item['telaah_jabatan_pengikut'] == 2) {
						$item['jabatan_order'] = 2;
					} else if ($item['telaah_jabatan_pengikut'] == 3) {
						$item['jabatan_order'] = 3;
					} else if ($item['telaah_jabatan_pengikut'] == 4) {
						$item['jabatan_order'] = 4;
					} else if ($item['telaah_jabatan_pengikut'] == 5) {
						$item['jabatan_order'] = 5;
					} else if ($item['telaah_jabatan_pengikut'] == 6) {
						$item['jabatan_order'] = 6;
					}
				} else {
					if ($item['pegawai_namajabatan'] == 'Penanggung Jawab') {
						$item['jabatan_order'] = 1;
					} else if ($item['pegawai_namajabatan'] == 'Pembantu Penanggung Jawab') {
						$item['jabatan_order'] = 2;
					} else if ($item['pegawai_namajabatan'] == 'Pengendali Teknis') {
						$item['jabatan_order'] = 3;
					} else if ($item['pegawai_namajabatan'] == 'Ketua Tim') {
						$item['jabatan_order'] = 4;
					} else if ($item['pegawai_namajabatan'] == 'Anggota') {
						$item['jabatan_order'] = 5;
					} else if ($item['pegawai_namajabatan'] == 'Admin Tim') {
						$item['jabatan_order'] = 6;
					} else {
						$item['jabatan_order'] = 99; // Jika jabatan tidak ada dalam list
					}
				}
			}
			unset($item); // Hapus reference untuk menghindari side effect

			// Urutkan $dataAnggota berdasarkan value 'jabatan_order' dari terendah ke tertinggi
			usort($dataAnggota, function ($a, $b) {
				return $a['jabatan_order'] - $b['jabatan_order'];
			});
			// $this->dd($dataAnggota);

			// START NAMA PEGAWAI PERJALANAN DINAS
			// Pelaksana
			// $pdf->SetFont('Times', '', 10);
			// $pdf->Cell(20, 6, '', 0, 0);
			// $pdf->Cell(3, 6, '', 0, 0);
			// $pdf->Cell(5, 6, '1.', 0, 0);
			// $pdf->Cell(40, 6, 'Nama', 0, 0);
			// $pdf->Cell(2, 6, ':', 0, 0);
			// $pdf->Cell(90, 6, $data[0]['pegawai_nama'], 0, 1);

			// if (
			// 	$data[0]['pegawai_jabatan'] == 1 || $data[0]['pegawai_jabatan'] == 16
			// 	|| $data[0]['pegawai_nip'] == 0 || $data[0]['pegawai_nip'] == 00 || $data[0]['pegawai_nip'] == 000
			// ) {
			// } else {
			// 	$pdf->SetFont('Times', '', 10);
			// 	$pdf->Cell(20, 6, '', 0, 0);
			// 	$pdf->Cell(3, 6, '', 0, 0);
			// 	$pdf->Cell(5, 6, '', 0, 0);
			// 	$pdf->Cell(40, 6, 'Pangkat/Golongan', 0, 0);
			// 	$pdf->Cell(2, 6, ':', 0, 0);
			// 	$pdf->Cell(90, 6, $data[0]['pangkat'] . ', Gol. ' . $data[0]['pegawai_golongan'], 0, 1);

			// 	$pdf->Cell(20, 6, '', 0, 0);
			// 	$pdf->Cell(3, 6, '', 0, 0);
			// 	$pdf->Cell(5, 6, '', 0, 0);
			// 	$pdf->Cell(40, 6, 'NIP', 0, 0);
			// 	$pdf->Cell(2, 6, ':', 0, 0);
			// 	$pdf->Cell(90, 6, $data[0]['pegawai_nip'], 0, 1);
			// }

			// $pdf->Cell(20, 6, '', 0, 0);
			// $pdf->Cell(3, 6, '', 0, 0);
			// $pdf->Cell(5, 6, '', 0, 0);
			// if ($data[0]['telaah_skpd_id'] == 182) {
			// 	$pdf->Cell(40, 6, 'Jabatan Dalam Perjalanan', 0, 0);
			// } else {
			// 	$pdf->Cell(40, 6, 'Jabatan', 0, 0);
			// }

			// $pdf->Cell(2, 6, ':', 0, 0);
			// if ($data[0]['telaah_skpd_id'] == 182) {
			// 	if ($data[0]['telaah_jabatan_pelaksana'] == 1) {
			// 		$pdf->MultiCell(90, 6, "Penanggung Jawab", 0, 1);
			// 	} else if ($data[0]['telaah_jabatan_pelaksana'] == 2) {
			// 		$pdf->MultiCell(90, 6, "Pembantu Penanggung Jawab", 0, 1);
			// 	} else if ($data[0]['telaah_jabatan_pelaksana'] == 3) {
			// 		$pdf->MultiCell(90, 6, "Pengendali Teknis", 0, 1);
			// 	} else if ($data[0]['telaah_jabatan_pelaksana'] == 4) {
			// 		$pdf->MultiCell(90, 6, "Ketua Tim", 0, 1);
			// 	} else if ($data[0]['telaah_jabatan_pelaksana'] == 5) {
			// 		$pdf->MultiCell(90, 6, "Anggota", 0, 1);
			// 	} else if ($data[0]['telaah_jabatan_pelaksana'] == 6) {
			// 		$pdf->MultiCell(90, 6, "Admin Tim", 0, 1);
			// 	} else { // TAMBAHAN KODE
			// 		$pdf->MultiCell(90, 6, $data[0]['pegawai_namajabatan'], 0, 1);
			// 	}
			// } else {
			// 	$pdf->MultiCell(90, 6, $data[0]['pegawai_namajabatan'], 0, 1);
			// }

			// Pengikut
			if (($posisi == "kadis" && $kategori_pelaksana == 1)
				|| ($posisi == "kadis" && $kategori_pelaksana == 2)
			) {
				$pdf->SetFont('Times', '', 10);
				$pdf->Cell(20, 6, '', 0, 0);
				$pdf->Cell(3, 6, '', 0, 0);
				$pdf->Cell(5, 6, '1.', 0, 0);
				$pdf->Cell(40, 6, 'Nama', 0, 0);
				$pdf->Cell(2, 6, ':', 0, 0);
				$pdf->Cell(90, 6, $data[0]['pegawai_nama'], 0, 1);

				if (
					$data[0]['pegawai_jabatan'] == 1 || $data[0]['pegawai_jabatan'] == 16
					|| $data[0]['pegawai_nip'] == 0 || $data[0]['pegawai_nip'] == 00 || $data[0]['pegawai_nip'] == 000
				) {
				} else {
					$pdf->SetFont('Times', '', 10);
					$pdf->Cell(20, 6, '', 0, 0);
					$pdf->Cell(3, 6, '', 0, 0);
					$pdf->Cell(5, 6, '', 0, 0);
					$pdf->Cell(40, 6, 'Pangkat/Golongan', 0, 0);
					$pdf->Cell(2, 6, ':', 0, 0);
					$pdf->Cell(90, 6, $data[0]['pangkat'] . ', Gol. ' . $data[0]['pegawai_golongan'], 0, 1);

					$pdf->Cell(20, 6, '', 0, 0);
					$pdf->Cell(3, 6, '', 0, 0);
					$pdf->Cell(5, 6, '', 0, 0);
					$pdf->Cell(40, 6, 'NIP', 0, 0);
					$pdf->Cell(2, 6, ':', 0, 0);
					$pdf->Cell(90, 6, $data[0]['pegawai_nip'], 0, 1);
				}

				$pdf->Cell(20, 6, '', 0, 0);
				$pdf->Cell(3, 6, '', 0, 0);
				$pdf->Cell(5, 6, '', 0, 0);
				if ($data[0]['telaah_skpd_id'] == 182) {
					$pdf->Cell(40, 6, 'Jabatan Dalam Perjalanan', 0, 0);
				} else {
					$pdf->Cell(40, 6, 'Jabatan', 0, 0);
				}

				$pdf->Cell(2, 6, ':', 0, 0);
				if ($data[0]['telaah_skpd_id'] == 182) {
					if ($data[0]['telaah_jabatan_pelaksana'] == 1) {
						$pdf->MultiCell(90, 6, "Penanggung Jawab", 0, 1);
					} else if ($data[0]['telaah_jabatan_pelaksana'] == 2) {
						$pdf->MultiCell(90, 6, "Pembantu Penanggung Jawab", 0, 1);
					} else if ($data[0]['telaah_jabatan_pelaksana'] == 3) {
						$pdf->MultiCell(90, 6, "Pengendali Teknis", 0, 1);
					} else if ($data[0]['telaah_jabatan_pelaksana'] == 4) {
						$pdf->MultiCell(90, 6, "Ketua Tim", 0, 1);
					} else if ($data[0]['telaah_jabatan_pelaksana'] == 5) {
						$pdf->MultiCell(90, 6, "Anggota", 0, 1);
					} else if ($data[0]['telaah_jabatan_pelaksana'] == 6) {
						$pdf->MultiCell(90, 6, "Admin Tim", 0, 1);
					} else { // TAMBAHAN KODE
						$pdf->MultiCell(90, 6, $data[0]['pegawai_namajabatan'], 0, 1);
					}
				} else {
					$pdf->MultiCell(90, 6, $data[0]['pegawai_namajabatan'], 0, 1);
				}
			} else {
				$no = 1;
				foreach ($dataAnggota as $v) {
					$pdf->SetFont('Times', '', 10);
					$pdf->Cell(20, 6, '', 0, 0);
					$pdf->Cell(3, 6, '', 0, 0);
					$pdf->Cell(5, 6, $no++ . '.', 0, 0);
					$pdf->Cell(40, 6, 'Nama', 0, 0);
					$pdf->Cell(2, 6, ':', 0, 0);
					$pdf->Cell(90, 6, $v['pegawai_nama'], 0, 1);

					if ($v['pegawai_jabatan'] == 16) {
					} else {
						if ($v['pangkat']) {
							$pdf->SetFont('Times', '', 10);
							$pdf->Cell(20, 6, '', 0, 0);
							$pdf->Cell(3, 6, '', 0, 0);
							$pdf->Cell(5, 6, '', 0, 0);
							$pdf->Cell(40, 6, 'Pangkat/Golongan', 0, 0);
							$pdf->Cell(2, 6, ':', 0, 0);
							$pdf->Cell(90, 6, $v['pangkat'] . ' - Gol. ' . $v['pegawai_golongan'], 0, 1);
						} else {
							$pdf->SetFont('Times', '', 10);
							$pdf->Cell(20, 6, '', 0, 0);
							$pdf->Cell(3, 6, '', 0, 0);
							$pdf->Cell(5, 6, '', 0, 0);
							$pdf->Cell(40, 6, 'Pangkat/Golongan', 0, 0);
							$pdf->Cell(2, 6, ':', 0, 0);
							$pdf->Cell(90, 6, '-', 0, 1);
						}

						if ($v['pegawai_nip'] != "000") {
							$pdf->Cell(20, 6, '', 0, 0);
							$pdf->Cell(3, 6, '', 0, 0);
							$pdf->Cell(5, 6, '', 0, 0);
							$pdf->Cell(40, 6, 'NIP', 0, 0);
							$pdf->Cell(2, 6, ':', 0, 0);
							$pdf->Cell(90, 6, $v['pegawai_nip'], 0, 1);
						} else {
							$pdf->Cell(20, 6, '', 0, 0);
							$pdf->Cell(3, 6, '', 0, 0);
							$pdf->Cell(5, 6, '', 0, 0);
							$pdf->Cell(40, 6, 'NIP', 0, 0);
							$pdf->Cell(2, 6, ':', 0, 0);
							$pdf->Cell(90, 6, '-', 0, 1);
						}
					}

					$pdf->Cell(20, 6, '', 0, 0);
					$pdf->Cell(3, 6, '', 0, 0);
					$pdf->Cell(5, 6, '', 0, 0);
					if ($data[0]['telaah_skpd_id'] == 182) {
						$pdf->Cell(40, 6, 'Jabatan Dalam Perjalanan', 0, 0);
					} else {
						$pdf->Cell(40, 6, 'Jabatan', 0, 0);
					}

					// $this->dd($dataAnggota);
					$pdf->Cell(2, 6, ':', 0, 0);
					if ($data[0]['telaah_skpd_id'] == 182) {
						// if ($v['telaah_jabatan_pengikut'] == 1 || $v['pegawai_namajabatan'] == 'Penanggung Jawab') {
						// 	$pdf->MultiCell(90, 6, "Penanggung Jawab", 0, 1);
						// } else if ($v['telaah_jabatan_pengikut'] == 2 || $v['pegawai_namajabatan'] == 'Pembantu Penanggung Jawab') {
						// 	$pdf->MultiCell(90, 6, "Pembantu Penanggung Jawab", 0, 1);
						// } else if ($v['telaah_jabatan_pengikut'] == 3 || $v['pegawai_namajabatan'] == 'Pengendali Teknis') {
						// 	$pdf->MultiCell(90, 6, "Pengendali Teknis", 0, 1);
						// } else if ($v['telaah_jabatan_pengikut'] == 4 || $v['pegawai_namajabatan'] == 'Ketua Tim') {
						// 	$pdf->MultiCell(90, 6, "Ketua Tim", 0, 1);
						// } else if ($v['telaah_jabatan_pengikut'] == 5 || $v['pegawai_namajabatan'] == 'Anggota') {
						// 	$pdf->MultiCell(90, 6, "Anggota", 0, 1);
						// } else if ($v['telaah_jabatan_pengikut'] == 6 || $v['pegawai_namajabatan'] == 'Admin Tim') {
						// 	$pdf->MultiCell(90, 6, "Admin Tim", 0, 1);
						// }
						if (isset($v['telaah_jabatan_pengikut'])) {
							if ($v['telaah_jabatan_pengikut'] == 1) {
								$pdf->MultiCell(90, 6, "Penanggung Jawab", 0, 1);
							} else if ($v['telaah_jabatan_pengikut'] == 2) {
								$pdf->MultiCell(90, 6, "Pembantu Penanggung Jawab", 0, 1);
							} else if ($v['telaah_jabatan_pengikut'] == 3) {
								$pdf->MultiCell(90, 6, "Pengendali Teknis", 0, 1);
							} else if ($v['telaah_jabatan_pengikut'] == 4) {
								$pdf->MultiCell(90, 6, "Ketua Tim", 0, 1);
							} else if ($v['telaah_jabatan_pengikut'] == 5) {
								$pdf->MultiCell(90, 6, "Anggota", 0, 1);
							} else if ($v['telaah_jabatan_pengikut'] == 6) {
								$pdf->MultiCell(90, 6, "Admin Tim", 0, 1);
							}
						} else {
							$pdf->MultiCell(90, 6, $v['pegawai_namajabatan'], 0, 1);
						}
					} else {
						$pdf->MultiCell(90, 6, $v['pegawai_namajabatan'], 0, 1);
					}
				}
			}
			// END NAMA PEGAWAI PERJALANAN DINAS
		}
		// Selain Inspektorat
		else {
			// START NAMA PEGAWAI PERJALANAN DINAS
			// Pelaksana
			$pdf->SetFont('Times', '', 10);
			$pdf->Cell(20, 6, '', 0, 0);
			$pdf->Cell(3, 6, '', 0, 0);
			$pdf->Cell(5, 6, '1.', 0, 0);
			$pdf->Cell(40, 6, 'Nama', 0, 0);
			$pdf->Cell(2, 6, ':', 0, 0);
			$pdf->Cell(90, 6, $data[0]['pegawai_nama'], 0, 1);

			if (
				$data[0]['pegawai_jabatan'] == 1 || $data[0]['pegawai_jabatan'] == 16
				|| $data[0]['pegawai_nip'] == 0 || $data[0]['pegawai_nip'] == 00 || $data[0]['pegawai_nip'] == 000
			) {
			} else {
				$pdf->SetFont('Times', '', 10);
				$pdf->Cell(20, 6, '', 0, 0);
				$pdf->Cell(3, 6, '', 0, 0);
				$pdf->Cell(5, 6, '', 0, 0);
				$pdf->Cell(40, 6, 'Pangkat/Golongan', 0, 0);
				$pdf->Cell(2, 6, ':', 0, 0);
				$pdf->Cell(90, 6, $data[0]['pangkat'] . ', Gol. ' . $data[0]['pegawai_golongan'], 0, 1);

				$pdf->Cell(20, 6, '', 0, 0);
				$pdf->Cell(3, 6, '', 0, 0);
				$pdf->Cell(5, 6, '', 0, 0);
				$pdf->Cell(40, 6, 'NIP', 0, 0);
				$pdf->Cell(2, 6, ':', 0, 0);
				$pdf->Cell(90, 6, $data[0]['pegawai_nip'], 0, 1);
			}

			$pdf->Cell(20, 6, '', 0, 0);
			$pdf->Cell(3, 6, '', 0, 0);
			$pdf->Cell(5, 6, '', 0, 0);
			if ($data[0]['telaah_skpd_id'] == 182) {
				$pdf->Cell(40, 6, 'Jabatan Dalam Perjalanan', 0, 0);
			} else {
				$pdf->Cell(40, 6, 'Jabatan', 0, 0);
			}

			$pdf->Cell(2, 6, ':', 0, 0);
			if ($data[0]['telaah_skpd_id'] == 182) {
				if ($data[0]['telaah_jabatan_pelaksana'] == 1) {
					$pdf->MultiCell(90, 6, "Penanggung Jawab", 0, 1);
				} else if ($data[0]['telaah_jabatan_pelaksana'] == 2) {
					$pdf->MultiCell(90, 6, "Pembantu Penanggung Jawab", 0, 1);
				} else if ($data[0]['telaah_jabatan_pelaksana'] == 3) {
					$pdf->MultiCell(90, 6, "Pengendali Teknis", 0, 1);
				} else if ($data[0]['telaah_jabatan_pelaksana'] == 4) {
					$pdf->MultiCell(90, 6, "Ketua Tim", 0, 1);
				} else if ($data[0]['telaah_jabatan_pelaksana'] == 5) {
					$pdf->MultiCell(90, 6, "Anggota", 0, 1);
				} else if ($data[0]['telaah_jabatan_pelaksana'] == 6) {
					$pdf->MultiCell(90, 6, "Admin Tim", 0, 1);
				} else { // TAMBAHAN KODE
					$pdf->MultiCell(90, 6, $data[0]['pegawai_namajabatan'], 0, 1);
				}
			} else {
				$pdf->MultiCell(90, 6, $data[0]['pegawai_namajabatan'], 0, 1);
			}

			// Pengikut
			if (($posisi == "kadis" && $kategori_pelaksana == 1)
				|| ($posisi == "kadis" && $kategori_pelaksana == 2)
			) {
			} else {
				$no = 2;
				foreach ($data2 as $v) {
					$pdf->SetFont('Times', '', 10);
					$pdf->Cell(20, 6, '', 0, 0);
					$pdf->Cell(3, 6, '', 0, 0);
					$pdf->Cell(5, 6, $no++ . '.', 0, 0);
					$pdf->Cell(40, 6, 'Nama', 0, 0);
					$pdf->Cell(2, 6, ':', 0, 0);
					$pdf->Cell(90, 6, $v->pegawai_nama, 0, 1);

					if ($v->pegawai_jabatan == 16) {
					} else {
						if ($v->pangkat) {
							$pdf->SetFont('Times', '', 10);
							$pdf->Cell(20, 6, '', 0, 0);
							$pdf->Cell(3, 6, '', 0, 0);
							$pdf->Cell(5, 6, '', 0, 0);
							$pdf->Cell(40, 6, 'Pangkat/Golongan', 0, 0);
							$pdf->Cell(2, 6, ':', 0, 0);
							$pdf->Cell(90, 6, $v->pangkat . ' - Gol. ' . $v->pegawai_golongan, 0, 1);
						} else {
							$pdf->SetFont('Times', '', 10);
							$pdf->Cell(20, 6, '', 0, 0);
							$pdf->Cell(3, 6, '', 0, 0);
							$pdf->Cell(5, 6, '', 0, 0);
							$pdf->Cell(40, 6, 'Pangkat/Golongan', 0, 0);
							$pdf->Cell(2, 6, ':', 0, 0);
							$pdf->Cell(90, 6, '-', 0, 1);
						}

						if ($v->pegawai_nip != "000") {
							$pdf->Cell(20, 6, '', 0, 0);
							$pdf->Cell(3, 6, '', 0, 0);
							$pdf->Cell(5, 6, '', 0, 0);
							$pdf->Cell(40, 6, 'NIP', 0, 0);
							$pdf->Cell(2, 6, ':', 0, 0);
							$pdf->Cell(90, 6, $v->pegawai_nip, 0, 1);
						} else {
							$pdf->Cell(20, 6, '', 0, 0);
							$pdf->Cell(3, 6, '', 0, 0);
							$pdf->Cell(5, 6, '', 0, 0);
							$pdf->Cell(40, 6, 'NIP', 0, 0);
							$pdf->Cell(2, 6, ':', 0, 0);
							$pdf->Cell(90, 6, '-', 0, 1);
						}
					}

					$pdf->Cell(20, 6, '', 0, 0);
					$pdf->Cell(3, 6, '', 0, 0);
					$pdf->Cell(5, 6, '', 0, 0);
					if ($data[0]['telaah_skpd_id'] == 182) {
						$pdf->Cell(40, 6, 'Jabatan Dalam Perjalanan', 0, 0);
					} else {
						$pdf->Cell(40, 6, 'Jabatan', 0, 0);
					}
					$pdf->Cell(2, 6, ':', 0, 0);
					if ($data[0]['telaah_skpd_id'] == 182) {
						if ($v->telaah_jabatan_pengikut == 1) {
							$pdf->MultiCell(90, 6, "Penanggung Jawab", 0, 1);
						} else if ($v->telaah_jabatan_pengikut == 2) {
							$pdf->MultiCell(90, 6, "Pembantu Penanggung Jawab", 0, 1);
						} else if ($v->telaah_jabatan_pengikut == 3) {
							$pdf->MultiCell(90, 6, "Pengendali Teknis", 0, 1);
						} else if ($v->telaah_jabatan_pengikut == 4) {
							$pdf->MultiCell(90, 6, "Ketua Tim", 0, 1);
						} else if ($v->telaah_jabatan_pengikut == 5) {
							$pdf->MultiCell(90, 6, "Anggota", 0, 1);
						} else if ($v->telaah_jabatan_pengikut == 6) {
							$pdf->MultiCell(90, 6, "Admin Tim", 0, 1);
						}
					} else {
						$pdf->MultiCell(90, 6, $v->pegawai_namajabatan, 0, 1);
					}
				}
			}
			// END NAMA PEGAWAI PERJALANAN DINAS
		}
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10, 7, '', 0, 1);

		$y = $pdf->GetY();
		if ($y > 215) {
			$pdf->AddPage();
		}

		$y = $pdf->GetY();
		if ($y > 320) {
			$pdf->AddPage();
		}

		$pdf->SetFont('Times', '', 10);
		$pdf->Cell(20, 6, 'Untuk', 0, 0);
		$pdf->Cell(3, 6, ':', 0, 0);

		//7
		$start_date = new DateTime($data[0]['telaah_tanggalberangkat']);
		$end_date = new DateTime($data[0]['telaah_tanggalkembali']);
		$interval = $start_date->diff($end_date);

		if ($data[0]['telaah_hari'] == 0) {
			$telaah_perihal = $data[0]['telaah_perihal'] . ' Di ' . $data[0]['telaah_kantortujuan'] . ' Selama ' . ($interval->days + 1) . ' hari dari tanggal ' .
				$this->tgl_indo($data[0]['telaah_tanggalberangkat']) . ' s/d ' . $this->tgl_indo($data[0]['telaah_tanggalkembali']) . '.';
		} else {
			$telaah_perihal = $data[0]['telaah_perihal'] . ' Di ' . $data[0]['telaah_kantortujuan'] . ' Selama ' . $data[0]['telaah_hari'] . ' hari dari tanggal ' .
				$this->tgl_indo($data[0]['telaah_tanggalberangkat']) . ' s/d ' . $this->tgl_indo($data[0]['telaah_tanggalkembali']) . '.';
		}

		$pdf->SetFont('Times', 'B', 10);
		$pdf->MultiCell(137, 6, $telaah_perihal, 0, 'J');

		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10, 7, '', 0, 1);

		$y = $pdf->GetY();
		if ($y > 329) {
			$pdf->AddPage();
		}

		$pdf->SetFont('Times', '', 10);
		if ($data[0]['jenis_skpd'] == 2) {
			if ($posisi == "sekwan") {
				$pdf->MultiCell(160, 6, 'Demikian Surat Tugas ini diberikan kepada yang bersangkutan untuk dilaksanakan dengan penuh rasa tanggung jawab.', 0, 'J');

				$y = $pdf->GetY();
				if ($y > 270) {
					$pdf->AddPage();
				}

				$pdf->Cell(100, 6, '', 0, 0);
				$pdf->Cell(50, 6, 'Ditetapkan Di Kendari', 0, 1);

				$pdf->SetFont('Times', '', 10);
				$pdf->Cell(100, 6, '', 0, 0);
				$pdf->Cell(50, 6, 'Pada Tanggal : ' . $this->tgl_indo($data[0]['telaah_tanggalspt']), 0, 1);
			} else {
				$pdf->MultiCell(160, 6, 'Demikian Surat Perintah Tugas ini diberikan untuk dilaksanakan dengan penuh rasa tanggung jawab dan apabila Surat Perintah Tugas Ini tidak dijalankan sesuai aturan Perundang-Undangan yang berlaku, maka yang bersangkutan dan/atau penerima Surat Perintah Tugas ini yang akan bertanggung jawab.', 0, 'J');

				$pdf->Cell(10, 4, '', 0, 1);

				$y = $pdf->GetY();
				if ($y > 270) {
					$pdf->AddPage();
				}

				$pdf->SetFont('Times', '', 10);
				$pdf->Cell(100, 6, '', 0, 0);
				$pdf->Cell(50, 6, 'Kendari, ' . $this->tgl_indo($data[0]['telaah_tanggalspt']), 0, 1);
			}
		} else {
			$pdf->MultiCell(160, 6, 'Demikian Surat Tugas ini diberikan kepada yang bersangkutan untuk dilaksanakan dengan penuh rasa tanggung jawab.', 0, 'J');

			$y = $pdf->GetY();
			if ($y > 270) {
				$pdf->AddPage();
			}

			$pdf->Cell(100, 6, '', 0, 0);
			$pdf->Cell(50, 6, 'Ditetapkan Di Kendari', 0, 1);

			$pdf->SetFont('Times', '', 10);
			$pdf->Cell(100, 6, '', 0, 0);
			$pdf->Cell(50, 6, 'Pada Tanggal : ' . $this->tgl_indo($data[0]['telaah_tanggalspt']), 0, 1);
		}

		$pdf->SetFont('Times', '', 10);
		$pdf->Cell(100, 6, '', 0, 0);
		$pdf->MultiCell(60, 6, '', 0, 1);

		$pdf->Cell(10, 40, '', 0, 1);
		$pdf->Cell(100, 6, '', 0, 0);
		$pdf->Cell(60, 6, '____________________________', 0, 1);

		$pdf->Cell(100, 6, '', 0, 0);
		$pdf->Cell(60, 6, '', 0, 1);
		$pdf->Cell(100, 6, '', 0, 0);
		$pdf->Cell(60, 6, '', 0, 1);

		$pdf->Cell(10, 7, '', 0, 1);
		if ($this->ion_auth->user()->row()->jenis_skpd == 2) {
			if ($posisi == "sekwan") {

				$pdf->SetFont('Times', '', 10);
				$y = $pdf->GetY();
				if ($y > 329) {
					$pdf->AddPage();
				}

				$pdf->Cell(20, 6, 'Tembusan Yth:', 0, 0);
				$pdf->Cell(3, 6, ':', 0, 0);
				$pdf->Cell(137, 6, '', 0, 1);

				$pdf->SetFont('Times', '', 10);
				$pdf->Cell(20, 6, '', 0, 0);
				$pdf->Cell(3, 6, '', 0, 0);
				$skpd_nama = strtolower($data[0]['skpd_nama']);
				$skpd_nama2 = ucwords($skpd_nama);
				//$pdf->Cell(137,6,'1. Kepala '.$skpd_nama2.' di Kendari',0,1);
				$pdf->Cell(137, 6, '1. Kepala Badan Kepegawaian dan Pengembangan SDM Kota Kendari di Kendari', 0, 1);

				$pdf->SetFont('Times', '', 10);
				$pdf->Cell(20, 6, '', 0, 0);
				$pdf->Cell(3, 6, '', 0, 0);
				$pdf->Cell(137, 6, '2. Bagian Organisasi dan Pemberdayaan Aparatur Kota Kendari di Kendari', 0, 1);
			}
		} else {
			$pdf->SetFont('Times', '', 10);
			$y = $pdf->GetY();
			if ($y > 329) {
				$pdf->AddPage();
			}
			$pdf->Cell(20, 6, 'Tembusan Yth:', 0, 0);
			$pdf->Cell(3, 6, ':', 0, 0);
			$pdf->Cell(137, 6, '', 0, 1);

			if (($posisi == "kadis" && $kategori_pelaksana == 1) || ($posisi == "sekda" && $kategori_pelaksana == 1)
				|| ($posisi == "sekda" && $kategori_pelaksana == 2)
			) {

				$pdf->SetFont('Times', '', 10);
				$pdf->Cell(20, 6, '', 0, 0);
				$pdf->Cell(3, 6, '', 0, 0);
				$skpd_nama = strtolower($data[0]['skpd_nama']);
				$skpd_nama2 = ucwords($skpd_nama);
				$pdf->Cell(137, 6, '1. Kepala ' . $skpd_nama2 . ' di Kendari', 0, 1);

				$pdf->SetFont('Times', '', 10);
				$pdf->Cell(20, 6, '', 0, 0);
				$pdf->Cell(3, 6, '', 0, 0);
				$pdf->Cell(137, 6, '2. Arsip', 0, 1);
			} else {

				$pdf->SetFont('Times', '', 10);
				$pdf->Cell(20, 6, '', 0, 0);
				$pdf->Cell(3, 6, '', 0, 0);
				$skpd_nama = strtolower($data[0]['skpd_nama']);
				$skpd_nama2 = ucwords($skpd_nama);
				//$pdf->Cell(137,6,'1. Kepala '.$skpd_nama2.' di Kendari',0,1);
				$pdf->Cell(137, 6, '1. Kepala Badan Kepegawaian dan Pengembangan SDM Kota Kendari di Kendari', 0, 1);

				$pdf->SetFont('Times', '', 10);
				$pdf->Cell(20, 6, '', 0, 0);
				$pdf->Cell(3, 6, '', 0, 0);
				$pdf->Cell(137, 6, '2. Bagian Organisasi dan Pemberdayaan Aparatur Kota Kendari di Kendari', 0, 1);
			}
		}


		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10, 10, '', 0, 1);

		$pdf->SetFont('Times', 'BI', 10);
		//$pdf->MultiCell(160, 5, 'Catatan : Jika Walikota berhalangan atau berada diluar Daerah maka penandatanganan SPT dapat dilakukan oleh Wakil Walikota atau Sekretaris Daerah jika Wakil Walikota juga berhalangan atau berada diluar daerah.', 0,'J');

		//atur posisi 1.5 cm dari bawah
		$pdf->SetY(-15);
		//buat garis horizontal
		$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
		//Arial italic 9
		$pdf->SetFont('Arial', 'I', 9);
		//nomor halaman
		$pdf->Cell(0, 10, 'Dokumen ini ditandatangani secara elektronik menggunakan Layanan BSrE', 0, 0, 'R');


		/**
		 * tambah tulisan gratifikasi dibagian bawah
		 */
		// atur posisi 2.7 cm dari bawah
		$pdf->SetY(-27);
		// Arial 
		$pdf->SetFont('Arial', 'I', 9);
		//nomor halaman
		$pdf->Cell(0, 10, 'Tidak Menerima Gratifikasi Dalam Bentuk Apapun Selama Pelaksanaan Tugas', 0, 0, 'C');
		/**
		 * End
		 */


		$filename = 'SPT-' . $telaah_id . '-' . $pegawai_id . '.pdf';
		$path = "./upload/doc_perjalanan/$filename";
		$pdf->Output($path, 'F');

		// $this->dd('oke');
	}

	## Create SPT DPRD
	function cetak_spt_dprd($telaah_id, $pegawai_id)
	{

		$data  = $this->m_laporan->get_pelaksana_dprd($telaah_id);
		$data2 = $this->m_laporan->get_pengikut_dprd2($telaah_id);

		// Merge data-data2
		$data2 = array_map(function ($item) {
			return (array) $item;
		}, $data2);

		$dataAnggota = array_merge($data, $data2);

		// Define the order of positions
		$jabatanOrder = [ // TAMBAHAN KODE
			'KETUA DPRD KOTA KENDARI'          => 1,
			'WAKIL KETUA DPRD'                 => 2,
			'WAKIL KETUA DPRD KOTA KENDARI'    => 2,
			'WAKIL KETUA I DPRD'               => 2,
			'WAKIL KETUA I DPRD KOTA KENDARI'  => 2,
			'WAKIL KETUA II DPRD'              => 3,
			'WAKIL KETUA II DPRD KOTA KENDARI' => 3,
			'KETUA KOMISI I'                   => 4,
			'KETUA KOMISI II'                  => 5,
			'KETUA KOMISI III'                 => 6,
			'KETUA BAPEMPERDA'                 => 7,
			'WAKIL KETUA KOMISI I'             => 8,
			'WAKIL KETUA KOMISI II'            => 9,
			'WAKIL KETUA KOMISI III'           => 10,
			'SEKRETARIS KOMISI I'              => 11,
			'SEKRETARIS KOMISI II'             => 12,
			'SEKRETARIS KOMISI III'            => 13,
			'ANGGOTA KOMISI I'                 => 14,
			'ANGGOTA KOMISI II'                => 15,
			'ANGGOTA KOMISI III'               => 16,
			'ANGGOTA'                          => 17,
		];

		// Sort the data based on the position order
		usort($dataAnggota, function ($a, $b) use ($jabatanOrder) {
			$aLevel = $jabatanOrder[trim($a['anggotadprd_jabatan'])] ?? 99;
			$bLevel = $jabatanOrder[trim($b['anggotadprd_jabatan'])] ?? 99;

			return $aLevel <=> $bLevel;
		});


		$pdf = new PDF_MC_Table('P', 'mm', 'legal');
		$pdf->SetMargins(25, 3.175, 25);
		$pdf->SetAutoPageBreak(false);

		$pdf->AddPage();
		$pdf->SetTopMargin(25);

		$pdf->Image('./upload/kop_surat/' . $data[0]['kop_surat2'], 20, 16, 170, 30);

		$pdf->SetFont('Times', 'BU', 16);
		$pdf->Cell(10, 45, '', 0, 1);

		$pdf->Cell(160, 7, 'SURAT PERINTAH TUGAS', 0, 1, 'C');

		$pdf->SetFont('Times', '', 10);
		$pdf->Cell(45, 6, '', 0, 0);
		$pdf->Cell(115, 6, 'NOMOR :', 0, 1);

		$pdf->Cell(10, 4, '', 0, 1);

		$pdf->SetFont('Times', '', 10);
		$pdf->Cell(5, 6, '1.', 0, 0);
		$pdf->Cell(15, 6, 'Dasar', 0, 0);
		$pdf->Cell(140, 6, ':', 0, 1);

		$pdf->Cell(5, 6, '', 0, 0);
		$pdf->Cell(5, 6, 'a.', 0, 0);
		$pdf->Cell(150, 6, 'Perda Kota Kendari No. 09 Tahun 2025 tentang APBD Kota Kendari Tahun ' . date('Y') . ';', 0, 1);

		$pdf->Cell(5, 6, '', 0, 0);
		$pdf->Cell(5, 6, 'b.', 0, 0);
		$pdf->Cell(150, 6, 'Peraturan Tata Tertib Dewan Perwakilan Rakyat Daerah Kota Kendari;', 0, 1);

		$pdf->Cell(5, 6, '', 0, 0);
		$pdf->Cell(5, 6, 'c.', 0, 0);
		$pdf->Cell(150, 6, 'Program Kerja DPRD Kota Kendari Tahun ' . date('Y') . ';', 0, 1);

		$pdf->Cell(5, 6, '', 0, 0);
		$pdf->Cell(5, 6, 'd.', 0, 0);
		$pdf->Cell(150, 6, 'DPA-SKPD Sekretariat DPRD Kota Kendari Tahun ' . date('Y') . ';', 0, 1);

		$pdf->Cell(5, 6, '', 0, 0);
		$pdf->Cell(155, 6, 'Menugaskan Kepada Anggota DPRD Kota Kendari yang tercantum namanya di bawah ini', 0, 1);

		$pdf->Cell(5, 6, '', 0, 1);

		$pdf->SetFont('Times', 'B', 10);
		$pdf->Cell(5, 6, 'No', 1, 0, 'C');
		$pdf->Cell(75, 6, 'N a m a', 1, 0, 'C');
		$pdf->Cell(70, 6, 'Jabatan', 1, 0, 'C');
		$pdf->Cell(10, 6, 'Ket', 1, 1, 'C');

		$pdf->SetFont('Times', '', 9);
		foreach ($dataAnggota as $i => $item) {
			$pdf->Cell(5, 6, $i + 1, 1, 0, 'C');
			$pdf->Cell(75, 6, $item['anggotadprd_name'], 1, 0, 'C');
			$pdf->Cell(70, 6, $item['anggotadprd_jabatan'], 1, 0, 'C');
			$pdf->Cell(10, 6, '', 1, 1, 'C');
		}

		$pdf->Cell(10, 4, '', 0, 1);

		$pdf->SetFont('Times', '', 10);
		$pdf->Cell(5, 6, '2.', 0, 0);
		$pdf->Cell(50, 6, 'Tujuan Perintah Tugas ', 0, 0);
		$pdf->Cell(10, 6, ':', 0, 1);

		$pdf->Cell(5, 6, '', 0, 0);
		$pdf->MultiCell(165, 6, $data[0]['telaah_perihal'], 0, 'J');

		$pdf->Cell(10, 4, '', 0, 1);

		$pdf->Cell(5, 6, '3.', 0, 0);
		$pdf->Cell(50, 6, 'Waktu dan Tempat Pelaksanaan', 0, 0);
		$pdf->Cell(10, 6, ':', 0, 1);

		$pdf->Cell(5, 6, '', 0, 0);
		$pdf->Cell(5, 6, 'a.', 0, 0);
		$pdf->Cell(25, 6, 'Hari/Tanggal', 0, 0);
		$pdf->Cell(5, 6, ':', 0, 0);
		$tanggal_berangkat = $data[0]['telaah_tanggalberangkat'];
		$tanggal_kembali = $data[0]['telaah_tanggalkembali'];
		$day1 = date('D', strtotime($tanggal_berangkat));
		$day2 = date('D', strtotime($tanggal_kembali));
		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);
		$pdf->MultiCell(100, 6, $dayList[$day1] . ', ' . $this->tgl_indo($data[0]['telaah_tanggalberangkat']) . ' s/d ' . $dayList[$day2] . ', ' . $this->tgl_indo($data[0]['telaah_tanggalkembali']) . '.', 0, 'J');

		$pdf->Cell(5, 6, '', 0, 0);
		$pdf->Cell(5, 6, 'b.', 0, 0);
		$pdf->Cell(25, 6, 'Tempat', 0, 0);
		$pdf->Cell(5, 6, ':', 0, 0);
		$pdf->MultiCell(100, 6, $data[0]['telaah_kantortujuan'], 0, 'J');

		$pdf->Cell(10, 4, '', 0, 1);

		$pdf->MultiCell(160, 6, 'Demikian Surat Perintah Tugas ini diberikan untuk dilaksanakan dengan penuh rasa tanggung jawab dan apabila Surat Perintah Tugas Ini tidak dijalankan sesuai aturan Perundang-Undangan yang berlaku, maka yang bersangkutan dan/atau penerima Surat Perintah Tugas ini yang akan bertanggung jawab.', 0, 'J');

		$pdf->Cell(10, 6, '', 0, 1);

		$pdf->Cell(100, 5, '', 0, 0);
		$pdf->Cell(35, 5, 'Kendari, ' . $this->tgl_indo($data[0]['telaah_tanggalspt']), 0, 1);

		$pdf->Cell(80, 5, '', 0, 0);
		$pdf->Cell(80, 5, 'KETUA DPRD KOTA KENDARI', 0, 1, 'C');

		$pdf->Cell(80, 5, '', 0, 0);

		$pdf->Cell(10, 20, '', 0, 1);
		$pdf->Cell(80, 5, '', 0, 0);
		$pdf->Cell(80, 5, '_______________________________', 0, 1, 'C');

		$pdf->SetFont('Times', '', 10);
		$pdf->Cell(20, 6, 'Tembusan Yth:', 0, 0);
		$pdf->Cell(3, 6, ':', 0, 0);
		$pdf->Cell(137, 6, '', 0, 1);

		$pdf->SetFont('Times', '', 10);
		$pdf->Cell(20, 6, '', 0, 0);
		$pdf->Cell(3, 6, '', 0, 0);
		$pdf->Cell(137, 6, '1. Walikota kendari di Kendari', 0, 1);

		$pdf->SetFont('Times', '', 10);
		$pdf->Cell(20, 6, '', 0, 0);
		$pdf->Cell(3, 6, '', 0, 0);
		$pdf->Cell(137, 6, '2. Arsip', 0, 1);

		//atur posisi 1.5 cm dari bawah
		$pdf->SetY(-15);
		//buat garis horizontal
		$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
		//Arial italic 9
		$pdf->SetFont('Arial', 'I', 9);
		//nomor halaman
		$pdf->Cell(0, 10, 'Dokumen ini ditandatangani secara elektronik menggunakan Layanan BSrE', 0, 0, 'R');

		$filename = 'SPT-' . $telaah_id . '-' . $pegawai_id . '.pdf';
		$path = "./upload/doc_perjalanan/$filename";
		$pdf->Output($path, 'F');
	}

	public function search_pegawai_ajax()
	{
		$term = $this->input->get('q');
		if (!$term) {
			echo json_encode(['items' => []]);
			return;
		}

		$data = $this->m_sekda->search_pegawai($term);

		$results = [];
		foreach ($data as $row) {
			$results[] = [
				'id' => $row->pegawai_id,
				'text' => $row->pegawai_nip . ' || ' . $row->pegawai_nama
			];
		}

		echo json_encode(['items' => $results]);
	}
}
