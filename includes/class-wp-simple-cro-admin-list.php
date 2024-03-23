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
        $actions = array(
            'view'      => sprintf('<a href="?post_type=simple_cro&page=simple-cro-list&action=view&id=%s&data-id=%s">%s</a>', $item['id'],$item['scro_id'], __('View', $this->plugin_name)),
            'delete'    => sprintf('<a href="?post_type=simple_cro&page=simple-cro-list&action=delete&id=%s">%s</a>', $item['id'], __('Delete', $this->plugin_name)),
        );
        return sprintf('%1$s %2$s', $item['title'], $this->row_actions($actions));
    }

    // Checkbox column
    public function column_cb($item) {
        return sprintf('<input type="checkbox" name="id[]" value="%s" data-id="%s" />', $item['id'], $item['scro_id']);
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

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $per_page = 20;
        $simple_cro_table = $wpdb->prefix . SIMPLE_CRO_TABLE;
		$simple_cro_click_table = $wpdb->prefix . SIMPLE_CRO_CLICK_TABLE;

        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->process_bulk_action();

        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $simple_cro_table");

        $search_query = isset($_REQUEST['s']) ? sanitize_text_field($_REQUEST['s']) : '';
  
        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged'] - 1) * $per_page) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'created_at';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';
        $query = "SELECT sct.id, sct.scro_id, sct.title, sct.cat, sct.tag, sct.created_at, 
        SUM(CASE WHEN scc.block_variation = 'a' THEN 1 ELSE 0 END) AS count_a,
        SUM(CASE WHEN scc.block_variation = 'b' THEN 1 ELSE 0 END) AS count_b,
        CASE WHEN SUM(CASE WHEN scc.block_variation = 'a' THEN 1 ELSE 0 END) > SUM(CASE WHEN scc.block_variation = 'b' THEN 1 ELSE 0 END) THEN sct.block1_title ELSE sct.block2_title END AS winning_block_title FROM $simple_cro_table AS sct INNER JOIN $simple_cro_click_table AS scc ON sct.id = scc.cro_id ";

        if (!empty($search_query)) {
            $query .= $wpdb->prepare("WHERE (title LIKE '%%%s%%' OR cat LIKE '%%%s%%' OR tag LIKE '%%%s%%')", $search_query, $search_query, $search_query);
        }

        $query .= " GROUP BY sct.id ORDER BY $orderby $order LIMIT $per_page OFFSET $paged";

        $this->items = $wpdb->get_results($query, ARRAY_A);
       
        $this->set_pagination_args(array(
            'total_items'   => $total_items,
            'per_page'      => $per_page,
            'total_pages'   => ceil($total_items / $per_page)
        ));        
    }
    //view form
    public function display_view_form() {
        if (isset($_GET['id']) && $_GET['id'] !== '' && isset($_GET['data-id'])) {
            $cro_id = $_GET['id'];
    
            global $wpdb;
            $simple_cro_table = $wpdb->prefix . SIMPLE_CRO_TABLE;
            $simple_cro_click_table = $wpdb->prefix . SIMPLE_CRO_CLICK_TABLE;
    
            $query = $wpdb->prepare("SELECT * FROM $simple_cro_table WHERE id = %d", $cro_id);
            $cro_data = $wpdb->get_row($query, ARRAY_A);
    
            wp_enqueue_style('simple-cro-admin-list', plugin_dir_url(__FILE__) . '../admin/css/wp-simple-cro-admin-list.css');
            wp_enqueue_script('simple-cro-admin-list', plugin_dir_url(__FILE__) . '../admin/js/wp-simple-cro-admin-list.js');
            if ($cro_data) {
                $croId = $cro_data['id'];
                $blockAClicks = $wpdb->get_row("SELECT count(*) AS total FROM $simple_cro_click_table WHERE cro_id IN ($croId) AND block_variation = 'a'", ARRAY_A);
                $blockBClicks = $wpdb->get_row("SELECT count(*) AS total FROM $simple_cro_click_table WHERE cro_id IN ($croId) AND block_variation = 'b'", ARRAY_A);
                $blockAConversion = number_format(($blockAClicks['total']/$cro_data['block1_display'])*100,2);
                $blockBConversion = number_format(($blockBClicks['total']/$cro_data['block2_display'])*100,2);
                echo "<script>var blocksData= ".json_encode(array('blockA' => array('display'=> $cro_data['block1_display'], 'conversion' => $blockAConversion), 'blockB' => array('display'=> $cro_data['block2_display'], 'conversion' => $blockBConversion ) ))."</script>";
                $post_content = get_post_field('post_content', $cro_data['post_id'], 'display');
                // Load the HTML content into a DOMDocument
                $dom = new DOMDocument();
                $dom->loadHTML($post_content);
                $xpath = new DOMXPath($dom);    
                // Find all div elements with class "scro-wrapper"
                $scro_wrappers = $xpath->query('//div[contains(@class, "scro-wrapper")]');
                // Initialize blocks array
                $blocks = array('block_a' => '', 'block_b' => '');
    
                foreach ($scro_wrappers as $scro_wrapper) {

                    $scro_id = $scro_wrapper->getAttribute('data-scro-id');
                    $data_id = isset($_GET['data-id']) ? $_GET['data-id'] : '';
                    $blocks = array();
    
                    if ($scro_id == $data_id) {

                        $inner_blocks = $xpath->query('.//div[contains(@class, "scro-inner-blocks")]/child::*', $scro_wrapper);    
                        if ($inner_blocks->length > 0) {

                            foreach ($inner_blocks as $inner_block) {

                                $html_content = $dom->saveHTML($inner_block);
                                if (empty($blocks['block_a'])) {
                                    $blocks['block_a'] = $html_content;
                                } else {
                                    $blocks['block_b'] = $html_content;
                                }
                            }

                        } else {
                            echo 'Inner blocks not found.';
                        }
                        break;
                    }
                }
                ?>
                <div class="wrap simple-cro-form">
                    <h1 class="wp-heading-inline"><?php _e('Edit Simple CRO Test', $this->plugin_name)?></h1>
                    <form method="get" action="">
                        <input type="text" name="item_title" data-id="<?php echo esc_attr($cro_data['scro_id']) ?>" value="<?php echo esc_attr($cro_data['title']); ?>">
                    </form>
                    <div class="nav-tab-wrapper">
                        <a href="#live-results" class="nav-tab active"><?php _e('Live Results', $this->plugin_name)?></a>
                        <a href="#settings" class="nav-tab"><?php _e('Settings', $this->plugin_name)?></a>
                    </div>
                    <div id="live-results" class="tab-content active">
                        <?php require_once(plugin_dir_path(__FILE__) . '../admin/partials/simple-cro-cpt-view-live-result.php'); ?>
                    </div>
                    <div id="settings" class="tab-content">
                        <?php require_once(plugin_dir_path(__FILE__) . '../admin/partials/simple-cro-cpt-view-setting.php'); ?>
                    </div>
                </div>
                <?php
            } else {
                echo '<p>' . __('Invalid item ID.', $this->plugin_name) . '</p>';
            }
        }
    } 
}