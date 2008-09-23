<?php
	class aFramework_CodeCompressorModule {
		public static $tplVars = array();
		public static $tplFile = true;

		protected static $type;
		protected static $exclude = array();

		private static $mimeTypes = array(
			'css' => 'text/css', 
			'js' => 'application/x-javascript'
		);

		public static function run() {
			header('Content-type: ' .self::$mimeTypes[self::$type]);

			$style = removeDots(@$_GET['s']);

			# If the requested style exists in the current site's Style-dir
			# it's considered a valid style.
			if(self::$type == 'css' and !is_dir(CURRENT_SITE_DIR .'Styles/' .$style .'/')) {
				return false;
			}

			# Get the highest last modified date of all the files in all the dirs
			$fileLastModified = self::getLastModifiedFile($style);

			# See if any cache of this script exists and that it's not older than any file
			$cachePath = DOCROOT .'aFramework/Cache/' .CURRENT_SITE .'_' .$style .'.' .self::$type;
			if(file_exists($cachePath) and filemtime($cachePath) >= $fileLastModified) {
				self::$tplVars[self::$type] = file_get_contents($cachePath);
			}
			# No cache or old, generate code
			else {
				# Get all the code in all the dirs of all the sites
				$code = self::getAllCodeInAllDirsOfAllSites($style);

				# JS gets packed
				if(self::$type == 'js') {
				#	$jsPacker = new JavaScriptPacker($code, 0);
				#	$code = $jsPacker->pack();
				}
				# CSS gets constant-treatment
				elseif(self::$type == 'css') {
					$code = CSSConstants::compile($code);
				}

				# Assign code to template
				self::$tplVars[self::$type] = $code;

				# Also create a cache
				file_put_contents($cachePath, $code);
			}
		}

		/**
		 * getLastModifiedFile
		 *
		 * Gets the last modified file
		 */
		private static function getLastModifiedFile($style) {
			$sites = explode(' ', SITE_HIERARCHY);
			$fileLastModified = 0;
			$dirs = array();

			foreach($sites as $site) {
				$dirs[] = DOCROOT .$site .'/Styles/__common/';
				$dirs[] = DOCROOT .$site .'/Styles/' .$style .'/';
				$dirs[] = DOCROOT .$site .'/Styles/' .$style .'/controllers/';
				$dirs[] = DOCROOT .$site .'/Styles/' .$style .'/modules/';

				# Only JS in module-dirs, no CSS
				if(self::$type == 'js' and is_dir(DOCROOT .$site .'/Modules/')) {
					$path = DOCROOT .$site .'/Modules/';

					if(is_dir($path)) {
						$dh = opendir($path);

						while($f = readdir($dh)) {
							if('.' != $f and '..' != $f and is_dir(DOCROOT .$site .'/Modules/' .$f .'/')) {
								$dirs[] = DOCROOT .$site .'/Modules/' .$f .'/';
							}
						}
					}
				}
			}

			foreach($dirs as $dir) {
				$flm = 0;

				if(is_dir($dir)) {
					$dh = opendir($dir);

					while($f = readdir($dh)) {
						if(self::$type == end(explode('.', $f))) {
							$tmpFlm = filemtime($dir .$f);

							if($tmpFlm > $flm) {
								$flm = $tmpFlm;
							}
						}
					}
				}

				if($flm > $fileLastModified) {
					$fileLastModified = $flm;
				}
			}

			return $fileLastModified;
		}

		/**
		 * getAllCodeInAllDirsOfAllSites
		 *
		 * Does what it's called; gets all code in all possible directories
		 */
		private static function getAllCodeInAllDirsOfAllSites($style) {
			$sites = explode(' ', SITE_HIERARCHY);
			$code = '';

			# We're starting backwards with the top-prio site first, 
			# its code should be last in the generated style
			foreach($sites as $site) {
				# CSS-dirs (there may be JS in them as well)
				# Controllers-dir
				$path = DOCROOT .$site .'/Styles/' .$style .'/controllers/';
				if(is_dir($path)) {
					$code = self::getCodeInDir($path, '-page') .$code;
				}

				# Modules-dir
				$path = DOCROOT .$site .'/Styles/' .$style .'/modules/';
				if(is_dir($path)) {
					$code = self::getCodeInDir(array($path), '') .$code;
				}

				# "Root"-dir
				$path = DOCROOT .$site .'/Styles/' .$style .'/';
				if(is_dir($path)) {
					$code = self::getCodeInDirs(array($path)) .$code;
				}

				# Common-dir
				$path = DOCROOT .$site .'/Styles/__common/';
				if(is_dir($path)) {
					$code = self::getCodeInDirs(array($path)) .$code;
				}

				# Actual module-js
				if(self::$type == 'js') {
					# Real modules-dir
					$dirs = array();
					$path = DOCROOT .$site .'/Modules/';

					if(is_dir($path)) {
						$dh = opendir($path);

						while($f = readdir($dh)) {
							if('.' != $f and '..' != $f and is_dir($path .$f)) {
								$dirs[] = $path .$f .'/';
							}
						}
					}

					$code = self::getCodeInDirs($dirs) .$code;
				}
			}

			return $code;
		}

		/**
		 * getCodeInDir
		 *
		 * Gets all code in a particular directory. Sorts on filename
		 */
		private static function getCodeInDirs($dirs, $prefixSelectorsWithFilename = false) {
			$code = '';
			$files = array();

			foreach($dirs as $dir) {
				$dh = opendir($dir);

				if($dh) {
					while($f = readdir($dh)) {
						if(self::$type == end(explode('.', $f)) and !in_array($f, self::$exclude)) {
							$files["$dir$f"] = strtolower($dir .$f);
						}
					}
				}
			}

			asort($files);

			foreach($files as $path => $name) {
				$code .= "\n\n/* ==== [ $name ] ==== */\n";

				$contents = file_get_contents($path);

				if(self::$type == 'css' and $prefixSelectorsWithFilename !== false) {
					$fileNameNoExt = substr($name, 0, -(strlen(end(explode('.', $name)))+1));

					$code .= CSSSelectorPrefixer::prefixSelectors($contents, '#' .$fileNameNoExt .$prefixSelectorsWithFilename);
				}
				else {
					$code .= $contents ."\n";
				}
			}

			return $code;
		}
	}
?>