<?php
namespace Model;
use DB;

class Movie extends SQL_movie
{
	public static $items_count;
	public $details;
	public $id;
	
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
		$limit = ($limit ? (int)$limit : 100);
		$start = ($start ? (int)$start : (\Info::$page_number - 1) * $limit);
		$where_arr = [];
		$genre_filter = "";
		if (count($filters)) {
			foreach ($filters as $i => $item) {
				//if ($i == 'genre') { $item = trim($item); $where_arr[] = "g.genre_name LIKE '%$item%'"; }
				if ($i == 'genre') { $item = trim($item); $genre_filter = " HAVING genres LIKE '%$item%'"; }
				if ($i == 'year') { $item = (int)$item; $where_arr[] = "release_date LIKE '%$item%'"; }
				if ($i == 'title') { $where_arr[] = "title LIKE '%$item%'"; }
			}
		}
		$where_str = "";
		if (count($where_arr)) {
			$where_str = " WHERE ".implode(" AND ", $where_arr);
		}
		$SQL = "
			SELECT m.movie_id, m.title, m.vote_average, m.release_date, group_concat(g.genre_name SEPARATOR', ') AS genres
			FROM movie m
			LEFT JOIN movie_genres mg ON mg.movie_id = m.movie_id
			LEFT JOIN genre g ON g.genre_id = mg.genre_id
			$where_str
			GROUP BY m.movie_id
			$genre_filter
		";
		self::$items_count = CountCache::get_count($SQL);
		$SQL_limit = "$SQL LIMIT $limit OFFSET $start";
		
		//echo $SQL;
		$result = DB::query($SQL_limit);
		return $result;
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
							WHERE mg.movie_id = %i", $this->id);
		return $result ?? [];
	}
	
	public function get_cast()
	{
		$result = DB::query("SELECT mc.*, p.person_name 
							FROM movie_cast mc
							LEFT JOIN person p ON p.person_id = mc.person_id
							WHERE mc.movie_id = %i
							ORDER BY cast_order", $this->id);
		return $result ?? [];
	}
	
	public function get_crew()
	{
		$result = DB::query("SELECT mcr.*, p.person_name, d.department_name
							FROM movie_crew mcr
							LEFT JOIN person p ON p.person_id = mcr.person_id
							LEFT JOIN department d ON d.department_id = mcr.department_id
							WHERE movie_id = %i
							ORDER BY department_id", $this->id);
		return $result ?? [];
	}
	
	public function get_keywords()
	{
		$result = DB::query("SELECT mk.*, k.keyword_name
							FROM movie_keywords mk
							LEFT JOIN keyword k ON k.keyword_id = mk.keyword_id
							WHERE mk.movie_id = %i", $this->id);
		return $result ?? [];
	}
	
	public function get_country()
	{
		$result = DB::query("SELECT pcnt.*, cnt.country_iso_code, cnt.country_name
							FROM production_country pcnt
							LEFT JOIN country cnt ON cnt.country_id = pcnt.country_id
							WHERE pcnt.movie_id = %i", $this->id);
		return $result ?? [];
	}
	
	public function get_company()
	{
		$result = DB::query("SELECT mcmp.*, cmp.company_name
							FROM movie_company mcmp
							LEFT JOIN production_company cmp ON cmp.company_id = mcmp.company_id
							WHERE mcmp.movie_id = %i", $this->id);
		return $result ?? [];
	}
	
	public function get_language()
	{
		$result = DB::query("SELECT ml.*, l.language_code, l.language_name, lr.language_role
							FROM movie_languages ml
							LEFT JOIN language l ON l.language_id = ml.language_id
							LEFT JOIN language_role lr ON lr.role_id = ml.language_role_id
							WHERE ml.movie_id = %i", $this->id);
		return $result ?? [];
	}
	
	
}



