<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://example.com
 * @since             1.0.0
 * @package           Wp_Simple_Cro
 *
 * @wordpress-plugin
 * Plugin Name:       WP Simple CRO
 * Plugin URI:        https://example.com
 * Description:       The purpose of this plugin is to allow testing of different Gutenberg blocks on a page.  A user creating or editing a page can create a new Gutenberg block called Simple CRO.
 * Version:           1.0.0
 * Author:            Rinkesh Gupta
 * Author URI:        https://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-simple-cro
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_SIMPLE_CRO_VERSION', '1.0.0' );
//Custom Post Type Constant
define( 'SIMPLE_CRO_CPT', 'simple_cro' );
define( 'SIMPLE_CRO_DB', 'simple_cro_block' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-simple-cro-activator.php
 */
function activate_wp_simple_cro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-simple-cro-activator.php';
	Wp_Simple_Cro_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-simple-cro-deactivator.php
 */
function deactivate_wp_simple_cro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-simple-cro-deactivator.php';
	Wp_Simple_Cro_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_simple_cro' );
register_deactivation_hook( __FILE__, 'deactivate_wp_simple_cro' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-simple-cro.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_simple_cro() {

	$plugin = new Wp_Simple_Cro();
	$plugin->run();

}
run_wp_simple_cro();
