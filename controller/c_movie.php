<?php

require_once GlobVars::$root_folder."/model/m_movie.php";

class MovieController extends Controller
{
	public static function index()
	{
		//Info::$result = Model\SQL_movie::load_data(20);
		Info::$result = ['list' => Model\Movie::get_movie_list(), 'total' => Model\Movie::$items_count];
		Info::$page_title = "Movies (total ".Model\Movie::$items_count." items)";
		self::show();
	}
	public static function page()
	{
		$page = Info::$controller_suboption;
		Info::$result = ['list' => Model\Movie::get_movie_list(null, $page), 'total' => Model\Movie::$items_count];
		Info::$page_title = "Movies (total ".Model\Movie::$items_count." items)";
		self::show();
	}
	
	
	public static function id() {
		//$found = Model\SQL_movie::get(Info::$controller_suboption);
		$id = Info::$controller_suboption;
		$found = new \Model\Movie($id);
		$found->get_details();
		Info::$result = $found;
		Info::$page_title = "About {$found->title}";
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
		Info::$result = ['list' => Model\Movie::get_movie_list(null, 0, ['genre' => $genre]), 'total' => Model\Movie::$items_count];
		Info::$page_title = "Movies of $genre genre (total ".Model\Movie::$items_count." items)";
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
		Info::$result = ['list' => Model\Movie::get_movie_list(null, 0, ['year' => $year]), 'total' => Model\Movie::$items_count];
		Info::$page_title = "Movies from $year (total ".Model\Movie::$items_count." items)";
		self::show();
	}
	
	public static function find()
	{
		$find_arr = get_find_filter();
		
		//var_dump($find_arr); //exit;
		
		$valid_args = true;
		$title = $keyword = $year = null;
		$genres = [];
		if (isset($find_arr['title'])) { $title = LibDB::clear_string($find_arr['title']); }
		if (isset($find_arr['year'])) { $year = (int)($find_arr['year']); }
		if (isset($find_arr['keyword'])) { $keyword = LibDB::clear_string($find_arr['keyword']); }
		if (isset($find_arr['genres'])) { foreach ($find_arr['genres'] as $i => $item) { if ((int)$item) { $genres[] = (int)$item; } } }
		
		if (!$title && !$keyword && !$year && count($genres) == 0) { $valid_args = false; }
		if ($title && mb_strlen($title) < 3) { $valid_args = false; }
		if ($year && $year < 1900 || $year > 9999) { $valid_args = false; }
		if ($keyword && mb_strlen($keyword < 2)) { $valid_args = false; }
		
		if (!$valid_args) {
			Info::$result = ['list' => [], 'total' => 0];
			//return;
		} else {
			$filter = ['title' => $title, 'year' => $year, 'keyword' => $keyword, 'genres' => $genres];
			Info::$result = ['list' => Model\Movie::get_movie_list(null, 0, $filter), 'total' => Model\Movie::$items_count];
		}
		
		Info::$page_title = "Found movies (total ".Model\Movie::$items_count." items)";
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
