<?php

namespace App\Services\Tmdb;

use App\Models\Movie;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class TmdbMoviesService extends AbstractTmdbService
{
    protected ?int $numOfRecords = 50;

    public function sync(): void
    {
        $movies = collect();

        foreach (TmdbLangEnum::cases() as $lang) {
            $langMovies = $this->get(
                "trending/movie/day",
                $lang,
                $this->numOfRecords
            );

            $movies->push(...$langMovies);
        }

        $this->saveMovies($movies);
    }

    protected function saveMovies(Collection $movies): void
    {
        $movies->each(function ($movieData) {
            try {
                Movie::updateOrCreate([
                    'tmdb_id' => $movieData['id'],
                    'lang' => $movieData['lang'],
                ], [
                    'adult' => $movieData['adult'],
                    'backdrop_path' => $movieData['backdrop_path'],
                    'title' => $movieData['title'],
                    'original_title' => $movieData['original_title'],
                    'overview' => $movieData['overview'],
                    'poster_path' => $movieData['poster_path'],
                    'media_type' => $movieData['media_type'],
                    'original_language' => $movieData['original_language'],
                    'popularity' => $movieData['popularity'],
                    'release_date' => $movieData['release_date'],
                    'video' => $movieData['video'],
                    'vote_average' => $movieData['vote_average'],
                    'vote_count' => $movieData['vote_count'],
                ]);
            } catch (Exception $e) {
                Log::error('Failed to save TMDB movie', [
                    'tmdb_id' => $movieData['id'] ?? null,
                    'lang' => $movieData['lang'] ?? null,
                    'exception' => $e->getMessage(),
                ]);
            }
        });
    }
}
