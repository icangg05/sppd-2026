<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Staff_dprd extends public_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('m_staff_dprd');
		$this->load->model('m_pengikut');
		$this->load->model('m_pegawai');
		$this->load->model('m_lokasi_tujuan');
		$this->load->model('m_timeline');
		$this->load->model('m_telaah');
		$this->load->model('setting/m_log');
		if(!($this->ion_auth->user()->row()->id)){
			redirect('login');
		}
	}
	//View All Data
	public function index()
	{
		$config = array ();
		$config ["base_url"] = base_url () . "staff_dprd/index";
		$config ["total_rows"] = $this->m_staff_dprd->record_count();
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
		$this->data['staff_dprd'] = $this->m_staff_dprd->data($config ["per_page"], $page);
		$this->render('list_telaah/staff_dprd/content');
	}
	
	//View Data Search
	public function search()
	{
		if($this->input->post('submit')){
			$column = 'pegawai_nama';
			$query = $this->input->post('data');
			
			$option = array(
				'user_column'=>$column,
				'user_data'=>$query
				);
			$this->session->set_userdata($option);
		}else{
			$query = $this->uri->segment ( 3 );
			$column = $this->uri->segment ( 4 );
		}
		
		$config = array ();
		$config ["base_url"] = base_url () . "staff_dprd/search/".$query."/".$column;
		$config ["total_rows"] = $this->m_staff_dprd->record_count_search($column,$query);
		$config ["per_page"] = 25;
		$config ["uri_segment"] = 5;
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
		
		if ($this->uri->segment ( 5 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 5 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 5 )) ? $this->uri->segment ( 5 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['staff_dprd'] = $this->m_staff_dprd->data_search($column,$query,$config ["per_page"], $page);
		$this->render('list_telaah/staff_dprd/content');
	}
	//View Data Result
	public function result()
	{
		$column = 'table_telaah.telaah_status';
		$query = $this->input->get('status');
		
		$config = array ();
		$config ["base_url"] = base_url () . "staff_dprd/search/".$query."/".$column;
		$config ["total_rows"] = $this->m_staff_dprd->record_count_search($column,$query);
		$config ["per_page"] = 25;
		$config ["uri_segment"] = 5;
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
		
		if ($this->uri->segment ( 5 ) == "") {
			$this->data ['number'] = 0;
		} else {
			$this->data ['number'] = $this->uri->segment ( 5 );
		}
		
		$this->pagination->initialize ( $config );
		$page = ($this->uri->segment ( 5 )) ? $this->uri->segment ( 5 ) : 0;
		
		$this->data['links'] = $this->pagination->create_links ();
		$this->data['staff_dprd'] = $this->m_staff_dprd->data_search($column,$query,$config ["per_page"], $page);
		$this->render('list_telaah/staff_dprd/content');
	}
	//View Create Data
	public function create_view()
	{
		$this->data['pegawai'] = $this->m_staff_dprd->pegawai($this->ion_auth->user()->row()->skpd_id);
		$this->data['anggaran'] = $this->m_staff_dprd->anggaran($this->ion_auth->user()->row()->skpd_id);
		$this->data['rekening'] = $this->m_staff_dprd->rekening($this->ion_auth->user()->row()->skpd_id);
		$this->data['provinsi'] = $this->m_staff_dprd->get_provinsi();
		$this->data['kabupaten'] = $this->m_staff_dprd->get_kabupaten();
		$this->data['posisi_kadprd'] = $this->m_staff_dprd->posisi_kadprd();
		$this->render('list_telaah/staff_dprd/insert');
	}
	
	//Create Data
	public function create()
	{
		$this->form_validation->set_rules('telaah_kepada', 'Kepada', 'required');
		$this->form_validation->set_rules('telaah_perihal', 'Perihal', 'required');
		$this->form_validation->set_rules('telaah_persoalan', 'Persoalan', 'required');
		$this->form_validation->set_rules('telaah_fakta', 'Fakta', 'required');
		$this->form_validation->set_rules('telaah_analisis', 'Analisis', 'required');
		$this->form_validation->set_rules('telaah_jenisangkutan', 'Jenis Angkutan', 'required');
		$this->form_validation->set_rules('telaah_angkutan', 'Angkutan', 'required');
		$this->form_validation->set_rules('telaah_tanggalberangkat', 'Tanggal Berangkat', 'required');
		$this->form_validation->set_rules('telaah_tanggalkembali', 'Tanggal Kembali', 'required');
		$this->form_validation->set_rules('telaah_hari', 'Lama Perjalanan (Hari)', 'required');
		$this->form_validation->set_rules('telaah_tempatberangkat', 'Tempat Berangkat', 'required');
		$this->form_validation->set_rules('telaah_kantortujuan', 'Kantor Tujuan', 'required');
		$this->form_validation->set_rules('telaah_domainperjalanan', 'Domain Perjalanan', 'required');
		$this->form_validation->set_rules('telaah_kegiatan', 'Kegiatan', 'required');
		$this->form_validation->set_rules('telaah_kategoriperjalanan', 'Kategori Perjalanan', 'required');
		$this->form_validation->set_rules('telaah_kecepatan', 'Telaah Kecepatan', 'required');
		$this->form_validation->set_rules('telaah_pelaksana', 'Pelaksana', 'required');
		$this->form_validation->set_rules('telaah_ttdspt', 'Tanda Tangan SPT', 'required');
		$this->form_validation->set_rules('telaah_ttdsppd', 'Tanda Tangan SPPD', 'required');
		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->create_view();
		} 
		else 
		{	
			$filename = $this->input->post('telaah_perihal');
			$config['upload_path'] = './upload/telaah/';
			$config['allowed_types'] = "gif|jpg|jpeg|png";
			$config['overwrite']="true";
			$config['max_size']="20000000";
			$config['max_width']="10000";
			$config['max_height']="10000";
			$config['file_name'] = ''.$filename;
			$this->upload->initialize($config);
			if(!$this->upload->do_upload()){
				
				$data['telaah_kepada'] = $this->input->post('telaah_kepada');
				$data['telaah_perihal'] = $this->input->post('telaah_perihal');
				$data['telaah_persoalan'] = $this->input->post('telaah_persoalan');
				$data['telaah_fakta'] = $this->input->post('telaah_fakta');
				$data['telaah_analisis'] = $this->input->post('telaah_analisis');
				$data['telaah_jenisangkutan'] = $this->input->post('telaah_jenisangkutan');
				$data['telaah_angkutan'] = $this->input->post('telaah_angkutan');
				$data['telaah_tanggalberangkat'] = $this->input->post('telaah_tanggalberangkat');
				$data['telaah_tanggalkembali'] = $this->input->post('telaah_tanggalkembali'); 
				$data['telaah_hari'] = str_replace(".", "", $this->input->post('telaah_hari')); 
				$data['telaah_tempatberangkat'] = $this->input->post('telaah_tempatberangkat'); 
				$data['telaah_domainperjalanan'] = $this->input->post('telaah_domainperjalanan');
				if($data['telaah_domainperjalanan']==1){
					$data['telaah_provinsitujuan'] = $this->input->post('telaah_provinsitujuan');
					$data['telaah_kotatujuan'] = $this->input->post('telaah_kotatujuan');
				} else if($data['telaah_domainperjalanan']==2){
					$data['telaah_provinsitujuan'] = 74;
					$data['telaah_kotatujuan'] = $this->input->post('telaah_kotatujuan2');
				} else if($data['telaah_domainperjalanan']==3){
					$data['telaah_provinsitujuan'] = 74;
					$data['telaah_kotatujuan'] = 7471;
				}
				
				$data['telaah_kantortujuan'] = $this->input->post('telaah_kantortujuan');
				$data['telaah_kegiatan'] = $this->input->post('telaah_kegiatan');
				$data['telaah_kategoriperjalanan'] = $this->input->post('telaah_kategoriperjalanan');
				$data['telaah_kecepatan'] = $this->input->post('telaah_kecepatan');
				$data['telaah_pelaksana'] = $this->input->post('telaah_pelaksana');
				$data['telaah_ttdspt'] = $this->input->post('telaah_ttdspt');
				$data['telaah_ttdsppd'] = $this->input->post('telaah_ttdsppd');
				$data['user_id'] = $this->ion_auth->user()->row()->user_id;
				$data['telaah_kategori'] = 6;
				$data['telaah_waktuinput'] = date("Y-m-d H:i:s");
				
				$this->m_staff_dprd->create($data);
				
				$data2['pegawai_id'] = $this->input->post('telaah_pelaksana');
				$data2['status'] = 1;
				$this->m_pegawai->update($data2);
				
				$last = $this->m_staff_dprd->getLast();
				foreach ($last as $l) {
					$last_id = $l->telaah_id;
				}
				$datas['telaah_id'] = $last_id;
				
				$this->m_timeline->create6($datas);
				
				$jumlah = count($this->input->post('telaah_pengikut'));
				
				for($i=0;$i<$jumlah;$i++) {
					$data3['telaah_id'] = $last_id;
					$data3['pegawai_id'] = $this->input->post('telaah_pengikut')[$i];
					$this->m_pengikut->create($data3);
					
					$data4['pegawai_id'] = $this->input->post('telaah_pengikut')[$i];
					$data4['status'] = 1;
					$this->m_pegawai->update($data4);
				}		
				
				$jml_lokasi_tujuan = count($this->input->post('telaah_provinsitujuan2'));
				for($i=0;$i<$jml_lokasi_tujuan;$i++){
					$data5['telaah_id'] = $last_id;
					$data5['provinsi_id'] = $this->input->post('telaah_provinsitujuan2')[$i];
					$data5['kabkot_id'] = $this->input->post('telaah_kotatujuan3')[$i];
					$this->m_lokasi_tujuan->create($data5);
				}
				
				$log['kode_log_action'] = "53";
				$log['action'] = "INSERT";
				$log['kode_log_action_table'] = "15";
				$log['action_table'] = "TELAAH STAFF";
				$this->m_log->create($log);
				
				$this->session->set_flashdata('notif','Data Telaah Staf Di Simpan !');
				redirect('list_telaah/staff_dprd');
			}else {
				
				$dat = $this->upload->data();
				
				$data['telaah_kepada'] = $this->input->post('telaah_kepada');
				$data['telaah_perihal'] = $this->input->post('telaah_perihal');
				$data['telaah_persoalan'] = $this->input->post('telaah_persoalan');
				$data['telaah_fakta'] = $this->input->post('telaah_fakta');
				$data['telaah_analisis'] = $this->input->post('telaah_analisis');
				$data['telaah_jenisangkutan'] = $this->input->post('telaah_jenisangkutan');
				$data['telaah_angkutan'] = $this->input->post('telaah_angkutan');
				$data['telaah_tanggalberangkat'] = $this->input->post('telaah_tanggalberangkat');
				$data['telaah_tanggalkembali'] = $this->input->post('telaah_tanggalkembali'); 
				$data['telaah_hari'] = str_replace(".", "", $this->input->post('telaah_hari')); 
				$data['telaah_tempatberangkat'] = $this->input->post('telaah_tempatberangkat'); 
				$data['telaah_domainperjalanan'] = $this->input->post('telaah_domainperjalanan');
				if($data['telaah_domainperjalanan']==1){
					$data['telaah_provinsitujuan'] = $this->input->post('telaah_provinsitujuan');
					$data['telaah_kotatujuan'] = $this->input->post('telaah_kotatujuan');
				} else if($data['telaah_domainperjalanan']==2){
					$data['telaah_provinsitujuan'] = 74;
					$data['telaah_kotatujuan'] = $this->input->post('telaah_kotatujuan2');
				} else if($data['telaah_domainperjalanan']==3){
					$data['telaah_provinsitujuan'] = 74;
					$data['telaah_kotatujuan'] = 7471;
				}
				
				$data['telaah_kantortujuan'] = $this->input->post('telaah_kantortujuan');
				$data['telaah_kegiatan'] = $this->input->post('telaah_kegiatan');
				$data['telaah_kategoriperjalanan'] = $this->input->post('telaah_kategoriperjalanan');
				$data['telaah_kecepatan'] = $this->input->post('telaah_kecepatan');
				$data['telaah_pelaksana'] = $this->input->post('telaah_pelaksana');
				$data['telaah_ttdspt'] = $this->input->post('telaah_ttdspt');
				$data['telaah_ttdsppd'] = $this->input->post('telaah_ttdsppd');
				$data['user_id'] = $this->ion_auth->user()->row()->user_id;
				$data['telaah_kategori'] = 6;
				$data['telaah_waktuinput'] = date("Y-m-d H:i:s");
				$data['telaah_dokumenpendukung'] = $dat['file_name'];
				
				$this->m_staff_dprd->create($data);
				
				$data2['pegawai_id'] = $this->input->post('telaah_pelaksana');
				$data2['status'] = 1;
				$this->m_pegawai->update($data2);
				
				$last = $this->m_staff_dprd->getLast();
				foreach ($last as $l) {
					$last_id = $l->telaah_id;
				}
				$datas['telaah_id'] = $last_id;
				
				$this->m_timeline->create6($datas);
				
				$jumlah = count($this->input->post('telaah_pengikut'));
				
				for($i=0;$i<$jumlah;$i++) {
					$data3['telaah_id'] = $last_id;
					$data3['pegawai_id'] = $this->input->post('telaah_pengikut')[$i];
					$this->m_pengikut->create($data3);
					
					$data4['pegawai_id'] = $this->input->post('telaah_pengikut')[$i];
					$data4['status'] = 1;
					$this->m_pegawai->update($data4);
				}		
				
				$jml_lokasi_tujuan = count($this->input->post('telaah_provinsitujuan2'));
				for($i=0;$i<$jml_lokasi_tujuan;$i++){
					$data5['telaah_id'] = $last_id;
					$data5['provinsi_id'] = $this->input->post('telaah_provinsitujuan2')[$i];
					$data5['kabkot_id'] = $this->input->post('telaah_kotatujuan3')[$i];
					$this->m_lokasi_tujuan->create($data5);
				}
				
				$log['kode_log_action'] = "53";
				$log['action'] = "INSERT";
				$log['kode_log_action_table'] = "15";
				$log['action_table'] = "TELAAH STAFF";
				$this->m_log->create($log);
				
				$this->session->set_flashdata('notif','Data Telaah Staf Di Simpan !');
				redirect('list_telaah/staff_dprd');
			}
			
		}
	}
	
	//View Detail Data
	public function detail()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		
		$this->data['entry'] =  $this->m_staff_dprd->get($telaah_id);
		if(!isset($this->data['entry'][0]) || $this->data['entry'][0] == ""){
			redirect('list_telaah/staff_dprd');
		} else {
			$this->data['pengikut'] =  $this->m_pengikut->data($telaah_id);
			$this->data['timeline'] =  $this->m_staff_dprd->getTimeline($telaah_id);
			$this->render('list_telaah/staff_dprd/detail');
		}
	}
	
	public function get(){
		$data ['get_kabkot']=$this->m_staff_dprd->get_kabkot($this->uri->segment(4));
		$this->load->view('kabkot',$data);
	}
	
	//View Laporan
	public function laporan()
	{
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$this->data['telaah_id'] =  $telaah_id;
		$this->data['data'] =  $this->m_telaah->get($telaah_id);
		$this->render('list_telaah/laporan');
	}
	public function cek_timeline(){
		$telaah_id = $this->encrypt->decode(base64_decode($this->input->get('telaah_id')), $this->session->userdata('encrypt_key'));
		$this->data['timeline']	=$this->m_staff_dprd->getTimeline($telaah_id);
		$this->data['data_telaah']		=$this->m_staff_dprd->get($telaah_id);
		$this->render('list_telaah/staff_dprd/cek_timeline');
	}
	public function update_timeline(){
		if($this->input->post('job')=='kabag'){
			$data['timeline_kabag_disposisi'] = $this->input->post('timeline_kabag_disposisi');
			$data['telaah_id'] 								= $this->input->post('telaah_id');
		}elseif($this->input->post('job')=='sekwan'){
			$data['timeline_sekwan_disposisi'] = $this->input->post('timeline_sekwan_disposisi');
			$data['telaah_id'] 								= $this->input->post('telaah_id');
		}
		$this->m_staff_dprd->update_timeline($data);
		
		$this->session->set_flashdata('notif','Data Timeline Berhasil Di Update !');
		$telaah_id = base64_encode($this->encrypt->encode($data['telaah_id'], $this->session->userdata('encrypt_key')));	
		redirect('list_telaah/staff_dprd/cek_timeline?telaah_id='.$telaah_id);
		
	}
	
	/*fungsi delete telaah semua dinas*/
	public function delete_telaah(){
		
		$arrayTable=array('table_telaah','table_timeline1','table_timeline2','table_timeline3','table_timeline4', 'table_timeline5','table_timeline6','table_timeline7','table_timeline8','table_timeline9','table_timeline10','table_pengikut','table_pengeluaran_rill', 'table_kuitansi_panjar','table_laporanperjalanan','table_lokasi_tujuan','table_rincian_biaya','table_tanggal_perjalanan');
		
		for($i=0;$i<count($arrayTable);$i++){
			$this->m_staff_dprd->delete_telaah($this->input->post('telaah_id'), $arrayTable[$i]);
		}

		
		$this->session->set_flashdata('notif','Data Telaah Staf Di Hapus !');
		redirect('list_telaah/'.$this->input->post('url'));

	}

}