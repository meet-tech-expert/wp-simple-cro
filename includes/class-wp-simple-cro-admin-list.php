<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Wp_Simple_CRO_Admin_List extends WP_List_Table {

    private $plugin_name;

    // Constructor
    public function __construct($plugin_name) {

        $this->plugin_name = $plugin_name;
        
        parent::__construct(array(
            'singular'  => 'CRO Block',
            'plural'    => 'CRO Blocks',
        ));
    }

    // Default column renderer
    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'title':
                return isset($item['title']) ? $item['title'] : '';
            case 'categories':
                return isset($item['cat']) ? $item['cat'] : '';
            case 'tags':
                return isset($item['tag']) ? $item['tag'] : '';
            case 'date':
                return isset($item['created_at']) ? "Published <br/>".date('Y/m/d h:i a', strtotime($item['created_at'])) : '';
            case 'winning':
                return isset($item['winning_block_title']) ? $item['winning_block_title'] : '';                               
            default:
                return isset($item[$column_name]) ? $item[$column_name] : '';
        }
    }

    // Rendering column with title and actions
    public function column_title($item) {
        //print_r($item);
        $actions = array(
            'view'      => sprintf('<a href="?post_type=simple_cro&page=simple-cro-list&id=%s">%s</a>', $item['id'], __('View', $this->plugin_name)),
            'delete'    => sprintf('<a href="?post_type=simple_cro&page=simple-cro-list&action=delete&id=%s">%s</a>', $item['id'], __('Delete', $this->plugin_name)),
        );
        return sprintf('%1$s %2$s', $item['title'], $this->row_actions($actions));
    }

    // Checkbox column
    public function column_cb($item) {
        return sprintf('<input type="checkbox" name="id[]" value="%s" />', $item['id']);
    }

    // Define columns for the table
    public function get_columns() {
        return array(
            'cb'            => '<input type="checkbox" />',
            'title'         => __('Title', $this->plugin_name),
            'categories'    => __('Categories', $this->plugin_name),
            'tags'          => __('Tags', $this->plugin_name),
            'date'          => __('Date', $this->plugin_name),
            'winning'       => __('Winning', $this->plugin_name),
        );
    }

    // Define sortable columns
    public function get_sortable_columns() {
        return array(
            'title'         => array('title',   true),
            'date'          => array('created_at',    true),
            'winning'       => array('winning', false),
        );
    }

    // Return array of bulk actions
    public function get_bulk_actions() {
        $actions = array(
            'delete' => __('Delete permanently'),
        );
        return $actions;
    }

    // Process bulk actions
    public function process_bulk_action() {
        global $wpdb;
        $simple_cro_table       = $wpdb->prefix . SIMPLE_CRO_TABLE;
		$simple_cro_click_table = $wpdb->prefix . SIMPLE_CRO_CLICK_TABLE;
        //var_dump($this->current_action());
        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $simple_cro_table WHERE id IN($ids)");
                $wpdb->query("DELETE FROM $simple_cro_click_table WHERE cro_id IN($ids)");
            }
        }
    }
        // Prepare items for the table
    public function prepare_items() {
        global $wpdb;

        // Define column headers, hidden columns, and sortable columns
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $per_page = 20; // constant, how much records will be shown per page
        $simple_cro_table = $wpdb->prefix . SIMPLE_CRO_TABLE;
		$simple_cro_click_table = $wpdb->prefix . SIMPLE_CRO_CLICK_TABLE;

        // Set column headers
        $this->_column_headers = array($columns, $hidden, $sortable);

        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        // will be used in pagination settings
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $simple_cro_table");

        // Handle search query   
        $search_query = isset($_REQUEST['s']) ? sanitize_text_field($_REQUEST['s']) : '';
  

        // prepare query params, as usual current page, order by and order direction
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged'] - 1) * $per_page) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'created_at';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';
        $query = "SELECT sct.id, sct.title, sct.cat, sct.tag, sct.created_at, SUM(CASE WHEN scc.block_variation = 'a' THEN 1 ELSE 0 END) AS count_a,SUM(CASE WHEN scc.block_variation = 'b' THEN 1 ELSE 0 END) AS count_b,CASE WHEN SUM(CASE WHEN scc.block_variation = 'a' THEN 1 ELSE 0 END) > SUM(CASE WHEN scc.block_variation = 'b' THEN 1 ELSE 0 END) THEN sct.block1_title ELSE sct.block2_title END AS winning_block_title FROM $simple_cro_table AS sct INNER JOIN $simple_cro_click_table AS scc ON sct.id = scc.cro_id ";

        // Add search conditions to the query if a search query is provided
        if (!empty($search_query)) {
            $query .= $wpdb->prepare("WHERE (title LIKE '%%%s%%' OR cat LIKE '%%%s%%' OR tag LIKE '%%%s%%')", $search_query, $search_query, $search_query);
        }
        // Append sorting and pagination clauses to the query
        $query .= " GROUP BY sct.id ORDER BY $orderby $order LIMIT $per_page OFFSET $paged";

        // Execute the query
        $this->items = $wpdb->get_results($query, ARRAY_A);
       
        //print_r($this->items);
         // [REQUIRED] configure pagination
         $this->set_pagination_args(array(
            'total_items'   => $total_items, // total items defined above
            'per_page'      => $per_page, // per page constant defined at top of method
            'total_pages'   => ceil($total_items / $per_page) // calculate pages count
        ));        
    }
    public function display_view_form() {
        if (isset($_GET['id'])) {
            $item_id = $_GET['id'];
            
            global $wpdb;
            $simple_cro_table = $wpdb->prefix . SIMPLE_CRO_TABLE;
            $simple_cro_click_table = $wpdb->prefix . SIMPLE_CRO_CLICK_TABLE;
            $query = $wpdb->prepare("SELECT * FROM $simple_cro_table WHERE id = %d", $item_id);
            $item_data = $wpdb->get_row($query, ARRAY_A);
            ?>
            <div class="wrap">
                <h1 class="wp-heading-inline"><?php _e('Edit Simple CRO Test', $this->plugin_name)?></h1>
                <form method="get" action="">
                    <!-- Display item title with full width -->
                    <input type="text" name="item_title" value="<?php echo esc_attr($item_data['title']); ?>" style="width: 100%; background-color: #fff; font-size: 22px;" readonly>
                   
                </form>
                
                <!-- Tabs for Live Results and Settings -->
                <div class="nav-tab-wrapper">
                    <a href="#live-results" class="nav-tab"><?php _e('Live Results', $this->plugin_name)?></a>
                    <a href="#settings" class="nav-tab"><?php _e('Settings', $this->plugin_name)?></a>
                </div>
    
                <!-- Tab content with white background -->
                <div id="live-results" class="tab-content" style="background-color: #fff;">
                    <!-- Live Results content goes here -->
                </div>
    
                <div id="settings" class="tab-content" style="background-color: #fff;">
                    <label for="cro_test_id"><?php _e('CRO Test ID:', $this->plugin_name) ?></label>
                    <input type="text" id="cro_test_id" value="<?php echo $item_data['id']; ?>" readonly>

                    <label for="cro_categories"><?php _e('CRO Categories:', $this->plugin_name) ?></label>
                    <input type="text" id="cro_categories" value="<?php echo $item_data['cat']; ?>" readonly>

                    <label for="cro_tags"><?php _e('CRO Tags:', $this->plugin_name) ?></label>
                    <input type="text" id="cro_tags" value="<?php echo $item_data['tag']; ?>" readonly>

                    <label for="block_a_title"><?php _e('Block A Title:', $this->plugin_name) ?></label>
                    <input type="text" id="block_a_title" value="<?php echo $item_data['block1_title']; ?>" readonly>

                    <label for="block_a_id"><?php _e('Block A ID:', $this->plugin_name) ?></label>
                    <input type="text" id="block_a_id" value="<?php echo $item_data['block1_id']; ?>" readonly>

                    <label for="block_b_title"><?php _e('Block B Title:', $this->plugin_name) ?></label>
                    <input type="text" id="block_b_title" value="<?php echo $item_data['block2_title']; ?>" readonly>

                    <label for="block_b_id"><?php _e('Block B ID:', $this->plugin_name) ?></label>
                    <input type="text" id="block_b_id" value="<?php echo $item_data['block2_id']; ?>" readonly>
                </div>
                <style>
                label {
                    display: block; 
                    margin-bottom: 5px; 
                    font-weight: bold; 
                }
                input[type="text"] {
                    width: 50%; 
                    padding: 5px; 
                    margin-bottom: 10px; 
                    border: 1px solid #ccc; 
                    border-radius: 4px; 
                    box-sizing: border-box; 
                }
                .tab-content {
                    padding: 20px; 
                }
                </style>
            </div> 
                <?php
        } else {
            echo '<p>' . __('Invalid item ID.', $this->plugin_name) . '</p>';
        }          
    }   
}