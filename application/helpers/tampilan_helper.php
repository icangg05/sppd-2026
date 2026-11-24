<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Helper tampilan umum (di-autoload)

if (!function_exists('baris_data_kosong')) {
	// Satu baris tabel selebar $kolom kolom, dipakai saat data tabel kosong.
	// Contoh di view: if (empty($data)) { echo baris_data_kosong(5); }
	function baris_data_kosong($kolom, $pesan = 'Data tidak ada')
	{
		return '<tr class="baris-data-kosong">'
			. '<td colspan="' . max(1, (int) $kolom) . '" class="text-center text-muted" style="padding: 20px;">'
			. '<i class="fa fa-info-circle"></i> ' . html_escape($pesan)
			. '</td></tr>';
	}
}
