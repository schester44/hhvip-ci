
<article class="entry style-media media type-post bigAv">
		<?php if ($mixtapes_bigav): ?>
				<?php foreach ($mixtapes_bigav as $var): ?>
			<div style="width:19%;margin:auto;display:inline-block;overflow:hidden; min-height:200px; max-height:200px;">
				<a href="<?php echo $var->link; ?>" title="<?php echo 'Listen and download ' . $var->title; ?>">
				<img src="<?php echo $var->image; ?>" width="125px" height="125px">
				<div class="bigavText" style="text-align:center">
				<strong><?php echo $var->title; ?>
				</strong>
				</div>
				</a>
			</div>
				<?php endforeach ?>
		<?php endif ?>
</article>

