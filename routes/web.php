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


Route::get('/', function () {
    return view('index');
})->name('home');

//Admin Auth
Route::group(['namespace' => 'Admin\Auth'], function () {

    // Registration Routes...
    Route::get('admin-register/{token}', 'RegistrationController@showRegistrationForm')->name('admin.register.request');
    Route::post('admin-register', 'RegistrationController@register')->name('admin.register');

    // Authentication Routes...
    Route::get('admin-login', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('admin-login', 'LoginController@login');
    Route::post('admin-logout', 'LoginController@logout')->name('admin.logout');

    // Password Reset Routes...
    Route::get('admin-password/reset', 'PasswordResetController@showLinkRequestForm')->name('admin.password.request');
    Route::post('admin-password/email', 'PasswordResetController@sendResetLinkEmail')->name('admin.password.email');
    Route::get('admin-password/reset/{token}', 'PasswordResetController@showResetForm')->name('admin.password.reset');
    Route::post('admin-password/reset', 'PasswordResetController@reset');

});

//User Auth
Route::group(['namespace' => 'User\Auth'], function () {
    // Password Reset
    Route::get('password/reset/{token}', 'PasswordResetController@showResetForm')->name('password.reset');

    // Email Verification
    Route::get('register/verify/{token}', 'RegistrationController@verification')->name('register.verify');

    // Social Auth Routes
    Route::get('auth/{provider}', 'SocialAuthController@redirectToProvider')->name('socialauth');
    Route::get('auth/{provider}/callback', 'SocialAuthController@handleProviderCallback');

});

//Admin Routes
Route::namespace('Admin')->group(function () {

    Route::get('admin/home', 'HomeController@index')->name('admin.home');

    Route::resource('admin/admin', 'AdminController');

    Route::resource('admin/role', 'RoleController');

    Route::post('admin/profile', 'ProfileController@update');

    //Place
    Route::group(['namespace' => 'Place'], function () {

        Route::resource('admin/place', 'PlaceController');

        Route::post('admin/place/{place}/publish', 'PlaceController@publish')->name('place.publish');

        Route::resource('admin/tag', 'TagController');

        Route::resource('admin/category', 'CategoryController');

    });

    //Hotel
    Route::group(['namespace' => 'Hotel'], function () {

        Route::resource('admin/hotel', 'HotelController');

        Route::post('admin/hotel/{hotel}/publish', 'HotelController@publish')->name('hotel.publish');

        Route::resource('admin/room', 'RoomController');

        Route::resource('admin/type', 'TypeController');

    });

    Route::group(['namespace' => 'Article'], function () {

        Route::resource('admin/article', 'ArticleController');

        Route::post('admin/article/{article}/publish', 'ArticleController@publish')->name('article.publish');

    });

    Route::resource('admin/tour-package', 'TourPackageController');

    Route::post('admin/tour-package/{tour_package}/publish', 'TourPackageController@publish')->name('tour-package.publish');

});
