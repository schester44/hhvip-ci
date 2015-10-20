<div class="ribbon ribbon-highlight">
    <ol class="breadcrumb ribbon-inner">
        <li><a href="<?php echo base_url(); ?>" title="">Home</a>
        </li>
        <li><a href="<?php echo base_url('playlists'); ?>" title="Playlists">Playlists</a></li>
        <li class="active" title="No Artist Found">No Artist Found</li>
    </ol>
</div>
<div class="section row entries topPlayerBoard">
    <div class="col-md-3 col-lg-3 col-xl-3 album-art hidden-sm hidden-xs" style="padding-right:0px">
        <div class="mixtape-metadetails">
        <div id="playlist_song_image"><img src="//secure.hiphopvip.com/resources/img/300_playlist_icon.png"></div>
        </div>
    </div>

    <div class="col-md-9 col-lg-9 col-xl-9" style="padding-top:5px">
        <?php if($this->ion_auth->is_admin()) {
            echo $this->session->flashdata('update_status');
        } ?>


        <div class="vote-container vote-container-list pull-left" style="margin-right:5px">
        </div>
        <h1 class="page-song-title bebas">No Artist Found</h1>

        <div class="player-body-main" style="padding-top:10px">

        <h3 style="color:red;text-align:center">The artist you're searching for could not be found.<br />Please revise your search and try again.</h3>
        </div> <!-- player-body-main -->


    </div>
    <!--column-->

</div>
<!-- top section-->
