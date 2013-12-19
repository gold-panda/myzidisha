function saveLabel(id)
{
	document.getElementById("txtHint"+id).innerHTML="<img src='images/layout/icons/ajax-loader.gif' border='0' alt=''>";
	var str=document.getElementById(id).value;
	var lang=document.getElementById('language').value;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
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
				document.getElementById("txtHint"+id).innerHTML=xmlhttp.responseText;
		}
		else 
			document.getElementById("txtHint"+id).innerHTML="<font color=red>failed</font>";
	}
	var params = "q="+id+"&filelang="+lang+"&str="+escape(str);
	xmlhttp.open("POST","includes/savelabel.php",true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.setRequestHeader("Content-length", params.length);
	xmlhttp.setRequestHeader("Connection", "close");
	xmlhttp.send(params);
}
function removeText(id)
{
	document.getElementById("txtHint"+id).innerHTML="";
}