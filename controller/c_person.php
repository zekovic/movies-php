<?php

require_once GlobVars::$root_folder."/model/m_person.php";

use Model\Person;

class PersonController extends Controller
{
	public static function index() {
		$name = LibHtml::url_to_string(Info::$controller_option);
		$person = Person::get_by_name($name);
		Info::$result = ['person_type' => Info::$original_route];
		
		if (Info::$original_route == 'crew') {
			$person->get_crew_details();
			Info::$page_title = "Info about crew: $person->person_name";
		}
		if (Info::$original_route == 'actor') {
			$person->get_actor_details();
			Info::$page_title = "Info about actor: $person->person_name";
		}
		Info::$result['person'] = $person;
		self::show(Info::$original_route);
	}
	
	protected static function get_crew_details() {
		
	}
	protected static function get_actor_details() {
		
	}
}


PersonController::process();
