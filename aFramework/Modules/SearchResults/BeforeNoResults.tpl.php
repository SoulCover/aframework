<h2>
	<?php echo Lang::get('No Search Results for'); ?> 
	&quot;<?php echo escHTML($_GET['q']); ?>&quot;
</h2>

<p>
	<?php echo Lang::get('Your search'); ?> - 
	&quot;<strong><?php echo @escHTML($_GET['q']); ?></strong>&quot; - 
	<?php echo Lang::get('did not match any documents.'); ?>
</p>