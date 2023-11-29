<?php

require_once GlobVars::$root_folder."/model/m_movie.php";

class MovieController extends Controller
{
	public static function index()
	{
		//Info::$result = Model\SQL_movie::load_data(20);
		Info::$result = Model\Movie::get_movie_list();
		Info::$page_title = "Movies";
		self::show();
	}
	
	public static function id() {
		$found = Model\SQL_movie::get(Info::$controller_suboption);
		Info::$result = $found;
		Info::$page_title = "About {$found['title']}";
		self::show('movie_info');
		
	}
	
	public static function genre()
	{
		$genre = isset(Info::$controller_suboption) ? Info::$controller_suboption : null;
		if (!$genre) {
			Info::$result = null;
			return;
		}
		$genre = urldecode($genre);
		//self::print("Movies of $genre genre...");
		Info::$result = Model\Movie::get_movie_list(null, 0, ['genre' => $genre]);
		Info::$page_title = "Movies of $genre genre";
		self::show();
		//self::show('movie');
	}
	
	public static function year()
	{
		$year = isset(Info::$controller_suboption) ? Info::$controller_suboption : null;
		if (!$year) {
			Info::$result = null;
			return;
		}
		$year = (int)$year;
		//self::print("Movies of $year genre...");
		Info::$result = Model\Movie::get_movie_list(null, 0, ['year' => $year]);
		Info::$page_title = "Movies from $year";
		self::show();
	}
	
	public static function stats()
	{
		self::show('movie_stats');
	}
	
	public static function details()
	{
		$id = isset(Info::$controller_suboption) ? Info::$controller_suboption : null;
		if (!$id) {
			Info::$result = null;
			return;
		}
		//$found = new Model\SQL_movie((int)$id);
		//Info::$result = $found->data();
		Info::$result = Model\SQL_movie::get($id);
		self::return_json();
	}
	
}

MovieController::process();
