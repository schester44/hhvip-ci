<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MY_Controller {

	public function events($o = NULL) {
		$this->load->library('Stats');

		if (isset($o) && $o == 'playlist') {
			$add = $this->stats->log(array(
				'list_id'=>$this->input->post('lid'),
				'track_id'=>$this->input->post('tid'),
				'event'=>$this->input->post('e'),
				'type'=>$this->input->post('t')
				));
		} else {
			$add = $this->stats->log(array(
				'track_id'=>$this->input->post('tid'),
				'event'=>$this->input->post('e'),
				'type'=>$this->input->post('t')));
		}

		if ($add) {
			echo 'L ' . $this->input->post('tid');
		} else {
			echo 'NL ' . $this->input->post('tid');
		}
	}
}

/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */