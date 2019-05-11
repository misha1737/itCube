console.log('javascript ok');

$('#mobileMenu').click(function()  {

//	
	if($('.modalMenu' ).hasClass('disablet')){
		$('.modalMenu' ).removeClass('disablet');

	}else{
		$('.modalMenu' ).addClass('disablet');
	}
});
