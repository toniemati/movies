<?php

namespace App\Services\Tmdb;

class TmdbService
{
    public static function mapLocale(string $locale): string
    {
        return match ($locale) {
            'pl' => 'pl-PL',
            'de' => 'de-DE',
            default => 'en-US',
        };
    }

    public static function supportedLocales(): array
    {
        return ['en', 'pl', 'de'];
    }
}
