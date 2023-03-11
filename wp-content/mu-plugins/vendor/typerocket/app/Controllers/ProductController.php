<?php
namespace App\Controllers;

use App\Models\Product;
use App\Models\Option;
use TypeRocket\Controllers\WPPostController;
use TypeRocket\Http\Request;

class ProductController extends WPPostController
{
    protected $modelClass = Product::class; 
}