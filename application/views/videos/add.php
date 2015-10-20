<script type="text/javascript">
    $(function () {
        $('#processing').hide();
        $('#submit-btn').click(function (e) {
            e.preventDefault();

            $('#formMain').hide();
            $('#formErrors').hide();
            $('#processing').show();
            $('#myForm').ajaxSubmit({
                beforeSubmit: function () {},
                success: function (data) {
                    $('#processing').hide();
                    if (data.validation == 'error') {
                      $('#formMain').show();
                      $("#formErrors").show();
                        $('#formErrors').html(data.message);
                    } else if (data.validation == 'valid' && data.response == 'error') {
                        $('#formMain').show();
                        $("#formErrors").show();
                        $('#formErrors').html(data.message);
                    } else if (data.validation == 'valid' && data.response == 'success') {
                        $("#formErrors").show();
                        $('#formErrors').html(data.message);
                    }
                }
            });
        });
    });
    $(function () {
        $("input,textarea").on('click', function() {
        $("#formErrors").hide();
        })

        $("#buy_link_input").hide();
        $("input[name=can_download]").change(function (e) {
            $("#buy_link_input").toggle(!$(this).is(':checked'));
        });
    });
</script>
		<div class="section row entries">
		</div>
			<div id="content" class="content section row">
				<div class="col-md-8 bg-base col-lg-8 col-xl-9">
					<div class="ribbon ribbon-highlight">
						<ol class="breadcrumb ribbon-inner">
                            <li><a href="<?php echo base_url(); ?>">Home</a></li>
							<li><a href="<?php echo base_url('videos'); ?>">Videos</a></li>
							<li>Add Video</li>
						</ol>
					</div>
					
					<header class="page-header">
						<h3 class="page-title">Add Video</h3>
					</header>
					<article class="entry style-single type-post">
						<div class="entry-content">
        <div id="processing">
            <div class="spinner">
              <div class="rect1"></div>
              <div class="rect2"></div>
              <div class="rect3"></div>
              <div class="rect4"></div>
              <div class="rect5"></div>
                Processing...
            </div>
        </div>
            <div id="formErrors" style="color:red;font-weight:bold;text-align:center"></div>
            <div id="formMain">
 				<form method="post" action="<?php echo base_url('videos/upload'); ?>" enctype="multipart/form-data" id="myForm">
                    <div class="row">
                        <div class="col-sm-6">
                         Video Title
                            <?php echo form_input($form_video_title); ?>
                        </div>

                        <div class="col-sm-6">
                            Video Link (YouTube or VIMEO)
                            <?php echo form_input($form_video_url); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            Description
                            <?php echo form_textarea($form_video_description); ?>
                            <span class="charsleft"></span>
                        </div>
                    </div>
                    <div class="row" style="padding-top:5px">
                        <div class="col-sm-12">
                    <a href="<?php echo base_url('manage/videos') ?>" type="button" id="cancel-btn" class="btn btn-danger">Cancel</a>
                    <button type="submit" id="submit-btn" class="btn btn-danger">Submit</button>  
                        </div>
                    </div>
            <?php echo form_close(); ?>
            </div>

						</div>
					</article>
					

				</div><!--/.col-md-8.col-lg-8.col-xl-9-->