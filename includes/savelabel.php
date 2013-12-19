<?php

	include_once("../library/session.php");
	global $db;	
	$id=$_POST["q"];
	$text=utf8_encode($_POST['str']);
	$language=$_POST["filelang"];
	$res=0;
	if(!empty($language)) {
		$r = "SELECT mainkey, subkey from ! where id=?";
		$res1 = $db->getRow($r, array('labels',$id));
		$p = "SELECT id from ! where mainkey= ? and subkey=? and lang=?";
		$id = $db->getOne($p, array('labels',$res1['mainkey'], $res1['subkey'] , $language));
		if(empty($id))
		{
			$q = "INSERT into ! (mainkey, subkey, lang, text) values (?,?,?,?)";
			$res = $db->query($q, array('labels',$res1['mainkey'], $res1['subkey'], $language, $text));
		}
		else
		{
			$q = "UPDATE ! set text= ? where mainkey=? and subkey=? and lang=?";
			$res = $db->query($q, array('labels',$text, $res1['mainkey'], $res1['subkey'], $language ));
		}
	}
	if($res===1)
		echo "<font color=green>saved</font>";
	else
		echo "";
?>