<?php 
	include_once("library/session.php");
	global $database;
	$id = $_GET['id_no'];
	$recipient_email = $_GET['email_id'];
	$order_detail = $database->GetOrderDetailByEmail($id,$recipient_email);
	if($order_detail)
	{
		$amt = number_format($order_detail['card_amount']);
		$to = $order_detail['to_name'];
		$from = $order_detail['from_name'];
		$msg = $order_detail['message'];
		$exp_date=date ( 'F d, Y', $order_detail['exp_date']);
		$rdm_code=$order_detail['card_code'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="#SiteUrl#style/isp.css" rel="stylesheet" type="text/css" />
<title>Zidisha Gift Certificate</title>
<style type="text/css">
body
{
font-family: "arial"; 

}
</style>
</head>
<body>
	<table width="831px"  height="575px"   cellpadding="0" cellspacing="0" style= "background-color:#FFFFFF; border:#000000 solid 5px;  font:Arial">
		<tr>
			<td  width="60%" valign="top" style="padding-left:30px; padding-top:0px;">
				<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td colspan="2" width="40%" valign="middle">
						<img src="/images/gift_card/combined_img.png" height="330"/></td>
						
						
					</tr>
					
					<tr>
						<td  width='40%'align="left"><font size='4' face='Arial' color='#000000'> Redemption Code:</font></td>
						  <td align="left" ><div id="code"><b><font size='5' face='Arial' color='#000000'><?php echo $rdm_code; ?></font></b></div></td>
					</tr>
                    <tr>
			<td  colspan="2" style="padding-left:0px; padding-right:8px; padding-top:5px; text-align:justify; padding-bottom:15px;"><font size='4' face='Arial' color='#000000'>Redeem this gift at www.zidisha.org and make a loan to a small business owner from around the world.
				Communicate directly with him or her as the business you funded grows. Then relend your funds to another individual of your choice, fighting poverty while getting to know some of the world's most remarkable entrepreneurs.</font>                
			</td>
		</tr>
		<tr>
			<td align="left"  colspan="2" style="padding-left:0px; padding-bottom:3px; padding-top:2px;">
				<i><font size='4' face='Arial' color='#000000'>Expires on <?php echo $exp_date; ?>.</font></i>
			</td>
		</tr>
				</table>
			</td>
			<td width="40%" valign="top" style="padding-left:8px; padding-top:8px;">
				<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td align="left" style=" padding-left:50px; padding-bottom:10px; ">
                        <div><b><font size='50' color='#0095F2'>Gift Card</font></b></div>
						
						 <div id='amt' style=" padding-left:50px;"><b><font size='50' color='#0095F2'>$ <?php echo $amt; ?></font></b></div>
						</td>
					</tr>
					<tr>
						<td align="left" style="padding-left:5px;">
							<table>
								<tr>
									<td valign="top" align="left" style="padding-top:5px;"><font size='4' face='Arial' color='#000000'>To:</font></td>
									<td valign="bottom" style="padding-left:24px;padding-top:5px;"><font size='4' face='Arial' color='#000000'><?php echo $to; ?></font></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td align="left" style="padding-left:5px;">
							<table>
								<tr>
									<td  align="left" valign="top" style="padding-top:6px;"><font size='4' face='Arial' color='#000000'>From:</font></td>
									<td style="padding-left:8px;padding-top:6px;"><font size='4' face='Arial' color='#000000'><?php echo $from; ?></font></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td align="left">
							<table width="100%" cellpadding="0" cellspacing="0">
								<tr>
									<td rowspan=""  colspan="2" style="padding-top:6px; padding-left:8px; text-align:justify; padding-right:20px;  "><font size='3' face='Arial' color='#000000'><?php echo nl2br($msg); ?></font></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		
	</table>
<?php
	}
	else
		echo "Sorry You do not have permission to open this Gift Card.";
?>
</body>
</html>