<?php
use App\Http\Controllers\api\products\productController;
use App\Http\Controllers\api\users\Authcintroller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

route::group(['prefix'=>'products'],function(){
    route::get('index',[productController::class,'index']);
    route::get('create',[productController::class,'create']);
    route::post('store',[productController::class,'store']);
    route::get('edit/{id}',[productController::class,'edit']);
    route::post('update/{id}',[productController::class,'update']);
    route::get('destroy/{id}',[productController::class,'destroy']);
});

route::group(['prefix'=>'users'],function(){
    route::post('register',[Authcintroller::class,'register']);
    route::post('login',[Authcintroller::class,'login']);
    route::post('forget-password',[Authcintroller::class,'forgetPassword']);
    route::post('verify-forget-code',[Authcintroller::class,'verifyForgetCode']);
    route::post('set-new-password',[Authcintroller::class,'setNewPassword']);

    route::group(['middleware' => 'Checkauth'],function(){
        route::post('send-code',[Authcintroller::class,'sendCode']);
        route::post('verify-code',[Authcintroller::class,'verifyCode']);
        route::get('profile',[Authcintroller::class,'profile']);
        route::get('logout',[Authcintroller::class,'logout']);

    });


});
