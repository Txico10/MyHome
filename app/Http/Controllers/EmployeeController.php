<?php
/**
 * Employee Controller
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

use App\Models\Role;
use App\Models\Team;
use App\Models\TeamSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laratrust\LaratrustFacade;
/**
 *  Employee Controller class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Team $company)
    {
        //$users = $company->users->load('employees', 'contacts');
        //dd($users);

        if ($request->ajax()) {
            $users = $company->users->load('employees', 'contacts');
            $roles = Role::all();

            return datatables()->of($users)
                ->editColumn(
                    'photo',
                    function ($user) {
                        if (is_null($user->photo)) {
                            $img = '<img alt="Avatar" class="img-circle img-size-32 mr-2" src="https://picsum.photos/id/1/160">';
                        } else {
                            $img = '<img alt="Avatar" class="img-circle img-size-32 mr-2" src="'.asset('storage/images/profile/users/'.$user->photo).'">';
                        }
                        return $img;
                    }
                )
                ->editColumn(
                    'active',
                    function ($user) {
                        return $user->active==1?'Active':'Inactive';
                    }
                )
                ->addColumn(
                    'role',
                    function ($user) use ($roles) {
                        $role_id = $user->employees->last()->role_id;
                        return $roles->where('id', $role_id)->first()->display_name;
                    }
                )
                ->addColumn(
                    'availability',
                    function ($user) {
                        return ucfirst($user->employees->last()->availability);
                    }
                )
                ->addColumn(
                    'contact',
                    function ($user) {
                        foreach ($user->contacts as $contact) {
                            if (strcmp($contact->priority, 'main')==0) {
                                if (strcmp($contact->type, 'phone')==0 || strcmp($contact->type, 'mobile')==0) {
                                    return $contact->description;
                                }
                            }
                        }
                        return 'N/A';
                    }
                )
                ->addColumn(
                    'action',
                    function ($user) use ($company, $roles) {
                        $btn = null;
                        if (LaratrustFacade::isAbleTo('employee-read')) {
                            $btn = $btn.'<a class="btn btn-outline-primary btn-sm mx-1 shadow" type="button" title="More details" href="'.route('company.employees.show', ['company'=>$company, 'employee'=>$user]).'"><i class="fas fa-search fa-fw"></i></a>';
                        }
                        if (Auth::id()!=$user->id) {
                            $role_id = $user->employees->last()->role_id;
                            if (strcmp($roles->where('id', $role_id)->first()->name, 'owner')!=0) {
                                if (LaratrustFacade::isAbleTo('employee-delete')) {
                                    $btn = $btn.'<button class="btn btn-outline-danger mx-1 shadow btn-sm deleteEmployeeButton" title="Delete Employee" type="button" value="'.$user->id.'" data-company="'.$company->id.'"><i class="fas fa-trash-alt fa-fw"></i></button>';
                                }
                            }
                        }

                        return $btn;
                    }
                )
                ->rawColumns(['photo', 'action'])
                ->removeColumn('id')
                ->removeColumn('birthdate')
                ->removeColumn('gender')
                ->removeColumn('ssn')
                ->removeColumn('email_verified_at')
                ->removeColumn('password')
                ->removeColumn('remember_token')
                ->removeColumn('created_at')
                ->removeColumn('updated_at')
                ->make();

        }

        return view('companies.employees', ['company'=>$company]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Team $company Company
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Team $company)
    {
        return view('companies.employee-create', ['company'=>$company]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Team $company)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Team $company  Company
     * @param User $employee Employee
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Team $company, User $employee)
    {
        $role = Role::findOrFail($employee->contractCompany($company->id)->first()->role_id);
        $contract_terminations = TeamSetting::where('team_id', $company->id)
            ->where('type', 'contract_termination')
            ->get();

        //dd($contract_terminations);

        return view('companies.employee-show', ['company'=>$company, 'employee'=> $employee, 'role_name'=>$role->display_name, 'contract_terminations'=>$contract_terminations]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Team $company  Company
     * @param User $employee Employee
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $company, User $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request  Request
     * @param Team    $company  Company
     * @param User    $employee Employee
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $company, User $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Team $company  Company
     * @param User $employee Employee
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Team $company, User $employee)
    {
        //
    }
}
