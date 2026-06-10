<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_spt extends CI_Model
{	
	public function pelaksana($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_telaah, table_pegawai');
		$this->db->where('table_telaah.telaah_pelaksana = table_pegawai.pegawai_id');
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
	
	public function pengikut($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_pengikut, table_pegawai');
		$this->db->where('table_pengikut.pegawai_id = table_pegawai.pegawai_id');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result_array();
	}

	public function getTelaahKategori($telaah_id) {
		$this->db->select('telaah_kategori, jenis_skpd');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id = table_telaah.telaah_pelaksana','LEFT');
		$this->db->join('table_skpd','table_skpd.skpd_id = table_pegawai.skpd_id','LEFT');
		$this->db->join('table_anggotadprd','table_anggotadprd.anggotadprd_id = table_telaah.telaah_pelaksana','LEFT');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	/// TANGGAL PERJALANAN
	public function get_tanggal_perjalanan($telaah_id)
	{
		$this->db->select('*');
		$this->db->from('table_tanggal_perjalanan');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function get($tanggal_perjalanan_id) {
		$this->db->select('*');
		$this->db->from('table_tanggal_perjalanan');
		$this->db->where('tanggal_perjalanan_id',$tanggal_perjalanan_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function create($data) {
		
        $this->db->insert('table_tanggal_perjalanan', $data);
    }
	
	public function update($data) {
        $this->db->update('table_tanggal_perjalanan', $data, array('tanggal_perjalanan_id'=>$data['tanggal_perjalanan_id']));
    }
	
	public function delete($tanggal_perjalanan_id) {
        $this->db->delete('table_tanggal_perjalanan', array('tanggal_perjalanan_id' => $tanggal_perjalanan_id));
    }
}