<?php
	class Config {
		private static $config = array();

		public static function set ($k, $v) {
			if (false === strpos($k, '.')) {
				self::$config[$k]			= isset(self::$config[$k]) ? self::$config[$k] : array('__info' => array());
				$oldInfo					= isset(self::$config[$k]['__info']) ? self::$config[$k]['__info'] : array();
				self::$config[$k]['__info']	= array_merge($oldInfo, array(
					'name'			=> $k, 
					'title'			=> (is_array($v) and isset($v['title'])) ? escHTML($v['title']) : escHTML(ucfirst(str_replace('_', ' ', $v))), 
					'description'	=> (is_array($v) and isset($v['description'])) ? escHTML($v['description']) : escHTML(ucfirst(str_replace('_', ' ', $v)))
				));
				return;
			}

			$levels	= explode('.', $k);
			$tmp	= &self::$config;

			foreach ($levels as $l) {
				$tmp = &$tmp[$l];
			}

			$tmp = array(
				'name'			=> $k, 
				'value'			=> is_array($v) ? $v['value'] : $v, 
				'key'			=> $k, 
				'highest_key'	=> $l, 
				'title'			=> (is_array($v) and isset($v['title'])) ? escHTML($v['title']) : escHTML(ucfirst(str_replace('_', ' ', $l))), 
				'description'	=> (is_array($v) and isset($v['description'])) ? escHTML($v['description']) : (is_array($v) ? escHTML($v['value']) : escHTML($v))
			);
		}

		public static function clear () {
			self::$config = array();
		}

		public static function get ($k) {
			$levels	= explode('.', $k);
			$tmp	= &self::$config;

			foreach ($levels as $l) {
				$tmp = &$tmp[$l];
			}

			return $tmp['value'];
		}

		public static function asArray () {
			$items		= array();
			$configs	= array();

			foreach (self::$config as $k => $v) {
				foreach (self::$config[$k] as $ik => $iv) {
					if('__info' != $ik) {
						$items[$ik] = self::$config[$k][$ik];
					}
				}

				$configs[] = array(
					'info'	=> self::$config[$k]['__info'], 
					'items'	=> $items
				);
				$items = array();
			}

			return $configs;
		}
	}
?>
