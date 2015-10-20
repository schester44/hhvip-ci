<div style="background:rgb(245, 245, 245)" id="psv-<?php echo $this->input->get('id'); ?>">
          <form method="post" action="<?php echo base_url('upload/publish_s3_upload'); ?>" enctype="multipart/form-data" id="song-upload">
            <input type="hidden" id="song_uid" name="song_uid" value="">
            <input type="hidden" id="scimg" name="scimg" value="">
            <input type="hidden" id="file_name" name="file_name" value="">
            <input type="hidden" id="user_id" name="user_id" value="<?php echo $user->id ?>">
          <!--.details tabs-->

            <ul class="nav nav-tabs" id="myTab">
              <li class="active"><a href="#basic">Basic Info</a></li>
              <li><a href="#meta">Metadata</a></li>
            </ul>

            <div class="tab-content">
              <div class="tab-pane active" id="basic">
                <div class="row">
                  <div class="col-sm-6" id="imageupload">
                    <img src="https://placeholdit.imgix.net/~text?txtsize=33&txt=Album%20Art&w=350&h=350">
                    <a class="btn btn-warning" id="get_file" style="width:100%">Select Album Art</a>
                    <input type="file" name="image_file" id="image_file" accept=".jpg,.png,.gif,.jpeg">
                  </div>
                  
                  <div class="col-sm-6">                          
                    <div class="form-group">
                      <label for="artist">Song Artist:</label>
                      <?php echo form_input(array('name'=>'artist','id'=>'artist')); ?>
                    </div>

                    <div class="form-group">
                      <label for="title">Song Title:</label>
                      <?php echo form_input(array('name'=>'title','id'=>'title')); ?>
                    </div>

                    <div class="form-group">
                      <label for="featuring">Featuring Artists:</label>
                      <?php echo form_input(array('name'=>'featuring','id'=>'featuring')); ?>
                    </div>

                    <div class="form-group">
                      <label for="description">Description:</label>
                      <textarea name="description" id="description"></textarea>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="tab-pane" id="meta">
                    <div class="form-group" id="can_downloadForm">
                      <label for="can_download">Enable Download:</label>
                      <?php echo form_checkbox('can_download', 'yes', TRUE); ?>
                      <span style="display:block;font-size:0.7em;color:#999;line-height:1.1em">Distributing content without permission is unlawful. Make sure oyu have all necessary rights.</span>
                    </div>

                    <div class="form-group" id="buy-link">
                      <label for="buy_link">Buy Link:</label>
                        <?php echo form_input(array('name'=>'buy_link','id'=>'buy_link','placeholder'=>'iTunes/Amazon link')); ?>
                    </div>

                    <div class="form-group" id="make_privateForm">
                      <label for="buy_link">Make Private:</label>
                        <?php echo form_checkbox(array('name'=>'make_private','id'=>'make_private','value'=> 'yes','checked'=>FALSE)); ?>
                        <span id="make_privateAlert" style="display:block;font-size:0.7em;color:#999">Setting this to private means only you and those you share the link with will be able to see/play the song. The song will not be listed in our charts or on your profile page.</span>
                    </div>

              </div>
            </div>

            <!--/.details tabs-->
          <?php echo form_close(); ?>
</div>