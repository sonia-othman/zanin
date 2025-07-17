<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Laravel\Scout\Builder;
use App\Models\ArticleTranslation;
use Illuminate\Support\Facades\Log;

class PublicArticleController extends Controller
{
    public function index(Request $request)
    {
        $locale = app()->getLocale();
        $search = $request->input('search');

        // Debug logging
        Log::info('Current locale in controller: ' . $locale);
        Log::info('Session locale: ' . session('locale', 'not set'));
        Log::info('Config app locale: ' . config('app.locale'));

        $articles = Article::with([
                'translation' => fn($q) => $q->where('language', $locale), 
                'subCategory.translation' => fn($q) => $q->where('language', $locale)
            ])
            ->when($search, function ($query) use ($search, $locale) {
                $query->whereHas('translations', function ($q) use ($search, $locale) {
                    $q->where('language', $locale)
                      ->where('title', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::with([
            'translations' => fn($q) => $q->where('language', $locale),
            'subCategories.translations' => fn($q) => $q->where('language', $locale),
            'subCategories.articles.translations' => fn($q) => $q->where('language', $locale)
        ])->get();

        // Transform categories to include the correct translation
        $categories = $categories->map(function ($category) use ($locale) {
            $category->translation = $category->translations->first();
            
            $category->subCategories = $category->subCategories->map(function ($subCategory) use ($locale) {
                $subCategory->translation = $subCategory->translations->first();
                
                $subCategory->articles = $subCategory->articles->map(function ($article) use ($locale) {
                    $article->translation = $article->translations->first();
                    return $article;
                });
                
                return $subCategory;
            });
            
            return $category;
        });

        // Debug: Check if we have Kurdish translations
        $debugCategory = $categories->first();
        if ($debugCategory) {
            Log::info('Category translations:', $debugCategory->translations->toArray());
            Log::info('Category translation for ' . $locale . ':', $debugCategory->translation?->toArray() ?? 'null');
        }

        $popularArticles = Article::with(['translations' => fn($q) => $q->where('language', $locale)])
            ->orderByDesc('views')
            ->take(5)
            ->get()
            ->map(function ($article) {
                $article->translation = $article->translations->first();
                return $article;
            });

        return Inertia::render('Articles/Index', [
            'articles' => $articles,
            'filters' => ['search' => $search],
            'categories' => $categories,
            'popularArticles' => $popularArticles,
            'locale' => $locale,
            'debug' => [
                'session_locale' => session('locale'),
                'app_locale' => app()->getLocale(),
                'config_locale' => config('app.locale'),
            ]
        ]);
    }

    public function show($slug)
    {
        $locale = app()->getLocale();
        Log::info('Show method - Current locale: ' . $locale);

        $article = Article::with([
                'translations' => fn($q) => $q->where('language', $locale), 
                'category.translations' => fn($q) => $q->where('language', $locale), 
                'subCategory.translations' => fn($q) => $q->where('language', $locale), 
                'images'
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        // Set the translation
        $article->translation = $article->translations->first();

        // Debug article translations
        Log::info('Article translations:', $article->translations->toArray());
        Log::info('Article translation for ' . $locale . ':', $article->translation?->toArray() ?? 'null');

        $article->content = $this->processInlineImages($article->content);
        $article->increment('views');

        $categories = Category::with([
            'translations' => fn($q) => $q->where('language', $locale),
            'subCategories.translations' => fn($q) => $q->where('language', $locale),
            'subCategories.articles.translations' => fn($q) => $q->where('language', $locale)
        ])->get();

        // Transform categories to include the correct translation
        $categories = $categories->map(function ($category) use ($locale) {
            $category->translation = $category->translations->first();
            
            $category->subCategories = $category->subCategories->map(function ($subCategory) use ($locale) {
                $subCategory->translation = $subCategory->translations->first();
                
                $subCategory->articles = $subCategory->articles->map(function ($article) use ($locale) {
                    $article->translation = $article->translations->first();
                    return $article;
                });
                
                return $subCategory;
            });
            
            return $category;
        });

        return Inertia::render('Articles/Show', [
            'article' => $article,
            'categories' => $categories,
            'locale' => $locale,
            'debug' => [
                'session_locale' => session('locale'),
                'app_locale' => app()->getLocale(),
                'config_locale' => config('app.locale'),
            ]
        ]);
    }

    public function search(Request $request)
    {
        $locale = app()->getLocale();
        $search = $request->input('q');

        if (empty($search)) {
            return redirect()->route('home');
        }

        $articles = Article::search($search)
            ->paginate(10);

        $categories = Category::with([
            'translations' => fn($q) => $q->where('language', $locale),
            'subCategories.translations' => fn($q) => $q->where('language', $locale),
            'subCategories.articles.translations' => fn($q) => $q->where('language', $locale)
        ])->get();

        // Transform categories to include the correct translation
        $categories = $categories->map(function ($category) use ($locale) {
            $category->translation = $category->translations->first();
            
            $category->subCategories = $category->subCategories->map(function ($subCategory) use ($locale) {
                $subCategory->translation = $subCategory->translations->first();
                
                $subCategory->articles = $subCategory->articles->map(function ($article) use ($locale) {
                    $article->translation = $article->translations->first();
                    return $article;
                });
                
                return $subCategory;
            });
            
            return $category;
        });

        return Inertia::render('Articles/SearchResults', [
            'articles' => $articles,
            'search' => $search,
            'categories' => $categories,
            'locale' => $locale,
        ]);
    }

    public function autocomplete(Request $request)
    {
        $locale = app()->getLocale();
        $search = $request->input('q');

        $suggestions = ArticleTranslation::where('language', $locale)
            ->where('title', 'like', '%' . $search . '%')
            ->take(5)
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->article->slug => $item->title];
            });

        return response()->json($suggestions);
    }

    private function processInlineImages($content)
    {
        if (!$content) return '';
        
        $content = preg_replace_callback(
            '/src="(?!http|https|\/storage\/|\/)(.*?)"/',
            function ($matches) {
                return 'src="/storage/' . $matches[1] . '"';
            },
            $content
        );

        $content = preg_replace_callback(
            '/data-trix-attachment="([^"]*)"[^>]*><\/figure>/i',
            function ($matches) {
                $attachmentData = json_decode(html_entity_decode($matches[1]), true);
                if (isset($attachmentData['url'])) {
                    $url = $attachmentData['url'];
                    if (!str_starts_with($url, 'http') && !str_starts_with($url, '/storage/')) {
                        $url = '/storage/' . $url;
                    }
                    return '<img src="' . $url . '" alt="' . ($attachmentData['filename'] ?? '') . '">';
                }
                return $matches[0];
            },
            $content
        );
        
        return $content;
    }
}