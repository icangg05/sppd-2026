<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Tanda_tangan extends public_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('setting_admin/m_pegawai');
		$this->load->model('setting_admin/m_tanda_tangan');
		$this->load->model('telaah/m_relasi_kelurahan');
		$this->load->model('setting_admin/m_jenis_skpd');
		$this->load->model('setting/m_log');
	}
	//View All Data
	public function index()
	{
		$config = array ();
		$config ["base_url"] = base_url () . "setting_admin/tanda_tangan/index";
		$config ["total_rows"] = $this->m_tanda_tangan->record_count($this->ion_auth->user()->row()->skpd_id);
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
		$this->data['tanda_tangan'] = $this->m_tanda_tangan->data($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
		$this->render('setting_admin/tanda_tangan/content');
	}
	
	//View Data Search
	public function search()
	{
		if($this->input->post('submit')){
			$column = "skpd_nama";
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
		$config ["base_url"] = base_url () . "setting_admin/tanda_tangan/search/".$query."/".$column;
		$config ["total_rows"] = $this->m_tanda_tangan->record_count_search($column,$query, $this->ion_auth->user()->row()->skpd_id);
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
		$this->data['tanda_tangan'] = $this->m_tanda_tangan->data_search($column,$query,$this->ion_auth->user()->row()->skpd_id, $config ["per_page"], $page);
		$this->render('setting_admin/tanda_tangan/content');
	}
	
	//View Create Data
	public function create_view()
	{
		$this->data['jabatan']= $this->m_pegawai->jabatan();
		$this->render('setting_admin/tanda_tangan/insert');
	}
	
	//Create Data
	public function create()
	{
		$this->form_validation->set_rules('jabatan_id', 'Jabatan', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');
		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->data['jabatan']= $this->m_pegawai->jabatan();
			$this->render('setting_admin/tanda_tangan/insert');
		} 
		else 
		{	
			if($_FILES['userfile']['name']==''){
				
				$data['skpd_id'] = $this->ion_auth->user()->row()->skpd_id;
				$data['jabatan_id'] = $this->input->post('jabatan_id');
				$data['status'] = $this->input->post('status');
				$this->m_tanda_tangan->create($data);
				
				
					/*$log['kode_log_action'] = "53";
					$log['action'] = "Insert";
					$log['kode_log_action_table'] = "14";
					$log['action_table'] = "Table tanda_tangan";
					$this->m_log->create($log);*/
					
					$this->session->set_flashdata('notif','Data Tanda Tangan Di Simpan !');
					redirect('setting_admin/tanda_tangan');
					
				} else {	
					
					$filename = $this->input->post('userfile');
					$config['upload_path'] = './upload/tanda_tangan/';
					$config['allowed_types'] = "gif|jpg|jpeg|png";
					$config['overwrite']="true";
					$config['max_size']="20000000";
					$config['max_width']="10000";
					$config['max_height']="10000";
					$config['file_name'] = ''.$filename;
					$this->upload->initialize($config);
					if(!$this->upload->do_upload()){
						echo  $this->upload->display_errors();
					}else {
						
						$dat = $this->upload->data();
						$data['skpd_id'] = $this->ion_auth->user()->row()->skpd_id;
						$data['jabatan_id'] = $this->input->post('jabatan_id');
						$data['status'] = $this->input->post('status');
						$data['tanda_tangan'] = $dat['file_name'];
						$this->m_tanda_tangan->create($data);
						
						
					/*$log['kode_log_action'] = "53";
					$log['action'] = "INSERT";
					$log['kode_log_action_table'] = "14";
					$log['action_table'] = "TABLE tanda_tangan";
					$this->m_log->create($log);*/
					
					$this->session->set_flashdata('notif','Data Tanda Tangan Di Simpan !');
					redirect('setting_admin/tanda_tangan');
				}	
			}
		}
	}
	
	//View Update Data
	public function update_view()
	{
		$tanda_tangan_id = $this->encrypt->decode(base64_decode($this->input->get('tanda_tangan_id')), $this->session->userdata('encrypt_key'));
		
		$this->data['jabatan']= $this->m_pegawai->jabatan();
		$this->data['entry'] =  $this->m_tanda_tangan->get($tanda_tangan_id);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('setting_admin/tanda_tangan');
		} else {
			$this->render('setting_admin/tanda_tangan/update');
		}
	}
	//Update Data
	public function update()
	{
		
		$this->form_validation->set_rules('jabatan_id', 'Jabatan', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');
		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');
		
		if ($this->form_validation->run() == FALSE)
		{	
			
			$this->data['jabatan']= $this->m_pegawai->jabatan();
			$this->data['entry'] =  $this->m_tanda_tangan->get($this->input->post('tanda_tangan_id'));
			if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
				redirect('setting_admin/tanda_tangan');
			} else {
				$this->render('setting_admin/tanda_tangan/update');
			}
		} 
		else 
		{	
			if($_FILES['userfile']['name']==''){
				
				$data['tanda_tangan_id'] = $this->input->post('tanda_tangan_id');
				$data['skpd_id'] = $this->ion_auth->user()->row()->skpd_id;
				$data['jabatan_id'] = $this->input->post('jabatan_id');
				$data['status'] = $this->input->post('status');
				$this->m_tanda_tangan->update($data);
				
					/*$log['kode_log_action'] = "54";
					$log['action'] = "Update dengan skpd_id = ".$this->input->post('skpd_id');
					$log['kode_log_action_table'] = "14";
					$log['action_table'] = "Table tanda_tangan";
					$this->m_log->create($log);*/
					
					$this->session->set_flashdata('notif','Data Tanda Tangan Di Ubah !');
					redirect('setting_admin/tanda_tangan');
					
				} else {	
					
					$filename = $this->input->post('userfile');
					$config['upload_path'] = './upload/tanda_tangan/';
					$config['allowed_types'] = "gif|jpg|jpeg|png";
					$config['overwrite']="true";
					$config['max_size']="20000000";
					$config['max_width']="10000";
					$config['max_height']="10000";
					$config['file_name'] = ''.$filename;
					$this->upload->initialize($config);
					if(!$this->upload->do_upload()){
						echo  $this->upload->display_errors();
					}else {
						
						$image = $this->m_tanda_tangan->link_gambar($this->input->post('tanda_tangan_id'));
						if ($image->num_rows() > 0)
						{
							$row = $image->row();			
							$file_gambar = $row->tanda_tangan;
							$path_file = './upload/tanda_tangan/';
							unlink($path_file.$file_gambar);
						}					
						
						$dat = $this->upload->data();
						
						$data['tanda_tangan_id'] = $this->input->post('tanda_tangan_id');
						$data['skpd_id'] = $this->ion_auth->user()->row()->skpd_id;
						$data['jabatan_id'] = $this->input->post('jabatan_id');
						$data['status'] = $this->input->post('status');
						$data['tanda_tangan'] = $dat['file_name'];
						$this->m_tanda_tangan->update($data);
						
					/*$log['kode_log_action'] = "54";
					$log['action'] = "UPDATE skpd_id = ".$this->input->post('skpd_id');
					$log['kode_log_action_table'] = "14";
					$log['action_table'] = "TABLE tanda_tangan";
					$this->m_log->create($log);*/
					
					$this->session->set_flashdata('notif','Data Tanda Tangan Di Ubah !');
					redirect('setting_admin/tanda_tangan');
					
				}	
			}
		}
	}
	
	//Delete Data
	public function delete() 
	{
		$tanda_tangan_id = $this->encrypt->decode(base64_decode($this->input->get('tanda_tangan_id')), $this->session->userdata('encrypt_key'));
		
		$image = $this->m_tanda_tangan->link_gambar($tanda_tangan_id);
		if ($image->num_rows() > 0)
		{
			$row = $image->row();			
			$file_gambar = $row->tanda_tangan;
			$path_file = './upload/tanda_tangan/';
			unlink($path_file.$file_gambar);
		}	
		$this->m_tanda_tangan->delete($tanda_tangan_id);
		$this->m_relasi_kelurahan->delete($tanda_tangan_id);
		
		/*$log['kode_log_action'] = "56";
		$log['action'] = "DELETE skpd_id = ".$skpd_id;
		$log['kode_log_action_table'] = "14";
		$log['action_table'] = "TABLE tanda_tangan";
		$this->m_log->create($log);	*/
		
		$this->session->set_flashdata('notif','Data tanda_tangan Di Hapus !');
		redirect('setting_admin/tanda_tangan');	
	}
}