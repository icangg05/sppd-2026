<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_tte extends CI_Model
{
	
	public function data($limit, $start, $group, $skpd_id, $jenis_skpd) {
		$this->db->select('	telaah_waktuinput, 
							telaah_perihal, 
							table_telaah.telaah_kategori,
							table_pegawai.pegawai_nama as pegawai_nama_opd, 
							table_pegawai.pegawai_namajabatan as jabatan_opd, 
							table_pimpinan.pegawai_nama as pegawai_nama_walikota, 
							table_pimpinan.pegawai_namajabatan as jabatan_walikota, 
							table_anggotadprd.anggotadprd_name as pegawai_nama_dprd, 
							table_anggotadprd.anggotadprd_jabatan as jabatan_dprd, 
							table_telaah.telaah_id as telaah_id, 
							tte_id,
							table_tte.pegawai_id as penandatangan,
							status_tte');
		$this->db->from('table_tte');
		$this->db->join('table_telaah','table_telaah.telaah_id=table_tte.telaah_id', 'LEFT');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id=table_telaah.telaah_pelaksana', 'LEFT');
		$this->db->join('table_pimpinan','table_pimpinan.pegawai_id=table_telaah.telaah_pelaksana', 'LEFT');
		$this->db->join('table_anggotadprd','table_anggotadprd.anggotadprd_id=table_telaah.telaah_pelaksana', 'LEFT');
		$this->db->where('table_tte.group', $group);
		$this->db->where('table_tte.skpd_id', $skpd_id);
		$this->db->where('table_tte.jenis_skpd', $jenis_skpd);
		if($limit){
			$this->db->limit ($limit, $start);
			$this->db->order_by ('status_tte', 'ASC');
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function search($column, $value, $limit, $start, $group, $skpd_id, $jenis_skpd) {
		$this->db->select('	telaah_waktuinput, 
							telaah_perihal, 
							table_telaah.telaah_kategori,
							table_pegawai.pegawai_nama as pegawai_nama_opd, 
							table_pegawai.pegawai_namajabatan as jabatan_opd, 
							table_pimpinan.pegawai_nama as pegawai_nama_walikota, 
							table_pimpinan.pegawai_namajabatan as jabatan_walikota, 
							table_anggotadprd.anggotadprd_name as pegawai_nama_dprd, 
							table_anggotadprd.anggotadprd_jabatan as jabatan_dprd, 
							table_telaah.telaah_id as telaah_id, 
							tte_id,
							table_tte.pegawai_id as penandatangan,
							status_tte');
		$this->db->from('table_tte');
		$this->db->join('table_telaah','table_telaah.telaah_id=table_tte.telaah_id', 'LEFT');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id=table_telaah.telaah_pelaksana', 'LEFT');
		$this->db->join('table_pimpinan','table_pimpinan.pegawai_id=table_telaah.telaah_pelaksana', 'LEFT');
		$this->db->join('table_anggotadprd','table_anggotadprd.anggotadprd_id=table_telaah.telaah_pelaksana', 'LEFT');
		$this->db->where('table_tte.group', $group);
		$this->db->where('table_tte.skpd_id', $skpd_id);
		$this->db->where('table_tte.jenis_skpd', $jenis_skpd);
		$this->db->group_start();
		$this->db->like($column,$value);
		$this->db->or_like('table_pimpinan.pegawai_nama',$value);
		$this->db->or_like('table_anggotadprd.anggotadprd_name',$value);
		$this->db->group_end();
		if($limit){
			$this->db->limit ($limit, $start);
			$this->db->order_by ('tte_id', 'DESC');
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function update($data) {
		$this->db->update('table_tte', $data, array('tte_id'=>$data['tte_id']));
	}
	
}