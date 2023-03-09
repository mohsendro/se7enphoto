<?php
/**
* @deprecated : Typerocket Custom Code
*/

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

// Register New Directory Active Theme
if ( ! defined( 'TYPEROCKET_DIR_PATH' ) ) define( 'TYPEROCKET_DIR_PATH' , plugin_dir_path( __FILE__ ) ) ;
if ( ! defined( 'TYPEROCKET_DIR_URL' ) ) define( 'TYPEROCKET_DIR_URL' , plugin_dir_url( __FILE__ ) ) ;


// Register New Directory Active Theme
// register_theme_directory( dirname( __FILE__ ) . '/resources/themes/' );

function wpplus_template_directory_uri($template_dir_uri) {
    return str_replace('/wp-content/themes/wpplus', '/wp-content/mu-plugins/vendor/typerocket/resources/themes/', $template_dir_uri);
}
function wpplus_template_directory($template_dir) {
    return str_replace('/wp-content/themes/wpplus', '/wp-content/mu-plugins/vendor/typerocket/resources/themes/', $template_dir);
}
function wpplus_stylesheet_directory_uri($stylesheet_dir_uri) {
    return str_replace('/wp-content/themes/wpplus', '/wp-content/mu-plugins/vendor/typerocket/resources/themes/', $stylesheet_dir_uri);
}
function wpplus_stylesheet_directory($stylesheet_dir) {
    return str_replace('/wp-content/themes/wpplus', '/wp-content/mu-plugins/vendor/typerocket/resources/themes/', $stylesheet_dir);
}

add_filter('template_directory_uri', 'wpplus_template_directory_uri');
add_filter('template_directory', 'wpplus_template_directory');
add_filter('stylesheet_directory_uri', 'wpplus_stylesheet_directory_uri');
add_filter('stylesheet_directory', 'wpplus_stylesheet_directory');
// var_dump( get_template_directory() );

function wpplus_custom_route() {

	$url = $_SERVER['REQUEST_URI']; 
	if(
		! strpos( $url, 'account') ||
		! strpos( $url, 'signon') ||
		! strpos( $url, 'test')
	) {
		
		// add_action('template_redirect', 'wpplus_hierarchy_template');
		// add_filter( 'index_template', 'wpplus_hierarchy_template', 10, 3 );
		// add_filter( 'frontpage_template', 'wpplus_hierarchy_template', 10, 3 );
		// add_filter( 'home_template', 'wpplus_hierarchy_template', 10, 3 );
		// add_filter( 'page_template', 'wpplus_hierarchy_template', 10, 3 );
		// add_filter( 'paged_template', 'wpplus_hierarchy_template', 10, 3 );
		// add_filter( 'archive_template', 'wpplus_hierarchy_template', 10, 3 );
		// add_filter( 'taxonomy_template', 'wpplus_hierarchy_template', 10, 3 );
		// add_filter( 'category_template', 'wpplus_hierarchy_template', 10, 3 );
		// add_filter( 'tag_template', 'wpplus_hierarchy_template', 10, 3 );
		// add_filter( 'date_template', 'wpplus_hierarchy_template', 10, 3 );
		// add_filter( 'author_template', 'wpplus_hierarchy_template', 10, 3 );
		// add_filter( 'search_template', 'wpplus_hierarchy_template', 10, 3 );
		// add_filter( 'singular_template', 'wpplus_hierarchy_template', 10, 3 );
		// add_filter( 'single_template', 'wpplus_hierarchy_template', 10, 3 );
		// add_filter( 'embed_template', 'wpplus_hierarchy_template', 10, 3 );
		// add_filter( 'attachment_template', 'wpplus_hierarchy_template', 10, 3 );
		// add_filter( 'privacypolicy_template', 'wpplus_hierarchy_template', 10, 3 );
		// add_filter( '404_template', 'wpplus_hierarchy_template', 10, 3 );
	}

}
// wpplus_custom_route();

function wpplus_hierarchy_template() {

    $templates = (new Brain\Hierarchy\Hierarchy())->templates();
    
    foreach($templates as $template) {

    //   $path = get_theme_file_path("/{$template}.php");
      $path = plugin_dir_path( __FILE__) . "resources/themes/{$template}.php";
      if ( file_exists( $path ) ) {
		
        require $path;
        exit();

      }

    }
    
}
// add_action('template_redirect', 'wpplus_hierarchy_template');


// Register Theme Features
function wpplus_theme_features()  {

	// Add theme support for Automatic Feed Links
	add_theme_support( 'automatic-feed-links' );

	// Add theme support for Post Formats
	add_theme_support( 'post-formats', array( 'status', 'quote', 'gallery', 'image', 'video', 'audio', 'link', 'aside', 'chat' ) );

	// Add theme support for Featured Images
	add_theme_support( 'post-thumbnails' );

	// Add theme support for HTML5 Semantic Markup
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

	// Add theme support for document Title tag
	add_theme_support( 'title-tag' );

	// Add theme support for custom CSS in the TinyMCE visual editor
	add_editor_style();

	// Add theme support for Translation
	load_theme_textdomain( 'wpplus', get_template_directory() . '/language' );

}
add_action('after_setup_theme', 'wpplus_theme_features') ;

// Register Enqueue Scripts
function wpplus_enqueue_scripts() {

	wp_register_style( 'bootstrap-reboot', plugin_dir_url(__FILE__) . 'resources/assets/css/vendor/bootstrap-v5/bootstrap-reboot.rtl.min.css', false, false );
	wp_register_style( 'bootstrap.rtl', plugin_dir_url(__FILE__) . 'resources/assets/css/vendor/bootstrap-v5/bootstrap.rtl.min.css', false, false );
	wp_register_style( 'font-awesome', plugin_dir_url(__FILE__) . 'resources/assets/font/font-awesome/css/font-awesome.min.css', false, false );
	wp_register_style( 'line-awesome', plugin_dir_url(__FILE__) . 'resources/assets/font/line-awesome/css/line-awesome.min.css', false, false );
	wp_register_style( 'webslidemenu', plugin_dir_url(__FILE__) . 'resources/assets/css/vendor/webslide/webslidemenu.css', false, false );
	wp_register_style( 'header-footer', plugin_dir_url(__FILE__) . 'resources/assets/css/header-footer.css', false, false );
	wp_register_style( 'aos', plugin_dir_url(__FILE__) . 'resources/assets/css/vendor/aos/aos.css', false, false );
	wp_register_style( 'swiper', plugin_dir_url(__FILE__) . 'resources/assets/css/vendor/swiper/swiper-bundle.min.css', false, false );
	wp_register_style( 'filter-multi-select', plugin_dir_url(__FILE__) . 'resources/assets/css/vendor/filter-multi-select/filter_multi_select.css', false, false );
	wp_register_style( 'style', plugin_dir_url(__FILE__) . 'resources/assets/css/style.css', false, false );
	wp_register_style( 'responsive', plugin_dir_url(__FILE__) . 'resources/assets/css/responsive.css', false, false );

	wp_register_script( 'bootstrap-bundle', plugin_dir_url(__FILE__) . 'resources/assets/js/vendor/bootstrap-v5/bootstrap.bundle.min.js', false, false );
	wp_register_script( 'jquery', plugin_dir_url(__FILE__) . 'resources/assets/js/vendor/jquery/jquery.min.js', false, false );
	wp_register_script( 'aos', plugin_dir_url(__FILE__) . 'resources/"assets/js/vendor/aos/aos.js', false, false );
	wp_register_script( 'swiper', plugin_dir_url(__FILE__) . 'resources/assets/js/vendor/swiper/swiper-bundle.min.js', false, false );
	wp_register_script( 'webslidemenu', plugin_dir_url(__FILE__) . 'resources/assets/js/vendor/webslide/webslidemenu.js', ['jquery'], false );
	wp_register_script( 'filter-multi-select', plugin_dir_url(__FILE__) . 'resources/assets/js/vendor/filter-multi-select/filter-multi-select-bundle.min.js', false, false );
	wp_register_script( 'public', plugin_dir_url(__FILE__) . 'resources/assets/js/public.js', false, false );

	wp_enqueue_style( 'bootstrap-reboot' );
	wp_enqueue_style( 'bootstrap.rtl' );
	wp_enqueue_style( 'font-awesome' );
	wp_enqueue_style( 'line-awesome' );
	wp_enqueue_style( 'webslidemenu' );
	wp_enqueue_style( 'header-footer' );
	// wp_enqueue_style( 'aos' );
	// wp_enqueue_style( 'swiper' );
	// wp_enqueue_style( 'filter-multi-select' );
	wp_enqueue_style( 'style' );
	wp_enqueue_style( 'responsive' );

	wp_enqueue_script( 'bootstrap-bundle' );
	// wp_enqueue_script( 'jquery' );
	// wp_enqueue_script( 'aos' );
	// wp_enqueue_script( 'swiper' );
	wp_enqueue_script( 'webslidemenu' );
	// wp_enqueue_script( 'filter-multi-select' );
	wp_enqueue_script( 'public' );

}
add_action('wp_enqueue_scripts', 'wpplus_enqueue_scripts');


// Snippets
require_once plugin_dir_path(__FILE__) . 'functions/snippets/wp-rewrite-rules.php';
require_once plugin_dir_path(__FILE__) . 'functions/snippets/optimize.php';
require_once plugin_dir_path(__FILE__) . 'functions/snippets/image-size.php';

// Post Types
require_once plugin_dir_path(__FILE__) . 'functions/posttype/post.php';
require_once plugin_dir_path(__FILE__) . 'functions/posttype/gallery.php';

// Taxonomies
require_once plugin_dir_path(__FILE__) . 'functions/taxonomy/gallery_cat.php';

// Meta Boxes
include plugin_dir_path(__FILE__) . 'functions/metabox/user.php';
include plugin_dir_path(__FILE__) . 'functions/metabox/product.php';
include plugin_dir_path(__FILE__) . 'functions/metabox/gallery.php';

// Resource
// require_once plugin_dir_path(__FILE__) . 'functions/metabox/user.php';

// Menu
require_once plugin_dir_path(__FILE__) . 'functions/menu/shareholder.php';

// Table
// require_once plugin_dir_path(__FILE__) . 'functions/table/forms.php';

// Columns
require_once plugin_dir_path(__FILE__) . 'functions/column/product.php';
require_once plugin_dir_path(__FILE__) . 'functions/column/order.php';
require_once plugin_dir_path(__FILE__) . 'functions/column/line-item.php';

// Roles
require_once plugin_dir_path(__FILE__) . 'functions/role/graphicer.php';
require_once plugin_dir_path(__FILE__) . 'functions/role/photographer.php';