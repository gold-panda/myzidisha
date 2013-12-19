<?php
	include_once("library/session.php");   /*   created by chetan   */
date_default_timezone_set('America/New_York');
if(isset($_SESSION['orderid'])) {
	$order_id = $_SESSION['orderid'];
	unset($_SESSION['orderid']);
	Logger_Array("cvError_order_id",$order_id);
}
if(!$database)
{
	Logger_Array("cvError_db_connecttion",'database connection error');
}	
if(isset($order_id)) {
	$order_detail = $database->GetOrderDetail($order_id);
	Logger_Array("cvError_order_detail",$order_detail);
}
if(empty($order_detail) && isset($_SESSION['PaidGiftcardCart'])) {
	$order_detail = $database->GetOrderDetailbyCustom($_SESSION['PaidGiftcardCart']);
	Logger_Array("cvError_order_detail",$order_detail);
	unset($_SESSION['PaidGiftcardCart']);
}
if(empty($order_detail) && isset($_SESSION['gifcardids'])) {
	$order_detail = $database->GetOrderDetailPaynow($_SESSION['gifcardids']);
	unset($_SESSION['gifcardids']);
	Logger_Array("cvError_order_detail",$order_detail);
}
?>
<div class="row">
	<div class="span16">
<?php
		if(empty($order_detail))
		{
			echo "";
		}
		else
		{
			echo "<br>";
			if($order_detail[0]['order_type'] == 'print')
			{
				echo "Thanks for your gift card purchase!  Please review your order details below:";
				echo "<br>";
				foreach( $order_detail as $rows )
				{
					$amt =  number_format($rows['card_amount'], 2, '.', ',');
					$to = $rows['to_name'];
					$from = $rows['from_name'];
					$msg = $rows['message'];
					$date = $rows['date'];
					$exp_date = date ( 'F j, Y', $rows['exp_date']);
					$recmail = $rows['recipient_email'];
					$card_code = $rows['card_code'];
					if(empty($order_id)) {
						$card_id = $rows['txn_id'];
					}else {
						$card_id = $order_id;
					}
					$url = "cardimage.php?id_no=$card_id&card_code=$card_code";
				?>
					<br>
					<table class='detail'>
					<!--<tbody>
							<tr>
								<td>
									<table class='detail'>-->
									<div align="center">	
										<tbody>
											<tr>
												<td width='28%'>Order Placed on: </td>
												<td><?php echo (date ( 'M j, Y', $date)." at ".date ( 'h:i A', $date)); ?></td>
											</tr>
											<tr>
												<td>Gift Card Amount: </td>
												<td>USD <?php echo $amt; ?></td>
											</tr>
											<tr>
												<td>Delivery Method: </td>
												<td>Print</td>
											</tr>
											<tr>
												<td>To: </td>
												<td><?php echo $to; ?></td>
											</tr>
											<tr>
												<td>From: </td>
												<td><?php echo $from; ?></td>
											</tr>
											<tr>
												<td>Message: </td>
												<td><?php echo $msg; ?></td>
											</tr>
										</tbody>
									</div>	
								<!--</table>
								</td>
								<td width='30%'style="padding-left:10px; padding-right:10px;" >
									<div align='center'><a href="<?php echo $url ?>" target='_blank' style='text-decoration:none'><img width="150" border='none'src="./images/gift_card/samplecard_150.png"/>Preview</a></div>
								</td>
							</tr>
						</tbody>-->
					</table>
					<br>
					<div align='center'><button class='btn' type="button" onClick="window.open('<?php echo SITE_URL.$url; ?>')">Print Gift Card</button></div>
					<br>
					<hr>
					<script type="text/javascript">
					<!--
						window.onbeforeunload = confirmExit;
						function confirmExit() {
							return "Please confirm that you have completed printing of your gift card.";
						}
					//-->
					</script>
		<?php	}
			}
			if($order_detail[0]['order_type'] == 'email')
			{
				echo "Thanks for your gift card purchase!  Please review your order details below:";
				echo "<br>";
				foreach( $order_detail as $rows )
				{
					$amt =  number_format($rows['card_amount'], 2, '.', ',');
					$to = $rows['to_name'];
					$from = $rows['from_name'];
					$msg = $rows['message'];
					$date = $rows['date'];
					$exp_date = date ( 'F j, Y', $rows['exp_date']);
					$recmail = $rows['recipient_email'];
					$card_code = $rows['card_code'];
				?>
					<br>
					<table class='detail'>
					<!--<tbody>
							<tr>
								<td>
									<table class='detail'>-->
										<tbody>
											<tr>
												<td width='28%'>Order Placed on: </td>
												<td><?php echo (date ( 'M j, Y', $date)." at ".date ( 'h:i A', $date)); ?></td>
											</tr>
											<tr>
												<td>Gift Card Amount: </td>
												<td>USD <?php echo $amt; ?></td>
											</tr>
											<tr>
												<td>Delivery Method: </td>
												<td>Email to <?php echo $recmail; ?></td>
											</tr>
											<tr>
												<td>To: </td>
												<td><?php echo $to; ?></td>
											</tr>
											<tr>
												<td>From: </td>
												<td><?php echo $from; ?></td>
											</tr>
											<tr>
												<td>Message: </td>
												<td><?php echo $msg; ?></td>
											</tr>
										</tbody>
								<!--</table>
								</td>
								<td style="padding-left:10px; padding-right:10px;" >
									<div align='center'><a href="<?php echo $url ?>" target='_blank' style='text-decoration:none'><img width="150" border='none'src="./images/gift_card/samplecard_150.png"/>Preview</a></div>
								</td>
							</tr>
						</tbody>-->
					</table>
					<br>
					<p>This gift card was emailed to <?php echo $recmail; ?> on <?php echo (date ( 'M j, Y', $date)." at ".date ( 'h:i A', $date)); ?>.</p>
					<br>
					<hr>
	<?php		}
			}
		}
		echo "<br><div align='center'><a href='./'>Return to Home Page</a></div>";
?>
	</div>
</div>