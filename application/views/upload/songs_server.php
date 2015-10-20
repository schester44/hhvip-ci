<script type="text/javascript">
$(document).ready(function(){

	var incomplete = <?php echo $incomplete; ?>;

	$("#formErrors").hide();
	$("#finish-upload-msg").hide();
	$("#song_list_songs").hide();

  $('#sc-submit').click(function(e){
      e.preventDefault();

  var schttpurl = 'http://soundcloud.com';
  var schttpsurl = 'https://soundcloud.com';
  var str = $('#scLink').val();
  if (str.indexOf(schttpurl) >= 0 || str.indexOf(schttpsurl) >= 0) {
        $('#scForm').ajaxSubmit({
          beforeSubmit: function() {
          },
          success: function(data) {
            $('.modal-body').show();
            $('#processing').hide();
            if (data.response == 'error') {
              alert('ERROR: ' + data.message);
            } else if (data.response == 'success') {
             addSongToList(data.song);
            } else {
              alert('UNKNOWN ERROR, Are you sure this is a valid SoundCloud song?');
            }
          $('#scForm').resetForm();
          }
        });
  } else {
    alert('ERROR: Not A Valid SoundCloud Link');
  }
});


	$("#uploader").pluploadQueue({
		// General settings
		runtimes : 'html5,flash,silverlight,html4',
		url : '<?php echo base_url("upload/upload_to_server") ?>',
		max_file_size: '30mb',
		rename : true,
		dragdrop: true,
		multipart: true,
		multipart_params : {
			"Amazon S3" : "value1",
			"Amazon S3_param2" : "value2"
		},
		file_data_name: 'file',
		multiple_queues: true,
		filters : [
		{title : "MP3 files", extensions : "mp3"}
		],
		flash_swf_url : '<?php echo base_url(VENDOR) ?>/plupload/Moxie.swf',
		silverlight_xap_url : '<?php echo base_url(VENDOR) ?>/plupload/Moxie.xap'
	});
  
  var uploader = $('#uploader').pluploadQueue();

	uploader.bind('FileUploaded', function (up,file,info) {
		$.ajax({
			cache: false,
			dataType: 'json',
			data: {id: file.id, response: info.response, size: file.size},
			url: "/manage/process/json",
			type: 'POST',
			success: function(response) {
				if(response.song && response.song.id) {
					addSongToList(response.song);
				} else {
					alert('Initial Upload Failed. Please try again');
				}
			}
		});

		if (uploader.files.length == (uploader.total.uploaded + uploader.total.failed)) {
			var obj = JSON.parse(info.response);
			var elem = document.getElementById("file_name");
			elem.value = (obj.cleanFileName);
		}
	});

	//push data to modal
	$('#songs').on('click', '.finish-upload', function (){

	var $tr  = $(this).parents('tr');
	var id   = $tr.data('id');
	//data is the data from the parent TR. addsongtolist pushes json to the TR so we can push the json to the form
	var data = $tr.data('data');
	var $sf = $("#song_form");

	$("input[name=song_uid]", $sf).val(id);
	$("input[name=file_name]", $sf).val(data.file);

  $("input[name=scimg]", $sf).val(data.scimg);

    $('#can_downloadForm').show();
    $('#scimgalert').hide();

  if (data.source == 'soundcloud') {
    $('#can_downloadForm').hide();
    if ($("input[name=scimg]").val().length || data.song_image) {
      $('#scimgalert').show();
      $('#scimgalert').html('<br />This will overwrite the SoundCloud Album art.').css({'color':'red','font-size':'10px'});      
    };
  };

	$("input[name=title]", $sf).val(data.title);
	$("input[name=album]", $sf).val(data.album);
	$("input[name=artist]", $sf).val(data.artist);
	$("input[name=featuring]", $sf).val(data.featuring);
	$("input[name=producer]", $sf).val(data.producer);
	$("textarea[name=description]", $sf).val(data.description);
	$("input[name=can_download]", $sf).attr('checked', 'checked');
	$("input[name=buy_link]", $sf).val('');
	//buy url hidden until toggle is
	$("#buy_link_input").hide();
	//open dialog box on click

	});

	$("input[name=can_download]").change(function(e) {
		$("#buy_link_input").toggle(!$(this).is(':checked'));
	});

	$("#buy_link_input input[name=buy_link]").change(function(e) {
		var txt = $.trim($(this).val());
		var url = '';

		if(txt === '') {
			return;
		}

		if(url == txt.match(/href=['"]([^'"]+)['"]/i)) {
			$(this).val(url[1]);
			txt = url[1];
		}

		if(!txt.match(/^https?:\/\//)) {
			$(this).val("http://" + txt);
			}
	});

	if(incomplete && incomplete.length) {
		for(var i = 0; i < incomplete.length; i++) {
		addSongToList(incomplete[i]);
		}
	}



	function addSongToList(song) {
		$("#no-songs-in-list").hide();  
		$("#song_list_songs").show();
		$("#finish-upload-msg").show();

    var songtitle = song.file.replace('HIPHOPVIP.mp3', ' ');
		songtitle = songtitle.replace(/\_/g, ' ');
		var date = song.date;
		var $tr = $("<tr id='song_"+song.id+"'></tr>");

		$tr.append("<td class='ui-widget-content' style='max-width:350px;overflow:hidden;text-overflow:ellipsis'>"+songtitle+"</td>");
		$tr.append("<td class='ui-widget-content'>"+date+"</td>");
		var actions = "<td class='actions ui-widget-content'>";
		actions += '<button class="btn btn-danger delete-btn" id="delete-btn" data-toggle="modal" data-target="#delete_modal">DELETE</button> ';
		actions += ' <button class="btn btn-success finish-upload" id="finish-upload" data-toggle="modal" data-target="#song_form">FINISH</button>';
		actions += "</td>";
		$tr.append(actions);

		$tr.data('id', song.id);
		$tr.data('data', song);
		$tr.appendTo("#song_list tbody");
	}

  $('#processing').hide();
	
  $('#submit-btn').click(function(e){
      e.preventDefault();
      $('#formMain').hide();
      $('#processing').show();
      $('#myForm').ajaxSubmit({
        beforeSubmit: function() {
        },
        success: function(data) {
          $('.modal-body').show();
          $('#processing').hide();
          if (data.validation == 'error') {
            $('#formMain').show();
            $("#formErrors").show();
              ga('send', 'event', 'Form', 'Upload Song','Validation Error');
            $('#formErrors').html(data.message);
          } else if (data.validation == 'valid' && data.response == 'error') {
              ga('send', 'event', 'Form', 'Upload Song','Response Error');
            $('#formMain').show();
            $("#formErrors").show();
            $('#formErrors').html(data.message);
          } else if (data.validation == 'valid' && data.response == 'success') {
              ga('send', 'event', 'Form', 'Upload Song','Success');
            $("#formErrors").hide();
            $('#formMain').show();
            $('#song_form').modal('hide');

            $("#song_"+data.song.song_id+" td.actions button").remove();
            $('<a href="<?php echo base_url("song/" . $user->username) ?>/' + data.song.song_url + '" class="btn btn-warning" title="View Song" target="_blank">View Song</a> ').appendTo("#song_"+data.song.song_id+" td:eq(2)");
          }
			
          $('#myForm').resetForm();
        }
      });
    });

		$('.delete-btn').click(function(e){
      e.preventDefault();
      
		var $tp  = $(this).parents('tr');
		var data = $tp.data('data');
		var $df = $("#delete_modal");

			$("input[name=song_id]", $df).val(data.song_id);
			$("input[name=song_uid]", $df).val(data.id);
			$("input[name=file_name]", $df).val(data.file);
      
      $('.confirm-delete-btn').click(function(e){
				e.preventDefault();
        var url = '<?php echo base_url("manage/song/delete") ?>';
        $.post(url,
               $('#deleteForm').serialize(),
          function(data, status, xhr) {
            if (data.validation == 'error') {
              $("#deleteformErrors").show();
              $('#deleteformErrors').html(data.message);
            } else if (data.validation == 'valid' && data.response == 'error') {
              $("#deleteformErrors").show();
              $('#deleteformErrors').html(data.message);
            } else if (data.validation == 'valid' && data.response == 'success') {
              $($tp).remove();
              $("#deleteformErrors").hide();
            $('#delete_modal').modal('hide');
            location.reload();
          }
         });
      });
    });
  
  var title = $('#title');
  var artist = $('#artist');
  var featuring = $('#featuring');
  $('#artisttarget').hide();
  $('#titlecontainer').hide();
  $('#featurecontainer').hide();
  $('#showTitle').hide();

  $('#song_form').on('hidden.bs.modal', function () {
    $("#artisttarget").empty();
    $("#titletarget").empty();
    $("#featuringtarget").empty();
    $('#artisttarget').hide();
    $('#featurecontainer').hide();
    $('#titlecontainer').hide();
    $('#showTitle').hide();
    $("#formErrors").hide();
  });

    $('#featuring').keyup(function(){
      $('#featurecontainer').hide();
      if (featuring.val() !== '') {
        $('#featurecontainer').show();
      }
      
      $('#featuringtarget').html($(featuring).val());
    });

    $('#artist').keyup(function(){
      $('#showTitle').show();
      $('#artisttarget').show();
      $('#artisttarget').html($(artist).val());
    });

    $('#title').keyup(function(){
      $('#showTitle').show();
      $('#titlecontainer').hide();
      if (title.val() !== '') {
        $('#titlecontainer').show();
      }
      $('#titletarget').html($(title).val());
    });

});
</script>

<div class="section row entries">
    <article class="entry col-xs-12 col-sm-12">
    </article>

</div>

<div id="content" class="content section row">
    <div class="col-md-8 bg-base col-lg-8 col-xl-9">

        <div class="ribbon ribbon-highlight">
            <ol class="breadcrumb ribbon-inner">
                <li><a href="<?php echo base_url(); ?>">Home</a>
                </li>
                <li>Upload</li>
            </ol>
        </div>

        <div id="infoMessage">
            <?php echo $this->session->flashdata('message');;?></div>
        <article class="entry style-single type-post">
        
          
<div class="entry-content">
        <?php if ($this->ion_auth->is_admin()): ?>
          <!-- <div style="text-align:center">
            <a href="<?php echo base_url('upload/mixtape'); ?>" class="btn btn-warning btn-lg" title="Click here to upload a mixtape">CLICK HERE TO UPLOAD A MIXTAPE</a>  
          </div> -->
        <?php endif ?>

                <div id="uploader">
                    <p>Your browser doesn't have Flash, Silverlight or HTML5 support.</p>
                </div>

                <div id="songs">
                    <table class="table" id="song_list">
                        <thead>
                            <tr>
                                <th class='ui-state-default'>Filename</th>
                                <th class='ui-state-default'>Uploaded On</th>
                                <th class='actions ui-state-default' style="width:170px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="no-songs-in-list">
                                <td colspan="3">Upload a MP3 file above first, then it will show up in the list here.</td>
                            </tr>
                        </tbody>
                    </table>
                    <div id="finish-upload-msg" style="text-align:center">After uploading, click the Finish button to make your song active.</div>
                </div>

            <h3>Add a song by entering a SoundCloud link</h3>     
    <form method="post" action="<?php echo base_url('manage/scup'); ?>" enctype="multipart/form-data" id="scForm">
          Soundcloud URL: <?php echo form_input(array('name'=>'scLink','id'=>'scLink','placeholder'=>'https://soundcloud.com/mack-breezy/leffbreezy-oh-well','style'=>'width:400px')); ?>
           <button type="submit" id="sc-submit" class="btn btn-warning">Add SC</button>
    </form>

        </article>
        </div>
        <!--/.col-md-8.col-lg-8.col-xl-9-->



        <!-- Final/Finish Modal -->
        <div class="modal fade" id="song_form" tabindex="-1" role="dialog" aria-labelledby="song_formLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Complete Your Track Details</h4>
                    </div>
                    <form method="post" action="<?php echo base_url('manage/finish/json'); ?>" enctype="multipart/form-data" id="myForm">

          <div id="processing" style="padding-top:25px;padding-bottom:25px;">
            <div class="spinner">
              <div class="rect1"></div>
              <div class="rect2"></div>
              <div class="rect3"></div>
              <div class="rect4"></div>
              <div class="rect5"></div>
                Processing...
            </div>
        </div>
            <div id="formMain">
                        <div class="modal-body">
                    <div id="formErrors" class="alert alert-danger"></div>

                            <input type="hidden" id="song_uid" name="song_uid" value="">
                            <input type="hidden" id="scimg" name="scimg" value="">
                            <input type="hidden" id="file_name" name="file_name" value="">
                            <input type="hidden" id="user_id" name="user_id" value="<?php echo $user->id ?>">
                            <div class="row">
                                <div class="col-sm-6">
                                    <p>Main Artist:*
                                        <br />

                                        <?php echo form_input(array('name'=>'artist','id'=>'artist')); ?></p>
                                </div>

                                <div class="col-sm-6">
                                    <p>Featuring:
                                        <br />
                                        <?php echo form_input(array('name'=>'featuring','id'=>'featuring')); ?>
                                    </p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <p>Title:*
                                        <br />
                                        <?php echo form_input(array('name'=>'title','id'=>'title')); ?>
                                    </p>
                                </div>

                                <div class="col-sm-6">
                                    <p>Producer:
                                        <br />
                                        <?php echo form_input(array('name'=>'producer')); ?>
                                    </p>
                                </div>


                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <p>Album/Mixtape:
                                        <br />
                                        <?php echo form_input(array('name'=>'album')); ?>
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <p>YouTube or Vimeo link:
                                        <br />
                                        <?php echo form_input(array('name'=>'video')); ?>
                                    </p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6" id="imageupload">
                                    Select Album Art:
                                <span id="scimgalert"></span>
                                    <br />
                                    <input type="file" name="image_file">
                                </div>

                                <div class="col-sm-6">

                                    <p>Track Description:
                                        <br />
                                        <textarea name="description" id="description"></textarea>
                                        <br />
                                        <span class='charsleft' id="charsleft"></span>
                                    </p>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6" id="can_downloadForm">
                                    <p>Allow Downloads:
                                        <?php echo form_checkbox('can_download', 'yes', TRUE); ?>
                                    </p>

                                </div>
                                <div class="col-sm-6">
                                    <p id='buy_link_input'>Buy Link:
                                        <br />
                                        <?php echo form_input(array('name'=>'buy_link','placeholder'=>'iTunes/Amazon link')); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row container" style="text-align:center">
                                <div id="showTitle" style="font-size:16px;font-weight:bold">Song Title Preview</div>
                                <span id="artisttarget">Artist</span>
                                <span id="featurecontainer">(feat. <span id="featuringtarget"></span> )</span>
                                <span id="titlecontainer"> - <span id="titletarget">Title</span></span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="pull-left">* - denotes required field.</div>
                            <button type="button" id="cancel-btn" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                            <button type="submit" id="submit-btn" class="btn btn-warning">Save & Submit</button>

                            <?php echo form_close(); ?>


                        </div>
                        </div><!--formMain-->
                </div>
            </div>
        </div>


        <!-- Delete Modal -->
        <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="delete_modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Delete Incomplete Song</h4>
                    </div>

                    <div class="modal-body">
              
               <div id="deleteformErrors" style="color:red"></div>

                        <p style="color:red"><strong>WARNING!</strong>
                        </p>
                        <p>Deleting a song will permanently delete and remove all traces of the song from our network. This action is irreversable!</p>
                        <p style="color:red"><strong>Are you sure you want to delete?!</strong>
                        </p>


                        <?php echo form_open( 'manage/song/delete', $delete_form_attributes) ?>
                        <input type="hidden" id="song_id" name="song_id" value="">
                        <input type="hidden" id="song_uid" name="song_uid" value="">
                        <input type="hidden" id="file_name" name="file_name" value="">
                        <input type="hidden" id="user_id" name="user_id" value="<?php echo $user->id ?>">
                        <?php echo form_close(); ?>


                    </div>
                    <div class="modal-footer">
                        <button type="button" id="cancel-btn" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                        <button type="submit" id="confirm-delete-btn" class="btn btn-danger confirm-delete-btn">Delete Song</button>
                    </div>
                </div>
            </div>
        </div>