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
use Tiptap\Editor;
use Tiptap\Extensions\StarterKit;
use App\TiptapExtensions\Image; // <-- Use your custom extension

class PublicArticleController extends Controller
{
// PublicArticleController.php - Optimize index method
public function index(Request $request)
{
    set_time_limit(300);
    $locale = app()->getLocale();
    $search = $request->input('search');

    // Use select to limit columns - now including excerpt
        $articles = Article::select(['id', 'slug', 'image', 'sub_category_id', 'views', 'created_at', 'user_id'])
        ->with([
            'translation' => fn($q) => $q->select(['article_id', 'title', 'excerpt'])->where('language', $locale),
            'subCategory:id,category_id',
            'subCategory.translation' => fn($q) => $q->select(['sub_category_id', 'name'])->where('language', $locale),
            'author:id,name' 
        ])
        ->when($search, function ($query) use ($search, $locale) {
            $query->whereHas('translations', function ($q) use ($search, $locale) {
                $q->where('language', $locale)
                  ->where(function ($q) use ($search) {
                      $q->where('title', 'like', '%' . $search . '%')
                        ->orWhere('excerpt', 'like', '%' . $search . '%');
                  });
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

// Cache popular articles with better key strategy - now including excerpt
private function getPopularArticles($locale)
{
    return Cache::remember("popular_articles_{$locale}", 3600, function () use ($locale) {
        return Article::select(['id', 'slug', 'views', 'created_at'])
            ->with(['translation' => fn($q) => $q->select(['article_id', 'title', 'excerpt'])->where('language', $locale)])
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
        'subCategory:id,category_id', // ✅ must include id and category_id
        'subCategory.translations' => fn($q) => $q->where('language', $locale),
        'subCategory.category:id', // ✅ make sure category is loaded
        'subCategory.category.translations' => fn($q) => $q->where('language', $locale),
        'images',
    ])->where('slug', $slug)->firstOrFail();

    // Assign translation
    $translation = $article->translations->first();
    $article->translation = $translation;
    $article->title = $translation?->title ?? 'Untitled Article';

    // SubCategory
    if ($article->subCategory) {
        $subTrans = $article->subCategory->translations->first();
        $article->subCategory->translation = $subTrans;
        $article->subCategory->name = $subTrans?->name ?? 'No Subcategory Name';

        if ($article->subCategory->category) {
            $catTrans = $article->subCategory->category->translations->first();
            $article->subCategory->category->translation = $catTrans;
            $article->subCategory->category->name = $catTrans?->name ?? 'No Category Name';
        }
    }

    // Content
    $rawContent = $translation?->content ?? '';
    $content = $this->parseJsonContent($rawContent);
    $htmlContent = $this->convertJsonToHtml($content);
    $article->content_html = $htmlContent;

    $article->increment('views');

    return Inertia::render('Articles/Show', [
        'article' => $article->toArray(),
        'locale' => $locale,
    ]);
}




    private function parseJsonContent($rawContent)
    {
        if (empty($rawContent)) {
            return null;
        }

        // If it's already an array, return it
        if (is_array($rawContent)) {
            return $rawContent;
        }

        // If it's an object, convert to array
        if (is_object($rawContent)) {
            return json_decode(json_encode($rawContent), true);
        }

        // If it's a string, try to decode it
        if (is_string($rawContent)) {
            $decoded = json_decode($rawContent, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        return null;
    }

    private function convertJsonToHtml($content)
    {
        if (!$content || !isset($content['content'])) {
            return '';
        }

        $html = '';
        
        foreach ($content['content'] as $block) {
            $html .= $this->renderBlock($block);
        }

        return $html;
    }

    private function renderBlock($block)
    {
        if (!isset($block['type'])) {
            return '';
        }

        $html = '';
        
        switch ($block['type']) {
            case 'paragraph':
                $html .= '<p>';
                if (isset($block['content'])) {
                    foreach ($block['content'] as $element) {
                        $html .= $this->renderInlineElement($element);
                    }
                }
                $html .= '</p>';
                break;

            case 'heading':
                $level = $block['attrs']['level'] ?? 1;
                $html .= "<h{$level}>";
                if (isset($block['content'])) {
                    foreach ($block['content'] as $element) {
                        $html .= $this->renderInlineElement($element);
                    }
                }
                $html .= "</h{$level}>";
                break;

            case 'bulletList':
                $html .= '<ul>';
                if (isset($block['content'])) {
                    foreach ($block['content'] as $listItem) {
                        $html .= $this->renderBlock($listItem);
                    }
                }
                $html .= '</ul>';
                break;

            case 'orderedList':
                $start = isset($block['attrs']['start']) ? ' start="' . $block['attrs']['start'] . '"' : '';
                $html .= "<ol{$start}>";
                if (isset($block['content'])) {
                    foreach ($block['content'] as $listItem) {
                        $html .= $this->renderBlock($listItem);
                    }
                }
                $html .= '</ol>';
                break;

            case 'listItem':
                $html .= '<li>';
                if (isset($block['content'])) {
                    foreach ($block['content'] as $element) {
                        $html .= $this->renderBlock($element);
                    }
                }
                $html .= '</li>';
                break;

            case 'blockquote':
                $html .= '<blockquote>';
                if (isset($block['content'])) {
                    foreach ($block['content'] as $element) {
                        $html .= $this->renderBlock($element);
                    }
                }
                $html .= '</blockquote>';
                break;

            case 'codeBlock':
                $language = $block['attrs']['language'] ?? '';
                $html .= '<pre><code' . ($language ? ' class="language-' . htmlspecialchars($language) . '"' : '') . '>';
                if (isset($block['content'])) {
                    foreach ($block['content'] as $element) {
                        if (isset($element['text'])) {
                            $html .= htmlspecialchars($element['text']);
                        }
                    }
                }
                $html .= '</code></pre>';
                break;

            case 'horizontalRule':
                $html .= '<hr>';
                break;

            case 'image':
                $html .= $this->renderImage($block);
                break;
        }

        return $html;
    }

    private function renderInlineElement($element)
    {
        if (!isset($element['type'])) {
            return '';
        }

        $html = '';

        switch ($element['type']) {
            case 'text':
                $text = $element['text'] ?? '';
                
                // Apply text formatting marks
                if (isset($element['marks'])) {
                    foreach ($element['marks'] as $mark) {
                        switch ($mark['type']) {
                            case 'bold':
                            case 'strong':
                                $text = '<strong>' . $text . '</strong>';
                                break;
                            case 'italic':
                            case 'em':
                                $text = '<em>' . $text . '</em>';
                                break;
                            case 'code':
                                $text = '<code>' . htmlspecialchars($text) . '</code>';
                                break;
                            case 'link':
                                $href = $mark['attrs']['href'] ?? '#';
                                $target = isset($mark['attrs']['target']) ? ' target="' . htmlspecialchars($mark['attrs']['target']) . '"' : '';
                                $text = '<a href="' . htmlspecialchars($href) . '"' . $target . '>' . $text . '</a>';
                                break;
                            case 'underline':
                                $text = '<u>' . $text . '</u>';
                                break;
                            case 'strike':
                                $text = '<s>' . $text . '</s>';
                                break;
                        }
                    }
                } else {
                    $text = htmlspecialchars($text);
                }
                
                $html .= $text;
                break;

            case 'hardBreak':
                $html .= '<br>';
                break;

            case 'image':
                $html .= $this->renderImage($element);
                break;
        }

        return $html;
    }

    private function renderImage($element)
    {
        $attrs = $element['attrs'] ?? [];
        
        $src = $attrs['src'] ?? '';
        $alt = $attrs['alt'] ?? '';
        $title = $attrs['title'] ?? '';
        $width = $attrs['width'] ?? null;
        $height = $attrs['height'] ?? null;
        $class = $attrs['class'] ?? '';
        $style = $attrs['style'] ?? '';

        if (empty($src)) {
            \Log::warning('Image element has no src attribute', ['attrs' => $attrs]);
            return '';
        }

        \Log::info('Rendering image', ['src' => $src, 'alt' => $alt, 'attrs' => $attrs]);

        $attributes = [
            'src="' . htmlspecialchars($src) . '"',
            'alt="' . htmlspecialchars($alt) . '"'
        ];

        if (!empty($title)) {
            $attributes[] = 'title="' . htmlspecialchars($title) . '"';
        }

        if ($width !== null) {
            $attributes[] = 'width="' . htmlspecialchars($width) . '"';
        }

        if ($height !== null) {
            $attributes[] = 'height="' . htmlspecialchars($height) . '"';
        }

        if (!empty($class)) {
            $attributes[] = 'class="' . htmlspecialchars($class) . '"';
        }

        if (!empty($style)) {
            $attributes[] = 'style="' . htmlspecialchars($style) . '"';
        }

        // Add loading="lazy" for better performance
        if (isset($attrs['lazy']) && $attrs['lazy']) {
            $attributes[] = 'loading="lazy"';
        }

        $html = '<img ' . implode(' ', $attributes) . '>';
        
        \Log::info('Generated image HTML', ['html' => $html]);
        
        return $html;
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
                  ->where(function ($q) use ($search) {
                      $q->where('title', 'like', '%' . $search . '%')
                        ->orWhere('excerpt', 'like', '%' . $search . '%')
                        ->orWhere('content', 'like', '%' . $search . '%');
                  });
            })
            ->paginate(10);

        // Transform search results
        $articles->getCollection()->transform(function ($article) use ($locale) {
            $translation = $article->translations->where('language', $locale)->first();
            $article->translation = $translation;
            $article->title = $translation?->title ?? 'Untitled Article';
            $article->excerpt = $translation?->excerpt ?? '';
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
            ->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('excerpt', 'like', '%' . $search . '%');
            })
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