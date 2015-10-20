<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Errors extends MY_Controller {

	public function page_missing() {

		$this->output->set_header("HTTP/1.1 404 Not Found");

		$this->data['title'] = '404 Page Not Found | hiphopVIP';
		$this->_render('errors/404', $this->data);
	}

		public function no_artist_playlist() {

		$this->output->set_header("HTTP/1.1 404 Not Found");

		$this->data['noSidebar'] = TRUE;

		$this->data['title'] = '404 Artist Not Found | hiphopVIP';
		$this->_render('errors/no_playlist', $this->data);
	}
}