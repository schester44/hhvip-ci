<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Radio extends MY_Controller {

		function __construct()
	{
		parent::__construct();
		
	}

	public function index()
	{

		$this->_render('radio/index',$renderData="");
	}


/******************
* USERS' RADIO PAGE
******************/
	public function user_radio($user="") {

			$user = $this->User_model->get_user_info($user);

			if (!$user) {
				redirect('radio', 'refresh');
				//flash session message about radio station not existing
			}

			$this->data['user'] = $user;

			//extra css and js needed for player
			$this->data['vendorCSS'] = array(
				'apm/skin/apmplayer_base.css',
				'apm/skin/hhvip.css',
				'apm/skin/jquery-ui-slider.custom.css',
				'social-likes/social-likes_classic.css'
				);

			$this->data['vendorJS'] = array(
				'apm/lib/jquery-ui-slider-1.10.4.custom.min.js',
				'apm/lib/modernizr-2.5.3-custom.min.js', 
				'apm/lib/soundmanager2-jsmin.js', 
				'apm/apmplayer.js',
				'apm/apmplayer_ui.jquery.js',
				'social-likes/social-likes.min.js'
				);

			$this->data['meta_name'] = array(
				'description'=>'Listen to and download the latest songs from '. $user->username . 's online radio',
				'twitter:card'=>'summary_large_image',
				'twitter:domain'=>base_url(),
				'twitter:site'=> '@hiphopvip1',
				'twitter:title'=>$user->username . "'s Radio",
				'twitter:creator'=>'@'.$user->twitter_handle,
				'twitter:description'=>'Listen to and download the latest songs from '. $user->username . 's online radio',
				'twitter:image:src'=>$user->username
				);

			$this->data['meta_prop'] = array(
				'og:title'=> $user->username."'s radio is online, Listen!",
				'og:url'=> base_url('radio/'.$user->username),
				'og:site_name'=> 'hiphopVIP',
				'og:description'=> 'Listen to and download the latest songs from '.$user->username.'s Radio'
				);
		$this->_render('radio/user_radio', $this->data);
	}

}

/* End of file radio.php */
/* Location: ./application/controllers/radio.php */