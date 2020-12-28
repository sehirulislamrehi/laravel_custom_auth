<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/',[AuthController::class,'login_view'])->name('login.view');
Route::get('/register',[AuthController::class,'register_view'])->name('register.view');
Route::post('/register',[AuthController::class,'register'])->name('register');
Route::post('/login',[AuthController::class,'login'])->name('login');
Route::get('/logout',[AuthController::class,'logout'])->name('logout');
Route::get('forget-password',[AuthController::class,'getEmail'])->name('get.email');
Route::post('forget-password',[AuthController::class,'postEmail'])->name('post.email');
Route::get('reset-password/{token}/{email}',[AuthController::class,'getPassword'])->name('get.password');
Route::post('reset-password/{email}',[AuthController::class,'reset_password'])->name('password.reset');

Route::group(['prefix'=>'dashboard','middleware'=>'authenticate'], function(){
    Route::get('/',[AuthController::class,'dashboard'])->name('dashboard');
});

