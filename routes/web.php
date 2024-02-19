<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;


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

Route::get('/', function () {
    if (Auth::check()){
        return redirect()->route('layout.product_index');

    } else {
        return redirect()->route('login');

    }
});


Auth::routes();

Route::group(['middleware' => 'auth'], function(){
    Route::resource('products', ProductController::class);
});

// routingは基本web.phpにかく。api連携はapi.php。他はあまり使わない
Route::get('/home', [App\Http\Controllers\ProductController::class, 'index'])->name('home');

// 商品画面一覧の表示 routing確認済み
Route::get('/list', 'App\Http\Controllers\ProductController@index')->name('products.index');
// 商品新規登録画面の表示　routing確認済み
Route::get('/create','App\Http\Controllers\ProductController@create')->name('products.create');
// 商品情報詳細画面の表示　routing確認済み
Route::get('/products/{product}','App\Http\Controllers\ProductController@show')->name('products.show');
// 商品情報編集画面の表示　routing確認済み
Route::get('/products/{product}/edit','App\Http\Controllers\ProductController@edit')->name('products.edit');

//更新
// Route::get('/products/{product}','App\Http\Controllers\ProductController@update')->name('products.update');
//削除
// Route::get('/products/{product}','App\Http\Controllers\ProductController@destroy')->name('products.destroy');