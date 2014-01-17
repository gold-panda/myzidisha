<!-- Google Code for Lending Cart Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1005464495;
var google_conversion_language = "en";
var google_conversion_format = "2";
var google_conversion_color = "ffffff";
var google_conversion_label = "y4alCJHN6AMQr9e43wM";
var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
	<img height="1" width="1" style="border-style:none;" alt="" src="https://www.googleadservices.com/pagead/conversion/1005464495/?value=0&amp;label=y4alCJHN6AMQr9e43wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>

<script type="text/javascript" src="includes/scripts/eepztooltip.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
		$("#stay-target-1").ezpz_tooltip({
			stayOnContent: true,
			offset: 0
		});
	$("#stay-target-2").ezpz_tooltip({
			stayOnContent: true,
			offset: 0
		});
});
</script>
<script type="text/javascript" src="includes/scripts/generic.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<style type="text/css">
	@import url(library/tooltips/btnew.css);
</style>
<?php
include_once("library/session.php");
include_once("./editables/order-tnc.php");
$path=	getEditablePath('order-tnc.php');
include_once("editables/".$path);
include_once("./editables/withdraw.php");
$path=	getEditablePath('withdraw.php');
include_once("editables/".$path);
$cont = 0;
if(isset($_GET['cont'])) {
	$cont = 1;
}
if(isset($_SESSION['LendingCartBid']) && ($session->userlevel == LENDER_LEVEL || empty($session->userid))&& isset($_SESSION['bidPaymentId'])) {
	unset($_SESSION['LendingCartBid']);	
	$bidPaymentId = $_SESSION['bidPaymentId'];
	$bid_detail = $database->getBidDetail($bidPaymentId, $session->userid);
	$database->AddToLendingCart($bidPaymentId, 1, $session->userid);
	$brwrname = $database->getNameById($bid_detail['borrowerid']);
	$msg = 'Thank you! USD '.number_format($bid_detail['bidamt'], 2, ".", ",").' toward a loan for '.$brwrname.' has been added to your Lending Cart.';
}else if(isset($_SESSION['LendingCartGift']) && isset($_SESSION['order_id'])) {
	$order_id = $_SESSION['order_id'];
	$order_detail = $database->GetOrderDetailForCart($order_id);
	$database->AddToLendingCart($order_id, 2, $session->userid);
	if($order_detail['count']==1) {
		if(empty($order_detail['to_name'])) {
			$recpName = '';
		}else {
			$recpName = ' for '.$order_detail['to_name'];
		}
		$cardamt = $order_detail['card_amount'];
		$msg = 'Thank you! A gift card of USD '.number_format($order_detail['card_amount'], 2, ".", ",").$recpName.' has been added to your Lending Cart.';
	}else if($order_detail['count'] > 1) {
		$msg = 'Thank you! '.convertNumber2word($order_detail['count']).' gift cards have been added to your Lending Cart.';
	}
	unset($_SESSION['order_id']);
}elseif(isset($_SESSION['LendingCartDonation']) && isset($_SESSION['donation_id'])){
	$donation_id= $_SESSION['donation_id'];
	$donation_detail = $database->GetDonationDetailForCart($donation_id);
	$database->AddToLendingCart($donation_id, 3, $session->userid);
	$msg = 'Thank you! A Donation of USD '.number_format($donation_detail['amount'], 2, ".", ",").' to zidisha has been added to your Lending Cart.';
	unset($_SESSION['donation_id']);
	unset($_SESSION['LendingCartDonation']);
}
	$Lendingcart = $database->getLendingCart($session->userid);
	$paypalTranFeeOrg= $database->getAdminSetting('PaypalTransaction');
	$amtincart = 0;
	$donateamt=0;
echo"<div class='span12'>";
	if(isset($_SESSION['notloggedinuser'])) {
		echo"<div style='text-align:center;font-size:16px;color:green;'><strong>In order to continue, please <a href='javascript:void' onclick='getloginfocus()'>log in</a> or <a href='index.php?p=1&sel=2'>create a member account</a></strong>.</div>";
		unset($_SESSION['notloggedinuser']);
	}
	if(isset($_SESSION['donation_give'])) {
		echo"<div style='text-align:center;font-size:16px;color:green;'><strong>Thank you for your donation. We will send a donation receipt confirmation to your registered email address.</strong>.</div>";
		unset($_SESSION['donation_give']);
	}
if(!empty($Lendingcart) && $cont == 0 ) {
?>
		<div align='left' class='static'><h1>Lending Cart</h1></div>
		<div style="color:green;">
			<?php if(isset($msg)) {
				echo "<div paddding-top:10px;>$msg</div>";
			}?>
		</div>
		<div style="padding-top:20px;">
			<a href="microfinance/lend.html">Add another loan</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="microfinance/gift-cards.html">Add a gift card - Your friend chooses a loan!</a>
		</div>
		<table class='zebra-striped' style="padding-top:20px;">
			<tbody>
				<?php  
				foreach($Lendingcart as $cart){
					if($cart['type'] == 1) {
						$bname = trim($cart['FirstName']." ".$cart['LastName']);
						$borrowerid = $cart['borrowerid'];
						$loan_id = $cart['loanid'];
						$city = $cart['City'];
						$country = $database->mysetCountry($cart['Country']);
						$amtincart += $cart['bidamt'];
						$donateamt+=$cart['bidamt'];
						$loanprurl = getLoanprofileUrl($cart['borrowerid'],$cart['loanid']);
				?>
					<tr style="height:55px">
						<td style="width:60px"><a href='<?php echo $loanprurl ?>'><img width="60px;" class="my_port_img" src="library/getimagenew.php?id=<?php echo $cart['borrowerid'] ?>&width=120&height=80"/></a></td>
						<td>Loan for <a href='<?php echo $loanprurl?>'><?php echo $bname ?></a>&nbsp;&nbsp;<?php echo  $city?>,&nbsp;<?php echo $country?></td>
						<td>USD <?php echo number_format($cart['bidamt'], 2, ".", ",");?></td>
						<td> 
							<form action='process.php' id="<?php echo $cart['id']?>" method='post' id="removeform<?php echo $cart['id']?>" name="removeform<?php echo $cart['id']?>" >
								<input type='hidden' name='RemoveFromCart'/>
								<input type='hidden' value="<?php echo generateToken('RemoveFromCart')?>" name='user_guess'/>
								<input type='hidden' value="<?php echo $cart['id']?>" name='Cartid'/>
								<a  onclick="document.getElementById('<?php echo $cart['id']?>').submit()" href="javascript:void()">remove</>
							</form>
						</td>
					</tr>
				<? }
				else if($cart['type'] == '2') {
					if(empty($cart['to_name'])) {
						$toname = '';
					}else {
						$toname = 'for '.$cart['to_name'];
					}
					$amtincart += $cart['card_amount'];
					$donateamt += $cart['card_amount'];
					?>
					<tr style="height:55px">
						<td style="width:60px"></td>
						<td >Gift Card <?php echo $toname?></td>
						<td >USD <?php echo number_format($cart['card_amount'], 2, ".", ",");?></td>
						<td> 
							<form action='process.php' method='post' id="<?php echo $cart['id']?>" name="removeform<?php echo $cart['id']?>" >
								<input type='hidden' name='RemoveFromCart'/>
								<input type='hidden' value="<?php echo generateToken('RemoveFromCart')?>" name='user_guess'/>
								<input type='hidden' value="<?php echo $cart['id']?>" name='Cartid'/>
								<a  onclick="document.getElementById('<?php echo $cart['id']?>').submit()" href="javascript:void()">remove</>
							</form>
						</td>
					</tr>
					<?php }
				elseif($cart['type'] == 3){
					$amtincart += $cart['amount']; ?>
					<tr style="height:55px">
						<td style="width:60px"></td>
						<td >Donation</td>
						<td >USD <?php echo number_format($cart['amount'], 2, ".", ",");?></td>
						<td> 
							<form action='process.php' method='post' id="<?php echo $cart['id']?>" name="removeform<?php echo $cart['id']?>" >
								<input type='hidden' name='RemoveFromCart'/>
								<input type='hidden' value="<?php echo generateToken('RemoveFromCart')?>" name='user_guess'/>
								<input type='hidden' value="<?php echo $cart['id']?>" name='Cartid'/>
								<a  onclick="document.getElementById('<?php echo $cart['id']?>').submit()" href="javascript:void()">remove</>
							</form>
						</td>
					</tr>
				<?php }
				}
				?>
			<tbody>
		</table>
		<?php 
			$amountavail = truncate_num(round($session->amountToUseForBid($session->userid), 4),2);
			if(isset($_SESSION['Nodonationincart'])) {
				$donation = $_SESSION['Nodonationincart'];
			}else {
				$donation = number_format(($donateamt*15)/100, 2, ".", "");
				
			}
			$transfee = 0;
			$TotalpaymentOrg = 0;
			$Totalpayment = 0;
			if($amountavail < $amtincart) {
				if($donateamt>0){
					$TotalpaymentOrg = bcsub($donateamt, $amountavail, 2);
					$transfee = round(($TotalpaymentOrg*$paypalTranFeeOrg)/100,2);
				}else{
					$paypalTranFeeOrg=0;
					$transfee=0;
				}
				$Totalpayment = $TotalpaymentOrg + $donation + $transfee;
				
			}
			$totAmt = $amtincart + $donation + $transfee;
		?>
		<form action="process.php" method="post">
			<table class="detail">

				 <tr>
					<td><?php echo $lang['withdraw']['tran_fee'];?>: <a  style='margin-left:0px;cursor:pointer;' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['withdraw']['paypal_desc1_new2'];?></span><span class='bottom'></span></span></a></td>
					
					<td>USD <?php echo number_format($transfee, 2, ".", ""); ?></td>
					
				</tr> 
				<tr height="20px"></tr>

				<tr>
					<td>Subtotal:</td>
					
					<td>USD <?php echo number_format($amtincart, 2, ".", ",");?></td>
										
				</tr>
				<tr height="20px"></tr>
				<tr>
					<td style="width:320px;">
						<?php echo $lang['withdraw']['donation_amt'];?>: 
						<img src='library/tooltips/help.png' class="stay-tooltip-target tooltip-target" id="stay-target-1" style='border-style:none;display:inline'/>
						<div class="stay-tooltip-content tooltip-content" id="stay-content-1">
							<span class="tooltip">
								<span class="tooltipTop"></span>
								<span class="tooltipMiddle" >
								<?php echo $lang['withdraw']['donation_tooltip'];?>
									<p class="auditedreportlink">
										<a  href="includes/financial_report.php" rel="facebox"><?php echo $lang['withdraw']['financial_report']?></a>
									</p>
								</span>	
								<span class="tooltipBottom"></span>
							</span>
						</div>
						<br/>
					</td>
					<td style="text-align:left">
						<input type="text"  name="paypal_donation" id="paypal_donation_cart" value="<?php echo $donation?>">
					</td>
					<td><a href="javascript:void()" id='nodonation' >No donation</a></td>
				</tr>
				<tr height="20px"></tr>
				
				<tr>
					<td><strong>Total Payment:</strong></td>
					<td ><strong>USD <span id="tot_amt_cart"><?php echo number_format($totAmt, 2, ".", ",");?></span></strong></td>
					<td></td>
				</tr>
				<tr height="20px"></tr>
				<tr>
					<td>Payment method(s):</td>
					<td ></td>
					<td></td>
				</tr>
				<?php $submitval = 'Continue';
				if($totAmt > $amountavail) {
					$displayrow = '';
					$amtchargedfrmavail = $amountavail; 
				} else {
					$displayrow = 'display:none';
					$submitval = 'Confirm';
					$amtchargedfrmavail = $totAmt;
				}?>
				<?php if($amountavail > 0) {?>
					<tr>
						<td>Credit Available:<br/>
						(Current Balance: USD <?php echo number_format($amountavail, 2, ".", ",");?>)
						</td>
						<td>USD <span id="chargefromcravail"><?php echo $amtchargedfrmavail?></span></td>
						<td></td>
					</tr>
					<tr height="20px"></tr>
				<?php } ?>
				<tr id='amtTochargedPaypalrow' style='<?php echo $displayrow?>'>
					<td>PayPal or Credit Card:</td>
					<td>USD <span id='amtTochargedPaypal'> <?php echo number_format(($totAmt - $amountavail), 2, ".", ",");?></span></td>
					<td></td>
				</tr>
				<tr style='<?php echo $displayrow?>' height="20px"></tr>
				<tr>
					<td><i>Other ways to credit your account:</i></td>
					<td></td>
				</tr>
				<tr height="20px"></tr>
				<tr>
					<td colspan='2'><i>Mail a check from a US bank account (1-2 weeks, no fees) </i><a  style='margin-left:0px;cursor:pointer;' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['withdraw']['check_desc1'];?></span><span class='bottom'></span></span></a>
					<br/><br/>
					<i><?php echo $lang['withdraw']['other_pmt_option1']?></i>
					 <a  style='margin-left:0px;cursor:pointer;' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['withdraw']['other_pmt_tool_tip1'];?></span><span class='bottom'></span></span></a><br/><br/>
					<i>Send a bank wire from any location worldwide (1-2 weeks, fees estimated at $50 - $100)</i>  <img src='library/tooltips/help.png' class="stay-tooltip-target tooltip-target" id="stay-target-2" style='border-style:none;display:inline'/>
						<div class="stay-tooltip-content tooltip-content" id="stay-content-2">
							<span class="tooltip">
								<span class="tooltipTop"></span>
								<span class="tooltipMiddle" >
									<?php echo $lang['withdraw']['transfer_desc1'];?>
								</span>	
								<span class="tooltipBottom"></span>
							</span>
						</div>
					</td>
					<td></td>
				</tr>
				
				<tr>
					<td colspan='3' style="text-align:right;padding-right:50px;">
						<input type="hidden" size=5 name="paypal_transaction" id="paypal_transaction_cart" value="<?php echo number_format($transfee, 2, ".", ""); ?>" autocomplete=off readonly="true">
						<input type="hidden" id='AmtIncart' name="AmtIncart" value='<?php echo $amtincart?>'>
						<input type="hidden" id='creditavailable' name="cravail" value="<?php echo $amountavail?>">
						<input type="hidden" name="ProcessCart" value=''>
						<input type="hidden" name="lending_cart_paypal">
						<input type="hidden" name="addPayment">
						<input type="hidden" name="paypal_trans"  id="paypal_trans_cart" value="<?php echo $paypalTranFeeOrg?>">
						<input type="hidden" name="donated_amt"  id="donated_amt" value="<?php echo $donateamt?>">
						<input type="hidden" value="<?php echo $Totalpayment?>" name="paypal_amount" id="hidden_paypal_amount">
						<input class="btn" id = 'cartsubmitButton' type="submit" value="<?php echo $submitval?>">
					</td>
				</tr>
			</table>
		</form>
		
		<?php 
		} else {
				echo"<br/><br/>Your lending cart is empty.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				echo"<strong><a href='microfinance/lend.html' style='font-size:16px'>Make a Loan</a></strong>";
			}?>
	<script type="text/javascript">
	<!--
		function getloginfocus() {
			document.getElementById("username").focus();
		}
	
	//-->
	</script>
