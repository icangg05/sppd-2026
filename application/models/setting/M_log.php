<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_log extends CI_Model
{
	function __construct() {
		parent::__construct();
	}
	public function data($id) {
		$query  = $this->db->query("SELECT * FROM core_log WHERE id_user='$id'");
		return $query->result();
	}
	
	public function getByUser($id) {
		$query  = $this->db->query("SELECT * FROM core_log WHERE id_user='$id'");
		return $query->result();
	}
	
	public function record_count($id) {
		return $this->db->count_all("core_log WHERE id_user='$id'");
	}
	
	public function record_count_search($column, $data) {
		return $this->db->count_all("core_log WHERE $column like '%$data%'");
	}
	
	public function data_log($limit, $start, $id) {
		$this->db->select('*');
		$this->db->from('core_log');
		$this->db->join('users','core_log.id_user=users.id','LEFT');
		$this->db->join('table_skpd','core_log.id_log=table_skpd.skpd_nama','LEFT');
		$this->db->where('users.id',$id);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function data_search($column, $value, $limit, $start,$id) {
		$this->db->select('*');
		$this->db->from('core_log');
		$this->db->join('users','core_log.id_user=users.id','LEFT');
		$this->db->join('table_skpd','core_log.id_log=table_skpd.skpd_nama','LEFT');
		$this->db->where('users.id',$id);
		$this->db->like($column,$value);
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function create($data) {
        //get data
		$this->kode_log_action = $data['kode_log_action'];
		$this->action = $data['action'];
		$this->kode_log_action_table = $data['kode_log_action_table'];
		$this->action_table = $data['action_table'];
		$this->id_user = $this->ion_auth->user()->row()->id;
		$this->date = date("Y-m-d");
		$this->time = date("h:i:s");
		$this->ip_address = $this->input->ip_address();
        //insert data
		$this->db->insert('core_log', $this);
	}
	
	public function create2($data) {
        //get data
		$this->kode_log_action = $data['kode_log_action'];
		$this->action = $data['action'];
		$this->kode_log_action_table = $data['kode_log_action_table'];
		$this->action_table = $data['action_table'];
		$this->id_user = $data['id_user'];
		$this->date = date("Y-m-d");
		$this->time = date("h:i:s");
		$this->ip_address = $this->input->ip_address();
        //insert data
		$this->db->insert('core_log', $this);
	}
	
	public function create_tte($data) {
        //get data
		$this2->telaah_id = $data['telaah_id'];
		$this2->pegawai_id = $data['pegawai_id'];
		$this2->action = $data['action'];
		$this2->id_user = $this->ion_auth->user()->row()->id;
		$this2->date = date("Y-m-d");
		$this2->time = date("h:i:s");
		$this2->ip_address = $this->input->ip_address();
        //insert data
		$this->db->insert('core_log_tte', $this2);
	}
	
	public function create_tte2($data) {
        //get data
		$this2->telaah_id = $data['telaah_id'];
		$this2->pegawai_id = $data['pegawai_id'];
		$this2->action = $data['action'];
		$this2->id_user = $data['id_user'];
		$this2->date = date("Y-m-d");
		$this2->time = date("h:i:s");
		$this2->ip_address = $this->input->ip_address();
        //insert data
		$this->db->insert('core_log_tte', $this2);
	}
	
	public function update($data) {
        //get data
		$this->kode_log_action = $data['kode_log_action'];
		$this->action = $data['action'];
		$this->kode_log_action_table = $data['kode_log_action_table'];
		$this->action_table = $data['action_table'];
		$this->id_user = $this->ion_auth->user()->row()->id;
		$this->date = date("Y-m-d");
		$this->time = date("h:i:s");
		$this->ip_address = $this->input->ip_address();
        //insert data
		$this->db->update('core_log', $this, array('id_log'=>$data['id_log']));
	}
	
	public function delete($id) {
		$this->db->delete('core_log', array('id_log' => $id));
	}
}