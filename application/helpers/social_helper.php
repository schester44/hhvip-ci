<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('user_follows'))
{
        function user_follows($user){
        	$CI = get_instance();
				//if user is logged in, check to see if the user follows the users profile page
			if ($CI->ion_auth->logged_in()) {
				$follows = $CI->Social_model->get_follow(array('follower_id'=>$CI->ion_auth->user()->row()->id,'following_id'=>$user));
				if ($follows) {
					return true;
				} else {
					return false;
				}
			}

        }
}