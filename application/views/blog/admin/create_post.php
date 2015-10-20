<style type="text/css">
    #featured_image {
        display: none;
    }

    #show-filename {
        clear:both;
        display:block;
        font-size:10px;
        padding:5px;
    }
</style>
<script type="text/javascript">
    $(function () {
        $('#cancel-btn').click(function(e) {
            e.preventDefault();
            window.location.replace("<?php echo base_url('backend/blog'); ?>");
        });

        //hide defualt select file button
        document.getElementById('get_file').onclick = function() {
            document.getElementById('featured_image').click();
        };
        $('input[type=file]').change(function (e) {

            var featured_image = $('#featured_image').val();
            featured_image = featured_image.replace("C:\\fakepath\\", "");

            $('#show-filename').html('<span style="font-weight:bold">SELECTED: </span>' + featured_image);
            $('#get_file').removeClass('btn-primary').addClass('btn-warning');
            $('#show-filename').css({'margin-bottom':'-25px'});
        });

        $('#submit-btn').click(function (e) {
            e.preventDefault();
        
        //not a youtube video
        var youtube_Set = $('#video').val().match(/watch\?v=([a-zA-Z0-9\-_]+)/);

        if ($('#video').val() != '' && !youtube_Set)
        {
            $("#post-info").show();
            $('#post-info').removeClass('alert-info').addClass('alert-danger');
            $('#post-info').html('The Video URL does not appear to be a valid YouTube URL.');
            $("html, body").animate({ scrollTop: 0 }, "slow");
            return false;
        }

        if ($('input[type=file]').val() == '' ) {
            $("#post-info").show();
            $('#post-info').removeClass('alert-info').addClass('alert-danger');
            $('#post-info').html('You need to set a featured image before you can continue');
            $("html, body").animate({ scrollTop: 0 }, "slow");
            return false;
        };

            //update post_content element before submitting form
            for ( instance in CKEDITOR.instances )
            {
                CKEDITOR.instances[instance].updateElement();
            }

            $('#newPost').ajaxSubmit({
                beforeSubmit:  function(){},
                success: function (data) {
                    if (data.validation == 'error') {
                        $("#post-info").show();
                        $('#post-info').html(data.message);
                    } else if (data.validation == 'valid' && data.response == 'error') {
                        $('#post-info').removeClass('alert-info').addClass('alert-danger');
                        $("#post-info").show();
                        $('#post-info').html(data.message);
                    } else if (data.validation == 'valid' && data.response == 'success') {
                        $('#post-info').removeClass('alert-danger').addClass('alert-info');
                        $("#post-info").show();
                        $("#post-form").hide();
                        $("#post-info").html("Post Created. <a href='<?php echo base_url('b'); ?>/" + data.post_details.category + "/" + data.post_details.url + "' title='" + data.post_details.title + "'>Click Here to View</a> | <a href='<?php echo base_url('backend/blog/add'); ?>' title='Add New Post'>Add New Post</a>");
                    }
                }
            });
        });
    });
    $(function () {

        $("#buy_link_input").hide();
        $("input[name=can_download]").change(function (e) {
            $("#buy_link_input").toggle(!$(this).is(':checked'));
        });
    });
</script>

<div class="section row entries">
         <article class="entry col-xs-12 col-sm-12">
        </article>
</div>
<div class="ribbon ribbon-highlight">
    <ol class="breadcrumb ribbon-inner">
        <li><a href="<?php echo base_url(); ?>">Home</a>
        </li>
        <li><a href="<?php echo base_url('backend'); ?>">Manage</a></li>
        <li><a href="<?php echo base_url('backend/blog'); ?>">Blog</a></li>
        <li class="active" title="">New Post</li>
    </ol>
</div>

<div id="content" class="content section row">
    <div class="col-md-8 bg-base col-lg-8 col-xl-9">
    <h1 class="song-subsections-heading" style="color:#fa8900">Add New Post</h1>

    <div id="post-info" class="alert alert-info" style="display:none"></div>
    <div id="post-form">
        <form method="post" action="<?php echo base_url('backend/blog/add/post'); ?>" enctype="multipart/form-data" id="newPost">
            <?php echo form_input($form_title); ?>
            <hr />
            <a class="btn btn-primary" id="get_file">Set Featured Image</a>
                <input type="file" id="featured_image" name="featured_image" accept=".jpg,.png,.gif,.jpeg">
            <a class="btn btn-primary">Set Post URL</a>

            <span id="show-filename"></span>

            <hr />
            <?php echo form_textarea($form_content); ?> 
            <?php echo form_input($form_video); ?>

            <h3 style="margin-bottom:0">Select A Category</h3>
            <?php if (isset($categories) && !empty($categories)): ?>
            <ol style="list-style:none;margin-left:-25px">
                <?php foreach ($categories as $key => $cat): ?>
                    <li><input type="radio" name="category" value="<?php echo $cat->id; ?>" style="margin:5px"> <strong><?php echo ucfirst(str_replace('-', ' ', $cat->title)); ?></strong></li>
                <?php endforeach ?>
            </ol>
            <?php else: ?>
                <h4>No existing categories.<br /><a href="<?php echo base_url('backend/blog/categories') ?>" class="btn btn-xs btn-primary" style="margin-top:10px;margin-left:10px">Create A Category</a></h4>
            <?php endif ?>

            <h3 style="margin-bottom:0">Post Visiblity</h3>

            <ol style="list-style:none;margin-left:-25px">
                    <li><input type="radio" name="access" value="public" style="margin:5px" CHECKED> <strong>Public</strong> - (Blog)</li>
                    <li><input type="radio" name="access" value="private" style="margin:5px"> <strong>Private</strong> - (Internal Docs)</li>
                    <li><input type="radio" name="access" value="unlisted" style="margin:5px"> <strong>Unlisted</strong> - (Tutorials, etc)</li>
            </ol>
            
            <hr>
            <div style="text-align:right">
                <button class="btn btn-sm btn-danger" style="margin:5px;margin-right:-3px" id="cancel-btn">Cancel</button>
                <button class="btn btn-sm btn-primary" style="margin:5px;margin-right:-3px" id="draft-btn">Save As Draft</button>
                <button class="btn btn-sm btn-primary" style="margin:5px;margin-right:-3px" id="submit-btn">Submit</button> 
            </div>
        </form>
    </div>

    </div> <!--/.col-md-8.col-lg-8.col-xl-9-->

<?php $this->load->view('admin/sidebar'); ?>

<script>
    CKEDITOR.replace( 'post_content' );
</script>