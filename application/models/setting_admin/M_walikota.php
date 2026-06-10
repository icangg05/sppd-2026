<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_walikota extends CI_Model
{
	
	public function record_count() {
		return $this->db->count_all("table_pimpinan where status_delete = '0'");
	}
	
	public function record_count_search($column, $data) {
		return $this->db->count_all("table_pimpinan");
	}
	
	public function data($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_pimpinan');
		$this->db->where('status_delete', 0);
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function data_search($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_pimpinan');
		$this->db->where('status_delete', 0);
		$this->db->like($column,$value);
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function get($pegawai_id) {
		$this->db->where('pegawai_id', $pegawai_id);
		$query = $this->db->get('table_pimpinan');
		return $query->result_array();
	}
	
	public function create($data) {
		
		$this->db->insert('table_pimpinan', $data);
	}
	
	public function update($data) {
		$this->db->update('table_pimpinan', $data, array('pegawai_id'=>$data['pegawai_id']));
	}
	
	public function delete($pegawai_id) {
		$this->db->delete('table_pimpinan', array('pegawai_id' => $pegawai_id));
	}
}