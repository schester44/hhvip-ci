<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Videos extends MY_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('Video_model');

	}

	public function index() {

		$this->load->library('pagination');

        $config['uri_segment'] = 2;
        $config['base_url'] = base_url('videos');
		$config['total_rows'] = $this->cache->model('Video_model', 'video_count', array(array('id >'=>0,'status'=>'published','video_source !='=>'0')), 300); // keep for 5 minutes
        //$config['total_rows'] = $this->Video_model->video_count(array('id >'=>0));        
        $config['per_page'] = 10;
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        
        $order = 'id DESC';
		$where = array('video_source !='=>'0','status'=>'published');

		$videos = $this->cache->model('Video_model', 'get_videos', array($where, $order, $config['per_page'], $page), 300);
        
        $this->pagination->initialize($config); 

		$this->data['meta_name'] = array('description'=>$this->lang->line('meta_description'),'twitter:card'=>'summary_large_image','twitter:domain'=>base_url(),'twitter:site'=> $this->lang->line('meta_twitter'),'twitter:title'=> $this->lang->line('meta_title'),'twitter:creator'=>$this->lang->line('meta_twitter'),'twitter:description'=>$this->lang->line('meta_description'),'twitter:image:src'=>base_url('resources/img/placeholders/song_img.jpg'));
		$this->data['meta_prop'] = array('og:title'=> $this->lang->line('meta_title'),'og:url'=> base_url('/'),'og:site_name'=> 'hiphopVIP','og:description'=> $this->lang->line('meta_description'));
        $this->data['pagination'] = $this->pagination->create_links();
		$this->data['videos'] = $videos;

		$this->_render('videos/index',$this->data);
	}

	public function play($username=NULL,$title=NULL) {
		//video player page
		if (!$this->ion_auth->username_check($username) || !$this->uri->segment('3')) {
			redirect('videos', 'refresh');
		}

		$user = $this->User_model->get_user_info($username);

		$existsWhere = array('video_url'=>$this->uri->segment('3'),'user_id'=>$user->id);
		$videoExists = $this->Video_model->get_videos($existsWhere);

		if (!empty($videoExists)) {
			$video = $videoExists[0];
			$this->data['video'] = $video;
		} else {
			$this->session->set_flashdata('videoError', '<div class="alert alert=error" style="color:red;font-weight:bold;text-align:center">The video you were looking for does not exist. Try one of the videos below.</div>');
			redirect('videos','refresh');
		}

			$creator = (!empty($user->twitter_handle)) ? '@' . $user->twitter_handle : '@hiphopvip1';

			$this->data['meta_name'] = array(
				'description'=> htmlspecialchars('Watch ' . $video->video_title, ENT_QUOTES),
				'twitter:card'=>'summary_large_image',
				'twitter:domain'=>base_url(),
				'twitter:site'=> '@hiphopvip1',
				'twitter:title'=> htmlspecialchars($video->video_title, ENT_QUOTES),
				'twitter:description'=>htmlspecialchars($video->video_description, ENT_QUOTES),
				'twitter:image:src'=>video_img($video->username, $video->video_img),
				'twitter:creator'=>$creator
				);
			$this->data['meta_prop'] = array(
				'og:title'=> htmlspecialchars('Watch ' . $video->video_title, ENT_QUOTES),
				'og:url'=> base_url('videos/'.$user->username.'/'.$video->video_url),
				'og:image'=> video_img($video->username, $video->video_img),
				'og:site_name'=> 'hiphopVIP',
				'og:description'=> htmlspecialchars($video->video_description, ENT_QUOTES)
				);

		$this->_render('videos/play', $this->data);
	}

	public function add() {

		if (!$this->ion_auth->logged_in()) {
			redirect('videos','refresh');
		}

     	$this->load->helper('form');
        $this->data['vendorJS'] = array('jquery.form.js');
		$this->data['vendorCSS'] = array('forms.css');

		$this->data['form_video_title'] 		= array('name'=>'title','id'=>'title','type'=>'text');
		$this->data['form_video_url'] 			= array('name'=>'url','id'=>'url','type'=>'text');
		$this->data['form_video_description'] 	= array('name'=>'description','id'=>'description','type'=>'textarea');

		$this->_render('videos/add', $this->data);
		// submit videos to database, available to admins only
	}
	public function edit($id) {

		if (!$this->ion_auth->logged_in() || !isset($id)) {
			//redirect('videos','refresh');
		}

		$this->load->model('Video_model');
     	$this->load->helper('form');

     	$user = $this->ion_auth->user()->row();

		$video = $this->Video_model->get_videos(array('videos.id'=>$id));
		if ($video) {
			$video = $video[0];
		} else {
			redirect('videos', 'refresh');
		}

		if ($user->id !== $video->user_id && !$this->ion_auth->is_admin()) {
			//redirect('/','refresh');
		}

        if ($video->video_source === 'youtube') {
        	$link = 'http://youtube.com/watch?v=' . $video->video_id;
        } elseif ($video->video_source === 'vimeo') {
        	$link = 'http://vimeo.com/' . $video->video_id;
        } else {
        	$link = $video->video_id;
        }

		$this->data['form_video_title'] 		= array('name'=>'title','id'=>'title','value'=>$video->video_title,'type'=>'text');
		$this->data['form_video_url'] 			= array('name'=>'url','id'=>'url','value'=>$link,'type'=>'text');
		$this->data['form_video_description'] 	= array('name'=>'description','id'=>'description','value'=>$video->video_description,'type'=>'textarea');

        $this->data['vendorJS'] = array('jquery.form.js');
		$this->data['vendorCSS'] = array('forms.css');
		$this->data['video'] = $video;

		$this->_render('videos/edit',$this->data);

	}
	public function update() {
		$this->load->library('form_validation');
        $this->load->model('Video_model');
        $this->output->set_header('Content-Type: application/json; charset=utf-8');

     	$user = $this->ion_auth->user()->row();

       $this->form_validation->set_rules('title', 'Video Title', 'trim|required|min_length[2]|max_length[255]|xss_clean');
       $this->form_validation->set_rules('url', 'Video URL', 'trim|required|min_length[2]|max_length[255]|xss_clean');
       $this->form_validation->set_rules('description', 'Description', 'trim|min_length[2]|max_length[500]|xss_clean');                      

       $id 					= $this->input->post('vid', TRUE);
       $title 				= $this->input->post('title', TRUE);
       $url 				= $this->input->post('url', TRUE);
       $description 		= $this->input->post('description', TRUE);
        

        $video = $this->Video_model->get_videos(array('id'=>$id));
        $video = $video[0];

        //make sure the user owns the file, or is an admin
        if ($video->user_id !== $user->id && !$this->ion_auth->is_admin()) {
        	redirect('videos','refresh');
        }

	    $video_id = 0;
	    $video_source = 0; 

       if ($this->form_validation->run() === FALSE) {
            //validation errors
	        $output_array = array('validation' => 'error', 'message' => validation_errors('<div class="alert alert-error"><strong>Error!</strong> ', '</div>'));
	        $this->output->set_output(json_encode($output_array));
        } else {
			
			$vimeo = json_decode($this->vimeoCurl($url));

	       if (isset($vimeo->video_id)) {
	       		$video_id = $vimeo->video_id;
	       		$video_source = 'vimeo';
	       		$video_img	= $vimeo->thumbnail_url;

	       } elseif (preg_match('/^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/', $url)) { 
	            parse_str(parse_url($url, PHP_URL_QUERY),$my_array_of_vars);
		            if (!empty($my_array_of_vars['v'])) {
		           		$video_id = $my_array_of_vars['v'];
		       			$video_source = 'youtube';
		       			$video_img = 'http://img.youtube.com/vi/'.$video_id.'/hqdefault.jpg';
		            }
	       }

	       if ($video_id === 0 || $video_source === 0) {
	       		$output_array = array('validation' => 'valid', 'response'=>'error', 'message' => '<div class="alert alert-error">Video URL Not Supported. Only Vimeo and Youtube Links are supported.<br />Ensure there is an http:// or https:// in front of the URL string.</div>');
	        	$this->output->set_output(json_encode($output_array));
	       } else {

              $this->load->library('images');
            // Make sure the fileName is unique
            if (file_exists(FCPATH . 'asset_uploads/'.$user->username.'/videos/'.url_slug($title).'.jpg')) {
                $fileName = url_slug($title) . '_1';
            } else {
            	$fileName = url_slug($title);
            }

            	 $data = array(
	        	'user_id'=>$user->id,
	        	'video_title'=>$title,
	        	'video_description'=>$description,
	        	'video_source'=>$video_source,
	        	'video_id'=>$video_id,
	        	'video_img'=>$fileName . '.jpg',
	        	'upload_date'=>time()
	        	);  

            $resizeDir = FCPATH . 'asset_uploads/'.$user->username.'/videos/';
		     $this->images->uploadRemoteFile($video_img, $fileName, 'videos',$user->id);
		     $this->images->resizeImage($resizeDir, $fileName, 'jpg', '128');

		     $where = array('id'=>$id);
	     	 $update = $this->Video_model->update_video($where, $data);
	      		if (!$update) {
	      			$output_array = array('validation' => 'valid', 'response'=>'error', 'message' => 'Unable to Update. Please try again');
		        	$this->output->set_output(json_encode($output_array));
	      		} else {
	      			$output_array = array('validation' => 'valid', 'response'=>'success', 'message' => 'Video Updated. <a href="'.base_url('videos/'.$user->username.'/'.$video->video_url).'">Click Here to View.</a>');
		        	$this->output->set_output(json_encode($output_array));
	      		}

	       }


        }
	}

	public function upload() {

     	$this->load->library('form_validation');
        $this->load->model('Video_model');
        $this->output->set_header('Content-Type: application/json; charset=utf-8');
     	
     	$user = $this->ion_auth->user()->row();

       $this->form_validation->set_rules('title', 'Video Title', 'trim|required|min_length[2]|max_length[255]|xss_clean');
       $this->form_validation->set_rules('url', 'Video URL', 'trim|required|min_length[2]|max_length[255]|xss_clean');
       $this->form_validation->set_rules('description', 'Description', 'trim|min_length[2]|max_length[500]|xss_clean');                      

       $title 				= $this->input->post('title', TRUE);
       $url 				= $this->input->post('url', TRUE);
       $description 		= $this->input->post('description', TRUE);

	   $video_id = 0;
	   $video_source = 0; 

       if ($this->form_validation->run() === FALSE) {
            //validation errors
	        $output_array = array('validation' => 'error', 'message' => validation_errors('<div class="alert alert-error"><strong>Error!</strong> ', '</div>'));
	        $this->output->set_output(json_encode($output_array));
        } else {
	       $vimeo = json_decode($this->vimeoCurl($url));

	       if (isset($vimeo->video_id)) {
	       		$video_id = $vimeo->video_id;
	       		$video_source = 'vimeo';
	       		$video_img	= $vimeo->thumbnail_url;

	       } elseif (preg_match('/^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/', $url)) { 
	            parse_str(parse_url($url, PHP_URL_QUERY),$my_array_of_vars);
		            if (!empty($my_array_of_vars['v'])) {
		           		$video_id = $my_array_of_vars['v'];
		       			$video_source = 'youtube';
		       			$video_img = 'http://img.youtube.com/vi/'.$video_id.'/hqdefault.jpg';
		            }
	       }

	       if ($video_id === 0 || $video_source === 0) {
	       		$output_array = array('validation' => 'valid', 'response'=>'error', 'message' => '<div class="alert alert-error">Video Source Not Supported. Only Vimeo and Youtube Links are supported.<br />Ensure there is an http:// or https:// in front of the URL string.</div>');
	        	$this->output->set_output(json_encode($output_array));
	       } else {

              $this->load->library('images');
            // Make sure the fileName is unique
            if (file_exists(FCPATH . 'asset_uploads/'.$user->username.'/videos/'.url_slug($title).'.jpg')) {
                $fileName = url_slug($title) . '_1';
            } else {
            	$fileName = url_slug($title);
            }

            	 $data = array(
	        	'user_id'=>$user->id,
	        	'video_title'=>$title,
	        	'video_description'=>$description,
	        	'video_source'=>$video_source,
	        	'video_id'=>$video_id,
	        	'video_url'=>url_slug($title),
	        	'video_img'=>$fileName . '.jpg',
	        	'upload_date'=>time()
	        	);  

            $resizeDir = FCPATH . 'asset_uploads/'.$user->username.'/videos/';
		     $this->images->uploadRemoteFile($video_img, $fileName, 'videos',$user->id);
		     $this->images->resizeImage($resizeDir, $fileName, 'jpg', '150');

	     	 $upload = $this->Video_model->add_video($data);
	      		if (!$upload) {
	      			$output_array = array('validation' => 'valid', 'response'=>'error', 'message' => 'Unable to submit. Please try again');
		        	$this->output->set_output(json_encode($output_array));
	      		} else {
	      			$output_array = array('validation' => 'valid', 'response'=>'success', 'message' => 'Your video has been submitted but is not yet active (pending approval).<br />If approved, your video will be available <a href="'.base_url('videos/'.$user->username.'/'.url_slug($title)).'">HERE</a>.');
		        	$this->output->set_output(json_encode($output_array));
	      		}

	       }

        } //validation
	}


/* FOR USERS AND JSON */
 public function delete() {

  	$this->output->set_header('Content-Type: application/json; charset=utf-8');

    $this->load->library('form_validation');
    $this->load->model('Video_model');

    if (!$this->ion_auth->logged_in() || !$this->input->post('id')) {
    	redirect('/','refresh');
    }

    $id = $this->input->post('id');;
    $uid = $this->input->post('uid');

    $user = $this->ion_auth->user()->row();
	$video = $this->Video_model->get_videos(array('id'=>$id));

        if (!empty($video)) {
       	 $video = $video[0];
        } else {
        	redirect('/','refresh');
        }


    if ($uid !== $user->id && $user->id !== $video->user_id && !$this->ion_auth->is_admin()) {
    	redirect('/','refresh');
    }

    $this->form_validation->set_rules('id', 'Video ID', 'trim|required|numeric');
    $this->form_validation->set_rules('uid', 'User ID', 'trim|required|numeric');

    $delete = $this->Video_model->delete_video(array('id'=>$id,'user_id'=>$uid));
    
    if ($this->form_validation->run() == FALSE) {
        $output_array = array('validation'=>'error', 'response'=>'error','message'=>'There seems to be a problem. Your video was not deleted. Please try again.');
    	$this->output->set_output(json_encode($output_array));  
    } elseif (!$delete) {
        $output_array = array('validation'=>'valid', 'response'=>'error','message'=>'We are unable to delete your video at this time. Please try Again.');
   		$this->output->set_output(json_encode($output_array));
    } else {
			//delete images
		if (file_exists(FCPATH . 'asset_uploads/' . $video->username . '/videos/' . $video->video_img)) {
    	  	unlink(FCPATH . 'asset_uploads/' . $video->username . '/videos/' . $video->video_img);
          }
         if (file_exists(FCPATH . 'asset_uploads/' . $video->username . '/videos/150_' . $video->video_img)) {
    	  	unlink(FCPATH . 'asset_uploads/' . $video->username . '/videos/150_' . $video->video_img);
          }

        $output_array = array('validation'=>'valid', 'response'=>'success','message'=>'has been deleted.');
 	   	$this->output->set_output(json_encode($output_array));
    }
}

	public function simple_delete($id) {
		//for admins
		
		if (!$this->ion_auth->logged_in() || !isset($id) || !$this->ion_auth->is_admin()) {
			echo 'set';
			redirect('videos','refresh');
		}

		$this->load->model('Video_model');

		$user = $this->ion_auth->user()->row();

		$video = $this->Video_model->get_videos(array('id'=>$id));
		
		if (empty($video)) {
			redirect('videos','refresh');
		}
		$video = $video[0];

		if ($user->id !== $video->user_id && !$this->ion_auth->is_admin()) {
			redirect('videos','refresh');
		}

			$delete = $this->Video_model->delete_video(array('id'=>$id));

			if ($delete) {
				//delete images
				if (file_exists(FCPATH . 'asset_uploads/' . $video->username . '/videos/' . $video->video_img)) {
		    	  	unlink(FCPATH . 'asset_uploads/' . $video->username . '/videos/' . $video->video_img);
		          } 

		       if (file_exists(FCPATH . 'asset_uploads/' . $video->username . '/videos/150_' . $video->video_img)) {
		    	  	unlink(FCPATH . 'asset_uploads/' . $video->username . '/videos/150_' . $video->video_img);
		          }

				$this->session->set_flashdata('video_deleted','<div class="alert alert-error" style="color:red;font-weight:bold;text-align:center">Video ('.$id.') Was Deleted</div>');
				redirect('backend/videos','refresh');
			} else {
				$this->session->set_flashdata('video_deleted','<div class="alert alert-error" style="color:red;font-weight:bold;text-align:center">Unable to Delete Video ('.$id.'). Try Again</div>');
				redirect('backend/videos','refresh');
			}
	}


	public function status() {
		if (!$this->ion_auth->is_admin()) {
			redirect('/','refresh');
		}

		if (!$this->input->is_ajax_request()) {
			redirect('/', 'refresh');
		}

       	$this->output->set_header('Content-Type: application/json; charset=utf-8');

		$this->load->model('Video_model');

		$id = $this->input->post('id');
		$status = $this->input->post('status');

		$video 	= $this->Video_model->get_videos(array('videos.id'=>$id)); 

		if (empty($video)) {
			die('E. Application Error');
		} else {
			$video = $video[0];
		}

	    $data = array('status'=>'published');
	    $update = $this->Video_model->update_video(array('videos.id'=>$id),$data);

	    if (!$update) {
			$output_array = array('response'=>'error','message'=>'update failed','id'=>$id,'status'=>$status);
	    } else {
			$output_array = array('response'=>'success','id'=>$id,'status'=>$status);
	    }

        $this->output->set_output(json_encode($output_array));
	}

	public function vimeoCurl($url) {
		//retrieves info from vimeo oembed api

		if(strpos($url, "http://") === false) {
			$url = "http://" . $url;
		}
		 $curl = curl_init('http://vimeo.com/api/oembed.json?url=' . $url);
		  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		  curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		  $return = curl_exec($curl);
		  curl_close($curl);
  			return $return;
	}

}

/* End of file video.php */
/* Location: ./application/controllers/video.php */