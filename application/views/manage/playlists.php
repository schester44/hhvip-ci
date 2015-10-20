<script type="text/javascript">
	$(document).ready(function(){

		$('.delete-btn').click(function(e){
          		e.preventDefault();
          
      			var row = $(this).parent().parent();

				var id = $(this).data('id');
				var uid = $(this).data('uid');
				var playlist_title = $(this).data('title');

				var file_name = $(this).data('filename');
				var $df = $("#delete_modal");

				$("input[name=playlist_id]", $df).val(id);
				$("input[name=uid]", $df).val('<?php echo $this->ion_auth->user()->row()->id; ?>');
          		
          		$('#mixtape-title').html(playlist_title);

          $('.confirm-delete-btn').click(function(e){
            e.preventDefault();
            var url = '<?php echo base_url("manage/playlist/delete") ?>';
            $.post(url,
                   $('#myForm').serialize(),
                   function(data, status, xhr) {
                     if (data.response == 'error') {
                       $("#formErrors").show();
                       $('#formErrors').html(data.message);
                     } else if (data.response == 'success') {
                       $('#delete_modal').modal('hide');
                       location.reload();
                     }
                   });
          });
        });
      });
</script>
			<div id="content" class="content section row">
				<div class="col-md-8 bg-base col-lg-8 col-xl-9">
					<div class="ribbon ribbon-highlight">
						<ol class="breadcrumb ribbon-inner">
							<li><a href="<?php echo base_url(); ?>">Home</a></li>
							<li><a href="<?php echo base_url('u/'.$user->username); ?>">Manage Account</a></li>
							<li>My Playlists</li>
						</ol>
					</div>
					
						<header class="page-header">
						<div id="infoMessage"><?php echo $this->session->flashdata('message');;?></div>

					<h2 class="page-title">
							Manage My Playlists
					</h2>
					<p class="sub-title">
						You can Edit/View/Delete your playlists from this page.
					</p>
						</header>
							<article class="entry style-single type-post">
<?php if (!$playlists) {
	echo "<div align='center'>Looks like you haven't created any playlists yet.<br />You can create one by clicking the 'Add to Playlist' button on any song.</div>";
	} else { ?>
							<div id="mixtapes">
							<table class="table table-striped">
								<thead style="font-weight:bold; text-align:center; font-size:18px">
									<td>Tape</td>
									<td>Songs</td>
									<td>Status</td>
									<td>Actions</td>
								</thead>
								<tbody>

								<?php foreach ($playlists as $key => $playlist) { ?>

								<tr style="text-align:center" id="list_row_<?php echo $playlist->id ?>"> <!--b. mixtape row-->
									<td class="mixtape" style="max-width:350px;overflow:hidden;text-overflow:ellipsis">
										<strong><?php echo $playlist->title; ?></strong>
									</td>
									<td class="total_tracks">
										<?php echo $playlist->track_count; ?>
									</td>
							
									<td class="status">
										<?php echo $playlist->status; ?>
									</td>
									<td class="actions">

									<div class="dropdown pull-right">
									  <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
									    Options
									    <span class="glyphicon glyphicon-cog"></span>
									  </button>
									  <ul class="dropdown-menu" role="menu" style="text-align:center" aria-labelledby="dropdownMenu1">
									    <li role="presentation"><a href="<?php echo base_url('manage/playlist/'.$playlist->id.'/edit'); ?>">Edit <span class="glyphicon glyphicon-pencil"></span></a></li>
									    <li role="presentation"><a href="<?php echo base_url('playlist/'.$user->username.'/'.$playlist->url); ?>">View <span class="glyphicon glyphicon-play-circle"></span></a></li>
									    <li role="presentation"><a class="delete-btn" style="cursor:pointer" id="delete-btn" data-id="<?php echo $playlist->id; ?>" data-uid="<?php echo $this->ion_auth->user()->row()->username; ?>" data-title="<?php echo htmlspecialchars($playlist->title, ENT_QUOTES); ?>" data-toggle="modal" data-target="#delete_modal">Delete <span class="glyphicon glyphicon-remove"></span></a></li>
									  </ul>
									</div>
									</td>
								</tr> <!--e. mixtape row-->

							<?php } //foreach ?>
								
							</tbody>
							</table>
							</div>

							<?php } // if/else ?>
							<?php echo $pagination; ?>
							</article>					

				</div><!--/.col-md-8.col-lg-8.col-xl-9-->



				<!-- Delete Modal -->
<div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="delete_modalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Delete: <span id="mixtape-title" style="padding-left:10px"> </span></h4>
     	<h3 style="color:red">
     		<div id="formErrors"></div>
     	</h3>
      </div>

      <div class="modal-body">
      <p style="color:red"><strong>WARNING! CLICKING DELETE WILL DELETE THE PLAYLIST</strong></p>
      <p>If you need to change some of the information associated with this playlist, please click cancel and use the 'edit' button instead. Deleting a playlist will permanently remove it from the network. It is always better to Edit than to delete.</p>
      		
    						<?php echo form_open('manage/playlist/delete', $form_attributes) ?>
      						<input type="hidden" id="playlist_id" name="playlist_id" value="">
      						<input type="hidden" id="uid" name="uid" value="<?php echo $user->id; ?>">
      						<?php echo form_close(); ?>


     </div>	
		      <div class="modal-footer">
		      		<button type="button" id="cancel-btn" class="btn btn-primary" data-dismiss="modal">Cancel</button>
					<button type="submit" id="confirm-delete-btn" class="btn btn-warning confirm-delete-btn">Delete Playlist</button>
		      </div>
    </div>
  </div>
</div>