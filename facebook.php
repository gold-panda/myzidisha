<?php
include_once("library/session.php");
include_once("editables/loaners.php");
$path=	getEditablePath('home.php');
include_once("editables/".$path);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Zidisha</title>
<script type="text/javascript" src="includes/scripts/jquery.js"></script>
<script type="text/javascript" src="includes/scripts/dropdowncontent.js"></script>
<script type="text/javascript" src="includes/scripts/jquery.tablesorter.js"></script>
<link href="includes/scripts/commom.css" rel="stylesheet" type="text/css" />
<link href="css/default/profile_style.css" rel="stylesheet" type="text/css" media="screen" />
<link href="css/default/comment.css" rel="stylesheet" type="text/css" media="screen" />
<link href="css/default/tablecss.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript">
$(document).ready(function()
{
	$("div#button").mouseover(function(){
		$(this).css({'color':'#000000', 'font-weight':'bolder'});});
	$("div#button").mouseout(function(){
		$(this).css({'color':'#000000', 'font-weight':'normal'});});
});
</script>
</head>
<body class="body">
	<div style='width:510px; height:710px'>
<?php
		echo $lang['home']['desc1'];
		$openloan=$database->getRandomOpenBorrower();
		if(!empty($openloan))
		{
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
			$totBid=$database->getTotalBid($borrowerid,$loanid);
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
				$photo='library/getimage.php?id='.$userid.'&width=250&height=250';
			}
			$statusbar=$session->getStatusBar($userid,$loanid);
			$amount=number_format($amount, 0, ".", ",");
			$button='button';
			$buttontext="Lend";
		?>
			<table border=0 class="lendertable" width=100%>
				<tr>
					<td>
						<div style="width:100%">
							<table class="lendertable" cellspacing="3">
							<tr>
								<td width='50%' valign='top'><a href='index.php?p=14&u=<?php echo $userid ?>&l=<?php echo $loanid ?>' margin-left='5'><img src=<?php echo $photo?> style='border:#cccccc 1px ridge' /></a></td>
								<td width='50%' valign='top'>
									<div style='margin-left:5px'><b><?php echo $lang['home']['summary_box_title'] ?></b><br/><br/><a href='index.php?p=14&u=<?php echo $userid ?>&l=<?php echo $loanid?>'><?php echo $name ?></a><br /><?php echo  number_format($f)."% Positive (<a href='index.php?p=12&u=".$userid."&fdb=2'>".$cf."</a>)<br />".$city.", ".$country ?><br /><br />
						<?php
										if(strlen($loanuse) >200)
											echo substr($loanuse,0,200).".... <a href='index.php?p=14&u=".$userid."&l=".$loanid."'>(read more)</a>";
										else
											echo $loanuse;
						?>
									</div><br/>
									<div id='<?php echo $button ?>' style='margin-left:50px;width:50px'><a href='index.php?p=14&u=<?php echo $userid ?>&l=<?php echo $loanid ?>#e5' style='text-decoration:none;color:black'><?php echo $buttontext ?></a></div><br />
									<div style='margin-left:5px'><b>Amount Requested:</b> <?php echo  $amount ?> USD<br /><b>Interest:</b> <?php echo number_format($interest, 2, '.', ',') ?>%<br /><br />
										<table width=80%>
											<tr><td><?php echo $statusbar ?></td></tr>
										</table>
									</div>
								</td>
							</tr>
							</table>
						</div>
					</td>
				</tr>
			</table>
<?php	}
		$random=$database->getRandomCommentAndUserName();
		if(!empty($random))
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

			$name12=$database->getNameById($borrowerid);
			$files =$database->getCommentFile($userid, $borrowerid, $messageid);
			$loanid = $database->getUNClosedLoanid($borrowerid);
			$user_citycountry=$database->getUserCityCountry($userid);
			$u_city=$user_citycountry['City'];
			$u_country=$user_citycountry['Country'];
			$url = "index.php?p=12&u=".$borrowerid;
			if($loanid != 0)
				$url = "index.php?p=14&u=".$borrowerid."&l=".$loanid;

?>
			<table border=0 class="lendertable" width=100%>
				<tr>
					<td>
						<table border=0 class="lendertable_o" width=100%>
				   			<tr>
								<td  width=50% valign='top'>
					<?php			if(!isset($files) || count($files) == 0)
									{	?>
										<a href='<?php echo $url ?>'> <img src="library/getimage.php?id=<?php echo $borrowerid;?>&width=250&height=250" style='border:#cccccc 1px ridge'> </a>
				   <?php			}
									else
									{
										foreach($files as $row)
										{
											echo "<a href='".$url."'><img src='includes/getcommentupload.php?p=61&imgid=".$row['uploadfile']."&width=250&height=250' /></a>";
											break;
										}
									}
					?>
									<br/><a href='<?php echo $url ?>'><?php echo $name12 ?></a>
								</td>
								<td valign='top'>
									<b><?php echo $lang['home']['comment_box_title'] ?></b><br/><br/>Posted by&nbsp;<b><?php echo $name13;?></b>&nbsp;in &nbsp;<b><?php echo $u_city.' '.$database->mysetCountry($u_country); ?></b>&nbsp;  on <?php echo date("d M Y", $date);?><br/><br/>
					<?php
									if(strlen($usermessage) >300)
										echo substr($usermessage,0,300).".... <a href='".$url."'>(read more)</a>";
									else
										echo $usermessage;
					 ?>
									<br/>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
<?php	}	?>
	</div>
</body>
</html>