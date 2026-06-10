<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_lokasi_tujuan extends CI_Model
{
	
	public function get($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_lokasi_tujuan');
		$this->db->join('table_provinsi','table_provinsi.provinsi_id=table_lokasi_tujuan.provinsi_id', 'LEFT');
		$this->db->join('table_kabkot','table_kabkot.kabkot_id=table_lokasi_tujuan.kabkot_id', 'LEFT');
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get();
		return $query->result();
	}
	
	public function get2($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_lokasi_tujuan');
		$this->db->join('table_provinsi','table_provinsi.provinsi_id=table_lokasi_tujuan.provinsi_id', 'LEFT');
		$this->db->join('table_kabkot','table_kabkot.kabkot_id=table_lokasi_tujuan.kabkot_id', 'LEFT');
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function create($data) {
		$this->db->insert('table_lokasi_tujuan', $data);
	}
	
	
	public function delete($lokasi_tujuan_id) {
		$this->db->delete('table_lokasi_tujuan', array('lokasi_tujuan_id' => $lokasi_tujuan_id));
	}
	
}