<div class="span12">
	
	<div id="static" style="text-align:justify">
		<h1>Comments on My Loans</h1>
		<br/>
	</div>

	<div id="maincontainer" style="padding-top:10px;">

		<div id='user_comment'>
			
		<?php	
				$comments=$database->getAllCommentForLender($session->userid);
				$margin=0;
				$cmtIdArr= Array();
				$cmtMar= Array();
				if(!empty($comments))
				{
					$c=0;
					$i = 0;
					foreach ($comments as $commns)
					{ 
						$i++;
						$userid=$commns['senderid'];
						$borrowerid=$commns['receiverid'];
						$bname = $database->getNameById($borrowerid);
						$loanid = $database->getUNClosedLoanid($borrowerid);
						$loanprurl = getLoanprofileUrl($borrowerid, $loanid);
						$usermessage=$commns['message'];
						if(!empty($commns['tr_message']))
							$usermessage=$commns['tr_message'];
	
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
										<a href='<?php echo $loanprurl?>'><img src="library/getimagenew.php?id=<?php echo $userid;?>&width=200&height=200"></a>
									</td>
									<td style="width:100%;">
										<?php echo $usermessage;?>
										<br/><br/>
										<p class="meta">

										<?php

										if ($userid != $borrowerid){ 

											echo $lang['home']['posted_at']." <a class='meta' href='".$loanprurl."'><strong>".$bname."</strong></a> ".$lang['home']['by']." ".$name13." ".$lang['home']['in']." <strong>".$u_city.", ".$u_country."</strong> ".$lang['home']['on']." ".date("d F Y", $date);	
											
										} else { 

											echo $lang['home']['posted_by']." <a class='meta' href='".$loanprurl."'><strong>".$name13."</strong></a> ".$lang['home']['in']." <strong>".$u_city.", ".$u_country."</strong> ".$lang['home']['on']." ".date("d F Y", $date);
											
										}

										?>
										</p>
									</td>
								</tr>
							</tbody>
						</table>
			<?php		
					}
				} else {
				echo"Your comment feed is empty.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				echo"<strong><a href='microfinance/lend.html' style='font-size:16px'>Make a Loan</a></strong>";
			}?>
			
		</div>
	</div>
</div>