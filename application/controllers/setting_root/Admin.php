<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin extends public_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('setting_root/m_admin');
		$this->load->model('setting/m_log');	
		$this->load->model('telaah/m_pengikut');
		$this->load->model('telaah/m_sekda');
		$this->load->model('telaah/m_relasi_sekda');
		$this->load->model('telaah/m_telaah');
		$this->load->model('telaah/m_timeline');
		
		if((!$this->ion_auth->get_users_groups()->row()->id == 9) || (!$this->ion_auth->get_users_groups()->row()->id == 100))
		{
			redirect('login');
		}
	}
	
	//View All Data Esselon
	public function index_esselon()
	{
		$config = array ();
		$config ["base_url"] = base_url () . "setting_root/admin/index_esselon/";
		$config ["total_rows"] = $this->m_admin->record_count_esselon();
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
		$this->data['esselon'] = $this->m_admin->data_esselon($config ["per_page"], $page);
		$this->render('list_telaah/esselon/content');
	}
	
	//View Data Search Esselon
	public function search_esselon()
	{
		if($this->input->post('submit')){
			$column = 'pegawai_nama';
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
		$config ["base_url"] = base_url () . "setting_root/admin/search_esselon/".$query."/".$column;
		$config ["total_rows"] = $this->m_admin->record_count_search_esselon($column,$query);
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
		
		if ($this->uri->segment ( 6 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 6 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 6 )) ? $this->uri->segment ( 6 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['esselon'] = $this->m_admin->data_search_esselon($column,$query,$config ["per_page"], $page);
		$this->render('list_telaah/esselon/content');
	}
	
	//View Data Result Esselon
	public function result_esselon()
	{
		$column = 'table_telaah.telaah_status';
		$query = $this->input->get('status');
		
		$config = array ();
		$config['page_query_string'] = TRUE;
		$config ["base_url"] = base_url () . "setting_root/admin/result_esselon?status=".$this->input->get('status')."";
		$config ["total_rows"] = $this->m_admin->record_count_search_esselon($column,$query);
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
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['esselon'] = $this->m_admin->data_search_esselon($column,$query,$config ["per_page"], $this->input->get('per_page'));
		$this->render('list_telaah/esselon/content');
	}
	
	//View Detail Data Esselon
	public function detail_esselon()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		$this->data['entry'] =  $this->m_admin->get_esselon($telaah_id);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('list_telaah/esselon');
		} else {
			$this->data['pengikut'] =  $this->m_pengikut->data($telaah_id);
			$this->data['timeline'] =  $this->m_admin->getTimeline1_esselon($telaah_id);
			$this->render('list_telaah/esselon/detail');
		}
	}
	
	//View All Data Kadis
	public function index_kadis()
	{
		$config = array ();
		$config ["base_url"] = base_url () . "setting_root/admin/index_kadis";
		$config ["total_rows"] = $this->m_admin->record_count_kadis();
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
		$this->data['kadis'] = $this->m_admin->data_kadis($config ["per_page"], $page);
		$this->render('list_telaah/kadis/content');
	}
	
	//View Data Search Kadis
	public function search_kadis()
	{
		if($this->input->post('submit')){
			$column = 'pegawai_nama';
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
		$config ["base_url"] = base_url () . "setting_root/admin/search_kadis/".$query."/".$column;
		$config ["total_rows"] = $this->m_admin->record_count_search_kadis($column,$query);
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
		
		if ($this->uri->segment ( 6 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 6 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 6 )) ? $this->uri->segment ( 6 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['kadis'] = $this->m_admin->data_search_kadis($column,$query,$config ["per_page"], $page);
		$this->render('list_telaah/kadis/content');
	}
	
	//View Data Result Kadis
	public function result_kadis()
	{
		$column = 'table_telaah.telaah_status';
		$query = $this->input->get('status');
		
		$config = array ();
		$config['page_query_string'] = TRUE;
		$config ["base_url"] = base_url () . "setting_root/admin/result_kadis?status=".$this->input->get('status')."";
		$config ["total_rows"] = $this->m_admin->record_count_search_kadis($column,$query);
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
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['kadis'] = $this->m_admin->data_search_kadis($column,$query,$config ["per_page"], $this->input->get('per_page'));
		$this->render('list_telaah/kadis/content');
	}
	
	//View Detail Data Kadis
	public function detail_kadis()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		$this->data['entry'] =  $this->m_admin->get_kadis($telaah_id);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('list_telaah/kadis');
		} else {
			$this->data['pengikut'] =  $this->m_pengikut->data($telaah_id);
			$this->data['timeline'] =  $this->m_admin->getTimeline_kadis($telaah_id);
			$this->render('list_telaah/kadis/detail');
		}
	}
	
	//View All Data DPRD
	public function index_dprd()
	{
		$config = array ();
		$config ["base_url"] = base_url () . "setting_root/admin/index_dprd";
		$config ["total_rows"] = $this->m_admin->record_count_dprd();
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
		$this->data['dprd'] = $this->m_admin->data_dprd($config ["per_page"], $page);
		$this->render('list_telaah/dprd/content');
	}
	
	//View Data Search DPRD
	public function search_dprd()
	{
		if($this->input->post('submit')){
			$column = 'anggotadprd_name';
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
		$config ["base_url"] = base_url () . "setting_root/admin/search_dprd/".$query."/".$column;
		$config ["total_rows"] = $this->m_admin->record_count_search_dprd($column,$query);
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
		
		if ($this->uri->segment ( 6 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 6 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 6 )) ? $this->uri->segment ( 6 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['dprd'] = $this->m_admin->data_search_dprd($column,$query,$config ["per_page"], $page);
		$this->render('list_telaah/dprd/content');
	}
	
	//View Data Result DPRD
	public function result_dprd()
	{
		$column = 'table_telaah.telaah_status';
		$query = $this->input->get('status');
		
		$config = array ();
		$config['page_query_string'] = TRUE;
		$config ["base_url"] = base_url () . "setting_root/admin/result_dprd?status=".$this->input->get('status')."";
		$config ["total_rows"] = $this->m_admin->record_count_search_dprd($column,$query);
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
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['dprd'] = $this->m_admin->data_search_dprd($column,$query,$config ["per_page"], $this->input->get('per_page'));
		$this->render('list_telaah/dprd/content');
	}
	
	//View Detail Data DPRD
	public function detail_dprd()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		$this->data['entry'] =  $this->m_admin->get_dprd($telaah_id);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('list_telaah/dprd');
		} else {
			$this->data['pengikut'] =  $this->m_pengikut->data($telaah_id);
			$this->data['timeline'] =  $this->m_admin->getTimeline_dprd($telaah_id);
			$this->render('list_telaah/dprd/detail');
		}
	}
	
	//View All Data Sekda
	public function index_sekda()
	{
		$config = array ();
		$config ["base_url"] = base_url () . "setting_root/admin/index_sekda";
		$config ["total_rows"] = $this->m_admin->record_count_sekda();
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
		$this->data['sekda'] = $this->m_admin->data_sekda($config ["per_page"], $page);
		$this->render('list_telaah/sekda/content');
	}
	
	//View Data Search Sekda
	public function search_sekda()
	{
		if($this->input->post('submit')){
			$column = 'pegawai_nama';
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
		$config ["base_url"] = base_url () . "setting_root/admin/search_sekda/".$query."/".$column;
		$config ["total_rows"] = $this->m_admin->record_count_search_sekda($column,$query);
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
		
		if ($this->uri->segment ( 6 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 6 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 6 )) ? $this->uri->segment ( 6 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['sekda'] = $this->m_admin->data_search_sekda($column,$query,$config ["per_page"], $page);
		$this->render('list_telaah/sekda/content');
	}
	
	//View Data Result Sekda
	public function result_sekda()
	{
		$column = 'table_telaah.telaah_status';
		$query = $this->input->get('status');
		
		$config = array ();
		$config ['page_query_string'] = TRUE;
		$config ["base_url"] = base_url () . "setting_root/admin/result_sekda?status=".$this->input->get('status')."";
		$config ["total_rows"] = $this->m_admin->record_count_search_sekda($column,$query);
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
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['sekda'] = $this->m_admin->data_search_sekda($column,$query,$config ["per_page"],$this->input->get('per_page'));
		$this->render('list_telaah/sekda/content');
	}
	
	//View Detail Data Sekda
	public function detail_sekda()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		$this->data['entry'] =  $this->m_admin->get_sekda($telaah_id);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('list_telaah/sekda');
		} else {
			$this->data['pengikut'] =  $this->m_pengikut->data($telaah_id);
			$this->data['timeline'] =  $this->m_admin->getTimeline_sekda($telaah_id);
			$this->render('list_telaah/sekda/detail');
		}
	}
	
	//View All Data Camat
	public function index_camat()
	{
		$config = array ();
		$config ["base_url"] = base_url () . "setting_root/admin/index_camat";
		$config ["total_rows"] = $this->m_admin->record_count_camat();
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
		$this->data['camat'] = $this->m_admin->data_camat($config ["per_page"], $page);
		$this->render('list_telaah/camat/content');
	}
	
	//View Data Search Camat
	public function search_camat()
	{
		if($this->input->post('submit')){
			$column = 'pegawai_nama';
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
		$config ["base_url"] = base_url () . "setting_root/admin/search_camat/".$query."/".$column;
		$config ["total_rows"] = $this->m_admin->record_count_search_camat($column,$query);
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
		
		if ($this->uri->segment ( 6 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 6 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 6 )) ? $this->uri->segment ( 6 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['camat'] = $this->m_admin->data_search_camat($column,$query,$config ["per_page"], $page);
		$this->render('list_telaah/camat/content');
	}
	
	//View Detail Data Camat
	public function detail_camat()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		$this->data['entry'] =  $this->m_admin->get_sekda($telaah_id);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('list_telaah/camat');
		} else {
			$this->data['pengikut'] =  $this->m_pengikut->data($telaah_id);
			$this->data['timeline'] =  $this->m_admin->getTimeline_camat($telaah_id);
			$this->render('list_telaah/camat/detail');
		}
	}
	
	//View All Data Lurah
	public function index_lurah()
	{
		$config = array ();
		$config ["base_url"] = base_url () . "setting_root/admin/index_lurah";
		$config ["total_rows"] = $this->m_admin->record_count_lurah();
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
		$this->data['lurah'] = $this->m_admin->data_lurah($config ["per_page"], $page);
		$this->render('list_telaah/lurah/content');
	}
	
	//View Data Search Lurah
	public function search_lurah()
	{
		if($this->input->post('submit')){
			$column = 'pegawai_nama';
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
		$config ["base_url"] = base_url () . "setting_root/admin/search_lurah/".$query."/".$column;
		$config ["total_rows"] = $this->m_admin->record_count_search($column,$query);
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
		
		if ($this->uri->segment ( 6 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 6 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 6 )) ? $this->uri->segment ( 6 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['lurah'] = $this->m_admin->data_search($column,$query,$config ["per_page"], $page);
		$this->render('list_telaah/lurah/content');
	}
	
	//View Detail Data Lurah
	public function detail_lurah()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		$this->data['entry'] =  $this->m_admin->get($telaah_id);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('list_telaah/lurah');
		} else {
			$this->data['pengikut'] =  $this->m_pengikut->data($telaah_id);
			$this->data['timeline'] =  $this->m_admin->getTimeline($telaah_id);
			$this->render('list_telaah/lurah/detail');
		}
	}
	
	//View All Data Walikota
	public function index_walikota()
	{
		$config = array ();
		$config ["base_url"] = base_url () . "setting_root/admin/index_walikota";
		$config ["total_rows"] = $this->m_admin->record_countwalikota();
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
		$this->data['telaah_staf'] = $this->m_admin->datawalikota2($config ["per_page"], $page);
		$this->render('walikota/content');
	}
	
	//View Data Search Walikota
	public function search_walikota()
	{
		if($this->input->post('submit')){
			$column = 'pegawai_nama';
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
		$config ["base_url"] = base_url () . "setting_root/admin/search_walikota/".$query."/".$column;
		$config ["total_rows"] = $this->m_admin->record_count_search_walikota($column,$query);
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
		
		if ($this->uri->segment ( 6 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 6 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 6 )) ? $this->uri->segment ( 6 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['telaah_staf'] = $this->m_admin->data_search_walikota($column,$query,$config ["per_page"], $page);
		
		$this->render('walikota/content');
	}

	//View All Data Staff Sekda
	public function index_kasubagstaf()
	{
		$sekda = $this->m_relasi_sekda->getsubbagian(671);
		$config = array ();
		$config ["base_url"] = base_url () . "setting_root/admin/index_kasubagstaf";
		$config ["total_rows"] = $this->m_sekda->record_count3($sekda[0]['subbagian_id']);
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
		$this->data['sekda'] = $this->m_sekda->datakasubagstaf($config ["per_page"], $page, $sekda[0]['subbagian_id']);
		$this->render('list_telaah/sekda/contentkasubagstaf');
	}

	//View All Data Sekwan
	public function index_sekwan()
	{
		$config = array ();
		$config ["base_url"] = base_url () . "setting_root/admin/index_sekwan";
		$config ["total_rows"] = $this->m_admin->record_count_sekwan();
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
		}else {
			$this->data ['number'] = $this->uri->segment ( 4 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 4 )) ? $this->uri->segment ( 4 ) : 0;
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['sekwan'] = $this->m_admin->data_sekwan($config ["per_page"], $page);
		$this->render('list_telaah/sekwan/content');
	}
	
	//View Data Search Sekwan
	public function search_sekwan()
	{
		if($this->input->post('submit')){
			$column = 'pegawai_nama';
			$query = $this->input->post('data');
			$option = array(
				'user_column'=>$column,
				'user_data'=>$query
				);
			$this->session->set_userdata($option);
		}else {
			$query = $this->uri->segment ( 4 );
			$column = $this->uri->segment ( 5 );
		}
		
		$config = array ();
		$config ["base_url"] = base_url () . "setting_root/admin/search_sekwan/".$query."/".$column;
		$config ["total_rows"] = $this->m_admin->record_count_search_sekwan($column,$query);
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
		
		if ($this->uri->segment ( 6 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 6 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 6 )) ? $this->uri->segment ( 6 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['sekwan'] = $this->m_admin->data_search_sekwan($column,$query,$config ["per_page"], $page);
		$this->render('list_telaah/sekwan/content');
	}

	//View Data Result Sekwan
	public function result_sekwan()
	{
		$column = 'table_telaah.telaah_status';
		$query = $this->input->get('status');
		
		$config = array ();
		$config['page_query_string'] = TRUE;
		$config ["base_url"] = base_url () . "setting_root/admin/result_sekwan?status=".$this->input->get('status')."";
		$config ["total_rows"] = $this->m_admin->record_count_search_sekwan($column,$query);
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
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['sekwan'] = $this->m_admin->data_search_sekwan($column,$query,$config ["per_page"], $this->input->get('per_page'));
		$this->render('list_telaah/sekwan/content');
	}
	
	//View Detail Data Sekwan
	public function detail_sekwan()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		$this->data['entry'] =  $this->m_sekwan->get($telaah_id);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('list_telaah/sekwan');
		} else {
			$this->data['pengikut'] =  $this->m_pengikut->data($telaah_id);
			$this->data['timeline'] =  $this->m_sekwan->getTimeline10($telaah_id);
			$this->render('list_telaah/sekwan/detail');
		}
	}
	
	//View All Data Staff DPRD
	public function index_staffdprd()
	{
		$config = array ();
		$config ["base_url"] = base_url () . "setting_root/admin/index_staffdprd";
		$config ["total_rows"] = $this->m_admin->record_count_staffdprd();
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
		$this->data['staff_dprd'] = $this->m_admin->data_staffdprd($config ["per_page"], $page);
		$this->render('list_telaah/staff_dprd/content');
	}
	
	//View Data Search Staff DPRD
	public function search_staffdprd()
	{
		if($this->input->post('submit')){
			$column = 'pegawai_nama';
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
		$config ["base_url"] = base_url () . "setting_root/admin/search_staffdprd/".$query."/".$column;
		$config ["total_rows"] = $this->m_admin->record_count_search_staffdprd($column,$query);
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
		
		if ($this->uri->segment ( 6 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 6 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 6 )) ? $this->uri->segment ( 6 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['staff_dprd'] = $this->m_admin->data_search_staffdprd($column,$query,$config ["per_page"], $page);
		$this->render('list_telaah/staff_dprd/content');
	}
	
	//View Data Result Staff DPRD
	public function result_staffdprd()
	{
		$column = 'table_telaah.telaah_status';
		$query = $this->input->get('status');
		
		$config = array ();
		$config ['page_query_string'] = TRUE;
		$config ["base_url"] = base_url () . "setting_root/admin/result_staffdprd?status=".$this->input->get('status')."";
		$config ["total_rows"] = $this->m_admin->record_count_search_staffdprd($column,$query);
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
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['staff_dprd'] = $this->m_admin->data_search_staffdprd($column,$query,$config ["per_page"],$this->input->get('per_page'));
		$this->render('list_telaah/staff_dprd/content');
	}
	
	//View Detail Data Staff DPRD
	public function detail_staffdprd()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		$this->data['entry'] =  $this->m_staff_dprd->get($telaah_id);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('list_telaah/staff_dprd');
		} else {
			$this->data['pengikut'] =  $this->m_pengikut->data($telaah_id);
			$this->data['timeline'] =  $this->m_staff_dprd->getTimeline($telaah_id);
			$this->render('list_telaah/staff_dprd/detail');
		}
	}
	
	//View All Data Staff Camat
	public function index_staffcamat()
	{
		$config = array ();
		$config ["base_url"] = base_url () . "setting_root/admin/index_staffcamat";
		$config ["total_rows"] = $this->m_admin->record_count_staffcamat();
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
		$this->data['staff_camat'] = $this->m_admin->data_staffcamat($config ["per_page"], $page);
		$this->render('list_telaah/staff_camat/content');
	}
	
	//View Data Search Staff Camat
	public function search_staffcamat()
	{
		if($this->input->post('submit')){
			$column = 'pegawai_nama';
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
		$config ["base_url"] = base_url () . "setting_root/admin/search_staffcamat/".$query."/".$column;
		$config ["total_rows"] = $this->m_admin->record_count_search_staffcamat($column,$query);
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
		
		if ($this->uri->segment ( 6 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 6 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 6 )) ? $this->uri->segment ( 6 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['staff_camat'] = $this->m_admin->data_search_staffcamat($column,$query,$config ["per_page"], $page);
		$this->render('list_telaah/staff_camat/content');
	}
	
	//View Data Result Staff Camat
	public function result_staffcamat()
	{
		$column = 'table_telaah.telaah_status';
		$query = $this->input->get('status');
		
		$config = array ();
		$config ["base_url"] = base_url () . "staff_camat/search/".$query."/".$column;
		$config ["total_rows"] = $this->m_staff_camat->record_count_search($column,$query, $this->ion_auth->user()->row()->skpd_id);
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
			$data ['number'] = 0;
		} else {
			$data ['number'] = $this->uri->segment ( 5 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 5 )) ? $this->uri->segment ( 5 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['staff_camat'] = $this->m_staff_camat->data_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
		$this->render('list_telaah/staff_camat/content');
	}
	
	//View Detail Data Staff Camat
	public function detail_staffcamat()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		$this->data['entry'] =  $this->m_staff_camat->get($telaah_id);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('list_telaah/staff_camat');
		} else {
			$this->data['pengikut'] =  $this->m_pengikut->data($telaah_id);
			$this->data['timeline'] =  $this->m_staff_camat->getTimeline($telaah_id);
			$this->render('list_telaah/staff_camat/detail');
		}
	}
	
	//View All Data Staff Lurah
	public function index_stafflurah()
	{
		$config = array ();
		$config ["base_url"] = base_url () . "setting_root/admin/index_stafflurah";
		$config ["total_rows"] = $this->m_admin->record_count_stafflurah();
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
		$this->data['staff_lurah'] = $this->m_admin->data_stafflurah($config ["per_page"], $page);
		$this->render('list_telaah/staff_lurah/content');
	}
	
	//View Data Search Staff Lurah
	public function search_stafflurah()
	{
		if($this->input->post('submit')){
			$column = 'pegawai_nama';
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
		$config ["base_url"] = base_url () . "setting_root/admin/search_stafflurah/".$query."/".$column;
		$config ["total_rows"] = $this->m_admin->record_count_search_stafflurah($column,$query);
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
		
		if ($this->uri->segment ( 6 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 6 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 6 )) ? $this->uri->segment ( 6 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['staff_lurah'] = $this->m_admin->data_search_stafflurah($column,$query,$config ["per_page"], $page);
		$this->render('list_telaah/staff_lurah/content');
	}
	
	//View Data Result Staff Lurah
	public function result_stafflurah()
	{
		$column = 'table_telaah.telaah_status';
		$query = $this->input->get('status');
		
		$config = array ();
		$config ["base_url"] = base_url () . "setting_root/admin/search/".$query."/".$column;
		$config ["total_rows"] = $this->m_admin->record_count_search_stafflurah($column,$query);
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
		
		if ($this->uri->segment ( 6 ) == "") {
			$data ['number'] = 0;
		} else {
			$data ['number'] = $this->uri->segment ( 6 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 6 )) ? $this->uri->segment ( 6 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['staff_lurah'] = $this->m_admin->data_search_stafflurah($column,$query,$config ["per_page"], $page);
		$this->render('list_telaah/staff_lurah/content');
	}
	
	//View Detail Data Staff Lurah
	public function detail_stafflurah()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		$this->data['entry'] =  $this->m_staff_lurah->get($telaah_id);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('list_telaah/staff_lurah');
		} else {
			$this->data['pengikut'] =  $this->m_pengikut->data($telaah_id);
			$this->data['timeline'] =  $this->m_staff_lurah->getTimeline($telaah_id);
			$this->render('list_telaah/staff_lurah/detail');
		}
	}
	
	//View All Data Puskesmas
	public function index_kapus()
	{
		$config = array ();
		$config ["base_url"] = base_url () . "setting_root/admin/index_kapus";
		$config ["total_rows"] = $this->m_admin->record_count_kapus();
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
		}else {
			$this->data ['number'] = $this->uri->segment ( 4 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 4 )) ? $this->uri->segment ( 4 ) : 0;
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['kapus'] = $this->m_admin->data_kapus($config ["per_page"], $page);
		$this->render('list_telaah/kapus/content');
	}
	
	//View Data Search Puskesmas
	public function search_kapus()
	{
		if($this->input->post('submit')){
			$column = 'pegawai_nama';
			$query = $this->input->post('data');
			$option = array(
				'user_column'=>$column,
				'user_data'=>$query
				);
			$this->session->set_userdata($option);
		}else {
			$query = $this->uri->segment ( 4 );
			$column = $this->uri->segment ( 5 );
		}
		
		$config = array ();
		$config ["base_url"] = base_url () . "setting_root/admin/search_kapus/".$query."/".$column;
		$config ["total_rows"] = $this->m_admin->record_count_search_kapus($column,$query);
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
		
		if ($this->uri->segment ( 6 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 6 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 6 )) ? $this->uri->segment ( 6 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['kapus'] = $this->m_admin->data_search_kapus($column,$query,$config ["per_page"], $page);
		$this->render('list_telaah/kapus/content');
	}

	//View Data Result Puskesmas
	public function result_kapus()
	{
		$column = 'table_telaah.telaah_status';
		$query = $this->input->get('status');
		
		$config = array ();
		$config ["base_url"] = base_url () . "setting_root/admin/search_kapus/".$query."/".$column;
		$config ["total_rows"] = $this->m_admin->record_count_search_kapus($column,$query);
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
		
		if ($this->uri->segment ( 6 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 6 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 6 )) ? $this->uri->segment ( 6 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['kapus'] = $this->m_admin->data_search_kapus($column,$query,$config ["per_page"], $page);
		$this->render('list_telaah/kapus/content');
	}
	
	//View Detail Data Puskesmas
	public function detail_kapus()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		$this->data['entry'] =  $this->m_kapus->get($telaah_id);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('list_telaah/kapus');
		} else {
			$this->data['pengikut'] =  $this->m_pengikut->data($telaah_id);
			$this->data['timeline'] =  $this->m_kapus->getTimeline2($telaah_id);
			$this->render('list_telaah/kapus/detail');
		}
	}
	
	//QR Code 
	public function qr_generator()
	{
		QRcode::png($_GET['code']);
	}

	//Delete Telaah
	public function delete_telaah()
	{
		
		$arrayTable=array('table_telaah','table_timeline1','table_timeline2','table_timeline3','table_timeline4', 'table_timeline5','table_timeline6','table_timeline7','table_timeline8','table_timeline9','table_timeline10','table_timeline11','table_pengikut','table_pengeluaran_rill', 'table_kuitansi_panjar','table_laporanperjalanan','table_lokasi_tujuan','table_rincian_biaya','table_tanggal_perjalanan');
		
		for($i=0;$i<count($arrayTable);$i++){
			$this->m_admin->delete_telaah($this->input->post('telaah_id'), $arrayTable[$i]);
		}

		redirect('setting_root/admin/'.$this->input->post('url'));

	}

}
