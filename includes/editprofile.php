<script type="text/javascript" src="includes/scripts/submain.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<?php
include_once("library/session.php");
include_once("includes/error.php");
include_once("./editables/register.php");
$path=	getEditablePath('editprofile.php');
include_once("editables/".$path);
?>
<div class="span12">
<?php
if(isset($_GET['err']) && $_GET['err'] >0)
{
	echo "<table width='80%' bgcolor='red' align='center'><tr align='center'><td>";
	echo "<font color='white'>".$errorArray[$_GET['err']]."</font>";
	echo "</td></tr></table>";
}
if($session->userlevel==PARTNER_LEVEL)
{
	include_once("includes/p_edtiprofile.php");
}
else if($session->userlevel==LENDER_LEVEL)
{
	include_once("includes/l_editprofile.php");
}
else if($session->userlevel==BORROWER_LEVEL)
{
	include_once("includes/b_editprofile.php");
}
else if($session->userlevel==ADMIN_LEVEL)
{
}
?>
</div>