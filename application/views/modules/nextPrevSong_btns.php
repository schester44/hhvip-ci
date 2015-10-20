<?php if ($prevSong): ?>
    <a href="<?php echo base_url('song/' . $prevSong->username . '/' . $prevSong->song_url) ?>" title="Listen and Download <?php echo $prevSong->song_title ?> by <?php echo $prevSong->song_artist ?>" class="btn btn-warning nav-home-btn" style="width:49%">Previous Song</a>
<?php endif ?>
<?php if ($nextSong): ?>
    <a href="<?php echo base_url('song/' . $nextSong->username . '/' . $nextSong->song_url) ?>" title="Listen and Download <?php echo $nextSong->song_title ?> by <?php echo $nextSong->song_artist ?>" class="btn btn-warning nav-home-btn" style="width:49%">Next Song</a>   
<?php endif ?>

<?php if (!$prevSong || !$nextSong): ?>
    <a href="<?php echo base_url('songs/trending') ?>" title="Download and stream trending hip hop music" class="btn btn-warning nav-home-btn" style="width:49%">Trending Songs</a>   
<?php endif ?>