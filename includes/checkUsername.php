<?php

include("../library/session.php");
$username="";
if(isset($_GET["username"])){
	$username=$_GET["username"];
}

$alphanum = eregi("^([0-9a-z])*$", $username);
$r=$database->usernameTaken($username);
 
if($r>0){
	echo 1;//1 for username exists
}
else if(!$alphanum)
{
    echo 2;//2 for alphanumeric existance
}
else{
	 echo 0;//userame not exists
}
?>