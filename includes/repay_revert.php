<?php 
include_once("library/session.php");
?>
<div class='span12'>
<?php if(isset($_SESSION['repayment_removed'])) {?>
<div style="color:green">Rpayment Removed successfully</div>
<?php unset($_SESSION['repayment_removed']);} ?>
<?php if(isset($_SESSION['repayment_not_removed'])) {?>
<div style="color:red">Rpayment could not removed.Try manually</div>
<?php unset($_SESSION['repayment_not_removed']);} ?>
<?php if(isset($_SESSION['not_athorized'])) {?>
<div style="color:red">You are not authorized to do this.</div>
<?php unset($_SESSION['not_athorized']);} ?>
	<form method="post" action="process.php">
	<table class='detail'>
	<tr>
		<td>Enter payment id</td>
		<td>
			<input type='text' name='payment_id'/>
			<input type="hidden" name="remove_payment">
			<input type="hidden" name="user_guess" value="<?php echo generateToken('remove_payment'); ?>"/>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<input type="submit" class='btn' value='Remove'>
		</td>
	</tr>
	</table>
		
	</form>
</div>