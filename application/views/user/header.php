<div class="user-profile-header">
        <div class="profile-background">
            <div class="promotedInner"> 
                <img src="<?php echo user_img($user->username); ?>" style="width:100%;height:auto">
            </div>
        </div>
          <div class="col-lg-4 col-xl-4">
              <a href="<?php echo base_url('u/' . $username) ?>" title="Back to <?php echo $user->username; ?>'s Profile"><img src="<?php echo user_img($user->username); ?>" style="width:150px"></a>
            </div>

            <div class="col-sm-12 col-xs-12 col-md-12 col-lg-8 col-xl-8">
              <h1 class="title"><a href="<?php echo base_url('u/' . $username); ?>" style="color:white"><?php echo $username; ?></a></h1>

                <div class="dropdown">
                <?php if ($this->ion_auth->logged_in() && $this->ion_auth->user()->row()->username !== $this->uri->segment(2)): ?>
                  <a href="#" class="follow-user-button" id="navFollow">
                  <?php if (user_follows($user->id)): ?>
                    Un-Follow
                  <?php else: ?>
                    Follow
                  <?php endif ?></a>
              <?php endif ?>
              <?php if ($this->ion_auth->logged_in() && $this->ion_auth->user()->row()->id === $user->id) { ?>
                <a href="<?php echo base_url('auth/edit_account'); ?>" class="follow-user-button">Edit My Account</a>
              <?php }?>
            </div>

       </div>   
</div>

<div class="user-profile">
                   <ul class="sort">
          <li class="<?php if($this->uri->segment('3') === 'favorites') { echo 'active'; } ?>"><a href="<?php echo base_url('u/' . $username . '/favorites'); ?>">Favorites</a></li>
          <li class="<?php if($this->uri->segment('3') === 'followers') { echo 'active'; } ?>"><a href="<?php echo base_url('u/' . $username . '/followers'); ?>">Followers</a></li>
          <li class="<?php if($this->uri->segment('3') === 'following') { echo 'active'; } ?>"><a href="<?php echo base_url('u/' . $username . '/following'); ?>">Following</a></li>
          <li class="<?php if($this->uri->segment('3') === 'playlists') { echo 'active'; } ?>"><a href="<?php echo base_url('u/' . $username . '/playlists'); ?>">Playlists</a></li>
          </ul>
</div>