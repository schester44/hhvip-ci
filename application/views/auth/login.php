      <div id="content" class="content section row">

        <div class="col-md-8 bg-base col-lg-8 col-xl-9">

          <div class="ribbon ribbon-highlight">
            <ol class="breadcrumb ribbon-inner">
              <li><a href="<?php echo base_url(); ?>">Home</a></li>
              <li class="active" title="Login">Login</li>
            </ol>
          </div>
          
          <header class="page-header">
            <h1 class="page-title">Login</h2>
          </header>

          <!--content-->
            <article class="entry">
              <div id="infoMessage"><?php echo $message;?></div>

              <div class="panel panel-default">
              <div class="panel-body">

                <?php echo form_open("auth/login");?>
                    <fieldset>
                <div class="form-group">
                  <input class="form-control" placeholder="E-mail" name="identity" id="identity" type="text" value="">
              </div>
              <div class="form-group">
                <input class="form-control" placeholder="Password" name="password" id="password" type="password" value="">
              </div>
              <div class="checkbox">
                  <label>
                    <input name="remember" type="checkbox" name="remember" value="1" id="remember"> Remember Me
                  </label>
                </div>
              <input class="btn btn-lg btn-success btn-block" type="submit" name="submit" value="Login">
            </fieldset>
<?php echo form_close();?>
         
<p><a href="forgot_password"><?php echo lang('login_forgot_password');?></a></p>
<div style="text-align:center">Don't have an account already? <a href="<?php echo base_url('auth/create_account') ?>" title="create an account" class="btn btn-primary" rel="nofollow">Create An Account!</a></div>

</div></div>
            </article><!--/ content formatting -->
        </div><!--/.col-md-8.col-lg-8.col-xl-9-->