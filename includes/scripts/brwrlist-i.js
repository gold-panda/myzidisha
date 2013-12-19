$(document).ready(function(){
		var error=0;
		
		
		
		//check for the username field
		$("#datepicker").blur(
			function(event){
				var date=$(this).val();
				if((date.length)<1){
					$("#dateerror").css({'color':'red'}).html("** Required field for date ");
				}
				else{
					
				if((date.length)<10){
					$("#dateerror").css({'color':'red'}).html("** Invalid Date mm/dd/yyyy");
				}
				else{
					$("#dateerror").css({'color':'red'}).html("");
					}
				}
			}
		);
		
		$("#loanamount").keyup(
			function(event){
				var amount=$(this).val();
				if((amount.length) < 1){
					$("#amounterror").css({'color':'red'}).html("** Required field for Amount");
				}
				else{
					//var filter = /^([0-9_\.])+([0-9]{2,4})+$/;
					var filter = /^([0-9])+$/;
					if(!filter.test(amount)){
					$("#amounterror").css({'color':'red'}).html("** Amount must be in number format, no punctuation is allowed for ex. (50000)");
					}
					else{
					$("#amounterror").css({'color':'red'}).html("");
					}
				}
			}
		);
		$("#loanamount").blur(
			function(event){
				var amount=$(this).val();
				if(amount.length<1){
					$("#amounterror").css({'color':'red'}).html("** Required field for Amount");
				}
				else{
					var filter = /^([0-9])+$/;
					if(!filter.test(amount)){
					$("#amounterror").css({'color':'red'}).html("** Amount must be in number format, no punctuation is allowed for ex. (50000)");
					}
					else{
					$("#amounterror").css({'color':'red'}).html("");
					}
				}
			}
		);
		
		$("#loanpaid").blur(
			function(event){
				var r = $("input[name='loanpaid']:checked").val();
				if(r.length<1){
					$("#loanpaiderror").css({'color':'red'}).html("** Select any  choice ");
				}
				else{
					$("#loanpaiderror").css({'color':'red'}).html("");
				}
			}
		);

		$("#pcomment").blur(
			function(event){
				var mno=$(this).val();
				if((mno.length)<1){
					$("#pcommenterror").css({'color':'red'}).html("** Please enter comment");
				}
				else{
					$("#pcommenterror").css({'color':'red'}).html("");
				}
			}
		);
		$("#dreason").blur(
			function(event){
				var mno=$(this).val();
				if((mno.length)<1){
					$("#dreasonerror").css({'color':'red'}).html("** Please enter the reason for ineligibility");
				}
				else{
					$("#dreasonerror").css({'color':'red'}).html("");
				}
			}
		);
		$("#pcomment").keyup(
			function(event){
				var mno=$(this).val();
				if((mno.length)<1){
					$("#pcommenterror").css({'color':'red'}).html("** Please enter comment");
				}
				else{
					$("#pcommenterror").css({'color':'red'}).html("");
				}
			}
		);
		$("#lendername").keyup(
			function(event){
				/* handled for date field for removing error msg */
				var date=$("#datepicker").val();
				if((date.length)==10){
					$("#dateerror").css({'color':'red'}).html("");
				}
				var lname=$(this).val();
				if((lname.length)<1){
					$("#lendererror").css({'color':'red'}).html("** Please enter lender name");
				}
				else{
					$("#lendererror").css({'color':'red'}).html("");
				}
			}
		);
		$("#lendername").blur(
			function(event){
				/* handled for date field for removing error msg */
				var date=$("#datepicker").val();
				if((date.length)==10){
					$("#dateerror").css({'color':'red'}).html("");
				}
				var lname=$(this).val();
				if((lname.length)<1){
					$("#lendererror").css({'color':'red'}).html("** Please enter lender name");
				}
				else{
					$("#lendererror").css({'color':'red'}).html("");
				}
			}
		);
		
		
		
		$('#eligible-tab').click(function() 
		{
			var var_name = $("input[@name='email_print_radio-1']:checked").val();	
			if(var_name=='yes')
			{
				document.getElementById("approveDiv").style.display = 'block'; 
				document.getElementById("declineDiv").style.display = 'none'; 
			}
			else
			{
				document.getElementById("declineDiv").style.display = 'block'; 
				document.getElementById("approveDiv").style.display = 'none'; 
			}
		});		
	})