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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::get('/contact', 'ContactController@index')->name('contact');
Route::post('/contact', 'ContactController@process')->name('contact-process');

// This must be specified above the other "providers" routes
Route::pages('providers/become-a-provider');


Route::get('/providers', 'ProviderSearchController@providers')->name('providers');
Route::get('/providers/{provider}', 'ProviderSearchController@detail')->name('provider.detail');
Route::get('/checklist/{category}', 'ChecklistController@category')->name('subcategory');

Route::get('/news', 'NewsController@index')->name('news');

Route::match(['get', 'post'], '/news/{post}', 'NewsController@detail')->name('news.detail');

Route::get('accept/{token}', 'InviteController@accept')->name('invites.accept')->middleware('guest');
Route::post('accept/{token}', 'InviteController@complete')->name('invites.complete')->middleware('guest');

Route::get('customer-invite', 'CustomerInviteController@invite')->name('customer-invite');
Route::post('customer-invite', 'CustomerInviteController@store')->name('customer.invite-post')->middleware('guest');
Route::get('customer-accept/{token}', 'CustomerInviteController@accept')->name('customer.accept')->middleware('guest');


Route::group(['middleware' => ['role:admin']], function () {
    Route::get('invite', 'InviteController@create')->name('invites.create');
    Route::post('invite', 'InviteController@store')->name('invites.store');
});

Route::group(['middleware' => ['role:customer|provider']], function () {
    Route::get('/me', 'ProfileController@show')->name('profile.me');
    Route::post('/me', 'ProfileController@update')->name('profile.update');
});

Route::group(['middleware' => ['role:customer']], function () {
    Route::get('dashboard', 'CustomerDashboardController@index')->name('dashboard');
    Route::get('dashboard/all-tasks', 'CustomerDashboardController@all')->name('dashboard.all');
    Route::get('dashboard/upcoming-tasks', 'CustomerDashboardController@upcoming')->name('dashboard.upcoming');
    Route::get('dashboard/print-tasks/{category?}', 'CustomerDashboardController@printList')->name('dashboard.print');

    //Route::get('dashboard/upcoming', 'CustomerDashboardController@category')->name('dashboard.upcoming');
    Route::get('dashboard/{category}', 'CustomerDashboardController@category')->name('dashboard.category');
    Route::post('/providers/{provider}/rate', 'ProviderRatingsController@rating')->name('provider.rate');

    Route::post('dashboard/update-task-status/{task}',
        'CustomerDashboardController@updateTask')->name('dashboard.update-task-status');
    Route::post('dashboard/update-task-due-date/{task}',
        'CustomerDashboardController@updateDueDate')->name('dashboard.update-task-due-date');

    //Route::get('/related-providers/{category}', 'CustomerDashboardController@relatedProviders');
    //
    //Route::get('/related-articles/{category}', 'CustomerDashboardController@relatedArticles');
    Route::get('/related-details/{category}', 'CustomerDashboardController@relatedTaskDetails');

    Route::get('/dashboard/download/{file}', 'CustomerDashboardController@taskFile');
});

Route::get('sitemap.xml', 'SitemapController@index')->name('sitemap');
//Route::get('sitemap/build', 'SitemapController@build')->name('sitemap');

// Catch all route that will look for pages in SNAP and if not found there will look
// for view files in the views/_pages folder matching the URI path including:
// about
// planning
// legal/terms
// legal/cookies
// legal/privacy
// legal/accessibility
Route::pages();


