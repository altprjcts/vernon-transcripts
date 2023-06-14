<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Register all custom post types.
add_action( 'init', 'vernon_transcripts_register_custom_post_types' );
function vernon_transcripts_register_custom_post_types() {

	register_post_type(
		'vernon-transcripts',
		array(
			'labels'            => array(
				'name'          => __( 'Transcripts' ),
				'singular_name' => __( 'Transcript' ),
			),
			'public'            => false,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'has_archive'       => false,
			'supports'          => array( 'editor' ),
			'menu_position'     => 39,
			'show_in_rest'		=> true,
		)
	);
}
