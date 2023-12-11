<?php
namespace Model;
use DB;
use LibDB;

class Person extends SQL_person
{
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
							WHERE person_id = %i", $this->id());
		$this->values['crew_info'] = $result ?? [];
	}
	public function get_actor_details()
	{
		$result = DB::query("SELECT m.title, mc.* FROM movie_cast mc
							LEFT JOIN movie m ON m.movie_id = mc.movie_id
							WHERE person_id = %i", $this->id());
		$this->values['actor_info'] = $result ?? [];
	}
}



