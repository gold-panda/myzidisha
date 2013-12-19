<h3 class="subhead">Enter Repayments</h3>
<?php
$ud = $_GET['u'];
$ld= $database->getUNClosedLoanid($ud);
if($ld == 0)
{
	echo '<p>No active loan for this borrower</p>';
	exit();
}
$schedule = $session->generateScheduleTable($ud, $ld, 1);
$name = $database->getNameById($ud);
if(!empty($schedule['schedule']))
{
	echo "<p>Use this page to enter repayments we have received from borrowers.</p><br /><strong>Repayment Schedule for ".$name."</strong></p>";

?>

<!-- comment by Julia 31-10-2013 pending repair of the write-off link

<div align="right">
<form name='defaultedform".$userid."' method='post' action='updateprocess.php'>
						<input name='makeLoanDefault' type='hidden' />
						<input type='hidden' name='user_guess' value='".generateToken('makeLoanDefault')."'/>
						<input name='borrowerid' value='$userid' type='hidden' />
						<input name='loanid' value='$loanid' type='hidden' />
						<a href='javascript:void(0)' style='color:red' onclick='javascript:mySubmit(5,defaultedform$userid);'>Write off loan</a>
						</form>

</div>
-->
	<?php
	echo $schedule['schedule'];
	echo $form->error('failure')."<br/>";
?>
	<form method='post' action='updateprocess.php'>
		<table class="detail" style="width:auto">
			<thead>
				<tr>
					<th>Select date paid:</th>
					<th>Enter amount paid:</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<input id='paiddate' class='borroweinstallmentDate' name='paiddate' type='text' value="<?php echo $form->value('paiddate');?>" autocomplete='off'/><br/>
						<?php echo $form->error('paiddate');?>
					</td>
					<td>
						<input type='text' id='paidamount' name='paidamt' value='<?php echo $form->value('paidamt')?>'/><br/>
						<?php echo $form->error('paidamt');?>
					</td>
					<td>
						<input name='Payment' value='0' type='hidden' />
						<input type="hidden" name="user_guess" value="<?php echo generateToken('Payment'); ?>"/>
						<input name='loanid' value='<?php echo $ld;?>' type='hidden' />
						<input name='userid' value='<?php echo $ud;?>' type='hidden' />
						<input class="btn" Type='submit' id ='paynow' onclick="return confirm_pay()" name='paynow' value='Confirm Repayment'>
					</td>
				</tr>
			</tbody>
		</table>
	</form>
<?php
}	?>
<script type="text/javascript">
<!--
	function confirm_pay() {
		var confirmed=false;
		var date=document.getElementById('paiddate').value;
		var paidamount=document.getElementById('paidamount').value;
		if(!date) {
			alert('Please select the repayment date');
			return false;
		}
		if(!paidamount) {
			alert('Please enter amount');
			return false;
		}
		var confirmed=confirm('You are entering a repayment received from this borrower on '+date+' in the amount of '+paidamount+'. Please confirm this is correct, and that this repayment has not already been entered in the schedule above.');
		
		if(confirmed)
			return true;
		else 
			return false;
	}
//-->
</script>