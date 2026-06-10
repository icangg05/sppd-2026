<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_dprd extends CI_Model
{
	// TAMPILAN DASHBOARD-----------------------------------------------------------------------------------------//
	
	public function total_pegawai() {
		$this->db->select('count(*) as total_pegawai');
		$this->db->from('table_pegawai');
		$this->db->where('skpd_id',2);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah() {
		$this->db->select('count(*) as total_list_telaah');
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',3);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah_masuk() {
		$this->db->select('count(*) as total_list_telaah_masuk');
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',3);
		$this->db->where('telaah_status', 0);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah_diproses() {
		$this->db->select('count(*) as total_list_telaah_diproses');
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',3);
		$this->db->where('telaah_status', 1);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah_diterima() {
		$this->db->select('count(*) as total_list_telaah_diterima');
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',3);
		$this->db->where('telaah_status', 2);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah_ditolak() {
		$this->db->select('count(*) as total_list_telaah_ditolak');
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',3);
		$this->db->where('telaah_status', 3);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	//-----------------------------------------------------------------------------------------//
	
	public function record_count() {
		return $this->db->count_all("table_telaah WHERE telaah_kategori='3'");
	}
	
	public function record_count_search($column, $data) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_anggotadprd ON table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id
			WHERE telaah_kategori='3' AND $column like '%$data%'");
	}
	
	public function data($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','left');
		$this->db->where('table_telaah.telaah_kategori',3);
		$this->db->order_by('telaah_waktuinput','DESC');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','left');
		$this->db->where('table_telaah.telaah_kategori',3);
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
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','left');
		$this->db->where('table_telaah.telaah_kategori',3);
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
		$this->db->join('table_anggotadprd','table_anggotadprd.anggotadprd_id=table_telaah.telaah_pelaksana', 'LEFT');		
		$this->db->join('table_anggaran','table_anggaran.id_anggaran=table_telaah.telaah_kegiatan', 'LEFT');
		$this->db->join('table_skpd',"table_skpd.jenis_skpd='2'", 'LEFT');
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
		$query = $this->db->get('table_timeline3', 1);
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
	
	public function anggota() {
		$this->db->select('*, anggotadprd_id as pegawai_id, anggotadprd_name as pegawai_nama, anggotadprd_jabatan as pegawai_namajabatan');
		$this->db->from('table_anggotadprd');
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
	
	public function posisi_kadprd() {
		$this->db->select('*');
		$this->db->from('table_skpd');
		$this->db->where('skpd_id', 2);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function cek_sisa_anggaran($id_anggaran) {
		$query  = $this->db->query("SELECT (SELECT SUM(tarif*item) ) as tes  FROM table_rincian_biaya a LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran WHERE c.id_anggaran='$id_anggaran' ");
		return $query->result();
	}
	
	public function cek_sisa_anggaran2($id_anggaran) {
		$query  = $this->db->query("SELECT SUM(tarif) as jumlah FROM table_pengeluaran_rill a
									LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id
									LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran
									WHERE c.id_anggaran='$id_anggaran' ");
		return $query->result();
	}
	
	public function cek_sisa_anggaran_skpd($id_skpd) {
		$query  = $this->db->query("SELECT (SELECT SUM(tarif*item) ) as tes  FROM table_rincian_biaya a LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran WHERE c.skpd_id='$id_skpd' ");
		return $query->result();
	}
	
	public function cek_sisa_anggaran_walikota($id_skpd) {
		$query  = $this->db->query("SELECT (SELECT SUM(tarif*item)) AS tes FROM table_rincian_biaya a LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran WHERE c.skpd_id='$id_skpd' AND b.telaah_kategori=8 ");
		return $query->result();
	}
	
	public function cek_sisa_anggaran_all() {
		$query  = $this->db->query("SELECT (SELECT SUM(tarif*item) ) as tes  FROM table_rincian_biaya a LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran ");
		return $query->result();
	}
	
	public function update_timeline($data) {
		$this->db->update('table_timeline3', $data, array('telaah_id'=>$data['telaah_id']));
	}
	
	/*Fungsi delete telaah*/
	public function delete_telaah($telaah_id, $table) {
    $this->db->delete($table, array('telaah_id' => $telaah_id));
  }
  
}