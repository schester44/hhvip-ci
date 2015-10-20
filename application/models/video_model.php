<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Video_model extends CI_Model {
		
	public function __construct(){
		parent::__construct();
	}

	public function get_video($id) {

		$this->db->select('videos.*, users.username')
			->from('users')
			->where(array('videos.id'=>$id))
			->join('users','users.id = videos.user_id');

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

	public function get_videos($where, $order="", $limit="", $offset="") {
		
		$this->db->select('videos.*, users.username')
			->from('videos')
			->where($where)
			->join('users','users.id = videos.user_id');

		if (!empty($limit)) {
			$this->db->limit($limit, $offset);
		}

		if (!empty($order)) {
			$this->db->order_by($order);
		}

		$query = $this->db->get();
		
		return $query->result();
	}

	public function video_count($where) {
	 	$this->db->where($where);
        return $this->db->count_all_results("videos");
    }

    public function add_video($data) {
    	$this->db->insert('videos', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
    }

	public function update_video($where, $data) {
		$this->db->where($where)->update('videos',$data);
		$updated = $this->db->affected_rows();
		return $updated;
	}

	public function delete_video($where) {
		$this->db->delete('videos', $where);
		$affected = $this->db->affected_rows();
		if ($affected) {
			return true;
		} else {
			return false;
		}
	}
}

