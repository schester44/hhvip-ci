<script type="text/javascript">
    $(document).ready(function () {

        $('#apm_media_wrapper').apmplayer_ui({
            playables: <?php echo $playlist; ?>,
            onPlaylistUpdate : function (playable) {

                    if ($('#apm_playlist li[ id = \'' + playable.identifier + '\']').length === 0) {   //create playlist item li + click handler if none exists.
                        $('#apm_playlist ol').append('<li id="' + playable.host + '" class="apm_playlist_item"></li>');
                        $('#apm_playlist li[ id = \'' + playable.host + '\']').click(function () {
                        $('#apm_player_container').apmplayer_ui('gotoPlaylistItem', playable.identifier);
                    });
            }
                var snippet = '';
                snippet += '<div class="apm_playlist_item_download"><a href="<?php echo base_url("download/tape-track/".$username); ?>/' + playable.identifier + '/<?php echo $tape->id . "/" . $dl_time . "/" . $dl_hash; ?>" title="Download ' + playable.title + ' by ' + playable.host + '"><span class="glyphicon glyphicon-save"></span></a></div>';
                snippet += '<div class="apm_playlist_item_info">' + playable.description + ' - ' + playable.title + '</div>';
                
                $('#apm_playlist li[ id = \'' + playable.host + '\']').html(snippet);

                },
                onMetadata : function (playable) {

                    $('#apm_song_title').html(playable.title);
                    $('#apm_song_artist').html(playable.description);

                    if(playable.identifier === playable.host) {
                        $('.apm_track_list li').removeClass('activetrack');
                    }
                    if ($('.apm_track_list #'+playable.host).length) {
                        $('.apm_track_list #'+playable.host).addClass('activetrack');
                    }
                }
        });

        $('#apm_player_backward').on('click',function(){
             window.apmplayer_ui.playlist.previous();  
        });

        $('#apm_player_forward').on('click',function(){
             window.apmplayer_ui.playlist.next();
        });

        $("#song-stats").hide();
        $("#vote-alert").hide();
        $('#song-stats-btn').click(function (e) {
            e.preventDefault();
            $("#song-stats").toggle("slow", function () {});
        });

        //gray out download button
        $('#disdown').prop('disabled', true);

        $('#votemsg').hide();
        $('#apm_player_play').on('click',function(){
            $('#votemsg').show();
        });
    });
</script>
<div class="ribbon ribbon-highlight">
    <ol class="breadcrumb ribbon-inner">
        <li><a href="<?php echo base_url(); ?>" title="">Home</a>
        </li>
        <li>
            <a href="<?php echo base_url('u/' . $username); ?>" title="<?php echo $username; ?>">
                <?php echo $username; ?>
            </a>
        </li>
        <li class="active" title="Listen and Download <?php echo $tape->tape_artist . ' - ' . $tape->tape_title; ?> Mixtape"><?php echo $tape->tape_artist . ' - ' . $tape->tape_title; ?> Mixtape Download</li>
    </ol>
</div>
<div class="section row entries topPlayerBoard">
    <div class="col-md-3 col-lg-3 col-xl-3 album-art hidden-sm hidden-xs" style="padding-right:0px">
        <img src="<?php echo $tape_image; ?>">

        <div class="mixtape-metadetails">
            <h4 class="song-subsections-heading comments-heading">MIXTAPE DETAILS</h4>
                <br />
            <strong>Artist:</strong> <a href="<?php echo base_url('search/songs/'.$tape->tape_artist); ?>" title="More Songs by <?php echo $tape->tape_artist; ?>"><?php echo $tape->tape_artist; ?></a><br/>
            <strong>Title:</strong> <?php echo $tape->tape_title; ?><br/>
            <strong>Release Date:</strong> <?php echo date('m/d/Y', $tape->published_date); ?><br />
            <strong>Uploader:</strong> <a href="<?php echo base_url('u/'.$username); ?>" title="View <?php echo $username; ?>'s Profile"><?php echo $username; ?></a><br/>
        </div>
    </div>

    <div class="col-md-9 col-lg-9 col-xl-9" style="padding-top:5px">
        <?php if($this->ion_auth->is_admin()) {
            echo $this->session->flashdata('update_status');
        } ?>


        <div class="vote-container vote-container-list pull-left" style="margin-right:5px" id="vote_container_<?php echo $tape->id;?>">
        </div>

        <h3 class="page-song-artist"><?php echo htmlspecialchars($tape->tape_artist, ENT_QUOTES); ?></h3>
        <h2 class="page-song-title bebas"><?php echo htmlspecialchars($tape->tape_title, ENT_QUOTES); ?></h2>

        <div class="player-body-main" style="padding-top:10px">
       
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


                </div>
            </div>
        </div>
        <!-- END Player Container -->

        <div id="apm_playlist_container" class="playlist_container">
            <div id="apm_playlist">
                <ol class="apm_track_list">
                </ol>
            </div>
        </div>

        </div> <!-- player-body-main -->

        <div class="download-embed">
             <a href="#" class="btn btn-sm btn-default" data-toggle="modal" data-target="#embed_modal"><span class="glyphicon glyphicon-link"></span> Embed</a>    
            <a class="btn btn-sm btn-warning" href="<?php echo base_url('download/'.$username . '/' . $tape->tape_url.'/'.$dl_time.'/'.$dl_hash) ?>" title="Download <?php echo $tape->tape_title . ' by ' . $tape->tape_artist; ?>">Download</a> 
        </div>
        
    </div>
    <!--column-->

</div>
<!-- top section-->

<div id="content" class="content section row">
    <div class="col-md-8 bg-base col-lg-8 col-xl-9">
 <!-- other mixtapes -->
        <?php if ($mixtape_subSection): ?>
            <div class="panel panel-default widget">
                <div class="panel-heading">
                    <h4 class="song-subsections-heading comments-heading">Other Tapes From <?php echo $username; ?></h4>
                </div>
                <div class="panel-body">
                    <ul class="list-group">
                        <?php foreach ($mixtape_subSection as $key=>$moreTapes): ?>

                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-xs-2 col-md-1">
                                    <a class="playlist-play" href="<?php echo base_url('mixtape/' . $moreTapes->username . '/' . $moreTapes->tape_url) ?>"></a>
                                </div>
                                <div class="col-xs-10 col-md-11" style="padding-left:25px; padding-top:7px">
                                    <div class="recent-tracks">
                                        <a href="<?php echo base_url('mixtape/' . $moreTapes->username . '/' . $moreTapes->tape_url) ?>">
                                            <?php echo htmlspecialchars($moreTapes->tape_artist .' - '. $moreTapes->tape_title, ENT_QUOTES); ?>
                                        </a>
                                    </div>
                                    <div class="mic-info">
                                        Artist: <?php echo $moreTapes->tape_artist; ?>| Uploaded on:
                                            <?php echo $moreTapes->published_date; ?>
                                            <?php echo date('m/d/Y', $moreTapes->published_date); ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endforeach ?>
                    </ul>
                </div>
            </div>
        <?php endif ?>
        <!--e.other mixtapes-->

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
                    ADD THIS MIXTAPE TO YOUR WEBSITE
                </div>

                <div class="modal-body">
                    <p><strong>Embed Code:</strong></p><p>
                    <textarea class="embed-text" onclick="this.focus();this.select()">&lt;iframe src="<?php echo base_url( 'embed/mixtape/1/'.$tape->username.'/'.$tape->tape_url) ?>" scrolling="no" width="100%" height="325px" scrollbars="no" frameborder="0"&gt;&lt;/iframe&gt;</textarea></p>
                    
                    <p><strong>Direct Link:</strong></p><p>
                    <input type="text" name="dLink" value="<?php echo base_url('mixtape/'.$tape->username.'/'.$tape->tape_url) ?>" onclick="this.focus();this.select();" style="width:100%">
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!--/.embed-->