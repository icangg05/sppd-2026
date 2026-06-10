<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Log_tte extends public_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('setting_root/m_log_tte');
		
		if(!($this->ion_auth->user()->row()->id)){
			redirect('login');
		}
	}
	
	//View All Data log_tte
	public function index()
	{
		## Pagination
		$base_url = base_url () . "setting_root/log_tte/index";
		
		if($this->ion_auth->user()->row()->id==1){
			$total_rows = $this->m_log_tte->data('', '', '');
		} else {
			$total_rows = $this->m_log_tte->data('', '', $this->ion_auth->user()->row()->skpd_id);
		}
		
		$per_page = 25;
		$uri_segment = 4;
		$page = ($this->uri->segment ( $uri_segment )) ? $this->uri->segment ( $uri_segment ) : 0;
		$paging = $this->paging->paginate_function($base_url,$total_rows,$per_page,$uri_segment);
		
		$this->data['number'] = $paging['number'];
		$this->data['links'] = $paging['links'];
		
		## Menampilkan Semua Data
		if($this->ion_auth->user()->row()->id==1){
			$this->data['data'] = $this->m_log_tte->data($per_page, $page, '');
		} else {
			$this->data['data'] = $this->m_log_tte->data($per_page, $page, $this->ion_auth->user()->row()->skpd_id);
		}
		
		$this->render('setting_root/log_tte/content');
	}
	
	//View Data Search log_tte
	public function search()
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
			$query = str_replace("%20"," ",$this->uri->segment ( 4 ));
			$column = $this->uri->segment ( 5 );
		}
		
		## Pagination
		$base_url = base_url () . "setting_root/log_tte/search/".$query."/".$column;
		
		if($this->ion_auth->user()->row()->id==1){
			$total_rows = $this->m_log_tte->data_search($column,$query,'','','');
		} else {
			$total_rows = $this->m_log_tte->data_search($column,$query,'','',$this->ion_auth->user()->row()->skpd_id);
		}
		
		$per_page = 25;
		$uri_segment = 6;
		$page = ($this->uri->segment ( $uri_segment )) ? $this->uri->segment ( $uri_segment ) : 0;
		$paging = $this->paging->paginate_function($base_url,$total_rows,$per_page,$uri_segment);
		
		$this->data['number'] = $paging['number'];
		$this->data['links'] = $paging['links'];
		
		## Menampilkan Semua Data
		if($this->ion_auth->user()->row()->id==1){
			$this->data['data'] = $this->m_log_tte->data_search($column,$query,$per_page,$page,'');
		} else {
			$this->data['data'] = $this->m_log_tte->data_search($column,$query,$per_page,$page,$this->ion_auth->user()->row()->skpd_id);
		}
		
		$this->render('setting_root/log_tte/content');
	}
	
	//View All Data log_tte
	public function list_log_tte()
	{
		
		## Pagination
		$base_url = base_url () . "setting_root/log_tte/list_log_tte/".$this->uri->segment(4);
		$total_rows = $this->m_log_tte->data2('', '', $this->uri->segment(4));
		$per_page = 25;
		$uri_segment = 5;
		$page = ($this->uri->segment ( $uri_segment )) ? $this->uri->segment ( $uri_segment ) : 0;
		$paging = $this->paging->paginate_function($base_url,$total_rows,$per_page,$uri_segment);
		
		$this->data['number'] = $paging['number'];
		$this->data['links'] = $paging['links'];
		
		## Menampilkan Semua Data
		$this->data['data'] = $this->m_log_tte->data2($per_page, $page, $this->uri->segment(4));
		
		$this->render('setting_root/log_tte/list_log_tte');
	}
	
	
}