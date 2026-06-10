<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_pegawai extends CI_Model
{
	
	public function record_count($skpd_id) {
		return $this->db->count_all("table_pegawai WHERE skpd_id = '$skpd_id'");
	}
	
	public function record_count_setda($skpd_id, $bagian_id) {
		return $this->db->count_all("table_pegawai WHERE skpd_id = '$skpd_id' AND bagian_id='$bagian_id'");
	}
	
	public function record_count_search($column, $data, $skpd_id) {
		return $this->db->count_all("table_pegawai WHERE skpd_id = '$skpd_id' AND $column like '%$data%'");
	}
	
	public function record_count_search_setda($column, $data, $skpd_id, $bagian_id) {
		return $this->db->count_all("table_pegawai WHERE skpd_id = '$skpd_id' AND bagian_id='$bagian_id' AND $column like '%$data%'");
	}
	
	public function data($limit, $start, $skpd_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->join('table_golongan','table_pegawai.pegawai_golongan=table_golongan.golongan','left');
		$this->db->where('table_pegawai.status_delete',0);
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$query1 = $this->db->get_compiled_select(); 

		$this->db->select('*');
		$this->db->from('table_pimpinan');
		$this->db->join('table_skpd','table_pimpinan.skpd_id=table_skpd.skpd_id','left');
		$this->db->join('table_golongan','table_pimpinan.pegawai_golongan=table_golongan.golongan','left');
		$this->db->where('table_pimpinan.status_delete',0);
		$this->db->where('table_pimpinan.skpd_id',$skpd_id);
		$query2 = $this->db->get_compiled_select(); 

		if($limit){
			$this->db->limit ($limit, $start);
			if(!$start){
				$s = 0;
			} else {
				$s = $start;
			}
			$query = $this->db->query($query1." UNION ALL ".$query2." limit ".$s." , ".$limit);
			return $query->result();
		} else {
			$a = $this->db->count_all_results("table_pegawai WHERE skpd_id = '$skpd_id' AND status_delete = 0 "); 
			$b = $this->db->count_all_results("table_pimpinan WHERE skpd_id = '$skpd_id' AND status_delete = 0 "); 
			return $a + $b;
		}
	}
	
	public function data_setda($limit, $start, $skpd_id, $bagian_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->join('table_golongan','table_pegawai.pegawai_golongan=table_golongan.golongan','left');
		$this->db->where('table_pegawai.status_delete',0);
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->where('table_pegawai.bagian_id',$bagian_id);
		
		if($limit){					
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
		
	}
	
	public function data_search($column, $value, $limit, $start, $skpd_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->join('table_golongan','table_pegawai.pegawai_golongan=table_golongan.golongan','left');
		$this->db->where('table_pegawai.status_delete',0);
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->like($column,$value);
		$query1 = $this->db->get_compiled_select(); 

		$this->db->select('*');
		$this->db->from('table_pimpinan');
		$this->db->join('table_skpd','table_pimpinan.skpd_id=table_skpd.skpd_id','left');
		$this->db->join('table_golongan','table_pimpinan.pegawai_golongan=table_golongan.golongan','left');
		$this->db->where('table_pimpinan.status_delete',0);
		$this->db->where('table_pimpinan.skpd_id',$skpd_id);
		$this->db->like($column,$value);
		$query2 = $this->db->get_compiled_select(); 

		if($limit){
			$this->db->limit ($limit, $start);
			if(!$start){
				$s = 0;
			} else {
				$s = $start;
			}
			$query = $this->db->query($query1." UNION ALL ".$query2." limit ".$s." , ".$limit);
			return $query->result();
		} else {
			$a = $this->db->count_all_results("table_pegawai WHERE skpd_id = '$skpd_id' AND status_delete = 0  AND $column like '%$value%'"); 
			$b = $this->db->count_all_results("table_pimpinan WHERE skpd_id = '$skpd_id' AND status_delete = 0  AND $column like '%$value%'"); 
			return $a + $b;
		}
	}
	
	public function data_search_setda($column, $value, $limit, $start, $skpd_id, $bagian_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->join('table_golongan','table_pegawai.pegawai_golongan=table_golongan.golongan','left');
		$this->db->where('table_pegawai.status_delete',0);
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->where('table_pegawai.bagian_id',$bagian_id);
		$this->db->like($column,$value);
		
		if($limit){					
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
		
	}
	
	public function pptk($limit, $start, $skpd_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->join('table_golongan','table_pegawai.pegawai_golongan=table_golongan.golongan','left');
		$this->db->join('table_jabatan','table_pegawai.pegawai_jabatan=table_jabatan.jabatan_id','left');
		$this->db->where('table_pegawai.status_delete',0);
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->group_start();
		$this->db->where('pegawai_jabatan', 1);
		$this->db->or_where('pegawai_jabatan', 2);
		$this->db->or_where('pegawai_jabatan', 3);
		$this->db->or_where('pegawai_jabatan', 4);
		$this->db->or_where('pegawai_jabatan', 5);
		$this->db->or_where('pegawai_jabatan', 6);
		$this->db->or_where('pegawai_jabatan', 7);
		$this->db->or_where('pegawai_jabatan', 8);
		$this->db->or_where('pegawai_jabatan', 9);
		$this->db->or_where('pegawai_jabatan', 10);
		$this->db->or_where('pegawai_jabatan', 11);
		$this->db->or_where('pegawai_jabatan', 12);
		$this->db->or_where('pegawai_jabatan', 13);
		$this->db->or_where('pegawai_jabatan', 14);
		$this->db->or_where('pegawai_jabatan', 17);
		$this->db->or_where('pegawai_jabatan', 18);
		$this->db->or_where('pegawai_jabatan', 19);
		$this->db->or_where('pegawai_jabatan', 20);
		$this->db->group_end();
		$this->db->order_by('jabatan_id','ASC');
		if($limit){
			$this->db->limit ($limit, $start);
		}
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get($pegawai_id) {
		$this->db->where('pegawai_id', $pegawai_id);
		$query = $this->db->get('table_pegawai', 1);
		return $query->result_array();
	}
	
	public function get_pegawai_nik($posisi,$nik,$skpd_id) {
		$group = $this->ion_auth->get_users_groups()->row()->id;
		$this->db->select('*');
		if($posisi=="dprd"){
			$this->db->from('table_anggotadprd');
		} else if($posisi=="walikota"){
			$this->db->from('table_pimpinan');
			$this->db->join('table_golongan','table_golongan.golongan=table_pimpinan.pegawai_golongan', 'LEFT');
		} else if($posisi=="walikota2" && $group==6){
			$this->db->from('table_pegawai');
			$this->db->join('table_golongan','table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');
		} else if($posisi=="walikota2" && $group==8){
			$this->db->from('table_pimpinan');
			$this->db->join('table_golongan','table_golongan.golongan=table_pimpinan.pegawai_golongan', 'LEFT');
		} else {
			$this->db->from('table_pegawai');
			$this->db->join('table_golongan','table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');
		}
		
		switch($posisi){
			case "kadis" 		: $jabatan = 4; break;
			case "walikota" 	: 
									if($group == 6){
										$jabatan = 3;
									} else {
										$jabatan = 1;
									}
									break;
			case "walikota2" 	: 
									if($group == 6){
										$jabatan = 3;
									} else {
										$jabatan = 1;
									}
									break;
			case "sekwan" 		: $jabatan = 9; break;
			case "kadprd" 		: $jabatan = 5; break;
			case "sekda" 		: $jabatan = 3; break;
			case "camat" 		: $jabatan = 10; break;
			case "lurah" 		: $jabatan = 12; break;
			case "kapus" 		: $jabatan = 19; break;
		}
		
		if($jabatan==4 || $jabatan==10 || $jabatan==12 || $jabatan==19){
			if($skpd_id){
				$this->db->where('skpd_id', $skpd_id);
			} else {
				$this->db->where('skpd_id', $this->ion_auth->user()->row()->skpd_id);
			}
		}
		$this->db->where('pegawai_jabatan', $jabatan);
		$this->db->where('pegawai_nik', $nik);
		$this->db->where('status_delete', 0);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function get_walikota($pegawai_id) {
		$this->db->where('pegawai_id', $pegawai_id);
		$query = $this->db->get('table_pimpinan', 1);
		return $query->result_array();
	}
	
	public function get_status($pegawai_id, $status) {
		$this->db->where('pegawai_id', $pegawai_id);
		$this->db->where('status', $status);
		$query = $this->db->get('table_pegawai', 1);
		return $query->result_array();
	}
	
	public function get_pegawai_skpd($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->where('status_delete',0);
		$this->db->where('skpd_id', $skpd_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function create($data) {
		
		$this->db->insert('table_pegawai', $data);
	}
	
	public function update($data) {
		$this->db->update('table_pegawai', $data, array('pegawai_id'=>$data['pegawai_id']));
	}
	
	public function update_walikota($data) {
		$this->db->update('table_pimpinan', $data, array('pegawai_id'=>$data['pegawai_id']));
	}
	
	public function delete($pegawai_id) {
		$this->db->delete('table_pegawai', array('pegawai_id' => $pegawai_id));
	}
	
	public function skpd() {
		$this->db->select('*');
		$this->db->from('table_skpd');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function skpd2($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_skpd');
		$this->db->where('skpd_id',$skpd_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function golongan() {
		$this->db->select('*');
		$this->db->from('table_golongan');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function esselon() {
		$this->db->select('*');
		$this->db->from('table_esselon');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function pegawai($pegawai_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->where('pegawai_nama', $pegawai_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function jabatan() {
		$this->db->select('*');
		$this->db->from('table_jabatan');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function subbagian() {
		$this->db->select('*');
		$this->db->from('table_subbagian');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function bagian() {
		$this->db->select('*');
		$this->db->from('table_bagian');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function asisten() {
		$this->db->select('*');
		$this->db->from('table_asisten');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function link_gambar($pegawai_id)
	{
		
		$this->db->where('pegawai_id',$pegawai_id);
		$query = $getData = $this->db->get('table_pegawai');
		if($getData->num_rows() > 0)
			return $query;
		else
			return null;
		
	}
}