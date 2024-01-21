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
			Info::$site_title = $person->person_name;
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
	
	public static function find() {
		$find_arr = get_find_filter();
		//var_dump($find_arr);
		
		$valid_args = true;
		$filter = [];
		
		if (Info::$original_route == 'actor') {
			$actor = $character = null;
			if (isset($find_arr['actor'])) { $actor = LibDB::clear_string($find_arr['actor']); }
			if (isset($find_arr['character'])) { $character = LibDB::clear_string($find_arr['character']); }
			
			if (!$actor && !$character) { $valid_args = false; }
			if ($actor && mb_strlen($actor < 3)) { $valid_args = false; }
			if ($character && mb_strlen($character < 2)) { $valid_args = false; }
			
			if ($valid_args) {
				$filter = ['actor' => $actor, 'character' => $character];
				Info::$result = ['list' => Model\Person::get_actor_list(null, 0, $filter), 'total' => Model\Person::$items_count];
			}
			Info::$site_title = "Found actors";
		}
		
		if (Info::$original_route == 'crew') {
			$crew_name = $company = null;
			$jobs = [];
			if (isset($find_arr['crew_name'])) { $crew_name = LibDB::clear_string($find_arr['crew_name']); }
			if (isset($find_arr['company'])) { $company = LibDB::clear_string($find_arr['company']); }
			if (isset($find_arr['jobs'])) { foreach ($find_arr['jobs'] as $i => $item) { if ((int)$item) { $jobs[] = (int)$item; } } }
			
			if (!$crew_name && !$company && count($jobs) == 0) { $valid_args = false; }
			if ($crew_name && mb_strlen($crew_name) < 3) { $valid_args = false; }
			if ($company && mb_strlen($company) < 2) { $valid_args = false; }
			
			if ($valid_args) {
				$filter = ['crew_name' => $crew_name, 'company' => $company, 'jobs' => $jobs];
				Info::$result = ['list' => Model\Person::get_crew_list(null, 0, $filter), 'total' => Model\Person::$items_count];
			}
			Info::$site_title = "Found crew";
		}
		
		if (!$valid_args) {
			Info::$result = ['list' => [], 'total' => 0];
			//return;
		}
		Info::$page_title = "Found results (total ".Model\Person::$items_count." items)";
		//self::show();
		self::show(Info::$original_route."_list");
		
	}
	
}


PersonController::process();
