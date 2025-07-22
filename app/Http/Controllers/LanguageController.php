<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class LanguageController extends Controller
{
    public function switch(Request $request, $locale)
    {
        $availableLocales = ['en', 'ar', 'ku'];
        
        // Validate locale
        if (!in_array($locale, $availableLocales)) {
            return redirect()->back()->withErrors(['Invalid language selected']);
        }
        
        // Set the locale in session
        Session::put('locale', $locale);
        Session::save(); // Force save session immediately
        
        // Clear all translation-related caches
        $this->clearTranslationCaches();
        
        Log::info("Language switched to: {$locale}");
        
        // Force a complete page reload by redirecting to home page
        // This ensures the entire Inertia.js app refreshes with new language
        return redirect()->route('home')->with([
            'language_changed' => true,
            'new_language' => $locale
        ]);
    }
    
    private function clearTranslationCaches()
    {
        $locales = ['en', 'ar', 'ku'];
        
        foreach ($locales as $locale) {
            Cache::forget("categories_{$locale}");
            Cache::forget("categories_full_{$locale}");
            Cache::forget("categories_complete_{$locale}");
            Cache::forget("popular_articles_{$locale}");
        }
        
        // Remove cache tagging - not supported by file/array cache drivers
        // If you need to clear all translation caches, list them explicitly above
        
        // Optional: Clear all cache (use with caution in production)
        // Cache::flush();
    }
}