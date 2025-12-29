<?php

use Illuminate\Support\Facades\Route;
use StatisticLv\AdminPanel\Http\Controllers\AuthController;
use StatisticLv\AdminPanel\Http\Controllers\DashboardController;
use StatisticLv\AdminPanel\Http\Controllers\NewsController;
use StatisticLv\AdminPanel\Http\Controllers\MenuController;
use StatisticLv\AdminPanel\Http\Controllers\PageController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Routes
Route::middleware(['admin.auth'])->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');
    
    // News Management
    Route::resource('news', NewsController::class)->names([
        'index' => 'admin.news.index',
        'create' => 'admin.news.create',
        'store' => 'admin.news.store',
        'show' => 'admin.news.show',
        'edit' => 'admin.news.edit',
        'update' => 'admin.news.update',
        'destroy' => 'admin.news.destroy',
    ]);
    
    // Page Management
    Route::resource('pages', PageController::class)->names([
        'index' => 'admin.pages.index',
        'create' => 'admin.pages.create',
        'store' => 'admin.pages.store',
        'show' => 'admin.pages.show',
        'edit' => 'admin.pages.edit',
        'update' => 'admin.pages.update',
        'destroy' => 'admin.pages.destroy',
    ]);
    
    // Menu Management
    Route::resource('menus', MenuController::class)->names([
        'index' => 'admin.menus.index',
        'create' => 'admin.menus.create',
        'store' => 'admin.menus.store',
        'show' => 'admin.menus.show',
        'edit' => 'admin.menus.edit',
        'update' => 'admin.menus.update',
        'destroy' => 'admin.menus.destroy',
    ]);
    
    // Menu Item Management
    Route::post('menus/{menu}/items', [MenuController::class, 'addItem'])->name('admin.menus.items.store');
    Route::put('menus/{menu}/items/{item}', [MenuController::class, 'updateItem'])->name('admin.menus.items.update');
    Route::delete('menus/{menu}/items/{item}', [MenuController::class, 'deleteItem'])->name('admin.menus.items.destroy');
});
