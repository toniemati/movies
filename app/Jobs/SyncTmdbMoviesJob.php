<?php

namespace App\Jobs;

use App\Services\Tmdb\TmdbMoviesService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class SyncTmdbMoviesJob implements ShouldQueue
{
    use Queueable, Dispatchable;

    public function __construct()
    {
        //
    }

    public function handle(): void
    {
        app(TmdbMoviesService::class)->sync();
    }
}
