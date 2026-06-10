<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_telaah extends CI_Model
{
	
	public function record_countwalikota() {
		return $this->db->count_all("table_telaah 
			LEFT JOIN table_pimpinan ON table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id
			LEFT JOIN table_timeline8 ON table_telaah.telaah_id=table_timeline8.telaah_id
			WHERE telaah_kategori='8' 
			AND table_timeline8.timeline_kabag_id = '0'
			");
	}
	
	public function datawalikota($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pimpinan','table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id','left');
		$this->db->join('table_timeline8','table_telaah.telaah_id=table_timeline8.telaah_id','left');
		$this->db->join('table_skpd','table_pimpinan.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("table_telaah.telaah_kategori", 8);
		//$this->db->where('table_timeline8.timeline_kabag_id',0);
		$this->db->order_by('table_telaah.telaah_id','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function datawalikota2($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pimpinan','table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id','left');
		$this->db->join('table_timeline8','table_telaah.telaah_id=table_timeline8.telaah_id','left');
		$this->db->join('table_skpd','table_pimpinan.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("table_telaah.telaah_kategori", 8);
		//$this->db->where('table_timeline8.timeline_kabag_id',0);
		$this->db->order_by('table_telaah.telaah_id','DESC');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function datawalikota3($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pimpinan','table_telaah.telaah_pelaksana=table_pimpinan.pegawai_id','left');
		$this->db->join('table_timeline8','table_telaah.telaah_id=table_timeline8.telaah_id','left');
		$this->db->join('table_skpd','table_pimpinan.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("table_telaah.telaah_kategori", 8);
		//$this->db->where('table_timeline8.timeline_kabag_id',0);
		$this->db->order_by('table_telaah.telaah_id','DESC');
		$this->db->limit ($limit, $start);
		$query = $this->db->get ();
		return $query->result();
	}
	
	## KABID OPD
	public function kabid_opd($limit, $start, $skpd_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("table_telaah.telaah_kategori", 1);
		$this->db->where('table_timeline1.timeline_kabid_id',0);
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->where('telaah_sekretariat != 1 ');
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function kabid_opd_search($column, $value, $limit, $start, $skpd_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where("table_telaah.telaah_kategori", 1);
		$this->db->where('table_timeline1.timeline_kabid_id',0);
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->where('telaah_sekretariat != 1 ');
		$this->db->like($column,$value);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## SEKDIS
	public function sekdis($limit, $start, $skpd_id) {
		$this->db->select('table_telaah.*, table_pegawai.*');
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
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function sekdis_search($column, $value, $limit, $start, $skpd_id) {
		$this->db->select('table_telaah.*, table_pegawai.*');
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
		$this->db->like($column,$value);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## KADIS
	public function kadis($limit, $start, $skpd_id) {
		$this->db->select('table_telaah.*, table_pegawai.*');
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
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function kadis_search($column, $value, $limit, $start, $skpd_id) {
		$this->db->select('table_telaah.*, table_pegawai.*');
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
		$this->db->like($column,$value);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## SEKDA
	public function sekda($limit, $start) {
		
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
			$query2 = $this->db->get_compiled_select();

			if($limit){
				$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT $start, $limit");
				return $query->result();
			} else {
				$query = $this->db->query($query1." UNION ALL ".$query2."");
				return $query->result_array();
			}
			
	}
	
	public function sekda_search($column, $value, $limit, $start) {
		
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
		
		$this->db->like($column,$value);
		
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
			$this->db->like($column,$value);
			$query2 = $this->db->get_compiled_select();

			if($limit){
				$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT $start, $limit");
				return $query->result();
			} else {
				$query = $this->db->query($query1." UNION ALL ".$query2."");
				return $query->result_array();
			}
	}
	
	## WALIKOTA
	public function walikota($limit, $start) {
		
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
		
		$this->db->where('table_timeline8.timeline_sekda_id',1);
		$this->db->group_start();
				$this->db->where('table_timeline8.timeline_walikota_id',0);
				$this->db->or_where('table_timeline8.timeline_walikota_id',5);
		$this->db->group_end();
		$query2 = $this->db->get_compiled_select();

		if($limit){
			$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT $start, $limit");
			return $query->result();
		} else {
			$query = $this->db->query($query1." UNION ALL ".$query2."");
			return $query->result_array();
		}
			
	}
	
	public function walikota_search($column, $value, $limit, $start) {
		
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
		$this->db->like($column,$value);
		
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
		
		$this->db->where('table_timeline8.timeline_sekda_id',1);
		$this->db->group_start();
				$this->db->where('table_timeline8.timeline_walikota_id',0);
				$this->db->or_where('table_timeline8.timeline_walikota_id',5);
		$this->db->group_end();
		$this->db->like($column,$value);
		$query2 = $this->db->get_compiled_select();

		if($limit){
			$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT $start, $limit");
			return $query->result();
		} else {
			$query = $this->db->query($query1." UNION ALL ".$query2."");
			return $query->result_array();
		}
		
	}
	
	## KABID DPRD
	public function kabid_dprd($limit, $start) {
		$this->db->select('table_telaah.*, table_anggotadprd.*, table_pegawai.*');
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
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function kabid_dprd_search($column, $value, $limit, $start) {
		$this->db->select('table_telaah.*, table_anggotadprd.*, table_pegawai.*');
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
		$this->db->like($column,$value);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## SEKWAN
	public function sekwan($limit, $start) {
		$this->db->select('*, table_telaah.telaah_id as telaah_id');
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
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	
	public function sekwan_search($column, $value, $limit, $start) {
		$this->db->select('*, table_telaah.telaah_id as telaah_id');
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
		$this->db->like($column,$value);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
		
	## PIMPINAN DPRD
	public function kadprd($limit, $start) {
		$this->db->select('table_telaah.*, table_anggotadprd.*');
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','left');
		$this->db->join('table_timeline3','table_telaah.telaah_id=table_timeline3.telaah_id','left');
		$this->db->join('table_skpd','table_anggotadprd.anggotadprd_id=table_skpd.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',3);
		$this->db->where('table_timeline3.timeline_sekwan_id',1);
		$this->db->where('table_timeline3.timeline_kadprd_id',0);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function kadprd_search($column, $value, $limit, $start) {
		$this->db->select('table_telaah.*, table_anggotadprd.*');
		$this->db->from('table_telaah');
		$this->db->join('table_anggotadprd','table_telaah.telaah_pelaksana=table_anggotadprd.anggotadprd_id','left');
		$this->db->join('table_timeline3','table_telaah.telaah_id=table_timeline3.telaah_id','left');
		$this->db->join('table_skpd','table_anggotadprd.anggotadprd_id=table_skpd.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori',3);
		$this->db->where('table_timeline3.timeline_sekwan_id',1);
		$this->db->where('table_timeline3.timeline_kadprd_id',0);
		$this->db->like($column,$value);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## KABID SEKDA
	public function kabid_sekda($limit, $start, $bagian_id) {
		
		if($this->ion_auth->user()->row()->id == 638) {
			
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
			$query2 = $this->db->get_compiled_select();

			if($limit){
				$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT $start, $limit");
				return $query->result();
			} else {
				$query = $this->db->query($query1." UNION ALL ".$query2."");
				return $query->result_array();
			}
			
		} else {
			
			$this->db->select('table_telaah.*, table_pegawai.*, table_subbagian.*');
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
			$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
			if($limit){
				$this->db->limit ($limit, $start);
				$query = $this->db->get ();
				return $query->result();
			} else {
				return $this->db->count_all_results(); 
			}
		}
		
	}
	
	public function kabid_sekda_search($column, $value, $limit, $start, $bagian_id) {
		
		if($this->ion_auth->user()->row()->id == 638) {
			
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
			$this->db->like($column,$value);
			$query2 = $this->db->get_compiled_select();

			if($limit){
				$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT $start, $limit");
				return $query->result();
			} else {
				$query = $this->db->query($query1." UNION ALL ".$query2."");
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
			$this->db->like($column,$value);
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
			$this->db->like($column,$value);
			$query2 = $this->db->get_compiled_select();

			if($limit){
				$query = $this->db->query($query1." UNION ALL ".$query2." ORDER BY telaah_waktuinput DESC LIMIT $start, $limit");
				return $query->result();
			} else {
				$query = $this->db->query($query1." UNION ALL ".$query2."");
				return $query->result_array();
			}
		}
		
	}
	
	## ASISTEN
	public function asisten($limit, $start, $asisten_id) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_subbagian.*');
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
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function asisten_search($column, $value, $limit, $start, $asisten_id) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_subbagian.*');
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
		$this->db->like($column,$value);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## SEKCAM
	public function sekcam($limit, $start, $skpd_id) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.*');
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
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function sekcam_search($column, $value, $limit, $start, $skpd_id) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.*');
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
		$this->db->like($column,$value);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## CAMAT
	public function camat($limit, $start, $skpd_id) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.*');
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
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function camat_search($column, $value, $limit, $start, $skpd_id) {
		$this->db->select('table_telaah.*, table_pegawai.*, table_skpd.*');
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
		$this->db->like($column,$value);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## KASUBAG CAMAT / LURAH
	public function kasubag_lurah($limit, $start, $skpd_id) {
		$this->db->select('table_telaah.*, table_pegawai.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline7','table_telaah.telaah_id=table_timeline7.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->where('table_telaah.telaah_kategori',7);
		$this->db->where('table_timeline7.timeline_lurah_id',0);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function kasubag_lurah_search($column, $value, $limit, $start, $skpd_id) {
		$this->db->select('table_telaah.*, table_pegawai.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline7','table_telaah.telaah_id=table_timeline7.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->where('table_telaah.telaah_kategori',7);
		$this->db->where('table_timeline7.timeline_lurah_id',0);
		$this->db->like($column,$value);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## KABID DINKES
	public function kabid_dinkes($limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		// $this->db->where('table_telaah.telaah_kategori',1);
		$this->db->where("(table_skpd.jenis_skpd = 7 OR table_skpd.jenis_skpd = 10)");
		$this->db->where('table_timeline1.timeline_kabid_id',0);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function kabid_dinkes_search($column, $value, $limit, $start) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id','left');
		$this->db->join('table_timeline1','table_telaah.telaah_id=table_timeline1.telaah_id','left');
		// $this->db->where('table_telaah.telaah_kategori',1);
		$this->db->where("(table_skpd.jenis_skpd = 7 OR table_skpd.jenis_skpd = 10)");
		$this->db->where('table_timeline1.timeline_kabid_id',0);
		$this->db->like($column,$value);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## SEKDIS DINKES
	public function sekdis_dinkes($limit, $start) {
		$this->db->select('*, table_telaah.telaah_id');
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
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function sekdis_dinkes_search($column, $value, $limit, $start) {
		$this->db->select('*, table_telaah.telaah_id');
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
		$this->db->like($column,$value);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## KADIS DINKES
	public function kadis_dinkes($limit, $start) {
		$this->db->select('*, table_telaah.telaah_id');
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
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	public function kadis_dinkes_search($column, $value, $limit, $start) {
		$this->db->select('*, table_telaah.telaah_id');
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
		$this->db->like($column,$value);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	## KAPUS
	public function kapus($limit, $start, $skpd_id) {
		$this->db->select('table_telaah.*, table_pegawai.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline11','table_telaah.telaah_id=table_timeline11.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori', 11);
		$this->db->where('table_timeline11.timeline_kapus_id', 0);
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	
	public function kapus_search($column, $value, $limit, $start, $skpd_id) {
		$this->db->select('table_telaah.*, table_pegawai.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_timeline11','table_telaah.telaah_id=table_timeline11.telaah_id','left');
		$this->db->join('table_skpd','table_pegawai.skpd_id=table_skpd.skpd_id','left');
		$this->db->where('table_telaah.telaah_kategori', 11);
		$this->db->where('table_timeline11.timeline_kapus_id', 0);
		$this->db->where('table_pegawai.skpd_id',$skpd_id);
		$this->db->like($column,$value);
		$this->db->order_by('table_telaah.telaah_waktuinput','DESC');
		if($limit){
			$this->db->limit ($limit, $start);
			$query = $this->db->get ();
			return $query->result();
		} else {
			return $this->db->count_all_results(); 
		}
	}
	
	
	///====================================================
	public function get($telaah_id) {
		$this->db->select('*, telaah_skpd_id as skpd, table_telaah.telaah_id as telaah_id');
		$this->db->from('table_telaah');
		$this->db->join('table_provinsi','table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');
		$this->db->join('table_kabkot','table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id=table_telaah.telaah_pelaksana', 'LEFT');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');
		$this->db->join('table_tanggal_perjalanan','table_tanggal_perjalanan.telaah_id=table_telaah.telaah_id', 'LEFT');
		$this->db->join('table_anggaran','table_anggaran.id_anggaran=table_telaah.telaah_kegiatan', 'LEFT');
		$this->db->where('table_telaah.telaah_id', $telaah_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function get_sekwan($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_provinsi','table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');
		$this->db->join('table_kabkot','table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');
		$this->db->join('table_anggotadprd','table_anggotadprd.anggotadprd_id=table_telaah.telaah_pelaksana', 'LEFT');
		//$this->db->join('table_pegawai','table_pegawai.pegawai_id=table_telaah.telaah_pelaksana', 'LEFT');
		$this->db->join('table_anggaran','table_anggaran.id_anggaran=table_telaah.telaah_kegiatan', 'LEFT');
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	public function getWalikota($telaah_id) {
		$this->db->select('*, telaah_skpd_id as skpd, table_telaah.telaah_id as telaah_id');
		$this->db->from('table_telaah');
		$this->db->join('table_provinsi','table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');
		$this->db->join('table_kabkot','table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');
		$this->db->join('table_pimpinan','table_pimpinan.pegawai_id=table_telaah.telaah_pelaksana', 'LEFT');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pimpinan.skpd_id', 'LEFT');
		$this->db->join('table_anggaran','table_anggaran.id_anggaran=table_telaah.telaah_kegiatan', 'LEFT');
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function get_dprd($telaah_id) {
		$this->db->select('*, anggotadprd_name as pegawai_nama, anggotadprd_id as pegawai_id, 
							anggotadprd_jabatan as pegawai_namajabatan, telaah_skpd_id as skpd, 
							telaah_jenis_skpd as jenis_skpd, table_telaah.telaah_id as telaah_id');
		$this->db->from('table_telaah');
		$this->db->join('table_provinsi','table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');
		$this->db->join('table_kabkot','table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');
		$this->db->join('table_anggotadprd','table_anggotadprd.anggotadprd_id=table_telaah.telaah_pelaksana', 'LEFT');
		$this->db->join('table_anggaran','table_anggaran.id_anggaran=table_telaah.telaah_kegiatan', 'LEFT');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_telaah.telaah_skpd_id', 'LEFT');
		$this->db->where('telaah_id', $telaah_id);
		$this->db->where('telaah_skpd_id', 2);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function getLast() {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->order_by('telaah_id','DESC');
		$this->db->limit (1, 0);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function create($data) {
		
		$this->db->insert('table_telaah', $data);
	}
	
	public function update($data) {
		$this->db->update('table_telaah', $data, array('telaah_id'=>$data['telaah_id']));
	}
	
	public function update_perbaikan($table,$telaah_id,$posisi,$text) {
		$this->db->set($posisi, 0);
		$this->db->where($posisi, 5);
		$this->db->where('telaah_id', $telaah_id);
		$this->db->update($table);
	}
	
	public function pegawai($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->where('skpd_id', $skpd_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function pegawai2() {
		$this->db->select('*');
		$this->db->from('table_pegawai');
		$this->db->where('skpd_id !=2');
		$this->db->where('skpd_id !=3');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function pegawai3($skpd_id) {
		$this->db->select('pegawai_id, pegawai_nip, pegawai_nama');
		$this->db->from('table_pegawai');
		$this->db->where('skpd_id', $skpd_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function getTimeline1($telaah_id) {
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get('table_timeline1', 1);
		return $query->result_array();
	}
	
	public function getTimeline2($telaah_id) {
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get('table_timeline2', 1);
		return $query->result_array();
	}
	
	public function getTimeline3($telaah_id) {
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get('table_timeline3', 1);
		return $query->result_array();
	}
	
	public function getTimeline4($telaah_id) {
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get('table_timeline4', 1);
		return $query->result_array();
	}
	
	public function getTimeline5($telaah_id) {
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get('table_timeline5', 1);
		return $query->result_array();
	}
	
	public function getTimeline6($telaah_id) {
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get('table_timeline6', 1);
		return $query->result_array();
	}
	
	public function getTimeline7($telaah_id) {
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get('table_timeline7', 1);
		return $query->result_array();
	}
	public function getTimeline8($telaah_id) {
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get('table_timeline8', 1);
		return $query->result_array();
	}
	public function getTimeline9($telaah_id) {
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get('table_timeline9', 1);
		return $query->result_array();
	}
	
	public function getTimeline10($telaah_id) {
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get('table_timeline10', 1);
		return $query->result_array();
	}
	
	public function getTimeline11($telaah_id) {
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get('table_timeline11', 1);
		return $query->result_array();
	}
	
	public function anggaran($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_anggaran');
		$this->db->where('skpd_id',$skpd_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function rekening($skpd_id) {
		$this->db->select('*');
		$this->db->from('table_rekening');
		$this->db->where('skpd_id',$skpd_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_provinsi() {
		$this->db->select('*');
		$this->db->from('table_provinsi');
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kabupaten($provinsi_id) {
		$this->db->select('*');
		$this->db->from('table_kabkot');
		$this->db->where('provinsi_id',$provinsi_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kecamatan($kabkot_id) {
		$this->db->select('*');
		$this->db->from('table_kecamatan');
		$this->db->join('table_kabkot','table_kecamatan.kabkot_id = table_kabkot.kabkot_id', 'LEFT');
		$this->db->join('table_provinsi','table_kabkot.provinsi_id = table_provinsi.provinsi_id', 'LEFT');
		$this->db->where('table_kabkot.kabkot_id',$kabkot_id);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kecamatan_ddak() {
		$this->db->select('*');
		$this->db->from('table_kecamatan');
		$this->db->join('table_kabkot','table_kecamatan.kabkot_id = table_kabkot.kabkot_id', 'LEFT');
		$this->db->join('table_provinsi','table_kabkot.provinsi_id = table_provinsi.provinsi_id', 'LEFT');
		$this->db->where('table_provinsi.provinsi_id',74);
		$this->db->where('table_kabkot.kabkot_id',7471);
		$query = $this->db->get ();
		return $query->result();
	}
	
	public function get_kecamatan_dddk() {
		$this->db->select('*');
		$this->db->from('table_kecamatan');
		$this->db->where('kec_id',747101);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function get_kabkot($provinsi_id){
		$this->db->where('provinsi_id',$provinsi_id);
		$this->db->order_by('kabupaten_kota','asc');
		$kelurahan=$this->db->get('table_kabkot');
		if($kelurahan->num_rows()>0){
			foreach ($kelurahan->result_array() as $row)
			{
				$result['']= '- Pilih Kabupaten/Kota -';
				$result[$row['kabkot_id']]= $row['kabupaten_kota'];
			}
		} else {
			$result['']= '- Belum Ada Kabupaten/Kota -';
		}
		return $result;
	}
	
	public function get_kec($kabkot_id){
		$this->db->where('kabkot_id',$kabkot_id);
		$this->db->order_by('kecamatan','asc');
		$kelurahan=$this->db->get('table_kecamatan');
		if($kelurahan->num_rows()>0){
			foreach ($kelurahan->result_array() as $row)
			{
				$result['']= '- Pilih Kecamatan -';
				$result[$row['kec_id']]= $row['kecamatan'];
			}
		} else {
			$result['']= '- Belum Ada Kecamatan -';
		}
		return $result;
	}
	
	public function count_rincian_biaya($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_rincian_biaya');
		$this->db->where('telaah_id', $telaah_id);
		return $this->db->count_all_results(); 
	}
	
	public function count_pengeluaran_rill($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_pengeluaran_rill');
		$this->db->where('telaah_id', $telaah_id);
		return $this->db->count_all_results(); 
	}
	
	## COUNT LAPORAN PERJALANAN
	public function count_laporan_perjalanan($telaah_id) {
		$this->db->from('table_laporanperjalanan');	
		$this->db->where('telaah_id',$telaah_id);
		return $this->db->count_all_results();
	}
	
	## COUNT PERJALANAN
	public function laporan_perjalanan($telaah_id) {
		$this->db->from('table_laporanperjalanan');	
		$this->db->where('telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	## COUNT PERJALANAN
	public function count_laporan_perjalanan_pegawai($pegawai_id) {
		$this->db->select('table_laporanperjalanan.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_laporanperjalanan','table_laporanperjalanan.telaah_id=table_telaah.telaah_id','left');
		$this->db->where('pegawai_id',$pegawai_id);
		$this->db->group_start();
		$this->db->where('status_laporan',0);
		$this->db->or_where('status_laporan IS NULL');
		$this->db->group_end();
		return $this->db->count_all_results(); 
	}
	
	## COUNT PERJALANAN
	public function laporan_perjalanan_pegawai($pegawai_id) {
		$this->db->select('table_pegawai.*, table_laporanperjalanan.*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','left');
		$this->db->join('table_laporanperjalanan','table_laporanperjalanan.telaah_id=table_telaah.telaah_id','left');
		$this->db->where('pegawai_id',$pegawai_id);
		$this->db->group_start();
		$this->db->where('status_laporan',0);
		$this->db->or_where('status_laporan IS NULL');
		$this->db->group_end();
		$query = $this->db->get ();
		return $query->result_array();
	}
	
}