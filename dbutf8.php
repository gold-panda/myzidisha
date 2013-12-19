<?php
include("library/session.php");
global $database,$session;

// convert code
$q = 'SHOW TABLES';
$res=$db->getAll($q);
//$res = mysql_query("SHOW TABLES");
foreach ($res as $key => $tbl) {
	//var_dump($tbl);exit;
        $table = $tbl[key($tbl)];
		$q1 = "ALTER TABLE " . $table . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci";
        $db->query($q1);
		echo $q1."<br/>";
		
    }
?>