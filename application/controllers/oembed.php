<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Oembed extends MY_Controller {

	public function index()
	{
		if (!$this->input->get('url')) {
			redirect('/','refresh');
		}
		$title = substr($this->input->get('url'), strpos($this->input->get('url'), 'song/') + 5);
		$username = substr($title, 0, strpos($title, '/'));
		$url_slug = substr($title, strpos($title, '/') + 1);

		$user = $this->User_model->get_user_info($username);



		if (!$user) {
			die('<strong>ERROR FETCHING DATA :: ENSURE YOUR URL IS CORRECT AND THERE IS A VALID USER.</strong>');
		}

		$song = $this->Song_model->get_song_where(array('username'=>$username,'song_url'=>$url_slug));

		if (!$song) {
			die('<strong>ERROR FETCHING DATA :: ENSURE YOUR URL IS CORRECT AND THERE IS A VALID SONG.</strong>');
			
		}

        $this->output->set_header('Content-Type: application/json; charset=utf-8');
        
        $output_array = array(
        	'type'=>'rich',
        	'title'=>$song->song_title,
        	'description'=>$song->song_description,
        	'version'=>'1.0',
        	'provider_name'=>'hiphopVIP',
        	'provider_url'=>base_url(),
        	'html'=>"<iframe src='".base_url("embed/1/".$username."/".$url_slug)."' scrolling='no' width='100%' height='100' scrollbars='no' frameborder='0'><\iframe>",
        	'width'=>'100%',
        	'height'=>'100',
        	'thumbnail_url'=>song_img($song->username, $song->song_url, $song->song_image),
        	'url'=>base_url('song/'.$username.'/'.$url_slug),
        	'album'=>$song->album,
        	'producer'=>$song->song_producer,
        	'buy_link'=>$song->buy_link,
        	'author_name'=>$song->song_artist,
        	'author_url'=>base_url('u/'.$username)
        	);


		$this->output->set_output(json_encode($output_array));


	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */