<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Laporan extends public_Controller {
    function __construct()
    {
        parent::__construct();
		error_reporting(0);
		$this->load->model('laporan/m_laporan');
		$this->load->model('telaah/m_pengikut');
		$this->load->model('telaah/m_timeline');
		$this->load->model('laporan/m_rincian');
		$this->load->model('laporan/m_pengeluaran_rill');
		$this->load->model('laporan/m_pptk_pengeluaran_rill');
		$this->load->model('laporan/m_kuitansi');
		$this->load->model('telaah/m_relasi_sekda');
		$this->load->model('telaah/m_lokasi_tujuan');
		$this->load->model('telaah/m_telaah');
		$this->load->model('setting/m_log');
		
    }

	public function dd($data)
	{
		echo "<pre>";
		print_r($data);
		echo "</pre>";
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
		
		// variabel pecahkan 0 = tanggal
		// variabel pecahkan 1 = bulan
		// variabel pecahkan 2 = tahun
	 
		return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
	}
	
	## SPD OPD
	function cetak_spd(){
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		$telaah =  $this->m_telaah->get($telaah_id);
		
		## Hapus PDF
		$filename = 'SPPD - '.$telaah[0]['pegawai_nama'].' - '.date("d-m-Y", strtotime($telaah[0]['telaah_tanggalspd'])).'.pdf';
		$path_file = './upload/doc_dummy/';
		unlink($path_file.$filename);
		
		switch($this->uri->segment(5)){
			case "opd"			: if($this->uri->segment(6)==1){
									$data = $this->m_laporan->get_pelaksana_opd($telaah_id);
								} else if($this->uri->segment(6)==2){
									$data = $this->m_laporan->get_pengikut_opd($telaah_id, $this->input->get('pengikut_id'));
								} 
								break;
			case "dprd"			: if($this->uri->segment(6)==1){
									$data = $this->m_laporan->get_pelaksana_dprd($telaah_id);
								} else if($this->uri->segment(6)==2){
									$data = $this->m_laporan->get_pengikut_dprd($telaah_id, $this->input->get('pengikut_id'));	
								} 
								break;
			case "walikota"		: if($this->uri->segment(6)==1){
									$data = $this->m_laporan->get_pelaksana_walikota($telaah_id);
								} else if($this->uri->segment(6)==2){
									$data = $this->m_laporan->get_pengikut_dprd($telaah_id, $this->input->get('pengikut_id'));	
								} 
								break;
		}
		
		$lokasi_tujuan = $this->m_lokasi_tujuan->get2($telaah_id);
		$tanda_tangan = $this->m_laporan->tanda_tangan($data[0]['telaah_ttdspd']);
		
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
			$pdf-> Image('./upload/kop_surat/'.$data[0]['kop_surat'],20,16,170,30);
		}
		
		// QRcode::png('https://sppd.kendarikota.go.id/telaah/laporan/qr_new/sppd?telaah_id='.$this->input->get('telaah_id'),"test.png");
		// $pdf->Image("test.png", 185, 10, 20, 20, "png");
		
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
		
		if($this->uri->segment(6)==1 && $this->uri->segment(5)!="dprd"){
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
			$x=$pdf->GetX();
			$y=$pdf->GetY();
			$pdf->MultiCell(10,0,$pdf-> Image('./upload/tanda_tangan/'.$tanda_tangan[0]['pegawai_tandatangan'],105,$y,40,15),0,0);
		}		
		
		$pdf->Cell(10,35,'',0,1);
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
		$pdf->Cell(0,10,'Tanda Tangan Digital pada surat ini menggunakan Layanan BSRE',0,0,'R');
	
	
		
		// membuat halaman baru
		$pdf->AddPage();

		$pdf->SetFont('Arial','',8);
		$pdf->Cell(10,40,'',0,1);
		$pdf->Cell(80,4,'','LTR',0);
		$pdf->Cell(5,4,'','TL',0);
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
			
		if($tanda_tangan[0]['status_tandatangan']==1){
			$x=$pdf->GetX();
			$y=$pdf->GetY();
			$pdf->MultiCell(10,0,$pdf-> Image('./upload/tanda_tangan/'.$tanda_tangan[0]['pegawai_tandatangan'],125,$y,40,15),0,0);
		}	
		
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
				$caption = array('I.','Tiba Di',': '.$data[0]['kabupaten_kota'],'','Berangkat dari',': '.$data[0]['kabupaten_kota']);
				$pdf->Row($caption, $border, $align);
			} else {
				$pdf->Cell(5,4,'I.','LT',0);
				$pdf->Cell(30,4,'Tiba Di','T',0);
				$pdf->Cell(45,4,':','T',0);
				$pdf->Cell(5,4,'','LT',0);
				$pdf->Cell(30,4,'Berangkat dari','T',0);
				$pdf->Cell(45,4,':','TR',1);
			}
		} else {
			$pdf->Cell(5,4,'I.','LT',0);
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
		$pdf->Cell(45,4,': '.date("d-m-Y", strtotime($data[0]['telaah_tanggalkembali'])),'R',1);
		
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
		
		$pdf->Cell(5,4,'II.','LT',0);
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
		/*if($data[0]['telaah_domainperjalanan']==1 || $data[0]['telaah_domainperjalanan']==2 ){
			$pdf->SetWidths(array(5,30,45,5,30,45));
			$border = array('LT','T','T','LT','T','TR');
			$align = array('','','','','','');
			$caption = array('III','Tiba Di',': '.$lokasi_tujuan[1]['kabupaten_kota'],'','Berangkat dari',': '.$lokasi_tujuan[1]['kabupaten_kota']);
			$pdf->Row($caption, $border, $align);
		} else {*/
			$pdf->Cell(5,4,'III','LT',0);
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
		
		//baris 4
		if($data[0]['skpd_id']==38){
		/*if($data[0]['telaah_domainperjalanan']==1 || $data[0]['telaah_domainperjalanan']==2 ){
			$pdf->SetWidths(array(5,30,45,5,30,45));
			$border = array('LT','T','T','LT','T','TR');
			$align = array('','','','','','');
			$caption = array('III','Tiba Di',': '.$lokasi_tujuan[1]['kabupaten_kota'],'','Berangkat dari',': '.$lokasi_tujuan[1]['kabupaten_kota']);
			$pdf->Row($caption, $border, $align);
		} else {*/
			$pdf->Cell(5,4,'IV','LT',0);
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
		
		//baris 5
		if(($data[0]['telaah_kategori']==1 && $data[0]['jenis_skpd']!=7) || ($data[0]['telaah_kategori']==2)){
			if($data[0]['telaah_domainperjalanan']==1 || $data[0]['telaah_domainperjalanan']==2 ){
				$pdf->SetWidths(array(5,30,45,80));
				$border = array('LT','T','RT','LRT');
				$align = array('','','','');
				if($data[0]['skpd_id']==38){
					$caption = array('V.','Tiba Di',': Kendari','Telah diperiksa dengan keterangan bahwa');
				}else {
					$caption = array('IV','Tiba Di',': Kendari','Telah diperiksa dengan keterangan bahwa');
				}
				$pdf->Row($caption, $border, $align);
			} else {
				if($data[0]['skpd_id']==38){
					$pdf->Cell(5,4,'V.','LT',0);
				}else {
					$pdf->Cell(5,4,'IV.','LT',0);
				}
				$pdf->Cell(30,4,'Tiba Di','T',0);
				$pdf->Cell(45,4,':','RT',0);
				$pdf->Cell(80,4,'Telah diperiksa dengan keterangan bahwa','lRT',1);
			}
		} else {
			if($data[0]['skpd_id']==38){
				$pdf->Cell(5,4,'V.','LT',0);
			}else {
				$pdf->Cell(5,4,'IV.','LT',0);
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
		
		if($data[0]['jenis_skpd']==7 || $data[0]['jenis_skpd']==10 ){
			if($tanda_tangan[0]['status_tandatangan']==1){
				$x=$pdf->GetX();
				$y=$pdf->GetY()-20;
				$pdf->MultiCell(10,0,$pdf-> Image('./upload/tanda_tangan/'.$tanda_tangan[0]['pegawai_tandatangan'],47,$y,40,15),0,0);
				$pdf->MultiCell(10,0,$pdf-> Image('./upload/tanda_tangan/'.$tanda_tangan[0]['pegawai_tandatangan'],127,$y,40,15),0,0);	
			}	
		}
		
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
			$pdf->Cell(5,4,'VI.','LTB',0);
		} else {
			$pdf->Cell(5,4,'V.','LTB',0);
		}
		$pdf->Cell(155,4,'Keterangan Lain-lain','RTB',1);
		
		if($data[0]['skpd_id']==38){
			$pdf->Cell(5,4,'VII.','LT',0);
		} else {
			$pdf->Cell(5,4,'VI.','LT',0);
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
		$pdf->Cell(0,10,'Tanda Tangan Digital pada surat ini menggunakan Layanan BSRE',0,0,'R');
		
		//$pdf->Output('D','SPPD - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y", strtotime($data[0]['telaah_tanggalspd'])).'.pdf'); 
	
		//$path = "D:/workspace/sppd-dev/upload/AAA.pdf";
		//$path = base_url().'upload/doc_dummy/1.pdf';
		$filename = 'SPPD - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y", strtotime($data[0]['telaah_tanggalspd'])).'.pdf';
		$path = "./upload/doc_dummy/$filename";
		$pdf->Output($path,'F');
    }
	
	// SPT OPD (esselon)
	function cetak_spt(){
		
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		switch($this->uri->segment(5)){
			case "esselon"		:
			case "camat"		:
			case "staff_setda"	:
			case "kapus"		:
			case "staff_camat"	: $data = $this->m_laporan->get_pelaksana_opd($telaah_id);
								  $data2 = $this->m_laporan->get_pengikut2($telaah_id);
								  break;
			case "kadis"		: if($this->uri->segment(6)==1){
									## Pengikut
									$data = $this->m_laporan->get_pengikut_opd($telaah_id, $this->input->get('pengikut_id'));
								} else if($this->uri->segment(6)==2){
									## Pelaksana
									$data = $this->m_laporan->get_pelaksana_opd($telaah_id);
								} 
								break;
			case "dprd"			: if($this->uri->segment(6)==1){
									$data = $this->m_laporan->get_pelaksana_dprd($telaah_id);
								} else if($this->uri->segment(6)==2){
									$data = $this->m_laporan->get_pengikut_dprd($telaah_id, $this->input->get('pengikut_id'));	
								} 
								break;
			case "sekwan"		: $data = $this->m_laporan->get_pelaksana_opd($telaah_id);
								  break;
			case "sekda"		: if($this->uri->segment(6)==1){
									## Pengikut
									$data = $this->m_laporan->get_pengikut_opd($telaah_id, $this->input->get('pengikut_id'));
								} else if($this->uri->segment(6)==2){
									## Pelaksana
									$data = $this->m_laporan->get_pelaksana_opd($telaah_id);
									$data2 = $this->m_laporan->get_pengikut2($telaah_id);
								} 
								  break;
			case "walikota"		: if($this->uri->segment(6)==1){
									$data = $this->m_laporan->get_pelaksana_walikota($telaah_id);
								} else if($this->uri->segment(6)==2){
									$data = $this->m_laporan->get_pengikut_dprd($telaah_id, $this->input->get('pengikut_id'));	
								} 
								break;
		}
		
		$lokasi_tujuan = $this->m_lokasi_tujuan->get2($telaah_id);
		
		
		## TTD BUKAN WALIKOTA		
		if($data[0]['telaah_ttdsptw']==0){
			if($this->uri->segment(5)=="kadis" && $this->uri->segment(6)==1){
				$tanda_tangan = $this->m_laporan->tanda_tangan_kepala_opd($data[0]['skpd_id']);
			} else if($this->uri->segment(5)=="sekda" && $this->uri->segment(6)==2){
				$tanda_tangan = $this->m_laporan->tanda_tangan_sekda();
			} else {	
				$tanda_tangan = $this->m_laporan->tanda_tangan($data[0]['telaah_ttdspt']);
			}
		
		## TTD WALIKOTA		
		} else {
			if(($this->uri->segment(5)=="kadis" && $this->uri->segment(6)==2) 
				|| ($this->uri->segment(5)=="sekda" && $this->uri->segment(6)==2)
				|| $this->uri->segment(5)=="sekwan" 
				|| $this->uri->segment(5)=="camat"){
				$tanda_tangan = $this->m_laporan->tanda_tangan_walikota($data[0]['telaah_ttdspt']);
			} else if($this->uri->segment(5)=="sekda" && $this->uri->segment(6)==1){	
				$tanda_tangan = $this->m_laporan->tanda_tangan_sekda();
			} else {	
				$tanda_tangan = $this->m_laporan->tanda_tangan_kepala_opd($data[0]['skpd_id']);
			}
		}
		
        $pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(25, 3.175, 25);
		$pdf->SetAutoPageBreak(false);
        // membuat halaman baru
        $pdf->AddPage();
		$pdf->SetTopMargin(25);
		//$pdf-> Image('./assets2/dist/img/kota_kendari.png',20,10,160,20);
		if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']!=11){
			$dinas_kesehatan = $this->m_laporan->get_dinas_kesehatan();
			$pdf-> Image('./upload/kop_surat/'.$dinas_kesehatan[0]['kop_surat'],20,16,170,30);
		} else if($data[0]['telaah_kategori']==2){
			if($this->uri->segment(6)==1){
				$pdf-> Image('./upload/kop_surat/'.$data[0]['kop_surat'],20,16,170,30);
			} else if($this->uri->segment(6)==2) {	
				$pdf-> Image('./assets2/dist/img/garuda.png',90,10,30,30);
			}
		} else if($this->uri->segment(5)=="camat" || $this->uri->segment(5)=="sekwan" || ($this->uri->segment(5)=="sekda" && $this->uri->segment(6)==2)){
			$pdf-> Image('./assets2/dist/img/garuda.png',90,10,30,30);
		} else {
			$pdf-> Image('./upload/kop_surat/'.$data[0]['kop_surat'],20,16,170,30);
		}
		
		//$pdf->Image(base_url()."admin/qr_generator?code=sppdkotakendari", 10, 270, 20, 20, "png");
		// QRcode::png('https://sppd.kendarikota.go.id/telaah/laporan/qr_new/sppd?telaah_id='.$this->input->get('telaah_id'),"test.png");
		// $pdf->Image("test.png", 185, 10, 20, 20, "png");
		
		if(($this->uri->segment(5)=="kadis" && $this->uri->segment(6)==2) 
			|| ($this->uri->segment(5)=="sekda" && $this->uri->segment(6)==2) 
			|| $this->uri->segment(5)=="sekwan" || $this->uri->segment(5)=="camat"){
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
		
		if(($this->uri->segment(5)=="kadis" && $this->uri->segment(6)==2) 
			|| ($this->uri->segment(5)=="sekda" && $this->uri->segment(6)==2)
			|| $this->uri->segment(5)=="camat"){
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
			} else if($data[0]['jenis_skpd'] == 3){
				$pdf->Cell(140,6,': '.$skpd_nama2,0,1);
			} else {
				$pdf->Cell(140,6,': Kepala '.$skpd_nama2,0,1);
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
		if(($this->uri->segment(5)=="kadis" && $this->uri->segment(6)==1)
			||($this->uri->segment(5)=="kadis" && $this->uri->segment(6)==2)
			||($this->uri->segment(5)=="sekda" && $this->uri->segment(6)==1)){
			
		} else { 
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
		}
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
			if($this->uri->segment(5)=="sekwan"){
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
			$x=$pdf->GetX();
			$y=$pdf->GetY();
			$pdf->MultiCell(10,0,$pdf-> Image('./upload/tanda_tangan/'.$tanda_tangan[0]['pegawai_tandatangan'],128,$y,40,15),0,0);
		}	
		
		$pdf->Cell(10,40,'',0,1);	
		$pdf->Cell(100,6,'',0,0);
		$pdf->Cell(60,6,$tanda_tangan[0]['pegawai_nama'],0,1);
		
		if($data[0]['telaah_ttdsptw']==0){
			$pdf->Cell(100,6,'',0,0);
			$pdf->Cell(60,6,$tanda_tangan[0]['pangkat'].", Gol. ".$tanda_tangan[0]['pegawai_golongan'],0,1);
			$pdf->Cell(100,6,'',0,0);
			$pdf->Cell(60,6,'NIP.'. $tanda_tangan[0]['pegawai_nip'],0,1);
		} else {
			if(($this->uri->segment(5)=="kadis" && $this->uri->segment(6)==2) 
				|| ($this->uri->segment(5)=="sekda" && $this->uri->segment(6)==2) 
				|| ($this->uri->segment(5)=="sekwan" && $tanda_tangan[0]['pegawai_jabatan']==1)
				|| $this->uri->segment(5)=="camat"){
				
			} else {	
				$pdf->Cell(100,6,'',0,0);
				$pdf->Cell(60,6,$tanda_tangan[0]['pangkat'].", Gol. ".$tanda_tangan[0]['pegawai_golongan'],0,1);
				$pdf->Cell(100,6,'',0,0);
				$pdf->Cell(60,6,'NIP.'. $tanda_tangan[0]['pegawai_nip'],0,1);
			}
		}
		
		$pdf->Cell(10,7,'',0,1);
		if($this->ion_auth->user()->row()->jenis_skpd == 2){
			if($this->uri->segment(5)=="sekwan"){
				
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
		$pdf->Cell(0,10,'Tanda Tangan Digital pada surat ini menggunakan Layanan BSRE',0,0,'R');
		
		
		
		$pdf->Output('D','SPT - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y", strtotime($data[0]['telaah_tanggalspt'])).'.pdf'); 
    }
	
	function cetak_spt_dprd(){
		
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$data = $this->m_laporan->get_pelaksana_dprd($telaah_id);
		$data2 = $this->m_laporan->get_pengikut_dprd2($telaah_id);
		
		$tanda_tangan = $this->m_laporan->tanda_tangan($data[0]['telaah_ttdspt']);
		
		
        $pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(25, 3.175, 25);
		$pdf->SetAutoPageBreak(false);
        $pdf->AddPage();
		$pdf-> Image('./upload/kop_surat/'.$data[0]['kop_surat2'],20,16,170,30);
		// QRcode::png('https://sppd.kendarikota.go.id/telaah/laporan/qr_new/sppd?telaah_id='.$this->input->get('telaah_id'),"test.png");
		// $pdf->Image("test.png", 190, 5, 20, 20, "png");
		
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
        $pdf->Cell(150,6,'Perda Kota Kendari No. 14 Tahun 2020 tentang APBD Kota Kendari Tahun '.date('Y').';',0,1);
		
        $pdf->Cell(5,6,'',0,0);
        $pdf->Cell(5,6,'b.',0,0);
        $pdf->Cell(150,6,'Peraturan Tata Tertib Dewan Perwakilan Rakyat Daerah Kota Kendari;',0,1);
		
        $pdf->Cell(5,6,'',0,0);
        $pdf->Cell(5,6,'c.',0,0);
        $pdf->Cell(150,6,'Program Kerja DPRD Kota Kendari Tahun '.date('Y').';',0,1);
		
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
		if($tanda_tangan[0]['pegawai_jabatan']==5){
			$pdf->Cell(80,5,'KETUA DEWAN PERWAKILAN RAKYAT DAERAH',0,1,'C');
		} else if($tanda_tangan[0]['pegawai_jabatan']==20){
			$pdf->Cell(80,5,'WAKIL KETUA DEWAN PERWAKILAN RAKYAT DAERAH',0,1,'C');
		} 
		
        $pdf->Cell(80,5,'',0,0);
        $pdf->Cell(80,5,'KOTA KENDARI',0,1,'C');
		
		if($tanda_tangan[0]['status_tandatangan']==1){
			$x=$pdf->GetX();
			$y=$pdf->GetY();
			$pdf->MultiCell(10,0,$pdf-> Image('./upload/tanda_tangan/'.$tanda_tangan[0]['pegawai_tandatangan'],125,$y,40,15),0,0);
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
		$pdf->Cell(0,10,'Tanda Tangan Digital pada surat ini menggunakan Layanan BSRE',0,0,'R');
		
		$pdf->Output('D','SPT - '.$data[0]['anggotadprd_name'].' - '.date("d-m-Y", strtotime($data[0]['telaah_tanggalspt'])).'.pdf'); 
    }
	
	//KUITANSI PANJAR
	public function cetak_kuitansi_panjar(){
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		switch($this->uri->segment(5)){
			case "pelaksana"		:  	$data = $this->m_laporan->get_rincian_pelaksana($telaah_id, $this->input->get('pegawai_id'));									  
										if($data[0]['telaah_kategori']==1 && $data[0]['telaah_jenis_skpd']==7 ){
											$data2 = $this->m_laporan->get_kepala_opd(36);			
										} else if($data[0]['telaah_kategori']==1 && $data[0]['telaah_jenis_skpd']!=7 ) {
											$data2 = $this->m_laporan->get_kepala_opd($this->ion_auth->user()->row()->skpd_id);			
										} else if($data[0]['telaah_kategori']==2) {
											$data2 = $this->m_laporan->get_kepala_opd($this->ion_auth->user()->row()->skpd_id);			
										} else if($data[0]['telaah_kategori']==3){
											$data2 = $this->m_laporan->get_sekwan();
										} else if($data[0]['telaah_kategori']==4){
											$data2 = $this->m_laporan->get_sekda(3);
										} else if($data[0]['telaah_kategori']==5 && $data[0]['telaah_jenis_skpd']==4){
											$data2 = $this->m_laporan->get_camat($this->ion_auth->user()->row()->skpd_id);
										} else if($data[0]['telaah_kategori']==5 && $data[0]['telaah_jenis_skpd']==5){
											$data2 = $this->m_laporan->get_lurah($this->ion_auth->user()->row()->skpd_id);
										} else if($data[0]['telaah_kategori']==6){
											$data2 = $this->m_laporan->get_sekwan();
										} else if($data[0]['telaah_kategori']==7 && $data[0]['telaah_jenis_skpd']==4){
											$data2 = $this->m_laporan->get_camat($this->ion_auth->user()->row()->skpd_id);
										} else if($data[0]['telaah_kategori']==7 && $data[0]['telaah_jenis_skpd']==5){
											$data2 = $this->m_laporan->get_lurah($this->ion_auth->user()->row()->skpd_id);
										} else if($data[0]['telaah_kategori']==8){
											$data2 = $this->m_laporan->get_sekda(3);
										} else if($data[0]['telaah_kategori']==9){
											$data2 = $this->m_laporan->get_sekda(3);
										} else if($data[0]['telaah_kategori']==10){
											$data2 = $this->m_laporan->get_sekda(3);
										} else if($data[0]['telaah_kategori']==11 && $data[0]['telaah_jenis_skpd']==7 ) {
											$data2 = $this->m_laporan->get_kepala_puskesmas($this->ion_auth->user()->row()->skpd_id);
										} 					  
										$rincian = $this->m_kuitansi->get_kuitansi_panjar($telaah_id,$this->input->get('pegawai_id'));
										break;
			case "pengikut"			:  	$data = $this->m_laporan->get_rincian_pengikut($telaah_id, $this->input->get('pengikut_id'));										  
										if($data[0]['telaah_kategori']==1 && $data[0]['telaah_jenis_skpd']==7 ){
											$data2 = $this->m_laporan->get_kepala_opd(36);			
										} else if($data[0]['telaah_kategori']==1 && $data[0]['telaah_jenis_skpd']!=7 ) {
											$data2 = $this->m_laporan->get_kepala_opd($this->ion_auth->user()->row()->skpd_id);			
										} else if($data[0]['telaah_kategori']==2) {
											$data2 = $this->m_laporan->get_kepala_opd($this->ion_auth->user()->row()->skpd_id);			
										} else if($data[0]['telaah_kategori']==3){
											$data2 = $this->m_laporan->get_sekwan();
										} else if($data[0]['telaah_kategori']==4){
											$data2 = $this->m_laporan->get_sekda(3);
										} else if($data[0]['telaah_kategori']==5 && $data[0]['telaah_jenis_skpd']==4){
											$data2 = $this->m_laporan->get_camat($this->ion_auth->user()->row()->skpd_id);
										} else if($data[0]['telaah_kategori']==5 && $data[0]['telaah_jenis_skpd']==5){
											$data2 = $this->m_laporan->get_lurah($this->ion_auth->user()->row()->skpd_id);
										} else if($data[0]['telaah_kategori']==6){
											$data2 = $this->m_laporan->get_sekwan();
										} else if($data[0]['telaah_kategori']==7 && $data[0]['telaah_jenis_skpd']==4){
											$data2 = $this->m_laporan->get_camat($this->ion_auth->user()->row()->skpd_id);
										} else if($data[0]['telaah_kategori']==7 && $data[0]['telaah_jenis_skpd']==5){
											$data2 = $this->m_laporan->get_lurah($this->ion_auth->user()->row()->skpd_id);
										} else if($data[0]['telaah_kategori']==8){
											$data2 = $this->m_laporan->get_sekda(3);
										} else if($data[0]['telaah_kategori']==9){
											$data2 = $this->m_laporan->get_sekda(3);
										} else if($data[0]['telaah_kategori']==10){
											$data2 = $this->m_laporan->get_sekda(3);
										} else if($data[0]['telaah_kategori']==11 && $data[0]['telaah_jenis_skpd']==7 ) {
											$data2 = $this->m_laporan->get_kepala_puskesmas($this->ion_auth->user()->row()->skpd_id);
										} 	
										$rincian = $this->m_kuitansi->get_kuitansi_panjar($telaah_id,$this->input->get('pengikut_id'));
										break;
			case "pelaksana_dprd"	: 	$data = $this->m_laporan->get_rincian_pelaksana_dprd($telaah_id, $this->input->get('pegawai_id'));
										$data2 = $this->m_laporan->get_pimpinan_dprd();		
										$rincian = $this->m_kuitansi->get_kuitansi_panjar_dprd($telaah_id,$this->input->get('pegawai_id'));
										break;
			case "pengikut_dprd"	: 	$data = $this->m_laporan->get_rincian_pengikut_dprd($telaah_id, $this->input->get('pengikut_id'));
										$data2 = $this->m_laporan->get_pimpinan_dprd();		
										$rincian = $this->m_kuitansi->get_kuitansi_panjar_dprd($telaah_id,$this->input->get('pengikut_id'));
										break;
			case "walikota"			:	$data = $this->m_laporan->get_rincian_walikota($telaah_id, $this->input->get('pegawai_id')); 
										$data2 = $this->m_laporan->get_sekda($this->ion_auth->user()->row()->skpd_id);
										$rincian = $this->m_kuitansi->get_kuitansi_panjar_walikota($telaah_id, $this->input->get('pegawai_id'));
										break;
		}
		
		if($data[0]['telaah_ttdspd']==1 || $data[0]['telaah_ttdspd']==14 ){
			$tanda_tangan = $this->m_laporan->tanda_tangan_walikota($data[0]['telaah_ttdspd']);
		} else {
			$tanda_tangan = $this->m_laporan->tanda_tangan($data[0]['telaah_ttdspd']);
		}

		$staff_sekda = false;
		$staff_sekda2 = false;
		if($this->ion_auth->user()->row()->jenis_skpd == 3) {
			$staff_sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
		}

		if($staff_sekda){
			$bendahara = $this->m_laporan->get_bendahara_setda($staff_sekda[0]['bagian_id']);
		} else if($staff_sekda2){
			$bendahara = $this->m_laporan->get_bendahara_setda($staff_sekda2[0]['bagian_id']);
		} else {
			if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']!=11){
				$bendahara= $this->m_laporan->get_bendahara(36);
			} elseif($data[0]['telaah_kategori']==11 && $data[0]['telaah_jenis_skpd']==7) {
				$bendahara = $this->m_laporan->get_bendahara($data[0]['skpd_id']);
			} else if($data[0]['telaah_kategori']==3) {
				$bendahara = $this->m_laporan->get_bendahara_dprd();
			} else {
				$bendahara = $this->m_laporan->get_bendahara($data[0]['skpd']);
			}
		}

		// Validasi: Cek apakah Bendahara Pengeluaran ada
		if(empty($bendahara) || !isset($bendahara[0]['pegawai_nama'])) {
			$this->session->set_flashdata('error', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-ban"></i> Error!</h4>Bendahara Pengeluaran belum ditentukan untuk SKPD ini. Silakan atur data Bendahara terlebih dahulu.</div>');
			redirect($_SERVER['HTTP_REFERER']);
			return;
		}
		
        $pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(20, 3.175, 25);
		
        $pdf->AddPage();
		
		QRcode::png('https://sppd.kendarikota.go.id/telaah/laporan/qr_new/sppd?telaah_id='.$this->input->get('telaah_id'),"application/test.png");
		$pdf->Image("application/test.png", 185, 5, 20, 20, "png");
		
		$pdf->SetFont('Times','',9);
        $pdf->Cell(1,20,'',0,1);
        $pdf->Cell(10,6,'PEMERINTAH KOTA KENDARI',0,0);
        $pdf->Cell(100,6,'',0,0);
        $pdf->Cell(35,6,'TAHUN ANGGARAN',0,0);
        $pdf->Cell(25,6,': '.date("Y"),0,1);
		
		$skpd_nama = strtoupper($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
		
		$pdf->SetWidths(array(110,35,25));
		$border = array('','', '');
		$align = array('','','');
		$caption = array($skpd_nama2,'KODE REKENING', ': '.$data[0]['no_rekening']);
		$pdf->Row($caption, $border, $align);
		
        $pdf->Cell(110,6,'',0,0);
        $pdf->Cell(35,6,'BKU NO.',0,0);
        $pdf->Cell(25,6,': ',0,1);
		
        $pdf->Cell(110,6,'',0,0);
        $pdf->Cell(35,6,'TANGGAL',0,0);
        $pdf->Cell(25,6,': ',0,1);
		
		$pdf->Cell(10,7,'',0,1);
		
        $pdf->SetFont('Times','BUI',16);
        $pdf->Cell(170,7,'KUITANSI',0,1,'C');
		
		$pdf->Cell(10,7,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(55,6,'SUDAH TERIMA DARI',0,0);
        $pdf->Cell(5,6,':',0,0);
		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
        $pdf->MultiCell(110,6,'Pengguna Anggaran '.$skpd_nama2,0,'J');
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(55,6,'UANG SEBESAR',0,0);
        $pdf->Cell(5,6,':',0,0);
        $pdf->MultiCell(110,6,'Rp. '.number_format($rincian[0]['jumlah'],0,",","."),0,'J');
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(55,6,'UNTUK PEMBAYARAN',0,0);
        $pdf->Cell(5,6,':',0,0);
        $pdf->MultiCell(110,6,$data[0]['telaah_perihal'],0,'J');
		
		$pdf->Cell(10,7,'',0,1);
		$pdf->SetFont('Times','B',10);
        $pdf->Cell(170,7,'TERBILANG : '.$this->terbilang($rincian[0]['jumlah']).' Rupiah',1,1,'C');
		
		$pdf->Cell(10,7,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(10,6,'',0,0);
        $pdf->Cell(100,6,'',0,0);
        $pdf->Cell(35,6,'KENDARI,',0,0);
        $pdf->Cell(25,6,'',0,1);
		
		$pdf->Cell(10,6,'',0,0);
        $pdf->Cell(115,6,'',0,0);
        $pdf->Cell(35,6,'YANG MENERIMA,',0,0);
        $pdf->Cell(25,6,'',0,1);
		
        $pdf->Cell(55,6,'SETUJU BAYAR',0,0);
        $pdf->Cell(137,6,'PADA TANGGAL',0,0);
        $pdf->Cell(60,6,'YANG MENERIMA',0,1,'C');
		
        if($data[0]['telaah_kategori']==11 && $data[0]['telaah_jenis_skpd']==7){
			$pdf->Cell(55,6,'KUASA PENGGUNA ANGGARAN',0,0);
			$pdf->Cell(3,6,'',0,0);
			$pdf->Cell(145,6,'BENDAHARA DANA KAPITASI JKN',0,0);
			$pdf->Cell(60,6,'',0,1,'');
		}else{
			$pdf->Cell(55,6,'PENGGUNA ANGGARAN',0,0);
			$pdf->Cell(3,6,'',0,0);
			$pdf->Cell(145,6,'BENDAHARA PENGELUARAN',0,0);
			$pdf->Cell(60,6,'',0,1,'');
		}
		
		$pdf->Cell(10,7,'',0,1);
		$pdf->Cell(10,7,'',0,1);
		$pdf->Cell(10,7,'',0,1);
		
		$pdf->SetFont('Times','B',9);
		$pdf->SetWidths(array(55,3,60,3,60));
		$border = array(0,0,0,0,0);
		$align = array('C','','C','','C');
		
       if($data[0]['pegawai_jabatan']==1 || $data[0]['pegawai_jabatan']==16 || $data[0]['telaah_kategori']==3 || $data[0]['pegawai_nip']==0
			|| $data[0]['pegawai_nip']==00|| $data[0]['pegawai_nip']==000 ||$data[0]['pegawai_nip']==00000000 ){
			if($data2[0]['pegawai_jabatan']==5){
				$caption = array($data2[0]['pegawai_nama'],'',
						 $bendahara[0]['pegawai_nama']."\n".'NIP. '.$bendahara[0]['pegawai_nip'],'',
						 $data[0]['pegawai_nama']);
			} else {
				$caption = array($data2[0]['pegawai_nama']."\n".'NIP. '.$data2[0]['pegawai_nip'],'',
						 $bendahara[0]['pegawai_nama']."\n".'NIP. '.$bendahara[0]['pegawai_nip'],'',
						 $data[0]['pegawai_nama']);
			}
		} else {
			$caption = array($data2[0]['pegawai_nama']."\n".'NIP. '.$data2[0]['pegawai_nip'],'',
						 $bendahara[0]['pegawai_nama']."\n".'NIP. '.$bendahara[0]['pegawai_nip'],'',
						 $data[0]['pegawai_nama']."\n".'NIP. '.$data[0]['pegawai_nip']);
		}
		$pdf->Row($caption, $border, $align);

		$pdf->Output('D','KUITANSI_PANJAR - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y", strtotime($data[0]['telaah_tanggalspd'])).'.pdf'); 
	}
	
	//KUITANSI RAMPUNG 
	public function cetak_kuitansi_rampung()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		switch($this->uri->segment(5)){
			case "pelaksana"		:  	$data = $this->m_laporan->get_rincian_pelaksana($telaah_id, $this->input->get('pegawai_id'));	
										if($data[0]['telaah_kategori']==1 && $data[0]['telaah_jenis_skpd']==7 ){
											$data2 = $this->m_laporan->get_kepala_opd(36);			
										} else if($data[0]['telaah_kategori']==1 && $data[0]['telaah_jenis_skpd']!=7 ) {
											$data2 = $this->m_laporan->get_kepala_opd($this->ion_auth->user()->row()->skpd_id);			
										} else if($data[0]['telaah_kategori']==2) {
											$data2 = $this->m_laporan->get_kepala_opd($this->ion_auth->user()->row()->skpd_id);			
										} else if($data[0]['telaah_kategori']==3){
											$data2 = $this->m_laporan->get_sekwan();
										} else if($data[0]['telaah_kategori']==4){
											$data2 = $this->m_laporan->get_sekda(3);
										} else if($data[0]['telaah_kategori']==5 && $data[0]['telaah_jenis_skpd']==4){
											$data2 = $this->m_laporan->get_camat($this->ion_auth->user()->row()->skpd_id);
										} else if($data[0]['telaah_kategori']==5 && $data[0]['telaah_jenis_skpd']==5){
											$data2 = $this->m_laporan->get_lurah($this->ion_auth->user()->row()->skpd_id);
										} else if($data[0]['telaah_kategori']==6){
											$data2 = $this->m_laporan->get_sekwan();
										} else if($data[0]['telaah_kategori']==7 && $data[0]['telaah_jenis_skpd']==4){
											$data2 = $this->m_laporan->get_camat($this->ion_auth->user()->row()->skpd_id);
										} else if($data[0]['telaah_kategori']==7 && $data[0]['telaah_jenis_skpd']==5){
											$data2 = $this->m_laporan->get_lurah($this->ion_auth->user()->row()->skpd_id);
										} else if($data[0]['telaah_kategori']==8){
											$data2 = $this->m_laporan->get_sekda(3);
										} else if($data[0]['telaah_kategori']==9){
											$data2 = $this->m_laporan->get_sekda(3);
										} else if($data[0]['telaah_kategori']==10){
											$data2 = $this->m_laporan->get_sekwan();
										} else if($data[0]['telaah_kategori']==11 && $data[0]['telaah_jenis_skpd']==7 ) {
											$data2 = $this->m_laporan->get_kepala_puskesmas($this->ion_auth->user()->row()->skpd_id);
										} 	
										$rincian = $this->m_rincian->get_rincian($telaah_id,$this->input->get('pegawai_id'));
										$pengeluaran_rill = $this->m_pengeluaran_rill->get_rincian($telaah_id,$this->input->get('pegawai_id'));
										$panjar = $this->m_kuitansi->get_kuitansi_panjar($telaah_id,$this->input->get('pegawai_id'));
										break;
			case "pengikut"			:  	$data = $this->m_laporan->get_rincian_pengikut($telaah_id, $this->input->get('pengikut_id'));	
										if($data[0]['telaah_kategori']==1 && $data[0]['telaah_jenis_skpd']==7 ){
											$data2 = $this->m_laporan->get_kepala_opd(36);			
										} else if($data[0]['telaah_kategori']==1 && $data[0]['telaah_jenis_skpd']!=7 ) {
											$data2 = $this->m_laporan->get_kepala_opd($this->ion_auth->user()->row()->skpd_id);			
										} else if($data[0]['telaah_kategori']==2) {
											$data2 = $this->m_laporan->get_kepala_opd($this->ion_auth->user()->row()->skpd_id);			
										} else if($data[0]['telaah_kategori']==3){
											$data2 = $this->m_laporan->get_sekwan();
										} else if($data[0]['telaah_kategori']==4){
											$data2 = $this->m_laporan->get_sekda(3);
										} else if($data[0]['telaah_kategori']==5 && $data[0]['telaah_jenis_skpd']==4){
											$data2 = $this->m_laporan->get_camat($this->ion_auth->user()->row()->skpd_id);
										} else if($data[0]['telaah_kategori']==5 && $data[0]['telaah_jenis_skpd']==5){
											$data2 = $this->m_laporan->get_lurah($this->ion_auth->user()->row()->skpd_id);
										} else if($data[0]['telaah_kategori']==6){
											$data2 = $this->m_laporan->get_sekwan();
										} else if($data[0]['telaah_kategori']==7 && $data[0]['telaah_jenis_skpd']==4){
											$data2 = $this->m_laporan->get_camat($this->ion_auth->user()->row()->skpd_id);
										} else if($data[0]['telaah_kategori']==7 && $data[0]['telaah_jenis_skpd']==5){
											$data2 = $this->m_laporan->get_lurah($this->ion_auth->user()->row()->skpd_id);
										} else if($data[0]['telaah_kategori']==8){
											$data2 = $this->m_laporan->get_sekda(3);
										} else if($data[0]['telaah_kategori']==9){
											$data2 = $this->m_laporan->get_sekda(3);
										} else if($data[0]['telaah_kategori']==10){
											$data2 = $this->m_laporan->get_sekwan();
										} else if($data[0]['telaah_kategori']==11 && $data[0]['telaah_jenis_skpd']==7 ) {
											$data2 = $this->m_laporan->get_kepala_puskesmas($this->ion_auth->user()->row()->skpd_id);
										} 					  
										$rincian = $this->m_rincian->get_rincian($telaah_id,$this->input->get('pengikut_id'));
										$pengeluaran_rill = $this->m_pengeluaran_rill->get_rincian($telaah_id,$this->input->get('pengikut_id'));
										$panjar = $this->m_kuitansi->get_kuitansi_panjar($telaah_id,$this->input->get('pengikut_id'));
										break;
			case "pelaksana_dprd"	: 	$data = $this->m_laporan->get_rincian_pelaksana_dprd($telaah_id, $this->input->get('pegawai_id'));
										//$data2 = $this->m_laporan->get_pimpinan_dprd();	
										$data2 = $this->m_laporan->get_sekwan();
										$rincian = $this->m_rincian->get_rincian($telaah_id,$this->input->get('pegawai_id'));
										$pengeluaran_rill = $this->m_pengeluaran_rill->get_rincian_dprd($telaah_id,$this->input->get('pegawai_id'));
										$panjar = $this->m_kuitansi->get_kuitansi_panjar($telaah_id,$this->input->get('pegawai_id'));
										break;
			case "pengikut_dprd"	: 	$data = $this->m_laporan->get_rincian_pengikut_dprd($telaah_id, $this->input->get('pengikut_id'));
										//$data2 = $this->m_laporan->get_pimpinan_dprd();		
										$data2 = $this->m_laporan->get_sekwan();
										$rincian = $this->m_rincian->get_rincian($telaah_id,$this->input->get('pengikut_id'));
										$pengeluaran_rill = $this->m_pengeluaran_rill->get_rincian_dprd($telaah_id,$this->input->get('pengikut_id'));
										$panjar = $this->m_kuitansi->get_kuitansi_panjar($telaah_id,$this->input->get('pengikut_id'));
										break;
			case "walikota"			:	$data = $this->m_laporan->get_rincian_walikota($telaah_id, $this->input->get('pegawai_id')); 
										$data2 = $this->m_laporan->get_sekda($this->ion_auth->user()->row()->skpd_id);
										$rincian = $this->m_rincian->get_rincian_walikota($telaah_id,$this->input->get('pegawai_id'));
										$pengeluaran_rill = $this->m_pengeluaran_rill->get_rincian_walikota($telaah_id,$this->input->get('pegawai_id'));
										$panjar = $this->m_kuitansi->get_kuitansi_panjar_walikota($telaah_id,$this->input->get('pegawai_id'));
										break;
		}
		
		
		if($data[0]['telaah_ttdspd']==1 || $data[0]['telaah_ttdspd']==14 ){
			$tanda_tangan = $this->m_laporan->tanda_tangan_walikota($data[0]['telaah_ttdspd']);
		} else {
			$tanda_tangan = $this->m_laporan->tanda_tangan($data[0]['telaah_ttdspd']);
		}

		$staff_sekda = false;
		$staff_sekda2 = false;
		if($this->ion_auth->user()->row()->jenis_skpd == 3) {
			$staff_sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
		}

		if($staff_sekda) {
			$bendahara = $this->m_laporan->get_bendahara_setda($staff_sekda[0]['bagian_id']);
		} else if($staff_sekda2) {
			$bendahara = $this->m_laporan->get_bendahara_setda($staff_sekda2[0]['bagian_id']);
		} else {
			if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']!=11){
				$bendahara= $this->m_laporan->get_bendahara(36);
			} else if($data[0]['telaah_kategori']==3) {
				$bendahara = $this->m_laporan->get_bendahara_dprd();
			} else {
				$bendahara = $this->m_laporan->get_bendahara($data[0]['skpd']);
			}
		}

		// Validasi: Cek apakah Bendahara Pengeluaran ada
		if(empty($bendahara) || !isset($bendahara[0]['pegawai_nama'])) {
			$this->session->set_flashdata('error', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-ban"></i> Error!</h4>Bendahara Pengeluaran belum ditentukan untuk SKPD ini. Silakan atur data Bendahara terlebih dahulu.</div>');
			redirect($_SERVER['HTTP_REFERER']);
			return;
		}
		
		
        $pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(15, 3.175, 25);
        // membuat halaman baru
        $pdf->AddPage();
		
		QRcode::png('https://sppd.kendarikota.go.id/telaah/laporan/qr_new/sppd?telaah_id='.$this->input->get('telaah_id'),"application/test.png");
		$pdf->Image("application/test.png", 185, 5, 20, 20, "png");
		
		$pdf->SetFont('Times','',9);
        $pdf->Cell(1,20,'',0,1);
        $pdf->Cell(10,6,'PEMERINTAH KOTA KENDARI',0,0);
        $pdf->Cell(100,6,'',0,0);
        $pdf->Cell(35,6,'TAHUN ANGGARAN',0,0);
        $pdf->Cell(25,6,': '.date("Y"),0,1);
		
		$skpd_nama = strtoupper($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
		
		$pdf->SetWidths(array(110,35,25));
		$border = array('','', '');
		$align = array('','','');
		$caption = array($skpd_nama2,'KODE REKENING', ': '.$data[0]['no_rekening']);
		$pdf->Row($caption, $border, $align);
		
        $pdf->Cell(110,6,'',0,0);
        $pdf->Cell(35,6,'BKU NO.',0,0);
        $pdf->Cell(25,6,': ',0,1);
		
        $pdf->Cell(110,6,'',0,0);
        $pdf->Cell(35,6,'TANGGAL',0,0);
        $pdf->Cell(25,6,': ',0,1);
		
		$pdf->Cell(10,7,'',0,1);
		
        $pdf->SetFont('Times','BUI',16);
        $pdf->Cell(175,7,'KUITANSI',0,1,'C');
		
		$pdf->Cell(10,7,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(55,6,'SUDAH TERIMA DARI',0,0);
        $pdf->Cell(5,6,':',0,0);
		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
        $pdf->MultiCell(115,6,'Pengguna Anggaran '.$skpd_nama2,0,'J');
        
		$no = 1;
		$jumlah_pengeluaran_rill = 0;
		foreach($pengeluaran_rill as $v){
			$jumlah_pengeluaran_rill = $jumlah_pengeluaran_rill + $v->tarif;
		}
		
		$jumlah = 0;
		foreach($rincian as $v){
			$jumlah = $jumlah + ($v->tarif * $v->item);
		}
		
		$total = $jumlah_pengeluaran_rill + $jumlah;
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(55,6,'UANG SEBESAR',0,0);
        $pdf->Cell(5,6,':',0,0);
        $pdf->MultiCell(115,6,'Rp. '.number_format($total,0,",","."),0,'J');
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(55,6,'UNTUK PEMBAYARAN',0,0);
        $pdf->Cell(5,6,':',0,0);
        $pdf->MultiCell(115,6,$data[0]['telaah_perihal'],0,'J');
		
		$pdf->Cell(10,7,'',0,1);
		$pdf->SetFont('Times','B',10);
        $pdf->Cell(180,7,'TERBILANG : '.$this->terbilang($total).' Rupiah',1,1,'C');
		
		$pdf->Cell(10,7,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(10,6,'',0,0);
        $pdf->Cell(110,6,'',0,0);
        $pdf->Cell(35,6,'KENDARI,',0,0);
        $pdf->Cell(25,6,'',0,1);
		
		$pdf->Cell(10,6,'',0,0);
        $pdf->Cell(121,6,'',0,0);
        $pdf->Cell(40,6,'YANG MENERIMA,',0,0,'C');
        $pdf->Cell(25,6,'',0,1);
		
        $pdf->Cell(55,6,'SETUJU BAYAR',0,0);
        $pdf->Cell(137,6,'PADA TANGGAL',0,0);
        $pdf->Cell(60,6,'',0,1,'C');
		
        if($data[0]['telaah_kategori']==11 && $data[0]['telaah_jenis_skpd']==7){
			$pdf->Cell(55,6,'KUASA PENGGUNA ANGGARAN',0,0);
			$pdf->Cell(3,6,'',0,0);
			$pdf->Cell(145,6,'BENDAHARA DANA KAPITASI JKN',0,0);
			$pdf->Cell(60,6,'',0,1,'');
		}else{
			$pdf->Cell(55,6,'PENGGUNA ANGGARAN',0,0);
			$pdf->Cell(3,6,'',0,0);
			$pdf->Cell(145,6,'BENDAHARA PENGELUARAN',0,0);
			$pdf->Cell(60,6,'',0,1,'');
		}
		
		$pdf->Cell(10,7,'',0,1);
		$pdf->Cell(10,7,'',0,1);
		$pdf->Cell(10,7,'',0,1);
		
		$pdf->SetFont('Times','B',9);
		$pdf->SetWidths(array(55,3,60,3,60));
		$border = array(0,0,0,0,0);
		$align = array('C','','C','','C');
		
       if($data[0]['pegawai_jabatan']==1 || $data[0]['pegawai_jabatan']==16 || $data[0]['telaah_kategori']==3 || $data[0]['pegawai_nip']==0
				|| $data[0]['pegawai_nip']==00|| $data[0]['pegawai_nip']==000){
				$caption = array($data2[0]['pegawai_nama']."\n".'NIP. '.$data2[0]['pegawai_nip'],'',
							 $bendahara[0]['pegawai_nama']."\n".'NIP. '.$bendahara[0]['pegawai_nip'],'',
							 $data[0]['pegawai_nama']);
			} else {
				$caption = array($data2[0]['pegawai_nama']."\n".'NIP. '.$data2[0]['pegawai_nip'],'',
							 $bendahara[0]['pegawai_nama']."\n".'NIP. '.$bendahara[0]['pegawai_nip'],'',
							 $data[0]['pegawai_nama']."\n".'NIP. '.$data[0]['pegawai_nip']);
			}
		
		$pdf->Row($caption, $border, $align);
		
		$pdf->Output('D','KUITANSI_RAMPUNG - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y", strtotime($data[0]['telaah_tanggalspd'])).'.pdf');
	}
	
	public function cetak_rbpd(){
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		switch($this->uri->segment(5)){
        	case "staff_lurah"		:
			case "camat"		:
			case "staff_setda"	:
			case "kapus"		:
			case "staff_camat"	: 
			case "esselon"		: 	
			case "sekda"		: 	
			case "sekwan"		: 	
			case "staff_dprd"	: 	
			case "kadis"		: 	if($this->uri->segment(6)==1){
										## Pelaksana
										$data = $this->m_laporan->get_pelaksana_opd($telaah_id);
										$panjar = $this->m_kuitansi->get_kuitansi_panjar($telaah_id,$this->input->get('pegawai_id'));
										$pengeluaran_rill = $this->m_pengeluaran_rill->get_rincian($telaah_id,$this->input->get('pegawai_id'));
										$rincian = $this->m_rincian->get_rincian($telaah_id,$this->input->get('pegawai_id'));
									} else if($this->uri->segment(6)==2){
										## Pengikut
										$data = $this->m_laporan->get_pengikut_opd($telaah_id, $this->input->get('pengikut_id'));
										$panjar = $this->m_kuitansi->get_kuitansi_panjar($telaah_id,$this->input->get('pengikut_id'));
										$pengeluaran_rill = $this->m_pengeluaran_rill->get_rincian($telaah_id,$this->input->get('pengikut_id'));
										$rincian = $this->m_rincian->get_rincian($telaah_id,$this->input->get('pengikut_id'));
									} 
									break;
			case "dprd"			: if($this->uri->segment(6)==1){
									$data = $this->m_laporan->get_pelaksana_dprd($telaah_id);
									$panjar = $this->m_kuitansi->get_kuitansi_panjar_dprd($telaah_id,$this->input->get('pegawai_id'));
									$pengeluaran_rill = $this->m_pengeluaran_rill->get_rincian_dprd($telaah_id,$this->input->get('pegawai_id'));
									$rincian = $this->m_rincian->get_rincian($telaah_id,$this->input->get('pegawai_id'));
								} else if($this->uri->segment(6)==2){
									$data = $this->m_laporan->get_pengikut_dprd($telaah_id, $this->input->get('pengikut_id'));	
									$panjar = $this->m_kuitansi->get_kuitansi_panjar_dprd($telaah_id,$this->input->get('pengikut_id'));
									$pengeluaran_rill = $this->m_pengeluaran_rill->get_rincian_dprd($telaah_id,$this->input->get('pengikut_id'));
									$rincian = $this->m_rincian->get_rincian($telaah_id,$this->input->get('pengikut_id'));
								} 
								break;
			case "walikota"		: if($this->uri->segment(6)==1){
									$data = $this->m_laporan->get_pelaksana_walikota($telaah_id);
									$panjar = $this->m_kuitansi->get_kuitansi_panjar($telaah_id,$this->input->get('pegawai_id'));
									$pengeluaran_rill = $this->m_pengeluaran_rill->get_rincian_walikota($telaah_id,$this->input->get('pegawai_id'));
									$rincian = $this->m_rincian->get_rincian_walikota($telaah_id,$this->input->get('pegawai_id'));
								} else if($this->uri->segment(6)==2){
									$data = $this->m_laporan->get_pengikut_opd($telaah_id, $this->input->get('pengikut_id'));	
									$panjar = $this->m_kuitansi->get_kuitansi_panjar($telaah_id,$this->input->get('pengikut_id'));
									$pengeluaran_rill = $this->m_pengeluaran_rill->get_rincian($telaah_id,$this->input->get('pengikut_id'));
									$rincian = $this->m_rincian->get_rincian($telaah_id,$this->input->get('pengikut_id'));
								} 
								break;
		}
		
		
		if($data[0]['telaah_kategori']==1 && $data[0]['telaah_jenis_skpd']==7 ){
			$tanda_tangan = $this->m_laporan->get_kepala_opd(36);			
		} else if($data[0]['telaah_kategori']==1 && $data[0]['telaah_jenis_skpd']!=7 ) {
			$tanda_tangan = $this->m_laporan->get_kepala_opd($this->ion_auth->user()->row()->skpd_id);			
		} else if($data[0]['telaah_kategori']==2) {
			$tanda_tangan = $this->m_laporan->get_kepala_opd($this->ion_auth->user()->row()->skpd_id);			
		} else if($data[0]['telaah_kategori']==3){
			$tanda_tangan = $this->m_laporan->get_sekwan();
		} else if($data[0]['telaah_kategori']==4){
			$tanda_tangan = $this->m_laporan->get_sekda(3);
		} else if($data[0]['telaah_kategori']==5 && $data[0]['telaah_jenis_skpd']==4){
			$tanda_tangan = $this->m_laporan->get_camat($this->ion_auth->user()->row()->skpd_id);
		} else if($data[0]['telaah_kategori']==5 && $data[0]['telaah_jenis_skpd']==5){
			$tanda_tangan = $this->m_laporan->get_lurah($this->ion_auth->user()->row()->skpd_id);
		} else if($data[0]['telaah_kategori']==6){
			$tanda_tangan = $this->m_laporan->get_sekwan();
		} else if($data[0]['telaah_kategori']==7 && $data[0]['telaah_jenis_skpd']==4){
			$tanda_tangan = $this->m_laporan->get_camat($this->ion_auth->user()->row()->skpd_id);
		} else if($data[0]['telaah_kategori']==7 && $data[0]['telaah_jenis_skpd']==5){
			$tanda_tangan = $this->m_laporan->get_lurah($this->ion_auth->user()->row()->skpd_id);
		} else if($data[0]['telaah_kategori']==8){
			$tanda_tangan = $this->m_laporan->get_sekda(3);
		} else if($data[0]['telaah_kategori']==9){
			$tanda_tangan = $this->m_laporan->get_sekda(3);
		} else if($data[0]['telaah_kategori']==10){
			$tanda_tangan = $this->m_laporan->get_sekwan();
		} else if($data[0]['telaah_kategori']==11 && $data[0]['telaah_jenis_skpd']==7 ) {
			$tanda_tangan = $this->m_laporan->get_kepala_puskesmas($this->ion_auth->user()->row()->skpd_id);
		} 	
		
		
		$pptk = $this->m_laporan->tanda_tangan($data[0]['telaah_ttdpptk']);

		$staff_sekda = false;
		$staff_sekda2 = false;
		if($this->ion_auth->user()->row()->jenis_skpd == 3) {
			$staff_sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
		}

		if($staff_sekda) {
			$bendahara = $this->m_laporan->get_bendahara_setda($staff_sekda[0]['bagian_id']);
		} else if($staff_sekda2) {
			$bendahara = $this->m_laporan->get_bendahara_setda($staff_sekda2[0]['bagian_id']);
		} else {
			if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']!=11){
				$bendahara= $this->m_laporan->get_bendahara(36);
			} else if($data[0]['telaah_kategori']==3) {
				$bendahara = $this->m_laporan->get_bendahara_dprd();
			} else {
				$bendahara = $this->m_laporan->get_bendahara($data[0]['skpd']);
			}
		}
			
			// $this->dd($bendahara);
			// Validasi: Cek apakah Bendahara Pengeluaran ada
			if(empty($bendahara) || !isset($bendahara[0]['pegawai_nama'])) {
				$this->session->set_flashdata('error', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-ban"></i> Error!</h4>Bendahara Pengeluaran belum ditentukan untuk SKPD ini. Silakan atur data Bendahara terlebih dahulu.</div>');
				redirect($_SERVER['HTTP_REFERER']);
				return;
			}

        $pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(5, 3.175, 25);
        $pdf->AddPage();
        
		QRcode::png('https://sppd.kendarikota.go.id/telaah/laporan/qr_new/sppd?telaah_id='.$this->input->get('telaah_id'),"application/test.png");
		$pdf->Image("application/test.png", 185, 10, 20, 20, "png");
		
        $pdf->SetFont('Times','B',12);
		$pdf->Cell(10,15,'',0,1);
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->Cell(180,6,'BIAYA PERJALANAN DINAS',0,0,'C');
        $pdf->Cell(10,6,'',0,1,'C');
		
        $pdf->SetFont('Times','',10);
		$pdf->Cell(10,2,'',0,1);
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->Cell(180,6,'An. '.$data[0]['pegawai_nama'],0,0,'C');
        $pdf->Cell(10,6,'',0,1,'C');
		
		$pdf->Cell(10,4,'',0,1);
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->Cell(50,6,'Lampiran SPPD Nomor',0,0);
        $pdf->Cell(130,6,':',0,0);
        $pdf->Cell(10,6,'',0,1,'C');
		
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->Cell(50,6,'Tanggal',0,0);
        $pdf->Cell(130,6,': '.date("d-m-Y", strtotime($data[0]['telaah_tanggalspd'])),0,0);
        $pdf->Cell(10,6,'',0,1,'C');
		
        $pdf->Cell(10,4,'',0,1,'C');
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->Cell(7,6,'NO',1,0,'C');
        $pdf->Cell(100,6,'RINCIAN BELANJA',1,0,'C');
        $pdf->Cell(30,6,'JUMLAH',1,0,'C');
        $pdf->Cell(43,6,'KETERANGAN',1,1,'C');
		
		$no = 1;
		$jumlah_pengeluaran_rill = 0;
		foreach($pengeluaran_rill as $v){
			$jumlah_pengeluaran_rill = $jumlah_pengeluaran_rill + $v->tarif;
		}
		
		$pdf->Cell(10,6,'',0,0,'C');
		$pdf->Cell(7,6,'1','LR',0,'C');
		$pdf->Cell(100,6,'Pengeluaran Rill','LR',0);
		$pdf->Cell(7,6,'Rp.','L',0);
		$pdf->Cell(23,6,number_format($jumlah_pengeluaran_rill,0,",","."),'R',0,'R');
		$pdf->Cell(43,6,'','LR',1,'C');
		
		$no = 2;
		$jumlah_biaya_perjalanan_dinas = 0;
		foreach($rincian as $v){
			$jml = $v->tarif * $v->item;
			$jumlah_biaya_perjalanan_dinas = $jumlah_biaya_perjalanan_dinas + ($v->tarif * $v->item);
			
			//1
			$pdf->SetWidths(array(10,7,100,7,23,43));
			$border = array('','L','LR','L','R','LR');
			$align = array('C', 'C', 'J', '', 'R','C');
			$caption = array('',$no++,$v->keterangan,"Rp. ", number_format($jml,0,",","."), '');
			$pdf->Row($caption, $border, $align);
			
			//2
			$pdf->SetWidths(array(10,7,100,7,23,43));
			$border = array('','L','LR','L','R','LR');
			$align = array('C', 'C', '', '', 'R','C');
			$caption = array('','',number_format($v->item,0,",",".").' x Rp. '.number_format($v->tarif,0,",","."),'','','');
			$pdf->Row($caption, $border, $align);
			
		}
		
		$total = $jumlah_pengeluaran_rill + $jumlah_biaya_perjalanan_dinas;
		
		$pdf->Cell(10,6,'',0,0,'C');
		$pdf->Cell(7,6,'','LBR',0,'C');
		$pdf->Cell(100,6,'JUMLAH',1,0,'C');
		$pdf->Cell(7,6,'RP.','LTB',0);
		$pdf->Cell(23,6,number_format($total,0,",","."),'RTB',0,'R');
		$pdf->Cell(43,6,'',1,1,'C');
        $pdf->Cell(10,3,'',0,1,'C');
		
		$pdf->Cell(10,6,'',0,0,'C');
		$pdf->Cell(7,6,'',0,0,'C');
		$pdf->Cell(100,6,'',0,0);
		$pdf->Cell(7,6,'Kendari,',0,0);
		$pdf->Cell(23,6,'',0,0,'R');
		$pdf->Cell(43,6,'',0,1,'C');
		
		$pdf->Cell(10,6,'',0,0,'C');
		$pdf->Cell(7,6,'Telah dibayarkan Sejumlah',0,0);
		$pdf->Cell(100,6,'',0,0);
		$pdf->Cell(7,6,'Telah Menerima Jumlah uang Sebesar,',0,0);
		$pdf->Cell(23,6,'',0,0,'R');
		$pdf->Cell(43,6,'',0,1,'C');
		$pdf->Cell(10,6,'',0,0,'C');
		
		 if($panjar[0]['jumlah']==0){
			$pdf->Cell(7,6,'Rp.    '.number_format($total,0,",","."),0,0);
		} else {
			$pdf->Cell(7,6,'Rp.    '.number_format($panjar[0]['jumlah'],0,",","."),0,0);
		} 
		
		$pdf->Cell(100,6,'',0,0);
		$pdf->Cell(7,6,'Rp.    '.number_format($total,0,",","."),0,0);
		$pdf->Cell(23,6,'',0,0,'R');
		$pdf->Cell(43,6,'',0,1,'C');
		$pdf->Cell(10,6,'',0,0,'C');
		$pdf->Cell(7,6,'Bendahara Pengeluaran',0,0);
		$pdf->Cell(100,6,'',0,0);
		$pdf->Cell(7,6,'Yang Menerima',0,0);
		$pdf->Cell(23,6,'',0,0,'R');
		$pdf->Cell(43,6,'',0,1,'C');
		
        $pdf->Cell(10,15,'',0,1,'C');
		
		$pdf->Cell(10,6,'',0,0,'C');
        $pdf->SetFont('Times','BU',10);
		//$pdf-> Image('./upload/tanda_tangan/'.$bendahara[0]['tanda_tangan'],20,148,40,15);
		$pdf->Cell(7,6,$bendahara[0]['pegawai_nama'],0,0);
		$pdf->Cell(100,6,'',0,0);
		$pdf->Cell(7,6,$data[0]['pegawai_nama'],0,0);
		$pdf->Cell(23,6,'',0,0,'R');
		$pdf->Cell(43,6,'',0,1,'C');
		$pdf->Cell(10,6,'',0,0,'C');
        $pdf->SetFont('Times','',10);
		$pdf->Cell(7,6,'NIP. '.$bendahara[0]['pegawai_nip'],0,0);
		$pdf->Cell(100,6,'',0,0);
		if($data[0]['pegawai_jabatan']==1 || $data[0]['pegawai_jabatan']==15 || $data[0]['pegawai_jabatan']==16 
			|| $data[0]['pegawai_nip']==0 || $data[0]['pegawai_nip']==00 || $data[0]['pegawai_nip']==000 ){
				$pdf->Cell(7,6,'',0,0);
			}
			else{
				$pdf->Cell(7,6,'NIP. '.$data[0]['pegawai_nip'],0,0);
			}
		
		$pdf->Cell(23,6,'',0,0,'R');
		$pdf->Cell(43,6,'',0,1,'C');
		
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->Cell(180,6,'__________________________________________________________________________________________________________',0,0,'C');
        $pdf->Cell(10,6,'',0,1,'C');
		
		$pdf->Cell(10,2,'',0,1);
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->Cell(180,6,'PERHITUNGAN SPPD RAMPUNG',0,0,'C');
        $pdf->Cell(10,6,'',0,1,'C');
		
		$pdf->Cell(10,4,'',0,1);
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->Cell(50,6,'Ditetapkan Sejumlah',0,0);
        $pdf->Cell(130,6,': Rp.   '.number_format($total,0,",","."),0,0);
        $pdf->Cell(10,6,'',0,1,'C');
		
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->Cell(50,6,'Yang telah dibayar semula',0,0);
		
		if($panjar[0]['jumlah']==0){
			//$pdf->Cell(130,6,': Rp.   '.number_format($total,0,",","."),0,0);
			$pdf->Cell(130,6,': Rp.   '.number_format($panjar[0]['jumlah'],0,",","."),0,0);
		} else {
			$pdf->Cell(130,6,': Rp.   '.number_format($panjar[0]['jumlah'],0,",","."),0,0);
		}
		
        $pdf->Cell(10,6,'',0,1,'C');
		
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->Cell(50,6,'Sisa Kurang/Lebih',0,0);
		
		if($panjar[0]['jumlah']==0){
			//$pdf->Cell(130,6,': Rp.   '.number_format(0,0,",","."),0,0);
			$pdf->Cell(130,6,': Rp.   '.number_format($total - $panjar[0]['jumlah'],0,",","."),0,0);
		} else {
			$pdf->Cell(130,6,': Rp.   '.number_format($total - $panjar[0]['jumlah'],0,",","."),0,0);
		}
        
        $pdf->Cell(10,6,'',0,1,'C');
		
		$pdf->Cell(10,4,'',0,1);
		
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->SetFont('Times','I',10);
		$total2 = $total - $panjar[0]['jumlah'];
		if($total2==0){
			$pdf->Cell(180,6,'',0,0,'C');
		} else {
			$pdf->Cell(180,6,'Terbilang : '.$this->terbilang($total - $panjar[0]['jumlah']).' Rupiah',0,0,'C');
		}
        $pdf->Cell(10,6,'',0,1,'C');
		
		$pdf->Cell(10,4,'',0,1);
		
        $pdf->SetFont('Times','',10);
		$pdf->Cell(10,6,'',0,0,'C');
		$pdf->Cell(7,6,'Setuju bayar:',0,0);
		$pdf->Cell(100,6,'',0,0);
		$pdf->Cell(7,6,'Mengetahui/Menyetujui',0,0);
		$pdf->Cell(7,6,'',0,0);
		$pdf->Cell(23,6,'',0,0,'R');
		$pdf->Cell(43,6,'',0,1,'C');
		
		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
		
		$pdf->Cell(10,6,'',0,0,'C');
		$pdf->Cell(7,6,$tanda_tangan[0]['pegawai_namajabatan'],0,0);
		
		$pdf->Cell(100,6,'',0,0);
		$pdf->Cell(7,6,'Pejabat Pelaksana Teknis Kegiatan',0,0);
		$pdf->Cell(7,6,'',0,0);
		$pdf->Cell(23,6,'',0,0,'R');
		$pdf->Cell(43,6,'',0,1,'C');
		
        $pdf->Cell(10,15,'',0,1,'C');
		
		$pdf->Cell(10,6,'',0,0,'C');
        $pdf->SetFont('Times','BU',10);
		
		$pdf->Cell(7,6,$tanda_tangan[0]['pegawai_nama'],0,0);
		$pdf->Cell(100,6,'',0,0);
		$pdf->Cell(7,6,$pptk[0]['pegawai_nama'],0,0);
		$pdf->Cell(7,6,'',0,0);
		$pdf->Cell(23,6,'',0,0,'R');
		$pdf->Cell(43,6,'',0,1,'C');
		$pdf->Cell(10,6,'',0,0,'C');
        $pdf->SetFont('Times','',10);
		$pdf->Cell(7,6,'NIP. '.$tanda_tangan[0]['pegawai_nip'],0,0);
		$pdf->Cell(100,6,'',0,0);
		$pdf->Cell(7,6,'NIP. '.$pptk[0]['pegawai_nip'],0,0);
		$pdf->Cell(7,6,'',0,0);
		$pdf->Cell(23,6,'',0,0,'R');
		$pdf->Cell(43,6,'',0,1,'C');
		
		
		$pdf->Output('D','RBPD - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y", strtotime($data[0]['telaah_tanggalspd'])).'.pdf');
		// $filename = 'RBPD - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y", strtotime($data[0]['telaah_tanggalspd'])).'.pdf';
		// $path = "./upload/doc_dummy/$filename";
		// $pdf->Output($path,'F');
	}
	
	// PENGELUARAN RILL
	public function laporan_pengeluaran_rill(){
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
    	
		switch($this->uri->segment(5)){
        	case "staff_lurah"	:
			case "camat"		:
			case "staff_setda"	:
			case "kapus"		:
			case "staff_camat"	: 
			case "esselon"		: 	
			case "sekda"		: 	
			case "sekwan"		: 	
			case "staff_dprd"	: 	
			case "kadis"		: 	if($this->uri->segment(6)==1){
										## Pelaksana
										$data = $this->m_laporan->get_pelaksana_opd($telaah_id);
										$pengeluaran_rill = $this->m_pengeluaran_rill->get_rincian($telaah_id,$this->input->get('pegawai_id'));
									} else if($this->uri->segment(6)==2){
										## Pengikut
										$data = $this->m_laporan->get_pengikut_opd($telaah_id, $this->input->get('pengikut_id'));
										$pengeluaran_rill = $this->m_pengeluaran_rill->get_rincian($telaah_id,$this->input->get('pengikut_id'));
									} 
									break;
        
			case "dprd"			: if($this->uri->segment(6)==1){
									$data = $this->m_laporan->get_pelaksana_dprd($telaah_id);
									$pengeluaran_rill = $this->m_pengeluaran_rill->get_rincian_dprd($telaah_id,$this->input->get('pegawai_id'));
								} else if($this->uri->segment(6)==2){
									$data = $this->m_laporan->get_pengikut_dprd($telaah_id, $this->input->get('pengikut_id'));	
									$pengeluaran_rill = $this->m_pengeluaran_rill->get_rincian_dprd($telaah_id,$this->input->get('pengikut_id'));
								} 
								break;
			case "walikota"		: if($this->uri->segment(6)==1){
									$data = $this->m_laporan->get_pelaksana_walikota($telaah_id);
									$pengeluaran_rill = $this->m_pengeluaran_rill->get_rincian_walikota($telaah_id,$this->input->get('pegawai_id'));
								} else if($this->uri->segment(6)==2){
									$data = $this->m_laporan->get_pengikut_opd($telaah_id, $this->input->get('pengikut_id'));	
									$pengeluaran_rill = $this->m_pengeluaran_rill->get_rincian($telaah_id,$this->input->get('pengikut_id'));
								} 
								break;
		}
		
		
		if($data[0]['telaah_kategori']==1 && $data[0]['telaah_jenis_skpd']==7 ){
			$tanda_tangan = $this->m_laporan->get_kepala_opd(36);			
		} else if($data[0]['telaah_kategori']==1 && $data[0]['telaah_jenis_skpd']!=7 ) {
			$tanda_tangan = $this->m_laporan->get_kepala_opd($this->ion_auth->user()->row()->skpd_id);			
		} else if($data[0]['telaah_kategori']==2) {
			$tanda_tangan = $this->m_laporan->get_kepala_opd($this->ion_auth->user()->row()->skpd_id);			
		} else if($data[0]['telaah_kategori']==3){
			$tanda_tangan = $this->m_laporan->get_sekwan();
		} else if($data[0]['telaah_kategori']==4){
			$tanda_tangan = $this->m_laporan->get_sekda(3);
		} else if($data[0]['telaah_kategori']==5 && $data[0]['telaah_jenis_skpd']==4){
			$tanda_tangan = $this->m_laporan->get_camat($this->ion_auth->user()->row()->skpd_id);
		} else if($data[0]['telaah_kategori']==5 && $data[0]['telaah_jenis_skpd']==5){
			$tanda_tangan = $this->m_laporan->get_lurah($this->ion_auth->user()->row()->skpd_id);
		} else if($data[0]['telaah_kategori']==6){
			$tanda_tangan = $this->m_laporan->get_sekwan();
		} else if($data[0]['telaah_kategori']==7 && $data[0]['telaah_jenis_skpd']==4){
			$tanda_tangan = $this->m_laporan->get_camat($this->ion_auth->user()->row()->skpd_id);
		} else if($data[0]['telaah_kategori']==7 && $data[0]['telaah_jenis_skpd']==5){
			$tanda_tangan = $this->m_laporan->get_lurah($this->ion_auth->user()->row()->skpd_id);
		} else if($data[0]['telaah_kategori']==8){
			$tanda_tangan = $this->m_laporan->get_sekda(3);
		} else if($data[0]['telaah_kategori']==9){
			$tanda_tangan = $this->m_laporan->get_sekda(3);
		} else if($data[0]['telaah_kategori']==10){
			$tanda_tangan = $this->m_laporan->get_sekda(3);
		} else if($data[0]['telaah_kategori']==11 && $data[0]['telaah_jenis_skpd']==7 ) {
			$tanda_tangan = $this->m_laporan->get_kepala_puskesmas($this->ion_auth->user()->row()->skpd_id);
		} 	
		
		
		$pptk = $this->m_laporan->tanda_tangan($data[0]['telaah_ttdpptk']);
		
        $pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(25, 3.175, 25);
        $pdf->AddPage();
        if($data[0]['jenis_skpd']==7 && $data[0]['telaah_kategori']!=11){
			$dinas_kesehatan = $this->m_laporan->get_dinas_kesehatan();
			$pdf-> Image('./upload/kop_surat/'.$dinas_kesehatan[0]['kop_surat'],20,16,165,30);
		} else {
			$pdf-> Image('./upload/kop_surat/'.$data[0]['kop_surat'],20,16,165,30);
		}
		
		QRcode::png('https://sppd.kendarikota.go.id/telaah/laporan/qr_new/sppd?telaah_id='.$this->input->get('telaah_id'),"application/test.png");
		$pdf->Image("application/test.png", 185, 10, 20, 20, "png");
		
		$pdf->Cell(10,20,'',0,1);
		$pdf->Cell(10,7,'',0,1);
		$pdf->Cell(10,25,'',0,1);
		
        $pdf->SetFont('Times','B',14);
        $pdf->Cell(160,7,'DAFTAR PENGELUARAN RILL',0,1,'C');
		
        $pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(75,6,'Yang bertanda tangan dibawah ini :',0,0);
        $pdf->Cell(80,6,'',0,1);
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Nama',0,0);
        $pdf->Cell(3,6,':',0,0);
        $pdf->Cell(137,6,$data[0]['pegawai_nama'],0,1);
		
		if($this->uri->segment(5)=='walikota' || $this->uri->segment(5)=='dprd' || $data[0]['pegawai_jabatan']==15 || $data[0]['pegawai_jabatan']==16 
			|| $data[0]['pegawai_nip']==0 || $data[0]['pegawai_nip']==00 || $data[0]['pegawai_nip']==000 ){
		} else {
			$pdf->SetFont('Times','',10);
			$pdf->Cell(20,6,'NIP',0,0);
			$pdf->Cell(3,6,':',0,0);
			$pdf->Cell(137,6,$data[0]['pegawai_nip'],0,1);			
		}
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Jabatan',0,0);
        $pdf->Cell(3,6,':',0,0);
        $pdf->Cell(137,6,$data[0]['pegawai_namajabatan'],0,1);
		
		$pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','',10);
        $pdf->MultiCell(160, 5, 'Berdasarkan Surat Perintah Perjalanan Dinas (SPPD) tanggal '.date("d-m-Y", strtotime($data[0]['telaah_tanggalspd'])).' Nomor _______________________________ dengan ini kami menyatakan dengan sesungguhnya bahwa :', 0,'J');
		
		
		$pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(7,6,'1. ',0,0);
        $pdf->MultiCell(153,6,'Biaya transport pegawai dibawah ini yang tidak dapat diperoleh bukti-bukti pengeluarannya, meliputi :',0,1);
		
        $pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','B',10);
        $pdf->Cell(7,6,'NO',1,0);
        $pdf->Cell(113,6,'URAIAN',1,0,'C');
        $pdf->Cell(40,6,'JUMLAH',1,0,'C');
        $pdf->Cell(15,6,'',0,1);
		
		$pdf->SetFont('Times','',10);
		
		$no = 1;
		$jumlah = 0;
		foreach($pengeluaran_rill as $v){
			$jumlah = $jumlah + $v->tarif;
			$pdf->SetWidths(array(7,113,10,30,15));
			$border = array(1,1,'LTB','TRB','');
			$align = array('', 'J', '', 'R', '');
			$caption = array($no++,$v->uraian,"Rp. ", number_format($v->tarif,0,",","."), '');
			$pdf->Row($caption, $border, $align);
		}
		
        $pdf->SetFont('Times','B',10);
        $pdf->Cell(7,6,'',1,0);
        $pdf->Cell(113,6,'JUMLAH',1,0,'C');
		$pdf->Cell(5,6,'Rp. ','LTB',0);
		$pdf->Cell(35,6,number_format($jumlah,0,",","."),'TRB',0,'R');
        $pdf->Cell(15,6,'',0,1);
        
		$pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(7,6,'2. ',0,0);
        $pdf->MultiCell(153,6,'Jumlah uang tersebut pada angka 1 diatas benar-benar dikeluarkan untuk pelaksanaan perjalanan dinas dimaksud dan apabila dikemudian hari terdapat kelebihan atas pembayaran, kami bersedia untuk menyetorkan kelebihan tersebut ke Kas Daerah.',0,'J');
		
		$pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','',10);
        $pdf->MultiCell(155, 5, 'Demikian pernyataan ini kami buat dengan sebenar-benarnya, untuk dipergunakan sebagaimana mestinya.', 0,1);
		
		$pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(100,6,'Kendari,',0,1,'R');
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(80,6,'Menyetujui :',0,0,'C');
        $pdf->Cell(80,6,'',0,1,'R');
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(80,6,'Pejabat Pelaksana Teknis Kegiatan,',0,0,'C');
        $pdf->Cell(80,6,'Yang Melakukan Perjalanan Dinas',0,1,'C');
		
		$pdf->Cell(10,15,'',0,1);
        $pdf->SetFont('Times','BU',10);
        $pdf->Cell(80,6,$pptk[0]['pegawai_nama'],0,0,'C');
        $pdf->Cell(80,6,$data[0]['pegawai_nama'],0,1,'C');
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(80,6,'NIP. '.$pptk[0]['pegawai_nip'],0,0,'C');
        
		if($this->uri->segment(5)=='walikota' || $this->uri->segment(5)=='dprd' || $data[0]['pegawai_jabatan']==15 || $data[0]['pegawai_jabatan']==16 
			|| $data[0]['pegawai_nip']==0 || $data[0]['pegawai_nip']==00 || $data[0]['pegawai_nip']==000 ){
			$pdf->Cell(80,6,'',0,1,'C');
		} else {
			$pdf->Cell(80,6,'NIP. '.$data[0]['pegawai_nip'],0,1,'C');
		}
		
		$pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(160,6,'Mengetahui :',0,1,'C');
		
        $pdf->SetFont('Times','',10);
		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
		
        $pdf->Cell(40,6,'',0,0,'C');
		
		$pdf->MultiCell(80,6,$tanda_tangan[0]['pegawai_namajabatan'],0,'C');
		
		$pdf->Cell(10,15,'',0,1);
        $pdf->SetFont('Times','BU',10);
        $pdf->Cell(160,6,$tanda_tangan[0]['pegawai_nama'],0,1,'C');
        $pdf->SetFont('Times','',10);
        $pdf->Cell(160,6,'NIP. '.$tanda_tangan[0]['pegawai_nip'],0,1,'C');
		
		
		$pdf->Output('D','PENGELUARAN_RILL - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y", strtotime($data[0]['telaah_tanggalspd'])).'.pdf');
	}
}