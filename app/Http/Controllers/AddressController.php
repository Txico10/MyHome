<?php
/**
 * Address Controller
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

use App\Models\Address;
use Illuminate\Http\Request;
/**
 *  Address Controller class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class AddressController extends Controller
{
    /**
     * Index
     *
     * @param Address $address Address
     *
     * @return view
     */
    public function index(Address $address)
    {

    }
}
