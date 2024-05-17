$(document).ready(function(){
	$('.ir-arriba').click(function(){
		$('body, html').animate({
			scrolltop:'0px'

		},300);
	});
	$(window).scroll(function(){
		if($(this)).scrolltop()>0){
		$('.ir-arriba').slideDown(300);
	}
	});
});