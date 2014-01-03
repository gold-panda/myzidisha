<?php
session_start();
include_once("constant.php");
if (1)
{
	require_once  PEAR_DIR .'DB.php';
}
else
{
	require_once  PEAR_DIR .'cachedDB.php';
}

if (!isset($db))
{
	$db_options = array('persistent' => TRUE );

	if (1)
	{
		
		if (1)
		{
			$dsn = 'mysql' . '://' . USER . ':' . PASS . '@' . PATH . '/' . NAME;
			$db = @DB::connect( $dsn, $db_options );
			if (PEAR::isError($db)){
				error_log("Error connecting to DB: " . $db->getMessage());
				header("location: landing_page/fullyfunded.html");
				exit;
			}
		}
		else
		{
			
			$dsn = 'mysqlc' . '://' . USER . ':' . PASS . '@' . PATH . '/' . NAME;
			$db = @cachedDB::connect( $dsn, $db_options );

		}
	}

}
	$db->setFetchMode(DB_FETCHMODE_ASSOC);
	mysql_query("SET NAMES 'utf8'");





?>