<rss version="2.0">
	<channel>
		<title><?php echo escHTML(Config::get('general.site_title')); ?></title>
		<description><?php echo escHTML(Config::get('general.site_tagline')); ?></description>
		<link>http://<?php echo $_SERVER['SERVER_NAME'] . WEBROOT; ?></link>
		<copyright>Copyright (c) <?php echo escHTML(Config::get('general.site_author')); ?></copyright>

		<?php foreach ($articles as $a) { ?>
			<item>
				<title><?php echo escHTML($a['title']); ?></title>
				<description><?php echo escHTML(NiceString::makeNice($a['content'], 4)); ?></description>
				<link>http://<?php echo $_SERVER['SERVER_NAME'] . Router::urlFor('Article', $a); ?></link>
				<pubDate><?php echo date(DATE_RSS, strtotime($a['pub_date'])); ?></pubDate>
			</item>
		<?php } ?>
	</channel>
</rss>