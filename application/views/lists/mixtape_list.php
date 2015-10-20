<div id="content" class="content section row">

    <div class="col-md-8 bg-base col-lg-8 col-xl-9">
        <div class="ribbon ribbon-highlight">
            <ol class="breadcrumb ribbon-inner">
                <li><a href="<?php echo base_url(); ?>">Home</a>
                </li>
                <li>
                    <a href="<?php echo base_url('mixtapes/' . $this->uri->segment(2)) ?>">
                        <?php echo $page_title; ?>
                    </a>
                </li>
            </ol>
        </div>

        <div class="alert alert-info">This is a new section. Please give us some time to populate this area. Enjoy the free music!</div>
                <?php 
                //popular sort
                if ($this->uri->segment(2) == 'popular') { 
                    ?>
                <div class="sortList" id="sort-list" style="margin-bottom:-15px;">
                <div class="sortBy bebas" style="font-size:18px;margin-bottom:-2px;"><a href="#" title="Sort Mixtapes" id="show-sort"><strong>SORT BY</strong></a></div>

                    <ul class="sort-links" style="margin-left:-45px;">
                        <li class="<?php if ($this->uri->segment('3') == 'today') { echo 'active'; } ?> "><a href="<?php echo base_url('mixtapes/popular/today'); ?>" title="Popular mixtapes today">Today</a>
                        </li>
                        <li class="<?php if ($this->uri->segment('3') == 'week' || !$this->uri->segment(3)) { echo 'active'; } ?> "><a href="<?php echo base_url('mixtapes/popular/week'); ?>" title="Popular mixtapes this week">This Week</a>
                        </li>
                        <li class="<?php if ($this->uri->segment('3') == 'month') { echo 'active'; } ?> "><a href="<?php echo base_url('mixtapes/popular/month'); ?>" title="Popular mixtapes this month">This Month</a>
                        </li>
                        <li class="<?php if ($this->uri->segment('3') == 'year') { echo 'active'; } ?> "><a href="<?php echo base_url('mixtapes/popular/year'); ?>" title="Popular mixtapes this year">This Year</a>
                        </li>
                        <li class="<?php if ($this->uri->segment(3) == 'all') { echo 'active'; } ?> "><a href="<?php echo base_url('mixtapes/popular/all'); ?>" title="Popular mixtapes today">All</a>
                        </li>

                    </ul>
                </div>
                <hr>
                <?php } ?>

<?php if ($mixtape) { ?>
<?php foreach ($mixtape as $key => $tape): ?>

    <?php 
    $artist         = htmlspecialchars($tape->tape_artist, ENT_QUOTES);
    $title          = htmlspecialchars($tape->tape_title, ENT_QUOTES);
    $description    = htmlspecialchars($tape->tape_description, ENT_QUOTES);
     ?>

        <article class="entry style-media media type-post listEntry">
       <!--     <div class="vote-container vote-container-list pull-left" id="vote_container_<?php echo $tape->id;?>">
                <div id="already_voted_<?php echo $tape->id; ?>" class="already_voted">Already Voted!</div>
                <div id="text_vote_up_<?php echo $tape->id; ?>" class="vote-button vote-button-top upvote">
                    <a href="" onclick="vote(<?php echo $tape->id; ?>, 10); return false;" title="<?php echo $this->lang->line('vote_dope_desc'); ?>">
                        <?php echo $this->lang->line('vote_dope'); ?></a>
                </div>
                <div id="already_voted_down_<?php echo $tape->id; ?>" class="already_voted">Already Voted!</div>
                <div id="text_vote_down_<?php echo $tape->id; ?>" class="vote-button vote-button-bottom downvote">
                    <a href="#" onclick="vote(<?php echo $tape->id; ?>, -10); return false;" title="<?php echo $this->lang->line('vote_nope_desc'); ?>">
                        <?php echo $this->lang->line('vote_nope'); ?></a>
                </div>
            </div>-->

            <figure class="media-object pull-left list-image" style="height:65px">
                <a href="<?php echo base_url('mixtape/' . $tape->username . '/' . $tape->tape_url) ?>" rel="bookmark">
           <!--     <div id="votes_<?php echo $tape->id; ?>" class="style-review-score">
                    <?php echo $this->voting->vote_sum($tape->upvotes, $tape->downvotes); ?>
                </div>-->
                    <img src="<?php echo tape_img($tape->username,$tape->tape_url,$tape->tape_image, 64); ?>" data-src="<?php echo tape_img($tape->username,$tape->tape_url,$tape->tape_image, 64); ?>" width="54" height="54" alt="Listen to <?php echo $tape->tape_title . ' by ' . $tape->tape_artist; ?>" />
                    <noscript>
                        <img src="<?php echo tape_img($tape->username,$tape->tape_url,$tape->tape_image, 64); ?>" width="54px" height="54px" />
                    </noscript>
                </a>

            </figure>
                <header class="">
               <!-- <header class="entry-header">  temp til voting enabled -->
                <h3 class="list-title blck"><a href="<?php echo base_url('mixtape/' . $tape->username . '/' . $tape->tape_url) ?>" rel="bookmark"><?php echo $title; ?><br /><span class="orange"><?php echo $artist; ?></span></a></h3>
                
                <div class="list-desc"><?php echo $description; ?></div>
                <div class="entry-song-detail">
                    <strong>Uploader:</strong> 
                    <a href="<?php echo base_url('u/'.$tape->username); ?>" title="View <?php echo $tape->username; ?>'s Profile" rel="nofollow">
                        <?php echo $tape->username; ?></a>| <strong>Released:</strong> 
                    <?php echo time_ago($tape->published_date) ?> | <strong>Artist:</strong> 
                    <a href="<?php echo base_url('search/mixtapes/'.$artist) ?>" title="More mixtapes by <?php echo $artist; ?>"><?php echo $artist; ?></a> | <strong>Type:</strong> Mixtape
                </div>

            </header>
        </article>
<?php endforeach ?>
    

        <div id="pagination" class="row container" style="text-align:center">
            <?php echo $result_nums; ?>
            <br />
            <?php echo $pagination; ?>
        </div>

<?php } else { ?>
    


        <?php $this->load->view('lists/subsection_bigav'); ?>
        <h1>Application Error</h1>
        <h3>Unable to retrieve <?php echo $page_title ?>. Please try again later.</h3>
<?php } ?>
    </div>
    <!--/.col-md-8.col-lg-8.col-xl-9-->