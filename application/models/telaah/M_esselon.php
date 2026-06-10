<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_esselon extends CI_Model
{
	
	// TAMPILAN DASHBOARD-----------------------------------------------------------------------------------------//
	
	// Total Pegawai OPD
	public function total_pegawai($skpd_id) {
		$this->db->select('count(*) as total_pegawai');
		$this->db->from('table_pegawai');
		$this->db->where('skpd_id',$skpd_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	// Total List Telaah Esselon
	public function total_list_telaah($skpd_id) {
		$this->db->select('count(*) as total_list_telaah');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id');
		$this->db->where('skpd_id',$skpd_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	// Total List Telaah Esselon yang Masuk
	public function total_list_telaah_masuk($skpd_id) {
		$this->db->select('count(*) as total_list_telaah_masuk');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id');
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('telaah_status', 0);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	// Total List Telaah Esselon yang diproses
	public function total_list_telaah_diproses($skpd_id) {
		$this->db->select('count(*) as total_list_telaah_diproses');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id');
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('telaah_status', 1);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	// Total List Telaah Esselon yang diterima
	public function total_list_telaah_diterima($skpd_id) {
		$this->db->select('count(*) as total_list_telaah_diterima');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id');
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('telaah_status', 2);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	// Total List Telaah Esselon yang ditolak
	public function total_list_telaah_ditolak($skpd_id) {
		$this->db->select('count(*) as total_list_telaah_ditolak');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id');
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('telaah_status', 3);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	#######################################
	// Total List Telaah Esselon Dinkes
	public function total_list_telaah_dinkes() {
		$this->db->select('count(*) as total_list_telaah');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
		$this->db->where('table_telaah.telaah_kategori',1);
		$this->db->where("(table_jenis_skpd.jenis_skpd_id = 7 OR table_jenis_skpd.jenis_skpd_id = 10)");
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	// Total List Telaah Esselon Dinkes yang masuk
	public function total_list_telaah_masuk_dinkes() {
		$this->db->select('count(*) as total_list_telaah_masuk');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
		$this->db->where('table_telaah.telaah_kategori',1);
		$this->db->where("(table_jenis_skpd.jenis_skpd_id = 7 OR table_jenis_skpd.jenis_skpd_id = 10)");
		$this->db->where('telaah_status', 0);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	// Total List Telaah Esselon Dinkes yang diproses
	public function total_list_telaah_diproses_dinkes() {
		$this->db->select('count(*) as total_list_telaah_diproses');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
		$this->db->where('table_telaah.telaah_kategori',1);
		$this->db->where("(table_jenis_skpd.jenis_skpd_id = 7 OR table_jenis_skpd.jenis_skpd_id = 10)");
		$this->db->where('telaah_status', 1);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	// Total List Telaah Esselon Dinkes yang diterima
	public function total_list_telaah_diterima_dinkes() {
		$this->db->select('count(*) as total_list_telaah_diterima');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
		$this->db->where('table_telaah.telaah_kategori',1);
		$this->db->where("(table_jenis_skpd.jenis_skpd_id = 7 OR table_jenis_skpd.jenis_skpd_id = 10)");
		$this->db->where('telaah_status', 2);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	// Total List Telaah Esselon Dinkes yang ditolak
	public function total_list_telaah_ditolak_dinkes() {
		$this->db->select('count(*) as total_list_telaah_ditolak');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
		$this->db->where('table_telaah.telaah_kategori',1);
		$this->db->where("(table_jenis_skpd.jenis_skpd_id = 7 OR table_jenis_skpd.jenis_skpd_id = 10)");
		$this->db->where('telaah_status', 3);
		$query = $this->db->get ();
		return $query->result_array();
	}
	//====================================================================================================
	
	
	public function record_count($skpd_id) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='1' AND skpd_id='$skpd_id' ");
	}
	
	public function record_count_search($column, $data, $skpd_id) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='1' AND skpd_id='$skpd_id' AND $column like '%$data%'");
	}
	
	public function data($limit, $start, $skpd_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->where('table_telaah.telaah_kategori',1);
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
		$this->db->where('table_telaah.telaah_kategori',1);
		$this->db->where('skpd_id',$skpd_id);
		$this->db->order_by('telaah_waktuinput','DESC');
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	/// Dinas Kesehatan
	public function record_count2() {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			LEFT JOIN table_skpd ON table_skpd.skpd_id=table_pegawai.skpd_id
			LEFT JOIN table_jenis_skpd ON table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd
			WHERE telaah_kategori='1'
			AND (table_jenis_skpd.jenis_skpd_id = 7 OR table_jenis_skpd.jenis_skpd_id = 10) ");
	}
	
	public function record_count_search2($column, $data) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			LEFT JOIN table_skpd ON table_skpd.skpd_id=table_pegawai.skpd_id
			LEFT JOIN table_jenis_skpd ON table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd
			WHERE telaah_kategori='1'
			AND (table_jenis_skpd.jenis_skpd_id = 7 OR table_jenis_skpd.jenis_skpd_id = 10)
			AND $column like '%$data%'");
	}
	
	public function data2($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
		$this->db->where('table_telaah.telaah_kategori',1);
		$this->db->where("(table_jenis_skpd.jenis_skpd_id = 7 OR table_jenis_skpd.jenis_skpd_id = 10)");
		$this->db->order_by('telaah_waktuinput','DESC');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data3($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
		$this->db->where('table_telaah.telaah_kategori',1);
		$this->db->where('table_jenis_skpd.jenis_skpd_id',7);
		if($limit){
			$this->db->limit ($limit, $start);
			$this->db->order_by('telaah_waktuinput','DESC');
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	
	public function data_search2($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
		$this->db->where('table_telaah.telaah_kategori',1);
		$this->db->where("(table_jenis_skpd.jenis_skpd_id = 7 OR table_jenis_skpd.jenis_skpd_id = 10)");
		$this->db->order_by('telaah_waktuinput','DESC');
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search3($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
		$this->db->where('table_telaah.telaah_kategori',1);
		$this->db->where('table_jenis_skpd.jenis_skpd_id',7);
		$this->db->like($column,$value);
		if($limit){
			$this->db->limit ($limit, $start);
			$this->db->order_by('telaah_waktuinput','DESC');
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
		$this->db->join('table_anggotadprd','table_anggotadprd.anggotadprd_id=table_telaah.telaah_pelaksana', 'LEFT');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');
		$this->db->join('table_anggaran','table_anggaran.id_anggaran=table_telaah.telaah_kegiatan', 'LEFT');
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
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
	
	public function get_sekretaris_opd($pegawai_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->where('pegawai_id', $pegawai_id);
		$this->db->where('pegawai_jabatan', 6);
		$query = $this->db->get ();
		return $query->result_array();
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
	
	public function getTimeline1($id) {
		$this->db->where('telaah_id', $id);
		$query = $this->db->get('table_timeline1', 1);
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
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function skpd() {
		$this->db->select('*');
		$this->db->from('table_skpd');
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
	
	public function get_anggaran($id_anggaran){
		$this->db->select('*');
		$this->db->from('table_anggaran');
		$this->db->where('id_anggaran',$id_anggaran);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	
	public function posisi_kaopd($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_skpd');
		$this->db->where('skpd_id', $skpd_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	public function update_timeline($data) {
		$this->db->update('table_timeline1', $data, array('telaah_id'=>$data['telaah_id']));
	}
	
	/*Fungsi delete telaah*/
	public function delete_telaah($telaah_id, $table) {
    $this->db->delete($table, array('telaah_id' => $telaah_id));
  }

  
	public function sudah_upload_laporan($limit, $start, $kategori, $skpd_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_laporanperjalanan','table_telaah.telaah_id=table_laporanperjalanan.telaah_id');
		$this->db->where('table_telaah.telaah_kategori',$kategori);
		if($this->uri->segment(4)=='puskesmas'){
			$this->db->where('table_jenis_skpd.jenis_skpd_id',7);
		}
		$this->db->where('skpd_id',$skpd_id);
		$this->db->order_by('telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}

	public function cetak_sudah_upload_laporan($kategori, $skpd_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_laporanperjalanan','table_telaah.telaah_id=table_laporanperjalanan.telaah_id');
		$this->db->where('table_telaah.telaah_kategori',$kategori);
		if($this->uri->segment(4)=='puskesmas'){
			$this->db->where('table_jenis_skpd.jenis_skpd_id',7);
		}
		$this->db->where('skpd_id',$skpd_id);
		$this->db->order_by('telaah_waktuinput','DESC');
		$query = $this->db->get ();
		return $query->result();
	}

	public function sudah_upload_laporan_staffsekda_bag($limit, $start, $kategori, $bagian_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
		$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id', 'LEFT');
		$this->db->join('table_laporanperjalanan','table_telaah.telaah_id=table_laporanperjalanan.telaah_id');
		$this->db->where('table_telaah.telaah_kategori',$kategori);
		$this->db->where('table_bagian.bagian_id',$bagian_id);
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function cetak_sudah_upload_laporan_staffsekda_bag($kategori, $bagian_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
		$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id', 'LEFT');
		$this->db->join('table_laporanperjalanan','table_telaah.telaah_id=table_laporanperjalanan.telaah_id');
		$this->db->where('table_telaah.telaah_kategori',$kategori);
		$this->db->where('table_bagian.bagian_id',$bagian_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function sudah_upload_laporan_staffsekda($limit, $start, $kategori) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
		$this->db->join('table_laporanperjalanan','table_telaah.telaah_id=table_laporanperjalanan.telaah_id');
		$this->db->where('table_telaah.telaah_kategori',$kategori);
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function cetak_sudah_upload_laporan_staffsekda($limit, $start, $kategori) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
		$this->db->join('table_laporanperjalanan','table_telaah.telaah_id=table_laporanperjalanan.telaah_id');
		$this->db->where('table_telaah.telaah_kategori',$kategori);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## 

	public function belum_upload_laporan($limit, $start, $kategori, $skpd_id) {
		$this->db->select('table_telaah.*, table_pegawai.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_laporanperjalanan','table_telaah.telaah_id=table_laporanperjalanan.telaah_id','left');
		$this->db->where('table_telaah.telaah_kategori',$kategori);
		$this->db->where('table_laporanperjalanan.telaah_id IS NULL');
		if($this->uri->segment(4)=='puskesmas'){
			$this->db->where('table_jenis_skpd.jenis_skpd_id',7);
		}
		$this->db->where('skpd_id',$skpd_id);
		$this->db->order_by('telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}

	public function cetak_belum_upload_laporan($kategori, $skpd_id) {
		$this->db->select('table_telaah.*, table_pegawai.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_laporanperjalanan','table_telaah.telaah_id=table_laporanperjalanan.telaah_id','left');
		$this->db->where('table_telaah.telaah_kategori',$kategori);
		$this->db->where('table_laporanperjalanan.telaah_id IS NULL');
		if($this->uri->segment(4)=='puskesmas'){
			$this->db->where('table_jenis_skpd.jenis_skpd_id',7);
		}
		$this->db->where('skpd_id',$skpd_id);
		$this->db->order_by('telaah_waktuinput','DESC');
		$query = $this->db->get ();
		return $query->result();
	}

	public function belum_upload_laporan_staffsekda_bag($limit, $start, $kategori, $bagian_id) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_subbagian.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
		$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id', 'LEFT');
		$this->db->join('table_laporanperjalanan','table_telaah.telaah_id=table_laporanperjalanan.telaah_id','left');
		$this->db->where('table_telaah.telaah_kategori',$kategori);
		$this->db->where('table_laporanperjalanan.telaah_id IS NULL');
		$this->db->where('table_bagian.bagian_id',$bagian_id);
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function cetak_belum_upload_laporan_staffsekda_bag($kategori, $bagian_id) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_subbagian.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
		$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id', 'LEFT');
		$this->db->join('table_laporanperjalanan','table_telaah.telaah_id=table_laporanperjalanan.telaah_id','left');
		$this->db->where('table_telaah.telaah_kategori',$kategori);
		$this->db->where('table_laporanperjalanan.telaah_id IS NULL');
		$this->db->where('table_bagian.bagian_id',$bagian_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function belum_upload_laporan_staffsekda($limit, $start, $kategori) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_subbagian.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
		$this->db->join('table_laporanperjalanan','table_telaah.telaah_id=table_laporanperjalanan.telaah_id','left');
		$this->db->where('table_telaah.telaah_kategori',$kategori);
		$this->db->where('table_laporanperjalanan.telaah_id IS NULL');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function cetak_belum_upload_laporan_staffsekda($kategori) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_subbagian.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
		$this->db->join('table_laporanperjalanan','table_telaah.telaah_id=table_laporanperjalanan.telaah_id','left');
		$this->db->where('table_telaah.telaah_kategori',$kategori);
		$this->db->where('table_laporanperjalanan.telaah_id IS NULL');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	
}