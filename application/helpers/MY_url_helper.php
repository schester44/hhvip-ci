<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('current_url')) {

	function current_url()
	{
	    $CI =& get_instance();

	    $url = $CI->config->site_url($CI->uri->uri_string());
	    return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
	}

}