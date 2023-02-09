<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

// Meta Box: user - متادیتای کاربر

// Control core classes for avoid errors
if( class_exists( 'CSF' ) ) {

    //
    // Set a unique slug-like ID
    $prefix = 'user_shareholder';
  
    //
    // Create profile options
    CSF::createProfileOptions( $prefix, array(
      'data_type' => 'unserialize ', // The type of the database save options. `serialize` or `unserialize`
    ) );
  
    //
    // Create a section
    CSF::createSection( $prefix, array(
      'title'  => 'اطلاعات مالی',
      'fields' => array(
  
        array(
          'id'          => 'user_shareholder_stock_amount',
          'type'        => 'spinner',
          'title'       => 'درصد سهام',
          'subtitle'    => 'حداقل: 0 | حداکثر: 100 | پیشفرض: 30',
          'desc'        => "<strong>نکات :</strong>" . "<br>" . '1) فقط برای کاربران دارای سهام مشارکت یا به عبارتی نقش های کاربری عکاس و گرافیست تنظیم شود' . "<br>" . '2) مقدار پیشفرض بصورت عمومی بوده ولی برای هر محصول و یا تصویر قابلیت سفارشی سازی می باشد. و در صورت عدم تنظیم مقدار دهی در محصول از این مقدار پیشفرض استفاده خواهد شد' . "<br>" . '3) در فروش هر محصول چندین کاربر از جمله مدیر، عکاس و گرافیست سهم خواهند داشت بنابراین در ورود درصد سهام دقت لازم بعمل آید تا مجموع سهام این نقش ها بیش از 100 درصد نباشد',
          'unit'        => '%',
          'min'         => 0,
          'max'         => 100,
          'step'        => 1,
          'default'     => 30,
        ),

        array(
          'id'          => 'user_shareholder_wallet_amount',
          'type'        => 'number',
          'title'       => 'موجودی کیف پول',
          'unit'        => 'تومان',
          'default'     => 0,
        ),
  
      )
    ) );
  
}
