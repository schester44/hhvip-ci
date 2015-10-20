		<div class="section row entries">
		</div>
			<div id="content" class="content section row">
				<div class="col-md-8 bg-base col-lg-8 col-xl-9">
					<div class="ribbon ribbon-highlight">
						<ol class="breadcrumb ribbon-inner">
							<li><a href="<?php echo base_url(); ?>">Home</a></li>
							<li><a href="<?php echo base_url('videos'); ?>">Videos</a></li>
							<li><?php echo htmlspecialchars($video->video_title, ENT_QUOTES); ?></li>
						</ol>
					</div>
					<header class="page-header" style="min-height:80px">
                    <?php if ($this->ion_auth->is_admin()): ?>
                    <span class="pull-right"><a href="<?php echo base_url('videos/edit/'.$video->id) ?>" class="btn btn-warning">Edit</a> <a href="<?php echo base_url('videos/delete/'.$video->id) ?>" class="btn btn-danger">Delete</a></span>
                        
                    <?php endif ?>
                    <div class="pull-left" style="padding:8px;"><img src="<?php echo video_img($video->username, $video->video_img, 150); ?>" width="64px" height="64px"></div>
                        <h1 class="page-song-title bebas" style="padding-top:5px"><?php echo htmlspecialchars($video->video_title, ENT_QUOTES); ?></h1>
                    </header>
					<article class="entry style-single type-post">
						<div class="entry-content">

<?php if ($video->status !== 'published') {?>
<h1 class="page-song-title" style="text-align:center">Video Pending Approval. Currently Being Reviewed.</h1>
<?php } else { ?>
    							<div class="row">
						<?php if ($video->video_source === 'youtube') { ?>
			<iframe width="100%" height="390" src="//www.youtube.com/embed/<?php echo $video->video_id; ?>?showinfo=0&autohide=1" frameborder="0" allowfullscreen></iframe>
					<?php } elseif($video->video_source === 'vimeo') { ?>
			<iframe width="100%" height="390" src="//player.vimeo.com/video/<?php echo $video->video_id; ?>" frameborder="0" allowfullscreen></iframe>
						<?php } ?>
       						 </div>
       						 <?php echo $video->video_description; ?>
						</div>
<?php } //end moderation ?>

					</article>
					
<!--comments -->
        <div class="panel panel-default widget">
            <div class="panel-heading">
                <h4 class="song-subsections-heading comments-heading">
                    Latest Comments</h4>
            </div>

            <div id="disqus_thread"></div>
            <script type="text/javascript">
                /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
                var disqus_shortname = 'hhvip2'; // required: replace example with your forum shortname

                /* * * DON'T EDIT BELOW THIS LINE * * */
                (function () {
                    var dsq = document.createElement('script');
                    dsq.type = 'text/javascript';
                    dsq.async = true;
                    dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                })();
            </script>
            <noscript>Please enable JavaScript to view the comments.</noscript>
        </div>

        
				</div><!--/.col-md-8.col-lg-8.col-xl-9-->

<script type="text/javascript">
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        var disqus_shortname = 'hhvip2'; // required: replace example with your forum shortname

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function () {
            var s = document.createElement('script');
            s.async = true;
            s.type = 'text/javascript';
            s.src = '//' + disqus_shortname + '.disqus.com/count.js';
            (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
        }());
</script>