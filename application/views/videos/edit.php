<script type="text/javascript">
    $(function () {
        $('#processing').hide();
        $('#update-btn').click(function (e) {
            e.preventDefault();
            $('#processing').show();
            $('#myForm').ajaxSubmit({
                beforeSubmit: function () {},
                success: function (data) {
                        $('#processing').hide();
                    
                    if (data.validation == 'error') {
                        $("#formErrors").show();
                        $('#formErrors').html(data.message);
                    } else if (data.validation == 'valid' && data.response == 'error') {
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
</script>

<div id="content" class="content section row">

    <div class="col-md-8 bg-base col-lg-8 col-xl-9">

        <div class="ribbon ribbon-highlight">
            <ol class="breadcrumb ribbon-inner">
                <li><a href="<?php echo base_url(); ?>">Home</a>
                </li>
                <li><a href="<?php echo base_url('manage/videos'); ?>">Manage Videos</a>
                </li>
                <li>Edit Song</li>
            </ol>
        </div>
        <header class="page-header">
            <div id="infoMessage">
                <?php echo $this->session->flashdata('message');;?></div>
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
            <h2 class="page-title">Edit Video (<?php echo $video->id; ?>)</h2>
            <h3><div id="formErrors" style="color:red;"></div></h3>
        </header>
        <article class="entry style-single type-post">

            <div class="entry-content">
                <form method="post" action="<?php echo base_url('manage/video/update'); ?>" enctype="multipart/form-data" id="myForm">
                    <input type="hidden" id="vid" name="vid" value="<?php echo $video->id; ?>">
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
                    <button type="submit" id="update-btn" class="btn btn-danger">Submit</button>  
                        </div>
                    </div>
            <?php echo form_close(); ?>
            </div>

        </article>

    </div>
    <!--/.col-md-8.col-lg-8.col-xl-9-->