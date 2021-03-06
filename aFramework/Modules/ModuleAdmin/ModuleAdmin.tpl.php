<h2><?php echo Lang::get('Module admin'); ?></h2>

<?php foreach ($available_modules as $mod) { ?>
	<div id="mod-<?php echo $mod['html_id']; ?>"<?php if($mod['in_use']) { ?> class="in-use"<?php } ?>>

		<h3><?php echo $mod['name']; ?></h3>

		<?php if($mod['in_use']) { ?>
			<form method="post" action="">

				<p>
					<input type="hidden" name="module_admin_remove_module" value="1" />
					<input type="hidden" name="controller_in_use" value="<?php echo $_GET['controller']; ?>" />
					<input type="hidden" name="module_to_remove" value="<?php echo $mod['name']; ?>" />
					<input type="submit" value="<?php echo Lang::get('Remove module from controller'); ?>" />
				</p>

			</form>
		<?php } else { ?>
			<form method="post" action="">

				<p>
					<label>
						<input type="radio" name="add_type" value="append" checked="checked" /> 
						<?php echo Lang::get('Add to'); ?>
					</label> 
					<?php echo Lang::get('Or'); ?> 
					<label>
						<input type="radio" name="add_type" value="before" /> 
						<?php echo Lang::get('Insert before'); ?>
					</label>
				</p>

				<p>
					<select name="target">
						<option value="Base" class="base">Base</option>
						<?php foreach($modules_in_controller as $modc) { ?>
							<option value="<?php echo $modc['name']; ?>" class="<?php echo $modc['html_id']; ?>">
								<?php echo $modc['name']; ?>
							</option>
						<?php } ?>
					</select> 
					<input type="hidden" name="module_admin_add_module" value="1" />
					<input type="hidden" name="controller_in_use" value="<?php echo $_GET['controller']; ?>" />
					<input type="hidden" name="module_to_add" value="<?php echo $mod['name']; ?>" />
					<input type="submit" value="Go" />
				</p>

			</form>
		<?php } ?>

	</div>
<?php } ?>