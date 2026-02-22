<?php

namespace App\Services\Tmdb;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class AbstractTmdbService
{
    protected string $apiUrl = 'https://api.themoviedb.org/3';

    protected ?int $numOfRecords = null;

    protected function get(string $url, TmdbLangEnum $lang, ?int $numOfRecords = null, string $key = 'results'): Collection
    {
        $result = collect();
        $page = 1;

        do {
            $response = $this->fetch(
                $url,
                $lang,
                ['page' => $page]
            );

            $result = $result->merge($response[$key]);

            $page++;
        } while ($numOfRecords && $result->count() < $numOfRecords);

        return $result = $result
            ->take($numOfRecords)
            ->map(function ($item) use ($lang) {
                $item['lang'] = $lang->value;

                return $item;
            });
    }

    protected function fetch(string $url, TmdbLangEnum $lang, array $query = []): array
    {
        try {
            $response = Http::get("{$this->apiUrl}/{$url}", [
                'api_key' => config('tmdb.api_key'),
                'language' => $lang->value,
                ...$query
            ]);

            $json = $response->json();

            if (!$response->ok()) {
                $message = $json['status_message'] ?? "Cannot fetch '$url'";
                Log::error('TMDB request failed', [
                    'url' => $url,
                    'status' => $response->status(),
                    'message' => $message,
                ]);

                throw new Exception($message);
            }

            return $json;
        } catch (Exception $e) {
            Log::error('TMDB request exception', [
                'url' => $url,
                'exception' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
