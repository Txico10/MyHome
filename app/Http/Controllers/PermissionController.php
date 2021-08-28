<?php
/**
 * Permission Controller
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

use App\Models\Permission;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Laratrust\LaratrustFacade;

/**
 * Permision Controller class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class PermissionController extends Controller
{
    /**
     * Index
     *
     * @param Request $request users request
     *
     * @return void
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $permissions = Permission::all()->sortBy('name', SORT_NATURAL);

            return datatables()->of($permissions)
                ->addIndexColumn()
                ->editColumn(
                    'updated_at',
                    function ($request) {
                        return $request->updated_at->format('d F Y');
                    }
                )
                ->addColumn(
                    'action',
                    function ($permission) {
                        $btn = '<a class="btn btn-outline-primary btn-sm mx-1 shadow" type="button" title="More details" href="'.route('admin.permissions.show', ['permission'=>$permission]).'"><i class="fas fa-info-circle fa-fw"></i></a>';
                        if (LaratrustFacade::isAbleTo('permissions-update')) {
                            $btn = $btn.'<button class="btn btn-outline-secondary mx-1 shadow btn-sm editPermissionButton" type="button" title="Edit permission" value="'.$permission->id.'"><i class="fas fa-pencil-alt fa-fw"></i></button>';
                        }
                        if (LaratrustFacade::isAbleTo('permissions-delete')) {
                            $btn = $btn.'<button class="btn btn-outline-danger mx-1 shadow btn-sm deletePermissionButton" title="Delete permission" type="button" value="'.$permission->id.'"><i class="fas fa-trash-alt fa-fw"></i></button>';
                        }
                        return $btn;
                    }
                )
                ->removeColumn('id')
                ->removeColumn('created_at')
                ->make();
        }

        return view('admin.permissions');
    }

    /**
     * Store
     *
     * @param mixed $request Request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'permission_id' => ['nullable', 'numeric', 'exists:permissions,id'],
                'permission_name' => ['required', 'alpha_dash', 'min:5','max:255'],
                'permission_display_name' => ['required', 'string', 'min:5','max:255'],
                'permission_description'=> ['required', 'string', 'min:5'],
            ]
        );
        $permission = Permission::updateOrCreate(
            ['id' => $request->permission_id],
            [
                'name' => $request->permission_name,
                'display_name' => $request->permission_display_name,
                'description' => $request->permission_description
            ]
        );

        return redirect()->route('admin.permissions')
            ->with('success', 'Permission saved successfully');
    }

    /**
     * Show
     *
     * @param Request    $request    Request
     * @param Permission $permission Permission
     *
     * @return View|Ajax
     */
    public function show(Request $request, Permission $permission)
    {
        if ($request->ajax()) {
            $companies = Team::all();
            $users = $permission->users;
            return datatables()->of($users)
                ->addIndexColumn()
                ->addColumn(
                    'companies',
                    function ($user) use ($companies) {
                        $company = $companies
                            ->where('id', $user->pivot->team_id)
                            ->first();
                        return $company->display_name??'';
                    }
                )
                ->addColumn(
                    'action',
                    function ($user) use ($companies) {
                        $company = $companies
                            ->where('id', $user->pivot->team_id)
                            ->first();
                        $btn = '<button class="btn btn-outline-danger mx-1 shadow btn-sm detachUserPermissionButton" title="Detach permission" type="button" value="'.$user->id.'" data-company="'.$company->id.'"><i class="fas fa-trash-alt fa-fw"></i></button>';
                        return $btn;
                    }
                )
                ->removeColumn('id')
                ->removeColumn('birthdate')
                ->removeColumn('gender')
                ->removeColumn('ssn')
                ->removeColumn('email_verified_at')
                ->removeColumn('password')
                ->removeColumn('active')
                ->removeColumn('photo')
                ->removeColumn('remember_token')
                ->removeColumn('created_at')
                ->removeColumn('updated_at')
                ->make();
        }
        return view('admin.permissions-show', ['permission'=> $permission]);
    }

    /**
     * Edit
     *
     * @param Request $request Request
     * @param int     $id      Permission id
     *
     * @return null|Response
     */
    public function edit(Request $request, int $id)
    {
        if ($request->ajax()) {
            $permission = Permission::findOrFail($id);
            return response()->json(['permission'=>$permission], 200);
        }
        return null;
    }

    /**
     * Destroy
     *
     * @param Request $request Request
     * @param int     $id      Permission id
     *
     * @return null|Response
     */
    public function destroy(Request $request, int $id)
    {
        if ($request->ajax()) {
            $permission = Permission::findOrFail($id);

            $permission->roles->each(
                function ($role) use ($permission) {
                    $role->detachPermission($permission);
                }
            );
            $permission->users->each(
                function ($user) use ($permission) {
                    $user->detachPermission($permission);
                }
            );

            $permission->delete();

            return response()->json(['message'=>'Permission deleted successfully ']);
        }
        return null;
    }

    /**
     * Detach User permission
     *
     * @param Request    $request    Request
     * @param Permission $permission Permission
     *
     * @return Response
     */
    public function detachUser(Request $request, Permission $permission)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'user_id' => ['required', 'numeric', 'exists:users,id'],
                    'company_id' => ['required', 'numeric', 'exists:teams,id']
                ]
            );

            $user = User::find($request->user_id);
            $user->detachPermission($permission, $request->company_id);

            return response()->json(['message'=>'permission detached successfully'], 200);
        }
        return null;
    }

}
