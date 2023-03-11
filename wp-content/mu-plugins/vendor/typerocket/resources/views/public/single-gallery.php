<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

?>

<?php get_header(); ?>

<!-- Gallery Single Start -->
<section id="gallery-single" class="container gallery-single">
    <div class="row">
        <div class="col-12 info-single">
            <div class="title">
                گالری: <strong><?php echo $post->post_title; ?></strong>
            </div>
            <div class="ctegory">
                <?php 
                    $gallery_cat = get_the_terms($post->ID, 'gallery_cat'); 
                    echo $gallery_cat[0]->name;
                ?>
            </div>           
            <div class="date">
                <?php if ( $post->meta->gallery_play_date ): ?>
                    در تاریخ: <?php echo parsidate("Y-m-d h:i:s", $post->meta->gallery_play_date, "per"); ?>
                <?php else: ?>
                    تاریخ نامعلوم
                <?php endif; ?>
            </div>
            <div class="count-img">
                <i class="las la-image"></i>
                <?php if ( $count ): ?>
                    <?php echo $count; ?> عکس
                <?php else: ?>
                    بدون عکس
                <?php endif; ?>
            </div>
        </div>
        <!-- <div class="col-12 thumbnail">
            <?php //echo get_the_post_thumbnail( $post->ID, 'gallery_cover_size', array( 'class' => 'alignleft' ) ); ?>
        </div> -->
        <!-- <div class="col-12 content">
            <?php //echo $post->post_content; ?>
        </div> -->

        <?php if( $products ): ?>
            <?php foreach( $products as $product ): ?>
                <div class="col-12 col-md-6 col-lg-4 col-xxl-3 item-product">
                    <?php echo get_the_post_thumbnail( $product->ID, 'woocommerce_thumbnail', array( 'class' => 'alignleft' ) ); ?>
                    <a href="<?php echo get_permalink($product->ID); ?>" target="_blank" class="title">
                        <?php echo $product->post_title; ?>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 empty-post">
                محتوایی وجود ندارد
            </div>
        <?php endif; ?>
    </div>
</section>
<!-- Gallery Single End -->

<?php get_footer(); ?>