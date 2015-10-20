<nav id="header" class="header-navbar" role="navigation">
			<div class="header-navbar-inner container">
				<div id="brand" class="navbar-brand">
					<a href="<?php echo base_url(); ?>" rel="bookmark">

						<!-- to disable lazy loading, remove data-src and data-src-retina -->
						<img src="<?php echo $this->config->item('secure_cdn'); ?>resources/img/logo2.png" data-src="<?php echo $this->config->item('secure_cdn'); ?>resources/img/logo2.png" data-src-retina="<?php echo $this->config->item('secure_cdn'); ?>resources/img/logo2.png" alt="HIPHOPVIP" width="110">

						<!--fallback for no javascript browsers-->
						<noscript>
							<img src="<?php echo $this->config->item('secure_cdn'); ?>resources/img/logo2.png" alt="">
						</noscript>
					</a>
				</div>
	
				<ul class="nav nav-icons">
					<li>
						<a href="#" class="btn-icon" data-toggle=".header-navbar-inner" data-toggle-class="search-toggled-in">
							<span class="search-toggled-out-icon glyphicon glyphicon-search"></span>
							<span class="search-toggled-in-icon glyphicon glyphicon-remove"></span>
						</a>
					</li>
				</ul>

				<div class="search-wrapper js-stoppropagation">
					<div class="search-wrapper-inner">
						<form action="<?php echo base_url('search') ?>" method="get">
							<input type="text" id="q" name="q" value="" placeholder="search...">
							<button class="btn-icon" type="submit"><span class="glyphicon glyphicon-search"></span></button>
							</form>
					</div>
				</div>	
				<ul class="nav navbar-nav pull-left">
					<li class="<?php if(!$this->uri->segment(1)) { echo 'active'; } ?>"><a href="<?php echo base_url(); ?>" title="HHVIP Home">Home</a></li>
					<li>|</li>
					<li class="<?php if($this->uri->segment(1) == 'songs' && $this->uri->segment(2) == 'popular') { echo 'active'; } ?>" title="View Popular Songs"><a href="<?php echo base_url('songs/popular/week'); ?>" data-toggle="li">Top</a></li>
					<li>|</li>
					<li class="<?php if($this->uri->segment(1) == 'songs' && $this->uri->segment(2) == 'latest') { echo 'active'; } ?>" title="View Latest Songs"><a href="<?php echo base_url('songs/latest'); ?>" data-toggle="li">Latest</a></li>
					<li>|</li>
					<li class="<?php if($this->uri->segment(1) == 'playlists' || $this->uri->segment(1) == 'playlist') { echo 'active'; } ?>" title="View curated playlists"><a href="<?php echo base_url('playlists'); ?>" data-toggle="li">Playlists</a></li>
				</ul>
					
				<ul class="nav navbar-nav pull-right" id="rightNavTop">

	<?php if ($this->ion_auth->logged_in()) { 
		$user = $this->ion_auth->user()->row(); ?>
                  
<?php if ($this->ion_auth->is_admin() || $this->ion_auth->is_moderator()): ?>
    <?php if ($this->uri->segment(1) == 'song' && (!empty($song))): ?>              		
	<li class="dropdown">
    <a class="btn btn-primary" data-toggle="dropdown" href="#">
     Track<span class="caret"></span>
    </a>
	    <ul class="dropdown-menu">
	    	<li> <a href="<?php echo base_url('manage/song/'.$song->song_id.'/edit') ?>">Edit/Update Song</a></li>
    		<li class="divider"></li>
		
		<?php if (!$copyright_status): ?>
			<?php if (!$featured_nav): ?>
			    <li><a href="<?php echo base_url('manage/song/feature/'.$song->song_id.'/feature') ?>">Feature Song (Frontpage)</a></li>
	    	<?php else: ?>
	    		<li><a href="<?php echo base_url('manage/song/feature/'.$song->song_id.'/unfeature') ?>">Un-Feature</a></li>
			<?php endif ?>			
    	
	    	<?php if (!$promoted_nav): ?>    		
	    		<li><a href="<?php echo base_url('manage/song/promote/'.$song->song_id.'/promote') ?>">Sponsor Song</a></li>
	    	<?php else: ?>	
	    		<li><a href="<?php echo base_url('manage/song/promote/'.$song->song_id.'/unpromote') ?>">Un-Sponsor</a></li>
	    	<?php endif ?>
    		<li class="divider"></li>
				<li><a href="<?php echo base_url('manage/song/remove/'.$song->song_id.'/copyright'); ?>" class="btn btn-danger" style="color: yellow">Copyright</a></li>
			<?php endif ?>
				<li><a href="<?php echo base_url('manage/song/remove/'.$song->song_id.'/removed'); ?>" class="btn btn-danger" style="color: white">Remove</a></li> 
		</ul>
  	</li>
  	<?php endif ?>
 <?php endif ?>

      <li><a class="upload-btn btn btn-danger nav-upload-btn" href="<?php echo base_url('upload')?>" title="Upload Songs">Upload</a></li>

  	 <li class="dropdown active">
          <a href="#" class="dropdown-toggle navbar-username hide-overflow" data-toggle="dropdown" style="margin-right:-5px"><?php if (!empty($user)) { echo $user->username; } ?><span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li style="padding-top:10px"><a href="<?php echo base_url('u/'.$user->username) ?>"><span class="glyphicon glyphicon-user"></span> My Profile</a></li>
            <li style="padding-top:10px"><a href="<?php echo base_url('u/'.$user->username.'/playlists') ?>"><span class="glyphicon glyphicon-play"></span> My Playlists</a></li>
            <li style="padding-top:10px"><a href="<?php echo base_url('u/'.$user->username.'/favorites') ?>"><span class="glyphicon glyphicon-star"></span> Favorites</a></li>
            <li style="padding-top:10px"><a href="<?php echo base_url('u/'.$user->username.'/following') ?>"><span class="glyphicon glyphicon-list"></span> Following</a></li>
            <li style="padding-top:10px;padding-bottom:10px"><a href="<?php echo base_url('u/'.$user->username.'/followers') ?>"><span class="glyphicon glyphicon-list"></span> Followers</a></li>
          </ul>
        </li>

        <li class="dropdown active">
        	          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="<?php echo $this->config->item('secure_cdn'); ?>resources/img/main/navGear.png" style="padding-bottom:3px"></a>
          <ul class="dropdown-menu" role="menu">
            <li style="padding-top:10px"><a href="<?php echo base_url('manage/songs') ?>">Manage My Songs</a></li>
            <li><a href="<?php echo base_url('manage/playlists') ?>">Manage My Playlists</a></li>	

            <?php if ($this->ion_auth->is_admin()): ?>
            <li><a href="<?php echo base_url('manage/mixtapes') ?>">Manage Mixtapes</a></li>	
            <li><a href="<?php echo base_url('u/'.$this->ion_auth->user()->row()->username.'/stats') ?>">Stats</a></li>	
            <?php endif ?>
            <li class="divider"></li>
            <li><a href="<?php echo base_url('news') ?>" title="HHVIP Blog">Blog</a></li>
            <li><a href="<?php echo base_url('playlists') ?>" title="Public Playlists on HHVIP">Playlists</a></li>
            <li class="divider"></li>
            <li><a href="<?php echo base_url('auth/edit_account') ?>"><span class="glyphicon glyphicon-tasks"></span> Settings</a></li>
    		<li><a href="<?php echo  base_url('auth/logout')?>"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
    <?php if ($this->ion_auth->is_admin() || $this->ion_auth->is_moderator()): ?>
    		<li class="divider"></li>
            <li><a href="<?php echo base_url('backend') ?>">Admin</a></li>	
<?php endif ?>
          </ul>
        </li>

             <? }  else { ?>
      <li><a class="upload-btn btn btn-danger nav-upload-btn" href="<?php echo base_url('auth/login')?>" title="Upload Songs">Upload</a></li>       
      <li>  <a href="<?php echo  base_url('auth/create_account')?>">Sign Up</a></li>
      <li>  <a href="<?php echo  base_url('auth/login')?>">Login</a></li>
              <? }?>
				</ul>
				
				<ul class="nav navbar-nav">
					<li class="nav-all pull-right full-subnav-wrapper">
						<a href="#" data-toggle="li" id="pull"> 
							<span class="toggle glyphicon glyphicon-align-justify"> </span>
						</a>

						<div class="row subnav-wrapper">
							
							<div class="col-md-2 col-sm-2 bg-bar">
								<ul class="subnav-full">
								<?php if ($this->ion_auth->is_admin() || $this->ion_auth->is_moderator()): ?>
    							<?php if ($this->uri->segment(1) == 'song' && (!empty($song))): ?>
								<span class="subnav-header">ADMIN</span>
								<li><a href="<?php echo base_url('manage/song/'.$song->song_id.'/edit'); ?>" title="Edit Song">Edit Track</a></li>
									<?php if (!$copyright_status): ?>
					   				<?php if(!$featured_nav): ?>
					    		<li><a href="<?php echo base_url('manage/song/feature/'.$song->song_id.'/feature') ?>">Feature Song (Frontpage)</a></li>
						    		<?php else: ?>
					    		<li><a href="<?php echo base_url('manage/song/feature/'.$song->song_id.'/unfeature') ?>">Un-Feature</a></li>
					    			<?php endif ?>
					    		<?php if(!$promoted_nav): ?>
					    		<li><a href="<?php echo base_url('manage/song/promote/'.$song->song_id.'/promote') ?>">Sponsor Song</a></li>
					    			<?php else: ?>
					    		<li><a href="<?php echo base_url('manage/song/promote/'.$song->song_id.'/unpromote') ?>">Un-Sponsor</a></li>
					    			<?php endif ?>
					    		
								<li><a href="<?php echo base_url('manage/song/remove/'.$song->song_id.'/copyright'); ?>">Copyright</a></li>
									<?php endif ?>
								<li><a href="<?php echo base_url('manage/song/remove/'.$song->song_id.'/removed'); ?>">Remove</a></li> 
							<?php endif ?>
							<?php endif ?>
								<span class="subnav-header">MUSIC</span>
								<?php if ($this->ion_auth->logged_in()): ?>
									<li class="active"><a href="<?php echo base_url('upload'); ?>" title="Upload Music">Upload Music</a></li>
								<?php endif ?>
									<li><a href="<?php echo base_url('songs/popular/week'); ?>" title="View the most Popular Songs">Top Songs</a></li>
									<li><a href="<?php echo base_url('songs/latest'); ?>" title="View the Latest Songs">Latest Songs</a></li>
									<li><a href="<?php echo base_url('playlists'); ?>" title="User curated playlists">User Playlists</a></li>
									<span class="subnav-header">ACCOUNT</span>
							<?php if ($this->ion_auth->logged_in()) { ?>
									<li><a href="<?php echo base_url('u/'.$user->username); ?>" title="My Profile">My Profile</a></li>
									<li><a href="<?php echo base_url('manage/songs'); ?>" title="Manage Songs">Manage Songs</a></li>
									<li><a href="<?php echo base_url('manage/playlists'); ?>" title="Manage Playlists">Manage Playlists</a></li>
									<li><a href="<?php echo base_url('manage'); ?>" title="Settings">Settings</a></li>

									<li><a href="<?php echo base_url('auth/logout'); ?>" title="Logout">Logout</a></li>
							<?php } else { ?>
									<li class="active"><a href="<?php echo base_url('auth/create_account'); ?>">Sign-up</a></li>
									<li class="active"><a href="<?php echo base_url('auth/login'); ?>">Login</a></li>
									<?php } ?>
								</ul>
							</div>

						</div>
					</li>
				</ul>
			</div>
		</nav>