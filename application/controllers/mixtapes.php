<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mixtapes extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();

		$this->load->model('Mixtape_model');
	}

	public function index()
	{
		redirect('mixtapes/latest','refresh');
	}

	public function player($username="", $tape_title="") {
		
		$mixtape = $this->cache->model('Mixtape_model','get_mixtapes',array(array('mixtapes.tape_url'=>$tape_title,'username'=>$username)),300);

		if (!$mixtape) {
			redirect('errors/page_missing','refresh');
		}

		$mixtape = $mixtape[0];

		//mixtape isn't published yet, so don't allow access to the page. could maybe do this with jquery/javascript -- just hide the player and display info but idk what would be best at 5:36am right now
		if ($mixtape->status != 'published') {
			redirect('error/mixtape-processing','refresh');
		}

		$this->data['tape'] 	= $mixtape;
		$this->data['username'] = $username;

		$this->data['playlist']	= $this->getPlaylistData($mixtape->id);
		$this->data['tracks'] = $this->cache->model('Mixtape_model','get_tracks',array(array('tape_id'=>$mixtape->id),'tape_order ASC'), 300);

		/* hotlinking protection */
		$ip = $_SERVER['REMOTE_ADDR'];
		$salt = 'fuck!these&other$itesit$hiph0pvip0rd!e';
		$path = $mixtape->file_name;
		$timestamp = time() + 2400; // file valid for 40 minutes
		$hash = md5($salt . $ip . $timestamp . $path); // order isn't important at all... just do the same when verifying

		$this->data['dl_time'] = $timestamp;
		$this->data['dl_hash'] = $hash;
		/* end hotlinking */

		$meta_download		= ($mixtape->can_download === 'yes') ? 'Download ' : 'Listen to ';
		$meta_description 	= (empty($mixtape->tape_description)) ? $meta_download . htmlspecialchars($mixtape->tape_title, ENT_QUOTES) . ' by ' . htmlspecialchars($mixtape->tape_artist, ENT_QUOTES) : htmlspecialchars($mixtape->tape_description, ENT_QUOTES);

		$this->data['meta_name'] = array(
			'description'=> htmlspecialchars($meta_description, ENT_QUOTES),
			'twitter:card'=>'player',
			'twitter:site'=>'@hiphopvip1',
			'twitter:domain'=>'http://hiphopvip.com',
			'twitter:title'=>htmlspecialchars($mixtape->tape_artist, ENT_QUOTES) . ' - ' .htmlspecialchars($mixtape->tape_title, ENT_QUOTES),
			'twitter:description'=>htmlspecialchars($mixtape->tape_description, ENT_QUOTES),
			'twitter:image'=>tape_img($mixtape->username, $mixtape->tape_url, $mixtape->tape_image),
			'twitter:player'=> $this->config->item('secure_base_url').'/embed/mixtape/1/'.$username.'/'.$mixtape->tape_url,
			'twitter:player:width'=>'480',
			'twitter:player:height'=>'325',
		);
		$this->data['meta_prop'] = array(
			'og:title'=> $meta_download . htmlspecialchars($mixtape->tape_artist . ' - ' . $mixtape->tape_title, ENT_QUOTES),
			'og:url'=> base_url('mixtape/'.$username.'/'.$mixtape->tape_url),
			'og:image'=> song_img($mixtape->username, $mixtape->tape_url, $mixtape->tape_image),
			'og:site_name'=> 'hiphopVIP',
			'og:description'=> $meta_description
		);	

		$this->data['mixtape_subSection'] 	= $this->Mixtape_model->get_mixtapes(array('mixtapes.user_id'=>$mixtape->user_id,'mixtapes.status'=>'published'), 'id DESC', 5,0);
		$this->data['tape_image'] = tape_img($mixtape->username, $mixtape->tape_url, $mixtape->tape_image, 300);
		$this->data['vendorCSS'] = array('apm/skin/hhvip.css','apm/skin/jquery-ui-slider.custom.css','social-likes/social-likes_classic.css');
		$this->data['vendorJS'] = array('apm/lib/jquery-ui-slider-1.10.4.custom.min.js','apm/lib/modernizr-2.5.3-custom.min.js', 'apm/lib/soundmanager2-jsmin.js', 'apm/apmplayer.js','apm/apmplayer_ui.jquery.js','social-likes/social-likes.min.js');

		$this->data['title'] = 'Download & Listen to ' . $mixtape->tape_title . ' by ' . $mixtape->tape_artist;
		$this->_render('mixtapes/player', $this->data);
	}

	public function embed_player($skin="", $username="", $tape="") {
		if (!$this->ion_auth->username_check($username)) {
			$this->data['tape'] = NULL;
			$this->data['tape_status'] = "The embedded mixtape no longer exists.";
		} else {

			$this->data['username'] = $username;
			//cached DB call
			$user = $this->cache->model('User_model', 'get_user_info', array($username), 1800);
			//$user = $this->User_model->get_user_info($username);

			$sqlWhere = array('mixtapes.user_id'=>$user->id,'mixtapes.tape_url'=>$tape);
			$tape = $this->cache->model('Mixtape_model', 'get_mixtapes', array($sqlWhere), 1800);

			if (!$tape) {
				$this->data['tape'] = NULL;
				$this->data['tape_status'] = "The embedded mixtape no longer exists.";
			} else {
			$tape = $tape[0];

			$this->data['tape'] = $tape;
			$this->load->helper('status');
			$this->data['tape_status'] = status_message('mixtape',$tape->status);			
			$this->data['user'] = $user;
			
			$meta_description = ($tape->tape_description === '') ? htmlspecialchars($tape->tape_title . ' mixtape, by ' . $tape->tape_artist, ENT_QUOTES) : htmlspecialchars($tape->tape_description, ENT_QUOTES);


			$this->data['meta_name'] = array(
				'description'=> html_entity_decode('Steam/Download ' . $meta_description),
				'twitter:card'=>'player',
				'twitter:domain'=>base_url(),
				'twitter:site'=> $this->lang->line('meta_twitter'),
				'twitter:title'=>htmlspecialchars($tape->tape_artist . ' - ' . $tape->tape_title, ENT_QUOTES),
				'twitter:description'=>htmlspecialchars($meta_description, ENT_QUOTES),
				'twitter:image'=>tape_img($tape->username, $tape->tape_url, $tape->tape_image),
				'twitter:player'=>base_url('embed/mixtape/1/'.$username.'/'.$tape->tape_url),
				'twitter:player:width'=>'480',
				'twitter:player:height'=>'300',
				'twitter:creator'=>'@hiphopvip1',
				);

			$this->data['meta_prop'] = array(
				'og:title'=> htmlspecialchars('Listen and Download ' . $tape->tape_artist . ' - ' . $tape->tape_title, ENT_QUOTES),
				'og:url'=> base_url('mixtape/'.$username.'/'.$tape->tape_url),
				'og:image'=> tape_img($tape->username, $tape->tape_url, $tape->tape_image),
				'og:site_name'=> 'hiphopVIP',
				'og:description'=> htmlspecialchars($meta_description, ENT_QUOTES)
				);

			$this->data['playlist']	= $this->getPlaylistData($tape->id);

			$this->data['vendorCSS']	= array('apm/skin/hhvip.css','apm/skin/jquery-ui-slider.custom.css','social/social-likes_flat.css','forms.css');
			$this->data['vendorJS'] 	= array('apm/lib/jquery-ui-slider-1.10.4.custom.min.js','apm/lib/modernizr-2.5.3-custom.min.js', 'apm/lib/soundmanager2-jsmin.js', 'apm/apmplayer.js','apm/apmplayer_ui.jquery.js','social-likes/social-likes.min.js');

			} // song exists
		}//user exists

		$this->_render('mixtapes/embed_player', $renderData='EMBED', $this->data);

	}

	public function edit($id) {

		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login','refresh');
		}

		$this->load->helper('form');
     	$user = $this->ion_auth->user()->row();

		$mixtape = $this->Mixtape_model->get_mixtape($id);
     	
     	if (!$mixtape || !$this->ion_auth->logged_in()) {
     		redirect('manage','refresh');
     	}

     	//check if the user is an admin or if the user owns the mixtape
     	if ($user->id !== $mixtape->user_id && !$this->ion_auth->is_admin()) {
     		redirect('manage','refresh');
     	}

		$dl_checked = ($mixtape->can_download === 'yes') ? 'checked' : FALSE;

        $this->data['form_attributes'] 		= array('id'=>'mixtapeEdit');
        $this->data['form_tape_name'] 		= array('name'=>'tape_title','id'=>'tape_title','value'=>$mixtape->tape_title,'type'=>'text','size'=>'50','class'=>'form-control');
        $this->data['form_artist'] 			= array('name'=>'tape_artist','id'=>'tape_artist','value'=>$mixtape->tape_artist,'type'=>'text','size'=>'50','class'=>'form-control');
        $this->data['form_artwork'] 		= array('name'=>'tape_image','id'=>'tape_image','type'=>'text','size'=>'50','class'=>'form-control');
        $this->data['form_video'] 			= array('name'=>'tape_video','id'=>'tape_video','value'=>$mixtape->tape_video,'type'=>'text','size'=>'50','class'=>'form-control');
        $this->data['form_description'] 	= array('name'=>'tape_description','id'=>'tape_description','value'=>$mixtape->tape_description,'type'=>'text','size'=>'50','class'=>'form-control');
        $this->data['form_can_download'] 	= array('name'=>'can_download','id'=>'can_download','checked'=>$dl_checked);
        $this->data['form_buy_link'] 		= array('name'=>'buy_link','id'=>'buy_link','type'=>'text','size'=>'50','value'=>$mixtape->buy_link,'class'=>'form-control');

	    $this->data['vendorJS'] = array('jquery.form.js');
        $this->data['vendorJS'] = array('uploadkit/uploadkit.js','jquery-ui.min.js','plupload/plupload.full.min.js','plupload/js/jquery.plupload.queue.js','jquery.form.js','jquery.inlineEdit.js');
        $this->data['vendorCSS'] = array('uploadkit/uploadkit.css','plupload/css/jquery.plupload.queue.css','forms.css');

		$this->data['tracks'] = $this->Mixtape_model->get_tracks(array('tape_id'=>$mixtape->id),'tape_order ASC');
		$this->data['mixtape'] = $mixtape;
	
		$this->data['noSidebar'] = true;
		$this->_render('mixtapes/edit');

	}

	public function update() {

        $this->output->set_header('Content-Type: application/json; charset=utf-8');
     	$user = $this->ion_auth->user()->row();
     	$this->load->library('form_validation');

     	if ($this->input->post('id')) {
     		$tape = $this->Mixtape_model->get_mixtape($this->input->post('id'));

		     if (!$tape) {
				die('{"validation":"error","message":"FATAL ERROR :: TAPE NOT FOUND"}');	
		     }
     	} else {
     		redirect('/','refresh');
     	}
     
     	if ($this->input->post('user_id') != $user->id && !$this->ion_auth->is_admin() && $user->id != $tape->user_id) {
				die('{"validation":"error","message":"FATAL ERROR. TYPE: A"}');
		}

        $this->form_validation->set_rules('tape_artist', 'Artist', 'trim|required|min_length[1]|max_length[200]|xss_clean');
        $this->form_validation->set_rules('tape_title', 'Mixtape Name', 'trim|required|min_length[1]|max_length[200]|xss_clean');
        $this->form_validation->set_rules('tape_video', 'Video', 'trim|min_length[1]|max_length[200]|xss_clean');
        $this->form_validation->set_rules('tape_description', 'Description', 'trim|min_length[1]|max_length[1000]|xss_clean');

        //hidden inputs
        $id     		= $this->input->post('id');
        $user_id		= $this->input->post('user_id');

        if ($this->ion_auth->is_admin()) {
        	$user_id 	= $tape->user_id;
        }

        $artist         = $this->input->post('tape_artist');
        $title          = $this->input->post('tape_title');
		$description    = $this->input->post('tape_description');
		$video          = $this->input->post('tape_video');
		$tape_image     = $this->input->post('tape_image');
		$can_download 	= $this->input->post('can_download');
		$buy_link 		= $this->input->post('buy_link');

	    //make sure its a youtube video
	    if (preg_match('/^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/', $video)) { 
	        parse_str( parse_url($video, PHP_URL_QUERY),$my_array_of_vars);
	        $video = $my_array_of_vars['v'];
	    }

	        if ($this->form_validation->run() == FALSE)
	        {
	        //validation errors
	        	$output_array = array('validation' => 'error', 'message' => validation_errors('<div class="error"><strong>Error!</strong> ', '</div>'));
	        } else if (!$this->Mixtape_model->get_mixtapes(array('mixtapes.id'=>$id,'mixtapes.user_id'=>$user_id),'id DESC',1,0)) {                   
	        	$output_array = array('validation' => 'valid', 'response'=>'error', 'message' => 'Were having trouble locating the mixtape.' . 'user_id=>' . $user_id .'|| id=>'.$id);
	    	} else {

			//album art upload
            if (isset($_FILES['image_file'])) {
                $uploads = $_FILES['image_file'];
                if ($uploads['error'] == 0) {
                    $this->load->library('images');
                    $urlslug = 'mixtapes/'.$tape->tape_url;
                    $this->images->uploadLocalFile($uploads, md5($tape->tape_url), $urlslug);
                        $ext = pathinfo($uploads['name']);
                        $ext = $ext['extension'];
                        $image_dir = FCPATH . 'asset_uploads/' . $this->ion_auth->user()->row()->username. '/mixtapes/' . $tape->tape_url . '/';
                        $size_array = array('64','150','300');
                        foreach ($size_array as $size) {
                        $resize = $this->images->resizeImage($image_dir, md5($tape->tape_url), $ext, $size);
                        }
                    $tape_image = md5($tape->tape_url) .'.'. $ext;
                }
            } else {
            	$tape_image = $tape_image;
            } // -- end album art upload

				$update_data = array(
					'tape_artist'=>$artist,
					'tape_title'=>$title,
					'tape_description'=>$description,
					'tape_video' => $video,
					'tape_image' =>$tape_image,
					'buy_link' => $buy_link,
					'can_download'=> $can_download);

				$update_where = array('id'=>$id,'user_id'=>$user_id);
				$update = $this->Mixtape_model->update_mixtape($update_where, $update_data);

				if(!$update) { 
		    		$output_array = array('validation' => 'valid', 'response'=>'error', 'message' => 'The mixtape was not updated. Did you make any changes?');
		    	} else {
		    		//paydirt

					//delete existing mixtape and mixtape tracks cache 
					$this->cache->model('Mixtape_model', 'get_mixtapes', array(array('mixtapes.id'=>$id,'mixtapes.user_id'=>$tape->user_id)), -1);
					$this->cache->model('Mixtape_model','get_tracks',array(array('tape_id'=>$tape->id),'tape_order ASC'), -1);

					$output_array = array('validation' => 'valid', 'response'=>'success','message'=>'Mixtape updated. <a href="'. base_url('mixtape/'.$user->username.'/'.$tape->tape_url) .'">Click here to view</a>');
				}
			}//end form_validation and JSON returns
		$this->output->set_output(json_encode($output_array));
	} 

	/* FOR USERS AND JSON */
	public function delete() {

	      	$this->output->set_header('Content-Type: application/json; charset=utf-8');
	        $user = $this->ion_auth->user()->row();
	        $this->load->library('form_validation');

	        if ($this->input->post('id')) {
	            $tape_row = $this->Mixtape_model->get_mixtape($this->input->post('id'));
	            
	        } else {
	            redirect('manage/mixtapes','refresh');
	        }


	        if ($this->input->post('user_id') != $user->id && !$this->ion_auth->is_admin() && $this->input->post('user_id') != $tape_row->user_id) {
	                redirect('manage/mixtapes', 'refresh');
	        } else {

	        $this->form_validation->set_rules('id', 'Mixtape ID', 'trim|required');
	        $this->form_validation->set_rules('user_id', 'user_id', 'trim|required|numeric');

	        $tape_id        = $this->input->post('id');
	        $file_uid       = $this->input->post('file_uid');
	        $file_name      = $this->input->post('file_name');
	        $tape_user_id   = $this->input->post('user_id');

	        $where_data = array(
	            'id'=> $tape_id,
	            'user_id'=> $tape_user_id,
	            'file_uid'=> $file_uid,
	            'file_name'=> $file_name
	            );

	        $delete = $this->Mixtape_model->delete_mixtape($where_data);
	        
	            if ($this->form_validation->run() == FALSE) {
	            $output_array = array('validation'=>'error', 'response'=>'error','message'=>validation_errors('<div class="error"><strong>Error!</strong> ', '</div>'));
	        	$this->output->set_output(json_encode($output_array));  
	            } elseif (!$delete) {
	            $output_array = array('validation'=>'valid', 'response'=>'error','message'=>'We are unable to delete your mixtape at this time. Please try Again.');
	       		$this->output->set_output(json_encode($output_array));
	            } else {
	            $output_array = array('validation'=>'valid', 'response'=>'success','message'=>'Your mixtape has been deleted.');
	     	   	$this->output->set_output(json_encode($output_array));
	            }

	        }
	}

	public function getPlaylistData($tid) {
        $playlist = $this->Mixtape_model->get_tracks(array('tape_id'=>$tid),'tape_order ASC');

        if (!$playlist) {
             return "''";
        } else {
           foreach ($playlist as $p) {
            $data[] = 
            array(
            	'host'=>$p->tape_order,
            	'title'=>htmlspecialchars($p->song_title, ENT_QUOTES),
            	'description'=>htmlspecialchars($p->song_artist, ENT_QUOTES),
            	'identifier'=>$p->id,
            	'type'=>'audio',
            	'image_sm'=>$p->file_name,
            	'http_file_path'=>base_url('audio_uploads/' . $p->username . '/mixtapes/' . $p->file_name),
            	'title'=>htmlspecialchars($p->song_title, ENT_QUOTES)
            	);
            }

            return json_encode($data,JSON_UNESCAPED_SLASHES);
        }
    }


public function download_mixtape($username, $tape_url) {
		if (!$this->ion_auth->username_check($username)) {
			redirect('errors/page_missing', 'refresh');
		}

        $user = $this->cache->model('User_model', 'get_user_info', array($username), 300); // keep for 5 minutes
        
        $where = array('mixtapes.user_id'=>$user->id,'mixtapes.tape_url'=>$tape_url);
        $tape = $this->cache->model('Mixtape_model', 'get_mixtapes', array($where), 300); // keep for 5 minutes

        if ($tape) {
        	$tape = $tape[0];
        } else {
        	redirect('/', 'refresh');
        }

		if ($this->uri->segment(5) == NULL) {
			redirect(base_url('mixtape/'.$username.'/'.$tape_url), 'refresh');	
		}
			//hash is set in the url so get the params from the url and compare to the current timestamp
			$hashGiven = $this->uri->segment(5);
			$timestamp = $this->uri->segment(4);
			$ip = $_SERVER['REMOTE_ADDR'];
			$salt = 'fuck!these&other$itesit$hiph0pvip0rd!e';
			$path = $tape->file_name;

			$hash = md5($salt . $ip . $timestamp . $path);

			//does the URI->segment(5) hash equal the hash algo from above $hash? is the time from $seg(4) greater than or equal to the current time? if so, proceed, if not, die.
			if($hashGiven == $hash && $timestamp >= time()) {
				/*****
				*BEGIN DOWNLOAD FILE
				******/
				define('ALLOWED_REFERRER', '');
				// Download folder, i.e. folder where you keep all files for download.
				// MUST end with slash (i.e. "/" )
				define('BASE_DIR', FCPATH . 'audio_uploads/' . $username . '/mixtapes/');
				// log downloads?  true/false
				define('LOG_DOWNLOADS',FALSE);
				// log file name
				define('LOG_FILE',FCPATH . 'application/logs/downloads.log');


				// Make sure program execution doesn't time out
				// Set maximum script execution time in seconds (0 means no limit)
				set_time_limit(0);

				// Get real file name.
				// Remove any path info to avoid hacking by adding relative path, etc.
				$fname = basename($tape->file_name);

				$file_path = FCPATH . 'audio_uploads/' . $username . '/mixtapes/' . $tape->file_name;
				
				if (!is_file($file_path)) {
				  die("File does not exist. Make sure you specified correct file name."); 
				}

				// file size in bytes
				$fsize = filesize($file_path); 

				// file extension
				$fext = strtolower(substr(strrchr($fname,"."),1));

				//REMOVE UNDERSCORES FROM FILENAME
				$asfname = str_replace('_', ' ', $fname);

				// set headers
				header("Pragma: public");
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Cache-Control: public");
				header("Content-Description: File Transfer");
				header("Content-Type: application/zip");
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

			} else {
			    die('<div align="center"><strong>Link has expired or is invalid.</strong><br /><a href="'.base_url('song/'.$username.'/'.$tape_url).'">Listen/Download'. $tape->tape_artist . ' - ' . $tape->tape_title . '</a></div>');
			}
		}

public function download_single_track($username, $track_id, $tape_id) {
		if (!$this->ion_auth->username_check($username)) {
			redirect('errors/page_missing', 'refresh');
		}

        $track = $this->cache->model('Mixtape_model', 'get_tracks', array(array('mixtape_tracks.id'=>$track_id,'mixtape_tracks.tape_id'=>$tape_id), 'id ASC', 1,0), 300); // keep for 5 minutes
        $track = $track[0];
        $mixtape = $this->cache->model('Mixtape_model', 'get_mixtape', array($tape_id), 300); // keep for 5 minutes

		if ($this->uri->segment(5) == NULL) {
			redirect(base_url('mixtape/'.$username.'/'.$mixtape->tape_url), 'refresh');	
		}
			//hash is set in the url so get the params from the url and compare to the current timestamp
			$hashGiven = $this->uri->segment(7);
			$timestamp = $this->uri->segment(6);
			$ip = $_SERVER['REMOTE_ADDR'];
			$salt = 'fuck!these&other$itesit$hiph0pvip0rd!e';
			$path = $mixtape->file_name;

			$hash = md5($salt . $ip . $timestamp . $path);

			//does the URI->segment(5) hash equal the hash algo from above $hash? is the time from $seg(4) greater than or equal to the current time? if so, proceed, if not, die.
			if($hashGiven == $hash && $timestamp >= time()) {
				/*****
				*BEGIN DOWNLOAD FILE
				******/
				define('ALLOWED_REFERRER', '');
				// Download folder, i.e. folder where you keep all files for download.
				// MUST end with slash (i.e. "/" )
				define('BASE_DIR', FCPATH . 'audio_uploads/' . $username . '/mixtapes/' . $mixtape->tape_url);
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

				if (!isset($track) || empty($track)) {
				  die("Please specify a song to download.");
				}

				// Get real file name.
				// Remove any path info to avoid hacking by adding relative path, etc.
				
				$fname = basename($track->file_name);


				// get full file path (including subfolders)
				$file_path = BASE_DIR . '/' . $fname;



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
			    die('<div align="center"><strong>Link has expired or is invalid.</strong><br /><a href="'.base_url('mixtape/'.$mixtape->username.'/'.$mixtape->tape_url).'">Listen/Download '. $mixtape->tape_artist . ' - ' . $mixtape->tape_title . '</a></div>');
			}
	}
}//end class
 
/* End of file home.php */
/* Location: ./application/controllers/home.php */