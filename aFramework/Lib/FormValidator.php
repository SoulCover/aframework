<?php
	class FormValidator {
		private static $commonFields = array(		
			'name'			=> '/^\S.*$/',											// name (atleast one character)
			'title'			=> '/^\S.*$/',											// title (atleast one character)
			'author'		=> '/^\S.*$/',											// author (atleast one character)
			'message'		=> '/^\S.*$/s',											// message (atleast one character)
			'comment'		=> '/^\S.*$/s',											// comment (atleast one character)
			'description'	=> '/^\S.*$/s',											// description (atleast one character)
			'dimensions'	=> '/^\d+x\d+$/',										// dimensions (DIGITxDIGIT)
			'price'			=> '/^\d+$/',											// price (atleast one digit)
			'url'			=> '/^http:\/\/?www?[^ ]+\.[^ ]{2,5}$/',				// url
			'email'			=> '/^.+?@.+?\..{2,4}$/'								// email
		);

		public static function validate ($fields, $addedFields = array()) {
			$vFields = array_merge(self::$commonFields, (array)$addedFields);

			foreach ($fields as $name => $v) {
				if (isset($vFields[$name])) {
					if (!preg_match($vFields[$name], $v)) {
						return false;
					}
				}
			}

			return true;
		}
	}
?>
