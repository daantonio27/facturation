<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {

    Route::resources([
        'users' => 'UserController',
        'providers' => 'ProviderController',
        'inventory/products' => 'ProductController',
        'clients' => 'ClientController',
        'inventory/categories' => 'ProductCategoryController',
        'transactions/transfer' => 'TransferController',
        'methods' => 'MethodController',
    ]);

    Route::resource('transactions', 'TransactionController')->except(['create', 'show']);
    Route::get('transactions/stats/{year?}/{month?}/{day?}', ['as' => 'transactions.stats', 'uses' => 'TransactionController@stats']);
    Route::get('transactions/{type}', ['as' => 'transactions.type', 'uses' => 'TransactionController@type']);
    Route::get('transactions/{type}/create', ['as' => 'transactions.create', 'uses' => 'TransactionController@create']);
    Route::get('transactions/{transaction}/edit', ['as' => 'transactions.edit', 'uses' => 'TransactionController@edit']);
    Route::get('products/search', ['as' => 'products.search', 'uses' => 'ProductController@search']);
    Route::get('products/json', ['as' => 'products.json', 'uses' => 'ProductController@listJson']);

    Route::get('inventory/stats/{year?}/{month?}/{day?}', ['as' => 'inventory.stats', 'uses' => 'InventoryController@stats']);
    Route::resource('inventory/receipts', 'ReceiptController')->except(['edit', 'update']);
    Route::get('inventory/receipts/{receipt}/finalize', ['as' => 'receipts.finalize', 'uses' => 'ReceiptController@finalize']);
    Route::get('inventory/receipts/{receipt}/product/add', ['as' => 'receipts.product.add', 'uses' => 'ReceiptController@addproduct']);
    Route::get('inventory/receipts/{receipt}/product/{receivedproduct}/edit', ['as' => 'receipts.product.edit', 'uses' => 'ReceiptController@editproduct']);
    Route::post('inventory/receipts/{receipt}/product', ['as' => 'receipts.product.store', 'uses' => 'ReceiptController@storeproduct']);
    Route::match(['put', 'patch'], 'inventory/receipts/{receipt}/product/{receivedproduct}', ['as' => 'receipts.product.update', 'uses' => 'ReceiptController@updateproduct']);
    Route::delete('inventory/receipts/{receipt}/product/{receivedproduct}', ['as' => 'receipts.product.destroy', 'uses' => 'ReceiptController@destroyproduct']);

    Route::resource('devis', 'DevisController')->except(['edit', 'update']);
    Route::get('devis/{devis}/product/add', ['as' => 'devis.product.add', 'uses' => 'DevisController@addproduct']);
    Route::post('devis/{devis}/product', ['as' => 'devis.product.store', 'uses' => 'DevisController@storeproduct']);
    Route::get('devis/{devis}/product/{soldproduct}/edit', ['as' => 'devis.product.edit', 'uses' => 'DevisController@editproduct']);
    Route::match(['put', 'patch'], 'devis/{devis}/product/{soldproduct}', ['as' => 'devis.product.update', 'uses' => 'DevisController@updateproduct']);
    Route::delete('devis/{devis}/product/{soldproduct}', ['as' => 'devis.product.destroy', 'uses' => 'DevisController@destroyproduct']);
    Route::get('devis/{devis}/finalize', ['as' => 'devis.finalize', 'uses' => 'DevisController@finalize']);
    Route::get('devis/{devis}/validate', ['as' => 'devis.validate', 'uses' => 'DevisController@validateDevis']);
    Route::get('devis/{devis}/options', ['as' => 'devis.print.options', 'uses' => 'DevisController@optionsPDF']);
    Route::post('devis/{devis}/print', ['as' => 'devis.print.pdf', 'uses' => 'DevisController@createPDF']);
    Route::get('devis/{devis}/edit', ['as' => 'devis.products.edit', 'uses' => 'DevisController@editDevis']);

    Route::resource('sales', 'SaleController')->except(['edit', 'update']);
    Route::post('sales/{sale}/cache-in', ['as' => 'sales.cachein', 'uses' => 'TransactionController@createEncaissement']);
    Route::get('sales/{sale}/finalize', ['as' => 'sales.finalize', 'uses' => 'SaleController@finalize']);
    Route::get('sales/{sale}/product/add', ['as' => 'sales.product.add', 'uses' => 'SaleController@addproduct']);
    Route::get('sales/{sale}/product/{soldproduct}/edit', ['as' => 'sales.product.edit', 'uses' => 'SaleController@editproduct']);
    Route::post('sales/{sale}/product', ['as' => 'sales.product.store', 'uses' => 'SaleController@storeproduct']);
    Route::match(['put', 'patch'], 'sales/{sale}/product/{soldproduct}', ['as' => 'sales.product.update', 'uses' => 'SaleController@updateproduct']);
    Route::delete('sales/{sale}/product/{soldproduct}', ['as' => 'sales.product.destroy', 'uses' => 'SaleController@destroyproduct']);
    Route::get('sales/{sale}/return/data', ['as' => 'sales.product.return.data', 'uses' => 'SaleController@returnSale']);


    Route::get('return/{sale}/product/add', ['as' => 'return.product.add', 'uses' => 'ReturnController@addproduct']);
    Route::get('return/{sale}/product/{soldproduct}/edit', ['as' => 'return.product.edit', 'uses' => 'ReturnController@editproduct']);
    Route::post('return/{sale}/product/store', ['as' => 'return.product.store', 'uses' => 'ReturnController@storeproduct']);
    Route::match(['put', 'patch'],'return/{sale}/product/{soldproduct}/update', ['as' => 'return.product.update', 'uses' => 'ReturnController@updateproduct']);
    Route::get('return/{sale}/return', ['as' => 'return.sale.product', 'uses' => 'SaleController@refreshReturnSale']);
    Route::delete('return/{sale}/product/{soldproduct}', ['as' => 'return.product.destroy', 'uses' => 'ReturnController@destroyproduct']);
    Route::get('return/{sale}/finalize', ['as' => 'return.finalize', 'uses' => 'ReturnController@finalize']);

    Route::get('clients/{client}/transactions/add', ['as' => 'clients.transactions.add', 'uses' => 'ClientController@addtransaction']);
    Route::get('sale/{sale}/options', ['as' => 'sale.print.options', 'uses' => 'SaleController@optionsPDF']);
    Route::post('sale/{sale}/print', ['as' => 'sale.print.pdf', 'uses' => 'SaleController@createPDF']);

    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
    Route::match(['put', 'patch'], 'profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
    Route::match(['put', 'patch'], 'profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('icons', ['as' => 'pages.icons', 'uses' => 'PageController@icons']);
    Route::get('notifications', ['as' => 'pages.notifications', 'uses' => 'PageController@notifications']);
    Route::get('tables', ['as' => 'pages.tables', 'uses' => 'PageController@tables']);
    Route::get('typography', ['as' => 'pages.typography', 'uses' => 'PageController@typography']);
});
