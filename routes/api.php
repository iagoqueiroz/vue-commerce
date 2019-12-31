<?php

use Illuminate\Http\Request;

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

Route::prefix('v1')->group(function() {
    Route::prefix('auth')->group(function() {
        Route::post('register', 'AuthController@register')->name('register');
        Route::post('login', 'AuthController@login')->name('login');
        Route::get('refresh', 'AuthController@refresh')->name('refresh');

        Route::middleware('auth:api')->group(function() {
            Route::get('user', 'AuthController@getUser')->name('getUser');
            Route::post('logout', 'AuthController@logout')->name('logout');
        });
    });

    // Product
    Route::get('products/catalog', 'ProductController@index')->name('products.catalog');
    Route::get('products/list', 'ProductController@list')->name('products.list');
    Route::post('products/create', 'ProductController@store')->name('product.store');
    Route::post('products/import', 'ProductController@import')->name('product.import');
    Route::get('products/{product}', 'ProductController@show')->name('products.show');
    Route::get('products/edit/{product}', 'ProductController@edit')->name('products.edit');
    Route::put('products/edit/{product}', 'ProductController@update')->name('products.update');
    Route::delete('products/delete/{product}', 'ProductController@delete')->name('products.delete');

    // Category
    Route::get('categories/list', 'CategoryController@index')->name('category.list');
});
