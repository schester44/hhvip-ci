<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('video_img')) {

    function video_img($username=NULL, $video_image=NULL, $size=NULL) {
        $CI = get_instance();

        
        if (isset($size)) {
            $size = $size . '_';
        }
        
        if (file_exists(FCPATH . 'asset_uploads/' . $username . '/videos/' . $size . $video_image)) {
            return $CI->config->item('assets_url') . 'asset_uploads/' . $username . '/videos/' . $size . $video_image;
        } else {
            return $CI->config->item('assets_url') . 'resources/img/placeholders/'.$size.'song_img.jpg';
        }
    }
}

if (!function_exists('tape_img'))
{
    function tape_img($username, $tape_url, $tape_image, $size=NULL) {
        $CI = get_instance();

        if (isset($size)) {
            $size = $size . '_';
        }

        if (file_exists(FCPATH . 'asset_uploads/' . $username . '/mixtapes/' . $tape_url . '/' . $size . $tape_image)) {
            return $CI->config->item('assets_url') . 'asset_uploads/' . $username . '/mixtapes/' . $tape_url . '/' . $size . $tape_image;
        } else {
            return $CI->config->item('assets_url') . 'resources/img/placeholders/' . $size . 'song_img.jpg';
        }
    }
}

if (!function_exists('promotedPostBg'))
{
    function promotedPostBg($username=NULL, $song_url=NULL, $song_img=NULL) {
        $CI = get_instance();

        if (empty($username)) {
            return $CI->config->item('site_url') . '/resources/img/placeholders/150_song_img.jpg';
        }

        if (file_exists(FCPATH . 'asset_uploads/' . $username . '/' . $song_url . '/64_' . $song_img)) {
            return $CI->config->item('site_url') . '/asset_uploads/' . $username . '/' . $song_url . '/64_' . $song_img;
        } else {
            return $CI->config->item('site_url') . '/resources/img/placeholders/150_song_img.jpg';
        }
    }
}


if (!function_exists('song_img'))
{
    function song_img($username=NULL, $song_url=NULL, $song_img=NULL, $size=NULL) {
        $CI = get_instance();
        if (isset($size)) {
            $size = $size . '_';
        }
        
        if (empty($username)) {
            return $CI->config->item('assets_url') . 'resources/img/placeholders/'.$size.'song_img.jpg';
        }

        if (file_exists(FCPATH . 'asset_uploads/' . $username . '/' . $song_url . '/' . $size . $song_img)) {
            return $CI->config->item('assets_url') . 'asset_uploads/' . $username . '/' . $song_url . '/' . $size . $song_img;
        } else {
            return $CI->config->item('assets_url') . 'resources/img/placeholders/'.$size.'song_img.jpg';
        }
    }
}

if (!function_exists('blog_featured_img'))
{
    function blog_featured_img($username=NULL, $post_url=NULL, $post_img=NULL, $size=NULL) {
        $CI = get_instance();
        if (isset($size)) {
            $size = $size . '_';
        }
        
        if (empty($username)) {
            return $CI->config->item('assets_url') . 'resources/img/placeholders/'.$size.'song_img.jpg';
        }

        if (file_exists(FCPATH . 'asset_uploads/' . $username . '/blog/' . $post_url . '/' . $size . $post_img)) {
            return $CI->config->item('assets_url') . 'asset_uploads/' . $username . '/blog/' . $post_url . '/' . $size . $post_img;
        } else {
            return $CI->config->item('assets_url') . 'resources/img/placeholders/'.$size.'song_img.jpg';
        }
    }
}

if (!function_exists('user_img'))
{
    function user_img($username, $size=NULL) {
        $CI = get_instance();

        if (isset($size)) {
            $size = $size . '_';
        }

        if (!file_exists(FCPATH . 'asset_uploads/' . $username . '/profile/user_avatar.jpg')) {
            return $CI->config->item('assets_url') . 'resources/img/placeholders' . '/' . $size . 'profileImg.png';
        } else {
            return $CI->config->item('assets_url') . 'asset_uploads/' . $username . '/profile/' . $size. 'user_avatar.jpg';
        }    

    }
}