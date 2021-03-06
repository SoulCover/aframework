<?php
	class aFramework_NavigationModule {
		public static $tplVars = array();
		public static $tplFile = true;

		public static function run () {
			if (Config::get('navigation.home')) {
				self::addItem(Lang::get('Home'), Router::urlFor('Home'), 0);
			}

			if (Config::get('navigation.styles')) {
				self::addItem(Lang::get('Styles'), Router::urlFor('Styles'));
			}

			self::setSelectedNavigationItem();
		}

		public static function addItem ($title, $url, $pos = false) {
			if ($pos === false) {
				self::$tplVars['nav_items'][] = array('title' => $title, 'url' => $url);
			}
			else {
				if (!isset(self::$tplVars['nav_items'])) {
					self::$tplVars['nav_items'] = array();
				}

				array_insert(array('title' => $title, 'url' => $url), $pos, self::$tplVars['nav_items']);
			}
		}

		public static function removeItem ($pos) {
			array_splice(self::$tplVars['nav_items'], $pos, 1);
		}

		public static function setSelectedNavigationItem () {
			$url			= $_SERVER['REQUEST_URI'];
			$url			= explode('?', $url);
			$url			= $url[0];
			$tmpSelected	= array('url' => '', 'selected' => false);
			$numItems		= count(self::$tplVars['nav_items']);

			for ($i = 0; $i < $numItems; $i++) {
				self::$tplVars['nav_items'][$i]['selected'] = false;

				if (strpos($url, self::$tplVars['nav_items'][$i]['url']) === 0 and strlen($tmpSelected['url']) < strlen(self::$tplVars['nav_items'][$i]['url'])) {
					$tmpSelected['selected'] = false;

					self::$tplVars['nav_items'][$i]['selected'] = true;

					$tmpSelected = &self::$tplVars['nav_items'][$i];
				}
			}

			# If home is selected and we're not on the home-page, deselect it
			$homeURL = Router::urlFor('Home');

			for ($i = 0; $i < $numItems; $i++) {
				if (self::$tplVars['nav_items'][$i]['selected'] and self::$tplVars['nav_items'][$i]['url'] == $homeURL and $url != $homeURL) {
					self::$tplVars['nav_items'][$i]['selected'] = false;
				}
			}
		}
	}
?>
