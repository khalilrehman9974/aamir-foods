<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChartOfAccountController;
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

require_once 'theme-routes.php';

Route::get('/barebone', function () {
    return view('pages/user/profile', ['title' => 'This is Title']);
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () {

    //Company routes
    Route::group(['prefix' => 'chart-of-account'], function () {
        // Route::get('list', ['as' => 'chart-of-account.list', 'uses' => 'CompaniesController@index']);
        Route::get('create', ['as' => 'chart-of-account.create', 'uses' => 'App\Http\Controllers\ChartOfAccountController@create']);
        Route::post('save', ['as' => 'chart-of-account.save', 'uses' => 'App\Http\Controllers\ChartOfAccountController@store']);
        // Route::get('edit/{id}', ['as' => 'chart-of-account.edit', 'uses' => 'CompaniesController@edit']);
        // Route::post('update', ['as' => 'chart-of-account.update', 'uses' => 'CompaniesController@update']);
        // Route::delete('delete/{id}', ['as' => 'chart-of-account.delete', 'uses' => 'CompaniesController@destroy']);
        // Route::post('show/{id}', ['as' => 'chart-of-account.show', 'uses' => 'CompaniesController@show']);
        // Route::get('search', ['as' => 'chart-of-account.search', 'uses' => 'CompaniesController@search']);

    });

    //Sub-Sub head routes
    Route::group(['prefix' => 'main-head', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'main-head.list', 'uses' => 'App\Http\Controllers\CoaMainHeadController@index']);
        Route::get('create', ['as' => 'main-head.create', 'uses' => 'App\Http\Controllers\CoaMainHeadController@create']);
        Route::post('save', ['as' => 'main-head.save', 'uses' => 'App\Http\Controllers\CoaMainHeadController@store']);
        Route::get('edit/{id}', ['as' => 'main-head.edit', 'uses' => 'App\Http\Controllers\CoaMainHeadController@edit']);
        Route::post('update', ['as' => 'main-head.update', 'uses' => 'App\Http\Controllers\CoaMainHeadController@store']);
        Route::delete('delete/{id}', ['as' => 'main-head.delete', 'uses' => 'App\Http\Controllers\CoaMainHeadController@destroy']);
        Route::get('search', ['as' => 'main-head.search', 'uses' => 'App\Http\Controllers\CoaMainHeadController@search']);
    });

    //Sub-Sub head routes
    Route::group(['prefix' => 'control-head', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'control-head.list', 'uses' => 'App\Http\Controllers\CoaControlHeadController@index']);
        Route::get('create', ['as' => 'control-head.create', 'uses' => 'App\Http\Controllers\CoaControlHeadController@create']);
        Route::post('save', ['as' => 'control-head.save', 'uses' => 'App\Http\Controllers\CoaControlHeadController@store']);
        Route::get('edit/{id}', ['as' => 'control-head.edit', 'uses' => 'App\Http\Controllers\CoaControlHeadController@edit']);
        Route::post('update', ['as' => 'control-head.update', 'uses' => 'App\Http\Controllers\CoaControlHeadController@store']);
        Route::delete('delete/{id}', ['as' => 'control-head.delete', 'uses' => 'App\Http\Controllers\CoaControlHeadController@destroy']);
        Route::get('search', ['as' => 'control-head.search', 'uses' => 'App\Http\Controllers\CoaControlHeadController@search']);
        Route::get('get-control-head-account/{id}', ['as' => 'control-head-account', 'uses' => 'App\Http\Controllers\CoaControlHeadController@getMaxControlHeadCode']);
    });

    //Sub-Sub head routes
    Route::group(['prefix' => 'sub-head', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'sub-head.list', 'uses' => 'App\Http\Controllers\CoaSubHeadController@index']);
        Route::get('create', ['as' => 'sub-head.create', 'uses' => 'App\Http\Controllers\CoaSubHeadController@create']);
        Route::post('save', ['as' => 'sub-head.save', 'uses' => 'App\Http\Controllers\CoaSubHeadController@store']);
        Route::get('edit/{id}', ['as' => 'sub-head.edit', 'uses' => 'App\Http\Controllers\CoaSubHeadController@edit']);
        Route::post('update', ['as' => 'sub-head.update', 'uses' => 'App\Http\Controllers\CoaSubHeadController@store']);
        Route::delete('delete/{id}', ['as' => 'sub-head.delete', 'uses' => 'App\Http\Controllers\CoaSubHeadController@destroy']);
        Route::get('search', ['as' => 'sub-head.search', 'uses' => 'App\Http\Controllers\CoaSubHeadController@search']);
        Route::get('get-sub-head-account/{code}', ['as' => 'sub-head-account', 'uses' => 'App\Http\Controllers\CoaSubHeadController@getMaxSubHeadCode']);
        Route::get('get-control-head-account/{id}', ['as' => 'control-head-account-for-main-head', 'uses' => 'App\Http\Controllers\CoaSubHeadController@getControlAccountForMainHead']);
    });

    //Sub-Sub head routes
    Route::group(['prefix' => 'sub-sub-head', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'sub-sub-head.list', 'uses' => 'App\Http\Controllers\CoaSubSubHeadController@index']);
        Route::get('create', ['as' => 'sub-sub-head.create', 'uses' => 'App\Http\Controllers\CoaSubSubHeadController@create']);
        Route::post('save', ['as' => 'sub-sub-head.save', 'uses' => 'App\Http\Controllers\CoaSubSubHeadController@store']);
        Route::get('edit/{id}', ['as' => 'sub-sub-head.edit', 'uses' => 'App\Http\Controllers\CoaSubSubHeadController@edit']);
        Route::post('update', ['as' => 'sub-sub-head.update', 'uses' => 'App\Http\Controllers\CoaSubSubHeadController@store']);
        Route::delete('delete/{id}', ['as' => 'sub-sub-head.delete', 'uses' => 'App\Http\Controllers\CoaSubSubHeadController@destroy']);
        Route::get('search', ['as' => 'sub-sub-head.search', 'uses' => 'App\Http\Controllers\CoaSubSubHeadController@search']);
        Route::get('get-sub-sub-head-account/{code}', ['as' => 'sub-sub-head-account', 'uses' => 'App\Http\Controllers\CoaSubSubHeadController@getMaxSubSubHeadCode']);
        Route::get('get-sub-heads/{id}', ['as' => 'sub-head-account-for-control-head', 'uses' => 'App\Http\Controllers\CoaSubSubHeadController@getSubAccountForControlHead']);
    });

    //Detail account routes
    Route::group(['prefix' => 'detail-account', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'detail-account.list', 'uses' => 'App\Http\Controllers\CoaDetailAccountController@index']);
        Route::get('create', ['as' => 'detail-account.create', 'uses' => 'App\Http\Controllers\CoaDetailAccountController@create']);
        Route::post('save', ['as' => 'detail-account.save', 'uses' => 'App\Http\Controllers\CoaDetailAccountController@store']);
        Route::get('edit/{id}', ['as' => 'detail-account.edit', 'uses' => 'App\Http\Controllers\CoaDetailAccountController@edit']);
        Route::post('update', ['as' => 'detail-account.update', 'uses' => 'App\Http\Controllers\CoaDetailAccountController@store']);
        Route::delete('delete/{id}', ['as' => 'detail-account.delete', 'uses' => 'App\Http\Controllers\CoaDetailAccountController@destroy']);
        Route::get('search', ['as' => 'detail-account.search', 'uses' => 'App\Http\Controllers\CoaDetailAccountController@search']);
        Route::get('get-detail-account-code/{code}', ['as' => 'detail-account-code', 'uses' => 'App\Http\Controllers\CoaDetailAccountController@getMaxDetailAccountCode']);
        Route::get('get-sub-sub-account/{id}', ['as' => 'sub-sub-head-by-sub-head', 'uses' => 'App\Http\Controllers\CoaDetailAccountController@getSubSubHeadAccountsBySubHead']);
    });

    //Store issue note
    Route::group(['prefix' => 'store-issue-note'], function () {
        Route::get('list', ['as' => 'store-issue-note.list', 'uses' => 'App\Http\Controllers\StoreIssueNoteController@index']);
        Route::get('create', ['as' => 'store-issue-note.create', 'uses' => 'App\Http\Controllers\StoreIssueNoteController@create']);
        Route::post('save', ['as' => 'store-issue-note.save', 'uses' => 'App\Http\Controllers\StoreIssueNoteController@store']);
        Route::get('edit/{id}', ['as' => 'store-issue-note.edit', 'uses' => 'App\Http\Controllers\StoreIssueNoteController@edit']);
        Route::post('update', ['as' => 'store-issue-note.update', 'uses' => 'App\Http\Controllers\StoreIssueNoteController@update']);
        Route::delete('delete/{id}', ['as' => 'store-issue-note.delete', 'uses' => 'App\Http\Controllers\StoreIssueNoteController@destroy']);
        // Route::post('show/{id}', ['as' => 'chart-of-account.show', 'uses' => 'CompaniesController@show']);
        // Route::get('search', ['as' => 'chart-of-account.search', 'uses' => 'CompaniesController@search']);
    });

    //Dispatch note
    Route::group(['prefix' => 'dispatch-note'], function () {
        Route::get('list', ['as' => 'dispatch-note.list', 'uses' => 'App\Http\Controllers\DispatchNoteController@index']);
        Route::get('create', ['as' => 'dispatch-note.create', 'uses' => 'App\Http\Controllers\DispatchNoteController@create']);
        Route::post('save', ['as' => 'dispatch-note.save', 'uses' => 'App\Http\Controllers\DispatchNoteController@store']);
        Route::get('edit/{id}', ['as' => 'dispatch-note.edit', 'uses' => 'App\Http\Controllers\DispatchNoteController@edit']);
        Route::post('update', ['as' => 'dispatch-note.update', 'uses' => 'App\Http\Controllers\DispatchNoteController@update']);
        Route::delete('delete/{id}', ['as' => 'dispatch-note.delete', 'uses' => 'App\Http\Controllers\DispatchNoteController@destroy']);
        Route::post('show/{id}', ['as' => 'dispatch-note.show', 'uses' => 'App\Http\Controllers\DispatchNoteController@show']);
        Route::get('search', ['as' => 'dispatch-note.search', 'uses' => 'App\Http\Controllers\DispatchNoteController@search']);
    });

    Route::group(['prefix' => 'product', 'middleware' => 'auth'], function () {
        Route::get('/products-list', [App\Http\Controllers\ProductsController::class, 'index'])->name('product.list');
        Route::get('/create', [App\Http\Controllers\ProductsController::class, 'create'])->name('product.create');
        Route::get('/edit/{id}', [App\Http\Controllers\ProductsController::class, 'edit'])->name('product.edit');
        Route::get('/view/{id}', [App\Http\Controllers\ProductsController::class, 'view'])->name('product.view');
        Route::post('/store', [App\Http\Controllers\ProductsController::class, 'store'])->name('product.store');
        Route::post('/update', [App\Http\Controllers\ProductsController::class, 'update'])->name('product.update');
        Route::post('/delete', [App\Http\Controllers\ProductsController::class, 'delete'])->name('product.delete');
    });

    Route::group(['prefix' => 'sector', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'sector.list', 'uses' => 'App\Http\Controllers\SectorController@index']);
        Route::get('create', ['as' => 'sector.create', 'uses' => 'App\Http\Controllers\SectorController@create']);
        Route::post('save', ['as' => 'sector.save', 'uses' => 'App\Http\Controllers\SectorController@store']);
        Route::get('edit/{id}', ['as' => 'sector.edit', 'uses' => 'App\Http\Controllers\SectorController@edit']);
        Route::post('update', ['as' => 'sector.update', 'uses' => 'App\Http\Controllers\SectorController@store']);
        Route::delete('delete/{id}', ['as' => 'sector.delete', 'uses' => 'App\Http\Controllers\SectorController@delete']);
        Route::post('show/{id}', ['as' => 'sector.show', 'uses' => 'App\Http\Controllers\SectorController@show']);
        Route::get('search', ['as' => 'sector.search', 'uses' => 'App\Http\Controllers\SectorController@search']);
    });

    Route::group(['prefix' => 'area', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'area.list', 'uses' => 'App\Http\Controllers\AreasController@index']);
        Route::get('create', ['as' => 'area.create', 'uses' => 'App\Http\Controllers\AreasController@create']);
        Route::post('save', ['as' => 'area.save', 'uses' => 'App\Http\Controllers\AreasController@store']);
        Route::get('edit/{id}', ['as' => 'area.edit', 'uses' => 'App\Http\Controllers\AreasController@edit']);
        Route::post('update', ['as' => 'area.update', 'uses' => 'App\Http\Controllers\AreasController@store']);
        Route::delete('delete/{id}', ['as' => 'area.delete', 'uses' => 'App\Http\Controllers\AreasController@destroy']);
        Route::post('show/{id}', ['as' => 'area.show', 'uses' => 'App\Http\Controllers\AreasController@show']);
        Route::get('search', ['as' => 'area.search', 'uses' => 'App\Http\Controllers\AreasController@search']);
    });


    Route::group(['prefix' => 'assignArea', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'assignArea.list', 'uses' => 'App\Http\Controllers\AssignAreaController@index']);
        Route::get('create', ['as' => 'assignArea.create', 'uses' => 'App\Http\Controllers\AssignAreaController@create']);
        Route::post('save', ['as' => 'assignArea.save', 'uses' => 'App\Http\Controllers\AssignAreaController@store']);
        Route::get('edit/{id}', ['as' => 'assignArea.edit', 'uses' => 'App\Http\Controllers\AssignAreaController@edit']);
        Route::post('update', ['as' => 'assignArea.update', 'uses' => 'App\Http\Controllers\AssignAreaController@store']);
        Route::delete('delete/{id}', ['as' => 'assignArea.delete', 'uses' => 'App\Http\Controllers\AssignAreaController@destroy']);
    });

    Route::group(['prefix' => 'saleMan', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'saleMan.list', 'uses' => 'App\Http\Controllers\SaleManController@index']);
        Route::get('create', ['as' => 'saleMan.create', 'uses' => 'App\Http\Controllers\SaleManController@create']);
        Route::post('save', ['as' => 'saleMan.save', 'uses' => 'App\Http\Controllers\SaleManController@store']);
        Route::get('edit/{id}', ['as' => 'saleMan.edit', 'uses' => 'App\Http\Controllers\SaleManController@edit']);
        Route::post('update', ['as' => 'saleMan.update', 'uses' => 'App\Http\Controllers\SaleManController@store']);
        Route::delete('delete/{id}', ['as' => 'saleMan.delete', 'uses' => 'App\Http\Controllers\SaleManController@destroy']);
    });

    Route::group(['prefix' => 'assignSector', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'assignSector.list', 'uses' => 'App\Http\Controllers\AssignSectorController@index']);
        Route::get('create', ['as' => 'assignSector.create', 'uses' => 'App\Http\Controllers\AssignSectorController@create']);
        Route::post('save', ['as' => 'assignSector.save', 'uses' => 'App\Http\Controllers\AssignSectorController@store']);
        Route::get('edit/{id}', ['as' => 'assignSector.edit', 'uses' => 'App\Http\Controllers\AssignSectorController@edit']);
        Route::post('update', ['as' => 'assignSector.update', 'uses' => 'App\Http\Controllers\AssignSectorController@store']);
        Route::delete('delete/{id}', ['as' => 'assignSector.delete', 'uses' => 'App\Http\Controllers\AssignSectorController@destroy']);
    });

    Route::group(['prefix' => 'business', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'business.list', 'uses' => 'App\Http\Controllers\BusinessController@index']);
        Route::get('create', ['as' => 'business.create', 'uses' => 'App\Http\Controllers\BusinessController@create']);
        Route::post('save', ['as' => 'business.save', 'uses' => 'App\Http\Controllers\BusinessController@store']);
        Route::get('edit/{id}', ['as' => 'business.edit', 'uses' => 'App\Http\Controllers\BusinessController@edit']);
        Route::post('update', ['as' => 'business.update', 'uses' => 'App\Http\Controllers\BusinessController@store']);
        Route::delete('delete/{id}', ['as' => 'business.delete', 'uses' => 'App\Http\Controllers\BusinessController@destroy']);
        Route::post('show/{id}', ['as' => 'business.show', 'uses' => 'App\Http\Controllers\BusinessController@show']);
        Route::get('search', ['as' => 'business.search', 'uses' => 'App\Http\Controllers\BusinessController@search']);
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/list', ['as' => 'users.list', 'uses' => 'App\Http\Controllers\Auth\RegisterController@index']);
        Route::get('edit/{id}', ['as' => 'user.edit', 'uses' => 'App\Http\Controllers\Auth\RegisterController@edit']);
        Route::post('update', ['as' => 'user.update', 'uses' => 'App\Http\Controllers\Auth\RegisterController@update']);
        Route::delete('delete/{id}', ['as' => 'user.delete', 'uses' => 'App\Http\Controllers\Auth\RegisterController@destroy']);
    });

    //Sales
    Route::group(['prefix' => 'sale', 'middleware' => 'auth'], function () {
        Route::get('/sales-list', [App\Http\Controllers\SalesController::class, 'index'])->name('sale.sales');
        Route::get('/create', [App\Http\Controllers\SalesController::class, 'create'])->name('sale.create');
        Route::get('/edit/{id}', [App\Http\Controllers\SalesController::class, 'edit'])->name('sale.edit');
        Route::get('/view/{id}', [App\Http\Controllers\SalesController::class, 'view'])->name('sale.view');
        Route::post('/store', [App\Http\Controllers\SalesController::class, 'store'])->name('sale.store');
        Route::post('/update', [App\Http\Controllers\SalesController::class, 'update'])->name('sale.update');
        Route::post('/delete', [App\Http\Controllers\SalesController::class, 'delete'])->name('sale.delete');
        Route::get('/get-dispatch-note', [App\Http\Controllers\SalesController::class, 'getDispatchNote'])->name('getDispatchNote');
    });

    //Sales return
    Route::group(['prefix' => 'sale-return', 'middleware' => 'auth'], function () {
        Route::get('/sales-return-list', [App\Http\Controllers\SalesReturnController::class, 'index'])->name('sale-return.sales-return');
        Route::get('/create', [App\Http\Controllers\SalesReturnController::class, 'create'])->name('sale-return.create');
        Route::get('/edit/{id}', [App\Http\Controllers\SalesReturnController::class, 'edit'])->name('sale-return.edit');
        Route::get('/view/{id}', [App\Http\Controllers\SalesReturnController::class, 'view'])->name('sale-return.view');
        Route::post('/store', [App\Http\Controllers\SalesReturnController::class, 'store'])->name('sale-return.store');
        Route::post('/update', [App\Http\Controllers\SalesReturnController::class, 'update'])->name('sale-return.update');
        Route::post('/delete', [App\Http\Controllers\SalesReturnController::class, 'delete'])->name('sale-return.delete');
        Route::get('/get/brand-products', [App\Http\Controllers\SalesReturnController::class, 'getProductsByBrand'])->name('brand.products');
        Route::get('/get/product-detail', [App\Http\Controllers\SalesReturnController::class, 'getProductDetail'])->name('product.detail');
    });

    Route::group(['prefix' => 'transporter', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'transporter.list', 'uses' => 'App\Http\Controllers\TransporterController@index']);
        Route::get('create', ['as' => 'transporter.create', 'uses' => 'App\Http\Controllers\TransporterController@create']);
        Route::post('save', ['as' => 'transporter.save', 'uses' => 'App\Http\Controllers\TransporterController@store']);
        Route::get('edit/{id}', ['as' => 'transporter.edit', 'uses' => 'App\Http\Controllers\TransporterController@edit']);
        Route::post('update', ['as' => 'transporter.update', 'uses' => 'App\Http\Controllers\TransporterController@store']);
        Route::delete('delete/{id}', ['as' => 'transporter.delete', 'uses' => 'App\Http\Controllers\TransporterController@destroy']);
        Route::post('show/{id}', ['as' => 'transporter.show', 'uses' => 'App\Http\Controllers\TransporterController@show']);
        Route::get('search', ['as' => 'transporter.search', 'uses' => 'App\Http\Controllers\TransporterController@search']);
    });

    Route::group(['prefix' => 'purchase', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'purchase.list', 'uses' => 'App\Http\Controllers\PurchaseController@index']);
        Route::get('create', ['as' => 'purchase.create', 'uses' => 'App\Http\Controllers\PurchaseController@create']);
        Route::post('save', ['as' => 'purchase.save', 'uses' => 'App\Http\Controllers\PurchaseController@store']);
        Route::get('edit/{id}', ['as' => 'purchase.edit', 'uses' => 'App\Http\Controllers\PurchaseController@edit']);
        Route::post('update', ['as' => 'purchase.update', 'uses' => 'App\Http\Controllers\PurchaseController@store']);
        Route::delete('delete/{id}', ['as' => 'purchase.delete', 'uses' => 'App\Http\Controllers\PurchaseController@delete']);
        Route::post('show/{id}', ['as' => 'purchase.show', 'uses' => 'App\Http\Controllers\PurchaseController@show']);
        Route::get('search', ['as' => 'purchase.search', 'uses' => 'App\Http\Controllers\PurchaseController@search']);
    });

    Route::group(['prefix' => 'purchase-return', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'purchase-return.list', 'uses' => 'App\Http\Controllers\PurchaseReturnController@index']);
        Route::get('create', ['as' => 'purchase-return.create', 'uses' => 'App\Http\Controllers\PurchaseReturnController@create']);
        Route::post('save', ['as' => 'purchase-return.save', 'uses' => 'App\Http\Controllers\PurchaseReturnController@store']);
        Route::get('edit/{id}', ['as' => 'purchase-return.edit', 'uses' => 'App\Http\Controllers\PurchaseReturnController@edit']);
        Route::post('update', ['as' => 'purchase-return.update', 'uses' => 'App\Http\Controllers\PurchaseReturnController@store']);
        Route::get('/delete', ['as' => 'purchase-return.delete', 'uses' => 'App\Http\Controllers\PurchaseReturnController@delete']);
        Route::post('show/{id}', ['as' => 'purchase-return.show', 'uses' => 'App\Http\Controllers\PurchaseReturnController@show']);
        Route::get('search', ['as' => 'purchase-return.search', 'uses' => 'App\Http\Controllers\PurchaseReturnController@search']);
    });

    Route::group(['prefix' => 'distributer', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'distributer.list', 'uses' => 'App\Http\Controllers\DistributerController@index']);
        Route::get('create', ['as' => 'distributer.create', 'uses' => 'App\Http\Controllers\DistributerController@create']);
        Route::post('save', ['as' => 'distributer.save', 'uses' => 'App\Http\Controllers\DistributerController@store']);
        Route::get('edit/{id}', ['as' => 'distributer.edit', 'uses' => 'App\Http\Controllers\DistributerController@edit']);
        Route::post('update', ['as' => 'distributer.update', 'uses' => 'App\Http\Controllers\DistributerController@store']);
        Route::delete('delete/{id}', ['as' => 'distributer.delete', 'uses' => 'App\Http\Controllers\DistributerController@destroy']);
        Route::get('/delete', ['as' => 'distributer.delete', 'uses' => 'App\Http\Controllers\DistributerController@delete']);
        Route::post('show/{id}', ['as' => 'distributer.show', 'uses' => 'App\Http\Controllers\DistributerController@show']);
        Route::get('search', ['as' => 'distributer.search', 'uses' => 'App\Http\Controllers\DistributerController@search']);
    });

    Route::group(['prefix' => 'bpv', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'bpv.list', 'uses' => 'App\Http\Controllers\BPVoucherController@index']);
        Route::get('create', ['as' => 'bpv.create', 'uses' => 'App\Http\Controllers\BPVoucherController@create']);
        Route::post('save', ['as' => 'bpv.save', 'uses' => 'App\Http\Controllers\BPVoucherController@store']);
        Route::get('edit/{id}', ['as' => 'bpv.edit', 'uses' => 'App\Http\Controllers\BPVoucherController@edit']);
        Route::post('update', ['as' => 'bpv.update', 'uses' => 'App\Http\Controllers\BPVoucherController@store']);
        Route::get('/delete', ['as' => 'bpv.delete', 'uses' => 'App\Http\Controllers\BPVoucherController@delete']);
        Route::post('show/{id}', ['as' => 'bpv.show', 'uses' => 'App\Http\Controllers\BPVoucherController@show']);
        Route::get('search', ['as' => 'bpv.search', 'uses' => 'App\Http\Controllers\BPVoucherController@search']);
        Route::get('get-party/{code}', ['as' => 'party-code', 'uses' => 'App\Http\Controllers\BPVoucherController@getParty']);
        Route::get('get-detail-data/{id}', ['as' => 'get-detail-data', 'uses' => 'App\Http\Controllers\BPVoucherController@getDetailData']);
        Route::get('get-party-code/{name}', ['as' => 'party-code', 'uses' => 'App\Http\Controllers\BPVoucherController@getPartyCode']);
    });

    Route::group(['prefix' => 'brv', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'brv.list', 'uses' => 'App\Http\Controllers\BRVoucherController@index']);
        Route::get('create', ['as' => 'brv.create', 'uses' => 'App\Http\Controllers\BRVoucherController@create']);
        Route::post('save', ['as' => 'brv.save', 'uses' => 'App\Http\Controllers\BRVoucherController@store']);
        Route::get('edit/{id}', ['as' => 'brv.edit', 'uses' => 'App\Http\Controllers\BRVoucherController@edit']);
        Route::post('update', ['as' => 'brv.update', 'uses' => 'App\Http\Controllers\BRVoucherController@store']);
        Route::get('/delete', ['as' => 'brv.delete', 'uses' => 'App\Http\Controllers\BRVoucherController@delete']);
        Route::post('show/{id}', ['as' => 'brv.show', 'uses' => 'App\Http\Controllers\BRVoucherController@show']);
        Route::get('search', ['as' => 'brv.search', 'uses' => 'App\Http\Controllers\BRVoucherController@search']);
    });

    Route::group(['prefix' => 'cpv', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'cpv.list', 'uses' => 'App\Http\Controllers\CPVoucherController@index']);
        Route::get('create', ['as' => 'cpv.create', 'uses' => 'App\Http\Controllers\CPVoucherController@create']);
        Route::post('save', ['as' => 'cpv.save', 'uses' => 'App\Http\Controllers\CPVoucherController@store']);
        Route::get('edit/{id}', ['as' => 'cpv.edit', 'uses' => 'App\Http\Controllers\CPVoucherController@edit']);
        Route::post('update', ['as' => 'cpv.update', 'uses' => 'App\Http\Controllers\CPVoucherController@store']);
        Route::get('/delete', ['as' => 'cpv.delete', 'uses' => 'App\Http\Controllers\CPVoucherController@delete']);
        Route::post('show/{id}', ['as' => 'cpv.show', 'uses' => 'App\Http\Controllers\CPVoucherController@show']);
        Route::get('search', ['as' => 'cpv.search', 'uses' => 'App\Http\Controllers\CPVoucherController@search']);
    });

    Route::group(['prefix' => 'crv', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'crv.list', 'uses' => 'App\Http\Controllers\CRVoucherController@index']);
        Route::get('create', ['as' => 'crv.create', 'uses' => 'App\Http\Controllers\CRVoucherController@create']);
        Route::post('save', ['as' => 'crv.save', 'uses' => 'App\Http\Controllers\CRVoucherController@store']);
        Route::get('edit/{id}', ['as' => 'crv.edit', 'uses' => 'App\Http\Controllers\CRVoucherController@edit']);
        Route::post('update', ['as' => 'crv.update', 'uses' => 'App\Http\Controllers\CRVoucherController@store']);
        Route::get('/delete', ['as' => 'crv.delete', 'uses' => 'App\Http\Controllers\CRVoucherController@delete']);
        Route::post('show/{id}', ['as' => 'crv.show', 'uses' => 'App\Http\Controllers\CRVoucherController@show']);
        Route::get('search', ['as' => 'crv.search', 'uses' => 'App\Http\Controllers\CRVoucherController@search']);
    });

    Route::group(['prefix' => 'jv', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'jv.list', 'uses' => 'App\Http\Controllers\JournalVoucherController@index']);
        Route::get('create', ['as' => 'jv.create', 'uses' => 'App\Http\Controllers\JournalVoucherController@create']);
        Route::post('save', ['as' => 'jv.save', 'uses' => 'App\Http\Controllers\JournalVoucherController@store']);
        Route::get('edit/{id}', ['as' => 'jv.edit', 'uses' => 'App\Http\Controllers\JournalVoucherController@edit']);
        Route::post('update', ['as' => 'jv.update', 'uses' => 'App\Http\Controllers\JournalVoucherController@store']);
        Route::get('/delete', ['as' => 'jv.delete', 'uses' => 'App\Http\Controllers\JournalVoucherController@delete']);
        Route::post('show/{id}', ['as' => 'jv.show', 'uses' => 'App\Http\Controllers\JournalVoucherController@show']);
        Route::get('search', ['as' => 'jv.search', 'uses' => 'App\Http\Controllers\JournalVoucherController@search']);
    });

    Route::group(['prefix' => 'grn', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'grn.list', 'uses' => 'App\Http\Controllers\GRNotesController@index']);
        Route::get('create', ['as' => 'grn.create', 'uses' => 'App\Http\Controllers\GRNotesController@create']);
        Route::post('save', ['as' => 'grn.save', 'uses' => 'App\Http\Controllers\GRNotesController@store']);
        Route::get('edit/{id}', ['as' => 'grn.edit', 'uses' => 'App\Http\Controllers\GRNotesController@edit']);
        Route::post('update', ['as' => 'grn.update', 'uses' => 'App\Http\Controllers\GRNotesController@update']);
        Route::delete('delete/{id}', ['as' => 'grn.delete', 'uses' => 'App\Http\Controllers\GRNotesController@delete']);
        Route::post('show/{id}', ['as' => 'grn.show', 'uses' => 'App\Http\Controllers\GRNotesController@show']);
        Route::get('search', ['as' => 'grn.search', 'uses' => 'App\Http\Controllers\GRNotesController@search']);
    });
    //Chart of Inventory Main Heads
    Route::group(['prefix' => 'co-inv-main-head'], function () {
        Route::get('/list', ['as' => 'co-inventory-main-head.list', 'uses' => 'App\Http\Controllers\ChartOfInvMainHeadController@index']);
        Route::get('create', ['as' => 'co-inventory-main-head.create', 'uses' => 'App\Http\Controllers\ChartOfInvMainHeadController@create']);
        Route::get('edit/{id}', ['as' => 'co-inventory-main-head.edit', 'uses' => 'App\Http\Controllers\ChartOfInvMainHeadController@edit']);
        Route::post('save', ['as' => 'co-inventory-main-head.save', 'uses' => 'App\Http\Controllers\ChartOfInvMainHeadController@store']);
        Route::post('update', ['as' => 'co-inventory-main-head.update', 'uses' => 'App\Http\Controllers\ChartOfInvMainHeadController@store']);
        Route::delete('delete/{id}', ['as' => 'co-inventory-main-head.delete', 'uses' => 'App\Http\Controllers\ChartOfInvMainHeadController@destroy']);
    });

    //Chart of Inventory Sub Heads
    Route::group(['prefix' => 'co-inv-sub-head'], function () {
        Route::get('/list', ['as' => 'co-inventory-sub-head.list', 'uses' => 'App\Http\Controllers\ChartOfInvSubHeadController@index']);
        Route::get('create', ['as' => 'co-inventory-sub-head.create', 'uses' => 'App\Http\Controllers\ChartOfInvSubHeadController@create']);
        Route::get('edit/{id}', ['as' => 'co-inventory-sub-head.edit', 'uses' => 'App\Http\Controllers\ChartOfInvSubHeadController@edit']);
        Route::post('save', ['as' => 'co-inventory-sub-head.save', 'uses' => 'App\Http\Controllers\ChartOfInvSubHeadController@store']);
        Route::post('update', ['as' => 'co-inventory-sub-head.update', 'uses' => 'App\Http\Controllers\ChartOfInvSubHeadController@store']);
        Route::delete('delete/{id}', ['as' => 'co-inventory-sub-head.delete', 'uses' => 'App\Http\Controllers\ChartOfInvSubHeadController@destroy']);
        Route::get('get-sub-head-account/{code}', ['as' => 'sub-head-account', 'uses' => 'App\Http\Controllers\ChartOfInvSubHeadController@getMaxSubSubHeadCode']);
    });

    Route::group(['prefix' => 'co-inv-sub-sub-head'], function () {
        Route::get('/list', ['as' => 'co-inventory-sub-sub-head.list', 'uses' => 'App\Http\Controllers\ChartOfInvSubSubHeadController@index']);
        Route::get('create', ['as' => 'co-inventory-sub-sub-head.create', 'uses' => 'App\Http\Controllers\ChartOfInvSubSubHeadController@create']);
        Route::get('edit/{id}', ['as' => 'co-inventory-sub-sub-head.edit', 'uses' => 'App\Http\Controllers\ChartOfInvSubSubHeadController@edit']);
        Route::post('save', ['as' => 'co-inventory-sub-sub-head.save', 'uses' => 'App\Http\Controllers\ChartOfInvSubSubHeadController@store']);
        Route::post('update', ['as' => 'co-inventory-sub-sub-head.update', 'uses' => 'App\Http\Controllers\ChartOfInvSubSubHeadController@store']);
        Route::delete('delete/{id}', ['as' => 'co-inventory-sub-sub-head.delete', 'uses' => 'App\Http\Controllers\ChartOfInvSubSubHeadController@destroy']);
        Route::get('get-sub-head-accounts/{id}', ['as' => 'sub-head-accounts-by-main-head', 'uses' => 'App\Http\Controllers\ChartOfInvSubSubHeadController@getSubHeadAccountsByMainHead']);
        Route::get('get-sub-sub-head-account-code/{code}', ['as' => 'sub-sub-head-account', 'uses' => 'App\Http\Controllers\ChartOfInvSubSubHeadController@getMaxSubSubHeadCode']);
    });

    //Chart of Inventory Detail account.
    Route::group(['prefix' => 'co-inv-detail-account'], function () {
        Route::get('/list', ['as' => 'co-inventory-detail-account.list', 'uses' => 'App\Http\Controllers\ChartOfInvDetailAccountController@index']);
        Route::get('create', ['as' => 'co-inventory-detail-account.create', 'uses' => 'App\Http\Controllers\ChartOfInvDetailAccountController@create']);
        Route::get('edit/{id}', ['as' => 'co-inventory-detail-account.edit', 'uses' => 'App\Http\Controllers\ChartOfInvDetailAccountController@edit']);
        Route::post('save', ['as' => 'co-inventory-detail-account.save', 'uses' => 'App\Http\Controllers\ChartOfInvDetailAccountController@store']);
        Route::post('update', ['as' => 'co-inventory-detail-account.update', 'uses' => 'App\Http\Controllers\ChartOfInvDetailAccountController@store']);
        Route::delete('delete/{id}', ['as' => 'co-inventory-detail-account.delete', 'uses' => 'App\Http\Controllers\ChartOfInvDetailAccountController@destroy']);
        Route::get('get-detail-account-code/{code}', ['as' => 'detail-account', 'uses' => 'App\Http\Controllers\ChartOfInvDetailAccountController@getMaxDetailAccountCode']);
        Route::get('get-sub-head-accounts/{id}', ['as' => 'sub-head-accounts-by-main-head', 'uses' => 'App\Http\Controllers\ChartOfInvDetailAccountController@getSubHeadAccountsByMainHead']);
        Route::get('get-sub-sub-head-accounts/{id}', ['as' => 'sub-sub-head-accounts-by-sub-head', 'uses' => 'App\Http\Controllers\ChartOfInvDetailAccountController@getSubSubHeadAccountsBySubHead']);
    });

    Route::group(['prefix' => 'purchase-order', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'purchase-order.list', 'uses' => 'App\Http\Controllers\PurchaseOrderController@index']);
        Route::get('create', ['as' => 'purchase-order.create', 'uses' => 'App\Http\Controllers\PurchaseOrderController@create']);
        Route::post('save', ['as' => 'purchase-order.store', 'uses' => 'App\Http\Controllers\PurchaseOrderController@store']);
        Route::get('edit/{id}', ['as' => 'purchase-order.edit', 'uses' => 'App\Http\Controllers\PurchaseOrderController@edit']);
        Route::post('update', ['as' => 'purchase-order.update', 'uses' => 'App\Http\Controllers\PurchaseOrderController@store']);
        Route::delete('delete/{id}', ['as' => 'purchase-order.delete', 'uses' => 'App\Http\Controllers\PurchaseOrderController@destroy']);
    });

    Route::group(['prefix' => 'priceTag', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'priceTag.list', 'uses' => 'App\Http\Controllers\PriceTagController@index']);
        Route::get('create', ['as' => 'priceTag.create', 'uses' => 'App\Http\Controllers\PriceTagController@create']);
        Route::post('save', ['as' => 'priceTag.save', 'uses' => 'App\Http\Controllers\PriceTagController@store']);
        Route::get('edit/{id}', ['as' => 'priceTag.edit', 'uses' => 'App\Http\Controllers\PriceTagController@edit']);
        Route::post('update', ['as' => 'priceTag.update', 'uses' => 'App\Http\Controllers\PriceTagController@store']);
        Route::delete('delete/{id}', ['as' => 'priceTag.delete', 'uses' => 'App\Http\Controllers\PriceTagController@delete']);
    });

    Route::group(['prefix' => 'MeasurementType', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'MeasurementType.list', 'uses' => 'App\Http\Controllers\MeasurementTypeController@index']);
        Route::get('create', ['as' => 'MeasurementType.create', 'uses' => 'App\Http\Controllers\MeasurementTypeController@create']);
        Route::post('save', ['as' => 'MeasurementType.save', 'uses' => 'App\Http\Controllers\MeasurementTypeController@store']);
        Route::get('edit/{id}', ['as' => 'MeasurementType.edit', 'uses' => 'App\Http\Controllers\MeasurementTypeController@edit']);
        Route::post('update', ['as' => 'MeasurementType.update', 'uses' => 'App\Http\Controllers\MeasurementTypeController@store']);
        Route::delete('delete/{id}', ['as' => 'MeasurementType.delete', 'uses' => 'App\Http\Controllers\MeasurementTypeController@delete']);
    });

    Route::group(['prefix' => 'PackingType', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'PackingType.list', 'uses' => 'App\Http\Controllers\PackingTypeController@index']);
        Route::get('create', ['as' => 'PackingType.create', 'uses' => 'App\Http\Controllers\PackingTypeController@create']);
        Route::post('save', ['as' => 'PackingType.save', 'uses' => 'App\Http\Controllers\PackingTypeController@store']);
        Route::get('edit/{id}', ['as' => 'PackingType.edit', 'uses' => 'App\Http\Controllers\PackingTypeController@edit']);
        Route::post('update', ['as' => 'PackingType.update', 'uses' => 'App\Http\Controllers\PackingTypeController@store']);
        Route::delete('delete/{id}', ['as' => 'PackingType.delete', 'uses' => 'App\Http\Controllers\PackingTypeController@delete']);
    });

    Route::group(['prefix' => 'purchase-order', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'purchase-order.list', 'uses' => 'App\Http\Controllers\PurchaseOrderController@index']);
        Route::get('create', ['as' => 'purchase-order.create', 'uses' => 'App\Http\Controllers\PurchaseOrderController@create']);
        Route::post('save', ['as' => 'purchase-order.store', 'uses' => 'App\Http\Controllers\PurchaseOrderController@store']);
        Route::get('edit/{id}', ['as' => 'purchase-order.edit', 'uses' => 'App\Http\Controllers\PurchaseOrderController@edit']);
        Route::post('update', ['as' => 'purchase-order.update', 'uses' => 'App\Http\Controllers\PurchaseOrderController@store']);
        Route::delete('delete/{id}', ['as' => 'purchase-order.delete', 'uses' => 'App\Http\Controllers\PurchaseOrderController@destroy']);
    });

    Route::group(['prefix' => 'sale-order', 'middleware' => 'auth'], function () {
        Route::get('list', ['as' => 'sale-order.list', 'uses' => 'App\Http\Controllers\SaleOrderController@index']);
        Route::get('create', ['as' => 'sale-order.create', 'uses' => 'App\Http\Controllers\SaleOrderController@create']);
        Route::post('save', ['as' => 'sale-order.save', 'uses' => 'App\Http\Controllers\SaleOrderController@store']);
        Route::get('edit/{id}', ['as' => 'sale-order.edit', 'uses' => 'App\Http\Controllers\SaleOrderController@edit']);
        Route::post('update', ['as' => 'sale-order.update', 'uses' => 'App\Http\Controllers\SaleOrderController@store']);
        Route::delete('delete/{id}', ['as' => 'sale-order.delete', 'uses' => 'App\Http\Controllers\SaleOrderController@destroy']);
    });

    Route::group(['prefix' => 'storeReturn'], function () {
        Route::get('list', ['as' => 'storeReturn.list', 'uses' => 'App\Http\Controllers\StoreReturnController@index']);
        Route::get('create', ['as' => 'storeReturn.create', 'uses' => 'App\Http\Controllers\StoreReturnController@create']);
        Route::post('save', ['as' => 'storeReturn.save', 'uses' => 'App\Http\Controllers\StoreReturnController@store']);
        Route::get('edit/{id}', ['as' => 'storeReturn.edit', 'uses' => 'App\Http\Controllers\StoreReturnController@edit']);
        Route::post('update', ['as' => 'storeReturn.update', 'uses' => 'App\Http\Controllers\StoreReturnController@update']);
        Route::delete('delete/{id}', ['as' => 'storeReturn.delete', 'uses' => 'App\Http\Controllers\StoreReturnController@destroy']);
    });

    Route::get('/clear-cache', function () {
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('config:cache');
        \Illuminate\Support\Facades\Artisan::call('view:clear');

        return "Cache cleared successfully";
    });
});
