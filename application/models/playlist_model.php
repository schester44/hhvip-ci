<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Playlist_model extends CI_Model {
		
	public function __construct(){
		parent::__construct();
	}

 	public function get($where, $order = NULL, $limit = NULL, $offset = NULL) {
		$this->db->select('playlists.*, users.username')
			->from('playlists')
			->where($where)
			->join('users', 'users.id = playlists.user_id');

		if (!empty($order)) {
	    	$this->db->order_by($order);
	    }


		if (!empty($limit)) {
			$this->db->limit($limit, $offset);
		}
		
		$query = $this->db->get();

		if (!$query->result()) {
			return false;
		} else {
			return $query->result();
		}
 	}

	public function add($data) {
		$this->db->insert('playlists', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	public function delete($where) {
		$this->db->delete('playlists', $where);
		$affected = $this->db->affected_rows();
		if ($affected) {
			return true;
		} else {
			return false;
		}
	}

	public function update($where, $data) {
		$this->db->where($where)->update('playlists',$data);
		$updated = $this->db->affected_rows();
		return $updated;
	}

	/* ---- TRACKS ---- */


	public function get_tracks($where, $order = NULL) {
		
		if (!empty($order)) {
	    	$this->db->order_by($order);
	    }

		$this->db->select('playlist_tracks.*, songs.*, users.username')
			->from('playlist_tracks')
			->where($where)
			->join('songs', 'songs.song_id = playlist_tracks.song_id')
			->join('users', 'users.id = songs.user_id');


		$query = $this->db->get();

		if (!$query->result()) {
			return false;
		} else {
			return $query->result();
		}

 	}

	public function add_track($data) {
		$this->db->insert('playlist_tracks', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	public function delete_track($where) {
		$this->db->delete('playlist_tracks', $where);
		$affected = $this->db->affected_rows();
		if ($affected) {
			return true;
		} else {
			return false;
		}
	}

	public function update_track($where, $data) {
		$this->db->where($where)->update('playlist_tracks',$data);
		$updated = $this->db->affected_rows();
		return $updated;
	}


		public function count($where) {
	 	$this->db->where($where);
        return $this->db->count_all_results("playlists");
    }

    	public function count_tracks($where) {
	 	$this->db->where($where);
        return $this->db->count_all_results("playlist_tracks");
    }

    public function update_track_count($where, $data) {
		$this->db->set($data,NULL,FALSE);
		$this->db->where($where)->update('playlists');
		$updated = $this->db->affected_rows();
	}

}