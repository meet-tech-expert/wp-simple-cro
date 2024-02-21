<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Wp_Simple_Cro
 * @subpackage Wp_Simple_Cro/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Simple_Cro
 * @subpackage Wp_Simple_Cro/admin
 * @author     Rinkesh Gupta <gupta.rinkesh1990@gmail.com>
 */
class Wp_Simple_Cro_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-simple-cro-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 
			$this->plugin_name, 
			plugin_dir_url( __FILE__ ) . 'js/wp-simple-cro-admin.js', 
			array('jQuery'), 
			$this->version, 
			false 
		);
		wp_enqueue_script( 
			'simple-cro-gutenberg', 
			plugin_dir_url( __FILE__ ) . 'js/simple-cro.js', 
			array('wp-blocks', 'wp-editor'), 
			$this->version, 
			true 
		);
	
		// Localize script for translation
		wp_localize_script(
			$this->plugin_name,
			'simpleCroBlock', // Handle to access localized data in JavaScript
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ), // Example of localized data
			)
		);
	
		// Set script translations
		wp_set_script_translations( 
			'wp-simple-cro-admin', 
			'wp-simple-cro', 
			plugin_dir_path( __FILE__ ) . 'languages' 
		);
	}
}