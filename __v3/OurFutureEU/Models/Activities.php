<?php
	class Activities {
		public static function getByID ($id) {
			$res = DB::qry('
				SELECT
					*
				FROM
					activities
				WHERE
					activities_id = ' . escSQL($id) . '
				LIMIT 1'
			);

			if (mysql_num_rows($res)) {
				return mysql_fetch_assoc($res);
			}
			else {
				return false;
			}
		}

		public static function get ($sort = 'pub_date', $order = 'ASC', $start = 0, $limit = 10000000, $where = '1 = 1') {
			$res = DB::qry('
				SELECT
					*, 
					DATE_FORMAT(pub_date, "%Y") AS year, 
					DATE_FORMAT(pub_date, "%m") AS month, 
					DATE_FORMAT(pub_date, "%d") AS day
				FROM
					activities
				WHERE 
					' . $where . '
				ORDER BY
					' . escSQL($sort) . ' ' . escSQL($order) . '
				LIMIT
					' . escSQL($start) . ', ' . escSQL($limit)
			);

			if (mysql_num_rows($res) === 1) {
				return $limit === 1 ? mysql_fetch_assoc($res) : array(mysql_fetch_assoc($res));
			}
			elseif (mysql_num_rows($res) > 1) {
				$rows = array();

				while ($row = mysql_fetch_assoc($res)) {
					$rows[] = $row;
				}

				return $rows;
			}
			else {
				return false;
			}
		}

		public static function insert ($row) {
			$fields	= array(
				'title'				=> $row['title'], 
				'content'			=> $row['content'], 
				'pub_date'			=> (isset($row['pub_date']) and !empty($row['pub_date'])) ? $row['pub_date'] : date('Y-m-d H:i:s')
			);

			return DBRow::insert('activities', $fields);
		}

		public static function update ($id, $row) {
			$validFields = array(
				'title', 
				'content', 
				'pub_date'
			);
			$fields = array();

			foreach ($row as $col => $val) {
				if (in_array($col, $validFields)) {
					$fields[$col] = $val;
				}
			}

			return DBRow::update('activities', $id, $fields);
		}

		public static function delete ($id) {
			return DBRow::delete('activities', $id);
		}
	}
?>