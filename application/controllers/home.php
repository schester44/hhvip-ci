<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

	public function index()
	{		
		$this->load->model('Blog_model');

		$this->data['latest_news'] 		= $this->cache->model('Blog_model', 'get_posts', array(array('status'=>'published','date_published >'=>date(strtotime("-7 days")),'access'=>'public'), 'id DESC', 10, 0 ), 300);
		$this->data['latest_news_count'] = count($this->data['latest_news']);
		
		$this->data['promoted']			= $this->cache->library('sorting', 'get_list', array('songs','promoted',5));
		$this->data['popular'] 			= $this->cache->library('sorting', 'get_list', array('songs','popular',8));
		$this->data['trending'] 		= $this->cache->library('sorting', 'get_list', array('songs','trending',20));

		$lists = [];
		$listType = array('trending', 'popular');

		foreach ($listType as $key => $type) {
			if ($this->data[$type]) {
				$lists[$type] = array('title'=>$type,'entry'=>$this->data[$type]);
			}
		}


		/*
		VOTING BUTTONS, COLOR THEM WHEN LOGGED IN
		 */
		if ($this->ion_auth->logged_in()) {
			$voteIds  = array();
			foreach ($lists as $key => $song) {
				foreach ($song['entry'] as $key => $song) {
					$voteIds[] = $song->song_id;
				}	
			}

			$votes = $this->Vote_model->get_where_in('vote_song_id,vote_rating', $voteIds, $this->ion_auth->user()->row()->id);
			$this->data['userVotes'] = json_encode($votes);

		} else {
			$this->data['userVotes'] = "";
		}
			

		$this->data['lists'] = $lists;

		$this->data['meta_name'] = array('description'=>$this->lang->line('meta_description'),'twitter:card'=>'summary_large_image','twitter:domain'=>base_url(),'twitter:site'=> $this->lang->line('meta_twitter'),'twitter:title'=> $this->lang->line('meta_title'),'twitter:creator'=>$this->lang->line('meta_twitter'),'twitter:description'=>$this->lang->line('meta_description'),'twitter:image:src'=>base_url('resources/img/placeholders/song_img.jpg'));
		$this->data['meta_prop'] = array('og:title'=> $this->lang->line('meta_title'),'og:url'=> base_url('/'),'og:site_name'=> 'hiphopVIP','og:description'=> $this->lang->line('meta_description'));
		
		$this->data['coreJS'] = array('lists.js');
		$this->data['vendorJS'] = array('news-ticker/jquery.easy-ticker.js');
		$this->_render('main/index',$this->data);
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */