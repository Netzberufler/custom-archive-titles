<?php
/*
Plugin Name: Custom Archive Titles
Plugin URI: https://themezee.com/plugins/custom-archive-titles/
Description: This plugin allows you to add a nice and elegant breadcrumb navigation. Breadcrumbs make it easy for the user to navigate up and down the hierarchy of your website and are good for SEO.
Author: ThemeZee
Author URI: https://themezee.com/
Version: 1.0
Text Domain: themezee-custom-archive-titles
Domain Path: /languages/
License: GPL v3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

ThemeZee Custom Archive Titles
Copyright(C) 2016, ThemeZee.com - support@themezee.com

*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }


/**
 * Main ThemeZee_Custom_Archive_Titles Class
 *
 * @package ThemeZee Custom Archive Titles
 */
class ThemeZee_Custom_Archive_Titles {

	/**
	 * Call all Functions to setup the Plugin
	 *
	 * @uses ThemeZee_Custom_Archive_Titles::constants() Setup the constants needed
	 * @uses ThemeZee_Custom_Archive_Titles::includes() Include the required files
	 * @uses ThemeZee_Custom_Archive_Titles::setup_actions() Setup the hooks and actions
	 * @return void
	 */
	static function setup() {

		// Setup Constants.
		self::constants();

		// Setup Translation.
		add_action( 'plugins_loaded', array( __CLASS__, 'translation' ) );

		// Include Files.
		self::includes();

		// Setup Action Hooks.
		self::setup_actions();

	}

	/**
	 * Setup plugin constants
	 *
	 * @return void
	 */
	static function constants() {

		// Define Plugin Name.
		define( 'TZCAT_NAME', 'ThemeZee Custom Archive Titles' );

		// Define Version Number.
		define( 'TZCAT_VERSION', '1.0' );

		// Plugin Folder Path.
		define( 'TZCAT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

		// Plugin Folder URL.
		define( 'TZCAT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

		// Plugin Root File.
		define( 'TZCAT_PLUGIN_FILE', __FILE__ );

	}

	/**
	 * Load Translation File
	 *
	 * @return void
	 */
	static function translation() {

		load_plugin_textdomain( 'themezee-custom-archive-titles', false, dirname( plugin_basename( TZCAT_PLUGIN_FILE ) ) . '/languages/' );

	}

	/**
	 * Include required files
	 *
	 * @return void
	 */
	static function includes() {

		// Include Settings Classes.
		require_once TZCAT_PLUGIN_DIR . '/includes/class-tzcat-settings.php';
		require_once TZCAT_PLUGIN_DIR . '/includes/class-tzcat-settings-page.php';

	}

	/**
	 * Setup Action Hooks
	 *
	 * @see https://codex.wordpress.org/Function_Reference/add_action WordPress Codex
	 * @return void
	 */
	static function setup_actions() {

		// Change Archive Titles based on user settings.
		add_filter( 'get_the_archive_title', array( __CLASS__, 'custom_archive_titles' ) );

		// Add Settings link to Plugin actions.
		add_filter( 'plugin_action_links_' . plugin_basename( TZCAT_PLUGIN_FILE ), array( __CLASS__, 'plugin_action_links' ) );

	}

	/**
	* Filter the default archive title.
	*
	* @param string $title Archive title.
	* @return string $title
	*/
	static function custom_archive_titles( $title ) {

		// Get Settings.
		$instance = TZCAT_Settings::instance();
		$options = $instance->get_all();

		// Change Archive Titles.
		if ( is_category() && __( 'Category: %s' ) !== $options['category_title'] ) {

			$title = sprintf( esc_html( $options['category_title'] ), single_cat_title( '', false ) );

		} elseif ( is_tag() ) {

			$title = single_tag_title( '', false );

		} elseif ( is_author() && 'Author: %s' !== $options['author_title'] ) {

			$title = '<span class="vcard">' . get_the_author() . '</span>';

		} elseif ( is_post_type_archive() ) {

			$title = post_type_archive_title( '', false );

		} elseif ( is_tax() ) {

			$title = single_term_title( '', false );

		}

		return $title;
	}

	/**
	 * Add Settings link to the plugin actions
	 *
	 * @return array $actions Plugin action links
	 */
	static function plugin_action_links( $actions ) {

		$settings_link = array( 'settings' => sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php?page=themezee-custom-archive-titles' ), __( 'Settings', 'themezee-custom-archive-titles' ) ) );

		return array_merge( $settings_link, $actions );
	}
}

// Run Plugin.
ThemeZee_Custom_Archive_Titles::setup();
