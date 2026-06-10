<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class History extends public_Controller {
	function __construct()
	{
		parent::__construct();
		error_reporting(0);
		$this->load->model('telaah/m_esselon');
		$this->load->model('telaah/m_dprd');
		$this->load->model('telaah/m_sekda');
		$this->load->model('telaah/m_pengikut');
		$this->load->model('telaah/m_lokasi_tujuan');
		$this->load->model('telaah/m_timeline');
		$this->load->model('telaah/m_telaah');
		$this->load->model('telaah/m_history');
		$this->load->model('setting/m_log');
		
	}
	
	public function index()
	{
		$pegawai_id = $this->encrypt->decode(base64_decode($this->input->get('pegawai_id')), $this->session->userdata('encrypt_key'));
		
		$config = array ();
		$config['page_query_string'] = TRUE;
		$config ["base_url"] = base_url () . "telaah/history/index/".$this->uri->segment(4)."?pegawai_id=".$this->input->get('pegawai_id')."";
		
		if($this->uri->segment(4)=="dprd"){
			$config ["total_rows"] = count($this->m_history->record_count_dprd($pegawai_id));
		} else if($this->uri->segment(4)=="walikota"){
			$config ["total_rows"] = count($this->m_history->record_count_walikota($pegawai_id));
		} else {
			$config ["total_rows"] = count($this->m_history->record_count($pegawai_id));
		}
		
		$config ["per_page"] = 25;
		$config ["uri_segment"] = 5;
		$choice = $config ["total_rows"] / $config ["per_page"];
		$config ["num_links"] = 5;
		
		$config ['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
		$config ['full_tag_close'] = '</ul>';
		$config ['first_link'] = 'First';
		$config ['last_link'] = 'Last';
		$config ['first_tag_open'] = '<li>';
		$config ['first_tag_close'] = '</li>';
		$config ['prev_link'] = 'Previous';
		$config ['prev_tag_open'] = '<li class="prev">';
		$config ['prev_tag_close'] = '</li>';
		$config ['next_link'] = 'Next';
		$config ['next_tag_open'] = '<li>';
		$config ['next_tag_close'] = '</li>';
		$config ['last_tag_open'] = '<li>';
		$config ['last_tag_close'] = '</li>';
		$config ['cur_tag_open'] = '<li class="active"><a href="#">';
		$config ['cur_tag_close'] = '</a></li>';
		$config ['num_tag_open'] = '<li>';
		$config ['num_tag_close'] = '</li>';
		
		if ($this->input->get('per_page') == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->input->get('per_page');
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		
		if($this->uri->segment(4)=="dprd"){
			$this->data['telaah_staf'] = $this->m_history->data_dprd($config ["per_page"], $page, $pegawai_id);
		} else if($this->uri->segment(4)=="walikota"){
			$this->data['telaah_staf'] = $this->m_history->data_walikota($config ["per_page"], $page, $pegawai_id);
		} else {
			$this->data['telaah_staf'] = $this->m_history->data($config ["per_page"], $page, $pegawai_id);
		}
		
		
		$this->render('telaah/list_telaah/history');
		
	}
	
	//View Detail Data
	public function detail()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		$data['entry'] =  $this->m_esselon->get($telaah_id);
		if(!isset($data['entry'][0]) || $data['entry'][0] == ""){
			redirect('history');
		} else {
			if($data['entry'][0]['telaah_kategori']==3){
				$data['data'] =  $this->m_dprd->get($telaah_id);
			} else if ($data['entry'][0]['telaah_kategori']==8){
				$data['data'] = $this->m_sekda->getWalikota($telaah_id);
			} else {
				$data['data'] = $this->m_esselon->get($telaah_id);
			}
			
			$data['pengikut'] =  $this->m_pengikut->data($telaah_id);
			$data['pengikut2'] =  $this->m_pengikut->data2($telaah_id);
			$this->load->view('templates/_parts/header');
			$this->load->view('telaah/list_telaah/qr', $data);
			//$this->load->view('templates/_parts/footer');
		}
		
		
	}
}