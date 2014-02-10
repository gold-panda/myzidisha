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
<div align='left' class='static'><h1>Lender Invite Report</h1></div><br/>
<?php 
if($session->userlevel==ADMIN_LEVEL ) {

		$profile = $database->inviteReportLenders();
		$showingRes =count($profile);

		?>
		<br/>
		<p><a href="index.php?p=118">Email Invitors</a></p>

		<br/><br/>

		<p>Viewing <?php echo $showingRes?> Results.</p>


		<table class="zebra-striped tablesorter_pending_borrowers">
			<thead>
				<tr>
					<th>Name</th>
					<th>Location</th>
					<th>Email</th>
					<th>Last Login</th>
					<th>Total Invites Sent</th>
					<th>Total Members Recruited</th>
					
				</tr>
			</thead>
			<tbody>

			<?php 
				
				foreach($profile as $rows) {
					
					$borrowerid = $rows['userid'];
					$zidisha_name= $rows['FirstName']." ".$rows['LastName'];
					$prurl= getUserProfileUrl($borrowerid);
					$location=$rows['City'].", ".$database->mysetCountry($rows['Country']);
					$email = $rows['Email'];
					$last_login = $rows['last_login'];
					$invite_details=$database->getInvitedMember($borrowerid);
					$total_invites=count($invite_details);
					$sum_invites += $total_invites;
					$recruited_members = $database->getInvitedMemberJoins($borrowerid);
					$total_recruited = count($recruited_members);
					$sum_recruited += $total_recruited;
			?>
					<tr>


						<td><a href="<?php echo $prurl;?>"><?php echo $zidisha_name; ?></a></td>
							
						<td><?php echo $location; ?></td>

						<td><?php echo $email; ?></td>

						<td><span style='display:none'><?php echo $last_login; ?></span><?php echo date('M d, Y', $last_login); ?></td>

						<td><?php echo $total_invites; ?></td>

						<td><?php echo $total_recruited; ?></td>


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
					<th><?php echo $sum_invites; ?></th>
					<th><?php echo $sum_recruited; ?></th>
					<th><?php echo number_format(($sum_recruited/$sum_invites)*100); ?>%</th>
				</tr>
			</tfoot>
</table>
</div>