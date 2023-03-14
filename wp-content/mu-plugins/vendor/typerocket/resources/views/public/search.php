<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

?>

<?php get_header(); ?>

<!-- Gallery Start -->
<section id="gallery" class="container gallery">
    <div class="row">
        <div class="col-12 count-post">
            تعداد نتایج: <strong><?php echo $count; ?></strong> | جستجو برای: <strong><?php echo $param; ?></strong>
        </div>
        <?php if( $posts ): ?>
            <?php foreach ($posts as $post): ?>
                <?php $meta = get_post_meta( $post->ID, 'gallery', true ); ?>
                <div class="col-12 col-md-6 col-lg-4 col-xxl-3 item-post">
                    <div class="content-post">
                        <div class="count-img">
                            <i class="las la-image"></i>
                            <?php if ($meta['gallery_products'] ): ?>
                                <?php echo count($meta['gallery_products']); ?> عکس
                            <?php else: ?>
                                بدون عکس
                            <?php endif; ?>
                        </div>
                        <div class="thumbnail">
                            <?php echo get_the_post_thumbnail( $post->ID, 'gallery_cover_size', array( 'class' => 'alignleft' ) ); ?>
                        </div>
                        <a href="<?php echo get_permalink($post->ID); ?>" class="title">
                            <?php echo $post->post_title; ?>
                        </a>
                        <div class="ctegory">
                            <?php 
                                $gallery_cat = get_the_terms($post->ID, 'gallery_cat'); 
                                echo $gallery_cat[0]->name;
                            ?>
                        </div>
                        <div class="date">
                            <?php if ($meta['gallery_products'] ): ?>
                                در تاریخ: <?php echo parsidate("Y-m-d h:i:s", $meta['gallery_play_date'], "per"); ?>
                            <?php else: ?>
                                تاریخ نامعلوم
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 empty-post">
                محتوایی وجود ندارد
            </div>
        <?php endif; ?>
    </div>
</section>
<!-- Gallery End -->

<?php
    require TYPEROCKET_DIR_PATH . '/functions/snippets/pagination.php';
    // pagination_post($count, $total_page, 2, $current_page);
    insertSearchPagination(home_url('search/'.$param), $current_page, $total_page, true);
?>

<?php get_footer(); ?>
