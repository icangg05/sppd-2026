<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Beranda extends public_Controller {
	public function dd($data) {
		echo "<pre>";
		print_r($data);
		echo "</pre>";
		die();
	}

	function __construct()
	{
		parent::__construct();
		// error_reporting(0);
		
		// Cek login terlebih dahulu sebelum load model yang berat
		if (!$this->ion_auth->logged_in())
		{
			redirect('login', 'refresh');
		}

		$this->load->model('setting/m_menu');
		$this->load->model('setting_admin/m_anggaran');
		$this->load->model('telaah/m_telaah');
		$this->load->model('telaah/m_telaah_disetujui');
		$this->load->model('telaah/m_telaah_ditolak');
		$this->load->model('telaah/m_esselon');
		$this->load->model('telaah/m_kadis');
		$this->load->model('telaah/m_dprd');
		$this->load->model('telaah/m_staff_dprd');
		$this->load->model('telaah/m_sekda');
		$this->load->model('telaah/m_sekwan');
		$this->load->model('telaah/m_camat');
		$this->load->model('telaah/m_lurah');
		$this->load->model('telaah/m_staff_camat');
		$this->load->model('telaah/m_staff_lurah');
		$this->load->model('telaah/m_kapus');
		$this->load->model('setting_root/m_admin');
		$this->load->model('telaah/m_relasi_sekda');
		$this->load->model('telaah/m_walikota');
		$this->load->model('api/m_api');
		$this->load->model('m_beranda');
		$this->load->model('setting_admin/m_pegawai');
		$this->data['side'] = $this->m_menu->getActiveMenu();
		// if(!($this->ion_auth->user()->row()->id)){
		// 	redirect('login');
		// }
	}
	
	public function index()
	{
		if($this->ion_auth->user()->row() && $this->ion_auth->user()->row()->id){
			// 1 (Kasubid / kasubag) (OPD)
			if($this->ion_auth->get_users_groups()->row()->id == 1 && $this->ion_auth->user()->row()->jenis_skpd == 1){
				$this->data['count_perjalanan'] = $this->m_beranda->count_perjalanan('','','');
				$this->data['anggaran'] = $this->m_beranda->anggaran('','',$this->ion_auth->user()->row()->skpd_id);
				$sum_all_rincian_skpd = $this->m_beranda->sum_all_rincian_skpd();
				$sum_all_pengeluaran_rill_skpd = $this->m_beranda->sum_all_pengeluaran_rill_skpd();
				$this->data['sum_all_rincian_skpd'] = $sum_all_rincian_skpd +  $sum_all_pengeluaran_rill_skpd;
				$this->data['sum_all_anggaran_skpd'] = $this->m_beranda->sum_all_anggaran_skpd();
				if($this->data['sum_all_anggaran_skpd'] > 0){

					$this->data['anggaran_terpakai'] = ($this->data['sum_all_rincian_skpd']/$this->data['sum_all_anggaran_skpd'])*100;

					$this->data['anggaran_tersedia'] = (($this->data['sum_all_anggaran_skpd']-$this->data['sum_all_rincian_skpd'])/$this->data['sum_all_anggaran_skpd'])*100;

				} else {

					$this->data['anggaran_terpakai'] = 0;

					$this->data['anggaran_tersedia'] = 0;

				}
				$this->data['total_pegawai'] = $this->m_esselon->total_pegawai($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_esselon'] = $this->m_esselon->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses_esselon'] = $this->m_esselon->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima_esselon'] = $this->m_esselon->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak_esselon'] = $this->m_esselon->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_kaopd'] = $this->m_kadis->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses_kaopd'] = $this->m_kadis->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima_kaopd'] = $this->m_kadis->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak_kaopd'] = $this->m_kadis->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
				$this->data['posisi_kaopd'] = $this->m_admin->posisi_kaopd($this->ion_auth->user()->row()->skpd_id);
			// 1 (Kasubid / kasubag) (Puskesmas)
			} else if($this->ion_auth->get_users_groups()->row()->id == 1 && $this->ion_auth->user()->row()->jenis_skpd == 7){
				$this->data['count_perjalanan'] = $this->m_beranda->count_perjalanan($this->ion_auth->user()->row()->jenis_skpd,'','');
				$this->data['anggaran'] = $this->m_beranda->anggaran('','',$this->ion_auth->user()->row()->skpd_id);
				$sum_all_rincian_skpd = $this->m_beranda->sum_all_rincian_puskesmas();
				$sum_all_pengeluaran_rill_skpd = $this->m_beranda->sum_all_pengeluaran_rill_puskesmas();
				if($sum_all_rincian_skpd && $sum_all_pengeluaran_rill_skpd){
					$this->data['sum_all_rincian_skpd'] = $sum_all_rincian_skpd +  $sum_all_pengeluaran_rill_skpd;
					$this->data['sum_all_anggaran_skpd'] = $this->m_beranda->sum_all_anggaran_skpd();
					if($this->data['sum_all_anggaran_skpd'] > 0){

						$this->data['anggaran_terpakai'] = ($this->data['sum_all_rincian_skpd']/$this->data['sum_all_anggaran_skpd'])*100;

						$this->data['anggaran_tersedia'] = (($this->data['sum_all_anggaran_skpd']-$this->data['sum_all_rincian_skpd'])/$this->data['sum_all_anggaran_skpd'])*100;

					} else {

						$this->data['anggaran_terpakai'] = 0;

						$this->data['anggaran_tersedia'] = 0;

					}
				} else {
					$this->data['sum_all_rincian_skpd'] = 0;
					$this->data['sum_all_anggaran_skpd'] = 0;
					$this->data['anggaran_terpakai'] =0;
					$this->data['anggaran_tersedia'] = 0;
				}
				$this->data['total_pegawai'] = $this->m_kapus->total_pegawai($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah'] = $this->m_kapus->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses'] = $this->m_kapus->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima'] = $this->m_kapus->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak'] = $this->m_kapus->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_bok'] = $this->m_esselon->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses_bok'] = $this->m_esselon->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima_bok'] = $this->m_esselon->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak_bok'] = $this->m_esselon->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
				$this->data['posisi_kaopd'] = $this->m_admin->posisi_kaopd($this->ion_auth->user()->row()->skpd_id);
			// 1 (Kasubid / kasubag) (Dinkes)
			} else if($this->ion_auth->get_users_groups()->row()->id == 1 && $this->ion_auth->user()->row()->jenis_skpd == 10){
				$this->data['count_perjalanan'] = $this->m_beranda->count_perjalanan($this->ion_auth->user()->row()->jenis_skpd,'','');
				$this->data['anggaran'] = $this->m_beranda->anggaran('','',$this->ion_auth->user()->row()->skpd_id);
				$sum_all_rincian_skpd = $this->m_beranda->sum_all_rincian_skpd();
				$sum_all_pengeluaran_rill_skpd = $this->m_beranda->sum_all_pengeluaran_rill_skpd();
				$this->data['sum_all_rincian_skpd'] = $sum_all_rincian_skpd +  $sum_all_pengeluaran_rill_skpd;
				$this->data['sum_all_anggaran_skpd'] = $this->m_beranda->sum_all_anggaran_skpd();
				if($this->data['sum_all_anggaran_skpd'] > 0){

					$this->data['anggaran_terpakai'] = ($this->data['sum_all_rincian_skpd']/$this->data['sum_all_anggaran_skpd'])*100;

					$this->data['anggaran_tersedia'] = (($this->data['sum_all_anggaran_skpd']-$this->data['sum_all_rincian_skpd'])/$this->data['sum_all_anggaran_skpd'])*100;

				} else {

					$this->data['anggaran_terpakai'] = 0;

					$this->data['anggaran_tersedia'] = 0;

				}
				$this->data['total_pegawai'] = $this->m_esselon->total_pegawai($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_esselon'] = $this->m_esselon->total_list_telaah_dinkes();
				$this->data['total_list_telaah_diproses_esselon'] = $this->m_esselon->total_list_telaah_diproses_dinkes();
				$this->data['total_list_telaah_diterima_esselon'] = $this->m_esselon->total_list_telaah_diterima_dinkes();
				$this->data['total_list_telaah_ditolak_esselon'] = $this->m_esselon->total_list_telaah_ditolak_dinkes();
				$this->data['total_list_telaah_kaopd'] = $this->m_kadis->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses_kaopd'] = $this->m_kadis->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima_kaopd'] = $this->m_kadis->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak_kaopd'] = $this->m_kadis->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
				$this->data['posisi_kaopd'] = $this->m_admin->posisi_kaopd($this->ion_auth->user()->row()->skpd_id);
			// 1 (Kasubid / kasubag) (DPRD)
			} else if($this->ion_auth->get_users_groups()->row()->id == 1 && $this->ion_auth->user()->row()->jenis_skpd == 2){
				$this->data['count_perjalanan']      = $this->m_beranda->count_perjalanan($this->ion_auth->user()->row()->jenis_skpd,'','');
				$this->data['anggaran']              = $this->m_beranda->anggaran('','',$this->ion_auth->user()->row()->skpd_id);
				$sum_all_rincian_skpd                = $this->m_beranda->sum_all_rincian_skpd();
				$sum_all_pengeluaran_rill_skpd       = $this->m_beranda->sum_all_pengeluaran_rill_skpd();
				$this->data['sum_all_rincian_skpd']  = $sum_all_rincian_skpd +  $sum_all_pengeluaran_rill_skpd;
				$this->data['sum_all_anggaran_skpd'] = $this->m_beranda->sum_all_anggaran_skpd();
				if($this->data['sum_all_anggaran_skpd'] > 0){

					$this->data['anggaran_terpakai'] = ($this->data['sum_all_rincian_skpd']/$this->data['sum_all_anggaran_skpd'])*100;

					$this->data['anggaran_tersedia'] = (($this->data['sum_all_anggaran_skpd']-$this->data['sum_all_rincian_skpd'])/$this->data['sum_all_anggaran_skpd'])*100;

				} else {

					$this->data['anggaran_terpakai'] = 0;

					$this->data['anggaran_tersedia'] = 0;

				}
				$this->data['posisi_kadprd']                         = $this->m_admin->posisi_kadprd();
				$this->data['total_pegawai']                         = $this->m_dprd->total_pegawai();
				$this->data['total_list_telaah_dprd']                = $this->m_dprd->total_list_telaah();
				$this->data['total_list_telaah_diproses_dprd']       = $this->m_dprd->total_list_telaah_diproses();
				$this->data['total_list_telaah_diterima_dprd']       = $this->m_dprd->total_list_telaah_diterima();
				$this->data['total_list_telaah_ditolak_dprd']        = $this->m_dprd->total_list_telaah_ditolak();
				$this->data['total_list_telaah_staff_dprd']          = $this->m_staff_dprd->total_list_telaah();
				$this->data['total_list_telaah_diproses_staff_dprd'] = $this->m_staff_dprd->total_list_telaah_diproses();
				$this->data['total_list_telaah_diterima_staff_dprd'] = $this->m_staff_dprd->total_list_telaah_diterima();
				$this->data['total_list_telaah_ditolak_staff_dprd']  = $this->m_staff_dprd->total_list_telaah_ditolak();
				$this->data['total_list_telaah_sekwan']              = $this->m_sekwan->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses_sekwan']     = $this->m_sekwan->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima_sekwan']     = $this->m_sekwan->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak_sekwan']      = $this->m_sekwan->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
			// 1 (Kasubid / kasubag) (Sekda)
			} else if($this->ion_auth->get_users_groups()->row()->id == 1 && $this->ion_auth->user()->row()->jenis_skpd == 3){
				$staff_sekda = $this->m_relasi_sekda->getsubbagian($this->ion_auth->user()->row()->id);
				if($staff_sekda){
					$this->data['total_pegawai'] = $this->m_sekda->total_pegawai_sekda($staff_sekda[0]['bagian_id']);
				} else {
					$this->data['total_pegawai'] = $this->m_sekda->total_pegawai();
				}
				$this->data['count_perjalanan'] = $this->m_beranda->count_perjalanan($this->ion_auth->user()->row()->jenis_skpd,$staff_sekda[0]['bagian_id'],'');
				$this->data['anggaran'] = $this->m_beranda->anggaran_setda($staff_sekda[0]['bagian_id']);
				$sum_all_rincian_skpd = $this->m_beranda->sum_all_rincian_setda($staff_sekda[0]['bagian_id']);
				$sum_all_pengeluaran_rill_skpd = $this->m_beranda->sum_all_pengeluaran_rill_setda($staff_sekda[0]['bagian_id']);
				$this->data['sum_all_rincian_skpd'] = $sum_all_rincian_skpd + $sum_all_pengeluaran_rill_skpd;
				$this->data['sum_all_anggaran_skpd'] = $this->m_beranda->sum_all_anggaran_setda($staff_sekda[0]['bagian_id']);
				if($this->data['sum_all_anggaran_skpd'] > 0){

					$this->data['anggaran_terpakai'] = ($this->data['sum_all_rincian_skpd']/$this->data['sum_all_anggaran_skpd'])*100;

					$this->data['anggaran_tersedia'] = (($this->data['sum_all_anggaran_skpd']-$this->data['sum_all_rincian_skpd'])/$this->data['sum_all_anggaran_skpd'])*100;

				} else {

					$this->data['anggaran_terpakai'] = 0;

					$this->data['anggaran_tersedia'] = 0;

				}
				$this->data['total_list_telaah_walikota'] = $this->m_walikota->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses_walikota'] = $this->m_walikota->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima_walikota'] = $this->m_walikota->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak_walikota'] = $this->m_walikota->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah'] = $this->m_sekda->total_list_telaah_sekda($staff_sekda[0]['subbagian_id']);
				$this->data['total_list_telaah_diproses'] = $this->m_sekda->total_list_telaah_diproses($staff_sekda[0]['subbagian_id']);
				$this->data['total_list_telaah_diterima'] = $this->m_sekda->total_list_telaah_diterima_sekda($staff_sekda[0]['subbagian_id']);
				$this->data['total_list_telaah_ditolak'] = $this->m_sekda->total_list_telaah_ditolak_sekda($staff_sekda[0]['subbagian_id']);
				$this->data['total_list_telaah_staff_sekda'] = $this->m_sekda->total_list_telaah_staff($staff_sekda[0]['bagian_id']);
				$this->data['total_list_telaah_diproses_staff_sekda'] = $this->m_sekda->total_list_telaah_diproses_staff($staff_sekda[0]['bagian_id']);
				$this->data['total_list_telaah_diterima_staff_sekda'] = $this->m_sekda->total_list_telaah_diterima_staff($staff_sekda[0]['bagian_id']);
				$this->data['total_list_telaah_ditolak_staff_sekda'] = $this->m_sekda->total_list_telaah_ditolak_staff($staff_sekda[0]['bagian_id']);
				$this->data['posisi_sekda'] = $this->m_admin->posisi_sekda();

			// 1 (Kasubid / kasubag) (Camat)
			} else if($this->ion_auth->get_users_groups()->row()->id == 1 && $this->ion_auth->user()->row()->jenis_skpd == 4){
				$this->data['count_perjalanan'] = $this->m_beranda->count_perjalanan('','','');
				$this->data['anggaran'] = $this->m_beranda->anggaran('','',$this->ion_auth->user()->row()->skpd_id);
				$sum_all_rincian_skpd = $this->m_beranda->sum_all_rincian_skpd();
				$sum_all_pengeluaran_rill_skpd = $this->m_beranda->sum_all_pengeluaran_rill_skpd();
				$this->data['sum_all_rincian_skpd'] = $sum_all_rincian_skpd +  $sum_all_pengeluaran_rill_skpd;
				$this->data['sum_all_anggaran_skpd'] = $this->m_beranda->sum_all_anggaran_skpd();
				if($this->data['sum_all_anggaran_skpd'] > 0){

					$this->data['anggaran_terpakai'] = ($this->data['sum_all_rincian_skpd']/$this->data['sum_all_anggaran_skpd'])*100;

					$this->data['anggaran_tersedia'] = (($this->data['sum_all_anggaran_skpd']-$this->data['sum_all_rincian_skpd'])/$this->data['sum_all_anggaran_skpd'])*100;

				} else {

					$this->data['anggaran_terpakai'] = 0;

					$this->data['anggaran_tersedia'] = 0;

				}
				$this->data['total_pegawai'] = $this->m_camat->total_pegawai($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah'] = $this->m_camat->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses'] = $this->m_camat->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima'] = $this->m_camat->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak'] = $this->m_camat->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_staff_camat'] = $this->m_staff_camat->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses_staff_camat'] = $this->m_staff_camat->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima_staff_camat'] = $this->m_staff_camat->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak_staff_camat'] = $this->m_staff_camat->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
				$this->data['posisi_kaopd'] = $this->m_admin->posisi_kaopd($this->ion_auth->user()->row()->skpd_id);
			// 5 (Lurah)
			} else if($this->ion_auth->get_users_groups()->row()->id == 13 && $this->ion_auth->user()->row()->jenis_skpd == 5){
				$this->data['count_perjalanan'] = $this->m_beranda->count_perjalanan('','','');
				$this->data['anggaran'] = $this->m_beranda->anggaran('','',$this->ion_auth->user()->row()->skpd_id);
				$sum_all_rincian_skpd = $this->m_beranda->sum_all_rincian_skpd();
				$sum_all_pengeluaran_rill_skpd = $this->m_beranda->sum_all_pengeluaran_rill_skpd();
				$this->data['sum_all_rincian_skpd'] = $sum_all_rincian_skpd +  $sum_all_pengeluaran_rill_skpd;
				$this->data['sum_all_anggaran_skpd'] = $this->m_beranda->sum_all_anggaran_skpd();
				if($this->data['sum_all_anggaran_skpd'] > 0){

					$this->data['anggaran_terpakai'] = ($this->data['sum_all_rincian_skpd']/$this->data['sum_all_anggaran_skpd'])*100;

					$this->data['anggaran_tersedia'] = (($this->data['sum_all_anggaran_skpd']-$this->data['sum_all_rincian_skpd'])/$this->data['sum_all_anggaran_skpd'])*100;

				} else {

					$this->data['anggaran_terpakai'] = 0;

					$this->data['anggaran_tersedia'] = 0;

				}
				$this->data['total_pegawai'] = $this->m_lurah->total_pegawai($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah'] = $this->m_lurah->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses'] = $this->m_lurah->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima'] = $this->m_lurah->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak'] = $this->m_lurah->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_staff_lurah'] = $this->m_staff_lurah->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses_staff_lurah'] = $this->m_staff_lurah->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima_staff_lurah'] = $this->m_staff_lurah->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak_staff_lurah'] = $this->m_staff_lurah->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
			// 14 (Bendahara Camat) (Staff Camat)
			} else if($this->ion_auth->get_users_groups()->row()->id == 14 && $this->ion_auth->user()->row()->jenis_skpd == 4){
				$this->data['count_perjalanan'] = $this->m_beranda->count_perjalanan('','','');
				$this->data['anggaran'] = $this->m_beranda->anggaran('','',$this->ion_auth->user()->row()->skpd_id);
				$sum_all_rincian_skpd = $this->m_beranda->sum_all_rincian_skpd();
				$sum_all_pengeluaran_rill_skpd = $this->m_beranda->sum_all_pengeluaran_rill_skpd();
				$this->data['sum_all_rincian_skpd'] = $sum_all_rincian_skpd +  $sum_all_pengeluaran_rill_skpd;
				$this->data['sum_all_anggaran_skpd'] = $this->m_beranda->sum_all_anggaran_skpd();
				if($this->data['sum_all_anggaran_skpd'] > 0){

					$this->data['anggaran_terpakai'] = ($this->data['sum_all_rincian_skpd']/$this->data['sum_all_anggaran_skpd'])*100;

					$this->data['anggaran_tersedia'] = (($this->data['sum_all_anggaran_skpd']-$this->data['sum_all_rincian_skpd'])/$this->data['sum_all_anggaran_skpd'])*100;

				} else {

					$this->data['anggaran_terpakai'] = 0;

					$this->data['anggaran_tersedia'] = 0;

				}
				$this->data['total_pegawai'] = $this->m_camat->total_pegawai($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_staff_camat'] = $this->m_staff_camat->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses_staff_camat'] = $this->m_staff_camat->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima_staff_camat'] = $this->m_staff_camat->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak_staff_camat'] = $this->m_staff_camat->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_staff_camat'] = $this->m_staff_camat->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses_staff_camat'] = $this->m_staff_camat->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima_staff_camat'] = $this->m_staff_camat->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak_staff_camat'] = $this->m_staff_camat->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
				$this->data['posisi_kaopd'] = $this->m_admin->posisi_kaopd($this->ion_auth->user()->row()->skpd_id);
			// 14 (Bendahara Lurah) (Staff Lurah)
			} else if($this->ion_auth->get_users_groups()->row()->id == 15 && $this->ion_auth->user()->row()->jenis_skpd == 5){
				$this->data['count_perjalanan'] = $this->m_beranda->count_perjalanan('','','');
				$this->data['anggaran'] = $this->m_beranda->anggaran('','',$this->ion_auth->user()->row()->skpd_id);
				$sum_all_rincian_skpd = $this->m_beranda->sum_all_rincian_skpd();
				$sum_all_pengeluaran_rill_skpd = $this->m_beranda->sum_all_pengeluaran_rill_skpd();
				$this->data['sum_all_rincian_skpd'] = $sum_all_rincian_skpd +  $sum_all_pengeluaran_rill_skpd;
				$this->data['sum_all_anggaran_skpd'] = $this->m_beranda->sum_all_anggaran_skpd();
				if($this->data['sum_all_anggaran_skpd'] > 0){

					$this->data['anggaran_terpakai'] = ($this->data['sum_all_rincian_skpd']/$this->data['sum_all_anggaran_skpd'])*100;

					$this->data['anggaran_tersedia'] = (($this->data['sum_all_anggaran_skpd']-$this->data['sum_all_rincian_skpd'])/$this->data['sum_all_anggaran_skpd'])*100;

				} else {

					$this->data['anggaran_terpakai'] = 0;

					$this->data['anggaran_tersedia'] = 0;

				}
				$this->data['total_pegawai'] = $this->m_lurah->total_pegawai($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_staff_lurah'] = $this->m_staff_lurah->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses_staff_lurah'] = $this->m_staff_lurah->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima_staff_lurah'] = $this->m_staff_lurah->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak_staff_lurah'] = $this->m_staff_lurah->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_staff_lurah'] = $this->m_staff_lurah->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses_staff_lurah'] = $this->m_staff_lurah->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima_staff_lurah'] = $this->m_staff_lurah->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak_staff_lurah'] = $this->m_staff_lurah->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
			// 9 (Administrator Full)
			} else if(($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->skpd_id=="")||($this->ion_auth->get_users_groups()->row()->id == 100)){
				$this->data['total_pegawai'] = $this->m_admin->total_pegawai();
				$this->data['total_skpd'] = $this->m_admin->total_skpd();
				$this->data['total_list_telaah'] = $this->m_admin->total_list_telaah();
				$this->data['total_list_telaah_diterima'] = $this->m_admin->total_list_telaah_diterima();
				$this->data['total_list_telaah_ditolak'] = $this->m_admin->total_list_telaah_ditolak();
			// 9 (Admin OPD)
			} else if($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->jenis_skpd==1){
				$this->data['count_perjalanan'] = $this->m_beranda->count_perjalanan('','','');
				$this->data['anggaran'] = $this->m_beranda->anggaran('','',$this->ion_auth->user()->row()->skpd_id);
				$sum_all_rincian_skpd = $this->m_beranda->sum_all_rincian_skpd();
				$sum_all_pengeluaran_rill_skpd = $this->m_beranda->sum_all_pengeluaran_rill_skpd();
				$this->data['sum_all_rincian_skpd'] = $sum_all_rincian_skpd +  $sum_all_pengeluaran_rill_skpd;
				$this->data['sum_all_anggaran_skpd'] = $this->m_beranda->sum_all_anggaran_skpd();
				if($this->data['sum_all_anggaran_skpd'] > 0){

					$this->data['anggaran_terpakai'] = ($this->data['sum_all_rincian_skpd']/$this->data['sum_all_anggaran_skpd'])*100;

					$this->data['anggaran_tersedia'] = (($this->data['sum_all_anggaran_skpd']-$this->data['sum_all_rincian_skpd'])/$this->data['sum_all_anggaran_skpd'])*100;

				} else {

					$this->data['anggaran_terpakai'] = 0;

					$this->data['anggaran_tersedia'] = 0;

				}
				$this->data['total_pegawai'] = $this->m_esselon->total_pegawai($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_esselon'] = $this->m_esselon->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses_esselon'] = $this->m_esselon->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima_esselon'] = $this->m_esselon->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak_esselon'] = $this->m_esselon->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_kaopd'] = $this->m_kadis->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses_kaopd'] = $this->m_kadis->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima_kaopd'] = $this->m_kadis->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak_kaopd'] = $this->m_kadis->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
				$this->data['posisi_kaopd'] = $this->m_admin->posisi_kaopd($this->ion_auth->user()->row()->skpd_id);
			// 9 (Admin Dinkes)
			} else if($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->jenis_skpd==10){
				$this->data['count_perjalanan'] = $this->m_beranda->count_perjalanan($this->ion_auth->user()->row()->jenis_skpd,'','');
				$this->data['anggaran'] = $this->m_beranda->anggaran('','',$this->ion_auth->user()->row()->skpd_id);
				$sum_all_rincian_skpd = $this->m_beranda->sum_all_rincian_skpd();
				$sum_all_pengeluaran_rill_skpd = $this->m_beranda->sum_all_pengeluaran_rill_skpd();
				$this->data['sum_all_rincian_skpd'] = $sum_all_rincian_skpd +  $sum_all_pengeluaran_rill_skpd;
				$this->data['sum_all_anggaran_skpd'] = $this->m_beranda->sum_all_anggaran_skpd();
				if($this->data['sum_all_anggaran_skpd'] > 0){

					$this->data['anggaran_terpakai'] = ($this->data['sum_all_rincian_skpd']/$this->data['sum_all_anggaran_skpd'])*100;

					$this->data['anggaran_tersedia'] = (($this->data['sum_all_anggaran_skpd']-$this->data['sum_all_rincian_skpd'])/$this->data['sum_all_anggaran_skpd'])*100;

				} else {

					$this->data['anggaran_terpakai'] = 0;

					$this->data['anggaran_tersedia'] = 0;

				}
				$this->data['total_pegawai'] = $this->m_esselon->total_pegawai($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_esselon'] = $this->m_esselon->total_list_telaah_dinkes();
				$this->data['total_list_telaah_diproses_esselon'] = $this->m_esselon->total_list_telaah_diproses_dinkes();
				$this->data['total_list_telaah_diterima_esselon'] = $this->m_esselon->total_list_telaah_diterima_dinkes();
				$this->data['total_list_telaah_ditolak_esselon'] = $this->m_esselon->total_list_telaah_ditolak_dinkes();
				$this->data['total_list_telaah_kaopd'] = $this->m_kadis->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses_kaopd'] = $this->m_kadis->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima_kaopd'] = $this->m_kadis->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak_kaopd'] = $this->m_kadis->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
				$this->data['posisi_kaopd'] = $this->m_admin->posisi_kaopd($this->ion_auth->user()->row()->skpd_id);
			// 9 (Admin OPD)(DPRD)
			} else if ($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->jenis_skpd==2){
				$this->data['count_perjalanan'] = $this->m_beranda->count_perjalanan($this->ion_auth->user()->row()->jenis_skpd,'','');
				$this->data['anggaran'] = $this->m_beranda->anggaran('','',$this->ion_auth->user()->row()->skpd_id);
				$sum_all_rincian_skpd = $this->m_beranda->sum_all_rincian_skpd();
				$sum_all_pengeluaran_rill_skpd = $this->m_beranda->sum_all_pengeluaran_rill_skpd();
				$this->data['sum_all_rincian_skpd'] = $sum_all_rincian_skpd +  $sum_all_pengeluaran_rill_skpd;
				$this->data['sum_all_anggaran_skpd'] = $this->m_beranda->sum_all_anggaran_skpd();
				if($this->data['sum_all_anggaran_skpd'] > 0){

					$this->data['anggaran_terpakai'] = ($this->data['sum_all_rincian_skpd']/$this->data['sum_all_anggaran_skpd'])*100;

					$this->data['anggaran_tersedia'] = (($this->data['sum_all_anggaran_skpd']-$this->data['sum_all_rincian_skpd'])/$this->data['sum_all_anggaran_skpd'])*100;

				} else {

					$this->data['anggaran_terpakai'] = 0;

					$this->data['anggaran_tersedia'] = 0;

				}
				$this->data['total_pegawai'] = $this->m_dprd->total_pegawai();
				$this->data['total_list_telaah_dprd'] = $this->m_dprd->total_list_telaah();
				$this->data['total_list_telaah_diproses_dprd'] = $this->m_dprd->total_list_telaah_diproses();
				$this->data['total_list_telaah_diterima_dprd'] = $this->m_dprd->total_list_telaah_diterima();
				$this->data['total_list_telaah_ditolak_dprd'] = $this->m_dprd->total_list_telaah_ditolak();
				$this->data['total_list_telaah_staff_dprd'] = $this->m_staff_dprd->total_list_telaah();
				$this->data['total_list_telaah_diproses_staff_dprd'] = $this->m_staff_dprd->total_list_telaah_diproses();
				$this->data['total_list_telaah_diterima_staff_dprd'] = $this->m_staff_dprd->total_list_telaah_diterima();
				$this->data['total_list_telaah_ditolak_staff_dprd'] = $this->m_staff_dprd->total_list_telaah_ditolak();
				$this->data['total_list_telaah_sekwan'] = $this->m_sekwan->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses_sekwan'] = $this->m_sekwan->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima_sekwan'] = $this->m_sekwan->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak_sekwan'] = $this->m_sekwan->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
				$this->data['posisi_kadprd'] = $this->m_admin->posisi_kadprd();
			// 9 (Admin OPD)(Sekda)
			} else if ($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->jenis_skpd==3){
				$staff_sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
				if($staff_sekda){
					$this->data['total_pegawai'] = $this->m_sekda->total_pegawai_sekda($staff_sekda[0]['bagian_id']);
				} else {
					$this->data['total_pegawai'] = $this->m_sekda->total_pegawai();
				}
				$this->data['count_perjalanan'] = $this->m_beranda->count_perjalanan($this->ion_auth->user()->row()->jenis_skpd,$staff_sekda[0]['bagian_id'],'');
				$this->data['anggaran'] = $this->m_beranda->anggaran_setda($staff_sekda[0]['bagian_id']);
				$sum_all_rincian_skpd = $this->m_beranda->sum_all_rincian_setda($staff_sekda[0]['bagian_id']);
				$sum_all_pengeluaran_rill_skpd = $this->m_beranda->sum_all_pengeluaran_rill_setda($staff_sekda[0]['bagian_id']);
				$this->data['sum_all_rincian_skpd'] = $sum_all_rincian_skpd +  $sum_all_pengeluaran_rill_skpd;
				$this->data['sum_all_anggaran_skpd'] = $this->m_beranda->sum_all_anggaran_setda($staff_sekda[0]['bagian_id']);
				if($this->data['sum_all_anggaran_skpd'] > 0){

					$this->data['anggaran_terpakai'] = ($this->data['sum_all_rincian_skpd']/$this->data['sum_all_anggaran_skpd'])*100;

					$this->data['anggaran_tersedia'] = (($this->data['sum_all_anggaran_skpd']-$this->data['sum_all_rincian_skpd'])/$this->data['sum_all_anggaran_skpd'])*100;

				} else {

					$this->data['anggaran_terpakai'] = 0;

					$this->data['anggaran_tersedia'] = 0;

				}
				$this->data['total_list_telaah_walikota'] = $this->m_walikota->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses_walikota'] = $this->m_walikota->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima_walikota'] = $this->m_walikota->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak_walikota'] = $this->m_walikota->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah'] = $this->m_sekda->total_list_telaah();
				$this->data['total_list_telaah_diproses'] = $this->m_sekda->total_list_telaah_diproses();
				$this->data['total_list_telaah_diterima'] = $this->m_sekda->total_list_telaah_diterima();
				$this->data['total_list_telaah_ditolak'] = $this->m_sekda->total_list_telaah_ditolak();
				$this->data['posisi_sekda'] = $this->m_admin->posisi_sekda();
			// 9 (Admin OPD)(Kecamatan)
			} else if ($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->jenis_skpd==4){
				$this->data['count_perjalanan'] = $this->m_beranda->count_perjalanan('','','');
				$this->data['anggaran'] = $this->m_beranda->anggaran('','',$this->ion_auth->user()->row()->skpd_id);
				$sum_all_rincian_skpd = $this->m_beranda->sum_all_rincian_skpd();
				$sum_all_pengeluaran_rill_skpd = $this->m_beranda->sum_all_pengeluaran_rill_skpd();
				$this->data['sum_all_rincian_skpd'] = $sum_all_rincian_skpd +  $sum_all_pengeluaran_rill_skpd;
				$this->data['sum_all_anggaran_skpd'] = $this->m_beranda->sum_all_anggaran_skpd();
				if($this->data['sum_all_anggaran_skpd'] > 0){

					$this->data['anggaran_terpakai'] = ($this->data['sum_all_rincian_skpd']/$this->data['sum_all_anggaran_skpd'])*100;

					$this->data['anggaran_tersedia'] = (($this->data['sum_all_anggaran_skpd']-$this->data['sum_all_rincian_skpd'])/$this->data['sum_all_anggaran_skpd'])*100;

				} else {

					$this->data['anggaran_terpakai'] = 0;

					$this->data['anggaran_tersedia'] = 0;

				}
				$this->data['total_pegawai'] = $this->m_camat->total_pegawai($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah'] = $this->m_camat->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses'] = $this->m_camat->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima'] = $this->m_camat->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak'] = $this->m_camat->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_staff_camat'] = $this->m_staff_camat->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses_staff_camat'] = $this->m_staff_camat->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima_staff_camat'] = $this->m_staff_camat->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak_staff_camat'] = $this->m_staff_camat->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
				$this->data['posisi_kaopd'] = $this->m_admin->posisi_kaopd($this->ion_auth->user()->row()->skpd_id);
			// 9 (Admin OPD)(Kelurahan)
			} else if ($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->jenis_skpd==5){
				$this->data['count_perjalanan'] = $this->m_beranda->count_perjalanan('','','');
				$this->data['anggaran'] = $this->m_beranda->anggaran('','',$this->ion_auth->user()->row()->skpd_id);
				$sum_all_rincian_skpd = $this->m_beranda->sum_all_rincian_skpd();
				$sum_all_pengeluaran_rill_skpd = $this->m_beranda->sum_all_pengeluaran_rill_skpd();
				$this->data['sum_all_rincian_skpd'] = $sum_all_rincian_skpd +  $sum_all_pengeluaran_rill_skpd;
				$this->data['sum_all_anggaran_skpd'] = $this->m_beranda->sum_all_anggaran_skpd();
				if($this->data['sum_all_anggaran_skpd'] > 0){

					$this->data['anggaran_terpakai'] = ($this->data['sum_all_rincian_skpd']/$this->data['sum_all_anggaran_skpd'])*100;

					$this->data['anggaran_tersedia'] = (($this->data['sum_all_anggaran_skpd']-$this->data['sum_all_rincian_skpd'])/$this->data['sum_all_anggaran_skpd'])*100;

				} else {

					$this->data['anggaran_terpakai'] = 0;

					$this->data['anggaran_tersedia'] = 0;

				}
				$this->data['total_pegawai'] = $this->m_lurah->total_pegawai($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah'] = $this->m_lurah->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses'] = $this->m_lurah->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima'] = $this->m_lurah->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak'] = $this->m_lurah->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_staff_lurah'] = $this->m_staff_lurah->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses_staff_lurah'] = $this->m_staff_lurah->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima_staff_lurah'] = $this->m_staff_lurah->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak_staff_lurah'] = $this->m_staff_lurah->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
				$this->data['posisi_kaopd'] = $this->m_admin->posisi_kaopd($this->ion_auth->user()->row()->skpd_id);
			// 9 (Admin OPD)(Walikota)
			} else if ($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->jenis_skpd==6){
				$this->data['posisi_walikota'] = $this->m_admin->posisi_walikota();
			// 9 (Admin OPD)(Puskesmas)
			} else if ($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->jenis_skpd==7){
				$this->data['count_perjalanan'] = $this->m_beranda->count_perjalanan($this->ion_auth->user()->row()->jenis_skpd,'','');
				$this->data['anggaran'] = $this->m_beranda->anggaran('','',$this->ion_auth->user()->row()->skpd_id);
				$sum_all_rincian_skpd = $this->m_beranda->sum_all_rincian_puskesmas();
				$sum_all_pengeluaran_rill_skpd = $this->m_beranda->sum_all_pengeluaran_rill_puskesmas();
				if($sum_all_rincian_skpd && $sum_all_pengeluaran_rill_skpd){
					$this->data['sum_all_rincian_skpd'] = $sum_all_rincian_skpd +  $sum_all_pengeluaran_rill_skpd;
					$this->data['sum_all_anggaran_skpd'] = $this->m_beranda->sum_all_anggaran_skpd();
					if($this->data['sum_all_anggaran_skpd'] > 0){

						$this->data['anggaran_terpakai'] = ($this->data['sum_all_rincian_skpd']/$this->data['sum_all_anggaran_skpd'])*100;

						$this->data['anggaran_tersedia'] = (($this->data['sum_all_anggaran_skpd']-$this->data['sum_all_rincian_skpd'])/$this->data['sum_all_anggaran_skpd'])*100;

					} else {

						$this->data['anggaran_terpakai'] = 0;

						$this->data['anggaran_tersedia'] = 0;

					}
				} else {
					$this->data['sum_all_rincian_skpd'] = 0;
					$this->data['sum_all_anggaran_skpd'] = 0;
					$this->data['anggaran_terpakai'] =0;
					$this->data['anggaran_tersedia'] = 0;
				}
				$this->data['total_pegawai'] = $this->m_kapus->total_pegawai($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah'] = $this->m_kapus->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses'] = $this->m_kapus->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima'] = $this->m_kapus->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak'] = $this->m_kapus->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_bok'] = $this->m_esselon->total_list_telaah($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diproses_bok'] = $this->m_esselon->total_list_telaah_diproses($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima_bok'] = $this->m_esselon->total_list_telaah_diterima($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak_bok'] = $this->m_esselon->total_list_telaah_ditolak($this->ion_auth->user()->row()->skpd_id);
				$this->data['posisi_kaopd'] = $this->m_admin->posisi_kaopd($this->ion_auth->user()->row()->skpd_id);
			// 2 (Kabid / Kabag)
			} else if($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd != 2 
						&& $this->ion_auth->user()->row()->jenis_skpd != 3 && $this->ion_auth->user()->row()->jenis_skpd != 10){
				$this->data['total_list_telaah'] = $this->m_telaah->kabid_opd('','',$this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima'] = $this->m_telaah_disetujui->kabid_opd('','',$this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak'] = $this->m_telaah_ditolak->kabid_opd('','',$this->ion_auth->user()->row()->skpd_id);
			// 2 (Kabid / Kabag) (Dinkes)
			} else if($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 10){
				$this->data['total_list_telaah'] = $this->m_telaah->kabid_dinkes('','',$this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima'] = $this->m_telaah_disetujui->kabid_dinkes('','',$this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak'] = $this->m_telaah_ditolak->kabid_dinkes('','',$this->ion_auth->user()->row()->skpd_id);
			// 2 (Kabid / Kabag) (DPRD)
			} else if($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 2){
				$this->data['total_list_telaah'] = $this->m_telaah->kabid_dprd('','');
				$this->data['total_list_telaah_diterima'] = $this->m_telaah_disetujui->kabid_dprd('','');
				$this->data['total_list_telaah_ditolak'] = $this->m_telaah_ditolak->kabid_dprd('','');
			// 2 (Kabid / Kabag) (Sekda)
			} else if($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 3){
				$sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
				$total_list_telaah = $this->m_telaah->kabid_sekda('','',$sekda[0]['bagian_id']);
				$total_list_telaah_diterima = $this->m_telaah_disetujui->kabid_sekda('','',$sekda[0]['bagian_id']);
				$total_list_telaah_ditolak = $this->m_telaah_ditolak->kabid_sekda('','',$sekda[0]['bagian_id']);
				if($this->ion_auth->user()->row()->id == 638) {
					$this->data['total_list_telaah'] = $total_list_telaah[0]['numrows']+$total_list_telaah[1]['numrows'];
					$this->data['total_list_telaah_diterima'] = $total_list_telaah_diterima[0]['numrows']+$total_list_telaah_diterima[1]['numrows'];
					$this->data['total_list_telaah_ditolak'] = $total_list_telaah_ditolak[0]['numrows']+$total_list_telaah_ditolak[1]['numrows'];
				} else {
					$this->data['total_list_telaah'] = $this->m_telaah->kabid_sekda('','',$sekda[0]['bagian_id']);
					$this->data['total_list_telaah_diterima'] = $this->m_telaah_disetujui->kabid_sekda('','',$sekda[0]['bagian_id']);
					$this->data['total_list_telaah_ditolak'] = $this->m_telaah_ditolak->kabid_sekda('','',$sekda[0]['bagian_id']);
				}
			// 3 (Sekretaris OPD)
			} else if($this->ion_auth->get_users_groups()->row()->id == 3 && $this->ion_auth->user()->row()->jenis_skpd != 10){
				$this->data['total_list_telaah'] = $this->m_telaah->sekdis('','',$this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima'] = $this->m_telaah_disetujui->sekdis('','',$this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak'] = $this->m_telaah_ditolak->sekdis('','',$this->ion_auth->user()->row()->skpd_id);
			// 3 (Sekretaris Dinkes)
			} else if($this->ion_auth->get_users_groups()->row()->id == 3 && $this->ion_auth->user()->row()->jenis_skpd == 10){
				$this->data['total_list_telaah'] = $this->m_telaah->sekdis_dinkes('','',$this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima'] = $this->m_telaah_disetujui->sekdis_dinkes('','',$this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak'] = $this->m_telaah_ditolak->sekdis_dinkes('','',$this->ion_auth->user()->row()->skpd_id);
			// 4 (Kepala OPD)
			} else if($this->ion_auth->get_users_groups()->row()->id == 4 && $this->ion_auth->user()->row()->jenis_skpd != 2 && $this->ion_auth->user()->row()->jenis_skpd != 10){
				$this->data['count_perjalanan'] = $this->m_beranda->count_perjalanan('','','');
				$this->data['anggaran'] = $this->m_beranda->anggaran('','',$this->ion_auth->user()->row()->skpd_id);
				$sum_all_rincian_skpd = $this->m_beranda->sum_all_rincian_skpd();
				$sum_all_pengeluaran_rill_skpd = $this->m_beranda->sum_all_pengeluaran_rill_skpd();
				$this->data['sum_all_rincian_skpd'] = $sum_all_rincian_skpd +  $sum_all_pengeluaran_rill_skpd;
				$this->data['sum_all_anggaran_skpd'] = $this->m_beranda->sum_all_anggaran_skpd();
				if($this->data['sum_all_anggaran_skpd'] > 0){

					$this->data['anggaran_terpakai'] = ($this->data['sum_all_rincian_skpd']/$this->data['sum_all_anggaran_skpd'])*100;

					$this->data['anggaran_tersedia'] = (($this->data['sum_all_anggaran_skpd']-$this->data['sum_all_rincian_skpd'])/$this->data['sum_all_anggaran_skpd'])*100;

				} else {

					$this->data['anggaran_terpakai'] = 0;

					$this->data['anggaran_tersedia'] = 0;

				}
				$this->data['total_pegawai'] = $this->m_esselon->total_pegawai($this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah'] = $this->m_telaah->kadis('','',$this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima'] = $this->m_telaah_disetujui->kadis('','',$this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak'] = $this->m_telaah_ditolak->kadis('','',$this->ion_auth->user()->row()->skpd_id);
			// 4 (Kepala Dinkes)
			} else if($this->ion_auth->get_users_groups()->row()->id == 4 && $this->ion_auth->user()->row()->jenis_skpd == 10){
				$this->data['total_list_telaah'] = $this->m_telaah->kadis_dinkes('','',$this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima'] = $this->m_telaah_disetujui->kadis_dinkes('','',$this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak'] = $this->m_telaah_ditolak->kadis_dinkes('','',$this->ion_auth->user()->row()->skpd_id);
			// 10 (Sekwan) (DPRD)
			} else if($this->ion_auth->get_users_groups()->row()->id == 10 && $this->ion_auth->user()->row()->jenis_skpd == 2){
				$this->data['total_list_telaah'] = $this->m_telaah->sekwan('','');
				$this->data['total_list_telaah_diterima'] = $this->m_telaah_disetujui->sekwan('','');
				$this->data['total_list_telaah_ditolak'] = $this->m_telaah_ditolak->sekwan('','');
			// 5 (Asisten)
			} else if($this->ion_auth->get_users_groups()->row()->id == 5){
				$sekda = $this->m_relasi_sekda->getasisten($this->ion_auth->user()->row()->id);
				$this->data['total_list_telaah'] = $this->m_telaah->asisten('','',$sekda[0]['asisten_id']);
				$this->data['total_list_telaah_diterima'] = $this->m_telaah_disetujui->asisten('','',$sekda[0]['asisten_id']);
				$this->data['total_list_telaah_ditolak'] = $this->m_telaah_ditolak->asisten('','',$sekda[0]['asisten_id']);
			// 6 (Sekda)
			} else if($this->ion_auth->get_users_groups()->row()->id == 6){
				$total_list_telaah = $this->m_telaah->sekda('','');
				$total_list_telaah_diterima = $this->m_telaah_disetujui->sekda('','');
				$total_list_telaah_ditolak = $this->m_telaah_ditolak->sekda('','');
				$this->data['total_list_telaah'] = $total_list_telaah[0]['numrows']+$total_list_telaah[1]['numrows'];
				$this->data['total_list_telaah_diterima'] = $total_list_telaah_diterima[0]['numrows']+$total_list_telaah_diterima[1]['numrows'];
				$this->data['total_list_telaah_ditolak'] = $total_list_telaah_ditolak[0]['numrows']+$total_list_telaah_ditolak[1]['numrows'];
			// 7 (Pimpinan DPRD)
			} else if($this->ion_auth->get_users_groups()->row()->id == 7){
				$this->data['total_list_telaah'] = $this->m_telaah->kadprd('','');
				$this->data['total_list_telaah_diterima'] = $this->m_telaah_disetujui->kadprd('','');
				$this->data['total_list_telaah_ditolak'] = $this->m_telaah_ditolak->kadprd('','');
			// 8 (Walikota)
			} else if($this->ion_auth->get_users_groups()->row()->id == 8 || $this->ion_auth->get_users_groups()->row()->id == 17 ){
				
				//API SPPD
				//$urlTelaah=file_get_contents('http://sppd.kendarikota.go.id/api/api/telaah');
				//$this->data['dataTelaah'] = json_decode($urlTelaah, true);
				$this->data['dataTelaah'] = $this->m_api->allData();
				$jumlahTelaah=count($this->data['dataTelaah']);

				//map
				//$urlMap=file_get_contents('http://sppd.kendarikota.go.id/api/api/dataLive/'.date("Y-m-d"));
				//$this->data['dataMap'] = json_decode($urlMap, true);
				$this->data['dataMap'] = $this->m_api->dataLive(date("Y-m-d")); 

				//rekap SPPD per ALUR
				//timeline 1 -> Eselon III, IV & Staf
				//$url=file_get_contents('http://sppd.kendarikota.go.id/api/api/countById2/1');
				//$this->data['total1'] = json_decode($url, true);
				$this->data['total1'] = $this->m_api->countById2(1);
				$this->data['totalMasuk1'] = $this->m_api->countByStatus2(1,0);
				$this->data['totalProses1'] = $this->m_api->countByStatus2(1,1);
				$this->data['totalTerima1'] = $this->m_api->countByStatus2(1,2);
				$this->data['totalTolak1'] = $this->m_api->countByStatus2(1,3);

				//timeline 2 -> Kepala OPD  
				$this->data['total2'] = $this->m_api->countById2(2);
				$this->data['totalMasuk2'] = $this->m_api->countByStatus2(2,0);
				$this->data['totalProses2'] = $this->m_api->countByStatus2(2,1);
				$this->data['totalTerima2'] = $this->m_api->countByStatus2(2,2);
				$this->data['totalTolak2'] = $this->m_api->countByStatus2(2,3);

				//timeline 3 -> Anggota DPRD 
				$this->data['total3'] = $this->m_api->countById2(3);
				$this->data['totalMasuk3'] = $this->m_api->countByStatus2(3,0);
				$this->data['totalProses3'] = $this->m_api->countByStatus2(3,1);
				$this->data['totalTerima3'] = $this->m_api->countByStatus2(3,2);
				$this->data['totalTolak3'] = $this->m_api->countByStatus2(3,3);

				//timeline 4 -> KABAG, ASISTEN & SEKDA di SEKRETARIAT 
				$this->data['total4'] = $this->m_api->countById2(4);
				$this->data['totalMasuk4'] = $this->m_api->countByStatus2(4,0);
				$this->data['totalProses4'] = $this->m_api->countByStatus2(4,1);
				$this->data['totalTerima4'] = $this->m_api->countByStatus2(4,2);
				$this->data['totalTolak4'] = $this->m_api->countByStatus2(4,3);

				//timeline 5 -> CAMAT & LURAH
				$this->data['total5'] = $this->m_api->countById2(5);
				$this->data['totalMasuk5'] = $this->m_api->countByStatus2(5,0);
				$this->data['totalProses5'] = $this->m_api->countByStatus2(5,1);
				$this->data['totalTerima5'] = $this->m_api->countByStatus2(5,2);
				$this->data['totalTolak5'] = $this->m_api->countByStatus2(5,3);

				//timeline 6 -> STAFF DPRD
				$this->data['total6'] = $this->m_api->countById2(6);
				$this->data['totalMasuk6'] = $this->m_api->countByStatus2(6,0);
				$this->data['totalProses6'] = $this->m_api->countByStatus2(6,1);
				$this->data['totalTerima6'] = $this->m_api->countByStatus2(6,2);
				$this->data['totalTolak6'] = $this->m_api->countByStatus2(6,3);

				//timeline 7 -> Staff Camat & Lurah
				$this->data['total7'] = $this->m_api->countById2(7);
				$this->data['totalMasuk7'] = $this->m_api->countByStatus2(7,0);
				$this->data['totalProses7'] = $this->m_api->countByStatus2(7,1);
				$this->data['totalTerima7'] = $this->m_api->countByStatus2(7,2);
				$this->data['totalTolak7'] = $this->m_api->countByStatus2(7,3);

				//timeline 8 -> walikota
				$this->data['total8'] = $this->m_api->countById2(8);
				$this->data['totalMasuk8'] = $this->m_api->countByStatus2(8,0);
				$this->data['totalProses8'] = $this->m_api->countByStatus2(8,1);
				$this->data['totalTerima8'] = $this->m_api->countByStatus2(8,2);
				$this->data['totalTolak8'] = $this->m_api->countByStatus2(8,3);
				
				//timeline 9 -> Staff Setda
				$this->data['total9'] = $this->m_api->countById2(9);
				$this->data['totalMasuk9'] = $this->m_api->countByStatus2(9,0);
				$this->data['totalProses9'] = $this->m_api->countByStatus2(9,1);
				$this->data['totalTerima9'] = $this->m_api->countByStatus2(9,2);
				$this->data['totalTolak9'] = $this->m_api->countByStatus2(9,3);
				
				//timeline 10 -> Sekwan
				$this->data['total10'] = $this->m_api->countById2(10);
				$this->data['totalMasuk10'] = $this->m_api->countByStatus2(10,0);
				$this->data['totalProses10'] = $this->m_api->countByStatus2(10,1);
				$this->data['totalTerima10'] = $this->m_api->countByStatus2(10,2);
				$this->data['totalTolak10'] = $this->m_api->countByStatus2(10,3);
				
				//timeline 11 -> Puskesmas 
				$this->data['total11'] = $this->m_api->countById2(11);
				$this->data['totalMasuk11'] = $this->m_api->countByStatus2(11,0);
				$this->data['totalProses11'] = $this->m_api->countByStatus2(11,1);
				$this->data['totalTerima11'] = $this->m_api->countByStatus2(11,2);
				$this->data['totalTolak11'] = $this->m_api->countByStatus2(11,3);
				
				$this->data['grafik_opd'] = $this->m_walikota->grafik_perjalanan(1);
				$this->data['grafik_puskesmas'] = $this->m_walikota->grafik_perjalanan(7);
				$this->data['grafik_kecamatan'] = $this->m_walikota->grafik_perjalanan(4);
				$this->data['grafik_kelurahan'] = $this->m_walikota->grafik_perjalanan(5);
				$this->data['skpd'] = $this->m_walikota->getSKPD();
				
				if($this->ion_auth->get_users_groups()->row()->id == 8){
					$total_list_telaah = $this->m_telaah->walikota('','');
					$total_list_telaah_diterima = $this->m_telaah_disetujui->walikota('','');
					$total_list_telaah_ditolak = $this->m_telaah_ditolak->walikota('','');
					$this->data['total_list_telaah'] = $total_list_telaah[0]['numrows']+$total_list_telaah[1]['numrows'];
					$this->data['total_list_telaah_diterima'] = $total_list_telaah_diterima[0]['numrows']+$total_list_telaah_diterima[1]['numrows'];
					$this->data['total_list_telaah_ditolak'] = $total_list_telaah_ditolak[0]['numrows']+$total_list_telaah_ditolak[1]['numrows'];
				}
				
			// 11 (Camat)
			} else if($this->ion_auth->get_users_groups()->row()->id == 11){
				$this->data['total_list_telaah'] = $this->m_telaah->camat('','',$this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima'] = $this->m_telaah_disetujui->camat('','',$this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak'] = $this->m_telaah_ditolak->camat('','',$this->ion_auth->user()->row()->skpd_id);
			// 12 (Sekcam)
			} else if($this->ion_auth->get_users_groups()->row()->id == 12){
				$this->data['total_list_telaah'] = $this->m_telaah->sekcam('','',$this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima'] = $this->m_telaah_disetujui->sekcam('','',$this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak'] = $this->m_telaah_ditolak->sekcam('','',$this->ion_auth->user()->row()->skpd_id);
			// 16 (Puskesmas)
			} else if($this->ion_auth->get_users_groups()->row()->id == 16){
				$this->data['total_list_telaah'] = $this->m_telaah->kapus('','',$this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_diterima'] = $this->m_telaah_disetujui->kapus('','',$this->ion_auth->user()->row()->skpd_id);
				$this->data['total_list_telaah_ditolak'] = $this->m_telaah_ditolak->kapus('','',$this->ion_auth->user()->row()->skpd_id);
			}
			
			
			
			$this->data['nama_skpd'] = $this->m_menu->nama_skpd($this->ion_auth->user()->row()->skpd_id);
			$this->render('content');
		} else {
			redirect('login');
		}
		
	}
	
	public function get(){
		$data ['get_kabkot']=$this->m_esselon->get_kabkot($this->uri->segment(3));
		$this->load->view('kabkot',$data);
	}
	
	public function get_anggaran(){
		$data['pagu']=$this->m_anggaran->get($this->uri->segment(4));
		$data['rincian_biaya'] = $this->m_anggaran->cek_sisa_anggaran_skpd($this->uri->segment(4));
		$data['pengeluaran_rill'] = $this->m_anggaran->cek_pengeluaran_rill_skpd($this->uri->segment(4));
		$data['id_anggaran'] = $this->uri->segment(4);
		$pelaksana=explode(",",$this->uri->segment(5));
		$data['telaah_pelaksana'] = $this->m_pegawai->get($pelaksana[0]);
		switch($this->uri->segment(3)){
			case 1 : $this->load->view('anggaran',$data); break; 
			case 2 : $this->load->view('modal_konfirmasi',$data); break; 
		}
	}
	
	public function get2(){
		$data ['get_kec']=$this->m_esselon->get_kec($this->uri->segment(3));
		$this->load->view('kecamatan',$data);
	}
	public function update_posisi_walikota(){
		$data['setting_id'] = 1;
		$posisi = $this->m_admin->posisi_walikota();
		if($posisi[0]['status']==1 ){
			$data['status'] = 0;
		} else {
			$data['status'] = 1;
		}
		
		$this->m_admin->update_posisi_walikota($data);
	}
	public function update_posisi_kaopd(){
		$data['skpd_id'] = $this->ion_auth->user()->row()->skpd_id;
		$posisi = $this->m_admin->posisi_kaopd($this->ion_auth->user()->row()->skpd_id);
		if($posisi[0]['status']==1 ){
			$data['status'] = 0;
		} else {
			$data['status'] = 1;
		}
		
		$this->m_admin->update_posisi_kaopd($data);
	}
	public function update_posisi_kadprd(){
		$data['skpd_id'] = 2;
		$posisi = $this->m_admin->posisi_kadprd();
		if($posisi[0]['status']==1 ){
			$data['status'] = 0;
		} else {
			$data['status'] = 1;
		}
		
		$this->m_admin->update_posisi_kadprd($data);
	}
	public function update_posisi_sekda(){
		$data['skpd_id'] = 3;
		$posisi = $this->m_admin->posisi_sekda();
		if($posisi[0]['status']==1 ){
			$data['status'] = 0;
		} else {
			$data['status'] = 1;
		}
		
		$this->m_admin->update_posisi_sekda($data);
	}
}