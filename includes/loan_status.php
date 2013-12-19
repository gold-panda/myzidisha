<script type="text/javascript" src="jquery.tablesorter.js"></script>
<?php
include_once("library/session.php");
include_once("./editables/admin.php");
include_once("./editables/loan_status.php");
$path=	getEditablePath('loan_status.php');
include_once("editables/".$path);
date_default_timezone_set ('EST');
?>
<style type="text/css"> 	
	@import url(library/tooltips/btnew.css);
</style>
<script type="text/javascript">
$(function() {		
		$(".tablesorter_activeBids").tablesorter({sortList:[[3,1]], widgets: ['zebra'], headers: { 0:{sorter: false}}});
		$(".tablesorter_activeLoans").tablesorter({sortList:[[3,1]], widgets: ['zebra'], headers: { 0:{sorter: false},3:{sorter: 'digit'}, 4:{sorter: 'digit'},5:{sorter: 'digit'}}});
		$(".tablesorter_endLoans").tablesorter({sortList:[[1,0]], widgets: ['zebra'], headers: { 0:{sorter: false}}});
	});	
</script>
<script type="text/javascript">
<!--
$(document).ready(function() {
	$('#user_comments').click(function() {
		$('#user_comment_desc').slideToggle("slow");
		$(this).text($(this).text() == "View More" ? "View Less" : "View More");
		$(this).toggleClass("view-less");
	});
	$('#active_bids').click(function() {
		$('#active_bids_desc').slideToggle("slow");
		$(this).text($(this).text() == "View More" ? "View Less" : "View More");
		$(this).toggleClass("view-less");
	});
	$('#active_loans').click(function() {
		$('#active_loans_desc').slideToggle("slow");
		$(this).text($(this).text() == "View More" ? "View Less" : "View More");
		$(this).toggleClass("view-less");
	});
		$('#ended_loans').click(function() {
		$('#ended_loans_desc').slideToggle("slow");
		$(this).text($(this).text() == "View More" ? "View Less" : "View More");
		$(this).toggleClass("view-less");
	});
})
//-->
</script>
<div class='span12'>
<?php
	if($session->userlevel == LENDER_LEVEL)
	{
		$amtUseforbid = round($session->amountToUseForBid($userid), 4);
		$amtincart = $database->getAmtinLendingcart($userid);
		if(isset($_SESSION['Nodonationincart'])) {
				$availAmt = $amtUseforbid - $amtincart['amt'];
			}else {
				$availAmt = $amtUseforbid - $amtincart['amt'] - $amtincart['donation'];
			}
			$creditincart = $amtUseforbid - $availAmt;
			if($availAmt<0) {
				$availAmt = 0;
			}
		$amt = $database->getTransaction($session->userid,0);
		$availAmt = truncate_num(round($availAmt, 4),2);
		$FundUploaded=$database->getFundUploaded($userid);
		$FundUploaded=number_format($FundUploaded, 2, ".", ",");
		$total_invested=$database->totalAmountLend($userid);
		$active_investamtDisplay=$database->amountInActiveBidsDisplay($userid);
		$totoal_invest_amount1=$total_invested+$active_investamtDisplay;
		$totoal_invest_amount=number_format($totoal_invest_amount1, 2, ".", ",");
		$business_financed=$database->businessFinanced($userid);
		$invite_details = $database->getLenderImpact($userid);
	?>
		<div align='left' class='static'><h1><?php echo $lang['loan_status']['myportfolio'] ?></h1></div>
			<div>
			<table class = 'detail'>
				<tr style="height:15px;"><td></td></tr>
				<tr>
					<td width='250px'>Funds Uploaded: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loan_status']['uploaded_tooltip'] ?></span><span class='bottom'></span></span></a></td>
					<td width='130px'><?php echo "USD ".$FundUploaded ?></td>
				</tr>
				<tr style="height:6px"><td></td></tr>
				<tr>
					<td width='250px'><?php echo $lang['loan_status']['total_avl_amt']?>: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loan_status']['availAmt_tooltip'] ?></span><span class='bottom'></span></span></a></td>
					<td width='130px'><?php 
					echo "USD ".number_format($availAmt, 2, ".", ",");
					?>
					</td>
					<td>
						<strong><a href="microfinance/lend.html" style="font-size:16px"><?php echo $lang['loginform']['l_loan'] ?></a></strong>
					</td>
				</tr>
				<tr>
				<?php if($creditincart>0){
					?><tr style="height:6px"><td></td></tr>
					<?php echo "<td width='250px'>Credit In Lending Cart:</td><td>USD ".number_format($creditincart, 2, ".", ",")."</td>";
					} 
				?>
				</tr>
				<tr style="height:6px;"><td></td></tr>
				
				<?php 
					$borrowerLoanstatus = $database->getLender_disbursedLoan($session->userid);
					$amt_outstand=0;
					$amt_outstand_total=0;
					foreach( $borrowerLoanstatus as $rows ) {
						$borrowerid=$rows['userid'];
						$loan_id=$rows['loanid'];
						$activestate=$rows['Loan_State'];
						$amountgiven=$rows['AMT'];
						$percent_paid=$session->getStatusBar($borrowerid,$loan_id,3);
																	if($activestate==0){
							$amt_outstand=$amountgiven;
						}
						else if($activestate==1)
						{
							$amt_outstand=$amountgiven;
						}
						else if($activestate==2)
						{

							$percent_paid=$session->getStatusBar($borrowerid,$loan_id,3);
							$active_state=$amountgiven*($percent_paid/100);														$amt_outstand=$amountgiven-$active_state;
						}

						$amt_outstand_total += $amt_outstand;
					}

//include outstanding bids in total outstanding

					$lenderbidstatus = $database->getLenderBids($session->userid);
					$tot_val_act_bids= 0;
					foreach( $lenderbidstatus as $rows )
					{
						$bidamt=$rows['bidamount'];
						$bidstatus=$rows['bidstatus'];
						if($bidstatus == 1)
						{
							$tot_val_act_bids += $bidamt;
						}
						else if($bidstatus == 2)
						{
							$tot_val_act_bids += $rows['bidamt_acpt'];
						}
					}
					$sum_outstanding = $amt_outstand_total + $tot_val_act_bids;
					?>
				<tr>

					 <td width='250px'>Principal Outstanding: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loan_status']['outstand_tooltip'] ?></span><span class='bottom'></span></span></a></td>
					 <td width='130px'><?php echo  "USD ".number_format($sum_outstanding, 2, '.', ','); ?>
					</td>
				</tr>
				<tr style="height:25px;"><td></td></tr>
				<tr>
<tr>
					<td width='250px'>Loans Made:</td>
					<td width='130px'><?php echo $business_financed?></td></tr>
					<tr><td></td></tr>
				<tr>
					<td>Lending Groups:</td>
				<td>
					
				<?php 
				$lending_groups = $database->getlendingGrouops();
				foreach($lending_groups as $groups) { 
					$gid = $groups['id'];
					$gname = $groups['name'];
					$is_member=$database->IsmemberOfGroup($userid, $gid);
					if ($is_member == 1){
						echo "<a href='index.php?p=82&gid=$gid'>".$gname."</a><br/>";
					}
				}
				?>				
				</td>	
<td>
					<strong><a href="index.php?p=80" style="font-size:16px">Join A Group</a></strong>
					</td>
				</tr>

				<tr style="height:25px;"><td></td></tr>
				<tr>
					<td>Invites Sent:</td>
					<td>
						<?php echo $invite_details['invite_sent'];?>						
					</td>
					<td>
						<strong><a href="index.php?p=30" style="font-size:16px">Send Invite</a></strong>
					</td>
				</tr>
				<tr><td></td></tr>
				<tr>
					<td>Invites Accepted:</td>
					<td><?php echo $invite_details['invite_accptd']?></td>
				</tr>
				<tr><td></td></tr>
				<tr>
					<td>Loans Made By My Invitees:</td>
					<td><?php echo $invite_details['invite_loan_made']?></td>
				</tr>
				<tr style="height:20px"><td></td></tr>
				<tr>
					<td>Gift Cards Gifted:</td>
					<td>
						<?php echo $invite_details['gift_card_purchased'];?>						
					</td>
					<td>
						<strong><a href="microfinance/gift-cards.html" style="font-size:16px">Give a Gift Card</a></strong>
					</td>
				</tr>
				<tr><td></td></tr>
				<tr>
					<td>
						Gift Cards Redeemed by My Recipients:
					</td>
					<td><?php echo $invite_details['gift_card_redeemed'];?></td>
				</tr>
				<tr><td></td></tr>
				<tr>
					<td>
						Loans Made By My Gift Card Recipients:
					</td>
					<td style="height:15px"><?php echo $invite_details['giftrecp_loan_made']?></td>
				</tr>
				<tr style="height:20px"><td></td></tr>
				<tr>
					<td>Amount Lent By Me:</td>
					<td><?php echo "USD ".$totoal_invest_amount;?></td>
				</tr>
				<tr><td></td></tr>
				<tr>
					<td>Amount Lent By My Invitees:</td>
					<td><?php echo "USD ".number_format($invite_details['invite_AmtLent'], 2, '.', ',')?></td>
				</tr>
				<tr><td></td></tr>
				<tr>
					<td>Amount Lent By My Gift Card Recipients:</td>
					<td><?php echo "USD ".number_format($invite_details['Giftrecp_AmtLent'], 2, '.', ',')?></td>
				</tr>
				<tr style="height:20px"><td></td></tr>
				<tr>
					<td><strong style="font-size:16px">Total Impact: <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loan_status']['impact_tooltip'] ?></span><span class='bottom'></span></span></a></strong></td>
					<td style="font-size:16px">
					<strong><?php 
						$total_impact = $invite_details['Giftrecp_AmtLent']+$invite_details['invite_AmtLent']+$totoal_invest_amount1;
							echo "USD ".number_format($total_impact, 2, '.', ',')?></Strong></td>
				</tr>
			</table>
		</div>
		<br/><br/><br/>
		<div class="row">
			<?php
				include_once("./editables/profile.php");
				$path=	getEditablePath('profile.php');
				include_once("editables/".$path);
				include_once("includes/l_comments.php");
			?>
		</div>
		<br/><br/>



		<h3 class="subhead"><?php echo $lang['loan_status']['act_bid']; ?><p id="active_bids" class="view-more-less">View Less</p></h3>
<?php	if(isset($amt) && $amt != 0 && $amt != '')
			echo "";
		else
			echo "<p>".$lang['loan_status']['no_t_y']."</p>";
		$lenderbidstatus = $database->getLenderBids($session->userid);
		if($lenderbidstatus)
		{		
?>	
	<div id='active_bids_desc'>
		<table  class="zebra-striped tablesorter_activeBids">
				<thead>
					<tr>
						<th></th>
						<th width='35%'><?php echo $lang['loan_status']['borrower_name'];?> </th>
						<th width='20%']><?php echo $lang['loan_status']['usd_amt_bid'];?></th>
						<th width='15%'><?php echo $lang['loan_status']['biddate'];?></th>
						<th width='20%'><?php echo $lang['loan_status']['bid_status'];?></th>
					</tr>
				</thead>
				<tbody>
		<?php		$tot_val_act_bids= 0;
					foreach( $lenderbidstatus as $rows )
					{
						$firstname=$rows['FirstName'];
						$lastname=$rows['LastName'];
						$name=$firstname." ".$lastname;
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
						$loanprurl = getLoanprofileUrl($borrowerid,$loan_id);
					?>
						<tr>
							<td style="width:50px"><a href='<?php echo $loanprurl?>'><img class="my_port_img" src="library/getimagenew.php?id=<?php echo $borrowerid ?>&width=60&height=60" alt="<?php echo $name ?>"/></a></td>
							<td><a href='<?php echo $loanprurl?>'><?php echo $name ?></a>&nbsp;&nbsp;<?php echo  $city?>,&nbsp;<?php echo $country?></td>
							<td ><?php echo number_format($bidamt, 2, '.', ',')?></td>
							<td>
								<span style='display:none'><?php echo $biddate?></span>
								<?php echo date('F d, Y',$biddate);?>
							</td>
							<td><?php echo $bidstatus_desc?></td>
						</tr>
			<?php	}	?>
				</tbody>
				<tfoot>
					<tr>
						<td></td>
						<td colspan='2'><strong><?php echo $lang['loan_status']['tot_val_act_bids'];?></strong></td>
						<td colspan='2'><strong><?php echo number_format($tot_val_act_bids, 2, '.', ','); ?></strong></td>
					</tr>
				</tfoot>
			</table>
		</div>
<?php	}	?>
		<br/><br/>
		<h3 class="subhead"><?php echo $lang['loan_status']['act_loan']; ?><p id="active_loans" class="view-more-less">View Less</p></h3>
<?php	if(isset($amt) && $amt != 0 && $amt != '')
			echo "";
		else
			echo "<p>".$lang['loan_status']['no_t_y']."</p>";	
		
		if($borrowerLoanstatus)
		{	?>
		<div id='active_loans_desc'>
			<table class="zebra-striped tablesorter_activeLoans">
				<thead>
					<tr>
						<th></th>
						<th><?php echo $lang['loan_status']['borrower_name'];?> </th>
						<th><?php echo $lang['loan_status']['date_disb'];?></th>

						<th><?php echo $lang['loan_status']['borr_givenamt'];?> </th>
						
						<th><?php echo $lang['loan_status']['per_repaid'];?> <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loan_status']['repaid_tooltip'] ?></span><span class='bottom'></span></span></a></th>
						<th><?php echo $lang['loan_status']['amt_outstanding'];?> <a style='cursor:pointer' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['loan_status']['outstand_tooltip'] ?></span><span class='bottom'></span></span></a></th>
					</tr>
				</thead>
				<tbody>
		<?php		$amtgivenTotal=0;
				
//added by Julia 14-10-2013
				$amtpaidTotal=0;
				$amtoutstandingTotal=0;
				$amt_outstanding=0;
					foreach( $borrowerLoanstatus as $rows )
					{
						$disdata=$rows['TrDate'];
						$firstname=$rows['FirstName'];
						$lastname=$rows['LastName'];
						$name=$firstname." ".$lastname;
						$borrowerid=$rows['userid'];
						$activestate=$rows['Loan_State'];
						$amountgiven=$rows['AMT'];
						$loan_id=$rows['loanid'];
						$city = $rows['city'];
						$country = $rows['country'];
						$country = $database->mysetCountry($rows['country']);
						if($activestate==0){
							$active_state="LOAN OPEN";
							$loan_sorting=0;
							$outstanding_sorting=0;
							$amt_outstanding=$amountgiven;
						}
						else if($activestate==1)
						{
							$active_state="LOAN FUNDED";
							$loan_sorting=-1;
							$outstanding_sorting=-1;
							$amt_outstanding=$amountgiven;
						}
						else if($activestate==2)
						{

//modified by Julia to display amount repaid instead of percent 14-10-2013

							$percent_paid=$session->getStatusBar($borrowerid,$loan_id,3);
							
// Julia 14-10-2013 redefining $active_state as amount repaid instead of percentage

							$active_state=$amountgiven*($percent_paid/100);
							
							$loan_sorting=$active_state;


							$amt_outstanding=$amountgiven-$active_state;

								
							$outstanding_sorting=$amt_outstanding;


							/*$active_state="LOAN ACTIVE";*/
						}
						else if($activestate==3)
						{
							$active_state="LOAN REPAID";
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
						$amtgivenTotal += $amountgiven;
//added by Julia 13-10-2013 to display amount repaid and outstanding
						$amtpaidTotal += $active_state;
						$amtoutstandingTotal += $amt_outstanding;
						$loanprurl = getLoanprofileUrl($borrowerid,$loan_id);
					?>
						<tr>
							<td style="width:50px">
								<a href='<?php echo $loanprurl?>'>
									<img class="my_port_img" src="library/getimagenew.php?id=<?php echo $borrowerid ?>&width=60&height=60" alt="<?php echo $name ?>"/>
								</a>
							</td>
							<td>
								<a href='<?php echo $loanprurl?>'><?php echo $name?></a>&nbsp;&nbsp;<?php echo $city?>,&nbsp;<?php echo $country?>
							<td>
							<span style='display:none'><?php echo $disdata?></span>
							<?php echo date('F d, Y',$disdata);?></td>

							
							<td>
							<span style='display:none'><?php echo $amountgiven?></span>
							<?php echo number_format($amountgiven, 2, '.', ',')?></td>

							<td>
							<span style='display:none'><?php echo $loan_sorting?></span>
							<?php if(is_numeric($active_state)==true) 
									{ 
									echo number_format($active_state, 2, '.', ',');
									}else{
									 echo number_format(0, 2, '.', ',');
									}
							?></td>

<!-- added by Julia 14-10-2013-->
							<td>
							<span style='display:none'><?php echo $outstanding_sorting?></span>
											<?php echo number_format($amt_outstanding, 2, '.', ',')?></td>


						</tr>
			<?php	}	?>
				</tbody>
				<tfoot>
					<tr>
						<th></th>
						<th colspan='2'><?php echo $lang['loan_status']['tot_amt_lnt']; ?></th>
						<th><?php echo number_format($amtgivenTotal, 2, '.', ','); ?></th>

<!--added by Julia 13-10-2013 to display total amount repaid and outstanding-->

<th><?php echo number_format($amtpaidTotal, 2, '.', ','); ?></th>

<th><?php echo number_format($amtoutstandingTotal, 2, '.', ','); ?></th>

					</tr>
				</tfoot>
			</table>
		</div>
<?php	}	?>
		<br/><br/>
		<h3 class="subhead"><?php echo $lang['loan_status']['end_loan']; ?><p id="ended_loans" class="view-more-less">View Less</p></h3>
		<?php	if(isset($amt) && $amt != 0 && $amt != '')
			echo "";
		else
			echo "<p>".$lang['loan_status']['no_t_y']."</p>";	
		$borrowerLoanstatus = $database->getLender_disbursedLoan_end($session->userid);
		if($borrowerLoanstatus)
		{	?>
		<div id='ended_loans_desc'>
			<table class="zebra-striped tablesorter_endLoans">
				<thead>
					<tr>
						<th></th>
						<th><?php echo $lang['loan_status']['borrower_name'];?> </th>
						<th><?php echo $lang['loan_status']['borr_givenamt'];?> </th>
						<th><?php echo $lang['loan_status']['loan_status'];?></th>
					</tr>
				</thead>
				<tbody>
			<?php	$amtgivenTotal=0;
					foreach( $borrowerLoanstatus as $rows )
					{
						$firstname=$rows['FirstName'];
						$lastname=$rows['LastName'];
						$name=$firstname." ".$lastname;
						$borrowerid=$rows['userid'];
						$activestate=$rows['Loan_State'];
						$amountgiven=$rows['AMT'];
						$loan_id=$rows['loanid'];
						$city = $rows['city'];
						$country = $rows['country'];
						$country = $database->mysetCountry($rows['country']);
						$write_off=false;

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
							/*$active_state="LOAN ACTIVE";*/
						}
						else if($activestate==3)
						{
							$active_state="100% Repaid";
						}
						else if($activestate==5)
						{
							$active_state=$session->getStatusBar($borrowerid,$loan_id,4);
							$write_off=true;
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
						$amtgivenTotal += $amountgiven;
						$feedbackGiven = $database->isLenderGivenFeedback($loan_id, $session->userid);
						$loanprurl = getLoanprofileUrl($borrowerid, $loan_id);
						if(!$feedbackGiven && $active_state !="Forgiven" && !$write_off)
							$feedbackUrl="<a href='$loanprurl#e1'>Leave Feedback</a>";
						else
							$feedbackUrl="";
						?>		
						<tr>
							<td style="width:50px"><a href='<?php echo $loanprurl?>'><img class="my_port_img" src="library/getimagenew.php?id=<?php echo $borrowerid ?>&width=60&height=60" alt="<?php echo $name ?>"/></a></td><td><a href='<?php echo $loanprurl?>'><?php echo $name?></a>&nbsp;&nbsp;<?php echo $city?>,&nbsp;<?php echo $country?></td>
							<td><?php echo number_format($amountgiven, 2, '.', ',')?></td>
							<td><?php echo $active_state?><br/><?php echo $feedbackUrl?></td>
						</tr>
							
			<?php	} ?>
				</tbody>
				<tfoot>
					<tr>
						<th></th>
						<th><?php echo $lang['loan_status']['tot_amt_lnt']; ?></th>
						<th colspan='2'><?php echo number_format($amtgivenTotal, 2, '.', ','); ?></th>
					</tr>
				</tfoot>
			</table>
		</div>
<?php	}
	}
	else
	{
		echo "<div>";
		echo $lang['admin']['allow'];
		echo "<br />";
		echo $lang['admin']['Please'];
		echo "<a href='index.php'>click here</a>".$lang['admin']['for_more']. "<br />";
		echo "</div>";
	}	?>
</div>