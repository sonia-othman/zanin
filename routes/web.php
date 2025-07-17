<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicArticleController;
use App\Http\Controllers\CategoryController;
use App\Models\Article;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\SubCategory;
use App\Models\SubCategoryTranslation;
use App\Models\ArticleTranslation;
use Illuminate\Support\Facades\App;

// Debug route - remove after fixing
Route::get('/debug-translations', function () {
    $data = [
        'current_locale' => app()->getLocale(),
        'session_locale' => session('locale'),
        'config_locale' => config('app.locale'),
        'categories' => Category::with('translations')->get(),
        'subcategories' => SubCategory::with('translations')->get(),
        'articles' => Article::with('translations')->take(5)->get(),
        'category_translations' => CategoryTranslation::where('language', 'ku')->get(),
        'subcategory_translations' => SubCategoryTranslation::where('language', 'ku')->get(),
        'article_translations' => ArticleTranslation::where('language', 'ku')->take(5)->get(),
    ];
    
    return response()->json($data);
});

Route::get('/articles/autocomplete', [PublicArticleController::class, 'autocomplete']);
Route::get('/', [PublicArticleController::class, 'index'])->name('home');
Route::get('/articles/{slug}', [PublicArticleController::class, 'show'])->name('articles.show');
Route::get('/search', [PublicArticleController::class, 'search'])->name('articles.search');
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('/lang/{lang}', function ($lang) {
    $available = ['en', 'ar', 'ku'];

    if (in_array($lang, $available)) {
        // Set both session and app locale
        session(['locale' => $lang]);
        App::setLocale($lang);
        
        // Debug logging
        \Log::info('Language changed to: ' . $lang);
        \Log::info('Session locale after change: ' . session('locale'));
        \Log::info('App locale after change: ' . app()->getLocale());
    }

    return redirect()->back();
})->name('change.language');