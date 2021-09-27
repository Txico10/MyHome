<?php
/**
 * Ensure Right Company Middleware
 *
 * PHP version 7.4
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laratrust\LaratrustFacade;

/**
 *  Ensure Right Company Middleware
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class EnsureRightCompany
{
    /**
     * Handle an incoming request.

     * @param Request  $request Request
     * @param \Closure $next    next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!LaratrustFacade::hasRole('superadministrator')) {
            if ($request->session()->get('companyID') != $request->route('company')->id) {
                return abort('403');
            }
        }

        return $next($request);
    }
}
