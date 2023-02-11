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

echo "<div class='wrap' style='margin-bottom: 35px;'>";
    echo "<h1 class='wp-heading-inline'>گزارشات</h1>";
    echo "<div><span>لیست گزارشات مربوط به کاربر " . $user->display_name . " </span></div>";
    echo "<hr class='wp-header-end'>";
echo "</div>";


// Current Month Income
$where_month_user = [ 
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
$where_month_amount = [
    [
        'column'   => 'meta_key',
        'operator' => '=',
        'value'    => $user_amount
    ],
];

$order_month = tr_query()->table('se7en_wc_order_product_lookup')->setIdColumn('product_id')->findAll()->orderBy('date_created', 'DESC');
$order_month = $order_month->join('se7en_postmeta', 'se7en_postmeta.post_id', '=', 'se7en_wc_order_product_lookup.product_id', 'LEFT')->where($where_month_user);
$order_month = $order_month->distinct()->get();

foreach( $order_month as $item ) {

    if( date('Y-m') == date('Y-m', strtotime($item->date_created)) ) {

        $shareholder = tr_query()->table('se7en_postmeta')->setIdColumn('post_id')->findByID($item->product_id)->where($where_month_amount)->select('meta_value')->get();
        if( $shareholder['meta_value'] ) {

            $user_shareholder_month += (($item->product_gross_revenue * $shareholder['meta_value']) / 100) / 2;

        }

    }
    
}
echo 'درآمد ماه جاری: ' . "<strong>" . $user_shareholder_month . "</strong>";
echo "<hr>";


// Total Income
$where_total_user = [ 
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
$where_total_amount = [
    [
        'column'   => 'meta_key',
        'operator' => '=',
        'value'    => $user_amount
    ],
];

$order_total = tr_query()->table('se7en_wc_order_product_lookup')->setIdColumn('product_id')->findAll()->orderBy('date_created', 'DESC');
$order_total = $order_total->join('se7en_postmeta', 'se7en_postmeta.post_id', '=', 'se7en_wc_order_product_lookup.product_id', 'LEFT')->where($where_total_user);
$order_total = $order_total->distinct()->get();

foreach( $order_total as $item2 ) {

    $shareholder2 = tr_query()->table('se7en_postmeta')->setIdColumn('post_id')->findByID($item2->product_id)->where($where_total_amount)->select('meta_value')->get();
    if( $shareholder2['meta_value'] ) {

        $user_shareholder_total += (($item2->product_gross_revenue * $shareholder2['meta_value']) / 100) / 2;

    }
    
}
echo 'درآمد کل: ' . "<strong>" . $user_shareholder_total . "</strong>";
echo "<hr>";


// Custom Date Income
// $order_date = tr_query()->table('se7en_wc_order_product_lookup');
// $order_date = $order_date->findAll()->orderBy('date_created', 'DESC');
// $order_date = json_decode($order_date);

$order_year = tr_query()->table('se7en_wc_order_product_lookup')->findAll()->orderBy('date_created', 'DESC');
foreach( $order_year as $date ) {

    $order_date_year[parsidate("Y", $date->date_created, "per")] += parsidate("Y", $date->date_created, "per");
    
}

foreach( $order_date_year as $year => $item ) {
    
    echo 'آمار سال ' . $year . "<br>";
    echo "<a href='#'> فروردین " . $year . "</a>";
    echo "<a href='#'> اردیبهشت " . $year . "</a>";
    echo "<a href='#'> خرداد " . $year . "</a>";
    echo "<a href='#'> تیر " . $year . "</a>";
    echo "<a href='#'> مرداد " . $year . "</a>";
    echo "<a href='#'> شهریور " . $year . "</a>";
    echo "<a href='#'> مهر " . $year . "</a>";
    echo "<a href='#'> آبان " . $year . "</a>";
    echo "<a href='#'> آذر " . $year . "</a>";
    echo "<a href='#'> دی " . $year . "</a>";
    echo "<a href='#'> بهمن " . $year . "</a>";
    echo "<a href='#'> اسفند " . $year . "</a>";   
    echo "<hr>";

}