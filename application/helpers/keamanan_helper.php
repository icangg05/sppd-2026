<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Nama kolom pencarian yang diambil dari URL wajib berupa nama kolom biasa (mis. pegawai_nama / table_telaah.telaah_perihal),
// karena query builder CI3 tidak meng-escape nama kolom di like()/where(). Kosong/NULL dibiarkan seperti sebelumnya.
if ( ! function_exists('kolom_aman'))
{
	function kolom_aman($column)
	{
		if ($column === NULL || $column === '' || preg_match('/^[A-Za-z0-9_]+(\.[A-Za-z0-9_]+)?$/', $column))
		{
			return $column;
		}

		show_404();
	}
}

// Nama file untuk hasil upload: awalan yang dibersihkan + 16 karakter acak, TANPA ekstensi
// (CI menambahkan ekstensi asli yang sudah lolos allowed_types). Nama tidak bisa ditebak atau
// diatur pengguna, sehingga file milik orang lain tidak bisa ditimpa. Bila $awalan kosong,
// dipakai nama asli berkas dari $_FILES[$field] agar tetap mudah dikenali saat diunduh.
if ( ! function_exists('nama_file_unggah'))
{
	function nama_file_unggah($awalan = '', $field = 'userfile')
	{
		$awalan = is_scalar($awalan) ? (string) $awalan : '';
		if ($awalan === '' && isset($_FILES[$field]['name']) && is_string($_FILES[$field]['name']))
		{
			$awalan = pathinfo($_FILES[$field]['name'], PATHINFO_FILENAME);
		}
		$awalan = trim(preg_replace('/[^A-Za-z0-9_-]+/', '_', $awalan), '_-');
		$awalan = substr($awalan, 0, 50);

		return ($awalan !== '' ? $awalan . '_' : '') . bin2hex(random_bytes(8));
	}
}

// Hapus file hasil upload lama. Nama file berasal dari database (pernah dimanipulasi penyerang),
// jadi hanya nama file murni di dalam $folder yang boleh dihapus, tidak bisa keluar folder.
if ( ! function_exists('hapus_file_unggah'))
{
	function hapus_file_unggah($folder, $nama)
	{
		$nama = basename(str_replace('\\', '/', (string) $nama));
		if ($nama === '' || $nama === '.' || $nama === '..')
		{
			return FALSE;
		}
		$path = rtrim((string) $folder, '/') . '/' . $nama;

		return is_file($path) ? unlink($path) : FALSE;
	}
}
