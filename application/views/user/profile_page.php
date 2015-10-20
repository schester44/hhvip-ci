<div id="content" class="content section row">
    <div class="col-md-8 bg-base col-lg-8 col-xl-9">
      <?php $this->load->view('user/header'); ?>
        <div class="col-xs-12 col-sm-12 col-lg-12">
            <article class="entry style-single type-post">
      <ul style="list-style-type: none;margin-left:-40px;padding-top:15px">
      <?php if ($songs) { ?>
      <?php foreach ($songs as $key => $song): ?>

        <?php 
                    $songArtist = htmlspecialchars($song->song_artist, ENT_QUOTES);
                    $songTitle   = htmlspecialchars($song->song_title, ENT_QUOTES);
                    $songDesc    = htmlspecialchars($song->song_description, ENT_QUOTES);
                    $songFeaturing = htmlspecialchars($song->featuring, ENT_QUOTES);
                    $songProducer    = htmlspecialchars($song->song_producer, ENT_QUOTES);
      ?>
        <article class="entry style-media media type-post ">
            <div class="vote-container vote-container-list pull-left" id="vote_container_<?php echo $song->song_id;?>">
                <div id="already_voted_<?php echo $song->song_id; ?>" class="already_voted">Already Voted!</div>
                <div id="text_vote_up_<?php echo $song->song_id; ?>" class="text_vote_up_<?php echo $song->song_id; ?> vote-button vote-button-top upvote vote_up_<?php echo $song->song_id; ?>">
                    <a href="" onclick="vote(<?php echo $song->song_id; ?>, 10); return false;" title="<?php echo $this->lang->line('vote_dope_desc'); ?>"><?php echo $this->lang->line('vote_dope'); ?></a>
                </div>
                <div id="already_voted_down_<?php echo $song->song_id; ?>" class="already_voted">Already Voted!</div>
                <div id="text_vote_down_<?php echo $song->song_id; ?>" class="text_vote_down_<?php echo $song->song_id; ?> vote-button vote-button-bottom downvote vote_down_<?php echo $song->song_id; ?>">
                    <a href="#" onclick="vote(<?php echo $song->song_id; ?>, -10); return false;" title="<?php echo $this->lang->line('vote_nope_desc'); ?>"><?php echo $this->lang->line('vote_nope'); ?></a>
                </div>
            </div>

            <figure class="pull-left list-art">
                <a href="<?php echo base_url('song/' . $song->username . '/' . $song->song_url) ?>" rel="bookmark">
                <img src="<?php echo song_img($song->username, $song->song_url, $song->song_image, 150); ?>" data-src="<?php echo song_img($song->username, $song->song_url, $song->song_image, 150); ?>" alt="Listen to <?php echo $songTitle . ' by ' . $songArtist; ?>"/>
                    <noscript>
                        <img src="<?php echo song_img($song->username, $song->song_url, $song->song_image, 150); ?>"  alt="Listen to <?php echo $songTitle . ' by ' . $songArtist; ?>"/>
                    </noscript>
                </a>

            </figure>
            <header class="entry-header">
                <h3 class="list-title" style="font-weight:bold;border:0">
                    <a href="<?php echo base_url('song/' . $song->username . '/' . $song->song_url) ?>" rel="bookmark">
                        <span class="list-artist">
                            <?php echo $songArtist; ?>
                        </span>
                       <?php echo $songTitle; ?>
                       <?php if ($songProducer): ?>
                            (Prod. by <?php echo $songProducer; ?>)
                        <?php endif ?> 
                        <span class="list-featuring">
                            <?php echo $songFeaturing; ?>
                        </span>
                    </a>
                </h3>
                <div class="entry-song-detail">
                  <span style="font-weight:bold">Artist: </span>
                    <a href="<?php echo base_url('search?q='.str_replace(' ', '+', $songArtist)); ?>" title="More songs by <?php echo $songArtist ?>"><?php echo $songArtist; ?></a> |
                     Uploaded <?php echo time_ago($song->published_date) ?> by  <a href="<?php echo base_url('u/'.$song->username); ?>" title="View <?php echo $song->username ?>'s Profile" rel="nofollow"><?php echo $song->username; ?></a> 
                     
                     <?php if ($this->ion_auth->in_group('verified', $song->user_id)): ?>
                        <div class="verified-small">
                            <span class="glyphicon glyphicon-ok" style="padding-left:2px;color:white"></span>
                        </div>
                     <?php endif ?> 
        </header>
    </article>
        <hr>

                        <?php endforeach ?>
                       <?php } else {
                        if ($this->ion_auth->logged_in() && $this->ion_auth->user()->row()->username === $username) { ?>
                         
                        <div style="text-align:center;font-size:18px;">Looks like you haven't uploaded any songs yet!<br /> <a href="<?php echo base_url('upload'); ?>" class="btn btn-danger" title="Upload Songs">Upload A Song</a>
                        </div>
                        <?php } else { ?>
                        <div style="text-align:center;font-size:18px;">This person has not uploaded any music.</div>
                          <?php } // user
                          } //else ?>

                        </ul>
                        <?php echo $pagination; ?>

                        <?php if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()): ?>
                        <div> 
                          <a href="<?php echo base_url('auth/edit_user/' . $user->id); ?>" target="blank" class="btn btn-warning">MANAGE USER</a>
                        </div>
                        <?php endif ?>
            </article>
        </div>
    </div><!--/.col-md-8.col-lg-8.col-xl-9-->