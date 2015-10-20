
<script type="text/javascript">
    $(document).ready(function () {
		$('.delete-btn').click(function(e){
          		e.preventDefault();
          
      			var row = $(this).parent().parent();
				var id = $(this).data('id');
				var title = $(this).data('videotitle');
				var uid = $(this).data('uid');

				var $df = $("#delete_modal");

				$("input[name=id]", $df).val(id);
				$("input[name=uid]", $df).val(uid);

          $('#video-title').html(title);
          $('#confirm-delete-btn').click(function(e){
            e.preventDefault();
            var url = '<?php echo base_url("manage/video/delete"); ?>';
            $.post(url,
                   $('#myForm').serialize(),
                   function(data, status, xhr) {
                     if (data.validation == 'error') {
                       $("#formErrors").show();
                       $('#formErrors').html(data.message);
                     } else if (data.validation == 'valid' && data.response == 'error') {
                       $("#formErrors").show();
                       $('#formErrors').html(data.message);
                     } else if (data.validation == 'valid' && data.response == 'success') {
                       $('#delete_modal').modal('hide');
                       alert(title + ' was deleted');
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
							<li>My Videos</li>
						</ol>
					</div>
					
						<header class="page-header">
						<div id="infoMessage"><?php echo $this->session->flashdata('message');;?></div>

					<h2 class="page-title">Manage My Videos</h2>
					<p class="sub-title">
						You can Edit/View/Delete your videos from this page.
					</p>
						</header>
							<article class="entry style-single type-post">
<?php if (!$videos) {
	echo "<div align='center'>Looks like you haven't uploaded any videos yet. You can upload them <a href='" . base_url('videos/add') ."' title='Add Videos' style='color:orange;'>here</a>.</div>";
	} else { ?>
							<div id="songs">
							<table class="table table-striped">
								<thead style="font-weight:bold; text-align:center; font-size:18px">
									<td>Video</td>
									<td>Date Uploaded</td>
									<td>Status</td>
									<td>Actions</td>
								</thead>
								<tbody>
								<?php foreach ($videos as $key => $video) { ?>

								<tr style="text-align:center"> <!--b. video row-->
									<td class="video"  style="max-width:350px;overflow:hidden;text-overflow:ellipsis">
									<div class="row">
										<strong><?php echo htmlspecialchars($video->video_title, ENT_QUOTES); ?></strong></div>
									</td>
									<td class="uploaded">
										<?php echo date('m/d/Y', $video->upload_date); ?>
									</td>
									<td class="status">
										<?php echo $video->status; ?>
									</td>
									<td class="actions">


									<div class="dropdown pull-right">
									  <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
									    Options
									    <span class="glyphicon glyphicon-cog"></span>
									  </button>
									  <ul class="dropdown-menu" role="menu" style="text-align:center" aria-labelledby="dropdownMenu1">
										<?php if ($video->status === 'published'): ?>
										 <li role="presentation"><a href="<?php echo base_url('videos/'.$user->username.'/'.$video->video_url); ?>">View</a></li>	
									<?php endif ?>
										 <li role="presentation"><a href="<?php echo base_url('videos/edit/'.$video->id); ?>">Edit</a></li>
										 <li role="presentation"><a class="delete-btn" style="cursor:pointer" id="delete-btn" data-id="<?php echo $video->id; ?>" data-videotitle="<?php echo htmlspecialchars($video->video_title,ENT_QUOTES); ?>" data-uid="<?php echo $video->user_id; ?>" data-toggle="modal" data-target="#delete_modal">Delete</button></li>

									  </ul>
									</div>
									</td>
								</tr> <!--e. song row-->

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
        <h4 class="modal-title" id="myModalLabel">Delete: <span id="video-title" style="padding-left:10px"> </span></h4>
     	<h3 style="color:red">
     		<div id="formErrors"></div>
     	</h3>
      </div>

      <div class="modal-body">
      <p style="color:red"><strong>WARNING! CLICKING DELETE WILL PERMANENTLY DELETE THE VIDEO</strong></p>
      <p>If you need to change some of the information associated with this video, please click cancel and use the 'edit' button instead. Deleting a video will permanently delete and remove all traces of the video from our network. It is always better to Edit than to delete.</p>
      		
    						<?php echo form_open('manage/videos/delete', $form_attributes) ?>
      						<input type="hidden" id="id" name="id" value="">
      						<input type="hidden" id="uid" name="uid" value="">
      						<?php echo form_close(); ?>


     </div>	
		      <div class="modal-footer">
		      		<button type="button" id="cancel-btn" class="btn btn-primary" data-dismiss="modal">Cancel</button>
					<button type="submit" id="confirm-delete-btn" class="btn btn-warning confirm-delete-btn">Delete Song</button>
		      </div>
    </div>
  </div>
</div>