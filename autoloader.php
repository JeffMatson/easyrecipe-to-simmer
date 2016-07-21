<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

function easyrecipe_to_simmer_autoload( $classname ) {

	$class = str_replace( '\\', DIRECTORY_SEPARATOR, str_replace( '_', '-', strtolower($classname) ) );
	$file_path = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $class . '.php';

	if ( file_exists( $file_path ) ) {
		require_once $file_path;
	}

}

spl_autoload_register('easyrecipe_to_simmer_autoload');