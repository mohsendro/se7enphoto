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


echo "<div class='wrap' style='margin-bottom: 35px;'>";
    echo "<h1 class='wp-heading-inline'>گزارشات</h1>";
    echo "<div><span>لیست گزارشات مربوط به کاربر " . $user->display_name . " </span></div>";
    echo "<hr class='wp-header-end'>";
echo "</div>";


// Current Month Income
$order_month = $order;
foreach( $order_month as $item ) {

    if( date('Y-m') == date('Y-m', strtotime($item->date_created)) ) {

        $shareholder = tr_query()->table('se7en_postmeta')->setIdColumn('post_id')->findByID($item->product_id)->where($where_amount)->select('meta_value')->get();
        if( $shareholder['meta_value'] ) {

            $user_shareholder_month += ($item->product_gross_revenue * $shareholder['meta_value']) / 100;
            // $user_shareholder_month += (($item->product_gross_revenue * $shareholder['meta_value']) / 100) / 2;

        }

    }
    
}
echo 'درآمد ماه جاری: ' . "<strong>" . $user_shareholder_month . "</strong>";
echo "<hr>";


// Total Income
$order_total = $order;
foreach( $order_total as $item2 ) {

    $shareholder2 = tr_query()->table('se7en_postmeta')->setIdColumn('post_id')->findByID($item2->product_id)->where($where_amount)->select('meta_value')->get();
    if( $shareholder2['meta_value'] ) {

        $user_shareholder_total += ($item2->product_gross_revenue * $shareholder2['meta_value']) / 100;
        // $user_shareholder_total += (($item2->product_gross_revenue * $shareholder2['meta_value']) / 100) / 2;

    }
    
}
echo 'درآمد کل: ' . "<strong>" . $user_shareholder_total . "</strong>";
echo "<hr>";


// Custom Date Income
if( $_GET['date'] ) {

    $order_custom_date = $order;
    foreach( $order_custom_date as $item ) { 

        if( $_GET['date'] == date('Y-m', strtotime($item->date_created)) ) {
    
            $shareholder3 = tr_query()->table('se7en_postmeta')->setIdColumn('post_id')->findByID($item->product_id)->where($where_amount)->select('meta_value')->get();
            if( $shareholder3['meta_value'] ) {
    
                $user_shareholder_custom_date += ($item->product_gross_revenue * $shareholder3['meta_value']) / 100;
    
            }
    
        }
        
    }
    echo 'درآمد در تاریخ ' . parsidate("m-Y", strtotime($_GET['date']), "per") . " : <strong>" . $user_shareholder_custom_date . "</strong>";
    echo "<hr>";
}

$order_year = $order;
foreach( $order_year as $date ) { //var_dump($date->date_created); date("Y", strtotime($date->date_created))ک

    $order_date_year[$date->date_created] = $date->date_created;
    // $order_date_year[date("Y", strtotime($date->date_created))] = date("Y", strtotime($date->date_created));
    // $order_date_year[$date->date_created] += $date->date_created;
    // $order_date_year[parsidate("Y", $date->date_created, "per")] += parsidate("Y", $date->date_created, "per");
    
} //print_r($order_date_year); echo "<hr>";

foreach( $order_date_year as $year => $value ) {
   
    // $date_per = date("Y", strtotime($year)); //var_dump($date_per);
    $year_per = parsidate("Y", $year, "per"); //var_dump( date("Y", $year) );

    echo 'آمار سال ' . $year_per . "<br>";
    echo "<a href='" . add_query_arg('date', $year.'-01') . "'> فروردین " . $year_per . "</a>";
    echo "<a href='" . add_query_arg('date', $year.'-02') . "'> اردیبهشت " . $year_per . "</a>";
    echo "<a href='" . add_query_arg('date', $year.'-03') . "'> خرداد " . $year_per . "</a>";
    echo "<a href='" . add_query_arg('date', $year.'-04') . "'> تیر " . $year_per . "</a>";
    echo "<a href='" . add_query_arg('date', $year.'-05') . "'> مرداد " . $year_per . "</a>";
    echo "<a href='" . add_query_arg('date', $year.'-06') . "'> شهریور " . $year_per . "</a>";
    echo "<a href='" . add_query_arg('date', $year.'-07') . "'> مهر " . $year_per . "</a>";
    echo "<a href='" . add_query_arg('date', $year.'-08') . "'> آبان " . $year_per . "</a>";
    echo "<a href='" . add_query_arg('date', $year.'-09') . "'> آذر " . $year_per . "</a>";
    echo "<a href='" . add_query_arg('date', $year.'-10') . "'> دی " . $year_per . "</a>";
    echo "<a href='" . add_query_arg('date', $year.'-11') . "'> بهمن " . $year_per . "</a>";
    echo "<a href='" . add_query_arg('date', $year.'-12') . "'> اسفند " . $year_per . "</a>";   
    echo "<hr>";

}