<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pptk_pengeluaran_rill extends public_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('telaah/m_esselon');
		$this->load->model('telaah/m_telaah');
		$this->load->model('setting_admin/m_pegawai');
		$this->load->model('laporan/m_pptk_pengeluaran_rill');
		$this->load->model('setting_admin/m_jenis_skpd');
		$this->load->model('setting/m_log');
	}
	
	//View Create Data PPTK Perjalanan
    public function create_view()
    {
		$this->data['telaah_id'] = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$get_data_pegawai = $this->m_telaah->get($this->data['telaah_id']);
		if($get_data_pegawai[0]['jenis_skpd']==7 && $get_data_pegawai[0]['telaah_kategori']==1){
			$this->data['pegawai'] = $this->m_pegawai->pptk('','',36);
		} else {
			$this->data['pegawai'] = $this->m_pegawai->pptk('','',$this->ion_auth->user()->row()->skpd_id);
		}
		$this->data['posisi']= $this->uri->segment(5);
        $this->render('laporan/pptk_pengeluaran_rill/insert');
	}
	
	
	//Create Data
	public function create()
	{
		$this->form_validation->set_rules('telaah_ttdpptk', 'Pejabat Pelaksana Teknis kegiatan', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->data['telaah_id'] = $this->input->post('telaah_id');
			$get_data_pegawai = $this->m_telaah->get($this->input->post('telaah_id'));
			if($get_data_pegawai[0]['jenis_skpd']==7 && $get_data_pegawai[0]['telaah_kategori']==1){
				$this->data['pegawai'] = $this->m_pegawai->pptk('','',36);
			} else {
				$this->data['pegawai'] = $this->m_pegawai->pptk('','',$this->ion_auth->user()->row()->skpd_id);
			}
			$this->data['posisi']= $this->input->post('posisi');
			$this->render('laporan/pptk_pengeluaran_rill/insert');
		} 
		else 
		{	
			$telaah_id = base64_encode($this->encrypt->encode($this->input->post('telaah_id'), $this->session->userdata('encrypt_key')));	
			$data['telaah_id'] = $this->input->post('telaah_id');
			$data['telaah_ttdpptk'] = $this->input->post('telaah_ttdpptk');
			
			$this->m_telaah->update($data);
			
			$this->session->set_flashdata('notif','Data Tanggal SPPD Di Simpan !');
			redirect('telaah/laporan/pengeluaran_rill/index/'.$this->input->post('posisi').'?telaah_id='.$telaah_id);
					
		}
	}
	
	//View Update Data
	public function update_view()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$this->data['entry'] =  $this->m_telaah->get($telaah_id);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('telaah/laporan/pptk_pengeluaran_rill/update_view/'.$this->uri->segment(5).'?telaah_id='.$this->input->get('telaah_id'));
		} else {
			$get_data_pegawai = $this->m_telaah->get($telaah_id );
			if($get_data_pegawai[0]['jenis_skpd']==7 && $get_data_pegawai[0]['telaah_kategori']==1){
				$this->data['pegawai'] = $this->m_pegawai->pptk('','',36);
			} else {
				$this->data['pegawai'] = $this->m_pegawai->pptk('','',$this->ion_auth->user()->row()->skpd_id);
			}
			$this->data['posisi']= $this->uri->segment(5);
			$this->render('laporan/pptk_pengeluaran_rill/update');
		}
	}
	
	//Update Data
	public function update()
	{
		$this->form_validation->set_rules('telaah_ttdpptk', 'Pejabat Pelaksana Teknis kegiatan', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$telaah_id = base64_encode($this->encrypt->encode($this->input->post('telaah_id'), $this->session->userdata('encrypt_key')));	
			$this->data['entry'] =  $this->m_telaah->get($this->input->post('telaah_id'));
			if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
				redirect('telaah/laporan/pptk_pengeluaran_rill/update_view/'.$this->uri->segment(5).'?telaah_id='.$telaah_id);
			} else {
				$get_data_pegawai = $this->m_telaah->get($this->input->post('telaah_id'));
				if($get_data_pegawai[0]['jenis_skpd']==7 && $get_data_pegawai[0]['telaah_kategori']==1){
					$this->data['pegawai'] = $this->m_pegawai->pptk('','',36);
				} else {
					$this->data['pegawai'] = $this->m_pegawai->pptk('','',$this->ion_auth->user()->row()->skpd_id);
				}
				$this->data['posisi']= $this->input->post('posisi');
				$this->render('laporan/pptk_pengeluaran_rill/update');
			}
		} 
		else 
		{	
			$telaah_id = base64_encode($this->encrypt->encode($this->input->post('telaah_id'), $this->session->userdata('encrypt_key')));	
			
			$data['telaah_id'] = $this->input->post('telaah_id');
			$data['telaah_ttdpptk'] = $this->input->post('telaah_ttdpptk');
			
			$this->m_telaah->update($data);
			
			$this->session->set_flashdata('notif','Data PPTK Di Ubah !');
			redirect('telaah/laporan/pengeluaran_rill/index/'.$this->input->post('posisi').'?telaah_id='.$telaah_id);
					
		}
	}
	
	//Delete Data
	public function delete() 
	{
		$pptk_id = $this->encrypt->decode(base64_decode($this->input->get('pptk_id')), $this->session->userdata('encrypt_key'));
		
		$this->m_pptk_pengeluaran_rill->delete($pptk_id);
		/*$log['kode_log_action'] = "56";
		$log['action'] = "DELETE skpd_id = ".$skpd_id;
		$log['kode_log_action_table'] = "14";
		$log['action_table'] = "TABLE pptk";
		$this->m_log->create($log);	*/
		
		$this->session->set_flashdata('notif','Data PPTK Di Hapus !');
		redirect('pptk');	
	}
}