function saveborrowerdetail(brwrid)
{
	var lastvisited = document.getElementById("lastVisited"+brwrid).value;
	var admin_notes = document.getElementById('borrower_notes'+brwrid).value;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
				document.getElementById("response"+brwrid).innerHTML=xmlhttp.responseText;
		}
		else {
				document.getElementById("response"+brwrid).innerHTML="<img src='images/layout/icons/ajax-loader.gif' border='0' alt=''>";
		}
	}
	var params = "id="+brwrid+"&lastvisited="+lastvisited+"&admin_notes="+admin_notes;
	xmlhttp.open("POST","includes/savebrwdetail.php",true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.setRequestHeader("Content-length", params.length);
	xmlhttp.setRequestHeader("Connection", "close");
	xmlhttp.send(params);
}