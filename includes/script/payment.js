function verify() 
{
	var themessage = "Please complete the following fields: ";
	if (document.epayform.UMname.value=="") {
		themessage = themessage + " - Full Name";
	}
	if (document.epayform.UMemail !=null && document.epayform.UMemail.value=="") {
		themessage = themessage + " - Email Address";
	}
	if (document.epayform.UMaddress.value=="") {
		themessage = themessage + " - Address";
	}
	if (document.epayform.UMcity.value=="") {
		themessage = themessage + " - City";
	}
	if (document.epayform.UMstate.value=="") {
		themessage = themessage + " - State";
	}
	if (document.epayform.UMzip.value=="") {
		themessage = themessage + " - Zip Code";
	}
	if (document.epayform.UMphone.value=="") {
		themessage = themessage + " - Phone no";
	}
	if (document.epayform.UMroutno.value=="") {
		themessage = themessage + " - Routing Number";
	}
	if (document.epayform.UMaccountno.value=="") {
		themessage = themessage + " - Account Number";
	}
	if (themessage == "Please complete the following fields: "){
		document.epayform.submit();
		return true;
	}
	else{
		alert(themessage);
		return false;
	}
}
function paymentRedirect()
{
	window.location = "index.php?p=17"
}
function donationRedirect()
{
	window.location = "microfinance/donate.html"
}
function hdrRedirect()
{
	window.location = "index.php?p=28"
}
function bidRedirect(loanid, bid, bidup)
{
	if(bidup)
		window.location = "index.php?p=14&u="+bid+"&l="+loanid+"#e5";
	else
		window.location = "index.php?p=14&u="+bid+"&l="+loanid+"#e6";
}