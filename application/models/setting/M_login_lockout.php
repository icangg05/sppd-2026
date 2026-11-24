<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pembatasan percobaan login (per username):
 *   - 3 kali gagal pertama  -> login dikunci 60 detik
 *   - 3 kali gagal berikutnya -> username diblokir, hanya super admin yang bisa membuka
 * Akun super admin tidak diblokir permanen (hanya dikunci 60 detik tiap 3 kali gagal),
 * agar penyerang tidak bisa mengunci satu-satunya akun yang bisa membuka blokir.
 *
 * Dipakai oleh Ion_auth_model::login() sehingga berlaku untuk semua jalur login
 * (form web maupun API).
 */
class M_login_lockout extends CI_Model
{
	const TABEL       = 'login_lockout';
	const BATAS_TAHAP = 3;   // jumlah gagal per tahap
	const LAMA_KUNCI  = 60;  // detik

	public function __construct()
	{
		parent::__construct();
		$this->pastikan_tabel();
	}

	private function pastikan_tabel()
	{
		if ($this->db->table_exists(self::TABEL)) {
			return;
		}
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . self::TABEL . "` (
			`login`        varchar(100) NOT NULL,
			`gagal`        int unsigned NOT NULL DEFAULT 0,
			`kunci_sampai` int unsigned NOT NULL DEFAULT 0,
			`diblokir`     tinyint(1) NOT NULL DEFAULT 0,
			`diblokir_pada` int unsigned DEFAULT NULL,
			`ip_terakhir`  varchar(45) DEFAULT NULL,
			`diubah_pada`  int unsigned NOT NULL DEFAULT 0,
			PRIMARY KEY (`login`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
		$this->db->data_cache = array(); // reset cache table_exists
	}

	// Username dibandingkan tanpa beda huruf besar/kecil (kolom users.username juga _ci)
	public function kunci($identity)
	{
		return mb_strtolower(trim((string) $identity), 'UTF-8');
	}

	private function ambil($identity)
	{
		$kunci = $this->kunci($identity);
		if ($kunci === '') {
			return NULL;
		}
		return $this->db->where('login', $kunci)->get(self::TABEL)->row();
	}

	/**
	 * @return array diblokir(bool), sisa_detik(int), gagal(int), sisa_percobaan(int),
	 *               tahap(1 = menuju kunci 60 detik, 2 = menuju blokir),
	 *               kebal(bool: super admin, tidak pernah diblokir)
	 */
	public function status($identity)
	{
		$row   = $this->ambil($identity);
		$gagal = $row ? (int) $row->gagal : 0;
		$sisa  = $row ? max(0, (int) $row->kunci_sampai - time()) : 0;
		$tahap = $gagal < self::BATAS_TAHAP ? 1 : 2;
		$batas = self::BATAS_TAHAP * $tahap;

		return array(
			'diblokir'       => $row ? (bool) $row->diblokir : FALSE,
			'sisa_detik'     => $sisa,
			'gagal'          => $gagal,
			'sisa_percobaan' => max(0, $batas - $gagal),
			'tahap'          => $tahap,
			'kebal'          => $gagal > 0 ? $this->kebal_blokir($identity) : FALSE,
		);
	}

	// Super admin (user id 1 / grup 100): tidak diblokir, hanya dikunci 60 detik tiap 3 kali gagal
	public function kebal_blokir($identity)
	{
		$user = $this->db->select('id')->where('username', trim((string) $identity))->limit(1)->get('users')->row();
		if (!$user) {
			return FALSE;
		}
		if ((int) $user->id === 1) {
			return TRUE;
		}
		return $this->db->where(array('user_id' => $user->id, 'group_id' => 100))->count_all_results('users_groups') > 0;
	}

	// Dipanggil setiap password salah / username tidak dikenal
	public function catat_gagal($identity, $kebal_blokir = FALSE)
	{
		$kunci = $this->kunci($identity);
		if ($kunci === '') {
			return;
		}
		$row   = $this->ambil($kunci);
		$gagal = ($row ? (int) $row->gagal : 0) + 1;
		$data  = array(
			'gagal'       => $gagal,
			'ip_terakhir' => substr((string) $this->input->ip_address(), 0, 45),
			'diubah_pada' => time(),
		);

		if ($gagal >= self::BATAS_TAHAP * 2) {
			if ($kebal_blokir) {
				// super admin: kembali ke awal tahap 2, kunci 60 detik lagi
				$data['gagal']        = self::BATAS_TAHAP;
				$data['kunci_sampai'] = time() + self::LAMA_KUNCI;
			} else {
				$data['diblokir']      = 1;
				$data['diblokir_pada'] = time();
				$data['kunci_sampai']  = 0;
			}
		} elseif ($gagal == self::BATAS_TAHAP) {
			$data['kunci_sampai'] = time() + self::LAMA_KUNCI;
		}

		if ($row) {
			$this->db->where('login', $kunci)->update(self::TABEL, $data);
		} else {
			$data['login'] = $kunci;
			$this->db->insert(self::TABEL, $data);
		}
	}

	// Login berhasil / dibuka super admin
	public function reset($identity)
	{
		$kunci = $this->kunci($identity);
		if ($kunci !== '') {
			$this->db->where('login', $kunci)->delete(self::TABEL);
		}
	}

	// Username yang sedang diblokir atau dikunci, beserta data user-nya bila ada
	public function daftar()
	{
		return $this->db->query(
			"SELECT l.*, u.id AS user_id, u.first_name, u.last_name, s.skpd_nama
			 FROM `" . self::TABEL . "` l
			 LEFT JOIN users u ON u.username = l.login
			 LEFT JOIN table_skpd s ON s.skpd_id = u.skpd_id
			 WHERE l.diblokir = 1 OR l.kunci_sampai > ?
			 ORDER BY l.diblokir DESC, l.diubah_pada DESC",
			array(time())
		)->result();
	}

	public function jumlah_diblokir()
	{
		return $this->db->where('diblokir', 1)->count_all_results(self::TABEL);
	}
}
