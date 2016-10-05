<?php

namespace EasyRecipe_To_Simmer;

class Ajax {

	public static function import_post() {

		// Gets the post to import.
		$post_data = get_post( intval( $_POST['post_id'] ) );
		// Strips the EasiyRecipe data from the post content.
		$post_content_stripped = preg_replace( '/<div class="easyrecipe".*>[\s\S]*<div class="endeasyrecipe"[\s\S]*?>[\s\S]*?<\/div>/Uism', '', $post_data->post_content );
		// Processes the data from the post content.
		$post_obj = new Models\EasyRecipe( $post_data );

		// Sets up the data to be imported.
		$import_data = array(
			'post_author'   => $post_data->post_author,
			'post_date'     => $post_data->post_date,
			'post_date_gmt' => $post_data->post_date_gmt,
			'post_status'   => $post_data->post_status,
			'post_type'     => 'recipe',
			'post_content'  => $post_content_stripped,
			'post_title'    => $post_data->post_title,
			'meta_input'    => array(),
		);

		// Inserts the new post using the recipe post type used by Simmer.
		$success = wp_insert_post( $import_data );

		// If it worked, do things.
		if ( $success ) {
			// Send the success message for debugging.
			echo $success;
			// Hooray
			echo 'it worked';

			// Gets the instructions and imports them using Simmer's existing functionality.
			foreach ( $post_obj->instructions as $instruction ) {
				simmer_add_recipe_instruction( intval( $success ), $instruction );
			}

			// Gets the ingredients and imports them using Simmer's existing functionality.
			foreach ( $post_obj->ingredients as $ingredient ) {
				simmer_add_recipe_ingredient( intval( $success ), $ingredient['description'], $ingredient['amount'], $ingredient['unit'] );
			}

			// JSON encode the data and respond with it.
			echo json_encode( $post_obj );
		} else {
			// You have failed at life.
			echo 'falure';
		}

		// Kill it with fire to prevent WP AJAX 0 response.
		wp_die();
	}

}