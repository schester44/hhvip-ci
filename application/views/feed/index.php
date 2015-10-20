<?php echo '<?xml version="1.0" encoding="UTF-8" ?>'; ?>
<rss version="2.0"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:admin="http://webns.net/mvcb/"
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:content="http://purl.org/rss/1.0/modules/content/">
    <channel><atom10:link xmlns:atom10="http://www.w3.org/2005/Atom" rel="self" type="application/xml" href="<?php echo current_url(); ?>" /><title><?php echo $feed_name; ?></title>
    <link><?php echo $feed_url; ?></link>
    <description><?php echo $page_description; ?></description>
    <dc:language><?php echo $page_language; ?></dc:language>
    <dc:creator><?php echo $creator_email; ?></dc:creator>
    <dc:rights>Copyright <?php echo gmdate("Y", time()); ?></dc:rights>
     <admin:generatorAgent rdf:resource="http://www.codeigniter.com/" />

     <?php if ($posts): ?>
         
 	<?php foreach ($posts as $key => $song): ?>
        <?php 

        $featuring = (!empty($song->featuring)) ? ' (Feat. '. $song->featuring .') ' : NULL;
        $description = strlen($song->song_description) > 200 ? substr($song->song_description,0,200)."..." : $song->song_description;
         ?>
        <item>
            <title><?php echo htmlspecialchars($song->song_artist . $featuring . ' - ' . $song->song_title, ENT_QUOTES); ?></title>
            <link><?php echo base_url('song/'.$song->username.'/'.$song->song_url);?></link>
            <guid><?php echo base_url('song/'.$song->username.'/'.$song->song_url); ?></guid>
            <image>http:<?php echo song_img($song->username, $song->song_url, $song->song_image, 150); ?></image>
            <description><![CDATA[ <?php echo $description; ?> ]]></description>
            
            <pubDate><?php echo date('D, d M Y H:m:s', $song->published_date) . ' EST'; ?></pubDate>
        </item> 
 	<?php endforeach ?>
     <?php endif ?>

    </channel>
</rss>