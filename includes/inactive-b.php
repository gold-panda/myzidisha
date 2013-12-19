<?php
include_once("library/session.php");
include_once("./editables/inactive-b.php");
?>
<div class='span12'>
<?php

if($session->userlevel != LENDER_LEVEL && $session->userlevel != PARTNER_LEVEL && $session->userlevel != ADMIN_LEVEL && !$is_mentor)
{
	echo $lang['inactive-b']['msg_to_allowed'];
}
else
{
	$sp=0;
	if(isset($_GET['s']))
	{
		$sp=$_GET['s'];
	}
	if(isset($_SESSION['Assigned']))
	{
		echo "<div align='center'><font color=green><b>Verification request has been sent to selected partner.</b></font></div>";
		unset($_SESSION['Assigned']);
	}
	if(isset($_SESSION['Declined']))
	{
		echo "<div align='center'><font color=green><b>Declined reason has been updated successfully.</b></font></div>";
		unset($_SESSION['Declined']);
	}
?>		
	
	<div align='left' class='static'><h1><?php echo $lang['inactive-b']['enter_report'] ?></h1></div>
<?php
	if($sp==0)
	{	
		include_once("includes/brwrlist-i.php");
	}
	else if($sp==1)
	{
		echo $lang['inactive-b']['activated'];
	?>
		<br /><br />
		<?php echo $lang['inactive-b']['Thank_you']; ?>
		<br />
		<?php echo $lang['inactive-b']['org_name'];
	}
}	?>
</div>