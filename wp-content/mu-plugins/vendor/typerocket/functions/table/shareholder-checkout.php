<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

// Table: shareholder - جدول سفارشات

// Plugin menu callback function
function shareholder_checkout_list_table_init() {

      // Creating an instance
      $ShareholderCheckoutTable = new Shareholder_Order_List_Table();
      $user = get_userdata( $_GET['shareholder_id'] );
      
      echo "<div class='wrap'>";
            echo "<h1 class='wp-heading-inline'>پرداخت‌ها</h1>";
            echo "<div><span>لیست پرداخت‌های مربوط به کاربر " . $user->display_name . " </span></div>";
            echo "<hr class='wp-header-end'>";
            include plugin_dir_path(__FILE__) . '../menu/shareholder/checkout.php';
            // include plugin_dir_path(__FILE__) . '../menu/shareholder/action.php';
            // Prepare table
            $ShareholderCheckoutTable->prepare_items();
            //echo "<form method='get'>";
                  //echo "<input type='hidden' name='page' value='wc-shareholder' />";
                  //$ShareholderCheckoutTable->search_box('جستجو', 'search_id');
                  // Display table
                  // if( isset($_GET['shareholder_id']) ) {
                        $ShareholderCheckoutTable->display();
                  // } else {
                  //       echo 'لطفاً کاربر دارای سهام فروش مد نظر خود را انتخاب نمایید...';
                  // }
            //echo "</form>";
      echo "</div>";

}
shareholder_checkout_list_table_init();

// Loading table class
if( !class_exists('WP_List_Table') ) {

      require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');

}

// Extending class
class Shareholder_Order_List_Table extends WP_List_Table {

      private $shareholder_data;

      private function get_shareholder_checkout_data($search = "") {

            $where = [
                  [
                        'column'    => 'user_id',
                        'operator'  => '=',
                        'value'     => $_GET['shareholder_id']
                  ],
            ];

            $shareholder_data =  tr_query()->table('se7en_shareholder_checkout')->setIdColumn('ID');
            $shareholder_data =  $shareholder_data->findAll()->where($where)->orderBy('ID', 'DESC')->distinct()->get();
            $this->shareholder_data = json_decode($shareholder_data);
            return $this->shareholder_data;
            
      }

      // Define table columns
      function get_columns() {

            $columns = array(
                  // 'cb'            => '<input type="checkbox" />',
                  'ID'              => 'شناسه',
                  'order_item_id'   => 'اطلاعات آخرین سفارش',
                  'order_date'      => 'تاریخ آخرین سفارش',
                  // 'user_id'         => 'اطلاعات سهامدار',
                  'date_created'    => 'تاریخ تسویه',
                  'wallet'          => 'کیف پول',
                  'status'          => 'وضعیت',
            );
            return $columns;

      }

      // Bind table with columns, data and all
      function prepare_items() {

            if ( isset( $_GET['page'] ) && isset( $_GET['s'] ) ) {
                  $this->shareholder_data = $this->get_shareholder_checkout_data($_GET['s']);
            } else {
                  $this->shareholder_data = $this->get_shareholder_checkout_data();
            }

            $columns = $this->get_columns();
            $hidden = array();
            $sortable = $this->get_sortable_columns();
            $this->_column_headers = array($columns, $hidden, $sortable);

            /* pagination */
            $per_page = $this->get_items_per_page('shareholder_per_page', 20);
            $current_page = $this->get_pagenum();
            $total_items = count($this->shareholder_data);

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

            $this->shareholder_data = array_slice($this->shareholder_data, (($current_page - 1) * $per_page), $per_page);

            $this->set_pagination_args(array(
                  'total_items' => $total_items, // total number of items
                  'per_page'    => $per_page // items to show on a page
            ));

            // $this->shareholder_data = $this->get_shareholder_checkout_data();
            // $this->items = $this->shareholder_data;
            $this->items = $this->shareholder_data;

      }

      // bind data with column
      function column_default($item, $column_name) {

            switch ($column_name) {

                  case 'ID':
                        return $item->ID;
                  case 'order_item_id':
                        $where = [
                              [
                                    'column'    => 'order_item_id',
                                    'operator'  => '=',
                                    'value'     => $item->order_item_id
                              ],
                        ];
                        $order_data =  tr_query()->table('se7en_wc_order_product_lookup')->findAll()->where($where)->select('order_item_id','order_id','product_id','variation_id','date_created')->get();
                        $order_data = json_decode($order_data);

                        if( $order_data[0]->variation_id == 0 ) {
                              $product = tr_query()->table('se7en_posts')->findById($order_data[0]->product_id)->select('ID', 'post_title')->get();
                              return 
                                    'شناسه سفارش: ' . "<a href='" . admin_url( '/post.php?post=' ) . $order_data[0]->order_id . "&action=edit' target='_blank'>" . $order_data[0]->order_id . "#</a>"
                                    . ' | '
                                    . 'شناسه ردیف: ' . $order_data[0]->order_item_id
                                    . "<br>"
                                    . "<a href='" . admin_url( '/post.php?post=' ) . $order_data[0]->product_id . "&action=edit' target='_blank'>" . $product['post_title'] . "</a>"
                                    . "<br>"
                                    . 'نوع محصول: ساده'
                                    . ' | '
                                    . 'شناسه محصول: '
                                    . $order_data[0]->product_id;
                        } else {
                              $product = tr_query()->table('se7en_posts')->findById($order_data[0]->variation_id)->select('ID', 'post_title')->get();
                              return 
                                    'شناسه سفارش: ' . "<a href='" . admin_url( '/post.php?post=' ) . $order_data[0]->order_id . "&action=edit' target='_blank'>" . $order_data[0]->order_id . "#</a>"
                                    . ' | '
                                    . 'شناسه ردیف: ' . $order_data[0]->order_item_id
                                    . "<br>"
                                    . "<a href='" . admin_url( '/post.php?post=' ) . $order_data[0]->product_id . "&action=edit' target='_blank'>" . $product['post_title'] . "</a>"
                                    . "<br>"
                                    . 'نوع محصول: متغیر'
                                    . ' | '
                                    . 'شناسه محصول: '
                                    . $order_data[0]->product_id
                                    . ' | '
                                    . 'شناسه متغیر: '
                                    . $order_data[0]->variation_id;
                        }
                  case 'date_created':
                        return
                              'تاریخ شمسی: ' . parsidate("Y-m-d h:i:s", $item->date_created, "per")
                              . "<br>"
                              . 'تاریخ میلادی: ' . $item->date_created;
                  case 'order_date':
                        return
                              'تاریخ شمسی: ' . parsidate("Y-m-d h:i:s", $item->order_date, "per")
                              . "<br>"
                              . 'تاریخ میلادی: ' . $item->order_date;
                  case 'user_id':
                        $user = get_userdata( $item->user_id );
                        return
                              "<a href='" . admin_url( 'user-edit.php?user_id=' ) . $user->ID . "' target='_blank'>" . $user->display_name . "</a>";
                  case 'wallet':
                        return $item->wallet_amount;
                  case 'status':
                        if( $item->status ) {
                              return "<div class='shareholder-status'>تسویه شده</div>";
                        } else {
                              return "<div class='shareholder-status'>تسویه نشده</div>";
                        }
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