<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\InnercategoryController;
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



Route::group(['middleware' => ['auth:sanctum','role:Admin']], function () {
    //
    Route::post('/addCatagory',[CategoryController::class,'store']);
    Route::post('/addSubCatagory',[SubcategoryController::class,'store']);
    Route::post('/addInnnerCatagory',[InnercategoryController::class,'store']);
    Route::get('/getAllCatagories',[CategoryController::class,'getAllCatagory']);
});


