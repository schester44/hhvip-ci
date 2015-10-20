<?php 

$songArtist     = ($song && $song->song_artist) ? htmlspecialchars($song->song_artist, ENT_QUOTES) : 'Missing Song';
$songTitle      = ($song && $song->song_title) ? htmlspecialchars($song->song_title, ENT_QUOTES) : 'Missing Song';
$songDesc       = ($song && $song->song_description) ? htmlspecialchars($song->song_description, ENT_QUOTES) : '';
$songFeaturing  = ($song && $song->featuring) ? htmlspecialchars($song->featuring, ENT_QUOTES) : '';
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
    <title><?php echo $songTitle . ' by ' . $songArtist . ' ' . $this->lang->line('meta_title');; ?></title>


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
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-41619870-4', 'hiphopvip.com');
      // currently not logging a pageview 

    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            
            var external_source = '<?php echo $song->external_source ?>';

            if (external_source === 'soundcloud') {
                $('#viaSoundCloud').css({'display':'inherit'});
                $('#viaLogo').css({'top':'42px'});
            };

            $("#embed").on('click', function () {
                if (!$("#share-details:visible").length) {
                    $("#flicker").toggle('medium');
                }
                $("#embed-details").toggle('medium');
                    if ($("#share-details:visible").length) {
                        $("#share-details").hide();
                    }
            });

            $("#share").on('click', function () {
                if (!$("#embed-details:visible").length) {
                    $("#flicker").toggle('medium');
                }
                $("#share-details").toggle('medium');
                    if ($("#embed-details:visible").length) {
                        $("#embed-details").hide();
                    }
            });

            $('#apm_media_wrapper').apmplayer_ui({
                playables: [{
                    identifier: '<?php echo $songTitle; ?>',
                    type: 'audio',
                    title: '<?php echo $songTitle; ?>',
                    program: '<?php echo $songArtist; ?>',
                    http_file_path: '<?php echo $mp3Source; ?>'
                }, ],
            });
    //gA tracking

            if($('#apm_player_play').length) {
                var gPlay = document.getElementById('apm_player_play');
                gPlay.addEventListener('click', function(){
                    ga('send', 'event', 'Embed-player', 'Play', '<?php echo current_url(); ?>');
                }, false);
            }

            if($('#embed').length) {
                var gEmbed = document.getElementById('embed');
                gEmbed.addEventListener('click', function(){
                    ga('send', 'event', 'Embed-player', 'Embed', '<?php echo current_url(); ?>');
                }, false);
            }

            if($('#share').length) {
                var gShare = document.getElementById('share');
                gShare.addEventListener('click', function(){
                    ga('send', 'event', 'Embed-player', 'Share', '<?php echo current_url(); ?>');
                }, false);
            }

            if($('#download').length) {
                var gdl = document.getElementById('download');
                gdl.addEventListener('click', function(){
                    ga('send', 'event', 'Embed-player', 'Download', '<?php echo current_url(); ?>');
                }, false);
            }

        });
    </script>
    <?php if (!$song_status) { ?>
    <!-- BEGIN Player Container -->
    <div id="apm_media_wrapper" class="clearfix preroll-inactive">
        <div id="apm_player_controls" class="volume playtime">

            <div id="apm_player_toggle">
                <div id="share-details" class="shembed">

                    <div id="link-to-song">
                        Link to Song:
                        <input type="text" onclick="this.focus();this.select()" id="shemed" class="input" name="sd" value="<?php echo base_url('song/'.$song->username.'/'.$song->song_url); ?>">
                    </div>
                    <div class="social-likes" style="margin-top:-2px" data-url="<?php echo base_url('song/'.$song->username.'/'.$song->song_url) ?>">
                        <div class="facebook" title="Share link on Facebook">Facebook</div>
                        <div class="twitter" data-via="hiphopvip1" data-related="<?php echo $songDesc?>" title="Share link on Twitter">Twitter</div>
                    </div>

                </div>
                <div id="embed-details" class="shembed">
                    Copy and paste the following code to display the player on your website.
                    <textarea class="embed-text" onclick="this.focus();this.select()" id="shemed" style>&lt;iframe src="<?php echo base_url( 'embed/1/'.$song->username.'/'.$song->song_url) ?>" scrolling="no" width="100%" height="100" scrollbars="no" frameborder="0"&gt;&lt;/iframe&gt;</textarea>
                </div>
                <div id="flicker">
                    <div id="apm_player_play" class=" hide-text controls play">
                        Play
                    </div>
                    <div id="apm_player_pause" class=" hide-text controls pause">
                        Pause
                    </div>
                    <div id="apm_song_details_container">
                        <div id="apm_song_artist">
                            <?php echo $songArtist; ?>
                            <?php if (!empty($song->featuring)) { echo '(Feat. ' . $songFeaturing . ')'; } ?>
                        </div>
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
                        <div id="apm_player_bar_container" class="rounded">
                            <div id="apm_player_bar">
                                <div id="apm_player_loading" class="rounded4"></div>
                                <div id="apm_player_playtime">0:00</div>
                            </div>
                        </div>
                    </div>

                    <!-- for embed-->
                    <div id="viaLogo">
                        <span id="viaSoundCloud"><a href="<?php echo $song->external_url; ?>" rel="nofollow" target="new" title="via SoundCloud">via <span class="red">SoundCloud</span></a><br /></span> 
                        <span class="viaHHVIP" id="viaHHVIP"><a href="<?php echo base_url('song/'.$username.'/'.$song->song_url); ?>" title="HIPHOPVIP">hiphopVIP</a></span>
                    </div>
                    <!--/. for embed-->

                </div>
            </div>
        </div>

        <div id="action-btns">
            <a href="<?php echo base_url('song/'.$song->username.'/'.$song->song_url) ?>" target="new" class="btn action-btn float-btn" id="download">Download</a>
            <a href="#" class=" action-btn float-btn" id="embed">Embed</a> 
            <a href="#" class=" action-btn float-btn" id="share">Share</a> 
        </div>

    </div>
    <!-- END Player Container -->
    <?php } else { ?>
    <div class="songRemoved">
        <div class="songRemovedText">
            <?php echo $song_status; ?>
            <?php if ($song) { ?>
            <a href="<?php echo base_url() ?>" target="new" class="btn action-btn" id="download" style="width:100%">Find More Songs by <?php echo $songArtist ?></a>
            <br />
            <a href="<?php echo base_url('u/'.$user->username) ?>" target="new" class="btn action-btn" id="download" style="width:100%">View More Songs From <?php echo $user->username ?></a>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
</body>
</html>