<ol<?php if ($start > 1) { ?> start="<?php $start; ?>"<?php } ?>>
	<?php foreach ($comments as $c) { ?>
		<li<?php if ($c['karma'] < 1) { ?> class="spam"<?php } ?>>
			<h3>
				<img src="http://www.gravatar.com/avatar.php?gravatar_id=<?php echo $c['email_md5']; ?>" alt="" /> 
				<a href="<?php echo Router::urlFor('Article', $c); ?>#comment-<?php echo $c['comments_id']; ?>">
					<?php echo escHTML($c['author']); ?>
				</a> <?php echo Lang::get('on'); ?> 
				&quot;<a href="<?php echo Router::urlFor('Article', $c); ?>">
					<?php echo escHTML($c['article_title']); ?>
				</a>&quot;
			</h3>

			<?php echo NiceString::makeNice($c['content'], 4, false, 50); ?>		</li>
	<?php } ?>
</ol>
