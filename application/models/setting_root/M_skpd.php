<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_skpd extends CI_Model
{
	
	public function record_count() {
		return $this->db->count_all('table_skpd');
	}
	
	public function record_count_search($column, $data) {
		return $this->db->count_all("table_skpd WHERE $column like '%$data%'");
	}
	
	public function data($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_skpd');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_skpd');
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get($skpd_id) {
		
		$this->db->select('a.*, table_relasi_kelurahan.*, b.skpd_id as id_kecamatan, b.skpd_nama as nama_kecamatan');
		$this->db->from('table_skpd a');
		$this->db->join('table_relasi_kelurahan','a.skpd_id=table_relasi_kelurahan.id_kelurahan','left');
		$this->db->join('table_skpd b','b.skpd_id=table_relasi_kelurahan.id_kecamatan','left');
		$this->db->where('a.skpd_id', $skpd_id);
		$this->db->limit (1);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function get_kecamatan() {
		$this->db->select('*');
		$this->db->from('table_skpd');
		$this->db->where('jenis_skpd',4);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function getLast() {
		$this->db->select('*');
		$this->db->from('table_skpd');
		$this->db->order_by('skpd_id','DESC');
		$this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function create($data) {
		
		$this->db->insert('table_skpd', $data);
	}
	
	public function update($data) {
		$this->db->update('table_skpd', $data, array('skpd_id'=>$data['skpd_id']));
	}
	
	public function delete($skpd_id) {
		$this->db->delete('table_skpd', array('skpd_id' => $skpd_id));
	}
	public function link_gambar($skpd_id)
	{
		
		$this->db->where('skpd_id',$skpd_id);
		$query = $getData = $this->db->get('table_skpd');
		if($getData->num_rows() > 0)
			return $query;
		else
			return null;
		
	}
	
}