<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicArticleController;
use App\Http\Controllers\CategoryController;

Route::get('/articles/autocomplete', [PublicArticleController::class, 'autocomplete']);
Route::get('/', [PublicArticleController::class, 'index'])->name('home');
Route::get('/articles/{slug}', [PublicArticleController::class, 'show'])->name('articles.show');
Route::get('/search', [PublicArticleController::class, 'search'])->name('articles.search');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');
