<h1>
	<a href="<?php echo Router::urlFor('Home'); ?>" title="<?php echo Lang::get('Home Page'); ?>">
		<?php echo escHTML(Config::get('general.site_title')); ?>
	</a>
</h1>

<p><?php echo Lang::get(Config::get('general.site_tagline')); ?></p>
