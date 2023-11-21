<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UseController;
use App\Http\Controllers\ProductController;
use App\Http\Resources\UserResource;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return "authorized";
});

Route::post('login',[AuthController::class,'login']);
Route::post('register',[AuthController::class,'register']);
Route::post('logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
Route::post('checkToken',[AuthController::class,'checkTokenExpiration']);







Route::group(['middleware' => ['auth:sanctum','role:Admin']], function () {
    //
    Route::post('/addCatagory',[CategoryController::class,'store']);
    Route::get('/getAllCatagories',[CategoryController::class,'getAllCatagory']);
    Route::put('/updateCatagory/{id}',[CategoryController::class,'update']);
    Route::delete('/deleteCatagory/{id}',[CategoryController::class,'destroy']);
    Route::get('/getAllUsers',[UseController::class,'getAllUsers']);
    Route::post('/addProduct',[ProductController::class,'store']);
    Route::get('/getAllProduct',[ProductController::class,'getallProducts']);
    Route::put('/updateProduct/{id}',[ProductController::class,'updateProduct']);
    Route::delete('/deleteProduct/{id}',[ProductController::class,'deleteProduct']);
    Route::post('/imageUpload',[ProductController::class,'imageUpload']);
    Route::post('/fileUpload',[ProductController::class,'fileUpload']);
});


