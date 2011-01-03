<?php

include_once('../../config/config.inc.php');
include_once('../../init.php');
include_once('../../modules/shopImporter/shopImporter.php');

if (!Tools::getValue('ajax'))
	die('');

if (Tools::isSubmit('checkAndSaveConfig'))
{
	if ($link = @mysql_connect(Tools::getValue('server'), Tools::getValue('user'), Tools::getValue('password')))
	{
		if(!@mysql_select_db(Tools::getValue('database'), $link))
			die('{"hasError" : true, "error" : ["'.Tools::displayError('The database selection cannot be made.').'"]}');
		else
			die('{"hasError" : false, "error" : []}');
	}
	else
		die('{"hasError" : true, "error" : ["'.Tools::displayError('Link to database cannot be established.').'"]}');
	
}

if (Tools::isSubmit('getData'))
{	
	$moduleName = Tools::getValue('moduleName');
	$className =Tools::getValue('className');
	$getMethod = Tools::getValue('getMethod');
	$limit = Tools::getValue('limit');
	$server = Tools::getValue('server');
	$user = Tools::getValue('user');
	$password = Tools::getValue('password');
	$database = Tools::getValue('database');
	$prefix = Tools::getValue('prefix');
	$save = Tools::getValue('save');

	d('prout');

	if (file_exists('../../modules/'.$moduleName.'/'.$moduleName.'.php'))
	{
		require_once('../../modules/'.$moduleName.'/'.$moduleName.'.php');
		$importModule = new $moduleName();
		$importModule->prefix = $prefix;
		$importModule->initDatabaseConnection($server, $user, $password, $database);
		if (!method_exists($importModule, $getMethod))
			die('{"hasError" : true, "error" : ["not_exist"], "datas" : []}');
		else
		{
			$return = call_user_func_array(array($importModule, $getMethod), array($limit));
			$shopImporter = new shopImporter();
			$shopImporter->generiqueImport($className, $return, (bool)$save);
		}
	}
}

if (Tools::isSubmit('truncatTable'))
{	
	$moduleName = Tools::getValue('moduleName');
	$className =Tools::getValue('className');

	$shopImporter = new shopImporter();
	if ($shopImporter->truncateTable($className))
		die('{"hasError" : false, "error" : []}');
	else
		die('{"hasError" : true, "error" : ["'.$className.'"]}');

}

if (Tools::isSubmit('displaySpecificOptions'))
{
	$moduleName = Tools::getValue('moduleName');
	$server = Tools::getValue('server');
	$user = Tools::getValue('user');
	$password = Tools::getValue('password');
	$database = Tools::getValue('database');
	$prefix = Tools::getValue('prefix');
	
	
	if (file_exists('../../modules/'.$moduleName.'/'.$moduleName.'.php'))
	{
		require_once('../../modules/'.$moduleName.'/'.$moduleName.'.php');
		$importModule = new $moduleName();
		$importModule->prefix = $prefix;
		$return = $importModule->initDatabaseConnection($server, $user, $password, $database);
		if ($return)
			if (method_exists($importModule, 'displaySpecificOptions'))
				die($importModule->displaySpecificOptions());
			else
				die('not_exist');

		
	}	
}



?>