<?php
	class Pages {
		public static function getPageByUrlStr ( $urlStr ) {
			$res = dbQry('
				SELECT
					*
				FROM
					' . Config::get('db.table_prefix') . 'pages
				WHERE
					url_str = "' . esc($urlStr) . '"
				LIMIT 1
			');

			if ( mysql_num_rows($res) ) {
				return mysql_fetch_assoc($res);
			}
			else {
				return false;
			}
		}

		public static function getPagesInNavigation (  ) {
			$res = dbQry('
				SELECT
					*
				FROM
					' . Config::get('db.table_prefix') . 'pages
				WHERE
					in_navigation = 1
				ORDER BY
					priority ASC
			');

			if ( mysql_num_rows($res) ) {
				$rows = array();
				
				while ( $row = mysql_fetch_assoc($res) ) {
					$rows[] = $row;
				}

				return $rows;
			}
			else {
				return false;
			}
		}

		public static function insert ( $row ) {
			$fields	= array(
				'url_str'			=> $row['url_str'], 
				'pub_date'			=> isset($row['pub_date']) ? $row['pub_date'] : date('Y-m-d H:i:s'), 
				'in_navigation'		=> $row['in_navigation'] ? 1 : 0, 
				'priority'			=> (isset($row['priority']) and is_numeric($row['priority'])) ? $row['priority'] : 0, 
				'title'				=> $row['title'], 
				'meta_keywords'		=> $row['meta_keywords'], 
				'meta_description'	=> $row['meta_description'], 
				'content'			=> $row['content']
			);

			return DBRow::insert(Config::get('db.table_prefix') .'pages', $fields);
		}

		public static function update ( $id, $row ) {
			$validFields = array(
				'url_str', 
				'pub_date', 
				'in_navigation', 
				'priority', 
				'title', 
				'meta_keywords', 
				'meta_description', 
				'content'
			);
			$fields = array();

			foreach ( $row as $col => $val ) {
				if ( in_array($col, $validFields) ) {
					$fields[$col] = $val;
				}
			}

			DBRow::update(Config::get('db.table_prefix') .'pages', $id, $fields);
		}

		public static function delete ( $id ) {
			DBRow::delete(Config::get('db.table_prefix') .'pages', $id);
		}
	}
?>