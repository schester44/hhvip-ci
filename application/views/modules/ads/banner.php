<div class="mainTopPiece" style="margin-top:5px;margin-bottom:5px">
    <?php 
    $ads = array(
        array(
            'id'=>'2',
            'img'=>'//secure.hiphopvip.com/resources/img/network/kf-leaderboard-rukf.jpg',
            'link'=>'http://kushfriendly.com/?utm_source=hhvip&utm_medium=banner&utm_campaign=VIP-Leaderboard-RUKF',
            'title'=>'Shop Kush Friendly'
            ),
        array(
            'id'=>'1',
            'img'=>'//secure.hiphopvip.com/resources/img/network/banner-summer15.gif',
            'link'=>'http://kushfriendly.com/?utm_source=hhvip-banner&utm_medium=banner&utm_campaign=hhvip-banner-summer15',
            'title'=>'Shop Kush Friendly'
            )
        );

    shuffle($ads);
    $ad = $ads[0];
    ?>
    <a href="<?php echo $ad['link']; ?>" title="<?php echo $ad['title']; ?>"><img src="<?php echo $ad['img']; ?>" alt="<?php echo $ad['title']; ?>"></a>
</div>