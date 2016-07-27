<?php

namespace EasyRecipe_To_Simmer\Models;

class Simmer {

	public static $easyrecipe_obj = null;

	public function __construct( $easyrecipe_obj ) {
		self::$easyrecipe_obj = $easyrecipe_obj;
	}

}