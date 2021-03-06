<?php
	class aFramework_AutoStyleSwitcherModule {
		public static $tplVars = array();
		public static $tplFile = false;

		public static function run() {
			# Make sure user-selected style exists
			if (isset($_COOKIE['style']) and !is_dir(CURRENT_SITE_DIR . 'Styles/' . basename($_COOKIE['style']) . '/')) {
				self::setStyle(Config::get('general.default_style'));
			}

			# Don't do nothing if current site doesn't allow styles (has only one style)
			if (self::getNumStylesForCurrentSite() < 2) {
				return false;
			}

			# See if user wants to change style
			$tmp = array_merge($_GET, $_POST);

			if (isset($tmp['style']) and !empty($tmp['style'])) {
				self::setStyle($tmp['style']);
			}
		}

		private static function getNumStylesForCurrentSite () {
			$path		= CURRENT_SITE_DIR . 'Styles/';
			$numStyles	= 0;

			if (is_dir($path)) {
				$dh = opendir($path);

				while ($f = readdir($dh)) {
					if (substr($f, 0, 1) != '.' and substr($f, 0, 2) != '__' and is_dir($path . $f)) {
						$numStyles++;
					}
				}
			}

			return $numStyles;
		}

		private static function setStyle ($style) {
			$style = basename($style);

			if (is_dir(CURRENT_SITE_DIR . 'Styles/' . $style . '/')) {
				$oldStyle = isset($_COOKIE['style']) ? $_COOKIE['style'] : Config::get('general.default_style');

				setcookie('style', $style, time() + 31536000, WEBROOT);

				if (!XHR) {
					redirect(msg('Style Switcher', "You successfully changed style. If you don't like it you can always [change back](/styles/).") . '&style=');
				}
			}
		}
	}
?>
