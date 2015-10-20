 <script type="text/javascript">
    $(document).ready(function () {
        //move download button down if user logged in and on mobile
        var viewport = {
            width  : $(window).width(),
            height : $(window).height()
        };
        var uli = '<?php print_r($this->ion_auth->logged_in()); ?>';

        if (uli.length > 0 && viewport.width < 700) {
            $('#dlbtn').css({'margin-top':'20px','margin-bottom':'10px'});
        }

        $('#apm_media_wrapper').apmplayer_ui({
            playables: [{
                identifier: '<?php echo $song->song_id; ?>',
                type: 'audio',
                title: '<?php echo $songTitle; ?>',
                program: '<?php echo $songArtist; ?>',
                http_file_path: '<?php echo $mp3Source; ?>',
            }, ],
        });

        $("#vote-alert").hide();

        $(".download-song-btn").on('click',function(){
            trackEvent('<?php echo $song->song_id ?>', 'song', 'download');
        });
        
        var external_source = '<?php echo $song->external_source; ?>';

        if (external_source === 'soundcloud') {
            $('#viaLogo').css({'display':'inherit'});
        }

        if($('#apm_player_backward').length) {
            var apm_player_backward = document.getElementById('apm_player_backward');
            apm_player_backward.addEventListener('click', function(){
                ga('send', 'event', 'Main-player', 'Previous Song', '<?php echo base_url("song/".$username."/$song->song_url") ?>');
            }, false);
        }
                        
        if($('#apm_player_forward').length) {
            var apm_player_forward = document.getElementById('apm_player_forward');
            apm_player_forward.addEventListener('click', function(){
                ga('send', 'event', 'Main-player', 'Next Song', '<?php echo base_url("song/".$username."/$song->song_url") ?>');
            }, false);
        }

        if($('#apm_player_play').length) {
            var gPlay = document.getElementById('apm_player_play');
            gPlay.addEventListener('click', function(){
                ga('send', 'event', 'Main-player', 'Play', '<?php echo base_url("song/".$username."/$song->song_url") ?>');
            }, false);
        }
        if($('#apm_player_pause').length) {
            var gPause = document.getElementById('apm_player_pause');
            gPause.addEventListener('click', function(){
                ga('send', 'event', 'Main-player', 'Pause', '<?php echo base_url("song/".$username."/$song->song_url") ?>');
            }, false);
        }
        if($('#dlbtn').length) {
            var gdl = document.getElementById('dlbtn');
            gdl.addEventListener('click', function(){
                ga('send', 'event', 'Main-player', 'Download', '<?php echo base_url("song/".$username."/$song->song_url") ?>');
            }, false);
        }
    });

    $(document).ready(function() {
        var existing_playlists = '<?php if(isset($user_playlists)) { echo "existing_playlists"; }; ?>';
        if (existing_playlists.length > 0) {
            $('#create_playlist_legend').html('OR Create A New Playlist');
        } else {
            $('#create_playlist_legend').html('Create A New Playlist');
        }

        $('#save-playlist').one('click',function(e) {
            var playlist_ids = new Array();
            $("input[name=playlist_id]:checked").each(function (){
                playlist_ids.push($(this).val());
            });

            var fields = $("input[name='playlist_id']").serializeArray(); 
            
            if (fields.length === 0 && $('#playlist_name').val().length === 0) { 
               alert('No playlist selected');
            } else { 
                $.ajax({
                    url: window.location.pathname + '/playlist',
                    type: 'POST',
                    cache: false,
                    dataType: "json",
                    data: {
                        'playlist_name': $('#playlist_name').val(),
                        'playlist_id':   playlist_ids.toString(),
                        'status':       $('input[name=status]:radio:checked').val(),
                        },
                    success: function(data, status, jqXHR) {                        
                        $('#save-playlist').hide();

                        if (playlist_ids.length) {
                            $('#playlist_modal .modal-footer').append('<p>Song successfully added to your existing playlist(s)</p>');
                        }
                        else {
                            $('#playlist_modal .modal-footer').append('<p>Song successfully added to your new playlist: <a href="<?php echo base_url(); ?>' + data.url + '" class="btn btn-danger">View playlist</a></p>');
                        }
                    },

                }); //end ajax
            } //end if
                e.preventDefault();
        });
});
</script>

<div class="ribbon ribbon-highlight">
    <ol class="breadcrumb ribbon-inner">
        <li><a href="<?php echo base_url(); ?>" title="<?php echo htmlspecialchars($title, ENT_QUOTES); ?>">Home</a>
        </li>
        <li>
            <a href="<?php echo base_url('u/' . $username); ?>" title="<?php echo $username; ?>">
                <?php echo $username; ?>
            </a>
        </li>
        <li class="active" title="<?php echo $songTitle; ?>">
            <?php echo $songArtist . ' - ' . $songTitle; ?> mp3</li>
    </ol>
</div>
<div class="section row entries topPlayerBoard">
    <div class="col-md-3 col-lg-3 col-xl-3 album-art hidden-sm hidden-xs" style="padding-right:0px">
        <img src="<?php echo song_img($song->username, $song->song_url, $song->song_image, 300); ?>" class="artBorder">
    </div>

    <div class="col-md-9 col-lg-9 col-xl-9" style="padding-top:5px">
        <?php if($this->ion_auth->is_admin()) {
            echo $this->session->flashdata('update_status');
        } ?>
        <div class="vote-container vote-container-list pull-left" style="margin-right:5px" id="vote_container_<?php echo $song->song_id;?>">
            <div id="already_voted_<?php echo $song->song_id; ?>" class="already_voted">Already Voted!</div>
            <div id="text_vote_up_<?php echo $song->song_id; ?>" class="text_vote_up_<?php echo $song->song_id; ?> vote-button vote-button-top upvote">
                <a href="" onclick="vote(<?php echo $song->song_id; ?>, 10); return false;" title="<?php echo $this->lang->line('vote_dope_desc'); ?>">
                    <?php echo $this->lang->line('vote_dope'); ?></a>
            </div>
            <div id="votes_<?php echo $song->song_id; ?>" class="vote-center">
                <?php echo $this->voting->vote_sum($song->upvotes, $song->downvotes); ?>
            </div>
            <div id="already_voted_down_<?php echo $song->song_id; ?>" class="already_voted">Already Voted!</div>
            <div id="text_vote_down_<?php echo $song->song_id; ?>" class="text_vote_down_<?php echo $song->song_id; ?> vote-button vote-button-bottom downvote">
                <a href="#" onclick="vote(<?php echo $song->song_id; ?>, -10); return false;" title="<?php echo $this->lang->line('vote_nope_desc'); ?>">
                    <?php echo $this->lang->line('vote_nope'); ?></a>
            </div>
        </div>

        <h3 class="page-song-artist"><?php echo $songArtist; ?></h3>
        <h2 class="page-song-title bebas"><?php echo $songTitle; ?></h2>

    
    <div class="player-body-main" style="padding-top:10px">
        <div id="please-vote-msg"><span class="glyphicon glyphicon-arrow-up"></span> PLEASE VOTE! IS THIS SONG DOPE, OR NOPE?</div>
<?php if (!$song_status): ?>
    <?php if ($song->external_source === 'soundcloud'): ?>
      <iframe width="100%" height="126" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/<?php echo $song->external_file; ?>&amp;color=ff9900&amp;auto_play=false&amp;hide_related=true&amp;show_comments=false&amp;show_user=false&amp;show_reposts=false&amp;show_artwork=false"></iframe>
    <?php else: ?>
                <!-- BEGIN Player Container -->
        <div id="apm_media_wrapper" class="clearfix preroll-inactive">
            <div id="apm_player_controls" class="volume playtime">
                <div id="apm_player_toggle">
                    <div id="apm_player_play" class="hide-text controls play" style="left:40px;">
                        Play
                    </div>
                    <div id="apm_player_pause" class="hide-text controls pause" style="left:40px;">
                        Pause
                    </div>
                    <div class="apm_next">
                    <?php if ($prevSong): ?>
                        <a href="<?php echo base_url('song/' . $prevSong->username . '/' . $prevSong->song_url) ?>" title="Listen to <?php echo $prevSong->song_title; ?> by <?php echo $prevSong->song_artist; ?>"><span class="glyphicon glyphicon-backward" id="apm_player_backward"></span></a>
                    <?php endif ?>
                    <?php if ($nextSong): ?>
                        <a href="<?php echo base_url('song/' . $nextSong->username . '/' . $nextSong->song_url) ?>" title="Listen to <?php echo $nextSong->song_title; ?> by <?php echo $nextSong->song_artist; ?>"><span class="glyphicon glyphicon-forward" id="apm_player_forward"></span></a>                   
                    <?php endif ?>
                    </div>

                    <div id="apm_song_details_container">
                        <div id="apm_song_artist">
                            <?php echo $songArtist ?></div>
                        <div id="apm_song_title">
                            <?php echo $songTitle; ?>
                        </div>
                    </div>


                    <div id="apm_player_volume_wrapper">
                        <div id="apm_player_volume_status">
                        </div>
                        <div id="apm_player_volume_slider_wrapper">
                            <div id="apm_player_volume_slider_container" class="rounded">
                                <div id="apm_volume_bar">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div id="apm_player_bar_wrapper">
                        <div id="apm_player_bar_container">
                            <div id="apm_player_bar">
                                <div id="apm_player_loading"></div>
                                <div id="apm_player_playtime">0:00</div>
                            </div>
                        </div>
                    </div>

                        <div id="viaLogo"><a href="<?php echo $song->external_url; ?>" rel="nofollow" target="new" title="via SoundCloud">via <span class="red scLogo">SoundCloud</span></a></div>

                </div>
            </div>
        </div>
        <!-- END Player Container -->
    <?php endif ?>

        </div> <!-- player-body-main -->

        <div class="song-action-btns">
            <div class="social-likes" data-url="<?php echo base_url('song/'.$username.'/'.$song->song_url); ?>">
                <div class="facebook" title="Share link on Facebook">Facebook</div>
                <div class="twitter" <?php echo $twitter_via; ?> data-related="<?php echo $description; ?>" title="Share link on Twitter">Twitter</div>
            </div>

            <div class="download-embed">
            <?php
            if ($this->ion_auth->logged_in()) {
             if ($this->uri->segment('2') == $this->ion_auth->user()->row()->username) { ?>
                <a class="btn btn-default" title="Edit Song Details" href="<?php echo base_url('manage/song/'.$song->song_id.'/edit') ?>"><span class="glyphicon glyphicon-wrench"></span> Manage</a>
                
                <?php } 
            } // end edit button 4 user
            ?>            
            
             <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#embed_modal"><span class="glyphicon glyphicon-link"></span> Share</a>    

            <?php if ($this->ion_auth->logged_in()): ?>

                <?php $favorite_button = ($favorite) ? 'Un-Favorite' : 'Favorite'; ?>
            <button class="btn btn-primary add-to-playlist" id="playlist" title="Add Song to Playlist"  data-toggle="modal" data-target="#playlist_modal"><span class="glyphicon glyphicon-plus"></span> Playlist</button>
            <button class="btn btn-primary" id="favorite" title="Add Song to Favorites"><span class="glyphicon glyphicon-star"></span> <?php echo $favorite_button; ?></button>    
            <?php endif ?>


                <?php if($song->can_download == 'no') { if ($song->buy_link) { ?>
                <a class="btn btn-warning" title="Buy <?php echo $songArtist . ' - ' . $songTitle; ?>" href="<?php echo $song->buy_link; ?>" target="new">Buy Song</a>
                <? } else { ?>


                <button class="btn btn-default strike disdown" id="dlbtn"><span class="glyphicon glyphicon-save"></span> Download</button>
                
                
                <?php } //disabled d/ button
                
                } else { ?>
                
                <?php if (!empty($song->external_source) && $song->can_download === 'yes'): ?>
                <a href="https://api.soundcloud.com/tracks/<?php echo $song->external_file; ?>/download?client_id=<?php echo $this->config->item('soundcloud_client_id'); ?>" class="btn btn-warning download-song-btn" target="blank" title="Download <?php echo $songArtist . ' - ' . $songTitle; ?> mp3" id="dlbtn"><span class="glyphicon glyphicon-save"></span> Download</a>
                <?php endif ?>

                <?php if (empty($song->external_source)): ?>
                <a href="<?php echo getSignedURL($this->config->item('cloudfront_music') . '/tracks/' . $song->username . '/' . $song->file_name, '600');?>" class="btn btn-danger download-song-btn" target="blank" title="Download <?php echo $songArtist . ' - ' . $songTitle; ?> mp3" id="dlbtn"><span class="glyphicon glyphicon-save"></span> Download</a>    
                <?php endif ?>
                
                <?php } ?>
            </div>


        </div>
        
<?php $this->load->view('modules/ads/messageBar.php'); ?>

<?php else: ?>
        <div class="song-status">
        	<?php echo $this->session->flashdata('file_deleted'); ?><br />
            <?php if ($this->session->flashdata('song-status')) { echo $this->session->flashdata('song-status'); } else { echo $song_status; } ?>
        </div>
    </div>
<?php endif ?>

    </div><!--column-->

</div><!-- top section-->

<div id="content" class="content section row">
    <div class="col-md-8 bg-base col-lg-8 col-xl-9">
    <?php if ($song->video): ?>
         <div class="row">
            <iframe width="100%" height="390" src="//www.youtube.com/embed/<?php echo $song->video; ?>" frameborder="0" allowfullscreen></iframe>
        </div>   
    <?php endif ?>

        <div class="after-entry">
            <div class="song-description">
                <h4 class="song-subsections-heading comments-heading">TRACK DETAILS</h4><br />
                <?php echo $featuring; ?>
                <?php echo $producer; ?>
                <?php echo $album; ?>
                <span style="display:inline-block"><span style="font-weight:bold">Uploader: </span> <a href="<?php echo base_url('u/'.$username); ?>" title="View <?php echo $username ?>'s Profile"><?php echo $username; ?></a></span>                      <?php if ($this->ion_auth->in_group('verified', $song->user_id)): ?>
                        <div class="verified-small">
                            <span class="glyphicon glyphicon-ok" style="padding-left:2px;color:white"></span>
                        </div>
                     <?php endif ?> 
                <span style="display:block"><span style="font-weight:bold">Released: </span> <?php echo $releaseDate; ?></span>
                <?php echo $visibility; ?>
            </div>
            <?php echo $description; ?>
        </div>

        <div class="after-entry">           
            <hr>
        <?php if ($more_tracks): ?>
            <div class="panel panel-default widget">
                <div class="panel-heading">
                    <h4 class="song-subsections-heading comments-heading">
                    <?php echo $more_tracks_title; ?></h4>
                </div>

                <?php if ($start_a_playlist): ?>
                    <a href="<?php echo base_url('playlist/artist/'.$song->song_artist); ?>" class="btn btn-primary" style="margin-top:5px;margin-bottom:5px;width:100%;text-align:center" title="Start A <?php echo $song->song_title; ?> Playlist">Start a <span style="color:#fa8900"><?php echo $song->song_artist; ?></span> Playlist <span style="color:black;background:#fa8900;padding-top:2px;padding-bottom:2px;padding-left:5px;padding-right:5px;margin-left:10px"> <span class="glyphicon glyphicon-headphones"></span> Play Now</span></a>
                <?php endif ?>

                <div class="panel-body">
    <?php $this->load->view('modules/promoted_songs'); ?>
            <div class="content section row">
            <?php $this->load->view('modules/searchBar'); ?>
        </div>      
     <?php foreach ($more_tracks as $key=>$mt): ?>
        <?php 
            $mtArtist = htmlspecialchars($mt->song_artist, ENT_QUOTES);
            $mtTitle = htmlspecialchars($mt->song_title, ENT_QUOTES);
            $mtDesc  = htmlspecialchars($mt->song_description, ENT_QUOTES);
            $mtFeaturing = htmlspecialchars($mt->featuring, ENT_QUOTES);
            $mtProducer = htmlspecialchars($mt->song_producer, ENT_QUOTES);

            $featuring       = (!empty($mt->featuring) ? ' Feat. ' . $mtFeaturing : NULL);
            $stream_download = ($mt->can_download == 'yes' ? 'Stream/Download' : 'Stream Only');
        ?>
        <article class="entry style-media media type-post ">
            <div class="vote-container vote-container-list pull-left" id="vote_container_<?php echo $mt->song_id;?>">
                <div id="already_voted_<?php echo $mt->song_id; ?>" class="already_voted">Already Voted!</div>
                <div id="text_vote_up_<?php echo $mt->song_id; ?>" class="text_vote_up_<?php echo $mt->song_id; ?> vote-button vote-button-top upvote vote_up_<?php echo $mt->song_id; ?>">
                    <a href="" onclick="vote(<?php echo $mt->song_id; ?>, 10); return false;" title="<?php echo $this->lang->line('vote_dope_desc'); ?>"><?php echo $this->lang->line('vote_dope'); ?></a>
                </div>
                <div id="already_voted_down_<?php echo $mt->song_id; ?>" class="already_voted">Already Voted!</div>
                <div id="text_vote_down_<?php echo $mt->song_id; ?>" class="text_vote_down_<?php echo $mt->song_id; ?> vote-button vote-button-bottom downvote vote_down_<?php echo $mt->song_id; ?>">
                    <a href="#" onclick="vote(<?php echo $mt->song_id; ?>, -10); return false;" title="<?php echo $this->lang->line('vote_nope_desc'); ?>"><?php echo $this->lang->line('vote_nope'); ?></a>
                </div>
            </div>

            <figure class="pull-left list-art">
                <a href="<?php echo base_url('song/' . $mt->username . '/' . $mt->song_url) ?>" rel="bookmark">
                <img src="<?php echo song_img($mt->username, $mt->song_url, $mt->song_image, 150); ?>" data-src="<?php echo song_img($mt->username, $mt->song_url, $mt->song_image, 150); ?>" alt="Listen to <?php echo $mtTitle . ' by ' . $mtArtist; ?>"/>
                    <noscript>
                        <img src="<?php echo song_img($mt->username, $mt->song_url, $mt->song_image, 150); ?>"  alt="Listen to <?php echo $mtTitle . ' by ' . $mtArtist; ?>"/>
                    </noscript>
                </a>

            </figure>
            <header class="entry-header">
                <h3 class="list-title">
                <div id="votes_<?php echo $mt->song_id; ?>" class="style-review-score list-review-score" style="background-color:<?php echo $this->voting->hotness_color($mt->upvotes, $mt->downvotes); ?>">
                    <?php echo $this->voting->vote_sum($mt->upvotes, $mt->downvotes); ?>
                </div>
                    <a href="<?php echo base_url('song/' . $mt->username . '/' . $mt->song_url) ?>" rel="bookmark">
                        <span class="list-artist">
                            <?php echo $mtArtist; ?>
                        </span>
                       <?php echo $mtTitle; ?>
                       <?php if ($mtProducer): ?>
                            (Prod. by <?php echo $mtProducer; ?>)
                        <?php endif ?> 
                        <span class="list-featuring">
                            <?php echo $featuring; ?>
                        </span>
                    </a>
                </h3>
                <div class="entry-song-detail">
                  <span style="font-weight:bold">Artist: </span>
                    <a href="<?php echo base_url('search?q='.str_replace(' ', '+', $mtArtist)); ?>" title="More songs by <?php echo $mtArtist ?>"><?php echo $mtArtist; ?></a> |
                     Uploaded <?php echo time_ago($mt->published_date) ?> by  <a href="<?php echo base_url('u/'.$mt->username); ?>" title="View <?php echo $mt->username ?>'s Profile" rel="nofollow"><?php echo $mt->username; ?></a> 
                     
                     <?php if ($this->ion_auth->in_group('verified', $mt->user_id)): ?>
                        <div class="verified-small">
                            <span class="glyphicon glyphicon-ok" style="padding-left:2px;color:white"></span>
                        </div>
                     <?php endif ?> 
            <?php if ($this->ion_auth->is_admin()): ?>
            <div class="entry-song-detail">

                <?php if ($mt->featured != 'yes'): ?>
                    <a href="<?php echo base_url('manage/song/feature/' . $mt->song_id . '/feature') ?>" class="" style="font-weight:bold;color:green">Feature</a> | 
                <?php else: ?>
                    <a href="<?php echo base_url('manage/song/feature/' . $mt->song_id . '/unfeature'); ?>" class="" style="font-weight:bold;color:green">Un-Feature</a> | 
                <?php endif ?>


                <?php if ($mt->promoted != 'yes'): ?>
                    <a href="<?php echo base_url('manage/song/promote/' . $mt->song_id . '/promote'); ?>" class="" style="font-weight:bold;color:green">Sponsor</a> | 
                <?php else: ?>
                    <a href="<?php echo base_url('manage/song/promote/' . $mt->song_id . '/unpromote'); ?>" class="" style="font-weight:bold;color:green">Un-Sponsor</a> | 
                <?php endif ?>
                    <a href="<?php echo base_url('manage/song/remove/' . $mt->song_id . '/copyright'); ?>" style="font-weight:bold;color:red">Copyright</a>| 
                    <a href="#" id="<?php echo $mt->song_id; ?>-boost" class="boostVotes" title="Boost Votes" style="font-weight:bold;color:orange">Boost</a>
            </div>
            <?php endif ?>
        </header>
    </article>
        <hr>

        <?php endforeach ?>
        </div>
    </div>
<?php endif ?>

            <!--e.recent tracks-->
            <hr>
        </div>
        <!--comments -->
        <div class="panel panel-default widget">
            <div class="panel-heading">
                <h4 class="song-subsections-heading comments-heading">
                    Latest Comments</h4>
            </div>

            <div id="disqus_thread"></div>
            <script type="text/javascript">
                /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
                var disqus_shortname = 'hhvip2'; // required: replace example with your forum shortname

                /* * * DON'T EDIT BELOW THIS LINE * * */
                (function () {
                    var dsq = document.createElement('script');
                    dsq.type = 'text/javascript';
                    dsq.async = true;
                    dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                })();
            </script>
            <noscript>Please enable JavaScript to view the comments.</noscript>
        </div>



    </div> <!--/.col-md-8.col-lg-8.col-xl-9-->

<?php if ($this->ion_auth->logged_in()): ?>
    <!--  PLAYLIST MODAL -->
    <div class="modal fade" id="playlist_modal" tabindex="-1" role="dialog" aria-labelledby="playlist_modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form role="form" id="playlist-form"> <!-- form start-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <div id="embed_modalLabel">Add To Playlist</div>
                </div>
                   <div class="modal-body">
                    <fieldset>
            <?php if(!empty($user_playlists)): ?>
                        <fieldset>
                            <legend>Add to Existing Playlist</legend>

                        <div class="form-group" style="border-bottom:1px solid #CCC;width:auto;max-height:150px;overflow:auto">
                            <?php foreach ($user_playlists as $key => $ep): ?>
                                <div class="checkbox" id="p_<?php echo $ep->id; ?>">
                                    <label>
                                        <input type="checkbox" name="playlist_id" value="<?php echo $ep->id; ?>">
                                            <?php echo htmlspecialchars($ep->title, ENT_QUOTES); ?> | <strong>Status: </strong><?php echo $ep->status; ?> | <strong>View: </strong> <a href="<?php echo base_url('playlist/'.$this->ion_auth->user()->row()->username.'/'.$ep->url); ?>" title="View This Playlist">Link</a>
                                </div>
                            <?php endforeach ?>
                        </div>
                        </fieldset>
            <?php endif; ?>
                        <div class="form-group">
                            <legend id="create_playlist_legend">OR Create A New Playlist</legend>
                            <label for="playlist_name">New Playlist Name</label>
                            <input id="playlist_name" name="playlist_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="status" id="status" value="public" checked>
                                    Public playlist - Visible to the world
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="status" id="status" value="private">
                                    Private playlist - Visible to only you
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="status" id="status" value="unlisted">
                                    Unlisted playlist - you and whoever you share with can see it
                                </label>
                            </div>
                        </div>
                    </fieldset>
            </div><!-- ./modal body-->
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="save-playlist">Add to playlist</button>
            </div>
            </form><!-- ./playlist form -->
        </div>
    </div>
</div>
    <!--/.embed-->
<?php endif ?>


    <!--  EMBED MODAL -->
    <div class="modal fade" id="embed_modal" tabindex="-1" role="dialog" aria-labelledby="embed_modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <div id="embed_modalLabel">ADD THIS SONG TO YOUR WEBSITE</div>
                </div>

                <div class="modal-body">
                    <iframe src="<?php echo base_url('embed/1/'.$song->username.'/'.$song->song_url) ?>" scrolling="no" width="100%" height="100" scrollbars="no" frameborder="0"></iframe>
                    <p><strong>Embed Code:</strong></p><p>
                    <textarea class="embed-text" onclick="this.focus();this.select()">&lt;iframe src="<?php echo base_url( 'embed/1/'.$song->username.'/'.$song->song_url) ?>" scrolling="no" width="100%" height="100" scrollbars="no" frameborder="0"&gt;&lt;/iframe&gt;</textarea></p>
                    
                    <p><strong>Direct Link:</strong></p><p>
                    <input type="text" name="dLink" value="<?php echo base_url('song/'.$song->username.'/'.$song->song_url) ?>" onclick="this.focus();this.select();" style="width:100%">
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!--/.embed-->

    <script type="text/javascript">
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        var disqus_shortname = 'hhvip2'; // required: replace example with your forum shortname

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function () {
            var s = document.createElement('script');
            s.async = true;
            s.type = 'text/javascript';
            s.src = '//' + disqus_shortname + '.disqus.com/count.js';
            (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
        }());
    </script>