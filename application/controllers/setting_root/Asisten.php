<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Asisten extends public_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('setting_root/m_asisten');
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
		$config ["base_url"] = base_url () . "setting_root/asisten/index";
		$config ["total_rows"] = $this->m_asisten->record_count();
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
		$this->data['asisten'] = $this->m_asisten->data($config ["per_page"], $page);
		$this->render('setting_root/asisten/content');
	}
	
	//View Data Search
	public function search()
	{
		if($this->input->post('submit')){
				$column = "nama_asisten";
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
		$config ["base_url"] = base_url () . "setting_root/asisten/search/".$query."/".$column;
		$config ["total_rows"] = $this->m_asisten->record_count_search($column,$query);
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
		$this->data['asisten'] = $this->m_asisten->data_search($column,$query,$config ["per_page"], $page);
 		$this->render('setting_root/asisten/content');
	}
	
	//View Create Data
	public function create_view()
	{
		$this->render('setting_root/asisten/insert');
	}
	
	//Create Data
	public function create()
	{
		$this->form_validation->set_rules('nama_asisten', 'Nama Kabupaten/Kota', 'required|max_length[255]');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->render('setting_root/asisten/insert');
		} 
		else 
		{	
			$data['nama_asisten'] = $this->input->post('nama_asisten');
			
			$this->m_asisten->create($data);
			
			$log['kode_log_action'] = "53";
			$log['action'] = "INSERT";
			$log['kode_log_action_table'] = "5";
			$log['action_table'] = "TABLE KABUPATEN KOTA";
			$this->m_log->create($log);
			
			$this->session->set_flashdata('notif','Data Asisten Di Simpan !');
			redirect('setting_root/asisten');
			
		}
	}
	
	//View Update Data
	public function update_view()
	{
		$asisten_id = $this->encrypt->decode(base64_decode($this->input->get('asisten_id')), $this->session->userdata('encrypt_key'));
		
		$this->data['entry'] =  $this->m_asisten->get($asisten_id);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('setting_root/asisten');
		} else {
			$this->render('setting_root/asisten/update');
		}
	}
	
	//Update Data
	public function update()
	{
		$this->form_validation->set_rules('nama_asisten', 'Nama Kabupaten/Kota', 'required|max_length[255]');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->data['entry'] =  $this->m_asisten->get($this->input->post('asisten_id'));
			if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
				redirect('setting_root/asisten');
			} else {
				$this->render('setting_root/asisten/update');
			}
		} 
		else 
		{	
			$data['asisten_id'] = $this->input->post('asisten_id');
			$data['nama_asisten'] = $this->input->post('nama_asisten');
			
			$this->m_asisten->update($data);		
			
			$log['kode_log_action'] = "54";
			$log['action'] = "UPDATE asisten_id = ".$this->input->post('asisten_id');
			$log['kode_log_action_table'] = "5";
			$log['action_table'] = "TABLE KABUPATEN KOTA";
			$this->m_log->create($log);	
			
			$this->session->set_flashdata('notif','Data Asisten Di Ubah !');
			redirect('setting_root/asisten');
		}
	}
	
	//Delete Data
	public function delete() 
	{
		$asisten_id = $this->encrypt->decode(base64_decode($this->input->get('asisten_id')), $this->session->userdata('encrypt_key'));
		
		$this->m_asisten->delete($asisten_id);
		
		$log['kode_log_action'] = "56";
		$log['action'] = "DELETE asisten_id = ".$asisten_id;
		$log['kode_log_action_table'] = "10";
		$log['action_table'] = "TABLE KABUPATEN KOTA";
		$this->m_log->create($log);	
		
		$this->session->set_flashdata('notif','Data Asisten Di Hapus !');
		redirect('setting_root/asisten');	
	}
}