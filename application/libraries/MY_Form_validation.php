<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
	// Tambah pesan error di luar aturan validasi (mis. upload file gagal) agar ikut tampil
	// di validation_errors() dan form_error($field) pada form yang ditampilkan ulang.
	public function tambah_error($field, $pesan)
	{
		$this->_error_array[$field] = $pesan;
		if (!isset($this->_field_data[$field])) {
			$this->_field_data[$field] = array(
				'field' => $field, 'label' => $field, 'rules' => array(), 'errors' => array(),
				'is_array' => FALSE, 'keys' => array(), 'postdata' => NULL,
			);
		}
		$this->_field_data[$field]['error'] = $pesan;
		return $this;
	}
}
