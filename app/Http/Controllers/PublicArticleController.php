<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Laravel\Scout\Builder;
use App\Models\ArticleTranslation;
use Illuminate\Support\Facades\Log;

class PublicArticleController extends Controller
{
// PublicArticleController.php - Optimize index method
public function index(Request $request)
{
    set_time_limit(300);
    $locale = app()->getLocale();
    $search = $request->input('search');

    // Use select to limit columns
    $articles = Article::select(['id', 'slug', 'image', 'sub_category_id', 'views', 'created_at'])
        ->with([
            'translation' => fn($q) => $q->select(['article_id', 'title'])->where('language', $locale),
            'subCategory:id,category_id',
            'subCategory.translation' => fn($q) => $q->select(['sub_category_id', 'name'])->where('language', $locale)
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

    return Inertia::render('Articles/Index', [
        'articles' => $articles,
        'filters' => ['search' => $search],
        'popularArticles' => $this->getPopularArticles($locale),
        'locale' => $locale,
    ]);
}

// Cache popular articles with better key strategy
private function getPopularArticles($locale)
{
    return Cache::remember("popular_articles_{$locale}", 3600, function () use ($locale) {
        return Article::select(['id', 'slug', 'views', 'created_at'])
            ->with(['translation' => fn($q) => $q->select(['article_id', 'title'])->where('language', $locale)])
            ->orderByDesc('views')
            ->take(5)
            ->get();
    });
}

    public function show($slug)
    {
        $locale = app()->getLocale();

        $article = Article::with([
                'translations' => fn($q) => $q->where('language', $locale), 
                'category.translations' => fn($q) => $q->where('language', $locale), 
                'subCategory.translations' => fn($q) => $q->where('language', $locale), 
                'images'
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        // Set the translation and transform the article
        $translation = $article->translations->where('language', $locale)->first();
        $article->translation = $translation;
        $article->title = $translation?->title ?? 'Untitled Article';
        $article->content = $translation?->content ?? '';

        // Set category translation
        if ($article->category && $article->category->translations) {
            $catTranslation = $article->category->translations->where('language', $locale)->first();
            $article->category->translation = $catTranslation;
            $article->category->name = $catTranslation?->name ?? 'Untitled Category';
        }

        // Set subcategory translation
        if ($article->subCategory && $article->subCategory->translations) {
            $subTranslation = $article->subCategory->translations->where('language', $locale)->first();
            $article->subCategory->translation = $subTranslation;
            $article->subCategory->name = $subTranslation?->name ?? 'Untitled Subcategory';
        }

        $article->content = $this->processInlineImages($article->content);
        $article->increment('views');

        return Inertia::render('Articles/Show', [
            'article' => $article,
            'locale' => $locale,
        ]);
    }

    public function search(Request $request)
    {
        $locale = app()->getLocale();
        $search = $request->input('q');

        if (empty($search)) {
            return redirect()->route('home');
        }

        $articles = Article::with(['translations' => fn($q) => $q->where('language', $locale)])
            ->whereHas('translations', function ($q) use ($search, $locale) {
                $q->where('language', $locale)
                  ->where('title', 'like', '%' . $search . '%');
            })
            ->paginate(10);

        // Transform search results
        $articles->getCollection()->transform(function ($article) use ($locale) {
            $translation = $article->translations->where('language', $locale)->first();
            $article->translation = $translation;
            $article->title = $translation?->title ?? 'Untitled Article';
            return $article;
        });

        return Inertia::render('Articles/SearchResults', [
            'articles' => $articles,
            'search' => $search,
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