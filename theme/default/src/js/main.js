
$('#mobileMenu').click(function()  {
	if($('.modalMenu' ).hasClass('disablet')){
		$('.modalMenu' ).removeClass('disablet');
	}else{
		$('.modalMenu' ).addClass('disablet');
	}
});

function slider(selector, speed){
	$(selector).append('<div id="leftArrov"> </div>');
	$(selector).append('<div id="rightArrov"> </div>');
var leftPos = $(selector).scrollLeft();
   $(selector).animate({scrollLeft: leftPos + 20000}, speed, "linear");
   var width=$(selector).width();
var elements=$(selector.selector+' li');

elements=elements.length;
function changeВirection() {
   width=350*elements;
   leftPos = $(selector).scrollLeft();
  var windowWidth=$(window).width();
   if((leftPos+windowWidth)>=width){
  $(selector).stop();
  	 $(selector).animate({scrollLeft: leftPos - 20000}, speed, "linear");

}
if(leftPos==0){
  $(selector).stop();
  $(selector).animate({scrollLeft: leftPos + 20000}, speed, "linear");
}

}
var interval=setInterval(changeВirection
, 1000); 
console.log('ok');

$(selector.selector+' #leftArrov').hover(function()
		{
			//alert('left');
			$(selector).stop();
			$(selector).animate({scrollLeft: leftPos - 20000}, speed/4, "linear");	

		});

$(selector.selector+' #leftArrov').mouseout(function(){
$(selector).stop();
});

$(selector.selector+' #leftArrov').click(function(){
leftPos -= 200;
$(selector).stop();
$(selector).animate({scrollLeft: leftPos}, 500, "linear");
});

$(selector.selector+' #rightArrov').hover(function()
		{
			//alert('right');
			$(selector).stop();
			$(selector).animate({scrollLeft: leftPos + 20000}, speed/4, "linear");	

		});


$(selector.selector+' #rightArrov').mouseout(function(){
$(selector).stop();
});

$(selector.selector+' #rightArrov').click(function(){
leftPos += 200;
$(selector).stop();
$(selector).animate({scrollLeft: leftPos}, 500, "linear");

});


}

var selector=$('.newsBlock #search-results-live');
slider(selector, 400000);

var selector=$('.consaltingBlock #search-results-live');
slider(selector, 800000);

if($('body').attr("id")=='novini'){
		 $('#novini article  ul').attr('id', 'newsBlock');
		 $('#novini article  ul li').addClass("col-md-6 col-sm-6 col-xs-offset-1 col-xs-10");
	}

if($('body').attr("id").substr(0,4)=='news'){
		$('body').attr('id', 'news');
}

if($('body').attr("id").substr(0,4)=='news'){
		$('article ' ).addClass("content");
	}
if($('body').attr("id")=='novini'){
		$('article ' ).addClass("content");
	}
if($('body').attr("id")=='proekti'){
		$('article ' ).addClass("content");
	}
if($('body').attr("id")=='konsultac'){
		$('article ' ).addClass("content");
	}

if($('body').attr("id").substr(0,8)=='perevaga'){
		$('article ' ).addClass("content");
	}

if($('body').attr("id").substr(0,5)=='consu'){
		$('article ' ).addClass("content");
	}
	
