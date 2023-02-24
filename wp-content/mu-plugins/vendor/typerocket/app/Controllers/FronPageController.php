<?php
namespace App\Controllers;

use App\Models\Post;
use TypeRocket\Controllers\Controller;
use TypeRocket\Http\Request;

class FronPageController extends Controller
{
    /**
     * The index page for public
     *
     * @return mixed
     */
    public function index(Post $post)
    {
        $posts = $post->findAll()->where('post_status', '=', 'publish')->orderBy('id', 'DESC')->take(10)->get();
        
        return tr_view('public.front-page', compact('posts') );
    }
}