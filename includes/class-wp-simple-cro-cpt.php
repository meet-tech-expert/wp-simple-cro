<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Wp_Simple_CRO_CPT {

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
        add_action( 'init',  array( $this,'register_simple_cro_cpt') );	
	}

    // Register the custom post type
    
    public function register_simple_cro_cpt() {

        $labels = array(
            'name'                  => esc_html__( 'Simple CRO', $this->plugin_name ),
            'singular_name'         => esc_html__( 'Simple CRO', $this->plugin_name ),
            'menu_name'             => esc_html__( 'Simple CRO', $this->plugin_name ),
            'name_admin_bar'        => esc_html__( 'Simple CRO', $this->plugin_name ),
            'archives'              => esc_html__( 'Simple CRO Archives', $this->plugin_name ),
            'attributes'            => esc_html__( 'Simple CRO Attributes', $this->plugin_name ),
            'parent_item_colon'     => esc_html__( 'Parent Simple CRO:', $this->plugin_name ),
            'all_items'             => esc_html__( 'All Simple CRO ', $this->plugin_name ),
            'add_new_item'          => esc_html__( 'Add New Simple CRO ', $this->plugin_name ),
            'add_new'               => esc_html__( 'Add New', $this->plugin_name ),
            'new_item'              => esc_html__( 'New Simple CRO ', $this->plugin_name ),
            'edit_item'             => esc_html__( 'Edit Simple CRO ', $this->plugin_name ),
            'update_item'           => esc_html__( 'Update Simple CRO ', $this->plugin_name ),
            'view_item'             => esc_html__( 'View Simple CRO ', $this->plugin_name ),
            'view_items'            => esc_html__( 'View Simple CRO ', $this->plugin_name ),
            'search_items'          => esc_html__( 'Search Simple CRO ', $this->plugin_name ),
            'not_found'             => esc_html__( 'Not found', $this->plugin_name ),
            'not_found_in_trash'    => esc_html__( 'Not found in Trash', $this->plugin_name ),
            'featured_image'        => esc_html__( 'Featured Image', $this->plugin_name ),
            'set_featured_image'    => esc_html__( 'Set featured image', $this->plugin_name ),
            'remove_featured_image' => esc_html__( 'Remove featured image', $this->plugin_name ),
            'use_featured_image'    => esc_html__( 'Use as featured image', $this->plugin_name ),
            'insert_into_item'      => esc_html__( 'Insert into Simple CRO ', $this->plugin_name ),
            'uploaded_to_this_item' => esc_html__( 'Uploaded to this Simple CRO ', $this->plugin_name ),
            'items_list'            => esc_html__( 'Simple CRO list', $this->plugin_name ),
            'items_list_navigation' => esc_html__( 'Simple CRO list navigation', $this->plugin_name ),
            'filter_items_list'     => esc_html__( 'Filter Simple CRO list', $this->plugin_name ),
        );
        $args = array(
            'label'                 => esc_html__( 'Simple CRO ', $this->plugin_name ),
            'description'           => esc_html__( 'Simple CRO ', $this->plugin_name ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'custom-fields' ),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-layout',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
        );

        register_post_type( SIMPLE_CRO_CPT, $args );
    }

}
