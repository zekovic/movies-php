<?php
//define("ROOT_FOLDER", __DIR__);
//define("SYSTEM_FOLDER", __DIR__."/system");

require_once __DIR__.'/src/settings.php';

GlobVars::$root_folder = __DIR__;
GlobVars::$system_folder = __DIR__."/system";

require_once GlobVars::$system_folder."/db.class.php";

require_once GlobVars::$system_folder.'/lib.php';
connect_db();

require_once GlobVars::$system_folder."/model.php";
require_once GlobVars::$system_folder."/ctrl.php";

require_once GlobVars::$system_folder.'/router_core.php';

require_once GlobVars::$root_folder.'/src/router.php';

require_once GlobVars::$root_folder.'/src/mylib.php';


Router::process();
Controller::load();
//Controller::funkcija();
exit;