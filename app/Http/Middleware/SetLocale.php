<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $availableLocales = ['en', 'ar', 'ku'];
        $sessionLocale = session('locale');
        $configLocale = config('app.locale', 'en');
        
        // Determine which locale to use (prioritize session)
        $locale = $sessionLocale && in_array($sessionLocale, $availableLocales) 
            ? $sessionLocale 
            : $configLocale;
        
        // Ensure the locale is valid
        if (!in_array($locale, $availableLocales)) {
            $locale = 'en'; // fallback to English
        }
        
        // Set the application locale
        App::setLocale($locale);
        
        // Ensure session stores the correct locale
        if (session('locale') !== $locale) {
            session(['locale' => $locale]);
        }
        
        // Optional: Add locale to view for debugging
        if (config('app.debug')) {
            view()->share('current_locale', $locale);
        }
        
        return $next($request);
    }
}