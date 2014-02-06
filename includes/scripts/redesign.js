function show_facebook_modal(){
	var facebook_flag = '<input type="hidden" name="fb_connect" />';
	if ($('#basic-modal-content .default_login_form').length == 0) {
		$('#basic-modal-content').append($('.default_login').html());
		$('#basic-modal-content .default_login_form').append(facebook_flag);
		$('#basic-modal-content .heading.default').remove();
	}
	$('#basic-modal-content').modal({
		containerCss:{
			width:300,
			height:285,
		},
		onClose : function(){
			window.location.assign(window.location.origin+"/index.php?p=116");
		}
	});
}

jQuery(function ($) {

	// Load dialog on click
	$('.terms_of_use_action').click(function (e) {
		$('#basic-modal-content').modal();

		return false;
	});
});