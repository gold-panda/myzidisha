<?php
include_once("editables/registrationfee.php");
include_once("library/session.php");
include_once("./editables/admin.php");
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
	<h4><?php echo $lang['registration']['reg_fee_current'] ?></h4>
	<form method="post" action="process.php">
		<table class='detail'>
			<tbody>
				<tr>
					<td><strong><?php echo $lang['registration']['select_currency'];?></strong></td>
					<td>
						<select name="currency" id="currency">
							<option value="sel" selected="true" >Select Currency</option>
					<?php	$currency=$database->getAllCurrency(1);
							$tempcurrency=$form->value("currency");
							if(!empty($tempcurrency))
								$currencysel=$tempcurrency;
							if(!empty($currency))
							{
								foreach($currency as $currencyrow)
								{ 
									$value=$currencyrow['id'].'#'.$currencyrow['currencyname'].'#'.$currencyrow['Currency'];
									?>
									<option value="<?php echo $value; ?>" <?php if($currencysel==$value)echo "Selected='true'"; ?>><?php echo $currencyrow['currencyname']." in ".$currencyrow['country'];?></option>
					<?php		}
							} ?>
						</select>						
					</td>
					<td><?php echo $form->error("currency"); ?></td>
				</tr>
				<tr>
					<td width='145px'><strong><?php echo $lang['registration']['amount'];?></strong></td>
					<td>
						<input  type="text" maxlength="10" name="amount" value='<?php echo $form->value("amount"); ?>' />
						<input type="hidden" name="amt_entered" />
						<input type="hidden" name="user_guess" value="<?php echo generateToken('amt_entered'); ?>"/>
						<input class='btn' type="submit" value=<?php echo $lang['registration']['save'];?> />
					</td>
					<td><?php echo $form->error("amount"); ?></td>
				</tr>
			</tbody>
		</table>
	</form>
<?php
	$set=$database->getRegistrationFeeData();
	if(empty($set))
	{
		echo "There are no data in  set for the website<br /><br />";
	}
	else
	{	?>
		
		<table class='detail'>
			<thead>
				<tr>
					<th>S. No.</th>
					<th><?php echo $lang['registration']['currency']; ?></th>
					<th><?php echo $lang['registration']['amount']; ?></th>
				</tr>
			</thead>
			<tbody>
		<?php	$i = 1;
				$j=1;
				foreach($set as $row)
				{	?>
					<script type="text/javascript">
						$(document).ready(function(){
						var error=0;
						//check for the Minimum Fund a lender can provide
						$("#viewMinAmount<?php echo $j; ?>").click(
						function(event){
						$(this).hide("fast");
						$("#editMinAmount<?php echo $j; ?>").show("fast");
						}
						);
						$("#cEditMinAmount<?php echo $j; ?>").click(
						function(event){
						$("#viewMinAmount<?php echo $j; ?>").show("fast");
						$("#editMinAmount<?php echo $j; ?>").hide("fast");
						}
						);
						$("#sEditMinAmount<?php echo $j; ?>").click(
						function(event){
						var un=$("#mamount<?php echo $j; ?>").val();
						var cid=$("#amtcurrencyid").val();
						$.get("includes/saveRegistrationfee.php?k=<?php echo $j;?>&amtcurrencyid=<?php echo																	$row['currency_id']; ?>",{mamount<?php echo $j; ?>:un},
						function(data){
						if(data == 0){
						$("#mamountHide<?php echo $j; ?>").html (un);
						$("#viewMinAmount<?php echo $j; ?>").show("fast");
						$("#editMinAmount<?php echo $j; ?>").hide("fast");
						}
						else{
						}
						}
						);
						}
						);
						});
					</script>
			<?php
					$cid=$row['currency_id'];
					$currency=$row['currency'];
					$currencyname=$row['currency_name'];
					$amt=$row['Amount'];
				?>	
					<tr>
						<td><?php echo $i?></td>
							<td><?php echo $currency?></td>
						<td>
							<div id="viewMinAmount<?php echo $j; ?>" name="viewMinAmount" style="display:display; overflow: hidden;">
								<table>
									<tr>
										<td><div id="mamountHide<?php echo $j; ?>" name="mamountHide<?php echo $j; ?>"> <?php echo $database->getEditAmount($cid);?></div></td>
										<td><input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit"></td>
									</tr>
								</table>
							</div>
							<div id="editMinAmount<?php echo $j; ?>" style="display:none; overflow: hidden; ">
								<input type="text" name="mamount<?php echo $j; ?>" id="mamount<?php echo $j; ?>" value="<?php  echo $database->getEditAmount($cid); ?>" size="2"/>
								<input type="hidden" name="amtcurrencyid" value="<?php echo $cid;?>" />
								<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditMinAmount<?php echo $j; ?>" name="sEditMinAmount<?php echo $j; ?>" value="<?php echo $database->getEditAmount($cid);?>"/> 
								<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditMinAmount<?php echo $j; ?>" name="cEditMinAmount<?php echo $j; ?>" >
							</div>
						</td>
					</tr>
			<?php	$i++;
					$j++;
				}	?>
			</tbody>
		</table>
<?php
	}
}
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