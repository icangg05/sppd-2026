<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_anggota extends CI_Model
{
	
	public function record_count() {
		return $this->db->count_all("table_anggotadprd");
	}
	
	public function record_count_search($column, $data) {
		return $this->db->count_all("table_anggotadprd");
	}
	
	public function data($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_anggotadprd');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_anggotadprd');
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get($anggotadprd_id) {
		$this->db->where('anggotadprd_id', $anggotadprd_id);
		$query = $this->db->get('table_anggotadprd');
		return $query->result_array();
	}
	
	public function anggota($anggotadprd_id) {
		$this->db->select('*');
		$this->db->from('table_anggotadprd');
		$this->db->where('anggotadprd_name', $anggotadprd_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_status($pegawai_id, $status) {
		$this->db->select('*, anggotadprd_id as pegawai_id, anggotadprd_name as pegawai_nama, anggotadprd_jabatan as pegawai_namajabatan');
		$this->db->from('table_anggotadprd');
		$this->db->where('status', $status);
		$this->db->where('anggotadprd_id', $pegawai_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function create($data) {
		
		$this->db->insert('table_anggotadprd', $data);
	}
	
	public function update($data) {
		$this->db->update('table_anggotadprd', $data, array('anggotadprd_id'=>$data['anggotadprd_id']));
	}
	
	public function delete($anggotadprd_id) {
		$this->db->delete('table_anggotadprd', array('anggotadprd_id' => $anggotadprd_id));
	}
	
}