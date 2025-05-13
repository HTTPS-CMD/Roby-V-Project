<?php

use App\Http\Controllers\Admin\UserController;
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

    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('/','index')->name('users.index')->middleware('permission:view-user');
        Route::get('get','getIndex')->name('users.data')->middleware('permission:view-user');
        Route::post('store','store')->name('users.store')->middleware('permission:add-user');
        Route::match(['patch','put'],'update/{id}','update')->name('users.update')->middleware('permission:edit-user');
        Route::delete('{id}','destroy')->name('users.delete')->middleware('permission:delete-user');
    });
});
