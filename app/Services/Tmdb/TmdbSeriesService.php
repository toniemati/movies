<?php

namespace App\Services\Tmdb;

use App\Models\Serie;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class TmdbSeriesService extends AbstractTmdbService
{
    protected ?int $numOfRecords = 10;

    public function sync(): void
    {
        $series = collect();

        foreach (TmdbLangEnum::cases() as $lang) {
            $langSeries = $this->get(
                "trending/tv/day",
                $lang,
                $this->numOfRecords
            );

            $series->push(...$langSeries);
        }

        $this->saveSeries($series);
    }

    protected function saveSeries(Collection $series): void
    {
        $series->each(function ($serieData) {
            try {
                Serie::updateOrCreate([
                    'tmdb_id' => $serieData['id'],
                    'lang' => $serieData['lang'],
                ], [
                    'adult' => $serieData['adult'],
                    'backdrop_path' => $serieData['backdrop_path'],
                    'name' => $serieData['name'],
                    'original_name' => $serieData['original_name'],
                    'overview' => $serieData['overview'],
                    'poster_path' => $serieData['poster_path'],
                    'media_type' => $serieData['media_type'],
                    'original_language' => $serieData['original_language'],
                    'popularity' => $serieData['popularity'],
                    'first_air_date' => $serieData['first_air_date'],
                    'vote_average' => $serieData['vote_average'],
                    'vote_count' => $serieData['vote_count'],
                ]);
            } catch (Exception $e) {
                Log::error('Failed to save TMDB series', [
                    'tmdb_id' => $serieData['id'] ?? null,
                    'lang' => $serieData['lang'] ?? null,
                    'exception' => $e->getMessage(),
                ]);
            }
        });
    }
}
