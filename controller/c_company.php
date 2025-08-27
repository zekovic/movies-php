<?php

use Model\Company;

require_once GlobVars::$root_folder."/model/m_company.php";

class CompanyController extends Controller {

	public static function index()
	{
		$url_arr = explode("-", Info::$controller_option);
		
		Info::$result = ['list' => Company::get_companies_list() , 'total' => Company::$items_count];
		Info::$page_title = "Companies (total ".Company::$items_count." items)";
		Info::$site_title = "Companies";
		self::show('companies');
	}
	
	public static function id() {
		
		$id = Info::$controller_suboption;
		$found = new Company($id);
		$found->get_details();
		Info::$result = $found;
		Info::$page_title = "About {$found->company_name}";
		Info::$site_title = $found->company_name;
		self::show('company_info');
		
	}

}

CompanyController::process();

