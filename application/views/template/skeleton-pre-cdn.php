<!DOCTYPE html>
<!--[if lt IE 7]>   <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>      <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>      <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<title><?php if (isset($title)) { echo $title; } else { echo $base_title; }?></title>

<?php
if (isset($title)) { $t = $title; } else { $t = $base_title; };

 if (isset($meta_name)) {
	foreach ($meta_name as $m => $val) { ?>
		<meta name="<?php echo $m ?>" content="<?php echo $val ?>">
	<?php } 

	} else {
		echo '<meta name="description" content="'.$this->lang->line('meta_description').'">';
		echo '<meta name="twitter:card" content="summary_large_image">';
		echo '<meta name="twitter:domain" content="'.base_url().'">';
		echo '<meta name="twitter:site" content="'.$this->lang->line('meta_twitter').'">';
		echo '<meta name="twitter:title" content="'.$t.'">';
		echo '<meta name="twitter:creator" content="'.$this->lang->line('meta_twitter').'">';
		echo '<meta name="twitter:description" content="'.$this->lang->line('meta_description').'">';
		echo '<meta name="twitter:image" content="'.$this->lang->line('meta_song_img_placeholder').'">';

		} ?>

	<?php if (isset($meta_prop)) {
	foreach ($meta_prop as $mp => $prop_val) { ?>
		<meta property="<?php echo $mp ?>" content="<?php echo $prop_val ?>">
	<?php }
	 } else {
	 	echo '<meta property="og:title" content="'.$t.'">';
	 	echo '<meta property="og:url" content="'.base_url().'">';
	 	echo '<meta property="og:site_name" content="'.$base_title.'">';
	 	echo '<meta property="og:description" content="'.$this->lang->line('meta_description').'">';

	 	} ?>
		<!-- Mobile Devices Viewport Reset-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
		<meta name="apple-mobile-web-app-capable" content="yes">

		<link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url('resources/img/favicon.ico') ?>' />
		<link rel="apple-touch-icon" href="<?php echo base_url('resources/img/aicon.png'); ?>">
		<link rel="apple-touch-startup-image" href="<?php echo base_url('resources/img/aloadup.png') ?>">
		<link rel="stylesheet" href="<?php echo base_url(VENDOR."bootstrap/css/bootstrap.min.css");?>">
		<link rel="stylesheet" href="<?php echo base_url(CSS."base.css") ?>">
		<link rel="canonical" href="<?php echo current_url(); ?>">
		<link href='//fonts.googleapis.com/css?family=Droid+Sans:400,700|Lato:300,400,700,400italic,700italic|Droid+Serif' rel='stylesheet' type='text/css'>


<?php if (isset($vendorCSS)) {
	foreach ($vendorCSS as $c) { ?>
		<link rel="stylesheet" href="<?php echo base_url(VENDOR ."$c") ?>">
	<? } } ?>
		<script src="<?php echo base_url(VENDOR."jquery-1.11.0.min.js") ?>"></script>
<?php if (isset($coreJS)) {
	foreach ($coreJS as $core) { ?>
	<script src="<?php echo base_url(JS ."$core") ?>"></script>
	<? } } ?>

<?php if (isset($vendorJS)) {
	foreach ($vendorJS as $j) { ?>
	<script src="<?php echo base_url(VENDOR ."$j") ?>"></script>
	<? } } ?>

<!-- start GA -->
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-41619870-4', 'hiphopvip.com');
	  ga('send', 'pageview');
	</script>

	<?php if (isset($this->ion_auth->user()->row()->id)) { ?> 
	<script>
	  ga('set', '&uid', <?php echo $this->ion_auth->user()->row()->id; ?>);
	</script>
	<?php } ?>
<!-- end GA-->
	</head>
	<body>
	<?php echo $body ?>
		<script src="<?php echo base_url(VENDOR."bootstrap/js/bootstrap.min.js") ?>"></script>

<script type="text/javascript">
    $('#favorite').on('click', function(event) {
        $.ajax({ url: window.location.pathname + '/favorite',
                 success: function(result) {
                    if ($( '#favorite').text() == 'Favorite') {
                        $( '#favorite').html($( '#favorite').html().replace('Favorite', 'Un-Favorite'));
                    }
                    else {
                        $( '#favorite').html($( '#favorite').html().replace('Un-Favorite', 'Favorite'));
                    }

                 }
             });
        event.preventDefault();
    });

    $("#navFollow").on('click', function(event) {
        $.ajax({ url: window.location.pathname + '/follow',
                 success: function(result) {
                    if ($( '#navFollow').text() == 'Follow') {
                        $( '#navFollow').html($('#navFollow').html().replace('Follow', 'Un-Follow'));
                    }
                    else {
                        $( '#navFollow').html($( '#navFollow').html().replace('Un-Follow', 'Follow'));
                    }

                 }
             });
        event.preventDefault();
    });


	$(document).ready(function() {
    $('.already_voted').hide();
});

function vote(id, rating) {
    $target_votes = $("#votes_" + id);
    $target_text_vote = $("#text_vote_" + id);
    $target_text_unvote = $("#text_unvote_" + id);

    $.ajax({
        type: 'POST',
        url: '/votes/ajax_vote',
        data: {id: id ,rating: rating },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            $target_votes.html('err');
        },
        success: function (data) {
            if (data.error === true) {} else {
                $target_votes.html(data.votes);
                $target_votes.addClass('vote_color_top_just_voted');
                if (rating > 0 && data.undo == false) {
                    $('#text_vote_up_'+ id).addClass('vote_just_voted');
                    $('#text_vote_down_'+ id).removeClass('vote_just_voted');
                                    //analytics
                ga('send', 'event', 'vote', 'up', + id);

                } else if (rating < 0 && data.undo == false) {
                    $('#text_vote_down_'+ id).addClass('vote_just_voted');
                    $('#text_vote_up_'+ id).removeClass('vote_just_voted');
                                    //analytics
                ga('send', 'event', 'vote', 'down', + id);
                }
                if (rating > 0 && data.undo == true) {
                    $('#text_vote_down_'+ id).removeClass('vote_just_voted');
                                    //analytics
                ga('send', 'event', 'vote', 'up', + id);

                };
                if (rating < 0 && data.undo == true) {
                    $('#text_vote_up_'+ id).removeClass('vote_just_voted');
                                    //analytics
                ga('send', 'event', 'vote', 'down', + id);
                };

                if (data.result) {
                    if (data.rating > 0) {
                        $('#text_vote_up_'+ id).fadeOut(100, function(){
                            $('#already_voted_'+id).fadeIn().delay(700).fadeOut(200, function(){
                                $('#text_vote_up_'+ id).fadeIn(100);
                            });
                        });
                    };

                    if (data.rating < 0) {
                        $('#text_vote_down_'+ id).fadeOut(100, function(){
                            $('#already_voted_down_'+id).fadeIn().delay(700).fadeOut(200, function(){
                                $('#text_vote_down_'+ id).fadeIn(100);
                            });
                        });
                    };
                };

            }
        },
        dataType: "json"
    });
}

// Avoid `console` errors in browsers that lack a console.
if (!(window.console && console.log)) {
    (function() {
        var noop = function() {};
        var methods = ['assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error', 'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log', 'markTimeline', 'profile', 'profileEnd', 'markTimeline', 'table', 'time', 'timeEnd', 'timeStamp', 'trace', 'warn'];
        var length = methods.length;
        var console = window.console = {};
        while (length--) {
            console[methods[length]] = noop;
        }
    }());
}


(function ($) {
    "use strict";

    $(function(){

        $(document)
        .on('click', function(e) {

            if ( $(this).is( '[data-toggle]' ) === false ) {
                $('[data-toggled]').each(function(e) {

                    if ( $(this).hasClass( $(this).attr('data-toggled') ) ) {
                        $(this).toggleClass( $(this).attr('data-toggled') );
                        $(this).removeAttr('data-toggled');
                    }
                });
            }

        })
        .on('click', '[data-toggle][href="#"]', function(e) {
            e.stopPropagation();
            e.preventDefault();

            var $target = $(this).closest( $(this).data('toggle') ),
            class_name = ( $(this).data('toggle-class') ) ? $(this).data('toggle-class') : 'toggled-in';

            $target.toggleClass( class_name );

            if ( $target.hasClass( class_name ) ) {
                $target.attr('data-toggled', class_name );
                var $input = $target.find('input');
                
                if ( $input.size() > 0 ) {
                    $input[0].focus();
                }
            } else {
                $target.removeAttr('data-toggled');
            }

            var $siblings = $target.siblings('.' + class_name );

            $siblings.find( '.' +class_name ).toggleClass(class_name).removeAttr('data-toggled');
            $siblings.toggleClass( class_name ).removeAttr( 'data-toggled' );

        })

        .on('mouseover', '[data-toggle][href="#"]', function(e) {
            var $target = $(this).closest( $(this).data('toggle') ),
            class_name = ( $(this).data('toggle-class') ) ? $(this).data('toggle-class') : 'toggled-in',
            $siblings = $target.siblings('.' + class_name );

            $siblings.find( '.' +class_name ).toggleClass(class_name).removeAttr('data-toggled');
            $siblings.toggleClass( class_name ).removeAttr( 'data-toggled' );
        })

        .on( 'click', '.js-stoppropagation', function(e) {
            e.stopPropagation();
        })

        .on('click', '.js .collapsible-widgets .widget-title', function(e) {
            if ( $(this).closest('.widget').hasClass('active') ) {
                $(this).closest('.widget').removeClass('active');
            } else {
                $(this).closest('.widget').addClass('active').siblings().removeClass('active');
            }
            
            $(window).trigger('scroll');
        })
        .on('mouseover', '.subnav-tabbed-tabs a', function(e) {
            e.preventDefault();
            $(this).closest('li').addClass('active').siblings().removeClass('active');
            $( $(this).attr('href') ).addClass('active').siblings().removeClass('active');
        })
        .on('click', '.nav-tabs a', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        $('.js .collapsible-widgets .widget:first .widget-title').trigger('click');

        $(window).trigger('scroll');

        $('.carousel').carousel();
    });

})(window.jQuery);
</script>

<!-- start Clicky -->
<script type="text/javascript">
var clicky_site_ids = clicky_site_ids || [];
clicky_site_ids.push(66465258);
(function() {
  var s = document.createElement('script');
  s.type = 'text/javascript';
  s.async = true;
  s.src = '//static.getclicky.com/js';
  ( document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0] ).appendChild( s );
})();
</script>
<noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/66465258ns.gif" /></p></noscript>
<!-- end Clicky-->
</body>
	
</html>
