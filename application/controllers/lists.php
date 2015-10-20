<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Lists extends MY_Controller {


	public function index() {
		$this->_render('lists/index', $renderData='');
	}

	public function song_list($sortType, $popSort="") {	

		if ($this->uri->segment('2') === 'popular' && !$this->uri->segment('3')) {
			redirect('songs/popular/week');
		}
	
		$this->load->library('pagination'); 

		$sortData = $this->sorting->prepareList('songs',$sortType);
		$where = $sortData['where'];

		$config['per_page'] = 18; //how many results to return from the query
		$config['uri_segment'] = 3;
		$config['base_url'] = base_url('songs/' . $sortType);
		$page = ($this->uri->segment(3) ? $this->uri->segment(3) : 0);

		if ($popSort && !is_numeric($popSort)) {
			$where = $this->sorting->popularSort($popSort);

			$config['uri_segment'] = 4;
			$config['base_url'] = base_url('songs/' . $sortType . '/' . $popSort);
			$page = ($this->uri->segment(4) ? $this->uri->segment(4) : 0);
		}

		$config['total_rows'] = $this->cache->model('Song_model', 'song_count', array($where), 300); // keep for 5 minutes


		$list = $this->cache->library('sorting', 'get_list', array('songs',$sortType, $config['per_page'], $page, $popSort), 300); // keep for 5 minutes
		$curTotalRows = count($list);

		$this->pagination->initialize($config); 
		$this->data['pagination'] = $this->pagination->create_links();
		$results = 'Showing '. $curTotalRows . ' of '.$this->pagination->total_rows;
		$this->data['result_nums'] = $results;

		if ($this->uri->segment(2) == 'popular') {
			if ($this->uri->segment(3) != 'today' && $this->uri->segment('3') != 'all') {
				$this->data['song_list_type'] = 'This ' . ucfirst($this->uri->segment(3));
			} else {
				$this->data['song_list_type'] = ucfirst($this->uri->segment(3));

				if ($this->uri->segment('3') == 'all') {
					$this->data['song_list_type'] = 'All Time';
                }
            }
		}

			/*
			VOTING BUTTONS, COLOR THEM WHEN LOGGED IN
			 */
			if ($this->ion_auth->logged_in() && !empty($list)) {
				$voteIds  = array();
				foreach ($list as $key => $song) {
					$voteIds[] = $song->song_id;
				}

				$votes = $this->Vote_model->get_where_in('vote_song_id,vote_rating', $voteIds, $this->ion_auth->user()->row()->id);
				$this->data['userVotes'] = json_encode($votes);

			} else {
				$this->data['userVotes'] = "";
			}
			


		$this->data['promoted']			= $this->cache->library('sorting', 'get_list', array('songs','promoted',5), 900);
		$this->data['subSection_bigav'] = $this->cache->library('sorting', 'get_list', array('songs','popular',5), 300); // keep for 5 minutes		
		$this->data['page_title'] = $this->cache->library('sorting', 'list_title', array('songs',$sortType), 300); // keep for 5 minutes
		
		$this->data['songs'] = $list;
		$this->data['title'] = ucfirst($sortType) . ' Music on ' . SITE_TITLE;
		$this->data['meta_name'] = array('description'=>$this->lang->line('meta_description'),'twitter:card'=>'summary_large_image','twitter:domain'=>base_url(),'twitter:site'=> $this->lang->line('meta_twitter'),'twitter:title'=> $this->lang->line('meta_title'),'twitter:creator'=>$this->lang->line('meta_twitter'),'twitter:description'=>$this->lang->line('meta_description'),'twitter:image:src'=>base_url('resources/img/placeholders/song_img.jpg'));
		$this->data['meta_prop'] = array('og:title'=> $this->lang->line('meta_title'),'og:url'=> base_url('/'),'og:site_name'=> 'hiphopVIP','og:description'=> $this->lang->line('meta_description'));
		
		$this->data['coreJS'] = array('lists.js');
		$this->_render('lists/song_list', $this->data);
	}

	public function mixtape_list($sortType, $popSort="") {
		$this->load->library('pagination'); 
		$this->load->model('Mixtape_model');

		$sortData 	= $this->sorting->prepareList('mixtapes',$sortType);
		$where		= $sortData['where'];

		$config['total_rows'] 	= $this->Mixtape_model->mixtape_count($where);
		$config['per_page']		= 25;
		$config['uri_segment'] = 3;
		$config['base_url'] = base_url('mixtapes/' . $sortType);
		$page = ($this->uri->segment(3) ? $this->uri->segment(3) : 0);


		/* popular sorting */
		if ($popSort && !is_numeric($popSort)) {
			$where = $this->sorting->popularSort($popSort);
			$config['total_rows'] = $this->cache->model('Mixtape_model', 'mixtape_count', array($where), 300); // keep for 5 minutes
			$config['uri_segment'] = 4;
			$config['base_url'] = base_url('mixtapes/' . $sortType . '/' . $popSort);
			$page = ($this->uri->segment(4) ? $this->uri->segment(4) : 0);
		}
		
		$list = $this->cache->library('sorting', 'get_list', array('mixtapes',$sortType, $config['per_page'], $page, $popSort), 300); // keep for 5 minutes

		$this->pagination->initialize($config); 
		$curTotalRows = count($list);
		
		$this->data['pagination'] = $this->pagination->create_links();
		$results = 'Showing '.$curTotalRows. ' of '.$this->pagination->total_rows;
		$this->data['result_nums'] = $results;


		$this->data['subSection_bigav'] = $this->cache->library('sorting', 'get_list', array('mixtapes','popular',5), 300); // keep for 5 minutes		
		$this->data['page_title'] = $this->cache->library('sorting', 'list_title', array('mixtapes',$sortType), 300); // keep for 5 minutes
		
		$this->data['mixtape'] = $list;
		$this->data['title'] = ucfirst($sortType) . ' Mixtapes Showing '.($this->pagination->cur_page * $this->pagination->per_page). ' of '.$this->pagination->total_rows.' | ' . SITE_TITLE;
		
		$this->_render('lists/mixtape_list', $this->data);
	}

	public function playlist_list($sortType = NULL, $popSort = NULL) {
		$this->load->library('pagination'); 
		$this->load->model('Playlist_model');

		$sortData 	= $this->sorting->prepareList('playlists',$sortType);
		$where		= array('status'=>'public', 'track_count >'=>'2');
		$config['total_rows'] 	= $this->cache->model('Playlist_model', 'count', array($where), 900);
		$config['per_page']		= 20;
		$config['uri_segment'] = 3;
		$config['base_url'] = base_url('playlists/' . $sortType);
		$page = ($this->uri->segment(3) ? $this->uri->segment(3) : 0);


		$playlists = $this->cache->model('Playlist_model', 'get', array($where, 'id DESC', $config['per_page'], $page), 900);


		$this->pagination->initialize($config); 
		$curTotalRows = count($playlists);
		
		$this->data['pagination'] = $this->pagination->create_links();
		$results = 'Showing '.$curTotalRows. ' of '.$this->pagination->total_rows;
		$this->data['result_nums'] = $results;


		$this->data['page_title'] = 'Latest Playlists';
		
		$this->data['playlists'] = $playlists;
		$this->data['title'] = ucfirst($sortType) . ' Playlists | ' . SITE_TITLE;
		

        $this->_render('playlist/lists/main',$this->data);
	}

}