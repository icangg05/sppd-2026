<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Spt extends public_Controller {
    function __construct()
    {
        parent::__construct();
		error_reporting(0);
		$this->load->model('laporan/m_spt');
		$this->load->model('laporan/m_spd');
		$this->load->model('telaah/m_telaah');
		$this->load->model('telaah/m_disposisi');
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
			case "staff_dprd" 	: 	
			case "sekwan" 		: 		
			case "kapus" 		: 	$this->data['spt'] = $this->m_telaah->get($telaah_id);
									$this->data['tanda_tangan_spt'] = $this->m_pegawai->get($this->data['spt'][0]['telaah_ttdspt']);
									break;
			case "walikota" 	: 	$this->data['spt'] = $this->m_telaah->getWalikota($telaah_id);
									$this->data['tanda_tangan_spt'] = $this->m_pegawai->get($this->data['spt'][0]['telaah_ttdspt']);
									break;
			case "kadis" 		: 	
									$this->data['spt'] = $this->m_telaah->get($telaah_id);
									if($this->data['spt'][0]['telaah_ttdsptw']==1){
										$this->data['tanda_tangan_spt'] = $this->m_pegawai->get_walikota($this->data['spt'][0]['telaah_ttdspt']);
									} else {
										$this->data['tanda_tangan_spt'] = $this->m_pegawai->get($this->data['spt'][0]['telaah_ttdspt']);
									}
									break;
			case "dprd" 		: 	$this->data['spt'] = $this->m_telaah->get_dprd($telaah_id);
									$this->data['tanda_tangan_spt'] = $this->m_pegawai->get($this->data['spt'][0]['telaah_ttdspt']);
									break;
		}
		
		$this->data['t'] = $this->m_spt->getTelaahKategori($telaah_id);
		$this->data['t'] =$this->data['t'][0];
		
		$x = $this->m_spt->getTelaahKategori($telaah_id);
		foreach($x as $f) {
			$telaah_kategori = $f->telaah_kategori;
		}
		
		if($telaah_kategori == 8) {
			$this->data['pelaksana'] = $this->m_spd->pelaksanaWalikota($telaah_id);	
		}elseif($telaah_kategori == 3){
			$this->data['pelaksana'] = $this->m_spd->pelaksanadprd($telaah_id);	
		} else {
			
			$this->data['pelaksana'] = $this->m_spd->pelaksana($telaah_id);
		}
		
		if($telaah_kategori == 3) {
			$this->data['pengikut'] = $this->m_spd->pengikutdprd($telaah_id);
		}else{
			$this->data['pengikut'] = $this->m_spd->pengikut($telaah_id);
		}
		$this->data['jumlah_pengikut'] = count($this->data['pengikut']);
		$this->render('laporan/spt/content');
	}
	
	//View Create Data
    public function create_view()
    {
		$this->data['telaah_id'] = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$this->data['posisi']= $this->uri->segment(5);
		$this->data['data'] = $this->m_telaah->get($this->data['telaah_id']);
		
		switch($this->uri->segment(5)){
			case "esselon" 		: 	$this->data['kepala_opd']= $this->m_spd->kepala_opd($this->ion_auth->user()->row()->skpd_id);
									$this->data['sekretaris_opd']= $this->m_spd->sekretaris_opd($this->ion_auth->user()->row()->skpd_id);
									if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']==1){
										$this->data['kabid']= $this->m_spd->kabid_dinkes();
									} else {
										$this->data['kabid']= $this->m_spd->kabid_opd($this->ion_auth->user()->row()->skpd_id);
									}
									break;
			case "kadis" 		: 	$this->data['kepala_opd']= $this->m_spd->kepala_opd($this->ion_auth->user()->row()->skpd_id);
									$this->data['walikota']= $this->m_spd->walikota();
									$this->data['wakil_walikota']= $this->m_spd->wakil_walikota();
									$this->data['sekda']= $this->m_spd->sekda();
									break;
			case "dprd" 		: 	$this->data['ketua_dprd']= $this->m_spd->ketua_dprd();
									$this->data['wakil_ketua_dprd']= $this->m_spd->wakil_ketua_dprd();
									break;
			case "sekwan" 		: 	$this->data['walikota']= $this->m_spd->walikota();
									$this->data['wakil_walikota']= $this->m_spd->wakil_walikota();
									$this->data['sekda']= $this->m_spd->sekda();
									break;
			case "staff_dprd" 	: 	$this->data['sekwan']= $this->m_spd->sekwan($this->ion_auth->user()->row()->skpd_id);
									$this->data['kabid']= $this->m_spd->kabid_opd($this->ion_auth->user()->row()->skpd_id);
									break;
			case "walikota" 	: 
			case "sekda" 		: 	$this->data['walikota']= $this->m_spd->walikota();
									$this->data['wakil_walikota']= $this->m_spd->wakil_walikota();
									$this->data['sekda']= $this->m_spd->sekda();
									$this->data['asisten1']= $this->m_spd->asisten1();
									$this->data['asisten2']= $this->m_spd->asisten2();
									$this->data['asisten3']= $this->m_spd->asisten3();
									break;		
			case "staff_setda" 	: 	
									$this->data['sekda']= $this->m_spd->sekda();
									$this->data['asisten1']= $this->m_spd->asisten1();
									$this->data['asisten2']= $this->m_spd->asisten2();
									$this->data['asisten3']= $this->m_spd->asisten3();
									break;
			case "camat" 		:
			case "lurah" 		:	$this->data['walikota']= $this->m_spd->walikota();
									break;
			case "staff_camat" 	:	$this->data['camat']= $this->m_spd->camat($this->ion_auth->user()->row()->skpd_id);
									$this->data['sekcam']= $this->m_spd->sekcam($this->ion_auth->user()->row()->skpd_id);
									break;
			case "staff_lurah" 	:	$this->data['lurah']= $this->m_spd->lurah($this->ion_auth->user()->row()->skpd_id);
									break;
			case "kapus" 		: 	$this->data['kapus']= $this->m_spd->kapus($this->ion_auth->user()->row()->skpd_id);
									break;
		}
		
        $this->render('laporan/spt/insert');
	}
	
	//Create Data
	public function create()
	{
		$this->form_validation->set_rules('telaah_tanggalspt', 'Tanggal', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$telaah_id = base64_encode($this->encrypt->encode($this->input->post('telaah_id'), $this->session->userdata('encrypt_key')));	
			redirect('telaah/laporan/spt/create_view/esselon?telaah_id='.$telaah_id);
			
		} 
		else 
		{	
			$telaah_id = base64_encode($this->encrypt->encode($this->input->post('telaah_id'), $this->session->userdata('encrypt_key')));	
			
			$ttd=explode(",",$this->input->post('telaah_ttdspt'));

			echo $ttd[0]; //Code 
			echo $ttd[1]; //Description  
			
			## WALIKOTA, WAKIL WALIKOTA
			if($ttd[0]==1){
				$data['telaah_id'] = $this->input->post('telaah_id');
				$data['telaah_tanggalspt'] = $this->input->post('telaah_tanggalspt');
				$data['telaah_ttdspt'] = $ttd[1];
				$data['telaah_ttdsptw'] = 1;
				
				$this->m_telaah->update($data);
			} else {
				$data['telaah_id'] = $this->input->post('telaah_id');
				$data['telaah_tanggalspt'] = $this->input->post('telaah_tanggalspt');
				$data['telaah_ttdspt'] = $ttd[1];
				$data['telaah_ttdsptw'] = 0;
				
				$this->m_telaah->update($data);
			} 
			
			$log['kode_log_action'] = "53";
			$log['action'] = "INSERT";
			$log['kode_log_action_table'] = "22";
			$log['action_table'] = "TABLE TANGGAL PERJALANAN (SPT)";
			$this->m_log->create($log);
			
			$this->session->set_flashdata('notif','Data Tanggal SPT Di Simpan !');
			redirect('telaah/laporan/spt/index/'.$this->input->post('posisi').'?telaah_id='.$telaah_id);
		
		}
	}
	
	//View Update Data
	public function update_view()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$this->data['entry'] =  $this->m_telaah->get($telaah_id);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('spt');
		} else {
			$this->data['telaah_id'] = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
			$this->data['posisi']= $this->uri->segment(5);
			$this->data['data'] = $this->m_telaah->get($this->data['telaah_id']);
			
			switch($this->uri->segment(5)){
				case "esselon" 		: 	$this->data['kepala_opd']= $this->m_spd->kepala_opd($this->ion_auth->user()->row()->skpd_id);
										$this->data['sekretaris_opd']= $this->m_spd->sekretaris_opd($this->ion_auth->user()->row()->skpd_id);
										if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']==1){
											$this->data['kabid']= $this->m_spd->kabid_dinkes();
										} else {
											$this->data['kabid']= $this->m_spd->kabid_opd($this->ion_auth->user()->row()->skpd_id);
										}
										break;
				case "kadis" 		: 	$this->data['kepala_opd']= $this->m_spd->kepala_opd($this->ion_auth->user()->row()->skpd_id);
										$this->data['walikota']= $this->m_spd->walikota();
										$this->data['wakil_walikota']= $this->m_spd->wakil_walikota();
										$this->data['sekda']= $this->m_spd->sekda();
										break;
				case "dprd" 		: 	$this->data['ketua_dprd']= $this->m_spd->ketua_dprd();
										$this->data['wakil_ketua_dprd']= $this->m_spd->wakil_ketua_dprd();
										break;
				case "sekwan" 		: 	$this->data['walikota']= $this->m_spd->walikota();
										$this->data['wakil_walikota']= $this->m_spd->wakil_walikota();
										$this->data['sekda']= $this->m_spd->sekda();
										break;
				case "staff_dprd" 	: 	$this->data['sekwan']= $this->m_spd->sekwan($this->ion_auth->user()->row()->skpd_id);
										$this->data['kabid']= $this->m_spd->kabid_opd($this->ion_auth->user()->row()->skpd_id);
										break;
				case "walikota" 	: 
				case "sekda" 		: 	$this->data['walikota']= $this->m_spd->walikota();
										$this->data['wakil_walikota']= $this->m_spd->wakil_walikota();
										$this->data['sekda']= $this->m_spd->sekda();
										$this->data['asisten1']= $this->m_spd->asisten1();
										$this->data['asisten2']= $this->m_spd->asisten2();
										$this->data['asisten3']= $this->m_spd->asisten3();
										break;		
				case "staff_setda" 	: 	
										$this->data['sekda']= $this->m_spd->sekda();
										$this->data['asisten1']= $this->m_spd->asisten1();
										$this->data['asisten2']= $this->m_spd->asisten2();
										$this->data['asisten3']= $this->m_spd->asisten3();
										break;
				case "camat" 		:
				case "lurah" 		:	$this->data['walikota']= $this->m_spd->walikota();
										break;
				case "staff_camat" 	:	$this->data['camat']= $this->m_spd->camat($this->ion_auth->user()->row()->skpd_id);
										$this->data['sekcam']= $this->m_spd->sekcam($this->ion_auth->user()->row()->skpd_id);
										break;
				case "staff_lurah" 	:	$this->data['lurah']= $this->m_spd->lurah($this->ion_auth->user()->row()->skpd_id);
										break;
				case "kapus" 		: 	$this->data['kapus']= $this->m_spd->kapus($this->ion_auth->user()->row()->skpd_id);
										break;
			}
		
			$this->render('laporan/spt/update');
		}
	}
	
	//Update Data
	public function update()
	{
		$this->form_validation->set_rules('telaah_tanggalspt', 'Tanggal', 'required');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->data['entry'] =  $this->m_spd->get($this->input->post('tanggal_perjalanan_id'));
			if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
				redirect('spt');
			} else {
				$this->data['telaah_id']= $this->input->post('telaah_id');
				$this->data['posisi']= $this->uri->segment(5);
				$this->data['data'] = $this->m_telaah->get($this->data['telaah_id']);
				
				switch($this->uri->segment(5)){
					case "esselon" 		: 	$this->data['kepala_opd']= $this->m_spd->kepala_opd($this->ion_auth->user()->row()->skpd_id);
											$this->data['sekretaris_opd']= $this->m_spd->sekretaris_opd($this->ion_auth->user()->row()->skpd_id);
											if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']==1){
												$this->data['kabid']= $this->m_spd->kabid_dinkes();
											} else {
												$this->data['kabid']= $this->m_spd->kabid_opd($this->ion_auth->user()->row()->skpd_id);
											}
											break;
					case "kadis" 		: 	$this->data['kepala_opd']= $this->m_spd->kepala_opd($this->ion_auth->user()->row()->skpd_id);
											$this->data['walikota']= $this->m_spd->walikota();
											$this->data['wakil_walikota']= $this->m_spd->wakil_walikota();
											$this->data['sekda']= $this->m_spd->sekda();
											break;
					case "dprd" 		: 	$this->data['ketua_dprd']= $this->m_spd->ketua_dprd();
											$this->data['wakil_ketua_dprd']= $this->m_spd->wakil_ketua_dprd();
											break;
					case "sekwan" 		: 	$this->data['walikota']= $this->m_spd->walikota();
											$this->data['wakil_walikota']= $this->m_spd->wakil_walikota();
											$this->data['sekda']= $this->m_spd->sekda();
											break;
					case "staff_dprd" 	: 	$this->data['sekwan']= $this->m_spd->sekwan($this->ion_auth->user()->row()->skpd_id);
											$this->data['kabid']= $this->m_spd->kabid_opd($this->ion_auth->user()->row()->skpd_id);
											break;
					case "walikota" 	: 
					case "sekda" 		: 	$this->data['walikota']= $this->m_spd->walikota();
											$this->data['wakil_walikota']= $this->m_spd->wakil_walikota();
											$this->data['sekda']= $this->m_spd->sekda();
											$this->data['asisten1']= $this->m_spd->asisten1();
											$this->data['asisten2']= $this->m_spd->asisten2();
											$this->data['asisten3']= $this->m_spd->asisten3();
											break;		
					case "staff_setda" 	: 	
											$this->data['sekda']= $this->m_spd->sekda();
											$this->data['asisten1']= $this->m_spd->asisten1();
											$this->data['asisten2']= $this->m_spd->asisten2();
											$this->data['asisten3']= $this->m_spd->asisten3();
											break;
					case "camat" 		:
					case "lurah" 		:	$this->data['walikota']= $this->m_spd->walikota();
											break;
					case "staff_camat" 	:	$this->data['camat']= $this->m_spd->camat($this->ion_auth->user()->row()->skpd_id);
											$this->data['sekcam']= $this->m_spd->sekcam($this->ion_auth->user()->row()->skpd_id);
											break;
					case "staff_lurah" 	:	$this->data['lurah']= $this->m_spd->lurah($this->ion_auth->user()->row()->skpd_id);
											break;
					case "kapus" 		: 	$this->data['kapus']= $this->m_spd->kapus($this->ion_auth->user()->row()->skpd_id);
											break;
				}
		
				$this->render('laporan/spt/update');
			}
		} 
		else 
		{		
			
			$telaah_id = base64_encode($this->encrypt->encode($this->input->post('telaah_id'), $this->session->userdata('encrypt_key')));	
			
			$ttd=explode(",",$this->input->post('telaah_ttdspt'));

			echo $ttd[0]; //Code 
			echo $ttd[1]; //Description  
			
			## WALIKOTA, WAKIL WALIKOTA
			if($ttd[0]==1){
				$data['telaah_id'] = $this->input->post('telaah_id');
				$data['telaah_tanggalspt'] = $this->input->post('telaah_tanggalspt');
				$data['telaah_ttdspt'] = $this->input->post('telaah_ttdspt');
				$data['telaah_ttdsptw'] = 1;
				
				$this->m_telaah->update($data);
			} else {
				$data['telaah_id'] = $this->input->post('telaah_id');
				$data['telaah_tanggalspt'] = $this->input->post('telaah_tanggalspt');
				$data['telaah_ttdspt'] = $this->input->post('telaah_ttdspt');
				$data['telaah_ttdsptw'] = 0;
				
				$this->m_telaah->update($data);
			} 
			
			$log['kode_log_action'] = "54";
			$log['action'] = "UPDATE tanggal_perjalanan_id = ".$this->input->post('tanggal_perjalanan_id');
			$log['kode_log_action_table'] = "22";
			$log['action_table'] = "TABLE TANGGAL PERJALANAN (SPT)";
			$this->m_log->create($log);	
			
			$this->session->set_flashdata('notif','Data Tanggal SPT Di Ubah !');
			redirect('telaah/laporan/spt/index/'.$this->input->post('posisi').'?telaah_id='.$telaah_id);
	
		}
	}
	
	//Reset TTE
    public function reset_tte()
    {
		$telaah_id = $this->encrypt->decode(base64_decode($this->uri->segment(5)), $this->session->userdata('encrypt_key'));
		$telaah = $this->m_telaah->get($telaah_id);
		
		switch($this->uri->segment(6)){
			case "esselon" 		: 	$data['telaah_id'] = $telaah_id;
									$data['timeline_kadis_id'] = 0; 
									$this->m_disposisi->update_timeline_1($data,'');
									
									$data2['telaah_id'] = $telaah_id;
									$data2['telaah_reset_tte2'] = 1;
									$this->m_telaah->update($data2);
									
									$this->session->set_flashdata('notif','TTE Di Reset !');
									redirect('telaah/laporan/spt/index/esselon?telaah_id='.$this->uri->segment(5));
									break;
			case "kadis" 		: 	if($telaah[0]['telaah_domainperjalanan']==3){
										$data['telaah_id'] = $telaah_id;
										$data['timeline_kadis_id'] = 0; 
										$this->m_disposisi->update_timeline_2($data,'');
									} else {
										$data['telaah_id'] = $telaah_id;
										$data['timeline_walikota_id'] = 0; 
										$this->m_disposisi->update_timeline_2($data,'');
									}
									
									$data2['telaah_id'] = $telaah_id;
									$data2['telaah_reset_tte2'] = 1;
									$this->m_telaah->update($data2);
									
									$this->session->set_flashdata('notif','TTE Di Reset !');
									redirect('telaah/laporan/spt/index/kadis?telaah_id='.$this->uri->segment(5));
									break;
			case "dprd" 		: 	$data['telaah_id'] = $telaah_id;
									$data['timeline_kadprd_id'] = 0; 
									$this->m_disposisi->update_timeline_3($data,'');
									
									$data2['telaah_id'] = $telaah_id;
									$data2['telaah_reset_tte2'] = 1;
									$this->m_telaah->update($data2);
									
									$this->session->set_flashdata('notif','TTE Di Reset !');
									redirect('telaah/laporan/spt/index/dprd?telaah_id='.$this->uri->segment(5));
									break;	
			case "staff_dprd" 	: 	$data['telaah_id'] = $telaah_id;
									$data['timeline_sekwan_id'] = 0; 
									$this->m_disposisi->update_timeline_6($data,'');
									
									$data2['telaah_id'] = $telaah_id;
									$data2['telaah_reset_tte2'] = 1;
									$this->m_telaah->update($data2);
									
									$this->session->set_flashdata('notif','TTE Di Reset !');
									redirect('telaah/laporan/spt/index/staff_dprd?telaah_id='.$this->uri->segment(5));
									break;	
			case "sekwan" 		: 	$data['telaah_id'] = $telaah_id;
									$data['timeline_walikota_id'] = 0; 
									$this->m_disposisi->update_timeline_10($data,'');
									
									$data2['telaah_id'] = $telaah_id;
									$data2['telaah_reset_tte2'] = 1;
									$this->m_telaah->update($data2);
									
									$this->session->set_flashdata('notif','TTE Di Reset !');
									redirect('telaah/laporan/spt/index/sekwan?telaah_id='.$this->uri->segment(5));
									break;	
			case "walikota" 	: 	$data['telaah_id'] = $telaah_id;
									$data['timeline_walikota_id'] = 0; 
									$this->m_disposisi->update_timeline_8($data,'');
									
									$data2['telaah_id'] = $telaah_id;
									$data2['telaah_reset_tte2'] = 1;
									$this->m_telaah->update($data2);
									
									$this->session->set_flashdata('notif','TTE Di Reset !');
									redirect('telaah/laporan/spt/index/walikota?telaah_id='.$this->uri->segment(5));
									break;	
			case "sekda" 		: 	$data['telaah_id'] = $telaah_id;
									$data['timeline_walikota_id'] = 0; 
									$this->m_disposisi->update_timeline_4($data,'');
									
									$data2['telaah_id'] = $telaah_id;
									$data2['telaah_reset_tte2'] = 1;
									$this->m_telaah->update($data2);
									
									$this->session->set_flashdata('notif','TTE Di Reset !');
									redirect('telaah/laporan/spt/index/sekda?telaah_id='.$this->uri->segment(5));
									break;	
			case "staff_setda" 	: 	$data['telaah_id'] = $telaah_id;
									$data['timeline_sekda_id'] = 0; 
									$this->m_disposisi->update_timeline_9($data,'');
									
									$data2['telaah_id'] = $telaah_id;
									$data2['telaah_reset_tte2'] = 1;
									$this->m_telaah->update($data2);
									
									$this->session->set_flashdata('notif','TTE Di Reset !');
									redirect('telaah/laporan/spt/index/staff_setda?telaah_id='.$this->uri->segment(5));
									break;	
			case "camat" 		:	$data['telaah_id'] = $telaah_id;
									$data['timeline_sekda_id'] = 0; 
									$this->m_disposisi->update_timeline_5($data,'');
									
									$data2['telaah_id'] = $telaah_id;
									$data2['telaah_reset_tte2'] = 1;
									$this->m_telaah->update($data2);
									
									$this->session->set_flashdata('notif','TTE Di Reset !');
									redirect('telaah/laporan/spt/index/camat?telaah_id='.$this->uri->segment(5));
									break;	
			case "lurah" 		:	$data['telaah_id'] = $telaah_id;
									$data['timeline_sekda_id'] = 0; 
									$this->m_disposisi->update_timeline_5($data,'');
									
									$data2['telaah_id'] = $telaah_id;
									$data2['telaah_reset_tte2'] = 1;
									$this->m_telaah->update($data2);
									
									$this->session->set_flashdata('notif','TTE Di Reset !');
									redirect('telaah/laporan/spt/index/lurah?telaah_id='.$this->uri->segment(5));
									break;	
			case "staff_camat" 	:	$data['telaah_id'] = $telaah_id;
									$data['timeline_camat_id'] = 0; 
									$this->m_disposisi->update_timeline_7($data,'');
									
									$data2['telaah_id'] = $telaah_id;
									$data2['telaah_reset_tte2'] = 1;
									$this->m_telaah->update($data2);
									
									$this->session->set_flashdata('notif','TTE Di Reset !');
									redirect('telaah/laporan/spt/index/staff_camat?telaah_id='.$this->uri->segment(5));
									break;	
			case "staff_lurah" 	:	$data['telaah_id'] = $telaah_id;
									$data['timeline_lurah_id'] = 0; 
									$this->m_disposisi->update_timeline_7($data,'');
									
									$data2['telaah_id'] = $telaah_id;
									$data2['telaah_reset_tte2'] = 1;
									$this->m_telaah->update($data2);
									
									$this->session->set_flashdata('notif','TTE Di Reset !');
									redirect('telaah/laporan/spt/index/staff_lurah?telaah_id='.$this->uri->segment(5));
									break;	
			case "kapus" 		:	$data['telaah_id'] = $telaah_id;
									$data['timeline_kapus_id'] = 0; 
									$this->m_disposisi->update_timeline_11($data,'');
									
									$data2['telaah_id'] = $telaah_id;
									$data2['telaah_reset_tte2'] = 1;
									$this->m_telaah->update($data2);
									
									$this->session->set_flashdata('notif','TTE Di Reset !');
									redirect('telaah/laporan/spt/index/kapus?telaah_id='.$this->uri->segment(5));
									break;	
		}
		
	}
}