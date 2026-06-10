<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_spd extends CI_Model
{	
	public function pelaksana($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_telaah, table_pegawai');
		$this->db->where('table_telaah.telaah_pelaksana = table_pegawai.pegawai_id');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function pelaksanadprd($telaah_id) {
		$this->db->select('*, anggotadprd_id as pegawai_id, anggotadprd_name as pegawai_nama, anggotadprd_jabatan as pegawai_namajabatan');
		$this->db->from('table_telaah, table_anggotadprd');
		$this->db->where('table_telaah.telaah_pelaksana = table_anggotadprd.anggotadprd_id');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function pelaksanaWalikota($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_telaah, table_pimpinan');
		$this->db->where('table_telaah.telaah_pelaksana = table_pimpinan.pegawai_id');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function pengikut($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_pengikut, table_pegawai');
		$this->db->where('table_pengikut.pegawai_id = table_pegawai.pegawai_id');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function pengikutdprd($telaah_id) {
		$this->db->select('*, anggotadprd_name as pegawai_nama, anggotadprd_jabatan as pegawai_namajabatan');
		$this->db->from('table_pengikut, table_anggotadprd');
		$this->db->where('table_pengikut.pegawai_id = table_anggotadprd.anggotadprd_id');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function getTelaahKategori($telaah_id) {
		$this->db->select('telaah_kategori');
		$this->db->from('table_telaah');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## Tanda Tangan SPD
	
	public function walikota() {
		$this->db->select('*');
		$this->db->from('table_pimpinan');
		$this->db->join('table_jabatan','table_jabatan.jabatan_id = table_pimpinan.pegawai_jabatan','LEFT');
		$this->db->where('jabatan_id',1);
		$this->db->where('status_delete',0);
        $this->db->order_by('pegawai_id','DESC');
        $this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function wakil_walikota() {
		$this->db->select('*');
		$this->db->from('table_pimpinan');
		$this->db->join('table_jabatan','table_jabatan.jabatan_id = table_pimpinan.pegawai_jabatan','LEFT');
		$this->db->where('jabatan_id',14);
		$this->db->where('status_delete',0);
        $this->db->order_by('pegawai_id','DESC');
        $this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function sekda() {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->join('table_jabatan','table_jabatan.jabatan_id = table_pegawai.pegawai_jabatan','LEFT');
		$this->db->join('table_skpd','table_skpd.skpd_id = table_pegawai.skpd_id','LEFT');
		$this->db->where('jabatan_id',3);
		$this->db->where('status_delete',0);
        $this->db->order_by('pegawai_id','DESC');
        $this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function asisten1() {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->join('table_jabatan','table_jabatan.jabatan_id = table_pegawai.pegawai_jabatan','LEFT');
		$this->db->where('jabatan_id',2);
		$this->db->where('status_delete',0);
        $this->db->order_by('pegawai_id','DESC');
        $this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function asisten2() {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->join('table_jabatan','table_jabatan.jabatan_id = table_pegawai.pegawai_jabatan','LEFT');
		$this->db->where('jabatan_id',17);
		$this->db->where('status_delete',0);
        $this->db->order_by('pegawai_id','DESC');
        $this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function asisten3() {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->join('table_jabatan','table_jabatan.jabatan_id = table_pegawai.pegawai_jabatan','LEFT');
		$this->db->where('jabatan_id',18);
		$this->db->where('status_delete',0);
        $this->db->order_by('pegawai_id','DESC');
        $this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function ketua_dprd() {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->join('table_jabatan','table_jabatan.jabatan_id = table_pegawai.pegawai_jabatan','LEFT');
		$this->db->where('jabatan_id',5);
		$this->db->where('status_delete',0);
        $this->db->order_by('pegawai_id','DESC');
        $this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function wakil_ketua_dprd() {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->join('table_jabatan','table_jabatan.jabatan_id = table_pegawai.pegawai_jabatan','LEFT');
		$this->db->where('jabatan_id',20);
		$this->db->where('status_delete',0);
        $this->db->order_by('pegawai_id','DESC');
        $this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function kepala_opd($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->join('table_jabatan','table_jabatan.jabatan_id = table_pegawai.pegawai_jabatan','LEFT');
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('jabatan_id',4);
		$this->db->where('status_delete',0);
        $this->db->order_by('pegawai_id','DESC');
        $this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function sekwan() {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->join('table_jabatan','table_jabatan.jabatan_id = table_pegawai.pegawai_jabatan','LEFT');
		$this->db->where('jabatan_id',9);
		$this->db->where('status_delete',0);
        $this->db->order_by('pegawai_id','DESC');
        $this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function sekretaris_opd($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->join('table_jabatan','table_jabatan.jabatan_id = table_pegawai.pegawai_jabatan','LEFT');
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('jabatan_id',6);
		$this->db->where('status_delete',0);
		$this->db->order_by('pegawai_id','DESC');
		$this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function kabid_opd($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->join('table_jabatan','table_jabatan.jabatan_id = table_pegawai.pegawai_jabatan','LEFT');
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('jabatan_id',7);
		$this->db->where('status_delete',0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function kabid_dinkes() {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->join('table_jabatan','table_jabatan.jabatan_id = table_pegawai.pegawai_jabatan','LEFT');
		$this->db->where('skpd_id',36);
		$this->db->where('status_delete',0);
		$this->db->where('jabatan_id',7);
        $this->db->order_by('pegawai_id','DESC');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function relasi_kelurahan($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_relasi_kelurahan');
		$this->db->where('id_kelurahan',$skpd_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function camat($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->join('table_jabatan','table_jabatan.jabatan_id = table_pegawai.pegawai_jabatan','LEFT');
		$this->db->join('table_skpd','table_skpd.skpd_id = table_pegawai.skpd_id','LEFT');
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->where('jabatan_id',10);
		$this->db->where('status_delete',0);
        $this->db->order_by('pegawai_id','DESC');
        $this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function sekcam($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->join('table_jabatan','table_jabatan.jabatan_id = table_pegawai.pegawai_jabatan','LEFT');
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('jabatan_id',11);
		$this->db->where('status_delete',0);
        $this->db->order_by('pegawai_id','DESC');
        $this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function lurah($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->join('table_jabatan','table_jabatan.jabatan_id = table_pegawai.pegawai_jabatan','LEFT');
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('jabatan_id',12);
		$this->db->where('status_delete',0);
        $this->db->order_by('pegawai_id','DESC');
        $this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function kapus($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->join('table_jabatan','table_jabatan.jabatan_id = table_pegawai.pegawai_jabatan','LEFT');
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('jabatan_id',19);
		$this->db->where('status_delete',0);
        $this->db->order_by('pegawai_id','DESC');
        $this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function bendahara($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->join('table_jabatan','table_jabatan.jabatan_id = table_pegawai.pegawai_jabatan','LEFT');
		$this->db->where('skpd_id',$skpd_id);
		$this->db->where('status_delete',0);
		$this->db->where('jabatan_id',13);
		$query = $this->db->get ();
		return $query->result();
	}
	
	/// TANGGAL PERJALANAN
	public function get_tanggal_perjalanan($telaah_id)
	{
		$this->db->select('*');
		$this->db->from('table_tanggal_perjalanan');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function get($tanggal_perjalanan_id) {
		$this->db->select('*');
		$this->db->from('table_tanggal_perjalanan');
		$this->db->where('tanggal_perjalanan_id',$tanggal_perjalanan_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function create($data) {
		
        $this->db->insert('table_tanggal_perjalanan', $data);
    }
	
	public function update($data) {
        $this->db->update('table_tanggal_perjalanan', $data, array('tanggal_perjalanan_id'=>$data['tanggal_perjalanan_id']));
    }
	
	public function delete($tanggal_perjalanan_id) {
        $this->db->delete('table_tanggal_perjalanan', array('tanggal_perjalanan_id' => $tanggal_perjalanan_id));
    }
	
}