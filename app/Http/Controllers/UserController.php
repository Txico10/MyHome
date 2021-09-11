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
use App\Models\User;
use App\Rules\Checkpassword;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
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
        if (Auth::id()!= $user->id) {
            abort(403);
        }

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

        return redirect()->route('user.profile', [$user])
            ->with('message', 'Password changed successfully!!!');

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
}
