<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends MY_Controller {

    private $removePiff;

    function __construct() {
        parent::__construct();

        $this->removePiff = array(
            '-_DatPiff.com_',
            '-(DatPiff.com)',
            ' (DatPiff Exclusive)',
            ' (DatPiff',
            ' Exclusive',
            'DatPiff.com',
            'datpiff',
            'DatPiff',
            'Exclusive)');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }

        $this->load->helper('form');
        //user info in view
        $this->data['user'] = $this->ion_auth->user()->row();
    }


    function index() {

        $this->data['s3_bucket'] = $this->config->item('s3_uploads_bucket');
         
        // these can be found on your Account page, under Security Credentials > Access Keys
        $this->data['s3_accessKeyId'] = $this->config->item('s3_accessKeyId');
        $this->data['secret'] = $this->config->item('s3_secret');
         
        $this->data['s3_policy'] = base64_encode(json_encode(array(
          // ISO 8601 - date('c'); generates uncompatible date, so better do it manually
          'expiration' => date('Y-m-d\TH:i:s.000\Z', strtotime('+1 day')), 
          'conditions' => array(
            array('bucket' => $this->data['s3_bucket']),
            array('starts-with', '$key', ''),
            array('starts-with', '$Content-Type', ''), // accept all files
            array('starts-with', '$Content-Disposition', ''), // accept all files
            // Plupload internally adds name field, so we need to mention it here
            array('starts-with', '$name', ''), 
            // One more field to take into account: Filename - gets silently sent by FileReference.upload() in Flash
            // http://docs.amazonwebservices.com/AmazonS3/latest/dev/HTTPPOSTFlash.html
            array('starts-with', '$Filename', ''),
            array('starts-with', '$success_action_status', '')
          )
        )));

        $this->data['s3_signature'] = base64_encode(hash_hmac('sha1', $this->data['s3_policy'], $this->data['secret'], true));
        $this->data['incomplete'] = $this->get_incomplete_songs();

        $this->data['delete_form_attributes'] = array('id'=>'deleteForm');
        $this->data['form_hidden_fields'] = array('song'=>'','user'=>$this->data['user']->id,'file_name'=>'filenamehereee');
      
        $this->data['vendorJS'] = array('jquery-ui.min.js','plupload/plupload.full.min.js','plupload/js/jquery.plupload.queue.js','jquery.form.js');
        $this->data['vendorCSS'] = array('plupload/css/jquery.plupload.queue.css','forms.css');

        $this->data['title'] = 'Upload Songs | ' . SITE_TITLE;
        $this->_render('upload/songs_s3',$this->data);
    }

/**
 * base_url/upload/server_upload to upload files to the local server. 
 * IMPORTANT: disable access to this function when in production
 * @return view
 */
	function server_upload() {
        $this->data['incomplete'] = $this->get_incomplete_songs();

        $this->data['delete_form_attributes'] = array('id'=>'deleteForm');
        $this->data['form_hidden_fields'] = array('song'=>'','user'=>$this->data['user']->id,'file_name'=>'filenamehereee');
        $this->data['vendorJS'] = array('jquery-ui.min.js','plupload/plupload.full.min.js','plupload/js/jquery.plupload.queue.js','jquery.form.js');
        $this->data['vendorCSS'] = array('plupload/css/jquery.plupload.queue.css','forms.css');

        $this->data['title'] = 'Upload Songs | ' . SITE_TITLE;
        $this->_render('upload/songs_server',$this->data);
	}

    public function mixtape() {

        if (!$this->ion_auth->is_admin()) {
            redirect('errors/page_missing','refresh');   
        }

        $this->data['form_attributes'] = array('id'=>'mixtapeInit');
        $this->data['form_tape_name'] = array('name'=>'tape_title','id'=>'tape_title','type'=>'text','size'=>'50','class'=>'form-control');
        $this->data['form_artist'] = array('name'=>'tape_artist','id'=>'tape_artist','type'=>'text','size'=>'50','class'=>'form-control');
        $this->data['form_artwork'] = array('name'=>'tape_image','id'=>'tape_image','type'=>'text','size'=>'50','class'=>'form-control');
        $this->data['form_video'] = array('name'=>'tape_video','id'=>'tape_video','type'=>'text','size'=>'50','class'=>'form-control');
        $this->data['form_description'] = array('name'=>'tape_description','id'=>'tape_description','type'=>'text','size'=>'50','class'=>'form-control');
        $this->data['form_can_download'] = array('name'=>'can_download','id'=>'can_download','checked'=>'checked');
        $this->data['form_buy_link'] = array('name'=>'buy_link','id'=>'buy_link','type'=>'text','size'=>'50','class'=>'form-control','placeholder'=>'https://itunes.apple.com/us/album/blacc-hollywood-deluxe-version/id896029984');

        $this->data['vendorJS'] = array('jquery-ui.min.js','plupload/plupload.full.min.js','plupload/js/jquery.plupload.queue.js','jquery.form.js');
        $this->data['vendorCSS'] = array('plupload/css/jquery.plupload.queue.css','forms.css');

        $this->_render('upload/mixtapes',$this->data);
    }

    /**
     * uploads mp3s to the local server
     * @return json
     */
	public function upload_to_server()
        {

        // prevent access unless something has been sent to the function
            if (!isset($_REQUEST) || empty($_FILES)) {
                redirect('upload', 'refresh');
            }

            // 5 minutes execution time
            @set_time_limit(5 * 60);

            // Settings
            $targetDir = FCPATH.'audio_uploads/' . $this->data['user']->username . '/';
            $cleanupTargetDir = true; // Remove old files
            $maxFileAge = 5 * 3600; // Temp file age in seconds

            // Chunking might be enabled
            $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
            $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
            $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

            // Clean the fileName for security reasons
            $fileName = preg_replace('/[^\w\-._]+/', '_', $fileName);

            // Make sure the fileName is unique but only if chunking is disabled
            if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
                $ext = strrpos($fileName, '.');
                $fileName_a = substr($fileName, 0, $ext);
                $fileName_b = substr($fileName, $ext);

                $count = 1;
                while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
                    $count++;

                $fileName = $fileName_a . '_' . $count . $fileName_b;
            }

            //Add _HIPHOPVIP.COM to filename (before .mp3 extension)

            $extension_pos = strrpos($fileName, '.');
            $fileName = substr($fileName, 0, $extension_pos) . '_HIPHOPVIP' . substr($fileName, $extension_pos);

            $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;


            // Create target dir
            if (!file_exists($targetDir)) {
                @mkdir($targetDir);
            }

            // Remove old temp files    
            if ($cleanupTargetDir) {
                if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                    die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
                }

                while (($file = readdir($dir)) !== false) {
                    $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                    // If temp file is current file proceed to the next
                    if ($tmpfilePath == "{$filePath}.part") {
                        continue;
                    }

                    // Remove temp file if it is older than the max age and is not the current file
                    if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
                        @unlink($tmpfilePath);
                    }
                }
                closedir($dir);
            }   


            // Open temp file
            if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }

            if (!empty($_FILES)) {
                if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                    die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
                }

                // Read binary input stream and append it to temp file
                if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                    die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                }
            } else {    
                if (!$in = @fopen("php://input", "rb")) {
                    die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                }
            }

            while ($buff = fread($in, 4096)) {
                fwrite($out, $buff);
            }

            @fclose($out);
            @fclose($in);

            // Check if file has been uploaded
            if (!$chunks || $chunk == $chunks - 1) {
                // Strip the temp .part suffix off 
                rename("{$filePath}.part", $filePath);
            }

            // Return Success JSON-RPC response
            die('{"jsonrpc" : "2.0","result" : null,"id" : "id","cleanFileName" : "'.$fileName.'","song" : [{"file":"'.$fileName.'","uploader": "'.$this->data['user']->username.'","date":"' . date('m-d-Y h:i:s') . '"}]}');
    }  //end uploaad_to_server

    public function init_song_upload($type){

            if (!$this->input->post()) {
                redirect('upload','refresh');
            }
            switch ($type) {
                case 'other':
                    echo 'Application Error';
                    break;
                default:
                    case 'json':

                            $this->output->set_header('Content-Type: application/json; charset=utf-8');


                    //this data comes from the FileUploaded ajax
                    $size   = $this->input->post('size');
                    $uid    = $this->input->post('id');
                    $fr = json_decode($_POST['response'], true); 
                    //json response sent from upload_to_server
                    if (isset($fr)) {
                        # code...
                    $iosong = $fr['song'];
                    //get song properties inside JSON-RPC response
                    foreach ($iosong as $key => $val) {
                        $song = $val;
                    }
                    $date = $song['date'];
                    $file = $song['file'];
                    $this->load->library('tags');
                    $dir_file = FCPATH . 'audio_uploads/' . $this->data['user']->username . '/' . $file;
                    $tags = $this->tags->getTagsInfo($dir_file);

                        if (isset($tags['Title'])) {
                            $tagsTitle = $tags['Title'];
                        } else {
                            $tagsTitle = '';
                        }
                        if (isset($tags['Author'])) {
                            $tagsAuthor = $tags['Author'];
                        } else {
                            $tagsAuthor = '';
                        }

                    $data = array('user_id' => $this->data['user']->id,'file_uid' => $uid,'song_artist'=>$tagsAuthor,'song_title'=>$tagsTitle,'file_name' => $file,'upload_date' => $date,'status' => 'incomplete');

                    //send data to DB
                    $song_id = $this->Song_model->add($data); 
                    
                        if (!$song_id) {
                                 $output_array = array ('response'=> 'error', 'file'=>$song['file'],  'song' => array('id'=>$uid,'file'=>$song['file'],'size'=>$size,'date'=>$date));
                        } else {
                                $output_array = array ('response'=> 'success', 'file'=>$song['file'],  'song' => array('song_id' => $song_id,'id'=>$uid,'title'=>$tagsTitle,'artist'=>$tagsAuthor,'file'=>$song['file'],'size'=>$size,'date'=>$date,'source'=>'local'));
                        }
                        $this->output->set_output(json_encode($output_array));
                    break;
                }
            }
        }

/**
 * this function is called via ajax after we upload the file to S3. This function adds the necessary data to our sql server.
 * @param  string $type - response type
 * @return json       return song info needed to add track to incomplete list
 */
    public function init_s3_upload($type){
        if (!$this->input->post()) {
            redirect('upload','refresh');
        }

        switch ($type) {
            case 'other':
                echo 'Application Error';
                break;
            default:
                case 'json':

                $this->output->set_header('Content-Type: application/json; charset=utf-8');

                //this data comes from the FileUploaded ajax
                $size   = $this->input->post('size');
                $uid    = $this->input->post('id');
                $fileName = $this->input->post('name');

                //maybe use simplexml_load_string() to parse
                $response = $this->input->post('response');


                $date = date('Y-m-d H:i:s');
                $pretty_date = date('m-d-Y h:i:s', strtotime($date));

                /**
                 * Retrieve ID3 Tags from file
                 */
                
               // $this->load->library('tags');
               // $tags = $this->tags->getTagsInfo($file);

                $data = array(
                    'user_id' => $this->data['user']->id,
                    'file_uid' => $uid,
                    'file_name' => $fileName,
                    'upload_date' => $pretty_date,
                    'status' => 'incomplete');

                //send data to DB
                $song_id = $this->Song_model->add($data); 
                
                    if (!$song_id) {
                        //did not temp data to DB
                             $output_array = array(
                                'response'=> 'error',
                                'file'=>$fileName,
                                'song'=>array(
                                    'id'=>$uid,
                                    'file'=>$fileName,
                                    'size'=>$size,
                                    'date'=>$pretty_date)
                                );
                    } else {
                        //success
                            $output_array = array(
                                'response'=>'success', 
                                'file'=>$fileName,
                                'song'=>array(
                                    'song_id'=>$song_id,
                                    'id'=>$uid,
                                    'file'=>$fileName,
                                    'size'=>$size,
                                    'date'=>$pretty_date,
                                    'source'=>'local')
                                );
                    }
                    $this->output->set_output(json_encode($output_array));
                break;
        }
    }

/**
 * copies original mp3 from initial uploads bucket to the users directory under hhvip-music bucket.
 * Adds content disposition (good download filename)
 * Renames mp3 to player-friendly/download-download friendly format (url slug)
 * Updates filename, status, published date, etc... basically publish_song_upload with S3 features
 * Set song URL
 * @return [json] return validation error/success with song URL and all that good stuff from publish_song_upload
 */
    public function publish_s3_upload() {
        $this->load->library('s3');
        $init_bucket    = $this->config->item('s3_uploads_bucket');
        $music_bucket   = $this->config->item('s3_music_bucket');


        $this->output->set_header('Content-Type: application/json; charset=utf-8');
        
        $this->load->model('Upload_model');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('artist', 'Artist', 'trim|required|min_length[1]|max_length[200]');
        $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[1]|max_length[255]');
        $this->form_validation->set_rules('featuring', 'Featuring', 'trim||min_length[1]|max_length[255]');
        $this->form_validation->set_rules('producer', 'Producer', 'trim|min_length[1]|max_length[100]');
        $this->form_validation->set_rules('album', 'Album', 'trim|min_length[1]|max_length[255]');
        $this->form_validation->set_rules('video', 'Video', 'trim|min_length[2]');
        $this->form_validation->set_rules('image', 'Image', 'trim|min_length[2]');
        $this->form_validation->set_rules('description', 'Description', 'trim|min_length[2]|max_length[10000]');                      

        $file_uid           = $this->input->post('song_uid');
        $user_id            = $this->input->post('user_id');
        $file_name          = $this->input->post('file_name');
        $soundcloudImg      = $this->input->post('scimg');

        $artist             = $this->input->post('artist');
        $title              = $this->input->post('title');
        $featuring          = $this->input->post('featuring');
        $producer           = $this->input->post('producer');
        $album              = $this->input->post('album');
        $video              = $this->input->post('video');
        $song_image_url     = $this->input->post('image');
        $buy_link           = $this->input->post('buy_link');
        $can_download       = $this->input->post('can_download');
        $description        = $this->input->post('description');
        $songUrl            = $artist . '_' . $featuring . '_' . $title;
        $urlslug            = url_slug($songUrl);
        $published_date = time();

        $visibility         = $this->input->post('make_private');

        $sfname = md5($file_uid . time());

        /**
         * $disposition_filename is the name of the file that will be downloaded clean file name without underscores or dashes
         * $clean_filename is the actual filename of the object in the hhvp-music bucket. need a clean filename to download and use in the play         */
        
        if (!empty($featuring)) {
            $dispo_featuring = ' Feat ' . $featuring;
        } else {
            $dispo_featuring = NULL;
        }

        $visibility = (($this->input->post('make_private') == 'yes') ? 'unlisted' : 'public');

        $disposition_filename_raw = $artist . $dispo_featuring . ' - ' . $title . ' HHVIP';
        $disposition_filename = preg_replace('/[^A-Za-z0-9\-]/', ' ', $disposition_filename_raw);
        $disposition_filename = $disposition_filename . '.mp3';
        $clean_filename = url_slug($artist . '-' . $title) . '.mp3';


         //make sure its a youtube video
        if (preg_match('/^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/', $video)) { 
            parse_str( parse_url($video, PHP_URL_QUERY),$my_array_of_vars);
            $video = $my_array_of_vars['v'];
        } else {
            $video = '';
        }
        
        if ($this->form_validation->run() == FALSE)
        {
                //validation errors
            $output_array = array('validation' => 'error', 'message' => validation_errors('<div class="error"><strong>Error!</strong> ', '</div>'));
            $this->output->set_output(json_encode($output_array));

        } else if (!$this->Song_model->valid_song_exists(array('file_uid' => $file_uid,'user_id'=>$user_id,'file_name'=>$file_name))) { 
            //couldn't find song that matches the file_uid/user_id/file_name
            $output_array = array('validation' => 'valid', 'response'=>'error', 'message' => 'It appears the song doesnt exist.');
            $this->output->set_output(json_encode($output_array));
       
        } else {
            if ($this->song_exists($user_id, $file_name)) {
                $output_array = array('validation' => 'valid', 'response'=>'error', 'message' => 'Song already uploaded. Check your existing song catalog.');
                $this->output->set_output(json_encode($output_array));
            } else {

                $existing = $this->Song_model->get_song_where(array('file_uid'=>$file_uid));
                
                if (isset($_FILES['image_file'])) {
                    $uploads = $_FILES['image_file'];

                    if ($uploads['error'] == 0) {
                        $this->load->library('images');
                        $this->images->uploadLocalFile($uploads, $sfname, $urlslug);

                            $ext = pathinfo($uploads['name']);
                            $ext = $ext['extension'];
                            $image_dir = FCPATH . 'asset_uploads/' . $this->ion_auth->user()->row()->username . '/' . $urlslug . '/';
                            $size_array = array('64','150','300');
                            
                            foreach ($size_array as $size) {
                                $resize = $this->images->resizeImage($image_dir, $sfname, $ext, $size);
                            }
                            
                            if (!$resize) {
                                $output_array = array('validation' => 'valid', 'response'=>'error', 'message' => 'Unable to resize image');
                                $this->output->set_output(json_encode($output_array));
                            } else {

                            }

                        $song_image = $sfname .'.'. $ext;
                    }

                } elseif ($existing->external_source === 'soundcloud') {
                    // check if the SC track is downloable so we can display the correct download buttons in the view
                    $this->load->helper('soundcloud');
                    $sc = json_decode(_curlSoundcloud('tracks',$existing->external_file));
                    $can_download = ($sc->downloadable == '1') ? 'yes' : 'no';

                    if ($sc->purchase_url != '') {
                        $buy_link = $sc->purchase_url;
                    }

                    //upload SC Image
                    $this->load->library('images');
                    $this->images->uploadRemoteFile($existing->song_image, $sfname, $urlslug);
                    $song_image = $sfname .'.jpg';

                    $image_dir = FCPATH . 'asset_uploads/' . $this->ion_auth->user()->row()->username . '/' . $urlslug . '/';
                    $size_array = array('64','150','300');
                    foreach ($size_array as $size) {
                        $this->images->resizeImage($image_dir, $sfname, 'jpg', $size);
                    }
                } else {
                    $song_image = $existing->song_image;
                } //if album art is uploaded, elseif the track is soundcloud - get album art, else use existing

                $all_song_data = array(
                'uploader'=> $this->ion_auth->user()->row()->id,
                'song_artist' => $artist,
                'song_title' => $title,
                'song_description' => $description,
                'featuring'=> $featuring,
                'song_producer'=> $producer,
                'published_date'=> $published_date,
                'song_url'=> $urlslug,
                'album' => $album,
                'file_name' => $clean_filename,
                'video' => $video,
                'song_image' =>$song_image,
                'buy_link' => $buy_link,
                'status'=>'published',
                'visibility'=>$visibility,
                'can_download'=> $can_download,
                'sfname'=> $sfname
                );

                //gets rid of any empty post fields so we can submit everything
                $song_data = array_filter($all_song_data);
                //matches the fields of the row were looking to update
                $update_where = array(
                    'file_uid'=>$file_uid,
                    'user_id'=>$user_id,
                    'file_name'=>$file_name
                    );

                if ($existing->external_source === 'soundcloud') {

                    $song_row_updated = $this->Song_model->update($update_where, $song_data);
                
                    if(!$song_row_updated) {
                        $output_array = array('validation' => 'valid', 'response'=>'error', 'message' => 'Unable to upload song.');
                        $this->output->set_output(json_encode($output_array));
                    } else {
                        //pay dirt [song moved to correct bucket and song added to DB correctly]
                        
                        // cache -- delete 'latest songs' cache -- array properties are set in lists controller
                        $this->cache->library('sorting', 'get_list', array('songs','latest', 25, 0, ''), -1); // keep for 5 minutes

                        $output_array = array('validation' => 'valid', 'response'=>'success','scimg'=>$soundcloudImg,'message'=>'Song Uploaded..', 'song'=>array('song_id'=>$file_uid,'song_url'=>$urlslug));
                        $this->output->set_output(json_encode($output_array));
                    } // if song is uploaded


                } else {
                    if ($this->s3->copyObject($init_bucket, $this->ion_auth->user()->row()->username . '/' . $file_name, $music_bucket, 'tracks/' . $this->ion_auth->user()->row()->username . '/' . $clean_filename, S3::ACL_PRIVATE,
                    array(),
                    array('content-disposition'=>'attachment; filename=' . $disposition_filename)
                    )) {
                        
                        $song_row_updated = $this->Song_model->update($update_where, $song_data);
                    
                        if(!$song_row_updated) {
                            $output_array = array('validation' => 'valid', 'response'=>'error', 'message' => 'Unable to upload song.');
                            $this->output->set_output(json_encode($output_array));
                        } else {
                            //pay dirt [song moved to correct bucket and song added to DB correctly]
                            
                            // cache -- delete 'latest songs' cache -- array properties are set in lists controller
                            $this->cache->library('sorting', 'get_list', array('songs','latest', 25, 0, ''), -1); // keep for 5 minutes

                            $output_array = array('validation' => 'valid', 'response'=>'success','scimg'=>$soundcloudImg,'message'=>'Song Uploaded..', 'song'=>array('song_id'=>$file_uid,'song_url'=>$urlslug));
                            $this->output->set_output(json_encode($output_array));
                        } // if song is uploaded


                    } else {
                        $output_array = array('validation' => 'valid', 'response'=>'error','scimg'=>$soundcloudImg,'message'=>'Unable to process the song upload at this time. This usually occurs when there are foreign characters in the filename. Ensure the filename only contains alphanumeric characters', 'song'=>array('song_id'=>$file_uid,'song_url'=>$urlslug));
                        $this->output->set_output(json_encode($output_array));    
                    } //if song is copied to production bucket
                } //if file is soundcloud or not
            } // if existing song exists
        } // if form validation
    }

    public function init_soundcloud() {
        if (!$this->input->post()) {
            redirect('upload','refresh');
        }

        $this->output->set_header('Content-Type: application/json; charset=utf-8');
        $this->load->helper('soundcloud');

        $sc = json_decode(_resolveSoundcloud($this->input->post('scLink')));

        if (empty($sc->id)) {
             $output_array = array ('response'=>'error','message'=>'Not a valid Soundcloud Link');
        } else {

        $trackNum = $sc->id;
        $artist = substr($sc->title, 0, strpos($sc->title, " - "));
        $title  = substr($sc->title, strpos($sc->title, ' - ') + 3);

        // if no soundcloud artwork, use the users avatar
       if (empty($sc->artwork_url)) {
           $scimg = str_replace('large.jpg', 't500x500.jpg', $sc->user->avatar_url);
       } else {
            $scimg  = str_replace('large.jpg', 't500x500.jpg', $sc->artwork_url);
       }

        $uid    = 'soundcloud_' . md5($sc->title);

        $data = array(
            'user_id'=>$this->data['user']->id,
            'file_uid'=>$uid,
            'file_name'=>htmlspecialchars($artist . ' - ' . $title, ENT_QUOTES),
            'song_artist'=>$artist,
            'song_title'=>$title,
            'external_source'=>'soundcloud',
            'external_file'=>$trackNum,
            'external_url'=>$this->input->post('scLink'),
            'song_image'=>$scimg,
            'upload_date'=>date('Y-m-d H:i:s'),
            'status'=>'incomplete');


        if ($sc->streamable != 1) {
            $output_array = array('response'=>'error','message'=>'Valid Song but there doesnt seem to be a streamable URL available. Unable to upload. Please try again');
        } else {
                        //send data to DB
            $song_id = $this->Song_model->add($data); 
                        
                if (!$song_id) {
                     $output_array = array('response'=>'error','message'=>'Song Not Uploaded. Try Again');
                } else {
                    $output_array = array('response'=>'success','song' => array(
                        'song_id'=>$song_id,
                        'id'=>$uid,
                        'title'=>$title,
                        'artist'=>$artist,
                        'scimg'=>$scimg,
                        'file'=>htmlspecialchars($artist . ' - ' . $title, ENT_QUOTES),
                        'size'=>'5',
                        'date'=>date('Y-m-d H:i:s'),
                        'source'=>'soundcloud'
                        ));
                } //if song uploaded
            } //if song isn't streamable
        } // if song exists

        $this->output->set_output(json_encode($output_array));
    }
  
  /**
   * complete the song upload process, add published date, set status to published and generate url
   * @param  [string] $type currently only setup to support json data
   * @return [json]  response and validation
   */
    public function publish_song_upload($type) {

                    $this->load->helper('slug');
                    $this->load->model('Upload_model');
                    $this->load->library('form_validation');

                    //modal form validaiton
                      $this->form_validation->set_rules('artist', 'Artist', 'trim|required|min_length[1]|max_length[200]');
                      $this->form_validation->set_rules('title', 'Title', 'trim|required|min_length[1]|max_length[255]');
                      $this->form_validation->set_rules('featuring', 'Featuring', 'trim||min_length[1]|max_length[255]');
                      $this->form_validation->set_rules('producer', 'Producer', 'trim|min_length[1]|max_length[100]');
                      $this->form_validation->set_rules('album', 'Album', 'trim|min_length[1]|max_length[255]');
                      $this->form_validation->set_rules('video', 'Video', 'trim|min_length[2]');
                      $this->form_validation->set_rules('image', 'Image', 'trim|min_length[2]');
                      $this->form_validation->set_rules('description', 'Description', 'trim|min_length[2]|max_length[10000]');                      

                switch ($type) {
                    case 'other':
                        echo 'Application Error';
                        break;
                
                    default:
                     case 'json':
                
                 $this->output->set_header('Content-Type: application/json; charset=utf-8');
                        $file_uid           = $this->input->post('song_uid');
                        $user_id            = $this->input->post('user_id');
                        $file_name          = $this->input->post('file_name');
                        $soundcloudImg      = $this->input->post('scimg');

                        $artist             = $this->input->post('artist');
                        $title              = $this->input->post('title');
                        $featuring          = $this->input->post('featuring');
                        $producer           = $this->input->post('producer');
                        $album              = $this->input->post('album');
                        $video              = $this->input->post('video');
                        $song_image_url     = $this->input->post('image');
                        $buy_link           = $this->input->post('buy_link');
                        $can_download       = $this->input->post('can_download');
                        $description        = $this->input->post('description');
                        $songUrl            = $artist . '_' . $featuring . '_' . $title;
                        $urlslug            = url_slug($songUrl);
                        $published_date = time();

                        //make sure its a youtube video
                        if (preg_match('/^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/', $video)) { 
                            parse_str( parse_url($video, PHP_URL_QUERY),$my_array_of_vars);
                            $video = $my_array_of_vars['v'];
                        } else {
                            $video = '';
                        }
                        
                        //sfname stands for SHORT FILE NAME
                        //Used for saving updated files based upon an encrypted name rather then their full path name
                        $sfname = md5($file_uid . time());
                        
                        if ($this->form_validation->run() == FALSE)
                        {
                                //validation errors
                            $output_array = array('validation' => 'error', 'message' => validation_errors('<div class="error"><strong>Error!</strong> ', '</div>'));
                            $this->output->set_output(json_encode($output_array));

                        } else if (!$this->Song_model->valid_song_exists(array('file_uid' => $file_uid,'user_id'=>$user_id,'file_name'=>$file_name))) { 
                            //couldn't find song that matches the file_uid/user_id/file_name
                            $output_array = array('validation' => 'valid', 'response'=>'error', 'message' => 'It appears the song doesnt exist.');
                            $this->output->set_output(json_encode($output_array));
                       
                        } else {
                            if ($this->song_exists($user_id, $file_name)) {
                                $output_array = array('validation' => 'valid', 'response'=>'error', 'message' => 'Song already uploaded. Check your existing song catalog.');
                                $this->output->set_output(json_encode($output_array));
                            } else {

                                $existing = $this->Song_model->get_song_where(array('file_uid'=>$file_uid));
                                
                                if (isset($_FILES['image_file'])) {
                                    $uploads = $_FILES['image_file'];
                                    if ($uploads['error'] == 0) {
                                         
                                        $this->load->library('images');
                                        $this->images->uploadLocalFile($uploads, $sfname, $urlslug);

                                            $ext = pathinfo($uploads['name']);
                                            $ext = $ext['extension'];
                                            $image_dir = FCPATH . 'asset_uploads/' . $this->ion_auth->user()->row()->username . '/' . $urlslug . '/';
                                            $size_array = array('64','150','300');
                                            foreach ($size_array as $size) {
                                            $resize = $this->images->resizeImage($image_dir, $sfname, $ext, $size);
                                            }
                                            if (!$resize) {
                                    $output_array = array('validation' => 'valid', 'response'=>'error', 'message' => 'Unable to resize image');
                                    $this->output->set_output(json_encode($output_array));
                                            }

                                         $song_image = $sfname .'.'. $ext;

                                    }
                                } elseif ($existing->external_source === 'soundcloud') {

                                    // check if the SC track is downloable so we can display the correct download buttons in the view
                                    $this->load->helper('soundcloud');
                                    $sc = json_decode(_curlSoundcloud('tracks',$existing->external_file));

                                    $can_download = ($sc->downloadable == '1') ? 'yes' : 'no';

                                    if ($sc->purchase_url != '') {
                                        $buy_link = $sc->purchase_url;
                                    }

                                    //upload SC Image
                                    $this->load->library('images');
                                    $this->images->uploadRemoteFile($existing->song_image, $sfname, $urlslug);
                                    $song_image = $sfname .'.jpg';

                                    $image_dir = FCPATH . 'asset_uploads/' . $this->ion_auth->user()->row()->username . '/' . $urlslug . '/';
                                    $size_array = array('64','150','300');
                                    foreach ($size_array as $size) {
                                        $this->images->resizeImage($image_dir, $sfname, 'jpg', $size);
                                    }
                                } else {
                                    $song_image = $existing->song_image;
                                }

                                $all_song_data = array(
                                'uploader'=> $this->ion_auth->user()->row()->id,
                                'song_artist' => $artist,
                                'song_title' => $title,
                                'song_description' => $description,
                                'featuring'=> $featuring,
                                'song_producer'=> $producer,
                                'published_date'=> $published_date,
                                'song_url'=> $urlslug,
                                'album' => $album,
                                'file_name' => $file_name,
                                'video' => $video,
                                'song_image' =>$song_image,
                                'buy_link' => $buy_link,
                                'status'=>'published',
                                'can_download'=> $can_download,
                                'sfname'=> $sfname
                            );
                            //gets rid of anyy empty post fields so we can submit everything
                            $song_data = array_filter($all_song_data);
                            //matches the fields of the row were looking to update
                            $update_where = array('file_uid'=>$file_uid,'user_id'=>$user_id,'file_name'=>$file_name);


                                $song_row_updated = $this->Song_model->update($update_where, $song_data);
                                    //errors with updating the row
                                if(!$song_row_updated) {
                                    $output_array = array('validation' => 'valid', 'response'=>'error', 'message' => 'Unable to upload song.');
                                    $this->output->set_output(json_encode($output_array));
                                } else {
                                    //pay dirt

                                    // cache -- delete 'latest songs' cache -- array properties are set in lists controller
                                    $this->cache->library('sorting', 'get_list', array('songs','latest', 25, 0, ''), -1); // keep for 5 minutes

                                    $output_array = array('validation' => 'valid', 'response'=>'success','scimg'=>$soundcloudImg,'message'=>'Song Uploaded..', 'song'=>array('song_id'=>$file_uid,'song_url'=>$urlslug));
                                    $this->output->set_output(json_encode($output_array));
                                }
                            }
                        }
                break;
        }
    }

    public function init_mixtape_upload() {
        //form validation and submission, on good submit, redirect to finalizing page to finish // maybe like youtube with ajax?

        if (!$this->ion_auth->is_admin()) {
            redirect('mixtapes','refresh');   
        }

        if (!$this->input->post()) {
            redirect('upload/mixtape','refresh');
        }

        $this->load->helper('slug');
        $this->load->model('Mixtape_model');
        $this->load->library('form_validation');
       
        $this->output->set_header('Content-Type: application/json; charset=utf-8');

        $user = $this->ion_auth->user()->row();

        $this->form_validation->set_rules('tape_artist', 'Artist', 'trim|required|min_length[1]|max_length[200]');
        $this->form_validation->set_rules('tape_title', 'Mixtape Name', 'trim|required|min_length[1]|max_length[200]');
        $this->form_validation->set_rules('tape_video', 'Video', 'trim|min_length[1]|max_length[200]');
        $this->form_validation->set_rules('tape_description', 'Description', 'trim|min_length[1]|max_length[1000]');

        //make sure its a youtube video
        if (preg_match('/^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/', $this->input->post('video'))) { 
            parse_str( parse_url($this->input->post('video'), PHP_URL_QUERY),$video_vars);
            $video = $video_vars['v'];
        } else {
            $video = '';
        }

        if ($this->form_validation->run() == FALSE) { 
            $output_array = array('validation' => 'error', 'message' => validation_errors('<div class="error"><strong>Error!</strong> ', '</div>'));
        } else {

           $artist          = $this->input->post('tape_artist');
           $title           = $this->input->post('tape_title');
           $description     = $this->input->post('tape_description');
           $video           = $this->input->post('tape_video');
           $url             = url_slug($title);
           $sfname          = md5($url);
           $tape_image      = '';
           $can_download    = $this->input->post('can_download');
           $buy_link        = $this->input->post('buy_link');

           //album art upload
            if (isset($_FILES['image_file'])) {
                $uploads = $_FILES['image_file'];
                if ($uploads['error'] == 0) {
                    $this->load->library('images');
                    $urlslug = 'mixtapes/'.$url;
                    $this->images->uploadLocalFile($uploads, $sfname, $urlslug);
                        $ext = pathinfo($uploads['name']);
                        $ext = $ext['extension'];
                        $image_dir = FCPATH . 'asset_uploads/' . $this->ion_auth->user()->row()->username. '/mixtapes/' . $url . '/';
                        $size_array = array('64','150','300');
                        foreach ($size_array as $size) {
                        $resize = $this->images->resizeImage($image_dir, $sfname, $ext, $size);
                        }
                    $tape_image = $sfname .'.'. $ext;
                }
            } // -- end album art upload
    
            $uploadData = array(
                'user_id'=>$user->id,
                'uploader'=>$user->id,
                'tape_artist'=>$artist,
                'tape_title'=>$title,
                'upload_date'=>date('Y-m-d H:i:s'),
                'tape_url'=>$url,
                'tape_description'=>$description,
                'tape_image'=>$tape_image,
                'tape_video'=>$video,
                'can_download'=>$can_download,
                'buy_link'=>$buy_link,
                'published_date'=>$this->input->post('release_date')
                );

            $upload = $this->Mixtape_model->add_mixtape($uploadData);

            if ($upload) {
                $output_array = array('validation' => 'valid', 'response'=>'success','song'=>array('id'=>$upload));
            } else {
                $output_array = array('validation' => 'valid', 'response'=>'error','message'=>'Mixtape Not Uploaded');
            }

        }
    $this->output->set_output(json_encode($output_array));
    }


    public function mixtape_to_server() {
    // prevent access unless something has been sent to the function
            if (!isset($_REQUEST) || empty($_FILES)) {
                redirect('upload', 'refresh');
            }

        $userAudioDir       = FCPATH . 'audio_uploads/'.$this->ion_auth->user()->row()->username;
        $userTapeDir        = FCPATH . 'audio_uploads/'.$this->ion_auth->user()->row()->username . '/mixtapes';

            //create audio_uploads/user dir
            if (!file_exists($userAudioDir)) {
                mkdir($userAudioDir);
                file_put_contents($userAudioDir . '/index.html', '');
            }

            //create audio_uploads/user/mixtape dir
            if (!file_exists($userTapeDir)) {
                mkdir($userTapeDir);
                file_put_contents($userTapeDir . '/index.html', '');
            }

            // 5 minutes execution time
            @set_time_limit(5 * 60);

            // Settings
            $targetDir = FCPATH.'audio_uploads/' . $this->data['user']->username . '/mixtapes/';
            $cleanupTargetDir = true; // Remove old files
            $maxFileAge = 5 * 3600; // Temp file age in seconds

            // Chunking might be enabled
            $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
            $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
            $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

            // Clean the fileName for security reasons
           
            $fileName   = str_replace($this->removePiff, '', $fileName);
            $fileName   = preg_replace('/[^\w\-._]+/', '_', $fileName);

            // Make sure the fileName is unique but only if chunking is disabled
            if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
                $ext = strrpos($fileName, '.');
                $fileName_a = substr($fileName, 0, $ext);
                $fileName_b = substr($fileName, $ext);

                $count = 1;
                while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
                    $count++;

                $fileName = $fileName_a . $count . $fileName_b;
            }

            $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;


            // Create target dir
            if (!file_exists($targetDir)) {
                @mkdir($targetDir);
            }

            // Remove old temp files    
            if ($cleanupTargetDir) {
                if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                    die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
                }

                while (($file = readdir($dir)) !== false) {
                    $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

                    // If temp file is current file proceed to the next
                    if ($tmpfilePath == "{$filePath}.part") {
                        continue;
                    }

                    // Remove temp file if it is older than the max age and is not the current file
                    if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
                        @unlink($tmpfilePath);
                    }
                }
                closedir($dir);
            }   


            // Open temp file
            if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            }

            if (!empty($_FILES)) {
                if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                    die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
                }

                // Read binary input stream and append it to temp file
                if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                    die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                }
            } else {    
                if (!$in = @fopen("php://input", "rb")) {
                    die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                }
            }

            while ($buff = fread($in, 4096)) {
                fwrite($out, $buff);
            }

            @fclose($out);
            @fclose($in);

            // Check if file has been uploaded
            if (!$chunks || $chunk == $chunks - 1) {
                // Strip the temp .part suffix off 
                rename("{$filePath}.part", $filePath);
            }

            // Return Success JSON-RPC response
            die('{"jsonrpc" : "2.0","result" : null,"id" : "id","cleanFileName" : "'.$fileName.'","mixtape" : [{"file":"'.$fileName.'","uploader": "'.$this->data['user']->username.'","date":"' . date('M d, Y h:ia') . '"}]}');
    }  //end uploaad_to_server

    public function publish_mixtape_upload(){

        if (!$this->ion_auth->is_admin()) {
            redirect('mixtapes','refresh');   
        }

        $this->load->library('tags');
        $this->load->library('images');
        $this->load->model('Mixtape_model');
        //$this->output->set_header('Content-Type: application/json; charset=utf-8');
        
        $jsonResponse = json_decode($this->input->post('response'), true); 
        
        if (!isset($jsonResponse['error'])) {
            $ti = $jsonResponse['mixtape'];
            if ($ti) {
                foreach ($ti as $key => $val) {
                    $tapeInfo = $val;
                }
            }
        }
        $user       = $this->ion_auth->user()->row()->username;
        $mixtape    = $this->Mixtape_model->get_mixtape($this->input->post('mid'));

           // delete existing tracks from DB and file system
                $tracks = $this->Mixtape_model->get_tracks(array('tape_id'=>$mixtape->id));
                if ($tracks) {
                    foreach ($tracks as $key => $track) {
                        if($this->Mixtape_model->delete_track(array('tape_id'=>$mixtape->id,'id'=>$track->id))) {
                            if (file_exists('audio_uploads/'.$user.'/mixtapes/'.$track->file_name)) {
                                if(unlink('audio_uploads/'.$user.'/mixtapes/'.$track->file_name)) {
                                }
                            }
                        }
                    }
                }


        if (!isset($tapeInfo)) {
                $output_array = array('validation' => 'error', 'response'=>'error', 'message'=>'No songs were found in the zip file');
                die(json_encode($output_array));
        }

        $file = FCPATH . 'audio_uploads/'.$user.'/mixtapes/'.$tapeInfo['file'];

        if (!$mixtape) {
                    $output_array = array('validation' => 'error', 'response'=>'error', 'message'=>'Server Error. Mixtape not found.');
                    die(json_encode($output_array));
        }

        $slug = $this->input->post('slug');

        $userPhotoDir       = FCPATH . 'asset_uploads/'.$user;
        $tapePhotoDir       = FCPATH . 'asset_uploads/'.$user.'/mixtapes/';
        $tapePhotoSlugDir   = $tapePhotoDir . '/' . $slug;
        $userAudioDir       = FCPATH . 'audio_uploads/'.$user;
        $userTapeDir        = FCPATH . 'audio_uploads/'.$user . '/mixtapes';
        $tapePath           = $userTapeDir . '/' . $slug;

        $tape_id            = $mixtape->id;
        $can_download       = $mixtape->can_download;
        $mixtape_title      = $mixtape->tape_title;


        $allowExtensions = array ('txt','doc','pdf','mp3','jpg','png','gif',);
        $images = array('jpg','png','gif');
        $songs  = array('mp3'); 
        $extra_image = NULL;
        $extra_image2 = NULL;

        $zip = new ZipArchive;
        $res = $zip->open($file);

        if ($res === TRUE) {

            //create asset_uploads/user dir
            if (!file_exists($userPhotoDir)) {
                mkdir($userPhotoDir);
                file_put_contents($userPhotoDir . '/index.html', '');
            }

            //create asset_uploads/user/mixtape dir 
            if (!file_exists($tapePhotoDir)) {
                mkdir($tapePhotoDir);
                file_put_contents($tapePhotoDir . '/index.html', '');
            }

            //create asset_uploads/user/mixtape/tape-slug dir
            if (!file_exists($tapePhotoSlugDir)) {
                mkdir($tapePhotoSlugDir);
                file_put_contents($tapePhotoSlugDir . '/index.html', '');
            }

            //create audio_uploads/user dir
            if (!file_exists($userAudioDir)) {
                mkdir($userAudioDir);
                file_put_contents($userAudioDir . '/index.html', '');
            }

            //create audio_uploads/user/mixtape dir
            if (!file_exists($userTapeDir)) {
                mkdir($userTapeDir);
                file_put_contents($userTapeDir . '/index.html', '');
            }

            //create audio_uploads/user/mixtape/tape-slug dir
            if (!file_exists($tapePath)) {
                mkdir($tapePath);
                file_put_contents($tapePath . '/index.html', '');
            }
        $tape_order = 1;
        $p = 1; //for extra photos
        $song_count = 0; // how many songs are in the zip

        for ($i = 0; $i < $zip->numFiles; $i++) {

            $filename = $zip->getNameIndex($i);
            $fullFileName = $zip->statIndex($i);
            $ext = substr(strrchr($filename,'.'),1);
            $info = pathinfo($fullFileName['name']);

            if ($filename[strlen($filename)-1] == "/") {
                    //this is a folder, so do nothing
             } else {

                if (!in_array($ext, $allowExtensions)) {
                    //rogue files, delete them from the zip file
                    $zip->deleteIndex($i);
                } //end rogue


                if (in_array($ext, $images)) {
                    $photoFileName = url_slug($mixtape_title) . '-' . $i;
                    file_put_contents($tapePhotoSlugDir . '/' . $photoFileName . '.jpg', $zip->getFromIndex($i));

                    $sizes      = array('64','150','300','500');
                    foreach ($sizes as $size) {
                        $this->images->resizeImage($tapePhotoSlugDir . '/', $photoFileName, 'jpg', $size);
                    } //end foreach

                    if ($p === 1) {
                        $extra_image = $photoFileName . '.jpg';
                    } elseif ($p === 2) {
                        $extra_image2 = $photoFileName . '.jpg';
                    }
                    $p++;

                    unlink($tapePhotoSlugDir . '/' . $photoFileName . '.jpg');
              
                } //end images
                if (in_array($ext, $songs)) {
                    $song_file_name = str_replace($this->removePiff, '', $info['filename']);

                    if (file_put_contents($tapePath . '/' . file_slug($song_file_name) . '.' . $info['extension'], $zip->getFromIndex($i))){
                        //write track info to DB

                        // Get ID3 Infoc
                        $dir_file = $tapePath . '/' . file_slug($song_file_name) . '.' . $info['extension'];
                        $tags = $this->tags->getTagsInfo($dir_file);
                        $temp_title = (isset($tags['Title'])) ? $tags['Title'] : '';
                        $temp_artist = (isset($tags['Author'])) ? $tags['Author'] : '';

                        $title  = str_replace($this->removePiff, '', $temp_title);
                        $artist = str_replace($this->removePiff, '', $temp_artist);

                        $data = array(
                            'tape_id'=>$tape_id,
                            'user_id'=>$this->ion_auth->user()->row()->id,
                            'song_artist'=>$artist,
                            'song_title'=>$title,
                            'song_url'=>url_slug($song_file_name),
                            'file_name'=>$slug . '/' . file_slug($song_file_name) . '.' . $info['extension'],
                            'can_download'=>$can_download,
                            'tape_order'=>$tape_order,
                            );

                        $addTrack = $this->Mixtape_model->add_track($data);
                        $tape_order++;

                        if (!$addTrack) {
                            // output something like "THE FOLLOWING FILES COULD NOT BE PROCESSED"
                           //tape not added, error out or something
                        } else {
                            $song_count++;
                        } //add track to DB

                    } //end song move
                } // end songs 


             } // end if/else folder
         } // end for loop

         $zip->close();
         
        } else {
            echo 'failed, code:' . $res;
        } //end zip open

        if ($song_count > 0) {                
            $fileName = str_replace($this->removePiff, '', $this->input->post('name'));
            $fileName = preg_replace('/[^\w\-._]+/', '_', $fileName);

            $where =  array('id'=>$this->input->post('mid'),'tape_url'=>$this->input->post('slug'),'user_id'=>$this->ion_auth->user()->row()->id);
            $data  = array(
                'file_name'=>$fileName,
                'status'=>'published',
                'extra_image'=>$extra_image,
                'extra_image2'=>$extra_image2,
                'published_date'=>time());
            $this->Mixtape_model->update_mixtape($where, $data);                

            $output_array = array('validation' => 'valid', 'response'=>'success', 'count'=>$song_count, 'name'=>$fileName);

        } else {
            //no songs in the array
            $output_array = array('validation' => 'error', 'response'=>'error', 'message'=>'No MP3s were found in the zip file. Please try again.');   
        }
        $this->output->set_output(json_encode($output_array));
        //JSON RESPONSE SHOULD INCLUDE LIST OF SONGS   
    }


    public function get_incomplete_songs() {

        $where = array(
            'user_id'=>$this->data['user']->id,
            'status'=>'incomplete'
            );

        $all_incomplete = $this->Song_model->get_songs_where($where, $limit = '50');

        if (!$all_incomplete) {
             return "''";
        } else {
           foreach ($all_incomplete as $i) {
                $song_artist    = $i->song_artist;
                $song_title     = $i->song_title;
                $song_id        = $i->song_id;
                $user_id        = $i->user_id;
                $file_uid       =  $i->file_uid;
                $file_name      =  $i->file_name;
                $upload_date    = date('M d, Y h:ia', strtotime($i->upload_date));
                $external_source    = $i->external_source;
                
            $data[] = array('song_id'=>$song_id,'user_id'=>$user_id,'id' => $file_uid,'song_image'=>$i->song_image,'title'=>$song_title,'artist'=>$song_artist,'file' => $file_name, 'date' => $upload_date,'source'=>$external_source);
            }

            return json_encode($data);
        }

    }

    public function song_exists($user_id, $file_name) {
        $where = array('user_id'=>$user_id,'file_name'=>$file_name,'status'=>'published');
        $exists = $this->Upload_model->song_already_uploaded($where);

        if (!$exists) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * fetches the form and view for the individual songs on the upload page
     * @return [type] [description]
     */
    public function partial(){
        // $this->output->set_header('Content-Type: application/json; charset=utf-8');
        // $this->output->set_output(json_encode(array('success'=>'message','content'=>'ss')));
        $this->load->view('upload/partial', $this->data);
    }
    
}
/* End of file upload.php */
/* Location: ./application/controllers/upload.php */