<?php
include_once("library/session.php");
include_once("error.php");
include_once("./editables/admin.php");
?>
<script type="text/javascript" src="includes/scripts/admin.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<link rel="stylesheet" href="css/default/jquery.custom.css" type="text/css"></link>
<script src="includes/scripts/jquery.custom.min.js" type="text/javascript"></script>
<link href="css/default/popup_style.css?q=<?php echo RANDOM_NUMBER ?>" rel="stylesheet">
<style type="text/css">
	@import url(library/tooltips/btnew.css);
</style>	
<script type="text/javascript">
$(document).ready(function(){
	$(".borroweinstallmentDate").datepicker({ maxDate: new Date });
});

function mySubmit(a,id) 
{  
	if(a == 1){	
		document.forms.form_currency.submit();
	}
	else if(a == 2){
		var val=confirm("Are you sure! you want to delete borrower");
		if(val)		
			id.submit();	
		else
			alert("You have decided not to delete borrower !");
	}
	else if(a == 3){
		var val=confirm("Are you sure! you want to delete partner");
		if(val)
			id.submit();
		else
			alert("You have decided not to delete partner !");
	}
	else if(a == 4){
		var val=confirm("Are you sure! you want to delete lender");
		if(val)
			id.submit();
		else
			alert("You have decided not to delete lender !");
	}
	else if(a == 5){
		var val=confirm("Are you sure! you want to make this loan as written off");
		if(val)
			id.submit();
		else
			alert("You have decided not to make this loan as written off !");
	}
	else if(a == 6){
		var val=confirm("Are you sure! you want to undo written off loan");
		if(val)
			id.submit();
		else
			alert("You have decided not to undo written off loan !");
	}
}
</script>
<div class='span12'>
<?php
$a=0;
if(isset($_GET["a"]))
{
	$a=$_GET["a"];
}
if($session->userlevel==LENDER_LEVEL)
{
	$userid=$session->userid;
	$res=$database->isTranslator($userid);
	if($res==1)

		$isvolunteer=1;
} else {
		$isvolunteer=0;
} 

if($isvolunteer==0 && $session->userlevel != ADMIN_LEVEL)
{	?>
	<div>
		<p><?php echo $lang['admin']['allow']; ?></p>
		<p><?php echo $lang['admin']['Please']; ?><a href="index.php">click here</a><? echo $lang['admin']['for_more']; ?></p>
	</div>
<?php
}
else
{	
	if(isset($_GET['err']) && $_GET['err'] >0)
	{
		echo "<table width='80%' bgcolor='red' align='center'><tr align='center'><td>";
		echo "<font color='white'>".$errorArray[$_GET['err']]."</font>";
		echo "</td></tr></table>";
	}
	else if(isset($_GET['err1']) && $_GET['err1'] >0)
	{
		echo "<table width='80%' bgcolor='red' align='center'><tr align='center'><td>";
		echo "<font color='white'>Active Loan id is missing please set it first in borrowers table</font>";
		echo "</td></tr></table>";
	}
	if($a==0)
	{
		echo "<p>".$lang['admin']['welcome_page']."</p>";
		echo "<p>".$lang['admin']['thank_you']."</p>";
	}
	else if($a==1)
	{
		//view borrowers activate/deactivate
		include_once("includes/borrowers.php");
	}
	else if($a==2)
	{	
		//view activate/deactivate partners
		include_once("includes/partners.php");
	}
	else if($a==3)
	{
		//view lenders
		include_once("includes/lenders.php");
	}
	else if($a==4)
	{	
		//set exchange rate
		include_once("includes/exrate.php");
	}
	else if($a==5)
	{	
		//borrower payment or installments
		include_once("includes/installment.php");
	}
	else if($a==10)
	{	
		//admin settings
		include_once("includes/settings.php");
	}
	else if($a==11)
	{	//set exchange rate
			include_once("includes/currency.php");
			
	}
	else if($a==12)
	{	//set loan active deactive
		include_once("includes/manageloans.php");
	} 
	else if($a==13)
	{	//manage Repayment instructions
		include_once("includes/manage_r_instruction.php");
	} 
}	?>
</div>