<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Backend extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->data['noSidebar'] = true;

		if (!$this->ion_auth->is_admin()) {
			redirect('errors/page_missing', 'refresh');
		}

	}

	public function index() {

		$this->_render('admin/index');
	}

	public function batch_songs(){
		$this->_render('admin/manage/copyright');

	}

	public function playlists() {
		$this->load->model('Playlist_model');
		$this->load->library('pagination');

		$where = array('playlists.id >'=>'1');



        $order = 'playlists.id DESC';
        $config['uri_segment'] = 3;
        $config['base_url'] = base_url('backend/playlists/');
        $config['total_rows'] = $this->Playlist_model->count($where);
        $config['per_page'] = 10;
        $page = ($this->uri->segment(3) ? $this->uri->segment(3) : 0);
        
		$playlists = $this->Playlist_model->get($where, 'id DESC', $config['per_page'], $page);

        $this->pagination->initialize($config); 
        $this->data['pagination'] = $this->pagination->create_links();

        $this->data['playlists'] = $playlists;

		$this->_render('admin/manage/playlists');            

	}

	public function stats() {

		$this->load->model('Stats_model');

		$this->data['week_count'] 		= $this->Song_model->song_count(array('published_date > '=> date(strtotime("-7 days"))));
		$this->data['month_count'] 		= $this->Song_model->song_count(array('published_date > '=> date(strtotime("-1 month"))));
		$this->data['year_count'] 		= $this->Song_model->song_count(array('published_date > '=> date(strtotime("-1 year"))));
		$this->data['all_time_count'] 	= $this->Song_model->song_count(array('song_id >'=>0));
		
		
		$join_table = "songs";
		$join_to = 'songs.song_id = stats_song_events.track_id';
		$song_select = 'stats_song_events.*, songs.song_title as title, songs.user_id as song_user_id, songs.song_url as song_url';
		
		$this->data['top_5_plays_today']		= $this->cache->model('Stats_model', 'get_totals', array('song', $song_select, array('event'=>'play','date >'=>date(strtotime("-1 days"))), 'track_id', $join_table, $join_to ,'total DESC', 5, 0), 900);
		$this->data['top_5_plays_week']			= $this->cache->model('Stats_model', 'get_totals', array('song', $song_select, array('event'=>'play','date >'=>date(strtotime("-7 days"))), 'track_id', $join_table, $join_to ,'total DESC', 5, 0), 900);
		$this->data['top_5_plays_month']		= $this->cache->model('Stats_model', 'get_totals', array('song', $song_select, array('event'=>'play','date >'=>date(strtotime("-30 days"))), 'track_id', $join_table, $join_to ,'total DESC', 5, 0), 900);
		$this->data['top_5_plays_year']			= $this->cache->model('Stats_model', 'get_totals', array('song', $song_select, array('event'=>'play','date >'=>date(strtotime("-365 days"))), 'track_id', $join_table, $join_to ,'total DESC', 5, 0), 900);
		$this->data['top_5_plays_all']			= $this->cache->model('Stats_model', 'get_totals', array('song', $song_select, array('event'=>'play'), 'track_id', $join_table, $join_to ,'total DESC', 5, 0), 900);

		$this->data['top_5_views_today']		= $this->cache->model('Stats_model', 'get_totals', array('song', $song_select, array('event'=>'view','date >'=>date(strtotime("-1 days"))), 'track_id', $join_table, $join_to ,'total DESC', 5, 0), 900);
		$this->data['top_5_views_week']			= $this->cache->model('Stats_model', 'get_totals', array('song', $song_select, array('event'=>'view','date >'=>date(strtotime("-7 days"))), 'track_id', $join_table, $join_to ,'total DESC', 5, 0), 900);
		$this->data['top_5_views_month']		= $this->cache->model('Stats_model', 'get_totals', array('song', $song_select, array('event'=>'view','date >'=>date(strtotime("-30 days"))), 'track_id', $join_table, $join_to ,'total DESC', 5, 0), 900);
		$this->data['top_5_views_year']			= $this->cache->model('Stats_model', 'get_totals', array('song', $song_select, array('event'=>'view','date >'=>date(strtotime("-365 days"))), 'track_id', $join_table, $join_to ,'total DESC', 5, 0), 900);
		$this->data['top_5_views_all']			= $this->cache->model('Stats_model', 'get_totals', array('song', $song_select, array('event'=>'view'), 'track_id', $join_table, $join_to ,'total DESC', 5, 0), 900);


		$this->data['top_5_downloads_today']		= $this->cache->model('Stats_model', 'get_totals', array('song', $song_select, array('event'=>'download','date >'=>date(strtotime("-1 days"))), 'track_id', $join_table, $join_to ,'total DESC', 5, 0), 900);
		$this->data['top_5_downloads_week']			= $this->cache->model('Stats_model', 'get_totals', array('song', $song_select, array('event'=>'download','date >'=>date(strtotime("-7 days"))), 'track_id', $join_table, $join_to ,'total DESC', 5, 0), 900);
		$this->data['top_5_downloads_month']		= $this->cache->model('Stats_model', 'get_totals', array('song', $song_select, array('event'=>'download','date >'=>date(strtotime("-30 days"))), 'track_id', $join_table, $join_to ,'total DESC', 5, 0), 900);
		$this->data['top_5_downloads_year']			= $this->cache->model('Stats_model', 'get_totals', array('song', $song_select, array('event'=>'download','date >'=>date(strtotime("-365 days"))), 'track_id', $join_table, $join_to ,'total DESC', 5, 0), 900);
		$this->data['top_5_downloads_all']			= $this->cache->model('Stats_model', 'get_totals', array('song', $song_select, array('event'=>'download'), 'track_id', $join_table, $join_to ,'total DESC', 5, 0), 900);

		$this->_render('admin/stats');
	}


	public function blog() {
		$this->load->model('Blog_model');
		$this->load->library('pagination');

        $config['uri_segment'] = 3;
        $config['base_url'] = base_url('backend/blog');
        $config['per_page'] = 25;

        $where = array('blog_posts.id >'=>'0');
        
        $config['total_rows'] = $this->Blog_model->count($where);        

        $page = ($this->uri->segment(3) ? $this->uri->segment(3) : 0);
		$posts = $this->Blog_model->get_posts($where, 'id DESC', $config['per_page'], $page);

        $this->pagination->initialize($config); 
        $this->data['pagination'] = $this->pagination->create_links();

		$this->data['posts'] = $posts;
		$this->data['vendorCSS'] = array('forms.css');
		$this->data['vendorJS'] = array('jquery.form.js');

		$this->_render('blog/admin/index');
	}
	
	public function videos() {

		$this->load->model('Video_model');
		$this->load->library('pagination');

        $config['uri_segment'] = 3;
        $config['base_url'] = base_url('backend/videos');
        $config['total_rows'] = $this->Video_model->video_count(array('id >'=>0));        
        $config['per_page'] = 5;

        $page = ($this->uri->segment(3) ? $this->uri->segment(3) : 0);
        $order = 'id DESC';
		$where = array('videos.id >'=>0);

		$videos = $this->Video_model->get_videos($where, $order, $config['per_page'], $page);  
		
        $this->pagination->initialize($config); 
        $this->data['pagination'] = $this->pagination->create_links();
		
		$this->data['videos'] = $videos;


		$this->_render('admin/manage/videos', $this->data);

	}

	public function songs() {

     	$type = $this->uri->segment(3);
			$this->load->library('pagination');
			if (!is_numeric($type)) {
        	$where = array('song_id >'=> '0','status'=>$type);
			} else {
       		 $where = array('song_id >'=> '0');
       		 $type = 'published';
			}
        $order = 'song_id DESC';
        $config['uri_segment'] = 4;
        $config['base_url'] = base_url('backend/songs/'.$type);
        $where = array('status'=>$type);
        $config['total_rows'] = $this->Song_model->song_count($where);
        $config['per_page'] = 5;
        $page = ($this->uri->segment(4) ? $this->uri->segment(4) : 0);

        $songs = $this->Song_model->get_all_songs($where, $order, $config['per_page'], $page);
        
        $this->pagination->initialize($config); 
        $this->data['pagination'] = $this->pagination->create_links();

        $this->data['songs'] = $songs;
            
            $this->_render('admin/manage/songs', $this->data);

	}


	public function mixtapes() {

		$this->load->model('Mixtape_model');
		
     	$type = $this->uri->segment(3);
			$this->load->library('pagination');
			if (!is_numeric($type)) {
        	$where = array('id >'=> '0','status'=>$type);
			} else {
       		 $where = array('id >'=> '0');
       		 $type = 'published';
			}
        $order = 'id DESC';
        $config['uri_segment'] = 4;
        $config['base_url'] = base_url('backend/mixtapes/'.$type);
        $where = array('status'=>$type);
        $config['total_rows'] = $this->Mixtape_model->mixtape_count($where);
        $config['per_page'] = 5;
        $page = ($this->uri->segment(4) ? $this->uri->segment(4) : 0);

        $tapes = $this->Mixtape_model->get_mixtapes($where, $order, $config['per_page'], $page);
        
        $this->pagination->initialize($config); 
        $this->data['pagination'] = $this->pagination->create_links();

        $this->data['tapes'] = $tapes;
        
        $this->_render('admin/manage/mixtapes', $this->data);

	}

		public function users() {
		$this->load->library('pagination');
       	$where = array('id >'=> '0');
        $order = 'username ASC';			
        $config['uri_segment'] = 3;
        $config['base_url'] = base_url('backend/users/');

        $config['total_rows'] = $this->User_model->user_count($where);
        $config['per_page'] = 5;
        $page = ($this->uri->segment(3) ? $this->uri->segment(3) : 0);

        $users = $this->User_model->get_all_users($where, $order, $config['per_page'], $page);
        
        $this->pagination->initialize($config); 
        $this->data['pagination'] = $this->pagination->create_links();

        $this->data['users'] = $users;

        foreach ($this->data['users'] as $k => $user)
		{
			$this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
		}

        $this->_render('admin/manage/users', $this->data);

	}


	public function search() {

		if ($this->input->get('t') != 'user' || !$this->input->get('t') || !$this->input->get('q')) {
			$this->data['title'] = 'Search | ' . SITE_TITLE;
			$this->data['resultsAvailable'] = FALSE;
			$this->_render('admin/search', $this->data);
		} else {

			$this->load->library('form_validation');
			$this->form_validation->set_rules('q', 'Search Query', 'trim|disallowed_words|min_length[2]');
			$this->form_validation->set_rules('t', 'Model Type', 'trim|disallowed_words|min_length[2]');

			$this->data['resultsAvailable'] = TRUE;
			$this->data['searchPar'] = $this->input->get('q');
			$this->data['modelType'] = $this->input->get('t');

			//TODO
			//if using input->get('t'), setup a SWITCH with different SQL queries based on predefined rules. 
			//Sort of like an API			
			$this->data['result'] = $this->User_model->get_all_users(array('username'=>$this->input->get('q')));			
			$this->_render('admin/search', $this->data);
		}
	}

	public function simple_admin_delete_song($song_id) {
	
		if (isset($song_id)) {
			$where = array('song_id'=>$song_id);
			$delete = $this->Song_model->delete_song($where);

			if ($delete) {
					redirect('backend/songs', 'refresh');
			} else {
					echo 'SHITS BROKE';
			}

		}
	}


	public function simple_admin_delete_mixtape($id) {
		$this->load->model('Mixtape_model');
		if (isset($id)) {
			$where = array('id'=>$id);
			$delete = $this->Mixtape_model->delete_mixtape($where);

			if ($delete) {
					redirect('backend/mixtapes', 'refresh');
			} else {
					echo 'SHITS BROKE';
			}

		}
	}

	public function site_functions() {
		$this->_render('admin/manage/site_functions', $this->data);
	}

/**
 * bumps votes and hotness up to promote songs to the trending page.
 * @return [json] returns an array of info including error and messages.
 */
	function boostVotes() {
		$this->output->set_content_type('application/json');

		$id   		= $this->input->post('id');
		$value   	= $this->input->post('value');
		$song 		= $this->Song_model->get(array('songs.song_id'=>$id));

		if (!$song) {
    		$this->output->set_output(json_encode(array('error'=>'false', 'message'=> 'NO SONG FOUND')));
		}

		$song = $song[0];
		$upvotes = $song->upvotes;

		if ($this->input->post('dump') != '') {
			$data = array(
				'upvotes'=>$upvotes - $value,
				'hotness'=>$this->sorting->hot($upvotes - $value, 0, $song->published_date));
		} else {
			$data = array(
				'upvotes'=>$upvotes + $value,
				'hotness'=>$this->sorting->hot($upvotes + $value, 0, $song->published_date));
		}

		$update = $this->Song_model->update(array('songs.song_id'=>$id), $data);

		if ($update) {
    		$this->output->set_output(json_encode(array('error'=>'false', 'message'=> 'Success')));
		} else {
    		$this->output->set_output(json_encode(array('error'=>'true', 'message'=>'Failed to update')));
		}

		$this->cache->library('sorting', 'get_list', array('songs','latest', 18, 0), -1);
		$this->cache->library('sorting', 'get_list', array('songs','trending',20), -1);

	}

	function db_migrate() {
		$this->update_song_migrate();
		$this->move_mp3();
		$this->setup_song_images();
	}

	function setupDbSchema() {
		$query = $this->db->get_where('hotaru_users',array('user_lastlogin > '=>'2013-01-01 1:10:30','user_password_conf !='=>'NULL'));
		$users = $query->result();

		if ($users) {
			foreach ($users as $u) {
				//insert into db
				$addUser = $this->User_model->add_user($data);
				if ($addUser) {
					//success
				} else {
					//error
				}
			}
		}
	}

	function update_song_migrate() {
				//urldecode fields, set hotness.
		$where = array('song_id >'=>'0');
		$songs = $this->Song_model->get_all_songs($where);
		echo '<h1>DO NOT REFRESH OR LEAVE THE BROWSER</h1>';
		foreach ($songs as $song) {

			//takes off 10 upvotes (default starting position for v1 platforms
			$real_upvotes = ($song->upvotes > 5) ? $song->upvotes - 10 : $song->upvotes;
		
			$hotness = $this->sorting->hot($real_upvotes,$song->downvotes,$song->published_date);
		
			$update_data = array(
				'file_uid'=>md5($song->song_id . $song->song_url . $song->published_date),
				'sfname'=>md5($song->song_id . $song->song_url . $song->published_date),
				'song_description'=>urldecode(stripcslashes(htmlspecialchars_decode($song->song_description))),
				'song_artist'=>urldecode(stripcslashes(htmlspecialchars_decode($song->song_artist))),
				'song_title'=>urldecode(stripcslashes(htmlspecialchars_decode($song->song_title))),
				'hotness'=>$hotness,
				'status'=>'published',
				'upvotes'=>$real_upvotes
				);

			$where = array('song_id'=>$song->song_id);
			$update = $this->Song_model->update($where, $update_data);
			if ($update) {
				echo 'updated song id row ' . $song->song_id . '<br />';
			}
		}
	}

	function move_mp3() {
		$where = array('song_id >'=>'0');
		$songs = $this->Song_model->get_all_songs($where);
		echo '<h1>MOVING MP3S TO USER DIRECTORY</h1>';

		foreach ($songs as $song) {

			if ($song->file_name != '') {
				if (file_exists(FCPATH . 'temp_audio/' . $song->file_name)) {
					echo '<h3>'. $song->song_title . ' - ' . $song->song_id . '</h3>';
					if (!file_exists(FCPATH . 'audio_uploads/' . $song->username)) {
						mkdir(FCPATH . 'audio_uploads/' . $song->username, 0755);
						file_put_contents(FCPATH . 'audio_uploads/' . $song->username . '/index.html', 'index.html');
					} else {
						echo '<p>directory exists.</p>'; 
					}

					$move = rename(FCPATH . 'temp_audio/' . $song->file_name, FCPATH . 'audio_uploads/' . $song->username . '/' . $song->file_name);
					if ($move) {
						echo '<p>was moved</p>';
					} else {
						echo '<p>was <b>NOT</b> moved';

					}
				}
			} 
		}

	}


	function setup_song_images() {
		$query = $this->db->get_where('hotaru_categories',array('category_id > '=>'1'));
		$categories = $query->result();

		echo '<H1>THIS MAY TAKE A WHILE</H1>';
			foreach ($categories as $hotaru) {
		echo $hotaru->category_name;
			if ($hotaru->category_image != '') {
				$image = pathinfo($hotaru->category_image);
				echo '<pre>';
				print_r($image);
				echo '</pre>';
				$fullfile = urldecode($image['basename']);
				$ext = $image['extension'];

				$file = str_replace('http://hiphopvip.com/content/plugins/category_manager/upload/uploads/', '', $fullfile);

				$filename = md5($file);

				$q = $this->db->get_where('hotaru_posts', array('post_category'=>$hotaru->category_id));
				
				$posts = $q->result();
					foreach ($posts as $post) {
					$update = array('song_image'=>$filename .".".$ext);
					$where = array('user_id'=>$post->post_author,'song_url'=>$post->post_url);
					$updated = $this->Song_model->update($where, $update);
					if ($updated) {
						$this->load->library('images');

						$this->images->uploadRemoteFile($fullfile, $filename, $post->post_url, $post->post_author);

						echo 'updated';
					} else {
						echo 'not updated';
					}
					}
				}
			}



	}

	function clear_cache() {
		$this->db->cache_delete_all();
		redirect('backend','refresh');
	}
}