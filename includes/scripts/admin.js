$(document).ready(function(){
		var error=0;
		
		
		//------------One for each set------------------//
		//check for the Minimum Fund a lender can provide
		$("#viewMinAmount").click(
			function(event){
				$(this).hide("fast");
				$("#editMinAmount").show("fast");
			}
		);
		$("#cEditMinAmount").click(
			function(event){
				$("#viewMinAmount").show("fast");
				$("#editMinAmount").hide("fast");
			}
		);
		
		$("#sEditMinAmount").click(
			function(event){

				var un=$("#mamount").val();
				$.get("includes/saveSettings.php",{mamount:un},
					  function(data){
						if(data == 0){
							$("#mamountHide").html (un);
							$("#viewMinAmount").show("fast");
							$("#editMinAmount").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
	
//----------------------Till Here----------------------//
		//------------One for each set------------------//
		//check for the Dead line for escrowing funds (#days)
		$("#viewDeadLine").click(
			function(event){
				$(this).hide("fast");
				$("#editDeadLine").show("fast");
			}
		);
		$("#cDeadLine").click(
			function(event){
				$("#viewDeadLine").show("fast");
				$("#editDeadLine").hide("fast");
			}
		);
		
		$("#sDeadLine").click(
			function(event){

				var un=$("#deadline").val();
				$.get("includes/saveSettings.php",{deadline:un},
					  function(data){
						if(data == 0){
							$("#deadlineHide").html (un);
							$("#viewDeadLine").show("fast");
							$("#editDeadLine").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
	
//----------------------Till Here----------------------//
		//------------One for each set------------------//
		//check for the Minimum amount to be requested be borrower
		$("#viewMinBAmount").click(
			function(event){
				$(this).hide("fast");
				$("#editMinBAmount").show("fast");
			}
		);
		$("#cEditMinBAmount").click(
			function(event){
				$("#viewMinBAmount").show("fast");
				$("#editMinBAmount").hide("fast");
			}
		);
		
		$("#sEditMinBAmount").click(
			function(event){

				var un=$("#mbamount").val();
				$.get("includes/saveSettings.php",{mbamount:un},
					  function(data){
						if(data == 0){
							$("#mbamountHide").html (un);
							$("#viewMinBAmount").show("fast");
							$("#editMinBAmount").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
	
//----------------------Till Here----------------------//
		//------------One for each set------------------//
		//check for the Maximum amount to be requested be borrower
		$("#viewMaxBAmount").click(
			function(event){
				$(this).hide("fast");
				$("#editMaxBAmount").show("fast");
			}
		);
		$("#cEditMaxBAmount").click(
			function(event){
				$("#viewMaxBAmount").show("fast");
				$("#editMaxBAmount").hide("fast");
			}
		);
		
		$("#sEditMaxBAmount").click(
			function(event){

				var un=$("#maxbamount").val();
				$.get("includes/saveSettings.php",{maxbamount:un},
					  function(data){
						if(data == 0){
							$("#maxbamountHide").html (un);
							$("#viewMaxBAmount").show("fast");
							$("#editMaxBAmount").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
	
//----------------------Till Here----------------------//
		//------------One for each set------------------//
		//check for the Website Fee rate
		$("#viewFee").click(
			function(event){
				$(this).hide("fast");
				$("#editFee").show("fast");
			}
		);
		$("#cFee").click(
			function(event){
				$("#viewFee").show("fast");
				$("#editFee").hide("fast");
			}
		);
		
		$("#sFee").click(
			function(event){

				var un=$("#fee").val();
				$.get("includes/saveSettings.php",{fee:un},
					  function(data){
						if(data == 0){
							$("#feeHide").html (un);
							$("#viewFee").show("fast");
							$("#editFee").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
	

//----------------------Till Here----------------------//
		//------------One for each set------------------//
		//check for the First Loan Percentage to be requested by borrower
		$("#viewFirstLoanPercentage").click(
			function(event){
				$(this).hide("fast");
				$("#editFirstLoanPercentage").show("fast");
			}
		);
		$("#cEditFirstLoanPercentage").click(
			function(event){
				$("#viewFirstLoanPercentage").show("fast");
				$("#editFirstLoanPercentage").hide("fast");
			}
		);
		
		$("#sEditFirstLoanPercentage").click(
			function(event){

				var un=$("#flpercent").val();
				$.get("includes/saveSettings.php",{flpercent:un},
					  function(data){
						if(data == 0){
							$("#flpercentHide").html (un);
							$("#viewFirstLoanPercentage").show("fast");
							$("#editFirstLoanPercentage").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
	
//----------------------Till Here----------------------//

//------------One for each set------------------//
		//check for the First Loan Percentage to be requested by borrower
		$("#viewSecondLoanPercentage").click(
			function(event){
				$(this).hide("fast");
				$("#editSecondLoanPercentage").show("fast");
			}
		);
		$("#cEditSecondLoanPercentage").click(
			function(event){
				$("#viewSecondLoanPercentage").show("fast");
				$("#editSecondLoanPercentage").hide("fast");
			}
		);
		
		$("#sEditSecondLoanPercentage").click(
			function(event){

				var un=$("#slpercent").val();
				$.get("includes/saveSettings.php",{slpercent:un},
					  function(data){
						if(data == 0){
							$("#slpercentHide").html (un);
							$("#viewSecondLoanPercentage").show("fast");
							$("#editSecondLoanPercentage").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);


	
//----------------------Till Here----------------------//
	//------------One for each set------------------//
		//check for the Next Loan Percentage to be requested by borrower
		$("#viewNextLoanPercentage").click(
			function(event){
				$(this).hide("fast");
				$("#editNextLoanPercentage").show("fast");
			}
		);
		$("#cEditNextLoanPercentage").click(
			function(event){
				$("#viewNextLoanPercentage").show("fast");
				$("#editNextLoanPercentage").hide("fast");
			}
		);
		
		$("#sEditNextLoanPercentage").click(
			function(event){

				var un=$("#nlpercent").val();
				$.get("includes/saveSettings.php",{nlpercent:un},
					  function(data){
						if(data == 0){
							$("#nlpercentHide").html (un);
							$("#viewNextLoanPercentage").show("fast");
							$("#editNextLoanPercentage").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
	
//----------------------Till Here----------------------//
//------------One for each set------------------//
		//check for the risk between 0 to 30 days
		$("#risk0-30").click(
			function(event){
				$(this).hide("fast");
				$("#editRisk0-30Percentage").show("fast");
			}
		);
		$("#cEditRisk0-30Percentage").click(
			function(event){
				$("#risk0-30").show("fast");
				$("#editRisk0-30Percentage").hide("fast");
			}
		);
		
		$("#sEditRisk0-30Percentage").click(
			function(event){

				var un=$("#risk0-30percent").val();
				$.get("includes/saveSettings.php",{risk1percent:un},
					  function(data){
						if(data == 0){
							$("#risk0-30percentHide").html (un);
							$("#risk0-30").show("fast");
							$("#editRisk0-30Percentage").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
	
//----------------------Till Here----------------------//
//------------One for each set------------------//
		//check for the risk between 0 to 30 days
		$("#risk31-60").click(
			function(event){
				$(this).hide("fast");
				$("#editRisk31-60Percentage").show("fast");
			}
		);
		$("#cEditRisk31-60Percentage").click(
			function(event){
				$("#risk31-60").show("fast");
				$("#editRisk31-60Percentage").hide("fast");
			}
		);
		
		$("#sEditRisk31-60Percentage").click(
			function(event){

				var un=$("#risk31-60percent").val();
				$.get("includes/saveSettings.php",{risk2percent:un},
					  function(data){
						if(data == 0){
							$("#risk31-60percentHide").html (un);
							$("#risk31-60").show("fast");
							$("#editRisk31-60Percentage").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
	
//----------------------Till Here----------------------//
//------------One for each set------------------//
		//check for the risk between 0 to 30 days
		$("#risk61-90").click(
			function(event){
				$(this).hide("fast");
				$("#editRisk61-90Percentage").show("fast");
			}
		);
		$("#cEditRisk61-90Percentage").click(
			function(event){
				$("#risk61-90").show("fast");
				$("#editRisk61-90Percentage").hide("fast");
			}
		);
		
		$("#sEditRisk61-90Percentage").click(
			function(event){

				var un=$("#risk61-90percent").val();
				$.get("includes/saveSettings.php",{risk3percent:un},
					  function(data){
						if(data == 0){
							$("#risk61-90percentHide").html (un);
							$("#risk61-90").show("fast");
							$("#editRisk61-90Percentage").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
	
//----------------------Till Here----------------------//
//------------One for each set------------------//
		//check for the risk between 0 to 30 days
		$("#risk91-180").click(
			function(event){
				$(this).hide("fast");
				$("#editRisk91-180Percentage").show("fast");
			}
		);
		$("#cEditRisk91-180Percentage").click(
			function(event){
				$("#risk91-180").show("fast");
				$("#editRisk91-180Percentage").hide("fast");
			}
		);
		
		$("#sEditRisk91-180Percentage").click(
			function(event){

				var un=$("#risk91-180percent").val();
				$.get("includes/saveSettings.php",{risk4percent:un},
					  function(data){
						if(data == 0){
							$("#risk91-180percentHide").html (un);
							$("#risk91-180").show("fast");
							$("#editRisk91-180Percentage").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
	
//----------------------Till Here----------------------//
//------------One for each set------------------//
		//check for the risk between 0 to 30 days
		$("#risk181-over").click(
			function(event){
				$(this).hide("fast");
				$("#editRisk181-overPercentage").show("fast");
			}
		);
		$("#cEditRisk181-overPercentage").click(
			function(event){
				$("#risk181-over").show("fast");
				$("#editRisk181-overPercentage").hide("fast");
			}
		);
		
		$("#sEditRisk181-overPercentage").click(
			function(event){

				var un=$("#risk181-overpercent").val();
				$.get("includes/saveSettings.php",{risk5percent:un},
					  function(data){
						if(data == 0){
							$("#risk181-overpercentHide").html (un);
							$("#risk181-over").show("fast");
							$("#editRisk181-overPercentage").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
	
//----------------------Till Here----------------------//
//------------One for each set------------------//
		//check for the risk between 0 to 30 days
		$("#late_threshold").click(
			function(event){
				$(this).hide("fast");
				$("#editLate_thresholdPercentage").show("fast");
			}
		);
		$("#cEditLate_thresholdPercentage").click(
			function(event){
				$("#late_threshold").show("fast");
				$("#editLate_thresholdPercentage").hide("fast");
			}
		);
		
		$("#sEditLate_thresholdPercentage").click(
			function(event){

				var un=$("#late_thresholdpercent").val();
				$.get("includes/saveSettings.php",{latethreshold:un},
					  function(data){
						if(data == 0){
							$("#late_thresholdpercentHide").html (un);
							$("#late_threshold").show("fast");
							$("#editLate_thresholdPercentage").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
	
//----------------------Till Here----------------------//
//------------One for each set------------------//
		//check for the risk between 0 to 30 days
		$("#resch_allow").click(
			function(event){
				$(this).hide("fast");
				$("#editResch_allowValue").show("fast");
			}
		);
		$("#cEditResch_allowValue").click(
			function(event){
				$("#resch_allow").show("fast");
				$("#editResch_allowValue").hide("fast");
			}
		);
		
		$("#sEditResch_allowValue").click(
			function(event){

				var un=$("#resch_allowValue").val();
				$.get("includes/saveSettings.php",{reschallow:un},
					  function(data){
						if(data == 0){
							$("#resch_allowHide").html (un);
							$("#resch_allow").show("fast");
							$("#editResch_allowValue").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
	
//----------------------Till Here----------------------//
//------------One for each set------------------//
		//check for the risk between 0 to 30 days
		$("#paypal_tran").click(
			function(event){
				$(this).hide("fast");
				$("#editPaypal_tranValue").show("fast");
			}
		);
		$("#cEditPaypal_tranValue").click(
			function(event){
				$("#paypal_tran").show("fast");
				$("#editPaypal_tranValue").hide("fast");
			}
		);
		
		$("#sEditPaypal_tranValue").click(
			function(event){

				var un=$("#paypal_tranValue").val();
				$.get("includes/saveSettings.php",{paypalTran:un},
					  function(data){
						if(data == 0){
							$("#paypal_tranHide").html (un);
							$("#paypal_tran").show("fast");
							$("#editPaypal_tranValue").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
	
//----------------------Till Here----------------------//
$("#first_loan_size").click(
			function(event){
				$(this).hide("fast");
				$("#Editfirst_loan_size").show("fast");
			}
		);
		$("#cEditfirst_loanValue").click(
			function(event){
				$("#first_loan_size").show("fast");
				$("#Editfirst_loan_size").hide("fast");
			}
		);
		
		$("#sEditfirst_loanValue").click(
			function(event){

				var un=$("#first_loanValue").val();
				$.get("includes/saveSettings.php",{first_loanValue:un},
					  function(data){
						if(data == 0){
							$("#first_loan_sizeHide").html (un);
							$("#first_loan_size").show("fast");
							$("#Editfirst_loan_size").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
		//----------------------Till Here----------------------//
		$("#second_loan_size").click(
			function(event){
				$(this).hide("fast");
				$("#Editsecond_loan_size").show("fast");
			}
		);
		$("#cEditsecond_loanValue").click(
			function(event){
				$("#second_loan_size").show("fast");
				$("#Editsecond_loan_size").hide("fast");
			}
		);
		
		$("#sEditsecond_loanValue").click(
			function(event){

				var un=$("#second_loanValue").val();
				$.get("includes/saveSettings.php",{second_loanValue:un},
					  function(data){
						if(data == 0){
							$("#second_loan_sizeHide").html (un);
							$("#second_loan_size").show("fast");
							$("#Editsecond_loan_size").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);

		$("#third_loan_size").click(
			function(event){
				$(this).hide("fast");
				$("#Editthird_loan_size").show("fast");
			}
		);
		$("#cEditthird_loanValue").click(
			function(event){
				$("#third_loan_size").show("fast");
				$("#Editthird_loan_size").hide("fast");
			}
		);
		
		$("#sEditthird_loanValue").click(
			function(event){

				var un=$("#third_loanValue").val();
				$.get("includes/saveSettings.php",{third_loanValue:un},
					  function(data){
						if(data == 0){
							$("#third_loan_sizeHide").html (un);
							$("#third_loan_size").show("fast");
							$("#Editthird_loan_size").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
		//----------------------Till Here----------------------//
				//----------------------Till Here----------------------//
		$("#next_loan_size").click(
			function(event){
				$(this).hide("fast");
				$("#Editnext_loan_size").show("fast");
			}
		);
		$("#cEditnext_loanValue").click(
			function(event){
				$("#next_loan_size").show("fast");
				$("#Editnext_loan_size").hide("fast");
			}
		);
		
		$("#sEditnext_loanValue").click(
			function(event){

				var un=$("#next_loanValue").val();
				$.get("includes/saveSettings.php",{next_loanValue:un},
					  function(data){
						if(data == 0){
							$("#next_loan_sizeHide").html (un);
							$("#next_loan_size").show("fast");
							$("#Editnext_loan_size").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
		//----------------------Till Here----------------------//
			$("#max_loan_period").click(
			function(event){
				$(this).hide("fast");
				$("#Editmax_loan_period").show("fast");
			}
		);
		$("#cEditmax_periodValue").click(
			function(event){
				$("#max_loan_period").show("fast");
				$("#Editmax_loan_period").hide("fast");
			}
		);
		
		$("#sEditmax_periodValue").click(
			function(event){
				var un=$("#max_periodValue").val();
				$.get("includes/saveSettings.php",{maxPeriodValue:un},
					  function(data){
						if(data == 0){
							$("#max_loan_periodHide").html (un);
							$("#max_loan_period").show("fast");
							$("#Editmax_loan_period").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
	//----------------------Till Here----------------------//
						$("#max_Grace_period").click(
			function(event){
				$(this).hide("fast");
				$("#Editmax_Grace_period").show("fast");
			}
		);
		$("#cEditmax_GraceperiodValue").click(
			function(event){
				$("#max_Grace_period").show("fast");
				$("#Editmax_Grace_period").hide("fast");
			}
		);
		
		$("#sEditmax_GraceperiodValue").click(
			function(event){
				var un=$("#max_GraceperiodValue").val();
				$.get("includes/saveSettings.php",{maxGraceperiodValue:un},
					  function(data){
						if(data == 0){
							$("#max_Grace_periodHide").html (un);
							$("#max_Grace_period").show("fast");
							$("#Editmax_Grace_period").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
		//----------------------Till Here----------------------//
		$("#max_Loan_App_Grace_period").click(
			function(event){
				$(this).hide("fast");
				$("#Editmax_Loan_App_Grace_period").show("fast");
			}
		);
		$("#cEditmax_Loan_App_Grace_periodValue").click(
			function(event){
				$("#max_Loan_App_Grace_period").show("fast");
				$("#Editmax_Loan_App_Grace_period").hide("fast");
			}
		);
		
		$("#sEditmax_Loan_App_Grace_periodValue").click(
			function(event){
				var un=$("#max_Loan_App_Grace_periodValue").val();
				$.get("includes/saveSettings.php",{maxLoanAppGraceperiod:un},
					  function(data){
						if(data == 0){
							$("#max_Loan_App_Grace_periodHide").html (un);
							$("#max_Loan_App_Grace_period").show("fast");
							$("#Editmax_Loan_App_Grace_period").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
		//----------------------Till Here----------------------//
		$("#max_Loan_App_Interest").click(
			function(event){
				$(this).hide("fast");
				$("#Editmax_Loan_App_Interest").show("fast");
			}
		);
		$("#cEditmax_Loan_App_InterestValue").click(
			function(event){
				$("#max_Loan_App_Interest").show("fast");
				$("#Editmax_Loan_App_Interest").hide("fast");
			}
		);
		
		$("#sEditmax_Loan_App_InterestValue").click(
			function(event){
				var un=$("#max_Loan_App_InterestValue").val();
				$.get("includes/saveSettings.php",{maxLoanAppInterest:un},
					  function(data){
						if(data == 0){
							$("#max_Loan_App_InterestHide").html (un);
							$("#max_Loan_App_Interest").show("fast");
							$("#Editmax_Loan_App_Interest").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
		//----------------------Till Here----------------------//
		$("#maxRepayPeriod").click(
			function(event){
				$(this).hide("fast");
				$("#EditmaxRepayPeriod").show("fast");
			}
		);
		$("#cEditmaxRepayPeriodValue").click(
			function(event){
				$("#maxRepayPeriod").show("fast");
				$("#EditmaxRepayPeriod").hide("fast");
			}
		);
		
		$("#sEditmaxRepayPeriodValue").click(
			function(event){
				var un=$("#maxRepayPeriodValue").val();
				$.get("includes/saveSettings.php",{maxRepayPeriod:un},
					  function(data){
						if(data == 0){
							$("#maxRepayPeriodHide").html (un);
							$("#maxRepayPeriod").show("fast");
							$("#EditmaxRepayPeriod").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
		//----------------------Till Here----------------------//
		$("#RepaymentReportThrshld").click(
			function(event){
				$(this).hide("fast");
				$("#EditRepaymentReportThrshld").show("fast");
			}
		);
		$("#cEditRepaymentReportThrshldValue").click(
			function(event){
				$("#RepaymentReportThrshld").show("fast");
				$("#EditRepaymentReportThrshld").hide("fast");
			}
		);
		
		$("#sEditRepaymentReportThrshldValue").click(
			function(event){
				var un=$("#RepaymentReportThrshldValue").val();
				$.get("includes/saveSettings.php",{RepaymentReportThrshld:un},
					  function(data){
						if(data == 0){
							$("#RepaymentReportThrshldHide").html (un);
							$("#RepaymentReportThrshld").show("fast");
							$("#EditRepaymentReportThrshld").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
		//----------------------Till Here----------------------//
		$("#TimeThrshld").click(
			function(event){
				$(this).hide("fast");
				$("#EditTimeThrshld").show("fast");
			}
		);
		$("#cEditTimeThrshldValue").click(
			function(event){
				$("#TimeThrshld").show("fast");
				$("#EditTimeThrshld").hide("fast");
			}
		);
		
		$("#sEditTimeThrshldValue").click(
			function(event){
				var un=$("#TimeThrshldValue").val();
				$.get("includes/saveSettings.php",{TimeThrshld:un},
					  function(data){
						if(data == 0){
							$("#TimeThrshldHide").html (un);
							$("#TimeThrshld").show("fast");
							$("#EditTimeThrshld").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);

		$("#TimeThrshld_above").click(
			function(event){
				$(this).hide("fast");
				$("#EditTimeThrshld_above").show("fast");
			}
		);
		$("#cEditTimeThrshld_aboveValue").click(
			function(event){
				$("#TimeThrshld_above").show("fast");
				$("#EditTimeThrshld_above").hide("fast");
			}
		);
		
		$("#sEditTimeThrshld_aboveValue").click(
			function(event){
				var un=$("#TimeThrshld_aboveValue").val();
				$.get("includes/saveSettings.php",{TimeThrshld_above:un},
					  function(data){
						if(data == 0){
							$("#TimeThrshld_aboveHide").html (un);
							$("#TimeThrshld_above").show("fast");
							$("#EditTimeThrshld_above").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
		//----------------------Till Here----------------------//
		
		//---------------------- added by Mohit 28-11-13 ----------------------//
		$("#TimeThrshldMid1").click(
			function(event){
				$(this).hide("fast");
				$("#EditTimeThrshldMid1").show("fast");
			}
		);
		$("#cEditTimeThrshldMid1Value").click(
			function(event){
				$("#TimeThrshldMid1").show("fast");
				$("#EditTimeThrshldMid1").hide("fast");
			}
		);
		
		$("#sEditTimeThrshldMid1Value").click(
			function(event){
				var un=$("#TimeThrshldMid1Value").val();
				$.get("includes/saveSettings.php",{TimeThrshldMid1:un},
					  function(data){
						if(data == 0){
							$("#TimeThrshldMid1Hide").html (un);
							$("#TimeThrshldMid1").show("fast");
							$("#EditTimeThrshldMid1").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
		
		$("#TimeThrshldMid2").click(
			function(event){
				$(this).hide("fast");
				$("#EditTimeThrshldMid2").show("fast");
			}
		);
		$("#cEditTimeThrshldMid2Value").click(
			function(event){
				$("#TimeThrshldMid2").show("fast");
				$("#EditTimeThrshldMid2").hide("fast");
			}
		);
		
		$("#sEditTimeThrshldMid2Value").click(
			function(event){
				var un=$("#TimeThrshldMid2Value").val();
				$.get("includes/saveSettings.php",{TimeThrshldMid2:un},
					  function(data){
						if(data == 0){
							$("#TimeThrshldMid2Hide").html (un);
							$("#TimeThrshldMid2").show("fast");
							$("#EditTimeThrshldMid2").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
		
		//------------- Till here----------------//
		$("#MinRepayRate").click(
			function(event){
				$(this).hide("fast");
				$("#EditMinRepayRate").show("fast");
			}
		);
		$("#cEditMinRepayRateValue").click(
			function(event){
				$("#MinRepayRate").show("fast");
				$("#EditMinRepayRate").hide("fast");
			}
		);
		
		$("#sEditMinRepayRateValue").click(
			function(event){
				var un=$("#MinRepayRateValue").val();
				$.get("includes/saveSettings.php",{MinRepayRate:un},
					  function(data){
						if(data == 0){
							$("#MinRepayRateHide").html (un);
							$("#MinRepayRate").show("fast");
							$("#EditMinRepayRate").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
		//----------------------Till Here----------------------//

		$("#MinFbFrnds").click(
			function(event){
				$(this).hide("fast");
				$("#EditMinFbFrnds").show("fast");
			}
		);
		$("#cEditMinFbFrndsValue").click(
			function(event){
				$("#MinFbFrnds").show("fast");
				$("#EditMinFbFrnds").hide("fast");
			}
		);
		
		$("#sEditMinFbFrndsValue").click(
			function(event){
				var un=$("#MinFbFrndsValue").val();
				$.get("includes/saveSettings.php",{MinFbFrnds:un},
					  function(data){
						if(data == 0){
							$("#MinFbFrndsHide").html (un);
							$("#MinFbFrnds").show("fast");
							$("#EditMinFbFrnds").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
		//----------------------Till Here----------------------//

		$("#MinFbMonths").click(
			function(event){
				$(this).hide("fast");
				$("#EditMinFbMonths").show("fast");
			}
		);
		$("#cEditMinFbMonthsValue").click(
			function(event){
				$("#MinFbMonths").show("fast");
				$("#EditMinFbMonths").hide("fast");
			}
		);
		
		$("#sEditMinFbMonthsValue").click(
			function(event){
				var un=$("#MinFbMonthsValue").val();
				$.get("includes/saveSettings.php",{MinFbMonths:un},
					  function(data){
						if(data == 0){
							$("#MinFbMonthsHide").html (un);
							$("#MinFbMonths").show("fast");
							$("#EditMinFbMonths").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);

		//----------------------Till Here----------------------//

		$("#MinEndorser").click(
			function(event){
				$(this).hide("fast");
				$("#EditMinEndorser").show("fast");
			}
		);
		$("#cEditMinEndorserValue").click(
			function(event){
				$("#MinEndorser").show("fast");
				$("#EditMinEndorser").hide("fast");
			}
		);
		
		$("#sEditMinEndorserValue").click(
			function(event){
				var un=$("#MinEndorserValue").val();
				$.get("includes/saveSettings.php",{MinEndorser:un},
					  function(data){
						if(data == 0){
							$("#MinEndorserHide").html (un);
							$("#MinEndorser").show("fast");
							$("#EditMinEndorser").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);

		//----------------------Till Here----------------------//

		$("#AgainReminderDays").click(
			function(event){
				$(this).hide("fast");
				$("#EditAgainReminderDays").show("fast");
			}
		);
		$("#cEditAgainReminderDaysValue").click(
			function(event){
				$("#AgainReminderDays").show("fast");
				$("#EditAgainReminderDays").hide("fast");
			}
		);
		
		$("#sEditAgainReminderDaysValue").click(
			function(event){
				var un=$("#AgainReminderDaysValue").val();
				$.get("includes/saveSettings.php",{AgainReminderDays:un},
					  function(data){
						if(data == 0){
							$("#AgainReminderDaysHide").html (un);
							$("#AgainReminderDays").show("fast");
							$("#EditAgainReminderDays").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);

		//----------------------Till Here----------------------//

		$("#AgainReminderAmt").click(
			function(event){
				$(this).hide("fast");
				$("#EditAgainReminderAmt").show("fast");
			}
		);
		$("#cEditAgainReminderAmtValue").click(
			function(event){
				$("#AgainReminderAmt").show("fast");
				$("#EditAgainReminderAmt").hide("fast");
			}
		);
		
		$("#sEditAgainReminderAmtValue").click(
			function(event){
				var un=$("#AgainReminderAmtValue").val();
				$.get("includes/saveSettings.php",{AgainReminderAmt:un},
					  function(data){
						if(data == 0){
							$("#AgainReminderAmtHide").html (un);
							$("#AgainReminderAmt").show("fast");
							$("#EditAgainReminderAmt").hide("fast");
							
						}
						else{
						
						}
					  }
					  );
			}
		);
});

/* For language setting in Admin Section By Mohit 24-01-2014*/
function managelanguage(event){
var opt='lang_opt'+event;
var sel_lang=$("#lang_opt"+event).val();
var cont_opt='count_code'+event;
var sel_country=$("#count_code"+event).val();
var msg_opt='lang_update'+event;		
 jQuery.ajax({
                url: 'updateprocess.php',
                type: 'POST',
                data: 'manage_lang=lang_setting&lang_code='+sel_lang+'&country_code='+sel_country, 
                success: function(data){
					if(data==1){
						$("#"+msg_opt).fadeIn(500);
						$("#"+msg_opt).fadeOut(3000);
					}
                }
            });

}

