<?php
/**
 * Load scripts & styles
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Foodica
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Foodica_Enqueue_Scripts_Admin' ) ) {

	/**
	 * foodica_Enqueue_Scripts initial setup
	 *
	 * @since 1.0.0
	 */
	class Foodica_Enqueue_Scripts_Admin {

		/**
		 * Constructor
		 */
		public function __construct() {

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		}

		/**
		 * Enqueue scripts and styles for all admin pages.
		 */
		public function admin_scripts($hook) {

            // Versioned by file time so admin style changes are never served from the browser cache.
            $admin_css = get_template_directory() . '/assets/admin/css/admin.css';
            wp_enqueue_style( 'foodica-admin', get_template_directory_uri() . '/assets/admin/css/admin.css', array(), file_exists( $admin_css ) ? filemtime( $admin_css ) : wp_get_theme()->get( 'Version' ) );

            if ( 'appearance_page_page-foodica' != $hook ) {

               wp_enqueue_script("jquery-ui");
               wp_enqueue_script("jquery-ui-tabs");

            }
		}


	}

	new Foodica_Enqueue_Scripts_Admin();
}
