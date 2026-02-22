<?php

namespace App\Console\Commands;

use App\Jobs\SyncTmdbGenresJob;
use App\Services\Tmdb\TmdbGenresService;
use Illuminate\Console\Command;

class SyncTmdbGenres extends Command
{
    protected $signature = 'sync:genres';

    protected $description = 'Sync genres from tmdb';

    public function handle()
    {
        app(TmdbGenresService::class)->sync();
        // SyncTmdbGenresJob::dispatch();

        $this->info('TMDB genres sync dispatched successfully.');
    }
}
