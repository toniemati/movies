<?php

use App\Models\Genre;
use App\Services\Tmdb\TmdbService;

test('can list genres with default pagination', function () {
    Genre::factory()->count(20)->create([
        'lang' => TmdbService::mapLocale('en'),
    ]);

    $response = $this->withHeader('Accept-Language', 'en')
        ->getJson('/api/genres');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'tmdb_id',
                    'name',
                    'lang',
                ],
            ],
            'links',
            'meta',
        ])
        ->assertJsonCount(15, 'data');
});

test('can list genres with custom per_page parameter', function () {
    Genre::factory()->count(30)->create([
        'lang' => TmdbService::mapLocale('en'),
    ]);

    $response = $this->withHeader('Accept-Language', 'en')
        ->getJson('/api/genres?per_page=10');

    $response->assertOk()
        ->assertJsonCount(10, 'data');
});

test('per_page parameter is limited to maximum 50', function () {
    Genre::factory()->count(100)->create([
        'lang' => TmdbService::mapLocale('en'),
    ]);

    $response = $this->withHeader('Accept-Language', 'en')
        ->getJson('/api/genres?per_page=100');

    $response->assertOk()
        ->assertJsonCount(50, 'data');
});

test('can show a single genre', function () {
    $genre = Genre::factory()->create([
        'lang' => TmdbService::mapLocale('en'),
        'name' => 'Action',
    ]);

    $response = $this->withHeader('Accept-Language', 'en')
        ->getJson("/api/genres/{$genre->id}");

    $response->assertOk()
        ->assertJson([
            'data' => [
                'id' => $genre->id,
                'name' => 'Action',
            ],
        ]);
});

test('returns 404 when genre not found', function () {
    $response = $this->withHeader('Accept-Language', 'en')
        ->getJson('/api/genres/99999');

    $response->assertNotFound();
});

test('filters genres by locale', function () {
    Genre::factory()->count(5)->create([
        'lang' => TmdbService::mapLocale('en'),
        'name' => 'Action',
    ]);

    Genre::factory()->count(5)->create([
        'lang' => TmdbService::mapLocale('pl'),
        'name' => 'Akcja',
    ]);

    $response = $this->withHeader('Accept-Language', 'pl')
        ->getJson('/api/genres');

    $response->assertOk()
        ->assertJsonCount(5, 'data')
        ->assertJsonMissing(['data' => [['name' => 'Action']]]);
});

test('shows only genre with matching locale', function () {
    $englishGenre = Genre::factory()->create([
        'lang' => TmdbService::mapLocale('en'),
        'name' => 'Action',
    ]);

    $polishGenre = Genre::factory()->create([
        'lang' => TmdbService::mapLocale('pl'),
        'name' => 'Akcja',
    ]);

    $response = $this->withHeader('Accept-Language', 'pl')
        ->getJson("/api/genres/{$polishGenre->id}");

    $response->assertOk()
        ->assertJson([
            'data' => [
                'id' => $polishGenre->id,
                'name' => 'Akcja',
            ],
        ]);

    $response = $this->withHeader('Accept-Language', 'pl')
        ->getJson("/api/genres/{$englishGenre->id}");

    $response->assertNotFound();
});

test('defaults to english locale when Accept-Language header is missing', function () {
    Genre::factory()->count(5)->create([
        'lang' => TmdbService::mapLocale('en'),
    ]);

    Genre::factory()->count(5)->create([
        'lang' => TmdbService::mapLocale('pl'),
    ]);

    $response = $this->getJson('/api/genres');

    $response->assertOk()
        ->assertJsonCount(5, 'data');
});

test('defaults to english locale when unsupported locale is provided', function () {
    Genre::factory()->count(5)->create([
        'lang' => TmdbService::mapLocale('en'),
    ]);

    Genre::factory()->count(5)->create([
        'lang' => TmdbService::mapLocale('pl'),
    ]);

    $response = $this->withHeader('Accept-Language', 'fr')
        ->getJson('/api/genres');

    $response->assertOk()
        ->assertJsonCount(5, 'data');
});
