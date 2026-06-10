<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pengeluaran_rill extends public_Controller {
    function __construct()
    {
        parent::__construct();
		error_reporting(0);
		$this->load->model('laporan/m_pengeluaran_rill');
		$this->load->model('laporan/m_pptk_pengeluaran_rill');
		$this->load->model('telaah/m_telaah');
		$this->load->model('setting_admin/m_pegawai');
		$this->load->model('setting/m_log');
    }
	//View All Data
	public function index()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		switch($this->uri->segment(5)){
			case "esselon" 		: 	
			case "puskesmas" 	: 	
			case "sekda" 		: 	
			case "staff_setda" 	: 	
			case "camat" 		: 	
			case "staff_camat" 	: 	
			case "lurah" 		: 	
			case "staff_lurah" 	: 	
			case "kapus" 		:	
			case "staff_dprd" 	:	
			case "sekwan" 		:	
			case "kadis" 		: 	
									$this->data['pptk'] = $this->m_telaah->get($telaah_id);
									$this->data['tanda_tangan_pptk'] = $this->m_pegawai->get($this->data['pptk'][0]['telaah_ttdpptk']);
									break;
			case "dprd" 		: 	$this->data['pptk'] = $this->m_telaah->get_dprd($telaah_id);
									$this->data['tanda_tangan_pptk'] = $this->m_pegawai->get($this->data['pptk'][0]['telaah_ttdpptk']);
									break;
			case "walikota" 	: 	$this->data['pptk'] = $this->m_telaah->getWalikota($telaah_id);
									$this->data['tanda_tangan_pptk'] = $this->m_pegawai->get($this->data['pptk'][0]['telaah_ttdpptk']);
									break;
		}
		
		$this->data['t'] = $this->m_pengeluaran_rill->getTelaahKategori($telaah_id);
		$this->data['t'] =$this->data['t'][0];
		$x = $this->m_pengeluaran_rill->getTelaahKategori($telaah_id);
		foreach($x as $f) {
			$telaah_kategori = $f->telaah_kategori;
		}
		
		if($telaah_kategori == 8) {
			$this->data['pelaksana'] = $this->m_pengeluaran_rill->pelaksanaWalikota($telaah_id);
			$this->data['rincian_pelaksana'] = $this->m_pengeluaran_rill->get_rincianpelaksana($telaah_id,$this->data['pelaksana'][0]['pegawai_id']);
		} else if($telaah_kategori == 3){
			$this->data['pelaksana'] = $this->m_pengeluaran_rill->pelaksanadprd($telaah_id);	
			$this->data['rincian_pelaksana'] = $this->m_pengeluaran_rill->get_rincian_dprd($telaah_id,$this->data['pelaksana'][0]['anggotadprd_id']);
		}   else {
			
			$this->data['pelaksana'] = $this->m_pengeluaran_rill->pelaksana($telaah_id);
			$this->data['rincian_pelaksana'] = $this->m_pengeluaran_rill->get_rincian($telaah_id,$this->data['pelaksana'][0]['pegawai_id']);
		}
		
		if($telaah_kategori == 3) {
			$this->data['pengikut'] = $this->m_pengeluaran_rill->pengikutdprd($telaah_id);
		}else{
			$this->data['pengikut'] = $this->m_pengeluaran_rill->pengikut($telaah_id);
		}
		$this->data['telaah_kategori'] = $telaah_kategori;
		$this->data['jumlah_pengikut'] = count($this->data['pengikut']);
		$this->render('laporan/pengeluaran_rill/content');
	}
	
	//View Create Data
    public function create_view()
    {
		$this->data['telaah_id'] = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$this->data['pegawai_id']= $this->input->get('pegawai_id');
		$this->data['posisi']= $this->uri->segment(5);
        $this->render('laporan/pengeluaran_rill/insert');
	}
	
	//Create Data
	public function create()
	{
		$this->form_validation->set_rules('uraian', 'Uraian', 'required');
		$this->form_validation->set_rules('tarif', 'Tarif', 'required');
		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->create_view();
		} 
		else 
		{	
			$telaah_id = base64_encode($this->encrypt->encode($this->input->post('telaah_id'), $this->session->userdata('encrypt_key')));	
			$data['telaah_id'] = $this->input->post('telaah_id');
			$data['pegawai_id'] = $this->input->post('pegawai_id');
			$data['uraian'] = $this->input->post('uraian');
			$data['tarif'] = str_replace(".", "", $this->input->post('tarif'));
			
			$this->m_pengeluaran_rill->create($data);
			
			$log['kode_log_action'] = "53";
			$log['action'] = "INSERT";
			$log['kode_log_action_table'] = "12";
			$log['action_table'] = "TABLE PENGELUARAN RILL BIAYA";
			$this->m_log->create($log);
			
			$this->session->set_flashdata('notif','Data Pengeluaran Rill Di Simpan !');
			redirect('telaah/laporan/pengeluaran_rill/index/'.$this->input->post('posisi').'?telaah_id='.$telaah_id);
		
		}
	}
	
	//View Update Data
	public function update_view()
	{
		$pengeluaran_rill_id = $this->encrypt->decode(base64_decode($this->input->get('pengeluaran_rill_id')), $this->session->userdata('encrypt_key'));
		
		$this->data['entry'] =  $this->m_pengeluaran_rill->get($pengeluaran_rill_id);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('pengeluaran_rill');
		} else {
			$this->data['posisi']= $this->uri->segment(5);
			$this->render('laporan/pengeluaran_rill/update');
		}
	}
	//Update Data
	public function update()
	{
		$this->form_validation->set_rules('uraian', 'Uraian', 'required');
		$this->form_validation->set_rules('tarif', 'Tarif', 'required');
		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->data['entry'] =  $this->m_pengeluaran_rill->get($this->input->post('pengeluaran_rill_id'));
			if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
				redirect('pengeluaran_rill');
			} else {
				$this->data['posisi']= $this->input->post('posisi');
				$this->render('laporan/pengeluaran_rill/update');
			}
		} 
		else 
		{	
			$telaah_id = base64_encode($this->encrypt->encode($this->input->post('telaah_id'), $this->session->userdata('encrypt_key')));	
			$data['pengeluaran_rill_id'] = $this->input->post('pengeluaran_rill_id');
			$data['telaah_id'] = $this->input->post('telaah_id');
			$data['pegawai_id'] = $this->input->post('pegawai_id');
			$data['uraian'] = $this->input->post('uraian');
			$data['tarif'] = str_replace(".", "", $this->input->post('tarif'));
			
			$this->m_pengeluaran_rill->update($data);		
			
			//$log['action'] = "UPDATE pengeluaran_rill_id = ".$this->input->post('pengeluaran_rill_id');
			//$this->m_log->create($log);	
			
			$this->session->set_flashdata('notif','Data Pengeluaran Rill Di Ubah !');
			redirect('telaah/laporan/pengeluaran_rill/index/'.$this->input->post('posisi').'?telaah_id='.$telaah_id);
			
		}
	}
	
	//Delete Data
	public function delete() 
	{
		$pengeluaran_rill_id = $this->encrypt->decode(base64_decode($this->input->get('pengeluaran_rill_id')), $this->session->userdata('encrypt_key'));
		$telaah_id = base64_encode($this->encrypt->encode($this->input->get('telaah_id'), $this->session->userdata('encrypt_key')));	
		
        $this->m_pengeluaran_rill->delete($pengeluaran_rill_id);
		
		$log['kode_log_action'] = "";
		$log['action'] = "HAPUS rincian_id = ".$pengeluaran_rill_id;
		$log['kode_log_action_table'] = "12";
		$log['action_table'] = "TABLE pengeluaran_rill BIAYA";
		$this->m_log->create($log);	
					
		$this->session->set_flashdata('notif','Data Pengeluaran Rill Di Hapus !');
		redirect('telaah/laporan/pengeluaran_rill/index/'.$this->uri->segment(5).'?telaah_id='.$telaah_id);
    }
}