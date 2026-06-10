<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Laporan_dprd extends public_Controller {
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
	
	/// SPD Pelaksana
	function cetak_spd_dprd(){
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$data = $this->m_laporan->get_dprd($telaah_id);
		$tanggal_laporan = $this->m_laporan->tanggal_laporan($telaah_id);
		
		if($this->ion_auth->user()->row()->jenis_skpd == 2){
			 if($tanggal_laporan[0]['tanda_tangan_spd']==9){
				$kepala_opd = $this->m_laporan->get_sekwan();
			} else if ($tanggal_laporan[0]['tanda_tangan_spd']==7){
				$kepala_opd = $this->m_laporan->get_kabid_opd($tanggal_laporan[0]['tanda_tangan_spd_pegawai']);
			} 
		} else if($this->ion_auth->user()->row()->jenis_skpd == 3){
			$kepala_opd = $this->m_laporan->get_sekda($data[0]['skpd_id']);
		} else {
			$kepala_opd = $this->m_laporan->get_kepala_opd($data[0]['skpd_id']);
		}
		
        $pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(25, 3.175, 25);
		
		$pdf->SetAutoPageBreak(false);
        // membuat halaman baru
        $pdf->AddPage();
		
		$pdf->Cell(10,12,'',0,1);
        $pdf->SetFont('Arial','B',20);
		//$pdf-> Image('./assets2/dist/img/kota_kendari.png',20,16,25,25); SEKRETARIAT_DPRD.jpeg
		$pdf-> Image('./upload/kop_surat/'.$data[0]['kop_surat'],20,16,170,30);
		
		
		QRcode::png('http://sppd.kendarikota.go.id/qr/sppd?telaah_id='.$this->input->get('telaah_id'),"test.png");
		$pdf->Image("test.png", 180, 5, 20, 20, "png");
		
		$pdf->Cell(10,25,'',0,1);
        // Memberikan space kebawah agar tidak terlalu rapat
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
		
		//1
        $pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(5,6,'1.',1,0);
        $pdf->Cell(75,6,'Pejabat berwenang yang memberi perintah',1,0);
        $pdf->Cell(80,6,'Sekretaris DPRD Kota Kendari',1,1);
		
		//2
        $pdf->Cell(5,6,'2.',1,0);
        $pdf->Cell(75,6,'Nama Pegawai yang diperintahkan',1,0);
        $pdf->Cell(80,6,$data[0]['anggotadprd_name'],1,1);
		
		//3.a
        $pdf->Cell(5,6,'3.','LTR',0,'T');
        $pdf->Cell(5,6,'a.','LT',0,'T');
        $pdf->Cell(70,6,'Pangkat dan Golongan ruang gaji','TR',0);
        $pdf->Cell(80,6,$data[0]['pangkat']." ".$data[0]['pegawai_golongan'],'LTR',1);
		
        $pdf->Cell(5,6,'','LR',0,'T');
        $pdf->Cell(5,6,'','L',0,'T');
        $pdf->Cell(70,6,'menurut PP No.30 Tahun 2015','R',0);
        $pdf->Cell(80,6,'','LR',1);
		
		//3.b
		$pdf->SetWidths(array(5,5,70,80));
		$border = array('LR','L', 'R', 'LR');
		$align = array('','','','J');
		$caption = array("","b.","Jabatan / Instansi",$data[0]['anggotadprd_jabatan']);
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
		
		/*$pengikut = $this->m_laporan->get_pengikut_dprd($telaah_id);
			$jml_pengikut = count($pengikut);
			if(!isset($pengikut[0]) || $pengikut[0] == ""){
			} else {
				for($i=0;$i<$jml_pengikut;$i++){
					$pdf->Cell(5,6,'','LR',0,'T');
					$pdf->Cell(5,6,($i+1).'.','L',0,'T');
					$pdf->Cell(70,6,$pengikut[$i]['anggotadprd_name'],'R',0);
					$pdf->Cell(80,6,'','LR',1);
				}
				
			}*/
			
		// $total = 4 - $i;
		
		// for($n=0;$n<$total;$n++){
		// 	$pdf->Cell(5,6,'','LR',0,'T');
		// 	$pdf->Cell(5,6,'','L',0,'T');
		// 	$pdf->Cell(70,6,'','R',0);
		// 	$pdf->Cell(80,6,'','LR',1);
		// }
		
		//9
		$pdf->Cell(5,6,'9.',1,0);
        $pdf->Cell(75,6,'Pembebanan Anggaran',1,0);
        $pdf->Cell(80,6,'',1,1);
		
		//9.a
		$pdf->Cell(5,6,'','LR',0);
        $pdf->Cell(5,6,'a.','LT',0);
        $pdf->Cell(70,6,'Instansi','TR',0);
        $pdf->Cell(80,6,'SEKRETARIAT DPRD KOTA KENDARI','LTR',1);
		
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
		
		
		// TANDA TANGAN
        $pdf->Cell(10,7,'',0,1);
		
        $pdf->Cell(80,5,'',0,0);
        $pdf->Cell(35,5,'Dikeluarkan di',0,0);
        $pdf->Cell(45,5,': Kendari',0,1);
		
        $pdf->Cell(80,5,'',0,0);
        $pdf->Cell(35,5,'Tanggal',0,0);
        $pdf->Cell(45,5,': '.$this->tgl_indo($tanggal_laporan[0]['tanggal_spd']),0,1);
		
        $pdf->Cell(80,5,'',0,0);
		
		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
		
		/*if($this->ion_auth->user()->row()->jenis_skpd == 2){
			$pdf->Cell(80,5,'Ketua DPRD',0,1);
		} else if($this->ion_auth->user()->row()->jenis_skpd == 3){
			$pdf->Cell(80,5,'Sekretaris Daerah',0,1);
		} else {
			$pdf->Cell(80,5,'Kepala '.$skpd_nama2.'',0,1);
		}*/
		
		$pdf->MultiCell(80,5,$kepala_opd[0]['pegawai_namajabatan'],0,1);
		
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
		$pdf->Cell(30,4,$kepala_opd[0]['pegawai_namajabatan'],0,0);
		
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
		
		$pdf->SetWidths(array(5,75,5,75));
		$border = array('L','R', 'L', 'R');
		$align = array('','J','','J');
		$caption = array("",$kepala_opd[0]['pegawai_namajabatan'],"",$kepala_opd[0]['pegawai_namajabatan']);
		$pdf->Row($caption, $border, $align);
		
		$pdf->Cell(5,4,'','L',0);
		//$pdf->Cell(75,4,'Kepala OPD','R',0);
		$pdf->Cell(75,4,'','R',0);
		$pdf->Cell(5,4,'','L',0);
		//$pdf->Cell(75,4,'Kepala OPD','R',1);
		$pdf->Cell(75,4,'','R',1);
		
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
		$pdf->Cell(75,4,'NIP .'.$kepala_opd[0]['pegawai_nip'],'R',0,'C');
		//$pdf->Cell(75,4,'','R',0,'C');
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'NIP .'.$kepala_opd[0]['pegawai_nip'],'R',1,'C');
		//$pdf->Cell(75,4,'','R',1,'C');
		
		$pdf->Cell(5,4,'V.','LTB',0);
		$pdf->Cell(155,4,'Keterangan Lain-lain','RTB',1);
		
		$pdf->Cell(5,4,'VI.','LT',0);
		$pdf->Cell(155,4,'PERHATIAN','RT',1);
		
		$pdf->MultiCell(160,4,'Pejabat yang berwenang memberi SPPD pegawai yang melakukan Perjalanan Dinas, para pejabat yang mengesahkan tanggal berangkat/tiba, serta bendaharawan bertanggung jawab berdasarkan peraturan - peraturan Keuangan Negara, apabila Negara menderita rugi akibat kesalahan, kelalaian dan kealpaan (Lampiran SK. Menteri Keuangan tanggal 30-4-1974 Nomor B-296/MK/I/1974).',1,'J');
		
		$pdf->Output('D','SPPD - '.$data[0]['anggotadprd_name'].' - '.date("d-m-Y", strtotime($tanggal_laporan[0]['tanggal_spd'])).'.pdf');  
    }
	
	function cetak_spd_dprd2(){
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$data = $this->m_laporan->get_dprd($telaah_id);
		$pengikut = $this->m_laporan->get_pengikut_dprd3($telaah_id, $this->input->get('pegawai_id'));
			
		$tanggal_laporan = $this->m_laporan->tanggal_laporan($telaah_id);
		
		if($this->ion_auth->user()->row()->jenis_skpd == 2){
			//$kepala_opd = $this->m_laporan->get_pimpinan_dprd($data[0]['skpd_id']);
			$kepala_opd = $this->m_laporan->get_sekwan();
		} else if($this->ion_auth->user()->row()->jenis_skpd == 3){
			$kepala_opd = $this->m_laporan->get_sekda($data[0]['skpd_id']);
		} else {
			$kepala_opd = $this->m_laporan->get_kepala_opd($data[0]['skpd_id']);
		}
		
        $pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(25, 3.175, 25);
		
		$pdf->SetAutoPageBreak(false);
        // membuat halaman baru
        $pdf->AddPage();
		
		$pdf->Cell(10,12,'',0,1);
        $pdf->SetFont('Arial','B',20);
		//$pdf-> Image('./assets2/dist/img/kota_kendari.png',20,16,25,25); SEKRETARIAT_DPRD.jpeg
		$pdf-> Image('./upload/kop_surat/'.$data[0]['kop_surat'],20,16,170,30);
		
		
		QRcode::png('http://sppd.kendarikota.go.id/qr/sppd?telaah_id='.$this->input->get('telaah_id'),"test.png");
		$pdf->Image("test.png", 180, 5, 20, 20, "png");
		
		$pdf->Cell(10,25,'',0,1);
        /*$pdf->SetFont('Arial','B',20);
        $pdf->Cell(30,7,'',0,0,'C');
        $pdf->Cell(110,7,'PEMERINTAH KOTA KENDARI',0,1,'C');
        $pdf->SetFont('Times','B',12);*/
        /*$pdf->SetFont('Arial','B',12);
        $pdf->Cell(30,7,'',0,0,'C');
        $pdf->Cell(110,7,$data[0]['skpd_nama'],0,1,'C');
        $pdf->Cell(30,7,'',0,0,'C');
        $pdf->Cell(110,7,'KOTA KENDARI',0,1,'C');
        $pdf->SetFont('Times','',10);
        $pdf->Cell(30,7,'',0,0,'C');
        $pdf->Cell(110,7,'JL. DRS. ABD. SILONDAE NO.8 KOTA KENDARI',0,1,'C');
		
		$pdf->Cell(0, 1, '_______________________________________________________________________________________', 0, 1,'C');*/
		
        // Memberikan space kebawah agar tidak terlalu rapat
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
		
        //1
        $pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(5,6,'1.',1,0);
        $pdf->Cell(75,6,'Pejabat berwenang yang memberi perintah',1,0);
        $pdf->Cell(80,6,'Sekretaris DPRD Kota Kendari',1,1);
		
		//2
        $pdf->Cell(5,6,'2.',1,0);
        $pdf->Cell(75,6,'Nama Pegawai yang diperintahkan',1,0);
        $pdf->Cell(80,6,$pengikut[0]['anggotadprd_name'],1,1);
		
		//3
        $pdf->Cell(5,6,'3.','LTR',0,'T');
        $pdf->Cell(5,6,'a.','LT',0,'T');
        $pdf->Cell(70,6,'Pangkat dan Golongan ruang gaji','TR',0);
        $pdf->Cell(80,6,$pengikut[0]['pangkat']." ".$pengikut[0]['pegawai_golongan'],'LTR',1);
		
        $pdf->Cell(5,6,'','LR',0,'T');
        $pdf->Cell(5,6,'','L',0,'T');
        $pdf->Cell(70,6,'menurut PP No.30 Tahun 2015','R',0);
        $pdf->Cell(80,6,'','LR',1);
		
		//3.b
		$pdf->SetWidths(array(5,5,70,80));
		$border = array('LR','L', 'R', 'LR');
		$align = array('','','','J');
		$caption = array("","b.","Jabatan / Instansi",$pengikut[0]['anggotadprd_jabatan']);
		$pdf->Row($caption, $border, $align);
		
		//3.c
		$pdf->SetWidths(array(5,5,70,80));
		$border = array('LR','L', 'R', 'LR');
		$caption = array("","c.","Tingkat biaya perjalanan dinas","");
		$pdf->Row($caption, $border);
		
        $pdf->Cell(5,6,'','LBR',0,'T');
        $pdf->Cell(5,6,'c.','L',0,'T');
        $pdf->Cell(70,6,'Tingkat biaya perjalanan dinas','R',0);
        $pdf->Cell(80,6,'','LBR',1);
		
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
		
		/*$pengikut = $this->m_laporan->get_pengikut_dprd($telaah_id);
			$jml_pengikut = count($pengikut);
			if(!isset($pengikut[0]) || $pengikut[0] == ""){
			} else {
				for($i=0;$i<$jml_pengikut;$i++){
					$pdf->Cell(5,6,'','LR',0,'T');
					$pdf->Cell(5,6,($i+1).'.','L',0,'T');
					$pdf->Cell(70,6,$pengikut[$i]['anggotadprd_name'],'R',0);
					$pdf->Cell(80,6,'','LR',1);
				}
				
			}*/
			
		// $total = 4 - $i;
		
		// for($n=0;$n<$total;$n++){
		// 	$pdf->Cell(5,6,'','LR',0,'T');
		// 	$pdf->Cell(5,6,'','L',0,'T');
		// 	$pdf->Cell(70,6,'','R',0);
		// 	$pdf->Cell(80,6,'','LR',1);
		// }
		
		//9
		$pdf->Cell(5,6,'9.',1,0);
        $pdf->Cell(75,6,'Pembebanan Anggaran',1,0);
        $pdf->Cell(80,6,'',1,1);
		
		//9.a
		$pdf->Cell(5,6,'','LR',0);
        $pdf->Cell(5,6,'a.','LT',0);
        $pdf->Cell(70,6,'Instansi','TR',0);
        $pdf->Cell(80,6,'SEKRETARIAT DPRD KOTA KENDARI','LTR',1);
		
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
		
        $pdf->Cell(80,5,'',0,0);
		
		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
		
		/*if($this->ion_auth->user()->row()->jenis_skpd == 2){
			$pdf->Cell(80,5,'Ketua DPRD',0,1);
		} else if($this->ion_auth->user()->row()->jenis_skpd == 3){
			$pdf->Cell(80,5,'Sekretaris Daerah',0,1);
		} else {
			$pdf->Cell(80,5,'Kepala '.$skpd_nama2.'',0,1);
		}*/
		
		$pdf->MultiCell(80,5,$kepala_opd[0]['pegawai_namajabatan'],0,1);
		
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
		$pdf->Cell(30,4,$kepala_opd[0]['pegawai_namajabatan'],0,0);
		
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
		//$pdf->Cell(75,4,'','R',0);
		$pdf->Cell(5,4,'','L',0);
		$pdf->Cell(75,4,'Pejabat yang memberi perintah','R',1);
		//$pdf->Cell(75,4,'','R',1);
		
		$pdf->SetWidths(array(5,75,5,75));
		$border = array('L','R', 'L', 'R');
		$align = array('','J','','J');
		$caption = array("",$kepala_opd[0]['pegawai_namajabatan'],"",$kepala_opd[0]['pegawai_namajabatan']);
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
		
		$pdf->Output('D','SPPD - '.$pengikut[0]['anggotadprd_name'].' - '.date("d-m-Y", strtotime($tanggal_laporan[0]['tanggal_spd'])).'.pdf');  
    }
	
	function cetak_spt_dprd(){
		
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$data = $this->m_laporan->get_dprd($telaah_id);
		$data2 = $this->m_laporan->get_pengikut_dprd2($telaah_id);
		$tanggal_laporan = $this->m_laporan->tanggal_laporan($telaah_id);
		
		if($tanggal_laporan[0]['tanda_tangan_spt']==5){
			$pimpinan_dprd = $this->m_laporan->get_pimpinan_dprdFix();
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==20){
			$pimpinan_dprd = $this->m_laporan->get_wakilketua_dprd();
		} 
		
		
        $pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(25, 3.175, 25);
        // membuat halaman baru
        $pdf->AddPage();
		//$pdf-> Image('./assets2/dist/img/kota_kendari.png',20,10,160,20);
		$pdf-> Image('./upload/kop_surat/'.$data[0]['kop_surat2'],20,16,170,30);
		//$pdf->Image(base_url()."admin/qr_generator?code=sppdkotakendari", 10, 270, 20, 20, "png");
		QRcode::png('http://sppd.kendarikota.go.id/qr/sppd?telaah_id='.$this->input->get('telaah_id'),"test.png");
		$pdf->Image("test.png", 190, 5, 20, 20, "png");
		
        // setting jenis font yang akan digunakan
        $pdf->SetFont('Times','BU',16);
        // Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,45,'',0,1);
		
        $pdf->Cell(160,7,'SURAT PERINTAH TUGAS',0,1,'C');
        // Memberikan space kebawah agar tidak terlalu rapat
		
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
        $pdf->Cell(35,5,'Kendari, '.$this->tgl_indo($tanggal_laporan[0]['tanggal_spt']),0,1);
		
        $pdf->Cell(80,5,'',0,0);
		//$skpd_nama = strtolower($data[0]['skpd_nama']);
		//$skpd_nama2 = ucwords($skpd_nama);
		if($tanggal_laporan[0]['tanda_tangan_spt']==5){
			$pdf->Cell(80,5,'KETUA DEWAN PERWAKILAN RAKYAT DAERAH',0,1,'C');
		} else if($tanggal_laporan[0]['tanda_tangan_spt']==20){
			$pdf->Cell(80,5,'WAKIL KETUA DEWAN PERWAKILAN RAKYAT DAERAH',0,1,'C');
		} 
        
		
        $pdf->Cell(80,5,'',0,0);
        $pdf->Cell(80,5,'KOTA KENDARI',0,1,'C');
		
		if($pimpinan_dprd[0]['status']==1){
			$x=$pdf->GetX();
			$y=$pdf->GetY();
			$pdf->MultiCell(20,0,$pdf-> Image('./upload/tanda_tangan/'.$pimpinan_dprd[0]['tanda_tangan'],125,$y,40,15),0,0);
			$pdf->Cell(10,20,'',0,1);
		} else {
			$pdf->Cell(10,20,'',0,1);
		}
		
        $pdf->Cell(80,5,'',0,0);
        $pdf->Cell(80,5,$pimpinan_dprd[0]['pegawai_nama'],0,1,'C');
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Tembusan Yth:',0,0);
        $pdf->Cell(3,6,':',0,0);
        $pdf->Cell(137,6,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'',0,0);
        $pdf->Cell(3,6,'',0,0);
		/*$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
        $pdf->Cell(137,6,'1. Kepala '.$skpd_nama2.' di Kendari',0,1);*/
		$pdf->Cell(137,6,'1. Walikota kendari di Kendari',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'',0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(137,6,'2. Arsip',0,1);
		
        // Memberikan space kebawah agar tidak terlalu rapat
		//$pdf->Cell(10,10,'',0,1);
		
		//$pdf->SetFont('Times','BI',10);
		//$pdf->MultiCell(160, 5, 'Catatan : Jika Walikota berhalangan atau berada diluar Daerah maka penandatanganan SPT dapat dilakukan oleh Wakil Walikota atau Sekretaris Daerah jika Wakil Walikota juga berhalangan atau berada diluar daerah.', 0,'J');
		
		$pdf->Output('D','SPT - '.$data[0]['anggotadprd_name'].' - '.date("d-m-Y", strtotime($tanggal_laporan[0]['tanggal_spt'])).'.pdf'); 
    }
	
	
	public function cetak_kuitansi_panjar_dprd(){
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$data = $this->m_laporan->get_rincian_dprd($telaah_id, $this->input->get('pegawai_id'));
		$tanggal_laporan = $this->m_laporan->tanggal_laporan($telaah_id);
		
		//if($this->ion_auth->user()->row()->jenis_skpd == 2 || $this->ion_auth->user()->row()->jenis_skpd == 6 ){
			//$kepala_opd = $this->m_laporan->get_pimpinan_dprd($data[0]['skpd_id']);
			$sekwan = $this->m_laporan->get_sekwan();
		//} else if($this->ion_auth->user()->row()->jenis_skpd == 3){
		//	$kepala_opd = $this->m_laporan->get_sekda($data[0]['skpd_id']);
		//} else {
		//	$kepala_opd = $this->m_laporan->get_kepala_opd($data[0]['skpd_id']);
		//}
		
		
		$bendahara = $this->m_laporan->get_bendahara_dprd();
		
        $pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(20, 3.175, 25);
        // membuat halaman baru
        $pdf->AddPage();
		//$pdf->Image(base_url()."admin/qr_generator?code=sppdkotakendari", 10, 270, 20, 20, "png");
		
		QRcode::png('http://sppd.kendarikota.go.id/qr/sppd?telaah_id='.$this->input->get('telaah_id'),"test.png");
		$pdf->Image("test.png", 185, 5, 20, 20, "png");
		
		$pdf->SetFont('Times','',9);
        $pdf->Cell(1,20,'',0,1);
        $pdf->Cell(10,6,'PEMERINTAH KOTA KENDARI',0,0);
        $pdf->Cell(100,6,'',0,0);
        $pdf->Cell(35,6,'TAHUN ANGGARAN',0,0);
        $pdf->Cell(25,6,': '.date("Y"),0,1);
		
		$skpd_nama = strtoupper($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
        $pdf->Cell(10,6,$skpd_nama2,0,0);
        $pdf->Cell(100,6,'',0,0);
        $pdf->Cell(35,6,'KODE REKENING',0,0);
        $pdf->MultiCell(25,6,': '.$data[0]['no_rekening'],0,1);
		
        $pdf->Cell(110,6,'',0,0);
        $pdf->Cell(35,6,'BKU NO.',0,0);
        $pdf->Cell(25,6,': ',0,1);
		
        $pdf->Cell(110,6,'',0,0);
        $pdf->Cell(35,6,'TANGGAL',0,0);
        $pdf->Cell(25,6,': ',0,1);
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,7,'',0,1);
		
        $pdf->SetFont('Times','BUI',16);
        $pdf->Cell(170,7,'KUITANSI',0,1,'C');
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,7,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(55,6,'SUDAH TERIMA DARI',0,0);
        $pdf->Cell(5,6,':',0,0);
		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
        $pdf->MultiCell(110,6,'Pengguna Anggaran '.$skpd_nama2,0,'J');
        //$pdf->Cell(35,6,'',0,0);
        //$pdf->Cell(25,6,'',0,1);
		
		$rincian = $this->m_kuitansi->get_kuitansi_panjar_dprd($telaah_id,$this->input->get('pegawai_id'));
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(55,6,'UANG SEBESAR',0,0);
        $pdf->Cell(5,6,':',0,0);
        $pdf->MultiCell(110,6,'Rp. '.number_format($rincian[0]['jumlah'],0,",","."),0,'J');
       // $pdf->Cell(35,6,'',0,0);
      //  $pdf->Cell(25,6,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(55,6,'UNTUK PEMBAYARAN',0,0);
        $pdf->Cell(5,6,':',0,0);
        $pdf->MultiCell(110,6,$data[0]['telaah_perihal'],0,'J');
       // $pdf->Cell(35,6,'',0,0);
      //  $pdf->Cell(25,6,'',0,1);
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,7,'',0,1);
		$pdf->SetFont('Times','B',10);
        $pdf->Cell(170,7,'TERBILANG : '.$this->terbilang($rincian[0]['jumlah']).' Rupiah',1,1,'C');
		
		// Memberikan space kebawah agar tidak terlalu rapat
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
		
        $pdf->Cell(55,6,'PENGGUNA ANGGARAN',0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(145,6,'BENDAHARA PENGELUARAN',0,0);
        $pdf->Cell(60,6,'',0,1,'');
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,7,'',0,1);
		$pdf->Cell(10,7,'',0,1);
		$pdf->Cell(10,7,'',0,1);
		
		$pdf->SetFont('Times','UB',9);
        $pdf->Cell(55,6,$sekwan[0]['pegawai_nama'],0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(55,6,$bendahara[0]['pegawai_nama'],0,0,'C');
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(55,6,$data[0]['anggotadprd_name'],0,1,'C');
		
		//$pdf-> Image('./upload/tanda_tangan/'.$kepala_opd[0]['tanda_tangan'],30,135,40,15);
		//$pdf-> Image('./upload/tanda_tangan/'.$bendahara[0]['tanda_tangan'],90,135,40,15);
		
		$pdf->SetFont('Times','B',9);
		$pdf->Cell(55,6,'NIP. '.$sekwan[0]['pegawai_nip'],0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(55,6,'NIP. '.$bendahara[0]['pegawai_nip'],0,0,'C');
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(55,6,'',0,1,'C');
		
		
		$pdf->Output('D','KUITANSI_PANJAR - '.$data[0]['anggotadprd_name'].' - '.date("d-m-Y", strtotime($tanggal_laporan[0]['tanggal_spd'])).'.pdf'); 
	}
	
	
	public function cetak_kuitansi_panjar2_dprd(){
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$data = $this->m_laporan->get_rincian_pengikut_dprd($telaah_id, $this->input->get('pengikut_id'));
		$tanggal_laporan = $this->m_laporan->tanggal_laporan($telaah_id);
		
		//if($this->ion_auth->user()->row()->jenis_skpd == 2 || $this->ion_auth->user()->row()->jenis_skpd == 6 ){
			//$kepala_opd = $this->m_laporan->get_pimpinan_dprd($data[0]['skpd_id']);
			$sekwan = $this->m_laporan->get_sekwan();
		//} else if($this->ion_auth->user()->row()->jenis_skpd == 3){
		//	$kepala_opd = $this->m_laporan->get_sekda($data[0]['skpd_id']);
		//} else {
		//	$kepala_opd = $this->m_laporan->get_kepala_opd($data[0]['skpd_id']);
		//}
		
		
		$bendahara = $this->m_laporan->get_bendahara_dprd();
		
        $pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(20, 3.175, 25);
        // membuat halaman baru
        $pdf->AddPage();
		//$pdf->Image(base_url()."admin/qr_generator?code=sppdkotakendari", 10, 270, 20, 20, "png");
		
		QRcode::png('http://sppd.kendarikota.go.id/qr/sppd?telaah_id='.$this->input->get('telaah_id'),"test.png");
		$pdf->Image("test.png", 185, 5, 20, 20, "png");
		
		$pdf->SetFont('Times','',9);
        $pdf->Cell(1,20,'',0,1);
        $pdf->Cell(10,6,'PEMERINTAH KOTA KENDARI',0,0);
        $pdf->Cell(100,6,'',0,0);
        $pdf->Cell(35,6,'TAHUN ANGGARAN',0,0);
        $pdf->Cell(25,6,': '.date("Y"),0,1);
		
		$skpd_nama = strtoupper($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
        $pdf->Cell(10,6,$skpd_nama2,0,0);
        $pdf->Cell(100,6,'',0,0);
        $pdf->Cell(35,6,'KODE REKENING',0,0);
        $pdf->MultiCell(25,6,': '.$data[0]['no_rekening'],0,1);
		
        $pdf->Cell(110,6,'',0,0);
        $pdf->Cell(35,6,'BKU NO.',0,0);
        $pdf->Cell(25,6,': ',0,1);
		
        $pdf->Cell(110,6,'',0,0);
        $pdf->Cell(35,6,'TANGGAL',0,0);
        $pdf->Cell(25,6,': ',0,1);
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,7,'',0,1);
		
        $pdf->SetFont('Times','BUI',16);
        $pdf->Cell(170,7,'KUITANSI',0,1,'C');
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,7,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(55,6,'SUDAH TERIMA DARI',0,0);
        $pdf->Cell(5,6,':',0,0);
		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
        $pdf->MultiCell(110,6,'Pengguna Anggaran '.$skpd_nama2,0,'J');
        //$pdf->Cell(35,6,'',0,0);
        //$pdf->Cell(25,6,'',0,1);
		
		$rincian = $this->m_kuitansi->get_kuitansi_panjar_dprd($telaah_id,$this->input->get('pengikut_id'));
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(55,6,'UANG SEBESAR',0,0);
        $pdf->Cell(5,6,':',0,0);
        $pdf->MultiCell(110,6,'Rp. '.number_format($rincian[0]['jumlah'],0,",","."),0,'J');
       // $pdf->Cell(35,6,'',0,0);
      //  $pdf->Cell(25,6,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(55,6,'UNTUK PEMBAYARAN',0,0);
        $pdf->Cell(5,6,':',0,0);
        $pdf->MultiCell(110,6,$data[0]['telaah_perihal'],0,'J');
       // $pdf->Cell(35,6,'',0,0);
      //  $pdf->Cell(25,6,'',0,1);
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,7,'',0,1);
		$pdf->SetFont('Times','B',10);
        $pdf->Cell(170,7,'TERBILANG : '.$this->terbilang($rincian[0]['jumlah']).' Rupiah',1,1,'C');
		
		// Memberikan space kebawah agar tidak terlalu rapat
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
		
        $pdf->Cell(55,6,'PENGGUNA ANGGARAN',0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(145,6,'BENDAHARA PENGELUARAN',0,0);
        $pdf->Cell(60,6,'',0,1,'');
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,7,'',0,1);
		$pdf->Cell(10,7,'',0,1);
		$pdf->Cell(10,7,'',0,1);
		
		$pdf->SetFont('Times','UB',9);
        $pdf->Cell(55,6,$sekwan[0]['pegawai_nama'],0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(55,6,$bendahara[0]['pegawai_nama'],0,0,'C');
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(55,6,$data[0]['anggotadprd_name'],0,1,'C');
		
		//$pdf-> Image('./upload/tanda_tangan/'.$kepala_opd[0]['tanda_tangan'],30,135,40,15);
		//$pdf-> Image('./upload/tanda_tangan/'.$bendahara[0]['tanda_tangan'],90,135,40,15);
		
		$pdf->SetFont('Times','B',9);
		$pdf->Cell(55,6,'NIP. '.$sekwan[0]['pegawai_nip'],0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(55,6,'NIP. '.$bendahara[0]['pegawai_nip'],0,0,'C');
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(55,6,'',0,1,'C');
		
		
		$pdf->Output('D','KUITANSI_PANJAR - '.$data[0]['anggotadprd_name'].' - '.date("d-m-Y", strtotime($tanggal_laporan[0]['tanggal_spd'])).'.pdf'); 
	}
	
	
	
	//KUITANSI RAMPUNG 
	public function cetak_kuitansi_rampung_dprd(){
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$data = $this->m_laporan->get_rincian_dprd($telaah_id, $this->input->get('pegawai_id'));
		$tanggal_laporan = $this->m_laporan->tanggal_laporan($telaah_id);
		
		$sekwan = $this->m_laporan->get_sekwan();
		
		$bendahara = $this->m_laporan->get_bendahara_dprd();
		
		
        $pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(20, 3.175, 25);
        // membuat halaman baru
        $pdf->AddPage();
		//$pdf->Image(base_url()."admin/qr_generator?code=sppdkotakendari", 10, 270, 20, 20, "png");
		
		QRcode::png('http://sppd.kendarikota.go.id/qr/sppd?telaah_id='.$this->input->get('telaah_id'),"test.png");
		$pdf->Image("test.png", 185, 5, 20, 20, "png");
		
		$pdf->SetFont('Times','',9);
        $pdf->Cell(1,20,'',0,1);
        $pdf->Cell(10,6,'PEMERINTAH KOTA KENDARI',0,0);
        $pdf->Cell(100,6,'',0,0);
        $pdf->Cell(35,6,'TAHUN ANGGARAN',0,0);
        $pdf->Cell(25,6,': '.date("Y"),0,1);
		
		$skpd_nama = strtoupper($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
        $pdf->Cell(10,6,$skpd_nama2,0,0);
        $pdf->Cell(100,6,'',0,0);
        $pdf->Cell(35,6,'KODE REKENING',0,0);
        $pdf->MultiCell(25,6,': '.$data[0]['no_rekening'],0,1);
		
        $pdf->Cell(110,6,'',0,0);
        $pdf->Cell(35,6,'BKU NO.',0,0);
        $pdf->Cell(25,6,': ',0,1);
		
        $pdf->Cell(110,6,'',0,0);
        $pdf->Cell(35,6,'TANGGAL',0,0);
        $pdf->Cell(25,6,': ',0,1);
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,7,'',0,1);
		
        $pdf->SetFont('Times','BUI',16);
        $pdf->Cell(170,7,'KUITANSI',0,1,'C');
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,7,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(55,6,'SUDAH TERIMA DARI',0,0);
        $pdf->Cell(5,6,':',0,0);
		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
        $pdf->MultiCell(110,6,'Pengguna Anggaran '.$skpd_nama2,0,'J');
        //$pdf->Cell(35,6,'',0,0);
        //$pdf->Cell(25,6,'',0,1);
		//
		$pengeluaran_rill = $this->m_pengeluaran_rill->get_rincian_dprd($telaah_id,$data[0]['anggotadprd_id']);
		
		$no = 1;
		$jumlah_pengeluaran_rill = 0;
		foreach($pengeluaran_rill as $v){
			$jumlah_pengeluaran_rill = $jumlah_pengeluaran_rill + $v->tarif;
		}
		
		$rincian = $this->m_rincian->get_rincian_dprd($telaah_id,$this->input->get('pegawai_id'));
		
		$jumlah = 0;
		foreach($rincian as $v){
			$jumlah = $jumlah + ($v->tarif * $v->item);
		}
		
		$total = $jumlah_pengeluaran_rill + $jumlah;
		
		$panjar = $this->m_kuitansi->get_kuitansi_panjar_dprd($telaah_id,$this->input->get('pegawai_id'));
		
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(55,6,'UANG SEBESAR',0,0);
        $pdf->Cell(5,6,':',0,0);
        $pdf->MultiCell(110,6,'Rp. '.number_format($total,0,",","."),0,'J');
       // $pdf->Cell(35,6,'',0,0);
       // $pdf->Cell(25,6,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(55,6,'UNTUK PEMBAYARAN',0,0);
        $pdf->Cell(5,6,':',0,0);
        $pdf->MultiCell(110,6,$data[0]['telaah_perihal'],0,'J');
       // $pdf->Cell(35,6,'',0,0);
       // $pdf->Cell(25,6,'',0,1);
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,7,'',0,1);
		$pdf->SetFont('Times','B',10);
        $pdf->Cell(170,7,'TERBILANG : '.$this->terbilang($total - $panjar[0]['jumlah']).' Rupiah',1,1,'C');
		
		// Memberikan space kebawah agar tidak terlalu rapat
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
		
        $pdf->Cell(55,6,'PENGGUNA ANGGARAN',0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(145,6,'BENDAHARA PENGELUARAN',0,0);
        $pdf->Cell(60,6,'',0,1,'');
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,7,'',0,1);
		$pdf->Cell(10,7,'',0,1);
		$pdf->Cell(10,7,'',0,1);
		
		$pdf->SetFont('Times','UB',9);
        $pdf->Cell(55,6,$sekwan[0]['pegawai_nama'],0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(55,6,$bendahara[0]['pegawai_nama'],0,0,'C');
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(55,6,$data[0]['anggotadprd_name'],0,1,'C');
		
		//$pdf-> Image('./upload/tanda_tangan/'.$kepala_opd[0]['tanda_tangan'],30,135,40,15);
		//$pdf-> Image('./upload/tanda_tangan/'.$bendahara[0]['tanda_tangan'],90,135,40,15);
		
		$pdf->SetFont('Times','B',9);
		$pdf->Cell(55,6,'NIP. '.$sekwan[0]['pegawai_nip'],0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(55,6,'NIP. '.$bendahara[0]['pegawai_nip'],0,0,'C');
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(55,6,'',0,1,'C');
		
		
		$pdf->Output('D','KUITANSI_RAMPUNG - '.$data[0]['anggotadprd_name'].' - '.date("d-m-Y", strtotime($tanggal_laporan[0]['tanggal_spd'])).'.pdf');
	}
	
	//KUITANSI RAMPUNG PENGIKUT ANGGOTA DPRD
	public function cetak_kuitansi_rampung2_dprd(){
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$data = $this->m_laporan->get_rincian_pengikut_dprd($telaah_id, $this->input->get('pengikut_id'));
		$tanggal_laporan = $this->m_laporan->tanggal_laporan($telaah_id);
		
		$sekwan = $this->m_laporan->get_sekwan();
		
		$bendahara = $this->m_laporan->get_bendahara_dprd();
		
		
        $pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(20, 3.175, 25);
        // membuat halaman baru
        $pdf->AddPage();
		//$pdf->Image(base_url()."admin/qr_generator?code=sppdkotakendari", 10, 270, 20, 20, "png");
		
		QRcode::png('http://sppd.kendarikota.go.id/qr/sppd?telaah_id='.$this->input->get('telaah_id'),"test.png");
		$pdf->Image("test.png", 185, 5, 20, 20, "png");
		
		$pdf->SetFont('Times','',9);
        $pdf->Cell(1,20,'',0,1);
        $pdf->Cell(10,6,'PEMERINTAH KOTA KENDARI',0,0);
        $pdf->Cell(100,6,'',0,0);
        $pdf->Cell(35,6,'TAHUN ANGGARAN',0,0);
        $pdf->Cell(25,6,': '.date("Y"),0,1);
		
		$skpd_nama = strtoupper($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
        $pdf->Cell(10,6,$skpd_nama2,0,0);
        $pdf->Cell(100,6,'',0,0);
        $pdf->Cell(35,6,'KODE REKENING',0,0);
        $pdf->MultiCell(25,6,': '.$data[0]['no_rekening'],0,1);
		
        $pdf->Cell(110,6,'',0,0);
        $pdf->Cell(35,6,'BKU NO.',0,0);
        $pdf->Cell(25,6,': ',0,1);
		
        $pdf->Cell(110,6,'',0,0);
        $pdf->Cell(35,6,'TANGGAL',0,0);
        $pdf->Cell(25,6,': ',0,1);
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,7,'',0,1);
		
        $pdf->SetFont('Times','BUI',16);
        $pdf->Cell(170,7,'KUITANSI',0,1,'C');
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,7,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(55,6,'SUDAH TERIMA DARI',0,0);
        $pdf->Cell(5,6,':',0,0);
		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
        $pdf->MultiCell(110,6,'Pengguna Anggaran '.$skpd_nama2,0,'J');
        //$pdf->Cell(35,6,'',0,0);
        //$pdf->Cell(25,6,'',0,1);
		//
		$pengeluaran_rill = $this->m_pengeluaran_rill->get_rincian_dprd($telaah_id,$data[0]['anggotadprd_id']);
		
		$no = 1;
		$jumlah_pengeluaran_rill = 0;
		foreach($pengeluaran_rill as $v){
			$jumlah_pengeluaran_rill = $jumlah_pengeluaran_rill + $v->tarif;
		}
		
		$rincian = $this->m_rincian->get_rincian_dprd($telaah_id,$data[0]['anggotadprd_id']);
		
		$jumlah = 0;
		foreach($rincian as $v){
			$jumlah = $jumlah + ($v->tarif * $v->item);
		}
		
		$total = $jumlah_pengeluaran_rill + $jumlah;
		
		$panjar = $this->m_kuitansi->get_kuitansi_panjar_dprd($telaah_id,$data[0]['anggotadprd_id']);
		
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(55,6,'UANG SEBESAR',0,0);
        $pdf->Cell(5,6,':',0,0);
        $pdf->MultiCell(110,6,'Rp. '.number_format($total,0,",","."),0,'J');
       // $pdf->Cell(35,6,'',0,0);
       // $pdf->Cell(25,6,'',0,1);
		
		$pdf->SetFont('Times','',10);
        $pdf->Cell(55,6,'UNTUK PEMBAYARAN',0,0);
        $pdf->Cell(5,6,':',0,0);
        $pdf->MultiCell(110,6,$data[0]['telaah_perihal'],0,'J');
       // $pdf->Cell(35,6,'',0,0);
       // $pdf->Cell(25,6,'',0,1);
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,7,'',0,1);
		$pdf->SetFont('Times','B',10);
        $pdf->Cell(170,7,'TERBILANG : '.$this->terbilang($total - $panjar[0]['jumlah']).' Rupiah',1,1,'C');
		
		// Memberikan space kebawah agar tidak terlalu rapat
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
		
        $pdf->Cell(55,6,'PENGGUNA ANGGARAN',0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(145,6,'BENDAHARA PENGELUARAN',0,0);
        $pdf->Cell(60,6,'',0,1,'');
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,7,'',0,1);
		$pdf->Cell(10,7,'',0,1);
		$pdf->Cell(10,7,'',0,1);
		
		$pdf->SetFont('Times','UB',9);
        $pdf->Cell(55,6,$sekwan[0]['pegawai_nama'],0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(55,6,$bendahara[0]['pegawai_nama'],0,0,'C');
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(55,6,$data[0]['anggotadprd_name'],0,1,'C');
		
		//$pdf-> Image('./upload/tanda_tangan/'.$kepala_opd[0]['tanda_tangan'],30,135,40,15);
		//$pdf-> Image('./upload/tanda_tangan/'.$bendahara[0]['tanda_tangan'],90,135,40,15);
		
		$pdf->SetFont('Times','B',9);
		$pdf->Cell(55,6,'NIP. '.$sekwan[0]['pegawai_nip'],0,0);
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(55,6,'NIP. '.$bendahara[0]['pegawai_nip'],0,0,'C');
        $pdf->Cell(3,6,'',0,0);
        $pdf->Cell(55,6,'',0,1,'C');
		
		
		$pdf->Output('D','KUITANSI_RAMPUNG - '.$data[0]['anggotadprd_name'].' - '.date("d-m-Y", strtotime($tanggal_laporan[0]['tanggal_spd'])).'.pdf');
	}
	
		public function cetak_rbpd_dprd(){
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$data = $this->m_laporan->get_rincian_dprd($telaah_id, $this->input->get('pegawai_id'));
		$pptk = $this->m_pptk_pengeluaran_rill->get2($telaah_id);
		$tanggal_laporan = $this->m_laporan->tanggal_laporan($telaah_id);
		
		if($this->ion_auth->user()->row()->jenis_skpd == 2){
			$kepala_opd = $this->m_laporan->get_pimpinan_dprd($data[0]['skpd_id']);
			$sekwan = $this->m_laporan->get_sekwan();
		} else if($this->ion_auth->user()->row()->jenis_skpd == 3){
			$kepala_opd = $this->m_laporan->get_sekda($data[0]['skpd_id']);
		} else {
			$kepala_opd = $this->m_laporan->get_kepala_opd($data[0]['skpd_id']);
		}
		$bendahara = $this->m_laporan->get_bendahara($data[0]['skpd_id']);
		
        $pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(5, 3.175, 25);
        // membuat halaman baru
        $pdf->AddPage();
		//$pdf->Image(base_url()."admin/qr_generator?code=sppdkotakendari", 10, 270, 20, 20, "png");
        
		QRcode::png('http://sppd.kendarikota.go.id/qr/sppd?telaah_id='.$this->input->get('telaah_id'),"test.png");
		$pdf->Image("test.png", 185, 10, 20, 20, "png");
		
        $pdf->SetFont('Times','B',12);
		$pdf->Cell(10,15,'',0,1);
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->Cell(180,6,'BIAYA PERJALANAN DINAS',0,0,'C');
        $pdf->Cell(10,6,'',0,1,'C');
		
        $pdf->SetFont('Times','',10);
		$pdf->Cell(10,2,'',0,1);
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->Cell(180,6,'An. '.$data[0]['anggotadprd_name'],0,0,'C');
        $pdf->Cell(10,6,'',0,1,'C');
		
		$pdf->Cell(10,4,'',0,1);
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->Cell(50,6,'Lampiran SPPD Nomor',0,0);
        $pdf->Cell(130,6,':',0,0);
        $pdf->Cell(10,6,'',0,1,'C');
		
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->Cell(50,6,'Tanggal',0,0);
        $pdf->Cell(130,6,': '.date("d-m-Y", strtotime($tanggal_laporan[0]['tanggal_spd'])),0,0);
        $pdf->Cell(10,6,'',0,1,'C');
		
        $pdf->Cell(10,4,'',0,1,'C');
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->Cell(7,6,'NO',1,0,'C');
        $pdf->Cell(100,6,'RINCIAN BELANJA',1,0,'C');
        $pdf->Cell(30,6,'JUMLAH',1,0,'C');
        $pdf->Cell(43,6,'KETERANGAN',1,1,'C');
		
		$pengeluaran_rill = $this->m_pengeluaran_rill->get_rincian_dprd($telaah_id,$this->input->get('pegawai_id'));
		
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
		
		
		$rincian = $this->m_rincian->get_rincian_dprd($telaah_id,$this->input->get('pegawai_id'));
		
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
		$panjar = $this->m_kuitansi->get_kuitansi_panjar_dprd($telaah_id,$this->input->get('pegawai_id'));
		
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
		//$pdf->Cell(7,6,$bendahara[0]['pegawai_nama'],0,0);
		$pdf->Cell(7,6,'',0,0);
		$pdf->Cell(100,6,'',0,0);
		$pdf->Cell(7,6,$data[0]['anggotadprd_name'],0,0);
		$pdf->Cell(23,6,'',0,0,'R');
		$pdf->Cell(43,6,'',0,1,'C');
		$pdf->Cell(10,6,'',0,0,'C');
        $pdf->SetFont('Times','',10);
		//$pdf->Cell(7,6,'NIP. '.$bendahara[0]['pegawai_nip'],0,0);
		$pdf->Cell(7,6,'',0,0);
		$pdf->Cell(100,6,'',0,0);
		//$pdf->Cell(7,6,'NIP. '.$data[0]['pegawai_nip'],0,0);
		$pdf->Cell(7,6,'',0,0);
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
		$pdf->Cell(23,6,'',0,0,'R');
		$pdf->Cell(43,6,'',0,1,'C');
		
		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
		
		$pdf->Cell(10,6,'',0,0,'C');
		$pdf->Cell(7,6,$sekwan[0]['pegawai_namajabatan'],0,0);
		$pdf->Cell(100,6,'',0,0);
		$pdf->Cell(7,6,'Pejabat Pelaksana Teknis Kegiatan',0,0);
		$pdf->Cell(23,6,'',0,0,'R');
		$pdf->Cell(43,6,'',0,1,'C');
		
        $pdf->Cell(10,15,'',0,1,'C');
		
		$pdf->Cell(10,6,'',0,0,'C');
        $pdf->SetFont('Times','BU',10);
		//$pdf-> Image('./upload/tanda_tangan/'.$sekwan[0]['tanda_tangan'],20,238,40,15);
		$pdf->Cell(7,6,$sekwan[0]['pegawai_nama'],0,0);
		$pdf->Cell(100,6,'',0,0);
		$pdf->Cell(7,6,$pptk[0]['pegawai_nama'],0,0);
		$pdf->Cell(23,6,'',0,0,'R');
		$pdf->Cell(43,6,'',0,1,'C');
		$pdf->Cell(10,6,'',0,0,'C');
        $pdf->SetFont('Times','',10);
		$pdf->Cell(7,6,'NIP. '.$sekwan[0]['pegawai_nip'],0,0);
		$pdf->Cell(100,6,'',0,0);
		$pdf->Cell(7,6,'NIP. '.$pptk[0]['pegawai_nip'],0,0);
		$pdf->Cell(7,6,'',0,0);
		$pdf->Cell(23,6,'',0,0,'R');
		$pdf->Cell(43,6,'',0,1,'C');
		
		
		$pdf->Output('D','RBPD - '.$data[0]['anggotadprd_name'].' - '.date("d-m-Y", strtotime($tanggal_laporan[0]['tanggal_spd'])).'.pdf');
	}
	
	public function cetak_rbpd2_dprd(){
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$data = $this->m_laporan->get_dprd($telaah_id);
		$data2 = $this->m_laporan->get_pengikut_dprd3($telaah_id, $this->input->get('pengikut_id'));
		$pptk = $this->m_pptk_pengeluaran_rill->get2($telaah_id);
		$tanggal_laporan = $this->m_laporan->tanggal_laporan($telaah_id);
		
		if($this->ion_auth->user()->row()->jenis_skpd == 2){
			$kepala_opd = $this->m_laporan->get_pimpinan_dprd($data[0]['skpd_id']);
			$sekwan = $this->m_laporan->get_sekwan();
		} else if($this->ion_auth->user()->row()->jenis_skpd == 3){
			$kepala_opd = $this->m_laporan->get_sekda($data[0]['skpd_id']);
		} else {
			$kepala_opd = $this->m_laporan->get_kepala_opd($data[0]['skpd_id']);
		}
		$bendahara = $this->m_laporan->get_bendahara($data[0]['skpd_id']);
		
        $pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(5, 3.175, 25);
        // membuat halaman baru
        $pdf->AddPage();
		//$pdf->Image(base_url()."admin/qr_generator?code=sppdkotakendari", 10, 270, 20, 20, "png");
        
		QRcode::png('http://sppd.kendarikota.go.id/qr/sppd?telaah_id='.$this->input->get('telaah_id'),"test.png");
		$pdf->Image("test.png", 185, 10, 20, 20, "png");
		
        $pdf->SetFont('Times','B',12);
		$pdf->Cell(10,15,'',0,1);
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->Cell(180,6,'BIAYA PERJALANAN DINAS',0,0,'C');
        $pdf->Cell(10,6,'',0,1,'C');
		
        $pdf->SetFont('Times','',10);
		$pdf->Cell(10,2,'',0,1);
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->Cell(180,6,'An. '.$data2[0]['anggotadprd_name'],0,0,'C');
        $pdf->Cell(10,6,'',0,1,'C');
		
		$pdf->Cell(10,4,'',0,1);
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->Cell(50,6,'Lampiran SPPD Nomor',0,0);
        $pdf->Cell(130,6,':',0,0);
        $pdf->Cell(10,6,'',0,1,'C');
		
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->Cell(50,6,'Tanggal',0,0);
        $pdf->Cell(130,6,': '.date("d-m-Y", strtotime($tanggal_laporan[0]['tanggal_spd'])),0,0);
        $pdf->Cell(10,6,'',0,1,'C');
		
        $pdf->Cell(10,4,'',0,1,'C');
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->Cell(7,6,'NO',1,0,'C');
        $pdf->Cell(100,6,'RINCIAN BELANJA',1,0,'C');
        $pdf->Cell(30,6,'JUMLAH',1,0,'C');
        $pdf->Cell(43,6,'KETERANGAN',1,1,'C');
		
		$pengeluaran_rill = $this->m_pengeluaran_rill->get_rincian($telaah_id,$data2[0]['anggotadprd_id']);
		
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
		
		
		$rincian = $this->m_rincian->get_rincian($telaah_id,$data2[0]['anggotadprd_id']);
		
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
		$pdf->Cell(7,6,'Rp.    '.number_format($total,0,",","."),0,0);
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
		//$pdf->Cell(7,6,$bendahara[0]['pegawai_nama'],0,0);
		$pdf->Cell(7,6,'',0,0);
		$pdf->Cell(100,6,'',0,0);
		$pdf->Cell(7,6,$data2[0]['anggotadprd_name'],0,0);
		$pdf->Cell(23,6,'',0,0,'R');
		$pdf->Cell(43,6,'',0,1,'C');
		$pdf->Cell(10,6,'',0,0,'C');
        $pdf->SetFont('Times','',10);
		//$pdf->Cell(7,6,'NIP. '.$bendahara[0]['pegawai_nip'],0,0);
		$pdf->Cell(7,6,'',0,0);
		$pdf->Cell(100,6,'',0,0);
		//$pdf->Cell(7,6,'NIP. '.$data[0]['pegawai_nip'],0,0);
		$pdf->Cell(7,6,'',0,0);
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
		
		$panjar = $this->m_kuitansi->get_kuitansi_panjar($telaah_id,$data2[0]['anggotadprd_id']);
		
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->Cell(50,6,'Yang telah dibayar semula',0,0);
        $pdf->Cell(130,6,': Rp.   '.number_format($panjar[0]['jumlah'],0,",","."),0,0);
        $pdf->Cell(10,6,'',0,1,'C');
		
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->Cell(50,6,'Sisa Kurang/Lebih',0,0);
        $pdf->Cell(130,6,': Rp.   '.number_format($total - $panjar[0]['jumlah'],0,",","."),0,0);
        $pdf->Cell(10,6,'',0,1,'C');
		
		$pdf->Cell(10,4,'',0,1);
		
        $pdf->Cell(10,6,'',0,0,'C');
        $pdf->SetFont('Times','I',10);
        $pdf->Cell(180,6,'Terbilang : '.$this->terbilang($total - $panjar[0]['jumlah']).' Rupiah',0,0,'C');
        $pdf->Cell(10,6,'',0,1,'C');
		
		$pdf->Cell(10,4,'',0,1);
		
        $pdf->SetFont('Times','',10);
		$pdf->Cell(10,6,'',0,0,'C');
		$pdf->Cell(7,6,'Setuju bayar:',0,0);
		$pdf->Cell(100,6,'',0,0);
		$pdf->Cell(7,6,'Mengetahui/Menyetujui',0,0);
		$pdf->Cell(23,6,'',0,0,'R');
		$pdf->Cell(43,6,'',0,1,'C');
		
		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
		
		$pdf->Cell(10,6,'',0,0,'C');
		$pdf->Cell(7,6,$sekwan[0]['pegawai_namajabatan'],0,0);
		$pdf->Cell(100,6,'',0,0);
		$pdf->Cell(7,6,'Pejabat Pelaksana Teknis Kegiatan',0,0);
		$pdf->Cell(23,6,'',0,0,'R');
		$pdf->Cell(43,6,'',0,1,'C');
		
        $pdf->Cell(10,15,'',0,1,'C');
		
		$pdf->Cell(10,6,'',0,0,'C');
        $pdf->SetFont('Times','BU',10);
		//$pdf-> Image('./upload/tanda_tangan/'.$sekwan[0]['tanda_tangan'],20,238,40,15);
		$pdf->Cell(7,6,$sekwan[0]['pegawai_nama'],0,0);
		$pdf->Cell(100,6,'',0,0);
		$pdf->Cell(7,6,$pptk[0]['pegawai_nama'],0,0);
		$pdf->Cell(23,6,'',0,0,'R');
		$pdf->Cell(43,6,'',0,1,'C');
		$pdf->Cell(10,6,'',0,0,'C');
        $pdf->SetFont('Times','',10);
		$pdf->Cell(7,6,'NIP. '.$sekwan[0]['pegawai_nip'],0,0);
		$pdf->Cell(100,6,'',0,0);
		$pdf->Cell(7,6,'NIP. '.$pptk[0]['pegawai_nip'],0,0);
		$pdf->Cell(7,6,'',0,0);
		$pdf->Cell(23,6,'',0,0,'R');
		$pdf->Cell(43,6,'',0,1,'C');
		
		
		$pdf->Output('D','RBPD - '.$data2[0]['anggotadprd_name'].' - '.date("d-m-Y", strtotime($tanggal_laporan[0]['tanggal_spd'])).'.pdf');
	}
	
	
	
	
	// PENGELUARAN RILL
	public function laporan_pengeluaran_rill_dprd(){
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$data = $this->m_laporan->get_dprd($telaah_id);
		$pptk = $this->m_pptk_pengeluaran_rill->get2($telaah_id);
		$tanggal_laporan = $this->m_laporan->tanggal_laporan($telaah_id);
		if($this->ion_auth->user()->row()->jenis_skpd == 2){
			$kepala_opd = $this->m_laporan->get_pimpinan_dprd($data[0]['skpd_id']);
			$sekwan = $this->m_laporan->get_sekwan();
		} else if($this->ion_auth->user()->row()->jenis_skpd == 3){
			$kepala_opd = $this->m_laporan->get_sekda($data[0]['skpd_id']);
		} else {
			$kepala_opd = $this->m_laporan->get_kepala_opd($data[0]['skpd_id']);
		}
		
        $pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(25, 3.175, 25);
        // membuat halaman baru
        $pdf->AddPage();
		//$pdf-> Image('./assets2/dist/img/kota_kendari.png',20,16,25,25);
		$pdf-> Image('./upload/kop_surat/'.$data[0]['kop_surat'],20,16,165,30);
		//$pdf->Image(base_url()."admin/qr_generator?code=sppdkotakendari", 10, 265, 20, 20, "png");
        
		QRcode::png('http://sppd.kendarikota.go.id/qr/sppd?telaah_id='.$this->input->get('telaah_id'),"test.png");
		$pdf->Image("test.png", 185, 10, 20, 20, "png");
		
		$pdf->Cell(10,20,'',0,1);
      
		
        // Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,7,'',0,1);
		
		$pdf->Cell(10,25,'',0,1);
		
        $pdf->SetFont('Times','B',14);
        $pdf->Cell(160,7,'DAFTAR PENGELUARAN RILL',0,1,'C');
		
		// Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(75,6,'Yang bertanda tangan dibawah ini :',0,0);
        $pdf->Cell(80,6,'',0,1);
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Nama',0,0);
        $pdf->Cell(3,6,':',0,0);
        $pdf->Cell(137,6,$data[0]['anggotadprd_name'],0,1);
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Jabatan',0,0);
        $pdf->Cell(3,6,':',0,0);
        $pdf->Cell(137,6,$data[0]['anggotadprd_jabatan'],0,1);
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','',10);
        $pdf->MultiCell(160, 5, 'Berdasarkan Surat Perintah Perjalanan Dinas (SPPD) tanggal '.date("d-m-Y", strtotime($tanggal_laporan[0]['tanggal_spd'])).' Nomor _______________________________ dengan ini kami menyatakan dengan sesungguhnya bahwa :', 0,'J');
		
		// Memberikan space kebawah agar tidak terlalu rapat
		
		$pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(7,6,'1. ',0,0);
        $pdf->MultiCell(153,6,'Biaya transport pegawai dibawah ini yang tidak dapat diperoleh bukti-bukti pengeluarannya, meliputi :',0,1);
		
		// Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','B',10);
        $pdf->Cell(7,6,'NO',1,0);
        $pdf->Cell(113,6,'URAIAN',1,0,'C');
        $pdf->Cell(40,6,'JUMLAH',1,0,'C');
        $pdf->Cell(15,6,'',0,1);
		
		$pdf->SetFont('Times','',10);
		$pengeluaran_rill = $this->m_pengeluaran_rill->get_rincian_dprd($telaah_id,$this->input->get('pegawai_id'));
		
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
		
		// Memberikan space kebawah agar tidak terlalu rapat
        
		$pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(7,6,'2. ',0,0);
        $pdf->MultiCell(153,6,'Jumlah uang tersebut pada angka 1 diatas benar-benar dikeluarkan untuk pelaksanaan perjalanan dinas dimaksud dan apabila dikemudian hari terdapat kelebihan atas pembayaran, kami bersedia untuk menyetorkan kelebihan tersebut ke Kas Daerah.',0,'J');
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','',10);
        $pdf->MultiCell(155, 5, 'Demikian pernyataan ini kami buat dengan sebenar-benarnya, untuk dipergunakan sebagaimana mestinya.', 0,1);
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(100,6,'Kendari,',0,1,'R');
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(80,6,'Menyetujui :',0,0,'C');
        $pdf->Cell(80,6,'',0,1,'R');
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(80,6,'Pejabat Pelaksana Teknis Kegiatan,',0,0,'C');
        $pdf->Cell(80,6,'Yang Melakukan Perjalanan Dinas',0,1,'C');
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,15,'',0,1);
        $pdf->SetFont('Times','BU',10);
        $pdf->Cell(80,6,$pptk[0]['pegawai_nama'],0,0,'C');
        $pdf->Cell(80,6,$data[0]['anggotadprd_name'],0,1,'C');
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(80,6,'NIP. '.$pptk[0]['pegawai_nip'],0,0,'C');
        $pdf->Cell(80,6,'',0,1,'C');
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(160,6,'Mengetahui :',0,1,'C');
		
        $pdf->SetFont('Times','',10);
		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
		
        $pdf->Cell(40,6,'',0,0,'C');
        $pdf->MultiCell(80,6,$sekwan[0]['pegawai_namajabatan'],0,'C');
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,15,'',0,1);
        $pdf->SetFont('Times','BU',10);
        $pdf->Cell(160,6,$sekwan[0]['pegawai_nama'],0,1,'C');
        $pdf->SetFont('Times','',10);
		//$pdf-> Image('./upload/tanda_tangan/'.$sekwan[0]['tanda_tangan'],85,227,40,15);
        $pdf->Cell(160,6,'NIP. '.$sekwan[0]['pegawai_nip'],0,1,'C');
		
		
		$pdf->Output('D','PENGELUARAN_RILL - '.$data[0]['anggotadprd_name'].' - '.date("d-m-Y", strtotime($tanggal_laporan[0]['tanggal_spd'])).'.pdf');
	}
	
	
	public function laporan_pengeluaran_rill2_dprd(){
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$data = $this->m_laporan->get_dprd($telaah_id);
		$data2 = $this->m_laporan->get_pengikut_dprd($telaah_id);
		$pptk = $this->m_pptk_pengeluaran_rill->get2($telaah_id);
		$tanggal_laporan = $this->m_laporan->tanggal_laporan($telaah_id);
		if($this->ion_auth->user()->row()->jenis_skpd == 2){
			$kepala_opd = $this->m_laporan->get_pimpinan_dprd($data[0]['skpd_id']);
			$sekwan = $this->m_laporan->get_sekwan();
		} else if($this->ion_auth->user()->row()->jenis_skpd == 3){
			$kepala_opd = $this->m_laporan->get_sekda($data[0]['skpd_id']);
		} else {
			$kepala_opd = $this->m_laporan->get_kepala_opd($data[0]['skpd_id']);
		}
		
        $pdf = new PDF_MC_Table('P','mm','legal');
		$pdf->SetMargins(25, 3.175, 25);
        // membuat halaman baru
        $pdf->AddPage();
		//$pdf-> Image('./assets2/dist/img/kota_kendari.png',20,16,25,25);
		$pdf-> Image('./upload/kop_surat/'.$data[0]['kop_surat'],20,16,165,30);
		//$pdf->Image(base_url()."admin/qr_generator?code=sppdkotakendari", 10, 265, 20, 20, "png");
        
		QRcode::png('http://sppd.kendarikota.go.id/qr/sppd?telaah_id='.$this->input->get('telaah_id'),"test.png");
		$pdf->Image("test.png", 185, 10, 20, 20, "png");
		
		$pdf->Cell(10,20,'',0,1);
      
		
        // Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,7,'',0,1);
        $pdf->SetFont('Times','B',14);
        $pdf->Cell(160,7,'DAFTAR PENGELUARAN RILL',0,1,'C');
		
		// Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(75,6,'Yang bertanda tangan dibawah ini :',0,0);
        $pdf->Cell(80,6,'',0,1);
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Nama',0,0);
        $pdf->Cell(3,6,':',0,0);
        $pdf->Cell(137,6,$data2[0]['anggotadprd_name'],0,1);
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(20,6,'Jabatan',0,0);
        $pdf->Cell(3,6,':',0,0);
        $pdf->Cell(137,6,$data2[0]['anggotadprd_jabatan'],0,1);
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','',10);
        $pdf->MultiCell(160, 5, 'Berdasarkan Surat Perintah Perjalanan Dinas (SPPD) tanggal '.date("d-m-Y", strtotime($tanggal_laporan[0]['tanggal_spd'])).' Nomor _______________________________ dengan ini kami menyatakan dengan sesungguhnya bahwa :', 0,'J');
		
		// Memberikan space kebawah agar tidak terlalu rapat
		
		$pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(7,6,'1. ',0,0);
        $pdf->MultiCell(153,6,'Biaya transport pegawai dibawah ini yang tidak dapat diperoleh bukti-bukti pengeluarannya, meliputi :',0,1);
		
		// Memberikan space kebawah agar tidak terlalu rapat
        $pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','B',10);
        $pdf->Cell(7,6,'NO',1,0);
        $pdf->Cell(113,6,'URAIAN',1,0,'C');
        $pdf->Cell(40,6,'JUMLAH',1,0,'C');
        $pdf->Cell(15,6,'',0,1);
		
		$pdf->SetFont('Times','',10);
		$pengeluaran_rill = $this->m_pengeluaran_rill->get_rincian($telaah_id,$data2[0]['anggotadprd_id']);
		
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
		
		// Memberikan space kebawah agar tidak terlalu rapat
        
		$pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(7,6,'2. ',0,0);
        $pdf->MultiCell(153,6,'Jumlah uang tersebut pada angka 1 diatas benar-benar dikeluarkan untuk pelaksanaan perjalanan dinas dimaksud dan apabila dikemudian hari terdapat kelebihan atas pembayaran, kami bersedia untuk menyetorkan kelebihan tersebut ke Kas Daerah.',0,'J');
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','',10);
        $pdf->MultiCell(155, 5, 'Demikian pernyataan ini kami buat dengan sebenar-benarnya, untuk dipergunakan sebagaimana mestinya.', 0,1);
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(100,6,'Kendari,',0,1,'R');
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(80,6,'Menyetujui :',0,0,'C');
        $pdf->Cell(80,6,'',0,1,'R');
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(80,6,'Pejabat Pelaksana Teknis Kegiatan,',0,0,'C');
        $pdf->Cell(80,6,'Yang Melakukan Perjalanan Dinas',0,1,'C');
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,15,'',0,1);
        $pdf->SetFont('Times','BU',10);
        $pdf->Cell(80,6,$pptk[0]['pegawai_nama'],0,0,'C');
        $pdf->Cell(80,6,$data2[0]['anggotadprd_name'],0,1,'C');
		
        $pdf->SetFont('Times','',10);
        $pdf->Cell(80,6,'NIP. '.$pptk[0]['pegawai_nip'],0,0,'C');
        $pdf->Cell(80,6,'',0,1,'C');
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,5,'',0,1);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(160,6,'Mengetahui :',0,1,'C');
		
        $pdf->SetFont('Times','',10);
		$skpd_nama = strtolower($data[0]['skpd_nama']);
		$skpd_nama2 = ucwords($skpd_nama);
		
        $pdf->Cell(40,6,'',0,0,'C');
        $pdf->MultiCell(80,6,$sekwan[0]['pegawai_namajabatan'],0,'C');
		
		// Memberikan space kebawah agar tidak terlalu rapat
		$pdf->Cell(10,15,'',0,1);
        $pdf->SetFont('Times','BU',10);
        $pdf->Cell(160,6,$sekwan[0]['pegawai_nama'],0,1,'C');
        $pdf->SetFont('Times','',10);
        $pdf->Cell(160,6,'NIP. '.$sekwan[0]['pegawai_nip'],0,1,'C');
		
		
		$pdf->Output('D','PENGELUARAN_RILL - '.$data2[0]['anggotadprd_name'].' - '.date("d-m-Y", strtotime($tanggal_laporan[0]['tanggal_spd'])).'.pdf');
	}
	
	
}