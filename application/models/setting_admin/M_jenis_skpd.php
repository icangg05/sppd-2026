<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_jenis_skpd extends CI_Model
{
	
	public function record_count() {
		return $this->db->count_all('table_jenis_skpd');
	}
	
	public function record_count_search($column, $data) {
		return $this->db->count_all("table_jenis_skpd WHERE $column like '%$data%'");
	}
	
	public function data($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_jenis_skpd');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_jenis_skpd');
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	
	public function get_all() {
		$this->db->select('*');
		$this->db->from('table_jenis_skpd');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get($jenis_skpd_id) {
		$this->db->where('jenis_skpd_id', $jenis_skpd_id);
		$query = $this->db->get('table_jenis_skpd', 1);
		return $query->result_array();
	}
	
	public function create($data) {
		
		$this->db->insert('table_jenis_skpd', $data);
	}
	
	public function update($data) {
		$this->db->update('table_jenis_skpd', $data, array('jenis_skpd_id'=>$data['jenis_skpd_id']));
	}
	
	public function delete($jenis_skpd_id) {
		$this->db->delete('table_jenis_skpd', array('jenis_skpd_id' => $jenis_skpd_id));
	}
	
}