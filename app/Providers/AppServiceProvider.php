<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
     $locale = Session::get('locale', Cookie::get('locale', config('app.locale')));
    App::setLocale($locale);
        
    }
}