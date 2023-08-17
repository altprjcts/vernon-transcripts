<?php

namespace Vernon_Transcripts;

defined( 'ABSPATH' ) or die();

/**
 * Post Types class.
 *
 * @since 1.0.0
 */
final class Post_Types {

    /**
     * The instance.
     *
     * @since 1.0.0
     */
    private static $instance;

    /**
     * Returns the instance.
     *
     * @since 1.0.0
     *
     * @return Post_Types
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    private function __construct() {
        $this->add_hooks();
    }

    /**
     * Adds hooks.
     *
     * @since 1.0.0
     */
    protected function add_hooks() {
        add_action( 'init', [ $this, 'register_custom_post_types' ] );
    }

    /**
     * Register custom post types.
     *
     * @since 1.0.0
     */
    public function register_custom_post_types() {
        register_post_type(
            'vernon-transcripts',
            [
                'labels'            => [
                    'name'          => esc_html__( 'Transcripts', 'vernon-transcripts' ),
                    'singular_name' => esc_html__( 'Transcript', 'vernon-transcripts' ),
                ],
                'public'            => false,
                'show_in_nav_menus' => true,
                'show_ui'           => true,
                'has_archive'       => false,
                'supports'          => [ 'title', 'editor' ],
                'menu_position'     => 39,
                'show_in_rest'		=> true,
            ]
        );
    }
}

/**
 * Initializes the class.
 *
 * @since 1.0.0
 */
Post_Types::get_instance();
