<?php
namespace App\Controllers;

use App\Models\ToJob;
use App\Models\User;
use App\Models\Job;
use TypeRocket\Controllers\Controller;

class ToJobController extends Controller
{
    /**
     * The index page for admin
     *
     * @return mixed
     */
    public function index(ToJob $to_job, User $user, Job $job)
    {
        $to_job = $to_job->findAll()->orderBy('id', 'DESC')->get();
        $user   = $user->findAll()->get();
        $job    = $job->findAll()->get();
        return tr_view('tojob.admin.index', compact('to_job', 'user', 'job') );
    }

    /**
     * The add page for admin
     *
     * @return mixed
     */
    public function add(User $user, Job $job)
    {
        $user = $user->findAll()->get();
        $job = $job->findAll()->get();
        return tr_view('tojob.admin.add', compact('user', 'job') );
    }

    /**
     * Create item
     *
     * AJAX requests and normal requests can be made to this action
     *
     * @return mixed
     */
    public function create(ToJob $to_job)
    {
        $tr_request = tr_request();
        $to_job->user_id = $tr_request->getDataPost('tojob_add_user_id');
        $to_job->job_id  = $tr_request->getDataPost('tojob_add_job_id');
        $to_job->content = $tr_request->getDataPost('tojob_add_content');
        if( $tr_request->getDataPost('tojob_add_status') ) {
            $to_job->status = "active";
        } else {
            $to_job->status = "deactive";
        }
        $to_job->save(); 
        tr_response()->flashNext('درخواست جدید ثبت شد'); 
        return tr_redirect()->toPage('tojob', 'index');
    }

    /**
     * The edit page for admin
     *
     * @param string|ToJob $to_job
     *
     * @return mixed
     */
    public function edit(ToJob $to_job)
    {
        // TODO: Implement edit() method.
    }

    /**
     * Update item
     *
     * AJAX requests and normal requests can be made to this action
     *
     * @param string|ToJob $to_job
     *
     * @return mixed
     */
    public function update(ToJob $to_job)
    {
        // TODO: Implement update() method.
    }

    /**
     * The show page for admin
     *
     * @param string|ToJob $to_job
     *
     * @return mixed
     */
    public function show(ToJob $to_job)
    {
        // TODO: Implement show() method.
    }

    /**
     * The delete page for admin
     *
     * @param string|ToJob $to_job
     *
     * @return mixed
     */
    public function delete(ToJob $to_job)
    {
        // TODO: Implement delete() method.
    }

    /**
     * Destroy item
     *
     * AJAX requests and normal requests can be made to this action
     *
     * @param string|ToJob $to_job
     *
     * @return mixed
     */
    public function destroy(ToJob $to_job)
    {
        // TODO: Implement destroy() method.
    }

    /**
     * Create item
     *
     * AJAX requests and normal requests can be made to this action
     *
     * @return mixed
     */
    public function request(ToJob $to_job)
    {
        $tr_request = tr_request();
        $to_job->user_id = $tr_request->getDataGet('toJobUserID');
        $to_job->job_id  = $tr_request->getDataGet('toJobJobID');
        $to_job->content = $tr_request->getDataGet('toJobContent');
        // if( $tr_request->getDataPost('tojob_add_status') ) {
        //     $to_job->status = "active";
        // } else {
        //     $to_job->status = "deactive";
        // }
        $to_job->save(); 
        // tr_response()->flashNext('درخواست جدید ثبت شد'); 
        // return tr_redirect()->toPage('tojob', 'index');
        // var_dump($_GET); 
        // var_dump($tr_request);
        return wp_redirect( get_home_url() . '/job/' . $tr_request->getDataGet('toJobJobID') );
    }

}