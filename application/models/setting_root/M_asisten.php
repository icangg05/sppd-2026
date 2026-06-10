<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_asisten extends CI_Model
{
	
	public function record_count() {
		return $this->db->count_all("table_asisten");
	}
	
	public function record_count_search($column, $data) {
		return $this->db->count_all("table_asisten WHERE $column like '%$data%'");
	}
	
	public function data($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_asisten');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_asisten');
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get($asisten_id) {
		$this->db->where('asisten_id', $asisten_id);
		$query = $this->db->get('table_asisten', 1);
		return $query->result_array();
	}
	
	public function create($data) {
		
		$this->db->insert('table_asisten', $data);
	}
	
	public function update($data) {
		$this->db->update('table_asisten', $data, array('asisten_id'=>$data['asisten_id']));
	}
	
	public function delete($asisten_id) {
		$this->db->delete('table_asisten', array('asisten_id' => $asisten_id));
	}
	
	public function provinsi() {
		$this->db->select('*');
		$this->db->from('table_provinsi');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kabupaten() {
		$this->db->select('*');
		$this->db->from('table_asisten');
		$this->db->where('provinsi_id',74);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kabupaten2($provinsi_id) {
		$this->db->select('*');
		$this->db->from('table_asisten');
		$this->db->where('provinsi_id',$provinsi_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
}