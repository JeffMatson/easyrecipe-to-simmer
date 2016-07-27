<?php

namespace EasyRecipe_To_Simmer\Views;

use EasyRecipe_To_Simmer\Core;

class Settings {

	public function init() {
		$this->display();
	}

	public function add_actions() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( 'bootstrap', ER2SIMMER_URL . 'js/includes/bootstrap.min.js', array( 'jquery' ), '3.3.7' );
	}

	public function enqueue_styles() {
		wp_enqueue_style( 'bootstrap', ER2SIMMER_URL . 'css/includes/bootstrap.min.css', array(), '3.3.7' );
		wp_enqueue_style( 'bootstrap-theme', ER2SIMMER_URL . 'css/includes/bootstrap-theme.min.css', array( 'bootstrap' ), '3.3.7' );
	}

	public function display() { ?>

		<div class="wrap">

			<h1>EasyRecipe To Simmer Migration Tool</h1>

			<?php $this->build_post_selections(); ?>


		</div>

	<?php
	}

	public function build_post_selections() { ?>

		<ul class="list-group">

		<?php foreach ( Core::pull_from_easyrecipe() as $found_post ) : ?>
			<div class="list-group-item">
				<div style="display:flex; align-items: center">
				<div class="checkbox col-md-1 col-lg-1">
					<label>
						<input type="checkbox">
					</label>
				</div>
				<div class="col-md-9 col-lg-9">
					<?php echo $found_post->post_title; ?>
				</div>
				</div>
			</div>
		<?php endforeach; ?>

		</ul>
		<?php var_dump( Core::pull_from_easyrecipe() ); ?>

	<?php
	}

}