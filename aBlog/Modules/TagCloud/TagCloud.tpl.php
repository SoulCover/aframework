<ul>
	<?php foreach ($tags as $t) { ?>
		<li>
			<a href="<?php echo Router::urlFor('ArticlesByTag', $t); ?>">
				<?php echo escHTML($t['title']); ?>
			</a> 
			<strong>(<?php echo $t['num_articles']; ?>)</strong>
		</li>
	<?php } ?>
</ul>
