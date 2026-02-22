<?php

namespace App\Jobs;

use App\Services\Tmdb\TmdbGenresService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class SyncTmdbGenresJob implements ShouldQueue
{
    use Queueable, Dispatchable;

    public function __construct()
    {
        //
    }

    public function handle(): void
    {
        app(TmdbGenresService::class)->sync();
    }
}
