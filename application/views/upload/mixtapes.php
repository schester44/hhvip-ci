<script type="text/javascript">
    $(document).ready(function(){

        //valid youtube
        function ytVidId(url) {
          var p = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
          return (url.match(p)) ? RegExp.$1 : false;
        }

        var tape_video    = $("input[name=tape_video]").val();


        $('#tape_video').keyup(function(){
        // no need to reselect on the input, just use "this"
        tape_video = $(this).val(); // initialization in an inner scope
         });

        $("#buy_link_input").hide();
        $("input[name=can_download]").change(function (e) {
            $("#buy_link_input").toggle(!$(this).is(':checked'));
        });

        $("#errorMsg").hide();

        $('#submit-btn').click(function (e) {
            e.preventDefault();
             if (ytVidId(tape_video) || tape_video === '') {
                $('#mixtapeInit').ajaxSubmit({
                    beforeSubmit: function () {},
                    success: function (data) {
                        $('#processing').hide();
                        if (data.validation == 'error') {
                            $("#errorMsg").show();
                            $('#errorMsg').html(data.message);
                        } else if (data.validation == 'valid' && data.response == 'error') {
                            $("#errorMsg").show();
                            $('#errorMsg').html(data.message);
                        } else if (data.validation == 'valid' && data.response == 'success') {
                           window.location.href = "<?php echo base_url('manage/mixtape') ?>/" + data.song.id + "/edit";                         
                        }
                    }
                });
            } else {
                    $("#errorMsg").show();
                    $('#errorMsg').html('The video url entered is not a valid YouTube video link.');
            }//check youtube
        });

        $('input').on('keyup change',function(){
          $("#errorMsg").hide();
        });
        
    });
</script>
    <div id="content" class="content section row">
    <div class="col-md-8 bg-base col-lg-8 col-xl-9">

        <div class="ribbon ribbon-highlight">
            <ol class="breadcrumb ribbon-inner">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li>Upload Mixtape</li>
            </ol>
        </div>
        <header class="page-header">
            <h2 class="page-title">Upload A Mixtape</h2>
<span style="color:#999">Step 1 of 2</span>

        </header>
        <article class="entry style-single type-post">
            <div class="entry-content">
            <div id="errorMsg" class="alert alert-danger"></div>
            <div class="alert alert-info" style="text-align:center;font-weight:bold;color:#444">Enter only the mixtape meta details here. You will upload the mixtape on the next page.</div>
                <form method="post" action="<?php echo base_url('upload/mixtape/init'); ?>" enctype="multipart/form-data" id="mixtapeInit">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                            Artist Name
                            <?php echo form_input($form_artist); ?>
                            </div>
                            <div class="form-group">
                             Mixtape/Album Name
                             <?php echo form_input($form_tape_name); ?>
                            </div>

                            <div class="form-group">
                            Youtube/Vimeo Link
                            <?php echo form_input($form_video); ?>
                            </div>

                            <div class="form-group">
                            Description
                            <?php echo form_textarea($form_description); ?>
                            </div>

                            <div class="form-group">
                            Album Art:
                                    <input type="file" name="image_file" accept="image/jpg, image/gif, image/png, image/jpeg">
                           <span style="font-size:10px"> TIP: If no album art is uploaded, HHVIP will use any album art found inside the zip file.</span>
                            </div>
                            <div class="form-group">                            
                                Alow Download: 
                                <input type='hidden' value='no' name='can_download'>
                                 <?php echo form_checkbox('can_download', 'yes', TRUE); ?>  Allow 
                            </div>
                            <div id="buy_link_input" class="form-group">
                            Buy Link:
                                <?php echo form_input($form_buy_link); ?>
                           </div>

                        </div>
                    </div>

                    <a href="<?php echo base_url('manage') ?>" type="button" id="cancel-btn" class="btn btn-danger">Cancel</a>
                    <button type="submit" id="submit-btn" class="btn btn-warning">Next</button>



            <?php echo form_close(); ?>
            </div> <!-- /entry-content -->
        </article>

    </div>
    <!--/.col-md-8.col-lg-8.col-xl-9-->