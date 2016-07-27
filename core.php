<?php

namespace EasyRecipe_To_Simmer;

use EasyRecipe_To_Simmer\Models\EasyRecipe;
use EasyRecipe_To_Simmer\Views\Settings;

class Core {

	public static $instance;

	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public static function init() {
		self::add_actions();
	}

	public static function add_actions() {
		add_action( 'admin_menu', array( '\EasyRecipe_To_Simmer\Core', 'admin_menu' ) );
		add_action( 'current_screen', array( '\EasyRecipe_To_Simmer\Core', 'conditional_actions' ) );
	}

	public static function conditional_actions() {
		$current_screen = get_current_screen();

		if ( $current_screen->id == 'tools_page_easyrecipe-to-simmer' ) {
			$display = new Settings();
			$display->add_actions();
		}
	}

	public static function admin_menu() {
		add_management_page( 'EasyRecipe to Simmer', 'EasyRecipe to Simmer', 'edit_posts', 'easyrecipe-to-simmer', array( '\EasyRecipe_To_Simmer\Core', 'display_view' ) );
	}

	public static function display_view() {
		$display = new Settings();
		$display->init();
	}

	public static function post_uses_easyrecipe( $post ) {

		$search = strpos( $post->post_content, 'easyrecipe' );

		if ( $search === false ) {
			return false;
		} else {
			return true;
		}
	}

	public static function pull_from_easyrecipe( $limit = -1 ) {

		$query = get_posts( array( 'numberposts' => $limit ) );

		$posts_found = array();

		foreach ( $query as $post ) {
			if ( self::post_uses_easyrecipe( $post ) ) {
				$posts_found[] = $post;
			}
		}

		return $posts_found;

	}

	public static function process_easyrecipe_single( $post ) {
		$recipe_data = new EasyRecipe( $post );
		return $recipe_data;
	}

	public static function process_easyrecipe_items() {
		$processed_items = array();

		foreach ( self::pull_from_easyrecipe() as $item ) {
			$processed_items[] = self::process_easyrecipe_single( $item );
		}

		return $processed_items;
	}

	public static function insert_into_simmer() {

	}

}