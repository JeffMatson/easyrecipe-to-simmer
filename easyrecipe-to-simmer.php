<?php

/*
Plugin Name: EasyRecipe To Simmer
Description: Migrates content from EasyRecipe to Simmer
Version: 0.1
Author: Jeff Matson
Author URI: https://jeffmatson.net
*/

define( 'ER2SIMMER', plugin_dir_path( __FILE__ ) );
define( 'ER2SIMMER_URL', plugin_dir_url( __FILE__ ) );

require_once( __DIR__ . '/autoloader.php' );

//function get_recipe_content( $post_id ) {
//	$post_content = get_post( $post_id )->post_content;
//
//	$start = strpos( $post_content, 'easyrecipe' );
//	$end = strpos( $post_content, 'endeasyrecipe');
//	$length = $end - $start;
//
//	return substr( $post_content, $start, $length );
//}
//
//$recipe_content = get_recipe_content(2470);
//

if ( is_admin() ) {
	add_action( 'simmer_loaded', 'er_simmer_init' );
}



function er_simmer_init() {
	\EasyRecipe_To_Simmer\Core::init();
//	$recipe_stuff = \EasyRecipe_To_Simmer\Core::process_easyrecipe_items();
//	var_dump($recipe_stuff);
}


//
//var_dump($recipe_stuff);

//$recipe_data = array();
//
//function get_recipe_name( $recipe_content ) {
//	$start = strpos( $recipe_content, '<div class="item ERName">' );
//	$end = strpos( $recipe_content, '</div>');
//	$length = $end - $start;
//
//	return wp_strip_all_tags(substr( $recipe_content, $start, $length ));
//}
//
//$anything = get_recipe_name($recipe_content);
//var_dump($anything);



//$postdata = get_post(16446);
//var_dump($postdata->post_content);
//$start = strpos($postdata->post_content, 'easyrecipe' );
//var_dump($start);
//$end = strpos($postdata->post_content, 'endeasyrecipe');
//var_dump($end);
//$length = $end - $start;
//var_dump($length);
//
//$newstuff = substr($postdata->post_content, $start, $length);
//var_dump($newstuff);
