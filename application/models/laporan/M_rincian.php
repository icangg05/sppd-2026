<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_rincian extends CI_Model
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
		$this->db->select('*, anggotadprd_id as pegawai_id, anggotadprd_name as pegawai_nama, anggotadprd_jabatan as pegawai_namajabatan');
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
		$this->db->from('table_rincian_biaya');
		$this->db->join('table_pegawai','table_rincian_biaya.pegawai_id = table_pegawai.pegawai_id','LEFT');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('table_rincian_biaya.pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result();
	}

	public function get_rincian_walikota($telaah_id,$pegawai_id)
	{
		$this->db->select('*');
		$this->db->from('table_rincian_biaya, table_pimpinan');
		$this->db->where('table_rincian_biaya.pegawai_id = table_pimpinan.pegawai_id');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('table_rincian_biaya.pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result();
	}

	public function get_rincian_dprd($telaah_id,$pegawai_id)
	{
		$this->db->select('*, anggotadprd_id as pegawai_id, anggotadprd_name as pegawai_nama, anggotadprd_jabatan as pegawai_namajabatan');
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
		$this->db->from('table_rincian_biaya, table_pimpinan');
		$this->db->where('table_rincian_biaya.pegawai_id = table_pimpinan.pegawai_id');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('table_rincian_biaya.pegawai_id',$pegawai_id);
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
		$this->db->select('*, anggotadprd_id as pegawai_id, anggotadprd_name as pegawai_nama, anggotadprd_jabatan as pegawai_namajabatan');
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

	public function get2($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_rincian_biaya');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result_array();
	}

	public function get3($telaah_id, $pegawai_id) {
		$this->db->select('SUM(tarif*item) AS total');
		$this->db->from('table_rincian_biaya');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('pegawai_id',$pegawai_id);
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
	
	
	public function create($data) {
		
        $this->db->insert('table_rincian_biaya', $data);
    }
	
	public function update($data) {
        $this->db->update('table_rincian_biaya', $data, array('rincian_biaya_id'=>$data['rincian_biaya_id']));
    }
	
	public function delete($rincian_biaya_id) {
        $this->db->delete('table_rincian_biaya', array('rincian_biaya_id' => $rincian_biaya_id));
    }
	
	public function link_gambar($rincian_biaya_id)
	{
		
		$this->db->where('rincian_biaya_id',$rincian_biaya_id);
		$query = $getData = $this->db->get('table_rincian_biaya');

		if($getData->num_rows() > 0)
		return $query;
		else
		return null;
			
	}
	
}