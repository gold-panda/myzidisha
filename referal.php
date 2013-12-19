<?php
include_once("library/session.php");
global $database,$session;
$time = time();
	$r="SELECT distinct(userid) from ! ORDER BY id ";
	$userids = $db->getALL($r,array('comments'));
	foreach($userids as $uid) {
		$r="SELECT id, refOfficial_number, refOfficial_name, userid from ! where userid = ?  ORDER BY id DESC";
		$refdetail = $db->getRow($r,array('comments',$uid['userid']));
		if($refdetail['refOfficial_number']!=null || $refdetail['refOfficial_name']!=null) {
			$q1 = "UPDATE ! set refOfficial_number = ?, refOfficial_name = ? where userid = ? ORDER BY id ASC LIMIT 1";
			$res1 = $db->query($q1, array('comments', $refdetail['refOfficial_number'], $refdetail['refOfficial_name'], $refdetail['userid']));
		}
	}
	
echo"completed";
exit;
?>
