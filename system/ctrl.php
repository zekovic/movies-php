<?php

class Controller
{
	public static function load()
	{
		include_once GlobVars::$root_folder."/controller/c_".Info::$controller.".php";
		//static::process();
	}
	
	public static function show($view_file = null)
	{
		if (!$view_file) {
			$view_file = Info::$controller;
		}
		include_once GlobVars::$root_folder."/view/v_$view_file.php";
	}
	
	public static function process()
	{
		
		$calling_function = Info::$controller_option ? Info::$controller_option : 'index';
		if (method_exists(static::class, $calling_function)) {
			static::class::$calling_function();
			
		}
		//var_dump($calling_function);
		
	}
	
	public static function return()
	{
		self::print(Info::$result);
	}
	public static function return_json()
	{
		self::print(json_encode(Info::$result));
	}
	
	public static function print($data)
	{
		//ob_start();
		echo $data;
		//ob_clean();
		exit;
	}
}


