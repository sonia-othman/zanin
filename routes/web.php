<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

// Clean language switching route - IMPROVED VERSION
Route::get('/lang/{lang}', function ($lang) {
    $available = ['en', 'ar', 'ku'];

    if (in_array($lang, $available)) {
        // Set application locale
        App::setLocale($lang);
        
        // Store in session
        session(['locale' => $lang]);
        
        // Also store in cookie for persistence
        Cookie::queue('locale', $lang, 525600); // 1 year
        
        // Add flash message for feedback
        session()->flash('language_changed', true);
        session()->flash('new_language', $lang);
    }

    return redirect()->back()->with('success', 'Language changed successfully');
})->name('language.switch');

// Your existing routes...
Route::get('/articles/autocomplete', [PublicArticleController::class, 'autocomplete'])
    ->name('articles.autocomplete');

Route::get('/', [PublicArticleController::class, 'index'])
    ->name('home');

Route::get('/articles/{slug}', [PublicArticleController::class, 'show'])
    ->name('articles.show');

Route::get('/search', [PublicArticleController::class, 'search'])
    ->name('articles.search');

Route::get('/categories/{slug}', [CategoryController::class, 'show'])
    ->name('categories.show');