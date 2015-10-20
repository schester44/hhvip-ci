<style type="text/css">
.mainTopPiece {
	text-align: center;
}
</style>
<div class="mainTopPiece">
	
<?php 

$ad1 = '<a href="http://kushfriendly.com" title="Kush Friendly Apparel" target="new"><img src="'.base_url('resources/img/network/kfeXo.gif').'" border="0" alt="Kush Friendly Apparel"></a>';
$ad2 = '<a href="http://kushfriendly.com" title="Kush Friendly Apparel" target="new"><img src="//static.hiphopvip.com/images/kfbeane.gif" border="0" alt="Kush Friendly Headwear"></a>';
$ad3 = '<a href="http://kushfriendly.com" title="Kush Friendly Apparel" target="new"><img src="'.base_url('resources/img/network/kfeXo.gif').'" border="0" alt=""></a>';

$ads = array($ad1,$ad2,$ad3);
shuffle($ads);
	if ($ads[0] == 'DISPLAYMESSAGEBAR') {
		$this->load->view('modules/ads/messageBar');
	} else {
		echo $ads[0];
	}

?>

</div>
