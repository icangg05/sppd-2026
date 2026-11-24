<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_user extends CI_Model
{
	
	// $grup > 0: hanya akun dengan role tersebut (filter role di daftar user super admin)
	public function record_count($grup = 0) {
		if ((int) $grup <= 0) {
			return $this->db->count_all('users');
		}
		return $this->db->from('users')
			->join('users_groups', 'users.id = users_groups.user_id')
			->where('users_groups.group_id', (int) $grup)
			->count_all_results();
	}
	
	public function record_count_search($column, $data, $grup = 0) {
		if ((int) $grup <= 0) {
			return $this->db->count_all("users WHERE $column like '%".$this->db->escape_str((string) $data)."%'");
		}
		return $this->db->from('users')
			->join('users_groups', 'users.id = users_groups.user_id')
			->where('users_groups.group_id', (int) $grup)
			->like($column, (string) $data)
			->count_all_results();
	}

	// Jumlah akun per role (semua & aktif) untuk pilihan filter
	public function rekap_grup() {
		return $this->db->select('groups.id, groups.name, COUNT(users.id) AS total, SUM(users.active = 1) AS aktif', FALSE)
			->from('groups')
			->join('users_groups', 'users_groups.group_id = groups.id', 'left')
			->join('users', 'users.id = users_groups.user_id', 'left')
			->group_by('groups.id, groups.name')
			->order_by('groups.id')
			->get()->result();
	}
	
	public function data($limit, $start, $grup = 0) {
		$this->db->select('users.*, groups.id as group_id, groups.bgcolor, groups.name, skpd_nama');
		$this->db->from('users');
		$this->db->join('users_groups', 'users.id = users_groups.user_id' );
		$this->db->join('groups', 'users_groups.group_id = groups.id' );
		$this->db->join('table_skpd', 'users.skpd_id = table_skpd.skpd_id','LEFT' );
		if ((int) $grup > 0) {
			$this->db->where('users_groups.group_id', (int) $grup);
		}
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search($column, $value, $limit, $start, $grup = 0) {
		$this->db->select('users.*, groups.id as group_id, groups.bgcolor, groups.name, skpd_nama');
		$this->db->from('users');
		$this->db->join('users_groups', 'users.id = users_groups.user_id' );
		$this->db->join('groups', 'users_groups.group_id = groups.id' );
		$this->db->join('table_skpd', 'users.skpd_id = table_skpd.skpd_id','LEFT' );
		$this->db->like($column,$value);
		if ((int) $grup > 0) {
			$this->db->where('users_groups.group_id', (int) $grup);
		}
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	// Daftar user untuk admin OPD (SKPD sendiri): akun super admin tidak ditampilkan
	public function record_count2($skpd_id) {
		return $this->db->count_all("users WHERE skpd_id='".$this->db->escape_str((string) $skpd_id)."' AND id <> 1 AND id NOT IN (SELECT user_id FROM users_groups WHERE group_id = 100)");
	}
	
	public function record_count_search2($column, $data, $skpd_id) {
		return $this->db->count_all("users WHERE skpd_id='".$this->db->escape_str((string) $skpd_id)."' AND id <> 1 AND id NOT IN (SELECT user_id FROM users_groups WHERE group_id = 100) AND $column like '%".$this->db->escape_str((string) $data)."%'");
	}
	
	public function data2($limit, $start, $skpd_id) {
		$this->db->select('users.*, groups.id as group_id, groups.bgcolor, groups.name, skpd_nama');
		$this->db->from('users');
		$this->db->join('users_groups', 'users.id = users_groups.user_id' );
		$this->db->join('groups', 'users_groups.group_id = groups.id' );
		$this->db->join('table_skpd', 'users.skpd_id = table_skpd.skpd_id','LEFT' );
		$this->db->where('users.skpd_id',$skpd_id);
		$this->db->where('users.id <>', 1);
		$this->db->where('users.id NOT IN (SELECT user_id FROM users_groups WHERE group_id = 100)', NULL, FALSE);
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
		$this->db->where('users.id <>', 1);
		$this->db->where('users.id NOT IN (SELECT user_id FROM users_groups WHERE group_id = 100)', NULL, FALSE);
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	
	public function record_count_setda($skpd_id, $bagian_id) {
		return $this->db->count_all("users 
									LEFT JOIN users_groups_bagian 
									ON users_groups_bagian.user_id = users.id
									WHERE skpd_id='".$this->db->escape_str((string) $skpd_id)."'
									AND bagian_id='".$this->db->escape_str((string) $bagian_id)."'");
	}
	
	public function record_count_search_setda($column, $data, $skpd_id, $bagian_id) {
		return $this->db->count_all("users 
									LEFT JOIN users_groups_bagian 
									ON users_groups_bagian.user_id = users.id
									WHERE skpd_id='".$this->db->escape_str((string) $skpd_id)."' 
									AND bagian_id='".$this->db->escape_str((string) $bagian_id)."'
									AND $column like '%".$this->db->escape_str((string) $data)."%'");
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