<?php include_once("../library/session.php");
$gid =$_GET['gid'];
?>
<div id='transffer_leadership'>
<form action="process.php" method='post'>
	<div style="margin-top:10px;padding:15px;">
		<strong>Please select a new Group Leader.</strong><br/>
		<table class='detail'>
		
			<input type="hidden" name="groupid" value="<?php echo $gid?>">
			<input type='hidden' value='1' name='transffer_leadership'>
		<?php 
			$members = $database->getLendingGroupMembers($gid);
			$count=0;
			foreach($members as $member ) { 
			if($member['member_id']!=$session->userid) {
				$defaultchecked='';
				if($count==0)
					$defaultchecked = 'checked';
				$username = $member['username'];
				$memberid = $member['member_id'];
				$impact = $database->getMyImpact($memberid);
				$total_invested=$database->totalAmountLend($memberid);
				$active_investamtDisplay = $database->amountInActiveBidsDisplay($memberid);
				$totlat_impact = number_format($active_investamtDisplay + $total_invested + $impact['invite_AmtLent']+$impact['Giftrecp_AmtLent'], 2, '.', ',');
				$prurl = getUserProfileUrl($memberid);
				echo"<tr>";
					echo"<td><input type='radio' name='selectleader' value='$memberid' $defaultchecked><a href='$prurl' target='_blank'>$username</a></td>";
					echo"<td>$totlat_impact</td>";
				echo"<tr/>";
			}
		$count++;}?>
		</table>
		<br/>
		<input type='submit' class='btn'  value='Select Leader'>
	
	</div>
	</form>
</div>