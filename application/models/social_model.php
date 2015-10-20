<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Social_model extends CI_Model {
		
	public function __construct(){
		parent::__construct();
	}

	public function get_favorite($where){
		$query = $this->db->get_where('user_favorites',$where);

			if ($query->num_rows > 0) {
				foreach ($query->result() as $row) {
					return $row;
				}
			} else {
				return false;
			}
	}

	public function get_favorites($where, $order = NULL, $limit = NULL, $offset = NULL) {
    	if (!empty($limit)) {
    		$this->db->limit($limit, $offset);
	    }
	    if (!empty($order)) {
	    	$this->db->order_by($order);
	    }
	$this->db->select('*')
		->from('user_favorites')
		->join('songs', 'songs.song_id = user_favorites.song_id')
		->join('users', 'users.id = songs.user_id')
		->where($where);

	$query = $this->db->get();
	return $query->result();
    }


	public function add_favorite($data) {
		$this->db->insert('user_favorites', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	public function delete_favorite($where) {
		$this->db->delete('user_favorites', $where);
		$affected = $this->db->affected_rows();
		if ($affected) {
			return true;
		} else {
			return false;
		}
	}

	public function get_followers($where, $order = NULL, $limit = NULL, $offset = NULL) {
    	if (!empty($limit)) {
    		$this->db->limit($limit, $offset);
	    }
	    if (!empty($order)) {
	    	$this->db->order_by($order);
	    }
	$this->db->select('*')
		->from('user_follows')
		->join('users', 'users.id = user_follows.follower_id')
		->where($where);
	$query = $this->db->get();
	return $query->result();
    }

    public function get_following($where, $order = NULL, $limit = NULL, $offset = NULL) {
    	if (!empty($limit)) {
    		$this->db->limit($limit, $offset);
	    }
	    if (!empty($order)) {
	    	$this->db->order_by($order);
	    }
	$this->db->select('*')
		->from('user_follows')
		->join('users', 'users.id = user_follows.following_id')
		->where($where);
	$query = $this->db->get();
	return $query->result();
    }

	public function get_follow($where){
		$query = $this->db->get_where('user_follows',$where);

			if ($query->num_rows > 0) {
				foreach ($query->result() as $row) {
					return $row;
				}
			} else {
				return false;
			}
	}

	public function add_follow($data) {
		$this->db->insert('user_follows', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	public function delete_follow($where) {
		$this->db->delete('user_follows', $where);
		$affected = $this->db->affected_rows();
		if ($affected) {
			return true;
		} else {
			return false;
		}
	}

	public function follow_count($where) {
	 	$this->db->where($where);
        return $this->db->count_all_results("user_follows");
    }

	public function favorites_count($where) {
	 	$this->db->where($where);
        return $this->db->count_all_results("user_favorites");
    }
}

