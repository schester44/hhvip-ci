<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Song_model extends CI_Model {
		
	public function __construct(){
		parent::__construct();
	}


	public function get($where, $order = NULL, $limit = NULL, $offset = 0) {
		if ($order) {
			$this->db->order_by($order);
		}
		if ($limit) {
			$this->db->limit($limit, $offset);
		}

		$this->db->select('songs.*,users.username, users.twitter_handle')
			->from('songs')
			->where($where)
			->join('users', 'users.id = songs.user_id');

		$query = $this->db->get();

		if (!$query->result()) {
			return false;
		} else {
			return $query->result();
		}
	}

/**
 * getUpdatedUrl - if the user has changed a published song URL, getUpdatedUrl will fetch the updated URL which is used to redirect to the correct page. 
 */
	public function getUpdatedUrl($where) {
		$this->db->select('url_changes.*, users.username')
			->from('url_changes')
			->where($where)
			->join('songs', 'songs.song_id = url_changes.song_id')
			->join('users', 'users.id = songs.user_id')
			->order_by('id DESC')
			->limit(1, 0);

		$query = $this->db->get();

		if (!$query->result()) {
			return false;
		} else {
			// returning the first object inside the array
			return $query->result()[0];
		}
	}

	public function addUpdatedUrl($data){
		$this->db->insert('url_changes', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	public function add($data) {
		$this->db->insert('songs', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	public function update($where, $data) {
		$this->db->where($where)->update('songs',$data);
		$updated = $this->db->affected_rows();
		return $updated;
	}

	public function get_songs_where($where, $limit, $order="", $offset=NULL) {
		if ($order != "") {
			$this->db->order_by($order);
		}

		$this->db->select('songs.*,users.username, users.twitter_handle')
			->from('songs')
			->where($where)
			->join('users', 'users.id = songs.user_id')
			->limit($limit,$offset);

		$query = $this->db->get();

		if (!$query->result()) {
			return false;
		} else {
			return $query->result();
		}
	}

	public function get_song_where($where){
		$this->db->select('songs.*, users.id, users.username, users.twitter_handle')
			->from('songs')
			->where($where)
			->join('users', 'users.id = songs.user_id')
			->limit('1');


		$query = $this->db->get();

			if ($query->num_rows > 0) {
				foreach ($query->result() as $row) {
					return $row;
				}
			} else {
				return false;
			}
	}

 /* get single song based on its song id */
	public function get_song($song_id) {

		$this->db->select('songs.*, users.username, users.twitter_handle')
			->from('songs')
			->where(array('song_id'=>$song_id))
			->join('users', 'users.id = songs.user_id');

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

	public function get_all_songs($where, $order="", $limit="", $offset="") {

		$this->db->select('songs.*, users.username, users.twitter_handle')
			->from('songs')
			->where($where)
			->join('users','users.id = songs.user_id');

			if (!empty($limit)) {
				$this->db->limit($limit, $offset);
			}

			if (!empty($order)) {
				$this->db->order_by($order);
			}

		$query = $this->db->get();
		return $query->result();
	}


	 /* get the songs list so we know what type of query to do */
	public function get_list($where, $order, $limit="", $start=""){


		$this->db->select('songs.*,users.id,users.username')
			->from('songs')
			->where($where)
			->order_by($order)
			->join('users', 'users.id = songs.user_id');

		if ($limit != '') {
			if ($start != '') {
				$start = $start;
			} else {
				$start = NULL;
			}
			
			$this->db->limit($limit, $start);
		}

		$query = $this->db->get();
		
		if ($query->num_rows > 0) {
			return $query->result();
		}
	}


	public function song_count($where) {
	 	$this->db->where($where);
        return $this->db->count_all_results("songs");
    }

 /* checks if a valid song exists based on the file_uid, user_id and filename. used during the upload/finish process */
	public function valid_song_exists($where) {
		
		$this->db->select('songs.*, users.username')
			->from('songs')
			->where($where)
			->join('users','users.id = songs.user_id');
			
		$query = $this->db->get();

		if ($query->num_rows() > 0){
	        return true;
	    }
	    else{
	        return false;
	    }
	}

	public function delete_song($where) {
		$this->db->delete('songs', $where);
		$affected = $this->db->affected_rows();
		if ($affected) {
			return true;
		} else {
			return false;
		}
	}

/***********
* REMOVES SONG FROM WEBSITE BY CHANGING STATUS TO $REASON
* DOES NOT DELETE THE SONG FROM THE DATABASE
*/
	 public function remove_song($where, $reason) {

		$this->db->where($where)->update('songs', $reason); 
		$updated = $this->db->affected_rows();

		if ($updated) {
		return true;
		} else {
			return false;
		}

	}

	/**
	 * main Song search query
	 * @param  array $where   - an array of search params (status => published)
	 * @param  string $search     search term
	 * @param  string $limit      how many search results to display
	 * @param  string $offset     offset by how many
	 * @param  string $order      how should we order them?
	 * @param  [type] $description do we want to display a description? if so, set $description to anything other than NULL
	 * @return array of results
	 */
	 public function search($where, $search, $limit='', $offset='', $order='', $description = NULL) {
		//where `` = "" AND `` LIKE ""
	 	$this->db->select('songs.*,users.username');
	 	$this->db->from('songs');
		$this->db->having($where);
		$this->db->like('song_title', $search);
		$this->db->or_like('song_artist', $search);
		$this->db->or_like('featuring', $search);

		// search the description field only when we want to.. 
		// mainly used to exclude remixes & other tracks that only have search term in description from playlists
		// 
		if ($description === NULL) {
			$this->db->or_like('song_description', $search);
		}

		$this->db->or_like('song_producer', $search);
		$this->db->join('users','users.id = songs.user_id');

		if ($limit != '') {
			$this->db->limit($limit, $offset);
		}

		if ($order != '') {
			$this->db->order_by($order);
		}

		$query = $this->db->get();
		return $query->result();

	}

	public function countSearch($where, $search) {
		//where `` = "" AND `` LIKE ""
		//
		$this->db->having($where);		
		$this->db->like('song_title', $search);
		$this->db->or_like('song_artist', $search);
		$this->db->or_like('featuring', $search);
		$this->db->or_like('song_description', $search);
		$this->db->or_like('song_producer', $search);
		$result = $this->db->get('songs');
		return $result->num_rows;
	}
 
 /* --- PLAYLISTS --- */

 	public function get_playlist($where) {
		$this->db->select('*')
			->from('playlists')
			->where($where);

		$query = $this->db->get();

		if (!$query->result()) {
			return false;
		} else {
			return $query->result();
		}
 	}

 	public function get_playlist_tracks($where) {
		$this->db->select('playlist_tracks.*, songs.*')
			->from('playlist_tracks')
			->where($where)
			->join('songs', 'songs.song_id = playlist_tracks.song_id');


		$query = $this->db->get();

		if (!$query->result()) {
			return false;
		} else {
			return $query->result();
		}

 	}

	public function add_playlist($data) {
		$this->db->insert('playlists', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	public function delete_playlist($where) {
		$this->db->delete('playlists', $where);
		$affected = $this->db->affected_rows();
		if ($affected) {
			return true;
		} else {
			return false;
		}
	}

	public function update_playlist($where, $data) {
		$this->db->where($where)->update('playlists',$data);
		$updated = $this->db->affected_rows();
		return $updated;
	}

	public function add_playlist_track($data) {
		$this->db->insert('playlist_tracks', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	public function delete_playlist_track($where) {
		$this->db->delete('playlist_tracks', $where);
		$affected = $this->db->affected_rows();
		if ($affected) {
			return true;
		} else {
			return false;
		}
	}

	public function update_playlist_track($where, $data) {
		$this->db->where($where)->update('playlist_tracks',$data);
		$updated = $this->db->affected_rows();
		return $updated;
	}

}