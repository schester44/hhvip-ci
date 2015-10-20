 <script type="text/javascript">
$(document).ready(function(){
	$('.news-ticker').fadeIn(2000);
	$('.news-ticker').easyTicker({
	direction: 'up',
	easing: 'swing',
	speed: 'fast',
	interval: 2500,
	height: 'auto',
	visible: 0,
	mousePause: 1,
	controls: {
		up: '',
		down: '',
		toggle: '',
		playText: 'Play',
		stopText: 'Stop'
	}
	});
});
</script>
<div id="content" class="content section row">
	<div class="col-md-8 bg-base col-lg-8 col-xl-9">
		<div class="content section row">
			<?php $this->load->view('modules/searchBar'); ?>
		</div>
	
	<?php if (isset($latest_news) && !empty($latest_news) && $latest_news_count > 1): ?>
		<div class="ribbon ribbon-highlight">
			<ol class="breadcrumb ribbon-inner">
				<li>LATEST NEWS</li>
			</ol>
		</div>
		<div class="news-ticker">
			<ul>
				<?php foreach ($latest_news as $key => $news): ?>
					<li class="hide-overflow"><a href="<?php echo base_url('b/' . $news->category_title . '/' . $news->url); ?>" title="Read about <?php echo $news->title; ?>"><img src="<?php echo blog_featured_img($news->username, $news->url, $news->featured_image, 64); ?>">
						<span style="color:red"><?php echo ucfirst($news->category_title); ?></span> |  <?php echo strtoupper($news->title); ?></a></li>
				<?php endforeach ?>
			</ul>
		</div>

		<p><a href="<?php echo base_url('news'); ?>" class="btn btn-warning" title="View Latest News" style="width:100%;font-weight:bold">View Latest News</a></p>					
	<?php endif ?>

	<?php foreach ($lists as $key => $content): ?>
		<div class="ribbon ribbon-highlight" style="margin-bottom:-10px">
			<ol class="breadcrumb ribbon-inner">
				<li><?php echo ucfirst($content['title']); ?> Songs</li>
			</ol>
		</div>
		<?php if ($content['title'] === 'trending'): ?>
			<?php $this->load->view('modules/promoted_songs'); ?>
		<?php endif ?>

		<?php foreach ($content['entry'] as $key => $song): ?>	 
			<?php $featuring = (!empty($song->featuring) ? 'Feat. ' . $song->featuring : NULL);?>

			<article class="entry style-media media type-post">
				<div class="vote-container vote-container-list pull-left" id="vote_container_<?php echo $song->song_id;?>">
	                <div id="already_voted_<?php echo $song->song_id; ?>" class="already_voted">Already Voted!</div>
	                <div id="text_vote_up_<?php echo $song->song_id; ?>" class="text_vote_up_<?php echo $song->song_id; ?> vote-button vote-button-top upvote"><a href="" onclick="vote(<?php echo $song->song_id; ?>, 10); return false;" title="<?php echo $this->lang->line('vote_dope_desc'); ?>"><?php echo $this->lang->line('vote_dope'); ?></a></div>
	                <div id="already_voted_down_<?php echo $song->song_id; ?>" class="already_voted">Already Voted!</div>
	                <div id="text_vote_down_<?php echo $song->song_id; ?>" class="text_vote_down_<?php echo $song->song_id; ?> vote-button vote-button-bottom downvote"><a href="#" onclick="vote(<?php echo $song->song_id; ?>, -10); return false;" title="<?php echo $this->lang->line('vote_nope_desc'); ?>"><?php echo $this->lang->line('vote_nope'); ?></a></div>
	            </div>
				<figure class="pull-left list-art">
					<a href="<?php echo base_url('song/'. $song->username . '/' . $song->song_url) ?>" rel="bookmark">
						<img src="<?php echo song_img($song->username, $song->song_url, $song->song_image, 150); ?>" data-src-retina="<?php echo song_img($song->username, $song->song_url, $song->song_image, 150); ?>" alt="Listen to <?php echo $song->song_artist . ' - ' . $song->song_title; ?>">
					</a>
				</figure>
				<header class="entry-header">
	                <h3 class="list-title">
	                	<div id="votes_<?php echo $song->song_id; ?>" class="style-review-score list-review-score" style="background-color:<?php echo $this->voting->hotness_color($song->upvotes, $song->downvotes); ?>">
							<?php echo $this->voting->vote_sum($song->upvotes, $song->downvotes); ?>
						</div>
	                    <a href="<?php echo base_url('song/' . $song->username . '/' . $song->song_url) ?>" rel="bookmark">
	                        <span class="list-artist">
	                            <?php echo $song->song_artist; ?>
	                        </span>
	                       <?php echo $song->song_title; ?>
	                       <span class="list-featuring hide-overflow">
                            <?php echo $featuring; ?>
                        </span>
	                    </a>
	                </h3>
	                
	                <div class="entry-song-detail">
	                   Uploaded <?php echo time_ago($song->published_date) ?> by  <a href="<?php echo base_url('u/'.$song->username); ?>" title="View <?php echo $song->username ?>'s Profile" rel="nofollow"><?php echo $song->username; ?></a> 
	                     | <strong>Artist:</strong> 
	                    <a href="<?php echo base_url('search?q='.str_replace(' ', '+', $song->song_artist)); ?>" title="More songs by <?php echo $song->song_artist; ?>"><?php echo $song->song_artist; ?></a>
	               
	        <?php if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()): ?>
	           <div class="entry-song-detail">
                <?php if ($song->featured != 'yes'): ?>
                    <a href="<?php echo base_url('manage/song/feature/' . $song->song_id . '/feature') ?>" style="font-weight:bold;color:green">Feature</a> | 
                <?php else: ?>
                    <a href="<?php echo base_url('manage/song/feature/' . $song->song_id . '/unfeature'); ?>" style="font-weight:bold;color:green">Un-Feature</a> | 
                <?php endif ?>


                <?php if ($song->promoted != 'yes'): ?>
                    <a href="<?php echo base_url('manage/song/promote/' . $song->song_id . '/promote'); ?>" style="font-weight:bold;color:green">Sponsor</a>
                <?php else: ?>
                    <a href="<?php echo base_url('manage/song/promote/' . $song->song_id . '/unpromote'); ?>" style="font-weight:bold;color:green">Un-Sponsor</a>
                <?php endif ?>        
                    <select name="only" id="boost-<?php echo $song->song_id; ?>" class="boostDrop">
                        <option value="" selected>Boost Votes</option>
                        <option value="1">by 01</option>
                        <option value="2">by 02</option>
                        <option value="3">by 03</option>
                        <option value="4">by 04</option>
                        <option value="5">by 05</option>
                        <option value="6">by 06</option>
                        <option value="7">by 07</option>
                        <option value="8">by 08</option>
                        <option value="9">by 09</option>
                        <option value="10">by 10</option>
                    </select>
                    <select name="only" id="dump-<?php echo $song->song_id; ?>" class="dumpDrop">
                        <option value="" selected>Dump Votes</option>
                        <option value="1">by 01</option>
                        <option value="2">by 02</option>
                        <option value="3">by 03</option>
                        <option value="4">by 04</option>
                        <option value="5">by 05</option>
                        <option value="6">by 06</option>
                        <option value="7">by 07</option>
                        <option value="8">by 08</option>
                        <option value="9">by 09</option>
                        <option value="10">by 10</option>
                    </select>

				</div>
                    
            <?php endif ?>

	                </div>
	            </header>
			</article>

		<?php endforeach ?>
		<?php if ($content['title'] != 'featured'): ?>
			<p><a href="<?php echo base_url('songs/' . $content['title']); ?>" class="btn btn-warning" title="View More <?php echo $content['title']; ?> Songs" style="margin-top:10px;width:100%;font-weight:bold">View More <?php echo ucfirst($content['title']); ?> Songs</a></p>		
		<?php endif ?>
	<?php endforeach ?>
	</div><!--/.col-md-8.col-lg-8.col-xl-9-->