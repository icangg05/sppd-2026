<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_kabupaten extends CI_Model
{
	
	public function record_count() {
		return $this->db->count_all("table_kabkot");
	}
	
	public function record_count_search($column, $data) {
		return $this->db->count_all("table_kabkot WHERE $column like '%$data%'");
	}
	
	public function data($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_kabkot');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_kabkot');
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get($kabkot_id) {
		$this->db->where('kabkot_id', $kabkot_id);
		$query = $this->db->get('table_kabkot', 1);
		return $query->result_array();
	}
	
	public function create($data) {
		
		$this->db->insert('table_kabkot', $data);
	}
	
	public function update($data) {
		$this->db->update('table_kabkot', $data, array('kabkot_id'=>$data['kabkot_id']));
	}
	
	public function delete($kabkot_id) {
		$this->db->delete('table_kabkot', array('kabkot_id' => $kabkot_id));
	}
	
	public function provinsi() {
		$this->db->select('*');
		$this->db->from('table_provinsi');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kabupaten() {
		$this->db->select('*');
		$this->db->from('table_kabkot');
		$this->db->where('provinsi_id',74);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kabupaten2($provinsi_id) {
		$this->db->select('*');
		$this->db->from('table_kabkot');
		$this->db->where('provinsi_id',$provinsi_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
}