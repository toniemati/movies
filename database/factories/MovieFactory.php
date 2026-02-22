<?php

namespace Database\Factories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'adult' => false,
            'backdrop_path' => '/backdrop' . fake()->uuid() . '.jpg',
            'tmdb_id' => fake()->unique()->numberBetween(1, 999999),
            'title' => fake()->sentence(3),
            'original_title' => fake()->sentence(3),
            'overview' => fake()->paragraph(),
            'poster_path' => '/poster' . fake()->uuid() . '.jpg',
            'media_type' => 'movie',
            'original_language' => 'en',
            'popularity' => fake()->randomFloat(2, 0, 1000),
            'release_date' => fake()->date('Y-m-d'),
            'video' => false,
            'vote_average' => fake()->randomFloat(1, 0, 10),
            'vote_count' => fake()->numberBetween(0, 10000),
            'lang' => 'en-US',
        ];
    }
}
