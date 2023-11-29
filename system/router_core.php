<?php

class Router {
	
	public static $routes = [];
	
	public static function process() {
		global $argc, $argv;
		
		if (isset($argc) && isset($argv)) {
			
			// php index.php create_sql > model/sql_tables.php
			// php main.php create_sql > model/sql_tables.php
			
			if ($argv[1] == "create_sql") {
				require_once GlobVars::$system_folder."/model.php";
				echo Model\make_sql_tables_code();
			}
			
			exit;
			
		}
		
		Info::$request['url'] = $_SERVER['REQUEST_URI'];
		Info::$request['args'] = parse_url($_SERVER['REQUEST_URI']);
		if (Info::$request['args']) {
			Info::$request['path'] = [];
			Info::$request['args']['path'] = trim(Info::$request['args']['path'], '/');
			if (Info::$request['args']['path'] != "") {
				Info::$request['path'] = explode("/", Info::$request['args']['path']);
			}
			
		}
		if (isset(Info::$request['args']['query'])) {
			parse_str(Info::$request['args']['query'], Info::$request['query']);
		}
		// todo Info::$request['method'] = 'GET' , 'POST', ...
		
		//echo "<pre>".json_encode(Info::$request , JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."</pre>";
		
		if (count(Info::$request['path'])) {
			Info::$controller = Info::$request['path'][0];
			if (isset(static::$routes[Info::$request['path'][0]]) ) {
				Info::$controller = static::$routes[Info::$request['path'][0]];
			}
			if (count(Info::$request['path']) > 1) {
				Info::$controller_option = Info::$request['path'][1];
			}
			if (is_numeric(Info::$controller_option)) {
				Info::$controller_suboption = (int)Info::$controller_option;
				Info::$controller_option = 'id';
			}
			if (count(Info::$request['path']) > 2) {
				Info::$controller_suboption = Info::$request['path'][2];
			}
		} else if (GlobVars::$home_page != "") {
			Info::$controller = GlobVars::$home_page;
		} else {
			LibHtml::print_default_page();
			exit;
		}
		
		//require_once SYSTEM_FOLDER."/ctrl.php";
		//Controller::load();
		//exit;
	}
	
}

