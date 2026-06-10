<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_anggaran extends CI_Model
{

	public function record_count($skpd_id)
	{
		return $this->db->count_all("table_anggaran WHERE skpd_id = '$skpd_id'");
	}

	public function record_count_setda($skpd_id, $bagian_id)
	{
		return $this->db->count_all("table_anggaran 
									 WHERE skpd_id = '$skpd_id'
									 AND bagian_id = '$bagian_id'");
	}

	public function record_count_search($column, $data, $skpd_id, $tahun)
	{
		if ($column) {
			return $this->db->count_all("table_anggaran WHERE skpd_id = '$skpd_id' AND tahun = '$tahun' AND $column like '%$data%'");
		} else {
			return $this->db->count_all("table_anggaran WHERE skpd_id = '$skpd_id' AND tahun = '$tahun'");
		}
	}

	public function record_count_search_setda($column, $data, $skpd_id, $bagian_id, $tahun)
	{

		if ($column) {
			return $this->db->count_all("table_anggaran 
									WHERE skpd_id = '$skpd_id' 
									AND bagian_id = '$bagian_id' 
									AND tahun = '$tahun' 
									AND $column like '%$data%'");
		} else {
			return $this->db->count_all("table_anggaran 
									WHERE skpd_id = '$skpd_id' 
									AND bagian_id = '$bagian_id' 
									AND tahun = '$tahun' ");
		}
	}

	public function data($limit, $start, $skpd_id)
	{
		$this->db->select('*');
		$this->db->from('table_anggaran');
		$this->db->join('table_skpd', 'table_anggaran.skpd_id=table_skpd.skpd_id', 'left');
		$this->db->where('table_anggaran.skpd_id', $skpd_id);
		$this->db->order_by('id_anggaran', 'DESC');
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query->result();
	}

	public function data_setda($limit, $start, $skpd_id, $bagian_id)
	{
		$this->db->select('*');
		$this->db->from('table_anggaran');
		$this->db->join('table_skpd', 'table_anggaran.skpd_id=table_skpd.skpd_id', 'left');
		$this->db->where('table_anggaran.skpd_id', $skpd_id);
		$this->db->where('table_anggaran.bagian_id', $bagian_id);
		$this->db->order_by('id_anggaran', 'DESC');
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query->result();
	}

	public function data_search($column, $value, $limit, $start, $skpd_id, $tahun)
	{
		$this->db->select('*');
		$this->db->from('table_anggaran');
		$this->db->join('table_skpd', 'table_anggaran.skpd_id=table_skpd.skpd_id', 'left');
		$this->db->where('table_anggaran.skpd_id', $skpd_id);
		$this->db->where('tahun', $tahun);
		if ($column) {
			$this->db->like($column, $value);
		}
		$this->db->order_by('id_anggaran', 'DESC');
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query->result();
	}

	public function data_search_setda($column, $value, $skpd_id, $limit, $start, $bagian_id, $tahun)
	{
		$this->db->select('*');
		$this->db->from('table_anggaran');
		$this->db->join('table_skpd', 'table_anggaran.skpd_id=table_skpd.skpd_id', 'left');
		$this->db->where('table_anggaran.skpd_id', $skpd_id);
		$this->db->where('table_anggaran.bagian_id', $bagian_id);
		$this->db->where('tahun', $tahun);
		if ($column) {
			$this->db->like($column, $value);
		}
		$this->db->order_by('id_anggaran', 'DESC');
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query->result();
	}

	public function get($id_anggaran)
	{
		$this->db->where('id_anggaran', $id_anggaran);
		$query = $this->db->get('table_anggaran', 1);
		return $query->result_array();
	}

	public function count_telaah_anggaran($id_anggaran)
	{
		$this->db->select('table_telaah.telaah_waktuinput, table_telaah.telaah_perihal, table_telaah.telaah_id,  table_pegawai.pegawai_id , table_pegawai.pegawai_nama');
		$this->db->from('table_anggaran');
		$this->db->join('table_telaah', 'table_telaah.telaah_kegiatan=table_anggaran.id_anggaran', 'left');
		$this->db->join('table_pegawai', 'table_pegawai.pegawai_id=table_telaah.telaah_pelaksana', 'left');
		$this->db->where('table_anggaran.id_anggaran', $id_anggaran);
		$query1 = $this->db->get_compiled_select();

		$this->db->select('table_telaah.telaah_waktuinput, table_telaah.telaah_perihal, table_rincian_biaya.telaah_id,  table_rincian_biaya.pegawai_id , table_pegawai.pegawai_nama');
		$this->db->from('table_anggaran');
		$this->db->join('table_telaah', 'table_telaah.telaah_kegiatan=table_anggaran.id_anggaran', 'left');
		$this->db->join('table_pengikut', 'table_pengikut.telaah_id=table_telaah.telaah_id', 'left');
		$this->db->join('table_pegawai', 'table_pegawai.pegawai_id=table_pengikut.pegawai_id', 'left');
		$this->db->join('table_rincian_biaya', 'table_telaah.telaah_id=table_rincian_biaya.telaah_id AND table_pegawai.pegawai_id = table_rincian_biaya.pegawai_id');
		$this->db->where('table_anggaran.id_anggaran', $id_anggaran);
		$query2 = $this->db->get_compiled_select();

		$this->db->select('table_telaah.telaah_waktuinput, table_telaah.telaah_perihal, table_pengeluaran_rill.telaah_id,  table_pengeluaran_rill.pegawai_id , table_pegawai.pegawai_nama');
		$this->db->from('table_anggaran');
		$this->db->join('table_telaah', 'table_telaah.telaah_kegiatan=table_anggaran.id_anggaran', 'left');
		$this->db->join('table_pengikut', 'table_pengikut.telaah_id=table_telaah.telaah_id', 'left');
		$this->db->join('table_pegawai', 'table_pegawai.pegawai_id=table_pengikut.pegawai_id', 'left');
		$this->db->join('table_pengeluaran_rill', 'table_telaah.telaah_id=table_pengeluaran_rill.telaah_id AND table_pegawai.pegawai_id = table_pengeluaran_rill.pegawai_id');
		$this->db->where('table_anggaran.id_anggaran', $id_anggaran);
		$query3 = $this->db->get_compiled_select();

		$query = $this->db->query($query1 . " UNION " . $query2 . " UNION " . $query3);
		return $query->result();
	}

	public function get_telaah_anggaran($id_anggaran, $limit, $start)
	{
		$this->db->select('table_telaah.telaah_waktuinput, table_telaah.telaah_perihal, table_telaah.telaah_id,  table_pegawai.pegawai_id , table_pegawai.pegawai_nama');
		$this->db->from('table_anggaran');
		$this->db->join('table_telaah', 'table_telaah.telaah_kegiatan=table_anggaran.id_anggaran', 'left');
		$this->db->join('table_pegawai', 'table_pegawai.pegawai_id=table_telaah.telaah_pelaksana', 'left');
		$this->db->where('table_anggaran.id_anggaran', $id_anggaran);
		$query1 = $this->db->get_compiled_select();

		$this->db->select('table_telaah.telaah_waktuinput, table_telaah.telaah_perihal, table_rincian_biaya.telaah_id,  table_rincian_biaya.pegawai_id , table_pegawai.pegawai_nama');
		$this->db->from('table_anggaran');
		$this->db->join('table_telaah', 'table_telaah.telaah_kegiatan=table_anggaran.id_anggaran', 'left');
		$this->db->join('table_pengikut', 'table_pengikut.telaah_id=table_telaah.telaah_id', 'left');
		$this->db->join('table_pegawai', 'table_pegawai.pegawai_id=table_pengikut.pegawai_id', 'left');
		$this->db->join('table_rincian_biaya', 'table_telaah.telaah_id=table_rincian_biaya.telaah_id AND table_pegawai.pegawai_id = table_rincian_biaya.pegawai_id');
		$this->db->where('table_anggaran.id_anggaran', $id_anggaran);
		$query2 = $this->db->get_compiled_select();

		$this->db->select('table_telaah.telaah_waktuinput, table_telaah.telaah_perihal, table_pengeluaran_rill.telaah_id,  table_pengeluaran_rill.pegawai_id , table_pegawai.pegawai_nama');
		$this->db->from('table_anggaran');
		$this->db->join('table_telaah', 'table_telaah.telaah_kegiatan=table_anggaran.id_anggaran', 'left');
		$this->db->join('table_pengikut', 'table_pengikut.telaah_id=table_telaah.telaah_id', 'left');
		$this->db->join('table_pegawai', 'table_pegawai.pegawai_id=table_pengikut.pegawai_id', 'left');
		$this->db->join('table_pengeluaran_rill', 'table_telaah.telaah_id=table_pengeluaran_rill.telaah_id AND table_pegawai.pegawai_id = table_pengeluaran_rill.pegawai_id');
		$this->db->where('table_anggaran.id_anggaran', $id_anggaran);
		$query3 = $this->db->get_compiled_select();

		$query = $this->db->query($query1 . " UNION " . $query2 . " UNION " . $query3 . " ORDER BY telaah_waktuinput DESC LIMIT $start, $limit ");
		return $query->result();
	}

	public function anggaran_opd($skpd_id)
	{
		$this->db->select('*');
		$this->db->from('table_anggaran');
		$this->db->where('skpd_id', $skpd_id);
		$this->db->where('tahun', date('Y'));
		$query = $this->db->get();
		return $query->result();
	}

	public function create($data)
	{

		$this->db->insert('table_anggaran', $data);
	}

	public function update($data)
	{
		$this->db->update('table_anggaran', $data, array('id_anggaran' => $data['id_anggaran']));
	}

	public function delete($id_anggaran)
	{
		$this->db->delete('table_anggaran', array('id_anggaran' => $id_anggaran));
	}


	public function skpd()
	{
		$this->db->select('*');
		$this->db->from('table_skpd');
		$query = $this->db->get();
		return $query->result();
	}

	public function cek_sisa_anggaran_skpd($id_anggaran)
	{
		$query  = $this->db->query("SELECT (SELECT SUM(tarif*item) ) as jumlah  
					FROM table_rincian_biaya a LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
					LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran 
					WHERE c.id_anggaran='$id_anggaran' ");
		return $query->result();
	}

	// public function cek_pengeluaran_rill_skpd($id_anggaran) {
	// $query  = $this->db->query("SELECT table_pengeluaran_rill.*, skpd_nama, id_anggaran FROM table_pengeluaran_rill
	// left JOIN table_pegawai ON table_pengeluaran_rill.pegawai_id = table_pegawai.pegawai_id
	// left JOIN table_skpd ON table_skpd.skpd_id = table_pegawai.skpd_id
	// left JOIN table_telaah ON table_telaah.telaah_id = table_pengeluaran_rill.telaah_id
	// left JOIN table_anggaran ON table_telaah.telaah_kegiatan = table_anggaran.id_anggaran
	// WHERE table_skpd.skpd_id = 14 AND id_anggaran = 55");
	// return $query->result();
	// }

	public function cek_pengeluaran_rill_skpd($id_anggaran)
	{
		$query  = $this->db->query("SELECT SUM(tarif) as jumlah, id_anggaran FROM table_pengeluaran_rill
									JOIN table_telaah ON table_telaah.telaah_id = table_pengeluaran_rill.telaah_id
									JOIN table_anggaran ON table_telaah.telaah_kegiatan = table_anggaran.id_anggaran
									WHERE id_anggaran = '$id_anggaran' ");
		return $query->result();
	}

	public function cek_pengeluaran_rill()
	{
		$query  = $this->db->query("SELECT SUM(tarif) as jumlah FROM table_pengeluaran_rill");
		return $query->result();
	}

	public function setting_anggaran()
	{
		$this->db->select('*');
		$this->db->from('table_setting');
		$this->db->where('setting_id', 2);
		$query = $this->db->get();
		return $query->result_array();
	}


	## RINCIAN BIAYA PER SKPD
	// SELECT (SELECT SUM(tarif*item) ) as jumlah, skpd_nama  
	// FROM table_rincian_biaya a LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
	// LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran 
	// LEFT JOIN table_skpd d ON d.skpd_id=c.skpd_id
	// GROUP BY d.skpd_id;

	## RINCIAN PENGELUARAN PER SKPD
	// SELECT SUM(tarif) as jumlah, skpd_nama FROM table_pengeluaran_rill
	// JOIN table_telaah ON table_telaah.telaah_id = table_pengeluaran_rill.telaah_id
	// JOIN table_anggaran ON table_telaah.telaah_kegiatan = table_anggaran.id_anggaran

	// LEFT JOIN table_skpd ON table_skpd.skpd_id=table_anggaran.skpd_id
	// GROUP BY table_skpd.skpd_id;
	## ANGGARAN SKPD
	// SELECT SUM(pagu),skpd_nama, table_skpd.skpd_id FROM table_anggaran 
	// JOIN table_skpd ON table_skpd.skpd_id=table_anggaran.skpd_id
	// GROUP BY table_anggaran.skpd_id;

	## cek realisasi perjalanan per kegiatan opd (dinkes)
	// SELECT(SELECT SUM(tarif*item) ) as tes , table_telaah.telaah_id FROM table_telaah 
	// JOIN table_anggaran ON table_anggaran.id_anggaran=table_telaah.telaah_kegiatan

	// LEFT JOIN table_rincian_biaya ON table_telaah.telaah_id=table_rincian_biaya.telaah_id
	// WHERE telaah_kegiatan = '499'
	// group BY  table_telaah.telaah_id
	// ORDER BY tes DESC



}
