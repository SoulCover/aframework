<ul>
	<?php foreach($links as $l) { ?>
		<li>
			<a href="<?php echo $l['url']; ?>">
				<?php echo Lang::get('Skip to ' . $l['title']); ?>
			</a>
		</li>
	<?php } ?>
</ul>
