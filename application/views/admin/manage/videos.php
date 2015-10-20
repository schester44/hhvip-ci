<script type="text/javascript">

$(document).ready(function(){
	$(".pub-btn").hide();

});

function setStatus(id, status) {
    $target_video = $("#video_" + id);
    $.ajax({
        type: 'POST',
        url: '/videos/status',
        data: {id: id ,status: status },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
        	alert(errorThrown)
            $target_video.html('err');
        },
        success: function (data) {
            if (data.error === true) {
            	alert('error')
            } else {
            	$("#publish_" + id).hide();
            	$("#published_" + id).show();
            }
        },
        dataType: "json"
    });
}
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
							<li>Manage Videos</li>
						</ol>
					</div>

	<header class="page-header">        
          <h2 class="page-title">Videos Manager</h2>
<p><?php echo lang('index_subheading');?></p>
          </header>

					<article class="entry style-single type-post">
					<div class="entry-content">
            <div id="infoMessage">
                	<?php echo $this->session->flashdata('video_setStatus');;?>
					<?php echo $this->session->flashdata('video_deleted'); ?>
					</div>
				<?php if ($videos) { ?>
					
				<table class="table">
					<thead>
						<tr>
							<th>Video Title</th>
							<th>Uploader</th>
							<th>Uploaded</th>
							<th>Status</th>
							<th style="width:170px">Actions</th>
						</tr>
					</thead>
					<tbody>
					

				<?php foreach ($videos as $video) { ?>
						<tr id="song_<?php echo $video->id; ?>"><td colspan="1">
							<a href="<?php echo base_url('videos/'.$video->username.'/'.$video->video_url); ?>"><?php echo htmlspecialchars($video->video_title, ENT_QUOTES); ?></a>
							</td>
						<td><a href="<?php echo base_url('u/'.$video->username); ?>" title="View <?php echo $video->username; ?>'s Profile"><?php echo $video->username; ?></a></td>
						<td><?php echo date('m-d-Y', $video->upload_date); ?></td>
						<td>
						<?php if ($video->status === 'pending') { ?>
							<button class="btn btn-danger btn-xs" onclick="setStatus(<?php echo $video->id ?>,'published')" id="publish_<?php echo $video->id ?>">Publish</button>
							<div class="btn btn-success btn-xs pub-btn" id="published_<?php echo $video->id ?>">Published</div>
						<?php } else { ?>
							<div class="btn btn-success btn-xs" id="published_<?php echo $video->id ?>"><?php echo $video->status; ?></div>

						<? } ?>
						<td>
							<a href="<?php echo base_url('videos/'.$video->username.'/'. $video->video_url) ?>" class="btn btn-info btn-xs">View</a>
							<a href="<?php echo base_url('videos/edit/'. $video->id) ?>" class="btn btn-primary btn-xs">Edit</a>
							<a href="<?php echo base_url('videos/delete/'.$video->id) ?>" class="btn btn-warning btn-xs">Delete</a>

						</td>
						</tr>			
				<?php 	} ?>
				</tbody>
			</table>

						<?php } else {
							echo '<td>NO VIDEOS!</td>';
							} ?>

						<div><?php echo $pagination; ?></div>
							<p class="lead"><div id="infoMessage"><?php echo $this->session->flashdata('message');;?></div></p>
						</div>
					</article>
				</div><!--/.col-md-8.col-lg-8.col-xl-9-->
				<?php echo $this->load->view('admin/sidebar'); ?>
				