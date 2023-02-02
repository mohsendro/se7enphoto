<?php
namespace App\Controllers;

use App\Models\Job;
use App\Models\Company;
use TypeRocket\Controllers\Controller;

class JobController extends Controller
{
    /**
     * The index page for admin
     *
     * @return mixed
     */
    public function index(Job $job, Company $company)
    {
        $job = $job->findAll()->orderBy('id', 'DESC')->get();
        $company = $company->findAll()->get()->toArray();
        // $company = $job->company()->findAll()->get();
        return tr_view('job.admin.index', compact('job', 'company') );
    }

    /**
     * The add page for admin
     *
     * @return mixed
     */
    public function add(Company $company)
    {
        $company = $company->findAll()->get();
        return tr_view('job.admin.add', compact('company') );
    }

    /**
     * Create item
     *
     * AJAX requests and normal requests can be made to this action
     *
     * @return mixed
     */
    public function create(Job $job)
    {
        $tr_request = tr_request(); //var_dump($tr_request);
        $job->company_id = $tr_request->getDataPost('job_add_company_id');
        $job->title = $tr_request->getDataPost('job_add_title');
        $job->content = $tr_request->getDataPost('job_add_content');
        $job->save(); 
        tr_response()->flashNext('آگهی جدید ثبت شد'); 
        return tr_redirect()->toPage('job', 'index');
    }

    /**
     * The edit page for admin
     *
     * @param string|Job $job
     *
     * @return mixed
     */
    public function edit(Job $job)
    {
        // $id = $_GET['post'];
        $id = $_GET["route_args"];
        // $Job = $job->where('id', '=' , $id)->get();
        // $Job = $job->findById($id)->get();
        // $company = $company->findById($id)->get()->toArray();
        $company = $job->company()->findAll()->get()->toArray();
        if( isset($job) ) {
            return tr_view('job.admin.edit', compact('job', 'company'));
        } else {
            echo "همچین شناسه ای وجود ندارد";
        }
    }

    /**
     * Update item
     *
     * AJAX requests and normal requests can be made to this action
     *
     * @param string|Job $job
     *
     * @return mixed
     */
    public function update(Job $job)
    {
        $tr_request = tr_request();
        $job->company_id = $tr_request->getDataPost('job_edit_company_id');
        $job->title   = $tr_request->getDataPost('job_edit_title');
        $job->content = $tr_request->getDataPost('job_edit_content');
        $job->save(); 
        tr_response()->flashNext('آگهی بروزرسانی شد'); 
        return tr_redirect()->toPage('job', 'edit', $job->getID() );
    }

    /**
     * The show page for admin
     *
     * @param string|Job $job
     *
     * @return mixed
     */
    public function show(Job $job)
    {
        // return tr_view('job.admin.show', ['job' => $job] );
    }

    /**
     * The delete page for admin
     *
     * @param string|Job $job
     *
     * @return mixed
     */
    public function delete(Job $job)
    {
        return tr_view('job.admin.delete', compact('job') );
    }

    /**
     * Destroy item
     *
     * AJAX requests and normal requests can be made to this action
     *
     * @param string|Job $job
     *
     * @return mixed
     */
    public function destroy(Job $job)
    {
        $job->delete();
        tr_response()->flashNext('آگهی مورد نظرحذف شد', 'warning');
        return tr_redirect()->toPage('job', 'index');
    }

    /**
     * The index page for public
     *
     * @return mixed
     */
    public function archivePublic(Job $job)
    {
        $job = $job->findAll()->get()->toArray();
        return tr_view('job.public.archivePublic', compact('job') );
    }

    /**
     * The index page for public
     *
     * @return mixed
     */
    public function singlePublic(Job $job, $id)
    {
        $job = $job->where('id', '=' , $id)->get();
        if( isset($job) ) {
            $job = $job->toArray();
            return tr_view('job.public.singlePublic', compact('job') );
        } else {
            echo "همچین شناسه ای وجود ندارد";
        }
    }

}