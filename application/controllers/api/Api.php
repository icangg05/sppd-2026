<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller
{
    function __construct()
	{
		parent::__construct();
		error_reporting(0);
		$this->load->model('api/m_api');
		$this->load->model('telaah/m_telaah');
		$this->load->model('setting/m_log');
		$this->load->model('telaah/m_esselon');
		$this->load->model('telaah/m_kadis');
		$this->load->model('telaah/m_dprd');
		$this->load->model('telaah/m_staff_dprd');
		$this->load->model('telaah/m_sekda');
		$this->load->model('telaah/m_camat');
		$this->load->model('telaah/m_lurah');
		$this->load->model('telaah/m_staff_camat');
		$this->load->model('telaah/m_staff_lurah');
		$this->load->model('telaah/m_sekwan');
		$this->load->model('telaah/m_kapus');
		$this->load->model('setting_root/m_admin');
		$this->load->model('telaah/m_relasi_sekda');
		$this->load->model('telaah/m_walikota');
		$this->load->model('m_widget');
		$this->load->library('ion_auth');
    }

    public function index()
    {
        
	}
    function bulan($bln)
	{
		switch ($bln)
		{
			case 1:
				return "Januari";
				break;
			case 2:
				return "Februari";
				break;
			case 3:
				return "Maret";
				break;
			case 4:
				return "April";
				break;
			case 5:
				return "Mei";
				break;
			case 6:
				return "Juni";
				break;
			case 7:
				return "Juli";
				break;
			case 8:
				return "Agustus";
				break;
			case 9:
				return "September";
				break;
			case 10:
				return "Oktober";
				break;
			case 11:
				return "November";
				break;
			case 12:
				return "Desember";
				break;
		}
	}
	
	function date_indo($tgl)
	{
		$ubah = gmdate($tgl, time()+60*60*8);
		$pecah = explode("-",$ubah);
		$tanggal = $pecah[2];
		$bulan = $this->bulan($pecah[1]);
		$tahun = $pecah[0];
		return $tanggal.' '.$bulan.' '.$tahun;
	}
	
    public function telaah(){
        // required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);

        // Security Check: Only authenticated users
        if (!$this->ion_auth->logged_in()) {
             echo json_encode(['status' => 'error', 'message' => 'Unauthorized access. Please login first.']);
             return;
        }

        echo json_encode($this->m_api->allData());
    }

    public function countById( $id ){
        // required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
        if( $id != null )

            echo json_encode(   
                $this->m_api->countById($id)  
                ) ;
        else
        echo json_encode( 
            array(
                "harap masukkan id telaah"
            )
         ) ;   
    }

    public function countByStatus($id1, $id2){
        // required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
        if( $id1 != null OR $id2 != null)

            echo json_encode(   
                $this->m_api->countByStatus( $id1, $id2 )  
                ) ;
        else
        echo json_encode( 
            array(
                "harap masukkan id telaah"
            )
         ) ;   
    }


    public function dataLive(){
        // required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);

        // Security Check: Only authenticated users
        if (!$this->ion_auth->logged_in()) {
             echo json_encode(['status' => 'error', 'message' => 'Unauthorized access. Please login first.']);
             return;
        }
        
		echo json_encode($this->m_api->dataLive(date("Y-m-d")), JSON_PRETTY_PRINT) ;
        
    }

	### Login 
	
	public function login()
	{
		header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$data=array('username'	=>	$this->input->post('username'),
                    'password' 	=> 	$this->input->post('password'));
					  
		$result = $this->ion_auth->login($data['username'],$data['password']);
		if($result){
			echo json_encode($this->m_api->user($this->ion_auth->user()->row()->id));
			// $resultData = array('status' => true, 'message' => 'Login Berhasil');
		} else {
			// $resultData = array('status' => false, 'message' => 'Login Gagal');
			// echo json_encode($resultData);
			 echo json_encode( 
			 array(
			 )
		  ) ;   
		}
		
		 
	}
	
	## DAFTAR PERJALANAN OPD
    public function daftar_perjalanan($user_id){
        // required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$user = $this->m_api->user($user_id);
		
		$limit = $this->input->get('limit');
		$start = $this->input->get('start');
		$order_by = $this->input->get('order_by');
		$key = $this->input->get('key');
		
		$telaah = $this->m_api->daftar_perjalanan($order_by, $limit, $start, $user[0]['skpd_id'], $key);
		$resultData = array();
		foreach($telaah as $v){
				$rincian_belanja = $this->m_api->count_rincian_belanja($v->telaah_id);
				$pengeluaran_rill = $this->m_api->count_pengeluaran_rill($v->telaah_id);
				
				$rincian = $rincian_belanja + $pengeluaran_rill;
				$laporan_perjalanan = $this->m_api->count_laporan_perjalanan($v->telaah_id);
				
				if($rincian > 0){
					$status_rincian = "1";
					$hasil_rincian = "Sudah Realisasi";
				} else {
					$status_rincian = "0";
					$hasil_rincian = "Belum Realisasi";
				}
				
				if($laporan_perjalanan > 0){
					$status_laporan = "1";
					$hasil_laporan_perjalanan = "Sudah Upload laporan";
				} else {
					$status_laporan = "0";
					$hasil_laporan_perjalanan = "Belum Upload laporan";
				}
				
				$date = substr($v->telaah_waktuinput, 0, 10);
				$time = substr($v->telaah_waktuinput, 11, 19);
				$telaah_waktuinput =  $this->date_indo($date);
				
				$date2 = substr($v->telaah_tanggalberangkat, 0, 10);
				$telaah_tanggalberangkat =  $this->date_indo($date2);
				
				$date3 = substr($v->telaah_tanggalkembali, 0, 10);
				$telaah_tanggalkembali =  $this->date_indo($date3);
				
				$kota_tujuan = $this->m_api->get_kota_tujuan($v->telaah_kotatujuan);
				
				$resultData[] = array('telaah_id'=> $v->telaah_id,
									'telaah_perihal'=> $v->telaah_perihal,
									'telaah_waktuinput'=> $telaah_waktuinput.' '.$time,
									'user_id'=> $v->user_id,
									'telaah_kategori'=> $v->telaah_kategori,
									'pegawai_id'=> $v->pegawai_id,
									'pegawai_nip'=> $v->pegawai_nip,
									'pegawai_nama'=> $v->pegawai_nama,
									'pegawai_jabatan'=> $v->pegawai_jabatan,
									'pegawai_namajabatan'=> $v->pegawai_namajabatan,
									'skpd_nama'=> $v->skpd_nama,
									'telaah_tempatberangkat'=> $v->telaah_tempatberangkat,
									'telaah_tanggalberangkat'=> $telaah_tanggalberangkat,
									'telaah_tanggalkembali'=> $telaah_tanggalkembali,
									'telaah_kotatujuan'=> $kota_tujuan,
									'telaah_kantortujuan'=> $v->telaah_kantortujuan,
									'status_rincian' => $status_rincian, 
									'hasil_rincian' => $hasil_rincian, 
									'status_laporan' => $status_laporan, 
									'hasil_laporan_perjalanan' => $hasil_laporan_perjalanan
									);
				
			}
			
		echo json_encode($resultData, JSON_PRETTY_PRINT);
      
    }
	
	### TOTAL PERMOHONAN TELAAH STAFF
	public function jumlah_permohonan($user_id)
	{
		header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);

        // Security Check: Only authenticated users
        if (!$this->ion_auth->logged_in()) {
             echo json_encode(['status' => 'error', 'message' => 'Unauthorized access. Please login first.']);
             return;
        }
		
		$user = $this->m_api->user($user_id);
		
		## 1 (Kasubid / kasubag) (OPD)
		if($user[0]['group_id'] == 1 && $user[0]['jenis_skpd'] == 1){
			
			echo json_encode($this->m_esselon->total_list_telaah($user[0]['skpd_id']));
			echo json_encode($this->m_esselon->total_list_telaah_diproses($user[0]['skpd_id']));
			echo json_encode($this->m_esselon->total_list_telaah_diterima($user[0]['skpd_id']));
			echo json_encode($this->m_esselon->total_list_telaah_ditolak($user[0]['skpd_id']));
			echo json_encode($this->m_kadis->total_list_telaah($user[0]['skpd_id']));
			echo json_encode($this->m_kadis->total_list_telaah_diproses($user[0]['skpd_id']));
			echo json_encode($this->m_kadis->total_list_telaah_diterima($user[0]['skpd_id']));
			echo json_encode($this->m_kadis->total_list_telaah_ditolak($user[0]['skpd_id']));
			
		## 1 (Kasubid / kasubag) (Puskesmas)	
		} else if($user[0]['group_id'] == 1 && $user[0]['jenis_skpd'] == 7){
			
			echo json_encode($this->m_kapus->total_list_telaah($user[0]['skpd_id']));
			echo json_encode($this->m_kapus->total_list_telaah_diproses($user[0]['skpd_id']));
			echo json_encode($this->m_kapus->total_list_telaah_diterima($user[0]['skpd_id']));
			echo json_encode($this->m_kapus->total_list_telaah_ditolak($user[0]['skpd_id']));
			
			echo json_encode($this->m_esselon->total_list_telaah($user[0]['skpd_id']));
			echo json_encode($this->m_esselon->total_list_telaah_diproses($user[0]['skpd_id']));
			echo json_encode($this->m_esselon->total_list_telaah_diterima($user[0]['skpd_id']));
			echo json_encode($this->m_esselon->total_list_telaah_ditolak($user[0]['skpd_id']));
		
		## 1 (Kasubid / kasubag) (Dinkes)		
		} else if($user[0]['group_id']  == 1 && $user[0]['jenis_skpd'] == 10){
			echo json_encode($this->m_esselon->total_list_telaah_dinkes());
			echo json_encode($this->m_esselon->total_list_telaah_diproses_dinkes());
			echo json_encode($this->m_esselon->total_list_telaah_diterima_dinkes());
			echo json_encode($this->m_esselon->total_list_telaah_ditolak_dinkes());
			
			echo json_encode($this->m_kadis->total_list_telaah($user[0]['skpd_id']));
			echo json_encode($this->m_kadis->total_list_telaah_diproses($user[0]['skpd_id']));
			echo json_encode($this->m_kadis->total_list_telaah_diterima($user[0]['skpd_id']));
			echo json_encode($this->m_kadis->total_list_telaah_ditolak($user[0]['skpd_id']));
		
		## 1 (Kasubid / kasubag) (DPRD)	
		} else if($user[0]['group_id'] == 1 && $user[0]['jenis_skpd'] == 2){
			echo json_encode($this->m_dprd->total_list_telaah());
			echo json_encode($this->m_dprd->total_list_telaah_diproses());
			echo json_encode($this->m_dprd->total_list_telaah_diterima());
			echo json_encode($this->m_dprd->total_list_telaah_ditolak());
			
			echo json_encode($this->m_staff_dprd->total_list_telaah());
			echo json_encode($this->m_staff_dprd->total_list_telaah_diproses());
			echo json_encode($this->m_staff_dprd->total_list_telaah_diterima());
			echo json_encode($this->m_staff_dprd->total_list_telaah_ditolak());
		
		## 1 (Kasubid / kasubag) (Sekda)
		} else if($user[0]['group_id'] == 1 && $user[0]['jenis_skpd'] == 3){
			echo json_encode($this->m_sekda->total_list_telaah_sekda($sekda[0]['subbagian_id']));
			echo json_encode($this->m_sekda->total_list_telaah_diproses($sekda[0]['subbagian_id']));
			echo json_encode($this->m_sekda->total_list_telaah_diterima_sekda($sekda[0]['subbagian_id']));
			echo json_encode($this->m_sekda->total_list_telaah_ditolak_sekda($sekda[0]['subbagian_id']));
			
		## 1 (Kasubid / kasubag) (Camat)
		} else if($user[0]['group_id'] == 1 && $user[0]['jenis_skpd'] == 4){
			
			echo json_encode($this->m_camat->total_list_telaah($user[0]['skpd_id']));
			echo json_encode($this->m_camat->total_list_telaah_diproses($user[0]['skpd_id']));
			echo json_encode($this->m_camat->total_list_telaah_diterima($user[0]['skpd_id']));
			echo json_encode($this->m_camat->total_list_telaah_ditolak($user[0]['skpd_id']));
			
		## 5 (Lurah)
		} else if($user[0]['group_id'] == 13 && $user[0]['jenis_skpd'] == 5){
			
			echo json_encode($this->m_lurah->total_list_telaah($user[0]['skpd_id']));
			echo json_encode($this->m_lurah->total_list_telaah_diproses($user[0]['skpd_id']));
			echo json_encode($this->m_lurah->total_list_telaah_diterima($user[0]['skpd_id']));
			echo json_encode($this->m_lurah->total_list_telaah_ditolak($user[0]['skpd_id']));
			
		## 14 (Bendahara Camat) (Staff Camat)
		} else if($user[0]['group_id'] == 14 && $user[0]['jenis_skpd'] == 4){
			
			echo json_encode($this->m_staff_camat->total_list_telaah($user[0]['skpd_id']));
			echo json_encode($this->m_staff_camat->total_list_telaah_diproses($user[0]['skpd_id']));
			echo json_encode($this->m_staff_camat->total_list_telaah_diterima($user[0]['skpd_id']));
			echo json_encode($this->m_staff_camat->total_list_telaah_ditolak($user[0]['skpd_id']));
			
		## 14 (Bendahara Lurah) (Staff Lurah)
		} else if($user[0]['group_id'] == 15 && $user[0]['jenis_skpd'] == 5){
			
			echo json_encode($this->m_staff_lurah->total_list_telaah($user[0]['skpd_id']));
			echo json_encode($this->m_staff_lurah->total_list_telaah_diproses($user[0]['skpd_id']));
			echo json_encode($this->m_staff_lurah->total_list_telaah_diterima($user[0]['skpd_id']));
			echo json_encode($this->m_staff_lurah->total_list_telaah_ditolak($user[0]['skpd_id']));
			
		## 9 (Admin OPD)
		} else if($user[0]['group_id'] == 9 && $user[0]['jenis_skpd']==1){
			
			echo json_encode($this->m_esselon->total_list_telaah($user[0]['skpd_id']));
			echo json_encode($this->m_esselon->total_list_telaah_diproses($user[0]['skpd_id']));
			echo json_encode($this->m_esselon->total_list_telaah_diterima($user[0]['skpd_id']));
			echo json_encode($this->m_esselon->total_list_telaah_ditolak($user[0]['skpd_id']));
			echo json_encode($this->m_kadis->total_list_telaah($user[0]['skpd_id']));
			echo json_encode($this->m_kadis->total_list_telaah_diproses($user[0]['skpd_id']));
			echo json_encode($this->m_kadis->total_list_telaah_diterima($user[0]['skpd_id']));
			echo json_encode($this->m_kadis->total_list_telaah_ditolak($user[0]['skpd_id']));
			
		## 9 (Admin Dinkes)
		} else if($user[0]['group_id'] == 9 && $user[0]['jenis_skpd']==10){
			
			echo json_encode($this->m_esselon->total_list_telaah_dinkes());
			echo json_encode($this->m_esselon->total_list_telaah_diproses_dinkes());
			echo json_encode($this->m_esselon->total_list_telaah_diterima_dinkes());
			echo json_encode($this->m_esselon->total_list_telaah_ditolak_dinkes());
			
			echo json_encode($this->m_kadis->total_list_telaah($user[0]['skpd_id']));
			echo json_encode($this->m_kadis->total_list_telaah_diproses($user[0]['skpd_id']));
			echo json_encode($this->m_kadis->total_list_telaah_diterima($user[0]['skpd_id']));
			echo json_encode($this->m_kadis->total_list_telaah_ditolak($user[0]['skpd_id']));
			
		## 9 (Admin OPD)(DPRD)
		} else if ($user[0]['group_id'] == 9 && $user[0]['jenis_skpd']==2){
			echo json_encode($this->m_dprd->total_list_telaah());
			echo json_encode($this->m_dprd->total_list_telaah_diproses());
			echo json_encode($this->m_dprd->total_list_telaah_diterima());
			echo json_encode($this->m_dprd->total_list_telaah_ditolak());
			
			echo json_encode($this->m_staff_dprd->total_list_telaah());
			echo json_encode($this->m_staff_dprd->total_list_telaah_diproses());
			echo json_encode($this->m_staff_dprd->total_list_telaah_diterima());
			echo json_encode($this->m_staff_dprd->total_list_telaah_ditolak());
		
		## 9 (Admin OPD)(Sekda)
		} else if ($user[0]['group_id'] == 9 && $user[0]['jenis_skpd']==3){
			echo json_encode($this->m_sekda->total_list_telaah());
			echo json_encode($this->m_sekda->total_list_telaah_diproses());
			echo json_encode($this->m_sekda->total_list_telaah_diterima());
			echo json_encode($this->m_sekda->total_list_telaah_ditolak());
			
		## 9 (Admin OPD)(Kecamatan)
		} else if ($user[0]['group_id'] == 9 && $user[0]['jenis_skpd']==4){
			
			echo json_encode($this->m_camat->total_list_telaah($user[0]['skpd_id']));
			echo json_encode($this->m_camat->total_list_telaah_diproses($user[0]['skpd_id']));
			echo json_encode($this->m_camat->total_list_telaah_diterima($user[0]['skpd_id']));
			echo json_encode($this->m_camat->total_list_telaah_ditolak($user[0]['skpd_id']));
			
			echo json_encode($this->m_staff_camat->total_list_telaah($user[0]['skpd_id']));
			echo json_encode($this->m_staff_camat->total_list_telaah_diproses($user[0]['skpd_id']));
			echo json_encode($this->m_staff_camat->total_list_telaah_diterima($user[0]['skpd_id']));
			echo json_encode($this->m_staff_camat->total_list_telaah_ditolak($user[0]['skpd_id']));
			
		## 9 (Admin OPD)(Kelurahan)
		} else if ($user[0]['group_id'] == 9 && $user[0]['jenis_skpd']==5){
			
			echo json_encode($this->m_lurah->total_list_telaah($user[0]['skpd_id']));
			echo json_encode($this->m_lurah->total_list_telaah_diproses($user[0]['skpd_id']));
			echo json_encode($this->m_lurah->total_list_telaah_diterima($user[0]['skpd_id']));
			echo json_encode($this->m_lurah->total_list_telaah_ditolak($user[0]['skpd_id']));
			
			echo json_encode($this->m_staff_lurah->total_list_telaah($user[0]['skpd_id']));
			echo json_encode($this->m_staff_lurah->total_list_telaah_diproses($user[0]['skpd_id']));
			echo json_encode($this->m_staff_lurah->total_list_telaah_diterima($user[0]['skpd_id']));
			echo json_encode($this->m_staff_lurah->total_list_telaah_ditolak($user[0]['skpd_id']));
			
		## 9 (Admin OPD)(Puskesmas)
		} else if ($user[0]['group_id'] == 9 && $user[0]['jenis_skpd']==7){
			
			echo json_encode($this->m_kapus->total_list_telaah($user[0]['skpd_id']));
			echo json_encode($this->m_kapus->total_list_telaah_diproses($user[0]['skpd_id']));
			echo json_encode($this->m_kapus->total_list_telaah_diterima($user[0]['skpd_id']));
			echo json_encode($this->m_kapus->total_list_telaah_ditolak($user[0]['skpd_id']));
			
			echo json_encode($this->m_esselon->total_list_telaah($user[0]['skpd_id']));
			echo json_encode($this->m_esselon->total_list_telaah_diproses($user[0]['skpd_id']));
			echo json_encode($this->m_esselon->total_list_telaah_diterima($user[0]['skpd_id']));
			echo json_encode($this->m_esselon->total_list_telaah_ditolak($user[0]['skpd_id']));
		
		## 2 (Kabid / Kabag)
		} else if($user[0]['group_id'] == 2 && $user[0]['jenis_skpd'] != 2 
						&& $user[0]['jenis_skpd'] != 3 && $user[0]['jenis_skpd'] != 10){
			
			$total = $this->m_esselon->total_list_telaah2($user[0]['skpd_id']);
			$diterima = $this->m_esselon->total_list_telaah_diterima2($user[0]['skpd_id']);
			$ditolak = $this->m_esselon->total_list_telaah_ditolak2($user[0]['skpd_id']);
			
			$resultData = array('total_list_telaah' => $total[0]['total_list_telaah'], 
								'total_list_telaah_diterima' => $diterima[0]['total_list_telaah_diterima'],
								'total_list_telaah_ditolak' => $ditolak[0]['total_list_telaah_ditolak'],
								);
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
						
		## 2 (Kabid / Kabag) (Dinkes)
		} else if($user[0]['group_id'] == 2 && $user[0]['jenis_skpd'] == 10){
			
			$total = $this->m_esselon->total_list_telaah16($user[0]['skpd_id']);
			$diterima = $this->m_esselon->total_list_telaah_diterima16($user[0]['skpd_id']);
			$ditolak = $this->m_esselon->total_list_telaah_ditolak16($user[0]['skpd_id']);
			
			$resultData[] = array('total_list_telaah' => $total[0]['total_list_telaah'], 
								'total_list_telaah_diterima' => $diterima[0]['total_list_telaah_diterima'],
								'total_list_telaah_ditolak' => $ditolak[0]['total_list_telaah_ditolak'],
								);
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
						
		## 2 (Kabid / Kabag) (DPRD)
		} else if($user[0]['group_id'] == 2 && $user[0]['jenis_skpd'] == 2){
			
			$total = $this->m_esselon->total_list_telaah7($user[0]['skpd_id']);
			$diterima = $this->m_esselon->total_list_telaah_diterima7($user[0]['skpd_id']);
			$ditolak = $this->m_esselon->total_list_telaah_ditolak7($user[0]['skpd_id']);
			
			$resultData[] = array('total_list_telaah' => $total[0]['total_list_telaah'], 
								'total_list_telaah_diterima' => $diterima[0]['total_list_telaah_diterima'],
								'total_list_telaah_ditolak' => $ditolak[0]['total_list_telaah_ditolak'],
								);
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
			
		## 2 (Kabid / Kabag) (Sekda)
		} else if($user[0]['group_id'] == 2 && $user[0]['jenis_skpd'] == 3){
			$sekda = $this->m_relasi_sekda->getkabag($this->ion_auth->user()->row()->id);
			
			$total = $this->m_esselon->total_list_telaah10($sekda[0]['bagian_id']);
			$diterima = $this->m_esselon->total_list_telaah_diterima10($sekda[0]['bagian_id']);
			$ditolak = $this->m_esselon->total_list_telaah_ditolak10($sekda[0]['bagian_id']);
			
			$resultData[] = array('total_list_telaah' => $total[0]['total_list_telaah'], 
								'total_list_telaah_diterima' => $diterima[0]['total_list_telaah_diterima'],
								'total_list_telaah_ditolak' => $ditolak[0]['total_list_telaah_ditolak'],
								);
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
				
		## 3 (Sekretaris OPD)
		} else if($user[0]['group_id'] == 3 && $user[0]['jenis_skpd'] != 10){
			
			$total = $this->m_esselon->total_list_telaah3($user[0]['skpd_id']);
			$diterima = $this->m_esselon->total_list_telaah_diterima3($user[0]['skpd_id']);
			$ditolak = $this->m_esselon->total_list_telaah_ditolak3($user[0]['skpd_id']);
			
			$resultData[] = array('total_list_telaah' => $total[0]['total_list_telaah'], 
								'total_list_telaah_diterima' => $diterima[0]['total_list_telaah_diterima'],
								'total_list_telaah_ditolak' => $ditolak[0]['total_list_telaah_ditolak'],
								);
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
			
		## 3 (Sekretaris Dinkes)
		} else if($user[0]['group_id'] == 3 && $user[0]['jenis_skpd'] == 10){
			
			$total = $this->m_esselon->total_list_telaah15($user[0]['skpd_id']);
			$diterima = $this->m_esselon->total_list_telaah_diterima15($user[0]['skpd_id']);
			$ditolak = $this->m_esselon->total_list_telaah_ditolak15($user[0]['skpd_id']);
			
			$resultData[] = array('total_list_telaah' => $total[0]['total_list_telaah'], 
								'total_list_telaah_diterima' => $diterima[0]['total_list_telaah_diterima'],
								'total_list_telaah_ditolak' => $ditolak[0]['total_list_telaah_ditolak'],
								);
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
			
		## 4 (Kepala OPD)
		} else if($user[0]['group_id'] == 4 && $user[0]['jenis_skpd'] != 2 && $user[0]['jenis_skpd'] != 10){
			
			$total = $this->m_esselon->total_list_telaah4($user[0]['skpd_id']);
			$diterima = $this->m_esselon->total_list_telaah_diterima4($user[0]['skpd_id']);
			$ditolak = $this->m_esselon->total_list_telaah_ditolak4($user[0]['skpd_id']);
			
			$resultData[] = array('total_list_telaah' => $total[0]['total_list_telaah'], 
								'total_list_telaah_diterima' => $diterima[0]['total_list_telaah_diterima'],
								'total_list_telaah_ditolak' => $ditolak[0]['total_list_telaah_ditolak'],
								);
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
			
		## 4 (Kepala Dinkes)
		} else if($user[0]['group_id'] == 4 && $user[0]['jenis_skpd'] == 10){
			
			$total = $this->m_esselon->total_list_telaah14($user[0]['skpd_id']);
			$diterima = $this->m_esselon->total_list_telaah_diterima14($user[0]['skpd_id']);
			$ditolak = $this->m_esselon->total_list_telaah_ditolak14($user[0]['skpd_id']);
			
			$resultData[] = array('total_list_telaah' => $total[0]['total_list_telaah'], 
								'total_list_telaah_diterima' => $diterima[0]['total_list_telaah_diterima'],
								'total_list_telaah_ditolak' => $ditolak[0]['total_list_telaah_ditolak'],
								);
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
			
		## 10 (Sekwan) (DPRD)
		} else if($user[0]['group_id'] == 10 && $user[0]['jenis_skpd'] == 2){
			
			$total = $this->m_esselon->total_list_telaah8($user[0]['skpd_id']);
			$diterima = $this->m_esselon->total_list_telaah_diterima8($user[0]['skpd_id']);
			$ditolak = $this->m_esselon->total_list_telaah_ditolak8($user[0]['skpd_id']);
			
			$resultData[] = array('total_list_telaah' => $total[0]['total_list_telaah'], 
								'total_list_telaah_diterima' => $diterima[0]['total_list_telaah_diterima'],
								'total_list_telaah_ditolak' => $ditolak[0]['total_list_telaah_ditolak'],
								);
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
			
		## 5 (Asisten)
		} else if($user[0]['group_id'] == 5){
			$sekda = $this->m_relasi_sekda->getasisten($this->ion_auth->user()->row()->id);
			
			$total = $this->m_esselon->total_list_telaah11($sekda[0]['asisten_id']);
			$diterima = $this->m_esselon->total_list_telaah_diterima11($sekda[0]['asisten_id']);
			$ditolak = $this->m_esselon->total_list_telaah_ditolak11($sekda[0]['asisten_id']);
			
			$resultData[] = array('total_list_telaah' => $total[0]['total_list_telaah'], 
								'total_list_telaah_diterima' => $diterima[0]['total_list_telaah_diterima'],
								'total_list_telaah_ditolak' => $ditolak[0]['total_list_telaah_ditolak'],
								);
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
			
		## 6 (Sekda)
		} else if($user[0]['group_id'] == 6){
			
			$total = $this->m_esselon->total_list_telaah5($user[0]['skpd_id']);
			$diterima = $this->m_esselon->total_list_telaah_diterima5($user[0]['skpd_id']);
			$ditolak = $this->m_esselon->total_list_telaah_ditolak5($user[0]['skpd_id']);
			
			$resultData[] = array('total_list_telaah' => $total[0]['total_list_telaah'], 
								'total_list_telaah_diterima' => $diterima[0]['total_list_telaah_diterima'],
								'total_list_telaah_ditolak' => $ditolak[0]['total_list_telaah_ditolak'],
								);
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
			
		## 7 (Pimpinan DPRD)
		} else if($user[0]['group_id'] == 7){
			
			$total = $this->m_esselon->total_list_telaah9($user[0]['skpd_id']);
			$diterima = $this->m_esselon->total_list_telaah_diterima9($user[0]['skpd_id']);
			$ditolak = $this->m_esselon->total_list_telaah_ditolak9($user[0]['skpd_id']);
			
			$resultData[] = array('total_list_telaah' => $total[0]['total_list_telaah'], 
								'total_list_telaah_diterima' => $diterima[0]['total_list_telaah_diterima'],
								'total_list_telaah_ditolak' => $ditolak[0]['total_list_telaah_ditolak'],
								);
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
			
		## 11 (Camat)
		} else if($user[0]['group_id'] == 11){
			
			$total = $this->m_esselon->total_list_telaah13($user[0]['skpd_id']);
			$diterima = $this->m_esselon->total_list_telaah_diterima13($user[0]['skpd_id']);
			$ditolak = $this->m_esselon->total_list_telaah_ditolak13($user[0]['skpd_id']);
			
			$resultData[] = array('total_list_telaah' => $total[0]['total_list_telaah'], 
								'total_list_telaah_diterima' => $diterima[0]['total_list_telaah_diterima'],
								'total_list_telaah_ditolak' => $ditolak[0]['total_list_telaah_ditolak'],
								);
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
			
		## 12 (Sekcam)
		} else if($user[0]['group_id'] == 12){
			
			$total = $this->m_esselon->total_list_telaah12($user[0]['skpd_id']);
			$diterima = $this->m_esselon->total_list_telaah_diterima12($user[0]['skpd_id']);
			$ditolak = $this->m_esselon->total_list_telaah_ditolak12($user[0]['skpd_id']);
			
			$resultData[] = array('total_list_telaah' => $total[0]['total_list_telaah'], 
								'total_list_telaah_diterima' => $diterima[0]['total_list_telaah_diterima'],
								'total_list_telaah_ditolak' => $ditolak[0]['total_list_telaah_ditolak'],
								);
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
			
		## 16 (Puskesmas)
		} else if($user[0]['group_id'] == 16){
			
			$total = $this->m_esselon->total_list_telaah17($user[0]['skpd_id']);
			$diterima = $this->m_esselon->total_list_telaah_diterima17($user[0]['skpd_id']);
			$ditolak = $this->m_esselon->total_list_telaah_ditolak17($user[0]['skpd_id']);
			
			$resultData[] = array('total_list_telaah' => $total[0]['total_list_telaah'], 
								'total_list_telaah_diterima' => $diterima[0]['total_list_telaah_diterima'],
								'total_list_telaah_ditolak' => $ditolak[0]['total_list_telaah_ditolak'],
								);
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
			
		}
		
	}	
	
	### TOTAL REKAP PERJALANAN
	public function rekap_perjalanan_opd($user_id)
	{
		header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$user = $this->m_api->user($user_id);
		
		## 1 (Kasubid / kasubag) (OPD)
		if($user[0]['jenis_skpd'] == 1){
			
			$esselon = $this->m_esselon->total_list_telaah($user[0]['skpd_id']);
			$masuk_esselon = $this->m_esselon->total_list_telaah_masuk($user[0]['skpd_id']);
			$proses_esselon = $this->m_esselon->total_list_telaah_diproses($user[0]['skpd_id']);
			$selesai_esselon = $this->m_esselon->total_list_telaah_diterima($user[0]['skpd_id']);
			$tolak_esselon = $this->m_esselon->total_list_telaah_ditolak($user[0]['skpd_id']);
			
			$kepala_opd = $this->m_kadis->total_list_telaah($user[0]['skpd_id']);
			$masuk_kepala_opd = $this->m_kadis->total_list_telaah_masuk($user[0]['skpd_id']);
			$proses_kepala_opd = $this->m_kadis->total_list_telaah_diproses($user[0]['skpd_id']);
			$selesai_kepala_opd = $this->m_kadis->total_list_telaah_diterima($user[0]['skpd_id']);
			$tolak_kepala_opd = $this->m_kadis->total_list_telaah_ditolak($user[0]['skpd_id']);
			
			$resultData[] = array('esselon' => $esselon[0]['total_list_telaah'], 
								'masuk_esselon' => $masuk_esselon[0]['total_list_telaah_masuk'],
								'proses_esselon' => $proses_esselon[0]['total_list_telaah_diproses'],
								'selesai_esselon' => $selesai_esselon[0]['total_list_telaah_diterima'],
								'tolak_esselon' => $tolak_esselon[0]['total_list_telaah_ditolak'],
								
								'kepala_opd' => $kepala_opd[0]['total_list_telaah'], 
								'masuk_kepala_opd' => $masuk_kepala_opd[0]['total_list_telaah_masuk'], 
								'proses_kepala_opd' => $proses_kepala_opd[0]['total_list_telaah_diproses'],
								'selesai_kepala_opd' => $selesai_kepala_opd[0]['total_list_telaah_diterima'],
								'tolak_kepala_opd' => $tolak_kepala_opd[0]['total_list_telaah_ditolak'],
								);
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
			
		## 1 (Kasubid / kasubag) (Puskesmas)	
		} else if($user[0]['jenis_skpd'] == 7){
			
			$puskesmas = $this->m_kapus->total_list_telaah($user[0]['skpd_id']);
			$masuk_puskesmas = $this->m_kapus->total_list_telaah_masuk($user[0]['skpd_id']);
			$proses_puskesmas = $this->m_kapus->total_list_telaah_diproses($user[0]['skpd_id']);
			$selesai_puskesmas = $this->m_kapus->total_list_telaah_diterima($user[0]['skpd_id']);
			$tolak_puskesmas = $this->m_kapus->total_list_telaah_ditolak($user[0]['skpd_id']);
			
			$esselon = $this->m_esselon->total_list_telaah($user[0]['skpd_id']);
			$masuk_esselon = $this->m_esselon->total_list_telaah_masuk($user[0]['skpd_id']);
			$proses_esselon = $this->m_esselon->total_list_telaah_diproses($user[0]['skpd_id']);
			$selesai_esselon = $this->m_esselon->total_list_telaah_diterima($user[0]['skpd_id']);
			$tolak_esselon = $this->m_esselon->total_list_telaah_ditolak($user[0]['skpd_id']);
			
			$resultData[] = array('puskesmas' => $puskesmas[0]['total_list_telaah'], 
								'masuk_puskesmas' => $masuk_puskesmas[0]['total_list_telaah_masuk'],
								'proses_puskesmas' => $proses_puskesmas[0]['total_list_telaah_diproses'],
								'selesai_puskesmas' => $selesai_puskesmas[0]['total_list_telaah_diterima'],
								'tolak_puskesmas' => $tolak_puskesmas[0]['total_list_telaah_ditolak'],
								
								'esselon' => $esselon[0]['total_list_telaah'], 
								'masuk_esselon' => $masuk_esselon[0]['total_list_telaah_masuk'],
								'proses_esselon' => $proses_esselon[0]['total_list_telaah_diproses'],
								'selesai_esselon' => $selesai_esselon[0]['total_list_telaah_diterima'],
								'tolak_esselon' => $tolak_esselon[0]['total_list_telaah_ditolak'],
								);
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
		
		## 1 (Kasubid / kasubag) (Dinkes)		
		} else if($user[0]['jenis_skpd'] == 10){
			
			$esselon = $this->m_esselon->total_list_telaah_dinkes();
			$masuk_esselon = $this->m_esselon->total_list_telaah_masuk_dinkes();
			$proses_esselon = $this->m_esselon->total_list_telaah_diproses_dinkes();
			$selesai_esselon = $this->m_esselon->total_list_telaah_diterima_dinkes();
			$tolak_esselon = $this->m_esselon->total_list_telaah_ditolak_dinkes();
			
			$kepala_opd = $this->m_kadis->total_list_telaah($user[0]['skpd_id']);
			$masuk_kepala_opd = $this->m_kadis->total_list_telaah_masuk($user[0]['skpd_id']);
			$proses_kepala_opd = $this->m_kadis->total_list_telaah_diproses($user[0]['skpd_id']);
			$selesai_kepala_opd = $this->m_kadis->total_list_telaah_diterima($user[0]['skpd_id']);
			$tolak_kepala_opd = $this->m_kadis->total_list_telaah_ditolak($user[0]['skpd_id']);
			
			$resultData[] = array('esselon' => $esselon[0]['total_list_telaah'], 
								'masuk_esselon' => $masuk_esselon[0]['total_list_telaah_masuk'],
								'proses_esselon' => $proses_esselon[0]['total_list_telaah_diproses'],
								'selesai_esselon' => $selesai_esselon[0]['total_list_telaah_diterima'],
								'tolak_esselon' => $tolak_esselon[0]['total_list_telaah_ditolak'],
								
								'kepala_opd' => $kepala_opd[0]['total_list_telaah'], 
								'masuk_kepala_opd' => $masuk_kepala_opd[0]['total_list_telaah_masuk'], 
								'proses_kepala_opd' => $proses_kepala_opd[0]['total_list_telaah_diproses'],
								'selesai_kepala_opd' => $selesai_kepala_opd[0]['total_list_telaah_diterima'],
								'tolak_kepala_opd' => $tolak_kepala_opd[0]['total_list_telaah_ditolak'],
								);
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
		
		## 1 (Kasubid / kasubag) (DPRD)	
		} else if($user[0]['jenis_skpd'] == 2){
			
			$anggota_dprd = $this->m_dprd->total_list_telaah();
			$masuk_anggota_dprd = $this->m_dprd->total_list_telaah_masuk();
			$proses_anggota_dprd = $this->m_dprd->total_list_telaah_diproses();
			$selesai_anggota_dprd = $this->m_dprd->total_list_telaah_diterima();
			$tolak_anggota_dprd = $this->m_dprd->total_list_telaah_ditolak();
			
			$staff_dprd = $this->m_staff_dprd->total_list_telaah();
			$masuk_staff_dprd = $this->m_staff_dprd->total_list_telaah_masuk();
			$proses_staff_dprd = $this->m_staff_dprd->total_list_telaah_diproses();
			$selesai_staff_dprd = $this->m_staff_dprd->total_list_telaah_diterima();
			$tolak_staff_dprd = $this->m_staff_dprd->total_list_telaah_ditolak();
			
			$sekwan = $this->m_sekwan->total_list_telaah($user[0]['skpd_id']);
			$masuk_sekwan = $this->m_sekwan->total_list_telaah_masuk($user[0]['skpd_id']);
			$proses_sekwan = $this->m_sekwan->total_list_telaah_diproses($user[0]['skpd_id']);
			$selesai_sekwan = $this->m_sekwan->total_list_telaah_diterima($user[0]['skpd_id']);
			$tolak_sekwan = $this->m_sekwan->total_list_telaah_ditolak($user[0]['skpd_id']);
			
			$resultData[] = array('anggota_dprd' => $anggota_dprd[0]['total_list_telaah'], 
								'masuk_anggota_dprd' => $masuk_anggota_dprd[0]['total_list_telaah_masuk'],
								'proses_anggota_dprd' => $proses_anggota_dprd[0]['total_list_telaah_diproses'],
								'selesai_anggota_dprd' => $selesai_anggota_dprd[0]['total_list_telaah_diterima'],
								'tolak_anggota_dprd' => $tolak_anggota_dprd[0]['total_list_telaah_ditolak'],
								
								'staff_dprd' => $staff_dprd[0]['total_list_telaah'], 
								'masuk_staff_dprd' => $masuk_staff_dprd[0]['total_list_telaah_masuk'],
								'proses_staff_dprd' => $proses_staff_dprd[0]['total_list_telaah_diproses'],
								'selesai_staff_dprd' => $selesai_staff_dprd[0]['total_list_telaah_diterima'],
								'tolak_staff_dprd' => $tolak_staff_dprd[0]['total_list_telaah_ditolak'],
								
								'sekwan' => $sekwan[0]['total_list_telaah'], 
								'masuk_sekwan' => $masuk_sekwan[0]['total_list_telaah_masuk'],
								'proses_sekwan' => $proses_sekwan[0]['total_list_telaah_diproses'],
								'selesai_sekwan' => $selesai_sekwan[0]['total_list_telaah_diterima'],
								'tolak_sekwan' => $tolak_sekwan[0]['total_list_telaah_ditolak'],
								);
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
		
		## 1 (Kasubid / kasubag) (Sekda)
		} else if($user[0]['jenis_skpd'] == 3){
			$staff_sekda = $this->m_relasi_sekda->getsubbagian($user_id);
			if($staff_sekda){
				$this->data['total_pegawai'] = $this->m_sekda->total_pegawai_sekda($staff_sekda[0]['bagian_id']);
			} else {
				$this->data['total_pegawai'] = $this->m_sekda->total_pegawai();
			}
			
			$walikota = $this->m_walikota->total_list_telaah($user[0]['skpd_id']);
			$masuk_walikota = $this->m_walikota->total_list_telaah_masuk($user[0]['skpd_id']);
			$proses_walikota = $this->m_walikota->total_list_telaah_diproses($user[0]['skpd_id']);
			$selesai_walikota = $this->m_walikota->total_list_telaah_diterima($user[0]['skpd_id']);
			$tolak_walikota = $this->m_walikota->total_list_telaah_ditolak($user[0]['skpd_id']);
			
			$sekda = $this->m_sekda->total_list_telaah_sekda($sekda[0]['subbagian_id']);
			$masuk_sekda = $this->m_sekda->total_list_telaah_masuk($sekda[0]['subbagian_id']);
			$proses_sekda = $this->m_sekda->total_list_telaah_diproses($sekda[0]['subbagian_id']);
			$selesai_sekda = $this->m_sekda->total_list_telaah_diterima_sekda($sekda[0]['subbagian_id']);
			$tolak_sekda = $this->m_sekda->total_list_telaah_ditolak_sekda($sekda[0]['subbagian_id']);
			
			$staff_setda = $this->m_sekda->total_list_telaah_staff($staff_sekda[0]['bagian_id']);
			$masuk_staff_setda = $this->m_sekda->total_list_telaah_masuk_staff($staff_sekda[0]['bagian_id']);
			$proses_staff_setda = $this->m_sekda->total_list_telaah_diproses_staff($staff_sekda[0]['bagian_id']);
			$selesai_staff_setda = $this->m_sekda->total_list_telaah_diterima_staff($staff_sekda[0]['bagian_id']);
			$tolak_staff_setda = $this->m_sekda->total_list_telaah_ditolak_staff($staff_sekda[0]['bagian_id']);
			
			$resultData[] = array('walikota' => $walikota[0]['total_list_telaah'], 
								'masuk_walikota' => $masuk_walikota[0]['total_list_telaah_masuk'],
								'proses_walikota' => $proses_walikota[0]['total_list_telaah_diproses'],
								'selesai_walikota' => $selesai_walikota[0]['total_list_telaah_diterima'],
								'tolak_walikota' => $tolak_walikota[0]['total_list_telaah_ditolak'],
								
								'sekda' => $sekda[0]['total_list_telaah'], 
								'masuk_sekda' => $masuk_sekda[0]['total_list_telaah_masuk'],
								'proses_sekda' => $proses_sekda[0]['total_list_telaah_diproses'],
								'selesai_sekda' => $selesai_sekda[0]['total_list_telaah_diterima'],
								'tolak_sekda' => $tolak_sekda[0]['total_list_telaah_ditolak'],
								
								'staff_setda' => $staff_setda[0]['total_list_telaah_staff'], 
								'masuk_staff_setda' => $masuk_staff_setda[0]['total_list_telaah_masuk_staff'],
								'proses_staff_setda' => $proses_staff_setda[0]['total_list_telaah_diproses_staff'],
								'selesai_staff_setda' => $selesai_staff_setda[0]['total_list_telaah_diterima_staff'],
								'tolak_staff_setda' => $tolak_staff_setda[0]['total_list_telaah_ditolak_staff'],
								);
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
			
			
		## 1 (Kasubid / kasubag) (Camat)
		} else if($user[0]['jenis_skpd'] == 4){
			
			$camat = $this->m_camat->total_list_telaah($user[0]['skpd_id']);
			$masuk_camat = $this->m_camat->total_list_telaah_masuk($user[0]['skpd_id']);
			$proses_camat = $this->m_camat->total_list_telaah_diproses($user[0]['skpd_id']);
			$selesai_camat = $this->m_camat->total_list_telaah_diterima($user[0]['skpd_id']);
			$tolak_camat = $this->m_camat->total_list_telaah_ditolak($user[0]['skpd_id']);
			
			$staff_camat = $this->m_staff_camat->total_list_telaah($user[0]['skpd_id']);
			$masuk_staff_camat = $this->m_staff_camat->total_list_telaah_masuk($user[0]['skpd_id']);
			$proses_staff_camat = $this->m_staff_camat->total_list_telaah_diproses($user[0]['skpd_id']);
			$selesai_staff_camat = $this->m_staff_camat->total_list_telaah_diterima($user[0]['skpd_id']);
			$tolak_staff_camat = $this->m_staff_camat->total_list_telaah_ditolak($user[0]['skpd_id']);
			
			$resultData[] = array('camat' => $camat[0]['total_list_telaah'], 
								'masuk_camat' => $masuk_camat[0]['total_list_telaah_masuk'],
								'proses_camat' => $proses_camat[0]['total_list_telaah_diproses'],
								'selesai_camat' => $selesai_camat[0]['total_list_telaah_diterima'],
								'tolak_camat' => $tolak_camat[0]['total_list_telaah_ditolak'],
								
								'staff_camat' => $staff_camat[0]['total_list_telaah'], 
								'masuk_staff_camat' => $masuk_staff_camat[0]['total_list_telaah_masuk'], 
								'proses_staff_camat' => $proses_staff_camat[0]['total_list_telaah_diproses'],
								'selesai_staff_camat' => $selesai_staff_camat[0]['total_list_telaah_diterima'],
								'tolak_staff_camat' => $tolak_staff_camat[0]['total_list_telaah_ditolak']
								);
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
			
		## 5 (Lurah)
		} else if($user[0]['jenis_skpd'] == 5){
			
			$lurah = $this->m_lurah->total_list_telaah($user[0]['skpd_id']);
			$masuk_lurah = $this->m_lurah->total_list_telaah_masuk($user[0]['skpd_id']);
			$proses_lurah = $this->m_lurah->total_list_telaah_diproses($user[0]['skpd_id']);
			$selesai_lurah = $this->m_lurah->total_list_telaah_diterima($user[0]['skpd_id']);
			$tolak_lurah = $this->m_lurah->total_list_telaah_ditolak($user[0]['skpd_id']);
			
			$staff_lurah = $this->m_staff_lurah->total_list_telaah($user[0]['skpd_id']);
			$masuk_staff_lurah = $this->m_staff_lurah->total_list_telaah_masuk($user[0]['skpd_id']);
			$proses_staff_lurah = $this->m_staff_lurah->total_list_telaah_diproses($user[0]['skpd_id']);
			$selesai_staff_lurah = $this->m_staff_lurah->total_list_telaah_diterima($user[0]['skpd_id']);
			$tolak_staff_lurah = $this->m_staff_lurah->total_list_telaah_ditolak($user[0]['skpd_id']);
			
			$resultData[] = array('lurah' => $lurah[0]['total_list_telaah'], 
								'masuk_lurah' => $masuk_lurah[0]['total_list_telaah_masuk'],
								'proses_lurah' => $proses_lurah[0]['total_list_telaah_diproses'],
								'selesai_lurah' => $selesai_lurah[0]['total_list_telaah_diterima'],
								'tolak_lurah' => $tolak_lurah[0]['total_list_telaah_ditolak'],
								
								'staff_lurah' => $staff_lurah[0]['total_list_telaah'], 
								'masuk_staff_lurah' => $masuk_staff_lurah[0]['total_list_telaah_masuk'],
								'proses_staff_lurah' => $proses_staff_lurah[0]['total_list_telaah_diproses'],
								'selesai_staff_lurah' => $selesai_staff_lurah[0]['total_list_telaah_diterima'],
								'tolak_staff_lurah' => $tolak_staff_lurah[0]['total_list_telaah_ditolak']
								);
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
			
		} 
		
	}
	
	### TOTAL ANGGARAN KESELURUHAN
	public function anggaran_keseluruhan()
	{
		// required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$total_anggaran_keseluruhan = $this->m_api->total_anggaran_keseluruhan();
		$jumlah_rincian_belanja_keseluruhan = $this->m_api->rincian_belanja('');
		$jumlah_pengeluaran_rill_keseluruhan = $this->m_api->pengeluaran_rill('');
		
		$total_anggaran_dalam_daerah = $this->m_api->total_anggaran_dalam_daerah('');
		$jumlah_rincian_belanja_dalam_daerah = $this->m_api->rincian_belanja_dalam_daerah('');
		$jumlah_pengeluaran_rill_dalam_daerah = $this->m_api->pengeluaran_rill_dalam_daerah('');
		
		$total_anggaran_luar_daerah = $this->m_api->total_anggaran_luar_daerah('');
		$jumlah_rincian_belanja_luar_daerah = $this->m_api->rincian_belanja_luar_daerah('');
		$jumlah_pengeluaran_rill_luar_daerah = $this->m_api->pengeluaran_rill_luar_daerah('');
		
		$total_anggaran_bimtek = $this->m_api->total_anggaran_bimtek('');
		$jumlah_rincian_belanja_bimtek = $this->m_api->rincian_belanja_bimtek('');
		$jumlah_pengeluaran_rill_bimtek = $this->m_api->pengeluaran_rill_bimtek('');
		
		$resultData[] = array('total_anggaran_keseluruhan' => $total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan'], 
							'jumlah_rincian_belanja_keseluruhan' => $jumlah_rincian_belanja_keseluruhan[0]['jumlah_rincian_belanja_keseluruhan'],
							'jumlah_pengeluaran_rill_keseluruhan' => $jumlah_pengeluaran_rill_keseluruhan[0]['jumlah_pengeluaran_rill_keseluruhan'],
							
							'total_anggaran_dalam_daerah' => $total_anggaran_dalam_daerah[0]['total_anggaran_dalam_daerah'],
							'jumlah_rincian_belanja_dalam_daerah' => $jumlah_rincian_belanja_dalam_daerah[0]['jumlah_rincian_belanja_dalam_daerah'],
							'jumlah_pengeluaran_rill_dalam_daerah' => $jumlah_pengeluaran_rill_dalam_daerah[0]['jumlah_pengeluaran_rill_dalam_daerah'],
							
							'total_anggaran_luar_daerah' => $total_anggaran_luar_daerah[0]['total_anggaran_luar_daerah'],
							'jumlah_rincian_belanja_luar_daerah' => $jumlah_rincian_belanja_luar_daerah[0]['jumlah_rincian_belanja_luar_daerah'],
							'jumlah_pengeluaran_rill_luar_daerah' => $jumlah_pengeluaran_rill_luar_daerah[0]['jumlah_pengeluaran_rill_luar_daerah'],
							
							'total_anggaran_bimtek' => $total_anggaran_bimtek[0]['total_anggaran_bimtek'],
							'jumlah_rincian_belanja_bimtek' => $jumlah_rincian_belanja_bimtek[0]['jumlah_rincian_belanja_bimtek'],
							'jumlah_pengeluaran_rill_bimtek' => $jumlah_pengeluaran_rill_bimtek[0]['jumlah_pengeluaran_rill_bimtek'],
							);
		
		echo json_encode($resultData, JSON_PRETTY_PRINT);
		
	}	
	
	### TOTAL ANGGARAN SKPD
	public function anggaran($user_id)
	{
		// required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$user = $this->m_api->user($user_id);
		
		if($user[0]['group_id']==8){
			$total_anggaran_keseluruhan = $this->m_api->total_anggaran_keseluruhan();
			$jumlah_rincian_belanja_keseluruhan = $this->m_api->rincian_belanja('');
			$jumlah_pengeluaran_rill_keseluruhan = $this->m_api->pengeluaran_rill('');
			
			$total_anggaran_dalam_daerah = $this->m_api->total_anggaran_dalam_daerah('');
			$jumlah_rincian_belanja_dalam_daerah = $this->m_api->rincian_belanja_dalam_daerah('');
			$jumlah_pengeluaran_rill_dalam_daerah = $this->m_api->pengeluaran_rill_dalam_daerah('');
			
			$total_anggaran_luar_daerah = $this->m_api->total_anggaran_luar_daerah('');
			$jumlah_rincian_belanja_luar_daerah = $this->m_api->rincian_belanja_luar_daerah('');
			$jumlah_pengeluaran_rill_luar_daerah = $this->m_api->pengeluaran_rill_luar_daerah('');
			
			$total_anggaran_bimtek = $this->m_api->total_anggaran_bimtek('');
			$jumlah_rincian_belanja_bimtek = $this->m_api->rincian_belanja_bimtek('');
			$jumlah_pengeluaran_rill_bimtek = $this->m_api->pengeluaran_rill_bimtek('');
			
			$resultData[] = array('total_anggaran_keseluruhan' => $total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan'], 
								'jumlah_realisasi_keseluruhan' => $jumlah_rincian_belanja_keseluruhan[0]['jumlah_rincian_belanja_keseluruhan'] + $jumlah_pengeluaran_rill_keseluruhan[0]['jumlah_pengeluaran_rill_keseluruhan'],
								'jumlah_sisa_keseluruhan' => $total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan'] - ($jumlah_rincian_belanja_keseluruhan[0]['jumlah_rincian_belanja_keseluruhan'] + $jumlah_pengeluaran_rill_keseluruhan[0]['jumlah_pengeluaran_rill_keseluruhan']),
								
								'total_anggaran_dalam_daerah' => $total_anggaran_dalam_daerah[0]['total_anggaran_dalam_daerah'],
								'jumlah_realisasi_dalam_daerah' => $jumlah_rincian_belanja_dalam_daerah[0]['jumlah_rincian_belanja_dalam_daerah'] + $jumlah_pengeluaran_rill_dalam_daerah[0]['jumlah_pengeluaran_rill_dalam_daerah'],
								'jumlah_sisa_dalam_daerah' => $total_anggaran_dalam_daerah[0]['total_anggaran_dalam_daerah'] - ($jumlah_rincian_belanja_dalam_daerah[0]['jumlah_rincian_belanja_dalam_daerah'] + $jumlah_pengeluaran_rill_dalam_daerah[0]['jumlah_pengeluaran_rill_dalam_daerah']),
								
								'total_anggaran_luar_daerah' => $total_anggaran_luar_daerah[0]['total_anggaran_luar_daerah'],
								'jumlah_realisasi_luar_daerah' => $jumlah_rincian_belanja_luar_daerah[0]['jumlah_rincian_belanja_luar_daerah'] + $jumlah_pengeluaran_rill_luar_daerah[0]['jumlah_pengeluaran_rill_luar_daerah'],
								'jumlah_sisa_luar_daerah' => $total_anggaran_luar_daerah[0]['total_anggaran_luar_daerah'] - ($jumlah_rincian_belanja_luar_daerah[0]['jumlah_rincian_belanja_luar_daerah'] + $jumlah_pengeluaran_rill_luar_daerah[0]['jumlah_pengeluaran_rill_luar_daerah']),
								
								'total_anggaran_bimtek' => $total_anggaran_bimtek[0]['total_anggaran_bimtek'],
								'jumlah_realisasi_bimtek' => $jumlah_rincian_belanja_bimtek[0]['jumlah_rincian_belanja_bimtek'] + $jumlah_pengeluaran_rill_bimtek[0]['jumlah_pengeluaran_rill_bimtek'],
								'jumlah_sisa_bimtek' => $total_anggaran_bimtek[0]['total_anggaran_bimtek'] - ($jumlah_rincian_belanja_bimtek[0]['jumlah_rincian_belanja_bimtek'] + $jumlah_pengeluaran_rill_bimtek[0]['jumlah_pengeluaran_rill_bimtek']),
								);
		} else {
			$total_anggaran_keseluruhan = $this->m_api->total_anggaran_skpd($user[0]['skpd_id']);
			$jumlah_rincian_belanja_keseluruhan = $this->m_api->rincian_belanja($user[0]['skpd_id']);
			$jumlah_pengeluaran_rill_keseluruhan = $this->m_api->pengeluaran_rill($user[0]['skpd_id']);
			
			$total_anggaran_dalam_daerah = $this->m_api->total_anggaran_dalam_daerah($user[0]['skpd_id']);
			$jumlah_rincian_belanja_dalam_daerah = $this->m_api->rincian_belanja_dalam_daerah($user[0]['skpd_id']);
			$jumlah_pengeluaran_rill_dalam_daerah = $this->m_api->pengeluaran_rill_dalam_daerah($user[0]['skpd_id']);
			
			$total_anggaran_luar_daerah = $this->m_api->total_anggaran_luar_daerah($user[0]['skpd_id']);
			$jumlah_rincian_belanja_luar_daerah = $this->m_api->rincian_belanja_luar_daerah($user[0]['skpd_id']);
			$jumlah_pengeluaran_rill_luar_daerah = $this->m_api->pengeluaran_rill_luar_daerah($user[0]['skpd_id']);
			
			$total_anggaran_bimtek = $this->m_api->total_anggaran_bimtek($user[0]['skpd_id']);
			$jumlah_rincian_belanja_bimtek = $this->m_api->rincian_belanja_bimtek($user[0]['skpd_id']);
			$jumlah_pengeluaran_rill_bimtek = $this->m_api->pengeluaran_rill_bimtek($user[0]['skpd_id']);
			
			$resultData[] = array('total_anggaran_keseluruhan' => $total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan'], 
								'jumlah_realisasi_keseluruhan' => $jumlah_rincian_belanja_keseluruhan[0]['jumlah_rincian_belanja_keseluruhan'] +$jumlah_pengeluaran_rill_keseluruhan[0]['jumlah_pengeluaran_rill_keseluruhan'],
								'jumlah_sisa_keseluruhan' => $total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan'] -($jumlah_rincian_belanja_keseluruhan[0]['jumlah_rincian_belanja_keseluruhan'] +$jumlah_pengeluaran_rill_keseluruhan[0]['jumlah_pengeluaran_rill_keseluruhan']),
								
								'total_anggaran_dalam_daerah' => $total_anggaran_dalam_daerah[0]['total_anggaran_dalam_daerah'],
								'jumlah_realisasi_dalam_daerah' => $jumlah_rincian_belanja_dalam_daerah[0]['jumlah_rincian_belanja_dalam_daerah'] + $jumlah_pengeluaran_rill_dalam_daerah[0]['jumlah_pengeluaran_rill_dalam_daerah'],
								'jumlah_sisa_dalam_daerah' => $total_anggaran_dalam_daerah[0]['total_anggaran_dalam_daerah'] - ($jumlah_rincian_belanja_dalam_daerah[0]['jumlah_rincian_belanja_dalam_daerah'] + $jumlah_pengeluaran_rill_dalam_daerah[0]['jumlah_pengeluaran_rill_dalam_daerah']),
								
								'total_anggaran_luar_daerah' => $total_anggaran_luar_daerah[0]['total_anggaran_luar_daerah'],
								'jumlah_realisasi_luar_daerah' => $jumlah_rincian_belanja_luar_daerah[0]['jumlah_rincian_belanja_luar_daerah'] + $jumlah_pengeluaran_rill_luar_daerah[0]['jumlah_pengeluaran_rill_luar_daerah'],
								'jumlah_sisa_luar_daerah' => $total_anggaran_luar_daerah[0]['total_anggaran_luar_daerah'] - ($jumlah_rincian_belanja_luar_daerah[0]['jumlah_rincian_belanja_luar_daerah'] + $jumlah_pengeluaran_rill_luar_daerah[0]['jumlah_pengeluaran_rill_luar_daerah']),
								
								'total_anggaran_bimtek' => $total_anggaran_bimtek[0]['total_anggaran_bimtek'],
								'jumlah_realisasi_bimtek' => $jumlah_rincian_belanja_bimtek[0]['jumlah_rincian_belanja_bimtek'] + $jumlah_pengeluaran_rill_bimtek[0]['jumlah_pengeluaran_rill_bimtek'],
								'jumlah_sisa_bimtek' => $total_anggaran_bimtek[0]['total_anggaran_bimtek'] - ($jumlah_rincian_belanja_bimtek[0]['jumlah_rincian_belanja_bimtek'] + $jumlah_pengeluaran_rill_bimtek[0]['jumlah_pengeluaran_rill_bimtek']),
								);
		
		}
       
		echo json_encode($resultData, JSON_PRETTY_PRINT);
		
	}	
	
	### DETAIL TELAAH
	public function detail_telaah($telaah_id, $kategori)
	{
		// required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
        $telaah = $this->m_api->detail_telaah($telaah_id, $kategori);
		
		$rincian_belanja = $this->m_api->total_rincian($telaah[0]['id_anggaran']);
		$pengeluaran_rill = $this->m_api->total_pengeluaran_rill($telaah[0]['id_anggaran']);
		
		$total = $rincian_belanja[0]['total_rincian'] + $pengeluaran_rill[0]['total_pengeluaran_rill'];
		$date = substr($telaah[0]['telaah_waktuinput'], 0, 10);
		$time = substr($telaah[0]['telaah_waktuinput'], 11, 19);
		$telaah_waktuinput =  $this->date_indo($date);
		
		### Rincian Belanja Pelaksana
		$rincian_belanja_pelaksana = $this->m_api->get_rincian($telaah_id, $telaah[0]['pegawai_id'], $telaah[0]['telaah_kategori']);
		$rincian_pelaksana = array();
		foreach($rincian_belanja_pelaksana as $v){
		
			if($v->kategori_biaya==1){
				$kategori_biaya = "Penginapan";
			} else if($v->kategori_biaya==2){
				$kategori_biaya = "Sewa Kendaraan";
			} else if($v->kategori_biaya==3){
				$kategori_biaya = "Transport";
			} else if($v->kategori_biaya==4){
				$kategori_biaya = "Biaya Lainnya";
			} else if($v->kategori_biaya==5){
				$kategori_biaya = "Lumpsum";
			} else if($v->kategori_biaya==6){
				$kategori_biaya = "Representasi";
			}
				
			$rincian_pelaksana[] = array(
						'kategori_biaya'=> $kategori_biaya,
						'keterangan'=> $v->keterangan,
						'item'=> $v->item,
						'tarif'=> $v->tarif,
						'total'=> $v->tarif * $v->item);
			
		}
		
		### Rincian Belanja Pengikut
		$pengikut = $this->m_api->get_pengikut('','',$telaah_id, $telaah[0]['telaah_kategori']);
		$rincian_pengikut = array();
		$no = 0;
		foreach($pengikut as $s){
			$rincian_belanja_pengikut = $this->m_api->get_rincian($telaah_id, $s->pegawai_id, $telaah[0]['telaah_kategori']);
			$no++;
			
			$rincian_pengikut2 = array();
			foreach($rincian_belanja_pengikut as $v){
				
				if($v->kategori_biaya==1){
					$kategori_biaya = "Penginapan";
				} else if($v->kategori_biaya==2){
					$kategori_biaya = "Sewa Kendaraan";
				} else if($v->kategori_biaya==3){
					$kategori_biaya = "Transport";
				} else if($v->kategori_biaya==4){
					$kategori_biaya = "Biaya Lainnya";
				} else if($v->kategori_biaya==5){
					$kategori_biaya = "Lumpsum";
				} else if($v->kategori_biaya==6){
					$kategori_biaya = "Representasi";
				}
						
				$rincian_pengikut2[] = array(
							'pegawai_id'=> $v->pegawai_id,
							'kategori_biaya'=> $kategori_biaya,
							'keterangan'=> $v->keterangan,
							'item'=> $v->item,
							'tarif'=> $v->tarif,
							'total'=> $v->tarif * $v->item);
				
			}
			$rincian_pengikut[] = array('no'=> $no,
										'nama_pengikut'.$no=> $s->pegawai_nama,
										'rincian_pengikut'.$no=> $rincian_pengikut2);	
		}
		
		$resultData = array();
			$resultData[] = array('telaah_id'=> $telaah[0]['telaah_id'],
								'pegawai_id'=> $telaah[0]['pegawai_id'],
								'pegawai_nip'=> $telaah[0]['pegawai_nip'],
								'pegawai_nama'=> $telaah[0]['pegawai_nama'],
								'pegawai_jabatan'=> $telaah[0]['pegawai_jabatan'],
								'pegawai_namajabatan'=> $telaah[0]['pegawai_namajabatan'],
								'telaah_kepada'=> $telaah[0]['telaah_kepada'],
								'telaah_perihal'=> $telaah[0]['telaah_perihal'],
								'telaah_persoalan'=> $telaah[0]['telaah_persoalan'],
								'telaah_fakta'=> $telaah[0]['telaah_fakta'],
								'telaah_analisis'=> $telaah[0]['telaah_analisis'],
								'telaah_waktuinput'=> $telaah_waktuinput.' '.$time,
								'telaah_dokumenpendukung'=> $telaah[0]['telaah_dokumenpendukung'],
								'telaah_tanggalberangkat'=> $this->date_indo($telaah[0]['telaah_tanggalberangkat']),
								'telaah_tanggalkembali'=>  $this->date_indo($telaah[0]['telaah_tanggalkembali']),
								'telaah_domainperjalanan'=> $telaah[0]['telaah_domainperjalanan'],
								'telaah_provinsitujuan'=> $telaah[0]['telaah_provinsitujuan'],
								'kabupaten_kota'=> $telaah[0]['kabupaten_kota'],
								'provinsi'=> $telaah[0]['provinsi'],
								'telaah_kotatujuan'=> $telaah[0]['telaah_kotatujuan'],
								'telaah_kategoriperjalanan'=> $telaah[0]['telaah_kategoriperjalanan'],
								'telaah_kantortujuan'=> $telaah[0]['telaah_kantortujuan'],
								'skpd_id'=> $telaah[0]['skpd_id'],
								'bagian_id'=> $telaah[0]['bagian_id'],
								'skpd_kode'=> $telaah[0]['skpd_kode'],
								'skpd_nama'=> $telaah[0]['skpd_nama'],
								'id_anggaran'=> $telaah[0]['id_anggaran'],
								'jenis_anggaran'=> $telaah[0]['jenis_anggaran'],
								'nama_program'=> $telaah[0]['nama_program'],
								'nama_kegiatan'=> $telaah[0]['nama_kegiatan'],
								'tahun'=> $telaah[0]['tahun'],
								'mata_anggaran'=> $telaah[0]['mata_anggaran'],
								'pagu'=> $telaah[0]['pagu'],
								'pagu_tersisa'=> $telaah[0]['pagu']-$total,
								'no_rekening'=> $telaah[0]['no_rekening'],
								'uraian'=> $telaah[0]['uraian'],
								'domain_perjalanan'=> $telaah[0]['domain_perjalanan'],
								'user_id'=> $telaah[0]['user_id'],
								'telaah_kategori'=> $telaah[0]['telaah_kategori'],
								'rincian_pelaksana' =>$rincian_pelaksana,
								//'rincian_pengikut' =>$rincian_pengikut=(array('telaah_id'=> $rincian_pengikut2)),
								'rincian_pengikut' =>$rincian_pengikut,
								);
			
		
		echo json_encode($resultData, JSON_PRETTY_PRINT);
	}	
	
	### RINCIAN 
	public function rincian($telaah_id)
	{
		// required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$telaah = $this->m_api->get_telaah($telaah_id);
		
		### PELAKSANA
		$s = 0;
		$pengeluaran_rill= $this->m_api->get_pengeluaran_rill($telaah_id, $telaah[0]['telaah_pelaksana'], $telaah[0]['telaah_kategori']);
		
			foreach($pengeluaran_rill as $v){
				$s = $s + $v->tarif;
			}
		

		$rincian_belanja_pelaksana = $this->m_api->get_rincian($telaah_id, $telaah[0]['telaah_pelaksana'], $telaah[0]['telaah_kategori']);
		
		$n = 0;
		foreach($rincian_belanja_pelaksana as $v){
			
			$n	= $n + ($v->tarif * $v->item);			
		}
			
		$resultData[] = array(
						'nama_pegawai'=> $v->pegawai_nama,
						'total'=> $s+$n);
		
		###	PENGIKUT	
		$pengikut = $this->m_api->get_pengikut('','',$telaah_id, $telaah[0]['telaah_kategori']);
		
		$rincian_pengikut = array();
		$no = 0;
		$x = 0;
		$m = 0;
		foreach($pengikut as $s){
			
			$pengeluaran_rill = $this->m_api->get_pengeluaran_rill($telaah_id, $s->pegawai_id, $telaah[0]['telaah_kategori']);
			
			$pengeluaran_pengikut2 = array();
			foreach($pengeluaran_rill as $o){
				$m	= $m + $o->tarif;			
			}
			
			$rincian_belanja_pengikut = $this->m_api->get_rincian($telaah_id, $s->pegawai_id, $telaah[0]['telaah_kategori']);
			$no++;
			
			$rincian_pengikut2 = array();
			foreach($rincian_belanja_pengikut as $v){
				$x	= $x + ($v->tarif * $v->item);			
			}
			
			$resultData[] = array(
										'nama_pengikut'=> $s->pegawai_nama,
										'total'=> $x+$m);	
		}	
		
		### LAPORAN
		$laporan_perjalanan = $this->m_api->laporan_perjalanan($telaah_id);
		
		$n = 0;
		foreach($laporan_perjalanan as $v){
				
				$resultData[] = array(
						'nama_laporan'=> $v->laporanperjalanan_name,
						'deskripsi'=> $v->laporanperjalanan_desc,
						'tanggal'=> $v->laporanperjalanan_date,
						'nama_file'=> $v->laporanperjalanan_file,
						'url'=> base_url().'upload/laporan_perjalanan/'.$v->laporanperjalanan_file,
						);
			}
			
			
		
		echo json_encode($resultData, JSON_PRETTY_PRINT);
		
	}	
	
	### RINCIAN PELAKSANA TELAAH
	public function rincian_pelaksana($telaah_id)
	{
		// required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$telaah = $this->m_api->get_telaah($telaah_id);
		
		$s = 0;
		$pengeluaran_rill= $this->m_api->get_pengeluaran_rill($telaah_id, $telaah[0]['telaah_pelaksana'], $telaah[0]['telaah_kategori']);
		
			foreach($pengeluaran_rill as $v){
				$s = $s + $v->tarif;
			}
		

		$rincian_belanja_pelaksana = $this->m_api->get_rincian($telaah_id, $telaah[0]['telaah_pelaksana'], $telaah[0]['telaah_kategori']);
		
		$n = 0;
		if($rincian_belanja_pelaksana){
			foreach($rincian_belanja_pelaksana as $v){
				
				$n	= $n + ($v->tarif * $v->item);			
				// if($v->kategori_biaya==1){
					// $kategori_biaya = "Penginapan";
				// } else if($v->kategori_biaya==2){
					// $kategori_biaya = "Sewa Kendaraan";
				// } else if($v->kategori_biaya==3){
					// $kategori_biaya = "Transport";
				// } else if($v->kategori_biaya==4){
					// $kategori_biaya = "Biaya Lainnya";
				// } else if($v->kategori_biaya==5){
					// $kategori_biaya = "Lumpsum";
				// } else if($v->kategori_biaya==6){
					// $kategori_biaya = "Representasi";
				// }
			}
			
			$resultData[] = array(
						'nama_pegawai'=> $v->pegawai_nama,
						'total'=> $s+$n);
		} else {
			$resultData[] = array(
						'total'=> '');
		}
			
		
		echo json_encode($resultData, JSON_PRETTY_PRINT);
		
	}	
	
	### RINCIAN PENGIKUT TELAAH
	public function rincian_pengikut($telaah_id)
	{
		// required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		### Rincian Belanja Pengikut
		
		$telaah = $this->m_api->get_telaah($telaah_id);
		
		$pengikut = $this->m_api->get_pengikut('','',$telaah_id, $telaah[0]['telaah_kategori']);
		
		if($pengikut){
			$rincian_pengikut = array();
			$no = 0;
			$n = 0;
			$m = 0;
			foreach($pengikut as $s){
				
				$pengeluaran_rill = $this->m_api->get_pengeluaran_rill($telaah_id, $s->pegawai_id, $telaah[0]['telaah_kategori']);
				
				$pengeluaran_pengikut2 = array();
				foreach($pengeluaran_rill as $o){
					$m	= $m + $o->tarif;			
				}
				
				$rincian_belanja_pengikut = $this->m_api->get_rincian($telaah_id, $s->pegawai_id, $telaah[0]['telaah_kategori']);
				$no++;
				
				$rincian_pengikut2 = array();
				foreach($rincian_belanja_pengikut as $v){
					
					$n	= $n + ($v->tarif * $v->item);			
				}
				
				$resultData[] = array('nama_pengikut'=> $s->pegawai_nama,
											'total'=> $n+$m);	
			}
			
			
			
		} else {
			$resultData[] = array('Pengikut Tidak Ada');
		}
		
		echo json_encode($resultData, JSON_PRETTY_PRINT);
		
	}
	
	### LAPORAN PERJALANAN
	public function laporan_perjalanan($telaah_id)
	{
		// required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$telaah = $this->m_api->get_telaah($telaah_id);
		
		$laporan_perjalanan = $this->m_api->laporan_perjalanan($telaah_id);
		
		$n = 0;
		if($laporan_perjalanan){
			foreach($laporan_perjalanan as $v){
				
				$resultData[] = array(
						'nama_laporan'=> $v->laporanperjalanan_name,
						'deskripsi'=> $v->laporanperjalanan_desc,
						'tanggal'=> $v->laporanperjalanan_date,
						'nama_file'=> $v->laporanperjalanan_file,
						'url'=> base_url().'upload/laporan_perjalanan/'.$v->laporanperjalanan_file,
						);
			}
			
			
		} else {
			$resultData[] = array('Data Laporan Perjalan Tidak Ada');
		}
			
		
		echo json_encode($resultData, JSON_PRETTY_PRINT);
		
	}	
	
	### PENGELUARAN RILL PELAKSANA TELAAH
	public function pengeluaran_pelaksana($telaah_id)
	{
		// required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$telaah = $this->m_api->get_telaah($telaah_id);
		
		$pengeluaran_rill= $this->m_api->get_pengeluaran_rill($telaah_id, $telaah[0]['telaah_pelaksana'], $telaah[0]['telaah_kategori']);
		
			foreach($pengeluaran_rill as $v){
				$resultData[] = array(
						'keterangan'=> $v->uraian,
						'tarif'=> $v->tarif);
			}
		
		echo json_encode($resultData, JSON_PRETTY_PRINT);
		
	}	
	
	### PENGELUARAN RILL PENGIKUT TELAAH
	public function pengeluaran_pengikut($telaah_id)
	{
		// required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		### Rincian Belanja Pengikut
		
		$telaah = $this->m_api->get_telaah($telaah_id);
		
		$pengikut = $this->m_api->get_pengikut('','',$telaah_id, $telaah[0]['telaah_kategori']);
		
		$pengeluaran_pengikut = array();
		$no = 0;
		foreach($pengikut as $s){
			$pengeluaran_rill = $this->m_api->get_pengeluaran_rill($telaah_id, $s->pegawai_id, $telaah[0]['telaah_kategori']);
			$no++;
			
			$pengeluaran_pengikut2 = array();
			foreach($pengeluaran_rill as $v){
				
				$pengeluaran_pengikut2[] = array(
						'pegawai_id'=> $v->pegawai_id,
						'keterangan'=> $v->uraian,
						'tarif'=> $v->tarif);
				
			}
			
			$pengeluaran_pengikut[] = array('no'=> $no,
										'nama_pengikut'.$no=> $s->pegawai_nama,
										'pengeluaran_pengikut'.$no=> $pengeluaran_pengikut2);	
		}
		
		$resultData = array();
			$resultData[] = array('pengeluaran_pengikut' =>$pengeluaran_pengikut);
			
		
		echo json_encode($resultData);
		
	}	
	
		### TELAAH MASUK
	public function jumlah_telaah($user_id)
	{
		//required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$user = $this->m_api->user($user_id);
		
		$limit = $this->input->get('limit');
		$start = $this->input->get('start');
		$order_by = $this->input->get('order_by');
		$key = $this->input->get('key');
		
			switch($user[0]['group_id']){
				case "2" 			: $sekda = $this->m_relasi_sekda->getkabag($user[0]['id']);
									  if ($user[0]['group_id'] == 2 && $user[0]['jenis_skpd'] == 2){
											$telaah_masuk = $this->m_api->kabid_dprd('', '', '', $key);
											$telaah_disetujui = $this->m_api->kabid_dprd_disetujui('', '', '', $key);
											$telaah_ditolak = $this->m_api->kabid_dprd_ditolak('', '', '', $key);
									  } else if ($user[0]['group_id'] == 2 && $user[0]['jenis_skpd'] == 3){
											if($user[0]['id'] == 638){
												$total_list_telaah = $this->m_api->kabid_sekda('', '', '', $sekda[0]['bagian_id'],$key, $user[0]['id']);
												$total_list_telaah_diterima = $this->m_api->kabid_sekda_disetujui('', '', '', $sekda[0]['bagian_id'], $key,$user[0]['id']);
												$total_list_telaah_ditolak = $this->m_api->kabid_sekda_ditolak('', '', '', $sekda[0]['bagian_id'],$key, $user[0]['id']);
												$telaah_masuk = $total_list_telaah[0]['numrows']+$total_list_telaah[1]['numrows'];
												$telaah_disetujui = $total_list_telaah_diterima[0]['numrows']+$total_list_telaah_diterima[1]['numrows'];
												$telaah_ditolak = $total_list_telaah_ditolak[0]['numrows']+$total_list_telaah_ditolak[1]['numrows'];
											} else {
												$telaah_masuk = $this->m_api->kabid_sekda('', '', '', $sekda[0]['bagian_id'],$key, $user[0]['id']);
												$telaah_disetujui = $this->m_api->kabid_sekda_disetujui('', '', '', $sekda[0]['bagian_id'], $key,$user[0]['id']);
												$telaah_ditolak = $this->m_api->kabid_sekda_ditolak('', '', '', $sekda[0]['bagian_id'],$key, $user[0]['id']);
											}											
									  } else if ($user[0]['group_id'] == 2 && $user[0]['jenis_skpd'] == 10){
											$telaah_masuk = $this->m_api->kabid_dinkes('', '', '', $key);
											$telaah_disetujui = $this->m_api->kabid_dinkes_disetujui('', '', '', $key);
											$telaah_ditolak = $this->m_api->kabid_dinkes_ditolak('', '', '', $key);
									  } else{	
											$telaah_masuk = $this->m_api->kabid_opd('', '', '', $user[0]['skpd_id'],$key);
											$telaah_disetujui = $this->m_api->kabid_opd_disetujui('', '', '', $user[0]['skpd_id'],$key);
											$telaah_ditolak = $this->m_api->kabid_opd_ditolak('', '', '', $user[0]['skpd_id'],$key);
									  }
									  break;
				case "3" 			: if ($user[0]['jenis_skpd'] == 10){
											$telaah_masuk = $this->m_api->sekdis_dinkes('', '', '', $key);
											$telaah_disetujui = $this->m_api->sekdis_dinkes_disetujui('', '', '', $key);
											$telaah_ditolak = $this->m_api->sekdis_dinkes_ditolak('', '', '', $key);
									  } else {
											$telaah_masuk = $this->m_api->sekdis('', '', '', $user[0]['skpd_id'], $key);
											$telaah_disetujui = $this->m_api->sekdis_disetujui('', '', '', $user[0]['skpd_id'], $key);
											$telaah_ditolak = $this->m_api->sekdis_ditolak('', '', '', $user[0]['skpd_id'], $key);
									  }
									  break;
				case "4" 			: if ($user[0]['jenis_skpd'] == 10){
											$telaah_masuk = $this->m_api->kadis_dinkes('', '', '', $key);
											$telaah_disetujui = $this->m_api->sekdis_dinkes_disetujui('', '', '', $key);
											$telaah_ditolak = $this->m_api->sekdis_dinkes_ditolak('', '', '', $key);
									  } else {	
											$telaah_masuk = $this->m_api->kadis('', '', '', $user[0]['skpd_id'], $key);
											$telaah_disetujui = $this->m_api->kadis_disetujui('', '', '', $user[0]['skpd_id'], $key);
											$telaah_ditolak = $this->m_api->kadis_ditolak('', '', '', $user[0]['skpd_id'], $key);
									  }
									  break;
				case "6" 			: $total_list_telaah = $this->m_api->sekda('', '', '', $key);
									  $total_list_telaah_diterima = $this->m_api->sekda_disetujui('', '', '', $key);
									  $total_list_telaah_ditolak = $this->m_api->sekda_ditolak('', '', '', $key);
									  $telaah_masuk = $total_list_telaah[0]['numrows']+$total_list_telaah[1]['numrows'];
									  $telaah_disetujui = $total_list_telaah_diterima[0]['numrows']+$total_list_telaah_diterima[1]['numrows'];
									  $telaah_ditolak = $total_list_telaah_ditolak[0]['numrows']+$total_list_telaah_ditolak[1]['numrows'];
									  break;
				case "5" 			: $sekda = $this->m_relasi_sekda->getasisten($this->ion_auth->user()->row()->id);
									  $telaah_masuk = $this->m_api->asisten('', '', '', $sekda[0]['asisten_id'], $key);
									  $telaah_disetujui = $this->m_api->asisten_disetujui('', '', '', $sekda[0]['asisten_id'], $key);
									  $telaah_ditolak = $this->m_api->asisten_ditolak('', '', '', $sekda[0]['asisten_id'], $key);
									  break;
				case "7" 			: $telaah_masuk = $this->m_api->kadprd('', '', '', $key);
									  $telaah_disetujui = $this->m_api->kadprd_disetujui('', '', '', $key);
									  $telaah_ditolak = $this->m_api->kadprd_ditolak('', '', '', $key);
									  break;
				case "16" 			: $telaah_masuk = $this->m_api->kapus('', '', '', $user[0]['skpd_id'], $key);
									  $telaah_disetujui = $this->m_api->kapus_disetujui('', '', '', $user[0]['skpd_id'], $key);
									  $telaah_ditolak = $this->m_api->kapus_ditolak('', '', '', $user[0]['skpd_id'], $key);
									  break;
				case "10" 			: $telaah_masuk = $this->m_api->sekwan('', '', '', $key);
									  $telaah_disetujui = $this->m_api->sekwan_disetujui('', '', '', $key);
									  $telaah_ditolak = $this->m_api->sekwan_ditolak('', '', '', $key);
									  break;
				case "8" 			: $total_list_telaah = $this->m_api->walikota('', '', '', $key);
									  $total_list_telaah_diterima = $this->m_api->walikota_disetujui('', '', '', $key);
									  $total_list_telaah_ditolak = $this->m_api->walikota_ditolak('', '', '', $key);
									  $telaah_masuk = $total_list_telaah[0]['numrows']+$total_list_telaah[1]['numrows'];
									  $telaah_disetujui = $total_list_telaah_diterima[0]['numrows']+$total_list_telaah_diterima[1]['numrows'];
									  $telaah_ditolak = $total_list_telaah_ditolak[0]['numrows']+$total_list_telaah_ditolak[1]['numrows'];
									  break;
				case "1" 			: $telaah = $this->m_api->kasubag_lurah($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  break;
				case "13" 			: $telaah = $this->m_api->kasubag_lurah($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  break;
				case "12" 			: $telaah = $this->m_api->sekcam($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  break;
				case "11" 			: $telaah = $this->m_api->camat($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  break;
			}
			
        // switch($user[0]['group_id']){
				// case "2" 			: $sekda = $this->m_relasi_sekda->getkabag($user[0]['id']);
									  // if ($user[0]['group_id'] == 2 && $user[0]['skpd_id'] == 2){
										  // $telaah = $this->m_api->kabid_dprd_disetujui($order_by, $limit, $start, $key);
									  // } else if ($user[0]['group_id'] == 2 && $user[0]['skpd_id'] == 3){
										  // $telaah = $this->m_api->kabid_sekda_disetujui($order_by, $limit, $start, $sekda[0]['bagian_id'], $key,$user[0]['id']);
									  // } else if ($user[0]['group_id'] == 2 && $user[0]['jenis_skpd'] == 10){
										  // $telaah = $this->m_api->kabid_dinkes_disetujui($order_by, $limit, $start, $key);
									  // } else{		
										  // $telaah = $this->m_api->kabid_opd_disetujui($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  // }
									  // break;
				// case "3" 			: if ($user[0]['jenis_skpd'] == 10){
										 // $telaah = $this->m_api->sekdis_dinkes_disetujui($order_by, $limit, $start, $key);
									  // } else {	
										 // $telaah = $this->m_api->sekdis_disetujui($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  // }
									  // break;
				// case "4" 			: if ($user[0]['jenis_skpd'] == 10){
											// $telaah = $this->m_api->kadis_dinkes_disetujui($order_by, $limit, $start, $key);
									  // } else {	
											// $telaah = $this->m_api->kadis_disetujui($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  // }
									  // break;
				// case "6" 			: $telaah = $this->m_api->sekda_disetujui($order_by, $limit, $start, $key);
									  // break;
				// case "5" 			: $sekda = $this->m_relasi_sekda->getasisten($this->ion_auth->user()->row()->id);
									  // $telaah = $this->m_api->asisten_disetujui($order_by, $limit, $start, $sekda[0]['asisten_id'], $key);
									  // break;
				// case "7" 			: $telaah = $this->m_api->kadprd_disetujui($order_by, $limit, $start, $key);
									  // break;
				// case "16" 			: $telaah = $this->m_api->kapus_disetujui($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  // break;
				// case "10" 			: $telaah = $this->m_api->sekwan_disetujui($order_by, $limit, $start, $key);
									  // break;
				// case "8" 			: $telaah = $this->m_api->walikota_disetujui($order_by, $limit, $start, $key);
									  // break;
				// case "1" 			: $telaah = $this->m_api->kasubag_lurah_disetujui($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  // break;
				// case "13" 			: $telaah = $this->m_api->kasubag_lurah_disetujui($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  // break;
				// case "12" 			: $telaah = $this->m_api->sekcam_disetujui($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  // break;
				// case "11" 			: $telaah = $this->m_api->camat_disetujui($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  // break;
			// }
		$resultData = array();
			
				$resultData[] = array('telaah_masuk'=> $telaah_masuk,
										'telaah_disetujui'=> $telaah_disetujui,
										'telaah_ditolak'=> $telaah_ditolak
									);
				
	
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
		
	}	
	
		### TELAAH MASUK
	public function telaah_masuk($user_id)
	{
		//required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$user = $this->m_api->user($user_id);
		
		$limit = $this->input->get('limit');
		$start = $this->input->get('start');
		$order_by = $this->input->get('order_by');
		$key = $this->input->get('key');
		
			switch($user[0]['group_id']){
				case "2" 			: $sekda = $this->m_relasi_sekda->getkabag($user[0]['id']);
									  if ($user[0]['group_id'] == 2 && $user[0]['jenis_skpd'] == 2){
											$telaah = $this->m_api->kabid_dprd($order_by, $limit, $start, $key);
									  } else if ($user[0]['group_id'] == 2 && $user[0]['jenis_skpd'] == 3){
											$telaah = $this->m_api->kabid_sekda($order_by, $limit, $start, $sekda[0]['bagian_id'],$key, $user[0]['id']);
									  } else if ($user[0]['group_id'] == 2 && $user[0]['jenis_skpd'] == 10){
											$telaah = $this->m_api->kabid_dinkes($order_by, $limit, $start, $key);
									  } else{	
											$telaah = $this->m_api->kabid_opd($order_by, $limit, $start, $user[0]['skpd_id'],$key);
									  }
									  break;
				case "3" 			: if ($user[0]['jenis_skpd'] == 10){
											$telaah = $this->m_api->sekdis_dinkes($order_by, $limit, $start, $key);
									  } else {
											$telaah = $this->m_api->sekdis($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  }
									  break;
				case "4" 			: if ($user[0]['jenis_skpd'] == 10){
											$telaah = $this->m_api->kadis_dinkes($order_by, $limit, $start, $key);
									  } else {	
											$telaah = $this->m_api->kadis($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  }
									  break;
				case "6" 			: $telaah = $this->m_api->sekda($order_by, $limit, $start, $key);
									  break;
				case "5" 			: $sekda = $this->m_relasi_sekda->getasisten($this->ion_auth->user()->row()->id);
									  $telaah = $this->m_api->asisten($order_by, $limit, $start, $sekda[0]['asisten_id'], $key);
									  break;
				case "7" 			: $telaah = $this->m_api->kadprd($order_by, $limit, $start, $key);
									  break;
				case "16" 			: $telaah = $this->m_api->kapus($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  break;
				case "10" 			: $telaah = $this->m_api->sekwan($order_by, $limit, $start, $key);
									  break;
				case "8" 			: $telaah = $this->m_api->walikota($order_by, $limit, $start, $key);
									  break;
				case "1" 			: $telaah = $this->m_api->kasubag_lurah($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  break;
				case "13" 			: $telaah = $this->m_api->kasubag_lurah($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  break;
				case "12" 			: $telaah = $this->m_api->sekcam($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  break;
				case "11" 			: $telaah = $this->m_api->camat($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  break;
			}
			
        
		$resultData = array();
			foreach($telaah as $v){
				$rincian_belanja = $this->m_api->count_rincian_belanja($v->telaah_id);
				$pengeluaran_rill = $this->m_api->count_pengeluaran_rill($v->telaah_id);
				
				$rincian = $rincian_belanja + $pengeluaran_rill;
				$laporan_perjalanan = $this->m_api->count_laporan_perjalanan($v->telaah_id);
				
				if($rincian > 0){
					$status_rincian = "1";
					$hasil_rincian = "Sudah Realisasi";
				} else {
					$status_rincian = "0";
					$hasil_rincian = "Belum Realisasi";
				}
				
				if($laporan_perjalanan > 0){
					$status_laporan = "1";
					$hasil_laporan_perjalanan = "Sudah Upload laporan";
				} else {
					$status_laporan = "0";
					$hasil_laporan_perjalanan = "Belum Upload laporan";
				}
				
				$date = substr($v->telaah_waktuinput, 0, 10);
				$time = substr($v->telaah_waktuinput, 11, 19);
				$telaah_waktuinput =  $this->date_indo($date);
				
				$date2 = substr($v->telaah_tanggalberangkat, 0, 10);
				$telaah_tanggalberangkat =  $this->date_indo($date2);
				
				$date3 = substr($v->telaah_tanggalkembali, 0, 10);
				$telaah_tanggalkembali =  $this->date_indo($date3);
				
				$kota_tujuan = $this->m_api->get_kota_tujuan($v->telaah_kotatujuan);
				
				$resultData[] = array('telaah_id'=> $v->telaah_id,
									'telaah_perihal'=> $v->telaah_perihal,
									'telaah_waktuinput'=> $telaah_waktuinput.' '.$time,
									'user_id'=> $v->user_id,
									'telaah_kategori'=> $v->telaah_kategori,
									'pegawai_id'=> $v->pegawai_id,
									'pegawai_nip'=> $v->pegawai_nip,
									'pegawai_nama'=> $v->pegawai_nama,
									'pegawai_jabatan'=> $v->pegawai_jabatan,
									'pegawai_namajabatan'=> $v->pegawai_namajabatan,
									'skpd_nama'=> $v->skpd_nama,
									'telaah_tempatberangkat'=> $v->telaah_tempatberangkat,
									'telaah_tanggalberangkat'=> $telaah_tanggalberangkat,
									'telaah_tanggalkembali'=> $telaah_tanggalkembali,
									'telaah_kotatujuan'=> $kota_tujuan,
									'telaah_kantortujuan'=> $v->telaah_kantortujuan,
									'status_rincian' => $status_rincian, 
									'hasil_rincian' => $hasil_rincian, 
									'status_laporan' => $status_laporan, 
									'hasil_laporan_perjalanan' => $hasil_laporan_perjalanan
									);
				
			}
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
		
	}	
	
	### TELAAH DISETUJUI
	public function telaah_disetujui($user_id)
	{
		//required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$user = $this->m_api->user($user_id);
		
		$limit = $this->input->get('limit');
		$start = $this->input->get('start');
		$order_by = $this->input->get('order_by');
		$key = $this->input->get('key');
		
			switch($user[0]['group_id']){
				case "2" 			: $sekda = $this->m_relasi_sekda->getkabag($user[0]['id']);
									  if ($user[0]['group_id'] == 2 && $user[0]['skpd_id'] == 2){
										  $telaah = $this->m_api->kabid_dprd_disetujui($order_by, $limit, $start, $key);
									  } else if ($user[0]['group_id'] == 2 && $user[0]['skpd_id'] == 3){
										  $telaah = $this->m_api->kabid_sekda_disetujui($order_by, $limit, $start, $sekda[0]['bagian_id'], $key,$user[0]['id']);
									  } else if ($user[0]['group_id'] == 2 && $user[0]['jenis_skpd'] == 10){
										  $telaah = $this->m_api->kabid_dinkes_disetujui($order_by, $limit, $start, $key);
									  } else{		
										  $telaah = $this->m_api->kabid_opd_disetujui($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  }
									  break;
				case "3" 			: if ($user[0]['jenis_skpd'] == 10){
										 $telaah = $this->m_api->sekdis_dinkes_disetujui($order_by, $limit, $start, $key);
									  } else {	
										 $telaah = $this->m_api->sekdis_disetujui($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  }
									  break;
				case "4" 			: if ($user[0]['jenis_skpd'] == 10){
											$telaah = $this->m_api->kadis_dinkes_disetujui($order_by, $limit, $start, $key);
									  } else {	
											$telaah = $this->m_api->kadis_disetujui($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  }
									  break;
				case "6" 			: $telaah = $this->m_api->sekda_disetujui($order_by, $limit, $start, $key);
									  break;
				case "5" 			: $sekda = $this->m_relasi_sekda->getasisten($this->ion_auth->user()->row()->id);
									  $telaah = $this->m_api->asisten_disetujui($order_by, $limit, $start, $sekda[0]['asisten_id'], $key);
									  break;
				case "7" 			: $telaah = $this->m_api->kadprd_disetujui($order_by, $limit, $start, $key);
									  break;
				case "16" 			: $telaah = $this->m_api->kapus_disetujui($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  break;
				case "10" 			: $telaah = $this->m_api->sekwan_disetujui($order_by, $limit, $start, $key);
									  break;
				case "8" 			: $telaah = $this->m_api->walikota_disetujui($order_by, $limit, $start, $key);
									  break;
				case "1" 			: $telaah = $this->m_api->kasubag_lurah_disetujui($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  break;
				case "13" 			: $telaah = $this->m_api->kasubag_lurah_disetujui($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  break;
				case "12" 			: $telaah = $this->m_api->sekcam_disetujui($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  break;
				case "11" 			: $telaah = $this->m_api->camat_disetujui($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  break;
			}
			
        
		$resultData = array();
			foreach($telaah as $v){
				$rincian_belanja = $this->m_api->count_rincian_belanja($v->telaah_id);
				$pengeluaran_rill = $this->m_api->count_pengeluaran_rill($v->telaah_id);
				
				$rincian = $rincian_belanja + $pengeluaran_rill;
				$laporan_perjalanan = $this->m_api->count_laporan_perjalanan($v->telaah_id);
				
				if($rincian > 0){
					$status_rincian = "1";
					$hasil_rincian = "Sudah Realisasi";
				} else {
					$status_rincian = "0";
					$hasil_rincian = "Belum Realisasi";
				}
				
				if($laporan_perjalanan > 0){
					$status_laporan = "1";
					$hasil_laporan_perjalanan = "Sudah Upload laporan";
				} else {
					$status_laporan = "0";
					$hasil_laporan_perjalanan = "Belum Upload laporan";
				}
				
				$date = substr($v->telaah_waktuinput, 0, 10);
				$time = substr($v->telaah_waktuinput, 11, 19);
				$telaah_waktuinput =  $this->date_indo($date);
				
				$date2 = substr($v->telaah_tanggalberangkat, 0, 10);
				$telaah_tanggalberangkat =  $this->date_indo($date2);
				
				$date3 = substr($v->telaah_tanggalkembali, 0, 10);
				$telaah_tanggalkembali =  $this->date_indo($date3);
				
				$kota_tujuan = $this->m_api->get_kota_tujuan($v->telaah_kotatujuan);
				
				$resultData[] = array('telaah_id'=> $v->telaah_id,
									'telaah_perihal'=> $v->telaah_perihal,
									'telaah_waktuinput'=> $telaah_waktuinput.' '.$time,
									'user_id'=> $v->user_id,
									'telaah_kategori'=> $v->telaah_kategori,
									'pegawai_id'=> $v->pegawai_id,
									'pegawai_nama'=> $v->pegawai_nama,
									'pegawai_namajabatan'=> $v->pegawai_namajabatan,
									'skpd_nama'=> $v->skpd_nama,
									'telaah_tempatberangkat'=> $v->telaah_tempatberangkat,
									'telaah_tanggalberangkat'=> $telaah_tanggalberangkat,
									'telaah_tanggalkembali'=> $telaah_tanggalkembali,
									'telaah_kotatujuan'=> $kota_tujuan,
									'telaah_kantortujuan'=> $v->telaah_kantortujuan,
									'status_rincian' => $status_rincian, 
									'hasil_rincian' => $hasil_rincian, 
									'status_laporan' => $status_laporan, 
									'hasil_laporan_perjalanan' => $hasil_laporan_perjalanan
									);
				
			}
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
		
	}	
	
	### TELAAH DITOLAK
	public function telaah_ditolak($user_id)
	{
		//required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$user = $this->m_api->user($user_id);
		
		$limit = $this->input->get('limit');
		$start = $this->input->get('start');
		$order_by = $this->input->get('order_by');
		$key = $this->input->get('key');
		
			switch($user[0]['group_id']){
				case "2" 			: $sekda = $this->m_relasi_sekda->getkabag($user[0]['id']);
									  if ($user[0]['group_id'] == 2 && $user[0]['skpd_id'] == 2){
											$telaah = $this->m_api->kabid_dprd_ditolak($order_by, $limit, $start, $key);
									  } else if ($user[0]['group_id'] == 2 && $user[0]['skpd_id'] == 3){
											$telaah = $this->m_api->kabid_sekda_ditolak($order_by, $limit, $start, $sekda[0]['bagian_id'], $key, $user[0]['id']);
									  } else if ($user[0]['group_id'] == 2 && $user[0]['jenis_skpd'] == 10){
											$telaah = $this->m_api->kabid_dinkes_ditolak($order_by, $limit, $start, $key);
									  } else{	
											$telaah = $this->m_api->kabid_opd_ditolak($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  }
									  break;
				case "3" 			: if ($user[0]['jenis_skpd'] == 10){
											$telaah = $this->m_api->sekdis_dinkes_ditolak($order_by, $limit, $start, $key);
									  } else {	
											$telaah = $this->m_api->sekdis_ditolak($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  }
									  break;
				case "4" 			: if ($user[0]['jenis_skpd'] == 10){
											$telaah = $this->m_api->kadis_dinkes_ditolak($order_by, $limit, $start, $key);
									  } else {	
											$telaah = $this->m_api->kadis_ditolak($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  }
									  break;
				case "6" 			: $telaah = $this->m_api->sekda_ditolak($order_by, $limit, $start, $key);
									  break;
				case "5" 			: $sekda = $this->m_relasi_sekda->getasisten($user[0]['id']);
									  $telaah = $this->m_api->asisten_ditolak($order_by, $limit, $start, $sekda[0]['asisten_id'], $key);
									  break;
				case "7" 			: $telaah = $this->m_api->kadprd_ditolak($order_by, $limit, $start, $key);
									  break;
				case "16" 			: $telaah = $this->m_api->kapus_ditolak($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  break;
				case "10" 			: $telaah = $this->m_api->sekwan_ditolak($order_by, $limit, $start, $key);
									  break;
				case "8" 			: $telaah = $this->m_api->walikota_ditolak($order_by, $limit, $start, $key);
									  break;
				case "1" 			: $telaah = $this->m_api->kasubag_lurah_ditolak($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  break;
				case "13" 			: $telaah = $this->m_api->kasubag_lurah_ditolak($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  break;
				case "12" 			: $telaah = $this->m_api->sekcam_ditolak($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  break;
				case "11" 			: $telaah = $this->m_api->camat_ditolak($order_by, $limit, $start, $user[0]['skpd_id'], $key);
									  break;
			}
			
        
		$resultData = array();
			foreach($telaah as $v){
				$rincian_belanja = $this->m_api->count_rincian_belanja($v->telaah_id);
				$pengeluaran_rill = $this->m_api->count_pengeluaran_rill($v->telaah_id);
				
				$rincian = $rincian_belanja + $pengeluaran_rill;
				$laporan_perjalanan = $this->m_api->count_laporan_perjalanan($v->telaah_id);
				
				if($rincian > 0){
					$status_rincian = "1";
					$hasil_rincian = "Sudah Realisasi";
				} else {
					$status_rincian = "0";
					$hasil_rincian = "Belum Realisasi";
				}
				
				if($laporan_perjalanan > 0){
					$status_laporan = "1";
					$hasil_laporan_perjalanan = "Sudah Upload laporan";
				} else {
					$status_laporan = "0";
					$hasil_laporan_perjalanan = "Belum Upload laporan";
				}
				
				$date = substr($v->telaah_waktuinput, 0, 10);
				$time = substr($v->telaah_waktuinput, 11, 19);
				$telaah_waktuinput =  $this->date_indo($date);
				
				$date2 = substr($v->telaah_tanggalberangkat, 0, 10);
				$telaah_tanggalberangkat =  $this->date_indo($date2);
				
				$date3 = substr($v->telaah_tanggalkembali, 0, 10);
				$telaah_tanggalkembali =  $this->date_indo($date3);
				
				$kota_tujuan = $this->m_api->get_kota_tujuan($v->telaah_kotatujuan);
				
				$resultData[] = array('telaah_id'=> $v->telaah_id,
									'telaah_perihal'=> $v->telaah_perihal,
									'telaah_waktuinput'=> $telaah_waktuinput.' '.$time,
									'user_id'=> $v->user_id,
									'telaah_kategori'=> $v->telaah_kategori,
									'pegawai_id'=> $v->pegawai_id,
									'pegawai_nip'=> $v->pegawai_nip,
									'pegawai_nama'=> $v->pegawai_nama,
									'pegawai_jabatan'=> $v->pegawai_jabatan,
									'pegawai_namajabatan'=> $v->pegawai_namajabatan,
									'skpd_nama'=> $v->skpd_nama,
									'telaah_tempatberangkat'=> $v->telaah_tempatberangkat,
									'telaah_tanggalberangkat'=> $telaah_tanggalberangkat,
									'telaah_tanggalkembali'=> $telaah_tanggalkembali,
									'telaah_kotatujuan'=> $kota_tujuan,
									'telaah_kantortujuan'=> $v->telaah_kantortujuan,
									'status_rincian' => $status_rincian, 
									'hasil_rincian' => $hasil_rincian, 
									'status_laporan' => $status_laporan, 
									'hasil_laporan_perjalanan' => $hasil_laporan_perjalanan
									);
				
			}
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
		
	}
	
	### PENGIKUT
	public function pengikut($telaah_id)
	{
		// required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$limit = $this->input->get('limit');
		$start = $this->input->get('start');
		
		$telaah = $this->m_api->get_telaah($telaah_id);
		
        echo json_encode($this->m_api->get_pengikut($limit, $start, $telaah_id, $telaah[0]['telaah_kategori']));
		
	}	
	
	### STATUS DISPOSISI
	public function status_disposisi($telaah_id)
	{
		// required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$telaah = $this->m_api->get_telaah($telaah_id);
		
        // Get TimeLine
			switch($telaah[0]['telaah_kategori']){
				case "1" 				: $timeline =  $this->m_esselon->getTimeline1($telaah_id); 
										  if ($telaah[0]['telaah_sekretariat']==1){
											  $disposisi1 = $timeline[0]['timeline_sekdis_id'];
											  $disposisi2 = $timeline[0]['timeline_kadis_id'];
											  $disposisi3 = "";
											  $disposisi4 = "";
											  
											  $pesan_disposisi1 = $timeline[0]['timeline_sekdis_disposisi'];
											  $pesan_disposisi2 = $timeline[0]['timeline_kadis_disposisi'];
											  $pesan_disposisi3 = "";
											  $pesan_disposisi4 = "";
											  
											  $nama_disposisi1 = "SEKRETARIS OPD";
											  $nama_disposisi2 = "KEPALA OPD";
											  $nama_disposisi3 = "";
											  $nama_disposisi4 = "";
										  } else {
											  $disposisi1 = $timeline[0]['timeline_kabid_id'];
											  $disposisi2 = $timeline[0]['timeline_sekdis_id'];
											  $disposisi3 = $timeline[0]['timeline_kadis_id'];
											  $disposisi4 = "";
											  
											  $pesan_disposisi1 = $timeline[0]['timeline_kabid_disposisi'];
											  $pesan_disposisi2 = $timeline[0]['timeline_sekdis_disposisi'];
											  $pesan_disposisi3 = $timeline[0]['timeline_kadis_disposisi'];
											  $pesan_disposisi4 = "";
											  
											  $nama_disposisi1 = "KABID / IRBAN / KABAG";
											  $nama_disposisi2 = "SEKRETARIS OPD";
											  $nama_disposisi3 = "KEPALA OPD";
											  $nama_disposisi4 = "";
										  }
										  break;
				case "2" 				: $timeline =  $this->m_kadis->getTimeline2($telaah_id); 
										  if ($telaah[0]['telaah_domainperjalanan']==3 || $telaah[0]['telaah_domainperjalanan']==4) {
											  $disposisi1 = $timeline[0]['timeline_sekdis_id'];
											  $disposisi2 = $timeline[0]['timeline_kadis_id'];
											  $disposisi3 = "";
											  $disposisi4 = "";
											  
											  $pesan_disposisi1 = $timeline[0]['timeline_sekdis_disposisi'];
											  $pesan_disposisi2 = $timeline[0]['timeline_kadis_disposisi'];
											  $pesan_disposisi3 = "";
											  $pesan_disposisi4 = "";
											  
											  $nama_disposisi1 = "SEKRETARIS OPD";
											  $nama_disposisi2 = "KEPALA OPD";
											  $nama_disposisi3 = "";
											  $nama_disposisi4 = "";
										  } else {
											  $disposisi1 = $timeline[0]['timeline_sekdis_id'];
											  $disposisi2 = $timeline[0]['timeline_kadis_id'];
											  $disposisi3 = $timeline[0]['timeline_sekda_id'];
											  $disposisi4 = $timeline[0]['timeline_walikota_id'];
											  
											  $pesan_disposisi1 = $timeline[0]['timeline_sekdis_disposisi'];
											  $pesan_disposisi2 = $timeline[0]['timeline_kadis_disposisi'];
											  $pesan_disposisi3 = $timeline[0]['timeline_sekda_disposisi'];
											  $pesan_disposisi4 = $timeline[0]['timeline_walikota_disposisi'];
											  
											  $nama_disposisi1 = "SEKRETARIS OPD";
											  $nama_disposisi2 = "KEPALA OPD";
											  $nama_disposisi3 = "SEKDA";
											  $nama_disposisi4 = "WALIKOTA";
										  }
										  break;
				case "3" 				: $timeline =  $this->m_dprd->getTimeline($telaah_id); 
										  $disposisi1 = $timeline[0]['timeline_kasubid_id'];
										  $disposisi2 = $timeline[0]['timeline_sekwan_id'];
										  $disposisi3 = $timeline[0]['timeline_kadprd_id'];
										  $disposisi4 = "";
										  
										  $pesan_disposisi1 = $timeline[0]['timeline_kasubid_disposisi'];
										  $pesan_disposisi2 = $timeline[0]['timeline_sekwan_disposisi'];
										  $pesan_disposisi3 = $timeline[0]['timeline_kadprd_disposisi'];
										  $pesan_disposisi4 = "";
											  
										  $nama_disposisi1 = "KABAG";
										  $nama_disposisi2 = "SEKRETARIS DEWAN";
										  $nama_disposisi3 = "PIMPINAN DPRD";
										  $nama_disposisi4 = "";
										  break;
				case "4" 				: $timeline =  $this->m_sekda->getTimeline($telaah_id); 
										  $disposisi1 = $timeline[0]['timeline_kabag_id'];
										  $disposisi2 = $timeline[0]['timeline_asisten_id'];
										  $disposisi3 = $timeline[0]['timeline_sekda_id'];
										  $disposisi4 = $timeline[0]['timeline_walikota_id'];
										  
										  $pesan_disposisi1 = $timeline[0]['timeline_kabag_disposisi'];
										  $pesan_disposisi2 = $timeline[0]['timeline_asisten_disposisi'];
										  $pesan_disposisi3 = $timeline[0]['timeline_sekda_disposisi'];
										  $pesan_disposisi4 = $timeline[0]['timeline_walikota_disposisi'];
											  
										  $nama_disposisi1 = "KABAG";
										  $nama_disposisi2 = "ASISTEN/KEPALA OPD";
										  $nama_disposisi3 = "SEKDA";
										  $nama_disposisi4 = "WALIKOTA";
										  break;
				case "5" 				: $timeline =  $this->m_camat->getTimeline($telaah_id); 
										  $disposisi1 = $timeline[0]['timeline_sekcam_id'];
										  $disposisi2 = $timeline[0]['timeline_camat_id'];
										  $disposisi3 = $timeline[0]['timeline_sekda_id'];
										  $disposisi4 = $timeline[0]['timeline_walikota_id'];
										  
										  $pesan_disposisi1 = $timeline[0]['timeline_sekcam_disposisi'];
										  $pesan_disposisi2 = $timeline[0]['timeline_camat_disposisi'];
										  $pesan_disposisi3 = $timeline[0]['timeline_sekda_disposisi'];
										  $pesan_disposisi4 = $timeline[0]['timeline_walikota_disposisi'];
											  
										  $nama_disposisi1 = "SEKCAM";
										  $nama_disposisi2 = "CAMAT";
										  $nama_disposisi3 = "SEKDA";
										  $nama_disposisi4 = "WALIKOTA";
										  break;
				case "6" 				: $timeline =  $this->m_staff_dprd->getTimeline($telaah_id); 
										  $disposisi1 = $timeline[0]['timeline_kabag_id'];
										  $disposisi2 = $timeline[0]['timeline_sekwan_id'];
										  $disposisi3 = "";
										  $disposisi4 = "";
										  
										  $pesan_disposisi1 = $timeline[0]['timeline_kabag_disposisi'];
										  $pesan_disposisi2 = $timeline[0]['timeline_sekwan_disposisi'];
										  $pesan_disposisi3 = "";
										  $pesan_disposisi4 = "";
											  
										  $nama_disposisi1 = "KABAG";
										  $nama_disposisi2 = "SEKRETARIS DEWAN";
										  $nama_disposisi3 = "";
										  $nama_disposisi4 = "";
										  break;
				case "7" 				: $timeline =  $this->m_staff_camat->getTimeline($telaah_id); 
										  $disposisi1 = $timeline[0]['timeline_lurah_id'];
										  $disposisi2 = $timeline[0]['timeline_sekcam_id'];
										  $disposisi3 = $timeline[0]['timeline_camat_id'];
										  $disposisi4 = "";
										  
										  $pesan_disposisi1 = $timeline[0]['timeline_lurah_disposisi'];
										  $pesan_disposisi2 = $timeline[0]['timeline_sekcam_disposisi'];
										  $pesan_disposisi3 = $timeline[0]['timeline_camat_disposisi'];
										  $pesan_disposisi4 = "";
											  
										  $nama_disposisi1 = "KASUBAG";
										  $nama_disposisi2 = "SEKCAM";
										  $nama_disposisi3 = "CAMAT";
										  $nama_disposisi4 = "";
										  break;
				case "8" 				: $timeline =  $this->m_sekda->getTimeline8($telaah_id); 
										  $disposisi1 = $timeline[0]['timeline_kabag_id'];
										  $disposisi2 = $timeline[0]['timeline_sekda_id'];
										  $disposisi3 = $timeline[0]['timeline_walikota_id'];
										  $disposisi4 = "";
										  
										  $pesan_disposisi1 = $timeline[0]['timeline_kabag_disposisi'];
										  $pesan_disposisi2 = $timeline[0]['timeline_sekda_disposisi'];
										  $pesan_disposisi3 = $timeline[0]['timeline_walikota_disposisi'];
										  $pesan_disposisi4 = "";
											  
										  $nama_disposisi1 = "KABAG";
										  $nama_disposisi2 = "SEKDA";
										  $nama_disposisi3 = "WALIKOTA";
										  $nama_disposisi4 = "";
										  break;
				case "9" 				: $timeline =  $this->m_sekda->getTimeline9($telaah_id); 
										  $disposisi1 = $timeline[0]['timeline_kabag_id'];
										  $disposisi2 = $timeline[0]['timeline_asisten_id'];
										  $disposisi3 = $timeline[0]['timeline_sekda_id'];
										  $disposisi4 = "";
										  
										  $pesan_disposisi1 = $timeline[0]['timeline_kabag_disposisi'];
										  $pesan_disposisi2 = $timeline[0]['timeline_asisten_disposisi'];
										  $pesan_disposisi3 = $timeline[0]['timeline_sekda_disposisi'];
										  $pesan_disposisi4 = "";
											  
										  $nama_disposisi1 = "KABAG";
										  $nama_disposisi2 = "ASISTEN/KEPALA OPD";
										  $nama_disposisi3 = "SEKDA";
										  $nama_disposisi4 = "";
										  break;
				case "10" 				: $timeline =  $this->m_sekwan->getTimeline10($telaah_id); 
										  $disposisi1 = $timeline[0]['timeline_kabag_id'];
										  $disposisi2 = $timeline[0]['timeline_sekwan_id'];
										  $disposisi3 = $timeline[0]['timeline_sekda_id'];
										  $disposisi4 = $timeline[0]['timeline_walikota_id'];
										  
										  $pesan_disposisi1 = $timeline[0]['timeline_kabag_disposisi'];
										  $pesan_disposisi2 = $timeline[0]['timeline_sekwan_disposisi'];
										  $pesan_disposisi3 = $timeline[0]['timeline_sekda_disposisi'];
										  $pesan_disposisi4 = $timeline[0]['timeline_walikota_disposisi'];
											  
										  $nama_disposisi1 = "KABAG";
										  $nama_disposisi2 = "SEKWAN";
										  $nama_disposisi3 = "SEKDA";
										  $nama_disposisi4 = "WALIKOTA";
										  break;
				case "11" 				: $timeline =  $this->m_kapus->getTimeline2($telaah_id); 
										  $disposisi1 = $timeline[0]['timeline_kapus_id'];
										  $disposisi2 = "";
										  $disposisi3 = "";
										  $disposisi4 = "";
										  
										  $pesan_disposisi1 = $timeline[0]['timeline_kapus_disposisi'];
										  $pesan_disposisi2 = "";
										  $pesan_disposisi3 = "";
										  $pesan_disposisi4 = "";
											  
										  $nama_disposisi1 = "KEPALA PUSKESMAS";
										  $nama_disposisi2 = "";
										  $nama_disposisi3 = "";
										  $nama_disposisi4 = "";
										  break;
			}
			
			$resultData[] = array(
								'nama_pejabat1' => $nama_disposisi1,
								'pesan_disposisi1' => $pesan_disposisi1, 
								'status_disposisi1' => $disposisi1, 
								'nama_pejabat2' => $nama_disposisi2,
								'pesan_disposisi2' => $pesan_disposisi2, 
								'status_disposisi2' => $disposisi2,
								'nama_pejabat3' => $nama_disposisi3,
								'pesan_disposisi3' => $pesan_disposisi3, 
								'status_disposisi3' => $disposisi3,
								'nama_pejabat4' => $nama_disposisi4,
								'pesan_disposisi4' => $pesan_disposisi4, 
								'status_disposisi4' => $disposisi4,
								);
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
		
	}	
	
	### HISTORY
	public function history($user_id)
	{
		// required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$user = $this->m_api->user($user_id);
		
		$limit = $this->input->get('limit');
		$start = $this->input->get('start');
        
		switch($user[0]['group_id']){
			case "2" 			: $sekda = $this->m_relasi_sekda->getkabag($user[0]['id']);
								  if ($user[0]['group_id'] == 2 && $user[0]['skpd_id'] == 2){
									  $telaah = $this->m_api->kabid_dprd_history();
								  } else if ($user[0]['group_id'] == 2 && $user[0]['skpd_id'] == 3){
									  $telaah = $this->m_api->kabid_sekda_history($sekda[0]['bagian_id']);
								  } else if ($user[0]['group_id'] == 2 && $user[0]['jenis_skpd'] == 10){
									  $telaah = $this->m_api->kabid_dinkes_history($user[0]['skpd_id']);
								  } else{		
									  $telaah = $this->m_api->kabid_opd_history($user[0]['skpd_id']);
								  }
								  break;
			case "3" 			: if ($user[0]['jenis_skpd'] == 10){
									 $telaah = $this->m_api->sekdis_dinkes_history($user[0]['skpd_id']);
								  } else {	
									 $telaah = $this->m_api->sekdis_history($user[0]['skpd_id']);
								  }
								  break;
			case "4" 			: if ($user[0]['jenis_skpd'] == 10){
										$telaah = $this->m_api->kadis_dinkes_history($user[0]['skpd_id']);
								  } else {	
										$telaah = $this->m_api->kadis_history($user[0]['skpd_id']);
								  }
								  break;
			case "6" 			: $telaah = $this->m_api->sekda_history();
								  break;
			case "5" 			: $sekda = $this->m_relasi_sekda->getasisten($this->ion_auth->user()->row()->id);
								  $telaah = $this->m_api->asisten_history($sekda[0]['asisten_id']);
								  break;
			case "7" 			: $telaah = $this->m_api->kadprd_history();
								  break;
			case "16" 			: $telaah = $this->m_api->kapus_history($user[0]['skpd_id']);
								  break;
			case "10" 			: $telaah = $this->m_api->sekwan_history();
								  break;
			case "8" 			: $telaah = $this->m_api->walikota_history();
								  break;
			case "1" 			: $telaah = $this->m_api->kasubag_lurah_history($user[0]['skpd_id']);
								  break;
			case "13" 			: $telaah = $this->m_api->kasubag_lurah_history($user[0]['skpd_id']);
								  break;
			case "12" 			: $telaah = $this->m_api->sekcam_history($user[0]['skpd_id']);
								  break;
			case "11" 			: $telaah = $this->m_api->camat_history($user[0]['skpd_id']);
								  break;
		}
		
		foreach($telaah as $v){
				$resultData[] = array('telaah_id'=> $v->telaah_id,
									'telaah_kantortujuan'=> $v->telaah_kantortujuan,
									'telaah_tanggalberangkat'=> $this->date_indo($v->telaah_tanggalberangkat),
									'telaah_tanggalkembali'=> $this->date_indo($v->telaah_tanggalkembali),
									'pegawai_id'=> $v->pegawai_id,
									'pegawai_nama'=> $v->pegawai_nama,
									'pegawai_namajabatan'=> $v->pegawai_namajabatan,
									);
				
			}
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
	}	
	
	### HISTORY PELAKSANA
	public function history_pelaksana($pegawai_id, $kategori)
	{
		// required headers
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$limit = $this->input->get('limit');
		$start = $this->input->get('start');
        
		$telaah = $this->m_api->history_pelaksana($limit, $start, $pegawai_id, $kategori);
		
		$resultData = array();
			foreach($telaah as $v){
				$rincian_belanja = $this->m_api->count_rincian_belanja($v->telaah_id);
				$pengeluaran_rill = $this->m_api->count_pengeluaran_rill($v->telaah_id);
				
				$rincian = $rincian_belanja + $pengeluaran_rill;
				$laporan_perjalanan = $this->m_api->count_laporan_perjalanan($v->telaah_id);
				
				if($rincian > 0){
					$status_rincian = "1";
					$hasil_rincian = "Sudah Realisasi";
				} else {
					$status_rincian = "0";
					$hasil_rincian = "Belum Realisasi";
				}
				
				if($laporan_perjalanan > 0){
					$status_laporan = "1";
					$hasil_laporan_perjalanan = "Sudah Upload laporan";
				} else {
					$status_laporan = "0";
					$hasil_laporan_perjalanan = "Belum Upload laporan";
				}
				
				$date = substr($v->telaah_waktuinput, 0, 10);
				$time = substr($v->telaah_waktuinput, 11, 19);
				$telaah_waktuinput =  $this->date_indo($date);
				
				$resultData[] = array('telaah_id'=> $v->telaah_id,
									'telaah_perihal'=> $v->telaah_perihal,
									'telaah_waktuinput'=> $telaah_waktuinput.' '.$time,
									'user_id'=> $v->user_id,
									'telaah_kategori'=> $v->telaah_kategori,
									'pegawai_id'=> $v->pegawai_id,
									'pegawai_nip'=> $v->pegawai_nip,
									'pegawai_nama'=> $v->pegawai_nama,
									'pegawai_jabatan'=> $v->pegawai_jabatan,
									'pegawai_namajabatan'=> $v->pegawai_namajabatan,
									'status_rincian' => $status_rincian, 
									'hasil_rincian' => $hasil_rincian, 
									'status_laporan' => $status_laporan, 
									'hasil_laporan_perjalanan' => $hasil_laporan_perjalanan
									);
				
			}
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
		
	}	
	
	### ACC
	public function acc($user_id, $telaah_id)
	{
		header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$user = $this->m_api->user($user_id);
		$telaah = $this->m_api->get_telaah($telaah_id);
		
		$data['telaah_id'] = $telaah_id;
		$data2['telaah_id'] = $telaah_id;
		$data2['telaah_status'] = 1;
		$this->m_telaah->update($data2);	
		
		### ESSELON
		 if($telaah[0]['telaah_kategori']==1){
			if($user[0]['group_id'] == 3){
				$data['timeline_sekdis_id'] = 1; 
				//$data['timeline_sekdis_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
				$data['timeline_sekdis_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekdis_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_1($data,'');
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if ($user[0]['group_id'] == 4){
				$data['timeline_kadis_id'] = 1; 
				$data['timeline_kadis_date'] = date("Y-m-d H:i:s");
				$data['timeline_kadis_disposisi'] = $this->input->post('disposisi');
				
				$data2['telaah_id'] = $telaah_id;
				$data2['telaah_status'] = 2;
				$result = $this->m_api->update_timeline_1($data, $data2);
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if ($user[0]['group_id'] == 2){
				$data['timeline_kabid_id'] = 1; 
				//$data['timeline_kabid_name'] = $this->ion_auth->user()->row()->first_name.$this->ion_auth->user()->row()->last_name; 
				$data['timeline_kabid_date'] = date("Y-m-d H:i:s");
				$data['timeline_kabid_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_1($data,'');
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}
		
		## KADIS
		} else if($telaah[0]['telaah_kategori']==2){
				
			if($user[0]['group_id'] == 3){
				
				$data['timeline_sekdis_id'] = 1; 
				$data['timeline_sekdis_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekdis_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_2($data,'');
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "17";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
				
			} else if($user[0]['group_id'] == 4){
				
				$data['timeline_kadis_id'] = 1; 
				$data['timeline_kadis_date'] = date("Y-m-d H:i:s");
				$data['timeline_kadis_disposisi'] = $this->input->post('disposisi');
				
				$data2['telaah_id'] = $telaah_id;
				$data2['telaah_status'] = 2;
				
				if($telaah[0]['telaah_domainperjalanan'] == 3 || $telaah[0]['telaah_domainperjalanan'] == 4 ){
					$result = $this->m_api->update_timeline_2($data, $data2);
				} else {
					$result = $this->m_api->update_timeline_2($data, '');
				}
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "17";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
			
					
			} else if($user[0]['group_id'] == 6){
				
				$data['timeline_sekda_id'] = 1; 
				$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekda_disposisi'] = $this->input->post('disposisi');
				// $data['timeline_walikota_id'] = 1; 
				// $data['timeline_walikota_date'] = date("Y-m-d H:i:s");
				// $data['timeline_walikota_disposisi'] = "ACC";
				
				// $data2['telaah_id'] = $telaah_id;
				// $data2['telaah_status'] = 2;
				
				$result = $this->m_api->update_timeline_2($data,'');
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "17";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
				
			}  else if($user[0]['group_id'] == 8){
				
				$data['timeline_walikota_id'] = 1; 
				$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
				$data['timeline_walikota_disposisi'] = $this->input->post('disposisi');
				
				$data2['telaah_id'] = $telaah_id;
				$data2['telaah_status'] = 2;
				
				$result = $this->m_api->update_timeline_2($data,$data2);
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "17";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else {
				
				$data['timeline_sekdis_id'] = 1; 
				$data['timeline_sekdis_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekdis_disposisi'] = $this->input->post('disposisi');
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "17";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
				$result = $this->m_api->update_timeline_2($data,'');
			}
		
		## DPRD
		} else if($telaah[0]['telaah_kategori']==3){
			
			if($user[0]['group_id'] == 2){
				
				$data['timeline_kasubid_id'] = 1; 
				$data['timeline_kasubid_date'] = date("Y-m-d H:i:s");
				$data['timeline_kasubid_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_3($data,'');
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "18";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 10){
				
				$data['timeline_sekwan_id'] = 1; 
				$data['timeline_sekwan_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekwan_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_3($data,'');
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "18";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 7){
				
				$data['timeline_kadprd_id'] = 1; 
				$data['timeline_kadprd_date'] = date("Y-m-d H:i:s");
				$data['timeline_kadprd_disposisi'] = $this->input->post('disposisi');
				
				$data2['telaah_id'] = $telaah_id;
				$data2['telaah_status'] = 2;
				
				$result = $this->m_api->update_timeline_3($data, $data2);
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "18";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} 
		
		## SEKDA
		} else if($telaah[0]['telaah_kategori']==4){
			
			if($user[0]['group_id'] == 2){
				
				$data['timeline_kabag_id'] = 1; 
				$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
				$data['timeline_kabag_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_4($data,'');
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "19";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 6){
				
				$data['timeline_sekda_id'] = 1; 
				$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekda_disposisi'] = $this->input->post('disposisi');
				// $data['timeline_walikota_id'] = 1; 
				// $data['timeline_walikota_date'] = date("Y-m-d H:i:s");
				// $data['timeline_walikota_disposisi'] = "ACC";
				
				// $data2['telaah_id'] = $telaah_id;
				// $data2['telaah_status'] = 2;
				
				$result = $this->m_api->update_timeline_4($data, '');
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "19";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 5){
				
				$data['timeline_asisten_id'] = 1; 
				$data['timeline_asisten_date'] = date("Y-m-d H:i:s");
				$data['timeline_asisten_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_4($data,'');
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "19";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}  else if($user[0]['group_id'] == 8){
				
				$data['timeline_walikota_id'] = 1; 
				$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
				$data['timeline_walikota_disposisi'] = $this->input->post('disposisi');
				
				$data2['telaah_id'] = $telaah_id;
				$data2['telaah_status'] = 2;
				
				$result = $this->m_api->update_timeline_4($data, $data2);
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "19";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}
		
		## STAFF DPRD
		} else if($telaah[0]['telaah_kategori']==6){
			
			if($user[0]['group_id'] == 2){
				
				$data['timeline_kabag_id'] = 1; 
				$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
				$data['timeline_kabag_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_6($data,'');
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "21";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}  else if($user[0]['group_id'] == 10){
				
				$data['timeline_sekwan_id'] = 1; 
				$data['timeline_sekwan_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekwan_disposisi'] = $this->input->post('disposisi');
				
				$data2['telaah_id'] = $telaah_id;
				$data2['telaah_status'] = 2;
				
				$result = $this->m_api->update_timeline_6($data, $data2);
				  
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "21";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}
		
		## LURAH DAN CAMAT
		} else if($telaah[0]['telaah_kategori']==5){
			
			if($user[0]['group_id'] == 12){
				
				$data['timeline_sekcam_id'] = 1; 
				$data['timeline_sekcam_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekcam_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_5($data,'');
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "20";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}  else if($user[0]['group_id'] == 11){
				
				$data['timeline_camat_id'] = 1; 
				$data['timeline_camat_date'] = date("Y-m-d H:i:s");
				$data['timeline_camat_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_5($data,'');
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "20";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 6){
				
				if($this->input->post('jenis_skpd')==5){
					
					$data['timeline_sekda_id'] = 1; 
					$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekda_disposisi'] = $this->input->post('disposisi');
					
					$data['timeline_walikota_id'] = 1; 
					$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
					$data['timeline_walikota_disposisi'] = "ACC";
					
					$data2['telaah_id'] = $telaah_id;
					$data2['telaah_status'] = 2;
					
					$result = $this->m_api->update_timeline_5($data, $data2);
				
				} else {
					
					$data['timeline_sekda_id'] = 1; 
					$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
					$data['timeline_sekda_disposisi'] = $this->input->post('disposisi');
					
					$result = $this->m_api->update_timeline_5($data, '');	
			
				}
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "20";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 8){
				
				$data['timeline_walikota_id'] = 1; 
				$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
				$data['timeline_walikota_disposisi'] = $this->input->post('disposisi');
				
				$data2['telaah_id'] = $telaah_id;
				$data2['telaah_status'] = 2;
				
				$result = $this->m_api->update_timeline_5($data, $data2);
			
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISIi";
				$log['kode_log_action_table'] = "20";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}
		
		## STAFF CAMAT DAN STAFF LURAH
		} else if($telaah[0]['telaah_kategori']==7){
			
			if($user[0]['group_id'] == 1 || $user[0]['group_id'] == 13){
				
				$data['timeline_lurah_id'] = 1; 
				$data['timeline_lurah_date'] = date("Y-m-d H:i:s");
				$data['timeline_lurah_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_7($data,'');
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "22";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}  else if($user[0]['group_id'] == 12){
				
				$data['timeline_sekcam_id'] = 1; 
				$data['timeline_sekcam_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekcam_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_7($data,'');
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "22";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] ==11){
				
				$data['timeline_camat_id'] = 1; 
				$data['timeline_camat_date'] = date("Y-m-d H:i:s");
				$data['timeline_camat_disposisi'] = $this->input->post('disposisi');
				
				$data2['telaah_id'] = $telaah_id;
				$data2['telaah_status'] = 2;
				
				$result = $this->m_api->update_timeline_7($data,$data2);
			
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "22";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}	
		
		## WALIKOTA
		} else if($telaah[0]['telaah_kategori']==8){
			
			if($user[0]['group_id'] == 2){
				
				$data['timeline_kabag_id'] = 1; 
				$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
				$data['timeline_kabag_disposisi'] = $this->input->post('timeline_kabag_disposisi');
				$result = $this->m_api->update_timeline_8($data,'');
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "19";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 6){
				
				$data['timeline_sekda_id'] = 1; 
				$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekda_disposisi'] = $this->input->post('timeline_sekda_disposisi');
				// $data['timeline_walikota_id'] = 1; 
				// $data['timeline_walikota_date'] = date("Y-m-d H:i:s");
				// $data['timeline_walikota_disposisi'] = "ACC";
				
				// $data2['telaah_id'] = $telaah_id;
				// $data2['telaah_status'] = 2;
				
				$result = $this->m_api->update_timeline_8($data, '');
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "19";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}  else if($user[0]['group_id'] == 8){
				
				$data['timeline_walikota_id'] = 1; 
				$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
				$data['timeline_walikota_disposisi'] = $this->input->post('timeline_walikota_disposisi');
				
				$data2['telaah_id'] = $telaah_id;
				$data2['telaah_status'] = 2;
				
				$result = $this->m_api->update_timeline_8($data, $data2);
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "19";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}
		
		##	STAFF SETDA		
		} else if($telaah[0]['telaah_kategori']==9){
			
			if($user[0]['group_id'] == 2){
				
				$data['timeline_kabag_id'] = 1; 
				$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
				$data['timeline_kabag_disposisi'] = $this->input->post('timeline_kabag_disposisi');
				$result = $this->m_api->update_timeline_9($data,'');
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "19";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 6){
				
				$data['timeline_sekda_id'] = 1; 
				$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekda_disposisi'] = $this->input->post('timeline_sekda_disposisi');
				
				$data2['telaah_id'] = $telaah_id;
				$data2['telaah_status'] = 2;
				
				$result = $this->m_api->update_timeline_9($data,$data2);
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "19";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 5){
				
				$data['timeline_asisten_id'] = 1; 
				$data['timeline_asisten_date'] = date("Y-m-d H:i:s");
				$data['timeline_asisten_disposisi'] = $this->input->post('timeline_asisten_disposisi');
				$result = $this->m_api->update_timeline_9($data,'');
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "19";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} 
		
		## SEKWAN		
		} else if($telaah[0]['telaah_kategori']==10){
			
			if($user[0]['group_id'] == 2){
				
				$data['timeline_kabag_id'] = 1; 
				$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
				$data['timeline_kabag_disposisi'] = $this->input->post('timeline_kabag_disposisi');
				$result = $this->m_api->update_timeline_10($data,'');
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "17";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 10){
				
				$data['timeline_sekwan_id'] = 1; 
				$data['timeline_sekwan_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekwan_disposisi'] = $this->input->post('timeline_sekwan_disposisi');
				$result = $this->m_api->update_timeline_10($data,'');
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "17";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 6){
				
				$data['timeline_sekda_id'] = 1; 
				$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekda_disposisi'] = $this->input->post('timeline_sekda_disposisi');
				// $data['timeline_walikota_id'] = 1; 
				// $data['timeline_walikota_date'] = date("Y-m-d H:i:s");
				// $data['timeline_walikota_disposisi'] = "ACC";
				
				// $data2['telaah_id'] = $telaah_id;
				// $data2['telaah_status'] = 2;
				
				$result = $this->m_api->update_timeline_10($data,'');
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "17";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}  else if($user[0]['group_id'] == 8){
				
				$data['timeline_walikota_id'] = 1; 
				$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
				$data['timeline_walikota_disposisi'] = $this->input->post('timeline_walikota_disposisi');
				
				$data2['telaah_id'] = $telaah_id;
				$data2['telaah_status'] = 2;
				
				$result = $this->m_api->update_timeline_10($data,$data2);
				
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "17";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} 
		
		## KAPUS
		} else if($telaah[0]['telaah_kategori']==11){
				
				$data['timeline_kapus_id'] = 1; 
				$data['timeline_kapus_date'] = date("Y-m-d H:i:s");
				$data['timeline_kapus_disposisi'] = $this->input->post('timeline_kapus_disposisi');
				
				$data2['telaah_id'] = $telaah_id;
				$data2['telaah_status'] = 2;
				
				$result = $this->m_api->update_timeline_11($data, $data2);
				  
				$log['kode_log_action'] = "57";
				$log['id_user'] = $user_id;
				$log['action'] = "ACC/DISPOSISI";
				$log['kode_log_action_table'] = "18";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
		}
		 
		 if($result){
			$resultData = array('status' => true, 'message' => 'ACC Berhasil');
		} else {
			$resultData = array('status' => false, 'message' => 'ACC Gagal');
		}

		//ngirim api dulu
		//$device_id = $this->input->pos('token');
		//$device_id = "cdX4eOFiDYg:APA91bHPFK6mRBqKWo2mVrFIaKNCzNE9Ik__5niQ_NgGtn2mg48_LyIf5b4gU0J6LNEgBnZV1V8m4k7XHruzbj6dknEDmL1P6qfhXiRb-63VAsQ51IvYOky4sDuv9LTJRD-SPlvSKSE3";
		$device_id = $this->input->post('token');
		$nama = $this->input->post('nama');
		$dinas = $this->input->post('dinas');
		$tanggal = $this->input->post('tanggal');
		$tujuan = $this->input->post('tujuan');
		$telaah_id = $this->input->post('telaah_id');
		$telaah_kategori = $this->input->post('telaah_kategori');

		//$token = $this->m_api->getNextToken($telaah_id);
		// $nama = "Drs. Moh. Nur Razak";
		// $dinas = "Dinas Komunikasi dan Informatika";
		// $tanggal = "2 Desember 2019";
		// $tujuan = "Jakarta";
		
		$r = $this->push_notification_android($device_id, $nama, $dinas, $tanggal, $tujuan, $telaah_id, $telaah_kategori);
		json_encode($r);
		 //echo json_encode($resultData);
		
	}	
	
	### TOLAK
	public function tolak($user_id, $telaah_id)
	{
		header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$user = $this->m_api->user($user_id);
		$telaah = $this->m_api->get_telaah($telaah_id);
		
		$data['telaah_id'] = $telaah_id;
		$data2['telaah_id'] = $telaah_id;
		$data2['telaah_status'] = 3;
		$this->m_telaah->update($data2);	
		
		### ESSELON
		 if($telaah[0]['telaah_kategori']==1){
			if($user[0]['group_id'] == 3){
				$data['timeline_sekdis_id'] = 2;  
				$data['timeline_sekdis_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekdis_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_1($data,'');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if ($user[0]['group_id'] == 4){
				$data['timeline_kadis_id'] = 2; 
				$data['timeline_kadis_date'] = date("Y-m-d H:i:s");
				$data['timeline_kadis_disposisi'] = $this->input->post('disposisi');
				
				$result = $this->m_api->update_timeline_1($data, '');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if ($user[0]['group_id'] == 2){
				$data['timeline_kabid_id'] = 2; 
				$data['timeline_kabid_date'] = date("Y-m-d H:i:s");
				$data['timeline_kabid_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_1($data,'');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}
		
		## KADIS
		} else if($telaah[0]['telaah_kategori']==2){
				
			if($user[0]['group_id'] == 3){
				
				$data['timeline_sekdis_id'] = 2; 
				$data['timeline_sekdis_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekdis_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_2($data,'');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "17";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
				
			} else if($user[0]['group_id'] == 4){
				
				$data['timeline_kadis_id'] = 2; 
				$data['timeline_kadis_date'] = date("Y-m-d H:i:s");
				$data['timeline_kadis_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_2($data, '');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "17";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
			
					
			} else if($user[0]['group_id'] == 6){
				
				$data['timeline_sekda_id'] = 2; 
				$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekda_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_2($data,'');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "17";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
				
			}  else if($user[0]['group_id'] == 8){
				
				$data['timeline_walikota_id'] = 2; 
				$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
				$data['timeline_walikota_disposisi'] = $this->input->post('disposisi');
				
				$result = $this->m_api->update_timeline_2($data,'');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "17";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else {
				
				$data['timeline_sekdis_id'] = 2; 
				$data['timeline_sekdis_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekdis_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_2($data,'');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "17";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}
		
		## DPRD
		} else if($telaah[0]['telaah_kategori']==3){
			
			if($user[0]['group_id'] == 2){
				
				$data['timeline_kasubid_id'] = 2; 
				$data['timeline_kasubid_date'] = date("Y-m-d H:i:s");
				$data['timeline_kasubid_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_3($data,'');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "18";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 10){
				
				$data['timeline_sekwan_id'] = 2; 
				$data['timeline_sekwan_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekwan_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_3($data,'');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "18";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 7){
				
				$data['timeline_kadprd_id'] = 2; 
				$data['timeline_kadprd_date'] = date("Y-m-d H:i:s");
				$data['timeline_kadprd_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_3($data, '');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "18";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} 
		
		## SEKDA
		} else if($telaah[0]['telaah_kategori']==4){
			
			if($user[0]['group_id'] == 2){
				
				$data['timeline_kabag_id'] = 2; 
				$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
				$data['timeline_kabag_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_4($data,'');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "19";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 6){
				
				$data['timeline_sekda_id'] = 2; 
				$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekda_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_4($data, '');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "19";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 5){
				
				$data['timeline_asisten_id'] = 2; 
				$data['timeline_asisten_date'] = date("Y-m-d H:i:s");
				$data['timeline_asisten_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_4($data,'');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "19";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}  else if($user[0]['group_id'] == 8){
				
				$data['timeline_walikota_id'] = 2; 
				$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
				$data['timeline_walikota_disposisi'] = $this->input->post('disposisi');
				
				$result = $this->m_api->update_timeline_4($data, '');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "19";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}
		
		## STAFF DPRD
		} else if($telaah[0]['telaah_kategori']==6){
			
			if($user[0]['group_id'] == 2){
				
				$data['timeline_kabag_id'] = 2; 
				$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
				$data['timeline_kabag_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_6($data,'');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "21";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}  else if($user[0]['group_id'] == 10){
				
				$data['timeline_sekwan_id'] = 2; 
				$data['timeline_sekwan_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekwan_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_6($data, '');
				  
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "21";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}
		
		## LURAH DAN CAMAT
		} else if($telaah[0]['telaah_kategori']==5){
			
			if($user[0]['group_id'] == 12){
				
				$data['timeline_sekcam_id'] = 2;  
				$data['timeline_sekcam_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekcam_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_5($data,'');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "20";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}  else if($user[0]['group_id'] == 11){
				
				$data['timeline_camat_id'] = 2; 
				$data['timeline_camat_date'] = date("Y-m-d H:i:s");
				$data['timeline_camat_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_5($data,'');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "20";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 6){
				
				$data['timeline_sekda_id'] = 2;  
				$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekda_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_5($data, '');	
			
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "20";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 8){
				
				$data['timeline_walikota_id'] = 2; 
				$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
				$data['timeline_walikota_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_5($data, '');
			
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "20";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}
		
		## STAFF CAMAT DAN STAFF LURAH
		} else if($telaah[0]['telaah_kategori']==7){
			
			if($user[0]['group_id'] == 1 || $user[0]['group_id'] == 13){
				
				$data['timeline_lurah_id'] = 2; 
				$data['timeline_lurah_date'] = date("Y-m-d H:i:s");
				$data['timeline_lurah_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_7($data,'');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "22";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}  else if($user[0]['group_id'] == 12){
				
				$data['timeline_sekcam_id'] = 2; 
				$data['timeline_sekcam_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekcam_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_7($data,'');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "22";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] ==11){
				
				$data['timeline_camat_id'] = 2; 
				$data['timeline_camat_date'] = date("Y-m-d H:i:s");
				$data['timeline_camat_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_7($data,'');
			
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "22";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}	
		
		## WALIKOTA
		} else if($telaah[0]['telaah_kategori']==8){
			
			if($user[0]['group_id'] == 2){
				
				$data['timeline_kabag_id'] = 2; 
				$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
				$data['timeline_kabag_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_8($data,'');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "19";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 6){
				
				$data['timeline_sekda_id'] = 2; 
				$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekda_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_8($data, '');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "19";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}  else if($user[0]['group_id'] == 8){
				
				$data['timeline_walikota_id'] = 2; 
				$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
				$data['timeline_walikota_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_8($data, '');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "19";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}
		
		##	STAFF SETDA		
		} else if($telaah[0]['telaah_kategori']==9){
			
			if($user[0]['group_id'] == 2){
				
				$data['timeline_kabag_id'] = 2; 
				$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
				$data['timeline_kabag_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_9($data,'');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "19";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 6){
				
				$data['timeline_sekda_id'] = 2; 
				$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekda_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_9($data,'');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "19";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 5){
				
				$data['timeline_asisten_id'] = 2; 
				$data['timeline_asisten_date'] = date("Y-m-d H:i:s");
				$data['timeline_asisten_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_9($data,'');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "19";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} 
		
		## SEKWAN		
		} else if($telaah[0]['telaah_kategori']==10){
			
			if($user[0]['group_id'] == 2){
				
				$data['timeline_kabag_id'] = 2; 
				$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
				$data['timeline_kabag_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_10($data,'');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "17";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 10){
				
				$data['timeline_sekwan_id'] = 2; 
				$data['timeline_sekwan_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekwan_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_10($data,'');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "17";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 6){
				
				$data['timeline_sekda_id'] = 2; 
				$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekda_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_10($data,'');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "17";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}  else if($user[0]['group_id'] == 8){
				
				$data['timeline_walikota_id'] = 2; 
				$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
				$data['timeline_walikota_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_10($data,'');
				
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "17";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} 
		
		## KAPUS
		} else if($telaah[0]['telaah_kategori']==11){
				
				$data['timeline_kapus_id'] = 2; 
				$data['timeline_kapus_date'] = date("Y-m-d H:i:s");
				$data['timeline_kapus_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_11($data, '');
				  
				$log['kode_log_action'] = "58";
				$log['id_user'] = $user_id;
				$log['action'] = "TIDAK ACC";
				$log['kode_log_action_table'] = "18";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
		}
		 
		if($result){
			$resultData = array('status' => true, 'message' => 'Berhasil Ditolak');
		} else {
			$resultData = array('status' => false, 'message' => 'Gagal Ditolak');
		}
		
		 echo json_encode($resultData);
		
	}
	
	
	### PERBAIKAN
	public function perbaikan($user_id, $telaah_id)
	{
		header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$user = $this->m_api->user($user_id);
		$telaah = $this->m_api->get_telaah($telaah_id);
		
		$data['telaah_id'] = $telaah_id;
		$data2['telaah_id'] = $telaah_id;
		$data2['telaah_perbaikan'] = 0;
		$data2['telaah_status'] = 5;
		$this->m_telaah->update($data2);	
		
		### ESSELON
		 if($telaah[0]['telaah_kategori']==1){
			if($user[0]['group_id'] == 3){
				$data['timeline_sekdis_id'] = 5;  
				$data['timeline_sekdis_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekdis_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_1($data,'');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if ($user[0]['group_id'] == 4){
				$data['timeline_kadis_id'] = 5; 
				$data['timeline_kadis_date'] = date("Y-m-d H:i:s");
				$data['timeline_kadis_disposisi'] = $this->input->post('disposisi');
				
				$result = $this->m_api->update_timeline_1($data, '');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if ($user[0]['group_id'] == 2){
				$data['timeline_kabid_id'] = 5; 
				$data['timeline_kabid_date'] = date("Y-m-d H:i:s");
				$data['timeline_kabid_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_1($data,'');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}
		
		## KADIS
		} else if($telaah[0]['telaah_kategori']==2){
				
			if($user[0]['group_id'] == 3){
				
				$data['timeline_sekdis_id'] = 5; 
				$data['timeline_sekdis_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekdis_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_2($data,'');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
				
			} else if($user[0]['group_id'] == 4){
				
				$data['timeline_kadis_id'] = 5; 
				$data['timeline_kadis_date'] = date("Y-m-d H:i:s");
				$data['timeline_kadis_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_2($data, '');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
			
					
			} else if($user[0]['group_id'] == 6){
				
				$data['timeline_sekda_id'] = 5; 
				$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekda_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_2($data,'');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
				
			}  else if($user[0]['group_id'] == 8){
				
				$data['timeline_walikota_id'] = 5; 
				$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
				$data['timeline_walikota_disposisi'] = $this->input->post('disposisi');
				
				$result = $this->m_api->update_timeline_2($data,'');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else {
				
				$data['timeline_sekdis_id'] = 5; 
				$data['timeline_sekdis_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekdis_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_2($data,'');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}
		
		## DPRD
		} else if($telaah[0]['telaah_kategori']==3){
			
			if($user[0]['group_id'] == 2){
				
				$data['timeline_kasubid_id'] = 5; 
				$data['timeline_kasubid_date'] = date("Y-m-d H:i:s");
				$data['timeline_kasubid_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_3($data,'');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 10){
				
				$data['timeline_sekwan_id'] = 5; 
				$data['timeline_sekwan_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekwan_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_3($data,'');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 7){
				
				$data['timeline_kadprd_id'] = 5; 
				$data['timeline_kadprd_date'] = date("Y-m-d H:i:s");
				$data['timeline_kadprd_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_3($data, '');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} 
		
		## SEKDA
		} else if($telaah[0]['telaah_kategori']==4){
			
			if($user[0]['group_id'] == 2){
				
				$data['timeline_kabag_id'] = 5; 
				$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
				$data['timeline_kabag_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_4($data,'');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 6){
				
				$data['timeline_sekda_id'] = 5; 
				$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekda_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_4($data, '');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 5){
				
				$data['timeline_asisten_id'] = 5; 
				$data['timeline_asisten_date'] = date("Y-m-d H:i:s");
				$data['timeline_asisten_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_4($data,'');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}  else if($user[0]['group_id'] == 8){
				
				$data['timeline_walikota_id'] = 5; 
				$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
				$data['timeline_walikota_disposisi'] = $this->input->post('disposisi');
				
				$result = $this->m_api->update_timeline_4($data, '');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}
		
		## STAFF DPRD
		} else if($telaah[0]['telaah_kategori']==6){
			
			if($user[0]['group_id'] == 2){
				
				$data['timeline_kabag_id'] = 5; 
				$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
				$data['timeline_kabag_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_6($data,'');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}  else if($user[0]['group_id'] == 10){
				
				$data['timeline_sekwan_id'] = 5; 
				$data['timeline_sekwan_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekwan_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_6($data, '');
				  
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}
		
		## LURAH DAN CAMAT
		} else if($telaah[0]['telaah_kategori']==5){
			
			if($user[0]['group_id'] == 12){
				
				$data['timeline_sekcam_id'] = 5;  
				$data['timeline_sekcam_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekcam_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_5($data,'');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}  else if($user[0]['group_id'] == 11){
				
				$data['timeline_camat_id'] = 5; 
				$data['timeline_camat_date'] = date("Y-m-d H:i:s");
				$data['timeline_camat_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_5($data,'');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 6){
				
				$data['timeline_sekda_id'] = 5;  
				$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekda_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_5($data, '');	
			
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 8){
				
				$data['timeline_walikota_id'] = 5; 
				$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
				$data['timeline_walikota_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_5($data, '');
			
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}
		
		## STAFF CAMAT DAN STAFF LURAH
		} else if($telaah[0]['telaah_kategori']==7){
			
			if($user[0]['group_id'] == 1 || $user[0]['group_id'] == 13){
				
				$data['timeline_lurah_id'] = 5; 
				$data['timeline_lurah_date'] = date("Y-m-d H:i:s");
				$data['timeline_lurah_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_7($data,'');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}  else if($user[0]['group_id'] == 12){
				
				$data['timeline_sekcam_id'] = 5; 
				$data['timeline_sekcam_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekcam_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_7($data,'');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] ==11){
				
				$data['timeline_camat_id'] = 5; 
				$data['timeline_camat_date'] = date("Y-m-d H:i:s");
				$data['timeline_camat_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_7($data,'');
			
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}	
		
		## WALIKOTA
		} else if($telaah[0]['telaah_kategori']==8){
			
			if($user[0]['group_id'] == 2){
				
				$data['timeline_kabag_id'] = 5; 
				$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
				$data['timeline_kabag_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_8($data,'');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 6){
				
				$data['timeline_sekda_id'] = 5; 
				$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekda_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_8($data, '');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}  else if($user[0]['group_id'] == 8){
				
				$data['timeline_walikota_id'] = 5; 
				$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
				$data['timeline_walikota_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_8($data, '');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}
		
		##	STAFF SETDA		
		} else if($telaah[0]['telaah_kategori']==9){
			
			if($user[0]['group_id'] == 2){
				
				$data['timeline_kabag_id'] = 5; 
				$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
				$data['timeline_kabag_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_9($data,'');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 6){
				
				$data['timeline_sekda_id'] = 5; 
				$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekda_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_9($data,'');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 5){
				
				$data['timeline_asisten_id'] = 5; 
				$data['timeline_asisten_date'] = date("Y-m-d H:i:s");
				$data['timeline_asisten_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_9($data,'');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} 
		
		## SEKWAN		
		} else if($telaah[0]['telaah_kategori']==10){
			
			if($user[0]['group_id'] == 2){
				
				$data['timeline_kabag_id'] = 5; 
				$data['timeline_kabag_date'] = date("Y-m-d H:i:s");
				$data['timeline_kabag_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_10($data,'');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 10){
				
				$data['timeline_sekwan_id'] = 5; 
				$data['timeline_sekwan_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekwan_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_10($data,'');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			} else if($user[0]['group_id'] == 6){
				
				$data['timeline_sekda_id'] = 5; 
				$data['timeline_sekda_date'] = date("Y-m-d H:i:s");
				$data['timeline_sekda_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_10($data,'');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
			}  else if($user[0]['group_id'] == 8){
				
				$data['timeline_walikota_id'] = 5; 
				$data['timeline_walikota_date'] = date("Y-m-d H:i:s");
				$data['timeline_walikota_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_10($data,'');
				
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
			} 
		
		## KAPUS
		} else if($telaah[0]['telaah_kategori']==11){
				
				$data['timeline_kapus_id'] = 5; 
				$data['timeline_kapus_date'] = date("Y-m-d H:i:s");
				$data['timeline_kapus_disposisi'] = $this->input->post('disposisi');
				$result = $this->m_api->update_timeline_11($data, '');
				  
				$log['kode_log_action'] = "59";
				$log['id_user'] = $user_id;
				$log['action'] = "PERBAIKAN";
				$log['kode_log_action_table'] = "16";
				$log['action_table'] = "Tracking SPPD";
				$this->m_log->create2($log);
				
		}
		 
		if($result){
			$resultData = array('status' => true, 'message' => 'Berhasil Ditolak');
		} else {
			$resultData = array('status' => false, 'message' => 'Gagal Ditolak');
		}
		
		 echo json_encode($resultData);
		
	}
	
	## UPDATE DATA USER
	public function update_user($user_id)
	{
		$new_data = array(
			'username' => $this->input->post('username'),
			'email' => $this->input->post('email'),
			'first_name' => $this->input->post('first_name'),
			'last_name'  => $this->input->post('last_name'),     
			);
			
		if ($this->input->post('password') == $this->input->post('password_confirm')){
			if(strlen($this->input->post('password'))>=6) $new_data['password'] = $this->input->post('password');
			$this->ion_auth->update($user_id, $new_data);
			
			if($this->input->post('username') && $this->input->post('email') && $this->input->post('first_name') && $this->input->post('last_name')){
				$result = $this->ion_auth->update($user_id, $new_data);
			
				if($result){
					$resultData = array('status' => true, 'message' => 'Data Berhasil Diubah');
				} else {
					$resultData = array('status' => false, 'message' => 'Data Gagal Diubah');
				}
			} else {
				$resultData = array('status' => false, 'message' => 'Data Tidak Boleh Kosong');
			}
			
			
		} else {
			$resultData = array('status' => false, 'message' => 'Password Tidak Cocok');
		}
		
		echo json_encode($resultData);
		
	}

	## REKAP PERJALANAN
	public function rekap_perjalanan(){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$esselon = $this->m_api->countById2(1);
		$masuk_esselon = $this->m_api->countByStatus2(1,0);
		$proses_esselon = $this->m_api->countByStatus2(1,1);
		$selesai_esselon = $this->m_api->countByStatus2(1,2);
		$tolak_esselon = $this->m_api->countByStatus2(1,3);

		$kepala_opd = $this->m_api->countById2(2);
		$masuk_kepala_opd = $this->m_api->countByStatus2(2,0);
		$proses_kepala_opd = $this->m_api->countByStatus2(2,1);
		$selesai_kepala_opd = $this->m_api->countByStatus2(2,2);
		$tolak_kepala_opd = $this->m_api->countByStatus2(2,3);

		$anggota_dprd = $this->m_api->countById2(3);
		$masuk_anggota_dprd = $this->m_api->countByStatus2(3,0);
		$proses_anggota_dprd = $this->m_api->countByStatus2(3,1);
		$selesai_anggota_dprd = $this->m_api->countByStatus2(3,2);
		$tolak_anggota_dprd = $this->m_api->countByStatus2(3,3);
		
		$sekda = $this->m_api->countById2(4);
		$masuk_sekda = $this->m_api->countByStatus2(4,0);
		$proses_sekda = $this->m_api->countByStatus2(4,1);
		$selesai_sekda = $this->m_api->countByStatus2(4,2);
		$tolak_sekda = $this->m_api->countByStatus2(4,3);

		$camat_lurah = $this->m_api->countById2(5);
		$masuk_camat_lurah = $this->m_api->countByStatus2(5,0);
		$proses_camat_lurah = $this->m_api->countByStatus2(5,1);
		$selesai_camat_lurah = $this->m_api->countByStatus2(5,2);
		$tolak_camat_lurah = $this->m_api->countByStatus2(5,3);

		$staff_dprd = $this->m_api->countById2(6);
		$masuk_staff_dprd = $this->m_api->countByStatus2(6,0);
		$proses_staff_dprd = $this->m_api->countByStatus2(6,1);
		$selesai_staff_dprd = $this->m_api->countByStatus2(6,2);
		$tolak_staff_dprd = $this->m_api->countByStatus2(6,3);

		$staff_camat_lurah = $this->m_api->countById2(7);
		$masuk_staff_camat_lurah = $this->m_api->countByStatus2(7,0);
		$proses_staff_camat_lurah = $this->m_api->countByStatus2(7,1);
		$selesai_staff_camat_lurah = $this->m_api->countByStatus2(7,2);
		$tolak_staff_camat_lurah = $this->m_api->countByStatus2(7,3);

		$walikota = $this->m_api->countById2(8);
		$masuk_walikota = $this->m_api->countByStatus2(8,0);
		$proses_walikota = $this->m_api->countByStatus2(8,1);
		$selesai_walikota = $this->m_api->countByStatus2(8,2);
		$tolak_walikota = $this->m_api->countByStatus2(8,3);

		$staff_setda = $this->m_api->countById2(9);
		$masuk_staff_setda = $this->m_api->countByStatus2(9,0);
		$proses_staff_setda = $this->m_api->countByStatus2(9,1);
		$selesai_staff_setda = $this->m_api->countByStatus2(9,2);
		$tolak_staff_setda = $this->m_api->countByStatus2(9,3);

		$sekwan = $this->m_api->countById2(10);
		$masuk_sekwan = $this->m_api->countByStatus2(10,0);
		$proses_sekwan = $this->m_api->countByStatus2(10,1);
		$selesai_sekwan = $this->m_api->countByStatus2(10,2);
		$tolak_sekwan = $this->m_api->countByStatus2(10,3);

		$puskesmas = $this->m_api->countById2(11);
		$masuk_puskesmas = $this->m_api->countByStatus2(11,0);
		$proses_puskesmas = $this->m_api->countByStatus2(11,1);
		$selesai_puskesmas = $this->m_api->countByStatus2(11,2);
		$tolak_puskesmas = $this->m_api->countByStatus2(11,3);

		$resultData[] = array('esselon' => $esselon[0]['jumlah'], 
							'masuk_esselon' => $masuk_esselon[0]['jumlah'],
							'proses_esselon' => $proses_esselon[0]['jumlah'],
							'selesai_esselon' => $selesai_esselon[0]['jumlah'],
							'tolak_esselon' => $tolak_esselon[0]['jumlah'],
							
							'kepala_opd' => $kepala_opd[0]['jumlah'], 
							'masuk_kepala_opd' => $masuk_kepala_opd[0]['jumlah'],
							'proses_kepala_opd' => $proses_kepala_opd[0]['jumlah'],
							'selesai_kepala_opd' => $selesai_kepala_opd[0]['jumlah'],
							'tolak_kepala_opd' => $tolak_kepala_opd[0]['jumlah'],
							
							'anggota_dprd' => $anggota_dprd[0]['jumlah'], 
							'masuk_anggota_dprd' => $masuk_anggota_dprd[0]['jumlah'],
							'proses_anggota_dprd' => $proses_anggota_dprd[0]['jumlah'],
							'selesai_anggota_dprd' => $selesai_anggota_dprd[0]['jumlah'],
							'tolak_anggota_dprd' => $tolak_anggota_dprd[0]['jumlah'],
							
							'sekda' => $sekda[0]['jumlah'], 
							'masuk_sekda' => $masuk_sekda[0]['jumlah'],
							'proses_sekda' => $proses_sekda[0]['jumlah'],
							'selesai_sekda' => $selesai_sekda[0]['jumlah'],
							'tolak_sekda' => $tolak_sekda[0]['jumlah'],
							
							'camat_lurah' => $camat_lurah[0]['jumlah'], 
							'masuk_camat_lurah' => $masuk_camat_lurah[0]['jumlah'],
							'proses_camat_lurah' => $proses_camat_lurah[0]['jumlah'],
							'selesai_camat_lurah' => $selesai_camat_lurah[0]['jumlah'],
							'tolak_camat_lurah' => $tolak_camat_lurah[0]['jumlah'],
							
							'staff_dprd' => $staff_dprd[0]['jumlah'], 
							'masuk_staff_dprd' => $masuk_staff_dprd[0]['jumlah'],
							'proses_staff_dprd' => $proses_staff_dprd[0]['jumlah'],
							'selesai_staff_dprd' => $selesai_staff_dprd[0]['jumlah'],
							'tolak_staff_dprd' => $tolak_staff_dprd[0]['jumlah'],
							
							'staff_camat_lurah' => $staff_camat_lurah[0]['jumlah'], 
							'masuk_staff_camat_lurah' => $masuk_staff_camat_lurah[0]['jumlah'],
							'proses_staff_camat_lurah' => $proses_staff_camat_lurah[0]['jumlah'],
							'selesai_staff_camat_lurah' => $selesai_staff_camat_lurah[0]['jumlah'],
							'tolak_staff_camat_lurah' => $tolak_staff_camat_lurah[0]['jumlah'],
							
							'walikota' => $walikota[0]['jumlah'], 
							'masuk_walikota' => $masuk_walikota[0]['jumlah'],
							'proses_walikota' => $proses_walikota[0]['jumlah'],
							'selesai_walikota' => $selesai_walikota[0]['jumlah'],
							'tolak_walikota' => $tolak_walikota[0]['jumlah'],
							
							'staff_setda' => $staff_setda[0]['jumlah'], 
							'masuk_staff_setda' => $masuk_staff_setda[0]['jumlah'],
							'proses_staff_setda' => $proses_staff_setda[0]['jumlah'],
							'selesai_staff_setda' => $selesai_staff_setda[0]['jumlah'],
							'tolak_staff_setda' => $tolak_staff_setda[0]['jumlah'],
							
							'sekwan' => $sekwan[0]['jumlah'], 
							'masuk_sekwan' => $masuk_sekwan[0]['jumlah'],
							'proses_sekwan' => $proses_sekwan[0]['jumlah'],
							'selesai_sekwan' => $selesai_sekwan[0]['jumlah'],
							'tolak_sekwan' => $tolak_sekwan[0]['jumlah'],
							
							'puskesmas' => $puskesmas[0]['jumlah'], 
							'masuk_puskesmas' => $masuk_puskesmas[0]['jumlah'],
							'proses_puskesmas' => $proses_puskesmas[0]['jumlah'],
							'selesai_puskesmas' => $selesai_puskesmas[0]['jumlah'],
							'tolak_puskesmas' => $tolak_puskesmas[0]['jumlah'],
							
							);
		
		echo json_encode($resultData, JSON_PRETTY_PRINT);
    }
	
	## GRAFIK PERJALANAN
	public function grafik_perjalanan($data){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		switch($data){
			case 1 : $grafik_opd = $this->m_walikota->grafik_perjalanan(1);break;
			case 2 : $grafik_opd = $this->m_walikota->grafik_perjalanan(7);break;
			case 3 : $grafik_opd = $this->m_walikota->grafik_perjalanan(4);break;
			case 4 : $grafik_opd = $this->m_walikota->grafik_perjalanan(5);break;
			
		}
		
		foreach ($grafik_opd as $v) {
                $grafik_pdld = $this->m_walikota->grafik_pdld($v->skpd_id);
                $grafik_pddd = $this->m_walikota->grafik_pddd($v->skpd_id);
				$resultData[] = array('nama_skpd' => $grafik_pddd[0]['skpd_nama'],
									'jumlah_perjalanan_luar_daerah' => $grafik_pdld[0]['total'],
									'jumlah_perjalanan_dalam_daerah' => $grafik_pddd[0]['total']
							  );
				
              }
		echo json_encode($resultData, JSON_PRETTY_PRINT);
      
    }
	
	## GRAFIK PERJALANAN
	public function grafik_perjalanan_all($data){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		switch($data){
			case 1 : $grafik_pdld = $this->m_walikota->grafik_pdld_all(1);
					 $grafik_pddd = $this->m_walikota->grafik_pddd_all(1);break;
			case 2 : $grafik_pdld = $this->m_walikota->grafik_pdld_all(7);
					 $grafik_pddd = $this->m_walikota->grafik_pddd_all(7);break;
			case 3 : $grafik_pdld = $this->m_walikota->grafik_pdld_all(4);
					 $grafik_pddd = $this->m_walikota->grafik_pddd_all(4);break;
			case 4 : $grafik_pdld = $this->m_walikota->grafik_pdld_all(5);
					 $grafik_pddd = $this->m_walikota->grafik_pddd_all(5);break;
			
		}
		
		$resultData[] = array('jumlah_perjalanan_luar_daerah' => $grafik_pdld[0]['total'],
							'jumlah_perjalanan_dalam_daerah' => $grafik_pddd[0]['total']
					  );
				
		echo json_encode($resultData, JSON_PRETTY_PRINT);
      
    }
	
	## PETA PERJALANAN
	public function perjalanan_kepala_opd(){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$data = $this->m_api->get_prov_id(date("Y-m-d"));
		
		foreach ($data as $v){
			$telaah = $this->m_api->get_dataLive($v->provinsi_id,date("Y-m-d"));
			
			$telaah2 = array();
			foreach($telaah as $s){
				$telaah2[] = array(
						'telaah_id'=> $s->telaah_id,
						'telaah_perihal'=> $s->telaah_perihal,
						'telaah_tanggalberangkat'=> $s->telaah_tanggalberangkat,
						'telaah_tanggalkembali'=> $s->telaah_tanggalkembali,
						'telaah_kategori'=> $s->telaah_kategori,
						'telaah_status'=> $s->telaah_status,
						'telaah_pelaksana'=> $s->telaah_pelaksana,
						'pegawai_nama'=> $s->pegawai_nama,
						'skpd_nama'=> $s->skpd_nama,
						'provinsi'=> $s->provinsi
						);
			}
			$resultData[] = array(
								'latitude' => $v->latitude,
								'longitude' => $v->longitude,
								'provinsi_id' => $v->provinsi_id,
								'provinsi' => $v->provinsi,
								'telaah' => $telaah2
							);
		}

		if(count($data)==0){
			$resultData[] = array(
			);
		}
		
		echo json_encode($resultData, JSON_PRETTY_PRINT);
		
    }
	
	## JUMLAH LIST PERJALANAN
	public function jumlah_list_perjalanan($kategori_id){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$limit = $this->input->get('limit');
		$start = $this->input->get('start');
		$order_by = $this->input->get('order_by');
		$key = $this->input->get('key');
		
		switch($this->uri->segment(4)){
			case "esselon" 				: $data = $this->m_api->jumlah_list_perjalanan('', 1); break;
			case "kepala_opd" 			: $data = $this->m_api->jumlah_list_perjalanan('', 2); break;
			case "dprd" 				: $data = $this->m_api->jumlah_list_perjalanan('', 3); break;
			case "sekda" 				: $data = $this->m_api->jumlah_list_perjalanan('', 4); break;
			case "camat_lurah" 			: $data = $this->m_api->jumlah_list_perjalanan('', 5); break;
			case "sekretariat_dprd" 	: $data = $this->m_api->jumlah_list_perjalanan('', 6); break;
			case "staff_camat_lurah" 	: $data = $this->m_api->jumlah_list_perjalanan('', 7); break;
			case "walikota" 			: $data = $this->m_api->jumlah_list_perjalanan('', 8); break;
			case "staff_setda" 			: $data = $this->m_api->jumlah_list_perjalanan('', 9); break;
			case "sekwan" 				: $data = $this->m_api->jumlah_list_perjalanan('', 10); break;
			case "puskesmas" 			: $data = $this->m_api->jumlah_list_perjalanan('', 11); break;
		}
		$resultData = array();
		$resultData[] = array('jumlah_perjalanan' => $data);
	
		echo json_encode($resultData, JSON_PRETTY_PRINT);
    }
	
	## LIST PERJALANAN
	public function list_perjalanan($kategori_id){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$limit = $this->input->get('limit');
		$start = $this->input->get('start');
		$order_by = $this->input->get('order_by');
		$key = $this->input->get('key');
		
		switch($this->uri->segment(4)){
			case "walikota" 			: $data = $this->m_api->list_perjalanan('',8, $order_by, $limit, $start, $key); break;
			case "sekda" 				: $data = $this->m_api->list_perjalanan(3, 4, $order_by, $limit, $start, $key); break;
			case "dprd" 				: $data = $this->m_api->list_perjalanan('', 3, $order_by, $limit, $start, $key); break;
			case "kepala_opd" 			: $data = $this->m_api->list_perjalanan('', 2, $order_by, $limit, $start, $key); break;
			case "sekretariat_daerah" 	: $data = $this->m_api->list_perjalanan('', 4, $order_by, $limit, $start, $key); break;
			case "sekretariat_dprd" 	: $data = $this->m_api->list_perjalanan('', 6, $order_by, $limit, $start, $key); break;
			case "opd" 					: $data = $this->m_api->list_perjalanan('', 1, $order_by, $limit, $start, $key); break;
			case "camat_lurah" 			: $data = $this->m_api->list_perjalanan('', 7, $order_by, $limit, $start, $key); break;
		}
		
		$resultData = array();
		
		foreach($data as $v){
			$resultData[] = array('telaah_id' => $v->telaah_id,
								'telaah_perihal' => $v->telaah_perihal,
								'telaah_tanggalberangkat' => $v->telaah_tanggalberangkat,
								'telaah_tanggalkembali' => $v->telaah_tanggalkembali,
								'telaah_provinsitujuan' => $v->telaah_provinsitujuan,
								'telaah_kategori' => $v->telaah_kategori,
								'telaah_status' => $v->telaah_status,
								'telaah_pelaksana' => $v->telaah_pelaksana,
								'pegawai_nama' => $v->pegawai_nama,
								'skpd_nama' => $v->skpd_nama,
								'latitude' => $v->latitude,
								'longitude' => $v->longitude,
								'provinsi' => $v->provinsi
							);
		}			
		
		echo json_encode($resultData, JSON_PRETTY_PRINT);
    }
	
	## PERJALANAN BULAN
	public function perjalanan_bulan(){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$januari 	= $this->m_api->count_month(1,'','','','','','','','','','','');
		$februari 	= $this->m_api->count_month('',2,'','','','','','','','','','');
		$maret 		= $this->m_api->count_month('','',3,'','','','','','','','','');
		$april 		= $this->m_api->count_month('','','',4,'','','','','','','','');
		$mei 		= $this->m_api->count_month('','','','',5,'','','','','','','');
		$juni 		= $this->m_api->count_month('','','','','',6,'','','','','','');
		$juli 		= $this->m_api->count_month('','','','','','',7,'','','','','');
		$agustus	= $this->m_api->count_month('','','','','','','',8,'','','','');
		$september 	= $this->m_api->count_month('','','','','','','','',9,'','','');
		$oktober 	= $this->m_api->count_month('','','','','','','','','',10,'','');
		$november 	= $this->m_api->count_month('','','','','','','','','','',11,'');
		$desember 	= $this->m_api->count_month('','','','','','','','','','','',12);
		
		$resultData[] = array(	'1'=>$januari,
								'2'=>$februari,
								'3'=>$maret,
								'4'=>$april,
								'5'=>$mei,
								'6'=>$juni,
								'7'=>$juli,
								'8'=>$agustus,
								'9'=>$september,
								'10'=>$oktober,
								'11'=>$november,
								'12'=>$desember);
		
		echo json_encode($resultData, JSON_PRETTY_PRINT);
    }

	## PERJALANAN TRIWULAN
	public function perjalanan_triwulan(){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$triwulan1	= $this->m_api->count_triwulan(1,'','','');
		$triwulan2 	= $this->m_api->count_triwulan('',2,'','');
		$triwulan3	= $this->m_api->count_triwulan('','',3,'');
		$triwulan4	= $this->m_api->count_triwulan('','','',4);
		
		$resultData[] = array(	'1'=>$triwulan1,
								'2'=>$triwulan2,
								'3'=>$triwulan3,
								'4'=>$triwulan4);
		
		echo json_encode($resultData, JSON_PRETTY_PRINT);												
    }

	## PERJALANAN SEMESTER
	public function perjalanan_semester(){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$semester1 	= $this->m_api->count_semester(1,'');
		$semester2 	= $this->m_api->count_semester('',2);
		
		$resultData[] = array(	'1'=>$semester1,
								'2'=>$semester2);
		
		echo json_encode($resultData, JSON_PRETTY_PRINT);	
    }

	## PERJALANAN TAHUN
	public function perjalanan_tahun(){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$resultData[] = array(	'tahun'=>$this->m_api->count_tahun());
		
		echo json_encode($resultData, JSON_PRETTY_PRINT);	
    }

	## PROV PERJALANAN LUAR DAERAH TERBANYAK
	public function perjalanan_luar_daerah_prov(){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		echo json_encode($this->m_api->count_prov_luar_daerah());
    }

	## KABUPATEN/KOTA PERJALANAN LUAR DAERAH TERBANYAK
	public function perjalanan_luar_daerah_kab(){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		echo json_encode($this->m_api->count_kab());
    }
	
	public function rekap_walikota($kategori)
	{
		header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
        
		$limit = $this->input->get('limit');
		$start = $this->input->get('start');
        
		switch($kategori){
			case "1" 	: $telaah = $this->m_admin->data_esselon($limit, $start); break;
			case "2" 	: $telaah = $this->m_admin->data_kadis($limit, $start); break;
			case "3" 	: $telaah = $this->m_admin->data_dprd($limit, $start); break;
			case "4" 	: $telaah = $this->m_admin->data_sekda($limit, $start); break;
			case "5" 	: $telaah = $this->m_walikota->data_camat($limit, $start); break;
			case "6" 	: $telaah = $this->m_admin->data_staffdprd($limit, $start); break;
			case "7" 	: $telaah = $this->m_walikota->data_staffcamat($limit, $start); break;
			case "8" 	: $telaah = $this->m_admin->datawalikota2($limit, $start); break;
			case "9" 	: $telaah = $this->m_admin->data_staffsekda($limit, $start); break;
			case "10" 	: $telaah = $this->m_admin->data_sekwan($limit, $start); break;
			case "11" 	: $telaah = $this->m_admin->data_kapus($limit, $start); break;
		}
		
		$resultData = array();
			foreach($telaah as $v){
				$rincian_belanja = $this->m_api->count_rincian_belanja($v->telaah_id);
				$pengeluaran_rill = $this->m_api->count_pengeluaran_rill($v->telaah_id);
				
				$rincian = $rincian_belanja + $pengeluaran_rill;
				$laporan_perjalanan = $this->m_api->count_laporan_perjalanan($v->telaah_id);
				
				if($rincian > 0){
					$status_rincian = "1";
					$hasil_rincian = "Sudah Realisasi";
				} else {
					$status_rincian = "0";
					$hasil_rincian = "Belum Realisasi";
				}
				
				if($laporan_perjalanan > 0){
					$status_laporan = "1";
					$hasil_laporan_perjalanan = "Sudah Upload laporan";
				} else {
					$status_laporan = "0";
					$hasil_laporan_perjalanan = "Belum Upload laporan";
				}
				
				$date = substr($v->telaah_waktuinput, 0, 10);
				$time = substr($v->telaah_waktuinput, 11, 19);
				$telaah_waktuinput =  $this->date_indo($date);
				
				$date2 = substr($v->telaah_tanggalberangkat, 0, 10);
				$telaah_tanggalberangkat =  $this->date_indo($date2);
				
				$date3 = substr($v->telaah_tanggalkembali, 0, 10);
				$telaah_tanggalkembali =  $this->date_indo($date3);
				
				$kota_tujuan = $this->m_api->get_kota_tujuan($v->telaah_kotatujuan);
				
				$resultData[] = array('telaah_id'=> $v->telaah_id,
									'telaah_perihal'=> $v->telaah_perihal,
									'telaah_waktuinput'=> $telaah_waktuinput.' '.$time,
									'user_id'=> $v->user_id,
									'telaah_kategori'=> $v->telaah_kategori,
									'pegawai_id'=> $v->pegawai_id,
									'pegawai_nip'=> $v->pegawai_nip,
									'pegawai_nama'=> $v->pegawai_nama,
									'pegawai_jabatan'=> $v->pegawai_jabatan,
									'pegawai_namajabatan'=> $v->pegawai_namajabatan,
									'skpd_nama'=> $v->skpd_nama,
									'telaah_tempatberangkat'=> $v->telaah_tempatberangkat,
									'telaah_tanggalberangkat'=> $telaah_tanggalberangkat,
									'telaah_tanggalkembali'=> $telaah_tanggalkembali,
									'telaah_kotatujuan'=> $kota_tujuan,
									'telaah_kantortujuan'=> $v->telaah_kantortujuan,
									'status_rincian' => $status_rincian, 
									'hasil_rincian' => $hasil_rincian, 
									'status_laporan' => $status_laporan, 
									'hasil_laporan_perjalanan' => $hasil_laporan_perjalanan
									);
				
			}
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
	}
	
	## Notif
	public function notif($telaah_id){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		echo json_encode($this->m_api->notif($telaah_id));
    }

	## Form Passphrase
	public function passphrase($user_id,$telaah_id){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$user = $this->m_api->user($user_id);
		$telaah = $this->m_api->get_telaah($telaah_id);
		
		if(	($telaah[0]['telaah_kategori']==1 && $user[0]['group_id']==4) ||
			($telaah[0]['telaah_kategori']==2 && $user[0]['group_id']==4) ||
			($telaah[0]['telaah_kategori']==2 && $user[0]['group_id']==8 && $telaah[0]['telaah_domainperjalanan']!=3) ||
			($telaah[0]['telaah_kategori']==3 && $user[0]['group_id']==10)||
			($telaah[0]['telaah_kategori']==3 && $user[0]['group_id']==7)||
			($telaah[0]['telaah_kategori']==4 && $user[0]['group_id']==6)||
			($telaah[0]['telaah_kategori']==4 && $user[0]['group_id']==8)||
			($telaah[0]['telaah_kategori']==5 && $user[0]['group_id']==11)||
			($telaah[0]['telaah_kategori']==5 && $user[0]['group_id']==6)||
			($telaah[0]['telaah_kategori']==6 && $user[0]['group_id']==10)||
			($telaah[0]['telaah_kategori']==7 && $entry[0]['jenis_skpd']==4 && $user[0]['group_id']==11)||
			($telaah[0]['telaah_kategori']==7 && $user[0]['group_id']==13)||
			($telaah[0]['telaah_kategori']==8 && $user[0]['group_id'] == 6)||
			($telaah[0]['telaah_kategori']==8 && $user[0]['group_id'] == 8)||
			($telaah[0]['telaah_kategori']==9 && $user[0]['group_id']==6)||
			($telaah[0]['telaah_kategori']==10 && $user[0]['group_id']==10)||
			($telaah[0]['telaah_kategori']==10 && $user[0]['group_id']==8)||
			($telaah[0]['telaah_kategori']==11 && $user[0]['group_id']==16)
			){
				
			$resultData[] = array('status'=> "1",
								  'message'=> 'Input Passphrase'
								  );
			echo json_encode($resultData, JSON_PRETTY_PRINT);
		} else {
				
			$resultData[] = array('status'=> "0",
								  'message'=> 'Tidak Input Passphrase'
								  );
			echo json_encode($resultData, JSON_PRETTY_PRINT);
		}
		
    }

	## INPUT NOTIF
	public function input_notif(){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		// $user = $this->m_api->user($user_id);
		// $telaah = $this->m_api->get_telaah($telaah_id);
		
		$data['permohonan_perjalanan_dinas'] = $this->input->post('permohonan_perjalanan_dinas');
		$data['skpd_nama'] = $this->input->post('skpd_nama');
		$data['pelaksana_perjalanan'] = $this->input->post('pelaksana_perjalanan');
		$data['tanggal'] = date("Y-m-d");
		$data['telaah_id'] = $this->input->post('telaah_id');
		$data['telaah_kategori'] = $this->input->post('telaah_kategori');
		$data['user_id'] = $this->input->post('user_id');
		
		$result = $this->m_api->create_notif($data);
		
		// if($result){
			$resultData[] = array('status'=> TRUE,
								  'message'=> 'Input Notifikasi Berhasil'
								  );
			echo json_encode($resultData, JSON_PRETTY_PRINT);
		// } else {
				
			// $resultData[] = array('status'=> FALSE,
								  // 'message'=> 'Input Notifikasi Tidak Berhasil'
								  // );
			// echo json_encode($resultData, JSON_PRETTY_PRINT);
		// }
		
    }

	## GET NOTIF
	public function get_notif($user_id){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		echo json_encode($this->m_api->get_notif($user_id));
		
    }

	## GET NOTIF
	public function delete_notif($user_id){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$data['user_id'] = $user_id;
		$data['status'] = 0;
		$this->m_api->non_aktif_notif($data);
		
		$resultData[] = array('status'=> TRUE,
							  'message'=> 'Data Notifikasi Dihapus'
							  );
		echo json_encode($resultData, JSON_PRETTY_PRINT);
		
    }

	## GRAFIK PERJALANAN
	public function grafik_anggaran_opd($skpd_id){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		
		## Total Anggaran
		$total_anggaran_keseluruhan = $this->m_kadis->total_anggaran_keseluruhan($skpd_id);
		$total_anggaran_dalam_daerah = $this->m_kadis->total_anggaran_dalam_daerah($skpd_id);
		$total_anggaran_luar_daerah = $this->m_kadis->total_anggaran_luar_daerah($skpd_id);
		$total_anggaran_bimtek = $this->m_kadis->total_anggaran_bimtek($skpd_id);
		
		$rincian_belanja_skpd = $this->m_kadis->rincian_belanja_skpd($skpd_id);
		$pengeluaran_rill_skpd = $this->m_kadis->pengeluaran_rill_skpd($skpd_id);
		$anggaran_terpakai = $rincian_belanja_skpd[0]['jumlah'] + $pengeluaran_rill_skpd[0]['jumlah'];
		
		## Anggaran Dalam Daerah
		$rincian_belanja_dalam_daerah = $this->m_kadis->rincian_belanja_dalam_daerah($skpd_id);
		$pengeluaran_rill_dalam_daerah = $this->m_kadis->pengeluaran_rill_dalam_daerah($skpd_id);
		$realisasi_anggaran_dalam_daerah = $rincian_belanja_dalam_daerah[0]['jumlah'] + $pengeluaran_rill_dalam_daerah[0]['jumlah'];
		
		## Anggaran Luar Daerah
		$rincian_belanja_luar_daerah = $this->m_kadis->rincian_belanja_luar_daerah($skpd_id);
		$pengeluaran_rill_luar_daerah = $this->m_kadis->pengeluaran_rill_luar_daerah($skpd_id);
		$realisasi_anggaran_luar_daerah = $rincian_belanja_luar_daerah[0]['jumlah'] + $pengeluaran_rill_luar_daerah[0]['jumlah'];
		
		## Anggaran Bimtek
		$rincian_belanja_bimtek = $this->m_kadis->rincian_belanja_bimtek($skpd_id);
		$pengeluaran_rill_bimtek = $this->m_kadis->pengeluaran_rill_bimtek($skpd_id);
		$realisasi_anggaran_bimtek = $rincian_belanja_bimtek[0]['jumlah'] + $pengeluaran_rill_bimtek[0]['jumlah'];
		
		
		$resultData[] = array('anggaran'=> $total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan'],
							  'anggaran_terpakai'=> $anggaran_terpakai,
							  'anggaran_sisa'=> $total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan']-$anggaran_terpakai,
							  
							  'anggaran_dalam_daerah'=> $total_anggaran_dalam_daerah[0]['total_anggaran'],
							  'anggaran_terpakai_dalam_daerah'=> $realisasi_anggaran_dalam_daerah,
							  'anggaran_sisa_dalam_daerah'=> $total_anggaran_dalam_daerah[0]['total_anggaran']-$realisasi_anggaran_dalam_daerah,
									
							  'anggaran_luar_daerah'=> $total_anggaran_luar_daerah[0]['total_anggaran'],
							  'anggaran_terpakai_luar_daerah'=> $realisasi_anggaran_luar_daerah,
							  'anggaran_sisa_luar_daerah'=> $total_anggaran_luar_daerah[0]['total_anggaran']-$realisasi_anggaran_luar_daerah,
									
							  'anggaran_bimtek'=> $total_anggaran_bimtek[0]['total_anggaran'],
							  'anggaran_terpakai_bimtek'=> $realisasi_anggaran_bimtek,
							  'anggaran_sisa_bimtek'=> $total_anggaran_bimtek[0]['total_anggaran']-$realisasi_anggaran_bimtek,
									
						);
		
		echo json_encode($resultData, JSON_PRETTY_PRINT);
      
    }
	
	## ANGGARAN
	public function anggaran_opd(){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		if($this->uri->segment(4)){
			
			if($this->uri->segment(4)==3){
				$key = $this->input->get('key');
				$telaah = $this->m_api->anggaran_sekretariat($key);
		
				$resultData = array();
				foreach($telaah as $v){
					
					$sisa_anggaran =  $this->m_walikota->cek_sisa_anggaran_sekretariat($v->bagian_id);
					$sisa_anggaran =  $sisa_anggaran[0]->tes;
					
					$resultData[] = array('bagian_id'=> $v->bagian_id,
										'nama_bagian'=> $v->skpd_nama,
										'total_anggaran'=> $v->pagu,
										'sisa_anggaran'=> $v->pagu - $sisa_anggaran,
										'anggaran_terpakai'=> $sisa_anggaran
										);
					
				}				
			} else {
				$resultData = array();
			}
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
			
		} else {
			$key = $this->input->get('key');
			$telaah = $this->m_api->anggaran_opd($key);
		
			$resultData = array();
				foreach($telaah as $v){
					
					$pagu = $this->m_walikota->total_anggaran_skpd($v->skpd_id);
					$sisa_anggaran =  $this->m_dprd->cek_sisa_anggaran_skpd($v->skpd_id);
					$sisa_anggaran =  $sisa_anggaran[0]->tes;
					
					$resultData[] = array('skpd_id'=> $v->skpd_id,
										'skpd_nama'=> $v->skpd_nama,
										'total_anggaran'=> $pagu[0]['total_anggaran_keseluruhan'],
										'sisa_anggaran'=> $pagu[0]['total_anggaran_keseluruhan'] - $sisa_anggaran,
										'anggaran_terpakai'=> $sisa_anggaran
										);
					
				}
				
				echo json_encode($resultData, JSON_PRETTY_PRINT);
		}
		
      
    }
	
	public function anggaran_sekretariat(){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$key = $this->input->get('key');
		$telaah = $this->m_api->anggaran_sekretariat($key);
		
		$resultData = array();
			foreach($telaah as $v){
				
				$sisa_anggaran =  $this->m_walikota->cek_sisa_anggaran_sekretariat($v->bagian_id);
				$sisa_anggaran =  $sisa_anggaran[0]->tes;
				
				$resultData[] = array('bagian_id'=> $v->bagian_id,
									'nama_bagian'=> $v->skpd_nama,
									'total_anggaran'=> $v->pagu,
									'sisa_anggaran'=> $v->pagu - $sisa_anggaran,
									'anggaran_terpakai'=> $sisa_anggaran
									);
				
			}
			
			echo json_encode($resultData, JSON_PRETTY_PRINT);
      
    }
	
	public function detail_anggaran_opd($skpd_id){
		
		header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$key = $this->input->get('key');
		$opd = $this->m_api->detail_anggaran_opd($skpd_id,$key);
		
		$resultData = array();
		foreach($opd as $v){
				
				$anggaran_terpakai =  $this->m_dprd->cek_sisa_anggaran($v->id_anggaran);
				$anggaran_terpakai2 =  $this->m_dprd->cek_sisa_anggaran2($v->id_anggaran);
				$anggaran_terpakai = $anggaran_terpakai[0]->tes + $anggaran_terpakai2[0]->jumlah;
				
				$resultData[] = array('skpd_nama'=> $v->skpd_nama,
									'nama_program'=> $v->nama_program,
									'uraian'=> $v->uraian,
									'anggaran'=> $v->pagu,
									'sisa_anggaran'=> $v->pagu - $anggaran_terpakai,
									'anggaran_terpakai'=> $anggaran_terpakai
									);
				
			}
		
		echo json_encode($resultData, JSON_PRETTY_PRINT);
	}
	
	public function detail_anggaran_sekretariat($bagian_id){
		
		header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
		
		$key = $this->input->get('key');
		$opd = $this->m_api->detail_anggaran_sekretariat($bagian_id,$key);
		
		$resultData = array();
		foreach($opd as $v){
				
				$anggaran_terpakai =  $this->m_dprd->cek_sisa_anggaran($v->id_anggaran);
				$anggaran_terpakai2 =  $this->m_dprd->cek_sisa_anggaran2($v->id_anggaran);
				$anggaran_terpakai = $anggaran_terpakai[0]->tes + $anggaran_terpakai2[0]->jumlah;
				
				$resultData[] = array('nama_bagian'=> $v->nama_bagian,
									'nama_program'=> $v->nama_program,
									'uraian'=> $v->uraian,
									'anggaran'=> $v->pagu,
									'sisa_anggaran'=> $v->pagu - $anggaran_terpakai,
									'anggaran_terpakai'=> $anggaran_terpakai
									);
				
			}
		
		echo json_encode($resultData, JSON_PRETTY_PRINT);
	}
	
	## UPDATE DATA USER TOKEN
	public function update_usertoken($user_id)
	{
		$new_data = array(
			'token' => $this->input->post('token'),   
			);
			
			$this->db->where('id', $user_id);
			$this->db->update('users', $new_data);
		//$this->ion_auth->update($user_id, $new_data);
		$resultData = array('status' => true, 'message' => 'Data Berhasil Diubah');
		
		echo json_encode($resultData);
		
	}

		function push_notification_android($device_id,$nama, $dinas, $tanggal,$tujuan, $telaah_id, $telaah_kategori){

			//API URL of FCM
			$url = 'https://fcm.googleapis.com/fcm/send';
		
			/*api_key available in:
			Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/    
			$api_key = 'AAAAXNd7Bxw:APA91bGXHw4WTRPqoywoYqyN8JTej9byl8LMt8wtXtyK9MW2E2mT6pqUcQUjwMD5kD4yeVkuZgt-EKldAPWCc22VxQWPJyE_I4a712uTruUQH7X_UmNuoVMUBLBmG2H7dNtFpNWS5A6y';
						
			$fields = array (
				'registration_ids' => array (
						$device_id
				),
				'data' => array (
					"nama" => $nama,
					"dinas" => $dinas,
					"tanggal" => $tanggal,
					"tujuan" => $tujuan,
					"telaah_id" => $telaah_id,
					"telaah_kategori" => $telaah_kategori
					//
				)
			);
		
			//header includes Content type and api key
			$headers = array(
				'Content-Type:application/json',
				'Authorization:key='.$api_key
			);
						
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$result = curl_exec($ch);
			if ($result === FALSE) {
				die('FCM Send Error: ' . curl_error($ch));
			}
			curl_close($ch);
			return $result;
		}

}

?>