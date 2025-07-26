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
        
        // Check for URL locale parameter first (from /lang/{lang} route)
        $urlLocale = $request->route('lang');
        
        // Then check session, cookie, and finally config
        $sessionLocale = session('locale');
        $cookieLocale = $request->cookie('locale');
        $configLocale = config('app.locale', 'en');
        
        // Priority: URL > Session > Cookie > Config
        $locale = $urlLocale && in_array($urlLocale, $availableLocales) 
            ? $urlLocale 
            : ($sessionLocale && in_array($sessionLocale, $availableLocales) 
                ? $sessionLocale 
                : ($cookieLocale && in_array($cookieLocale, $availableLocales) 
                    ? $cookieLocale 
                    : $configLocale));
        
        // Ensure the locale is valid
        if (!in_array($locale, $availableLocales)) {
            $locale = 'en'; // fallback to English
        }
        
        // Set the application locale
        App::setLocale($locale);
        
        // Only update session if locale actually changed to prevent session conflicts
        if (!session()->has('locale') || session('locale') !== $locale) {
            // Use put instead of direct session manipulation
            session(['locale' => $locale]);
        }
        
        // Optional: Add locale to view for debugging
        if (config('app.debug')) {
            view()->share('current_locale', $locale);
        }
        
        return $next($request);
    }
}