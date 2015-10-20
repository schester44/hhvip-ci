<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('url_slug')) {
    function url_slug($original){
        #convert case to lower
        $str = strtolower($original);
        //convert dollar sign to S
        #remove special characters

        str_replace(array(".",",","'"), "" , $str);
        str_replace(array("'s","$"), "s" , $str);
        $str = preg_replace('/[^a-zA-Z0-9]/i',' ', $str);
        #remove white space characters from both side
        $str = trim($str);
        #remove double or more space repeats between words chunk
        $str = preg_replace('/\s+/', ' ', $str);
        #fill spaces with hyphens
        $str = preg_replace('/\s+/', '-', $str);
        # check if final string contains data or not  
        $str = (empty($str) || $str == "") ? substr(md5($original), 0, 12) : $str;
        
        return $str;
    }
}

if (!function_exists('clean_name')) {
    function clean_name($original){
        #convert case to lower
        $str = strtolower($original);
        //convert dollar sign to S
        #remove special characters

        str_replace(array(".",",","'"), "" , $str);
        str_replace(array("'s","$"), "s" , $str);
        $str = preg_replace('/[^a-zA-Z0-9]/i',' ', $str);
        #remove white space characters from both side
        $str = trim($str);
        #remove double or more space repeats between words chunk
        $str = preg_replace('/\s+/', ' ', $str);
                        
        return $str;
    }
}

if (!function_exists('blog_url')) {
    function blog_url($category, $url) {
        return  base_url('b/' . $category . '/' . $url);
    }
}

if (!function_exists('file_slug')){
    function file_slug($original){
        $str = str_replace(array(".",",","'"), "" , $original);
        $str = str_replace(array("'s","$"), "s" , $str);
        $str = preg_replace('/[^a-zA-Z0-9]/i',' ', $str);
        #remove white space characters from both side
        $str = trim($str);
        #remove double or more space repeats between words chunk
        $str = preg_replace('/\s+/', ' ', $str);
        #fill spaces with hyphens
        $str = preg_replace('/\s+/', '-', $str);
        return $str;
    }
}

if (!function_exists('addhttp')){
    function addhttp($url){
       if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
          $url = "http://" . $url;
       }
       return $url;
    }
}

/*
* Removes the _HIPHOPVIP.COM.mp3 from FileNames. Used in Views
*/
if (!function_exists('cleanFileName'))
{
    function cleanFileName($name){
        $replace = "_";
        $pattern = "/([[:alnum:]_\.-]*)/";

        $newName = str_replace(str_split(preg_replace($pattern,$replace,$name)),$replace,$name);
        $repVIP = str_replace('_HIPHOPVIP.COM.mp3', ' ', $newName);
        $clean = str_replace('_', ' ', $repVIP);
        return $name;
    }
}