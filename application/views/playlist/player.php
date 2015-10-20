<script type="text/javascript">
    $(document).ready(function () {
        $('#apm_media_wrapper').apmplayer_ui({
            playables: <?php echo $tracks; ?>,
            onPlaylistUpdate : function (playable) {
                    if ($('#apm_playlist li[ id = \'' + playable.identifier + '\']').length === 0) {   //create playlist item li + click handler if none exists.
                        $('#apm_playlist ol').append('<li id="' + playable.host + '" class="apm_playlist_item"></li>');
                        $('#apm_playlist li[ id = \'' + playable.host + '\']').click(function () {
                        $('#apm_player_container').apmplayer_ui('gotoPlaylistItem', playable.identifier);
                    });
            }
                var snippet = '';
                snippet += '<div class="apm_playlist_item_download"><a href="' + playable.url + '" title="View Song" target="new"><span class="glyphicon glyphicon-new-window"></span></a></div>';
                //snippet += '<div class="apm_playlist_item_order">' + playable.identifier + '</div>';
                snippet += '<div class="apm_playlist_item_info">' + playable.artist + ' - ' + playable.title + playable.program + '</div>';
                
                $('#apm_playlist li[ id = \'' + playable.host + '\']').html(snippet);
                },
                onMetadata : function (playable) {

                    var track_id_id = '<?php echo $playlist->id; ?>';
                    //change playlist image
                    $('#playlist_song_image').html('<img src="' + playable.image_lg + '">');
                    //change player title and artist;
                    $('#apm_song_title').html(playable.title);
                    $('#apm_song_artist').html(playable.artist);

                    if(playable.identifier === playable.host) {
                        $('.apm_track_list li').removeClass('activetrack');
                    }
                    if ($('.apm_track_list #'+playable.host).length) {
                        $('.apm_track_list #'+playable.host).addClass('activetrack');
                    };

                    //start a playlist message
                    $('#start-playlist-body').show();
                    var clean_artist = playable.artist.replace(/&/g,"");
                    var clean_artist = clean_artist.replace('amp;',"");
                    var artist_playlist_link = 'Now playing: ' + playable.artist + ' - ' + playable.title + ' <a class="btn btn-warning btn-sm" href="<?php echo base_url("playlist/artist"); ?>/' + clean_artist + '"><span class="glyphicon glyphicon-play"></span> Start A ' + playable.artist + ' Playlist</a>';
                    $('#start-playlist-body').html(artist_playlist_link);

                    //if its a soundcloud song, display the via SoundCloud logo
                    if (playable.http_file_path.toLowerCase().indexOf("soundcloud") >= 0) {
                        $('#viaLogo').css({'display':'inherit'});
                        $('#viaLogo').html('<a href="' + playable.external_url + '" rel="nofollow" target="new" title="via SoundCloud">via <span class="red scLogo">SoundCloud</span></a>');
                    } else {
                        $('#viaLogo').css({'display':'none'});
                    }

                }
        });

        $('#apm_player_backward').on('click',function(){
             window.apmplayer_ui.playlist.previous();  
        });

        $('#apm_player_forward').on('click',function(){
             window.apmplayer_ui.playlist.next();
        });

        if($('#apm_player_play').length) {
            var gPlay = document.getElementById('apm_player_play');
            gPlay.addEventListener('click', function(){
                ga('send', 'event', 'Playlists', 'Play', '<?php echo htmlspecialchars($playlist->title, ENT_QUOTES); ?>');
            }, false);
        }
        if($('#embed-btn').length) {
            var gdl = document.getElementById('embed-btn');
            gdl.addEventListener('click', function(){
                ga('send', 'event', 'Playlists', 'Embed', '<?php echo htmlspecialchars($playlist->title, ENT_QUOTES); ?>');
            }, false);
        }


    });
</script>
<div class="ribbon ribbon-highlight">
    <ol class="breadcrumb ribbon-inner">
        <li><a href="<?php echo base_url(); ?>" title="">Home</a>
        </li>
        <li>
            <a href="<?php echo base_url('u/' . $this->uri->segment('2')); ?>" title="<?php echo $this->uri->segment('2'); ?>">
                <?php echo $this->uri->segment('2'); ?>
            </a>
        </li>
        <li class="active" title="<?php echo $playlist->title; ?>"><?php echo $playlist->title; ?></li>
    </ol>
</div>
<div class="section row entries topPlayerBoard">
    <div class="col-md-3 col-lg-3 col-xl-3 album-art hidden-sm hidden-xs" style="padding-right:0px">
        <div class="mixtape-metadetails">
        <div id="playlist_song_image"><img src="//secure.hiphopvip.com/resources/img/300_playlist_icon.png"></div>
            <h4 class="song-subsections-heading comments-heading">PLAYLIST DETAILS</h4>
                <br />
            <strong>Title:</strong> <?php echo $playlist->title; ?><br/>
            <strong>Date Created:</strong> <?php echo date('m/d/Y', strtotime($playlist->date_created)); ?><br />
            <strong>Creator:</strong> <a href="<?php echo base_url('u/'.$this->uri->segment('2')); ?>" title="View <?php echo $this->uri->segment('2'); ?>'s Profile"><?php echo $this->uri->segment('2'); ?></a><br/>
            <strong>Total Tracks: </strong> <?php echo $track_count; ?>
        </div>
    </div>

    <div class="col-md-9 col-lg-9 col-xl-9" style="padding-top:5px">
        <?php if($this->ion_auth->is_admin()) {
            echo $this->session->flashdata('update_status');
        } ?>


        <div class="vote-container vote-container-list pull-left" style="margin-right:5px" id="vote_container_<?php echo $playlist->id;?>">
        </div>
        <h1 class="page-song-title bebas"><?php echo htmlspecialchars($playlist->title, ENT_QUOTES); ?></h1>

        <div class="player-body-main">
       
 <!-- BEGIN Player Container -->
        <div class="pull-left topSectionMsg" id="start-playlist-body" style="display:none;">Loading...</div>

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
                        <span class="glyphicon glyphicon-backward" id="apm_player_backward"></span>
                        <span class="glyphicon glyphicon-forward" id="apm_player_forward"></span>
                    </div>
                    <div id="apm_mixtape_details_container">
                        <div id="apm_song_artist">Loading Artist..</div>
                        <div id="apm_song_title">Loading Track Title..</div>
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

                        <div id="viaLogo"></div>

                </div>
            </div>
        </div>
        <div id="apm_playlist_container" class="playlist_container">
            <div id="apm_playlist">
                <ol class="apm_track_list">
                </ol>
            </div>
        </div>
        <!-- END Player Container -->

        </div> <!-- player-body-main -->
        <div class="download-embed"> 

        <?php if ($this->ion_auth->is_admin()): ?>
            <a href="<?php echo base_url('manage/delete_playlist/' . $this->uri->segment(2) . '/' . $this->uri->segment(3)) ?>" class="btn btn-sm btn-warning">DELETE PLAYLIST</a>

        <?php endif ?>
        <?php if ($this->ion_auth->logged_in() && $playlist->user_id == $this->ion_auth->user()->row()->id): ?>
            <a href="<?php echo base_url('manage/playlist/'.$playlist->id.'/edit'); ?>" class="btn btn-sm btn-default" title="Edit Playlist">Edit </a>            
        <?php endif ?>
             <a href="#" id="embed-btn" class="btn btn-sm btn-default" data-toggle="modal" data-target="#embed_modal"><span class="glyphicon glyphicon-link"></span> Embed</a>    
        </div>


    </div>
    <!--column-->

</div>
<!-- top section-->

<div id="content" class="content section row">
    <div class="col-md-8 bg-base col-lg-8 col-xl-9">

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



    <!--  EMBED MODAL -->
    <div class="modal fade" id="embed_modal" tabindex="-1" role="dialog" aria-labelledby="embed_modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    ADD THIS PLAYLIST TO YOUR WEBSITE
                </div>

                <div class="modal-body">
                    <p><strong>Embed Code:</strong></p><p>
                    <textarea class="embed-text" onclick="this.focus();this.select()">&lt;iframe src="<?php echo base_url( 'embed/playlist/1/'.$playlist->username.'/'.$playlist->url) ?>" scrolling="no" width="100%" height="325px" scrollbars="no" frameborder="0"&gt;&lt;/iframe&gt;</textarea></p>
                    
                    <p><strong>Direct Link:</strong></p><p>
                    <input type="text" name="dLink" value="<?php echo base_url('playlist/'.$playlist->username.'/'.$playlist->url) ?>" onclick="this.focus();this.select();" style="width:100%">
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!--/.embed-->