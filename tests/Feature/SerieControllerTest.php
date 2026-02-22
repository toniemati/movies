<?php

use App\Models\Serie;
use App\Services\Tmdb\TmdbService;

test('can list series with default pagination', function () {
    Serie::factory()->count(20)->create([
        'lang' => TmdbService::mapLocale('en'),
    ]);

    $response = $this->withHeader('Accept-Language', 'en')
        ->getJson('/api/series');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'original_name',
                    'overview',
                    'poster_path',
                    'backdrop_path',
                    'first_air_date',
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

test('can list series with custom per_page parameter', function () {
    Serie::factory()->count(30)->create([
        'lang' => TmdbService::mapLocale('en'),
    ]);

    $response = $this->withHeader('Accept-Language', 'en')
        ->getJson('/api/series?per_page=10');

    $response->assertOk()
        ->assertJsonCount(10, 'data');
});

test('per_page parameter is limited to maximum 50', function () {
    Serie::factory()->count(100)->create([
        'lang' => TmdbService::mapLocale('en'),
    ]);

    $response = $this->withHeader('Accept-Language', 'en')
        ->getJson('/api/series?per_page=100');

    $response->assertOk()
        ->assertJsonCount(50, 'data');
});

test('can show a single serie', function () {
    $serie = Serie::factory()->create([
        'lang' => TmdbService::mapLocale('en'),
        'name' => 'Test Serie',
    ]);

    $response = $this->withHeader('Accept-Language', 'en')
        ->getJson("/api/series/{$serie->id}");

    $response->assertOk()
        ->assertJson([
            'data' => [
                'id' => $serie->id,
                'name' => 'Test Serie',
            ],
        ]);
});

test('returns 404 when serie not found', function () {
    $response = $this->withHeader('Accept-Language', 'en')
        ->getJson('/api/series/99999');

    $response->assertNotFound();
});

test('filters series by locale', function () {
    Serie::factory()->count(5)->create([
        'lang' => TmdbService::mapLocale('en'),
        'name' => 'English Serie',
    ]);

    Serie::factory()->count(5)->create([
        'lang' => TmdbService::mapLocale('pl'),
        'name' => 'Polish Serie',
    ]);

    $response = $this->withHeader('Accept-Language', 'pl')
        ->getJson('/api/series');

    $response->assertOk()
        ->assertJsonCount(5, 'data')
        ->assertJsonMissing(['data' => [['name' => 'English Serie']]]);
});

test('shows only serie with matching locale', function () {
    $englishSerie = Serie::factory()->create([
        'lang' => TmdbService::mapLocale('en'),
        'name' => 'English Serie',
    ]);

    $polishSerie = Serie::factory()->create([
        'lang' => TmdbService::mapLocale('pl'),
        'name' => 'Polish Serie',
    ]);

    $response = $this->withHeader('Accept-Language', 'pl')
        ->getJson("/api/series/{$polishSerie->id}");

    $response->assertOk()
        ->assertJson([
            'data' => [
                'id' => $polishSerie->id,
                'name' => 'Polish Serie',
            ],
        ]);

    $response = $this->withHeader('Accept-Language', 'pl')
        ->getJson("/api/series/{$englishSerie->id}");

    $response->assertNotFound();
});

test('defaults to english locale when Accept-Language header is missing', function () {
    Serie::factory()->count(5)->create([
        'lang' => TmdbService::mapLocale('en'),
    ]);

    Serie::factory()->count(5)->create([
        'lang' => TmdbService::mapLocale('pl'),
    ]);

    $response = $this->getJson('/api/series');

    $response->assertOk()
        ->assertJsonCount(5, 'data');
});

test('defaults to english locale when unsupported locale is provided', function () {
    Serie::factory()->count(5)->create([
        'lang' => TmdbService::mapLocale('en'),
    ]);

    Serie::factory()->count(5)->create([
        'lang' => TmdbService::mapLocale('pl'),
    ]);

    $response = $this->withHeader('Accept-Language', 'fr')
        ->getJson('/api/series');

    $response->assertOk()
        ->assertJsonCount(5, 'data');
});
