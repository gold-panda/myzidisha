$(document).ready(function(){
	var error=0;
	 $(document).click(function(){
		 
	 });
	 
	 $('#retval img').click(function(){
		 var ic  = $('#retval img').index(this);
		 var v1 = 'name' + ic;
		 var v2 = 'name2' + ic;
		 var err = 'err'+ic;
		 var val1 = $('#'+v1).val();
		 var val2 = $('#'+v2).val();
		 
		 if(ic != 1){
			
			$.get("library/generic.php",{param:'test', param1:val1},
			function(data){
					$('#'+err).css({'color':'red'}).html('error');
					alert(data);
				});
		}
		else if(ic == 1){
			 
			 var cU = "library/generic.php?param=test1";
			 cU += '&param1='+val1;
			 $.getJSON(cU,
			 function(data){
				 
				if(data['retval'] == 1)
					$('#'+err).css({'color':'red'}).html('error');
				var a='';
				//$("#retval").html( data['retval']);
				$.each(data, function (i, items){
					a += data[i];
				});
				$('#'+err).html(a);
			});

		 
		}
	});

	$('#loanbids img').click(function(){
		 alert("hi");
		 var ic  = $('#LoanBids img').index(this);
		 var v1 = 'name' + ic;
		 var v2 = 'name2' + ic;
		 var err = 'err'+ic;
		 $('#NewBid').fadeOut(1);
	});

	
	
	
	
	$("button").click(function () {
      alert($(this).text());
	  $(this).fadeOut(10000);
    });

})