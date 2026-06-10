<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_relasi_sekda extends CI_Model
{
	
	public function bagian_all() {
		$this->db->select('*');
		$this->db->from('table_bagian');
		$query = $this->db->get();
		return $query->result();
	}
	
	public function bagian($bagian_id) {
		$this->db->select('*');
		$this->db->from('table_bagian');
		$this->db->where('bagian_id', $bagian_id);
		$query = $this->db->get();
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
	
	public function getasisten($id) {
		$this->db->select('*');
		$this->db->from('users');
		$this->db->join('users_groups_asisten','users_groups_asisten.user_id=users.id');
		$this->db->join('table_asisten','table_asisten.asisten_id=users_groups_asisten.asisten_id', 'LEFT');
		$this->db->join('table_bagian','table_bagian.asisten_id=table_asisten.asisten_id', 'LEFT');
		$this->db->join('table_subbagian','table_bagian.bagian_id=table_subbagian.bagian_id', 'LEFT');
		$this->db->where('users.id', $id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function create($data) {
		$this->db->insert('table_relasi_sekda', $data);
	}
	
	public function update($data) {
		$this->db->update('table_relasi_sekda', $data, array('id_kelurahan'=>$data['id_kelurahan']));
	}
	
	public function delete($id_kelurahan) {
		$this->db->delete('table_relasi_sekda', array('id_kelurahan' => $id_kelurahan));
	}
	
}