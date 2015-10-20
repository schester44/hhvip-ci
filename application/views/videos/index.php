		<div class="section row entries">
		</div>
			<div id="content" class="content section row">
				<div class="col-md-8 bg-base col-lg-8 col-xl-9">
					<div class="ribbon ribbon-highlight">
						<ol class="breadcrumb ribbon-inner">
							<li><a href="<?php echo base_url(); ?>">Home</a></li>
							<li>Latest Videos</li>
						</ol>
					</div>
					<?php echo $this->session->flashdata('videoError'); ?>
					<?php if (empty($this->uri->segment('2'))) { ?>
					
					<header class="page-header">
						<h3 class="page-title">Latest Videos</h3>
					</header>
					<? } ?>

					<article class="entry style-single type-post">
						<div class="entry-content">
						<div class="row">

						<?php $counter = 0; ?>
						<?php foreach ($videos as $key => $video): ?>
							<?php 
							$link = base_url('videos/'.$video->username.'/'.$video->video_url);							$title = htmlspecialchars($video->video_title, ENT_QUOTES);
							$description = htmlspecialchars($video->video_description, ENT_QUOTES);
							 ?>

		<div class="col-sm-6">
           	<a href="<?php echo $link; ?>" class="videoBig" title="<?php echo $title; ?>">
		          <div class="play-button"></div>
		          <div class="caption"><h1><?php echo $title; ?></h1></div>
                <img src="<?php echo video_img($video->username, $video->video_img, 300); ?>" alt="<?php echo $title; ?>">
            <div style="margin-top:-20px;margin-bottom:15px"><?php echo $title; ?></div>
                
            </a>
        </div>
        <?php if(++$counter % 2 === 0) {
                  echo "</div><div class='row'>";
             } 

             ?>
						<?php endforeach ?>

							</div>
						</div>
        <div id="pagination" class="row container" style="text-align:center">
            <?php echo $pagination; ?>
        </div>
					</article>

				</div><!--/.col-md-8.col-lg-8.col-xl-9-->