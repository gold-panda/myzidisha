
function LoanForForgive(country){ 
	if (window.XMLHttpRequest)
	{
		xmlhttp=new XMLHttpRequest();
	}
	else
	{
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function()
	{
		if (xmlhttp.readyState==4)
		 {
			 document.getElementById("loansbycountry").innerHTML=xmlhttp.responseText;
		 }
	}
	var params = "loanbycntry="+country;
	xmlhttp.open("POST","process.php",true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.setRequestHeader("Content-length", params.length);
	xmlhttp.setRequestHeader("Connection", "close");
	xmlhttp.send(params);


}

