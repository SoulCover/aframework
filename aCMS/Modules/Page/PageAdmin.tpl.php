<?php if ($errors) { ?>
	<p>
		<strong>
			<?php echo Lang::get('The form contains errors.'); ?> 
			<?php echo Lang::get('Please make sure you have filled out everything correctly.'); ?>
		</strong>
	</p>
<?php } ?>

<form method="post" action="">

	<p>
		<label>
			<strong>*</strong> <?php echo Lang::get('Page Title'); ?><br />
			<input type="text" name="title" value="<?php echo escHTML($page['title']); ?>"<?php if (!SU and $page['pages_id']) { ?> readonly="readonly"<?php } ?>/>
		</label>
	</p>

	<p>
		<label>
			<strong>*</strong> <?php echo Lang::get('Slug'); ?><br />
			<input type="text" name="url_str" value="<?php echo escHTML($page['url_str']); ?>"<?php if (!SU and $page['pages_id']) { ?> readonly="readonly"<?php } ?>/>
		</label>
	</p>

	<p>
		<?php echo Lang::get('Show in Navigation'); ?><br />
		<label>
			<input type="radio" name="in_navigation" value="1"<?php if ($page['priority'] or !$page['pages_id']) { ?> checked="checked"<?php } ?><?php if (!SU and $page['pages_id']) { ?> disabled="disabled"<?php } ?>/> 
			<?php echo Lang::get('Yes'); ?>
		</label> 
		<label>
			<input type="radio" name="in_navigation" value="0"<?php if (!$page['priority'] and $page['pages_id']) { ?> checked="checked"<?php } ?><?php if (!SU and $page['pages_id']) { ?> disabled="disabled"<?php } ?>/> 
			<?php echo Lang::get('No'); ?>
		</label>
	</p>

	<p>
		<label>
			<?php echo Lang::get('Priority'); ?> <small>(<?php echo Lang::get('A lower number places page early in the list'); ?>)</small><br />
			<input type="text" name="priority" value="<?php echo escHTML($page['priority']); ?>"<?php if (!SU and $page['pages_id']) { ?> readonly="readonly"<?php } ?>/>
		</label>
	</p>

	<p>
		<label>
			<?php echo Lang::get('Meta Keywords'); ?><br />
			<input type="text" name="meta_keywords" value="<?php echo escHTML($page['meta_keywords']); ?>"<?php if (!SU and $page['pages_id']) { ?> readonly="readonly"<?php } ?>/>
		</label>
	</p>

	<p>
		<label>
			<?php echo Lang::get('Meta Description'); ?><br />
			<textarea name="meta_description" rows="3" cols="60"<?php if (!SU and $page['pages_id']) { ?> readonly="readonly"<?php } ?>><?php echo escHTML($page['meta_description']); ?></textarea>
		</label>
	</p>

	<p>
		<label>
			<strong>*</strong> <?php echo Lang::get('Page Content'); ?><br />
			<textarea name="content" rows="20" cols="60"><?php echo escHTML($page['content']); ?></textarea>
		</label>
	</p>

	
	<p>
		<input type="hidden" name="pages_id" value="<?php echo $page['pages_id']; ?>" />
		<input type="hidden" name="page_submit" value="1" />
		<input type="submit" name="insert" value="<?php echo $page['pages_id'] ? Lang::get('Save Changes') : Lang::get('Add Page'); ?>" />
		<?php if ($page['pages_id'] and SU) { ?>
			 <?php echo Lang::get('or'); ?> 
			<input type="submit" name="page_delete" value="<?php echo Lang::get('Delete this Page'); ?>" />
		<?php } ?>
	</p>

</form>
