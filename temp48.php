<?php
	include("library/session.php");
	global $database,$session;
	
	$q="SELECT * from ! where name=? AND town=?";
	$result=$db->getAll($q,array('on_borrower_behalf', '', ''));
	pr($result);

	
	foreach($result as $row) {
		$q1="update ! set borrower_behalf_id=? where borrower_behalf_id=?";
		$res= $db->query($q1, array('borrowers',0,  $row['id']));
		
		$q2="delete from ! where id=?";
		$res1=$db->query($q2,array('on_borrower_behalf', $row['id']));
	}
	exit;
?>