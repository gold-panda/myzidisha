
<script type="text/javascript" src="includes/scripts/giftajax.js?q=<?php echo RANDOM_NUMBER ?>"></script>
<script src="includes/scripts/jquery.custom.min.js" type="text/javascript"></script>
<script type="text/javascript">$(document).ready(function() {
	$(".giftcard_thumbnail label").live("click", function () {
	$(this).parents("div").parents(".giftcard_row").find(".giftcard_thumbnail").removeClass("selected");
  	$(this).parents("div").toggleClass("selected");
});
	$(".giftcard_thumbnail:first").addClass("selected");
});</script>
<style type="text/css">
	@import url(library/tooltips/btnew.css);
</style>
 <?php include_once("library/session.php");   /*   created by chetan   */
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
<div class="span15">
	<input type='hidden' name='exp_date' id='exp_date' value='<?php echo $exp_date; ?>'>
	
	<div align='left' class='static'><h1><?php echo $lang['giftcard']['gift_cards'] ?></h1></div>
	
	<div align='left'><br/><?php echo $lang['giftcard']['giftcard_text']?><br/><br/></div>
	
	<form name='giftform' id='giftform' action='updateprocess.php' method='post'>
	<div id='container' child='1' style="width: auto !important;">
		<div id='form-1' class='giftcard_row' style="margin-top:20px;">
			<div style='margin-right: 20px; margin-left: 10px; margin-top:30px;font-size:20px'>
				<strong>Step One: Select An Image</strong>
			</div><br/><br/>
					<tr>
						<td>
							<div id="giftcard-4-1" class="giftcard_thumbnail">
								<label for="giftcard_template_radio-4-1" ><img src="images/gift_card/image4.png"/></label>
								<input name="giftcard_template_radio-1" id="giftcard_template_radio-4-1" value="image4" checked="checked" type="radio">
							</div>
							<div id="giftcard-3-1" class="giftcard_thumbnail">
								<label for="giftcard_template_radio-3-1" ><img src="images/gift_card/image3.png"/></label>
								<input name="giftcard_template_radio-1" id="giftcard_template_radio-3-1" value="image3" checked="checked" type="radio">
							</div>
							<div id="giftcard-2-1" class="giftcard_thumbnail">
								<label for="giftcard_template_radio-2-1" ><img src="images/gift_card/image2.png"/></label>
								<input name="giftcard_template_radio-1" id="giftcard_template_radio-2-1" value="image2" checked="checked" type="radio">
							</div>
							<div id="giftcard-5-1" class="giftcard_thumbnail">
								<label for="giftcard_template_radio-5-1" ><img src="images/gift_card/image5.png"/></label>
								<input name="giftcard_template_radio-1" id="giftcard_template_radio-5-1" value="image5" checked="checked" type="radio">
							</div>
							<div id="giftcard-6-1" class="giftcard_thumbnail">
								<label for="giftcard_template_radio-6-1" ><img src="images/gift_card/image6.png"/></label>
								<input name="giftcard_template_radio-1" id="giftcard_template_radio-6-1" value="image6" checked="checked" type="radio">
							</div>
							<div id="giftcard-7-1" class="giftcard_thumbnail">
								<label for="giftcard_template_radio-7-1" ><img src="images/gift_card/image7.png"/></label>
								<input name="giftcard_template_radio-1" id="giftcard_template_radio-7-1" value="image7" checked="checked" type="radio">
							</div>
							<div id="giftcard-8-1" class="giftcard_thumbnail">
								<label for="giftcard_template_radio-8-1" ><img src="images/gift_card/image8.png"/></label>
								<input name="giftcard_template_radio-1" id="giftcard_template_radio-8-1" value="image8" checked="checked" type="radio">
							</div>
							<div id="giftcard-9-1" class="giftcard_thumbnail">
								<label for="giftcard_template_radio-9-1" ><img src="images/gift_card/image9.png"/></label>
								<input name="giftcard_template_radio-1" id="giftcard_template_radio-9-1" value="image9" checked="checked" type="radio">
							</div>
							<br style="clear:left;" />
						</td>
						<td width='10%'><div align='right' id='delete-1' style='margin-right: 20px;'><button class='btn' type="button" id='delete-1' onClick='javascript:deletediv(this.id);'><?php echo $lang['giftcard']['cancel'] ?></button></div></td>
					</tr>




			<div style='margin-right: 20px; margin-left: 10px; margin-top:30px;font-size:20px'><strong>
				<br/><br/>Step Two: Customize Gift Card</strong>
			</div><br/>
			<table class="detail">
				<tbody>
					<tr>
						<td width='20%'style='padding-left:10px'><label for="giftamt-1"><?php echo $lang['giftcard']['amount'] ?></label></td>
						<td>
							<br/>
							<select name="giftamt[]" id="giftamt-1" onChange='javascript:setGiftAmt();'>
								<option value="25">$25</option>
								<option value="30">$30</option>
								<option value="50">$50</option>
								<option value="75">$75</option>
								<option value="100">$100</option>
								<option value="150">$150</option>
								<option value="200">$200</option>
								<option value="250">$250</option>
								<option value="300">$300</option>
								<option value="400">$400</option>
								<option value="500">$500</option>
								<option value="1000">$1,000</option>
								<option value="5000">$5,000</option>
								<option value="10000">$10,000</option>
							</select>
						</td>
						<td></td>
					</tr>
					<tr>
						<td style='padding-left:10px'><label for="giftamt-1"><?php echo $lang['giftcard']['delv_method'] ?><br></label></td>
						<td>
							<div id="tabs-1">
								<br/><br/>
								<input name="email_print_radio-1" id="email_print_radio-1" value="print" checked="checked" type="radio"><?php echo $lang['giftcard']['print'] ?>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
								<a id='preview-1' onClick='javascript:preview(this.id);' style='cursor:pointer'><u><?php echo $lang['giftcard']['preview_gift_card'] ?></u></a>
								<br/><br/>
								<input name="email_print_radio-1" id="email_print_radio-1" value="email"  type="radio"><?php echo $lang['giftcard']['email'] ?>
							</div>
						</td>
						<td></td>
					</tr>
					<tr>
						<td style='padding-left:10px'><label for="recmail-1" id='recmaillbl-1'><?php echo $lang['giftcard']['rec_email'] ?>:<font color='red'>*</font></label></td>
						<td><div style='float:left'><input type="text" name="recmail[]" id="recmail-1" size='30' maxlength="50" value=""/></div><div id="recmailerror-1" style='float:left'></div></td>
						<td><!--
							<div align='right' id='delete-1' style='margin-right: 20px;'><button class='btn' type="button" id='delete-1' onClick='javascript:deletediv(this.id);'><?php echo $lang['giftcard']['cancel'] ?></button>
							</div> -->
						</td>
					</tr>
					<tr>
						<td style='padding-left:10px'>
							<label><?php echo $lang['giftcard']['optional'] ?>:</label>
							<strong><br/><br/><br/><?php echo $lang['giftcard']['to_optional'] ?></strong></td>
						<td><br/><br/><br/><br/><input type="text" name="toName[]" id="toName-1" size='30' maxlength="18" value=""/></td>
						<td></td>
					</tr>
					<tr>
						<td style='padding-left:10px'><strong><?php echo $lang['giftcard']['from_optional'] ?></strong></td>
						<td><input type="text" name="fromName[]" id="fromName-1" size='30' maxlength="18" value=""/></td>
						<td></td>
					</tr>
					<tr>
						<td style='padding-left:10px'><strong><?php echo $lang['giftcard']['msg_optional'] ?></strong></td>
						<td colspan="2"><textarea name="msg[]" id="msg-1" onKeyup='javascript:checkvalue1(this.id);'onKeypress='javascript:checkvalue3(this.id,event);' onblur='javascript:checkvalue2(this.id);' style="max-width:440px;width:440px;height:100px"></textarea></td>
						<td></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<div id='remainchar-1' style='margin-left: 290px; margin-top:5px;float:left'>500</div>
							<div style='margin-top:5px;float:left'>&nbsp;<?php echo $lang['giftcard']['chars_remain'] ?></div>
						</td>
						<td></td>
					</tr>
					<tr>
						<td style='padding-left:10px'>
							<strong><?php echo $lang['giftcard']['email_optional'] ?></strong> 
							<a style='cursor:pointer;position:relative' class='tt'><img src='library/tooltips/help.png' style='border-style: none;' /><span class='tooltip'><span class='top'></span><span class='middle'><?php echo $lang['giftcard']['send_conf'] ?></span><span class='bottom'></span></span></a>
						</td>
						<td>
							<div style='float:left'><input type="text" size='30' name="sendmail[]" id="sendmail-1" maxlength="50" value=""/></div>
							<div style='float:left' id="sendmailerror-1"></div>
						</td>
						<td></td>
					</tr>
				</tbody>
			</table>
			<br><br>
		</div>
	</div>
	<br>
	<div align='center'><a id='add' style='cursor: pointer;'><u><?php echo $lang['giftcard']['add_card'] ?></u></a></div><br>
	<div id='dollar_total' style='float:right; margin-right:150px'><font color='green'>25.00</font></div>
	<div style='float:right'><font color='green'><?php echo $lang['giftcard']['ord_tot']?>: USD&nbsp;</font></div>
	<div style="clear:both"></div><br/>
	<input type='hidden' name='lastformvalue' id='lastformvalue' value='1'>
	<input type='hidden' name='totalcost' id='totalcost' value='25'>
	<input type='hidden' name='giftcardorder' id='giftcardorder' value=''>
	<input type="hidden" name="user_guess" value="<?php echo generateToken('giftcardorder'); ?>"/>
	<div style='float:right; margin-right:150px'><input align='right' class='btn' type='submit' name='submit' value='<?php echo $lang['giftcard']['submit_ord'] ?>' onClick='return ordersubmit()'></div>
	</form>
</div>
<?php
}
else
{
	echo "Please log in to continue.
	";
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