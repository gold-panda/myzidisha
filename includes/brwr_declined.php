<script type="text/javascript">
	$(function() {		
		$(".tablesorter_borrowers").tablesorter({sortList:[[0,0]], widgets: ['zebra'], headers: { 3:{sorter:'digit'}, 4:{sorter:'digit'}}});
	});	
</script>
<div class="span12">
<?php 
	include_once("./editables/brwr_declined.php");
?>
<div align='left' class='static'><h1><?php echo $lang['brwr_declined']['declined_borrower']; ?></h1></div>
<?php 
if($database->getPartnerStatus($session->userid)==0 && $session->userlevel!=ADMIN_LEVEL )
{
	echo $lang['brwr_declined']['contact_us'];
}
else{
	$list= $database->getDeclinedBorrowers(); 
	if(empty($list))
	{
		echo $lang['brwr_declined']['borrower_view'];
	}
	else{ ?>
		<table class="zebra-striped tablesorter_borrowers">
				<thead>
					<tr>
					<!-- // Anupam 9-Jan-2013 commented photo column  bug# 219 -->
						<!-- <th><?php echo "Borrower"; ?></th> -->
						<th>Name</th>
						<th>Location</th>
						<th>Contacts</th>
						<th>Completed On</th>
						<th>Last Modified</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
		<?php	 foreach($list as $rows)
					{
						
						$userid=$rows['userid'];
						$fname=$rows['FirstName'];
						$lname=$rows['LastName'];
						$telmob=$rows['TelMobile'];
						$email=$rows['Email'];
						$address=$rows['PAddress'];
						$city=$rows['City'];
						$country=$database->mysetCountry($rows['Country']);
						$assigned_to=$rows['Assigned_to'];
						$partner_name= $database->getPartnerSelfName($assigned_to);
						$assigned_status=$rows['Assigned_status'];
						$declined_date= date('M d, Y', $rows['Assigned_date']);
						$completed_on = date('M d, Y', $rows['completed_on']);
						$status='Declined by '.$partner_name.' On '.$declined_date;
						$lending_inst_officer_name = $rows['lending_inst_officer'];
						$Posted_By = $rows['postedby'];
						$rec_form_offcr_name = $rows['rec_form_offcr_name'];
						$date=date('M d, Y', $rows['regdate']);
						$modDate = date('M d, Y', $rows['LastModified']);
						$prurl = getUserProfileUrl($userid);
				?>
						<tr>
							
							<td><?php echo $fname;?> &nbsp; <?php echo $lname;?></td>
							<td><span style="display:none;"><?php echo $city; ?></span><?php echo "$city<br/>$address";?>
							<td><?php echo $email?><br/><br/><?php echo $telmob?></td> 
							<td><span style="display:none;"><?php echo $rows['completed_on']; ?></span><?php echo $completed_on;?></td>
							<td><span style="display:none;"><?php echo $rows['LastModified']; ?></span><?php echo $modDate;?></td>
							<td><span style="display:none;"><?php echo $rows['Assigned_date']; ?></span><a href='index.php?p=7&id=<?php echo $userid;?>'><?php echo $status;?></a></td>
						</tr>
		<?php		}
			}
		?>
				</tbody>
		</table>
<?php } ?>
</div>