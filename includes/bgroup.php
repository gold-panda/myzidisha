<script type="text/javascript">
$(function() {		
		$(".tablesorter_Lending_groups").tablesorter({sortList:[[1,1]], widgets: ['zebra'], headers: { 1:{sorter: "digit"},2:{sorter: false},3:{sorter: false}}});
	});	

</script>
<div class='span12'>
	<div align='left' id='static'><h1>Borrowing Groups</h1></div>
	<br/>
<p>
Borrowing Groups let prospective lenders judge the credit risk of the group members based on the on-time repayment rate of the whole group.</p>

<p>Here's how it works:</p>

<p>1. Zidisha members who achieve a 95% or higher on-time repayment rate may start a Borrowing Group.</p>

<p>2. Borrowing Group leaders may invite up to ten fellow Zidisha members to join their groups. Be very careful to invite only people you know well and trust: these members' repayment performance will determine your Borrowing Group's reputation, and once you have invited a member, you may not remove that member from your group.</p>

<p>3. The Borrowing Group membership and the group's combined on-time repayment rate will be displayed to lenders in each member's loan profile page.  Lenders can be more confident in lending to members of groups with high on-time repayment rates.
</p>

<br/>

<div id="" style="width:531px;margin-top:20px;">
	<div style="float:right">
		<a href="index.php?p=105" class='btn'>Start a New Borrowing Group</a>
	</div>
	<?php $lending_groups = $database->getlendingGrouops(); 
	if(!empty($lending_groups)) {
?>

<table class="zebra-striped tablesorter_Lending_groups">
	<thead>
		<th class="{sorter: 'text'}">Group Name</th>
		<th>Total Impact</th>
		<th>About This Group</th>
		<th></th>
	</thead>
	<tbody>
	<?php 
		$disablejoin='';
		foreach($lending_groups as $groups) { 
			$gid = $groups['id'];
			$gname = $groups['name'];
			$gwebsite = $groups['website'];
			$gImage = urlencode($groups['image']);
			$about_grp = stripslashes($groups['about_grp']);
			$about_group = substr($about_grp, 0, 100);
			if(strlen($about_grp) > 100) {
				$about_group = $about_group."..."; 
			}
			$members = $database->getLendingGroupMembers($gid);
			$investedamt = 0;
			$mids=array();
			$active_investamtDisplay = 0;
			foreach($members as $member) {
				$mids[] = $member['member_id'];
				$investedamt += $database->totalAmountLend($member['member_id']);
				$active_investamtDisplay += $database->amountInActiveBidsDisplay($member['member_id']);
				
			}
				$grpTotalImpact = 0;
				$grpTotalImpacttosort = 0;
				$ids='';
			if(!empty($mids)) {
				$ids = implode(',', $mids);
				$gImpact = $database->getGroupImpact($ids);
				$grpTotalImpacttosort = $active_investamtDisplay + $investedamt + $gImpact['invite_AmtLent']+$gImpact['Giftrecp_AmtLent'];
				$grpTotalImpact = number_format($active_investamtDisplay + $investedamt + $gImpact['invite_AmtLent']+$gImpact['Giftrecp_AmtLent'], 2, '.', ',');
			}
		?>
		<tr>
			<td style="vertical-align:middle;width:200px">
			<span style="display:none"><?php echo $gname?></span>
			<?php if(!empty($gImage)) {?>
			<a style="text-decoration:none;" href="<?php echo 'index.php?p=82&gid='.$gid?>">
				<img src = <?php echo SITE_URL."images/client/".$gImage?> height='50px'>
				&nbsp;&nbsp;
				<?php echo "<a href='index.php?p=82&gid=$gid'>".$gname."</a>" ?>
			</a>
			<?php } else {
					echo "<span style='margin-left:65px;'><a href='index.php?p=82&gid=$gid'>".$gname."</a></span>"."</td>";
			} ?>
			<td style='width:100px'><span style="display:none"><?php echo $grpTotalImpacttosort?></span>
			USD <?php echo $grpTotalImpact;?></td>
			<td><?php echo nl2br($about_group); ?>
				 &nbsp;&nbsp;&nbsp;
				
			</td>
			<td>
				<strong><a href="<?php echo 'index.php?p=82&gid='.$gid?>">View Profile</a><br/><br/></strong>
			</td>
			<!-- <td>
				<form method="post" action="process.php">
				<input type="hidden" name="groupid" value="<?php echo $gid?>">
				<?php $ismemberofgroup = $database->IsmemberOfGroup($session->userid, $gid);
					if(!$ismemberofgroup) {	
				?>
					<input type="hidden" name="joinLendingGroup">
					<input type="submit" class='btn' value='Join This Group' <?php echo $disablejoin ?>>
			
				<?php } else {?>
					<input type='hidden' value='1' name='leavegroup'>
					<input type="submit" class='btn' value='Leave This Group'>
			
				<?php } ?>
				</form>
			</td> -->
		</tr>
							
	<?php	}
	}?>
	</tbody>
</table>
</div>