<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Playlists extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('Playlist_model');
	}

	public function index()
	{
      redirect('playlists/latest','refresh');
	}

/**
 * main user generated playlist player page
 * @param  string $username
 * @param  string $url
 */
	public function player($username="", $url="") {

        $playlist = $this->cache->model('Playlist_model','get',array(array('playlists.url'=>$url,'users.username'=>$username)), 300);
        $playlist = $playlist[0];

		if (!$playlist) {
			redirect('errors/page_missing', 'refresh');
		}

        if ($playlist->status === 'private') {
            if (!$this->ion_auth->logged_in()) {
                redirect('errors/page_missing','refresh');
            } 
            if (!$this->ion_auth->is_admin()) {
                if ($this->ion_auth->user()->row()->id != $playlist->user_id) {
                    redirect('errors/page_missing','refresh');
                }
            }
        }

		$tracks = $this->buildTrackData($playlist->id);


            $this->data['meta_name'] = array(
                'description'=> 'Listen to ' . htmlspecialchars($playlist->title, ENT_QUOTES) . ' Playlist',
                'twitter:card'=>'player',
                'twitter:site'=>'@hiphopvip1',
                'twitter:title'=>htmlspecialchars($playlist->title, ENT_QUOTES) . ' Playlist',
                'twitter:description'=>'Listen to ' . htmlspecialchars($playlist->title, ENT_QUOTES) . ' Playlist created by ' . $this->uri->segment('2'),
                'twitter:image'=>song_img(),
                'twitter:player'=> $this->config->item('secure_base_url').'/embed/playlist/1/'.$username.'/'.$url,
                'twitter:player:width'=>'480',
                'twitter:player:height'=>'325',
                'twitter:player:stream:content_type'=>'audio/mp3'
                );

            $this->data['meta_prop'] = array(
                'og:title'=> htmlspecialchars($playlist->title, ENT_QUOTES) . ' Playlist',
                'og:url'=> base_url('playlist/'.$username.'/'.$url),
                'og:image'=> song_img(),
                'og:site_name'=> 'hiphopVIP',
                'og:description'=> 'Listen to ' . htmlspecialchars($playlist->title, ENT_QUOTES) . ' Playlist created by ' . $this->uri->segment('2')
                );  


        //log view
        $this->load->library('Stats');
        $stats_array = array(
            'type'=>'playlist',
            'list_id'=>$playlist->id,
            'event'=>'view',
            );

		$this->data['playlist'] = $playlist;
		$this->data['tracks'] = $tracks;
        $this->data['track_count'] = $playlist->track_count;

        $this->data['title'] = htmlspecialchars($playlist->title, ENT_QUOTES) . ' Playlist | hiphopVIP';
		$this->data['vendorCSS'] = array('apm/skin/hhvip.css','apm/skin/jquery-ui-slider.custom.css','social-likes/social-likes_classic.css');
		$this->data['vendorJS'] = array('apm/lib/jquery-ui-slider-1.10.4.custom.min.js','apm/lib/modernizr-2.5.3-custom.min.js', 'apm/lib/soundmanager2-jsmin.js', 'apm/apmplayer.js','apm/apmplayer_ui.jquery.js','social-likes/social-likes.min.js');

		$this->_render('playlist/player', $this->data);
	}

/**
 * main user generated playlist EMBED player page
 * @param  string $skin - not currently used, can be used to set custom skins/themes
 * @param  string $username
 * @param  string $url
 */
    public function embed_player($skin="", $username="", $url="") {
        if (!$this->ion_auth->username_check($username)) {
            $this->data['playlist'] = NULL;
            $this->data['playlist_status'] = "The embedded playlist no longer exists.";
        } else {

            $this->load->helper('status_helper');

            $playlist        = $this->cache->model('Playlist_model', 'get', array(array('users.username'=>$username,'playlists.url'=>$url)), 300);
            

            if (!$playlist) {
                $this->data['tape'] = NULL;
                $this->data['tape_status'] = "The embedded playlist no longer exists.";
            } else {
            $playlist = $playlist[0];

            $this->data['playlist'] = $playlist;
            
            $this->data['username'] = $username;
            $this->data['tracks'] = $this->buildTrackData($playlist->id);

            
            $this->data['tape_status'] = status_message('playlist', 'published');

            $this->data['vendorCSS']    = array('apm/skin/hhvip.css','apm/skin/jquery-ui-slider.custom.css','social/social-likes_flat.css','forms.css');
            $this->data['vendorJS']     = array('apm/lib/jquery-ui-slider-1.10.4.custom.min.js','apm/lib/modernizr-2.5.3-custom.min.js', 'apm/lib/soundmanager2-jsmin.js', 'apm/apmplayer.js','apm/apmplayer_ui.jquery.js','social-likes/social-likes.min.js');

            } // song exists
        }//user exists

        $this->_render('playlist/embed_player', $renderData='EMBED', $this->data);

    }

    /**
     * playlist for artist. takes the artist name from URI segment 3 and returns a playlist using the search function from the User model
     * @param  string $artist
     * @return playlist
     */
    public function artist($artist) {

        $artist = str_replace('%20', ' ', $this->uri->segment(3));
        $songs = $this->cache->model('Song_model','search',array(array('status'=>'published'), $artist, 150, 0, 'songs.song_id DESC', 'EXCLUDE_DESCRIPTION'), 300);

        if (!$songs) {
            redirect('errors/no_artist_playlist');
        }

        foreach ($songs as $key => $song) {
             if ($song->external_source == 'soundcloud') {
                $http_file_path = 'http://api.soundcloud.com/tracks/'.$song->external_file.'/stream?consumer_key=' . $this->config->item('soundcloud_client_id');
            } else {
                $http_file_path = getSignedURL($this->config->item('cloudfront_music') . '/tracks/' . $song->username . '/' . $song->file_name, '84000');
            }

            $producer   = (!empty($song->song_producer)) ? ' (Prod. ' . htmlspecialchars($song->song_producer, ENT_QUOTES). ')' : NULL;
            $featuring  = (!empty($song->featuring)) ? ' (Feat. ' . htmlspecialchars($song->featuring, ENT_QUOTES) . ') ' : NULL;


          $song_data[] = array(
                'identifier'=>$song->song_id,
                'type'=>'audio',
                'host'=>$song->song_id,
                'title'=>htmlspecialchars($song->song_title, ENT_QUOTES),
                'artist'=>htmlspecialchars($song->song_artist, ENT_QUOTES),
                'program'=>$featuring . $producer,
                'image_lg'=>song_img($song->username, $song->song_url, $song->song_image, 300),
                'image_sm'=>$song->file_name,
                'url'=>base_url('song/' . $song->username . '/' . $song->song_url),
                'external_url'=>$song->external_url,
                'http_file_path'=>$http_file_path
                );
        }
        $this->data['tracks'] = json_encode($song_data,JSON_UNESCAPED_SLASHES);
        $this->data['track_count'] = count($song_data);

            //get artist image from last.fm API and add to cache.
            //if no image present, display placeholder
            //
            if ($this->cache->get('images/playlists/' . urlencode($artist))) {
                $this->data['lastfm_image'] = $this->cache->get('playlist_images/' . urlencode($artist));
            } else {
    
                $lastfm_key = 'c6f979d14e2320fb59955afc3e923133';
                $lastfm_artist = urlencode($artist);
                $lastfm_data    = "http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist={$lastfm_artist}&api_key={$lastfm_key}&format=json";
                $json    = @file_get_contents($lastfm_data);
                $lastfm_results    = json_decode($json);
                $image = get_object_vars($lastfm_results->artist->image[4]);
                
                if (!empty($image['#text'])) {
                    $this->data['lastfm_image'] = $image['#text'];
                } else {
                    $this->data['lastfm_image'] = '//secure.hiphopvip.com/resources/img/placeholders/playlist_img.jpg';
                }

                $this->cache->write($this->data['lastfm_image'], 'images/playlists/' . urlencode($artist));
            }


            $this->data['meta_name'] = array(
                'description'=> 'Listen to ' . $artist . ' Playlist',
                'twitter:card'=>'player',
                'twitter:site'=>'@hiphopvip1',
                'twitter:title'=>$artist . ' Playlist',
                'twitter:description'=>'Listen to ' . $artist . ' Playlist on ' . $this->lang->line('meta_title'),
                'twitter:image'=>$this->data['lastfm_image'],
                'twitter:player'=> $this->config->item('secure_base_url').'/embed/playlist/artist/1/'. $this->uri->segment(3),
                'twitter:player:width'=>'480',
                'twitter:player:height'=>'325',
                'twitter:player:stream:content_type'=>'audio/mp3'
                );

            $this->data['meta_prop'] = array(
                'og:title'=> 'Listen to a dope ' . $artist . ' Playlist on ' . $this->lang->line('meta_title'),
                'og:url'=> base_url('playlist/artist/' . $this->uri->segment(3)),
                'og:image'=> $this->data['lastfm_image'],
                'og:site_name'=> 'hiphopVIP',
                'og:description'=> $this->data['track_count'] . ' songs by ' . $artist . ' to listen to on ' . $this->lang->line('meta_title')
                ); 

            $artist = htmlspecialchars($artist, ENT_QUOTES);

        $this->data['playlist_title'] = ucwords($artist);
        $this->data['title'] = ucwords($artist) . ' Playlist | hiphopVIP';
        $this->data['vendorCSS'] = array('apm/skin/hhvip.css','apm/skin/jquery-ui-slider.custom.css','social-likes/social-likes_classic.css');
        $this->data['vendorJS'] = array('apm/lib/jquery-ui-slider-1.10.4.custom.min.js','apm/lib/modernizr-2.5.3-custom.min.js', 'apm/lib/soundmanager2-jsmin.js', 'apm/apmplayer.js','apm/apmplayer_ui.jquery.js','social-likes/social-likes.min.js');

        $this->_render('playlist/artist/artist_player', $this->data);
    }


/**
 * main artist playlist EMBED player page
 * @param  string $skin - not currently used, can be used to set custom skins/themes
 * @param  string $username
 * @param  string $url
 */
    public function artist_embed_player($skin="", $artist) {

            $this->load->helper('status_helper');

            $artist = str_replace('%20', ' ', $this->uri->segment(5));            
            $songs = $this->cache->model('Song_model','search',array(array('songs.status'=>'published'), $artist, 150, 0, 'songs.song_id DESC', 'EXCLUDE_DESCRIPTION'), 300);
            
            if (!$artist || empty($songs)) {
                $this->data['tape'] = NULL;
                $this->data['tape_status'] = "The embedded playlist no longer exists.";
            } else {                        
                foreach ($songs as $key => $song) {
                     if ($song->external_source == 'soundcloud') {
                        $http_file_path = 'http://api.soundcloud.com/tracks/'.$song->external_file.'/stream?consumer_key=' . $this->config->item('soundcloud_client_id');
                    } else {
                        $http_file_path = getSignedURL($this->config->item('cloudfront_music') . '/tracks/' . $song->username . '/' . $song->file_name, '84000');
                    }

                    $producer   = (!empty($song->song_producer)) ? ' (Prod. ' . htmlspecialchars($song->song_producer, ENT_QUOTES). ')' : NULL;
                    $featuring  = (!empty($song->featuring)) ? ' (Feat. ' . htmlspecialchars($song->featuring, ENT_QUOTES) . ') ' : NULL;


                  $song_data[] = array(
                        'identifier'=>$song->song_id,
                        'type'=>'audio',
                        'host'=>$song->song_id,
                        'title'=>htmlspecialchars($song->song_title, ENT_QUOTES),
                        'artist'=>htmlspecialchars($song->song_artist, ENT_QUOTES),
                        'program'=>$featuring . $producer,
                        'image_lg'=>song_img($song->username, $song->song_url, $song->song_image, 300),
                        'image_sm'=>$song->file_name,
                        'url'=>base_url('song/' . $song->username . '/' . $song->song_url),
                        'external_url'=>$song->external_url,
                        'http_file_path'=>$http_file_path
                        );
                }

                $this->data['tracks'] = json_encode($song_data,JSON_UNESCAPED_SLASHES);

            $this->data['artist'] = $artist;
            $this->data['artist_url'] = $this->uri->segment(5);
            $this->data['tape_status'] = status_message('playlist', 'published');

            $this->data['vendorCSS']    = array('apm/skin/hhvip.css','apm/skin/jquery-ui-slider.custom.css','social/social-likes_flat.css','forms.css');
            $this->data['vendorJS']     = array('apm/lib/jquery-ui-slider-1.10.4.custom.min.js','apm/lib/modernizr-2.5.3-custom.min.js', 'apm/lib/soundmanager2-jsmin.js', 'apm/apmplayer.js','apm/apmplayer_ui.jquery.js','social-likes/social-likes.min.js');

            } // playlist exists

        $this->_render('playlist/artist/artist_embed_player', $renderData='EMBED', $this->data);

    }


/**
 * edit page for user created playlists
 * @param  interger $id
 */
    public function edit($id) {

        if (!$this->ion_auth->logged_in()) {
            redirect('/','refresh');
        }

        $this->load->helper('form');
        $user = $this->ion_auth->user()->row();
        $playlist = $this->Playlist_model->get(array('playlists.id'=>$id));
        
        if (!$playlist) {
            redirect('manage','refresh');
        }

        $playlist = $playlist[0];

        //check if the user is an admin or if the user owns the mixtape
        if ($user->id !== $playlist->user_id && !$this->ion_auth->is_admin()) {
            redirect('/','refresh');
        }

        $this->data['form_attributes']          = array('id'=>'playlistEdit');
        $this->data['form_playlist_name']       = array('name'=>'playlist_name','id'=>'playlist_name','value'=>$playlist->title,'type'=>'text','style'=>'padding-left:10px;margin-bottom:15px');

        $this->data['vendorJS'] = array('jquery.form.js');
        $this->data['vendorJS'] = array('uploadkit/uploadkit.js','jquery-ui.min.js','plupload/plupload.full.min.js','plupload/js/jquery.plupload.queue.js','jquery.form.js','jquery.inlineEdit.js');
        $this->data['vendorCSS'] = array('uploadkit/uploadkit.css','plupload/css/jquery.plupload.queue.css','forms.css');

        $this->data['tracks'] = $this->Playlist_model->get_tracks(array('playlist_id'=>$playlist->id), 'position ASC');

        $this->data['playlist'] = $playlist;
    
        $this->data['noSidebar'] = true;
        $this->_render('playlist/edit');
    }

/**
 * ajax from edit view is posted to this function and is used to update user generated playlists
 * @return json
 */
    public function update() {

        $this->output->set_header('Content-Type: application/json; charset=utf-8');
        $user = $this->ion_auth->user()->row();
        $this->load->library('form_validation');

        if ($this->input->post('id')) {
            $playlist = $this->Playlist_model->get(array('playlists.id'=>$this->input->post('id')));

             if (!$playlist) {
                die('{"validation":"error","message":"FATAL ERROR :: PLAYLIST NOT FOUND"}');    
             }
        } else {
            redirect('/','refresh');
        }
     
        if ($this->input->post('user_id') != $user->id && !$this->ion_auth->is_admin() && $user->id != $playlist->user_id) {
                die('{"validation":"error","message":"FATAL ERROR. TYPE: A"}');
        }

        $playlist = $playlist[0];

        $this->form_validation->set_rules('playlist_name', 'Playlist Name', 'trim|required|min_length[1]|max_length[255]|xss_clean');
       
        //hidden inputs
        $id             = $this->input->post('id');
        $user_id        = $this->input->post('user_id');

        if ($this->ion_auth->is_admin()) {
            $user_id    = $playlist->user_id;
        }

        $playlist_name         = $this->input->post('playlist_name');
        $status                 = $this->input->post('status');

            if ($this->form_validation->run() == FALSE)
            {
            //validation errors
                $output_array = array('validation' => 'error', 'message' => validation_errors('<div class="error"><strong>Error!</strong> ', '</div>'));
            } else {
                $update_data = array(
                    'title'=>$playlist_name,
                    'status'=>$status,
                    'url'=>url_slug($playlist_name)
                    );

                $update_where = array('playlists.id'=>$id,'playlists.user_id'=>$user_id);
                $update = $this->Playlist_model->update($update_where, $update_data);

                if(!$update) { 
                    $output_array = array('validation' => 'valid', 'response'=>'error', 'message' => 'Playlist was not updated. Did you make any changes?');
                } else {
                    //paydirt

                   //delete existing mixtape and mixtape tracks cache 
                   
                    $this->cache->model('Playlist_model','get',array(array('playlists.user_id'=>$this->ion_auth->user()->row()->id), 'id DESC'), -1);
                    $this->cache->model('Playlist_model', 'get', array(array('playlists.id'=>$id,'playlists.user_id'=>$playlist->user_id)), -1);
                    $this->cache->model('Playlist_model','get_tracks',array(array('playlist_id'=>$playlist->id), 'position ASC'), -1);

                    $output_array = array('validation' => 'valid', 'response'=>'success','message'=>'Playlist updated. <a href="'. base_url('playlist/'.$playlist->username.'/'.url_slug($playlist_name)) .'">Click here to view</a>');
                }
            }//end form_validation and JSON returns
        $this->output->set_output(json_encode($output_array));
    } 

/**
 * ajax is posted to this function to delete user generated playlists
 * @return json
 */
    public function delete() {

        if (!$this->input->post()) {
            die('ERROR 100');
        }
        if (!$this->ion_auth->logged_in()) {
            die('ERROR 101');
        }
        if ($this->input->post('uid') !== $this->ion_auth->user()->row()->id) {
            die('ERROR 102');
        }

        $playlist = $this->Playlist_model->get(array('playlists.id'=>$this->input->post('playlist_id'),'playlists.user_id'=>$this->ion_auth->user()->row()->id), 1);

        if (!$playlist) {
            die('ERROR 103');
        }
        
        $this->output->set_header('Content-Type: application/json; charset=utf-8');

        $playlist = $playlist[0];

        $where_data = array(
            'playlists.id'=> $this->input->post('playlist_id'),
            'playlists.user_id'=> $this->input->post('uid')
            );

            $delete = $this->Playlist_model->delete($where_data);
            
            if (!$delete) {
                $output_array = array('response'=>'error','message'=>'We are unable to delete your playlist at this time. Please try Again.');
                $this->output->set_output(json_encode($output_array));
            } else {
                $this->Playlist_model->delete_track(array('playlist_id'=>$this->input->post('playlist_id')));
                $output_array = array('response'=>'success','message'=>'Your playlist has been deleted.');
                $this->output->set_output(json_encode($output_array));
            }
        $this->cache->model('Playlist_model','get', array(array('playlists.user_id'=>$this->ion_auth->user()->row()->id), 'id DESC', 10, 0), -1);
    }


/**
 * deletes individual tracks from user generated playlists
 * @return json
 */
    public function delete_track() {
        if (!$this->input->post()) {
            die('ERROR 100');
        }
        if (!$this->ion_auth->logged_in()) {
            die('ERROR 101');
        }
        if ($this->input->post('uid') !== $this->ion_auth->user()->row()->id) {
            die('ERROR 102');
        }

        $playlist = $this->Playlist_model->get(array('playlists.id'=>$this->input->post('playlist_id'),'playlists.user_id'=>$this->input->post('uid')), 1);

        if (!$playlist) {
            die('ERROR 103');
        }
        
        $playlist = $playlist[0];
        $this->output->set_header('Content-Type: application/json; charset=utf-8');

        if ($playlist->track_count == 1) {
            die(json_encode(array("response"=>"error","message"=>"Can't delete the last song in a playlist")));
        }

        $where = array('id'=>$this->input->post('tid'),'playlist_id'=>$this->input->post('playlist_id'));
        $delete = $this->Playlist_model->delete_track($where);

        if ($delete) {
            $this->Playlist_model->update_track_count(array('id'=>$this->input->post('playlist_id')), array('track_count'=>'track_count - 1'));
            $this->output->set_output(json_encode(array('response'=>'success','message'=>'Playlist saved & updated! The song was removed.','id'=>$this->input->post('tid'))));
        } else {
            $this->output->set_output(json_encode(array('response'=>'error','message'=>'System Error: Unable to delete track. Try again later')));
        }
    
        $this->cache->model('Playlist_model','get_tracks',array(array('playlist_id'=>$this->input->post('playlist_id'), 'position ASC')), -1);
        $this->cache->model('Playlist_model', 'get', array(array('playlists.user_id'=>$this->ion_auth->user()->row()->id), 'id DESC', 10, 0), -1);
    
    }


/**
 * retrieves data from the Playlist model for a playlist and sets up the data array to be used with APM media player
 * @param  integer $id - playlist Track ID
 * @return json encoded playlist array that should be used with APM Player
 */
	public function buildTrackData($id) {
        $playlist = $this->cache->model('Playlist_model', 'get', array(array('playlists.id'=>$id)), 300);
        $tracks = $this->cache->model('Playlist_model', 'get_tracks', array(array('playlist_id'=>$id), 'position ASC'), 300);
        $playlist = $playlist[0];
        
        if (!$tracks) {
             return "''";
        } else {
           foreach ($tracks as $t) {

            if ($t->external_source == 'soundcloud') {
                $http_file_path = 'http://api.soundcloud.com/tracks/'.$t->external_file.'/stream?consumer_key=' . $this->config->item('soundcloud_client_id');
            } else {
                $http_file_path = getSignedURL($this->config->item('cloudfront_music') . '/tracks/' . $t->username . '/' . $t->file_name, '86400');
            }

            $producer   = (!empty($t->song_producer)) ? ' (Prod. ' . htmlspecialchars($t->song_producer, ENT_QUOTES). ')' : NULL;
            $featuring  = (!empty($t->featuring)) ? ' (Feat. ' . htmlspecialchars($t->featuring, ENT_QUOTES) . ') ' : NULL;

            $data[] = 
            array(
                'identifier'=>$t->id,
                'list_id'=>$id,
                'type'=>'audio',
                'host'=>$t->id,
            	'title'=>htmlspecialchars($t->song_title, ENT_QUOTES),
                'artist'=>htmlspecialchars($t->song_artist, ENT_QUOTES),
                'program'=>$featuring . $producer,
                'image_lg'=>song_img($t->username, $t->song_url, $t->song_image, 300),
            	'image_sm'=>$t->file_name,
                'url'=>base_url('song/' . $t->username . '/' . $t->song_url),
                'external_url'=>$t->external_url,
            	'http_file_path'=>$http_file_path
            	);
            }
            return json_encode($data,JSON_UNESCAPED_SLASHES);
        }
    }

/**
 * modal on song player page sends data via AJAX to this function. 
 * Function is used to add a song to an existing playlist. 
 * Also used to create a new playlist.
 * @param  [string] $username - URI segment 2 from song player page, used to get the right song from the DB
 * @param  [string] $song_url - URI Segment 3 from song player page, used to get the right song from the DB
 * @return [json]
 */
    public function ajax_add_to_playlist($username = NULL, $song_url = NULL) {
       
        $this->output->set_header('Content-Type: application/json; charset=utf-8');

//TODO --- MAKE THE NOT LOGGED IN THING WORK
        if (!$this->ion_auth->logged_in()) {
            $this->output->set_output(json_encode(array('response_type'=>'error','message'=>'Please Login','response_reason'=>'guest')));
        }

        $song = $this->Song_model->get_song_where(array('users.username'=>$username,'songs.song_url'=>$song_url));
        $output_message = NULL;
        $output_URL = NULL;

        //used for deleting cache, need to get the correct
        $cache_playlist_ids = array();

    //create a playlist
    if($this->input->post('playlist_name')) {
        $create = array(
            'user_id'=>$this->ion_auth->user()->row()->id,
            'title'=>$this->input->post('playlist_name'),
            'status'=>$this->input->post('status'),
            'url'=>url_slug($this->input->post('playlist_name'))
            );
        //check if there is an existing playlist with the same URL
        $existing = $this->Playlist_model->get(array('playlists.url'=>url_slug($this->input->post('playlist_name')),'users.username'=>$this->ion_auth->user()->row()->username), 1);

        if ($existing) {
            $existing = $existing[0];
            
            $output_message = 'EXISTING';
            $output_URL = 'playlist/' . $this->ion_auth->user()->row()->username . '/' . url_slug($this->input->post('playlist_name'));

            $cache_playlist_ids[] = $existing->id;

            $add_data = array(
                'song_id'=>$song->song_id,
                'playlist_id'=>$existing->id,
                'position'=>$existing->track_count + 1
                );

            $this->Playlist_model->add_track($add_data);
            $this->Playlist_model->update_track_count(array('id'=>$existing->id),array('track_count'=>'track_count + 1'));
            $output =  array('response'=>'success','message'=>'Added song to existing playlist','url'=>$output_URL);

        } else {
            $create_list = $this->Playlist_model->add($create);
            if ($create_list) {
                //successfully created the playlist, now add the song to the list
                $add_data = array(
                    'song_id'=>$song->song_id,
                    'playlist_id'=>$create_list,
                    'position'=>'1'
                    );

            $cache_playlist_ids[] = $create_list;
                $this->Playlist_model->add_track($add_data);
                $this->Playlist_model->update_track_count(array('id'=>$create_list),array('track_count'=>'track_count + 1'));

                $output_URL = 'playlist/' . $this->ion_auth->user()->row()->username . '/' . url_slug($this->input->post('playlist_name'));
                $output =  array('response'=>'success','message'=>'Successfully created the playlist','url'=>$output_URL);
            } else {
                $output =  array('response'=>'error','message'=>'Could not create the playlist');
            } //create_list
        } //existing

    } 

    //add to existing
    if ($this->input->post('playlist_id')) {
        $playlist = $this->Playlist_model->get(array('playlists.id'=>$this->input->post('playlist_id')));
        $playlist = $playlist[0];

        $playlist_ids = explode(',', $this->input->post('playlist_id'));

        foreach ($playlist_ids as $key => $pid) {
            $cache_playlist_ids[] = $pid;
            $add_data = array(
                'song_id'=>$song->song_id,
                'playlist_id'=>$pid,
                'position'=> $playlist->track_count + 1
                );
            $add = $this->Playlist_model->add_track($add_data);
            $this->Playlist_model->update_track_count(array('playlists.id'=>$pid),array('playlists.track_count'=>'playlists.track_count + 1'));
            if ($add) {
                if (!empty($output_message)) {
                    $output =  array('response'=>'success','message'=>'Added the song to your existing Playlist');
                } else {
                    $output =  array('response'=>'success','message'=>'Successfully added song to playlist','url'=>$output_URL);
                }

            } else {
                $output =  array('response'=>'erorr','message'=>'Could not add the track');
            }
        }
    }

    $this->cache->model('Playlist_model','get',array(array('playlists.user_id'=>$this->ion_auth->user()->row()->id)), -1);
   
    foreach ($cache_playlist_ids as $key => $cpi) {
        $this->cache->model('Playlist_model', 'get_tracks', array(array('playlist_id'=>$cpi), 'position ASC'), -1);
    }
    
    $this->output->set_output(json_encode($output));

}

/**
 * AJAX function to update the track order on the user generated playlist edit page
 * @param  [interger] $playlist_id
 * @return [json]
 */
    public function update_sort_order($playlist_id) {
      if (!isset($playlist_id) || !$this->ion_auth->logged_in()) {
        die('Application Error: SORT ERROR 100');
      }

      $track_order = 1;

      if (!$this->input->post()) {
        redirect('/','refresh');
      }
        foreach ($this->input->post('track') as $id) {
          $where = array(
            'playlist_tracks.id'=>$id,
            'playlist_tracks.playlist_id'=>$playlist_id
          );
        
          $data = array('playlist_tracks.position'=>$track_order);
          $this->Playlist_model->update_track($where, $data);


          $track_order++;
        }

        //delete playlist and track cache so sort order is updated on the front end
        $this->cache->model('Playlist_model', 'get', array(array('playlists.id'=>$playlist_id)), -1);
        $this->cache->model('Playlist_model', 'get_tracks', array(array('playlist_id'=>$playlist_id), 'position ASC'), -1);


  }

}//end class
 
/* End of file playlists.php */
/* Location: ./application/controllers/playlists.php */