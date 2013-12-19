<?php
include_once("library/session.php");
include_once("./editables/admin.php");
?>
<script type="text/javascript" src="includes/scripts/generic.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<div class='span12'>
<?php if($session->userlevel== ADMIN_LEVEL) { ?>
<script type="text/javascript">
$(function() {		
		$(".tablesorter_lenders").tablesorter({sortList:[[0,0]], widgets: ['zebra'], headers: { 3:{sorter: 'digit'}, 4:{sorter: 'digit'}, 6:{sorter: 'digit'}, 7:{sorter: 'digit'}, 8:{sorter: 'digit'}, 9:{sorter: 'digit'}}});
 });	
</script>
<?php
if(isset($_GET['v'])) {
	if($_GET['v']==1) {
		echo "<p style='color:green;font-weight:bold;text-align:center'>".$lang['admin']['lender_activated']."</p>";
	} else if($_GET['v']==2) {
		echo "<p style='color:green;font-weight:bold;text-align:center'>".$lang['admin']['lender_deactivated']."</p>";
	} else {
		echo "<p style='color:red;font-weight:bold;text-align:center'>".$lang['admin']['error_msg']."</p>";
	}
}
$set=$session->getTotalLenderAmount();
$autolendingLender = $database->getAutoLendingLender();
?>
<div align="left" class="static"><h1>Lender Credit Balances</h1></div>

<!-- Julia moved to "Find Lender" page index.php?p=11&a=3&type=1&ord=ASC 24-10-2013 

Total Lender Credit Available (USD): <?php echo $set['total']; ?><br/>
Number of Lenders Using Automated Lending: <?php echo $autolendingLender; ?>

-->

<br/><br/>
<?php if(isset($_SESSION['donated'])) {	?>
		<div class="clearfix" style="color:green">
			<?php echo 'Lender\'s  account deactivated and amount donated to zidisha successfully!'; 
			unset($_SESSION['donated']); ?>
		</div><br/><br/>
<?php } 
if(!empty($set['lenders'])) { ?>
	<table class="zebra-striped tablesorter_lenders">
		<thead>
			<tr>
				<th><?php echo $lang['admin']['Name'];?></th>
				<th><?php echo $lang['admin']['Location'];?></th>
				<th><?php echo $lang['admin']['Contacts'];?></th>
				<th>Available balance</th>
				<th>Date Joined</th>
				<th>Last login</th>
				<th>Fund Uploaded</th>
				<th>Donations</th>
				<th>Transaction Fee</th>
				<th>Total Impact</th>
				<th><?php echo $lang['admin']['activate_button']; ?></th>
			</tr>
		</thead>
		<tbody>
	<?php
			foreach($set['lenders'] as $row) {
				$userid=$row['userid'];
				$firstname=$row['FirstName'];
				$lastname=$row['LastName'];
				$city=$row['City'];
				$country=$row['Country'];
				$email=$row['Email'];
				$joinDatetosort=$row['regdate'];
				$joinDate=date('F d, Y', $joinDatetosort);
				$active=$row['Active'];
				$active1='';
				$fundUploadTo=$row['fund'] + $row['donation'] + $row['paypalfee'];
				$FundUploaded=number_format($fundUploadTo, 2, ".", ",");
				$fundUploadToSort = round($fundUploadTo,0);
				$FundDonationToSort=$row['donation'] * -1;
				$FundDonation=number_format($FundDonationToSort, 2, ".", ",");
				$TransactionFeeToSort=$row['paypalfee'] * 1;
				$TransactionFee=number_format($TransactionFeeToSort, 2, ".", ",");
				$availableAmt = $row['totalamt'];
				$availAmt = number_format($availableAmt, 2, ".", ",");
				$lastLogin=$row['last_login'];
				if(!empty($lastLogin)) {
					$lastLogin=date("M d, Y H:i", $lastLogin);
				}
				$deactivatedmsg='';
				if($active==0){
					if($row['donateDate']) {	
						$date=date('M d Y ',$row['donateDate']);
						$deactivatedmsg='Converted to donation on '.$date;		
					}
				}
				$impact = $database->getMyImpact($userid);
				$total_invested=$database->totalAmountLend($userid);
				$amtinactivebids = $database->amountInActiveBidsDisplay($userid);
				$totlat_impact = number_format($total_invested + $amtinactivebids + $impact['invite_AmtLent']+$impact['Giftrecp_AmtLent'], 2, '.', ',');
				$totlat_impactToSort = round(($total_invested + $impact['invite_AmtLent']+$impact['Giftrecp_AmtLent']),0);
				$prurl = getUserProfileUrl($userid);
				if($availAmt>0 && empty($deactivatedmsg)) {
					$active1="<form name='activeform".$userid."' method='post' action='process.php'>".
					"<input name='deactivateAccount' value='1' type='hidden' />".
					"<input type='hidden' name='user_guess' value='".generateToken('deactivateAccount')."'/>".
					"<input name='lenderid' value='$userid' type='hidden' />".
					"<input name='availAmt' value='$availableAmt' type='hidden' />".
					"<a href='javascript:void(0)' style='color:red' onclick='document.forms.activeform".$userid.".submit()'>Deactivate account and convert to donation</a>".
					"</form>";
				} else {
					$active1= $deactivatedmsg;
				}/* 4 for calling javascript function for delettion a  lender */
				echo '<tr>';
					echo "<td><a href='$prurl'>$firstname $lastname</a></td>";
					echo "<td>$city<br/>$country</td>";
					echo "<td>$email</td>";
					echo"<td><span style='display:none'>$availableAmt</span><a href='index.php?p=16&u=$userid' target='_blank'>
					$availAmt</a></td>";
					echo "<td><span style='display:none'>$joinDatetosort</span>
					$joinDate</td>";
					echo"<td>$lastLogin</td>";
					echo"<td><span style='display:none'>$fundUploadToSort</span>
					$FundUploaded</td>";
					echo"<td><span style='display:none'>$FundDonationToSort</span>
					$FundDonation</td>";
					echo"<td><span style='display:none'>$TransactionFeeToSort</span>
					$TransactionFee</td>";
					echo"<td><span style='display:none'>$totlat_impactToSort</span>
					$totlat_impact</td>";
					echo "<td>$active1</td>";

				echo '</tr>';
			}
?>		</tbody>
	</table>	
<?php
	}
} else {
	echo "<div>";
	echo $lang['admin']['allow'];
	echo "<br />";
	echo $lang['admin']['Please'];
	echo "<a href='index.php'>click here</a>".$lang['admin']['for_more']. "<br />";
	echo "</div>";
}?>
</div>