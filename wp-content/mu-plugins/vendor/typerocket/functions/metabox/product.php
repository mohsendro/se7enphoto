<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

// Meta Box: product - متادیتای محصول

// Create Tab in woocommerce-product-data 
add_filter('woocommerce_product_data_tabs', function($tabs) {

	$tabs['shareholder_info'] = [
		'label' => 'اطلاعات مالی',
		'target' => 'shareholder_product_data',
		'class' => ['show_if_simple', 'show_if_variable'],
		'priority' => 25
	];
	return $tabs;

});


// Add Settings Fields in woocommerce-product-data 
add_action('woocommerce_product_data_panels', function() {

	?><div id="shareholder_product_data" class="panel woocommerce_options_panel hidden"><?php

    CSF::$enqueue = true;
    CSF::add_admin_enqueue_scripts();
    // $yourAppendedArea.csf_reload_script();

    require plugin_dir_path(__FILE__) . '/product/users-list.php';

    echo '<div class="csf-onload">';
    CSF::field( 
        array(
            'id'      => 'product_shareholder_status',
            'type'    => 'switcher',
            'title'   => 'سهام داران',
            'desc'    => "<strong>نکات :</strong>" . "<br>" . '1) در فروش هر محصول چندین کاربر از جمله مدیر، عکاس و گرافیست سهم خواهند داشت بنابراین در ورود درصد سهام دقت لازم بعمل آید تا مجموع سهام این نقش ها بیش از 100 درصد نباشد' . "<br>" . '2) در صورت عدم تنظیم مقدار سهام در این محصول از مقدار پیشفرض کاربر مورد نظر استفاده خواهد شد',
            'default' => $product_shareholder_status
        ),
        $product_shareholder_status
    );
    CSF::field(
        array(
            'id'          => 'product_shareholder_admin_user',
            'type'        => 'select',
            'title'       => 'مدیر مربوطه',
            'placeholder' => 'کاربر مورد نظر را انتخاب نمایید...',
            'options'     => users_list('administrator'),
            'dependency'  => array( 'product_shareholder_status', '==', 'true' ) // check for true/false by field id
        ),
        $product_shareholder_admin_user
    );
    CSF::field(
        array(
            'id'          => 'product_shareholder_admin_amount',
            'type'        => 'spinner',
            'title'       => 'درصد سهام مدیر',
            'subtitle'    => 'حداقل: 0 | حداکثر: 100 | پیشفرض: 100',
            'unit'        => '%',
            'min'         => 0,
            'max'         => 100,
            'step'        => 1,
            'default'     => 100,
            // 'attributes' => array(
            //     'readonly' => 'readonly',
            // ),
            'dependency'  => array( 'product_shareholder_status', '==', 'true' ) // check for true/false by field id
        ),
        $product_shareholder_admin_amount
    );
    CSF::field(
        array(
            'id'          => 'product_shareholder_photographer_user',
            'type'        => 'select',
            'title'       => 'عکاس مربوطه',
            'placeholder' => 'کاربر مورد نظر را انتخاب نمایید...',
            'options'     => users_list('photographer'),
            'dependency'  => array( 'product_shareholder_status', '==', 'true' ) // check for true/false by field id
        ),
        $product_shareholder_photographer_user
    );
    CSF::field(
        array(
            'id'          => 'product_shareholder_photographer_amount',
            'type'        => 'spinner',
            'title'       => 'درصد سهام عکاس',
            'subtitle'    => 'حداقل: 0 | حداکثر: 100',
            'unit'        => '%',
            'min'         => 0,
            'max'         => 100,
            'step'        => 1,
            'default'     => 0,
            'dependency'  => array( 'product_shareholder_status', '==', 'true' ) // check for true/false by field id
        ),
        $product_shareholder_photographer_amount
    );
    CSF::field(
        array(
            'id'          => 'product_shareholder_graphicer_user',
            'type'        => 'select',
            'title'       => 'گرافیست مربوطه',
            'placeholder' => 'کاربر مورد نظر را انتخاب نمایید...',
            'options'     => users_list('graphicer'),
            'dependency'  => array( 'product_shareholder_status', '==', 'true' ) // check for true/false by field id
        ),
        $product_shareholder_graphicer_user
    );
    CSF::field(
        array(
            'id'          => 'product_shareholder_graphicer_amount',
            'type'        => 'spinner',
            'title'       => 'درصد سهام گرافیست',
            'subtitle'    => 'حداقل: 0 | حداکثر: 100',
            'unit'        => '%',
            'min'         => 0,
            'max'         => 100,
            'step'        => 1,
            'default'     => 0,
            'dependency'  => array( 'product_shareholder_status', '==', 'true' ) // check for true/false by field id
        ),
        $product_shareholder_graphicer_amount
    );
    echo '</div>';
	?></div><?php

});


// Save Settings Fields in woocommerce-product-data 
function shareholder_product_meta_data($post_id, $post, $update) {

    if( $_POST['product_shareholder_status'] == 1 ) {
        update_post_meta( $post_id, 'product_shareholder_status', 'true' );
    } else {
        update_post_meta( $post_id, 'product_shareholder_status', 'false' );
    }
    update_post_meta( $post_id, 'product_shareholder_admin_user', $_POST['product_shareholder_admin_user'] );
    update_post_meta( $post_id, 'product_shareholder_admin_amount', $_POST['product_shareholder_admin_amount'] );
    update_post_meta( $post_id, 'product_shareholder_photographer_user', $_POST['product_shareholder_photographer_user'] );
    update_post_meta( $post_id, 'product_shareholder_photographer_amount', $_POST['product_shareholder_photographer_amount'] );
    update_post_meta( $post_id, 'product_shareholder_graphicer_user', $_POST['product_shareholder_graphicer_user'] );
    update_post_meta( $post_id, 'product_shareholder_graphicer_amount', $_POST['product_shareholder_graphicer_amount'] );

}
add_action('save_post_product', 'shareholder_product_meta_data', 10, 3);

// Ajax Handler Fields in woocommerce-product-data 
// require_once plugin_dir_path(__FILE__) . 'product/product-handle.php';
function product_metabox_enqueue_script() {

	wp_enqueue_script( 'product_metabox_script_handle', plugin_dir_url(__FILE__) . '/product/product.js', array('jquery') );
    wp_localize_script( 'product_metabox_script_handle', 'product_metabox_ajax_localize_obj', array(
                      'ajax_url' => admin_url( 'admin-ajax.php' ),
                      'the_nonce' => wp_create_nonce('product_metabox_form_nonce') 
	));
    
}
// add_action( 'admin_enqueue_scripts', 'product_metabox_enqueue_script' );


///////////////////////////////////////////////////////////////////////////////////// 
// Control core classes for avoid errors
if( class_exists( 'CSF' ) ) {

    //
    // Set a unique slug-like ID
    $prefix = 'product';
  
    //
    // Create a metabox
    CSF::createMetabox( $prefix, array(
        'title'              => 'اطلاعات گالری',
        'post_type'          => 'product',
        'data_type'          => 'unserialize',
        'context'            => 'side',
        'class'              => 'min-height-metabox',
    ) );
  
    //
    // Create a section
    CSF::createSection( $prefix, array(
        // 'title'  => 'Tab Title 1',
        'fields' => array(

            //
            // A select field
            array(
                'id'          => 'product_gallery',
                'type'        => 'select',
                'title'       => 'گالری مربوطه',
                'placeholder' => 'نام گالری مورد نظر را وارد نمایید...',
                'chosen'      => true,
                'multiple'    => false,
                'ajax'        => true,
                'options'     => 'posts',
                'query_args'  => array(
                  'post_type' => 'gallery'
                )
            ),            
            
        )
    ) );
    
}