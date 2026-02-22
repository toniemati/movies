<?php

use App\Models\Movie;
use App\Services\Tmdb\TmdbService;

test('can list movies with default pagination', function () {
    Movie::factory()->count(20)->create([
        'lang' => TmdbService::mapLocale('en'),
    ]);

    $response = $this->withHeader('Accept-Language', 'en')
        ->getJson('/api/movies');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'original_title',
                    'overview',
                    'poster_path',
                    'backdrop_path',
                    'release_date',
                    'vote_average',
                    'vote_count',
                    'popularity',
                    'lang',
                ],
            ],
            'links',
            'meta',
        ])
        ->assertJsonCount(15, 'data');
});

test('can list movies with custom per_page parameter', function () {
    Movie::factory()->count(30)->create([
        'lang' => TmdbService::mapLocale('en'),
    ]);

    $response = $this->withHeader('Accept-Language', 'en')
        ->getJson('/api/movies?per_page=10');

    $response->assertOk()
        ->assertJsonCount(10, 'data');
});

test('per_page parameter is limited to maximum 50', function () {
    Movie::factory()->count(100)->create([
        'lang' => TmdbService::mapLocale('en'),
    ]);

    $response = $this->withHeader('Accept-Language', 'en')
        ->getJson('/api/movies?per_page=100');

    $response->assertOk()
        ->assertJsonCount(50, 'data');
});

test('can show a single movie', function () {
    $movie = Movie::factory()->create([
        'lang' => TmdbService::mapLocale('en'),
        'title' => 'Test Movie',
    ]);

    $response = $this->withHeader('Accept-Language', 'en')
        ->getJson("/api/movies/{$movie->id}");

    $response->assertOk()
        ->assertJson([
            'data' => [
                'id' => $movie->id,
                'title' => 'Test Movie',
            ],
        ]);
});

test('returns 404 when movie not found', function () {
    $response = $this->withHeader('Accept-Language', 'en')
        ->getJson('/api/movies/99999');

    $response->assertNotFound();
});

test('filters movies by locale', function () {
    Movie::factory()->count(5)->create([
        'lang' => TmdbService::mapLocale('en'),
        'title' => 'English Movie',
    ]);

    Movie::factory()->count(5)->create([
        'lang' => TmdbService::mapLocale('pl'),
        'title' => 'Polish Movie',
    ]);

    $response = $this->withHeader('Accept-Language', 'pl')
        ->getJson('/api/movies');

    $response->assertOk()
        ->assertJsonCount(5, 'data')
        ->assertJsonMissing(['data' => [['title' => 'English Movie']]]);
});

test('shows only movie with matching locale', function () {
    $englishMovie = Movie::factory()->create([
        'lang' => TmdbService::mapLocale('en'),
        'title' => 'English Movie',
    ]);

    $polishMovie = Movie::factory()->create([
        'lang' => TmdbService::mapLocale('pl'),
        'title' => 'Polish Movie',
    ]);

    $response = $this->withHeader('Accept-Language', 'pl')
        ->getJson("/api/movies/{$polishMovie->id}");

    $response->assertOk()
        ->assertJson([
            'data' => [
                'id' => $polishMovie->id,
                'title' => 'Polish Movie',
            ],
        ]);

    $response = $this->withHeader('Accept-Language', 'pl')
        ->getJson("/api/movies/{$englishMovie->id}");

    $response->assertNotFound();
});

test('defaults to english locale when Accept-Language header is missing', function () {
    Movie::factory()->count(5)->create([
        'lang' => TmdbService::mapLocale('en'),
    ]);

    Movie::factory()->count(5)->create([
        'lang' => TmdbService::mapLocale('pl'),
    ]);

    $response = $this->getJson('/api/movies');

    $response->assertOk()
        ->assertJsonCount(5, 'data');
});

test('defaults to english locale when unsupported locale is provided', function () {
    Movie::factory()->count(5)->create([
        'lang' => TmdbService::mapLocale('en'),
    ]);

    Movie::factory()->count(5)->create([
        'lang' => TmdbService::mapLocale('pl'),
    ]);

    $response = $this->withHeader('Accept-Language', 'fr')
        ->getJson('/api/movies');

    $response->assertOk()
        ->assertJsonCount(5, 'data');
});
