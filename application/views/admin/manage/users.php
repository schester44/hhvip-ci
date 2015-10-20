<div class="section row entries">
         <article class="entry col-xs-12 col-sm-12">
        </article>
</div>
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
            <p><?php echo lang( 'index_subheading');?></p>
        </header>
        <article class="entry style-single type-post">
            <div class="entry-content">

                <a href="<?php echo base_url('auth/create_user'); ?>" class="btn btn-primary btn-sm">Create User</a> 
                <a href="<?php echo base_url('auth/create_group'); ?>" class="btn btn-primary btn-sm">Create Group</a> <br /><br />

                <?php if ($users) { ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Join Date</th>
                            <th>Status</th>
                            <th>Group(s)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    

                <?php foreach ($users as $user) { ?>

                        <tr id="user_<?php echo $user->id; ?>">
                        <td><?php echo $user->id; ?></td>
                       <td><a href="<?php echo base_url('u/'.$user->username) ?>" title="View <?php echo $user->username; ?>'s Profile"> <?php echo $user->username; ?></a></td>
                        <td><?php echo date('m-d-Y',$user->created_on); ?></td>
                        <td>

                            <?php echo ($user->active) ? anchor("auth/deactivate/".$user->id, 'Active') : anchor("auth/activate/". $user->id, 'Inactive');?></td>
                        <td>
                        <?php foreach ($user->groups as $group): ?>
                            <?php echo $group->name; ?>
                        <?php endforeach ?>
                        </td>
                        <td><a href="<?php echo base_url('auth/edit_user/'.$user->id); ?>" title="Edit User" class="btn btn-primary">Manage</a></td>

                        </tr>           
                <?php   } ?>
                </tbody>
            </table>
            <?php } else {
                echo '<td>END OF THE ROAD!';
                } ?>
                        <div><?php echo $pagination; ?></div>
                            <p class="lead">
                                <div id="infoMessage"><?php echo $this->session->flashdata('message');;?></div>
                            </p>
                        </div>
                    </article>
        <!--/ content formatting -->
    </div>
    <!--/.col-md-8.col-lg-8.col-xl-9-->

                <?php echo $this->load->view('admin/sidebar'); ?>
