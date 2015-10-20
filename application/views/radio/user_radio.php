            			<div id="content" class="content section row">

				<div class="col-md-8 bg-base col-lg-8 col-xl-9">

					<div class="ribbon ribbon-highlight">
						<ol class="breadcrumb ribbon-inner">
							<li><a href="<?php echo base_url(); ?>">Home</a></li>
							<li><a href="<?php echo base_url('u/'.$user->username) ?>"><?php echo $user->username ?></a></li>
						</ol>
					</div>
					
					<header class="page-header">
					<h2 class="page-title">
							<?php echo $user->username; ?>'s Radio
						</h2>
					</header>
					<article class="entry style-single type-post">

						<div class="entry-content">


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

                        <div id="apm_song_details_container" data-content="daata contenttt" rel="popover" data-placement="top" data-trigger="hover">
                            <div id="apm_song_artist">ARTIST</div>
                            <div id="apm_song_title">TITLE</div>
                            <div id="apm_song_info">                            
                                SONG INFO

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

					</article>
					

				</div><!--/.col-md-8.col-lg-8.col-xl-9-->