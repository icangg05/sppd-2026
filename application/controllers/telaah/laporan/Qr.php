<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Qr extends qr_Controller {
	function __construct()
	{
		parent::__construct();
		error_reporting(0);
		$this->load->model('telaah/m_esselon');
		$this->load->model('telaah/m_dprd');
		$this->load->model('telaah/m_sekda');
		$this->load->model('telaah/m_pengikut');
		$this->load->model('telaah/m_lokasi_tujuan');
		$this->load->model('telaah/m_timeline');
		$this->load->model('telaah/m_telaah');
		$this->load->model('telaah/m_tte');
		$this->load->model('laporan/m_laporan');
		$this->load->model('laporan/m_spd');
		$this->load->model('laporan/m_rincian');
		$this->load->model('laporan/m_pengeluaran_rill');
		$this->load->model('laporan/m_pptk_pengeluaran_rill');
		$this->load->model('laporan/m_kuitansi');
		$this->load->model('telaah/m_relasi_sekda');
		$this->load->model('telaah/m_disposisi');
		$this->load->model('setting_admin/m_pegawai');
		$this->load->model('setting/m_log');
		
	}
	
	//View Detail Data
	public function sppd()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		$data['entry'] =  $this->m_esselon->get($telaah_id);
		if(!isset($data['entry'][0]) || $data['entry'][0] == ""){
			redirect('qr');
		} else {
			if($data['entry'][0]['telaah_kategori']==3){
				$data['data'] =  $this->m_dprd->get($telaah_id);
			} else if ($data['entry'][0]['telaah_kategori']==8){
				$data['data'] = $this->m_sekda->getWalikota($telaah_id);
			} else {
				$data['data'] = $this->m_esselon->get($telaah_id);
			}
			
			$data['pengikut'] =  $this->m_pengikut->data($telaah_id);
			$data['pengikut2'] =  $this->m_pengikut->data2($telaah_id);
			$this->load->view('templates/_parts/header');
			$this->load->view('list_telaah/qr', $data);
			//$this->load->view('templates/_parts/footer');
		}
		
		
	}
	
	public function penyebut($nilai) {
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

	public function terbilang($nilai) {
		if($nilai<0) {
			$hasil = "minus ". trim($this->penyebut($nilai));
		} else {
			$hasil = trim($this->penyebut($nilai));
		}
		return $hasil;
	}
	
	function tgl_indo($tanggal){
		$bulan = array (
			1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		$pecahkan = explode('-', $tanggal);
		
		return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
	}
	
	## SPD OPD
	function cetak_spd($jenis_sppd, $posisi, $telaah_id, $pengikut_id, $nik, $posisi_penandatangan)
	{
		
		switch($jenis_sppd){
			case "opd"			: if($posisi==1){
									$data = $this->m_laporan->get_pelaksana_opd($telaah_id);
								} else if($posisi==2){
									$data = $this->m_laporan->get_pengikut_opd($telaah_id, $pengikut_id);
								} 
								break;
			case "dprd"			: if($posisi==1){
									$data = $this->m_laporan->get_pelaksana_dprd($telaah_id);
								} else if($posisi==2){
									$data = $this->m_laporan->get_pengikut_dprd($telaah_id, $pengikut_id);	
								} 
								break;
			case "walikota"		: if($posisi==1){
									$data = $this->m_laporan->get_pelaksana_walikota($telaah_id);
								} else if($posisi==2){
									$data = $this->m_laporan->get_pengikut_opd($telaah_id, $pengikut_id);	
								} 
								break;
		}
		$lokasi_tujuan = $this->m_lokasi_tujuan->get2($telaah_id);
		$tanda_tangan = $this->m_pegawai->get_pegawai_nik($posisi_penandatangan,$nik,'');
		
		$pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(25, 3.175, 25);
		
		$pdf->SetAutoPageBreak(false);
		// membuat halaman baru
		$pdf->AddPage();
		
		$pdf->Cell(10,12,'',0,1);
		$pdf->SetFont('Arial','B',20);
		
		if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']!=11){
			$dinas_kesehatan = $this->m_laporan->get_dinas_kesehatan();
			$pdf-> Image('./upload/kop_surat/'.$dinas_kesehatan[0]['kop_surat'],20,16,170,30);
		} else {
			if($jenis_sppd== "walikota"){
				$walikota = $this->m_laporan->get_pelaksana_walikota($telaah_id);
				$pdf-> Image('./upload/kop_surat/'.$walikota[0]['kop_surat'],20,16,170,30);
			} else {
				$pdf-> Image('./upload/kop_surat/'.$data[0]['kop_surat'],20,16,170,30);
			}
		}
		
		//QRcode::png('http://sppd.kendarikota.go.id/qr/sppd?telaah_id='.$this->input->get('telaah_id'),"test.png");
		//$pdf->Image("test.png", 185, 10, 20, 20, "png");
		
		$pdf->Cell(10,25,'',0,1);
		
		$pdf->Cell(10,7,'',0,1);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(110,5,'',0,0);
		$pdf->Cell(20,5,'Lampiran',0,0);
		$pdf->Cell(30,5,':',0,1);
		
		$pdf->Cell(110,5,'',0,0);
		$pdf->Cell(20,5,'Lembar Ke',0,0);
		$pdf->Cell(30,5,': I,II,III,IV',0,1);
		
		$pdf->Cell(110,5,'',0,0);
		$pdf->Cell(20,5,'Kode No.',0,0);
		$pdf->Cell(30,5,':',0,1);
		
		$pdf->Cell(110,5,'',0,0);
		$pdf->Cell(20,5,'Nomor',0,0);
		$pdf->Cell(30,5,':',0,1);
		
		$pdf->Cell(10,5,'',0,1);
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(160,7,'SURAT PERINTAH PERJALANAN DINAS (SPPD)',0,1,'C');
		
		$pdf->Cell(10,5,'',0,1);
		$pdf->SetFont('Arial','',8);
		
		$skpdnama = strtolower($data[0]['skpd_nama']);
		$skpdnama2 = ucwords($skpdnama);
		
		## 1
		$pdf->SetWidths(array(5,75,80));
		$border = array('LT', 'LT', 'LTR');
		$align = array('','','J');
		$style = array('', '', '');
		if($data[0]['jenis_skpd']==2){
			$caption = array("1.","Pejabat berwenang yang memberi perintah","Sekretaris DPRD Kota Kendari");
		} else if($data[0]['jenis_skpd']==3){
			$caption = array("1.","Pejabat berwenang yang memberi perintah","Sekretaris Daerah");
		} else if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']!=11){
			$caption = array("1.","Pejabat berwenang yang memberi perintah","Kepala Dinas Kesehatan Kota Kendari");
		} else {
			$caption = array("1.","Pejabat berwenang yang memberi perintah",'Kepala '.$skpdnama2);
		}
		$pdf->Row($caption, $border, $align);
		
		## 2
		$pdf->Cell(5,6,'2.','LTR',0);
		$pdf->Cell(75,6,'Nama Pegawai yang diperintahkan','TR',0);
		$pdf->Cell(80,6,$data[0]['pegawai_nama'],'TR',1);
		
		## 3.a
		$pdf->Cell(5,6,'3.','LTR',0,'T');
		$pdf->Cell(5,6,'a.','T',0,'T');
		$pdf->Cell(70,6,'Pangkat dan Golongan ruang gaji','TR',0);
		$pdf->Cell(80,6,$data[0]['pangkat']." - ".$data[0]['pegawai_golongan'],'TR',1);
		
		$pdf->Cell(5,6,'','LR',0,'T');
		$pdf->Cell(5,6,'','',0,'T');
		$pdf->Cell(70,6,'menurut PP No.30 Tahun 2015','R',0);
		$pdf->Cell(80,6,'','R',1);
		
		## 3.b
		$pdf->SetWidths(array(5,5,70,80));
		$border = array('LR','L', 'R', 'LR');
		$align = array('','','','J');
		$caption = array("","b.","Jabatan / Instansi",$data[0]['pegawai_namajabatan']);
		$pdf->Row($caption, $border, $align);
		
		## 3.c
		$pdf->SetWidths(array(5,5,70,80));
		$border = array('LR','L', 'R', 'LR');
		$caption = array("","c.","Tingkat biaya perjalanan dinas","");
		$pdf->Row($caption, $border);
		
		## 4
		$pdf->SetWidths(array(5,75,80));
		$border = array(1,1,1);
		$align = array('', '', 'J');
		$caption = array("4.","Maksud Perjalanan Dinas",$data[0]['telaah_perihal']);
		$pdf->Row($caption, $border, $align);
		
		## 5
		$pdf->Cell(5,6,'5.',1,0);
		$pdf->Cell(75,6,'Alat angkutan yang dipergunakan',1,0);
		$pdf->Cell(80,6,$data[0]['telaah_angkutan'],1,1);
		
		## 6.a
		$pdf->SetWidths(array(5,5,70,80));
		$border = array('L','L','','LR');
		$align = array('','', '', 'J');
		$caption = array("6.","a.","Tempat berangkat",$data[0]['telaah_tempatberangkat']);
		$pdf->Row($caption, $border, $align);
		
		## 6.b
		$pdf->SetWidths(array(5,5,70,80));
		$border = array('L','L','','LR');
		$align = array('','', '', 'J');
		if($data[0]['telaah_domainperjalanan']== 3 || $data[0]['telaah_domainperjalanan']== 4){
			$caption = array("","b.","Tempat tujuan",$data[0]['telaah_kantortujuan']);
		} else {
			if(count($lokasi_tujuan)==1){
				$caption = array("","b.","Tempat tujuan",$data[0]['kabupaten_kota']." DAN ".$lokasi_tujuan[0]['kabupaten_kota']);
			} else if(count($lokasi_tujuan)==2){
				$caption = array("","b.","Tempat tujuan",$data[0]['kabupaten_kota'].", ".$lokasi_tujuan[0]['kabupaten_kota']." DAN ".$lokasi_tujuan[1]['kabupaten_kota']);
			} else {
				$caption = array("","b.","Tempat tujuan",$data[0]['kabupaten_kota']);
			}
		}
		$pdf->Row($caption, $border, $align);
		
		## 7
		$start_date = new DateTime($data[0]['telaah_tanggalberangkat']);
		$end_date = new DateTime($data[0]['telaah_tanggalkembali']);
		$interval = $start_date->diff($end_date);
			
		$pdf->Cell(5,6,'7.','LTR',0);
		$pdf->Cell(5,6,'a.','LT',0);
		$pdf->Cell(70,6,'Lamanya Perjalanan dinas','TR',0);
		if($data[0]['telaah_hari']==0){
			$pdf->Cell(80,6,($interval->days + 1).' Hari','LTR',1);
		} else {
			$pdf->Cell(80,6,$data[0]['telaah_hari'].' Hari','LTR',1);
		}
		$pdf->Cell(5,6,'','LR',0);
		$pdf->Cell(5,6,'b.','L',0);
		$pdf->Cell(70,6,'Tanggal berangkat','R',0);
		$pdf->Cell(80,6,date("d-m-Y", strtotime($data[0]['telaah_tanggalberangkat'])),'LR',1);
		
		$pdf->Cell(5,6,'','LR',0);
		$pdf->Cell(5,6,'c.','L',0);
		$pdf->Cell(70,6,'Tanggal harus kembali','R',0);
		$pdf->Cell(80,6,date("d-m-Y", strtotime($data[0]['telaah_tanggalkembali'])),'LR',1);
		
		## 8
		$pdf->Cell(5,6,'8.',1,0);
		$pdf->Cell(75,6,'Pengikut',1,0);
		$pdf->Cell(80,6,'Keterangan',1,1);
		
		// if($posisi==1 && $jenis_sppd!="dprd"){
			// $pengikut = $this->m_laporan->get_pengikut($telaah_id);
			// $jml_pengikut = count($pengikut);
			// if(!isset($pengikut[0]) || $pengikut[0] == ""){
			// } else {
				// for($i=0;$i<$jml_pengikut;$i++){
					// $pdf->Cell(5,4,'','LR',0,'T');
					// $pdf->Cell(5,4,($i+1).'.','L',0,'T');
					// $pdf->Cell(70,4,$pengikut[$i]['pegawai_nama'],'R',0);
					// $pdf->Cell(80,4,'','LR',1);
				// }
				
			// }
		// } 
			
		## 9
		$pdf->Cell(5,6,'9.',1,0);
		$pdf->Cell(75,6,'Pembebanan Anggaran',1,0);
		$pdf->Cell(80,6,'',1,1);
		
		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
		
		## 9.a
		$pdf->SetWidths(array(5,5,70,80));
		$border = array('L','L','','LR');
		$align = array('','', '', 'J');
		$caption = array("","a.","Instansi",$skpd_nama2);
		$pdf->Row($caption, $border, $align);
		
		## 9.b
		$pdf->SetWidths(array(5,5,70,80));
		$border = array('L','L','','LR');
		$align = array('','', '', 'J');
		$caption = array("","b.","Mata Anggaran",$data[0]['no_rekening']);
		$pdf->Row($caption, $border, $align);
		
		$pdf->SetWidths(array(5,5,70,80));
		$border = array('L','L','','LR');
		$align = array('','', '', 'J');
		$caption = array("","","",$data[0]['mata_anggaran']);
		$pdf->Row($caption, $border, $align);
		
		## 10
		$pdf->Cell(5,6,'10.',1,0);
		$pdf->Cell(75,6,'keterangan lain-lain',1,0);
		$pdf->Cell(80,6,'',1,1);
		
		## TANDA TANGAN
		$pdf->Cell(10,7,'',0,1);
		
		$pdf->Cell(80,5,'',0,0);
		$pdf->Cell(35,5,'Dikeluarkan di',0,0);
		$pdf->Cell(45,5,': Kendari',0,1);
		
		$pdf->Cell(80,5,'',0,0);
		$pdf->Cell(35,5,'Tanggal',0,0);
		$pdf->Cell(45,5,': '.$this->tgl_indo($data[0]['telaah_tanggalspd']),0,1);
		
		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
		
		if($tanda_tangan[0]['pegawai_jabatan']==2){
			$pdf->Cell(80,5,'',0,0);
			$pdf->MultiCell(80,5,'Plh. Sekretaris Daerah Kota Kendari',0,1);
			$pdf->Cell(80,5,'',0,0);
			$pdf->MultiCell(80,5,'ASISTEN I',0,1);
		} else if($tanda_tangan[0]['pegawai_jabatan']==17){
			$pdf->Cell(80,5,'',0,0);
			$pdf->MultiCell(80,5,'Plh. Sekretaris Daerah Kota Kendari',0,1);
			$pdf->Cell(80,5,'',0,0);
			$pdf->MultiCell(80,5,'ASISTEN II',0,1);
		} else if($tanda_tangan[0]['pegawai_jabatan']==18){
			$pdf->Cell(80,5,'',0,0);
			$pdf->MultiCell(80,5,'Plh. Sekretaris Daerah Kota Kendari',0,1);
			$pdf->Cell(80,5,'',0,0);
			$pdf->MultiCell(80,5,'ASISTEN III',0,1);
		} else if($tanda_tangan[0]['pegawai_jabatan']==7){
			if($data[0]['jenis_skpd']==1 || $data[0]['jenis_skpd']==10){
				$pdf->Cell(80,5,'',0,0);
				$pdf->MultiCell(80,5,'Pelaksana Kepala Dinas',0,1);
			} else if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']==1){
				$pdf->Cell(80,5,'',0,0);
				$pdf->MultiCell(80,5,'Pelaksana Kepala Dinas',0,1);
			} else if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']==11){
				$pdf->Cell(80,5,'',0,0);
				$pdf->MultiCell(80,5,'Pelaksana Kepala Puskesmas',0,1);
			} 
			$pdf->Cell(80,5,'',0,0);
			$pdf->MultiCell(80,5,$tanda_tangan[0]['pegawai_namajabatan'],0,1);
		} else {
			$pdf->Cell(80,5,'',0,0);
			$pdf->MultiCell(80,5,$tanda_tangan[0]['pegawai_namajabatan'],0,1);
		} 
		
		## SETTING KOORDINAT Y
		if($pengikut_id){
			$data2['telaah_tte_y'] = $pdf->GetY()+1;
			$data2['telaah_id'] = $telaah_id;
			$data2['pegawai_id'] = $pengikut_id;
			$this->m_pengikut->update($data2);
		} else {
			$data3['telaah_tte_y'] = $pdf->GetY()+1;
			$data3['telaah_id'] = $telaah_id;
			$data3['telaah_ttdspd'] = $tanda_tangan[0]['pegawai_id'];
			$this->m_telaah->update($data3);	
		}
			
		
		$pdf->Cell(10,22,'',0,1);
		$pdf->Cell(80,5,'',0,0);
		$pdf->Cell(80,5,$tanda_tangan[0]['pegawai_nama'],0,1);
		
		if($tanda_tangan[0]['pegawai_jabatan']==1 || $tanda_tangan[0]['pegawai_jabatan']==14){
			$pdf->Cell(80,5,'',0,0);
			$pdf->Cell(80,5,'',0,1);
		} else {
			$pdf->Cell(80,5,'',0,0);
			$pdf->Cell(80,5,$tanda_tangan[0]['pangkat'].", Gol. ".$tanda_tangan[0]['pegawai_golongan'],0,1);
			$pdf->Cell(80,5,'',0,0);
			$pdf->Cell(80,5,'NIP.'. $tanda_tangan[0]['pegawai_nip'],0,1);
		} 
		
		//atur posisi 1.5 cm dari bawah
		$pdf->SetY(-15);
		//buat garis horizontal
		$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
		//Arial italic 9
		$pdf->SetFont('Arial','I',9);
		//nomor halaman
		$pdf->Cell(0,10,'Dokumen ini ditandatangani secara elektronik menggunakan Layanan BSrE',0,0,'R');
		
		
		// membuat halaman baru
		$pdf->AddPage();

		$pdf->SetFont('Arial','',8);
		$pdf->Cell(10,40,'',0,1);
		$pdf->Cell(80,4,'','LTR',0);
		$pdf->Cell(5,4,'I.','TL',0);
		$pdf->Cell(30,4,'Berangkat dari','T',0);
		$pdf->Cell(45,4,': Kendari','TR',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'(Tempat Kedudukan)',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,': '.date("d-m-Y", strtotime($data[0]['telaah_tanggalberangkat'])),'R',1);
		
		if($tanda_tangan[0]['pegawai_jabatan']==7){
			if($data[0]['jenis_skpd']==1 || $data[0]['jenis_skpd']==10){
				$pdf->Cell(80,4,'','LR',0);
				$pdf->Cell(5,4,'','L',0);
				$pdf->Cell(75,4,'Pelaksana Kepala Dinas','R',1);
			} else if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']==1){
				$pdf->Cell(80,4,'','LR',0);
				$pdf->Cell(5,4,'','L',0);
				$pdf->Cell(75,4,'Pelaksana Kepala Dinas','R',1);
			} else if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']==11){
				$pdf->Cell(80,4,'','LR',0);
				$pdf->Cell(5,4,'','L',0);
				$pdf->Cell(75,4,'Pelaksana Kepala Puskesmas','R',1);
			} 
		} 
		
		$start_x=$pdf->GetX(); //initial x (start of column position)
		
		$current_y = $pdf->GetY();
		$current_x = $pdf->GetX();
		$cell_width = 5;  
		$cell_height=4;    
		$text=str_repeat(' ',400);
		$pdf->MultiCell(80,$cell_height,"".$text,'LR'); 
		
		$current_x+=85;                           
		$pdf->SetXY($current_x, $current_y);  
		$pdf->MultiCell(70,$cell_height,$tanda_tangan[0]['pegawai_namajabatan'],''); 
		
		$current_x+=70;                           
		$pdf->SetXY($current_x, $current_y);  
		$pdf->MultiCell(5,$cell_height,"",'R'); 
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		/* if($data[0]['jenis_skpd']==7 || $data[0]['jenis_skpd']==10 ){
			if($tanda_tangan[0]['status_tandatangan']==1){
				if($tanda_tangan[0]['pegawai_tandatangan']){
					if(file_exists('./upload/tanda_tangan/'.$tanda_tangan[0]['pegawai_tandatangan'])){
						$x=$pdf->GetX();
						$y=$pdf->GetY();
						$pdf->MultiCell(10,0,$pdf-> Image('./upload/tanda_tangan/'.$tanda_tangan[0]['pegawai_tandatangan'],125,$y,40,15),0,0);
					} 
				}
			}
		} */
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
	
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
	
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,$tanda_tangan[0]['pegawai_nama'],'R',1,'C');
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		if($tanda_tangan[0]['pegawai_jabatan']==1 || $tanda_tangan[0]['pegawai_jabatan']==14){
			$pdf->Cell(75,4,'','BR',1,'C');
		} else {
			$pdf->Cell(75,4,'NIP .'.$tanda_tangan[0]['pegawai_nip'],'BR',1,'C');
		} 
	
		
		
		//baris 2
		if(($data[0]['telaah_kategori']==1 && $data[0]['jenis_skpd']!=7) || ($data[0]['telaah_kategori']==2)){
			if($data[0]['telaah_domainperjalanan']==1 || $data[0]['telaah_domainperjalanan']==2 ){
				$pdf->SetWidths(array(5,30,45,5,30,45));
				$border = array('LT','T','T','LT','T','TR');
				$align = array('','','','','','');
				$caption = array('II.','Tiba Di',': '.$data[0]['kabupaten_kota'],'','Berangkat dari',': '.$data[0]['kabupaten_kota']);
				$pdf->Row($caption, $border, $align);
			} else {
				$pdf->Cell(5,4,'II.','LT',0);
				$pdf->Cell(30,4,'Tiba Di','T',0);
				$pdf->Cell(45,4,':','T',0);
				$pdf->Cell(5,4,'','LT',0);
				$pdf->Cell(30,4,'Berangkat dari','T',0);
				$pdf->Cell(45,4,':','TR',1);
			}
		} else {
			$pdf->Cell(5,4,'II.','LT',0);
			$pdf->Cell(30,4,'Tiba Di','T',0);
			$pdf->Cell(45,4,':','T',0);
			$pdf->Cell(5,4,'','LT',0);
			$pdf->Cell(30,4,'Berangkat dari','T',0);
			$pdf->Cell(45,4,':','TR',1);
		}
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,': '.date("d-m-Y", strtotime($data[0]['telaah_tanggalberangkat'])),'R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Ke',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Jabatan',0,0);
		$pdf->Cell(45,4,':','R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Jabatan',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'_______________________________','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'_______________________________','R',1,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'','R',1,'C');
		
		//baris 3
		$lokasi_tujuan = $this->m_lokasi_tujuan->get2($telaah_id);
		
		$pdf->Cell(5,4,'III.','LT',0);
		$pdf->Cell(30,4,'Tiba Di','T',0);
		$pdf->Cell(45,4,':','T',0);
		$pdf->Cell(5,4,'','LT',0);
		$pdf->Cell(30,4,'Berangkat dari','T',0);
		$pdf->Cell(45,4,':','TR',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,':','R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Ke',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Jabatan',0,0);
		$pdf->Cell(45,4,':','R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Jabatan',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'_______________________________','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'_______________________________','R',1,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'','R',1,'C');
		
		//baris 4
		$lokasi_tujuan = $this->m_lokasi_tujuan->get2($telaah_id);
		
		$pdf->Cell(5,4,'IV.','LT',0);
		$pdf->Cell(30,4,'Tiba Di','T',0);
		$pdf->Cell(45,4,':','T',0);
		$pdf->Cell(5,4,'','LT',0);
		$pdf->Cell(30,4,'Berangkat dari','T',0);
		$pdf->Cell(45,4,':','TR',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,':','R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Ke',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Jabatan',0,0);
		$pdf->Cell(45,4,':','R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Jabatan',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'_______________________________','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'_______________________________','R',1,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'','R',1,'C');
		
		//baris 5
		
		$pdf->Cell(5,4,'V','LT',0);
		$pdf->Cell(30,4,'Tiba Di','T',0);
		$pdf->Cell(45,4,':','T',0);
		$pdf->Cell(5,4,'','LT',0);
		$pdf->Cell(30,4,'Berangkat dari','T',0);
		$pdf->Cell(45,4,':','TR',1);
	
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,':','R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Ke',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Jabatan',0,0);
		$pdf->Cell(45,4,':','R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Jabatan',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'_______________________________','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'_______________________________','R',1,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'','R',1,'C');
		
		//baris 5
		if($data[0]['skpd_id']==38){
	
		$pdf->Cell(5,4,'VI','LT',0);
		$pdf->Cell(30,4,'Tiba Di','T',0);
		$pdf->Cell(45,4,':','T',0);
		$pdf->Cell(5,4,'','LT',0);
		$pdf->Cell(30,4,'Berangkat dari','T',0);
		$pdf->Cell(45,4,':','TR',1);
	
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,':','R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Ke',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Jabatan',0,0);
		$pdf->Cell(45,4,':','R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Jabatan',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'_______________________________','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'_______________________________','R',1,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'','R',1,'C');
		}
		
		//baris 6
		
		if(($data[0]['telaah_kategori']==1 && $data[0]['jenis_skpd']!=7) || ($data[0]['telaah_kategori']==2)){
			if($data[0]['telaah_domainperjalanan']==1 || $data[0]['telaah_domainperjalanan']==2 ){
				$pdf->SetWidths(array(5,30,45,80));
				$border = array('LT','T','RT','LRT');
				$align = array('','','','');
				if($data[0]['skpd_id']==38){
					$caption = array('VII.','Tiba Di',': Kendari','Telah diperiksa dengan keterangan bahwa');
				}else {
					$caption = array('VI','Tiba Di',': Kendari','Telah diperiksa dengan keterangan bahwa');
				}
				$pdf->Row($caption, $border, $align);
			} else {
				if($data[0]['skpd_id']==38){
					$pdf->Cell(5,4,'VII.','LT',0);
				}else {
					$pdf->Cell(5,4,'VI.','LT',0);
				}
				$pdf->Cell(30,4,'Tiba Di','T',0);
				$pdf->Cell(45,4,':','RT',0);
				$pdf->Cell(80,4,'Telah diperiksa dengan keterangan bahwa','lRT',1);
			}
		} else {
			if($data[0]['skpd_id']==38){
				$pdf->Cell(5,4,'VII.','LT',0);
			}else {
				$pdf->Cell(5,4,'VI.','LT',0);
			}
			$pdf->Cell(30,4,'Tiba Di','T',0);
			$pdf->Cell(45,4,':','RT',0);
			$pdf->Cell(80,4,'Telah diperiksa dengan keterangan bahwa','lRT',1);
		}
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'(Tempat Kedudukan)',0,0);
		$pdf->Cell(45,4,':','R',0);
		$pdf->Cell(80,4,'perjalanan tersebut diatas telah benar dilakukan','LR',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,': '.date("d-m-Y", strtotime($data[0]['telaah_tanggalkembali'])),'R',0);
		$pdf->Cell(80,4,'atas perintahnya semata-mata untuk kepentingan','LR',1);
		
		$pdf->Cell(80,4,'','L',0);
		$pdf->Cell(80,4,'jabatan dalam waktu yang sesingkat-singkatnya.','LR',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'Pejabat yang memberi perintah','R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'Pejabat yang memberi perintah','R',1);
		
		
		if($tanda_tangan[0]['pegawai_jabatan']==7){
			if($data[0]['jenis_skpd']==1 || $data[0]['jenis_skpd']==10){
				$pdf->Cell(5,4,'','L',0);
				$pdf->Cell(75,4,'Pelaksana Kepala Dinas','R',0);
				$pdf->Cell(5,4,'','L',0);
				$pdf->Cell(75,4,'Pelaksana Kepala Dinas','R',1);
			} else if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']==1){
				$pdf->Cell(5,4,'','L',0);
				$pdf->Cell(75,4,'Pelaksana Kepala Dinas','R',0);
				$pdf->Cell(5,4,'','L',0);
				$pdf->Cell(75,4,'Pelaksana Kepala Dinas','R',1);
			} else if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']==11){
				$pdf->Cell(5,4,'','L',0);
				$pdf->Cell(75,4,'Pelaksana Kepala Puskesmas','R',0);
				$pdf->Cell(5,4,'','L',0);
				$pdf->Cell(75,4,'Pelaksana Kepala Puskesmas','R',1);
			} 
		} 
		
		$pdf->SetWidths(array(5,75,5,75));
		$border = array('L','R', 'L', 'R');
		$align = array('','','','');
		$caption = array("",$tanda_tangan[0]['pegawai_namajabatan'],"",$tanda_tangan[0]['pegawai_namajabatan']);
		$pdf->Row($caption, $border, $align);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		if ($data[0]['skpd_id']==1){
			$pdf->Cell(5,4,'','L',0);
			$pdf->Cell(75,4,"_______________________________",'R',0,'C');
			$pdf->Cell(5,4,'','L',0);
			$pdf->Cell(75,4,"_______________________________",'R',1,'C');
		} else {
			$pdf->Cell(5,4,'','L',0);
			$pdf->Cell(75,4,$tanda_tangan[0]['pegawai_nama'],'R',0,'C');
			$pdf->Cell(5,4,'','L',0);
			$pdf->Cell(75,4,$tanda_tangan[0]['pegawai_nama'],'R',1,'C');
		}
		
		/* if($data[0]['jenis_skpd']==7 || $data[0]['jenis_skpd']==10 ){
			if(count($tanda_tangan[0]['tanda_tangan'])!=0){
				if($tanda_tangan[0]['status']==1){
						$x=$pdf->GetX();
						$y=$pdf->GetY()-20;
						$pdf->MultiCell(10,0,$pdf-> Image('./upload/tanda_tangan/'.$tanda_tangan[0]['tanda_tangan'],47,$y,40,15),0,0);
						$pdf->MultiCell(10,0,$pdf-> Image('./upload/tanda_tangan/'.$tanda_tangan[0]['tanda_tangan'],127,$y,40,15),0,0);	
				}
			}
		} */
		
		$pdf->Cell(5,4,'','L',0);
		if($tanda_tangan[0]['pegawai_jabatan']==1 || $tanda_tangan[0]['pegawai_jabatan']==14 || $data[0]['skpd_id']==1){
			$pdf->Cell(75,4,'','R',0,'C');
		} else {
			$pdf->Cell(75,4,'NIP '.$tanda_tangan[0]['pegawai_nip'],'R',0,'C');
		} 
		$pdf->Cell(5,4,'','L',0);
		
		if($tanda_tangan[0]['pegawai_jabatan']==1 || $tanda_tangan[0]['pegawai_jabatan']==14 || $data[0]['skpd_id']==1){
			$pdf->Cell(75,4,'','R',1,'C');
		} else {
			$pdf->Cell(75,4,'NIP '.$tanda_tangan[0]['pegawai_nip'],'R',1,'C');
		} 
			
		if($data[0]['skpd_id']==38){
			$pdf->Cell(5,4,'VIII.','LTB',0);
		} else {
			$pdf->Cell(5,4,'VII.','LTB',0);
		}
		$pdf->Cell(155,4,'Keterangan Lain-lain','RTB',1);
		
		if($data[0]['skpd_id']==38){
			$pdf->Cell(5,4,'IX.','LT',0);
		} else {
			$pdf->Cell(5,4,'VIII.','LT',0);
		}
		
		$pdf->Cell(155,4,'PERHATIAN','RT',1); 
		
		$pdf->MultiCell(160,4,'Pejabat yang berwenang memberi SPPD pegawai yang melakukan Perjalanan Dinas, para pejabat yang mengesahkan tanggal berangkat/tiba, serta bendaharawan bertanggung jawab berdasarkan peraturan - peraturan Keuangan Negara, apabila Negara menderita rugi akibat kesalahan, kelalaian dan kealpaan (Lampiran SK. Menteri Keuangan tanggal 30-4-1974 Nomor B-296/MK/I/1974).',1,'J');
		
		//atur posisi 1.5 cm dari bawah
		$pdf->SetY(-15);
		//buat garis horizontal
		$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
		//Arial italic 9
		$pdf->SetFont('Arial','I',9);
		//nomor halaman
		$pdf->Cell(0,10,'Dokumen ini ditandatangani secara elektronik menggunakan Layanan BSrE',0,0,'R');
		
		//$pdf->Output('D','SPPD - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput'])).'.pdf'); 
	
		//$path = "D:/workspace/sppd-dev/upload/AAA.pdf";
		//$path = base_url().'upload/doc_dummy/1.pdf';
		$filename = 'SPPD - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput'])).'.pdf';
		$path = "./upload/doc_dummy/$filename";
		$pdf->Output($path,'F');
    }
	
	## SPD OPD
	/*function cetak_spd2($jenis_sppd, $posisi, $telaah_id, $pengikut_id, $nik, $posisi_penandatangan)
	{
		
		$telaah =  $this->m_telaah->get($telaah_id);
		
		## Hapus PDF
		$filename = 'SPPD - '.$telaah[0]['pegawai_nama'].' - '.date("d-m-Y", strtotime($telaah[0]['telaah_tanggalspd'])).'.pdf';
		$path_file = './upload/doc_TTE/';
		unlink($path_file.$filename);
		
		switch($jenis_sppd){
			case "opd"			: if($posisi==1){
									$data = $this->m_laporan->get_pelaksana_opd($telaah_id);
								} else if($posisi==2){
									$data = $this->m_laporan->get_pengikut_opd($telaah_id, $pengikut_id);
								} 
								break;
			case "dprd"			: if($posisi==1){
									$data = $this->m_laporan->get_pelaksana_dprd($telaah_id);
								} else if($posisi==2){
									$data = $this->m_laporan->get_pengikut_dprd($telaah_id, $pengikut_id);	
								} 
								break;
			case "walikota"		: if($posisi==1){
									$data = $this->m_laporan->get_pelaksana_walikota($telaah_id);
								} else if($posisi==2){
									$data = $this->m_laporan->get_pengikut_opd($telaah_id, $pengikut_id);	
								} 
								break;
		}
		
		$lokasi_tujuan = $this->m_lokasi_tujuan->get2($telaah_id);
		$tanda_tangan = $this->m_pegawai->get_pegawai_nik($posisi_penandatangan,$nik,'');
		
		$pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(25, 3.175, 25);
		
		$pdf->SetAutoPageBreak(false);
		// membuat halaman baru
		$pdf->AddPage();
		
		$pdf->Cell(10,12,'',0,1);
		$pdf->SetFont('Arial','B',20);
		
		if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']!=11){
			$dinas_kesehatan = $this->m_laporan->get_dinas_kesehatan();
			$pdf-> Image('./upload/kop_surat/'.$dinas_kesehatan[0]['kop_surat'],20,16,170,30);
		} else {
			if($jenis_sppd== "walikota"){
				$walikota = $this->m_laporan->get_pelaksana_walikota($telaah_id);
				$pdf-> Image('./upload/kop_surat/'.$walikota[0]['kop_surat'],20,16,170,30);
			} else {
				$pdf-> Image('./upload/kop_surat/'.$data[0]['kop_surat'],20,16,170,30);
			}
		}
		
		//QRcode::png('http://sppd.kendarikota.go.id/qr/sppd?telaah_id='.$this->input->get('telaah_id'),"test.png");
		//$pdf->Image("test.png", 185, 10, 20, 20, "png");
		
		$pdf->Cell(10,25,'',0,1);
		
		$pdf->Cell(10,7,'',0,1);
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(110,5,'',0,0);
		$pdf->Cell(20,5,'Lampiran',0,0);
		$pdf->Cell(30,5,':',0,1);
		
		$pdf->Cell(110,5,'',0,0);
		$pdf->Cell(20,5,'Lembar Ke',0,0);
		$pdf->Cell(30,5,': I,II,III,IV',0,1);
		
		$pdf->Cell(110,5,'',0,0);
		$pdf->Cell(20,5,'Kode No.',0,0);
		$pdf->Cell(30,5,':',0,1);
		
		$pdf->Cell(110,5,'',0,0);
		$pdf->Cell(20,5,'Nomor',0,0);
		$pdf->Cell(30,5,':',0,1);
		
		$pdf->Cell(10,5,'',0,1);
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(160,7,'SURAT PERINTAH PERJALANAN DINAS (SPPD)',0,1,'C');
		
		$pdf->Cell(10,5,'',0,1);
		$pdf->SetFont('Arial','',8);
		
		$skpdnama = strtolower($data[0]['skpd_nama']);
		$skpdnama2 = ucwords($skpdnama);
		
		## 1
		$pdf->SetWidths(array(5,75,80));
		$border = array('LT', 'LT', 'LTR');
		$align = array('','','J');
		$style = array('', '', '');
		if($data[0]['jenis_skpd']==2){
			$caption = array("1.","Pejabat berwenang yang memberi perintah","Sekretaris DPRD Kota Kendari");
		} else if($data[0]['jenis_skpd']==3){
			$caption = array("1.","Pejabat berwenang yang memberi perintah","Sekretaris Daerah");
		} else if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']!=11){
			$caption = array("1.","Pejabat berwenang yang memberi perintah","Kepala Dinas Kesehatan Kota Kendari");
		} else {
			$caption = array("1.","Pejabat berwenang yang memberi perintah",'Kepala '.$skpdnama2);
		}
		$pdf->Row($caption, $border, $align);
		
		## 2
		$pdf->Cell(5,6,'2.','LTR',0);
		$pdf->Cell(75,6,'Nama Pegawai yang diperintahkan','TR',0);
		$pdf->Cell(80,6,$data[0]['pegawai_nama'],'TR',1);
		
		## 3.a
		$pdf->Cell(5,6,'3.','LTR',0,'T');
		$pdf->Cell(5,6,'a.','T',0,'T');
		$pdf->Cell(70,6,'Pangkat dan Golongan ruang gaji','TR',0);
		$pdf->Cell(80,6,$data[0]['pangkat']." - ".$data[0]['pegawai_golongan'],'TR',1);
		
		$pdf->Cell(5,6,'','LR',0,'T');
		$pdf->Cell(5,6,'','',0,'T');
		$pdf->Cell(70,6,'menurut PP No.30 Tahun 2015','R',0);
		$pdf->Cell(80,6,'','R',1);
		
		## 3.b
		$pdf->SetWidths(array(5,5,70,80));
		$border = array('LR','L', 'R', 'LR');
		$align = array('','','','J');
		$caption = array("","b.","Jabatan / Instansi",$data[0]['pegawai_namajabatan']);
		$pdf->Row($caption, $border, $align);
		
		## 3.c
		$pdf->SetWidths(array(5,5,70,80));
		$border = array('LR','L', 'R', 'LR');
		$caption = array("","c.","Tingkat biaya perjalanan dinas","");
		$pdf->Row($caption, $border);
		
		## 4
		$pdf->SetWidths(array(5,75,80));
		$border = array(1,1,1);
		$align = array('', '', 'J');
		$caption = array("4.","Maksud Perjalanan Dinas",$data[0]['telaah_perihal']);
		$pdf->Row($caption, $border, $align);
		
		## 5
		$pdf->Cell(5,6,'5.',1,0);
		$pdf->Cell(75,6,'Alat angkutan yang dipergunakan',1,0);
		$pdf->Cell(80,6,$data[0]['telaah_angkutan'],1,1);
		
		## 6.a
		$pdf->SetWidths(array(5,5,70,80));
		$border = array('L','L','','LR');
		$align = array('','', '', 'J');
		$caption = array("6.","a.","Tempat berangkat",$data[0]['telaah_tempatberangkat']);
		$pdf->Row($caption, $border, $align);
		
		## 6.b
		$pdf->SetWidths(array(5,5,70,80));
		$border = array('L','L','','LR');
		$align = array('','', '', 'J');
		if($data[0]['telaah_domainperjalanan']== 3 || $data[0]['telaah_domainperjalanan']== 4){
			$caption = array("","b.","Tempat tujuan",$data[0]['telaah_kantortujuan']);
		} else {
			if(count($lokasi_tujuan)==1){
				$caption = array("","b.","Tempat tujuan",$data[0]['kabupaten_kota']." DAN ".$lokasi_tujuan[0]['kabupaten_kota']);
			} else if(count($lokasi_tujuan)==2){
				$caption = array("","b.","Tempat tujuan",$data[0]['kabupaten_kota'].", ".$lokasi_tujuan[0]['kabupaten_kota']." DAN ".$lokasi_tujuan[1]['kabupaten_kota']);
			} else {
				$caption = array("","b.","Tempat tujuan",$data[0]['kabupaten_kota']);
			}
		}
		$pdf->Row($caption, $border, $align);
		
		## 7
		$start_date = new DateTime($data[0]['telaah_tanggalberangkat']);
		$end_date = new DateTime($data[0]['telaah_tanggalkembali']);
		$interval = $start_date->diff($end_date);
			
		$pdf->Cell(5,6,'7.','LTR',0);
		$pdf->Cell(5,6,'a.','LT',0);
		$pdf->Cell(70,6,'Lamanya Perjalanan dinas','TR',0);
		if($data[0]['telaah_hari']==0){
			$pdf->Cell(80,6,($interval->days + 1).' Hari','LTR',1);
		} else {
			$pdf->Cell(80,6,$data[0]['telaah_hari'].' Hari','LTR',1);
		}
		$pdf->Cell(5,6,'','LR',0);
		$pdf->Cell(5,6,'b.','L',0);
		$pdf->Cell(70,6,'Tanggal berangkat','R',0);
		$pdf->Cell(80,6,date("d-m-Y", strtotime($data[0]['telaah_tanggalberangkat'])),'LR',1);
		
		$pdf->Cell(5,6,'','LR',0);
		$pdf->Cell(5,6,'c.','L',0);
		$pdf->Cell(70,6,'Tanggal harus kembali','R',0);
		$pdf->Cell(80,6,date("d-m-Y", strtotime($data[0]['telaah_tanggalkembali'])),'LR',1);
		
		## 8
		$pdf->Cell(5,6,'8.',1,0);
		$pdf->Cell(75,6,'Pengikut',1,0);
		$pdf->Cell(80,6,'Keterangan',1,1);
		
		if($jenis_sppd==1 && $jenis_sppd!="dprd"){
			$pengikut = $this->m_laporan->get_pengikut($telaah_id);
			$jml_pengikut = count($pengikut);
			if(!isset($pengikut[0]) || $pengikut[0] == ""){
			} else {
				for($i=0;$i<$jml_pengikut;$i++){
					$pdf->Cell(5,4,'','LR',0,'T');
					$pdf->Cell(5,4,($i+1).'.','L',0,'T');
					$pdf->Cell(70,4,$pengikut[$i]['pegawai_nama'],'R',0);
					$pdf->Cell(80,4,'','LR',1);
				}
				
			}
		} 
			
		## 9
		$pdf->Cell(5,6,'9.',1,0);
		$pdf->Cell(75,6,'Pembebanan Anggaran',1,0);
		$pdf->Cell(80,6,'',1,1);
		
		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
		
		## 9.a
		$pdf->SetWidths(array(5,5,70,80));
		$border = array('L','L','','LR');
		$align = array('','', '', 'J');
		$caption = array("","a.","Instansi",$skpd_nama2);
		$pdf->Row($caption, $border, $align);
		
		## 9.b
		$pdf->SetWidths(array(5,5,70,80));
		$border = array('L','L','','LR');
		$align = array('','', '', 'J');
		$caption = array("","b.","Mata Anggaran",$data[0]['no_rekening']);
		$pdf->Row($caption, $border, $align);
		
		$pdf->SetWidths(array(5,5,70,80));
		$border = array('L','L','','LR');
		$align = array('','', '', 'J');
		$caption = array("","","",$data[0]['mata_anggaran']);
		$pdf->Row($caption, $border, $align);
		
		## 10
		$pdf->Cell(5,6,'10.',1,0);
		$pdf->Cell(75,6,'keterangan lain-lain',1,0);
		$pdf->Cell(80,6,'',1,1);
		
		## TANDA TANGAN
		$pdf->Cell(10,7,'',0,1);
		
		$pdf->Cell(80,5,'',0,0);
		$pdf->Cell(35,5,'Dikeluarkan di',0,0);
		$pdf->Cell(45,5,': Kendari',0,1);
		
		$pdf->Cell(80,5,'',0,0);
		$pdf->Cell(35,5,'Tanggal',0,0);
		$pdf->Cell(45,5,': '.$this->tgl_indo($data[0]['telaah_tanggalspd']),0,1);
		
		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
		
		if($tanda_tangan[0]['pegawai_jabatan']==2){
			$pdf->Cell(80,5,'',0,0);
			$pdf->MultiCell(80,5,'Plh. Sekretaris Daerah Kota Kendari',0,1);
			$pdf->Cell(80,5,'',0,0);
			$pdf->MultiCell(80,5,'ASISTEN I',0,1);
		} else if($tanda_tangan[0]['pegawai_jabatan']==17){
			$pdf->Cell(80,5,'',0,0);
			$pdf->MultiCell(80,5,'Plh. Sekretaris Daerah Kota Kendari',0,1);
			$pdf->Cell(80,5,'',0,0);
			$pdf->MultiCell(80,5,'ASISTEN II',0,1);
		} else if($tanda_tangan[0]['pegawai_jabatan']==18){
			$pdf->Cell(80,5,'',0,0);
			$pdf->MultiCell(80,5,'Plh. Sekretaris Daerah Kota Kendari',0,1);
			$pdf->Cell(80,5,'',0,0);
			$pdf->MultiCell(80,5,'ASISTEN III',0,1);
		} else if($tanda_tangan[0]['pegawai_jabatan']==7){
			if($data[0]['jenis_skpd']==1 || $data[0]['jenis_skpd']==10){
				$pdf->Cell(80,5,'',0,0);
				$pdf->MultiCell(80,5,'Pelaksana Kepala Dinas',0,1);
			} else if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']==1){
				$pdf->Cell(80,5,'',0,0);
				$pdf->MultiCell(80,5,'Pelaksana Kepala Dinas',0,1);
			} else if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']==11){
				$pdf->Cell(80,5,'',0,0);
				$pdf->MultiCell(80,5,'Pelaksana Kepala Puskesmas',0,1);
			} 
			$pdf->Cell(80,5,'',0,0);
			$pdf->MultiCell(80,5,$tanda_tangan[0]['pegawai_namajabatan'],0,1);
		} else {
			$pdf->Cell(80,5,'',0,0);
			$pdf->MultiCell(80,5,$tanda_tangan[0]['pegawai_namajabatan'],0,1);
		} 
		
		if($tanda_tangan[0]['status_tandatangan']==1){
			if($tanda_tangan[0]['pegawai_tandatangan']){
				if(file_exists('./upload/tanda_tangan/'.$tanda_tangan[0]['pegawai_tandatangan'])){
					$x=$pdf->GetX();
					$y=$pdf->GetY();
					$pdf->MultiCell(10,0,$pdf-> Image('./upload/tanda_tangan/'.$tanda_tangan[0]['pegawai_tandatangan'],105,$y,40,15),0,0);
				} 
			}
		}
		
		$pdf->Cell(10,20,'',0,1);
        $pdf->Cell(80,5,'',0,0);
        $pdf->Cell(80,5,$tanda_tangan[0]['pegawai_nama'],0,1);
		
		if($tanda_tangan[0]['pegawai_jabatan']==1 || $tanda_tangan[0]['pegawai_jabatan']==14){
			$pdf->Cell(80,5,'',0,0);
			$pdf->Cell(80,5,'',0,1);
		} else {
			$pdf->Cell(80,5,'',0,0);
			$pdf->Cell(80,5,$tanda_tangan[0]['pangkat'].", Gol. ".$tanda_tangan[0]['pegawai_golongan'],0,1);
			$pdf->Cell(80,5,'',0,0);
			$pdf->Cell(80,5,'NIP.'. $tanda_tangan[0]['pegawai_nip'],0,1);
		} 
		
		//atur posisi 1.5 cm dari bawah
		$pdf->SetY(-15);
		//buat garis horizontal
		$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
		//Arial italic 9
		$pdf->SetFont('Arial','I',9);
		//nomor halaman
		// $pdf->Cell(0,10,'Tanda Tangan Digital pada surat ini menggunakan Layanan BSRE',0,0,'R');
	
	
		
		// membuat halaman baru
		$pdf->AddPage();

		$pdf->SetFont('Arial','',8);
		$pdf->Cell(10,40,'',0,1);
		$pdf->Cell(80,4,'','LTR',0);
		$pdf->Cell(5,4,'I.','LT',0);
		$pdf->Cell(30,4,'Berangkat dari','T',0);
		$pdf->Cell(45,4,': Kendari','TR',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'(Tempat Kedudukan)',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,': '.date("d-m-Y", strtotime($data[0]['telaah_tanggalberangkat'])),'R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'','R',1);
		
		
		$start_x=$pdf->GetX(); //initial x (start of column position)
		
		$current_y = $pdf->GetY();
		$current_x = $pdf->GetX();
		$cell_width = 5;  
		$cell_height=4;    
		$text=str_repeat(' ',400);
		$pdf->MultiCell(80,$cell_height,"".$text,'LR'); 
		
		$current_x+=85;                           
		$pdf->SetXY($current_x, $current_y);  
		$pdf->MultiCell(70,$cell_height,'',''); 
		
		$current_x+=70;                           
		$pdf->SetXY($current_x, $current_y);  
		$pdf->MultiCell(5,$cell_height,"",'R'); 
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
			
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
	
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'_________________________________','R',1,'C');
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'','BR',1,'C');
		
		
		//baris 2
		if(($data[0]['telaah_kategori']==1 && $data[0]['jenis_skpd']!=7) || ($data[0]['telaah_kategori']==2) || ($data[0]['jenis_skpd']==3)){
			if($data[0]['telaah_domainperjalanan']==1 || $data[0]['telaah_domainperjalanan']==2 ){
				$pdf->SetWidths(array(5,30,45,5,30,45));
				$border = array('LT','T','T','LT','T','TR');
				$align = array('','','','','','');
				$caption = array('I.','Tiba Di',': '.$data[0]['kabupaten_kota'],'','Berangkat dari',': '.$data[0]['kabupaten_kota']);
				$pdf->Row($caption, $border, $align);
			} else {
				$pdf->Cell(5,4,'II.','LT',0);
				$pdf->Cell(30,4,'Tiba Di','T',0);
				$pdf->Cell(45,4,':','T',0);
				$pdf->Cell(5,4,'','LT',0);
				$pdf->Cell(30,4,'Berangkat dari','T',0);
				$pdf->Cell(45,4,':','TR',1);
			}
		} else {
			$pdf->Cell(5,4,'II.','LT',0);
			$pdf->Cell(30,4,'Tiba Di','T',0);
			$pdf->Cell(45,4,':','T',0);
			$pdf->Cell(5,4,'','LT',0);
			$pdf->Cell(30,4,'Berangkat dari','T',0);
			$pdf->Cell(45,4,':','TR',1);
		}
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,': '.date("d-m-Y", strtotime($data[0]['telaah_tanggalberangkat'])),'R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Ke',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Jabatan',0,0);
		$pdf->Cell(45,4,':','R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Jabatan',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'_______________________________','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'_______________________________','R',1,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'','R',1,'C');
		
		//baris 3
		$lokasi_tujuan = $this->m_lokasi_tujuan->get2($telaah_id);
		
		$pdf->Cell(5,4,'III.','LT',0);
		$pdf->Cell(30,4,'Tiba Di','T',0);
		$pdf->Cell(45,4,':','T',0);
		$pdf->Cell(5,4,'','LT',0);
		$pdf->Cell(30,4,'Berangkat dari','T',0);
		$pdf->Cell(45,4,':','TR',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,':','R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Ke',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Jabatan',0,0);
		$pdf->Cell(45,4,':','R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Jabatan',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'_______________________________','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'_______________________________','R',1,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'','R',1,'C');
		
		//baris 4
		$lokasi_tujuan = $this->m_lokasi_tujuan->get2($telaah_id);
		
		$pdf->Cell(5,4,'IV.','LT',0);
		$pdf->Cell(30,4,'Tiba Di','T',0);
		$pdf->Cell(45,4,':','T',0);
		$pdf->Cell(5,4,'','LT',0);
		$pdf->Cell(30,4,'Berangkat dari','T',0);
		$pdf->Cell(45,4,':','TR',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,':','R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Ke',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Jabatan',0,0);
		$pdf->Cell(45,4,':','R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Jabatan',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'_______________________________','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'_______________________________','R',1,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'','R',1,'C');
		
		//baris 5
		
		/*if($data[0]['telaah_domainperjalanan']==1 || $data[0]['telaah_domainperjalanan']==2 ){
			$pdf->SetWidths(array(5,30,45,5,30,45));
			$border = array('LT','T','T','LT','T','TR');
			$align = array('','','','','','');
			$caption = array('III','Tiba Di',': '.$lokasi_tujuan[1]['kabupaten_kota'],'','Berangkat dari',': '.$lokasi_tujuan[1]['kabupaten_kota']);
			$pdf->Row($caption, $border, $align);
		} else {*/
		/*	$pdf->Cell(5,4,'V','LT',0);
			$pdf->Cell(30,4,'Tiba Di','T',0);
			$pdf->Cell(45,4,':','T',0);
			$pdf->Cell(5,4,'','LT',0);
			$pdf->Cell(30,4,'Berangkat dari','T',0);
			$pdf->Cell(45,4,':','TR',1);
		//}
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,':','R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Ke',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Jabatan',0,0);
		$pdf->Cell(45,4,':','R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Jabatan',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'_______________________________','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'_______________________________','R',1,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'','R',1,'C');
		
		//baris 5
		if($data[0]['skpd_id']==38){
		/*if($data[0]['telaah_domainperjalanan']==1 || $data[0]['telaah_domainperjalanan']==2 ){
			$pdf->SetWidths(array(5,30,45,5,30,45));
			$border = array('LT','T','T','LT','T','TR');
			$align = array('','','','','','');
			$caption = array('III','Tiba Di',': '.$lokasi_tujuan[1]['kabupaten_kota'],'','Berangkat dari',': '.$lokasi_tujuan[1]['kabupaten_kota']);
			$pdf->Row($caption, $border, $align);
		} else {*/
			/*$pdf->Cell(5,4,'VI','LT',0);
			$pdf->Cell(30,4,'Tiba Di','T',0);
			$pdf->Cell(45,4,':','T',0);
			$pdf->Cell(5,4,'','LT',0);
			$pdf->Cell(30,4,'Berangkat dari','T',0);
			$pdf->Cell(45,4,':','TR',1);
		//}
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,':','R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Ke',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Jabatan',0,0);
		$pdf->Cell(45,4,':','R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Jabatan',0,0);
		$pdf->Cell(45,4,':','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(5,4,'','L',0);
		//$pdf->Cell(75,4,$data[0]['pegawai_nama'],'R',0,'C');
		$pdf->Cell(75,4,'_______________________________','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		//$pdf->Cell(75,4,$data[0]['pegawai_nama'],'R',1,'C');
		$pdf->Cell(75,4,'_______________________________','R',1,'C');
		$pdf->Cell(5,4,'','L',0);
		//$pdf->Cell(75,4,'NIP '.$data[0]['pegawai_nip'],'R',0,'C');
		$pdf->Cell(75,4,'','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		//$pdf->Cell(75,4,'NIP '.$data[0]['pegawai_nip'],'R',1,'C');
		$pdf->Cell(75,4,'','R',1,'C');
		}
		
		//baris 6
		
		if(($data[0]['telaah_kategori']==1 && $data[0]['jenis_skpd']!=7) || ($data[0]['telaah_kategori']==2)){
			if($data[0]['telaah_domainperjalanan']==1 || $data[0]['telaah_domainperjalanan']==2 ){
				$pdf->SetWidths(array(5,30,45,80));
				$border = array('LT','T','RT','LRT');
				$align = array('','','','');
				if($data[0]['skpd_id']==38){
					$caption = array('VII.','Tiba Di',': Kendari','Telah diperiksa dengan keterangan bahwa');
				}else {
					$caption = array('VI','Tiba Di',': Kendari','Telah diperiksa dengan keterangan bahwa');
				}
				$pdf->Row($caption, $border, $align);
			} else {
				if($data[0]['skpd_id']==38){
					$pdf->Cell(5,4,'VII.','LT',0);
				}else {
					$pdf->Cell(5,4,'VI.','LT',0);
				}
				$pdf->Cell(30,4,'Tiba Di','T',0);
				$pdf->Cell(45,4,':','RT',0);
				$pdf->Cell(80,4,'Telah diperiksa dengan keterangan bahwa','lRT',1);
			}
		} else {
			if($data[0]['skpd_id']==38){
				$pdf->Cell(5,4,'VII.','LT',0);
			}else {
				$pdf->Cell(5,4,'VI.','LT',0);
			}
			$pdf->Cell(30,4,'Tiba Di','T',0);
			$pdf->Cell(45,4,':','RT',0);
			$pdf->Cell(80,4,'Telah diperiksa dengan keterangan bahwa','lRT',1);
		}
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'(Tempat Kedudukan)',0,0);
		$pdf->Cell(45,4,':','R',0);
		$pdf->Cell(80,4,'perjalanan tersebut diatas telah benar dilakukan','LR',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,': '.date("d-m-Y", strtotime($data[0]['telaah_tanggalkembali'])),'R',0);
		$pdf->Cell(80,4,'atas perintahnya semata-mata untuk kepentingan','LR',1);
		
		$pdf->Cell(80,4,'','L',0);
		$pdf->Cell(80,4,'jabatan dalam waktu yang sesingkat-singkatnya.','LR',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'Pejabat yang memberi perintah','R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'Pejabat yang memberi perintah','R',1);

		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'','R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'','R',1);

		$pdf->SetWidths(array(5,75,5,75));
		$border = array('L','R', 'L', 'R');
		$align = array('','','','');
		$caption = array("",'',"",'');
		$pdf->Row($caption, $border, $align);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'',0,0);
		$pdf->Cell(45,4,'','R',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,"_______________________________",'R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,"_______________________________",'R',1,'C');
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		
		$pdf->Cell(75,4,'','R',1,'C');
			
		if($data[0]['skpd_id']==38){
			$pdf->Cell(5,4,'VIII.','LTB',0);
		} else {
			$pdf->Cell(5,4,'VII.','LTB',0);
		}
		$pdf->Cell(155,4,'Keterangan Lain-lain','RTB',1);
		
		if($data[0]['skpd_id']==38){
			$pdf->Cell(5,4,'IX.','LT',0);
		} else {
			$pdf->Cell(5,4,'VIII.','LT',0);
		}
		
		$pdf->Cell(155,4,'PERHATIAN','RT',1); 
		
		$pdf->MultiCell(160,4,'Pejabat yang berwenang memberi SPPD pegawai yang melakukan Perjalanan Dinas, para pejabat yang mengesahkan tanggal berangkat/tiba, serta bendaharawan bertanggung jawab berdasarkan peraturan - peraturan Keuangan Negara, apabila Negara menderita rugi akibat kesalahan, kelalaian dan kealpaan (Lampiran SK. Menteri Keuangan tanggal 30-4-1974 Nomor B-296/MK/I/1974).',1,'J');
		
		//atur posisi 1.5 cm dari bawah
		$pdf->SetY(-15);
		//buat garis horizontal
		$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
		//Arial italic 9
		$pdf->SetFont('Arial','I',9);
		//nomor halaman
		// $pdf->Cell(0,10,'Tanda Tangan Digital pada surat ini menggunakan Layanan BSRE',0,0,'R');
		
		
		$filename = 'SPPD - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput'])).'.pdf';
		$path = "./upload/doc_TTE/$filename";
		$pdf->Output($path,'F');
    }*/
	
	## Create SPT
	function cetak_spt($telaah_id, $posisi, $kategori_pelaksana, $pegawai_id, $nik, $posisi_penandatangan)
	{
		
		switch($posisi){
			case "1"		:
			case "5"		:
			case "9"		:
			case "6"		:
			case "11"		:
			case "10"		:
			case "7"		: $data = $this->m_laporan->get_pelaksana_opd($telaah_id);
							  $data2 = $this->m_laporan->get_pengikut2($telaah_id);
							  break;
			case "2"		: if($kategori_pelaksana==1){
								## Pelaksana
								$data = $this->m_laporan->get_pelaksana_opd($telaah_id);
								$data2 = $this->m_laporan->get_pengikut2($telaah_id);
							  } else if($kategori_pelaksana==2){
								## Pengikut
								$data = $this->m_laporan->get_pengikut_opd($telaah_id, $pegawai_id);
							  } 
							  break;
			case "3"			: if($kategori_pelaksana==1){
									$data = $this->m_laporan->get_pelaksana_dprd($telaah_id);
								} else if($kategori_pelaksana==2){
									$data = $this->m_laporan->get_pengikut_dprd($telaah_id, $pegawai_id);	
								} 
								break;
			case "4"			: if($kategori_pelaksana==1){
									## Pengikut
									$data = $this->m_laporan->get_pelaksana_opd($telaah_id, $pegawai_id);
									$data2 = $this->m_laporan->get_pengikut2($telaah_id);
								} else if($kategori_pelaksana==2){
									## Pelaksana
									$data = $this->m_laporan->get_pelaksana_opd($telaah_id);
									$data2 = $this->m_laporan->get_pengikut2($telaah_id);
								} 
								  break;
			case "8"			: $data = $this->m_laporan->get_pelaksana_walikota($telaah_id);
								  $data2 = $this->m_laporan->get_pengikut2($telaah_id);
								  break;
		}
		
		$lokasi_tujuan = $this->m_lokasi_tujuan->get2($telaah_id);
		$tanda_tangan = $this->m_pegawai->get_pegawai_nik($posisi_penandatangan,$nik,'');
		
        $pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(25, 3.175, 25);
		$pdf->SetAutoPageBreak(false);
        // membuat halaman baru
        $pdf->AddPage();
		$pdf->SetTopMargin(25);
		if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']!=11){
			$dinas_kesehatan = $this->m_laporan->get_dinas_kesehatan();
			$pdf-> Image('./upload/kop_surat/'.$dinas_kesehatan[0]['kop_surat'],20,16,170,30);
		} else if($data[0]['telaah_kategori']==2){
			if($kategori_pelaksana==1){
				if($data[0]['telaah_domainperjalanan']==3){
					$pdf-> Image('./upload/kop_surat/'.$data[0]['kop_surat'],20,16,170,30);
				} else {
					$pdf-> Image('./assets2/dist/img/garuda.png',90,10,30,30);
				}
			} else if($kategori_pelaksana==2) {	
				$pdf-> Image('./upload/kop_surat/'.$data[0]['kop_surat'],20,16,170,30);
			}
		} else if($posisi=="camat" || $posisi==10 || $posisi==8 || $posisi==4){
			$pdf-> Image('./assets2/dist/img/garuda.png',90,10,30,30);
		} else if($data[0]['telaah_kategori']==5){
			$sekda = $this->m_laporan->get_kop_sekda();
			$pdf-> Image('./upload/kop_surat/'.$sekda[0]['kop_surat'],20,15,170,30);
		} else {
			$pdf-> Image('./upload/kop_surat/'.$data[0]['kop_surat'],20,16,170,30);
		}
		
		if(($posisi==2 && $kategori_pelaksana==1 && $data[0]['telaah_domainperjalanan']==1) 
			|| ($posisi==2 && $kategori_pelaksana==1 && $data[0]['telaah_domainperjalanan']==2) 
			|| ($posisi==4 && $kategori_pelaksana==2) 
			|| $posisi=="camat" || $posisi==10 || $posisi==8 || $posisi==4){
			$pdf->SetFont('Times','B',26);
			$pdf->Cell(10,50,'',0,1);
			$pdf->Cell(160,7,'WALIKOTA KENDARI',0,1,'C');
			
			$pdf->SetFont('Times','BU',16);
			$pdf->Cell(10,10,'',0,1);
		
		} else {
			$pdf->SetFont('Times','BU',16);
			$pdf->Cell(10,45,'',0,1);
		}
		
        $pdf->Cell(160,7,'SURAT PERINTAH TUGAS',0,1,'C');
        // Memberikan space kebawah agar tidak terlalu rapat
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(45,6,'',0,0);
        $pdf->Cell(115,6,'No.',0,1);
		
        $pdf->Cell(10,4,'',0,1);
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Dari',0,0);
		
		if(($posisi==2 && $kategori_pelaksana==1 && $data[0]['telaah_domainperjalanan']==1)
			|| ($posisi==2 && $kategori_pelaksana==1 && $data[0]['telaah_domainperjalanan']==2) 
			|| ($posisi==4 && $kategori_pelaksana==2) 
			|| $posisi=="camat" || $posisi==10 || $posisi==8 || $posisi==4){
			$pdf->Cell(140,6,': Walikota Kendari',0,1);
		
		} else {
			$skpd_nama = strtolower($data[0]['skpd_nama']);
			$skpd_nama2 = ucwords($skpd_nama);
			if($data[0]['jenis_skpd'] == 2 && $data[0]['telaah_kategori']!=10){
				$pdf->Cell(140,6,': SEKRETARIS DPRD KOTA KENDARI',0,1);
			} else if($data[0]['jenis_skpd'] == 2 && $data[0]['telaah_kategori']==10){
				$pdf->Cell(140,6,': Walikota Kendari',0,1);
			} else if($data[0]['jenis_skpd'] == 7 && $data[0]['telaah_kategori']==1){
				$pdf->Cell(140,6,': Kepala Dinas Kesehatan Kota Kendari',0,1);
			} else if($data[0]['jenis_skpd'] == 5 && $data[0]['telaah_kategori']==5){
				$pdf->Cell(140,6,': Sekretariat Daerah Kota Kendari',0,1);
			} else {
				if($data[0]['skpd_id']==37){
					$pdf->Cell(140,6,': Direktur '.$skpd_nama2,0,1);
				}else{	
					if($tanda_tangan[0]['pegawai_jabatan']==3){
						$pdf->Cell(140,6,': SEKRETARIS DAERAH ',0,1);
					} else {	
						$pdf->Cell(140,6,': Kepala '.$skpd_nama2,0,1);
					}
				}
			} 
		}
		
        
		
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
		
        $pdf->SetFont('Times','B',14);
        $pdf->Cell(160,7,'MEMERINTAHKAN',0,1,'C');
		
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,4,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Kepada',0,0);
        $pdf->Cell(3,6,':',0,0);
        $pdf->Cell(137,6,'',0,1);
		
		// Pelaksana
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'',0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(5,6,'1.',0,0);
        $pdf->Cell(35,6,'Nama',0,0);
		$pdf->Cell(2,6,':',0,0);
        $pdf->Cell(95,6,$data[0]['pegawai_nama'],0,1);
		
		if($data[0]['pegawai_jabatan']==1 || $data[0]['pegawai_jabatan']==15 || $data[0]['pegawai_jabatan']==16 
			|| $data[0]['pegawai_nip']==0 || $data[0]['pegawai_nip']==00 || $data[0]['pegawai_nip']==000 ){
				
		} else {
			$pdf->SetFont('Times','',10);
			$pdf->Cell(20,6,'',0,0);
			$pdf->Cell(3,6,'',0,0);
			$pdf->Cell(5,6,'',0,0);
			$pdf->Cell(35,6,'Pangkat/Golongan',0,0);
			$pdf->Cell(2,6,':',0,0);
			$pdf->Cell(95,6,$data[0]['pangkat']. ', Gol. ' .$data[0]['pegawai_golongan'],0,1);
			
			$pdf->Cell(20,6,'',0,0);
			$pdf->Cell(3,6,'',0,0);
			$pdf->Cell(5,6,'',0,0);
			$pdf->Cell(35,6,'NIP',0,0);
			$pdf->Cell(2,6,':',0,0);
			$pdf->Cell(95,6,$data[0]['pegawai_nip'],0,1);
			
		}
		
        $pdf->Cell(20,6,'',0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(5,6,'',0,0);
        $pdf->Cell(35,6,'Jabatan',0,0);
        $pdf->Cell(2,6,':',0,0);
        $pdf->MultiCell(95,6,$data[0]['pegawai_namajabatan'],0,1);
		
		// Pengikut
		// if(($posisi=="2" && $kategori_pelaksana==1)
			// ||($posisi=="2" && $kategori_pelaksana==2)){
			
		// } else { 
			$no = 2;
			foreach($data2 as $v) {
				$y=$pdf->GetY();
				if($y>304){
					$pdf->AddPage();
					$data4['telaah_id'] = $telaah_id;
					$data4['telaah_halaman_tte'] = 2;
					$this->m_telaah->update($data4);
				}
					
				$pdf->SetFont('Times','',10);
				$pdf->Cell(20,6,'',0,0);
				$pdf->Cell(3,6,'',0,0);
				$pdf->Cell(5,6,$no++.'.',0,0);
				$pdf->Cell(35,6,'Nama',0,0);
				$pdf->Cell(2,6,':',0,0);
				$pdf->Cell(95,6,$v->pegawai_nama,0,1);
				
				if($v->pegawai_jabatan==16){
					
				} else {
					if($v->pangkat){
						$pdf->SetFont('Times','',10);
						$pdf->Cell(20,6,'',0,0);
						$pdf->Cell(3,6,'',0,0);
						$pdf->Cell(5,6,'',0,0);
						$pdf->Cell(35,6,'Pangkat/Golongan',0,0);
						$pdf->Cell(2,6,':',0,0);
						$pdf->Cell(95,6,$v->pangkat. ' - Gol. ' .$v->pegawai_golongan,0,1);
					} 
					
					if($v->pegawai_nip!="000"){
						$pdf->Cell(20,6,'',0,0);
						$pdf->Cell(3,6,'',0,0);
						$pdf->Cell(5,6,'',0,0);
						$pdf->Cell(35,6,'NIP',0,0);
						$pdf->Cell(2,6,':',0,0);
						$pdf->Cell(95,6,$v->pegawai_nip,0,1);
					} 
				}
				
				$pdf->Cell(20,6,'',0,0);
				$pdf->Cell(3,6,'',0,0);
				$pdf->Cell(5,6,'',0,0);
				$pdf->Cell(35,6,'Jabatan',0,0);
				$pdf->Cell(2,6,':',0,0);
				$pdf->MultiCell(95,6,$v->pegawai_namajabatan,0,1);
				
			}
		
		$jumlah_pengikut = count($data2);
		if($jumlah_pengikut>=3 && $jumlah_pengikut<=8){
			$pdf->AddPage();
			$data4['telaah_id'] = $telaah_id;
			$data4['telaah_halaman_tte'] = 2;
			$this->m_telaah->update($data4);
		} 
		// }
		// Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
		
		$y=$pdf->GetY();
		if($y>215){
			$pdf->AddPage();
			$data4['telaah_id'] = $telaah_id;
			$data4['telaah_halaman_tte'] = 2;
			$this->m_telaah->update($data4);	
		}
		
		$y=$pdf->GetY();
		if($y>320){
			$pdf->AddPage();
			$data4['telaah_id'] = $telaah_id;
			$data4['telaah_halaman_tte'] = 2;
			$this->m_telaah->update($data4);
		}
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Untuk',0,0);
        // $pdf->Cell(20,6,count($data2),0,0);
        $pdf->Cell(3,6,':',0,0);
		
		//7
		$start_date = new DateTime($data[0]['telaah_tanggalberangkat']);
		$end_date = new DateTime($data[0]['telaah_tanggalkembali']);
		$interval = $start_date->diff($end_date);
			
		if($data[0]['telaah_hari']==0){
			$telaah_perihal = $data[0]['telaah_perihal'].' Di '.$data[0]['telaah_kantortujuan'].' Selama '.($interval->days + 1).' hari dari tanggal '. 
						  $this->tgl_indo($data[0]['telaah_tanggalberangkat']).' s/d '. $this->tgl_indo($data[0]['telaah_tanggalkembali']).'.';
		} else {
			$telaah_perihal = $data[0]['telaah_perihal'].' Di '.$data[0]['telaah_kantortujuan'].' Selama '.$data[0]['telaah_hari'].' hari dari tanggal '. 
						  $this->tgl_indo($data[0]['telaah_tanggalberangkat']).' s/d '. $this->tgl_indo($data[0]['telaah_tanggalkembali']).'.';
		}
		
		$pdf->SetFont('Times','B',10);
        $pdf->MultiCell(137,6,$telaah_perihal,0,'J');
		
		// Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
		
		$y=$pdf->GetY();
		if($y>329){
			$pdf->AddPage();
			$data4['telaah_id'] = $telaah_id;
			$data4['telaah_halaman_tte'] = 2;
			$this->m_telaah->update($data4);
		}
		
		$pdf->SetFont('Times','',10);
		if($data[0]['jenis_skpd'] == 2){
			if($posisi==10){
				$pdf->MultiCell(160,6,'Demikian Surat Tugas ini diberikan kepada yang bersangkutan untuk dilaksanakan dengan penuh rasa tanggung jawab.',0,'J');
			
				$y=$pdf->GetY();
				if($y>270){
					$pdf->AddPage();
					$data4['telaah_id'] = $telaah_id;
					$data4['telaah_halaman_tte'] = 2;
					$this->m_telaah->update($data4);
				}
			
				$pdf->Cell(100,6,'',0,0);
				$pdf->Cell(50,6,'Ditetapkan Di Kendari',0,1);
				
				$pdf->SetFont('Times','',10);
				$pdf->Cell(100,6,'',0,0);
				$pdf->Cell(50,6,'Pada Tanggal : '.$this->tgl_indo($data[0]['telaah_tanggalspt']),0,1);
			} else {
				$pdf->MultiCell(160,6,'Demikian Surat Perintah Tugas ini diberikan untuk dilaksanakan dengan penuh rasa tanggung jawab dan apabila Surat Perintah Tugas Ini tidak dijalankan sesuai aturan Perundang-Undangan yang berlaku, maka yang bersangkutan dan/atau penerima Surat Perintah Tugas ini yang akan bertanggung jawab.',0,'J');
			
				$pdf->Cell(10,4,'',0,1);
				
				$y=$pdf->GetY();
				if($y>270){
					$pdf->AddPage();
					$data4['telaah_id'] = $telaah_id;
					$data4['telaah_halaman_tte'] = 2;
					$this->m_telaah->update($data4);
				}
			
				$pdf->SetFont('Times','',10);
				$pdf->Cell(100,6,'',0,0);
				$pdf->Cell(50,6,'Kendari, '.$this->tgl_indo($data[0]['telaah_tanggalspt']),0,1);
			}
			
		} else {
			$pdf->MultiCell(160,6,'Demikian Surat Tugas ini diberikan kepada yang bersangkutan untuk dilaksanakan dengan penuh rasa tanggung jawab.',0,'J');
			
			$y=$pdf->GetY();
			if($y>270){
				$pdf->AddPage();
				$data4['telaah_id'] = $telaah_id;
				$data4['telaah_halaman_tte'] = 2;
				$this->m_telaah->update($data4);
			}
		
			$pdf->Cell(100,6,'',0,0);
			$pdf->Cell(50,6,'Ditetapkan Di Kendari',0,1);
			
			$pdf->SetFont('Times','',10);
			$pdf->Cell(100,6,'',0,0);
			$pdf->Cell(50,6,'Pada Tanggal : '.$this->tgl_indo($data[0]['telaah_tanggalspt']),0,1);
		} 
        
		$pdf->SetFont('Times','',10);
		
		if($tanda_tangan[0]['pegawai_jabatan']==2){
			$pdf->Cell(100,6,'',0,0);
			$pdf->MultiCell(60,6,'Plh. Sekretaris Daerah Kota Kendari',0,1);
			$pdf->Cell(100,6,'',0,0);
			$pdf->MultiCell(60,6,'ASISTEN I',0,1);
		} else if($tanda_tangan[0]['pegawai_jabatan']==17){
			$pdf->Cell(100,6,'',0,0);
			$pdf->MultiCell(60,6,'Plh. Sekretaris Daerah Kota Kendari',0,1);
			$pdf->Cell(100,6,'',0,0);
			$pdf->MultiCell(60,6,'ASISTEN II',0,1);
		} else if($tanda_tangan[0]['pegawai_jabatan']==18){
			$pdf->Cell(100,6,'',0,0);
			$pdf->MultiCell(60,6,'Plh. Sekretaris Daerah Kota Kendari',0,1);
			$pdf->Cell(100,6,'',0,0);
			$pdf->MultiCell(60,6,'ASISTEN III',0,1);
		} else if($tanda_tangan[0]['pegawai_jabatan']==7){
			if($data[0]['jenis_skpd']==1 || $data[0]['jenis_skpd']==10){
				$pdf->Cell(100,6,'',0,0);
				$pdf->MultiCell(60,6,'Pelaksana Kepala Dinas',0,1);
			} else if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']==1){
				$pdf->Cell(100,6,'',0,0);
				$pdf->MultiCell(60,6,'Pelaksana Kepala Dinas',0,1);
			} else if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']==11){
				$pdf->Cell(100,6,'',0,0);
				$pdf->MultiCell(60,6,'Pelaksana Kepala Puskesmas',0,1);
			} 
			$pdf->Cell(100,6,'',0,0);
			$pdf->MultiCell(60,6,$tanda_tangan[0]['pegawai_namajabatan'],0,1);
		} else {
			$pdf->Cell(100,6,'',0,0);
			$pdf->MultiCell(60,6,$tanda_tangan[0]['pegawai_namajabatan'],0,1);
		} 
			
		$pdf->Cell(10,2,'',0,1);	
		
		## SETTING KOORDINAT Y
		
		
		## SETTING KOORDINAT Y
		if($kategori_pelaksana == 1){
			$data3['telaah_tte_y2'] = $pdf->GetY()+1;
			$data3['telaah_id'] = $telaah_id;
			$data3['telaah_ttdspt'] = $tanda_tangan[0]['pegawai_id'];
			$this->m_telaah->update($data3);	
		} else {	
			$data2['telaah_tte_y2'] = $pdf->GetY()+1;
			$data2['telaah_id'] = $telaah_id;
			$data2['pegawai_id'] = $pegawai_id;
			$this->m_pengikut->update($data2);	
		}
		
		$pdf->Cell(10,20,'',0,1);	
		$pdf->Cell(100,6,'',0,0);
		$pdf->Cell(60,6,$tanda_tangan[0]['pegawai_nama'],0,1);
		
		if($tanda_tangan[0]['pegawai_jabatan']==1){
			
		} else {	
			$pdf->Cell(100,6,'',0,0);
			$pdf->Cell(60,6,$tanda_tangan[0]['pangkat'].", Gol. ".$tanda_tangan[0]['pegawai_golongan'],0,1);
			$pdf->Cell(100,6,'',0,0);
			$pdf->Cell(60,6,'NIP.'. $tanda_tangan[0]['pegawai_nip'],0,1);
		}
		
		$pdf->Cell(10,7,'',0,1);
		if($this->ion_auth->user()->row()->jenis_skpd == 2){
			if($this->uri->segment(5)==10){
				
				$pdf->SetFont('Times','',10);
				$y=$pdf->GetY();
				if($y>329){
					$pdf->AddPage();
					$data4['telaah_id'] = $telaah_id;
					$data4['telaah_halaman_tte'] = 2;
					$this->m_telaah->update($data4);
				}
				
				$pdf->Cell(20,6,'Tembusan Yth:',0,0);
				$pdf->Cell(3,6,':',0,0);
				$pdf->Cell(137,6,'',0,1);
				
				$pdf->SetFont('Times','',10);
				$pdf->Cell(20,6,'',0,0);
				$pdf->Cell(3,6,'',0,0);
				$skpd_nama = strtolower($data[0]['skpd_nama']);
				$skpd_nama2 = ucwords($skpd_nama);
				//$pdf->Cell(137,6,'1. Kepala '.$skpd_nama2.' di Kendari',0,1);
				$pdf->Cell(137,6,'1. Kepala Badan Kepegawaian dan Pengembangan SDM Kota Kendari di Kendari',0,1);
				
				$pdf->SetFont('Times','',10);
				$pdf->Cell(20,6,'',0,0);
				$pdf->Cell(3,6,'',0,0);
				$pdf->Cell(137,6,'2. Bagian Organisasi dan Pemberdayaan Aparatur Kota Kendari di Kendari',0,1);
			}
		} else {
			$pdf->SetFont('Times','',10);
			$y=$pdf->GetY();
			if($y>329){
				$pdf->AddPage();
				$data4['telaah_id'] = $telaah_id;
				$data4['telaah_halaman_tte'] = 2;
				$this->m_telaah->update($data4);
			}
			$pdf->Cell(20,6,'Tembusan Yth:',0,0);
			$pdf->Cell(3,6,':',0,0);
			$pdf->Cell(137,6,'',0,1);
			
			if(($this->uri->segment(5)=="kadis" && $this->uri->segment(6)==1) || ( $this->uri->segment(5)=="sekda" && $this->uri->segment(6)==1)){
						
				$pdf->SetFont('Times','',10);
				$pdf->Cell(20,6,'',0,0);
				$pdf->Cell(3,6,'',0,0);
				$skpd_nama = strtolower($data[0]['skpd_nama']);
				$skpd_nama2 = ucwords($skpd_nama);
				$pdf->Cell(137,6,'1. Kepala '.$skpd_nama2.' di Kendari',0,1);
				
				$pdf->SetFont('Times','',10);
				$pdf->Cell(20,6,'',0,0);
				$pdf->Cell(3,6,'',0,0);
				$pdf->Cell(137,6,'2. Arsip',0,1);
				
			} else {
				
				$pdf->SetFont('Times','',10);
				$pdf->Cell(20,6,'',0,0);
				$pdf->Cell(3,6,'',0,0);
				$skpd_nama = strtolower($data[0]['skpd_nama']);
				$skpd_nama2 = ucwords($skpd_nama);
				//$pdf->Cell(137,6,'1. Kepala '.$skpd_nama2.' di Kendari',0,1);
				$pdf->Cell(137,6,'1. Kepala Badan Kepegawaian dan Pengembangan SDM Kota Kendari di Kendari',0,1);
				
				$pdf->SetFont('Times','',10);
				$pdf->Cell(20,6,'',0,0);
				$pdf->Cell(3,6,'',0,0);
				$pdf->Cell(137,6,'2. Bagian Organisasi dan Pemberdayaan Aparatur Kota Kendari di Kendari',0,1);
			}
			
			
		} 
		
		
        // Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,10,'',0,1);
		
		$pdf->SetFont('Times','BI',10);
		//$pdf->MultiCell(160, 5, 'Catatan : Jika Walikota berhalangan atau berada diluar Daerah maka penandatanganan SPT dapat dilakukan oleh Wakil Walikota atau Sekretaris Daerah jika Wakil Walikota juga berhalangan atau berada diluar daerah.', 0,'J');
        
		//atur posisi 1.5 cm dari bawah
		$pdf->SetY(-15);
		//buat garis horizontal
		$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
		//Arial italic 9
		$pdf->SetFont('Arial','I',9);
		//nomor halaman
		$pdf->Cell(0,10,'Dokumen ini ditandatangani secara elektronik menggunakan Layanan BSrE',0,0,'R');
		
		$filename = 'SPT - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput'])).'.pdf';
		$path = "./upload/doc_dummy/$filename";
		$pdf->Output($path,'F');
    }
	
	## Create SPT
	/*function cetak_spt2($telaah_id, $posisi, $kategori_pelaksana, $pegawai_id, $nik, $posisi_penandatangan)
	{
		
		switch($posisi){
			case "1"		:
			case "5"		:
			case "9"		:
			case "6"		:
			case "11"		:
			case "10"		:
			case "7"		: $data = $this->m_laporan->get_pelaksana_opd($telaah_id);
								  $data2 = $this->m_laporan->get_pengikut2($telaah_id);
								  break;
			case "2"			: if($kategori_pelaksana==1){
									## Pelaksana
									$data = $this->m_laporan->get_pelaksana_opd($telaah_id);
									$data2 = $this->m_laporan->get_pengikut2($telaah_id);
								} else if($kategori_pelaksana==2){
									## Pengikut
									$data = $this->m_laporan->get_pengikut_opd($telaah_id, $pegawai_id);
								} 
								break;
			case "3"			: if($kategori_pelaksana==1){
									$data = $this->m_laporan->get_pelaksana_dprd($telaah_id);
								} else if($kategori_pelaksana==2){
									$data = $this->m_laporan->get_pengikut_dprd($telaah_id, $pegawai_id);	
								} 
								break;
			case "4"			: if($kategori_pelaksana==1){
									## Pengikut
									$data = $this->m_laporan->get_pelaksana_opd($telaah_id, $pegawai_id);
									$data2 = $this->m_laporan->get_pengikut2($telaah_id);
								} else if($kategori_pelaksana==2){
									## Pelaksana
									$data = $this->m_laporan->get_pelaksana_opd($telaah_id);
									$data2 = $this->m_laporan->get_pengikut2($telaah_id);
								} 
								  break;
			case "8"			: $data = $this->m_laporan->get_pelaksana_walikota($telaah_id);
								  $data2 = $this->m_laporan->get_pengikut2($telaah_id);
								  break;
		}
		
		$lokasi_tujuan = $this->m_lokasi_tujuan->get2($telaah_id);
		$tanda_tangan = $this->m_pegawai->get_pegawai_nik($posisi_penandatangan,$nik,'');
		
        $pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(25, 3.175, 25);
		$pdf->SetAutoPageBreak(false);
        // membuat halaman baru
        $pdf->AddPage();
		$pdf->SetTopMargin(25);
		if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']!=11){
			$dinas_kesehatan = $this->m_laporan->get_dinas_kesehatan();
			$pdf-> Image('./upload/kop_surat/'.$dinas_kesehatan[0]['kop_surat'],20,16,170,30);
		} else if($data[0]['telaah_kategori']==2){
			if($kategori_pelaksana==1){
				if($data[0]['telaah_domainperjalanan']==3){
					$pdf-> Image('./upload/kop_surat/'.$data[0]['kop_surat'],20,16,170,30);
				} else {
					$pdf-> Image('./assets2/dist/img/garuda.png',90,10,30,30);
				}
			} else if($kategori_pelaksana==2) {	
				$pdf-> Image('./upload/kop_surat/'.$data[0]['kop_surat'],20,16,170,30);
			}
		} else if($posisi=="camat" || $posisi==10 || $posisi==8 || $posisi==4){
			$pdf-> Image('./assets2/dist/img/garuda.png',90,10,30,30);
		} else if($data[0]['telaah_kategori']==5){
			$sekda = $this->m_laporan->get_kop_sekda();
			$pdf-> Image('./upload/kop_surat/'.$sekda[0]['kop_surat'],20,15,170,30);
		} else {
			$pdf-> Image('./upload/kop_surat/'.$data[0]['kop_surat'],20,16,170,30);
		}
		
		if(($posisi=="2" && $kategori_pelaksana==2) 
			|| ($posisi=="4" && $kategori_pelaksana==2) 
			|| $posisi==10 || $posisi=="camat"){
			$pdf->SetFont('Times','B',26);
			$pdf->Cell(10,40,'',0,1);
			$pdf->Cell(160,7,'WALIKOTA KENDARI',0,1,'C');
			
			$pdf->SetFont('Times','BU',16);
			$pdf->Cell(10,10,'',0,1);
		
		} else {
			$pdf->SetFont('Times','BU',16);
			$pdf->Cell(10,45,'',0,1);
		}
		
        $pdf->Cell(160,7,'SURAT PERINTAH TUGAS',0,1,'C');
        // Memberikan space kebawah agar tidak terlalu rapat
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(45,6,'',0,0);
        $pdf->Cell(115,6,'No.',0,1);
		
        $pdf->Cell(10,4,'',0,1);
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Dari',0,0);
		
		if(($posisi=="2" && $kategori_pelaksana==2) 
			|| ($posisi=="4" && $kategori_pelaksana==2)
			|| $posisi=="camat"){
			$pdf->Cell(140,6,': WALIKOTA KENDARI',0,1);
		
		} else {
			$skpd_nama = strtolower($data[0]['skpd_nama']);
			$skpd_nama2 = ucwords($skpd_nama);
			if($data[0]['jenis_skpd'] == 2 && $data[0]['telaah_kategori']!=10){
				$pdf->Cell(140,6,': SEKRETARIS DPRD KOTA KENDARI',0,1);
			} else if($data[0]['jenis_skpd'] == 2 && $data[0]['telaah_kategori']==10){
				$pdf->Cell(140,6,': Walikota Kendari',0,1);
			} else if($data[0]['jenis_skpd'] == 7 && $data[0]['telaah_kategori']==1){
				$pdf->Cell(140,6,': Kepala Dinas Kesehatan Kota Kendari',0,1);
			} else if($data[0]['jenis_skpd'] == 5 && $data[0]['telaah_kategori']==5){
				$pdf->Cell(140,6,': Sekretariat Daerah Kota Kendari',0,1);
			} else {
				if($data[0]['skpd_id']==37){
					$pdf->Cell(140,6,': Direktur '.$skpd_nama2,0,1);
				}else{	
					if($tanda_tangan[0]['pegawai_jabatan']==3){
						$pdf->Cell(140,6,': SEKRETARIS DAERAH ',0,1);
					} else {	
						$pdf->Cell(140,6,': Kepala '.$skpd_nama2,0,1);
					}
				}
			} 
		}
		
        
		
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
		
        $pdf->SetFont('Times','B',14);
        $pdf->Cell(160,7,'MEMERINTAHKAN',0,1,'C');
		
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,4,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Kepada',0,0);
        $pdf->Cell(3,6,':',0,0);
        $pdf->Cell(137,6,'',0,1);
		
		// Pelaksana
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'',0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(5,6,'1.',0,0);
        $pdf->Cell(35,6,'Nama',0,0);
		$pdf->Cell(2,6,':',0,0);
        $pdf->Cell(95,6,$data[0]['pegawai_nama'],0,1);
		
		if($data[0]['pegawai_jabatan']==1 || $data[0]['pegawai_jabatan']==15 || $data[0]['pegawai_jabatan']==16 
			|| $data[0]['pegawai_nip']==0 || $data[0]['pegawai_nip']==00 || $data[0]['pegawai_nip']==000 ){
				
		} else {
			$pdf->SetFont('Times','',10);
			$pdf->Cell(20,6,'',0,0);
			$pdf->Cell(3,6,'',0,0);
			$pdf->Cell(5,6,'',0,0);
			$pdf->Cell(35,6,'Pangkat/Golongan',0,0);
			$pdf->Cell(2,6,':',0,0);
			$pdf->Cell(95,6,$data[0]['pangkat']. ', Gol. ' .$data[0]['pegawai_golongan'],0,1);
			
			$pdf->Cell(20,6,'',0,0);
			$pdf->Cell(3,6,'',0,0);
			$pdf->Cell(5,6,'',0,0);
			$pdf->Cell(35,6,'NIP',0,0);
			$pdf->Cell(2,6,':',0,0);
			$pdf->Cell(95,6,$data[0]['pegawai_nip'],0,1);
			
		}
		
        $pdf->Cell(20,6,'',0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(5,6,'',0,0);
        $pdf->Cell(35,6,'Jabatan',0,0);
        $pdf->Cell(2,6,':',0,0);
        $pdf->MultiCell(95,6,$data[0]['pegawai_namajabatan'],0,1);
		
		// Pengikut
		// if(($posisi=="2" && $kategori_pelaksana==1)
			// ||($posisi=="2" && $kategori_pelaksana==2)){
			
		// } else { 
			$no = 2;
			foreach($data2 as $v) {
				$pdf->SetFont('Times','',10);
				$pdf->Cell(20,6,'',0,0);
				$pdf->Cell(3,6,'',0,0);
				$pdf->Cell(5,6,$no++.'.',0,0);
				$pdf->Cell(35,6,'Nama',0,0);
				$pdf->Cell(2,6,':',0,0);
				$pdf->Cell(95,6,$v->pegawai_nama,0,1);
				
				if($v->pegawai_jabatan==16){
					
				} else {
					if($v->pangkat){
						$pdf->SetFont('Times','',10);
						$pdf->Cell(20,6,'',0,0);
						$pdf->Cell(3,6,'',0,0);
						$pdf->Cell(5,6,'',0,0);
						$pdf->Cell(35,6,'Pangkat/Golongan',0,0);
						$pdf->Cell(2,6,':',0,0);
						$pdf->Cell(95,6,$v->pangkat. ' - Gol. ' .$v->pegawai_golongan,0,1);
					} else {
						$pdf->SetFont('Times','',10);
						$pdf->Cell(20,6,'',0,0);
						$pdf->Cell(3,6,'',0,0);
						$pdf->Cell(5,6,'',0,0);
						$pdf->Cell(35,6,'Pangkat/Golongan',0,0);
						$pdf->Cell(2,6,':',0,0);
						$pdf->Cell(95,6,'-',0,1);
					}
					
					if($v->pegawai_nip!="000"){
						$pdf->Cell(20,6,'',0,0);
						$pdf->Cell(3,6,'',0,0);
						$pdf->Cell(5,6,'',0,0);
						$pdf->Cell(35,6,'NIP',0,0);
						$pdf->Cell(2,6,':',0,0);
						$pdf->Cell(95,6,$v->pegawai_nip,0,1);
					} else {
						$pdf->Cell(20,6,'',0,0);
						$pdf->Cell(3,6,'',0,0);
						$pdf->Cell(5,6,'',0,0);
						$pdf->Cell(35,6,'NIP',0,0);
						$pdf->Cell(2,6,':',0,0);
						$pdf->Cell(95,6,'-',0,1);
					}
				}
				
				$pdf->Cell(20,6,'',0,0);
				$pdf->Cell(3,6,'',0,0);
				$pdf->Cell(5,6,'',0,0);
				$pdf->Cell(35,6,'Jabatan',0,0);
				$pdf->Cell(2,6,':',0,0);
				$pdf->MultiCell(95,6,$v->pegawai_namajabatan,0,1);
			
			}
		// }
		// Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
		
		$y=$pdf->GetY();
		if($y>215){
			$pdf->AddPage();
		}
		
		$y=$pdf->GetY();
		if($y>320){
			$pdf->AddPage();
		}
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Untuk',0,0);
        $pdf->Cell(3,6,':',0,0);
		
		//7
		$start_date = new DateTime($data[0]['telaah_tanggalberangkat']);
		$end_date = new DateTime($data[0]['telaah_tanggalkembali']);
		$interval = $start_date->diff($end_date);
			
		if($data[0]['telaah_hari']==0){
			$telaah_perihal = $data[0]['telaah_perihal'].' Di '.$data[0]['telaah_kantortujuan'].' Selama '.($interval->days + 1).' hari dari tanggal '. 
						  $this->tgl_indo($data[0]['telaah_tanggalberangkat']).' s/d '. $this->tgl_indo($data[0]['telaah_tanggalkembali']).'.';
		} else {
			$telaah_perihal = $data[0]['telaah_perihal'].' Di '.$data[0]['telaah_kantortujuan'].' Selama '.$data[0]['telaah_hari'].' hari dari tanggal '. 
						  $this->tgl_indo($data[0]['telaah_tanggalberangkat']).' s/d '. $this->tgl_indo($data[0]['telaah_tanggalkembali']).'.';
		}
		
		$pdf->SetFont('Times','B',10);
        $pdf->MultiCell(137,6,$telaah_perihal,0,'J');
		
		// Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
		
		$y=$pdf->GetY();
		if($y>329){
			$pdf->AddPage();
		}
		
		$pdf->SetFont('Times','',10);
		if($data[0]['jenis_skpd'] == 2){
			if($posisi==10){
				$pdf->MultiCell(160,6,'Demikian Surat Tugas ini diberikan kepada yang bersangkutan untuk dilaksanakan dengan penuh rasa tanggung jawab.',0,'J');
			
				$y=$pdf->GetY();
				if($y>270){
					$pdf->AddPage();
				}
			
				$pdf->Cell(100,6,'',0,0);
				$pdf->Cell(50,6,'Ditetapkan Di Kendari',0,1);
				
				$pdf->SetFont('Times','',10);
				$pdf->Cell(100,6,'',0,0);
				$pdf->Cell(50,6,'Pada Tanggal : '.$this->tgl_indo($data[0]['telaah_tanggalspt']),0,1);
			} else {
				$pdf->MultiCell(160,6,'Demikian Surat Perintah Tugas ini diberikan untuk dilaksanakan dengan penuh rasa tanggung jawab dan apabila Surat Perintah Tugas Ini tidak dijalankan sesuai aturan Perundang-Undangan yang berlaku, maka yang bersangkutan dan/atau penerima Surat Perintah Tugas ini yang akan bertanggung jawab.',0,'J');
			
				$pdf->Cell(10,4,'',0,1);
				
				$y=$pdf->GetY();
				if($y>270){
					$pdf->AddPage();
				}
			
				$pdf->SetFont('Times','',10);
				$pdf->Cell(100,6,'',0,0);
				$pdf->Cell(50,6,'Kendari, '.$this->tgl_indo($data[0]['telaah_tanggalspt']),0,1);
			}
			
		} else {
			$pdf->MultiCell(160,6,'Demikian Surat Tugas ini diberikan kepada yang bersangkutan untuk dilaksanakan dengan penuh rasa tanggung jawab.',0,'J');
			
			$y=$pdf->GetY();
			if($y>270){
				$pdf->AddPage();
			}
		
			$pdf->Cell(100,6,'',0,0);
			$pdf->Cell(50,6,'Ditetapkan Di Kendari',0,1);
			
			$pdf->SetFont('Times','',10);
			$pdf->Cell(100,6,'',0,0);
			$pdf->Cell(50,6,'Pada Tanggal : '.$this->tgl_indo($data[0]['telaah_tanggalspt']),0,1);
		} 
        
		$pdf->SetFont('Times','',10);
		
		if($tanda_tangan[0]['pegawai_jabatan']==2){
			$pdf->Cell(100,6,'',0,0);
			$pdf->MultiCell(60,6,'Plh. Sekretaris Daerah Kota Kendari',0,1);
			$pdf->Cell(100,6,'',0,0);
			$pdf->MultiCell(60,6,'ASISTEN I',0,1);
		} else if($tanda_tangan[0]['pegawai_jabatan']==17){
			$pdf->Cell(100,6,'',0,0);
			$pdf->MultiCell(60,6,'Plh. Sekretaris Daerah Kota Kendari',0,1);
			$pdf->Cell(100,6,'',0,0);
			$pdf->MultiCell(60,6,'ASISTEN II',0,1);
		} else if($tanda_tangan[0]['pegawai_jabatan']==18){
			$pdf->Cell(100,6,'',0,0);
			$pdf->MultiCell(60,6,'Plh. Sekretaris Daerah Kota Kendari',0,1);
			$pdf->Cell(100,6,'',0,0);
			$pdf->MultiCell(60,6,'ASISTEN III',0,1);
		} else if($tanda_tangan[0]['pegawai_jabatan']==7){
			if($data[0]['jenis_skpd']==1 || $data[0]['jenis_skpd']==10){
				$pdf->Cell(100,6,'',0,0);
				$pdf->MultiCell(60,6,'Pelaksana Kepala Dinas',0,1);
			} else if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']==1){
				$pdf->Cell(100,6,'',0,0);
				$pdf->MultiCell(60,6,'Pelaksana Kepala Dinas',0,1);
			} else if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']==11){
				$pdf->Cell(100,6,'',0,0);
				$pdf->MultiCell(60,6,'Pelaksana Kepala Puskesmas',0,1);
			} 
			$pdf->Cell(100,6,'',0,0);
			$pdf->MultiCell(60,6,$tanda_tangan[0]['pegawai_namajabatan'],0,1);
		} else {
			$pdf->Cell(100,6,'',0,0);
			$pdf->MultiCell(60,6,$tanda_tangan[0]['pegawai_namajabatan'],0,1);
		} 
			
		if($tanda_tangan[0]['status_tandatangan']==1){
			if($tanda_tangan[0]['pegawai_tandatangan']){
				if(file_exists('./upload/tanda_tangan/'.$tanda_tangan[0]['pegawai_tandatangan'])){
					$x=$pdf->GetX();
					$y=$pdf->GetY();
					$pdf->MultiCell(10,0,$pdf-> Image('./upload/tanda_tangan/'.$tanda_tangan[0]['pegawai_tandatangan'],128,$y,40,20),0,0);
				} 
			}
		}
		
		$pdf->Cell(10,20,'',0,1);	
		$pdf->Cell(100,6,'',0,0);
		$pdf->Cell(60,6,$tanda_tangan[0]['pegawai_nama'],0,1);
		
		if($tanda_tangan[0]['pegawai_jabatan']==1){
			
		} else {	
			$pdf->Cell(100,6,'',0,0);
			$pdf->Cell(60,6,$tanda_tangan[0]['pangkat'].", Gol. ".$tanda_tangan[0]['pegawai_golongan'],0,1);
			$pdf->Cell(100,6,'',0,0);
			$pdf->Cell(60,6,'NIP.'. $tanda_tangan[0]['pegawai_nip'],0,1);
		}
		
		$pdf->Cell(10,7,'',0,1);
		if($this->ion_auth->user()->row()->jenis_skpd == 2){
			if($this->uri->segment(5)==10){
				
				$pdf->SetFont('Times','',10);
				$y=$pdf->GetY();
				if($y>329){
					$pdf->AddPage();
				}
				
				$pdf->Cell(20,6,'Tembusan Yth:',0,0);
				$pdf->Cell(3,6,':',0,0);
				$pdf->Cell(137,6,'',0,1);
				
				$pdf->SetFont('Times','',10);
				$pdf->Cell(20,6,'',0,0);
				$pdf->Cell(3,6,'',0,0);
				$skpd_nama = strtolower($data[0]['skpd_nama']);
				$skpd_nama2 = ucwords($skpd_nama);
				//$pdf->Cell(137,6,'1. Kepala '.$skpd_nama2.' di Kendari',0,1);
				$pdf->Cell(137,6,'1. Kepala Badan Kepegawaian dan Pengembangan SDM Kota Kendari di Kendari',0,1);
				
				$pdf->SetFont('Times','',10);
				$pdf->Cell(20,6,'',0,0);
				$pdf->Cell(3,6,'',0,0);
				$pdf->Cell(137,6,'2. Bagian Organisasi dan Pemberdayaan Aparatur Kota Kendari di Kendari',0,1);
			}
		} else {
			$pdf->SetFont('Times','',10);
			$y=$pdf->GetY();
			if($y>329){
				$pdf->AddPage();
			}
			$pdf->Cell(20,6,'Tembusan Yth:',0,0);
			$pdf->Cell(3,6,':',0,0);
			$pdf->Cell(137,6,'',0,1);
			
			if(($this->uri->segment(5)=="kadis" && $this->uri->segment(6)==1) || ( $this->uri->segment(5)=="sekda" && $this->uri->segment(6)==1)){
						
				$pdf->SetFont('Times','',10);
				$pdf->Cell(20,6,'',0,0);
				$pdf->Cell(3,6,'',0,0);
				$skpd_nama = strtolower($data[0]['skpd_nama']);
				$skpd_nama2 = ucwords($skpd_nama);
				$pdf->Cell(137,6,'1. Kepala '.$skpd_nama2.' di Kendari',0,1);
				
				$pdf->SetFont('Times','',10);
				$pdf->Cell(20,6,'',0,0);
				$pdf->Cell(3,6,'',0,0);
				$pdf->Cell(137,6,'2. Arsip',0,1);
				
			} else {
				
				$pdf->SetFont('Times','',10);
				$pdf->Cell(20,6,'',0,0);
				$pdf->Cell(3,6,'',0,0);
				$skpd_nama = strtolower($data[0]['skpd_nama']);
				$skpd_nama2 = ucwords($skpd_nama);
				//$pdf->Cell(137,6,'1. Kepala '.$skpd_nama2.' di Kendari',0,1);
				$pdf->Cell(137,6,'1. Kepala Badan Kepegawaian dan Pengembangan SDM Kota Kendari di Kendari',0,1);
				
				$pdf->SetFont('Times','',10);
				$pdf->Cell(20,6,'',0,0);
				$pdf->Cell(3,6,'',0,0);
				$pdf->Cell(137,6,'2. Bagian Organisasi dan Pemberdayaan Aparatur Kota Kendari di Kendari',0,1);
			}
			
			
		} 
		
		
        // Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,10,'',0,1);
		
		$pdf->SetFont('Times','BI',10);
		//$pdf->MultiCell(160, 5, 'Catatan : Jika Walikota berhalangan atau berada diluar Daerah maka penandatanganan SPT dapat dilakukan oleh Wakil Walikota atau Sekretaris Daerah jika Wakil Walikota juga berhalangan atau berada diluar daerah.', 0,'J');
        
		//atur posisi 1.5 cm dari bawah
		$pdf->SetY(-15);
		//buat garis horizontal
		$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
		//Arial italic 9
		$pdf->SetFont('Arial','I',9);
		//nomor halaman
		
		$filename = 'SPT - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput'])).'.pdf';
		$path = "./upload/doc_TTE/$filename";
		$pdf->Output($path,'F');
    }*/
	
	## Create SPT DPRD
	function cetak_spt_dprd($telaah_id,$kategori_pelaksana,$pegawai_id,$nik, $posisi_penandatangan)
	{
		
		$data = $this->m_laporan->get_pelaksana_dprd($telaah_id);
		$data2 = $this->m_laporan->get_pengikut_dprd2($telaah_id);
		
		$tanda_tangan = $this->m_pegawai->get_pegawai_nik($posisi_penandatangan,$nik,'');
		
		$pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(25, 3.175, 25);
		$pdf->SetAutoPageBreak(false);
		
        $pdf->AddPage();
		$pdf->SetTopMargin(25);
		
		$pdf-> Image('./upload/kop_surat/'.$data[0]['kop_surat2'],20,16,170,30);
		
        $pdf->SetFont('Times','BU',16);
		$pdf->Cell(10,45,'',0,1);
		
        $pdf->Cell(160,7,'SURAT PERINTAH TUGAS',0,1,'C');
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(45,6,'',0,0);
        $pdf->Cell(115,6,'NOMOR :',0,1);
		
        $pdf->Cell(10,4,'',0,1);
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(5,6,'1.',0,0);
        $pdf->Cell(15,6,'Dasar',0,0);
        $pdf->Cell(140,6,':',0,1);
		
        $pdf->Cell(5,6,'',0,0);
        $pdf->Cell(5,6,'a.',0,0);
        $pdf->Cell(150,6,'Perda Kota Kendari no. 7 Tahun 2019 tentang APBD Kota Kendari tahun '.date('Y').';',0,1);
		
        $pdf->Cell(5,6,'',0,0);
        $pdf->Cell(5,6,'b.',0,0);
        $pdf->Cell(150,6,'Peraturan Tata Tertib Dewan Perwakilan Rakyat Daerah Kota Kendari;',0,1);
		
        $pdf->Cell(5,6,'',0,0);
        $pdf->Cell(5,6,'c.',0,0);
        $pdf->Cell(150,6,'Program Kerja DPRD Kota Kendari tahun '.date('Y').';',0,1);
		
        $pdf->Cell(5,6,'',0,0);
        $pdf->Cell(5,6,'d.',0,0);
        $pdf->Cell(150,6,'DPA-SKPD Sekretariat DPRD Kota Kendari;',0,1);
		
        $pdf->Cell(5,6,'',0,0);
        $pdf->Cell(155,6,'Menugaskan Kepada Anggota DPRD Kota Kendari yang tercantum namanya dibawah ini',0,1);
		
        $pdf->Cell(5,6,'',0,1);
		
		$pdf->SetFont('Times','B',10);
        $pdf->Cell(5,6,'No',1,0,'C');
        $pdf->Cell(75,6,'N a m a',1,0,'C');
        $pdf->Cell(70,6,'Jabatan',1,0,'C');
        $pdf->Cell(10,6,'Ket',1,1,'C');
		
		$pdf->SetFont('Times','',9);
        $pdf->Cell(5,6,'1.',1,0,'C');
        $pdf->Cell(75,6,$data[0]['anggotadprd_name'],1,0,'C');
        $pdf->Cell(70,6,$data[0]['anggotadprd_jabatan'],1,0,'C');
        $pdf->Cell(10,6,'',1,1,'C');
		
		
		$no = 2;
		foreach($data2 as $v) {
			$pdf->Cell(5,6,$no++.'.',1,0,'C');
			$pdf->Cell(75,6,$v->anggotadprd_name,1,0,'C');
			$pdf->Cell(70,6,$v->anggotadprd_jabatan,1,0,'C');
			$pdf->Cell(10,6,'',1,1,'C');
		}
		
        $pdf->Cell(10,4,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(5,6,'2.',0,0);
        $pdf->Cell(50,6,'Tujuan Perintah Tugas ',0,0);
        $pdf->Cell(10,6,':',0,1);
		
        $pdf->Cell(5,6,'',0,0);
        $pdf->MultiCell(165,6, $data[0]['telaah_perihal'],0,'J');
        
		$pdf->Cell(10,4,'',0,1);
		
        $pdf->Cell(5,6,'3.',0,0);
        $pdf->Cell(50,6,'Waktu dan Tempat Pelaksanaan',0,0);
        $pdf->Cell(10,6,':',0,1);
		
		$pdf->Cell(5,6,'',0,0);
        $pdf->Cell(5,6,'a.',0,0);
        $pdf->Cell(25,6,'Hari/Tanggal',0,0);
        $pdf->Cell(5,6,':',0,0);
		$tanggal_berangkat = $data[0]['telaah_tanggalberangkat'];
		$tanggal_kembali = $data[0]['telaah_tanggalkembali'];
		$day1 = date('D', strtotime($tanggal_berangkat));
		$day2 = date('D', strtotime($tanggal_kembali));
		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);
        $pdf->MultiCell(100,6,$dayList[$day1].', '.$this->tgl_indo($data[0]['telaah_tanggalberangkat']).' s/d '.$dayList[$day2].', '. $this->tgl_indo($data[0]['telaah_tanggalkembali']).'.',0,'J');
		
		$pdf->Cell(5,6,'',0,0);
        $pdf->Cell(5,6,'a.',0,0);
        $pdf->Cell(25,6,'Tempat',0,0);
        $pdf->Cell(5,6,':',0,0);
        $pdf->MultiCell(100,6,$data[0]['telaah_kantortujuan'],0,'J');
		
		$pdf->Cell(10,4,'',0,1);
		
        $pdf->MultiCell(160,6,'Demikian Surat Perintah Tugas ini diberikan untuk dilaksanakan dengan penuh rasa tanggung jawab dan apabila Surat Perintah Tugas Ini tidak dijalankan sesuai aturan Perundang-Undangan yang berlaku, maka yang bersangkutan dan/atau penerima Surat Perintah Tugas ini yang akan bertanggung jawab.',0,'J');
		
		$pdf->Cell(10,6,'',0,1);
		
		$pdf->Cell(100,5,'',0,0);
        $pdf->Cell(35,5,'Kendari, '.$this->tgl_indo($data[0]['telaah_tanggalspt']),0,1);
		
        $pdf->Cell(80,5,'',0,0);
		$pdf->Cell(80,5,'KETUA DPRD KOTA KENDARI',0,1,'C');
        $pdf->Cell(80,3,'',0,1);
		
		## SETTING KOORDINAT Y
		if($kategori_pelaksana == 1){
			$data3['telaah_tte_y2'] = $pdf->GetY()+1;
			$data3['telaah_id'] = $telaah_id;
			$data3['telaah_ttdspt'] = $tanda_tangan[0]['pegawai_id'];
			$this->m_telaah->update($data3);	
		} else {	
			$data2['telaah_tte_y2'] = $pdf->GetY()+1;
			$data2['telaah_id'] = $telaah_id;
			$data2['pegawai_id'] = $pegawai_id;
			$this->m_pengikut->update($data2);	
		}
		
		$pdf->Cell(10,20,'',0,1);
        $pdf->Cell(80,5,'',0,0);
        $pdf->Cell(80,5,$tanda_tangan[0]['pegawai_nama'],0,1,'C');
        
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Tembusan Yth:',0,0);
        $pdf->Cell(3,6,':',0,0);
        $pdf->Cell(137,6,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'',0,0);
        $pdf->Cell(3,6,'',0,0);
		$pdf->Cell(137,6,'1. Walikota kendari di Kendari',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'',0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(137,6,'2. Arsip',0,1);
		
		//atur posisi 1.5 cm dari bawah
		$pdf->SetY(-15);
		//buat garis horizontal
		$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
		//Arial italic 9
		$pdf->SetFont('Arial','I',9);
		//nomor halaman
		$pdf->Cell(0,10,'Dokumen ini ditandatangani secara elektronik menggunakan Layanan BSrE',0,0,'R');
		
		$filename = 'SPT - '.$data[0]['anggotadprd_name'].' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput'])).'.pdf';
		$path = "./upload/doc_dummy/$filename";
		$pdf->Output($path,'F');
    }
	
	## Create SPT DPRD
	/*function cetak_spt_dprd2($telaah_id,$kategori_pelaksana,$pegawai_id,$nik, $posisi_penandatangan)
	{
		
		$data = $this->m_laporan->get_pelaksana_dprd($telaah_id);
		$data2 = $this->m_laporan->get_pengikut_dprd2($telaah_id);
		
		$tanda_tangan = $this->m_pegawai->get_pegawai_nik($posisi_penandatangan,$nik,'');
		
		$pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(25, 3.175, 25);
		$pdf->SetAutoPageBreak(false);
		
        $pdf->AddPage();
		$pdf->SetTopMargin(25);
		
		$pdf-> Image('./upload/kop_surat/'.$data[0]['kop_surat2'],20,16,170,30);
		
        $pdf->SetFont('Times','BU',16);
		$pdf->Cell(10,45,'',0,1);
		
        $pdf->Cell(160,7,'SURAT PERINTAH TUGAS',0,1,'C');
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(45,6,'',0,0);
        $pdf->Cell(115,6,'NOMOR :',0,1);
		
        $pdf->Cell(10,4,'',0,1);
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(5,6,'1.',0,0);
        $pdf->Cell(15,6,'Dasar',0,0);
        $pdf->Cell(140,6,':',0,1);
		
        $pdf->Cell(5,6,'',0,0);
        $pdf->Cell(5,6,'a.',0,0);
        $pdf->Cell(150,6,'Perda Kota Kendari No. 11 Tahun 2018 tentang APBD Kota Kendari tahun 2019;',0,1);
		
        $pdf->Cell(5,6,'',0,0);
        $pdf->Cell(5,6,'b.',0,0);
        $pdf->Cell(150,6,'Peraturan Tata Tertib Dewan Perwakilan Rakyat Daerah Kota Kendari;',0,1);
		
        $pdf->Cell(5,6,'',0,0);
        $pdf->Cell(5,6,'c.',0,0);
        $pdf->Cell(150,6,'Program Kerja DPRD Kota Kendari Tahun 2019;',0,1);
		
        $pdf->Cell(5,6,'',0,0);
        $pdf->Cell(5,6,'d.',0,0);
        $pdf->Cell(150,6,'DPA-SKPD Sekretariat DPRD Kota Kendari Tahun '.date('Y').';',0,1);
		
        $pdf->Cell(5,6,'',0,0);
        $pdf->Cell(155,6,'Menugaskan Kepada Anggota DPRD Kota Kendari yang tercantum namanya dibawah ini',0,1);
		
        $pdf->Cell(5,6,'',0,1);
		
		$pdf->SetFont('Times','B',10);
        $pdf->Cell(5,6,'No',1,0,'C');
        $pdf->Cell(75,6,'N a m a',1,0,'C');
        $pdf->Cell(70,6,'Jabatan',1,0,'C');
        $pdf->Cell(10,6,'Ket',1,1,'C');
		
		$pdf->SetFont('Times','',9);
        $pdf->Cell(5,6,'1.',1,0,'C');
        $pdf->Cell(75,6,$data[0]['anggotadprd_name'],1,0,'C');
        $pdf->Cell(70,6,$data[0]['anggotadprd_jabatan'],1,0,'C');
        $pdf->Cell(10,6,'',1,1,'C');
		
		
		$no = 2;
		foreach($data2 as $v) {
			$pdf->Cell(5,6,$no++.'.',1,0,'C');
			$pdf->Cell(75,6,$v->anggotadprd_name,1,0,'C');
			$pdf->Cell(70,6,$v->anggotadprd_jabatan,1,0,'C');
			$pdf->Cell(10,6,'',1,1,'C');
		}
		
        $pdf->Cell(10,4,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(5,6,'2.',0,0);
        $pdf->Cell(50,6,'Tujuan Perintah Tugas ',0,0);
        $pdf->Cell(10,6,':',0,1);
		
        $pdf->Cell(5,6,'',0,0);
        $pdf->MultiCell(165,6, $data[0]['telaah_perihal'],0,'J');
        
		$pdf->Cell(10,4,'',0,1);
		
        $pdf->Cell(5,6,'3.',0,0);
        $pdf->Cell(50,6,'Waktu dan Tempat Pelaksanaan',0,0);
        $pdf->Cell(10,6,':',0,1);
		
		$pdf->Cell(5,6,'',0,0);
        $pdf->Cell(5,6,'a.',0,0);
        $pdf->Cell(25,6,'Hari/Tanggal',0,0);
        $pdf->Cell(5,6,':',0,0);
		$tanggal_berangkat = $data[0]['telaah_tanggalberangkat'];
		$tanggal_kembali = $data[0]['telaah_tanggalkembali'];
		$day1 = date('D', strtotime($tanggal_berangkat));
		$day2 = date('D', strtotime($tanggal_kembali));
		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);
        $pdf->MultiCell(100,6,$dayList[$day1].', '.$this->tgl_indo($data[0]['telaah_tanggalberangkat']).' s/d '.$dayList[$day2].', '. $this->tgl_indo($data[0]['telaah_tanggalkembali']).'.',0,'J');
		
		$pdf->Cell(5,6,'',0,0);
        $pdf->Cell(5,6,'a.',0,0);
        $pdf->Cell(25,6,'Tempat',0,0);
        $pdf->Cell(5,6,':',0,0);
        $pdf->MultiCell(100,6,$data[0]['telaah_kantortujuan'],0,'J');
		
		$pdf->Cell(10,4,'',0,1);
		
        $pdf->MultiCell(160,6,'Demikian Surat Perintah Tugas ini diberikan untuk dilaksanakan dengan penuh rasa tanggung jawab dan apabila Surat Perintah Tugas Ini tidak dijalankan sesuai aturan Perundang-Undangan yang berlaku, maka yang bersangkutan dan/atau penerima Surat Perintah Tugas ini yang akan bertanggung jawab.',0,'J');
		
		$pdf->Cell(10,6,'',0,1);
		
		$pdf->Cell(100,5,'',0,0);
        $pdf->Cell(35,5,'Kendari, '.$this->tgl_indo($data[0]['telaah_tanggalspt']),0,1);
		
        $pdf->Cell(80,5,'',0,0);
		$pdf->Cell(80,5,'KETUA DPRD KOTA KENDARI',0,1,'C');
        $pdf->Cell(80,3,'',0,1);
		
		
		if($tanda_tangan[0]['status_tandatangan']==1){
			if($tanda_tangan[0]['pegawai_tandatangan']){
				if(file_exists('./upload/tanda_tangan/'.$tanda_tangan[0]['pegawai_tandatangan'])){
					$x=$pdf->GetX();
					$y=$pdf->GetY();
					$pdf->MultiCell(10,0,$pdf-> Image('./upload/tanda_tangan/'.$tanda_tangan[0]['pegawai_tandatangan'],125,$y,40,15),0,0);
				} 
			}
		}
		
		$pdf->Cell(10,20,'',0,1);
        $pdf->Cell(80,5,'',0,0);
        $pdf->Cell(80,5,$tanda_tangan[0]['pegawai_nama'],0,1,'C');
        
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Tembusan Yth:',0,0);
        $pdf->Cell(3,6,':',0,0);
        $pdf->Cell(137,6,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'',0,0);
        $pdf->Cell(3,6,'',0,0);
		$pdf->Cell(137,6,'1. Walikota kendari di Kendari',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'',0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(137,6,'2. Arsip',0,1);
		
		//atur posisi 1.5 cm dari bawah
		$pdf->SetY(-15);
		//buat garis horizontal
		$pdf->Line(10,$pdf->GetY(),200,$pdf->GetY());
		//Arial italic 9
		$pdf->SetFont('Arial','I',9);
		//nomor halaman
		$pdf->Cell(0,10,'Dokumen ini ditandatangani secara elektronik menggunakan Layanan BSrE',0,0,'R');
		
		$filename = 'SPT - '.$data[0]['anggotadprd_name'].' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput'])).'.pdf';
		$path = "./upload/doc_TTE/$filename";
		$pdf->Output($path,'F');
    }*/
	
	function generate_tte ()
	{
		
		$telaah_id = base64_encode($this->encrypt->encode($this->input->post('telaah_id'), $this->session->userdata('encrypt_key')));
		
		switch($this->input->post('posisi')){
			case "kadis" 		: 	$pegawai_nik= $this->m_spd->kepala_opd($this->ion_auth->user()->row()->skpd_id);break;
			case "walikota" 	: 	$pegawai_nik= $this->m_spd->walikota();break;
			case "sekwan" 		: 	$pegawai_nik= $this->m_spd->sekwan();break;
			case "kadprd" 		: 	$pegawai_nik= $this->m_spd->ketua_dprd();break;
			case "kapus" 		: 	$pegawai_nik= $this->m_spd->kapus($this->ion_auth->user()->row()->skpd_id);break;
			case "camat" 		: 	$pegawai_nik= $this->m_spd->camat($this->ion_auth->user()->row()->skpd_id);break;
			case "sekda" 		: 	$pegawai_nik= $this->m_spd->sekda();break;
			case "lurah" 		: 	$pegawai_nik= $this->m_spd->lurah($this->ion_auth->user()->row()->skpd_id);break;
			case "walikota2" 	: 	if($this->ion_auth->get_users_groups()->row()->id == 6){
										$pegawai_nik= $this->m_spd->sekda();
									} else {
										$pegawai_nik= $this->m_spd->walikota();
									}
									break;
		}
		$nik = $pegawai_nik[0]['pegawai_nik'];
		
		if($nik==""){
			$this->session->set_flashdata('notif','NIK Pegawai Belum Diinput !!!');
			redirect('telaah/disposisi/lihat_laporan/'.$this->input->post('posisi').'/'.$this->input->post('telaah_kategori').'?telaah_id='.$telaah_id);
		} else {
			
			if($this->input->post('telaah_kategori')==3){
				$data =  $this->m_telaah->get_dprd($this->input->post('telaah_id'));
			} else {
				if($this->input->post('telaah_kategori')==8){
					$data =  $this->m_telaah->getWalikota($this->input->post('telaah_id'));
				} else {
					$data =  $this->m_telaah->get($this->input->post('telaah_id'));
				}
				
			}
			## Ambil Token
			$get_bearer_token = $this->getToken();
			$get_bearer_token  = json_decode($get_bearer_token , TRUE);

			echo "Get Token Bearer : ".$get_bearer_token['access_token']."<br>";
			
			$status_nik = $this->doCekStatus($nik, $get_bearer_token['access_token']);
			$status_nik  = json_decode($status_nik , TRUE);

			if($status_nik['status_code']==1111){
								
					## Buat PDF
					if($this->input->post('telaah_kategori')==3){
						
						$data =  $this->m_telaah->get_dprd($this->input->post('telaah_id'));
					
						## Hapus PDF
						$filename = 'SPPD - '.$data[0]['anggotadprd_name'].' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput'])).'.pdf';
						$filename3 = 'SPT - '.$data[0]['anggotadprd_name'].' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput'])).'.pdf';
						$path_file = './upload/doc_dummy/';
						$path_file2 = './upload/doc_TTE/';
						// unlink($path_file.$filename);
						unlink($path_file.$filename3);
						// unlink($path_file2.$filename);
						unlink($path_file2.$filename3);
						
						## SPT DPRD (3)
						if($this->input->post('telaah_kategori')==3 && $this->input->post('posisi')=='kadprd'){
							$this->cetak_spt_dprd($this->input->post('telaah_id'),1,$data[0]['anggotadprd_id'],$nik,$this->input->post('posisi'));
							
							// $data7['telaah_id'] = $this->input->post('telaah_id');
							// $data7['telaah_kategori'] = $this->input->post('telaah_kategori');
							
							// ## Insert Table TTE
							// $ttd =  $this->m_spd->sekwan();
							// $data7['group'] = 10;
							// $data7['pegawai_id'] = $ttd[0]['pegawai_id'];
							
							// $data7['skpd_id'] = $this->input->post('skpd_id');
							// $data7['jenis_skpd'] = $this->input->post('jenis_skpd');
							// $this->m_disposisi->kuasakan($data7);
							
						} 
						
						if($this->input->post('telaah_kategori')==3 && $this->input->post('posisi')=='sekwan'){
							$this->cetak_spd('dprd',1,$this->input->post('telaah_id'),'',$nik,$this->input->post('posisi'));
						} 
						
						$data2 =  $this->m_telaah->get_dprd($this->input->post('telaah_id'));
					
					 } else {
						
						if($this->input->post('telaah_kategori')==8){
							$data =  $this->m_telaah->getWalikota($this->input->post('telaah_id'));
						} else {
							$data =  $this->m_telaah->get($this->input->post('telaah_id'));
						}
						
						## Hapus PDF
						$filename = 'SPPD - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput'])).'.pdf';
						$filename3 = 'SPT - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput'])).'.pdf';
						$path_file = './upload/doc_dummy/';
						$path_file2 = './upload/doc_TTE/';
						unlink($path_file.$filename);
						unlink($path_file.$filename3);
						unlink($path_file2.$filename);
						unlink($path_file2.$filename3);
						
						## SPT Kepala OPD (2)
						if($this->input->post('telaah_kategori')==2 && $this->input->post('posisi')=='walikota'){
							$this->cetak_spt($this->input->post('telaah_id'),$this->input->post('telaah_kategori'),1, $data[0]['pegawai_id'],$nik,$this->input->post('posisi'));
							
							// $data7['telaah_id'] = $this->input->post('telaah_id');
							// $data7['telaah_kategori'] = $this->input->post('telaah_kategori');
							
							## Insert Table TTE
							// $ttd =  $this->m_spd->kepala_opd($this->input->post('skpd_id'));
							// $data7['group'] = 4;
							// $data7['pegawai_id'] = $ttd[0]['pegawai_id'];
							
							// $data7['skpd_id'] = $this->input->post('skpd_id');
							// $data7['jenis_skpd'] = $this->input->post('jenis_skpd');
							// $this->m_disposisi->kuasakan($data7);
							
						} else if($this->input->post('telaah_kategori')==2 && $this->input->post('posisi')=='kadis'){
							$this->cetak_spd('opd',1,$this->input->post('telaah_id'),'',$nik,$this->input->post('posisi'));
							$this->cetak_spt($this->input->post('telaah_id'),$this->input->post('telaah_kategori'),1, $data[0]['pegawai_id'],$nik,$this->input->post('posisi'));
						## SPT SEKDA (4)
						} else if($this->input->post('telaah_kategori')==4 && $this->input->post('posisi')=='walikota'){
							$this->cetak_spt($this->input->post('telaah_id'),$this->input->post('telaah_kategori'),1, $data[0]['pegawai_id'],$nik,$this->input->post('posisi'));
							
							// $data7['telaah_id'] = $this->input->post('telaah_id');
							// $data7['telaah_kategori'] = $this->input->post('telaah_kategori');
							
							## Insert Table TTE
							// $ttd =  $this->m_spd->sekda();
							// $data7['group'] = 6;
							// $data7['pegawai_id'] = $ttd[0]['pegawai_id'];
							
							// $data7['skpd_id'] = $this->input->post('skpd_id');
							// $data7['jenis_skpd'] = $this->input->post('jenis_skpd');
							// $this->m_disposisi->kuasakan($data7);
						## LURAH DAN CAMAT (5)
						} else if($this->input->post('telaah_kategori')==5 && $this->input->post('posisi')=='sekda'){
							$this->cetak_spt($this->input->post('telaah_id'),$this->input->post('telaah_kategori'),1, $data[0]['pegawai_id'],$nik,$this->input->post('posisi'));
						} else if($this->input->post('telaah_kategori')==5 && $this->input->post('posisi')=='camat'){
							$this->cetak_spd('opd',1,$this->input->post('telaah_id'),'',$nik,$this->input->post('posisi'));
						
						## WALIKOTA (8)
						} else if($this->input->post('telaah_kategori')==8 && $this->ion_auth->get_users_groups()->row()->id == 8){
							$this->cetak_spt($this->input->post('telaah_id'),$this->input->post('telaah_kategori'),1, $data[0]['pegawai_id'],$nik,$this->input->post('posisi'));
							
							// $data7['telaah_id'] = $this->input->post('telaah_id');
							// $data7['telaah_kategori'] = $this->input->post('telaah_kategori');
							
							## Insert Table TTE
							// $ttd =  $this->m_spd->sekda();
							// $data7['group'] = 6;
							// $data7['pegawai_id'] = $ttd[0]['pegawai_id'];
							
							// $data7['skpd_id'] = $this->input->post('skpd_id');
							// $data7['jenis_skpd'] = $this->input->post('jenis_skpd');
							// $this->m_disposisi->kuasakan($data7);
						} else if($this->input->post('telaah_kategori')==8 && $this->ion_auth->get_users_groups()->row()->id == 6){
							$this->cetak_spd('walikota',1,$this->input->post('telaah_id'),'',$nik,$this->input->post('posisi'));		
						## SPT SEKWAN (10)
						} else if($this->input->post('telaah_kategori')==10 && $this->input->post('posisi')=='walikota'){
							$this->cetak_spt($this->input->post('telaah_id'),$this->input->post('telaah_kategori'),1, $data[0]['pegawai_id'],$nik,$this->input->post('posisi'));
							
							// $data7['telaah_id'] = $this->input->post('telaah_id');
							// $data7['telaah_kategori'] = $this->input->post('telaah_kategori');
							
							## Insert Table TTE
							// $ttd =  $this->m_spd->sekwan();
							// $data7['group'] = 10;
							// $data7['pegawai_id'] = $ttd[0]['pegawai_id'];
							
							// $data7['skpd_id'] = $this->input->post('skpd_id');
							// $data7['jenis_skpd'] = $this->input->post('jenis_skpd');
							// $this->m_disposisi->kuasakan($data7);
						## 1,6,7,9,11
						} else {
							$this->cetak_spd('opd',1,$this->input->post('telaah_id'),'',$nik,$this->input->post('posisi'));
							$this->cetak_spt($this->input->post('telaah_id'),$this->input->post('telaah_kategori'),1, $data[0]['pegawai_id'],$nik,$this->input->post('posisi'));
						}
						
						if($this->input->post('telaah_kategori')==8){
							$data2 =  $this->m_telaah->getWalikota($this->input->post('telaah_id'));
						} else {
							$data2 =  $this->m_telaah->get($this->input->post('telaah_id'));
						}
					
					}
					
					$yAxis_fpdf = $data2[0]['telaah_tte_y']*3;
					$set_y = ((1050-$yAxis_fpdf)-61) ;
					$set_height = $set_y+62;
					
					if($data2[0]['telaah_halaman_tte']==2){
						$yAxis_fpdf3 = $data2[0]['telaah_tte_y2']*3;
						$set_y3 = ((1050-$yAxis_fpdf3)-80) ;
						$set_height3 = $set_y3+62;
					} else {
						$yAxis_fpdf3 = $data2[0]['telaah_tte_y2']*3;
						$set_y3 = ((1050-$yAxis_fpdf3)-61) ;
						$set_height3 = $set_y3+62;
					}
					
					if($this->input->post('telaah_kategori')==3){
						$data2 =  $this->m_pengikut->data_dprd($this->input->post('telaah_id'));
					} else {
						$data2 =  $this->m_pengikut->get_pengikut($this->input->post('telaah_id'));
					}
					
							################################ PENGIKUT
							foreach($data2 as $v){
								
								## Hapus PDF
								if($this->input->post('telaah_kategori')==3){
									$filename2 = 'SPPD - '.$v->anggotadprd_name.' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput'])).'.pdf';
								} else {
									$filename2 = 'SPPD - '.$v->pegawai_nama.' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput'])).'.pdf';
									$path_file = './upload/doc_dummy/';
									$path_file2 = './upload/doc_TTE/';
									unlink($path_file.$filename2);
									unlink($path_file2.$filename2);
								}
								
								
								
								if($this->input->post('telaah_kategori')==3){
									$this->cetak_spd('dprd',2,$this->input->post('telaah_id'),$v->anggotadprd_id,$nik,$this->input->post('posisi'));
								} else if($this->input->post('telaah_kategori')==8 && $this->ion_auth->get_users_groups()->row()->id == 6){
									$this->cetak_spd('walikota',2,$this->input->post('telaah_id'),$v->pegawai_id,$nik,$this->input->post('posisi'));
								} else if($this->input->post('telaah_kategori')==8 && $this->ion_auth->get_users_groups()->row()->id == 8){
								} else {
									$this->cetak_spd('opd',2,$this->input->post('telaah_id'),$v->pegawai_id,$nik,$this->input->post('posisi'));
								}
								
								$koordinat_tte_pengikut = $this->m_pengikut->get_koordinat_tte_pengikut($this->input->post('telaah_id'), $v->pegawai_id);
								$yAxis_fpdf2 = $koordinat_tte_pengikut[0]['telaah_tte_y']*3;
								$set_y2 = ((1050-$yAxis_fpdf2)-61) ;
								$set_height2 = $set_y2+62;
								
								## Ambil Token
								// $get_bearer_token = $this->getToken();
								// $get_bearer_token  = json_decode($get_bearer_token , TRUE);

								//echo "Get Token Bearer : ".$get_bearer_token['access_token']."<br>";
								
								## Tidak pakai SPT
								if(($this->input->post('telaah_kategori')==2 && $this->input->post('posisi')=='walikota') 
									|| ($this->input->post('telaah_kategori')==3 && $this->input->post('posisi')=='kadprd') 
									|| ($this->input->post('telaah_kategori')==4 && $this->input->post('posisi')=='walikota') 
									|| ($this->input->post('telaah_kategori')==5 && $this->input->post('posisi')=='sekda')
									|| ($this->input->post('telaah_kategori')==8 && $this->ion_auth->get_users_groups()->row()->id == 8) 
									|| ($this->input->post('telaah_kategori')==10 && $this->input->post('posisi')=='walikota')  ){
								
								
								## 1,6,7,9,11
								} else {
									
									if($this->input->post('telaah_kategori')==3){
										$filename_tte_sppd = 'SPPD - '.$v->anggotadprd_name.' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput']));
									} else {
										$filename_tte_sppd = 'SPPD - '.$v->pegawai_nama.' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput']));
									}
									
									//$send_sign_request   = $this->sendSignRequest($filename2,$set_y2,$set_height2,$nik);
									$send_sign_request   = $this->sendSignRequest($filename2,$set_y2,$set_height2,$nik, $get_bearer_token['access_token']  );
									$send_sign_request   = json_decode($send_sign_request , TRUE);
									
									## Cek ID Signed
									// if($send_sign_request['id_signed']==""){
										// if($this->input->post('telaah_kategori')==3){
											// $this->cetak_spd2('dprd',2,$this->input->post('telaah_id'),$v->anggotadprd_id,$nik,$this->input->post('posisi'));
										// } else if($this->input->post('telaah_kategori')==8){
											// $this->cetak_spd2('walikota',2,$this->input->post('telaah_id'),$v->pegawai_id,$nik,$this->input->post('posisi'));
										// } else {
											// $this->cetak_spd2('opd',2,$this->input->post('telaah_id'),$v->pegawai_id,$nik,$this->input->post('posisi'));
										// }
										
										// $data6['telaah_id'] = $this->input->post('telaah_id');
										// $data6['pegawai_id'] = $v->pegawai_id;
										// $data6['telaah_tte'] = $filename_tte_sppd.'.pdf';
										// $this->m_pengikut->update($data6);
									
									// } else {
										$sign_document = $this->signById($send_sign_request['id_signed'], $this->input->post('passphrase'), $nik, $get_bearer_token['access_token']);
										$sign_document  = json_decode($sign_document , TRUE);
										
										$data6['telaah_id'] = $this->input->post('telaah_id');
										$data6['pegawai_id'] = $v->pegawai_id;
										$data6['telaah_tte'] = $filename_tte_sppd.'.pdf';
										$this->m_pengikut->update($data6);
									
										## Download Dokumen
										$download_document  = $this->downloadDokumenById($send_sign_request['id_signed'], $filename_tte_sppd, $get_bearer_token['access_token']);
										$download_document  = json_decode($download_document , TRUE);
										
										// ## Halaman Kedua SPPD
										$send_sign_request4   = $this->sendSignRequest4($filename_tte_sppd,$nik,$get_bearer_token['access_token']  );
										$send_sign_request4   = json_decode($send_sign_request4 , TRUE);
										$sign_document4 = $this->signById($send_sign_request4['id_signed'], $this->input->post('passphrase'), $nik,$get_bearer_token['access_token']);
										$sign_document4  = json_decode($sign_document4 , TRUE);
										$download_document4  = $this->downloadDokumenById($send_sign_request4['id_signed'],$filename_tte_sppd,$get_bearer_token['access_token']);
										$download_document4  = json_decode($download_document4 , TRUE);
									// }
									
								}
								
							}
							########################################################################
					
					$get_halaman = $this->m_telaah->get($this->input->post('telaah_id'));
					
					## Ambil Token
					// $get_bearer_token = $this->getToken();
					// $get_bearer_token  = json_decode($get_bearer_token , TRUE);

					 // echo "Get Token Bearer : ".$get_bearer_token['access_token']."<br>";

					
					## Hanya SPT  
					if(($this->input->post('telaah_kategori')==2 && $this->input->post('posisi')=='walikota')
						|| ($this->input->post('telaah_kategori')==3 && $this->input->post('posisi')=='kadprd')
						|| ($this->input->post('telaah_kategori')==4 && $this->input->post('posisi')=='walikota')
						|| ($this->input->post('telaah_kategori')==5 && $this->input->post('posisi')=='sekda')
						|| ($this->input->post('telaah_kategori')==8 && $this->ion_auth->get_users_groups()->row()->id == 8)
						|| ($this->input->post('telaah_kategori')==10 && $this->input->post('posisi')=='walikota')){
						
						if($this->input->post('telaah_kategori')==3){
							$filename_tte_spt = 'SPT - '.$data[0]['anggotadprd_name'].' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput']));
						} else {
							$filename_tte_spt = 'SPT - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput']));
						}
						
						if($this->input->post('telaah_kategori')==3){
							$send_sign_request3   = $this->sendSignRequest3($filename3,$get_halaman[0]['telaah_halaman_tte'],$set_y3,$set_height3,$nik, $get_bearer_token['access_token']  );
							$send_sign_request3   = json_decode($send_sign_request3 , TRUE);
							
							// if($send_sign_request3['id_signed']==""){
								// $this->cetak_spt_dprd2($this->input->post('telaah_id'),1,$data[0]['anggotadprd_id'],$nik,$this->input->post('posisi'));
							// }else {
								echo "Get Signed ID : ".$send_sign_request3['id_signed']."<br>"; 
						
								$sign_document3 = $this->signById($send_sign_request3['id_signed'], $this->input->post('passphrase'), $nik, $get_bearer_token['access_token']);
								$sign_document3  = json_decode($sign_document3 , TRUE);
								
								echo "Proses Sign : ".$sign_document3['message']."<br>";
								$download_document3  = $this->downloadDokumenById($send_sign_request3['id_signed'],$filename_tte_spt, $get_bearer_token['access_token']);
								$download_document3  = json_decode($download_document3 , TRUE);
							// }
							
						}else{
							$send_sign_request3   = $this->sendSignRequest2($filename3,$get_halaman[0]['telaah_halaman_tte'],$set_y3,$set_height3,$nik, $get_bearer_token['access_token']  );
							$send_sign_request3   = json_decode($send_sign_request3 , TRUE);
							// if($send_sign_request3['id_signed']==""){
								// $this->cetak_spt2($this->input->post('telaah_id'),$this->input->post('telaah_kategori'),1, $data[0]['pegawai_id'],$nik,$this->input->post('posisi'));
							// }else {
								echo "Get Signed ID : ".$send_sign_request3['id_signed']."<br>"; 
						
								$sign_document3 = $this->signById($send_sign_request3['id_signed'], $this->input->post('passphrase'), $nik, $get_bearer_token['access_token']);
								$sign_document3  = json_decode($sign_document3 , TRUE);
								
								echo "Proses Sign : ".$sign_document3['message']."<br>";
								$download_document3  = $this->downloadDokumenById($send_sign_request3['id_signed'],$filename_tte_spt, $get_bearer_token['access_token']);
								$download_document3  = json_decode($download_document3 , TRUE);
							// }
							
						} 
						
					
					## Hanya SPD
					} else if(($this->input->post('telaah_kategori')==2 && $this->input->post('posisi')=='kadis' && $data[0]['telaah_domainperjalanan']==1)
							||($this->input->post('telaah_kategori')==2 && $this->input->post('posisi')=='kadis' && $data[0]['telaah_domainperjalanan']==2)
							||($this->input->post('telaah_kategori')==3 && $this->input->post('posisi')=='sekwan')
							||($this->input->post('telaah_kategori')==4 && $this->input->post('posisi')=='sekda')
							||($this->input->post('telaah_kategori')==5 && $this->input->post('posisi')=='camat')
							||($this->input->post('telaah_kategori')==8 && $this->ion_auth->get_users_groups()->row()->id == 6)
							||($this->input->post('telaah_kategori')==10 && $this->input->post('posisi')=='sekwan')){
						
						
						if($this->input->post('telaah_kategori')==3){
							$filename_tte_sppd = 'SPPD - '.$data[0]['anggotadprd_name'].' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput']));
						} else {
							$filename_tte_sppd = 'SPPD - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput']));
						}
						
						$send_sign_request   = $this->sendSignRequest($filename,$set_y,$set_height,$nik, $get_bearer_token['access_token']  );
						$send_sign_request   = json_decode($send_sign_request , TRUE);
						echo "Get Signed ID : ".$send_sign_request['id_signed']."<br>";
						
						// if($send_sign_request['id_signed']==""){
							// if($this->input->post('telaah_kategori')==3){
								// $this->cetak_spd2('dprd',1,$this->input->post('telaah_id'),'',$nik,$this->input->post('posisi'));
							// } else if($this->input->post('telaah_kategori')==8){
								// $this->cetak_spd2('walikota',1,$this->input->post('telaah_id'),'',$nik,$this->input->post('posisi'));
							// } else {
								// $this->cetak_spd2('opd',1,$this->input->post('telaah_id'),'',$nik,$this->input->post('posisi'));
							// }
						// } else {
							$sign_document = $this->signById($send_sign_request['id_signed'], $this->input->post('passphrase'), $nik, $get_bearer_token['access_token']);
							$sign_document  = json_decode($sign_document , TRUE);
							echo "Proses Sign : ".$sign_document['message']."<br>";
							$download_document  = $this->downloadDokumenById($send_sign_request['id_signed'],$filename_tte_sppd, $get_bearer_token['access_token']);
							$download_document  = json_decode($download_document , TRUE);
							
							// ## Halaman Kedua SPPD
							$send_sign_request4   = $this->sendSignRequest4($filename_tte_sppd,$nik,$get_bearer_token['access_token']  );
							$send_sign_request4   = json_decode($send_sign_request4 , TRUE);
							$sign_document4 = $this->signById($send_sign_request4['id_signed'], $this->input->post('passphrase'), $nik,$get_bearer_token['access_token']);
							$sign_document4  = json_decode($sign_document4 , TRUE);
							$download_document4  = $this->downloadDokumenById($send_sign_request4['id_signed'],$filename_tte_sppd,$get_bearer_token['access_token']);
							$download_document4  = json_decode($download_document4 , TRUE);
						// }
					
					## 1,6,7,9,11
					} else {
						
						if($this->input->post('telaah_kategori')==3){
							$filename_tte_sppd = 'SPPD - '.$data[0]['anggotadprd_name'].' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput']));
							$filename_tte_spt = 'SPT - '.$data[0]['anggotadprd_name'].' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput']));
						} else {
							$filename_tte_sppd = 'SPPD - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput']));
							$filename_tte_spt = 'SPT - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput']));
							
						}
						
						## Upload PDF
						$send_sign_request   = $this->sendSignRequest($filename,$set_y,$set_height,$nik, $get_bearer_token['access_token']  );
						$send_sign_request   = json_decode($send_sign_request , TRUE);
						
						echo "Get Signed ID : ".$send_sign_request['id_signed']."<br>";
						
						## Cek Dokumen
						$sign_document = $this->signById($send_sign_request['id_signed'], $this->input->post('passphrase'), $nik, $get_bearer_token['access_token']);
						$sign_document  = json_decode($sign_document , TRUE);
						
						echo "Proses Sign : ".$sign_document['message']."<br>";
						
								
						// if($send_sign_request['id_signed']==""){
							// if($this->input->post('telaah_kategori')==3){
								// $filename_tte_sppd = 'SPPD - '.$data[0]['anggotadprd_name'].' - '.date("d-m-Y-H-i-s", strtotime($data[0]['telaah_waktuinput']));
							// } else if($this->input->post('telaah_kategori')==8){
								// $this->cetak_spd2('walikota',1,$this->input->post('telaah_id'),'',$nik,$this->input->post('posisi'));
							// } else {
								// $this->cetak_spd2('opd',1,$this->input->post('telaah_id'),'',$nik,$this->input->post('posisi'));
								// $this->cetak_spt2($this->input->post('telaah_id'),$this->input->post('telaah_kategori'),1, $data[0]['pegawai_id'],$nik,$this->input->post('posisi'));
							// }
						// } else {
							
							## Download Dokumen
							$download_document  = $this->downloadDokumenById($send_sign_request['id_signed'],$filename_tte_sppd, $get_bearer_token['access_token']);
							$download_document  = json_decode($download_document , TRUE);
							
							$send_sign_request3   = $this->sendSignRequest2($filename3,$get_halaman[0]['telaah_halaman_tte'],$set_y3,$set_height3,$nik, $get_bearer_token['access_token']  );
							$send_sign_request3   = json_decode($send_sign_request3 , TRUE);

							$sign_document3 = $this->signById($send_sign_request3['id_signed'], $this->input->post('passphrase'), $nik, $get_bearer_token['access_token']);
							$sign_document3  = json_decode($sign_document , TRUE);
							
							$download_document3  = $this->downloadDokumenById($send_sign_request3['id_signed'],$filename_tte_spt, $get_bearer_token['access_token']);
							$download_document3  = json_decode($download_document3 , TRUE);
							
							// ## Halaman Kedua SPPD
							$send_sign_request4   = $this->sendSignRequest4($filename_tte_sppd,$nik,$get_bearer_token['access_token']  );
							$send_sign_request4   = json_decode($send_sign_request4 , TRUE);
							$sign_document4 = $this->signById($send_sign_request4['id_signed'], $this->input->post('passphrase'), $nik,$get_bearer_token['access_token']);
							$sign_document4  = json_decode($sign_document4 , TRUE);
							$download_document4  = $this->downloadDokumenById($send_sign_request4['id_signed'],$filename_tte_sppd,$get_bearer_token['access_token']);
							$download_document4  = json_decode($download_document4 , TRUE);
						// }
						
					}
					
					if($sign_document['error']){
						$this->session->set_flashdata('notif',$sign_document['error']);
						
						$log_tte['telaah_id'] = $this->input->post('telaah_id');
						$log_tte['pegawai_id'] = $data[0]['pegawai_id'];
						$log_tte['action'] = $sign_document['error'];
						$log_tte['action_table'] = "Tracking SPPD";
						$this->m_log->create_tte($log_tte);
						redirect('telaah/disposisi/lihat_laporan/'.$this->input->post('posisi').'/'.$this->input->post('telaah_kategori').'?telaah_id='.$telaah_id);
					
					} else if($sign_document3['error']){
						$this->session->set_flashdata('notif',$sign_document3['error']);
						
						$log_tte['telaah_id'] = $this->input->post('telaah_id');
						$log_tte['pegawai_id'] = $data[0]['pegawai_id'];
						$log_tte['action'] = $sign_document3['error'];
						$log_tte['action_table'] = "Tracking SPT";
						$this->m_log->create_tte($log_tte);
						redirect('telaah/disposisi/lihat_laporan/'.$this->input->post('posisi').'/'.$this->input->post('telaah_kategori').'?telaah_id='.$telaah_id);
					
					} else {
						## Esselon
						if($this->input->post('telaah_kategori')==1 && $this->input->post('posisi')=='kadis'){
							$data3['telaah_id'] = $this->input->post('telaah_id');
							$data3['timeline_kadis_id'] = 1; 
							$data3['timeline_kadis_date'] = date("Y-m-d H:i:s");
							$data3['timeline_kadis_disposisi'] = "ACC";
							$this->m_disposisi->update_timeline_1($data3,'');
							
							$data4['telaah_id'] = $this->input->post('telaah_id');
							$data4['telaah_status'] = 2;
							$this->m_disposisi->update_timeline_1($data3, $data4);
							
							$log['kode_log_action'] = "57";
							$log['action'] = "ACC/DISPOSISI";
							$log['kode_log_action_table'] = "16";
							$log['action_table'] = "Tracking SPPD";
							$this->m_log->create($log);
						} 
						## Kepala OPD Dalam Daerah
						if($this->input->post('telaah_kategori')==2 && $this->input->post('posisi')=='kadis'){
							$data3['telaah_id'] = $this->input->post('telaah_id');
							$data3['timeline_kadis_id'] = 1; 
							$data3['timeline_kadis_date'] = date("Y-m-d H:i:s");
							$data3['timeline_kadis_disposisi'] = "ACC";
							$this->m_disposisi->update_timeline_2($data3,'');
							
							if ($data[0]['telaah_domainperjalanan']==3){
								$data4['telaah_id'] = $this->input->post('telaah_id');
								$data4['telaah_status'] = 2;
								$this->m_disposisi->update_timeline_2($data3, $data4);
							}
							
							$log['kode_log_action'] = "57";
							$log['action'] = "ACC/DISPOSISI";
							$log['kode_log_action_table'] = "16";
							$log['action_table'] = "Tracking SPPD";
							$this->m_log->create($log);
						}
						## Kepala OPD Luar daerah
						if($this->input->post('telaah_kategori')==2 && $this->input->post('posisi')=='walikota'){
							$data3['telaah_id'] = $this->input->post('telaah_id');
							$data3['timeline_walikota_id'] = 1; 
							$data3['timeline_walikota_date'] = date("Y-m-d H:i:s");
							$data3['timeline_walikota_disposisi'] = "ACC";
							$this->m_disposisi->update_timeline_2($data3,'');
							
							$data4['telaah_id'] = $this->input->post('telaah_id');
							$data4['telaah_status'] = 2;
							$this->m_disposisi->update_timeline_2($data3, $data4);
							
							$log['kode_log_action'] = "57";
							$log['action'] = "ACC/DISPOSISI";
							$log['kode_log_action_table'] = "16";
							$log['action_table'] = "Tracking SPPD";
							$this->m_log->create($log);
						}## DPRD
						if($this->input->post('telaah_kategori')==3 && $this->input->post('posisi')=='sekwan'){
							$data3['telaah_id'] = $this->input->post('telaah_id');
							$data3['timeline_sekwan_id'] = 1; 
							$data3['timeline_sekwan_date'] = date("Y-m-d H:i:s");
							$data3['timeline_sekwan_disposisi'] = "ACC";
							$this->m_disposisi->update_timeline_3($data3,'');
							
							$log['kode_log_action'] = "57";
							$log['action'] = "ACC/DISPOSISI";
							$log['kode_log_action_table'] = "16";
							$log['action_table'] = "Tracking SPPD";
							$this->m_log->create($log);
						}
						if($this->input->post('telaah_kategori')==3 && $this->input->post('posisi')=='kadprd'){
							$data3['telaah_id'] = $this->input->post('telaah_id');
							$data3['timeline_kadprd_id'] = 1; 
							$data3['timeline_kadprd_date'] = date("Y-m-d H:i:s");
							$data3['timeline_kadprd_disposisi'] = "ACC";
							$this->m_disposisi->update_timeline_3($data3,'');
							
							$data4['telaah_id'] = $this->input->post('telaah_id');
							$data4['telaah_status'] = 2;
							$this->m_disposisi->update_timeline_3($data3, $data4);
							
							$log['kode_log_action'] = "57";
							$log['action'] = "ACC/DISPOSISI";
							$log['kode_log_action_table'] = "16";
							$log['action_table'] = "Tracking SPPD";
							$this->m_log->create($log);
						}
						## Sekda
						if($this->input->post('telaah_kategori')==4 && $this->input->post('posisi')=='sekda'){
							$data3['telaah_id'] = $this->input->post('telaah_id');
							$data3['timeline_sekda_id'] = 1; 
							$data3['timeline_sekda_date'] = date("Y-m-d H:i:s");
							$data3['timeline_sekda_disposisi'] = "ACC";
							$this->m_disposisi->update_timeline_4($data3,'');
							
							$log['kode_log_action'] = "57";
							$log['action'] = "ACC/DISPOSISI";
							$log['kode_log_action_table'] = "16";
							$log['action_table'] = "Tracking SPPD";
							$this->m_log->create($log);
						}
						if($this->input->post('telaah_kategori')==4 && $this->input->post('posisi')=='walikota'){
							$data3['telaah_id'] = $this->input->post('telaah_id');
							$data3['timeline_walikota_id'] = 1; 
							$data3['timeline_walikota_date'] = date("Y-m-d H:i:s");
							$data3['timeline_walikota_disposisi'] = "ACC";
							$this->m_disposisi->update_timeline_4($data3,'');
							
							$data4['telaah_id'] = $this->input->post('telaah_id');
							$data4['telaah_status'] = 2;
							$this->m_disposisi->update_timeline_4($data3, $data4);
							
							$log['kode_log_action'] = "57";
							$log['action'] = "ACC/DISPOSISI";
							$log['kode_log_action_table'] = "16";
							$log['action_table'] = "Tracking SPPD";
							$this->m_log->create($log);
						}
						## Camat dan Lurah
						if($this->input->post('telaah_kategori')==5 && $this->input->post('posisi')=='camat'){
							$data3['telaah_id'] = $this->input->post('telaah_id');
							$data3['timeline_camat_id'] = 1; 
							$data3['timeline_camat_date'] = date("Y-m-d H:i:s");
							$data3['timeline_camat_disposisi'] = "ACC";
							$this->m_disposisi->update_timeline_5($data3,'');
							
							$log['kode_log_action'] = "57";
							$log['action'] = "ACC/DISPOSISI";
							$log['kode_log_action_table'] = "16";
							$log['action_table'] = "Tracking SPPD";
							$this->m_log->create($log);
						}
						if($this->input->post('telaah_kategori')==5 && $this->input->post('posisi')=='sekda'){
							if($this->input->post('jenis_skpd')==5){
								$data3['telaah_id'] = $this->input->post('telaah_id');
								$data3['timeline_sekda_id'] = 1; 
								$data3['timeline_sekda_date'] = date("Y-m-d H:i:s");
								$data3['timeline_sekda_disposisi'] = "ACC";
								$data3['timeline_walikota_id'] = 1; 
								$data3['timeline_walikota_date'] = date("Y-m-d H:i:s");
								$data3['timeline_walikota_disposisi'] = "ACC";
								
								$data4['telaah_id'] = $this->input->post('telaah_id');
								$data4['telaah_status'] = 2;
								$this->m_disposisi->update_timeline_5($data3, $data4);

							} else {
								
								$data3['telaah_id'] = $this->input->post('telaah_id');
								$data3['timeline_sekda_id'] = 1; 
								$data3['timeline_sekda_date'] = date("Y-m-d H:i:s");
								$data3['timeline_sekda_disposisi'] = "ACC";
								$this->m_disposisi->update_timeline_5($data3,'');
								
							}
								
								$log['kode_log_action'] = "57";
								$log['action'] = "ACC/DISPOSISI";
								$log['kode_log_action_table'] = "16";
								$log['action_table'] = "Tracking SPPD";
								$this->m_log->create($log);
						}
						if($this->input->post('telaah_kategori')==6 && $this->input->post('posisi')=='sekwan'){
							$data3['telaah_id'] = $this->input->post('telaah_id');
							$data3['timeline_sekwan_id'] = 1; 
							$data3['timeline_sekwan_date'] = date("Y-m-d H:i:s");
							$data3['timeline_sekwan_disposisi'] = "ACC";
							$this->m_disposisi->update_timeline_6($data3,'');
							
							$data4['telaah_id'] = $this->input->post('telaah_id');
							$data4['telaah_status'] = 2;
							$this->m_disposisi->update_timeline_6($data3, $data4);
							
							$log['kode_log_action'] = "57";
							$log['action'] = "ACC/DISPOSISI";
							$log['kode_log_action_table'] = "16";
							$log['action_table'] = "Tracking SPPD";
							$this->m_log->create($log);
						}
						if($this->input->post('telaah_kategori')==7 && $this->input->post('posisi')=='lurah'){
							$data3['telaah_id'] = $this->input->post('telaah_id');
							$data3['timeline_lurah_id'] = 1; 
							$data3['timeline_lurah_date'] = date("Y-m-d H:i:s");
							$data3['timeline_lurah_disposisi'] = 'ACC';
							$this->m_disposisi->update_timeline_7($data3,'');
							
							$data4['telaah_id'] = $this->input->post('telaah_id');
							$data4['telaah_status'] = 1;
							$this->m_disposisi->update_timeline_7($data3, $data4);
							
							$log['kode_log_action'] = "57";
							$log['action'] = "ACC/DISPOSISI";
							$log['kode_log_action_table'] = "17";
							$log['action_table'] = "Tracking SPPD";
							$this->m_log->create($log);
						}
						if($this->input->post('telaah_kategori')==7 && $this->input->post('posisi')=='camat'){
							$data3['telaah_id'] = $this->input->post('telaah_id');
							$data3['timeline_camat_id'] = 1; 
							$data3['timeline_camat_date'] = date("Y-m-d H:i:s");
							$data3['timeline_camat_disposisi'] = 'ACC';
							$this->m_disposisi->update_timeline_7($data3,'');
							
							$data4['telaah_id'] = $this->input->post('telaah_id');
							$data4['telaah_status'] = 2;
							$this->m_disposisi->update_timeline_7($data3, $data4);
						
							$log['kode_log_action'] = "57";
							$log['action'] = "ACC/DISPOSISI";
							$log['kode_log_action_table'] = "17";
							$log['action_table'] = "Tracking SPPD";
							$this->m_log->create($log);
						}
						if($this->input->post('telaah_kategori')==8 && $this->ion_auth->get_users_groups()->row()->id == 6){
							$data3['telaah_id'] = $this->input->post('telaah_id');
							$data3['timeline_sekda_id'] = 1; 
							$data3['timeline_sekda_date'] = date("Y-m-d H:i:s");
							$data3['timeline_sekda_disposisi'] = 'ACC';
							$this->m_disposisi->update_timeline_8($data3,'');
							
							$log['kode_log_action'] = "57";
							$log['action'] = "ACC/DISPOSISI";
							$log['kode_log_action_table'] = "17";
							$log['action_table'] = "Tracking SPPD";
							$this->m_log->create($log);
						} if($this->input->post('telaah_kategori')==8 && $this->ion_auth->get_users_groups()->row()->id == 8){
							$data3['telaah_id'] = $this->input->post('telaah_id');
							$data3['timeline_walikota_id'] = 1; 
							$data3['timeline_walikota_date'] = date("Y-m-d H:i:s");
							$data3['timeline_walikota_disposisi'] = 'ACC';
							$this->m_disposisi->update_timeline_8($data3,'');
							
							$data4['telaah_id'] = $this->input->post('telaah_id');
							$data4['telaah_status'] = 2;
							$this->m_disposisi->update_timeline_8($data3, $data4);
						
							$log['kode_log_action'] = "57";
							$log['action'] = "ACC/DISPOSISI";
							$log['kode_log_action_table'] = "17";
							$log['action_table'] = "Tracking SPPD";
							$this->m_log->create($log);
						} 
						if($this->input->post('telaah_kategori')==9 && $this->input->post('posisi')=='sekda'){
							$data3['telaah_id'] = $this->input->post('telaah_id');
							$data3['timeline_sekda_id'] = 1; 
							$data3['timeline_sekda_date'] = date("Y-m-d H:i:s");
							$data3['timeline_sekda_disposisi'] = 'ACC';
							$this->m_disposisi->update_timeline_9($data3,'');
							
							$data4['telaah_id'] = $this->input->post('telaah_id');
							$data4['telaah_status'] = 2;
							$this->m_disposisi->update_timeline_9($data3, $data4);
						
							$log['kode_log_action'] = "57";
							$log['action'] = "ACC/DISPOSISI";
							$log['kode_log_action_table'] = "17";
							$log['action_table'] = "Tracking SPPD";
							$this->m_log->create($log);
						} 
						if($this->input->post('telaah_kategori')==10 && $this->input->post('posisi')=='sekwan'){
							$data3['telaah_id'] = $this->input->post('telaah_id');
							$data3['timeline_sekwan_id'] = 1; 
							$data3['timeline_sekwan_date'] = date("Y-m-d H:i:s");
							$data3['timeline_sekwan_disposisi'] = 'ACC';
							$this->m_disposisi->update_timeline_10($data3,'');
							
							$log['kode_log_action'] = "57";
							$log['action'] = "ACC/DISPOSISI";
							$log['kode_log_action_table'] = "17";
							$log['action_table'] = "Tracking SPPD";
							$this->m_log->create($log);
						}if($this->input->post('telaah_kategori')==10 && $this->input->post('posisi')=='walikota'){
							$data3['telaah_id'] = $this->input->post('telaah_id');
							$data3['timeline_walikota_id'] = 1; 
							$data3['timeline_walikota_date'] = date("Y-m-d H:i:s");
							$data3['timeline_walikota_disposisi'] = 'ACC';
							$this->m_disposisi->update_timeline_10($data3,'');
							
							$data4['telaah_id'] = $this->input->post('telaah_id');
							$data4['telaah_status'] = 2;
							$this->m_disposisi->update_timeline_10($data3, $data4);
						
							$log['kode_log_action'] = "57";
							$log['action'] = "ACC/DISPOSISI";
							$log['kode_log_action_table'] = "17";
							$log['action_table'] = "Tracking SPPD";
							$this->m_log->create($log);
						}
						if($this->input->post('telaah_kategori')==11 && $this->input->post('posisi')=='kapus'){
							$data3['telaah_id'] = $this->input->post('telaah_id');
							$data3['timeline_kapus_id'] = 1; 
							$data3['timeline_kapus_date'] = date("Y-m-d H:i:s");
							$data3['timeline_kapus_disposisi'] = 'ACC';
							$this->m_disposisi->update_timeline_11($data3,'');
							
							$data4['telaah_id'] = $this->input->post('telaah_id');
							$data4['telaah_status'] = 2;
							$this->m_disposisi->update_timeline_11($data3, $data4);
						
							$log['kode_log_action'] = "57";
							$log['action'] = "ACC/DISPOSISI";
							$log['kode_log_action_table'] = "17";
							$log['action_table'] = "Tracking SPPD";
							$this->m_log->create($log);
						}
						
						// if($send_sign_request['id_signed']=="" && $send_sign_request3['id_signed']==""){
							// $this->session->set_flashdata('notif','<p class="alert alert-info text-center"><b>Server BSRE Tidak Merespon !! SPPD Menggunakan Tanda Tangan Manual</b></p>');
							
							// $log_tte['telaah_id'] = $this->input->post('telaah_id');
							// $log_tte['pegawai_id'] = $data[0]['pegawai_id'];
							// $log_tte['action'] = 'Server BSRE Tidak Merespon !! SPPD Menggunakan Tanda Tangan Manual';
							// $log_tte['action_table'] = "Tracking SPPD";
							// $this->m_log->create_tte($log_tte);
							
							// $data5['telaah_id'] = $this->input->post('telaah_id');
							// if($this->input->post('telaah_kategori')==1 || $this->input->post('telaah_kategori')==6 
								// || $this->input->post('telaah_kategori')==7 
								// || $this->input->post('telaah_kategori')==9 || $this->input->post('telaah_kategori')==11){
									// $data5['telaah_tte'] = $filename_tte_sppd.'.pdf';
									// $data5['telaah_tte2'] = $filename_tte_spt.'.pdf';
							// } else {
								// if($send_sign_request){
									// $data5['telaah_tte'] = $filename_tte_sppd.'.pdf';
								// }
								// if($send_sign_request3){
									// $data5['telaah_tte2'] = $filename_tte_spt.'.pdf';
								// }
							// }
							// $this->m_telaah->update($data5);
							
							// redirect('telaah/disposisi/telaah_disetujui/'.$this->input->post('posisi'));
						// } else {
							$this->session->set_flashdata('notif','<p class="alert alert-success text-center"><b>SPPD Menggunakan Tanda Tangan Elektronik</b></p>');
							
							$log_tte['telaah_id'] = $this->input->post('telaah_id');
							$log_tte['pegawai_id'] = $data[0]['pegawai_id'];
							$log_tte['action'] = 'TTE BERHASIL';
							$log_tte['action_table'] = "Tracking SPPD";
							$this->m_log->create_tte($log_tte);
							
							$data5['telaah_id'] = $this->input->post('telaah_id');
							$data5['status_tte'] = 1;
							if($send_sign_request){
								$data5['telaah_tte'] = $filename_tte_sppd.'.pdf';
							}
							if($send_sign_request3){
								$data5['telaah_tte2'] = $filename_tte_spt.'.pdf';
							}
							$data5['telaah_reset_tte'] = 0;
							$data5['telaah_reset_tte2'] = 0;
							$this->m_telaah->update($data5);
						
							if($this->input->post('tte')=='tte'){
								$data8['tte_id'] = $this->input->post('tte_id');
								$data8['status_tte'] = 1;
								$this->m_tte->update($data8);
								
								redirect('telaah/tte/index/'.$this->input->post('posisi'));
							} else if($this->input->post('posisi')=='walikota2'){
								redirect('telaah/disposisi/index/'.$this->input->post('posisi'));
							} else {
								redirect('telaah/disposisi/telaah_disetujui/'.$this->input->post('posisi'));
							}
						// }
						
						}
			 } else {
				$this->session->set_flashdata('notif',$status_nik['message']);
						
				$log_tte['telaah_id'] = $this->input->post('telaah_id');
				$log_tte['pegawai_id'] = $data[0]['pegawai_id'];
				$log_tte['action'] = $status_nik['message'];
				$log_tte['action_table'] = "Tracking SPPD";
				$this->m_log->create_tte($log_tte);
				redirect('telaah/disposisi/lihat_laporan/'.$this->input->post('posisi').'/'.$this->input->post('telaah_kategori').'?telaah_id='.$telaah_id);
			 }
		}
	}
	
	/*API BSSN*/
	function getToken()
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
		## demo
		CURLOPT_URL => "https://api-bsre.bssn.go.id/token/",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => "grant_type=client_credentials",
		CURLOPT_HTTPHEADER => array(
				"Authorization: Basic MV9wZmNkYnNpTWp2Y2VUR1RPclBHOE4yYlVnYTpPbTBPR3gzdFJESGhxanc3WWxlVzdSUWJZTmNh",
				"cache-control: no-cache",
				"Content-Type: application/x-www-form-urlencoded",
				),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		return $response;
	}


	function sendSignRequest($filename, $y, $height, $nik, $token )
	{
        $curl 	= curl_init();
        $file1 	= $_SERVER['DOCUMENT_ROOT'].'/upload/doc_dummy/'.$filename;
        $filenames = array($file1);
        foreach ($filenames as $f){
            $files[$f] = file_get_contents($f);
        }

				$boundary = uniqid();
        $delimiter = '-------------' . $boundary;
        $post_data = $this->build_data_files($boundary, $files);

		curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api-bsre.bssn.go.id/esign/v2/api/entity/sign/request?penandatangan=".$nik."&tampilan=visible&image=false&linkQR=http://sppd.kendarikota.go.id/&halaman=pertama&yAxis=".$y."&xAxis=105&width=545&height=".$height."",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => $post_data,
		CURLOPT_HTTPHEADER => array(
		   "Authorization: Bearer ".$token,
		  "cache-control:no-cache",
		  "Content-Type: multipart/form-data; boundary=" . $delimiter,
		  "Content-Length: " . strlen($post_data)
		),
		));

		$response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
       	return $response;
    }
	
	function sendSignRequest4($filename, $nik, $token)
	{
        $curl 	= curl_init();
        $file1 	= $_SERVER['DOCUMENT_ROOT'].'/upload/doc_TTE/'.$filename.".pdf";
        $filenames = array($file1);
        foreach ($filenames as $f){
            $files[$f] = file_get_contents($f);
        }

				$boundary = uniqid();
        $delimiter = '-------------' . $boundary;
        $post_data = $this->build_data_files($boundary, $files);

		curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api-bsre.bssn.go.id/esign/v2/api/entity/sign/request?penandatangan=".$nik."&tampilan=visible&image=false&linkQR=http://sppd.kendarikota.go.id/&halaman=kedua&yAxis=830&xAxis=290&width=545&height=770",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => $post_data,
		CURLOPT_HTTPHEADER => array(
		  "Authorization: Bearer ".$token,
		  "cache-control:no-cache",
		  "Content-Type: multipart/form-data; boundary=" . $delimiter,
		  "Content-Length: " . strlen($post_data)
		),
		));

		$response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
       	return $response;
    }
	
	function sendSignRequest2($filename, $halaman, $y, $height, $nik, $token )
	{
		if($halaman==2){
			$x = "kedua";
		} else {
			$x = "pertama";
		}
		
        $curl 	= curl_init();
        $file1 	= $_SERVER['DOCUMENT_ROOT'].'/upload/doc_dummy/'.$filename;
        $filenames = array($file1);
        foreach ($filenames as $f){
            $files[$f] = file_get_contents($f);
        }

				$boundary = uniqid();
        $delimiter = '-------------' . $boundary;
        $post_data = $this->build_data_files($boundary, $files);

		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://api-bsre.bssn.go.id/esign/v2/api/entity/sign/request?penandatangan=".$nik."&tampilan=visible&image=false&linkQR=http://sppd.kendarikota.go.id/&halaman=".$x."&yAxis=".$y."&xAxis=220&width=545&height=".$height."",
			  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_SSL_VERIFYPEER => false,
		      CURLOPT_SSL_VERIFYHOST => false,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "POST",
				  CURLOPT_POSTFIELDS => $post_data,
				  CURLOPT_HTTPHEADER => array(
		              "Authorization: Bearer ".$token,
		              "cache-control:no-cache",
		              "Content-Type: multipart/form-data; boundary=" . $delimiter,
		              "Content-Length: " . strlen($post_data)
		          ),
				));

				$response = curl_exec($curl);
				//var_dump($response);
        $err = curl_error($curl);

        curl_close($curl);
       	return $response;
    }

	function sendSignRequest3($filename, $halaman, $y, $height, $nik, $token )
	{
		if($halaman==2){
			$x = "kedua";
		} else {
			$x = "pertama";
		}
		
        $curl 	= curl_init();
        $file1 	= $_SERVER['DOCUMENT_ROOT'].'/upload/doc_dummy/'.$filename;
        $filenames = array($file1);
        foreach ($filenames as $f){
            $files[$f] = file_get_contents($f);
        }

				$boundary = uniqid();
        $delimiter = '-------------' . $boundary;
        $post_data = $this->build_data_files($boundary, $files);

		curl_setopt_array($curl, array(
         CURLOPT_URL => "https://api-bsre.bssn.go.id/esign/v2/api/entity/sign/request?penandatangan=".$nik."&tampilan=visible&image=false&linkQR=http://sppd.kendarikota.go.id/&halaman=".$x."&yAxis=".$y."&xAxis=220&width=600&height=".$height."",
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_SSL_VERIFYPEER => false,
		      CURLOPT_SSL_VERIFYHOST => false,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "POST",
				  CURLOPT_POSTFIELDS => $post_data,
				  CURLOPT_HTTPHEADER => array(
		              "Authorization: Bearer ".$token,
		              "cache-control:no-cache",
		              "Content-Type: multipart/form-data; boundary=" . $delimiter,
		              "Content-Length: " . strlen($post_data)
		          ),
				));

				$response = curl_exec($curl);
				//var_dump($response);
        $err = curl_error($curl);

        curl_close($curl);
       	return $response;
    }

    function build_data_files($boundary, $files)
	{
        $data = '';
        $eol = "\r\n";

        $delimiter = '-------------' . $boundary;

        foreach ($files as $name => $content) {
            $data .= "--" . $delimiter . $eol
                . 'Content-Disposition: form-data; name="file"; filename="' . $name . '"' . $eol
                . 'Content-Type: Application/pdf'.$eol
            ;

            $data .= $eol;
            $data .= $content . $eol;
        }
        $data .= "--" . $delimiter . "--".$eol;

        return $data;
    }

    function signById($id, $passphrase, $nik, $token )
	{
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api-bsre.bssn.go.id/esign/v2/api/entity/sign/".$id."?passphrase=$passphrase&approved_info=ok&nik=$nik",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".$token,
            "Content-Type: application/x-www-form-urlencoded",
            "cache-control: no-cache"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        return $response;
    }

    function downloadDokumenById($id_signed, $filename, $token )
	{
		$path = './upload/doc_TTE/'.$filename.'.pdf';
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api-bsre.bssn.go.id/esign/v2/api/entity/sign/download/".$id_signed,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".$token,
            "cache-control: no-cache"
        ),
        ));

        $response = curl_exec($curl);
        
        $data = json_decode($response);
        $err = curl_error($curl);

        curl_close($curl);

		file_put_contents($path, $response);
    }

	function cek()
	{
		$cekDokumen = $this->cekDokumen();
		$cekDokumen  = json_decode($cekDokumen , TRUE);
		echo $cekDokumen['SUMMARY']; 
		$this->data['hasil'] = $cekDokumen['SUMMARY']; 
		$this->render('setting_admin/dokumen/content');
	}
	
	function cekDokumen()
	{
		
		$filename = $this->input->post('userfile');
		$config['upload_path'] = './upload/cek dokumen/';
		$config['allowed_types'] = "pdf";
		$config['overwrite']="true";
		$config['max_size']="20000000";
		$config['max_width']="10000";
		$config['max_height']="10000";
		$config['file_name'] = ''.$filename;
		$this->upload->initialize($config);

		if(!$this->upload->do_upload()){
			echo  $this->upload->display_errors();

		}else {
			//$filename = $dat['file_name'];
			$filename = $dat['file_name'];
			$path_file = './upload/cek dokumen';
			unlink($path_file.$filename);
			
			$dat = $this->upload->data();
			
			$data['file'] = $dat['file_name'];
			
			$curl = curl_init();
			$file1 	= file_get_contents($_FILES["userfile"]["name"]);
			$file1 	= $_SERVER['DOCUMENT_ROOT'].'/sppd-dev/upload/cek dokumen/'.$data['file'];
			
			$filenames = array($file1);

			foreach ($filenames as $f){
				$files[$f] = file_get_contents($f);
			}

			$boundary = uniqid();
			$delimiter = '-------------' . $boundary;
			$post_data = $this->build_data_files2($boundary, $files);

			curl_setopt_array($curl, array(
			  
			  CURLOPT_URL => "https://esign-bsre.bssn.go.id/api/v2/entity/verify/",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_SSL_VERIFYPEER => false,
			  CURLOPT_SSL_VERIFYHOST => false,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => $post_data,
			  CURLOPT_HTTPHEADER => array(
				  "Authorization: Bearer 5adf074e-c995-450b-af5f-009c137b80e5",
				  "cache-control:no-cache",
				  "Content-Type: multipart/form-data; boundary=" . $delimiter,
				  "Content-Length: " . strlen($post_data)
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);
			return $response;
			
		}
	}
	
	function doCekStatus($nik, $token )
	{
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api-bsre.bssn.go.id/esign/v2/api/entity/status/".$nik,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".$token,
            "cache-control: no-cache"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        return $response;
    }

	
}