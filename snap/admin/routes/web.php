<?php
Route::redirect('/', config('snap.admin.route.prefix').'/'.config('snap.admin.login_redirect'), 302);

// Authentication Routes...
Route::get('login', '\Snap\Admin\Http\Controllers\Auth\LoginController@showLoginForm')->name('admin/login');
Route::post('login', '\Snap\Admin\Http\Controllers\Auth\LoginController@login');
Route::get('forgot', '\Snap\Admin\Http\Controllers\Auth\LoginController@showForgotForm')->name('admin/forgot');
Route::match(['get', 'post'], 'logout', '\Snap\Admin\Http\Controllers\Auth\LoginController@logout')->name('admin/logout');

// Registration Routes...
//Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('admin/register');
//Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', '\Snap\Admin\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('admin/password/request');
Route::post('password/email', '\Snap\Admin\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('admin/password/email');
Route::get('password/reset/{token}', '\Snap\Admin\Http\Controllers\Auth\ResetPasswordController@showResetForm')->name('admin/password/reset/form');
Route::post('password/reset', '\Snap\Admin\Http\Controllers\Auth\ResetPasswordController@reset')->name('admin/password/reset');

Route::get('/me', '\Snap\Admin\Http\Controllers\MeController@index')
    ->middleware(['permission:admin actions'])
     ->name('admin/me')
;
Route::match(['put', 'patch'], '/me', '\Snap\Admin\Http\Controllers\MeController@update')
     ->middleware(['permission:admin actions'])->name('admin/me/update')
;

Route::get('/search', '\Snap\Admin\Http\Controllers\SearchController@results')
     ->middleware(['permission:view search'])->name('admin/search')
;

//Route::get('/dashboard', '\Snap\Admin\Http\Controllers\DashboardController@index')
//     ->middleware(['permission:view dashboard'])->name('admin/dashboard')
//;