<?php if ($promoted): ?>

<?php 
    shuffle($promoted); 
	
    $promoted = $promoted[0];
	$promoted_artist 	= htmlspecialchars($promoted->song_artist);
	$promoted_title 	= htmlspecialchars($promoted->song_title);
	$promoted_featuring = htmlspecialchars($promoted->featuring);
	$promoted_description = htmlspecialchars($promoted->song_description);

	$featuring = ($promoted->featuring) ? 'Feat. ' . $promoted_featuring : NULL;

?>

<script src="//secure.hiphopvip.com/resources/vendor/Vibrant.js"></script>
<script type="text/javascript">
$(document).ready(function(){
   var viewport = {width : $(window).width(),height : $(window).height()};
   if (viewport.width < 768 ) {
    $('.promotedPostBackground').css({height: '90px'});
    $('.promotedPost').css({height: '90px'});
   };
});

    var img = document.createElement('img');
    img.setAttribute('src', '<?php echo promotedPostBg($promoted->username, $promoted->song_url, $promoted->song_image, 64); ?>')

    img.addEventListener('load', function() {
        var vibrant = new Vibrant(img);
        var swatches = vibrant.swatches();
        var primary;
        var secondary;

        for (swatch in swatches)
            if (swatches.hasOwnProperty(swatch) && swatches[swatch]) {
                var primary = swatches['Vibrant'].getHex();
                var secondary = swatches['DarkMuted'].getHex();
            };
    $('.promotedPostBackground').css({
        background: '-webkit-linear-gradient(left top, ' + primary + ' , ' + secondary + ')',
        background: '-o-linear-gradient(bottom right, ' + primary + ' , ' + secondary + ')',
        background: '-moz-linear-gradient(bottom right, ' + primary + ' , ' + secondary + ')',
        background: 'linear-gradient(to bottom right, ' + primary + ' , ' + secondary + ')'
    });
});
</script>

        <article class="entry style-media media type-post promotedPost">
                <div class="promotedPostBackground">
                </div>
    <div class="col-xs-12 col-lg-12">
            <div class="vote-container vote-container-list pull-left" id="vote_container_<?php echo $promoted->song_id;?>" style="margin-left:-15px;margin-right:5px">
                <div id="already_voted_<?php echo $promoted->song_id; ?>" class="already_voted">Already Voted!</div>
                <div id="text_vote_up_<?php echo $promoted->song_id; ?>" class="text_vote_up_<?php echo $promoted->song_id; ?> vote-button vote-button-top upvote">
                    <a href="" onclick="vote(<?php echo $promoted->song_id; ?>, 10); return false;" title="<?php echo $this->lang->line('vote_dope_desc'); ?>">
                        <?php echo $this->lang->line('vote_dope'); ?></a>
                </div>
                <div id="already_voted_down_<?php echo $promoted->song_id; ?>" class="already_voted">Already Voted!</div>
                <div id="text_vote_down_<?php echo $promoted->song_id; ?>" class="text_vote_down_<?php echo $promoted->song_id; ?> vote-button vote-button-bottom downvote">
                    <a href="#" onclick="vote(<?php echo $promoted->song_id; ?>, -10); return false;" title="<?php echo $this->lang->line('vote_nope_desc'); ?>">
                        <?php echo $this->lang->line('vote_nope'); ?></a>
                </div>
            </div>
            <span class="sponsored-song-text">RECOMMENDED</span>
            <figure class="pull-left list-image">
                <a href="<?php echo base_url('song/' . $promoted->username . '/' . $promoted->song_url) ?>" rel="bookmark">
                    <img src="<?php echo song_img($promoted->username, $promoted->song_url, $promoted->song_image, 150); ?>" data-src="<?php echo song_img($promoted->username, $promoted->song_url, $promoted->song_image, 150); ?>" alt="Listen to <?php echo $promoted_title . ' by ' . $promoted_artist; ?>"/>
                    <noscript>
                        <img src="<?php echo song_img($promoted->username, $promoted->song_url, $promoted->song_image, 150); ?>"/>
                    </noscript>
                </a>

            </figure>

                        <header class="entry-header">
                <h3 class="list-title promoted-title">
                    <a href="<?php echo base_url('song/' . $promoted->username . '/' . $promoted->song_url) ?>" rel="bookmark">
                        <span style="font-size:18px;display:block;font-weight:100;padding-bottom:3px">
                            <?php echo $promoted_artist; ?> <?php echo $featuring; ?> 
                        </span>
                       <?php echo $promoted_title; ?> <?php if (!empty($promoted->song_producer)) { echo '<span style="display:inline;font-size:18px">(Prod. ' . $promoted->song_producer . ')</span>'; } ?>
                       </span>
                    </a>
                </h3>
                
                <div class="promoted-song-detail">
                   <span style="font-weight:bold">Artist: </span> 
                    <a href="<?php echo base_url('search?q='.str_replace(' ', '+', $promoted_artist)); ?>" title="More songs by <?php echo $promoted_artist ?>" class="orange"><?php echo $promoted_artist; ?></a>
                   | Uploaded <?php echo time_ago($promoted->published_date) ?> by  <a href="<?php echo base_url('u/'.$promoted->username); ?>" title="View <?php echo $promoted->username ?>'s Profile" rel="nofollow" class="orange"><?php echo $promoted->username; ?></a> 
                    
                </div>
            </header>

    </div> <!-- ./end col-->

        </article>

<?php else: ?>
    <?php $this->load->view('modules/ads/banner'); ?>
<?php endif ?>