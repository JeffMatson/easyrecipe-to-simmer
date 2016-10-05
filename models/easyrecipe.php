<?php

namespace EasyRecipe_To_Simmer\Models;

class EasyRecipe {

	/**
	 * Stores the post data.  Set on construct.
	 *
	 * @var null
	 */
	public $post_data = null;

	/**
	 * EasyRecipe constructor.
	 *
	 * @param $easyrecipe_post_data
	 */
	public function __construct( $easyrecipe_post_data ) {
		// Set the post data as a class property for easy access.
		$this->post_data = $easyrecipe_post_data->post_content;

		// Get the recipe title and store it as a class property.
		$this->title        = $this->get_recipe_title();
		// Get the recipe image and store it as a class property.
		$this->image        = $this->get_recipe_image();
		// Get the serving info and store it as a class property.
		$this->servings     = $this->get_servings();
		// Get the incredients and store it as a class property.
		$this->ingredients  = $this->get_incredients();
		// Get the instructions and store it as a class property.
		$this->instructions = $this->get_instructions();
		// Get the prep, cook, and total times and set it as a class property.
		$this->times        = array(
			'prep' => $this->get_prep_time(),
			'cook' => $this->get_cook_time(),
			'total' => $this->get_total_time(),
		);
	}

	public function get_tag_content( $post_content, $first_tag, $second_tag ) {
		$start  = strpos( $post_content, $first_tag );

		if ( $start === false ) {
			return null;
		}

		$end    = strpos( $post_content, $second_tag );

		$length = $end - $start;

		return array(
			'start'  => $start,
			'end'    => $end,
			'length' => $length,
		);
	}

	/**
	 * Trims out the recipe data from the post content.
	 *
	 * @param $post_content
	 *
	 * @return string
	 */
	public function trim_recipe_data( $post_content ) {

		$tag_content = $this->get_tag_content( $post_content, 'easyrecipe', 'endeasyrecipe' );

		// Return only the EasyRecipe post data.
		return substr( $post_content, $tag_content['start'], $tag_content['length'] );
	}

	/**
	 * Gets the recipe title from the post content.
	 *
	 * @return null|string
	 */
	public function get_recipe_title() {
		// Get the beginning of the recipe title based on the div it's contained it.
		$start = strpos( $this->post_data, '<div class="item ERName">' );

		// If not found, return null.
		if ( $start === false ) {
			return null;
		}

		// Get the next closing div tag.
		$end = strpos( $this->post_data, '</div>', $start);
		// Check the string length.
		$length = $end - $start;

		// Strip all tags out and return the content between the divs.
		return wp_strip_all_tags( substr( $this->post_data, $start, $length ) );
	}

	/**
	 * Gets the recipe image from the recipe data.
	 *
	 * @return mixed
	 */
	public function get_recipe_image() {
		// Get the recipe image based on the tags used in EasyRecipe.
		preg_match("'<link itemprop=\"image\" href=\"(.*?)\">'si", $this->post_data, $matches);
		// Return the found match.
		return $matches[0];
	}

	/**
	 * Gets the prep time from the recipe data.
	 *
	 * @return null|string
	 */
	public function get_prep_time() {
		// Get the beginning of the prep time based on tags used in EasyRecipe.
		$start = strpos( $this->post_data, '<time itemprop="prepTime"' );

		// If not found, return null.
		if ( $start === false ) {
			return null;
		}

		// Get the position of the closing tag.
		$end = strpos( $this->post_data, '</time>', $start);
		// Get the length between the starting and ending tags.
		$length = $end - $start;

		// Strip any tags, and return the found content.
		return wp_strip_all_tags( substr( $this->post_data, $start, $length ) );
	}

	/**
	 * Gets the cook time from the recipe data.
	 *
	 * @return null|string
	 */
	public function get_cook_time() {
		// Get the location of the first tag containing the cook time.
		$start = strpos( $this->post_data, '<time itemprop="cookTime"' );

		// If not found, return null.
		if ( $start === false ) {
			return null;
		}

		// Find the closing tag.
		$end = strpos( $this->post_data, '</time>', $start);
		// Calculate the length between the starting and closing tags.
		$length = $end - $start;

		// Strip any tags and return the found string.
		return wp_strip_all_tags( substr( $this->post_data, $start, $length ) );
	}

	/**
	 * Gets the total time from the recipe data.
	 *
	 * @return null|string
	 */
	public function get_total_time() {
		// Get the total time based on the tag used in EasyRecipe.
		$start = strpos( $this->post_data, '<time itemprop="totalTime"' );

		// If not found, return null.
		if ( $start === false ) {
			return null;
		}

		// Get the position of the closing tag.
		$end = strpos( $this->post_data, '</time>', $start);
		// Calculate the length between the tags.
		$length = $end - $start;

		// Strip all tags and return the content between the found positions.
		return wp_strip_all_tags( substr( $this->post_data, $start, $length ) );
	}

	/**
	 * Gets the servings from the recipe data.
	 *
	 * @return null|string
	 */
	public function get_servings() {
		// Get the position of the serving data based on the tag used.
		$start = strpos( $this->post_data, '<span class="yield">' );

		// If nothing found, return null.
		if ( $start === false ) {
			return null;
		}

		// Get the position of the closing tag.
		$end = strpos( $this->post_data, '</span>', $start);
		// Calculate the length.
		$length = $end - $start;

		// Strip all tags and return the content.
		return wp_strip_all_tags( substr( $this->post_data, $start, $length ) );
	}

	/**
	 * Gets the ingredients from the recipe data.
	 *
	 * @return array
	 */
	public function get_incredients() {
		// Get any ingredients that match the tag used.
		preg_match_all("'<li class=\"ingredient\">(.*?)</li>'si", $this->post_data, $matches);

		// Set up an empty array to store the processed data.
		$ingredients = array();

		// Loop through any matches.
		foreach ( $matches[1] as $ingredient ) {
			// Parse the ingredients using Simmer's own functionality.
			$parsed_ingredient = \Simmer_Admin_Bulk_Add::get_instance()->parse_input( $ingredient, 'ingredient');
			// Add the ingredient to the array.
			$ingredients[] = $parsed_ingredient['0'];
		}

		// Return the parsed ingredients.
		return $ingredients;
	}

	/**
	 * Gets the instructions from the recipe data.
	 *
	 * @return mixed
	 */
	public function get_instructions() {
		// Search the post content for instruction tags used by EasyRecipe.
		preg_match_all("'<li class=\"instruction\">(.*?)</li>'si", $this->post_data, $matches);
		// Return the found matches.
		return $matches[1];
	}
}