<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage extends MY_Controller {


	function __construct()
	{
		parent::__construct();

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		}

			$this->data['user'] = $this->ion_auth->user()->row();
	}

	public function index()
	{		
		redirect('u/'.$this->ion_auth->user()->row()->username,'refresh');
	}

		public function stats()
	{		
		$this->data['songs'] = $this->Song_model->get_all_songs($this->ion_auth->user()->row()->id);

   $this->data['title'] = 'View Stats | ' . $this->lang->line('meta_title');

		$this->_render('manage/stats',$this->data);
	}

  public function videos() {
    if (!$this->ion_auth->logged_in()) {
      redirect('auth/login');
    }

    $user = $this->ion_auth->user()->row();

    $this->load->library('pagination');
    $this->load->model('Video_model');

      $where = array('user_id'=>$user->id);
      $order = 'id DESC';
      
      $config['uri_segment'] = 3;
      $config['base_url'] = base_url('manage/videos/');
      $config['total_rows'] = $this->Video_model->video_count($where);
      $config['per_page'] = 5;
      $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

      $videos = $this->Video_model->get_videos($where, $order, $config['per_page'], $page);
      
      $this->pagination->initialize($config); 
      $this->data['pagination'] = $this->pagination->create_links();

      $this->data['videos'] = $videos;

      $this->load->helper('form');
      $this->data['form_attributes'] = array('id'=>'myForm');

      $this->data['title'] = 'Manage Videos | ' . $this->lang->line('meta_title');
      $this->_render('manage/videos', $this->data);


  }
	
	public function songs() {
        
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }

      $user = $this->ion_auth->user()->row();

      $this->load->library('pagination'); 
      $this->load->helper('form');

      $where = array('user_id'=>$user->id);
      $order = 'song_id DESC';
      
      $config['uri_segment'] = 3;
      $config['base_url'] = base_url('manage/songs/');
      $config['total_rows'] = $this->Song_model->song_count($where);
      $config['per_page'] = 5;

      $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

      $songs = $this->Song_model->get_all_songs($where, $order, $config['per_page'], $page);
          
      $this->pagination->initialize($config); 

      $this->data['pagination'] = $this->pagination->create_links();
      $this->data['songs'] = $songs;
      $this->data['form_attributes'] = array('id'=>'myForm');
      $this->data['title'] = 'Manage Songs | ' . $this->lang->line('meta_title');
      
      $this->_render('manage/songs', $this->data);
    }

  public function mixtapes(){
     if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
            redirect('auth/login','refresh');
        }

        $this->load->model('Mixtape_model');

      $user = $this->ion_auth->user()->row();

      $this->load->library('pagination'); 
      $this->load->helper('form');

      $where = array('mixtapes.user_id'=>$user->id);
      $order = 'id DESC';

      $config['uri_segment'] = 3;
      $config['base_url'] = base_url('manage/mixtapes/');
      $config['total_rows'] = $this->Mixtape_model->mixtape_count($where);
      $config['per_page'] = 5;

      $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

      $tapes = $this->Mixtape_model->get_mixtapes($where, $order, $config['per_page'], $page);
          
      $this->pagination->initialize($config); 

      $this->data['pagination'] = $this->pagination->create_links();
      $this->data['mixtapes'] = $tapes;
      $this->data['form_attributes'] = array('id'=>'myForm');
      $this->data['title'] = 'Manage Mixtapes | ' . $this->lang->line('meta_title');

    $this->_render('manage/mixtapes', $this->data);
  }

public function playlists(){
     if (!$this->ion_auth->logged_in()) {
            redirect('auth/login','refresh');
        }

        $this->load->model('Playlist_model');

      $user = $this->ion_auth->user()->row();

      $this->load->library('pagination'); 
      $this->load->helper('form');

      $where = array('playlists.user_id'=>$user->id);
      $order = 'id DESC';

      $config['uri_segment'] = 3;
      $config['base_url'] = base_url('manage/playlists/');
      $config['total_rows'] = $this->cache->model('Playlist_model', 'count', array($where), 600);
      $config['per_page'] = 10;

      $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
      
      $playlists = $this->cache->model('Playlist_model', 'get', array($where, $order, $config['per_page'], $page), 300);
      $this->pagination->initialize($config); 

      $this->data['pagination'] = $this->pagination->create_links();
      $this->data['playlists'] = $playlists;
      $this->data['form_attributes'] = array('id'=>'myForm');
      $this->data['title'] = 'Manage Playlists | ' . $this->lang->line('meta_title');

    $this->_render('manage/playlists', $this->data);
  }

  public function promote($song_id, $type) {

    if ($this->ion_auth->is_admin() || $this->ion_auth->is_moderator()) {

          $update_where = array('song_id'=>$song_id);
          $status = ($type === 'promote') ? 'yes' : 'no';
          
          if ($type === 'promote') {
              $data = array('promoted'=>$status,'promoted_date'=>time());
          } else {
              $data = array('promoted'=>$status);
          }

          $promote = $this->Song_model->update($update_where, $data);

          //getting song info to build URL for redirect, also using to check featured status
          $song = $this->Song_model->get_song($song_id);
          
           if ($promote) {
            
            if ($song->promoted === 'yes') {
              $message = 'Song Added to Promoted List.';
            } elseif ($song->promoted === 'no') {
              $message = 'Song Removed From Promoted List.';
            }

            //cache -- deleted featured songs cache when we feature a song -- array set in home.php
            $this->session->set_flashdata('update_status', '<h1 style="color:red;text-align:center">'.$message.'</h1>');
         }

          $this->cache->library('sorting', 'get_list', array('promoted', 10), -1);
          $sqlWhere = array('username'=>$song->username,'song_url'=>$song->song_url);
          $this->cache->model('Song_model', 'get', array($sqlWhere), -1);
            
          redirect('song/' . $song->username . '/' . $song->song_url, 'refresh');


    }
  }

	public function feature($song_id, $type) {

		if ($this->ion_auth->is_admin() || $this->ion_auth->is_moderator()) {

         	$update_where = array('song_id'=>$song_id);
          $status = ($type === 'feature') ? 'yes' : 'no';
          
          if ($type === 'feature') {
              $data = array('featured'=>$status,'featured_date'=>time());
          } else {
              $data = array('featured'=>$status);
          }

          $feature = $this->Song_model->update($update_where, $data);

         	//getting song info to build URL for redirect, also using to check featured status
         	$song = $this->Song_model->get_song($song_id);
          
           if ($feature) {
            
           	if ($song->featured === 'yes') {
           		$message = 'Song Added to Featured List.';
           	} elseif ($song->featured === 'no') {
           		$message = 'Song Removed From Featured List.';
           	}

            //cache -- deleted featured songs cache when we feature a song -- array set in home.php
            $this->session->set_flashdata('update_status', '<h1 style="color:red;text-align:center">'.$message.'</h1>');
         }

          $this->cache->library('sorting', 'get_list', array('featured', 10), -1);
          $sqlWhere = array('username'=>$song->username,'song_url'=>$song->song_url);
          $this->cache->model('Song_model', 'get', array($sqlWhere), -1);
            
          redirect('song/' . $song->username . '/' . $song->song_url, 'refresh');


		}
	}

  public function update_sort_order($tape_id) {
  if (!isset($tape_id) || !$this->ion_auth->logged_in()) {
    die('Application Error: SORT ERROR 100');
  }

    $this->load->model('Mixtape_model');

  $tape_order = 1;
  if (!$this->input->post()) {
    redirect('/','refresh');
  }
    foreach ($this->input->post('track') as $id) {
      $where = array(
        'id'=>$id,
        'tape_id'=>$tape_id,
        'user_id'=>$this->ion_auth->user()->row()->id
      );
    
      $data = array('tape_order'=>$tape_order);
      $this->Mixtape_model->update_track($where, $data);
  
      $tape_order++;
    }
  }

  public function update_track_info($tape_id, $field, $track_id){

    if (!isset($tape_id) || !isset($field) || !isset($track_id)) {
     die('Application Error: UPDATE ERROR 100');
    }
    if (!$this->ion_auth->logged_in()) {
      die('Application Error: UPDATE ERROR 101');
    }

    $this->load->model('Mixtape_model');
                            $this->output->set_header('Content-Type: application/json; charset=utf-8');

    $where = array('id'=>$track_id,'tape_id'=>$tape_id);

    $data = array('song_'.$field=>$this->input->post('data'));

    $update = $this->Mixtape_model->update_track($where, $data);
    if ($update) {
      $output_array = array(
        'success'=>true,
        'type'=>$field,
        'id'=>$tape_id,
        'value'=>$track_id
        );

    } else {
      $output_array = array(
        'success'=>false,
        'type'=>$field,
        'id'=>$tape_id,
        'value'=>$track_id
        );
    }
                        $this->output->set_output(json_encode($output_array));

  }
    
	public function account_settings() {
		$this->data['vendorCSS'] = array('forms.css');
		$this->_render('manage/account_settings', $this->data);
	}


  public function delete_playlist() {
      if (!$this->ion_auth->is_moderator() && !$this->ion_auth->is_admin()) {
          redirect('/', 'refresh');
      } 
      $this->load->model('Playlist_model');

      $playlist = $this->Playlist_model->get(array('users.username'=>$this->uri->segment('3'),'playlists.url'=>$this->uri->segment('4')));
      $playlist = $playlist[0];
      $user = $this->User_model->get_user_info($this->uri->segment(3));


      if ($playlist) {
        $delete = $this->Playlist_model->delete(array('user_id'=>$user->id,'playlists.url'=>$this->uri->segment('4')));    
        
        if ($delete) {

          $tracks = $this->Playlist_model->get_tracks(array('playlist_id'=>$playlist->id));

          if ($tracks) {
            foreach ($tracks as $key => $track) {
              $delete_track = $this->Playlist_model->delete_track(array('id'=>$track->id));
              if ($delete_track) {
                  echo '<p style="font-size:10px">DELETED SINGLE TRACK: ' . $track->song_title . '</p>';
              }
            }

          }

          echo 'DELETED ' . $playlist->title . 'by' . $playlist->username . '<br/>';
        } else {
          echo 'Could Not Delete <strong>' . $playlist->title . '</strong> by ' . $playlist->username . '<br/>';
        }
      } else {
        echo ' NO PLAYLIST FOUND';
      }
  }

  public function delete_song(){
      if (!$this->ion_auth->is_moderator() && !$this->ion_auth->is_admin()) {
          redirect('/', 'refresh');
      }

      $urls = explode("\n", str_replace("\r", "", $this->input->post('ids')));
      $reason   = $this->input->post('reason');

      foreach ($urls as $key => $url) {
        if (!0 === strpos($url, 'http')) {
          echo 'not a valid url. must start with http';
        } else {
          $parts = Explode('/', $url);
          $song_username = $parts[count($parts) - 2];
          $song_url = $parts[count($parts) - 1];
        
          $where = array('users.username'=>$song_username,'songs.song_url'=>$song_url);
          $data = array('status'=>$reason);
          $song = $this->Song_model->get($where);

          if (!$song) {
            //error
            echo $song_url . ' not found. Unable to update.';
          }  else {
            $updated = $this->Song_model->update(array('song_url'=>$song_url), $data);

            if (!$updated) {
              echo 'Error updating ' . $song_url . '<br />';
            } else {

              echo $song_url . ' updated to STATUS: ' . $reason . '<br />';
              
              $this->load->model('Playlist_model');
              $this->Playlist_model->delete_track(array('song_id'=>$song->song_id));
              $sqlWhere = array('username'=>$song->username,'song_url'=>$song->song_url);
              $this->cache->model('Song_model', 'get', array($sqlWhere), -1);
            
            } //updated
          } //if song

        } // if it begins with a http
      } //foreach
  }


}
/* End of file manage.php */
/* Location: ./application/controllers/manage.php */