<?php

namespace Vernon;

defined( 'ABSPATH' ) or die();

use Vernon\Rev_API;

/**
 * Transcripts class.
 *
 * @since 1.0.0
 */
final class Transcripts {

    /**
     * The prefix for saving data in database.
     *
     * @since 1.0.0
     */
    public static $prefix = 'vernon_transcript';

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
     * @return Transcripts
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
        add_action( 'init', [ $this, 'register_post_type' ] );
        add_action( 'wp_after_insert_post', [ $this, 'submit_transcript_job' ], 10, 2 );
    }

    /**
     * Registers Transcripts post types.
     *
     * @since 1.0.0
     */
    public function register_post_type() {
        register_post_type(
            'vernon-transcript',
            [
                'labels'            => [
                    'name'          => esc_html__( 'Transcripts', 'vernon' ),
                    'singular_name' => esc_html__( 'Transcript', 'vernon-transcript' ),
                ],
                'public'            => false,
                'show_in_nav_menus' => true,
                'show_ui'           => true,
                'has_archive'       => false,
                'supports'          => [ 'title', 'editor' ],
                'menu_position'     => 39,
                'show_in_rest'      => true,
            ]
        );
    }

    /**
     * When a post is updated, post a transcript job to Rev API.
     *
     * It calls the API if no other transcript has been posted before.
     *
     * @since 1.0.0
     */
    function submit_transcript_job( $post_id, $post ) {

        // Bails early if post type is not vernon-transcript.
        if ( 'vernon-transcript' !== $post->post_type ) {
            return;
        }

        // Bails early to prevent multiple hook call.
        if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
            return;
        }

        if (
            wp_is_post_autosave( $post_id )
            || wp_is_post_revision( $post_id )
        ) {
            return;
        }

        error_log( print_r( 'submit_transcript_job', true ) );

        // Gets the Job ID.
        $job_id = get_post_meta( $post_id, self::$prefix . '_id_read_only', true );

        // Bails early if Job ID exists.
        if ( ! empty(  $job_id ) ) {
            return;
        }

        // Prepares data to submit a job.
        $data = [
            'source_config' => [
                'url' => self::get_post_meta( $post_id, 'audio' ),
            ],
            'metadata'            => $post_id,
            'language'            => self::get_post_meta( $post_id, 'lang', 'en' ),
            'verbatim'            => self::get_post_meta( $post_id, 'verbatim', 'true' ),
            'skip_diarization'    => self::get_post_meta( $post_id, 'skip_diarization', 'false' ),
            'skip_postprocessing' => self::get_post_meta( $post_id, 'skip_postprocessing', 'false' ),
            'skip_punctuation'    => self::get_post_meta( $post_id, 'skip_punctuation', 'false' ),
            'remove_disfluencies' => self::get_post_meta( $post_id, 'remove_disfluencies', 'false' ),
            'remove_atmospherics' => self::get_post_meta( $post_id, 'remove_atmospherics', 'false' ),
            'filter_profanity'    => self::get_post_meta( $post_id, 'filter_profanity', 'false' ),
        ];

        // Calls the Rev API.
        $job_response = Rev_API::submit_job( $data );

        error_log( print_r( $job_response, true ) );

        // Updates Job details meta.
        update_post_meta( $post_id, self::$prefix . '_id_read_only', $job_response->id );
        update_post_meta( $post_id, self::$prefix . '_status_read_only', $job_response->status );
        update_post_meta( $post_id, self::$prefix . '_created_read_only', $job_response->created_on );

    }

    /**
     * ...
     *
     * @since 1.0.0
     */
    private static function get_post_meta( $post_id, $meta_key, $default = '' ) {
        $value = get_post_meta( $post_id, "vernon_transcript_$meta_key", true );

        if ( empty( $value ) ) {
            return $default;
        }

        return $value;
    }
}

/**
 * Initializes the class.
 *
 * @since 1.0.0
 */
Transcripts::get_instance();
