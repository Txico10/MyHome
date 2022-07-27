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

use App\Http\Controllers\AccessoryController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\BenefitsSettingController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DependencyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaseController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Barryvdh\DomPDF\Facade as PDF;
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
            Route::get('/bails', [UserController::class,'bails'])
                ->name('bails');
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
    ->middleware(['auth','verified','company.check','permission:clients-read|company-read'])
    ->name('company.show');
Route::get('/companies/{company}/edit', [CompanyController::class, 'edit'])
    ->middleware(['auth','verified','company.check','permission:clients-update|company-update'])
    ->name('company.edit');
Route::put('/companies/{company}', [CompanyController::class, 'update'])
    ->middleware(['auth','verified','company.check','permission:clients-update|company-update'])
    ->name('company.update');
Route::delete('/company/{id}', [CompanyController::class, 'destroy'])
    ->middleware(['auth','verified','company.check','permission:clients-delete'])
    ->name('company.destroy');
Route::post('/companies/{company}/logoupload', [CompanyController::class, 'logoupdate'])
    ->middleware(['auth','verified','company.check','permission:clients-update||company-update'])
    ->name('company.logoupdate');


/**
 * Company Settings
 *
 * Benefits Settings
 */
Route::get('/companies/{company:slug}/benefits-setting', [BenefitsSettingController::class, 'index'])
    ->middleware(['auth','verified','company.check','permission:benefitsSetting-read'])
    ->name('company.benefits-setting');
Route::post('/companies/{company:slug}/benefits-setting', [BenefitsSettingController::class, 'store'])
    ->middleware(
        [
            'auth','verified','company.check',
            'permission:benefitsSetting-create|benefitsSetting-update'
        ]
    )
    ->name('company.benefits-setting.store');
Route::get('/companies/{company:slug}/benefits-edit', [BenefitsSettingController::class, 'edit'])
    ->middleware(['auth','verified','company.check','permission:benefitsSetting-update'])
    ->name('company.benefits-setting.edit');
Route::delete('/companies/{company:slug}/benefits-delete', [BenefitsSettingController::class, 'destroy'])
    ->middleware(['auth','verified','company.check','permission:benefitsSetting-delete'])
    ->name('company.benefits-setting.delete');
/**
 * Contract Settings
 */

/**
 * Address management
 */
Route::put('/address/{id}', [AddressController::class, 'update'])
    ->middleware(['auth','verified'])
    ->name('address.update');
Route::get('/address/getCities', [AddressController::class, 'getCities'])
    ->middleware(['auth','verified'])
    ->name('address.getCities');
Route::get('/address/getRegion', [AddressController::class, 'getRegion'])
    ->middleware(['auth','verified'])
    ->name('address.getRegion');
 /**
  * Employees CRUD
  */
Route::get('/companies/{company:slug}/employees', [EmployeeController::class, 'index'])
    ->middleware(['auth','verified','company.check','permission:employee-read'])
    ->name('company.employees');
Route::get('/companies/{company:slug}/employees/create', [EmployeeController::class, 'create'])
    ->middleware(['auth','verified','company.check','permission:employee-create'])
    ->name('company.employees.create');
Route::post('/companies/{company:slug}/employees', [EmployeeController::class, 'store'])
    ->middleware(['auth','verified','company.check','permission:employee-create|employee-update'])
    ->name('company.employees.store');
Route::get('/companies/{company:slug}/employees/{employee}', [EmployeeController::class, 'show'])
    ->middleware(['auth','verified','company.check','permission:employee-read'])
    ->name('company.employees.show');
Route::get('/companies/{company:slug}/employees/{employee}/edit', [EmployeeController::class, 'edit'])
    ->middleware(['auth','verified','company.check','permission:employee-update'])
    ->name('company.employees.edit');
Route::put('/companies/{company:slug}/employees/{employee}', [EmployeeController::class, 'update'])
    ->middleware(['auth','verified','company.check','permission:employee-update'])
    ->name('company.employees.update');
Route::delete('/companies/{company:slug}/employees/{employee}', [EmployeeController::class, 'destroy'])
    ->middleware(['auth','verified','company.check','permission:employee-delete'])
    ->name('company.employees.delete');

/**
 * Company Employee Contract CRUD
 */
Route::get('/companies/{company:slug}/employees/{employee}/contracts', [ContractController::class, 'index'])
    ->middleware(['auth','verified','company.check','permission:contract-read'])
    ->name('company.employees.contract');
//contract Create
//Contract Store
Route::get('/companies/{company:slug}/employees/{employee}/contracts/{contract}', [ContractController::class, 'show'])
    ->middleware(['auth','verified','company.check','permission:contract-read'])
    ->name('company.employees.contract.show');
Route::get('/companies/{company:slug}/employees/{employee}/contracts/{contract}/edit', [ContractController::class, 'edit'])
    ->middleware(['auth','verified','company.check','permission:contract-update'])
    ->name('company.employees.contract.edit');
Route::patch('/companies/{company:slug}/employees/{employee}/contracts/{contract}', [ContractController::class, 'update'])
    ->middleware(['auth','verified','company.check','permission:contract-update'])
    ->name('company.employees.contract.update');
Route::delete('/companies/{company:slug}/employees/{employee}/contracts/delete', [ContractController::class, 'destroy'])
    ->middleware(['auth','verified','company.check','permission:contract-delete'])
    ->name('company.employees.contract.delete');
Route::post('/companies/{company:slug}/employees/{employee}/contracts/change-status', [ContractController::class, 'changeAgreementStatus'])
    ->middleware(['auth','verified','company.check','permission:contract-update'])
    ->name('company.employees.contract.change-status');
/**
 * Building CRUD
 */
Route::get('/companies/{company:slug}/buildings', [BuildingController::class, 'index'])
    ->middleware(['auth','verified','company.check','permission:building-read'])
    ->name('company.buildings');
Route::post('/companies/{company:slug}/buildings', [BuildingController::class, 'store'])
    ->middleware(['auth','verified','permission:building-create'])
    ->name('company.building.store');
Route::get('/companies/{company:slug}/buildings/{building}', [BuildingController::class, 'show'])
    ->middleware(['auth','verified','company.check','permission:building-read'])
    ->name('company.building.show');
Route::get('/companies/{company:slug}/buildings/{building}/edit', [BuildingController::class, 'edit'])
    ->middleware(['auth','verified','company.check','permission:building-update'])
    ->name('company.building.edit');
Route::put('/companies/{company:slug}/buildings/{building}', [BuildingController::class, 'update'])
    ->middleware(['auth','verified','company.check','permission:building-update'])
    ->name('company.building.update');
Route::delete('/companies/{company:slug}/buildings/delete', [BuildingController::class, 'destroy'])
    ->middleware(['auth','verified','company.check','permission:building-delete'])
    ->name('company.building.delete');
Route::get('/companies/{company:slug}/buildings/{building}/getAddress', [BuildingController::class, 'getAddress'])
    ->middleware(['auth','verified','company.check'])
    ->name('company.building.getAddress');
/**
 * Apartment CRUD
 */
Route::get('/companies/{company:slug}/apartments', [ApartmentController::class, 'index'])
    ->middleware(['auth','verified','company.check','permission:apartment-read'])
    ->name('company.apartments');
//create
//store
Route::post('/companies/{company:slug}/apartments', [ApartmentController::class, 'store'])
    ->middleware(['auth','verified','company.check','permission:apartment-create|apartment-update'])
    ->name('company.apartment.store');
Route::get('/companies/{company:slug}/apartments/{apartment}', [ApartmentController::class, 'show'])
    ->middleware(['auth','verified','company.check','permission:apartment-read'])
    ->name('company.apartment.show');
Route::get('/companies/{company:slug}/apartments/{apartment}/edit', [ApartmentController::class, 'edit'])
    ->middleware(['auth','verified','company.check','permission:apartment-update'])
    ->name('company.apartment.edit');
 /**
  * Dependency CRUD
  */
Route::post('/companies/{company:slug}/buildings/{building}/dependencies', [DependencyController::class, 'store'])
    ->middleware(['auth','verified','company.check','permission:dependency-create|dependency-update'])
    ->name('company.dependency.store');
Route::get('/companies/{company:slug}/dependenties/edit', [DependencyController::class, 'edit'])
    ->middleware(['auth','verified','company.check','permission:dependency-update'])
    ->name('company.dependency.edit');
Route::get('/companies/{company:slug}/buildings/{building}/getdependencies', [DependencyController::class, 'getBuildingDependencies'])
    ->middleware(['auth','verified','company.check','permission:dependency-read'])
    ->name('company.building.getdependencies');
/**
 * Accessory CRUD
 */
Route::get('/companies/{company:slug}/accessories', [AccessoryController::class, 'index'])
    ->middleware(['auth','verified','company.check','permission:accessory-read'])
    ->name('company.accessories');
Route::post('/companies/{company:slug}/accessories', [AccessoryController::class, 'store'])
    ->middleware(['auth','verified','company.check','permission:accessory-create|accessory-update'])
    ->name('company.accessories.store');
Route::get('/companies/{company:slug}/accessories/{accessory}', [AccessoryController::class, 'show'])
    ->middleware(['auth','verified','company.check','permission:accessory-read'])
    ->name('company.accessory.show');
Route::get('/companies/{company:slug}/accessories/{accessory}/edit', [AccessoryController::class, 'edit'])
    ->middleware(['auth','verified','company.check','permission:accessory-update'])
    ->name('company.accessory.edit');

/**
 * Lease CRUD
 */
Route::get('/companies/{company:slug}/leases', [LeaseController::class, 'index'])
    ->middleware(['auth','verified','company.check','permission:lease-read'])
    ->name('company.leases');
Route::get('/companies/{company:slug}/leases/create', [LeaseController::class, 'create'])
    ->middleware(['auth','verified','company.check','permission:lease-create'])
    ->name('company.lease.create');
Route::get('/companies/{company:slug}/leases/{lease}', [LeaseController::class, 'show'])
    ->middleware(['auth','verified','company.check','permission:lease-read'])
    ->name('company.lease.show');
Route::get('/companies/{company:slug}/leases/{lease}/download', [LeaseController::class, 'downloadPDF'])
    ->middleware(['auth','verified','company.check'])
    ->name('company.lease.download');
//PDF Test
Route::get(
    'print/invoice',
    function () {
        //return view('companies.dompdf.lease-report');
        //return view('companies.forprint.invoice');
        //$pdf = PDF::loadView('companies.forprint.biglist');
        //$pdf->getDomPDF()->set_option("enable_php", true);
        $pdf = PDF::loadView('companies.forprint.biglist');
        //$pdf = PDF::loadView('companies.dompdf.lease-report');
        return $pdf->download('lease.pdf');
        //$pdf = App::make('dompdf.wrapper');
        //$pdf->getDomPDF()->set_option("enable_php", true);
        //$pdf->loadView('companies.forprint.biglist');
        //return $pdf->stream();

    }
);
