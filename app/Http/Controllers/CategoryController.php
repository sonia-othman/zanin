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

    $subcategoryIds = $category->subCategories()->pluck('id');

    $articles = Article::with('category')  
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
