<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

echo "<div class='wrap'>";
    echo "<h1 class='wp-heading-inline'>پرداخت‌ها</h1>";
    echo "<hr class='wp-header-end'>";
echo "</div>";

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