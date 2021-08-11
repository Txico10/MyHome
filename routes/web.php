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
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Str;

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

Route::get('/users/{user}/profile', [UserController::class,'profile'])
    ->middleware(['auth', 'verified'])->name('user.profile');
Route::post('/users/{user}/updatepasswd', [UserController::class,'updatepasswd'])
    ->middleware(['auth', 'verified'])->name('user.updatepasswd');
Route::post('/users/{user}/photo-upload', [UserController::class,'updatephoto'])
    ->middleware(['auth', 'verified'])->name('user.photo.store');
Route::get('/users/{user}/edit', [UserController::class,'edit'])
    ->middleware(['auth', 'verified'])->name('user.edit');
Route::patch('/users/{user}', [UserController::class,'update'])
    ->middleware(['auth', 'verified'])->name('user.update');
