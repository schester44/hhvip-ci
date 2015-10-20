      <div id="content" class="content section row">

        <div class="col-md-8 bg-base col-lg-8 col-xl-9">

          <div class="ribbon ribbon-highlight">
            <ol class="breadcrumb ribbon-inner">
              <li><a href="<?php echo base_url('backend'); ?>">Backend</a></li>
              <li class="active" title="Create User">Create User</li>
            </ol>
          </div>
          
          <header class="page-header">
            
          <h2 class="page-title">
             <?php echo lang('create_user_heading');?>
            </h2>

          </header>

          <!--content-->
            <article class="entry">
<div id="infoMessage"><?php echo $message;?></div>

<div class="panel panel-default">
<div class="panel-body">
<?php echo form_open("auth/create_user");?>

      <p>
            <?php echo lang('create_user_fname_label', 'first_name');?> <br />
            <?php echo form_input($first_name);?>
      </p>

      <p>
            <?php echo lang('create_user_lname_label', 'last_name');?> <br />
            <?php echo form_input($last_name);?>
      </p>

      <p>
            <?php echo lang('create_user_company_label', 'company');?> <br />
            <?php echo form_input($company);?>
      </p>

      <p>
            <?php echo lang('create_user_username_label', 'username');?> <br />
            <?php echo form_input($username);?>
      </p>

      <p>
            <?php echo lang('create_user_email_label', 'email');?> <br />
            <?php echo form_input($email);?>
      </p>

      <p>
            <?php echo lang('create_user_password_label', 'password');?> <br />
            <?php echo form_input($password);?>
      </p>

      <p>
            <?php echo lang('create_user_password_confirm_label', 'password_confirm');?> <br />
            <?php echo form_input($password_confirm);?>
      </p>


      <p><?php echo form_submit('submit', lang('create_user_submit_btn'));?></p>

<?php echo form_close();?>
</div></div>
            </article><!--/ content formatting -->
        </div><!--/.col-md-8.col-lg-8.col-xl-9-->