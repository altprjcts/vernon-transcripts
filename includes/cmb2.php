<?php

namespace Vernon;

defined( 'ABSPATH' ) or die();

/**
 * Custom Fields class.
 *
 * @since 1.0.0
 */
final class Custom_Fields {

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
     * @return Custom_Fields
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
        add_action( 'cmb2_admin_init', [ $this, 'add_transcript_metaboxes' ] );
        add_action( 'cmb2_admin_init', [ $this, 'add_transcript_settings_page' ] );
    }

    /**
     * Adds metaboxes to Transcripts post type.
     *
     * @since 1.0.0
     */
    public function add_transcript_metaboxes() {
        $prefix = 'vernon_transcript_';

        // Registers 'Transcript Job Request Details' metabox.
        $job_request_details = new_cmb2_box(
            [
                'id'           => $prefix . 'job_request_metabox',
                'title'        => esc_html__( 'Transcript Job Request Details', 'vernon' ),
                'object_types' => [ 'vernon-transcript' ],
            ]
        );

        // Adds fields to 'Transcript Job Request Details' metabox.
        $job_request_details->add_field( [
            'name'    => esc_html__( 'Audio File', 'vernon' ),
            'desc'    => esc_html__( 'Upload an audio file or enter an URL.', 'vernon' ),
            'id'    => $prefix . 'audio',
            'type'    => 'file',
            'options' => [
                'url' => true,
            ],
            'text'    => [
                'add_upload_file_text' => esc_html__( 'Add File', 'vernon' ),
            ],
            // query_args are passed to wp.media's library query.
            'query_args' => [
                'type' => 'audio/mpeg',
                // Or only allow gif, jpg, or png images
                // 'type' => array(
                //     'image/gif',
                //     'image/jpeg',
                //     'image/png',
                // ),
            ],
        ] );

        $job_request_details->add_field( [
            'name'             => esc_html__( 'Language', 'vernon' ),
            'id'               => $prefix . 'lang',
            'type'             => 'select',
            'show_option_none' => false,
            'default'          => 'en',
            'options'          => [
                'en'  => esc_html__( 'English', 'vernon' ),
            ],
        ] );

        $job_request_details->add_field( [
            'name'    =>  esc_html__( 'Verbatim', 'vernon' ),
            'id'      => $prefix . 'verbatim',
            'desc'    => esc_html__( 'Configures the transcriber to transcribe every syllable. This will include all false starts and disfluencies in the transcript.', 'vernon' ),
            'type'    => 'radio_inline',
            'options' => [
                'true'  => esc_html__( 'True', 'vernon' ),
                'false' => esc_html__( 'False', 'vernon' ),
            ],
            'default' => 'true',
        ] );

        $job_request_details->add_field( [
            'name'    => esc_html__( 'Skip Diarization', 'vernon' ),
            'id'      => $prefix . 'skip_diarization',
            'desc'    => esc_html__( 'Specify if speaker diarization will be skipped by the speech engine', 'vernon' ),
            'type'    => 'radio_inline',
            'options' => [
                'true'  => esc_html__( 'True', 'vernon' ),
                'false' => esc_html__( 'False', 'vernon' ),
            ],
            'default' => 'false',
        ] );

        $job_request_details->add_field( [
            'name'    => esc_html__( 'Skip postprocessing', 'vernon' ),
            'id'      => $prefix . 'skip_postprocessing',
            'desc'    => esc_html__( 'Only available for English and Spanish languages. User-supplied preference on whether to skip post-processing operations such as inverse text normalization (ITN), casing and punctuation.', 'vernon' ),
            'type'    => 'radio_inline',
            'options' => [
                'true'  => esc_html__( 'True', 'vernon' ),
                'false' => esc_html__( 'False', 'vernon' ),
            ],
            'default' => 'false',
        ] );

        $job_request_details->add_field( [
            'name'    => esc_html__( 'Skip Punctuation', 'vernon' ),
            'id'      => $prefix . 'skip_punctuation',
            'desc'    => esc_html__( 'Specify if "punct" type elements will be skipped by the speech engine. For JSON outputs, this includes removing spaces. For text outputs, words will still be delimited by a space', 'vernon' ),
            'type'    => 'radio_inline',
            'options' => array(
                'true'  => esc_html__( 'True', 'vernon' ),
                'false' => esc_html__( 'False', 'vernon' ),
            ),
            'default' => 'false',
        ] );

        $job_request_details->add_field( [
            'name'    =>  esc_html__( 'Remove Disfluencies', 'vernon' ),
            'id'      => $prefix . 'remove_disfluencies',
            'desc'    =>  esc_html__( 'Currently we only define disfluencies as \'ums\' and \'uhs\'. When set to true, disfluencies will not appear in the transcript. This option also removes atmospherics if the remove_atmospherics is not set.', 'vernon' ),
            'type'    => 'radio_inline',
            'options' => [
                'true'  => esc_html__( 'True', 'vernon' ),
                'false' => esc_html__( 'False', 'vernon' ),
            ],
            'default' => 'false',
        ] );

        $job_request_details->add_field( [
            'name'    => esc_html__( 'Remove Atmospherics', 'vernon' ),
            'id'      => $prefix . 'remove_atmospherics',
            'desc'    => esc_html__( 'We define many atmospherics such <laugh>, <affirmative> etc. When set to true, atmospherics will not appear in the transcript.', 'vernon' ),
            'type'    => 'radio_inline',
            'options' => [
                'true'  => esc_html__( 'True', 'vernon' ),
                'false' => esc_html__( 'False', 'vernon' ),
            ],
            'default' => 'false',
        ] );

        $job_request_details->add_field( [
            'name'    => esc_html__( 'Filter Profanity', 'vernon' ),
            'id'      => $prefix . 'filter_profanity',
            'desc'    => esc_html__( 'Enabling this option will filter for approx. 600 profanities, which cover most use cases. If a transcribed word matches a word on this list, then all the characters of that word will be replaced by asterisks except for the first and last character.', 'vernon' ),
            'type'    => 'radio_inline',
            'options' => array(
                'true'  => esc_html__( 'True', 'vernon' ),
                'false' => esc_html__( 'False', 'vernon' ),
            ),
            'default' => 'false',
        ] );

        $group_field_id = $job_request_details->add_field(
            [
                'id'          => $prefix . 'speaker_repeat_group',
                'type'        => 'group',
                'desc'        => esc_html__( 'Used to identify speakers when postprocessing transcript in Word Document.', 'vernon' ),
                'options'     => [
                    'group_title'   => esc_html__( 'Speaker {#}', 'vernon' ),
                    'add_button'    => esc_html__( 'Add Another Speaker', 'vernon' ),
                    'remove_button' => esc_html__( 'Remove Speaker', 'vernon' ),
                    'sortable'      => true,
                    'closed'        => true,
                ],
            ]
        );

        // IDs for group's fields only need to be unique for the group. Prefix is not needed.
        $job_request_details->add_group_field(
            $group_field_id,
            [
                'name' => esc_html__( 'Name', 'vernon' ),
                'id'   => 'name',
                'type' => 'text',
            ]
        );

        // Registers 'Transcript Completed Job Details' metabox.
        $job_complete_details = new_cmb2_box(
            [
                'id'           => $prefix . 'job_complete_metabox',
                'title'        => esc_html__( 'Transcript Completed Job Details', 'vernon' ),
                'object_types' => [ 'vernon-transcript' ],
            ]
        );

        // Adds fields to 'Transcript Completed Job Details' metabox.
        $job_complete_details->add_field( [
            'name'    => esc_html__( 'ID', 'vernon' ),
            'id'      => $prefix . 'id_read_only',
            'type'    => 'text',
            'desc'    => esc_html__( 'The job ID.', 'vernon' ),
            'attributes' => [
                'readonly' => 'readonly',
                'disabled' => 'disabled',
            ],
            'save_field'  => false,
        ] );

        $job_complete_details->add_field( [
            'name'    => esc_html__( 'Audio Duration', 'vernon' ),
            'id'      => $prefix . 'audio_length_read_only',
            'type'    => 'text',
            'desc'    => esc_html__( 'Duration of the file in seconds. Null if the file could not be retrieved or there was not a valid media file.', 'vernon' ),
            'attributes' => [
                'readonly' => 'readonly',
                'disabled' => 'disabled',
            ],
            'save_field'  => false,
            'column' => [
                'position' => 2,
                'name' => esc_html__( 'Duration', 'vernon' ),
            ],
        ] );

        $job_complete_details->add_field( [
            'name' => esc_html__( 'Created On', 'vernon' ),
            'id'   => $prefix . 'created_read_only',
            'type' => 'text_datetime_timestamp',
            'desc'    => esc_html__( 'The date and time the job was created in ISO-8601 UTC form.', 'vernon' ),
            'attributes' => [
                'readonly' => 'readonly',
                'disabled' => 'disabled',
            ],
            'save_field'  => false,
            'column' => [
                'position' => 2,
                'name' => esc_html__( 'Created On', 'vernon' ),
            ],
        ] );

        $job_complete_details->add_field( [
            'name' => esc_html__( 'Completed On', 'vernon' ),
            'id'   => $prefix . 'completed_read_only',
            'type' => 'text_datetime_timestamp',
            'desc'    => esc_html__( 'The date and time the job was completed, whether successfully or failing, in ISO-8601 UTC form.', 'vernon' ),
            'attributes' => [
                'readonly' => 'readonly',
                'disabled' => 'disabled',
            ],
            'save_field'  => false,
            'column' => [
                'position' => 2,
                'name' => esc_html__( 'Completed On', 'vernon' ),
            ],
        ] );

        $job_complete_details->add_field( [
            'name'    => esc_html__( 'Status', 'vernon' ),
            'id'      => $prefix . 'status_read_only',
            'type'    => 'radio_inline',
            'desc'    => esc_html__( 'Current status of the job.', 'vernon' ),
            'options' => array(
                'in_progress'   => esc_html__( 'In Progress', 'vernon' ),
                'transcribed'   => esc_html__( 'Transcribed', 'vernon' ),
                'failed'       => esc_html__( 'Failed', 'vernon' ),
            ),
            'save_field'  => false,
            'attributes' => [
                'readonly' => 'readonly',
                'disabled' => 'disabled',
            ],
            'column' => [
                'position' => 2,
                'name' => esc_html__( 'Status', 'vernon' ),
            ],
        ] );

    }

    /**
     * Adds a Settings page to Transcripts post type.
     *
     * @since 1.0.0
     */
    function add_transcript_settings_page() {

        // Registers settings page menu item and form.
        $settings_page = new_cmb2_box( [
            'id'           => 'vernon_transcript_settings',
            'title'        => esc_html__( 'Settings', 'vernon' ),
            'object_types' => [ 'options-page' ],
            'option_key'   => 'vernon_transcript_settings',
            'parent_slug'  => 'edit.php?post_type=vernon-transcript',
        ] );

        // Adds fields to the settings page.
        $settings_page->add_field( array(
            'name'    => esc_html__( 'REV.AI Access Token', 'vernon' ),
            'id'      => 'vernon_transcript_rev_token',
            'type'    => 'text',
            'attributes' => [
                'type' => 'password',
            ],
        ) );

        $settings_page->add_field( array(
            'name'    => esc_html__( 'ShareFile Access Token', 'vernon' ),
            'id'      => 'vernon_transcript_sharefile_token',
            'type'    => 'text',
            'attributes' => [
                'type' => 'password',
            ],
        ) );

    }
}

/**
 * Initializes the class.
 *
 * @since 1.0.0
 */
Custom_Fields::get_instance();
