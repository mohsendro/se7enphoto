<?php
namespace App\Controllers;

use App\Models\Gallery;
use App\Models\Option;
use TypeRocket\Controllers\WPPostController;
use TypeRocket\Http\Request;

class GalleryController extends WPPostController
{
    protected $modelClass = Gallery::class;   
    
    /**
     * The index page for public
     *
     * @return mixed
     */
    public function home(Gallery $post, Option $option)
    {
        $where = [
            [
                'column'   => 'option_name',
                'operator' => '=',
                'value'    => 'posts_per_page'
            ]
        ];
        $option = $option->find()->where($where)->select('option_value')->get()->toArray();
        $option = $option[0]['option_value'];

        $posts = $post->findAll()->with('meta')->whereMeta('gallery_in_site', '=', 1)->where('post_status', '=', 'publish')->orderBy('id', 'DESC');
        $posts_data = $posts; 
        $posts = $posts->get();

        if( $posts != null || $posts > 0 ) {

            $count = $posts->count();
            $total_page = ceil($count / $option);
            $current_page = 1;
            $posts = $posts_data->take($option, 0)->get();

        } else {

            $posts = [];
            $count = 0;
            $total_page = 0;
            $current_page = 0;
            
        }

        return tr_view('public.gallery', compact('posts', 'count', 'total_page', 'current_page') );
    }

    /**
     * The index page for public
     *
     * @return mixed
     */
    public function page(Gallery $post, Option $option)
    {
        // tr_redirect()->toURL(home_url('/blog/'))->now();
        return include( get_query_template( '404' ) );
    }

    /**
     * The archive page for public
     *
     * @return mixed
     */
    public function archive(Gallery $post, Option $option, $number)
    {
        $where = [
            [
                'column'   => 'option_name',
                'operator' => '=',
                'value'    => 'posts_per_page'
            ]
        ];
        $option = $option->find()->where($where)->select('option_value')->get()->toArray();
        $option = $option[0]['option_value'];

        $posts = $post->findAll()->with('meta')->whereMeta('gallery_in_site', '=', 1)->where('post_status', '=', 'publish')->orderBy('id', 'DESC');
        $posts_data = $posts; 
        $posts = $posts->get();

        if( $posts != null || $posts > 0 ) {

            $count = $posts->count();
            $total_page = ceil($count / $option);
            $current_page = intval($number);

            if( (intval($number) <= $total_page) && (intval($number) >= 1) ) {
                $posts = $posts_data->take($option, ($number-1)*$option)->get();
                if( $number == 1 ) {
                    // $posts = $posts->take($option, 1);
                    tr_redirect()->toURL(home_url('/gallery/'))->now();
                }
            } else {
                // $posts = $posts->take($option, $number);
                // tr_redirect()->toURL(home_url('/blog/'))->now();
                return include( get_query_template( '404' ) );
            }

        } else {

            $posts = [];
            $count = 0;
            $total_page = 0;
            $current_page = 0;
            
        } 

        return tr_view('public.gallery', compact('posts', 'count', 'total_page', 'current_page') );
    }

    /**
     * The index page for public
     *
     * @return mixed
     */
    public function last(Gallery $post, Option $option)
    {
        $where = [
            [
                'column'   => 'option_name',
                'operator' => '=',
                'value'    => 'posts_per_page'
            ]
        ];
        $option = $option->find()->where($where)->select('option_value')->get()->toArray();
        $option = $option[0]['option_value'];

        $where_meta = [
            [
                'column'   => 'gallery_in_site',
                'operator' => '=',
                'value'    => 1
            ],
            'AND',
            [
                'column'   => 'gallery_last_view',
                'operator' => '=',
                'value'    => 1
            ]
        ];
        $posts = $post->findAll()->with('meta')->whereMeta($where_meta)->where('post_status', '=', 'publish')->orderBy('id', 'DESC');
        $posts_data = $posts; 
        $posts = $posts->get();

        if( $posts != null || $posts > 0 ) {

            $count = $posts->count();
            $total_page = ceil($count / $option);
            $current_page = 1;
            $posts = $posts_data->take($option, 0)->get();

        } else {

            $posts = [];
            $count = 0;
            $total_page = 0;
            $current_page = 0;
            
        }

        return tr_view('public.gallery-last', compact('posts', 'count', 'total_page', 'current_page') );
    }

    /**
     * The archive page for public
     *
     * @return mixed
     */
    public function archiveLast(Gallery $post, Option $option, $number)
    {
        $where = [
            [
                'column'   => 'option_name',
                'operator' => '=',
                'value'    => 'posts_per_page'
            ]
        ];
        $option = $option->find()->where($where)->select('option_value')->get()->toArray();
        $option = $option[0]['option_value'];

        $where_meta = [
            [
                'column'   => 'gallery_in_site',
                'operator' => '=',
                'value'    => 1
            ],
            'AND',
            [
                'column'   => 'gallery_last_view',
                'operator' => '=',
                'value'    => 1
            ]
        ];
        $posts = $post->findAll()->with('meta')->whereMeta($where_meta)->where('post_status', '=', 'publish')->orderBy('id', 'DESC');
        $posts_data = $posts; 
        $posts = $posts->get();

        if( $posts != null || $posts > 0 ) {

            $count = $posts->count();
            $total_page = ceil($count / $option);
            $current_page = intval($number);

            if( (intval($number) <= $total_page) && (intval($number) >= 1) ) {
                $posts = $posts_data->take($option, ($number-1)*$option)->get();
                if( $number == 1 ) {
                    // $posts = $posts->take($option, 1);
                    tr_redirect()->toURL(home_url('/gallery/gallery-last/'))->now();
                }
            } else {
                // $posts = $posts->take($option, $number);
                // tr_redirect()->toURL(home_url('/blog/'))->now();
                return include( get_query_template( '404' ) );
            }

        } else {

            $posts = [];
            $count = 0;
            $total_page = 0;
            $current_page = 0;
            
        } 

        return tr_view('public.gallery-last', compact('posts', 'count', 'total_page', 'current_page') );
    }

    /**
     * The index page for public
     *
     * @return mixed
     */
    public function featured(Gallery $post, Option $option)
    {
        $where = [
            [
                'column'   => 'option_name',
                'operator' => '=',
                'value'    => 'posts_per_page'
            ]
        ];
        $option = $option->find()->where($where)->select('option_value')->get()->toArray();
        $option = $option[0]['option_value'];

        $where_meta = [
            [
                'column'   => 'gallery_in_site',
                'operator' => '=',
                'value'    => 1
            ],
            'AND',
            [
                'column'   => 'gallery_featured',
                'operator' => '=',
                'value'    => 1
            ]
        ];
        $posts = $post->findAll()->with('meta')->whereMeta($where_meta)->where('post_status', '=', 'publish')->orderBy('id', 'DESC');
        $posts_data = $posts; 
        $posts = $posts->get();

        if( $posts != null || $posts > 0 ) {

            $count = $posts->count();
            $total_page = ceil($count / $option);
            $current_page = 1;
            $posts = $posts_data->take($option, 0)->get();

        } else {

            $posts = [];
            $count = 0;
            $total_page = 0;
            $current_page = 0;
            
        }

        return tr_view('public.gallery-featured', compact('posts', 'count', 'total_page', 'current_page') );
    }

    /**
     * The archive page for public
     *
     * @return mixed
     */
    public function archiveFeatured(Gallery $post, Option $option, $number)
    {
        $where = [
            [
                'column'   => 'option_name',
                'operator' => '=',
                'value'    => 'posts_per_page'
            ]
        ];
        $option = $option->find()->where($where)->select('option_value')->get()->toArray();
        $option = $option[0]['option_value'];

        $where_meta = [
            [
                'column'   => 'gallery_in_site',
                'operator' => '=',
                'value'    => 1
            ],
            'AND',
            [
                'column'   => 'gallery_featured',
                'operator' => '=',
                'value'    => 1
            ]
        ];
        $posts = $post->findAll()->with('meta')->whereMeta($where_meta)->where('post_status', '=', 'publish')->orderBy('id', 'DESC');
        $posts_data = $posts; 
        $posts = $posts->get();

        if( $posts != null || $posts > 0 ) {

            $count = $posts->count();
            $total_page = ceil($count / $option);
            $current_page = intval($number);

            if( (intval($number) <= $total_page) && (intval($number) >= 1) ) {
                $posts = $posts_data->take($option, ($number-1)*$option)->get();
                if( $number == 1 ) {
                    // $posts = $posts->take($option, 1);
                    tr_redirect()->toURL(home_url('/gallery/gallery-featured/'))->now();
                }
            } else {
                // $posts = $posts->take($option, $number);
                // tr_redirect()->toURL(home_url('/blog/'))->now();
                return include( get_query_template( '404' ) );
            }

        } else {

            $posts = [];
            $count = 0;
            $total_page = 0;
            $current_page = 0;
            
        } 

        return tr_view('public.gallery-featured', compact('posts', 'count', 'total_page', 'current_page') );
    }

    /**
     * The single page for admin
     *
     * @return mixed
     */
    public function single(Gallery $post, $slug)
    {
        $where = [
            [
                'column'   => 'post_status',
                'operator' => '=',
                'value'    => 'publish'
            ],
            'AND',
            [
                'column'   => 'post_name',
                'operator' => '=',
                'value'    => $slug
            ]
        ];
        $post = $post->first()->where($where)->get();

        if( $post ){
            return tr_view('public.single-gallery', compact('post', 'slug') );
        } else {
            return include( get_query_template( '404' ) );
        }
    } 
}