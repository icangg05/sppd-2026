<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Rekening extends public_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('setting_admin/m_rekening');
		$this->load->model('setting/m_log');
		if(!($this->ion_auth->user()->row()->id)){
			redirect('login');
		}
	}
	//View All Data
	public function index()
	{
		$config = array ();
		$config ["base_url"] = base_url () . "rekening/index";
		$config ["total_rows"] = $this->m_rekening->record_count($this->ion_auth->user()->row()->skpd_id);
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
		$this->data['rekening'] = $this->m_rekening->data($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
		$this->render('rekening/content');
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
		$config ["base_url"] = base_url () . "rekening/search/".$query."/".$column;
		$config ["total_rows"] = $this->m_rekening->record_count_search($column,$query,$this->ion_auth->user()->row()->skpd_id);
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
		$this->data['rekening'] = $this->m_rekening->data_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
		$this->render('rekening/content');
	}
	//View Create Data
	public function create_view()
	{
		$this->data['skpd']= $this->m_rekening->skpd();
		$this->render('rekening/insert');
	}
	//Create Data
	public function create()
	{
		$this->form_validation->set_rules('kode_rekening', 'Kode Rekening', 'required|max_length[255]');
		$this->form_validation->set_rules('nama_rekening', 'Nama Rekening', 'required|max_length[255]');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->data['skpd']= $this->m_rekening->skpd();
			$this->render('rekening/insert');
		} 
		else 
		{	
			$data['kode_rekening'] = $this->input->post('kode_rekening');
			$data['nama_rekening'] = $this->input->post('nama_rekening');
			$data['skpd_id'] = $this->ion_auth->user()->row()->skpd_id;
			
			$this->m_rekening->create($data);
			
			$log['kode_log_action'] = "53";
			$log['action'] = "INSERT";
			$log['kode_log_action_table'] = "11";
			$log['action_table'] = "TABLE REKENING";
			$this->m_log->create($log);
			
			$this->session->set_flashdata('notif','Data Rekening Di Simpan !');
			redirect('rekening');
			
		}
	}
		//View Update Data
	public function update_view()
	{
		$id_rekening = $this->encrypt->decode(base64_decode($this->input->get('id_rekening')), $this->session->userdata('encrypt_key'));
		
		$this->data['entry'] =  $this->m_rekening->get($id_rekening);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('rekening');
		} else {
			$this->data['skpd']= $this->m_rekening->skpd();
			$this->render('rekening/update');
		}
	}
	//Update Data
	public function update()
	{
		$this->form_validation->set_rules('kode_rekening', 'Nama Program', 'required|max_length[255]');
		$this->form_validation->set_rules('nama_rekening', 'Nama Kegiatan', 'required|max_length[255]');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->data['entry'] =  $this->m_rekening->get($this->input->post('id_rekening'));
			if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
				redirect('rekening');
			} else {
				$this->data['skpd']= $this->m_rekening->skpd();
				$this->render('rekening/update');
			}
		} 
		else 
		{	
			$data['id_rekening'] = $this->input->post('id_rekening');
			$data['kode_rekening'] = $this->input->post('kode_rekening');
			$data['nama_rekening'] = $this->input->post('nama_rekening');
			$data['skpd_id'] = $this->ion_auth->user()->row()->skpd_id;
			
			$this->m_rekening->update($data);		
			
			$log['kode_log_action'] = "54";
			$log['action'] = "UPDATE id_rekening = ".$this->input->post('id_rekening');
			$log['kode_log_action_table'] = "11";
			$log['action_table'] = "TABLE REKENING";
			$this->m_log->create($log);	
			
			$this->session->set_flashdata('notif','Data rekening Di Ubah !');
			redirect('rekening');
		}
	}
	//Delete Data
	public function delete() 
	{
		$id_rekening = $this->encrypt->decode(base64_decode($this->input->get('id_rekening')), $this->session->userdata('encrypt_key'));
		
		$this->m_rekening->delete($id_rekening);
		
		$log['kode_log_action'] = "56";
		$log['action'] = "DELETE id_rekening = ".$id_rekening;
		$log['kode_log_action_table'] = "11";
		$log['action_table'] = "TABLE REKENING";
		$this->m_log->create($log);	
		
		$this->session->set_flashdata('notif','Data Rekening Di Hapus !');
		redirect('rekening');	
	}
}