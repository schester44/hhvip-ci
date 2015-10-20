<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller{
	
	//Page info
	protected $data = Array();
	protected $pageName = FALSE;
	protected $template = "main";
	protected $hasNav = TRUE;
	//Page contents
	protected $javascript = array();
	protected $css = array();
	protected $fonts = array();

	function __construct()
	{	
		parent::__construct();


		if (ENVIRONMENT === 'production') {
			$this->load->helper('ssl');
			check_ssl();
		}
	}

	protected function _render($view,$renderData="FULLPAGE") {
        switch ($renderData) {
        case "AJAX"     :
            $this->load->view($view,$this->data);
        break;
        case "EMBED"	:
        $this->load->view($view, $this->data);
        break;
        case "JSON"     :
            echo json_encode($this->data);
        break;
        case "FULLPAGE" :
        default 		:

		//static & meta
		$toTpl["javascript"] = $this->javascript;
		$toTpl["css"] = $this->css;
		$toTpl["fonts"] = $this->fonts;
		$toTpl["base_title"] = 'hiphopVIP | Music for Life';

		//sets currenturl() into session data so when the user logs in, 
		//they'll be redirected back to their current page
		if ($this->uri->segment('1') != 'auth') {
			$this->session->set_userdata('last_page', current_url());
		}

		//TEMPLATE SIDEBAR
		if ($this->uri->segment(2) == 'latest') {
			$list_type = 'popular';
			$sidebar_widget_title = $this->sorting->list_title('songs',$list_type);
			
		} else {
			$list_type = 'latest';
			$sidebar_widget_title = $this->sorting->list_title('songs',$list_type);
		} 

		if ($this->uri->segment('1') === 'playlist' || $this->uri->segment('1') === 'mixtape') {
			$sidebar_song_limit = 5;
		} else {
			$sidebar_song_limit = 15;
		}

		if (!$this->uri->segment(1)) {
			$sidebar_song_limit = 10;
		}

		$this->data['sidebar_news'] 		= $this->cache->model('Blog_model', 'get_posts', array(array('status'=>'published','access'=>'public'), 'id DESC', 10, 0 ), 300);

		$this->data['sidebar_songs_list'] = $this->cache->library('sorting', 'get_list', array('songs',$list_type, $sidebar_song_limit));
		$this->data['sidebar_widget_title'] = $sidebar_widget_title;
		$this->data['sidebar_list_type'] = $list_type;
		
		/** limit sidebar, restrict which views can see certain content **/
		$limitUrls = array('upload','manage','auth');
		if (in_array($this->uri->segment(1), $limitUrls)) {
        	$this->data['limitSidebarContent'] = true;
		}

		if ($this->ion_auth->logged_in()) {
			$this->load->model('Playlist_model');
			$this->data['user_sidebar_playlist'] = $this->cache->model('Playlist_model', 'get', array(array('user_id'=>$this->ion_auth->user()->row()->id), 'playlists.id DESC', 5, 0), 300);
		}
		//END TEMPLATE SIDEBAR
		


		////MAIN TEMPLATE STUFF////
		$toBody["content_body"] = $this->load->view($view,array_merge($this->data,$toTpl),true);
		$toBody["sidebar"] 		= $this->load->view('template/sidebar',array_merge($this->data,$toTpl),true);

		if($this->hasNav){
			$toMenu["pageName"] = $this->pageName;
			$toHeader["nav"] 	= $this->load->view("template/nav",$toMenu,true);
		}

		$toBody["header"] 	= $this->load->view("template/header",$toHeader,true);
		$toBody["footer"] 	= $this->load->view("template/footer",'',true);
		$toTpl["body"] 		= $this->load->view("template/".$this->template,$toBody,true);
		////END MAIN TEMPLATE STUFF////		

		$this->load->view("template/skeleton",$toTpl);
		 break;
    }
	}
}
