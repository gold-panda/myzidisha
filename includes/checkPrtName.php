<?php

$pname="";
if(isset($_GET["partner"]){
	$pname=$_GET["partner"];
}
if(!$pname || strlen($pname=trim($pname))<1){
	return 0;
}
$r=$database->checkPartnerName($pname);

return $r;

?>