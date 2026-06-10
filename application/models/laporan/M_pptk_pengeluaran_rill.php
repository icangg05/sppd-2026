<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_pptk_pengeluaran_rill extends CI_Model
{	
	
	/// TANGGAL PERJALANAN
	public function data($telaah_id)
	{
		$this->db->select('*');
		$this->db->from('table_pptk_perjalanan');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id = table_pptk_perjalanan.pegawai_id', 'LEFT');
		$this->db->where('table_pptk_perjalanan.telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get($pptk_perjalanan_id) {
		$this->db->select('*');
		$this->db->from('table_pptk_perjalanan');
		$this->db->where('pptk_perjalanan_id',$pptk_perjalanan_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function get2($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_pptk_perjalanan');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id = table_pptk_perjalanan.pegawai_id', 'LEFT');
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function create($data) {
		
        $this->db->insert('table_pptk_perjalanan', $data);
    }
	
	public function update($data) {
        $this->db->update('table_pptk_perjalanan', $data, array('pptk_perjalanan_id'=>$data['pptk_perjalanan_id']));
    }
	
	public function delete($pptk_perjalanan_id) {
        $this->db->delete('table_pptk_perjalanan', array('pptk_perjalanan_id' => $pptk_perjalanan_id));
    }
	
}