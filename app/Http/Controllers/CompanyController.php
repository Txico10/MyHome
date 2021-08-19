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

use Illuminate\Http\Request;
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
     * @return View
     */
    public function index()
    {
        return view('admin.clients');
    }
}
