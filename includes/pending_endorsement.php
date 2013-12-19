<script type="text/javascript">
	$(function() {		
		$(".tablesorter_pending_borrowers").tablesorter({sortList:[[3,1]], widgets: ['zebra'], headers: { 3:{sorter: false}}});
	});	
</script>
<div class='span12'>
<div align='left' class='static'><h1>Pending Endorsement</h1></div>
	<?php 
		if($session->userlevel==ADMIN_LEVEL ) {
			$borrowers = $database->getPendingEndorsementBorrower();

		}
	?><br/>
	<table class="zebra-striped tablesorter_pending_borrowers">
		<thead>
			<tr>
				<th>Borrower Name</th>
				<th>Country</th>
				<th>Last Modified</th>
				<th>Endorsements Received</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			foreach($borrowers as $rows) {
				$name = trim($rows['FirstName']." ". $rows['LastName']);
				$country=$database->mysetCountry($rows['Country']);
				$borrowerid = $rows['userid'];
				$last_modified= date('d M Y', $rows['LastModified']);
				$endorsed_comp= $database->IsEndorsedComplete($borrowerid);
				$minendorser= $database->getAdminSetting('MinEndorser');
				if($endorsed_comp<$minendorser && $rows['Created']>'1370995200'){
					$endorsers= $database->getEndorserRecived($borrowerid); ?>
					<tr>
						<td><a href="index.php?p=7&id=<?php echo $borrowerid;?>"><?php echo $name?></a></td>
						<td><?php echo $country?></td>
						<td><?php echo $last_modified; ?></td>
						<td>
			<?php		
						foreach($endorsers as $endorser){
							$prurl=getUserProfileUrl($endorser['endorserid']);
							$e_name= $endorser['ename'];
			?><?php echo "<a href='$prurl' target='_blank'>$e_name</a><br/>"?>
			<?php 	} ?>
				</td></tr>
	<?php	}
		}
		?>
		</tbody>
	</table>
</div>