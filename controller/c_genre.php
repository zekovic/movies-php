<?php

require_once GlobVars::$root_folder."/model/m_movie.php";

class GenreController extends Controller
{
	public static function index()
	{
		Info::$result = Model\Movie::get_movies_count_by_genre();
		//include_once GlobVars::$root_folder."/view/v_".Info::$controller.".php";
		self::show();
	}
	
}

GenreController::process();
