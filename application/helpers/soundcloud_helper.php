<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('_curlSoundcloud'))
{

	function _curlSoundcloud($resource, $id) {
        
        $CI = get_instance();

		$curl = curl_init('https://api.soundcloud.com/'.$resource.'/'.$id.'.json?client_id='.$CI->config->item('soundcloud_client_id'));

		  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		  curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		  $return = curl_exec($curl);
		  curl_close($curl);
  		return $return;
	}

}

if (!function_exists('_resolveSoundcloud'))
{

	function _resolveSoundcloud($url) {
        
        $CI = get_instance();

		if(strpos($url, "http://") === false && strpos($url, "https://")) {
			$url = "http://" . $data->url;
		}

		$curl = curl_init('https://api.soundcloud.com/resolve.json?url='.$url.'&client_id='.$CI->config->item('soundcloud_client_id'));

		  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		  curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		  $return = curl_exec($curl);
		  curl_close($curl);
  		return $return;
	}

}