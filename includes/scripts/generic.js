$(document).ready(function(){
	var error=0;
	//$("#loanschedule").hide();
	 $('#retval img').click(function(){
		 var ic  = $('#retval img').index(this);
		 var v1 = 'bidamt' + ic;
		 var v2 = 'bidint' + ic;
		 var v3 = 'bidid' + ic;
		 var err1 = 'erramt'+ic;
		 var err2 = 'errint'+ic;
		 $("#pamount").get(0).value = $('#'+v1).val();
		 $("#pinterest")[0].selectedIndex = $('#'+v2).val();
		 $("#bidid").get(0).value = $('#'+v3).val();
		 $("#act").get(0).value = 'Save Your Bid';
		 $("#editBidAmount").val($('#'+v1).val());
		 $("#editBidMsg").html('You may increase the amount or reduce the interest rate of your original bid of USD '+$('#'+v1).val()+' below. Click <a onclick="setNewBid()" style="cursor:pointer">here</a> to place a new bid.');
		 
	});
	/*$('#layer1').Draggable(
					{
						zIndex: 	20,
						ghosting:	false,
						opacity: 	0.7,
						handle:	'#layer1_handle'
					}
				);	*/
			
		$("#layer1").hide();
					
		$('#preferences').click(function()
		{
			$("#layer1").show();
		});
		
		$('#close').click(function()
		{
			$("#layer1").hide();
		});

	$('.accept').click(function()
		{
			$("#loanschedule").show();
			$('.accept').hide();
		}
				);	
	$('.deny').click(function()
		{
			$("#loanschedule").hide();
			$('.accept').show();
		}
				);	
	$("#paypal_amount").keyup(
			function(event){
				var paypal_amount=parseFloat($(this).val());
				var paypal_trans=parseFloat($("#paypal_trans").val());
				var regex = /^[0-9]\d*(?:\.\d{0,2})?$/;
				if(regex.test(paypal_amount)){
					paypal_donation=((paypal_amount * 15)/100);
					transaction =(paypal_amount *  paypal_trans)/100;
					total=paypal_amount + transaction + paypal_donation;
					$('#paypal_transaction').val(transaction.toFixed(2));
					$('#paypal_donation').val(paypal_donation.toFixed(2));
					$('#paypal_tot_amt').html(total.toFixed(2));
				}
				else{
					$('#paypal_transaction').val('');
					$('#paypal_tot_amt').html('');
					$('#paypal_donation').val('');
				}
			}
		);
		
		$("#paypal_amount").blur(
			function(event){
				var paypal_amount=parseFloat($(this).val());
				var paypal_trans=parseFloat($("#paypal_trans").val());
				var regex = /^[0-9]\d*(?:\.\d{0,2})?$/;
				if(regex.test(paypal_amount)){
					paypal_donation=((paypal_amount * 15)/100);
					transaction =(paypal_amount *  paypal_trans)/100;
					total=paypal_amount + transaction + paypal_donation;
					$('#paypal_transaction').val(transaction.toFixed(2));
					$('#paypal_donation').val(paypal_donation.toFixed(2));
					$('#paypal_tot_amt').html(total.toFixed(2));
				}
				else{
					$('#paypal_amount').val('100.00');
					$('#paypal_transaction').val(paypal_trans.toFixed(2));
					$('#paypal_donation').val('15.00');
					total=100 + paypal_trans + 15;
					$('#paypal_tot_amt').html(total.toFixed(2));
				}
			}
		);
		$("#paypal_donation").keyup(
			function(event){
				var paypal_amount=parseFloat($("#paypal_amount").val());
				var paypal_trans=parseFloat($("#paypal_trans").val());
				var paypal_donation=parseFloat($("#paypal_donation").val());
				if(!paypal_donation)
					paypal_donation=0;
				var regex = /^[0-9]\d*(?:\.\d{0,2})?$/;
				if(regex.test(paypal_donation)){
					transaction =(paypal_amount *  paypal_trans)/100;
					total=paypal_amount + transaction + paypal_donation;
					$('#paypal_transaction').val(transaction.toFixed(2));
					$('#paypal_tot_amt').html(total.toFixed(2));
				}
				else{
					transaction =(paypal_amount *  paypal_trans)/100;
					total=paypal_amount + transaction;
					$('#paypal_tot_amt').html(total.toFixed(2));
				}
			}
		);
		
		$("#paypal_donation").blur(
			function(event){
				var paypal_amount=parseFloat($("#paypal_amount").val());
				var paypal_trans=parseFloat($("#paypal_trans").val());
				var paypal_donation=parseFloat($("#paypal_donation").val());
				if(!paypal_donation)
					paypal_donation=0;
				var regex = /^[0-9]\d*(?:\.\d{0,2})?$/;
				if(regex.test(paypal_donation)){
					transaction =(paypal_amount *  paypal_trans)/100;
					total=paypal_amount + transaction + paypal_donation;
					$('#paypal_transaction').val(transaction.toFixed(2));
					$('#paypal_tot_amt').html(total.toFixed(2));
				}
				else{
					transaction =(paypal_amount *  paypal_trans)/100;
					total=paypal_amount + transaction;
					$('#paypal_tot_amt').html(total.toFixed(2));
				}
			}
		);
		$("#gift_echeck_donation").keyup(
			function(event){
				var echeck_amount=parseFloat($("#echeck_amount").val());
				var echeck_donation=parseFloat($("#gift_echeck_donation").val());
				if(!echeck_donation)
					echeck_donation=0;
				var regex = /^[0-9]\d*(?:\.\d{0,2})?$/;
				if(regex.test(echeck_donation)){
					total=echeck_amount + echeck_donation;
					$('#echeck_tot_amt').html('USD '+total.toFixed(2));
				}
				else{
					$('#echeck_tot_amt').html('USD '+echeck_amount.toFixed(2));
				}
			}
		);
		$("#gift_echeck_donation").blur(
			function(event){
				var echeck_amount=parseFloat($("#echeck_amount").val());
				var echeck_donation=parseFloat($("#gift_echeck_donation").val());
				if(!echeck_donation)
					echeck_donation=0;
				var regex = /^[0-9]\d*(?:\.\d{0,2})?$/;
				if(regex.test(echeck_donation)){
					total=echeck_amount + echeck_donation;
					$('#echeck_tot_amt').html('USD '+total.toFixed(2));
				}
				else{
					$('#echeck_tot_amt').html('USD '+echeck_amount.toFixed(2));
				}
			}
		);
		$("#gift_paypal_donation").keyup(
			function(event){
				var paypal_amount=parseFloat($("#paypal_amount").val());
				var paypal_trans=parseFloat($("#paypal_trans").val());
				var paypal_donation=parseFloat($("#gift_paypal_donation").val());
				if(!paypal_donation)
					paypal_donation=0;
				var regex = /^[0-9]\d*(?:\.\d{0,2})?$/;
				if(regex.test(paypal_donation)){
					total=paypal_amount + paypal_trans + paypal_donation;
					$('#paypal_tot_amt').html('USD '+total.toFixed(2));
				}
				else{
					total=paypal_amount + paypal_trans;
					$('#paypal_tot_amt').html('USD '+total.toFixed(2));
				}
			}
		);
		$("#gift_paypal_donation").blur(
			function(event){
				var paypal_amount=parseFloat($("#paypal_amount").val());
				var paypal_trans=parseFloat($("#paypal_trans").val());
				var paypal_donation=parseFloat($("#gift_paypal_donation").val());
				if(!paypal_donation)
					paypal_donation=0;
				var regex = /^[0-9]\d*(?:\.\d{0,2})?$/;
				if(regex.test(paypal_donation)){
					total=paypal_amount + paypal_trans + paypal_donation;
					$('#paypal_tot_amt').html('USD '+total.toFixed(2));
				}
				else{
					total=paypal_amount + paypal_trans;
					$('#paypal_tot_amt').html('USD '+total.toFixed(2));
				}
			}
		);
		$("#bid_paypal_amount").keyup(
			function(event){
				var paypal_amount=parseFloat($(this).val());
				var paypal_trans=parseFloat($("#paypal_trans").val());
				var regex = /^[0-9]\d*(?:\.\d{0,2})?$/;
				if(regex.test(paypal_amount)){
					paypal_donation=((paypal_amount * 15)/100);
					transaction =(paypal_amount *  paypal_trans)/100;
					total=paypal_amount + transaction + paypal_donation;
					$('#bid_paypal_trans').val(transaction.toFixed(2));
					$('#bid_paypal_trans_div').html(transaction.toFixed(2));
					$('#bid_paypal_donation').val(paypal_donation.toFixed(2));
					$('#bid_paypal_tot_amt').html(total.toFixed(2));
				}
				else{
					$('#bid_paypal_trans').val('');
					$('#bid_paypal_trans_div').html('');
					$('#bid_paypal_donation').val('');
					$('#bid_paypal_tot_amt').html('');					
				}
			}
		);
		
		$("#bid_paypal_amount").blur(
			function(event){
				var paypal_amount=parseFloat($(this).val());
				var paypal_trans=parseFloat($("#paypal_trans").val());
				var regex = /^[0-9]\d*(?:\.\d{0,2})?$/;
				if(regex.test(paypal_amount)){
					paypal_donation=((paypal_amount * 15)/100);
					transaction =(paypal_amount *  paypal_trans)/100;
					total=paypal_amount + transaction + paypal_donation;
					$('#bid_paypal_trans').val(transaction.toFixed(2));
					$('#bid_paypal_trans_div').html(transaction.toFixed(2));
					$('#bid_paypal_donation').val(paypal_donation.toFixed(2));
					$('#bid_paypal_tot_amt').html(total.toFixed(2));
				}
				else{
					$('#bid_paypal_trans').val('');
					$('#bid_paypal_trans_div').html('');
					$('#bid_paypal_donation').val('');
					$('#bid_paypal_tot_amt').html('');
				}
			}
		);
		$("#bid_paypal_donation").keyup(
			function(event){
				var paypal_amount=parseFloat($("#bid_paypal_amount").val());
				var paypal_trans=parseFloat($("#paypal_trans").val());
				transaction =(paypal_amount *  paypal_trans)/100;
				var paypal_donation=parseFloat($("#bid_paypal_donation").val());
				if(!paypal_donation)
					paypal_donation=0;
				var regex = /^[0-9]\d*(?:\.\d{0,2})?$/;
				if(regex.test(paypal_donation)){
					total=paypal_amount + transaction + paypal_donation;
					$('#bid_paypal_tot_amt').html(total.toFixed(2));
				}
				else{
					total=paypal_amount + paypal_trans;
					$('#bid_paypal_tot_amt').html(total.toFixed(2));
				}
			}
		);
		$("#bid_paypal_donation").blur(
			function(event){
				var paypal_amount=parseFloat($("#bid_paypal_amount").val());
				var paypal_trans=parseFloat($("#paypal_trans").val());
				transaction =(paypal_amount *  paypal_trans)/100;
				var paypal_donation=parseFloat($("#bid_paypal_donation").val());
				if(!paypal_donation)
					paypal_donation=0;
				var regex = /^[0-9]\d*(?:\.\d{0,2})?$/;
				if(regex.test(paypal_donation)){
					total=paypal_amount + transaction + paypal_donation;
					$('#bid_paypal_tot_amt').html(total.toFixed(2));
				}
				else{
					total=paypal_amount + paypal_trans;
					$('#bid_paypal_tot_amt').html(total.toFixed(2));
				}
			}
		);
		$("#bid_echeck_amount").keyup(
			function(event){
				var echeck_amount=parseFloat($(this).val());
				var regex = /^[0-9]\d*(?:\.\d{0,2})?$/;
				if(regex.test(echeck_amount)){
					echeck_donation=((echeck_amount * 15)/100);
					total=echeck_amount + echeck_donation;
					$('#bid_echeck_donation').val(echeck_donation.toFixed(2));
					$('#bid_echeck_tot_amt').html(total.toFixed(2));
				}
				else{
					$('#bid_echeck_donation').val('');
					$('#bid_echeck_tot_amt').html('');					
				}
			}
		);
		
		$("#bid_echeck_amount").blur(
			function(event){
				var echeck_amount=parseFloat($(this).val());
				var regex = /^[0-9]\d*(?:\.\d{0,2})?$/;
				if(regex.test(echeck_amount)){
					echeck_donation=((echeck_amount * 15)/100);
					total=echeck_amount + echeck_donation;
					$('#bid_echeck_donation').val(echeck_donation.toFixed(2));
					$('#bid_echeck_tot_amt').html(total.toFixed(2));
				}
				else{
					$('#bid_echeck_donation').val('');
					$('#bid_echeck_tot_amt').html('');
				}
			}
		);
		$("#bid_echeck_donation").keyup(
			function(event){
				var echeck_amount=parseFloat($("#bid_echeck_amount").val());
				var echeck_donation=parseFloat($("#bid_echeck_donation").val());
				if(!echeck_donation)
					echeck_donation=0;
				var regex = /^[0-9]\d*(?:\.\d{0,2})?$/;
				if(regex.test(echeck_donation)){
					total=echeck_amount + echeck_donation;
					$('#bid_echeck_tot_amt').html(total.toFixed(2));
				}
				else{
					total=echeck_amount;
					$('#bid_echeck_tot_amt').html(total.toFixed(2));
				}
			}
		);
		$("#bid_echeck_donation").blur(
			function(event){
				var echeck_amount=parseFloat($("#bid_echeck_amount").val());
				var echeck_donation=parseFloat($("#bid_echeck_donation").val());
				if(!echeck_donation)
					echeck_donation=0;
				var regex = /^[0-9]\d*(?:\.\d{0,2})?$/;
				if(regex.test(echeck_donation)){
					total=echeck_amount + echeck_donation;
					$('#bid_echeck_tot_amt').html(total.toFixed(2));
				}
				else{
					total=echeck_amount;
					$('#bid_echeck_tot_amt').html(total.toFixed(2));
				}
			}
		);
		$("#paypal_donation_cart").keyup(
			function(event){
				var paypal_donation=parseFloat($(this).val());
				var regex = /^[0-9]\d*(?:\.\d{0,4})?$/;
				if(regex.test(paypal_donation)){
					transaction = $('#paypal_transaction_cart').val();
					paypal_amount = $('#hidden_paypal_amount').val();
					AmtIncart = $('#AmtIncart').val();
					cravail = $('#creditavailable').val();
					totalamt = parseFloat(AmtIncart) + parseFloat(transaction)+ parseFloat(paypal_donation);
					total=totalamt-cravail;
					
					if(total <= 0) {
						total = 0;
						$('#amtTochargedPaypalrow').css('display', 'none');
						$('#paypal_transaction_cart').val=0;
						$('#cartsubmitButton').val('Confirm');
						$('#chargefromcravail').html(addCommas(Number(totalamt).toFixed(2)));
					}else {
						$('#amtTochargedPaypalrow').css('display', '');
						$('#cartsubmitButton').val('Continue');
						$('#chargefromcravail').html(addCommas(Number(cravail).toFixed(2)));
					
					}
					$('#tot_amt_cart').html(addCommas(totalamt.toFixed(2)));
					$('#amtTochargedPaypal').html(addCommas(total.toFixed(2)));
					//var un=$("#paypal_donation_cart").val();
					//$.get("includes/saveSettings.php",{Nodonationincart:un});

				}
				else{
					paypal_donation = 0;
					transaction = $('#paypal_transaction_cart').val();
					paypal_amount = $('#hidden_paypal_amount').val();
					AmtIncart = $('#AmtIncart').val();
					cravail = $('#creditavailable').val();
					totalamt = parseFloat(AmtIncart) + parseFloat(transaction)+ parseFloat(paypal_donation);
					total=totalamt-cravail;

					if(total <= 0) {
						total = 0;
						$('#amtTochargedPaypalrow').css('display', 'none');
						$('#cartsubmitButton').val('Confirm');
						$('#chargefromcravail').html(addCommas(Number(totalamt).toFixed(2)));
					}else {
						$('#amtTochargedPaypalrow').css('display', '');
						$('#cartsubmitButton').val('Continue');
						$('#chargefromcravail').html(addCommas(Number(cravail).toFixed(2)));
					}
					$('#tot_amt_cart').html(addCommas(totalamt.toFixed(2)));
					$('#amtTochargedPaypal').html(addCommas(total.toFixed(2)));
					//var un=0;
					//$.get("includes/saveSettings.php",{Nodonationincart:un});
				}
			}
		);
		$("#nodonation").click(
			function(event){
				var paypal_donation = 0;
				$('#paypal_donation_cart').val(0);
				var regex = /^[0-9]\d*(?:\.\d{0,4})?$/;
				if(regex.test(paypal_donation)){
					transaction = $('#paypal_transaction_cart').val();
					paypal_amount = $('#hidden_paypal_amount').val();
					cravail = $('#creditavailable').val();
					AmtIncart = $('#AmtIncart').val();
					totalamt = parseFloat(AmtIncart) + parseFloat(transaction)+ parseFloat(paypal_donation);
					total = totalamt-cravail;
					if(total <= 0) {
						total = 0;
						$('#amtTochargedPaypalrow').css('display', 'none');
						$('#chargefromcravail').html(addCommas(Number(totalamt).toFixed(2)));
					}else {
						$('#amtTochargedPaypalrow').css('display', '');
						$('#chargefromcravail').html(addCommas(Number(cravail).toFixed(2)));
					}
					$('#amtTochargedPaypal').html(addCommas(total.toFixed(2)));
					$('#tot_amt_cart').html(addCommas(totalamt.toFixed(2)));
					var un=$("#paypal_donation_cart").val();
					$.get("includes/saveSettings.php",{Nodonationincart:un});
				}
				else{
					paypal_donation = 0;
					transaction = $('#paypal_transaction_cart').val();
					paypal_amount = $('#hidden_paypal_amount').val();
					cravail = $('#creditavailable').val();
					AmtIncart = $('#AmtIncart').val();
					totalamt = parseFloat(AmtIncart) + parseFloat(transaction)+ parseFloat(paypal_donation);
					$('#tot_amt_cart').html(addCommas(total.toFixed(2)));
					total=totalamt-cravail;
					if(total < 0) {
						total = 0;
						$('#amtTochargedPaypalrow').css('display', 'none');
						$('#cartsubmitButton').val('Confirm');
					}else {
						$('#amtTochargedPaypalrow').css('display', '');
						$('#cartsubmitButton').val('Continue');
					}
					var un=0;
					$('#amtTochargedPaypal').html(addCommas(total.toFixed(2)));
					$.get("includes/saveSettings.php",{Nodonationincart:un});
				}
			}
		);
})
function setNewBid()
{
	$("#pamount").get(0).value = "";
	$("#pinterest").get(0).value = "";
	$("#bidid").get(0).value = "";
	$("#act").get(0).value = 'Lend';
	$("#editBidAmount").val("");
	$("#editBidMsg").html('');
	$("#pamounterr").html('');
}
function checkvalue1(field,event)
{
	var key = event.keyCode;
	if(key==0)
		key = event.which;
	var msg=$('#'+field).val();
	var msglength= msg.length;
	var remainchar = 2000 - msglength;
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
function checkvalue2(field)
{
	var msg=$('#'+field).val();
	var msglength= msg.length;
	var remainchar = 2000 - msglength;
	if(remainchar <= 0) 
			$("#remainchar").html("0");
	else	
		$("#remainchar").html(remainchar);							
}
function fillDonation()
{
	var upload_amount=document.getElementById('upload_amount').value;
	if(upload_amount==''){
		document.getElementById('upload_amount').value='100.00';	
		document.getElementById('donation_amount').value='15.00';
		document.getElementById("tot_amt").innerHTML = '115.00';
	}
	else{
		var donation_amount= parseFloat(upload_amount) * 15 /100;
		document.getElementById('donation_amount').value=donation_amount.toFixed(2);
		var total= parseFloat(upload_amount) + donation_amount;
		document.getElementById("tot_amt").innerHTML = total.toFixed(2);
	}
}
function setTotal()
{
	var upload_amount=document.getElementById('upload_amount').value;
	var donation_amount=document.getElementById('donation_amount').value;
	if(donation_amount==''){
		document.getElementById("tot_amt").innerHTML = parseFloat(upload_amount).toFixed(2);
	}
	else{
		var total= parseFloat(upload_amount) + parseFloat(donation_amount);
		document.getElementById("tot_amt").innerHTML = total.toFixed(2);
	}
}
function addCommas(nStr)
{	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? '.' + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}