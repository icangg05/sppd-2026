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
