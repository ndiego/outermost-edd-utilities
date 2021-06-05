<?php
/**
 * Plugin Name:         Outermost EDD Utilities
 * Plugin URI:          https://www.nickdiego.com
 * Description:         Useful utilities for EDD
 * Version:             0.1.0
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
 *
 * @package outermost-edd-utilities
 */

defined( 'ABSPATH' ) || exit;

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
function oeu_redirect_login_to_members() {

	if ( is_page('login') && is_user_logged_in() && $_SERVER['PHP_SELF'] != '/wp-admin/admin-ajax.php' ) {
		wp_redirect( '/account/', 301 );
		exit;
  }
}
add_action( 'template_redirect', 'oeu_redirect_login_to_members' );

/**
 * Redirect logged-out users to the login page is they try and visit the account page
 */
function oeu_redirect_members_to_login() {

	if ( is_page('account') && ! is_user_logged_in() && $_SERVER['PHP_SELF'] != '/wp-admin/admin-ajax.php' ) {
		wp_redirect( '/login/', 301 );
		exit;
  }
}
add_action( 'template_redirect', 'oeu_redirect_members_to_login' );

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
