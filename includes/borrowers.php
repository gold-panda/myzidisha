<div class='span12'>
<div><div style="float:left"><div align='left' class='static'><h1>Enter Repayments</h1></div></div>
<div style="clear:both"></div></div><br/>

<script type="text/javascript">
	$(function() {		
		$(".tablesorter_borrowers").tablesorter({sortList:[[0,0]], widgets: ['zebra'], });
	});	
$(document).ready(function(){
	$(".datedisbursed").datepicker({ maxDate: new Date});
});
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
	$brwr_type='';
	$search='';
	if(isset($_GET['c'])){
		$country= $_GET['c'];
	}
	if(isset($_GET['brwr'])){
		$brwr_type= $_GET['brwr'];
	}
	if(isset($_GET['search'])){
		$search= $_GET['search'];
	}
	$page=1;
	$limit=5000;
	if(isset($_GET['page']) && !empty($_GET['page']))
	{
		$page = $_GET['page'];
	}
	$ord="ASC";
	$ordClass="headerSortDown";
	if(isset($_GET["ord"]) && $_GET["ord"]=='DESC')
	{
		$ord='DESC';
		$ordClass="headerSortUp";
	}
	$type=1;
	if(isset($_GET["type"]))
	{
		$type=$_GET["type"];
	}
	$sort='FirstName';
	if($type==2)
		$sort='country';
	else if($type==3)
		$sort='telmobile';
	$start=($page - 1) * $limit;
	$count=$database->getAllBorrowersCount();
	$set=$database->getAllBorrowers($sort, $ord, $start, $limit, $country, $brwr_type, $search);
	?>
	<form  action='updateprocess.php' method="POST">
		Select Country:&nbsp
				<select id="country" name="country" >
		<?php		$result1 = $database->countryListWithAll(true);
					foreach($result1 as $cont)
					{?>
						<option value='<?php echo $cont['code']?>' <?php if($country==$cont['code']) echo "Selected='true'";?>><?php echo $cont['name']?></option>
		<?php		}	?>
				</select><br/><br/><br/><br/>
		Select Status:&nbsp;&nbsp;&nbsp;
		<select id="borrower_type" name="borrower_type" >
			<option value='all' <?php if($brwr_type=='all') echo "Selected='true'";?>>All</option>
			<option value='endorser' <?php if($brwr_type=='endorser') echo "Selected='true'";?> >Endorser Account</option>
			<option value='pndng_sub' <?php if($brwr_type=='pndng_sub') echo "Selected='true'";?> >Pending Submission</option>
			<option value='pndng_act' <?php if($brwr_type=='pndng_act') echo "Selected='true'";?> >Pending Activation</option>
			<option value='decline' <?php if($brwr_type=='decline') echo "Selected='true'";?>>Declined</option>
			<option value='active' <?php if($brwr_type=='active') echo "Selected='true'";?>>Active</option>
		</select><br/><br/><br/><br/>
		Search for a name, email or phone number: <input type="text" name="search" maxlength="100" value="<?php echo $search; ?>"/><br/><br/><br/><br/>
		<input type="hidden" name="getAllborrowers">
		<input type="hidden" name="type" value="<?php echo $type; ?>">
		<input type="hidden" name="ord" value="<?php echo $ord; ?>">
		<center><input type='submit' name='submit' class='btn' value='Submit'/></center>
	</form>
			<div class="subhead2">
				  <div style="float:left"><h3 class="new_subhead"><?php echo $lang['admin']['all_borrowers']?></h3></div>
				  <?php if($session->userlevel==ADMIN_LEVEL ){?><div class="user_instructions">
					<a style='font-size:15px;' href="includes/instructions.php?p=<?php echo $_GET['p']; ?>" rel='facebox'>Instructions</a>
				  </div><?php } ?>
				  <div style="clear:both"></div>
			</div>

	<?php
	if(!empty($set))
	{
	?>
		<table class="zebra-striped">
			<thead>
				<tr>
					
					<th class="header <?php if($type==1) echo $ordClass;?>" onClick="borrowersort(1)"><?php echo $lang['admin']['Name'];?> </th>
					<th class="header <?php if($type==2) echo $ordClass;?>" onClick="borrowersort(2)"><?php echo $lang['admin']['Location'];?></th>
					<!---<th class="header <?php if($type==3) echo $ordClass;?>" onClick="borrowersort(3)"><?php echo $lang['admin']['Contacts'];?></th>----> <!---- comment by mohit 11-10-13 ---->
					<th><?php echo $lang['admin']['Loan_Status'];?></th>
			<!--	<th><?php echo $lang['admin']['delete_button'];?></th>  -->
				</tr>
			</thead>
			<tbody>
	<?php		$i=0;
		

				foreach($set as $row)
				{
					$userid=$row['userid'];
					$name=$row['firstname'].' '.$row['lastname'];
					$prurl= getUserProfileUrl($userid);
					$username=$database->getUserNameById($userid);	
					$city=$row['city'];
					$country=$database->mysetCountry($row['country']);
					$tel=$row['telmobile'];
					$email=$row['email'];
					$partner=$row['pname'];
					$loan=$row['loanactive'];
					$loanid = $row['activeloanid'];
					$active=$row['Active'];
					$loanstatus='';
					$bReg='';
					if($loan==NO_LOAN){
						$defaultLoanid=$database->getDefaultedLoanid($userid);
						if($defaultLoanid)
						{
							$loanstatus="WRITTEN OFF Loan<br/>"."<br/>"."<form name='undodefaultedform".$userid."' method='post' action='updateprocess.php'>".
							"<input name='makeLoanUndoDefault' type='hidden' />".
							"<input type='hidden' name='user_guess' value='".generateToken('makeLoanUndoDefault')."'/>".
							"<input name='borrowerid' value='$userid' type='hidden' />".
							"<input name='loanid' value='$defaultLoanid' type='hidden' />".
							"<a href='javascript:void(0)' style='color:red' onclick='javascript:mySubmit(6,undodefaultedform$userid);'>Undo Writeoff</a>".
							"</form>";
						}
						else
							$loanstatus="No Loan";
					}
					else if($loan == LOAN_OPEN){
						$loanstatus="OPEN Loan" . "<form name='expireform".$userid."' method='post' action='process.php'>".
						"<input name='makeLoanExpire' type='hidden' />".
						"<input type='hidden' name='user_guess' value='".generateToken('makeLoanExpire')."'/>".
						"<input name='borrowerid' value='$userid' type='hidden' />".
						"<input name='loanid' value='$loanid' type='hidden' />".
						"<a href='javascript:void(0)' style='color:red' onclick='document.forms.expireform".$userid.".submit()'>".$lang['admin']['expire_button']."</a>".
						"</form>";
					}
					else if($loan==LOAN_FUNDED){
						$bfrstloan=$database->getBorrowerFirstLoan($userid);
						$loanstatus="FUNDED Loan"."<br />

<!-- moved to pending_disbursements.php by Julia 16-10-2013

<form name='activeform".$userid."' method='post' action='process.php'>".
						"<input name='makeLoanActive' type='hidden' />".
						"<input type='hidden' name='user_guess' value='".generateToken('makeLoanActive')."'/>".
						"<input name='borrowerid' value='$userid' type='hidden' />".
						"<input name='loanid' value='$loanid' type='hidden' />";
						$openLoanAmt=$database->getOpenLoanAmount($userid,$loanid);
						$loanstatus .="<br>Principal Amount:<input id='amount$i' name='amount' value= '$openLoanAmt' 'type='text' size='8' style='width:auto' onkeyup='calculateAmt($i)'/>";
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
						$loanstatus .="<br>Registration Fee:<input name='reg_fee' style='width:auto;color:black' id='reg_fee$i' value='$reg_fee' type='text' size='8' style='width:auto' onkeyup='calculateAmt($i)'/>";
						$loanstatus .="<br>Net Amount to Pay:<input name='amtToPay' style='width:auto;color:black' value='$amtToPay' type='text' size='8' style='width:auto' disabled id='amtToPay$i'  />";

						}
						$acceptbid_note= $database->getAcceptBidNote($loanid);
						if(empty($acceptbid_note)){
							$acceptbid_note='';
						}
						$loanstatus .="<br/>Special Instructions:<br/><div style='width:130px;'>$acceptbid_note</div><br/>";
						$loanstatus .="<br/><br>Date Disbursed:<br/>";
						$loanstatus .="<input type='text' name='disb_date' style='width:80px;color:black' class='datedisbursed'>";
						$loanstatus .="<a href='javascript:void(0)'  onclick='document.forms.activeform".$userid.".submit()'>".'<br>Confirm Disbursement'."</a>".
						"</form>
-->

"."<br/><form name='expireform".$userid."' method='post' action='process.php'>".
						"<input name='makeLoanExpire' type='hidden' />".
						"<input type='hidden' name='user_guess' value='".generateToken('makeLoanExpire')."'/>".
						"<input name='borrowerid' value='$userid' type='hidden' />".
						"<input name='loanid' value='$loanid' type='hidden' />".
						"<a href='javascript:void(0)' style='color:red' onclick='document.forms.expireform".$userid.".submit()'>".$lang['admin']['expire_button']."</a>".
						"</form>";

					}
					else if($loan==LOAN_ACTIVE){
						$loanstatus="ACTIVE Loan<br/>"."<a href=index.php?p=11&a=5&u=$userid><br />Enter Repayment</a><br/><br/>"."

<!--moved to installment.php by Julia 16-10-2013

<form name='defaultedform".$userid."' method='post' action='updateprocess.php'>".
						"<input name='makeLoanDefault' type='hidden' />".
						"<input type='hidden' name='user_guess' value='".generateToken('makeLoanDefault')."'/>".
						"<input name='borrowerid' value='$userid' type='hidden' />".
						"<input name='loanid' value='$loanid' type='hidden' />".
						"<a href='javascript:void(0)' style='color:red' onclick='javascript:mySubmit(5,defaultedform$userid);'>".$lang['admin']['defauled_button']."</a>".
						"</form>
-->
						";
					}
					else if($loan==LOAN_REPAID){
						$loanstatus="REPAID Loan";
					}

			// Comment by mohit 11-10-13

					/*if($active==0){
						$active1="<form name='activeform".$userid."' method='post' action='process.php'>".
						"<input name='deactivateBorrower' type='hidden' />".
						"<input type='hidden' name='user_guess' value='".generateToken('deactivateBorrower')."'/>".
						"<input name='borrowerid' value='$userid' type='hidden' />".
						"<input name='set' value = 1 type='hidden' />".
						"<a href='javascript:void(0)' style='color:red' onclick='document.forms.activeform".$userid.".submit()'>".$lang['admin']['activate_button']." </a>".
						"</form>";
					}
					else{
						$active1=//"<a href=#>Deactivate</a>";
						"<form name='deactiveform".$userid."'method='post' action='process.php'>".
						"<input name='deactivateBorrower' type='hidden' />".
						"<input type='hidden' name='user_guess' value='".generateToken('deactivateBorrower')."'/>".
						"<input name='borrowerid' value='$userid' type='hidden' />".
						"<input name='set' value = 0 type='hidden' />".
						"<a href='javascript:void(0)' style='color:red' onclick='document.forms.deactiveform".$userid.".submit()'>".$lang['admin']['deactivate_button']."</a>".
						"</form>";
					}*/
				
				// end here
					$delete_btn1="
<!-- delete button hidden by Julia 16-10-2013
					<form name='del".$userid."' id='".$userid."' method='post' action='process.php'>".
					"<input name='deleteBorrower' type='hidden' />".
					"<input type='hidden' name='user_guess' value='".generateToken('deleteBorrower')."'/>".
					"<input name='borrowerid' value='$userid' type='hidden' />".
					"<a href='javascript:void(0)' style='color:red' onclick='javascript:mySubmit(2,del$userid);'>".$lang['admin']['delete_button']."</a>".
					"</form>

-->
";          

/* 2 for calling javascript function for delettion a borrower  */
					echo '<tr>';
						
						echo "<td><a href='$prurl'>$name</a><br /><br />Phone: $tel<br /><br />Email: $email</td>";
						echo "<td>$country<br>$city</td>";
						//echo "<td>$tel<br>$email</td>";
						
						if($database->isDeleteableBorrower($userid))
							echo "<td>$loanstatus<br>$active1<br>$delete_btn1</td>";
						else
							echo "<td>$loanstatus<br>$active1</td>";
						//echo "<td>$delete_btn1</td>";
					echo '</tr>';
					$i++;
				}	?>
			</tbody>
		</table>
		<?php
			if(!empty($set) && $count > $limit)
			{
				$last = ceil($count/$limit);
		?>
				<div align="center">
					<div class="pagination">
						<ul>
							<?php if($page !=1){?>
							<li><a href="javascript: void(0);" onClick="sub(1);"><?php echo $lang['admin']['first'] ?></a></li>
							<?php }else{?>
							<li class="disabled"><?php echo $lang['admin']['first'] ?></li>
							<?php }if($page >1){?>
							<li><a href="javascript: void(0);" onClick="sub(<?php echo ($page-1); ?>);">&larr; <?php echo $lang['admin']['previous'] ?></a></li>
							<?php }else{?>
							<li class="prev disabled">&larr; <?php echo $lang['admin']['previous'] ?></li>
							<?php }?>
		<?php				for($m=0, $n=1; $m<$count; $m=$m+$limit, $n++)
							{	?>
								<li class="<?php if(($n)==$page) echo'active'?>"><a href="javascript: void(0);" onClick="sub(<?php echo ($n); ?>);"><?php echo ($n); ?></a></li>
		<?php				}	?>
							<?php if(($page * $limit) < $count){?>
							<li class="next"><a href="javascript: void(0);" onClick="sub(<?php echo ($page+1); ?>);"><?php echo $lang['admin']['next'] ?> &rarr;</a></li>
							<li class="last"><a href="javascript: void(0);" onClick="sub(<?php echo $last; ?>);"><?php echo $lang['admin']['last'] ?></a></li>
							<?php }else{?>
							<li class="disabled"><?php echo $lang['admin']['next'] ?> &rarr;</li>
							<li class="disabled last"><?php echo $lang['admin']['last'] ?></li>
							<?php }?>
						</ul>
					</div>
				</div>
		<?php }   ?>
		</form>
<?php		} ?>
	<script language="javascript">
	function sub(page)
	{
		type='<?php echo $type; ?>';
		ord='<?php echo $ord; ?>';
		window.location = 'index.php?p=11&a=1&type='+type+'&ord='+ord+'&page='+page;
	}
	function borrowersort(type)
	{
		var search='';
		var cntry = 'AA';
		<?php 
			$type =0;
			if(isset($_GET['type'])) 
				$type =$_GET['type'];
			if(isset($_GET['c'])){
				$cntry =$_GET['c'];
			}
			if(isset($_GET['search'])) 
				$search =$_GET['search'];
		?>
			if(cntry !='<?php echo $cntry ?>'){
				var cntry = '<?php echo $cntry ?>';
			}
			if(search!='<?php echo $search ?>'){
				var search= '<?php echo $search ?>';
			}
			if(type == <?php echo $type ?>){
				if('ASC'=='<?php echo $ord; ?>'){
					window.location = "index.php?p=11&a=1&type="+type+"&ord=DESC&c="+cntry+"&search="+search;
				}
				else{
					window.location = "index.php?p=11&a=1&type="+type+"&ord=ASC&c="+cntry+"&search="+search;
				}		
			}else{
					window.location = "index.php?p=11&a=1&type="+type+"&ord=ASC&c="+cntry+"&search="+search;
			}
	}
	function calculateAmt(id) {
		var regFee = document.getElementById("reg_fee"+id).value;
		var Amount = document.getElementById("amount"+id).value;
		var AmtToPay = parseFloat(Amount)- parseFloat(regFee);
		if(!isNaN(AmtToPay)) {
			document.getElementById("amtToPay"+id).value = AmtToPay;
		}else{
			document.getElementById("amtToPay"+id).value = '';
		}
	}
	</script>
<?php
} ?>