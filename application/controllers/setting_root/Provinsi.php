<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Provinsi extends public_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('setting_root/m_provinsi');
		$this->load->model('setting/m_log');
		
		if((!$this->ion_auth->get_users_groups()->row()->id == 9) || (!$this->ion_auth->get_users_groups()->row()->id == 100))
		{
			redirect('login');
		}
	}
	
	//View All Data
	public function index()
	{
		$config = array ();
		$config ["base_url"] = base_url () . "setting_root/provinsi/index";
		$config ["total_rows"] = $this->m_provinsi->record_count();
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
		$this->data['provinsi'] = $this->m_provinsi->data($config ["per_page"], $page);
		$this->render('setting_root/provinsi/content');
	}
	
	//View Data Search
	public function search()
	{
		if($this->input->post('submit')){
				$column = "provinsi";
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
		$config ["base_url"] = base_url () . "setting_root/provinsi/search/".$query."/".$column;
		$config ["total_rows"] = $this->m_provinsi->record_count_search($column,$query);
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
		$this->data['provinsi'] = $this->m_provinsi->data_search($column,$query,$config ["per_page"], $page);
 		$this->render('setting_root/provinsi/content');
	}
	
	//View Create Data
	public function create_view()
	{
		$this->render('setting_root/provinsi/insert');
	}
	
	//Create Data
	public function create()
	{
		$this->form_validation->set_rules('provinsi', 'Nama Provinsi', 'required|max_length[255]');
		$this->form_validation->set_rules('latitude', 'Latitude', 'required|max_length[255]');
		$this->form_validation->set_rules('longitude', 'Longtitude', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->render('setting_root/provinsi/insert');
		} 
		else 
		{	
			$data['provinsi'] = $this->input->post('provinsi');
			$data['latitude'] = $this->input->post('latitude');
			$data['longitude'] = $this->input->post('longitude');
			
			$this->m_provinsi->create($data);
			
			$log['kode_log_action'] = "53";
			$log['action'] = "INSERT";
			$log['kode_log_action_table'] = "10";
			$log['action_table'] = "TABLE PROVINSI";
			$this->m_log->create($log);
			
			$this->session->set_flashdata('notif','Data provinsi Di Simpan !');
			redirect('setting_root/setting_root/provinsi');
			
		}
	}
	
	//View Update Data
	public function update_view()
	{
		$provinsi_id = $this->encrypt->decode(base64_decode($this->input->get('provinsi_id')), $this->session->userdata('encrypt_key'));
		
		$this->data['entry'] =  $this->m_provinsi->get($provinsi_id);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('setting_root/provinsi');
		} else {
			$this->render('setting_root/provinsi/update');
		}
	}
	
	//Update Data
	public function update()
	{
		$this->form_validation->set_rules('provinsi', 'Nama Provinsi', 'required|max_length[255]');
		$this->form_validation->set_rules('latitude', 'Latitude', 'required|max_length[255]');
		$this->form_validation->set_rules('longitude', 'Longtitude', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->data['entry'] =  $this->m_provinsi->get($this->input->post('provinsi_id'));
			if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
				redirect('setting_root/provinsi');
			} else {
				$this->render('setting_root/provinsi/update');
			}
		} 
		else 
		{	
			$data['provinsi_id'] = $this->input->post('provinsi_id');
			$data['provinsi'] = $this->input->post('provinsi');
			$data['latitude'] = $this->input->post('latitude');
			$data['longitude'] = $this->input->post('longitude');
			
			$this->m_provinsi->update($data);		
			
			$log['kode_log_action'] = "54";
			$log['action'] = "UPDATE provinsi_id = ".$this->input->post('provinsi_id');
			$log['kode_log_action_table'] = "10";
			$log['action_table'] = "TABLE PROVINSI";
			$this->m_log->create($log);	
			
			$this->session->set_flashdata('notif','Data Provinsi Di Ubah !');
			redirect('setting_root/provinsi');
		}
	}
	
	//Delete Data
	public function delete() 
	{
		$provinsi_id = $this->encrypt->decode(base64_decode($this->input->get('provinsi_id')), $this->session->userdata('encrypt_key'));
		
		$this->m_provinsi->delete($provinsi_id);
		
		$log['kode_log_action'] = "56";
		$log['action'] = "DELETE provinsi_id = ".$provinsi_id;
		$log['kode_log_action_table'] = "10";
		$log['action_table'] = "TABLE PROVINSI";
		$this->m_log->create($log);	
		
		$this->session->set_flashdata('notif','Data Provinsi Di Hapus !');
		redirect('setting_root/provinsi');	
	}
}