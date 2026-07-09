<?php

defined('BASEPATH') or exit('No direct script access allowed');

class M_laporan extends CI_Model

{

	public function get_pelaksana_opd($telaah_id)
	{

		$this->db->select('*, table_pegawai.skpd_id as skpd');

		$this->db->from('table_telaah');

		$this->db->join('table_pegawai', 'table_telaah.telaah_pelaksana=table_pegawai.pegawai_id', 'LEFT');

		$this->db->join('table_provinsi', 'table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');

		$this->db->join('table_kabkot', 'table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_relasi_kelurahan', 'table_relasi_kelurahan.id_kelurahan=table_skpd.skpd_id', 'LEFT');

		$this->db->join('table_anggaran', 'table_anggaran.id_anggaran=table_telaah.telaah_kegiatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->join('table_jabatan', 'table_pegawai.pegawai_jabatan=table_jabatan.jabatan_id', 'LEFT');

		$this->db->where('telaah_id', $telaah_id);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_pengikut_opd($telaah_id, $pengikut_id)
	{

		$this->db->select('*, table_pegawai.skpd_id as skpd');

		$this->db->from('table_pengikut');

		$this->db->join('table_pegawai', 'table_pengikut.pegawai_id=table_pegawai.pegawai_id', 'LEFT');

		$this->db->join('table_telaah', 'table_pengikut.telaah_id=table_telaah.telaah_id', 'LEFT');

		$this->db->join('table_provinsi', 'table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');

		$this->db->join('table_kabkot', 'table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_anggaran', 'table_anggaran.id_anggaran=table_telaah.telaah_kegiatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->join('table_jabatan', 'table_pegawai.pegawai_jabatan=table_jabatan.jabatan_id', 'LEFT');

		$this->db->where('table_pengikut.telaah_id', $telaah_id);

		$this->db->where('table_pengikut.pegawai_id', $pengikut_id);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_pelaksana_dprd($telaah_id)
	{

		$this->db->select('*, anggotadprd_name as pegawai_nama, anggotadprd_jabatan as pegawai_namajabatan');

		$this->db->from('table_telaah');

		$this->db->join('table_anggotadprd', 'table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id', 'LEFT');

		$this->db->join('table_provinsi', 'table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');

		$this->db->join('table_kabkot', 'table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=2', 'LEFT');

		$this->db->join('table_anggaran', 'table_anggaran.id_anggaran=table_telaah.telaah_kegiatan', 'LEFT');

		$this->db->where('telaah_id', $telaah_id);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_pengikut_dprd($telaah_id, $pengikut_id)
	{

		$this->db->select('*, anggotadprd_name as pegawai_nama, anggotadprd_jabatan as pegawai_namajabatan');

		$this->db->from('table_pengikut');

		$this->db->join('table_anggotadprd', 'table_pengikut.pegawai_id=table_anggotadprd.anggotadprd_id', 'LEFT');

		$this->db->join('table_telaah', 'table_pengikut.telaah_id=table_telaah.telaah_id', 'LEFT');

		$this->db->join('table_provinsi', 'table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');

		$this->db->join('table_kabkot', 'table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=2', 'LEFT');

		$this->db->join('table_anggaran', 'table_anggaran.id_anggaran=table_telaah.telaah_kegiatan', 'LEFT');

		$this->db->where('table_pengikut.telaah_id', $telaah_id);

		$this->db->where('table_pengikut.pegawai_id', $pengikut_id);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_pelaksana_walikota($telaah_id)
	{

		$this->db->select('*, table_pimpinan.skpd_id as skpd');

		$this->db->from('table_telaah');

		$this->db->join('table_pimpinan', 'table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id', 'LEFT');

		$this->db->join('table_provinsi', 'table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');

		$this->db->join('table_kabkot', 'table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pimpinan.skpd_id', 'LEFT');

		$this->db->join('table_anggaran', 'table_anggaran.id_anggaran=table_telaah.telaah_kegiatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pimpinan.pegawai_golongan', 'LEFT');

		$this->db->join('table_jabatan', 'table_pimpinan.pegawai_jabatan=table_jabatan.jabatan_id', 'LEFT');

		$this->db->where('telaah_id', $telaah_id);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_dinas_kesehatan()
	{

		$this->db->select('*');

		$this->db->from('table_skpd');

		$this->db->where('jenis_skpd', 10);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_rincian_pelaksana($telaah_id, $pegawai_id)
	{

		$this->db->select('*, table_pegawai.skpd_id as skpd');

		$this->db->from('table_telaah');

		$this->db->join('table_pegawai', 'table_telaah.telaah_pelaksana=table_pegawai.pegawai_id', 'LEFT');

		$this->db->join('table_provinsi', 'table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');

		$this->db->join('table_kabkot', 'table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_anggaran', 'table_anggaran.id_anggaran=table_telaah.telaah_kegiatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->join('table_jabatan', 'table_pegawai.pegawai_jabatan=table_jabatan.jabatan_id', 'LEFT');

		$this->db->where('telaah_id', $telaah_id);

		$this->db->where('pegawai_id', $pegawai_id);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_rincian_pengikut($telaah_id, $pengikut_id)
	{

		$this->db->select('*, table_pegawai.skpd_id as skpd');

		$this->db->from('table_pengikut');

		$this->db->join('table_pegawai', 'table_pengikut.pegawai_id=table_pegawai.pegawai_id', 'LEFT');

		$this->db->join('table_telaah', 'table_pengikut.telaah_id=table_telaah.telaah_id', 'LEFT');

		$this->db->join('table_provinsi', 'table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');

		$this->db->join('table_kabkot', 'table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_anggaran', 'table_anggaran.id_anggaran=table_telaah.telaah_kegiatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->join('table_jabatan', 'table_pegawai.pegawai_jabatan=table_jabatan.jabatan_id', 'LEFT');

		$this->db->where('table_pengikut.telaah_id', $telaah_id);

		$this->db->where('table_pengikut.pegawai_id', $pengikut_id);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_rincian_walikota($telaah_id, $pegawai_id)
	{

		$this->db->select('*, table_pimpinan.skpd_id as skpd');

		$this->db->from('table_telaah');

		$this->db->join('table_pimpinan', 'table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id', 'LEFT');

		$this->db->join('table_provinsi', 'table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');

		$this->db->join('table_kabkot', 'table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pimpinan.skpd_id', 'LEFT');

		$this->db->join('table_anggaran', 'table_anggaran.id_anggaran=table_telaah.telaah_kegiatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pimpinan.pegawai_golongan', 'LEFT');

		$this->db->join('table_jabatan', 'table_pimpinan.pegawai_jabatan=table_jabatan.jabatan_id', 'LEFT');

		$this->db->where('telaah_id', $telaah_id);

		$this->db->where('pegawai_id', $pegawai_id);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_rincian_pelaksana_dprd($telaah_id, $pegawai_id)
	{

		$this->db->select('*, anggotadprd_id as pegawai_id, anggotadprd_name as pegawai_nama, anggotadprd_jabatan as pegawai_namajabatan');

		$this->db->from('table_telaah');

		$this->db->join('table_anggotadprd', 'table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id', 'LEFT');

		$this->db->join('table_provinsi', 'table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');

		$this->db->join('table_kabkot', 'table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=2', 'LEFT');

		$this->db->join('table_anggaran', 'table_anggaran.id_anggaran=table_telaah.telaah_kegiatan', 'LEFT');

		$this->db->where('telaah_id', $telaah_id);

		$this->db->where('anggotadprd_id', $pegawai_id);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_rincian_pengikut_dprd($telaah_id, $pengikut_id)
	{

		$this->db->select('*, anggotadprd_id as pegawai_id, anggotadprd_name as pegawai_nama, anggotadprd_jabatan as pegawai_namajabatan');

		$this->db->from('table_pengikut');

		$this->db->join('table_telaah', 'table_pengikut.telaah_id=table_telaah.telaah_id', 'LEFT');

		$this->db->join('table_anggotadprd', 'table_pengikut.pegawai_id=table_anggotadprd.anggotadprd_id', 'LEFT');

		$this->db->join('table_provinsi', 'table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');

		$this->db->join('table_kabkot', 'table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=2', 'LEFT');

		$this->db->join('table_anggaran', 'table_anggaran.id_anggaran=table_telaah.telaah_kegiatan', 'LEFT');

		$this->db->where('table_pengikut.telaah_id', $telaah_id);

		$this->db->where('table_pengikut.pegawai_id', $pengikut_id);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function tanda_tangan($pegawai_id)
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.pegawai_id', $pegawai_id);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function tanda_tangan_walikota($pegawai_id)
	{

		$this->db->select('*');

		$this->db->from('table_pimpinan');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pimpinan.pegawai_golongan', 'LEFT');

		$this->db->where('table_pimpinan.pegawai_id', $pegawai_id);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function tanda_tangan_sekda()
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_jabatan', 'table_jabatan.jabatan_id = table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('jabatan_id', 3);

		$this->db->order_by('pegawai_id', 'DESC');

		$this->db->limit(1, 0);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function tanda_tangan_kepala_opd($skpd_id)
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_jabatan', 'table_jabatan.jabatan_id = table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('skpd_id', $skpd_id);

		$this->db->where('jabatan_id', 4);

		$this->db->order_by('pegawai_id', 'DESC');

		$this->db->limit(1, 0);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_tanda_tangan($jabatan_id)
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->where('table_pegawai.pegawai_jabatan', $jabatan_id);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_walikota()
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.pegawai_jabatan', 1);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_wakil_walikota()
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.pegawai_jabatan', 14);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_asisten1()
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.pegawai_jabatan', 2);

		$this->db->where('table_pegawai.status_delete', 0);

		$this->db->order_by('pegawai_id', 'DESC');

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_asisten2()
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.pegawai_jabatan', 17);

		$this->db->where('table_pegawai.status_delete', 0);

		$this->db->order_by('pegawai_id', 'DESC');

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_asisten3()
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.pegawai_jabatan', 18);

		$this->db->where('table_pegawai.status_delete', 0);

		$this->db->order_by('pegawai_id', 'DESC');

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_kepala_opd($skpd_id)
	{

		$this->db->select('*, pegawai_tandatangan as tanda_tangan');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.skpd_id=table_pegawai.skpd_id

						AND table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.skpd_id', $skpd_id);

		$this->db->where('table_pegawai.pegawai_jabatan', 4);

		$this->db->where('table_pegawai.status_delete', 0);

		$this->db->order_by('pegawai_id', 'DESC');

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_camat($skpd_id)
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.skpd_id=table_pegawai.skpd_id

						AND table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.skpd_id', $skpd_id);

		$this->db->where('table_pegawai.pegawai_jabatan', 10);

		$this->db->where('table_pegawai.status_delete', 0);

		$this->db->order_by('pegawai_id', 'DESC');

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_sekcam($skpd_id)
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.skpd_id=table_pegawai.skpd_id

						AND table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.skpd_id', $skpd_id);

		$this->db->where('table_pegawai.pegawai_jabatan', 11);

		$this->db->where('table_pegawai.status_delete', 0);

		$this->db->order_by('pegawai_id', 'DESC');

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_lurah($skpd_id)
	{

		$this->db->select('*, pegawai_tandatangan as tanda_tangan');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.skpd_id=table_pegawai.skpd_id

						AND table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.skpd_id', $skpd_id);

		$this->db->where('table_pegawai.pegawai_jabatan', 12);

		$this->db->where('table_pegawai.status_delete', 0);

		$this->db->order_by('pegawai_id', 'DESC');

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_sekretaris_opd($skpd_id)
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.skpd_id=table_pegawai.skpd_id

						AND table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.skpd_id', $skpd_id);

		$this->db->where('table_pegawai.pegawai_jabatan', 6);

		$this->db->where('table_pegawai.status_delete', 0);

		$this->db->order_by('pegawai_id', 'DESC');

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_kabid_opd($pegawai_id)
	{

		$this->db->select('*, pegawai_tandatangan as tanda_tangan, status_tandatangan as status');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.skpd_id=table_pegawai.skpd_id

						AND table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.pegawai_id', $pegawai_id);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_kepala_dinkes()
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.skpd_id=table_pegawai.skpd_id

						AND table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.skpd_id', 36);

		$this->db->where('table_pegawai.pegawai_jabatan', 4);

		$this->db->where('table_pegawai.status_delete', 0);

		$this->db->order_by('pegawai_id', 'DESC');

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_sekretaris_dinkes()
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.skpd_id=table_pegawai.skpd_id

						AND table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.skpd_id', 36);

		$this->db->where('table_pegawai.pegawai_jabatan', 6);

		$this->db->where('table_pegawai.status_delete', 0);

		$this->db->order_by('pegawai_id', 'DESC');

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_kepala_puskesmas($skpd_id)
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.skpd_id=table_pegawai.skpd_id

						AND table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.skpd_id', $skpd_id);

		$this->db->where('table_pegawai.pegawai_jabatan', 19);

		$this->db->where('table_pegawai.status_delete', 0);

		$this->db->order_by('pegawai_id', 'DESC');

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_pimpinan_dprd()
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		//$this->db->where('table_pegawai.skpd_id', $skpd_id);

		$this->db->where('table_pegawai.pegawai_jabatan', 5);

		$this->db->where('table_pegawai.status_delete', 0);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_pimpinan_dprdFix()
	{

		$this->db->select('*, pegawai_tandatangan as tanda_tangan, status_tandatangan as status');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.pegawai_jabatan', 5);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_wakilketua_dprd()
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.pegawai_jabatan', 20);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_sekwan()
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.pegawai_jabatan', 9);

		$this->db->where('table_pegawai.status_delete', 0);

		$this->db->order_by('pegawai_id', 'DESC');

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_sekda($skpd_id)
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.skpd_id=table_pegawai.skpd_id

						AND table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.skpd_id', $skpd_id);

		$this->db->where('table_pegawai.pegawai_jabatan', 3);

		$this->db->where('table_pegawai.status_delete', 0);

		$this->db->order_by('pegawai_id', 'DESC');

		$query = $this->db->get();

		return $query->result_array();
	}

	public function get_sekdaFix()
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.pegawai_jabatan', 3);

		$this->db->where('table_pegawai.status_delete', 0);

		$this->db->group_by('pegawai_nip');

		$this->db->order_by('pegawai_id', 'DESC');

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_bendahara($skpd_id)
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.skpd_id=table_pegawai.skpd_id

						AND table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.skpd_id', $skpd_id);

		$this->db->where('table_pegawai.pegawai_jabatan', 13);

		$this->db->where('table_pegawai.status_delete', 0);

		$this->db->order_by('pegawai_id', 'DESC');

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_bendahara_setda($bagian_id)
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.skpd_id=table_pegawai.skpd_id

						AND table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.bagian_id', $bagian_id);

		$this->db->where('table_pegawai.pegawai_jabatan', 13);

		$this->db->where('table_pegawai.status_delete', 0);

		$this->db->order_by('pegawai_id', 'DESC');

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_bendahara_dprd()
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.skpd_id', 2);

		$this->db->where('table_pegawai.pegawai_jabatan', 13);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_bendaharawalikota($skpd_id)
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_skpd', 'table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.skpd_id=table_pegawai.skpd_id

						AND table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pimpinan.skpd_id', $skpd_id);

		$this->db->where('table_pimpinan.pegawai_jabatan', 13);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_ttd($pegawai_id)
	{

		$this->db->select('*');

		$this->db->from('table_pegawai');

		$this->db->join('table_tanda_tangan', 'table_tanda_tangan.jabatan_id=table_pegawai.pegawai_jabatan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->where('table_pegawai.pegawai_id', $pegawai_id);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_pengikut($telaah_id)
	{

		$this->db->select('*');

		$this->db->from('table_pengikut');

		$this->db->join('table_telaah', 'table_telaah.telaah_id=table_pengikut.telaah_id', 'LEFT');

		$this->db->join('table_pegawai', 'table_pegawai.pegawai_id=table_pengikut.pegawai_id', 'LEFT');

		$this->db->join('table_provinsi', 'table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');

		$this->db->where('table_pengikut.telaah_id', $telaah_id);

		$query = $this->db->get();

		return $query->result_array();
	}



	public function get_pengikut2($telaah_id)
	{

		$this->db->select('*');

		$this->db->from('table_pengikut');

		$this->db->join('table_telaah', 'table_telaah.telaah_id=table_pengikut.telaah_id', 'LEFT');

		$this->db->join('table_pegawai', 'table_pegawai.pegawai_id=table_pengikut.pegawai_id', 'LEFT');

		$this->db->join('table_provinsi', 'table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');

		$this->db->join('table_golongan', 'table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');

		$this->db->join('table_jabatan', 'table_pegawai.pegawai_jabatan=table_jabatan.jabatan_id', 'LEFT');

		$this->db->where('table_pengikut.telaah_id', $telaah_id);

		if ($this->ion_auth->user()->row()->skpd_id == 182) {
			$this->db->order_by('table_pengikut.telaah_jabatan_pengikut', 'ASC');
		} else {
			$this->db->order_by('table_golongan.golongan_id', 'DESC');
		}


		$query = $this->db->get();

		return $query->result();
	}



	public function get_pengikut_dprd2($telaah_id)
	{

		$this->db->select('*');

		$this->db->from('table_pengikut');

		$this->db->join('table_telaah', 'table_telaah.telaah_id=table_pengikut.telaah_id', 'LEFT');

		$this->db->join('table_anggotadprd', 'table_anggotadprd.anggotadprd_id=table_pengikut.pegawai_id', 'LEFT');

		$this->db->join('table_provinsi', 'table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');

		$this->db->where('table_pengikut.telaah_id', $telaah_id);

		$this->db->order_by('table_anggotadprd.anggotadprd_jenis_jabatan', 'ASC');

		$query = $this->db->get();

		return $query->result();
	}



	public function get_kop_sekda()
	{

		$this->db->select('*');

		$this->db->from('table_skpd');

		$this->db->where('jenis_skpd', 3);

		$query = $this->db->get();

		return $query->result_array();
	}

	/**
	 * Khusus SETDA: nilai penandatangan "Setuju Bayar / Mengetahui" diambil dari
	 * file upload/json/data.json (menggantikan data dari database) sehingga dinamis.
	 * Struktur JSON: { "label", "nama", "asal_opd", "nip" }.
	 * Mengembalikan array bentuk hasil query (index 0) agar kompatibel dengan
	 * kode cetak yang memakai pegawai_nama / pegawai_nip / pegawai_namajabatan.
	 * Bila file tidak ada / tidak valid / nama kosong, mengembalikan array kosong
	 * sehingga pemanggil bisa fallback ke data database (get_sekda).
	 */
	public function get_setda_json()
	{
		$path = FCPATH . 'upload/json/data.json';

		if (!is_file($path)) {
			return array();
		}

		$json = json_decode(file_get_contents($path), true);

		if (!is_array($json) || !isset($json['nama']) || trim($json['nama']) === '') {
			return array();
		}

		return array(array(
			'pegawai_nama'        => isset($json['nama']) ? $json['nama'] : '',
			'pegawai_nip'         => isset($json['nip']) ? $json['nip'] : '',
			'pegawai_namajabatan' => isset($json['label']) ? $json['label'] : '',
			'setda_label'         => isset($json['label']) ? $json['label'] : '',
			'setda_asal_opd'      => isset($json['asal_opd']) ? $json['asal_opd'] : '',
			'setda_json'          => 1,
		));
	}
}
