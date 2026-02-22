<?php

namespace App\Console\Commands;

use App\Jobs\SyncTmdbSeriesJob;
use App\Services\Tmdb\TmdbSeriesService;
use Illuminate\Console\Command;

class SyncTmdbSeries extends Command
{
    protected $signature = 'sync:series';

    protected $description = 'Sync series from tmdb';

    public function handle()
    {
        app(TmdbSeriesService::class)->sync();
        // SyncTmdbSeriesJob::dispatch();

        $this->info('TMDB series sync dispatched successfully.');
    }
}
