<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GenreResource;
use App\Models\Genre;
use App\Services\Tmdb\TmdbService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GenreController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->integer('per_page', 15);
        $perPage = min($perPage, 50);

        $genres = Genre::query()
            ->where('lang', TmdbService::mapLocale(app()->getLocale()))
            ->paginate($perPage);

        return GenreResource::collection($genres);
    }

    public function show(int $genreId): GenreResource
    {
        $genre = Genre::query()
            ->where('lang', TmdbService::mapLocale(app()->getLocale()))
            ->findOrFail($genreId);

        return new GenreResource($genre);
    }
}
