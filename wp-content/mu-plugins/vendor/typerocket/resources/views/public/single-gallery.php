<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

?>

<h1>صفحه سینگل گالری <span>: <?php echo $post->post_title; ?></span></h1> 
<hr>

<?php
    echo get_the_post_thumbnail( $post->ID, 'thumbnail', array( 'class' => 'alignleft' ) );
    echo "<br>";
    echo $post->post_content; 
?>


<?php get_header(); ?>

<!-- Gallery Start -->
<section id="gallery" class="container gallery">
    <div class="row">
        <div class="col-12 count-post">
            تعداد نتایج: <strong><?php echo $count; ?></strong>
        </div>
        <?php if( $posts ): ?>
            <?php foreach ($posts as $post): ?>
                <?php $meta = get_post_meta( $post->ID, 'gallery', true ); ?>
                <div class="col-12 col-md-6 col-lg-4 col-xxl-3 item-post">
                    <div class="content-post">
                        <div class="count-img">
                            <?php if ($meta['gallery_products'] ): ?>
                                <?php echo count($meta['gallery_products']); ?> عکس
                            <?php else: ?>
                                بدون عکس
                            <?php endif; ?>
                        </div>
                        <div class="thumbnail">
                            <?php echo get_the_post_thumbnail( $post->ID, 'gallery_cover_size', array( 'class' => 'alignleft' ) ); ?>
                        </div>
                        <div class="date">
                            <?php if ($meta['gallery_products'] ): ?>
                                در تاریخ: <?php echo parsidate("Y-m-d h:i:s", $meta['gallery_play_date'], "per"); ?>
                            <?php else: ?>
                                تاریخ نامعلوم
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo get_permalink($post->ID); ?>" class="title">
                            <?php echo $post->post_title; ?>
                        </a>
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