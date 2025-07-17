<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $sessionLocale = session('locale');
        $configLocale = config('app.locale');
        $currentLocale = $sessionLocale ?? $configLocale;
        
        // Debug logging
        Log::info('SetLocale Middleware:');
        Log::info('- Session locale: ' . ($sessionLocale ?? 'null'));
        Log::info('- Config locale: ' . $configLocale);
        Log::info('- Setting locale to: ' . $currentLocale);
        
        App::setLocale($currentLocale);
        
        Log::info('- App locale after setting: ' . app()->getLocale());
        
        return $next($request);
    }
}