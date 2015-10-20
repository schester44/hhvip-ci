<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Voting {

	private $CI;

	  function __construct()
    {
        $this->CI =& get_instance();
    }

    public function set_vote($data="")
    {

//$data will be set in the voting controller, voting controller will be used to interact only with AJAX POSTS   
 		if (!isset($data) || empty($data)) {
 			die('Application Error. No Data');
 		}
    	if ($this->CI->Song_model->get_song($data['song_id'])) {
    		$song_id 		= $data['song_id'];
    		$vote_rating 	= $data['rating'];
    		$user_ip		= $data['user_ip'];
    		$undo			= false; //track whether we're adding a vote or undoing one
    	} else {
    		die('B. Song Does Not Exist');
    	}
		//run the vote function which will figure out the users intentions and interact with the Vote model    
	    $this->_vote($song_id, $vote_rating, $user_ip, $undo);
    }


	private function _vote($song_id, $vote_rating, $user_ip, $undo) {

		if (!$this->CI->ion_auth->logged_in()) {
			$user_id = 0;

			$sql_select = 'vote_rating'; 
			$sql_where =  array(
				'vote_song_id'=>$song_id,
				'vote_user_id'=>$user_id,
				'vote_user_ip'=>$user_ip,
				'vote_rating !='=>'-999');
			$already_voted = $this->CI->Vote_model->get_vote_rating($sql_select, $sql_where);
		
		} else {
			//only proceed if the user is logged in
			if (!$this->CI->ion_auth->logged_in()) { return false; }

			$user_id = $this->CI->ion_auth->user()->row()->id;

			//get users vote history for this post:

			$sql_select = 'vote_rating';
			$sql_where 	=  array(
				'vote_song_id'=>$song_id,
				'vote_user_id'=>$user_id,
				'vote_rating !='=>'-999');
			$sql_order 	= 'vote_updatedts DESC';
			$sql_limit	= '1';

			$already_voted = $this->CI->Vote_model->get_vote_rating($sql_select, $sql_where, $sql_order, $sql_limit);

		}

		//$already_voted = vote_rating in database || $vote_rating = the new vote rating being passed via AJAX
		if ($already_voted == $vote_rating) {
			//Repeat vote. Return false

			$output_array = array('result'=>'Already Voted','rating'=>$vote_rating);
       		$this->CI->output->set_header('Content-Type: application/json; charset=utf-8');
        	$this->CI->output->set_output(json_encode($output_array));

			return false;

		}

		//If already voted down, but now voting up, undo the original
		if ($already_voted && ($already_voted < 0) && ($vote_rating > 0)) {

			$this->_remove_vote($song_id, $user_id, $already_voted, $vote_rating);
			$undo = true;
		} 

		//If already voted up, but now  voting down, undo the original
		elseif ($already_voted && ($already_voted > 0) && ($vote_rating < 0)) {

			$this->_remove_vote($song_id, $user_id, $already_voted, $vote_rating);
			$undo = true;
		}

		//Vote Up!
		elseif ($vote_rating > 0) {

			$this->_vote_up($song_id, $user_id, $user_ip, $vote_rating);
		}
		//Vote Down!
		elseif ($vote_rating < 0) {

			$this->_vote_down($song_id, $user_id, $user_ip, $vote_rating);
		}

		//get current vote count and status
		$sql_select = 'upvotes, downvotes, status, published_date';
		$sql_where 	= array('song_id'=>$song_id);

		//cached model call
		$current_count = $this->CI->cache->model('Vote_model', 'get_votes', array($sql_select, $sql_where), 300); // keep for 5 minutes
		//$current_count = $this->CI->Vote_model->get_votes($sql_select, $sql_where);

		$this->CI->load->library('sorting');
		$hotness = $this->CI->sorting->_hot($current_count->upvotes, $current_count->downvotes, $current_count->published_date);

		$sql_hotness_table = "songs";
		$sql_hotness_where = array('song_id'=>$song_id);
		$sql_hotness_data = array('hotness'=>$hotness);
		$this->CI->Vote_model->update_vote($sql_hotness_table, $sql_hotness_data, $sql_hotness_where);


		$votes = $current_count->upvotes - $current_count->downvotes;
		$output_array = array('votes'=>$votes,'undo'=>$undo);

       		$this->CI->output->set_header('Content-Type: application/json; charset=utf-8');
        	$this->CI->output->set_output(json_encode($output_array));
	}


	private function _remove_vote($song_id, $user_id, $already_voted, $vote_rating) {
		
		//update postvotes table
		if (!$this->CI->ion_auth->logged_in()) { 
			$sql_table 	= 'songs_postvotes';
			$sql_where 	= array(
				'vote_song_id'=>$song_id,
				'vote_user_id'=>$user_id,
				'vote_rating'=>$already_voted
				);
			$this->CI->Vote_model->remove_vote($sql_table, $sql_where);
		} else {
			$sql_table 	= 'songs_postvotes';
			$sql_where 	= array(
				'vote_song_id'=>$song_id,
				'vote_user_id'=>$user_id,
				'vote_rating'=>$already_voted
				);
			$this->CI->Vote_model->remove_vote($sql_table, $sql_where);
		}



		// Update Posts Table
		if ($vote_rating > 0) {
			$sql_table = 'songs';
			$sql_data = array('downvotes'=>'downvotes -1');
			$sql_where = array('song_id'=>$song_id);

			$this->CI->Vote_model->update_vote($sql_table, $sql_data, $sql_where);
		} else {
			$sql_table = 'songs';
			$sql_data = array('upvotes'=>'upvotes - 1');
			$sql_where = array('song_id'=>$song_id);
			$this->CI->Vote_model->update_vote($sql_table, $sql_data, $sql_where);
		}

	}

	private function _vote_up($song_id, $user_id, $user_ip, $vote_rating) {

			$sql_table = 'songs';
			$sql_data = array('upvotes'=>'upvotes + 1');
			$sql_where = array('song_id'=>$song_id);

			$this->CI->Vote_model->update_vote($sql_table, $sql_data, $sql_where);
			
			//Update Post Votes Table
			$table = 'songs_postvotes';
			$data = array(
				'vote_song_id'=>$song_id,
				'vote_user_id'=> $user_id,
				'vote_user_ip'=> $user_ip,
				'vote_date'=> date('Y-m-d H:i:s'),
				'vote_rating'=>$vote_rating
				);

			$this->CI->Vote_model->add_vote($table, $data);

	}

	private function _vote_down($song_id, $user_id, $user_ip, $vote_rating) {
		//Update Posts Table

		$sql_table = 'songs';
		$sql_data = array('downvotes'=>'downvotes + 1');
		$sql_where = array('song_id'=>$song_id);

		$this->CI->Vote_model->update_vote($sql_table, $sql_data, $sql_where);

		//Update Postvotes Table
		$sql_table = 'songs_postvotes';
		$sql_data = array(
			'vote_song_id'	=> $song_id,
			'vote_user_id'	=> $user_id,
			'vote_user_ip'	=> $user_ip,
			'vote_date'		=> date('Y-m-d H:i:s'),
			'vote_rating'	=> $vote_rating
			);

		$this->CI->Vote_model->add_vote($sql_table, $sql_data);

	}

	public function has_voted($song_id) {


		if (!$this->CI->ion_auth->logged_in()) {
			$user_id = 0;
			$user_ip = $_SERVER['REMOTE_ADDR'];

			$sql_where = array('song_id'=>$song_id,'user_id'=>$user_id,'user_ip'=>$user_ip);

		} else {
			$user_id = $this->CI->ion_auth->user()->row()->id;
			$sql_where = array('song_id'=>$song_id,'user_id'=>$user_id);
		}
		$select = 'vote_rating';
		$current = $this->CI->Vote_model->get_vote($select, $sql_where);
		return $current->vote_rating;
	}

	public function vote_sum($upvotes,$downvotes) {
		$sum = $upvotes - $downvotes;

		if (strlen($sum) == 4) {
			$val = substr($sum, 0,1) . '.' . substr($sum, 1, 1) .'K';	
		} elseif (strlen($sum) === 5) {
			$val = substr($sum, 0,2) . '.' . substr($sum, 2,1) .'K';
		} else {
			$val = $sum;
		}

		return $val;
	}

	public function hotness_color($upvotes,$downvotes) {
		// currently not in use, but this changes the color of the vote number box based on how many votes.

		//$sum = $upvotes - $downvotes;
		
		// if ($sum >= 7) {
		// 	$color = 'red';
		// } elseif ($sum < 0 ) {
		// 	$color = '#ccc';
		// } else {
		// 	$color = '#fa8900';
		// }

		//return $color;
		
		return '#fa8900';
	}

}

/* End of file Voting.php */