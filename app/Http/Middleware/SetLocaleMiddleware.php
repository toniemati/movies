<?php

namespace App\Http\Middleware;

use App\Services\Tmdb\TmdbService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $acceptLanguage = $request->header('Accept-Language', 'en');

        $locale = $this->parseLocale($acceptLanguage);

        if (! in_array($locale, TmdbService::supportedLocales())) {
            $locale = 'en';
        }

        app()->setLocale($locale);

        return $next($request);
    }

    private function parseLocale(string $acceptLanguage): string
    {
        $parts = explode(',', $acceptLanguage);
        $primary = trim($parts[0]);

        $locale = strtolower(substr($primary, 0, 2));

        return $locale;
    }
}
