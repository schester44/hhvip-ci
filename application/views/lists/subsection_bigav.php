<script type="text/javascript">
$(document).ready(function() {

	var winwidtthh = $(window).width();

	if (winwidtthh > 750) {
		$('.bigAv').css({'display':'inherit'});
	};
});


</script>

<?php if ($subSection_bigav): ?>
<article class="entry style-media media type-post bigAv">
				<?php foreach ($subSection_bigav as $var): ?>
			<div class="baUnit">
				<a href="<?php echo base_url('song/'.$var->username.'/'.$var->song_url); ?>" title="<?php echo 'Listen and download ' . $var->song_artist . ' - ' . $var->song_title; ?>">
				<span class="baPlay glyphicon glyphicon-play-circle"></span>
				<img src="<?php echo song_img($var->username, $var->song_url, $var->song_image, 150) ?>" width="125px" height="125px">			
				<div style="padding-top:5px;line-height:15px">
				<strong><?php echo $var->song_artist; ?></strong><br />
				<?php echo $var->song_title; ?>
				</div>
				</a>
			</div>
				<?php endforeach ?>
</article>
<?php endif ?>
