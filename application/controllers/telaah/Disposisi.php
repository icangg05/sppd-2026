<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Disposisi extends public_Controller {
	function __construct()
	{
		parent::__construct();
		error_reporting(0);
		$this->load->model('telaah/m_disposisi');
		$this->load->model('telaah/m_telaah');
		$this->load->model('telaah/m_timeline');
		$this->load->model('telaah/m_telaah_disetujui');
		$this->load->model('telaah/m_telaah_ditolak');
		$this->load->model('setting_admin/m_anggaran');
		$this->load->model('laporan/m_rincian');
		$this->load->model('telaah/m_relasi_sekda');
		$this->load->model('telaah/m_pengikut');
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
		$this->load->model('telaah/m_walikota');
		$this->load->model('telaah/m_lokasi_tujuan');
		$this->load->model('setting_admin/m_history');
		$this->load->model('setting_root/m_admin');
		$this->load->model('telaah/m_timeline');
		$this->load->model('laporan/m_spd');
		$this->load->model('setting/m_log');
		$this->load->model('m_beranda');
		$this->load->model('m_widget');
		
		$this->data['count_tte'] = $this->m_beranda->count_tte($this->ion_auth->get_users_groups()->row()->id, $this->ion_auth->user()->row()->skpd_id, $this->ion_auth->user()->row()->jenis_skpd);
		
		if(!($this->ion_auth->user()->row()->id)){
			redirect('login');
		}
	}
	
	### TELAAH
	
	//View All Data
	public function index()
	{
		
		$config = array ();
		$config ["base_url"] = base_url () . "telaah/disposisi/index/".$this->uri->segment(4);
		
		switch($this->uri->segment(4)){
			case "kabid" 			: $sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
									  if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 2){
											$config ["total_rows"] = $this->m_telaah->kabid_dprd('','');
									  }else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 3){
											$count_data = $this->m_telaah->kabid_sekda('','',$sekda[0]['bagian_id']);
											if($this->ion_auth->user()->row()->id == 638) {
												$config ["total_rows"] = $count_data[0]['numrows']+$count_data[1]['numrows'];
											} else {
												$config ["total_rows"] = $this->m_telaah->kabid_sekda('','',$sekda[0]['bagian_id']);
											}
									  }else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 10){
											$config ["total_rows"] = $this->m_telaah->kabid_dinkes('','',$this->ion_auth->user()->row()->skpd_id);
									  }else{	
											$config ["total_rows"] = $this->m_telaah->kabid_opd('','',$this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "sekdis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$config ["total_rows"] = $this->m_telaah->sekdis_dinkes('','',$this->ion_auth->user()->row()->skpd_id);
									  } else {
											$config ["total_rows"] = $this->m_telaah->sekdis('','',$this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "kadis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$config ["total_rows"] = $this->m_telaah->kadis_dinkes('','',$this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$config ["total_rows"] = $this->m_telaah->kadis('','',$this->ion_auth->user()->row()->skpd_id);
									  }
									//   $this->data['total_anggaran_keseluruhan'] = $this->m_kadis->total_anggaran_keseluruhan($this->ion_auth->user()->row()->skpd_id);
									//   $this->data['sisa_anggaran'] = $this->m_dprd->cek_sisa_anggaran_skpd($this->ion_auth->user()->row()->skpd_id);
									$this->data['anggaran'] = $this->m_beranda->anggaran('','',$this->ion_auth->user()->row()->skpd_id);
									$sum_all_rincian_skpd = $this->m_beranda->sum_all_rincian_skpd();
									$sum_all_pengeluaran_rill_skpd = $this->m_beranda->sum_all_pengeluaran_rill_skpd();
									$this->data['sum_all_rincian_skpd'] = $sum_all_rincian_skpd +  $sum_all_pengeluaran_rill_skpd;
									$this->data['sum_all_anggaran_skpd'] = $this->m_beranda->sum_all_anggaran_skpd();
									$this->data['anggaran_terpakai'] = ($this->data['sum_all_rincian_skpd']/$this->data['sum_all_anggaran_skpd'])*100;
									$this->data['anggaran_tersedia'] = (($this->data['sum_all_anggaran_skpd']-$this->data['sum_all_rincian_skpd'])/$this->data['sum_all_anggaran_skpd'])*100;
									  break;
			case "sekda" 			: $count_data = $this->m_telaah->sekda('','');
									  $config ["total_rows"] = $count_data[0]['numrows']+$count_data[1]['numrows'];
									  break;
			case "asisten" 			: $sekda = $this->m_relasi_sekda->getasisten($this->ion_auth->user()->row()->id);
									  $config ["total_rows"] = $this->m_telaah->asisten('','',$sekda[0]['asisten_id']);
									  break;
			case "kadprd" 			: $config ["total_rows"] = $this->m_telaah->kadprd('','');
									  break;
			case "kapus" 			: $config ["total_rows"] = $this->m_telaah->kapus('','',$this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekwan" 			: $config ["total_rows"] = $this->m_telaah->sekwan('','');
									  break;
			case "walikota" 		: $count_data = $this->m_telaah->walikota('','');
									  $config ["total_rows"] = $count_data[0]['numrows']+$count_data[1]['numrows'];
									  break;
			case "walikota2" 		: $config ["total_rows"] = $this->m_telaah->datawalikota('','');
									  break;
			case "kasubag_lurah" 	: $config ["total_rows"] = $this->m_telaah->kasubag_lurah('','',$this->ion_auth->user()->row()->skpd_id);
									  break;
			case "kasubag_camat" 	: $config ["total_rows"] = $this->m_telaah->kasubag_lurah('','',$this->ion_auth->user()->row()->skpd_id);
									  break;
			case "lurah" 			: $config ["total_rows"] = $this->m_telaah->kasubag_lurah('','',$this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekcam" 			: $config ["total_rows"] = $this->m_telaah->sekcam('','',$this->ion_auth->user()->row()->skpd_id);
									  break;
			case "camat" 			: $config ["total_rows"] = $this->m_telaah->camat('','',$this->ion_auth->user()->row()->skpd_id);
									  break;
		}
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
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 5 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 5 )) ? $this->uri->segment ( 5 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		
		switch($this->uri->segment(4)){
			case "kabid" 			: if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 2){
											$this->data['telaah_staf'] = $this->m_telaah->kabid_dprd($config ["per_page"], $page);
									  } else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 3){
											$this->data['telaah_staf'] = $this->m_telaah->kabid_sekda($config ["per_page"], $page, $sekda[0]['bagian_id']);
									  } else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 10){
											$this->data['telaah_staf'] = $this->m_telaah->kabid_dinkes($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  } else{	
											$this->data['telaah_staf'] = $this->m_telaah->kabid_opd($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "sekdis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$this->data['telaah_staf'] = $this->m_telaah->sekdis_dinkes($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  } else {
											$this->data['telaah_staf'] = $this->m_telaah->sekdis($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "kadis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$this->data['telaah_staf'] = $this->m_telaah->kadis_dinkes($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$this->data['telaah_staf'] = $this->m_telaah->kadis($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  }
									  $this->data['total_anggaran_keseluruhan'] = $this->m_kadis->total_anggaran_keseluruhan($this->ion_auth->user()->row()->skpd_id);
									  $this->data['sisa_anggaran'] = $this->m_dprd->cek_sisa_anggaran_skpd($this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekda" 			: $this->data['telaah_staf'] = $this->m_telaah->sekda($config ["per_page"], $page);
									  break;
			case "asisten" 			: $sekda = $this->m_relasi_sekda->getasisten($this->ion_auth->user()->row()->id);
									  $this->data['telaah_staf'] = $this->m_telaah->asisten($config ["per_page"], $page, $sekda[0]['asisten_id']);
									  break;
			case "kadprd" 			: $this->data['telaah_staf'] = $this->m_telaah->kadprd($config ["per_page"], $page);
									  break;
			case "kapus" 			: $this->data['telaah_staf'] = $this->m_telaah->kapus($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekwan" 			: $this->data['telaah_staf'] = $this->m_telaah->sekwan($config ["per_page"], $page);
									  break;
			case "walikota" 		: $this->data['telaah_staf'] = $this->m_telaah->walikota($config ["per_page"], $page);
										## Total Anggaran
										$this->data['total_anggaran_keseluruhan'] = $this->m_walikota->total_anggaran_keseluruhan();
										$this->data['total_anggaran_dalam_daerah'] = $this->m_walikota->total_anggaran_dalam_daerah();
										$this->data['total_anggaran_luar_daerah'] = $this->m_walikota->total_anggaran_luar_daerah();
										$this->data['total_anggaran_bimtek'] = $this->m_walikota->total_anggaran_bimtek();
										
										$rincian_belanja_skpd = $this->m_walikota->rincian_belanja();
										$pengeluaran_rill_skpd = $this->m_walikota->pengeluaran_rill();
										$this->data['anggaran_terpakai'] = $rincian_belanja_skpd[0]['jumlah'] + $pengeluaran_rill_skpd[0]['jumlah'];
										
										## Anggaran Dalam Daerah
										$rincian_belanja_dalam_daerah = $this->m_walikota->rincian_belanja_dalam_daerah('');
										$pengeluaran_rill_dalam_daerah = $this->m_walikota->pengeluaran_rill_dalam_daerah('');
										$this->data['realisasi_anggaran_dalam_daerah'] = $rincian_belanja_dalam_daerah[0]['jumlah'] + $pengeluaran_rill_dalam_daerah[0]['jumlah'];
										
										## Anggaran Luar Daerah
										$rincian_belanja_luar_daerah = $this->m_walikota->rincian_belanja_luar_daerah('');
										$pengeluaran_rill_luar_daerah = $this->m_walikota->pengeluaran_rill_luar_daerah('');
										$this->data['realisasi_anggaran_luar_daerah'] = $rincian_belanja_luar_daerah[0]['jumlah'] + $pengeluaran_rill_luar_daerah[0]['jumlah'];
										
										## Anggaran Bimtek
										$rincian_belanja_bimtek = $this->m_walikota->rincian_belanja_bimtek('');
										$pengeluaran_rill_bimtek = $this->m_walikota->pengeluaran_rill_bimtek('');
										$this->data['realisasi_anggaran_bimtek'] = $rincian_belanja_bimtek[0]['jumlah'] + $pengeluaran_rill_bimtek[0]['jumlah'];
									  break;
			case "walikota2" 		: $this->data['telaah_staf'] = $this->m_telaah->datawalikota($config ["per_page"], $page);
									  break;
			case "kasubag_lurah" 	: $this->data['telaah_staf'] = $this->m_telaah->kasubag_lurah($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "kasubag_camat" 	: $this->data['telaah_staf'] = $this->m_telaah->kasubag_lurah($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "lurah" 			: $this->data['telaah_staf'] = $this->m_telaah->kasubag_lurah($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekcam" 			: $this->data['telaah_staf'] = $this->m_telaah->sekcam($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "camat" 			: $this->data['telaah_staf'] = $this->m_telaah->camat($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
		}
		
		if($this->uri->segment(4)=="walikota2"){
			$this->render('telaah/disposisi/telaah_walikota');
		} else {	
			$this->render('telaah/disposisi/telaah_masuk');
		}
	}
	
	public function data(){
		
		$config = array ();
		$config ["base_url"] = base_url () . "telaah/disposisi/data/".$this->uri->segment(4);
		
		switch($this->uri->segment(4)){
			case "1" 	: $this->data['skpd'] = $this->m_widget->getSKPD();
						  $config ["total_rows"] = $this->m_admin->record_count_esselon(); break;
			case "2" 	: $this->data['skpd'] = $this->m_walikota->getSKPD();
						  $config ["total_rows"] = $this->m_admin->record_count_kadis(); break;
			case "3" 	: $config ["total_rows"] = $this->m_admin->record_count_dprd(); break;
			case "4" 	: $config ["total_rows"] = $this->m_admin->record_count_sekda(); break;
			case "5" 	: $this->data['skpd'] = $this->m_walikota->getSKPD_Camat();
						  $config ["total_rows"] = $this->m_walikota->record_count_camat(); break;
			case "6" 	: $config ["total_rows"] = $this->m_admin->record_count_staffdprd(); break;
			case "7" 	: $this->data['skpd'] = $this->m_walikota->getSKPD_Camat();
						  $config ["total_rows"] = $this->m_walikota->record_count_staffcamat(); break;
			case "8" 	: $config ["total_rows"] = $this->m_admin->record_countwalikota(); break;
			case "9" 	: $config ["total_rows"] = $this->m_admin->record_count_staffsekda(); break;
			case "10" 	: $config ["total_rows"] = $this->m_admin->record_count_sekwan(); break;
			case "11" 	: $this->data['skpd'] = $this->m_walikota->getSKPD_puskesmas();
						  $config ["total_rows"] = $this->m_admin->record_count_kapus(); break;
		}
		
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
			if($this->uri->segment(4)==8){
				$this->data ['number'] = $config ["total_rows"];
			} else {
				$this->data ['number'] = 0;
			}
		}else {
			$this->data ['number'] = $this->uri->segment ( 5 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 5 )) ? $this->uri->segment ( 5 ) : 0;
		$this->data['links'] = $this->pagination->create_links ();
		
		switch($this->uri->segment(4)){
			case "1" 	: $this->data['data'] = $this->m_admin->data_esselon($config ["per_page"], $page); break;
			case "2" 	: $this->data['data'] = $this->m_admin->data_kadis($config ["per_page"], $page); break;
			case "3" 	: $this->data['data'] = $this->m_admin->data_dprd($config ["per_page"], $page); break;
			case "4" 	: $this->data['data'] = $this->m_admin->data_sekda($config ["per_page"], $page); break;
			case "5" 	: $this->data['data'] = $this->m_walikota->data_camat($config ["per_page"], $page); break;
			case "6" 	: $this->data['data'] = $this->m_admin->data_staffdprd($config ["per_page"], $page); break;
			case "7" 	: $this->data['data'] = $this->m_walikota->data_staffcamat($config ["per_page"], $page); break;
			case "8" 	: 
						  $this->data['data'] = $this->m_admin->datawalikota2($config ["per_page"], $page); 
						  $this->data['total_anggaran_keseluruhan'] = $this->m_walikota->total_anggaran_walikota();
						  $this->data['sisa_anggaran'] = $this->m_walikota->cek_sisa_anggaran_walikota(3);
						  break;
			case "9" 	: $this->data['data'] = $this->m_admin->data_staffsekda($config ["per_page"], $page); break;
			case "10" 	: $this->data['data'] = $this->m_admin->data_sekwan($config ["per_page"], $page); break;
			case "11" 	: $this->data['data'] = $this->m_admin->data_kapus($config ["per_page"], $page); break;
		}
		
		$this->data['jumlah_data'] = $config ["total_rows"];
		$this->render('telaah/disposisi/walikota/content'); 
	}
	
	//View Data Search
	public function search()
	{
		if($this->input->post('submit')){
			if ($this->ion_auth->user()->row()->jenis_skpd == 2){
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
			$query = $this->uri->segment ( 5 );
			$column = $this->uri->segment ( 6 );
		}
		
		$config = array ();
		$config ["base_url"] = base_url () . "telaah/disposisi/search/".$this->uri->segment(4)."/".$query."/".$column;
		
		switch($this->uri->segment(4)){
			case "kabid" 			: $sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
									  if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 2){
											$config ["total_rows"] = $this->m_telaah->kabid_dprd_search($column,$query,'','');
									  } else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 3){
											$count_data = $this->m_telaah->kabid_sekda_search($column,$query,'','',$sekda[0]['bagian_id']);
											if($this->ion_auth->user()->row()->id == 638) {
												$config ["total_rows"] = $count_data[0]['numrows']+$count_data[1]['numrows'];
											} else {
												$config ["total_rows"] = $this->m_telaah->kabid_sekda_search($column,$query,'','',$sekda[0]['bagian_id']);
											}
									  } else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 10){
											$config ["total_rows"] = $this->m_telaah->kabid_dinkes_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$config ["total_rows"] = $this->m_telaah->kabid_opd_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "sekdis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$config ["total_rows"] = $this->m_telaah->sekdis_dinkes_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$config ["total_rows"] = $this->m_telaah->sekdis_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "kadis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$config ["total_rows"] = $this->m_telaah->kadis_dinkes_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$config ["total_rows"] = $this->m_telaah->kadis_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "sekda" 			: $count_data = $this->m_telaah->sekda_search($column,$query,'','');
									  $config ["total_rows"] = $count_data[0]['numrows']+$count_data[1]['numrows'];
									  break;
			case "asisten" 			: $sekda = $this->m_relasi_sekda->getasisten($this->ion_auth->user()->row()->id);
									  $config ["total_rows"] = $this->m_telaah->asisten_search($column,$query,'','', $sekda[0]['asisten_id']);
									  break;
			case "kadprd" 			: $config ["total_rows"] = $this->m_telaah->kadprd_search($column,$query,'','');
									  break;
			case "kapus" 			: $config ["total_rows"] = $this->m_telaah->kapus_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekwan" 			: $config ["total_rows"] = $this->m_telaah->sekwan_search($column,$query,'','');
									  break;
			case "walikota" 		: $count_data = $this->m_telaah->walikota_search($column,$query,'','');
									  $config ["total_rows"] = $count_data[0]['numrows']+$count_data[1]['numrows'];
									  break;
			case "kasubag_lurah" 	: $config ["total_rows"] = $this->m_telaah->kasubag_lurah_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "kasubag_camat" 	: $config ["total_rows"] = $this->m_telaah->kasubag_lurah_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "lurah" 			: $config ["total_rows"] = $this->m_telaah->kasubag_lurah_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekcam" 			: $config ["total_rows"] = $this->m_telaah->sekcam_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "camat" 			: $config ["total_rows"] = $this->m_telaah->camat_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  break;
		}
		$config ["per_page"] = 10;
		$config ["uri_segment"] = 7;
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
		
		if ($this->uri->segment ( 7 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 7 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 7 )) ? $this->uri->segment ( 7 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		
		switch($this->uri->segment(4)){
			case "kabid" 			: $sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
									  if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 2){
											$this->data['telaah_staf'] = $this->m_telaah->kabid_dprd_search($column,$query,$config ["per_page"], $page);
									  } else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 3){
											$this->data['telaah_staf'] = $this->m_telaah->kabid_sekda_search($column,$query,$config ["per_page"], $page, $sekda[0]['bagian_id']);
									  } else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 10){
											$this->data['telaah_staf'] = $this->m_telaah->kabid_dinkes_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$this->data['telaah_staf'] = $this->m_telaah->kabid_opd_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "sekdis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$this->data['telaah_staf'] = $this->m_telaah->sekdis_dinkes_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$this->data['telaah_staf'] = $this->m_telaah->sekdis_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "kadis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$this->data['telaah_staf'] = $this->m_telaah->kadis_dinkes_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$this->data['telaah_staf'] = $this->m_telaah->kadis_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "sekda" 			: $this->data['telaah_staf'] = $this->m_telaah->sekda_search($column,$query,$config ["per_page"], $page);
									  break;
			case "asisten" 			: $sekda = $this->m_relasi_sekda->getasisten($this->ion_auth->user()->row()->id);
									  $this->data['telaah_staf'] = $this->m_telaah->asisten_search($column,$query,$config ["per_page"], $page, $sekda[0]['asisten_id']);
									  break;
			case "kadprd" 			: $this->data['telaah_staf'] = $this->m_telaah->kadprd_search($column,$query,$config ["per_page"], $page);
									  break;
			case "kapus" 			: $this->data['telaah_staf'] = $this->m_telaah->kapus_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekwan" 			: $this->data['telaah_staf'] = $this->m_telaah->sekwan_search($column,$query,$config ["per_page"], $page);
									  break;
			case "walikota" 		: $this->data['telaah_staf'] = $this->m_telaah->walikota_search($column,$query,$config ["per_page"], $page);
									  break;
			case "kasubag_lurah" 	: $this->data['telaah_staf'] = $this->m_telaah->kasubag_lurah_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "kasubag_camat" 	: $this->data['telaah_staf'] = $this->m_telaah->kasubag_lurah_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "lurah" 			: $this->data['telaah_staf'] = $this->m_telaah->kasubag_lurah_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekcam" 			: $this->data['telaah_staf'] = $this->m_telaah->sekcam_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "camat" 			: $this->data['telaah_staf'] = $this->m_telaah->camat_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
		}
		
		if($this->uri->segment(4)=="walikota2"){
			$this->render('telaah/disposisi/telaah_walikota');
		} else {	
			$this->render('telaah/disposisi/telaah_masuk');
		}
		
	}
	
	//View All Data Di Setujui
	public function telaah_disetujui()
	{
		
		$config = array ();
		$config ["base_url"] = base_url () . "telaah/disposisi/telaah_disetujui/".$this->uri->segment(4);
		
		switch($this->uri->segment(4)){
			case "kabid" 			: $sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
									  if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->skpd_id == 2){
											$config ["total_rows"] = $this->m_telaah_disetujui->kabid_dprd('','');
									  } else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->skpd_id == 3){
											$count_data = $this->m_telaah_disetujui->kabid_sekda('','',$sekda[0]['bagian_id']);
											if($this->ion_auth->user()->row()->id == 638) {
												$config ["total_rows"] = $count_data[0]['numrows']+$count_data[1]['numrows'];
											} else {
												$config ["total_rows"] = $this->m_telaah_disetujui->kabid_sekda('','',$sekda[0]['bagian_id']);
											}
									  } else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 10){
											$config ["total_rows"] = $this->m_telaah_disetujui->kabid_dinkes('','',$this->ion_auth->user()->row()->skpd_id);
									  } else{	
											$config ["total_rows"] = $this->m_telaah_disetujui->kabid_opd('','',$this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "sekdis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$config ["total_rows"] = $this->m_telaah_disetujui->sekdis_dinkes('','',$this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$config ["total_rows"] = $this->m_telaah_disetujui->sekdis('','',$this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "kadis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$config ["total_rows"] = $this->m_telaah_disetujui->kadis_dinkes('','',$this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$config ["total_rows"] = $this->m_telaah_disetujui->kadis('','',$this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "sekda" 			: $count_data = $this->m_telaah_disetujui->sekda('','');
									  $config ["total_rows"] = $count_data[0]['numrows']+$count_data[1]['numrows'];
									  break;
			case "asisten" 			: $sekda = $this->m_relasi_sekda->getasisten($this->ion_auth->user()->row()->id);
									  $config ["total_rows"] = $this->m_telaah_disetujui->asisten('','',$sekda[0]['asisten_id']);
									  break;
			case "kadprd" 			: $config ["total_rows"] = $this->m_telaah_disetujui->kadprd('','');
									  break;
			case "kapus" 			: $config ["total_rows"] = $this->m_telaah_disetujui->kapus('','',$this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekwan" 			: $config ["total_rows"] = $this->m_telaah_disetujui->sekwan('','');
									  break;
			case "walikota" 		: $count_data = $this->m_telaah_disetujui->walikota('','');
									  $config ["total_rows"] = $count_data[0]['numrows']+$count_data[1]['numrows'];
									  break;
			case "kasubag_lurah" 	: $config ["total_rows"] = $this->m_telaah_disetujui->kasubag_lurah('','',$this->ion_auth->user()->row()->skpd_id);
									  break;
			case "kasubag_camat" 	: $config ["total_rows"] = $this->m_telaah_disetujui->kasubag_lurah('','',$this->ion_auth->user()->row()->skpd_id);
									  break;
			case "lurah" 			: $config ["total_rows"] = $this->m_telaah_disetujui->kasubag_lurah('','',$this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekcam" 			: $config ["total_rows"] = $this->m_telaah_disetujui->sekcam('','',$this->ion_auth->user()->row()->skpd_id);
									  break;
			case "camat" 			: $config ["total_rows"] = $this->m_telaah_disetujui->camat('','',$this->ion_auth->user()->row()->skpd_id);
									  break;
		}
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
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 5 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 5 )) ? $this->uri->segment ( 5 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		
		switch($this->uri->segment(4)){
			case "kabid" 			: $sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
									  if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->skpd_id == 2){
										$this->data['telaah_disetujui'] = $this->m_telaah_disetujui->kabid_dprd($config ["per_page"], $page);
									  } else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->skpd_id == 3){
										$this->data['telaah_disetujui'] = $this->m_telaah_disetujui->kabid_sekda($config ["per_page"], $page, $sekda[0]['bagian_id']);
									  } else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 10){
									 	$this->data['telaah_disetujui'] = $this->m_telaah_disetujui->kabid_dinkes($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  } else{		
										$this->data['telaah_disetujui'] = $this->m_telaah_disetujui->kabid_opd($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "sekdis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$this->data['telaah_disetujui'] = $this->m_telaah_disetujui->sekdis_dinkes($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$this->data['telaah_disetujui'] = $this->m_telaah_disetujui->sekdis($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "kadis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$this->data['telaah_disetujui'] = $this->m_telaah_disetujui->kadis_dinkes($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$this->data['telaah_disetujui'] = $this->m_telaah_disetujui->kadis($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "sekda" 			: $this->data['telaah_disetujui'] = $this->m_telaah_disetujui->sekda($config ["per_page"], $page);
									  break;
			case "asisten" 			: $sekda = $this->m_relasi_sekda->getasisten($this->ion_auth->user()->row()->id);
									  $this->data['telaah_disetujui'] = $this->m_telaah_disetujui->asisten($config ["per_page"], $page, $sekda[0]['asisten_id']);
									  break;
			case "kadprd" 			: $this->data['telaah_disetujui'] = $this->m_telaah_disetujui->kadprd($config ["per_page"], $page);
									  break;
			case "kapus" 			: $this->data['telaah_disetujui'] = $this->m_telaah_disetujui->kapus($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekwan" 			: $this->data['telaah_disetujui'] = $this->m_telaah_disetujui->sekwan($config ["per_page"], $page);
									  break;
			case "walikota" 		: $this->data['telaah_disetujui'] = $this->m_telaah_disetujui->walikota($config ["per_page"], $page);
									  break;
			case "kasubag_lurah" 	: $this->data['telaah_disetujui'] = $this->m_telaah_disetujui->kasubag_lurah($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "kasubag_camat" 	: $this->data['telaah_disetujui'] = $this->m_telaah_disetujui->kasubag_lurah($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "lurah" 			: $this->data['telaah_disetujui'] = $this->m_telaah_disetujui->kasubag_lurah($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekcam" 			: $this->data['telaah_disetujui'] = $this->m_telaah_disetujui->sekcam($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "camat" 			: $this->data['telaah_disetujui'] = $this->m_telaah_disetujui->camat($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
		}
		
		$this->render('telaah/disposisi/telaah_disetujui');
	}
	
	//View Data Search Di Setujui
	public function search_telaah_disetujui()
	{
		if($this->input->post('submit')){
			if ($this->ion_auth->user()->row()->jenis_skpd == 2){
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
			$query = $this->uri->segment ( 5 );
			$column = $this->uri->segment ( 6 );
		}
		
		$config = array ();
		$config ["base_url"] = base_url () . "telaah/disposisi/search_telaah_disetujui/".$this->uri->segment(4)."/".$query."/".$column;
		
		switch($this->uri->segment(4)){
			case "kabid" 			: $sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
									  if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 2){
											$config ["total_rows"] = $this->m_telaah_disetujui->kabid_dprd_search($column,$query,'','');
									  } else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 3){
											$count_data = $this->m_telaah_disetujui->kabid_sekda_search($column,$query,'','',$sekda[0]['bagian_id']);
											if($this->ion_auth->user()->row()->id == 638) {
												$config ["total_rows"] = $count_data[0]['numrows']+$count_data[1]['numrows'];
											} else {
												$config ["total_rows"] = $this->m_telaah_disetujui->kabid_sekda_search($column,$query,'','',$sekda[0]['bagian_id']);
											}
									  } else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 10){
											$config ["total_rows"] = $this->m_telaah_disetujui->kabid_dinkes_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$config ["total_rows"] = $this->m_telaah_disetujui->kabid_opd_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "sekdis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$config ["total_rows"] = $this->m_telaah_disetujui->sekdis_dinkes_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$config ["total_rows"] = $this->m_telaah_disetujui->sekdis_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "kadis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$config ["total_rows"] = $this->m_telaah_disetujui->kadis_dinkes_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$config ["total_rows"] = $this->m_telaah_disetujui->kadis_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "sekda" 			: $count_data = $this->m_telaah_disetujui->sekda_search($column,$query,'','');
									  $config ["total_rows"] = $count_data[0]['numrows']+$count_data[1]['numrows'];
									  break;
			case "asisten" 			: $sekda = $this->m_relasi_sekda->getasisten($this->ion_auth->user()->row()->id);
									  $config ["total_rows"] = $this->m_telaah_disetujui->asisten_search($column,$query,'','', $sekda[0]['asisten_id']);
									  break;
			case "kadprd" 			: $config ["total_rows"] = $this->m_telaah_disetujui->kadprd_search($column,$query,'','');
									  break;
			case "kapus" 			: $config ["total_rows"] = $this->m_telaah_disetujui->kapus_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekwan" 			: $config ["total_rows"] = $this->m_telaah_disetujui->sekwan_search($column,$query,'','');
									  break;
			case "walikota" 		: $count_data = $this->m_telaah_disetujui->walikota_search($column,$query,'','');
									  $config ["total_rows"] = $count_data[0]['numrows']+$count_data[1]['numrows'];
									  break;
			case "kasubag_lurah" 	: $config ["total_rows"] = $this->m_telaah_disetujui->kasubag_lurah_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "kasubag_camat" 	: $config ["total_rows"] = $this->m_telaah_disetujui->kasubag_lurah_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "lurah" 			: $config ["total_rows"] = $this->m_telaah_disetujui->kasubag_lurah_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekcam" 			: $config ["total_rows"] = $this->m_telaah_disetujui->sekcam_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "camat" 			: $config ["total_rows"] = $this->m_telaah_disetujui->camat_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  break;
		}
		$config ["per_page"] = 25;
		$config ["uri_segment"] = 7;
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
		
		if ($this->uri->segment ( 7 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 7 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 7 )) ? $this->uri->segment ( 7 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		switch($this->uri->segment(4)){
			case "kabid" 			: $sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
									  if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 2){
											$this->data['telaah_disetujui'] = $this->m_telaah_disetujui->kabid_dprd_search($column,$query,$config ["per_page"], $page);
									  } else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 3){
											$this->data['telaah_disetujui'] = $this->m_telaah_disetujui->kabid_sekda_search($column,$query,$config ["per_page"], $page, $sekda[0]['bagian_id']);
									  } else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 10){
											$this->data['telaah_disetujui'] = $this->m_telaah_disetujui->kabid_dinkes_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$this->data['telaah_disetujui'] = $this->m_telaah_disetujui->kabid_opd_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "sekdis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$this->data['telaah_disetujui'] = $this->m_telaah_disetujui->sekdis_dinkes_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$this->data['telaah_disetujui'] = $this->m_telaah_disetujui->sekdis_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "kadis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$this->data['telaah_disetujui'] = $this->m_telaah_disetujui->kadis_dinkes_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$this->data['telaah_disetujui'] = $this->m_telaah_disetujui->kadis_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "sekda" 			: $this->data['telaah_disetujui'] = $this->m_telaah_disetujui->sekda_search($column,$query,$config ["per_page"], $page);
									  break;
			case "asisten" 			: $sekda = $this->m_relasi_sekda->getasisten($this->ion_auth->user()->row()->id);
									  $this->data['telaah_disetujui'] = $this->m_telaah_disetujui->asisten_search($column,$query,$config ["per_page"], $page, $sekda[0]['asisten_id']);
									  break;
			case "kadprd" 			: $this->data['telaah_disetujui'] = $this->m_telaah_disetujui->kadprd_search($column,$query,$config ["per_page"], $page);
									  break;
			case "kapus" 			: $this->data['telaah_disetujui'] = $this->m_telaah_disetujui->kapus_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekwan" 			: $this->data['telaah_disetujui'] = $this->m_telaah_disetujui->sekwan_search($column,$query,$config ["per_page"], $page);
									  break;
			case "walikota" 		: $this->data['telaah_disetujui'] = $this->m_telaah_disetujui->walikota_search($column,$query,$config ["per_page"], $page);
									  break;
			case "kasubag_lurah" 	: $this->data['telaah_disetujui'] = $this->m_telaah_disetujui->kasubag_lurah_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "kasubag_camat" 	: $this->data['telaah_disetujui'] = $this->m_telaah_disetujui->kasubag_lurah_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "lurah" 			: $this->data['telaah_disetujui'] = $this->m_telaah_disetujui->kasubag_lurah_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekcam" 			: $this->data['telaah_disetujui'] = $this->m_telaah_disetujui->sekcam_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "camat" 			: $this->data['telaah_disetujui'] = $this->m_telaah_disetujui->camat_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
		}
		
		$this->render('telaah/disposisi/telaah_disetujui');
	}
	
	//View All Data Di Tolak
	public function telaah_ditolak()
	{
		
		$config = array ();
		$config ["base_url"] = base_url () . "telaah/disposisi/telaah_ditolak/".$this->uri->segment(4);
		
		switch($this->uri->segment(4)){
			case "kabid" 			: $sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
									  if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->skpd_id == 2){
											$config ["total_rows"] = $this->m_telaah_ditolak->kabid_dprd('','');
									  } else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->skpd_id == 3){
											$count_data = $this->m_telaah_ditolak->kabid_sekda('','',$sekda[0]['bagian_id']);
											if($this->ion_auth->user()->row()->id == 638) {
												$config ["total_rows"] = $count_data[0]['numrows']+$count_data[1]['numrows'];
											} else {
												$config ["total_rows"] = $this->m_telaah_ditolak->kabid_sekda('','',$sekda[0]['bagian_id']);
											}
									  } else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 10){
											$config ["total_rows"] = $this->m_telaah_ditolak->kabid_dinkes('','',$this->ion_auth->user()->row()->skpd_id);
									  } else{	
											$config ["total_rows"] = $this->m_telaah_ditolak->kabid_opd('','',$this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "sekdis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$config ["total_rows"] = $this->m_telaah_ditolak->sekdis_dinkes('','',$this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$config ["total_rows"] = $this->m_telaah_ditolak->sekdis('','',$this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "kadis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$config ["total_rows"] = $this->m_telaah_ditolak->kadis_dinkes('','',$this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$config ["total_rows"] = $this->m_telaah_ditolak->kadis('','',$this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "sekda" 			: $count_data = $this->m_telaah_ditolak->sekda('','');
									  $config ["total_rows"] = $count_data[0]['numrows']+$count_data[1]['numrows'];
									  break;
			case "asisten" 			: $sekda = $this->m_relasi_sekda->getasisten($this->ion_auth->user()->row()->id);
									  $config ["total_rows"] = $this->m_telaah_ditolak->asisten('','',$sekda[0]['asisten_id']);
									  break;
			case "kadprd" 			: $config ["total_rows"] = $this->m_telaah_ditolak->kadprd('','');
									  break;
			case "kapus" 			: $config ["total_rows"] = $this->m_telaah_ditolak->kapus('','',$this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekwan" 			: $config ["total_rows"] = $this->m_telaah_ditolak->sekwan('','');
									  break;
			case "walikota" 		: $count_data = $this->m_telaah_ditolak->walikota('','');
									  $config ["total_rows"] = $count_data[0]['numrows']+$count_data[1]['numrows'];
									  break;
			case "kasubag_lurah" 	: $config ["total_rows"] = $this->m_telaah_ditolak->kasubag_lurah('','',$this->ion_auth->user()->row()->skpd_id);
									  break;
			case "kasubag_camat" 	: $config ["total_rows"] = $this->m_telaah_ditolak->kasubag_lurah('','',$this->ion_auth->user()->row()->skpd_id);
									  break;
			case "lurah" 			: $config ["total_rows"] = $this->m_telaah_ditolak->kasubag_lurah('','',$this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekcam" 			: $config ["total_rows"] = $this->m_telaah_ditolak->sekcam('','',$this->ion_auth->user()->row()->skpd_id);
									  break;
			case "camat" 			: $config ["total_rows"] = $this->m_telaah_ditolak->camat('','',$this->ion_auth->user()->row()->skpd_id);
									  break;
		}
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
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 5 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 5 )) ? $this->uri->segment ( 5 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		
		switch($this->uri->segment(4)){
			case "kabid" 			: $sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
									  if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->skpd_id == 2){
										$this->data['telaah_ditolak'] = $this->m_telaah_ditolak->kabid_dprd($config ["per_page"], $page);
									  } else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->skpd_id == 3){
										$this->data['telaah_ditolak'] = $this->m_telaah_ditolak->kabid_sekda($config ["per_page"], $page, $sekda[0]['bagian_id']);
									  } else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 10){
									 	$this->data['telaah_ditolak'] = $this->m_telaah_ditolak->kabid_dinkes($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  } else{		
										$this->data['telaah_ditolak'] = $this->m_telaah_ditolak->kabid_opd($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "sekdis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$this->data['telaah_ditolak'] = $this->m_telaah_ditolak->sekdis_dinkes($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$this->data['telaah_ditolak'] = $this->m_telaah_ditolak->sekdis($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "kadis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$this->data['telaah_ditolak'] = $this->m_telaah_ditolak->kadis_dinkes($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$this->data['telaah_ditolak'] = $this->m_telaah_ditolak->kadis($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "sekda" 			: $this->data['telaah_ditolak'] = $this->m_telaah_ditolak->sekda($config ["per_page"], $page);
									  break;
			case "asisten" 			: $sekda = $this->m_relasi_sekda->getasisten($this->ion_auth->user()->row()->id);
									  $this->data['telaah_ditolak'] = $this->m_telaah_ditolak->asisten($config ["per_page"], $page, $sekda[0]['asisten_id']);
									  break;
			case "kadprd" 			: $this->data['telaah_ditolak'] = $this->m_telaah_ditolak->kadprd($config ["per_page"], $page);
									  break;
			case "kapus" 			: $this->data['telaah_ditolak'] = $this->m_telaah_ditolak->kapus($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekwan" 			: $this->data['telaah_ditolak'] = $this->m_telaah_ditolak->sekwan($config ["per_page"], $page);
									  break;
			case "walikota" 		: $this->data['telaah_ditolak'] = $this->m_telaah_ditolak->walikota($config ["per_page"], $page);
									  break;
			case "kasubag_lurah" 	: $this->data['telaah_ditolak'] = $this->m_telaah_ditolak->kasubag_lurah($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "kasubag_camat" 	: $this->data['telaah_ditolak'] = $this->m_telaah_ditolak->kasubag_lurah($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "lurah" 			: $this->data['telaah_ditolak'] = $this->m_telaah_ditolak->kasubag_lurah($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekcam" 			: $this->data['telaah_ditolak'] = $this->m_telaah_ditolak->sekcam($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "camat" 			: $this->data['telaah_ditolak'] = $this->m_telaah_ditolak->camat($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
		}
		
		$this->render('telaah/disposisi/telaah_ditolak');
	}
	
	//View Data Search Di Tolak
	public function search_telaah_ditolak()
	{
		if($this->input->post('submit')){
			if ($this->ion_auth->user()->row()->jenis_skpd == 2){
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
			$query = $this->uri->segment ( 4 );
			$column = $this->uri->segment ( 5 );
		}
		
		$config = array ();
		$config ["base_url"] = base_url () . "telaah/disposisi/search_telaah_ditolak/".$this->uri->segment(4)."/".$query."/".$column;
		
		switch($this->uri->segment(4)){
			case "kabid" 			: $sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
									  if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 2){
											$config ["total_rows"] = $this->m_telaah_ditolak->kabid_dprd_search($column,$query,'','');
									  } else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 3){
											$count_data = $this->m_telaah_ditolak->kabid_sekda_search($column,$query,'','',$sekda[0]['bagian_id']);
											if($this->ion_auth->user()->row()->id == 638) {
												$config ["total_rows"] = $count_data[0]['numrows']+$count_data[1]['numrows'];
											} else {
												$config ["total_rows"] = $this->m_telaah_ditolak->kabid_sekda_search($column,$query,'','',$sekda[0]['bagian_id']);
											}
									  } else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 10){
											$config ["total_rows"] = $this->m_telaah_ditolak->kabid_dinkes_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$config ["total_rows"] = $this->m_telaah_ditolak->kabid_opd_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "sekdis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$config ["total_rows"] = $this->m_telaah_ditolak->sekdis_dinkes_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$config ["total_rows"] = $this->m_telaah_ditolak->sekdis_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "kadis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$config ["total_rows"] = $this->m_telaah_ditolak->kadis_dinkes_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$config ["total_rows"] = $this->m_telaah_ditolak->kadis_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "sekda" 			: $count_data = $this->m_telaah_ditolak->sekda_search($column,$query,'','');
									  $config ["total_rows"] = $count_data[0]['numrows']+$count_data[1]['numrows'];
									  break;
			case "asisten" 			: $sekda = $this->m_relasi_sekda->getasisten($this->ion_auth->user()->row()->id);
									  $config ["total_rows"] = $this->m_telaah_ditolak->asisten_search($column,$query,'','', $sekda[0]['asisten_id']);
									  break;
			case "kadprd" 			: $config ["total_rows"] = $this->m_telaah_ditolak->kadprd_search($column,$query,'','');
									  break;
			case "kapus" 			: $config ["total_rows"] = $this->m_telaah_ditolak->kapus_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekwan" 			: $config ["total_rows"] = $this->m_telaah_ditolak->sekwan_search($column,$query,'','');
									  break;
			case "walikota" 		: $count_data = $this->m_telaah_ditolak->walikota_search($column,$query,'','');
									  $config ["total_rows"] = $count_data[0]['numrows']+$count_data[1]['numrows'];
									  break;
			case "kasubag_lurah" 	: $config ["total_rows"] = $this->m_telaah_ditolak->kasubag_lurah_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "kasubag_camat" 	: $config ["total_rows"] = $this->m_telaah_ditolak->kasubag_lurah_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "lurah" 			: $config ["total_rows"] = $this->m_telaah_ditolak->kasubag_lurah_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekcam" 			: $config ["total_rows"] = $this->m_telaah_ditolak->sekcam_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "camat" 			: $config ["total_rows"] = $this->m_telaah_ditolak->camat_search($column,$query,'','', $this->ion_auth->user()->row()->skpd_id);
									  break;
		}
		$config ["per_page"] = 25;
		$config ["uri_segment"] = 6;
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
		
		if ($this->uri->segment ( 6 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 6 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 6 )) ? $this->uri->segment ( 6 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		switch($this->uri->segment(4)){
			case "kabid" 			: $sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
									  if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 2){
											$this->data['telaah_ditolak'] = $this->m_telaah_ditolak->kabid_dprd_search($column,$query,$config ["per_page"], $page);
									  } else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 3){
											$this->data['telaah_ditolak'] = $this->m_telaah_ditolak->kabid_sekda_search($column,$query,$config ["per_page"], $page, $sekda[0]['bagian_id']);
									  } else if ($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 10){
											$this->data['telaah_ditolak'] = $this->m_telaah_ditolak->kabid_dinkes_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$this->data['telaah_ditolak'] = $this->m_telaah_ditolak->kabid_opd_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "sekdis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$this->data['telaah_ditolak'] = $this->m_telaah_ditolak->sekdis_dinkes_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$this->data['telaah_ditolak'] = $this->m_telaah_ditolak->sekdis_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "kadis" 			: if ($this->ion_auth->user()->row()->jenis_skpd == 10){
											$this->data['telaah_ditolak'] = $this->m_telaah_ditolak->kadis_dinkes_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  } else {	
											$this->data['telaah_ditolak'] = $this->m_telaah_ditolak->kadis_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  }
									  break;
			case "sekda" 			: $this->data['telaah_ditolak'] = $this->m_telaah_ditolak->sekda_search($column,$query,$config ["per_page"], $page);
									  break;
			case "asisten" 			: $sekda = $this->m_relasi_sekda->getasisten($this->ion_auth->user()->row()->id);
									  $this->data['telaah_ditolak'] = $this->m_telaah_ditolak->asisten_search($column,$query,$config ["per_page"], $page, $sekda[0]['asisten_id']);
									  break;
			case "kadprd" 			: $this->data['telaah_ditolak'] = $this->m_telaah_ditolak->kadprd_search($column,$query,$config ["per_page"], $page);
									  break;
			case "kapus" 			: $this->data['telaah_ditolak'] = $this->m_telaah_ditolak->kapus_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekwan" 			: $this->data['telaah_ditolak'] = $this->m_telaah_ditolak->sekwan_search($column,$query,$config ["per_page"], $page);
									  break;
			case "walikota" 		: $this->data['telaah_ditolak'] = $this->m_telaah_ditolak->walikota_search($column,$query,$config ["per_page"], $page);
									  break;
			case "kasubag_lurah" 	: $this->data['telaah_ditolak'] = $this->m_telaah_ditolak->kasubag_lurah_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "kasubag_camat" 	: $this->data['telaah_ditolak'] = $this->m_telaah_ditolak->kasubag_lurah_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "lurah" 			: $this->data['telaah_ditolak'] = $this->m_telaah_ditolak->kasubag_lurah_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "sekcam" 			: $this->data['telaah_ditolak'] = $this->m_telaah_ditolak->sekcam_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
			case "camat" 			: $this->data['telaah_ditolak'] = $this->m_telaah_ditolak->camat_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
									  break;
		}
		
		$this->render('telaah/disposisi/telaah_ditolak');
	}
	
	//View Detail Data
	public function lihat_laporan()
	{
		
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		if($this->uri->segment(5)==3){
			$this->data['entry'] =  $this->m_telaah->get_dprd($telaah_id);
			$this->data['pengikut'] =  $this->m_pengikut->data_dprd($telaah_id);
		} else if($this->uri->segment(5)==8){
			$this->data['entry'] =  $this->m_telaah->getWalikota($telaah_id);
			$this->data['pengikut'] =  $this->m_pengikut->data($telaah_id);
		} else {
			$this->data['entry'] =  $this->m_telaah->get($telaah_id);
			$this->data['pengikut'] =  $this->m_pengikut->data($telaah_id);
		}
		
		$this->data['telaah_id'] =  $telaah_id;
		$this->data['telaah_kategori'] =  $this->uri->segment(5);
		$this->data['telaah_disetujui'] =  $this->input->get('telaah_disetujui');
		$this->data['telaah_ditolak'] =  $this->input->get('telaah_ditolak');
		$this->data['total_anggaran_keseluruhan'] = $this->m_walikota->total_anggaran_skpd($this->data['entry'][0]['skpd_id']);
		$this->data['sisa_anggaran'] = $this->m_dprd->cek_sisa_anggaran_skpd($this->data['entry'][0]['skpd_id']);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('telaah');
		} else {
			
			switch($this->uri->segment(4)){
			case "esselon" 		:
			case "kadis" 		: 	
									if($this->data['entry'][0]['jenis_skpd']==7 && $this->data['entry'][0]['telaah_kategori']==1){
										$this->data['kepala_opd']= $this->m_spd->kepala_opd(36);
										$this->data['sekretaris_opd']= $this->m_spd->sekretaris_opd(36);
										$this->data['kabid']= $this->m_spd->kabid_dinkes();
									} else {
										$this->data['kepala_opd']= $this->m_spd->kepala_opd($this->ion_auth->user()->row()->skpd_id);
										$this->data['sekretaris_opd']= $this->m_spd->sekretaris_opd($this->ion_auth->user()->row()->skpd_id);
										$this->data['kabid']= $this->m_spd->kabid_opd($this->ion_auth->user()->row()->skpd_id);
									}
									break;
			case "dprd" 		: 	
			case "staff_dprd" 	: 	
			case "sekwan" 		: 	
									$this->data['sekwan']= $this->m_spd->sekwan();
									$this->data['kabid']= $this->m_spd->kabid_opd($this->ion_auth->user()->row()->skpd_id);
									break;
			case "walikota" 	: 	
			case "sekda" 	: 	
			case "staff_sekda" 	: 	
									$this->data['sekda']= $this->m_spd->sekda();
									$this->data['asisten1']= $this->m_spd->asisten1();
									$this->data['asisten2']= $this->m_spd->asisten2();
									$this->data['asisten3']= $this->m_spd->asisten3();
									break;
			case "camat" 		:
			case "staff_camat" 	: 	$this->data['camat']= $this->m_spd->camat($this->ion_auth->user()->row()->skpd_id);
									$this->data['sekcam']= $this->m_spd->sekcam($this->ion_auth->user()->row()->skpd_id);
									break;										
			case "lurah" 		:	$kecamatan = $this->m_spd->relasi_kelurahan($this->ion_auth->user()->row()->skpd_id);
									$this->data['camat']= $this->m_spd->camat($kecamatan[0]['id_kecamatan']);
									$this->data['sekcam']= $this->m_spd->sekcam($kecamatan[0]['id_kecamatan']);
									break;
			case "staff_lurah" 	: 	$this->data['lurah']= $this->m_spd->lurah($this->ion_auth->user()->row()->skpd_id);
									$this->data['kabid']= $this->m_spd->kabid_opd($this->ion_auth->user()->row()->skpd_id);
									break;
			case "kapus" 		: 	$this->data['kapus']= $this->m_spd->kapus($this->ion_auth->user()->row()->skpd_id);
									break;
		}
		
			if($this->uri->segment(5) == 1) {
				$this->data['telaah_disetujui'] = $this->input->get('telaah_disetujui');
				$this->data['timeline'] = $this->m_disposisi->getTimeline1($telaah_id);
			} else if($this->uri->segment(5) == 2) {
				$this->data['telaah_disetujui'] = $this->input->get('telaah_disetujui');
				$this->data['timeline'] = $this->m_disposisi->getTimeline2($telaah_id);
			} else if($this->uri->segment(5) == 3) {
				$this->data['telaah_disetujui'] = $this->input->get('telaah_disetujui');
				$this->data['timeline'] = $this->m_disposisi->getTimeline3($telaah_id);
			} else if($this->uri->segment(5) == 4) {
				$this->data['telaah_disetujui'] = $this->input->get('telaah_disetujui');
				$this->data['timeline'] = $this->m_disposisi->getTimeline4($telaah_id);
			}  else if($this->uri->segment(5) == 5) {
				$this->data['telaah_disetujui'] = $this->input->get('telaah_disetujui');
				$this->data['timeline'] = $this->m_disposisi->getTimeline5($telaah_id);
			}  else if($this->uri->segment(5) == 6) {
				$this->data['telaah_disetujui'] = $this->input->get('telaah_disetujui');
				$this->data['timeline'] = $this->m_disposisi->getTimeline6($telaah_id);
			}  else if($this->uri->segment(5) == 7) {
				$this->data['telaah_disetujui'] = $this->input->get('telaah_disetujui');
				$this->data['timeline'] = $this->m_disposisi->getTimeline7($telaah_id);
			} else if($this->uri->segment(5) == 8) {
				$this->data['telaah_disetujui'] = $this->input->get('telaah_disetujui');
				$this->data['timeline'] = $this->m_disposisi->getTimeline8($telaah_id);
			} else if($this->uri->segment(5) == 9) {
				$this->data['telaah_disetujui'] = $this->input->get('telaah_disetujui');
				$this->data['timeline'] = $this->m_disposisi->getTimeline9($telaah_id);
			} else if($this->uri->segment(5) == 10) {
				$this->data['telaah_disetujui'] = $this->input->get('telaah_disetujui');
				$this->data['timeline'] = $this->m_disposisi->getTimeline10($telaah_id);
			}  else if($this->uri->segment(5) == 11) {
				$this->data['telaah_disetujui'] = $this->input->get('telaah_disetujui');
				$this->data['timeline'] = $this->m_disposisi->getTimeline11($telaah_id);
			} 
			
			$this->render('telaah/disposisi/lihat_laporan/content');
		}		
		
	}
	
	//View Detail Data
	public function detail()
	{
		
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$telaah_kategori = $this->encrypt->decode(base64_decode($this->input->get('telaah_kategori')), $this->session->userdata('encrypt_key'));
		
		if($this->uri->segment(5)==3){
			$this->data['entry'] =  $this->m_telaah->get_dprd($telaah_id);
			$this->data['pengikut'] =  $this->m_pengikut->data_dprd($telaah_id);
		} else if($this->uri->segment(5)==8){
			$this->data['entry'] =  $this->m_telaah->getWalikota($telaah_id);
			$this->data['pengikut'] =  $this->m_pengikut->data($telaah_id);
		} else {
			$this->data['entry'] =  $this->m_telaah->get($telaah_id);
			$this->data['pengikut'] =  $this->m_pengikut->data($telaah_id);
		}
		
		$this->data['telaah_kategori'] =  $telaah_kategori;
		$this->data['telaah_disetujui'] =  $this->input->get('telaah_disetujui');
		$this->data['telaah_ditolak'] =  $this->input->get('telaah_ditolak');
		$this->data['total_anggaran_keseluruhan'] = $this->m_walikota->total_anggaran_skpd($this->data['entry'][0]['skpd_id']);
		$this->data['sisa_anggaran'] = $this->m_dprd->cek_sisa_anggaran_skpd($this->data['entry'][0]['skpd_id']);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('telaah');
		} else {
			
			// Get TimeLine
			switch($this->uri->segment(5)){
				case "1" 		: $timeline =  $this->m_telaah->getTimeline1($telaah_id);
								  if ($this->data['entry'][0]['telaah_sekretariat']==1){
									  $this->data['disposisi1'] = $timeline[0]['timeline_sekdis_id'];
									  $this->data['disposisi2'] = $timeline[0]['timeline_kadis_id'];
									  $this->data['disposisi3'] = "";
									  $this->data['disposisi4'] = "";
									  
									  $this->data['nama_disposisi1'] = "SEKRETARIS OPD";
									  $this->data['nama_disposisi2'] = "KEPALA OPD";
									  
									  $this->data['isi1'] = $timeline[0]['timeline_sekdis_disposisi'];
									  $this->data['isi2'] = $timeline[0]['timeline_kadis_disposisi'];
								  } else {
									  $this->data['disposisi1'] = $timeline[0]['timeline_kabid_id'];
									  $this->data['disposisi2'] = $timeline[0]['timeline_sekdis_id'];
									  $this->data['disposisi3'] = $timeline[0]['timeline_kadis_id'];
									  $this->data['disposisi4'] = "";
									  
									  $this->data['nama_disposisi1'] = "KABID / IRBAN / KABAG";
									  $this->data['nama_disposisi2'] = "SEKRETARIS OPD";
									  $this->data['nama_disposisi3'] = "KEPALA OPD";
									  
									  $this->data['isi1'] = $timeline[0]['timeline_kabid_disposisi'];
									  $this->data['isi2'] = $timeline[0]['timeline_sekdis_disposisi'];
									  $this->data['isi3'] = $timeline[0]['timeline_kadis_disposisi'];
								  }
								  break;
				case "2" 		: $timeline =  $this->m_telaah->getTimeline2($telaah_id);
								  if ($this->data['entry'][0]['telaah_domainperjalanan']==3 || $this->data['entry'][0]['telaah_domainperjalanan']==4) {
									  $this->data['disposisi1'] = $timeline[0]['timeline_sekdis_id'];
									  $this->data['disposisi2'] = $timeline[0]['timeline_kadis_id'];
									  $this->data['disposisi3'] = "";
									  $this->data['disposisi4'] = "";
									  
									  $this->data['nama_disposisi1'] = "SEKRETARIS OPD";
									  $this->data['nama_disposisi2'] = "KEPALA OPD";
									  
									  $this->data['isi1'] = $timeline[0]['timeline_sekdis_disposisi'];
									  $this->data['isi2'] = $timeline[0]['timeline_kadis_disposisi'];
									  
								  } else {
									  $this->data['disposisi1'] = $timeline[0]['timeline_sekdis_id'];
									  $this->data['disposisi2'] = $timeline[0]['timeline_kadis_id'];
									  $this->data['disposisi3'] = $timeline[0]['timeline_sekda_id'];
									  $this->data['disposisi4'] = $timeline[0]['timeline_walikota_id'];
									  
									  $this->data['nama_disposisi1'] = "SEKRETARIS OPD";
									  $this->data['nama_disposisi2'] = "KEPALA OPD";
									  $this->data['nama_disposisi3'] = "SEKDA";
									  $this->data['nama_disposisi4'] = "WALIKOTA";
									  
									  $this->data['isi1'] = $timeline[0]['timeline_sekdis_disposisi'];
									  $this->data['isi2'] = $timeline[0]['timeline_kadis_disposisi'];
									  $this->data['isi3'] = $timeline[0]['timeline_sekda_disposisi'];
									  $this->data['isi4'] = $timeline[0]['timeline_walikota_disposisi'];
								  }
								  break;
				case "3" 		: $timeline =  $this->m_telaah->getTimeline3($telaah_id);
								  $this->data['disposisi1'] = $timeline[0]['timeline_kasubid_id'];
								  $this->data['disposisi2'] = $timeline[0]['timeline_sekwan_id'];
								  $this->data['disposisi3'] = $timeline[0]['timeline_kadprd_id'];
								  $this->data['disposisi4'] = "";
								  
								  $this->data['nama_disposisi1'] = "KABAG";
								  $this->data['nama_disposisi2'] = "SEKRETARIS DEWAN";
								  $this->data['nama_disposisi3'] = "PIMPINAN DPRD";
								  
								  $this->data['isi1'] = $timeline[0]['timeline_kasubid_disposisi'];
								  $this->data['isi2'] = $timeline[0]['timeline_sekwan_disposisi'];
								  $this->data['isi3'] = $timeline[0]['timeline_kadprd_disposisi'];
								  break;
				case "4" 		: $timeline =  $this->m_telaah->getTimeline4($telaah_id);
								  $this->data['disposisi1'] = $timeline[0]['timeline_kabag_id'];
								  $this->data['disposisi2'] = $timeline[0]['timeline_asisten_id'];
								  $this->data['disposisi3'] = $timeline[0]['timeline_sekda_id'];
								  $this->data['disposisi4'] = $timeline[0]['timeline_walikota_id'];
								  
								  $this->data['nama_disposisi1'] = "KABAG";
								  $this->data['nama_disposisi2'] = "ASISTEN/KEPALA OPD";
								  $this->data['nama_disposisi3'] = "SEKDA";
								  $this->data['nama_disposisi4'] = "WALIKOTA";
								  
								  $this->data['isi1'] = $timeline[0]['timeline_kabag_disposisi'];
								  $this->data['isi2'] = $timeline[0]['timeline_asisten_disposisi'];
								  $this->data['isi3'] = $timeline[0]['timeline_sekda_disposisi'];
								  $this->data['isi4'] = $timeline[0]['timeline_walikota_disposisi'];
								  break;
				case "5" 		: $timeline =  $this->m_telaah->getTimeline5($telaah_id);
								  $this->data['disposisi1'] = $timeline[0]['timeline_sekcam_id'];
								  $this->data['disposisi2'] = $timeline[0]['timeline_camat_id'];
								  $this->data['disposisi3'] = $timeline[0]['timeline_sekda_id'];
								  $this->data['disposisi4'] = $timeline[0]['timeline_walikota_id'];
								  
								  $this->data['nama_disposisi1'] = "SEKCAM";
								  $this->data['nama_disposisi2'] = "CAMAT";
								  $this->data['nama_disposisi3'] = "SEKDA";
								  $this->data['nama_disposisi4'] = "WALIKOTA";
								  
								  $this->data['isi1'] = $timeline[0]['timeline_sekcam_disposisi'];
								  $this->data['isi2'] = $timeline[0]['timeline_camat_disposisi'];
								  $this->data['isi3'] = $timeline[0]['timeline_sekda_disposisi'];
								  $this->data['isi4'] = $timeline[0]['timeline_walikota_disposisi'];
								  break;
				case "6" 		: $timeline =  $this->m_telaah->getTimeline6($telaah_id);
								  $this->data['disposisi1'] = $timeline[0]['timeline_kabag_id'];
								  $this->data['disposisi2'] = $timeline[0]['timeline_sekwan_id'];
								  $this->data['disposisi3'] = "";
								  $this->data['disposisi4'] = "";
								  
								  $this->data['nama_disposisi1'] = "KABAG";
								  $this->data['nama_disposisi2'] = "SEKRETARIS DEWAN";
								  
								  $this->data['isi1'] = $timeline[0]['timeline_kabag_disposisi'];
								  $this->data['isi2'] = $timeline[0]['timeline_sekwan_disposisi'];
								  break;
				case "7" 		: $timeline =  $this->m_telaah->getTimeline7($telaah_id);
								  $this->data['disposisi1'] = $timeline[0]['timeline_lurah_id'];
								  $this->data['disposisi2'] = $timeline[0]['timeline_sekcam_id'];
								  $this->data['disposisi3'] = $timeline[0]['timeline_camat_id'];
								  $this->data['disposisi4'] = "";
								  
								  $this->data['nama_disposisi1'] = "KASUBAG";
								  $this->data['nama_disposisi2'] = "SEKCAM";
								  $this->data['nama_disposisi3'] = "CAMAT";
								  
								  $this->data['isi1'] = $timeline[0]['timeline_lurah_disposisi'];
								  $this->data['isi2'] = $timeline[0]['timeline_sekcam_disposisi'];
								  $this->data['isi3'] = $timeline[0]['timeline_camat_disposisi'];
								  break;
				case "8" 		: $timeline =  $this->m_telaah->getTimeline8($telaah_id);
								  $this->data['disposisi1'] = $timeline[0]['timeline_kabag_id'];
								  $this->data['disposisi2'] = $timeline[0]['timeline_sekda_id'];
								  $this->data['disposisi3'] = $timeline[0]['timeline_walikota_id'];
								  $this->data['disposisi4'] = "";
								  
								  $this->data['nama_disposisi1'] = "KABAG";
								  $this->data['nama_disposisi2'] = "SEKDA";
								  $this->data['nama_disposisi3'] = "WALIKOTA";
								  
								  $this->data['isi1'] = $timeline[0]['timeline_kabag_disposisi'];
								  $this->data['isi2'] = $timeline[0]['timeline_sekda_disposisi'];
								  $this->data['isi3'] = $timeline[0]['timeline_walikota_disposisi'];
								  break;
				case "9" 		: $timeline =  $this->m_telaah->getTimeline9($telaah_id);
								  $this->data['disposisi1'] = $timeline[0]['timeline_kabag_id'];
								  $this->data['disposisi2'] = $timeline[0]['timeline_asisten_id'];
								  $this->data['disposisi3'] = $timeline[0]['timeline_sekda_id'];
								  $this->data['disposisi4'] = "";
								  
								  $this->data['nama_disposisi1'] = "KABAG";
								  $this->data['nama_disposisi2'] = "ASISTEN";
								  $this->data['nama_disposisi3'] = "SEKDA";
								  
								  $this->data['isi1'] = $timeline[0]['timeline_kabag_disposisi'];
								  $this->data['isi2'] = $timeline[0]['timeline_asisten_disposisi'];
								  $this->data['isi3'] = $timeline[0]['timeline_sekda_disposisi'];
								  break;
				case "10" 		: $timeline =  $this->m_telaah->getTimeline10($telaah_id);
								  $this->data['disposisi1'] = $timeline[0]['timeline_kabag_id'];
								  $this->data['disposisi2'] = $timeline[0]['timeline_sekwan_id'];
								  $this->data['disposisi3'] = $timeline[0]['timeline_sekda_id'];
								  $this->data['disposisi4'] = $timeline[0]['timeline_walikota_id'];
								  
								  $this->data['nama_disposisi1'] = "KABAG";
								  $this->data['nama_disposisi2'] = "SEKRETARIS DEWAN";
								  $this->data['nama_disposisi3'] = "SEKDA";
								  $this->data['nama_disposisi4'] = "WALIKOTA";
								  
								  $this->data['isi1'] = $timeline[0]['timeline_kabag_disposisi'];
								  $this->data['isi2'] = $timeline[0]['timeline_sekwan_disposisi'];
								  $this->data['isi3'] = $timeline[0]['timeline_sekda_disposisi'];
								  $this->data['isi4'] = $timeline[0]['timeline_walikota_disposisi'];
								  break;
				case "11" 		: $timeline =  $this->m_telaah->getTimeline11($telaah_id);
								  $this->data['disposisi1'] = $timeline[0]['timeline_kapus_id'];
								  $this->data['disposisi2'] = "";
								  $this->data['disposisi3'] = "";
								  $this->data['disposisi4'] = "";
								  
								  $this->data['nama_disposisi1'] = "KEPALA PUSKESMAS";
								  
								  $this->data['isi1'] = $timeline[0]['timeline_kapus_disposisi'];
								  break;
			}
			
			$this->render('telaah/disposisi/detail');
		}		
		
	}
	
	//View Detail Data
	public function detail2(){
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		//Get data
		switch($this->uri->segment(4)){
			case "1" 			: $this->data['entry'] =  $this->m_esselon->get($telaah_id); break;
			case "2" 			: $this->data['entry'] =  $this->m_kadis->get($telaah_id); break;
			case "3" 			: $this->data['entry'] =  $this->m_dprd->get($telaah_id); break;
			case "4" 			: $this->data['entry'] =  $this->m_sekda->get($telaah_id); break;
			case "5" 			: $this->data['entry'] =  $this->m_camat->get($telaah_id); break;
			case "lurah" 		: $this->data['entry'] =  $this->m_lurah->get($telaah_id); break;
			case "6" 			: $this->data['entry'] =  $this->m_staff_dprd->get($telaah_id); break;
			case "7" 			: $this->data['entry'] =  $this->m_staff_camat->get($telaah_id); break;
			case "staff_lurah" 	: $this->data['entry'] =  $this->m_staff_lurah->get($telaah_id); break;
			case "8" 			: $this->data['entry'] =  $this->m_sekda->getWalikota($telaah_id); break;
			case "9" 			: $this->data['entry'] =  $this->m_sekda->get($telaah_id); break;
			case "10" 			: $this->data['entry'] =  $this->m_sekwan->get($telaah_id); break;
			case "11" 			: $this->data['entry'] =  $this->m_kapus->get($telaah_id); break;
		}
		
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			
			//Redirect
			switch($this->uri->segment(4)){
				case "1" 			: redirect('telaah/list_telaah/index/esselon'); break;
				case "2" 			: redirect('telaah/list_telaah/index/kadis'); break;
				case "3" 			: redirect('telaah/list_telaah/index/dprd'); break;
				case "4" 			: redirect('telaah/list_telaah/index/sekda'); break;
				case "5" 			: redirect('telaah/list_telaah/index/camat'); break;
				case "lurah" 		: redirect('telaah/list_telaah/index/lurah'); break;
				case "6" 			: redirect('telaah/list_telaah/index/staff_dprd'); break;
				case "7" 			: redirect('telaah/list_telaah/index/staff_camat'); break;
				case "staff_lurah" 	: redirect('telaah/list_telaah/index/staff_lurah'); break;
				case "8" 			: redirect('walikota/list_telaah/index'); break;
				case "9" 			: redirect('telaah/list_telaah/index/staff_setda'); break;
				case "10" 			: redirect('telaah/list_telaah/index/sekwan'); break;
				case "11" 			: redirect('telaah/list_telaah/index/kapus'); break;
			}
			
		} else {
			
			// Get pengikut 
			if($this->uri->segment(4)=="dprd"){
				$this->data['pengikut'] =  $this->m_pengikut->data_dprd($telaah_id);
			} else {
				$this->data['pengikut'] =  $this->m_pengikut->data($telaah_id);
			}
			
			// Get TimeLine
			switch($this->uri->segment(4)){
				case "1" 			: $timeline =  $this->m_esselon->getTimeline1($telaah_id); 
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
										  
										  $this->data['nama_disposisi1'] = "KEPALA BAGIAN";
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
				case "5" 			: $timeline =  $this->m_camat->getTimeline($telaah_id); 
										  $this->data['disposisi1'] = $timeline[0]['timeline_sekcam_id'];
										  $this->data['disposisi2'] = $timeline[0]['timeline_camat_id'];
										  $this->data['disposisi3'] = $timeline[0]['timeline_sekda_id'];
										  $this->data['disposisi4'] = $timeline[0]['timeline_walikota_id'];
										  
										  $this->data['nama_disposisi1'] = "SEKCAM";
										  $this->data['nama_disposisi2'] = "CAMAT";
										  $this->data['nama_disposisi3'] = "SEKDA";
										  $this->data['nama_disposisi4'] = "WALIKOTA";
										  break;
				case "lurah" 		: $timeline =  $this->m_lurah->getTimeline($telaah_id); 
										  $this->data['disposisi1'] = $timeline[0]['timeline_sekcam_id'];
										  $this->data['disposisi2'] = $timeline[0]['timeline_camat_id'];
										  $this->data['disposisi3'] = $timeline[0]['timeline_sekda_id'];
										  $this->data['disposisi4'] = $timeline[0]['timeline_walikota_id'];
										  
										  $this->data['nama_disposisi1'] = "SEKCAM";
										  $this->data['nama_disposisi2'] = "CAMAT";
										  $this->data['nama_disposisi3'] = "SEKDA";
										  $this->data['nama_disposisi4'] = "WALIKOTA";
										  break;
				case "6" 			: $timeline =  $this->m_staff_dprd->getTimeline($telaah_id); 
										  $this->data['disposisi1'] = $timeline[0]['timeline_kabag_id'];
										  $this->data['disposisi2'] = $timeline[0]['timeline_sekwan_id'];
										  $this->data['disposisi3'] = "";
										  $this->data['disposisi4'] = "";
										  
										  $this->data['nama_disposisi1'] = "KABAG";
										  $this->data['nama_disposisi2'] = "SEKRETARIS DEWAN";
										  break;
				case "7" 			: $timeline =  $this->m_staff_camat->getTimeline($telaah_id); 
										  $this->data['disposisi1'] = $timeline[0]['timeline_lurah_id'];
										  $this->data['disposisi2'] = $timeline[0]['timeline_sekcam_id'];
										  $this->data['disposisi3'] = $timeline[0]['timeline_camat_id'];
										  $this->data['disposisi4'] = "";
										  
										  $this->data['nama_disposisi1'] = "KASUBAG";
										  $this->data['nama_disposisi2'] = "SEKCAM";
										  $this->data['nama_disposisi3'] = "CAMAT";
										  break;
				case "staff_lurah" 	: $timeline =  $this->m_staff_lurah->getTimeline($telaah_id); 
										  $this->data['disposisi1'] = $timeline[0]['timeline_lurah_id'];
										  $this->data['disposisi2'] = $timeline[0]['timeline_sekcam_id'];
										  $this->data['disposisi3'] = $timeline[0]['timeline_camat_id'];
										  $this->data['disposisi4'] = "";
										  
										  $this->data['nama_disposisi1'] = "KASUBAG";
										  $this->data['nama_disposisi2'] = "SEKCAM";
										  $this->data['nama_disposisi3'] = "CAMAT";
										  break;
				case "8" 			: $timeline =  $this->m_sekda->getTimeline8($telaah_id); 
										  $this->data['disposisi1'] = $timeline[0]['timeline_kabag_id'];
										  $this->data['disposisi2'] = $timeline[0]['timeline_sekda_id'];
										  $this->data['disposisi3'] = $timeline[0]['timeline_walikota_id'];
										  $this->data['disposisi4'] = "";
										  
										  $this->data['nama_disposisi1'] = "KABAG";
										  $this->data['nama_disposisi2'] = "SEKDA";
										  $this->data['nama_disposisi3'] = "WALIKOTA";
										  break;
				case "9" 			: $timeline =  $this->m_sekda->getTimeline9($telaah_id); 
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
			$this->render('telaah/disposisi/walikota/detail');
		}
	}
	
	### DISPOSISI
	public function disposisi() 
	{
		
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$telaah_kategori = $this->encrypt->decode(base64_decode($this->input->get('telaah_kategori')), $this->session->userdata('encrypt_key'));
		$this->data['entry'] =  $this->m_telaah->get($telaah_id);
		
		if($telaah_kategori == 1) {
			$this->data['telaah_disetujui'] = $this->input->get('telaah_disetujui');
			$this->data['timeline'] = $this->m_disposisi->getTimeline1($telaah_id);
		} else if($telaah_kategori == 2) {
			$this->data['telaah_disetujui'] = $this->input->get('telaah_disetujui');
			$this->data['timeline'] = $this->m_disposisi->getTimeline2($telaah_id);
		} else if($telaah_kategori == 3) {
			$this->data['telaah_disetujui'] = $this->input->get('telaah_disetujui');
			$this->data['timeline'] = $this->m_disposisi->getTimeline3($telaah_id);
		} else if($telaah_kategori == 4) {
			$this->data['telaah_disetujui'] = $this->input->get('telaah_disetujui');
			$this->data['timeline'] = $this->m_disposisi->getTimeline4($telaah_id);
		}  else if($telaah_kategori == 5) {
			$this->data['telaah_disetujui'] = $this->input->get('telaah_disetujui');
			$this->data['timeline'] = $this->m_disposisi->getTimeline5($telaah_id);
		}  else if($telaah_kategori == 6) {
			$this->data['telaah_disetujui'] = $this->input->get('telaah_disetujui');
			$this->data['timeline'] = $this->m_disposisi->getTimeline6($telaah_id);
		}  else if($telaah_kategori == 7) {
			$this->data['telaah_disetujui'] = $this->input->get('telaah_disetujui');
			$this->data['timeline'] = $this->m_disposisi->getTimeline7($telaah_id);
		} else if($telaah_kategori == 8) {
			$this->data['telaah_disetujui'] = $this->input->get('telaah_disetujui');
			$this->data['timeline'] = $this->m_disposisi->getTimeline8($telaah_id);
		} else if($telaah_kategori == 9) {
			$this->data['telaah_disetujui'] = $this->input->get('telaah_disetujui');
			$this->data['timeline'] = $this->m_disposisi->getTimeline9($telaah_id);
		} else if($telaah_kategori == 10) {
			$this->data['telaah_disetujui'] = $this->input->get('telaah_disetujui');
			$this->data['timeline'] = $this->m_disposisi->getTimeline10($telaah_id);
		}  else if($telaah_kategori == 11) {
			$this->data['telaah_disetujui'] = $this->input->get('telaah_disetujui');
			$this->data['timeline'] = $this->m_disposisi->getTimeline11($telaah_id);
		} 
		
		$this->render('telaah/disposisi/disposisi');
	}
	
	public function disposisi_update() 
	{
		
		$data['telaah_id'] = $this->input->post('telaah_id');
		
		### ACC
		if($this->input->post('acc')=="Acc dan Lanjutkan"){
			
			$data2['telaah_id'] = $this->input->post('telaah_id');
			//kode 1 jika telaah di ACC dan dilanjutkan ke akun selanjutnya
			$data2['telaah_perbaikan'] = 0;
			$data2['telaah_status'] = 1;
			$this->m_telaah->update($data2);
			
			if($this->input->post('telaah_kategori')==1){
				
				if($this->ion_auth->get_users_groups()->row()->id == 3){
					
					$data['timeline_sekdis_id'] = 1; 
					$data['timeline_sekdis_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_sekdis_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekdis_disposisi'] = $this->input->post('timeline_sekdis_disposisi');
					$this->m_disposisi->update_timeline_1($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if ($this->ion_auth->get_users_groups()->row()->id == 4){
					
					$data['timeline_kadis_id'] = 1; 
					$data['timeline_kadis_date'] = date("Y-m-d H:i:s");
					$data['timeline_kadis_disposisi'] = $this->input->post('timeline_kadis_disposisi');
					
					$data2['telaah_id'] = $this->input->post('telaah_id');
					$data2['telaah_status'] = 2;
					$this->m_disposisi->update_timeline_1($data, $data2);
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else {
					
					$data['timeline_kabid_id'] = 1; 
					$data['timeline_kabid_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_kabid_date'] = date("Y-m-d H:i:s");
					$data['timeline_kabid_disposisi'] = $this->input->post('timeline_kabid_disposisi');
					$this->m_disposisi->update_timeline_1($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}
				
			} else if($this->input->post('telaah_kategori')==2){
				
				if($this->ion_auth->get_users_groups()->row()->id == 3){
					
					$data['timeline_sekdis_id'] = 1; 
					$data['timeline_sekdis_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekdis_disposisi'] = $this->input->post('timeline_sekdis_disposisi');
					$this->m_disposisi->update_timeline_2($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "17";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 4){
					
					$data['timeline_kadis_id'] = 1; 
					$data['timeline_kadis_date'] = date("Y-m-d H:i:s");
					$data['timeline_kadis_disposisi'] = $this->input->post('timeline_kadis_disposisi');
					
					$data2['telaah_id'] = $this->input->post('telaah_id');
					$data2['telaah_status'] = 2;
					
					if($this->input->post('telaah_domainperjalanan') == 3 || $this->input->post('telaah_domainperjalanan') == 4 ){
						$this->m_disposisi->update_timeline_2($data, $data2);
					} else {
						$this->m_disposisi->update_timeline_2($data, '');
					}
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "17";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
				
				} else if($this->ion_auth->get_users_groups()->row()->id == 6){
					
					$data['timeline_sekda_id'] = 1; 
					$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekda_disposisi'] = $this->input->post('timeline_sekda_disposisi');
					$this->m_disposisi->update_timeline_2($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "17";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}  else if($this->ion_auth->get_users_groups()->row()->id == 8){
					
					$data['timeline_walikota_id'] = 1; 
					$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
					$data['timeline_walikota_disposisi'] = $this->input->post('timeline_walikota_disposisi');
					
					$data2['telaah_id'] = $this->input->post('telaah_id');
					$data2['telaah_status'] = 2;
					
					$this->m_disposisi->update_timeline_2($data,$data2);
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "17";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else {
					
					$data['timeline_sekdis_id'] = 1; 
					$data['timeline_sekdis_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekdis_disposisi'] = $this->input->post('timeline_sekdis_disposisi');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "17";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					$this->m_disposisi->update_timeline_2($data,'');
				}
				
			} else if($this->input->post('telaah_kategori')==3){
				
				if($this->ion_auth->get_users_groups()->row()->id == 2){
					
					$data['timeline_kasubid_id'] = 1; 
					$data['timeline_kasubid_date'] = date("Y-m-d H:i:s");
					$data['timeline_kasubid_disposisi'] = $this->input->post('timeline_kasubid_disposisi');
					$this->m_disposisi->update_timeline_3($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "18";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 10){
					
					$data['timeline_sekwan_id'] = 1; 
					$data['timeline_sekwan_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekwan_disposisi'] = $this->input->post('timeline_sekwan_disposisi');
					$this->m_disposisi->update_timeline_3($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "18";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 7){
					
					$data['timeline_kadprd_id'] = 1; 
					$data['timeline_kadprd_date'] = date("Y-m-d H:i:s");
					$data['timeline_kadprd_disposisi'] = $this->input->post('timeline_kadprd_disposisi');
					
					$data2['telaah_id'] = $this->input->post('telaah_id');
					$data2['telaah_status'] = 2;
					
					$this->m_disposisi->update_timeline_3($data, $data2);
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "18";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else {}
				
			} else if($this->input->post('telaah_kategori')==4){
				
				if($this->ion_auth->get_users_groups()->row()->id == 2){
					
					$data['timeline_kabag_id'] = 1; 
					$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
					$data['timeline_kabag_disposisi'] = $this->input->post('timeline_kabag_disposisi');
					$this->m_disposisi->update_timeline_4($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "19";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 6){
					
					$data['timeline_sekda_id'] = 1; 
					$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekda_disposisi'] = $this->input->post('timeline_sekda_disposisi');
					$this->m_disposisi->update_timeline_4($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "19";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 5){
					
					$data['timeline_asisten_id'] = 1; 
					$data['timeline_asisten_date'] = date("Y-m-d H:i:s");
					$data['timeline_asisten_disposisi'] = $this->input->post('timeline_asisten_disposisi');
					$this->m_disposisi->update_timeline_4($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "19";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}  else if($this->ion_auth->get_users_groups()->row()->id == 8){
					
					$data['timeline_walikota_id'] = 1; 
					$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
					$data['timeline_walikota_disposisi'] = $this->input->post('timeline_walikota_disposisi');
					
					$data2['telaah_id'] = $this->input->post('telaah_id');
					$data2['telaah_status'] = 2;
					
					$this->m_disposisi->update_timeline_4($data, $data2);
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "19";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}
				
			} else if($this->input->post('telaah_kategori')==6){
				
				if($this->ion_auth->get_users_groups()->row()->id == 2){
					
					$data['timeline_kabag_id'] = 1; 
					$data['timeline_kabag_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
					$data['timeline_kabag_disposisi'] = $this->input->post('timeline_kabag_disposisi');
					$this->m_disposisi->update_timeline_6($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "21";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}  else if($this->ion_auth->get_users_groups()->row()->id == 10){
					
					$data['timeline_sekwan_id'] = 1; 
					$data['timeline_sekwan_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_sekwan_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekwan_disposisi'] = $this->input->post('timeline_sekwan_disposisi');
					
					$data2['telaah_id'] = $this->input->post('telaah_id');
					$data2['telaah_status'] = 2;
					
					$this->m_disposisi->update_timeline_6($data, $data2);
					  
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "21";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}
				
			} else if($this->input->post('telaah_kategori')==5){
				
				if($this->ion_auth->get_users_groups()->row()->id == 12){
					
					$data['timeline_sekcam_id'] = 1; 
					$data['timeline_sekcam_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_sekcam_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekcam_disposisi'] = $this->input->post('timeline_sekcam_disposisi');
					$this->m_disposisi->update_timeline_5($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "20";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}  else if($this->ion_auth->get_users_groups()->row()->id == 11){
					
					$data['timeline_camat_id'] = 1; 
					$data['timeline_camat_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_camat_date'] = date("Y-m-d H:i:s");
					$data['timeline_camat_disposisi'] = $this->input->post('timeline_camat_disposisi');
					$this->m_disposisi->update_timeline_5($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "20";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 6){
					
					$data['timeline_sekda_id'] = 1; 
					$data['timeline_sekda_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekda_disposisi'] = $this->input->post('timeline_sekda_disposisi');
					$this->m_disposisi->update_timeline_5($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "20";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 8){
					
					$data['timeline_walikota_id'] = 1; 
					$data['timeline_walikota_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
					$data['timeline_walikota_disposisi'] = $this->input->post('timeline_walikota_disposisi');
					
					$data2['telaah_id'] = $this->input->post('telaah_id');
					$data2['telaah_status'] = 2;
					
					$this->m_disposisi->update_timeline_5($data, $data2);
				
					## Insert Table TTE
					if($this->input->post('jenis_skpd')==4){
						$ttd =  $this->m_spd->sekda();
						$data7['telaah_id'] = $this->input->post('telaah_id');
						$data7['telaah_kategori'] = $this->input->post('telaah_kategori');
						$data7['group'] = 6;
						$data7['pegawai_id'] = $ttd[0]['pegawai_id'];
						$data7['skpd_id'] = $ttd[0]['skpd_id'];
						$data7['jenis_skpd'] = $ttd[0]['jenis_skpd'];
						$this->m_disposisi->kuasakan($data7);
						
						$ttd2 =  $this->m_spd->camat($this->input->post('skpd_id'));
						$data8['telaah_id'] = $this->input->post('telaah_id');
						$data8['telaah_kategori'] = $this->input->post('telaah_kategori');
						$data8['group'] = 11;
						$data8['pegawai_id'] = $ttd2[0]['pegawai_id'];
						$data8['skpd_id'] = $ttd2[0]['skpd_id'];
						$data8['jenis_skpd'] = $ttd2[0]['jenis_skpd'];
						$this->m_disposisi->kuasakan($data8);
					}else{
						$relasi_kelurahan =  $this->m_spd->relasi_kelurahan($this->input->post('skpd_id'));
					
						$ttd =  $this->m_spd->sekda();
						$data7['telaah_id'] = $this->input->post('telaah_id');
						$data7['telaah_kategori'] = $this->input->post('telaah_kategori');
						$data7['group'] = 6;
						$data7['pegawai_id'] = $ttd[0]['pegawai_id'];
						$data7['skpd_id'] = $ttd[0]['skpd_id'];
						$data7['jenis_skpd'] = $ttd[0]['jenis_skpd'];
						$this->m_disposisi->kuasakan($data7);
						
						$ttd2 =  $this->m_spd->camat($relasi_kelurahan[0]['id_kecamatan']);
						$data8['telaah_id'] = $this->input->post('telaah_id');
						$data8['telaah_kategori'] = $this->input->post('telaah_kategori');
						$data8['group'] = 11;
						$data8['pegawai_id'] = $ttd2[0]['pegawai_id'];
						$data8['skpd_id'] = $ttd2[0]['skpd_id'];
						$data8['jenis_skpd'] = $ttd2[0]['jenis_skpd'];
						$this->m_disposisi->kuasakan($data8);
						
					}
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISIi";
					$log['kode_log_action_table'] = "20";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}
				
			} else if($this->input->post('telaah_kategori')==7){
				
				if($this->ion_auth->get_users_groups()->row()->id == 1 || $this->ion_auth->get_users_groups()->row()->id == 13){
					
					$data['timeline_lurah_id'] = 1; 
					$data['timeline_lurah_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_lurah_date'] = date("Y-m-d H:i:s");
					$data['timeline_lurah_disposisi'] = $this->input->post('timeline_lurah_disposisi');
					$this->m_disposisi->update_timeline_7($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "22";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}  else if($this->ion_auth->get_users_groups()->row()->id == 12){
					
					$data['timeline_sekcam_id'] = 1; 
					$data['timeline_sekcam_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_sekcam_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekcam_disposisi'] = $this->input->post('timeline_sekcam_disposisi');
					$this->m_disposisi->update_timeline_7($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "22";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id ==11){
					
					$data['timeline_camat_id'] = 1; 
					$data['timeline_camat_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_camat_date'] = date("Y-m-d H:i:s");
					$data['timeline_camat_disposisi'] = $this->input->post('timeline_camat_disposisi');
					
					$data2['telaah_id'] = $this->input->post('telaah_id');
					$data2['telaah_status'] = 2;
					
					$this->m_disposisi->update_timeline_7($data,$data2);
				
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "22";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}	
				
			} else if($this->input->post('telaah_kategori')==8){
				
				if($this->ion_auth->get_users_groups()->row()->id == 2){
					
					$data['timeline_kabag_id'] = 1; 
					$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
					$data['timeline_kabag_disposisi'] = $this->input->post('timeline_kabag_disposisi');
					$this->m_disposisi->update_timeline_8($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "19";
					$log['action_table'] = "Tracking SPPD";
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 6){
					
					$data['timeline_sekda_id'] = 1; 
					$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekda_disposisi'] = $this->input->post('timeline_sekda_disposisi');
					$this->m_disposisi->update_timeline_8($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "19";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}  else if($this->ion_auth->get_users_groups()->row()->id == 8){
					
					$data['timeline_walikota_id'] = 1; 
					$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
					$data['timeline_walikota_disposisi'] = $this->input->post('timeline_walikota_disposisi');
					
					$data2['telaah_id'] = $this->input->post('telaah_id');
					$data2['telaah_status'] = 2;
					
					$this->m_disposisi->update_timeline_8($data, $data2);
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "19";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}
				
			} else if($this->input->post('telaah_kategori')==9){
				
				if($this->ion_auth->get_users_groups()->row()->id == 2){
					
					$data['timeline_kabag_id'] = 1; 
					$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
					$data['timeline_kabag_disposisi'] = $this->input->post('timeline_kabag_disposisi');
					$this->m_disposisi->update_timeline_9($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "19";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 6){
					
					$data['timeline_sekda_id'] = 1; 
					$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekda_disposisi'] = $this->input->post('timeline_sekda_disposisi');
					
					$data2['telaah_id'] = $this->input->post('telaah_id');
					$data2['telaah_status'] = 2;
					
					$this->m_disposisi->update_timeline_9($data,$data2);
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "19";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 5){
					
					$data['timeline_asisten_id'] = 1; 
					$data['timeline_asisten_date'] = date("Y-m-d H:i:s");
					$data['timeline_asisten_disposisi'] = $this->input->post('timeline_asisten_disposisi');
					$this->m_disposisi->update_timeline_9($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "19";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} 
				 
			} else if($this->input->post('telaah_kategori')==10){
				
				if($this->ion_auth->get_users_groups()->row()->id == 2){
					
					$data['timeline_kabag_id'] = 1; 
					$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
					$data['timeline_kabag_disposisi'] = $this->input->post('timeline_kabag_disposisi');
					$this->m_disposisi->update_timeline_10($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "17";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 10){
					
					$data['timeline_sekwan_id'] = 1; 
					$data['timeline_sekwan_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekwan_disposisi'] = $this->input->post('timeline_sekwan_disposisi');
					$this->m_disposisi->update_timeline_10($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "17";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 6){
					
					$data['timeline_sekda_id'] = 1; 
					$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekda_disposisi'] = $this->input->post('timeline_sekda_disposisi');
					$this->m_disposisi->update_timeline_10($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "17";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}  else if($this->ion_auth->get_users_groups()->row()->id == 8){
					
					$data['timeline_walikota_id'] = 1; 
					$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
					$data['timeline_walikota_disposisi'] = $this->input->post('timeline_walikota_disposisi');
					
					$data2['telaah_id'] = $this->input->post('telaah_id');
					$data2['telaah_status'] = 2;
					
					$this->m_disposisi->update_timeline_10($data,$data2);
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "17";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} 
				
			} else if($this->input->post('telaah_kategori')==11){
					
					$data['timeline_kapus_id'] = 1; 
					$data['timeline_kapus_date'] = date("Y-m-d H:i:s");
					$data['timeline_kapus_disposisi'] = $this->input->post('timeline_kapus_disposisi');
					
					$data2['telaah_id'] = $this->input->post('telaah_id');
					$data2['telaah_status'] = 2;
					
					$this->m_disposisi->update_timeline_11($data, $data2);
					  
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "18";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
			} 
		
		if($this->input->post('telaah_kategori')==8){
			redirect('telaah/disposisi/index/'.$this->input->post('posisi'),'refresh');
		} else {
			redirect('telaah/disposisi/telaah_disetujui/'.$this->input->post('posisi'),'refresh');
		}
		
		
		### TIDAK ACC		
		} else if($this->input->post('acc')=="Tidak Acc") {
			
			$data2['telaah_id'] = $this->input->post('telaah_id');
			//kode 3 jika telaah di TOLAK
			$data2['telaah_status'] = 3;
			$this->m_telaah->update($data2);
			
			if($this->input->post('telaah_kategori')==1){
				if($this->ion_auth->get_users_groups()->row()->id == 3){
					$data['timeline_sekdis_id'] = 2; 
					$data['timeline_sekdis_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekdis_disposisi'] = $this->input->post('timeline_sekdis_disposisi');
					$this->m_disposisi->update_timeline_1($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 4){
					$data['timeline_kadis_id'] = 2; 
					$data['timeline_kadis_date'] = date("Y-m-d H:i:s");
					$data['timeline_kadis_disposisi'] = $this->input->post('timeline_kadis_disposisi');
					$this->m_disposisi->update_timeline_1($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else {
					$data['timeline_kabid_id'] = 2; 
					$data['timeline_kabid_date'] = date("Y-m-d H:i:s");
					$data['timeline_kabid_disposisi'] = $this->input->post('timeline_kabid_disposisi');
					$this->m_disposisi->update_timeline_1($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}
			} else if($this->input->post('telaah_kategori')==2){
				
				if($this->ion_auth->get_users_groups()->row()->id == 3){
					$data['timeline_sekdis_id'] = 2; 
					$data['timeline_sekdis_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekdis_disposisi'] = $this->input->post('timeline_sekdis_disposisi');
					$this->m_disposisi->update_timeline_2($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "17";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 4){
					$data['timeline_kadis_id'] = 2; 
					$data['timeline_kadis_date'] = date("Y-m-d H:i:s");
					$data['timeline_kadis_disposisi'] = $this->input->post('timeline_kadis_disposisi');
					$this->m_disposisi->update_timeline_2($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "17";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 6){
					$data['timeline_sekda_id'] = 2; 
					$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekda_disposisi'] = $this->input->post('timeline_sekda_disposisi');
					$this->m_disposisi->update_timeline_2($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "17";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}  else if($this->ion_auth->get_users_groups()->row()->id == 8){
					$data['timeline_walikota_id'] = 2; 
					$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
					$data['timeline_walikota_disposisi'] = $this->input->post('timeline_walikota_disposisi');
					$this->m_disposisi->update_timeline_2($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "17";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else {
					$data['timeline_sekdis_id'] = 2; 
					$data['timeline_sekdis_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekdis_disposisi'] = $this->input->post('timeline_sekdis_disposisi');
					$this->m_disposisi->update_timeline_2($data,'');
				}
			}else if($this->input->post('telaah_kategori')==3){
				
				if($this->ion_auth->get_users_groups()->row()->id == 2){
					$data['timeline_kasubid_id'] = 2; 
					$data['timeline_kasubid_date'] = date("Y-m-d H:i:s");
					$data['timeline_kasubid_disposisi'] = $this->input->post('timeline_kasubid_disposisi');
					$this->m_disposisi->update_timeline_3($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "18";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 10){
					$data['timeline_sekwan_id'] = 2; 
					$data['timeline_sekwan_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekwan_disposisi'] = $this->input->post('timeline_sekwan_disposisi');
					$this->m_disposisi->update_timeline_3($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "18";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 7){
					$data['timeline_kadprd_id'] = 2; 
					$data['timeline_kadprd_date'] = date("Y-m-d H:i:s");
					$data['timeline_kadprd_disposisi'] = $this->input->post('timeline_kadprd_disposisi');
					$this->m_disposisi->update_timeline_3($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "18";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else {}
				
			}else if($this->input->post('telaah_kategori')==4){
				if($this->ion_auth->get_users_groups()->row()->id == 2){
					$data['timeline_kabag_id'] = 2; 
					$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
					$data['timeline_kabag_disposisi'] = $this->input->post('timeline_kabag_disposisi');
					$this->m_disposisi->update_timeline_4($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "19";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 6){
					$data['timeline_sekda_id'] = 2; 
					$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekda_disposisi'] = $this->input->post('timeline_sekda_disposisi');
					$this->m_disposisi->update_timeline_4($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "19";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 5){
					$data['timeline_asisten_id'] = 2; 
					$data['timeline_asisten_date'] = date("Y-m-d H:i:s");
					$data['timeline_asisten_disposisi'] = $this->input->post('timeline_asisten_disposisi');
					$this->m_disposisi->update_timeline_4($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "19";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 8){
					$data['timeline_walikota_id'] = 2; 
					$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
					$data['timeline_walikota_disposisi'] = $this->input->post('timeline_walikota_disposisi');
					$this->m_disposisi->update_timeline_4($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "19";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}
			} else if($this->input->post('telaah_kategori')==6){
				if($this->ion_auth->get_users_groups()->row()->id == 2){
					$data['timeline_kabag_id'] = 2; 
					$data['timeline_kabag_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
					$data['timeline_kabag_disposisi'] = $this->input->post('timeline_kabag_disposisi');
					$this->m_disposisi->update_timeline_6($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "21";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}  else if($this->ion_auth->get_users_groups()->row()->id == 10){
					$data['timeline_sekwan_id'] = 2; 
					$data['timeline_sekwan_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_sekwan_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekwan_disposisi'] = $this->input->post('timeline_sekwan_disposisi');
					$this->m_disposisi->update_timeline_6($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "21";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}
			} else if($this->input->post('telaah_kategori')==5){
				if($this->ion_auth->get_users_groups()->row()->id == 12){
					$data['timeline_sekcam_id'] = 2; 
					$data['timeline_sekcam_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_sekcam_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekcam_disposisi'] = $this->input->post('timeline_sekcam_disposisi');
					$this->m_disposisi->update_timeline_5($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "20";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}  else if($this->ion_auth->get_users_groups()->row()->id == 11){
					$data['timeline_camat_id'] = 2; 
					$data['timeline_camat_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_camat_date'] = date("Y-m-d H:i:s");
					$data['timeline_camat_disposisi'] = $this->input->post('timeline_camat_disposisi');
					$this->m_disposisi->update_timeline_5($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "20";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 6){
					$data['timeline_sekda_id'] = 2; 
					$data['timeline_sekda_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekda_disposisi'] = $this->input->post('timeline_sekda_disposisi');
					$this->m_disposisi->update_timeline_5($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "20";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 8){
					$data['timeline_walikota_id'] = 2; 
					$data['timeline_walikota_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
					$data['timeline_walikota_disposisi'] = $this->input->post('timeline_walikota_disposisi');
					$this->m_disposisi->update_timeline_5($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "20";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}
			} else if($this->input->post('telaah_kategori')==7){
				if($this->ion_auth->get_users_groups()->row()->id == 1){
					$data['timeline_lurah_id'] = 2; 
					$data['timeline_lurah_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_lurah_date'] = date("Y-m-d H:i:s");
					$data['timeline_lurah_disposisi'] = $this->input->post('timeline_lurah_disposisi');
					$this->m_disposisi->update_timeline_7($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "22";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}  else if($this->ion_auth->get_users_groups()->row()->id == 12){
					$data['timeline_sekcam_id'] = 2; 
					$data['timeline_sekcam_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_sekcam_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekcam_disposisi'] = $this->input->post('timeline_sekcam_disposisi');
					$this->m_disposisi->update_timeline_7($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "22";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id ==11){
					$data['timeline_camat_id'] = 2; 
					$data['timeline_camat_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_camat_date'] = date("Y-m-d H:i:s");
					$data['timeline_camat_disposisi'] = $this->input->post('timeline_camat_disposisi');
					$this->m_disposisi->update_timeline_7($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "22";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} 
			} else if($this->input->post('telaah_kategori')==8){
				if($this->ion_auth->get_users_groups()->row()->id == 2){
					$data['timeline_kabag_id'] = 2; 
					$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
					$data['timeline_kabag_disposisi'] = $this->input->post('timeline_kabag_disposisi');
					$this->m_disposisi->update_timeline_8($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "19";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 6){
					$data['timeline_sekda_id'] = 2; 
					$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekda_disposisi'] = $this->input->post('timeline_sekda_disposisi');
					$this->m_disposisi->update_timeline_8($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "19";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}else if($this->ion_auth->get_users_groups()->row()->id == 8){
					$data['timeline_walikota_id'] = 2; 
					$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
					$data['timeline_walikota_disposisi'] = $this->input->post('timeline_walikota_disposisi');
					$this->m_disposisi->update_timeline_8($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "19";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}
			} else if($this->input->post('telaah_kategori')==4){
				if($this->ion_auth->get_users_groups()->row()->id == 9){
					$data['timeline_kabag_id'] = 2; 
					$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
					$data['timeline_kabag_disposisi'] = $this->input->post('timeline_kabag_disposisi');
					$this->m_disposisi->update_timeline_9($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "19";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 6){
					$data['timeline_sekda_id'] = 2; 
					$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekda_disposisi'] = $this->input->post('timeline_sekda_disposisi');
					$this->m_disposisi->update_timeline_9($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "19";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 5){
					$data['timeline_asisten_id'] = 2; 
					$data['timeline_asisten_date'] = date("Y-m-d H:i:s");
					$data['timeline_asisten_disposisi'] = $this->input->post('timeline_asisten_disposisi');
					$this->m_disposisi->update_timeline_9($data,'');
					
					$log['kode_log_action'] = "58";
					$log['action'] = "TIDAK ACC";
					$log['kode_log_action_table'] = "19";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}
			}  else if($this->input->post('telaah_kategori')==10){
				
				if($this->ion_auth->get_users_groups()->row()->id == 2){
					$data['timeline_kabag_id'] = 2; 
					$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
					$data['timeline_kabag_disposisi'] = $this->input->post('timeline_kabag_disposisi');
					$this->m_disposisi->update_timeline_10($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "17";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 9){
					$data['timeline_sekwan_id'] = 2; 
					$data['timeline_sekwan_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekwan_disposisi'] = $this->input->post('timeline_sekwan_disposisi');
					$this->m_disposisi->update_timeline_10($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "17";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} else if($this->ion_auth->get_users_groups()->row()->id == 6){
					$data['timeline_sekda_id'] = 2; 
					$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekda_disposisi'] = $this->input->post('timeline_sekda_disposisi');
					$this->m_disposisi->update_timeline_10($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "17";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				}  else if($this->ion_auth->get_users_groups()->row()->id == 8){
					$data['timeline_walikota_id'] = 2; 
					$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
					$data['timeline_walikota_disposisi'] = $this->input->post('timeline_walikota_disposisi');
					$this->m_disposisi->update_timeline_10($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "17";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
				} 
			}  else if($this->input->post('telaah_kategori')==11){
					$data['timeline_kapus_id'] = 2; 
					$data['timeline_kapus_date'] = date("Y-m-d H:i:s");
					$data['timeline_kapus_disposisi'] = $this->input->post('timeline_kapus_disposisi');
					$this->m_disposisi->update_timeline_11($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "18";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
			} 
		
			if($this->input->post('telaah_kategori')==8){
				redirect('telaah/disposisi/index/'.$this->input->post('posisi'),'refresh');
			} else {
				redirect('telaah/disposisi/telaah_ditolak/'.$this->input->post('posisi'),'refresh');
			}		
		
		}

		### PERBAIKAN
		else if($this->input->post('acc')=="Perbaikan") {
			$data2['telaah_id'] = $this->input->post('telaah_id');
		 	//kode 5 jika telaah di kembalikan untuk di perbaliki
			$data2['telaah_perbaikan'] = 0;
			$data2['telaah_status'] = 5;
			$this->m_telaah->update($data2);
			
			if($this->input->post('telaah_kategori')==1){
				if($this->ion_auth->get_users_groups()->row()->id == 3){
					$data['timeline_sekdis_id'] = 5; 
					$data['timeline_sekdis_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekdis_disposisi'] = $this->input->post('timeline_sekdis_disposisi');
					$this->m_disposisi->update_timeline_1($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('telaah/disposisi/index/sekdis','refresh');
				} else if($this->ion_auth->get_users_groups()->row()->id == 4){
					$data['timeline_kadis_id'] = 5; 
					$data['timeline_kadis_date'] = date("Y-m-d H:i:s");
					$data['timeline_kadis_disposisi'] = $this->input->post('timeline_kadis_disposisi');
					$this->m_disposisi->update_timeline_1($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('kadis/telaah_ditolak','refresh');
				} else {
					$data['timeline_kabid_id'] = 5; 
					$data['timeline_kabid_date'] = date("Y-m-d H:i:s");
					$data['timeline_kabid_disposisi'] = $this->input->post('timeline_kabid_disposisi');
					$this->m_disposisi->update_timeline_1($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('kabid/telaah_ditolak','refresh');
				}
			} else if($this->input->post('telaah_kategori')==2){
				
				if($this->ion_auth->get_users_groups()->row()->id == 3){
					$data['timeline_sekdis_id'] = 5; 
					$data['timeline_sekdis_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekdis_disposisi'] = $this->input->post('timeline_sekdis_disposisi');
					$this->m_disposisi->update_timeline_2($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('sekdis/list_telaah','refresh');
				} else if($this->ion_auth->get_users_groups()->row()->id == 4){
					$data['timeline_kadis_id'] = 5; 
					$data['timeline_kadis_date'] = date("Y-m-d H:i:s");
					$data['timeline_kadis_disposisi'] = $this->input->post('timeline_kadis_disposisi');
					$this->m_disposisi->update_timeline_2($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('kadis/list_telaah','refresh');
				} else if($this->ion_auth->get_users_groups()->row()->id == 6){
					$data['timeline_sekda_id'] = 5; 
					$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekda_disposisi'] = $this->input->post('timeline_sekda_disposisi');
					$this->m_disposisi->update_timeline_2($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('sekda/list_telaah','refresh');
				}  else if($this->ion_auth->get_users_groups()->row()->id == 8){
					$data['timeline_walikota_id'] = 5; 
					$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
					$data['timeline_walikota_disposisi'] = $this->input->post('timeline_walikota_disposisi');
					$this->m_disposisi->update_timeline_2($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('walikota/list_telaah','refresh');
				} else {
					$data['timeline_sekdis_id'] = 5; 
					$data['timeline_sekdis_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekdis_disposisi'] = $this->input->post('timeline_sekdis_disposisi');
					$this->m_disposisi->update_timeline_2($data,'');
				}
			}else if($this->input->post('telaah_kategori')==3){
				
				if($this->ion_auth->get_users_groups()->row()->id == 2){
					$data['timeline_kasubid_id'] = 5; 
					$data['timeline_kasubid_date'] = date("Y-m-d H:i:s");
					$data['timeline_kasubid_disposisi'] = $this->input->post('timeline_kasubid_disposisi');
					$this->m_disposisi->update_timeline_3($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('kabid/telaah_ditolak','refresh');
				} else if($this->ion_auth->get_users_groups()->row()->id == 10){
					$data['timeline_sekwan_id'] = 5; 
					$data['timeline_sekwan_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekwan_disposisi'] = $this->input->post('timeline_sekwan_disposisi');
					$this->m_disposisi->update_timeline_3($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('sekwan/telaah_ditolak','refresh');
				} else if($this->ion_auth->get_users_groups()->row()->id == 7){
					$data['timeline_kadprd_id'] = 5; 
					$data['timeline_kadprd_date'] = date("Y-m-d H:i:s");
					$data['timeline_kadprd_disposisi'] = $this->input->post('timeline_kadprd_disposisi');
					$this->m_disposisi->update_timeline_3($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('kadprd/telaah_ditolak','refresh');
				} else {}
				
			}else if($this->input->post('telaah_kategori')==4){
				if($this->ion_auth->get_users_groups()->row()->id == 2){
					$data['timeline_kabag_id'] = 5; 
					$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
					$data['timeline_kabag_disposisi'] = $this->input->post('timeline_kabag_disposisi');
					$this->m_disposisi->update_timeline_4($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('kabid/telaah_ditolak','refresh');
				} else if($this->ion_auth->get_users_groups()->row()->id == 6){
					$data['timeline_sekda_id'] = 5; 
					$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekda_disposisi'] = $this->input->post('timeline_sekda_disposisi');
					$this->m_disposisi->update_timeline_4($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('sekda/telaah_ditolak','refresh');
				} else if($this->ion_auth->get_users_groups()->row()->id == 5){
					$data['timeline_asisten_id'] = 5; 
					$data['timeline_asisten_date'] = date("Y-m-d H:i:s");
					$data['timeline_asisten_disposisi'] = $this->input->post('timeline_asisten_disposisi');
					$this->m_disposisi->update_timeline_4($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('asisten/telaah_ditolak','refresh');
				} else if($this->ion_auth->get_users_groups()->row()->id == 8){
					$data['timeline_walikota_id'] = 5; 
					$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
					$data['timeline_walikota_disposisi'] = $this->input->post('timeline_walikota_disposisi');
					$this->m_disposisi->update_timeline_4($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('walikota/telaah_ditolak','refresh');
				}
			} else if($this->input->post('telaah_kategori')==6){
				if($this->ion_auth->get_users_groups()->row()->id == 2){
					$data['timeline_kabag_id'] = 5; 
					$data['timeline_kabag_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
					$data['timeline_kabag_disposisi'] = $this->input->post('timeline_kabag_disposisi');
					$this->m_disposisi->update_timeline_6($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('kabid/telaah_ditolak','refresh');
				}  else if($this->ion_auth->get_users_groups()->row()->id == 10){
					$data['timeline_sekwan_id'] = 5; 
					$data['timeline_sekwan_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_sekwan_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekwan_disposisi'] = $this->input->post('timeline_sekwan_disposisi');
					$this->m_disposisi->update_timeline_6($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('sekwan/telaah_ditolak','refresh');
				}
			} else if($this->input->post('telaah_kategori')==5){
				if($this->ion_auth->get_users_groups()->row()->id == 12){
					$data['timeline_sekcam_id'] = 5; 
					$data['timeline_sekcam_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_sekcam_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekcam_disposisi'] = $this->input->post('timeline_sekcam_disposisi');
					$this->m_disposisi->update_timeline_5($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('sekcam/telaah_ditolak','refresh');
				}  else if($this->ion_auth->get_users_groups()->row()->id == 11){
					$data['timeline_camat_id'] = 5; 
					$data['timeline_camat_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_camat_date'] = date("Y-m-d H:i:s");
					$data['timeline_camat_disposisi'] = $this->input->post('timeline_camat_disposisi');
					$this->m_disposisi->update_timeline_5($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('camat/telaah_ditolak','refresh');
				} else if($this->ion_auth->get_users_groups()->row()->id == 6){
					$data['timeline_sekda_id'] = 5; 
					$data['timeline_sekda_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekda_disposisi'] = $this->input->post('timeline_sekda_disposisi');
					$this->m_disposisi->update_timeline_5($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('sekda/telaah_ditolak','refresh');
				} else if($this->ion_auth->get_users_groups()->row()->id == 8){
					$data['timeline_walikota_id'] = 5; 
					$data['timeline_walikota_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
					$data['timeline_walikota_disposisi'] = $this->input->post('timeline_walikota_disposisi');
					$this->m_disposisi->update_timeline_5($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('walikota/telaah_ditolak','refresh');
				}
			} else if($this->input->post('telaah_kategori')==7){
				if($this->ion_auth->get_users_groups()->row()->id == 1){
					$data['timeline_lurah_id'] = 5; 
					$data['timeline_lurah_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_lurah_date'] = date("Y-m-d H:i:s");
					$data['timeline_lurah_disposisi'] = $this->input->post('timeline_lurah_disposisi');
					$this->m_disposisi->update_timeline_7($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('kasubag_camat/telaah_ditolak','refresh');
				}  else if($this->ion_auth->get_users_groups()->row()->id == 12){
					$data['timeline_sekcam_id'] = 5; 
					$data['timeline_sekcam_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_sekcam_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekcam_disposisi'] = $this->input->post('timeline_sekcam_disposisi');
					$this->m_disposisi->update_timeline_7($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('sekcam/telaah_ditolak','refresh');
				} else if($this->ion_auth->get_users_groups()->row()->id ==11){
					$data['timeline_camat_id'] = 5; 
					$data['timeline_camat_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
					$data['timeline_camat_date'] = date("Y-m-d H:i:s");
					$data['timeline_camat_disposisi'] = $this->input->post('timeline_camat_disposisi');
					$this->m_disposisi->update_timeline_7($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('camat/telaah_ditolak','refresh');
				} 
			} else if($this->input->post('telaah_kategori')==8){
				if($this->ion_auth->get_users_groups()->row()->id == 2){
					$data['timeline_kabag_id'] = 5; 
					$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
					$data['timeline_kabag_disposisi'] = $this->input->post('timeline_kabag_disposisi');
					$this->m_disposisi->update_timeline_8($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('kabid/list_telaah/walikota','refresh');
				} else if($this->ion_auth->get_users_groups()->row()->id == 6){
					$data['timeline_sekda_id'] = 5; 
					$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekda_disposisi'] = $this->input->post('timeline_sekda_disposisi');
					$this->m_disposisi->update_timeline_8($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					//redirect('sekda/telaah_ditolak','refresh');
					redirect('sekda/list_telaah/walikota','refresh');
				} else if($this->ion_auth->get_users_groups()->row()->id == 8){
					$data['timeline_walikota_id'] = 5; 
					$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
					$data['timeline_walikota_disposisi'] = $this->input->post('timeline_walikota_disposisi');
					$this->m_disposisi->update_timeline_8($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('walikota/list_telaah/walikota','refresh');
				}
			} else if($this->input->post('telaah_kategori')==9){
				if($this->ion_auth->get_users_groups()->row()->id == 2){
					$data['timeline_kabag_id'] = 5; 
					$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
					$data['timeline_kabag_disposisi'] = $this->input->post('timeline_kabag_disposisi');
					$this->m_disposisi->update_timeline_9($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('kabid/list_telaah','refresh');
				} else if($this->ion_auth->get_users_groups()->row()->id == 6){
					$data['timeline_sekda_id'] = 5; 
					$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekda_disposisi'] = $this->input->post('timeline_sekda_disposisi');
					$this->m_disposisi->update_timeline_9($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('sekda/list_telaah','refresh');
				} else if($this->ion_auth->get_users_groups()->row()->id == 5){
					$data['timeline_asisten_id'] = 5; 
					$data['timeline_asisten_date'] = date("Y-m-d H:i:s");
					$data['timeline_asisten_disposisi'] = $this->input->post('timeline_asisten_disposisi');
					$this->m_disposisi->update_timeline_9($data,'');
					
					$log['kode_log_action'] = "67";
					$log['action'] = "PERBAIKAN";
					$log['kode_log_action_table'] = "16";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('asisten/list_telaah','refresh');
				}
			} else if($this->input->post('telaah_kategori')==10){
				
				if($this->ion_auth->get_users_groups()->row()->id == 2){
					$data['timeline_kabag_id'] = 5; 
					$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
					$data['timeline_kabag_disposisi'] = $this->input->post('timeline_kabag_disposisi');
					$this->m_disposisi->update_timeline_10($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "17";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('kabid/telaah_disetujui','refresh');
				} else if($this->ion_auth->get_users_groups()->row()->id == 10){
					$data['timeline_sekwan_id'] = 5; 
					$data['timeline_sekwan_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekwan_disposisi'] = $this->input->post('timeline_sekwan_disposisi');
					$this->m_disposisi->update_timeline_10($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "17";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('kadis/telaah_disetujui','refresh');
				} else if($this->ion_auth->get_users_groups()->row()->id == 6){
					$data['timeline_sekda_id'] = 5; 
					$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekda_disposisi'] = $this->input->post('timeline_sekda_disposisi');
					$this->m_disposisi->update_timeline_10($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "17";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('sekda/telaah_disetujui','refresh');
				}  else if($this->ion_auth->get_users_groups()->row()->id == 8){
					$data['timeline_walikota_id'] = 5; 
					$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
					$data['timeline_walikota_disposisi'] = $this->input->post('timeline_walikota_disposisi');
					$this->m_disposisi->update_timeline_10($data,'');
					
					//$data2['telaah_id'] = $this->input->post('telaah_id');
					//$data2['telaah_status'] = 2;
				//$this->m_telaah->update($data2);
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "17";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('walikota/telaah_disetujui','refresh');
				} 
			} else if($this->input->post('telaah_kategori')==11){
					$data['timeline_kapus_id'] = 5; 
					$data['timeline_kapus_date'] = date("Y-m-d H:i:s");
					$data['timeline_kapus_disposisi'] = $this->input->post('timeline_kapus_disposisi');
					$this->m_disposisi->update_timeline_11($data,'');
					
					$log['kode_log_action'] = "57";
					$log['action'] = "ACC/DISPOSISI";
					$log['kode_log_action_table'] = "18";
					$log['action_table'] = "Tracking SPPD";
					$this->m_log->create($log);
					
					redirect('kapus/telaah_disetujui','refresh');
			} 
		}
		
	}
	
	public function kuasakan()
	{
		$data['telaah_id'] = $this->input->post('telaah_id');
		$data['telaah_kategori'] = $this->input->post('telaah_kategori');
		$ttd=explode(",",$this->input->post('tanda_tangan_spd'));

		$data['group'] = $ttd[0];
		$data['pegawai_id'] = $ttd[1];
		
		$data['skpd_id'] = $this->ion_auth->user()->row()->skpd_id;
		$data['jenis_skpd'] = $this->ion_auth->user()->row()->jenis_skpd;
		$this->m_disposisi->kuasakan($data);
		
		$log['kode_log_action'] = "100";
		$log['action'] = "KUASAKAN";
		$log['kode_log_action_table'] = "100";
		$log['action_table'] = "TABLE TTE";
		$this->m_log->create($log);
		
		if($this->input->post('telaah_kategori')==1 && $this->input->post('posisi')=='kadis'){
			$data3['telaah_id'] = $this->input->post('telaah_id');
			$data3['timeline_kadis_id'] = 1; 
			$data3['timeline_kadis_date'] = date("Y-m-d H:i:s");
			$data3['timeline_kadis_disposisi'] = "ACC";
			$this->m_disposisi->update_timeline_1($data3, '');
			
			$data4['telaah_id'] = $this->input->post('telaah_id');
			$data4['telaah_status'] = 2;
			$this->m_disposisi->update_timeline_1($data3, $data4);
			
			$log['kode_log_action'] = "57";
			$log['action'] = "ACC/DISPOSISI";
			$log['kode_log_action_table'] = "16";
			$log['action_table'] = "Tracking SPPD";
			$this->m_log->create($log);
		} 
		if($this->input->post('telaah_kategori')==2 && $this->input->post('posisi')=='kadis'){
			$data3['telaah_id'] = $this->input->post('telaah_id');
			$data3['timeline_kadis_id'] = 1; 
			$data3['timeline_kadis_date'] = date("Y-m-d H:i:s");
			$data3['timeline_kadis_disposisi'] = "ACC";
			$this->m_disposisi->update_timeline_2($data3,'');
			
			$data4['telaah_id'] = $this->input->post('telaah_id');
			$data4['telaah_status'] = 2;
			$this->m_disposisi->update_timeline_2($data3, $data4);
			
			$log['kode_log_action'] = "57";
			$log['action'] = "ACC/DISPOSISI";
			$log['kode_log_action_table'] = "16";
			$log['action_table'] = "Tracking SPPD";
			$this->m_log->create($log);
		} 

		redirect('telaah/disposisi/telaah_disetujui/'.$this->input->post('posisi'));
	}
	
	function tampilkan_laporan_sementara(){
		
		$telaah_id = base64_encode($this->encrypt->encode($this->uri->segment(6), $this->session->userdata('encrypt_key')));	
  								
		$posisi = $this->uri->segment(4);
		$kategori = $this->uri->segment(5);
		$telaah = $this->uri->segment(6);
		$pelaksana = $this->uri->segment(7);
		$pegawai = $this->uri->segment(8);
		
		echo "<div class='nav-tabs-custom' id='hasil'>
		<ul class='nav nav-tabs'>
		  <li class='active'><a href='#tab_1' data-toggle='tab'>SPT</a></li>
		  <li><a href='#tab_2' data-toggle='tab'>SPPD</a></li>
		</ul>
		<div class='tab-content'>
		  <div class='pull-right'><a href='".base_url()."telaah/disposisi/detail/".$this->uri->segment(4)."/".$this->uri->segment(5)."?telaah_id=".$telaah_id."' class='btn btn-primary'> Lihat Detail Perjalanan</a></div><br><br>
		  <div class='tab-pane active' id='tab_1'>
			<object data='".base_url()."upload/doc_perjalanan/SPT-$telaah-$pelaksana.pdf' type='application/pdf' width='100%' height='700px'>
				<p>Preview dokumen tidak tersedia. Silakan <a href='".base_url()."upload/doc_perjalanan/SPT-$telaah-$pelaksana.pdf' target='_blank'>Download Dokumen SPT</a>.</p>
			</object>
		  </div>
		  <!-- /.tab-pane -->
		  <div class='tab-pane' id='tab_2'>
			<object data='".base_url()."upload/doc_perjalanan/SPPD-$telaah-$pegawai.pdf' type='application/pdf' width='100%' height='700px'>
				<p>Preview dokumen tidak tersedia. Silakan <a href='".base_url()."upload/doc_perjalanan/SPPD-$telaah-$pegawai.pdf' target='_blank'>Download Dokumen SPPD</a>.</p>
			</object>
		  </div>
		</div>
	  </div>";
	}
}