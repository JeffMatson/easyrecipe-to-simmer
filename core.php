<?php

// Define the namespace.
namespace EasyRecipe_To_Simmer;

use EasyRecipe_To_Simmer\Models\EasyRecipe;
use EasyRecipe_To_Simmer\Views\Settings;

/**
 * Class Core
 * @package EasyRecipe_To_Simmer
 */
class Core {

	/**
	 * Initializes the plugin.
	 *
	 * Called from the main plugin file.
	 *
	 * @uses \EasyRecipe_To_Simmer\Core::add_actions
	 */
	public static function init() {
		self::add_actions();
	}

	/**
	 * Adds any required actions.
	 *
	 * @used-by \EasyRecipe_To_Simmer\Core::init
	 * @uses    \EasyRecipe_To_Simmer\Core::admin_menu
	 * @uses    \EasyRecipe_To_Simmer\Core::conditional_actions
	 */
	public static function add_actions() {
		// Run admin menu.
		add_action( 'admin_menu', array( '\EasyRecipe_To_Simmer\Core', 'admin_menu' ) );
		// Run any conditional actions needed.
		add_action( 'current_screen', array( '\EasyRecipe_To_Simmer\Core', 'conditional_actions' ) );
	}

	/**
	 * Conditionally adds actions based on the current screen.
	 *
	 * @used-by \EasyRecipe_To_Simmer\Core::add_actions
	 * @uses    \EasyRecipe_To_Simmer\Views\Settings::add_actions
	 */
	public static function conditional_actions() {
		// Check the current screen.
		$current_screen = get_current_screen();

		// If we are on te Settings page, running the Settings view.
		if ( $current_screen->id == 'tools_page_easyrecipe-to-simmer' ) {
			// Get an instance of the Settings view.
			$display = new Settings();
			// Run the actions tied to the Settings view.
			$display->add_actions();
		}
	}

	/**
	 * Initializes the admin menu item.
	 *
	 * @used-by \EasyRecipe_To_Simmer\Core::add_actions
	 */
	public static function admin_menu() {
		// Add the admin menu item.
		add_management_page( 'EasyRecipe to Simmer', 'EasyRecipe to Simmer', 'edit_posts', 'easyrecipe-to-simmer', array( '\EasyRecipe_To_Simmer\Core', 'display_view' ) );
	}

	/**
	 * Determines what view to display and initializes it.
	 *
	 * @used-by \EasyRecipe_To_Simmer\Core::admin_menu
	 * @uses    \EasyRecipe_To_Simmer\Views\Settings::init
	 */
	public static function display_view() {
		// Get an instance of the Settings view.
		$display = new Settings();
		// Initialize the Settings view.
		$display->init();
	}

	/**
	 * Determines if a post uses EasyRecipe.
	 *
	 * @used-by \EasyRecipe_To_Simmer\Core::pull_from_easyrecipe
	 * @uses    \WP_Post::$post_content
	 *
	 * @param $post
	 *
	 * @return bool
	 */
	public static function post_uses_easyrecipe( $post ) {

		// Check the post content for the 'easyrecipe' string.
		$search = strpos( $post->post_content, 'easyrecipe' );

		// If the search returns false, return false here. Otherwise, return true.
		if ( $search === false ) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Gets a listing of posts that use EasyRecipe.
	 *
	 * @used-by \EasyRecipe_To_Simmer\Core::pull_from_easyrecipe
	 * @used-by \EasyRecipe_To_Simmer\Views\Settings::build_post_selections
	 * @uses    \EasyRecipe_To_Simmer\Core::post_uses_easyrecipe
	 *
	 * @param int $limit
	 *
	 * @return array
	 */
	public static function pull_from_easyrecipe( $limit = -1 ) {

		// Get all posts.
		$query = get_posts( array( 'numberposts' => $limit ) );

		// Create an empty array to store the post objects found.
		$posts_found = array();

		// Run through each of the posts found.
		foreach ( $query as $post ) {
			// Check if the post found is using EasyRecipe.
			if ( self::post_uses_easyrecipe( $post ) ) {
				// Add the post to the array.
				$posts_found[] = $post;
			}
		}

		// Return the array containing all posts found that use EasyRecipe.
		return $posts_found;

	}

}