<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_verifikasi extends CI_Model
{
	
	public function data($limit, $start) {
		if($this->uri->segment(4) == "sekwan"){
			$this->db->select('*, anggotadprd_name as pegawai_nama, anggotadprd_id as pegawai_id, telaah_skpd_id as skpd, table_telaah.telaah_id as telaah_id');
			$this->db->from('table_telaah');
			$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','left');
			$this->db->join('table_skpd','table_skpd.skpd_id=2','left');
		} else {
			$this->db->select('*');
			$this->db->from('table_telaah');
			$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
			$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		}
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
		$this->db->join('table_laporanperjalanan','table_laporanperjalanan.telaah_id=table_telaah.telaah_id');
		if($this->ion_auth->user()->row()->jenis_skpd == 10){
			$this->db->where("table_jenis_skpd.jenis_skpd_id IN (7,10)");
			$this->db->where("telaah_kategori",1);
		} else if($this->uri->segment(4) == "sekwan"){
			$this->db->where("telaah_kategori",3);
		} else if($this->uri->segment(4) == "sekda"){
			$this->db->where("telaah_kategori",4);
		} else if($this->uri->segment(4) == "walikota"){
			$this->db->where("telaah_kategori IN (2,3,4,5,10)");
		} else {
			$this->db->where('table_pegawai.skpd_id',$this->ion_auth->user()->row()->skpd_id);
		}
		$this->db->order_by('telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function data_search($column, $value, $limit, $start) {
		if($this->uri->segment(4) == "sekwan"){
			$this->db->select('*, anggotadprd_name as pegawai_nama, anggotadprd_id as pegawai_id, telaah_skpd_id as skpd, table_telaah.telaah_id as telaah_id');
			$this->db->from('table_telaah');
			$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','left');
			$this->db->join('table_skpd','table_skpd.skpd_id=2','left');
		} else {
			$this->db->select('*');
			$this->db->from('table_telaah');
			$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
			$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		}
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
		$this->db->join('table_laporanperjalanan','table_laporanperjalanan.telaah_id=table_telaah.telaah_id');
		if($this->ion_auth->user()->row()->jenis_skpd == 10){
			$this->db->where("table_jenis_skpd.jenis_skpd_id IN (7,10)");
			$this->db->where("telaah_kategori",1);
		} else if($this->uri->segment(4) == "sekwan"){
			$this->db->where("telaah_kategori",3);
		} else if($this->uri->segment(4) == "sekda"){
			$this->db->where("telaah_kategori",4);
		} else if($this->uri->segment(4) == "walikota"){
			$this->db->where("telaah_kategori IN (2,3,4,5,10)");
		} else {
			$this->db->where('table_pegawai.skpd_id',$this->ion_auth->user()->row()->skpd_id);
		}
		$this->db->like($column,$value);
		$this->db->order_by('telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function verifikasi($data) {
		$this->db->update('table_laporanperjalanan', $data, array('telaah_id'=>$data['telaah_id']));
	}
	
	public function rincian($telaah_id,$pegawai_id)
	{
		$this->db->select('(SELECT SUM(tarif*item) ) as total');
		$this->db->from('table_rincian_biaya');
		$this->db->join('table_pegawai','table_rincian_biaya.pegawai_id = table_pegawai.pegawai_id','LEFT');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('table_rincian_biaya.pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function all_rincian($telaah_id,$pegawai_id)
	{
		$this->db->select('*');
		$this->db->from('table_rincian_biaya');
		$this->db->join('table_pegawai','table_rincian_biaya.pegawai_id = table_pegawai.pegawai_id','LEFT');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('table_rincian_biaya.pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function pengeluaran_rill($telaah_id,$pegawai_id) {
		$this->db->select('SUM(tarif) AS total');
		$this->db->from('table_pengeluaran_rill');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	
	public function all_pengeluaran_rill($telaah_id,$pegawai_id) {
		$this->db->select('*');
		$this->db->from('table_pengeluaran_rill');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result();
	}
}