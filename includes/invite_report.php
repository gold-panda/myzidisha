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
<div align='left' class='static'><h1>Invite Report</h1></div><br/>
<?php 
if($session->userlevel==ADMIN_LEVEL ) {

		$profile = $database->inviteReport();
		$showingRes =count($profile);
		$completed_on = date('M d, Y', $rows['completed_on']);

		?>
		<br/>
		<p><a href="index.php?p=113">Email Invitors</a></p>

		<br/><br/>

		<p>Viewing <?php echo $showingRes?> Results.</p>


		<table class="zebra-striped tablesorter_pending_borrowers">
			<thead>
				<tr>
					<th>Name</th>
					<th>Country</th>
					<th>Date Joined</th>
					<th>On-Time Repayment Rate</th>
					<th>Total Members Recruited</th>
					<th>Total Members Recruited With Loans</th>
					<th>Successful Members Recruited</th>
					<th>Recruited Member Success Rate</th>
					
				</tr>
			</thead>
			<tbody>

			<?php 
				
				foreach($profile as $rows) {
					
					$borrowerid = $rows['userid'];
					$zidisha_name= $database->getNameById($borrowerid);
					$prurl= getUserProfileUrl($borrowerid);
					$link='index.php?p=7&id='.$borrowerid;
					$completed_on_sort = $rows['completed_on'];
					$completed_on = date('M d, Y', $rows['completed_on']);
					$country=$database->mysetCountry($rows['Country']);
					$RepayRate=$session->RepaymentRate($borrowerid);
					$invite_details=$database->getInvitedMember($borrowerid);
					$total_invites=count($invite_details);
					$invitee_loans=$database->getInviteesWithLoans($borrowerid);
					$successful_invites=$database->getSuccessfulInvitees($borrowerid);
					$sum_invites += $total_invites;
					$total_invitee_loans += $invitee_loans;
					$total_successful += $successful_invites;
					
			?>
					<tr>


						<td><a href="<?php echo $prurl;?>"><?php echo $zidisha_name; ?></a></td>
							
						<td><?php echo $country; ?></td>

						<td><span style='display:none'>$completed_on_sort</span><a href="<?php echo $link;?>"><?php echo $completed_on; ?></a></td>

						<td><?php echo number_format($RepayRate); ?>%</td>

						<td><?php echo $total_invites; ?></td>

						<td><?php echo $invitee_loans; ?></td>

						<td><?php echo $successful_invites; ?></td>

						<td><?php echo number_format(($successful_invites/$invitee_loans)*100); ?>%</td>



					</tr>
		<?php	}
	
}
	?>
	</tbody>
			<tfoot>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th><?php echo $sum_invites; ?></th>
					<th><?php echo $total_invitee_loans; ?></th>
					<th><?php echo $total_successful; ?></th>
					<th><?php echo number_format(($total_successful/$total_invitee_loans)*100); ?>%</th>
				</tr>
			</tfoot>
</table>
</div>