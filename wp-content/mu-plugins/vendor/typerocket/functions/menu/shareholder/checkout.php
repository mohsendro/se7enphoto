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

$order = tr_query()->table('se7en_wc_order_product_lookup')->setIdColumn('product_id')->findAll()->orderBy('date_created', 'DESC');
$order = $order->join('se7en_postmeta', 'se7en_postmeta.post_id', '=', 'se7en_wc_order_product_lookup.product_id', 'LEFT')->where($where_user);
$order = $order->distinct()->get();
























$where2 = [
    [
        'column'   => 'user_id',
        'operator' => '=',
        'value'    => get_current_user_id()
    ],
    'AND',
    [
        'column'   => 'meta_key',
        'operator' => '=',
        'value'    => 'user_shareholder_wallet_amount'
    ]
];
$user_wallet = tr_query()->table('se7en_usermeta')->findAll()->where($where2)->select('meta_value')->get();
echo 'موجودی کیف پول: ' . $user_wallet[0]->meta_value . "<br>" . 'موجودی کیف پول شما از آخرین تسویه در تاریخ ' . ' تا امروز' . "<hr>";