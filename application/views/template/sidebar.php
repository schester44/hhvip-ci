<div class="sidebar col-md-4 col-lg-4 col-xl-3">
<?php $this->load->view('modules/ads/sidebar'); ?>				
<?php if (!isset($limitSidebarContent)): ?>
					<aside class="widget hidden-xs hidden-sm sponsor">
						<ul class="social">
							<li><a href="http://twitter.com/hiphopvipcom" title="hiphopVIP on Twitter" target="new"><img src="<?php echo $this->config->item('secure_cdn'); ?>resources/img/social/sT.jpg" width="54" height="54" alt="Twitter"></a></li>
							<li><a href="http://facebook.com/hiphopvip" title="hiphopVIP on Facebook" target="new"><img src="<?php echo $this->config->item('secure_cdn'); ?>resources/img/social/sF.jpg" width="54" height="54" alt="Facebook"></a></li>
							<li><a href="http://instagram.com/kushfriendlyent" title="hiphopVIP on Instagram" target="new"><img src="<?php echo $this->config->item('secure_cdn'); ?>resources/img/social/sI.jpg" width="54" height="54" alt="Instagram"></a></li>
						</ul>
				</aside>

	<?php if ($this->uri->segment('3') !== 'playlists'): ?>
				<aside class="widget">
				<?php if (!$this->ion_auth->logged_in()): ?>					
					<h2 class="widget-title ribbon"><span>MY PLAYLISTS</span></h2>
				<?php else: ?>
					<h2 class="widget-title ribbon"><span><a href="<?php echo base_url('u/'.$this->ion_auth->user()->row()->username.'/playlists'); ?>">MY PLAYLISTS</a></span></h2>
				<?php endif ?>
				
				<?php if (!empty($user_sidebar_playlist)): ?>
					<?php foreach ($user_sidebar_playlist as $key => $usp): ?>
						<div class="entries row">							
							<article class="type-post style-media-list media col-sm-6 col-md-12">
								<figure class="media-object pull-left" style="height:65px">
									<a href="<?php echo base_url('playlist/'.$usp->username.'/'.$usp->url); ?>" title="Listen to <?php echo $usp->title; ?> playlist"><img src="<?php echo $this->config->item('secure_cdn'); ?>resources/img/64_playlist_icon.png" width="54" height="54"></a>		
								</figure>
								
								<div class="media-body">
									<h3  class="sidebar-list-title" style="font-weight:100;padding-top:10px">
										<a href="<?php echo base_url('playlist/'.$usp->username.'/'.$usp->url); ?>" title="Listen to <?php echo $usp->title; ?> playlist"><?php echo $usp->title; ?></a>
									</h3>
									<div class="entry-meta">
										Total Songs: <?php echo $usp->track_count; ?>	
									</div>
								</div>
							</article>
						</div>

					<?php endforeach ?>
				<?php else: ?>
					<h3 style="font-size:18px;text-align:center">You haven't created any playlists yet.<br />

					<?php if (!$this->ion_auth->logged_in()): ?>
						Login and 
					<?php endif ?> 

						Click the <button class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-plus"></span></button> button on any song to get started.</h3>
				<?php endif ?>
			</aside>
	<?php endif ?>	

	<?php if ($sidebar_songs_list): ?>
		

				<aside class="widget sidebarList">
						<h2 class="widget-title ribbon"><span><a href="<?php echo base_url('songs/'.$sidebar_list_type); ?>"><?php echo $sidebar_widget_title ?></a></span></h2>
<?php foreach ($sidebar_songs_list as $list): ?>
	<?php 
	$listTitle = htmlspecialchars($list->song_title, ENT_QUOTES);
	$listArtist = htmlspecialchars($list->song_artist, ENT_QUOTES);

	 ?>
						<div class="entries row">							
							<article class="type-post style-media-list media col-sm-6 col-md-12">

						<figure class="media-object pull-left" style="height:65px">
									<a href="<?php echo base_url('song/'.$list->username.'/'.$list->song_url) ?>" title="Listen and Download <?php echo $listArtist . ' - ' . $listTitle; ?>">
									<div id="votes_<?php echo $list->song_id; ?>" class="style-review-score sidebar-review-score">
										<?php echo $this->voting->vote_sum($list->upvotes, $list->downvotes); ?>
									</div>
						            <img src="<?php echo song_img($list->username, $list->song_url, $list->song_image,64); ?>" width="54" height="54" alt="Listen to and download <?php echo $listTitle . ' by ' . $listArtist; ?>"> 
									</a>
						</figure>
								<div class="media-body">
									<h3  class="sidebar-list-title">
										<a href="<?php echo base_url('song/'.$list->username.'/'.$list->song_url) ?>" title="Listen and Download <?php echo $listArtist . ' - ' . $listTitle; ?>">
										<span style="display:block;font-size:14px;font-weight:100">
											<?php echo $listArtist; ?>
										</span>
										<?php echo $listTitle; ?>
										</a>
									</h3>
									<div class="entry-song-detail">
							Uploaded on <?php echo date('m/d/Y', $list->published_date); ?> by <?php echo $list->username; ?>	
									</div>
								</div>
							</article>
						</div>
<?php endforeach ?>

<a href="<?php echo base_url('songs/'.$sidebar_list_type); ?>" class="btn btn-warning" style="width:100%;font-weight:bold">View More <?php echo $sidebar_widget_title; ?></a>
						</aside>
	<?php endif ?>

<?php if (isset($sidebar_news) && !empty($sidebar_news)): ?>
	<aside class="widget sidebarList">
		<h2 class="widget-title ribbon">
			<span><a href="<?php echo base_url('news'); ?>" title="Latest News">Latest News</a></span>
		</h2>

	<?php foreach ($sidebar_news as $key => $blog): ?>
		<div class="entries row">							
			<article class="type-post style-media-list media col-sm-6 col-md-12">
				<figure class="media-object pull-left" style="height:65px">
					<a href="<?php echo base_url('b/' . $blog->category_title . '/' . $blog->url) ?>" title="<?php echo $blog->title; ?>">
						<img src="<?php echo blog_featured_img($blog->username, $blog->url, $blog->featured_image, 64); ?>">
					</a>
				</figure>
				
				<div class="media-body">
					<h3  class="sidebar-list-title" style="font-weight:100">
						<a href="<?php echo base_url('b/' . $blog->category_title . '/' . $blog->url) ?>" title="<?php echo $blog->title; ?>">
							<?php echo $blog->title; ?>
						</a>
					</h3>
					<div class="entry-meta">
						Category: <?php echo ucfirst($blog->category_title); ?>	
					</div>
				</div>
			</article>
		</div>
	<?php endforeach ?>
	</aside>
<?php endif ?>


<?php endif ?> <!-- end limit content-->
		
			</div><!--/.sidebar col-md-4 col-lg-4 col-xl-3-->