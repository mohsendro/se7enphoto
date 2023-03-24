<?php
namespace App\Controllers;

use App\Models\Gallery;
use TypeRocket\Controllers\WPPostController;
use TypeRocket\Http\Request;

class FronPageController extends WPPostController
{
    /**
     * The index page for public
     *
     * @return mixed
     */
    public function index(Gallery $post)
    {
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
        $posts = $post->findAll()->with('meta')->whereMeta($where_meta)->where('post_status', '=', 'publish')->orderBy('id', 'DESC')->take(20, 0)->get();;

        return tr_view( 'public.front-page', compact('posts') );
    }
}