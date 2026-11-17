<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('check_file')) {
	/**
	 * Mengecek apakah file ada di disk lokal.
	 * Jika ada, mengembalikan URL ke file tersebut. Jika tidak ada, mengembalikan URL fallback.
	 *
	 * @param string $file_path Path file relatif terhadap FCPATH (misal: 'upload/kop_surat/logo.png')
	 * @param string $fallback_path Path file fallback relatif terhadap FCPATH
	 * @return string URL dari file yang ditemukan atau fallback
	 */
	function check_file($file_path, $fallback_path = '')
	{
		$file_path = ltrim(trim($file_path), '/');
		
		if (!empty($file_path) && file_exists(FCPATH . $file_path) && is_file(FCPATH . $file_path)) {
			return base_url($file_path);
		}

		if (!empty($fallback_path)) {
			return base_url(ltrim(trim($fallback_path), '/'));
		}

		return '';
	}
}

if (!function_exists('cek_file')) {
	/**
	 * Alias dari check_file()
	 */
	function cek_file($file_path, $fallback_path = '')
	{
		return check_file($file_path, $fallback_path);
	}
}

if (!function_exists('url_dokumen')) {
	/**
	 * URL dokumen dengan versi (?v=waktu ubah file), agar browser tidak menampilkan salinan lama dari cache
	 * setelah file ditimpa (mis. draf SPPD/SPT di doc_perjalanan diganti hasil TTE dengan nama yang sama).
	 *
	 * @param string $file_path Path file relatif terhadap FCPATH (misal: 'upload/doc_perjalanan/SPPD-1-2.pdf')
	 * @return string URL file (tanpa versi jika file tidak ada)
	 */
	function url_dokumen($file_path)
	{
		$file_path = ltrim(trim($file_path), '/');
		$url = base_url(implode('/', array_map('rawurlencode', explode('/', $file_path))));

		return is_file(FCPATH . $file_path) ? $url . '?v=' . filemtime(FCPATH . $file_path) : $url;
	}
}
