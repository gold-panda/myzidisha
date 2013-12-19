<script type="text/javascript" src="includes/scripts/expand.js"></script>
<style type="text/css">
a:hover, a:active, a:focus {
  border-style:solid;
  background-color:#f0f0f0;
  outline:0 none
}
 .expand a.open:link, .expand a.open:visited {
  border-style:solid;
  background:#eee url(img/arrow-up.gif) no-repeat 98% 50%
}
</style>
<script type="text/javascript">
<!--
	$(function() {
    // --- first section initially expanded:
    $(".expand").toggler();
    // --- Other options:
    //$("h4.expand").toggler({method: "toggle", speed: 0});
    //$("h4.expand").toggler({method: "toggle"});
    //$("h4.expand").toggler({speed: "fast"});
    //$("h4.expand").toggler({method: "fadeToggle"});
    //$("h4.expand").toggler({method: "slideFadeToggle"});
    $("#content").expandAll({trigger: "h4.expand", ref: "div.demo",  speed: 300, oneSwitch: false});
});
//-->
</script>
<?php
require_once("library/session.php");
include_once("./editables/loginform.php");
$path=	getEditablePath('loginform.php');
include_once("./editables/".$path);
$part=0;//sets the default part for the login to either login table or profile links
			//chnge to check for if(logged_in)
if($session->logged_in)
{
	$userid=$session->userid;
	if($session->userlevel==PARTNER_LEVEL)
	{
?>
		<h2><?php echo $lang['loginform']['my_account'];?></h2>
		<p><strong><a href="index.php?p=12&u=<?php echo $userid;?>"><?php echo $lang['loginform']['profile'] ?></a></p>
		<p><a href="index.php?p=13"><?php echo $lang['loginform']['eprofile'] ?></a></p>
		<p><a href="index.php?p=7"><?php echo $lang['loginform']['search_b'] ?></a></p>
		<p><a href="index.php?p=8"><?php echo $lang['loginform']['act_borrower'] ?></a></strong></p>
		<!--<p><a href="process.php"><?php echo $lang['loginform']['Logout'] ?></a></p>-->
		<p>&nbsp;</p>

		<h2><?php echo $lang['loginform']['mystatistics'] ?></h2>
		<p><strong><span class="blue"><?php echo $lang['loginform']['active_status'] ?>:</span>
		<?php
			$active=$database->getPartnerStatus($session->userid);
			if($active==0){
				echo $lang['loginform']['Inactive'];
			}
			else if($active==1){
				echo $lang['loginform']['Active'];
			}
		?>
		</p>
		<p><span class="blue"><?php echo $lang['loginform']['sponsored_bus'] ?>:</span> <?php echo $database->getActiveBCount($session->userid);?></strong></p>
		<p>&nbsp;</p>
<?php
	}
	if($session->userlevel==LENDER_LEVEL)
	{
		$active_investamt=$database->amountInActiveBids($session->userid);
?>
		<h2><?php echo $lang['loginform']['my_account'];?></h2>
		<p><strong><a href="index.php?p=12&u=<?php echo $userid;?>"><?php echo $lang['loginform']['profile'] ?></a></p>
		<p><a href="index.php?p=13"><?php echo $lang['loginform']['eprofile'] ?></a></p>
		<p><a href="index.php?p=19&u=<?php echo $session->userid;?>"><?php echo $lang['loginform']['myportfolio'] ?></a></p>
		<p><a href="index.php?p=16&u=<?php echo $session->userid;?>"><?php echo $lang['loginform']['tran_hist'] ?></a></p>
		<p><a href="index.php?p=17"><?php echo $lang['loginform']['lender_withdraw'] ?></a></p>
		<p><a href="index.php?p=30"><?php echo $lang['loginform']['invite_frnds'] ?></a></strong></p>
		<!--<p><a href="process.php"><?php echo $lang['loginform']['Logout'] ?></a></p>-->
		<p>&nbsp;</p>

		<h2><?php echo $lang['loginform']['mystatistics'] ?></h2>
		<p><strong><span class="blue"><?php echo $lang['loginform']['amount_invested'] ?>:</span> USD <?php echo  number_format($database->totalAmountLend($userid), 2, '.', ','); ?></p>
		<p><span class="blue"><?php echo $lang['loginform']['sponsored_bus'] ?>:</span> <?php echo $database->businessFinanced($session->userid);?></p>
		<p><span class="blue"><?php echo $lang['loginform']['amt_active_bid'] ?>:</span> USD <?php echo  number_format($active_investamt, 2, '.', ','); ?></p>
		<p><span class="blue"><?php echo $lang['loginform']['total_avl_amt'] ?>:</span> USD <?php echo  number_format(truncate_num($session->amountToUseForBid($userid),2), 2, '.', ','); ?></strong></p>
		<p>&nbsp;</p>
<?php
	}
	if($session->userlevel==BORROWER_LEVEL)
	{
		$row = $database->getBorrowerDetails($userid);
?>
		<h2><?php echo $lang['loginform']['my_account'];?></h2>
		<p><strong><a href="index.php?p=50"><?php echo $lang['loginform']['welcome_page'] ?></a></strong></p>		
		<?php if($row['activeLoanID'] != 0) {?>
		<p><strong><a href="index.php?p=14&u=<?php echo $userid;?>&l=<?php echo $row['activeLoanID']; ?>"><?php echo $lang['loginform']['view_crnt_loan'] ?></a></strong></p>
		<?php } ?>
		<!-- <p><a href="index.php?p=12&u=<?php echo $userid;?>"><?php echo $lang['loginform']['profile'] ?></a></strong></p> -->
		<p><strong><a href="index.php?p=13"><?php echo $lang['loginform']['eprofile'] ?></a></strong></p>
		<p><strong><a href="index.php?p=37&l=<?php echo $row['activeLoanID']; ?>"><?php echo $lang['loginform']['ac_detail'] ?></a></strong></p>
<?php	$loan_status=$database->getBorrowerCurrentLoanStatus($userid);
		if($loan_status==LOAN_ACTIVE)
		{
			$status=$database->canBorrowerReSchedule($userid,$row['activeLoanID']);
			if($status)
			{	?>
				<p><strong><a href="index.php?p=41&l=<?php echo $row['activeLoanID']; ?>"><?php echo $lang['loginform']['reschedule'];?></a></strong></p>
<?php		}
		}	?>
<?php	$referDetail=$database->getReferrals($row['Country'], false);
		if(!empty($referDetail))
		{	?>
				<p><strong><a href="index.php?p=49"><?php echo $lang['loginform']['referral_program'] ?></a></strong></p>
<?php	}	?>
		<p><strong><a href="index.php?p=71&u=<?php echo $userid;?>"><?php echo $lang['loginform']['b_repayment_instructions'] ?></a></strong></p>
		<!--<p><strong><a href="process.php"><?php echo $lang['loginform']['Logout'] ?></a></strong></p>-->
		<p>&nbsp;</p>
<?php
	}
	if($session->userlevel==ADMIN_LEVEL)
	{
?>
		<h2><?php echo $lang['loginform']['Administrator'];?></h2>
		<div style="height:300px;overflow:auto">
			<div class="demo">
				<h3 class="expand">Manage Borrowers</h3>
				<div class="collapse">
				   <strong> 
				   <p><a href="index.php?p=7"><?php echo $lang['loginform']['search_b'];?></a></p>
				   <p><a href="index.php?p=8"><?php echo $lang['loginform']['act_borrower'];?></a></p>
				   <p><a href="index.php?p=11&a=1"><?php echo $lang['loginform']['view_b'];?></a></p>
				   <p><a href="index.php?p=11&a=12"><?php echo $lang['loginform']['activate_deactivate_loan'];?></a></p>
				   <p><a href="index.php?p=49"><?php echo $lang['loginform']['b_referral'];?></a></p>
				   <p><a href="index.php?p=45"><?php echo $lang['loginform']['rescheduled_loans'];?></a></p>
				   <p><a href="index.php?p=11&a=13"><?php echo $lang['loginform']['b_repayment_instructions'] ?></a></p>
				   </strong>
				</div>
			</div>
			<div style='margin-top:10px;' class="demo">
				<h3 class="expand">Manage Lenders</h3>
					<div class="collapse">
						<strong><p><a href="index.php?p=11&a=3"><?php echo $lang['loginform']['view_l'];?></a></p>
							<p><a href="index.php?p=60"><?php /*if(PAYPAL_PERSONAL)*/ echo "Enter Lender Payments" ?></a></p>
							<p><a href="index.php?p=17"><?php echo $lang['loginform']['admin_withdraw'];?></a></p>
							<p><a href="index.php?p=63"><?php echo $lang['loginform']['nr'];?></a></p>
							<p><a href="index.php?p=29"><?php echo $lang['loginform']['mng_exp_card'];?></a></p>
						</strong>
				  </div>
			 </div>
			 <div style='margin-top:10px;' class="demo">
				<h3 class="expand">Translation</h3>
					<div class="collapse">
						<strong>
							<p><a href="index.php?p=25"><?php echo $lang['loginform']['mngtran'];?></a></p>
							<p><a href="index.php?p=32&ref=2"><?php echo $lang['loginform']['upload_files'];?></a></p>
							<p><a href="index.php?p=32&ref=1"><?php echo $lang['loginform']['trans_label'];?></a></p>
							<p><a href="index.php?p=32&ref=3"><?php echo $lang['loginform']['download_files'];?></a></p>
							<p><a href="index.php?p=35"><?php echo $lang['loginform']['manage_lang'];?></a></p>
						</strong>
				  </div>
			 </div>
			 <div style='margin-top :10px;' class="demo">
				<h3 class="expand">Website Settings</h3>
					<div class="collapse">
						<strong>
							<p><a href="index.php?p=11&a=11"><?php echo $lang['loginform']['addCurrency'];?></a></p>
							<p><a href="index.php?p=11&a=4"><?php echo $lang['loginform']['ex_rate'];?></a></p>
							<p><a href="index.php?p=11&a=10"><?php echo $lang['loginform']['Settings'];?></a></p>
							<p><a href="index.php?p=36"><?php echo $lang['loginform']['extra'];?></a></p>
						</strong>
				  </div>
			 </div>
			 <div style='margin-top:10px;' class="demo">
				<h3 class="expand">Reports</h3>
					<div class="collapse">
						<strong>
							<p><a href="index.php?p=22"><?php echo $lang['loginform']['tranhist'];?></a></p>
							<p><a href="index.php?p=31"><?php echo $lang['loginform']['repay_report'];?></a></p>
							<p><a href="index.php?p=23&v=1"><?php echo $lang['loginform']['pfreport'];?></a></p>
						</strong>
				  </div>
			 </div>
			<p><strong>
			<!--<a href="index.php?p=12&u=<?php echo $userid;?>"><?php echo $lang['loginform']['profile'] ?></a></p>
			<p><a href="index.php?p=11"><?php echo $lang['loginform']['Administration_Home'];?></a></p> -->
			<!--<p><a href="process.php"><?php echo $lang['loginform']['Logout'];?></a></p>-->

			<p><a href="index.php?p=11&a=2"><?php echo $lang['loginform']['view_p'];?></a></p>
			<p><a href="index.php?p=20"><?php echo $lang['loginform']['send_email'];?></a></p>
			<p><a href="index.php?p=39"><?php echo $lang['loginform']['change_password'];?></a></strong></p>
			<strong>
			<p><a href="index.php?p=21"><?php echo $lang['loginform']['registration_fee'];?></a></p>
			<!-- <p><a href="index.php?p=16"><?php echo $lang['loginform']['ac_detail'];?></a></p> -->
			<p><a href="index.php?p=53"><?php echo $lang['loginform']['lender_credit'] ?></a></p>
			<p><a href="index.php?p=54"><?php echo $lang['loginform']['referal_code'];?></a></p>
		</div>
		<h2><?php echo $lang['loginform']['partner_details'] ?></h2>
		<p><strong><span class="blue"><?php echo $lang['loginform']['pend_ptr'] ?>:</span> <?php echo $database->pendingPartners();?></p>
		<p><span class="blue"><?php echo $lang['loginform']['total_ptr'] ?>:</span> <?php echo $database->totalPartners();?></strong></p>
		<p>&nbsp;</p>

<?php
	}
}
?>