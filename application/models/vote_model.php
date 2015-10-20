<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vote_model extends CI_Model {
		
	public function __construct(){
		parent::__construct();
	}

	public function get_where_in($select, $where, $id) {

		$this->db->select($select)
			->from('songs_postvotes')
			->where(array('vote_user_id'=>$id))
			->where_in('vote_song_id', $where);

		$query = $this->db->get();

		if (!$query->result()) {
			return false;
		} else {
			return $query->result();
		}
	}

	public function get($select, $where, $order = NULL, $limit = NULL, $offset = 0) {
		if ($order) {
			$this->db->order_by($order);
		}
		if ($limit) {
			$this->db->limit($limit, $offset);
		}

		$this->db->select($select)
			->from('songs_postvotes')
			->where($where);

		$query = $this->db->get();

		if (!$query->result()) {
			return false;
		} else {
			return $query->result();
		}
	}
	
	public function get_vote($select, $where, $order="",$limit=""){
	
		if ($order != '') {
			$this->db->order_by($order);
		}
		if ($limit != '') {
			$this->db->limit($limit, $offset);
		}

		$this->db->select($select)
			->from('songs_postvotes')
			->where($where);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row)
			{
			    return $row;
			}
		} else {
			return false;
		}	

	}


	public function get_vote_rating($select,$where,$order="",$limit="") {

		$this->db->select($select);
		if ($order != '') {
			$this->db->order_by($order);
		}
		if ($limit != '') {
			$this->db->limit($limit);
		}

		$query	= $this->db->get_where('songs_postvotes',$where);
		
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				return $row->vote_rating;
			} 
		} else {
				return false;
			}
	}

	public function get_votes($select, $where) {
		$this->db->select($select);
		$query	= $this->db->get_where('songs',$where);

		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row)
			{
			    return $row;
			}
		} else {
			return false;
		}
	}

	public function add_vote($table, $data) {
		$this->db->set($data);
		$this->db->insert($table);
		$updated = $this->db->affected_rows();
	}

	public function update_vote($table, $data, $where) {
		$this->db->set($data,NULL,FALSE);
		$this->db->where($where)->update($table);
		$updated = $this->db->affected_rows();
	}

	public function remove_vote($table, $where) {
		$this->db->delete($table, $where);
		$affected = $this->db->affected_rows();
	}

	
}