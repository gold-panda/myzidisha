$(document).ready(function(){
		var error=0;
		
		
		
		//check for the username field
		$("#busername").blur(
			function(event){
				var un=$(this).val();
				if((un.length) < 5){
					$("#bunerror").css({'color':'red'}).html("** Your username must be at least 5 characters long");
				}
				else
				{
					$.get("includes/checkUsername.php",{username:un},
						  function(data){
							if(data==1){
								$("#bunerror").css({'color':'red'}).html("** Username already taken");
							}
							else if(data==2)
							  {
								$("#bunerror").css({'color':'red'}).html("** Username must consist of letters and numbers only.");
							  }
							else
							{
								$("#bunerror").css({'color':'red'}).html("");
							}						
						  }
						  );
				}
			}
		);
		
		//for password verification
		$("#bpass1").blur(
			function(event){
				var pass=$(this).val();
				if((pass.length) < 7){
					$("#passerror").css({'color':'red'}).html("** Your password must be at least 7 characters long");
				}
				else{
					$("#passerror").css({'color':'red'}).html("");
				}
			}
		);
		$("#bpass2").blur(
			function(event){
				var pass1=$("#bpass1").val();
				var pass2=$(this).val();
				if(pass1!=pass2){
					$("#compareerror").css({'color':'red'}).html("** Passwords do not match");
				}
				else{
					$("#compareerror").css({'color':'red'}).html("");
				}
			}
		);
		
		/*
		$("#bemail").keyup(
			function(event){
				var email=$(this).val();
				var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				if(!filter.test(email)){
					$("#emailerror").css({'color':'red'}).html("** Invalid Email Address");
				}
				else{
					$("#emailerror").css({'color':'red'}).html("");
				}
			}
		);
		*/
		$("#bemail").blur(
			function(event){
				var email=$(this).val();
				if((email.length)<1){
					$("#emailerror").css({'color':'red'}).html("** Required field for email address");
				}
				else{
					var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				if(!filter.test(email)){
					$("#emailerror").css({'color':'red'}).html("** Invalid Email Address");
				}
				else{
					$("#emailerror").css({'color':'red'}).html("");
					}
				}
			}
		);
		/*
		$("#bmobile").keyup(
			function(event){
				var mno=$(this).val();
				var filter = /^([0-9])+$/;
				if(!filter.test(mno)){
					$("#mobileerror").css({'color':'red'}).html("** Invalid Mobile number");
				}
				else{
					$("#mobileerror").css({'color':'red'}).html("");
				}
			}
		);
		*/
		$("#bmobile").blur(
			function(event){
				var mno=$(this).val();
				if((mno.length)<1){
					$("#mobileerror").css({'color':'red'}).html("** Required field for mobile number");
				}
				else{
					//if(((mno.length)<12)||((mno.length)>12)){// commented for  deactivate this check
					if(((mno.length)<1)){
							$("#mobileerror").css({'color':'red'}).html("** mobile number must have 12 digit ");
					}else{
							var filter = /^([0-9])+$/;
							if(!filter.test(mno)){
								$("#mobileerror").css({'color':'red'}).html("** Invalid Mobile number");
								}
							else{
										$("#mobileerror").css({'color':'red'}).html("");
								}
					}
				}
			}
		);
		$("#pname").blur(
			function(event){
				var pname=$(this).val();
				$.get("includes/checkPrtName.php",{partner:pname},
					function(data){
						if(data== 1){
							$("#pnameerror").css({'color':'red'}).html("** Partner name exists");
						}
						else{
							$("#emailerror").css({'color':'red'}).html("");	
						}
					}
				)
			}
		);
		
		$("#pamount").blur(
			function(event){
				var bamount=$(this).val();
				var borrowerid=$("#borrowerid").val();
				if(bamount !="")
				{
					$.get("includes/getLocalCurrency.php", {amount:bamount , borrowerid:borrowerid },
						function(data){
							if(data==0){
								$("#pamounterr").css({'color':'red'}).html("Invalid amount");	
							}
							else{
								//$("#pamounterr").css({'color':'red'}).html("Local Currency ("+data+")");
								$("#pamounterr").css({'color':'red'}).html("");
								
							}
							
						}
					)
				}
				else
				{
					$("#pamounterr").css({'color':'red'}).html("");
				}
			}
		);
		$("#pamount1").blur(
			function(event){
				var bamount=$(this).val();
				var borrowerid=$("#borrowerid1").val();
				if(bamount !="")
				{
					$.get("includes/getLocalCurrency.php", {amount:bamount , borrowerid:borrowerid },
						function(data){
							if(data==0){
								$("#pamounterr1").css({'color':'red'}).html("Invalid amount");	
							}
							else{
								//$("#pamounterr1").css({'color':'red'}).html("Local Currency ("+data+")");	
								$("#pamounterr1").css({'color':'red'}).html("");	
							}
							
						}
					)
				}
				else
				{
					$("#pamounterr1").css({'color':'red'}).html("");
				}
			}
		);

	$("#desired_interest_rate").blur(
			function(event){
				var intrest=$(this).val();
				intrest=intrest.replace('%', '');
				if((intrest.length) < 1){
					$("#interest_rate_err").css({'color':'red'}).html("** Please specify an interest rate.");
				}
				else
				{
					var filter = /^\s*(\+|-)?((\d+(\.\d+)?)|(\.\d+))\s*$/;;
					if(!filter.test(intrest)){
						$("#interest_rate_err").css({'color':'red'}).html("** Please enter an interest rate in numerical format.");
						}else{
							$("#interest_rate_err").css({'color':'red'}).html("");
						}
				}
			}
		);
		$("#loanAppAmount").blur(
			function(event){
				var amount=$(this).val();
				if((amount.length) < 1){
					$("#loanAppAmountError").css({'color':'red'}).html("** Invalid value for Loan Amount");
				}
				else {
					$.get("includes/saveSettings.php",{checkLoanAppAmount:amount},
						function(data){
							if((data.length) < 1){
								getMinIns();
							}
							$("#loanAppAmountError").css({'color':'red'}).html(data);
						}
					);
				}
			}
		);
		$("#loanAppInterest").blur(
			function(event){
				var interest=$(this).val();
				if((interest.length) < 1){
					$("#loanAppInterestError").css({'color':'red'}).html("** Please enter an interest rate in numerical format.");
				}
				else {
					$.get("includes/saveSettings.php",{checkLoanAppInterest:interest},
						function(data){
							if((data.length) < 1){
								getMinIns();
							}
							$("#loanAppInterestError").css({'color':'red'}).html(data);
						}
					);
				}
			}
		);
		$("#loanAppGracePeriod").blur(
			function(event){
				var graceperiod=$(this).val();
				if((graceperiod.length) < 1){
					$("#loanAppGracePeriodError").css({'color':'red'}).html("** Invalid value for grace period (0 for none)");
				}
				else {
					$.get("includes/saveSettings.php",{checkLoanAppGracePeriod:graceperiod},
						function(data){
							if((data.length) < 1){
								getMinIns();
							}
							$("#loanAppGracePeriodError").css({'color':'red'}).html(data);
						}
					);
				}
			}
		);
		$("#loanAppInstallment").blur(
			function(event){
				var installment=$(this).val();
				if((installment.length) < 1){
					$("#loanAppInstallmentError").css({'color':'red'}).html("** Please enter the loan repayment installment amount");
				}
				else {
					getMinIns(installment);
					$("#loanAppInstallmentError").css({'color':'red'}).html("");
				}
			}
		);

		$("#babout").blur(
			function(event){
				var abtus=$(this).val();
				abtus = abtus.trim();
				if((abtus.length)<1){
					$("#babout_err").css({'color':'red'}).html("** Required field for About Yourself");
				}
				else{
					if(((abtus.length)<500)){
							$("#babout_err").css({'color':'red'}).html("** Please enter at least 500 characters");
					}
					else{
						$("#babout_err").css({'color':'red'}).html("");
					}
				}
			}
		);
		$("#bbizdesc").blur(
			function(event){
				var bizdes=$(this).val();
				bizdes = bizdes.trim();
				if((bizdes.length)<1){
					$("#brbizdesc_err").css({'color':'red'}).html("** Required field for About business");
				}
				else{
					if(((bizdes.length)<500)){
							$("#brbizdesc_err").css({'color':'red'}).html("** Please enter at least 500 characters");
					}
					else{
						$("#brbizdesc_err").css({'color':'red'}).html("");
					}
				}
			}
		);
		$("#pinterest").blur(
			function(event){
				var intrest=$(this).val();
				intrest=intrest.replace('%', '');
				if((intrest.length) < 1){
					$("#pintrerr").css({'color':'red'}).html("** Please enter Interest..");
				}
				else
				{
					var filter = /^\s*(\+|-)?((\d+(\.\d+)?)|(\.\d+))\s*$/;;
					if(!filter.test(intrest)){
						$("#pintrerr").css({'color':'red'}).html("** Please enter an interest rate in numerical format.");
						}else{
							$("#pintrerr").css({'color':'red'}).html("");
						}
				}
			}
		);
		$("#pinterest1").blur(
			function(event){
				var intrest=$(this).val();
				intrest=intrest.replace('%', '');
				if((intrest.length) < 1){
					$("#pintrerr1").css({'color':'red'}).html("** Please enter Interest..");
				}
				else
				{
					var filter = /^\s*(\+|-)?((\d+(\.\d+)?)|(\.\d+))\s*$/;;
					if(!filter.test(intrest)){
						$("#pintrerr1").css({'color':'red'}).html("** Please enter an interest rate in numerical format.");
						}else{
							$("#pintrerr1").css({'color':'red'}).html("");
						}
				}
			}
		);
	
	})
function verifyTnC(){
needToConfirm = false;
	if(document.getElementById('agree').checked) {
		document.getElementById('tnc').value=1;
	}else{
		alert("You must accept the Terms of Use in order to create an account");
		return false;
	}

}
function getMinIns(installment) {
	var amount=$("#loanAppAmount").val();
	var interest=$("#loanAppInterest").val();
	var graceperiod=$("#loanAppGracePeriod").val();
	var installment_weekday=$("#installment_weekday").val();
	if(!graceperiod) {
		graceperiod=0;
	}
	if(amount.length > 0 && interest.length > 0){
		$.get("includes/saveSettings.php",{loanAppAmount:amount, loanAppInterest:interest, loanAppGracePeriod:graceperiod, installment_weekday:installment_weekday},
			function(data){
				if(installment) {
					if(parseInt(installment) < parseInt(data)) {
						$("#loanAppInstallmentError").css({'color':'red'}).html("** Monthly repayment amount cannot be less than "+data);
					} else {
						$("#loanAppInstallmentError").css({'color':'red'}).html("");
					}
				} else {
					$("#minLoanInsCalculated").html(data);
				}
			}
		);
	}	
}

function getfrontNationalId(){
   document.getElementById("front_national_id").click();
}

function getbackNationalId(){
   document.getElementById("back_national_id").click();
}

function getAddressProof(){
   document.getElementById("address_proof").click();
}

function getlegalDeclaration(){
   document.getElementById("legal_declaration").click();
}
function getbphoto(){
   document.getElementById("bphoto").click();
}
function getlegalDeclaration2(){
   document.getElementById("legal_declaration2").click();
}
 function uploadfile(obj){
    var id = obj.id;
	var file = obj.value;
    var fileName = file.split("\\");
	if(id=='front_national_id') {
		document.getElementById("front_national_id_file").innerHTML = fileName[fileName.length-1];
		document.getElementById("uploadfileanchor").value = "#front_national_id"
    }
	if(id=='back_national_id') {
		document.getElementById("back_national_id_file").innerHTML = fileName[fileName.length-1];
		document.getElementById("uploadfileanchor").value = "#back_national_id"
    }
	if(id=='address_proof') {
		document.getElementById("address_proof_file").innerHTML = fileName[fileName.length-1];
		document.getElementById("uploadfileanchor").value = "#address_proof"
    }
	if(id=='legal_declaration') {
		document.getElementById("legal_declaration_file").innerHTML = fileName[fileName.length-1];
		document.getElementById("uploadfileanchor").value = "#legal_declaration"
    }
	if(id=='bphoto') {
		document.getElementById("bphoto_file").innerHTML = fileName[fileName.length-1];
		document.getElementById("uploadfileanchor").value = "#bphoto"
	}
	if(id=='legal_declaration2') {
		document.getElementById("legal_declaration_file2").innerHTML = fileName[fileName.length-1];
		document.getElementById("uploadfileanchor").value = "#legal_declaration2"
	}
	document.getElementById("borrowersubmitform").click();
}