      <div id="content" class="content section row">

        <div class="col-md-8 bg-base col-lg-8 col-xl-9">

          <div class="ribbon ribbon-highlight">
            <ol class="breadcrumb ribbon-inner">
              <li><a href="<?php echo base_url(); ?>">Home</a></li>
              <li><a href="<?php echo base_url('manage'); ?>">Manage Account</a></li>
              <li class="active" title="Reset Password">Reset Password</li>
            </ol>
          </div>
          
          <header class="page-header">
            
          <h2 class="page-title">
      <?php echo lang('reset_password_heading');?>
            </h2>

          </header>

          <!--content-->
            <article class="entry">
            <div class="panel panel-default">
<div class="panel-body">

  <div id="infoMessage" class="redtextalert"><?php echo $message;?></div>
<?php echo form_open('auth/reset_password/' . $code);?>
                    <fieldset>
                <div class="form-group">
                  <?php echo form_input($new_password);?>

              </div>

               <div class="form-group">
                  <?php echo form_input($new_password_confirm);?>
              </div>
              <input class="btn btn-lg btn-success btn-block" type="submit" name="submit" value="Submit">
            </fieldset>
       <?php echo form_input($user_id);?>
  <?php echo form_hidden($csrf); ?>
<?php echo form_close();?>

</div></div>
            </article><!--/ content formatting -->
        </div><!--/.col-md-8.col-lg-8.col-xl-9-->