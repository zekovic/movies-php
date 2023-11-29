<?php

class GenreController extends Controller
{
	public static function index()
	{
		$x = Model\SQL_genre::load_data();
		foreach ($x as $i => $item) {
			$x[$i]['genre_name'] = mb_strtoupper($x[$i]['genre_name']);
		}
		//var_dump($x);
		//var_dump(Info::$result);
		
		Info::$result = $x;
		//include_once GlobVars::$root_folder."/view/v_".Info::$controller.".php";
		self::show();
	}
	
	public static function test01()
	{
		echo "Bre <b>bre</b>";
		self::show();
	}
}

GenreController::process();
