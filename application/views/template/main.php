<?php echo $header ?>
<div id="main" class="container">
	<div class="section row entries" style="margin-top:10px;margin-bottom:5px">
	         <article class="entry col-xs-12 col-sm-12">
				<?php $this->load->view('modules/ads/newsletter'); ?>
	        </article>
	</div>
	<?php echo $content_body ?>
	<?php if (!isset($noSidebar)): ?>
	<?php echo $sidebar ?>
	<?php endif ?>
    </div><!--/#content-->
        </div><!--#main.container-->
<?php echo $footer ?>