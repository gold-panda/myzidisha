<?php
include_once("./editables/loginform.php");
$path=	getEditablePath('loginform.php');
include_once("./editables/".$path);
if($session->userlevel==ADMIN_LEVEL){
?>
	<div class='span12'>
	<div><div style="float:left"><div align='left' class='static'><h1>Admin Settings</h1></div></div>
	<div style="clear:both"></div></div>
	<div class="collapse small_link_col">
		<ul>
			<li><a href="index.php?p=11&a=11"><?php echo $lang['loginform']['addCurrency'];?></a></li>
			<li><a href="index.php?p=11&a=4"><?php echo $lang['loginform']['ex_rate'];?></a></li>
			<li><a href="index.php?p=77"><?php echo $lang['loginform']['commentscredit'] ?></a></li>
			<li><a href="index.php?p=21"><?php echo $lang['loginform']['registration_fee'];?></a></li>
			<li><a href="index.php?p=11&a=13"><?php echo $lang['loginform']['b_repayment_instructions'] ?></a></li>
			<li><a href="index.php?p=11&a=10"><?php echo $lang['loginform']['Settings'];?></a></li>
			<li><a href="index.php?p=11&a=2"><?php echo $lang['loginform']['view_p'];?></a></li>
			<li><a href="index.php?p=20"><?php echo $lang['loginform']['send_email'];?></a></li>
			<li><a href="index.php?p=89"><?php echo $lang['loginform']['brwr_emails'];?></a></li>
			<li><a href="index.php?p=90"><?php echo $lang['loginform']['lender_emails'];?></a></li>
			<li><a href="index.php?p=98"><?php echo $lang['loginform']['FB_links'];?></a></li>
			<li><a href="index.php?p=85"><?php echo $lang['loginform']['pendingemail'] ?></a></li>
			<li><a href="index.php?p=94"><?php echo $lang['loginform']['pendingendorser'] ?></a></li>
			<li><a href="index.php?p=8&type=1&ord=ASC"><?php echo $lang['loginform']['act_borrower'];?></a></li>
			<li><a href="index.php?p=87"><?php echo $lang['loginform']['decliened_borrower'];?></a></li>
			<li><a href="index.php?p=95"><?php echo $lang['loginform']['endorser'];?></a></li>
			<li><a href="index.php?p=60"><?php /*if(PAYPAL_PERSONAL)*/ echo "Enter Lender Payments" ?></a></li>
			<li><a href="index.php?p=54"><?php echo $lang['loginform']['referal_code'];?></a></li>

		</ul>
	</div>
	</div>
<?php
}?>