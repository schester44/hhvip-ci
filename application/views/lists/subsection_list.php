<?php if ($subSection_list): ?>
<?php foreach ($subSection_list as $key => $var): ?>	
<?php 
	$featuring = (!empty($var->featuring)) ? '(Feat. ' . $var->featuring . ')' : NULL;
	$stream_download = ($var->can_download === 'yes') ? 'Stream/Download' : 'Stream Only';
 ?>
<article class="entry style-media media type-post">
     <div class="vote-container vote-container-list pull-left" id="vote_container_<?php echo $var->song_id;?>">
     	<div id="already_voted_<?php echo $var->song_id; ?>" class="already_voted">Already Voted!</div>
     	<div id="text_vote_up_<?php echo $var->song_id; ?>" class="vote-button vote-button-top upvote"><a href="" onclick="vote(<?php echo $var->song_id; ?>, 10); return false;" title="<?php echo $this->lang->line('vote_dope_desc'); ?>"><?php echo $this->lang->line('vote_dope'); ?></a></div>
     	<div id="already_voted_down_<?php echo $var->song_id; ?>" class="already_voted">Already Voted!</div>
     	<div id="text_vote_down_<?php echo $var->song_id; ?>" class="vote-button vote-button-bottom downvote"><a href="#" onclick="vote(<?php echo $var->song_id; ?>, -10); return false;" title="<?php echo $this->lang->line('vote_nope_desc'); ?>"><?php echo $this->lang->line('vote_nope'); ?></a></div>
     </div>
        
	<figure class="media-object pull-left list-image" style="height:65px">
		<div id="votes_<?php echo $var->song_id; ?>" class="style-review-score">
			<?php echo $this->voting->vote_sum($var->upvotes, $var->downvotes); ?>
		</div>
        <img src="<?php echo song_img($var->username, $var->song_url, $var->song_image, 64); ?>" width="54px" height="54px"> 
	</figure>							
	<header class="entry-header">
		<h3 class="list-title blck"><a href="<?php echo base_url('song/' . $var->username . '/' . $var->song_url) ?>" rel="bookmark"><?php echo $var->song_title; ?><br /><span class="orange"><?php echo $var->song_artist; ?></span>  <span class="gray"><?php echo $var->featuring; ?></span></a></h3>
		<div class="entry-song-detail">
		<strong>Uploader:</strong> <a href="<?php echo base_url('u/'.$var->username); ?>" title="View <?php echo $var->username; ?>'s Profile" rel="nofollow"><?php echo $var->username; ?></a> | <strong>Released:</strong> <?php echo time_ago($var->published_date) ?> | <strong>Type:</strong> <?php echo $stream_download; ?>
		</div>
	</header>
</article>
<?php endforeach ?>
<?php endif ?>