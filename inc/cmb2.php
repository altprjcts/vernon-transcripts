<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Transcripts
add_action( 'cmb2_admin_init', 'vernon_transcripts_metabox' );
function vernon_transcripts_metabox() {
	$prefix = 'vernon_transcripts_';

	$vernon_transcript_job_request_details = new_cmb2_box(
		array(
			'id'           => $prefix . 'metabox',
			'title'        => esc_html__( 'Transcript Job Request Details', 'vernon' ),
			'object_types' => array( 'vernon-transcripts' ),
		)
	);

	$vernon_transcript_job_request_details->add_field( array(
	    'name'    => 'Audio File',
	    'desc'    => 'Upload an audio file or enter an URL.',
	    'id'	  => $prefix . 'audio',
	    'type'    => 'file',
	    'options' => array(
	        'url' => true,
	    ),
	    'text'    => array(
	        'add_upload_file_text' => 'Add File'
	    ),
	    // query_args are passed to wp.media's library query.
	    'query_args' => array(
	        'type' => 'audio/mpeg',
	        // Or only allow gif, jpg, or png images
	        // 'type' => array(
	        //     'image/gif',
	        //     'image/jpeg',
	        //     'image/png',
	        // ),
	    ),
	) );

	$vernon_transcript_job_request_details->add_field( array(
	    'name'             => 'Language',
	    'id'      		   => $prefix . 'lang',
	    'type'             => 'select',
	    'show_option_none' => false,
	    'default'          => 'en',
	    'options'          => array(
	        'en'  => __( 'English', 'vernon' ), 
	    ),
	) );

	$vernon_transcript_job_request_details->add_field( array(
	    'name'    => 'Verbatim',
	    'id'      => $prefix . 'verbatim',
	    'desc'	  => 'Configures the transcriber to transcribe every syllable. This will include all false starts and disfluencies in the transcript.',
	    'type'    => 'radio_inline',
	    'options' => array(
	        'true' 	=> __( 'True', 'vernon' ),
	        'false' => __( 'False', 'vernon' ),
	    ),
	    'default' => 'true',
	) );

	$vernon_transcript_job_request_details->add_field( array(
	    'name'    => 'Skip Diarization',
	    'id'      => $prefix . 'skip_diarization',
	    'desc'	  => 'Specify if speaker diarization will be skipped by the speech engine',
	    'type'    => 'radio_inline',
	    'options' => array(
	        'true' 	=> __( 'True', 'vernon' ),
	        'false' => __( 'False', 'vernon' ),
	    ),
	    'default' => 'false',
	) );

	$vernon_transcript_job_request_details->add_field( array(
	    'name'    => 'Skip postprocessing',
	    'id'      => $prefix . 'skip_postprocessing',
	    'desc'	  => 'Only available for English and Spanish languages. User-supplied preference on whether to skip post-processing operations such as inverse text normalization (ITN), casing and punctuation.',
	    'type'    => 'radio_inline',
	    'options' => array(
	        'true' 	=> __( 'True', 'vernon' ),
	        'false' => __( 'False', 'vernon' ),
	    ),
	    'default' => 'false',
	) );

	$vernon_transcript_job_request_details->add_field( array(
	    'name'    => 'Skip Punctuation',
	    'id'      => $prefix . 'skip_punctuation',
	    'desc'	  => 'Specify if "punct" type elements will be skipped by the speech engine. For JSON outputs, this includes removing spaces. For text outputs, words will still be delimited by a space',
	    'type'    => 'radio_inline',
	    'options' => array(
	        'true' 	=> __( 'True', 'vernon' ),
	        'false' => __( 'False', 'vernon' ),
	    ),
	    'default' => 'false',
	) );

	$vernon_transcript_job_request_details->add_field( array(
	    'name'    => 'Remove Disfluencies',
	    'id'      => $prefix . 'remove_disfluencies',
	    'desc'	  => 'Currently we only define disfluencies as \'ums\' and \'uhs\'. When set to true, disfluencies will not appear in the transcript. This option also removes atmospherics if the remove_atmospherics is not set.',
	    'type'    => 'radio_inline',
	    'options' => array(
	        'true' 	=> __( 'True', 'vernon' ),
	        'false' => __( 'False', 'vernon' ),
	    ),
	    'default' => 'false',
	) );

	$vernon_transcript_job_request_details->add_field( array(
	    'name'    => 'Remove Atmospherics',
	    'id'      => $prefix . 'remove_atmospherics',
	    'desc'	  => 'We define many atmospherics such <laugh>, <affirmative> etc. When set to true, atmospherics will not appear in the transcript.',
	    'type'    => 'radio_inline',
	    'options' => array(
	        'true' 	=> __( 'True', 'vernon' ),
	        'false' => __( 'False', 'vernon' ),
	    ),
	    'default' => 'false',
	) );	

	$vernon_transcript_job_request_details->add_field( array(
	    'name'    => 'Filter Profanity',
	    'id'      => $prefix . 'filter_profanity',
	    'desc'	  => 'Enabling this option will filter for approx. 600 profanities, which cover most use cases. If a transcribed word matches a word on this list, then all the characters of that word will be replaced by asterisks except for the first and last character.',
	    'type'    => 'radio_inline',
	    'options' => array(
	        'true' 	=> __( 'True', 'vernon' ),
	        'false' => __( 'False', 'vernon' ),
	    ),
	    'default' => 'false',
	) );

	$group_field_id = $vernon_transcript_job_request_details->add_field(
		array(
			'id'          => $prefix . 'speaker_repeat_group',
			'type'        => 'group',
			'desc'		  => 'Used to identify speakers when postprocessing transcript in Word Document.',
			'options'     => array(
				'group_title'   => __( 'Speaker {#}', 'vernon' ),
				'add_button'    => __( 'Add Another Speaker', 'vernon' ),
				'remove_button' => __( 'Remove Speaker', 'vernon' ),
				'sortable'      => true,
				'closed'        => true,
			),
		)
	);

	// IDs for group's fields only need to be unique for the group. Prefix is not needed.
	$vernon_transcript_job_request_details->add_group_field(
		$group_field_id,
		array(
			'name' => 'Name',
			'id'   => 'name',
			'type' => 'text',
		)
	);



	// Transcript Completed Job Details
	$vernon_transcript_job_complete_details = new_cmb2_box(
		array(
			'id'           => $prefix . 'job_complete_metabox',
			'title'        => esc_html__( 'Transcript Completed Job Details', 'vernon' ),
			'object_types' => array( 'vernon-transcripts' ),
			'context'	=>	'side',
		)
	);

	// duration_seconds
	$vernon_transcript_job_complete_details->add_field( array(
	    'name'    => 'Audio Duration',
	    'id'      => $prefix . 'audio_length_read_only',
	    'type'    => 'text',
	    'default' => '00:00:00',
	    'desc'	  => 'Duration of the file in seconds. Null if the file could not be retrieved or there was not a valid media file.',
	    'attributes' => array(
	        'readonly' => 'readonly',
	        'disabled' => 'disabled',
	    ),
	    'save_field'  => false,
	    'column' => array(
            'position' => 2,
            'name' => 'Duration',
        ),
	) );

	$vernon_transcript_job_complete_details->add_field( array(
	    'name' => 'Created On',
	    'id'   => $prefix . 'created_read_only',
	    'type' => 'text_datetime_timestamp',
	    'default' => time(),
	    'desc'	  => 'The date and time the job was created in ISO-8601 UTC form.',
	    'attributes' => array(
	        'readonly' => 'readonly',
	        'disabled' => 'disabled',
	    ),
	    'save_field'  => false,
	    'column' => array(
            'position' => 2,
            'name' => 'Created On',
        ),
	) );

	$vernon_transcript_job_complete_details->add_field( array(
	    'name' => 'Completed On',
	    'id'   => $prefix . 'completed_read_only',
	    'type' => 'text_datetime_timestamp',
	    'desc'	  => 'The date and time the job was completed, whether successfully or failing, in ISO-8601 UTC form.',
	    'default' => time(),
	    'attributes' => array(
	        'readonly' => 'readonly',
	        'disabled' => 'disabled',
	    ),
	    'save_field'  => false,
	    'column' => array(
            'position' => 2,
            'name' => 'Completed On',
        ),
	) );

	$vernon_transcript_job_complete_details->add_field( array(
	    'name'    => 'Status',
	    'id'      => $prefix . 'status_read_only',
	    'type'    => 'radio_inline',
	    'desc'	  => 'Current status of the job.',
	    'options' => array(
	        'in_progress' 	=> __( 'In Progress', 'vernon' ),
	        'transcribed'   => __( 'Transcribed', 'vernon' ),
	        'failed'     	=> __( 'Failed', 'vernon' ),
	    ),
	    'save_field'  => false,
	    'default' => 'in_progress',
	    'attributes' => array(
	        'readonly' => 'readonly',
	        'disabled' => 'disabled',
	    ),
	    'column' => array(
            'position' => 2,
            'name' => 'Status',
        ),
	) );

}

/**
 * Hook in and register a submenu options page for the Page post-type menu.
 */
function vernon_transcripts_register_options_submenu_for_page_post_type() {

	/**
	 * Registers options page menu item and form.
	 */
	$vernon_transcripts_settings = new_cmb2_box( array(
		'id'           => 'vernon_transcripts_options_submenu_page',
		'title'        => esc_html__( 'Settings', 'cmb2' ),
		'object_types' => array( 'options-page' ),

		/*
		 * The following parameters are specific to the options-page box
		 * Several of these parameters are passed along to add_menu_page()/add_submenu_page().
		 */

		'option_key'      => 'vernon_transcripts_page_options', // The option key and admin menu page slug.
		// 'icon_url'        => '', // Menu icon. Only applicable if 'parent_slug' is left empty.
		// 'menu_title'      => esc_html__( 'Options', 'cmb2' ), // Falls back to 'title' (above).
		'parent_slug'     => 'edit.php?post_type=vernon-transcripts', // Make options page a submenu item of the themes menu.
		// 'capability'      => 'manage_options', // Cap required to view options-page.
		// 'position'        => 1, // Menu position. Only applicable if 'parent_slug' is left empty.
		// 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
		// 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
		// 'save_button'     => esc_html__( 'Save Theme Options', 'cmb2' ), // The text for the options-page save button. Defaults to 'Save'.
		// 'disable_settings_errors' => true, // On settings pages (not options-general.php sub-pages), allows disabling.
		// 'message_cb'      => 'vernon_transcripts_options_page_message_callback',
	) );

	$vernon_transcripts_settings->add_field( array(
	    'name'    => 'REV.AI Access Token',
	    'id'      => 'vernon_transcripts_rev_token',
	    'type'    => 'text',
	    'attributes' => array(
	        'type' => 'password',
	    ),
	) );

	$vernon_transcripts_settings->add_field( array(
	    'name'    => 'ShareFile Access Token',
	    'id'      => 'vernon_transcripts_sharefile_token',
	    'type'    => 'text',
	    'attributes' => array(
	        'type' => 'password',
	    ),
	) );

}
add_action( 'cmb2_admin_init', 'vernon_transcripts_register_options_submenu_for_page_post_type' );
