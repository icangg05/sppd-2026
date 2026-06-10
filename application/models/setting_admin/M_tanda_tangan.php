<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_tanda_tangan extends CI_Model
{
	
	public function record_count($skpd_id) {
		return $this->db->count_all("table_tanda_tangan WHERE skpd_id = '$skpd_id'");
	}
	
	public function record_count_setda($skpd_id, $bagian_id) {
		return $this->db->count_all("table_tanda_tangan 
									 WHERE skpd_id = '$skpd_id'
									 AND bagian_id = '$bagian_id'");
	}
	
	public function record_count_search($column, $data, $skpd_id) {
		return $this->db->count_all("table_tanda_tangan WHERE skpd_id = '$skpd_id' AND $column like '%$data%'");
	}
	
	public function record_count_search_setda($column, $data, $skpd_id, $bagian_id) {
		return $this->db->count_all("table_tanda_tangan 
									WHERE skpd_id = '$skpd_id' 
									AND bagian_id = '$bagian_id' 
									AND $column like '%$data%'");
	}
	
	public function data($limit, $start, $skpd_id) {
		$this->db->select('*');
		$this->db->from('table_tanda_tangan');
		$this->db->join('table_jabatan','table_tanda_tangan.jabatan_id = table_jabatan.jabatan_id', 'LEFT');
		$this->db->where('skpd_id',$skpd_id);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_setda($limit, $start, $skpd_id, $bagian_id) {
		$this->db->select('*');
		$this->db->from('table_tanda_tangan');
		$this->db->join('table_jabatan','table_tanda_tangan.jabatan_id = table_jabatan.jabatan_id', 'LEFT');
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('bagian_id',$bagian_id);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search($column, $value, $skpd_id, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_tanda_tangan');
		$this->db->join('table_jabatan','table_tanda_tangan.jabatan_id = table_jabatan.jabatan_id', 'LEFT');
		$this->db->where('skpd_id',$skpd_id);
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search_setda($column, $value, $skpd_id, $limit, $start, $bagian_id) {
		$this->db->select('*');
		$this->db->from('table_tanda_tangan');
		$this->db->join('table_jabatan','table_tanda_tangan.jabatan_id = table_jabatan.jabatan_id', 'LEFT');
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('bagian_id',$bagian_id);
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get($tanda_tangan_id) {
		
		$this->db->select('*');
		$this->db->from('table_tanda_tangan');
		$this->db->join('table_jabatan','table_tanda_tangan.jabatan_id = table_jabatan.jabatan_id', 'LEFT');
		$this->db->where('tanda_tangan_id', $tanda_tangan_id);
		$this->db->limit (1);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function create($data) {
		
		$this->db->insert('table_tanda_tangan', $data);
	}
	
	public function update($data) {
		$this->db->update('table_tanda_tangan', $data, array('tanda_tangan_id'=>$data['tanda_tangan_id']));
	}
	
	public function delete($tanda_tangan_id) {
		$this->db->delete('table_tanda_tangan', array('tanda_tangan_id' => $tanda_tangan_id));
	}
	public function link_gambar($tanda_tangan_id)
	{
		
		$this->db->where('tanda_tangan_id',$tanda_tangan_id);
		$query = $getData = $this->db->get('table_tanda_tangan');
		if($getData->num_rows() > 0)
			return $query;
		else
			return null;
		
	}
	
}