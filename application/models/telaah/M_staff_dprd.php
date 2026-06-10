<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_staff_dprd extends CI_Model
{
	// TAMPILAN DASHBOARD-----------------------------------------------------------------------------------------//
	
	public function total_list_telaah() {
		$this->db->select('count(*) as total_list_telaah');
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',6);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah_masuk() {
		$this->db->select('count(*) as total_list_telaah_masuk');
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',6);
		$this->db->where('telaah_status', 0);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah_diproses() {
		$this->db->select('count(*) as total_list_telaah_diproses');
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',6);
		$this->db->where('telaah_status', 1);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah_diterima() {
		$this->db->select('count(*) as total_list_telaah_diterima');
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',6);
		$this->db->where('telaah_status', 2);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah_ditolak() {
		$this->db->select('count(*) as total_list_telaah_ditolak');
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',6);
		$this->db->where('telaah_status', 3);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	//-----------------------------------------------------------------------------------------//
	
	
	public function record_count() {
		return $this->db->count_all("table_telaah WHERE telaah_kategori='6'");
	}
	
	public function record_count_search($column, $data) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='3' AND $column like '%$data%'");
	}
	
	public function data($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->where('table_telaah.telaah_kategori',6);
		$this->db->order_by('telaah_waktuinput','DESC');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->where('table_telaah.telaah_kategori',6);
		$this->db->order_by('telaah_waktuinput','DESC');
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search2($column, $value, $limit, $start) {
		//$qs = implode('* ', explode(' ', $value)).'*'; 
		$qs = implode('|', explode(' ', $value)).''; 
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->where('table_telaah.telaah_kategori',6);
		//$this->db->where("MATCH ($column) AGAINST ('$qs')", NULL, FALSE);
		$this->db->where("$column REGEXP '$qs'");
		$this->db->order_by('telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
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
		$query = $this->db->get('table_timeline6', 6);
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
	
	public function posisi_kadprd() {
		$this->db->select('*');
		$this->db->from('table_skpd');
		$this->db->where('skpd_id', 2);
		$query = $this->db->get ();
		return $query->result_array();
	}
	public function update_timeline($data) {
		$this->db->update('table_timeline6', $data, array('telaah_id'=>$data['telaah_id']));
	}
	
	/*Fungsi delete telaah*/
	public function delete_telaah($telaah_id, $table) {
    $this->db->delete($table, array('telaah_id' => $telaah_id));
  }
	
}