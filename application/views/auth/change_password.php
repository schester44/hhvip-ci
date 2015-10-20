      <div id="content" class="content section row">

        <div class="col-md-8 bg-base col-lg-8 col-xl-9">

          <div class="ribbon ribbon-highlight">
            <ol class="breadcrumb ribbon-inner">
              <li><a href="<?php echo base_url(); ?>">Home</a></li>
              <li><a href="<?php echo base_url('manage'); ?>">Manage Account</a></li>

              <li class="active" title="Change Password">Change Password</li>
            </ol>
          </div>
          
          <header class="page-header">
            
          <h2 class="page-title">
                 <?php echo lang('change_password_heading');?>
              
            </h2>

          </header>

          <!--content-->
            <article class="entry">


                        <div id="infoMessage" class="redtextalert"><?php echo $message;?></div>

                        <div class="panel panel-default">
<div class="panel-body">
<?php echo form_open("auth/change_password");?>
                    <fieldset>
                <div class="form-group">
                  <input class="form-control" placeholder="Old Password" name="old" id="old" type="password" value="">
              </div>
            <div class="form-group">
                  <input class="form-control" placeholder="New Password" name="new" id="new" type="password" pattern="^.{6}.*$" value="">
              </div>

            <div class="form-group">
                  <input class="form-control" placeholder="Confirm New Password" name="new_confirm" id="new_confirm" type="password" pattern="^.{6}.*$" value="">
              </div>
                  <?php echo form_input($user_id);?>    
              <input class="btn btn-lg btn-success btn-block" type="submit" name="submit" value="Change">
            </fieldset>
<?php echo form_close();?>
</div></div>

            </article><!--/ content formatting -->
        </div><!--/.col-md-8.col-lg-8.col-xl-9-->