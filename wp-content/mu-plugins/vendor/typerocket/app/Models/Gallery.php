<?php
namespace App\Models;

use TypeRocket\Models\WPPost;

class Gallery extends WPPost
{
    public const POST_TYPE = 'gallery';

    public function gaallery_cat()
    {
        return $this->belongsToTaxonomy(GalleryCat::class, GalleryCat::TAXONOMY);
    }
}