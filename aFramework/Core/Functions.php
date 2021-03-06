<?php
	/**
	 * Functions
	 *
	 * Random handy functions
	 **/
	# Helps build a msg url
	function msg ($title, $str = false, $error = false) {
		if ($str == false) {
			$str = Lang::get($title);
		}
		else {
			$str = Lang::get($title, false) . '|' . Lang::get($str, false);
		}

		if ($error) {
			$str .= '&type=error';
		}
		else {
			$str .= '&type=info';
		}

		return appendToQryStr('msg=' . $str, false);
	}

	# Like mysql_real_escape_string
	function escSQL ($str) {
		return mysql_real_escape_string(stripslashes($str));
	//	return (get_magic_quotes_gpc()) ? $str : mysql_real_escape_string($str); // this caused problems somewhere...
	}

	# Escapes the 5 characters used by HTML
	function escHTML ($str) {
		return str_replace(
					array(
						'&amp;', 
						'&', 
						'<', 
						'>', 
						'"', 
						"'"
					), 
					array(
						'&', 
						'&amp;', 
						'&lt;', 
						'&gt;', 
						'&quot;', 
						'&apos;'
					), 
					$str
				);
	}

	# Insert element in array at given position
	# from: http://snipplr.com/view/19898/insert-into--add-element-to-array-at-specific-position/ but modified
	function array_insert ($val, $pos, &$array) {
		$array2		= array_splice($array, $pos);
		$array[]	= $val;
		$array		= array_merge($array, $array2);
	}

	# Appends 'what' to query string
	function appendToQryStr ($what, $inHTML = true) {
		$qryStr = $_SERVER['QUERY_STRING'];

		if ($qryStr) {
			$newVars = explode('&', $what);

			foreach ($newVars as $var) {
				list($key, $val)	= explode('=', $var);
				$regExp				= "/$key=.*?(&|$)/";

				if (preg_match($regExp, $qryStr)) {
					$qryStr = preg_replace($regExp, "$key=$val" . '$1', $qryStr);
				}
				else {
					$qryStr .= "&$key=$val";
				}
			}
		}
		else {
			$qryStr = $what;
		}

		if ($inHTML) {
			return '?' . escHTML($qryStr);
		}
		else {
			return '?' . $qryStr;
		}
	}

	# Returns the current page url
	function currPageURL ($noQry = false) {
		$isHTTPS	= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on');
		$port		= (isset($_SERVER['SERVER_PORT']) && ((!$isHTTPS && $_SERVER['SERVER_PORT'] != "80") || ($isHTTPS && $_SERVER['SERVER_PORT'] != '443')));
		$port		= ($port) ? ':' . $_SERVER['SERVER_PORT'] : '';
		$url		= ($isHTTPS ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
		$url		= ($noQry && strpos($url, '?')) ? substr($url, 0, strpos($url, '?')) : $url;

		return $url;
	}

	# Determines whether it's naked day
	function is_naked_day ($d) {
		return false;

		$start	= date('U', mktime(-12, 0, 0, 04, $d, date('Y')));
		$end	= date('U', mktime(36, 0, 0, 04, $d, date('Y')));
		$z		= date('Z') * -1;
		$now	= time() + $z;

		if ($now >= $start && $now <= $end) {
			return true;
		}

		return false;
	}

	# Includes and returns contents instead of echo:ing
	function fetch ($f) {
		ob_start();
		include $f;
		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	}

	# Redirects and dies
	function redirect ($to) {
		header('Location: ' . $to);
		die('Redirect failed, please go to <a href="' . $to . '">' . $to . '</a>');
	}

	# Redirects to referrer
	function redirectToReferrer ($append) {
		$ref = $_SERVER['HTTP_REFERER'];
		$ref = (stristr($ref, '?')) ? $ref . '&' : $ref . '?';

		redirect($ref . $append);
	}

	# "Debug" variables
	function debug ($foo) {
		header('Content-type: text/plain; charset=utf-8');

		if (is_array($foo)) {
			echo '# ' . count($foo) . " elements #\n";
		}

		var_dump($foo);
		die;
	}

	function htmlentitiesDebug ($foo) {
		$bar = array_map_r('__htmlentitiesDebug', $foo);

		var_dump($bar);
	}

	function __htmlentitiesDebug ($foo) {
		return escHTML($foo);
	}

	function array_map_r ($func, $arr) {
		$newArr = array();

		foreach ($arr as $key => $value) {
			$newArr[$key] = (is_array($value) ? array_map_r($func, $value) : (is_array($func) ? call_user_func_array($func, $value) : $func($value)));
		}

		return $newArr;
	}

	# Replaces _first_ occurance of needle
	function str_replace_once ($needle, $replace, $haystack) {
		if ($pos = strpos($haystack, $needle)) {
			return substr_replace($haystack, $replace, $pos, strlen($needle));
		}

		return $haystack;
	}

	# Fixes camelCase to camel-case (or whatever $separator user wants)
	function ccFix ($str, $separator = '-') {
		$str = preg_replace('/([A-Z0-9]+)/', "$separator\\1", $str);

		if (substr($str, 0, 1) == $separator) {
			return substr($str, 1);
		}

		return $str;
	}

	# Cuts string to $reqLen's length and focuses around $sr (if existent)
	function searchResult ($str, $sr, $reqLen = 250) {
		# Make sure all linebreaks are \n
		$str = str_replace(array("\n\r","\r\n","\r"), "\n", $str);

		# Remove all [code], [more] and stuff like that
		$find = array(
			'/\[code\]/i', 
			'/\[more\]/i', 
			'/\[del\].*\[\/del\]/is', 
			'/!?\[[^\]]*\]\([^\)]*\)/', # Markdown images (unfortunately also links =/)
			'/^|\n#+/' # Markdown headings
		);
		$str = preg_replace($find, '', $str);

		# Cut the string so that search-terms are in the middle
		# (if any search-term IS in the string)
		$start = 0;
		if (($start = strpos(strtolower($str), strtolower($sr))) !== false) {
			$strLen = strlen($str);

			# We want the search-item in the middle of the result-string
			$start = $start - $reqLen / 2;

			# Now it might be a negative value, sort that out
			$start = ($start < 0) ? 0 : $start;

			# Calculate the difference between the start and the end of the string (it might be too short!)
			$diff = $strLen - $start;

			# If the result-string is too short
			if ($diff < $reqLen) {
				# Go back as much as needed to fit the number of characters desired
				$start = $start - $reqLen + $diff;

				# Now it might negative again, sort it!
				$start = ($start < 0) ? 0 : $start;
			}
		}

		$threeDotsFirst = ($start > 0) ? '[...] ' : '';
		$threeDotsLast = ((strlen($str) - strlen($start)) > $reqLen) ? ' [...]' : '';

		$str = escHTML($threeDotsFirst .substr($str, $start, $reqLen) .$threeDotsLast);

		# Make search terms bold
		$srs = explode(' ', $sr);

		foreach ($srs as $_sr) {
			$str = preg_replace("/($_sr)/i", '<strong>\\1</strong>', $str);
		}
		
		return $str;
	}

	# Extracts key/value pairs from $str
	function extractKeyValArray ($str) {
		$regexp		= '/@(.*?) ([^@]*)/is';
		$data		= array();
		$i			= 0;

		preg_match_all($regexp, $str, $matches);

		foreach ($matches[1] as $property) {
			$data[$property] = trim($matches[2][$i++]);
		}

		return $data;
	}
?>
