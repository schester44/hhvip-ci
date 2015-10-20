<?php 

if ($this->ion_auth->logged_in()) {
	$txt1 = array(
	'id'=>'0',
	'text'=>'Find More Songs By ' . $song->song_artist,
	'link'=>base_url('search?q=' . clean_name($song->song_artist)),
	'title'=>'Click Here',
	'icon'=>'glyphicon-headphones',
	'gTrack'=>'findbyartist-5-li'
	);
	$txt2 = array(
	'id'=>'1',
	'text'=>'Start A ' . $song->song_artist . ' Playlist',
	'link'=>base_url('playlist/artist/' . clean_name($song->song_artist)),
	'title'=>'Start Listening',
	'icon'=>'glyphicon-play',
	'gTrack'=>'startplaylist-5-li'
	);
$txt = array($txt1, $txt2);
shuffle($txt);
} else {
	$txt0 = array(
		'id'=>'2',
		'text'=>'Create a playlists!',
		'link'=>base_url('auth/create_account'),
		'title'=>'Sign Up',
		'icon'=>'glyphicon-headphones',
		'gTrack'=>'create-a-playlist'
		);
	$txt1 = array(
		'id'=>'3',
		'text'=>'Join for free. Sign up in seconds!',
		'link'=>base_url('auth/create_account'),
		'title'=>'Sign Up',
		'icon'=>'glyphicon-headphones',
		'gTrack'=>'join-for-free'
		);
	$txt2 = array(
		'id'=>'4',
		'text'=>'Find More Songs By ' . $song->song_artist,
		'link'=>base_url('search/songs/' . clean_name($song->song_artist)),
		'title'=>'Click Here',
		'icon'=>'glyphicon-headphones',
		'gTrack'=>'findbyartist-5'
		);
	$txt3 = array(
		'id'=>'1',
		'text'=>'Start A ' . $song->song_artist . ' Playlist',
		'link'=>base_url('playlist/artist/' . clean_name($song->song_artist)),
		'title'=>'Start Listening',
		'icon'=>'glyphicon-play',
		'gTrack'=>'startplaylist-5-li'
		);
$txt = array($txt0,$txt1,$txt2,$txt3);
shuffle($txt);
?>
<?php } ?>
	
<script type="text/javascript">
	$(document).ready(function(){
		if($('#msgBar').length) {
		var gBar = document.getElementById('msgBar');
        gBar.addEventListener('click', function(){
            ga('send', 'event', 'Ad', 'Click', 'Message Bar Button - <?php echo $txt[0]["gTrack"] ?>', {'page': '<?php echo current_url(); ?>', 'advert': '<?php echo $txt[0]["gTrack"] ?>'});
        }, false);
		}
	});
</script>
<div class="topSectionMsg"><?php echo $txt[0]['text']; ?> <a href="<?php echo $txt[0]['link']; ?>" title="<?php echo $txt[0]['title']; ?>" class="btn btn-warning btn-xs" style="margin:5px" id="msgBar"><span class="glyphicon <?php echo $txt[0]['icon']; ?>"></span> <?php echo $txt[0]['title']; ?></a></div>
