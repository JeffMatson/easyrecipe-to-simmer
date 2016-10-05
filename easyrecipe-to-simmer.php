<?php

/*
Plugin Name: EasyRecipe To Simmer
Description: Migrates content from EasyRecipe to Simmer
Version: 0.1
Author: Jeff Matson
Author URI: https://jeffmatson.net
*/

/**
 * Define the plugin directory path.
 */
define( 'ER2SIMMER', plugin_dir_path( __FILE__ ) );

/**
 * Define the plugin directory URL.
 */
define( 'ER2SIMMER_URL', plugin_dir_url( __FILE__ ) );

// Get the autoloader.
require_once( __DIR__ . '/autoloader.php' );

// Initialize the migration tool after Simmer is loaded.
if ( is_admin() ) {
	add_action( 'simmer_loaded', 'er_simmer_init' );
}

/**
 * Initialize the plugin.
 */
function er_simmer_init() {
	\EasyRecipe_To_Simmer\Core::init();
}

// Runs the AJAX action.
add_action( 'wp_ajax_import_post', array( '\EasyRecipe_To_Simmer\Ajax', 'import_post' ) );
