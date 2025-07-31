<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use App\Models\Category;


class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     */
    public function version(Request $request): ?string
    {
        // Force reload when language changes by including locale and timestamp
        return parent::version($request) . '-' . app()->getLocale() . '-' . session('locale');
    }

    /**
     * Define the props that are shared by default.
     */
    public function share(Request $request): array
    {
        // Get current locale - prioritize app locale (set by middleware)
        $locale = app()->getLocale();
        
        return array_merge(parent::share($request), [
            'locale' => $locale, // Current active locale
            'available_locales' => ['en', 'ar', 'ku'], // Available locales
            'categories' => $this->getTranslatedCategories($locale),
            // Add flash messages for language switching feedback
            'flash' => [
                'language_changed' => session('language_changed'),
                'new_language' => session('new_language'),
            ],
            // Add common translations for frontend
            'translations' => [
                'nav' => [
                    'menu' => __('nav.menu'),
                    'search' => __('nav.search'),
                    'categories' => __('nav.categories'),
                    'home' => __('nav.home'),
                ],
                'common' => [
                    'loading' => __('common.loading'),
                    'error' => __('common.error'),
                ]
            ]
        ]);
    }
    
    // HandleInertiaRequests.php - Improved caching
private function getTranslatedCategories($locale)
{
    return Cache::remember("categories_nav_{$locale}_v2", 7200, function () use ($locale) {
        return Category::select(['id', 'slug'])
            ->with([
                'translations' => fn($q) => $q->select(['category_id', 'name'])->where('language', $locale),
                'subCategories' => fn($q) => $q->select(['id', 'category_id', 'slug']),
                'subCategories.translations' => fn($q) => $q->select(['sub_category_id', 'name'])->where('language', $locale),
                'subCategories.articles' => fn($q) => $q->select(['id', 'sub_category_id', 'slug'])->limit(5),
                'subCategories.articles.translations' => fn($q) => $q->select(['article_id', 'title'])->where('language', $locale)
            ])
            ->get()
            ->transform(function ($category) use ($locale) {
                $translation = $category->translations->first();
                $category->translation = $translation;
                $category->name = $translation?->name ?? 'Untitled';

                // Filter subcategories to only those with articles
                $category->sub_categories = $category->subCategories
                    ->filter(fn($sub) => $sub->articles->isNotEmpty())
                    ->transform(function ($sub) use ($locale) {
                        $subTranslation = $sub->translations->first();
                        $sub->translation = $subTranslation;
                        $sub->name = $subTranslation?->name ?? 'Untitled';

                        $sub->articles = $sub->articles->transform(function ($article) use ($locale) {
                            $articleTranslation = $article->translations->first();
                            $article->translation = $articleTranslation;
                            $article->title = $articleTranslation?->title ?? 'Untitled';
                            return $article;
                        });

                        return $sub;
                    })
                    ->values(); // reset keys after filter

                // Return null to remove categories with no subcategories with articles
                if ($category->sub_categories->isEmpty()) {
                    return null;
                }

                unset($category->subCategories);
                return $category;
            })
            ->filter() // remove null categories
            ->values(); // reset keys
    });
}

}