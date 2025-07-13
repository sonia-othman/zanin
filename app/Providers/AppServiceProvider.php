<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Inertia::share([
            'categories' => function () {
                return Category::with(['subCategories.articles'])->get();
            }
        ]);
    }
}
