<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_admin extends CI_Model
{
	
	// TAMPILAN DASHBOARD-----------------------------------------------------------------------------------------//
	
	public function total_pegawai() {
		$this->db->select('count(*) as total_pegawai');
		$this->db->from('table_pegawai');
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_skpd() {
		$this->db->select('count(*) as total_skpd');
		$this->db->from('table_skpd');
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah() {
		$this->db->select('count(*) as total_list_telaah');
		$this->db->from('table_telaah');
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah_diterima() {
		$this->db->select('count(*) as total_list_telaah_diterima');
		$this->db->from('table_telaah');
		$this->db->where('telaah_status',2);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function total_list_telaah_ditolak() {
		$this->db->select('count(*) as total_list_telaah_ditolak');
		$this->db->from('table_telaah');
		$this->db->where('telaah_status',3);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function nama_skpd($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_skpd');
		$this->db->where('skpd_id',3);
		$query = $this->db->get ();
		return $query->result_array($skpd_id);
	}
	
	// Posisi Pemimpin
	public function posisi_walikota() {
		$this->db->select('*');
		$this->db->from('table_setting');
		$this->db->where('setting_id', 1);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function update_posisi_walikota($data) {
		$this->db->update('table_setting', $data, array('setting_id'=>$data['setting_id']));
	}
	
	public function posisi_kaopd($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_skpd');
		$this->db->where('skpd_id', $skpd_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function update_posisi_kaopd($data) {
		$this->db->update('table_skpd', $data, array('skpd_id'=>$data['skpd_id']));
	}
	
	public function posisi_kadprd() {
		$this->db->select('*');
		$this->db->from('table_skpd');
		$this->db->where('skpd_id', 2);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function update_posisi_kadprd($data) {
		$this->db->update('table_skpd', $data, array('skpd_id'=>$data['skpd_id']));
	}
	
	public function posisi_sekda() {
		$this->db->select('*');
		$this->db->from('table_skpd');
		$this->db->where('skpd_id', 3);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function update_posisi_sekda($data) {
		$this->db->update('table_skpd', $data, array('skpd_id'=>$data['skpd_id']));
	}
	
	//Data esselon
	public function record_count_esselon() {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='1' ");
	}
	
	public function data_esselon($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',1);
		$this->db->limit ($limit, $start);
		$this->db->order_by('table_telaah.telaah_waktuinput', 'DESC');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function record_count_search_esselon($column, $data) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='1' AND $column like '%$data%'");
	}
	
	public function data_search_esselon($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',1);
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_esselon($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_provinsi','table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');
		$this->db->join('table_kabkot','table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');
		$this->db->join('table_kecamatan','table_kecamatan.kec_id=table_telaah.telaah_kectujuan', 'LEFT');
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function getTimeline1_esselon($id) {
		$this->db->where('telaah_id', $id);
		$query = $this->db->get('table_timeline1', 1);
		return $query->result_array();
	}
	
	//Data Kadis
	public function record_count_kadis() {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='2' ");
	}
	
	public function data_kadis($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',2);
		$this->db->limit ($limit, $start);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function record_count_search_kadis($column, $data) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='2' AND $column like '%$data%'");
	}
	
	public function data_search_kadis($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',2);
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kadis($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_provinsi','table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');
		$this->db->join('table_kabkot','table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');
		$this->db->join('table_kecamatan','table_kecamatan.kec_id=table_telaah.telaah_kectujuan', 'LEFT');
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function getTimeline_kadis($id) {
		$this->db->where('telaah_id', $id);
		$query = $this->db->get('table_timeline2', 2);
		return $query->result_array();
	}
	
	public function get_kabkot_kadis($provinsi_id){
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
	
	public function get_kec_kadis($kabkot_id){
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
	
	//Model DPRD
	public function record_count_dprd() {
		return $this->db->count_all("table_telaah WHERE telaah_kategori='3'");
	}
	
	public function record_count_search_dprd($column, $data) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_anggotadprd ON table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id
			WHERE telaah_kategori='3' AND $column like '%$data%'");
	}
	
	public function data_dprd($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','left');
		$this->db->where('(table_telaah.telaah_kategori=3)');
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search_dprd($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','left');
		$this->db->where('(table_telaah.telaah_kategori=3)');
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_dprd($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_provinsi','table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');
		$this->db->join('table_kabkot','table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');
		$this->db->join('table_kecamatan','table_kecamatan.kec_id=table_telaah.telaah_kectujuan', 'LEFT');
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function getTimeline_dprd($id) {
		$this->db->where('telaah_id', $id);
		$query = $this->db->get('table_timeline3', 3);
		return $query->result_array();
	}
	
	public function get_kabkot_dprd($provinsi_id){
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
	
	public function get_kec_dprd($kabkot_id){
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
	
	//Model Sekda
	public function record_count_sekda() {
		return $this->db->count_all("table_telaah WHERE telaah_kategori='4'");
	}
	
	public function record_count_search_sekda($column, $data) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='4'AND $column like '%$data%'");
	}
	
	public function data_sekda($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',4);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search_sekda($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',4);
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_sekda($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_provinsi','table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');
		$this->db->join('table_kabkot','table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');
		$this->db->join('table_kecamatan','table_kecamatan.kec_id=table_telaah.telaah_kectujuan', 'LEFT');
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function getTimeline_sekda($id) {
		$this->db->where('telaah_id', $id);
		$query = $this->db->get('table_timeline4', 4);
		return $query->result_array();
	}
	
	public function get_kabkot_sekda($provinsi_id){
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
	
	public function get_kec_sekda($kabkot_id){
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
		$this->db->select('*');
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
	
	public function getsubbagian($id) {
		$this->db->select('*');
		$this->db->from('users');
		$this->db->join('users_groups_subbagian','users_groups_subbagian.user_id=users.id');
		$this->db->join('table_subbagian','users_groups_subbagian.subbagian_id=table_subbagian.subbagian_id', 'LEFT');
		$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id', 'LEFT');
		$this->db->join('table_asisten','table_asisten.asisten_id=table_bagian.asisten_id', 'LEFT');
		$this->db->where('users.id', $id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function getkabag($id) {
		$this->db->select('*');
		$this->db->from('users');
		$this->db->join('users_groups_bagian','users_groups_bagian.user_id=users.id');
		$this->db->join('table_bagian','table_bagian.bagian_id=users_groups_bagian.bagian_id', 'LEFT');
		$this->db->join('table_asisten','table_asisten.asisten_id=table_bagian.asisten_id', 'LEFT');
		$this->db->join('table_subbagian','table_bagian.bagian_id=table_subbagian.bagian_id', 'LEFT');
		$this->db->where('users.id', $id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	//Model Camat
	public function record_count_camat() {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='5'");
	}
	
	public function record_count_search_camat($column, $data) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='5' AND $column like '%$data%'");
	}
	
	public function data_camat($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');
		$this->db->join('table_relasi_kelurahan','table_skpd.skpd_id=table_relasi_kelurahan.id_kecamatan');
		$this->db->where('table_telaah.telaah_kategori',5);
		$this->db->limit ($limit, $start);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search_camat($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->where('table_telaah.telaah_kategori',5);
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_camat($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_provinsi','table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');
		$this->db->join('table_kabkot','table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id=table_telaah.telaah_pelaksana', 'LEFT');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_telaah.telaah_id', 'LEFT');
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function getTimeline_camat($id) {
		$this->db->where('telaah_id', $id);
		$query = $this->db->get('table_timeline5');
		return $query->result_array();
	}
	
	public function get_kabkot_camat($provinsi_id){
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
	
	public function get_kec_camat($kabkot_id){
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
	
	//Model Lurah
	public function record_count_lurah() {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='5'");
	}
	
	public function record_count_search($column, $data) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='5' AND $column like '%$data%'");
	}
	
	public function data_lurah($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');
		$this->db->join('table_relasi_kelurahan','table_skpd.skpd_id=table_relasi_kelurahan.id_kelurahan');
		$this->db->where('table_telaah.telaah_kategori',5);
		$this->db->limit ($limit, $start);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search_lurah($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->where('table_telaah.telaah_kategori',5);
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_lurah($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_provinsi','table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');
		$this->db->join('table_kabkot','table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id=table_telaah.telaah_pelaksana', 'LEFT');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_telaah.telaah_id', 'LEFT');
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function getTimeline_lurah($id) {
		$this->db->where('telaah_id', $id);
		$query = $this->db->get('table_timeline5', 1);
		return $query->result_array();
	}
	
	public function get_kabkot_lurah($provinsi_id){
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
	
	//Model Sekwan
	public function record_count_sekwan() {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='10'");
	}
	
	public function record_count_search_sekwan($column, $data) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='10' AND $column like '%$data%'");
	}
	
	public function data_sekwan($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->where('table_telaah.telaah_kategori',10);
		$this->db->order_by('telaah_waktuinput','DESC');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search_sekwan($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->where('table_telaah.telaah_kategori',10);
		$this->db->order_by('telaah_waktuinput','DESC');
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_sekwan($telaah_id) {
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
	
	public function getLast_sekwan() {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->order_by('telaah_id','DESC');
		$this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function pegawai_sekwan($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->where('skpd_id', $skpd_id);
		$query = $this->db->get ();
		return $query->result();
	}
	public function sekwan($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->where('skpd_id', $skpd_id);
		$this->db->where('pegawai_jabatan', 9);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function getTimeline10_sekwan($id) {
		$this->db->where('telaah_id', $id);
		$query = $this->db->get('table_timeline10', 1);
		return $query->result_array();
	}
	
	public function anggaran_sekwan($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_anggaran');
		$this->db->where('skpd_id',$skpd_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function rekening_sekwan($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_rekening');
		$this->db->where('skpd_id',$skpd_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_provinsi_sekwan() {
		$this->db->select('*');
		$this->db->from('table_provinsi');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kabupaten_sekwan() {
		$this->db->select('*');
		$this->db->from('table_kabkot');
		$this->db->where('provinsi_id',74);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kecamatan_sekwan() {
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
	
	public function get_kecamatan_dddk_sekwan() {
		$this->db->select('*');
		$this->db->from('table_kecamatan');
		$this->db->where('kec_id',747101);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function get_kabkot_sekwan($provinsi_id){
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
	
	public function get_kec_sekwan($kabkot_id){
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
	
	//Model Staff DPRD
	public function record_count_staffdprd() {
		return $this->db->count_all("table_telaah WHERE telaah_kategori='6'");
	}
	
	public function record_count_search_staffdprd($column, $data) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='6' AND $column like '%$data%'");
	}
	
	public function data_staffdprd($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',6);
		$this->db->order_by('telaah_waktuinput','DESC');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search_staffdprd($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',6);
		$this->db->order_by('telaah_waktuinput','DESC');
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_staffdprd($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_provinsi','table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');
		$this->db->join('table_kabkot','table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id=table_telaah.telaah_pelaksana', 'LEFT');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function getLast_staffdprd() {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->order_by('telaah_id','DESC');
		$this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function create_staffdprd($data) {
		
		$this->db->insert('table_telaah', $data);
	}
	
	public function pegawai($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->where('skpd_id', $skpd_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function getTimeline_staffdprd($id) {
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
	
	public function get_provinsi_staffdprd() {
		$this->db->select('*');
		$this->db->from('table_provinsi');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kabupaten_staffdprd() {
		$this->db->select('*');
		$this->db->from('table_kabkot');
		$this->db->where('provinsi_id',74);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kabkot_staffdprd($provinsi_id){
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
	
	public function update_timeline($data) {
		$this->db->update('table_timeline6', $data, array('telaah_id'=>$data['telaah_id']));
	}
	
	//Model Staff Camat
	public function record_count_staffcamat() {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='7'");
	}
	
	public function record_count_search_staffcamat($column, $data) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='7' AND $column like '%$data%'");
	}
	
	public function data_staffcamat($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',7);
		$this->db->order_by('telaah_waktuinput','DESC');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search_staffcamat($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',7);
		$this->db->order_by('telaah_waktuinput','DESC');
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_staffcamat($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_provinsi','table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');
		$this->db->join('table_kabkot','table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id=table_telaah.telaah_pelaksana', 'LEFT');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function getLast_staffcamat() {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->order_by('telaah_id','DESC');
		$this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function pegawai_staffcamat($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->where('skpd_id', $skpd_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function getTimeline_staffcamat($id) {
		$this->db->where('telaah_id', $id);
		$query = $this->db->get('table_timeline7', 7);
		return $query->result_array();
	}
	
	public function get_provinsi_staffcamat() {
		$this->db->select('*');
		$this->db->from('table_provinsi');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kabupaten_staffcamat() {
		$this->db->select('*');
		$this->db->from('table_kabkot');
		$this->db->where('provinsi_id',74);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kabkot_staffcamat($provinsi_id){
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
	
	//Model Staff Lurah
	public function record_count_stafflurah() {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='7'");
	}
	
	public function record_count_search_stafflurah($column, $data) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='7' AND $column like '%$data%'");
	}
	
	public function data_stafflurah($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',7);
		$this->db->order_by('telaah_waktuinput','DESC');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search_stafflurah($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',7);
		$this->db->order_by('telaah_waktuinput','DESC');
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_stafflurah($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_provinsi','table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');
		$this->db->join('table_kabkot','table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id=table_telaah.telaah_pelaksana', 'LEFT');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function getLast_stafflurah() {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->order_by('telaah_id','DESC');
		$this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function getTimeline_stafflurah($id) {
		$this->db->where('telaah_id', $id);
		$query = $this->db->get('table_timeline7', 7);
		return $query->result_array();
	}
	
	public function get_provinsi_stafflurah() {
		$this->db->select('*');
		$this->db->from('table_provinsi');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kabupaten_stafflurah() {
		$this->db->select('*');
		$this->db->from('table_kabkot');
		$this->db->where('provinsi_id',74);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kabkot_stafflurah($provinsi_id){
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
	
	//walikota
	public function record_countwalikota() {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pimpinan ON table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id
			WHERE telaah_kategori='8'");
	}
	public function datawalikota2($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pimpinan','table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id','left');
		$this->db->join('table_timeline8','table_telaah.telaah_id=table_timeline8.telaah_id','left');
		$this->db->join('table_skpd','table_pimpinan.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("table_telaah.telaah_kategori", 8);
		//$this->db->where('table_timeline8.timeline_kabag_id',0);
		$this->db->order_by('table_telaah.telaah_id','DESC');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	public function record_count_search_walikota($column, $data) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pimpinan ON table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id
			WHERE telaah_kategori='8' AND $column like '%$data%'");
	}
	public function data_search_walikota($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pimpinan','table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id','left');
		$this->db->join('table_timeline8','table_telaah.telaah_id=table_timeline8.telaah_id','left');
		$this->db->join('table_skpd','table_pimpinan.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("table_telaah.telaah_kategori", 8);
		//$this->db->where('table_timeline8.timeline_kabag_id',0);
		$this->db->like($column,$value);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	//Puskesmas
	public function record_count_kapus() {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='11'");
	}
	
	public function record_count_search_kapus($column, $data) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			WHERE telaah_kategori='11' AND $column like '%$data%'");
	}
	
	public function data_kapus($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',11);
		$this->db->order_by('telaah_waktuinput','DESC');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search_kapus($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',11);
		$this->db->order_by('telaah_waktuinput','DESC');
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kapus($telaah_id) {
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
	
	public function getLast_kapus() {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->order_by('telaah_id','DESC');
		$this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function kepala_opd_kapus($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->where('skpd_id', $skpd_id);
		$this->db->where('pegawai_jabatan', 4);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function getTimeline2_kapus($id) {
		$this->db->where('telaah_id', $id);
		$query = $this->db->get('table_timeline11', 1);
		return $query->result_array();
	}
	
	public function get_provinsi_kapus() {
		$this->db->select('*');
		$this->db->from('table_provinsi');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kabupaten_kapus() {
		$this->db->select('*');
		$this->db->from('table_kabkot');
		$this->db->where('provinsi_id',74);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kecamatan_kapus() {
		$this->db->select('*');
		$this->db->from('table_kecamatan');
		$this->db->join('table_kabkot','table_kecamatan.kabkot_id = table_kabkot.kabkot_id', 'LEFT');
		$this->db->join('table_provinsi','table_kabkot.provinsi_id = table_provinsi.provinsi_id', 'LEFT');
		$this->db->where('table_provinsi.provinsi_id',74);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kecamatan_ddak_kapus() {
		$this->db->select('*');
		$this->db->from('table_kecamatan');
		$this->db->join('table_kabkot','table_kecamatan.kabkot_id = table_kabkot.kabkot_id', 'LEFT');
		$this->db->join('table_provinsi','table_kabkot.provinsi_id = table_provinsi.provinsi_id', 'LEFT');
		$this->db->where('table_provinsi.provinsi_id',74);
		$this->db->where('table_kabkot.kabkot_id',7471);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kecamatan_dddk_kapus() {
		$this->db->select('*');
		$this->db->from('table_kecamatan');
		$this->db->where('kec_id',747101);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function get_kabkot_kapus($provinsi_id){
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
	
	public function get_kec_kapus($kabkot_id){
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
	
	/*Fungsi delete telaah*/
	public function delete_telaah($telaah_id, $table) {
    $this->db->delete($table, array('telaah_id' => $telaah_id));
  }
	
}