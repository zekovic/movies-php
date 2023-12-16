<?php

require_once GlobVars::$root_folder."/model/m_person.php";
require_once GlobVars::$root_folder."/model/m_movie.php";

use Model\Person;

class PersonController extends Controller
{
	public static function index() {
		$person_arr = explode("-", Info::$controller_option);
		if (count($person_arr) > 0 && is_numeric($person_arr[0])) {
			$person_id = (int)($person_arr[0]);
			$person = new Person($person_id);
		} else {
			$name = LibHtml::url_to_string(Info::$controller_option);
			$person = Person::get_by_name($name);
		}
		Info::$result = ['person_type' => Info::$original_route];
		
		$person->get_crew_details();
		$person->get_actor_details();
		if ($person) {
			if (Info::$original_route == 'crew') {
				Info::$page_title = "Info about crew: $person->person_name";
			}
			if (Info::$original_route == 'actor') {
				Info::$page_title = "Info about actor: $person->person_name";
			}
		}
		
		Info::$result['movie_list'] = [];
		Info::$result['movie_list_actor'] = [];
		if (count($person->person_movies)) {
			Info::$result['movie_list'] = \Model\Movie::get_movie_list(-1, 0, ['id_list' => array_keys($person->person_movies)]);
		}
		if (count($person->actor_movies)) {
			Info::$result['movie_list_actor'] = \Model\Movie::get_movie_list(-1, 0, ['id_list' => array_column($person->actor_movies, 'movie_id')]);
		}
		Info::$result['person'] = $person;
		self::show(Info::$original_route);
	}
}


PersonController::process();
