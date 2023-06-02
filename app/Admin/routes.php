<?php

use App\Admin\Controllers\BuildingController;
use App\Admin\Controllers\CategoryController;
use App\Admin\Controllers\CommunityController;
use App\Admin\Controllers\GoodController;
use App\Admin\Controllers\HouseController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Admin::routes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    $router->resource('categories', CategoryController::class);

    $router->get('categories/search', [CategoryController::class, 'search'])->name('categories.search');

    $router->resource('goods', GoodController::class);

    $router->resource('communities', CommunityController::class);

    $router->resource('buildings', BuildingController::class);

    $router->get('search', [BuildingController::class, 'search']);

    $router->resource('houses', HouseController::class);
});
