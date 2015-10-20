<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Songs extends MY_Controller {

		function __construct()
	{
		parent::__construct();
	}
    
	public function index()
	{
        redirect('songs/latest','refresh');
	}

    public function error(){


        $this->data['subSection_bigav'] = $this->cache->library('sorting', 'get_list', array('songs','popular',5), 300);
        $this->_render('songs/not_found',$this->data);
    }
    
	public function old_url_redirect($url) {
		$song = $this->Song_model->get_song_where(array('song_url'=>$url));

		if (!$song) {

			foreach(glob(APPPATH . 'controllers/*' . EXT) as $controller) {
   			 $controller = basename($controller, EXT);
				if ($url == $controller) {
					redirect($url.'/index', 'refresh');
				} else {
					redirect('errors/page_missing', 'refresh');
				}
			}

		} else { 
			redirect('song/'.$song->username.'/'.$song->song_url, 'location', '301');
		}
	}

	public function update() {
        $this->output->set_header('Content-Type: application/json; charset=utf-8');
		$this->load->helper('slug');
     	$user = $this->ion_auth->user()->row();
     	$this->load->library('form_validation');

     	if ($this->input->post('song_id')) {
     		$song_row = $this->Song_model->get_song($this->input->post('song_id'));
     		$real_user_id = $song_row->user_id;
     		$real_file_uid = $song_row->file_uid;	
     	} else {
     		redirect('manage/songs','refresh');
     	}
     
     	if ($this->input->post('user_id') != $user->id && !$this->ion_auth->is_admin() && $this->input->post('song') != $real_file_uid) {
			redirect('manage/songs', 'refresh');
		} else {
                    //modal form validaiton
                      $this->form_validation->set_rules('artist', 'Artist', 'trim|required|min_length[2]|max_length[100]|xss_clean');
                      $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[2]|max_length[100]|xss_clean');
                      $this->form_validation->set_rules('featuring', 'Featuring', 'trim||min_length[2]|max_length[255]|xss_clean');
                      $this->form_validation->set_rules('producer', 'Producer', 'trim|min_length[2]|max_length[255]|xss_clean');
                      $this->form_validation->set_rules('album', 'Album', 'trim|min_length[2]|max_length[255]|xss_clean');
                      $this->form_validation->set_rules('video', 'Video', 'trim|min_length[2]|xss_clean');
                      $this->form_validation->set_rules('image', 'Image', 'trim|min_length[2]|xss_clean');
                      $this->form_validation->set_rules('description', 'Description', 'trim|min_length[2]|max_length[10000]|xss_clean');                      
                      $this->form_validation->set_rules('song_url', 'Song URL', 'trim|min_length[2]|max_length[255]|xss_clean');                      

                      	//hidden inputs
                        $file_uid 		= $this->input->post('song');
                        $song_id     	= $this->input->post('song_id');
                        $file_name 		= $this->input->post('file_name');
                        $user_id		= $this->input->post('user_id');

                        $artist 		= $this->input->post('artist', TRUE);
                        $title 			= $this->input->post('title', TRUE);
                        $featuring 		= $this->input->post('featuring', TRUE);
                        $producer 		= $this->input->post('producer', TRUE);
                        $album 			= $this->input->post('album', TRUE);
                        $video 			= $this->input->post('video', TRUE);
                        $buy_link 		= $this->input->post('buy_link', TRUE);
                        $can_download 	= $this->input->post('can_download', TRUE);
                        $description    = $this->input->post('description', TRUE);
                        $soundcloud_url = $this->input->post('soundcloud_url', TRUE);
                        $published 		= 'published';
                        
                        $song 			= $this->Song_model->get_song($song_id);
                        $username 		= $song->username;
                        $urlslug 		= $song->song_url;
                        $sfname         = $song->sfname;
                        $visibility     = (($this->input->post('make_private', TRUE) == 'yes') ? 'unlisted' : 'public');

                                  
                        // if the user changed the soundcloud URL
                        if ($soundcloud_url != $song->external_url) {
                            $this->load->helper('soundcloud');

                            $sc = json_decode(_resolveSoundcloud($soundcloud_url));
                            
                            if (!empty($sc->id)) {
                                $external_file = $sc->id;
                            } else {
                                $external_file = $song->external_file;
                            }
                            $external_url  = $soundcloud_url;
                        
                        } else {
                            $external_file = $song->external_file;
                            $external_url  = $song->external_url;
                        }
                        
                        if ($this->input->post('song_url') !== $song->song_url) {
                            $urlslug = url_slug($this->input->post('song_url', TRUE));
                        }

                        //make sure its a youtube video
                        if (preg_match('/^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/', $video)) { 
                            parse_str( parse_url($video, PHP_URL_QUERY),$my_array_of_vars);
                            $video = $my_array_of_vars['v'];
                        } else {
                            $video = $video;
                        }

                        if ($this->form_validation->run() == FALSE)
                        {
                            //validation errors
                            $output_array = array('validation' => 'error', 'message' => validation_errors('<div class="alert alert-error"><strong>Error!</strong> ', '</div>'));
                            $this->output->set_output(json_encode($output_array));

                        } else if (!$this->Song_model->valid_song_exists(array('file_uid'=>$file_uid,'user_id'=>$user_id,'song_id'=>$song_id))) { 
                            //couldn't find song that matches the file_uid/user_id/file_name
                            $output_array = array('validation' => 'valid', 'response'=>'error', 'message' => 'It appears the song doesnt exist.');
                            $this->output->set_output(json_encode($output_array));
                        
                        } else {

                            if (isset($_FILES['image_file'])) {
                                $uploads = $_FILES['image_file'];
                                if ($uploads['error'] == 0) {
                                     
                                    $this->load->library('images');
                                    $this->images->uploadLocalFile($uploads, $sfname, $urlslug, $user_id);

                                        $ext = pathinfo($uploads['name']);
                                        $ext = $ext['extension'];
                                        $image_dir = FCPATH . 'asset_uploads/' . $song->username . '/' . $urlslug . '/';
                                        $size_array = array('64','150','300','500');
                                        foreach ($size_array as $size) {

                                        $resize = $this->images->resizeImage($image_dir, $sfname, $ext, $size);
                                        }
                                        if (!$resize) {
                                            $output_array = array('validation' => 'valid', 'response'=>'error', 'message' => 'Unable to resize image');
                                            $this->output->set_output(json_encode($output_array));
                                        
                                        }

                                     $song_image = $sfname .'.'. $ext;

                                }
                            } else {
                                $song_image = $song->song_image;
                            }

        						 $song_data = array(
                                    'song_artist' => $artist,
                                    'song_title' => $title,
                                    'song_description' => $description,
                                    'featuring'=> $featuring,
                                    'song_url'=>$urlslug,
                                    'song_producer'=> $producer,
                                    'album' => $album,
                                    'file_name' => $file_name,
                                    'video' => $video,
                                    'song_image' =>$song_image,
                                    'external_file'=>$external_file,
                                    'external_url'=>$external_url,
                                    'buy_link' => $buy_link,
                                    'status'=>$published,
                                    'visibility'=>$visibility,
                                    'can_download'=> $can_download);
        						 

                                 $update_where = array(
                                    'file_uid'=>$file_uid,
                                    'user_id'=>$user_id,
                                    'song_id'=>$song_id);

                               	 $song_row_updated = $this->Song_model->update($update_where, $song_data);
       
	           	             if(!$song_row_updated) { 
                        	    $output_array = array('validation' => 'valid', 'response'=>'error', 'message' => 'The song was not updated. Did you make any changes?');
                                $this->output->set_output(json_encode($output_array));
	                        
	                         } else {
                                //paydirt
                      
                                    if ($external_file != $song->external_file) {
                                        //upload SC Image
                                        $this->load->library('images');
                                        $this->images->uploadRemoteFile($sc->artwork_url, $sfname, $urlslug);
                                        $song_image = $sfname .'.jpg';

                                        $image_dir = FCPATH . 'asset_uploads/' . $username . '/' . $urlslug . '/';
                                        $size_array = array('64','150','300');
                                        foreach ($size_array as $size) {
                                            $this->images->resizeImage($image_dir, $sfname, 'jpg', $size);
                                        }  
                                    }    


                                //used to add more output to the returned json
                                $urlUpdated = NULL;
                                
                                if ($this->input->post('song_url') !== $song->song_url) {
                                     $urldata = array(
                                        'old_url'=>$song->song_url,
                                        'current_url'=>$urlslug,
                                        'song_id'=>$song->song_id,
                                        'date_created'=>time()
                                        );

                                    $updateUrl = $this->Song_model->addUpdatedUrl($urldata);
                                    if ($updateUrl) {
                                        $urlUpdated = "<br /><span style='font-weight:bold;font-size:1em'>The Song URL was updated as well. Please update your links accordingly.</span>";
                                    }
                                }
                        
                                //delete existing post cache 
                                $sqlWhere = array('username'=>$song->username,'song_url'=>$song->song_url);
                                $this->cache->model('Song_model', 'get_song_where', array($sqlWhere), -1);

    	                        $output_array = array('validation' => 'valid', 'response'=>'success','message'=>'Song updated. <a href="'. base_url('song/'.$username.'/'.$urlslug) .'">Click here to view</a>' . $urlUpdated);
                                $this->output->set_output(json_encode($output_array));
	                   
	                         }

                  }
            } //end form_validation and JSON returns
    } //end user/file checks

	public function edit($song_id) {

		$this->load->helper('form');
		$song = $this->Song_model->get_song($song_id);
		$this->data['song'] = $song;
     	$user = $this->ion_auth->user()->row();

		if (!isset($song_id)) {
			redirect('errors/page_missing', 'refresh');
		}

        $this->data['vendorJS'] = array('jquery.form.js');

		//song exists
		if ($song) {

			//check to see if song belongs to user_id or if its an admin
			if (!$this->ion_auth->is_admin() && $song->user_id != $user->id) {
				redirect('manage/songs', 'refresh');
			} else {	

                $dl_checked = ($song->can_download == 'yes') ? 'checked' : FALSE;

                $fullVideoUrl = (!empty($song->video)) ? 'https://www.youtube.com/watch?v=' . $song->video : $song->video;
                
                https://www.youtube.com/watch?v=

        		$this->data['form_attributes'] = array('id'=>'myForm');
				$this->data['form_artist'] = array('name'=>'artist','id'=>'artist','type'=>'text','value'=>$song->song_artist,'size'=>'50','class'=>'form-control');
                $this->data['form_title'] = array('name'=>'title','id'=>'title','type'=>'text','value'=>$song->song_title,'size'=>'50','class'=>'form-control');
	         	$this->data['form_url'] = array('name'=>'song_url','id'=>'song_url','type'=>'text','value'=>$song->song_url,'size'=>'50','class'=>'form-control');
	         	$this->data['form_featuring'] = array('name'=>'featuring','id'=>'featuring','type'=>'text','value'=>$song->featuring,'size'=>'50','class'=>'form-control');
	         	$this->data['form_producer'] = array('name'=>'producer','id'=>'producer','type'=>'text','value'=>$song->song_producer,'size'=>'50','class'=>'form-control');
	         	$this->data['form_album'] = array('name'=>'album','id'=>'album','type'=>'text','value'=>$song->album,'size'=>'50','class'=>'form-control');
	         	$this->data['form_video'] = array('name'=>'video','id'=>'video','type'=>'text','value'=>$fullVideoUrl,'size'=>'50','class'=>'form-control');
	         	$this->data['form_image'] = array('name'=>'image','id'=>'image','type'=>'text','value'=>$song->song_image,'placeholder'=>'URL of Image','size'=>'50','class'=>'form-control');
	         	$this->data['form_description'] = array('name'=>'description','id'=>'description','type'=>'text','value'=>$song->song_description,'size'=>'50','class'=>'form-control');
	         	$this->data['form_can_download'] = array('name'=>'can_download','id'=>'can_download','checked'=>$dl_checked);
                $this->data['form_buy_link'] = array('name'=>'buy_link','id'=>'buy_link','type'=>'text','value'=>$song->buy_link,'size'=>'50','class'=>'form-control');
	         	$this->data['form_soundcloud_url'] = array('name'=>'soundcloud_url','id'=>'form_soundcloud_url','type'=>'text','value'=>$song->external_url,'size'=>'50','class'=>'form-control','aria-describedby'=>'sizing-addon1');
                $this->data['soundcloud_url'] = $song->external_url;
            }
		
		} else {
			redirect('manage/songs', 'refresh');
		}

		$this->data['title'] = htmlspecialchars('Edit ' . $song->song_artist . ' - ' . $song->song_title . ' | ' . SITE_TITLE, ENT_QUOTES);

		$this->_render('songs/edit');
	}

/**
 * delete a song via JSON
 * @return [json] [array]
 */
 public function delete() {
  	$this->output->set_header('Content-Type: application/json; charset=utf-8');
    $user = $this->ion_auth->user()->row();

    $user = $this->input->post('user');
    $id = $this->input->post('id');

    $song = $this->Song_model->get(array('song_id'=>$id));

    if (!$song) {
        $output_array = array('error'=>'true','message'=>'SONG NOT FOUND');
        
    } else {
        $song = $song[0];

        
       if ($user != $song->user_id && !$this->ion_auth->is_admin()) {
         $output_array = array('error'=>'true','message'=>'INSUFFICIENT ACCESS');
            
        } else {

            $where = array('song_id'=>$id);
            $delete = $this->Song_model->delete_song($where);
            
            if (!$delete) {
                $output_array = array('error'=>'true','message'=>'There seems to be a problem. Your song was not deleted. Please try again.');
            } else {
                $this->load->model('Playlist_model');
                $this->Playlist_model->delete_track(array('song_id'=>$id));
                $output_array = array('error'=>'false','message'=>'Your song has been deleted.');            
            }
        }
    }

    $this->output->set_output(json_encode($output_array));
}

/**
 * Updates the song's status on the file server.
 * Typically used to remove files due to copyright/removal
 * @param  int $song_id - songs id
 * @param  string $reason - the reason for removal, this will replace the current song status in the DB (published/incomplete/copyright/removed)
 * @return [type]          [description]
 */
public function delete_song_from_server($song_id, $reason) {

    $song = $this->Song_model->get_song($song_id);

    if (!$this->ion_auth->is_moderator() && !$this->ion_auth->is_admin()) {
        redirect('/', 'refresh');
        exit();
    }

    if ($song) {

        $where = array('song_id'=>$song_id,'user_id'=>$song->user_id);
        $raw_reason = array('status'=>$reason);
        $clean_reason = array_filter($raw_reason);

        if (isset($reason)) {
            
            $removed = $this->Song_model->remove_song($where, $clean_reason);
            
            if ($removed) {
                // remove the song from all playlists
                $this->load->model('Playlist_model');
                $this->Playlist_model->delete_track(array('song_id'=>$song_id));

                $sqlWhere = array('username'=>$song->username,'song_url'=>$song->song_url);
                $this->cache->model('Song_model', 'get_song_where', array($sqlWhere), -1);
                
                //only delete the file if the song is to be removed or set as copyright
                if ($reason === 'copyright' || $reason === 'removed') {
                    if (file_exists(FCPATH . '/audio_uploads/'.$song->username.'/'.$song->file_name)) {
                        if (unlink(FCPATH . '/audio_uploads/'.$song->username.'/'.$song->file_name)) {
                            $this->session->set_flashdata('file_deleted', 'Song Was Deleted from our file server.');
                        }
                    } else {
                        $this->session->set_flashdata('file_deleted', 'Song Was Not Deleted! Contact an admin to have it manually deleted.');
                    }
                } else {
                    $this->session->set_flashdata('file_deleted', 'Song Status updated in Database. Song file has not been removed from server.');
                } //if reason is copyright/removed

                redirect('song/'.$song->username.'/'.$song->song_url);   
            
            } else {
                $this->session->set_flashdata('file_deleted', 'Song has already been removed');
                redirect('song/' . $song->username . '/' . $song->song_url, 'refresh');
            } //if db was updated
        } //if reason is set
    } //if song exists
}


public function delete_song_from_s3($song_id, $reason){

    $song = $this->Song_model->get_song($song_id);

    if (!$this->ion_auth->is_moderator() && !$this->ion_auth->is_admin()) {
        redirect('/', 'refresh');
        exit();
    }

    if ($song) {
        $where = array('song_id'=>$song_id,'user_id'=>$song->user_id);
        $raw_reason = array('status'=>$reason);
        $clean_reason = array_filter($raw_reason);

        if (isset($reason)) {
            $removed = $this->Song_model->remove_song($where, $clean_reason);
            if ($removed) {
                // remove the song from all playlists
                $this->load->model('Playlist_model');
                $this->Playlist_model->delete_track(array('song_id'=>$song_id));

                $sqlWhere = array('username'=>$song->username,'song_url'=>$song->song_url);
                $this->cache->model('Song_model', 'get_song_where', array($sqlWhere), -1);

                //only delete the file if the song is to be removed or set as copyright
                if ($reason === 'copyright' || $reason === 'removed') {

                    $this->load->library('S3');

                    if ($this->s3->deleteObject($this->config->item('s3_music_bucket'), '/tracks/' . $song->username . '/' . $song->file_name)) {
                        $this->session->set_flashdata('file_deleted', 'Song Was Deleted from S3.<br /> Song Status Was Updated In Database to: ' . strtoupper($reason));       
                    } else {
                        $this->session->set_flashdata('file_deleted', 'Song Was Not Deleted! Contact an admin to have it manually deleted.');
                    }
                } else {
                    $this->session->set_flashdata('file_deleted', 'Song Status updated in Database. Song file has not been removed from server.');
                } //if reason is copyright/removed

                redirect('song/'.$song->username.'/'.$song->song_url);   
            
            } else {
                $this->session->set_flashdata('file_deleted', 'Song has already been removed');
                redirect('song/' . $song->username . '/' . $song->song_url, 'refresh');
            } //if db was updated
        } //if reason is set
    } //if song exists
}



public function download_song($username, $song_url) {
		if (!$this->ion_auth->username_check($username)) {
			redirect('errors/page_missing', 'refresh');
		}

        //cached DB call
        $user = $this->cache->model('User_model', 'get_user_info', array($username), 300); // keep for 5 minutes
		//$user = $this->User_model->get_user_info($username);
        
        $where = array('user_id'=>$user->id,'song_url'=>$song_url);
        //cached DB call
        $song = $this->cache->model('Song_model', 'get_song_where', array($where), 300); // keep for 5 minutes
		//$song = $this->Song_model->get_song_where(array('user_id'=>$user->id,'song_url'=>$song_url));

		if ($this->uri->segment(5) == NULL) {
			redirect(base_url('song/'.$username.'/'.$song_url), 'refresh');	
		}
			//hash is set in the url so get the params from the url and compare to the current timestamp
			$hashGiven = $this->uri->segment(5);
			$timestamp = $this->uri->segment(4);
			$ip = $_SERVER['REMOTE_ADDR'];
			$salt = 'fuck!these&other$itesit$hiph0pvip0rd!e';
			$path = $song->file_name;

			$hash = md5($salt . $ip . $timestamp . $path);

			//does the URI->segment(5) hash equal the hash algo from above $hash? is the time from $seg(4) greater than or equal to the current time? if so, proceed, if not, die.
			if($hashGiven == $hash && $timestamp >= time()) {
				/*****
				*BEGIN DOWNLOAD FILE
				******/
				define('ALLOWED_REFERRER', '');
				// Download folder, i.e. folder where you keep all files for download.
				// MUST end with slash (i.e. "/" )
				define('BASE_DIR', FCPATH . 'audio_uploads/' . $username . '/');
				// log downloads?  true/false
				define('LOG_DOWNLOADS',FALSE);
				// log file name
				define('LOG_FILE',FCPATH . 'application/logs/downloads.log');
				$allowed_ext = array ('mp3' => 'audio/mpeg');

				// If hotlinking not allowed then make hackers think there are some server problems
				if (ALLOWED_REFERRER !== ''
				&& (!isset($_SERVER['HTTP_REFERER']) || strpos(strtoupper($_SERVER['HTTP_REFERER']),strtoupper(ALLOWED_REFERRER)) === false)
				) {
				  die("Internal server error. Please contact system administrator.");
				}

				// Make sure program execution doesn't time out
				// Set maximum script execution time in seconds (0 means no limit)
				set_time_limit(0);

				if (!isset($song) || empty($song)) {
				  die("Please specify a song to download.");
				}

				// Get real file name.
				// Remove any path info to avoid hacking by adding relative path, etc.
				$fname = basename($song->file_name);

				// get full file path (including subfolders)
				$file_path = '';
				find_file(BASE_DIR, $fname, $file_path);

				if (!is_file($file_path)) {
				  die("File does not exist. Make sure you specified correct file name."); 
				}

				// file size in bytes
				$fsize = filesize($file_path); 

				// file extension
				$fext = strtolower(substr(strrchr($fname,"."),1));

				// check if allowed extension
				if (!array_key_exists($fext, $allowed_ext)) {
				  die("Not allowed file type."); 
				}

				// get mime type
				if ($allowed_ext[$fext] == '') {
				  $mtype = '';
				  // mime type is not set, get from server settings
				  if (function_exists('mime_content_type')) {
				    $mtype = mime_content_type($file_path);
				  }
				  else if (function_exists('finfo_file')) {
				    $finfo = finfo_open(FILEINFO_MIME); // return mime type
				    $mtype = finfo_file($finfo, $file_path);
				    finfo_close($finfo);  
				  }
				  if ($mtype == '') {
				    $mtype = "application/force-download";
				  }
				}
				else {
				  // get mime type defined by admin
				  $mtype = $allowed_ext[$fext];
				}

				//REMOVE UNDERSCORES FROM FILENAME
				$asfname = str_replace('_', ' ', $fname);

				// set headers
				header("Pragma: public");
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Cache-Control: public");
				header("Content-Description: File Transfer");
				header("Content-Type: $mtype");
				header("Content-Disposition: attachment; filename=\"$asfname\"");
				header("Content-Transfer-Encoding: binary");
				header("Content-Length: " . $fsize);

				// download
				// @readfile($file_path);
				$file = @fopen($file_path,"rb");
				if ($file) {
				  while(!feof($file)) {
				    print(fread($file, 1024*8));
				    flush();
				    if (connection_status()!=0) {
				      @fclose($file);
				      die();
				    }
				  }
				  @fclose($file);
				}
		
				// log downloads
				if (!LOG_DOWNLOADS) die();

				$f = @fopen(LOG_FILE, 'a+');
				if ($f) {
				  @fputs($f, date("m.d.Y g:ia")."  ".$_SERVER['REMOTE_ADDR']."  ".$fname."\n");
				  @fclose($f);
				}

			} else {
			    die('<div align="center"><strong>Link has expired or is invalid.</strong><br /><a href="'.base_url('song/'.$username.'/'.$song_url).'">Listen/Download'. $song->song_artist . ' - ' . $song->song_title . '</a></div>');
			}
		}
	}

// Check if the file exists || PART OF THE DOWNLOAD SCRIPT
// Check in subfolders too  || PART OF THE DOWNLOAD SCRIPT
function find_file ($dirname, $fname, &$file_path) {

  $dir = opendir($dirname);

  while ($file = readdir($dir)) {
    if (empty($file_path) && $file != '.' && $file != '..') {
      if (is_dir($dirname.'/'.$file)) {
        find_file($dirname.'/'.$file, $fname, $file_path);
      }
      else {
        if (file_exists($dirname.'/'.$fname)) {
          $file_path = $dirname.'/'.$fname;
          return;
        }
      }
    }
  }

} // find_file


/* End of file songs.php */
/* Location: ./application/controllers/songs.php */