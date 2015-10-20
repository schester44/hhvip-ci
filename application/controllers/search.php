<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends MY_Controller {

		function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->data['promoted']			= $this->cache->library('sorting', 'get_list', array('songs','promoted',5));
		
	}

	public function index() {
		if (!$this->input->get('q')) {
			$this->data['meta_name'] = array(
				'description'=>'Find the music youre looking for in one of the largest hip hop databases on the net',
				'twitter:card'=>'summary_large_image',
				'twitter:domain'=>base_url(),
				'twitter:site'=> $this->lang->line('meta_twitter'),
				'twitter:title'=>$this->lang->line('meta_title'),
				'twitter:creator'=>$this->lang->line('meta_twitter'),
				'twitter:description'=>'Find the music youre looking for in one of the largest hip hop databases on the net',
				'twitter:image:src'=>'http://ci.dev/resources/img/placeholders/song_img.jpg'
				);

			$this->data['meta_prop'] = array(
				'og:title'=> 'Find the music you want on ' . $this->lang->line('meta_title'),
				'og:url'=> base_url('Search'),
				'og:site_name'=> 'hiphopVIP',
				'og:image'=>base_url('resources/img/placeholders/song_img.jpg'),
				'og:description'=> 'Find the music youre looking for in one of the largest hip hop databases on the net'
				);
			
			$this->data['songs'] 			= $this->cache->library('sorting', 'get_list', array('songs','latest',8));
			$this->data['coreJS'] = array('lists.js');


								/*
			VOTING BUTTONS, COLOR THEM WHEN LOGGED IN
			 */
			if ($this->ion_auth->logged_in()) {
				$voteIds  = array();
				foreach ($this->data['songs'] as $key => $song) {
					$voteIds[] = $song->song_id;
				}

				$votes = $this->Vote_model->get_where_in('vote_song_id,vote_rating', $voteIds, $this->ion_auth->user()->row()->id);
				$this->data['userVotes'] = json_encode($votes);

			} else {
				$this->data['userVotes'] = "";
			}


			$this->data['title'] = 'Search | ' . SITE_TITLE;
			$this->_render('search/index', $this->data);
		} else {
			$this->load->library('pagination');
			$this->load->library('form_validation');

			$searchPar = $this->input->get('q');			
			$this->data['search'] = $searchPar;
			$this->data['searchPar'] = $searchPar;

			$this->form_validation->set_rules('q', 'Song', 'trim|xss_clean|disallowed_words|min_length[2]');

			$offset = $this->input->get('per_page');
			$config['per_page'] = 25;
			$resultsWhere = array('status'=>'published');			
			$order_by = 'song_id DESC';

			if ($this->input->get('sort') !== '') {
				$sort = $this->input->get('sort');
				$allowed = array('popular','latest','trending');

				if(in_array($sort, $allowed)) {
					if ($sort == 'popular') {
						$order_by = 'upvotes DESC';
					} elseif ($sort == 'trending') {
						$order_by = 'hotness DESC';
					}
				}
			}	

			$this->data['results'] = $this->cache->model('Song_model','search',array($resultsWhere, $searchPar, $config['per_page'], $offset, $order_by), 900);
			$this->data['totalResults'] = $this->cache->model('Song_model','countSearch',array($resultsWhere, $searchPar), 900);


			if ($this->input->get('only') !== '') {
				$only = $this->input->get('only');
				$allowed = array('artist');
				if (in_array($only, $allowed)) {
					$resultsWhere = array('song_artist'=>$searchPar,'status'=>'published');
					$this->data['totalResults'] = $this->cache->model('Song_model','song_count',array($resultsWhere), 900);
					$this->data['results'] = $this->cache->model('Song_model','get',array($resultsWhere, $order_by, $config['per_page'], $offset), 900);
				}
			}

			$config['base_url'] = base_url() . '/search?q=' . $searchPar;
			
			$config['full_tag_open'] = '<ul class="pagination pagination-lg">';
			$config['full_tag_close'] = '</ul>';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['next_link'] = 'NEXT &raquo;';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['prev_link'] = '&laquo; PREVIOUS';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li class="active"><a href="#">';
			$config['cur_tag_close'] = '</a></li>';
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			
			$config['uri_segment'] = 4;
			$config['num_links'] = 2;
			$config['page_query_string'] = TRUE;

			$config['total_rows'] = $this->data['totalResults'];

			$this->pagination->initialize($config);
		
			$this->data['noResults'] = ($this->data['totalResults'] === 0) ? 'No Results Found' : NULL;
			$this->data['pagination'] = $this->pagination->create_links();

			if ($config['total_rows'] <= 3) {
				$this->data['subSection_title'] = 'Try One Of These '.$this->sorting->list_title('songs','latest');
				$this->data['subSection_list'] = $this->cache->library('sorting', 'get_list', array('songs','latest', 20));
			} else {
				$this->data['subSection_title'] = NULL;
				$this->data['subSection_list'] = NULL;
			}

		$this->data['meta_name'] = array(
			'description'=>'Find the music youre looking for in one of the largest hip hop databases on the net',
			'twitter:card'=>'summary_large_image',
			'twitter:domain'=>base_url(),
			'twitter:site'=> '@hiphopvip1',
			'twitter:title'=>'Find the music you want',
			'twitter:creator'=>'@hiphopvip1',
			'twitter:description'=>'Find the music youre looking for in one of the largest hip hop databases on the net',
			'twitter:image:src'=>'http://ci.dev/resources/img/placeholders/song_img.jpg'
			);

		$this->data['meta_prop'] = array(
			'og:title'=> 'Find the music youre looking for',
			'og:url'=> base_url('search'),
			'og:site_name'=> 'hiphopVIP',
			'og:image'=>'http://ci.dev/resources/img/placeholders/song_img.jpg',
			'og:description'=> 'Find the music youre looking for in one of the largest hip hop databases on the net'
			);

			if (($this->pagination->cur_page * $this->pagination->per_page == 0) || ($this->pagination->cur_page * $this->pagination->per_page > $this->pagination->total_rows)) {
					$showing_page = $this->pagination->total_rows;
			} else {
					$showing_page = $this->pagination->cur_page * $this->pagination->per_page;
			}


								/*
			VOTING BUTTONS, COLOR THEM WHEN LOGGED IN
			 */
			if ($this->ion_auth->logged_in()) {
				$voteIds  = array();
				foreach ($this->data['results'] as $key => $song) {
					$voteIds[] = $song->song_id;
				}

				$votes = $this->Vote_model->get_where_in('vote_song_id,vote_rating', $voteIds, $this->ion_auth->user()->row()->id);
				$this->data['userVotes'] = json_encode($votes);

			} else {
				$this->data['userVotes'] = "";
			}
			
			//promoted song
			$promoted = $this->cache->library('sorting', 'get_list', array('songs','promoted',5));
			if ($promoted) {
				shuffle($promoted);
				$promoted = $promoted[0];
				$this->data['promo'] = $promoted;
			}
			
			$this->data['search_title'] = ($this->input->get('q') ? htmlspecialchars(ucwords($this->input->get('q')), ENT_QUOTES) : htmlspecialchars(ucwords($this->uri->segment('3')), ENT_QUOTES));
			$this->data['search_title_url'] = str_replace(array(' ', '+'), '%20', $this->input->get('q'));
			$results = 'Showing '.($showing_page). ' of '.$this->pagination->total_rows;
			$this->data['title'] = htmlspecialchars('Search - ' . ucwords($this->data['search_title']) . ' | '. SITE_TITLE, ENT_QUOTES);
			$this->data['result_nums'] = $results;
			$this->data['coreJS'] = array('lists.js');

			$this->data['total_rows'] = $this->pagination->total_rows;

			$this->_render('search/results', $this->data);
		}
	}

	public function songs() {


		$this->load->library('pagination');
		$offset = $this->uri->segment(4);

		$config['per_page'] = 25;

		if ($this->input->post('song') || $this->uri->segment(3)) {
			if (!$this->input->post('song')) {
				$_POST['uri'] = $this->uri->segment(3);
				$searchPar = str_replace('%20', ' ', $this->uri->segment(3));
			} else {
				$searchPar = $this->input->post('song', TRUE);
			}

			$this->data['searchPar'] = $searchPar;
			$this->load->library('form_validation');

			$this->form_validation->set_rules('song', 'Song', 'trim|xss_clean|song|disallowed_words|min_length[2]');
			$this->form_validation->set_rules('uri', 'Song', 'trim|xss_clean|song|disallowed_words|min_length[2]');

			if ($this->form_validation->run() == FALSE && !$this->uri->segment(3)) {

				$this->session->set_flashdata('message', 'Please revise your search.<br />A minimum of 2 characters required.');
				redirect('search', 'refresh');

			} else {
				$this->data['search'] = $searchPar;

				$resultsWhere = array('status'=>'published');
				
				//cached DB model
				$this->data['results'] = $this->cache->model('Song_model','search',array($resultsWhere, $searchPar, $config['per_page'], $offset, 'song_id DESC'));
				//$this->data['results'] = $this->Song_model->search($searchPar, $config['per_page'], $offset, 'song_id DESC');

				$config['base_url'] = base_url() . '/search/songs/' . $searchPar . '/';
				
				$config['full_tag_open'] = '<ul class="pagination pagination-lg">';
				$config['full_tag_close'] = '</ul>';
				$config['num_tag_open'] = '<li>';
				$config['num_tag_close'] = '</li>';
				$config['next_link'] = 'NEXT &raquo;';
				$config['next_tag_open'] = '<li>';
				$config['next_tag_close'] = '</li>';
				$config['prev_link'] = '&laquo; PREVIOUS';
				$config['prev_tag_open'] = '<li>';
				$config['prev_tag_close'] = '</li>';
				$config['cur_tag_open'] = '<li class="active"><a href="#">';
				$config['cur_tag_close'] = '</a></li>';
				$config['first_tag_open'] = '<li>';
				$config['first_tag_close'] = '</li>';
				$config['last_tag_open'] = '<li>';
				$config['last_tag_close'] = '</li>';
				
				$config['uri_segment'] = 4;
				$config['num_links'] = 2;

			$this->data['totalResults'] = $this->Song_model->countSearch($resultsWhere, $searchPar);
				
				$config['total_rows'] = $this->data['totalResults'];

			$this->pagination->initialize($config);
			
			$this->data['noResults'] = ($this->data['totalResults'] === 0) ? 'No Results Found' : NULL;

			$this->data['pagination'] = $this->pagination->create_links();

				if ($config['total_rows'] <= 3) {
					$this->data['subSection_title'] = 'Checkout these '.$this->sorting->list_title('songs','latest');
					$this->data['subSection_bigav'] = $this->cache->library('sorting', 'get_list', array('songs','latest', 20));
				} else {
					$this->data['subSection_title'] = NULL;
					$this->data['subSection_bigav'] = NULL;
				}

			$this->data['meta_name'] = array(
				'description'=>'Find the music youre looking for in one of the largest hip hop databases on the net',
				'twitter:card'=>'summary_large_image',
				'twitter:domain'=>base_url(),
				'twitter:site'=> '@hiphopvip1',
				'twitter:title'=>'Find the music you want',
				'twitter:creator'=>'@hiphopvip1',
				'twitter:description'=>'Find the music youre looking for in one of the largest hip hop databases on the net',
				'twitter:image:src'=>'http://ci.dev/resources/img/placeholders/song_img.jpg'
				);

			$this->data['meta_prop'] = array(
				'og:title'=> 'Find the music youre looking for',
				'og:url'=> base_url('search'),
				'og:site_name'=> 'hiphopVIP',
				'og:image'=>'http://ci.dev/resources/img/placeholders/song_img.jpg',
				'og:description'=> 'Find the music youre looking for in one of the largest hip hop databases on the net'
				);

				if (($this->pagination->cur_page * $this->pagination->per_page == 0) || ($this->pagination->cur_page * $this->pagination->per_page > $this->pagination->total_rows)) {
						$showing_page = $this->pagination->total_rows;
				} else {
						$showing_page = $this->pagination->cur_page * $this->pagination->per_page;
				}


			$this->data['search_title'] = ($this->input->get('q') ? htmlspecialchars(ucwords($this->input->get('q')), ENT_QUOTES) : htmlspecialchars(ucwords($this->uri->segment('3')), ENT_QUOTES));
			$this->data['search_title_url'] = str_replace(' ', '%20', $this->data['search_title']);
		
			$results = 'Showing '.($showing_page). ' of '.$this->pagination->total_rows;
			$this->data['title'] = htmlspecialchars('Search Results ' .$results. '| ' . SITE_TITLE, ENT_QUOTES);
			$this->data['result_nums'] = $results;
			$this->_render('search/results', $this->data);


			}


		} else {
			redirect('search/index', 'refresh');
		}

	}
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */