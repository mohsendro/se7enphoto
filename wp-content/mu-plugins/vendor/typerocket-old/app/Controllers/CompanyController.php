<?php
namespace App\Controllers;

use App\Models\Company;
use TypeRocket\Controllers\Controller;

class CompanyController extends Controller
{
    /**
     * The index page for admin
     *
     * @return mixed
     */
    public function index(Company $company)
    {
        $company2 = $company->findAll()->get();
        $job     = $company->findById(1)->job()->get(); // ORM Model Relationships
        return tr_view('company.admin.index', compact('company2', 'job') );
    }

    /**
     * The add page for admin
     *
     * @return mixed
     */
    public function add()
    {
        return tr_view('company.admin.add');
    }

    /**
     * Create item
     *
     * AJAX requests and normal requests can be made to this action
     *
     * @return mixed
     */
    public function create(Company $company)
    {
        $tr_request = tr_request(); //var_dump($tr_request);
        $company->title = $tr_request->getDataPost('company_add_title');
        $company->content = $tr_request->getDataPost('company_add_content');
        $company->save(); 
        tr_response()->flashNext('شرکت جدید ثبت شد'); 
        return tr_redirect()->toPage('company', 'index');
    }

    /**
     * The edit page for admin
     *
     * @param string|Company $company
     *
     * @return mixed
     */
    public function edit(Company $company)
    {
        // $id = $_GET['post'];
        // $id = $_GET["route_args"];
        // $company = $company->where('id', '=' , $id)->get();
        if( isset($company) ) {
            return tr_view('company.admin.edit', compact('company'));
        } else {
            echo "همچین شناسه ای وجود ندارد";
        }
    }

    /**
     * Update item
     *
     * AJAX requests and normal requests can be made to this action
     *
     * @param string|Company $company
     *
     * @return mixed
     */
    public function update(Company $company)
    {
        $tr_request = tr_request();
        $company->title   = $tr_request->getDataPost('company_edit_title');
        $company->content = $tr_request->getDataPost('company_edit_content');
        $company->save(); 
        tr_response()->flashNext('شرکت بروزرسانی شد'); 
        return tr_redirect()->toPage('company', 'edit', $company->getID() );
    }

    /**
     * The show page for admin
     *
     * @param string|Company $company
     *
     * @return mixed
     */
    public function show(Company $company)
    {
        // return tr_view('company.admin.show', ['company' => $company] );
    }

    /**
     * The delete page for admin
     *
     * @param string|Company $company
     *
     * @return mixed
     */
    public function delete(Company $company)
    {
        return tr_view('company.admin.delete', compact('company') );
    }

    /**
     * Destroy item
     *
     * AJAX requests and normal requests can be made to this action
     *
     * @param string|Company $company
     *
     * @return mixed
     */
    public function destroy(Company $company)
    {
        $company->delete();
        tr_response()->flashNext('شرکت مورد نظرحذف شد', 'warning');
        return tr_redirect()->toPage('company', 'index');
    }

    /**
     * The index page for public
     *
     * @return mixed
     */
    public function archivePublic(Company $company)
    {
        $company = $company->findAll()->get()->toArray();
        return tr_view('company.public.archivePublic', compact('company') );
    }

    /**
     * The index page for public
     *
     * @return mixed
     */
    public function singlePublic(Company $company, $id)
    {
        $company = $company->where('id', '=' , $id)->get();
        if( isset($company) ) {
            $company = $company->toArray();
            return tr_view('company.public.singlePublic', compact('company') );
        } else {
            echo "همچین شناسه ای وجود ندارد";
        }
    }

}