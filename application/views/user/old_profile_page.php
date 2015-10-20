<script type="text/javascript">
$(document).ready(function(){
    $('#apm_media_wrapper').hide();
    $('#apm_media_wrapper').apmplayer_ui({
        playables : <?php echo $playlist; ?>,
        onPlaylistUpdate : function (playable) {
               if ($('#apm_playlist li[ id = \'' + playable.identifier + '\']').length === 0) {   //create playlist item li + click handler if none exists.
                $('#apm_playlist ul').append('<li id="' + playable.identifier + '" class="apm_playlist_item"></li>');

                $('#apm_playlist li[ id = \'' + playable.identifier + '\']').click(function () {
                    $('#apm_player_container').apmplayer_ui('gotoPlaylistItem', this.id);
                    $('#apm_song_title').empty().append(playable.identifier);
                    $('#apm_song_artist').empty().append(playable.program);
                    $('#apm_media_wrapper').show();
                });

            }

            var snippet = '';
            var d = new Date(playable.date * 1000);
            snippet += '<div class="row" style="margin-top:-15px">';
            snippet += '<div class="col-sm-2 pull-left"><img src="' + playable.image_sm + '" width="64px" height="64px"></div>';            
            
            snippet += '<div class="col-sm-10 pull-right">';
            if (playable.program !== '') {
                snippet += '<div class="apm_playlist_item_title" style="font-size:26px;">'+ playable.program + '</div>';
            }
            snippet += '<div class="playList_item_btns pull-right"><a href="<?php echo base_url("song/".$this->uri->segment(2)); ?>/' + playable.detail + '" class="btn btn-default btn-xs">Download</a></div>'; 
            
            if (playable.title !== '') {
                 snippet += '<div class="apm_playlist_item_info" style="font-size:16px">' + playable.title + '</div>';
            } else if (playable.description !== '') {
                 snippet += '<div class="apm_playlist_item_info">' + playable.description + '</div>';
            }
            snippet += '<div class="apm_playlist_item_info" style="margin-top: -8px;">Released: ' + d.getMonth() + '/' + d.getDate() + '/' + d.getFullYear() + '</div>';
            snippet += '</div>'; //end col
            snippet += '</div>';
            snippet += '<hr style="margin:5px;">';

            $('#apm_playlist li[ id = \'' + playable.identifier + '\']').html(snippet);
        }
    });
});
</script>

<div class="ribbon ribbon-highlight">
    <ol class="breadcrumb ribbon-inner">
        <li><a href="<?php echo base_url(); ?>">Home</a>
        </li>
        <li>
            <?php echo $username; ?>'s PROFILE</li>
    </ol>
</div>

<div class="section row entries" style="margin-top:-30px">
    <div class="col-md-12 col-lg-12 col-xl-12">
        <?php if($this->ion_auth->is_admin()) { ?>
        <h1 style="color:red;text-align:center"><?php echo $this->session->flashdata('update_status'); ?></h1>
        <?php } ?>
        <!-- BEGIN Player Container -->
        <div id="apm_media_wrapper" class="clearfix preroll-inactive">
            <div id="apm_player_controls" class="volume playtime">
                <div id="apm_player_toggle">

                    <div id="apm_player_play" class="hide-text controls play">
                        Play
                    </div>
                    <div id="apm_player_pause" class="hide-text controls pause">
                        Pause
                    </div>
                    <div id="apm_song_details_container">
                        <div id="apm_song_artist" data-content="song artist text" rel="popover" data-placement="top" data-trigger="hover">
                        </div>

                        <div id="apm_song_title">
                        </div>

                        <div id="apm_song_info">
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

                </div>
            </div>
        </div>
        <!-- END Player Container -->

    </div>
</div>

<div id="content" class="content section row">
    <div class="col-md-8 bg-base col-lg-8 col-xl-9">
        <div class="col-md-3 col-lg-3 col-xl-3 hidden-xs hidden-sm">
            <div class="profile">
                <img src="<?php echo user_img($user->username); ?>" class="img-circle">
            <?php if ($this->ion_auth->logged_in()) {
                    if ($this->ion_auth->user()->row()->id == $user->id) { ?>
                <a href="<?php echo base_url('auth/edit_account'); ?>" class="btn btn-warning btn-xs" style="margin-top:-50px;margin-left:21px;">Choose new image</a>
            <?php }
            } ?>
                <p style="font-size:24px"><?php echo $user->username; ?></p>
            </div>
                    <ul class="list-group">
                        <!--<li class="list-group-item"><a href="">FOLLOW</a></li>-->
                      <!--  <li class="list-group-item"><a href="">SHARE</a></li>-->
                      <?php if (!empty($user->location)): ?>
                      <div id="locationSidebar" class="row"> <span class="glyphicon glyphicon-home"></span>  <?php echo $user->location; ?></div>  
                      <?php endif ?>
                      <?php if (!empty($user->twitter_handle)): ?>     
                      <div id="twtrSidebar" class="row"> <span class="glyphicon glyphicon-thumbs-up"></span> <a href="http://twitter.com/<?php echo $user->twitter_handle ?>" title="<?php echo $user->username ?> on Twitter" rel="nofollow">@<?php echo $user->twitter_handle; ?></a></div>
                      <?php endif ?>
                      <?php if (!empty($user->website)): ?>
                      <div id="siteSidebar" class="row"> <span class="glyphicon glyphicon-cloud"></span> <?php echo $user->website; ?></div>    
                      <?php endif ?>
                      <?php if (!empty($user->bio)): ?>
                        <div id="bioSidebar" class="row"> <span class="glyphicon glyphicon-user"></span> <?php echo $user->bio; ?></div>                          
                      <?php endif ?>

                    </ul>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
            <article class="entry style-single type-post">
                <div id="apm_playlist_container" class="rounded box clearfix">
                    <h2 class="bebas"><?php echo strtoupper($username); ?>'S STREAM</h2>
                    <div id="apm_playlist">
                        <ul>
                        </ul>
                    </div>
                </div>
            </article>
        </div>
    </div><!--/.col-md-8.col-lg-8.col-xl-9-->