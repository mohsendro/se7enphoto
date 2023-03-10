<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

?>

<?php get_header(); ?>

<!-- Page Start -->
<section id="pages" class="container pages">
    <div class="row">
        <div class="col-12">
            <div class="title">
                <?php the_title(); ?>
            </div>
        </div>
        <div class="col-12">
            <div class="content">
                <?php the_content(); ?>
            </div>
        </div>
    </div>
</section>
<!-- Page End -->

<?php get_footer(); ?>