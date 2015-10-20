<script type="text/javascript">
	$(document).ready(function(){
		$('#batch-song-view').hide();

		$('#batch-song-view-btn').on('click',function(e){
			e.preventDefault();
			$('#batch-song-view').fadeToggle('medium');
		});

		$('.delete-btn').click(function(e){
  e.preventDefault();
  
		var $tp  = $(this).parents('tr');
		var data = $tp.data('data');
			var $df = $("#delete_modal");

			$("input[name=song_id]", $df).val(data.song_id);
			$("input[name=song_uid]", $df).val(data.id);
			$("input[name=file_name]", $df).val(data.file);
  
  $('.confirm-delete-btn').click(function(e){
    e.preventDefault();
    var url = '<?php echo base_url("manage/song/delete") ?>';
    $.post(url,
           $('#deleteForm').serialize(),
           function(data, status, xhr) {
             var data = jQuery.parseJSON(data);
             if (data.validation == 'error') {
               $("#formErrors").show();
               $('#formErrors').html(data.message);
             } else if (data.validation == 'valid' && data.response == 'error') {
               $("#formErrors").show();
               $('#formErrors').html(data.message);
             } else if (data.validation == 'valid' && data.response == 'success') {
               $($tp).remove();
               $('#delete_modal').modal('hide');
               location.reload();
             }
           });
 	 });
	});
});
</script>
		<div class="section row entries">
         <article class="entry col-xs-12 col-sm-12">
        </article>
</div>
			<div id="content" class="content section row">
				<div class="col-md-8 bg-base col-lg-8 col-xl-9">
					<div class="ribbon ribbon-highlight">
						<ol class="breadcrumb ribbon-inner">
            				<li><a href="<?php echo base_url(''); ?>">Home</a></li>
							<li><a href="<?php echo base_url('auth'); ?>">Admin Home</a></li>
							<li>Manage Songs</li>
						</ol>
					</div>


<div id="batch-song-view">					
	<h3>Batch update song status</h3>
	<form method="POST" action="<?php echo base_url('manage/delete_song'); ?>">
		<textarea name="ids" id="ids" style="width:100%;height:100px;"></textarea>
<div class="form-group">
  <label for="reason">Select list:</label>
  <select class="form-control" id="reason" name="reason">
    <option name="copyright" value="copyright">Copyright</option>
    <option name="removed" value="removed">Remove</option>
  </select>
</div>

		<input type="submit" class="btn btn-primary" style="margin-top:5px">
	</form>
</div>

	<header class="page-header">        
          <h2 class="page-title">Songs Manager</h2>
			<p><?php echo lang('index_subheading');?></p>
          </header>

					<article class="entry style-single type-post">
					<button class="btn btn-danger" id="batch-song-view-btn">Batch Song Update</button>
					<a href="<?php echo base_url('backend/songs/published') ?>" class="btn btn-primary">Published Songs</a>
					<a href="<?php echo base_url('backend/songs/incomplete') ?>" class="btn btn-primary">Incomplete Songs</a>
					<a href="<?php echo base_url('backend/songs/copyright') ?>" class="btn btn-primary">Copyrighted Songs</a>
						<div class="entry-content">

				<?php if ($songs) { ?>
					
				<table class="table">
					<thead>
						<tr>
							<th>Song Title</th>
							<th>Uploader</th>
							<th>Uploaded</th>
							<th>Visibility</th>
							<th style="width:170px">Actions</th>
						</tr>
					</thead>
					<tbody>
					

				<?php foreach ($songs as $song) { ?>

						<tr id="song_<?php echo $song->file_uid; ?>"><td colspan="1"><?php 
						if (!empty($song->song_title)) { ?>
						
						<a href="<?php echo base_url('song/' . $song->username . '/' . $song->song_url) ?>"><?php echo $song->song_artist . ' - ' . $song->song_title; ?></a>

						<?php } else {
								echo $song->file_name; 
							}?></td>
						<td><?php echo $song->username; ?></td>
						<td><?php echo $song->upload_date; ?></td>
						<td><?php echo $song->visibility; ?></td>
						<td>
							<a href="<?php echo base_url('manage/song/'. $song->song_id . '/edit') ?>" class="btn btn-primary">Edit</a>
							<a href="<?php echo base_url('manage/song/admin/'.$song->song_id.'/delete') ?>" class="btn btn-warning">Delete</a>

						</td>
						</tr>			
				<?php 	} ?>
				</tbody>
			</table>

						<?php } else {
							echo '<td>END OF THE ROAD!';
							} ?>

						<div><?php echo $pagination; ?></div>


							<p class="lead">
<div id="infoMessage"><?php echo $this->session->flashdata('message');;?></div>
								
							</p>
						</div>
					</article>
				</div><!--/.col-md-8.col-lg-8.col-xl-9-->
				<?php echo $this->load->view('admin/sidebar'); ?>
