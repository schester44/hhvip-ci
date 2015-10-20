<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload_model extends CI_Model {
		
	public function __construct(){
		parent::__construct();
	}

	public function song_already_uploaded($where) {
		$query = $this->db->get_where('songs',$where);
		if ($query->num_rows() > 0){
	        return true;
	    }
	    else{
	        return false;
	    }
	}

}