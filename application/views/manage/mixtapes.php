<script type="text/javascript">
	$(document).ready(function(){

		$('.delete-btn').click(function(e){
          		e.preventDefault();
          
      			var row = $(this).parent().parent();
				var tape_id = $(this).data('id');
				var tape_title = $(this).data('mixtapetitle');
				var tape_uid = $(this).data('mixtapeuid');
				var file_name = $(this).data('filename');
				var $df = $("#delete_modal");

				$("input[name=id]", $df).val(tape_id);
				$("input[name=file_uid]", $df).val(tape_uid);
				$("input[name=file_name]", $df).val(file_name);
          		
          		$('#mixtape-title').html(tape_title);

          $('.confirm-delete-btn').click(function(e){
            e.preventDefault();
            var url = '<?php echo base_url("manage/mixtape/delete") ?>';
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
                       $(row).remove();
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
							<li>My Mixtapes</li>
						</ol>
					</div>
					
						<header class="page-header">
						<div id="infoMessage"><?php echo $this->session->flashdata('message');;?></div>

					<h2 class="page-title">
							Manage My Tapes
					</h2>
					<p class="sub-title">
						You can Edit/View/Delete your mixtapes from this page.
					</p>
						</header>
							<article class="entry style-single type-post">
<?php if (!$mixtapes) {
	echo "<div align='center'>Looks like you haven't uploaded any mixtapes yet. You can upload them <a href='" . base_url('upload/mixtape') ."' title='Upload Mixtape' style='color:orange;'>here</a>.</div>";
	} else { ?>
							<div id="mixtapes">
							<table class="table table-striped">
								<thead style="font-weight:bold; text-align:center; font-size:18px">
									<td>Tape</td>
									<td>Date Uploaded</td>
									<td>Status</td>
									<td>Actions</td>
								</thead>
								<tbody>
								<?php foreach ($mixtapes as $key => $mixtape) { 

									$cname 		= cleanFileName($mixtape->file_name);
									$file_name 	= (strlen($cname) > 25) ? $file_name = substr($cname, 0, 30) . '...' : $cname;
								?>

								<tr> <!--b. mixtape row-->
									<td class="mixtape" style="text-align:center;max-width:350px;overflow:hidden;text-overflow:ellipsis">
									<div class="row">
										<strong><?php 
										if (!empty($mixtape->tape_title)) {
											echo '<a href="'.base_url('mixtape/'.$this->ion_auth->user()->row()->username.'/'.$mixtape->tape_url).'" title="'.htmlspecialchars($mixtape->tape_artist, ENT_QUOTES).' - '.htmlspecialchars($mixtape->tape_title, ENT_QUOTES).'">' . htmlspecialchars($mixtape->tape_title, ENT_QUOTES) . '</a>';
										} else {
											echo $file_name;
											} ?></strong></div>
										<div class="row">
										<?php echo $mixtape->tape_artist; ?>
										</div>

									</td>
									<td class="uploaded" style="text-align:center">
										<?php echo date('m/d/Y', strtotime($mixtape->upload_date)); ?>
									</td>
									<td class="status" style="text-align:center">
										<?php echo $mixtape->status; ?>
									</td>
									<td class="actions">

										<div class="dropdown pull-right">
										  <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
										    Options
										    <span class="glyphicon glyphicon-cog"></span>
										  </button>
										  <ul class="dropdown-menu" role="menu" style="text-align:center" aria-labelledby="dropdownMenu1">
											<?php if ($mixtape->status === 'published'): ?>
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('manage/mixtape/'.$mixtape->id.'/edit'); ?>">Edit <span class="glyphicon glyphicon-pencil"></span></a></li>
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('mixtape/'.$user->username.'/'.$mixtape->tape_url); ?>">View <span class="glyphicon glyphicon-play-circle"></span></a></li>
											<?php endif; ?>
											<?php if ($mixtape->status !== 'incomplete'): ?>
										    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url('manage/mixtape/'.$mixtape->id.'/edit'); ?>">Publish <span class="glyphicon glyphicon-upload"></span></a></li>
										   	<?php endif; ?>
										    <li role="presentation"><a class="delete-btn" style="cursor:pointer" id="delete-btn" data-id="<?php echo $mixtape->id; ?>" data-mixtapeuid="<?php echo $mixtape->file_uid; ?>" data-filename="<?php echo $mixtape->file_name; ?>" data-mixtapetitle="<?php echo htmlspecialchars($mixtape->tape_title, ENT_QUOTES); ?>" data-toggle="modal" data-target="#delete_modal">Delete <span class="glyphicon glyphicon-remove"></span></a></li>
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
      <p style="color:red"><strong>WARNING! CLICKING DELETE WILL DELETE THE MIXTAPE</strong></p>
      <p>If you need to change some of the information associated with this mixtape, please click cancel and use the 'edit' button instead. Deleting a mixtape will permanently delete and remove all traces of the mixtape from our network. It is always better to Edit than to delete.</p>
      		
    						<?php echo form_open('manage/mixtape/delete', $form_attributes) ?>
      						<input type="hidden" id="id" name="id" value="">
      						<input type="hidden" id="file_uid" name="file_uid" value="">
      						<input type="hidden" id="file_name" name="file_name" value="">
      						<input type="hidden" id="user_id" name="user_id" value="<?php echo $user->id ?>">
      						<?php echo form_close(); ?>


     </div>	
		      <div class="modal-footer">
		      		<button type="button" id="cancel-btn" class="btn btn-primary" data-dismiss="modal">Cancel</button>
					<button type="submit" id="confirm-delete-btn" class="btn btn-warning confirm-delete-btn">Delete mixtape</button>
		      </div>
    </div>
  </div>
</div>