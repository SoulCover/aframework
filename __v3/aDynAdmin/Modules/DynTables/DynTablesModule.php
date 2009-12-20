<?php
	class aDynAdmin_DynTablesModule {
		public static $tplVars = array();
		public static $tplFile = true;

		public static function run () {
			if (!ADMIN) {
				FourOFour::run();
			}

			# Change style
			aFramework_BaseModule::$tplVars['style'] = '__dynadmin';

			# Get all tables
			self::$tplVars['tables'] = DynItem::getTables(explode(',', Config::get('db.translated_tables')), CURRENT_LANG, explode(',', Config::get('general.allowed_langs')));
		}
	}
?>
