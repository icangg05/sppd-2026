<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
 * Perjalanan dinas LUAR NEGERI (telaah_domainperjalanan = 5).
 *
 * Alur persetujuannya sama dengan LDLP; yang berbeda hanya lokasi tujuan. Agar struktur
 * database tidak berubah, tujuan luar negeri disimpan di kolom yang sudah ada:
 *   - telaah_provinsitujuan = 0
 *   - telaah_kotatujuan     = id negara dari upload/json/negara.json (kode numerik ISO 3166-1,
 *                             selalu < 1000 sehingga tidak pernah cocok dengan kabkot_id >= 1101)
 *   - telaah_kantortujuan   = "<tempat tujuan>, <kota>, <negara>"
 * Nama kota diinput manual (tanpa koma) lalu digabung ke telaah_kantortujuan, sehingga cetakan
 * yang langsung memakai telaah_kantortujuan ("... Di <tempat tujuan> ...") tetap terbaca lengkap.
 */

if (!function_exists('negara_list')) {
	/**
	 * Daftar negara dari upload/json/negara.json dalam bentuk array(id => nama).
	 * Mengembalikan array kosong bila file tidak ada / tidak valid.
	 */
	function negara_list()
	{
		static $negara = null;

		if ($negara === null) {
			$negara = array();
			$path = FCPATH . 'upload/json/negara.json';
			$json = is_file($path) ? json_decode(file_get_contents($path), true) : null;

			if (is_array($json)) {
				foreach ($json as $v) {
					if (isset($v['id'], $v['nama'])) {
						$negara[(int) $v['id']] = $v['nama'];
					}
				}
			}
		}

		return $negara;
	}
}

if (!function_exists('negara_nama')) {
	function negara_nama($negara_id)
	{
		$negara = negara_list();
		return isset($negara[(int) $negara_id]) ? $negara[(int) $negara_id] : '';
	}
}

if (!function_exists('negara_valid')) {
	/**
	 * Rule form_validation: id negara harus ada di negara.json.
	 */
	function negara_valid($negara_id)
	{
		return negara_nama($negara_id) !== '';
	}
}

if (!function_exists('kota_luar_negeri_valid')) {
	/**
	 * Rule form_validation: koma dipakai sebagai pemisah di telaah_kantortujuan.
	 */
	function kota_luar_negeri_valid($kota)
	{
		return strpos((string) $kota, ',') === false;
	}
}

if (!function_exists('gabung_tujuan_luar_negeri')) {
	/**
	 * Menyusun nilai telaah_kantortujuan untuk perjalanan luar negeri.
	 */
	function gabung_tujuan_luar_negeri($kantor, $kota, $negara_id)
	{
		return trim($kantor) . ', ' . trim($kota) . ', ' . negara_nama($negara_id);
	}
}

if (!function_exists('tujuan_luar_negeri')) {
	/**
	 * Memecah tujuan luar negeri dari satu baris table_telaah (array atau object).
	 * Mengembalikan array('negara' => ..., 'kota' => ..., 'kantor' => ...).
	 */
	function tujuan_luar_negeri($row)
	{
		$row = (array) $row;
		$bagian = explode(', ', (string) $row['telaah_kantortujuan']);
		$kota = '';

		if (count($bagian) >= 3) {
			array_pop($bagian); // nama negara; acuannya tetap id negara di telaah_kotatujuan
			$kota = array_pop($bagian);
		}

		return array(
			'negara' => negara_nama($row['telaah_kotatujuan']),
			'kota'   => $kota,
			'kantor' => implode(', ', $bagian),
		);
	}
}

if (!function_exists('kantor_tujuan')) {
	/**
	 * Tempat tujuan (kantor/gedung/hotel) tanpa kota & negara untuk perjalanan luar negeri.
	 */
	function kantor_tujuan($row)
	{
		$row = (array) $row;

		if ($row['telaah_domainperjalanan'] == 5) {
			$tujuan = tujuan_luar_negeri($row);
			return $tujuan['kantor'];
		}

		return $row['telaah_kantortujuan'];
	}
}

if (!function_exists('lokasi_luar_negeri')) {
	/**
	 * Untuk hasil query cetakan (result_array): baris luar negeri tidak punya pasangan di
	 * table_provinsi / table_kabkot, jadi kolom provinsi & kabupaten_kota diisi dari negara/kota
	 * agar "Tempat tujuan" dan "Tiba di" pada SPPD tetap terisi.
	 */
	function lokasi_luar_negeri($rows)
	{
		foreach ($rows as $i => $row) {
			if (isset($row['telaah_domainperjalanan']) && $row['telaah_domainperjalanan'] == 5) {
				$tujuan = tujuan_luar_negeri($row);
				$rows[$i]['provinsi'] = ucfirst($tujuan['negara']);
				$rows[$i]['kabupaten_kota'] = ucfirst($tujuan['kota']) . ', ' . ucfirst($tujuan['negara']);
			}
		}

		return $rows;
	}
}
