<?php
defined('BASEPATH') OR exit('No direct script access allowed');

  
require_once APPPATH.'vendor/autoload.php';
use PhpOffice\PhpWord\PhpWord;
class Laporan extends public_Controller {
  
  function __construct() {
    parent::__construct();
		error_reporting(0);
		$this->load->model('laporan/m_laporan_perjalanan');
		$this->load->model('laporan/m_laporan');
		$this->load->model('setting/m_log');
		$this->load->model('telaah/m_telaah');
		$this->load->model('telaah/m_lokasi_tujuan');
		$this->load->model('setting_admin/m_pegawai');
		if(!($this->ion_auth->user()->row()->id)){
			redirect('login');
		}
  }
  
	//View All Data
	public function index() 
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$this->data['laporan_perjalanan'] = $this->m_laporan_perjalanan->get_laporan($telaah_id);
		$this->render('laporan/laporan_perjalanan/content');
	}
  
	//View Create Data
	public function create_view() 
	{
		$this->data['telaah_id']  = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$this->data['posisi']= $this->input->get('posisi');
		$this->render('laporan/laporan_perjalanan/insert');
	}
  
	//Create Data
  public function create() {
    $this->form_validation->set_rules('laporanperjalanan_desc', 'Isi Laporan', 'required');
    if($this->form_validation->run() == FALSE) {
      $this->data['telaah_id']  = $this->input->post('telaah_id');
      $this->data['posisi']= $this->input->post('posisi');
	  $this->render('laporan/laporan_perjalanan/insert');
    } else {
      $filename                = $this->input->post('telaah_id').'-'.date('YmdHis').rand(1,999);
      $config['upload_path']   = './upload/laporan_perjalanan/';
      $config['allowed_types'] = "gif|jpg|jpeg|png|pdf|doc|docx|xls|xlsx";
      $config['overwrite']     = "true";
      $config['max_size']      = "20000000";
      $config['max_width']     = "10000";
      $config['max_height']    = "10000";
      $config['file_name']     = '' . $filename;
      $this->upload->initialize($config);
      
      if (!$this->upload->do_upload()) {
        echo $this->upload->display_errors();
        
      } else {
        
        $dat = $this->upload->data();
        
        $telaah_id              = base64_encode($this->encrypt->encode($this->input->post('telaah_id'), $this->session->userdata('encrypt_key')));
        $data['laporanperjalanan_id']     = "";
        $data['laporanperjalanan_desc']   = $this->input->post('laporanperjalanan_desc');
        $data['laporanperjalanan_date']   = $this->input->post('laporanperjalanan_date');
        $data['telaah_id']                = $this->input->post('telaah_id');
        $data['laporanperjalanan_file']   = $dat['file_name'];
        
        $this->m_laporan_perjalanan->create($data);
		
		
		$pelaksana = $this->m_laporan->get_pelaksana_opd($this->input->post('telaah_id'));
		
		$data2['pegawai_id'] = $pelaksana[0]['pegawai_id'];
		$data2['status'] = 0;
		$this->m_pegawai->update($data2);
		
		$pengikut = $this->m_laporan->get_pengikut($this->input->post('telaah_id'));
		$jml_pengikut = count($pengikut);
			for($i=0;$i<$jml_pengikut;$i++){
				$data3['pegawai_id'] = $pengikut[$i]['pegawai_id'];
				$data3['status'] = 0;
				$this->m_pegawai->update($data3);
			}
        
        # 127 untuk kode log action Laporan Perjalanan
        # 133 untuk kode log action table Laporan Perjalanan 
        $log['kode_log_action']       = "127";
        $log['action']                = "INSERT";
        $log['kode_log_action_table'] = "133";
        $log['action_table']          = "TABLE LAPORAN PERJALANAN";
        $this->m_log->create($log);
        
        $this->session->set_flashdata('notif', 'Data Laporan Perjalanan Di Simpan !');
        redirect('telaah/laporan/laporan_perjalanan/laporan?telaah_id=' . $telaah_id.'&&posisi='.$this->input->post('posisi'));
        
      }
    }
  }
  
	//View Update Data
	public function update_view() 
	{
		$laporanperjalanan_id = $this->encrypt->decode(base64_decode($this->input->get('laporanperjalanan_id')), $this->session->userdata('encrypt_key'));
		$this->data['entry'] = $this->m_laporan_perjalanan->get($laporanperjalanan_id);
		
		if (!isset($this->data['entry'][0]) || $this->data['entry'][0] == "") {
			redirect('laporan_perjalanan/laporan');
		} else {
			$this->data['posisi']= $this->input->get('posisi');
			$this->render('laporan/laporan_perjalanan/update');
		}
	}
 
	//Update Data
	public function update() 
	{
		$this->form_validation->set_rules('laporanperjalanan_desc', 'Isi Laporan', 'required');
  
		if ($this->form_validation->run() == FALSE) {
			
			$this->data['entry'] = $this->m_laporan_perjalanan->get($this->input->post('laporanperjalanan_id'));
			if (!isset($this->data['entry'][0]) || $this->data['entry'][0] == "") {
				redirect('rincian');
			} else {
				$this->data['posisi']= $this->input->post('posisi');
				$this->render('laporan/laporan_perjalanan/update');
			}
			
		} else {
			$telaah_id = base64_encode($this->encrypt->encode($this->input->post('telaah_id'), $this->session->userdata('encrypt_key')));
      
			$data['laporanperjalanan_id']     = $this->input->post('laporanperjalanan_id');
			$data['laporanperjalanan_desc']   = $this->input->post('laporanperjalanan_desc');
			$data['laporanperjalanan_date']   = $this->input->post('laporanperjalanan_date');
			$data['telaah_id']                = $this->input->post('telaah_id');
          
			$this->m_laporan_perjalanan->update($data);
          
			# 127 untuk kode log action Laporan Perjalanan
			# 133 untuk kode log action table Laporan Perjalanan 
			$log['kode_log_action']       = "127";
			$log['action']                = "UPDATE";
			$log['kode_log_action_table'] = "133";
			$log['action_table']          = "TABLE LAPORAN PERJALANAN";
			$this->m_log->create($log);
          
			$this->session->set_flashdata('notif', 'Data Laporan Perjalanan Ubah !');
			redirect('telaah/laporan/laporan_perjalanan/laporan?telaah_id=' . $telaah_id.'&&posisi='.$this->input->post('posisi'));
        }
      }
      
    
  
	//Delete Data
	public function delete() 
	{
		$laporanperjalanan_id = $this->encrypt->decode(base64_decode($this->input->get('laporanperjalanan_id')), $this->session->userdata('encrypt_key'));
		$telaah_id        = base64_encode($this->encrypt->encode($this->input->get('telaah_id'), $this->session->userdata('encrypt_key')));
    
		$path_file   = './upload/laporan_perjalanan/';
		unlink($path_file . $this->input->get('file'));
    
		$this->m_laporan_perjalanan->delete($laporanperjalanan_id);
    
		$log['kode_log_action']       = "127";
		$log['action']                = "HAPUS laporanperjalanan_id = " . $laporanperjalanan_id;
		$log['kode_log_action_table'] = "133";
		$log['action_table']          = "TABLE LAPORAN PERJALANAN";
		$this->m_log->create($log);
    
		$this->session->set_flashdata('notif', 'Data Laporan Perjalanan Di Hapus !');
		redirect('telaah/laporan/laporan_perjalanan/laporan?telaah_id=' . $telaah_id.'&&posisi='.$this->input->get('posisi'));
	}
  
	public function penyebut($nilai) 
	{
		$nilai = abs($nilai);
		$huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
		$temp = "";
		if ($nilai < 12) {
			$temp = " ". $huruf[$nilai];
		} else if ($nilai <20) {
			$temp = $this->penyebut($nilai - 10). " Belas";
		} else if ($nilai < 100) {
			$temp = $this->penyebut($nilai/10)." Puluh". $this->penyebut($nilai % 10);
		} else if ($nilai < 200) {
			$temp = " Seratus" . $this->penyebut($nilai - 100);
		} else if ($nilai < 1000) {
			$temp = $this->penyebut($nilai/100) . " Ratus" . $this->penyebut($nilai % 100);
		} else if ($nilai < 2000) {
			$temp = " Seribu" . $this->penyebut($nilai - 1000);
		} else if ($nilai < 1000000) {
			$temp = $this->penyebut($nilai/1000) . " Ribu" . $this->penyebut($nilai % 1000);
		} else if ($nilai < 1000000000) {
			$temp = $this->penyebut($nilai/1000000) . " Juta" . $this->penyebut($nilai % 1000000);
		} else if ($nilai < 1000000000000) {
			$temp = $this->penyebut($nilai/1000000000) . " Milyar" . $this->penyebut(fmod($nilai,1000000000));
		} else if ($nilai < 1000000000000000) {
			$temp = $this->penyebut($nilai/1000000000000) . " Trilyun" . $this->penyebut(fmod($nilai,1000000000000));
		}
		return $temp;
	}

	public function cetak(){
		 //library
		$phpWord = new \PhpOffice\PhpWord\PhpWord();

		//Panggilan Database
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$data = $this->m_telaah->get($telaah_id);
		$data2 = $this->m_laporan_perjalanan->get_data($telaah_id);
		$lokasi_tujuan = $this->m_lokasi_tujuan->get2($telaah_id);
		
		$tanda_tangan = $this->m_laporan->tanda_tangan($data[0]['telaah_ttdspd']);
		
		//our docx will have 'lanscape' paper orientation
		$section = $phpWord->createSection(array('orientation'=>'portrait'));
		
		// Add image elements
		if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']!=11){
			$dinas_kesehatan = $this->m_laporan->get_dinas_kesehatan();
			$section->addImage(FCPATH.'./upload/kop_surat/'.$dinas_kesehatan[0]['kop_surat'],array(
				'width'         => 450,
				'height'        => 80,
				'marginTop'     => 0,
				'marginLeft'    => 0,
				'wrappingStyle' => 'behind'
			));
		} else {
			
			$section->addImage(FCPATH.'./upload/kop_surat/'.$data[0]['kop_surat'],array(
				'width'         => 450,
				'height'        => 80,
				'marginTop'     => 0,
				'marginLeft'    => 0,
				'wrappingStyle' => 'behind'
			));
		}
	
		// Add text elements
		$phpWord->addFontStyle('aStyle', array('bold'=>true, 'size'=>14, 'name'=>'Times New Roman'));
		$phpWord->addParagraphStyle('bStyle', array('align'=>'center'));
		$section->addText('LAPORAN PERJALANAN DINAS','aStyle','bStyle');
		$section->addTextBreak(1);
		
		
		$phpWord->addFontStyle('cStyle', array('size'=>12, 'name'=>'Times New Roman'));
		$phpWord->addParagraphStyle('dStyle', array('align'=>'both'));
		$section->addText('Kepada Yth		: '.$tanda_tangan[0]['pegawai_namajabatan'],'cStyle','dStyle');
		$section->addTextBreak(1);
		
		$section->addText('Dasar Perjalanan	: '.$data[0]['telaah_perihal'],'cStyle','dStyle');
		$section->addTextBreak(1);
		
		$section->addText('Laporan Perjalanan Dinas	: ','cStyle');
		\PhpOffice\PhpWord\Shared\Html::addHtml($section, $data2[0]['laporanperjalanan_desc'], false, false);
		$section->addTextBreak(1);
		
		$section->addText('Demikian disampaikan Laporan Hasil Perjalanan Dinas '.$data[0]['telaah_perihal'].', '.$data[0]['telaah_kantortujuan'].' selama '.$data[0]['telaah_hari']. ' hari dari tanggal '.date("d-m-Y", strtotime($data[0]['telaah_tanggalberangkat'] )). ' sampai dengan '.date("d-m-Y", strtotime($data[0]['telaah_tanggalkembali'] )).'.' ,'cStyle','dStyle');
		$section->addTextBreak(1);
		$section->addTextBreak(1);
		
		$section->addText('Kendari, '.date("d-m-Y", strtotime($data2[0]['laporanperjalanan_date'] )),'cStyle');
		$section->addText('Yang Melaksanakan Tugas, ','cStyle','dStyle');
		$section->addTextBreak(1);
		$section->addTextBreak(1);
		$section->addTextBreak(1);
		
		$section->addText($data[0]['pegawai_nama'],'cStyle','dStyle');
		$section->addText($data[0]['pegawai_nip'],'cStyle','dStyle');
		$section->addText($data[0]['pegawai_namajabatan'],'cStyle','dStyle');
		
		
		$file='Perjalanan Dinas - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y", strtotime($data[0]['telaah_tanggalspd'])).'.docx'; //save our document as this file name
		header("Content-Description: File Transfer");
		header('Content-Disposition: attachment; filename="' . $file . '"');
		header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Expires: 0');
		$xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
		$xmlWriter->save("php://output");
		
	}
  
}