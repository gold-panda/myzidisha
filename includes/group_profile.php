<?php
include_once("library/session.php");
include_once("./editables/admin.php");
$showShareBox=0;
?>
<?php
	$gid = 0;
	if(isset($_GET['gid'])) {
		$gid = $_GET['gid'];
	}
	$userid=$session->userid;
	$grpdetails = $database->getlendingGrouops($gid);
	$gname = $grpdetails['name'];
	$gwebsite = $grpdetails['website'];
	$grpimg = urlencode($grpdetails['image']);
	$abt_grp = stripslashes($grpdetails['about_grp']);
	$grp_leader= $grpdetails['grp_leader'];
	
?>
<div class='span12'>
<div style="float:right"><a href="index.php?p=80">Back to Lending Groups</a></div>

<?php 
if(isset($_SESSION['usernotloggedin'])) {
		echo"<div style='text-align:center;font-size:16px;color:green;'><strong>Please <a href='javascript:void' onclick='getloginfocus()'>log in</a> to join this group</a></strong>.</div>";
		unset($_SESSION['usernotloggedin']);
} ?>
<?php if(isset($_SESSION['alreadyjoined'])) {
		echo"<div style='text-align:center;font-size:16px;color:red;'><strong>You are already a member of this group</a></strong>.</div>";
		unset($_SESSION['alreadyjoined']);
} ?>
<?php if(isset($_SESSION['grp_joined'])) {
		echo"<div style='text-align:center;font-size:16px;color:green;'><strong>Congratulations! You are now a member of the $gname Lending Group.</a></strong></div>";
		$showShareBox=1;
		$bidMessage="You are now a member of the $gname Lending Group.";
		$share_msg= "Just joined the $gname Lending Group at Zidisha.";
		unset($_SESSION['grp_joined']);
} ?>
<!-- comment by Julia 25-10-2013
<?php if(isset($_SESSION['notlender'])) {
		echo"<div style='text-align:center;font-size:16px;color:red;'><strong>You are not allowed to join this group</a></strong>.</div>";
		unset($_SESSION['notlender']);
} ?>
-->
<?php if(isset($_SESSION['grp_leaved'])) {
		echo"<div style='text-align:center;font-size:16px;color:green;'><strong>You have successfully left the $gname Lending Group.</a></strong></div>";
		unset($_SESSION['grp_leaved']);
} ?>
<?php if(isset($_SESSION['groupcreated'])) {
		echo"<div style='text-align:center;font-size:16px;color:green;'><strong>Congratulations!
You have successfully created the $gname Lending Group.</a></strong></div>";
		$showShareBox=1;
		$bidMessage="You have successfully created the $gname Lending Group.";
		$share_msg= "Just created the $gname lending group at Zidisha.";
		unset($_SESSION['groupcreated']);
} ?>
<?php if(isset($_SESSION['leadernotselected'])) {
		echo"<div style='text-align:center;font-size:16px;color:red;'><strong>Please transfer leadership before leaving this group.</a></strong></div>";
		unset($_SESSION['leadernotselected']);
} ?>
<?php if(isset($_SESSION['updategroup'])) {
		echo"<div style='text-align:center;font-size:16px;color:green;'><strong>Your changes have been saved.</a></strong></div>";
		unset($_SESSION['updategroup']);
} ?><br/>
	<h3 STYLE="DISPLAY:INLINE"><?php echo $gname?></h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($grp_leader===$session->userid){ 
		echo '<a href="index.php?p=83&gid='.$gid.'">Edit Group</a>'; 
		
	}
	?>

<!-- share box added by Julia 25-10-2013 -->


<?php 
	$post_link= "https%3A%2F%2Fwww.zidisha.org%2Findex.php?p=82&gid=$gid";
	$sharephoto= SITE_URL."images/fb_logo.jpg"; 
	$short_url = "https%3A%2F%2Fwww.zidisha.org%2Findex.php?p=82&gid=$gid";
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
										<div align="left" id="bubble">
											<div class="space">

												<div class="left testi_text">
													<span><?php echo $share_msg; ?><br/><br/></span>
													&nbsp;<span class="link_text" style="font-style:normal;"><a target="_blank" class="link_text" href='<?php echo $post_link?>'>View Group</a></span>
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
															<textarea name="note" class="textarea_box"><?php echo $form->value('note'); ?>

View Group: https://www.zidisha.org/index.php?p=82&gid=<?php echo $gid?></textarea>
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
	  function checkreferal(country) { 
		  var v_array = [ "<?php if(isset($countries[0])) echo $countries[0] ?>" , "<?php if(isset($countries[1])) echo $countries[1]?>", "<?php if(isset($countries[2])) echo $countries[2]?>", "<?php if(isset($countries[3])) echo $countries[3]?>"];
			
			if($.inArray(country,v_array) !=-1) {
				document.getElementById("referrerRow").style.display = '';
			}else {
				document.getElementById("referrerRow").style.display = 'none';

			}
	  }
	function checkeligibility(id) {
		if (id=='debtfree') {
			if(!document.getElementById('repaidpast').checked) {
				document.getElementById('repaidpast_err').innerHTML='<font color="red">You must be able to answer \"yes"\ to the eligibility questions in order to complete this form.</font>';
				//alert('You must be able to answer \"yes"\ to the eligibility questions in order to complete this form.')
			//document.getElementById('repaidpast').checked=true;
			}else {
				document.getElementById('repaidpast_err').innerHTML='';
				document.getElementById('debtfree_err').innerHTML='';
			}
		}
		else if (id=='share_update') {
			if(!document.getElementById('repaidpast').checked) {
				document.getElementById('repaidpast_err').innerHTML='<font color="red">You must be able to answer \"yes"\ to the eligibility questions in order to complete this form.</font>';

			//document.getElementById('repaidpast').checked=true;
			}else if(!document.getElementById('debtfree').checked) {
				document.getElementById('debtfree_err').innerHTML='<font color="red">You must be able to answer \"yes"\ to the eligibility questions in order to complete this form.</font>';
			}else {
				document.getElementById('repaidpast_err').innerHTML='';
				document.getElementById('debtfree_err').innerHTML='';
			}
		} else if(id=='repaidpast') {
			if(document.getElementById('repaidpast').checked) {
				document.getElementById('repaidpast_err').innerHTML='';
			}
		}
	}

	function validateborwrReg() {
		needToConfirm = false;
		var adresspr = document.getElementById('address_proof').value;
		var Frntid = document.getElementById('front_national_id').value;
		var legaldec = document.getElementById('legal_declaration').value;
		var bphoto = document.getElementById('bphoto').value;
		var ret = true;
		var errurl='';
			if(!legaldec || 0 === legaldec.length) {
			<?php if(empty($islgldecl)) { ?>
			document.getElementById('legal_declaration_err').innerHTML='<font color="red">Please upload a copy of the Legal Contract.</font>';
			//alert('Please upload a copy of the Recommendation Form.')
			errurl = "index.php?p=1&sel=1&lang="+"#legal_declaration_err";
			ret = false;
			<?php } ?>
		}else {
			document.getElementById('legal_declaration_err').innerHTML='';
		}


		if(!adresspr || 0 === adresspr.length) {
			<?php if(empty($isaddrprf)) { ?>
				document.getElementById('address_proof_err').innerHTML='<font color="red">Please upload a copy of the Recommendation Form.</font>';
				//alert('Please upload a copy of the Recommendation Form.')
				errurl = "index.php?p=1&sel=1&lang="+"#address_proof_err";
				ret = false;
			<?php }?>
		}else {
			document.getElementById('address_proof_err').innerHTML='';
		}


		if(!Frntid || 0 === Frntid.length) {
			<?php if(empty($isFrntNatid)) { ?>
				document.getElementById('front_national_id_err').innerHTML='<font color="red">Please upload a copy of the front of your national identification card.</font>';
				ret = false;
				errurl = "index.php?p=1&sel=1&lang="+"#front_national_id_err";
			<?php } ?>
			//alert('Please upload a copy of the front of your national identification card.')
			
		}else {
				document.getElementById('front_national_id_err').innerHTML='';
		}



		if(!bphoto || 0 === bphoto.length) {
			<?php if(empty($isPhoto_select)) { ?>
				document.getElementById('bphoto_err').innerHTML='<font color="red">Please upload a photo.</font>';
				errurl = "index.php?p=1&sel=1&lang="+"#bphoto_err";
				ret = false;
			<?php }?>
			//alert('Please upload a copy of the Recommendation Form.')
			
		}else {
			document.getElementById('bphoto_err').innerHTML='';
		}

		if(!document.getElementById('repaidpast').checked || !document.getElementById('debtfree').checked || !document.getElementById('share_update').checked) {
			document.getElementById('share_update_err').innerHTML='<font color="red">You must be able to answer \"yes"\ to the eligibility questions in order to complete this form.</font>';
				//alert('You must be able to answer \"yes"\ to the eligibility questions in order to complete this form.')
				errurl = "index.php?p=1&sel=1&lang="+"#share_update_err";
				ret = false;
			//document.getElementById('repaidpast').checked=true;
		}else {
			document.getElementById('share_update_err').innerHTML='';
		}

		window.location = errurl;
		return ret;
	}

	  $('#bcountry').bind('change', function(event) {
		
		var countrycode = $('#bcountry').val();
		var data = "activeborrowers="+countrycode;
		$.ajax({
			url: 'process.php',
			type: 'post',
			//dataType: 'json',
			data: data,
			success: function(data, textStatus) {
				$("#refer_member").html(data);
			}
			//alert(data);
		});
	  });

	  $('#bcountry').bind('change', function(event) {
		
		var countrycode = $('#bcountry').val();
		var data = "volunteer_mentor="+countrycode;
		$.ajax({
			url: 'process.php',
			type: 'post',
			//dataType: 'json',
			data: data,
			success: function(data, textStatus) {
				$("#volunteer_mentor").html(data);
			}
			//alert(data);
		});
	  });

	  function open_contact(value){
		  if(value!=1){
			$('.facebook-auth').hide();
			document.getElementById('tele_contacts').style.display='';
			document.getElementById('FB_cntct_button').style.display='none';
			document.getElementById('fb_instruction').style.display='none';
			document.getElementById('endorser').style.display='none';
		  }else{
			$('.facebook-auth').show();
			document.getElementById('tele_contacts').style.display='none';
			document.getElementById('FB_cntct_button').style.display='';
			document.getElementById('fb_instruction').style.display='';
			document.getElementById('endorser').style.display='';
		  }
	  }
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
	</script>

<!-- end share box -->


	<div>
		<?php if (file_exists(USER_IMAGE_DIR.$grpimg)){ ?> 
		<div style="float:right">
			<img src = <?php echo SITE_URL."images/client/".$grpimg?> width='330px'	>
		</div>
		<?php } ?> 
		<div style="float:left">
			<table class="detail">
				<tbody>	
					<tr height="30px"></tr>
					<tr>
						<td style='width:50px'><strong>Website:</strong></td>
						<?php 
							$website = $gwebsite;
							$parsed_url = parse_url($gwebsite);
							if(!isset($parsed_url['scheme'])) {
								$website = "http://" . $gwebsite;
							  }	
						?>
						<td><a href='<?php echo $website?>' target='_blank'><?php echo $gwebsite;?></a></td>
					</tr>
					<tr height="20px"></tr>
					<tr>
						<td><strong>About Group:</strong></td>
						<td style="width:200px;"><?php echo nl2br($abt_grp);?></td>
					</tr>
					<!-- <tr>
						<td><strong><?php echo $lang['profile']['date_active'] ?>:</strong></td>
						<td><?php echo $activedate;?></td>
					</tr> -->

		<?php 
			$disablejoin = '';
			$members = $database->getLendingGroupMembers($gid);
			$investedamt = 0;
			$active_investamtDisplay = 0;
			foreach($members as $member) {
				$mids[] = $member['member_id'];
				$investedamt += $database->totalAmountLend($member['member_id']);
				$active_investamtDisplay += $database->amountInActiveBidsDisplay($member['member_id']);
			}
			$grpTotalImpact = 0;
			if(!empty($mids)) {
				$ids = implode(',', $mids);
				$gImpact = $database->getGroupImpact($ids);
				$grpTotalImpact = number_format($active_investamtDisplay + $investedamt + $gImpact['invite_AmtLent']+$gImpact['Giftrecp_AmtLent'], 2, '.', ',');
			}
			
				?><tr height="20px"></tr>
				<tr>
					<td style="width:150px"><strong>Total Group Impact:</strong></td>
					<td>USD <?php echo $grpTotalImpact?></td>
				</tr>
					</tbody>
			</table>
<br/><br/><br/>
			<form method="post" action="process.php">
				<input type="hidden" name="groupid" value="<?php echo $gid?>">
			<?php $ismemberofgroup = $database->IsmemberOfGroup($session->userid, $gid);
			if(!$ismemberofgroup) {
				 echo"<input type='hidden' name='joinLendingGroup'>";
				 echo"<input type='submit' class='btn' value='Join this Group'>";
			} else {
					if(count($members) > 2 && $session->userid == $grp_leader){
						$leavthigrp = "<a href='includes/leavegroup.php?gid=$gid' rel='facebox' class='btn'>LEAVE THIS GROUP</a>";
					}else {
						$leavthigrp = "<input type='submit' class='btn' value='Leave This Group'>";
					}
				?>
				<input type='hidden' value='1' name='leavegroup'>
				<?php echo $leavthigrp; ?>
				<?php }?>
			</form>
		

		</div>

		<div style="clear:both"></div>
				<br/>
				<br/>
				<?php if(!empty($members )) { 
				echo"<table class='detail'>";
				echo "<h3 class='subhead'>Members</h3>";
				foreach($members as $member) {
					$memberid = $member['member_id'];
					$username = $member['username'];
					// Anupam 10-Jan-2013 we are no more showing total individual impact in lending groups as per 'Quick repair: Lending team impacts' email
					/*$impact = $database->getMyImpact($memberid);
					$total_invested=$database->totalAmountLend($memberid);
					$active_investamtDisplay = $database->amountInActiveBidsDisplay($memberid);
					$totlat_impact = number_format($active_investamtDisplay + $total_invested + $impact['invite_AmtLent']+$impact['Giftrecp_AmtLent'], 2, '.', ',');*/
					$groupleader='';
					$trnferleader='';
					$prurl = getUserProfileUrl($memberid);
					if($member['member_id']==$grp_leader) {
						$groupleader = ' (Group Leader)';
					}
					if($session->userid == $grp_leader && $grp_leader == $member['member_id']) {
						$trnferleader = "<a href='includes/transffer_leader.php?gid=$gid' rel='facebox'>Transfer Leadership</a>";	
					}
					echo"<tr><td width='200px'>
								<a href='$prurl' target='_blank'>$username</a>$groupleader
							</td>
							<td>
								$trnferleader
							</td>
						</tr>";		
				}
		?>
		</table>
		<?php } ?>

	</div>
<div style='margin-top:15px;'>
<br/><br/>
	Would you like to receive email notifications when new messages are posted at the <?php echo $gname?> Lending Group's Message Board?
	<?php 
		$grpmsgnotify = $database->getlendergroupnotify($session->userid,$gid);
		
	?>
	<input type="radio" value='<?php echo $gid?>' name="GroupmsgBoardNotification" id='GroupmsgNotify_yes' <?php if($grpmsgnotify!='0') echo 'checked';?>>Yes
	<input type="radio" value='0' name="GroupmsgBoardNotification" id='GroupmsgNotify_no' <?php if($grpmsgnotify=='0') echo 'checked';
	?>>No
	&nbsp;&nbsp;&nbsp;&nbsp;<span id='response' ></span>
</div>
<br/><br/>
<?php 
		$fb=0;

			include_once("./editables/profile.php");
			$path=	getEditablePath('profile.php');
			include_once("editables/".$path);
			include_once("includes/group_comments.php");
?>
</div>

<script type="text/javascript">
	<!--
		function getloginfocus() {
			document.getElementById("username").focus();
		}
	
	//-->
	</script>
<script type="text/javascript">
<!--
$(document).ready(function(){
	$("#GroupmsgNotify_yes").click(
		function(event){
			var value=$("#GroupmsgNotify_yes").val();
			var userid="<?php echo $session->userid; ?>";
			document.getElementById("response").innerHTML="<img src='images/layout/icons/ajax-loader.gif' border='0' alt=''>";
			var data = "value="+value+"&userid="+userid+"&GroupmsgNotify="+'true'+"&grpid="+"<?php echo $gid?>";
			$.ajax({
				url: 'process.php',
				type: 'post',
				dataType: 'json',
				data: data,
				success: function() {
					
					document.getElementById("response").innerHTML="<font color=green>saved</font>";
				}
			});
		}
	);
	$("#GroupmsgNotify_no").click(
		function(event){
			var value=$("#GroupmsgNotify_no").val();
			var userid="<?php echo $session->userid; ?>";
			var data = "value="+value+"&userid="+userid+"&GroupmsgNotify="+'true'+"&grpid="+"<?php echo $gid?>";
			document.getElementById("response").innerHTML="<img src='images/layout/icons/ajax-loader.gif' border='0' alt=''>";
			$.ajax({
				url: 'process.php',
				type: 'post',
				dataType: 'json',
				data: data,
				success: function() {
					document.getElementById("response").innerHTML="<font color=green>saved</font>";
				}
			});
		}
	);
});
//-->
</script>