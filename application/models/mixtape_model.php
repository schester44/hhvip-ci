<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mixtape_model extends CI_Model {
		
	public function __construct(){
		parent::__construct();
	}

	public function add_mixtape($data) {
		$this->db->insert('mixtapes', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	public function add_track($data) {
		$this->db->insert('mixtape_tracks', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	public function get_mixtape($id) {

		$this->db->select('mixtapes.*, users.username')
			->from('mixtapes')
			->where(array('mixtapes.id'=>$id))
			->join('users','users.id = mixtapes.user_id');


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

	public function get_mixtapes($where, $order="", $limit="", $offset="") {

		$this->db->select('mixtapes.*, users.username')
			->from('mixtapes')
			->where($where)
			->join('users','users.id = mixtapes.user_id');


		if (!empty($limit)) {
			$this->db->limit($limit, $offset);
		}

		if (!empty($order)) {
			$this->db->order_by($order);
		}

		$query = $this->db->get();

		return $query->result();
	}

	public function update_mixtape($where, $data) {
		$this->db->where($where)->update('mixtapes',$data);
		$updated = $this->db->affected_rows();
		return $updated;
	}

	public function update_track($where, $data) {
		$this->db->where($where)->update('mixtape_tracks',$data);
		$updated = $this->db->affected_rows();
		return $updated;
	}

	public function get_tracks($where, $order="",$limit="",$offset="") {

		$this->db->select('mixtape_tracks.*, users.username')
			->from('mixtape_tracks')
			->where($where)
			->join('users','users.id = mixtape_tracks.user_id');


		if (!empty($limit)) {
			$this->db->limit($limit, $offset);
		}

		if (!empty($order)) {
			$this->db->order_by($order);
		}

		$query = $this->db->get();

		return $query->result();
	}

	/*universal function name (used for songs, and mixtapes) in Sorting library*/
	public function get_list($where, $order, $limit="", $start=""){

		$this->db->select('mixtapes.id,user_id,tape_artist,tape_title,upload_date,published_date,tape_url,tape_description,tape_image,status,can_download,promoted,upvotes,downvotes,hotness,username')
			->from('mixtapes')
			->where($where)
			->order_by($order);
		$this->db->join('users', 'users.id = mixtapes.user_id');
		
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

	public function delete_mixtape($where) {
		$this->db->delete('mixtapes', $where);
		$affected = $this->db->affected_rows();
		if ($affected) {
			return true;
		} else {
			return false;
		}
	}

	public function delete_track($where) {
		$this->db->delete('mixtape_tracks', $where);
		$affected = $this->db->affected_rows();
		if ($affected) {
			return true;
		} else {
			return false;
		}
	}

	public function mixtape_count($where) {
	 	$this->db->where($where);
        return $this->db->count_all_results("mixtapes");
    }

}

