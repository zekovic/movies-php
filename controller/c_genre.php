<?php

require_once GlobVars::$root_folder."/model/m_movie.php";

class GenreController extends Controller
{
	public static function index()
	{
		Info::$result = Model\Movie::get_movies_count_by_genre();
		$random_movies = Model\Movie::get_random_movies_by_genres();
		foreach (Info::$result as $i => $item) {
			Info::$result[$i]['random_movies'] = isset($random_movies[$item['genre_id']]) ? $random_movies[$item['genre_id']] : [];
		}
		//include_once GlobVars::$root_folder."/view/v_".Info::$controller.".php";
		self::show();
	}
	
}

GenreController::process();
