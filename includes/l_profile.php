<?php
	$id=$getuid;
	$data=$database->getLenderDetails($id);
	$fname=$data['FirstName'];
	$lname=$data['LastName'];
	$name=trim($fname.' '.$lname);
	$website=$data['website'];
	if($data['sublevel']==LENDER_GROUP_LEVEL)
		$lusername=	$fname;
	else
		$lusername=$data['username'];
	$email=$data['Email'];
	$city=$data['City'];
	$country = $database->mysetCountry($data['Country']);
	$desc=nl2br(trim($data['About']));
	$username=$data['username'];
	$hideamt=$data['hide_Amount'];//=0 means show the total amount lend

	$photo=$data['PhotoPath'];
	if(empty($hideamt))
		$totallendamt=$database->totalAmountLend($id);

	$karma_score = $database->getKarmaScore($id);
	?>

	<h3 class="subhead top"><?php echo $lang['profile']['lender_detail'] ?></h3>
	<div id="user-account">
	<?php if (file_exists(USER_IMAGE_DIR.$id.".jpg")){ ?> 
		<img class ="user-account-img" src="library/getimagenew.php?id=<?php echo $id;?>&width=330&height=380" alt=""/>
	<?php } ?> 
		<table class="detail" style="width:335px">
			<tbody>
				<tr>
					<td style="width:150px"><strong><?php echo $lang['profile']['User_Name'] ?>:</strong></td>
					<td><?php echo $lusername;?></td>
				</tr>
				<?php if(!empty($city)) { ?>
				<tr>
					<td><strong><?php echo $lang['profile']['City'] ?>:</strong></td>
					<td><?php echo $city;?></td>
				</tr>
				<?php }?>
				<?php if(!empty($country)) { ?>
				<tr>
					<td><strong><?php echo $lang['profile']['Country'] ?>:</strong></td>
					<td><?php echo $country;?></td>
				</tr>
				<?php }?>
				<?php if($session->usersublevel==LENDER_GROUP_LEVEL && !empty($website)){?>
					<tr>
					<td><strong><?php echo $lang['profile']['website'] ?>:</strong></td>
					<td><?php echo $website;?></td>
				</tr>
				<?php } ?>
				<tr>
					<td>
						<strong><?php echo $lang['profile']['karma'] ?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['profile']['karma_tooltip'] ?></span><span class='bottom'></span></span></a></strong></td>
					<td>
						<?php echo number_format($karma_score); ?>
					</td>
				</tr>
			</tbody>
		</table>
<?php	if(!empty($desc)){?>
		<h4><?php echo $lang['profile']['i_lend'] ?></h4>
        <p style="text-align:justify"><?php echo $desc; ?></p>
<?php	} ?>
<?php	if(empty($hideamt))
		{	?>
		<div class="bid-table" style="clear:both">
			<h3 class="subhead"><?php echo $lang['profile']['act_bid'] ?></h3>
<?php		$amt = $database->getTransaction($session->userid,0);
			$lenderbidstatus = $database->getLenderBids($getuid);
			if($lenderbidstatus)
			{
?>
			<table class="zebra-striped">
				<thead>
					<tr>
						<th><strong><?php echo $lang['profile']['biddate'] ?></strong></th>
						<th><strong><?php echo $lang['profile']['b_detail'] ?></strong></th>
						<th><strong><?php echo $lang['profile']['amt_bid'] ?> (USD)</strong></th>
						<th><strong><?php echo $lang['profile']['bid_status'] ?></strong></th>
					</tr>
				</thead>
				<tbody>
<?php				$tot_val_act_bids= 0;
					foreach( $lenderbidstatus as $rows )
					{
						$firstname=$rows['FirstName'];
						$lastname=$rows['LastName'];
						$city = $rows['city'];
						$country = $rows['country'];
						$country = $database->mysetCountry($rows['country']);
						$borrowerid=$rows['userid'];
						$loan_id=$rows['loanid'];
						$bidamt=$rows['bidamount'];
						$biddate=$rows['biddate'];
						$bidstatus=$rows['bidstatus'];
						if($bidstatus == 1)
						{
							$bidstatus_desc = "Active";
							$tot_val_act_bids += $bidamt;
						}
						else if($bidstatus == 2)
						{
							$bidstatus_desc = "Bid down to USD ".number_format($rows['bidamt_acpt'], 2, '.', ',')."";
							$tot_val_act_bids += $rows['bidamt_acpt'];
						}
						else if($bidstatus == 3)
							$bidstatus_desc = "Outbid";
						else
							$bidstatus_desc = "";
						$loanprurl = getLoanprofileUrl($borrowerid, $loan_id);

						echo '<tr>';
							echo "<td style='text-align:left;padding-left:10px'>".date('M d, Y',$biddate)."</td>";
							echo "<td style='text-align:left;padding-left:10px'><a href='$loanprurl'>$firstname $lastname</a> &nbsp;&nbsp; $city,&nbsp; $country</td>";
							echo "<td style='text-align:left;padding-left:10px'>".number_format($bidamt, 2, '.', ',')."</td>";
							echo "<td style='text-align:left;padding-left:10px'> $bidstatus_desc </td>";
						echo '</tr>';
					}
?>				</tbody>
				<tfoot>
					<tr>
						<td colspan=2><strong><?php echo $lang['profile']['tot_val_act_bids'] ?></strong></td>
						<td><strong><?php echo number_format($tot_val_act_bids, 2, '.', ','); ?></strong></td>
						<td></td>
					</tr>
				</tfoot>
			</table>
<?php		}	?>
		</div><!-- /bid-table -->
		<div class="bid-table">
			<h3 class="subhead"><?php echo $lang['profile']['act_loan'] ?></h3>
<?php		$borrowerLoanstatus = $database->getLender_disbursedLoan($getuid);
			if($borrowerLoanstatus)
			{
?>
			<table class="zebra-striped">
				<thead>
					<tr>
						<th><strong><?php echo $lang['profile']['b_detail'] ?></strong></th>
						<th><strong><?php echo $lang['profile']['amt_lent'] ?> (USD)</strong></th>
						<th><strong><?php echo $lang['profile']['loan_status'] ?></strong></th>
					</tr>
				</thead>
				<tbody>
<?php
					$amtgivenTotal=0;
					foreach( $borrowerLoanstatus as $rows )
					{
						$firstname=$rows['FirstName'];
						$lastname=$rows['LastName'];
						$borrowerid=$rows['userid'];
						$activestate=$rows['Loan_State'];
						$amountgiven=$rows['AMT'];							
						$loan_id=$rows['loanid'];
						$city = $rows['city'];
						$country = $rows['country'];
						$country = $database->mysetCountry($rows['country']);
						if($activestate==0){
							$active_state="LOAN OPEN";
						}
						else if($activestate==1){
							$active_state="LOAN FUNDED";
						}
						else if($activestate==2){
							$active_state=$session->getStatusBar($borrowerid,$loan_id,2);
							//$active_state="LOAN ACTIVE";
						}
						else if($activestate==3){
							$active_state="LOAN REPAID";
						}
						else if($activestate==5){
							$active_state="Written Off";
						}
						else if($activestate==6){
							$active_state="LOAN CANCELLED";
						}
						else if($activestate==7){
							$active_state="LOAN EXPIRED";
						}
						else if($activestate==8){
							$active_state="LOAN All";
						}
						$loanprurl = getLoanprofileUrl($borrowerid, $loan_id);
						$loanprurl1 = getLoanprofileUrl($session->userid, $loan_id);
						echo '<tr>';				
							echo "<td style='text-align:left;padding-left:10px'><a href='$loanprurl'>$firstname $lastname</a> &nbsp;&nbsp; $city,&nbsp; $country</td>";		       
							echo "<td style='text-align:left;padding-left:10px'>".number_format($amountgiven, 2, '.', ',')."</td>";					
							echo "<td style='text-align:left;padding-left:10px'><a href='$loanprurl1'>$active_state</a></td>";
						echo '</tr>';
						$amtgivenTotal += $amountgiven;
					}
?>				 </tbody>
				 <tfoot>
					<tr>
						<td><strong><?php echo $lang['profile']['tot_amt_lnt'] ?></strong></td>
						<td><strong><?php echo number_format($amtgivenTotal, 2, '.', ','); ?></strong></td>
						<td></td>
					</tr>
				</tfoot>            
			</table>
<?php		}	?>
		</div><!-- /bid-table -->
        <div class="bid-table">
			<h3 class="subhead"><?php echo $lang['profile']['end_loan'] ?></h3>
<?php		$borrowerLoanstatus = $database->getLender_disbursedLoan_end($getuid);
			if($borrowerLoanstatus)
			{
?>
          	<table class="zebra-striped">
				<thead>
					<tr>
						<th><strong><?php echo $lang['profile']['b_detail'] ?></strong></th>
						<th><strong><?php echo $lang['profile']['amt_lent'] ?> (USD)</strong></th>
						<th><strong><?php echo $lang['profile']['loan_status'] ?></strong></th>
					</tr>
				</thead>
				<tbody>
<?php
					$amtgivenTotal=0;
					foreach( $borrowerLoanstatus as $rows )
					{
						$firstname=$rows['FirstName'];
						$lastname=$rows['LastName'];
						$borrowerid=$rows['userid'];
						$activestate=$rows['Loan_State'];
						$amountgiven=$rows['AMT'];							
						$loan_id=$rows['loanid'];
						$city = $rows['city'];
						$country = $rows['country'];
						$country = $database->mysetCountry($rows['country']);
						if($activestate==0){
						$active_state="LOAN OPEN";
						}
						else if($activestate==1)
						{
						 $active_state="LOAN FUNDED";
						}
						else if($activestate==2)
						{
						 $active_state=$session->getStatusBar($borrowerid,$loan_id,2);
						 //$active_state="LOAN ACTIVE";
						}
						else if($activestate==3)
						{
						 $active_state="100% Repaid";
						}
						else if($activestate==5)
						{
						 $active_state="Written Off";
						}
						else if($activestate==6)
						{
						 $active_state="LOAN CANCELLED";
						}
						else if($activestate==7)
						{
						 $active_state="LOAN EXPIRED";
						}
						else if($activestate==8)
						{
						 $active_state="LOAN All";
						}
						if($database->isLenderForgivenThisLoan($loan_id,$session->userid))
						{
							$active_state="Forgiven";
						}
						$loanprurl = getLoanprofileUrl($borrowerid, $loan_id);
						$loanprurl1 = getLoanprofileUrl($session->userid, $loan_id);
						echo '<tr>';				
							echo "<td style='text-align:left;padding-left:10px'><a href='$loanprurl'>$firstname $lastname</a> &nbsp;&nbsp; $city,&nbsp; $country</td>";		       
							echo "<td style='text-align:left;padding-left:10px'>".number_format($amountgiven, 2, '.', ',')."</td>";					
							echo "<td style='text-align:left;padding-left:10px'><a href='$loanprurl1'>$active_state</a></td>";
						echo '</tr>';
						$amtgivenTotal += $amountgiven;
					}
?>				</tbody>
				<tfoot>
					<tr>
						<td><strong><?php echo $lang['profile']['tot_amt_lnt'] ?></strong></td>
						<td><strong><?php echo number_format($amtgivenTotal, 2, '.', ','); ?></strong></td>
						<td></td>
					</tr>
				</tfoot>
			</table>
<?php		}	?>
		</div><!-- /bid-table -->
<?php	}	?>
    </div>