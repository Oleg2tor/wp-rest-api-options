<?php
/*
Plugin Name: WP REST API Options
Description: WP REST API Options - returns list of options and their values.
Author: Oleg Kostin
Version: 1.0.1
Author URI: http://pmr.io
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// WP API v1.
include_once 'includes/wp-api-options-v1.php';

// WP API v2.
include_once 'includes/wp-api-options-v2.php';

/**
 * Check if WP REST API is active
 **/
if ( in_array( 'rest-api/plugin.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) :


	function wp_rest_options_init() {

		/**
		 * Init JSON REST API options routes.
		 *
		 * @since 1.0.0
		 */

		if ( !defined( 'JSON_API_VERSION' ) && !in_array( 'json-rest-api/plugin.php', get_option( 'active_plugins' ) ) ) {
			$class = new WP_REST_Options();
			add_filter( 'rest_api_init', array( $class, 'register_routes' ) );
		} else {
			$class = new WP_JSON_Options();
			add_filter( 'json_endpoints', array( $class, 'register_routes' ) );
		}
	}

	add_action( 'init', 'wp_rest_options_init' );


endif;