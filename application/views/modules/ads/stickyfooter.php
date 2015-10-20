<style type="text/css">
	/* mobile ad unit sticky footer */
.mobileStickyUnit {
  position: absolute;
  bottom: 0;
  width: 100%;
  z-index: 100;
  text-align: center;
  display: none;
  padding-top: 3px;
  background-color: #000;
}
</style>

<script type="text/javascript">
$(document).ready(function(){
	var stickyFoot = $('#stickyFoot');

	if ($(window).width() < 768) {
		$('#stickyFoot').show();
	}

	function stickFooter() {
		var $cache = $('.stickyFooter'); 
        $cache.css({'position': 'fixed', 'bottom': '0','width':'100%'});
	}
	
	$(window).scroll(stickFooter);

	stickFooter();
});

</script>


<div class="mobileStickyUnit stickyFooter" id="stickyFoot">

<?php 
$ads = array(
	array(
		'id'=>'2',
		'img'=>'//secure.hiphopvip.com/resources/img/network/kftee-banner.gif',
		'link'=>'http://kushfriendly.com/?utm_source=hhvip&utm_medium=banner&utm_campaign=stickyfoot-high-kfetee-banner_hhvip',
		'title'=>'Shop Kush Friendly'
		),
	array(
		'id'=>'1',
		'img'=>'//secure.hiphopvip.com/resources/img/network/banner-summer15.gif',
		'link'=>'http://kushfriendly.com/?utm_source=hhvip&utm_medium=banner&utm_campaign=stickyfoot-banner-summer15_hhvip',
		'title'=>'Shop Kush Friendly'
		)
	);

shuffle($ads);
$ad = $ads[0];
?>
<a href="<?php echo $ad['link']; ?>" title="<?php echo $ad['title']; ?>"><img src="<?php echo $ad['img']; ?>" alt="<?php echo $ad['title']; ?>" border="0" height="65"></a>
</div>