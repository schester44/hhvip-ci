 <?php if ($prevSong): ?>
                <article class="entry style-grid style-hero hero-nav type-post col-xs-6 col-sm-6" style="overflow:hidden">
                    
                    <a href="<?php echo base_url('song/' . $prevSong->username . '/' . $prevSong->song_url) ?>" title="Next Song by <?php echo $prevSong->song_artist ?>">
                            
                            <p class="small" style="margin-bottom:-5px;margin-top: 5px;">Previous Song</p>
                        
                       <p class="bebas" style="font-size:24px; color:white">  <?php echo $prevSong->song_artist;
                            if ($prevSong->featuring) {
                                echo ' Feat. '. $prevSong->featuring;
                            }
                             ?></p>
                        <h2 class="page-song-title">
                            <?php echo $prevSong->song_title; ?>
                </h2>   

                        <figure class="entry-thumbnail">

                            <div class="overlay overlay-primary"></div>

                            <!-- to disable lazy loading, remove data-src and data-src-retina -->
                            <img src="<?php echo song_img($prevSong->username, $prevSong->song_url, $prevSong->song_image, 300); ?>" data-src="<?php echo song_img($prevSong->username, $prevSong->song_url, $prevSong->song_image, 300); ?>" data-src-retina="<?php echo song_img($prevSong->username, $prevSong->song_url, $prevSong->song_image, 300); ?>" width="480" height="280" alt="">

                            <!--fallback for no javascript browsers-->
                            <noscript>
                                <img src="../uploads/480x280_3.jpg" alt="">
                            </noscript>

                        </figure>

                    </a> 
                </article>
                <?php endif ?>


            <?php if ($nextSong): ?>

                <article class="entry style-grid style-hero hero-nav type-post col-xs-6 col-sm-6" style="overflow:hidden">
                    
                    <a href="<?php echo base_url('song/' . $nextSong->username . '/' . $nextSong->song_url) ?>" title="Next Song by <?php echo $nextSong->song_artist ?>">
                            
                            <p class="small" style="margin-bottom:-5px;margin-top: 5px;">Next Song</p>
                        
                       <p class="bebas" style="font-size:24px; color:white">  <?php echo $nextSong->song_artist;
                            if ($nextSong->featuring) {
                                echo ' Feat. '. $nextSong->featuring;
                            }
                             ?></p>
                        <h2 class="page-song-title">
                            <?php echo $nextSong->song_title; ?>
                </h2>   

                        <figure class="entry-thumbnail">

                            <div class="overlay overlay-primary"></div>

                            <!-- to disable lazy loading, remove data-src and data-src-retina -->
                            <img src="<?php echo song_img($nextSong->username, $nextSong->song_url, $nextSong->song_image, '300') ?>" data-src="<?php echo song_img($nextSong->username, $nextSong->song_url, $nextSong->song_image, '300') ?>" data-src-retina="<?php echo song_img($nextSong->username, $nextSong->song_url, $nextSong->song_image, '300') ?>" width="480" height="280" alt="">

                            <!--fallback for no javascript browsers-->
                            <noscript>
                                <img src="../uploads/480x280_3.jpg" alt="">
                            </noscript>

                        </figure>

                    </a>
                </article>
                <?php endif ?> 