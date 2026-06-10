<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_history extends CI_Model
{
	public function record_count($pegawai_id) {
		$this->db->select('table_telaah.*, table_pegawai.*');
		$this->db->from('table_telaah, table_pegawai');
		$this->db->where('table_telaah.telaah_pelaksana = table_pegawai.pegawai_id');
		$this->db->where('pegawai_id',$pegawai_id);
		$this->db->where('telaah_status', 2);
		$query1 = $this->db->get_compiled_select(); 
		
		$this->db->select('table_telaah.*, table_pegawai.*');
		$this->db->from('table_telaah, table_pengikut, table_pegawai');
		$this->db->where('table_pengikut.telaah_id = table_telaah.telaah_id');
		$this->db->where('table_pengikut.pegawai_id = table_pegawai.pegawai_id');
		$this->db->where('table_pengikut.pegawai_id',$pegawai_id);
		$this->db->where('telaah_status', 2);
		$query2 = $this->db->get_compiled_select();
		
		$query = $this->db->query($query1." UNION ALL ".$query2."");
		return $query->result();
	}
	
	public function record_count_walikota($pegawai_id) {
		$this->db->select('table_telaah.*, table_pimpinan.*');
		$this->db->from('table_telaah, table_pimpinan');
		$this->db->where('table_telaah.telaah_pelaksana = table_pimpinan.pegawai_id');
		$this->db->where('pegawai_id',$pegawai_id);
		$this->db->where('telaah_status', 2);
		$query1 = $this->db->get_compiled_select(); 
		
		$this->db->select('table_telaah.*, table_pimpinan.*');
		$this->db->from('table_telaah, table_pengikut, table_pimpinan');
		$this->db->where('table_pengikut.telaah_id = table_telaah.telaah_id');
		$this->db->where('table_pengikut.pegawai_id = table_pimpinan.pegawai_id');
		$this->db->where('table_pengikut.pegawai_id',$pegawai_id);
		$this->db->where('telaah_status', 2);
		$query2 = $this->db->get_compiled_select();
		
		$query = $this->db->query($query1." UNION ALL ".$query2."");
		return $query->result();
	}
	
	public function record_count_dprd($anggotadprd_id) {
		$this->db->select('table_telaah.*, table_anggotadprd.*');
		$this->db->from('table_telaah, table_anggotadprd');
		$this->db->where('table_telaah.telaah_pelaksana = table_anggotadprd.anggotadprd_id');
		$this->db->where('anggotadprd_id',$anggotadprd_id);
		$this->db->where('telaah_status', 2);
		$query1 = $this->db->get_compiled_select(); 
		
		$this->db->select('table_telaah.*, table_anggotadprd.*');
		$this->db->from('table_telaah, table_pengikut, table_anggotadprd');
		$this->db->where('table_pengikut.telaah_id = table_telaah.telaah_id');
		$this->db->where('table_pengikut.pegawai_id = table_anggotadprd.anggotadprd_id');
		$this->db->where('table_pengikut.pegawai_id',$anggotadprd_id);
		$this->db->where('telaah_status', 2);
		$query2 = $this->db->get_compiled_select();
		
		$query = $this->db->query($query1." UNION ALL ".$query2."");
		return $query->result();
	}
	
	public function data($limit, $start, $pegawai_id) {
		$this->db->select('table_telaah.*, table_pegawai.*');
		$this->db->from('table_telaah, table_pegawai');
		$this->db->where('table_telaah.telaah_pelaksana = table_pegawai.pegawai_id');
		$this->db->where('pegawai_id',$pegawai_id);
		$this->db->where('telaah_status', 2);
		$query1 = $this->db->get_compiled_select(); 
		
		$this->db->select('table_telaah.*, table_pegawai.*');
		$this->db->from('table_telaah, table_pengikut, table_pegawai');
		$this->db->where('table_pengikut.telaah_id = table_telaah.telaah_id');
		$this->db->where('table_pengikut.pegawai_id = table_pegawai.pegawai_id');
		$this->db->where('table_pengikut.pegawai_id',$pegawai_id);
		$this->db->where('telaah_status', 2);
		$query2 = $this->db->get_compiled_select();
		
		$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT $start, $limit ");
		return $query->result();
	}
	
	public function data_walikota($limit, $start, $pegawai_id) {
		$this->db->select('table_telaah.*, table_pimpinan.*');
		$this->db->from('table_telaah, table_pimpinan');
		$this->db->where('table_telaah.telaah_pelaksana = table_pimpinan.pegawai_id');
		$this->db->where('pegawai_id',$pegawai_id);
		$this->db->where('telaah_status', 2);
		$query1 = $this->db->get_compiled_select(); 
		
		$this->db->select('table_telaah.*, table_pimpinan.*');
		$this->db->from('table_telaah, table_pengikut, table_pimpinan');
		$this->db->where('table_pengikut.telaah_id = table_telaah.telaah_id');
		$this->db->where('table_pengikut.pegawai_id = table_pimpinan.pegawai_id');
		$this->db->where('table_pengikut.pegawai_id',$pegawai_id);
		$this->db->where('telaah_status', 2);
		$query2 = $this->db->get_compiled_select();
		
		$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT $start, $limit ");
		return $query->result();
	}
	
	public function data_dprd($limit, $start, $anggotadprd_id) {
		$this->db->select('table_telaah.*, table_anggotadprd.*');
		$this->db->from('table_telaah, table_anggotadprd');
		$this->db->where('table_telaah.telaah_pelaksana = table_anggotadprd.anggotadprd_id');
		$this->db->where('anggotadprd_id',$anggotadprd_id);
		$this->db->where('telaah_status', 2);
		$query1 = $this->db->get_compiled_select(); 
		
		$this->db->select('table_telaah.*, table_anggotadprd.*');
		$this->db->from('table_telaah, table_pengikut, table_anggotadprd');
		$this->db->where('table_pengikut.telaah_id = table_telaah.telaah_id');
		$this->db->where('table_pengikut.pegawai_id = table_anggotadprd.anggotadprd_id');
		$this->db->where('table_pengikut.pegawai_id',$anggotadprd_id);
		$this->db->where('telaah_status', 2);
		$query2 = $this->db->get_compiled_select();
		
		$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT $start, $limit ");
		return $query->result();
	}
	
	
}