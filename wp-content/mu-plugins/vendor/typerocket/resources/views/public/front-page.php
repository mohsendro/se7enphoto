<?php

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

?>

<?php get_header(); ?>

<h1>صفحه اصلی</h1>
<hr>

<h3>گالری‌ها:</h3><br>
<?php

    if( $posts ) 
    foreach ($posts as $post) {
        echo $post->ID . ' | ' . $post->post_title;
        echo "<br>";
    }

?>
<hr>

<?php get_footer(); ?>