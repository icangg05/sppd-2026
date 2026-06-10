<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_walikota extends CI_Model
{
	// TAMPILAN DASHBOARD-----------------------------------------------------------------------------------------//
	
	// Total List Telaah Camat
	public function total_list_telaah($skpd_id) {
		$this->db->select('count(*) as total_list_telaah');
		$this->db->from('table_telaah');
		$this->db->join('table_pimpinan','table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',8);
		$this->db->where('skpd_id',$skpd_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	// Total List Telaah walikota yang masuk
	public function total_list_telaah_masuk($skpd_id) {
		$this->db->select('count(*) as total_list_telaah_masuk');
		$this->db->from('table_telaah');
		$this->db->join('table_pimpinan','table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',8);
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('telaah_status', 0);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	// Total List Telaah Camat yang diproses
	public function total_list_telaah_diproses($skpd_id) {
		$this->db->select('count(*) as total_list_telaah_diproses');
		$this->db->from('table_telaah');
		$this->db->join('table_pimpinan','table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',8);
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('telaah_status', 1);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	// Total List Telaah Camat yang diterima
	public function total_list_telaah_diterima($skpd_id) {
		$this->db->select('count(*) as total_list_telaah_diterima');
		$this->db->from('table_telaah');
		$this->db->join('table_pimpinan','table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',8);
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('telaah_status', 2);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	// Total List Telaah Camat yang ditolak
	public function total_list_telaah_ditolak($skpd_id) {
		$this->db->select('count(*) as total_list_telaah_ditolak');
		$this->db->from('table_telaah');
		$this->db->join('table_pimpinan','table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id','LEFT');
		$this->db->where('table_telaah.telaah_kategori',8);
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('telaah_status', 3);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	## Total Anggaran Keseluruhan
	public function total_anggaran_keseluruhan(){
		$this->db->select('SUM(pagu) as total_anggaran_keseluruhan');
		$this->db->where('skpd_id !=23 and skpd_id !=33 AND skpd_id !=34 AND skpd_id !=183');
		$this->db->from('table_anggaran');
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function rincian_belanja() {
		$query  = $this->db->query("SELECT (SELECT SUM(tarif*item) ) as jumlah  FROM table_rincian_biaya a 
									LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
									LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran ");
		return $query->result_array();
	}
	
	public function pengeluaran_rill() {
		$query  = $this->db->query("SELECT SUM(tarif) as jumlah FROM table_pengeluaran_rill a
									LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id
									LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran");
		return $query->result_array();
	}
	
	## Total Anggaran Dalam Daerah
	public function total_anggaran_dalam_daerah(){
		$this->db->select('SUM(pagu) as total_anggaran');
		$this->db->from('table_anggaran');
		$this->db->where('jenis_anggaran', 1);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function rincian_belanja_dalam_daerah($skpd_id) {
		if($skpd_id){
			$query  = $this->db->query("SELECT (SELECT SUM(tarif*item) ) as jumlah FROM table_rincian_biaya a 
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran 
										WHERE c.skpd_id = '$skpd_id' AND c.jenis_anggaran=1");
		} else {
			$query  = $this->db->query("SELECT (SELECT SUM(tarif*item) ) as jumlah FROM table_rincian_biaya a 
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran 
										WHERE c.jenis_anggaran=1");
		}
		return $query->result_array();
	}
	
	public function pengeluaran_rill_dalam_daerah($skpd_id) {
		if($skpd_id){
			$query  = $this->db->query("SELECT SUM(tarif) as jumlah FROM table_pengeluaran_rill a
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran
										WHERE c.skpd_id = '$skpd_id' AND c.jenis_anggaran=1");
		} else {
			$query  = $this->db->query("SELECT SUM(tarif) as jumlah FROM table_pengeluaran_rill a
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran
										WHERE c.jenis_anggaran=1");
		}
		return $query->result_array();
	}
	
	## Total Anggaran Luar Daerah
	public function total_anggaran_luar_daerah(){
		$this->db->select('SUM(pagu) as total_anggaran');
		$this->db->from('table_anggaran');
		$this->db->where('jenis_anggaran', 2);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function rincian_belanja_luar_daerah($skpd_id) {
		if($skpd_id){
			$query  = $this->db->query("SELECT (SELECT SUM(tarif*item)) as jumlah FROM table_rincian_biaya a 
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran 
										WHERE c.skpd_id = '$skpd_id' AND c.jenis_anggaran=2");
		} else {
			$query  = $this->db->query("SELECT (SELECT SUM(tarif*item)) as jumlah FROM table_rincian_biaya a 
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran 
										WHERE c.jenis_anggaran=2");
		}
		return $query->result_array();
	}
	
	public function pengeluaran_rill_luar_daerah($skpd_id) {
		if($skpd_id){
			$query  = $this->db->query("SELECT SUM(tarif) as jumlah FROM table_pengeluaran_rill a
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran
										WHERE c.skpd_id = '$skpd_id' AND c.jenis_anggaran=2");
		} else {
			$query  = $this->db->query("SELECT SUM(tarif) as jumlah FROM table_pengeluaran_rill a
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran
										WHERE c.jenis_anggaran=2");
		}
		return $query->result_array();
	}
	
	## Total Anggaran Bimtek
	public function total_anggaran_bimtek(){
		$this->db->select('SUM(pagu) as total_anggaran');
		$this->db->from('table_anggaran');
		$this->db->where('jenis_anggaran', 3);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function rincian_belanja_bimtek($skpd_id) {
		if($skpd_id){
			$query  = $this->db->query("SELECT (SELECT SUM(tarif*item) ) as jumlah FROM table_rincian_biaya a 
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran 
										WHERE c.skpd_id = '$skpd_id' AND c.jenis_anggaran=3");
		} else {
			$query  = $this->db->query("SELECT (SELECT SUM(tarif*item) ) as jumlah FROM table_rincian_biaya a 
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran 
										WHERE c.jenis_anggaran=3");
		}
		return $query->result_array();
	}
	
	public function pengeluaran_rill_bimtek($skpd_id) {
		if($skpd_id){
			$query  = $this->db->query("SELECT SUM(tarif) as jumlah FROM table_pengeluaran_rill a
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran
										WHERE c.skpd_id = '$skpd_id' AND c.jenis_anggaran=3");
		} else {
			$query  = $this->db->query("SELECT SUM(tarif) as jumlah FROM table_pengeluaran_rill a
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran
										WHERE c.jenis_anggaran=3");
		}
		return $query->result_array();
	}
	
	public function total_sisa_anggaran(){
		$this->db->select('SUM(sisa_pagu) as total_sisa_anggaran');
		$this->db->from('table_anggaran');
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	//Total Anggaran SKPD
	public function total_anggaran_skpd($skpd_id){
		$this->db->select('SUM(pagu) as total_anggaran_keseluruhan');
		$this->db->from('table_anggaran');
		$this->db->where('skpd_id', $skpd_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	//Total Anggaran SKPD
	public function total_anggaran_skpd2($skpd_id, $bagian_id){
		$this->db->select('SUM(pagu) as total_anggaran_keseluruhan');
		$this->db->from('table_anggaran');
		$this->db->where('skpd_id', $skpd_id);
		$this->db->where('bagian_id', $bagian_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	//Total Anggaran Walikota
	public function total_anggaran_walikota(){
		$this->db->select('SUM(pagu) as total_anggaran_keseluruhan');
		$this->db->from('table_anggaran');
		$this->db->where('(id_anggaran = 667 OR id_anggaran = 670 OR id_anggaran = 671)');
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function cek_sisa_anggaran_walikota($id_skpd) {
		$query  = $this->db->query("SELECT (SELECT SUM(tarif*item)) AS tes FROM table_rincian_biaya a LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran WHERE c.skpd_id='$id_skpd' AND b.telaah_kategori=8 ");
		return $query->result();
	}
	
	public function cek_sisa_anggaran_sekretariat($bagian_id) {
		$query  = $this->db->query("SELECT (SELECT SUM(tarif*item) ) as tes  
				FROM table_rincian_biaya a LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
				LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran WHERE c.skpd_id='3'
				AND bagian_id='$bagian_id'");
		return $query->result();
	}
	
	public function cek_sisa_anggaran_sekretariat_bagian($id_anggaran, $bagian_id) {
		$query  = $this->db->query("SELECT (SELECT SUM(tarif*item) ) as tes  
		FROM table_rincian_biaya a LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
		LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran 
		WHERE c.id_anggaran='$id_anggaran'
		AND bagian_id='$bagian_id' ");
		return $query->result();
	}
	
	public function cek_sisa_anggaran_sekretariat_bagian2($id_anggaran, $bagian_id) {
		$query  = $this->db->query("SELECT SUM(tarif) as jumlah FROM table_pengeluaran_rill a 
		LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
		LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran 
		WHERE c.id_anggaran='$id_anggaran'
		AND bagian_id='$bagian_id' ");
		return $query->result();
	}
	
	public function record_count_anggaran($skpd_id) {
		return $this->db->count_all("table_anggaran WHERE skpd_id = '$skpd_id'");
	}
	
	public function anggaran($skpd_id, $jenis_anggaran) {
		$this->db->select('*');
		$this->db->from('table_anggaran');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_anggaran.skpd_id');
		if($jenis_anggaran){
			$this->db->where('jenis_anggaran', $jenis_anggaran);
		}
		$this->db->where('table_anggaran.skpd_id', $skpd_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function anggaran_sekretariat($bagian_id, $jenis_anggaran) {
		$this->db->select('*');
		$this->db->from('table_anggaran');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_anggaran.skpd_id');
		$this->db->where('table_anggaran.skpd_id', 3);
		if($jenis_anggaran){
			$this->db->where('jenis_anggaran', $jenis_anggaran);
		}
		$this->db->where('table_anggaran.bagian_id', $bagian_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function record_count($jenis_anggaran) {
		$this->db->select('count(*)');
		$this->db->from('table_anggaran');
		$this->db->join('table_skpd','table_anggaran.skpd_id=table_skpd.skpd_id');
		if($jenis_anggaran){
			$this->db->where('jenis_anggaran',$jenis_anggaran);
		}
		$this->db->group_by('table_anggaran.skpd_id');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function record_count_search($column, $data) {
		$this->db->select('count(*)');
		$this->db->from('table_anggaran');
		$this->db->where($column,$data);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data($limit, $start, $jenis_anggaran) {
		$this->db->select('table_skpd.skpd_id,skpd_nama,sum(pagu) AS pagu,sum(sisa_pagu) AS sisa_pagu');
		$this->db->from('table_anggaran');
		$this->db->join('table_skpd','table_anggaran.skpd_id=table_skpd.skpd_id');
		if($jenis_anggaran){
			$this->db->where('jenis_anggaran',$jenis_anggaran);
		}
		$this->db->group_by('table_anggaran.skpd_id');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data2($limit, $start, $jenis_anggaran) 
	{
		
		// $this->db->select('table_skpd.skpd_id,skpd_nama,sum(pagu) AS pagu,sum(sisa_pagu) AS sisa_pagu');
		$this->db->select('*');
		$this->db->from('table_skpd');
		// $this->db->join('table_anggaran','table_anggaran.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_skpd.skpd_id', 3);
		$this->db->where('status', 1);
		// $this->db->group_by('table_anggaran.skpd_id');
		$query1 = $this->db->get_compiled_select(); 
			
		$this->db->select('*');
		$this->db->from('table_skpd');
		// $this->db->join('table_anggaran','table_anggaran.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_skpd.skpd_id', 2);
		$this->db->where('status', 1);
		// $this->db->group_by('table_anggaran.skpd_id');
		$query2 = $this->db->get_compiled_select(); 
				
		$this->db->select('*');
		$this->db->from('table_skpd');
		// $this->db->join('table_anggaran','table_anggaran.skpd_id=table_skpd.skpd_id','left');
		$this->db->like('table_skpd.skpd_id', 182);
		$this->db->where('status', 1);
		// $this->db->group_by('table_anggaran.skpd_id');
		$query3 = $this->db->get_compiled_select(); 
			
		$this->db->select('*');
		$this->db->from('table_skpd');
		// $this->db->join('table_anggaran','table_anggaran.skpd_id=table_skpd.skpd_id','left');
		$this->db->like('table_skpd.skpd_nama', 'badan');
		$this->db->where('status', 1);
		// $this->db->group_by('table_anggaran.skpd_id');
		$query4 = $this->db->get_compiled_select(); 
			
		$this->db->select('*');
		$this->db->from('table_skpd');
		// $this->db->join('table_anggaran','table_anggaran.skpd_id=table_skpd.skpd_id','left');
		$this->db->like('table_skpd.skpd_nama', 'dinas');
		$this->db->where('status', 1);
		// $this->db->group_by('table_anggaran.skpd_id');
		$query5 = $this->db->get_compiled_select(); 
			
		$this->db->select('*');
		$this->db->from('table_skpd');
		// $this->db->join('table_anggaran','table_anggaran.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_skpd.skpd_id', 37);
		$this->db->where('status', 1);
		// $this->db->group_by('table_anggaran.skpd_id');
		$query6 = $this->db->get_compiled_select(); 
			
		$this->db->select('*');
		$this->db->from('table_skpd');
		// $this->db->join('table_anggaran','table_anggaran.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_skpd.skpd_id', 15);
		$this->db->where('status', 1);
		// $this->db->group_by('table_anggaran.skpd_id');
		$query7 = $this->db->get_compiled_select(); 
			
		$this->db->select('*');
		$this->db->from('table_skpd');
		// $this->db->join('table_anggaran','table_anggaran.skpd_id=table_skpd.skpd_id','left');
		$this->db->like('table_skpd.skpd_nama', 'camat');
		$this->db->where('status', 1);
		// $this->db->group_by('table_anggaran.skpd_id');
		$query8 = $this->db->get_compiled_select();
			
		$this->db->select('*');
		$this->db->from('table_skpd');
		// $this->db->join('table_anggaran','table_anggaran.skpd_id=table_skpd.skpd_id','left');
		$this->db->like('table_skpd.skpd_nama', 'kelurahan');
		$this->db->where('status', 1);
		// $this->db->group_by('table_anggaran.skpd_id');
		$query9 = $this->db->get_compiled_select();
			
		$this->db->select('*');
		$this->db->from('table_skpd');
		// $this->db->join('table_anggaran','table_anggaran.skpd_id=table_skpd.skpd_id','left');
		$this->db->like('table_skpd.skpd_nama', 'puskesmas');
		$this->db->where('status', 1);
		// $this->db->group_by('table_anggaran.skpd_id');
		$query10 = $this->db->get_compiled_select();
			
		if($limit){
			$query = $this->db->query($query1." UNION ALL ".$query2." UNION ALL ".
							  $query3." UNION ALL ".$query4." UNION ALL ".
							  $query5." UNION ALL ".$query6." UNION ALL ".
							  $query7." UNION ALL ".$query8." UNION ALL ".
							  $query9." UNION ALL ".$query10." LIMIT $start, $limit");
			return $query->result();
		} else {
			$query = $this->db->query($query1." UNION ALL ".$query2." UNION ALL ".
							  $query3." UNION ALL ".$query4." UNION ALL ".
							  $query5." UNION ALL ".$query6." UNION ALL ".
							  $query7." UNION ALL ".$query8." UNION ALL ".
							  $query9." UNION ALL ".$query10);
			return $query->result();
		}
	}
	
	
	
	public function sekretariat($limit, $start) {
		$this->db->select('table_skpd.skpd_id,table_anggaran.bagian_id,nama_bagian as skpd_nama,sum(pagu) AS pagu,sum(sisa_pagu) AS sisa_pagu');
		$this->db->from('table_anggaran');
		$this->db->join('table_skpd','table_anggaran.skpd_id=table_skpd.skpd_id');
		$this->db->join('table_bagian','table_bagian.bagian_id=table_anggaran.bagian_id');
		$this->db->where('table_anggaran.skpd_id',3);
		$this->db->group_by('table_anggaran.bagian_id');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function data_search($column, $value, $limit, $start) {
		$this->db->select('table_skpd.skpd_id,skpd_nama,sum(pagu) AS pagu,sum(sisa_pagu) AS sisa_pagu');
		$this->db->from('table_anggaran');
		$this->db->join('table_skpd','table_anggaran.skpd_id=table_skpd.skpd_id');
		$this->db->group_by('table_anggaran.skpd_id');
		$this->db->where($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get($id_anggaran) {
		$this->db->where('id_anggaran', $id_anggaran);
		$query = $this->db->get('table_anggaran', 1);
		return $query->result_array();
	}
	
	public function create($data) {
		
		$this->db->insert('table_anggaran', $data);
	}
	
	public function update($data) {
		$this->db->update('table_anggaran', $data, array('id_anggaran'=>$data['id_anggaran']));
	}
	
	public function delete($id_anggaran) {
		$this->db->delete('table_anggaran', array('id_anggaran' => $id_anggaran));
	}
	
	public function status_perjalanan_walikota($telaah_id){
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get('table_timeline8', 1);
		return $query->result_array();
	}
	
	public function pengguna($limit, $start){
		$this->db->select('*');
		$this->db->from('table_anggaran');
		$this->db->join('table_skpd','table_anggaran.skpd_id=table_skpd.skpd_id','left');
		$this->db->join('table_pegawai','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function skpd() {
		$this->db->select('*');
		$this->db->from('table_skpd');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function grafik_perjalanan($skpd_id) {
		$this->db->select('count(*) as total, table_skpd.skpd_id, skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana = table_pegawai.pegawai_id');
		$this->db->join('table_skpd','table_pegawai.skpd_id = table_skpd.skpd_id');
		$this->db->where("(table_telaah.telaah_status='1' OR table_telaah.telaah_status='2' OR table_telaah.telaah_status='5')");
		$this->db->where("table_skpd.skpd_id !='171'");
		$this->db->where("table_skpd.skpd_id !='172'");
		$this->db->where('table_skpd.jenis_skpd',$skpd_id);
		$this->db->group_by('table_skpd.skpd_id');
		$this->db->order_by('total','DESC');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function grafik_pdld($skpd_id) {
		$this->db->select('count(*) as total, table_skpd.skpd_id, skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana = table_pegawai.pegawai_id');
		$this->db->join('table_skpd','table_pegawai.skpd_id = table_skpd.skpd_id');
		$this->db->where("(table_telaah.telaah_status='1' OR table_telaah.telaah_status='2' OR table_telaah.telaah_status='5')");
		$this->db->where("(table_telaah.telaah_domainperjalanan='1' OR table_telaah.telaah_domainperjalanan='2')");
		$this->db->where('table_skpd.skpd_id',$skpd_id);
		$this->db->order_by('total','DESC');
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function grafik_pdld_all($jenis_skpd) {
		$this->db->select('count(*) as total, table_skpd.skpd_id, skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana = table_pegawai.pegawai_id');
		$this->db->join('table_skpd','table_pegawai.skpd_id = table_skpd.skpd_id');
		$this->db->where("(table_telaah.telaah_status='1' OR table_telaah.telaah_status='2' OR table_telaah.telaah_status='5')");
		$this->db->where("(table_telaah.telaah_domainperjalanan='1' OR table_telaah.telaah_domainperjalanan='2')");
		$this->db->where("table_skpd.skpd_id !='171'");
		$this->db->where("table_skpd.skpd_id !='172'");
		$this->db->where('table_skpd.jenis_skpd',$jenis_skpd);
		$this->db->order_by('total','DESC');
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function grafik_pddd($skpd_id) {
		$this->db->select('count(*) as total, table_skpd.skpd_id, skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana = table_pegawai.pegawai_id');
		$this->db->join('table_skpd','table_pegawai.skpd_id = table_skpd.skpd_id');
		$this->db->where("(table_telaah.telaah_status='1' OR table_telaah.telaah_status='2' OR table_telaah.telaah_status='5')");
		$this->db->where("(table_telaah.telaah_domainperjalanan='3' OR table_telaah.telaah_domainperjalanan='4')");
		$this->db->where('table_skpd.skpd_id',$skpd_id);
		$this->db->order_by('total','DESC');
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function grafik_pddd_all($jenis_skpd) {
		$this->db->select('count(*) as total, table_skpd.skpd_id, skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana = table_pegawai.pegawai_id');
		$this->db->join('table_skpd','table_pegawai.skpd_id = table_skpd.skpd_id');
		$this->db->where("(table_telaah.telaah_status='1' OR table_telaah.telaah_status='2' OR table_telaah.telaah_status='5')");
		$this->db->where("(table_telaah.telaah_domainperjalanan='3' OR table_telaah.telaah_domainperjalanan='4')");
		$this->db->where("table_skpd.skpd_id !='171'");
		$this->db->where("table_skpd.skpd_id !='172'");
		$this->db->where('table_skpd.jenis_skpd',$jenis_skpd);
		$this->db->order_by('total','DESC');
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	##KEPALA OPD
	
	public function getSKPD() {
    $query  = $this->db->query("SELECT skpd_id, skpd_nama FROM table_skpd WHERE jenis_skpd=1");
    return $query->result();
  }
  
	public function record_count_search_kadis($column, $data) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			LEFT JOIN table_skpd ON table_skpd.skpd_id=table_pegawai.skpd_id
			WHERE telaah_kategori='2' 
			AND $column = $data");
	}
	
	public function data_search_kadis($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',2);
		$this->db->where($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	##ESSELON
	
	public function record_count_search_esselon($column, $data) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			LEFT JOIN table_skpd ON table_skpd.skpd_id=table_pegawai.skpd_id
			WHERE telaah_kategori='1' AND $column = $data");
	}
	
	public function data_search_esselon($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',1);
		$this->db->where($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## CAMAT DAN LURAH
	
	public function getSKPD_Camat() {
		$query  = $this->db->query("SELECT skpd_id, skpd_nama FROM table_skpd WHERE jenis_skpd=4 OR jenis_skpd=5");
		return $query->result();
	  }
  
	public function record_count_camat() {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			LEFT JOIN table_skpd ON table_skpd.skpd_id=table_pegawai.skpd_id
			WHERE telaah_kategori='5'");
	}
	
	public function record_count_search_camat($column, $data) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			LEFT JOIN table_skpd ON table_skpd.skpd_id=table_pegawai.skpd_id
			WHERE telaah_kategori='5' AND $column = $data");
	}
	
	public function data_camat($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');
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
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');
		$this->db->where('table_telaah.telaah_kategori',5);
		$this->db->where($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	### STAFF CAMAT & LURAH
	public function record_count_staffcamat() {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			LEFT JOIN table_skpd ON table_skpd.skpd_id=table_pegawai.skpd_id
			WHERE telaah_kategori='7'");
	}
	
	public function record_count_search_staffcamat($column, $data) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			LEFT JOIN table_skpd ON table_skpd.skpd_id=table_pegawai.skpd_id
			WHERE telaah_kategori='7' AND $column = $data");
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
		$this->db->where($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## PUSKESMAS (JKN)
	
	public function getSKPD_puskesmas() {
		$query  = $this->db->query("SELECT skpd_id, skpd_nama FROM table_skpd WHERE jenis_skpd=7");
		return $query->result();
	  }
	  
	public function record_count_search_kapus($column, $data) {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pegawai ON table_telaah.telaah_pelaksana=table_pegawai.pegawai_id
			LEFT JOIN table_skpd ON table_skpd.skpd_id=table_pegawai.skpd_id
			WHERE telaah_kategori='11' AND $column = $data");
	}
	
	public function data_search_kapus($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',11);
		$this->db->order_by('telaah_waktuinput','DESC');
		$this->db->where($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
}