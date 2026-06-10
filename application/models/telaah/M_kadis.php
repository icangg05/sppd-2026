<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_kadis extends CI_Model
{
	// TAMPILAN DASHBOARD-----------------------------------------------------------------------------------------//
	
	public function total_list_telaah($skpd_id) {
		$this->db->select('count(*) as total_list_telaah');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id');
		$this->db->where('skpd_id',$skpd_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	// Total List Telaah Esselon yang masuk
	public function total_list_telaah_masuk($skpd_id) {
		$this->db->select('count(*) as total_list_telaah_masuk');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id');
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
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id');
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('telaah_status', 1);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah_diterima($skpd_id) {
		$this->db->select('count(*) as total_list_telaah_diterima');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id');
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('telaah_status', 2);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah_ditolak($skpd_id) {
		$this->db->select('count(*) as total_list_telaah_ditolak');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id');
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('telaah_status', 3);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	
	##Total Anggaran Keseluruhan
	public function total_anggaran_keseluruhan($skpd_id){
		$this->db->select('SUM(pagu) as total_anggaran_keseluruhan');
		$this->db->from('table_anggaran');
		$this->db->where('skpd_id', $skpd_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function rincian_belanja_skpd($id_skpd) {
		$query  = $this->db->query("SELECT (SELECT SUM(tarif*item) ) as jumlah  FROM table_rincian_biaya a 
									LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
									LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran 
									WHERE c.skpd_id='$id_skpd' ");
		return $query->result_array();
	}
	
	public function pengeluaran_rill_skpd($id_skpd) {
		$query  = $this->db->query("SELECT SUM(tarif) as jumlah FROM table_pengeluaran_rill a
									LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id
									LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran
									WHERE c.skpd_id='$id_skpd' ");
		return $query->result_array();
	}
	
	## Total Anggaran Dalam Daerah
	public function total_anggaran_dalam_daerah($skpd_id){
		$this->db->select('SUM(pagu) as total_anggaran');
		$this->db->from('table_anggaran');
		$this->db->where('jenis_anggaran', 1);
		$this->db->where('skpd_id', $skpd_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function rincian_belanja_dalam_daerah($id_skpd) {
		$query  = $this->db->query("SELECT (SELECT SUM(tarif*item) ) as jumlah FROM table_rincian_biaya a 
									LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
									LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran 
									WHERE c.jenis_anggaran=1 
									AND c.skpd_id='$id_skpd' ");
		return $query->result_array();
	}
	
	public function pengeluaran_rill_dalam_daerah($id_skpd) {
		$query  = $this->db->query("SELECT SUM(tarif) as jumlah FROM table_pengeluaran_rill a
									LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id
									LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran
									WHERE c.jenis_anggaran=1 
									AND c.skpd_id='$id_skpd' ");
		return $query->result_array();
	}
	
	## Total Anggaran Luar Daerah
	public function total_anggaran_luar_daerah($skpd_id){
		$this->db->select('SUM(pagu) as total_anggaran');
		$this->db->from('table_anggaran');
		$this->db->where('jenis_anggaran', 2);
		$this->db->where('skpd_id', $skpd_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function rincian_belanja_luar_daerah($id_skpd) {
		$query  = $this->db->query("SELECT (SELECT SUM(tarif*item)) as jumlah FROM table_rincian_biaya a 
									LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
									LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran 
									WHERE c.jenis_anggaran=2 
									AND c.skpd_id='$id_skpd' ");
		return $query->result_array();
	}
	
	public function pengeluaran_rill_luar_daerah($id_skpd) {
		$query  = $this->db->query("SELECT SUM(tarif) as jumlah FROM table_pengeluaran_rill a
									LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id
									LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran
									WHERE c.jenis_anggaran=2 
									AND c.skpd_id='$id_skpd' ");
		return $query->result_array();
	}
	
	## Total Anggaran Bimtek
	public function total_anggaran_bimtek($skpd_id){
		$this->db->select('SUM(pagu) as total_anggaran');
		$this->db->from('table_anggaran');
		$this->db->where('jenis_anggaran', 3);
		$this->db->where('skpd_id', $skpd_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function rincian_belanja_bimtek($id_skpd) {
		$query  = $this->db->query("SELECT (SELECT SUM(tarif*item)) as jumlah FROM table_rincian_biaya a 
									LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
									LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran 
									WHERE c.jenis_anggaran=3 
									AND c.skpd_id='$id_skpd' ");
		return $query->result_array();
	}
	
	public function pengeluaran_rill_bimtek($id_skpd) {
		$query  = $this->db->query("SELECT SUM(tarif) as jumlah FROM table_pengeluaran_rill a
									LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id
									LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran
									WHERE c.jenis_anggaran=3 
									AND c.skpd_id='$id_skpd' ");
		return $query->result_array();
	}
	
	public function total_sisa_anggaran($skpd_id){
		$this->db->select('SUM(sisa_pagu) as total_sisa_anggaran');
		$this->db->from('table_anggaran');
		$this->db->where('skpd_id', $skpd_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	
	//======================================================
	public function record_count($skpd_id) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='2'AND skpd_id='$skpd_id' ");
	}
	
	public function record_count_search($column, $data, $skpd_id) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='2' AND skpd_id='$skpd_id' AND $column like '%$data%'");
	}
	
	public function data($limit, $start, $skpd_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->where('table_telaah.telaah_kategori',2);
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
		$this->db->where('table_telaah.telaah_kategori',2);
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
	public function kepala_opd($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->where('status_delete',0);
		$this->db->where('skpd_id', $skpd_id);
		$this->db->where('pegawai_jabatan', 4);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function getTimeline2($id) {
		$this->db->where('telaah_id', $id);
		$query = $this->db->get('table_timeline2', 1);
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
	public function update_timeline($data) {
		$this->db->update('table_timeline2', $data, array('telaah_id'=>$data['telaah_id']));
	}
	
	/*Fungsi delete telaah*/
	public function delete_telaah($telaah_id, $table) {
    $this->db->delete($table, array('telaah_id' => $telaah_id));
  }
}