<?php
	include_once("library/session.php");
	global $database;
	$flag = 0;
	$flag2 = 1;
	if(isset($_GET['id_no']) && isset($_GET['card_code']))
	{
		$id = $_GET['id_no'];
		$card_code = $_GET['card_code'];
		$order_detail = $database->GetOrderDetailByCardCode($id,$card_code);
		if($order_detail)
		{
			$amt = number_format($order_detail['card_amount']);
			$to = $order_detail['to_name'];
			$from = $order_detail['from_name'];
			$msg = $order_detail['message'];
			$exp_date=date ( 'F j, Y', $order_detail['exp_date']);
			$rdm_code=$order_detail['card_code'];
			if($order_detail['order_type'] == 'print')
				$flag =1;
		}
		else
		{
			echo "Sorry, No Gift Card available. Please contact to Zidisha.";
			$flag2=0;
		}
	}
	else if(isset($_GET['amt']))
	{
		$amt = $_GET['amt'];
		$to = stripslashes($_GET['to']);
		$from =stripslashes($_GET['from']);
		$msg = stripslashes(nl2br(urldecode($_GET['msg'])));
		$exp_date=$_GET['exp_date'];
		$rdm_code = "XXX-XXX-XXX-XXX";
		
	}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Zidisha Gift Certificate</title>
<style type="text/css">
@media print{.show{display:none; text-decoration:none;}}
@media screen{.show{display:block; text-decoration:none;}}
body
{
font-family: "arial"; 
margin-top:8px;
margin-left:9px;
}
</style>
</head>

<body>
<?php if($flag2==1)
{
echo "<img width='725' src='images/gift_card/card1.png' />";

if($flag == 1)
{
	echo "<div style='position: absolute; top: 20px;  left: 750px;'><button type='button' onClick='javascript:window.print();' class='print show'>Print Gift Card</button></div>";
} 
}?>
<div style="position: absolute; top: 77px; left: 590px; font-size: 42px; color: #0095F2; "><?php echo $amt; ?></div>
<div style="position: absolute; top: 128px; left: 530px; text-align:left; font-size: 19px;"><?php echo $to; ?></div>
<div style="position: absolute; top: 162px; left: 530px; text-align:left; font-size: 19px;"><?php echo $from; ?></div>
<div style="position: absolute; top: 210px; left: 472px; width: 245px; text-align:justify;font-size: 15px;"><?php echo nl2br(htmlentities(strip_tags($msg))); ?></div>
<div style="position: absolute; top: 295px; left: 190px; text-align:left; font-size: 19px;"><?php echo $rdm_code; ?></div>
<div style="position: absolute; top: 489px;  left: 115px; text-align:left; font-size: 15px;"><I><b><?php echo $exp_date; ?></b></I></div>
</body>
</html>