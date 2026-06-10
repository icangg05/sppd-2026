<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_relasi_kelurahan extends CI_Model
{
	
	public function create($data) {
		$this->db->insert('table_relasi_kelurahan', $data);
	}
	
	public function update($data) {
		$this->db->update('table_relasi_kelurahan', $data, array('id_kelurahan'=>$data['id_kelurahan']));
	}
	
	public function delete($id_kelurahan) {
		$this->db->delete('table_relasi_kelurahan', array('id_kelurahan' => $id_kelurahan));
	}
	
}