<div id="content" class="content section row">
    <div class="col-md-8 bg-base col-lg-8 col-xl-9">
      <?php $this->load->view('user/header'); ?>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <article class="entry style-single type-post">
                        <ul style="list-style-type: none;margin-left:-40px;padding-top:15px">
                        <?php if ($songs): ?>
                        <?php foreach ($songs as $key => $song): ?>  

                        <?php 
                    $songArtist = htmlspecialchars($song->song_artist, ENT_QUOTES);
                    $songTitle   = htmlspecialchars($song->song_title, ENT_QUOTES);
                    $songDesc    = htmlspecialchars($song->song_description, ENT_QUOTES);
                    $songFeaturing = htmlspecialchars($song->featuring, ENT_QUOTES);

                     ?>   
        <article class="entry style-media media type-post listEntry">

                         <figure class="media-object pull-left list-image" style="height:65px">
                <a href="<?php echo base_url('song/' . $username . '/' . $song->song_url) ?>" rel="bookmark">
                <div id="votes_<?php echo $song->song_id; ?>" class="style-review-score">
                    <?php echo $this->voting->vote_sum($song->upvotes, $song->downvotes); ?>
                </div>

                    <img src="<?php echo song_img($song->username, $song->song_url, $song->song_image, 64); ?>" data-src="<?php echo song_img($song->username, $song->song_url, $song->song_image, 64); ?>" width="54px" height="54px" />
                    <noscript>
                        <img src="<?php echo song_img($song->username, $song->song_url, $song->song_image, 64); ?>" width="54px" height="54px" />
                    </noscript>
                </a>

            </figure>
                  <div class="profileSongArtist blck"><a href="<?php echo base_url('song/'.$username.'/'.$song->song_url) ?>" title="Listen to <?php echo $songArtist; ?> - <?php echo $songTitle; ?>"><?php echo $songArtist; ?></a></div>
                  <div class="profileSongTitle blck"><a href="<?php echo base_url('song/'.$username.'/'.$song->song_url) ?>" title="Listen to <?php echo $songArtist; ?> - <?php echo $songTitle; ?>"><?php echo $songTitle; ?></a></div>
                  <div class="profileSongDesc">Released: <?php echo date('m-d-Y', $song->published_date); ?></div>
              </article>
                        <?php endforeach ?>
                        <?php endif ?>
                        </ul>
                <div id="pagination" style="text-align:center;margin-left:0px">
                    <?php echo $pagination; ?>
               </div>
            <?php if ($pagination): ?>
                <div id="result_nums" style="text-align:center;margin-top:-20px">
                    <?php echo $result_nums; ?>    
                </div>
            <?php endif ?>
            </article>
        </div>
    </div><!--/.col-md-8.col-lg-8.col-xl-9-->