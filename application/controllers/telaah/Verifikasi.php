<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Verifikasi extends public_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('telaah/m_telaah');
		$this->load->model('setting_root/m_admin');
		$this->load->model('telaah/m_verifikasi');
		$this->load->model('telaah/m_pengikut');
		$this->load->model('telaah/m_lokasi_tujuan');
		$this->load->model('setting/m_log');
		if(!($this->ion_auth->user()->row()->id)){
			redirect('login');
		}
	}
	//View All Data
	public function index()
	{
		## Pagination
		$base_url = base_url () . "telaah/verifikasi/index/".$this->uri->segment(4);
		$total_rows = $this->m_verifikasi->data('', '');
		
		$per_page = 25;
		$uri_segment = 5;
		$page = ($this->uri->segment ( $uri_segment )) ? $this->uri->segment ( $uri_segment ) : 0;
		$paging = $this->paging->paginate_function($base_url,$total_rows,$per_page,$uri_segment);
		
		$this->data['number'] = $paging['number'];
		$this->data['links'] = $paging['links'];
		
		## Menampilkan Semua Data
		$this->data['verifikasi'] = $this->m_verifikasi->data($per_page, $page);
		
		$this->render('telaah/verifikasi/content');
	}
	//View Data Search
	public function search()
	{
		if($this->input->post('submit')){
			if($this->uri->segment ( 4 )=="sekwan"){
				$column = 'anggotadprd_name';
			} else {
				$column = 'pegawai_nama';
			}
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
		$base_url = base_url () . "telaah/verifikasi/search/".$this->uri->segment(4)."/".$query."/".$column;
		$total_rows = $this->m_verifikasi->data_search($column,$query,'','');
		$per_page = 25;
		$uri_segment = 7;
		$page = ($this->uri->segment ( $uri_segment )) ? $this->uri->segment ( $uri_segment ) : 0;
		$paging = $this->paging->paginate_function($base_url,$total_rows,$per_page,$uri_segment);
		
		$this->data['number'] = $paging['number'];
		$this->data['links'] = $paging['links'];
		
		## Menampilkan Semua Data
		$this->data['verifikasi'] = $this->m_verifikasi->data_search($column,$query,$per_page,$page);
			
		$this->render('telaah/verifikasi/content');
	}
	
	public function verifikasi(){
		
		$data['telaah_id'] = $this->uri->segment(4);
		if($this->uri->segment(5)==1){
			$data['status_laporan'] = 0;
		} else {
			$data['status_laporan'] = 1;
		}
		$this->m_verifikasi->verifikasi($data);
		
		echo "<input type='hidden' class='form-control' name='status_laporan' id='status_laporan".$data['telaah_id']."' value='".$data['status_laporan']."''>";
		
	}
}