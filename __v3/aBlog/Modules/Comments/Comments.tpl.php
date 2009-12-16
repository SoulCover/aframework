<?php if ($comments) { ?>
	<ol>
		<?php foreach ($comments as $c) { ?>
			<li id="comment-<?php echo $c['comments_id']; ?>">
				<h4>
					<img src="http://www.gravatar.com/avatar.php?gravatar_id=<?php echo $c['email_md5']; ?>" alt="" /> 
					<?php if($c['website']) { ?>
						<a href="<?php echo $c['website']; ?>">
							<?php echo escHTML($c['author']); ?>
						</a>
					<?php } else { ?>
						<?php echo escHTML($c['author']); ?>
					<?php } ?>
				</h4>

				<p>
					<small>
						<a href="<?php echo Router::urlFor('Article', $c); ?>#comment-<?php echo $c['comments_id']; ?>">
							<?php echo Lang::get('Published'); ?> 
							<?php echo date(Config::get('general.date_format'), strtotime($c['pub_date'])); ?>
						</a>
					</small>
				</p>

				<?php echo NiceString::makeNice($c['content'], 5); ?>

				<?php if (ADMIN) { ?>
					<form method="post" action="">
						<p>
							<input type="hidden" name="comments_delete" value="1" />
							<input type="hidden" name="comments_id" value="<?php echo $c['comments_id']; ?>" />
							<input type="submit" value="<?php echo Lang::get('Delete'); ?>" />
						</p>
					</form>
				<?php } ?>
			</li>
		<?php } ?>
	</ol>
<?php } else { ?>
	<p>
		<?php echo Lang::get('No comments yet, why not'); ?> 
		<a href="#post-comment">
			<?php echo Lang::get('be the first to post one'); ?>
		</a>?
	</p>
<?php } ?>