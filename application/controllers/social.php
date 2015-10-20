<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Social extends MY_Controller {

	function __construct()
	{
		parent::__construct();

	}

	public function index()
	{
		redirect('/','refresh');
	}

	public function playlists($username) {
		if (!$this->ion_auth->username_check($username)) {
			redirect('errors/page_missing', 'refresh');
		}

		$user = $this->cache->model('User_model', 'get_user_info', array($username), 300);
		$this->data['user'] = $user;
		
		$this->load->library('pagination'); 
		$this->load->model('Playlist_model');


		if ($this->ion_auth->logged_in() && $this->ion_auth->user()->row()->username === $username) {
			$where  = array('users.username'=>$username);
		} else {
			$where		= array('status'=>'public','users.username'=>$username);
		}

		$config['total_rows'] 	= $this->Playlist_model->count($where);
		$config['per_page']		= 25;
		$config['uri_segment'] = 3;
		$config['base_url'] = base_url('u/'.$username.'/playlists');
		$page = ($this->uri->segment(4) ? $this->uri->segment(4) : 0);

		$playlists = $this->Playlist_model->get($where, 'id DESC', $config['per_page'], $page);

		$this->pagination->initialize($config); 
		$curTotalRows = count($playlists);
		
		$this->data['pagination'] = $this->pagination->create_links();
		$results = 'Showing '.$curTotalRows. ' of '.$this->pagination->total_rows;
		$this->data['result_nums'] = $results;

		$this->data['username'] = $username;
		$this->data['playlists'] = $playlists;
		$this->data['title'] = $username . "'s Playlists | " . SITE_TITLE;
			

       $this->_render('user/playlists',$this->data);

	}

	public function favorite($username, $song) {
        $this->output->set_header('Content-Type: application/json; charset=utf-8');


		//user not logged in so they're unable to add the song as a favorite, redirect to login.
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login','refresh');
		}

		//check if user exists		
		if (!$this->ion_auth->username_check($username)) {
			redirect('errors/page_missing', 'refresh');
		}

		//check if song exists based on song_url 
		if (!$this->Song_model->get_song_where(array('song_url'=>$song))) {
			$output_array = array('favorite' => 'null', 'response'=>'error', 'message' => 'Song does not exist');
		} else {	

			$song = $this->Song_model->get_song_where(array('song_url'=>$song));
			$uid = $this->ion_auth->user()->row()->id;

			$favExists = $this->Social_model->get_favorite(array('user_id'=>$uid,'song_id'=>$song->song_id));

			if ($favExists) {
				//fav exists, so remove it
				$this->Social_model->delete_favorite(array('user_id'=>$uid,'song_id'=>$song->song_id));
				$output_array = array('favorite' => 'deleted', 'response'=>'success', 'message' => 'Song deleted from favorites');

			} else {
				// fav doesnt exist, so add it.
				$this->Social_model->add_favorite(array('song_id'=>$song->song_id,'user_id'=>$uid,'date_added'=>time()));
				$output_array = array('favorite' => 'added', 'response'=>'success', 'message' => 'Song added to favorites');
			
			}
		}

        $this->output->set_output(json_encode($output_array));
		$this->cache->model('Social_model', 'get_favorites', array(array('user_favorites.user_id'=>$uid),'date_added DESC', 15, 0), -1);
	}

	public function follow($username) {
		
		//user not logged in so they're unable to follow users
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login','refresh');
		}

		//check if user exists		
		if (!$this->ion_auth->username_check($username)) {
			redirect('errors/page_missing', 'refresh');
		}

		//logged in user
		$uid = $this->ion_auth->user()->row()->id;

		//peron to follow
		$user = $this->cache->model('User_model', 'get_user_info', array($username), 300); // keep for 5 minutes

		if ($uid === $user->id) {
			redirect('u/'.$username,'refresh');
		}

		$followExists = $this->Social_model->get_follow(array('follower_id'=>$uid,'following_id'=>$user->id));

		if ($followExists) {
			$this->Social_model->delete_follow(array('follower_id'=>$uid,'following_id'=>$user->id));
			redirect(base_url('u/'.$username));
			
		} else {
			$this->Social_model->add_follow(array('follower_id'=>$uid,'following_id'=>$user->id,'date_followed'=>time()));
			redirect(base_url('u/'.$username));
		}
		$this->cache->model('Social_model', 'get_followers', array(array('user_follows.following_id'=>$uid),'date_followed DESC', 15, 0), -1);
	}


	public function followers($username) {

		if (!$this->ion_auth->username_check($username)) {
			redirect('errors/page_missing', 'refresh');
		}

		$this->load->library('pagination');

		$this->data['username'] = $username;
		
		$user = $this->cache->model('User_model', 'get_user_info', array($username), 300);
		$this->data['user'] = $user;


		$this->load->library('pagination'); 


		// cached model call
		$config['uri_segment'] = 4;
		$config['base_url'] = base_url('u/'.$username.'/followers');
		$config['total_rows'] = $this->cache->model('Social_model', 'follow_count', array(array('following_id'=>$user->id)), 300); // keep for 5 minutes

		$config['per_page'] = 15; //how many results to return from the query
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

		$this->data['followers'] = $this->cache->model('Social_model', 'get_followers', array(array('user_follows.following_id'=>$user->id),'date_followed DESC', $config['per_page'], $page), 300);

		$this->pagination->initialize($config); 
		$this->data['pagination'] = $this->pagination->create_links();
		
		$cur_page = ($this->pagination->cur_page === 0) ? $this->pagination->total_rows : $this->pagination->cur_page * $this->pagination->per_page;

		$results = 'Showing '.$cur_page. ' of '.$this->pagination->total_rows;
		$this->data['result_nums'] = $results;
		//end pagination

		$this->_render('user/followers', $this->data);	
	}

	public function following($username) {

		if (!$this->ion_auth->username_check($username)) {
			redirect('errors/page_missing', 'refresh');
		}

		$this->load->library('pagination');

		$this->data['username'] = $username;
		
		//cache this shit dawg!
		$user = $this->cache->model('User_model', 'get_user_info', array($username), 300);
		$this->data['user'] = $user;

		$this->load->library('pagination'); 

		// cached model call
		$config['uri_segment'] = 4;
		$config['base_url'] = base_url('u/'.$username.'/following');
		$config['total_rows'] = $this->cache->model('Social_model', 'follow_count', array(array('follower_id'=>$user->id)), 300); // keep for 5 minutes

		$config['per_page'] = 15; //how many results to return from the query
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

		$this->data['following'] = $this->cache->model('Social_model', 'get_following', array(array('user_follows.follower_id'=>$user->id),'date_followed DESC', $config['per_page'], $page), 300);
		$this->pagination->initialize($config); 
		$this->data['pagination'] = $this->pagination->create_links();
		
		$cur_page = ($this->pagination->cur_page === 0) ? $this->pagination->total_rows : $this->pagination->cur_page * $this->pagination->per_page;
		$results = 'Showing '.$cur_page. ' of '.$this->pagination->total_rows;
		$this->data['result_nums'] = $results;
		//end pagination

		$this->_render('user/following', $this->data);	
	}

	public function favorites($username) {

		if (!$this->ion_auth->username_check($username)) {
			redirect('errors/page_missing', 'refresh');
		}

		$this->load->library('pagination');
		
		$user = $this->cache->model('User_model', 'get_user_info', array($username), 300);
		$this->data['username'] = $username;
		$this->data['user'] = $user;

		$this->load->library('pagination'); 

		// cached model call
		$config['uri_segment'] = 4;
		$config['base_url'] = base_url('u/'.$username.'/favorites');
		$config['total_rows'] = $this->cache->model('Social_model', 'favorites_count', array(array('user_id'=>$user->id)), 300); // keep for 5 minutes

		$config['per_page'] = 15; //how many results to return from the query
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;


		$this->data['songs'] = $this->cache->model('Social_model', 'get_favorites', array(array('user_favorites.user_id'=>$user->id),'date_added DESC', $config['per_page'], $page), 300); // keep for 5 minutes

		$this->pagination->initialize($config); 
		$this->data['pagination'] = $this->pagination->create_links();

		$cur_page = ($this->pagination->cur_page === 0) ? $this->pagination->total_rows : $this->pagination->cur_page * $this->pagination->per_page;

		$results = 'Showing '.$cur_page. ' of '.$this->pagination->total_rows;
		$this->data['result_nums'] = $results;

			$this->_render('user/favorites', $this->data);
	}

}

/* End of file social.php */
/* Location: ./application/controllers/social.php */