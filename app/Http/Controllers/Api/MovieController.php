<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use App\Services\Tmdb\TmdbService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MovieController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->integer('per_page', 15);
        $perPage = min($perPage, 50);

        $movies = Movie::query()
            ->where('lang', TmdbService::mapLocale(app()->getLocale()))
            ->paginate($perPage);

        return MovieResource::collection($movies);
    }

    public function show(int $movieId): MovieResource
    {
        $movie = Movie::query()
            ->where('lang', TmdbService::mapLocale(app()->getLocale()))
            ->findOrFail($movieId);

        return new MovieResource($movie);
    }
}
