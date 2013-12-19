$(document).ready(function() {
// hides the slickbox as soon as the DOM is ready
// (a little sooner than page load)
$('.reply').hide();
// toggles the slickbox on clicking the noted link
$('a#slick-toggle').click(function() {
	$('#slickbox').slideToggle("slow");
	return false;
});

$('#comments button').click(function(){
		 var ic  = $('#comments button').index(this);
		 var div ='replybox'+ic ;
		 $('#'+div).slideToggle("slow");
		
	});



});

