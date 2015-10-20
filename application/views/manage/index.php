	<div class="section row entries">
	    <article class="entry col-xs-12 col-sm-12"></article>
	</div>

	<div id="content" class="content section row">

				<div class="col-md-8 bg-base col-lg-8 col-xl-9">

					<div class="ribbon ribbon-highlight">
						<ol class="breadcrumb ribbon-inner">
							<li><a href="<?php echo base_url(); ?>">Home</a></li>
								<li>Manage Account</li>
						</ol>
					</div>
					
					<div class="col-md-3 col-lg-3">
					<?php $this->load->view('user/sidebar'); ?>
					</div>

					<div class="col-md-9 col-lg-9">
						<div id="infoMessage"><?php echo $this->session->flashdata('message');;?></div>
							<article class="entry style-single type-post">

								<div class="entry-content">	

					</div>

							</article>


					</div>
					
					

				</div><!--/.col-md-8.col-lg-8.col-xl-9-->