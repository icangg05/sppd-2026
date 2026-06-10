<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_export extends CI_Model
{
	## Model Laporan Perjalanan Luar Daerah
	public function get() {
		$this->db->select('table_telaah.telaah_id, pegawai_nama, pegawai_namajabatan, pegawai_nip, pegawai_golongan, telaah_perihal, provinsi, tanggal_spt, tanggal_spd, telaah_tanggalberangkat, telaah_tanggalkembali, telaah_hari');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id = table_telaah.telaah_pelaksana','LEFT');
		$this->db->join('table_provinsi','table_provinsi.provinsi_id = table_telaah.telaah_provinsitujuan','LEFT');
		$this->db->join('table_tanggal_perjalanan','table_tanggal_perjalanan.telaah_id = table_telaah.telaah_id','LEFT');
        $this->db->where('(telaah_domainperjalanan = 1 OR telaah_domainperjalanan = 2)');
        $this->db->where('skpd_id', 1);
        $this->db->where('year(telaah_tanggalberangkat)',date('Y'));
        $this->db->order_by('table_telaah.telaah_id','DESC');
        $query = $this->db->get();
        return $query->result();
	}
	
	public function skpd() {
		$this->db->select('*');
		$this->db->from('table_skpd');
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function pengikut($telaah_id) {
		$this->db->select('table_pengikut.pegawai_id, pegawai_nama, pegawai_namajabatan, pegawai_nip, pegawai_golongan');
		$this->db->from('table_pengikut');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id = table_pengikut.pegawai_id','LEFT');
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get3($telaah_id,$pegawai_id) {
		$this->db->select('SUM(tarif) AS total');
		$this->db->from('table_pengeluaran_rill');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function get4($telaah_id, $pegawai_id) {
		$this->db->select('SUM(tarif*item) AS total');
		$this->db->from('table_rincian_biaya');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	##=========================================================================================================##
	
	## Model Laporan Perjalanan Dalam Daerah
	public function get5() {
		$this->db->select('table_telaah.telaah_id, pegawai_nama, pegawai_namajabatan, pegawai_nip, pegawai_golongan, telaah_perihal, provinsi, tanggal_spt, tanggal_spd, telaah_tanggalberangkat, telaah_tanggalkembali, telaah_hari');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id = table_telaah.telaah_pelaksana','LEFT');
		$this->db->join('table_provinsi','table_provinsi.provinsi_id = table_telaah.telaah_provinsitujuan','LEFT');
		$this->db->join('table_tanggal_perjalanan','table_tanggal_perjalanan.telaah_id = table_telaah.telaah_id','LEFT');
        $this->db->where('(telaah_domainperjalanan = 3 OR telaah_domainperjalanan = 4)');
        $this->db->where('skpd_id', 1);
        $this->db->where('year(telaah_tanggalberangkat)',date('Y'));
        $this->db->order_by('table_telaah.telaah_id','DESC');
        $query = $this->db->get();
        return $query->result();
	}
	
	public function pengikut1($telaah_id) {
		$this->db->select('table_pengikut.pegawai_id, pegawai_nama, pegawai_namajabatan, pegawai_nip, pegawai_golongan');
		$this->db->from('table_pengikut');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id = table_pengikut.pegawai_id','LEFT');
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get6($telaah_id,$pegawai_id) {
		$this->db->select('SUM(tarif) AS total');
		$this->db->from('table_pengeluaran_rill');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function get7($telaah_id, $pegawai_id) {
		$this->db->select('SUM(tarif*item) AS total');
		$this->db->from('table_rincian_biaya');
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	#Model Rekapan Laporan Perjalanan Dinas#
	public function get_laporan() {
		$this->db->select('table_telaah.telaah_id, pegawai_nama, pegawai_namajabatan, pegawai_nip, pegawai_golongan, telaah_perihal, provinsi, tanggal_spt, tanggal_spd, telaah_tanggalberangkat, telaah_tanggalkembali, telaah_hari');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id = table_telaah.telaah_pelaksana','LEFT');
		$this->db->join('table_provinsi','table_provinsi.provinsi_id = table_telaah.telaah_provinsitujuan','LEFT');
		$this->db->join('table_tanggal_perjalanan','table_tanggal_perjalanan.telaah_id = table_telaah.telaah_id','LEFT');
        $this->db->where('(telaah_domainperjalanan = 1 OR telaah_domainperjalanan = 2)');
        $this->db->where('skpd_id', 1);
        $this->db->where('year(telaah_tanggalberangkat)',date('Y'));
        $this->db->order_by('table_telaah.telaah_id','DESC');
        $query = $this->db->get();
        return $query->result();
	}

	#Model Rekapan Laporan Perjalanan Dinas#
	public function telaah_pelaksana($limit, $start, $pegawai_id) {
		$this->db->select('table_telaah.*, pegawai_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id = table_telaah.telaah_pelaksana','LEFT');
		$this->db->where('telaah_pelaksana', $pegawai_id);

		$query1 = $this->db->get_compiled_select(); 

		$this->db->select('table_telaah.*, pegawai_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pengikut','table_pengikut.telaah_id = table_telaah.telaah_id','LEFT');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id = table_pengikut.pegawai_id','LEFT');
		$this->db->where('table_pengikut.pegawai_id', $pegawai_id);

		$query2 = $this->db->get_compiled_select(); 

		if($limit){
			$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_id DESC LIMIT $start, $limit");
			return $query->result();
		} else {
			$query = $this->db->query($query1." UNION ALL ".$query2."");
			return $query->result(); 
		}
	}

	#Model Rekapan Laporan Perjalanan Dinas#
	public function search_telaah_pelaksana($column, $value, $limit, $start, $pegawai_id) {
		$this->db->select('table_telaah.*, pegawai_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id = table_telaah.telaah_pelaksana','LEFT');
		$this->db->where('telaah_pelaksana', $pegawai_id);

		$query1 = $this->db->get_compiled_select(); 

		$this->db->select('table_telaah.*, pegawai_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pengikut','table_pengikut.telaah_id = table_telaah.telaah_id','LEFT');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id = table_pengikut.pegawai_id','LEFT');
		$this->db->where('table_pengikut.pegawai_id', $pegawai_id);
		$this->db->like($column,$value);

		$query2 = $this->db->get_compiled_select(); 

		if($limit){
			$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_id DESC LIMIT $start, $limit");
			return $query->result();
		} else {
			$query = $this->db->query($query1." UNION ALL ".$query2."");
			return $query->result(); 
		}
	}
}