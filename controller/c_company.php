<?php

class CompanyController extends Controller {

	public static function index()
	{
		Info::$result = ['list' => Model\SQL_production_company::load_data() , 'total' => 1000];
		self::show();
	}

}

CompanyController::process();

