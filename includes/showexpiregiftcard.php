<?php
include_once("library/session.php");// created by chetan
include_once("./editables/admin.php");
include_once("./editables/showexpiregiftcard.php");
$RequestUrl = $_SERVER['REQUEST_URI'];
?>
<div class='span12'>
<?php
if($session->userlevel==LENDER_LEVEL)
{
	$userid=$session->userid;
	$res=$database->isTranslator($userid);
	if($res==1)

		$isvolunteer=1;
} 


if($isvolunteer==1 || $session->userlevel==ADMIN_LEVEL)
{
	if(isSet($_GET['v']))
		$v = $_GET['v'];
	else
		$v=-1;

	if(isSet($_GET['r']))
		$rowcount=$_GET["r"];
	else
		$rowcount=0;

	if(isSet($_GET['pg']))
		$page=$_GET["pg"];
	else
		$page=0;

	$res=$database->getAllGiftCards($rowcount);

	if(isset($_POST['resendGiftEmail']))
	{

		$session->sendGiftCardMailsToReciever($_POST['order_id'], $_POST['id']);
		echo "<font color='green'><b>Gift Card Mail sent<br/><br/></b></font>";
	}
?>
		<div class="subhead2">
			  <div style="float:left"><div align='left' class='static'><h1><?php echo $lang['showexpiregiftcard']['mng_cards'] ?></h1></div></div>
			  <?php if($session->userlevel==ADMIN_LEVEL ){?><div class="user_instructions">
				<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p'];?>" rel='facebox'>Instructions</a>
			  </div><? } ?>
			  <div style="clear:both"></div>
		</div>
	
<?php 
	echo $form->error("activeerr"); 
	if($v==1)
		echo "<font color='green'><b>".$lang['showexpiregiftcard']['zidisha_donate']."</b></font>";
	else if($v == 0)
		echo "<font color='red'><b>".$lang['showexpiregiftcard']['zidisha_donate_error']."</b></font>";
?>
	<table class="zebra-striped">
		<thead>
			<tr>
				<th><?php echo $lang['showexpiregiftcard']['type']; ?></th>
				<th><?php echo $lang['showexpiregiftcard']['amt']; ?></th>
				<th><?php echo $lang['showexpiregiftcard']['recipient']; ?></th>
				<th><?php echo $lang['showexpiregiftcard']['to']; ?></th>
				<th><?php echo $lang['showexpiregiftcard']['from']; ?></th>
				<th><?php echo $lang['showexpiregiftcard']['sender']; ?></th>
				<th><?php echo $lang['showexpiregiftcard']['card_code']; ?></th>
				<th><?php echo $lang['showexpiregiftcard']['status']; ?></th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
	<?php

		foreach($res as $row)
		{
			$id = $row['id'];
			$id_no = $row['txn_id'];
			$order_type = $row['order_type'];
			$amt = number_format($row['card_amount'], 2, '.', ',');
			$recipient_email = $row['recipient_email'];
			$to_name = $row['to_name'];
			$from_name = $row['from_name'];
			$sender = $row['sender'];
			$card_code = $row['card_code'];
			//$status = $row['status'];
			$clamied = $row['claimed'];
			$donated = $row['donated'];
			$exp_date = $row['exp_date'];
			$curr_date = time();
			$flag =0;
			$card_status ="";
			if($donated == 1)
				$card_status = "Donated";
			else if($clamied == 1)
				$card_status = "Claimed";
			
			else if($clamied == 0)
			{	
				if($exp_date < $curr_date)
				{
					$card_status = "Expired";
					$flag = 1;
					$text= "<form name='donateform".$id."' method='post' action='updateprocess.php'>
							<input type='hidden' name='card_id' id='card_id' value='$id' />
							<input type='hidden' name='card_code' id='card_code' value='$card_code' />
							<input type='hidden' name='card_amt' id='card_amt' value='$amt' />
							<input type='hidden' name='donate_card' id='donate_card' />
							<input type='hidden' name='user_guess' value='".generateToken('donate_card')."'/>
							<a href='$RequestUrl#' style='color:red' onclick='document.forms.donateform".$id.".submit()'>Donate</a>
							</form>";
				}
				else
					$card_status = "Pending";
			}
			if($order_type=='email')
			{
				$giftEmail= "<form method='post' action=''>
							<input type='hidden' name='order_id' id='order_id' value='$id_no' />
							<input type='hidden' name='id' id='id' value='$id' />
							<input type='hidden' name='resendGiftEmail' id='resendGiftEmail' />
							<input class='btn' type='submit' value='Re Send Email'>
							</form>";
			}
			else
			{
				$giftEmail= "";
			}
			echo '<tr>';
				echo "<td>$order_type</td>";
				echo "<td>$amt</td>";
				echo "<td>$recipient_email</td>";
				echo "<td>$to_name</td>";
				echo "<td>$from_name</td>";
				echo "<td>$sender</td>";
				echo "<td><a href='".SITE_URL."cardimage.php?id_no=".$id_no."&card_code=".$card_code."' target='_blank'>$card_code</a></td>";
				if($flag == 1)
					echo "<td>$card_status<br>$text</td>";
				else
					echo "<td>$card_status</td>";
				echo "<td>$giftEmail</td>";				
			echo '</tr>';
		}	?>
		</tbody>
	</table>
<?php
	if($res[0]['count']>PAGINATION)
	{
		$j=floor($res[0]['count']/PAGINATION)+1;
	}
	else
		$j=1;
	$i=floor($res[0]['count']/PAGINATION)*PAGINATION;
	$limit=floor($page/4)*4;
	$from = $rowcount + 1;
	$to=$rowcount+PAGINATION;
	$to=($to < $res[0]['count'])? $to : $res[0]['count'];
	$rows = $rowcount +PAGINATION;
	if($rows <= $res[0]['count'] )
		$rows =  $rowcount +PAGINATION;
	else $rows =  $res[0]['count'];

	if(isset($rows))
	{
	?>
	<div align="center">
	Total Record <?php echo $res[0]['count'] ?>&nbsp;&nbsp;&nbsp;&nbsp;

	<a href="javascript: void(0);" onClick="sub(0,1);">First</a>&nbsp;
	<a href="javascript: void(0);" onClick="sub(<?php echo $rowcount-PAGINATION; ?>,<?php echo ($page-1); ?>);">Previous</a>&nbsp;
	<?php
		for($m=$limit; ($m<($limit+4) && $m<$j); $m++ )
		{ ?>
			<a href="javascript: void(0);" onClick="sub(<?php echo ($m*PAGINATION); ?>,<?php echo ($m+1); ?>);"><?php echo ($m+1); ?></a>
	<?php
		} ?>
		&nbsp;<a href="javascript: void(0);" onClick="sub(<?php echo $rowcount+PAGINATION; ?>,<?php echo ($page+1); ?>);">Next</a>
		&nbsp;<a href="javascript: void(0);" onClick="sub(<?php echo $i; ?>,<?php echo $j; ?>);">Last</a>
		&nbsp;&nbsp;&nbsp;&nbsp;Page <?php echo ($rowcount/PAGINATION+1); ?>
		&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $from; ?> To <?php echo $to; ?>
	</div>
	<?php
	}
	?>
	<script language="javascript">
	function sub(a, b)
	{

		if(a>=0 && a <= <?php echo $res[0]['count'] ?>)
		{
			window.location = "index.php?p=29&r="+a+"&pg="+b;
		}

	}
	</script>
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