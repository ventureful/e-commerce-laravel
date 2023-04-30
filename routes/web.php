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

$locales = ['', 'en', 'vi'];

foreach ($locales as $locale) {
    Route::group([
        'prefix' => $locale,
        'as' => $locale,
        'middleware' => 'locale',
    ], function () use ($locale) {
        Route::get('/', 'HomeController@index')->name('home');
    });
}

Route::group(['prefix' => 'client'], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
    Route::get('home', function (){
       return view('end-user.home.index');
    })->name('home');

    Route::get('shop', function (){
        return view('end-user.shop.index');
    })->name('shop');

    Route::get('shop-detail', function (){
        return view('end-user.detail.index');
    })->name('detail');

    Route::get('list-cart', function (){
        return view('end-user.cart.index');
    })->name('list-cart');

    Route::get('checkout', function (){
        return view('end-user.checkout.index');
    })->name('checkout');

    Route::get('contact', function (){
        return view('end-user.contact.index');
    })->name('contact');;
});


