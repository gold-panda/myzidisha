<link rel="stylesheet" href="css/default/jquery.custom.css" type="text/css"></link>
<script src="includes/scripts/jquery.custom.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#date1").datepicker();
	$("#date2").datepicker();
	$('#totalHistory').click(function() {
		$('#totalHistoryDetails').slideToggle();
	});
});
$(function() {		
	$(".tablesorter_pending_borrowers").tablesorter({sortList:[[2,1]], widgets: ['zebra']});
	});	
</script>
<div class='span12'>
<div align='left' class='static'><h1>Member Repayment Rate</h1></div><br/>
<?php 
if($session->userlevel==ADMIN_LEVEL ) {
	$v=0;
	if(isset($_GET["v"])) {
		$v=$_GET["v"];
	}
	if(isset($_SESSION['date1']) ||isset($_SESSION['date2'])) {
		$date1=$_SESSION['date1'];
		$date2=$_SESSION['date2'];
	}
	else {
		$date1=$form->value("date1");
		$date2=$form->value("date2");
	}
	$fb='';
	if(isset($_GET['fb'])){
		$fb= $_GET['fb'];
	}
	$invite='';
	if(isset($_GET['invite'])){
		$invite= $_GET['invite'];
	}
	$text='';
	if(isset($_GET['text'])){
		$text= $_GET['text'];
	}

?>
	<form action='updateprocess.php' method="POST">
		<table class="detail">
			<tbody>
				<tr>
					<td><strong>Show members who joined from date:</strong></td>
					<td><input style="width:auto" name="date1" id="date1" type="text" value='<?php echo $date1 ;?>'/><br/><?php echo $form->error("fromdate"); ?></td>
					<td><strong>To date:</strong></td>
					<td><input style="width:auto"  name="date2" id="date2"type="text" value='<?php echo $date2 ;?>' /><br/><?php echo $form->error("todate"); ?></td>

				<tr><td></td><td><br/><br/></td></tr>
				<tr>

					<td><strong>Select Facebook status:</strong></td>
					<td><select id="fb" name="fb" >
						<option value='0'>All</option>
						<option value='1' <?php if($fb==1) echo "Selected='true'";?>>FB Not Linked</option>
						<option value='2' <?php if($fb==2) echo "Selected='true'";?>>FB Linked</option>

					</select></td>

				</tr>

				<tr><td></td><td><br/><br/></td></tr>
				<tr>

					<td><strong>Select invite status:</strong></td>
					<td><select id="invite" name="invite" >
						<option value='0'>All</option>
						<option value='1' <?php if($invite==1) echo "Selected='true'";?>>Was Invited By Existing Member</option>
						<option value='2' <?php if($invite==2) echo "Selected='true'";?>>Was Not Invited</option>
						<option value='3' <?php if($invite==3) echo "Selected='true'";?>>Has Invited Other Members</option>
						<option value='4' <?php if($invite==4) echo "Selected='true'";?>>Has Not Invited Other Members</option>

					</select></td>

				</tr>
				<tr><td></td><td><br/><br/></td></tr>
				<tr>

					<td><strong>Select length of free response text field in application:</strong></td>
					<td><select id="text" name="text" >
						<option value='0'>All</option>
						<option value='1' <?php if($text==1) echo "Selected='true'";?>>Less than 10 characters</option>
						<option value='2' <?php if($text==2) echo "Selected='true'";?>>10 - 40 characters</option>
						<option value='3' <?php if($text==3) echo "Selected='true'";?>>40 - 55 characters</option>
						<option value='4' <?php if($text==4) echo "Selected='true'";?>>Over 55 characters</option>

					</select></td>

				</tr>
				<tr><td></td><td><br/><br/></td></tr>
					<td>
						<input type="hidden" name="repayment_rate" id="repayment_rate">
						<input type="hidden" name="user_guess" value="<?php echo generateToken('repayment_rate'); ?>"/>
						<input type="hidden" name="row" id="row" value="0">
						<input class='btn' type='submit' name='report' align='right' value='Submit' />
					</td>
				</tr>
			</tbody>
		</table>
	</form><br/>
	<?php 
	if($v==1){
		$profile = $database->getActivatedBorrowers($date1, $date2, $fb, $invite, $text);
		$showingRes =count($profile);
		$completed_on = date('M d, Y', $rows['completed_on']);

		?>
		<p>Viewing <?php echo $showingRes?> Results.</p>


		<table class="zebra-striped tablesorter_pending_borrowers">
			<thead>
				<tr>
					<th>Name</th>
					<th>Country</th>
					<th>Date Submitted</th>
					<th>Payments Made On Time</th>
					<th>Payments Due</th>
					<th>On-Time Repayment Rate</th>
					
				</tr>
			</thead>
			<tbody>
			<?php 
				$totalTodayinstallment_sum = 0;
				$OnTimeinstallment_sum = 0;
				foreach($profile as $rows) {
					if($invite!=1){
						$borrowerid = $rows['userid'];
					}else{
						$borrowerid = $rows['invitee_id'];
					}
					$zidisha_name= $database->getNameById($borrowerid);
					$prurl= getUserProfileUrl($borrowerid);
					$link='index.php?p=7&id='.$borrowerid;
					$completed_on_sort = $rows['completed_on'];
					$completed_on = date('M d, Y', $rows['completed_on']);
					$country=$database->mysetCountry($rows['Country']);
					$RepayRate=$session->RepaymentRate($borrowerid);
					$totalTodayinstallment=$database->getTotalInstalAllLoans($borrowerid);
					$OnTimeinstallment=($RepayRate/100)*$totalTodayinstallment;
					$totalTodayinstallment_sum += $totalTodayinstallment;
					$OnTimeinstallment_sum += $OnTimeinstallment;
					$RepayRate_sum = ($OnTimeinstallment_sum / $totalTodayinstallment_sum) * 100;
					$text_length = $database->getTextResponseLength($borrowerid);

				

?>
						<tr>


							<td><a href="<?php echo $prurl;?>"><?php echo $zidisha_name; ?></a></td>
							<td><?php echo $country; ?></td>

							<td><span style='display:none'>$completed_on_sort</span><a href="<?php echo $link;?>"><?php echo $completed_on; ?></a><br/><br/><?php echo $text_length; ?></td>


							<td><?php echo number_format($OnTimeinstallment); ?></td>

							<td><?php echo $totalTodayinstallment; ?></td>

							<td><?php echo number_format($RepayRate); ?>%</td>



						</tr>
		<?php	}
	}
}
	?>
	</tbody>
				<tfoot>
					<tr>
						<th>Total Payments On Time:</th>
						<th><?php echo number_format($OnTimeinstallment_sum); ?></th>
						<th>Total Payments Due:</th>
						<th><?php echo $totalTodayinstallment_sum; ?></th>						<th>Total On-Time Repayment Rate:</th>
						<th><?php echo number_format($RepayRate_sum); ?>%</th>
					</tr>
				</tfoot>
</table>
</div>