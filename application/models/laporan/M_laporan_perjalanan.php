<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_laporan_perjalanan extends CI_Model {
  
  public function get_laporan($telaah_id){
    $this->db->select('*');
    $this->db->from('table_laporanperjalanan');
    $this->db->where('telaah_id',$telaah_id);
    $query = $this->db->get ();
    return $query->result();
  }
  
  public function get_data($telaah_id){
    $this->db->select('*');
    $this->db->from('table_laporanperjalanan');
    $this->db->where('telaah_id',$telaah_id);
    $query = $this->db->get ();
    return $query->result_array();
  }
  
  public function get($laporanperjalanan_id) {
    $this->db->select('*');
    $this->db->from('table_laporanperjalanan');
    $this->db->where('laporanperjalanan_id',$laporanperjalanan_id);
    $query = $this->db->get ();
    return $query->result_array();
  }
  public function create($data) {
    
    $this->db->insert('table_laporanperjalanan', $data);
  }
  
  public function update($data) {
    $this->db->update('table_laporanperjalanan', $data, array(
      'laporanperjalanan_id' => $data['laporanperjalanan_id']
      ));
  }
  
  public function delete($laporanperjalanan_id) {
    $this->db->delete('table_laporanperjalanan', array(
      'laporanperjalanan_id' => $laporanperjalanan_id
      ));
  }
  
  public function link_gambar($laporanperjalanan_id) {
    
    $this->db->where('laporanperjalanan_id', $laporanperjalanan_id);
    $query = $getData = $this->db->get('table_laporanperjalanan');
    
    if ($getData->num_rows() > 0)
      return $query;
    else
      return null;
    
  }
  
}