<?php
/**
 * WP JSON API Options routes
 *
 * @package WP_JSON_Options
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WP_JSON_Options' ) ) :


	/**
	 * WP JSON Options class.
	 *
	 * WP API Options support for WP API v1.
	 *
	 * @package WP_JSON_Options
	 * @since 1.0.0
	 */
	class WP_JSON_Options {


		/**
		 * Register options routes for WP API.
		 *
		 * @since 1.0.0
		 * @param  array $routes Existing routes
		 * @return array Modified routes
		 */
		public function register_routes( $routes ) {

			// all options
			$routes['/options'] = array(
				array( array( $this, 'get_options' ), WP_JSON_Server::READABLE ),
			);

			// a specific option
			$routes['/options/(?P<id>\w+)'] = array(
				array( array( $this, 'get_option' ), WP_JSON_Server::READABLE ),
			);

			return $routes;
		}


		/**
		 * Get options.
		 *
		 * @since  1.0.0
		 * @return array All options
		 */
		public static function get_options() {

			$json_url = get_json_url() . '/options/';
			$default_whitelist_options = self::get_whitelist_options();

			$whitelist_options = array_merge(
				$default_whitelist_options['general'],
				$default_whitelist_options['discussion'],
				$default_whitelist_options['media'],
				$default_whitelist_options['reading'],
				$default_whitelist_options['writing'],
				$default_whitelist_options['misc'],
				$default_whitelist_options['privacy']
			);

			$i = 0;
			$json_options = array();
			foreach ($whitelist_options as $whitelist_option) :
				$json_options[ $whitelist_option ] = get_option($whitelist_option);

				$json_options['meta']['links']['collection'] = $json_url;
				$json_options['meta']['links']['self']       = $json_url . $whitelist_option;
				$i ++;
			endforeach;

			return $json_options;
		}


		/**
		 * Get a specified option.
		 *
		 * @since  1.0.0
		 * @param  int   $id ID of the option
		 * @return mixed Option data
		 */
		public static function get_option($id) {

			$json_url       = get_json_url() . '/options/';
			$default_whitelist_options = self::get_whitelist_options();

			$whitelist_options = array_merge(
				$default_whitelist_options['general'],
				$default_whitelist_options['discussion'],
				$default_whitelist_options['media'],
				$default_whitelist_options['reading'],
				$default_whitelist_options['writing'],
				$default_whitelist_options['misc'],
				$default_whitelist_options['privacy']
			);

			if (!in_array($id, $whitelist_options)) {
				return new WP_Error( 'wp_api_options_option_not_found', __( 'Not found' ), array( 'status' => 404 ) );
			}

			$json_option = array();
			$json_option[$id] = get_option($id);
			$json_option['meta']['links']['collection'] = $json_url;
			$json_option['meta']['links']['self']       = $json_url . $id;

			return $json_option;
		}

		/**
		 * Get options whitelist.
		 *
		 * @since  1.0.0
		 * @return array whitelist options
		 */

		public static function get_whitelist_options() {
			/**
			 * Filter the options white list.
			 *
			 * @since 2.7.0
			 *
			 * @param array White list options.
			 */
			$whitelist_options = array(
				'general' => array( 'blogname', 'blogdescription', 'gmt_offset', 'date_format', 'time_format', 'start_of_week', 'timezone_string', 'WPLANG' ),
				'discussion' => array( 'default_pingback_flag', 'default_ping_status', 'default_comment_status', 'comments_notify', 'moderation_notify', 'comment_moderation', 'require_name_email', 'comment_whitelist', 'comment_max_links', 'moderation_keys', 'blacklist_keys', 'show_avatars', 'avatar_rating', 'avatar_default', 'close_comments_for_old_posts', 'close_comments_days_old', 'thread_comments', 'thread_comments_depth', 'page_comments', 'comments_per_page', 'default_comments_page', 'comment_order', 'comment_registration' ),
				'media' => array( 'thumbnail_size_w', 'thumbnail_size_h', 'thumbnail_crop', 'medium_size_w', 'medium_size_h', 'medium_large_size_w', 'medium_large_size_h', 'large_size_w', 'large_size_h', 'image_default_size', 'image_default_align', 'image_default_link_type' ),
				'reading' => array( 'posts_per_page', 'posts_per_rss', 'rss_use_excerpt', 'show_on_front', 'page_on_front', 'page_for_posts', 'blog_public' ),
				'writing' => array( 'default_category', 'default_email_category', 'default_link_category', 'default_post_format' )
			);
			$whitelist_options['misc'] = $whitelist_options['options'] = $whitelist_options['privacy'] = array();

			$mail_options = array('mailserver_url', 'mailserver_port', 'mailserver_login', 'mailserver_pass');

			if ( ! in_array( get_option( 'blog_charset' ), array( 'utf8', 'utf-8', 'UTF8', 'UTF-8' ) ) )
				$whitelist_options['reading'][] = 'blog_charset';

			if ( get_site_option( 'initial_db_version' ) < 32453 ) {
				$whitelist_options['writing'][] = 'use_smilies';
				$whitelist_options['writing'][] = 'use_balanceTags';
			}

			if ( !is_multisite() ) {
				if ( !defined( 'WP_SITEURL' ) )
					$whitelist_options['general'][] = 'siteurl';
				if ( !defined( 'WP_HOME' ) )
					$whitelist_options['general'][] = 'home';

				$whitelist_options['general'][] = 'admin_email';
				$whitelist_options['general'][] = 'users_can_register';
				$whitelist_options['general'][] = 'default_role';

				$whitelist_options['writing'] = array_merge($whitelist_options['writing'], $mail_options);
				$whitelist_options['writing'][] = 'ping_sites';

				$whitelist_options['media'][] = 'uploads_use_yearmonth_folders';

				// If upload_url_path and upload_path are both default values, they're locked.
				if ( get_option( 'upload_url_path' ) || ( get_option('upload_path') != 'wp-content/uploads' && get_option('upload_path') ) ) {
					$whitelist_options['media'][] = 'upload_path';
					$whitelist_options['media'][] = 'upload_url_path';
				}
			} else {
				$whitelist_options['general'][] = 'new_admin_email';

				/**
				 * Filter whether the post-by-email functionality is enabled.
				 *
				 * @since 3.0.0
				 *
				 * @param bool $enabled Whether post-by-email configuration is enabled. Default true.
				 */
				if ( apply_filters( 'enable_post_by_email_configuration', true ) )
					$whitelist_options['writing'] = array_merge($whitelist_options['writing'], $mail_options);
			}

			$whitelist_options = apply_filters( 'whitelist_options', $whitelist_options );

			return $whitelist_options;
		}


	}


endif;
