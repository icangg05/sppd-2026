<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_kalender extends CI_Model
{
	
	public function data($skpd_id,$tahun) {
		// $this->db->select('*');
		// $bulan_sebelumnya = $bulan-1;
		// $bulan_selanjutnya = $bulan+1;
		$this->db->select('telaah_id,pegawai_nama,telaah_kategori,telaah_jenis_skpd,telaah_tanggalberangkat,telaah_tanggalkembali');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->where('telaah_skpd_id', $skpd_id);
		$this->db->where('telaah_status', 2);
		 $this->db->where('YEAR(telaah_tanggalberangkat)', $tahun);
		// $this->db->where("(MONTH(telaah_tanggalberangkat)='$bulan' OR MONTH(telaah_tanggalberangkat)='$bulan_sebelumnya' OR MONTH(telaah_tanggalberangkat)='$bulan_selanjutnya') ");
  		return $this->db->get('table_telaah');
	}

	
}