<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Website\CareerController;
use App\Http\Controllers\Website\ContactController;
use App\Http\Controllers\Website\DealersController;
use App\Http\Controllers\Website\ProductCategoryController;
use App\Http\Controllers\Website\ProductEnquiryController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/career', [CareerController::class, 'index'])->name('career');
Route::post('/contact-us', [ContactController::class, 'store'])->name('contact-us');
Route::post('/dealers', [DealersController::class, 'store'])->name('dealers');
Route::post('/product-enquiry', [ProductEnquiryController::class, 'store'])->name('product-enquiry');
// Route::get('/product-category', [MenuController::class, 'getAllMenu'])->name('product-category');



Route::get('/get/category/{slug?}',[CategoryController::class,'index'])->name('industrial');  //listing all industrial category and subcategory 
Route::get('/get/subcategory/details/{slug}', [App\Http\Controllers\Api\CategoryController::class, 'getCategoryDetails']);
Route::post('/get/products', [App\Http\Controllers\Api\FilterController::class, 'getProducts']); // Listing all products with filter
Route::get('/get/products/by/slug/{product_url}', [App\Http\Controllers\Api\FilterController::class, 'getProductBySlug']); //Product Details
Route::post('/get/dynamic/filter/category', [App\Http\Controllers\Api\FilterController::class, 'getDynamicFilterCategory']);
Route::post('/get/other/category', [App\Http\Controllers\Api\FilterController::class, 'getOtherCategories']);
Route::get('/get/allMenu', [App\Http\Controllers\Api\MenuController::class, 'getAllMenu']);