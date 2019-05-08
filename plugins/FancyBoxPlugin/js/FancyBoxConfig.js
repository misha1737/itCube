jQuery(document).ready(function() {
    $('a.fb').fancybox();   
	$('a[rel]').fancybox();   
	$('a.inline').fancybox();	
	$("a.iframe").fancybox({
        'autoScale'     	: true,
        'transitionIn'		: 'none',
		'transitionOut'		: 'none',
		'type'				: 'iframe'
	});    
}); 
