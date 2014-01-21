jQuery(function ($) {

	// Load dialog on click
	$('.terms_of_use_action').click(function (e) {
		$('#basic-modal-content').modal();

		return false;
	});
});