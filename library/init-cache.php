<?php
session_start();
include("constant.php");
if (0)
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
		
		if (0)
		{
			$dsn = 'mysql' . '://' . USER . ':' . PASS . '@' . PATH . '/' . NAME;
			$db = @DB::connect( $dsn, $db_options );
		}
		else
		{
			
			$dsn = 'mysqlc' . '://' . USER . ':' . PASS . '@' . PATH . '/' . NAME;
			$db = @cachedDB::connect( $dsn, $db_options );

		}
	}

}
	$db->setFetchMode(DB_FETCHMODE_ASSOC);






?>