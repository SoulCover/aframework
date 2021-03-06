<h2>
	<a href="<?php echo Router::urlFor('Article', $article); ?>">
		<?php echo escHTML($article['title']); ?>
	</a>
</h2>

<p>
	<small>
		<?php echo Lang::get('Published %0', array(date(Config::get('general.date_format'), strtotime($article['pub_date'])))); ?>
	</small>
</p>

<?php echo NiceString::makeNice($article['content'], 3, $more_cut, false, true); ?>

<?php if ($more_cut) { ?>
	<p>
		<a href="<?php echo Router::urlFor('Article', $article); ?>">
			<?php echo Lang::get('Continue Reading'); ?> &raquo;
		</a>
	</p>
<?php } ?>

<dl>
	<dt><?php echo Lang::get('Tags'); ?></dt>
	<dd>
		<?php if ($article['tags']) { ?>
			<ul>
				<?php foreach ($article['tags'] as $t) { ?>
					<li>
						<a href="<?php echo Router::urlFor('ArticlesByTag', $t); ?>">
							<?php echo escHTML($t['title']); ?>
						</a>
					</li>
				<?php } ?>
			</ul>
		<?php } else { ?>
			<?php echo Lang::get('No tags'); ?>
		<?php } ?>
	</dd>
	<dt><?php echo Lang::get('Comments'); ?></dt>
	<dd>
		<a href="<?php echo Router::urlFor('Article', $article); ?>#comments">
			<?php echo $article['num_comments'] ? Lang::get('%0 comments', array($article['num_comments'])) : Lang::get('No comments'); ?>
		</a>
	</dd>
</dl>
