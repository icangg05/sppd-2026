<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_log_tte extends CI_Model
{
	function __construct() {
		parent::__construct();
	}
	
	public function data($limit, $start, $skpd_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','LEFT');
		if($skpd_id){
			$this->db->where('table_telaah.telaah_skpd_id',$skpd_id);
		}
		$this->db->order_by('telaah_id','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function data_search($column, $value, $limit, $start, $skpd_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','LEFT');
		if($skpd_id){
			$this->db->where('table_telaah.telaah_skpd_id',$skpd_id);
		}
		$this->db->order_by('telaah_id','DESC');
		$this->db->like($column,$value);
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function data2($limit, $start, $telaah_id) {
		$this->db->select('*');
		$this->db->from('core_log_tte');
		$this->db->join('table_pegawai','core_log_tte.pegawai_id=table_pegawai.pegawai_id','LEFT');
		$this->db->where('telaah_id',$telaah_id);
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	
}