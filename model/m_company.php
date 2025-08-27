<?php
namespace Model;
use DB;
use LibDB;

class Company extends SQL_production_company
{
	public static $items_count = 0;
	public $details;
	
	public static function get_by_name($str)
	{
		$result = DB::queryFirstRow("SELECT * FROM production_company WHERE company_name = %s", LibDB::clear_string($str));
		return $result ? new Company($result) : null;
	}
	
	public static function get_companies_list($limit = null, $start = 0, $filters = [])
	{
		$limit = ($limit ? (int)$limit : \GlobVars::$pagination_size);
		$start = ($start ? (int)$start : (\Info::$page_number - 1) * $limit);
		$where_arr = [];
		$db_args = [];
		$genre_filter = "";
		$genres_filter = "";
		$SQL_join = ""; // faster SQL when keyword and other filters not needed
		
		if (count($filters)) {
			foreach ($filters as $i => $item) {
				$item = LibDB::clear_string($item);
				if (!$item) { continue; }
				if ($i == 'genre') {
					$item = trim($item);
					$genre_filter = " HAVING genres LIKE %s_genre";
					$db_args['genre'] = "%$item%";
				}
				if ($i == 'genres') {
					$where_arr[] = "g.genre_id IN %li_genreids";
					$db_args['genreids'] = $item;
				}
				if ($i == 'year') {
					$item = (int)$item; $where_arr[] = "release_date LIKE %s_year";
					$db_args['year'] = "%$item%";
				}
				if ($i == 'title') {
					$where_arr[] = "title LIKE %s_title";
					$db_args['title'] = "%$item%";
				}
				if ($i == 'keyword') {
					$SQL_join .= "LEFT JOIN movie_keywords mk ON mk.movie_id = m.movie_id
									LEFT JOIN keyword k ON k.keyword_id = mk.keyword_id";
					$where_arr[] = "k.keyword_name LIKE %s_keyword";
					$db_args['keyword'] = "%$item%";
				}
				if ($i == 'language') {
					$SQL_join .= "LEFT JOIN movie_languages mlng ON mlng.movie_id = m.movie_id
									LEFT JOIN language lng ON lng.language_id = mlng.language_id";
					$where_arr[] = "lng.language_id = %i_language";
					$db_args['language'] = $item;
				}
				if ($i == 'country') {
					$SQL_join .= "LEFT JOIN production_country pcnt ON pcnt.movie_id = m.movie_id
									LEFT JOIN country cnt ON cnt.country_id = pcnt.country_id";
					$where_arr[] = "cnt.country_id = %i_country";
					$db_args['country'] = $item;
				}
				if ($i == 'id_list') {
					$where_arr[] = "m.movie_id IN %li_id";
					$db_args['id'] = $item;
				}
			}
		}
		
		$where_str = "";
		if (count($where_arr)) {
			$where_str = " WHERE ".implode(" AND ", $where_arr);
		}
		
		$SQL = "
			SELECT c.* ,
				GROUP_CONCAT(DISTINCT g.genre_name SEPARATOR', ') AS genres,
				GROUP_CONCAT(DISTINCT CONCAT('_', mg.genre_id, '_') SEPARATOR', ') AS genre_ids
			FROM production_company c
			
			LEFT JOIN movie_company mc ON c.company_id = mc.company_id
			LEFT JOIN movie_genres mg ON mg.movie_id = mc.movie_id
			LEFT JOIN genre g ON g.genre_id = mg.genre_id
			$SQL_join
			$where_str
			GROUP BY c.company_id
			$genre_filter $genres_filter
			ORDER BY c.company_name
		";
		$SQL_parsed = DB::parse($SQL, $db_args);
		//echo "$SQL \n\n $SQL_parsed";
		//exit;
		$SQL_limit = $SQL_parsed;
		if ($limit != -1) {
			self::$items_count = CountCache::get_count($SQL_parsed);
			$SQL_limit = "$SQL_parsed LIMIT $limit OFFSET $start";
		}
		
		$result = DB::query($SQL_limit);
		return $result;
	}
	
	public function get_details()
	{
		$this->details = [
			'genre' => $this->get_genre(),
			'movies' => $this->get_movies(),
		];
	}
	
	public function get_movies()
	{
		$result = DB::query("
			SELECT m.movie_id, m.title, m.budget, m.revenue, popularity, m.release_date, 
				m.runtime, m.movie_status, m.vote_average, m.vote_count,
				GROUP_CONCAT(DISTINCT g.genre_name SEPARATOR',') AS genres,
				GROUP_CONCAT(DISTINCT CONCAT('_', mg.genre_id, '_') SEPARATOR',') AS genre_ids,
				GROUP_CONCAT(DISTINCT cnt.country_iso_code SEPARATOR',') AS countries,
				GROUP_CONCAT(DISTINCT cnt.country_name SEPARATOR',') AS country_names
			FROM movie m
			
			INNER JOIN movie_company mc ON mc.movie_id = m.movie_id AND mc.company_id = %i
			LEFT JOIN movie_genres mg ON mg.movie_id = mc.movie_id
			LEFT JOIN genre g ON g.genre_id = mg.genre_id
			LEFT JOIN production_country pcnt ON pcnt.movie_id = m.movie_id
			LEFT JOIN country cnt ON cnt.country_id = pcnt.country_id
			
			GROUP BY m.movie_id
			ORDER BY m.release_date DESC", $this->id());
		return $result ?? [];
	}
	
	public function get_genre()
	{
		$result = DB::query("SELECT g.genre_id, g.genre_name
							FROM movie_genres mg
							INNER JOIN movie_company mc ON mc.movie_id = mg.movie_id
							LEFT JOIN genre g ON g.genre_id = mg.genre_id
							WHERE mc.company_id = %i
							GROUP BY g.genre_id", $this->id());
		return $result ?? [];
	}
	
	
}

