<?php

use App\Http\Controllers\RazorpayPaymentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
Auth::routes();
Route::middleware(['auth'])->group(function(){
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    Route::prefix('global')->group(function(){
        Route::get('/', [App\Http\Controllers\GlobalSettingController::class, 'index'])->name('global')->middleware(['checkAccess:visible']);
        Route::post('/save', [App\Http\Controllers\GlobalSettingController::class, 'saveForm'])->name('global.save')->middleware(['checkAccess:editable']);
        Route::post('/save/link', [App\Http\Controllers\GlobalSettingController::class, 'saveLinkForm'])->name('global.link.save')->middleware(['checkAccess:editable']);
        Route::post('/getTab', [App\Http\Controllers\GlobalSettingController::class, 'getTab'])->name('global.tab');
    });       

    Route::prefix('my-profile')->group(function(){
        Route::get('/', [App\Http\Controllers\MyProfileController::class, 'index'])->name('my-profile')->middleware(['checkAccess:visible']);
        Route::get('/password', [App\Http\Controllers\MyProfileController::class, 'getPasswordTab'])->name('my-profile.password')->middleware(['checkAccess:editable']);
        Route::post('/getTab', [App\Http\Controllers\MyProfileController::class, 'getTab'])->name('my-profile.get.tab');
        Route::post('/save', [App\Http\Controllers\MyProfileController::class, 'saveForm'])->name('my-profile.save')->middleware(['checkAccess:editable']);
    });
 
    $categoriesArray = array('sub_category');
    foreach ($categoriesArray as $catUrl ) {
        Route::prefix($catUrl)->group(function() use($catUrl) {
            Route::get('/', [App\Http\Controllers\Category\SubCategoryController::class, 'index'])->name($catUrl)->middleware(['checkAccess:visible']);
            Route::post('/addOrEdit', [App\Http\Controllers\Category\SubCategoryController::class, 'modalAddEdit'])->name($catUrl.'.add.edit')->middleware(['checkAccess:editable']);
            Route::post('/status', [App\Http\Controllers\Category\SubCategoryController::class, 'changeStatus'])->name($catUrl.'.status')->middleware(['checkAccess:status']);
            Route::post('/delete', [App\Http\Controllers\Category\SubCategoryController::class, 'delete'])->name($catUrl.'.delete')->middleware(['checkAccess:delete']);
            Route::post('/save', [App\Http\Controllers\Category\SubCategoryController::class, 'saveForm'])->name($catUrl.'.save');
            Route::post('/export/excel', [App\Http\Controllers\Category\SubCategoryController::class, 'export'])->name($catUrl.'.export.excel')->middleware(['checkAccess:export']);
            Route::get('/export/pdf', [App\Http\Controllers\Category\SubCategoryController::class, 'exportPdf'])->name($catUrl.'.export.pdf')->middleware(['checkAccess:export']);
        });
    }
    /***** loop for same routes */
    $routeArray = array(
        'product-category' => App\Http\Controllers\Product\ProductCategoryController::class,
        'main_category' => App\Http\Controllers\Category\MainCategoryController::class,        
        'users' => App\Http\Controllers\UserController::class,
        'roles' => App\Http\Controllers\Settings\RoleController::class,
        'customer' => App\Http\Controllers\CustomerController::class,
        'banner' => App\Http\Controllers\BannerController::class,
    );
    foreach ($routeArray as $key => $value) {
        Route::prefix($key)->group(function() use($key, $value) {
            Route::get('/', [$value, 'index'])->name($key)->middleware(['checkAccess:visible']);
            Route::post('/addOrEdit', [$value, 'modalAddEdit'])->name($key.'.add.edit')->middleware(['checkAccess:editable']);
            Route::post('/status', [$value, 'changeStatus'])->name($key.'.status')->middleware(['checkAccess:status']);
            Route::post('/delete', [$value, 'delete'])->name($key.'.delete')->middleware(['checkAccess:delete']);
            Route::post('/save', [$value, 'saveForm'])->name($key.'.save');
            Route::post('/export/excel', [$value, 'export'])->name($key.'.export.excel')->middleware(['checkAccess:export']);
            Route::get('/export/pdf', [$value, 'exportPdf'])->name($key.'.export.pdf')->middleware(['checkAccess:export']);
        });
    }
    Route::prefix('products')->group(function(){
        Route::get('/', [App\Http\Controllers\Product\ProductController::class, 'index'])->name('products')->middleware(['checkAccess:visible']); 
        Route::get('/upload', [App\Http\Controllers\Product\ProductController::class, 'bulkUpload'])->name('products.upload')->middleware(['checkAccess:editable']); 
        Route::post('/upload/product', [App\Http\Controllers\Product\ProductController::class, 'doBulkUpload'])->name('products.bulk.upload')->middleware(['checkAccess:editable']); 
        Route::get('/add/{id?}', [App\Http\Controllers\Product\ProductController::class, 'addEditPage'])->name('products.add.edit')->middleware(['checkAccess:editable']); 
        Route::post('/status', [App\Http\Controllers\Product\ProductController::class, 'changeStatus'])->name('products.status')->middleware(['checkAccess:status']);
        Route::post('/delete', [App\Http\Controllers\Product\ProductController::class, 'delete'])->name('products.delete')->middleware(['checkAccess:delete']);
        Route::post('/save', [App\Http\Controllers\Product\ProductController::class, 'saveForm'])->name('products.save');
        Route::post('/remove/image', [App\Http\Controllers\Product\ProductController::class, 'removeImage'])->name('products.remove.image');
        Route::post('/remove/brochure', [App\Http\Controllers\Product\ProductController::class, 'removeBrochure'])->name('products.remove.brochure');
        Route::post('/upload/brochure', [App\Http\Controllers\Product\ProductController::class, 'uploadBrochure'])->name('products.upload.brochure');
        Route::post('/upload/gallery', [App\Http\Controllers\Product\ProductController::class, 'uploadGallery'])->name('products.upload.gallery');
        Route::post('/export/excel', [App\Http\Controllers\Product\ProductController::class, 'export'])->name('products.export.excel')->middleware(['checkAccess:export']);
        Route::get('/export/pdf', [App\Http\Controllers\Product\ProductController::class, 'exportPdf'])->name('products.export.pdf')->middleware(['checkAccess:export']);
        /****** Product Collection */
    });
    Route::post('/getProduct/category/list', [App\Http\Controllers\CommonController::class, 'getProductCategoryList'])->name('common.category.dropdown');
    Route::post('/getProduct/brand/list', [App\Http\Controllers\CommonController::class, 'getProductBrandList'])->name('common.brand.dropdown');
    Route::post('/getProduct/dynamic/list', [App\Http\Controllers\CommonController::class, 'getProductDynamicList'])->name('common.dynamic.dropdown');

    Route::prefix('customer')->group(function(){
        Route::get('/coupon-gendrate', [App\Http\Controllers\CustomerController::class, 'couponGendrate'])->name('customer.coupon-gendrate');
        Route::post('/coupon-apply', [App\Http\Controllers\CustomerController::class, 'couponType'])->name('customer.coupon-apply');
        Route::get('/customer/view/{id}', [App\Http\Controllers\CustomerController::class, 'view'])->name('customer.view')->middleware(['checkAccess:visible']);
        Route::get('/add-address', [App\Http\Controllers\CustomerController::class, 'addAddress'])->name('customer.add-address')->middleware(['checkAccess:editable']);
        Route::post('/address', [App\Http\Controllers\CustomerController::class, 'customerAddress'])->name('customer.address');
        Route::post('/address/list', [App\Http\Controllers\CustomerController::class, 'addressList'])->name('customer.address.list')->middleware(['checkAccess:visible']);
        Route::post('/address/delete', [App\Http\Controllers\CustomerController::class, 'addressDelete'])->name('customer.delete')->middleware(['checkAccess:delete']);
    });

    Route::prefix('reports')->middleware(['checkAccess:visible'])->group(function(){
        Route::prefix('products')->group(function(){
            Route::get('/list', [App\Http\Controllers\ReportProductController::class, 'index'])->name('reports.products.list');
        });
    });    
   
});
