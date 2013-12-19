function savecodetail(id){
	var note = document.getElementById('note'+id).value;
	var borrowerid = id;
	var isedit = document.getElementById('isedit'+id).value;
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
			 if(xmlhttp.status==200) {
				if(xmlhttp.responseText == 1) {
					document.getElementById("response"+id).innerHTML = "<font color=green>saved</font>";
					var new_note = document.getElementById("note"+id).value;
					document.getElementById("editdiv"+id).innerHTML=' '+new_note;
					document.getElementById("note"+id).value = '';
					document.getElementById("isedit"+id).value=0;
					var editlink = document.getElementById("editlink"+id).innerHTML;
					if(editlink==null || editlink=='') {
						document.getElementById("editlink"+id).innerHTML = "<a href='javascript:void()' onclick='editnotes("+id+")'>edit</a>";
					}
				}
				else {
					document.getElementById("response"+id).innerHTML = "<font color=red>failed</font>";
				}
			}
		 }
	}
	var params = "id="+id+"&note="+note+"&isedit="+isedit+"&co_org_note="+1;
	xmlhttp.open("POST","process.php",true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send(params);
}