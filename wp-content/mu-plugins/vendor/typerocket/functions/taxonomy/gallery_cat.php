<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

// Taxonomy: gallery_cat - دسته‌بندی گالری

// general
$gallery_cat = tr_taxonomy('gallery_cat', 'gallery_cats');
$gallery_cat->addPostType('gallery');
// $gallery = tr_post_type('gallery');
// $gallery_cat->apply($gallery);
$gallery->setModelClass(\App\Models\GalleryCat::class);
$gallery->setHandler(\App\Controllers\GalleryCatController::class);

// labels
$upperPlural = 'دسته‌بندی‌ها';
$upperSingular = 'دسته‌بندی';
$lowerSingular = 'دسته‌بندی';
$lowerPlural = 'دسته‌بندی‌ها';
$labels = [
    'add_new_item'               => sprintf( _x( 'افزودن %s', 'taxonomy:gallery_cat', 'your-custom-domain' ), $upperSingular),
    'add_or_remove_items'        => sprintf( _x( 'افزودن یا حذف %s', 'taxonomy:gallery_cat', 'your-custom-domain' ), $lowerPlural),
    'all_items'                  => sprintf( _x( 'همهٔ %s', 'taxonomy:gallery_cat', 'your-custom-domain' ), $upperPlural),
    'back_to_items'              => sprintf( _x( '← برگشت به %s', 'taxonomy:gallery_cat', 'your-custom-domain' ), $lowerPlural),
    'choose_from_most_used'      => sprintf( _x( 'انتخاب از بیشترین موارد %s', 'taxonomy:gallery_cat', 'your-custom-domain' ), $lowerPlural),
    'edit_item'                  => sprintf( _x( 'ویرایش %s', 'taxonomy:gallery_cat', 'your-custom-domain' ), $upperSingular),
    'name'                       => sprintf( _x( '%s', 'taxonomy:gallery_cat:taxonomy general name', 'your-custom-domain' ), $upperPlural),
    'menu_name'                  => sprintf( _x( '%s', 'taxonomy:gallery_cat:admin menu', 'your-custom-domain' ), $upperPlural),
    'new_item_name'              => sprintf( _x( 'نام %s جدید', 'taxonomy:gallery_cat', 'your-custom-domain' ), $upperSingular),
    'no_terms'                   => sprintf( _x( 'بدون %s', 'taxonomy:gallery_cat', 'your-custom-domain' ), $lowerPlural),
    'not_found'                  => sprintf( _x( 'هیچ %s یافت نشد.', 'taxonomy:gallery_cat', 'your-custom-domain' ), $lowerPlural),
    'parent_item'                => sprintf( _x( '%s مادر', 'taxonomy:gallery_cat', 'your-custom-domain' ), $upperSingular),
    'parent_item_colon'          => sprintf( _x( 'مادر %s:', 'taxonomy:gallery_cat', 'your-custom-domain' ), $upperSingular),
    'popular_items'              => sprintf( _x( 'محبوب %s', 'taxonomy:gallery_cat', 'your-custom-domain' ), $upperPlural),
    'search_items'               => sprintf( _x( 'جستجو %s', 'taxonomy:gallery_cat', 'your-custom-domain' ), $upperPlural),
    'separate_items_with_commas' => sprintf( _x( 'جداسازی %s با کاما', 'taxonomy:gallery_cat', 'your-custom-domain' ), $lowerPlural),
    'singular_name'              => sprintf( _x( '%s', 'taxonomy:gallery_cat:taxonomy singular name', 'your-custom-domain' ), $upperSingular),
    'update_item'                => sprintf( _x( 'بروزرسانی %s', 'taxonomy:gallery_cat', 'your-custom-domain' ), $upperSingular),
    'view_item'                  => sprintf( _x( 'نمایش %s', 'taxonomy:gallery_cat', 'your-custom-domain' ), $upperSingular),
];
$gallery_cat->setLabels($labels);

// slug
$withFront = false;
$gallery_cat->setSlug('gallery_cat', $withFront);
$gallery_cat->setHierarchical();
$gallery_cat->setRest('gallery_cat');

// single


// archive
$gallery_cat->showQuickEdit(true);
$gallery_cat->showPostTypeAdminColumn(true);


// meta data
