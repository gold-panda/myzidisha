<?php
include_once("library/session.php");
include_once("./editables/admin.php");
include_once("./editables/referral.php");
$path=	getEditablePath('referral.php');
include_once("./editables/".$path);
$countrysel='';
if(isset($_GET["c"]))
{
	$countrysel=$_GET["c"];
}
?>
<script type="text/javascript">
	$(document).ready(function() {
	$('#b_referral').click(function() {
		$('#b_referral_desc').slideToggle("slow");
		$(this).text($(this).text() == "View More" ? "View Less" : "View More");
		$(this).toggleClass("view-less"); 
	});
	$('#pending_comm').click(function() {
		$('#pending_comm_desc').slideToggle("slow");
		$(this).text($(this).text() == "View More" ? "View Less" : "View More");
		$(this).toggleClass("view-less"); 
	});
	$('#paid_comm').click(function() {
		$('#paid_comm_desc').slideToggle("slow");
		$(this).text($(this).text() == "View More" ? "View Less" : "View More");
		$(this).toggleClass("view-less"); 
	});
});
</script>
<div class='span12'>
<?php
if($session->userlevel==ADMIN_LEVEL)
{
?>
	<div align='left' class='static'><h1>Borrower Referrals</h1></div>	
	<form method="post" action="process.php">
		<table class="detail" style="width:auto">
			<tbody>
				<tr>
					<td style="width:200px"><strong>Select Country:</strong></td>
					<td>
						<select name="country" id="country" onChange="window.location='index.php?p=49&c='+(this).value" style="width:auto">
				<?php		$countries=$database->getBorrowerCountries();
							$tempcountry=$form->value("country");
							if(!empty($tempcountry))
								$countrysel=$tempcountry;
							if(!empty($countries))
							{	?>
								<option value="">Select Country</option>
							<?php
								$countryName='';
								$countryCurrency='';
								foreach($countries as $row)
								{	
									if($countrysel==$row['Country'])
									{
										$countryName=$row['name'];
										$countryCurrency=$row['Currency'];
									}
									?>
									<option value="<?php echo $row['Country']  ; ?>"<?php if($countrysel==$row['Country'])echo "Selected='true'"; ?>><?php echo $row['name'];?></option>
					<?php 		}
							}	?>
						</select>						
					</td>
				</tr>
		<?php if(!empty($countrysel)){ ?>
				<tr>
					<td><strong>Referral commission:</strong></td>
					<td>
						<input style="width:100px" type="text" maxlength="10" name="refCommission" value="<?php echo $form->value("refCommission"); ?>" /><br/>
						<?php echo $form->error("refCommission"); ?>
					</td>					
				</tr>
				<tr>
					<td><strong>Percentage repayment required to earn commission:</strong></td>
					<td>
						<input style="width:100px" type="text" maxlength="10" name="refPercent" value="<?php echo $form->value("refPercent"); ?>" /><br/>
						<?php echo $form->error("refPercent"); ?>
					</td>
					<td>
						<input type="hidden" name="referral" />
						<input type="hidden" name="user_guess" value="<?php echo generateToken('referral'); ?>"/>
						<input class="btn" type="submit" value=<?php echo $lang['admin']['save'];?> />
					</td>
				</tr>
		<?php } ?>
			</tbody>
		</table>
	</form>
	<?php
	$set=$database->getReferrals($countrysel);
	if(!empty($set))
	{	?>
		<h3 class="subhead">Borrower Referral for <?php echo $countryName; ?><p id="b_referral" class="view-more-less">View Less</p></h3>
		<div id="b_referral_desc">
			<table class="zebra-striped">
				<thead>
					<tr>
						<th><strong>S. No.</strong></th>
						<th><strong>Referral Commission (<?php echo $countryCurrency; ?>)</strong></th>
						<th><strong>Percentage Repayment</strong></th>
						<th><strong>From</strong></th>
						<th><strong>To</strong></th>
						<th><strong>Action</strong></th>
					</tr>
				</thead>
				<tbody>
			<?php
					$i = 1;
					foreach($set as $row)
					{
						$ref_commission=$row['ref_commission'];
						$percent_repay=$row['percent_repay'];
						$from=date('d M Y', $row['start']);
						$country=$row['country'];
						$to=$row['stop'];
						$stop='';
						if(empty($to)) {
							$to='-';
								$stop="<input class='btn' type='submit' name='stop' value='Stop'>
								<input type='hidden' name='StopCommision'>
								<input type='hidden' name='country' value=$country>";
							
						}
						else {
							$to=date('d M Y', $to);
							$stop='';
						}
						echo "<form action='process.php' method='post'>";
						echo "<tr align='center'>";
						echo "<td>$i</td><td>$ref_commission</td><td>$percent_repay</td><td>$from</td><td>$to</td><td>$stop</td>";
						echo "</tr>";
						echo"</form>";
						$i++;
					}	?>
				</tbody>
			</table>
		</div>
<?php
	}	?>
	<br/>
			<div class="subhead2">
			  <div style="float:left;margin-top:5px" ><h3 class="new_subhead">Pending Commissions</h3></div>
			  <div style="float:right" ><h3><p id="pending_comm" class="view-more-less">View Less</p></h3></div>
			  <?php if($session->userlevel==ADMIN_LEVEL ){?><div class="user_instructions_less">
				<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p'];?>" rel='facebox'>Instructions</a>
			  </div><? } ?>
			  <div style="clear:both"></div>
	</div>
	<div id="pending_comm_desc">
		<table class="zebra-striped">
			<thead>
				<tr>
					<th><strong>Applicant</strong></th>
					<th><strong>Referring Borrower</strong></th>
					<th><strong>Commission Amount</strong></th>
					<th><strong>Percentage Required</strong></th>
					<th><strong>Percentage repaid to date</strong></th>
					<th><strong>Status</strong></th>
				</tr>
			</thead>
			<tbody>
		<?php
				$set=$database->getPendingCommissions();					
				foreach($set as $row)
				{
					$applicant_id =$row['applicant_id'];
					$referrer_id =$row['referrer_id'];
					$ref_commission =$row['ref_commission'];
					$percent_repay =$row['percent_repay'];
					$failed_reason =$row['failed_reason'];
					$applicant_name = $database->getNameById($applicant_id);
					$referrer_name = $database->getNameById($referrer_id);
					$currency = $database->getUserCurrency($referrer_id);
					$percent_repay_till='';
					$loanid= $database->getCurrentLoanid($applicant_id);
					if($loanid)
					{
						$percent_repay_till= $session->getStatusBar($applicant_id,$loanid, 2);
					}
					echo "<tr align='center'>";
					$prurl = getUserProfileUrl($applicant_id);
					$refurl = getUserProfileUrl($referrer_id);
					echo "<td><a href='$prurl'>$applicant_name</a></td>";
					echo "<td><a href='$refurl'>$referrer_name</a></td>";
					echo "<td>$ref_commission ($currency)</td>";
					echo "<td>$percent_repay%</td>";
					echo "<td>$percent_repay_till</td>";
					echo "<td>$failed_reason</td>";
					echo "</tr>";
				}
				if(empty($set))
				{
					echo "<tr align='center'><td colspan=6>No Data</td></tr>";
				} ?>
			</tbody>
		</table>
	</div>
	<br/>
	<h3 class="subhead">Paid Commissions<p id="paid_comm" class="view-more-less">View Less</p></h3>
	<div id="paid_comm_desc">
		<table class="zebra-striped">
			<thead>
				<tr>
					<th><strong>Applicant</strong></th>
					<th><strong>Referring Borrower</strong></th>
					<th><strong>Commission Amount</strong></th>
					<th><strong>Amount Credited</strong></th>
					<th><strong>Payment Date</strong></th>
				</tr>
			</thead>
			<tbody>
		<?php
				$set=$database->getPaidCommissions();
				foreach($set as $row)
				{
					$applicant_id =$row['applicant_id'];
					$referrer_id =$row['referrer_id'];
					$ref_commission =$row['ref_commission'];
					$paid_amt =number_format(round_local($row['paid_amt']), 0, '.', ',');
					$paid_date =date('d M Y', $row['paid_date']);
					$applicant_name = $database->getNameById($applicant_id);
					$referrer_name = $database->getNameById($referrer_id);
					$currency = $database->getUserCurrency($referrer_id);
					$refurl = getUserProfileUrl($referrer_id);
					$aplcurl = getUserProfileUrl($applicant_id);
					echo "<tr align='center'>";
					echo "<td><a href='$aplcurl'>$applicant_name</a></td>";
					echo "<td><a href='$refurl'>$referrer_name</a></td>";
					echo "<td>$ref_commission ($currency)</td>";
					echo "<td>$paid_amt ($currency)</td>";
					echo "<td>$paid_date</td>";
					echo "</tr>";
				}
				if(empty($set))
				{
					echo "<tr align='center'><td colspan=5>No Data</td></tr>";
				}?>
			</tbody>
		</table>
	</div>
<?php
}
else if($session->userlevel==BORROWER_LEVEL)
{ 
	$userinfo = $database->getUserInfo($session->username);
	$referDetail=$database->getReferrals($userinfo['country'], false);
	$currency_id=$database->getCurrencyIdByCountryCode($referDetail['country']);
	$currency=$database->getCurrencyNameByCurrencyId($currency_id);
	?>
	<div align='left' class='static'><h1><?php echo $lang['referral']['referral_program']; ?></h1></div>
	<p><?php echo $lang['referral']['desc1']; ?>:</p>
	<p><?php echo $lang['referral']['desc2']; ?></p>
	<p><?php echo $lang['referral']['desc3']; ?></p>
	<p><?php echo $lang['referral']['desc4']; ?> <?php echo $referDetail['ref_commission']." (".$currency.")"?> <?php echo $lang['referral']['desc5']; ?> 
<?php if($referDetail['percent_repay']==0)
		echo $lang['referral']['desc6'];
	  else
		  echo $lang['referral']['repaid']." ".$referDetail['percent_repay']."% ".$lang['referral']['desc7'];
?>
	</p>
	<br/><br/>
	<h3 class="subhead"><?php echo $lang['referral']['pending_commissions']; ?><p id="pending_comm" class="view-more-less">View Less</p></h3>
	<div id="pending_comm_desc">
		<table class="zebra-striped">
			<thead>
				<tr>
					<th><strong><?php echo $lang['referral']['applicant']; ?></strong></th>
					<th><strong><?php echo $lang['referral']['referring_borrower']; ?></strong></th>
					<th><strong><?php echo $lang['referral']['commission_amount']; ?></strong></th>
					<th><strong><?php echo $lang['referral']['percentage_required']; ?></strong></th>
					<th><strong><?php echo $lang['referral']['percentage_repaid_to_date']; ?></strong></th>
				</tr>
			</thead>
			<tbody>
		<?php
				$set=$database->getPendingCommissions($session->userid);					
				foreach($set as $row)
				{
					$applicant_id =$row['applicant_id'];
					$referrer_id =$row['referrer_id'];
					$ref_commission =$row['ref_commission'];
					$percent_repay =$row['percent_repay'];
					$applicant_name = $database->getNameById($applicant_id);
					$referrer_name = $database->getNameById($referrer_id);
					$currency = $database->getUserCurrency($referrer_id);
					$percent_repay_till='';
					$loanid= $database->getCurrentLoanid($applicant_id);
					if($loanid)
					{
						$percent_repay_till= $session->getStatusBar($applicant_id,$loanid, 2);
					}
					$aplcurl = getUserProfileUrl($applicant_id);
					$refurl = getUserProfileUrl($referrer_id);
					echo "<tr align='center'>";
					echo "<td><a href='$aplcurl'>$applicant_name</a></td>";
					echo "<td><a href='$refurl'>$referrer_name</a></td>";
					echo "<td>$ref_commission ($currency)</td>";
					echo "<td>$percent_repay%</td>";
					echo "<td>$percent_repay_till</td>";
					echo "</tr>";
				}	
				if(empty($set))
				{
					echo "<tr align='center'><td colspan=5>No Data</td></tr>";
				}	?>
			</tbody>
		</table>
	</div>
	<h3 class="subhead"><?php echo $lang['referral']['paid_commissions']; ?><p id="paid_comm" class="view-more-less">View Less</p></h3>
	<div id="paid_comm_desc">
		<table class="zebra-striped">
			<thead>
				<tr>
					<th><strong><?php echo $lang['referral']['applicant']; ?></strong></th>
					<th><strong><?php echo $lang['referral']['referring_borrower']; ?></strong></th>
					<th><strong><?php echo $lang['referral']['commission_amount']; ?></strong></th>					
					<th><strong><?php echo $lang['referral']['payment_date']; ?></strong></th>
				</tr>
			</thead>
			<tbody>
		<?php
				$set=$database->getPaidCommissions($session->userid);
				foreach($set as $row)
				{
					$applicant_id =$row['applicant_id'];
					$referrer_id =$row['referrer_id'];					
					$paid_amt =number_format(round_local($row['paid_amt']), 0, '.', ',');
					$paid_date =date('d M Y', $row['paid_date']);
					$applicant_name = $database->getNameById($applicant_id);
					$referrer_name = $database->getNameById($referrer_id);
					$currency = $database->getUserCurrency($referrer_id);
					$refurl = getUserProfileUrl($referrer_id);
					$aplcurl = getUserProfileUrl($applicant_id);
					echo "<tr align='center'>";
					echo "<td><a href='$aplcurl'>$applicant_name</a></td>";
					echo "<td><a href='$refurl'>$referrer_name</a></td>";					
					echo "<td>$paid_amt ($currency)</td>";
					echo "<td>$paid_date</td>";
					echo "</tr>";
				}
				if(empty($set))
				{
					echo "<tr align='center'><td colspan=5>No Data</td></tr>";
				} ?>
			</tbody>
		</table>
	</div>
	<br/>
	<p><?php echo $lang['referral']['desc8'];?> <a href="mailto:service@zidisha.org">service@zidisha.org</a>.</p>
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