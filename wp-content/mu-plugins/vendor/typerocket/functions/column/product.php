<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

// Column: product - ستون جدول محصول

// Add Column
function columns_product_shareholder($columns) {

    $columns['shareholder_product_data'] = 'اطلاعات مالی';
    return $columns;

}
add_filter('manage_product_posts_columns', 'columns_product_shareholder');

// Add Data To Column
function columns_product_shareholder_data($column, $post_id) {

    if ($column == 'shareholder_product_data') {

		$status = get_post_meta( $post_id, 'product_shareholder_status' );

        if( $status[0] != 'true' ) {
            echo 'وضعیت: غیرفعال';
            return;
        }

        $admin_user         = get_post_meta( $post_id, 'product_shareholder_admin_user' );
        $photographer_user  = get_post_meta( $post_id, 'product_shareholder_photographer_user' );
        $graphicer_user     = get_post_meta( $post_id, 'product_shareholder_graphicer_user' );
        $admin_amount         = get_post_meta( $post_id, 'product_shareholder_admin_amount' );
        $photographer_amount  = get_post_meta( $post_id, 'product_shareholder_photographer_amount' );
        $graphicer_amount     = get_post_meta( $post_id, 'product_shareholder_graphicer_amount' );
        $admin_info = get_user_by('id', $admin_user[0]);
        $photographer_info = get_user_by('id', $photographer_user[0]);
        $graphicer_info = get_user_by('id', $graphicer_user[0]);

        echo 'سهام مدیر: ' . "<a href='" . admin_url() . 'user-edit.php?user_id=' . $admin_info->ID . "' tergrt='_blank'>" . $admin_info->user_login . "</a>" . ' (' . $admin_amount[0] . '%) ';
        echo "<br>";
        echo 'سهام عکاس: ' . "<a href='" . admin_url() . 'user-edit.php?user_id=' . $photographer_info->ID . "' tergrt='_blank'>" . $photographer_info->user_login . "</a>" . ' (' . $photographer_amount[0] . '%) ';
        echo "<br>";
        echo 'سهام گرافیست: ' . "<a href='" . admin_url() . 'user-edit.php?user_id=' . $graphicer_info->ID . "' tergrt='_blank'>" . $graphicer_info->user_login . "</a>" . ' (' . $graphicer_amount[0] . '%) ';

	}

}
add_action('manage_product_posts_custom_column' , 'columns_product_shareholder_data', 10, 2);