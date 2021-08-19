<?php
/**
 * Route
 *
 * PHP version 7.4
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
require __DIR__.'/auth.php';

Route::get(
    '/',
    function () {
        return view('welcome');
    }
);

Route::get(
    '/dashboard',
    function () {
        return view('home');
    }
)->middleware(['auth', 'verified'])->name('dashboard');

/**
 * Users Profile Routes
 */
Route::middleware(['auth', 'verified'])->prefix('users/{user}')->name('user.')
    ->group(
        function () {
            Route::get('/profile', [UserController::class,'profile'])
                ->name('profile');
            Route::post('/updatepasswd', [UserController::class,'updatepasswd'])
                ->name('updatepasswd');
            Route::post('/photo-upload', [UserController::class,'updatephoto'])
                ->name('photo.store');
            Route::get('/edit', [UserController::class,'edit'])
                ->name('edit');
            Route::patch('/', [UserController::class,'update'])
                ->name('update');
        }
    );
/**
 * Admin Routes
 */
Route::middleware(['auth','verified','role:superadministrator'])
    ->name('admin.')->prefix('/admin')->group(
        function () {
            Route::get('/users', [UserController::class,'index'])
                ->name('users');
            Route::get('/roles', [RoleController::class,'index'])
                ->name('roles');
            Route::get('/permissions', [PermissionController::class, 'index'])
                ->name('permissions');
            Route::get('/clients', [CompanyController::class,'index'])
                ->name('clients');
        }
    );
