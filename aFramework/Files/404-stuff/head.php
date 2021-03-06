<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">

	<head>

		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />

		<style type="text/css">
			html {
				background: #d4d0c8;

				margin: 0;
				padding: 0;

				font: 100%/1.2 Arial, sans-serif;
				color: #000;
			}

				#wrapper {
					background: #fff url(chrome://global/skin/icons/warning-large.png) no-repeat 30px 30px;

					width: 550px;

					margin: 40px auto;
					padding: 20px 30px 20px 100px;

					border: 1px solid threedshadow;

					-moz-border-radius: 10px;
					-webkit-border-radius: 10px;
					borde-radius: 10px;
				}

					h1, 
					h2, 
					h3 {
						margin: 20px 0 10px;
						font-size: 140%;
						border-bottom: 1px solid #ccc;
					}

					h2 {
						font-size: 120%;
					}

					h3 {
						font-size: 100%;
						border: 0;
					}

					p {
						margin: 0 0 5px;
					}

					#wrapper > p:last-child {
						padding: 10px 0 0 0;
						border-top: 1px solid #ccc;
					}

					ul, 
					ol {
						margin: 0 0 10px 30px;
						padding: 0;
					}

					a {
						color: blue;
						text-decoration: underline;
					}

					a:hover {
						color: red;
						text-decoration: underline;
					}

					small {
						font-size: 80%;
						color: #666;
					}

					form {
						margin: 0 0 10px;
						padding: 10px 0 0;
						border-top: 1px solid #ccc;
					}

					/* Search Results Pagination */
					#wrapper h2 + ol + ul {
						margin-left: 0;
						padding-left: 0;
						list-style: none;
						text-align: center;
					}

						#wrapper h2 + ol + ul > li {
							display: inline;
						}

						#wrapper h2 + ol + ul > li:after {
							content: " | ";
						}

						#wrapper h2 + ol + ul > li:last-child:after {
							content: "";
						}

							#wrapper h2 + ol + ul > li:first-child a:before {
								content: "< ";
							}

							#wrapper h2 + ol + ul > li:last-child a:after {
								content: " >";
							}
			<?php
				if (SU) {
					$constantsCSS = fetch(DOCROOT . 'aFramework/Modules/Base/0Constants.css');
					$debugCSS = fetch(DOCROOT . 'aFramework/Modules/Debug/Debug.css');

					echo CSSConstants::compile($constantsCSS . $debugCSS);
				}
			?>
		</style>

		<title>Error: 404 - Page Not Found - <?php echo Config::get('general.site_title'); ?></title>

	</head>

	<body class="<?php if (ADMIN) { ?>admin<?php } if (SU) { ?> su<?php } ?>">

		<div id="wrapper">

			<h1>Error: 404 - Page Not Found</h1>
