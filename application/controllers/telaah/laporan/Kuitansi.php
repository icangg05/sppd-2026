<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kuitansi extends public_Controller {

    function __construct()
    {
        parent::__construct();
		error_reporting(0);
		$this->load->model('laporan/m_kuitansi');
		$this->load->model('laporan/m_spd');
		$this->load->model('laporan/m_pengeluaran_rill');
		$this->load->model('laporan/m_rincian');
		$this->load->model('laporan/m_laporan');
		$this->load->model('telaah/m_telaah');
		$this->load->model('setting_admin/m_pegawai');
		$this->load->model('setting/m_log');

    }

	//View All Data
	public function index()
	{
		
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));

		$this->data['bendahara'] = $this->m_telaah->get($telaah_id);
		$this->data['tanda_tangan_bendahara'] = $this->m_pegawai->get($this->data['bendahara'][0]['telaah_ttdbendahara']);
									
		$this->data['t'] = $this->m_kuitansi->getTelaahKategori($telaah_id);
		$this->data['pengeluaran_rill'] = $this->m_pengeluaran_rill->get2($telaah_id);
		$this->data['rincian'] = $this->m_rincian->get2($telaah_id);
		$this->data['t'] =$this->data['t'][0];
		$x = $this->m_kuitansi->getTelaahKategori($telaah_id);

		foreach($x as $f) {
			$telaah_kategori = $f->telaah_kategori;
		}
		
		if($telaah_kategori == 8) {
			$this->data['pelaksana'] = $this->m_kuitansi->pelaksanaWalikota($telaah_id);
			$this->data['rincian_pelaksana'] = $this->m_kuitansi->get_rincianpelaksana($telaah_id,$this->data['pelaksana'][0]['pegawai_id']);
		}  else if($telaah_kategori == 3){
			$this->data['pelaksana'] = $this->m_kuitansi->pelaksanadprd($telaah_id);	
			$this->data['rincian_pelaksana'] = $this->m_kuitansi->get_rincian_dprd($telaah_id,$this->data['pelaksana'][0]['anggotadprd_id']);
		}  else {
			
			$this->data['pelaksana'] = $this->m_kuitansi->pelaksana($telaah_id);
			$this->data['rincian_pelaksana'] = $this->m_kuitansi->get_rincian($telaah_id,$this->data['pelaksana'][0]['pegawai_id']);
		}
		
		//Cek data Bendahara Pengeluaran
		if($telaah_kategori != 11 && $this->ion_auth->user()->row()->jenis_skpd == 7){
			$this->data['bendahara_pengeluaran'] = $this->m_laporan->get_bendahara(36);
		}else{
			$this->data['bendahara_pengeluaran'] = $this->m_laporan->get_bendahara($this->ion_auth->user()->row()->skpd_id);
		}
		//$this->data['bendahara_pengeluaran'] = $this->m_laporan->get_bendahara($this->ion_auth->user()->row()->skpd_id);
		
		if($telaah_kategori == 3) {
			$this->data['pengikut'] = $this->m_kuitansi->pengikutdprd($telaah_id);
		}else{
			$this->data['pengikut'] = $this->m_kuitansi->pengikut($telaah_id);
		}
		$this->data['jumlah_pengikut'] = count($this->data['pengikut']);
		$this->render('laporan/kuitansi/content');
	}
	
	//View Create Data
    public function create_view()
    {
		$this->data['telaah_id'] = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$this->data['pegawai_id']= $this->input->get('pegawai_id');
		$this->data['posisi']= $this->uri->segment(5);
        $this->render('laporan/kuitansi/insert');
	}
	
	//View Create Data
    public function create_bendahara_view()
    {
		$this->data['telaah_id'] = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$this->data['pegawai_id']= $this->input->get('pegawai_id');
		$this->data['posisi']= $this->uri->segment(5);
		
		$data = $this->m_telaah->get($this->data['telaah_id']);
		if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']!=11){
			$this->data['bendahara']= $this->m_spd->bendahara(36);
		} else {
			$this->data['bendahara']= $this->m_spd->bendahara($this->ion_auth->user()->row()->skpd_id);
		}
        $this->render('laporan/kuitansi/insert_bendahara');
	}
	
	//Create Data
	public function create()
	{
		$this->form_validation->set_rules('jumlah', 'Jumlah', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->data['telaah_id']= $this->input->post('telaah_id');
			$this->data['pegawai_id']= $this->input->post('pegawai_id');
			$this->data['posisi']= $this->uri->segment(5);
			$this->render('laporan/kuitansi/insert');
		} 
		else 
		{	
			$telaah_id = base64_encode($this->encrypt->encode($this->input->post('telaah_id'), $this->session->userdata('encrypt_key')));	
			$data['telaah_id'] = $this->input->post('telaah_id');
			$data['pegawai_id'] = $this->input->post('pegawai_id');
			$data['jumlah'] = str_replace(".", "", $this->input->post('jumlah'));
			
			$this->m_kuitansi->create($data);
			
			$log['kode_log_action'] = "53";
			$log['action'] = "INSERT";
			$log['kode_log_action_table'] = "12";
			$log['action_table'] = "TABLE KUITANSI PANJAR";
			$this->m_log->create($log);
			
			$this->session->set_flashdata('notif','Data Kuitansi Di Simpan !');
			redirect('telaah/laporan/kuitansi/index/'.$this->input->post('posisi').'?telaah_id='.$telaah_id);
		
		}
	}
	
	//Create Data
	public function create_bendahara()
	{
		$this->form_validation->set_rules('telaah_ttdbendahara', 'Bendahara', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->create_bendahara_view();
		} 
		else 
		{	
			$telaah_id = base64_encode($this->encrypt->encode($this->input->post('telaah_id'), $this->session->userdata('encrypt_key')));	
			
			$data['telaah_id'] = $this->input->post('telaah_id');
			$data['telaah_ttdbendahara'] = $this->input->post('telaah_ttdbendahara');
			
			$this->m_telaah->update($data);
			
			$this->session->set_flashdata('notif','Data Bendahara Di Simpan !');
			redirect('telaah/laporan/kuitansi/index/'.$this->input->post('posisi').'?telaah_id='.$telaah_id);
		
		}
	}
	
	//View Update Data
	public function update_bendahara_view()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		$this->data['entry'] =  $this->m_telaah->get($telaah_id);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('kuitansi');
		} else {
			$this->data['telaah_id'] = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
			$this->data['posisi']= $this->uri->segment(5);
			$this->data['bendahara']= $this->m_spd->bendahara($this->ion_auth->user()->row()->skpd_id);
			$this->render('laporan/kuitansi/update_bendahara');
		}
	}

	//View Update Data
	public function update_view()
	{
		//$kuitansi_panjar_id = $this->encrypt->decode(base64_decode($this->input->get('kuitansi_panjar_id')), $this->session->userdata('encrypt_key'));
		
		$this->data['entry'] =  $this->m_kuitansi->get2($this->input->get('kuitansi_panjar_id'));
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('kuitansi');
		} else {
			$this->data['telaah_id'] = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
			$this->data['posisi']= $this->uri->segment(5);
			$this->render('laporan/kuitansi/update');
		}
	}

	//Update Data
	public function update()
	{
		$this->form_validation->set_rules('jumlah', 'Jumlah', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->data['entry'] =  $this->m_kuitansi->get2($this->input->post('kuitansi_panjar_id'));
			if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
				redirect('kuitansi');
			} else {
				$this->data['telaah_id'] = $this->encrypt->decode(base64_decode($this->input->post('telaah_id')), $this->session->userdata('encrypt_key'));
				$this->data['posisi']= $this->input->post('posisi');
				$this->render('kuitansi/update');
			}
		} 
		else 
		{		
			
			$telaah_id = base64_encode($this->encrypt->encode($this->input->post('telaah_id'), $this->session->userdata('encrypt_key')));	
					
			$data['kuitansi_panjar_id'] = $this->input->post('kuitansi_panjar_id');
			$data['telaah_id'] = $this->input->post('telaah_id');
			$data['pegawai_id'] = $this->input->post('pegawai_id');
			$data['jumlah'] = str_replace(".", "", $this->input->post('jumlah'));
			
			$this->m_kuitansi->update($data);		
			
			$log['kode_log_action'] = "54";
			$log['action'] = "UPDATE kuitansi_panjar_id = ".$this->input->post('kuitansi_panjar_id');
			$log['kode_log_action_table'] = "12";
			$log['action_table'] = "TABLE KUITANSI PANJAR";
			$this->m_log->create($log);	
			
			$this->session->set_flashdata('notif','Data Kuitansi Di Ubah !');
			redirect('telaah/laporan/kuitansi/index/'.$this->input->post('posisi').'?telaah_id='.$telaah_id);
	
		}
	}
	
	//Update Data
	public function update_bendahara()
	{
		$this->form_validation->set_rules('telaah_ttdbendahara', 'Bendahara', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->data['entry'] =  $this->m_telaah->get($this->input->post('telaah_id'));
			if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
				redirect('kuitansi');
			} else {
				$this->data['telaah_id'] = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
				$this->data['posisi']= $this->uri->segment(5);
				$this->data['bendahara']= $this->m_spd->bendahara($this->ion_auth->user()->row()->skpd_id);
				$this->render('laporan/kuitansi/update_bendahara');
			}
		} 
		else 
		{		
			
			$telaah_id = base64_encode($this->encrypt->encode($this->input->post('telaah_id'), $this->session->userdata('encrypt_key')));	
			
			$data['telaah_id'] = $this->input->post('telaah_id');
			$data['telaah_ttdbendahara'] = $this->input->post('telaah_ttdbendahara');
			
			$this->m_telaah->update($data);
			
			$this->session->set_flashdata('notif','Data Bendahara Di Simpan !');
			redirect('telaah/laporan/kuitansi/index/'.$this->input->post('posisi').'?telaah_id='.$telaah_id);
	
		}
	}
	
	//Delete Data
	public function delete() 
	{
		$kuitansi_panjar_id = $this->encrypt->decode(base64_decode($this->input->get('kuitansi_panjar_id')), $this->session->userdata('encrypt_key'));
		$telaah_id = base64_encode($this->encrypt->encode($this->input->get('telaah_id'), $this->session->userdata('encrypt_key')));	
		
        $this->m_kuitansi->delete($kuitansi_panjar_id);
		
		$log['kode_log_action'] = "";
		$log['action'] = "HAPUS rincian_id = ".$kuitansi_panjar_id;
		$log['kode_log_action_table'] = "12";
		$log['action_table'] = "TABLE kuitansi BIAYA";
		$this->m_log->create($log);	
					
		$this->session->set_flashdata('notif','Data kuitansi Di Hapus !');
		redirect('kuitansi?telaah_id='.$telaah_id.'&&posisi='.$this->input->get('posisi'));
    }
	
}