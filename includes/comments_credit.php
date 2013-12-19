<?php
include_once("library/session.php");
include_once("./editables/inactive-b.php");
?>
<script type="text/javascript" src="includes/scripts/admin.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<link rel="stylesheet" href="css/default/jquery.custom.css" type="text/css"></link>
<script src="includes/scripts/jquery.custom.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$(".classdatepicker").datepicker();
	});
</script>
<div class='span12'>
<?php
if($session->userlevel==ADMIN_LEVEL)
{
	$currencysel='sel';
?>
<div align='left' class='static'><h1>Comment Credits</h1></div>

<?php
	$countries = $database->getBorrowerCountries();
	if(empty($countries))
	{
		echo "There are no data in  set for the website<br /><br />";
	}
	else
	{	?>
		
		<table class='detail'>
			<thead>
				<tr>
					<th>S. No.</th>
					<th>Country</th>
					<th>Credit Amount</th>
					<th>Minimum Character</th>
					<th>Maximum Comments</th>
				</tr>
			</thead>
			<tbody>
		<?php	$i = 1;
				$j=1;
				foreach($countries as $row)
				{	?>
					<script type="text/javascript">
						$(document).ready(function(){
							var error=0;
							//check for the Minimum Fund a lender can provide
							$("#viewAmount<?php echo $j; ?>").click(
								function(event){
									$(this).hide("fast");
									$("#editAmount<?php echo $j; ?>").show("fast");
								}
							);
							$("#cEditAmount<?php echo $j; ?>").click(
								function(event){
								$("#viewAmount<?php echo $j; ?>").show("fast");
								$("#editAmount<?php echo $j; ?>").hide("fast");
								}
							);
							$("#viewCaharacter<?php echo $j; ?>").click(
								function(event){
									$(this).hide("fast");
									$("#editmaxcharacter<?php echo $j; ?>").show("fast");
								}
							);
							$("#cmaxcharacter<?php echo $j; ?>").click(
								function(event){
								$("#viewCaharacter<?php echo $j; ?>").show("fast");
								$("#editmaxcharacter<?php echo $j; ?>").hide("fast");
								}
							);
							
							$("#viewMaxComment<?php echo $j; ?>").click(
								function(event){
									$(this).hide("fast");
									$("#editMaxComment<?php echo $j; ?>").show("fast");
								}
							);
							$("#cEditMaxComment<?php echo $j; ?>").click(
								function(event){
								$("#viewMaxComment<?php echo $j; ?>").show("fast");
								$("#editMaxComment<?php echo $j; ?>").hide("fast");
								}
							);

							$("#sCreditAmount<?php echo $j; ?>,#smaxcharacter<?php echo $j; ?>,#sMaxComment<?php echo $j; ?>").click(
								function(event){
									var amount=$("#creditamount<?php echo $j; ?>").val();
									var maxchar=$("#maxcharacter<?php echo $j; ?>").val();
									var maxcmnt=$("#MaxComment<?php echo $j; ?>").val();
									var countrycode=$("#Countrycode<?php echo $j; ?>").val();
									//var cid=$("#amtcurrencyid").val();
									  var data = "loanamtlimit="+amount+"&charlimit="+maxchar+"&commentlimit="+maxcmnt+"&country="+countrycode+"&commentcredit="+'true'+"&type=1";
									$.ajax({
										url: 'process.php',
										type: 'post',
										dataType: 'json',
										data: data,
										success: function() {
											$("#creditamountHide<?php echo $j; ?>").html(amount);
											$("#maxcharacterHide<?php echo $j; ?>").html(maxchar);
											$("#MaxCommentHide<?php echo $j; ?>").html(maxcmnt);
											$("#viewMaxComment<?php echo $j; ?>").show("fast");
											$("#editMaxComment<?php echo $j; ?>").hide("fast");
											$("#viewCaharacter<?php echo $j; ?>").show("fast");
											$("#editmaxcharacter<?php echo $j; ?>").hide("fast");
											$("#viewAmount<?php echo $j; ?>").show("fast");
											$("#editAmount<?php echo $j; ?>").hide("fast");

										}
									});
								}
							);
						});
					</script>
			<?php
					$credit_setting = $database->getcreditsettingbyCountry($row['Country'],1);
					$maxCredit = $credit_setting['loanamt_limit'];
					if(empty($maxCredit))
						$maxCredit = 0;
					$mincharacter = $credit_setting['character_limit'];
					if(empty($mincharacter))
						$mincharacter = 0;
					$maxcomment = $credit_setting['comments_limit'];
					if(empty($maxcomment))
						$maxcomment = 0;
					
				?>	
					<tr>
						<td width='50px'><?php echo $i?></td>
							<td><?php echo $row['name']?></td>
						<td width='100px'>
							<div id="viewAmount<?php echo $j; ?>" name="viewAmount" style="display:display; overflow: hidden;">
								<table class="detail" style="width:auto">
									<tr>
										<td><div id="creditamountHide<?php echo $j; ?>" name="creditamountHide<?php echo $j; ?>"> <?php echo $maxCredit?></div></td>
										<td width='100px'><input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit"></td>
									</tr>
								</table>
							</div>
							<div id="editAmount<?php echo $j; ?>" style="display:none; overflow: hidden; ">
								<input type="text" name="creditamount<?php echo $j; ?>" id="creditamount<?php echo $j; ?>" value="<?php echo $maxCredit?>" size="2"/>
								<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sCreditAmount<?php echo $j; ?>" name="sCreditAmount<?php echo $j; ?>" value="0"/> 
								<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancel" id="cEditAmount<?php echo $j; ?>" name="cEditAmount<?php echo $j; ?>" >
							</div>
						</td>
						
						
						<td width='100px'>
							<div id="viewCaharacter<?php echo $j; ?>" name="viewCaharacter" style="display:block; overflow: hidden;">
								<table class="detail" style="width:auto">
									<tr>
										<td><div id="maxcharacterHide<?php echo $j; ?>" name="maxcharacterHide<?php echo $j; ?>"><?php echo $mincharacter?></div></td>
										<td width='100px'><input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit"></td>
									</tr>
								</table>
							</div>
							<div id="editmaxcharacter<?php echo $j; ?>" style="display:none; overflow: hidden; ">
								<input type="text" name="maxcharacter<?php echo $j; ?>" id="maxcharacter<?php echo $j; ?>" value="<?php echo $mincharacter?>" size="2"/>
								<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="smaxcharacter<?php echo $j; ?>" name="smaxcharacter<?php echo $j; ?>" value="0"/> 
								<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancel" id="cmaxcharacter<?php echo $j; ?>" name="cmaxcharacter<?php echo $j; ?>" >
							</div>
						</td>
						<td width='100px'>
							<div id="viewMaxComment<?php echo $j; ?>" name="viewMaxComment" style="display:display; overflow: hidden;">
								<table class="detail" style="width:auto">
									<tr>
										<td><div id="MaxCommentHide<?php echo $j; ?>" name="MaxCommentHide<?php echo $j; ?>"> <?php echo $maxcomment?></div></td>
										<td width='100px'><input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit"></td>
									</tr>
								</table>
							</div>
							<div id="editMaxComment<?php echo $j; ?>" style="display:none; overflow: hidden; ">
								<input type="text" name="MaxComment<?php echo $j; ?>" id="MaxComment<?php echo $j; ?>" value="<?php echo $maxcomment?>" size="2"/>
								<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sMaxComment<?php echo $j; ?>" name="sMaxComment<?php echo $j; ?>" value="0"/> 
								<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancel" id="cEditMaxComment<?php echo $j; ?>" name="cEditMaxComment<?php echo $j; ?>" >
							</div>
						</td>
						<td><input type="hidden" name="Countrycode<?php echo $j; ?>" id="Countrycode<?php echo $j; ?>" value="<?php echo $row['Country']?>" ></td>
					</tr>
			<?php	$i++;
					$j++;
				}	?>
			</tbody>
		</table>
<?php
	} ?>
	<br/><br/>
	<div align='left' class='static'><h1>On-Time Repayment Credits</h1></div>

		<table class='detail'>
			<thead>
				<tr>
					<th>S. No.</th>
					<th>Country</th>
					<th>Credit Amount</th>
				</tr>
			</thead>
			<tbody>
		<?php	$i = 1;
				$j=1;
				foreach($countries as $row)
				{	?>
					<script type="text/javascript">
						$(document).ready(function(){
							var error=0;
							//check for the Minimum Fund a lender can provide
							$("#viewMaxcreditamount<?php echo $j; ?>").click(
								function(event){
									$(this).hide("fast");
									$("#editMaxcreditamount<?php echo $j; ?>").show("fast");
								}
							);
							$("#cEditMaxcreditamount<?php echo $j; ?>").click(
								function(event){
								$("#viewMaxcreditamount<?php echo $j; ?>").show("fast");
								$("#editMaxcreditamount<?php echo $j; ?>").hide("fast");
								}
							);
							
							$("#sMaxcreditamount<?php echo $j; ?>").click(
								function(event){
									var amount=$("#Maxcreditamount<?php echo $j; ?>").val();
									var countrycode=$("#onCountrycode<?php echo $j; ?>").val();
									//var cid=$("#amtcurrencyid").val();
									  var data = "loanamtlimit="+amount+"&country="+countrycode+"&ontimerepaycredit="+'true'+"&type=2";
									$.ajax({
										url: 'process.php',
										type: 'post',
										dataType: 'json',
										data: data,
										success: function() {
											$("#MaxcreditamountHide<?php echo $j; ?>").html(amount);
											$("#viewMaxcreditamount<?php echo $j; ?>").show("fast");
											$("#editMaxcreditamount<?php echo $j; ?>").hide("fast");
										}
									});
								}
							);
						});
					</script>
			<?php
					$credit_setting = $database->getcreditsettingbyCountry($row['Country'],2);
					$maxCredit = $credit_setting['loanamt_limit'];
					if(empty($maxCredit))
						$maxCredit = 0;
					
					
				?>	
					<tr>
						<td width='50px'><?php echo $i?></td>
							<td><?php echo $row['name']?></td>
						<td width='100px'>
							<div id="viewMaxcreditamount<?php echo $j; ?>" name="Maxcreditamount" style="display:display; overflow: hidden;">
								<table class="detail" style="width:auto">
									<tr>
										<td><div id="MaxcreditamountHide<?php echo $j; ?>" name="MaxcreditamountHide<?php echo $j; ?>"> <?php echo $maxCredit?></div></td>
										<td width='100px'><input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit"></td>
									</tr>
								</table>
							</div>
							<div id="editMaxcreditamount<?php echo $j; ?>" style="display:none; overflow: hidden; ">
								<input type="text" name="maxcreditamount<?php echo $j; ?>" id="Maxcreditamount<?php echo $j; ?>" value="<?php echo $maxCredit?>" size="2"/>
								<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sMaxcreditamount<?php echo $j; ?>" name="sMaxcreditamount<?php echo $j; ?>" value="0"/> 
								<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancel" id="cEditMaxcreditamount<?php echo $j; ?>" name="cMaxcreditamount<?php echo $j; ?>" >
							</div>
						</td>
						
						
						
						<td><input type="hidden" name="onCountrycode<?php echo $j; ?>" id="onCountrycode<?php echo $j; ?>" value="<?php echo $row['Country']?>" ></td>
					</tr>
			<?php	$i++;
					$j++;
				}	?>
			</tbody>
		</table>
	<br/><br/>
	<div align='left' class='static'><h1>Borrower Invite Credits</h1></div>

		<table class='detail'>
			<thead>
				<tr>
					<th>S. No.</th>
					<th>Country</th>
					<th>Credit Amount</th>
				</tr>
			</thead>
			<tbody>
		<?php	$i = 1;
				$j=1;
				foreach($countries as $row)
				{	?>
					<script type="text/javascript">
						$(document).ready(function(){
							var error=0;
							//check for the Minimum Fund a lender can provide
							$("#viewinvitecreditamount<?php echo $j; ?>").click(
								function(event){
									$(this).hide("fast");
									$("#editinvitecreditamount<?php echo $j; ?>").show("fast");
								}
							);
							$("#cEditinvitecreditamount<?php echo $j; ?>").click(
								function(event){
								$("#viewinvitecreditamount<?php echo $j; ?>").show("fast");
								$("#editinvitecreditamount<?php echo $j; ?>").hide("fast");
								}
							);
							
							$("#sinvitecreditamount<?php echo $j; ?>").click(
								function(event){
									var amount=$("#invitecreditamount<?php echo $j; ?>").val();
									var countrycode=$("#onCountrycode<?php echo $j; ?>").val();
									//var cid=$("#amtcurrencyid").val();
									  var data = "loanamtlimit="+amount+"&country="+countrycode+"&brwrinvitecredit="+'true'+"&type=3";
									$.ajax({
										url: 'process.php',
										type: 'post',
										dataType: 'json',
										data: data,
										success: function() {
											$("#invitecreditamountHide<?php echo $j; ?>").html(amount);
											$("#viewinvitecreditamount<?php echo $j; ?>").show("fast");
											$("#editinvitecreditamount<?php echo $j; ?>").hide("fast");
										}
									});
								}
							);
						});
					</script>
			<?php
					$credit_setting = $database->getcreditsettingbyCountry($row['Country'],3);
					$maxCredit = $credit_setting['loanamt_limit'];
					if(empty($maxCredit))
						$maxCredit = 0;
					
					
				?>	
					<tr>
						<td width='50px'><?php echo $i?></td>
							<td><?php echo $row['name']?></td>
						<td width='100px'>
							<div id="viewinvitecreditamount<?php echo $j; ?>" name="invitecreditamount" style="display:display; overflow: hidden;">
								<table class="detail" style="width:auto">
									<tr>
										<td><div id="invitecreditamountHide<?php echo $j; ?>" name="invitecreditamountHide<?php echo $j; ?>"> <?php echo $maxCredit?></div></td>
										<td width='100px'><input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit"></td>
									</tr>
								</table>
							</div>
							<div id="editinvitecreditamount<?php echo $j; ?>" style="display:none; overflow: hidden; ">
								<input type="text" name="invitecreditamount<?php echo $j; ?>" id="invitecreditamount<?php echo $j; ?>" value="<?php echo $maxCredit?>" size="2"/>
								<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sinvitecreditamount<?php echo $j; ?>" name="sinvitecreditamount<?php echo $j; ?>" value="0"/> 
								<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancel" id="cEditinvitecreditamount<?php echo $j; ?>" name="cinvitecreditamount<?php echo $j; ?>" >
							</div>
						</td>
						
						
						
						<td><input type="hidden" name="onCountrycode<?php echo $j; ?>" id="onCountrycode<?php echo $j; ?>" value="<?php echo $row['Country']?>" ></td>
					</tr>
			<?php	$i++;
					$j++;
				}	?>
			</tbody>
		</table>
<?php }
else
{
	echo "<div>";
	echo $lang['admin']['allow'];
	echo "<br />";
	echo $lang['admin']['Please'];
	echo "<a href='index.php'>click here</a>".$lang['admin']['for_more']. "<br />";
	echo "</div>";
}	?>
</div>