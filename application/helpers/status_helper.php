<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('status_message'))
{
 	function status_message($for, $status){
 		//$for = playlist,song,mixtape
 				$CI = get_instance();
       $CI->lang->load('song');

		switch ($status) {
			case 'incomplete':
				return $CI->lang->line($for . '_incomplete');
				break;

			case 'published':
				return false;
				break;

			case 'copyright':
				return $CI->lang->line($for . '_copyright');
				
				break;

			case 'removed':
				return $CI->lang->line($for . '_removed');
				break;


			case 'deleted':
				return $CI->lang->line($for . '_deleted');
				break;
		}
	}
}