<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_history extends CI_Model
{
	public function record_count($pegawai_id) {
		return $this->db->count_all("table_telaah, table_pegawai 
			WHERE table_telaah.telaah_pelaksana = table_pegawai.pegawai_id
			AND pegawai_id='$pegawai_id'
			AND telaah_status=2 
			AND telaah_kategori!=3
			AND telaah_kategori!=8");
	}
	public function record_count_walikota($pegawai_id) {
		return $this->db->count_all("table_telaah, table_pimpinan
			WHERE table_telaah.telaah_pelaksana = table_pimpinan.pegawai_id
			AND pegawai_id='$pegawai_id'
			AND telaah_status=2");
	}
	
	public function data($limit, $start, $pegawai_id) {
		$this->db->select('*');
		$this->db->from('table_telaah, table_pegawai');
		$this->db->where('table_telaah.telaah_pelaksana = table_pegawai.pegawai_id');
		$this->db->where('pegawai_id',$pegawai_id);
		$this->db->where('telaah_status', 2);
		$this->db->where("telaah_kategori!=3");
		$this->db->where("telaah_kategori!=8");
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_walikota($limit, $start, $pegawai_id) {
		$this->db->select('*');
		$this->db->from('table_telaah, table_pimpinan');
		$this->db->where('table_telaah.telaah_pelaksana = table_pimpinan.pegawai_id');
		$this->db->where('pegawai_id',$pegawai_id);
		$this->db->where('telaah_status', 2);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function record_count_dprd($anggotadprd_id) {
		return $this->db->count_all("table_telaah, table_anggotadprd 
			WHERE table_telaah.telaah_pelaksana = table_anggotadprd.anggotadprd_id
			AND anggotadprd_id='$anggotadprd_id'");
	}
	
	public function data_dprd($limit, $start, $anggotadprd_id) {
		$this->db->select('*');
		$this->db->from('table_telaah, table_anggotadprd');
		$this->db->where('table_telaah.telaah_pelaksana = table_anggotadprd.anggotadprd_id');
		$this->db->where('anggotadprd_id',$anggotadprd_id);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	
}