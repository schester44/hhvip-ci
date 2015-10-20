<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Feed extends MY_Controller {


function __construct()
	{
		parent::__construct();

		if (!$this->uri->segment('2')) {
			redirect('feed/trending', 'refresh');
		}
	}

	public function index($sort)
	{

		$this->load->helper('xml');
		$this->output->set_content_type("application/rss+xml");


		$this->data['feed_name'] = 'hiphopVIP.com';
        $this->data['encoding'] = 'utf-8';
        $this->data['feed_url'] = 'http://www.hiphopvip.com/feed';
        $this->data['page_description'] = 'The latest trending songs in hiphop';
        $this->data['page_language'] = 'en-en';
        $this->data['creator_email'] = 'contact@hiphopvip.com';

        $allowedSortTypes = array('latest','popular','trending','trending-mixtapes','latest-mixtapes','popular-mixtapes');

		if (!$sort) {
			$sort = 'trending';
		} elseif(!in_array($sort, $allowedSortTypes)) {
			$sort = 'trending';
		} else {
			$sort = $sort;
		}

		$sortData = $this->sorting->prepareList('songs',$sort);
		$where = $sortData['where'];

		$list = $this->sorting->get_list('songs', $sort, 10,0);

        $this->data['posts'] = $list;

        $this->load->view('feed/index', $this->data);
		
	}
}

/* End of file feed.php */
/* Location: ./application/controllers/feed.php */