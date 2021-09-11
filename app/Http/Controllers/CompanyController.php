<?php
/**
 * Company Controller
 *
 * PHP version 7.4
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Models\Team;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Laratrust\LaratrustFacade;
/**
 *  Company Controller class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class CompanyController extends Controller
{
    /**
     * List of companies
     *
     * @param Request $request users request
     *
     * @return View
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $companies = Team::has('contracts')->withCount(
                [
                    'contracts as adminCount' => function (Builder $query) {
                        $query->where('role_id', 2);
                    },
                    'contracts as managerCount' => function (Builder $query) {
                        $query->where('role_id', 3);
                    },
                    'contracts as janitorCount' => function (Builder $query) {
                        $query->where('role_id', 4);
                    },
                    'contracts as tenantCount' => function (Builder $query) {
                        $query->where('role_id', 5);
                    },
                ]
            )->get();

            return datatables()->of($companies)
                ->editColumn(
                    'logo',
                    function ($company) {
                        return '<img alt="Avatar" class="img-circle img-size-32 mr-2" src="'.asset('storage/images/profile/companies/'.$company->logo).'">';
                    }
                )
                ->editColumn(
                    'adminCount',
                    function ($company) {
                        $tag = '<small class="text-success mr-1"><i class="fas fa-arrow-up"></i>12%</small>';
                        $tag = $tag.' '.$company->adminCount;
                        return $tag;
                    }
                )
                ->editColumn(
                    'managerCount',
                    function ($company) {
                        $tag = '<small class="text-warning mr-1"><i class="fas fa-arrow-down"></i>0.5%</small>';
                        $tag = $tag.' '.$company->managerCount;
                        return $tag;
                    }
                )
                ->editColumn(
                    'janitorCount',
                    function ($company) {
                        $tag = '<small class="text-danger mr-1"><i class="fas fa-arrow-down"></i>3%</small>';
                        $tag = $tag.' '.$company->janitorCount;
                        return $tag;
                    }
                )
                ->editColumn(
                    'tenantCount',
                    function ($company) {
                        $tag = '<small class="text-success mr-1"><i class="fas fa-arrow-up"></i>63%</small>';
                        $tag = $tag.' '.$company->tenantCount;
                        return $tag;
                    }
                )
                ->addColumn(
                    'action',
                    function ($company) {
                        $btn = null;
                        if (LaratrustFacade::isAbleTo('clients-read')) {
                            $btn = $btn.'<a class="btn btn-outline-primary btn-sm mx-1 shadow" type="button" title="More details" href="'.route('company.show', ['company'=>$company]).'"><i class="fas fa-search fa-fw"></i></a>';
                        }
                        if (LaratrustFacade::isAbleTo('clients-update')) {
                            $btn = $btn.'<a class="btn btn-outline-secondary mx-1 shadow btn-sm" type="button" title="Edit company" href="'.route('company.edit', ['company'=>$company]).'"><i class="fas fa-pencil-alt fa-fw"></i></a>';
                        }
                        if (LaratrustFacade::isAbleTo('clients-delete')) {
                            $btn = $btn.'<button class="btn btn-outline-danger mx-1 shadow btn-sm deleteClientButton" title="Delete client" type="button" value="'.$company->id.'"><i class="fas fa-trash-alt fa-fw"></i></button>';
                        }
                        return $btn;
                    }
                )
                ->rawColumns(
                    [
                        'logo', 'adminCount', 'managerCount', 'janitorCount',
                        'tenantCount', 'action'
                    ]
                )
                ->removeColumn('id')
                ->removeColumn('slug')
                ->removeColumn('legalform')
                ->removeColumn('description')
                ->removeColumn('created_at')
                ->removeColumn('updated_at')
                ->make();

        }
        return view('admin.clients');
    }

    /**
     * Create
     *
     * @return View
     */
    public function create()
    {
        return view('companies.company-create');
    }

    /**
     * Store
     *
     * @param Request $request Request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        // code...
    }

    /**
     * Show
     *
     * @param Team $company Company
     *
     * @return View
     */
    public function show(Team $company)
    {
        return view('companies.company-show', ['company'=>$company]);
    }

    /**
     * Edit
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return View
     */
    public function edit(Request $request, Team $company)
    {
        if ($request->ajax()) {
            return response()->json(['company'=>$company], 200);
        }

        return null;
    }

    /**
     * Update
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return Response
     */
    public function update(CompanyRequest $request, Team $company)
    {
        if ($request->ajax()) {

            if (strcmp($company->display_name, $request->display_name)!=0) {
                $company->display_name = $request->display_name;
            }

            if (strcmp($company->slug, $request->slug)!=0) {
                $company->slug = $request->slug;
            }


            if ($request->bn != $company->bn) {
                $company->bn = $request->bn;
            }

            if (strcmp($company->legalform, $request->legalform)!=0) {
                $company->legalform = $request->legalform;
            }

            $company->description = $request->description;

            $company->save();

            //send email notification to admin and manager

            return response()->json(['message' => 'Company updated successfully!']);

        }
        return null;
    }

    /**
     * Destroy
     *
     * @param int $id Company id
     *
     * @return Response
     */
    public function destroy(int $id)
    {
        // code...
    }

    /**
     * Logo update
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return Response Json
     */
    public function logoupdate(Request $request, Team $company)
    {
        if ($request->ajax()) {
            $request->validate(
                ['file' => 'bail|image|mimes:png,jpg,jpeg,gif,svg|max:2048']
            );

            if ($request->file('file')) {

                $file_name = Str::random().time().'.'.$request->file('file')
                    ->extension();

                if (!empty($company->logo) && strcmp($company->logo, "defaultCompany.png")) {
                    Storage::delete('public/images/profile/companies/'.$company->logo);
                }

                $request->file('file')
                    ->storeAs('public/images/profile/companies', $file_name);

                $company->logo = $file_name;

                Image::make('storage/images/profile/companies/'.$file_name)
                    ->fit(200)
                    ->save('storage/images/profile/companies/'.$file_name);

                $company->save();

            }

            return response()->json(['message'=>'Company logo updated successfully'], 200);
        }
        return null;
    }
}
