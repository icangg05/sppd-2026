<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_widget extends CI_Model
{
	
	public function getRekap() {
    $query  = $this->db->query("SELECT (SELECT count(telaah_id) FROM table_timeline2) as timeline2, (SELECT count(telaah_id) FROM table_timeline4) as timeline4, (SELECT count(telaah_id) FROM table_timeline5) as timeline5, (SELECT count(telaah_id) FROM table_timeline8) as timeline8 ");
    return $query->result();
  }


  /*Data Rekap by Timeline*/
  public function getDetailRekap($timeline){
  	
  	if($timeline==2){
  		$query  = $this->db->query("SELECT * FROM table_timeline2 a 
  																		LEFT JOIN table_telaah 	b ON a.telaah_id					= b.telaah_id
  																		LEFT JOIN table_pegawai c ON b.telaah_pelaksana		=	c.pegawai_id
  																		LEFT JOIN table_skpd 		d ON d.skpd_id						=	c.skpd_id

  															");
  	}elseif($timeline==4){
  		$query  = $this->db->query("SELECT * FROM table_timeline4 a 
  																		LEFT JOIN table_telaah 	b ON a.telaah_id					= b.telaah_id
  																		LEFT JOIN table_pegawai c ON b.telaah_pelaksana		=	c.pegawai_id
  																		LEFT JOIN table_skpd 		d ON d.skpd_id						=	c.skpd_id

  															");
  	}elseif($timeline==5){
  		$query  = $this->db->query("SELECT * FROM table_timeline5 a 
  																		LEFT JOIN table_telaah 	b ON a.telaah_id					= b.telaah_id
  																		LEFT JOIN table_pegawai c ON b.telaah_pelaksana		=	c.pegawai_id
  																		LEFT JOIN table_skpd 		d ON d.skpd_id						=	c.skpd_id

  															");
  	}elseif($timeline==8){
  		$query  = $this->db->query("SELECT * FROM table_timeline8 a 
  																		LEFT JOIN table_telaah 		b ON a.telaah_id					= b.telaah_id
  																		LEFT JOIN table_pimpinan 	c ON b.telaah_pelaksana		=	c.pegawai_id
  																		LEFT JOIN table_skpd 			d ON d.skpd_id						=	c.skpd_id
  															");
  	}
  	

    
    return $query->result();

  }
	


	public function getSKPD() {
    $query  = $this->db->query("SELECT skpd_id, skpd_nama FROM table_skpd");
    return $query->result();
  }


  public function getDetailSKPD($idSKPD){
  	if($idSKPD==2){
  		/*DPRD*/
  		$query  = $this->db->query("SELECT a.*, c.anggotadprd_name as pegawai_nama, c.anggotadprd_jabatan as pegawai_namajabatan, 'SEKRETARIAT DPRD' as skpd_nama FROM table_telaah a
  																					JOIN table_timeline3 b 		ON a.telaah_id				=	b.telaah_id
  																					JOIN table_anggotadprd c 	ON a.telaah_pelaksana	=	c.anggotadprd_id
  															");
  	}else{
  		/*SKPD LAIN*/
  		$query  = $this->db->query("SELECT * FROM table_telaah a
  																		LEFT JOIN table_pegawai 	b ON a.telaah_pelaksana		=	b.pegawai_id
  																		LEFT JOIN table_skpd 			c ON b.skpd_id						=	c.skpd_id
  																		WHERE b.skpd_id='$idSKPD'
  															");
  	}

  	return $query->result();
  }
	
	
}