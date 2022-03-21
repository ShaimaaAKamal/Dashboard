<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\indexController;
use App\Http\Controllers\products\productcontroller;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Auth\Middleware\Authenticate;

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

Route::get('/', [indexController::class,'index'] );
Route::group(['prefix' => 'dashboard','middleware' =>'verified'],function(){
    Route::get('/', [indexController::class,'dashboard']);
    Route::resource('products',productcontroller::class);
});

// Route::get('dashboard', [indexController::class,'dashboard'])->middleware('auth');
// Route::resource('products',productcontroller::class)->middleware('auth');


Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
