      <div id="content" class="content section row">

        <div class="col-md-8 bg-base col-lg-8 col-xl-9">

          <div class="ribbon ribbon-highlight">
            <ol class="breadcrumb ribbon-inner">
              <li><a href="<?php echo base_url(); ?>">Home</a></li>
              <li class="active" title="Forgot Password">Forgot Password</li>
            </ol>
          </div>
          
          <header class="page-header">
            
          <h2 class="page-title">
                        <?php echo lang('forgot_password_heading');?>
            </h2>

          </header>

          <!--content-->
            <article class="entry">
      <p><?php echo sprintf(lang('forgot_password_subheading'), $identity_label);?></p>
        <div class="panel panel-default">
          <div class="panel-body">
        <div id="infoMessage" class="redtextalert"><?php echo $message;?></div>
<?php echo form_open("auth/forgot_password");?>
                    <fieldset>
                <div class="form-group">
                  <input class="form-control" placeholder="E-mail" name="email" id="email" type="text" value="">
              </div>
              <input class="btn btn-lg btn-success btn-block" type="submit" name="submit" value="Submit">
            </fieldset>
<?php echo form_close();?>
       </div>
      </div>
            </article><!--/ content formatting -->
        </div><!--/.col-md-8.col-lg-8.col-xl-9-->