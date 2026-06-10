<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Anggotadprd extends public_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('setting_admin/m_anggotadprd');
		$this->load->model('setting/m_log');
		if(!($this->ion_auth->user()->row()->id)){
			redirect('login');
		}
	}
	//View All Data
	public function index()
	{
		$config = array ();
		$config ["base_url"] = base_url () . "anggotadprd/index";
		$config ["total_rows"] = $this->m_anggotadprd->record_count($this->ion_auth->user()->row()->skpd_id);
		$config ["per_page"] = 25;
		$config ["uri_segment"] = 3;
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
		
		if ($this->uri->segment ( 3 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 3 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 3 )) ? $this->uri->segment ( 3 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['anggotadprd'] = $this->m_anggotadprd->data($config ["per_page"], $page);
		$this->render('anggotadprd/content');
	}
	
	//View Data Search
	public function search()
	{
		if($this->input->post('submit')){
			$column = $this->input->post('column');
			$query = $this->input->post('data');
			
			$option = array(
				'user_column'=>$column,
				'user_data'=>$query
				);
			$this->session->set_userdata($option);
		}else{
			$query = $this->uri->segment ( 3 );
			$column = $this->uri->segment ( 4 );
		}
		
		$config = array ();
		$config ["base_url"] = base_url () . "anggotadprd/search/".$query."/".$column;
		$config ["total_rows"] = $this->m_anggotadprd->record_count_search($column,$query);
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
		$this->data['anggotadprd'] = $this->m_anggotadprd->data_search($column,$query,$config ["per_page"], $page);
		$this->render('anggotadprd/content');
	}
	//View Create Data
	public function create_view()
	{
		// $this->data['skpd']= $this->m_anggotadprd->skpd();
		// $this->data['golongan']= $this->m_anggotadprd->golongan();
		// $this->data['esselon']= $this->m_anggotadprd->esselon();
		// $this->data['jabatan']= $this->m_anggotadprd->jabatan();
		$this->render('anggotadprd/insert');
	}
	
	//Create Data
	public function create()
	{
		$this->form_validation->set_rules('anggotadprd_name', 'NAMA', 'required');
		
		
		if ($this->form_validation->run() == FALSE)
		{	
			// $this->data['skpd']= $this->m_anggotadprd->skpd();
			// $this->data['golongan']= $this->m_anggotadprd->golongan();
			// $this->data['esselon']= $this->m_anggotadprd->esselon();
			// $this->data['jabatan']= $this->m_anggotadprd->jabatan();
			$this->render('anggotadprd/insert');
		} 
		else 
		{	
			$data['anggotadprd_name'] = $this->input->post('anggotadprd_name');
			$data['anggotadprd_partai'] = $this->input->post('anggotadprd_partai');
			$data['anggotadprd_jabatan'] = $this->input->post('anggotadprd_jabatan');
			
			
			$this->m_anggotadprd->create($data);
			
			$log['kode_log_action'] = "53";
			$log['action'] = "INSERT";
			$log['kode_log_action_table'] = "8";
			$log['action_table'] = "TABLE PEGAWAI";
			$this->m_log->create($log);
			
			$this->session->set_flashdata('notif','Data Anggota DPRD Di Simpan !');
			redirect('anggotadprd');
			
		}
	}
	
	//View Update Data
	public function update_view()
	{
		$anggotadprd_id = $this->encrypt->decode(base64_decode($this->input->get('anggotadprd_id')), $this->session->userdata('encrypt_key'));
		
		$this->data['entry'] =  $this->m_anggotadprd->get($anggotadprd_id);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('anggotadprd');
		} else {
			// $this->data['skpd']= $this->m_anggotadprd->skpd();
			// $this->data['golongan']= $this->m_anggotadprd->golongan();
			// $this->data['esselon']= $this->m_anggotadprd->esselon();
			// $this->data['jabatan']= $this->m_anggotadprd->jabatan();
			$this->render('anggotadprd/update');
		}
	}
	//Update Data
	public function update()
	{
		$this->form_validation->set_rules('anggotadprd_name', 'Nama Anggota DPRD', 'required|max_length[255]');
		
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->data['entry'] =  $this->m_anggotadprd->get($this->input->post('anggotadprd_id'));
			if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
				redirect('anggotadprd');
			} else {
				// $this->data['skpd']= $this->m_anggotadprd->skpd();
				// $this->data['golongan']= $this->m_anggotadprd->golongan();
				// $this->data['esselon']= $this->m_anggotadprd->esselon();
				// $this->data['jabatan']= $this->m_anggotadprd->jabatan();
				$this->render('anggotadprd/update');
			}
		} 
		else 
		{	
			$data['anggotadprd_id'] = $this->input->post('anggotadprd_id');
			$data['anggotadprd_name'] = $this->input->post('anggotadprd_name');
			$data['anggotadprd_partai'] = $this->input->post('anggotadprd_partai');
			$data['anggotadprd_jabatan'] = $this->input->post('anggotadprd_jabatan');
			
			
			$this->m_anggotadprd->update($data);		
			
			$log['kode_log_action'] = "54";
			$log['action'] = "UPDATE anggotadprd_id = ".$this->input->post('anggotadprd_id');
			$log['kode_log_action_table'] = "8";
			$log['action_table'] = "TABLE ANGGOTADPRD";
			$this->m_log->create($log);	
			
			$this->session->set_flashdata('notif','Data Anggota DPRD Di Ubah !');
			redirect('anggotadprd');
		}
	}
	
	//Delete Data
	public function delete() 
	{
		$anggotadprd_id = $this->encrypt->decode(base64_decode($this->input->get('anggotadprd_id')), $this->session->userdata('encrypt_key'));
		
		$this->m_anggotadprd->delete($anggotadprd_id);
		
		$log['kode_log_action'] = "56";
		$log['action'] = "DELETE anggotadprd_id = ".$anggotadprd_id;
		$log['kode_log_action_table'] = "8";
		$log['action_table'] = "TABLE PEGAWAI";
		$this->m_log->create($log);	
		
		$this->session->set_flashdata('notif','Data anggotadprd Di Hapus !');
		redirect('anggotadprd');	
	}
}