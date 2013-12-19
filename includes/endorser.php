<script type="text/javascript">
	$(function() {		
		$(".tablesorter_pending_borrowers").tablesorter({sortList:[[2,1]], widgets: ['zebra']});
	});	
</script>
<div class='span12'>
<div align='left' class='static'><h1>Endorsers</h1></div>
	<?php
	if($session->userlevel==ADMIN_LEVEL ) {
		$endorsers= $database->getEndorser();
		/* unnecessary code added by julia & comment by mohit 21-10-13 
		$activationdate=$database->getendorserActivatedDate($endorserid);
			if(!empty($activationdate)){
				$activate_date=date('F d, Y',$activationdate);
			}else{
				$activate_date='';
			} */

	}
	?><br/>
<!--added by Julia 20-10-2013-->
	<?php 
	$showingRes =count($endorsers);
	?>
	<p>Viewing <?php echo $showingRes?> Results.</p>
	<table class="zebra-striped tablesorter_pending_borrowers">
		<thead>
			<tr>
				<th>Name</th>
				<th>Country</th>
				<th>Endorsement Submitted</th>
				<th>Last Modified</th>
				<th>Activation Date</th>
			</tr>
		</thead>
		<tbody>
		<?php 
				$activationdate=0;  // variable defined by mohit 21-10-13
				$activate_date=0;  // variable defined by mohit 21-10-13
			foreach($endorsers as $rows) {
				$name = trim($rows['FirstName']." ". $rows['LastName']);
				$country=$database->mysetCountry($rows['Country']);
				$last_modified=$rows['LastModified'];
				$created=$rows['Created'];
				$endorserid = $rows['userid'];
				$prurl=getUserProfileUrl($endorserid);
//added by Julia 19-10-2013
				$activationdate=$database->getendorserActivatedDate($endorserid);
				if(!empty($activationdate)){
					$activate_date=date('F d, Y',$activationdate);
				}else{
					$activate_date='';
				}
				?>
				<tr>
					<td><?php echo "<a href='$prurl' target='_blank'>$name</a>"?></td>
					<td><?php echo $country?></td>
					<td>
					<span style='display:none'><?php echo $created ?></span><?php echo date('F d, Y',$created)?>
					</td>
					<td>
					<span style='display:none'><?php echo $last_modified ?></span>
					<?php echo date('F d, Y',$last_modified) ?></td>
<!-- added by Julia 19-10-2013 -->
					
					<td>

					<?php echo "<span style='display:none'>$activationdate</span>$activate_date</td>";	// link removed by mohit 21-10-13
					?>
			
				</tr>
				

				
		<?php 	}
		?>

		</tbody>
	</table>
</div>