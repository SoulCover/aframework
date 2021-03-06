<?php
	class HTMLPacker {
		public static function pack ($html, $exceptIn = array(
															'<script.*?>'	=> '</script>', 
															'<pre.*?>'		=> '</pre>', 
															'<textarea.*?>'	=> '</textarea>')) {

			$ignoredBlocks = array();

			# Extract all the parts that we're gonna leave alone
			foreach ($exceptIn as $startTag => $endTag) {
				$pattern = "#$startTag.*?$endTag#s";

				preg_match_all($pattern, $html, $ignoredBlocks[$startTag]);

				$html = preg_replace($pattern, '___[EX_' . $startTag . '_BLOCK]__', $html);
			}

			# Now remove all line-breaks and stuff
			$html = str_replace(array("\n", "\n\r", "\r\n" ,"\r" , "\t"), '', $html);

			# Now re-insert all the ignored blocks
			foreach ($ignoredBlocks as $tag => $matches) {
				foreach ($matches[0] as $block) {
					$html = self::strReplaceOnce('___[EX_' . $tag . '_BLOCK]__', $block, $html);
				}
			}

			return $html;
		}

		private static function strReplaceOnce ($needle, $replace, $haystack) {
			if ($pos = strpos($haystack, $needle)) {
				return substr_replace($haystack, $replace, $pos, strlen($needle));
			}

			return $haystack;
		}
	}
?>
