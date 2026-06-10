<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_sekda extends CI_Model
{
	// TAMPILAN DASHBOARD-----------------------------------------------------------------------------------------//
	
	public function total_pegawai() {
		$this->db->select('count(*) as total_pegawai');
		$this->db->from('table_pegawai');
		$this->db->where('skpd_id',3);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_pegawai_sekda($bagian_id) {
		$this->db->select('count(*) as total_pegawai');
		$this->db->from('table_pegawai');
		$this->db->where('skpd_id',3);
		$this->db->where('bagian_id',$bagian_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah() {
		$this->db->select('count(*) as total_list_telaah');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',4);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah_masuk() {
		$this->db->select('count(*) as total_list_telaah_masuk');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',4);
		$this->db->where('telaah_status', 0);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah_diproses() {
		$this->db->select('count(*) as total_list_telaah_diproses');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',4);
		$this->db->where('telaah_status', 1);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah_diterima() {
		$this->db->select('count(*) as total_list_telaah_diterima');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',4);
		$this->db->where('telaah_status', 2);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah_ditolak() {
		$this->db->select('count(*) as total_list_telaah_ditolak');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',4);
		$this->db->where('telaah_status', 3);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	// TAMPILAN DASHBOARD STAFF SEKDA-----------------------------------------------------------------------------------------//
		
	public function total_list_telaah_staff($bagian_id) {
		$this->db->select('count(*) as total_list_telaah_staff');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
		$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id', 'LEFT');
		$this->db->where('(table_telaah.telaah_kategori=9)');
		$this->db->where('table_bagian.bagian_id',$bagian_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah_masuk_staff($bagian_id) {
		$this->db->select('count(*) as total_list_telaah_masuk_staff');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
		$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id', 'LEFT');
		$this->db->where('(table_telaah.telaah_kategori=9)');
		$this->db->where('table_bagian.bagian_id',$bagian_id);
		$this->db->where('telaah_status', 0);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah_diproses_staff($bagian_id) {
		$this->db->select('count(*) as total_list_telaah_diproses_staff');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
		$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id', 'LEFT');
		$this->db->where('(table_telaah.telaah_kategori=9)');
		$this->db->where('table_bagian.bagian_id',$bagian_id);
		$this->db->where('telaah_status', 1);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah_diterima_staff($bagian_id) {
		$this->db->select('count(*) as total_list_telaah_diterima_staff');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
		$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id', 'LEFT');
		$this->db->where('(table_telaah.telaah_kategori=9)');
		$this->db->where('table_bagian.bagian_id',$bagian_id);
		$this->db->where('telaah_status', 2);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah_ditolak_staff($bagian_id) {
		$this->db->select('count(*) as total_list_telaah_ditolak_staff');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
		$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id', 'LEFT');
		$this->db->where('(table_telaah.telaah_kategori=9)');
		$this->db->where('table_bagian.bagian_id',$bagian_id);
		$this->db->where('telaah_status', 3);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	//-----------------------------------------------------------------------------------------//
	
	//Model Staff Sekda
	public function record_count_staffsekda() {
		return $this->db->count_all("table_telaah WHERE telaah_kategori='9'");
	}
	
	//Model Staff Sekda Bagian
	public function record_count_staffsekda_bag($bagian_id) {
		return $this->db->count_all("table_telaah 
									LEFT JOIN table_relasi_sekda ON table_telaah.telaah_id=table_relasi_sekda.telaah_id
									LEFT JOIN table_subbagian ON table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id
									LEFT JOIN table_bagian ON table_bagian.bagian_id=table_subbagian.bagian_id
									WHERE telaah_kategori='9'
									AND table_bagian.bagian_id='$bagian_id' ");
	}
	
	public function record_count_search_staffsekda($column, $data) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='9'AND $column like '%$data%'");
	}
	
	public function record_count_search_staffsekda_bag($column, $data, $bagian_id) {
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
		$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id', 'LEFT');
		$this->db->where('(table_telaah.telaah_kategori=9)');
		$this->db->where('table_bagian.bagian_id',$bagian_id);
		$this->db->like($column,$data);
		return $this->db->count_all_results(); 
	}
	
	public function data_staffsekda($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
		$this->db->where('(table_telaah.telaah_kategori=9)');
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_staffsekda_bag($limit, $start, $bagian_id) {
		$this->db->select('*, table_telaah.telaah_id as telaah_id');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
		$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id', 'LEFT');
		$this->db->where('(table_telaah.telaah_kategori=9)');
		$this->db->where('table_bagian.bagian_id',$bagian_id);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search_staffsekda($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
		$this->db->where('(table_telaah.telaah_kategori=9)');
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search_staffsekda_bag($column, $value, $limit, $start, $bagian_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
		$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id', 'LEFT');
		$this->db->where('(table_telaah.telaah_kategori=9)');
		$this->db->where('table_bagian.bagian_id',$bagian_id);
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	//
	public function total_list_telaah_sekda($subbagian_id) {
		$this->db->select('count(*) as total_list_telaah');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_relasi_sekda.telaah_id = table_telaah.telaah_id');
		$this->db->where('table_telaah.telaah_kategori',4);
		$this->db->where('table_relasi_sekda.subbagian_id',$subbagian_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah_diterima_sekda($subbagian_id) {
		$this->db->select('count(*) as total_list_telaah_diterima');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_relasi_sekda.telaah_id = table_telaah.telaah_id');
		$this->db->where('table_telaah.telaah_kategori',4);
		$this->db->where('table_relasi_sekda.subbagian_id',$subbagian_id);
		$this->db->where('telaah_status', 2);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah_diproses_sekda($subbagian_id) {
		$this->db->select('count(*) as total_list_telaah_diproses');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_relasi_sekda.telaah_id = table_telaah.telaah_id');
		$this->db->where('table_telaah.telaah_kategori',4);
		$this->db->where('table_relasi_sekda.subbagian_id',$subbagian_id);
		$this->db->where('telaah_status', 1);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah_ditolak_sekda($subbagian_id) {
		$this->db->select('count(*) as total_list_telaah_ditolak');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_relasi_sekda.telaah_id = table_telaah.telaah_id');
		$this->db->where('table_telaah.telaah_kategori',4);
		$this->db->where('table_relasi_sekda.subbagian_id',$subbagian_id);
		$this->db->where('telaah_status', 3);
		$query = $this->db->get ();
		return $query->result_array();
	}
	public function total_skpd(){
		$this->db->select('count(*) as total_skpd');
		$this->db->from('table_skpd');
		$this->db->where('skpd_id',3);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	//-----------------------------------------------------------------------------------------//
	
	public function record_count() {
		return $this->db->count_all("table_telaah 
			WHERE telaah_kategori='4'");
	}
	
	public function record_count_search($column, $data) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='4' 
			AND $column like '%$data%'");
	}
	
	public function data($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->where('table_telaah.telaah_kategori',4);
		$this->db->order_by('telaah_waktuinput','DESC');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->where('table_telaah.telaah_kategori',4);
		$this->db->order_by('telaah_waktuinput','DESC');
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function record_count2($subbagian_id) {
		return $this->db->count_all("table_telaah 
			JOIN table_relasi_sekda ON table_relasi_sekda.telaah_id = table_telaah.telaah_id
			WHERE telaah_kategori='4'
			AND subbagian_id='$subbagian_id'");
	}
	public function record_count3($subbagian_id) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			JOIN table_relasi_sekda ON table_relasi_sekda.telaah_id = table_telaah.telaah_id
			WHERE telaah_kategori='9'
			AND subbagian_id='$subbagian_id'");
	}
	public function record_countwalikota() {
		return $this->db->count_all("table_telaah 
			WHERE telaah_kategori='8'
			");
	}
	
	public function record_count_search2($column, $data, $subbagian_id) {
		return $this->db->count_all("table_telaah 
			JOIN table_relasi_sekda ON table_relasi_sekda.telaah_id = table_telaah.telaah_id
			WHERE telaah_kategori='4' 
			AND subbagian_id='$subbagian_id'
			AND $column like '%$data%'");
	}
	public function record_count_search3($column, $data, $subbagian_id) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			JOIN table_relasi_sekda ON table_relasi_sekda.telaah_id = table_telaah.telaah_id
			WHERE telaah_kategori='9' 
			AND subbagian_id='$subbagian_id'
			AND $column like '%$data%'");
	}
	
	public function record_count_search_walikota($column, $data) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pimpinan ON table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id
			WHERE telaah_kategori='8' 
			AND $column like '%$data%'");
	}
	
	//telaah kategori 4
	public function data2($limit, $start, $subbagian_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_relasi_sekda.telaah_id = table_telaah.telaah_id');
		$this->db->where('table_telaah.telaah_kategori',4);
		$this->db->where('table_relasi_sekda.subbagian_id',$subbagian_id);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	//telaah kategori 9
	public function datakasubagstaf($limit, $start, $subbagian_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_relasi_sekda.telaah_id = table_telaah.telaah_id');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
		$this->db->where('table_telaah.telaah_kategori',9);
		$this->db->where('table_relasi_sekda.subbagian_id',$subbagian_id);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	public function datawalikota($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pimpinan','table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id','left');
		$this->db->join('table_skpd','table_pimpinan.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',8);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search_walikota($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pimpinan','table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id','left');
		$this->db->join('table_skpd','table_pimpinan.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',8);
		$this->db->like($column,$value);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search2($column, $value, $limit, $start, $subbagian_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_relasi_sekda.telaah_id = table_telaah.telaah_id');
		$this->db->where('table_telaah.telaah_kategori',4);
		$this->db->where('table_relasi_sekda.subbagian_id',$subbagian_id);
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search3($column, $value, $limit, $start, $subbagian_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_relasi_sekda.telaah_id = table_telaah.telaah_id');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
		$this->db->where('table_telaah.telaah_kategori',9);
		$this->db->where('table_relasi_sekda.subbagian_id',$subbagian_id);
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	//kategori 4 dan 9
	public function get($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_provinsi','table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');
		$this->db->join('table_kabkot','table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id=table_telaah.telaah_pelaksana', 'LEFT');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id', 'LEFT');
		$this->db->join('table_anggaran','table_anggaran.id_anggaran=table_telaah.telaah_kegiatan', 'LEFT');
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getWalikota($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_provinsi','table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');
		$this->db->join('table_kabkot','table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');
		$this->db->join('table_pimpinan','table_pimpinan.pegawai_id=table_telaah.telaah_pelaksana', 'LEFT');
		$this->db->join('table_skpd','table_pimpinan.skpd_id=table_skpd.skpd_id', 'LEFT');
		$this->db->join('table_anggaran','table_anggaran.id_anggaran=table_telaah.telaah_kegiatan', 'LEFT');
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function getLast() {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->order_by('telaah_id','DESC');
		$this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function create($data) {
		
		$this->db->insert('table_telaah', $data);
	}
	
	public function pegawai($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->where('status_delete',0);
		//$this->db->where('status',0);
		$this->db->where('skpd_id', $skpd_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function pegawai_setda($skpd_id, $bagian_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->where('status_delete',0);
		//$this->db->where('status',0);
		$this->db->where('skpd_id', $skpd_id);
		$this->db->where('bagian_id', $bagian_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function pegawaiall() {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->where('status_delete',0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function pegawaiall_optimized() {
		$this->db->select('pegawai_id, pegawai_nip, pegawai_nama');
		$this->db->from('table_pegawai');
		$this->db->where('status_delete',0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function search_pegawai($term) {
		$this->db->select('pegawai_id, pegawai_nip, pegawai_nama');
		$this->db->from('table_pegawai');
		$this->db->where('status_delete', 0);
		$this->db->group_start();
		$this->db->like('pegawai_nama', $term);
		$this->db->or_like('pegawai_nip', $term);
		$this->db->group_end();
		$this->db->limit(50);
		$query = $this->db->get();
		return $query->result();
	}

	public function pimpinan() {
		$this->db->select('*');
		$this->db->from('table_pimpinan');
		$this->db->where('status_delete',0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function getTimeline($id) {
		$this->db->where('telaah_id', $id);
		$query = $this->db->get('table_timeline4', 4);
		return $query->result_array();
	}
	public function getTimeline8($id) {
		$this->db->where('telaah_id', $id);
		$query = $this->db->get('table_timeline8', 8);
		return $query->result_array();
	}
	
	public function getTimeline9($id) {
		$this->db->where('telaah_id', $id);
		$query = $this->db->get('table_timeline9', 9);
		return $query->result_array();
	}
	
	public function anggaran($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_anggaran');
		$this->db->where('skpd_id',$skpd_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function anggaran_setda($skpd_id, $bagian_id) {
		$this->db->select('*');
		$this->db->from('table_anggaran');
		$this->db->where('skpd_id', $skpd_id);
		$this->db->where('bagian_id', $bagian_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function rekening($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_rekening');
		$this->db->where('skpd_id',$skpd_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_provinsi() {
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
	
	public function get_kecamatan() {
		$this->db->select('*');
		$this->db->from('table_kecamatan');
		$this->db->join('table_kabkot','table_kecamatan.kabkot_id = table_kabkot.kabkot_id', 'LEFT');
		$this->db->join('table_provinsi','table_kabkot.provinsi_id = table_provinsi.provinsi_id', 'LEFT');
		$this->db->where('table_provinsi.provinsi_id',74);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kecamatan_ddak() {
		$this->db->select('*');
		$this->db->from('table_kecamatan');
		$this->db->join('table_kabkot','table_kecamatan.kabkot_id = table_kabkot.kabkot_id', 'LEFT');
		$this->db->join('table_provinsi','table_kabkot.provinsi_id = table_provinsi.provinsi_id', 'LEFT');
		$this->db->where('table_provinsi.provinsi_id',74);
		$this->db->where('table_kabkot.kabkot_id',7471);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kecamatan_dddk() {
		$this->db->select('*');
		$this->db->from('table_kecamatan');
		$this->db->where('kec_id',747101);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function get_kabkot($provinsi_id){
		$this->db->where('provinsi_id',$provinsi_id);
		$this->db->order_by('kabupaten_kota','asc');
		$kelurahan=$this->db->get('table_kabkot');
		if($kelurahan->num_rows()>0){
			foreach ($kelurahan->result_array() as $row)
			{
				$result['']= '- Pilih Kabupaten/Kota -';
				$result[$row['kabkot_id']]= $row['kabupaten_kota'];
			}
		} else {
			$result['']= '- Belum Ada Kabupaten/Kota -';
		}
		return $result;
	}
	
	public function get_kec($kabkot_id){
		$this->db->where('kabkot_id',$kabkot_id);
		$this->db->order_by('kecamatan','asc');
		$kelurahan=$this->db->get('table_kecamatan');
		if($kelurahan->num_rows()>0){
			foreach ($kelurahan->result_array() as $row)
			{
				$result['']= '- Pilih Kecamatan -';
				$result[$row['kec_id']]= $row['kecamatan'];
			}
		} else {
			$result['']= '- Belum Ada Kecamatan -';
		}
		return $result;
	}
	
	public function posisi_walikota() {
		$this->db->select('*');
		$this->db->from('table_setting');
		$this->db->where('setting_id', 1);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	/*Fungsi delete telaah*/
	public function delete_telaah($telaah_id, $table) {
    $this->db->delete($table, array('telaah_id' => $telaah_id));
  }
}