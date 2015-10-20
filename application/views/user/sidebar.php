			<div class="profile">
				<img src="<?php echo user_img($user->username); ?>" class="img-circle">
            <?php if ($this->ion_auth->logged_in() && $this->ion_auth->user()->row()->id === $user->id) { ?>
                <a href="<?php echo base_url('auth/edit_account'); ?>" class="btn btn-warning btn-xs" style="margin-top:-50px;margin-left:21px;">Change Image</a>
            	<?php }?>
            
				<p><?php echo $user->username; ?>
       <?php if ($this->ion_auth->in_group('verified', $user->id)): ?>
              <span class="glyphicon glyphicon-ok verified-large"></span>          
       <?php endif ?> 
       </p>

			</div>
			<?php if ($this->uri->segment('1') === 'auth') { ?>
					<ul class="list-group">
						<li class="list-group-item"><a href="<?php echo base_url('u/'.$user->username) ?>">My Profile</a></li>
						<li class="list-group-item"><a href="<?php echo base_url('auth/edit_account') ?>">Manage Account</a></li>
						<li class="list-group-item"><a href="<?php echo base_url('manage/songs') ?>">Manage Songs</a></li>
						<li class="list-group-item"><a href="<?php echo base_url('manage/playlists') ?>">Manage Playlists</a></li>
						<li class="list-group-item"><a href="<?php echo base_url('auth/change_password') ?>">Change Password</a></li>
					</ul>
					<?php } else { ?>

                    <script type="text/javascript">
                    $(document).ready(function(){

                    $('#user_website').tooltip();
                    $('#user_twitter').tooltip();
                    $('#user_location').tooltip();
                    });
                    </script>

					 <ul class="list-group">
                      <?php if (!empty($user->location)): ?>
            <li class="list-group-item hide-overflow" id="user_location" data-toggle="tooltip" data-placement="top" title="<?php echo $user->location; ?>"><span class="glyphicon glyphicon-home"></span>  <?php echo $user->location; ?></li>
                      <?php endif ?>
                      <?php if (!empty($user->twitter_handle)): ?>     
            <li class="list-group-item hide-overflow" id="user_twitter" data-toggle="tooltip" data-placement="top" title="<?php echo $user->twitter_handle; ?>"> <span class="glyphicon glyphicon-thumbs-up"></span> <a href="http://twitter.com/<?php echo $user->twitter_handle ?>" title="<?php echo $user->username ?> on Twitter" rel="nofollow">@<?php echo $user->twitter_handle; ?></a></li>
                      <?php endif ?>
                      <?php if (!empty($user->website)): ?>
            <li class="list-group-item hide-overflow" id="user_website" data-toggle="tooltip" data-placement="top" title="<?php echo $user->website; ?>"> <span class="glyphicon glyphicon-cloud"></span> <?php echo $user->website; ?></li> 
                      <?php endif ?>
                      <?php if (!empty($user->bio)): ?>
            <li class="list-group-item"><span class="glyphicon glyphicon-user"></span> <?php echo $user->bio; ?></li>                           
                      <?php endif ?>
                    </ul>
						<?php } ?>
