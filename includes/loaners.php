<?php
include_once("library/session.php");
include_once("./editables/loaners.php");
// please do not uncomment following line now we do not expire loan automatically
//$session->processOldLoans();
$c=20;
$t=1;
//modified by Julia to sort in order of repayment rate 15-10-2013
$sort=7;
$row1=0;
$page=1;
$searchLoan ="Search Loans";
if(isset($_GET['row'])){
	$row1=$_GET['row'];
}
if(isset($_GET['pg'])){
	$page=$_GET['pg'];
}
if(isset($_GET['t'])){
	$t=$_GET['t'];
}
if(isset($_GET['s'])){
	$sort=$_GET['s'];
}
if(isset($_GET['key'])){
	$searchLoan=$_GET['key'];
}
?>
<script type="text/javascript">
function mySubmitform(frm){
	frm.submit();
}
$(document).ready(function() {
	$('.search-input').each(function() {
		var default_value = 'Search Loans';
		$(this).focus(function() {
			if(this.value == default_value) {
				this.value = '';
				 $(this).css("font-style","normal");
			}
		});
		$(this).blur(function() {
			if(this.value == '') {
				this.value = default_value;
				 $(this).css("font-style","italic");
			}
		});
	});
});
</script>

<div class="span12">
<?php
$randomLoans='';
if(isset($_SESSION['randomLoans']))
{
	$randomLoans= $_SESSION['randomLoans'];
	unset($_SESSION['randomLoans']);
}
if($searchLoan=="Search Loans")
{
	$searchLoan='';
}
$openloans=$database->getOpenBorrowers($row1, $c, $t, $sort,$searchLoan, $randomLoans);
if($searchLoan=="")
{
	$searchLoan='Search Loans';
}
$isAnyLoanIsNonFunded=$database->isAnyLoanIsNonFunded();
if(empty($openloans)){
	$showingRes=0;
	$totalRes= 0;
}
else{
	$showingRes =count($openloans);
	$totalRes= $openloans[0]['count'];
}
if(!$isAnyLoanIsNonFunded)
{
	echo $lang['loaners']['newtext'];
?>
	<br/><br/><br/>
	<form method="post" action="updateprocess.php">
		<table class="detail">
			<tbody>
  <?php		if(isset($_SESSION['registerEmail']) && $_SESSION['registerEmail'] ==1)
			{
				unset($_SESSION['registerEmail']);
			?>
				<tr bgcolor="#A3D84D"><td colspan="10"><?php  echo $lang['loaners']['successtext']; ?></td></tr>
  <?php		}	?>
				<tr>
					<td width="80"> </td>
					<td></td>
					<td><?php echo $form->error("email") ?></td>
					<td></td>
				</tr>
				<tr>
					<td width="80"> </td>
					<td><?php echo 'Email:' ;?></td>
					<td>
						<input type="text" name="email" class="inputcmmn-1" value="<?php echo $form->value("email"); ?>" />     <input type="submit" value="Submit" />
					</td>
					<td><input type="hidden" name="emailregister" /><input type="hidden" name="user_guess" value="<?php echo generateToken('emailregister'); ?>"/></td>
				</tr>
			</tbody>
		</table>
	</form><br/><br/>
<?php
}
else if($session->userlevel != LENDER_LEVEL)
{
	//$ltext="index.php?p=1";
	//echo "<div>".$lang['menu']['please_reg']." <a href=$ltext>".$lang['menu']['reg']."</a></div><br />";
}
$setcolor=0;
?>
<h5>
	<form style="display:inline;color:#0099FF" action='updateprocess.php' method='post' id='form_a' name='form_a'>
<?php	if($t==1)
		{
			echo $lang['loaners']['fund_loans'];
		}
		else
		{	?>
			<a style="color:#000000" href="javascript:void(0)" onClick="javascript:mySubmitform(form_a);"><?php echo $lang['loaners']['fund_loans'];?></a>
			<input type='hidden' name='type' value=1 />
			<input type='hidden' name='get_loans'/>
			<input type="hidden" name="user_guess" value="<?php echo generateToken('get_loans'); ?>"/>
<?php	}	?>
	</form>
	<div class="split">&nbsp;</div>
	<form style="display:inline;color:#0099FF" action='updateprocess.php' method='post' id='form_b' name='form_b'>
<?php	if($t==2)
		{
			echo $lang['loaners']['act_loans'];
		}
		else
		{	?>
			<a style="color:#000000" href="javascript:void(0)" onClick="javascript:mySubmitform(form_b);"><?php echo $lang['loaners']['act_loans'];?></a>
			<input type='hidden' name='type' value=2 />
			<input type='hidden' name='get_loans'/>
			<input type="hidden" name="user_guess" value="<?php echo generateToken('get_loans'); ?>"/>
<?php	}	?>
	</form>
	<div  class="split">&nbsp;</div>
	<form style="display:inline;color:#0099FF" action='updateprocess.php' method='post' id='form_c' name='form_c'>
	<?php	if($t==3)
		{
			echo $lang['loaners']['end_loans'];
		}
		else
		{	?>
			<a style="color:#000000" href="javascript:void(0)" onClick="javascript:mySubmitform(form_c);"><?php echo $lang['loaners']['end_loans'];?></a>
			<input type='hidden' name='type' value=3 />
			<input type='hidden' name='get_loans'/>
			<input type="hidden" name="user_guess" value="<?php echo generateToken('get_loans'); ?>"/>
<?php	}	?>
	</form>
</h5>
<div id="sortbar">
	<form method="post" action="updateprocess.php">
		<div class="sorttext">Viewing <?php echo $showingRes?> of <?php echo $totalRes?> Results.</div>
		<div class="input">
<?php if($t==2){ ?>
			<select class="medium" id="fundSort" name="activeSort" style="width:auto;">
				<option value='1'>Random Sorting</option>
				
				<!-- these options no longer work because number of loans to sort is too many, removed pending introduction of replacement filtering feature 
				<option value='2' <?php if($sort==2)echo "Selected"; ?>>Percent Repaid</option>
				<option value='3' <?php if($sort==3)echo "Selected"; ?>>Number of Comments</option>
				<option value='4' <?php if($sort==4)echo "Selected"; ?>>Borrower Feedback Rating</option>
			-->
				<option value='5' <?php if($sort==5)echo "Selected"; ?>>Date Disbursed (Earliest First)</option>
				<option value='6' <?php if($sort==6)echo "Selected"; ?>>Date Disbursed (Most Recent First)</option>
			</select>
			<input type='hidden' name='type' value=2 />

<?php } else if($t==3){ ?>
			<select class="medium" id="activeSort" name="endSort" style="width:auto;">
				<option value='1'>Random Sorting</option>
				<option value='2' <?php if($sort==2)echo "Selected"; ?>>Number of Comments</option>
				<option value='3' <?php if($sort==3)echo "Selected"; ?>>Borrower Feedback Rating</option>
				<option value='4' <?php if($sort==4)echo "Selected"; ?>>Date Repaid (Earliest First)</option>
				<option value='5' <?php if($sort==5)echo "Selected"; ?>>Date Repaid (Most Recent First)</option>
			</select>
			<input type='hidden' name='type' value=3 />
<?php } else{ ?>
			<select class="medium" id="endSort" name="fundSort" style="width:auto;">
				<option value='7' <?php if($sort==7)echo "Selected"; ?>>On-Time Repayments</option>
				<option value='3' <?php if($sort==3)echo "Selected"; ?>>Expiring Soon</option>
				<option value='6' <?php if($sort==6)echo "Selected"; ?>>Most Recently Posted</option>

				<option value='4' <?php if($sort==4)echo "Selected"; ?>>Interest Rate Offered</option>
				<option value='5' <?php if($sort==5)echo "Selected"; ?>>Borrower Feedback Rating</option>
				
				<option value='2' <?php if($sort==2)echo "Selected"; ?>>Amount Still Needed</option>
				<option value='1'>Random Sorting</option>
				
			</select>
			<input type='hidden' name='type' value=1 />
<?php } ?>
		</div><!-- /input -->
		<div class="sortsearch">
			<input class="login-field search-input" type="text" name="searchLoan" value="<?php echo $searchLoan ?>" style="<?php if($searchLoan!='Search Loans') echo 'font-style:normal';?>"/>
			<input type="hidden" name="searchSort">
			<input type='hidden' name='get_loans'/>
			<input type="hidden" name="user_guess" value="<?php echo generateToken('get_loans'); ?>"/>
			<button type="submit" class="btn square">Go</button></form>
		</div><!-- /sortsearch -->
	</form>
</div><! -- /sortbar -->
<?php
if(!empty($openloans))
{
	foreach($openloans as $row)
	{
		$userid=$row['userid'];
		$is_volunteer= $database->isBorrowerAlreadyAccess($userid);
		$bfrstloan=$database->getBorrowerFirstLoan($userid);
		$RepayRate=$session->RepaymentRate($userid);
		

//added by Julia 15-10-2013

		
		$totalTodayinstallment=$database->getTotalInstalAllLoans($userid);



		$name=$row['FirstName'].' '.$row['LastName'];
		$post=$row['PAddress'];
		$city=$row['City'];
		$country=$database->mysetCountry($row['Country']);
		$amount=$row['reqdamt'];
		//$interest=$row['interest'];
		$period=$row['period'];
		$grace=$row['grace'];
		if($row['tr_summary']==null || $row['tr_summary']=="")
			$summary=$row['summary'];
		else
			$summary=$row['tr_summary'];
		if($row['tr_loanuse']==null || $row['tr_loanuse']=="")
			$loanuse=$row['loanuse'];
		else
			$loanuse=$row['tr_loanuse'];
		$photo=$row['Photo'];
		$loanid = $row['loanid'];
		$interest=$database->getAvgBidInterest($userid, $loanid);
		$totBid=$database->getTotalBid($userid,$loanid);
		$brw2 = $database->getLoanDetails($loanid);
		$ramount=number_format($brw2['reqdamt'], 0, "", "");
		if($totBid>=$brw2['reqdamt'])
		{
			//$interest=$interest;
		}
		else
		{
			$brw2 = $database->getLoanDetails($loanid);
			$webfee=$brw2['WebFee'];
			$interest=$brw2['interest'] - $webfee;
		}
		//$loanuse=$session->smart_trim($loanuse, 100);
		$report=$database->loanReport($userid);
		$f=$report['feedback'];
		$cf=$report['Totalfeedback'];
		
		if (file_exists(USER_IMAGE_DIR.$userid.".jpg")){
			if(!$photo || $photo==NULL)
			{
				$photo='library/getimage.php?id='.$userid.'&width=235&height=310';
			} 
		}
		else {

			$brw=$database->getBorrowerDetails($userid);
			$fb_data = unserialize(base64_decode($brw['fb_data']));
	
			if( ! empty($fb_data)){ //case where borrower has not uploaded own photo but has linked FB account, use FB profile
							
				$photo="https://graph.facebook.com/".$fb_data['user_profile']['id']."/picture?width=9999&height=9999";
			}
		}
		$statusbar=$session->getStatusBar($userid,$loanid);
		$amount=number_format($amount, 0, ".", ",");
		$statusMsg=$lang['loaners']['lend'];
		if($brw2['active'] !=LOAN_OPEN)
			$statusMsg=$lang['loaners']['status'];
		$loanprurl = getLoanprofileUrl($userid,$loanid);
		$prurl = getUserProfileUrl($userid);
	?>
		<div class="browse-listing">
			<a href="<?php echo $loanprurl?>"><img src="<?php echo $photo?>" alt="<?php echo $name?>" /></a>
			<div class="listing-info">
				<h4><?php echo $name?></h4>
				<p><?php if($is_volunteer){?><img class='starimg' src="images/star.png" ></img>&nbsp;&nbsp;&nbsp;Volunteer Mentor<br/><?php } 

//modified by Julia to add number of months repayments were due 15-10-2013

		if($bfrstloan){ echo  $lang['loaners']['repayrate']?>: <?php echo number_format($RepayRate); ?>% (<?php echo number_format($totalTodayinstallment)?>)

<?php }

/* removed by Julia 15-10-2013

		if($bfrstloan){ if($f!=''){echo number_format($f); ?>% Positive Feedback&nbsp;(<a href="<?php echo $prurl?>?fdb=2"><?php echo $cf-1; ?></a>)<?php } }

*/

		else	echo 'New Member'; ?> 
				
				<br /><br /><?php echo $city.", ".$country;?></p>
				<p>
					<?php if(!empty($summary)){

						echo $summary." <a href='$loanprurl'>Read More</a>";

					}else{

						if(strlen($loanuse) >200){

							echo substr($loanuse,0,200)." <a href='$loanprurl'>Read More</a>";
						  
						}else{

							echo $loanuse." <a href='$loanprurl'>Read More</a>";
						}
					
					} ?>
				</p>
				<p><strong><?php echo $lang['loaners']['amount_requested']?>:</strong> <?php echo $amount; ?> USD<br /><strong><?php echo $lang['loaners']['interest']; ?>:</strong> <?php echo number_format($interest, 2, '.', ','); ?>%</p>
				<p><?php echo $statusbar ?></p>
				<?php $loanprurl = getLoanprofileUrl($userid,$loanid);?>
				<p><a class="btn" href="<?php echo $loanprurl;?>"><?php echo $statusMsg ?></a></p>
			</div><!-- /listing-info -->
		</div><!-- /browse-listing -->
		<div class="divider">&nbsp;</div>
<?php
	}
}
else
{
	echo "<div class='browse-listing'><p>".$lang['loaners']['no_search']."</p></div>";
}
?>
<form  name="form1" >
	<?php
		$randomLoans='';
		if(!empty($openloans[0]['randomLoans']))
		{
			$randomLoans=implode(',',$openloans[0]['randomLoans']);
		}
	?>
	<input type='hidden' name='get_loans'>
	<input type="hidden" name="user_guess" value="<?php echo generateToken('get_loans'); ?>"/>
	<input type='hidden' name='type' value="<?php echo $t ?>" />
	<input type='hidden' name='sort' value="<?php echo $sort ?>" />
	<input type='hidden' name='searchLoan' value="<?php echo $searchLoan ?>" />
	<input type='hidden' name='randomLoans' value="<?php echo $randomLoans ?>" />
<?php
	$pagination=$c;
	if($openloans[0]['count'] > $pagination)
	{
		$j=ceil($openloans[0]['count']/$pagination);
	}
	else
		$j=1;
	$i=floor($openloans[0]['count']/$pagination)*$pagination;
	$limit=(floor($page/4)*4)-1;
	if($limit <0)
		$limit=0;
	$from=$row1+1;
	$to=$row1+$pagination;
	$to=($to < $openloans[0]['count'])? $to : $openloans[0]['count'];
	$rows = $row1 +$pagination;
	if($rows <= $openloans[0]['count'] )
		$rows =  $row1 +$pagination;
	else
		$rows =  $openloans[0]['count'];

	if(isset($rows) && $openloans[0]['count'] > $pagination)
	{
?>
		<div align="center">
			<div class="pagination">
				<ul>
					<?php if($page !=1){?>
					<li><a href="javascript: void(0);" onClick="sub(form1,0,1);"><?php echo $lang['loaners']['first'] ?></a></li>
					<?php }else{?>
					<li class="disabled"><?php echo $lang['loaners']['first'] ?></li>
					<?php }if($page >1){?>
					<li><a href="javascript: void(0);" onClick="sub(form1,<?php echo $row1-$pagination; ?>, <?php echo ($page-1); ?>);">&larr; <?php echo $lang['loaners']['previous'] ?></a></li>
					<?php }else{?>
					<li class="prev disabled">&larr; <?php echo $lang['loaners']['previous'] ?></li>
					<?php }?>
<?php				for($m=$limit; ($m<($limit+4) && $m<$j); $m++ )
					{	?>
						<li class="<?php if(($m+1)==$page) echo'active'?>"><a href="javascript: void(0);" onClick="sub(form1,<?php echo ($m*$pagination); ?>,<?php echo ($m+1); ?>);"><?php echo ($m+1); ?></a></li>
<?php				}	?>
					<?php if(($row1+$pagination) < $openloans[0]['count']){?>
					<li class="next"><a href="javascript: void(0);" onClick="sub(form1,<?php echo $row1+$pagination; ?>,<?php echo ($page+1); ?>);"><?php echo $lang['loaners']['next'] ?> &rarr;</a></li>
					<li class="last"><a href="javascript: void(0);" onClick="sub(form1,<?php echo $i; ?>,<?php echo $j; ?>);"><?php echo $lang['loaners']['last'] ?></a></li>
					<?php }else{?>
					<li class="disabled"><?php echo $lang['loaners']['next'] ?> &rarr;</li>
					<li class="disabled last"><?php echo $lang['loaners']['last'] ?></li>
					<?php }?>
				</ul>
			</div>
		</div>
<?php }   ?>
</form>
<script language="javascript">
function sub(form1, a, b)
{

	if(a>=0 && a <= <?php echo
$openloans[0]['count'] ?>)
	{

form1.action="updateprocess.php?row="+a+"&pg="+b;
		form1.method="Post";
		form1.submit();
	}
}
</script>
</div><!-- /span12 -->