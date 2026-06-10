<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dokumen extends public_Controller {
	function __construct()
	{
		parent::__construct();
		error_reporting(0);
	}
	//View All Data
	public function index()
	{
		
		$this->data['hasil'] = "xxx"; 
		$this->render('setting_admin/dokumen/content');
	}
	
	 function build_data_files2($boundary, $files){
        $data = '';
        $eol = "\r\n";

        $delimiter = '-------------' . $boundary;

        foreach ($files as $name => $content) {
            $data .= "--" . $delimiter . $eol
                . 'Content-Disposition: form-data; name="signed_file"; filename="' . $name . '"' . $eol
                . 'Content-Type: Application/pdf'.$eol
            ;

            $data .= $eol;
            $data .= $content . $eol;
        }
        $data .= "--" . $delimiter . "--".$eol;

        return $data;
    }




	function cek(){
		$get_bearer_token = $this->getToken();
		$get_bearer_token  = json_decode($get_bearer_token , TRUE);

		$cekDokumen = $this->cekDokumen($get_bearer_token['access_token']);
		$cekDokumen  = json_decode($cekDokumen , TRUE);

		$this->data['hasil'] = $cekDokumen['SUMMARY']; 
		$this->data['details'] = $cekDokumen['details']; 
		$this->data['nama_dokumen'] = $cekDokumen['nama_dokumen']; 
		$this->data['jumlah_signature'] = $cekDokumen['jumlah_signature']; 
		$this->data['notes'] = $cekDokumen['notes'];
		foreach($cekDokumen['details'] as $item) {
			//echo 'Description: ' . $item['signature_field'].'<br />';
		}
		$this->render('setting_admin/dokumen/content');
	}
	
	function getToken(){
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://esign-dev.bssn.go.id/oauth/token?client_id=16314426&client_secret=39j1-sifb-del9-3cg6&grant_type=client_credentials",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => "",
		CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache"
				),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		return $response;
	}
	
	function cekDokumen($token){
		
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
			
			$dat = $this->upload->data();
			
			$data['file'] = $dat['file_name'];
			
			$curl = curl_init();
			//$file1 	= $_SERVER['DOCUMENT_ROOT'].'/upload/cek_dokumen/'.$data['file'];
			$file1 	= $_SERVER['DOCUMENT_ROOT'].'/sppd-tte/upload/cek dokumen/'.$data['file'];
			
			$filenames = array($file1);

			foreach ($filenames as $f){
				$files[$f] = file_get_contents($f);
			}

			$boundary = uniqid();
			$delimiter = '-------------' . $boundary;
			$post_data = $this->build_data_files2($boundary, $files);

			curl_setopt_array($curl, array(
			  
			  CURLOPT_URL => "https://esign-dev.bssn.go.id/api/v2/entity/verify/",
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
				  "Authorization: Bearer $token",
				  "cache-control:no-cache",
				  "Content-Type: multipart/form-data; boundary=" . $delimiter,
				  "Content-Length: " . strlen($post_data)
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			// if ($err) {
			  // echo "cURL Error #:" . $err;
			// } else {
			  // echo $response;
			// }
			
			return $response;
			
		}
	}
}