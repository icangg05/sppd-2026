<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Walikota extends public_Controller {
	function __construct()
	{
		parent::__construct();
		error_reporting(0);
		$this->load->model('setting_admin/m_walikota');
		$this->load->model('setting/m_log');
	}

	//View All Data
	public function index()
	{
		$config = array ();
		$config ["base_url"] = base_url () . "setting_admin/walikota/index";
		$config ["total_rows"] = $this->m_walikota->data('','');
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
		$this->data['walikota'] = $this->m_walikota->data($config ["per_page"], $page);
		$this->render('setting_admin/walikota/content');
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
		$config ["total_rows"] = $this->m_walikota->data_search($column,$query,'','');
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
		$this->data['walikota'] = $this->m_walikota->data_search($column,$query,$config ["per_page"], $page);
		$this->render('setting_admin/walikota/content');
	}
	
	//View Create Data
	public function create_view()
	{
		$this->render('setting_admin/walikota/insert');
	}
	
	//Create Data
	public function create()
	{
		$this->form_validation->set_rules('pegawai_nik', 'NIK', 'required|integer');
		$this->form_validation->set_rules('pegawai_nama', 'Nama Walikota/Wakil Walikota', 'required|max_length[255]');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->render('setting_admin/walikota/insert');
		} 
		else 
		{	
			$data['pegawai_nik'] = $this->input->post('pegawai_nik');
			$data['pegawai_nama'] = $this->input->post('pegawai_nama');
			$data['pegawai_jabatan'] = $this->input->post('pegawai_jabatan');
			$data['pegawai_namajabatan'] = $this->input->post('pegawai_namajabatan');
			$data['tanggal'] = date("Y-m-d");
			$data['waktu'] = date("h:i:s");
			$data['bagian_id'] = 11;
			$data['skpd_id'] = 3;
			
			$this->m_walikota->create($data);
			
			$log['kode_log_action'] = "53";
			$log['action'] = "INSERT";
			$log['kode_log_action_table'] = "8";
			$log['action_table'] = "TABLE WALIKOTA";
			$this->m_log->create($log);
			
			$this->session->set_flashdata('notif','Data Walikota Di Simpan !');
			redirect('setting_admin/walikota');
		}
	}
	
	//View Update Data
	public function update_view()
	{
		$walikota_id = $this->encrypt->decode(base64_decode($this->input->get('walikota_id')), $this->session->userdata('encrypt_key'));
		
		$this->data['entry'] =  $this->m_walikota->get($walikota_id);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('setting_admin/walikota');
		} else {
			$this->render('setting_admin/walikota/update');
		}
	}
	//Update Data
	public function update()
	{
		$this->form_validation->set_rules('pegawai_nik', 'NIK', 'required|integer');
		$this->form_validation->set_rules('pegawai_nama', 'Nama Walikota/Wakil Walikota', 'required|max_length[255]');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->data['entry'] =  $this->m_walikota->get($this->input->post('pegawai_id'));
			if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
				redirect('setting_admin/walikota');
			} else {
				$this->render('setting_admin/walikota/update');
			}
		} 
		else 
		{	
			
			$data['pegawai_id'] = $this->input->post('pegawai_id');
			$data['pegawai_nik'] = $this->input->post('pegawai_nik');
			$data['pegawai_nama'] = $this->input->post('pegawai_nama');
			$data['pegawai_jabatan'] = $this->input->post('pegawai_jabatan');
			$data['pegawai_namajabatan'] = $this->input->post('pegawai_namajabatan');
			
			$this->m_walikota->update($data);		
			
			$log['kode_log_action'] = "54";
			$log['action'] = "UPDATE walikota_id = ".$this->input->post('pegawai_id');
			$log['kode_log_action_table'] = "8";
			$log['action_table'] = "TABLE WALIKOTA";
			$this->m_log->create($log);	
			
			$this->session->set_flashdata('notif','Data walikota Di Ubah !');
			redirect('setting_admin/walikota/update_view?walikota_id='.$walikota_id);
				
		}
	}
	
	//Delete Data
	public function delete() 
	{
		$walikota_id = $this->encrypt->decode(base64_decode($this->input->get('walikota_id')), $this->session->userdata('encrypt_key'));
		
		$data['pegawai_id'] = $walikota_id;
		$data['status_delete'] = 1;
			
		$this->m_walikota->update($data);
		
		$log['kode_log_action'] = "56";
		$log['action'] = "DELETE walikota_id = ".$walikota_id;
		$log['kode_log_action_table'] = "8";
		$log['action_table'] = "TABLE walikota";
		$this->m_log->create($log);	
		
		$this->session->set_flashdata('notif','Data walikota Di Hapus !');
		redirect('setting_admin/walikota');	
	}
	
}