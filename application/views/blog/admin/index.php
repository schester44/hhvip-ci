<script type="text/javascript">
    $(document).ready(function(){
        $('#add-category-btn').click(function(){
            $('#new-category-form').toggle();
        })
        $( "#newCategory" ).submit(function(e) {
            e.preventDefault();
            $('#newCategory').ajaxSubmit({
                beforeSubmit:  function(){},
                success: function (data) {
                    if (data.response == 'error') {
                        $('#new_category').val('ERROR: ' + data.message);
                        $('#new_category').css({'background':'#FF7F7F'});

                    } else if (data.response == 'success') {
                        $('#new_category').val('Category Added');
                        $('#new_category').css({'background':'#B4EEB4'});
                        $('#new_category').prop('disabled', true);
                    }
                }
            });
        });

    });
</script>

<div class="section row entries">
         <article class="entry col-xs-12 col-sm-12">
        </article>
</div>
<div class="ribbon ribbon-highlight">
    <ol class="breadcrumb ribbon-inner">
        <li><a href="<?php echo base_url(); ?>">Home</a>
        </li>
        <li><a href="<?php echo base_url('backend'); ?>">Backend</a></li>
        <li>Blog</li>
        <li class="active">Manage Posts</li>
    </ol>
</div>

<div id="content" class="content section row">
    <div class="col-md-8 bg-base col-lg-8 col-xl-9">
    <h1 class="song-subsections-heading" style="color:#fa8900">Manage Blog</h1>
    <a  href="<?php echo base_url('backend/blog/add'); ?>" class="btn btn-sm btn-primary">New Post</a>
    <a class="btn btn-sm btn-primary" id="add-category-btn">Add Category</a>
    <div style="display:none;margin:5px" id="new-category-form">
    <span style="display:none;" class="alert alert-success" id="category-errors"></span>
        <form action="<?php echo base_url('backend/blog/add/category') ?>" method="POST" id="newCategory">
            <input type="text" name="new_category" id="new_category" placeholder="New Category Name">
        </form>
    </div>
<hr>
            <?php echo $this->session->flashdata('admin_blog_message'); ?>
    <div class="list-group">
    <?php if (!empty($posts)): ?>
    <?php foreach ($posts as $key => $post): ?>
        <a href="<?php echo base_url('b/'. $post->category_title . '/' . $post->url); ?>" class="list-group-item"  title="<?php echo $post->title; ?>"><span class="glyphicon glyphicon-th-large"></span>  <?php echo $post->title; ?><span class="badge"><span style="color:yellow"><?php echo ucfirst($post->access); ?></span> | <?php echo ucfirst($post->status); ?></span><br /><span style="font-size:10px; color:#ccc">ID: <?php echo $post->id ?> | Author: <?php echo $post->username; ?> | Date Published: <?php echo date('m/d/Y',$post->date_published); ?></span></a>            
    <?php endforeach ?>
    <?php else: ?>
        <div style="text-align:center">
        <h3>There are no blog posts.</h3>  
        <a href="<?php echo  base_url('backend/blog/add'); ?>" titl="Create New Post" class="btn btn-lg btn-primary">Create New Post</a>          
        </div>
    <?php endif ?>
    </div>
<?php echo $pagination; ?>
</div> <!--/.col-md-8.col-lg-8.col-xl-9-->

    <?php $this->load->view('admin/sidebar'); ?>