<h3 class="subhead">Recent Comments On My Loans<p id="user_comments" class="view-more-less view-less">View More</p></h3>
<div id="maincontainer" style="padding-top:10px;">
	<div>

		<div id='user_comment'>
			<div>
		<?php	$comments=$database->getAllCommentForLender($session->userid);
				$margin=0;
				$cmtIdArr= Array();
				$cmtMar= Array();
				if(!empty($comments))
				{
					$c=0;
					$i = 0;
					foreach ($comments as $commns)
					{ if($i < 3) {  //added to show initial three comments by default
						$i++;
						$userid=$commns['senderid'];
						$borrowerid=$commns['receiverid'];
						$loanid = $database->getUNClosedLoanid($borrowerid);
						$prurl = getUserProfileUrl($borrowerid);
						$loanprurl = getLoanprofileUrl($borrowerid, $loanid);
						$url = $prurl."#cid".$commns['id'];
						if($loanid != 0)
							$url = $loanprurl."#cid".$commns['id'];
						$usermessage=$commns['message'];
						if(!empty($commns['tr_message']))
							$usermessage=$commns['tr_message'];
						if(strlen($usermessage) >350)
						{
							$usermessage=substr($usermessage,0,350);
							$pos1= strrpos ($usermessage , ' ');
							if($pos1 !==false) {
								$usermessage=substr($usermessage,0,$pos1)."....\" <a href='".$url."'>Read More</a>";
							}
						}

						$date = $commns['pub_date'];
						$level =$database->getUserLevelbyid($userid);
						if($level==BORROWER_LEVEL || $level==PARTNER_LEVEL)
							$name13=$database->getNameById($userid);
						else{
							$sublevel=$database->getUserSublevelById($userid);
							if($sublevel==LENDER_GROUP_LEVEL)
								$name13=$database->getNameById($userid);
							else 
								$name13=$database->getUserNameById($userid);
						}
						
						$user_citycountry=$database->getUserCityCountry($userid);
						$u_city=$user_citycountry['City'];
						$u_country=$user_citycountry['Country'];
						$u_country=$database->mysetCountry($u_country);
						
					?>
						<table class="zebra-striped">
							<tbody>
								<tr>
									<td style="width:200px">
										<a href='<?php echo $url?>'><img src="library/getimagenew.php?id=<?php echo $userid;?>&width=60&height=60"></a>
									</td>
									<td style="width:100%;">
										<?php echo $usermessage;?>
										<br/><br/>
										<p class="meta"><?php echo $lang['home']['posted_by'] ?> <a class="meta" href='<?php echo $url?>'><strong><?php echo $name13;?></strong></a> <?php echo $lang['home']['in'] ?> <strong><?php echo $u_city.", ".$u_country;?></strong> <?php echo $lang['home']['on'] ?> <?php echo date("d F Y", $date);?></p>
									</td>
								</tr>
							</tbody>
						</table>
			<?php		}
					}
				}	?>
			</div>
		</div>

		<div id='user_comment_desc' style="display:none">
			<div>
		<?php	$margin=0;
				$cmtIdArr= Array();
				$cmtMar= Array();
				if(!empty($comments))
				{
					$c=0;
					$i = 0;
					foreach ($comments as $commns)
					{ 	$i++;
						if($i > 3) {  //added to show rest comments except initial three comments.
					
						$userid=$commns['senderid'];
						$borrowerid=$commns['receiverid'];
						$loanid = $database->getUNClosedLoanid($borrowerid);
						$prurl = getUserProfileUrl($borrowerid);
						$url = $prurl."#cid".$commns['id'];
						$loanprurl = getLoanprofileUrl($borrowerid, $loanid);
						if($loanid != 0)
							$url = $loanprurl."#cid".$commns['id'];
						$usermessage=$commns['message'];
						if(!empty($commns['tr_message']))
							$usermessage=$commns['tr_message'];
						if(strlen($usermessage) >350)
						{
							$usermessage=substr($usermessage,0,350);
							$pos1= strrpos ($usermessage , ' ');
							if($pos1 !==false) {
								$usermessage=substr($usermessage,0,$pos1)."....\" <a href='".$url."'>Read More</a>";
							}
						}

						$date = $commns['pub_date'];
						$level =$database->getUserLevelbyid($userid);
						if($level==BORROWER_LEVEL || $level==PARTNER_LEVEL)
							$name13=$database->getNameById($userid);
						else{
							$sublevel=$database->getUserSublevelById($userid);
							if($sublevel==LENDER_GROUP_LEVEL)
								$name13=$database->getNameById($userid);
							else 
								$name13=$database->getUserNameById($userid);
						}
						
						$user_citycountry=$database->getUserCityCountry($userid);
						$u_city=$user_citycountry['City'];
						$u_country=$user_citycountry['Country'];
						$u_country=$database->mysetCountry($u_country);
						
					?>
						<table class="zebra-striped">
							<tbody>
								<tr>
									<td style="width:200px">
										<a href='<?php echo $url?>'><img src="library/getimagenew.php?id=<?php echo $userid;?>&width=60&height=60"></a>
									</td>
									<td style="width:100%;">
										<?php echo $usermessage;?>
										<br/><br/>
										<p class="meta"><?php echo $lang['home']['posted_by'] ?> <a class="meta" href='<?php echo $url?>'><strong><?php echo $name13;?></strong></a> <?php echo $lang['home']['in'] ?> <strong><?php echo $u_city.", ".$u_country;?></strong> <?php echo $lang['home']['on'] ?> <?php echo date("d F Y", $date);?></p>
									</td>
								</tr>
							</tbody>
						</table>
			<?php		}
					}
				}	?>
			</div>
		</div>
	</div>
</div>