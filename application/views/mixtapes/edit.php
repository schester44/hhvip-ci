<script src="<?php echo base_url('resources/vendor/jquery.blockUI.js') ?>"></script>
<script type="text/javascript">
    $(document).ready(function(){

        $("#mixtapeDetails").hide();
        $("#errorMsg").hide();

        //valid youtube
        function ytVidId(url) {
          var p = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
          return (url.match(p)) ? RegExp.$1 : false;
        }

        var tape_published = '<?php echo $mixtape->published_date; ?>';
        var tape_fileName = '<?php echo $mixtape->file_name; ?>';

        if ($("input[name=tape_video]").val() !== '') {
            $("input[name=tape_video]").val('https://www.youtube.com/watch?v=' + '<?php echo $mixtape->tape_video; ?>');
        }

        var tape_video    = $("input[name=tape_video]").val();

        $('#tape_video').keyup(function(){
        // no need to reselect on the input, just use "this"
        tape_video = $(this).val(); // initialization in an inner scope
         });

        if (tape_published === 'incomplete' || tape_fileName !== '') {
            $('#uploaderwrapper').hide();
            $("#mixtapeDetails").show();
            $("#pageTitle").html('Edit Mixtape Details');
        }

        $('#showUF').on('click',function() {
            $("#mixtape-overwrite").toggle();
            $('#uploaderwrapper').toggle('medium');
             if($(this).text() == 'Hide File Uploader'){
                 $(this).text('Show File Uploader');
               } else {
                   $(this).text('Hide File Uploader');
               }
            // will need to toggle HTML to 'SHOW' if upload form is hidden
        });


        var uploader_params = {
            runtimes: 'html5,flash,silverlight',
            url: '<?php echo base_url("upload/mixtape_to_server"); ?>',
            max_file_size: '400mb',
            multipart: true,
            multipart_params: {
                "Amazon S3" : "value1",
                "Amazon S3_param2" : "value2"
            },

            file_data_name: 'file',
            multiple_queues: false,
            filters : [
                {title : "Archive of Album (.zip)", extensions : "zip"},
                {title : "Archive of Album (.rar)", extensions : "rar"},
            ],

            flash_swf_url : '<?php echo base_url("resources/vendor/plupload/Moxie.swf"); ?>',
            silverlight_xap_url : '<?php echo base_url("resources/vendor/plupload/Moxie.xap"); ?>'
        }; //params

        $("#uploader").data('uploader_params', uploader_params);

        var $inputs = $('input.uk-input[type="file"]');

        $inputs.each(function(index, element) {
            new UploadKit(element);
        });

    // unblock when ajax activity stops 
    $(document).ajaxStop($.unblockUI); 

        $('.uk-input').bind(UKEventType.FileUploaded, function(evt){ 
                        $.blockUI({ message: '<h1 style="text-align:center;"><img src="<?php echo base_url("resources/img/busy.gif"); ?>" height="64px" width="64px" /><br />Processing Mixtape<br />Please Wait</h1>' });
            $.ajax({
                cache: false,
                dataType: 'json',
                data: {id: evt.file.id, response: evt.response.response, size: evt.file.size, name: evt.file.name, mid: '<?php echo $mixtape->id; ?>',slug: '<?php echo $mixtape->tape_url; ?>'},
                url: "/upload/publish_mixtape_upload/",
                type: 'POST',
                success: function(response) {
                    if (response.validation === "error") {
                         $("#errorMsg").show();
                         $('#errorMsg').html(response.message);
                    } else {
                        $("#errorMsg").hide();
                        $("#mixtapeDetails").show();
                        $("#uploaderwrapper").fadeOut('slow', function() {
                            $("#songlistwrapper").html('<div class="alert alert-success">Mixtape Uploaded. Click <a href="<?php echo base_url("mixtape/" . $mixtape->username. "/" . $mixtape->tape_url); ?>" title="View Mixtape" target="new">HERE</a> to View</div>');
                        });
                        $("#pageTitle").html('Edit Mixtape Details');
                        location.reload(); //temp sorting fix
                    }
                }
            });
        });


        $('#update-btn').click(function (e) {
            e.preventDefault();
        if (ytVidId(tape_video) || tape_video === '') {
            $('#mixtapeUpdate').ajaxSubmit({
                beforeSubmit: function () {},
                success: function (data) {
                    if (data.validation == 'error') {
                        $("#errorMsg").show();
                        $('#errorMsg').html(data.message);
                    } else if (data.validation == 'valid' && data.response == 'error') {
                        $("#errorMsg").show();
                        $('#errorMsg').html(data.message);
                    } else if (data.validation == 'valid' && data.response == 'success') {
                        $("#errorMsg").show();
                        $('#errorMsg').html(data.message);
                    }
                }
            });
            } else {
                    $("#errorMsg").show();
                    $('#errorMsg').html('The video url entered is not a valid YouTube video link.');
            }//check youtube
        });//ajax update

    $('input').on('keyup change',function(){
      $("#errorMsg").hide();

    });

    });
</script>

<script>
  $(function() {
    $( "#sortable" ).sortable({
        update: function (event, ui) {
        var data = $(this).sortable('serialize');
        $.ajax({
            data: data,
            type: 'POST',
            url: '/manage/mixtape/sort-order/<?php echo $mixtape->id; ?>'
        });
      }
    });
    $( "#sortable" ).disableSelection();
  });


$(function() {
  $.inlineEdit({
    editArtist: '/manage/mixtape/update-track/<?php echo $mixtape->id; ?>/artist/',
    editTitle: '/manage/mixtape/update-track/<?php echo $mixtape->id; ?>/title/'
  }); 
});
</script>
<div id="content" class="content section row">
    <div class="col-md-12 bg-base col-lg-12 col-xl-12">

        <div class="ribbon ribbon-highlight">
            <ol class="breadcrumb ribbon-inner">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li><a href="<?php echo base_url('manage'); ?>">Account</a></li>
                <li><a href="<?php echo base_url('manage/mixtapes'); ?>">Mixtapes</a></li>
                <li>Edit Mixtape</li>
            </ol>
        </div>
       <header class="page-header">
            <h1 title="entry-title" id="pageTitle" class="page-title">Upload Your .ZIP/RAR File</h1> 
            <span style="color:#999"><?php if ($mixtape->status !== 'published') {
    echo 'Step 2 of 2';
} ?>  </span>
        </header>

<div class="alert alert-danger alert-dismissible" id="errorMsg" role="alert">
</div>
        <article class="entry style-single type-post">
            <div class="entry-content">
            
            <div class="alert alert-danger" id="mixtape-overwrite" style="display:none">WARNING: UPLOADING A NEW ZIP FILE WILL DELETE AND OVERWRITE <strong>ALL</strong> EXISTING TRACKS.</div>
            <!-- progress bar-->
            <div class="row" id="mixtapeProgressBar">
              <div class="mixtapeProgressBar">
                <div class="uploading-progress">
                        <span class="mixtape-upload-percentage"></span>
                  <div class="uploading-progress-bar" data-value="0" data-max="100">
                  </div>
                </div>
              </div>  
            </div>
             <!-- ./progress bar-->

   <div id="uploaderwrapper">
       
  <form method="post" action="#">
    <input type="file" class="uk-input" name="attachments" id="uploader" data-upload-url="<?php echo base_url('upload/mixtape_to_server') ?>" data-max-file-size="400mb"/>
  </form>
   </div>     
   <div id="songlistwrapper"></div> 
  
  <div id="mixtapeDetails">
        <div class="" id="showUF">Show File Uploader</div>
                    <div class="alert alert-info">     
            <strong>Tape Link:</strong> 
                <a href="<?php echo base_url('mixtape/'.$mixtape->username.'/'.$mixtape->tape_url); ?>" title="<?php echo htmlspecialchars($mixtape->tape_artist.' - '.$mixtape->tape_title, ENT_QUOTES); ?>">
                    <?php echo base_url( 'mixtape/'.htmlspecialchars($mixtape->username, ENT_QUOTES).'/'.$mixtape->tape_url); ?></a>
            </div>
                <form method="post" action="<?php echo base_url('manage/mixtape/update'); ?>" enctype="multipart/form-data" id="mixtapeUpdate">
                  
                    <input type="hidden" id="id" name="id" value="<?php echo $mixtape->id; ?>">
                    <input type="hidden" id="user_id" name="user_id" value="<?php echo $this->ion_auth->user()->row()->id; ?>">

                   <div class="row">
                        <div class="col-sm-6">
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
                            Download: 
                            <input type='hidden' value='no' name='can_download'>
                            <?php echo form_checkbox($form_can_download); ?>  Allow 
                            </div>                     
                            <div class="form-group">                     
                                Album Art 
                            <?php if ($mixtape->extra_image != '') {
                                 echo '<span style="color:red;font-size:10px">WARNING: This will overwrite the existing images</span><br />';
                             } ?>
                              <input type="file" name="image_file">
                             </div>
                                <img src="<?php echo tape_img($mixtape->username, $mixtape->tape_url, $mixtape->tape_image, 300); ?>">
                        </div>
                        <div class="col-sm-6"> 
                          <div id="sortTracksContainer">
    <h3 style="border-bottom:none;margin-bottom:-10px">Order & Rename Tracks</h3>

  <span style="font-weight:bold">TIP: </span><span style="color:#999">Drag and drop tracks to reorder. Double click artist name and/or title to rename.</span>
     
      <div id="sortTracks" class="sortTracksWrapper" style="padding-top:15px">
                  <ol id="sortable">
        <?php foreach ($tracks as $key => $track): ?>
             <li class="sortTracksItem" id="track_<?php echo $track->id; ?>">
              <span class="editableSingle editArtist id<?php echo $track->id; ?>" id="artist_<?php echo $mixtape->id; ?>_<?php echo $track->id; ?>" style="color:#fa8900"><?php echo $track->song_artist; ?></span> - 
              <span class="editableSingle editTitle id<?php echo $track->id; ?>" id="title_<?php echo $mixtape->id; ?>_<?php echo $track->id; ?>"><?php echo $track->song_title; ?></span></li>
          <?php endforeach ?>
          </ol>
      </div>
</div>

                    
                        </div>
                    </div>

        <a href="<?php echo base_url('manage/mixtapes') ?>" type="button" id="cancel-btn" class="btn btn-danger">Cancel</a>
        <button type="submit" id="update-btn" class="btn btn-danger">Save/Update</button>
        <span class="pull-right orangehref"><a href="<?php echo base_url('mixtape/' . $mixtape->username . '/' . $mixtape->tape_url); ?>" title="View Mixtape">VIEW MIXTAPE</a></span>
                    <?php echo form_close(); ?><br />
            </div><!--mixtapebody-->
<br />
        </article>
   </div>
        <!--/.col-md-8.col-lg-8.col-xl-9-->