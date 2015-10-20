<script>
$(function() {
  $( "#sortable" ).sortable({
      update: function (event, ui) {
      var data = $(this).sortable('serialize');
      $.ajax({
          data: data,
          type: 'POST',
          url: '/manage/playlist/sort-order/<?php echo $playlist->id; ?>'
      });
    }
  });
  $( "#sortable" ).disableSelection();
});

$(document).ready(function(){
  $('#update-btn').click(function (e) {
      e.preventDefault();
      $('#playlistUpdate').ajaxSubmit({
          beforeSubmit: function () {},
          success: function (data) {
              if (data.validation == 'error') {
                  $("#errorMsg").show();
                  $('#errorMsg').html(data.message);
              } else if (data.validation == 'valid' && data.response == 'error') {
                  $("#errorMsg").show();
                  $('#errorMsg').html(data.message);
              } else if (data.validation == 'valid' && data.response == 'success') {
                  $("#errorMsg").show();
                  $('#errorMsg').html(data.message);
              }
          }
      });
  });//ajax update

    $('.delete-btn').click(function (e) {
      e.preventDefault();
    
      var id = this.id;
        $.ajax({
            url: '/manage/playlist/delete-track',
            type: 'POST',
            cache: false,
            dataType: "json",
            data: {
                'tid': id,
                'playlist_id': '<?php echo $playlist->id ?>',
                'uid': '<?php echo $this->ion_auth->user()->row()->id; ?>',
                },
            success: function(data, status, jqXHR) {
                if (data.response === 'error') {
                    alert(data.message);
                }
                else if (data.response === 'success') {
                  $('#track_' + data.id).remove();
                  $('#trackMsgs').fadeIn('fast');
                  $('#trackMsgs').html(data.message)
                      .addClass('alert alert-info')
                      .delay(2500).fadeOut();
                }
            },
        }); //end ajax
  });//ajax update

  $('input').on('keyup change',function(){
    $("#errorMsg").hide();
  });
});
</script>
<div id="content" class="content section row">
    <div class="col-md-12 bg-base col-lg-12 col-xl-12">

        <div class="ribbon ribbon-highlight">
            <ol class="breadcrumb ribbon-inner">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li><a href="<?php echo base_url('manage'); ?>">Account</a></li>
                <li><a href="<?php echo base_url('manage/playlists'); ?>">Playlists</a></li>
                <li>Edit Playlist</li>
            </ol>
        </div>
       <header class="page-header">
            <h1 title="entry-title" id="pageTitle" class="page-title">Edit Playlist</h1> 
        </header>

        <div class="alert alert-danger alert-dismissible" id="errorMsg" role="alert" style="display:none"></div>
        
<article class="entry style-single type-post">
  <div class="entry-content">            
  <div id="songlistwrapper"></div>   
  <div id="mixtapeDetails">
    <form method="post" action="<?php echo base_url('manage/playlist/update'); ?>" enctype="multipart/form-data" id="playlistUpdate">
      <input type="hidden" id="id" name="id" value="<?php echo $playlist->id; ?>">
      <input type="hidden" id="user_id" name="user_id" value="<?php echo $this->ion_auth->user()->row()->id; ?>">
      <div class="row">
        <div class="col-sm-6">
          <legend>Playlist Name</legend>
          <div class="form-group">
            <div class="input">
                <?php echo form_input($form_playlist_name); ?>
            </div>
          </div>
          <legend>Playlist Visibility</legend>
        <div class="form-group" style="margin-bottom:50px">
          <div class="radio">
              <label>
                  <input type="radio" name="status" id="status" value="public" <?php if ($playlist->status === 'public') { echo 'checked';}; ?>>
                  Public playlist - Visible to the world
              </label>
          </div>
          <div class="radio">
              <label>
                  <input type="radio" name="status" id="status" value="private" <?php if ($playlist->status === 'private') { echo 'checked';}; ?>>
                  Private playlist - Visible to you only
              </label>
          </div>
          <div class="radio">
              <label>
                  <input type="radio" name="status" id="status" value="unlisted" <?php if ($playlist->status === 'unlisted') { echo 'checked';}; ?>>
                  Unlisted playlist - you and whoever you share with can see it
              </label>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div id="sortTracksContainer">
          <h3 style="border-bottom:none;margin-bottom:-10px">Order Tracks</h3>

          <span style="font-weight:bold">TIP: </span><span style="color:#999">Drag and drop tracks to reorder. Order will auto save.</span>     
          <div id="sortTracks" class="sortTracksWrapper" style="padding-top:15px">
            <div id="trackMsgs" style="padding:3px"></div> 
           
            <ol id="sortable">
              <?php if (isset($tracks) && $tracks): ?>
              <?php foreach ($tracks as $key => $track): ?>
                <li class="sortTracksItem" id="track_<?php echo $track->id; ?>" style="padding:5px">
                  <span class="id<?php echo $track->id; ?>" id="title_<?php echo $playlist->id; ?>_<?php echo $track->id; ?>"><?php echo $track->song_artist . ' - ' . $track->song_title; ?></span>
                  <span style="float:right" class="btn btn-warning btn-xs delete-btn" id="<?php echo $track->id; ?>">DELETE</span>
                </li>
              <?php endforeach ?>
              <?php else: ?>
                Looks like there are no songs in this playlist.
              <?php endif ?>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <a href="<?php echo base_url('manage/playlists') ?>" type="button" id="cancel-btn" class="btn btn-danger">Cancel</a>
    <button type="submit" id="update-btn" class="btn btn-danger">Save/Update</button>
    <?php echo form_close(); ?><br />
  </div><!--playlistbody-->
  <br />
</article>

</div>