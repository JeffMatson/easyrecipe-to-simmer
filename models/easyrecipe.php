<?php

namespace EasyRecipe_To_Simmer\Models;

class EasyRecipe {

	public $post_data = null;

	public function __construct( $easyrecipe_post_data ) {
		$this->post_data = $easyrecipe_post_data->post_content;

		$this->title        = $this->get_recipe_title();
		$this->image        = $this->get_recipe_image();
		$this->servings     = $this->get_servings();
		$this->ingredients  = $this->get_incredients();
		$this->instructions = $this->get_instructions();
		$this->times        = array(
			'prep' => $this->get_prep_time(),
			'cook' => $this->get_cook_time(),
			'total' => $this->get_total_time(),
		);
	}

	public function trim_recipe_data( $post_content ) {
		$start = strpos( $post_content, 'easyrecipe' );
		$end = strpos( $post_content, 'endeasyrecipe');
		$length = $end - $start;

		return substr( $post_content, $start, $length );
	}

	public function get_recipe_title() {
		$start = strpos( $this->post_data, '<div class="item ERName">' );

		if ( $start === false ) {
			return null;
		}

		$end = strpos( $this->post_data, '</div>', $start);
		$length = $end - $start;

		return wp_strip_all_tags( substr( $this->post_data, $start, $length ) );
	}

	public function get_recipe_image() {
		preg_match("'<link itemprop=\"image\" href=\"(.*?)\">'si", $this->post_data, $matches);
		return $matches[0];
	}

	public function get_prep_time() {
		$start = strpos( $this->post_data, '<time itemprop="prepTime"' );

		if ( $start === false ) {
			return null;
		}

		$end = strpos( $this->post_data, '</time>', $start);
		$length = $end - $start;

		return wp_strip_all_tags( substr( $this->post_data, $start, $length ) );
	}

	public function get_cook_time() {
		$start = strpos( $this->post_data, '<time itemprop="cookTime"' );

		if ( $start === false ) {
			return null;
		}

		$end = strpos( $this->post_data, '</time>', $start);
		$length = $end - $start;

		return wp_strip_all_tags( substr( $this->post_data, $start, $length ) );
	}

	public function get_total_time() {
		$start = strpos( $this->post_data, '<time itemprop="totalTime"' );

		if ( $start === false ) {
			return null;
		}

		$end = strpos( $this->post_data, '</time>', $start);
		$length = $end - $start;

		return wp_strip_all_tags( substr( $this->post_data, $start, $length ) );
	}

	public function get_servings() {
		$start = strpos( $this->post_data, '<span class="yield">' );

		if ( $start === false ) {
			return null;
		}

		$end = strpos( $this->post_data, '</span>', $start);
		$length = $end - $start;

		return wp_strip_all_tags( substr( $this->post_data, $start, $length ) );
	}

	public function get_incredients() {
		preg_match_all("'<li class=\"ingredient\">(.*?)</li>'si", $this->post_data, $matches);

		$ingredients = array();

		foreach ( $matches[1] as $ingredient ) {
			$parsed_ingredient = \Simmer_Admin_Bulk_Add::get_instance()->parse_input( $ingredient, 'ingredient');
			$ingredients[] = $parsed_ingredient['0'];
		}

		return $ingredients;
	}

	public function get_incredient_quantity( $ingredient ) {
		preg_match( '/^([\d\/\s\.]*)/im', $ingredient, $match );
		return trim( $match[0] );
	}

	public function get_ingredient_unit( $ingredient ) {
		$measurements = array(
			'tsp' => array(
				'teaspoon',
				'teaspoons',
				'tsp',
			),
			'tbsp' => array(
				'tablespoon',
				'tablespoons',
				'tbsp',
			),
			'floz' => array(
				'fluid ounce',
				'fluid ounces',
				'floz',
				'fl oz'
			),
			'cup' => array(
				'cup',
				'cups'
			),
			'pt' => array(
				'pint',
				'pints',
				'pt'
			),
		);
	}

	public function get_instructions() {
		preg_match_all("'<li class=\"instruction\">(.*?)</li>'si", $this->post_data, $matches);
		return $matches[1];
	}
}