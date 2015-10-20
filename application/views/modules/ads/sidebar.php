<?php 
$ads = array(
	array(
		'id'=>'2',
		'img'=> $this->config->item('secure_cdn') . 'resources/img/network/kf-hats.gif',
		'link'=>'http://kushfriendly.com/?utm_source=hhvip-sidebar&utm_medium=box-banner&utm_campaign=hhvip-sidebar-kf-hats',
		'title'=>'Shop Kush Friendly',
		'tracking'=>'kf-hats-hhvip-sidebar',
		'dimensions'=>array('width'=>'250','height'=>'250')
		),
	array(
		'id'=>'1',
		'img'=> $this->config->item('secure_cdn') . 'resources/img/network/kf-summer15.gif',
		'link'=>'http://kushfriendly.com/?utm_source=hhvip-sidebar&utm_medium=box-banner&utm_campaign=summer15-gif',
		'title'=>'Shop Kush Friendly',
		'tracking'=>'sidebar-summer15-gif',
		'dimensions'=>array('width'=>'300','height'=>'300')
		)
	);

shuffle($ads);
$ad = $ads[0];

?>
<script type="text/javascript">
	$(document).ready(function(){
		if($('#sidebarCreative').length) {
		var gSBCRT = document.getElementById('sidebarCreative');
        gSBCRT.addEventListener('click', function(){
            ga('send', 'event', 'Ad', 'Click', '<?php echo $ad["tracking"]; ?>', {'page': '<?php echo current_url(); ?>','advert': '<?php echo $ad["id"] ?> - <?php echo $ad["tracking"] ?>'});
        }, false);
		}
	});
</script>

				<aside class="widget hidden-sm hidden-sm sponsor">
						<div class="entries row" style="text-align:center">
							<article class="type-post style-media-list media col-sm-6 col-md-12">
								<a id="sidebarCreative" href="<?php echo $ad['link']; ?>" title="<?php echo $ad['title']; ?>"><img src="<?php echo $ad['img']; ?>" width="<?php echo $ad['dimensions']['width'] ?>" height="<?php echo $ad['dimensions']['height']; ?>" alt="Shop Kush Friendly"></a>
							</article>

						</div>
				</aside>