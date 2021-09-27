<?php
/**
 * Users Controller
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

use App\Http\Requests\UserRequest;
use App\Models\EmployeeContract;
use App\Models\Role;
use App\Models\User;
use App\Rules\Checkpassword;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManagerStatic as Image;

/**
 *  Users Controller class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class UserController extends Controller
{
    /**
     * List of users
     *
     * @param Request $request request
     *
     * @return void
     */
    public function index(Request $request)
    {
        $users = User::with('roles', 'rolesTeams')->WithLastLoginDate()->get();
        //$teams = $user[2]->rolesTeams;
        //dd($user);

        return view('admin.users', ['users'=>$users]);
    }

    /**
     * Profile
     *
     * @param User $user User
     *
     * @return view
     */
    public function profile(User $user)
    {

        $user = $user->load('addresses', 'contacts');
        return view('users.profile', ['user' => $user]);
    }


    /**
     * Update password
     *
     * @param Request $request Request
     * @param User    $user    User
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function updatepasswd(Request $request, User $user)
    {
        $request->validate(
            [
                'old_password' => [
                    'bail',
                    'required',
                    'string',
                    'min:6',
                    new Checkpassword($user->password),
                ],
                'new_password' => [
                    'required','string','min:8','different:old_password'
                ],
                'new_password_confirmation' => [
                    'required','string','min:8','same:new_password'
                ],
            ]
        );

        $user->password = bcrypt($request->new_password);
        $user->save();

        activity('user_log')
            ->performedOn($user)
            ->causedBy(Auth::user())
            ->log("User password has been updated");

        //return redirect()->route('user.profile', [$user])
        //    ->with('message', 'Password changed successfully!!!');
        return back()
            ->with('message', 'Password changed successfully!!!')
            ->withInput(['tab'=>'reset_password']);

    }

    /**
     * Photo Upload
     *
     * @param Request $request Request
     * @param User    $user    User
     *
     * @return Illuminate\Http\Response
     */
    public function updatephoto(Request $request, User $user)
    {
        if ($request->ajax()) {
            $request->validate(
                ['file' => 'bail|image|mimes:png,jpg,jpeg,gif,svg|max:2048']
            );

            if ($request->file('file')) {

                $file_name = Str::random().time().'.'.$request->file('file')
                    ->extension();

                if ($user->photo) {
                    Storage::delete('public/images/profile/users/'.$user->photo);
                }

                $request->file('file')
                    ->storeAs('public/images/profile/users', $file_name);

                $user->photo = $file_name;

                Image::make('storage/images/profile/users/'.$file_name)
                    ->fit(200)
                    ->save('storage/images/profile/users/'.$file_name);

                $user->save();

                activity('user_log')
                    ->performedOn($user)
                    ->causedBy(Auth::user())
                    ->log("User photo has been updated");

            }

            return response()->json(['message'=>'Photo updated successfully'], 200);
        }
        return null;
    }

    /**
     * Edit user data
     *
     * @param Request $request Request
     * @param User    $user    User
     *
     * @return Illuminate\Http\Response
     */
    public function edit(Request $request, User $user)
    {
        if ($request->ajax()) {
            return response()->json(['user'=>$user], 200);
        }
        return null;
    }

    /**
     * Update
     *
     * @param Request $request Request
     * @param User    $user    User
     *
     * @return Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        if ($request->ajax()) {

            $user->name = $request->name;
            if (strcmp($user->email, $request->email)!=0) {
                $user->email = $request->email;
                $user->email_verified_at = null;
            }
            $user->birthdate = $request->birthdate;
            $user->gender = $request->gender;
            if ($user->ssn != $request->ssn) {
                $user->ssn = $request->ssn;
            }


            $user->save();

            if ($user->email_verified_at == null) {
                event(new Registered($user));
            }

            return response()
                ->json(['message'=>'Profile updated successfully'], 200);
        }
        return null;
    }

    /**
     * Contracts
     *
     * @param Request $request Request
     * @param User    $user    User
     *
     * @return Illuminate\Http\Response
     */
    public function contracts(Request $request, User $user)
    {
        if ($request->ajax()) {
            if ($user->employees->isNotEmpty()) {
                $contracts = $user->employees
                    ->filter(
                        function ($contract, $key) {
                            if (strcmp($contract->agreement_status, 'unavailable')
                                && strcmp($contract->agreement_status, 'pending')
                            ) {
                                if (strcmp($contract->agreement_status, 'terminated') || $contract->acceptance_at!=null) {
                                    return $contract;
                                }
                            }
                        }
                    )
                    ->sortByDesc('created_at');

                $roles = Role::all();
                $companies = $user->companies;

                return datatables()->of($contracts)
                    ->addIndexColumn()
                    ->addColumn(
                        'code',
                        function ($contract) use ($companies) {
                            $company = $companies->where('id', $contract->pivot->team_id)->first();
                            return "HR".$company->id.$contract->id.$contract->role_id;
                        }
                    )
                    ->addColumn(
                        'company',
                        function ($contract) use ($companies) {
                            return $companies->where('id', $contract->pivot->team_id)->first()->display_name;
                        }
                    )
                    ->addColumn(
                        'role',
                        function ($contract) use ($roles) {
                            $role_display_name = $roles->where('id', $contract->role_id)
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
                            case 'refused':
                                $tag_color = 'bg-danger';
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
                        function ($contract) use ($companies, $user) {
                            $company = $companies->where('id', $contract->pivot->team_id)->first();
                            $btn = '<nobr>';
                            $btn = $btn.'<a class="btn btn-outline-primary btn-sm mx-1 shadow" type="button" title="More details" href="'.route('company.employees.contract.show', ['company'=>$company, 'employee'=>$user, 'contract'=> $contract]).'"><i class="fas fa-search fa-fw"></i></a>';
                            if (strcmp($contract->agreement_status, 'published')==0) {
                                $btn =$btn.'<button class="btn btn-sm btn-outline-danger mx-1 shadow contractSignature" type="button" title="contract  signature" value="'.$contract->id.'"><i class="fas fa-file-signature"></i></button>';
                            }
                            if (strcmp($contract->agreement_status, 'accepted')==0) {
                                $btn =$btn.'<button class="btn btn-sm btn-outline-danger mx-1 shadow" value="'.$contract->id.'" type="button"   title="greement" ><i class="fas fa-file-pdf"></i></button>';
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
        return null;
    }

    /**
     * Contract Signature
     *
     * @param Request $request Request
     * @param User    $user    User
     *
     * @return Illuminate\Http\Response
     */
    public function contractSignature(Request $request, User $user)
    {
        if ($request->ajax()) {

            $request->validate(
                [
                    'contract_id'=> ['required', 'exists:employee_contracts,id'],
                    'agreement_status'=> ['required', Rule::in(['accepted', 'refused'])],
                    'signaturePassword' => [
                        'required',
                        new Checkpassword($user->password)
                    ],
                    'conditionsTermsCheck'=>['required', 'accepted'],
                    'checkboxAcceptDate'=>['required', 'accepted'],
                ],
            );

            $contract = EmployeeContract::find($request->contract_id);
            $contract->agreement_status=$request->agreement_status;
            $contract->acceptance_at = now();

            $contract->save();

            if (session('companyID')==null) {
                session(['companyID' => $user->active_company]);
            }

            return response()->json(['message'=>'Contract signed successfully']);
        }
    }
}
