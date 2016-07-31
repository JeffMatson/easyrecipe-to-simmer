<?php

/*
Plugin Name: EasyRecipe To Simmer
Description: Migrates content from EasyRecipe to Simmer
Version: 0.1
Author: Jeff Matson
Author URI: https://jeffmatson.net
*/

/**
 *
 */
define( 'ER2SIMMER', plugin_dir_path( __FILE__ ) );

/**
 *
 */
define( 'ER2SIMMER_URL', plugin_dir_url( __FILE__ ) );

require_once( __DIR__ . '/autoloader.php' );

if ( is_admin() ) {
	add_action( 'simmer_loaded', 'er_simmer_init' );
}

/**
 *
 */
function er_simmer_init() {
	\EasyRecipe_To_Simmer\Core::init();
}

add_action( 'wp_ajax_import_post', array( '\EasyRecipe_To_Simmer\Ajax', 'import_post' ) );
