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
							<li>Song Not Found</li>
						</ol>
					</div>
					
					<header class="page-header">
					<h1 class="page-title">Page Not Found</h1>
					</header>
					<article class="entry style-single type-post">

						<div class="entry-content">
	<h4 style="text-align:center">
		We're sorry, but the song you are looking for has either been removed or has never existed. <br />Listen to one of the dope songs below or visit our <a href="<?php echo base_url('songs/popular') ?>" title="popular songs this week">popular</a> or <a href="<?php echo base_url('songs/latest') ?>" title="latest songs">latest songs charts</a>.
	</h4>						
						</div>

						<div class="entry-content">
							<?php $this->load->view('lists/subsection_bigav'); ?>
						</div>
					</article>
					

				</div><!--/.col-md-8.col-lg-8.col-xl-9-->