<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Votes extends MY_Controller {

	public function index()
	{
		redirect('/', 'refresh');
	}
	public function ajax_vote() {

		if (!$this->input->is_ajax_request()) {
			redirect('/', 'refresh');
		}

		if (!$this->input->post('id')) {
			redirect('/', 'refresh');
		}

			$exists = $this->Song_model->get_song($this->input->post('id'));

		if (!$exists) {
			die('E. Application Error');
		}

	    $data = array(
	    	'song_id' 	=> $this->input->post('id'),
	    	'rating' 	=> $this->input->post('rating'),
	    	'user_ip' 	=> $_SERVER['REMOTE_ADDR'],
	     );

		//$data = array('song_id'=>'421','rating'=>'10','user_ip'=>$_SERVER['REMOTE_ADDR']);
	    $this->voting->set_vote($data);
	}
	
}

/* End of file votes.php */
/* Location: ./application/controllers/votes.php */