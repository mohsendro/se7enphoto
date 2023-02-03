<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

// get saved screen meta value
add_filter('set-screen-option', 'shareholder_table_set_option', 10, 3);

function shareholder_table_set_option($status, $option, $value) {

      return $value;

}

// Plugin menu callback function
function shareholder_employees_list_init() {

      // Creating an instance
      $empTable = new Employees_List_Table();

      echo "<div class='wrap'>";
            echo "<h1>سهامداران</h1>";
            // Prepare table
            $empTable->prepare_items();

            echo "<form method='get'>";
                  echo "<input type='hidden' name='page' value='employees_list_table' />";
                  $empTable->search_box('جستجو', 'search_id');
                  // Display table
                  $empTable->display();
            echo "</form>";
      echo "</div>";

}



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                                                                                                //
//                                        Start Table List                                                        //
//                                                                                                                //
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Loading table class
if( !class_exists('WP_List_Table') ) {

      require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

}

// Extending class
class Employees_List_Table extends WP_List_Table {

      private $users_data;

      private function get_users_data($search = "") {

            global $wpdb;

            if (!empty($search)) {
                  return $wpdb->get_results(
                        "SELECT ID,user_login,user_email,display_name from {$wpdb->prefix}users WHERE ID Like '%{$search}%' OR user_login Like '%{$search}%' OR user_email Like '%{$search}%' OR display_name Like '%{$search}%'",
                        ARRAY_A
                  );
            } else {
                  return $wpdb->get_results(
                        "SELECT ID,user_login,user_email,display_name from {$wpdb->prefix}users",
                        ARRAY_A
                  );
            }

      }

      // Define table columns
      function get_columns() {

            $columns = array(
                  'cb'            => '<input type="checkbox" />',
                  'ID'          => 'شناسه',
                  'product'     => 'محصول',
                  'customer'    => 'خریدار',
                  'sharehoder'  => 'سهم',
                  'order'       => 'سفارش',
                  'order_date'  => 'تاریخ سفارش',
            );
            return $columns;

      }

      // Bind table with columns, data and all
      function prepare_items() {

            if (isset($_GET['page']) && isset($_GET['s'])) {
                  $this->users_data = $this->get_users_data($_GET['s']);
            } else {
                  $this->users_data = $this->get_users_data();
            }

            $columns = $this->get_columns();
            $hidden = array();
            $sortable = $this->get_sortable_columns();
            $this->_column_headers = array($columns, $hidden, $sortable);

            /* pagination */
            $per_page = $this->get_items_per_page('employees_per_page', 20);
            $current_page = $this->get_pagenum();
            $total_items = count($this->users_data);

            // edit
            if (isset($_GET['action']) && $_GET['page'] == "employees_list_table" && $_GET['action'] == "edit") {
                  $empID = intval($_GET['employee']);

                  //... do operation
            }

            // delete
            if (isset($_GET['action']) && $_GET['page'] == "employees_list_table" && $_GET['action'] == "delete") {
                  $empID = intval($_GET['employee']);

                  //... do operation
            }

            // bulk action
            if (isset($_GET['action']) && $_GET['page'] == "employees_list_table" && $_GET['action'] == "delete_all") {
                  $empIDs = $_GET['user'];
                  
                  //... do operation
            }

            if (isset($_GET['action']) && $_GET['page'] == "employees_list_table" && $_GET['action'] == "draft_all") {
                  $empIDs = $_GET['user'];
                  
                  //... do operation
            }

            $this->users_data = array_slice($this->users_data, (($current_page - 1) * $per_page), $per_page);

            $this->set_pagination_args(array(
                  'total_items' => $total_items, // total number of items
                  'per_page'    => $per_page // items to show on a page
            ));

            usort($this->users_data, array(&$this, 'usort_reorder'));

            $this->items = $this->users_data;
      }

      // bind data with column
      function column_default($item, $column_name)
      {
            switch ($column_name) {
                  case 'ID':
                  case 'user_login':
                  case 'user_email':
                        return $item[$column_name];
                  case 'display_name':
                        return ucwords($item[$column_name]);
                  default:
                        return print_r($item, true); //Show the whole array for troubleshooting purposes
            }
      }

      // To show checkbox with each row
      function column_cb($item)
      {
            return sprintf(
                  '<input type="checkbox" name="user[]" value="%s" />',
                  $item['ID']
            );
      }

      // Add sorting to columns
      protected function get_sortable_columns() {

            $sortable_columns = array(
                  'ID'           => array('ID', false),
                  'product'      => array('product', false),
                  'customer'     => array('customer', true),
                  'sharehoder'   => array('sharehoder', true),
                  'order'        => array('order', true),
                  'order_date'   => array('order_date', true),
            );
            return $sortable_columns;

      }

      // Sorting function
      function usort_reorder($a, $b)
      {
            // If no sort, default to user_login
            $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'user_login';
            // If no order, default to asc
            $order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';
            // Determine sort order
            $result = strcmp($a[$orderby], $b[$orderby]);
            // Send final sort direction to usort
            return ($order === 'asc') ? $result : -$result;
      }

      // Adding action buttons to column
      function column_user_login($item)
      {
            $actions = array(
                  'edit'      => sprintf('<a href="?page=%s&action=%s&employee=%s">Edit</a>', $_REQUEST['page'], 'edit', $item['ID']),
                  'delete'    => sprintf('<a href="?page=%s&action=%s&employee=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['ID']),
            );

            return sprintf('%1$s %2$s', $item['user_login'], $this->row_actions($actions));
      }

      // To show bulk action dropdown
      function get_bulk_actions()
      {
            $actions = array(
                'edit_all'      => "ویرایش",
                'draft_all'     => "پیشنویس",
                'delete_all'    => 'انتقال به زباله‌دان',
            );
            return $actions;
      }

}