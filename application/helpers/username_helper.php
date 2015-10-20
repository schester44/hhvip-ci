<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('username'))
{
 	function username($user_id){
 				$CI = get_instance();
                $user = $CI->User_model->get_user($user_id);

                if ($user) {
                        $username = $user->username;
                        return $username;
                } else {
                        return $user_id;
                }
	}
}