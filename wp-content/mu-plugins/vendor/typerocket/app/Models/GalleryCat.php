<?php
namespace App\Models;

use TypeRocket\Models\WPTerm;

class GalleryCat extends WPTerm
{
    public const TAXONOMY = 'gallery_cat';

    // public function gallerys()
    // {
    //     return $this->belongsToPost(Gallery::class);
    // }

    public function posts()
    {
        // $model = '\App\Models\Gallery';
        return $this->belongsToPost(Gallery::class, function($posts) {
            $where = [
                [
                    'column'   => 'post_status',
                    'operator' => '=',
                    'value'    => 'publish'
                ]
            ];
            $posts->with('meta')->whereMeta('gallery_in_site', '=', 1)->where($where)->orderBy('id', 'DESC');
        });
    }
}