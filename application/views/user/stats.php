<style type="text/css">
	.stats-main-title {
		font-weight: bold;
	}

	.stats-most-list {
		list-style-type: none;
		margin-left: -35px;
	}

	.stats-most-list li {
		white-space: nowrap;
		text-overflow: ellipsis;
		overflow: hidden;
		width: 100%;
		box-sizing: border-box;
		margin-bottom: -10px;
	}

	.stats-most-list li img {
		width: 17px;
		height: 17px;
		padding-right: 3px;
		float: left;
	}

</style>

			<div class="section row entries">
				<article class="entry col-xs-12 col-sm-12">
				<?php $this->load->view('modules/searchBar'); ?>
				</article>
			</div>
			
			<div id="content" class="content section row">

				<div class="col-md-8 bg-base col-lg-8 col-xl-9">

					<div class="ribbon ribbon-highlight">
						<ol class="breadcrumb ribbon-inner">
							<li><a href="<?php echo base_url(); ?>">Home</a></li>
							<li><?php echo $this->uri->segment('2'); ?> Stats</li>
						</ol>
					</div>
					
					<header class="page-header" style="border:0px">				
						<h2 class="page-title">Stats - <?php echo ucfirst($time_segment); ?></h2>
					</header>
                <div class="sortList" id="sort-list" style="margin-top:-40px">
                    <ul class="sort-links" style="margin-left:-45px;">
                        <li class="<?php if ($this->uri->segment('4') == 'today' || !$this->uri->segment(4)) { echo 'active'; } ?> "><a href="<?php echo base_url('u/'.$this->uri->segment('2').'/stats'); ?>" title="Popular songs today">Today</a>
                        </li>
                        <li class="<?php if ($this->uri->segment('4') == 'week') { echo 'active'; }?>"><a href="<?php echo base_url('u/'.$this->uri->segment('2').'/stats/week'); ?>" title="Popular songs this week">This Week</a>
                        </li>
                        <li class="<?php if ($this->uri->segment('4') == 'month') { echo 'active'; } ?> "><a href="<?php echo base_url('u/'.$this->uri->segment('2').'/stats/month'); ?>" title="Popular songs this month">This Month</a>
                        </li>
                        <li class="<?php if ($this->uri->segment('4') == 'year') { echo 'active'; } ?> "><a href="<?php echo base_url('u/'.$this->uri->segment('2').'/stats/year'); ?>" title="Popular songs this year">This Year</a>
                        </li>
                        </li>
                    </ul>
                </div>

					<article class="entry style-single type-post">
						<div class="entry-content">
							<div class="col-md-3">
								<span>Plays</span>
								<span class="stats-main-stat"><h3>32</h3></span>
							</div>
							<div class="col-md-3">
								<span>Downloads</span>
								<span class="stats-main-stat"><h3>300</h3></span>
							</div>
							<div class="col-md-3">
								<span>Favorites</span>
								<span class="stats-main-stat"><h3>62</h3></span>
							</div>
							<div class="col-md-3">
								<span>Votes</span>
								<span class="stats-main-stat"><h3>24969</h3></span>
							</div>
						</div>
					</article>
					<article class="entry style-single type-post">
						<div class="entry-content">
							<div class="col-md-3">
								<span class="stats-main-title">Most Played</span>
							<ul class="stats-most-list">
								<li><a href=""><img src="http://phpsound.dev/thumb.php?src=269141897_30639771_607629560.jpg&amp;t=m&amp;w=50&amp;h=50"> Some Long sports title shit here</a></li>
								<li><a href=""><img src="http://phpsound.dev/thumb.php?src=269141897_30639771_607629560.jpg&amp;t=m&amp;w=50&amp;h=50"> Some Long sports title shit here</a></li>
								<li><a href=""><img src="http://phpsound.dev/thumb.php?src=269141897_30639771_607629560.jpg&amp;t=m&amp;w=50&amp;h=50"> Some Long sports title shit here</a></li>
								<li><a href=""><img src="http://phpsound.dev/thumb.php?src=269141897_30639771_607629560.jpg&amp;t=m&amp;w=50&amp;h=50"> Some Long sports title shit here</a></li>
								<li><a href=""><img src="http://phpsound.dev/thumb.php?src=269141897_30639771_607629560.jpg&amp;t=m&amp;w=50&amp;h=50"> Some Long sports title shit here</a></li>
							</ul>
							</div>
							<div class="col-md-3">
								<span class="stats-main-title">Most Downloaded</span>
							<ul class="stats-most-list">
								<li><img src="http://phpsound.dev/thumb.php?src=269141897_30639771_607629560.jpg&amp;t=m&amp;w=50&amp;h=50"> Some Long sports title shit here</li>
								<li><img src="http://phpsound.dev/thumb.php?src=269141897_30639771_607629560.jpg&amp;t=m&amp;w=50&amp;h=50"> Some Long sports title shit here</li>
								<li><img src="http://phpsound.dev/thumb.php?src=269141897_30639771_607629560.jpg&amp;t=m&amp;w=50&amp;h=50"> Some Long sports title shit here</li>
								<li><img src="http://phpsound.dev/thumb.php?src=269141897_30639771_607629560.jpg&amp;t=m&amp;w=50&amp;h=50"> Some Long sports title shit here</li>
								<li><img src="http://phpsound.dev/thumb.php?src=269141897_30639771_607629560.jpg&amp;t=m&amp;w=50&amp;h=50"> Some Long sports title shit here</li>
							</ul>
							</div>
							<div class="col-md-3">
								<span class="stats-main-title">Most Favorited</span>
							<ul class="stats-most-list">
								<li><img src="http://phpsound.dev/thumb.php?src=269141897_30639771_607629560.jpg&amp;t=m&amp;w=50&amp;h=50"> Some Long sports title shit here</li>
								<li><img src="http://phpsound.dev/thumb.php?src=269141897_30639771_607629560.jpg&amp;t=m&amp;w=50&amp;h=50"> Some Long sports title shit here</li>
								<li><img src="http://phpsound.dev/thumb.php?src=269141897_30639771_607629560.jpg&amp;t=m&amp;w=50&amp;h=50"> Some Long sports title shit here</li>
								<li><img src="http://phpsound.dev/thumb.php?src=269141897_30639771_607629560.jpg&amp;t=m&amp;w=50&amp;h=50"> Some Long sports title shit here</li>
								<li><img src="http://phpsound.dev/thumb.php?src=269141897_30639771_607629560.jpg&amp;t=m&amp;w=50&amp;h=50"> Some Long sports title shit here</li>
							</ul>
							</div>
							<div class="col-md-3">
								<span class="stats-main-title">Most Voted</span>
							<ul class="stats-most-list">
								<li><img src="http://phpsound.dev/thumb.php?src=269141897_30639771_607629560.jpg&amp;t=m&amp;w=50&amp;h=50"> Some Long sports title shit here</li>
								<li><img src="http://phpsound.dev/thumb.php?src=269141897_30639771_607629560.jpg&amp;t=m&amp;w=50&amp;h=50"> Some Long sports title shit here</li>
								<li><img src="http://phpsound.dev/thumb.php?src=269141897_30639771_607629560.jpg&amp;t=m&amp;w=50&amp;h=50"> Some Long sports title shit here</li>
								<li><img src="http://phpsound.dev/thumb.php?src=269141897_30639771_607629560.jpg&amp;t=m&amp;w=50&amp;h=50"> Some Long sports title shit here</li>
								<li><img src="http://phpsound.dev/thumb.php?src=269141897_30639771_607629560.jpg&amp;t=m&amp;w=50&amp;h=50"> Some Long sports title shit here</li>
							</ul>
							</div>
						</div>
					</article>

				</div><!--/.col-md-8.col-lg-8.col-xl-9-->