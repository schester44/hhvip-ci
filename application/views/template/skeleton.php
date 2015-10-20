<!DOCTYPE html>
<html>
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
		<link rel="shortcut icon" type="image/x-icon" href="/resources/img/favicon.ico" />
		<link rel="apple-touch-icon" href="/resources/img/aicon.png">
		<link rel="apple-touch-startup-image" href="/resources/img/aloadup.png">
		<link rel="stylesheet" href="/resources/vendor/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="/resources/css/base-2.0.3.css">
		<link rel="canonical" href="<?php echo current_url(); ?>">

<?php if ($this->uri->segment(1) === 'song' && isset($song)): ?>
    <link href="<?php echo base_url(); ?>oembed?url=<?php echo current_url(); ?>" rel="alternate" type="application/json+oembed" title="<?php echo htmlspecialchars($song->song_artist .' - ' . $song->song_title); ?>">
<?php endif ?>

<?php if (isset($vendorCSS)) {
	foreach ($vendorCSS as $c) { ?>
		<link rel="stylesheet" href="<?php echo base_url(VENDOR ."$c") ?>">
	<? } } ?>
		<script src="/resources/vendor/jquery-1.11.0.min.js"></script>
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

	<?php if ($this->ion_auth->logged_in()) { ?> 
	<script>
	  ga('set', '&uid', <?php echo $this->ion_auth->user()->row()->id; ?>);
	</script>
	<?php } ?>
<!-- end GA-->

        <script src="<?php echo $this->config->item('assets_url'); ?>resources/vendor/bootstrap/js/bootstrap.min.js"></script>

	</head>
	<body>
    <?php echo $body ?>
        <script src="<?php echo $this->config->item('assets_url'); ?>resources/js/voting.songs.js"></script>

            
<script type="text/javascript">
<?php if (isset($userVotes) && !empty($userVotes)): ?>
    $(document).ready(function(){
        var votes = jQuery.parseJSON('<?php echo $userVotes; ?>');
        if (votes.length >= 1) {
            jQuery.each( votes, function( i, val ) {
                if (val.vote_rating > 0) {
                    $('.text_vote_up_'+ val.vote_song_id).addClass('vote_just_voted');
                } else {
                    $('.text_vote_down_'+ val.vote_song_id).addClass('vote_just_voted');
                }
            });
        }
    });
<?php endif ?>        
        //gray out download button
        $('.disdown').prop('disabled', true);


//hide top ad on smaller mobile devices
if ($(window).width() < 780 ) {
    $('.mainTopPiece').hide();
    $('.footer-bottom').css({'margin-bottom':'65px'});
};

if ($(window).width() < 1200) {
    $('.profile-background').css({'height':'345px'});
};

//stats
function trackEvent(tid, t, e) {
    $.ajax({
        url: "/ajax/events",
        data: {
            tid: tid,
            t: t,
            e: e
        },
        type: "POST"
        
    })
}
function trackPlaylistEvent(t, lid, tid, e) {
    $.ajax({
        url: "/ajax/events/playlist",
        data: {
            t: t,
            lid: lid,
            tid: tid,
            e: e
        },
        type: "POST"
    })
}
    var profileNameLength = $('.navbar-username').text().length;
        if (profileNameLength > 8) {
            $('.navbar-username').html('Account<span class="caret"></span>');
        };

    $('#favorite').on('click', function(e) {
        $.ajax({
                url: window.location.pathname + '/favorite',
                type: 'POST',
                cache: false,
                dataType: "json",
                 success: function(result) {
                    if ($( '#favorite').text() == 'Favorite') {
                        $( '#favorite').html($( '#favorite').html().replace('Favorite', 'Un-Favorite'));
                    }
                    else {
                        $( '#favorite').html($( '#favorite').html().replace('Un-Favorite', 'Favorite'));
                    }

                 }
             });
        e.preventDefault();
    });

    $("#navFollow").on('click', function(e) {
        $.ajax({ url: window.location.pathname + '/follow',
                 success: function(result) {
                    if ($('#navFollow').text() === 'Follow') {
                        $('#navFollow').html('Un-Follow');
                    } else {
                        $('#navFollow').html('Follow');
                    }
                 }
             });
        e.preventDefault();
    });

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

/**
 * MAIL CHIMP
 */

$(document).ready(function(){
        function IsEmail(email) {
          var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
          return regex.test(email);
        }

        //if valid email, add cookie
        // if cookie is present, then hide
    $('#mc-embedded-subscribe').on('click',function(){
        if (IsEmail($('#mce-EMAIL').val()) === true) {
            $.cookie('subscribed-to-email', '1');
        };
    });

    if (!$.cookie('subscribed-to-email')) {
        $('#subscriberBox').show();
    };

});

</script>

<?php if ($this->ion_auth->is_admin()): ?> 
   <script type="text/javascript">
    $(document).ready(function(){
        $('.boostDrop').on('change',function(){
           var sid = $(this).attr('id').replace('boost-','');
           var val = $(this).val();

           console.log(val);
           console.log(sid);

            $.ajax({
                cache: false,
                dataType: 'json',
                data: {id: sid, value: val},
                url: "/backend/boostVotes",
                type: 'POST',
                success: function(response) {
                    if(response.error === 'false') {
                        $('#boost-' + sid + ' option[value=' + val + ']').text('Boosted by ' + val);
                    } else {
                        $('#boost-' + sid + ' option[value=' + val + ']').text('ERROR!');
                    }
                }
            });
        });
        $('.dumpDrop').on('change',function(){
           var sid = $(this).attr('id').replace('dump-','');
           var val = $(this).val();

           console.log(val);
           console.log(sid);

            $.ajax({
                cache: false,
                dataType: 'json',
                data: {id: sid, value: val, dump:'yes'},
                url: "/backend/boostVotes",
                type: 'POST',
                success: function(response) {
                    if(response.error === 'false') {
                        $('#dump-' + sid + ' option[value=' + val + ']').text('Lowered by ' + val);
                    } else {
                        $('#dump-' + sid + ' option[value=' + val + ']').text('ERROR!');
                    }
                }
            });
        });
    });
    </script>
 <?php endif ?>
 
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

<?php $this->load->view('modules/ads/stickyfooter'); ?>
</body>
	
</html>
