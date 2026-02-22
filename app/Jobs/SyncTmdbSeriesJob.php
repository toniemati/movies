<?php

namespace App\Jobs;

use App\Services\Tmdb\TmdbSeriesService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class SyncTmdbSeriesJob implements ShouldQueue
{
    use Queueable, Dispatchable;

    public function __construct()
    {
        //
    }

    public function handle(): void
    {
        app(TmdbSeriesService::class)->sync();
    }
}
