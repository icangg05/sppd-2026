<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_sub_bagian extends CI_Model
{
	
	public function record_count() {
		return $this->db->count_all("table_subbagian");
	}
	
	public function record_count_search($column, $data) {
		return $this->db->count_all("table_subbagian WHERE $column like '%$data%'");
	}
	
	public function data($limit, $start) {
		$this->db->select('table_subbagian.subbagian_id, nama_subbagian, nama_bagian');
		$this->db->from('table_subbagian');
		$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id','left');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search($column, $value, $limit, $start) {
		$this->db->select('table_subbagian.subbagian_id, nama_subbagian, nama_bagian');
		$this->db->from('table_subbagian');
		$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id','left');
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get($subbagian_id) {
		$this->db->where('subbagian_id', $subbagian_id);
		$query = $this->db->get('table_subbagian', 1);
		return $query->result_array();
	}
	
	public function create($data) {
		
		$this->db->insert('table_subbagian', $data);
	}
	
	public function update($data) {
		$this->db->update('table_subbagian', $data, array('subbagian_id'=>$data['subbagian_id']));
	}
	
	public function delete($subbagian_id) {
		$this->db->delete('table_subbagian', array('subbagian_id' => $subbagian_id));
	}
	
	public function bagian() {
		$this->db->select('*');
		$this->db->from('table_bagian');
		$query = $this->db->get ();
		return $query->result();
	}
	
}