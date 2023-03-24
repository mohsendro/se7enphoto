<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

?>

<?php get_header(); ?>

<!-- Slide Start -->
<section id="front-page" class="container-fluid front-page">
    <div class="row slide">
        <div class="search">
            <form class="d-flex" action="<?php get_bloginfo('url'); ?>" method="get">
                <input class="border border-3 border-top-0 border-start-0 border-end-0" type="search" name="s" placeholder="نام گالری مورد نظر را وارد نمایید..."
                    aria-label="Search" style=" outline: none;">
                <button class="btn btn-primary" type="submit">جستجو</button>
            </form>
        </div>
    </div>
</section>
<!-- Slide End -->

<!-- Gallery Start -->
<section id="gallery" class="container gallery">
    <div class="row">
        <div class="col-12 head">
           آخرین گالری‌ها
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

<?php get_footer(); ?>