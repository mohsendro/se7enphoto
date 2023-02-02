<?php
/**
* @deprecated : Typerocket Custom Code
*/

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

tr_resource_pages('Company', 'شرکت ها');
tr_resource_pages('Job', 'آگهی ها');
tr_resource_pages('Resume', 'رزومه ها');
tr_resource_pages('ToJob', 'درخواست ها');



// $test = (new \TypeRocket\Controllers); 
// $test = (new \TypeRocket\Controllers\WPPostController); 
// var_dump($test);





/*
* Enqueue Styles && Scripts
*/
/*
function wpplus_enqueuing_admin_styles_scripts() {
 
	wp_register_style( 'admin-style', plugin_dir_url(__FILE__) . 'typerocket/resources/assets/admin/admin.css', false, '4.2.1' );
	wp_enqueue_style( 'admin-style' );

    wp_register_script( 'admin-script', plugin_dir_url(__FILE__) . 'typerocket/resources/assets/admin/admin.js', false, '1.0.0' );
	wp_enqueue_script( 'admin-script' );

}

function wpplus_enqueuing_public_styles_scripts() {

	wp_register_style( 'public-style', plugin_dir_url(__FILE__) . 'typerocket/resources/assets/public/public.css', false, '4.2.1' );
	wp_enqueue_style( 'public-style' );

    wp_register_script( 'public-script', plugin_dir_url(__FILE__) . 'typerocket/resources/assets/public/public.js', array('jquery'), '1.0.0' );
	wp_enqueue_script( 'public-script' );

}

add_action( 'admin_enqueue_scripts', 'wpplus_enqueuing_admin_styles_scripts' );
add_action( 'wp_enqueue_scripts', 'wpplus_enqueuing_public_styles_scripts' );
*/



/*
* ToJob Ajax Form Handler
*/
/*
function dcwd_ajax_enqueue_script() {
	// wp_enqueue_script( 'script_handle', plugin_dir_url(__FILE__) . 'typerocket/resources/assets/public/public.js', array('jquery') );
    wp_localize_script( 'public-script', 'tojob_ajax_localize_obj', array(
                      'ajax_url' => admin_url( 'admin-ajax.php' ),
                      'the_nonce' => wp_create_nonce('tojob_form_nonce') 
	));
}
add_action( 'wp_enqueue_scripts', 'dcwd_ajax_enqueue_script' );


function tojob_ajax_handle_function() {
    check_ajax_referer( 'tojob_form_nonce', 'submitted_nonce' );  // This function will die if submitted_nonce is not correct.

    //$post_type = sanitize_text_field($_POST['post_type']);  

    // global $wpdb;
    // $table_to_job = $wpdb->prefix . 'to_jobs';
    // $wpdb->insert( 
    //     $table_to_job, 
    //     array( 
    //         'user_id' => 1, 
    //         'job_id' => 2, 
    //         'content' => 'Test', 
    //     ), 
    //     array( 
    //         '%d', 
    //         '%d', 
    //         '%s', 
    //     )
    // );

    $to_job = new App\Models\ToJob();
    $to_job->user_id = sanitize_text_field($_POST['toJobUserID']);
    $to_job->job_id  = sanitize_text_field($_POST['toJobJobID']);
    $to_job->content = sanitize_text_field($_POST['toJobContent']);
    $to_job->save(); 

    $response = array(
        'success'   => 'درخواست با موفقیت ارسال شد',
    );

    wp_send_json_success( $response, 200 );
    
    wp_send_json_error();
    wp_die();

}
// add_action( 'wp_ajax_nopriv_tojob_ajax_handle', 'tojob_ajax_handle_function' );
add_action( 'wp_ajax_tojob_ajax_handle', 'tojob_ajax_handle_function' );  // For logged in users.
*/



