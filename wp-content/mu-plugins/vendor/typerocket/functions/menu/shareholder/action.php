<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

$model = new \App\Models\ShareholderCheckout;
$request = tr_request();
$model->order_item_id = $request->getDataPost('order_item_id');
$model->order_date = $request->getDataPost('order_date');
$model->user_id = $request->getDataPost('user_id');
$model->date_created = $request->getDataPost('date_created');
$model->wallet_amount = $request->getDataPost('wallet_amount');
$model->status = $request->getDataPost('status');
$model->save(); 
tr_response()->flashNext('تسویه حساب با موفقیت انجام شد'); 
return tr_redirect()->toPage('wc-shareholder&tab=checkou', 'shareholder_id', $_GET['shareholder_id']);