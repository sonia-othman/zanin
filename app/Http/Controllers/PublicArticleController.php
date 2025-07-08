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

        // Use the processed content attribute or process inline images
        $article->content = $this->processInlineImages($article->content);

        return Inertia::render('Articles/Show', [
            'article' => $article,
        ]);
    }

    private function processInlineImages($content)
    {
        if (!$content) return '';
        
        // Fix image paths for both TinyEditor and RichEditor uploads
        $content = preg_replace_callback(
            '/src="(?!http|https|\/storage\/|\/)(.*?)"/',
            function ($matches) {
                // Add /storage/ prefix if the path doesn't already have it
                return 'src="/storage/' . $matches[1] . '"';
            },
            $content
        );

        // Handle Filament RichEditor attachment format
        $content = preg_replace_callback(
            '/data-trix-attachment="([^"]*)"[^>]*><\/figure>/i',
            function ($matches) {
                // Extract filename from trix attachment data
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