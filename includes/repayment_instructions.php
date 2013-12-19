<?php
include_once("library/session.php");
include_once("./editables/loginform.php");
$path=	getEditablePath('loginform.php');
include_once("editables/".$path);
?>
<div class="span12">
<?php
if($session->userlevel  == BORROWER_LEVEL)
{
	$ud=$session->userid;
	$data=$database->getBorrowerDetails($ud);
	$country=$data['Country'];
	$repayment_instruction=$database->getRepayment_InstructionsByCountryCode($country);
	if(empty($repayment_instruction)) {
		$repayment_instruction = $database->getDefaultRepayment_Instructions();
	}
	if(!empty($repayment_instruction))
	{	?>
		<div class="row">
			<div>
				<h3 class="subhead"><?php echo $lang['loginform']['b_repayment_instructions'] ?></h3>
				<p><?php echo nl2br($repayment_instruction['description']); ?></p>
			</div>
		</div><!-- /row -->
<?php
	}
	else
	{
		echo "No Repayment Instructions";
	}
}
?>
</div>