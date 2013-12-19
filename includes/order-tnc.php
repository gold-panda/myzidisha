<?php
include_once("./editables/order-tnc.php");
$path=	getEditablePath('order-tnc.php');
include_once("editables/".$path);
$paypalTranFeeOrg= $database->getAdminSetting('PaypalTransaction');
?>
<script type="text/javascript" src="includes/scripts/giftajax.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<script src="includes/scripts/jquery.custom.min.js" type="text/javascript"></script>
<div class="span12">
	<h4><?php echo $lang['order-tnc']['gift_tnc_heading']; ?>:</h4>
	<ol>
		<li><p><?php echo $lang['order-tnc']['gift_tnc_1']; ?></p></li>
		<li><p><?php echo $lang['order-tnc']['gift_tnc_2']; ?></p></li>
		<li><p><?php echo $lang['order-tnc']['gift_tnc_3']; ?></p></li>
		<li><p><?php echo $lang['order-tnc']['gift_tnc_4']; ?></p></li>
	</ol> 
	<?php 
		$Lendingcart = $database->getLendingCart(); 
		$userid=$session->userid;
	//	if($userid || !empty($Lendingcart)) {
			$postform = 'index.php?p=75';
	//	}else {
	//		$postform = 'index.php?p=46';
	//	}
	?>
	<form name='ordertnc' id='ordertnc' action="<?php echo $postform?>" method='post'>
		<input type="checkbox" name="tnc" id="tnc" value="" /><label for="tnc" style="width:200px;float:none;"><?php echo $lang['order-tnc']['agree_tnc'] ?></label><br><br>
		<input type='hidden' name='giftordertnc' id='giftordertnc' value=''>
		<div align='center'><input class='btn' type='submit' name='submit' value='Continue' onClick='return tncCheck()'></div>
	</form>
</div>