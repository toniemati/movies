<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SerieResource;
use App\Models\Serie;
use App\Services\Tmdb\TmdbService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SerieController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->integer('per_page', 15);
        $perPage = min($perPage, 50);

        $series = Serie::query()
            ->where('lang', TmdbService::mapLocale(app()->getLocale()))
            ->paginate($perPage);

        return SerieResource::collection($series);
    }

    public function show(int $serieId): SerieResource
    {
        $serie = Serie::query()
            ->where('lang', TmdbService::mapLocale(app()->getLocale()))
            ->findOrFail($serieId);

        return new SerieResource($serie);
    }
}
