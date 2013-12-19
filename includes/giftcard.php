
<script type="text/javascript" src="includes/scripts/giftajax.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<script src="includes/scripts/jquery.custom.min.js" type="text/javascript"></script>
<style type="text/css">
	@import url(library/tooltips/btnew.css);
</style>
 <?php include_once("library/session.php");   /*   created by chetan   */

  /* please do not change value of any id  it is recommended   */


 include_once("./editables/admin.php");
 include_once("./editables/giftcard.php");
 $path=	getEditablePath('giftcard.php');
 include_once("./editables/".$path);
$currentDate = time();
$exp_date=date('F j, Y',strtotime('+1 year', $currentDate));
$RequestUrl = $_SERVER['REQUEST_URI'];
if($session->userlevel  == LENDER_LEVEL){
	$redeemUrl = "index.php?p=17";
}
else{
	$redeemUrl = "index.php?p=1&sel=2";
}
 if(1)
{
?>
<div class="span12">
	<input type='hidden' name='exp_date' id='exp_date' value='<?php echo $exp_date; ?>'>
	<!--<div align='right' style='float:right; margin-left:10px;'><a href='gift_card_sample.html' target='_blank'>View Sample Card</a></div> -->
	<div align='left' class='static'><h1><?php echo $lang['giftcard']['gift_cards'] ?></h1></div>
	<!--<div align='left'><i><?php echo $lang['giftcard']['notice'] ?></i></div><br>-->
	<div align='left'> <?php echo $lang['giftcard']['giftcard_text']?><br/><br/><a href="<?php echo $RequestUrl.'#giftcard_terms'; ?>" rel="facebox"><?php echo $lang['giftcard']['terms_use']; ?></a></div>
	<form name='giftform' id='giftform' action='updateprocess.php' method='post'>
	<div id='container' child='1'>
		<div id='form-1' style="margin-top:20px;">
			<div align='center' style='margin-right: 20px; margin-left: 10px; margin-top:10px;font-size:15px'><strong><?php echo $lang['giftcard']['purchase_gift_card'] ?></strong></div><br/>
			<div align='right' style='margin-right: 20px; margin-top:10px;float:right'><a id='preview-1' onClick='javascript:preview(this.id);' style='cursor:pointer'><u><?php echo $lang['giftcard']['preview_gift_card'] ?></u></a></div>
			<table class="detail">
				<tbody>
					<tr>
						<td width='20%'style='padding-left:10px'><label for="giftamt-1"><?php echo $lang['giftcard']['amount'] ?>:</label></td>
						<td>
							<select name="giftamt[]" id="giftamt-1" onChange='javascript:setGiftAmt();'>
								<option value="25">$25</option>
								<option value="50">$50</option>
								<option value="75">$75</option>
								<option value="100">$100</option>
								<option value="500">$500</option>
								<option value="1000">$1000</option>
								<option value="5000">$5000</option>
								<option value="10000">$10000</option>
							</select>
						</td>
					</tr>
					<tr>
						<td style='padding-left:10px'><?php echo $lang['giftcard']['delv_method'] ?>:<br></td>
						<td>
							<div id="tabs-1">
								<input name="email_print_radio-1" id="email_print_radio-1" value="print" checked="checked" type="radio"><?php echo $lang['giftcard']['print'] ?>
								<input name="email_print_radio-1" id="email_print_radio-1" value="email"  type="radio"><?php echo $lang['giftcard']['email'] ?>
							</div>
						</td>
						<td width='10%'><div align='right' id='delete-1' style='margin-right: 20px;'><button class='btn' type="button" id='delete-1' onClick='javascript:deletediv(this.id);'><?php echo $lang['giftcard']['cancel'] ?></button></div></td>
					</tr>
					<tr>
						<td style='padding-left:10px'><label for="recmail-1" id='recmaillbl-1'><?php echo $lang['giftcard']['rec_email'] ?>:<font color='red'>*</font></label></td>
						<td><div style='float:left'><input type="text" name="recmail[]" id="recmail-1" size='30' maxlength="50" value=""/></div><div id="recmailerror-1" style='float:left'></div></td>
					</tr>
					<tr>
						<td style='padding-left:10px'><label for="toName-1"><?php echo $lang['giftcard']['to_optional'] ?>:</label></td>
						<td><input type="text" name="toName[]" id="toName-1" size='30' maxlength="18" value=""/></td>
					</tr>
					<tr>
						<td style='padding-left:10px'><label for="fromName-1"><?php echo $lang['giftcard']['from_optional'] ?>:</label></td>
						<td><input type="text" name="fromName[]" id="fromName-1" size='30' maxlength="18" value=""/></td>
					</tr>
					<tr>
						<td style='padding-left:10px'><label for="msg-1"><?php echo $lang['giftcard']['msg_optional'] ?>:</label></td>
						<td><textarea name="msg[]" id="msg-1" onKeyup='javascript:checkvalue1(this.id);'onKeypress='javascript:checkvalue3(this.id,event);' onblur='javascript:checkvalue2(this.id);' style="max-width:440px;width:440px;height:100px"></textarea></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<div id='remainchar-1' style='margin-left: 290px; margin-top:5px;float:left'>500</div>
							<div style='margin-top:5px;float:left'>&nbsp;<?php echo $lang['giftcard']['chars_remain'] ?></div>
						</td>
					</tr>
					<tr>
						<td style='padding-left:10px'>
							<label for="sendmail-1" style="width:70px"><?php echo $lang['giftcard']['email_optional'] ?>:</label>
							<a style='margin-left:10px;cursor:pointer;position:relative;top:20px' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['giftcard']['send_conf'] ?></span><span class='bottom'></span></span></a>
						</td>
						<td>
							<div style='float:left'><input type="text" size='30' name="sendmail[]" id="sendmail-1" maxlength="50" value=""/></div>
							<div style='float:left' id="sendmailerror-1"></div>
						</td>
					</tr>
				</tbody>
			</table>
			<br><br>
		</div>
	</div>
	<br>
	<div align='center'><a id='add' style='cursor: pointer;'><u><?php echo $lang['giftcard']['add_card'] ?></u></a></div><br>
	<div id='dollar_total' style='float:right'><font color='green'>25.00</font></div>
	<div style='float:right'><font color='green'><?php echo $lang['giftcard']['ord_tot']?>: USD&nbsp;</font></div>
	<div style="clear:both"></div><br/>
	<input type='hidden' name='lastformvalue' id='lastformvalue' value='1'>
	<input type='hidden' name='totalcost' id='totalcost' value='25'>
	<input type='hidden' name='giftcardorder' id='giftcardorder' value=''>
	<input type="hidden" name="user_guess" value="<?php echo generateToken('giftcardorder'); ?>"/>
	<div align='right'><input align='right' class='btn' type='submit' name='submit' value='<?php echo $lang['giftcard']['submit_ord'] ?>' onClick='return ordersubmit()'></div>
	</form>
</div>
<?php
}
else
{
	echo "<div class='span12'>";
	echo $lang['admin']['allow'];
	echo "<br />";
	echo $lang['admin']['Please'];
	echo "<a href='index.php'>click here</a>".$lang['admin']['for_more']. "<br />";
	echo "</div>";
}	?>
<?php 
include_once("./editables/order-tnc.php");
$path=	getEditablePath('order-tnc.php');
include_once("editables/".$path);
?>
<div style="display:none" id="giftcard_terms">
	<div class='instruction_space' style='height: 415px;width: 415px;'>
		<div class='instruction_title'><?php echo $lang['order-tnc']['gift_tnc_heading']; ?>:</div>
		<div class='instruction_text'>
			<ol>
				<li><p><?php echo $lang['order-tnc']['gift_tnc_1']; ?></p></li>
				<li><p><?php echo $lang['order-tnc']['gift_tnc_2']; ?></p></li>
				<li><p><?php echo $lang['order-tnc']['gift_tnc_3']; ?></p></li>
				<li><p><?php echo $lang['order-tnc']['gift_tnc_4']; ?></p></li>
			</ol>
		</div>
	</div>
</div>
</script>
<script language="JavaScript">
	var ids = new Array('giftamt-1','email_print_radio-1','toName-1','fromName-1','msg-1','sendmail-1');
	var values = new Array('','','');
	var needToConfirm = true;
</script>
<script type="text/JavaScript" src="includes/scripts/navigateaway.js"></script>
<script language="JavaScript">
  populateArrays();
</script>