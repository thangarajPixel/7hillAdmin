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

Route::post('/career', [CareerController::class, 'index'])->name('career');
Route::post('/contact-us', [ContactController::class, 'store'])->name('contact-us');
Route::post('/dealers', [DealersController::class, 'store'])->name('dealers');
Route::post('/product-enquiry', [ProductEnquiryController::class, 'store'])->name('product-enquiry');



// Route::get('/get/category/{slug?}',[CategoryController::class,'index'])->name('industrial');  //listing all industrial category and subcategory 
// Route::get('/get/subcategory/details/{slug}', [App\Http\Controllers\Api\CategoryController::class, 'getCategoryDetails']);

Route::post('/get/dynamic/filter/category', [App\Http\Controllers\Api\FilterController::class, 'getDynamicFilterCategory']);



Route::get('/get/allMenu/{limit?}', [App\Http\Controllers\Api\MenuController::class, 'getAllMenu']);
Route::get('/get/products/by/slug/{product_url}', [App\Http\Controllers\Api\FilterController::class, 'getProductBySlug']); //Product Details
Route::post('/get/other/category', [App\Http\Controllers\Api\FilterController::class, 'getOtherCategories']);

Route::post('/get/products/{slug}', [App\Http\Controllers\Api\FilterController::class, 'getProducts']); // Listing all products with filter
Route::post('/products_filter', [App\Http\Controllers\Api\FilterController::class, 'getFilterProducts']); // Filter Data Product List
