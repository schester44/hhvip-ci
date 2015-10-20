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
							<li>Manage Playlists</li>
						</ol>
					</div>
	<header class="page-header">        
          <h2 class="page-title">Playlists Manager</h2>
			<p><?php echo lang('index_subheading');?></p>
          </header>

					<article class="entry style-single type-post">
						<div class="entry-content">

				<?php if ($playlists) { ?>
					
				<table class="table">
					<thead>
						<tr>
							<th>Title</th>
							<th>Uploader</th>
							<th>Uploaded</th>
							<th style="width:170px">Actions</th>
						</tr>
					</thead>
					<tbody>
					

				<?php foreach ($playlists as $list) { ?>

						<tr><td colspan="1"><a href="<?php echo base_url('playlist/' . $list->username . '/' . $list->url); ?>" title="<?php echo $list->title; ?>"><?php echo $list->title; ?></a></td>
						<td><?php echo $list->username; ?></td>
						<td><?php echo $list->date_created; ?></td>
						<td>
							<a href="<?php echo base_url('manage/delete_playlist/' . $list->username . '/' . $list->url); ?>" class="btn btn-danger">DELETE</a>
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
