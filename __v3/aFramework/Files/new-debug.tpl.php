<?php
	ini_set('display_errors', false);
	$tplVars = aFramework::$debugInfo;
	$tplVars['routes'] = Router::getRoutes();
?>

<div id="new-debug">

	<h2>Debug Information</h2>

	<p>
		<small>
			<?php echo str_replace(DOCROOT, '', $tplVars['controller']['path']); ?>, 
			<?php echo round(Timer::stop(), 2); ?> sec(s), <?php $qryInfo = dbQry(false, true); echo $qryInfo['num_queries']; ?> queries.
		</small>
	</p>

	<ul>
		<li>
			<h3>Modules</h3>

			<ul>
				<?php foreach($tplVars['modules'] as $mod) { ?>
					<li title="<?php echo $mod['html_id']; ?>">
						<h4><?php echo $mod['class_name'] ? $mod['class_name'] : '[NoClass]' .'_' .$mod['name']; ?></h4>

						<p><?php echo isset($mod['path']) ? str_replace(DOCROOT, '', $mod['path']) : '[no module-class]'; ?></p>

						<dl>
							<dt>Run time</dt>
							<dd><?php echo $mod['run_time'] ? $mod['run_time'] : '[no run]'; ?> sec(s)</dd>
							<dt>Number of queries</dt>
							<dd><?php echo $mod['num_queries'] ? $mod['num_queries'] : '[none]'; ?></dd>
							<dt>Template paths</dt>
							<dd>
								<?php if(count($mod['tpl_paths'])) { ?>
									<dl>
										<?php foreach($mod['tpl_paths'] as $k => $v) { ?>
											<dt><?php echo ucfirst($k); ?></dt>
											<dd><?php echo $v != '' ? str_replace(DOCROOT, '', $v) : '[empty]'; ?></dd>
										<?php } ?>
									</dl>
								<?php } else { ?>
									[none]
								<?php } ?>
							</dd>
							<dt>Template variables</dt>
							<dd>
								<?php if(count($mod['tpl_vars'])) { ?>
									<dl>
										<?php foreach($mod['tpl_vars'] as $k => $v) { ?>
											<dt><?php echo $k; ?></dt>
											<dd>
												<?php 
													if(is_array($v)) {
														echo '<pre>';
														htmlentitiesDebug($v);
														echo '</pre>';
													}
													else {
														echo htmlentities($v) != '' ? htmlentities($v) : '[empty]';
													}
												?>
											</dd>
										<?php } ?>
									</dl>
								<?php } else { ?>
									[none]
								<?php } ?>
							</dd>
						</dl>
					</li>
				<?php } ?>
			</ul>
		</li>
		<li>
			<h3>$_GLOBAL variables</h3>

			<ul>
				<?php foreach (array('GET' => $_GET, 'POST' => $_POST, 'SESSION' => $_SESSION, 'COOKIE' => $_COOKIE, 'REQUEST' => $_REQUEST, 'SERVER' => $_SERVER) as $ok => $ov) { ?>					
					<?php if(count($ov)) { ?>
						<li>
							<h4>$_<?php echo $ok; ?></h4>

							<dl>
								<?php foreach($ov as $k => $v) { ?>
									<dt><?php echo $k; ?></dt>
									<dd><?php echo $v != '' ? $v : '[empty]'; ?></dd>
								<?php } ?>
							</dl>
						</li>
					<?php } ?>
				<?php } ?>
			</ul>
		</li>
		<li>
			<h3>Tools</h3>

			<ul>
				<li><a href="<?php echo WEBROOT; ?>?module=ExportDatabase">Export Database</a></li>
				<li><a href="<?php echo WEBROOT; ?>?module=ExportDatabase">Import Database</a></li>
				<li>Switch to <a href="?style=admin">Admin</a> or <a href="?style=hmm">Default</a> style</li>
			</ul>
		</li>
	</ul>

</div>