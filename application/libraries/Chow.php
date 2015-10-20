<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Chow {

    private $CI;

    function __construct()
    {
        $this->CI =& get_instance(); 
    }

    public function get($apiUrl) {
		$list = file_get_contents($apiUrl);

		$decode = json_decode($list);
		foreach ($decode as $post) {
			 $array = array();
    		preg_match('/src="(.*?)"/i', $post->description, $images);
    		
    		$data['image'] 		= $images[1];
   			$data['title'] 		= $post->title;
   			$data['link'] 		= $post->link;
   			$data['site_title'] = $post->site_title;
   			$data['site_link'] 	= $post->site_url;
   			$img[] = $data;
		}
		return $img;
	}

  public function mixtapes($limit) {
    $tapes = file_get_contents('http://rapchow.com/api/allTapes/format/json?limit='.$limit);
    return json_decode($tapes);
  }
}