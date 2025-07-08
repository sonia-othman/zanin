<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class PublicArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::with('category')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Articles/Index', [
            'articles' => $articles,
        ]);
    }

    public function show($slug)
    {
        $article = Article::with(['category', 'images'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Process the content to fix image paths for inline images
        $article->content = $this->processInlineImages($article->content);

        return Inertia::render('Articles/Show', [
            'article' => $article,
        ]);
    }

    private function processInlineImages($content)
    {
        if (!$content) return '';
        
        // Fix image paths that don't already have /storage/ prefix
        // This handles images uploaded through RichEditor's attachFiles
        $content = preg_replace_callback(
            '/src="(?!http|https|\/storage\/|\/)(.*?)"/',
            function ($matches) {
                // Add /storage/ prefix if the path doesn't already have it
                return 'src="/storage/' . $matches[1] . '"';
            },
            $content
        );
        
        return $content;
    }
}