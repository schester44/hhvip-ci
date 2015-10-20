			<div class="section row entries">

			</div>
						<div id="content" class="content section row">

				<div class="col-md-8 bg-base col-lg-8 col-xl-9">

					<div class="ribbon ribbon-highlight">
						<ol class="breadcrumb ribbon-inner">
							<li><a href="<?php echo base_url(); ?>">Home</a></li>
							<li>DMCA</li>
						</ol>
					</div>
					
					<header class="page-header">
					<h2 class="page-title">
							DMCA
						</h2>
					</header>
					<article class="entry style-single type-post">

						<div class="entry-content">

						<?php echo $this->session->flashdata('formErrors'); ?>
<p>hiphopVIP takes the abuse of our service seriously and will work with copyright holders to ensure that infringing material is removed from our service.</p><p> If you believe that a file that a user has uploaded to hiphopVIP infringes on your copyright then <strong>please use the form below to submit a request</strong>. Be sure to include your relationship to the owner of the copyrighted work, your full contact info, and the url of the song/album you are referring to. Direct email contact is <strong>dmca[at]hiphopvip.com</strong></p>

			<form action="<?php echo base_url('contact/index'); ?>" method="post">
              <fieldset>
                <div class="form-group">
                  <input class="form-control" placeholder="Your Name" name="name" id="name" type="text" value="">
              </div>
              <div class="form-group">
                <input class="form-control" placeholder="Your Email Address" name="email" id="email" type="text" value="">
              </div>
              <div class="form-group">
                <input class="form-control" placeholder="Company" name="company" id="company" type="text" value="">
              </div>
              <div class="form-group">
                <input class="form-control" placeholder="URL of infringing material" name="url" id="url" type="text" value="">
              </div>

              <div class="form-group">
                <textarea class="form-control" placeholder="Message" name="message" id="message" type="message" value=""></textarea>
              </div>

             <div class="form-group">
             Spam Prevention Question:
                <input class="form-control" placeholder="What color is a blue car?" name="spam_protection" id="spam_protection" type="text" value="">
              </div>

              <input class="btn btn-lg btn-success btn-block" type="submit" name="submit" value="Submit Form">
            </fieldset>
            </form>
					</article>
					

				</div><!--/.col-md-8.col-lg-8.col-xl-9-->