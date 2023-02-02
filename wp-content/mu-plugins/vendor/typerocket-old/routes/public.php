<?php
/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/

// include dirname( __FILE__ ) . '/hierarchy.php';

// tr_route()->get()->match('path')->do('method@Controller');
// tr_route()->post()->match('path')->do('method@Controller');
// tr_route()->put()->match('path')->do('method@Controller');
// tr_route()->delete()->match('path')->do('method@Controller');
// tr_route()->any()->match('path')->do('method@Controller');


/*********************** Company Routs ***********************/
tr_route()->get()->match('company')->do('archivePublic@Company');
// tr_route()->get()->match('company/(.*)', ['id'])->do('singlePublic@Company');
// tr_route()->get()->match('company/([^\/]+)', ['id'])->do('singlePublic@Company');
tr_route()->get()->match('company/(\d+)', ['id'])->do('singlePublic@Company');


/*********************** Job Routs ***********************/
tr_route()->get()->match('job')->do('archivePublic@Job');
tr_route()->get()->match('job/(\d+)', ['id'])->do('singlePublic@Job');


/*********************** Resume Routs ***********************/
tr_route()->get()->match('resume')->do('archivePublic@Resume');
tr_route()->get()->match('resume/(\d+)', ['id'])->do('singlePublic@Resume');

tr_route()->get()->match('request')->do('request@ToJob');
tr_route()->post()->match('request2')->do('request2@ToJob');



// tr_route_template()->on('home', function() {
//     return 'Your new site index template';
// });

// tr_template_router(function() {
// 	return tr_view('blog.single');
// });

// tr_route()->get()->match('shop')->do(function() {
    // use TypeRocket\Controllers\ResumeController as aaa;
    // $test = new aaa;
    // var_dump($test);
//     echo 'Shop';
// });