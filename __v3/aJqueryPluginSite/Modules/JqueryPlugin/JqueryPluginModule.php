<?php
	class aJqueryPluginSite_JqueryPluginModule {
		public static $tplVars = array();
		public static $tplFile = true;

		public static function run() {
			if(isset($_GET['url_str'])) {
				self::$tplVars['plugin'] = JqueryPlugins::getByUrlStr($_GET['url_str']);

				if(!self::$tplVars['plugin']) {
					aFramework::$force404 = true;
				}
				else {
					aFramework_BaseModule::$tplVars['html_title'] = 'jQuery ' .self::$tplVars['plugin']['title'];
				}
			}
			else {
				aFramework::$force404 = true;
			}
		}
	}
?>