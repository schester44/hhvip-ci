<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends MY_Controller {

	
	public function index() {

		show_404('Site Index Controller', 'log_error');
	}
	
	public function dmca() {
		$this->data['title'] = 'DMCA Information | ' . $this->lang->line('meta_title');
		$this->_render('main/dmca', $this->data);
	}

	public function about() {
		$this->data['title'] = 'About ' . $this->lang->line('meta_title');
		$this->_render('main/about', $this->data);
	}

	public function advertise() {

		$this->data['title'] = 'Advertise on ' . $this->lang->line('meta_title');
		$this->_render('main/advertise', $this->data);

	}

	public function contact() {

		$this->data['title'] = 'Contact Us | ' . $this->lang->line('meta_title');
		$this->_render('main/contact', $this->data);
	}

	public function privacy() {

		$this->data['title'] = 'Privacy Policy | ' . $this->lang->line('meta_title');
		$this->_render('main/privacy', $this->data);
	}

	public function terms() {
		$this->data['title'] = 'Terms of Service | ' . $this->lang->line('meta_title');
		$this->_render('main/terms', $this->data);
	}

}