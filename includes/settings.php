<h3 class="subhead"><?php echo $lang['admin']['setting'] ?></h3>
<table class="zebra-striped">
	<tbody>
		
		<tr>
			<td><?php echo $lang['admin']['min_amount'];?></td>
			<td>
				<div id="viewMinBAmount" name="viewMinBAmount" style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
							<div id="mbamountHide" name="mbamountHide"> <?php echo $database->getAdminSetting('minBorrowerAmt');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr></table>
				</div>
				<div id="editMinBAmount" style="display:none; overflow: hidden; "> 
					<input type="text" name="mbamount" id="mbamount" value="<?php echo $database->getAdminSetting('minBorrowerAmt'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditMinBAmount" name="sEditMinBAmount"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditMinBAmount" name="cEditMinBAmount" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>



		<tr>
			<td><?php echo $lang['admin']['firstLoanValue'];?></td>
			<td>
				<div id="first_loan_size" name="first_loan_size" style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
							<div id="first_loan_sizeHide" name="first_loan_sizeHide"> <?php echo $database->getAdminSetting('firstLoanValue');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr>


					</table>
				</div>
				<div id="Editfirst_loan_size" style="display:none; overflow: hidden; "> 
					<input type="text" name="first_loanValue" id="first_loanValue" value="<?php echo $database->getAdminSetting('firstLoanValue'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditfirst_loanValue" name="sEditfirst_loanValue"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditfirst_loanValue" name="cEditfirst_loanValue" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>


			<tr>
			<td><?php echo $lang['admin']['secondLoanValue'];?></td>
			<td>
				<div id="second_loan_size" name="second_loan_size" style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
							<div id="second_loan_sizeHide" name="second_loan_sizeHide"> <?php echo $database->getAdminSetting('secondLoanValue');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr>


					</table>
				</div>
				<div id="Editsecond_loan_size" style="display:none; overflow: hidden; "> 
					<input type="text" name="second_loanValue" id="second_loanValue" value="<?php echo $database->getAdminSetting('secondLoanValue'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditsecond_loanValue" name="sEditsecond_loanValue"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditsecond_loanValue" name="cEditsecond_loanValue" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>



	<tr>
			<td><?php echo $lang['admin']['thirdLoanValue'];?></td>
			<td>
				<div id="third_loan_size" name="third_loan_size" style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
							<div id="third_loan_sizeHide" name="third_loan_sizeHide"> <?php echo $database->getAdminSetting('thirdLoanValue');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr>


					</table>
				</div>
				<div id="Editthird_loan_size" style="display:none; overflow: hidden; "> 
					<input type="text" name="third_loanValue" id="third_loanValue" value="<?php echo $database->getAdminSetting('thirdLoanValue'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditthird_loanValue" name="sEditthird_loanValue"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditthird_loanValue" name="cEditthird_loanValue" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>



		<tr>
			<td><?php echo $lang['admin']['nextLoanValue'];?></td>
			<td>
				<div id="next_loan_size" name="next_loan_size" style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
							<div id="next_loan_sizeHide" name="next_loan_sizeHide"> <?php echo $database->getAdminSetting('nextLoanValue');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr>
					</table>
				</div>


				<div id="Editnext_loan_size" style="display:none; overflow: hidden; "> 
					<input type="text" name="next_loanValue" id="next_loanValue" value="<?php echo $database->getAdminSetting('nextLoanValue'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditnext_loanValue" name="sEditnext_loanValue"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditnext_loanValue" name="cEditnext_loanValue" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>

		<!--<tr>
			<td><?php echo $lang['admin']['first_loan_per'];?></td>
			<td>
				<div id="viewFirstLoanPercentage" name="viewFirstLoanPercentage" style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
							<div id="flpercentHide" name="flpercentHide"> <?php echo $database->getAdminSetting('firstLoanPercentage');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr></table>
				</div>
				<div id="editFirstLoanPercentage" style="display:none; overflow: hidden; "> 
					<input type="text" name="flpercent" id="flpercent" value="<?php echo $database->getAdminSetting('firstLoanPercentage'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditFirstLoanPercentage" name="sEditFirstLoanPercentage"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditFirstLoanPercentage" name="cEditFirstLoanPercentage" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>-->
		<tr>
			<td><?php echo $lang['admin']['second_loan_per'];?></td>
			<td>
				<div id="viewSecondLoanPercentage" name="viewSecondLoanPercentage" style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
							<div id="slpercentHide" name="slpercentHide"> <?php echo $database->getAdminSetting('secondLoanPercentage');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr></table>
				</div>
				<div id="editSecondLoanPercentage" style="display:none; overflow: hidden; "> 
					<input type="text" name="slpercent" id="slpercent" value="<?php echo $database->getAdminSetting('secondLoanPercentage'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditSecondLoanPercentage" name="sEditSecondLoanPercentage"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditSecondLoanPercentage" name="cEditSecondLoanPercentage" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>
		<tr>
			<td><?php echo $lang['admin']['next_loan_per'];?></td>
			<td>
				<div id="viewNextLoanPercentage" name="viewNextLoanPercentage" style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
							<div id="nlpercentHide" name="nlpercentHide"> <?php echo $database->getAdminSetting('nextLoanPercentage');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr></table>
				</div>
				<div id="editNextLoanPercentage" style="display:none; overflow: hidden; "> 
					<input type="text" name="nlpercent" id="nlpercent" value="<?php echo $database->getAdminSetting('nextLoanPercentage'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditNextLoanPercentage" name="sEditNextLoanPercentage"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditNextLoanPercentage" name="cEditNextLoanPercentage" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>
		
		<tr>
			<td><?php echo $lang['admin']['TimeThrshld_below'];?></td>
			<td>
				<div id="TimeThrshld" name="TimeThrshld" style="overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
						<div id="TimeThrshldHide" name="TimeThrshldHide"> <?php echo $database->getAdminSetting('TimeThrshld');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr>
					</table>
				</div>
				<div id="EditTimeThrshld" style="display:none; overflow: hidden; "> 
					<input type="text" name="TimeThrshldValue" id="TimeThrshldValue" value="<?php echo $database->getAdminSetting('TimeThrshld'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditTimeThrshldValue" name="#sEditTimeThrshldValue"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditTimeThrshldValue" name="cEditTimeThrshldValue" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>
		<!------------ Enhancement by Mohit on date  28-11-13 -------->
		<tr>
			<td><?php echo $lang['admin']['TimeThrshld_Mid_Range1'];?></td>
			<td>
				<div id="TimeThrshldMid1" name="TimeThrshldMid1" style="overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
						<div id="TimeThrshldMid1Hide" name="TimeThrshldMid1Hide"> <?php echo $database->getAdminSetting('TimeThrshldMid1');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr>
					</table>
				</div>
				<div id="EditTimeThrshldMid1" style="display:none; overflow: hidden; "> 
					<input type="text" name="TimeThrshldMid1Value" id="TimeThrshldMid1Value" value="<?php echo $database->getAdminSetting('TimeThrshldMid1'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditTimeThrshldMid1Value" name="#sEditTimeThrshldMid1Value"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditTimeThrshldMid1Value" name="cEditTimeThrshldMid1Value" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>
		
		<tr>
			<td><?php echo $lang['admin']['TimeThrshld_Mid_Range2'];?></td>
			<td>
				<div id="TimeThrshldMid2" name="TimeThrshldMid2" style="overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
						<div id="TimeThrshldMid2Hide" name="TimeThrshldMid2Hide"> <?php echo $database->getAdminSetting('TimeThrshldMid2');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr>
					</table>
				</div>
				<div id="EditTimeThrshldMid2" style="display:none; overflow: hidden; "> 
					<input type="text" name="TimeThrshldMid2Value" id="TimeThrshldMid2Value" value="<?php echo $database->getAdminSetting('TimeThrshldMid2'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditTimeThrshldMid2Value" name="#sEditTimeThrshldMid2Value"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditTimeThrshldMid2Value" name="cEditTimeThrshldMid2Value" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>
		<!----------- End here ------------>
		<tr>
			<td><?php echo $lang['admin']['TimeThrshld_above'];?></td>
			<td>
				<div id="TimeThrshld_above" name="TimeThrshld_above" style="overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
						<div id="TimeThrshld_aboveHide" name="TimeThrshld_aboveHide"> <?php echo $database->getAdminSetting('TimeThrshld_above');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr>
					</table>
				</div>
				<div id="EditTimeThrshld_above" style="display:none; overflow: hidden; "> 
					<input type="text" name="TimeThrshld_aboveValue" id="TimeThrshld_aboveValue" value="<?php echo $database->getAdminSetting('TimeThrshld_above'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditTimeThrshld_aboveValue" name="#sEditTimeThrshld_aboveValue"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditTimeThrshld_aboveValue" name="cEditTimeThrshld_aboveValue" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>

		<tr>
			<td><?php echo $lang['admin']['MinRepayRate'];?></td>
			<td>
				<div id="MinRepayRate" name="MinRepayRate" style="overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
						<div id="MinRepayRateHide" name="MinRepayRateHide"> <?php echo $database->getAdminSetting('MinRepayRate');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr>
					</table>
				</div>
				<div id="EditMinRepayRate" style="display:none; overflow: hidden; "> 
					<input type="text" name="MinRepayRateValue" id="MinRepayRateValue" value="<?php echo $database->getAdminSetting('MinRepayRate'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditMinRepayRateValue" name="#sEditMinRepayRateValue"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditMinRepayRateValue" name="cEditMinRepayRateValue" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>





		<tr>
			<td><?php echo $lang['admin']['maxLoanPeriod'];?></td>
			<td>
				<div id="max_loan_period" name="max_loan_period" style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
							<div id="max_loan_periodHide" name="max_loan_periodHide"> <?php echo $database->getAdminSetting('maxPeriodValue');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr>
					</table>
				</div>
				<div id="Editmax_loan_period" style="display:none; overflow: hidden; "> 
					<input type="text" name="max_periodValue" id="max_periodValue" value="<?php echo $database->getAdminSetting('maxPeriodValue'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditmax_periodValue" name="sEditmax_periodValue"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditmax_periodValue" name="cEditmax_periodValue" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>
		<tr>
			<td><?php echo $lang['admin']['maxLoanAppGracePeriod'];?></td>
			<td>
				<div id="max_Loan_App_Grace_period" name="max_Loan_App_Grace_period" style="overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
						<div id="max_Loan_App_Grace_periodHide" name="max_Loan_App_Grace_periodHide"> <?php echo $database->getAdminSetting('maxLoanAppGracePeriod');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr>
					</table>
				</div>
				<div id="Editmax_Loan_App_Grace_period" style="display:none; overflow: hidden; "> 
					<input type="text" name="max_Loan_App_Grace_periodValue" id="max_Loan_App_Grace_periodValue" value="<?php echo $database->getAdminSetting('maxLoanAppGracePeriod'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditmax_Loan_App_Grace_periodValue" name="#sEditmax_Loan_App_Grace_periodValue"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditmax_Loan_App_Grace_periodValue" name="cEditmax_Loan_App_Grace_periodValue" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>



		<tr>
			<td><?php echo $lang['admin']['maxLoanAppInterest'];?></td>
			<td>
				<div id="max_Loan_App_Interest" name="max_Loan_App_Interest" style="overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
						<div id="max_Loan_App_InterestHide" name="max_Loan_App_InterestHide"> <?php echo $database->getAdminSetting('maxLoanAppInterest');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr>
					</table>
				</div>
				<div id="Editmax_Loan_App_Interest" style="display:none; overflow: hidden; "> 
					<input type="text" name="max_Loan_App_InterestValue" id="max_Loan_App_InterestValue" value="<?php echo $database->getAdminSetting('maxLoanAppInterest'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditmax_Loan_App_InterestValue" name="#sEditmax_Loan_App_InterestValue"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditmax_Loan_App_InterestValue" name="cEditmax_Loan_App_InterestValue" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>






<tr>
			<td width="300px"><?php echo $lang['admin']['min_Fund_lender'];?></td>
			<td> 
				<div id="viewMinAmount" name="viewMinAmount"	style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
							<td>
								<div id="mamountHide" name="mamountHide"> <?php echo $database->getAdminSetting('minAmount');?> </div>
							</td>
							<td>
								<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
							</td>
					</tr></table>
				</div>
				<div id="editMinAmount" style="display:none; overflow: hidden; "> 
					<input type="text" name="mamount" id="mamount" value="<?php echo $database->getAdminSetting('minAmount');  ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditMinAmount" name="sEditMinAmount"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle"	id="cEditMinAmount" name="cEditMinAmount" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>
		<tr>
			<td><?php echo $lang['admin']['Dead_line'];?></td>
			<td> 
				<div id="viewDeadLine" name="viewDeadLine" style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
							<div id="deadlineHide" name="deadlineHide"> <?php echo $database->getAdminSetting('deadline');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr></table>
				</div>
				<div id="editDeadLine" style="display:none; overflow: hidden; ">
					<input type="text" name="deadline" id="deadline" value="<?php echo $database->getAdminSetting('deadline');  ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sDeadLine" name="sDeadLine"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cDeadLine" name="cDeadLine" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>

		<!-- <tr>
			<td><?php echo $lang['admin']['max_amount']; ?></td>
			<td> 
				<div id="viewMaxBAmount" name="viewMaxBAmount" style="display:display; overflow: hidden;"> 
				<table class="detail" style="width:auto"><tr>
					<td>
						<div id="maxbamountHide" name="maxbamountHide"> <?php echo $database->getAdminSetting('maxBorrowerAmt');?> </div>
					</td>
					<td>
						<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
					</td>
				</tr></table>
				</div>
				<div id="editMaxBAmount" name="editMaxBAmount" style="display:none; overflow: hidden; ">
				<input type="text" name="maxbamount" id="maxbamount" value="<?php echo $database->getAdminSetting('maxBorrowerAmt');  ?>" size="2"/> 
				<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditMaxBAmount" name="sEditMaxBAmount"> 
				<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditMaxBAmount" name="cEditMaxBAmount" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr> -->
		<tr>
			<td><?php echo $lang['admin']['web_fee'];?></td>
			<td> 
				<div id="viewFee" name="viewFee" style="display:display;	overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td><div id="feeHide" name="feeHide"> <?php echo $database->getAdminSetting('Fee');?> </div></td>
						<td><input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit"></td></tr>
					</table>
				</div>
				<div id="editFee" name="editFee" style="display:none; overflow: hidden; "> 
				<input type="text" name="fee" id="fee" value="<?php echo $database->getAdminSetting('Fee');  ?>" size="2"/>
				<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sFee" name="sFee"> 
				<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cFee" name="cFee" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>



			<tr>
			<td><?php echo $lang['admin']['paypal_tran_fee'];?></td>
			<td>
				<div id="paypal_tran" name="paypal_tran" style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
							<div id="paypal_tranHide" name="paypal_tranHide"> <?php echo $database->getAdminSetting('PaypalTransaction');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr>


					</table>
				</div>
				<div id="editPaypal_tranValue" style="display:none; overflow: hidden; "> 
					<input type="text" name="paypal_tranValue" id="paypal_tranValue" value="<?php echo $database->getAdminSetting('PaypalTransaction'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditPaypal_tranValue" name="sEditPaypal_tranValue"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditPaypal_tranValue" name="cEditPaypal_tranValue" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>



		<tr>
			<td><?php echo $lang['admin']['resch_allow'];?></td>
			<td>
				<div id="resch_allow" name="resch_allow" style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
							<div id="resch_allowHide" name="resch_allowHide"> <?php echo $database->getAdminSetting('RescheduleAllow');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr></table>
				</div>
				<div id="editResch_allowValue" style="display:none; overflow: hidden; "> 
					<input type="text" name="resch_allowValue" id="resch_allowValue" value="<?php echo $database->getAdminSetting('RescheduleAllow'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditResch_allowValue" name="sEditResch_allowValue"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditResch_allowValue" name="cEditResch_allowValue" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
			</tr>



		
		<tr>
			<td><?php echo $lang['admin']['maxGracePeriod'];?></td>
			<td>
				<div id="max_Grace_period" name="max_Grace_period" style="overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
						<div id="max_Grace_periodHide" name="max_Grace_periodHide"> <?php echo $database->getAdminSetting('maxGraceperiodValue');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr>
					</table>
				</div>
				<div id="Editmax_Grace_period" style="display:none; overflow: hidden; "> 
					<input type="text" name="max_GraceperiodValue" id="max_GraceperiodValue" value="<?php echo $database->getAdminSetting('maxGraceperiodValue'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditmax_GraceperiodValue" name="#sEditmax_GraceperiodValue"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditmax_GraceperiodValue" name="cEditmax_GraceperiodValue" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>
		<tr>
			<td><?php echo $lang['admin']['maxRepayPeriod'];?></td>
			<td>
				<div id="maxRepayPeriod" name="maxRepayPeriod" style="overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
						<div id="maxRepayPeriodHide" name="maxRepayPeriodHide"> <?php echo $database->getAdminSetting('maxRepayPeriod');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr>
					</table>
				</div>
				<div id="EditmaxRepayPeriod" style="display:none; overflow: hidden; "> 
					<input type="text" name="maxRepayPeriodValue" id="maxRepayPeriodValue" value="<?php echo $database->getAdminSetting('maxRepayPeriod'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditmaxRepayPeriodValue" name="#sEditmaxRepayPeriodValue"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditmaxRepayPeriodValue" name="cEditmaxRepayPeriodValue" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>


		<tr>
			<td><?php echo $lang['admin']['late_threshold'];?></td>
			<td>
				<div id="late_threshold" name="late_threshold" style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
							<div id="late_thresholdpercentHide" name="late_thresholdpercentHide"> <?php echo $database->getAdminSetting('LATENESS_THRESHOLD');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr></table>
				</div>
				<div id="editLate_thresholdPercentage" style="display:none; overflow: hidden; "> 
					<input type="text" name="late_thresholdpercent" id="late_thresholdpercent" value="<?php echo $database->getAdminSetting('LATENESS_THRESHOLD'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditLate_thresholdPercentage" name="sEditLate_thresholdPercentage"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditLate_thresholdPercentage" name="cEditLate_thresholdPercentage" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>



		<tr>
			<td><?php echo $lang['admin']['risk_0-30'];?></td>
			<td>
				<div id="risk0-30" name="risk0-30" style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
							<div id="risk0-30percentHide" name="risk0-30percentHide"> <?php echo $database->getAdminSetting('PAR-0-30-DAYS');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr></table>
				</div>
				<div id="editRisk0-30Percentage" style="display:none; overflow: hidden; "> 
					<input type="text" name="risk0-30percent" id="risk0-30percent" value="<?php echo $database->getAdminSetting('PAR-0-30-DAYS'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditRisk0-30Percentage" name="sEditRisk0-30Percentage"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditRisk0-30Percentage" name="cEditRisk0-30Percentage" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>
		<tr>
			<td><?php echo $lang['admin']['risk_31-60'];?></td>
			<td>
				<div id="risk31-60" name="risk31-60" style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
							<div id="risk31-60percentHide" name="risk31-60percentHide"> <?php echo $database->getAdminSetting('PAR-31-60-DAYS');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr></table>
				</div>
				<div id="editRisk31-60Percentage" style="display:none; overflow: hidden; "> 
					<input type="text" name="risk31-60percent" id="risk31-60percent" value="<?php echo $database->getAdminSetting('PAR-31-60-DAYS'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditRisk31-60Percentage" name="sEditRisk31-60Percentage"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditRisk31-60Percentage" name="cEditRisk31-60Percentage" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>
		<tr>
			<td><?php echo $lang['admin']['risk_61-90'];?></td>
			<td>
				<div id="risk61-90" name="risk61-90" style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
							<div id="risk61-90percentHide" name="risk61-90percentHide"> <?php echo $database->getAdminSetting('PAR-61-90-DAYS');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr></table>
				</div>
				<div id="editRisk61-90Percentage" style="display:none; overflow: hidden; "> 
					<input type="text" name="risk61-90percent" id="risk61-90percent" value="<?php echo $database->getAdminSetting('PAR-61-90-DAYS'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditRisk61-90Percentage" name="sEditRisk61-90Percentage"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditRisk61-90Percentage" name="cEditRisk61-90Percentage" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>
		<tr>
			<td><?php echo $lang['admin']['risk_91-180'];?></td>
			<td>
				<div id="risk91-180" name="risk91-180" style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
							<div id="risk91-180percentHide" name="risk91-180percentHide"> <?php echo $database->getAdminSetting('PAR-91-180-DAYS');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr></table>
				</div>
				<div id="editRisk91-180Percentage" style="display:none; overflow: hidden; "> 
					<input type="text" name="risk91-180percent" id="risk91-180percent" value="<?php echo $database->getAdminSetting('PAR-91-180-DAYS'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditRisk91-180Percentage" name="sEditRisk91-180Percentage"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditRisk91-180Percentage" name="cEditRisk91-180Percentage" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>
		<tr>
			<td><?php echo $lang['admin']['risk_181-over'];?></td>
			<td>
				<div id="risk181-over" name="risk181-over" style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
							<div id="risk181-overpercentHide" name="risk181-overpercentHide"> <?php echo $database->getAdminSetting('PAR-181-OVER-DAYS');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr></table>
				</div>
				<div id="editRisk181-overPercentage" style="display:none; overflow: hidden; "> 
					<input type="text" name="risk181-overpercent" id="risk181-overpercent" value="<?php echo $database->getAdminSetting('PAR-181-OVER-DAYS'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditRisk181-overPercentage" name="sEditRisk181-overPercentage"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditRisk181-overPercentage" name="cEditRisk181-overPercentage" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>
		<tr>
			<td><?php echo $lang['admin']['RepaymentReportThrshld'];?></td>
			<td>
				<div id="RepaymentReportThrshld" name="RepaymentReportThrshld" style="overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
						<div id="RepaymentReportThrshldHide" name="RepaymentReportThrshldHide"> <?php echo $database->getAdminSetting('RepaymentReportThrshld');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr>
					</table>
				</div>
				<div id="EditRepaymentReportThrshld" style="display:none; overflow: hidden; "> 
					<input type="text" name="RepaymentReportThrshldValue" id="RepaymentReportThrshldValue" value="<?php echo $database->getAdminSetting('RepaymentReportThrshld'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditRepaymentReportThrshldValue" name="#sEditRepaymentReportThrshldValue"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditRepaymentReportThrshldValue" name="cEditRepaymentReportThrshldValue" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>

		<tr>
			<td><?php echo $lang['admin']['MinFbFrnds'];?></td>
			<td>
				<div id="MinFbFrnds" name="MinFbFrnds" style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
							<div id="MinFbFrndsHide" name="MinFbFrndsHide"> <?php echo $database->getAdminSetting('MIN_FB_FRNDS');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr>


					</table>
				</div>
				<div id="EditMinFbFrnds" style="display:none; overflow: hidden; "> 
					<input type="text" name="MinFbFrndsValue" id="MinFbFrndsValue" value="<?php echo $database->getAdminSetting('MIN_FB_FRNDS'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditMinFbFrndsValue" name="sEditMinFbFrndsValue"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditMinFbFrndsValue" name="cEditMinFbFrndsValue" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>

		<tr>
			<td><?php echo $lang['admin']['MinFbMonths'];?></td>
			<td>
				<div id="MinFbMonths" name="MinFbMonths" style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
							<div id="MinFbMonthsHide" name="MinFbMonthsHide"> <?php echo $database->getAdminSetting('MIN_FB_MONTHS');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr>


					</table>
				</div>
				<div id="EditMinFbMonths" style="display:none; overflow: hidden; "> 
					<input type="text" name="MinFbMonthsValue" id="MinFbMonthsValue" value="<?php echo $database->getAdminSetting('MIN_FB_MONTHS'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditMinFbMonthsValue" name="sEditMinFbMonthsValue"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditMinFbMonthsValue" name="cEditMinFbMonthsValue" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>

		<tr>
			<td><?php echo $lang['admin']['MinEndorser'];?></td>
			<td>
				<div id="MinEndorser" name="MinEndorser" style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
							<div id="MinEndorserHide" name="MinEndorserHide"> <?php echo $database->getAdminSetting('MinEndorser');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr>


					</table>
				</div>
				<div id="EditMinEndorser" style="display:none; overflow: hidden; "> 
					<input type="text" name="MinEndorserValue" id="MinEndorserValue" value="<?php echo $database->getAdminSetting('MinEndorser'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditMinEndorserValue" name="sEditMinEndorserValue"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditMinEndorserValue" name="cEditMinEndorserValue" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>

		<tr>
			<td><?php echo $lang['admin']['AgainReminderDays'];?></td>
			<td>
				<div id="AgainReminderDays" name="AgainReminderDays" style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
							<div id="AgainReminderDaysHide" name="AgainReminderDaysHide"> <?php echo $database->getAdminSetting('AgainReminderDays');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr>


					</table>
				</div>
				<div id="EditAgainReminderDays" style="display:none; overflow: hidden; "> 
					<input type="text" name="AgainReminderDaysValue" id="AgainReminderDaysValue" value="<?php echo $database->getAdminSetting('AgainReminderDays'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditAgainReminderDaysValue" name="sEditAgainReminderDaysValue"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditAgainReminderDaysValue" name="cEditAgainReminderDaysValue" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>

		<tr>
			<td><?php echo $lang['admin']['AgainReminderAmt'];?></td>
			<td>
				<div id="AgainReminderAmt" name="AgainReminderAmt" style="display:display; overflow: hidden;"> 
					<table class="detail" style="width:auto"><tr>
						<td>
							<div id="AgainReminderAmtHide" name="AgainReminderAmtHide"> <?php echo $database->getAdminSetting('AgainReminderAmt');?> </div>
						</td>
						<td>
							<input Type="image" SRC="images/layout/icons/edit.png"  BORDER="0" ALT="Edit">
						</td>
					</tr>


					</table>
				</div>
				<div id="EditAgainReminderAmt" style="display:none; overflow: hidden; "> 
					<input type="text" name="AgainReminderAmtValue" id="AgainReminderAmtValue" value="<?php echo $database->getAdminSetting('AgainReminderAmt'); ?>" size="2"/> 
					<input Type="image" SRC="images/layout/icons/tick.png"  BORDER="0" ALT="Save" id="sEditAgainReminderAmtValue" name="sEditAgainReminderAmtValue"> 
					<input Type="image" SRC="images/layout/icons/x.png"  BORDER="0" ALT="Cancle" id="cEditAgainReminderAmtValue" name="cEditAgainReminderAmtValue" >
				</div>
			</td>
			<td><?php echo $form->error('mamount'); ?></td>
		</tr>




	</tbody>
</table>