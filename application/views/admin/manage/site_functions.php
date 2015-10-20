							<script type="text/javascript">
							$(document).ready(function(){
								$('#dbMigrateBody').hide();

							$('#dbMigrate').on('click', function(){
								$('#dbMigrateBody').toggle();
								});
							});
							</script>

		<div id="content" class="content section row">

				<div class="col-md-8 bg-base col-lg-8 col-xl-9">

					<div class="ribbon ribbon-highlight">
						<ol class="breadcrumb ribbon-inner">
							<li><a href="<?php echo base_url(); ?>">Home</a></li>
							<li>Site Functions</li>
						</ol>
					</div>
					
					<header class="page-header">
						
					<h2 class="page-title">Site Functions</h2>

					</header>

					<article class="entry style-single type-post">

						<div class="entry-content">
							<p class="lead">
<div id="infoMessage"><?php echo $this->session->flashdata('message');;?></div>
							</p>
						<h1>DON'T FUCK AROUND IN HERE IF YOU DON'T KNOW WHAT YOUR DOING. MOST FUNCTIONS IN HERE SHOULD NEVER BE TOUCHED.</h1>
<!-- hidden for the sake of the site
						<a href="<?php echo base_url('backend/clear_cache/song/blinger'); ?> " class="btn btn-danger">Delete Incomplete/Remved Songs (Manual Cron)</a>
						<button id="dbMigrate" class="btn btn-danger">DB Migrate / Install Options</button>
-->
						</div>

						<div class="entry-content" id="dbMigrateBody">
							<h1>Database Migration / Install Options</h1>
							<p>
								Runs songs columns through filters to remove encoding and other stuff that was used in the previous setup
						<br /><a href="<?php echo base_url('backend/update_song_migrate'); ?> " class="btn btn-danger">Clean Song Names</a>
							</p>
							<p>
							Maps mp3's located in temp_audio to rows in the songs db. Once mapped, it will create the file directories and move the temp mp3 to its permantent location in audio_uploads/username
						<br /><a href="<?php echo base_url('backend/move_mp3'); ?> " class="btn btn-danger">Move Temp mp3's</a>
							</p>
							<p>
							Maps the category_id of hotaru posts and downloads the image based upon the filename. sets up the directory structure for the asset_uploads/username/song_url folder and resizes the images for use.
							<strong>i think this may only work if the old/existing files are still present on the server</strong>	
						<br /><a href="<?php echo base_url('backend/setup_song_images'); ?> " class="btn btn-danger">Move Song Images</a>
							</p>
						</div>

					</article>
					

				</div><!--/.col-md-8.col-lg-8.col-xl-9-->

				<?php $this->load->view('admin/sidebar') ?>