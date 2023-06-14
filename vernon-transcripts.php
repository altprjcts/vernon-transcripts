<?php
   /*
   Plugin Name: Vernon Transcripts
   Plugin URI: https://www.vernoncourtreporters.com
   Description: Web application to process audio files
   Version: 1.0
   Author: Vernon Court Reporters, LLC
   Author URI: https://www.vernoncourtreporters.com
   */

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
  echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
  exit;
}

// Define the constant for wp_get_environment_type().
//define( 'WP_ENVIRONMENT_TYPE', ( 'https://staging.vernoncourtreporters.com' === get_site_url() ) ? 'staging' : 'production' );

// Check if CMB2 plugin is installed and active.
register_activation_hook( __FILE__, 'vernon_transcripts_plugin_activate' );
function vernon_transcripts_plugin_activate() {
    // Require parent plugin
    if ( ! is_plugin_active( 'cmb2/init.php' ) and current_user_can( 'activate_plugins' ) ) {
        // Stop activation redirect and show error
        wp_die('Sorry, but this plugin requires the CMB2 plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
    }
}

require_once plugin_dir_path( __FILE__ ) . 'inc/cmb2.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/custom-post-types.php';

?>