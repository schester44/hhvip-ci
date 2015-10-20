<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('check_ssl'))
{
    function check_ssl()
    {
        $CI =& get_instance();
        $class = $CI->router->fetch_class();       
        $ssl = array('auth','ajax','backend','errors','feed','lists','manage','mixtapes','oembed','player','playlists','search','site','songs','upload','videos','votes');        
        

        if(in_array($class, $ssl) || !$CI->uri->segment('1'))
        {
            force_ssl();
        }
        else
        {
            unforce_ssl();
        }
    }
}

if (!function_exists('force_ssl'))
{
    function force_ssl()
    {        
        $CI =& get_instance();
        $CI->config->set_item('base_url', str_replace('http://', 'https://', config_item('base_url'))); 
        if ($_SERVER['SERVER_PORT'] != 443) // it will loop if you change it to !==
        {
            parse_str($_SERVER['QUERY_STRING'], $get);
            if($CI->uri->segment('1') === 'search' && isset($get['q']) && !empty($get['q'])) {
                //if theres a search result, keep the query string
                //could be expanded to include all GET queries
                redirect($CI->uri->uri_string() . '?q=' . $get['q']);
            } else {
                redirect($CI->uri->uri_string());
            }
        }
    }
}

if (!function_exists('unforce_ssl'))
{
    function unforce_ssl()
    {
        $CI =& get_instance();
        $CI->config->set_item('base_url', str_replace('https://', 'http://', config_item('base_url')));
        if ($_SERVER['SERVER_PORT'] != 80) // it will loop if you change it to !==
        {
            redirect($CI->uri->uri_string());
        }   
    }
}