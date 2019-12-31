<?php

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
Route::get('csv', function() {
    $products = \App\Product::with('category')->limit(10)->get()->toArray();
    $products = array_map(function($product) {
        return ['name' => $product['name'], 'category' => $product['category']['name'], 'price' => $product['price']];
    }, $products);

    return \Excel::create('import_products', function($excel) use ($products) {
        $excel->sheet('Product Imports', function($sheet) use ($products) {
            $sheet->fromArray($products);
        });
    })->download('csv');
});

Route::any('/{any}', function () {
    return view('app');
})->where('any', '.*');
