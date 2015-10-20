<div id="content" class="content section row">

    <div class="col-md-8 bg-base col-lg-8 col-xl-9">

        <div class="ribbon ribbon-highlight">
            <ol class="breadcrumb ribbon-inner">
                <li><a href="<?php echo base_url(''); ?>">Home</a>
                </li>
                <li><a href="<?php echo base_url('auth'); ?>">Admin Home</a>
                </li>
                <li class="active" title="Auth">Auth</li>
            </ol>
        </div>

        <header class="page-header">
            <h2 class="page-title">User Manager</h2>
            <?php $this->load->view('admin/nav'); ?>
            <p>
                <?php echo lang( 'index_subheading');?>
            </p>
        </header>

        <!--content-->
        <article class="entry">


            <div id="infoMessage">
                <?php echo $message;?>
            </div>

            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
                <thead>
                    <tr>
                        <th>
                            <?php echo lang( 'index_username_th');?>
                        </th>
                        <th>
                            <?php echo lang( 'index_email_th');?>
                        </th>
                        <th>
                            <?php echo lang( 'index_groups_th');?>
                        </th>
                        <th>
                            <?php echo lang( 'index_status_th');?>
                        </th>
                        <th>
                            <?php echo lang( 'index_action_th');?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user):?>
                    <tr>
                        <td>
                            <?php echo $user->username;?></td>
                        <td>
                            <?php echo $user->email;?></td>
                        <td>
                            <?php foreach ($user->groups as $group):?>
                            <?php echo anchor( "auth/edit_group/".$group->id, $group->name) ;?>
                            <br />
                            <?php endforeach?>
                        </td>
                        <td>
                            <?php echo ($user->active) ? anchor("auth/deactivate/".$user->id, lang('index_active_link')) : anchor("auth/activate/". $user->id, lang('index_inactive_link'));?></td>
                        <td>
                            <?php echo anchor( "auth/edit_user/".$user->id, 'Edit') ;?></td>
                    </tr>
                    <?php endforeach;?>
                    <?php echo $pagination; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>
                            <?php echo lang( 'index_username_th');?>
                        </th>
                        <th>
                            <?php echo lang( 'index_email_th');?>
                        </th>
                        <th>
                            <?php echo lang( 'index_groups_th');?>
                        </th>
                        <th>
                            <?php echo lang( 'index_status_th');?>
                        </th>
                        <th>
                            <?php echo lang( 'index_action_th');?>
                        </th>
                    </tr>
                </tfoot>
            </table>



        </article>
        <!--/ content formatting -->
    </div>
    <!--/.col-md-8.col-lg-8.col-xl-9-->

