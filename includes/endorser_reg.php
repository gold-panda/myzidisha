<?php
	include_once("./editables/register.php");
	$path=	getEditablePath('register.php');
	include_once("./editables/".$path);
	$validation_code='';
	$showShareBox=0;
	$fbmsg_hide=0;
	$web_acc=0;
	$fb_fail_reason='Endorser Registration : '.isset($_SESSION['FB_Fail_Reason']) ? $_SESSION['FB_Fail_Reason'] : '';
	if(isset($_GET['vd'])){
		$validation_code= $_GET['vd'];
		$borrowerid=$database->getBorrowerOfEndorserByCode($validation_code);
		$bname=$database->getNameById($borrowerid);
	}
	if(!empty($form->values)){
		$_SESSION['fb_data']=$form->values;
	}
	if(isset($_REQUEST['fb_data'])){ 
		if(isset($_SESSION['fb_data'])){
			$form->values= $_SESSION['fb_data']; 
		}
	}
	$fb_reload=false;
	if(isset($_REQUEST['code'])){
		$fb_reload=true;
		echo"<script type='text/javascript'>window.close();
		window.opener.location.reload();
		</script>";
	}
	if(isset($_REQUEST['error_reason'])){
		$fb_reload=true;
		echo"<script type='text/javascript'>window.close();
		window.opener.location.reload();</script>";
	}
	if(isset($_REQUEST['fb_disconnect'])) { 
		$session->facebook_disconnect();	
		$fb_reload=true;
		echo"<script type='text/javascript'>window.close();window.opener.location.reload();</script>";
	} 
	if($fb_reload) {
		echo"<script type='text/javascript'>
		var url = window.location.href;
		url += '\/#FB_cntct\/';
		window.location.href = url;
		</script>";
	}
	if(isset($_SESSION['FB_Detail']) && !isset($_SESSION['hide_fbmsg'])){
		$web_acc=1;
		$fb_fail_reason='Endorser Registration Ok : '.isset($_SESSION['FB_Fail_Reason']) ? $_SESSION['FB_Fail_Reason'] : '';
	}
	if(isset($_SESSION['hide_fbmsg'])){
		$fbmsg_hide=1;
		unset($_SESSION['hide_fbmsg']);
	}

	if(isset($_SESSION['endored_already'])){
		echo "<div align='center'><font color=green><strong>You have already Endorsed.</strong></font></div><br/>";
		unset($_SESSION['endored_already']);
	}
?>

<div class="span12">
<div id="static"><h1>Endorsement Form</h1></div><br/>
<?php 
	$params['bname'] = $bname;
	$text = $session->formMessage($lang['register']['endorser_instruction'], $params);
	echo $text."<br/><br/>";
	$about_borrower= $session->formMessage($lang['register']['e_know_brwr'], $params);
	$confident_brwr=$session->formMessage($lang['register']['e_cnfdnt_brwr'], $params);
	$displayperm=$session->formMessage($lang['register']['e_candisplay'], $params);
?>

<div class="row">
	<form enctype="multipart/form-data" id="sub-Endorser" name="sub-Endorser" method="post" action="process.php">
		<table class="detail">
			<tbody>
		<!--		<tr>
					<td><?php echo $lang['register']['Country']?><a id="bcountryerr"></a></td>
					<td>
						<select id="bcountry" name="bcountry" onchange="needToConfirm = false;submitform1();">
							<option value='0'>Select Country</option>
					<?php	$result1 = $database->countryList(true);
							$i=0;
							foreach($result1 as $state)
							{	?>
								<option value='<?php echo $state['code'] ?>' <?php if($form->value("bcountry")==$state['code']) echo "selected" ?>><?php echo $state['name'] ?></option>
					<?php	}	?>
						</select>
						<br/><?php echo $form->error("bcountry"); ?>
					</td>
				</tr>
				 <tr><td>&nbsp;</td></tr>-->
				 <tr>
					<td><div id="fb_instruction"><?php echo $lang['register']['endorser_fb']; ?></div>
					<?php echo $form->error("cntct_type"); ?>
					</td>
					<td>
					<?php $fbData=$session->facebook_connect($borrowerid); // $borrowerid added by mohit to check ip of existing endorser on date 28-10-13
							if(!empty($fbData['user_profile']['id'])){
							$database->saveFacebookInfo($fbData['user_profile']['id'], serialize($fbData), $web_acc, 0, '', $fb_fail_reason);
						}
						if($fbData['loginUrl']==''){ 
							$showShareBox=1;
							if(isset($_REQUEST['fb_join']) || $_SESSION['FB_Error']!=false){ 
								$showShareBox=0;
							}?>
							<a class="facebook-auth" href="javascript:void()" id="FB_cntct_button" onclick="needToConfirm = false;javascript:login_popup('<?php echo $fbData['logoutUrl']?>');return false;" ><img src="images/f_disconnect.jpg"/></a>
						<?php }else{?>
							<a class="facebook-auth" href="javascript:void()" id="FB_cntct_button" onclick="needToConfirm = false;javascript:login_popup('<?php echo $fbData['loginUrl']?>');"><img src="images/facebook-connect.png"/></a>
					<?php }?>
					</td>
				</tr>
				<tr>
				<td><?php 
					if(isset($_SESSION['FB_Detail']) && $fbmsg_hide!=1){
						echo "<div align='center'><font color=green><strong>Your Facebook account is now linked to Zidisha.</strong></font></div><br/>";
					}
					if(isset($_SESSION['FB_Error'])){
						echo $_SESSION['FB_Error'];
					}?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				 <tr>
					<td><?php echo $lang['register']['b_fname'];?><a id="bfnameerr"></a></td>
					<td><input type="text" name="bfname" id='bfname' maxlength="25" class="inputcmmn-1" value="<?php echo $form->value("bfname"); ?>"/><br/><?php echo $form->error("bfname"); ?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['b_lname'];?><a id="blnameerr"></a></td>
					<td><input type="text" name="blname" id="blname" maxlength="25" class="inputcmmn-1" value="<?php echo $form->value("blname"); ?>"/><br/><?php echo $form->error("blname"); ?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
			<!--	 <tr>
					<td><?php echo $lang['register']['paddress'];?><a id="bpostadderr"></a></td>
					<td><textarea name="bpostadd" id='bpostadd' class="textareacmmn" ><?php echo $form->value("bpostadd"); ?></textarea><br/><?php echo $form->error("bpostadd"); ?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				 <tr>
					<td><?php echo $lang['register']['home_no'];?><a id="home_noerr"></a></td>
					<td><textarea name="home_no" id='home_no' class="textareacmmn" ><?php echo $form->value("home_no"); ?></textarea><br/><?php echo $form->error("home_no"); ?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['City'];?><a id="bcityerr"></a></td>
					<td><input type="text" name="bcity" id="bcity" maxlength="50" class="inputcmmn-1" value="<?php echo $form->value("bcity"); ?>"/><br/><?php echo $form->error("bcity"); ?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				 <tr>
					<td><?php echo $lang['register']['nationid'];?><a id="bnationiderr"></a></td>
					<td><input type="text" name="bnationid" id='bnationid' maxlength="50" class="inputcmmn-1" value="<?php echo $form->value("bnationid"); ?>" /><br/><?php echo $form->error("bnationid"); ?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>-->
				 <tr>
					<td><?php echo $lang['register']['endorser_email'];?><a id="bemailerr"></a></td>
					<td><input type="text" id="bemail" name="bemail" maxlength="50" class="inputcmmn-1"  value="<?php echo $form->value("bemail"); ?>" /><br/><div id="emailerror"><?php echo $form->error("bemail"); ?></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				 <tr>
					<td><?php echo $lang['register']['endorser_tele_no'];?><a id="bmobileerr"></a></td>
					<td><input type="text" id="bmobile" name="bmobile" maxlength="15" class="inputcmmn-1" value="<?php echo $form->value("bmobile"); ?>" /><br/><div id="mobileerror"><?php echo $form->error("bmobile"); ?></div></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $about_borrower;?><a id="babouterr"></a></td>
					<td><textarea name="babout" id='babout' class="textareacmmn" style="height:130px;"><?php echo $form->value("babout"); ?></textarea><br/>
					<div id="babout_err"><?php echo $form->error("babout"); ?></div></td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $confident_brwr;?><a id="bconfdnterr"></a></td>
					<td><textarea name="bconfdnt" id='bconfdnt' class="textareacmmn" style="height:130px;"><?php echo $form->value("bconfdnt"); ?></textarea><br/>
					<div id="bconfdnt_err"><?php echo $form->error("bconfdnt"); ?></div></td>
				</tr>

<!---added by Julia 13-11-2013 to allow optional public display, hidden on 23-11-2013 to make public display no longer optional for endorsements submitted after today 

				<tr>


					<td><br/><br/><?php echo $displayperm;?>
</td>
					<td>

-->

						<div style="display: none"><INPUT TYPE="Radio" id="e_candisplay" name="e_candisplay" value="0" tabindex="3" checked/><?php echo $lang['register']['yes'];?> &nbsp; &nbsp; &nbsp; &nbsp;
						<INPUT TYPE="Radio" name="e_candisplay" value="1" tabindex="4"  /><?php echo $lang['register']['no'];?></div>

<!--
					</td>
				</tr>

-->
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td>
						<?php echo $lang['register']['endorser_uname'];?>
						<a name="busernameerr" id="busernameerr"></a>
					</td>
					<td><input type="text" id="busername" name="busername" maxlength="100" class="inputcmmn-1" value="<?php echo $form->value("busername"); ?>" /><br/><div id="bunerror"><?php echo $form->error("busername"); ?></div></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				 <tr>
					<td><?php echo $lang['register']['ppassword'];?><a id="bpass1err"></a></td>
					<td><input type="password" id="bpass1" name="bpass1" class="inputcmmn-1" value="<?php echo $form->value("bpass1"); ?>" /><br/><div id="passerror"><?php echo $form->error("bpass1"); ?></div></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr>
					<td><?php echo $lang['register']['CPassword'];?></td>
					<td><input type="password" id="bpass2" name="bpass2" class="inputcmmn-1" value="<?php echo $form->value("bpass2"); ?>"/></td>
				</tr>
				 <tr><td>&nbsp;</td></tr>
				<tr style='height:20px;'><td></td></tr>
				<tr> 
					<td style="text-align:center;">
						<input type="hidden" name="reg-endorser" />
						<input type="hidden" name="before_fb_data" id="before_fb_data" />
						<input type="hidden" name="validation_code" value= "<?php echo $validation_code; ?>"/>
						<input type="hidden" name="fb_data" id="fb_data" value='<?php echo urlencode(addslashes(serialize($fbData))); ?>'/>
						<input type="submit" name='submitform'  id='endorsersubmitform' class="btn" value="<?php echo $lang['register']['endorser_save'];?>" onclick="needToConfirm = false;"/>
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>
</div>
<?php 
$bidMessage="You have successfully linked your Facebook account.";
$post_link= SITE_URL;
$share_msg= "Just joined Zidisha.";
$sharephoto= SITE_URL."images/fb_logo.jpg"; 
$short_url = "https%3A%2F%2Fwww.zidisha.org";
?>
<div id="shareForm" style="display:none">
		<div style="width:100%"  align="center">
			<div align="center" id="container">
				<div id="top_strip"></div><!--top_strip closed -->
				<div id="mid_strip">
					<div id="containt">
						<div id="upper" class="padding_prop" style="padding-top:5px;">
							<div class="left">
								<div class="thankyou_text left">Thank you!</div>
								<div class="upper_text right"><?php echo $bidMessage; ?></div>
								<div class="clear"></div>
							</div><!--left closed -->
							<!--right closed -->
							<div class="clear"></div>
						</div><!--upper closed -->
						<div id="lower" class="padding_prop">
							<div>
								<div class="left news_text">Now Share The News</div>
								<div class="left" style="padding-top:5px;">
									<div class="block2 shareTab1 <?php if(!isset($_SESSION['shareEmailValidate'])) { echo 'tab2'; } ?>" onClick="showBox(1);" style="cursor:pointer">
										<div  class="tab_space2" align="center">
											<img src="images/layout/popup/fb.png" border="0" />
									   </div>
									</div>
									<div class="block shareTab2" onClick="showBox(2);"  style="cursor:pointer">
										<div  class="tab_space" align="center">
											<img src="images/layout/popup/twitter.png"  border="0"/>
										</div>
									</div>
									<div  class="block shareTab3 <?php if(isset($_SESSION['shareEmailValidate'])) { echo 'tab'; } ?>" onClick="showBox(3);"  style="cursor:pointer">
										<div  class="tab_space" align="center">
											<img src="images/layout/popup/mail.png" />
										</div>
									</div>
								</div>
								<div class="clear"></div>
							</div>
							<div id="slant">&nbsp;</div><!--slant closed -->
							<div id="data">
								<div class="left testi">
									<div class="black_small_text"><?php echo $share_msg; ?></div>
									<div class="link_text"><a href="<?php echo SITE_URL;?>">www.zidisha.org</a></div>
									<div align="left" id="bubble">
										<div class="space">
											<div class="left">
												<img src="images/logo.png"/>
											</div>
											<div class="left testi_text">
												<span><?php echo $share_msg; ?></span>
												&nbsp;<span class="link_text" style="font-style:normal;"><a target="_blank" class="link_text" href='<?php echo $post_link?>'>More</a></span>
											</div>
											<div class="clear"></div>
										</div><!--space closed -->
									</div><!--bubble closed -->
								</div><!--testi closed -->
								<div class="right form">
									<div class="shareTab1Detail" style="<?php if(isset($_SESSION['shareEmailValidate'])) { echo 'display:none'; } ?>">
										<div style="padding-top:20px">
											<a href="javascript:void(0)" onClick="fbshare();"><img src="images/layout/popup/fb_button.png" /></a>
										</div>
										<div style="padding-top:63px;width:200px;text-align:right">
											<a href="javascript:void(0)" onclick="$.facebox.close();" class=''>
											<img src="images/layout/popup/nothanks_button.png" /></a>
										</div>
									</div>
									<div class="shareTab2Detail" style="display:none">
										<table class="form_text" align="center" cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td valign="top" class="paddign_right_prop">NOW SHARE THE NEWS</td>
											</tr>
											<tr>
												<td style="padding-top:5px;"> 
													<textarea id="twtTextShare" name="twtTextShare" class="textarea_box2  textarea_text twtTextShare"><?php echo $share_msg; ?></textarea>
												</td>
											</tr>
											<tr>
												<td align="center" style="padding-top:10px;">
													<a href="javascript:void(0)" onClick="twtshare();"><img src="images/layout/popup/tweet_button.png" /></a>
												</td>
											</tr>
										</table>
									</div>
									<div class="shareTab3Detail" style="<?php if(!isset($_SESSION['shareEmailValidate']) || isset($_SESSION['ShareEmailSent'])) {echo 'display:none';} ?>">
										<form name="bidform1" action="process.php" method="post">
											<table class="form_text" align="center" cellpadding="0" cellspacing="0" width="100%">
												<tr>
													<td valign="top" class="paddign_right_prop">TO</td>
													<td>
														<input name="to_email" type="text" class="input_box" value="<?php echo $form->value('to_email'); ?>"/>
														<div><?php echo $form->error('to_email'); ?></div>
													</td>
												</tr>
												<tr><td class="top_padding_prop">&nbsp;</td></tr>
												<tr>
													<td valign="top" class="paddign_right_prop">NOTE</td>
													<td>
														<textarea name="note" class="textarea_box"><?php echo $form->value('note'); ?></textarea>
														<div><?php echo $form->error('note'); ?></div>
													</td>
												</tr>
												<tr>
													<td>&nbsp;</td>
													<td style="padding-top:10px;">
														<div>
															<div class="left">
																<div class="left"><input name="sendme" type="checkbox" /></div>
																<div class="right" style="font-family:Arial;margin-top:3px;">Send me a copy</div>
																<div class="clear"></div>
															</div>
															<div class="right" style="margin-right:5px;">
																<input type="hidden" id="sendJoinEmail" name="sendJoinEmail" />
																<input type="hidden" name="user_guess" value="<?php echo generateToken('sendJoinEmail'); ?>"/>
																<input type="hidden" name="formbidpos" value="<?php echo $showShareBox ?>" />
																<input type="hidden" name="email_sub" value="<?php echo $share_msg ?>" />
																<?php unset($_SESSION['shareEmailValidate']);?>
																<input type="image" src="images/layout/popup/send_button.png" name="Send"/>
															</div>
															<div class="clear"></div>
														</div>
													</td>
												</tr>
											</table>
										</form>
									</div>
									<div class='mail_sent_section' >
											<?php 
													if(isset($_SESSION['ShareEmailSent'])){ 
													echo"<div class='black_text_emailSent'>";
														echo"Your email has been sent.";
														unset($_SESSION['ShareEmailSent']);
													echo"</div>";
													echo"<div style='margin-top:30px'><input type='image' src='images/layout/popup/send_another_button.png' name='Send Again' onClick='showBox(3)' value=' '></div>";
												}?>
									</div>
								</div><!--form closed -->
								<div class="clear"></div><!--clear closed -->
							</div><!--data closed -->
						</div><!--lower closed -->
					</div><!--containt closed -->
				</div><!--mid_strip closed -->
				<div id="bottom_strip"></div><!--bottom_strip closed -->
			</div><!--container closed -->
		</div>		
	</div>
<script language="JavaScript">
	var ids = new Array('busername', 'bpass1', 'bpass2','bfname','blname','bpostadd','bcity','bcountry','bnationid','bemail','bmobile', 'agree');
	var values = new Array('','','','','','','','','','');
	var needToConfirm = true;

</script>
<script type="text/JavaScript" src="includes/scripts/navigateaway.js"></script>
<script language="JavaScript">
  populateArrays();

   
</script><script language="JavaScript">
	var newwindow;
	function login_popup(url) { 
	 var  screenX    = typeof window.screenX != 'undefined' ? window.screenX : window.screenLeft,
	 screenY    = typeof window.screenY != 'undefined' ? window.screenY : window.screenTop,
	 outerWidth = typeof window.outerWidth != 'undefined' ? window.outerWidth : document.body.clientWidth,
	 outerHeight = typeof window.outerHeight != 'undefined' ? window.outerHeight : (document.body.clientHeight - 22),
	 width    = 500,
	 height   = 500,
	 left     = parseInt(screenX + ((outerWidth - width) / 2), 10),
	 top_win  = parseInt(screenY + ((outerHeight - height) / 2.5), 10),
	 features = (
	  'width=' + width +
	  ',height=' + height +
	  ',left=' + left +
	  ',top=' + top_win+
	  ',scroll=yes'
	 );
	 if(url=='fs' || url=='undefined') {
	  newwindow=window.open('login/','',features);
	  } else {
	  newwindow=window.open(url,'',features);
	 }
	 if (window.focus) {
	  newwindow.focus()
	 }
	 return false;
	}
</script>
<script>
if(<?php echo $showShareBox; ?>){
	jQuery.facebox({ div: '#shareForm' });
}
function showBox(box)
{
	if(box==1) {
		$('.shareTab1').addClass('tab2');
		$('.shareTab2').removeClass('tab');
		$('.shareTab3').removeClass('tab');
		$('.shareTab1Detail').show();
		$('.shareTab2Detail').hide();
		$('.shareTab3Detail').hide();
		$('.mail_sent_section').hide();
	}
	else if(box==2) {
		$('.shareTab2').addClass('tab');
		$('.shareTab3').removeClass('tab');
		$('.shareTab1').removeClass('tab2');
		$('.shareTab2Detail').show();
		$('.shareTab3Detail').hide();
		$('.shareTab1Detail').hide();
		$('.mail_sent_section').hide();
	}
	else {
		$('.shareTab3').addClass('tab');
		$('.shareTab1').removeClass('tab2');
		$('.shareTab2').removeClass('tab');
		$('.shareTab3Detail').show();
		$('.shareTab1Detail').hide();
		$('.shareTab2Detail').hide();
		$('.mail_sent_section').hide();
	}
	
}
var twtUrl= "<?php echo $post_link; ?>";
function fbshare()
{
	var fburl="http://www.facebook.com/sharer.php?s=100&p[title]=<?php echo urlencode($share_msg);?>&p[url]=<?php echo $short_url; ?>&p[images][0]=<?php echo $sharephoto; ?>";
	window.open(fburl,'','width=600,height=450,left=200,top=200');
}
function twtshare()
{
	$('.twtTextShare').each(function(){
	   twttext = $(this).val();
	});
	var twitterParams = { 
		url: twtUrl, 
		text: twttext 
	}; 
	var twturl="http://twitter.com/share?" + $.param(twitterParams);
	window.open(twturl,'','width=600,height=450,left=200,top=200');
}
function submitform1()
{ 
	document.getElementById("before_fb_data").value = "1";
	document.forms["sub-Endorser"].submit();
}
</script>
<div id='ResidentialaddrExample' style="display:none">
<div class="instruction_space" style="margin-top:10px;height:160px">
	<div class="instruction_text"><?php echo $lang['register']['addresexample']?></div>
</div>
</div>