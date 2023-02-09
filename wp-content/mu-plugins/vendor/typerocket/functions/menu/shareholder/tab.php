<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

$tabs = tr_tabs();

$tabContentOne = "<p>Main content 1.</p>";
$tabContentTwo = "<p>Main content 2.</p>";
$tabContentThree = "<p>Main content 3.</p>";

$tabs->tab('سفارشات', 'users', $tabContentOne);
$tabs->tab('گزارشات', 'books', $tabContentTwo);
$tabs->tab('پرداخت‌ها', 'projects', $tabContentThree);

echo $tabs;