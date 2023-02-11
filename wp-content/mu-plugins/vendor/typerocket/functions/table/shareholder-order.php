<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

// Table: shareholder - جدول سفارشات

// Plugin menu callback function
function shareholder_order_list_table_init() {

      // Creating an instance
      $ShareholderOrderTable = new Shareholder_Order_List_Table();
      $user = get_userdata( $_GET['shareholder_id'] );
      
      echo "<div class='wrap'>";
            echo "<h1 class='wp-heading-inline'>سفارشات</h1>";
            echo "<div><span>لیست سفارشات مربوط به کاربر " . $user->display_name . " </span></div>";
            echo "<hr class='wp-header-end'>";
            // Prepare table
            $ShareholderOrderTable->prepare_items();
            //echo "<form method='get'>";
                  //echo "<input type='hidden' name='page' value='wc-shareholder' />";
                  //$ShareholderOrderTable->search_box('جستجو', 'search_id');
                  // Display table
                  // if( isset($_GET['shareholder_id']) ) {
                        $ShareholderOrderTable->display();
                  // } else {
                  //       echo 'لطفاً کاربر دارای سهام فروش مد نظر خود را انتخاب نمایید...';
                  // }
            //echo "</form>";
      echo "</div>";

}
shareholder_order_list_table_init();

// Loading table class
if( !class_exists('WP_List_Table') ) {

      require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

}

// Extending class
class Shareholder_Order_List_Table extends WP_List_Table {

      private $order_data;

      private function get_shareholder_order_data($search = "") {

            $user = get_userdata( $_GET['shareholder_id'] );
            switch ( $user->roles[0] ) {
                  case 'administrator':
                        $user_id = 'product_shareholder_admin_user';
                        break;
                        
                  case 'photographer':
                        $user_id = 'product_shareholder_photographer_user';
                        break;
            
                  case 'graphicer':
                        $user_id = 'product_shareholder_graphicer_user';
                        break;
            
                  default:
                        $user_id = '';
                        break;
            }
            $where_join = [
                  [
                        'column' => 'se7en_postmeta.meta_key',
                        'operator' => '=',
                        'value' => $user_id
                  ],
                  'AND',
                  [
                        'column' => 'se7en_postmeta.meta_value',
                        'operator' => '=',
                        'value' => $_GET['shareholder_id']
                  ]
            ];

            $line_items = tr_query()->table('se7en_wc_order_product_lookup')->setIdColumn('product_id');
            $line_items = $line_items->findAll()->orderBy('order_item_id', 'DESC')->groupBy(['variation_id','product_id','order_id']);
            $line_items = $line_items->join('se7en_postmeta', 'se7en_postmeta.post_id', '=', 'se7en_wc_order_product_lookup.product_id', 'LEFT')->where($where_join);
            $line_items = $line_items->distinct()->get();
            $this->order_data = json_decode($line_items);
            return $this->order_data;

            // $line_items = tr_query()->table('se7en_wc_order_product_lookup');
            // $this->order_data = $line_items->findAll()->orderBy('order_item_id', 'DESC')->groupBy(['variation_id','product_id','order_id'])->get();
            // $this->order_data = json_decode($this->order_data);
            // return $this->order_data;
            
      }

      // Define table columns
      function get_columns() {

            $columns = array(
                  // 'cb'            => '<input type="checkbox" />',
                  'ID'          => 'شناسه',
                  'product'     => 'اطلاعات محصول',
                  'customer'    => 'اطلاعات خریدار',
                  'sharehoder'  => 'اطلاعات سهام',
                  'order'       => 'اطلاعات سفارش',
            );
            return $columns;

      }

      // Bind table with columns, data and all
      function prepare_items() {

            if ( isset( $_GET['page'] ) && isset( $_GET['s'] ) ) {
                  $this->order_data = $this->get_shareholder_order_data($_GET['s']);
            } else {
                  $this->order_data = $this->get_shareholder_order_data();
            }

            $columns = $this->get_columns();
            $hidden = array();
            $sortable = $this->get_sortable_columns();
            $this->_column_headers = array($columns, $hidden, $sortable);

            /* pagination */
            $per_page = $this->get_items_per_page('shareholder_per_page', 20);
            $current_page = $this->get_pagenum();
            $total_items = count($this->order_data);

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

            $this->order_data = array_slice($this->order_data, (($current_page - 1) * $per_page), $per_page);

            $this->set_pagination_args(array(
                  'total_items' => $total_items, // total number of items
                  'per_page'    => $per_page // items to show on a page
            ));

            // $this->order_data = $this->get_shareholder_order_data();
            // $this->items = $this->order_data;
            $this->items = $this->order_data;

      }

      // bind data with column
      function column_default($item, $column_name) {

            switch ($column_name) {

                  case 'ID':
                        return $item->order_item_id;
                  case 'product':
                        if( $item->variation_id == 0 ) {
                              $product = tr_query()->table('se7en_posts')->findById($item->product_id)->select('ID', 'post_title')->get();
                              return 
                                    "<a href='" . admin_url( '/post.php?post=' ) . $item->product_id . "&action=edit' target='_blank'>" . $product['post_title'] . "</a>"
                                    . "<br>"
                                    . 'نوع محصول: ساده'
                                    . ' | '
                                    . 'شناسه محصول: '
                                    . $item->product_id;
                        } else {
                              $product = tr_query()->table('se7en_posts')->findById($item->variation_id)->select('ID', 'post_title')->get();
                              return 
                                    "<a href='" . admin_url( '/post.php?post=' ) . $item->product_id . "&action=edit' target='_blank'>" . $product['post_title'] . "</a>"
                                    . "<br>"
                                    . 'نوع محصول: متغیر'
                                    . ' | '
                                    . 'شناسه محصول: '
                                    . $item->product_id
                                    . ' | '
                                    . 'شناسه متغیر: '
                                    . $item->variation_id;
                        }
                  case 'customer':
                        $customer = tr_query()->table('se7en_users')->findById($item->customer_id)->select('ID', 'display_name', 'user_email')->get();
                        return 
                              "<a href='" . admin_url( 'user-edit.php?user_id=' ) . $customer['ID'] . "' target='_blank'>" . $customer['display_name'] . "</a>"
                              . "<br>"
                              . 'ایمیل: ' . $customer['user_email'];
                  case 'sharehoder':
                        $user = get_userdata( $_GET['shareholder_id'] );
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
                        ];
                        $shareholder = tr_query()->table('se7en_postmeta')->setIdColumn('post_id')->findByID($item->product_id)->where($where)->select('meta_value')->get();
                        if( $shareholder['meta_value'] ) {
                              $user_shareholder = ($item->product_gross_revenue * $shareholder['meta_value']) / 100;
                              $pct = $shareholder['meta_value'];
                              return 
                                    'مبلغ سود: ' . "<strong>" . $user_shareholder . "</strong>"
                                    . ' | '
                                    . 'درصد سود: ' . $pct . '%'
                                    . "<br>"
                                    . 'ذی نفع: ' . "<a href='" . admin_url( 'user-edit.php?user_id=' ) . $user->ID . "' target='_blank'>" . $user->display_name . "</a>";
                        }
                  case 'order':
                        return 
                              'شناسه سفارش: ' . "<a href='" . admin_url( '/post.php?post=' ) . $item->order_id . "&action=edit' target='_blank'>" . $item->order_id . "#</a>"
                              . ' | '
                              . 'مبلغ سفارش: ' . "<strong>" . $item->product_gross_revenue . "</strong>"
                              . "<br>"
                              .  'تاریخ شمسی سفارش: ' . parsidate("Y-m-d h:i:s", $item->date_created, "per")
                              . "<br>"
                              . 'تاریخ میلادی سفارش: ' . $item->date_created;
                  default:
                        return print_r($item, true); //Show the whole array for troubleshooting purposes

            }

      }

      // To show checkbox with each row
      // function column_cb($item) {

      //       // return sprintf(
      //       //       '<input type="checkbox" name="order[]" value="%s" />',
      //       //       $item->ID
      //       // );

      // }

      // Add sorting to columns
      // protected function get_sortable_columns() {

      //       // $sortable_columns = array(
      //       //       'ID'           => array('ID', false),
      //       //       'product'      => array('product_id', false),
      //       //       'customer'     => array('customer_id', false),
      //       //       'sharehoder'   => array('sharehoder_price', false),
      //       //       'order'        => array('order_price', false),
      //       //       'order_date'   => array('order_date', false),
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