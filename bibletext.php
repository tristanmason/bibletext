<?php
/**
 * Plugin Name: Christ Community Bible Text
 * Plugin URI: https://tristanmason.com
 * Description: This plugin reads a Bible reference from a page's URL or shortcode and displays the Bible text on the page. Use shortcodes like [bibletext book="john" chapter="1" verse="1-4"] in any post or page. Also allows URL queries.
 * Version: 1.0.1
 * Author: Tristan Mason
 * Author URI: https://tristanmason.com
 * License: GPL3
 */

// Add the query variables that we can use in the URL

function ccc_add_query_vars_filter( $vars ){
  $vars[] = 'book';
  $vars[] = 'chapter';
  $vars[] = 'verses';
  $vars[] = 'verse';
 return $vars;
}

add_filter( 'query_vars', 'ccc_add_query_vars_filter' );


// Add a shortcode called "bibletext" and use it to return the API response

function bibletext_shortcode_fn($atts){

	// Retrieve the query variables

	$book = get_query_var('book');
	$chapter = get_query_var('chapter');
	$verses = get_query_var('verses');
	$verse = get_query_var('verse');

	// If the "book" query variable is empty, use the shortcode parameters instead

	if (empty($book)) {

		extract(shortcode_atts(array(
			'book' => '',
			'chapter' => '',
			'verses' => '',
			'verse' => '',
		), $atts));

	}

	// If someone used "verse" instead of "verses", copy it over.

	if (!empty($verse)) {

		$verses = $verse;

	}

	// Talk with the ESV Bible API and return its response

		  $key = "IP";
		  $passage = urlencode($book .' '. $chapter .':'. $verses);
		  $options = "include-footnotes=false";
		  $url = "http://www.esvapi.org/v2/rest/passageQuery?key=$key&passage=$passage&$options";
		  $ch = curl_init($url); 
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		  $response = curl_exec($ch);
		  curl_close($ch);
		 
		return $response;
}
 
add_shortcode('bibletext', 'bibletext_shortcode_fn');

?>
