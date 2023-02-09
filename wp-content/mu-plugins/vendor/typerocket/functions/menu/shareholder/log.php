<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

$order_date = tr_query()->table('se7en_wc_order_product_lookup');
$order_date = $order_date->findAll()->orderBy('date_created', 'DESC');
// $order_date = json_decode($order_date);



$order_month = $order_date->select('product_id', 'variation_id', 'product_gross_revenue', 'date_created')->get();
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
];
foreach( $order_month as $item ) {

    if( date('Y-m') == date('Y-m', strtotime($item->date_created)) ) {

        $shareholder = tr_query()->table('se7en_postmeta')->setIdColumn('post_id')->findByID($item->product_id)->where($where)->select('meta_value')->get();
        if( $shareholder['meta_value'] ) {

            $user_shareholder += ($item->product_gross_revenue * $shareholder['meta_value']) / 100;

        }

    }
    
}
echo 'درآمد این ماه: ' . $user_shareholder;



echo "<hr>";



$order_all = $order_date->select('product_id', 'variation_id', 'product_gross_revenue', 'date_created')->get();
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
];
foreach( $order_all as $item2 ) {

    $shareholder2 = tr_query()->table('se7en_postmeta')->setIdColumn('post_id')->findByID($item2->product_id)->where($where)->select('meta_value')->get();
    if( $shareholder2['meta_value'] ) {

        $user_shareholder2 += ($item2->product_gross_revenue * $shareholder2['meta_value']) / 100;

    }
    
}
echo 'درآمد کل: ' . $user_shareholder2;



echo "<hr>";



$order_year = $order_date->select('date_created')->get();
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