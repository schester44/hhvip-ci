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
							<li>Artist Not Found</li>
						</ol>
					</div>
					
					<header class="page-header">
						
					<h2 class="page-title">
							Artist Not Found.
						</h2>

					</header>

					<article class="entry style-single type-post">

						<div class="entry-content">
						<h4>Fail! Maybe the username is mispelled or no longer exists.</h4>
						<h3>LATEST SONGS ON THE NETWORK</h3>
						<?php 
        			$this->load->view('lists/subsection_bigav');
 ?>
							<p class="lead">
<div id="infoMessage"><?php echo $this->session->flashdata('message');;?></div>
								
							</p>
						

						</div>

					</article>
					

				</div><!--/.col-md-8.col-lg-8.col-xl-9-->