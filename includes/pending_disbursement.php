<link rel="stylesheet" href="css/default/jquery.custom.css" type="text/css"></link>
<script src="includes/scripts/jquery.custom.min.js" type="text/javascript"></script>

<script type="text/javascript">
$(function() {		
		$(".pndng_disbrs_rprt").tablesorter({sortList:[[5,5]], widgets: ['zebra'], headers: { 2:{sorter:false}, 3:{sorter:false},4:{sorter:false},5:{sorter:'digit'},6:{sorter:false},7:{sorter:false},8:{sorter:false}}});
 });	
</script>

<div class='span12'>
<div><div style="float:left"><div align='left' class='static'><h1>Pending Disbursements</h1></div></div>
<div style="clear:both"></div></div><br/>
<script language="javascript">
$(document).ready(function(){
	$(".datedisbursed").datepicker({ maxDate: new Date});
	$(".confirm_auth").datepicker({ maxDate: new Date});
});
function calculateAmt(id) {
	
	var Amount = document.getElementById("amount"+id).value;

	if(!isNaN(Amount)) {
		if(document.getElementById("open_amount"+id))
			document.getElementById("open_amount"+id).value=Amount;
		if(document.getElementById("princ_amount"+id))
			document.getElementById("princ_amount"+id).value=Amount;
	}
	else
	   {	
		if(document.getElementById("open_amount"+id))
			document.getElementById("open_amount"+id).value='';
	   }

	if(document.getElementById("reg_fee"+id))
	{
		var regFee = document.getElementById("reg_fee"+id).value;
	}

	var AmtToPay = parseFloat(Amount)- parseFloat(regFee);
	
	if(!isNaN(regFee)) {
		if(document.getElementById("register_fee"+id))
			document.getElementById("register_fee"+id).value=regFee;
	}else
		{
		if(document.getElementById("register_fee"+id))
			document.getElementById("register_fee"+id).value='';
	}


	if(!isNaN(AmtToPay)) {
		if(document.getElementById("amtToPay"+id))
			document.getElementById("amtToPay"+id).value = AmtToPay;
		if(document.getElementById("amountToPay"+id))
			document.getElementById("amountToPay"+id).value = AmtToPay;
	}else{
		if(document.getElementById("amtToPay"+id))
			document.getElementById("amtToPay"+id).value = '';
		if(document.getElementById("amountToPay"+id))
			document.getElementById("amountToPay"+id).value = '';
	}
}
function saverefdetail(id)
{
	var note = document.getElementById('note'+id).value;
	var loanid = document.getElementById('loan'+id).value;
	if (window.XMLHttpRequest)
	{
		xmlhttp = new XMLHttpRequest();
	}
	else
	{
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200) {
				if(xmlhttp.responseText == 1) {
					document.getElementById("response"+id).innerHTML = "<font color=green>saved</font>";
					var new_note = document.getElementById("note"+id).value;
					document.getElementById("note"+id).value = new_note;
				} else if(xmlhttp.responseText == 2) {
					document.getElementById("response"+id).innerHTML = "<font color=green>saved</font>";
					var new_note = document.getElementById("note"+id).value;
					document.getElementById("note"+id).value = new_note;
				}
				else {
					document.getElementById("response"+id).innerHTML = "<font color=red>failed</font>";
				}
			}
		}
		else {
				document.getElementById("response"+id).innerHTML="<img src='images/layout/icons/ajax-loader.gif' border='0' alt=''>";
		}
	}
	var params = "savedisbursednote="+''+"&userid="+id+"&note="+note+"&loanid="+loanid;
	xmlhttp.open("POST","process.php",true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.setRequestHeader("Content-length", params.length);
	xmlhttp.setRequestHeader("Connection", "close");
	xmlhttp.send(params);
}
</script>
<?php 
if($session->userlevel==LENDER_LEVEL)
{
	$userid=$session->userid;
	$res=$database->isTranslator($userid);
	if($res==1)

		$isvolunteer=1;
} 


if($isvolunteer==1 || $session->userlevel==ADMIN_LEVEL){ 
	$country='';
	if(isset($_GET['c'])){
		$country=$_GET['c'];
	}
	?>
		<form  action='updateprocess.php' method="POST">
			<!-- To Date:&nbsp<input  name="date" id="date" type="text"value='<?php echo $date ;?>'/>	 -->
			&nbsp&nbsp
			Country:&nbsp
			<select id="country" name="country" >
	<?php		$result1 = $database->countryListWithAll(true);
				echo "<option value='0'>Select a country</option>";
				foreach($result1 as $cont)
				{?>
					<option value='<?php echo $cont['code']?>' <?php if($country==$cont['code']) echo "Selected='true'";?>><?php echo $cont['name']?></option>
	<?php		}	?>
			</select>
			<input type="hidden" size="3" name="disbursement_report" id="disbursement_report">
			<input type="hidden" name="user_guess" value="<?php echo generateToken('disbursement_report'); ?>"/>
			&nbsp&nbsp<input class='btn' type='submit' name='report' value='Report' /><br/>
		</form>
		<br/>
		<br/>

	<?php 
		if(!empty($country)){
			$borrower_report= $database->getAllFundedLoans($country);
				
			$i=0;
	?>
			
		<table class="zebra-striped pndng_disbrs_rprt">
		<thead>
			<tr>
				<th>Name</th>
				<th>Location</th>
				<th>National ID</th>
				<th>Telephone</th>
				<th>Special Instructions</th>
				<th>Bids Accepted</th>
				<th>Amount</th>
				<th>Status</th>
				<th>Notes</th>
			</tr>
		</thead>
		<tbody>
	<?php	foreach($borrower_report as $borrower){
				$userid=$borrower['borrowerid'];
				$loanid=$borrower['loanid'];
				$prurl=getUserProfileUrl($userid);
				$name=$borrower['FirstName']." ".$borrower['LastName'];
				$location=$database->mysetCountry($borrower['Country'])."<br/><br/>".$borrower['City'];
				$nationId=$borrower['nationId'];
				$number=$borrower['TelMobile'];
				$pamount=$borrower['p_amount'];
				
				if($borrower['accept_bid_note']!='') {
				
				$bid_notes=$borrower['accept_bid_note'];
				
				}else{
				$bid_notes='';
				}
				
				$bid_accpt_date=$borrower['AcceptDate'];
				$accept_date=date('d M, Y',$borrower['AcceptDate']);
				$bfrstloan=$database->getBorrowerFirstLoan($userid);
				$activationdate=$database->getborrowerActivatedDate($userid);
							
				
				/************* Amount Summary ***************/
				
				
				$auth_date=$database->getAuthActive($loanid);
	
				if(empty($auth_date)) {
									
					$openLoanAmt=$database->getOpenLoanAmount($userid,$loanid);
				}else{
					if($pamount>0)
						{
						$openLoanAmt=number_format($pamount, 0, ".", "");
						}
						else{
						$openLoanAmt=$database->getAuthLoanAmount($userid,$loanid);
						}
				}
				
				$amount_smry="Principal Amount:<input id='amount$i' name='amount' value= '$openLoanAmt' 'type='text' size='8' style='width:auto' onkeyup='calculateAmt($i)'/>";
				$isfirstLoan=false;
				if(!$bfrstloan)
				{
				$reg_fee=$database->getReg_CurrencyAmount($userid);
				foreach($reg_fee as $row)
				{
					$currency1=$row['currency'];
					$amount_reg=$row['Amount'];
					$reg_fee=number_format($amount_reg, 0, ".", "");
				}
				
				$amtToPay = $openLoanAmt-$reg_fee;
				
				$amount_smry .="<br>Registration Fee:<input name='reg_fee' style='width:auto;color:black' id='reg_fee$i' value='$reg_fee' type='text' size='8' style='width:auto' onkeyup='calculateAmt($i)'/>";
				
				$amount_smry .="<br>Net Amount to Pay:<input name='amtToPay' style='width:auto;color:black' value='$amtToPay' type='text' size='8' style='width:auto' disabled id='amtToPay$i'  />";
				$isfirstLoan=true;

				}				
							
				/**************************************/

				/********** for disbursment amount*********/

				$amount="<form name='activeform".$userid."' method='post' action='process.php'>".
				"<input name='makeLoanActive' type='hidden' />".
				"<input type='hidden' name='user_guess' value='".generateToken('makeLoanActive')."'/>".
				"<input name='borrowerid' value='$userid' type='hidden' />".
				"<input name='loanid' value='$loanid' type='hidden' />".
				"<input id='open_amount$i' name='amount' value= '$openLoanAmt' type='hidden' />";
				if($isfirstLoan) {
					$amount .="<input name='reg_fee' id='register_fee$i' value='$reg_fee' type='hidden' />";
				}				
				$amount .="<input name='amtToPay' value='$amtToPay' type='hidden' id='amountToPay$i'  />";
				
				$auth_date=$database->getAuthActive($loanid);
				
				$authorized_date=date('M d, Y',$auth_date);

				$amount .="Authorized <br/>".$authorized_date;
				$amount .="<br/><br>Date Disbursed:<br/>";
				$amount .="<input type='text' name='disb_date' style='width:80px;color:black' class='datedisbursed'><br/>";
				$amount .="<a href='javascript:void(0)'  onclick='document.forms.activeform".$userid.".submit()'>".'<br>Confirm Disbursement'."</a>".
				"</form>";
				
				/**************************************/

				/********** for  Authorization Status*********/

				$status="<form name='authform".$userid."' method='post' action='process.php'>".
				"<input name='makeConfirmAuth' type='hidden' />".
				"<input type='hidden' name='user_guess' value='".generateToken('makeConfirmAuth')."'/>".
				"<input name='borrowerid' value='$userid' type='hidden' />".
				"<input name='loanid' value='$loanid' type='hidden' />".
				"<input id='princ_amount$i' name='pamount' value= '$openLoanAmt' type='hidden' />";
				$status .='Pending Authorization<br/><br/>Date Authorized:';
				$status .="<input type='text' name='auth_date' style='width:80px;color:black' class='confirm_auth'><br/>";

				$status .="<a href='javascript:void(0)'  onclick='document.forms.authform".$userid.".submit()'>".'<br/>Confirm Authorization'."</a>"."</form>";
				
				/**************************************/
				
				$notes=$database->getDisbursedNotes($loanid);
				echo '<tr>';
					
					echo "<td><a href='$prurl'>$name</a></td>";
					echo "<td>$location</td>";
					echo "<td>$nationId</td>";
					echo "<td>$number</td>";
					echo "<td>$bid_notes<br/><br/>";

				/* 
				If borrower was activated since the date we removed the ID card 
				and recommendation form from pre-activation requirements, display 
				an alert to check these newly uploaded documents before disbursing 
				the loan. 
				*/

					if ($activation_date > 1389835131){
						$details=$database->getBorrowerById($userid); 

						if(!empty($details['frontNationalId'])){
							echo "<strong>This member has uploaded a national ID card since activation. Please open the profile page and check the national ID card to ensure it is legible and matches the member's name before disbursing this loan.</strong>";
						}

						if(!empty($details['addressProof'])){
							echo "<strong>This member has uploaded a Recommendation Form since activation. Please open the profile page and check the Recommendation Form to ensure it matches the member's name and has been signed by an eligible community leader before disbursing this loan.</strong>";
						}

					}

					echo"</td>";
					echo "<td><span style='display:none'>$bid_accpt_date</span>$accept_date</td>";
					echo "<td>$amount_smry</td>";
				
				$auth_date=$database->getAuthActive($loanid);		
				
				if(empty($auth_date)) {
									
					echo "<td>$status</td>";
				}else
					{
					echo "<td>$amount</td>";
				}
				 

					echo "<td><textarea name='note'  id='note$userid' col='6' row='6' style='height: 80px'  >$notes</textarea><br/><br/><input type='hidden' id='loan$userid' value='$loanid'><div id='saveButtonDiv$userid' style='margin-left: 90px;'><input type='button' name='save' class='btn' id='save$userid' value='Save' onclick='saverefdetail($userid)'><br/><span	id='response$userid'></span></div></td>";
				echo '</tr>';

				$i++;
			}?>
		</tbody>
	</table>
		
<?php	}
	} ?>
</div>