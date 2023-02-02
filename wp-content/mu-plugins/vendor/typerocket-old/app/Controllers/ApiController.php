<?php
namespace App\Controllers;

use App\Models\Post;
use TypeRocket\Controllers\Controller;

class ApiController extends Controller
{
    public function latest() {
        $latest = (new \App\Models\Post)->published()->take(10)->get();    
        // return $latest;
        echo "سلام";
    }
}