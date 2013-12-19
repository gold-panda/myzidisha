<?php
	include_once("library/session.php");
	include_once("./editables/withdraw.php");
	include_once("error.php");

 if(isset($_GET['err']) && $_GET['err'] >0){
		echo "<table width='80%' bgcolor='red' align='center'><tr align='center'><td>";
		echo "<font color='white'>".$errorArray[$_GET['err']]."</font>";
		echo "</td></tr></table>";
 }
if($session->userlevel  == LENDER_LEVEL  ){
 echo "<h3>".$lang['payment']['manageFunds']."</h3>";
	
	$availAmt = $session->amountToUseForBid($userid);
	$investAmt = $database->amountInActiveBids($session->userid);

if(PAYPAL_PERSONAL){
?>
<b> It is very important that you enter your correct username in the box below. Else the payment may not be correctly credited to your account.</b> <br/><br/>
<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<table>
<tr><td><input type="hidden" name="on0" value="name">name</td></tr><tr><td><select name="os0">
	<option value="Option 1">Option 1 $100.00
	<option value="Option 2">Option 2 $500.00
	<option value="Option 3">Option 3 $1,000.00
</select> </td></tr>
<tr><td><input type="hidden" name="on1" value="Enter your Zidisha Username">Enter your Zidisha Username</td></tr><tr><td><input type="text" name="os1" maxlength="60"></td></tr>
</table>
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>




<?php
}
else
{
?>
<table width="100%" border="0">
<tr><td width="50%">
<b><?php echo $lang['payment']['add_fund'];?></b>
<form action="library/paypal/getMoney.php" method="post">  
  <input type="text" name="amount" value="100.00">  
  <input type="submit" value=<?php echo $lang['payment']['add'];?>>  
</form> 
<br/><br/><br/>
</td></tr> </table>
<?php } ?>
<table width="100%" border="0"><tr><td width="100%">
<?php

echo ''.$lang['payment']['total_avl_amt'].' USD '.number_format($availAmt, 2, ".", ",");
echo ' <br/>Amount invested in different active bids: USD ' . number_format($investAmt, 2, ".", ",") . '<br/><br/>';
?>
</td></tr>
<tr><td width="100%">
<?php 
	echo "<h3>".$lang['payment']['widrawal_status']."</h3>";
$withdrawAmt = $database->getwithdraw($session->userid);
	if($withdrawAmt){ ?>
		<table cellspacing="1" width="99%" class="tablesorter {sortlist: [[0,0],[4,0]]}">
		<thead>
		<tr>
		<th><? echo $lang['payment']['date'];?></th>
		<th><? echo $lang['payment']['amount'];?></th>
		<th>Paypal Email</th>
		<th></th>
		</tr>
		</thead>
		<tbody>
	<?php
			foreach( $withdrawAmt as $rows ){//id  userid  amount  date  paid 
								
			echo "<tr>";
				echo "<td style='text-align:center'>".date('m/d/Y', $rows['date'])."</td>";
				echo "<td style='text-align:center'>".number_format($rows['amount'], 2, ".", ",")."</td>";
				echo "<td style='text-align:center'>".$rows['paypalemail']."</td>";

				if($rows['paid'] == 0)
					echo "<td style='text-align:center'>" .$lang['payment']['Pending']."</td>";
				else
					echo "<td style='text-align:center'>".$lang['payment']['Completed']."</td>";
				
			echo "</tr>";
			}
	?>
	</tbody>
		</table>
		<?php 
	}else{
		echo 'No request yet!';
	}
	
	?>
</td></tr>
<tr><td width="50%">
<br/><br/><b><?php echo $lang['payment']['withdraw_fund'];?></b>
<table>
<tr><td>Paypal Email:</td><td>
<?php if(PAYPAL_PERSONAL) {?>
<form action="updateprocess.php" method="post">  
<?php } else { ?>
<form action="library/paypal/MassPayReceipt.php" method="post">  
<?php }?>
  <input type="text" name="paypalemail" value="<?php echo $form->value("paypalemail"); ?>"> </td> </tr>
<tr><td>Amount:</td><td>  <input type="text" name="amount" value="<?php echo $form->value("amount"); ?>"> </td> </tr>
 <tr><td></td><td> <input type="hidden" name="withdraw">
  <input type="submit" value=<?php echo $lang['payment']['withdraw'];?>>  </td> </tr>
</form> <div id="berror"><?php echo $form->error("amount"); ?>

</td> </tr></table>

</div>

<br/><br/><br/>

</td></tr></table>

<?php
	}else if($session->userlevel  == ADMIN_LEVEL ){
	echo "<h3>".$lang['payment']['pending_Payments']."</h3>";
	$set=$database->getwithdraw();
		if(empty($set)){
		echo "There are no withdraw listed";
		}else{							
		?>
		<table cellspacing="1" width="99%" class="tablesorter {sortlist: [[0,0],[4,0]]}">
		<thead>
		<tr>
		<th><? echo $lang['payment']['date'];?></th>
		<th><? echo $lang['payment']['name'];?></th>
		<th>Paypal Email</th>
		<th><? echo $lang['payment']['amount'];?></th>
		<th></th>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach($set as $row ){//userid  amount  date  paid  
		$date=date('m/d/Y', $row['date']);
		$rowid=$row['id'];
		$userid=$row['userid'];
		$Detail=$database->getEmail($userid);
		$name=$Detail['name'];
		$paypalemail = $row ['paypalemail'];
		$email=$Detail['email'];
		$amount=number_format($row['amount'], 2, ".", ",");
		$active=$row['paid'];
		if($active==0){
			$active1=
			"<form method='post' action='updateprocess.php'>".
			"<input name='paywithdraw' type='hidden' />".
			"<input name='lenderid' value='$userid' type='hidden' />".
			"<input name='rowid' value='$rowid' type='hidden' />".
			"<input name='amount' value='$amount' type='hidden' />".
			"<input type='submit' value=".$lang['payment']['pay']." />".
			"</form>";
		}else{
			$active1=$lang['payment']['Accepted'];
		}
																
		echo '<tr>';
		echo "<td>$date</td>";
		echo "<td><a href='index.php?p=12&u=".$userid."'>$name</a></td>";
		echo "<td>$paypalemail</td>";
		echo "<td>$amount</td>";
		echo "<td width='5%'>$active1</td>";
		echo '</tr>';
		}
		?>
		</tbody>
		</table>
		<?php

		}
		}
?>