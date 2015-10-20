
				var incomplete = <?php echo $incomplete; ?>;

					$(function() {
					$("#finish-upload-msg").hide();
					$("#song_list_songs").hide();
				});
							$(function() {
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
			$("input[name=file_name]", $sf).val(data['file']);

			$("input[name=title]", $sf).val(data['title']);
			$("input[name=album]", $sf).val(data['album']);
			$("input[name=artist]", $sf).val(data['artist']);
			$("input[name=featuring]", $sf).val(data['featuring']);
			$("input[name=producer]", $sf).val(data['producer']);
			$("input[name=genre]", $sf).val(data['genre']);
			$("textarea[name=description]", $sf).val(data['description']);
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

			if(txt == '') {
				return;
			}

			if(url = txt.match(/href=['"]([^'"]+)['"]/i)) {
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

 			$(function(){
 				$('#cancel-btn').click(function(e){
 					e.preventDefault();
				$("#formErrors").hide();
 				});


				
			    $('#submit-btn').click(function(e){
			      e.preventDefault();
                        
                    $('#myForm').ajaxSubmit({
                        beforeSubmit: function() {
                        },
                        success: function(data) {
				         	if (data.validation == 'error') {
				         		$("#formErrors").show();
				         		$('#formErrors').html(data.message);
				         	} else if (data.validation == 'valid' && data.response == 'error') {
				         		$("#formErrors").show();
				         		$('#formErrors').html(data.message);
				         	} else if (data.validation == 'valid' && data.response == 'success') {
	          					$('#song_form').modal('hide');

	          					 $("#song_"+data.song.song_id+" td.actions button").remove();
	          					 $('<a href="<?php echo base_url("song/" . $user->username) ?>/' + data.song.song_url + '" class="btn btn-warning" title="View Song" target="_blank">View Song</a> ').appendTo("#song_"+data.song.song_id+" td:eq(2)");
	          					 $(' <a href="link-to-share" class="btn btn-warning" title="Share" target="_blank">Share</a>').appendTo("#song_"+data.song.song_id+" td:eq(2)");
			         	};

$('#myForm').resetForm();
                        }
                    });
    	});
	});

});
