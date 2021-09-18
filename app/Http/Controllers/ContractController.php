<?php
/**
 * Contract Controller
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

use App\Models\EmployeeContract;
use App\Models\Role;
use App\Models\Team;
use App\Models\TeamSetting;
use App\Models\User;
use App\Notifications\ContractTerminationNotification;
use App\Notifications\NewContractNotification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laratrust\LaratrustFacade;
/**
 *  Contract Controller class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request  Request
     * @param Team    $company  Company
     * @param User    $employee Employee
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Team $company, User $employee)
    {
        if ($request->ajax()) {
            $contracts = $employee->contractCompany($company->id);

            return datatables()->of($contracts)
                ->addIndexColumn()
                ->addColumn(
                    'code',
                    function ($contract) use ($company) {
                        return "HR".$company->id.$contract->id.$contract->role_id;
                    }
                )
                ->addColumn(
                    'role',
                    function ($contract) {
                        $role_display_name = Role::where('id', $contract->role_id)
                            ->first()
                            ->display_name;
                        //$tag = '<span class="badge bg-primary">'.$name.'</span>';
                        return $role_display_name??'';
                    }
                )
                ->editColumn(
                    'start_at',
                    function ($contract) {
                        return $contract->start_at->format('d F Y');
                    }
                )
                ->editColumn(
                    'agreement_status',
                    function ($contract) {
                        $tag_color = null;

                        switch ($contract->agreement_status) {
                        case 'unavailable':
                            $tag_color = 'bg-warning';
                            break;
                        case 'pending':
                            $tag_color = 'bg-primary';
                            break;
                        case 'published':
                            $tag_color = 'bg-orange';
                            break;
                        case 'accepted':
                            $tag_color = 'bg-success';
                            break;
                        default :
                            $tag_color = 'bg-danger';
                        }

                        return '<span class="badge '.$tag_color.'">'.ucfirst($contract->agreement_status).'</span>';
                    }
                )
                ->editColumn(
                    'acceptance_at',
                    function ($contract) {
                        return is_null($contract->acceptance_at) ?'N/A':$contract->acceptance_at->format('d F Y');
                    }
                )
                ->editColumn(
                    'end_at',
                    function ($contract) {
                        return is_null($contract->end_at)? 'N/A':$contract->end_at->format('d F Y');
                    }
                )
                ->editColumn(
                    'availability',
                    function ($contract) {
                        return is_null($contract->availability)? 'N/A':ucfirst($contract->availability);
                    }
                )
                ->addColumn(
                    'action',
                    function ($contract) use ($company, $employee) {
                        $btn = '<nobr>';
                        $btn = $btn.'<a class="btn btn-outline-primary btn-sm mx-1 shadow" type="button" title="More details" href="'.route('company.employees.contract.show', ['company'=>$company, 'employee'=>$employee, 'contract'=> $contract]).'"><i class="fas fa-search fa-fw"></i></a>';
                        if (strcmp($contract->agreement_status, 'accepted')!=0 && strcmp($contract->agreement_status, 'refused')!=0 && strcmp($contract->agreement_status, 'terminated')!=0) {
                            if (LaratrustFacade::isAbleTo(['contract-update'])) {
                                $btn = $btn.'<a type="button" class="btn btn-sm btn-outline-info mx-1 shadow contractUpdate" href="'.route('company.employees.contract.edit', ['company'=>$company, 'employee'=>$employee, 'contract'=> $contract]).'" title="Edit Contract"><i class="fas fa-fw fa-pencil-alt"></i></a>';
                            }
                            if (strcmp($contract->agreement_status, 'pending')==0) {
                                $btn = $btn.'<button type="button" class="btn btn-sm btn-outline-success mx-1 shadow contractPublish"  value="'.$contract->id.'" title="Publish Contract"><i class="fas fa-fw fa-share"></i></button>';
                            }
                            if (LaratrustFacade::isAbleTo(['contract-delete'])) {
                                $btn = $btn.'<button type="button" class="btn btn-sm btn-outline-danger mx-1 shadow contractTerminate"  value="'.$contract->id.'" title="Close Contract" data-agreement_status="'.$contract->agreement_status.'"><i class="fas fa-fw fa-user-times"></i></button>';
                            }
                        } else {
                            if ($contract->acceptance_at!=null) {
                                $btn =$btn.'<a class="btn btn-sm btn-outline-danger mx-1 shadow" type="button" title="Employee Agreement" href="#"><i class="fas fa-file-signature"></i></a>';
                            }
                            if (strcmp($contract->agreement_status, 'refused')!=0 && strcmp($contract->agreement_status, 'terminated')!=0) {
                                $btn = $btn.'<button type="button" class="btn btn-sm btn-outline-danger mx-1 shadow contractTerminate"  value="'.$contract->id.'" title="Terminate Contract" data-agreement_status="'.$contract->agreement_status.'"><i class="fas fa-fw fa-user-times"></i></button>';
                            }

                        }

                        $btn=$btn.'</nobr>';
                        return $btn;
                    }
                )
                ->rawColumns(['agreement_status', 'action'])
                ->removeColumn('id')
                ->removeColumn('role_id')
                ->removeColumn('salary_term')
                ->removeColumn('salary_amount')
                ->removeColumn('min_week_time')
                ->removeColumn('max_week_time')
                ->removeColumn('agreement')
                ->removeColumn('created_at')
                ->removeColumn('updated_at')
                ->make();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request Request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Team             $company  Company
     * @param User             $employee Employee
     * @param EmployeeContract $contract Contract
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Team $company, User $employee, EmployeeContract $contract)
    {
        $role_display_name = Role::where('id', $contract->role_id)->first()->display_name;
        $contract = $contract->load('teamSettings');
        //dd($contract->teamSettings->count());
        return view('companies.employee-contract-show', ['company'=>$company, 'employee'=>$employee, 'contract'=>$contract, 'role_name'=> $role_display_name]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Team             $company  Company
     * @param User             $employee Employee
     * @param EmployeeContract $contract Contract
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $company, User $employee, EmployeeContract $contract)
    {
        $roles =Role::whereNotIn('id', [1,5])->get();
        $contract = $contract->load('teamSettings');
        //dd($contract->agreement_status);
        if (strcmp($contract->agreement_status, "published")==0) {
            $contract->agreement_status = "pending";
            $contract->save();
        }

        $benefits = TeamSetting::where('team_id', $company->id)
            ->where('type', 'benefit')->pluck('display_name', 'id');
        //dd($benefits);
        return view(
            'companies.employee-contract-edit',
            [
                'company'=>$company,
                'employee'=>$employee,
                'contract'=>$contract,
                'roles'=>$roles,
                'benefits' => $benefits
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request          $request  Request
     * @param Team             $company  Company
     * @param User             $employee Employee
     * @param EmployeeContract $contract Contract
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Team $company, User $employee, EmployeeContract $contract)
    {
        //dd($request->salary_amount);

        $request->validate(
            [
                'role_id' => ['required', 'exists:roles,id'],
                'availability' => ['required', Rule::in(['full-time', 'partial-time'])],
                'min_week_time' => ['required_if:availability,partial-time', 'nullable', 'regex:/(\d+\:\d+:\d+)/'],
                'max_week_time' => ['nullable', 'regex:/(\d+\:\d+:\d+)/'],
                'start_at'=> ['required', 'date'],
                'end_at' => ['nullable', 'date'],
                'salary_term'=> ['required', Rule::in(['hourly', 'monthly', 'annual'])],
                'salary_amount' => ['required', 'regex:/(?=.*\d)^\$?(([1-9]\d{0,2}(,\d{3})*)|0)?(\.\d{1,2})?$/'],
                'agreement' => ['required', 'min:50']
            ],
            [
                'min_week_time.regex'=>'The min time format is not correct',
                'max_week_time.regex'=> 'The max time format is not correct',
                'salary_amount.regex'=> 'The salary amount format is not correct',
            ]
        );

        $salary = substr($request->salary_amount, 1, strlen($request->salary_amount));
        if (strlen($salary)>6) {
            $salary = str_replace(",", "", $salary);
        }
        $contract->role_id = $request->role_id;
        $contract->start_at = $request->start_at;
        $contract->end_at = $request->end_at;
        $contract->salary_term = $request->salary_term;
        $contract->salary_amount = $salary;
        $contract->availability = $request->availability;
        $contract->min_week_time = $request->min_week_time;
        $contract->max_week_time = $request->max_week_time;
        $contract->agreement = $request->agreement;
        if (strcmp($contract->agreement_status, "unavailable")==0) {
            $contract->agreement_status = "pending";
        }

        //dd($contract);

        $contract->save();

        $contract->teamSettings()->sync($request->benefits);

        return redirect()->route('company.employees.contract.show', ['company'=>$company, 'employee'=>$employee, 'contract'=>$contract])->with('success', 'Agreement updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request  Request
     * @param Team    $company  Comoany
     * @param User    $employee Employee
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Team $company, User $employee)
    {
        if ($request->ajax()) {

            $request->validate(
                [
                    'contract_id'=>['required','exists:employee_contracts,id'],
                    'termination_id'=>['required', 'exists:team_settings,id'],
                    'termination_description'=>['required_with:termination_id','string', 'min:5', 'max:255']
                ],
                [
                    'termination_description.required_with'=> 'The description field is required!'
                ]
            );


            $contract = EmployeeContract::findOrFail($request->contract_id);
            $contract->termination_at = now();
            $contract->agreement_status= "terminated";
            $contract->save();

            $contract->teamSettings()->attach($request->termination_id, ['description' => $request->termination_description]);

            $employee->active = false;
            $employee->save();

            $employee->notify(new ContractTerminationNotification($contract));

            return response()->json(['message'=>'Contract terminated successfully!']);
        }
    }

    /**
     * Change Agreement Status
     *
     * @param Request $request  Request
     * @param Team    $company  Comoany
     * @param User    $employee Employee
     *
     * @return \Illuminate\Http\Response
     */
    public function changeAgreementStatus(Request $request, Team $company, User $employee)
    {
        if ($request->ajax()) {
            $contract = EmployeeContract::find($request->contract_id);
            $contract->agreement_status = "published";
            $contract->save();
            $employee->notify(new NewContractNotification($contract));
            return response()->json(['message'=> 'Contract status changed!']);
        }
    }
}
