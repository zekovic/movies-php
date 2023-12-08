<?php


class LibHtml {
	public static function print_header() {
		/*?>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?= GlobVars::$site_name ?></title>
		<script type="text/javascript" src="/assets/jquery-3.7.1.min.js"></script>
		<script type="text/javascript" src="/assets/jquery-ui.min.js"></script>
		
		<script type="text/javascript" src="/assets/page.js?a=<?= time() ?>"></script>
		<link rel="stylesheet" type="text/css" href="/assets/style.css?a=<?= time() ?>">
		<?php
		*/
		self::print_part("header");
	}
	
	public static function print_default_page() {
		echo "<h2>Empty site</h2><p>no text here...</p>";
	}
	
	public static function print_part($page) {
		include GlobVars::$root_folder."/templates/$page.php";
	}
	
	public static function string_for_url($str) {
		return urlencode(str_replace(" ", "-", mb_strtolower($str)));
	}
}


class LibTime {
	static function unix_to_yyyy_mm_dd($unix_time) {
		return "--- $unix_time ---";
	}
	static function unix_to_dd_mm_yyyy($unix_time) {
		return "----- $unix_time -----";
	}
	static function yyyy_mm_dd_to_unix($str) {
		return "-.-.- $str -.-.-";
	}
	
	static function yyyy_mm_dd_to_unix_2($str) {
		return "asdfghj";
	}
	
	static function hours_to_hh_mm($hours) {
		$hours = (int)$hours;
		$hh = floor($hours / 60);
		$mm = $hours % 60;
		return sprintf('%02d:%02d', $hh, $mm);
	}
}

class LibDB {
	static function connect_db() {
		//require_once GlobVars::$system_folder."/db.class.php";
		DB::$user = GlobVars::$db_user;
		DB::$password = GlobVars::$db_password;
		DB::$dbName = GlobVars::$db_dbName;
		DB::$encoding = GlobVars::$db_encoding;
	}
	
	static function clear_string($str) {
		var_dump($str);
		return str_ireplace(["%"], [""], $str);
	}
}



class Info {
	public static $request = ["url" => null, "args" => null, "query" => null, "path" => null];
	public static $controller = false;
	public static $controller_option = false;
	public static $controller_suboption = false;
	public static $result;
	public static $page_title = "";
	public static $page_number = 1;
}


