<div id="content" class="content section row">
    <div class="col-md-8 bg-base col-lg-8 col-xl-9">

        <div class="ribbon ribbon-highlight">
            <ol class="breadcrumb ribbon-inner">
                <li>Home</li>
                <li><?php echo $page_title; ?></li>
            </ol>
        </div>  

            <div class="content section row">
            <?php $this->load->view('modules/searchBar'); ?>
        </div>

              <?php $this->load->view('modules/promoted_songs'); ?>

    <?php if ($this->uri->segment('2') == 'popular'): ?>
            <div class="chart-header">
                <ul class="sort">
                    <li class="<?php if($this->uri->segment('3') === 'today') { echo 'active'; } ?>"><a href="<?php echo base_url('songs/popular/today'); ?>">Today</a></li>
                    <li class="<?php if($this->uri->segment('3') === 'week') { echo 'active'; } ?>"><a href="<?php echo base_url('songs/popular/week'); ?>">This Week</a></li>
                    <li class="<?php if($this->uri->segment('3') === 'month') { echo 'active'; } ?>"><a href="<?php echo base_url('songs/popular/month'); ?>">This Month</a></li>
                    <li class="<?php if($this->uri->segment('3') === 'year') { echo 'active'; } ?>"><a href="<?php echo base_url('songs/popular/year'); ?>">This Year</a></li>
                    <li class="<?php if($this->uri->segment('3') === 'all') { echo 'active'; } ?>"><a href="<?php echo base_url('songs/popular/all'); ?>">All Time</a></li>
                </ul> 
            </div>    
    <?php endif ?>


<?php if (isset($songs)): ?>
<?php $currentDate = false; ?>

    <?php foreach ($songs as $key=>$song): ?>
        <?php 
            if ($currentDate != date('m-d-Y',$song->published_date) && $this->uri->segment('2') === 'latest') {
                if (date('m-d-Y') === date('m-d-Y', $song->published_date)) {
                    $day = 'Today';
                } elseif (date('m-d-Y',$song->published_date) === date('m-d-Y',now() - (24 * 60 * 60))){
                    $day = 'Yesterday';
                } else {
                    $day = date('l', $song->published_date);
                }

                echo '<div style="border-bottom: 1px solid #fa8900;margin-bottom:20px">';
                echo '<span class="listSeparatorDay">'.strtoupper($day).'</span> <span class="listSeparatorDate">'.strtoupper(date('F d, Y',$song->published_date)).'</span>';
                echo '</div>';

                $currentDate = date('m-d-Y',$song->published_date); 
            }

            $songArtist = htmlspecialchars($song->song_artist, ENT_QUOTES);
            $songTitle = htmlspecialchars($song->song_title, ENT_QUOTES);
            $songDesc  = htmlspecialchars($song->song_description, ENT_QUOTES);
            $songFeaturing = htmlspecialchars($song->featuring, ENT_QUOTES);
            $songProducer = htmlspecialchars($song->song_producer, ENT_QUOTES);

            $featuring       = (!empty($song->featuring) ? ' Feat. ' . $songFeaturing : NULL);
            $stream_download = ($song->can_download == 'yes' ? 'Stream/Download' : 'Stream Only');
        ?>
        <article class="entry style-media media type-post ">
            <div class="vote-container vote-container-list pull-left" id="vote_container_<?php echo $song->song_id;?>">
                <div id="already_voted_<?php echo $song->song_id; ?>" class="already_voted">Already Voted!</div>
                <div id="text_vote_up_<?php echo $song->song_id; ?>" class="text_vote_up_<?php echo $song->song_id; ?> vote-button vote-button-top upvote vote_up_<?php echo $song->song_id; ?>">
                    <a href="" onclick="vote(<?php echo $song->song_id; ?>, 10); return false;" title="<?php echo $this->lang->line('vote_dope_desc'); ?>"><?php echo $this->lang->line('vote_dope'); ?></a>
                </div>
                <div id="already_voted_down_<?php echo $song->song_id; ?>" class="already_voted">Already Voted!</div>
                <div id="text_vote_down_<?php echo $song->song_id; ?>" class="text_vote_down_<?php echo $song->song_id; ?> vote-button vote-button-bottom downvote vote_down_<?php echo $song->song_id; ?>">
                    <a href="#" onclick="vote(<?php echo $song->song_id; ?>, -10); return false;" title="<?php echo $this->lang->line('vote_nope_desc'); ?>"><?php echo $this->lang->line('vote_nope'); ?></a>
                </div>
            </div>

            <figure class="pull-left list-art">
                <a href="<?php echo base_url('song/' . $song->username . '/' . $song->song_url) ?>" rel="bookmark">
                <img src="<?php echo song_img($song->username, $song->song_url, $song->song_image, 150); ?>" data-src="<?php echo song_img($song->username, $song->song_url, $song->song_image, 150); ?>" alt="Listen to <?php echo $songTitle . ' by ' . $songArtist; ?>"/>
                    <noscript>
                        <img src="<?php echo song_img($song->username, $song->song_url, $song->song_image, 150); ?>"  alt="Listen to <?php echo $songTitle . ' by ' . $songArtist; ?>"/>
                    </noscript>
                </a>

            </figure>
            <header class="entry-header">
                <h3 class="list-title">
                <div id="votes_<?php echo $song->song_id; ?>" class="style-review-score list-review-score" style="background-color:<?php echo $this->voting->hotness_color($song->upvotes, $song->downvotes); ?>">
                    <?php echo $this->voting->vote_sum($song->upvotes, $song->downvotes); ?>
                </div>
                    <a href="<?php echo base_url('song/' . $song->username . '/' . $song->song_url) ?>" rel="bookmark">
                        <span class="list-artist">
                            <?php echo $songArtist; ?>
                        </span>
                       <?php echo $songTitle; ?>
                       <?php if ($songProducer): ?>
                            (Prod. by <?php echo $songProducer; ?>)
                        <?php endif ?> 
                        <span class="list-featuring">
                            <?php echo $featuring; ?>
                        </span>
                    </a>
                </h3>
                <div class="entry-song-detail">
                  <span style="font-weight:bold">Artist: </span>
                    <a href="<?php echo base_url('search?q='.str_replace(' ', '+', $songArtist)); ?>" title="More songs by <?php echo $songArtist ?>"><?php echo $songArtist; ?></a> |
                     Uploaded <?php echo time_ago($song->published_date) ?> by  <a href="<?php echo base_url('u/'.$song->username); ?>" title="View <?php echo $song->username ?>'s Profile" rel="nofollow"><?php echo $song->username; ?></a> 
                     
                     <?php if ($this->ion_auth->in_group('verified', $song->user_id)): ?>
                        <div class="verified-small">
                            <span class="glyphicon glyphicon-ok" style="padding-left:2px;color:white"></span>
                        </div>
                     <?php endif ?>                      
            <?php if ($this->ion_auth->is_admin()): ?>
            <div class="entry-song-detail">

                <?php if ($song->featured != 'yes'): ?>
                    <a href="<?php echo base_url('manage/song/feature/' . $song->song_id . '/feature') ?>" class="" style="font-weight:bold;color:green">Feature</a> | 
                <?php else: ?>
                    <a href="<?php echo base_url('manage/song/feature/' . $song->song_id . '/unfeature'); ?>" class="" style="font-weight:bold;color:green">Un-Feature</a> | 
                <?php endif ?>


                <?php if ($song->promoted != 'yes'): ?>
                    <a href="<?php echo base_url('manage/song/promote/' . $song->song_id . '/promote'); ?>" class="" style="font-weight:bold;color:green">Sponsor</a> | 
                <?php else: ?>
                    <a href="<?php echo base_url('manage/song/promote/' . $song->song_id . '/unpromote'); ?>" class="" style="font-weight:bold;color:green">Un-Sponsor</a> | 
                <?php endif ?>
                    <a href="<?php echo base_url('manage/song/remove/' . $song->song_id . '/copyright'); ?>" style="font-weight:bold;color:red">Copyright</a>| 
       
                <select name="only" id="boost-<?php echo $song->song_id; ?>" class="boostDrop">
                    <option value="" selected>Boost Votes</option>
                    <option value="1">by 01</option>
                    <option value="2">by 02</option>
                    <option value="3">by 03</option>
                    <option value="4">by 04</option>
                    <option value="5">by 05</option>
                    <option value="6">by 06</option>
                    <option value="7">by 07</option>
                    <option value="8">by 08</option>
                    <option value="9">by 09</option>
                    <option value="10">by 10</option>
                </select>

                <select name="only" id="dump-<?php echo $song->song_id; ?>" class="dumpDrop">
                    <option value="" selected>Dump Votes</option>
                    <option value="1">by 01</option>
                    <option value="2">by 02</option>
                    <option value="3">by 03</option>
                    <option value="4">by 04</option>
                    <option value="5">by 05</option>
                    <option value="6">by 06</option>
                    <option value="7">by 07</option>
                    <option value="8">by 08</option>
                    <option value="9">by 09</option>
                    <option value="10">by 10</option>
                </select>

            </div>
            <?php endif ?>
        </header>
    </article>
        <hr>

        <?php endforeach ?>
        <div id="pagination" class="row container" style="text-align:center">
            <?php echo $result_nums; ?>
            <br />
            <?php echo $pagination; ?>
        </div>
<hr>
<?php $this->load->view('modules/ads/banner'); ?>

        <?php $this->load->view('lists/subsection_bigav'); ?>
        <?php else: ?>
        <h1>Application Error</h1>
        <h3>Unable to retrieve <?php echo $page_title ?>. Please try again later.</h3>
        <?php endif ?>

    </div>
    <!--/.col-md-8.col-lg-8.col-xl-9-->