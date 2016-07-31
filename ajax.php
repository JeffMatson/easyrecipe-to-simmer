<?php

namespace EasyRecipe_To_Simmer;

class Ajax {

	public static function import_post() {

		$post_data = get_post( intval( $_POST['post_id'] ) );
		$post_content_stripped = preg_replace( '/<div class="easyrecipe".*>[\s\S]*<div class="endeasyrecipe"[\s\S]*?>[\s\S]*?<\/div>/Uism', '', $post_data->post_content );

		$post_obj = new Models\EasyRecipe( $post_data );

		$import_data_meta = array();



		$import_data = array(
			'post_author'   => $post_data->post_author,
			'post_date'     => $post_data->post_date,
			'post_date_gmt' => $post_data->post_date_gmt,
			'post_status'   => $post_data->post_status,
			'post_type'     => 'recipe',
			'post_content'  => $post_content_stripped,
			'post_title'    => $post_data->post_title,
			'meta_input'    => $import_data_meta,
		);



		$success = wp_insert_post( $import_data );

		if ( $success ) {
			echo $success;
			echo 'it worked';


			foreach ( $post_obj->instructions as $instruction ) {
				simmer_add_recipe_instruction( intval( $success ), $instruction );
			}

			foreach ( $post_obj->ingredients as $ingredient ) {
				simmer_add_recipe_ingredient( intval( $success ), $ingredient['description'], $ingredient['amount'], $ingredient['unit'] );
			}

			echo json_encode( $post_obj );
		} else {
			echo 'falure';
		}

		wp_die();
	}

}