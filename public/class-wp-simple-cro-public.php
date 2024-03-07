<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Wp_Simple_Cro
 * @subpackage Wp_Simple_Cro/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Simple_Cro
 * @subpackage Wp_Simple_Cro/public
 * @author     Rinkesh Gupta <gupta.rinkesh1990@gmail.com>
 */
class Wp_Simple_Cro_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/simple-cro-front.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-simple-cro-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'simple-cro-gutenberg', plugin_dir_url( __FILE__ ) . 'js/simple-cro-front.js', array( 'jquery' ), $this->version, false );	
		wp_localize_script( 'simple-cro-gutenberg', 'simpleCroFrontBlock', array(
			'ajax_url' => admin_url('admin-ajax.php'),
		));
	}	
	function handle_cro_click() {
		global $wpdb;
	
		// Get data sent via AJAX
		$croBlockId = $_POST['croBlockId'];
		$croTitle = $_POST['croTitle'];
		$croCat = $_POST['croCat'];
		$croTag = $_POST['croTag'];
		$croUniqueId = $_POST['croUniqueId'];
		$croBlock1Id = $_POST['croBlock1Id'];
		$croBlock2Id = $_POST['croBlock2Id'];
		$croBlock1Title = $_POST['croBlock1Title'];
		$croBlock2Title = $_POST['croBlock2Title'];
		$croBlock1Percentage = $_POST['croBlock1Percentage'];
		$croBlock2Percentage = $_POST['croBlock2Percentage'];
		$deviceType = $_POST['deviceType'];
		$pagePath = $_POST['pagePath'];

		// Sanitize data before inserting into the database
		$data = array(
			'scro_id' => intval($croBlockId),
			'scro_unique_id' => intval($croUniqueId),
			'scro_title' => sanitize_text_field($croTitle),
			'scro_category' => sanitize_text_field($croCat),
			'scro_tag' => sanitize_text_field($croTag),
			'block1_percentage' => intval($croBlock1Percentage),
			'block2_percentage' => intval($croBlock2Percentage),
			'block1_id' => intval($croBlock1Id),
			'block1_title' => sanitize_text_field($croBlock1Title),
			'block2_id' => intval($croBlock2Id),
			'block2_title' => sanitize_text_field($croBlock2Title),
			'device_type' => sanitize_text_field($deviceType),
			'page_path' => esc_url_raw($pagePath)
		);


	
		// Insert data into the database table
		$table_name = $wpdb->prefix . 'simple_cro_blocks';
		$wpdb->insert($table_name, $data);
	
		// Send JSON response
		wp_send_json_success('Click handled successfully');
	
		// Always exit to prevent unwanted output
		exit;
	}
	
}
