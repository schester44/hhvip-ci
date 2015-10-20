	<div class="section row entries">
	    <div class="entry col-xs-12 col-sm-12"></div>
	</div>
<div class="section row entries topPlayerBoard">
	    <div class="entry col-xs-12 col-sm-12">
			<form action="<?php echo base_url('search') ?>" method="get">
			<div class="form-wrapper cf">
			<input type="text" id="q" name="q" placeholder="Let me hear some..." onFocus="this.select()">
			<button type="submit" style="font-size:14px">Search <span class="glyphicon glyphicon-search"></span></button>
			</div>
			<div class="sort-results-wrapper" style="text-align:center;padding-top:10px">
				<div class="sort-results" style="font-weight:bold;display:inline">Sort Results:</div>	
				<div style="display:inline" class="search-sort">
					
					<select name="sort">
						<option value="latest" <?php if ($this->input->get('sort') == 'latest' || $this->input->get('sort') == '') { echo 'selected'; } ?>>Most Recent</option>
						<option value="popular" <?php if ($this->input->get('sort') == 'popular') { echo 'selected'; } ?>>Most Popular</option>
						<option value="trending" <?php if ($this->input->get('sort') == 'trending') { echo 'selected'; } ?>>Most Relevent</option>
					</select>
					<select name="only">
						<option value="" <?php if ($this->input->get('only') == '') { echo 'selected'; } ?>>All Results</option>
						<option value="artist" <?php if ($this->input->get('only') == 'artist') { echo 'selected'; } ?>>Artist Only</option>
					</select>

				</div>		

			</div>
			</form>
	    </div>
</div>	 		<!-- lol at this hack of a hack-->
			<div id="content" class="content section row" style="display:none">

				<div class="col-md-8 bg-base col-lg-8 col-xl-9">
				</div><!--/.col-md-8.col-lg-8.col-xl-9-->