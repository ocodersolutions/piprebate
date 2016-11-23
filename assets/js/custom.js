$("input").keyup(function(e){
	p1 = ($(this).val());
	decimal = $(this).data('decimal');
	counterid = $(this).data('counterid');
	crossrate = $(this).data('crossrate');

	console.log(crossrate);
	
	switch (decimal) {
    case 0:
        decimal_value = 1;
        break;
    case 2:
	    decimal_value = 0.01;
	    break;
    case 3:
	    decimal_value = 0.001;
	    break;
    case 4:
	    decimal_value = 0.0001;
	    break;
    }

	p2 = p1 - decimal_value;
	$(this).closest('tr').find('input[name="price2"]').val(p2);
	lotsize = $(this).data('lotsize');

	var crossratedivide_1 = new RegExp("^([a-zA-Z]{3,}/JPY)$");
	var crossratedivide_2 = new RegExp("^([a-zA-Z]{3,}/CHF)$");
	var crossratedivide_3 = new RegExp("^([a-zA-Z]{3,}/CAD)$");

	var normalcount_1 = new RegExp("^([a-zA-Z]{3,}/USD)$");
	var normalcount_2 = new RegExp("^([a-zA-Z]{3,}/[a-zA-Z]{3,})$");

	if (crossratedivide_1.test(counterid) || crossratedivide_2.test(counterid) || crossratedivide_3.test(counterid)) {
		if(crossrate == '' || crossrate == counterid){
			cpp = decimal_value * lotsize / p1;
		} else {
			$.ajax({
			  method: "POST",
			  url: "../ajaxGetPriceByCounterId.php",
			  data: { counterid: crossrate }
			}).done(function( price1 ) {
				price1 = price1 * 10000;
			    cpp = decimal_value * lotsize / price1 * 10000;
			});
		}
	} else if(normalcount_1.test(counterid) || !normalcount_2.test(counterid)) {
		cpp = decimal_value * lotsize;
	} else {
		if(crossrate == '' || crossrate == counterid){
			cpp = decimal_value * lotsize * p1;
		} else {
			$.ajax({
			  method: "POST",
			  url: "../ajaxGetPriceByCounterId.php",
			  data: { counterid: crossrate }
			}).done(function( price1 ) {
				price1 = price1 * 10000;
			    cpp = decimal_value * lotsize * price1 / 10000;
			});
		}
	}

	


	//check number is true
	if($.isNumeric( p1 )){
		$(this).closest('tr').find('input[name="price3"], input[name="costperpip"]').val(parseFloat(cpp).toFixed(2));
	}else{
		alert(' not number');
		$(this).val('');
		$(this).closest('tr').find('input[name="costperpip"], input[name="price3"], input[name="price2"]').val('');
	}

	//check emty input
	// if ($(this).val() == ''){
	// 	$(this).closest('tr').find('input[name="costperpip"], input[name="costperpip-show"], input[name="price2"]').val('');
	// }
	
});