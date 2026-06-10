<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kalender extends public_Controller {
    public function __construct() {
        parent::__construct();
		error_reporting(0);
		$this->load->model('setting_root/m_kalender');
		$this->load->model('telaah/m_kadis');
		$this->load->model('telaah/m_esselon');
		$this->load->model('telaah/m_dprd');
		$this->load->model('telaah/m_sekda');
		$this->load->model('telaah/m_staff_dprd');
		$this->load->model('telaah/m_sekwan');
		$this->load->model('telaah/m_camat');
		$this->load->model('telaah/m_lurah');
		$this->load->model('telaah/m_staff_camat');
		$this->load->model('telaah/m_staff_lurah');
		$this->load->model('telaah/m_kapus');
		$this->load->model('telaah/m_sekwan');
		$this->load->model('telaah/m_pengikut');
		$this->load->model('telaah/m_lokasi_tujuan');
		$this->load->model('telaah/m_timeline');
		$this->load->model('telaah/m_telaah');
		$this->load->model('telaah/m_relasi_sekda');
		if((!$this->ion_auth->get_users_groups()->row()->id == 9) || (!$this->ion_auth->get_users_groups()->row()->id == 100))
		{
			redirect('login');
		}
    }
    
    //View All Data
	public function index()
	{
		$this->render('setting_root/kalender/content');
	}

	function load($tahun)
	{
		$event_data = $this->m_kalender->data($this->ion_auth->user()->row()->skpd_id,$tahun);
		foreach($event_data->result_array() as $row)
		{
			$data[] = array(
			'id' => $row['telaah_id'],
			'title' => $row['pegawai_nama'],
			'kategori' => $row['telaah_kategori'],
			'jenis_skpd' => $row['telaah_jenis_skpd'],
			'start' => $row['telaah_tanggalberangkat'],
			'end' => date('Y-m-d', strtotime( $row['telaah_tanggalkembali'] . " +1 days")),
			);
		}
		echo json_encode($data, JSON_PRETTY_PRINT) ;
		
	}

	//View Detail Data
	public function detail($jenis_skpd, $kategori, $telaah_id)
	{
		switch($this->uri->segment(5)){
			case "1" 		:  if($this->uri->segment(4)==1){
							$this->data['entry'] =  $this->m_esselon->get($telaah_id); break;
						} else {
							$this->data['entry'] =  $this->m_esselon->get($telaah_id); break;
						}
			case "2" 		: $this->data['entry'] =  $this->m_kadis->get($telaah_id); break;
			case "3" 		: $this->data['entry'] =  $this->m_dprd->get($telaah_id); break;
			case "4" 		: $this->data['entry'] =  $this->m_sekda->get($telaah_id); break;
			case "5" 		: 	if($this->uri->segment(4)==4){
								$this->data['entry'] =  $this->m_camat->get($telaah_id); break;
							} else {
								$this->data['entry'] =  $this->m_lurah->get($telaah_id); break;
							}
			case "6" 		: $this->data['entry'] =  $this->m_staff_dprd->get($telaah_id); break;
			case "7" 		: 	if($this->uri->segment(4)==4){
								$this->m_staff_camat->get($telaah_id); break;
							} else {
								$this->m_staff_lurah->get($telaah_id); break;
							}
			case "8" 		: $this->data['entry'] =  $this->m_sekda->getWalikota($telaah_id); break;
			case "9" 		: $this->data['entry'] =  $this->m_sekda->get($telaah_id); break;
			case "10" 	: $this->data['entry'] =  $this->m_sekwan->get($telaah_id); break;
			case "11" 	: $this->data['entry'] =  $this->m_kapus->get($telaah_id); break;
		}
		
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('setting_root/kalender');
			
		} else {
			
			if($this->uri->segment(4)=="dprd"){
				$this->data['pengikut'] =  $this->m_pengikut->data_dprd($telaah_id);
			} else {
				$this->data['pengikut'] =  $this->m_pengikut->data($telaah_id);
			}
			
			// Get TimeLine
			switch($this->uri->segment(5)){
				case "1" 				: 
										if($this->uri->segment(4)==1){
											$timeline =  $this->m_esselon->getTimeline1($telaah_id); 
															if ($this->data['entry'][0]['telaah_sekretariat']==1){
																$this->data['disposisi1'] = $timeline[0]['timeline_sekdis_id'];
																$this->data['disposisi2'] = $timeline[0]['timeline_kadis_id'];
																$this->data['disposisi3'] = "";
																$this->data['disposisi4'] = "";
																
																$this->data['nama_disposisi1'] = "SEKRETARIS OPD";
																$this->data['nama_disposisi2'] = "KEPALA OPD";
															} else {
																$this->data['disposisi1'] = $timeline[0]['timeline_kabid_id'];
																$this->data['disposisi2'] = $timeline[0]['timeline_sekdis_id'];
																$this->data['disposisi3'] = $timeline[0]['timeline_kadis_id'];
																$this->data['disposisi4'] = "";
																
																$this->data['nama_disposisi1'] = "KABID / IRBAN / KABAG";
																$this->data['nama_disposisi2'] = "SEKRETARIS OPD";
																$this->data['nama_disposisi3'] = "KEPALA OPD";
															}
															break;
										} else {
											$timeline =  $this->m_esselon->getTimeline1($telaah_id); 
															if ($this->data['entry'][0]['telaah_sekretariat']==1){
																$this->data['disposisi1'] = $timeline[0]['timeline_sekdis_id'];
																$this->data['disposisi2'] = $timeline[0]['timeline_kadis_id'];
																$this->data['disposisi3'] = "";
																$this->data['disposisi4'] = "";
																
																$this->data['nama_disposisi1'] = "SEKRETARIS OPD";
																$this->data['nama_disposisi2'] = "KEPALA OPD";
															} else {
																$this->data['disposisi1'] = $timeline[0]['timeline_kabid_id'];
																$this->data['disposisi2'] = $timeline[0]['timeline_sekdis_id'];
																$this->data['disposisi3'] = $timeline[0]['timeline_kadis_id'];
																$this->data['disposisi4'] = "";
																
																$this->data['nama_disposisi1'] = "KABID / IRBAN / KABAG";
																$this->data['nama_disposisi2'] = "SEKRETARIS OPD";
																$this->data['nama_disposisi3'] = "KEPALA OPD";
															}
															break;
										}
										
				case "2" 			: $timeline =  $this->m_kadis->getTimeline2($telaah_id); 
										  if ($this->data['entry'][0]['telaah_domainperjalanan']==3 || $this->data['entry'][0]['telaah_domainperjalanan']==4) {
											  $this->data['disposisi1'] = $timeline[0]['timeline_sekdis_id'];
											  $this->data['disposisi2'] = $timeline[0]['timeline_kadis_id'];
											  $this->data['disposisi3'] = "";
											  $this->data['disposisi4'] = "";
											  
											  $this->data['nama_disposisi1'] = "SEKRETARIS OPD";
											  $this->data['nama_disposisi2'] = "KEPALA OPD";
										  } else {
											  $this->data['disposisi1'] = $timeline[0]['timeline_sekdis_id'];
											  $this->data['disposisi2'] = $timeline[0]['timeline_kadis_id'];
											  $this->data['disposisi3'] = $timeline[0]['timeline_sekda_id'];
											  $this->data['disposisi4'] = $timeline[0]['timeline_walikota_id'];
											  
											  $this->data['nama_disposisi1'] = "SEKRETARIS OPD";
											  $this->data['nama_disposisi2'] = "KEPALA OPD";
											  $this->data['nama_disposisi3'] = "SEKDA";
											  $this->data['nama_disposisi4'] = "WALIKOTA";
										  }
										  break;
				case "3" 			: $timeline =  $this->m_dprd->getTimeline($telaah_id); 
										  $this->data['disposisi1'] = $timeline[0]['timeline_kasubid_id'];
										  $this->data['disposisi2'] = $timeline[0]['timeline_sekwan_id'];
										  $this->data['disposisi3'] = $timeline[0]['timeline_kadprd_id'];
										  $this->data['disposisi4'] = "";
										  
										  $this->data['nama_disposisi1'] = "KABAG";
										  $this->data['nama_disposisi2'] = "SEKRETARIS DEWAN";
										  $this->data['nama_disposisi3'] = "PIMPINAN DPRD";
										  break;
				case "4" 			: $timeline =  $this->m_sekda->getTimeline($telaah_id); 
										  $this->data['disposisi1'] = $timeline[0]['timeline_kabag_id'];
										  $this->data['disposisi2'] = $timeline[0]['timeline_asisten_id'];
										  $this->data['disposisi3'] = $timeline[0]['timeline_sekda_id'];
										  $this->data['disposisi4'] = $timeline[0]['timeline_walikota_id'];
										  
										  $this->data['nama_disposisi1'] = "KABAG";
										  $this->data['nama_disposisi2'] = "ASISTEN/KEPALA OPD";
										  $this->data['nama_disposisi3'] = "SEKDA";
										  $this->data['nama_disposisi4'] = "WALIKOTA";
										  break;
				case "5" 			:  	if($this->uri->segment(4)==4){
										$timeline =  $this->m_camat->getTimeline($telaah_id); 
														$this->data['disposisi1'] = $timeline[0]['timeline_sekcam_id'];
														$this->data['disposisi2'] = $timeline[0]['timeline_camat_id'];
														$this->data['disposisi3'] = $timeline[0]['timeline_sekda_id'];
														$this->data['disposisi4'] = $timeline[0]['timeline_walikota_id'];
														
														$this->data['nama_disposisi1'] = "SEKCAM";
														$this->data['nama_disposisi2'] = "CAMAT";
														$this->data['nama_disposisi3'] = "SEKDA";
														$this->data['nama_disposisi4'] = "WALIKOTA";
														break;
									} else {
										$timeline =  $this->m_lurah->getTimeline($telaah_id); 
														$this->data['disposisi1'] = $timeline[0]['timeline_sekcam_id'];
														$this->data['disposisi2'] = $timeline[0]['timeline_camat_id'];
														$this->data['disposisi3'] = $timeline[0]['timeline_sekda_id'];
														$this->data['disposisi4'] = "";
														
														$this->data['nama_disposisi1'] = "SEKCAM";
														$this->data['nama_disposisi2'] = "CAMAT";
														$this->data['nama_disposisi3'] = "SEKDA";
														$this->data['nama_disposisi4'] = "";
														break;
									}
					
				case "6" 		: $timeline =  $this->m_staff_dprd->getTimeline($telaah_id); 
										  $this->data['disposisi1'] = $timeline[0]['timeline_kabag_id'];
										  $this->data['disposisi2'] = $timeline[0]['timeline_sekwan_id'];
										  $this->data['disposisi3'] = "";
										  $this->data['disposisi4'] = "";
										  
										  $this->data['nama_disposisi1'] = "KABAG";
										  $this->data['nama_disposisi2'] = "SEKRETARIS DEWAN";
										  break;
				case "7" 		: 	if($this->uri->segment(4)==4){
									$timeline =  $this->m_staff_camat->getTimeline($telaah_id); 
													$this->data['disposisi1'] = $timeline[0]['timeline_lurah_id'];
													$this->data['disposisi2'] = $timeline[0]['timeline_sekcam_id'];
													$this->data['disposisi3'] = $timeline[0]['timeline_camat_id'];
													$this->data['disposisi4'] = "";
													
													$this->data['nama_disposisi1'] = "KASUBAG";
													$this->data['nama_disposisi2'] = "SEKCAM";
													$this->data['nama_disposisi3'] = "CAMAT";
													break;
								} else {
									$timeline =  $this->m_staff_lurah->getTimeline($telaah_id); 
													$this->data['disposisi1'] = $timeline[0]['timeline_lurah_id'];
													$this->data['disposisi2'] = $timeline[0]['timeline_sekcam_id'];
													$this->data['disposisi3'] = $timeline[0]['timeline_camat_id'];
													$this->data['disposisi4'] = "";
													
													$this->data['nama_disposisi1'] = "LURAH";
													$this->data['nama_disposisi2'] = "SEKCAM";
													$this->data['nama_disposisi3'] = "CAMAT";
													break;
								}
					
				case "8" 		: $timeline =  $this->m_sekda->getTimeline8($telaah_id); 
										  $this->data['disposisi1'] = $timeline[0]['timeline_kabag_id'];
										  $this->data['disposisi2'] = $timeline[0]['timeline_sekda_id'];
										  $this->data['disposisi3'] = $timeline[0]['timeline_walikota_id'];
										  $this->data['disposisi4'] = "";
										  
										  $this->data['nama_disposisi1'] = "KABAG";
										  $this->data['nama_disposisi2'] = "SEKDA";
										  $this->data['nama_disposisi3'] = "WALIKOTA";
										  break;
				case "9" 		: $timeline =  $this->m_sekda->getTimeline9($telaah_id); 
										  $this->data['disposisi1'] = $timeline[0]['timeline_kabag_id'];
										  $this->data['disposisi2'] = $timeline[0]['timeline_asisten_id'];
										  $this->data['disposisi3'] = $timeline[0]['timeline_sekda_id'];
										  $this->data['disposisi4'] = "";
										  
										  $this->data['nama_disposisi1'] = "KABAG";
										  $this->data['nama_disposisi2'] = "ASISTEN/KEPALA OPD";
										  $this->data['nama_disposisi3'] = "SEKDA";
										  break;
				case "10" 			: $timeline =  $this->m_sekwan->getTimeline10($telaah_id); 
										  $this->data['disposisi1'] = $timeline[0]['timeline_kabag_id'];
										  $this->data['disposisi2'] = $timeline[0]['timeline_sekwan_id'];
										  $this->data['disposisi3'] = $timeline[0]['timeline_sekda_id'];
										  $this->data['disposisi4'] = $timeline[0]['timeline_walikota_id'];
										  
										  $this->data['nama_disposisi1'] = "KABAG";
										  $this->data['nama_disposisi2'] = "SEKWAN";
										  $this->data['nama_disposisi3'] = "SEKDA";
										  $this->data['nama_disposisi4'] = "WALIKOTA";
										  break;
				case "11" 			: $timeline =  $this->m_kapus->getTimeline2($telaah_id); 
										  $this->data['disposisi1'] = $timeline[0]['timeline_kapus_id'];
										  $this->data['disposisi2'] = "";
										  $this->data['disposisi3'] = "";
										  $this->data['disposisi4'] = "";
										  
										  $this->data['nama_disposisi1'] = "KEPALA PUSKESMAS";
										  break;
			}
			$this->load->view('templates/_parts/header');
			$this->load->view('setting_root/kalender/detail', $this->data);
		}
	}
	
}