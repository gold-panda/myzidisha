<?php
include_once("library/session.php");
include_once("./editables/admin.php");
include_once("includes/label.php");
?>
<script type="text/javascript" src="includes/scripts/getloanforForgive.js" ></script>
<script type="text/javascript" src="includes/scripts/label.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<div class='span12'>
<?php
if($session->userlevel==ADMIN_LEVEL)
{		
		$LoanForgiveness=$database->getAllLoanForgiveness();
?>
		<div>
				<?php if(isset($_SESSION['loan_fogiveness']))
							echo "<div id='error' align='center'><font color='green'>Loan successfully added to forgiveness.</font></div><br/>";
						unset($_SESSION['loan_fogiveness']);
				?>
			  <div style="float:left"><h3 class="new_subhead"><div align='left' class='static'><h1><br/>Allow Forgiveness</h1></div></h3></div>
			  <div style="clear:both"></div>
		</div>
		<form  action='process.php' method="POST">
			<table class='detail' style="width:auto">
				<tbody>
				<tr>
					<td style="padding-bottom: 13px;">Select Country:<br/><br/>
							<select name="c" id="c" class="selectcmmn" onChange="LoanForForgive(this.value)" style="min-width:300px">
							<option>Select Country</option>
							<?php	$result1 = $database->countryList(true);
								$tempcountry=$form->value("country");
								if(!empty($tempcountry))
									$country=$tempcountry;
								if(!empty($result1))
								{	
									foreach($result1 as $state)
									{	?>
										<option value="<?php echo $state['code']; ?>"<?php if($country==$state['code'])echo "Selected='true'"; ?>><?php echo $state['name'];?></option>
							<?php 	}
								}	?>
									
							</select>
						</td>
					</tr>
					<tr>
						<td>Select Loan<br/><br/><div id="loansbycountry"><select id="activeLoan" name="loan_id" style="width:300px"><option></option></select></div>
						<div><?php echo $form->error("loan_id"); ?></div>
						</td>
					</tr>
					<tr>
						<td>Comment <br/><br/>
							<textarea name="comment" style="width:290px;height:170px;"><?php echo $form->value("comment"); ?></textarea>
							<div><?php echo $form->error("comment"); ?></div>
						</td>
					</tr>
					<tr>
						<td colspan='2' >
							<input type="hidden" name="AllowForgive" id="AllowForgive" value='allow'>
							<input class='btn' type='submit' name='Allow' value='Allow Forgiveness' onclick="return confirm('Are you sure you wish to allow forgiveness for this loan?')"/>
						</td>
					</tr>
					</tbody>
				</table>
			</form>
		<?if(!empty($LoanForgiveness))
			{	
		echo"<table class='zebra-striped'>";
						echo"
						<thead>
							<th>Borrower</th>
							<th>Loanid</th>
							<th>Comment</th>
							<th>date</th>
							<th>detail</th>
						</thead>";
					echo"<tbody>";
								foreach($LoanForgiveness as $loan) {
									$lenderids = $database->ForgivenLendersThisLoan($loan['loanid']);
									$lenderoptedout = $database->getoptedoutlenders($loan['loanid']);
									$notresponded = $database->getnotrespondedlendersonforgive($loan['loanid']);
									$notrespondedcount = count($notresponded);
									$lendersforgiven = count($lenderids);
									$lenderoptedoutcount = count($lenderoptedout);
									$name=$loan['FirstName'].' '.$loan['LastName'];
									$prurl = getUserProfileUrl($loan['userid']);
									$loanid=$loan['loanid'];
									$comment=$loan['comment'];
									$date=date('M d, Y',$loan['time']);
									$userid=$loan['userid'];
									echo"<tr>";
									echo"<td><a href='$prurl'>$name</a></td>";
									echo"<td>$loanid</td>";
									echo"<td>$comment</td>";
									echo"<td>$date</td>";
									echo"<td width='150px'>
										Forgiven:$lendersforgiven<br/><br/>
										Opted Out:$lenderoptedoutcount<br/><br/>
										<a href='javascript:void()' id='notresponded_$loanid' class='notresponded' style='text-decoration:none' onclcik=''>Not Responded:$notrespondedcount</a><br/><br/>
										<span id='lendernotresponded$loanid' class='lendernotresponded' style='display:none'>
										";
										foreach($notresponded as $lenderid) {
											$lprurl = getUserProfileUrl($lenderid['lenderid']);
											echo "<a href='$lprurl' target='_blank'>".trim($lenderid['FirstName']." ".$lenderid['LastName'])."</a><br/>".$lenderid['Email']."<br/><br/>";
										}
										echo"</span>
									</td>";
									echo"<tr/>";
								}?>
						</tbody>
				</table>
	<?php }
}
else {
			echo "<div>";
			echo $lang['admin']['allow'];
			echo "<br />";
			echo $lang['admin']['Please'];
			echo "<a href='index.php'>click here</a>".$lang['admin']['for_more']. "<br />";
			echo "</div>";
		}?>
		<script type="text/javascript">
		<!--
		$(document).ready(function() {
			$('.notresponded').click(function() {
				var elemid = $(this).attr('id'); 
				var substr = elemid.split('_');
				var loanid = substr[1]
				$('#lendernotresponded'+loanid).toggle(600);
				});
			});
		//-->
		</script>