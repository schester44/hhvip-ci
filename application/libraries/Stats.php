<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Stats {

    private $CI;

    function __construct()
    {
        $this->CI =& get_instance(); 
        $this->CI->load->model('Stats_model');
    }

/**
 * main stats logging function
 * @param  array $a - minimum params listed below
 * type
 * track_id
 * list_id (if playlist)
 * 
 * @return true or false
 */
  function log($a) {

    $tid = (isset($a['track_id']) ? $a['track_id'] : NULL);    
    $lid = (isset($a['list_id']) ? $a['list_id'] : NULL);    
    $event = (isset($a['event']) ? $a['event'] : NULL);    
    $user_id = (($this->CI->ion_auth->logged_in()) ? $this->CI->ion_auth->user()->row()->id : 0);
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $date = time();
    $time_limit = $date - 30;

    $log_data = array(
          'track_id' => $tid,
          'event' => $event,
          'user_id' => $user_id,
          'user_ip' => $user_ip,
          'date'  => $date,
        );

    $existing_record = $this->CI->Stats_model->get($a['type'], array(
      'event'=>$event,
      'track_id'=>$tid,
      'user_id'=>$user_id,
      'user_ip'=>$user_ip
      ), 'id DESC', 1, 0);

  if ($a['type'] == 'playlist_track') {
    $log_data = array(
        'list_id' => $lid,
        'track_id' => $tid,
        'event' => $event,
        'user_id' => $user_id,
        'user_ip' => $user_ip,
        'date'  => $date,
      );

    $existing_record = $this->CI->Stats_model->get($a['type'], array(
      'list_id' => $lid,
      'event'=>$event,
      'track_id'=>$tid,
      'user_id'=>$user_id,
      'user_ip'=>$user_ip
      ), 'id DESC', 1, 0);

  }
  
  if ($a['type'] == 'playlist') {

      $existing_record = $this->CI->Stats_model->get($a['type'], array(
      'list_id' => $lid,
      'event'=>$event,
      'user_id'=>$user_id,
      'user_ip'=>$user_ip
      ), 'id DESC', 1, 0);
      
    $log_data = array(
        'list_id' => $lid,
        'event' => $event,
        'user_id' => $user_id,
        'user_ip' => $user_ip,
        'date'  => $date,
      );
  
  }  

    if (!empty($existing_record)) {
      if ($time_limit - $existing_record[0]->date >= 0) {
        //existing record exists but is old enough to log a new record
        if ($this->CI->Stats_model->add($a['type'], $log_data)) {
          return true;
        } else {
          return false;
        }
      } else {
        //too soon junior (time limit has not expired)
       return false;
      }
    } else {
      //no existing record exists, so log 
      if ($this->CI->Stats_model->add($a['type'], $log_data)) {
        return true;
      } else {
        return false;
      }
    }
   

  }

  function get($type, $where) {
    if (isset($type) && isset($where)) {
     return $this->CI->Stats_model->get($type, $where);
    } else {
      return false;
    }
  }
}