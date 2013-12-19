<?php
include_once("library/session.php");
include_once("./editables/brwrlist-i.php");
include_once("./editables/admin.php");
?>
<script type="text/javascript">
	$(function() {		
		$(".tablesorter_pending_borrowers").tablesorter({sortList:[[3,1]], widgets: ['zebra'], headers: { 3:{sorter: false}}});
	});	
</script>
<div class='span12'>
<div align='left' class='static'><h1>Pending Email Confirmation</h1></div>
	<?php 
		if($session->userlevel==ADMIN_LEVEL ) {
			$borrowers = $database->getPendingEmailBorrower();

		}
	?><br/>
	<table class="zebra-striped tablesorter_pending_borrowers">
		<thead>
			<tr>
				<th>Borrower Name</th>
				<th>Country</th>
				<th>Email</th>
				<th>Confirmation Link</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			foreach($borrowers as $rows) {
				$name = trim($rows['FirstName']." ". $rows['LastName']);
				$country=$database->mysetCountry($rows['Country']);
				$email=$rows['Email'];
				$activate_key = $database->getActivationKey($rows['userid']);
				$borrowerid = $rows['userid'];
				$link = SITE_URL."index.php?p=51&ident=$borrowerid&activate=$activate_key";
		?>
				<tr>
					<td><?php echo $name?></td>
					<td><?php echo $country?></td>
					<td><?php echo $email?></td>
					<td><?php echo "<a href='$link' target='_blank'>$link</a>"?></td>
				</tr>
		<?php 	}
		?>
		</tbody>
	</table>
</div>