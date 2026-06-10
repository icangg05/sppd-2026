<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Detail_anggaran extends public_Controller {
	function __construct()
	{
		parent::__construct();
		//error_reporting(0);
		$this->load->model('telaah/m_telaah');
		$this->load->model('telaah/m_pengikut');
		$this->load->model('telaah/m_walikota');
		$this->load->model('setting_admin/m_anggaran');
		$this->load->model('telaah/m_lokasi_tujuan');
		$this->load->model('setting_admin/m_history');
		$this->load->model('telaah/m_timeline');
		$this->load->model('m_widget');
		$this->load->model('telaah/m_dprd');
		$this->load->model('setting/m_log');
		if(!($this->ion_auth->user()->row()->id)){
			redirect('login');
		}
	}
	
	//View All Data
	public function index()
	{
		$config = array ();
		$config ["base_url"] = base_url () . "telaah/detail_anggaran/index";
		$config ["total_rows"] = count($this->m_walikota->data2('','',''));
		$config ["per_page"] = 25;
		$config ["uri_segment"] = 4;
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
		
		if ($this->uri->segment ( 4 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 4 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 4 )) ? $this->uri->segment ( 4 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['telaah_staf'] = $this->m_walikota->data2($config ["per_page"], $page,'');
		$this->data['skpd'] = $this->m_walikota->skpd();
		$this->data['total_anggaran_keseluruhan'] = $this->m_walikota->total_anggaran_keseluruhan();
		$this->data['total_sisa_anggaran'] = $this->m_walikota->total_sisa_anggaran($this->ion_auth->user()->row()->skpd_id);
		$this->data['jenis_anggaran'] = '';
		$this->render('telaah/disposisi/detail_anggaran');
	}
	
	//View All Data
	public function index2()
	{
		$config = array ();
		$config ["base_url"] = base_url () . "walikota/detail_anggaran/index2/".$this->uri->segment(4);
		$config ["total_rows"] = count($this->m_walikota->record_count($this->uri->segment(4)));
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
		
		if ($this->uri->segment ( 5 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 5 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 5 )) ? $this->uri->segment ( 5 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['telaah_staf'] = $this->m_walikota->data2($config ["per_page"], $page, $this->uri->segment(4));
		$this->data['skpd'] = $this->m_walikota->skpd();
		switch($this->uri->segment(4)){
			case 1	: $this->data['total_anggaran_keseluruhan'] = $this->m_walikota->total_anggaran_dalam_daerah();
			case 2	: $this->data['total_anggaran_keseluruhan'] = $this->m_walikota->total_anggaran_luar_daerah();
		}
		$this->data['jenis_anggaran'] = $this->uri->segment(4);
		$this->render('telaah/disposisi/detail_anggaran');
	}
	
	//View All Data
	public function sekretariat()
	{
		$config = array ();
		$config ["base_url"] = base_url () . "walikota/detail_anggaran/sekretariat";
		$config ["total_rows"] = $this->m_walikota->sekretariat('','');
		$config ["per_page"] = 25;
		$config ["uri_segment"] = 4;
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
		
		if ($this->uri->segment ( 4 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 4 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 4 )) ? $this->uri->segment ( 4 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['telaah_staf'] = $this->m_walikota->sekretariat($config ["per_page"], $page);
		$this->data['skpd'] = $this->m_walikota->skpd();
		$this->data['total_anggaran_keseluruhan'] = $this->m_walikota->total_anggaran_keseluruhan();
		$this->data['total_sisa_anggaran'] = $this->m_walikota->total_sisa_anggaran($this->ion_auth->user()->row()->skpd_id);
		$this->data['jenis_anggaran'] = '';
		$this->render('telaah/disposisi/detail_anggaran');
	}
	
	//View Data Search
	public function search()
	{
		if($this->input->post('submit')){
			$column = 'table_anggaran.skpd_id';
			$query = $this->input->post('data');
			
			$option = array(
				'user_column'=>$column,
				'user_data'=>$query
				);
			$this->session->set_userdata($option);
		}else{
			$query = $this->uri->segment ( 4 );
			$column = $this->uri->segment ( 5 );
		}
		
		$config = array ();
		$config ["base_url"] = base_url () . "telaah/list_telaah/search/".$query."/".$column;
		$config ["total_rows"] = count($this->m_walikota->record_count_search($column,$query,''));
		$config ["per_page"] = 25;
		$config ["uri_segment"] = 6;
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
		
		if ($this->uri->segment ( 4 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 4 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 6 )) ? $this->uri->segment ( 6 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['telaah_staf'] = $this->m_walikota->data_search($column,$query,$config ["per_page"], $page,'');
		$this->data['skpd'] = $this->m_walikota->skpd();
		$this->data['total_anggaran_keseluruhan'] = $this->m_walikota->total_anggaran_keseluruhan();
		$this->data['total_sisa_anggaran'] = $this->m_walikota->total_sisa_anggaran($this->ion_auth->user()->row()->skpd_id);
		$this->render('telaah/disposisi/detail_anggaran');
	}
	
	public function pengguna_anggaran(){
		if($this->uri->segment(5)){
			$this->data['telaah_staf'] = $this->m_walikota->anggaran($this->uri->segment(4),$this->uri->segment(5));
		} else {
			$this->data['telaah_staf'] = $this->m_walikota->anggaran($this->uri->segment(4),'');
		}
		$this->render('telaah/disposisi/pengguna_anggaran');
	}
	
	public function pengguna_anggaran_sekretariat(){
		if($this->uri->segment(5)){
			$this->data['telaah_staf'] = $this->m_walikota->anggaran_sekretariat($this->uri->segment(4),$this->uri->segment(5));
		} else {
			$this->data['telaah_staf'] = $this->m_walikota->anggaran_sekretariat($this->uri->segment(4),'');
		}
		$this->render('telaah/disposisi/pengguna_anggaran');
	}

	public function rekap_data(){
		//$this->data['rekap'] =  $this->m_widget->getRekap();
		$this->data['rekap'] 	=  $this->m_widget->getRekap();
		$this->data['skpd'] 	=  $this->m_widget->getSKPD();
		$this->render('widget');
	}

	public function detail_rekap_data(){
		$this->data['rekap'] = $this->m_widget->getDetailRekap($this->uri->segment(4));
		$this->render('getDetailRekap');
	}


	public function getDataBySKPD(){
		$namaSKPD=explode('-', $this->input->post('skpd'));
		$this->data['nama_skpd']	= $namaSKPD[1];
		$this->data['rekap'] 			= $this->m_widget->getDetailSKPD($namaSKPD[0]);
		$this->render('getRekapSkpd');
	}
	
	
}