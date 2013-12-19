function verifyTnC(){
if(document.getElementById('agree').checked)
	{
	document.getElementById('tnc').value=1;
			return true;
	
	}else{
	alert("Please Accept the Terms And Condition");
	return false;
	}


}