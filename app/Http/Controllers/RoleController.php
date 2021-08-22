<?php
/**
 * Role Controller
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
use App\Models\Role;
use App\Models\Team;
use Illuminate\Http\Request;
use Laratrust\LaratrustFacade;

/**
 * Role Controller class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class RoleController extends Controller
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

            $roles = Role::all();

            return datatables()->of($roles)
                ->addIndexColumn()
                ->editColumn(
                    'updated_at',
                    function ($request) {
                        return $request->updated_at->format('d F Y');
                    }
                )
                ->addColumn(
                    'action',
                    function ($role) {
                        $btn = '<a class="btn btn-outline-primary btn-sm mx-1 shadow" type="button" title="More details" href="'.route('admin.roles.show', ['role'=>$role]).'"><i class="fas fa-info-circle fa-fw"></i></a>';
                        if (LaratrustFacade::isAbleTo('roles-update')) {
                            $btn = $btn.'<button class="btn btn-outline-secondary mx-1 shadow btn-sm editRoleButton" type="button" title="Edit role" value="'.$role->id.'"><i class="fas fa-pencil-alt fa-fw"></i></button>';
                        }
                        if (LaratrustFacade::isAbleTo('roles-delete')) {
                            $btn = $btn.'<button class="btn btn-outline-danger mx-1 shadow btn-sm deleteRoleButton" title="Delete role" type="button" value="'.$role->id.'"><i class="fas fa-trash-alt fa-fw"></i></button>';
                        }
                        return $btn;
                    }
                )
                ->removeColumn('id')
                ->removeColumn('created_at')
                ->make();
        }
        return view('admin.roles', ['permissions' => Permission::pluck('display_name', 'id')]);
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
                'role_id' => ['nullable', 'numeric', 'exists:roles,id'],
                'role_name' => ['required', 'alpha_dash', 'min:5','max:255'],
                'role_display_name' => ['required', 'string', 'min:5','max:255'],
                'role_description'=> ['required', 'string', 'min:5'],
            ]
        );

        $role = Role::updateOrCreate(
            ['id'=>$request->role_id],
            [
                'name' => $request->role_name,
                'display_name' => $request->role_display_name,
                'description' => $request->role_description,
            ]
        );

        return redirect()->route('admin.roles')
            ->with('success', 'Role saved successfully');
    }

    /**
     * Show
     *
     * @param mixed $request Request
     * @param Role  $role    Role
     *
     * @return View|Ajax
     */
    public function show(Request $request, Role $role)
    {
        //$role = $role->load('permissions');
        $role_permissions = $role->permissions;
        $permissions = Permission::get(['id', 'name','display_name'])
            ->sortBy('name', SORT_NATURAL);
        $permissions = $permissions->map(
            function ($item, $key) use ($role_permissions) {
                //dd($item);
                if ($role_permissions->contains($item->id)) {
                    $item['selected'] = 1;
                } else {
                    $item['selected'] = 0;
                }
                return $item;
            }
        );

        if ($request->ajax()) {
            $users = $role->users;
            //$users = $users->load('companies');
            $companies = Team::all();
            return datatables()->of($users)
                ->addIndexColumn()
                ->addColumn(
                    'companies',
                    function ($user) use ($companies) {
                        $company = $companies
                            ->where('id', $user->pivot->team_id)
                            ->first();
                        //$tag = '<span class="badge bg-primary">'.$name.'</span>';
                        return $company->display_name??'';
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

        return view('admin.roles-show', ['role'=>$role, 'permissions'=>$permissions]);
    }

    /**
     * Edit
     *
     * @param Request $request Request
     * @param int     $id      ID
     *
     * @return null|Response
     */
    public function edit(Request $request, int $id)
    {
        if ($request->ajax()) {
            $role = Role::findOrFail($id);

            return response()->json(['role'=>$role], 200);
        }

        return null;
    }

    /**
     * Destroy
     *
     * @param Request $request Request
     * @param int     $id      Role id
     *
     * @return null|Response
     */
    public function destroy(Request $request, int $id)
    {
        if ($request->ajax()) {

            $role = Role::findOrFail($id);
            $permissions =$role->permissions;

            if ($permissions->isNotEmpty()) {
                $role->detachPermissions($permissions);
            }

            $role->delete();

            return response()->json(['message'=>'Role deleted successfully', 200]);
        }
        return null;
    }

    /**
     * Rermission Update
     *
     * @param Request $request Request
     * @param Role    $role    Role
     *
     * @return void
     */
    public function permissionUpdate(Request $request, Role $role)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'permission_id'=>['required', 'numeric','exists:permissions,id'],
                    'selected' => ['required', 'boolean']
                ]
            );
            //$selected = 0;

            if ($request->selected) {
                $role->detachPermission($request->permission_id);
                $selected = 0;
            } else {
                $role->attachPermission($request->permission_id);
                $selected = 1;
            }

            return response()->json(['message'=>'Permission successfully updated!', 'selected'=>$selected], 200);
        }
    }
}
