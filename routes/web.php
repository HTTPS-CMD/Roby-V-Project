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

    Route::controller(\App\Http\Controllers\Admin\RoleController::class)->prefix('roles')->middleware('role:admin')
        ->group(function () {
            Route::get('/', 'index')->name('roles.index');
            Route::get('get', 'getIndex')->name('roles.getIndex');
            Route::post('store', 'store')->name('roles.store');
            Route::match(['put','patch'],'{id}', 'update')->name('roles.update');
            Route::delete('{id}', 'destroy')->name('roles.destroy');
        });

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

    Route::controller(\App\Http\Controllers\Admin\FaqController::class)->prefix('faqs')->middleware('role:admin')
        ->group(function () {
            Route::get('/','index')->name('faqs.index');
            Route::get('get','getIndex')->name('faqs.getIndex');
            Route::post('store','store')->name('faqs.store');
            Route::put('faqs/sort', 'sort')->name('faqs.sort');
            Route::match(['patch','put'],'update/{id}','update')->name('faqs.update');
            Route::delete('{id}','destroy')->name('faqs.destroy');
        });

    Route::controller(\App\Http\Controllers\Admin\UserController::class)->prefix('users')->group(function () {
        Route::get('/','index')->name('users.index')->middleware('permission:view-user');
        Route::get('get','getIndex')->name('users.getIndex')->middleware('permission:view-user');
        Route::post('store','store')->name('users.store')->middleware('permission:add-user');
        Route::match(['patch','put'],'update/{id}','update')->name('users.update')->middleware('permission:edit-user');
        Route::delete('{id}','destroy')->name('users.destroy')->middleware('permission:delete-user');
    });

    Route::controller(\App\Http\Controllers\Admin\NotificationController::class)->prefix('notifications')->group(function () {
        Route::get('/','index')->name('notifications.index')->middleware('permission:view-notifications');
        Route::get('get','getIndex')->name('notifications.getIndex')->middleware('permission:view-notifications');
        Route::post('store','store')->name('notifications.store')->middleware('permission:add-notifications');
        Route::match(['patch','put'],'update/{id}','update')->name('notifications.update')->middleware('permission:edit-notifications');
        Route::delete('{id}','destroy')->name('notifications.destroy')->middleware('permission:delete-notifications');
    });
});
