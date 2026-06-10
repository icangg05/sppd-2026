<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Laporan_walikota extends public_Controller {
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
		$this->load->model('setting/m_log');
		
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
	
	/// SPD
	function cetak_spdwalikota(){
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$data = $this->m_laporan->getWalikota($telaah_id);
		$tanggal_laporan = $this->m_laporan->tanggal_laporan($telaah_id);
		
		if($tanggal_laporan[0]['tanda_tangan_spd']==1){
			$kepala_opd = $this->m_laporan->get_walikota();
		} else if($tanggal_laporan[0]['tanda_tangan_spd']==14){
			$kepala_opd = $this->m_laporan->get_wakil_walikota();
		} else if($tanggal_laporan[0]['tanda_tangan_spd']==2){
			$kepala_opd = $this->m_laporan->get_asisten1();
		} else if($tanggal_laporan[0]['tanda_tangan_spd']==17){
			$kepala_opd = $this->m_laporan->get_asisten2();
		} else if($tanggal_laporan[0]['tanda_tangan_spd']==18){
			$kepala_opd = $this->m_laporan->get_asisten3();
		} else if($tanggal_laporan[0]['tanda_tangan_spd']==9){
			$kepala_opd = $this->m_laporan->get_sekwan();
		} else if($tanggal_laporan[0]['tanda_tangan_spd']==3){
			$kepala_opd = $this->m_laporan->get_sekdaFix();
		} else if($tanggal_laporan[0]['tanda_tangan_spd']==4){
			$kepala_opd = $this->m_laporan->get_kepala_opd($data[0]['skpd_id']);
		} else if($tanggal_laporan[0]['tanda_tangan_spd']==5){
			$kepala_opd = $this->m_laporan->get_pimpinan_dprdFix();
		} 
		
        $pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(25, 3.175, 25);
		
		$pdf->SetAutoPageBreak(false);
        // membuat halaman baru
        $pdf->AddPage();
		
		$pdf->Cell(10,12,'',0,1);
        $pdf->SetFont('Arial','B',20);
		//$pdf-> Image('./assets2/dist/img/kota_kendari.png',20,16,25,25);
		$pdf-> Image('./upload/kop_surat/'.$data[0]['kop_surat'],20,16,170,30);
		
		QRcode::png('http://sppd.kendarikota.go.id/qr/sppd?telaah_id='.$this->input->get('telaah_id'),"test.png");
		$pdf->Image("test.png", 185, 10, 20, 20, "png");
		
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
		
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Arial','',8);
        //$pdf->Cell(5,6,'1.',1,0);
		//$pdf->Cell(75,6,'Pejabat berwenang yang memberi perintah',1,0);
		
		$skpdnama = strtolower($data[0]['skpd_nama']);
		$skpdnama2 = ucwords($skpdnama);
		
		//1
		$pdf->SetWidths(array(5,75,80));
		$border = array('LT', 'LT', 'LTR');
		$align = array('','','J');
		$style = array('', '', '');
		$caption = array("1.","Pejabat berwenang yang memberi perintah","Sekretaris Daerah");
		$pdf->Row($caption, $border, $align);
		
		//2
        $pdf->Cell(5,6,'2.',1,0);
        $pdf->Cell(75,6,'Nama Pegawai yang diperintahkan',1,0);
        $pdf->Cell(80,6,$data[0]['pegawai_nama'],1,1);
		
		//3.a
        $pdf->Cell(5,6,'3.','LTR',0,'T');
        $pdf->Cell(5,6,'a.','LT',0,'T');
        $pdf->Cell(70,6,'Pangkat dan Golongan ruang gaji','TR',0);
        $pdf->Cell(80,6,$data[0]['pangkat']." - ".$data[0]['pegawai_golongan'],'LTR',1);
		
        $pdf->Cell(5,6,'','LR',0,'T');
        $pdf->Cell(5,6,'','L',0,'T');
        $pdf->Cell(70,6,'menurut PP No.30 Tahun 2015','R',0);
        $pdf->Cell(80,6,'','LR',1);
		
		//3.b
		$pdf->SetWidths(array(5,5,70,80));
		$border = array('LR','L', 'R', 'LR');
		$align = array('','','','J');
		$caption = array("","b.","Jabatan / Instansi",$data[0]['pegawai_namajabatan']);
		$pdf->Row($caption, $border, $align);
		
		//3.c
		$pdf->SetWidths(array(5,5,70,80));
		$border = array('LR','L', 'R', 'LR');
		$caption = array("","c.","Tingkat biaya perjalanan dinas","");
		$pdf->Row($caption, $border);
		
		//4
		$pdf->SetWidths(array(5,75,80));
		$border = array(1,1,1);
		$align = array('', '', 'J');
		$caption = array("4.","Maksud Perjalanan Dinas",$data[0]['telaah_perihal']);
		$pdf->Row($caption, $border, $align);
		
		//5
		$pdf->Cell(5,6,'5.',1,0);
        $pdf->Cell(75,6,'Alat angkutan yang dipergunakan',1,0);
        $pdf->Cell(80,6,$data[0]['telaah_angkutan'],1,1);
		
		//6.a
		$pdf->SetWidths(array(5,5,70,80));
		$border = array('L','L','','LR');
		$align = array('','', '', 'J');
		$caption = array("6.","a.","Tempat berangkat",$data[0]['telaah_tempatberangkat']);
		$pdf->Row($caption, $border, $align);
		
		//6.b
		$pdf->SetWidths(array(5,5,70,80));
		$border = array('L','L','','LR');
		$align = array('','', '', 'J');
		if($data[0]['telaah_domainperjalanan']== 3 || $data[0]['telaah_domainperjalanan']== 4){
			$caption = array("","b.","Tempat tujuan",$data[0]['telaah_kantortujuan']);
		} else {
			$caption = array("","b.","Tempat tujuan",$data[0]['kabupaten_kota']);
		}
		$pdf->Row($caption, $border, $align);
		
		//7
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
		
        $pdf->Cell(5,6,'','LBR',0);
        $pdf->Cell(5,6,'c.','LB',0);
        $pdf->Cell(70,6,'Tanggal harus kembali','RB',0);
        $pdf->Cell(80,6,date("d-m-Y", strtotime($data[0]['telaah_tanggalkembali'])),'LBR',1);
		
		//8
		$pdf->Cell(5,6,'8.',1,0);
        $pdf->Cell(75,6,'Pengikut',1,0);
        $pdf->Cell(80,6,'Keterangan',1,1);
		
		$pengikut = $this->m_laporan->get_pengikut($telaah_id);
			$jml_pengikut = count($pengikut);
			if(!isset($pengikut[0]) || $pengikut[0] == ""){
			} else {
				for($i=0;$i<$jml_pengikut;$i++){
					$pdf->Cell(5,6,'','LR',0,'T');
					$pdf->Cell(5,6,($i+1).'.','L',0,'T');
					$pdf->Cell(70,6,$pengikut[$i]['pegawai_nama'],'R',0);
					$pdf->Cell(80,6,'','LR',1);
				}
				
			}
			
		//9
		$pdf->Cell(5,6,'9.',1,0);
        $pdf->Cell(75,6,'Pembebanan Anggaran',1,0);
        $pdf->Cell(80,6,'',1,1);
		
		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
		
		
		//9.a
		$pdf->SetWidths(array(5,5,70,80));
		$border = array('L','L','','LR');
		$align = array('','', '', 'J');
		$caption = array("","a.","Instansi",$skpd_nama2);
		$pdf->Row($caption, $border, $align);
		
		//9.b
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
		
		
		//10
		$pdf->Cell(5,6,'10.',1,0);
        $pdf->Cell(75,6,'keterangan lain-lain',1,0);
        $pdf->Cell(80,6,'',1,1);
		
		
		// Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
		
        $pdf->Cell(80,5,'',0,0);
        $pdf->Cell(35,5,'Dikeluarkan di',0,0);
        $pdf->Cell(45,5,': Kendari',0,1);
		
        $pdf->Cell(80,5,'',0,0);
        $pdf->Cell(35,5,'Tanggal',0,0);
        $pdf->Cell(45,5,': '.$this->tgl_indo($tanggal_laporan[0]['tanggal_spd']),0,1);
		
        
		
		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
		
		/*if($this->ion_auth->user()->row()->jenis_skpd == 2){
			$pdf->Cell(80,5,'Ketua DPRD',0,1);
		} else if($this->ion_auth->user()->row()->jenis_skpd == 3){
			$pdf->Cell(80,5,'Sekretaris Daerah',0,1);
		} else {
			$pdf->Cell(80,5,'Kepala '.$skpd_nama2.'',0,1);
		}*/
		
		if($tanggal_laporan[0]['tanda_tangan_spd']==2){
			$pdf->Cell(80,5,'',0,0);
			$pdf->MultiCell(80,5,'Plh. Sekretaris Daerah Kota Kendari',0,1);
			$pdf->Cell(80,5,'',0,0);
			$pdf->MultiCell(80,5,'ASISTEN I',0,1);
		} else if($tanggal_laporan[0]['tanda_tangan_spd']==17){
			$pdf->Cell(80,5,'',0,0);
			$pdf->MultiCell(80,5,'Plh. Sekretaris Daerah Kota Kendari',0,1);
			$pdf->Cell(80,5,'',0,0);
			$pdf->MultiCell(80,5,'ASISTEN II',0,1);
		} else if($tanggal_laporan[0]['tanda_tangan_spd']==18){
			$pdf->Cell(80,5,'',0,0);
			$pdf->MultiCell(80,5,'Plh. Sekretaris Daerah Kota Kendari',0,1);
			$pdf->Cell(80,5,'',0,0);
			$pdf->MultiCell(80,5,'ASISTEN III',0,1);
		} else {
			$pdf->Cell(80,5,'',0,0);
			$pdf->MultiCell(80,5,$kepala_opd[0]['pegawai_namajabatan'],0,1);
		} 
		
		if(count($kepala_opd[0]['tanda_tangan'])!=0){
			if($kepala_opd[0]['status']==1){
					$x=$pdf->GetX();
					$y=$pdf->GetY();
					$pdf->MultiCell(10,0,$pdf-> Image('./upload/tanda_tangan/'.$kepala_opd[0]['tanda_tangan'],105,$y,40,15),0,0);
			}
		}
		
		$pdf->Cell(10,20,'',0,1);
        $pdf->Cell(80,5,'',0,0);
        $pdf->Cell(80,5,$kepala_opd[0]['pegawai_nama'],0,1);
		
        
		//if($jml_pengikut==0){
		//	$pdf-> Image('./upload/tanda_tangan/'.$kepala_opd[0]['tanda_tangan'],110,238,40,15);
		//} else if ($jml_pengikut==1){
		//	$pdf-> Image('./upload/tanda_tangan/'.$kepala_opd[0]['tanda_tangan'],110,245,40,15);
		//} else if ($jml_pengikut==2){
		//	$pdf-> Image('./upload/tanda_tangan/'.$kepala_opd[0]['tanda_tangan'],110,260,40,15);
		//}
		
		
		if($tanggal_laporan[0]['tanda_tangan_spd']==1 || $tanggal_laporan[0]['tanda_tangan_spd']==14){
			$pdf->Cell(80,5,'',0,0);
			$pdf->Cell(80,5,'',0,1);
		} else {
			$pdf->Cell(80,5,'',0,0);
			$pdf->Cell(80,5,$kepala_opd[0]['pangkat'].", Gol. ".$kepala_opd[0]['pegawai_golongan'],0,1);
			$pdf->Cell(80,5,'',0,0);
			$pdf->Cell(80,5,'NIP.'. $kepala_opd[0]['pegawai_nip'],0,1);
		} 
        
		
		
		
		
		
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
		$pdf->Cell(45,4,':','R',1);
		
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		if($tanggal_laporan[0]['tanda_tangan_spd']==2){
			$pdf->Cell(30,4,'ASISTEN I',0,0);
		} else if($tanggal_laporan[0]['tanda_tangan_spd']==17){
			$pdf->Cell(30,4,'ASISTEN II',0,0);
		} else if($tanggal_laporan[0]['tanda_tangan_spd']==18){
			$pdf->Cell(30,4,'ASISTEN III',0,0);
		} else {
			$pdf->Cell(30,4,$kepala_opd[0]['pegawai_namajabatan'],0,0);
		} 
		
		
		/*if($this->ion_auth->user()->row()->jenis_skpd == 2){
			$pdf->Cell(30,4,'Ketua DPRD',0,0);
		} else if($this->ion_auth->user()->row()->jenis_skpd == 3){
			$pdf->Cell(30,4,'Sekretaris Daerah',0,0);
		} else {
			$pdf->Cell(30,4,'Kepala OPD',0,0);
		}*/
		
		$pdf->Cell(45,4,'','R',1);
		
		if(count($kepala_opd[0]['tanda_tangan'])!=0){
			if($kepala_opd[0]['status']==1){
				$x=$pdf->GetX();
				$y=$pdf->GetY();
				$pdf->MultiCell(10,0,$pdf-> Image('./upload/tanda_tangan/'.$kepala_opd[0]['tanda_tangan'],125,$y,40,15),0,0);
			}
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
		$pdf->Cell(75,4,$kepala_opd[0]['pegawai_nama'],'R',1,'C');
		
		$pdf->Cell(80,4,'','LR',0);
		$pdf->Cell(5,4,'','L',0);
		//$pdf-> Image('./upload/tanda_tangan/'.$kepala_opd[0]['tanda_tangan'],130,41,40,15);
		if($tanggal_laporan[0]['tanda_tangan_spd']==1 || $tanggal_laporan[0]['tanda_tangan_spd']==14){
			$pdf->Cell(75,4,'','BR',1,'C');
		} else {
			$pdf->Cell(75,4,'NIP .'.$kepala_opd[0]['pegawai_nip'],'BR',1,'C');
		} 
		
		//baris 2
		$pdf->Cell(5,4,'I.','LT',0);
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
		
		//baris 3
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
		
		//baris 4
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
		
		
		//baris 5
		$pdf->Cell(5,4,'IV.','LT',0);
		$pdf->Cell(30,4,'Tiba Di','T',0);
		$pdf->Cell(45,4,':','RT',0);
		$pdf->Cell(80,4,'Telah diperiksa dengan keterangan bahwa','lRT',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'(Tempat Kedudukan)',0,0);
		$pdf->Cell(45,4,':','R',0);
		$pdf->Cell(80,4,'perjalanan tersebut diatas telah benar dilakukan','LR',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(30,4,'Pada Tanggal',0,0);
		$pdf->Cell(45,4,':','R',0);
		$pdf->Cell(80,4,'atas perintahnya semata-mata untuk kepentingan','LR',1);
		
		$pdf->Cell(80,4,'','L',0);
		$pdf->Cell(80,4,'jabatan dalam waktu yang sesingkat-singkatnya.','LR',1);
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'Pejabat yang memberi perintah','R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'Pejabat yang memberi perintah','R',1);
		
		if($tanggal_laporan[0]['tanda_tangan_spd']==2){
			$pdf->Cell(5,4,'','L',0);
			$pdf->Cell(75,4,'ASISTEN I','R',0);
			$pdf->Cell(5,4,'','L',0);
			$pdf->Cell(75,4,'ASISTEN I','R',1);
		} else if($tanggal_laporan[0]['tanda_tangan_spd']==17){
			$pdf->Cell(5,4,'','L',0);
			$pdf->Cell(75,4,'ASISTEN II','R',0);
			$pdf->Cell(5,4,'','L',0);
			$pdf->Cell(75,4,'ASISTEN II','R',1);
		} else if($tanggal_laporan[0]['tanda_tangan_spd']==18){
			$pdf->Cell(5,4,'','L',0);
			$pdf->Cell(75,4,'ASISTEN III','R',0);
			$pdf->Cell(5,4,'','L',0);
			$pdf->Cell(75,4,'ASISTEN III','R',1);
		} else {
			$pdf->SetWidths(array(5,75,5,75));
			$border = array('L','R', 'L', 'R');
			$align = array('','J','','J');
			$caption = array("",$kepala_opd[0]['pegawai_namajabatan'],"",$kepala_opd[0]['pegawai_namajabatan']);
			$pdf->Row($caption, $border, $align);
		
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
		
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,$kepala_opd[0]['pegawai_nama'],'R',0,'C');
		//$pdf->Cell(75,4,'_______________________________','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,$kepala_opd[0]['pegawai_nama'],'R',1,'C');
		//$pdf->Cell(75,4,'_______________________________','R',1,'C');
		
		
		//$pdf-> Image('./upload/tanda_tangan/'.$kepala_opd[0]['tanda_tangan'],50,193,40,15);
		//$pdf-> Image('./upload/tanda_tangan/'.$kepala_opd[0]['tanda_tangan'],130,193,40,15);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'NIP '.$kepala_opd[0]['pegawai_nip'],'R',0,'C');
		//$pdf->Cell(75,4,'','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'NIP '.$kepala_opd[0]['pegawai_nip'],'R',1,'C');
		//$pdf->Cell(75,4,'','R',1,'C');
		
		$pdf->Cell(5,4,'V.','LTB',0);
		$pdf->Cell(155,4,'Keterangan Lain-lain','RTB',1);
		
		$pdf->Cell(5,4,'VI.','LT',0);
		$pdf->Cell(155,4,'PERHATIAN','RT',1);
		
		$pdf->MultiCell(160,4,'Pejabat yang berwenang memberi SPPD pegawai yang melakukan Perjalanan Dinas, para pejabat yang mengesahkan tanggal berangkat/tiba, serta bendaharawan bertanggung jawab berdasarkan peraturan - peraturan Keuangan Negara, apabila Negara menderita rugi akibat kesalahan, kelalaian dan kealpaan (Lampiran SK. Menteri Keuangan tanggal 30-4-1974 Nomor B-296/MK/I/1974).',1,'J');
		
		$pdf->Output('D','SPPD - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y", strtotime($tanggal_laporan[0]['tanggal_spd'])).'.pdf'); 
    }
	
	
	
	
	// SPT WALIKOTA untuk pengikut walikota
	function cetak_spt_walikota(){
		
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$data = $this->m_laporan->get($telaah_id);
		$data2 = $this->m_laporan->get_rincian($telaah_id, $this->input->get('pegawai_id'));
		//$data = $this->m_laporan->get_rincian_pengikut($telaah_id, $this->input->get('pengikut_id'));
		//$walikota = $this->m_laporan->get_walikota();
		$tanggal_laporan = $this->m_laporan->tanggal_laporan($telaah_id);
		
		if($tanggal_laporan[0]['tanda_tangan_spt']==1){
			$kepala_opd = $this->m_laporan->get_walikota();
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==14){
			$kepala_opd = $this->m_laporan->get_wakil_walikota();
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==9){
			$kepala_opd = $this->m_laporan->get_sekwan();
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==3){
			$kepala_opd = $this->m_laporan->get_sekdaFix();
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==4){
			$kepala_opd = $this->m_laporan->get_kepala_opd($data[0]['skpd_id']);
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==5){
			$kepala_opd = $this->m_laporan->get_pimpinan_dprdFix();
		} 
		
        $pdf = new FPDF('P','mm','A4');
		$pdf->SetMargins(25, 3.175, 25);
        // membuat halaman baru
        $pdf->AddPage();
		//$pdf->Image('./assets2/dist/img/kota_kendari.png',20,10,-1700);
		$pdf-> Image('./assets2/dist/img/garuda.png',90,10,30,30);
		//$pdf->Image(base_url()."admin/qr_generator?code=sppdkotakendari", 10, 265, 20, 20, "png");
		
		//$pdf-> Image('./upload/tanda_tangan/'.$kepala_opd[0]['tanda_tangan'],130,190,40,15);
		
		QRcode::png('http://sppd.kendarikota.go.id/qr/sppd?telaah_id='.$this->input->get('telaah_id'),"test.png");
		$pdf->Image("test.png", 180, 10, 20, 20, "png");
		
		$pdf->SetFont('Times','B',26);
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,40,'',0,1);
		
		$pdf->Cell(160,7,'WALIKOTA KENDARI',0,1,'C');
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
		
        // setting jenis font yang akan digunakan
        $pdf->SetFont('Times','BU',16);
        // Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,4,'',0,1);
		
        $pdf->Cell(160,7,'SURAT PERINTAH TUGAS',0,1,'C');
        // Memberikan space kebawah agar tidak terlalu rapat
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(45,6,'',0,0);
        $pdf->Cell(115,6,'No.',0,1);
		
        $pdf->Cell(10,4,'',0,1);
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Dari',0,0);
		$telaah_kepada = strtolower($data[0]['telaah_kepada']);
		$telaah_kepada2 = ucwords($telaah_kepada);
        $pdf->Cell(140,6,': '.$telaah_kepada2,0,1);
		
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
		
        $pdf->SetFont('Times','B',14);
        $pdf->Cell(160,7,'MEMERINTAHKAN',0,1,'C');
		
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Kepada',0,0);
        $pdf->Cell(3,6,':',0,0);
        $pdf->Cell(137,6,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'',0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(5,6,'1.',0,0);
        $pdf->Cell(35,6,'Nama',0,0);
        $pdf->Cell(2,6,':',0,0);
        $pdf->Cell(95,6,$data[0]['pegawai_nama'],0,1);
		
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
		
        $pdf->Cell(20,6,'',0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(5,6,'',0,0);
        $pdf->Cell(35,6,'Jabatan',0,0);
        $pdf->Cell(2,6,':',0,0);
		//$pegawai_namajabatan = ucwords($data[0]['pegawai_namajabatan']);
        $pdf->MultiCell(95,6,$data[0]['pegawai_namajabatan'],0,1);
		
		// Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Untuk',0,0);
        $pdf->Cell(3,6,':',0,0);
		//$hasil1=strlen($data[0]['telaah_perihal']);
		//$hasil2 = 500 - $hasil1;
		//$text=str_repeat(' ',$hasil2);
        //$pdf->MultiCell(137,6,$data[0]['telaah_perihal'].' '.$text,0,'J');
		
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
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(110,6,'',0,0);
        $pdf->Cell(50,6,'Ditetapkan Di Kendari',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(110,6,'',0,0);
        $pdf->Cell(50,6,'Pada Tanggal : '.$this->tgl_indo($tanggal_laporan[0]['tanggal_spt']),0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(110,6,'',0,0);
        $pdf->MultiCell(50,6,$kepala_opd[0]['pegawai_namajabatan'],0,1);
		
        // Memberikan space kebawah agar tidak terlalu rapat
		//$pdf->Cell(10,20,'',0,1);
		$x=$pdf->GetX();
        $y=$pdf->GetY()+3;
		
		$pdf->MultiCell(10,20,$pdf-> Image('./upload/tanda_tangan/'.$kepala_opd[0]['tanda_tangan'],135,$y,40,15),0,0);
		
		$pdf->Cell(110,6,'',0,0);
		$pdf->Cell(50,6,$kepala_opd[0]['pegawai_nama'],0,1);
		
		if($tanggal_laporan[0]['tanda_tangan_spt']==1 || $tanggal_laporan[0]['tanda_tangan_spt']==14){
			
		} else {
			$pdf->Cell(110,6,'',0,0);
			$pdf->Cell(50,6,$kepala_opd[0]['pangkat'].", Gol. ".$kepala_opd[0]['pegawai_golongan'],0,1);
			$pdf->Cell(110,6,'',0,0);
			$pdf->Cell(50,6,'NIP.'. $kepala_opd[0]['pegawai_nip'],0,1);
		} 
        // Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,7,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Tembusan Yth:',0,0);
        $pdf->Cell(3,6,':',0,0);
        $pdf->Cell(137,6,'',0,1);
		
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
		
        // Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,10,'',0,1);
		
		//$pdf->SetFont('Times','BI',10);
		//$pdf->MultiCell(160, 5, 'Catatan : Jika Walikota berhalangan atau berada diluar Daerah maka penandatanganan SPT dapat dilakukan oleh Wakil Walikota atau Sekretaris Daerah jika Wakil Walikota juga berhalangan atau berada diluar daerah.', 0,'J');
       
		$pdf->Output('D','SPT - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y", strtotime($tanggal_laporan[0]['tanggal_spt'])).'.pdf'); 
    }
	
	
	// SPT WALIKOTA untuk pengikut
	function cetak_spt_walikota2(){
		
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$data = $this->m_laporan->get($telaah_id);
		$data2 = $this->m_laporan->get_rincian($telaah_id, $this->input->get('pegawai_id'));
		$data3 = $this->m_laporan->get_pengikut2($telaah_id);
		//$data = $this->m_laporan->get_rincian_pengikut($telaah_id, $this->input->get('pengikut_id'));
		//$walikota = $this->m_laporan->get_walikota();
		$tanggal_laporan = $this->m_laporan->tanggal_laporan($telaah_id);
		
		if($tanggal_laporan[0]['tanda_tangan_spt']==1){
			$kepala_opd = $this->m_laporan->get_walikota();
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==14){
			$kepala_opd = $this->m_laporan->get_wakil_walikota();
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==2){
			$kepala_opd = $this->m_laporan->get_asisten1();
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==17){
			$kepala_opd = $this->m_laporan->get_asisten2();
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==18){
			$kepala_opd = $this->m_laporan->get_asisten3();
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==9){
			$kepala_opd = $this->m_laporan->get_sekwan();
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==3){
			$kepala_opd = $this->m_laporan->get_sekdaFix();
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==4){
			$kepala_opd = $this->m_laporan->get_kepala_opd($data[0]['skpd_id']);
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==5){
			$kepala_opd = $this->m_laporan->get_pimpinan_dprdFix();
		} 
		
        $pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(25, 3.175, 25);
        // membuat halaman baru
        $pdf->AddPage();
		$pdf->SetTopMargin(25);
		//$pdf->Image('./assets2/dist/img/kota_kendari.png',20,10,-1700);
		$pdf-> Image('./assets2/dist/img/garuda.png',90,10,30,30);
		//$pdf->Image(base_url()."admin/qr_generator?code=sppdkotakendari", 10, 265, 20, 20, "png");
		
		//$pdf-> Image('./upload/tanda_tangan/'.$kepala_opd[0]['tanda_tangan'],130,190,40,15);
		
		QRcode::png('http://sppd.kendarikota.go.id/qr/sppd?telaah_id='.$this->input->get('telaah_id'),"test.png");
		$pdf->Image("test.png", 180, 10, 20, 20, "png");
		
		$pdf->SetFont('Times','B',26);
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,40,'',0,1);
		
		$pdf->Cell(160,7,'WALIKOTA KENDARI',0,1,'C');
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
		
        // setting jenis font yang akan digunakan
        $pdf->SetFont('Times','BU',16);
        // Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,4,'',0,1);
		
        $pdf->Cell(160,7,'SURAT PERINTAH TUGAS',0,1,'C');
        // Memberikan space kebawah agar tidak terlalu rapat
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(45,6,'',0,0);
        $pdf->Cell(115,6,'No.',0,1);
		
        $pdf->Cell(10,4,'',0,1);
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Dari',0,0);
		$telaah_kepada = strtolower($data[0]['telaah_kepada']);
		$telaah_kepada2 = ucwords($telaah_kepada);
        $pdf->Cell(140,6,': '.$telaah_kepada2,0,1);
		
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
		
        $pdf->SetFont('Times','B',14);
        $pdf->Cell(160,7,'MEMERINTAHKAN',0,1,'C');
		
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Kepada',0,0);
        $pdf->Cell(3,6,':',0,0);
        $pdf->Cell(137,6,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'',0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(5,6,'1.',0,0);
        $pdf->Cell(35,6,'Nama',0,0);
        $pdf->Cell(2,6,':',0,0);
        $pdf->Cell(95,6,$data[0]['pegawai_nama'],0,1);
		
		if($data[0]['pegawai_jabatan']==16){
				
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
		//$pegawai_namajabatan = ucwords($data[0]['pegawai_namajabatan']);
        $pdf->MultiCell(95,6,$data[0]['pegawai_namajabatan'],0,1);
		
		// Pengikut
		$no = 2;
		foreach($data3 as $v) {
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
			//$pegawai_namajabatan = strtolower($v->pegawai_namajabatan);
			//$pegawai_namajabatan2 = ucwords($pegawai_namajabatan);
			$pdf->Cell(2,6,':',0,0);
			$pdf->MultiCell(95,6,$v->pegawai_namajabatan,0,1);
		   // $pdf->Cell(97,6,': '.$data[0]['pegawai_namajabatan'],0,1);
			//$pdf->Cell(97,6,': '.$v->pegawai_namajabatan,0,1);
		
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
		
		//$hasil1=strlen($data[0]['telaah_perihal']);
		//$hasil2 = 500 - $hasil1;
		//$text=str_repeat(' ',$hasil2);
        //$pdf->MultiCell(137,6,$data[0]['telaah_perihal'].' '.$text,0,'J');
		
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
        $pdf->Cell(110,6,'',0,0);
        $pdf->Cell(50,6,'Ditetapkan Di Kendari',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(110,6,'',0,0);
        $pdf->Cell(50,6,'Pada Tanggal : '.$this->tgl_indo($tanggal_laporan[0]['tanggal_spt']),0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(110,6,'',0,0);
        $pdf->MultiCell(50,6,$kepala_opd[0]['pegawai_namajabatan'],0,1);
		
        // Memberikan space kebawah agar tidak terlalu rapat
		//$pdf->Cell(10,20,'',0,1);
		
		if($kepala_opd[0]['tanda_tangan']){
			$x=$pdf->GetX();
			$y=$pdf->GetY()+3;
			
			$pdf->MultiCell(10,20,$pdf-> Image('./upload/tanda_tangan/'.$kepala_opd[0]['tanda_tangan'],135,$y,40,15),0,0);
		}
		
		$pdf->Cell(110,6,'',0,0);
		$pdf->Cell(50,6,$kepala_opd[0]['pegawai_nama'],0,1);
		
		if($tanggal_laporan[0]['tanda_tangan_spt']==1 || $tanggal_laporan[0]['tanda_tangan_spt']==14){
			
		} else {
			$pdf->Cell(110,6,'',0,0);
			$pdf->Cell(50,6,$kepala_opd[0]['pangkat'].", Gol. ".$kepala_opd[0]['pegawai_golongan'],0,1);
			$pdf->Cell(110,6,'',0,0);
			$pdf->Cell(50,6,'NIP.'. $kepala_opd[0]['pegawai_nip'],0,1);
		} 
        // Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,7,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Tembusan Yth:',0,0);
        $pdf->Cell(3,6,':',0,0);
        $pdf->Cell(137,6,'',0,1);
		
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
		
        // Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,10,'',0,1);
		
		//$pdf->SetFont('Times','BI',10);
		//$pdf->MultiCell(160, 5, 'Catatan : Jika Walikota berhalangan atau berada diluar Daerah maka penandatanganan SPT dapat dilakukan oleh Wakil Walikota atau Sekretaris Daerah jika Wakil Walikota juga berhalangan atau berada diluar daerah.', 0,'J');
       
		$pdf->Output('D','SPT - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y", strtotime($tanggal_laporan[0]['tanggal_spt'])).'.pdf'); 
    }
	
	
	
	// SPT WALIKOTA
	function cetak_spt_walikotasendiri(){
		
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$data = $this->m_laporan->getWalikota($telaah_id);
		//$kepala_opd = $this->m_laporan->get_kepala_opd($data[0]['skpd_id']);
		//$tanggal_laporan = $this->m_laporan->tanggal_laporan($telaah_id);
		$data2 = $this->m_laporan->get_pengikut2($telaah_id);
		$tanggal_laporan = $this->m_laporan->tanggal_laporan($telaah_id);
		
		if($tanggal_laporan[0]['tanda_tangan_spt']==1){
			$kepala_opd = $this->m_laporan->get_walikota();
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==14){
			$kepala_opd = $this->m_laporan->get_wakil_walikota();
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==9){
			$kepala_opd = $this->m_laporan->get_sekwan();
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==3){
			$kepala_opd = $this->m_laporan->get_sekdaFix();
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==4){
			$kepala_opd = $this->m_laporan->get_kepala_opd($data[0]['skpd_id']);
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==5){
			$kepala_opd = $this->m_laporan->get_pimpinan_dprdFix();
		} 
		
        $pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(25, 3.175, 25);
        // membuat halaman baru
        $pdf->AddPage();
		$pdf->SetTopMargin(25);
		//$pdf->Image('./assets2/dist/img/kota_kendari.png',20,10,-1700);
		$pdf-> Image('./assets2/dist/img/garuda.png',90,10,30,30);
		//$pdf->Image(base_url()."admin/qr_generator?code=sppdkotakendari", 10, 265, 20, 20, "png");
		
		QRcode::png('http://sppd.kendarikota.go.id/qr/sppd?telaah_id='.$this->input->get('telaah_id'),"test.png");
		$pdf->Image("test.png", 185, 10, 20, 20, "png");
		
		$pdf->SetFont('Times','B',26);
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,40,'',0,1);
		
		$pdf->Cell(160,7,'WALIKOTA KENDARI',0,1,'C');
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
		
        // setting jenis font yang akan digunakan
        $pdf->SetFont('Times','BU',16);
        // Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,4,'',0,1);
		
        $pdf->Cell(160,7,'SURAT PERINTAH TUGAS',0,1,'C');
        // Memberikan space kebawah agar tidak terlalu rapat
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(45,6,'',0,0);
        $pdf->Cell(115,6,'No.',0,1);
		
        $pdf->Cell(10,4,'',0,1);
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Dari',0,0);
		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
        if($this->ion_auth->user()->row()->jenis_skpd == 2){
			$pdf->Cell(140,6,': SEKRETARIS DPRD KOTA KENDARI',0,1);
		} else {
			if($tanggal_laporan[0]['tanda_tangan_spt']==1){
				$pdf->Cell(140,6,$kepala_opd[0]['pegawai_namajabatan'],0,1);
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
		
        $pdf->Cell(20,6,'',0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(5,6,'',0,0);
        $pdf->Cell(35,6,'Jabatan',0,0);
		//$pegawai_namajabatan = strtolower($data[0]['pegawai_namajabatan']);
		//$pegawai_namajabatan2 = ucwords($pegawai_namajabatan);
        $pdf->Cell(2,6,':',0,0);
        $pdf->MultiCell(95,6,$data[0]['pegawai_namajabatan'],0,1);
		
		// Pengikut
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
			//$pegawai_namajabatan = strtolower($v->pegawai_namajabatan);
			//$pegawai_namajabatan2 = ucwords($pegawai_namajabatan);
			$pdf->Cell(2,6,':',0,0);
			$pdf->MultiCell(95,6,$v->pegawai_namajabatan,0,1);
		   // $pdf->Cell(97,6,': '.$data[0]['pegawai_namajabatan'],0,1);
			//$pdf->Cell(97,6,': '.$v->pegawai_namajabatan,0,1);
		
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
		
		//$hasil1=strlen($telaah_perihal);
		//$hasil2 = 500 - $hasil1;
		//$text=str_repeat(' ',$hasil2);
        //$pdf->MultiCell(137,6,$telaah_perihal.' '.$text,0,'J');
		
		$pdf->SetFont('Times','B',10);
        $pdf->MultiCell(137,6,$telaah_perihal,0,'J');
		
		// Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
		
		$y=$pdf->GetY();
		if($y>329){
			$pdf->AddPage();
		}
		
		$pdf->SetFont('Times','',10);
		if($this->ion_auth->user()->row()->jenis_skpd == 2){
			$pdf->MultiCell(160,6,'Demikian Surat Perintah Tugas ini diberikan untuk dilaksanakan dengan penuh rasa tanggung jawab dan apabila Surat Perintah Tugas Ini tidak dijalankan sesuai aturan Perundang-Undangan yang berlaku, maka yang bersangkutan dan/atau penerima Surat Perintah Tugas ini yang akan bertanggung jawab.',0,'J');
		} else {
			$pdf->MultiCell(160,6,'Demikian Surat Tugas ini diberikan kepada yang bersangkutan untuk dilaksanakan dengan penuh rasa tanggung jawab.',0,'J');
		} 
        
		$y=$pdf->GetY();
		if($y>270){
			$pdf->AddPage();
		}
		
        $pdf->Cell(100,3,'',0,1);
		$pdf->Cell(100,6,'',0,0);
        $pdf->Cell(50,6,'Ditetapkan Di Kendari',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(100,6,'',0,0);
        $pdf->Cell(50,6,'Pada Tanggal : '.$this->tgl_indo($tanggal_laporan[0]['tanggal_spt']),0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(100,6,'',0,0);
        $pdf->MultiCell(60,6,$kepala_opd[0]['pegawai_namajabatan'],0,'J');
		
        // Memberikan space kebawah agar tidak terlalu rapat
		//$pdf->Cell(10,20,'',0,1);
		$x=$pdf->GetX();
        $y=$pdf->GetY()+3;
		
		$pdf->MultiCell(10,20,$pdf-> Image('./upload/tanda_tangan/'.$kepala_opd[0]['tanda_tangan'],125,$y,40,15),0,0);
		
		//$pdf->Cell(10,20,'',0,1);
		
		$pdf->Cell(100,6,'',0,0);
		$pdf->Cell(60,6,$kepala_opd[0]['pegawai_nama'],0,1);
		
		if($tanggal_laporan[0]['tanda_tangan_spt']==1 || $tanggal_laporan[0]['tanda_tangan_spt']==14){
			
		} else {
			$pdf->Cell(100,6,'',0,0);
			$pdf->Cell(60,6,$kepala_opd[0]['pangkat'].", Gol. ".$kepala_opd[0]['pegawai_golongan'],0,1);
			$pdf->Cell(100,6,'',0,0);
			$pdf->Cell(60,6,'NIP.'. $kepala_opd[0]['pegawai_nip'],0,1);
		} 
        // Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,7,'',0,1);
		if($this->ion_auth->user()->row()->jenis_skpd == 2){
			
		} else {
			$pdf->SetFont('Times','',10);
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
		
		
        // Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,10,'',0,1);
		
		$pdf->SetFont('Times','BI',10);
		//$pdf->MultiCell(160, 5, 'Catatan : Jika Walikota berhalangan atau berada diluar Daerah maka penandatanganan SPT dapat dilakukan oleh Wakil Walikota atau Sekretaris Daerah jika Wakil Walikota juga berhalangan atau berada diluar daerah.', 0,'J');
        
		$pdf->Output('D','SPT - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y", strtotime($tanggal_laporan[0]['tanggal_spt'])).'.pdf'); 
    }
	
	// SPT WALIKOTA
	function cetak_spt_walikotapelaksana(){
		
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$data = $this->m_laporan->get($telaah_id);
		//$walikota = $this->m_laporan->get_walikota();
		$tanggal_laporan = $this->m_laporan->tanggal_laporan($telaah_id);
		
		if($tanggal_laporan[0]['tanda_tangan_spt']==1){
			$kepala_opd = $this->m_laporan->get_walikota();
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==14){
			$kepala_opd = $this->m_laporan->get_wakil_walikota();
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==2){
			$kepala_opd = $this->m_laporan->get_asisten1();
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==17){
			$kepala_opd = $this->m_laporan->get_asisten2();
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==18){
			$kepala_opd = $this->m_laporan->get_asisten3();
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==9){
			$kepala_opd = $this->m_laporan->get_sekwan();
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==3){
			$kepala_opd = $this->m_laporan->get_sekdaFix();
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==4){
			$kepala_opd = $this->m_laporan->get_kepala_opd($data[0]['skpd_id']);
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==5){
			$kepala_opd = $this->m_laporan->get_pimpinan_dprdFix();
		} 
		
		$tanggal_laporan = $this->m_laporan->tanggal_laporan($telaah_id);
		
        $pdf = new FPDF('P','mm','legal');
		$pdf->SetMargins(25, 3.175, 25);
        // membuat halaman baru
        $pdf->AddPage();
		//$pdf->Image('./assets2/dist/img/kota_kendari.png',20,10,-1700);
		$pdf-> Image('./assets2/dist/img/garuda.png',90,10,30,30);
		//$pdf->Image(base_url()."admin/qr_generator?code=sppdkotakendari", 10, 265, 20, 20, "png");
		//$pdf-> Image('./upload/tanda_tangan/'.$kepala_opd[0]['tanda_tangan'],130,200,40,15);
		
		QRcode::png('http://sppd.kendarikota.go.id/qr/sppd?telaah_id='.$this->input->get('telaah_id'),"test.png");
		$pdf->Image("test.png", 180, 10, 20, 20, "png");
		
		$pdf->SetFont('Times','B',26);
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,40,'',0,1);
		
		$pdf->Cell(160,7,'WALIKOTA KENDARI',0,1,'C');
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
		
        // setting jenis font yang akan digunakan
        $pdf->SetFont('Times','BU',16);
        // Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,4,'',0,1);
		
        $pdf->Cell(160,7,'SURAT PERINTAH TUGAS',0,1,'C');
        // Memberikan space kebawah agar tidak terlalu rapat
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(45,6,'',0,0);
        $pdf->Cell(115,6,'No.',0,1);
		
        $pdf->Cell(10,4,'',0,1);
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Dari',0,0);
		$telaah_kepada = strtolower($data[0]['telaah_kepada']);
		$telaah_kepada2 = ucwords($telaah_kepada);
        $pdf->Cell(140,6,': '.$telaah_kepada2,0,1);
		
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
		
        $pdf->SetFont('Times','B',14);
        $pdf->Cell(160,7,'MEMERINTAHKAN',0,1,'C');
		
        // Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Kepada',0,0);
        $pdf->Cell(3,6,':',0,0);
        $pdf->Cell(137,6,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'',0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(5,6,'1.',0,0);
        $pdf->Cell(35,6,'Nama',0,0);
        $pdf->Cell(2,6,':',0,0);
        $pdf->Cell(95,6,$data[0]['pegawai_nama'],0,1);
		
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
		
        $pdf->Cell(20,6,'',0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(5,6,'',0,0);
        $pdf->Cell(35,6,'Jabatan',0,0);
        $pdf->Cell(2,6,':',0,0);
		//$pegawai_namajabatan = ucwords($data[0]['pegawai_namajabatan']);
        $pdf->MultiCell(95,6,$data[0]['pegawai_namajabatan'],0,1);
		
		// Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
		
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
		
		//$hasil1=strlen($telaah_perihal);
		//$hasil2 = 500 - $hasil1;
		//$text=str_repeat(' ',$hasil2);
        //$pdf->MultiCell(137,6,$telaah_perihal.' '.$text,0,'J');
		
		$pdf->SetFont('Times','B',10);
        $pdf->MultiCell(137,6,$telaah_perihal,0,'J');
		
		// Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,7,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->MultiCell(160,6,'Demikian Surat Tugas ini diberikan kepada yang bersangkutan untuk dilaksanakan dengan penuh rasa tanggung jawab.',0,'J');
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(110,6,'',0,0);
        $pdf->Cell(50,6,'Ditetapkan Di Kendari',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(110,6,'',0,0);
        $pdf->Cell(50,6,'Pada Tanggal : '.$this->tgl_indo($tanggal_laporan[0]['tanggal_spt']),0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(110,6,'',0,0);
        $pdf->MultiCell(50,6,$kepala_opd[0]['pegawai_namajabatan'],0,1);
		
        // Memberikan space kebawah agar tidak terlalu rapat
		//$pdf->Cell(10,20,'',0,1);
		$x=$pdf->GetX();
        $y=$pdf->GetY()+3;
		
		if(count($kepala_opd[0]['tanda_tangan'])!=0){
			$pdf->MultiCell(10,20,$pdf-> Image('./upload/tanda_tangan/'.$kepala_opd[0]['tanda_tangan'],135,$y,40,15),0,0);
		} else {
			$pdf->Cell(10,20,'',0,1);
		}
		
		$pdf->Cell(110,6,'',0,0);
		$pdf->Cell(50,6,$kepala_opd[0]['pegawai_nama'],0,1);
		
		if($tanggal_laporan[0]['tanda_tangan_spt']==1 || $tanggal_laporan[0]['tanda_tangan_spt']==14){
			
		} else {
			$pdf->Cell(110,6,'',0,0);
			$pdf->Cell(50,6,$kepala_opd[0]['pangkat'].", Gol. ".$kepala_opd[0]['pegawai_golongan'],0,1);
			$pdf->Cell(110,6,'',0,0);
			$pdf->Cell(50,6,'NIP.'. $kepala_opd[0]['pegawai_nip'],0,1);
		} 
        // Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,7,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Tembusan Yth:',0,0);
        $pdf->Cell(3,6,':',0,0);
        $pdf->Cell(137,6,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'',0,0);
        $pdf->Cell(3,6,'',0,0);
		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
        $pdf->Cell(137,6,'1. Kepala Badan Kepegawaian dan Pengembangan SDM Kota Kendari di Kendari',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'',0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(137,6,'2. Bagian Organisasi dan Pemberdayaan Aparatur Kota Kendari di Kendari',0,1);
		
        // Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,10,'',0,1);
		
		$pdf->SetFont('Times','BI',10);
		//$pdf->MultiCell(160, 5, 'Catatan : Jika Walikota berhalangan atau berada diluar Daerah maka penandatanganan SPT dapat dilakukan oleh Wakil Walikota atau Sekretaris Daerah jika Wakil Walikota juga berhalangan atau berada diluar daerah.', 0,'J');
       
		$pdf->Output('D','SPT - '.$data[0]['pegawai_nama'].' - '.date("d-m-Y", strtotime($tanggal_laporan[0]['tanggal_spt'])).'.pdf'); 
    }
	
	
	
}