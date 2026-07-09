<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model pendukung form "Setuju Bayar (SETDA)".
 * Menyediakan daftar pegawai (untuk dropdown) beserta asal OPD-nya, sehingga
 * nama / NIP / asal OPD dapat diisi otomatis ke upload/json/data.json.
 */
class M_setuju_bayar extends CI_Model {

	/**
	 * Daftar pegawai pada satu SKPD untuk pilihan penandatangan.
	 */
	public function pegawai($skpd_id) {
		$this->db->select('table_pegawai.pegawai_id, table_pegawai.pegawai_nama, table_pegawai.pegawai_nip, table_skpd.skpd_nama');
		$this->db->from('table_pegawai');
		$this->db->join('table_skpd', 'table_skpd.skpd_id = table_pegawai.skpd_id', 'LEFT');
		$this->db->where('table_pegawai.skpd_id', $skpd_id);
		$this->db->where('table_pegawai.status_delete', 0);
		$this->db->order_by('table_pegawai.pegawai_nama', 'ASC');
		return $this->db->get()->result();
	}

	/**
	 * Ambil satu pegawai (nama, NIP, asal OPD) berdasarkan pegawai_id.
	 */
	public function get_pegawai($pegawai_id) {
		$this->db->select('table_pegawai.pegawai_id, table_pegawai.pegawai_nama, table_pegawai.pegawai_nip, table_skpd.skpd_nama');
		$this->db->from('table_pegawai');
		$this->db->join('table_skpd', 'table_skpd.skpd_id = table_pegawai.skpd_id', 'LEFT');
		$this->db->where('table_pegawai.pegawai_id', $pegawai_id);
		return $this->db->get()->row_array();
	}
}
