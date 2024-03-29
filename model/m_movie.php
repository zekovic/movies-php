<?php
namespace Model;
use DB;
use LibDB;

class Movie extends SQL_movie
{
	public static $items_count = 0;
	public $details;
	//public $id;
	
	protected static $options = [
		'runtime' => ['min' => 10, 'max' => 500, ],
	];
	/*function __construct($id = null)
	{
		if ($id) {
			// db::query where id = $id
			// if found fill properties with result
		}
	}*/
	
	public function __set($name, $value)
	{
		$set_value = $value;
		
		if ($name == 'title')
		{
			$set_value = ucfirst($value);
		}
		/*if ($name == "budget")
		{
			$set_value = (int)$value;
		}*/
		parent::__set($name, $set_value);
		//parent::__set($name, $value);
	}
	
	public static function get_movie_list($limit = null, $start = 0, $filters = [])
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
			SELECT m.movie_id, m.title, m.vote_average, m.release_date, 
				GROUP_CONCAT(DISTINCT g.genre_name SEPARATOR', ') AS genres,
				GROUP_CONCAT(DISTINCT CONCAT('_', g.genre_id, '_') SEPARATOR', ') AS genre_ids
			FROM movie m
			LEFT JOIN movie_genres mg ON mg.movie_id = m.movie_id
			LEFT JOIN genre g ON g.genre_id = mg.genre_id
			$SQL_join
			$where_str
			GROUP BY m.movie_id
			$genre_filter $genres_filter
			ORDER BY m.release_date DESC, m.movie_id
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
	
	public static function get_movies_count_by_genre()
	{
		$result = DB::query("SELECT mg.genre_id, g.genre_name, COUNT(mg.movie_id) AS movies_count
							FROM movie_genres mg
							LEFT JOIN genre g ON g.genre_id = mg.genre_id
							GROUP BY genre_id
							ORDER BY genre_name");
		return $result ?? [];
	}
	
	public static function get_random_movies_by_genres()
	{
		$random_arr = ["00", "00", "00"];
		foreach ($random_arr as $i => $item) {
			$random_arr[$i] = bin2hex(random_bytes(1));
		}
		
		$result = DB::query("SELECT MD5(mg.movie_id * mg.genre_id) as hash, g.genre_id, g.genre_name, COUNT(m.movie_id),
									GROUP_CONCAT(m.title SEPARATOR'\n') AS movie_titles,
									GROUP_CONCAT(m.movie_id SEPARATOR' ') AS movie_ids,
									GROUP_CONCAT(m.release_date SEPARATOR' ') AS movie_dates,
									GROUP_CONCAT(m.vote_average SEPARATOR' ') AS movie_ratings
								FROM movie_genres mg
								LEFT JOIN genre g ON g.genre_id = mg.genre_id
								LEFT JOIN movie m ON m.movie_id = mg.movie_id
								WHERE MD5(mg.movie_id * mg.genre_id) LIKE '%{$random_arr[0]}%'
									OR MD5(mg.movie_id * mg.genre_id) LIKE '%{$random_arr[1]}%'
									OR MD5(mg.movie_id * mg.genre_id) LIKE '%{$random_arr[2]}%'
								GROUP BY mg.genre_id");
		if (!$result) {
			return [];
		}
		$genre_arr = array();
		foreach ($result as $i => $row) {
			$titles = explode("\n", $row['movie_titles']);
			$ids = explode(" ", $row['movie_ids']);
			$dates = explode(" ", $row['movie_dates']);
			$ratings = explode(" ", $row['movie_ratings']);
			$genre_arr[$row['genre_id']] = array();
			foreach ($titles as $j => $title) {
				if ($j > 10) break;
				$genre_arr[$row['genre_id']][] = array(
					'id' => $ids[$j],
					'title' => $titles[$j],
					'date' => $dates[$j],
					'rating' => $ratings[$j]
				);
			}
		}
		
		return $genre_arr;
	}
	
	
	public function get_details()
	{
		$this->details = [
			'genre' => $this->get_genre(),
			'cast' => $this->get_cast(),
			'crew' => $this->get_crew(),
			'keywords' => $this->get_keywords(),
			'country' => $this->get_country(),
			'company' => $this->get_company(),
			'language' => $this->get_language(),
		];
		
	}
	
	public function get_genre()
	{
		$result = DB::query("SELECT mg.*, g.genre_name 
							FROM movie_genres mg
							LEFT JOIN genre g ON g.genre_id = mg.genre_id
							WHERE mg.movie_id = %i", $this->id());
		return $result ?? [];
	}
	
	public function get_cast()
	{
		$result = DB::query("SELECT mc.*, p.person_name 
							FROM movie_cast mc
							LEFT JOIN person p ON p.person_id = mc.person_id
							WHERE mc.movie_id = %i
							ORDER BY cast_order", $this->id());
		return $result ?? [];
	}
	
	public function get_crew()
	{
		$result = DB::query("SELECT mcr.*, p.person_name, d.department_name
							FROM movie_crew mcr
							LEFT JOIN person p ON p.person_id = mcr.person_id
							LEFT JOIN department d ON d.department_id = mcr.department_id
							WHERE movie_id = %i
							ORDER BY department_id", $this->id());
		return $result ?? [];
	}
	
	public function get_keywords()
	{
		$result = DB::query("SELECT mk.*, k.keyword_name
							FROM movie_keywords mk
							LEFT JOIN keyword k ON k.keyword_id = mk.keyword_id
							WHERE mk.movie_id = %i", $this->id());
		return $result ?? [];
	}
	
	public function get_country()
	{
		$result = DB::query("SELECT pcnt.*, cnt.country_iso_code, cnt.country_name
							FROM production_country pcnt
							LEFT JOIN country cnt ON cnt.country_id = pcnt.country_id
							WHERE pcnt.movie_id = %i", $this->id());
		return $result ?? [];
	}
	
	public function get_company()
	{
		$result = DB::query("SELECT mcmp.*, cmp.company_name
							FROM movie_company mcmp
							LEFT JOIN production_company cmp ON cmp.company_id = mcmp.company_id
							WHERE mcmp.movie_id = %i", $this->id());
		return $result ?? [];
	}
	
	public function get_language()
	{
		$result = DB::query("SELECT ml.*, l.language_code, l.language_name, lr.language_role
							FROM movie_languages ml
							LEFT JOIN language l ON l.language_id = ml.language_id
							LEFT JOIN language_role lr ON lr.role_id = ml.language_role_id
							WHERE ml.movie_id = %i", $this->id());
		return $result ?? [];
	}
	
	
}



