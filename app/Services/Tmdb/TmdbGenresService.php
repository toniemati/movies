<?php

namespace App\Services\Tmdb;

use App\Models\Genre;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class TmdbGenresService extends AbstractTmdbService
{
    protected ?int $numOfRecords = null;

    public function sync(): void
    {
        $genres = collect();

        foreach (TmdbLangEnum::cases() as $lang) {
            $langMovieGenres = $this->get(
                "genre/movie/list",
                $lang,
                $this->numOfRecords,
                'genres'
            );

            $langTvGenres = $this->get(
                "genre/tv/list",
                $lang,
                $this->numOfRecords,
                'genres'
            );

            $genres->push(...$langMovieGenres, ...$langTvGenres);
        }

        $this->saveGenres($genres);
    }

    protected function saveGenres(Collection $genres): void
    {
        $genres->unique()->each(function ($genreData) {
            try {
                Genre::updateOrCreate([
                    'tmdb_id' => $genreData['id'],
                    'lang' => $genreData['lang'],
                ], [
                    'name' => $genreData['name']
                ]);
            } catch (Exception $e) {
                Log::error('Failed to save TMDB genre', [
                    'tmdb_id' => $genreData['id'] ?? null,
                    'lang' => $genreData['lang'] ?? null,
                    'exception' => $e->getMessage(),
                ]);
            }
        });
    }
}
