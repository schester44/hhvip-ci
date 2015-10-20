<style type="text/css">
    #image_file {
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

$(document).ready(function(){
    $('#advanced-settings').hide();
    var ab = $('#advanced-settings-btn').html();
    $('#advanced-settings-btn').on('click', function(e){
        e.preventDefault();
        $('#advanced-settings').toggle('slow', function(){
            if (ab == 'Show Advanced Settings') {
                $('#advanced-settings-btn').html('Hide Advanced Settings');
                ab = $('#advanced-settings-btn').html();
            } else if(ab == 'Hide Advanced Settings') {
                $('#advanced-settings-btn').html('Show Advanced Settings');
                ab = $('#advanced-settings-btn').html();
            }
        });
    });
});

    $(function () {

        var visibility = '<?php echo $song->visibility ?>';
        $('#make_privateAlert').hide();


        if (visibility == 'public') {
          $("input[name=make_private]").change(function(e) {
            $('#make_privateAlert').toggle();
          });
        };


        //hide defualt select file button
        document.getElementById('get_file').onclick = function() {
            document.getElementById('image_file').click();
        };
        $('input[type=file]').change(function (e) {

            var image_file = $('#image_file').val();
            image_file = image_file.replace("C:\\fakepath\\", "");
            $('#show-filename').show();
            $('#show-filename').html('<span style="font-weight:bold">ALBUM ART: </span>' + image_file);
            $('#get_file').removeClass('btn-warning').addClass('btn-success');
        });

        $('#processing').hide();
        $('#formErrors').hide();
        $('#update-btn').click(function (e) {
            e.preventDefault();

                var sc_url = '<?php echo $soundcloud_url; ?>';
                    sc_url = sc_url.substr(sc_url.indexOf('://')+3);

                if (sc_url.length >= 1) {               
                    if ($('#form_soundcloud_url').val() == '') {
                        alert('This is a SoundCloud upload. You need a valid SoundCloud link to update. Please check your inputs bruh.')
                        return false;
                    };

                    var a = 'http://soundcloud.com';
                    var b = 'https://soundcloud.com';
                    var c = 'https://www.soundcloud.com';
                    var d = 'http://www.soundcloud.com';
                    var str = $('#form_soundcloud_url').val();

                    if (str.indexOf(a) >= 0 || str.indexOf(b) >= 0 || str.indexOf(c) >= 0 || str.indexOf(d) >= 0) {
                    } else {
                    alert('The SoundCloud song URL does not appear to valid');
                    return false;
                    }
                };

                if ($('#song_url').val() == '') {
                    alert('The Song URL field cannot be left blank\nWe recommend something short and concise.');
                    return false;
                };

            $('#processing').show();
            $('#myForm').ajaxSubmit({
                beforeSubmit: function () {},
                success: function (data) {
                    $('#processing').hide();
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                    
                    if (data.validation == 'error') {
                        $('#formErrors').html(data.message).addClass('alert alert-danger').show();
                    } else if (data.validation == 'valid' && data.response == 'error') {
                        $('#formErrors').html(data.message).addClass('alert alert-danger').show();
                    } else if (data.validation == 'valid' && data.response == 'success') {
                        $('#formErrors').html(data.message).addClass('alert alert-danger').show();
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

<?php if ($song->can_download == 'no') { ?>
<script type="text/javascript">
    $(function () {
        $("#buy_link_input").show();
    });
</script>
<?php } ?>
<div id="content" class="content section row">

    <div class="col-md-8 bg-base col-lg-8 col-xl-9">

        <div class="ribbon ribbon-highlight">
            <ol class="breadcrumb ribbon-inner">
                <li><a href="<?php echo base_url(); ?>">Home</a>
                </li>
                <li><a href="<?php echo base_url('manage'); ?>">Account</a>
                </li>
                <li><a href="<?php echo base_url('manage/songs'); ?>">Songs</a>
                </li>
                <li>Edit Song</li>
            </ol>
        </div>
        <header class="page-header">
            <div id="infoMessage"><?php echo $this->session->flashdata('message');;?></div>
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
            <h2 class="page-title">Edit Song Details</h2>
            <div class="alert" style="padding:3px;margin-top:-10px">     
            <strong>Song:</strong> 
                <a href="<?php echo base_url('song/'.$song->username.'/'.$song->song_url); ?>" title="<?php echo $song->song_artist.' - '.$song->song_title ?>">
                    <?php echo base_url( 'song/'.$song->username.'/'.$song->song_url); ?></a>
            </div>
            <div id="formErrors" style="padding:3px"></div>
        </header>
        <article class="entry style-single type-post">

            <div class="entry-content">
                <form method="post" action="<?php echo base_url('manage/song/update'); ?>" enctype="multipart/form-data" id="myForm">

                    <input type="hidden" id="song" name="song" value="<?php echo $song->file_uid; ?>">
                    <input type="hidden" id="song_id" name="song_id" value="<?php echo $song->song_id; ?>">
                    <input type="hidden" id="user_id" name="user_id" value="<?php echo $song->user_id; ?>">
                    <input type="hidden" id="file_name" name="file_name" value="<?php echo $song->file_name; ?>">

                    <div class="row">
                        <div class="col-sm-6">
                            Main Artist
                            <?php echo form_input($form_artist); ?>
                        </div>

                        <div class="col-sm-6">
                            Featuring
                            <?php echo form_input($form_featuring); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            Track Title
                            <?php echo form_input($form_title); ?>
                        </div>
                        <div class="col-sm-6">
                            Producer
                            <?php echo form_input($form_producer); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            Album/Mixtape
                            <?php echo form_input($form_album); ?>
                        </div>
                        <div class="col-sm-6">
                            YouTube Link
                            <?php echo form_input($form_video); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            Upload Album Art
                            <br />
                                <div id="pc-upload">
                                    <a class="btn btn-default" id="get_file" style="width:100%;margin-bottom:5px">Select Album Art</a>
                                    <input type="file" name="image_file" id="image_file" accept=".jpg,.png,.gif,.jpeg">
                                    <span id="show-filename" style="display:none"></span>
                                </div>
                            <img src="<?php echo song_img($song->username, $song->song_url, $song->song_image, 150); ?>">
                        </div>
                        <div class="col-sm-6">
                            Description
                            <?php echo form_textarea($form_description); ?>
                            <span class="charsleft"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <button class="btn btn-default" style="width: 100%;margin-top:10px" id="advanced-settings-btn">Show Advanced Settings</button>
                        
                        <div style="padding-top:10px; font-size:16px" id="advanced-settings">
                            HHVIP URL
                            <div class="input-group input-group-sm" style="padding-bottom:10px">
                             <span class="input-group-addon" id="sizing-addon1">hiphopvip.com/song/<?php echo $song->username; ?>/</span>
                            <?php echo form_input($form_url); ?>
                            </div>
                            <?php if ($song->external_source == 'soundcloud'): ?> 
                            <div id="soundcloud_url" style="padding-bottom:10px">
                                Soundcloud Song URL:
                                <?php echo form_input($form_soundcloud_url) ?>
                            </div>
                            <?php endif ?>
                            <div id="buy_link_input">
                                Buy Link:
                                <?php echo form_input($form_buy_link); ?>
                            </div>
                            <div style="padding-bottom:10px">
                               Allow Downloads:
                                <?php if ($song->can_download == 'no'): ?>
                                    <?php echo form_checkbox( 'can_download', 'yes', FALSE); ?>
                                <?php else: ?>
                                    <input type='hidden' value='no' name='can_download'>
                                    <?php echo form_checkbox( 'can_download', 'yes', TRUE); ?>                            
                                <?php endif ?>
                             </div>

                                <div id="make_privateForm">
                                    Make Private:
                                <?php if ($song->visibility == 'unlisted'): ?>
                                    <?php echo form_checkbox(array('name'=>'make_private','id'=>'make_private','value'=> 'yes','checked'=>TRUE)); ?>
                                <?php else: ?>
                                    <input type='hidden' value='no' name='make_private'>
                                    <?php echo form_checkbox(array('name'=>'make_private','id'=>'make_private','value'=> 'yes','checked'=>FALSE)); ?>                                
                                <?php endif ?>
                                       <span id="make_privateAlert" style="display:block;font-size:0.8em;color:red;line-height:1.2em">Setting this to private means only you and those you share the link with will be able to see/play the song. The song will not be listed in our charts or on your profile page.</span>
                                </div>
                            </div>



                        </div>

                    </div>
                    <div class="row" style="padding-top:10px;padding-bottom:10px">
                        <div class="col-sm-12">
                                  <a href="<?php echo base_url('manage/songs') ?>" type="button" id="cancel-btn" class="btn btn-danger">Cancel</a>
                            <button type="submit" id="update-btn" class="btn btn-warning">Update</button>
                        
                        </div>
                    </div>
            </div>

            <?php echo form_close(); ?>

        </article>

    </div>
    <!--/.col-md-8.col-lg-8.col-xl-9-->