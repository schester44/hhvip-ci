<div class="section row entries">
         <article class="entry col-xs-12 col-sm-12">
        </article>
</div>
			<div id="content" class="content section row">
				<div class="col-md-8 bg-base col-lg-8 col-xl-9">

					<div class="ribbon ribbon-highlight">
						<ol class="breadcrumb ribbon-inner">
							<li><a href="<?php echo base_url(); ?>">Home</a></li>
							<li><a href="<?php echo base_url('backend'); ?>">Backend</a></li>							
							<li><a href="<?php echo base_url('backend/stats'); ?>">Stats</a></li>
						</ol>
					</div>
					
					<header class="page-header">
						
					<h2 class="page-title">VIP Backend</h2>
					</header>

					<article class="entry style-single type-post">

						<div class="entry-content">
							<p class="lead">
<div id="infoMessage"><?php echo $this->session->flashdata('message');;?></div>
								
							</p>

							<h2 style="border:0;margin-bottom:0px">Song Stats</h2>
							<div class="row">

							<div role="tabpanel">
  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#today" aria-controls="home" role="tab" data-toggle="tab">Today</a></li>
    <li role="presentation"><a href="#week" aria-controls="profile" role="tab" data-toggle="tab">Week</a></li>
    <li role="presentation"><a href="#month" aria-controls="messages" role="tab" data-toggle="tab">Month</a></li>
    <li role="presentation"><a href="#year" aria-controls="settings" role="tab" data-toggle="tab">Year</a></li>
    <li role="presentation"><a href="#all" aria-controls="settings" role="tab" data-toggle="tab">All</a></li>
  </ul>
  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="today">
    					<div class="col-sm-4">
							<h3><span style="color:#fa8900;font-weight:bold;padding-right:25px">TOP 5 PLAYS</span></h3>
							<ul style="list-style-type:none;margin-left:-25px">
								<?php if (isset($top_5_plays_today) && !empty($top_5_plays_today)): ?>
									
								<?php foreach ($top_5_plays_today as $key => $tfd): ?>
									<li class="hide-overflow"><a href="<?php echo base_url('song/'.username($tfd->song_user_id) . '/' . $tfd->song_url) ?>">[<span style="color:red"><?php echo $tfd->total; ?></span>] <?php echo ucfirst($tfd->title); ?></a></li>
								<?php endforeach ?>
								<?php else: ?>
								<li>No Stats available</li>
								<?php endif ?>

							</ul>
						</div>
						<div class="col-sm-4">
							<h3><span style="color:#fa8900;font-weight:bold;padding-right:25px">TOP 5 D/Ls</span></h3>
							<ul style="list-style-type:none;margin-left:-25px">
								<?php if (isset($top_5_downloads_today) && !empty($top_5_downloads_today)): ?>
									
								<?php foreach ($top_5_downloads_today as $key => $tfd): ?>
									<li class="hide-overflow"><a href="<?php echo base_url('song/'.username($tfd->song_user_id) . '/' . $tfd->song_url) ?>">[<span style="color:red"><?php echo $tfd->total; ?></span>] <?php echo ucfirst($tfd->title); ?></a></li>
								<?php endforeach ?>
								<?php else: ?>
								<li>No Stats available</li>
								<?php endif ?>
							</ul>
						</div>
							<div class="col-sm-4">
								<h3><span style="color:#fa8900;font-weight:bold;padding-right:25px">TOP 5 VIEWS</span></h3>
								<ul style="list-style-type:none;margin-left:-25px">
									<?php if (isset($top_5_views_today) && !empty($top_5_views_today)): ?>
										
									<?php foreach ($top_5_views_today as $key => $tfd): ?>
										<li class="hide-overflow"><a href="<?php echo base_url('song/'.username($tfd->song_user_id) . '/' . $tfd->song_url) ?>">[<span style="color:red"><?php echo $tfd->total; ?></span>] <?php echo ucfirst($tfd->title); ?></a></li>
									<?php endforeach ?>
									<?php else: ?>
									<li>No Stats available</li>
									<?php endif ?>

								</ul>
							</div>
    </div>
    <div role="tabpanel" class="tab-pane" id="week">
		<div class="col-sm-4">
			<h3><span style="color:#fa8900;font-weight:bold;padding-right:25px">TOP 5 PLAYS</span></h3>
			<ul style="list-style-type:none;margin-left:-25px">
				<?php if (isset($top_5_plays_week) && !empty($top_5_plays_week)): ?>
					
				<?php foreach ($top_5_plays_week as $key => $tfd): ?>
					<li class="hide-overflow"><a href="<?php echo base_url('song/'.username($tfd->song_user_id) . '/' . $tfd->song_url) ?>">[<span style="color:red"><?php echo $tfd->total; ?></span>] <?php echo ucfirst($tfd->title); ?></a></li>
				<?php endforeach ?>
				<?php else: ?>
				<li>No Stats available</li>
				<?php endif ?>

			</ul>
		</div>
		<div class="col-sm-4">
			<h3><span style="color:#fa8900;font-weight:bold;padding-right:25px">TOP 5 D/Ls</span></h3>
			<ul style="list-style-type:none;margin-left:-25px">
				<?php if (isset($top_5_downloads_week) && !empty($top_5_downloads_week)): ?>
					
				<?php foreach ($top_5_downloads_week as $key => $tfd): ?>
					<li class="hide-overflow"><a href="<?php echo base_url('song/'.username($tfd->song_user_id) . '/' . $tfd->song_url) ?>">[<span style="color:red"><?php echo $tfd->total; ?></span>] <?php echo ucfirst($tfd->title); ?></a></li>
				<?php endforeach ?>
				<?php else: ?>
				<li>No Stats available</li>
				<?php endif ?>
			</ul>
		</div>
		<div class="col-sm-4">
			<h3><span style="color:#fa8900;font-weight:bold;padding-right:25px">TOP 5 VIEWS</span></h3>
				<ul style="list-style-type:none;margin-left:-25px">
					<?php if (isset($top_5_views_week) && !empty($top_5_views_week)): ?>
						
					<?php foreach ($top_5_views_week as $key => $tfd): ?>
						<li class="hide-overflow"><a href="<?php echo base_url('song/'.username($tfd->song_user_id) . '/' . $tfd->song_url) ?>">[<span style="color:red"><?php echo $tfd->total; ?></span>] <?php echo ucfirst($tfd->title); ?></a></li>
					<?php endforeach ?>
					<?php else: ?>
					<li>No Stats available</li>
					<?php endif ?>

				</ul>
		</div>
    </div>
    <div role="tabpanel" class="tab-pane" id="month">
		<div class="col-sm-4">
			<h3><span style="color:#fa8900;font-weight:bold;padding-right:25px">TOP 5 PLAYS</span></h3>
			<ul style="list-style-type:none;margin-left:-25px">
				<?php if (isset($top_5_plays_month) && !empty($top_5_plays_month)): ?>
					
				<?php foreach ($top_5_plays_month as $key => $tfd): ?>
					<li class="hide-overflow"><a href="<?php echo base_url('song/'.username($tfd->song_user_id) . '/' . $tfd->song_url) ?>">[<span style="color:red"><?php echo $tfd->total; ?></span>] <?php echo ucfirst($tfd->title); ?></a></li>
				<?php endforeach ?>
				<?php else: ?>
				<li>No Stats available</li>
				<?php endif ?>

			</ul>
		</div>
		<div class="col-sm-4">
			<h3><span style="color:#fa8900;font-weight:bold;padding-right:25px">TOP 5 D/Ls</span></h3>
			<ul style="list-style-type:none;margin-left:-25px">
				<?php if (isset($top_5_downloads_month) && !empty($top_5_downloads_month)): ?>
					
				<?php foreach ($top_5_downloads_month as $key => $tfd): ?>
					<li class="hide-overflow"><a href="<?php echo base_url('song/'.username($tfd->song_user_id) . '/' . $tfd->song_url) ?>">[<span style="color:red"><?php echo $tfd->total; ?></span>] <?php echo ucfirst($tfd->title); ?></a></li>
				<?php endforeach ?>
				<?php else: ?>
				<li>No Stats available</li>
				<?php endif ?>
			</ul>
		</div>
		<div class="col-sm-4">
			<h3><span style="color:#fa8900;font-weight:bold;padding-right:25px">TOP 5 VIEWS</span></h3>
			<ul style="list-style-type:none;margin-left:-25px">
					<?php if (isset($top_5_views_month) && !empty($top_5_views_month)): ?>
						
					<?php foreach ($top_5_views_month as $key => $tfd): ?>
						<li class="hide-overflow"><a href="<?php echo base_url('song/'.username($tfd->song_user_id) . '/' . $tfd->song_url) ?>">[<span style="color:red"><?php echo $tfd->total; ?></span>] <?php echo ucfirst($tfd->title); ?></a></li>
					<?php endforeach ?>
					<?php else: ?>
					<li>No Stats available</li>
					<?php endif ?>

				</ul>
		</div>
    </div>
    <div role="tabpanel" class="tab-pane" id="year">
    		<div class="col-sm-4">
			<h3><span style="color:#fa8900;font-weight:bold;padding-right:25px">TOP 5 PLAYS</span></h3>
			<ul style="list-style-type:none;margin-left:-25px">
				<?php if (isset($top_5_plays_year) && !empty($top_5_plays_year)): ?>
					
				<?php foreach ($top_5_plays_year as $key => $tfd): ?>
					<li class="hide-overflow"><a href="<?php echo base_url('song/'.username($tfd->song_user_id) . '/' . $tfd->song_url) ?>">[<span style="color:red"><?php echo $tfd->total; ?></span>] <?php echo ucfirst($tfd->title); ?></a></li>
				<?php endforeach ?>
				<?php else: ?>
				<li>No Stats available</li>
				<?php endif ?>

			</ul>
		</div>
		<div class="col-sm-4">
			<h3><span style="color:#fa8900;font-weight:bold;padding-right:25px">TOP 5 D/Ls</span></h3>
			<ul style="list-style-type:none;margin-left:-25px">
				<?php if (isset($top_5_downloads_year) && !empty($top_5_downloads_year)): ?>
					
				<?php foreach ($top_5_downloads_year as $key => $tfd): ?>
					<li class="hide-overflow"><a href="<?php echo base_url('song/'.username($tfd->song_user_id) . '/' . $tfd->song_url) ?>">[<span style="color:red"><?php echo $tfd->total; ?></span>] <?php echo ucfirst($tfd->title); ?></a></li>
				<?php endforeach ?>
				<?php else: ?>
				<li>No Stats available</li>
				<?php endif ?>
			</ul>
		</div>
		<div class="col-sm-4">
			<h3><span style="color:#fa8900;font-weight:bold;padding-right:25px">TOP 5 VIEWS</span></h3>
			<ul style="list-style-type:none;margin-left:-25px">
					<?php if (isset($top_5_views_year) && !empty($top_5_views_year)): ?>
						
					<?php foreach ($top_5_views_year as $key => $tfd): ?>
						<li class="hide-overflow"><a href="<?php echo base_url('song/'.username($tfd->song_user_id) . '/' . $tfd->song_url) ?>">[<span style="color:red"><?php echo $tfd->total; ?></span>] <?php echo ucfirst($tfd->title); ?></a></li>
					<?php endforeach ?>
					<?php else: ?>
					<li>No Stats available</li>
					<?php endif ?>

				</ul>
		</div>
    </div>
    <div role="tabpanel" class="tab-pane" id="all">
    		<div class="col-sm-4">
			<h3><span style="color:#fa8900;font-weight:bold;padding-right:25px">TOP 5 PLAYS</span></h3>
			<ul style="list-style-type:none;margin-left:-25px">
				<?php if (isset($top_5_plays_all) && !empty($top_5_plays_all)): ?>
					
				<?php foreach ($top_5_plays_all as $key => $tfd): ?>
					<li class="hide-overflow"><a href="<?php echo base_url('song/'.username($tfd->song_user_id) . '/' . $tfd->song_url) ?>">[<span style="color:red"><?php echo $tfd->total; ?></span>] <?php echo ucfirst($tfd->title); ?></a></li>
				<?php endforeach ?>
				<?php else: ?>
				<li>No Stats available</li>
				<?php endif ?>

			</ul>
		</div>
		<div class="col-sm-4">
			<h3><span style="color:#fa8900;font-weight:bold;padding-right:25px">TOP 5 D/Ls</span></h3>
			<ul style="list-style-type:none;margin-left:-25px">
				<?php if (isset($top_5_downloads_all) && !empty($top_5_downloads_all)): ?>
				<?php foreach ($top_5_downloads_all as $key => $tfd): ?>
					<li class="hide-overflow"><a href="<?php echo base_url('song/'.username($tfd->song_user_id) . '/' . $tfd->song_url) ?>">[<span style="color:red"><?php echo $tfd->total; ?></span>] <?php echo ucfirst($tfd->title); ?></a></li>
				<?php endforeach ?>
				<?php else: ?>
				<li>No Stats available</li>
				<?php endif ?>
			</ul>
		</div>
		<div class="col-sm-4">
			<h3><span style="color:#fa8900;font-weight:bold;padding-right:25px">TOP 5 VIEWS</span></h3>
			<ul style="list-style-type:none;margin-left:-25px">
					<?php if (isset($top_5_views_all) && !empty($top_5_views_all)): ?>
						
					<?php foreach ($top_5_views_all as $key => $tfd): ?>
						<li class="hide-overflow"><a href="<?php echo base_url('song/'.username($tfd->song_user_id) . '/' . $tfd->song_url) ?>">[<span style="color:red"><?php echo $tfd->total; ?></span>] <?php echo ucfirst($tfd->title); ?></a></li>
					<?php endforeach ?>
					<?php else: ?>
					<li>No Stats available</li>
					<?php endif ?>

				</ul>
		</div>
    
    </div>
  </div>

</div>


							</div>
							<h3><span style="color:#fa8900;font-weight:bold;padding-right:25px">TOTAL SONG PLAYS:</span> <?php if(isset($play_stats) && !empty($play_stats)) { echo $play_stats; } ?></h3>
							<h3><span style="color:#fa8900;font-weight:bold;padding-right:25px">TOTAL SONG DOWNLOADS:</span> <?php if(isset($download_stats) && !empty($download_stats)) { echo $download_stats; } ?></h3>
							
							<h2 style="border:0;margin-bottom:-25px">Total Songs Uploaded</h2>
							<h3><span style="color:#fa8900;font-weight:bold;padding-right:25px">LAST 7 DAYS:</span> <?php echo $week_count; ?></h3>
							<h3><span style="color:#fa8900;font-weight:bold;padding-right:25px">LAST 30 DAYS:</span> <?php echo $month_count; ?></h3>
							<h3><span style="color:#fa8900;font-weight:bold;padding-right:25px">LAST 365 DAYS:</span> <?php echo $year_count; ?></h3>
							<h3><span style="color:#fa8900;font-weight:bold;padding-right:25px">TOTAL SONGS:</span> <?php echo $all_time_count; ?></h3>
					

						</div>

					</article>
					

				</div><!--/.col-md-8.col-lg-8.col-xl-9-->

				<?php echo $this->load->view('admin/sidebar'); ?>