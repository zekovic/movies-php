<?php
namespace Model;
use DB;
use LibDB;

class Person extends SQL_person
{
	public static $items_count = 0;
	
	public static function get_by_name($str)
	{
		$result = DB::queryFirstRow("SELECT * FROM person WHERE person_name = %s", LibDB::clear_string($str));
		return $result ? new Person($result) : null;
	}
	
	public function get_crew_details()
	{
		$result = DB::query("SELECT m.title, d.department_name, mcr.* FROM movie_crew mcr
							LEFT JOIN movie m ON m.movie_id = mcr.movie_id
							LEFT JOIN department d ON mcr.department_id = d.department_id
							WHERE person_id = %i
							ORDER BY m.release_date, m.movie_id", $this->id());
		$this->group_by_movies($result ?? []);
	}
	public function get_actor_details()
	{
		$result = DB::query("SELECT m.title, mc.* FROM movie_cast mc
							LEFT JOIN movie m ON m.movie_id = mc.movie_id
							WHERE person_id = %i
							ORDER BY m.release_date, m.movie_id, mc.cast_order", $this->id());
		
		$this->values['actor_movies'] = [];
		if ($result) {
			foreach ($result as $i => $item) {
				$this->values['actor_movies'][$item['movie_id']] = $item;
			}
		}
	}
	
	public static function get_crew_list($limit = null, $start = 0, $filters = [])
	{
		$limit = ($limit ? (int)$limit : \GlobVars::$pagination_size);
		$start = ($start ? (int)$start : (\Info::$page_number - 1) * $limit);
		
		//var_dump($filters); exit;
		
		$where_arr = [];
		$db_args = [];
		$company_filter = "";
		$jobs_filter = "";
		if (count($filters)) {
			foreach ($filters as $i => $item) {
				$item = LibDB::clear_string($item);
				if (!$item) { continue; }
				if ($i == 'company') { $company_filter = " HAVING companies LIKE %s_company"; $db_args['company'] = "%$item%"; }
				//if ($i == 'genres') { $where_arr[] = "mg.genre_id IN %li_mgid"; $db_args['mgid'] = $item; }
				if ($i == 'jobs') {
					$jobs_arr = [];
					foreach ($item as $j => $j_id) {
						$jobs_arr[] = "_".(int)$j_id."_";
					}
					$jobs_filter = " HAVING jobs REGEXP '^".implode("|", $jobs_arr)."' ";
				}
				if ($i == 'crew_name') { $where_arr[] = "person_name LIKE %s_crewname"; $db_args['crewname'] = "%$item%"; }
			}
		}
		$where_str = "";
		if (count($where_arr)) {
			$where_str = " WHERE ".implode(" AND ", $where_arr);
		}
		
		$SQL = "
			SELECT p.*, mcr.movie_id, m.title, m.release_date, m.vote_average, mcr.department_id, 
				GROUP_CONCAT(DISTINCT CONCAT('_', d.department_id, '_', d.department_name, ' - ', mcr.job) SEPARATOR'\n') AS jobs,
				GROUP_CONCAT(DISTINCT CONCAT(pc.company_id, '_', pc.company_name) SEPARATOR'\n') AS companies
			FROM person p
			INNER JOIN movie_crew mcr ON p.person_id = mcr.person_id
			LEFT JOIN movie m ON m.movie_id = mcr.movie_id
			LEFT JOIN movie_company mcmp ON mcmp.movie_id = m.movie_id
			LEFT JOIN production_company pc ON pc.company_id = mcmp.company_id
			LEFT JOIN department d ON d.department_id = mcr.department_id
			$where_str
			GROUP BY CONCAT(p.person_id,'_', m.movie_id)
			$jobs_filter
			ORDER BY m.title, p.person_name
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
	
	public static function get_actor_list($limit = null, $start = 0, $filters = [])
	{
		$limit = ($limit ? (int)$limit : \GlobVars::$pagination_size);
		$start = ($start ? (int)$start : (\Info::$page_number - 1) * $limit);

		//var_dump($filters); exit;
		
		$where_arr = [];
		$db_args = [];
		$company_filter = "";
		$jobs_filter = "";
		if (count($filters)) {
			foreach ($filters as $i => $item) {
				$item = LibDB::clear_string($item);
				if (!$item) { continue; }
				if ($i == 'actor') { $where_arr[] = "person_name LIKE %s_actor"; $db_args['actor'] = "%$item%"; }
				if ($i == 'character') { $where_arr[] = "character_name LIKE %s_character"; $db_args['character'] = "%$item%"; }
			}
		}
		$where_str = "";
		if (count($where_arr)) {
			$where_str = " WHERE ".implode(" AND ", $where_arr);
		}
		
		$SQL = "
			SELECT p.*, m.movie_id, m.title, m.release_date, m.vote_average, mc.character_name, mc.gender_id, mc.cast_order
			FROM person p
			LEFT JOIN movie_cast mc ON mc.person_id = p.person_id
			LEFT JOIN movie m ON m.movie_id = mc.movie_id
			$where_str
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
	
	private function group_by_movies($items)
	{
		$movie_arr = [];
		foreach ($items as $i => $item) {
			$id = $item['movie_id'];
			if (!isset($movie_arr[$id])) {
				$movie_arr[$id] = [];
			}
			$movie_arr[$id][] = $item;
		}
		
		$this->values['person_movies'] = $movie_arr;
	}
}



