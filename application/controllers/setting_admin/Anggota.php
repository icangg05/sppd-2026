<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Anggota extends public_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('setting_admin/m_anggota');
		$this->load->model('setting/m_log');
		if(!($this->ion_auth->user()->row()->id)){
			redirect('login');
		}
	}
	//View All Data
	public function index()
	{
		$config = array ();
		$config ["base_url"] = base_url () . "setting_admin/anggota/index";
		$config ["total_rows"] = $this->m_anggota->record_count();
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
		$this->data['anggota'] = $this->m_anggota->data($config ["per_page"], $page);
		$this->render('setting_admin/anggota/content');
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
			$query = $this->uri->segment ( 4 );
			$column = $this->uri->segment ( 5 );
		}
		
		$config = array ();
		$config ["base_url"] = base_url () . "setting_admin/anggota/search/".$query."/".$column;
		$config ["total_rows"] = $this->m_anggota->record_count_search($column,$query);
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
		$this->data['anggota'] = $this->m_anggota->data_search($column,$query,$config ["per_page"], $page);
		$this->render('setting_admin/anggota/content');
	}
	//View Create Data
	public function create_view()
	{
		$this->render('setting_admin/anggota/insert');
	}
	
	//Create Data
	public function create()
	{
		$this->form_validation->set_rules('anggotadprd_name', 'NAMA ANGGOTA DPRD', 'required|max_length[255]');
		$this->form_validation->set_rules('anggotadprd_partai', 'PARTAI ANGGOTA DPRD', 'required|max_length[255]');
		$this->form_validation->set_rules('anggotadprd_jabatan', 'JABATAN ANGGOTA DPRD', 'required|max_length[255]');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->render('setting_admin/anggota/insert');
		} 
		else 
		{	
			$data['anggotadprd_name'] = $this->input->post('anggotadprd_name');
			$data['anggotadprd_partai'] = $this->input->post('anggotadprd_partai');
			$data['anggotadprd_jabatan'] = $this->input->post('anggotadprd_jabatan');
			
			$this->m_anggota->create($data);
			
			$log['kode_log_action'] = "53";
			$log['action'] = "INSERT";
			$log['kode_log_action_table'] = "8";
			$log['action_table'] = "TABLE ANGGOTA DPRD";
			$this->m_log->create($log);
			
			$this->session->set_flashdata('notif','Data anggota Di Simpan !');
			redirect('setting_admin/anggota');
			
		}
	}
	
	//View Update Data
	public function update_view()
	{
		$anggotadprd_id = $this->encrypt->decode(base64_decode($this->input->get('anggotadprd_id')), $this->session->userdata('encrypt_key'));
		
		$this->data['entry'] =  $this->m_anggota->get($anggotadprd_id);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('setting_admin/anggota');
		} else {
			$this->render('setting_admin/anggota/update');
		}
	}
	//Update Data
	public function update()
	{
		$this->form_validation->set_rules('anggotadprd_name', 'PARTAI ANGGOTA DPRD', 'required|max_length[255]');
		$this->form_validation->set_rules('anggotadprd_partai', 'PARTAI ANGGOTA DPRD', 'required|max_length[255]');
		$this->form_validation->set_rules('anggotadprd_jabatan', 'JABATAN ANGGOTA DPRD', 'required|max_length[255]');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->data['entry'] =  $this->m_anggota->get($this->input->post('anggotadprd_id'));
			if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
				redirect('setting_admin/anggota');
			} else {
				$this->render('setting_admin/anggota/update');
			}
		} 
		else 
		{	
			$data['anggotadprd_id'] = $this->input->post('anggotadprd_id');
			$data['anggotadprd_name'] = $this->input->post('anggotadprd_name');
			$data['anggotadprd_partai'] = $this->input->post('anggotadprd_partai');
			$data['anggotadprd_jabatan'] = $this->input->post('anggotadprd_jabatan');
			$data['status'] = $this->input->post('status');
			
			$this->m_anggota->update($data);		
			
			$log['kode_log_action'] = "54";
			$log['action'] = "UPDATE anggotadprd_id = ".$this->input->post('anggotadprd_id');
			$log['kode_log_action_table'] = "8";
			$log['action_table'] = "TABLE ANGGOTA DPRD";
			$this->m_log->create($log);	
			
			$this->session->set_flashdata('notif','Data anggota Di Ubah !');
			redirect('setting_admin/anggota');
		}
	}
	
	//Delete Data
	public function delete() 
	{
		$anggotadprd_id = $this->encrypt->decode(base64_decode($this->input->get('anggotadprd_id')), $this->session->userdata('encrypt_key'));
		
		$this->m_anggota->delete($anggotadprd_id);
		
		$log['kode_log_action'] = "56";
		$log['action'] = "DELETE anggotadprd_id = ".$anggotadprd_id;
		$log['kode_log_action_table'] = "8";
		$log['action_table'] = "TABLE ANGGOTA DPRD";
		$this->m_log->create($log);	
		
		$this->session->set_flashdata('notif','Data anggota Di Hapus !');
		redirect('setting_admin/anggota');	
	}
}