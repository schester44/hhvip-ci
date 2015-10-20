<div class="ribbon ribbon-highlight">
    <ol class="breadcrumb ribbon-inner">
        <li><a href="<?php echo base_url(); ?>">Home</a>
        </li>
        <li><a href="<?php echo base_url('news'); ?>">NEWS</a></li>
        <li><a href="<?php echo base_url('news/category/' . $post->category_title); ?>"><?php echo $post->category_title ?></a></li>
        <li class="active" title="POST TITLE"><?php echo htmlspecialchars($post->title, ENT_QUOTES); ?></li>
    </ol>
</div>

<div id="content" class="content section row">
    <div class="col-md-8 bg-base col-lg-8 col-xl-9">
    <div class="row" style="min-height:210px;margin-top:-20px">
        <div class="blog-blur">
            <div class="blog-blur-inner"> 
                <img src="<?php echo $featured_image; ?>" style="width:100%;height:auto">
            </div>
        </div>
        <div class="col-sm-4 col-xs-4 col-md-4 col-lg-4 col-xl-2">
            <img src="<?php echo $featured_image; ?>">
        </div> 
        <div class="col-sm-8 col-xs-8 col-md-8 col-lg-8 col-xl-10">
            <h1 class="song-subsections-heading blog-title"><?php echo $post->title; ?></h1>
            <span class="blog-post-details">Posted on <?php echo date('M d, Y', $post->date_published); ?> by <a href="<?php echo base_url('u/'.$post->username); ?>" title="View <?php echo $post->username; ?>'s profile"><?php echo $post->username; ?></a></span>
        
            <div class="blog-social-buttons">
                 <div class="social-likes" data-url="<?php echo base_url('u/' . $post->category_title . '/' . $post->url); ?>" style="font-size:10px">
                    <div class="facebook" title="Share link on Facebook">Facebook</div>
                    <div class="twitter" data-related="<?php echo $post_summary; ?>" title="Share link on Twitter">Twitter</div>
                </div>
            </div> 
        </div>   
    </div>
     <?php if ($post->video) {?>
        <div class="row" style="margin-top:10px">
            <iframe width="100%" height="390" src="//www.youtube.com/embed/<?php echo $post->video; ?>" frameborder="0" allowfullscreen></iframe>
        </div>
    <?php } ?>
    <div class="blog-content">
        
 <?php echo $post->content; ?>
    </div>

<?php if ($this->ion_auth->logged_in() && $post->author === $this->ion_auth->user()->row()->id || $this->ion_auth->is_admin()): ?>
    <div style="text-align:right">
    <button class="btn btn-default btn-sm">Access: <?php echo ucfirst($post->access); ?></button>
    <a href="<?php echo base_url('backend/blog/edit/'. $post->id); ?>" class="btn btn-sm btn-primary" title="Edit Post">Edit</a>
    </div>
<?php endif ?>    
    <hr>

    <?php if (isset($other_posts) && !empty($other_posts)): ?>

        <h2 class="bebas" style="font-size:48px">You May Also Like...</h2>
    <?php foreach ($other_posts as $key => $op): ?>    
    <article class="entry style-media media type-post">
        <figure class="media-object pull-left list-image" style="height:65px">
            <img src="<?php echo blog_featured_img($op->username, $op->url, $op->featured_image, 64); ?>" width="54px" height="54px"> 
        </figure>                           
            <h3 style="margin-top:10px"><a href="<?php echo base_url('b/' . $op->category_title . '/' . $op->url) ?>" rel="bookmark"><?php echo $op->title; ?></a></h3>
    </article>
    <?php endforeach ?>
    <?php endif ?>

        <!--comments -->
        <div class="panel panel-default widget">
            <div class="panel-heading">
                <h4 class="song-subsections-heading comments-heading">
                    Latest Comments</h4>
            </div>

            <div id="disqus_thread"></div>
            <script type="text/javascript">
                /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
                var disqus_shortname = 'hhvip2'; // required: replace example with your forum shortname

                /* * * DON'T EDIT BELOW THIS LINE * * */
                (function () {
                    var dsq = document.createElement('script');
                    dsq.type = 'text/javascript';
                    dsq.async = true;
                    dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                })();
            </script>
            <noscript>Please enable JavaScript to view the comments.</noscript>
        </div>
    </div> <!--/.col-md-8.col-lg-8.col-xl-9-->

    <script type="text/javascript">
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        var disqus_shortname = 'hhvip2'; // required: replace example with your forum shortname

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function () {
            var s = document.createElement('script');
            s.async = true;
            s.type = 'text/javascript';
            s.src = '//' + disqus_shortname + '.disqus.com/count.js';
            (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
        }());
    </script>