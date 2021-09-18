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

use App\Http\Controllers\BenefitsSettingController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\EmployeeController;
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
Route::middleware(['auth', 'verified', 'personal'])->prefix('users/{user}')->name('user.')
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
            Route::get('/contracts', [UserController::class,'contracts'])
                ->name('contracts');
            Route::post('/contracts', [UserController::class,'contractSignature'])
                ->name('contracts.signed');
        }
    );
/**
 * Admin Routes
 */
Route::middleware(['auth','verified','role:superadministrator'])
    ->name('admin.')->prefix('/admin')->group(
        function () {
            Route::get(
                '/',
                function () {
                    return view('admin.admin');
                }
            )->name('index');
            //users
            Route::get('/users', [UserController::class,'index'])
                ->name('users');
            //roles
            Route::get('/roles', [RoleController::class,'index'])
                ->middleware('permission:roles-read')
                ->name('roles');
            Route::post('/roles', [RoleController::class,'store'])
                ->middleware('permission:roles-create|roles-update')
                ->name('roles.store');
            Route::get('/roles/{role}', [RoleController::class,'show'])
                ->middleware('permission:roles-read')
                ->name('roles.show');
            Route::get('/roles/{id}/edit', [RoleController::class,'edit'])
                ->middleware('permission:roles-update')
                ->name('roles.edit');
            Route::delete('/roles/{id}', [RoleController::class,'destroy'])
                ->middleware('permission:roles-delete')
                ->name('roles.destroy');
            Route::post('/roles/{role}/permission', [RoleController::class, 'permissionUpdate'])
                ->name('roles.permission');
            //permissions
            Route::get('/permissions', [PermissionController::class, 'index'])
                ->name('permissions');
            Route::post('/permissions', [PermissionController::class, 'store'])
                ->middleware('permission:permissions-create|permissions-update')
                ->name('permissions.store');
            Route::get('/permissions/{permission}', [PermissionController::class, 'show'])
                ->middleware('permission:permissions-read')
                ->name('permissions.show');
            Route::get('/permissions/{id}/edit', [PermissionController::class,'edit'])
                ->middleware('permission:permissions-update')
                ->name('permissions.edit');
            Route::delete('/permissions/{id}', [PermissionController::class,'destroy'])
                ->middleware('permission:permissions-delete')
                ->name('permissions.destroy');
            Route::post('/permissions/{permission}/detachuser', [PermissionController::class,'detachUser'])
                ->name('permissions.detachuser');
            //clients
            Route::get('/clients', [CompanyController::class,'index'])
                ->middleware('permission:clients-create')
                ->name('clients.index');
            Route::get('/clients/create', [CompanyController::class,'create'])
                ->middleware('permission:clients-create')
                ->name('clients.create');
            Route::post('/clients', [CompanyController::class, 'store'])
                ->middleware('permission:clients-create')
                ->name('clients.store');
        }
    );

Route::get('/companies/{company:slug}', [CompanyController::class, 'show'])
    ->middleware(['auth','verified','permission:clients-read|company-read'])
    ->name('company.show');
Route::get('/companies/{company}/edit', [CompanyController::class, 'edit'])
    ->middleware(['auth','verified','permission:clients-update|company-update'])
    ->name('company.edit');
Route::put('/companies/{company}', [CompanyController::class, 'update'])
    ->middleware(['auth','verified','permission:clients-update|company-update'])
    ->name('company.update');
Route::delete('/company/{id}', [CompanyController::class, 'destroy'])
    ->middleware(['auth','verified','permission:clients-delete'])
    ->name('company.destroy');
Route::post('/companies/{company}/logoupload', [CompanyController::class, 'logoupdate'])
    ->middleware(['auth','verified','permission:clients-update||company-update'])
    ->name('company.logoupdate');


/**
 * Company Settings
 *
 * Benefits Settings
 */
Route::get('/companies/{company:slug}/benefits-setting', [BenefitsSettingController::class, 'index'])
    ->middleware(['auth','verified','permission:benefitsSetting-read'])
    ->name('company.benefits-setting');
Route::post('/companies/{company}/benefits-setting', [BenefitsSettingController::class, 'store'])
    ->middleware(
        [
            'auth','verified',
            'permission:benefitsSetting-create|benefitsSetting-update'
        ]
    )
    ->name('company.benefits-setting.store');
Route::get('/companies/{company}/benefits-edit', [BenefitsSettingController::class, 'edit'])
    ->middleware(['auth','verified','permission:benefitsSetting-update'])
    ->name('company.benefits-setting.edit');
Route::delete('/companies/{company}/benefits-delete', [BenefitsSettingController::class, 'destroy'])
    ->middleware(['auth','verified','permission:benefitsSetting-delete'])
    ->name('company.benefits-setting.delete');
/**
 * Contract Settings
 */

 /**
  * Employees CRUD
  */
Route::get('/companies/{company:slug}/employees', [EmployeeController::class, 'index'])
    ->middleware(['auth','verified','permission:employee-read'])
    ->name('company.employees');
Route::get('/companies/{company:slug}/employees/create', [EmployeeController::class, 'create'])
    ->middleware(['auth','verified','permission:employee-create'])
    ->name('company.employees.create');
Route::post('/companies/{company:slug}/employees', [EmployeeController::class, 'store'])
    ->middleware(['auth','verified','permission:employee-create|employee-update'])
    ->name('company.employees.store');
Route::get('/companies/{company:slug}/employees/{employee}', [EmployeeController::class, 'show'])
    ->middleware(['auth','verified','permission:employee-read'])
    ->name('company.employees.show');
Route::get('/companies/{company:slug}/employees/{employee}/edit', [EmployeeController::class, 'edit'])
    ->middleware(['auth','verified','permission:employee-update'])
    ->name('company.employees.edit');
Route::put('/companies/{company:slug}/employees/{employee}', [EmployeeController::class, 'update'])
    ->middleware(['auth','verified','permission:employee-update'])
    ->name('company.employees.update');
Route::delete('/companies/{company:slug}/employees/{employee}', [EmployeeController::class, 'destroy'])
    ->middleware(['auth','verified','permission:employee-delete'])
    ->name('company.employees.delete');

/**
 * Company Employee Contract CRUD
 */
Route::get('/companies/{company:slug}/employees/{employee}/contracts', [ContractController::class, 'index'])
    ->middleware(['auth','verified','permission:contract-read'])
    ->name('company.employees.contract');
//contract Create
//Contract Store
Route::get('/companies/{company:slug}/employees/{employee}/contracts/{contract}', [ContractController::class, 'show'])
    ->middleware(['auth','verified','permission:contract-read'])
    ->name('company.employees.contract.show');
Route::get('/companies/{company:slug}/employees/{employee}/contracts/{contract}/edit', [ContractController::class, 'edit'])
    ->middleware(['auth','verified','permission:contract-update'])
    ->name('company.employees.contract.edit');
Route::patch('/companies/{company:slug}/employees/{employee}/contracts/{contract}', [ContractController::class, 'update'])
    ->middleware(['auth','verified','permission:contract-update'])
    ->name('company.employees.contract.update');
Route::delete('/companies/{company:slug}/employees/{employee}/contracts/delete', [ContractController::class, 'destroy'])
    ->middleware(['auth','verified','permission:contract-delete'])
    ->name('company.employees.contract.delete');
Route::post('/companies/{company:slug}/employees/{employee}/contracts/change-status', [ContractController::class, 'changeAgreementStatus'])
    ->middleware(['auth','verified','permission:contract-update'])
    ->name('company.employees.contract.change-status');
