<?php include_once("../library/session.php");
$gid =$_GET['gid'];
?>
<div id='transffer_leadership'>
<form action="process.php" method='post'>
	<div style="margin-top:10px;padding:15px;">
		<strong>Please select a new Group Leader.</strong><br/>
		<table class='detail'>
		
			<input type="hidden" name="groupid" value="<?php echo $gid?>">
			<input type='hidden' value='1' name='leavegroup'>
		<?php 
			$members = $database->getLendingGroupMembers($gid);
			foreach($members as $member ) { 
			if($member['member_id']!=$session->userid) {
				$username = $member['username'];
				$memberid = $member['member_id'];
				$impact = $database->getMyImpact($memberid);
				$total_invested=$database->totalAmountLend($memberid);
				$prurl = getUserProfileUrl($memberid);
				$active_investamtDisplay = $database->amountInActiveBidsDisplay($memberid);
				$totlat_impact = number_format($active_investamtDisplay + $total_invested + $impact['invite_AmtLent']+$impact['Giftrecp_AmtLent'], 2, '.', ',');
				echo"<tr>";
					echo"<td><input type='radio' name='selectleader' value='$memberid' checked><a href='$prurl' target='_blank'>$username</a></td>";
					echo"<td>$totlat_impact</td>";
				echo"<tr/>";
			}
		}?>
		</table>
		<br/>
		<input type='submit' class='btn'  value='Select Leader'>
	
	</div>
	</form>
</div>