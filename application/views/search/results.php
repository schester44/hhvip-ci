	<div class="section row entries">
	    <div class="entry col-xs-12 col-sm-12"></div>
	</div>
<div class="section row entries topPlayerBoard">
	    <div class="entry col-xs-12 col-sm-12">
	   <h1 class="entry-title" style="text-align:center"> <?php echo $total_rows; ?> results matching "<?php echo $search_title; ?>"</h1>
			<form action="<?php echo base_url('search') ?>" method="get">
			<div class="form-wrapper cf">
			<input type="text" id="q" name="q" value="<?php echo $searchPar; ?>" onFocus="this.select()">
			<button type="submit" style="font-size:14px">Search <span class="glyphicon glyphicon-search"></span></button>
			</div>
			<div class="sort-results-wrapper" style="text-align:center;padding-top:10px">
				<div class="sort-results" style="font-weight:bold;display:inline">Sort Results:</div>	
				<div style="display:inline" class="search-sort">
					
					<select name="sort">
						<option value="latest" <?php if ($this->input->get('sort') == 'latest' || $this->input->get('sort') == '') { echo 'selected'; } ?>>Most Recent</option>
						<option value="popular" <?php if ($this->input->get('sort') == 'popular') { echo 'selected'; } ?>>Most Popular</option>
						<option value="trending" <?php if ($this->input->get('sort') == 'trending') { echo 'selected'; } ?>>Most Relevent</option>
					</select>
					<select name="only">
						<option value="" <?php if ($this->input->get('only') == '') { echo 'selected'; } ?>>All Results</option>
						<option value="artist" <?php if ($this->input->get('only') == 'artist') { echo 'selected'; } ?>>Artist Only</option>
					</select>

				</div>		

			</div>
			</form>
	    </div>
</div>

<div id="content" class="content section row">
	<div class="col-md-8 bg-base col-lg-8 col-xl-9">
		<div class="ribbon ribbon-highlight">
			<ol class="breadcrumb ribbon-inner">
				<li><a href="<?php echo base_url(); ?>">Home</a></li>
				<li>Search Results</li>
			</ol>
		</div>
		<div class="entry-content">
  			<h3><?php echo $noResults; ?></h2>

		<?php $this->load->view('modules/promoted_songs'); ?>	
<?php foreach ($results as $key => $song): ?>
        <?php 
            $songArtist = htmlspecialchars($song->song_artist, ENT_QUOTES);
            $songTitle = htmlspecialchars($song->song_title, ENT_QUOTES);
            $songDesc  = htmlspecialchars($song->song_description, ENT_QUOTES);
            $songFeaturing = htmlspecialchars($song->featuring, ENT_QUOTES);
            $songProducer = htmlspecialchars($song->song_producer, ENT_QUOTES);

            $featuring       = (!empty($song->featuring) ? ' Feat. ' . $songFeaturing : NULL);
            $stream_download = ($song->can_download == 'yes' ? 'Stream/Download' : 'Stream Only');
        ?>
        <article class="entry style-media media type-post ">
            <div class="vote-container vote-container-list pull-left" id="vote_container_<?php echo $song->song_id;?>">
                <div id="already_voted_<?php echo $song->song_id; ?>" class="already_voted">Already Voted!</div>
                <div id="text_vote_up_<?php echo $song->song_id; ?>" class="text_vote_up_<?php echo $song->song_id; ?> vote-button vote-button-top upvote vote_up_<?php echo $song->song_id; ?>">
                    <a href="" onclick="vote(<?php echo $song->song_id; ?>, 10); return false;" title="<?php echo $this->lang->line('vote_dope_desc'); ?>"><?php echo $this->lang->line('vote_dope'); ?></a>
                </div>
                <div id="already_voted_down_<?php echo $song->song_id; ?>" class="already_voted">Already Voted!</div>
                <div id="text_vote_down_<?php echo $song->song_id; ?>" class="text_vote_down_<?php echo $song->song_id; ?> vote-button vote-button-bottom downvote vote_down_<?php echo $song->song_id; ?>">
                    <a href="#" onclick="vote(<?php echo $song->song_id; ?>, -10); return false;" title="<?php echo $this->lang->line('vote_nope_desc'); ?>"><?php echo $this->lang->line('vote_nope'); ?></a>
                </div>
            </div>

            <figure class="pull-left list-art">
                <a href="<?php echo base_url('song/' . $song->username . '/' . $song->song_url) ?>" rel="bookmark">
                <img src="<?php echo song_img($song->username, $song->song_url, $song->song_image, 150); ?>" data-src="<?php echo song_img($song->username, $song->song_url, $song->song_image, 150); ?>" alt="Listen to <?php echo $songTitle . ' by ' . $songArtist; ?>"/>
                    <noscript>
                        <img src="<?php echo song_img($song->username, $song->song_url, $song->song_image, 150); ?>"  alt="Listen to <?php echo $songTitle . ' by ' . $songArtist; ?>"/>
                    </noscript>
                </a>

            </figure>
            <header class="entry-header">
                <h3 class="list-title">
                <div id="votes_<?php echo $song->song_id; ?>" class="style-review-score list-review-score" style="background-color:<?php echo $this->voting->hotness_color($song->upvotes, $song->downvotes); ?>">
                    <?php echo $this->voting->vote_sum($song->upvotes, $song->downvotes); ?>
                </div>
                    <a href="<?php echo base_url('song/' . $song->username . '/' . $song->song_url) ?>" rel="bookmark">
                        <span class="list-artist">
                            <?php echo $songArtist; ?>
                        </span>
                       <?php echo $songTitle; ?>
                       <?php if ($songProducer): ?>
                            (Prod. by <?php echo $songProducer; ?>)
                        <?php endif ?> 
                        <span class="list-featuring">
                            <?php echo $featuring; ?>
                        </span>
                    </a>
                </h3>
                <div class="entry-song-detail">
                  <span style="font-weight:bold">Artist: </span>
                    <a href="<?php echo base_url('search?q='.str_replace(' ', '+', $songArtist)); ?>" title="More songs by <?php echo $songArtist ?>"><?php echo $songArtist; ?></a> |
                     Uploaded <?php echo time_ago($song->published_date) ?> by  <a href="<?php echo base_url('u/'.$song->username); ?>" title="View <?php echo $song->username ?>'s Profile" rel="nofollow"><?php echo $song->username; ?></a> 
                     
                     <?php if ($this->ion_auth->in_group('verified', $song->user_id)): ?>
                        <div class="verified-small">
                            <span class="glyphicon glyphicon-ok" style="padding-left:2px;color:white"></span>
                        </div>
                     <?php endif ?> 
            <?php if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()): ?>
            <div class="entry-song-detail">

                <?php if ($song->featured != 'yes'): ?>
                    <a href="<?php echo base_url('manage/song/feature/' . $song->song_id . '/feature') ?>" class="" style="font-weight:bold;color:green">Feature</a> | 
                <?php else: ?>
                    <a href="<?php echo base_url('manage/song/feature/' . $song->song_id . '/unfeature'); ?>" class="" style="font-weight:bold;color:green">Un-Feature</a> | 
                <?php endif ?>


                <?php if ($song->promoted != 'yes'): ?>
                    <a href="<?php echo base_url('manage/song/promote/' . $song->song_id . '/promote'); ?>" class="" style="font-weight:bold;color:green">Sponsor</a> | 
                <?php else: ?>
                    <a href="<?php echo base_url('manage/song/promote/' . $song->song_id . '/unpromote'); ?>" class="" style="font-weight:bold;color:green">Un-Sponsor</a> | 
                <?php endif ?>
                    <a href="<?php echo base_url('manage/song/remove/' . $song->song_id . '/copyright'); ?>" style="font-weight:bold;color:red">Copyright</a>| 
                    <a href="#" id="<?php echo $song->song_id; ?>-boost" class="boostVotes" title="Boost Votes" style="font-weight:bold;color:orange">Boost</a>
            </div>
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
            <?php endif ?>
        </header>
    </article>
    <hr>
<?php endforeach ?>

<?php if (!empty($results)): ?>
	<div class="alert alert-success" style="text-align:center;margin-top:10px">Hey, looks like you're searching for <?php echo $search_title; ?>. How about a <?php echo $search_title; ?> playlist? <a href="<?php echo base_url('playlist/artist/'. $search_title_url); ?>" class="btn btn-primary">Listen Now</a></div>
<?php endif ?>
			<div class="row" style="text-align:center;padding-top:10px">
				<?php echo $result_nums; ?>		
			</div>
		</div>


		<div id="pagination" class="row" style="text-align:center">
			<?php echo $pagination; ?>
		</div>
		<hr>			
<?php $this->load->view('modules/ads/banner'); ?>
		<br />
	</div><!--/.col-md-8.col-lg-8.col-xl-9-->