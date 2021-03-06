<?php
/**
 * Plugin Name:         Outermost EDD Utilities
 * Plugin URI:          https://www.nickdiego.com
 * Description:         Useful utilities for EDD
 * Version:             0.4.1
 * Requires at least:   5.5
 * Requires PHP:        5.6
 * Author:              Nick Diego
 * Author URI:          https://www.nickdiego.com
 * License:             GPLv2
 * License URI:         https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain:         oeu
 * Domain Path:         /languages
 *
 * GitHub Plugin URI:   ndiego/outermost-edd-utilities
 * Primary Branch:      main
 *
 * @package outermost-edd-utilities
 */

defined( 'ABSPATH' ) || exit;


function oeu_register_editor_scripts_styles() {

	// Scripts.
	$asset_file = include( plugin_dir_path( __FILE__ ) . 'build/index.asset.php');

	wp_enqueue_script(
		'oeu-editor-scripts',
		plugins_url( 'build/index.js', __FILE__ ),
		$asset_file['dependencies'],
		$asset_file['version']
	);

	// Styles.
	//$asset_file = include( plugin_dir_path( __FILE__ ) . 'build/index.asset.php');

	wp_enqueue_style(
		'oeu-editor-styles',
		plugins_url( 'build/index.css', __FILE__ ),
		array()
	);
}
add_action( 'enqueue_block_editor_assets', 'oeu_register_editor_scripts_styles' );

//require_once plugin_dir_path( __FILE__ ) . 'src/blocks/user-info/index.php';
include_once dirname( __FILE__ ) . '/src/blocks/user-info/index.php';


/**
 * When the cart is empty, redirect to the pricing page.
 */
function oeu_redirect_empty_cart() {

	if (
		is_page( edd_get_option( 'purchase_page' ) ) &&
		edd_get_cart_quantity() == 0
	) {

		wp_redirect( get_home_url( null, '/pricing' ) );

		exit;
	}
}
add_action( 'template_redirect', 'oeu_redirect_empty_cart', 11 );

/**
 * Redirects all download pages to the pricing page. We don't want customers to
 * be able to access the downloads pages directly.
 */
function oeu_downloads_redirect_to_pricing() {

    if ( is_singular( 'download' ) ) {

        wp_redirect( get_home_url( null, '/pricing' ) );

		exit;
    }
}
add_action( 'template_redirect', 'oeu_downloads_redirect_to_pricing' );

/**
 * Excludes all download pages from search.
 */
function oeu_edd_hide_from_search( $args ) {
	$args['exclude_from_search'] = true;

	return $args;
}
add_filter( 'edd_download_post_type_args', 'oeu_edd_hide_from_search' );

/**
 * Redirect logged-in users to account page if they try and visit the login page
 */
function oeu_redirect_login_to_account() {

	if ( is_page('login') && is_user_logged_in() && $_SERVER['PHP_SELF'] !== '/wp-admin/admin-ajax.php' ) {
		wp_redirect( '/account/', 301 );
		exit;
  }
}
add_action( 'template_redirect', 'oeu_redirect_login_to_account' );

/**
 * Redirect logged-out users to the login page is they try and visit the account page
 */
function oeu_redirect_account_to_login() {

	$slug = 'account';

	if ( ! is_user_logged_in() ) {

		// If we are already on the Account page, redirect.
		if ( is_page( $slug ) && $_SERVER['PHP_SELF'] !== '/wp-admin/admin-ajax.php' ) {
			wp_redirect( '/login/', 301 );
			exit;
		}

		$page      = get_page_by_path( $slug );
		$parent_id = wp_get_post_parent_id( get_the_ID() );

		// If we are on a child page and the parent id is equal to the Account page, redirect.
		if ( $parent_id  && $parent_id === $page->ID ) {
			wp_redirect( '/login/', 301 );
			exit;
		}
	}
}
add_action( 'template_redirect', 'oeu_redirect_account_to_login' );



// Filter the from email for all system emails
function blox_filter_wp_mail_from( $email ){
	return "support@bloxwp.com";
}
//add_filter( 'wp_mail_from', 'blox_filter_wp_mail_from' );

// Filter the from name for all system emails
function blox_filter_wp_mail_from_name( $from_name ){
	return "Blox Support";
}
//add_filter( 'wp_mail_from_name', 'blox_filter_wp_mail_from_name' );

/* Add wrapper around the edd_checkout_form_wrap div and add a header for renewals and discounts */
function oeu_before_checkout_form_wrap() {
	echo '<div class="edd_checkout_form_wrap_main"><h3>' . __( 'Renewals & Discounts', 'outermost-edd-utilities' ) . '</h3>';
}
add_action( 'edd_after_checkout_cart', 'oeu_before_checkout_form_wrap', 0 );

/* Add the closing div for the new edd_checkout_form_wrap_main div */
function oeu_after_checkout_form_wrap() {
	echo '</div>';
}
add_action( 'edd_after_purchase_form', 'oeu_after_checkout_form_wrap', 0 );

/* Add message before the purchase button */
function oeu_edd_before_submit() {
	echo '<h3>' . __( 'You\'re almost done!', 'outermost-edd-utilities' ) . '</h3>';
}
add_action( 'edd_purchase_form_before_submit', 'oeu_edd_before_submit', 0 );
