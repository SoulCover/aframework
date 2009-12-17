<?php
	class Articles {
		public static function getArticlesGroupedByWeek ($month, $year) {
			
		}

		public static function getArticlesGroupedByMonth () {
			$articles	= self::get('pub_date', 'DESC');
			$dates		= array();
			$currDate	= false;

			if (is_array($articles)) {
				foreach ($articles as $a) {
					$monthYear = date('F Y', strtotime($a['pub_date']));

					if ($currDate === false or $currDate != $monthYear) {
						$currDate = $monthYear;

						$dates[$monthYear] = array(
							'month_year'	=> $monthYear, 
							'year'			=> date('Y', strtotime($a['pub_date'])), 
							'month'			=> date('m', strtotime($a['pub_date']))
						);
					}

					$dates[$monthYear]['articles'][] = $a;
				}
			}

			return count($dates) ? $dates : false;
		}

		public static function getArticlesByPubDate ($pubDate) {
			if (!is_numeric($pubDate)) {
				return false;
			}

			if (strlen($pubDate) == 4) {
				$dateFormatA	= '%Y';
				$dateFormatB	= '%Y';
				$date			= date('Y', mktime(0, 0, 0, 1, 1, $pubDate));
			}
			elseif (strlen($pubDate) == 6) {
				$dateFormatA	= '%Y%m';
				$dateFormatB	= '%M %Y';
				$date			= date('F Y', mktime(0, 0, 0, substr($pubDate, 4, 2), 1, substr($pubDate, 0, 4)));
			}
			elseif (strlen($pubDate) == 8) {
				$dateFormatA	= '%Y%m%d';
				$dateFormatB	= '%W, %M %D, %Y';
				$date			= date('l, F jS, Y', mktime(0, 0, 0, substr($pubDate, 4, 2), substr($pubDate, 6, 2), substr($pubDate, 0, 4)));
			}
			else {
				return false;
			}

			$res = DB::qry('
				SELECT
					articles.*, 
					DATE_FORMAT(articles.pub_date, "' . $dateFormatA . '") AS compare_date, 
					DATE_FORMAT(articles.pub_date, "' . $dateFormatB . '") AS show_date,
					DATE_FORMAT(articles.pub_date, "%Y") AS year, 
					DATE_FORMAT(articles.pub_date, "%m") AS month, 
					DATE_FORMAT(articles.pub_date, "%d") AS day, 
					COUNT(comments_id) as num_comments
				FROM 
					articles 
				LEFT JOIN
					comments USING(articles_id)
				GROUP BY
					articles.articles_id
				HAVING
					articles.pub_date <= NOW() AND 
					DATE_FORMAT(articles.pub_date, "' . $dateFormatA . '") = ' . $pubDate . '
				ORDER BY 
					articles.pub_date DESC
			');

			if (mysql_num_rows($res)) {
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

		public static function getArticlesByTagURLStr ($urlStr) {
			$res = DB::qry('
				SELECT
					articles.*, 
					tags.url_str AS tags_url_str, 
					DATE_FORMAT(articles.pub_date, "%Y") AS year, 
					DATE_FORMAT(articles.pub_date, "%m") AS month, 
					DATE_FORMAT(articles.pub_date, "%d") AS day, 
					COUNT(comments_id) as num_comments
				FROM
					articles
				LEFT JOIN
					article_tags USING(articles_id)
				LEFT JOIN
					comments USING(articles_id)
				LEFT JOIN
					tags USING(tags_id)
				WHERE
					articles.pub_date <= NOW() AND 
					tags.url_str LIKE BINARY "' . escSQL($urlStr) . '"
				GROUP BY
					articles.articles_id
				ORDER BY
					articles.pub_date DESC
			');

			if (mysql_num_rows($res)) {
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

		public static function getArticleByURLStr ($urlStr) {
			$res = DB::qry('
				SELECT
					articles.*, 
					DATE_FORMAT(articles.pub_date, "%Y") AS year, 
					DATE_FORMAT(articles.pub_date, "%m") AS month, 
					DATE_FORMAT(articles.pub_date, "%d") AS day, 
					COUNT(comments_id) as num_comments
				FROM
					articles
				LEFT JOIN
					comments USING(articles_id)
				GROUP BY
					articles.articles_id
				HAVING
					articles.pub_date <= NOW() AND 
					articles.url_str LIKE BINARY "' . escSQL($urlStr) . '"
				LIMIT 1
			');

			if (mysql_num_rows($res)) {
				return mysql_fetch_assoc($res);
			}
			else {
				return false;
			}
		}

		public static function getImages () {
			$articles	= Articles::get();
			$matches	= array();
			$images		= array();

			foreach ($articles as $article) {
				if (preg_match_all('/!\[(.*?)\]\((.*?)\)/', $article['content'], $matches)) {
					foreach ($matches[0] as $k => $v) {
						$images[] = array(
							'title'		=> ucwords(str_replace(array('-', '.jpg', '.gif', '.png'), array(' ', ''), basename($matches[2][$k]))),
							'src'		=> $matches[2][$k],
							'article'	=> $article
						);
					}
				}
			}

			return count($images) ? $images : false;
		}

		public static function get ($sort = 'pub_date', $order = 'DESC', $start = 0, $limit = 10000000, $where = '1 = 1') {
			$res = DB::qry('
				SELECT
					articles.*, 
					DATE_FORMAT(articles.pub_date, "%Y") AS year, 
					DATE_FORMAT(articles.pub_date, "%m") AS month, 
					DATE_FORMAT(articles.pub_date, "%d") AS day, 
					COUNT(comments_id) as num_comments
				FROM
					articles
				LEFT JOIN
					comments USING(articles_id)
				GROUP BY
					articles.articles_id
				HAVING
					articles.pub_date <= NOW() AND 
					' . $where . '
				ORDER BY
					articles.' . escSQL($sort) . ' ' . escSQL($order) . '
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
			$fields	 = array(
				'url_str'			=> $row['url_str'], 
				'title'				=> $row['title'], 
				'content'			=> $row['content'], 
				'pub_date'			=> (isset($row['pub_date']) and strlen($row['pub_date'])) ? $row['pub_date'] : date('Y-m-d H:i:s'), 
				'allow_comments'	=> $row['allow_comments'] ? 1 : 0, 
				'allow_rating'		=> $row['allow_rating'] ? 1 : 0, 
				'meta_keywords'		=> $row['meta_keywords'], 
				'meta_description'	=> $row['meta_description'], 
				'num_hits'			=> 0
			);

			return DBRow::insert('articles', $fields);
		}

		public static function update ($id, $row) {
			$validFields = array(
				'url_str', 
				'formatting', 
				'title', 
				'content', 
				'pub_date', 
				'allow_comments', 
				'allow_rating', 
				'meta_keywords', 
				'meta_description', 
				'num_hits'
			);
			$fields = array();

			foreach ($row as $col => $val) {
				if (in_array($col, $validFields)) {
					$fields[$col] = $val;
				}
			}

			return DBRow::update('articles', $id, $fields);
		}

		public static function delete ($id) {
			return DBRow::delete('articles', $id);
		}
	}
?>
