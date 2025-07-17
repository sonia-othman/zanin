<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // ✅ Share locale and categories globally with Inertia
        Inertia::share([
            'locale' => fn() => app()->getLocale(),
            'locales' => fn() => [
                'en' => 'English',
                'ar' => 'العربية',
                'ku' => 'کوردی',
            ],
            'categories' => function () {
                $locale = App::getLocale();
                return Category::with([
                    'subCategories.articles.translations' => fn($q) => $q->where('language', $locale),
                    'translations' => fn($q) => $q->where('language', $locale),
                    'subCategories.translations' => fn($q) => $q->where('language', $locale)
                ])->get();
            },
        ]);
    }
}
