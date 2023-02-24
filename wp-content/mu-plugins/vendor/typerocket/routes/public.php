<?php
/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/

// tr_route()->get()->match('path')->do('method@Controller');
// tr_route()->post()->match('path')->do('method@Controller');
// tr_route()->put()->match('path')->do('method@Controller');
// tr_route()->delete()->match('path')->do('method@Controller');
// tr_route()->any()->match('path')->do('method@Controller');

// Index or Front-page
tr_route()->get()->match('/')->do('index@FronPageController');
// tr_route()->get()->match('/')->do('index@\App\Controllers\FronPageController');

// Blog or Post
tr_route()->get()->match('/blog')->do('home@PostController');
// tr_route()->get()->match('/blog/page')->do('page@PostController');
tr_route()->get()->match('/blog/page/([^\/]+)', ['number'])->do('archive@PostController');
// tr_route()->get()->match('/category')->do('page@CategoryController');
tr_route()->get()->match('/category/([^\/]+)', ['cat_name'])->do('category@CategoryController');
tr_route()->get()->match('/category/([^\/]+)/page/([^\/]+)', ['cat_name', 'number'])->do('archive@CategoryController');
// tr_route()->get()->match('/tag')->do('page@TagController');
tr_route()->get()->match('/tag/([^\/]+)', ['tag_name'])->do('tag@TagController');
tr_route()->get()->match('/tag/([^\/]+)/page/([^\/]+)', ['tag_name', 'number'])->do('archive@TagController');
tr_route()->get()->match('/blog/([^\/]+)', ['slug'])->do('single@PostController'); // single

// Advertising
// tr_route()->get()->match('/advertising')->do('home@AdvertisingController');
// // tr_route()->get()->match('/advertising/page')->do('page@AdvertisingController');
// tr_route()->get()->match('/advertising/page/([^\/]+)', ['number'])->do('archive@AdvertisingController');
// // tr_route()->get()->match('/advertising-cat')->do('page@AdcertisingCatController');
// tr_route()->get()->match('/advertising-cat/([^\/]+)', ['cat_name'])->do('category@AdcertisingCatController');
// tr_route()->get()->match('/advertising-cat/([^\/]+)/page/([^\/]+)', ['cat_name', 'number'])->do('archive@AdcertisingCatController');
// tr_route()->get()->match('/advertising/([^\/]+)', ['slug'])->do('single@AdvertisingController'); // single

// Author
tr_route()->get()->match('/author')->do('home@UserController');
tr_route()->get()->match('/author/page/([^\/]+)', ['number'])->do('archive@UserController');
tr_route()->get()->match('/author/([^\/]+)', ['slug'])->do('single@UserController'); // single

// Metadata Archive
// tr_route()->get()->match('/project')->do('home@ProjectController');

// Search
tr_route()->get()->match('/search/([^\/]+)', ['param'])->do('archive@SearchController');


