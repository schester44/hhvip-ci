<div id="content" class="content section row">
    <div class="col-md-8 bg-base col-lg-8 col-xl-9">
      <?php $this->load->view('user/header'); ?>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <article class="entry style-single type-post">
                        <ul style="list-style-type: none;margin-left:-40px;padding-top:15px">
                        <?php if ($following) { ?>
                        <?php foreach ($following as $key => $f): ?>  
                        <li>          
                            <div class="listEntry" style="margin-bottom:15px">
                                <div class="col-sm-12 col-md-12 col-lg-12" style="margin-left:-20px;">
                                  <a href="<?php echo base_url('u/'.$f->username);?>"title="View <?php echo $f->username?>'s Profile">
                                  <img src="<?php echo user_img($f->username, 150); ?>" width="64px" height='64px'><span class="followUserName"><?php echo $f->username; ?></span></a>
                                </div>  
                            </div>
                          </li>
                        <?php endforeach ?>
                        <?php } else {
                        if ($this->ion_auth->logged_in() && $this->ion_auth->user()->row()->username === $username) { ?>
                         
                        <div style="text-align:center;font-size:18px;">You should try following someone!</div>
                        <?php } else { ?>
                        <div style="text-align:center;font-size:18px;">This person doesn't follow anybody!</div>
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