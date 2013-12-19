<?php
	include_once("library/session.php");
	include_once("editables/loanapplic.php");
	$sp=0;
	
	if(isset($_GET['s'])){
		$sp=$_GET['s'];
	}
	
	if(!$session->logged_in){
		echo "".$lang['loanapplic']['welcome_g']."<br /><br />".$lang['loanapplic']['required_reg']." 
			".$lang['loanapplic']['pls_login']."<a href='index.php?p=1'>register</a>".$lang['loanapplic']['cont']."";
	}
	else{
		$userid=$session->userid;
		
		if($session->userlevel != BORROWER_LEVEL){
			echo "".$lang['loanapplic']['allow']."<br />".$lang['loanapplic']['Click']." <a href='index.php'>here</a> ".$lang['loanapplic']['cont']."";
		}
		else{
			$active=$database->getLoanStatus($userid);
			
			if($active == LOAN_OPEN || $active == LOAN_FUNDED || $active == LOAN_ACTIVE ){//NO_LOAN=4
				if($sp==4){
					
				?>
					<table>
						<tr>
							<td><b>Loan Application</b></td>
						</tr>
						<tr>
							<td>
							<?php echo $lang['loanapplic']['thank_note'];?><b><?php echo $lang['loanapplic']['open'];?></b>.
							<?php echo $lang['loanapplic']['best_wish']; ?>
							<br />
							<br />
							<?php echo $lang['loanapplic']['org'];?>
							</td>
						</tr>
					</table>
				<?php
				}
				else{
					echo "".$lang['loanapplic']['active_loan_not allow']." $active";
				}
			}
			else{
					$activeted=$database->getBorrowerActive($userid);
					if(!empty($activeted)){
						$currency=$database->getUserCurrency($userid);
						$rate=$database->getCurrentRate($userid);

				if($sp==0){//loan application page 1
					$webfee=$database->getAdminSetting('fee');//website fee rate
					

					$usdmaxBorrowerAmt=$database->getAdminSetting('maxBorrowerAmt');//website fee rate
					$usdminBorrowerAmt=$database->getAdminSetting('minBorrowerAmt');//website fee rate
					$maxBorrowerAmt=convertToNative($usdmaxBorrowerAmt, $rate);
					$minBorrowerAmt=convertToNative($usdminBorrowerAmt, $rate);
					//...............................
					$back = 0;
					if (isset($_GET['back'])){
						$back = 1;
					}
			?>
					<form action="process.php" method="post">
					<table style="width:100%">
						<tr>
							<td><h3><?php echo $lang['loanapplic']['loan_applic'];?></h3></td>
						</tr>
						<tr>
							<td>	
								
							</td>
						</tr>
						<tr>
							<td>
								<table style="width:95%" border="0">
									<tr>
										<td colspan="3">
											<?php echo $lang['loanapplic']['accept_fee'];?>
											<ul>
												<li><?php echo $lang['loanapplic']['min_amt']; ?><?php echo "USD : ".$usdminBorrowerAmt." (".$currency.":". $minBorrowerAmt.")"; ?>.</li>
												<li><?php echo $lang['loanapplic']['max_amt']; echo "USD : ".$usdmaxBorrowerAmt." (".$currency.":". $maxBorrowerAmt.")"; ?></li>
												<li><?php echo $lang['loanapplic']['anu_tr_fee']; echo $webfee.'%'; ?></li>
												<li><?php echo $lang['loanapplic']['lend_fee'];?></li>
												<?php
                                                    $bfrstloan=$database->getBorrowerFirstLoan($userid);
							                      // print_r($bfrstloan);
                                                   if(!$bfrstloan)
														{  global $database;
														   $currency_amt=$database->getReg_CurrencyAmount($userid);
															 foreach($currency_amt as $row)
																{
																	 $currency1=$row['currency'];
																	 $amount_reg=$row['Amount'];
																	 //$amt=(float)$amount_reg; //number_format("1000000",2);
																	 $amt=number_format($amount_reg,2);
																	
																}		
                                    
												    
												   ?>
                                                <li><?php echo $lang['loanapplic']['reg_fee_currency'];echo $amt.' '.$currency1; echo $lang['loanapplic']['reg_fee_curr'];?></li>
                                                 <?php
													}
												  ?>

											</ul>
											<?php echo $lang['loanapplic']['accept_charges'];?>
											<br />
											<br />
										</td>
									</tr>	
									<tr>
										<td style="width:100px"><?php echo $lang['loanapplic']['loan_amt'];?></td>
										<td align="left" style="width:150px"><input maxlength="10" type="text" name="amount" value="<?php 
								if($back ==0)
									echo $form->value('amount') ;
								else
									echo $_SESSION['la']['amt'];
								?>" /></td>
										<td style="width:150px"><?php echo $form->error("amount"); ?>&nbsp;</td>
									</tr>
									<tr>
										<td><?php echo $lang['loanapplic']['anul_int_rate'];?></td>
										<td><input maxlength="5" type="text" name="interest" value="<?php 
									if($back ==0)
										echo $form->value('interest')  ;
									else
										echo $_SESSION['la']['intr'];
									?>" /></td>
										<td><?php echo $form->error('interest') ?></td>
									</tr>
									<tr>
										<td><?php echo $lang['loanapplic']['re_paymnet_per'];?></td>
										<td><input maxlength="2" type="text" value="<?php 
										if($back ==0)
											echo $form->value('period') ;
										else
											echo $_SESSION['la']['pd']; ?>"  name="period" /></td>
										<td><?php echo $form->error('period')  ;?></td>
									</tr>
									<tr>
										<td><?php echo $lang['loanapplic']['grace_p'];?></td>
										<td><input maxlength="2" type="text" name="gperiod" value="<?php 
										if($back ==0)
											echo $form->value('gperiod') ;
										else
											echo $_SESSION['la']['gp'];
										?>" /> </td>
										<td><?php 	echo $form->error('gperiod') ?></td>
									</tr>
									<tr>
										<td colspan="3">
											<br /><?php echo $lang['loanapplic']['use_loan'];?><br />
											<textarea name="loanuse" style="width:100%; height:100px"><?php 
									if($back ==0)
										echo $form->value('loanuse');
									else
										echo $_SESSION['la']['lu']; 	
									 ?></textarea><br />
											<?php echo $form->error('loanuse') ?>
										</td>
									</tr>
									<tr>
										<td colspan="3">
									<table>
				<tr><td><b><?php echo $lang['loanapplic']['t_cond'];?></b></td></tr>
				<tr>
				<td colspan=2 align="center" style="vertical-align:text-top"><div align="left" style="border: 1px solid black; padding: 0px 10px 10px; overflow: auto; line-height: 1.5em; width: 90%; height: 130px; background-color: rgb(255, 255, 255);"><?php include_once("editables/legalagreement.html");?></div>
				</td>
						<td><div id="error"></div></td>
					</tr>
					<tr>
					<?php $check = '';
						  $check1 = 'checked ';
							if($form->value('agree') == 0 && $form->value('agree') != ''){
								$check = '';
								$check1 = 'checked ';
							}
							else if($form->value('agree') == 1){
								$check = 'checked ';
								$check1 = '';
							}
					?>
					<td colspan="2" align="left"><b><?php echo $lang['loanapplic']['accept'];?></b> <INPUT TYPE="Radio" name="agree" id="agree" value="1" tabindex="3" <?php echo $check ?> /><?php echo $lang['loanapplic']['acceptyes'];?> &nbsp; &nbsp; &nbsp; &nbsp;  
					<INPUT TYPE="Radio" name="agree" id="agree" value="0" tabindex="4" <?php echo $check1 ?> /><?php echo $lang['loanapplic']['not-accept']; echo $form->error('agree') ?></td>
				</tr>
				</table></td>
									</tr>
									<tr>
									<tr>
										<td align="center" colspan="3">
											<input type="hidden" name="loanapplication" />
											<input type="submit" value='<?php echo $lang['loanapplic']['buttonnext'];?>'  />
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					</form>
								
			<?php
				//...............................

				}
				else if($sp==1){//loan schedule page
				?>
					<form action="process.php" method="post">
					<table width="100%">
						<tr>
							<td>
								<b><h3><?php echo $lang['loanapplic']['loan_applic_con'];?></h3></b>
							</td>
						</tr>
						<tr>
							<td>
								<?php echo $lang['loanapplic']['conferm_shedule'];?><br />
								<? echo $lang['loanapplic']['note_amt_pr'];?>
							</td>
						</tr>
						<tr>
							<td>
								<table>
								<?php
									$loan=$_SESSION['loanapp'];
				                    //print_r($loan);
									//exit;
									$amount=$loan['amount'];
									$interest=$loan['interest'];
									$period=$loan['period'];
									$grace=$loan['grace'];
									$tnc=$loan['tnc'];
									$famt=number_format($amount, 2, ".", ",");

                                     // $bfrstloan=$database->getBorrowerFirstLoan($userid);
                                     
									echo "<tr>".
										"<td><b>".$lang['loanapplic']['amt_req']."</b></td>".
										"<td><b>$currency. $famt</b></td>
									 </tr>
   									<tr>
										<td><b>".$lang['loanapplic']['anul_int_rate']."</b></td>
										<td>$interest%</td>
									</tr>
									<tr>
										<td><b>".$lang['loanapplic']['re_paymnet_per']."</b></td>
										<td>$period Months</td>
									</tr>
									<tr>
										<td><b>".$lang['loanapplic']['grace_p']."</b></td>
										<td>$grace Months</td>
									</tr>";
									

									if($tnc)
										echo "<tr>
										<td><b>".$lang['loanapplic']['t_cond']."</b></td>
										<td><b>".$lang['loanapplic']['accp']."</b></td>
									</tr>";
									
								
								echo "</table>				
							</td>
						</tr>
						<tr>
							<td>";
								echo " ".$lang['loanapplic']['shedule_assume']."</br>";
								$webfee=$database->getAdminSetting('fee');//website fee rate
								$loneAcceptDate=time();
								$sched=$session->getSchedule($amount, $interest, $period, $grace,$loneAcceptDate,$webfee);
								
								echo $sched;
							echo "</td>
						</tr>";
						?>
						<tr>
							<td colspan="3" >
								<a href="index.php?p=9&s=0&back=1"><?php echo $lang['loanapplic']['Back'];?></a><br /><br />
								<input type="hidden" name="confirmApplication" />
								<?php if($tnc){echo"<input type='submit' value=".$lang['loanapplic']['confermbutton']." />";}else{ echo $lang['loanapplic']['goback'];}
								?>
							</td>
						</tr>
					</table>
					</form>
				<?php
				}
				else if($sp==2){
				?>
					
				<?php
				}
					}else{
					
					echo $lang['loanapplic']['sucess_msg'];
					
					}
			}
		}
	}
	
	
	
	
?>