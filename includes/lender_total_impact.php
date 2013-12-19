<?php
include_once("library/session.php");
include_once("./editables/admin.php");
?>

<div class='span12'>
<?php
	if($session->userlevel == ADMIN_LEVEL)
	{
		//$active_investamtFunded = $database->amountInActiveBidsFunded($userid);
		$business_financed=$database->businessFinanced();
		$invite_details = $database->getLenderTotalImpact($userid);
	?>
		<div align='left' class='static'><h1>Lender Impact Totals</h1></div>
			<div>
			<table class = 'detail'>
				<tr>
					<td width='250px'>Loans Made:</td>
					<td width='130px'><?php echo $business_financed?></td>
				</tr>
				<tr style="height:20px;"><td></td></tr>
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
					<td>Loans Made By Invitees:</td>
					<td><?php echo $invite_details['invite_loan_made']?></td>
				</tr>
				<tr style="height:15px"><td></td></tr>
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
						Gift Cards Redeemed Recipients:
					</td>
					<td><?php echo $invite_details['gift_card_redeemed'];?></td>
				</tr>
				<tr><td></td></tr>
				<tr>
					<td>
						Loans Made By Gift Card Recipients:
					</td>
					<td style="height:15px"><?php echo $invite_details['giftrecp_loan_made']?></td>
				</tr>
				<tr style="height:15px"><td></td></tr>
				
				<tr>
					<td>Amount Lent By Invitees:</td>
					<td><?php echo "USD ".number_format($invite_details['invite_AmtLent'], 2, '.', ',')?></td>
				</tr>
				<tr><td></td></tr>
				<tr>
					<td>Amount Lent By Gift Card Recipients:</td>
					<td><?php echo "USD ".number_format($invite_details['Giftrecp_AmtLent'], 2, '.', ',')?></td>
				</tr>
				<tr style="height:15px"><td></td></tr>
				<tr>
					<td><strong style="font-size:16px">Total Impact:</strong></td>
					<td style="font-size:16px">
					<strong><?php 
						$total_impact = $invite_details['Giftrecp_AmtLent']+$invite_details['invite_AmtLent'];
							echo "USD ".number_format($total_impact, 2, '.', ',')?></Strong></td>
				</tr>
			</table>
		</div>
		<?php	
		
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