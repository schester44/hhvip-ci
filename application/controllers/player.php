<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Player extends MY_Controller {

	public function embed($skin="", $username="", $song_title="", $color1="", $color2="") {

			$song = $this->cache->model('Song_model', 'get_song_where', array(array('username'=>$username,'song_url'=>$song_title)), 1800);

			if (!$song) {
				$this->data['song'] = NULL;
				$this->data['song_status'] = "The embedded song no longer exists.";
			} else {

			$this->load->helper('status');
			$this->data['song_status'] = status_message('song',$song->status);
			$this->data['song'] = $song;

			if ($song->song_description == '') {
				$meta_description = htmlspecialchars('Stream & download ' . $song->song_title . ' by ' . $song->song_artist, ENT_QUOTES);
			} else {
				$meta_description = htmlspecialchars($song->song_description, ENT_QUOTES);
			}

			if ($song->external_source == 'soundcloud') {
   				$this->data['mp3Source'] = 'http://api.soundcloud.com/tracks/'.$song->external_file.'/stream?consumer_key=' . $this->config->item('soundcloud_client_id');
			} else {
   				$this->data['mp3Source'] = getSignedURL($this->config->item('cloudfront_music') . '/tracks/' . $username . '/' . $song->file_name, '1800');
			}

			$this->data['meta_name'] = array(
				'description'=> html_entity_decode('Steam & download ' . $meta_description),
				'twitter:card'=>'player',
				'twitter:domain'=>base_url(),
				'twitter:site'=> $this->lang->line('meta_twitter'),
				'twitter:title'=>htmlspecialchars($song->song_artist . ' - ' . $song->song_title, ENT_QUOTES),
				'twitter:description'=>htmlspecialchars($meta_description, ENT_QUOTES),
				'twitter:image'=>song_img($song->username, $song->song_url, $song->song_image),
				'twitter:player'=>base_url('embed/1/'.$username.'/'.$song->song_url),
				'twitter:player:width'=>'480',
				'twitter:player:height'=>'100',
				'twitter:creator'=>'@hiphopvip1',
				);

			$this->data['meta_prop'] = array(
				'og:title'=> htmlspecialchars('Stream & download ' . $song->song_artist . ' - ' . $song->song_title, ENT_QUOTES),
				'og:url'=> base_url('song/'.$username.'/'.$song->song_url),
				'og:image'=> song_img($song->username, $song->song_url, $song->song_image),
				'og:site_name'=> 'hiphopVIP',
				'og:description'=> htmlspecialchars($meta_description, ENT_QUOTES)
				);

			$this->data['username'] = $username;
			$this->data['vendorCSS']	= array('apm/skin/hhvip_embed.css','apm/skin/jquery-ui-slider.custom.css','social/social-likes_flat.css','forms.css');
			$this->data['vendorJS'] 	= array('apm/lib/jquery-ui-slider-1.10.4.custom.min.js','apm/lib/modernizr-2.5.3-custom.min.js', 'apm/lib/soundmanager2-jsmin.js', 'apm/apmplayer.js','apm/apmplayer_ui.jquery.js','social-likes/social-likes.min.js');
			
			}

		$this->_render('player/embed', $renderData='EMBED', $this->data);

	}

	public function old_embed_redirect() {

		if (isset($_GET['track']) && strpos($_GET['track'],'.mp3') !== false) {	
			$songs = $this->Song_model->get_songs_where(array('file_name'=>$_GET['track']),1,'published_date DESC');
		
			if (!$songs) {
				echo 'No Song Found. Visit <a href="'.base_url().'">hhVIP for the latest hip hop.</a>';	
			} else {
				$song = $songs[0];
				redirect('embed/1/'.$song->username.'/'.$song->song_url, 'location', 301);
			}

		} else {
			show_404();
		}

	}

	public function main($username="", $song_title="") {


		$song = $this->cache->model('Song_model', 'get_song_where', array(array('username'=>$username,'song_url'=>$song_title)), 300);
		

		if (!$song) {
			$checkSong = $this->Song_model->getUpdatedUrl(array('old_url'=>$song_title,'users.username'=>$username));
			
			if ($checkSong) {
				redirect('song/' . $username . '/' . $checkSong->current_url, '301');
			} else {
				redirect('songs/error','refresh');
			}	
		}

			$this->load->helper('status');
			$this->data['song_status'] = status_message('song',$song->status);
			
			$this->data['song'] = $song;
			$this->data['username'] = $username;
			$song_artist_full = $song->song_artist;

			if (!empty($song->featuring)) {
				$song_artist_full .= ' Feat. ' . $song->featuring;
			}

			$meta_download		= ($song->can_download === 'yes' || !empty($song->buy_link)) ? 'Stream & Download ' : 'Listen to ';
			$meta_description 	= (empty($song->song_description)) ? $meta_download . htmlspecialchars($song->song_title, ENT_QUOTES) . ' by ' . $song_artist_full : htmlspecialchars($song->song_description, ENT_QUOTES);

			if ($song->external_source == 'soundcloud') {
   				$this->data['mp3Source'] = 'http://api.soundcloud.com/tracks/'.$song->external_file.'/stream?consumer_key=' . $this->config->item('soundcloud_client_id');
			} else {
   				$this->data['mp3Source'] = getSignedURL($this->config->item('cloudfront_music') . '/tracks/' . $username . '/' . $song->file_name, '1800');
			}

			$this->data['twitter_via'] = (!empty($song->twitter_handle) ? 'data-via="' . $song->twitter_handle . '"' : NULL);

			$this->data['meta_name'] = array(
				'description'=> htmlspecialchars($meta_description, ENT_QUOTES),
				'twitter:card'=>'player',
				'twitter:site'=>'@hiphopvip1',
				'twitter:title'=>htmlspecialchars($song->song_artist, ENT_QUOTES) . ' - ' .htmlspecialchars($song->song_title, ENT_QUOTES),
				'twitter:description'=>htmlspecialchars($song->song_description, ENT_QUOTES),
				'twitter:image'=>song_img($song->username, $song->song_url, $song->song_image),
				'twitter:player'=> $this->config->item('secure_base_url').'/embed/song/'.$username.'/'.$song->song_url,
				'twitter:player:width'=>'480',
				'twitter:player:height'=>'100',
				'twitter:player:stream'=>$this->data['mp3Source'],
				'twitter:player:stream:content_type'=>'audio/mp3'
				);
			$this->data['meta_prop'] = array(
				'og:title'=> $meta_download . htmlspecialchars($song_artist_full . ' - ' . $song->song_title, ENT_QUOTES),
				'og:url'=> base_url('song/'.$username.'/'.$song->song_url),
				'og:image'=> song_img($song->username, $song->song_url, $song->song_image),
				'og:site_name'=> 'hiphopVIP',
				'og:description'=> htmlspecialchars($meta_description, ENT_QUOTES)
				);	

		/* - display latest tracks if we cannot find songs by artist - */
		$recentLimit = 10;

		$userRecentWhere = array('status'=>'published','song_url !='=>$song->song_url);
		$userRecentTracks = $this->cache->model('Song_model', 'search', array($userRecentWhere, $song->song_artist, $recentLimit, 0, 'song_id DESC'), 1800);
		
		$userRecentCount = count($userRecentTracks);
		$this->data['recent_count'] = $userRecentCount;
		
		if ($userRecentCount < 5) {
			$latestWhere = array('status'=>'published','song_url !='=>$song->song_url);
			$latestTracks = $this->cache->model('Song_model', 'get_songs_where', array($latestWhere, $recentLimit,'song_id DESC'), 1800);
			$this->data['more_tracks'] = $latestTracks;
			$this->data['more_tracks_title'] = 'Other Songs You Might Like';
			$this->data['start_a_playlist'] = FALSE;
		} else {
			$this->data['start_a_playlist'] = TRUE;
			$this->data['more_tracks'] = $userRecentTracks;
			$this->data['more_tracks_title'] = 'More Songs From ' .$song->song_artist;

		}

				/*
		VOTING BUTTONS, COLOR THEM WHEN LOGGED IN
		 */
		if ($this->ion_auth->logged_in()) {
			$voteIds  = array();
			foreach ($this->data['more_tracks'] as $key => $s) {
					$voteIds[] = $s->song_id;
			}

			$votes = $this->Vote_model->get_where_in('vote_song_id,vote_rating', $voteIds, $this->ion_auth->user()->row()->id);
			$this->data['userVotes'] = json_encode($votes);

		} else {
			$this->data['userVotes'] = "";
		}


		/* - END display latest tracks - */

		$nextSongSQL = array('song_id >'=>$song->song_id,'status'=>'published');
		$nextSong = $this->cache->model('Song_model', 'get_songs_where', array($nextSongSQL, "1","song_id ASC"), 1800);

		$prevSongSQL = array('song_id <'=>$song->song_id,'status'=>'published');
		$prevSong = $this->cache->model('Song_model', 'get_songs_where', array($prevSongSQL, "1","song_id DESC"), 1800);


		//get next and previous songs
		if ($nextSong) {
			foreach ($nextSong as $ns) {
				$this->data['nextSong'] = $ns;
			}
		} else {
			$this->data['nextSong'] = null;
		}

		if ($prevSong) {
			foreach ($prevSong as $ps) {
				$this->data['prevSong'] = $ps;
			}	
		} else {
			$this->data['prevSong'] = NULL;
		}
		
		if ($this->ion_auth->logged_in()) {
			$this->data['favorite'] = ($this->Social_model->get_favorite(array('song_id'=>$song->song_id,'user_id'=>$this->ion_auth->user()->row()->id)) ? TRUE : FALSE);

			$this->load->model('Playlist_model');
			$this->data['user_playlists']	= $this->Playlist_model->get(array('playlists.user_id'=>$this->ion_auth->user()->row()->id), 'id DESC');
		}

		$this->data['promoted']			= $this->cache->library('sorting', 'get_list', array('songs','promoted',5));


		$this->data['featuring'] = (!empty($song->featuring) ? '<span style="display:block"><span style="font-weight:bold">Featuring: </span> ' . htmlspecialchars($song->featuring, ENT_QUOTES) . '</span>': NULL);
		$this->data['producer'] = (!empty($song->song_producer) ? '<span style="display:block"><span style="font-weight:bold">Producer: </span> ' . htmlspecialchars($song->song_producer, ENT_QUOTES) . '</span>': NULL);
		$this->data['album'] = (!empty($song->album) ? '<span style="display:block"><span style="font-weight:bold">Album: </span> ' . htmlspecialchars($song->album, ENT_QUOTES) . '</span>': NULL);
		$this->data['releaseDate'] = date( 'm/d/Y', $song->published_date);
	    $this->data['description']       	= (!empty($song->song_description) ? htmlspecialchars($song->song_description, ENT_QUOTES) : NULL);
		$this->data['visibility'] = ($this->ion_auth->logged_in() && $this->ion_auth->user()->row()->username == $this->uri->segment(2) || $this->ion_auth->is_admin() ? '<span style="display:block"><span style="font-weight:bold">Visibility: </span>' . ucfirst($song->visibility) . '</span>' : NULL);	
		$this->data['songArtist']   	= htmlspecialchars($song->song_artist, ENT_QUOTES);
	    $this->data['songTitle']      	= htmlspecialchars($song->song_title, ENT_QUOTES);

		$this->data['featured_nav'] 	= ($song->featured === 'yes') ? TRUE : FALSE;
		$this->data['promoted_nav'] 	= ($song->promoted === 'yes') ? TRUE : FALSE;
		$this->data['copyright_status'] = ($song->status === 'copyright') ? TRUE : FALSE;

		$this->data['coreJS'] = array('lists.js');
		$this->data['vendorCSS'] = array('apm/skin/hhvip.css','apm/skin/jquery-ui-slider.custom.css','social-likes/social-likes_classic.css');
		$this->data['vendorJS'] = array('apm/lib/jquery-ui-slider-1.10.4.custom.min.js','apm/lib/modernizr-2.5.3-custom.min.js', 'apm/lib/soundmanager2-jsmin.js', 'apm/apmplayer.js','apm/apmplayer_ui.jquery.js','social-likes/social-likes.min.js');
		
		$this->data['title'] = $meta_download . $song->song_artist . ' - ' . $song->song_title . ' on ' . SITE_TITLE;
		$this->data['title'] = htmlspecialchars($this->data['title'], ENT_QUOTES);
		$this->_render('player/main', $this->data);
	}

}