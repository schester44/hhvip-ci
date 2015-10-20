      <div id="content" class="content section row">

        <div class="col-md-8 bg-base col-lg-8 col-xl-9">

          <div class="ribbon ribbon-highlight">
            <ol class="breadcrumb ribbon-inner">
              <li><a href="<?php echo base_url(); ?>">Home</a></li>
              <li class="active" title="Sign Up">Sign Up</li>
            </ol>
          </div>
          
          <header class="page-header">
            
          <h2 class="page-title">
              <?php echo lang('register_user_heading');?>
            </h2>
          </header>

          <!--content-->
            <article class="entry">
<div id="infoMessage"><?php echo $message;?></div>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open("auth/create_account");?>
                    <fieldset>
              <div class="form-group">
                  <input class="form-control" placeholder="Username" name="username" id="username" type="text" value="<?php echo $username['value']; ?>">
              </div>
                <div class="form-group">
                  <input class="form-control" placeholder="E-mail" name="email" id="email" type="text" value="<?php echo $email['value']; ?>">
              </div>
              <div class="form-group">
                <input class="form-control" placeholder="Password" name="password" id="password" type="password" value="">
              </div>
            <div class="form-group">
                <input class="form-control" placeholder="Confirm Password" name="password_confirm" id="password_confirm" type="password" value="">
              </div>
              <div class="checkbox">
                  <label>
                    <input name="terms_of_service" type="checkbox" name="terms_of_service" value="1" id="terms_of_service"> I agree to the <?php echo SITE_TITLE; ?> <a href="<?php echo base_url('site/terms'); ?>">terms of service.</a>
                  </label>
                </div>
              <input class="btn btn-lg btn-success btn-block" type="submit" name="submit" value="<?php echo lang('register_user_submit_btn') ?>">
            </fieldset>
<?php echo form_close();?>
<p></p>
<div style="text-align:center">
  Already have an account? <a href="<?php echo base_url('auth/login') ?>" title="login" rel="nofollow" class="btn btn-primary btn-sm">Login Here</a>
</div>

</div></div>

            </article><!--/ content formatting -->
        </div><!--/.col-md-8.col-lg-8.col-xl-9-->