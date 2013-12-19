function preview(preview_id)
{	
	var pos = preview_id.indexOf("-");
	var no = preview_id.substr(pos+1,preview_id.length-1);
	var amt=$('#giftamt-'+no).val();
	var toName = $('#toName-'+no).val();
	var fromName = $('#fromName-'+no).val();
	var msg = $('#msg-'+no).val();		
	var exp_date = $('#exp_date').val();
	var base = GetBase();
	url = base+"cardimage.php?amt="+amt+"&to="+toName+"&from="+fromName+"&msg="+escape(msg)+"&exp_date="+exp_date;
	window.open(url, "_blank");
	
}
function hdrRedirect()
{
	window.location = "index.php?p=28"
}
function ordersubmit()
  {
		needToConfirm = false;
		var count = parseInt($("#container").attr("child"));		
		var flag=0;
		for(i=1; i<count+1 && flag==0; i++)
		{
			var formname =$("#form-"+i).attr("id");
			if(formname)
			{
				var var_name = $("input[@name='email_print_radio']:checked").val();				
				if(var_name=='email')
				{									
					var email=$('#recmail-'+i).val()
					if((email.length)<1){
						$("#recmailerror-"+i).css({'color':'red'}).html("** Please enter recipient email");
						flag=1;
					}
					else{
						var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
					if(!filter.test(email)){
						$("#recmailerror-"+i).css({'color':'red'}).html("** Invalid Email Address");
						flag=1;
					}
					else{
						$("#recmailerror-"+i).css({'color':'red'}).html("");
						}
					}
				}
				var email=$('#sendmail-'+i).val()
				if((email.length)>0){
					var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
					if(!filter.test(email)){
						$("#sendmailerror-"+i).css({'color':'red'}).html("** Invalid Email Address");
						flag=1;
					}
					else{
						$("#sendmailerror-"+i).css({'color':'red'}).html("");
					}					
				}				
			}
			else
			{}
		}		

		if(flag==1)
			return false;
		else
			return true;
  }
  function tncCheck()
  {
		if(document.ordertnc.tnc.checked)
			return true
		else
	    {
			alert("Please confirm acceptance of the Terms and Conditions to continue.");
			return false;
		}
  }
function deletediv(del)
  {
		var pos = del.indexOf("-");
		var no = del.substr(pos+1,del.length-1);	
		if(no !=1){
			$('#form-'+no).remove();
		}
		setGiftAmt();
  }
  function setGiftAmt()
  {
		var count = parseInt($("#container").attr("child"));	
		var dollartotal=0;
		for(i=1; i<count+1; i++)
		{
			if(parseInt($("#giftamt-"+i).val()) > 0)
				dollartotal += parseInt($("#giftamt-"+i).val());		
		}		
		$("#dollar_total").html("<font color='green'>"+dollartotal+".00</font>");
		$("#totalcost").attr("value", dollartotal);
  }  

$(document).ready(function(){
		var error=0;		
		var lastchildid = parseInt($("#container").attr("child"));
		$('#delete-1').hide();
		for(i=1; i<lastchildid+1; i++)
		{
			$('#recmail-'+i).hide();
			$('#recmaillbl-'+i).hide();		
			$('#recmailerror-'+i).hide();
		}		
														/*  add jquery start */
		$('#add').click(function() {			
			var container = $("#container");				
			var line = $('#form-1').clone().attr("id", "form")
			//var lineCount = container.children().length + 1;					
				var lastchildid = $("#container").attr("child");
				lineCount = parseInt(lastchildid) +1;
				$("#container").attr("child", lineCount);				
				$("#lastformvalue").attr("value", lineCount);
			line.attr("id", line.attr("id") + "-" + lineCount);
			line.find(":text").each(function() { 
			   $(this).attr("id", $(this).attr("id").substr(0,$(this).attr("id").length-2) + "-" + lineCount);
			  // $(this).attr("name", $(this).attr("name").substr(0,$(this).attr("name").length-2) + "-" + lineCount);
			   $(this).val("");
			});
			line.find("textarea").each(function() { 
			   $(this).attr("id", $(this).attr("id").substr(0,$(this).attr("id").length-2) + "-" + lineCount);
			  // $(this).attr("name", $(this).attr("name").substr(0,$(this).attr("name").length-2) + "-" + lineCount);
			   $(this).val("");
			});
			line.find(":radio").each(function() { 
			   $(this).attr("id", $(this).attr("id").substr(0,$(this).attr("id").length-2) + "-" + lineCount);
			   $(this).attr("name", $(this).attr("name").substr(0,$(this).attr("name").length-2) + "-" + lineCount);
			   $(this).val("");
			});
			line.find("div").each(function() { 
			   $(this).attr("id", $(this).attr("id").substr(0,$(this).attr("id").length-2) + "-" + lineCount);       
			   $(this).val("");
			});
			line.find("a").each(function() { 
			   $(this).attr("id", $(this).attr("id").substr(0,$(this).attr("id").length-2) + "-" + lineCount);       
			   $(this).val("");
			});
			line.find("label").each(function() { 
				if($(this).attr("id").length !=0)
				{
					$(this).attr("id", $(this).attr("id").substr(0,$(this).attr("id").length-2) + "-" + lineCount);   
				}
			   $(this).attr("for", $(this).attr("for").substr(0,$(this).attr("for").length-2) + "-" + lineCount);    
			   $(this).val("");
			});
			line.find("select").each(function() { 
			   $(this).attr("id", $(this).attr("id").substr(0,$(this).attr("id").length-2) + "-" + lineCount);
			   //$(this).attr("name", $(this).attr("name").substr(0,$(this).attr("name").length-2) + "-" + lineCount);
			   $(this).val("");
			});
			line.find("button").each(function() { 
			   $(this).attr("id", $(this).attr("id").substr(0,$(this).attr("id").length-2) + "-" + lineCount);
			   $(this).val("");
			});				
			container.append(line);	
			var senderEmail= $("#sendmail-1").attr("value");
			$("#sendmail-"+lineCount).attr("value", senderEmail);
			$("#remainchar-"+lineCount).css({'color':'blue'}).html("500");
			setGiftAmt();	

			$('div#tabs-'+lineCount+' input:radio:nth(0)').attr("disabled", "disabled");
			$('div#tabs-'+lineCount+' input:radio:nth(1)').attr("disabled", "disabled");

			var var_name = $("input[@name='email_print_radio-1']:checked").val();
			if(var_name=='email')
				$('div#tabs-'+lineCount+' input:radio:nth(1)').attr("checked","checked");
			else
				$('div#tabs-'+lineCount+' input:radio:nth(0)').attr("checked","checked");

			$('#delete-'+lineCount).show();
			$(":button").text("Cancel");
		});
												/*  add jquery end */


		$('#tabs-1').click(function() {
			var var_name = $("input[@name='email_print_radio-1']:checked").val();	
			var count = parseInt($("#container").attr("child"));		
			
			if(var_name=='email')
			{
				for(i=1; i<count+1; i++)
				{
					$('#recmail-'+i).show();
					$('#recmaillbl-'+i).show();
					$('#recmailerror-'+i).show();
					$('div#tabs-'+i+' input:radio:nth(1)').attr("checked","checked");
				}
			}
			else
			{
				for(i=1; i<count+1; i++)
				{
					$('#recmail-'+i).hide();
					$('#recmaillbl-'+i).hide();
					$('#recmailerror-'+i).hide();
					$('div#tabs-'+i+' input:radio:nth(0)').attr("checked","checked");
				}
			}
		});						
	})
						/*form validation start*/

  function checkvalue1(field)
  {
	    var pos = field.indexOf("-");
		var no = field.substr(pos+1,field.length-1);
		var name =field.substr(0,pos);
		if(name=='msg')
	    {	
			var msg=$('#'+field).val();			
			var msglength= msg.length;
			
			var remainchar = 500 - msglength;
			var count = msg.split("\n").length;
				
			remainchar = remainchar - (count - 1)*39;  /*39 is taken for 'enter key' (40 characters will be subtract, 1 char is for enter key it self) */
			if(remainchar <= 0) 
				$("#remainchar-"+no).html("0");
			else	
				$("#remainchar-"+no).html(remainchar);			
	    }
  }
  function checkvalue3(field,event)
  {
	    var pos = field.indexOf("-");
		var no = field.substr(pos+1,field.length-1);
		var name =field.substr(0,pos);
		if(name=='msg')
	    {
			var key = event.keyCode;
			if(key==0)
				key = event.which;
			var msg=$('#'+field).val();
			var msglength= msg.length;				
			var remainchar = 500 - msglength;
			var count = msg.split("\n").length;				
			remainchar = remainchar - (count - 1)*39;			
			if(remainchar <= 0) 
			{
				if(key==8)
				{}
				else if(key >31 || key==13)
				{
					if(event.preventDefault) 
						event.preventDefault();
					else
						event.returnValue = false;
				}
		    }
		}
  }

 /* function checkvalue2(field)
  {
		var pos = field.indexOf("-");
		var no = field.substr(pos+1,field.length-1);
		var name =field.substr(0,pos);
	    if(name=='msg')
	    {	
			var msg=$('#'+field).val();
			var msglength= msg.length;
			var remainchar = 500 - msglength;
			var count = msg.split("\n").length
			remainchar = remainchar - (count - 1)*39;
			if(remainchar <= 0)
			{
				$('#'+field).attr("value", msg.substr(0,(500-(count-1)*39)));
				$("#remainchar-"+no).css({'color':'blue'}).html("0");
			}
			else	
				$("#remainchar-"+no).css({'color':'blue'}).html(remainchar);	
		}
  }*/

  /*form validation end*/
  function GetBase() {
	var oBaseColl = document.all.tags('BASE');
	return ( (oBaseColl && oBaseColl.length) ? oBaseColl[0].href : 
		null );
}
