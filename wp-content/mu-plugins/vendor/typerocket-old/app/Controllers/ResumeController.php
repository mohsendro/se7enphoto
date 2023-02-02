<?php
namespace App\Controllers;

use App\Models\Resume;
use App\Models\User;
use TypeRocket\Controllers\Controller;

class ResumeController extends Controller
{
    /**
     * The index page for admin
     *
     * @return mixed
     */
    public function index(Resume $resume, User $user)
    {
        $resume = $resume->findAll()->orderBy('id', 'DESC')->get();
        // $user2 = (new Resume)->user()->findAll()->get();
        $user = $user->findAll()->get()->toArray();
        return tr_view('resume.admin.index', compact('resume', 'user') );
    }

    /**
     * The add page for admin
     *
     * @return mixed
     */
    public function add(User $user)
    {
        $user = $user->findAll()->get();
        return tr_view('resume.admin.add', compact('user') );
    }

    /**
     * Create item
     *
     * AJAX requests and normal requests can be made to this action
     *
     * @return mixed
     */
    public function create(Resume $resume)
    {
        $tr_request = tr_request(); //var_dump($tr_request);
        $resume->user_id = $tr_request->getDataPost('resume_add_user_id');
        $resume->title = $tr_request->getDataPost('resume_add_title');
        $resume->content = $tr_request->getDataPost('resume_add_content');
        $resume->save(); 
        tr_response()->flashNext('رزومه جدید ثبت شد'); 
        return tr_redirect()->toPage('resume', 'index');
    }

    /**
     * The edit page for admin
     *
     * @param string|Resume $resume
     *
     * @return mixed
     */
    public function edit(Resume $resume)
    {
        // $id = $_GET['post'];
        $id = $_GET["route_args"];
        $user = $resume->user()->findAll()->get();
        if( isset($resume) ) {
            return tr_view('resume.admin.edit', compact('resume', 'user'));
        } else {
            echo "همچین شناسه ای وجود ندارد";
        }
    }

    /**
     * Update item
     *
     * AJAX requests and normal requests can be made to this action
     *
     * @param string|Resume $resume
     *
     * @return mixed
     */
    public function update(Resume $resume)
    {
        $tr_request = tr_request();
        $resume->user_id = $tr_request->getDataPost('resume_edit_user_id');
        $resume->title   = $tr_request->getDataPost('resume_edit_title');
        $resume->content = $tr_request->getDataPost('resume_edit_content');
        $resume->save(); 
        tr_response()->flashNext('رزومه بروزرسانی شد'); 
        return tr_redirect()->toPage('resume', 'edit', $resume->getID() );
    }

    /**
     * The show page for admin
     *
     * @param string|Resume $resume
     *
     * @return mixed
     */
    public function show(Resume $resume)
    {
        // return tr_view('resume.admin.show', ['resume' => $resume] );
    }

    /**
     * The delete page for admin
     *
     * @param string|Resume $resume
     *
     * @return mixed
     */
    public function delete(Resume $resume)
    {
        return tr_view('resume.admin.delete', compact('resume') );
    }

    /**
     * Destroy item
     *
     * AJAX requests and normal requests can be made to this action
     *
     * @param string|Resume $resume
     *
     * @return mixed
     */
    public function destroy(Resume $resume)
    {
        $resume->delete();
        tr_response()->flashNext('رزومه مورد نظرحذف شد', 'warning');
        return tr_redirect()->toPage('resume', 'index');
    }

    /**
     * The index page for public
     *
     * @return mixed
     */
    public function archivePublic(Resume $resume)
    {
        $resume = $resume->findAll()->get()->toArray();
        return tr_view('resume.public.archivePublic', compact('resume') );
    }

    /**
     * The index page for public
     *
     * @return mixed
     */
    public function singlePublic(Resume $resume, $id)
    {
        $resume = $resume->where('id', '=' , $id)->get();
        // $user_id = (new Resume)->findById($id)->select('user_id')->get();
        // $user = $user->findById($user_id->user_id)->get();
        $user = (new Resume)->findById($id)->user()->get();
        if( isset($resume) ) {
            $resume = $resume->toArray();
            return tr_view('resume.public.singlePublic', compact('resume', 'user') );
        } else {
            echo "همچین شناسه ای وجود ندارد";
        }
    }

}