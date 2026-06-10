<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pengeluaran_rill extends CI_Model
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
		$this->db->select('*, table_anggotadprd.anggotadprd_name as pegawai_nama, table_anggotadprd.anggotadprd_id as pegawai_id');
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
		$this->db->from('table_pengeluaran_rill, table_pegawai');
		$this->db->where('table_pengeluaran_rill.pegawai_id = table_pegawai.pegawai_id');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('table_pengeluaran_rill.pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result();
	} 

	public function get_rincian_walikota($telaah_id,$pegawai_id)
	{
		$this->db->select('*');
		$this->db->from('table_pengeluaran_rill, table_pimpinan');
		$this->db->where('table_pengeluaran_rill.pegawai_id = table_pimpinan.pegawai_id');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('table_pengeluaran_rill.pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result();
	} 

	public function get_rincian_dprd($telaah_id,$pegawai_id)
	{
		$this->db->select('*');
		$this->db->from('table_pengeluaran_rill, table_anggotadprd');
		$this->db->where('table_pengeluaran_rill.pegawai_id = table_anggotadprd.anggotadprd_id');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('table_pengeluaran_rill.pegawai_id',$pegawai_id);
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
		$this->db->from('table_pengeluaran_rill, table_pegawai');
		$this->db->where('table_pengeluaran_rill.pegawai_id = table_pegawai.pegawai_id');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('table_pengeluaran_rill.pegawai_id',$pegawai_id);
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
		$this->db->select('*, table_anggotadprd.anggotadprd_name as pegawai_nama, table_anggotadprd.anggotadprd_id as pegawai_id');
		$this->db->from('table_pengikut, table_anggotadprd');
		$this->db->where('table_pengikut.pegawai_id = table_anggotadprd.anggotadprd_id');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	/// Get PPTK
	public function get_pptk_perjalanan($telaah_id)
	{
		$this->db->select('*');
		$this->db->from('table_pptk_perjalanan');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	public function get($pengeluaran_rill_id) {
		$this->db->select('*');
		$this->db->from('table_pengeluaran_rill');
		$this->db->where('pengeluaran_rill_id',$pengeluaran_rill_id);
		$query = $this->db->get ();
		return $query->result_array();
	}

	public function get2($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_pengeluaran_rill');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result_array();
	}

	public function get3($telaah_id,$pegawai_id) {
		$this->db->select('SUM(tarif) AS total');
		$this->db->from('table_pengeluaran_rill');
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
		
        $this->db->insert('table_pengeluaran_rill', $data);
    }
	
	public function update($data) {
        $this->db->update('table_pengeluaran_rill', $data, array('pengeluaran_rill_id'=>$data['pengeluaran_rill_id']));
    }
	
	public function delete($pengeluaran_rill_id) {
        $this->db->delete('table_pengeluaran_rill', array('pengeluaran_rill_id' => $pengeluaran_rill_id));
    }
	
	public function link_gambar($pengeluaran_rill_id)
	{
		
		$this->db->where('pengeluaran_rill_id',$pengeluaran_rill_id);
		$query = $getData = $this->db->get('table_pengeluaran_rill');

		if($getData->num_rows() > 0)
		return $query;
		else
		return null;
			
	}
	
}