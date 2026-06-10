<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_api extends CI_Model
{

    function __construct() {
        parent::__construct();
    }
    
    public function allData() {
        // $query = $this->db->query("SELECT t.telaah_id, t.telaah_perihal, t.telaah_tanggalberangkat,
                                    // t.telaah_tanggalkembali, t.telaah_provinsitujuan, t.telaah_kategori,
                                    // t.telaah_status, t.telaah_pelaksana, p.pegawai_nama, s.skpd_nama,
                                    // pr.provinsi, a.anggotadprd_name
                                    // FROM table_telaah t 
                                    // LEFT JOIN table_pegawai p ON t.telaah_pelaksana = p.pegawai_id
									// LEFT JOIN table_anggotadprd a ON t.telaah_pelaksana = a.anggotadprd_id
                                    // LEFT JOIN table_skpd s ON p.skpd_id = s.skpd_id
                                    // LEFT JOIN table_provinsi pr 
                                    // ON t.telaah_provinsitujuan=pr.provinsi_id
                                    // WHERE (t.telaah_domainperjalanan = 1 OR t.telaah_domainperjalanan = 2)
                                    // AND t.telaah_status = 2
                                    // ORDER BY t.telaah_waktuinput DESC LIMIT 10");
		$this->db->select('telaah_waktuinput, table_telaah.telaah_id, table_telaah.telaah_perihal, table_telaah.telaah_tanggalberangkat,
                                    table_telaah.telaah_tanggalkembali, table_telaah.telaah_provinsitujuan, table_telaah.telaah_kategori,
                                    table_telaah.telaah_status, table_telaah.telaah_pelaksana, table_pegawai.pegawai_nama, table_skpd.skpd_nama,
                                    table_provinsi.provinsi, table_anggotadprd.anggotadprd_name');
		$this->db->from('table_pegawai');
		$this->db->join('table_telaah','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->join('table_provinsi','table_telaah.telaah_provinsitujuan=table_provinsi.provinsi_id','left');
		$this->db->where('(table_telaah.telaah_domainperjalanan = 1 OR table_telaah.telaah_domainperjalanan = 2)');
		$this->db->where('table_telaah.telaah_status',2);
		$query1 = $this->db->get_compiled_select(); 

		$this->db->select('telaah_waktuinput, table_telaah.telaah_id, table_telaah.telaah_perihal, table_telaah.telaah_tanggalberangkat,
                                    table_telaah.telaah_tanggalkembali, table_telaah.telaah_provinsitujuan, table_telaah.telaah_kategori,
                                    table_telaah.telaah_status, table_telaah.telaah_pelaksana, table_pimpinan.pegawai_nama, table_skpd.skpd_nama,
                                    table_provinsi.provinsi, table_anggotadprd.anggotadprd_name');
		$this->db->from('table_pimpinan');
		$this->db->join('table_telaah','table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id','left');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','left');
		$this->db->join('table_skpd','table_pimpinan.skpd_id=table_skpd.skpd_id','left');
		$this->db->join('table_provinsi','table_telaah.telaah_provinsitujuan=table_provinsi.provinsi_id','left');
		$this->db->where('(table_telaah.telaah_domainperjalanan = 1 OR table_telaah.telaah_domainperjalanan = 2)');
		$this->db->where('table_telaah.telaah_status',2);
		$query2 = $this->db->get_compiled_select();
		
		$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT 10");
		return $query->result();
		
    }

    public function countById($id) {
        $this->db->select('count(t.telaah_id) as jumlah');
        $this->db->from('table_telaah t');
        $this->db->where('t.telaah_kategori', $id);
        return $this->db->get()->result();
    }

    public function countByStatus($id1, $id2) {
        $query = $this->db->query("SELECT count(t.telaah_id) as jumlah
                                    FROM table_telaah t 
                                    WHERE t.telaah_kategori = '$id1' AND t.telaah_status = '$id2'
                                    ");
        return $query->result();
    }

	public function countById2($id) {
        $query = $this->db->query("SELECT count(t.telaah_id) as jumlah
                                    FROM table_telaah t 
                                    WHERE t.telaah_kategori = '$id'
                                    ");
        return $query->result_array();
    }

    public function countByStatus2($id1, $id2) {
        $this->db->select('count(t.telaah_id) as jumlah');
        $this->db->from('table_telaah t');
        $this->db->where('t.telaah_kategori', $id1);
        $this->db->where('t.telaah_status', $id2);
        return $this->db->get()->result_array();
    }

    public function dataLive($day) {
        $this->db->select('t.telaah_id, t.telaah_perihal, t.telaah_tanggalberangkat,
                           t.telaah_tanggalkembali, t.telaah_provinsitujuan, t.telaah_kategori,
                           t.telaah_status, t.telaah_pelaksana, p.pegawai_nama, s.skpd_nama, 
                           pr.latitude, pr.longitude, pr.provinsi');
        $this->db->from('table_telaah t');
        $this->db->join('table_pegawai p', 't.telaah_pelaksana = p.pegawai_id', 'left');
        $this->db->join('table_skpd s', 'p.skpd_id = s.skpd_id', 'left');
        $this->db->join('table_provinsi pr', 't.telaah_provinsitujuan = pr.provinsi_id', 'left');
        $this->db->where('t.telaah_domainperjalanan', 1);
        $this->db->where('t.telaah_status', 2);
        $this->db->group_start();
            $this->db->where('t.telaah_kategori', 2);
            $this->db->or_where('t.telaah_kategori', 10);
            $this->db->or_where('t.telaah_kategori', 5);
        $this->db->group_end();
        $this->db->where('t.telaah_tanggalberangkat <=', $day);
        $this->db->where('t.telaah_tanggalkembali >=', $day);
        $this->db->order_by('t.telaah_waktuinput', 'ASC');
        return $this->db->get()->result();
    }
    
    public function get_prov_id($day) {
        $query = $this->db->query("SELECT 
                                    pr.latitude, pr.longitude, pr.provinsi_id, pr.provinsi
                                    FROM table_telaah t 
                                    LEFT JOIN table_pegawai p 
                                    ON t.telaah_pelaksana = p.pegawai_id
                                    LEFT JOIN table_skpd s
                                    ON p.skpd_id = s.skpd_id
                                    LEFT JOIN table_provinsi pr 
                                    ON t.telaah_provinsitujuan=pr.provinsi_id
                                    WHERE t.telaah_domainperjalanan = 1
                                    AND t.telaah_status = 2
                                    AND (t.telaah_kategori = 2 OR t.telaah_kategori = 10 OR t.telaah_kategori = 5)
                                    AND (t.telaah_tanggalberangkat<='$day' AND t.telaah_tanggalkembali>='$day')
                                    GROUP BY provinsi_id
									ORDER BY t.telaah_waktuinput ASC
									");
        return $query->result();
    }
    
    public function get_dataLive($provinsi_id, $day) {
        $this->db->select('t.telaah_id, t.telaah_perihal, t.telaah_tanggalberangkat,
                           t.telaah_tanggalkembali, t.telaah_provinsitujuan, t.telaah_kategori,
                           t.telaah_status, t.telaah_pelaksana, p.pegawai_nama, s.skpd_nama, 
                           pr.latitude, pr.longitude, pr.provinsi');
        $this->db->from('table_telaah t');
        $this->db->join('table_pegawai p', 't.telaah_pelaksana = p.pegawai_id', 'left');
        $this->db->join('table_skpd s', 'p.skpd_id = s.skpd_id', 'left');
        $this->db->join('table_provinsi pr', 't.telaah_provinsitujuan = pr.provinsi_id', 'left');
        $this->db->where('t.telaah_domainperjalanan', 1);
        $this->db->where('t.telaah_status', 2);
        $this->db->group_start();
            $this->db->where('t.telaah_kategori', 2);
            $this->db->or_where('t.telaah_kategori', 10);
            $this->db->or_where('t.telaah_kategori', 5);
        $this->db->group_end();
        $this->db->where('t.telaah_tanggalberangkat <=', $day);
        $this->db->where('t.telaah_tanggalkembali >=', $day);
        $this->db->where('provinsi_id', $provinsi_id);
        $this->db->order_by('t.telaah_waktuinput', 'ASC');
        return $this->db->get()->result();
    }
    
    // public function daftar_perjalanan($skpd_id) {
		// $date = date('Y-m-d');
        // $query = $this->db->query("SELECT t.telaah_id, t.telaah_perihal, t.telaah_tanggalberangkat,
                                    // t.telaah_tanggalkembali, t.telaah_provinsitujuan, t.telaah_kategori,
                                    // t.telaah_status, t.telaah_pelaksana, p.pegawai_nama, s.skpd_nama, 
                                    // pr.latitude, pr.longitude, pr.provinsi
                                    // FROM table_telaah t 
                                    // LEFT JOIN table_pegawai p 
                                    // ON t.telaah_pelaksana = p.pegawai_id
                                    // LEFT JOIN table_skpd s
                                    // ON p.skpd_id = s.skpd_id
                                    // LEFT JOIN table_provinsi pr 
                                    // ON t.telaah_provinsitujuan=pr.provinsi_id
                                    // WHERE t.telaah_status = 2
									// AND s.skpd_id=$skpd_id
                                    // AND (t.telaah_tanggalberangkat<='$date' AND t.telaah_tanggalkembali>='$date')
                                    // ORDER BY t.telaah_waktuinput ASC");
        // return $query->result();
    // }
	
	public function daftar_perjalanan($order_by, $limit, $start, $skpd_id, $key) {
		$date = date('Y-m-d');
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_telaah.telaah_status', 2);
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->where("(telaah_tanggalberangkat<='$date' AND telaah_tanggalkembali>='$date')");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function jumlah_list_perjalanan($id_jabatan, $kategori_id) 
	{
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->where('table_telaah.telaah_kategori',$kategori_id);
		return $this->db->count_all_results(); 
    }
	
	public function list_perjalanan($id_jabatan, $kategori_id, $order_by, $limit, $start, $key) {
		
		if($kategori_id==8){
			$this->db->select('table_telaah.telaah_id, telaah_perihal, telaah_tanggalberangkat,telaah_tanggalkembali, telaah_provinsitujuan, telaah_kategori,
							telaah_status, telaah_pelaksana, pegawai_nama, skpd_nama, latitude, longitude, provinsi');
			$this->db->from('table_telaah');
			$this->db->join('table_pimpinan','table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id','left');
			$this->db->join('table_skpd','table_skpd.skpd_id=table_pimpinan.skpd_id','left');
		} else if($kategori_id==3){
			$this->db->select('table_telaah.telaah_id, telaah_perihal, telaah_tanggalberangkat,telaah_tanggalkembali, telaah_provinsitujuan, telaah_kategori,
							telaah_status, telaah_pelaksana, table_anggotadprd.anggotadprd_name as pegawai_nama, skpd_nama, latitude, longitude, provinsi');
			$this->db->from('table_telaah');
			$this->db->join('table_anggotadprd','table_anggotadprd.anggotadprd_id=table_telaah.telaah_pelaksana', 'LEFT');	
			$this->db->join('table_skpd',"table_skpd.jenis_skpd='2'", 'LEFT');
		} else {
			$this->db->select('table_telaah.telaah_id, telaah_perihal, telaah_tanggalberangkat,telaah_tanggalkembali, telaah_provinsitujuan, telaah_kategori,
							telaah_status, telaah_pelaksana, pegawai_nama, skpd_nama, latitude, longitude, provinsi');
			$this->db->from('table_telaah');
			$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
			$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		}
		
		$this->db->join('table_provinsi','table_telaah.telaah_provinsitujuan=table_provinsi.provinsi_id','left');
		$this->db->where('table_telaah.telaah_kategori',$kategori_id);
		// if($id_jabatan){
			// $this->db->where('table_pegawai.pegawai_jabatan',3);
		// }
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
    }
	
	##
	
	public function user($id) {
		 $query = $this->db->query("SELECT users.*, groups.id as group_id, groups.bgcolor, groups.name, skpd_nama, jenis_skpd
                                    FROM users 
                                    JOIN users_groups ON users.id = users_groups.user_id
                                    JOIN groups ON users_groups.group_id = groups.id
                                    JOIN table_skpd ON users.skpd_id = table_skpd.skpd_id
                                    WHERE users.id='$id'");
		return $query->result_array();
    }
	
    ## Total Anggaran Keseluruhan
	public function total_anggaran_keseluruhan(){
		$this->db->select('SUM(pagu) as total_anggaran_keseluruhan');
		$this->db->where('skpd_id !=23 and skpd_id !=33 AND skpd_id !=34 AND skpd_id !=183');
		$this->db->from('table_anggaran');
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	##Total Anggaran SKPD
	public function total_anggaran_skpd($skpd_id){
		$this->db->select('SUM(pagu) as total_anggaran_keseluruhan');
		$this->db->from('table_anggaran');
		$this->db->where('skpd_id', $skpd_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function rincian_belanja($skpd_id) {
		if($skpd_id){
			$query  = $this->db->query("SELECT (SELECT SUM(tarif*item) ) as jumlah_rincian_belanja_keseluruhan  FROM table_rincian_biaya a 
									LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
									LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran 
									WHERE c.skpd_id='$skpd_id' ");
		} else {
			$query  = $this->db->query("SELECT (SELECT SUM(tarif*item) ) as jumlah_rincian_belanja_keseluruhan FROM table_rincian_biaya a 
									LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
									LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran ");
		}
		return $query->result_array();
	}
	
	public function pengeluaran_rill($skpd_id) {
		if($skpd_id){
			$query  = $this->db->query("SELECT SUM(tarif) as jumlah_pengeluaran_rill_keseluruhan FROM table_pengeluaran_rill a
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran
										WHERE c.skpd_id='$skpd_id' ");
		} else {
			$query  = $this->db->query("SELECT SUM(tarif) as jumlah_pengeluaran_rill_keseluruhan FROM table_pengeluaran_rill a
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran");
		}
		return $query->result_array();
	}
	
	## Total Anggaran Dalam Daerah
	public function total_anggaran_dalam_daerah($skpd_id){
		$this->db->select('SUM(pagu) as total_anggaran_dalam_daerah');
		$this->db->from('table_anggaran');
		$this->db->where('jenis_anggaran', 1);
		if($skpd_id){
			$this->db->where('skpd_id', $skpd_id);
		}
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	
	public function rincian_belanja_dalam_daerah($skpd_id) {
		if($skpd_id){
			$query  = $this->db->query("SELECT (SELECT SUM(tarif*item) ) as jumlah_rincian_belanja_dalam_daerah FROM table_rincian_biaya a 
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran 
										WHERE c.skpd_id = '$skpd_id' AND c.jenis_anggaran=1");
		} else {
			$query  = $this->db->query("SELECT (SELECT SUM(tarif*item) ) as jumlah_rincian_belanja_dalam_daerah FROM table_rincian_biaya a 
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran 
										WHERE c.jenis_anggaran=1");
		}
		return $query->result_array();
	}
	
	public function pengeluaran_rill_dalam_daerah($skpd_id) {
		if($skpd_id){
			$query  = $this->db->query("SELECT SUM(tarif) as jumlah_pengeluaran_rill_dalam_daerah FROM table_pengeluaran_rill a
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran
										WHERE c.skpd_id = '$skpd_id' AND c.jenis_anggaran=1");
		} else {
			$query  = $this->db->query("SELECT SUM(tarif) as jumlah_pengeluaran_rill_dalam_daerah FROM table_pengeluaran_rill a
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran
										WHERE c.jenis_anggaran=1");
		}
		return $query->result_array();
	}
	
	## Total Anggaran Luar Daerah
	public function total_anggaran_luar_daerah($skpd_id){
		$this->db->select('SUM(pagu) as total_anggaran_luar_daerah');
		$this->db->from('table_anggaran');
		$this->db->where('jenis_anggaran', 2);
		if($skpd_id){
			$this->db->where('skpd_id', $skpd_id);
		}
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function rincian_belanja_luar_daerah($skpd_id) {
		if($skpd_id){
			$query  = $this->db->query("SELECT (SELECT SUM(tarif*item)) as jumlah_rincian_belanja_luar_daerah FROM table_rincian_biaya a 
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran 
										WHERE c.skpd_id = '$skpd_id' AND c.jenis_anggaran=2");
		} else {
			$query  = $this->db->query("SELECT (SELECT SUM(tarif*item)) as jumlah_rincian_belanja_luar_daerah FROM table_rincian_biaya a 
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran 
										WHERE c.jenis_anggaran=2");
		}
		return $query->result_array();
	}
	
	public function pengeluaran_rill_luar_daerah($skpd_id) {
		if($skpd_id){
			$query  = $this->db->query("SELECT SUM(tarif) as jumlah_pengeluaran_rill_luar_daerah FROM table_pengeluaran_rill a
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran
										WHERE c.skpd_id = '$skpd_id' AND c.jenis_anggaran=2");
		} else {
			$query  = $this->db->query("SELECT SUM(tarif) as jumlah_pengeluaran_rill_luar_daerah FROM table_pengeluaran_rill a
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran
										WHERE c.jenis_anggaran=2");
		}
		return $query->result_array();
	}
	
	## Total Anggaran Bimtek
	public function total_anggaran_bimtek($skpd_id){
		$this->db->select('SUM(pagu) as total_anggaran_bimtek');
		$this->db->from('table_anggaran');
		$this->db->where('jenis_anggaran', 3);
		if($skpd_id){
			$this->db->where('skpd_id', $skpd_id);
		}
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function rincian_belanja_bimtek($skpd_id) {
		if($skpd_id){
			$query  = $this->db->query("SELECT (SELECT SUM(tarif*item)) as jumlah_rincian_belanja_bimtek FROM table_rincian_biaya a 
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran 
										WHERE c.skpd_id = '$skpd_id' AND c.jenis_anggaran=3");
		} else {
			$query  = $this->db->query("SELECT (SELECT SUM(tarif*item)) as jumlah_rincian_belanja_bimtek FROM table_rincian_biaya a 
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran 
										WHERE c.jenis_anggaran=3");
		}
		return $query->result_array();
	}
	
	public function pengeluaran_rill_bimtek($skpd_id) {
		if($skpd_id){
			$query  = $this->db->query("SELECT SUM(tarif) as jumlah_pengeluaran_rill_bimtek FROM table_pengeluaran_rill a
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran
										WHERE c.skpd_id = '$skpd_id' AND c.jenis_anggaran=3");
		} else {
			$query  = $this->db->query("SELECT SUM(tarif) as jumlah_pengeluaran_rill_bimtek FROM table_pengeluaran_rill a
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran
										WHERE c.jenis_anggaran=3");
		}
		return $query->result_array();
	}
	
	## DETAIL TELAAH
	public function detail_telaah($telaah_id, $kategori_id) {
		if($kategori_id==3){
			$this->db->select('*, CASE
								WHEN table_telaah.telaah_domainperjalanan = 1 THEN "Luar Daerah Luar Provinsi (LDLP)"
								WHEN table_telaah.telaah_domainperjalanan = 2 THEN "Luar Daerah Dalam Provinsi (LDDP)"
								ELSE "Dalam Daerah" END AS domain_perjalanan,
								table_anggotadprd.anggotadprd_id as pegawai_id, table_anggotadprd.anggotadprd_name as pegawai_nama, table_anggotadprd.anggotadprd_jabatan as pegawai_namajabatan');
			$this->db->from('table_telaah');
			$this->db->join('table_provinsi','table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');
			$this->db->join('table_kabkot','table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');
			$this->db->join('table_anggotadprd','table_anggotadprd.anggotadprd_id=table_telaah.telaah_pelaksana', 'LEFT');	
			$this->db->join('table_skpd',"table_skpd.jenis_skpd='2'", 'LEFT');
		} else if ($kategori_id==8){
			$this->db->select('*, CASE
								WHEN table_telaah.telaah_domainperjalanan = 1 THEN "Luar Daerah Luar Provinsi (LDLP)"
								WHEN table_telaah.telaah_domainperjalanan = 2 THEN "Luar Daerah Dalam Provinsi (LDDP)"
								ELSE "Dalam Daerah" END AS domain_perjalanan');
			$this->db->from('table_telaah');
			$this->db->join('table_provinsi','table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');
			$this->db->join('table_kabkot','table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');
			$this->db->join('table_pimpinan','table_pimpinan.pegawai_id=table_telaah.telaah_pelaksana', 'LEFT');
			$this->db->join('table_skpd','table_skpd.skpd_id=table_pimpinan.skpd_id', 'LEFT');
		} else {	
			$this->db->select('*, CASE
								WHEN table_telaah.telaah_domainperjalanan = 1 THEN "Luar Daerah Luar Provinsi (LDLP)"
								WHEN table_telaah.telaah_domainperjalanan = 2 THEN "Luar Daerah Dalam Provinsi (LDDP)"
								ELSE "Dalam Daerah" END AS domain_perjalanan');
			$this->db->from('table_telaah');
			$this->db->join('table_provinsi','table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');
			$this->db->join('table_kabkot','table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');
			$this->db->join('table_pegawai','table_pegawai.pegawai_id=table_telaah.telaah_pelaksana', 'LEFT');
			$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');
		}
		$this->db->join('table_anggaran','table_anggaran.id_anggaran=table_telaah.telaah_kegiatan', 'LEFT');
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function get_rincian($telaah_id,$pegawai_id,$telaah_kategori)
	{
		if($telaah_kategori==3){
			$this->db->select('*,table_anggotadprd.anggotadprd_id as pegawai_id, table_anggotadprd.anggotadprd_name as pegawai_nama, table_anggotadprd.anggotadprd_jabatan as pegawai_namajabatan');
			$this->db->from('table_rincian_biaya');
			$this->db->join('table_anggotadprd','table_anggotadprd.anggotadprd_id=table_rincian_biaya.pegawai_id', 'LEFT');	
		} else if($telaah_kategori==8){
			$this->db->select('*');
			$this->db->from('table_rincian_biaya');
			$this->db->join('table_pimpinan','table_rincian_biaya.pegawai_id = table_pimpinan.pegawai_id','LEFT');
		} else {
			$this->db->select('*');
			$this->db->from('table_rincian_biaya');
			$this->db->join('table_pegawai','table_rincian_biaya.pegawai_id = table_pegawai.pegawai_id','LEFT');
		}
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('table_rincian_biaya.pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_pengeluaran_rill($telaah_id,$pegawai_id,$telaah_kategori)
	{
		if($telaah_kategori==3){
			$this->db->select('*,table_anggotadprd.anggotadprd_id as pegawai_id, table_anggotadprd.anggotadprd_name as pegawai_nama, table_anggotadprd.anggotadprd_jabatan as pegawai_namajabatan');
			$this->db->from('table_pengeluaran_rill');
			$this->db->join('table_anggotadprd','table_anggotadprd.anggotadprd_id=table_pengeluaran_rill.pegawai_id', 'LEFT');	
		} else if($telaah_kategori==8){
			$this->db->select('*');
			$this->db->from('table_pengeluaran_rill');
			$this->db->join('table_pimpinan','table_pengeluaran_rill.pegawai_id = table_pimpinan.pegawai_id','LEFT');
		} else {
			$this->db->select('*');
			$this->db->from('table_pengeluaran_rill');
			$this->db->join('table_pegawai','table_pengeluaran_rill.pegawai_id = table_pegawai.pegawai_id','LEFT');
		}
		$this->db->where('telaah_id',$telaah_id);
		$this->db->where('table_pengeluaran_rill.pegawai_id',$pegawai_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function total_rincian($anggaran_id) {
		$query  = $this->db->query("SELECT (SELECT SUM(tarif*item)) AS total_rincian FROM table_rincian_biaya a 
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id 
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran 
										WHERE b.telaah_kegiatan='$anggaran_id'");
	
		return $query->result_array();
	}
	
	public function total_pengeluaran_rill($anggaran_id) {
		$query  = $this->db->query("SELECT SUM(tarif) as total_pengeluaran_rill FROM table_pengeluaran_rill a
										LEFT JOIN table_telaah b ON a.telaah_id=b.telaah_id
										LEFT JOIN table_anggaran c ON b.telaah_kegiatan=c.id_anggaran
										WHERE b.telaah_kegiatan='$anggaran_id'");
	
		return $query->result_array();
	}
	
	public function get_telaah($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get();
		return $query->result_array();
		
		
	}

	################################################# GET TOKEN NEXT ####################################################
	// SELECT table_telaah.telaah_id, table_telaah.telaah_kategori, table_pegawai.pegawai_nama, anggotadprd_name,
	// 	users.id, users.username, users.token, table_pegawai.skpd_id, table_pegawai.bagian_id, jenis_skpd_id, table_bagian.asisten_id

	public function getTokenNext($telaah_id) {
		$query  = $this->db->query("
		SELECT users.token
		
		FROM table_telaah
		LEFT JOIN table_pegawai ON table_pegawai.pegawai_id = table_telaah.telaah_pelaksana
		LEFT JOIN table_pimpinan ON table_pimpinan.pegawai_id = table_telaah.telaah_pelaksana
		LEFT JOIN table_anggotadprd ON table_anggotadprd.anggotadprd_id=table_telaah.telaah_pelaksana
		left JOIN table_timeline1 ON table_timeline1.telaah_id = table_telaah.telaah_id 
		left JOIN table_timeline2 ON table_timeline2.telaah_id = table_telaah.telaah_id 
		left JOIN table_timeline3 ON table_timeline3.telaah_id = table_telaah.telaah_id 
		left JOIN table_timeline4 ON table_timeline4.telaah_id = table_telaah.telaah_id 
		left JOIN table_timeline5 ON table_timeline5.telaah_id = table_telaah.telaah_id 
		left JOIN table_timeline6 ON table_timeline6.telaah_id = table_telaah.telaah_id 
		left JOIN table_timeline7 ON table_timeline7.telaah_id = table_telaah.telaah_id 
		left JOIN table_timeline8 ON table_timeline8.telaah_id = table_telaah.telaah_id 
		left JOIN table_timeline9 ON table_timeline9.telaah_id = table_telaah.telaah_id 
		left JOIN table_timeline10 ON table_timeline10.telaah_id = table_telaah.telaah_id 
		left JOIN table_timeline11 ON table_timeline11.telaah_id = table_telaah.telaah_id 
		left JOIN table_relasi_kelurahan ON table_pegawai.skpd_id = table_relasi_kelurahan.id_kelurahan
		left JOIN table_skpd ON table_pegawai.skpd_id = table_skpd.skpd_id
		left JOIN table_jenis_skpd ON table_skpd.jenis_skpd = table_jenis_skpd.jenis_skpd_id
		LEFT JOIN table_bagian ON table_bagian.bagian_id = table_pegawai.bagian_id
		LEFT JOIN table_asisten ON table_asisten.asisten_id = table_bagian.asisten_id
		LEFT JOIN users ON users.skpd_id = 
		CASE	
			 WHEN table_timeline1.timeline_kabid_id=0 AND jenis_skpd_id!=7 THEN table_pegawai.skpd_id
			WHEN table_timeline1.timeline_kabid_id=0 AND jenis_skpd_id=7 THEN 36
			 WHEN table_timeline1.timeline_sekdis_id=0 AND jenis_skpd_id!=7 THEN table_pegawai.skpd_id
			 WHEN table_timeline1.timeline_sekdis_id=0 AND jenis_skpd_id=7 THEN 36
			WHEN table_timeline1.timeline_kadis_id=0 AND jenis_skpd_id!=7 THEN table_pegawai.skpd_id
			WHEN table_timeline1.timeline_kadis_id=0 AND jenis_skpd_id=7 THEN 36
			
			WHEN table_timeline2.timeline_kadis_id=0 THEN table_pegawai.skpd_id
			WHEN table_timeline2.timeline_sekda_id=0 THEN 3
			WHEN table_timeline2.timeline_walikota_id=0 THEN 177
			
			WHEN table_timeline3.timeline_kasubid_id=0 THEN 2
			WHEN table_timeline3.timeline_sekwan_id=0 THEN 2
			WHEN table_timeline3.timeline_kadprd_id=0 THEN 2
			
			WHEN table_timeline4.timeline_kabag_id=0 THEN table_pegawai.skpd_id
			WHEN table_timeline4.timeline_asisten_id=0 THEN 3
			WHEN table_timeline4.timeline_sekda_id=0 THEN 3
			WHEN table_timeline4.timeline_walikota_id=0 THEN 177
			
			WHEN table_timeline5.timeline_sekcam_id=0 THEN table_relasi_kelurahan.id_kecamatan
			WHEN table_timeline5.timeline_camat_id=0 THEN table_relasi_kelurahan.id_kecamatan
			WHEN table_timeline5.timeline_sekda_id=0 THEN 3
			WHEN table_timeline5.timeline_walikota_id=0 THEN 177
			
			WHEN table_timeline6.timeline_kabag_id=0 THEN table_pegawai.skpd_id
			WHEN table_timeline6.timeline_sekwan_id=0 THEN 2
			
			WHEN table_timeline7.timeline_lurah_id=0 THEN table_pegawai.skpd_id
			WHEN table_timeline7.timeline_sekcam_id=0 THEN table_relasi_kelurahan.id_kecamatan
			WHEN table_timeline7.timeline_camat_id=0 THEN table_relasi_kelurahan.id_kecamatan
			
			WHEN table_timeline8.timeline_kabag_id=0 THEN table_pimpinan.skpd_id 
			WHEN table_timeline8.timeline_sekda_id=0 THEN 3
			WHEN table_timeline8.timeline_walikota_id=0 THEN 177
			
			WHEN table_timeline9.timeline_kabag_id=0 THEN table_pegawai.skpd_id 
			WHEN table_timeline9.timeline_asisten_id=0 THEN 3
			WHEN table_timeline9.timeline_sekda_id=0 THEN 3
			
			WHEN table_timeline10.timeline_kabag_id=0 THEN table_pegawai.skpd_id 
			WHEN table_timeline10.timeline_sekwan_id=0 THEN 2
			WHEN table_timeline10.timeline_sekda_id=0 THEN 3
			WHEN table_timeline10.timeline_walikota_id=0 THEN 177
			
			WHEN table_timeline11.timeline_kapus_id=0 THEN table_pegawai.skpd_id 
		END 
		LEFT JOIN users_groups ON users.id = users_groups.user_id
		LEFT JOIN groups ON groups.id = users_groups.group_id
		LEFT JOIN users_groups_bagian ON users_groups_bagian.user_id = users.id
		LEFT JOIN users_groups_asisten ON users_groups_asisten.user_id = users.id
		WHERE table_telaah.telaah_id = '$telaah_id'
		AND CASE
			WHEN table_timeline1.timeline_kabid_id=0 THEN groups.id=2 
			WHEN table_timeline1.timeline_sekdis_id=0 THEN groups.id=3
			WHEN table_timeline1.timeline_kadis_id=0 THEN groups.id=4 
			
			WHEN table_timeline2.timeline_sekdis_id=0 THEN groups.id=3
			WHEN table_timeline2.timeline_kadis_id=0 THEN groups.id=4 
			WHEN table_timeline2.timeline_sekda_id=0 THEN groups.id=6
			WHEN table_timeline2.timeline_walikota_id=0 THEN groups.id=8
			
			WHEN table_timeline3.timeline_kasubid_id=0 THEN groups.id=2
			WHEN table_timeline3.timeline_sekwan_id=0 THEN groups.id=10
			WHEN table_timeline3.timeline_kadprd_id=0 THEN groups.id=7
			
			WHEN table_timeline4.timeline_kabag_id=0 THEN groups.id=2 AND users_groups_bagian.bagian_id=table_pegawai.bagian_id
			WHEN table_timeline4.timeline_asisten_id=0 THEN groups.id=5 
			WHEN table_timeline4.timeline_sekda_id=0 THEN groups.id=6
			WHEN table_timeline4.timeline_walikota_id=0 THEN groups.id=8
			
			WHEN table_timeline5.timeline_sekcam_id=0 THEN groups.id=12
			WHEN table_timeline5.timeline_camat_id=0 THEN groups.id=11 
			WHEN table_timeline5.timeline_sekda_id=0 THEN groups.id=6
			WHEN table_timeline5.timeline_walikota_id=0 THEN groups.id=8
			
			WHEN table_timeline6.timeline_kabag_id=0 THEN groups.id=2
			WHEN table_timeline6.timeline_sekwan_id=0 THEN groups.id=10
			
			WHEN table_timeline7.timeline_lurah_id=0 THEN groups.id=13
			WHEN table_timeline7.timeline_sekcam_id=0 THEN groups.id=12
			WHEN table_timeline7.timeline_camat_id=0 THEN groups.id=11
			
			WHEN table_timeline8.timeline_kabag_id=0 THEN groups.id=2 AND users_groups_bagian.bagian_id=table_pimpinan.bagian_id
			WHEN table_timeline8.timeline_sekda_id=0 THEN groups.id=6
			WHEN table_timeline8.timeline_walikota_id=0 THEN groups.id=8
			
			WHEN table_timeline9.timeline_kabag_id=0 THEN groups.id=2 AND users_groups_bagian.bagian_id=table_pegawai.bagian_id
			WHEN table_timeline9.timeline_asisten_id=0 THEN groups.id=5 AND table_bagian.asisten_id=users_groups_asisten.asisten_id
			WHEN table_timeline9.timeline_sekda_id=0 THEN groups.id=6
			
			WHEN table_timeline10.timeline_kabag_id=0 THEN groups.id=2
			WHEN table_timeline10.timeline_sekwan_id=0 THEN groups.id=10
			WHEN table_timeline10.timeline_sekda_id=0 THEN groups.id=6
			WHEN table_timeline10.timeline_walikota_id=0 THEN groups.id=8
			
			WHEN table_timeline11.timeline_kapus_id=0 THEN groups.id=16
		END ");
	
		return $query->result_array();
	}
	
	################################################# TELAAH MASUK ####################################################
	
	## KABID OPD
	public function kabid_opd($order_by, $limit, $start, $skpd_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("table_telaah.telaah_kategori", 1);
		$this->db->where('table_timeline1.timeline_kabid_id',0);
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->where('telaah_sekretariat != 1 ');
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## SEKDIS
	public function sekdis($order_by, $limit, $start, $skpd_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("(table_telaah.telaah_kategori='1' OR table_telaah.telaah_kategori='2')");
		$this->db->where("(table_timeline1.timeline_kabid_id = '1' 
			AND table_timeline1.timeline_sekdis_id = '0' 
			OR table_timeline2.timeline_sekdis_id = '0'
			OR table_timeline1.timeline_sekdis_id = '5' 
			OR table_timeline2.timeline_sekdis_id = '5'
			OR table_timeline2.timeline_kadis_id = '5'
			OR table_timeline2.timeline_sekda_id = '5'
			OR table_timeline2.timeline_walikota_id = '5')");
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## KADIS
	public function kadis($order_by, $limit, $start, $skpd_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("(table_telaah.telaah_kategori='1' OR table_telaah.telaah_kategori='2')");
		$this->db->where("(table_timeline1.timeline_sekdis_id = '1' AND table_timeline1.timeline_kadis_id = 0
			OR table_timeline2.timeline_sekdis_id = '1' AND table_timeline2.timeline_kadis_id = 0 
			OR table_timeline1.timeline_sekdis_id = '1' AND table_timeline1.timeline_kadis_id = 5 
			OR table_timeline2.timeline_sekdis_id = '1' AND table_timeline2.timeline_kadis_id = 5 )");
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## SEKDA
	public function sekda($order_by, $limit, $start, $key) {
		
		if($limit){
			$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		}else {
			$this->db->select('COUNT(*) AS `numrows`');
		}
		
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id 
						AND (table_telaah.telaah_domainperjalanan = 1 OR table_telaah.telaah_domainperjalanan = 2)','left');
		$this->db->join('table_timeline4','table_telaah.telaah_id=table_timeline4.telaah_id','left');
		$this->db->join('table_timeline5','table_telaah.telaah_id=table_timeline5.telaah_id','left');
		$this->db->join('table_timeline9','table_telaah.telaah_id=table_timeline9.telaah_id','left');
		$this->db->join('table_timeline10','table_telaah.telaah_id=table_timeline10.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("(table_telaah.telaah_kategori = '2'
			OR table_telaah.telaah_kategori = '4'
			OR table_telaah.telaah_kategori='5'
			OR table_telaah.telaah_kategori='9'
			OR table_telaah.telaah_kategori='10'
			)");
		$this->db->where("((table_timeline2.timeline_kadis_id = '1' AND table_timeline2.timeline_sekda_id = '0') 
			OR (table_timeline2.timeline_kadis_id = '1' AND table_timeline2.timeline_sekda_id = '5')
			OR (table_timeline4.timeline_asisten_id = '1' AND table_timeline4.timeline_sekda_id = '0')
			OR (table_timeline4.timeline_asisten_id = '1' AND table_timeline4.timeline_sekda_id = '5')
			OR (table_timeline9.timeline_asisten_id = '1' AND table_timeline9.timeline_sekda_id = '0')
			OR (table_timeline9.timeline_asisten_id = '1' AND table_timeline9.timeline_sekda_id = '5')
			OR (table_timeline5.timeline_camat_id = '1' AND table_timeline5.timeline_sekda_id = '0')
			OR (table_timeline5.timeline_camat_id = '1' AND table_timeline5.timeline_sekda_id = '5')
			OR (table_timeline10.timeline_sekwan_id = '1' AND table_timeline10.timeline_sekda_id = '0')
			OR (table_timeline10.timeline_sekwan_id = '1' AND table_timeline10.timeline_sekda_id = '5'))");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		$query1 = $this->db->get_compiled_select(); 
		
		if($limit){
			$this->db->select('table_telaah.*, table_pimpinan.*, skpd_nama');
		} else {
			$this->db->select('COUNT(*) AS `numrows`');
		}
		$this->db->from('table_telaah');
		$this->db->join('table_pimpinan','table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_timeline8','table_telaah.telaah_id=table_timeline8.telaah_id','left');
		$this->db->join('table_skpd','table_pimpinan.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("table_telaah.telaah_kategori", 8);
		
		$this->db->where('table_timeline8.timeline_kabag_id',1);
		$this->db->group_start();
				$this->db->where('table_timeline8.timeline_sekda_id',0);
				$this->db->or_where('table_timeline8.timeline_sekda_id',5);
		$this->db->group_end();
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		$query2 = $this->db->get_compiled_select();
	
		if($limit){
			if($order_by){
				if($start){
					$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput ".$order_by." LIMIT $start, $limit");
				} else {
					$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput ".$order_by." LIMIT 0, $limit");
				}
			} else {	
				if($start){
					$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT $start, $limit");
				} else {
					$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT 0, $limit");
				}
			}
			return $query->result();
		}else{
			$query = $this->db->query($query1." UNION ALL ".$query2);
			return $query->result_array();
		}
		
	}
	
	## WALIKOTA
	public function walikota($order_by, $limit, $start, $key) {
		if($limit){
			$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		}else {
			$this->db->select('COUNT(*) AS `numrows`');
		}
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id 
						AND (table_telaah.telaah_domainperjalanan = 1 OR table_telaah.telaah_domainperjalanan = 2)','left');
		$this->db->join('table_timeline4','table_telaah.telaah_id=table_timeline4.telaah_id','left');
		$this->db->join('table_timeline5','table_telaah.telaah_id=table_timeline5.telaah_id','left');
		$this->db->join('table_timeline10','table_telaah.telaah_id=table_timeline10.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("(table_telaah.telaah_kategori='2' OR table_telaah.telaah_kategori='4' 
		OR table_telaah.telaah_kategori='5' OR table_telaah.telaah_kategori='10')");
		$this->db->where("(table_timeline2.timeline_sekda_id = '1' AND table_timeline2.timeline_walikota_id = '0')
			OR (table_timeline2.timeline_sekda_id = '1' AND table_timeline2.timeline_walikota_id = '5')
			OR (table_timeline4.timeline_sekda_id = '1' AND table_timeline4.timeline_walikota_id = '0')
			OR (table_timeline4.timeline_sekda_id = '1' AND table_timeline4.timeline_walikota_id = '5')
			OR (table_timeline5.timeline_sekda_id = '1' AND table_timeline5.timeline_walikota_id = '0')
			OR (table_timeline5.timeline_sekda_id = '1' AND table_timeline5.timeline_walikota_id = '5')
			OR (table_timeline10.timeline_sekda_id = '1' AND table_timeline10.timeline_walikota_id = '0')
			OR (table_timeline10.timeline_sekda_id = '1' AND table_timeline10.timeline_walikota_id = '5')");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		$query1 = $this->db->get_compiled_select(); 
		
		if($limit){
			$this->db->select('table_telaah.*, table_pimpinan.*, skpd_nama');
		}else {
			$this->db->select('COUNT(*) AS `numrows`');
		}
		$this->db->from('table_telaah');
		$this->db->join('table_pimpinan','table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_timeline8','table_telaah.telaah_id=table_timeline8.telaah_id','left');
		$this->db->join('table_skpd','table_pimpinan.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("table_telaah.telaah_kategori", 8);
		
		$this->db->where('table_timeline8.timeline_sekda_id',1);
		$this->db->group_start();
				$this->db->where('table_timeline8.timeline_walikota_id',0);
				$this->db->or_where('table_timeline8.timeline_walikota_id',5);
		$this->db->group_end();
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		$query2 = $this->db->get_compiled_select();

		if($limit){
				if($order_by){
					if($start){
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput ".$order_by." LIMIT $start, $limit");
					} else {
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput ".$order_by." LIMIT 0, $limit");
					}
				} else {	
					if($start){
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT $start, $limit");
					} else {
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT 0, $limit");
					}
				}
				return $query->result();
			}else{
				$query = $this->db->query($query1." UNION ALL ".$query2);
				return $query->result_array();
			}

	}
	
	## KABID DPRD
	public function kabid_dprd($order_by, $limit, $start, $key) {
		$this->db->select('table_telaah.*, table_anggotadprd.*, table_pegawai.*, table_skpd.skpd_nama,
							IF (table_telaah.telaah_kategori = 3, table_anggotadprd.anggotadprd_name, table_pegawai.pegawai_nama) AS pegawai_nama,
							IF (table_telaah.telaah_kategori = 3, table_anggotadprd.anggotadprd_jabatan, table_pegawai.pegawai_namajabatan) AS pegawai_namajabatan');
							
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','left');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline3','table_telaah.telaah_id=table_timeline3.telaah_id','left');
		$this->db->join('table_timeline6','table_telaah.telaah_id=table_timeline6.telaah_id','left');
		$this->db->join('table_timeline10','table_telaah.telaah_id=table_timeline10.telaah_id','left');
		$this->db->join('table_skpd','table_anggotadprd.anggotadprd_id=table_skpd.skpd_id','left');
		$this->db->where("(table_telaah.telaah_kategori='3' OR table_telaah.telaah_kategori='6' OR table_telaah.telaah_kategori='10')");
		$this->db->where("(table_timeline3.timeline_kasubid_id = '0' OR table_timeline6.timeline_kabag_id = '0' OR table_timeline10.timeline_kabag_id = '0'
			OR table_timeline3.timeline_kasubid_id = '5' OR table_timeline6.timeline_kabag_id = '5' OR table_timeline10.timeline_kabag_id = '5')");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## SEKWAN
	public function sekwan($order_by, $limit, $start, $key) {
		$this->db->select('table_telaah.*, table_anggotadprd.*, table_pegawai.*, table_skpd.skpd_nama,
							IF (table_telaah.telaah_kategori = 3, table_anggotadprd.anggotadprd_name, table_pegawai.pegawai_nama) AS pegawai_nama,
							IF (table_telaah.telaah_kategori = 3, table_anggotadprd.anggotadprd_jabatan, table_pegawai.pegawai_namajabatan) AS pegawai_namajabatan');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','left');
		$this->db->join('table_timeline3','table_telaah.telaah_id=table_timeline3.telaah_id','left');
		$this->db->join('table_timeline6','table_telaah.telaah_id=table_timeline6.telaah_id','left');
		$this->db->join('table_timeline10','table_telaah.telaah_id=table_timeline10.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("(table_telaah.telaah_kategori='3' OR table_telaah.telaah_kategori='6' OR table_telaah.telaah_kategori='10')");
		$this->db->where("(table_timeline3.timeline_kasubid_id = '1' AND table_timeline3.timeline_sekwan_id = '0' 
							OR table_timeline6.timeline_kabag_id = '1' AND table_timeline6.timeline_sekwan_id = '0'
							OR table_timeline10.timeline_kabag_id = '1' AND table_timeline10.timeline_sekwan_id = '0')");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## PIMPINAN DPRD
	public function kadprd($order_by, $limit, $start, $key) {
		$this->db->select('table_telaah.*, table_anggotadprd.*, table_anggotadprd.anggotadprd_id as pegawai_id, table_anggotadprd.anggotadprd_name as pegawai_nama, table_anggotadprd.anggotadprd_jabatan as pegawai_namajabatan, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','left');
		$this->db->join('table_timeline3','table_telaah.telaah_id=table_timeline3.telaah_id','left');
		$this->db->join('table_skpd','table_anggotadprd.anggotadprd_id=table_skpd.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',3);
		$this->db->where('table_timeline3.timeline_sekwan_id',1);
		$this->db->where('table_timeline3.timeline_kadprd_id',0);
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## KABID SEKDA
	public function kabid_sekda($order_by, $limit, $start, $bagian_id,$key, $user_id) {
		if($user_id == 638) {
			
			if($limit){
				$this->db->select('table_telaah.*, table_pegawai.*, table_subbagian.*');
			}else {
				$this->db->select('COUNT(*) AS `numrows`');
			}
			$this->db->from('table_telaah');
			$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
			$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id');
			$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id');
			$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id');
			$this->db->join('table_timeline4','table_telaah.telaah_id=table_timeline4.telaah_id','left');
			$this->db->join('table_timeline9','table_telaah.telaah_id=table_timeline9.telaah_id','left');
			$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
			$this->db->where('table_bagian.bagian_id',$bagian_id);
			$this->db->group_start();
			$this->db->where('table_telaah.telaah_kategori',4);
			$this->db->or_where('table_telaah.telaah_kategori',9);
			$this->db->group_end();
			
			$this->db->group_start();
			$this->db->where('table_timeline4.timeline_kabag_id',0);
			$this->db->or_where('table_timeline9.timeline_kabag_id',0);
			$this->db->or_where('table_timeline4.timeline_kabag_id',5);
			$this->db->or_where('table_timeline9.timeline_kabag_id',5);
			$this->db->group_end();
			if($key){
				$this->db->group_start();
				$this->db->like('pegawai_nama',$key);
				$this->db->or_like('telaah_perihal',$key);
				$this->db->group_end();
			}
			$query1 = $this->db->get_compiled_select(); 
			
			if($limit){
				$this->db->select('table_telaah.*, table_pimpinan.*, table_subbagian.*');
			} else {
				$this->db->select('COUNT(*) AS `numrows`');
			}
			$this->db->from('table_telaah');
			$this->db->join('table_pimpinan','table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id','left');
			$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
			$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
			$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id','left');
			$this->db->join('table_timeline8','table_telaah.telaah_id=table_timeline8.telaah_id','left');
			$this->db->join('table_skpd','table_pimpinan.skpd_id=table_skpd.skpd_id','left');
			$this->db->where("table_telaah.telaah_kategori", 8);
			
			$this->db->group_start();
			$this->db->where('table_timeline8.timeline_kabag_id',0);
			$this->db->or_where('table_timeline8.timeline_kabag_id',5);
			$this->db->group_end();
			if($key){
				$this->db->group_start();
				$this->db->like('pegawai_nama',$key);
				$this->db->or_like('telaah_perihal',$key);
				$this->db->group_end();
			}
			$query2 = $this->db->get_compiled_select();

			if($limit){
				if($order_by){
					if($start){
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput ".$order_by." LIMIT $start, $limit");
					} else {
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput ".$order_by." LIMIT 0, $limit");
					}
				} else {	
					if($start){
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT $start, $limit");
					} else {
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT 0, $limit");
					}
				}
				return $query->result();
			}else{
				$query = $this->db->query($query1." UNION ALL ".$query2);
				return $query->result_array();
			}
			
			
			
		} else {
			
			$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
			$this->db->from('table_telaah');
			$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
			$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id');
			$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id');
			$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id');
			$this->db->join('table_timeline4','table_telaah.telaah_id=table_timeline4.telaah_id','left');
			$this->db->join('table_timeline9','table_telaah.telaah_id=table_timeline9.telaah_id','left');
			$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
			
			$this->db->group_start();
			$this->db->where('table_telaah.telaah_kategori',4);
			$this->db->or_where('table_telaah.telaah_kategori',9);
			$this->db->group_end();
			
			$this->db->group_start();
			$this->db->where('table_timeline4.timeline_kabag_id',0);
			$this->db->or_where('table_timeline9.timeline_kabag_id',0);
			$this->db->or_where('table_timeline4.timeline_kabag_id',5);
			$this->db->or_where('table_timeline9.timeline_kabag_id',5);
			$this->db->group_end();
			$this->db->where('table_bagian.bagian_id',$bagian_id);
			if($key){
				$this->db->group_start();
				$this->db->like('pegawai_nama',$key);
				$this->db->or_like('telaah_perihal',$key);
				$this->db->group_end();
			}
			if($order_by){
				$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
			} else {	
				$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
			}
			if($limit){
				$this->db->limit ($limit, $start);
				$query = $this->db->get ();
				return $query->result();
			} else {
				return $this->db->count_all_results(); 
			}
		}
	}
	
	## ASISTEN
	public function asisten($order_by, $limit, $start, $asisten_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id');
		$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id');
		$this->db->join('table_asisten','table_asisten.asisten_id=table_bagian.asisten_id');
		$this->db->join('table_timeline4','table_telaah.telaah_id=table_timeline4.telaah_id','left');
		$this->db->join('table_timeline9','table_telaah.telaah_id=table_timeline9.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('(table_telaah.telaah_kategori = 4 OR table_telaah.telaah_kategori = 9) ');
		$this->db->where('((table_timeline4.timeline_kabag_id = 1 AND table_timeline4.timeline_asisten_id = 0) 
						OR (table_timeline9.timeline_kabag_id = 1 AND table_timeline9.timeline_asisten_id = 0) )');
		$this->db->where('table_asisten.asisten_id', $asisten_id);
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## SEKCAM
	public function sekcam($order_by, $limit, $start, $skpd_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_kelurahan','table_relasi_kelurahan.id_kelurahan=table_pegawai.skpd_id','left');
		$this->db->join('table_timeline5','table_telaah.telaah_id=table_timeline5.telaah_id','left');
		$this->db->join('table_timeline7','table_telaah.telaah_id=table_timeline7.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("(table_telaah.telaah_kategori='5' OR table_telaah.telaah_kategori='7')");
		$this->db->where("((table_timeline5.timeline_sekcam_id = '0') 
			OR (table_timeline7.timeline_lurah_id = '1' AND table_timeline7.timeline_sekcam_id = '0'))");
		$this->db->where("(table_pegawai.skpd_id = '$skpd_id' OR table_relasi_kelurahan.id_kecamatan = '$skpd_id' )");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## CAMAT
	public function camat($order_by, $limit, $start, $skpd_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_kelurahan','table_relasi_kelurahan.id_kelurahan=table_pegawai.skpd_id','left');
		$this->db->join('table_timeline5','table_telaah.telaah_id=table_timeline5.telaah_id','left');
		$this->db->join('table_timeline7','table_telaah.telaah_id=table_timeline7.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("(table_telaah.telaah_kategori='5' OR table_telaah.telaah_kategori='7')");
		$this->db->where("((table_timeline5.timeline_sekcam_id = '1' AND table_timeline5.timeline_camat_id = '0') 
			OR (table_timeline7.timeline_sekcam_id = '1' AND table_timeline7.timeline_camat_id = '0') OR(table_timeline5.timeline_camat_id = '5'))");
		$this->db->where("(table_pegawai.skpd_id = '$skpd_id' OR table_relasi_kelurahan.id_kecamatan = '$skpd_id' )");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## KASUBAG CAMAT / LURAH
	public function kasubag_lurah($order_by, $limit, $start, $skpd_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline7','table_telaah.telaah_id=table_timeline7.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->where('table_telaah.telaah_kategori',7);
		$this->db->where('table_timeline7.timeline_lurah_id',0);
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## KABID DINKES
	public function kabid_dinkes($order_by, $limit, $start, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->where('table_telaah.telaah_kategori',1);
		$this->db->where("(table_jenis_skpd.jenis_skpd_id = 7 OR table_jenis_skpd.jenis_skpd_id = 10)");
		$this->db->where("(table_timeline1.timeline_kabid_id = '0')");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## SEKDIS DINKES
	public function sekdis_dinkes($order_by, $limit, $start, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id','left');
		$this->db->where("(table_telaah.telaah_kategori='1' OR table_telaah.telaah_kategori='2')");
		$this->db->where("(table_jenis_skpd.jenis_skpd_id = 7 OR table_jenis_skpd.jenis_skpd_id = 10)");
		$this->db->where("((table_timeline1.timeline_kabid_id = '1') 
							AND (table_timeline1.timeline_sekdis_id = '0' OR table_timeline1.timeline_sekdis_id = '5')
							OR (table_timeline2.timeline_sekdis_id = '0'))
							");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## KADIS DINKES
	public function kadis_dinkes($order_by, $limit, $start, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id','left');
		$this->db->where("(table_telaah.telaah_kategori='1' OR table_telaah.telaah_kategori='2')");
		$this->db->where("(table_jenis_skpd.jenis_skpd_id = 7 OR table_jenis_skpd.jenis_skpd_id = 10)");
		$this->db->where("((table_timeline1.timeline_sekdis_id = '1') 
				AND (table_timeline1.timeline_kadis_id = '0' OR table_timeline1.timeline_kadis_id = '5')
				OR (table_timeline2.timeline_sekdis_id = '1') AND (table_timeline2.timeline_kadis_id = '0' OR table_timeline2.timeline_kadis_id = '5'))");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	
	## KAPUS
	public function kapus($order_by, $limit, $start, $skpd_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline11','table_telaah.telaah_id=table_timeline11.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori', 11);
		$this->db->where('table_timeline11.timeline_kapus_id', 0);
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	#################################### TELAAH DISETUJUI ######################################
	##KABID OPD
	public function kabid_opd_disetujui($order_by, $limit, $start, $skpd_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',1);
		$this->db->where('table_timeline1.timeline_kabid_id',1);
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->where('telaah_sekretariat != 1 ');
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## SEKDIS
	public function sekdis_disetujui($order_by, $limit, $start, $skpd_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->where("(table_telaah.telaah_kategori='1' OR table_telaah.telaah_kategori='2')");
		$this->db->where("(table_timeline1.timeline_sekdis_id = '1' OR table_timeline2.timeline_sekdis_id = '1')");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## KADIS
	public function kadis_disetujui($order_by, $limit, $start, $skpd_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->where("(table_telaah.telaah_kategori='1' OR table_telaah.telaah_kategori='2')");
		$this->db->where("(table_timeline1.timeline_kadis_id = '1' OR table_timeline2.timeline_kadis_id = '1')");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## SEKDA
	public function sekda_disetujui($order_by, $limit, $start, $key) {
		
		if($limit){
			$this->db->select('table_telaah.*, table_pegawai.*, skpd_nama');
		}else {
			$this->db->select('COUNT(*) AS `numrows`');
		}
		
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id 
						AND (table_telaah.telaah_domainperjalanan = 1 OR table_telaah.telaah_domainperjalanan = 2)','left');
		$this->db->join('table_timeline4','table_telaah.telaah_id=table_timeline4.telaah_id','left');
		$this->db->join('table_timeline5','table_telaah.telaah_id=table_timeline5.telaah_id','left');
		$this->db->join('table_timeline9','table_telaah.telaah_id=table_timeline9.telaah_id','left');
		$this->db->join('table_timeline10','table_telaah.telaah_id=table_timeline10.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		
		$this->db->group_start();
		$this->db->where('table_telaah.telaah_kategori',2);
		$this->db->or_where('table_telaah.telaah_kategori',4);
		$this->db->or_where('table_telaah.telaah_kategori',5);
		$this->db->or_where('table_telaah.telaah_kategori',9);
		$this->db->or_where('table_telaah.telaah_kategori',10);
		$this->db->group_end();
		
		$this->db->group_start();
		$this->db->where('table_timeline2.timeline_sekda_id',1);
		$this->db->or_where('table_timeline4.timeline_sekda_id',1);
		$this->db->or_where('table_timeline5.timeline_sekda_id',1);
		$this->db->or_where('table_timeline9.timeline_sekda_id',1);
		$this->db->or_where('table_timeline10.timeline_sekda_id',1);
		$this->db->group_end();
		
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		
		$query1 = $this->db->get_compiled_select(); 
		
		if($limit){
			$this->db->select('table_telaah.*, table_pimpinan.*, skpd_nama');
		}else {
			$this->db->select('COUNT(*) AS `numrows`');
		}
		
		$this->db->from('table_telaah');
		$this->db->join('table_pimpinan','table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_timeline8','table_telaah.telaah_id=table_timeline8.telaah_id','left');
		$this->db->join('table_skpd','table_pimpinan.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("table_telaah.telaah_kategori", 8);
		$this->db->where('table_timeline8.timeline_sekda_id',1);
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		$query2 = $this->db->get_compiled_select();

		if($limit){
				if($order_by){
					if($start){
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput ".$order_by." LIMIT $start, $limit");
					} else {
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput ".$order_by." LIMIT 0, $limit");
					}
				} else {	
					if($start){
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT $start, $limit");
					} else {
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT 0, $limit");
					}
				}
				return $query->result();
			}else{
				$query = $this->db->query($query1." UNION ALL ".$query2);
				return $query->result_array();
			}
	}
	
	## WALIKOTA 
	public function walikota_disetujui($order_by, $limit, $start, $key) {
		
		if($limit){
			$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		}else {
			$this->db->select('COUNT(*) AS `numrows`');
		}
		
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id 
						AND (table_telaah.telaah_domainperjalanan = 1 OR table_telaah.telaah_domainperjalanan = 2)','left');
		$this->db->join('table_timeline4','table_telaah.telaah_id=table_timeline4.telaah_id','left');
		$this->db->join('table_timeline5','table_telaah.telaah_id=table_timeline5.telaah_id','left');
		$this->db->join('table_timeline10','table_telaah.telaah_id=table_timeline10.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("(table_telaah.telaah_kategori='2' OR table_telaah.telaah_kategori='4' 
		OR table_telaah.telaah_kategori='5' OR table_telaah.telaah_kategori='10')");
		$this->db->where("(table_timeline2.timeline_walikota_id = '1'
			OR table_timeline4.timeline_walikota_id = '1'
			OR table_timeline5.timeline_walikota_id = '1'
			OR table_timeline10.timeline_walikota_id = '1')");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		$query1 = $this->db->get_compiled_select(); 
		
		
		if($limit){
			$this->db->select('table_telaah.*, table_pimpinan.*, skpd_nama');
		}else {
			$this->db->select('COUNT(*) AS `numrows`');
		}
		$this->db->from('table_telaah');
		$this->db->join('table_pimpinan','table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_timeline8','table_telaah.telaah_id=table_timeline8.telaah_id','left');
		$this->db->join('table_skpd','table_pimpinan.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("table_telaah.telaah_kategori", 8);
		$this->db->where('table_timeline8.timeline_walikota_id',1);
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		$query2 = $this->db->get_compiled_select();

		if($limit){
				if($order_by){
					if($start){
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput ".$order_by." LIMIT $start, $limit");
					} else {
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput ".$order_by." LIMIT 0, $limit");
					}
				} else {	
					if($start){
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT $start, $limit");
					} else {
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT 0, $limit");
					}
				}
				return $query->result();
			}else{
				$query = $this->db->query($query1." UNION ALL ".$query2);
				return $query->result_array();
			}

	}
	
	## KABID DPRD
	public function kabid_dprd_disetujui($order_by, $limit, $start, $key) {
		$this->db->select('table_telaah.*, table_anggotadprd.*, table_pegawai.*, table_skpd.skpd_nama,
							IF (table_telaah.telaah_kategori = 3, table_anggotadprd.anggotadprd_name, table_pegawai.pegawai_nama) AS pegawai_nama,
							IF (table_telaah.telaah_kategori = 3, table_anggotadprd.anggotadprd_jabatan, table_pegawai.pegawai_namajabatan) AS pegawai_namajabatan');
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','left');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline3','table_telaah.telaah_id=table_timeline3.telaah_id','left');
		$this->db->join('table_timeline6','table_telaah.telaah_id=table_timeline6.telaah_id','left');
		$this->db->join('table_timeline10','table_telaah.telaah_id=table_timeline10.telaah_id','left');
		$this->db->join('table_skpd','table_anggotadprd.anggotadprd_id=table_skpd.skpd_id','left');
		$this->db->where("(table_telaah.telaah_kategori='3' OR table_telaah.telaah_kategori='6' OR table_telaah.telaah_kategori='10')");
		$this->db->where("(table_timeline3.timeline_kasubid_id = '1' OR table_timeline6.timeline_kabag_id = '1' OR table_timeline10.timeline_kabag_id = '1')");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	 }
	
	## SEKWAN
	public function sekwan_disetujui($order_by, $limit, $start, $key) {
		$this->db->select('table_telaah.*, table_anggotadprd.*, table_pegawai.*, table_skpd.skpd_nama,
							IF (table_telaah.telaah_kategori = 3, table_anggotadprd.anggotadprd_name, table_pegawai.pegawai_nama) AS pegawai_nama,
							IF (table_telaah.telaah_kategori = 3, table_anggotadprd.anggotadprd_jabatan, table_pegawai.pegawai_namajabatan) AS pegawai_namajabatan');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','left');
		$this->db->join('table_timeline3','table_telaah.telaah_id=table_timeline3.telaah_id','left');
		$this->db->join('table_timeline6','table_telaah.telaah_id=table_timeline6.telaah_id','left');
		$this->db->join('table_timeline10','table_telaah.telaah_id=table_timeline10.telaah_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=2','left');
		$this->db->where("(table_telaah.telaah_kategori='3' OR table_telaah.telaah_kategori='6' OR table_telaah.telaah_kategori='10')");
		$this->db->where("(table_timeline3.timeline_sekwan_id = '1' OR table_timeline6.timeline_sekwan_id = '1' 
							OR table_timeline10.timeline_sekwan_id = '1')");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## PIMPINAN DPRD
	public function kadprd_disetujui($order_by, $limit, $start, $key) {
		$this->db->select('table_telaah.*, table_anggotadprd.*, table_anggotadprd.anggotadprd_id as pegawai_id, table_anggotadprd.anggotadprd_name as pegawai_nama, table_anggotadprd.anggotadprd_jabatan as pegawai_namajabatan, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','left');
		$this->db->join('table_timeline3','table_telaah.telaah_id=table_timeline3.telaah_id','left');
		$this->db->join('table_skpd','table_anggotadprd.anggotadprd_id=table_skpd.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',3);
		$this->db->where('table_timeline3.timeline_kadprd_id',1);
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## KABID SEKDA
	public function kabid_sekda_disetujui($order_by, $limit, $start, $bagian_id, $key, $user_id) {
		
		if($user_id== 638) {
			
			if($limit){
				$this->db->select('table_telaah.*, table_pegawai.*, table_subbagian.*');
			}else {
				$this->db->select('COUNT(*) AS `numrows`');
			}
			$this->db->from('table_telaah');
			$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
			$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id');
			$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id');
			$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id');
			$this->db->join('table_timeline4','table_telaah.telaah_id=table_timeline4.telaah_id','left');
			$this->db->join('table_timeline9','table_telaah.telaah_id=table_timeline9.telaah_id','left');
			$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
			$this->db->where('table_telaah.telaah_kategori IN (4,9)');
			
			$this->db->group_start();
			$this->db->where('table_timeline4.timeline_kabag_id',1);
				$this->db->or_group_start();
					$this->db->where('table_timeline9.timeline_kabag_id',1);
					$this->db->where('table_bagian.bagian_id',$bagian_id);
				$this->db->group_end();
			$this->db->group_end();
			
			if($key){
				$this->db->group_start();
				$this->db->like('pegawai_nama',$key);
				$this->db->or_like('telaah_perihal',$key);
				$this->db->group_end();
			}
			
			$query1 = $this->db->get_compiled_select(); 
			
			if($limit){
				$this->db->select('table_telaah.*, table_pimpinan.*, table_subbagian.*');
			} else {
				$this->db->select('COUNT(*) AS `numrows`');
			}
			$this->db->from('table_telaah');
			$this->db->join('table_pimpinan','table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id','left');
			$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
			$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
			$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id','left');
			$this->db->join('table_timeline8','table_telaah.telaah_id=table_timeline8.telaah_id','left');
			$this->db->join('table_skpd','table_pimpinan.skpd_id=table_skpd.skpd_id','left');
			$this->db->where("table_telaah.telaah_kategori", 8);
			
			$this->db->group_start();
			$this->db->where('table_timeline8.timeline_kabag_id',1);
			$this->db->group_end();
			if($key){
				$this->db->group_start();
				$this->db->like('pegawai_nama',$key);
				$this->db->or_like('telaah_perihal',$key);
				$this->db->group_end();
			}
			$query2 = $this->db->get_compiled_select();

			if($limit){
				if($order_by){
					if($start){
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput ".$order_by." LIMIT $start, $limit");
					} else {
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput ".$order_by." LIMIT 0, $limit");
					}
				} else {	
					if($start){
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT $start, $limit");
					} else {
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT 0, $limit");
					}
				}
				return $query->result();
			}else{
				$query = $this->db->query($query1." UNION ALL ".$query2);
				return $query->result_array();
			}
			
		} else {
			
			$this->db->from('table_telaah');
			$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
			$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id');
			$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id');
			$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id');
			$this->db->join('table_timeline4','table_telaah.telaah_id=table_timeline4.telaah_id','left');
			$this->db->join('table_timeline9','table_telaah.telaah_id=table_timeline9.telaah_id','left');
			$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
			$this->db->where('table_telaah.telaah_kategori IN (4,9)');
			
			$this->db->group_start();
			$this->db->where('table_timeline4.timeline_kabag_id',1);
				$this->db->or_group_start();
					$this->db->where('table_timeline9.timeline_kabag_id',1);
					$this->db->where('table_bagian.bagian_id',$bagian_id);
				$this->db->group_end();
			$this->db->group_end();
			
			if($key){
				$this->db->group_start();
				$this->db->like('pegawai_nama',$key);
				$this->db->or_like('telaah_perihal',$key);
				$this->db->group_end();
			}
			if($order_by){
				$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
			} else {	
				$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
			}
			if($limit){
				$this->db->limit ($limit, $start);
				$query = $this->db->get ();
				return $query->result();
			} else {
				return $this->db->count_all_results(); 
			}
		}
	}
	
	## ASISTEN
	public function asisten_disetujui($order_by, $limit, $start, $asisten_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id');
		$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id');
		$this->db->join('table_asisten','table_asisten.asisten_id=table_bagian.asisten_id');
		$this->db->join('table_timeline4','table_telaah.telaah_id=table_timeline4.telaah_id','left');
		$this->db->join('table_timeline9','table_telaah.telaah_id=table_timeline9.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('(table_telaah.telaah_kategori = 4 OR table_telaah.telaah_kategori = 9) ');
		$this->db->where('(table_timeline4.timeline_asisten_id = 1 OR table_timeline9.timeline_asisten_id = 1 )');
		$this->db->where('table_asisten.asisten_id', $asisten_id);
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## SEKCAM
	public function sekcam_disetujui($order_by, $limit, $start, $skpd_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_kelurahan','table_relasi_kelurahan.id_kelurahan=table_pegawai.skpd_id','left');
		$this->db->join('table_timeline5','table_telaah.telaah_id=table_timeline5.telaah_id','left');
		$this->db->join('table_timeline7','table_telaah.telaah_id=table_timeline7.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("(table_telaah.telaah_kategori='5' OR table_telaah.telaah_kategori='7')");
		$this->db->where("(table_timeline5.timeline_sekcam_id = '1' OR table_timeline7.timeline_sekcam_id = '1')");
		$this->db->where("(table_pegawai.skpd_id = '$skpd_id' OR table_relasi_kelurahan.id_kecamatan = '$skpd_id' )");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## CAMAT
	public function camat_disetujui($order_by, $limit, $start, $skpd_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_kelurahan','table_relasi_kelurahan.id_kelurahan=table_pegawai.skpd_id','left');
		$this->db->join('table_timeline5','table_telaah.telaah_id=table_timeline5.telaah_id','left');
		$this->db->join('table_timeline7','table_telaah.telaah_id=table_timeline7.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("(table_telaah.telaah_kategori='5' OR table_telaah.telaah_kategori='7')");
		$this->db->where("(table_timeline5.timeline_camat_id = '1' OR table_timeline7.timeline_camat_id = '1')");
		$this->db->where("(table_pegawai.skpd_id = '$skpd_id' OR table_relasi_kelurahan.id_kecamatan = '$skpd_id' )");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## KASUBAG CAMAT / LURAH
	public function kasubag_lurah_disetujui($order_by, $limit, $start, $skpd_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline7','table_telaah.telaah_id=table_timeline7.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->where('table_telaah.telaah_kategori',7);
		$this->db->where('table_timeline7.timeline_lurah_id',1);
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## KABID DINKES
	public function kabid_dinkes_disetujui($order_by, $limit, $start, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->where('table_telaah.telaah_kategori',1);
		$this->db->where("(table_jenis_skpd.jenis_skpd_id = 7 OR table_jenis_skpd.jenis_skpd_id = 10)");
		$this->db->where('table_timeline1.timeline_kabid_id',1);
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## SEKDIS DINKES
	public function sekdis_dinkes_disetujui($order_by, $limit, $start, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id','left');
		$this->db->where("(table_telaah.telaah_kategori='1' OR table_telaah.telaah_kategori='2')");
		$this->db->where("(table_jenis_skpd.jenis_skpd_id = 7 OR table_jenis_skpd.jenis_skpd_id = 10)");
		$this->db->where("(table_timeline1.timeline_sekdis_id = '1' OR table_timeline2.timeline_sekdis_id = '1')");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## KADIS DINKES
	public function kadis_dinkes_disetujui($order_by, $limit, $start, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id','left');
		$this->db->where("(table_telaah.telaah_kategori='1' OR table_telaah.telaah_kategori='2')");
		$this->db->where("(table_jenis_skpd.jenis_skpd_id = 7 OR table_jenis_skpd.jenis_skpd_id = 10)");
		$this->db->where("(table_timeline1.timeline_kadis_id = '1' OR table_timeline2.timeline_kadis_id = '1')");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## KAPUS
	public function kapus_disetujui($order_by, $limit, $start, $skpd_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline11','table_telaah.telaah_id=table_timeline11.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori', 11);
		$this->db->where('table_timeline11.timeline_kapus_id', 1);
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result(); 
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	##################################### TELAAH DI TOLAK ###############################
	
	##KABID OPD
	public function kabid_opd_ditolak($order_by, $limit, $start, $skpd_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',1);
		$this->db->where('table_timeline1.timeline_kabid_id',2);
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->where('telaah_sekretariat != 1 ');
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## SEKDIS
	public function sekdis_ditolak($order_by, $limit, $start, $skpd_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->where("(table_telaah.telaah_kategori='1' OR table_telaah.telaah_kategori='2')");
		$this->db->where("(table_timeline1.timeline_sekdis_id = '2' OR table_timeline2.timeline_sekdis_id = '2')");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## KADIS
	public function kadis_ditolak($order_by, $limit, $start, $skpd_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->where("(table_telaah.telaah_kategori='1' OR table_telaah.telaah_kategori='2')");
		$this->db->where("(table_timeline1.timeline_kadis_id = '2' OR table_timeline2.timeline_kadis_id = '2')");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## SEKDA
	public function sekda_ditolak($order_by, $limit, $start, $key) {
		
		if($limit){
			$this->db->select('table_telaah.*, table_pegawai.*, skpd_nama');
		}else {
			$this->db->select('COUNT(*) AS `numrows`');
		}
		
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id 
						AND (table_telaah.telaah_domainperjalanan = 1 OR table_telaah.telaah_domainperjalanan = 2)','left');
		$this->db->join('table_timeline4','table_telaah.telaah_id=table_timeline4.telaah_id','left');
		$this->db->join('table_timeline5','table_telaah.telaah_id=table_timeline5.telaah_id','left');
		$this->db->join('table_timeline9','table_telaah.telaah_id=table_timeline9.telaah_id','left');
		$this->db->join('table_timeline10','table_telaah.telaah_id=table_timeline10.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		
		$this->db->group_start();
		$this->db->where('table_telaah.telaah_kategori',2);
		$this->db->or_where('table_telaah.telaah_kategori',4);
		$this->db->or_where('table_telaah.telaah_kategori',5);
		$this->db->or_where('table_telaah.telaah_kategori',9);
		$this->db->or_where('table_telaah.telaah_kategori',10);
		$this->db->group_end();
		
		$this->db->group_start();
		$this->db->where('table_timeline2.timeline_sekda_id',2);
		$this->db->or_where('table_timeline4.timeline_sekda_id',2);
		$this->db->or_where('table_timeline5.timeline_sekda_id',2);
		$this->db->or_where('table_timeline9.timeline_sekda_id',2);
		$this->db->or_where('table_timeline10.timeline_sekda_id',2);
		$this->db->group_end();
		
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		$query1 = $this->db->get_compiled_select(); 
		
		if($limit){
			$this->db->select('table_telaah.*, table_pimpinan.*, skpd_nama');
		}else {
			$this->db->select('COUNT(*) AS `numrows`');
		}
		
		$this->db->from('table_telaah');
		$this->db->join('table_pimpinan','table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_timeline8','table_telaah.telaah_id=table_timeline8.telaah_id','left');
		$this->db->join('table_skpd','table_pimpinan.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("table_telaah.telaah_kategori", 8);
		$this->db->where('table_timeline8.timeline_sekda_id',2);
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		$query2 = $this->db->get_compiled_select();

		if($limit){
				if($order_by){
					if($start){
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput ".$order_by." LIMIT $start, $limit");
					} else {
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput ".$order_by." LIMIT 0, $limit");
					}
				} else {	
					if($start){
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT $start, $limit");
					} else {
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT 0, $limit");
					}
				}
				return $query->result();
			}else{
				$query = $this->db->query($query1." UNION ALL ".$query2);
				return $query->result_array();
			}

	}
	
	## WALIKOTA 
	public function walikota_ditolak($order_by, $limit, $start, $key) {
		
		if($limit){
			$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		}else {
			$this->db->select('COUNT(*) AS `numrows`');
		}
		
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id 
						AND (table_telaah.telaah_domainperjalanan = 1 OR table_telaah.telaah_domainperjalanan = 2)','left');
		$this->db->join('table_timeline4','table_telaah.telaah_id=table_timeline4.telaah_id','left');
		$this->db->join('table_timeline5','table_telaah.telaah_id=table_timeline5.telaah_id','left');
		$this->db->join('table_timeline10','table_telaah.telaah_id=table_timeline10.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("(table_telaah.telaah_kategori='2' OR table_telaah.telaah_kategori='4' 
		OR table_telaah.telaah_kategori='5' OR table_telaah.telaah_kategori='10')");
		$this->db->where("(table_timeline2.timeline_walikota_id = '2'
			OR table_timeline4.timeline_walikota_id = '2'
			OR table_timeline5.timeline_walikota_id = '2'
			OR table_timeline10.timeline_walikota_id = '2')");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		$query1 = $this->db->get_compiled_select(); 
	
		if($limit){
			$this->db->select('table_telaah.*, table_pimpinan.*, skpd_nama');
		}else {
			$this->db->select('COUNT(*) AS `numrows`');
		}
		
		$this->db->from('table_telaah');
		$this->db->join('table_pimpinan','table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
		$this->db->join('table_timeline8','table_telaah.telaah_id=table_timeline8.telaah_id','left');
		$this->db->join('table_skpd','table_pimpinan.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("table_telaah.telaah_kategori", 8);
		$this->db->where('table_timeline8.timeline_walikota_id',2);
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		$query2 = $this->db->get_compiled_select();

		if($limit){
				if($order_by){
					if($start){
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput ".$order_by." LIMIT $start, $limit");
					} else {
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput ".$order_by." LIMIT 0, $limit");
					}
				} else {	
					if($start){
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT $start, $limit");
					} else {
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT 0, $limit");
					}
				}
				return $query->result();
			}else{
				$query = $this->db->query($query1." UNION ALL ".$query2);
				return $query->result_array();
			}
	}
	
	## KABID DPRD
	public function kabid_dprd_ditolak($order_by, $limit, $start, $key) {
		$this->db->select('table_telaah.*, table_anggotadprd.*, table_pegawai.*, table_skpd.skpd_nama,
							IF (table_telaah.telaah_kategori = 3, table_anggotadprd.anggotadprd_name, table_pegawai.pegawai_nama) AS pegawai_nama,
							IF (table_telaah.telaah_kategori = 3, table_anggotadprd.anggotadprd_jabatan, table_pegawai.pegawai_namajabatan) AS pegawai_namajabatan');
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','left');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline3','table_telaah.telaah_id=table_timeline3.telaah_id','left');
		$this->db->join('table_timeline6','table_telaah.telaah_id=table_timeline6.telaah_id','left');
		$this->db->join('table_timeline10','table_telaah.telaah_id=table_timeline10.telaah_id','left');
		$this->db->join('table_skpd','table_anggotadprd.anggotadprd_id=table_skpd.skpd_id','left');
		$this->db->where("(table_telaah.telaah_kategori='3' OR table_telaah.telaah_kategori='6' OR table_telaah.telaah_kategori='10')");
		$this->db->where("(table_timeline3.timeline_kasubid_id = '2' OR table_timeline6.timeline_kabag_id = '2' OR table_timeline10.timeline_kabag_id = '2')");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	 }
	 
	## SEKWAN
	public function sekwan_ditolak($order_by, $limit, $start, $key) {
		$this->db->select('*, table_telaah.telaah_id as telaah_id, table_skpd.skpd_nama, 
							IF (table_telaah.telaah_kategori = 3, table_anggotadprd.anggotadprd_name, table_pegawai.pegawai_nama) AS pegawai_nama,
							IF (table_telaah.telaah_kategori = 3, table_anggotadprd.anggotadprd_jabatan, table_pegawai.pegawai_namajabatan) AS pegawai_namajabatan');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','left');
		$this->db->join('table_timeline3','table_telaah.telaah_id=table_timeline3.telaah_id','left');
		$this->db->join('table_timeline6','table_telaah.telaah_id=table_timeline6.telaah_id','left');
		$this->db->join('table_timeline10','table_telaah.telaah_id=table_timeline10.telaah_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=2','left');
		$this->db->where("(table_telaah.telaah_kategori='3' OR table_telaah.telaah_kategori='6' OR table_telaah.telaah_kategori='10')");
		$this->db->where("(table_timeline3.timeline_sekwan_id = '2' OR table_timeline6.timeline_sekwan_id = '2' 
							OR table_timeline10.timeline_sekwan_id = '2')");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## PIMPINAN DPRD
	public function kadprd_ditolak($order_by, $limit, $start, $key) {
		$this->db->select('table_telaah.*, table_anggotadprd.*, table_anggotadprd.anggotadprd_id as pegawai_id, table_anggotadprd.anggotadprd_name as pegawai_nama, table_anggotadprd.anggotadprd_jabatan as pegawai_namajabatan, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','left');
		$this->db->join('table_timeline3','table_telaah.telaah_id=table_timeline3.telaah_id','left');
		$this->db->join('table_skpd','table_anggotadprd.anggotadprd_id=table_skpd.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',3);
		$this->db->where('table_timeline3.timeline_kadprd_id',2);
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## KABID SEKDA
	public function kabid_sekda_ditolak($order_by, $limit, $start, $bagian_id, $key, $user_id) {
		if($user_id== 638) {
			
			if($limit){
				$this->db->select('table_telaah.*, table_pegawai.*, table_subbagian.*');
			}else {
				$this->db->select('COUNT(*) AS `numrows`');
			}
			$this->db->from('table_telaah');
			$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
			$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id');
			$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id');
			$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id');
			$this->db->join('table_timeline4','table_telaah.telaah_id=table_timeline4.telaah_id','left');
			$this->db->join('table_timeline9','table_telaah.telaah_id=table_timeline9.telaah_id','left');
			$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
			$this->db->where('table_telaah.telaah_kategori IN (4,9)');
			
			$this->db->group_start();
			$this->db->where('table_timeline4.timeline_kabag_id',2);
				$this->db->or_group_start();
					$this->db->where('table_timeline9.timeline_kabag_id',2);
					$this->db->where('table_bagian.bagian_id',$bagian_id);
				$this->db->group_end();
			$this->db->group_end();
			
			if($key){
				$this->db->group_start();
				$this->db->like('pegawai_nama',$key);
				$this->db->or_like('telaah_perihal',$key);
				$this->db->group_end();
			}
			
			$query1 = $this->db->get_compiled_select(); 
			
			if($limit){
				$this->db->select('table_telaah.*, table_pimpinan.*, table_subbagian.*');
			} else {
				$this->db->select('COUNT(*) AS `numrows`');
			}
			$this->db->from('table_telaah');
			$this->db->join('table_pimpinan','table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id','left');
			$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id','left');
			$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id','left');
			$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id','left');
			$this->db->join('table_timeline8','table_telaah.telaah_id=table_timeline8.telaah_id','left');
			$this->db->join('table_skpd','table_pimpinan.skpd_id=table_skpd.skpd_id','left');
			$this->db->where("table_telaah.telaah_kategori", 8);
			
			$this->db->group_start();
			$this->db->where('table_timeline8.timeline_kabag_id',2);
			$this->db->group_end();
			if($key){
				$this->db->group_start();
				$this->db->like('pegawai_nama',$key);
				$this->db->or_like('telaah_perihal',$key);
				$this->db->group_end();
			}
			$query2 = $this->db->get_compiled_select();

			if($limit){
				if($order_by){
					if($start){
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput ".$order_by." LIMIT $start, $limit");
					} else {
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput ".$order_by." LIMIT 0, $limit");
					}
				} else {	
					if($start){
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT $start, $limit");
					} else {
						$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT 0, $limit");
					}
				}
				return $query->result();
			}else{
				$query = $this->db->query($query1." UNION ALL ".$query2);
				return $query->result_array();
			}
			
		} else {
			
			$this->db->from('table_telaah');
			$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
			$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id');
			$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id');
			$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id');
			$this->db->join('table_timeline4','table_telaah.telaah_id=table_timeline4.telaah_id','left');
			$this->db->join('table_timeline9','table_telaah.telaah_id=table_timeline9.telaah_id','left');
			$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
			$this->db->where('table_telaah.telaah_kategori IN (4,9)');
			
			$this->db->group_start();
			$this->db->where('table_timeline4.timeline_kabag_id',2);
				$this->db->or_group_start();
					$this->db->where('table_timeline9.timeline_kabag_id',2);
					$this->db->where('table_bagian.bagian_id',$user_id);
				$this->db->group_end();
			$this->db->group_end();
			
			if($key){
				$this->db->group_start();
				$this->db->like('pegawai_nama',$key);
				$this->db->or_like('telaah_perihal',$key);
				$this->db->group_end();
			}
			if($order_by){
				$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
			} else {	
				$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
			}
			if($limit){
				$this->db->limit ($limit, $start);
				$query = $this->db->get ();
				return $query->result();
			} else {
				return $this->db->count_all_results(); 
			}
		}
	}
	
	## ASISTEN
	public function asisten_ditolak($order_by, $limit, $start, $asisten_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id');
		$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id');
		$this->db->join('table_asisten','table_asisten.asisten_id=table_bagian.asisten_id');
		$this->db->join('table_timeline4','table_telaah.telaah_id=table_timeline4.telaah_id','left');
		$this->db->join('table_timeline9','table_telaah.telaah_id=table_timeline9.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('(table_telaah.telaah_kategori = 4 OR table_telaah.telaah_kategori = 9) ');
		$this->db->where('(table_timeline4.timeline_asisten_id = 2 OR table_timeline9.timeline_asisten_id = 2 )');
		$this->db->where('table_asisten.asisten_id', $asisten_id);
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## SEKCAM
	public function sekcam_ditolak($order_by, $limit, $start, $skpd_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_kelurahan','table_relasi_kelurahan.id_kelurahan=table_pegawai.skpd_id','left');
		$this->db->join('table_timeline5','table_telaah.telaah_id=table_timeline5.telaah_id','left');
		$this->db->join('table_timeline7','table_telaah.telaah_id=table_timeline7.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("(table_telaah.telaah_kategori='5' OR table_telaah.telaah_kategori='7')");
		$this->db->where("(table_timeline5.timeline_sekcam_id = '2' OR table_timeline7.timeline_sekcam_id = '2')");
		$this->db->where("(table_pegawai.skpd_id = '$skpd_id' OR table_relasi_kelurahan.id_kecamatan = '$skpd_id' )");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## CAMAT
	public function camat_ditolak($order_by, $limit, $start, $skpd_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_kelurahan','table_relasi_kelurahan.id_kelurahan=table_pegawai.skpd_id','left');
		$this->db->join('table_timeline5','table_telaah.telaah_id=table_timeline5.telaah_id','left');
		$this->db->join('table_timeline7','table_telaah.telaah_id=table_timeline7.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("(table_telaah.telaah_kategori='5' OR table_telaah.telaah_kategori='7')");
		$this->db->where("(table_timeline5.timeline_camat_id = '2' OR table_timeline7.timeline_camat_id = '2')");
		$this->db->where("(table_pegawai.skpd_id = '$skpd_id' OR table_relasi_kelurahan.id_kecamatan = '$skpd_id' )");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	
	## KASUBAG CAMAT / LURAH
	public function kasubag_lurah_ditolak($order_by, $limit, $start, $skpd_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline7','table_telaah.telaah_id=table_timeline7.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->where('table_telaah.telaah_kategori',7);
		$this->db->where('table_timeline7.timeline_lurah_id',2);
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## KABID DINKES
	public function kabid_dinkes_ditolak($order_by, $limit, $start, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->where('table_telaah.telaah_kategori',1);
		$this->db->where("(table_jenis_skpd.jenis_skpd_id = 7 OR table_jenis_skpd.jenis_skpd_id = 10)");
		$this->db->where('table_timeline1.timeline_kabid_id',2);
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## SEKDIS DINKES
	public function sekdis_dinkes_ditolak($order_by, $limit, $start, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id','left');
		$this->db->where("(table_telaah.telaah_kategori='1' OR table_telaah.telaah_kategori='2')");
		$this->db->where("(table_jenis_skpd.jenis_skpd_id = 7 OR table_jenis_skpd.jenis_skpd_id = 10)");
		$this->db->where("(table_timeline1.timeline_sekdis_id = '2' OR table_timeline2.timeline_sekdis_id = '2')");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	
	## KADIS DINKES
	public function kadis_dinkes_ditolak($order_by, $limit, $start, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id','left');
		$this->db->where("(table_telaah.telaah_kategori='1' OR table_telaah.telaah_kategori='2')");
		$this->db->where("(table_jenis_skpd.jenis_skpd_id = 7 OR table_jenis_skpd.jenis_skpd_id = 10)");
		$this->db->where("(table_timeline1.timeline_kadis_id = '2' OR table_timeline2.timeline_kadis_id = '2')");
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	
	## KAPUS
	public function kapus_ditolak($order_by, $limit, $start, $skpd_id, $key) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.skpd_nama');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline11','table_telaah.telaah_id=table_timeline11.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori', 11);
		$this->db->where('table_timeline11.timeline_kapus_id', 2);
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		if($key){
			$this->db->group_start();
			$this->db->like('pegawai_nama',$key);
			$this->db->or_like('telaah_perihal',$key);
			$this->db->group_end();
		}
		if($order_by){
			$this->db->order_by('table_telaah.telaah_waktuinput',$order_by);
		} else {	
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		}
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	####################################### HISTORY ######################################
	
	##KABID OPD
	public function kabid_opd_history($skpd_id) {
		$this->db->select('table_telaah.telaah_id, table_telaah.telaah_kantortujuan, table_telaah.telaah_tanggalberangkat, , table_telaah.telaah_tanggalkembali, table_pegawai.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',1);
		$this->db->where('(table_timeline1.timeline_kabid_id = 1 OR table_timeline1.timeline_kabid_id = 2)');
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->where('telaah_sekretariat != 1 ');
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit (5, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## SEKDIS
	public function sekdis_history($skpd_id) {
		$this->db->select('table_telaah.telaah_id, table_telaah.telaah_kantortujuan, table_telaah.telaah_tanggalberangkat, , table_telaah.telaah_tanggalkembali, table_pegawai.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->where("(table_telaah.telaah_kategori='1' OR table_telaah.telaah_kategori='2')");
		$this->db->where("(table_timeline1.timeline_sekdis_id = '1' OR table_timeline2.timeline_sekdis_id = '1' OR table_timeline1.timeline_sekdis_id = '2' OR table_timeline2.timeline_sekdis_id = '2')");
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit (5, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## KADIS
	public function kadis_history($skpd_id) {
		$this->db->select('table_telaah.telaah_id, table_telaah.telaah_kantortujuan, table_telaah.telaah_tanggalberangkat, , table_telaah.telaah_tanggalkembali, table_pegawai.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->where("(table_telaah.telaah_kategori='1' OR table_telaah.telaah_kategori='2')");
		$this->db->where("(table_timeline1.timeline_kadis_id = '1' OR table_timeline2.timeline_kadis_id = '1' OR table_timeline1.timeline_kadis_id = '2' OR table_timeline2.timeline_kadis_id = '2')");
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit (5, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## SEKDA
	public function sekda_history() {
		$this->db->select('table_telaah.telaah_id, table_telaah.telaah_kantortujuan, table_telaah.telaah_tanggalberangkat, , table_telaah.telaah_tanggalkembali, table_pegawai.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id 
						AND (table_telaah.telaah_domainperjalanan = 1 OR table_telaah.telaah_domainperjalanan = 2)','left');
		$this->db->join('table_timeline4','table_telaah.telaah_id=table_timeline4.telaah_id','left');
		$this->db->join('table_timeline5','table_telaah.telaah_id=table_timeline5.telaah_id','left');
		$this->db->join('table_timeline9','table_telaah.telaah_id=table_timeline9.telaah_id','left');
		$this->db->join('table_timeline10','table_telaah.telaah_id=table_timeline10.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		
		$this->db->group_start();
		$this->db->where('table_telaah.telaah_kategori',2);
		$this->db->or_where('table_telaah.telaah_kategori',4);
		$this->db->or_where('table_telaah.telaah_kategori',5);
		$this->db->or_where('table_telaah.telaah_kategori',9);
		$this->db->or_where('table_telaah.telaah_kategori',10);
		$this->db->group_end();
		
		$this->db->group_start();
		$this->db->where('table_timeline2.timeline_sekda_id',1);
		$this->db->or_where('table_timeline4.timeline_sekda_id',1);
		$this->db->or_where('table_timeline5.timeline_sekda_id',1);
		$this->db->or_where('table_timeline9.timeline_sekda_id',1);
		$this->db->or_where('table_timeline10.timeline_sekda_id',1);
		$this->db->or_where('table_timeline2.timeline_sekda_id',2);
		$this->db->or_where('table_timeline4.timeline_sekda_id',2);
		$this->db->or_where('table_timeline5.timeline_sekda_id',2);
		$this->db->or_where('table_timeline9.timeline_sekda_id',2);
		$this->db->or_where('table_timeline10.timeline_sekda_id',2);
		$this->db->group_end();
		
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit (5, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## WALIKOTA 
	public function walikota_history() {
		$this->db->select('table_telaah.telaah_id, table_telaah.telaah_kantortujuan, table_telaah.telaah_tanggalberangkat, , table_telaah.telaah_tanggalkembali, table_pegawai.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id 
						AND (table_telaah.telaah_domainperjalanan = 1 OR table_telaah.telaah_domainperjalanan = 2)','left');
		$this->db->join('table_timeline4','table_telaah.telaah_id=table_timeline4.telaah_id','left');
		$this->db->join('table_timeline5','table_telaah.telaah_id=table_timeline5.telaah_id','left');
		$this->db->join('table_timeline10','table_telaah.telaah_id=table_timeline10.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("(table_telaah.telaah_kategori='2' OR table_telaah.telaah_kategori='4' 
		OR table_telaah.telaah_kategori='5' OR table_telaah.telaah_kategori='10')");
		$this->db->where("(table_timeline2.timeline_walikota_id = '1'
			OR table_timeline4.timeline_walikota_id = '1'
			OR table_timeline5.timeline_walikota_id = '1'
			OR table_timeline10.timeline_walikota_id = '1'
			OR table_timeline2.timeline_walikota_id = '2'
			OR table_timeline4.timeline_walikota_id = '2'
			OR table_timeline5.timeline_walikota_id = '2'
			OR table_timeline10.timeline_walikota_id = '2')");
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit (5, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## KABID DPRD
	public function kabid_dprd_history() {
		$this->db->select('table_telaah.telaah_id, table_telaah.telaah_kantortujuan, table_telaah.telaah_tanggalberangkat, , table_telaah.telaah_tanggalkembali, table_anggotadprd.*, table_pegawai.*, 
							IF (table_telaah.telaah_kategori = 3, table_anggotadprd.anggotadprd_name, table_pegawai.pegawai_nama) AS pegawai_nama,
							IF (table_telaah.telaah_kategori = 3, table_anggotadprd.anggotadprd_jabatan, table_pegawai.pegawai_namajabatan) AS pegawai_namajabatan');
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','left');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline3','table_telaah.telaah_id=table_timeline3.telaah_id','left');
		$this->db->join('table_timeline6','table_telaah.telaah_id=table_timeline6.telaah_id','left');
		$this->db->join('table_timeline10','table_telaah.telaah_id=table_timeline10.telaah_id','left');
		$this->db->join('table_skpd','table_anggotadprd.anggotadprd_id=table_skpd.skpd_id','left');
		$this->db->where("(table_telaah.telaah_kategori='3' OR table_telaah.telaah_kategori='6' OR table_telaah.telaah_kategori='10')");
		$this->db->where("(table_timeline3.timeline_kasubid_id = '1' OR table_timeline6.timeline_kabag_id = '1' OR table_timeline10.timeline_kabag_id = '1' OR table_timeline3.timeline_kasubid_id = '2' OR table_timeline6.timeline_kabag_id = '2' OR table_timeline10.timeline_kabag_id = '2')");
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit (5, 0);
		$query = $this->db->get ();
		return $query->result();
	 }
	
	## SEKWAN
	public function sekwan_history() {
		$this->db->select('table_telaah.telaah_id, table_telaah.telaah_kantortujuan, table_telaah.telaah_tanggalberangkat, , table_telaah.telaah_tanggalkembali, table_anggotadprd.*, table_pegawai.*, 
							IF (table_telaah.telaah_kategori = 3, table_anggotadprd.anggotadprd_name, table_pegawai.pegawai_nama) AS pegawai_nama,
							IF (table_telaah.telaah_kategori = 3, table_anggotadprd.anggotadprd_jabatan, table_pegawai.pegawai_namajabatan) AS pegawai_namajabatan');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','left');
		$this->db->join('table_timeline3','table_telaah.telaah_id=table_timeline3.telaah_id','left');
		$this->db->join('table_timeline6','table_telaah.telaah_id=table_timeline6.telaah_id','left');
		$this->db->join('table_timeline10','table_telaah.telaah_id=table_timeline10.telaah_id','left');
		$this->db->join('table_skpd','table_anggotadprd.anggotadprd_id=table_skpd.skpd_id','left');
		$this->db->where("(table_telaah.telaah_kategori='3' OR table_telaah.telaah_kategori='6' OR table_telaah.telaah_kategori='10')");
		$this->db->where("(table_timeline3.timeline_sekwan_id = '1' OR table_timeline6.timeline_sekwan_id = '1' 
							OR table_timeline10.timeline_sekwan_id = '1' OR table_timeline3.timeline_sekwan_id = '2' OR table_timeline6.timeline_sekwan_id = '2' 
							OR table_timeline10.timeline_sekwan_id = '2')");
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit (5, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## PIMPINAN DPRD
	public function kadprd_history() {
		$this->db->select('table_telaah.*, table_anggotadprd.*, table_anggotadprd.anggotadprd_id as pegawai_id, table_anggotadprd.anggotadprd_name as pegawai_nama, table_anggotadprd.anggotadprd_jabatan as pegawai_namajabatan');
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','left');
		$this->db->join('table_timeline3','table_telaah.telaah_id=table_timeline3.telaah_id','left');
		$this->db->join('table_skpd','table_anggotadprd.anggotadprd_id=table_skpd.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',3);
		$this->db->where('table_timeline3.timeline_kadprd_id = 1 OR table_timeline3.timeline_kadprd_id = 2');
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit (5, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## KABID SEKDA
	public function kabid_sekda_history($bagian_id) {
		$this->db->select('table_telaah.telaah_id, table_telaah.telaah_kantortujuan, table_telaah.telaah_tanggalberangkat, , table_telaah.telaah_tanggalkembali, table_pegawai.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id');
		$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id');
		$this->db->join('table_timeline4','table_telaah.telaah_id=table_timeline4.telaah_id','left');
		$this->db->join('table_timeline9','table_telaah.telaah_id=table_timeline9.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		
		$this->db->group_start();
		$this->db->where('table_telaah.telaah_kategori',4);
		$this->db->or_where('table_telaah.telaah_kategori',9);
		$this->db->group_end();
		$this->db->where('table_timeline4.timeline_kabag_id',1);
		$this->db->or_where('table_timeline9.timeline_kabag_id',1);
		$this->db->or_where('table_timeline4.timeline_kabag_id',2);
		$this->db->or_where('table_timeline9.timeline_kabag_id',2);
		$this->db->where('table_bagian.bagian_id',$bagian_id);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit (5, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## ASISTEN
	public function asisten_history($asisten_id) {
		$this->db->select('table_telaah.telaah_id, table_telaah.telaah_kantortujuan, table_telaah.telaah_tanggalberangkat, , table_telaah.telaah_tanggalkembali, table_pegawai.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_sekda','table_telaah.telaah_id=table_relasi_sekda.telaah_id');
		$this->db->join('table_subbagian','table_subbagian.subbagian_id=table_relasi_sekda.subbagian_id');
		$this->db->join('table_bagian','table_bagian.bagian_id=table_subbagian.bagian_id');
		$this->db->join('table_asisten','table_asisten.asisten_id=table_bagian.asisten_id');
		$this->db->join('table_timeline4','table_telaah.telaah_id=table_timeline4.telaah_id','left');
		$this->db->join('table_timeline9','table_telaah.telaah_id=table_timeline9.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('(table_telaah.telaah_kategori = 4 OR table_telaah.telaah_kategori = 9) ');
		$this->db->where('(table_timeline4.timeline_asisten_id = 1 OR table_timeline9.timeline_asisten_id = 1 OR table_timeline4.timeline_asisten_id = 2 OR table_timeline9.timeline_asisten_id = 2 )');
		$this->db->where('table_asisten.asisten_id', $asisten_id);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit (5, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## SEKCAM
	public function sekcam_history($skpd_id) {
		$this->db->select('table_telaah.telaah_id, table_telaah.telaah_kantortujuan, table_telaah.telaah_tanggalberangkat, , table_telaah.telaah_tanggalkembali, table_pegawai.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_kelurahan','table_relasi_kelurahan.id_kelurahan=table_pegawai.skpd_id','left');
		$this->db->join('table_timeline5','table_telaah.telaah_id=table_timeline5.telaah_id','left');
		$this->db->join('table_timeline7','table_telaah.telaah_id=table_timeline7.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("(table_telaah.telaah_kategori='5' OR table_telaah.telaah_kategori='7')");
		$this->db->where("(table_timeline5.timeline_sekcam_id = '1' OR table_timeline7.timeline_sekcam_id = '1' OR table_timeline5.timeline_sekcam_id = '2' OR table_timeline7.timeline_sekcam_id = '2')");
		$this->db->where("(table_pegawai.skpd_id = '$skpd_id' OR table_relasi_kelurahan.id_kecamatan = '$skpd_id' )");
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit (5, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## CAMAT
	public function camat_history($skpd_id) {
		$this->db->select('table_telaah.telaah_id, table_telaah.telaah_kantortujuan, table_telaah.telaah_tanggalberangkat, , table_telaah.telaah_tanggalkembali, table_pegawai.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_relasi_kelurahan','table_relasi_kelurahan.id_kelurahan=table_pegawai.skpd_id','left');
		$this->db->join('table_timeline5','table_telaah.telaah_id=table_timeline5.telaah_id','left');
		$this->db->join('table_timeline7','table_telaah.telaah_id=table_timeline7.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("(table_telaah.telaah_kategori='5' OR table_telaah.telaah_kategori='7')");
		$this->db->where("(table_timeline5.timeline_camat_id = '1' OR table_timeline7.timeline_camat_id = '1' OR table_timeline5.timeline_camat_id = '2' OR table_timeline7.timeline_camat_id = '2')");
		$this->db->where("(table_pegawai.skpd_id = '$skpd_id' OR table_relasi_kelurahan.id_kecamatan = '$skpd_id' )");
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit (5, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## KASUBAG CAMAT / LURAH
	public function kasubag_lurah_history($skpd_id) {
		$this->db->select('table_telaah.telaah_id, table_telaah.telaah_kantortujuan, table_telaah.telaah_tanggalberangkat, , table_telaah.telaah_tanggalkembali, table_pegawai.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline7','table_telaah.telaah_id=table_timeline7.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->where('table_telaah.telaah_kategori',7);
		$this->db->where('(table_timeline7.timeline_lurah_id = 1 OR table_timeline7.timeline_lurah_id = 2)');
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit (5, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## KABID DINKES
	public function kabid_dinkes_history() {
		$this->db->select('table_telaah.telaah_id, table_telaah.telaah_kantortujuan, table_telaah.telaah_tanggalberangkat, , table_telaah.telaah_tanggalkembali, table_pegawai.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->where('table_telaah.telaah_kategori',1);
		$this->db->where("(table_jenis_skpd.jenis_skpd_id = 7 OR table_jenis_skpd.jenis_skpd_id = 10)");
		$this->db->where('(table_timeline1.timeline_kabid_id = 1 OR table_timeline1.timeline_kabid_id = 2)');
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit (5, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## SEKDIS DINKES
	public function sekdis_dinkes_history() {
		$this->db->select('table_telaah.telaah_id, table_telaah.telaah_kantortujuan, table_telaah.telaah_tanggalberangkat, , table_telaah.telaah_tanggalkembali, table_pegawai.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id','left');
		$this->db->where("(table_telaah.telaah_kategori='1' OR table_telaah.telaah_kategori='2')");
		$this->db->where("(table_jenis_skpd.jenis_skpd_id = 7 OR table_jenis_skpd.jenis_skpd_id = 10)");
		$this->db->where("(table_timeline1.timeline_sekdis_id = '1' OR table_timeline2.timeline_sekdis_id = '1' OR table_timeline1.timeline_sekdis_id = '2' OR table_timeline2.timeline_sekdis_id = '2')");
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit (5, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## KADIS DINKES
	public function kadis_dinkes_history() {
		$this->db->select('table_telaah.telaah_id, table_telaah.telaah_kantortujuan, table_telaah.telaah_tanggalberangkat, , table_telaah.telaah_tanggalkembali, table_pegawai.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->join('table_jenis_skpd','table_jenis_skpd.jenis_skpd_id=table_skpd.jenis_skpd','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->join('table_timeline2','table_telaah.telaah_id=table_timeline2.telaah_id','left');
		$this->db->where("(table_telaah.telaah_kategori='1' OR table_telaah.telaah_kategori='2')");
		$this->db->where("(table_jenis_skpd.jenis_skpd_id = 7 OR table_jenis_skpd.jenis_skpd_id = 10)");
		$this->db->where("(table_timeline1.timeline_kadis_id = '1' OR table_timeline2.timeline_kadis_id = '1' OR table_timeline1.timeline_kadis_id = '2' OR table_timeline2.timeline_kadis_id = '2')");
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit (5, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## KAPUS
	public function kapus_history($skpd_id) {
		$this->db->select('table_telaah.telaah_id, table_telaah.telaah_kantortujuan, table_telaah.telaah_tanggalberangkat, , table_telaah.telaah_tanggalkembali, table_pegawai.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline11','table_telaah.telaah_id=table_timeline11.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori', 11);
		$this->db->where('(table_timeline11.timeline_kapus_id = 1 OR table_timeline11.timeline_kapus_id = 2)');
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit (5, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	####################################### ACC #######################################
	
	public function update_timeline_1($data,$data2) {
		if($data2){
			$this->db->trans_begin();
			$this->db->update('table_telaah', $data2, array('telaah_id'=>$data2['telaah_id']));
			$this->db->update('table_timeline1', $data, array('telaah_id'=>$data['telaah_id']));

			if ($this->db->trans_status() === FALSE){ 
				$this->db->trans_rollback();
			} else {
				return $this->db->trans_commit();
			}
		} else {	
			return $this->db->update('table_timeline1', $data, array('telaah_id'=>$data['telaah_id']));
		}
	}
	
	public function update_timeline_2($data,$data2) {
		if($data2){
			$this->db->trans_begin();
			$this->db->update('table_telaah', $data2, array('telaah_id'=>$data2['telaah_id']));
			$this->db->update('table_timeline2', $data, array('telaah_id'=>$data['telaah_id']));

			if ($this->db->trans_status() === FALSE){ 
				$this->db->trans_rollback();
			} else {
				return $this->db->trans_commit();
			}
		} else {	
			return $this->db->update('table_timeline2', $data, array('telaah_id'=>$data['telaah_id']));
		}
	}
	
	public function update_timeline_3($data,$data2) {
		if($data2){
			$this->db->trans_begin();
			$this->db->update('table_telaah', $data2, array('telaah_id'=>$data2['telaah_id']));
			$this->db->update('table_timeline3', $data, array('telaah_id'=>$data['telaah_id']));

			if ($this->db->trans_status() === FALSE){ 
				$this->db->trans_rollback();
			} else {
				return $this->db->trans_commit();
			}
		} else {	
			return $this->db->update('table_timeline3', $data, array('telaah_id'=>$data['telaah_id']));
		}
	}
	
	public function update_timeline_4($data,$data2) {
		if($data2){
			$this->db->trans_begin();
			$this->db->update('table_telaah', $data2, array('telaah_id'=>$data2['telaah_id']));
			$this->db->update('table_timeline4', $data, array('telaah_id'=>$data['telaah_id']));

			if ($this->db->trans_status() === FALSE){ 
				$this->db->trans_rollback();
			} else {
				return $this->db->trans_commit();
			}
		} else {	
			return $this->db->update('table_timeline4', $data, array('telaah_id'=>$data['telaah_id']));
		}
	}
	
	public function update_timeline_5($data,$data2) {
		if($data2){
			$this->db->trans_begin();
			$this->db->update('table_telaah', $data2, array('telaah_id'=>$data2['telaah_id']));
			$this->db->update('table_timeline5', $data, array('telaah_id'=>$data['telaah_id']));

			if ($this->db->trans_status() === FALSE){ 
				$this->db->trans_rollback();
			} else {
				return $this->db->trans_commit();
			}
		} else {	
			return $this->db->update('table_timeline5', $data, array('telaah_id'=>$data['telaah_id']));
		}
	}
	
	public function update_timeline_6($data,$data2) {
		if($data2){
			$this->db->trans_begin();
			$this->db->update('table_telaah', $data2, array('telaah_id'=>$data2['telaah_id']));
			$this->db->update('table_timeline6', $data, array('telaah_id'=>$data['telaah_id']));

			if ($this->db->trans_status() === FALSE){ 
				$this->db->trans_rollback();
			} else {
				return $this->db->trans_commit();
			}
		} else {	
			return $this->db->update('table_timeline6', $data, array('telaah_id'=>$data['telaah_id']));
		}
	}
	
	public function update_timeline_7($data,$data2) {
		if($data2){
			$this->db->trans_begin();
			$this->db->update('table_telaah', $data2, array('telaah_id'=>$data2['telaah_id']));
			$this->db->update('table_timeline7', $data, array('telaah_id'=>$data['telaah_id']));

			if ($this->db->trans_status() === FALSE){ 
				$this->db->trans_rollback();
			} else {
				return $this->db->trans_commit();
			}
		} else {	
			return $this->db->update('table_timeline7', $data, array('telaah_id'=>$data['telaah_id']));
		}
	}
	
	public function update_timeline_8($data,$data2) {
		if($data2){
			$this->db->trans_begin();
			$this->db->update('table_telaah', $data2, array('telaah_id'=>$data2['telaah_id']));
			$this->db->update('table_timeline8', $data, array('telaah_id'=>$data['telaah_id']));

			if ($this->db->trans_status() === FALSE){ 
				$this->db->trans_rollback();
			} else {
				return $this->db->trans_commit();
			}
		} else {	
			return $this->db->update('table_timeline8', $data, array('telaah_id'=>$data['telaah_id']));
		}
	}
	
	public function update_timeline_9($data,$data2) {
		if($data2){
			$this->db->trans_begin();
			$this->db->update('table_telaah', $data2, array('telaah_id'=>$data2['telaah_id']));
			$this->db->update('table_timeline9', $data, array('telaah_id'=>$data['telaah_id']));

			if ($this->db->trans_status() === FALSE){ 
				$this->db->trans_rollback();
			} else {
				return $this->db->trans_commit();
			}
		} else {	
			return $this->db->update('table_timeline9', $data, array('telaah_id'=>$data['telaah_id']));
		}
	}
	
	public function update_timeline_10($data,$data2) {
		if($data2){
			$this->db->trans_begin();
			$this->db->update('table_telaah', $data2, array('telaah_id'=>$data2['telaah_id']));
			$this->db->update('table_timeline10', $data, array('telaah_id'=>$data['telaah_id']));

			if ($this->db->trans_status() === FALSE){ 
				$this->db->trans_rollback();
			} else {
				return $this->db->trans_commit();
			}
		} else {	
			return $this->db->update('table_timeline10', $data, array('telaah_id'=>$data['telaah_id']));
		}
	}
	
	public function update_timeline_11($data,$data2) {
		if($data2){
			$this->db->trans_begin();
			$this->db->update('table_telaah', $data2, array('telaah_id'=>$data2['telaah_id']));
			$this->db->update('table_timeline11', $data, array('telaah_id'=>$data['telaah_id']));

			if ($this->db->trans_status() === FALSE){ 
				$this->db->trans_rollback();
			} else {
				return $this->db->trans_commit();
			}
		} else {	
			return $this->db->update('table_timeline11', $data, array('telaah_id'=>$data['telaah_id']));
		}
	}
	
	## HISTORY PELAKSANA
	public function history_pelaksana($limit, $start, $pegawai_id, $kategori_id) {
		if($kategori_id==3){
			$this->db->select('table_telaah.*, table_anggotadprd.*, table_anggotadprd.anggotadprd_id as pegawai_id, table_anggotadprd.anggotadprd_id as pegawai_nip, table_anggotadprd.anggotadprd_name as pegawai_nama, table_anggotadprd.anggotadprd_jabatan as pegawai_namajabatan, table_anggotadprd.anggotadprd_jabatan as pegawai_jabatan');
			$this->db->from('table_telaah');
			$this->db->join('table_anggotadprd','table_anggotadprd.anggotadprd_id=table_telaah.telaah_pelaksana', 'LEFT');	
			$this->db->join('table_skpd',"table_skpd.jenis_skpd='2'", 'LEFT');
			$this->db->where('anggotadprd_id',$pegawai_id);
		} else if ($kategori_id==8){
			$this->db->select('telaah_id,telaah_waktuinput,telaah_perihal, pegawai_id, pegawai_nip, telaah_kategori, user_id, pegawai_nama, pegawai_namajabatan, pegawai_jabatan');
			$this->db->from('table_telaah');
			$this->db->join('table_pimpinan','table_pimpinan.pegawai_id=table_telaah.telaah_pelaksana', 'LEFT');
			$this->db->join('table_skpd','table_skpd.skpd_id=table_pimpinan.skpd_id', 'LEFT');
			$this->db->where('pegawai_id',$pegawai_id);
		} else {	
			$this->db->select('telaah_id,telaah_waktuinput,telaah_perihal, pegawai_id, telaah_kategori, user_id, pegawai_nip, pegawai_nama, pegawai_namajabatan, pegawai_jabatan');
			$this->db->from('table_telaah');
			$this->db->join('table_pegawai','table_pegawai.pegawai_id=table_telaah.telaah_pelaksana', 'LEFT');
			$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');
			$this->db->where('pegawai_id',$pegawai_id);
		}
		$this->db->where('telaah_status', 2);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
		
	}
	
	## KOTA TUJUAN
	public function get_kota_tujuan($kabkot_id) {
		$this->db->select('kabupaten_kota');
		$this->db->from('table_kabkot');
		$this->db->where('kabkot_id',$kabkot_id);
		$query = $this->db->get ();
		return $query->result_array()[0]['kabupaten_kota'];
	}
	
	## PENGIKUT
	public function get_pengikut($limit, $start, $telaah_id, $telaah_kategori) {
		if($telaah_kategori==3){	
			$this->db->select('table_pengikut.*, table_anggotadprd.anggotadprd_name as pegawai_nama, table_anggotadprd.anggotadprd_jabatan as pegawai_namajabatan');
			$this->db->from('table_pengikut');
			$this->db->join('table_anggotadprd','table_pengikut.pegawai_id = table_anggotadprd.anggotadprd_id', 'LEFT');	
		} else if($telaah_kategori==8){	
			$this->db->select('table_pengikut.*, pegawai_nip, pegawai_nama, pegawai_namajabatan');
			$this->db->from('table_pengikut');
			$this->db->join('table_pegawai','table_pengikut.pegawai_id = table_pegawai.pegawai_id', 'LEFT');	
		} else {
			$this->db->select('table_pengikut.*, pegawai_nip, pegawai_nama, pegawai_namajabatan');
			$this->db->from('table_pengikut');
			$this->db->join('table_pegawai','table_pengikut.pegawai_id = table_pegawai.pegawai_id', 'LEFT');	
		}
		$this->db->where('telaah_id',$telaah_id);
		if($limit){	
			$this->db->limit ($limit, $start);
		}
		$query = $this->db->get ();
		return $query->result();
	}
	
	## COUNT RINCIAN BELANJA
	public function count_rincian_belanja($telaah_id) {
		$this->db->from('table_rincian_biaya');	
		$this->db->where('telaah_id',$telaah_id);
		return $this->db->count_all_results();
	}
	
	## COUNT PENGELUARAN RILL
	public function count_pengeluaran_rill($telaah_id) {
		$this->db->from('table_pengeluaran_rill');	
		$this->db->where('telaah_id',$telaah_id);
		return $this->db->count_all_results();
	}
	
	## LAPORAN PERJALANAN
	public function laporan_perjalanan($telaah_id) {
		$this->db->select('*');	
		$this->db->from('table_laporanperjalanan');	
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result();
	}
		
	## COUNT LAPORAN PERJALANAN
	public function count_laporan_perjalanan($telaah_id) {
		$this->db->from('table_laporanperjalanan');	
		$this->db->where('telaah_id',$telaah_id);
		return $this->db->count_all_results();
	}
		
	##
	## COUNT PERJALANAN SEMESTER
	public function count_semester($semester1,$semester2) {
		$a1 = date('Y')."-01-01";
		$a2 = date('Y')."-06-31";
		$b1 = date('Y')."-07-01";
		$b2 = date('Y')."-12-31";
		$this->db->from('table_telaah');	
		$this->db->where('telaah_status',2);
		if($semester1){
			$this->db->where('telaah_tanggalberangkat BETWEEN "'.$a1.'" AND "'.$a2.'"');
		} else {
			$this->db->where('telaah_tanggalberangkat BETWEEN "'.$b1.'" AND "'.$b2.'"');
		}
		return $this->db->count_all_results();
	}
	
	## COUNT PERJALANAN TRI WULAN
	public function count_triwulan($triwulan1,$triwulan2,$triwulan3,$triwulan4) {
		$a1 = date('Y')."-01-01";
		$a2 = date('Y')."-03-31";
		$b1 = date('Y')."-04-01";
		$b2 = date('Y')."-06-30";
		$c1 = date('Y')."-07-01";
		$c2 = date('Y')."-09-31";
		$d1 = date('Y')."-10-01";
		$d2 = date('Y')."-12-31";
		$this->db->from('table_telaah');	
		$this->db->where('telaah_status',2);
		if($triwulan1){
			$this->db->where('telaah_tanggalberangkat BETWEEN "'.$a1.'" AND "'.$a2.'"');
		} else if($triwulan2){
			$this->db->where('telaah_tanggalberangkat BETWEEN "'.$b1.'" AND "'.$b2.'"');
		} else if($triwulan3){
			$this->db->where('telaah_tanggalberangkat BETWEEN "'.$c1.'" AND "'.$c2.'"');
		} else {
			$this->db->where('telaah_tanggalberangkat BETWEEN "'.$d1.'" AND "'.$d2.'"');
		}
		return $this->db->count_all_results();
	}
	
	## COUNT PERJALANAN BULAN
	public function count_month($januari,$februari,$maret,$april,$mei,$juni,$juli,$agustus,$september,$oktober,$november,$desember) {
		$a1 = date('Y')."-01-01";
		$a2 = date('Y')."-01-31";
		$b1 = date('Y')."-02-01";
		$b2 = date('Y')."-02-29";
		$c1 = date('Y')."-03-01";
		$c2 = date('Y')."-03-31";
		$d1 = date('Y')."-04-01";
		$d2 = date('Y')."-04-30";
		$e1 = date('Y')."-05-01";
		$e2 = date('Y')."-05-31";
		$f1 = date('Y')."-06-01";
		$f2 = date('Y')."-06-30";
		$g1 = date('Y')."-07-01";
		$g2 = date('Y')."-07-31";
		$h1 = date('Y')."-08-01";
		$h2 = date('Y')."-08-31";
		$i1 = date('Y')."-09-01";
		$i2 = date('Y')."-09-30";
		$j1 = date('Y')."-10-01";
		$j2 = date('Y')."-10-31";
		$k1 = date('Y')."-11-01";
		$k2 = date('Y')."-11-30";
		$l1 = date('Y')."-12-01";
		$l2 = date('Y')."-12-31";
		$this->db->from('table_telaah');	
		$this->db->where('telaah_status',2);
		if($januari){
			$this->db->where('telaah_tanggalberangkat BETWEEN "'.$a1.'" AND "'.$a2.'"');
		} else if($februari){
			$this->db->where('telaah_tanggalberangkat BETWEEN "'.$b1.'" AND "'.$b2.'"');
		} else if($maret){
			$this->db->where('telaah_tanggalberangkat BETWEEN "'.$c1.'" AND "'.$c2.'"');
		} else if($april){
			$this->db->where('telaah_tanggalberangkat BETWEEN "'.$d1.'" AND "'.$d2.'"');
		} else if($mei){
			$this->db->where('telaah_tanggalberangkat BETWEEN "'.$e1.'" AND "'.$e2.'"');
		} else if($juni){
			$this->db->where('telaah_tanggalberangkat BETWEEN "'.$f1.'" AND "'.$f2.'"');
		} else if($juli){
			$this->db->where('telaah_tanggalberangkat BETWEEN "'.$g1.'" AND "'.$g2.'"');
		} else if($agustus){
			$this->db->where('telaah_tanggalberangkat BETWEEN "'.$h1.'" AND "'.$h2.'"');
		} else if($september){
			$this->db->where('telaah_tanggalberangkat BETWEEN "'.$i1.'" AND "'.$i2.'"');
		} else if($oktober){
			$this->db->where('telaah_tanggalberangkat BETWEEN "'.$j1.'" AND "'.$j2.'"');
		} else if($november){
			$this->db->where('telaah_tanggalberangkat BETWEEN "'.$k1.'" AND "'.$k2.'"');
		} else if($desember){
			$this->db->where('telaah_tanggalberangkat BETWEEN "'.$l1.'" AND "'.$l2.'"');
		}
		return $this->db->count_all_results();
	}
	
	## COUNT PERJALANAN DALAM DAERAH
	public function count_tahun() {
		$a1 = date('Y')."-01-01";
		$a2 = date('Y')."-12-31";
		$this->db->from('table_telaah');	
		$this->db->where('telaah_status',2);
		$this->db->where('telaah_tanggalberangkat BETWEEN "'.$a1.'" AND "'.$a2.'"');
		return $this->db->count_all_results();
	}
	
	## COUNT PERJALANAN DALAM DAERAH
	public function count_prov() {
		$this->db->select('COUNT(telaah_id) as jumlah, provinsi');	
		$this->db->from('table_telaah');	
		$this->db->join('table_provinsi','table_provinsi.provinsi_id = table_telaah.telaah_provinsitujuan');	
		$this->db->group_by('provinsi_id');	
		$this->db->order_by('jumlah','DESC');	
		$this->db->limit(10);	
		$query = $this->db->get ();
		return $query->result();
	}
	
	## COUNT PERJALANAN DALAM DAERAH
	public function count_prov_luar_daerah() {
		$this->db->select('COUNT(telaah_id) as jumlah, provinsi');	
		$this->db->from('table_telaah');	
		$this->db->join('table_provinsi','table_provinsi.provinsi_id = table_telaah.telaah_provinsitujuan');	
		$this->db->where('telaah_status',2);
		$this->db->where('telaah_domainperjalanan',1);
		$this->db->group_by('provinsi_id');	
		$this->db->order_by('jumlah','DESC');	
		$this->db->limit(10);	
		$query = $this->db->get ();
		return $query->result();
	}
	
	## COUNT PERJALANAN DALAM DAERAH
	public function count_kab() {
		$this->db->select('COUNT(telaah_id) as jumlah, kabupaten_kota');	
		$this->db->from('table_telaah');	
		$this->db->join('table_kabkot','table_kabkot.kabkot_id = table_telaah.telaah_kotatujuan');	
		$this->db->where('telaah_status',2);
		$this->db->group_start();
		$this->db->where('telaah_domainperjalanan',1);
		$this->db->or_where('telaah_domainperjalanan',2);
		$this->db->group_end();
		$this->db->group_by('kabkot_id');	
		$this->db->order_by('jumlah','DESC');	
		$this->db->limit(10);	
		$query = $this->db->get ();
		return $query->result();
	}
	
	## NOTIF
	public function notif($telaah_id) {
		$query = $this->db->query("SELECT table_telaah.telaah_id, table_telaah.telaah_kategori, table_pegawai.pegawai_nama, anggotadprd_name,
				users.id as user_id, users.username, table_pegawai.skpd_id, skpd_nama, table_pegawai.bagian_id, jenis_skpd_id, table_bagian.asisten_id

				FROM table_telaah
				LEFT JOIN table_pegawai ON table_pegawai.pegawai_id = table_telaah.telaah_pelaksana
				LEFT JOIN table_pimpinan ON table_pimpinan.pegawai_id = table_telaah.telaah_pelaksana
				LEFT JOIN table_anggotadprd ON table_anggotadprd.anggotadprd_id=table_telaah.telaah_pelaksana
				left JOIN table_timeline1 ON table_timeline1.telaah_id = table_telaah.telaah_id 
				left JOIN table_timeline2 ON table_timeline2.telaah_id = table_telaah.telaah_id 
				left JOIN table_timeline3 ON table_timeline3.telaah_id = table_telaah.telaah_id 
				left JOIN table_timeline4 ON table_timeline4.telaah_id = table_telaah.telaah_id 
				left JOIN table_timeline5 ON table_timeline5.telaah_id = table_telaah.telaah_id 
				left JOIN table_timeline6 ON table_timeline6.telaah_id = table_telaah.telaah_id 
				left JOIN table_timeline7 ON table_timeline7.telaah_id = table_telaah.telaah_id 
				left JOIN table_timeline8 ON table_timeline8.telaah_id = table_telaah.telaah_id 
				left JOIN table_timeline9 ON table_timeline9.telaah_id = table_telaah.telaah_id 
				left JOIN table_timeline10 ON table_timeline10.telaah_id = table_telaah.telaah_id 
				left JOIN table_timeline11 ON table_timeline11.telaah_id = table_telaah.telaah_id 
				left JOIN table_relasi_kelurahan ON table_pegawai.skpd_id = table_relasi_kelurahan.id_kelurahan
				left JOIN table_skpd ON table_pegawai.skpd_id = table_skpd.skpd_id
				left JOIN table_jenis_skpd ON table_skpd.jenis_skpd = table_jenis_skpd.jenis_skpd_id
				LEFT JOIN table_bagian ON table_bagian.bagian_id = table_pegawai.bagian_id
				LEFT JOIN table_asisten ON table_asisten.asisten_id = table_bagian.asisten_id
				LEFT JOIN users ON users.skpd_id = 
				CASE	
					 WHEN table_timeline1.timeline_kabid_id=0 AND jenis_skpd_id!=7 THEN table_pegawai.skpd_id
					WHEN table_timeline1.timeline_kabid_id=0 AND jenis_skpd_id=7 THEN 36
					 WHEN table_timeline1.timeline_sekdis_id=0 AND jenis_skpd_id!=7 THEN table_pegawai.skpd_id
					 WHEN table_timeline1.timeline_sekdis_id=0 AND jenis_skpd_id=7 THEN 36
					WHEN table_timeline1.timeline_kadis_id=0 AND jenis_skpd_id!=7 THEN table_pegawai.skpd_id
					WHEN table_timeline1.timeline_kadis_id=0 AND jenis_skpd_id=7 THEN 36
					
					WHEN table_timeline2.timeline_kadis_id=0 THEN table_pegawai.skpd_id
					WHEN table_timeline2.timeline_sekda_id=0 THEN 3
					WHEN table_timeline2.timeline_walikota_id=0 THEN 177
					
					WHEN table_timeline3.timeline_kasubid_id=0 THEN 2
					WHEN table_timeline3.timeline_sekwan_id=0 THEN 2
					WHEN table_timeline3.timeline_kadprd_id=0 THEN 2
					
					WHEN table_timeline4.timeline_kabag_id=0 THEN table_pegawai.skpd_id
					WHEN table_timeline4.timeline_asisten_id=0 THEN 3
					WHEN table_timeline4.timeline_sekda_id=0 THEN 3
					WHEN table_timeline4.timeline_walikota_id=0 THEN 177
					
					WHEN table_timeline5.timeline_sekcam_id=0 THEN table_relasi_kelurahan.id_kecamatan
					WHEN table_timeline5.timeline_camat_id=0 THEN table_relasi_kelurahan.id_kecamatan
					WHEN table_timeline5.timeline_sekda_id=0 THEN 3
					WHEN table_timeline5.timeline_walikota_id=0 THEN 177
					
					WHEN table_timeline6.timeline_kabag_id=0 THEN table_pegawai.skpd_id
					WHEN table_timeline6.timeline_sekwan_id=0 THEN 2
					
					WHEN table_timeline7.timeline_lurah_id=0 THEN table_pegawai.skpd_id
					WHEN table_timeline7.timeline_sekcam_id=0 THEN table_relasi_kelurahan.id_kecamatan
					WHEN table_timeline7.timeline_camat_id=0 THEN table_relasi_kelurahan.id_kecamatan
					
					WHEN table_timeline8.timeline_kabag_id=0 THEN table_pimpinan.skpd_id 
					WHEN table_timeline8.timeline_sekda_id=0 THEN 3
					WHEN table_timeline8.timeline_walikota_id=0 THEN 177
					
					WHEN table_timeline9.timeline_kabag_id=0 THEN table_pegawai.skpd_id 
					WHEN table_timeline9.timeline_asisten_id=0 THEN 3
					WHEN table_timeline9.timeline_sekda_id=0 THEN 3
					
					WHEN table_timeline10.timeline_kabag_id=0 THEN table_pegawai.skpd_id 
					WHEN table_timeline10.timeline_sekwan_id=0 THEN 2
					WHEN table_timeline10.timeline_sekda_id=0 THEN 3
					WHEN table_timeline10.timeline_walikota_id=0 THEN 177
					
					WHEN table_timeline11.timeline_kapus_id=0 THEN table_pegawai.skpd_id 
				END 
				LEFT JOIN users_groups ON users.id = users_groups.user_id
				LEFT JOIN groups ON groups.id = users_groups.group_id
				LEFT JOIN users_groups_bagian ON users_groups_bagian.user_id = users.id
				LEFT JOIN users_groups_asisten ON users_groups_asisten.user_id = users.id
				WHERE table_telaah.telaah_id = '$telaah_id'
				AND CASE
					WHEN table_timeline1.timeline_kabid_id=0 THEN groups.id=2 
					WHEN table_timeline1.timeline_sekdis_id=0 THEN groups.id=3
					WHEN table_timeline1.timeline_kadis_id=0 THEN groups.id=4 
					
					WHEN table_timeline2.timeline_sekdis_id=0 THEN groups.id=3
					WHEN table_timeline2.timeline_kadis_id=0 THEN groups.id=4 
					WHEN table_timeline2.timeline_sekda_id=0 THEN groups.id=6
					WHEN table_timeline2.timeline_walikota_id=0 THEN groups.id=8
					
					WHEN table_timeline3.timeline_kasubid_id=0 THEN groups.id=2
					WHEN table_timeline3.timeline_sekwan_id=0 THEN groups.id=10
					WHEN table_timeline3.timeline_kadprd_id=0 THEN groups.id=7
					
					WHEN table_timeline4.timeline_kabag_id=0 THEN groups.id=2 AND users_groups_bagian.bagian_id=table_pegawai.bagian_id
					WHEN table_timeline4.timeline_asisten_id=0 THEN groups.id=5 
					WHEN table_timeline4.timeline_sekda_id=0 THEN groups.id=6
					WHEN table_timeline4.timeline_walikota_id=0 THEN groups.id=8
					
					WHEN table_timeline5.timeline_sekcam_id=0 THEN groups.id=12
					WHEN table_timeline5.timeline_camat_id=0 THEN groups.id=11 
					WHEN table_timeline5.timeline_sekda_id=0 THEN groups.id=6
					WHEN table_timeline5.timeline_walikota_id=0 THEN groups.id=8
					
					WHEN table_timeline6.timeline_kabag_id=0 THEN groups.id=2
					WHEN table_timeline6.timeline_sekwan_id=0 THEN groups.id=10
					
					WHEN table_timeline7.timeline_lurah_id=0 THEN groups.id=13
					WHEN table_timeline7.timeline_sekcam_id=0 THEN groups.id=12
					WHEN table_timeline7.timeline_camat_id=0 THEN groups.id=11
					
					WHEN table_timeline8.timeline_kabag_id=0 THEN groups.id=2 AND users_groups_bagian.bagian_id=table_pimpinan.bagian_id
					WHEN table_timeline8.timeline_sekda_id=0 THEN groups.id=6
					WHEN table_timeline8.timeline_walikota_id=0 THEN groups.id=8
					
					WHEN table_timeline9.timeline_kabag_id=0 THEN groups.id=2 AND users_groups_bagian.bagian_id=table_pegawai.bagian_id
					WHEN table_timeline9.timeline_asisten_id=0 THEN groups.id=5 AND table_bagian.asisten_id=users_groups_asisten.asisten_id
					WHEN table_timeline9.timeline_sekda_id=0 THEN groups.id=6
					
					WHEN table_timeline10.timeline_kabag_id=0 THEN groups.id=2
					WHEN table_timeline10.timeline_sekwan_id=0 THEN groups.id=10
					WHEN table_timeline10.timeline_sekda_id=0 THEN groups.id=6
					WHEN table_timeline10.timeline_walikota_id=0 THEN groups.id=8
					
					WHEN table_timeline11.timeline_kapus_id=0 THEN groups.id=16
				END 
                                    ");
        return $query->result();
	}
	
	public function get_notif($user_id) {
		$this->db->select('*');
		$this->db->from('table_notifikasi');
		$this->db->where('user_id',$user_id);
		$this->db->where('status',1);
		$this->db->order_by('notifikasi_id','DESC');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function create_notif($data) {
		$this->db->insert('table_notifikasi', $data);
	}
	
	public function non_aktif_notif($data) {
        $this->db->update('table_notifikasi', $data, array('user_id'=>$data['user_id']));
    }

	public function anggaran_opd($key) {
		$this->db->select('*');
		$this->db->from('table_skpd');
		$this->db->where('table_skpd.skpd_id', 3);
		$this->db->where('status', 1);
		if($key){
			$this->db->like('skpd_nama', $key);
		}
		$query1 = $this->db->get_compiled_select(); 
			
		$this->db->select('*');
		$this->db->from('table_skpd');
		$this->db->where('table_skpd.skpd_id', 2);
		$this->db->where('status', 1);
		if($key){
			$this->db->like('skpd_nama', $key);
		}
		$query2 = $this->db->get_compiled_select(); 
				
		$this->db->select('*');
		$this->db->from('table_skpd');
		$this->db->like('table_skpd.skpd_id', 182);
		$this->db->where('status', 1);
		if($key){
			$this->db->like('skpd_nama', $key);
		}
		$query3 = $this->db->get_compiled_select(); 
			
		$this->db->select('*');
		$this->db->from('table_skpd');
		$this->db->like('table_skpd.skpd_nama', 'badan');
		$this->db->where('status', 1);
		if($key){
			$this->db->like('skpd_nama', $key);
		}
		$query4 = $this->db->get_compiled_select(); 
			
		$this->db->select('*');
		$this->db->from('table_skpd');
		$this->db->like('table_skpd.skpd_nama', 'dinas');
		$this->db->where('status', 1);
		if($key){
			$this->db->like('skpd_nama', $key);
		}
		$query5 = $this->db->get_compiled_select(); 
			
		$this->db->select('*');
		$this->db->from('table_skpd');
		$this->db->where('table_skpd.skpd_id', 37);
		$this->db->where('status', 1);
		if($key){
			$this->db->like('skpd_nama', $key);
		}
		$query6 = $this->db->get_compiled_select(); 
			
		$this->db->select('*');
		$this->db->from('table_skpd');
		$this->db->where('table_skpd.skpd_id', 15);
		$this->db->where('status', 1);
		if($key){
			$this->db->like('skpd_nama', $key);
		}
		$query7 = $this->db->get_compiled_select(); 
			
		$this->db->select('*');
		$this->db->from('table_skpd');
		$this->db->like('table_skpd.skpd_nama', 'kecamatan');
		$this->db->where('status', 1);
		if($key){
			$this->db->like('skpd_nama', $key);
		}
		$query8 = $this->db->get_compiled_select();
			
		$this->db->select('*');
		$this->db->from('table_skpd');
		$this->db->like('table_skpd.skpd_nama', 'kelurahan');
		$this->db->where('status', 1);
		if($key){
			$this->db->like('skpd_nama', $key);
		}
		$query9 = $this->db->get_compiled_select();
			
		$this->db->select('*');
		$this->db->from('table_skpd');
		$this->db->like('skpd_nama', 'puskesmas');
		$this->db->where('status', 1);
		if($key){
			$this->db->like('skpd_nama', $key);
		}
		$query10 = $this->db->get_compiled_select();
			
		$query = $this->db->query($query1." UNION ALL ".$query2." UNION ALL ".
								  $query3." UNION ALL ".$query4." UNION ALL ".
								  $query5." UNION ALL ".$query6." UNION ALL ".
								  $query7." UNION ALL ".$query8." UNION ALL ".
								  $query9." UNION ALL ".$query10."");
				return $query->result();
	}
	
	public function anggaran_sekretariat($key) {
		$this->db->select('table_skpd.skpd_id,table_anggaran.bagian_id,nama_bagian as skpd_nama,sum(pagu) AS pagu,sum(sisa_pagu) AS sisa_pagu');
		$this->db->from('table_anggaran');
		$this->db->join('table_skpd','table_anggaran.skpd_id=table_skpd.skpd_id');
		$this->db->join('table_bagian','table_bagian.bagian_id=table_anggaran.bagian_id');
		$this->db->where('table_anggaran.skpd_id',3);
		if($key){
			$this->db->like('nama_bagian', $key);
		}
		$this->db->group_by('table_anggaran.bagian_id');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function detail_anggaran_opd($skpd_id, $key) {
		$this->db->select('*');
		$this->db->from('table_anggaran');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_anggaran.skpd_id');
		$this->db->where('table_anggaran.skpd_id', $skpd_id);
		if($key){
			$this->db->group_start();
			$this->db->like('nama_program',$key);
			$this->db->or_like('uraian',$key);
			$this->db->group_end();
		}
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function detail_anggaran_sekretariat($bagian_id, $key) {
		$this->db->select('*');
		$this->db->from('table_anggaran');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_anggaran.skpd_id');
		$this->db->join('table_bagian','table_bagian.bagian_id=table_anggaran.bagian_id');
		$this->db->where('table_anggaran.skpd_id', 3);
		$this->db->where('table_anggaran.bagian_id', $bagian_id);
		if($key){
			$this->db->group_start();
			$this->db->like('nama_program',$key);
			$this->db->or_like('uraian',$key);
			$this->db->group_end();
		}
		$query = $this->db->get ();
		return $query->result();
	}
}