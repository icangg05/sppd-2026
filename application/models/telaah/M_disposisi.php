<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_disposisi extends CI_Model
{	
	public function get($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_telaah');
		$this->db->join('table_pegawai','table_telaah.telaah_pelaksana=table_pegawai.pegawai_id','LEFT');
		$this->db->join('table_provinsi','table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');
		$this->db->join('table_kabkot','table_kabkot.kabkot_id=table_telaah.telaah_kotatujuan', 'LEFT');
		$this->db->join('table_skpd','table_skpd.skpd_id=table_pegawai.skpd_id', 'LEFT');
		$this->db->join('table_anggaran','table_anggaran.id_anggaran=table_telaah.telaah_kegiatan', 'LEFT');
		$this->db->join('table_golongan','table_golongan.golongan=table_pegawai.pegawai_golongan', 'LEFT');
		$this->db->where('telaah_id', $telaah_id);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function get_pengikut($telaah_id) {
		$this->db->select('*');
		$this->db->from('table_pengikut');
		$this->db->join('table_telaah','table_telaah.telaah_id=table_pengikut.telaah_id', 'LEFT');
		$this->db->join('table_pegawai','table_pegawai.pegawai_id=table_pengikut.pegawai_id', 'LEFT');
		$this->db->join('table_provinsi','table_provinsi.provinsi_id=table_telaah.telaah_provinsitujuan', 'LEFT');
		$this->db->where('table_pengikut.telaah_id',$telaah_id);
		$query = $this->db->get ();
		return $query->result_array();
	}
	
	public function getTimeline1($id) {
		$this->db->where('telaah_id', $id);
		$query = $this->db->get('table_timeline1', 1);
		return $query->result_array();
	}
	
	public function getTimeline2($id) {
		$this->db->where('telaah_id', $id);
		$query = $this->db->get('table_timeline2', 1);
		return $query->result_array();
	}
	
	public function getTimeline3($id) {
		$this->db->where('telaah_id', $id);
		$query = $this->db->get('table_timeline3', 1);
		return $query->result_array();
	}
	
	public function getTimeline4($id) {
		$this->db->where('telaah_id', $id);
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
	
	public function update_timeline_1($data,$data2) {
		if($data2){
			$this->db->trans_begin();
			$this->db->update('table_timeline1', $data, array('telaah_id'=>$data['telaah_id']));
			$this->db->update('table_telaah', $data2, array('telaah_id'=>$data2['telaah_id']));
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
		} else {	
			$this->db->update('table_timeline1', $data, array('telaah_id'=>$data['telaah_id']));
		}
	}
	
	public function update_timeline_2($data,$data2) {
		if($data2){
			$this->db->trans_begin();
			$this->db->update('table_timeline2', $data, array('telaah_id'=>$data['telaah_id']));
			$this->db->update('table_telaah', $data2, array('telaah_id'=>$data2['telaah_id']));
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
		} else {	
			$this->db->update('table_timeline2', $data, array('telaah_id'=>$data['telaah_id']));
		}
	}
	
	public function update_timeline_3($data,$data2) {
		if($data2){
			$this->db->trans_begin();
			$this->db->update('table_timeline3', $data, array('telaah_id'=>$data['telaah_id']));
			$this->db->update('table_telaah', $data2, array('telaah_id'=>$data2['telaah_id']));
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
		} else {	
			$this->db->update('table_timeline3', $data, array('telaah_id'=>$data['telaah_id']));
		}
	}
	
	public function update_timeline_4($data,$data2) {
		if($data2){
			$this->db->trans_begin();
			$this->db->update('table_timeline4', $data, array('telaah_id'=>$data['telaah_id']));
			$this->db->update('table_telaah', $data2, array('telaah_id'=>$data2['telaah_id']));
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
		} else {	
			$this->db->update('table_timeline4', $data, array('telaah_id'=>$data['telaah_id']));
		}
	}
	
	public function update_timeline_5($data,$data2) {
		if($data2){
			$this->db->trans_begin();
			$this->db->update('table_timeline5', $data, array('telaah_id'=>$data['telaah_id']));
			$this->db->update('table_telaah', $data2, array('telaah_id'=>$data2['telaah_id']));
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
		} else {	
			$this->db->update('table_timeline5', $data, array('telaah_id'=>$data['telaah_id']));
		}
	}
	
	public function update_timeline_6($data,$data2) {
		if($data2){
			$this->db->trans_begin();
			$this->db->update('table_timeline6', $data, array('telaah_id'=>$data['telaah_id']));
			$this->db->update('table_telaah', $data2, array('telaah_id'=>$data2['telaah_id']));
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
		} else {	
			$this->db->update('table_timeline6', $data, array('telaah_id'=>$data['telaah_id']));
		}
	}
	
	public function update_timeline_7($data,$data2) {
		if($data2){
			$this->db->trans_begin();
			$this->db->update('table_timeline7', $data, array('telaah_id'=>$data['telaah_id']));
			$this->db->update('table_telaah', $data2, array('telaah_id'=>$data2['telaah_id']));
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
		} else {	
			$this->db->update('table_timeline7', $data, array('telaah_id'=>$data['telaah_id']));
		}
	}
	
	public function update_timeline_8($data,$data2) {
		if($data2){
			$this->db->trans_begin();
			$this->db->update('table_timeline8', $data, array('telaah_id'=>$data['telaah_id']));
			$this->db->update('table_telaah', $data2, array('telaah_id'=>$data2['telaah_id']));
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
		} else {	
			$this->db->update('table_timeline8', $data, array('telaah_id'=>$data['telaah_id']));
		}
	}
	
	public function update_timeline_9($data,$data2) {
		if($data2){
			$this->db->trans_begin();
			$this->db->update('table_timeline9', $data, array('telaah_id'=>$data['telaah_id']));
			$this->db->update('table_telaah', $data2, array('telaah_id'=>$data2['telaah_id']));
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
		} else {	
			$this->db->update('table_timeline9', $data, array('telaah_id'=>$data['telaah_id']));
		}
	}
	
	public function update_timeline_10($data,$data2) {
		if($data2){
			$this->db->trans_begin();
			$this->db->update('table_timeline10', $data, array('telaah_id'=>$data['telaah_id']));
			$this->db->update('table_telaah', $data2, array('telaah_id'=>$data2['telaah_id']));
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
		} else {	
			$this->db->update('table_timeline10', $data, array('telaah_id'=>$data['telaah_id']));
		}
	}
	
	public function update_timeline_11($data,$data2) {
		if($data2){
			$this->db->trans_begin();
			$this->db->update('table_timeline11', $data, array('telaah_id'=>$data['telaah_id']));
			$this->db->update('table_telaah', $data2, array('telaah_id'=>$data2['telaah_id']));
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}
		} else {	
			$this->db->update('table_timeline11', $data, array('telaah_id'=>$data['telaah_id']));
		}
	}
	
	public function kuasakan($data) {
		$this->db->insert('table_tte', $data);
	}
	
}