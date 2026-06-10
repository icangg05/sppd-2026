<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_bagian extends CI_Model
{
	
	public function record_count() {
		return $this->db->count_all("table_bagian");
	}
	
	public function record_count_search($column, $data) {
		return $this->db->count_all("table_bagian WHERE $column like '%$data%'");
	}
	
	public function data($limit, $start) {
		$this->db->select('table_bagian.bagian_id, nama_bagian, nama_asisten');
		$this->db->from('table_bagian');
		$this->db->join('table_asisten','table_asisten.asisten_id=table_bagian.asisten_id','left');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search($column, $value, $limit, $start) {
		$this->db->select('table_bagian.bagian_id, nama_bagian, nama_asisten');
		$this->db->from('table_bagian');
		$this->db->join('table_asisten','table_asisten.asisten_id=table_bagian.asisten_id','left');
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get($bagian_id) {
		$this->db->where('bagian_id', $bagian_id);
		$query = $this->db->get('table_bagian', 1);
		return $query->result_array();
	}
	
	public function create($data) {
		
		$this->db->insert('table_bagian', $data);
	}
	
	public function update($data) {
		$this->db->update('table_bagian', $data, array('bagian_id'=>$data['bagian_id']));
	}
	
	public function delete($bagian_id) {
		$this->db->delete('table_bagian', array('bagian_id' => $bagian_id));
	}
	
	public function asisten() {
		$this->db->select('*');
		$this->db->from('table_asisten');
		$query = $this->db->get ();
		return $query->result();
	}	
}