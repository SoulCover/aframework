<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

	<head>

		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<meta name="author" content="<?php echo SITE_AUTHOR; ?>" />
		<meta name="copyright" content="Copyright (c) <?php echo date('Y') .' ' .SITE_AUTHOR; ?>" />
		<meta name="keywords" content="<?php echo $meta_keywords; ?>" />
		<meta name="description" content="<?php echo $meta_description; ?>" />
		<meta name="robots" content="all" />

		<link rel="alternate" type="application/rss+xml" title="<?php echo SITE_TITLE; ?> Articles" href="<?php echo WEBROOT; ?>?mod=ArticleListing&amp;rss=1" />
		<link rel="shortcut icon" type="image/ico" href="<?php echo WEBROOT; ?>favicon.ico" />
		<?php if(!NAKED_DAY) { ?>
			<link rel="stylesheet" type="text/css" media="screen,projection" href="<?php echo WEBROOT; ?>?module=CSSCompressor&amp;s=<?php echo $style; ?>" />
		<?php } ?>

		<title><?php echo $html_title; ?> - <?php echo SITE_TITLE; ?></title>

	</head>

	<body id="<?php echo $body_id; ?>-page" class="js-disabled <?php echo $time_body_class; ?> <?php echo $weather_body_class; ?> <?php echo ADMIN ? 'admin' : 'not-admin'; ?> <?php echo DEBUG ? 'debug' : 'not-debug'; ?>">

		<script type="text/javascript">
			document.body.className = document.body.className.replace('js-disabled', 'js-enabled');
			WEBROOT = '<?php echo WEBROOT; ?>';
		</script>

		<!--[if IE]>
			<div id="ie-warning">

				<p>Your browser is outdated and unsafe. For a richer browsing experience, please consider upgrading to <a href="http://www.getfirefox.com">a better, modern browser</a>. You would also <a href="http://www.savethedevelopers.org/">help save the developers</a> :)</p>

			</div>
		<![endif]-->

		<noscript>

			<p>You're really missing out coming here with <abbr title="JavaScript">JS</abbr> disabled. Everything still works but it's so much nicer with JS enabled. If you <em>had</em> JS enabled and also "ajax-mode" enabled, <a href="?ajax_mode=0">click here to disable it</a>.</p>

		</noscript>

		<?php if(NAKED_DAY) { ?>
			<div id="naked-day-info">

				<p>Today it's CSS naked day, that's why I'm all naked. I've also taken the liberty of disabling JavaScript - I believe it's equally important to do accessible JS.</p>

				<p>To learn more about naked day visit the <a href="http://naked.dustindiaz.com" title="Web Standards Naked Day Host Website">Annual CSS Naked Day</a> website.</p>

			</div>
		<?php } ?>

		<?php echo $child_modules; ?>

		<?php if(!NAKED_DAY) { ?>
			<script type="text/javascript" src="<?php echo WEBROOT; ?>?module=JSCompressor&amp;s=<?php echo $style; ?>"></script>
		<?php } ?>

		<?php if(GA_ID) { ?>
			<script type="text/javascript">
				var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
				document.write(unescape("%3Cscript src='" +gaJsHost +"google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
			</script>
			<script type="text/javascript">
				var pageTracker = _gat._getTracker("<?php echo GA_ID ?>");
				pageTracker._initData();
				pageTracker._trackPageview();
			</script>
		<?php } ?>

	</body>

</html>