<?php

function render_block_user_info( $attributes ) {
	$current_user = wp_get_current_user();

	if ( ! ( $current_user instanceof WP_User ) || ! $current_user ) {
		return;
	}

	$wrapper_attributes = get_block_wrapper_attributes();

	$avatar = get_avatar( $current_user->ID, 90 );

	if ( isset( $attributes['imageLink'] ) ) {
		$avatar = sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url( $attributes['imageLink'] ),
			$avatar
		);
	}

	$welcome_message = sprintf(
		'<span class="welcome-message">%1$s</span>',
		__( 'Welcome' )
	);

	$user_name = esc_html( $current_user->display_name );

	if ( isset( $attributes['userNameLink'] ) ) {
		$user_name = sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url( $attributes['userNameLink'] ),
			$user_name
		);
	}

	$user_name = '<h2 class="user-name">' . $user_name . '</h2>';

	$logout_message = sprintf(
		'<span class="logout-message">%1$s <a rel="nofollow" href="%2$s">%3$s</a></span>',
		__( 'Not you?' ),
		esc_url( wp_logout_url() ),
		__( 'Log out.' )
	);

	$markup  = '<div class="user-avatar">' . $avatar . '</div>';
	$markup .= '<div class="user-details">' . $welcome_message . $user_name . $logout_message . '</div>';

	return sprintf(
		'<div %1$s>%2$s</div>',
		$wrapper_attributes,
		$markup
	);
}

function register_block_user_info() {

	register_block_type_from_metadata(
		__DIR__, // This needs to target the directory that the block.json is in.
		array(
			'render_callback' => 'render_block_user_info'
		)
	);
}
add_action( 'init', 'register_block_user_info' );
