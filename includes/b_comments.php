<?php $RequestUrl = $_SERVER['REQUEST_URI'];?>
<script type="text/javascript">
$(document).ready(function() {
	$('a#slick-toggle').click(function() {
		$('#slickbox').slideToggle("slow");
		return false;
	});
	$('#comments button').click(function(){
		var ic  = $('#comments button').index(this);
		var div ='replybox'+ic ;
		$('#'+div).slideToggle("slow");
	});
	$('#user_comment').click(function() {
		$('#user_comment_desc').slideToggle("slow");
		$(this).text($(this).text() == "View More" ? "View Less" : "View More");
		$(this).toggleClass("view-less"); 
	});
	
});
</script>
<style>
.zebra-striped tbody tr td
{
	vertical-align:top;
}
.submitLink {
	background-color: transparent;
	text-decoration: underline;
	border: none;
	cursor: pointer;
	cursor: hand;
	color:#0099FF;
	padding-left:0px;
	margin-left:-3px;
}
</style>
<a name="cnew" ></a>
<a name="e4"></a>
<div id="maincontainer" style="padding-top:10px;">
	<div style="float:left">
		<h3 class="subhead" id='cmnt_heading' style="border-bottom:none;margin-bottom:0px"><?php echo $lang['profile']['comments'] ?></h3>
	</div>
	<div style="float:right">
		<h3 class="subhead" style="border-bottom:none;margin-bottom:0px"><?php echo"<a style='padding-right:20px' id='slick-toggle' href='$RequestUrl#'>".$lang['profile']['wcomments']."</a>";?><p id="user_comment" class="view-more-less">View Less</p></h3>
	</div><br/>
	<?php 

	$vmcurrentloanid= $database->getCurrentLoanid($brw['mentor_id']);
	$params['brwr_name']= $name;
	$params['invitedby']= $invitedby;
	$params['invited_tooltip']=$lang['profile']['tooltip_invited'];
	$params['brwr_name']= $name;
	$params['mentor']= $mentor;
	$params['vm_tooltip']=$lang['profile']['tooltip_mentor'];
			
	if(!empty($invcurrentloanid) && empty($vmcurrentloanid)){

		$vm_comments= $session->formMessage($lang['profile']['invited_text'], $params);

	} elseif(!empty($vmcurrentloanid) && empty($invcurrentloanid)){


	$params['vm_name']= $vm_name;
	$params['vm_url']=$vm_url; 
	$params['vm_tooltip']=$lang['profile']['tooltip_mentor'];
		
		$vm_comments= $session->formMessage($lang['profile']['vm_comment_text'], $params);

	} elseif(!empty($mentor_id) && !empty($invcurrentloanid)){
		
		$vm_comments= $session->formMessage($lang['profile']['inv_vm_text'], $params);	
	
	} else {

		$vm_comments = "";
	}


	?>
	<div style="float:left;padding-bottom: 15px;">
		<?php echo $vm_comments; ?>
	</div>
	<div style="clear:both;border-top:1px solid #DFDCDC">&nbsp;</div>
	<div>
		<div id="slickbox" style="border-bottom:1px solid #DFDCDC;display:none">
<?php		if(isset($session->userid))
			{	?>
				<form name="feedback" method="POST" action='./updatefeedback.php' enctype="multipart/form-data">
					<div style="float:left;width:52%">
						<p><strong><?php echo $lang['profile']['comment'];?></strong></p>
						<div>
							<textarea name="txtcomment" id='txtcomment' style="width:95%;height:140px"></textarea>
						</div>
					</div>
					<div style="float:right;padding-top:30px;width:48%" align="right">
						<p>Upload File1: <input type="file" name="file1[]" id="file11" /></p>
						<p>Upload File2: <input type="file" name="file1[]" id="file12" /></p>
						<p>Upload File3: <input type="file" name="file1[]" id="file13" /></p>
						<p>File should have .jpg, .png or .gif extension.</p>
						<input class="btn" onclick="needToConfirm = false;"  name="Submit" type="submit" value="<?php echo $lang['profile']['cmt_submit'];?>">
						<input type='hidden' name='feedback' />
						<input type='hidden' name='userid' value='<?php echo $ud;?>' />
						<input type='hidden' name='loanid' value='<?php echo $ld;?>' />
						<input type='hidden' name='senderid' value='<?php echo $session->userid;?>' />						
						<input type='hidden' name='MessType' value='Insert' />
						<input type='hidden' name='return' value='<?php echo $page;?>' />
						<input type='hidden' name='backComment' value='cnew' />
						<input type='hidden' name='fb' value='<?php echo $fb;?>' />
					</div>
					<div style="clear:both"></div>
				</form>
				<p>&nbsp;</p>
	<?php	}
			else
			{
				echo "<p><strong>Please log in to post a comment.</strong></p>";
			}	?>
		</div>
		<div id='user_comment_desc'>
			<a name="c-1" ></a>
			<div>
				<!--code for feedBack view (Maximum 3 feedback)-->

		<?php	$result=$database->getDetailByForumId($ud);
				$incr=0;
				if(!isset($fb)) {
					$fb = 0;
				}
				if(!empty($result))
				{
					foreach($result as $forumid)
					{
						$feeddetails=$database->getAllCommentForum($ud,$session->userid,$forumid['forumid'],0,0);
						//set for how many feed back
						$margin=0;
						$cmtIdArr= Array();
						$cmtMar= Array();
						if(!empty($feeddetails))
						{
							$c=0;
							foreach ($feeddetails as $commns)
							{
								if($commns['tr_message']==null || $commns['tr_message']=="")
									$msg1=$commns['message'];
								else
									$msg1=$commns['tr_message'];
								$msgorg1=$commns['message'];
								$cmtIdArr[$c]=$commns['id'];
								if($commns['parentid']==0)
								{
									$margin = 0;
									$cmtMar[$c]=0;
								}
								else
								{
									for($k=0; $k<count($cmtIdArr); $k++)
									{
										if($cmtIdArr[$k]==$commns['parentid'])
										{
											$margin =$cmtMar[$k] + 20;
											$cmtMar[$c]=$margin;
											break;
										}
									}
								}
								$c++;
								$classDepth = 0;
								if(isset($commns['depth'])) {
									$classDepth = $commns['depth'];
								}
								$class = ' child c'.$classDepth;
								if($commns['status']==0)
								{
									$translate_user_name= $database->getUserNameById($commns['tr_user']);
									$translator_level= $database->getUserLevelbyid($commns['tr_user']);
									$translator_loanid= $database->getCurrentLoanid($commns['tr_user']);
									if($translator_level==BORROWER_LEVEL && !empty($translator_loanid)){
										$translator_url= getLoanprofileUrl($commns['tr_user'],$translator_loanid);
									}else{
										$translator_url = getUserProfileUrl($commns['tr_user']);
									}
									echo '<a name="c'.$incr.'" ></a>';
									echo '<a name="cid'.$commns['id'].'" ></a>';
									if(count($feeddetails)>0)
									{	?>
										<div style="margin-left:<?php echo $margin; ?>px" class="post<?php echo $class; ?>">
							<?php	}
									else
									{
										echo "<div>";
									}	?>
									<script type="text/javascript">
										function Deletearow<?php echo $incr; ?>(str)
										{
											var val=confirm('Are you sure you want to delete this comment?');
											if(val)
											{
												document.delform<?php echo $incr; ?>.submit();
											}
										}
									</script>
									<script type="text/javascript">
										$(document).ready(function()
										{
											$('a#slick-toggle<?php echo $incr; ?>').click(function() {
												$('#editbox<?php echo $incr; ?>').hide();
												$('#slickbox<?php echo $incr; ?>').slideToggle("slow");
												//return false;
											});
											$('a#edit-toggle<?php echo $incr; ?>').click(function() {
												$('#slickbox<?php echo $incr; ?>').hide();
												$('#editbox<?php echo $incr; ?>').slideToggle("slow");
												//return false;
											});
											$('#msg1-toggle<?php echo $incr; ?>').click(function() {
												$('#msg1_org<?php echo $incr; ?>').slideToggle("slow");
												var txt = $(this).text();
												if(txt == "<?php echo $lang['profile']['disp_text']; ?>")
													$(this).text("<?php echo $lang['profile']['hide_text']; ?>");
												else
													$(this).text("<?php echo $lang['profile']['disp_text']; ?>");
												//return false;
											});
										});
									</script>
					<?php			$senderid1=$commns['senderid'];
									$imagesrc_comment=$database->getProfileImage($senderid1);
									$receiverid=$commns['receiverid'];
									$level =$database->getUserLevelbyid($senderid1);

									if($level==BORROWER_LEVEL){
										$name12=$database->getNameById($senderid1);
										if (!empty($senderloanid)){
											$sender_url= getLoanprofileUrl($senderid1,$senderloanid);
										}else{
											$sender_url='';
										}
									}else { 
										$sender_url=getUserProfileUrl($senderid1);
										$karma_score = number_format($database->getKarmaScore($senderid1));
										$karma_tooltip = $lang['profile']['karma_tooltip'];						
										$sublevel=$database->getUserSublevelById($senderid1);
										if($sublevel==LENDER_GROUP_LEVEL){
											$name12=$database->getNameById($senderid1);
										}else {
											$name12=$database->getUserNameById($senderid1);
										}

									}?>
									<table class="zebra-striped">
										<tbody>
											<tr>
												<td style="width:100px;border-top:1px solid #DFDCDC;border-bottom:1px solid #DFDCDC;border-left:1px solid #DFDCDC">
													<img style="max-width:100px" src="<?php echo $imagesrc_comment; ?>">
													<p style="margin-top:10px;text-align:center;"><?php if($level==LENDER_LEVEL){
														echo "<a href='$sender_url'>$name12</a><br/><a style='cursor:pointer' class='tt'>($karma_score)<span class='tooltip'><span class='top'></span><span class='middle'>$karma_tooltip</span><span class='bottom'></span></span></a>";
													} else {
														echo "<a href='$sender_url'>$name12</a>";
													} ?>
													</p>
												</td>
												<td style="width:100%;border-top:1px solid #DFDCDC;border-right:1px solid #DFDCDC;border-bottom:1px solid #DFDCDC">
													<strong><?php echo $name12;?></strong>&nbsp;<?php echo $lang['profile']['comments_on'] ?>&nbsp;<?php echo date("M d, Y", $commns['pub_date']);?><br/><br/>
													<?php echo nl2br($msg1);?>
													<br/>
											<?php	$res=$database->getDetailCommentFile($commns['forumid'],$commns['id']);
													echo "<div>";
													foreach($res as $row)
													{
														echo "<div style='width:106px;float:left;padding:10px;text-align:center;'><a href='includes/image.php?imgid=".$row['uploadfile']."' target='_blank'><img src='includes/getcommentupload.php?p=61&imgid=".$row['uploadfile']."&width=96&height=96' /></a><br/><a href='includes/image.php?imgid=".$row['uploadfile']."' target='_blank'>Enlarge</a>";
														
														if(isset($session->userid) && ($session->userid==$senderid1 || $session->userlevel == ADMIN_LEVEL))
														{	?>
															<form action='./updatefeedback.php' method='POST'>
																<input type="submit" class="submitLink" value="Delete">
																<input type='hidden' name='MessType' value='ImgDel'>
																<input type='hidden' name='loanid' value='<?php echo $ld;?>'>
																<input type='hidden' name='userid' value='<?php echo $ud;?>'>
																<input type='hidden' name='imgID' value='<?php echo $row['id'];?>'>
																<input type='hidden' name='ImgFile' value='<?php echo $row['uploadfile'];?>'>
																<input type='hidden' name='receiverid' value='<?php echo $commns['receiverid'];?>'>
																<input type='hidden' name='return' value='<?php echo $page;?>' />
																<input type='hidden' name='backComment' value='c<?php echo ($incr-1);?>' />
																<input type='hidden' name='fb' value='<?php echo $fb;?>' />
															</form>
												<?php	}
														echo "</div>"; //style width:106px
													}
													echo "</div>"; //line 254
													if($msg1 != $msgorg1)
													{
														if(!empty($translate_user_name)){
															echo "<p align='right'><i>".$lang['profile']['translate_by']." <a href='$translator_url' style='font-style: italic;'>".$translate_user_name."</a></i>&nbsp&nbsp&nbsp&nbsp&nbsp<a id='msg1-toggle".$incr."' href='javascript:void(0)'>".$lang['profile']['disp_text']."</a></p>";
														}else{
															echo "<p align='right'><a id='msg1-toggle".$incr."' href='javascript:void(0)'>".$lang['profile']['disp_text']."</a></p>";
														}
													}	?>
													<div id="msg1_org<?php echo $incr;?>" style="border-bottom:1px solid #DFDCDC;display:none">
														<?php echo nl2br($msgorg1);?>
														<p>&nbsp;</p>
													</div>
												
										<?php	if(isset($session->userid))
												{	
													if(!empty($commns['tr_message'])){
														$translate='Edit translation';
													}else{
														$translate='Add translation';
													}?>
												
												<form action='./updatefeedback.php' method='POST'>
												<?php								
													echo "<br/><br/><p><a id='slick-toggle".$incr."' href='$RequestUrl#c".($incr+1)."' style='text-decoration:underline'>Reply</a>												
													&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
													<a style='text-decoration:underline' href='index.php?p=24&c_id=".$commns['id']."&ref=1'>$translate</a>
													&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";										

												if(isset($session->userid) && $session->userid!=$senderid1)
																										{
															if($commns['publish']==1)
															{
																$pulishType="Feature on homepage";
															}
															else
															{
																$pulishType="Remove from homepage";
															}	?>
															
																<input type="submit" class="submitLink" value="<?php echo $pulishType;?>">
																<input type='hidden' name='MessType' value='<?php echo $pulishType;?>'>
																<input type='hidden' name='loanid' value='<?php echo $ld;?>'>
																<input type='hidden' name='userid' value='<?php echo $ud;?>'>
																<input type='hidden' name='PublishID' value='<?php echo $commns['id'];?>'>
																<input type='hidden' name='receiverid' value='<?php echo $commns['receiverid'];?>'>
																<input type='hidden' name='return' value='<?php echo $page;?>' />
																<input type='hidden' name='backComment' value='c<?php echo ($incr-1);?>' />
																<input type='hidden' name='fb' value='<?php echo $fb;?>' />
															</form>
												<?php	}

													if($session->userid==$senderid1 || $session->userlevel == ADMIN_LEVEL)
													{
														$res=$database->getNextDeleteId($commns['forumid'], $commns['id']);
														$deleteValue="Delete";
														if(count($res)>0 && $res==$commns['id'])
														{
															$deleteValue="DeleteReal";
														}	?>
														<a id='edit-toggle<?php echo $incr;?>' href="<?php echo $RequestUrl?>#c<?php echo ($incr+1);?>" style='text-decoration:underline'>Edit</a>
														<form action='./updatefeedback.php' method='POST' name='delform<?php echo $incr;?>'>
															<p><a href='javascript:void(0)' onclick='Deletearow<?php echo $incr;?>(<?php echo $incr;?>)' style='text-decoration:underline;'>Delete</a></p>
															<input type='hidden' name='MessType' value='<?php echo $deleteValue; ?>'>
															<input type='hidden' name='loanid' value='<?php echo $ld;?>'>
															<input type='hidden' name='userid' value='<?php echo $ud;?>'>
															<input type='hidden' name='parentid' value='<?php echo $commns['id'];?>'>
															<input type='hidden' name='forumid' value='<?php echo $commns['forumid'];?>'>
															<input type='hidden' name='receiverid' value='<?php echo $commns['receiverid'];?>'>
															<input type='hidden' name='Senderid1' value='<?php echo $commns['senderid'];?>'>
															<input type='hidden' name='return' value='<?php echo $page;?>' />
															<input type='hidden' name='backComment' value='c<?php echo ($incr-1);?>' />
															<input type='hidden' name='fb' value='<?php echo $fb;?>' />
														</form>
														
												<?php	}	?>
												</td>
										<?php	}	?>
											</tr>
										</tbody>
									</table>
							<?php	if(isset($session->userid))
									{	?>
										<div id="slickbox<?php echo $incr;?>" style="border-bottom:1px solid #DFDCDC;display:none">
											<form name="feedback" method="POST" action='./updatefeedback.php' enctype="multipart/form-data">
												<div style="float:left;width:52%">
													<p><strong><?php echo $lang['profile']['comment'];?></strong></p>
													<div><textarea name="message"  style="width:95%;height:140px"></textarea>
													</div>
												</div>
												<div style="float:right;padding-top:30px;width:48%" align="right">
													<p>Upload File1: <input type="file" name="file1[]" id="file11" /></p>
													<p>Upload File2: <input type="file" name="file1[]" id="file12" /></p>
													<p>Upload File3: <input type="file" name="file1[]" id="file13" /></p>
													<p>File should have .jpg, .png or .gif extension.</p>
													<input class="btn" name="Submit" type="submit" value="Submit">
													<input type='hidden' name='feedback' />
													<input type='hidden' name='subject' value='Re:<?php echo $msgorg1; ?>' />
													<input type='hidden' name='parentid' value='<?php echo $commns['id'];?>' />
													<input type='hidden' name='thread' value='<?php echo $commns['thread'];?>' />
													<input type='hidden' name='forumid' value='<?php echo $commns['forumid'];?>' />
													<input type='hidden' name='receiverid' value='<?php echo $receiverid;?>' />
													<input type='hidden' name='Senderid1' value='<?php echo $session->userid;?>' />
													<input type='hidden' name='return' value='<?php echo $page;?>' />
													<input type='hidden' name='MessType' value='Reply' />
													<input type='hidden' name='loanid' value='<?php echo $ld;?>' />
													<input type='hidden' name='userid' value='<?php echo $ud;?>' />
													<input type='hidden' name='backComment' value='c<?php echo ($incr-1) ?>' />
													<input type='hidden' name='fb' value='<?php echo $fb;?>' />
												</div>
												<div style="clear:both"></div>
											</form>
											<p>&nbsp;</p>
										</div> <!-- slickbox -->
										<div id="editbox<?php echo $incr;?>" style="border-bottom:1px solid #DFDCDC;display:none">
											<form name="feedback" method="POST" action='./updatefeedback.php' enctype="multipart/form-data">
												<div style="float:left;width:52%">
													<p><strong><?php echo $lang['profile']['comment'];?></strong></p>
													<div><textarea name="message" id='editmessage' style="width:95%;height:140px"><?php echo $msgorg1; ?></textarea>
													</div>
												</div>
												<div style="float:right;padding-top:30px;width:48%" align="right">
													<p>Upload File1: <input type="file" name="file1[]" id="file11" /></p>
													<p>Upload File2: <input type="file" name="file1[]" id="file12" /></p>
													<p>Upload File3: <input type="file" name="file1[]" id="file13" /></p>
													<p>File should have .jpg, .png or .gif extension.</p>
													<input class="btn" name="Submit" type="submit" onclick="needToConfirm = false;"  value="Update">
													<input type='hidden' name='feedback' />
													<input type='hidden' name='subject' value='Re:<?php echo $msgorg1; ?>' />
													<input type='hidden' name='parentid' value='<?php echo $commns['id'];?>' />
													<input type='hidden' name='thread' value='<?php echo $commns['thread'];?>' />
													<input type='hidden' name='forumid' value='<?php echo $commns['forumid'];?>' />
													<input type='hidden' name='receiverid' value='<?php echo $receiverid;?>' />
													<input type='hidden' name='Senderid1' value='<?php echo $session->userid;?>' />
													<input type='hidden' name='return' value='<?php echo $page;?>' />
													<input type='hidden' name='MessType' value='Update' />
													<input type='hidden' name='loanid' value='<?php echo $ld;?>' />
													<input type='hidden' name='userid' value='<?php echo $ud;?>' />
													<input type='hidden' name='backComment' value='c<?php echo ($incr-1) ?>' />
													<input type='hidden' name='fb' value='<?php echo $fb;?>' />
												</div>
												<div style="clear:both"></div>
											</form>
											<p>&nbsp;</p>
										</div> <!-- editbox -->
							<?php	}	?>
								</div>
						<?php	}
								$incr++;
							}
						}
						else
						{
							echo"";
						}
					}
				}
				else
				{
					echo"";
				}	?>
			</div> <!-- /div on line 115 -->
		</div> <!-- /user_comment_desc -->
	</div> <!-- /div on line 78 -->
</div> <!-- /maincontainer -->