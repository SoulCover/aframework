<h1>
	<a href="<?php echo Router::urlFor('Home'); ?>" title="<?php echo Lang::get('Home Page'); ?>">
		<?php echo htmlentities(Config::get('general.site_title')); ?>
	</a>
</h1>

<p><?php echo htmlentities(Config::get('general.site_tagline')); ?></p>