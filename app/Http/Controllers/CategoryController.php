<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Article;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
public function show($slug)
{
    $locale = app()->getLocale();

    $category = Category::with(['translation' => fn($q) => $q->where('language', $locale)])->where('slug', $slug)->firstOrFail();

    $subcategoryIds = $category->subCategories()->pluck('id');

    $articles = Article::with(['translation' => fn($q) => $q->where('language', $locale), 'category.translation' => fn($q) => $q->where('language', $locale)])
        ->whereIn('sub_category_id', $subcategoryIds)
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return Inertia::render('Articles/Category', [
        'category' => $category,
        'articles' => $articles,
    ]);
}


}
