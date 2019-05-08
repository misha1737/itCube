<?php
/*
Plugin Name: Paragraph Remover
Description: Removes paragraphs around images
Version: 0.1
Author: Martin Vlcek
Author URI: http://mvlcek.bplaced.net
*/

# get correct id for plugin
$thisfile = basename(__FILE__, ".php");

# register plugin
register_plugin(
	$thisfile, 
	'PRemover', 	
	'0.1', 		
	'Martin Vlcek',
	'http://mvlcek.bplaced.net', 
	'Removes paragraphs around images',
	'',
	''  
);

# activate filter
add_filter('content','premover_replace');

function premover_replace($content) {
  return preg_replace_callback("/<p>(?:\s|&nbsp;)*(<a[^>]+>)?(?:\s|&nbsp;)*(<img[^>]+>)(?:\s|&nbsp;)*(<\/a>)?(?:\s|&nbsp;)*<\/p>/",'premover_replace_match',$content);
}

function premover_replace_match($match) {
  return $match[1].@$match[2].@$match[3];
}
