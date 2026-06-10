<?php
class m_utilitas extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	
	public function delete() {
		$this->db->query("DROP TABLE core_log ");
		$this->db->query("DROP TABLE core_menu ");
		$this->db->query("DROP TABLE table_anggaran ");
		$this->db->query("DROP TABLE table_esselon ");
		$this->db->query("DROP TABLE table_golongan ");
		$this->db->query("DROP TABLE table_jabatan ");
		$this->db->query("DROP TABLE table_jenis_skpd ");
		$this->db->query("DROP TABLE table_kabkot ");		
		$this->db->query("DROP TABLE table_kategori ");		
		$this->db->query("DROP TABLE table_lokasi_tujuan ");		
		$this->db->query("DROP TABLE table_pegawai ");		
		$this->db->query("DROP TABLE table_pengikut ");		
		$this->db->query("DROP TABLE table_provinsi ");		
		$this->db->query("DROP TABLE table_rekening ");		
		$this->db->query("DROP TABLE table_relasi_kelurahan ");		
		$this->db->query("DROP TABLE table_rincian_biaya ");		
		$this->db->query("DROP TABLE table_setting ");		
		$this->db->query("DROP TABLE table_skpd ");			
		$this->db->query("DROP TABLE table_timeline1 ");		
		$this->db->query("DROP TABLE table_timeline2 ");		
		$this->db->query("DROP TABLE table_timeline3 ");		
		$this->db->query("DROP TABLE table_timeline4 ");		
		$this->db->query("DROP TABLE table_timeline5 ");		
		$this->db->query("DROP TABLE table_timeline6 ");		
		$this->db->query("DROP TABLE table_timeline7 ");	
		$this->db->query("DROP TABLE users_groups ");		
		$this->db->query("DROP TABLE users ");			
		$this->db->query("DROP TABLE groups ");
		$this->db->query("DROP TABLE table_telaah ");	
		
	}
	
}