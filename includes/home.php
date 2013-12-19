<?php
include_once("library/session.php");
include_once("./editables/loaners.php");
include_once("./editables/home.php");
$path=	getEditablePath('home.php');
include_once("editables/".$path);
?>
<div class="span4">
	<?php include_once("includes/stats.php"); ?>
</div>
<div style="float:right">
	<div class="span6">
	<?php
	$openloans=$database->getRandomOpenBorrower();
	$marginConnectZidisha=0;
	if(!empty($openloans))
	{	?>
		<h2><?php echo $lang['home']['spotlight_loan'] ?></h2>
	<?php
		$i=1;
		$noOfLoans = 0;
		foreach($openloans as $openloan)
		{
			if($noOfLoans >=2)
				break;
			$noOfLoans++;
			$userid=$openloan['userid'];
			$name=$openloan['FirstName'].' '.$openloan['LastName'];
			$city=$openloan['City'];
			$country=$database->mysetCountry($openloan['Country']);
			$amount=$openloan['reqdamt'];
			if($openloan['tr_loanuse']==null || $openloan['tr_loanuse']=="")
				$loanuse=$openloan['loanuse'];
			else
				$loanuse=$openloan['tr_loanuse'];
			$photo=$openloan['Photo'];
			$loanid = $openloan['loanid'];
			$interest=$database->getAvgBidInterest($userid, $loanid);
			$totBid=$database->getTotalBid($userid,$loanid);
			$ramount=number_format($openloan['reqdamt'], 0, "", "");

			if($totBid < $ramount)
			{
				$interest=$openloan['interest'] - $openloan['WebFee'];
			}
			$report=$database->loanReport($userid);
			$f=$report['feedback'];
			$cf=$report['Totalfeedback'];

			if(!$photo || $photo==NULL)
			{
				$photo='library/getimage.php?id='.$userid.'&amp;width=200&amp;height=200';
			}
			$statusbar=$session->getStatusBar($userid,$loanid);
			$amount=number_format($amount, 0, ".", ",");
			$button='button';
			$buttontext="Lend";
			$loanprurl = getLoanprofileUrl($userid,$loanid);
		?>	
			<div style="float:left;padding-right:10px;"><a href="<?php echo $loanprurl?>"><img src="<?php echo $photo?>" alt="<?php echo $name?>" /></a></div>
			<div style="float:left;max-width:130px">
				<h4 style="margin-top:-6px;margin-bottom:0px;"><?php echo $name ?></h4>
				<p style="margin-bottom:5px;"><?php echo $city.", ".$country ?></p>
				<p>
		<?php		if(strlen($loanuse) >100)
						echo substr($loanuse,0,100)." <a href='$loanprurl'>Read More</a>";
					else
						echo $loanuse." <a href='$loanprurl'>Read More</a>";
					?>
				</p>
			</div>
			<div style="clear:both"></div>
			<div style="float:left;padding-top:5px">
				<p><strong><?php echo $lang['home']['amount_requested'] ?>:</strong> <?php echo  $amount ?> USD<br />
				<strong><?php echo $lang['home']['interest'] ?>:</strong> <?php echo number_format($interest, 2, '.', ',') ?>%</p>
			</div>
			<div style="float:right;padding-right:30px;padding-top:10px">
				<!-- REMOVED BY REQUEST.. DANIEL -->
				<!-- <p><a class="btn" href="<?php echo $loanprurl?>"><?php echo $lang['home']['Lend'] ?></a></p> -->
			</div>
			<div style="clear:both"></div>
			<?php echo $statusbar ?>
	<?php	if($i==1)
				echo "<p>&nbsp;</p><hr/>";
			$i++;
		} 
		if($i==3)
			$marginConnectZidisha=-205;	
	}
	else
	{
		echo "<br/>";
	}	?>
	</div>
	<div class="span6">
<?php
	$randomLoans=$database->getRandomCommentAndUserName();
	if(!empty($randomLoans))
	{	?>
		
		<h2><?php echo $lang['home']['progress_report'];?></h2>
<?php
		foreach($randomLoans as $random)
		{
			$userid=$random['senderid'];
			$borrowerid=$random['receiverid'];
			if($random['tr_message']==null || $random['message']=="")
				$usermessage=$random['message'];
			else
				$usermessage=$random['tr_message'];
			$messageid = $random['id'];
			$date = $random['pub_date'];
			$level =$database->getUserLevelbyid($userid);
			if($level==BORROWER_LEVEL || $level==PARTNER_LEVEL)
				$name13=$database->getNameById($userid);
			else
				$name13=$database->getUserNameById($userid);

			//$name12=$database->getNameById($borrowerid);
			//$files =$database->getCommentFile($userid, $borrowerid, $messageid);
			$loanid = $database->getUNClosedLoanid($borrowerid);
			$user_citycountry=$database->getUserCityCountry($userid);
			$u_city=$user_citycountry['City'];
			$u_country=$user_citycountry['Country'];
			$u_country=$database->mysetCountry($u_country);
			$prurl = getUserProfileUrl($borrowerid);
			$url = $prurl;
			$loanprurl = getLoanprofileUrl($borrowerid,$loanid);
			if($loanid != 0)
				$url = $loanprurl;
		?>
			<p>
	<?php	if(strlen($usermessage) >300)
			{
				$shortMsg=substr($usermessage,0,300);
				$pos1= strrpos ($shortMsg , ' ');
				if($pos1 !==false)
					$shortMsg=substr($shortMsg,0,$pos1);
				echo "\"".$shortMsg."....\" <a href='".$url."'>Read More</a>";
			}
			else
				echo "\"".$usermessage."\"";
	?>		</p>
			<p class="meta"><?php echo $lang['home']['posted_by'] ?> <strong><?php echo $name13;?></strong> <?php echo $lang['home']['in'] ?> <strong><?php echo $u_city.", ".$u_country;?></strong> <?php echo $lang['home']['on'] ?> <?php echo date("d M Y", $date);?></p>
<?php	}	?>
		<p><span class="blue"><a href="microfinance/testimonials.html"><?php echo $lang['home']['more_testi'];?></a></span></p>
	<?php
	}
	else
	{
		echo "<br/>";
	}
?>
	</div>
	<div style="clear:both"></div>
</div>
<div style="clear:both"></div>
<div id="zidisha-connect" style="margin-top:<?php echo $marginConnectZidisha;?>px">
	<img alt="Review ZIDISHA INC. on Great Nonprofits" title="Review ZIDISHA INC. on Great Nonprofits" src="images/layout/logo/zidisha-simple.png" usemap="#ZidishaNonProfitBadgeMap"/>
	<map name="ZidishaNonProfitBadgeMap" id="ZidishaNonProfitBadgeMap">
		  <area shape="rect" coords="17,107,111,117" target='_blank' alt="Sun" href="http://greatnonprofits.org/reviews/zidisha-inc/?badge=1" />
		  <area shape="rect" coords="31,121,92,130" target='_blank' alt="Mercury" href="http://greatnonprofits.org/reviews/zidisha-inc/?badge=1" />
		  <area shape="rect" coords="26,135,97,144" target='_blank' alt="Venus" href="http://greatnonprofits.org/reviews/write/zidisha-inc/?badge=1" />
	</map>
</div>
