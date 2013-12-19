function saverefdetail(id)
{  
	var name = document.getElementById("refname"+id).value;
	var number = document.getElementById('refnumber'+id).value;
	var expctdate = document.getElementById('exdate'+id).value;
	var note = document.getElementById('note'+id).value;
	var borrowerid = document.getElementById('borrower'+id).value;
	var loanid = document.getElementById('loan'+id).value;
	var isedit = document.getElementById('isedit'+id).value;
	var e= document.getElementById('volunteer_mentor'+id);  
	var mentor = e.options[e.selectedIndex].value; 
	if(mentor!='0'){
		var mentorText = e.options[e.selectedIndex].text; 
		var m= mentorText.split(/[:,]/); 
		var mentorName= m[1];
		var tel= m[2].split(" ");
		var mentorTelephone= tel[2]; 
	}
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
			if(xmlhttp.status==200) {
				if(xmlhttp.responseText == 1) {
					document.getElementById("response"+id).innerHTML = "<font color=green>saved</font>";
					var new_note = document.getElementById("note"+id).value;
					document.getElementById("editdiv"+id).innerHTML+=' '+new_note;
					document.getElementById("note"+id).value = '';
					document.getElementById("isedit"+id).value=0;
					populateArrays();
					$('#rowid'+id).children("td").css('border-top', 'none');
					$('#rowid'+id).children("td").css('border-bottom', 'none');
					$('#rowid'+id).children('td:first-child').css('border-left', 'none');
					$('#rowid'+id).children('td:last').css('border-right', 'none');
					var editlink = document.getElementById("editlink"+id).innerHTML;
					if(mentor=='0'){
						document.getElementById("volunteer_name"+id).innerHTML='';
						document.getElementById("volunteer_no"+id).innerHTML='';
					}else{
						document.getElementById("volunteer_name"+id).innerHTML=mentorName;
						if(typeof(mentorTelephone)!='undefined')
							document.getElementById("volunteer_no"+id).innerHTML=mentorTelephone;
						else
							document.getElementById("volunteer_no"+id).innerHTML='';
					}
					if(editlink==null || editlink=='') {
						document.getElementById("editlink"+id).innerHTML = "<a href='javascript:void()' onclick='editnotes("+id+")'>edit</a>";
					}
				} else if(xmlhttp.responseText == 2) {
					document.getElementById("response"+id).innerHTML = "<font color=green>saved</font>";
					var new_note = document.getElementById("note"+id).value;
					document.getElementById("editdiv"+id).innerHTML=' '+new_note;
					document.getElementById("isedit"+id).value=0;
					document.getElementById("note"+id).value = '';
					populateArrays();
					$('#rowid'+id).children("td").css('border-top', 'none');
					$('#rowid'+id).children("td").css('border-bottom', 'none');
					$('#rowid'+id).children('td:first-child').css('border-left', 'none');
					$('#rowid'+id).children('td:last').css('border-right', 'none');
					
				}
				else {
					document.getElementById("response"+id).innerHTML = "<font color=red>failed</font>";
				}
			}
		}
		else {
				document.getElementById("response"+id).innerHTML="<img src='images/layout/icons/ajax-loader.gif' border='0' alt=''>";
		}
	}
	var params = "q="+id+"&name="+name+"&number="+number+"&date="+expctdate+"&note="+note+"&borrowerid="+borrowerid +"&loanid="+loanid+"&isedit="+isedit+"&mentor="+mentor+"&saveRepayReport="+1;
	//xmlhttp.open("POST","includes/saverepaydetail.php",true); // comment by mohit 24-10-13 unstructured file include issue
	xmlhttp.open("POST","process.php",true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.setRequestHeader("Content-length", params.length);
	xmlhttp.setRequestHeader("Connection", "close");
	xmlhttp.send(params);
}
// Added by Mohit on date 12-11-13
function getVolMentStaffMemList(cid,assignto)
{   
    jQuery("#loading_image").show();
    jQuery.ajax({
                url: 'process.php',
                type: 'POST',
                data: 'getVolMentStaffMemList=1&cid='+cid+'&assignedto='+assignto, 
                success: function(data){
                    jQuery("#loading_image").hide(); 
                    jQuery("#assignedto").append(data);
                }
            });
    }
