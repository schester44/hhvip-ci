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
							<li>Manage Mixtapes</li>
						</ol>
					</div>

	<header class="page-header">        
          <h2 class="page-title">Tape Manager</h2>
<p><?php echo lang('index_subheading');?></p>
          </header>

					<article class="entry style-single type-post">

					<a href="<?php echo base_url('backend/mixtapes/published') ?>" class="btn btn-primary">Published Tapes</a>
					<a href="<?php echo base_url('backend/mixtapes/incomplete') ?>" class="btn btn-primary">Incomplete Tapes</a>
					<a href="<?php echo base_url('backend/mixtapes/copyright') ?>" class="btn btn-primary">Copyrighted Tapes</a>
						<div class="entry-content">

				<?php if ($tapes) { ?>
					
				<table class="table">
					<thead>
						<tr>
							<th>Song Title</th>
							<th>Uploader</th>
							<th>Uploaded</th>
							<th style="width:170px">Actions</th>
						</tr>
					</thead>
					<tbody>
					

				<?php foreach ($tapes as $tape) { ?>

						<tr id="song_<?php echo $tape->file_uid; ?>"><td colspan="1"><?php 
						if (!empty($tape->tape_title)) { ?>
						
						<a href="<?php echo base_url('song/' . $tape->username . '/' . $tape->tape_url) ?>"><?php echo $tape->tape_artist . ' - ' . $tape->tape_title; ?></a>

						<?php } else {
								echo $tape->file_name; 
							}?></td>
						<td><?php echo $tape->username; ?></td>
						<td><?php echo $tape->upload_date; ?></td>
						<td>
							<a href="<?php echo base_url('manage/mixtape/'. $tape->id . '/edit') ?>" class="btn btn-primary">Edit</a>
							<a href="<?php echo base_url('manage/mixtape/admin/'.$tape->id.'/delete') ?>" class="btn btn-warning">Delete</a>

						</td>
						</tr>			
				<?php 	} ?>
				</tbody>
			</table>

						<?php } else {
							echo '<td><span style="font-weight:bold;color:red;font-size:18px">NO TAPES FOUND!</span></td>';
							} ?>

						<div><?php echo $pagination; ?></div>


							<p class="lead">
<div id="infoMessage"><?php echo $this->session->flashdata('message');;?></div>
								
							</p>
						

						</div>
					</article>
				</div><!--/.col-md-8.col-lg-8.col-xl-9-->
				<?php echo $this->load->view('admin/sidebar'); ?>
				