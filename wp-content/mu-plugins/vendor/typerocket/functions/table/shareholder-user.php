<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

// Table: shareholder - جدول سهامداران

// Plugin menu callback function
function shareholder_user_list_table_init() {

      // Creating an instance
      $ShareholderUserTable = new Shareholder_User_List_Table();

      echo "<div class='wrap'>";
            echo "<h1 class='wp-heading-inline'>سهامداران</h1>";
            echo "<hr class='wp-header-end'>";
            // Prepare table
            $ShareholderUserTable->prepare_items();
            //echo "<form method='get'>";
                  //echo "<input type='hidden' name='page' value='wc-shareholder' />";
                  //$ShareholderUserTable->search_box('جستجو', 'search_id');
                  // Display table
                  // if( isset($_GET['shareholder_id']) ) {
                        $ShareholderUserTable->display();
                  // } else {
                  //       echo 'لطفاً کاربر دارای سهام فروش مد نظر خود را انتخاب نمایید...';
                  // }
            //echo "</form>";
      echo "</div>";

}
shareholder_user_list_table_init();

// Loading table class
if( !class_exists('WP_List_Table') ) {

      require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

}

// Extending class
class Shareholder_User_List_Table extends WP_List_Table {

      private $user_data;

      private function get_shareholder_user_data($search = "") {

            $users = tr_query()->table('se7en_users');
            $this->user_data = $users->findAll()->orderBy('ID', 'DESC')->get();
            $this->user_data = json_decode($this->user_data);
            return $this->user_data;
            
      }

      // Define table columns
      function get_columns() {

            $columns = array(
                  // 'cb'            => '<input type="checkbox" />',
                  'ID'        => 'شناسه',
                  'nicename'  => 'نام کاربری',
                  'name'      => 'نام',
                  'email'     => 'ایمیل',
                  'role'      => 'نقش',
                  'more'      => 'جزئیات',
            );
            return $columns;

      }

      // Bind table with columns, data and all
      function prepare_items() {

            if ( isset( $_GET['page'] ) && isset( $_GET['s'] ) ) {
                  $this->user_data = $this->get_shareholder_user_data($_GET['s']);
            } else {
                  $this->user_data = $this->get_shareholder_user_data();
            }

            $columns = $this->get_columns();
            $hidden = array();
            $sortable = $this->get_sortable_columns();
            $this->_column_headers = array($columns, $hidden, $sortable);

            /* pagination */
            $per_page = $this->get_items_per_page('shareholder_per_page', 20);
            $current_page = $this->get_pagenum();
            $total_items = count($this->user_data);

            // edit
            // if (isset($_GET['action']) && $_GET['page'] == "wc-shareholder" && $_GET['action'] == "edit") {
            //       $empID = intval($_GET['employee']);

            //       //... do operation
            // }

            // delete
            // if (isset($_GET['action']) && $_GET['page'] == "wc-shareholder" && $_GET['action'] == "delete") {
            //       $empID = intval($_GET['employee']);

            //       //... do operation
            // }

            // bulk action
            // if (isset($_GET['action']) && $_GET['page'] == "wc-shareholder" && $_GET['action'] == "delete_all") {
            //       $empIDs = $_GET['user'];
                  
            //       //... do operation
            // }

            // if (isset($_GET['action']) && $_GET['page'] == "wc-shareholder" && $_GET['action'] == "draft_all") {
            //       $empIDs = $_GET['user'];
                  
            //       //... do operation
            // }

            $this->user_data = array_slice($this->user_data, (($current_page - 1) * $per_page), $per_page);

            $this->set_pagination_args(array(
                  'total_items' => $total_items, // total number of items
                  'per_page'    => $per_page // items to show on a page
            ));

            // $this->user_data = $this->get_shareholder_user_data();
            // $this->items = $this->user_data;
            $this->items = $this->user_data;

      }

      // bind data with column
      function column_default($item, $column_name) {

            switch ($column_name) {

                  case 'ID':
                        return $item->ID;
                  case 'nicename':
                        return $item->user_nicename;
                  case 'name':
                        return $item->display_name;
                  case 'email':
                        return $item->user_email;
                  case 'role':
                        $user_meta = get_userdata($item->ID);
                        $user_roles = $user_meta->roles;
                        $roles = implode( ", ", translate_user_role($user_roles) );
                        return $roles;
                  case 'more':
                        return 
                              "<a href='" . esc_url( add_query_arg('shareholder_id', $item->ID) ) . "'>جزئیات سهام</a>"
                              . ' | ' .
                              "<a href='" . admin_url( 'user-edit.php?user_id=' ) . $item->ID . "' target='_blank'>جزئیات کاربر</a>";
                  default:
                        return print_r($item, true); //Show the whole array for troubleshooting purposes

            }

      }

      // To show checkbox with each row
      // function column_cb($item) {

      //       return sprintf(
      //             '<input type="checkbox" name="user[]" value="%s" />',
      //             $item->ID
      //       );

      // }

      // Add sorting to columns
      // protected function get_sortable_columns() {

      //       // $sortable_columns = array(
      //       //       'ID'        => array('ID', false),
      //       //       'nicename'  => array('nicename', false),
      //       //       'email'     => array('email', false),
      //       //       'role'      => array('role', false),
      //       // );
      //       // return $sortable_columns;

      // }

      // Adding action buttons to column
      // function column_ID($item) {

      //       $actions = array(
      //             'edit'      => sprintf('<a href="?page=%s&action=%s&employee=%s">Edit</a>', $_REQUEST['page'], 'edit', $item->ID),
      //             'delete'    => sprintf('<a href="?page=%s&action=%s&employee=%s">Delete</a>', $_REQUEST['page'], 'delete', $item->ID),
      //       );

      //       return sprintf('%1$s %2$s', $item->ID, $this->row_actions($actions));

      // }

      // To show bulk action dropdown
      // function get_bulk_actions() {

      //       $actions = array(
      //           'edit_all'      => "ویرایش",
      //           'draft_all'     => "پیشنویس",
      //           'delete_all'    => 'انتقال به زباله‌دان',
      //       );
      //       return $actions;

      // }

}