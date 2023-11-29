<?php
namespace Model;


class Movie extends SQL_movie
{
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
		$start = ($start ? (int)$start : 0);
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
			SELECT m.movie_id, m.title, /*m.overview,*/ m.release_date, group_concat(g.genre_name SEPARATOR', ') AS genres
			FROM movie m
			INNER JOIN movie_genres mg ON mg.movie_id = m.movie_id
			INNER JOIN genre g ON g.genre_id = mg.genre_id
			$where_str
			GROUP BY m.movie_id
			$genre_filter
			LIMIT $limit OFFSET $start
			
		";
		//echo $SQL;
		$result = \DB::query($SQL);
		return $result;
	}
}



