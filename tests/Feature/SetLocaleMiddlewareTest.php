<?php

use App\Models\Movie;
use App\Services\Tmdb\TmdbService;

test('sets locale from Accept-Language header', function () {
    Movie::factory()->create([
        'lang' => TmdbService::mapLocale('pl'),
        'title' => 'Polski Film',
    ]);

    Movie::factory()->create([
        'lang' => TmdbService::mapLocale('en'),
        'title' => 'English Film',
    ]);

    $response = $this->withHeader('Accept-Language', 'pl')
        ->getJson('/api/movies');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'Polski Film');
});

test('sets locale to english when Accept-Language header is missing', function () {
    Movie::factory()->create([
        'lang' => TmdbService::mapLocale('en'),
        'title' => 'English Film',
    ]);

    Movie::factory()->create([
        'lang' => TmdbService::mapLocale('pl'),
        'title' => 'Polski Film',
    ]);

    $response = $this->getJson('/api/movies');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'English Film');
});

test('sets locale to english when unsupported locale is provided', function () {
    Movie::factory()->create([
        'lang' => TmdbService::mapLocale('en'),
        'title' => 'English Film',
    ]);

    Movie::factory()->create([
        'lang' => TmdbService::mapLocale('pl'),
        'title' => 'Polski Film',
    ]);

    $response = $this->withHeader('Accept-Language', 'fr')
        ->getJson('/api/movies');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'English Film');
});

test('parses locale from Accept-Language header with quality values', function () {
    Movie::factory()->create([
        'lang' => TmdbService::mapLocale('pl'),
        'title' => 'Polski Film',
    ]);

    Movie::factory()->create([
        'lang' => TmdbService::mapLocale('en'),
        'title' => 'English Film',
    ]);

    $response = $this->withHeader('Accept-Language', 'pl-PL,pl;q=0.9,en-US;q=0.8,en;q=0.7')
        ->getJson('/api/movies');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'Polski Film');
});

test('parses locale from Accept-Language header with multiple languages', function () {
    Movie::factory()->create([
        'lang' => TmdbService::mapLocale('de'),
        'title' => 'German Film',
    ]);

    Movie::factory()->create([
        'lang' => TmdbService::mapLocale('en'),
        'title' => 'English Film',
    ]);

    $response = $this->withHeader('Accept-Language', 'de,en;q=0.9')
        ->getJson('/api/movies');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'German Film');
});

test('handles uppercase locale in Accept-Language header', function () {
    Movie::factory()->create([
        'lang' => TmdbService::mapLocale('pl'),
        'title' => 'Polski Film',
    ]);

    Movie::factory()->create([
        'lang' => TmdbService::mapLocale('en'),
        'title' => 'English Film',
    ]);

    $response = $this->withHeader('Accept-Language', 'PL')
        ->getJson('/api/movies');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'Polski Film');
});

test('handles locale with region code', function () {
    Movie::factory()->create([
        'lang' => TmdbService::mapLocale('pl'),
        'title' => 'Polski Film',
    ]);

    Movie::factory()->create([
        'lang' => TmdbService::mapLocale('en'),
        'title' => 'English Film',
    ]);

    $response = $this->withHeader('Accept-Language', 'pl-PL')
        ->getJson('/api/movies');

    $response->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'Polski Film');
});
