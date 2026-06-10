<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Lokasi_tujuan extends public_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_lokasi_tujuan');
		$this->load->model('setting/m_log');
		if(!($this->ion_auth->user()->row()->id)){
			redirect('login');
		}
	}
	
	//Delete Data
	public function delete() 
	{
		$lokasi_tujuan_id = $this->encrypt->decode(base64_decode($this->input->get('lokasi_tujuan_id')), $this->session->userdata('encrypt_key'));
		
		$this->m_lokasi_tujuan->delete($lokasi_tujuan_id);
		
		$log['kode_action'] = "56";
		$log['action'] = "DELETE lokasi_tujuan_id = ".$lokasi_tujuan_id;
		$log['kode_action_table'] = "7";
		$log['action_table'] = "TABLE LOKASI TUJIAN";
		$this->m_log->create($log);	
		
		$this->session->set_flashdata('notif','Data anggaran Di Hapus !');
		redirect('kabid/list_telaah/update_view?telaah_id='.$this->input->get('telaah_id'));
	}
}