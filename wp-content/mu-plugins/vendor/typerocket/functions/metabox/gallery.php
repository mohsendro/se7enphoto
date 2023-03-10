<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

// Meta Box: gallery - متادیتای گالری

// Control core classes for avoid errors
if( class_exists( 'CSF' ) ) {

    //
    // Set a unique slug-like ID
    $prefix = 'gallery';
  
    //
    // Create a metabox
    CSF::createMetabox( $prefix, array(
        'title'              => 'اطلاعات گالری',
        'post_type'          => 'gallery',
        'data_type'          => 'unserialize',
        'context'            => 'advanced',
    ) );
  
    //
    // Create a section
    CSF::createSection( $prefix, array(
        // 'title'  => 'Tab Title 1',
        'fields' => array(

            //
            // A select field
            array(
                'id'          => 'gallery_products',
                'type'        => 'select',
                'title'       => 'تصاویر مربوطه',
                'placeholder' => 'نام محصول (تصویر) مورد نظر را وارد نمایید...',
                'chosen'      => true,
                'multiple'    => true,
                'ajax'        => true,
                'options'     => 'posts',
                'query_args'  => array(
                  'post_type' => 'product'
                )
            ),

            //
            // A switcher field
            array(
                'id'         => 'gallery_in_site',
                'type'       => 'switcher',
                'title'      => 'نمایش در سایت',
                'text_on'    => 'بله',
                'text_off'   => 'خیر',
                'default'    => true,
                'text_width' => 70
            ),

            array(
                'id'         => 'gallery_last_view',
                'type'       => 'switcher',
                'title'      => 'نمایش در آخرین گالری‌ها',
                'text_on'    => 'بله',
                'text_off'   => 'خیر',
                'default'    => true,
                'text_width' => 70
            ),

            array(
                'id'         => 'gallery_featured',
                'type'       => 'switcher',
                'title'      => 'گالری منتخب',
                'text_on'    => 'بله',
                'text_off'   => 'خیر',
                'default'    => false,
                'text_width' => 70
            ),

            //
            // A time field
            array(
                'id'       => 'gallery_play_date',
                'type'     => 'datetime',
                'title'    => 'تاریخ برگزاری بازی',
                'settings' => array(
                    'altFormat'  => 'F j, Y',
                    'dateFormat' => 'Y-m-d',
                ),
            ),
                
            array(
                'id'       => 'gallery_play_time',
                'type'     => 'datetime',
                'title'    => 'ساعت برگزاری بازی',
                'settings' => array(
                    'noCalendar' => true,
                    'enableTime' => true,
                    'dateFormat' => 'H:i',
                    'time_24hr'  => true,
                ),
            ),             
            
        )
    ) );
    
}
