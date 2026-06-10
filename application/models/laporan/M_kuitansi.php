<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_kuitansi extends CI_Model
{	
	public function pelaksana($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_telaah, table_pegawai');
		$this->db->where('table_telaah.telaah_pelaksana = table_pegawai.pegawai_id');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	public function pelaksanadprd($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_telaah, table_anggotadprd');
		$this->db->where('table_telaah.telaah_pelaksana = table_anggotadprd.anggotadprd_id');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function pelaksanaWalikota($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_telaah, table_pimpinan');
		$this->db->where('table_telaah.telaah_pelaksana = table_pimpinan.pegawai_id');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function get_rincian($telaah_id,$pegawai_id)
	{
		$this->db->select('*');
		$this->db->from('table_rincian_biaya, table_pegawai');
		$this->db->where('table_rincian_biaya.pegawai_id = table_pegawai.pegawai_id');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('table_rincian_biaya.pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result();
	}
	public function get_rincian_dprd($telaah_id,$pegawai_id)
	{
		$this->db->select('*');
		$this->db->from('table_rincian_biaya, table_anggotadprd');
		$this->db->where('table_rincian_biaya.pegawai_id = table_anggotadprd.anggotadprd_id');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('table_rincian_biaya.pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result();
	} 
	public function get_rincianpelaksana($telaah_id,$pegawai_id)
	{
		$this->db->select('*');
		$this->db->from('table_pengeluaran_rill, table_pimpinan');
		$this->db->where('table_pengeluaran_rill.pegawai_id = table_pimpinan.pegawai_id');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('table_pengeluaran_rill.pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_rincian_pengikut($telaah_id,$pegawai_id)
	{
		$this->db->select('*');
		$this->db->from('table_rincian_biaya, table_pegawai');
		$this->db->where('table_rincian_biaya.pegawai_id = table_pegawai.pegawai_id');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('table_rincian_biaya.pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function pengikut($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_pengikut, table_pegawai');
		$this->db->where('table_pengikut.pegawai_id = table_pegawai.pegawai_id');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function pengikutdprd($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_pengikut, table_anggotadprd');
		$this->db->where('table_pengikut.pegawai_id = table_anggotadprd.anggotadprd_id');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function get($rincian_biaya_id) {
		$this->db->select('*');
		$this->db->from('table_rincian_biaya');
		$this->db->where('rincian_biaya_id',$rincian_biaya_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	public function getTelaahKategori($telaah_id) {
		$this->db->select('telaah_kategori');
		$this->db->from('table_telaah');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	
	// KUITANSI PANJAR
	
	public function get_kuitansi_panjar($telaah_id,$pegawai_id)
	{
		$this->db->select('*');
		$this->db->from('table_kuitansi_panjar, table_pegawai');
		$this->db->where('table_kuitansi_panjar.pegawai_id = table_pegawai.pegawai_id');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('table_kuitansi_panjar.pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function get_kuitansi_panjar_walikota($telaah_id,$pegawai_id)
	{
		$this->db->select('*');
		$this->db->from('table_kuitansi_panjar, table_pimpinan');
		$this->db->where('table_kuitansi_panjar.pegawai_id = table_pimpinan.pegawai_id');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('table_kuitansi_panjar.pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function get_kuitansi_panjar_dprd($telaah_id,$pegawai_id)
	{
		$this->db->select('*');
		$this->db->from('table_kuitansi_panjar, table_anggotadprd');
		$this->db->where('table_kuitansi_panjar.pegawai_id = table_anggotadprd.anggotadprd_id');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('table_kuitansi_panjar.pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function get2($kuitansi_panjar_id) {
		$this->db->select('*');
		$this->db->from('table_kuitansi_panjar');
		$this->db->where('kuitansi_panjar_id',$kuitansi_panjar_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	public function create($data) {
		
        $this->db->insert('table_kuitansi_panjar', $data);
    }
	
	public function update($data) {
        $this->db->update('table_kuitansi_panjar', $data, array('kuitansi_panjar_id'=>$data['kuitansi_panjar_id']));
    }
	
	public function delete($kuitansi_panjar_id) {
        $this->db->delete('table_kuitansi_panjar', array('kuitansi_panjar_id' => $kuitansi_panjar_id));
    }
	
	
}