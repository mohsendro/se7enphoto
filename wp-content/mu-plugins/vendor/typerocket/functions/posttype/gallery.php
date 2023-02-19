<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

// Post Type: gallery - گالری‌

// general
$gallery = tr_post_type('gallery', 'gallerys');
$gallery->setIcon('dashicons-archive');
$gallery->setPosition(5);
$gallery->setModelClass(\App\Models\Gallery::class);
$gallery->setHandler(\App\Controllers\GalleryController::class);

// labels
$upperPlural = 'گالری‌‌ها';
$upperSingular = 'گالری‌';
$lowerSingular = 'گالری‌';
$pluralLower = 'گالری‌‌ها';
$labels = [
    'add_new'               => _x('افزودن گالری‌', 'post_type:gallery', 'wpplus'),
    'all_items'             => sprintf( _x('همهٔ %s', 'post_type:gallery', 'wpplus'), $upperPlural),
    'archives'              => sprintf( _x('%s گالری‌‌ها', 'post_type:gallery', 'wpplus'), $upperSingular),
    'add_new_item'          => sprintf( _x('%s تازه', 'post_type:gallery', 'wpplus'), $upperSingular),
    'attributes'            => sprintf( _x('ویژگی %s', 'post_type:gallery', 'wpplus'), $upperSingular),
    'edit_item'             => sprintf( _x('ویرایش %s', 'post_type:gallery', 'wpplus'), $upperSingular),
    'filter_items_list'     => sprintf( _x('فیلتر %s list %s', 'post_type:gallery', 'wpplus'), $pluralLower, $upperSingular),
    'insert_into_item'      => sprintf( _x('درج در %s', 'post_type:gallery', 'wpplus'), $lowerSingular),
    'item_published'        => sprintf( _x('%s انتشار.', 'post_type:gallery', 'wpplus'), $upperSingular),
    'item_published_privately' => sprintf( _x('%s انتشار خصوصی.', 'wpplus'), $upperSingular),
    'item_updated'          => sprintf( _x('%s بروزرسانی.', 'post_type:gallery', 'wpplus'), $upperSingular),
    'item_reverted_to_draft'=> sprintf( _x('%s انتقال به پیشنویس.', 'post_type:gallery', 'wpplus'), $upperSingular),
    'item_scheduled'        => sprintf( _x('%s برنامه ریزی.', 'post_type:gallery', 'wpplus'), $upperSingular),
    'items_list'            => sprintf( _x('%s لیست', 'post_type:gallery', 'wpplus'), $upperPlural),
    'menu_name'             => sprintf( _x('%s',  'post_type:gallery:admin menu', 'wpplus'), $upperPlural),
    'name'                  => sprintf( _x('%s', 'post_type:gallery:post type general name', 'wpplus'), $upperPlural),
    'name_admin_bar'        => sprintf( _x('%s', 'post_type:gallery:add new from admin bar', 'wpplus'), $upperSingular),
    'items_list_navigation' => sprintf( _x('%s list navigation', 'post_type:gallery', 'wpplus'), $upperPlural),
    'new_item'              => sprintf( _x('جدید %s', 'post_type:gallery', 'wpplus'), $upperSingular),
    'not_found'             => sprintf( _x('هیچ گالری‌ یافت نشد', 'post_type:gallery', 'wpplus'), $pluralLower),
    'not_found_in_trash'    => sprintf( _x('یافت نشد %s در سطل زباله', 'post_type:gallery', 'wpplus'), $pluralLower),
    'parent_item_colon'     => sprintf( _x("مادر %s:", 'post_type:gallery', 'wpplus'), $upperPlural),
    'search_items'          => sprintf( _x('جستجو %s', 'post_type:gallery', 'wpplus'), $upperPlural),
    'singular_name'         => sprintf( _x('%s',  'post_type:gallery:post type singular name', 'wpplus'), $upperSingular),
    'uploaded_to_this_item' => sprintf( _x('بارگذاری در %s', 'post_type:gallery', 'wpplus'), $lowerSingular),
    'view_item'             => sprintf( _x('نمایش %s', 'post_type:gallery', 'wpplus'), $upperSingular),
    'view_items'            => sprintf( _x('نمایش %s', 'post_type:gallery', 'wpplus'), $upperPlural),
];
$gallery->setLabels($labels);

// slug
$withFront = false;
$gallery->setSlug('gallery', $withFront);
$gallery->setHierarchical(true);
$gallery->setRest('gallery');
// $gallery->setRootOnly('/{post-type}/{post-name}');

// single
$gallery->forceDisableGutenberg();
$gallery->setSupports(['title', 'thumbnail', 'editor', 'excerpt', 'author', 'comments', 'page-attributes', 'post-formats']);
// $gallery->featureless();

// archive
// $gallery->addColumn('Photo', true, 'Photo3', function($value) {
//     return 'asdsad';
// });


// meta data
// $gallery->setModelClass(\App\Models\Gallery::class)
//             ->saveTitleAs(function (\App\Models\Gallery $gallery, \TypeRocket\Http\Request $request) {
//                 return $gallery->data->$request->getDataPost('post_title');
//             });