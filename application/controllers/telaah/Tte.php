<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class tte extends public_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('telaah/m_tte');
		$this->load->model('setting_admin/m_pegawai');
		$this->load->model('m_beranda');
		
		$this->data['count_tte'] = $this->m_beranda->count_tte($this->ion_auth->get_users_groups()->row()->id, $this->ion_auth->user()->row()->skpd_id, $this->ion_auth->user()->row()->jenis_skpd);
		
		if(!($this->ion_auth->user()->row()->id)){
			redirect('login');
		}
	}
	
	//View All Data
	public function index()
	{
	
		## Pagination
		$base_url = base_url () . "telaah/tte/index/".$this->uri->segment(4);
		$total_rows = $this->m_tte->data('', '',$this->ion_auth->get_users_groups()->row()->id, $this->ion_auth->user()->row()->skpd_id, $this->ion_auth->user()->row()->jenis_skpd);
	
		$per_page = 25;
		$uri_segment = 5;
		$page = ($this->uri->segment ( $uri_segment )) ? $this->uri->segment ( $uri_segment ) : 0;
		$paging = $this->paging->paginate_function($base_url,$total_rows,$per_page,$uri_segment);
		
		$this->data['number'] = $paging['number'];
		$this->data['links'] = $paging['links'];
		$this->data['data'] = $this->m_tte->data($per_page, $page, $this->ion_auth->get_users_groups()->row()->id, $this->ion_auth->user()->row()->skpd_id, $this->ion_auth->user()->row()->jenis_skpd);
		
	
		$this->render('telaah/tte/content');
	}
	
	
	//View Data Pencarian
	public function search()
	{
		if($this->input->post('submit')){
				$column = "table_pegawai.pegawai_nama";
				$query = $this->input->post('data');
				
				$option = array(
					'user_column'=>$column,
					'user_data'=>$query
				);
				$this->session->set_userdata($option);
		}else{
		   $query = str_replace("%20"," ",$this->uri->segment ( 5 ));
		   $column = $this->uri->segment ( 6 );
		}
			
		## Pagination
		$base_url = base_url () . "telaah/tte/search/".$this->uri->segment(4)."/".$query."/".$column;
		$total_rows = $this->m_tte->search($column,$query,'', '',$this->ion_auth->get_users_groups()->row()->id, $this->ion_auth->user()->row()->skpd_id, $this->ion_auth->user()->row()->jenis_skpd);
		$per_page = 25;
		$uri_segment = 7;
		$page = ($this->uri->segment ( $uri_segment )) ? $this->uri->segment ( $uri_segment ) : 0;
		$paging = $this->paging->paginate_function($base_url,$total_rows,$per_page,$uri_segment);
		
		$this->data['number'] = $paging['number'];
		$this->data['links'] = $paging['links'];
		$this->data['data'] = $this->m_tte->search($column,$query,$per_page, $page,$this->ion_auth->get_users_groups()->row()->id, $this->ion_auth->user()->row()->skpd_id, $this->ion_auth->user()->row()->jenis_skpd);
		
		$this->render('telaah/tte/content');
	}
	
}