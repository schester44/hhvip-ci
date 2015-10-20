<div class="ribbon ribbon-highlight">
    <ol class="breadcrumb ribbon-inner">
        <li><a href="<?php echo base_url(); ?>">Home</a></li>
        <li>News</li>
    </ol>
</div>

<div id="content" class="content section row">
    <div class="col-md-8 bg-base col-lg-8 col-xl-9" style="padding-top:5px">
        <span style="font-weight:bold">Sort By:</span>
        <div class="sortList" id="sort-list" style="margin-bottom:-15px;">
            <ul class="sort-links" style="margin-left:-45px;">

            <li class="<?php if (!$this->uri->segment('3')) { echo 'active'; } ?> " style="margin-right:-5px"><a href="<?php echo base_url('news'); ?>" title="All news">All</a></li>                
            <?php foreach ($categories as $key => $category): ?>
                <li class="<?php if ($this->uri->segment('3') == $category->title) { echo 'active'; } ?> " style="margin-right:-5px"><a href="<?php echo base_url('news/category/' . $category->title); ?>" title="<?php echo $category->title; ?> articles"><?php echo ucfirst($category->title); ?></a></li>                
            <?php endforeach ?>
            </ul>
        </div>
        <hr>

    <?php if (isset($posts) && !empty($posts)): ?>
        <?php $show_ad = 0; ?>

        <?php foreach ($posts as $key => $post): ?>
            <?php if ($show_ad == 10) { ?>
            <div style="background:#ffd8b9;padding-top:5px;padding-bottom:5px;margin-top:-10px">
              <?php $this->load->view('modules/ads/banner'); ?>
            </div>
            <?php } ?>

            <article class="entry style-media media type-post">
            <figure class=" pull-left">
           <a href="<?php echo base_url('b/'.$post->category_title.'/'.$post->url); ?>" title="<?php echo htmlspecialchars($post->title, ENT_QUOTES); ?>"> <img src="<?php echo blog_featured_img($post->username, $post->url, $post->featured_image, 150); ?>"></a>
            </figure>
            <header class="entry-header">
            <h1 class="song-subsections-heading blog-list-title"><a href="<?php echo base_url('b/'.$post->category_title.'/'.$post->url); ?>" title="<?php echo htmlspecialchars($post->title, ENT_QUOTES); ?>"><?php echo htmlspecialchars($post->title, ENT_QUOTES); ?></a></h1>                    
            <span style="color:#666;font-size:10px;">Posted on <?php echo date('M d, Y', $post->date_published); ?> (<?php echo time_ago($post->date_published); ?>) by <a href="<?php echo base_url('u/'.$post->username); ?>" title="View <?php echo $post->username; ?>'s profile"><?php echo $post->username; ?></a> | Category: <?php echo ucfirst($post->category_title); ?></span>
            
            </header>
            <div class="blog-list-excerpt">
<?php 
        $post_content = strip_tags($post->content, '<a>');
        echo (strlen($post_content) > 250) ? substr($post_content,0,247). ' ... (<a href="' . base_url('b/'.$post->category_title.'/'.$post->url) . '" title="' . htmlspecialchars($post->title, ENT_QUOTES) .'">Read More</a>) ' : $post_content;
 ?>
            </div>

        </article>
<hr />
    <?php $show_ad++; ?>
    <?php endforeach ?>
    <?php else: ?>  
   <h2 style="text-align:center;"> No more news stories</h2>
    <?php endif ?>

    <div class="content section" style="text-align:center">
        <?php echo $pagination; ?>
    </div>
</div> <!--/.col-md-8.col-lg-8.col-xl-9-->