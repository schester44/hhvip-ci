<script type="text/javascript">
    $(function () {
        $('#cancel-btn').click(function(e) {
            e.preventDefault();
            window.location.replace("<?php echo base_url('backend/blog'); ?>");
        });

        $('#submit-btn').click(function (e) {
            e.preventDefault();

            //update post_content element before submitting form
            for ( instance in CKEDITOR.instances )
            {
                CKEDITOR.instances[instance].updateElement();
            }

            $('#updatePost').ajaxSubmit({
                beforeSubmit: function () {},
                success: function (data) {
                    if (data.validation == 'error') {
                        $("#post-info").show();
                        $('#post-info').html(data.message);
                    } else if (data.validation == 'valid' && data.response == 'error') {
                        $("#post-info").show();
                        $('#post-info').html(data.message);
                    } else if (data.validation == 'valid' && data.response == 'success') {
                        $("#post-info").show();
                        $("#post-info").html("Post Updated. <a href='<?php echo base_url('b'); ?>/" + data.post_details.category + "/" + data.post_details.url + "' title='" + data.post_details.title + "'>Click Here to View</a>");
                    }
                }
            });
        });
    });

    $(function () {
        $('#modal-delete-btn').click(function (e) {
            e.preventDefault();
            $('#deletePost').ajaxSubmit({
                beforeSubmit: function () {},
                success: function (data) {
                    if (data.response == 'error') {
                        $("#modal-error").show();
                        $('#modal-error').html(data.message);
                    } else if (data.response == 'success') {
                        window.location.replace("<?php echo base_url('backend/blog'); ?>");
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
        <li><a href="<?php echo base_url(); ?>">Home</a></li>
        <li><a href="<?php echo base_url('backend'); ?>">Backend</a></li>
        <li><a href="<?php echo base_url('backend/blog'); ?>">Blog</a></li>
        <li class="active">Edit Post</li>
    </ol>
</div>

<div id="content" class="content section row">
    <div class="col-md-8 bg-base col-lg-8 col-xl-9">
    <h1 class="song-subsections-heading" style="color:#fa8900">Edit Post</h1>
<div id="post-info" class="alert alert-info" style="display:none"></div>
<form method="post" action="<?php echo base_url('backend/blog/update/post'); ?>" enctype="multipart/form-data" id="updatePost">
        <?php echo form_input($form_id); ?>
        <?php echo form_input($form_title); ?>
        <?php echo form_textarea($form_content); ?> 
        <?php echo form_input($form_video); ?>

            <h4 style="margin-bottom:0">Select A Category</h4>
            <?php if (isset($categories)): ?>
            <ol style="list-style:none;margin-left:-25px">
                <?php foreach ($categories as $key => $cat): ?>
                <?php  $checked = (($post->category === $cat->id) ? 'CHECKED' : NULL); ?>

                    <li><input type="radio" name="category" value="<?php echo $cat->id; ?>" style="margin:5px" <?php echo $checked ?>> <?php echo ucfirst(str_replace('-', ' ', $cat->title)); ?></li>
                <?php endforeach ?>
            </ol>
            <?php endif ?>


    <div style="text-align:right">
        <button class="btn btn-sm btn-danger" style="margin:5px;margin-right:-3px" id="delete-btn" data-toggle="modal" data-target="#delete-modal">Delete Post</button>
        <button class="btn btn-sm btn-primary" style="margin:5px;margin-right:-3px">Cancel</button>
        <button class="btn btn-sm btn-primary" style="margin:5px;margin-right:-3px" id="submit-btn">Update</button> 

    </div>
</form>
    </div> <!--/.col-md-8.col-lg-8.col-xl-9-->

<?php $this->load->view('admin/sidebar'); ?>



<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Delete <?php echo $post->title; ?></h4>
      </div>
      <div class="modal-body">
        <p style="text-align:center">You are about to delete the following post:</p>
        <h3 style="text-align:center"><?php echo $post->title ?></h3>
        <p style="color:red;text-align:center;font-weight:bold">ARE YOU ABSOLUTELY SURE? THERE IS NO UNDO BUTTON.</p>
            <form method="post" action="<?php echo base_url('backend/blog/delete/post'); ?>" enctype="multipart/form-data" id="deletePost">
                <input type="hidden" name="id" value="<?php echo $post->id; ?>">
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="modal-delete-btn">Confirm Delete</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script>
    CKEDITOR.replace('post_content');
</script>'