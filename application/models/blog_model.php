<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog_model extends CI_Model {
		
	public function __construct(){
		parent::__construct();
	}

	public function add_post($data) {
		$this->db->insert('blog_posts', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	public function delete_post($where) {
		$this->db->delete('blog_posts', $where);
		$affected = $this->db->affected_rows();
		if ($affected) {
			return true;
		} else {
			return false;
		}
	}

	public function get_post($where) {
		$this->db->select('blog_posts.*, users.username, blog_categories.title AS category_title')
			->from('blog_posts')
			->where($where)
			->join('users','users.id = blog_posts.author')
			->join('blog_categories','blog_categories.id = blog_posts.category');


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

	public function update_post($where, $data) {
		$this->db->where($where)->update('blog_posts',$data);
		$updated = $this->db->affected_rows();
		return $updated;
	}

	public function get_posts($where, $order = NULL, $limit = NULL, $offset = NULL) {

		$this->db->select('blog_posts.*, users.username, blog_categories.title AS category_title')
			->from('blog_posts')
			->where($where)
			->join('users','users.id = blog_posts.author')
			->join('blog_categories','blog_categories.id = blog_posts.category');
			
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

	public function count($where) {
	 	$this->db->where($where);
	 	$this->db->from('blog_posts');
	 	$this->db->join('blog_categories','blog_categories.id = blog_posts.category');
			
        return $this->db->count_all_results();
    }
	/**
	 * BLOG CATEGORIES
	 */
	
	public function add_category($data) {
		$this->db->insert('blog_categories', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	public function update_category($where, $data) {
		$this->db->where($where)->update('blog_categories',$data);
		$updated = $this->db->affected_rows();
		return $updated;
	}

	public function delete_category($where) {
		$this->db->delete('blog_categories', $where);
		$affected = $this->db->affected_rows();
		if ($affected) {
			return true;
		} else {
			return false;
		}
	}

	public function get_category($where) {
		$this->db->select('&')
			->from('blog_categories')
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

	public function get_categories($where) {
		$this->db->select('*')
			->from('blog_categories')
			->where($where);

		$query = $this->db->get();

		if (!$query->result()) {
			return false;
		} else {
			return $query->result();
		}
	}

}

