<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_provinsi extends CI_Model
{
	
	public function record_count() {
		return $this->db->count_all("table_provinsi");
	}
	
	public function record_count_search($column, $data) {
		return $this->db->count_all("table_provinsi WHERE $column like '%$data%'");
	}
	
	public function data($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_provinsi');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_provinsi');
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get($provinsi_id) {
		$this->db->where('provinsi_id', $provinsi_id);
		$query = $this->db->get('table_provinsi', 1);
		return $query->result_array();
	}
	
	public function create($data) {
		
		$this->db->insert('table_provinsi', $data);
	}
	
	public function update($data) {
		$this->db->update('table_provinsi', $data, array('provinsi_id'=>$data['provinsi_id']));
	}
	
	public function delete($provinsi_id) {
		$this->db->delete('table_provinsi', array('provinsi_id' => $provinsi_id));
	}
	public function get_provinsi() {		$this->db->select('*');		$this->db->from('table_provinsi');		$query = $this->db->get ();		return $query->result();	}	
}