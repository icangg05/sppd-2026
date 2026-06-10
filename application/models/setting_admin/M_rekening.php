<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_rekening extends CI_Model
{
	
	public function record_count($skpd_id) {
		return $this->db->count_all("table_rekening WHERE skpd_id = '$skpd_id'");
	}
	
	public function record_count_search($column, $data, $skpd_id) {
		return $this->db->count_all("table_rekening WHERE skpd_id = '$skpd_id' AND $column like '%$data%'");
	}
	
	public function data($limit, $start, $skpd_id) {
		$this->db->select('*');
		$this->db->from('table_rekening');
		$this->db->join('table_skpd','table_rekening.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_rekening.skpd_id',$skpd_id);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search($column, $value, $limit, $start, $skpd_id) {
		$this->db->select('*');
		$this->db->from('table_rekening');
		$this->db->join('table_skpd','table_rekening.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_rekening.skpd_id',$skpd_id);
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get($id_rekening) {
		$this->db->where('id_rekening', $id_rekening);
		$query = $this->db->get('table_rekening', 1);
		return $query->result_array();
	}
	
	public function rekening_opd($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_rekening');
		$query = $this->db->get ();
		return $query->result();
	}
	
	
	public function create($data) {
		
		$this->db->insert('table_rekening', $data);
	}
	
	public function update($data) {
		$this->db->update('table_rekening', $data, array('id_rekening'=>$data['id_rekening']));
	}
	
	public function delete($id_rekening) {
		$this->db->delete('table_rekening', array('id_rekening' => $id_rekening));
	}
	
	
	public function skpd() {
		$this->db->select('*');
		$this->db->from('table_skpd');
		$query = $this->db->get ();
		return $query->result();
	}
	
}