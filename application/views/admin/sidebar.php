<style type="text/css">
	.admin-sidebar-search input {
		width: 100%;
		height: 40px;
		margin-bottom: 5px;
		font-size: 18px;
		padding-left: 5px;	
	}
</style>
<div class="sidebar col-md-4 col-lg-4 col-xl-3">
	<form method="GET" action="<?php echo base_url('backend/search'); ?>">
		<div style="padding-bottom:25px" class="admin-sidebar-search">
			<select name="t" style="width:100%;margin-bottom:5px">
			  <option value="user" selected="selected">User</option>
			  <option value="song">Song</option>
			  <option value="playlist">Playlist</option>
			  <option value="blog">Blog Post</option>
			</select>
			<input type="text" name="q" value="" placeholder="Search.." id="sidebar-admin-query">
			<input type="button" class="btn btn-warning" style="width:100%" value="Submit!" name="submit">
		</div>
	</form>


	<a href="<?php echo base_url('backend'); ?>" style="width:100%;margin-bottom:3px" class="btn btn-primary">Admin Home</a>
	<a href="<?php echo base_url(); ?>" style="width:100%" class="btn btn-primary">Site Home</a>
	<H3>Site Manager</H3>
		<a href="<?php echo base_url('backend/songs') ?>" title="Manage Songs" style="width:100%;margin-bottom:3px;text-align:left" class="btn btn-primary">Songs Manager</a>
		<a href="<?php echo base_url('backend/playlists') ?>" title="Manage Playlists" style="width:100%;margin-bottom:3px;text-align:left" class="btn btn-primary">Playlists Manager</a>
		<a href="<?php echo base_url('backend/mixtapes') ?>" title="Manage Mixtapes" style="width:100%;margin-bottom:3px;text-align:left" class="btn btn-primary">Tape Manager</a>
		<a href="<?php echo base_url('backend/videos') ?>" title="Manage Videos" style="width:100%;margin-bottom:3px;text-align:left" class="btn btn-primary">Videos Manager</a>
		<a href="<?php echo base_url('backend/users'); ?>" title="Manage Users" style="width:100%;margin-bottom:3px;text-align:left" class="btn btn-primary">User Manager</a>
	<h3>Blog</h3>
		<a href="<?php echo base_url('backend/blog'); ?>" title="Manage Blog Posts" style="width:100%;margin-bottom:3px;text-align:left" class="btn btn-primary">Manage Posts</a>
		<a href="<?php echo base_url('backend/blog/categories'); ?>" title="Manage Blog Categories" style="width:100%;margin-bottom:3px;text-align:left" class="btn btn-primary">Manage Categories</a>

<?php if ($this->ion_auth->is_admin()) { ?>
	<h3>Site Administration</h3>
		<a href="<?php echo base_url('backend/stats'); ?>" style="width:100%;margin-bottom:3px;text-align:left" class="btn btn-success">Song Stats</a>
		<a href="<?php echo base_url('backend/site_functions'); ?>" style="width:100%;margin-bottom:3px;text-align:left" class="btn btn-warning">Misc. Functions</a>
<?php } ?>

</div><!--/.sidebar col-md-4 col-lg-4 col-xl-3-->
