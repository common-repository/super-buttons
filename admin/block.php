<?php
/**
 * Gutenberg Support
 *
 * @package Super Buttons
 */

/**
 * Register Gutenberg block
 *
 * @since 1.0.3
 */
// if ( function_exists( 'register_block_type' ) ) {
	
// }
function super_buttons_gutenberg_block() {
    // use our already defined short code.
	register_block_type( 'super-buttons/button', array(
		'render_callback' => 'super_buttons_short_code_callback',
	));
}
add_action( 'init', 'super_buttons_gutenberg_block' );

/**
 * Enqueue the block's assets for the editor.
 *
 * `wp-blocks`: includes block type registration and related functions.
 * `wp-element`: includes the WordPress Element abstraction for describing the structure of your blocks.
 * `wp-i18n`: To internationalize the block's text.
 *
 * @return void
 *
 * @author  Gautam Thapar <gautam@thewebsitedev.com>
 * @package Super Buttons
 * @link    https://thewebsitedev.com
 * @since   1.0.3
 */
function super_buttons_block_assets() {
	// Scripts.
	wp_enqueue_script(
		'super-buttons-block', // Handle.
		plugins_url( 'block/block.min.js', __FILE__ ), // Block.js: We register the block here.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
		filemtime( plugin_dir_path( __FILE__ ) . 'block/block.min.js' ) // filemtime — Gets file modification time.
	);
	wp_localize_script( 'super-buttons-block', 'super_buttons', array(
		'ajax_url'   => admin_url( 'admin-ajax.php' ),
		'ajax_nonce' => wp_create_nonce( 'super-buttons-admin' ),
	) );

	// Styles.
	wp_enqueue_style(
		'super-buttons-block', // Handle.
		plugins_url( 'block/styles/editor.css', __FILE__ ), // Block editor CSS.
		array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
		filemtime( plugin_dir_path( __FILE__ ) . 'block/styles/editor.css' ) // filemtime — Gets file modification time.
	);
}
add_action( 'enqueue_block_editor_assets', 'super_buttons_block_assets' );


// function gutenberg_boilerplate_block() {
//     wp_register_script(
//         'gutenberg-boilerplate-es5-step01',
//         plugins_url( 'block.js', __FILE__ ),
//         array( 
// 			'wp-blocks', 
// 			'wp-element', 
// 			'wp-editor', // Note the addition of wp-editor to the dependencies
// 		)
//     );

//     register_block_type( 'gutenberg-boilerplate-es5/hello-world-step-01', array(
//         'editor_script' => 'gutenberg-boilerplate-es5-step01',
//     ) );
// }
// add_action( 'init', 'gutenberg_boilerplate_block' );
