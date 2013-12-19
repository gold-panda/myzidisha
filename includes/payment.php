<?php
include_once("library/session.php");
include_once("./editables/admin.php");
include_once("./editables/payment.php");
$path=	getEditablePath('payment.php');
include_once("editables/".$path);
date_default_timezone_set ('EST');
if($session->userlevel==LENDER_LEVEL)
{
	$userid1=$session->userid;
	$res=$database->isTranslator($userid1);
	if($res==1)

		$isvolunteer=1;
} 


if($isvolunteer==1 || $session->userlevel==ADMIN_LEVEL)
 {
	$userid = $_GET['u'];
}
elseif($session->userlevel  == LENDER_LEVEL) {
	$userid = $session->userid;
}
?>
<SCRIPT src="includes/scripts/paging.js?q=<?php echo RANDOM_NUMBER ?>" type="text/javascript"></SCRIPT>
<div class='span12'>
<?php
// allow admin to view transaction history as logged-in Lender can view.
	if($session->userlevel  == LENDER_LEVEL || $session->userlevel  == ADMIN_LEVEL)
	{
		$amtUseforbid = round($session->amountToUseForBid($userid), 4);
			$amtincart = $database->getAmtinLendingcart($userid);
//added by Julia 27-10-2013
			if(isset($_SESSION['Nodonationincart'])) {
				$availAmt = $amtUseforbid - $amtincart['amt'];
			}else {
				$availAmt = $amtUseforbid - $amtincart['amt'] - $amtincart['donation'];
			}
			$creditincart = $amtUseforbid - $availAmt;
			if($availAmt<0) {
				$availAmt = 0;
			}
		$investAmtDisplay = $database->amountInActiveBidsDisplay($userid);
		$availAmt = truncate_num(round($availAmt, 4),2);
		if(isset($_GET["ord"]))
			$ord=$_GET["ord"];
		else
			$ord="DESC";
		$image = "images/layout/table_show/asc.gif";
		$amt = $database->getTransaction($userid,0);
		$dollarAmt = $database->getTransactionNew($userid);
		if($ord=='ASC')
		{
			$dollarAmt = array_reverse($dollarAmt);
			$image = "images/layout/table_show/desc.gif";
		}
		$count = count($dollarAmt);
	?>
		<div align='left' class='static'><h1><?php echo $lang['payment']['tran_hist'] ?></h1></div>
		<p><?php echo $lang['payment']['total_avl_amt'].": <a href='index.php?p=19&u=".$userid."'>USD ".number_format($availAmt, 2, ".", ",")."</a>"; ?></p>

<!-- added by Julia 27-10-2013 -->
				<tr>
				<?php if($creditincart>0){
					?><tr style="height:6px"><td></td></tr>
					<?php echo "<td width='250px'>Credit In lending cart: </td><td>USD ".number_format($creditincart, 2, ".", ",")."</td><br/><br/>";
					} 
				?>
				</tr>
				

		<p><tr style="height:6px"><?php echo $lang['payment']['amt_invested'].": <a href='index.php?p=19&u=".$userid."'>USD ".number_format($investAmtDisplay, 2, ".", ",")."</a>"; ?></p>
<?php	if($dollarAmt)
		{	?>

			<table id="transtable" class="zebra-striped">
				<thead>
				<tr>
					<th width='24%' align='left' onClick="tablesort()" style="cursor:pointer"><?php echo $lang['payment']['tr-date']; ?><img src='<?php echo $image;?>'/></th>
					<!-- <th width='24%' align='left'><?php echo $lang['payment']['tr-date']; ?></th> -->
					<th width='45%' align='center'><?php echo $lang['payment']['tr_descp'];?></th>
					<th width='13%' align='center'><?php echo $lang['payment']['Amount'];?> (USD)</th>
					<th width='13%' align='center'><?php echo $lang['payment']['bal'];?> (USD)</th>
				</tr>
				</thead>
				<tbody>
		<?php		$baln = $availAmt;
					foreach( $dollarAmt as $rows )
					{	
						echo "<tr>";
						$date=$rows['TrDate'];
						echo "<td>".date('M d, Y', $date)."</td>";
						$borrower_id = $database->getBorrowerId($rows['loanid']);
						$borrower_name = $database->getNameById($borrower_id);
						if($rows['txn_type']==LOAN_BACK_LENDER) {
							if($rows['txn_sub_type']==REFERRAL_CREDIT) {
								$rows['txn_desc'] = $lang['payment']['referral_credit_frm'].$borrower_name;
							} else {
								$rows['txn_desc'] = $lang['payment']['repy_rcvd_frm'].$borrower_name;
							}
						} else if($rows['txn_type']==LOAN_BID) {
							if($rows['autoid']!=null){
								$rows['txn_desc'] = "Automated bid on loan for ".$borrower_name."";
							}else {
								$rows['txn_desc'] = "Bid on loan for ".$borrower_name."";
							}
						} else if($rows['txn_type']==LOAN_OUTBID) {
							if($rows['txn_sub_type']==LOAN_BID_EXPIRED) {
								$rows['txn_desc'] = "Credit back: expired loan bid for ".$borrower_name;
							} else if($rows['txn_sub_type']==LOAN_BID_CANCELED) {
								$rows['txn_desc'] = "Credit back: cancelled loan for ".$borrower_name;
							} else {
								$rows['txn_desc'] = "Credit back from bid on loan for ".$borrower_name;
							}
						}
						if($rows['loanid'] == 0) {
							echo "<td>".$rows['txn_desc']."</td>";
						}
						else {
							$loanprofileurl = getLoanprofileUrl($borrower_id,$rows['loanid']);
							echo "<td><a href='".$loanprofileurl."'>".$rows['txn_desc']."</a></td>";
						}
						echo "<td>".number_format(truncate_num(round($rows['amount'], 4),2), 2, ".", ",")."</td>";
						echo "<td>".number_format(truncate_num(round($rows['bal'], 4), 2), 2, ".", ",")."</td>";
						echo "</tr>";
					}	?>
				</tbody>
			</table>
			<div id="pageNavPosition" align='center'></div>
			<div align='right'><font color='blue'>Total Records  <?php echo $count; ?></font></div>
			<p>&nbsp;</p>
			<script type="text/javascript">
				var pager = new Pager('transtable', 100);
				pager.init();
				pager.showPageNav('pager', 'pageNavPosition');
				pager.showPage(1);
			</script>
<?php	}	?>
		<script language="javascript">
		function tablesort()
		{
			if('ASC'=='<?php echo $ord; ?>')
			{
				window.location = 'index.php?p=16&u=<?php echo $userid ?>&ord=DESC'
			}
			else
			{
				window.location = 'index.php?p=16&u=<?php echo $userid ?>&ord=ASC'
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
		echo " <a href='index.php'>click here</a>".$lang['admin']['for_more']. "<br />";
		echo "</div>";
	}
?>
</div>