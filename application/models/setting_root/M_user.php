<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_user extends CI_Model
{
	
	public function record_count() {
		return $this->db->count_all('users');
	}
	
	public function record_count_search($column, $data) {
		return $this->db->count_all("users WHERE $column like '%$data%'");
	}
	
	public function data($limit, $start) {
		$this->db->select('users.*, groups.id as group_id, groups.bgcolor, groups.name, skpd_nama');
		$this->db->from('users');
		$this->db->join('users_groups', 'users.id = users_groups.user_id' );
		$this->db->join('groups', 'users_groups.group_id = groups.id' );
		$this->db->join('table_skpd', 'users.skpd_id = table_skpd.skpd_id','LEFT' );
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search($column, $value, $limit, $start) {
		$this->db->select('users.*, groups.id as group_id, groups.bgcolor, groups.name, skpd_nama');
		$this->db->from('users');
		$this->db->join('users_groups', 'users.id = users_groups.user_id' );
		$this->db->join('groups', 'users_groups.group_id = groups.id' );
		$this->db->join('table_skpd', 'users.skpd_id = table_skpd.skpd_id','LEFT' );
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function record_count2($skpd_id) {
		return $this->db->count_all("users WHERE skpd_id='$skpd_id'");
	}
	
	public function record_count_search2($column, $data, $skpd_id) {
		return $this->db->count_all("users WHERE skpd_id='$skpd_id' AND $column like '%$data%'");
	}
	
	public function data2($limit, $start, $skpd_id) {
		$this->db->select('users.*, groups.id as group_id, groups.bgcolor, groups.name, skpd_nama');
		$this->db->from('users');
		$this->db->join('users_groups', 'users.id = users_groups.user_id' );
		$this->db->join('groups', 'users_groups.group_id = groups.id' );
		$this->db->join('table_skpd', 'users.skpd_id = table_skpd.skpd_id','LEFT' );
		$this->db->where('users.skpd_id',$skpd_id);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search2($column, $value, $limit, $start, $skpd_id) {
		$this->db->select('users.*, groups.id as group_id, groups.bgcolor, groups.name, skpd_nama');
		$this->db->from('users');
		$this->db->join('users_groups', 'users.id = users_groups.user_id' );
		$this->db->join('groups', 'users_groups.group_id = groups.id' );
		$this->db->join('table_skpd', 'users.skpd_id = table_skpd.skpd_id','LEFT' );
		$this->db->where('users.skpd_id',$skpd_id);
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	
	public function record_count_setda($skpd_id, $bagian_id) {
		return $this->db->count_all("users 
									LEFT JOIN users_groups_bagian 
									ON users_groups_bagian.user_id = users.id
									WHERE skpd_id='$skpd_id'
									AND bagian_id='$bagian_id'");
	}
	
	public function record_count_search_setda($column, $data, $skpd_id, $bagian_id) {
		return $this->db->count_all("users 
									LEFT JOIN users_groups_bagian 
									ON users_groups_bagian.user_id = users.id
									WHERE skpd_id='$skpd_id' 
									AND bagian_id='$bagian_id'
									AND $column like '%$data%'");
	}
	
	public function data_setda($limit, $start, $skpd_id, $bagian_id) {
		$this->db->select('users.*, groups.id as group_id, groups.bgcolor, groups.name, skpd_nama');
		$this->db->from('users');
		$this->db->join('users_groups', 'users.id = users_groups.user_id' );
		$this->db->join('users_groups_bagian', 'users_groups_bagian.user_id = users.id','LEFT' );
		$this->db->join('users_groups_subbagian', 'users_groups_subbagian.user_id = users.id','LEFT' );
		$this->db->join('groups', 'users_groups.group_id = groups.id' );
		$this->db->join('table_skpd', 'users.skpd_id = table_skpd.skpd_id','LEFT' );
		$this->db->where('users.skpd_id',$skpd_id);
		$this->db->where('bagian_id',$bagian_id);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function search_setda($column, $value, $limit, $start, $skpd_id, $bagian_id) {
		$this->db->select('users.*, groups.id as group_id, groups.bgcolor, groups.name, skpd_nama');
		$this->db->from('users');
		$this->db->join('users_groups', 'users.id = users_groups.user_id' );
		$this->db->join('users_groups_bagian', 'users_groups_bagian.user_id = users.id','LEFT' );
		$this->db->join('groups', 'users_groups.group_id = groups.id' );
		$this->db->join('table_skpd', 'users.skpd_id = table_skpd.skpd_id','LEFT' );
		$this->db->where('users.skpd_id',$skpd_id);
		$this->db->where('bagian_id',$bagian_id);
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get($id_user) {
		$this->db->select('users.*, groups.id as group_id, groups.name as group_name, users_groups_subbagian.subbagian_id, users_groups_bagian.bagian_id, users_groups_asisten.asisten_id');
		$this->db->from('users');
		$this->db->join('users_groups', 'users.id = users_groups.user_id' );
		$this->db->join('groups', 'users_groups.group_id = groups.id' );
		$this->db->join('users_groups_subbagian', 'users_groups_subbagian.user_id = users.id','LEFT' );
		$this->db->join('users_groups_bagian', 'users_groups_bagian.user_id = users.id','LEFT' );
		$this->db->join('users_groups_asisten', 'users_groups_asisten.user_id = users.id','LEFT' );
		$this->db->where('users.id', $id_user );
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function getLast() {
		$this->db->select('*');
		$this->db->from('users');
		$this->db->order_by('id','DESC');
		$this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function groups() {
		$this->db->select('*');
		$this->db->from('groups');
		$this->db->where("id != '100'" );
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function create1($data) {
		$this->db->insert('users_groups_subbagian', $data);
	}
	
	public function create2($data) {
		$this->db->insert('users_groups_bagian', $data);
	}
	
	public function create3($data) {	
		$this->db->insert('users_groups_asisten', $data);
	}
	
	public function delete1($user_id) {
		$this->db->delete('users_groups_subbagian', array('user_id' => $user_id));
	}
	
	public function delete2($user_id) {
		$this->db->delete('users_groups_bagian', array('user_id' => $user_id));
	}
	
	public function delete3($user_id) {
		$this->db->delete('users_groups_asisten', array('user_id' => $user_id));
	}
	
	public function delete($id) {
		$this->db->delete('users', array('id' => $id));
	}
	
	public function update($data) {
		$this->db->update('users', $data, array('id'=>$data['id']));
	}
	
	public function link_gambar($id)
	{
		
		$this->db->where('id',$id);
		$query = $getData = $this->db->get('users');
		if($getData->num_rows() > 0)
			return $query;
		else
			return null;
		
	}
	
}