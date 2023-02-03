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

      // private $users_data;
      private $table_data;

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

            if ( isset($_GET['page'] ) && isset( $_GET['s']) ) {
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

            // $this->items = $this->users_data;


            $line_items = tr_query()->table('se7en_wc_order_product_lookup');
            $this->table_data = $line_items->findAll()->orderBy('order_item_id', 'DESC')->groupBy(['variation_id','product_id','order_id'])->get();
            $this->items = $this->table_data;

      }

      // bind data with column
      function column_default($item, $column_name) {
            switch ($column_name) {

                  case 'ID':
                        return $item->order_item_id;
                  case 'product':
                        if( $item->variation_id == 0 ) {
                              $product = tr_query()->table('se7en_posts')->findById($item->product_id)->select('ID', 'post_title')->get();
                              return $product['post_title'] . "<br>" . 'محصول ساده' . ' | ' . 'شناسه محصول: ' . $item->product_id . ' | ' . $item->variation_id;
                        } else {
                              $product = tr_query()->table('se7en_posts')->findById($item->variation_id)->select('ID', 'post_title')->get();
                              return $product['post_title'] . "<br>" . 'محصول متغیر' . ' | ' . 'شناسه محصول: ' . $item->product_id . ' | ' . 'شناسه متغیر: '  . $item->variation_id;
                        }
                  case 'customer':
                        // $customer = tr_query()->table('se7en_users')->findById($item->customer_id)->select('display_name')->get();
                        return $item->customer_id;
                  case 'sharehoder':
                        $user = wp_get_current_user();
                        switch ( $user->roles[0] ) {
                              case 'administrator':
                                    $user_amount = 'product_shareholder_admin_amount';
                                    break;
                                    
                              case 'photographer':
                                    $user_amount = 'product_shareholder_photographer_amount';
                                    break;

                              case 'graphicer':
                                    $user_amount = 'product_shareholder_graphicer_amount';
                                    break;

                              default:
                                    $user_amount = '';
                                    break;
                        }
                        $where = [
                              [
                                    'column' => 'meta_key',
                                    'operator' => '=',
                                    'value' => $user_amount
                              ],
                              // 'AND',
                              // [
                              //       'column' => 'meta_value',
                              //       'operator' => '=',
                              //       'value' => get_current_user_id()
                              // ]
                        ];
                        $shareholder = tr_query()->table('se7en_postmeta')->setIdColumn('post_id')->findByID($item->product_id)->where($where)->select('meta_value')->get();
                        if( $shareholder['meta_value'] ) {
                              $user_shareholder = ($item->product_gross_revenue * $shareholder['meta_value']) / 100;
                              $pct = $shareholder['meta_value'];
                              return $user_shareholder . "<br>" . $pct . '% | ' . 'ذی نفع: ' . $user->display_name . ' | ' . $user->ID;
                        }
                  case 'order':
                        return $item->order_id . ' | ' . 'مبلغ سفارش: ' . $item->product_gross_revenue; 
                  case 'order_date':
                        return $item->date_created;
                  default:
                        return print_r($item, true); //Show the whole array for troubleshooting purposes

            }

      }

      // To show checkbox with each row
      function column_cb($item) {

            return sprintf(
                  '<input type="checkbox" name="order[]" value="%s" />',
                  $item->ID
            );

      }

      // Add sorting to columns
      protected function get_sortable_columns() {

            $sortable_columns = array(
                  'ID'           => array('ID', false),
                  'product'      => array('product_id', false),
                  'customer'     => array('customer_id', true),
                  'sharehoder'   => array('sharehoder_price', true),
                  'order'        => array('order_price', true),
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