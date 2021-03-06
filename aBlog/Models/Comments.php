<?php
	class Comments {
		public static function deleteAllSpamForArticle ($articlesID) {
			DB::qry('DELETE FROM {comments} WHERE articles_id = ' . escSQL($articlesID) . ' AND karma < 1');
		}

		public static function deleteCommentsForArticle ($articlesID) {
			DB::qry('DELETE FROM {comments} WHERE articles_id = ' . escSQL($articlesID));
		}

		public static function getByArticleID ($id) {
			return self::get('pub_date', 'ASC', 0, INFINITY, '{articles}.articles_id = ' . escSQL($id));
		}

		public static function getByArticleURLStr ($urlStr) {
			return self::get('pub_date', 'ASC', 0, INFINITY, '{articles}.url_str LIKE BINARY "' . escSQL($urlStr) . '"');
		}

		public static function get ($sort = '1', $order = 'ASC', $start = 0, $limit = INFINITY, $where = '1 = 1', $select = '1') {
			$where .= ADMIN ? '' : ' AND {comments}.karma > 0';

			$res = DB::qry('
				SELECT
					{comments}.*, 
					DATE_FORMAT({articles}.pub_date, "%Y") AS year, 
					DATE_FORMAT({articles}.pub_date, "%m") AS month, 
					DATE_FORMAT({articles}.pub_date, "%d") AS day, 
					{articles}.url_str, 
					{articles}.title AS article_title, 
					MD5({comments}.email) AS email_md5, 
					IF({comments}.website != "" AND SUBSTR({comments}.website, 1, 4) != "http", CONCAT("http://", {comments}.website), {comments}.website) AS clean_website, 
					' . $select . '
				FROM
					{comments}
				LEFT JOIN
					{articles} USING(articles_id)
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
			$fields = array(
				'articles_id'		=> $row['articles_id'], 
				'karma'				=> isset($row['karma']) ? $row['karma'] : SpamChecker::getKarma($row),
				'ip'				=> $_SERVER['REMOTE_ADDR'], 
				'author'			=> $row['author'], 
				'email'				=> $row['email'], 
				'website'			=> $row['website'], 
				'content'			=> $row['content'], 
				'pub_date'			=> isset($row['pub_date']) ? $row['pub_date'] : date('Y-m-d H:i:s')
			);

			return DBRow::insert('comments', $fields);
		}

		public static function update ($id, $row) {
			$validFields = array(
				'articles_id', 
				'karma', 
				'author', 
				'email', 
				'website', 
				'content', 
				'pub_date'
			);
			$fields = array();

			foreach ($row as $col => $val) {
				if (in_array($col, $validFields)) {
					$fields[$col] = $val;
				}
			}

			return DBRow::update('comments', $id, $fields);
		}

		public static function delete ($id) {
			return DBRow::delete('comments', $id);
		}
	}
?>
