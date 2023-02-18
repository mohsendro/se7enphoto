<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

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

$where_user = [ 
    [
        'column'   => 'se7en_postmeta.meta_key',
        'operator' => '=',
        'value'    => $user_id
    ],
    'AND',
    [
        'column'   => 'se7en_postmeta.meta_value',
        'operator' => '=',
        'value'    => $_GET['shareholder_id']
    ]
];
$where_amount = [
    [
        'column'   => 'meta_key',
        'operator' => '=',
        'value'    => $user_amount
    ],
];



$where_last_order = [
    [
        'column'    => 'user_id',
        'operator'  => '=',
        'value'     => $_GET['shareholder_id']
    ],
];
$last_order =  tr_query()->table('se7en_shareholder_checkout')->findAll()->where($where_last_order)->orderBy('ID', 'DESC')->select('order_item_id', 'date_created')->get();


if( $last_order[0] ) {

    $order = tr_query()->table('se7en_wc_order_product_lookup')->findAll()->where('order_item_id', '>', $last_order[0]->order_item_id);
    $order = $order->join('se7en_postmeta', 'se7en_postmeta.post_id', '=', 'se7en_wc_order_product_lookup.product_id', 'LEFT')->where($where_user);
    $order = $order->setIdColumn('order_item_id')->orderBy('order_item_id', 'DESC')->distinct()->get();
    $last_date = $last_order[0]->date_created;

} else {

    $order = tr_query()->table('se7en_wc_order_product_lookup')->findAll();
    $order = $order->join('se7en_postmeta', 'se7en_postmeta.post_id', '=', 'se7en_wc_order_product_lookup.product_id', 'LEFT')->where($where_user);
    $order = $order->setIdColumn('order_item_id')->orderBy('order_item_id', 'DESC')->distinct()->get();
    $last_date = null;

}


foreach( $order as $item ) {

    $shareholder = tr_query()->table('se7en_postmeta')->setIdColumn('post_id')->findByID($item->product_id)->where($where_amount)->select('meta_value')->get();
    if( $shareholder['meta_value'] ) {
        $wallet += ($item->product_gross_revenue * $shareholder['meta_value']) / 100;
        // $user_shareholder_month += (($item->product_gross_revenue * $shareholder['meta_value']) / 100) / 2;
    }
        
}

if( ! $wallet ) {
    $wallet = 0;
}

if( $last_date ) {
    $last_date = 'موجودی کیف پول شما از آخرین تسویه در تاریخ ' . $last_date . ' | ' . parsidate("Y-m-d h:i:s", $last_date, "per");
} else {
    $last_date = 'موجودی کیف پول شما ';
}

echo 
    'موجودی کیف پول: '
    . $wallet
    . "<br>"
    . $last_date
    . ' تا به امروز'
    . "<br>";


if( $order[0] ) {
    
    include plugin_dir_path(__FILE__) . '/action.php';
    echo 
        "<form action='' method='post' style='margin-top: 20px;'>"
        ."<input type='hidden' name='order_item_id' id='order_item_id' value='" . $order[0]->order_item_id . "' required>"
        ."<input type='hidden' name='order_date' id='order_date' value='" . $order[0]->date_created . "' required>"
        ."<input type='hidden' name='user_id' id='user_id' value='" . $_GET['shareholder_id'] . "' required>"
        ."<input type='hidden' name='date_created' id='date_created' value='" . date('Y-m-d H:i:s') . "' required>"
        ."<input type='hidden' name='wallet_amount' id='wallet_amount' value='" . $wallet . "' required>"
        ."<input type='hidden' name='status' id='status' value='1' required>"
        . "<input type='submit' value='تسویه' class='shareholder-submit button button-primary'>"
        . "</form>"
        . "<hr>";

}