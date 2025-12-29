<?php

use Illuminate\Support\Facades\Route;
use StatisticLv\AdminPanel\Http\Controllers\Frontend\HomeController;
use StatisticLv\AdminPanel\Http\Controllers\Frontend\NewsController as FrontendNewsController;
use StatisticLv\AdminPanel\Http\Controllers\Frontend\PageController as FrontendPageController;

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// News routes
Route::get('/news', [FrontendNewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [FrontendNewsController::class, 'show'])->name('news.show');

// Page routes - this should be last to act as catch-all
Route::get('/{slug}', [FrontendPageController::class, 'show'])->name('page.show');
