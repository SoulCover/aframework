<?php
	Config::set('db.user',						'root');
	Config::set('db.pass',						'root');
	Config::set('db.host',						'localhost');
	Config::set('db.name',						'ante_agcom');

	Config::set('general.default_style',		'cyber-a');
	Config::set('general.site_author',			'Agnes Ekman');
	Config::set('general.site_title',			'AgnesEkman.com');
	Config::set('general.site_tagline',			'The "a" in cyberspace');
	Config::set('general.contact_email',		'youwish@gmail.com');
	Config::set('general.date_format',			'l jS \of F Y');
	Config::set('general.ga_id',				false); # UA-1823084-4

	Config::set('admin.user',					'admin');
	Config::set('admin.pass',					'1234');
	Config::set('su.user', 						'su');
	Config::set('su.pass', 						'4321');

	Config::set('ablog.num_recent_comments',	3);
	Config::set('ablog.num_recent_articles',	3);

	Config::set('navigation.home',				false);
	Config::set('navigation.styles',			false);

	Config::set('ie_support.fallback_style',	Router::urlForStyle('super-simple'));
?>
