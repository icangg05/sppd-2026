<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_beranda extends CI_Model
{
	
	// TAMPILAN DASHBOARD-----------------------------------------------------------------------------------------//
	
	public function count_perjalanan($jenis_skpd,$bagian_id,$subbagian_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana = table_pegawai.pegawai_id','LEFT');
		## Dinkes
		if($jenis_skpd==10){
			$this->db->join('table_skpd','table_pegawai.skpd_id = table_skpd.skpd_id','LEFT');
			$this->db->join('table_jenis_skpd','table_skpd.jenis_skpd = table_jenis_skpd.jenis_skpd_id','LEFT');
			$this->db->group_start();
			$this->db->where('table_telaah.telaah_kategori',1);
			$this->db->or_where('table_telaah.telaah_kategori',2);
			$this->db->group_end();
			$this->db->group_start();
			$this->db->where('table_jenis_skpd.jenis_skpd_id',$jenis_skpd);
			$this->db->or_where('table_jenis_skpd.jenis_skpd_id',7);
			$this->db->group_end();
		} 
		## DPRD
		else if($jenis_skpd==2){
			$this->db->join('table_skpd','table_pegawai.skpd_id = table_skpd.skpd_id','LEFT');
			$this->db->join('table_jenis_skpd','table_skpd.jenis_skpd = table_jenis_skpd.jenis_skpd_id','LEFT');
			$this->db->group_start();
			$this->db->where('table_telaah.telaah_kategori',3);
			$this->db->or_where('table_telaah.telaah_kategori',6);
			$this->db->or_where('table_telaah.telaah_kategori',10);
			$this->db->group_end();
		} 
		## Sekda
		else if($jenis_skpd==3 && $bagian_id && $subbagian_id==""){
			$this->db->join('table_skpd','table_pegawai.skpd_id = table_skpd.skpd_id','LEFT');
			$this->db->join('table_jenis_skpd','table_skpd.jenis_skpd = table_jenis_skpd.jenis_skpd_id','LEFT');
			$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
			$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
			$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id', 'LEFT');
			$this->db->group_start();
			$this->db->where('table_telaah.telaah_kategori',4);
			$this->db->or_where('table_telaah.telaah_kategori',9);
			$this->db->where('table_bagian.bagian_id',$bagian_id);
			$this->db->group_end();
		} 
		## Sekda
		else if($jenis_skpd==3 && $subbagian_id){
			$this->db->join('table_skpd','table_pegawai.skpd_id = table_skpd.skpd_id','LEFT');
			$this->db->join('table_jenis_skpd','table_skpd.jenis_skpd = table_jenis_skpd.jenis_skpd_id','LEFT');
			$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
			$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
			$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id', 'LEFT');
			$this->db->group_start();
			$this->db->where('table_telaah.telaah_kategori',4);
			$this->db->or_where('table_telaah.telaah_kategori',9);
			$this->db->where('table_subbagian.subbagian_id',$subbagian_id);
			$this->db->group_end();
		} 
		## Puskesmas
		else if($jenis_skpd==7){
			$this->db->join('table_skpd','table_pegawai.skpd_id = table_skpd.skpd_id','LEFT');
			$this->db->join('table_jenis_skpd','table_skpd.jenis_skpd = table_jenis_skpd.jenis_skpd_id','LEFT');
			$this->db->group_start();
			$this->db->where('table_telaah.telaah_kategori',1);
			$this->db->or_where('table_telaah.telaah_kategori',11);
			$this->db->group_end();
			$this->db->where('table_pegawai.skpd_id',$this->ion_auth->user()->row()->skpd_id);
		} else {	
			$this->db->where('table_pegawai.skpd_id',$this->ion_auth->user()->row()->skpd_id);
		}
		$this->db->where('table_telaah.telaah_status',2);
		return $this->db->count_all_results(); 
	}
	
	public function count_jumlah_perjalanan($id_anggaran) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_anggaran','table_anggaran.id_anggaran=table_telaah.telaah_kegiatan');
		$this->db->where('id_anggaran',$id_anggaran);
		$this->db->where('telaah_status',2);
		return $this->db->count_all_results(); 
	}
	
	public function anggaran() {
		$this->db->select('*');
		$this->db->from('table_anggaran');
		$this->db->join('table_skpd','table_anggaran.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_anggaran.skpd_id',$this->ion_auth->user()->row()->skpd_id);
		$this->db->where('tahun',date('Y'));
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function anggaran_setda($bagian_id) {
		$this->db->select('*');
		$this->db->from('table_anggaran');
		$this->db->join('table_skpd','table_anggaran.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_anggaran.skpd_id',$this->ion_auth->user()->row()->skpd_id);
		$this->db->where('table_anggaran.bagian_id',$bagian_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function sum_all_anggaran_skpd() {
		$this->db->select_sum('pagu');
		$this->db->from('table_anggaran');
		$this->db->where('skpd_id',$this->ion_auth->user()->row()->skpd_id);
		$this->db->where('tahun = YEAR(NOW())');
		$query = $this->db->get ();
		return $query->result_array()[0]['pagu'];
	}
	
	public function sum_all_anggaran_setda($bagian_id) {
		$this->db->select_sum('pagu');
		$this->db->from('table_anggaran');
		$this->db->where('skpd_id',$this->ion_auth->user()->row()->skpd_id);
		$this->db->where('bagian_id',$bagian_id);
		$this->db->where('tahun = YEAR(NOW())');
		$query = $this->db->get ();
		return $query->result_array()[0]['pagu'];
	}
	
	public function sum_all_rincian_skpd() {
		$this->db->select('(SELECT SUM(tarif*item)) AS rincian');
		$this->db->from('table_telaah');
		$this->db->join('table_anggaran','table_telaah.telaah_kegiatan = table_anggaran.id_anggaran','LEFT');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana = table_pegawai.pegawai_id','LEFT');
		$this->db->join('table_rincian_biaya','table_telaah.telaah_id = table_rincian_biaya.telaah_id','LEFT');
		$this->db->where('table_pegawai.skpd_id',$this->ion_auth->user()->row()->skpd_id);
		$this->db->where('tahun = YEAR(NOW())');
		$query = $this->db->get ();
		return $query->result_array()[0]['rincian'];
	}
	
	public function sum_all_pengeluaran_rill_skpd() {
		$this->db->select_sum('tarif');
		$this->db->from('table_telaah');
		$this->db->join('table_anggaran','table_telaah.telaah_kegiatan = table_anggaran.id_anggaran','LEFT');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana = table_pegawai.pegawai_id','LEFT');
		$this->db->join('table_pengeluaran_rill','table_telaah.telaah_id = table_pengeluaran_rill.telaah_id','LEFT');
		$this->db->where('table_pegawai.skpd_id',$this->ion_auth->user()->row()->skpd_id);
		$this->db->where('tahun = YEAR(NOW())');
		$query = $this->db->get ();
		return $query->result_array()[0]['tarif'];
	}
	
	### SETDA
	public function sum_all_rincian_setda($bagian_id) {
		$this->db->select('(SELECT SUM(tarif*item)) AS rincian');
		$this->db->from('table_telaah');
		$this->db->join('table_anggaran','table_telaah.telaah_kegiatan = table_anggaran.id_anggaran','LEFT');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana = table_pegawai.pegawai_id','LEFT');
		$this->db->join('table_rincian_biaya','table_telaah.telaah_id = table_rincian_biaya.telaah_id','LEFT');
		$this->db->where('table_pegawai.skpd_id',$this->ion_auth->user()->row()->skpd_id);
		$this->db->where('table_pegawai.bagian_id',$bagian_id);
		$this->db->where('tahun = YEAR(NOW())');
		$query = $this->db->get ();
		return $query->result_array()[0]['rincian'];
	}
	
	public function sum_all_pengeluaran_rill_setda($bagian_id) {
		$this->db->select_sum('tarif');
		$this->db->from('table_telaah');
		$this->db->join('table_anggaran','table_telaah.telaah_kegiatan = table_anggaran.id_anggaran','LEFT');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana = table_pegawai.pegawai_id','LEFT');
		$this->db->join('table_pengeluaran_rill','table_telaah.telaah_id = table_pengeluaran_rill.telaah_id','LEFT');
		$this->db->where('table_pegawai.skpd_id',$this->ion_auth->user()->row()->skpd_id);
		$this->db->where('table_pegawai.bagian_id',$bagian_id);
		$this->db->where('tahun = YEAR(NOW())');
		$query = $this->db->get ();
		return $query->result_array()[0]['tarif'];
	}
	
	### PUSKESMAS
	public function sum_all_rincian_puskesmas() {
		$this->db->select('(SELECT SUM(tarif*item)) AS rincian');
		$this->db->from('table_telaah');
		$this->db->join('table_anggaran','table_telaah.telaah_kegiatan = table_anggaran.id_anggaran','LEFT');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana = table_pegawai.pegawai_id','LEFT');
		$this->db->join('table_rincian_biaya','table_telaah.telaah_id = table_rincian_biaya.telaah_id','LEFT');
		$this->db->where('table_anggaran.skpd_id',$this->ion_auth->user()->row()->skpd_id);
		$this->db->where('tahun = YEAR(NOW())');
		$query = $this->db->get ();
		return $query->result_array()[0]['rincian'];
	}
	
	public function sum_all_pengeluaran_rill_puskesmas() {
		$this->db->select_sum('tarif');
		$this->db->from('table_telaah');
		$this->db->join('table_anggaran','table_telaah.telaah_kegiatan = table_anggaran.id_anggaran','LEFT');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana = table_pegawai.pegawai_id','LEFT');
		$this->db->join('table_pengeluaran_rill','table_telaah.telaah_id = table_pengeluaran_rill.telaah_id','LEFT');
		$this->db->where('table_anggaran.skpd_id',$this->ion_auth->user()->row()->skpd_id);
		$this->db->where('tahun = YEAR(NOW())');
		$query = $this->db->get ();
		return $query->result_array()[0]['tarif'];
	}
	
	## Count TTE Yang Belum Di tandatangani
	public function count_tte($group, $skpd_id, $jenis_skpd) {
		$this->db->select('status_tte');
		$this->db->from('table_tte');
		$this->db->join('table_telaah','table_telaah.telaah_id=table_tte.telaah_id', 'LEFT');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id=table_telaah.telaah_pelaksana', 'LEFT');
		$this->db->join('table_pimpinan','table_pimpinan.pegawai_id=table_telaah.telaah_pelaksana', 'LEFT');
		$this->db->join('table_anggotadprd','table_anggotadprd.anggotadprd_id=table_telaah.telaah_pelaksana', 'LEFT');
		$this->db->where('table_tte.group', $group);
		$this->db->where('table_tte.skpd_id', $skpd_id);
		$this->db->where('table_tte.jenis_skpd', $jenis_skpd);
		$this->db->where('status_tte', 0);
		return $this->db->count_all_results(); 
	}
	
}