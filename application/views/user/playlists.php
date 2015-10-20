<div id="content" class="content section row">
    <div class="col-md-8 bg-base col-lg-8 col-xl-9">
      <?php $this->load->view('user/header'); ?>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <article class="entry style-single type-post">
                        <ul style="list-style-type: none;margin-left:-60px;padding-top:15px">
                        <?php if ($playlists) { ?>
                        <?php foreach ($playlists as $key => $pl): ?>     
                          <li>          
                          <?php if ($this->ion_auth->logged_in() && $this->ion_auth->user()->row()->username === $username): ?>
                                <div class="col-sm-10 col-md-10 col-lg-10">                              
                          <?php else: ?>
                                <div class="col-sm-12 col-md-12 col-lg-12"> 
                            <?php endif ?>                             
                                  <a href="<?php echo base_url('playlist/'.$username.'/'.$pl->url);?>"title="Listen to <?php echo $pl->title; ?> playlist">
                                    <h3 class="hide-overflow" style="margin-bottom:-15px; padding-top:5px; border:0"><img src="//secure.hiphopvip.com/resources/img/64_playlist_icon.png"> <?php echo $pl->title; ?></h3></a>
                                <strong>Total Songs: <?php echo $pl->track_count; ?></strong> | <strong>Date Created:</strong> <?php echo date('m/d/Y', strtotime($pl->date_created)); ?>
                            <?php if ($this->ion_auth->logged_in() && $this->ion_auth->user()->row()->username === $username): ?>
                                <div style="display:block;margin:10px;">                                    
                                   <a href="<?php echo base_url('manage/playlist/' . $pl->id . '/edit'); ?>" class="btn btn-default btn-xs">Manage</a> | <a href="<?php echo base_url('manage/playlists'); ?>" class="btn btn-xs btn-default" title="Manage Playlists">Delete</a>
                                </div>                         
                            <?php endif ?>
                                    <hr>

                                </div>
                          </li>
                        <?php endforeach ?>
                        <?php } else {
                        if ($this->ion_auth->logged_in() && $this->ion_auth->user()->row()->username === $username) { ?>
                         
                        <div style="text-align:center;font-size:18px;">You haven't created any public playlists yet!</div>
                        <?php } else { ?>
                        <div style="text-align:center;font-size:18px;">This user hasn't created any public playlists.</div>
                          <?php } // user
                          } //else ?>
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