<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends MY_Controller {
	function __construct()
	{
		parent::__construct();
		// error_reporting(0);
		$this->load->model('setting/m_log');
		$this->load->library('ion_auth');
		$this->load->model('setting/m_login_lockout');
	}

	public function index()
	{
		$this->data['page_title'] = 'Login';
		$this->load->library('form_validation');
		$this->form_validation->set_rules('identity', 'Identity', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('remember','Remember me','integer');
		if($this->form_validation->run()===TRUE)
		{
			$identity = trim((string) $this->input->post('identity'));
			$remember = (bool) $this->input->post('remember');

			// captcha dicek lebih dulu; bila salah password tidak diperiksa
			// dan tidak dihitung sebagai percobaan login gagal
			if (!$this->cek_captcha($this->input->post('captcha')))
			{
				$this->session->set_flashdata('captcha_salah', TRUE);
				$this->session->set_flashdata('isi_identity', $identity);
				redirect('login', 'refresh');
			}

			if ($this->ion_auth->login($identity, $this->input->post('password'), $remember))
			{
				$this->session->unset_userdata('login_identity_terakhir');
				//save log
				$log['kode_log_action'] = "51";
				$log['action'] = "LOGIN";
				$log['kode_log_action_table'] = "0";
				$log['action_table'] = "";
				$this->m_log->create($log);
				//redirect
				redirect('beranda', 'refresh');
			}
			elseif (in_array('login_unsuccessful_not_active', (array) $this->ion_auth->errors_array(FALSE), TRUE))
			{
				// password benar tetapi akun dinonaktifkan super admin (tidak dihitung percobaan gagal)
				$this->session->unset_userdata('login_identity_terakhir');
				$this->session->set_flashdata('notif', 'Akun ini sudah dinonaktifkan. Hubungi administrator.');
				$this->session->set_flashdata('isi_identity', $identity);
				redirect('login', 'refresh');
			}
			else
			{
				// pesan & hitung mundur disusun ulang di halaman login dari status di database
				$this->session->set_userdata('login_identity_terakhir', $identity);
				$this->session->set_flashdata('login_gagal', TRUE);
				// username diisi kembali di form login setelah redirect
				$this->session->set_flashdata('isi_identity', $identity);
				redirect('login', 'refresh');
			}
		}
		else
		{
			$this->load->helper('form');
			$this->data['status_login'] = $this->status_login();
			$this->load->view('login', $this->data);
		}
	}

	// Dulu salinan persis index(); disatukan agar pembatasan login tidak bisa dilewati
	public function loginx()
	{
		$this->index();
	}

	// ---------------------------------------------------------------
	// Captcha login: kode dibuat & disimpan di session (server), gambar dibuat
	// di memori dengan GD (tanpa file). Bot yang langsung POST ke /login tanpa
	// membaca gambar tidak bisa lolos.
	// ---------------------------------------------------------------
	const CAPTCHA_HURUF   = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // tanpa 0/O, 1/I yang membingungkan
	const CAPTCHA_PANJANG = 5;
	const CAPTCHA_UMUR    = 300; // detik

	public function captcha()
	{
		$kode = '';
		$jumlah_huruf = strlen(self::CAPTCHA_HURUF);
		for ($i = 0; $i < self::CAPTCHA_PANJANG; $i++) {
			$kode .= self::CAPTCHA_HURUF[random_int(0, $jumlah_huruf - 1)];
		}
		$this->session->set_userdata('captcha_login', array('kode' => $kode, 'waktu' => time()));

		$lebar = 160;
		$tinggi = 50;
		$img = imagecreatetruecolor($lebar, $tinggi);
		imagefilledrectangle($img, 0, 0, $lebar, $tinggi, imagecolorallocate($img, random_int(236, 250), random_int(240, 250), random_int(244, 252)));

		// noise di belakang huruf
		for ($i = 0; $i < 6; $i++) {
			$warna = imagecolorallocate($img, random_int(140, 200), random_int(150, 205), random_int(160, 215));
			imageline($img, random_int(0, $lebar), random_int(0, $tinggi), random_int(0, $lebar), random_int(0, $tinggi), $warna);
		}
		for ($i = 0; $i < 350; $i++) {
			$warna = imagecolorallocate($img, random_int(110, 210), random_int(110, 210), random_int(110, 210));
			imagesetpixel($img, random_int(0, $lebar - 1), random_int(0, $tinggi - 1), $warna);
		}

		// huruf: diputar & digeser acak
		$font = BASEPATH . 'fonts/texb.ttf';
		$pakai_ttf = function_exists('imagettftext') && is_file($font);
		$x = 12;
		for ($i = 0; $i < self::CAPTCHA_PANJANG; $i++) {
			$warna = imagecolorallocate($img, random_int(20, 80), random_int(40, 90), random_int(90, 150));
			if ($pakai_ttf) {
				imagettftext($img, random_int(20, 24), random_int(-25, 25), $x, random_int(34, 40), $warna, $font, $kode[$i]);
				$x += random_int(26, 29);
			} else {
				imagestring($img, 5, $x, random_int(8, 26), $kode[$i], $warna);
				$x += 28;
			}
		}

		// dua garis melintasi huruf
		imagesetthickness($img, 2);
		for ($i = 0; $i < 2; $i++) {
			$warna = imagecolorallocate($img, random_int(60, 120), random_int(80, 130), random_int(120, 170));
			imageline($img, 0, random_int(10, $tinggi - 10), $lebar, random_int(10, $tinggi - 10), $warna);
		}

		ob_start();
		imagepng($img);
		$png = ob_get_clean();

		$this->output
			->set_content_type('image/png')
			->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0')
			->set_header('Pragma: no-cache')
			->set_header('X-Content-Type-Options: nosniff')
			->set_output($png);
	}

	// Sekali pakai: kode di session selalu dihapus setelah dicek (benar maupun salah)
	private function cek_captcha($jawaban)
	{
		$simpan = $this->session->userdata('captcha_login');
		$this->session->unset_userdata('captcha_login');

		$jawaban = strtoupper(trim((string) $jawaban));
		if (!is_array($simpan) || empty($simpan['kode']) || $jawaban === '') {
			return FALSE;
		}
		if (time() - (int) $simpan['waktu'] > self::CAPTCHA_UMUR) {
			return FALSE;
		}
		return hash_equals((string) $simpan['kode'], $jawaban);
	}

	/**
	 * Status untuk alert di halaman login:
	 *   jenis: '' | 'salah' | 'kunci' | 'blokir'
	 */
	private function status_login()
	{
		$identity = (string) $this->session->userdata('login_identity_terakhir');
		$baru_gagal = (bool) $this->session->flashdata('login_gagal');
		$hasil = array('jenis' => '', 'sisa_detik' => 0, 'sisa_percobaan' => 0, 'tahap' => 1, 'kebal' => FALSE);
		if ($identity === '') {
			return $hasil;
		}

		$st = $this->m_login_lockout->status($identity);
		$hasil['sisa_percobaan'] = $st['sisa_percobaan'];
		$hasil['tahap'] = $st['tahap'];
		$hasil['kebal'] = $st['kebal'];

		if ($st['diblokir']) {
			$hasil['jenis'] = 'blokir';
		} elseif ($st['sisa_detik'] > 0) {
			$hasil['jenis'] = 'kunci';
			$hasil['sisa_detik'] = $st['sisa_detik'];
		} elseif ($baru_gagal) {
			$hasil['jenis'] = 'salah';
		} elseif ($st['gagal'] == 0) {
			$this->session->unset_userdata('login_identity_terakhir');
		}
		return $hasil;
	}

	public function logout()
	{
		//save log
		// $log['kode_log_action'] = "52";
		// $log['action'] = "LOGOUT";
		// $log['kode_log_action_table'] = "0";
		// $log['action_table'] = "";
		// $this->m_log->create($log);
		//redirect
		$this->ion_auth->logout();
		redirect('login', 'refresh');
	}

}
