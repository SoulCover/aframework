<?php
	class aJqueryPluginSite_RandomJqueryPluginsModule {
		public static $tplVars = array();
		public static $tplFile = true;

		public static function run() {
			self::$tplVars['plugins'] = JqueryPlugins::get();

			shuffle(self::$tplVars['plugins']);

			self::$tplVars['plugins'] = array_slice(self::$tplVars['plugins'], 0, 3);
		}
	}
?>