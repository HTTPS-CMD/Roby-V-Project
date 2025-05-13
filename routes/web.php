<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });
Route::redirect('/', '/login');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)->except(['edit','create'])->names('roles')->middleware('role:admin');
    Route::get('roles/get', [\App\Http\Controllers\Admin\RoleController::class, 'getIndex'])->name('roles.getIndex')->middleware('role:admin');

    Route::controller(\App\Http\Controllers\Admin\NewsController::class)->prefix('news')->group(function () {
        Route::get('/', 'index')->name('news.index')->middleware('permission:view-news');
        Route::get('get', 'getIndex')->name('news.getIndex')->middleware('permission:view-news');
        Route::post('store', 'store')->name('news.store')->middleware('permission:add-news');
        Route::match(['put','patch'],'{id}', 'update')->name('news.update')->middleware('permission:edit-news');
        Route::delete('{id}', 'destroy')->name('news.destroy')->middleware('permission:delete-news');
    });

    Route::controller(\App\Http\Controllers\Admin\ServerController::class)->prefix('servers')->group(function () {
        Route::get('/', 'index')->name('servers.index')->middleware('permission:view-news');
        Route::get('get', 'getIndex')->name('servers.getIndex')->middleware('permission:view-news');
        Route::post('store', 'store')->name('servers.store')->middleware('permission:add-news');
        Route::match(['put','patch'],'{id}', 'update')->name('servers.update')->middleware('permission:edit-news');
        Route::delete('{id}', 'destroy')->name('servers.destroy')->middleware('permission:delete-news');
    });

    Route::controller(\App\Http\Controllers\Admin\ConfigController::class)->prefix('configs')->group(function () {
        Route::get('/', 'index')->name('configs.index')->middleware('permission:view-configs');
        Route::get('get', 'getIndex')->name('configs.getIndex')->middleware('permission:view-configs');
        Route::post('store', 'store')->name('configs.store')->middleware('permission:add-configs');
        Route::match(['put','patch'],'{id}', 'update')->name('configs.update')->middleware('permission:edit-configs');
        Route::delete('{id}', 'destroy')->name('configs.destroy')->middleware('permission:delete-configs');
    });
});
