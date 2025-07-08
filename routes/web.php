<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicArticleController;

Route::get('/', [PublicArticleController::class, 'index'])->name('home');
Route::get('/articles/{slug}', [PublicArticleController::class, 'show'])->name('articles.show');
