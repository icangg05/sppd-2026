<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Utilitas extends public_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('setting_root/m_utilitas');
		$this->load->model('setting/m_log');
		$this->load->dbforge();
		
		if((!$this->ion_auth->get_users_groups()->row()->id == 9) || (!$this->ion_auth->get_users_groups()->row()->id == 100))
		{
			redirect('login');
		}
	}
	
	// View Content Back Up dan Restore
	public function index(){
		$this->render('setting_root/utilitas/backup');		
	}
	
	// Back Up DB
	public function backupdb(){
        // Load Clas Utilitas Database
		$this->load->dbutil();
		
        // nyiapin aturan untuk file backup
		$aturan = array(    
			'format'      => 'zip',            
			'filename'    => 'db_sppd.sql'
			);
		
		
		$backup =& $this->dbutil->backup($aturan);
		
		$nama_database = 'DB_SPPD ('. date("Y-m-d-H-i-s") .').zip';
		$simpan = '/backup'.$nama_database;
		
		$this->load->helper('file');
		write_file($simpan, $backup);
		
		
		$this->load->helper('download');
		force_download($nama_database, $backup);
	}
	
	// Restore DB
	function restore()	{
		//$this->m_utilitas->delete();
		
		$isi_file = file_get_contents($this->input->post('userfile'));
		$string_query = rtrim( $isi_file, "\n;" );
		$array_query = explode(";", $string_query);
		foreach($array_query as $query){
			$this->db->query($query);
		}
		redirect('setting_root/utilitas');
	}
	
	public function update(){
			
			$seq=1;
			$handle = fopen($_FILES['filename']['tmp_name'], "r"); //Membuka file dan membacanya
			while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
				
			$import = "UPDATE table_telaah SET telaah_tanggalspd = '$data[1]', telaah_tanggalspt = '$data[2]'
					   WHERE telaah_id = '$data[0]'";
				
			$this->db->query($import);
				
				$seq++;
			}
		fclose($handle); //Menutup CSV file
		
		$this->session->set_flashdata('notif','Data CSV Berhasil Disimpan !');
         	
		redirect('pegawai','refresh');
			
		
	}
	
}