<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderControll;
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
Route::get('/getAllCatagories',[CategoryController::class,'getAllCatagory']);
Route::get('/getProductById/{productId}',[ProductController::class,'getProductById']);
Route::get('/getProductByCatId/{categoryId}',[ProductController::class,'getProductsByCategoryId']);
Route::get('/getCatagoriById/{categoryId}',[CategoryController::class,'getCategoryById']);
Route::get('/getAllProductPublic',[ProductController::class,'getallProductsPublic']);
Route::post('/order',[OrderControll::class,'store']);
Route::put('/completOrders/{orderId}',[OrderControll::class,'updateTransactionId']);





Route::group(['middleware' => ['auth:sanctum','role:Admin']], function () {
    //
    Route::post('/addCatagory',[CategoryController::class,'store']);
    Route::put('/updateCatagory/{id}',[CategoryController::class,'update']);
    Route::delete('/deleteCatagory/{id}',[CategoryController::class,'destroy']);
    Route::get('/getAllUsers',[UseController::class,'getAllUsers']);
    Route::post('/addProduct',[ProductController::class,'store']);
    Route::get('/getAllProduct',[ProductController::class,'getallProducts']);
    Route::put('/updateProduct/{id}',[ProductController::class,'updateProduct']);
    Route::delete('/deleteProduct/{id}',[ProductController::class,'deleteProduct']);
    Route::post('/imageUpload',[ProductController::class,'imageUpload']);
    Route::post('/fileUpload',[ProductController::class,'fileUpload']);
    Route::get('/getAllImages/{productId}',[ProductController::class,'getImagesByProductId']);
    Route::delete('/deleteImage/{imageId}',[ProductController::class,'deleteImage']);
    Route::get('/getFile/{productId}',[ProductController::class,'getFileByProductId']);
    Route::delete('/deleteFile/{imageId}',[ProductController::class,'deleteFile']);
    Route::post('/addquery', [ContactController::class, 'insertContact']);
    Route::get('/getquery', [ContactController::class, 'getAllContacts']);
    Route::delete('/deletequery/{id}', [ContactController::class, 'deleteContact']);

});

Route::group(['middleware' => ['auth:sanctum','role:Admin|User']], function (){

    Route::post('/addToCart',[CartController::class,'addToCart']);
    Route::get('/getCartItems/{userId}',[CartController::class,'getCartByUserId']);
    Route::delete('/deleteItem/{ItemId}',[CartController::class,'deleteFile']);
    Route::get('/getCatrProductList/{userId}',[CartController::class,'getCartProductList']);
    
});


