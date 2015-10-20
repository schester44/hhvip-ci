<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {
		
	public function __construct(){
		parent::__construct();
	}

 /* get user information based on their username */
	public function get_user_info($username = '') {
		if (empty($username)) {
			return false;
		}
		 $query = $this->db->query("SELECT id,username,email,company,twitter_handle,website,bio,location FROM users WHERE username = '$username'");

			foreach ($query->result() as $row)
			{
			    return $row;
			}
	}

 /* get user info based on their id */

	public function get_user($id) {
		if (empty($id)) {
			return false;
		}
		
		$query = $this->db->get_where('users',array('id'=>$id));
		$this->db->limit('1');

		if ($query->num_rows > 0 ) {
			foreach ($query->result() as $row)
			{
			    return $row;
			}
		}
			
	}

	public function get_all_users($where, $order="", $limit="", $offset="") {
			if (!empty($limit)) {
				$this->db->limit($limit, $offset);
			}

			if (!empty($order)) {
				$this->db->order_by($order);
			}

		$query = $this->db->get_where('users',$where);
		return $query->result();
	}

	public function user_count($where) {
	 	$this->db->where($where);
        return $this->db->count_all_results("users");
    }

	public function update_account($user_id, $data) {

		$where = array('id'=>$user_id);
		$this->db->where($where)->update('users',$data);
		$updated = $this->db->affected_rows();

		if ($updated > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function get_user_stats($where, $type, $order = null, $limit = null) {

		$plays = $this->db->query(sprintf("SELECT `views`.`track`,`tracks`.`title`,`tracks`.`art`, COUNT(`by`) as `count` FROM `views`,`tracks` WHERE `views`.`track` IN (%s) AND `views`.`track` = `tracks`.`id` AND DATE_SUB(CURDATE(),INTERVAL %s DAY) <= date(`views`.`time`) GROUP BY `track` ORDER BY `count` DESC LIMIT %s", $trackList, $days, $limit));

	}	



}