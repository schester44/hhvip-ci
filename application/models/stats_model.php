<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stats_model extends CI_Model {
		
	public function __construct(){
		parent::__construct();
	}

	function add($type, $data) {
		$this->db->insert('stats_' . $type . '_events',$data);
		return $this->db->affected_rows();
	}

	function get_totals($type, $select, $where, $group_by, $join_table = NULL, $join_to, $order = NULL, $limit = NULL, $offset = NULL) {

		$this->db->select($select . ', COUNT(event) as total');
		$this->db->where($where);
		$this->db->from('stats_'. $type .'_events');
		$this->db->group_by($group_by);
		$this->db->join($join_table, $join_to);

		if (!empty($limit)) {
			$this->db->limit($limit, $offset);
		}

		if (!empty($order)) {
			$this->db->order_by($order);
		}
		
		$query = $this->db->get();

		if (!$query->result()) {
			return false;
		} else {
			return $query->result();
		}

	}

	function get($type, $where, $order = NULL, $limit = NULL, $offset = NULL) {
		$this->db->select('*');
		$this->db->where($where);
		$this->db->from('stats_' . $type . '_events');

		if (!empty($limit)) {
			$this->db->limit($limit, $offset);
		}

		if (!empty($order)) {
			$this->db->order_by($order);
		}
		
		$query = $this->db->get();

		if (!$query->result()) {
			return false;
		} else {
			return $query->result();
		}

	}

	function delete($type, $where) {
		$this->db->delete('stats_' . $type . '_events', $where);
		$affected = $this->db->affected_rows();
		if ($affected) {
			return true;
		} else {
			return false;
		}
	}

}