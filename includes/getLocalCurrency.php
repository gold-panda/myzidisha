<?php
	include_once("../library/session.php");
	$amount=0;
	$borrowerid=0;
	if(isset($_GET['amount'])){
		$amount=$_GET['amount'];
	}
	if(isset($_GET['borrowerid'])){
		$borrowerid=$_GET['borrowerid'];
	}
	$amount=str_replace(array('$' , ','),'',$amount);
	if(!eregi("^[0-9/.]", $amount)){
		echo "0";
	}
	else{
		$r=$amount*($database->getCurrentRate($borrowerid));
		$r=round($r, 2);
		$r=number_format($r, 2, ".", ",");
		echo "$r";
	}
?>