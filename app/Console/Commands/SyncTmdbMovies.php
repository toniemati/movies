<?php

namespace App\Console\Commands;

use App\Jobs\SyncTmdbMoviesJob;
use App\Services\Tmdb\TmdbMoviesService;
use Illuminate\Console\Command;

class SyncTmdbMovies extends Command
{
    protected $signature = 'sync:movies';

    protected $description = 'Sync movies from tmdb';

    public function handle()
    {
        app(TmdbMoviesService::class)->sync();
        // SyncTmdbMoviesJob::dispatch();

        $this->info('TMDB movies sync dispatched successfully.');
    }
}
