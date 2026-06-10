<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_pengikut extends CI_Model
{
	
	
	public function data($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_pengikut, table_pegawai');
		$this->db->where('table_pengikut.pegawai_id = table_pegawai.pegawai_id');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data2($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_pengikut, table_anggotadprd');
		$this->db->where('table_pengikut.pegawai_id = table_anggotadprd.anggotadprd_id');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_dprd($telaah_id) {
		$this->db->select('*, anggotadprd_name as pegawai_nama, anggotadprd_id as pegawai_id, 
							anggotadprd_jabatan as pegawai_namajabatan');
		$this->db->from('table_pengikut, table_anggotadprd');
		$this->db->where('table_pengikut.pegawai_id = table_anggotadprd.anggotadprd_id');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get($telaah_id,$pegawai_id) {
		$this->db->select('*');
		$this->db->from('table_pengikut, table_pegawai');
		$this->db->where('table_pengikut.pegawai_id = table_pegawai.pegawai_id');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('table_pengikut.pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function get_dprd($telaah_id,$pegawai_id) {
		$this->db->select('*');
		$this->db->from('table_pengikut, table_anggotadprd');
		$this->db->where('table_pengikut.pegawai_id = table_anggotadprd.anggotadprd_id');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('table_pengikut.pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function get_walikota($telaah_id,$pegawai_id) {
		$this->db->select('*');
		$this->db->from('table_pengikut, table_pimpinan');
		$this->db->where('table_pengikut.pegawai_id = table_pimpinan.pegawai_id');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('table_pengikut.pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function get_pengikut($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_pengikut, table_pegawai');
		$this->db->where('table_pengikut.pegawai_id = table_pegawai.pegawai_id');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_koordinat_tte_pengikut($telaah_id, $pegawai_id) {
		$this->db->select('*');
		$this->db->from('table_pengikut');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function create($data) {
		
		$this->db->insert('table_pengikut', $data);
	}
	
	public function update($data) {
		$this->db->update('table_pengikut', $data, array('telaah_id'=>$data['telaah_id'],'pegawai_id'=>$data['pegawai_id']));
	}
	
	public function delete($telaah_id) {
		$this->db->delete('table_pengikut', array('telaah_id' => $telaah_id));
	}
	
}