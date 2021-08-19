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

use Illuminate\Http\Request;
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
     * @return void
     */
    public function index()
    {
        return view('admin.roles');
    }
}
