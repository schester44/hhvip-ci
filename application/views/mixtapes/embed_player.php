<?php 

$tapeArtist     = ($tape) ? htmlspecialchars($tape->tape_artist, ENT_QUOTES) : 'Missing Mixtape';
$tapeTitle      = ($tape) ? htmlspecialchars($tape->tape_title, ENT_QUOTES) : 'Missing Mixtape';
$tapeDesc       = ($tape) ? htmlspecialchars($tape->tape_description, ENT_QUOTES) : '';
 ?>


<!DOCTYPE html>
<!--[if lt IE 7]>   <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>      <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>      <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <!-- Mobile Devices Viewport Resset-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title><?php echo $tapeTitle . ' by ' . $tapeArtist . ' ' . $this->lang->line('meta_title'); ?></title>

        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">

    <?php
    if (isset($meta_name)) {
        foreach ($meta_name as $m => $val) { ?>
        <meta name="<?php echo $m ?>" content="<?php echo $val ?>">
    <?php } } 
    if (isset($meta_prop)) {
        foreach ($meta_prop as $mp => $prop_val) { ?>
        <meta property="<?php echo $mp ?>" content="<?php echo $prop_val ?>">
    <?php } } ?> 


    <script src="<?php echo base_url(VENDOR."jquery-1.11.0.min.js") ?>"></script>

    <?php if (isset($vendorCSS)) { foreach ($vendorCSS as $c) { ?>
    <link rel="stylesheet" href="<?php echo base_url(VENDOR ."$c") ?>">
    <? } } ?>

    <?php if (isset($vendorJS)) { foreach ($vendorJS as $j) { ?>
    <script src="<?php echo base_url(VENDOR ."$j") ?>"></script>
    <? } } ?>
</head>

<body>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#embed").on('click', function () {
                if (!$("#share-details:visible").length) {
                    $("#flicker").toggle('medium');
                }
                $("#mixtape-embed-details").toggle('medium');

                if ($("#mixtape-share-details:visible").length) {
                    $("#mixtape-share-details").hide();
                }

            });

            $("#share").on('click', function () {
                if (!$("#mixtape-embed-details:visible").length) {
                    $("#flicker").toggle('medium');
                }
                $("#mixtape-share-details").toggle('medium');

                if ($("#mixtape-embed-details:visible").length) {
                    $("#mixtape-embed-details").hide();
                }
            });

            
        $('#apm_player_backward').on('click',function(){
             window.apmplayer_ui.playlist.previous();  
        });

        $('#apm_player_forward').on('click',function(){
             window.apmplayer_ui.playlist.next();
        });

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
                snippet += '<div class="apm_playlist_item_download"><a href="<?php echo base_url("dlmts/".$username); ?>/' + playable.image_sm + '" title="Download ' + playable.title + ' by ' + playable.host + '"><span class="glyphicon glyphicon-save"></span></a></div>';
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

        });
    </script>
    <?php if (!$tape_status) { ?>
      
 <!-- BEGIN Player Container -->
        <div id="apm_media_wrapper" class="clearfix preroll-inactive">
            <div id="apm_player_controls" class="volume playtime">

        <div id="mixtape-share-details" class="shembed">Click to share:<br />
            <div class="social-likes" style="margin-top:-2px" data-url="<?php echo base_url('mixtape/'.$user->username.'/'.$tape->tape_url) ?>">
                <div class="facebook" title="Share link on Facebook">Facebook</div>
                <div class="twitter" data-via="hiphopvip1" data-related="<?php echo $tape->tape_title . ' mixtape by ' . $tape->tape_artist; ?>" title="Share link on Twitter">Twitter</div>
            </div>

        </div>
        <div id="mixtape-embed-details" class="shembed">
            Copy and paste the following code to display the player on your website.
            <textarea class="embed-text" onclick="this.focus();this.select()" id="shemed" style>&lt;iframe src="<?php echo base_url( 'embed/mixtape/1/'.$user->username.'/'.$tape->tape_url) ?>" scrolling="no" width="100%" height="325px" scrollbars="no" frameborder="0"&gt;&lt;/iframe&gt;</textarea>
        </div>

              <div id="flicker">
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
                        <div id="apm_song_artist">
                            artist</div>
                        <div id="apm_song_title">
                           title
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


                </div>
            </div>
            </div>
        </div>
        <div id="apm_playlist_container" class="playlist_container">
            <div id="apm_playlist">
                <ol class="apm_track_list">
                </ol>
            </div>
        </div>


        <div class="playlist_share_container"> 
            <div id="action-btns">
                <a href="#" id="embed">Embed</a> / 
                <a href="#" id="share">Share</a> / 
                <a href="<?php echo base_url('mixtape/'.$user->username.'/'.$tape->tape_url) ?>" target="new" id="tape_download">Download Mixtape</a>
            </div>
        </div>
    <!-- END Player Container -->

    <?php } else { ?>
    <div class="songRemoved">
        <div class="songRemovedText">
            <?php echo $tape_status; ?>
            <?php if ($tape) { ?>
            <a href="<?php echo base_url() ?>" target="new" class="btn action-btn" id="download" style="width:100%">Find More Mixtapes by <?php echo $tapeArtist ?></a>
            <br />
            <a href="<?php echo base_url('u/'.$user->username) ?>" target="new" class="btn action-btn" id="download" style="width:100%">View More Songs From <?php echo $user->username ?></a>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
</body>
</html>