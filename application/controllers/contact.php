<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends MY_Controller {

		protected		$sendEmailTo 		= 'hiphopvip1@gmail.com';
		protected		$subjectLine 		= '';

		protected		$spam_protection 	= true;
		protected		$spam_question 		= 'What color is the blue car?';
		protected		$spam_answer 		= 'blue';

	public function index() {
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->data['show_spam_protection'] = $this->spam_protection; // used in the view
		$this->data['spam_question'] = $this->spam_question; // used in the view

		$this->subjectLine = "Contact form response from " . $_SERVER['HTTP_HOST'];
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('company', 'Company', 'trim');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('url', 'Infringing URL', 'trim|required|');
		$this->form_validation->set_rules('message', 'Message', 'trim|xss_clean');

		if ($this->spam_protection) {
			$this->form_validation->set_rules('spam_protection', 'Spam Protection', 'callback_spam_protection');
		}

		if($this->form_validation->run() == FALSE) {

			$this->session->set_flashdata('formErrors', validation_errors('<div class="alert alert-error" style="color:red"><strong>Error!</strong> ', '</div>'));
			redirect('site/dmca','refresh');


		} else {
			// success! email it, assume it sent, then show contact success view.

			$this->load->library('email');
			$this->email->from($this->input->post('email'), $this->input->post('name'));
			$this->email->to($this->sendEmailTo);
			$this->email->subject($this->subjectLine);
			$this->email->message($this->input->post('message'));
			$this->email->send();

			$this->session->set_flashdata('formErrors', '<div class="alert alert-error" style="color:green;font-size:18px"><strong>Your email was successfully sent.<br/>We will review the request and get back to you as soon as possible.</strong></div>');
			redirect('site/dmca','refresh');
		}
	}

	public function spam_protection($str) {
		// we will assume the user is lazy with their caps lock
		if (strtolower(trim($str)) == strtolower(trim($this->spam_answer))) {
			return true;
		}
		else {
			$this->form_validation->set_message('spam_protection', 'The %s field did not match the correct answer');
			return false;
		}
	}
}