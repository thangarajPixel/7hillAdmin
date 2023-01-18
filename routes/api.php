<?php

use App\Http\Controllers\Website\CareerController;
use App\Http\Controllers\Website\ContactController;
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
Route::post('/dealers', [ContactController::class, 'store'])->name('dealers');

