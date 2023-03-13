<?php

use Illuminate\Routing\Router;
Route::group([
    'prefix'        => config('snap.admin.route.prefix'),
    'namespace'     => config('snap.admin.route.namespace'),
    'middleware'    => config('snap.admin.route.middleware'),
], function (Router $router) {


	Admin::routes();

});