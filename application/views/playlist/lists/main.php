<div id="content" class="content section row">

    <div class="col-md-8 bg-base col-lg-8 col-xl-9">
        <div class="ribbon ribbon-highlight">
            <ol class="breadcrumb ribbon-inner">
                <li><a href="<?php echo base_url(); ?>">Home</a>
                </li>
                <li>
                    <a href="<?php echo base_url('playlists/' . $this->uri->segment(2)) ?>">
                        <?php echo $page_title; ?>
                    </a>
                </li>
            </ol>
        </div>

        <?php

            $alert_message = ($this->ion_auth->logged_in() ? "Create your own playlists by clicking the <button class'btn btn-primary'><span class='glyphicon glyphicon-plus'></span></button> button on any song." : "Create your own playlists by logging in and clicking the <button class'btn btn-primary'><span class='glyphicon glyphicon-plus'></span></button>  button on any song.");

         ?>

        <div class="alert alert-info"><?php echo $alert_message; ?></div>
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

<?php if ($playlists) { ?>
<?php foreach ($playlists as $key => $playlist): ?>


        <a href="<?php echo base_url('playlist/' . $playlist->username . '/' . $playlist->url) ?>" rel="bookmark" title="Listen to $playlist->title" class="list-group-item">
            <span class="glyphicon glyphicon-th-list"></span>
             <?php echo $playlist->title; ?>
                <span class="badge"><?php echo 'Total Songs: ' . ucfirst($playlist->track_count); ?></span><br />
                <span style="font-size:10px; color:#ccc">Created By: <?php echo $playlist->username; ?> | Date Published: <?php echo date('m/d/Y', strtotime($playlist->date_created)); ?></span>
        </a>
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