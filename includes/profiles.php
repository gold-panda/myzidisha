<?php 
	include_once("library/session.php");
	include_once("editables/profiles.php");
?><html>
<head>
<style type="text/css"> 
	@import url(library/tooltips/btnew.css);
</style>
<script type="text/javascript" src="scripts\jquery.js"></script>
<script type="text/javascript">
	$(document).ready(function() {			
	$('#my_prof_heading').click(function() {
			$('#my_prof_desc').slideToggle("slow");
			$(this).toggleClass("active"); return false;
	});
	$('#busy_desc').click(function() {
			$('#msg_desc').slideToggle("slow");
			$(this).toggleClass("active"); return false;
	});
	$('#crnt_loan_heading').click(function() {
			$('#crnt_loan_desc').slideToggle("slow");
	});
	$('#cmnt_heading').click(function() {
			$('#cmnt_desc').slideToggle("slow");
			$(this).toggleClass("active"); return false;
	});
	$('#part_prof_heading').click(function() {
			$('#part_prof_desc').slideToggle("slow");
			$(this).toggleClass("active"); return false;
	});
	$('#abt_org').click(function() {
			$('#abt_org_desc').slideToggle("slow");	
			var txt = $(this).text();			
			if(txt == "<?php echo $lang['profile']['disp_text']; ?>")
				$(this).html("<b><font color='blue'><?php echo $lang['profile']['hide_text']; ?></font></b>");
			else
				$(this).html("<b><font color='blue'><?php echo $lang['profile']['disp_text']; ?></font></b>");
	});
	$('td#busi_desc_org').click(function() {
			$('#busi_desc_org_desc').slideToggle("slow");
			var txt = $(this).text();			
			if(txt == "<?php echo $lang['profile']['disp_text']; ?>")
				$(this).html("<b><font color='blue'><?php echo $lang['profile']['hide_text']; ?></font></b>");
			else
				$(this).html("<b><font color='blue'><?php echo $lang['profile']['disp_text']; ?></font></b>");
	});	
	$('#abt_org_desc').hide();
	$('#busi_desc_org_desc').hide();	
	});
</script>
</head>
</html>
<?php
	$activeuser = 0;
	if($session->userlevel==ADMIN_LEVEL)
		$activeuser = 1;
	else if($session->userlevel==LENDER_LEVEL)
	{
		$userid=$session->userid;
		$res=$database->isTranslator($userid);
		if($res==1)
			$activeuser = 1;
	}
	$rightuser = 0;
	if($session->userlevel==ADMIN_LEVEL)
		$rightuser = 1;
	else if($session->userlevel==PARTNER_LEVEL)	
		$rightuser = 1;
	
	$feedback=array(2=>'Favourable',3=>'Neutral',4=>' Un Favourable',);
	$yesno= array(1=>'Yes',0=>'No');
	$getuid=$_GET['u'];
	$ld=$_GET['l'];
	
	$presentid=$session->userid;//working user's id
	$displyall=0;
	/*Error handling related code*/
	if(isset($_GET['err']) && $_GET['err'] != 0){
		include_once("error.php");
		echo "<table width='80%' bgcolor='red' align='center'><tr align='center'><td>";
		echo "<font color='white'>".$errorArray[$_GET['err']]."</font>";
		echo "</td></tr></table>";
	}
	if($presentid==$getuid)
	{
		$displyall=1;
	}
	
	$getulevel=$database->getUserLevelbyid($getuid);

	if(!empty($getuid)){
		if($getulevel==PARTNER_LEVEL){
			//$pid=$session->userid;
			$pid=$getuid;
			$pdetails=$database->getPartnerDetails($pid);
			$data=$pdetails;
			$name=$data['name'];
			$postadd=$data['PostAddress'];
			$email=$data['email'];
			$city=$data['City'];
			$country=$data['Country'];
			$website=$data['Website'];
			$desc=nl2br($data['Description']);
			$username=$data['username'];
			$active=$data['Active'];
			$activedate=$data['activedate'];
			
			if(!empty($activedate)){
				$activedate=date("M d, Y", $activedate);
			}else{
				$activedate="InActive";
			}
?>

			<div id="maincontainer">
				<div id="left_prof">
					<div align="center" style="margin-right: 7px; margin-left: 25px; margin-top: 12px;">
						<table border=0 width=100%>
							<tr width=100%>
								<td colspan=2>
									<h3 class='arrow' id='my_prof_heading'><?php echo $lang['profile']['profile'];?></h3>
									<div>
										<img src="./images/front_page/line.png" align="left" border="0">
									</div>
								</td>
							</tr>
							</table>
							<div id='my_prof_desc'>
							<table border=0 width=100% class="tablecss">
							<tr>
								<td style="width:30%">
									<b><?php echo $lang['profile']['Name'];?></b>
								</td>
								<td><?php echo $name; if($displyall){?>
								</td>
							</tr>
							<tr>
								<td>
									<b><?php echo $lang['profile']['Post_Add'];?></b>
								</td>
								<td><?php echo $postadd; }?>
								</td>
							</tr>
							<tr>
								<td>
									<b><?php echo $lang['profile']['Email'];?></b>
								</td>
								<td>
									<?php echo $email;?>
								</td>
							</tr>
							<tr>
								<td>
									<b><?php echo $lang['profile']['City'];?></b>
								</td>
								<td>
									<?php echo $city ?>
								</td>
							</tr>
							<tr>
								<td>
									<b><?php echo $lang['profile']['Country'];?></b>
								</td>
								<td>
									<?php echo $database->mysetCountry($country) ?>
								</td>
							</tr>
							<tr>
								<td>
									<b><?php echo $lang['profile']['website'];?></b>
								</td>
								<td>
									<?php echo $website ?>
								</td>
							</tr>
							<tr>
								<td>
									<b><?php echo $lang['profile']['active'];?></b>
								</td>
								<td>
									<?php if ($active==0){
												echo "No";
											}
											else{
												echo "Yes";
											} ?>
								</td>
							</tr>
							<tr>
								<td>
									<b><?php echo $lang['profile']['date_active'];?></b> 
								</td>
								<td>
									<?php echo $activedate; ?>
								</td>
							</tr>						
						</table>
							</div>
					</div>
				</div>
				<div id="right_prof">
					<div>
						<div align="center" style="margin-right: 7px; margin-left: 30px; margin-top: 12px;">
							<table border=0 width=100%>
								<tr width=100%>
									<td>
									</td>
								</tr>
								<tr width=100% height=100>
									<td>
										<div align="center">
											<div >
												<img src="library/getimagenew.php?id=<?php echo $pid;?>&width=150&height=150">
											</div>
										</div> 
									</td>
								</tr>
								<tr>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>

			<div id="maincontainer">
				<div id="left_fdb">
					<div align="center" style="margin-right: 17px; margin-left: 17px; margin-top: 7px;">
					 <table border=0 width=100%>
						<tr>
							<td>
								<b><h3><?php echo $lang['profile']['discp'];?></h3></b>
								<div>
									<img src="./images/front_page/line2.png" align="left" border="0">
								</div>
							</td>
						</tr>								
						<tr>
							<td>
								<?php echo $desc; ?>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div style="clear: both;">
			</div>
		
			<?php
				if($displyall){ 
			?>
			<div id="maincontainer">
				<div id="left_fdb">
					<div align="center" style="margin-right: 17px; margin-left: 17px; margin-top: 7px;">
						<table border=0 width=100%>
							<tr>
								<td colspan=2>
									<b><h3><?php echo $lang['profile']['loan_detail'];?></h3></b>
									<div>
										<img src="./images/front_page/line2.png" align="left" border="0">
									</div>
								</td>
							</tr>								
							<tr>
								<td style="width:40%">
									<b><?php echo $lang['profile']['user_name'];?></b>
								</td>
								<td>
									<?php echo $username; ?>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<?php
				}
			}
			else if($getulevel==LENDER_LEVEL)
			{
				$id=$getuid;
				$data=$database->getLenderDetails($id);
				$fname=$data['FirstName'];
				$lname=$data['LastName'];
				$name=$fname.' '.$lname;
				
				$lusername=$data['username'];
                $email=$data['Email'];
                $city=$data['City'];
				$country=$data['Country'];
				if($data['tr_About']==null || $data['tr_About']=="")
					$desc=nl2br($data['About']);
				else
					$desc=nl2br($data['tr_About']);
				
				$username=$data['username'];
				$hideamt=$data['hide_Amount'];//=0 means show the total amount lend
				
				$photo=$data['PhotoPath'];
				if(empty($hideamt))
					$totallendamt=$database->totalAmountLend($id);
			?>

			<div id="maincontainer">
				<div id="left_prof">
					<div align="center" style="margin-right: 7px; margin-top: 12px;">
						<table border=0 width=100% align="left">
							<tr width=100% align="left">
								<td colspan=2>
									<h3><?php echo $lang['profile']['lender_detail'];?></h3>
									<div>
										<img src="./images/front_page/line.png" align="left" border="0">
									</div>
								</td>
							</tr>
							<tr align="left">
								<td style="width:30%">
									<b><?php echo $lang['profile']['user_name'];?></b>
								</td>

								<td>
									<?php echo $lusername;?>
								</td>
							</tr>

							<tr align="left">
								<td>
									<b><?php echo $lang['profile']['City']; ?></b>
								</td>
								<td>
									<?php echo $city;?>
								</td>
							</tr>
                        
						    <tr align="left">
								<td>
									<b><?php echo $lang['profile']['Country'];?></b>
								</td>
								<td>
									<?php $country = $database->mysetCountry($country);echo $country;?>
								</td>
							</tr>

							
						</table>
					</div>
				</div>
				<div id="right_prof" style="width: 45%">
					<div>
						<div align="center" style="margin-right: 7px; margin-left: 30px; margin-top: 12px;">
							<table border=0 width=100%>
								<tr width=100% height=100>
									<td>
										<div align="center">
											<img src="library/getimagenew.php?id=<?php echo $id;?>&width=150&height=150">
										</div>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
				<div style="clear: both;">
				</div>
			</div>
			<div id="maincontainer">
				<div id="left_fdb" style="margin-left: 0px;">
					<div align="left" style="margin-right: 17px; margin-top: 7px;">
						<table border=0 width=100%>
							<tr>
								<td>
									<b><h3><?php echo $lang['profile']['discp'];?></h3></b>
									<div>
										<img src="./images/front_page/line2.png" align="left" border="0">
									</div>
								</td>
							</tr>								
							<tr>
								<td align="left">
									<?php echo $desc; ?>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div style="clear: both;">
				</div>
			</div>
			<div id="maincontainer" style="margin-top: 12px;">
		<?php if(empty($hideamt))
			  {
				echo "<h3>".$lang['profile']['act_bid']."</h3>";
				$amt = $database->getTransaction($session->userid,0);
				if(isset($amt) && $amt != 0 && $amt != '')
					echo "";
				else
					echo "".$lang['payment']['no_t_y']."" ;

				echo "<div>";
				$lenderbidstatus = $database->getLenderBids($getuid);
				if($lenderbidstatus)
				{ 
			?>
					<table cellspacing="1" width="99%" class="tablewithoutsorter">
						<thead>
							<tr>                    
								<th width='15%' style='text-align:center'><?php echo $lang['profile']['biddate'];?> </th>
								<th width='35%' style='text-align:center'><?php echo $lang['profile']['borrower_name'];?> </th>
								<th width='20%' style='text-align:center'><?php echo $lang['profile']['usd_amt_bid'];?></th> 
								<th width='20%' style='text-align:center'><?php echo $lang['profile']['bid_status'];?></th>
							</tr>
						</thead>
						<tbody>
				<?php
						$tot_val_act_bids= 0;
						foreach( $lenderbidstatus as $rows )
						{
							$firstname=$rows['FirstName'];
							$lastname=$rows['LastName'];
							$city = $rows['city'];
							$country = $rows['country'];
							$country = $database->mysetCountry($rows['country']);
							$borrowerid=$rows['userid'];
							$loan_id=$rows['loanid'];
							$bidamt=$rows['bidamount'];
							$biddate=$rows['biddate'];	
							$bidstatus=$rows['bidstatus'];
							if($bidstatus == 1)
							{
								$bidstatus_desc = "Active";
								$tot_val_act_bids += $bidamt;
							}
							else if($bidstatus == 2)
							{
								$bidstatus_desc = "Bid down to USD ".number_format($rows['bidamt_acpt'], 2, '.', ',')."";
								$tot_val_act_bids += $rows['bidamt_acpt'];
							}
							else if($bidstatus == 3)
								$bidstatus_desc = "Outbid";
							else
								$bidstatus_desc = "";
							
							echo '<tr>';	
								echo "<td style='text-align:left;padding-left:10px'>".date('M d, Y',$biddate)."</td>";
								echo "<td style='text-align:left;padding-left:10px'><a href='index.php?p=12&u=$borrowerid'>$firstname $lastname</a> &nbsp;&nbsp; $city,&nbsp; $country</td>";		       
								echo "<td style='text-align:left;padding-left:10px'>".number_format($bidamt, 2, '.', ',')."</td>";					
								echo "<td style='text-align:left;padding-left:10px'> $bidstatus_desc </td>";
							echo '</tr>';				
						}
					?>
						</tbody>
						<tfoot>
							<tr style='height:20px;'>                    
								<td colspan='2' style='text-align:left;padding-left:10px'><b><?php echo $lang['profile']['tot_val_act_bids'];?> </td>	
								<td colspan='2' style='text-align:left;padding-left:10px'><b><?php echo number_format($tot_val_act_bids, 2, '.', ','); ?></td>
							</tr>
						</tfoot>
					</table>
			<?php
				}
			echo "</div>";
			echo "<h3>".$lang['profile']['act_loan']."</h3>";
			if(isset($amt) && $amt != 0 && $amt != '')
				echo "";
			else
				echo "".$lang['payment']['no_t_y']."" ;
		?>
			<div>	   
			<?php	
				$borrowerLoanstatus = $database->getLender_disbursedLoan($getuid);
				 if($borrowerLoanstatus)
				 { 
			?>
					<table cellspacing="1" width="99%" class="tablesorter {sortlist: [[0,0],[2,0]]}">
						<thead>
							<tr>                    
								<th width='55%'><?php echo $lang['profile']['borrower_name'];?> </th>
								<th width='25%'><?php echo $lang['profile']['borr_givenamt'];?> </th>
								<th width='20%'><?php echo $lang['profile']['loan_status'];?></th>            
							</tr>
						</thead>
						<tbody>
				<?php
						$amtgivenTotal=0;
						foreach( $borrowerLoanstatus as $rows )
						{
							$firstname=$rows['FirstName'];
							$lastname=$rows['LastName'];
							$borrowerid=$rows['userid'];
							$activestate=$rows['Loan_State'];
							$amountgiven=$rows['AMT'];							
							$loan_id=$rows['loanid'];
							$city = $rows['city'];
							$country = $rows['country'];
							$country = $database->mysetCountry($rows['country']);
							if($activestate==0){
							$active_state="LOAN OPEN";
							}
							else if($activestate==1)
							{
							 $active_state="LOAN FUNDED";
							}
							else if($activestate==2)
							{
							 $active_state="LOAN ACTIVE";
							}
							else if($activestate==3)
							{
							 $active_state="LOAN REPAID";
							}
							else if($activestate==5)
							{
							 $active_state="LOAN DEFAULTED";
							}
							else if($activestate==6)
							{
							 $active_state="LOAN CANCELLED";
							}
							else if($activestate==7)
							{
							 $active_state="LOAN EXPIRED";
							}
							else if($activestate==8)
							{
							 $active_state="LOAN All";
							}

							echo '<tr>';				
								echo "<td style='text-align:left;padding-left:10px'><a href='index.php?p=12&u=$borrowerid'>$firstname $lastname</a> &nbsp;&nbsp; $city,&nbsp; $country</td>";		       
								echo "<td style='text-align:left;padding-left:10px'>".number_format($amountgiven, 2, '.', ',')."</td>";					
								echo "<td style='text-align:left;padding-left:10px'><a href='index.php?p=14&u=$session->userid&l=$loan_id'>$active_state</a></td>";
							echo '</tr>';
							$amtgivenTotal += $amountgiven;
						}
				?>
						</tbody>
						<tfoot>
							<tr style='height:20px; background-color:#cccccc'>
								<th><b><?php echo $lang['profile']['tot_amt_lnt']; ?></th>
								<th colspan='2' style='text-align:left;padding-left:10px'><b><?php echo number_format($amtgivenTotal, 2, '.', ','); ?></th>					
							</tr>
						</tfoot>
					</table>
			<?php 
				}	
			
			echo "</div>";
			  }
		?>

			</div>
			
			<?php  
				if($displyall){ 
			?>
			<div id="maincontainer">
				<div id="left_fdb">
					<div align="center" style="margin-right: 17px; margin-left: 17px; margin-top: 7px;">
						
					</div>
				</div>
				<div style="clear: both;">
				</div>
			</div>
			<?php  
				} 
		}
		else if($getulevel==BORROWER_LEVEL){

			$id=$getuid;
			$data=$database->getBorrowerDetails($id);
			$fname=$data['FirstName'];
			$lname=$data['LastName'];
			$name=$fname.' '.$lname;
			$padd=$data['PAddress'];
			$city=$data['City'];
			$country=$data['Country'];
			$nationid=$data['nationId'];
			$loanhist=$data['loanHist'];
			$telmobile=$data['TelMobile'];
			$email=$data['Email'];
			if($data['tr_About']==null || $data['tr_About']=="")
				$about=nl2br($data['About']);
			else
				$about=nl2br($data['tr_About']);
			if($data['tr_BizDesc']==null || $data['tr_BizDesc']=="")
				$desc=nl2br($data['BizDesc']);
			else
				$desc=nl2br($data['tr_BizDesc']);
			$username=$data['username'];
			$activeloanid=$data['activeLoanID'];
			$currRate=$database->getCurrentRate($id);
			$UserCurrency = $database->getUserCurrency($id);
			$profile=$database->getBorrowerPartner($id);
			if(!empty($profile)){
				$partid=$profile['userid'];
				$partname=$profile['name'];
				$partweb=$profile['website'];	
			}else{
				$nonactive=1;
				$act="Not Activated Yet";
			}
			$myfriends=0;
			if(($session->userlevel==PARTNER_LEVEL)||($session->userlevel==ADMIN_LEVEL))$myfriends=1;
			if($session->userlevel==LENDER_LEVEL){
				$myfriendsid=$session->userid;
				$myfriends=$lenders=$database->getMyLenders($id,$myfriendsid);
			}
			
		    if(!empty($_GET['fdb']))
			{
				if($_GET['fdb']==1){
					$noOfcomments=$database->getAllComment($id,0,0);//set secnod arg as start ,end . 0,0 for all
			
				$st=0;
				$start=0;
				$end=0;
				$c=10;
				if(isset($_REQUEST['c'])){
					$c = intval($_REQUEST['c']);
				}
				if(isset($_REQUEST['n'])){
					$st = intval($_REQUEST['n'])+$c;
				}
				$st1=$st+$c;
				if($st1 >= $noOfcomments)
					$end=1;
			
				if(isset($_REQUEST['f'])){
					$st = intval($_REQUEST['f'])-$c;
				}
				if($st<=0){
					$st=0;
					$start=1;
				}
			?>
			<div>
				<table border=0 width=100% align="" cellspacing=0 cellpadding="0" class="tablecss">
					<tr>
						<td>
							<div style="width:45%; float:left">
								<h3 class='arrow' id='cmnt_heading'><a name="e4"><?php echo $lang['profile']['comments'];?></a></h3>
							</div>
							
							<div style="width:25%; float:right">
								<h3>  <?php echo"<a id='slick-toggle' href='#' style='color: #000000;'>".$lang['profile']['wcomments']."</a>";?></h3>
							</div>
                            
								
						</td>
					</tr>

				<tr>
						<td align="left"colspan=2>
									
							<div id="slickbox" align="left">
								<div id="left_fdb">
									<div align="left" style="margin-right: 17px; margin-left:20px; margin-top: 20px;">
                                     <?php if(isset($session->userid)){?>
										<font><b><?php echo $lang['profile']['comments'];?></b></font>
										<form name="feedback" method="POST" action='./updatefeedback.php' enctype="multipart/form-data">
										<div>
											<TEXTAREA NAME="txtcomment" ROWS=2 COLS=60 TABINDEX="8"></TEXTAREA>
										</div>
										Upload File1 :<input type="file" name="file1[]" id="file11" />&nbsp;&nbsp;File should have .jpg or .gif extension. <br/>
										Upload File2 :<input type="file" name="file1[]" id="file12" /><br/>
										Upload File3 :<input type="file" name="file1[]" id="file13" /><br/>
										&nbsp;<input name="Submit" type="submit" value="Submit"><input type='hidden' name='feedback' />
										<input type='hidden' name='userid' value='<?php echo $id;?>' />
										<input type='hidden' name='senderid' value='<?php echo $session->userid;?>' />
										<input type='hidden' name='loanid' value='<?php echo $ld;?>' />
										<input type='hidden' name='MessType' value='Insert' />
                                        <input type='hidden' name='return' value='up' />
										</form>
                                        <?php }else{ ?>
                                        <span><h1>Please login to post comment</h1></span>
                                        <?php } ?>
									</div>
								</div>
							</div>
						</td>
					</tr>

				</table> 
				<div id='cmnt_desc'>
			<?php
							//echo $start."//".$end;
								global $db;
								
                                $result=$database->getDetailByForumId($id);
								
								$incr=0;
								foreach($result as $forumid)
								{
									//echo $forumid['forumid'];
									$feeddetails=$database->getAllCommentForum($id,$session->userid,$forumid['forumid'],0,0);	///set for how many feed back
								
								
								if(!empty($feeddetails)){
									//print_r($feeddetails);
									$c=0;
									foreach ($feeddetails as $commns){
										if($commns['tr_message']==null || $commns['tr_message']=="")
											$msg=$commns['message'];
										else
											$msg=$commns['tr_message'];
										$msgorg=$commns['message'];
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
										 $class = array();
										  //if ($commns['parentId']) {
											  $class[] = ' child c'.$commns['depth'];
										  //}
										 //print_r($class) ;
									?>
                                    <?php 
									if($commns['status']==0)
									{	
										if(count($feeddetails)>0)
                                        {
                                    ?>
											<div style="margin-left:<?php echo $margin; ?>px" class="post<?php echo $class[0]; ?>">
								  <?php }else{ echo "<div>"; } ?>
								<script type="text/javascript">
									function Deletearow<?php echo $incr; ?>(str)
									{
										document.delform<?php echo $incr; ?>.submit();
									}
								</script>
									<script type="text/javascript">
									$(document).ready(function() {
										// hides the slickbox as soon as the DOM is ready
										// (a little sooner than page load)
										$('#slickbox<?php echo $incr; ?>').hide();
										$('#editbox<?php echo $incr; ?>').hide();
										$('#msg_org<?php echo $incr; ?>').hide();
										// toggles the slickbox on clicking the noted link
										$('a#slick-toggle<?php echo $incr; ?>').click(function() {
											$('#editbox<?php echo $incr; ?>').hide();
										$('#slickbox<?php echo $incr; ?>').slideToggle("slow");
										return false;
										});

										$('a#edit-toggle<?php echo $incr; ?>').click(function() {
											$('#slickbox<?php echo $incr; ?>').hide();
										$('#editbox<?php echo $incr; ?>').slideToggle("slow");
										return false;
										});
										$('#msg-toggle<?php echo $incr; ?>').click(function() {											
										$('#msg_org<?php echo $incr; ?>').slideToggle("slow");
										var txt = $(this).text();			
										if(txt == "<?php echo $lang['profile']['disp_text']; ?>")
											$(this).html("<b><font color='blue'><?php echo $lang['profile']['hide_text']; ?></font></b>");
										else
											$(this).html("<b><font color='blue'><?php echo $lang['profile']['disp_text']; ?></font></b>");
										return false;
										});
									});
							</script>
							<?php
										$senderid1=$commns['senderid'];
										$receiverid=$commns['receiverid'];
										$name12=$database->getUserNameById($senderid1);
										if($setcolor==0)
											{ 
							?>
							
							<table border=0 class="lendertable" width=100%>
								<?php $setcolor=1;
									}else{
								?>
								
								<table border=0 class="lendertable_o" width=100%>
									<?php $setcolor=0; } 
							
							?>
									<tr>
										<td height=60 width=22%><img src="library/getimagenew.php?id=<?php echo $senderid1;?>&width=50&height=50">
											<br/><?php echo "<a href='index.php?p=12&u=$senderid1'>$name12</a>";?><!--Photo of peon-->
										</td>
										<td>
											<b><?php echo $name12;?></b>&nbsp;comments on &nbsp;<?php echo date("M d, Y", $commns['pub_date']);?><br/><br/> 
											<?php 
												echo nl2br($msg);
											?>
											<br/>
											<?php
												
												$res=$database->getDetailCommentFile($commns['forumid'],$commns['id']);
												echo "<div>";
												foreach($res as $row)
												{
													echo "<div style='width:106px;float:left;padding:10px;text-align:center;'><a href='includes/image.php?imgid=".$row['uploadfile']."' target='_blank'><img src='includes/getcommentupload.php?p=61&imgid=".$row['uploadfile']."&width=96&height=96' /></a>";if(isset($session->userid) && ($session->userid==$senderid1 || $session->userid == ADMIN_ID)){echo "<a href='./updatefeedback.php?imgID=".$row['id']."&MessType=ImgDel&ImgFile=".$row['uploadfile']."&return=up&userid=".$receiverid."'>Delete</a>";} echo "</div>";
												}
												echo "</div>";
												if($msg != $msgorg)
												{
													echo "<div id='msg-toggle".$incr."' style='cursor: pointer' align='right'><b><font color='blue'>".$lang['profile']['disp_text']."</font></b></div>";
												}
											?>
										</td>
										<td><?php 
											if(isset($session->userid))
											{
												if($activeuser == 1)
												{
													echo "<a href='index.php?p=24&c_id=".$commns['id']."&ref=1'>translation</a><br>";
												}
												echo "<a id='slick-toggle".$incr."' href='#' style='color: #000000;'>Reply</a>&nbsp;&nbsp;";
											}
											else
												echo "&nbsp;&nbsp;";
											?>
										<?php
											if($session->userid==$senderid1 || $session->userid == ADMIN_ID)
											{
                                                $res=$database->getNextDeleteId($commns['forumid'], $commns['id']);
                                                
                                                if(count($res)>0 && $res==$commns['id'])
                                                {
													echo "<form action='./updatefeedback.php' method='POST' name='delform$incr'><a onclick='Deletearow$incr($incr)' style='text-decoration:underline;'>Delete</a><input type='hidden' name='MessType' value='DeleteReal'><input type='hidden' name='parentid' value='".$commns['id']."'><input type='hidden' name='loanid' value='".$ld."'><input type='hidden' name='forumid' value='".$commns['forumid']."'><input type='hidden' name='receiverid' value='".$commns['receiverid']."'><input type='hidden' name='Senderid1' value='".$commns['senderid']."'><input type='hidden' name='return' value='up' /></form>&nbsp;&nbsp;<a id='edit-toggle".$incr."' href='#' style='color: #000000;'>Edit</a>";
                                                }else{
                                                    echo "<form action='./updatefeedback.php' method='POST' name='delform$incr'><a onclick='Deletearow$incr($incr)' style='text-decoration:underline;'>Delete</a><input type='hidden' name='MessType' value='Delete'><input type='hidden' name='parentid' value='".$commns['id']."'><input type='hidden' name='forumid' value='".$commns['forumid']."'><input type='hidden' name='receiverid' value='".$commns['receiverid']."'><input type='hidden' name='Senderid1' value='".$commns['senderid']."'><input type='hidden' name='return' value='up' /></form>&nbsp;&nbsp;<a id='edit-toggle".$incr."' href='#' style='color: #000000;'>Edit</a>";
                                                }
												if($session->userid == ADMIN_ID){
													if($commns['publish']==1)
													{
														echo "<br/><a href='./updatefeedback.php?MessType=Publish&return=up&receiverid=".$commns['receiverid']."&PublishID=".$commns['id']."' style='text-decoration:underline;'>Publish</a>";
													}else{
														echo "<br/><a href='./updatefeedback.php?MessType=UnPublish&return=up&receiverid=".$commns['receiverid']."&PublishID=".$commns['id']."' style='text-decoration:underline;'>UnPublish</a>";
													}
												}
											}
										?>
											</td>
									</tr>
                                    <?php if(isset($session->userid)) 
										  {	
									?>
									<tr>
										<td align="left"colspan="3">
													
											<div id="slickbox<?php echo $incr;?>" align="left">
												<div id="left_fdb">
													<div align="left" style="margin-right: 17px; margin-left:20px; margin-top: 20px;">
														<font><b><?php echo $lang['profile']['comments'];?></b></font>
														<form name="feedback" method="POST" action='./updatefeedback.php' enctype="multipart/form-data">
														<div>
															<TEXTAREA NAME="message" ROWS=2 COLS=60 TABINDEX="8"></TEXTAREA>
														</div>
														Upload File1 :<input type="file" name="file1[]" id="file11" />&nbsp;&nbsp;File should have .jpg or .gif extension. <br/>
														Upload File2 :<input type="file" name="file1[]" id="file12" /><br/>
														Upload File3 :<input type="file" name="file1[]" id="file13" /><br/>
														&nbsp;<input name="Submit" type="submit" value="Submit"><input type='hidden' name='feedback' />
														<input type='hidden' name='subject' value='Re:<?php echo $msgorg; ?>' />
														<input type='hidden' name='parentid' value='<?php echo $commns['id'];?>' />
														<input type='hidden' name='thread' value='<?php echo $commns['thread'];?>' />
														<input type='hidden' name='forumid' value='<?php echo $commns['forumid'];?>' />
														<input type='hidden' name='receiverid' value='<?php echo $receiverid;?>' />
														<input type='hidden' name='Senderid1' value='<?php echo $session->userid;?>' />
														<input type='hidden' name='return' value='up' />
														<input type='hidden' name='MessType' value='Reply' />
														</form>
													</div>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<td align="left"colspan="3">
													
											<div id="editbox<?php echo $incr;?>" align="left">
												<div id="left_fdb">
													<div align="left" style="margin-right: 17px; margin-left:20px; margin-top: 20px;">
														<font><b><?php echo $lang['profile']['comments'];?></b></font>
														<form name="feedback" method="POST" action='./updatefeedback.php' enctype="multipart/form-data">
														<div>
															<TEXTAREA NAME="message" ROWS=2 COLS=60 TABINDEX="8"><?php echo $msgorg; ?></TEXTAREA>
														</div>
														Upload File1 :<input type="file" name="file1[]" id="file11" />&nbsp;&nbsp;File should have .jpg or .gif extension. <br/>
														Upload File2 :<input type="file" name="file1[]" id="file12" /><br/>
														Upload File3 :<input type="file" name="file1[]" id="file13" /><br/>
														&nbsp;<input name="Submit" type="submit" value="Submit"><input type='hidden' name='feedback' />
														<input type='hidden' name='subject' value='Re:<?php echo $msgorg; ?>' />
														<input type='hidden' name='parentid' value='<?php echo $commns['id'];?>' />
														<input type='hidden' name='thread' value='<?php echo $commns['thread'];?>' />
														<input type='hidden' name='forumid' value='<?php echo $commns['forumid'];?>' />
														<input type='hidden' name='receiverid' value='<?php echo $receiverid;?>' />
														<input type='hidden' name='Senderid1' value='<?php echo $session->userid;?>' />
														<input type='hidden' name='return' value='up' />
														<input type='hidden' name='MessType' value='Update' />
														</form>
													</div>
												</div>
											</div>
										</td>
									</tr>
							<?php } ?>
									<tr>
										<td align="left" colspan="3">
											<div id="msg_org<?php echo $incr;?>">
												<?php echo $msgorg;?>
											</div>
										</td>
									</tr>
									</table>
								</div>
                                    
								<?php }else{
											if(count($feeddetails)>1 && $incr < (count($feeddetails)-1))
                                                {
											$res=$database->getNextDeleteId($commns['forumid'], $commns['id']);
                                                if(count($res)>0)
                                                    {
											
                                                    }else{
                                                        
                                                    } 
                                                } 
                                            } ?>
								
								<?php $incr++; } 
									}
									else{
										echo"No feedback yet!";
									}  
								}
								?>
			
				</div>
				</div>

			<!--End of loop-->
		<?php
			echo "<div align='right' id='next_page'>";
				if(!$start){
					echo "<a href='index.php?p=12&u=$id&fdb=1&f=$st&c=$c'>Previous</a>";
				}
				echo"&nbsp;&nbsp;";
				if(!$end)
				{
					echo"<a href='index.php?p=12&u=$id&fdb=1&n=$st&c=$c'>Next</a>";
				}
			echo"</div>"; ?>
		<?php
			}
			elseif($_GET['fdb']==2)
			{
				$feeddetail=$database->getPartnerComment($id);
		?>
			<div id="comments">
			<!--Write loop for multiple feedback here-->
			<?php 
				if(!empty($feeddetail)){
					$i=0;
					foreach ($feeddetail as $commns){
						$sendid=$commns['partid'];
						$name=$database->getNameById($sendid);
						$lendername = $commns['lender'];						
						$amt = $commns['amount'];
						if($commns['rate']==null || $commns['rate']=="" || $commns['rate']==0)
							$rate = $database->getExRateById($commns['editDate'],$commns['userid']);
						else
							$rate = $commns['rate'];
						$amt_us = convertToDollar($amt,$rate); 
						$date_disb = $commns['date'];
						if($commns['lpaid'] == 1)
							$fully_repd ='Yes'; 
						else
							$fully_repd ='No'; 
						if($commns['ontime'] == 1)
							$ontime ='Yes'; 
						else
							$ontime ='No'; 
						$divid="replybox".$i;
						if($commns['feedback']==3)
							$feedbackText = "Neutral";
						if($commns['feedback']==4)
							$feedbackText = "Negative";
						else
							$feedbackText = "Positive";
						/* Changes for zidisha loan feedback by chetan  */
						if($commns['loneid'])
						{
							$lendername = "Zidisha Loan";
							$loanDetail= $database->getLoanDetails($commns['loneid']);
							$amt= $loanDetail['AmountGot'];
							$amt_us = convertToDollar($amt,$rate);
							$date_disb =$loanDetail['AcceptDate'];
                            if($commns['ontime']==0)
								$ontime ='No';
							else
								$ontime ='Yes';							
							$fully_repd ='Yes';
							$name = $database->getUserNameById($sendid);
						}
			?>
			<script type="text/javascript">
			$(document).ready(function() {		
				$('#partner_comment_desc<?php echo $i ?>').hide();
				$('#partner_comment<?php echo $i ?>').click(function() {
				$('#partner_comment_desc<?php echo $i ?>').slideToggle("slow");	
					var txt = $(this).text();			
					if(txt == "<?php echo $lang['profile']['disp_text']; ?>")
						$(this).html("<b><font color='blue'><?php echo $lang['profile']['hide_text']; ?></font></b>");
					else
						$(this).html("<b><font color='blue'><?php echo $lang['profile']['disp_text']; ?></font></b>");
				});
			});
			</script>

				<table>
					<tr>
						<td>
							<div id="main_center_fdb" >
								<table border=0 width=100% cellpadding=0 cellspacing=0>
									<tr>
										<td colspan=2>
											<table style="border: 1px groove rgb(204, 204, 204); " width=100% cellpadding=0 cellspacing=2>
												<tr height="10px">
													<td width=20% align="center" rowspan='3' style="background-color:#F3FAFA;">
														<a name='<?php echo $i;?>'></a>
														<a href='index.php?p=12&u=<?php echo $sendid;?>'><img src="library/getimagenew.php?id=<?php echo $sendid;?>&width=75&height=75" border=0></a>
														<br/><?php echo "<a href='index.php?p=12&u=".$sendid."'>".$name."</a>";?>
													</td>
													<?php $type=$commns['type']; ?>
													<td align="left" style="background-color:#F3FAFA;">
														<div style="margin-left: 14px; margin-top: 5px; float: left;"><b>
															<?php echo $feedbackText;?></b>&nbsp;&nbsp;&nbsp;<?php echo date("M d, Y", $commns['editDate']);?>
														</div>
														<div style="float: left; margin-top: 5px; margin-left: 105px;">
														<?php if(!empty($commns['loneid']))
															  {	?> 
																<a href='index.php?p=14&u=<?php echo $commns['userid'];?>&l=<?php echo $commns['loneid'];?>'>Loan Detail</a>
														<?php }
															  else{ 
																echo "<b>Pre Zidisha Loan</b>";}?>
														</div>
														<div style="float: right; margin-top: 5px; margin-right: 15px;">
															<?php if($myfriends || $displyall){ echo"<button>Reply</button>";}?>
														</div>
													</td>
												</tr>
												<tr style="background-color:#F3FAFA;">
													<td>
														<table>
															<tr>
																<td style='padding-left:12px'><b><?php echo $lang['profile']['lender'];?>:</td>
																<td><?php echo $lendername; ?></td>
															</tr>
															<tr>
																<td style='padding-left:12px'><b><?php echo $lang['profile']['amount'];?> (USD):</td>
																<td><?php echo number_format($amt_us, 2, ".", ","); ?></td>
															</tr>
															<tr>
																<td style='padding-left:12px'><b><?php echo $lang['profile']['date_disbursed'];?>:</td>
																<td><?php echo date("M d, Y", $date_disb); ?></td>
															</tr>
															<tr>
																<td style='padding-left:12px'><b><?php echo $lang['profile']['fully_repd'];?>:</td>
																<td><?php echo $fully_repd; ?></td>
															</tr>
															<tr>
																<td style='padding-left:12px'><b><?php echo $lang['profile']['repdontime'];?>:</td>
																<td><?php echo $ontime; ?></td>
															</tr>										
															</tr>																				
														</table>
													</td>
												</tr>
												<tr valign="top">
													<td style="background-color:#F3FAFA;">
														<div style="margin-left: 15px;">
															<?php 
																if($commns['tr_comment']==null || $commns['tr_comment']=="")
																	$comnt = $commns['comment'];
																else
																	$comnt = $commns['tr_comment'];
																
																if(strlen($comnt)>2000)
																{ 
																	echo substr($comnt, 0,2000)."...&nbsp;<a href='index.php?p=12&u=$id&fdb=1'>more</a>";
																} 
																else 
																	echo $comnt;														
															?>
														</div>
														<?php 														
														if($activeuser == 1)
														{
															echo "<div align='right'><a href='index.php?p=24&lc_id=".$commns['id']."&ref=1'>translation</a></div>";
														}
														if($comnt == $commns['tr_comment'])
														{
														echo "<div id='partner_comment".$i."' style='cursor: pointer' align='right'><b><font color='blue'>".$lang['profile']['disp_text']."</font></b></div>";
														} ?>
													</td>

												</tr>
											</table>
										</td>
									</tr>
									
									<tr id='partner_comment_desc<?php echo $i ?>'>									
										<td  align="left" colspan=2>
										<div style="border: 1px groove rgb(204, 204, 204); width: 610px; height: 100%; background-color:#F3FAFA;">
										<div style="margin-left: 15px;">
								<?php	if(strlen($commns['comment'])>2000)
										{ 
											echo substr($commns['comment'], 0,2000)."...&nbsp;<a href='index.php?p=12&u=$id&fdb=1'>more</a>";
										} 
										else 
											echo $commns['comment'];
								?>
										</div>
										</div>
										</td>										
									</tr>
									<tr>
										<td colspan=2>
											<div id=<?php echo $divid;?> class="reply" align="left" style="border: 1px groove rgb(204, 204, 204); width: 100%; height: 130px;">
												<div id="left_fdb"><div align="left" style="margin-right: 17px; margin-left:20px; margin-top: 20px;">
													<font><b>Comments</b></font>
													<form  method="POST" action='./updatefeedback.php'>
													<div>
														<TEXTAREA NAME="txtcomment" ROWS=2 COLS=60 TABINDEX="8"></TEXTAREA>
													</div>
													&nbsp;<input name="Submit" type="submit" value="Submit">
													<input type='hidden' name='feedback' />
													<input type='hidden' name='userid' value='<?php echo $id;?>' />
													<input type='hidden' name='type' value='<?php echo $type;?>' />
													<input type='hidden' name='divid' value='<?php echo $i;?>' />
													<input type='hidden' name='senderid' value='<?php echo $session->userid;?>' />
													</form>
												</div>
											</div>
										</td>
									</tr>
									<!--Write loop for multiple comments for a feedback here-->
									<?php
										$type=$commns['type'];
										$userid1=$commns['userid'];
										$reply=$database->getAllreply($userid1,$type,0,0);
			
										if(!empty($reply)){	
											foreach ($reply as $eachreply){
											//print_r($eachreply);
												$sendid1=$eachreply['senderid'];
												$name1=$database->getNameById($sendid1); 
									?>
									<tr>
										<td colspan=2>
											<?php if($setcolor==0)
												{
											?>
											<table class="lendertable">
											<?php $setcolor=1;
												}
												else{ 
											?>
											<table class="lendertable_o">
												<?php $setcolor=0;
												}
												?>
												<tr>
													<td width=20% align="center" rowspan='2'>
														<a href='index.php?p=12&u=<?php echo $sendid1;?>'><img src="library/getimagenew.php?id=<?php echo $sendid1;?>&width=75&height=75" border=0></a>
														<br/><?php echo "<a href='index.php?p=12&u=$sendid1'>$name1</a>";?>
													</td>
													<td align="left">
														<b><?php echo $name1;?></b>&nbsp;replied on &nbsp;<?php echo date("M d, Y", $eachreply['editdate']);?> 
													</td>
												</tr>
												<tr>
													<td>
														<div style="margin-left: 15px;">
															<?php if(strlen($eachreply['comment'])>2000){ echo substr($eachreply['comment'], 0,2000)."...&nbsp;<a href='index.php?p=12&u=$id&fdb=1'>more</a>";} else echo $eachreply['comment'];?>
														</div>
													</td>
												</tr>
											</table>
										</td>
									</tr>
										<!--end loop for multiple comments for a feedback here-->
										<?php 
											} 
										}else
											{ 
												echo "No comments on this feedback yet!"; 
											}  
										?>
								</table>
							</div>
						</td>
					</tr>
				</table>
				<?php 
					++$i; 
					} 
				}
				else
					{
					echo"no any feedback yet";
					} 
			?>
			<!--End of loop-->
			<div align="right" id="next_page">
				<a href="index.php?p=12&u=<?php echo $id ?>&fdb=2">Previous</a>&nbsp;&nbsp;<a href="index.php?p=12&u=<?php echo $id ?>&fdb=2">Next</a>
			</div>
		<?php
			}
		}
		else{
		?>
			<table width=100% height=100% border=0 class="tablecss">
				<tr>
					<td width=40% valign="top">
			<?php 
						$lastaloan=$database->getLastloan($id);
						$tpm=2;	
						if(!empty($lastaloan)){
							$activeloanid=$lastaloan['loanid'];
							$currenlonAmt=convertToNative($lastaloan['reqdamt'],$currRate);
							$dcurrenlonAmt=$lastaloan['reqdamt'];
							$bot = '';
							if($lastaloan['active']==LOAN_OPEN){
								$bot = $lang['profile']['Bid_open']. date('M d, Y',$lastaloan['applydate'] + ($database->getAdminSetting('deadline') * 24 * 60 * 60 ));
								$totBid=$database->getTotalBid($id,$activeloanid);
								$tpm=1;
							}else if($lastaloan['active']==LOAN_FUNDED){ 
								$bot = $lang['profile']['Funded']. date('M d, Y',$lastaloan['AcceptDate']);
							}else if($lastaloan['active']==LOAN_ACTIVE){ 
								$bot = $lang['profile']['Active']. date('M d, Y',$lastaloan['AcceptDate']);
							}else if($lastaloan['active']==LOAN_REPAID){ 
								$bot = $lang['profile']['Repaid'];
							}else if($lastaloan['active']==LOAN_DEFAULTED){ 
								$bot = $lang['profile']['Defaulted']. date('M d, Y',$lastaloan['expires']);
							}else if($lastaloan['active']==LOAN_CANCELED){ 
								$bot = $lang['profile']['Canceled']. date('M d, Y',$lastaloan['expires']);
							}else if($lastaloan['active']==LOAN_EXPIRED){ 
								$bot = $lang['profile']['Expired']. date('M d, Y',$lastaloan['expires']);
							}

						}
						else{
							$tpm=0;
							$bot = $lang['profile']['No_Loan'];
						}	
				?>
						<div id="current" width="400">
							<table border=0 width=100% style="margin-left: 10px;">
								<tr width=100%>
									<td colspan=2>
										<h2 id='crnt_loan_heading'>
										<?php 
											echo $lang['profile']['current_loan_info'];
										?>
										</h2>
										<div>
											<img src="./images/front_page/line3.png" align="left" border="0">
										</div>
									</td>
								</tr>
							   </table>
							   <div id='crnt_loan_desc'>
							   <table border=0 width=100% style="margin-left: 10px;">
								<?php
									if(!$tpm){
								?> 
								<tr>
									<td colspan=2 align="center">
									<b>
									<?php
										echo $bot;
									?></b>
									</td>
								</tr>
								<?php 
									}else{
								?>
								<tr>
									<td style="width:50%">
										<b>
										<?php
											echo $lang['profile']['loan_amt'];
										?> 
										</b>
										</td>
									<td style="width:50%"><b>
										<?php
											echo 'USD: '.number_format($dcurrenlonAmt, 0, '.', ',');
										?></b>
									</td>
								</tr>
								
								<tr>
									<td>&nbsp;</td>
									<td></td>
								</tr>	
							   
								<?php
									if($tpm==1){
										 echo"<tr><td><b>".$lang['profile']['total_bids']."</b></td><td><b>USD " .number_format($totBid, 0, '.', ',')."</b></td></tr>";
										 echo" <tr><td>&nbsp;</td><td></td></tr>";
										 echo"<tr><td colspan=2 align='center'><b>$bot</b></td></tr>";
										 echo" <tr><td>&nbsp;</td><td></td></tr>";
										 echo"<tr><td colspan=2 align='center'><a href='index.php?p=14&u=$id&l=$activeloanid#e5'><div id='button' align='center'> </div></a></td></tr>";
									}
									else if($tpm==2){
										echo"<tr><td colspan=2 align='left'><b>$bot</b></td></tr>";
										echo" <tr><td>&nbsp;</td><td></td></tr>";
										echo"<tr><td colspan=2 align='center'><a href='index.php?p=14&u=$id&l=$activeloanid'>".$lang['profile']['Detail']."</a></td></tr>";
									}
								}
								?>
							</table>
							</div>
						</div>
					</td>
					<td  width=70%>
						<div id="right">
							<table border=0 width=100%>
								<tr>
									<td align="center">
										<img src="library/getimagenew.php?id=<?php echo $id;?>&width=400&height=300">
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
			</table>
			<div id="left">
				<table border=0 width=100%>
					<tr width=100% class="tablecss">
						<td colspan=2>
							<div>
								<img src="./images/front_page/line.png" align="left" border="0">
							</div>
						</td>
					</tr>
					<tr>
						<td >
							<?php echo $about; ?>
						</td>
					</tr>
			<?php	if($activeuser == 1)
					{
						echo "<tr>
						<td colspan=2 align='right'><a href='index.php?p=24&id=".$_GET['u']."&l_id=".$_GET['l']."&ref=1'>translation</a></td>
						</tr>";
					}
			?>
				<?php
					if($about ==$data['tr_About'])
					{	
					echo "<tr>
						<td id='abt_org' style='cursor: pointer' align='right'><b><font color='blue'>".$lang['profile']['disp_text']."</font></b></td>
					</tr></table>";
					echo "<table border=0 width=100%>				
					<tr width=100% class='tablecss'>
						<td id='abt_org_desc' >".$data['About'];
					} ?>
				</table>
			</div> 
			<div id="left_right">
                <table border=0 width=100% class="tablecss">
					<tr width=100%>
						<td colspan=2>
							<h3 class='arrow' id='my_prof_heading'><?php echo $lang['profile']['profile'];?></h3>
							<div>
								<img src="./images/front_page/line3.png" align="left" border="0">
							</div>
						</td>
					</tr>
				</table>

				<div id='my_prof_desc'>
				<table border=0 width="100%" style="float:right" class="tablecss">
					
					<tr>
						<td width="50%">
							<b><?php echo $lang['profile']['Name'];?></b>
						</td>						
						<td>
							<?php echo $name; ?>
						</td>
					</tr>
                    <?php if($displyall){ ?>
					<tr>
						<td>
							<b><?php echo $lang['profile']['User_Name'];?></b>
						</td>						
						<td>
							<?php echo $username ?>
						</td>
					</tr>
					<tr>
						<td>
							<b><?php echo $lang['profile']['Post_Add'];?></b>
						</td>						
						<td>
							<?php echo $padd; ?>
						</td>
					</tr>
					<tr>
						<td>
							<b><?php echo $lang['profile']['Email'];?></b>
						</td>						
						<td>
							<?php echo $email; ?>
						</td>
					</tr>
				    <tr>
						<td>
							<b><?php echo $lang['profile']['Contact_no'];?></b>
						</td>						
						<td>
							<?php echo $telmobile; }?>
						</td>
					</tr>
					<tr>
						<td>
							<b><?php echo $lang['profile']['City'];?></b>
						</td>						
						<td>
							<?php echo $city ?>
						</td>
					</tr>
					<tr>
						<td>
							<b><?php echo $lang['profile']['Country'];?></b>
						</td>						
						<td>
							<?php echo $database->mysetCountry($country) ?>
						</td>
					</tr>
			<?php  if($rightuser == 1)
				   {  ?>
					<tr>
						<td>
							<b><?php echo $lang['profile']['nationid'];?></b>
						</td>						
						<td>
							<?php echo $nationid ?>
						</td>
					</tr>
					<tr>
						<td>
							<b><?php echo $lang['profile']['loanhist'];?></b>
						</td>						
						<td>
							<?php echo $loanhist ?>
						</td>
					</tr>
			<?php } ?>
					<?php
						$brwl=$database->getLoanDetail($id);
						if(empty($brwl)){
							echo "<tr><td colspan=2>No Loan Application Yet </td></tr>";
						}
						$report=$database->loanReport($id);
					
						$ldate=$report['sincedate'];
						$countt=$report['NoOfLone'];
						$lamount=$report['Total'];
					/*	$damount=convertToDollar($lamount,$currRate);    */
						$damount=$report['Total_us'];
						$CPaidOntime=$report['PaidonTime'];
						$PaidOntime=$report['AmtPaidonTime'];
					/*	$dPaidOntime=convertToDollar($PaidOntime,$currRate);   */
						$dPaidOntime=$report['AmtPaidonTime_us'];
						$CPaidLate=$report['late'];
						$PaidLate=$report['Amtlate'];
						$dPaidLate=$report['Amtlate_us'];
					/*	$dPaidLate=convertToDollar($PaidLate,$currRate);   */
					/*	$CDefalted=$report['Deflted'];  */
						$CDefalted =0;
						$Defaulted=$report['AmtDeflted'];
						if(!empty($Defaulted))
						$dDefaulted=$report['AmtDeflted_us'];
						$f=$report['feedback'];
						$cf=$report['Totalfeedback'];
					
					?>
		
					<tr>
						<td>
							<b><?php echo $lang['profile']['brw_since_date'];?></b>
						</td>						
						<td>
							<?php echo date("M d, Y ", $ldate);?>
						</td>
					</tr>
					<tr>
						<td>
							<b><?php echo $lang['profile'][' No_Loan_dis'];?></b>
						</td>						
						<td>
							<?php echo $countt;?>
						</td>
					</tr>
					<tr>
						<td>
							<b> <?php echo $lang['profile']['total_val_Loan_dis'];?></b>
						</td>						
						<td>
							USD <?php echo number_format($damount, 0, ".", ",");?> (<?php echo number_format($lamount, 0, ".", ",") . ' '. $UserCurrency ;?>)
						</td>
					</tr>
					
					<tr>
						<td>
							<b> <?php echo $lang['profile']['loan_repaid'];?></b>
						</td>						
						<td>
							<?php echo $CPaidOntime;?>
						</td>
					</tr>
					<tr>
						<td>
							<b> <?php echo $lang['profile']['total_loan_repaid'];?></b>
						</td>						
						<td>
							USD <?php echo number_format($dPaidOntime, 0, ".", ",");?> (<?php echo number_format($PaidOntime, 0, ".", ",") . ' '. $UserCurrency ;?>)
						</td>
					</tr>
					<tr>
						<td>
							<b><?php echo $lang['profile']['loan_repaid_late'];?></b>
						</td>						
						<td>
							<?php echo $CPaidLate ;?>
						</td>
					</tr>
					<tr>
						<td>
							<b><?php echo $lang['profile']['total_loan_repaid_late'];?></b>
						</td>						
						<td>
							USD <?php echo number_format($dPaidLate, 0, ".", ",");?> (<?php echo number_format($PaidLate, 0, ".", ",") . ' '. $UserCurrency ;?>)
						</td>
					</tr>

					<tr>
						<td>
							<b><?php echo $lang['profile']['loan_defaulted'];?></b>
						</td>						
						<td>
							<?php echo $CDefalted;?>
						</td>
					</tr>
					<tr>
						<td>
							<b> <?php echo $lang['profile']['total_loan_deflt'];?></b>
						</td>						
						<td>
							USD <?php echo number_format($dDefaulted, 0, ".", ",");?> (<?php echo number_format($Defaulted, 0, ".", ",") . ' '. $UserCurrency ;?>)
						</td>
					</tr>
					<tr>
						<td>
							<b><?php echo $lang['profile']['Partner_feed'];?></b><a href='#' class='tt'><img src='library/tooltips/help.png' style='border-style: none' /><span class='tooltip'><span class='top'></span>
		<span class='middle'><?php echo $lang['profile']['tooltip_feed_rating'];?></span><span class='bottom'></span></span></a>
						</td>						
						<td><?php echo number_format($f, 2, '.', ',')." % Positive (<a href='index.php?p=12&u=$id&fdb=2'>".$cf."</a>)";?>
						</td>
					</tr>
					
				</table>
				</div>	
			</div>
			
			<div id="left_right">
				<table border=0 width=100% class="tablecss">
					<tr>
						<td colspan=2 >
							<h3 class='arrow' id='part_prof_heading' ><?php echo $lang['profile']['Detail_Partner'];?><a href='#' class='zz'>&nbsp<span class='tooltip'><span class='top'></span>
		<span class='middle'><?php echo $lang['profile']['tooltip_field_part'];?></span><span class='bottom'></span></span></a>&nbsp</h3>
							
							<div>
								<img src="./images/front_page/line3.png" align="left" border="0">
							</div>
						</td>
					</tr>
				</table>
				<div id='part_prof_desc'>
				<table border=0 width=100% class="tablecss">
					<?php if($nonactive!=1){ ?>
					<tr>
						<td style="width:50%">
							<?php echo "<a href='index.php?p=12&u=$partid'>$partname</a>";?><br/><?php echo "<a href='http://$partweb'>$partweb</a>";?>
						</td>
						<td align="right" style="width:50%">
							<a href='index.php?p=12&u=<?php echo $partid;?>'><img src="library/getimagenew.php?id=<?php echo $partid;?>&width=75&height=75"></a>
						</td>
					</tr>
						<?php }else{?>
					<tr>
						<td>
							<b></b>
						</td>
						<td>
							<?php echo $act;?>
						</td>
					</tr>
						<?php	}?>
				</table>
				</div>
			</div>
			<div id="maincontainer">
				<div id="left">
					<table border=0 width=100%>
						<tr>
							<td colspan=2>
								<h3 class='arrow' id='busy_desc'><?php echo $lang['profile']['business_des'] ?></h3>
								<div>
									<img src="./images/front_page/line.png" align="left" border="0">
								</div>
							</td>
						</tr>
						<tr>
							<td><div id='msg_desc'>
								<?php echo $desc; ?>
							</div></td>
						</tr>
				<?php	if($activeuser == 1)
						{
							echo "<tr>
								<td colspan=2 align='right'><a href='index.php?p=24&id=".$_GET['u']."&l_id=".$_GET['l']."&ref=1'>translation</a></td>
							</tr>";
						}
				?>						
					<?php
						if($desc == $data['tr_BizDesc'])
						{	
							echo "<tr>
							<td id='busi_desc_org' style='cursor: pointer' align='right'><b><font color='blue'>".$lang['profile']['disp_text']."</font></b></td>
							</tr></table>";
							echo "<table border=0 width=100%>				
							<tr>
								<td id='busi_desc_org_desc' >".$data['BizDesc']."</td>
							</tr>
							</table>";
					    }
						else
					       echo "</table>";
				?>
				</div>
				<div style="clear: both;">
				</div>
			</div>
			<div id="maincontainer">
				<?php 
					$width=100;
					if(($displyall)||($myfriends)){
						$width=70;
					}
				?>
				
				<table border=0 width=100% align="left" cellspacing=0 cellpadding="0" class="tablecss">
					<tr>
						<td>
							<div style="width:45%; float:left">
								<h3 class='arrow' id='cmnt_heading'><a name="e4"><?php echo $lang['profile']['comments'];?></a></h3>
							</div>
							
							<div style="width:25%; float:right">
								<h3>  <?php echo"<a id='slick-toggle' href='#' style='color: #000000;'>".$lang['profile']['wcomments']."</a>";?></h3>
							</div>
                            
								
						</td>
					</tr>
					<tr>
						<td align="left">
									
							<div id="slickbox" align="left">
								<div id="left_fdb">
									<div align="left" style="margin-right: 17px; margin-left:20px; margin-top: 20px;">
                                    <?php if(isset($session->userid)){?>
										<font><b><?php echo $lang['profile']['comments'];?></b></font>
                                        
										<form name="feedback" method="POST" action='./updatefeedback.php' enctype="multipart/form-data">
										<div>
											<TEXTAREA NAME="txtcomment" ROWS=2 COLS=60 TABINDEX="8"></TEXTAREA>
										</div>
										Upload File1 :<input type="file" name="file1[]" id="file11" />&nbsp;&nbsp;File should have .jpg or .gif extension. <br/>
										Upload File2 :<input type="file" name="file1[]" id="file12" /><br/>
										Upload File3 :<input type="file" name="file1[]" id="file13" /><br/>
										&nbsp;<input name="Submit" type="submit" value="Submit"><input type='hidden' name='feedback' />
										<input type='hidden' name='userid' value='<?php echo $id;?>' />
										<input type='hidden' name='senderid' value='<?php echo $session->userid;?>' />
										 <input type='hidden' name='loanid' value='<?php echo $ld;?>' />
										<input type='hidden' name='MessType' value='Insert' />
                                        <input type='hidden' name='return' value='down' />
										</form>
                                        <?php }else{ ?>
                                        <span><h1>Please login to post comment</h1></span>
                                        <?php } ?>
									</div>
								</div>
							</div>
					<div id='cmnt_desc'>
					<table border=0 width=100% align="left" cellspacing=0 cellpadding="0" class="tablecss">
					<tr width="70%">
						<td colspan=2 rowspan=2>
							<!--code for feedBack view (Maximum 3 feedback)-->
							<?php 
								global $db;
								
								$result=$database->getDetailByForumId($id);								
								$incr=0;
								foreach($result as $forumid)
								{
									//echo $forumid['forumid'];
									$feeddetails=$database->getAllCommentForum($id,$session->userid,$forumid['forumid'],0,0);	///set for how many feed back
									//print_r($feeddetails);
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
											$class = array();										 
											$class[] = ' child c'.$commns['depth'];							
										if($commns['status']==0)
										{
											if(count($feeddetails)>0)
											{												
										?>
												<div style="margin-left:<?php echo $margin; ?>px" class="post<?php echo $class[0]; ?>">
									<?php	}
											else
											{ 
												echo "<div>"; 
											} 
										?>
											<script type="text/javascript">
											function Deletearow<?php echo $incr; ?>(str)
											{
												document.delform<?php echo $incr; ?>.submit();
											}
											</script>
											<script type="text/javascript">
											$(document).ready(function() 
											{
												// hides the slickbox as soon as the DOM is ready
												// (a little sooner than page load)
												$('#slickbox<?php echo $incr; ?>').hide();
												$('#editbox<?php echo $incr; ?>').hide();
												$('#msg1_org<?php echo $incr; ?>').hide();
												// toggles the slickbox on clicking the noted link
												$('a#slick-toggle<?php echo $incr; ?>').click(function() {
													$('#editbox<?php echo $incr; ?>').hide();
												$('#slickbox<?php echo $incr; ?>').slideToggle("slow");
												return false;
												});

												$('a#edit-toggle<?php echo $incr; ?>').click(function() {
													$('#slickbox<?php echo $incr; ?>').hide();
												$('#editbox<?php echo $incr; ?>').slideToggle("slow");
												return false;
												});
												$('#msg1-toggle<?php echo $incr; ?>').click(function() {											
												$('#msg1_org<?php echo $incr; ?>').slideToggle("slow");
												var txt = $(this).text();			
												if(txt == "<?php echo $lang['profile']['disp_text']; ?>")
													$(this).html("<b><font color='blue'><?php echo $lang['profile']['hide_text']; ?></font></b>");
												else
													$(this).html("<b><font color='blue'><?php echo $lang['profile']['disp_text']; ?></font></b>");
												return false;
												});
											});
									</script>
							<?php
									$senderid1=$commns['senderid'];
									$receiverid=$commns['receiverid'];
									//########Borrower name 
									$name12=$database->getUserNameById($senderid1);
									if($setcolor==0)
									{ 
						?>							
										<table border=0 class="lendertable" width=100%>
								<?php	$setcolor=1;
									}
									else
									{
								?>								
										<table border=0 class="lendertable_o" width=100%>
								<?php	$setcolor=0; 
									} 									
							?>
									<tr>
										<td height=60 width=22%><img src="library/getimagenew.php?id=<?php echo $senderid1;?>&width=50&height=50">
											<br/><?php echo "<a href='index.php?p=12&u=$senderid1'>$name12</a>";?><!--Photo of peon-->
										</td>
										<td>
											<b><?php echo $name12;?></b>&nbsp;comments on &nbsp;<?php echo date("M d, Y", $commns['pub_date']);?><br/><br/> 
											<?php 
												echo nl2br($msg1);
											?>
											<br/>
											<?php
												
												$res=$database->getDetailCommentFile($commns['forumid'],$commns['id']);
												
												echo "<div>";
											foreach($res as $row)
											{
												echo "<div style='width:106px;float:left;padding:10px;text-align:center;'><a href='includes/image.php?imgid=".$row['uploadfile']."' target='_blank'><img src='includes/getcommentupload.php?p=61&imgid=".$row['uploadfile']."&width=96&height=96' /></a>";
												
												if(isset($session->userid) && ($session->userid==$senderid1 || $session->userid == ADMIN_ID))
												{
													echo "<a href='./updatefeedback.php?imgID=".$row['id']."&MessType=ImgDel&ImgFile=".$row['uploadfile']."&return=up&userid=".$receiverid."'>Delete</a>";
												}
												echo "</div>";
											}
                                            echo "</div>";
											if($msg1 != $msgorg1)
											{
												echo "<div id='msg1-toggle".$incr."' style='cursor: pointer' align='right'><b><font color='blue'>".$lang['profile']['disp_text']."</font></b></div>";
											}
										?>
										</td>
										<td>
									<?php 
										if(isset($session->userid))
										{
											if($activeuser == 1)
											{
												echo "<a href='index.php?p=24&c_id=".$commns['id']."&ref=1'>translation</a><br>";
											}
											echo "<a id='slick-toggle".$incr."' href='#' style='color: #000000;'>Reply</a>&nbsp;&nbsp;";
										}
										else
											echo "&nbsp;&nbsp;";
											
										if($session->userid==$senderid1 || $session->userid == ADMIN_ID)
										{
											$res=$database->getNextDeleteId($commns['forumid'], $commns['id']);
											
											if(count($res)>0 && $res==$commns['id'])
											{
												echo "<form action='./updatefeedback.php' method='POST' name='delform$incr'><a onclick='Deletearow$incr($incr)' style='text-decoration:underline;'>Delete</a><input type='hidden' name='MessType' value='DeleteReal'><input type='hidden' name='loanid' value='".$ld."'><input type='hidden' name='parentid' value='".$commns['id']."'><input type='hidden' name='forumid' value='".$commns['forumid']."'><input type='hidden' name='receiverid' value='".$commns['receiverid']."'><input type='hidden' name='Senderid1' value='".$commns['senderid']."'><input type='hidden' name='return' value='down' /></form>&nbsp;&nbsp;<a id='edit-toggle".$incr."' href='#' style='color: #000000;'>Edit</a>";
											}
											else
											{
												echo "<form action='./updatefeedback.php' method='POST' name='delform$incr'><a onclick='Deletearow$incr($incr)' style='text-decoration:underline;'>Delete</a><input type='hidden' name='MessType' value='Delete'><input type='hidden' name='loanid' value='".$ld."'><input type='hidden' name='parentid' value='".$commns['id']."'><input type='hidden' name='forumid' value='".$commns['forumid']."'><input type='hidden' name='receiverid' value='".$commns['receiverid']."'><input type='hidden' name='Senderid1' value='".$commns['senderid']."'><input type='hidden' name='return' value='down' /></form>&nbsp;&nbsp;<a id='edit-toggle".$incr."' href='#' style='color: #000000;'>Edit</a>";
											}
											if($session->userid == ADMIN_ID)
											{
												if($commns['publish']==1  )
												{
													echo "<br/><a href='./updatefeedback.php?MessType=Publish&return=down&receiverid=".$commns['receiverid']."&PublishID=".$commns['id']."' style='text-decoration:underline;'>Publish</a>";
												}
												else
												{
													echo "<br/><a href='./updatefeedback.php?MessType=UnPublish&return=down&receiverid=".$commns['receiverid']."&PublishID=".$commns['id']."' style='text-decoration:underline;'>UnPublish</a>";
												}
											}
										}
									?>
										</td>
									</tr>
                              <?php 
									if(isset($session->userid)) 
									{	
							  ?>
									<tr>
										<td align="left"colspan="3">
													
											<div id="slickbox<?php echo $incr;?>" align="left">
											<div id="left_fdb">
											<div align="left" style="margin-right: 17px; margin-left:20px; margin-top: 20px;">
												<font><b><?php echo $lang['profile']['comments'];?></b></font>
												<form name="feedback" method="POST" action='./updatefeedback.php' enctype="multipart/form-data">
													<div>
													<TEXTAREA NAME="message" ROWS=2 COLS=60 TABINDEX="8"></TEXTAREA>
													</div>
													Upload File1 :<input type="file" name="file1[]" id="file11" />&nbsp;&nbsp;File should have .jpg or .gif extension. <br/>
													Upload File2 :<input type="file" name="file1[]" id="file12" /><br/>
													Upload File3 :<input type="file" name="file1[]" id="file13" /><br/>
													&nbsp;<input name="Submit" type="submit" value="Submit"><input type='hidden' name='feedback' />
													<input type='hidden' name='subject' value='Re:<?php echo $msgorg1; ?>' />
													<input type='hidden' name='parentid' value='<?php echo $commns['id'];?>' />
													<input type='hidden' name='thread' value='<?php echo $commns['thread'];?>' />
													<input type='hidden' name='forumid' value='<?php echo $commns['forumid'];?>' />
													<input type='hidden' name='receiverid' value='<?php echo $receiverid;?>' />
													<input type='hidden' name='Senderid1' value='<?php echo $session->userid;?>' />
													<input type='hidden' name='return' value='down' />
													<input type='hidden' name='MessType' value='Reply' />
												</form>
											</div>
											</div>
											</div>
										</td>
									</tr>
									<tr>
										<td align="left"colspan="3">
													
											<div id="editbox<?php echo $incr;?>" align="left">
											<div id="left_fdb">
											<div align="left" style="margin-right: 17px; margin-left:20px; margin-top: 20px;">
												<font><b><?php echo $lang['profile']['comments'];?></b></font>
												<form name="feedback" method="POST" action='./updatefeedback.php' enctype="multipart/form-data">
												<div>
													<TEXTAREA NAME="message" ROWS=2 COLS=60 TABINDEX="8"><?php echo $msgorg1; ?></TEXTAREA>
												</div>
												Upload File1 :<input type="file" name="file1[]" id="file11" />&nbsp;&nbsp;File should have .jpg or .gif extension. <br/>
												Upload File2 :<input type="file" name="file1[]" id="file12" /><br/>
												Upload File3 :<input type="file" name="file1[]" id="file13" /><br/>
												&nbsp;<input name="Submit" type="submit" value="Submit"><input type='hidden' name='feedback' />
												<input type='hidden' name='subject' value='Re:<?php echo $msgorg1; ?>' />
												<input type='hidden' name='parentid' value='<?php echo $commns['id'];?>' />
												<input type='hidden' name='thread' value='<?php echo $commns['thread'];?>' />
												<input type='hidden' name='forumid' value='<?php echo $commns['forumid'];?>' />
												<input type='hidden' name='receiverid' value='<?php echo $receiverid;?>' />
												<input type='hidden' name='Senderid1' value='<?php echo $session->userid;?>' />
												<input type='hidden' name='return' value='down' />
												<input type='hidden' name='MessType' value='Update' />
												</form>
											</div>
											</div>
											</div>
										</td>
									</tr>
									<?php
									}
								?>
									<tr>
										<td align="left" colspan="3">
											<div id="msg1_org<?php echo $incr;?>">
												<?php echo $msgorg1;?>
											</div>
										</td>
									</tr>
									</table>							
								</div>
                            <?php 
									}
									else
									{
										if(count($feeddetails)>1)
                                        {
                                            $res=$database->getNextDeleteId($commns['forumid'], $commns['id']);
                                            if(count($res)>0)
                                            {											
                                            }
											else
											{                                                        
                                            }
                                        }    
                                    }
							?>
									
							<?php 
										$incr++; 
									} 
								}
									else
									{
										echo"No feedback yet!";
									} 									
								}
								?>			
							</td>
						</tr>
					</table>
                    </td>
					</tr>
					</table>
				</div>   <!--  closing of div id cmnt_desc   -->
				</div>
				<div style="clear: both;">
					<div align="right" style="margin-right: 40px;">
						<a href="index.php?p=12&u=<?php echo $id ?>&l=<?php echo $ld;?>&fdb=1">View All</a>
					</div>
				</div>
			</div>
			
			<?php
			}
		}
		else if($getulevel==ADMIN_LEVEL){
			$id=$getuid;
	
		}
		}else{
			echo"invalid value for any client";
		}
?>