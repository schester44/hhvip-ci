		<div id="content" class="content section row">
				<div class="col-md-8 bg-base col-lg-8 col-xl-9">
					<div class="ribbon ribbon-highlight">
						<ol class="breadcrumb ribbon-inner">
							<li><a href="<?php echo base_url(); ?>">Home</a></li>
							<li><a href="<?php echo base_url('manage'); ?>">Manage Account</a></li>
							<li>Account Settings</li>
						</ol>
					</div>

					<div class="col-md-3 col-lg-3">
					<?php $this->load->view('user/sidebar'); ?>
					</div>

					<div class="col-md-8 col-lg-8">

						<header class="page-header">
						<div id="infoMessage" style="color:red; font-size:18px">
						<?php echo $message; ?></div>

					<h2 class="page-title">Account Settings</h2>

						</header>
							<article class="entry style-single type-post">
							<div class="entry-content">

							<?php echo form_open_multipart('auth/edit_account');?>
							<div class="row" style="margin:5px">
							<div class="input-group col-md-12 col-lg-12"><strong>Twitter:</strong>
							  <span class="input-group-addon">@</span>
							  <?php echo form_input($twitter_form); ?>
							</div>
							</div>

							<div class="row" style="margin:5px">
								<div class="input-group col-md-12 col-lg-12">
								<strong>Website:</strong>
								  <span class="input-group-addon">http://</span>
								  <?php echo form_input($website_form); ?>
								</div>
							</div>


							<div class="row" style="margin:5px">
								<div class="input-group col-md-12 col-lg-12">
								<strong>Location:</strong>
								<?php echo form_input($location_form); ?>
								</div>
							</div>

							<div class="row" style="padding-bottom: 5px;margin:5px;">
								<div class="col-md-12 col-lg-12"><strong>Bio:</strong> <span style="font-size:12px">(limit 500 characters)</span>
								<?php echo form_textarea($bio_form); ?>
								</div>
							</div>
							
							<div class="row" style="margin:5px">
								<div class="col-md-12 col-lg-12"><strong>Profile Picture</strong><br />
								<?php echo form_upload($profile_img_form); ?>
								</div>
							</div>

							<div class="row" style="margin:5px">
								<div class="col-md-12 col-lg-12">
									<img src="<?php echo user_img($this->ion_auth->user()->row()->username);?>" style="width:150px; height:150px">
								</div>
							</div>


				<a href="<?php echo base_url('auth') ?>" type="button" id="cancel-btn" class="btn btn-danger">Cancel</a>
				<input type="submit" name="submit" id="submit" value="Update" class="btn btn-danger">
				<?php echo form_close(); ?>
								</div>
							</article>
						</div><!-- /.col-md-8-->
				</div><!--/.col-md-8.col-lg-8.col-xl-9-->