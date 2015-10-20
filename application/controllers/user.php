<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller {

		function __construct()
	{
		parent::__construct();

	}

	public function index()
	{	

		$this->data['subSection_bigav'] = $this->cache->library('sorting', 'get_list', array('songs','popular',5));

		$this->_render('user/index', $this->data);

	}

	public function profile_page($username) {

		if (!$this->ion_auth->username_check($username)) {
			redirect('errors/page_missing', 'refresh');
		}

		$this->load->helper('slug');
		$this->load->library('pagination');

			//cached DB call
			$user = $this->cache->model('User_model', 'get_user_info', array($username), 300); // keep for 5 minutes
			$this->data['username'] = $user->username;
			$this->data['user'] = $user;

		//pagination
		//
			if ($this->ion_auth->logged_in() && $this->ion_auth->user()->row()->username == $this->uri->segment('2')) {
				$sWhere = array('user_id'=>$user->id,'status'=>'published');
			} else {
				$sWhere = array('user_id'=>$user->id,'status'=>'published', 'visibility'=>'public');

			}
		
			$config['total_rows'] = $this->cache->model('Song_model', 'song_count', array($sWhere), 120);
			$config['per_page'] = 20;
			$config['base_url'] = base_url('u/'.$username);

			$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

			$this->data['songs'] 	= $this->cache->model('Song_model', 'get_songs_where', array($sWhere, $config['per_page'], 'song_id DESC',$page), 120); // keep for 2 minutes


			$this->pagination->initialize($config); 

			$this->data['pagination'] = $this->pagination->create_links();
			$results = 'Showing '.($this->pagination->cur_page * $this->pagination->per_page). ' of '.$this->pagination->total_rows;
			$this->data['result_nums'] = $results;

			$this->data['meta_name'] = array(
				'description'=>'Songs uploaded by '.$username . '. ' . $this->lang->line('meta_description'),
				'twitter:card'=>"summary_large_image",
				'twitter:domain'=>base_url('u/'.$username),
				'twitter:site'=> $this->lang->line('meta_twitter'),
				'twitter:title'=> $username."'s Profile | " . $this->lang->line('meta_title'),
				'twitter:creator'=>'@'. $user->twitter_handle,
				'twitter:description'=> $username . "'s profile. Singles and Mixtapes uploaded by " . $username,
				'twitter:image:src'=>user_img($username)
				);

			$this->data['meta_prop'] = array(
				'og:title'=> $username."'s Profile | " . $this->lang->line('meta_title'),
				'og:url'=> base_url('u/'.$username),
				'og:site_name'=> 'hiphopVIP',
				'og:description'=> $username . "'s profile. Singles and Mixtapes uploaded by " . $username
				);

		$this->data['title'] = $user->username ."'s Stream | " . $this->lang->line('meta_title');
		$this->_render('user/profile_page',$this->data);
	}

	public function stats() {

		// $filter is 'today,week,month,year'

		if (!$this->ion_auth->username_check($this->uri->segment('2'))) {
			redirect('erros/page_missing','refresh');
		}

		$user = $this->User_model->get_user_info($this->uri->segment('2'));


		$this->data['time_segment'] = $this->uri->segment('4');		
		if (!$this->uri->segment('4')) {
			$this->data['time_segment'] = 'Today';
		}

		$this->_render('user/stats');
	}

	public function get_playlist_songs($user_id) {

        $where = array(
            'user_id'=>$user_id,
            'status'=>'published'
            );

		$playlist = $this->cache->model('Song_model', 'get_songs_where', array($where, 10, 'song_id DESC'), 120); // keep for 2 minutes

        if (!$playlist) {
             return "''";
        } else {

           foreach ($playlist as $i) {

			if (!empty($i->featuring)) {
				$featuring = ' (Feat. ' . $i->featuring . ')';
			} else {
				$featuring = NULL;
			}
                $song_id        = $i->song_id;
                $song_title		= $i->song_title;
                $song_artist	= $i->song_artist . $featuring;
                $user_id        = $i->user_id;
                $username 		= $i->username;
                $file_uid       = $i->file_uid;
                $url 			= $i->song_url;
                $file_name      = $i->file_name;
                $upload_date    = $i->upload_date;
                $image_sm		= song_img($username, $url, $i->song_image, 64);
            $data[] = array(
            	'identifier'=>$song_title,
            	'type'=>'audio',
            	'description'=>$i->song_description,
            	'date'=>$i->published_date,
            	'image_sm'=>$image_sm,
            	'title'=>$song_title,
            	'program'=>$song_artist,
            	'http_file_path'=>getSignedURL($this->config->item('cloudfront_music') . '/tracks/' . $username . '/' . $file_name, '6000'),
            	'detail'=>$url);
            }

            return json_encode($data);
        }

    }
}

/* End of file user.php */
/* Location: ./application/controllers/user.php */