<?php

namespace Vernon;

defined( 'ABSPATH' ) or die();

/**
 * Rev API class.
 *
 * @since 1.0.0
 */
final class Rev_API {

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
     * @return Rev_API
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
    }

    /**
     * ...
     *
     * @since 1.0.0
     */
    public static function submit_job( $data ) {
        error_log( print_r( 'submit_job', true ) );

        $response = wp_remote_post( 'https://api.rev.ai/speechtotext/v1/jobs', [
            'body' => json_encode( $data ),
            'headers' => [
                'content-type'  => 'application/json',
                'Authorization' => 'Bearer ' . self::get_auth_token(),
            ],
        ] );

        return json_decode( wp_remote_retrieve_body( $response ) );
    }

    /**
     * ...
     *
     * @since 1.0.0
     */
    private static function get_auth_token() {
        return cmb2_get_option( 'vernon_transcript_settings', 'vernon_transcript_rev_token' );
    }

}

/**
 * Initializes the class.
 *
 * @since 1.0.0
 */
Rev_API::get_instance();
