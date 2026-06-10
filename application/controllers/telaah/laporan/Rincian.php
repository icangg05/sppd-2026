<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Rincian extends public_Controller {
    function __construct()
    {
        parent::__construct();
		error_reporting(0);
		$this->load->model('laporan/m_rincian');
		$this->load->model('telaah/m_telaah');
		$this->load->model('setting_admin/m_pegawai');
		$this->load->model('setting/m_log');
    }
	
	//View All Data
	public function index()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		switch($this->uri->segment(5)){
			case "esselon" 		: 	
			case "puskesmas" 	: 	
			case "sekda" 		: 	
			case "staff_setda" 	: 	
			case "camat" 		: 	
			case "staff_camat" 	: 	
			case "lurah" 		: 	
			case "staff_lurah" 	: 	
			case "kapus" 		:	
			case "staff_dprd" 	:	
			case "sekwan" 		:	
			case "kadis" 		: 	
									$this->data['data'] = $this->m_telaah->get($telaah_id);
									$this->data['tanda_tangan_pptk'] = $this->m_pegawai->get($this->data['data'][0]['telaah_ttdpptk']);
									break;
			case "dprd" 		: 	$this->data['data'] = $this->m_telaah->get_dprd($telaah_id);
									$this->data['tanda_tangan_pptk'] = $this->m_pegawai->get($this->data['data'][0]['telaah_ttdpptk']);
									break;
			case "walikota" 	: 	$this->data['data'] = $this->m_telaah->getWalikota($telaah_id);
									$this->data['tanda_tangan_pptk'] = $this->m_pegawai->get($this->data['data'][0]['telaah_ttdpptk']);
									break;
		}
		
		$this->data['t'] = $this->m_rincian->getTelaahKategori($telaah_id);
		$this->data['t'] =$this->data['t'][0];
		$x = $this->m_rincian->getTelaahKategori($telaah_id);
		foreach($x as $f) {
			$telaah_kategori = $f->telaah_kategori;
		}
		
		if($telaah_kategori == 8) {
			$this->data['pelaksana'] = $this->m_rincian->pelaksanaWalikota($telaah_id);
			$this->data['rincian_pelaksana'] = $this->m_rincian->get_rincianpelaksana($telaah_id,$this->data['pelaksana'][0]['pegawai_id']);
		} else if($telaah_kategori == 3){
			$this->data['pelaksana'] = $this->m_rincian->pelaksanadprd($telaah_id);	
			$this->data['rincian_pelaksana'] = $this->m_rincian->get_rincian_dprd($telaah_id,$this->data['pelaksana'][0]['anggotadprd_id']);
		}  else {
			
			$this->data['pelaksana'] = $this->m_rincian->pelaksana($telaah_id);
			$this->data['rincian_pelaksana'] = $this->m_rincian->get_rincian($telaah_id,$this->data['pelaksana'][0]['pegawai_id']);
		}
		
		if($telaah_kategori == 3) {
			$this->data['pengikut'] = $this->m_rincian->pengikutdprd($telaah_id);
		}else{
			$this->data['pengikut'] = $this->m_rincian->pengikut($telaah_id);
		}
		$this->data['jumlah_pengikut'] = count($this->data['pengikut']);
		$this->render('laporan/rincian/content');
	}
	
	//View Create Data
    public function create_view()
    {
		$this->data['telaah_id'] = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$this->data['pegawai_id']= $this->input->get('pegawai_id');
		$this->data['posisi']= $this->uri->segment(5);
        $this->render('laporan/rincian/insert');
	}
	
	//Create Data
	public function create()
	{
		$this->form_validation->set_rules('kategori_biaya', 'Kategori Biaya', 'required');
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
		$this->form_validation->set_rules('item', 'Item', 'required');
		$this->form_validation->set_rules('tarif', 'Tarif', 'required');
		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->create_view();
		} 
		else 
		{	
			$filename = $this->input->post('telaah_id').$this->input->post('pegawai_id');
			$config['upload_path'] = './upload/bukti/';
			$config['allowed_types'] = "gif|jpg|jpeg|png";
			$config['overwrite']="true";
			$config['max_size']="20000000";
			$config['max_width']="10000";
			$config['max_height']="10000";
			$config['file_name'] = ''.$filename;
			$this->upload->initialize($config);
			if(!$this->upload->do_upload()){
				$telaah_id = base64_encode($this->encrypt->encode($this->input->post('telaah_id'), $this->session->userdata('encrypt_key')));	
				$data['telaah_id'] = $this->input->post('telaah_id');
				$data['pegawai_id'] = $this->input->post('pegawai_id');
				$data['kategori_biaya'] = $this->input->post('kategori_biaya');
				$data['nama_maspakai'] = $this->input->post('nama_maspakai');
				$data['no_tiket'] = $this->input->post('no_tiket');
				$data['keterangan'] = $this->input->post('keterangan');
				$data['item'] = str_replace(".", "", $this->input->post('item'));
				$data['tarif'] = str_replace(".", "", $this->input->post('tarif'));
				
				$this->m_rincian->create($data);
				
				$log['kode_log_action'] = "53";
				$log['action'] = "INSERT";
				$log['kode_log_action_table'] = "12";
				$log['action_table'] = "TABLE RINCIAN BIAYA";
				$this->m_log->create($log);
				
				$this->session->set_flashdata('notif','Data Pegawai Di Simpan !');
				redirect('telaah/laporan/rincian/index/'.$this->input->post('posisi').'?telaah_id='.$telaah_id);
			}else {
				
				$dat = $this->upload->data();
				
				$telaah_id = base64_encode($this->encrypt->encode($this->input->post('telaah_id'), $this->session->userdata('encrypt_key')));	
				$data['telaah_id'] = $this->input->post('telaah_id');
				$data['pegawai_id'] = $this->input->post('pegawai_id');
				$data['kategori_biaya'] = $this->input->post('kategori_biaya');
				$data['nama_maspakai'] = $this->input->post('nama_maspakai');
				$data['no_tiket'] = $this->input->post('no_tiket');
				$data['keterangan'] = $this->input->post('keterangan');
				$data['item'] = str_replace(".", "", $this->input->post('item'));
				$data['tarif'] = str_replace(".", "", $this->input->post('tarif'));
		        	$data['foto'] = $dat['file_name'];
				
				$this->m_rincian->create($data);
				
				$log['kode_log_action'] = "53";
				$log['action'] = "INSERT";
				$log['kode_log_action_table'] = "12";
				$log['action_table'] = "TABLE RINCIAN BIAYA";
				$this->m_log->create($log);
				
				$this->session->set_flashdata('notif','Data Pegawai Di Simpan !');
				redirect('telaah/laporan/rincian/index/'.$this->input->post('posisi').'?telaah_id='.$telaah_id);
				
			}
		
		}
	}
	
	//View Update Data
	public function update_view()
	{
		$rincian_biaya_id = $this->encrypt->decode(base64_decode($this->input->get('rincian_biaya_id')), $this->session->userdata('encrypt_key'));
		
		$this->data['entry'] =  $this->m_rincian->get($rincian_biaya_id);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('rincian');
		} else {
			$this->data['posisi']= $this->uri->segment(5);
			$this->render('laporan/rincian/update');
		}
	}
	//Update Data
	public function update()
	{
		$this->form_validation->set_rules('kategori_biaya', 'Kategori Biaya', 'required');
		$this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
		$this->form_validation->set_rules('item', 'Item', 'required');
		$this->form_validation->set_rules('tarif', 'Tarif', 'required');
		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->data['entry'] =  $this->m_rincian->get($this->input->post('rincian_biaya_id'));
			if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
				redirect('rincian');
			} else {
				$this->data['posisi']= $this->input->post('posisi');
				$this->render('laporan/rincian/update');
			}
		} 
		else 
		{		
			if($_FILES['userfile']['name']==''){
			
				$telaah_id = base64_encode($this->encrypt->encode($this->input->post('telaah_id'), $this->session->userdata('encrypt_key')));	
				$data['rincian_biaya_id'] = $this->input->post('rincian_biaya_id');
				$data['telaah_id'] = $this->input->post('telaah_id');
				$data['pegawai_id'] = $this->input->post('pegawai_id');
				$data['kategori_biaya'] = $this->input->post('kategori_biaya');
				$data['nama_maspakai'] = $this->input->post('nama_maspakai');
				$data['no_tiket'] = $this->input->post('no_tiket');
				$data['keterangan'] = $this->input->post('keterangan');
				$data['item'] = str_replace(".", "", $this->input->post('item'));
				$data['tarif'] = str_replace(".", "", $this->input->post('tarif'));
				
				$this->m_rincian->update($data);		
				
				$log['kode_log_action'] = "54";
				$log['action'] = "UPDATE rincian_biaya_id = ".$this->input->post('rincian_biaya_id');
				$log['kode_log_action_table'] = "12";
				$log['action_table'] = "TABLE RINCIAN BIAYA";
				$this->m_log->create($log);	
				
				$this->session->set_flashdata('notif','Data Rincian Di Ubah !');
				redirect('telaah/laporan/rincian/index/'.$this->input->post('posisi').'?telaah_id='.$telaah_id);
			
			} else {
			
				$telaah_id = base64_encode($this->encrypt->encode($this->input->post('telaah_id'), $this->session->userdata('encrypt_key')));	
					
				$filename = $this->input->post('telaah_id').$this->input->post('pegawai_id');
				$config['upload_path'] = './upload/bukti/';
				$config['allowed_types'] = "gif|jpg|jpeg|png";
				$config['overwrite']="true";
				$config['max_size']="20000000";
				$config['max_width']="10000";
				$config['max_height']="10000";
				$config['file_name'] = ''.$filename;
				$this->upload->initialize($config);
				if(!$this->upload->do_upload()){
					echo  $this->upload->display_errors();
				}else {
					
					$image = $this->m_rincian->link_gambar($this->input->post('rincian_biaya_id'));
					if ($image->num_rows() > 0)
					{
						$row = $image->row();			
						$file_gambar = $row->foto;
						$path_file = './upload/bukti/';
						unlink($path_file.$file_gambar);
					}					
				
					$dat = $this->upload->data();
					
					$data['rincian_biaya_id'] = $this->input->post('rincian_biaya_id');
					$data['telaah_id'] = $this->input->post('telaah_id');
					$data['pegawai_id'] = $this->input->post('pegawai_id');
					$data['kategori_biaya'] = $this->input->post('kategori_biaya');
					$data['nama_maspakai'] = $this->input->post('nama_maspakai');
					$data['no_tiket'] = $this->input->post('no_tiket');
					$data['keterangan'] = $this->input->post('keterangan');
					$data['item'] = str_replace(".", "", $this->input->post('item'));
					$data['tarif'] = str_replace(".", "", $this->input->post('tarif'));
					$data['foto'] = $dat['file_name'];
					
					$this->m_rincian->update($data);		
					
					$log['kode_log_action'] = "54";
					$log['action'] = "UPDATE rincian_biaya_id = ".$this->input->post('rincian_biaya_id');
					$log['kode_log_action_table'] = "12";
					$log['action_table'] = "TABLE RINCIAN BIAYA";
					$this->m_log->create($log);	
					
					$this->session->set_flashdata('notif','Data Rincian Di Ubah !');
					redirect('telaah/laporan/rincian/index/'.$this->input->post('posisi').'?telaah_id='.$telaah_id);
				}
			}
			
		}
	}
	
	//Delete Data
	public function delete() 
	{
		$rincian_biaya_id = $this->encrypt->decode(base64_decode($this->input->get('rincian_biaya_id')), $this->session->userdata('encrypt_key'));
		$telaah_id = base64_encode($this->encrypt->encode($this->input->get('telaah_id'), $this->session->userdata('encrypt_key')));	
		
		$image = $this->m_rincian->link_gambar($rincian_biaya_id);
		if ($image->num_rows() > 0)
		{
			$row = $image->row();			
			$file_gambar = $row->foto;
			$path_file = './upload/bukti/';
			unlink($path_file.$file_gambar);
		}			
		
        $this->m_rincian->delete($rincian_biaya_id);
		
		$log['kode_log_action'] = "";
		$log['action'] = "HAPUS rincian_id = ".$rincian_biaya_id;
		$log['kode_log_action_table'] = "12";
		$log['action_table'] = "TABLE RINCIAN BIAYA";
		$this->m_log->create($log);	
					
		$this->session->set_flashdata('notif','Data Rincian Di Hapus !');
		redirect('telaah/laporan/rincian/index/'.$this->uri->segment(5).'?telaah_id='.$telaah_id);
    }
}