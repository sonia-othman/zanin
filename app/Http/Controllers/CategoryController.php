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
    $category = Category::where('slug', $slug)->firstOrFail();

    // Get all subcategory IDs in this category
    $subcategoryIds = $category->subCategories()->pluck('id');

    // Get articles that have sub_category_id in those IDs
    $articles = Article::with('category')  // Make sure 'category' relation exists (see below)
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
