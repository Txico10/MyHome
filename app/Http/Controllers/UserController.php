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

use Illuminate\Http\Request;
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
     * Profile
     *
     * @return void
     */
    public function profile()
    {
        return view('users.profile');
    }
}
