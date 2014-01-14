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
<div align='left' class='static'><h1>Loans Funded</h1></div><br/>

<p>This report shows the on-time repayment rate of all loans funded within a given date range. On-time payments are defined here as the amounts paid in full (within a threshold of $1 or the value of the installment amount, whichever is lesser) within 24 hours of the due date.  The report shows repayment data for installments due over two days ago only, to allow time for data entry.</p>

<br/><br/>

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

?>
	<form action='updateprocess.php' method="POST">
		<table class="detail">
			<tbody>
				<tr>
					<td><strong>Show loans funded from date:</strong></td>
					<td><input style="width:auto" name="date1" id="date1" type="text" value='<?php echo $date1 ;?>'/><br/><?php echo $form->error("fromdate"); ?></td>
					<td><strong>To date:</strong></td>
					<td><input style="width:auto"  name="date2" id="date2"type="text" value='<?php echo $date2 ;?>' /><br/><?php echo $form->error("todate"); ?></td>

				<tr><td></td><td><br/><br/></td></tr>

					<td>
						<input type="hidden" name="loans_funded" id="loans_funded">
						<input type="hidden" name="user_guess" value="<?php echo generateToken('loans_funded'); ?>"/>
						<input type="hidden" name="row" id="row" value="0">
						<input class='btn' type='submit' name='report' align='right' value='Submit' />
					</td>
				</tr>
			</tbody>
		</table>
	</form><br/>
	<?php 
	if($v==1){

		$profile = $database->getLoansFunded($date1, $date2);
		$showingRes =count($profile);

		?>
		<p>Viewing <?php echo $showingRes?> Results.</p>


		<table class="zebra-striped tablesorter_pending_borrowers">
			<thead>
				<tr>
					<th>Name</th>
					<th>Country</th>
					<th>Date Funded</th>
					<th>First Installment On Time</th>
					<th>First Installment Due</th>
					<th>Payments Made On Time (This Loan Only)</th>
					<th>Payments Due (This Loan Only)</th>
					<th>On-Time Repayment Rate (This Loan Only)</th>
					
				</tr>
			</thead>
			<tbody>
			<?php 
				$totalTodayinstallment_sum = 0;
				$OnTimeinstallment_sum = 0;
				foreach($profile as $rows) {
					$borrowerid = $rows['userid'];
					$zidisha_name= $database->getNameById($borrowerid);
					$prurl= getUserProfileUrl($borrowerid);
					$link='index.php?p=7&id='.$borrowerid;
					$loanid= $rows['loanid'];
					$funded_on_sort = $rows['AcceptDate'];
					$funded_on = date('M d, Y', $funded_on_sort);
					$country=$database->mysetCountry($rows['Country']);
					$loanDetail= $database->onTimeInstallmentsNoThreshold($borrowerid, $loanid);
					$totalTodayinstallment=$loanDetail['totalTodayinstallment'];
					$OnTimeinstallment=$loanDetail['totalTodayinstallment']- $loanDetail['missedInst'];
					$RepayRate=($OnTimeinstallment/$totalTodayinstallment)*100;
					$totalTodayinstallment_sum += $totalTodayinstallment;
					$OnTimeinstallment_sum += $OnTimeinstallment;
					$RepayRate_sum = ($OnTimeinstallment_sum / $totalTodayinstallment_sum) * 100;
					$firstinst_ontime = $database->wasFirstInstalOnTime($borrowerid, $loanid);
					if ($totalTodayinstallment>=1){
						$firstinst_due = 1;
					}else{
						$firstinst_due = 0;
					}
					$firstinst_ontime_sum += $firstinst_ontime;
					$firstinst_due_sum += $firstinst_due;
					$firstinst_rate_sum = ($firstinst_ontime_sum / $firstinst_due_sum)*100;
?>
						<tr>


							<td><a href="<?php echo $prurl;?>"><?php echo $zidisha_name; ?></a></td>
							
							<td><?php echo $country; ?></td>

							<td><span style='display:none'>$funded_on_sort</span><a href="<?php echo $link;?>"><?php echo $funded_on; ?></a></td>

							<td><?php echo $firstinst_ontime; ?></td>
							
							<td><?php echo $firstinst_due; ?></td>

							<td><?php echo number_format($OnTimeinstallment); ?></td>

							<td><?php echo $totalTodayinstallment; ?></td>

							<td><?php echo number_format($RepayRate); ?>%</td>



						</tr>
		<?php	}
	}
}
	?>
	</tbody>
			
</table>

<br/><br/><br/>

<strong>
<p>Total 1st Installments On Time:  <?php echo number_format($firstinst_ontime_sum); ?></p>
						
<p>Total 1st Installments Due:  <?php echo number_format($firstinst_due_sum); ?></p>
						
<p>1st Installment On-Time Repayment Rate:  <?php echo number_format($firstinst_rate_sum); ?>%</p>
						
<p>Total Payments On Time: <?php echo number_format($OnTimeinstallment_sum); ?></p>
						
<p>Total Payments Due: <?php echo $totalTodayinstallment_sum; ?></p>

<p>Total On-Time Repayment Rate:  <?php echo number_format($RepayRate_sum); ?>%</p>

</strong>


</div>