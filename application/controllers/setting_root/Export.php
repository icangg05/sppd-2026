<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Export extends public_Controller {
    public function __construct() {
        parent::__construct();
		error_reporting(0);
		$this->load->model('telaah/m_telaah');
		$this->load->model('telaah/m_verifikasi');
		$this->load->model('setting_admin/m_pegawai');
		$this->load->model('setting_root/m_export');
		$this->load->model('telaah/m_pengikut');
		$this->load->model('telaah/m_timeline');
		$this->load->model('laporan/m_rincian');
		$this->load->model('laporan/m_pengeluaran_rill');
		$this->load->model('laporan/m_pptk_pengeluaran_rill');
		$this->load->model('laporan/m_kuitansi');
		$this->load->model('telaah/m_relasi_sekda');
		$this->load->model('telaah/m_lokasi_tujuan');
		$this->load->model('setting/m_log');	
		$this->load->library('excel');	
		
		if((!$this->ion_auth->get_users_groups()->row()->id == 9) || (!$this->ion_auth->get_users_groups()->row()->id == 100))
		{
			redirect('login');
		}
    }
    
    //View All Data
	public function index()
	{
		$this->data['export'] = $this->m_export->get($this->ion_auth->user()->row()->skpd_id);
		
		$staff_sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
		$config = array ();
		$config ["base_url"] = base_url () . "setting_root/export/index";
		if($staff_sekda){
			$config ["total_rows"] = $this->m_pegawai->data_setda('','',$this->ion_auth->user()->row()->skpd_id, $staff_sekda[0]['bagian_id']);
		} else {
			$config ["total_rows"] = $this->m_pegawai->data('','',$this->ion_auth->user()->row()->skpd_id);
		}
		$config ["per_page"] = 25;
		$config ["uri_segment"] = 4;
		$choice = $config ["total_rows"] / $config ["per_page"];
		$config ["num_links"] = 5;
		
		$config ['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
		$config ['full_tag_close'] = '</ul>';
		$config ['first_link'] = 'First';
		$config ['last_link'] = 'Last';
		$config ['first_tag_open'] = '<li>';
		$config ['first_tag_close'] = '</li>';
		$config ['prev_link'] = 'Previous';
		$config ['prev_tag_open'] = '<li class="prev">';
		$config ['prev_tag_close'] = '</li>';
		$config ['next_link'] = 'Next';
		$config ['next_tag_open'] = '<li>';
		$config ['next_tag_close'] = '</li>';
		$config ['last_tag_open'] = '<li>';
		$config ['last_tag_close'] = '</li>';
		$config ['cur_tag_open'] = '<li class="active"><a href="#">';
		$config ['cur_tag_close'] = '</a></li>';
		$config ['num_tag_open'] = '<li>';
		$config ['num_tag_close'] = '</li>';
		
		if ($this->uri->segment ( 4 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 4 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 4 )) ? $this->uri->segment ( 4 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		if($staff_sekda){
			$this->data['pegawai'] = $this->m_pegawai->data_setda($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id, $staff_sekda[0]['bagian_id']);
		} else {
			$this->data['pegawai'] = $this->m_pegawai->data($config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
		}

		$this->render('setting_root/export/content');
	}

	//View Data Search
	public function search()
	{
		$this->data['export'] = $this->m_export->get($this->ion_auth->user()->row()->skpd_id);

		if($this->input->post('submit')){
			$column = $this->input->post('column');
			$query = $this->input->post('data');
			
			$option = array(
				'user_column'=>$column,
				'user_data'=>$query
				);
			$this->session->set_userdata($option);
		}else{
			$query = $this->uri->segment ( 4 );
			$column = $this->uri->segment ( 5 );
		}
		$staff_sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
		$config = array ();
		$config ["base_url"] = base_url () . "setting_root/export/search/".$query."/".$column;
		if($staff_sekda){
			$config ["total_rows"] = $this->m_pegawai->data_search_setda($column,$query,'','',$this->ion_auth->user()->row()->skpd_id, $staff_sekda[0]['bagian_id']);
		} else {
			$config ["total_rows"] = $this->m_pegawai->data_search($column,$query,'','',$this->ion_auth->user()->row()->skpd_id);
		}
		$config ["per_page"] = 25;
		$config ["uri_segment"] = 6;
		$choice = $config ["total_rows"] / $config ["per_page"];
		$config ["num_links"] = 5;
		
		$config ['full_tag_open'] = '<ul class="pagination pagination-sm no-margin pull-right">';
		$config ['full_tag_close'] = '</ul>';
		$config ['first_link'] = 'First';
		$config ['last_link'] = 'Last';
		$config ['first_tag_open'] = '<li>';
		$config ['first_tag_close'] = '</li>';
		$config ['prev_link'] = 'Previous';
		$config ['prev_tag_open'] = '<li class="prev">';
		$config ['prev_tag_close'] = '</li>';
		$config ['next_link'] = 'Next';
		$config ['next_tag_open'] = '<li>';
		$config ['next_tag_close'] = '</li>';
		$config ['last_tag_open'] = '<li>';
		$config ['last_tag_close'] = '</li>';
		$config ['cur_tag_open'] = '<li class="active"><a href="#">';
		$config ['cur_tag_close'] = '</a></li>';
		$config ['num_tag_open'] = '<li>';
		$config ['num_tag_close'] = '</li>';
		
		if ($this->uri->segment ( 6 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 6 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 6 )) ? $this->uri->segment ( 6 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		if($staff_sekda){
			$this->data['pegawai'] = $this->m_pegawai->data_search_setda($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id, $staff_sekda[0]['bagian_id']);
		} else {
			$this->data['pegawai'] = $this->m_pegawai->data_search($column,$query,$config ["per_page"], $page, $this->ion_auth->user()->row()->skpd_id);
		}
		$this->render('setting_root/export/content');
	}
	
	//View Update Data
	public function laporan_perjalanan()
	{
		$pegawai_id = $this->encrypt->decode(base64_decode($this->uri->segment(4)), $this->session->userdata('encrypt_key'));
		
		## Pagination
		$base_url = base_url () . "setting_root/export/laporan_perjalanan/".$this->uri->segment(4);
		$total_rows = count($this->m_export->telaah_pelaksana('', '',$pegawai_id));
		$per_page = 25;
		$uri_segment = 5;
		$page = ($this->uri->segment ( $uri_segment )) ? $this->uri->segment ( $uri_segment ) : 0;
		$paging = $this->paging->paginate_function($base_url,$total_rows,$per_page,$uri_segment);
		
		$this->data['number'] = $paging['number'];
		$this->data['links'] = $paging['links'];
		
		## Menampilkan Semua Data
		$this->data['verifikasi'] = $this->m_export->telaah_pelaksana($per_page, $page,$pegawai_id);
		$this->render('setting_root/export/telaah_pelaksana');
	}

	//View Data Search
	public function search_laporan_perjalanan()
	{
		
		$pegawai_id = $this->encrypt->decode(base64_decode($this->uri->segment(4)), $this->session->userdata('encrypt_key'));
		
		if($this->input->post('submit')){
			$column = 'telaah_perihal';
			$query = $this->input->post('data');
			
			$option = array(
				'user_column'=>$column,
				'user_data'=>$query
				);
			$this->session->set_userdata($option);

		}else{
			$query = str_replace("%20"," ",$this->uri->segment ( 5 ));
			$column = $this->uri->segment ( 6 );
		}
		
		## Pagination
		$base_url = base_url () . "setting_root/export/search_laporan_perjalanan/".$this->uri->segment(4)."/".$query."/".$column;
		$total_rows = count($this->m_export->search_telaah_pelaksana($column,$query,'','',$pegawai_id));
		$per_page = 25;
		$uri_segment = 7;
		$page = ($this->uri->segment ( $uri_segment )) ? $this->uri->segment ( $uri_segment ) : 0;
		$paging = $this->paging->paginate_function($base_url,$total_rows,$per_page,$uri_segment);
		
		$this->data['number'] = $paging['number'];
		$this->data['links'] = $paging['links'];
		
		## Menampilkan Semua Data
		$this->data['verifikasi'] = $this->m_export->search_telaah_pelaksana($column,$query,$per_page,$page,$pegawai_id);
			
		$this->render('setting_root/export/telaah_pelaksana');
	}

	#LAPORAN PERJALANAN DINAS LUAR DAERAH#
    function cetak_lpld() {	
		// $skpd_id = $this->encrypt->decode(base64_decode($this->input->get('skpd_id')), $this->session->userdata('encrypt_key'));
		$data = $this->m_export->get($this->ion_auth->user()->row()->skpd_id);
		
		#load PHPExcel library
		$this->excel->setActiveSheetIndex(0);
		#name the worksheet
		$this->excel->getActiveSheet()->setTitle('Laporan Perjalanan Luar Daerah');
		
		#STYLING
		$styleArray = array(
		  'borders' => array(
			'allborders' => array(
			  'style' => PHPExcel_Style_Border::BORDER_THIN,
			  'color' => array(
				'argb' => '0000'
			  )
			)
		  )
		);
		
		#Style Font
		$this->excel->getActiveSheet()->getStyle('A2:S8')->getFont()->setName('Times New Roman');
		$this->excel->getActiveSheet()->getStyle('A6:S8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A6:S8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		// $this->excel->getActiveSheet()->getStyle('A2:S8')->getAlignment()->setWrapText(true);
		$this->excel->getActiveSheet()->getStyle('A2:S8')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('A6:S8')->getFont()->setSize(10);
		$this->excel->getActiveSheet()->getStyle('A2:S4')->getFont()->setSize(14);
		
		#Set Report Header
		$this->excel->getActiveSheet()->mergeCells('A2:S2');
		$this->excel->getActiveSheet()->setCellValue('A2', 'LAPORAN REKAPITULASI PERJALANAN DINAS ');
		$this->excel->getActiveSheet()->mergeCells('A3:S3');
		$this->excel->getActiveSheet()->setCellValue('A3', 'PERJALANAN DINAS LUAR DAERAH');
		$this->excel->getActiveSheet()->mergeCells('A4:S4');
		$this->excel->getActiveSheet()->setCellValue('A4', 'TAHUN');
		
		//Set Column No
		$this->excel->getActiveSheet()->mergeCells('A6:A7');
		$this->excel->getActiveSheet()->setCellValue('A6', 'NO.');
		$this->excel->getActiveSheet()->setCellValue('A8', '1');
		
		//Set Column Data Pelaksana		
		$this->excel->getActiveSheet()->mergeCells('B6:E6');
		$this->excel->getActiveSheet()->setCellValue('B6', 'DATA PELAKSANA');
		$this->excel->getActiveSheet()->setCellValue('B7', 'NAMA');
		$this->excel->getActiveSheet()->setCellValue('B8', '2');
		$this->excel->getActiveSheet()->setCellValue('C7', 'JABATAN');
		$this->excel->getActiveSheet()->setCellValue('C8', '3');
		$this->excel->getActiveSheet()->setCellValue('D7', 'NIP');
		$this->excel->getActiveSheet()->setCellValue('D8', '4');
		$this->excel->getActiveSheet()->setCellValue('E7', 'GOL');
		$this->excel->getActiveSheet()->setCellValue('E8', '5');
		
		//Set Column Data Perjalanan		
		$this->excel->getActiveSheet()->mergeCells('F6:L6');
		$this->excel->getActiveSheet()->setCellValue('F6', 'DATA PERJALANAN');
		$this->excel->getActiveSheet()->setCellValue('F7', 'MAKSUD PERJALANAN DINAS');
		$this->excel->getActiveSheet()->setCellValue('F8', '6');
		$this->excel->getActiveSheet()->setCellValue('G7', 'TUJUAN (PROVINSI - KOTA/KAB)');
		$this->excel->getActiveSheet()->setCellValue('G8', '7');
		$this->excel->getActiveSheet()->setCellValue('H7', 'TANGGAL SPT');
		$this->excel->getActiveSheet()->setCellValue('H8', '8');
		$this->excel->getActiveSheet()->setCellValue('I7', 'TANGGAL SPPD');
		$this->excel->getActiveSheet()->setCellValue('I8', '9');
		$this->excel->getActiveSheet()->setCellValue('J7', 'TANGGAL BERANGKAT');
		$this->excel->getActiveSheet()->setCellValue('J8', '10');
		$this->excel->getActiveSheet()->setCellValue('K7', 'TANGGAL KEMBALI');
		$this->excel->getActiveSheet()->setCellValue('K8', '11');
		$this->excel->getActiveSheet()->setCellValue('L7', 'LAMA HARI');
		$this->excel->getActiveSheet()->setCellValue('L8', '12');
		
		//Set Column Biaya Perjalanan		
		$this->excel->getActiveSheet()->setCellValue('M6', 'BIAYA PERJALANAN');
		$this->excel->getActiveSheet()->setCellValue('M7', 'JUMLAH YANG DIBAYARKAN');
		$this->excel->getActiveSheet()->setCellValue('M8', '13');
		
		//Set Column Data Pengikut		
		$this->excel->getActiveSheet()->mergeCells('N6:R6');
		$this->excel->getActiveSheet()->setCellValue('N6', 'DATA PENGIKUT');
		$this->excel->getActiveSheet()->setCellValue('N7', 'NAMA PENGIKUT');
		$this->excel->getActiveSheet()->setCellValue('N8', '14');
		$this->excel->getActiveSheet()->setCellValue('O7', 'JABATAN');
		$this->excel->getActiveSheet()->setCellValue('O8', '15');
		$this->excel->getActiveSheet()->setCellValue('P7', 'NIP');
		$this->excel->getActiveSheet()->setCellValue('P8', '16');
		$this->excel->getActiveSheet()->setCellValue('Q7', 'GOL');
		$this->excel->getActiveSheet()->setCellValue('Q8', '17');
		$this->excel->getActiveSheet()->setCellValue('R7', 'JUMLAH YANG DIBAYARKAN');
		$this->excel->getActiveSheet()->setCellValue('R8', '18');

		//Set Column Total Biaya Perjalanan		
		$this->excel->getActiveSheet()->mergeCells('S6:S7');
		$this->excel->getActiveSheet()->setCellValue('S6', 'TOTAL BIAYA PERJALANAN');
		$this->excel->getActiveSheet()->setCellValue('S8', '19');
		
		#Lebar column
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(70);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(22);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(7);
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(90);
		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
		$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(14);
		$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(22);
		$this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(18);
		$this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(11);
		$this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(26);
		$this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(47);
		$this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(70);
		$this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(22);
		$this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(7);
		$this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(27);
		$this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(26);
		
		//TAMPILKAN DATA DARI DATABASE
		$no    = 9;
		$nomor = 1;
		foreach ($data as $v) {
			#A
			$this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('A' . $no, $nomor);
			
			#B
			$this->excel->getActiveSheet()->getStyle('B' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('B' . $no, $v->pegawai_nama);
			
			#C
			$this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->setCellValue('C' . $no, $v->pegawai_namajabatan);
			
			#D
			$this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('D' . $no, $v->pegawai_nip);
			
			#E
			$this->excel->getActiveSheet()->getStyle('E' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('E' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('E' . $no, $v->pegawai_golongan);
			
			#F
			$this->excel->getActiveSheet()->getStyle('F' . $no)->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->getStyle('F' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('F' . $no, $v->telaah_perihal);
			
			#G
			$this->excel->getActiveSheet()->getStyle('G' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('G' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('G' . $no, $v->provinsi);
			
			#H
			$this->excel->getActiveSheet()->getStyle('H' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('H' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('H' . $no, $v->tanggal_spt);
			
			#I
			$this->excel->getActiveSheet()->getStyle('I' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('I' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('I' . $no, $v->tanggal_spd);
			
			#J
			$this->excel->getActiveSheet()->getStyle('J' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('J' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('J' . $no, $v->telaah_tanggalberangkat);
			
			#K
			$this->excel->getActiveSheet()->getStyle('K' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('K' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('K' . $no, $v->telaah_tanggalkembali);
			
			#L
			$this->excel->getActiveSheet()->getStyle('L' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('L' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('L' . $no, $v->telaah_hari);
			
				/* $jumlah_dibayar = $this->m_export->get3($v->telaah_id, $this->input->get('pegawai_id'));
				foreach($jumlah_dibayar as $t){
					#M
					$this->excel->getActiveSheet()->getStyle('M' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$this->excel->getActiveSheet()->getStyle('M' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$this->excel->getActiveSheet()->setCellValue('M' . $no, $total);
				} */
			
				$pengikut = $this->m_export->pengikut($v->telaah_id);
				foreach ($pengikut as $p) {
					#N
					$this->excel->getActiveSheet()->getStyle('N' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$this->excel->getActiveSheet()->setCellValue('N' . $no, $p->pegawai_nama);
					
					#O
					$this->excel->getActiveSheet()->getStyle('O' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$this->excel->getActiveSheet()->getStyle('O' . $no)->getAlignment()->setWrapText(true);
					$this->excel->getActiveSheet()->setCellValue('O' . $no, $p->pegawai_namajabatan);
					
					#P
					$this->excel->getActiveSheet()->getStyle('P' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$this->excel->getActiveSheet()->getStyle('P' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$this->excel->getActiveSheet()->setCellValue('P' . $no, $p->pegawai_nip);
					
					#Q
					$this->excel->getActiveSheet()->getStyle('Q' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$this->excel->getActiveSheet()->getStyle('Q' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$this->excel->getActiveSheet()->setCellValue('Q' . $no, $p->pegawai_golongan);
					
					$no++;
				}	
				
			$no++;
			$nomor++;
		}
		
		//MASUKKAN DALAM TABEL
		$this->excel->getActiveSheet()->getStyle('A6:S8'.($no - 1))->applyFromArray($styleArray);
		
		#Proses Download
		ob_end_clean();
		$filename = 'Laporan Perjalanan Luar Daerah.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		$objWriter->save('php://output');
	   
		redirect('export');
    }
	
	#LAPORAN PERJALANAN DINAS DALAM DAERAH#
    function cetak_lpdd() {	
	// $skpd_id = $this->encrypt->decode(base64_decode($this->input->get('skpd_id')), $this->session->userdata('encrypt_key'));
		$data = $this->m_export->get5();
		
		#load PHPExcel library
		$this->excel->setActiveSheetIndex(0);
		#name the worksheet
		$this->excel->getActiveSheet()->setTitle('Laporan Perjalanan Dalam Daerah');
		
		#STYLING
		$styleArray = array(
		  'borders' => array(
			'allborders' => array(
			  'style' => PHPExcel_Style_Border::BORDER_THIN,
			  'color' => array(
				'argb' => '0000'
			  )
			)
		  )
		);
		
		#Style Font
		$this->excel->getActiveSheet()->getStyle('A2:S8')->getFont()->setName('Times New Roman');
		$this->excel->getActiveSheet()->getStyle('A6:S8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A6:S8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		// $this->excel->getActiveSheet()->getStyle('A2:S8')->getAlignment()->setWrapText(true);
		$this->excel->getActiveSheet()->getStyle('A2:S8')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('A6:S8')->getFont()->setSize(10);
		$this->excel->getActiveSheet()->getStyle('A2:S4')->getFont()->setSize(14);
		
		#Set Report Header
		$this->excel->getActiveSheet()->mergeCells('A2:S2');
		$this->excel->getActiveSheet()->setCellValue('A2', 'LAPORAN REKAPITULASI PERJALANAN DINAS ');
		$this->excel->getActiveSheet()->mergeCells('A3:S3');
		$this->excel->getActiveSheet()->setCellValue('A3', 'PERJALANAN DINAS DALAM DAERAH');
		$this->excel->getActiveSheet()->mergeCells('A4:S4');
		$this->excel->getActiveSheet()->setCellValue('A4', 'TAHUN');
		
		//Set Column No
		$this->excel->getActiveSheet()->mergeCells('A6:A7');
		$this->excel->getActiveSheet()->setCellValue('A6', 'NO.');
		$this->excel->getActiveSheet()->setCellValue('A8', '1');
		
		//Set Column Data Pelaksana		
		$this->excel->getActiveSheet()->mergeCells('B6:E6');
		$this->excel->getActiveSheet()->setCellValue('B6', 'DATA PELAKSANA');
		$this->excel->getActiveSheet()->setCellValue('B7', 'NAMA');
		$this->excel->getActiveSheet()->setCellValue('B8', '2');
		$this->excel->getActiveSheet()->setCellValue('C7', 'JABATAN');
		$this->excel->getActiveSheet()->setCellValue('C8', '3');
		$this->excel->getActiveSheet()->setCellValue('D7', 'NIP');
		$this->excel->getActiveSheet()->setCellValue('D8', '4');
		$this->excel->getActiveSheet()->setCellValue('E7', 'GOL');
		$this->excel->getActiveSheet()->setCellValue('E8', '5');
		
		//Set Column Data Perjalanan		
		$this->excel->getActiveSheet()->mergeCells('F6:L6');
		$this->excel->getActiveSheet()->setCellValue('F6', 'DATA PERJALANAN');
		$this->excel->getActiveSheet()->setCellValue('F7', 'MAKSUD PERJALANAN DINAS');
		$this->excel->getActiveSheet()->setCellValue('F8', '6');
		$this->excel->getActiveSheet()->setCellValue('G7', 'TUJUAN (PROVINSI - KOTA/KAB)');
		$this->excel->getActiveSheet()->setCellValue('G8', '7');
		$this->excel->getActiveSheet()->setCellValue('H7', 'TANGGAL SPT');
		$this->excel->getActiveSheet()->setCellValue('H8', '8');
		$this->excel->getActiveSheet()->setCellValue('I7', 'TANGGAL SPPD');
		$this->excel->getActiveSheet()->setCellValue('I8', '9');
		$this->excel->getActiveSheet()->setCellValue('J7', 'TANGGAL BERANGKAT');
		$this->excel->getActiveSheet()->setCellValue('J8', '10');
		$this->excel->getActiveSheet()->setCellValue('K7', 'TANGGAL KEMBALI');
		$this->excel->getActiveSheet()->setCellValue('K8', '11');
		$this->excel->getActiveSheet()->setCellValue('L7', 'LAMA HARI');
		$this->excel->getActiveSheet()->setCellValue('L8', '12');
		
		//Set Column Biaya Perjalanan		
		$this->excel->getActiveSheet()->setCellValue('M6', 'BIAYA PERJALANAN');
		$this->excel->getActiveSheet()->setCellValue('M7', 'JUMLAH YANG DIBAYARKAN');
		$this->excel->getActiveSheet()->setCellValue('M8', '13');
		
		//Set Column Data Pengikut		
		$this->excel->getActiveSheet()->mergeCells('N6:R6');
		$this->excel->getActiveSheet()->setCellValue('N6', 'DATA PENGIKUT');
		$this->excel->getActiveSheet()->setCellValue('N7', 'NAMA PENGIKUT');
		$this->excel->getActiveSheet()->setCellValue('N8', '14');
		$this->excel->getActiveSheet()->setCellValue('O7', 'JABATAN');
		$this->excel->getActiveSheet()->setCellValue('O8', '15');
		$this->excel->getActiveSheet()->setCellValue('P7', 'NIP');
		$this->excel->getActiveSheet()->setCellValue('P8', '16');
		$this->excel->getActiveSheet()->setCellValue('Q7', 'GOL');
		$this->excel->getActiveSheet()->setCellValue('Q8', '17');
		$this->excel->getActiveSheet()->setCellValue('R7', 'JUMLAH YANG DIBAYARKAN');
		$this->excel->getActiveSheet()->setCellValue('R8', '18');

		//Set Column Total Biaya Perjalanan		
		$this->excel->getActiveSheet()->mergeCells('S6:S7');
		$this->excel->getActiveSheet()->setCellValue('S6', 'TOTAL BIAYA PERJALANAN');
		$this->excel->getActiveSheet()->setCellValue('S8', '19');
		
		#Lebar column
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(70);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(22);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(7);
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(90);
		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
		$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(14);
		$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(22);
		$this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(18);
		$this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(11);
		$this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(26);
		$this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(47);
		$this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(70);
		$this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(22);
		$this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(7);
		$this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(27);
		$this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(26);
		
		//TAMPILKAN DATA DARI DATABASE
		$no    = 9;
		$nomor = 1;
		foreach ($data as $v) {
			#A
			$this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('A' . $no, $nomor);
			
			#B
			$this->excel->getActiveSheet()->getStyle('B' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('B' . $no, $v->pegawai_nama);
			
			#C
			$this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('C' . $no)->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->setCellValue('C' . $no, $v->pegawai_namajabatan);
			
			#D
			$this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('D' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('D' . $no, $v->pegawai_nip);
			
			#E
			$this->excel->getActiveSheet()->getStyle('E' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('E' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('E' . $no, $v->pegawai_golongan);
			
			#F
			$this->excel->getActiveSheet()->getStyle('F' . $no)->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->getStyle('F' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('F' . $no, $v->telaah_perihal);
			
			#G
			$this->excel->getActiveSheet()->getStyle('G' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('G' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('G' . $no, $v->provinsi);
			
			#H
			$this->excel->getActiveSheet()->getStyle('H' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('H' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('H' . $no, $v->tanggal_spt);
			
			#I
			$this->excel->getActiveSheet()->getStyle('I' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('I' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('I' . $no, $v->tanggal_spd);
			
			#J
			$this->excel->getActiveSheet()->getStyle('J' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('J' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('J' . $no, $v->telaah_tanggalberangkat);
			
			#K
			$this->excel->getActiveSheet()->getStyle('K' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('K' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('K' . $no, $v->telaah_tanggalkembali);
			
			#L
			$this->excel->getActiveSheet()->getStyle('L' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('L' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('L' . $no, $v->telaah_hari);
			
				$pengikut = $this->m_export->pengikut1($v->telaah_id);
				foreach ($pengikut as $p) {
					#N
					$this->excel->getActiveSheet()->getStyle('N' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$this->excel->getActiveSheet()->setCellValue('N' . $no, $p->pegawai_nama);
					
					#O
					$this->excel->getActiveSheet()->getStyle('O' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$this->excel->getActiveSheet()->getStyle('O' . $no)->getAlignment()->setWrapText(true);
					$this->excel->getActiveSheet()->setCellValue('O' . $no, $p->pegawai_namajabatan);
					
					#P
					$this->excel->getActiveSheet()->getStyle('P' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$this->excel->getActiveSheet()->getStyle('P' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$this->excel->getActiveSheet()->setCellValue('P' . $no, $p->pegawai_nip);
					
					#Q
					$this->excel->getActiveSheet()->getStyle('Q' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$this->excel->getActiveSheet()->getStyle('Q' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
					$this->excel->getActiveSheet()->setCellValue('Q' . $no, $p->pegawai_golongan);
					
					$no++;
				}	
				
			$no++;
			$nomor++;
		}
		
		//MASUKKAN DALAM TABEL
		$this->excel->getActiveSheet()->getStyle('A6:S8'.($no - 1))->applyFromArray($styleArray);
		
		#Proses Download
		ob_end_clean();
		$filename = 'Laporan Perjalanan Dalam Daerah.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		$objWriter->save('php://output');
	   
		redirect('export');
    }
	
	#Rekapan Semua perjalanan Dinas#
	function cetak_laporan() {	
	// $skpd_id = $this->encrypt->decode(base64_decode($this->input->get('skpd_id')), $this->session->userdata('encrypt_key'));
		$data = $this->m_export->get_laporan();
		
		#load PHPExcel library
		$this->excel->setActiveSheetIndex(0);
		#name the worksheet
		$this->excel->getActiveSheet()->setTitle('Rekap Laporan Perjalanan Dinas');
		
		#STYLING
		$styleArray = array(
		  'borders' => array(
			'allborders' => array(
			  'style' => PHPExcel_Style_Border::BORDER_THIN,
			  'color' => array(
				'argb' => '0000'
			  )
			)
		  )
		);
		
		#Style Font
		$this->excel->getActiveSheet()->getStyle('A1:AJ5')->getFont()->setName('Times New Roman');
		$this->excel->getActiveSheet()->getStyle('A1:AJ5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A1:AJ5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		// $this->excel->getActiveSheet()->getStyle('A1:AJ5')->getAlignment()->setWrapText(true);
		$this->excel->getActiveSheet()->getStyle('A1:AJ5')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('A1:AJ2')->getFont()->setSize(14);
		$this->excel->getActiveSheet()->getStyle('A3:AJ5')->getFont()->setSize(9);
		
		#set report header
		$this->excel->getActiveSheet()->mergeCells('A1:AJ1');
		$this->excel->getActiveSheet()->setCellValue('A1', 'DAFTAR REKAPITULASI PERJALANAN DINAS LUAR DAERAH');
		$this->excel->getActiveSheet()->mergeCells('A2:AJ2');
		$this->excel->getActiveSheet()->setCellValue('A2', 'BADAN PENGELOLA KEUANGAN DAN ASET DAERAH TA. 2019');
		
		//set column No
		$this->excel->getActiveSheet()->mergeCells('A3:A5');
		$this->excel->getActiveSheet()->setCellValue('A3', 'NO.');
		
		//set column Data Bukti
		$this->excel->getActiveSheet()->mergeCells('B3:C4');
		$this->excel->getActiveSheet()->setCellValue('B3', 'BUKTI');
		$this->excel->getActiveSheet()->setCellValue('B5', 'NOMOR');
		$this->excel->getActiveSheet()->setCellValue('C5', 'TANGGAL');
		
		//set column Kode Rekening
		$this->excel->getActiveSheet()->mergeCells('D3:D5');
		$this->excel->getActiveSheet()->setCellValue('D3', 'KODE REKENING');
		
		//set column Nama Non Gelar
		$this->excel->getActiveSheet()->mergeCells('E3:E5');
		$this->excel->getActiveSheet()->setCellValue('E3', 'NAMA (NON GELAR)');
		
		//set column Jabaran Esselon
		$this->excel->getActiveSheet()->mergeCells('F3:F5');
		$this->excel->getActiveSheet()->setCellValue('F3', 'JABATAN (ESSELON)');
		
		//set column NIP
		$this->excel->getActiveSheet()->mergeCells('G3:G5');
		$this->excel->getActiveSheet()->setCellValue('G3', 'NIP');
		
		//set column Keperluan Perjalanan
		$this->excel->getActiveSheet()->mergeCells('H3:H5');
		$this->excel->getActiveSheet()->setCellValue('H3', 'KEPERLUAN PERJALANAN');
		
		//set column Jumlah Dibayarkan
		$this->excel->getActiveSheet()->mergeCells('I3:I5');
		$this->excel->getActiveSheet()->setCellValue('I3', 'JUMLAH DIBAYARKAN');
		
		//set column Gol
		$this->excel->getActiveSheet()->mergeCells('J3:J5');
		$this->excel->getActiveSheet()->setCellValue('J3', 'GOL');
		
		//set column Tujuan
		$this->excel->getActiveSheet()->mergeCells('K3:K5');
		$this->excel->getActiveSheet()->setCellValue('K3', 'TUJUAN');
		
		//set column Data Sppd
		$this->excel->getActiveSheet()->mergeCells('L3:M3');
		$this->excel->getActiveSheet()->setCellValue('L3', 'SPPD');
		$this->excel->getActiveSheet()->mergeCells('L4:M4');
		$this->excel->getActiveSheet()->setCellValue('L4', 'TANGGAL');
		$this->excel->getActiveSheet()->setCellValue('L5', 'BERANGGAT');
		$this->excel->getActiveSheet()->setCellValue('M5', 'KEMBALI');
		
		//set column Data Rincian Biaya
		$this->excel->getActiveSheet()->mergeCells('N3:T3');
		$this->excel->getActiveSheet()->setCellValue('N3', 'RINCIAN BIAYA');
		$this->excel->getActiveSheet()->mergeCells('N4:N5');
		$this->excel->getActiveSheet()->setCellValue('N4', 'LAMA HARI');
		$this->excel->getActiveSheet()->mergeCells('O4:P4');
		$this->excel->getActiveSheet()->setCellValue('O4', 'UANG HARIAN');
		$this->excel->getActiveSheet()->setCellValue('O5', 'PERHARI');
		$this->excel->getActiveSheet()->setCellValue('P5', 'TOTAL');
		$this->excel->getActiveSheet()->mergeCells('Q4:T4');
		$this->excel->getActiveSheet()->setCellValue('Q4', 'BIAYA');
		$this->excel->getActiveSheet()->setCellValue('Q5', 'AKOMODASI');
		$this->excel->getActiveSheet()->setCellValue('R5', 'HOTEL');
		$this->excel->getActiveSheet()->setCellValue('S5', 'LAIN-LAIN/KONTRIBUSI');
		$this->excel->getActiveSheet()->setCellValue('T5', 'TIKET.PP');
		
		//set column Data Akomodasi/Tiket
		$this->excel->getActiveSheet()->mergeCells('U3:AE3');
		$this->excel->getActiveSheet()->setCellValue('U3', 'AKOMODASI / TIKET');
		$this->excel->getActiveSheet()->mergeCells('U4:U5');
		$this->excel->getActiveSheet()->setCellValue('U4', 'JUMLAH');
		$this->excel->getActiveSheet()->mergeCells('V4:V5');
		$this->excel->getActiveSheet()->setCellValue('V4', 'PENGINAPAN');
		$this->excel->getActiveSheet()->mergeCells('W4:W5');
		$this->excel->getActiveSheet()->setCellValue('W4', 'TUJUAN');
		$this->excel->getActiveSheet()->mergeCells('X4:AA4');
		$this->excel->getActiveSheet()->setCellValue('X4', 'BERANGKAT');
		$this->excel->getActiveSheet()->setCellValue('X5', 'TANGGAL');
		$this->excel->getActiveSheet()->setCellValue('Y5', 'PESAWAT');
		$this->excel->getActiveSheet()->setCellValue('Z5', 'NO. TIKET');
		$this->excel->getActiveSheet()->setCellValue('AA5', 'HARGA');
		$this->excel->getActiveSheet()->mergeCells('AB4:AE4');
		$this->excel->getActiveSheet()->setCellValue('AB4', 'KEMBALI');
		$this->excel->getActiveSheet()->setCellValue('AB5', 'TANGGAL');
		$this->excel->getActiveSheet()->setCellValue('AC5', 'PESAWAT');
		$this->excel->getActiveSheet()->setCellValue('AD5', 'NO. TIKET');
		$this->excel->getActiveSheet()->setCellValue('AE5', 'HARGA');
		
		//set column Data Pendukung
		$this->excel->getActiveSheet()->mergeCells('AF3:AJ3');
		$this->excel->getActiveSheet()->setCellValue('AF3', 'PENDUKUNG');
		$this->excel->getActiveSheet()->mergeCells('AF4:AF5');
		$this->excel->getActiveSheet()->setCellValue('AF4', 'PROPOSAL / UNDANGAN ');
		$this->excel->getActiveSheet()->mergeCells('AG4:AG5');
		$this->excel->getActiveSheet()->setCellValue('AG4', 'NO. SURAT ');
		$this->excel->getActiveSheet()->mergeCells('AH4:AH5');
		$this->excel->getActiveSheet()->setCellValue('AH4', 'SPPD');
		$this->excel->getActiveSheet()->mergeCells('AI4:AI5');
		$this->excel->getActiveSheet()->setCellValue('AI4', 'BOARDING PASS');
		$this->excel->getActiveSheet()->mergeCells('AJ4:AJ5');
		$this->excel->getActiveSheet()->setCellValue('AJ4', 'LAPORAN PERJADIN');
		
		#Lebar Column
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(11);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(16);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(28);
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(51);
		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(27);
		$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(80);
		$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(7);
		$this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(30);
		$this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(21);
		$this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('V')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('W')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('X')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('Y')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('Z')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('AA')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('AB')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('AC')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('AD')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('AE')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('AF')->setWidth(22);
		$this->excel->getActiveSheet()->getColumnDimension('AG')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('AH')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('AI')->setWidth(16);
		$this->excel->getActiveSheet()->getColumnDimension('AJ')->setWidth(70);
		
		//TAMPILKAN DATA DARI DATABASE
		$no    = 6;
		$nomor = 1;
		foreach ($data as $v) {
			#A
			$this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$this->excel->getActiveSheet()->getStyle('A' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('A' . $no, $nomor);
			
			#E
			$this->excel->getActiveSheet()->getStyle('E' . $no)->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->getStyle('E' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('E' . $no, $v->pegawai_nama);
			
			#F
			$this->excel->getActiveSheet()->getStyle('F' . $no)->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->getStyle('F' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('F' . $no, $v->pegawai_namajabatan);
			
			#G
			$this->excel->getActiveSheet()->getStyle('G' . $no)->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->getStyle('G' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('G' . $no, $v->pegawai_nip);
			
			#H
			$this->excel->getActiveSheet()->getStyle('H' . $no)->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->getStyle('H' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('H' . $no, $v->telaah_perihal);
			
			#J
			$this->excel->getActiveSheet()->getStyle('J' . $no)->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->getStyle('J' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('J' . $no, $v->pegawai_golongan);
			
			#K
			$this->excel->getActiveSheet()->getStyle('K' . $no)->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->getStyle('K' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('K' . $no, $v->provinsi);
			
			#L
			$this->excel->getActiveSheet()->getStyle('L' . $no)->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->getStyle('L' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('L' . $no, $v->telaah_tanggalberangkat);
			
			#M
			$this->excel->getActiveSheet()->getStyle('M' . $no)->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->getStyle('M' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('M' . $no, $v->telaah_tanggalkembali);
			
			#N
			$this->excel->getActiveSheet()->getStyle('N' . $no)->getAlignment()->setWrapText(true);
			$this->excel->getActiveSheet()->getStyle('N' . $no)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$this->excel->getActiveSheet()->setCellValue('N' . $no, $v->telaah_hari);
			
			$no++;
			$nomor++;
		}
		
		//MASUKKAN DALAM TABEL
		$this->excel->getActiveSheet()->getStyle('A3:AJ5'.($no - 1))->applyFromArray($styleArray);
		
		#Proses Download
		ob_end_clean();
		$filename = 'Rekap Laporan Perjalanan Dinas.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		$objWriter->save('php://output');
	   
		redirect('export');
    }
}