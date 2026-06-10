<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_camat extends CI_Model
{
	// TAMPILAN DASHBOARD-----------------------------------------------------------------------------------------//
	
	// Total Pegawai Camat
	public function total_pegawai($skpd_id) {
		$this->db->select('count(*) as total_pegawai');
		$this->db->from('table_pegawai');
		$this->db->where('skpd_id',$skpd_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	// Total List Telaah Camat
	public function total_list_telaah($skpd_id) {
		$this->db->select('count(*) as total_list_telaah');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',5);
		$this->db->where('skpd_id',$skpd_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	// Total List Telaah Camat yang masuk
	public function total_list_telaah_masuk($skpd_id) {
		$this->db->select('count(*) as total_list_telaah_masuk');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',5);
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('telaah_status', 0);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	// Total List Telaah Camat yang diproses
	public function total_list_telaah_diproses($skpd_id) {
		$this->db->select('count(*) as total_list_telaah_diproses');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',5);
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('telaah_status', 1);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	// Total List Telaah Camat yang diterima
	public function total_list_telaah_diterima($skpd_id) {
		$this->db->select('count(*) as total_list_telaah_diterima');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',5);
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('telaah_status', 2);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	// Total List Telaah Camat yang ditolak
	public function total_list_telaah_ditolak($skpd_id) {
		$this->db->select('count(*) as total_list_telaah_ditolak');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',5);
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('telaah_status', 3);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	//-----------------------------------------------------------------------------------------//
	
	public function record_count($skpd_id) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='5'AND skpd_id='$skpd_id' ");
	}
	
	public function record_count_search($column, $data, $skpd_id) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='5' AND skpd_id='$skpd_id' AND $column like '%$data%'");
	}
	
	public function data($limit, $start, $skpd_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->where('table_telaah.telaah_kategori',5);
		$this->db->where('skpd_id',$skpd_id);
		$this->db->order_by('telaah_waktuinput','DESC');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search($column, $value, $limit, $start, $skpd_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->where('table_telaah.telaah_kategori',5);
		$this->db->where('skpd_id',$skpd_id);
		$this->db->order_by('telaah_waktuinput','DESC');
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_provinsi','table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');
		$this->db->join('table_kabkot','table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id=table_telaah.telaah_pelaksana', 'LEFT');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');
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
	
	public function getTimeline($id) {
		$this->db->where('telaah_id', $id);
		$query = $this->db->get('table_timeline5', 1);
		return $query->result_array();
	}
	
	public function anggaran($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_anggaran');
		$this->db->where('skpd_id',$skpd_id);
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