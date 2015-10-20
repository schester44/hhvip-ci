<style type="text/css">

.model-selector div {

	margin: 0 50px;
}


.model-selector label {
  width: 200px;
  border-radius: 3px;
  border: 1px solid #D1D3D4
}

/* hide input */
input.radio:empty {
	margin-left: -999px;
}

/* style label */
input.radio:empty ~ label {
	position: relative;
	float: left;
	line-height: 2.5em;
	text-indent: 3.25em;
	margin-top: 2em;
	cursor: pointer;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

input.radio:empty ~ label:before {
	position: absolute;
	display: block;
	top: 0;
	bottom: 0;
	left: 0;
	content: '';
	width: 2.5em;
	background: #D1D3D4;
	border-radius: 3px 0 0 3px;
}

/* toggle hover */
input.radio:hover:not(:checked) ~ label:before {
	content:'\2714';
	text-indent: .9em;
	color: #C2C2C2;
}

input.radio:hover:not(:checked) ~ label {
	color: #888;
}

/* toggle on */
input.radio:checked ~ label:before {
	content:'\2714';
	text-indent: .9em;
	color: #FFFFFF;
	background-color: #FA8900;
}

input.radio:checked ~ label {
	color: #777;
}

/* radio focus */
input.radio:focus ~ label:before {
	box-shadow: 0 0 0 3px #999;
}	
</style>
<div class="section row entries">
	<article class="entry col-xs-12 col-sm-12">
		
	</article>
</div>
<div id="content" class="content section row">
	<div class="col-md-8 bg-base col-lg-8 col-xl-9">
		<div class="ribbon ribbon-highlight">
			<ol class="breadcrumb ribbon-inner">
				<li><a href="<?php echo base_url(); ?>">Home</a></li>
				<li><a href="<?php echo base_url('backend'); ?>">Backend</a></li>
				<li>Search</li>
			</ol>
		</div>
					
		<header class="page-header">
			<h2 class="page-title">Backend Search</h2>
		</header>
		<article class="entry style-single type-post">

				<p class="lead">
					<div id="infoMessage">
						<?php echo $this->session->flashdata('message');;?>
					</div>
				</p>	


			<div class="entry-content">
				<?php if ($resultsAvailable): ?>
					<ol>
					<?php foreach ($result as $key => $r): ?>
						<li>ID: <?php echo $r->id; ?> | Username: <?php echo $r->username; ?> | Email: <?php echo $r->email; ?> <a href="<?php echo base_url('auth/edit_user/' . $r->id); ?>" target="blank" class="btn btn-xs btn-primary">Edit</a></li>
					<?php endforeach ?>
					</ol>
				<?php else: ?>
					<h2>No Results Bruh.. Check your search parameters.</h2>
				<?php endif ?>
			</div>
		</article>
	</div><!--/.col-md-8.col-lg-8.col-xl-9-->

				<?php echo $this->load->view('admin/sidebar'); ?>