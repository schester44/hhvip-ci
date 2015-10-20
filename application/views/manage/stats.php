			<div id="content" class="content section row">

				<div class="col-md-8 bg-base col-lg-8 col-xl-9">

					<div class="ribbon ribbon-highlight">
						<ol class="breadcrumb ribbon-inner">
							<li><a href="<?php echo base_url(); ?>" title="Back to Home">Home</a></li>
							<li><a href="<?php echo base_url('manage'); ?>" title="Manage My Account">Manage Account</a></li>
							<li>Account Stats</li>
						</ol>
					</div>

					<div class="col-md-3 col-lg-3">
					<?php $this->load->view('user/sidebar'); ?>
					</div>

					<div class="col-md-8 col-lg-8">

						<header class="page-header">
						<div id="infoMessage"><?php echo $this->session->flashdata('message');;?></div>

					<h2 class="page-title">
							Account Stats
						</h2>
						</header>

<h3 style="color:red">This feature is currently disabled.</h3>
							<article class="entry style-single type-post">

								<div class="entry-content">
								</div>

							</article>

					</div>
					

				</div><!--/.col-md-8.col-lg-8.col-xl-9-->